<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\Database;
use PDO;
use Throwable;

/**
 * Service: In-Platform Video Interviews (Daily.co)
 *
 * - One private Daily room per interview (unguessable name, stored in interview_rooms).
 * - Short-lived meeting tokens per participant: employer = owner, candidate = guest.
 * - Admin controls (platform_settings + room status): enable/disable video, max duration,
 *   screen share, chat, end/reopen rooms, live presence.
 * - Scorecards persist to interview_evaluations (SQLite in dev, MySQL in production).
 *
 * Spec: docs/47-video-interviews.md
 */
class VideoInterviewService
{
    private const API = 'https://api.daily.co/v1';

    private static bool $tablesChecked = false;

    /** Admin-controlled defaults (overridden by platform_settings rows). */
    private const DEFAULT_SETTINGS = [
        'video_enabled'       => true,
        'max_duration_min'    => 60,
        'allow_screenshare'   => true,
        'allow_chat'          => true,
        'join_early_min'      => 15,
    ];

    /* ── Setup ─────────────────────────────────────────────────────────── */

    private static function driver(PDO $pdo): string
    {
        return (string)$pdo->getAttribute(PDO::ATTR_DRIVER_NAME);
    }

    public static function ensureTables(): void
    {
        if (self::$tablesChecked) {
            return;
        }

        try {
            $pdo = Database::getConnection();
            if (self::driver($pdo) === 'sqlite') {
                $pdo->exec("
                    CREATE TABLE IF NOT EXISTS interview_evaluations (
                        id INTEGER PRIMARY KEY AUTOINCREMENT,
                        interview_id INTEGER NOT NULL UNIQUE,
                        technical_score INTEGER DEFAULT 4,
                        problem_solving_score INTEGER DEFAULT 4,
                        communication_score INTEGER DEFAULT 5,
                        culture_score INTEGER DEFAULT 5,
                        overall_recommendation TEXT DEFAULT 'hire',
                        notes TEXT DEFAULT '',
                        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                    );
                ");
                $pdo->exec("
                    CREATE TABLE IF NOT EXISTS interview_rooms (
                        id INTEGER PRIMARY KEY AUTOINCREMENT,
                        interview_id INTEGER NOT NULL UNIQUE,
                        room_name TEXT NOT NULL UNIQUE,
                        room_url TEXT NOT NULL,
                        status TEXT NOT NULL DEFAULT 'open',
                        ended_by TEXT NULL,
                        created_at TEXT NOT NULL,
                        ended_at TEXT NULL
                    );
                ");
                $pdo->exec("
                    CREATE TABLE IF NOT EXISTS interview_room_events (
                        id INTEGER PRIMARY KEY AUTOINCREMENT,
                        interview_id INTEGER NOT NULL,
                        role TEXT NOT NULL,
                        event TEXT NOT NULL,
                        detail TEXT NULL,
                        created_at TEXT NOT NULL
                    );
                ");
                $pdo->exec("
                    CREATE TABLE IF NOT EXISTS platform_settings (
                        setting_key TEXT PRIMARY KEY,
                        setting_value TEXT NOT NULL,
                        updated_at TEXT NOT NULL
                    );
                ");
            }
            // MySQL tables are created by database/schema_mysql.sql.
            self::$tablesChecked = true;
        } catch (Throwable $e) {
            error_log('VideoInterviewService table creation error: ' . $e->getMessage());
        }
    }

    /* ── Admin settings ────────────────────────────────────────────────── */

    public static function settings(): array
    {
        self::ensureTables();
        $settings = self::DEFAULT_SETTINGS;
        try {
            $rows = Database::getConnection()
                ->query("SELECT setting_key, setting_value FROM platform_settings WHERE setting_key LIKE 'video.%'")
                ->fetchAll(PDO::FETCH_KEY_PAIR);
            foreach ($rows as $key => $value) {
                $name = substr((string)$key, 6);
                if (array_key_exists($name, $settings)) {
                    $settings[$name] = is_bool(self::DEFAULT_SETTINGS[$name]) ? $value === '1' : (int)$value;
                }
            }
        } catch (Throwable $e) {
            // defaults
        }
        return $settings;
    }

    public static function saveSettings(array $input): array
    {
        self::ensureTables();
        $clean = [
            'video_enabled'     => !empty($input['video_enabled']),
            'max_duration_min'  => max(15, min(180, (int)($input['max_duration_min'] ?? 60))),
            'allow_screenshare' => !empty($input['allow_screenshare']),
            'allow_chat'        => !empty($input['allow_chat']),
            'join_early_min'    => max(0, min(60, (int)($input['join_early_min'] ?? 15))),
        ];

        $pdo = Database::getConnection();
        $sql = self::driver($pdo) === 'mysql'
            ? 'INSERT INTO platform_settings (setting_key, setting_value, updated_at) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value), updated_at = VALUES(updated_at)'
            : 'INSERT INTO platform_settings (setting_key, setting_value, updated_at) VALUES (?, ?, ?) ON CONFLICT(setting_key) DO UPDATE SET setting_value = excluded.setting_value, updated_at = excluded.updated_at';
        $stmt = $pdo->prepare($sql);
        foreach ($clean as $key => $value) {
            $stmt->execute(['video.' . $key, is_bool($value) ? ($value ? '1' : '0') : (string)$value, date('Y-m-d H:i:s')]);
        }
        return $clean;
    }

    /* ── Daily REST API ────────────────────────────────────────────────── */

    public static function isConfigured(): bool
    {
        return trim((string)($_ENV['DAILY_API_KEY'] ?? '')) !== '';
    }

    /**
     * @return array{status:int, body:array}
     */
    private static function api(string $method, string $path, ?array $payload = null): array
    {
        $ch = curl_init(self::API . $path);
        $headers = ['Authorization: Bearer ' . trim((string)$_ENV['DAILY_API_KEY']), 'Accept: application/json'];
        if ($payload !== null) {
            $headers[] = 'Content-Type: application/json';
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        }
        curl_setopt_array($ch, [
            CURLOPT_CUSTOMREQUEST  => $method,
            CURLOPT_HTTPHEADER     => $headers,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => 15,
        ]);
        $raw = curl_exec($ch);
        $status = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        if ($raw === false) {
            error_log('Daily API transport error: ' . $error);
            return ['status' => 0, 'body' => ['error' => 'transport', 'info' => $error]];
        }
        return ['status' => $status, 'body' => json_decode((string)$raw, true) ?: []];
    }

    /* ── Rooms ─────────────────────────────────────────────────────────── */

    public static function getRoomRecord(int $interviewId): ?array
    {
        self::ensureTables();
        try {
            $stmt = Database::getConnection()->prepare('SELECT * FROM interview_rooms WHERE interview_id = ?');
            $stmt->execute([$interviewId]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row ?: null;
        } catch (Throwable $e) {
            return null;
        }
    }

    /**
     * Make sure a private Daily room exists for this interview and return its record.
     *
     * @return array{ok:bool, error?:string, room?:array}
     */
    private static function ensureRoom(int $interviewId): array
    {
        $record = self::getRoomRecord($interviewId);
        $settings = self::settings();
        $pdo = Database::getConnection();

        if ($record && $record['status'] === 'ended') {
            return ['ok' => false, 'error' => 'This interview room has been closed.'];
        }

        $roomName = $record['room_name'] ?? ('ic-int-' . $interviewId . '-' . bin2hex(random_bytes(5)));
        $properties = [
            'exp'                 => time() + 24 * 3600,
            'eject_at_room_exp'   => true,
            'enable_chat'         => false,
            'enable_screenshare'  => (bool)$settings['allow_screenshare'],
            'enable_prejoin_ui'   => false,
            'start_video_off'     => false,
            'max_participants'    => 4,
        ];

        if ($record) {
            // Keep room properties in line with current admin settings (also revives expired rooms).
            $res = self::api('POST', '/rooms/' . rawurlencode($roomName), ['properties' => $properties]);
            if ($res['status'] === 404) {
                $res = self::api('POST', '/rooms', ['name' => $roomName, 'privacy' => 'private', 'properties' => $properties]);
            }
        } else {
            $res = self::api('POST', '/rooms', ['name' => $roomName, 'privacy' => 'private', 'properties' => $properties]);
        }

        if ($res['status'] < 200 || $res['status'] >= 300 || empty($res['body']['url'])) {
            error_log('Daily room error (' . $res['status'] . '): ' . json_encode($res['body']));
            return ['ok' => false, 'error' => $res['status'] === 401
                ? 'The video service rejected the API key. Please check DAILY_API_KEY.'
                : 'We could not open the video room right now. Please try again in a moment.'];
        }

        if (!$record) {
            $stmt = $pdo->prepare('INSERT INTO interview_rooms (interview_id, room_name, room_url, status, created_at) VALUES (?, ?, ?, ?, ?)');
            $stmt->execute([$interviewId, $roomName, $res['body']['url'], 'open', date('Y-m-d H:i:s')]);
            $record = self::getRoomRecord($interviewId);
        }

        return ['ok' => true, 'room' => $record];
    }

    /**
     * Everything the browser needs to join: room URL + participant token.
     * Never exposes the API key. Employer joins as owner, candidate as guest.
     */
    public static function joinInfo(int $interviewId, string $role, string $displayName): array
    {
        $settings = self::settings();
        $base = ['configured' => self::isConfigured(), 'settings' => $settings];

        if (!$settings['video_enabled']) {
            return $base + ['ok' => false, 'error' => 'Video interviews are temporarily disabled by the InternConnect team.'];
        }
        if (!self::isConfigured()) {
            return $base + ['ok' => false, 'error' => 'The video service is not set up yet.'];
        }

        $room = self::ensureRoom($interviewId);
        if (!$room['ok']) {
            return $base + ['ok' => false, 'error' => $room['error']];
        }

        $isOwner = $role === 'employer';
        $tokenRes = self::api('POST', '/meeting-tokens', ['properties' => [
            'room_name'          => $room['room']['room_name'],
            'user_name'          => mb_substr($displayName, 0, 60),
            'user_id'            => $role . '-' . $interviewId,
            'is_owner'           => $isOwner,
            'exp'                => time() + ((int)$settings['max_duration_min'] + 30) * 60,
            'eject_after_elapsed'=> (int)$settings['max_duration_min'] * 60 + 300,
            'enable_screenshare' => (bool)$settings['allow_screenshare'],
            'start_video_off'    => false,
            'start_audio_off'    => false,
        ]]);

        if ($tokenRes['status'] < 200 || $tokenRes['status'] >= 300 || empty($tokenRes['body']['token'])) {
            error_log('Daily token error (' . $tokenRes['status'] . '): ' . json_encode($tokenRes['body']));
            return $base + ['ok' => false, 'error' => 'We could not create your access pass for this room. Please try again.'];
        }

        self::logEvent($interviewId, $role, 'joined');

        return $base + [
            'ok'    => true,
            'url'   => $room['room']['room_url'],
            'token' => $tokenRes['body']['token'],
            'owner' => $isOwner,
        ];
    }

    /** Close the room for everyone (employer "End for everyone" or admin). */
    public static function endRoom(int $interviewId, string $endedBy): bool
    {
        $record = self::getRoomRecord($interviewId);
        if (!$record) {
            return false;
        }
        if (self::isConfigured()) {
            // Deleting the Daily room disconnects every participant immediately.
            self::api('DELETE', '/rooms/' . rawurlencode($record['room_name']));
        }
        $stmt = Database::getConnection()->prepare('UPDATE interview_rooms SET status = ?, ended_by = ?, ended_at = ? WHERE interview_id = ?');
        $stmt->execute(['ended', $endedBy, date('Y-m-d H:i:s'), $interviewId]);
        self::logEvent($interviewId, $endedBy, 'room_ended');
        return true;
    }

    /** Allow an ended room to be used again (admin). A fresh Daily room is created on next join. */
    public static function reopenRoom(int $interviewId): bool
    {
        $stmt = Database::getConnection()->prepare('UPDATE interview_rooms SET status = ?, ended_by = NULL, ended_at = NULL WHERE interview_id = ?');
        $stmt->execute(['open', $interviewId]);
        self::logEvent($interviewId, 'admin', 'room_reopened');
        return $stmt->rowCount() > 0;
    }

    /** Live participant count per room (Daily presence API). */
    public static function presence(): array
    {
        if (!self::isConfigured()) {
            return [];
        }
        $res = self::api('GET', '/presence');
        if ($res['status'] !== 200) {
            return [];
        }
        $out = [];
        foreach ($res['body'] as $roomName => $participants) {
            if (is_array($participants)) {
                $out[$roomName] = array_map(static fn ($p) => [
                    'name'   => (string)($p['userName'] ?? 'Guest'),
                    'since'  => (string)($p['joinTime'] ?? ''),
                    'owner'  => !empty($p['owner']),
                ], $participants);
            }
        }
        return $out;
    }

    public static function logEvent(int $interviewId, string $role, string $event, string $detail = ''): void
    {
        self::ensureTables();
        try {
            $stmt = Database::getConnection()->prepare('INSERT INTO interview_room_events (interview_id, role, event, detail, created_at) VALUES (?, ?, ?, ?, ?)');
            $stmt->execute([$interviewId, mb_substr($role, 0, 20), mb_substr($event, 0, 40), mb_substr($detail, 0, 255), date('Y-m-d H:i:s')]);
        } catch (Throwable $e) {
            error_log('logEvent error: ' . $e->getMessage());
        }
    }

    /** Rooms + recent activity for the admin console. */
    public static function adminOverview(): array
    {
        self::ensureTables();
        $pdo = Database::getConnection();
        $rooms = [];
        try {
            $rooms = $pdo->query('SELECT * FROM interview_rooms ORDER BY created_at DESC LIMIT 100')->fetchAll(PDO::FETCH_ASSOC);
        } catch (Throwable $e) {
        }
        $events = [];
        try {
            $events = $pdo->query('SELECT * FROM interview_room_events ORDER BY id DESC LIMIT 40')->fetchAll(PDO::FETCH_ASSOC);
        } catch (Throwable $e) {
        }
        $evaluations = [];
        try {
            foreach ($pdo->query('SELECT interview_id, overall_recommendation, technical_score, problem_solving_score, communication_score, culture_score FROM interview_evaluations')->fetchAll(PDO::FETCH_ASSOC) as $row) {
                $evaluations[(int)$row['interview_id']] = $row;
            }
        } catch (Throwable $e) {
        }
        return ['rooms' => $rooms, 'events' => $events, 'evaluations' => $evaluations];
    }

    /* ── Room page data ────────────────────────────────────────────────── */

    /**
     * Get meeting room configuration and participant data
     */
    public static function getRoomDetails(int $interviewId, string $userRole = 'employer'): array
    {
        self::ensureTables();

        $record = self::getRoomRecord($interviewId);

        // Canonical interview details (demo data until interviews are database-backed)
        $details = [
            'id'                  => $interviewId,
            'title'               => 'UI/UX Design Intern — Technical Round',
            'company_name'        => 'Nexura Technologies',
            'company_logo'        => asset('assets/images/internconnect-icon.svg'),
            'candidate_id'        => 1,
            'candidate_name'      => 'Sarah Peters',
            'candidate_role'      => 'UI/UX Design Intern',
            'candidate_avatar'    => asset('assets/images/profile.png'),
            'candidate_school'    => 'University of Lagos (UNILAG)',
            'candidate_gpa'       => '4.65 / 5.0 (First Class Honours)',
            'candidate_duration'  => '6 Months SIWES / IT Placement',
            'interviewer_name'    => 'Tunde Bakare',
            'interviewer_role'    => 'Talent Acquisition & Design Lead',
            'interviewer_avatar'  => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=160&h=160&fit=crop&crop=faces',
            'date'                => 'Oct 26, 2026',
            'time'                => '10:00 AM - 10:45 AM (WAT)',
            'duration_minutes'    => 45,
            'room_status'         => $record['status'] ?? 'open',
            'room_url'            => $record['room_url'] ?? ('https://' . ($_ENV['DAILY_DOMAIN'] ?? 'internconnect') . '.daily.co/nxt-uxin-' . $interviewId),
            'daily_room_url'      => $record['room_url'] ?? ('https://' . ($_ENV['DAILY_DOMAIN'] ?? 'internconnect') . '.daily.co/nxt-uxin-' . $interviewId),
            'user_role'           => $userRole,
            'agenda'              => [
                ['range' => '0-10m',  'text' => 'Introductions and background review with Tunde Bakare.'],
                ['range' => '10-30m', 'text' => 'Oakland banking app case study walkthrough (share your screen).'],
                ['range' => '30-40m', 'text' => 'Technical questions: Figma tokens, auto-layout, design systems.'],
                ['range' => '40-45m', 'text' => 'Your questions for the Nexura design team.'],
            ],
            'portfolio_links'     => [
                ['label' => 'Figma Case Studies', 'url' => 'https://figma.com/@sarahpeters', 'icon' => 'figma'],
                ['label' => 'GitHub Repositories', 'url' => 'https://github.com/sarahpeters', 'icon' => 'github'],
                ['label' => 'Personal Portfolio', 'url' => 'https://sarahpeters.design', 'icon' => 'globe'],
            ],
            'screening_questions' => [
                ['id' => 1, 'question' => 'Can you walk us through your design process for the Oakland banking app project?', 'hint' => 'Focus on user journey mapping, design tokens, and accessibility contrast standards.', 'asked' => false],
                ['id' => 2, 'question' => 'How do you handle developer handoffs and spacing consistency in auto-layout?', 'hint' => 'Listen for 8pt grid system, Figma variables, component variants, and token documentation.', 'asked' => false],
                ['id' => 3, 'question' => 'Tell us about a time you received critical design critique and iterated on feedback.', 'hint' => 'Assess ego, receptiveness, data-driven reasoning, and collaboration with product teams.', 'asked' => false],
                ['id' => 4, 'question' => 'Are you available full-time on-site/hybrid at our Victoria Island office for the 6-month duration?', 'hint' => 'SIWES availability check (Mon-Fri 9AM-5PM starting November 2026).', 'asked' => false],
            ],
            'rubric' => [
                'technical_score'        => 0,
                'problem_solving_score'  => 0,
                'communication_score'    => 0,
                'culture_score'          => 0,
                'overall_recommendation' => '',
                'notes'                  => '',
            ],
            'jenny_ai_tips' => [
                ['title' => 'Use the STAR Method for Questions', 'desc' => 'Situation, Task, Action, Result. Keep your answers concise within 90-120 seconds.'],
                ['title' => 'Highlight Accessibility Standards', 'desc' => 'Mention WCAG colour contrast and comfortable tap targets when discussing UI components.'],
                ['title' => 'Stay Relaxed & Authentic', 'desc' => 'Take a 2-second pause before answering complex questions to collect your thoughts.'],
                ['title' => 'Ask Thoughtful Questions at the End', 'desc' => 'Ask about the team design review workflow and opportunities for SIWES mentorship.'],
            ],
        ];

        $savedRubric = self::getEvaluation($interviewId);
        if (!empty($savedRubric)) {
            $details['rubric'] = array_merge($details['rubric'], $savedRubric);
        }

        return $details;
    }

    /* ── Scorecards ────────────────────────────────────────────────────── */

    public static function saveEvaluation(int $interviewId, array $data): array
    {
        if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
            @session_start();
        }

        self::ensureTables();

        // 0 = not rated yet, 1-5 = star rating
        $score = static fn (string $k): int => max(0, min(5, (int)($data[$k] ?? 0)));
        $tech = $score('technical_score');
        $prob = $score('problem_solving_score');
        $comm = $score('communication_score');
        $cult = $score('culture_score');
        $rec  = in_array($data['overall_recommendation'] ?? '', ['strong_hire', 'hire', 'hold', 'reject'], true)
            ? $data['overall_recommendation']
            : 'hold';
        $notes = mb_substr(trim((string)($data['notes'] ?? '')), 0, 5000);

        $record = [
            'interview_id'           => $interviewId,
            'technical_score'        => $tech,
            'problem_solving_score'  => $prob,
            'communication_score'    => $comm,
            'culture_score'          => $cult,
            'overall_recommendation' => $rec,
            'notes'                  => $notes,
            'updated_at'             => date('Y-m-d H:i:s'),
        ];

        $_SESSION['interview_evaluations'][$interviewId] = $record;

        try {
            $pdo = Database::getConnection();
            $sql = self::driver($pdo) === 'mysql'
                ? 'INSERT INTO interview_evaluations (interview_id, technical_score, problem_solving_score, communication_score, culture_score, overall_recommendation, notes)
                   VALUES (?, ?, ?, ?, ?, ?, ?)
                   ON DUPLICATE KEY UPDATE technical_score = VALUES(technical_score), problem_solving_score = VALUES(problem_solving_score),
                       communication_score = VALUES(communication_score), culture_score = VALUES(culture_score),
                       overall_recommendation = VALUES(overall_recommendation), notes = VALUES(notes)'
                : 'INSERT INTO interview_evaluations (interview_id, technical_score, problem_solving_score, communication_score, culture_score, overall_recommendation, notes)
                   VALUES (?, ?, ?, ?, ?, ?, ?)
                   ON CONFLICT(interview_id) DO UPDATE SET
                       technical_score = excluded.technical_score, problem_solving_score = excluded.problem_solving_score,
                       communication_score = excluded.communication_score, culture_score = excluded.culture_score,
                       overall_recommendation = excluded.overall_recommendation, notes = excluded.notes,
                       updated_at = CURRENT_TIMESTAMP';
            $pdo->prepare($sql)->execute([$interviewId, $tech, $prob, $comm, $cult, $rec, $notes]);
        } catch (Throwable $e) {
            error_log('saveEvaluation error: ' . $e->getMessage());
        }

        return $record;
    }

    public static function getEvaluation(int $interviewId): ?array
    {
        if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
            @session_start();
        }

        self::ensureTables();

        try {
            $stmt = Database::getConnection()->prepare('SELECT * FROM interview_evaluations WHERE interview_id = ?');
            $stmt->execute([$interviewId]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($row) {
                return [
                    'technical_score'        => (int)$row['technical_score'],
                    'problem_solving_score'  => (int)$row['problem_solving_score'],
                    'communication_score'    => (int)$row['communication_score'],
                    'culture_score'          => (int)$row['culture_score'],
                    'overall_recommendation' => (string)$row['overall_recommendation'],
                    'notes'                  => (string)$row['notes'],
                ];
            }
        } catch (Throwable $e) {
            // fallback to session
        }

        return $_SESSION['interview_evaluations'][$interviewId] ?? null;
    }
}
