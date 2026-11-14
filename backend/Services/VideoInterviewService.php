<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\Database;
use PDO;
use Throwable;

/**
 * Service: In-Platform Video Interviews (Daily.co)
 *
 * Every scheduled interview (scheduled_interviews.id) is its own session:
 * - one private Daily room per interview (interview_rooms), short-lived tokens per participant
 *   (employer = owner, candidate = guest), the API key never leaves the server;
 * - timing: candidates can join `join_early_min` before the start (employer/admin any time), the call lasts the interview duration
 *   plus `grace_min`, then the Daily room expires (everyone is disconnected);
 * - employer setup (criteria, questions, recommendation options) drives the in-call scorecard;
 * - files, chat, scorecards, room activity (with approximate location) and an AI report are
 *   stored per interview for history pages and the admin console.
 *
 * Spec: docs/47-video-interviews.md
 */
class VideoInterviewService
{
    private const API = 'https://api.daily.co/v1';

    private static bool $tablesChecked = false;

    /** Admin-controlled defaults (overridden by platform_settings rows, keys "video.*"). */
    private const DEFAULT_SETTINGS = [
        'video_enabled'        => true,
        'allow_screenshare'    => true,
        'allow_chat'           => true,
        'allow_files'          => true,
        'default_duration_min' => 30,
        'grace_min'            => 1,
        'join_early_min'       => 5,
    ];

    public const DEFAULT_CRITERIA = [
        ['key' => 'technical',       'label' => 'Technical competence'],
        ['key' => 'problem_solving', 'label' => 'Problem solving'],
        ['key' => 'communication',   'label' => 'Communication'],
        ['key' => 'culture',         'label' => 'Culture & team fit'],
    ];

    public const DEFAULT_RECOMMENDATIONS = [
        ['key' => 'strong_hire', 'label' => 'Strong hire', 'tone' => 'good'],
        ['key' => 'hire',        'label' => 'Hire',        'tone' => 'good'],
        ['key' => 'hold',        'label' => 'Hold',        'tone' => 'neutral'],
        ['key' => 'reject',      'label' => 'Do not proceed', 'tone' => 'bad'],
    ];

    /* ══════════════════════════════════════════════════════════════════════
       SETUP
       ══════════════════════════════════════════════════════════════════════ */

    private static function pdo(): PDO
    {
        return Database::getConnection();
    }

    private static function driver(PDO $pdo): string
    {
        return (string)$pdo->getAttribute(PDO::ATTR_DRIVER_NAME);
    }

    private static function addColumn(PDO $pdo, string $table, string $column, string $definition): void
    {
        $cols = array_column($pdo->query("PRAGMA table_info({$table})")->fetchAll(PDO::FETCH_ASSOC), 'name');
        if ($cols && !in_array($column, $cols, true)) {
            $pdo->exec("ALTER TABLE {$table} ADD COLUMN {$column} {$definition}");
        }
    }

    public static function ensureTables(): void
    {
        if (self::$tablesChecked) {
            return;
        }

        try {
            // scheduled_interviews is created by CommunicationService.
            CommunicationService::ensureTables();
            $pdo = self::pdo();
            if (self::driver($pdo) === 'sqlite') {
                $pdo->exec("CREATE TABLE IF NOT EXISTS interview_evaluations (
                    id INTEGER PRIMARY KEY AUTOINCREMENT, interview_id INTEGER NOT NULL UNIQUE,
                    technical_score INTEGER DEFAULT 0, problem_solving_score INTEGER DEFAULT 0,
                    communication_score INTEGER DEFAULT 0, culture_score INTEGER DEFAULT 0,
                    overall_recommendation TEXT DEFAULT 'hold', notes TEXT DEFAULT '',
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP)");
                $pdo->exec("CREATE TABLE IF NOT EXISTS interview_rooms (
                    id INTEGER PRIMARY KEY AUTOINCREMENT, interview_id INTEGER NOT NULL UNIQUE,
                    room_name TEXT NOT NULL UNIQUE, room_url TEXT NOT NULL, status TEXT NOT NULL DEFAULT 'open',
                    ended_by TEXT NULL, created_at TEXT NOT NULL, ended_at TEXT NULL, started_at TEXT NULL)");
                $pdo->exec("CREATE TABLE IF NOT EXISTS interview_room_events (
                    id INTEGER PRIMARY KEY AUTOINCREMENT, interview_id INTEGER NOT NULL, role TEXT NOT NULL,
                    event TEXT NOT NULL, detail TEXT NULL, created_at TEXT NOT NULL,
                    ip_masked TEXT NULL, city TEXT NULL, country TEXT NULL, country_code TEXT NULL,
                    lat REAL NULL, lng REAL NULL, device TEXT NULL)");
                $pdo->exec("CREATE TABLE IF NOT EXISTS platform_settings (
                    setting_key TEXT PRIMARY KEY, setting_value TEXT NOT NULL, updated_at TEXT NOT NULL)");
                $pdo->exec("CREATE TABLE IF NOT EXISTS interview_scorecards (
                    interview_id INTEGER PRIMARY KEY, scores TEXT NOT NULL DEFAULT '{}', recommendation TEXT NULL,
                    notes TEXT NULL, asked TEXT NOT NULL DEFAULT '[]', updated_at TEXT NOT NULL)");
                $pdo->exec("CREATE TABLE IF NOT EXISTS interview_files (
                    id INTEGER PRIMARY KEY AUTOINCREMENT, interview_id INTEGER NOT NULL, role TEXT NOT NULL,
                    uploader TEXT NOT NULL, meta TEXT NOT NULL, created_at TEXT NOT NULL)");
                $pdo->exec("CREATE TABLE IF NOT EXISTS interview_chat (
                    id INTEGER PRIMARY KEY AUTOINCREMENT, interview_id INTEGER NOT NULL, role TEXT NOT NULL,
                    sender TEXT NOT NULL, body TEXT NOT NULL, file_id INTEGER NULL, created_at TEXT NOT NULL)");
                $pdo->exec("CREATE TABLE IF NOT EXISTS interview_reports (
                    interview_id INTEGER PRIMARY KEY, summary TEXT NOT NULL, source TEXT NOT NULL, updated_at TEXT NOT NULL)");

                // Columns added after the first release of these tables.
                self::addColumn($pdo, 'interview_rooms', 'started_at', 'TEXT NULL');
                foreach (['ip_masked' => 'TEXT NULL', 'city' => 'TEXT NULL', 'country' => 'TEXT NULL', 'country_code' => 'TEXT NULL', 'lat' => 'REAL NULL', 'lng' => 'REAL NULL', 'device' => 'TEXT NULL'] as $col => $def) {
                    self::addColumn($pdo, 'interview_room_events', $col, $def);
                }
                foreach (['scheduled_at' => 'TEXT NULL', 'duration_min' => 'INTEGER NULL', 'setup_json' => 'TEXT NULL', 'created_by' => 'TEXT NULL'] as $col => $def) {
                    self::addColumn($pdo, 'scheduled_interviews', $col, $def);
                }
            }
            // MySQL tables and columns are defined in database/schema_mysql.sql.
            self::$tablesChecked = true;
        } catch (Throwable $e) {
            error_log('VideoInterviewService table creation error: ' . $e->getMessage());
        }
    }

    /* ══════════════════════════════════════════════════════════════════════
       ADMIN SETTINGS
       ══════════════════════════════════════════════════════════════════════ */

    public static function settings(): array
    {
        self::ensureTables();
        $settings = self::DEFAULT_SETTINGS;
        try {
            $rows = self::pdo()
                ->query("SELECT setting_key, setting_value FROM platform_settings WHERE setting_key LIKE 'video.%'")
                ->fetchAll(PDO::FETCH_KEY_PAIR);
            // Older installs stored the call length as "max_duration_min".
            if (isset($rows['video.max_duration_min']) && !isset($rows['video.default_duration_min'])) {
                $rows['video.default_duration_min'] = $rows['video.max_duration_min'];
            }
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
            'video_enabled'        => !empty($input['video_enabled']),
            'allow_screenshare'    => !empty($input['allow_screenshare']),
            'allow_chat'           => !empty($input['allow_chat']),
            'allow_files'          => !empty($input['allow_files']),
            'default_duration_min' => max(15, min(180, (int)($input['default_duration_min'] ?? 30))),
            'grace_min'            => max(0, min(10, (int)($input['grace_min'] ?? 1))),
            'join_early_min'       => max(0, min(60, (int)($input['join_early_min'] ?? 5))),
        ];

        $pdo = self::pdo();
        $sql = self::driver($pdo) === 'mysql'
            ? 'INSERT INTO platform_settings (setting_key, setting_value, updated_at) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value), updated_at = VALUES(updated_at)'
            : 'INSERT INTO platform_settings (setting_key, setting_value, updated_at) VALUES (?, ?, ?) ON CONFLICT(setting_key) DO UPDATE SET setting_value = excluded.setting_value, updated_at = excluded.updated_at';
        $stmt = $pdo->prepare($sql);
        foreach ($clean as $key => $value) {
            $stmt->execute(['video.' . $key, is_bool($value) ? ($value ? '1' : '0') : (string)$value, date('Y-m-d H:i:s')]);
        }
        return $clean;
    }

    /* ══════════════════════════════════════════════════════════════════════
       INTERVIEW RECORDS, SETUP & TIMING
       ══════════════════════════════════════════════════════════════════════ */

    /** Parse "10/28/2026" + "03:00 PM" (or ISO forms) into a timestamp; null when unknown. */
    public static function parseSchedule(string $date, string $time): ?int
    {
        $date = trim(preg_replace('/\s+/', '', $date) ?? $date);
        $time = trim($time);
        if ($date === '') {
            return null;
        }
        foreach (['m/d/Y h:i A', 'm/d/Y g:i A', 'm/d/Y H:i', 'Y-m-d H:i', 'Y-m-d h:i A', 'Y-m-d g:i A', 'd/m/Y H:i'] as $format) {
            $dt = \DateTime::createFromFormat('!' . $format, $date . ' ' . $time);
            if ($dt !== false) {
                $errors = \DateTime::getLastErrors();
                if (!$errors || ($errors['warning_count'] === 0 && $errors['error_count'] === 0)) {
                    return $dt->getTimestamp();
                }
            }
        }
        return null;
    }

    /** Clean employer setup. Missing parts fall back to defaults. */
    public static function sanitizeSetup(?array $setup): array
    {
        $setup = $setup ?? [];

        $criteria = [];
        foreach ((array)($setup['criteria'] ?? []) as $c) {
            $label = mb_substr(trim(strip_tags((string)(is_array($c) ? ($c['label'] ?? '') : $c))), 0, 60);
            if ($label === '') {
                continue;
            }
            $key = trim(preg_replace('/[^a-z0-9]+/', '_', strtolower($label)) ?? '', '_') ?: 'criterion';
            $base = $key;
            $n = 2;
            while (in_array($key, array_column($criteria, 'key'), true)) {
                $key = $base . '_' . $n++;
            }
            $criteria[] = ['key' => $key, 'label' => $label];
        }
        $criteria = array_slice($criteria ?: self::DEFAULT_CRITERIA, 0, 8);

        $questions = [];
        foreach ((array)($setup['questions'] ?? []) as $i => $q) {
            $text = mb_substr(trim(strip_tags((string)(is_array($q) ? ($q['question'] ?? ($q['text'] ?? '')) : $q))), 0, 300);
            if ($text === '') {
                continue;
            }
            $questions[] = [
                'id'       => count($questions) + 1,
                'question' => $text,
                'hint'     => mb_substr(trim(strip_tags((string)(is_array($q) ? ($q['hint'] ?? '') : ''))), 0, 200),
            ];
        }
        $questions = array_slice($questions, 0, 15);

        $recs = [];
        foreach ((array)($setup['recommendations'] ?? []) as $r) {
            $label = mb_substr(trim(strip_tags((string)(is_array($r) ? ($r['label'] ?? '') : $r))), 0, 30);
            if ($label === '') {
                continue;
            }
            $tone = is_array($r) && in_array($r['tone'] ?? '', ['good', 'neutral', 'bad'], true) ? $r['tone'] : 'neutral';
            $key = trim(preg_replace('/[^a-z0-9]+/', '_', strtolower($label)) ?? '', '_') ?: 'option';
            if (!in_array($key, array_column($recs, 'key'), true)) {
                $recs[] = ['key' => $key, 'label' => $label, 'tone' => $tone];
            }
        }
        if (count($recs) < 2) {
            $recs = self::DEFAULT_RECOMMENDATIONS;
        }

        return ['criteria' => $criteria, 'questions' => $questions, 'recommendations' => array_slice($recs, 0, 5)];
    }

    /** Default questions used when an employer has not written their own. */
    private static function defaultQuestions(string $role): array
    {
        return [
            ['id' => 1, 'question' => 'Walk us through a recent project you are proud of. What was your role?', 'hint' => 'Ownership, clarity, impact.'],
            ['id' => 2, 'question' => 'Tell us about a time you received critical feedback and what you changed.', 'hint' => 'Receptiveness and growth.'],
            ['id' => 3, 'question' => 'Which part of the ' . $role . ' role excites you most, and why?', 'hint' => 'Motivation and role fit.'],
            ['id' => 4, 'question' => 'Are you available for the full internship period and schedule?', 'hint' => 'Availability check.'],
        ];
    }

    /** Normalised interview record, or null when the id does not exist. */
    public static function getInterview(int $id): ?array
    {
        self::ensureTables();
        if ($id <= 0) {
            return null;
        }
        try {
            $stmt = self::pdo()->prepare('SELECT * FROM scheduled_interviews WHERE id = ?');
            $stmt->execute([$id]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Throwable $e) {
            $row = false;
        }
        if (!$row) {
            return null;
        }

        $settings = self::settings();
        $duration = (int)($row['duration_min'] ?? 0);
        if ($duration <= 0) {
            $duration = (int)preg_replace('/\D+/', '', (string)($row['duration'] ?? '')) ?: (int)$settings['default_duration_min'];
        }
        $scheduledAt = !empty($row['scheduled_at']) ? strtotime((string)$row['scheduled_at']) : self::parseSchedule((string)$row['interview_date'], (string)$row['interview_time']);
        $setup = self::sanitizeSetup(json_decode((string)($row['setup_json'] ?? ''), true));
        if (!$setup['questions']) {
            $setup['questions'] = self::defaultQuestions((string)$row['candidate_role']);
        }

        return [
            'id'               => (int)$row['id'],
            'candidate_id'     => (int)$row['candidate_id'],
            'employer_id'      => (int)$row['employer_id'],
            'candidate_name'   => (string)$row['candidate_name'],
            'candidate_role'   => (string)$row['candidate_role'],
            'company_name'     => (string)$row['company_name'],
            'interviewer_name' => (string)$row['interviewer_name'],
            'meeting_type'     => (string)$row['meeting_type'],
            'status'           => (string)$row['status'],
            'date'             => (string)$row['interview_date'],
            'time'             => (string)$row['interview_time'],
            'scheduled_at'     => $scheduledAt ?: null,
            'duration_min'     => max(5, min(240, $duration)),
            'notes'            => (string)($row['notes'] ?? ''),
            'created_by'       => (string)($row['created_by'] ?? 'employer'),
            'setup'            => $setup,
        ];
    }

    /**
     * Ownership: may the signed-in account of $role open interview $id? Admins always; candidates and
     * employers only their own (session candidate_id / employer_id; demo accounts are #1).
     */
    public static function accessibleBy(int $interviewId, string $role): bool
    {
        if ($role === 'admin') {
            return true;
        }
        $interview = self::getInterview($interviewId);
        if (!$interview) {
            return false;
        }
        return $role === 'employer'
            ? (int)$interview['employer_id'] === (int)($_SESSION['employer_id'] ?? 1)
            : (int)$interview['candidate_id'] === (int)($_SESSION['candidate_id'] ?? 1);
    }

    /** Store schedule extras on an interview created by CommunicationService::scheduleInterview(). */
    public static function saveInterviewMeta(int $id, ?int $scheduledAt, int $durationMin, ?array $setup, string $createdBy): void
    {
        self::ensureTables();
        $stmt = self::pdo()->prepare('UPDATE scheduled_interviews SET scheduled_at = ?, duration_min = ?, setup_json = ?, created_by = ? WHERE id = ?');
        $stmt->execute([
            $scheduledAt ? date('Y-m-d H:i:s', $scheduledAt) : null,
            max(15, min(180, $durationMin)),
            json_encode(self::sanitizeSetup($setup)),
            in_array($createdBy, ['employer', 'admin'], true) ? $createdBy : 'employer',
            $id,
        ]);
    }

    public static function setStatus(int $id, string $status, string $by): bool
    {
        $allowed = ['Scheduled', 'Confirmed', 'Ready', 'Completed', 'Cancelled'];
        if (!in_array($status, $allowed, true)) {
            return false;
        }
        self::ensureTables();
        $stmt = self::pdo()->prepare('UPDATE scheduled_interviews SET status = ? WHERE id = ?');
        $stmt->execute([$status, $id]);
        if ($status === 'Cancelled') {
            self::endRoom($id, $by);
        }
        self::logEvent($id, $by, 'status_' . strtolower($status));
        return $stmt->rowCount() > 0;
    }

    /**
     * Join window and call timing (unix seconds).
     * opens_at: scheduled start minus join_early_min (null = no schedule, join any time). Applies to candidates only.
     * started_at: first join of the current session; ends_at = started + duration; hard_end = + grace.
     */
    public static function timing(array $interview, ?array $room = null, ?array $settings = null): array
    {
        $settings = $settings ?? self::settings();
        $scheduled = $interview['scheduled_at'] ?? null;
        $duration = (int)$interview['duration_min'];
        $grace = (int)$settings['grace_min'];
        $started = $room && !empty($room['started_at']) ? strtotime((string)$room['started_at']) : null;
        $ends = $started ? $started + $duration * 60 : null;

        return [
            'scheduled_at' => $scheduled,
            'opens_at'     => $scheduled ? $scheduled - (int)$settings['join_early_min'] * 60 : null,
            'duration_min' => $duration,
            'grace_min'    => $grace,
            'started_at'   => $started,
            'ends_at'      => $ends,
            'hard_end'     => $ends ? $ends + $grace * 60 : null,
        ];
    }

    /* ══════════════════════════════════════════════════════════════════════
       DAILY REST API & ROOMS
       ══════════════════════════════════════════════════════════════════════ */

    public static function isConfigured(): bool
    {
        return trim((string)($_ENV['DAILY_API_KEY'] ?? ''), "\"' ") !== '';
    }

    /** @return array{status:int, body:array} */
    private static function api(string $method, string $path, ?array $payload = null): array
    {
        $ch = curl_init(self::API . $path);
        $headers = ['Authorization: Bearer ' . trim((string)$_ENV['DAILY_API_KEY'], "\"' "), 'Accept: application/json'];
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
            return ['status' => 0, 'body' => ['error' => 'transport']];
        }
        return ['status' => $status, 'body' => json_decode((string)$raw, true) ?: []];
    }

    public static function getRoomRecord(int $interviewId): ?array
    {
        self::ensureTables();
        try {
            $stmt = self::pdo()->prepare('SELECT * FROM interview_rooms WHERE interview_id = ?');
            $stmt->execute([$interviewId]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row ?: null;
        } catch (Throwable $e) {
            return null;
        }
    }

    /**
     * Make sure a private Daily room exists for this interview, expiring at $exp.
     *
     * @return array{ok:bool, error?:string, room?:array}
     */
    private static function ensureRoom(int $interviewId, int $exp): array
    {
        $record = self::getRoomRecord($interviewId);
        $settings = self::settings();

        if ($record && $record['status'] === 'ended') {
            return ['ok' => false, 'error' => 'This interview room has been closed. Ask the employer to schedule a new session.'];
        }

        $roomName = $record['room_name'] ?? ('ic-int-' . $interviewId . '-' . bin2hex(random_bytes(5)));
        $properties = [
            'exp'                => $exp,
            'eject_at_room_exp'  => true,
            'enable_chat'        => false,
            'enable_screenshare' => (bool)$settings['allow_screenshare'],
            'enable_prejoin_ui'  => false,
            'start_video_off'    => false,
            'max_participants'   => 4,
        ];

        if ($record) {
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
            $stmt = self::pdo()->prepare('INSERT INTO interview_rooms (interview_id, room_name, room_url, status, created_at) VALUES (?, ?, ?, ?, ?)');
            $stmt->execute([$interviewId, $roomName, $res['body']['url'], 'open', date('Y-m-d H:i:s')]);
            $record = self::getRoomRecord($interviewId);
        }

        return ['ok' => true, 'room' => $record];
    }

    /**
     * Everything the browser needs to join: room URL + participant token + timing.
     */
    public static function joinInfo(int $interviewId, string $role, string $displayName): array
    {
        $settings = self::settings();
        $base = ['configured' => self::isConfigured(), 'settings' => $settings];

        if (!$settings['video_enabled']) {
            return $base + ['ok' => false, 'code' => 'disabled', 'error' => 'Video interviews are temporarily disabled by the InternConnect team.'];
        }
        if (!self::isConfigured()) {
            return $base + ['ok' => false, 'code' => 'not_configured', 'error' => 'The video service is not set up yet.'];
        }

        $interview = self::getInterview($interviewId);
        if (!$interview) {
            return $base + ['ok' => false, 'code' => 'not_found', 'error' => 'We could not find this interview.'];
        }
        if ($interview['status'] === 'Cancelled') {
            return $base + ['ok' => false, 'code' => 'cancelled', 'error' => 'This interview was cancelled.'];
        }

        $now = time();
        $room = self::getRoomRecord($interviewId);

        // The host can start a new session in a room they ended themselves; admin-closed rooms stay closed.
        // Admins can start any room, including one that was closed.
        if ($room && $room['status'] === 'ended'
            && ($role === 'admin' || ($role === 'employer' && ($room['ended_by'] ?? '') !== 'admin'))) {
            self::reopenRoom($interviewId, $role);
            $room = self::getRoomRecord($interviewId);
        }

        $timing = self::timing($interview, $room, $settings);
        // Only the candidate waits for the join window; the employer and admin can open the room any time to prepare.
        if ($role === 'candidate' && $timing['opens_at'] && $now < $timing['opens_at']) {
            return $base + ['ok' => false, 'code' => 'too_early', 'opens_at' => $timing['opens_at'] * 1000,
                'error' => 'The room opens ' . (int)$settings['join_early_min'] . ' minutes before the interview starts.'];
        }

        $sessionOver = $timing['hard_end'] && $now >= $timing['hard_end'];
        if ($sessionOver && $role === 'candidate') {
            return $base + ['ok' => false, 'code' => 'finished', 'error' => 'This interview has finished.'];
        }
        $startsNow = !$timing['started_at'] || $sessionOver;
        // Hosts may open the room early to prepare; the interview clock still starts at the scheduled time.
        $scheduled = (int)($interview['scheduled_at'] ?? 0);
        $startAt = ($startsNow && !$sessionOver && $scheduled > $now) ? $scheduled : $now;
        $projectedEnd = $startsNow ? $startAt + ($interview['duration_min'] + $timing['grace_min']) * 60 : $timing['hard_end'];

        $ensured = self::ensureRoom($interviewId, $projectedEnd + 60);
        if (!$ensured['ok']) {
            return $base + ['ok' => false, 'code' => 'room_error', 'error' => $ensured['error']];
        }
        if ($startsNow) {
            self::pdo()->prepare('UPDATE interview_rooms SET started_at = ? WHERE interview_id = ?')->execute([date('Y-m-d H:i:s', $startAt), $interviewId]);
            $room = self::getRoomRecord($interviewId);
            $timing = self::timing($interview, $room, $settings);
        }

        $tokenRes = self::api('POST', '/meeting-tokens', ['properties' => [
            'room_name'          => $ensured['room']['room_name'],
            'user_name'          => mb_substr($displayName, 0, 60),
            'user_id'            => $role . '-' . $interviewId,
            'is_owner'           => $role !== 'candidate',
            'exp'                => (int)$timing['hard_end'] + 120,
            'enable_screenshare' => (bool)$settings['allow_screenshare'],
            'start_video_off'    => false,
            'start_audio_off'    => false,
        ]]);

        if ($tokenRes['status'] < 200 || $tokenRes['status'] >= 300 || empty($tokenRes['body']['token'])) {
            error_log('Daily token error (' . $tokenRes['status'] . '): ' . json_encode($tokenRes['body']));
            return $base + ['ok' => false, 'code' => 'token_error', 'error' => 'We could not create your access pass for this room. Please try again.'];
        }

        self::logEvent($interviewId, $role, 'joined', $displayName, true);
        if (in_array($interview['status'], ['Scheduled', 'Confirmed', 'Ready'], true)) {
            self::pdo()->prepare("UPDATE scheduled_interviews SET status = 'In progress' WHERE id = ?")->execute([$interviewId]);
        }

        return $base + [
            'ok'        => true,
            'url'       => $ensured['room']['room_url'],
            'token'     => $tokenRes['body']['token'],
            'owner'     => $role !== 'candidate',
            'serverNow' => $now * 1000,
            'endsAt'    => $timing['ends_at'] * 1000,
            'hardEnd'   => $timing['hard_end'] * 1000,
        ];
    }

    /** Close the room for everyone (employer "End for everyone", admin, or cancellation). */
    public static function endRoom(int $interviewId, string $endedBy): bool
    {
        $record = self::getRoomRecord($interviewId);
        if (!$record) {
            return true; // never opened or already removed
        }
        if (self::isConfigured()) {
            self::api('DELETE', '/rooms/' . rawurlencode($record['room_name']));
        }
        self::pdo()->prepare('UPDATE interview_rooms SET status = ?, ended_by = ?, ended_at = ? WHERE interview_id = ?')
            ->execute(['ended', $endedBy, date('Y-m-d H:i:s'), $interviewId]);
        try {
            self::pdo()->prepare("UPDATE scheduled_interviews SET status = 'Completed' WHERE id = ? AND status = 'In progress'")->execute([$interviewId]);
        } catch (Throwable $e) {
        }
        self::logEvent($interviewId, $endedBy, 'room_ended');
        return true;
    }

    /** Allow an ended room to be used again. A fresh Daily room and timer start on the next join. */
    public static function reopenRoom(int $interviewId, string $by = 'admin'): bool
    {
        $stmt = self::pdo()->prepare('UPDATE interview_rooms SET status = ?, ended_by = NULL, ended_at = NULL, started_at = NULL WHERE interview_id = ?');
        $stmt->execute(['open', $interviewId]);
        self::logEvent($interviewId, $by, 'room_reopened');
        return $stmt->rowCount() > 0;
    }

    /** Live participants per Daily room name. */
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
                    'name'  => (string)($p['userName'] ?? 'Guest'),
                    'role'  => str_starts_with((string)($p['userId'] ?? ''), 'employer') ? 'employer' : 'candidate',
                    'since' => (string)($p['joinTime'] ?? ''),
                ], $participants);
            }
        }
        return $out;
    }

    /* ══════════════════════════════════════════════════════════════════════
       ACTIVITY LOG (with approximate location)
       ══════════════════════════════════════════════════════════════════════ */

    public static function logEvent(int $interviewId, string $role, string $event, string $detail = '', bool $withGeo = false): void
    {
        self::ensureTables();
        $geo = null;
        $ipMasked = null;
        $device = null;
        if ($withGeo) {
            $ip = GeoService::clientIp();
            $ipMasked = GeoService::maskIp($ip);
            $device = GeoService::deviceLabel((string)($_SERVER['HTTP_USER_AGENT'] ?? ''));
            $geo = GeoService::lookup($ip);
        }
        try {
            self::pdo()->prepare('INSERT INTO interview_room_events (interview_id, role, event, detail, created_at, ip_masked, city, country, country_code, lat, lng, device) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)')
                ->execute([
                    $interviewId, mb_substr($role, 0, 20), mb_substr($event, 0, 40), mb_substr($detail, 0, 255), date('Y-m-d H:i:s'),
                    $ipMasked, $geo['city'] ?? null, $geo['country'] ?? null, $geo['code'] ?? null, $geo['lat'] ?? null, $geo['lng'] ?? null, $device,
                ]);
        } catch (Throwable $e) {
            error_log('logEvent error: ' . $e->getMessage());
        }
    }

    /**
     * Display label + badge tone for an activity event (shared by reports and the admin console).
     * Tones map to the design palette: green = joined/positive status, slate = neutral,
     * indigo = in-call actions, sky = files, violet = scheduling, amber = warnings, red = ended/cancelled.
     */
    public static function eventMeta(string $event): array
    {
        $map = [
            'joined'               => ['Joined', 'green'],
            'left'                 => ['Left', 'slate'],
            'screen_share_started' => ['Shared screen', 'indigo'],
            'screen_share_stopped' => ['Stopped sharing', 'slate'],
            'file_shared'          => ['Shared a file', 'sky'],
            'scheduled'            => ['Scheduled', 'violet'],
            'room_ended'           => ['Ended the room', 'red'],
            'room_reopened'        => ['Reopened', 'indigo'],
            'settings_updated'     => ['Changed settings', 'slate'],
            'time_warning'         => ['Time warning', 'amber'],
            'status_ready'         => ['Marked ready', 'green'],
            'status_cancelled'     => ['Cancelled', 'red'],
            'status_scheduled'     => ['Rescheduled', 'violet'],
            'status_completed'     => ['Completed', 'slate'],
            'status_confirmed'     => ['Confirmed', 'green'],
        ];
        return $map[$event] ?? [ucfirst(str_replace('_', ' ', $event)), 'slate'];
    }

    public static function events(int $interviewId = 0, int $limit = 60): array
    {
        self::ensureTables();
        try {
            if ($interviewId > 0) {
                $stmt = self::pdo()->prepare('SELECT * FROM interview_room_events WHERE interview_id = ? ORDER BY id ASC');
                $stmt->execute([$interviewId]);
            } else {
                // Console feed: platform-wide events plus sessions created through the scheduling flow.
                $stmt = self::pdo()->prepare('SELECT e.* FROM interview_room_events e LEFT JOIN scheduled_interviews s ON s.id = e.interview_id WHERE e.interview_id = 0 OR s.created_by IS NOT NULL ORDER BY e.id DESC LIMIT ?');
                $stmt->bindValue(1, $limit, PDO::PARAM_INT);
                $stmt->execute();
            }
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Throwable $e) {
            return [];
        }
    }

    /* ══════════════════════════════════════════════════════════════════════
       FILES & CHAT
       ══════════════════════════════════════════════════════════════════════ */

    /** @return array{ok:bool, error?:string, file?:array} */
    public static function addFile(int $interviewId, string $role, string $uploader, ?array $upload): array
    {
        if (!self::settings()['allow_files']) {
            return ['ok' => false, 'error' => 'File sharing is turned off for interviews.'];
        }
        if (!self::getInterview($interviewId)) {
            return ['ok' => false, 'error' => 'We could not find this interview.'];
        }
        $stored = UploadStorage::store($upload, 'interview_file');
        if (!$stored['ok']) {
            return ['ok' => false, 'error' => $stored['error']];
        }
        $pdo = self::pdo();
        $pdo->prepare('INSERT INTO interview_files (interview_id, role, uploader, meta, created_at) VALUES (?, ?, ?, ?, ?)')
            ->execute([$interviewId, $role, mb_substr($uploader, 0, 80), json_encode($stored['meta']), date('Y-m-d H:i:s')]);
        $fileId = (int)$pdo->lastInsertId();
        self::logEvent($interviewId, $role, 'file_shared', $stored['meta']['name']);
        return ['ok' => true, 'file' => self::describeFile($fileId, $role, $uploader, $stored['meta'], date('Y-m-d H:i:s'))];
    }

    private static function describeFile(int $id, string $role, string $uploader, array $meta, string $createdAt): array
    {
        return [
            'id'         => $id,
            'role'       => $role,
            'uploader'   => $uploader,
            'name'       => (string)$meta['name'],
            'ext'        => (string)$meta['ext'],
            'size_label' => (int)$meta['size'] < 102400 ? max(1, (int)round(((int)$meta['size']) / 1024)) . ' KB' : number_format(((int)$meta['size']) / 1048576, 1) . ' MB',
            'created_at' => $createdAt,
        ];
    }

    public static function files(int $interviewId): array
    {
        self::ensureTables();
        try {
            $stmt = self::pdo()->prepare('SELECT * FROM interview_files WHERE interview_id = ? ORDER BY id ASC');
            $stmt->execute([$interviewId]);
            $out = [];
            foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
                $meta = json_decode((string)$row['meta'], true) ?: [];
                if (UploadStorage::path($meta) !== null) {
                    $out[] = self::describeFile((int)$row['id'], (string)$row['role'], (string)$row['uploader'], $meta, (string)$row['created_at']);
                }
            }
            return $out;
        } catch (Throwable $e) {
            return [];
        }
    }

    public static function fileMeta(int $interviewId, int $fileId): ?array
    {
        self::ensureTables();
        $stmt = self::pdo()->prepare('SELECT meta FROM interview_files WHERE id = ? AND interview_id = ?');
        $stmt->execute([$fileId, $interviewId]);
        $meta = $stmt->fetchColumn();
        return $meta ? (json_decode((string)$meta, true) ?: null) : null;
    }

    public static function addChat(int $interviewId, string $role, string $sender, string $body, ?int $fileId = null): void
    {
        self::ensureTables();
        $body = mb_substr(trim($body), 0, 1000);
        if ($body === '' && !$fileId) {
            return;
        }
        self::pdo()->prepare('INSERT INTO interview_chat (interview_id, role, sender, body, file_id, created_at) VALUES (?, ?, ?, ?, ?, ?)')
            ->execute([$interviewId, $role, mb_substr($sender, 0, 80), $body, $fileId, date('Y-m-d H:i:s')]);
    }

    public static function chat(int $interviewId): array
    {
        self::ensureTables();
        $stmt = self::pdo()->prepare('SELECT role, sender, body, file_id, created_at FROM interview_chat WHERE interview_id = ? ORDER BY id ASC');
        $stmt->execute([$interviewId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /* ══════════════════════════════════════════════════════════════════════
       SCORECARDS
       ══════════════════════════════════════════════════════════════════════ */

    public static function saveScorecard(int $interviewId, array $input): array
    {
        self::ensureTables();
        $interview = self::getInterview($interviewId);
        $setup = $interview['setup'] ?? self::sanitizeSetup(null);

        $scores = [];
        foreach ($setup['criteria'] as $c) {
            $scores[$c['key']] = max(0, min(5, (int)($input['scores'][$c['key']] ?? 0)));
        }
        $recKeys = array_column($setup['recommendations'], 'key');
        $rec = in_array($input['recommendation'] ?? '', $recKeys, true) ? (string)$input['recommendation'] : null;
        $notes = mb_substr(trim((string)($input['notes'] ?? '')), 0, 5000);
        $asked = array_values(array_filter(array_map('intval', (array)($input['asked'] ?? [])), static fn ($v) => $v > 0));

        $pdo = self::pdo();
        $sql = self::driver($pdo) === 'mysql'
            ? 'INSERT INTO interview_scorecards (interview_id, scores, recommendation, notes, asked, updated_at) VALUES (?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE scores = VALUES(scores), recommendation = VALUES(recommendation), notes = VALUES(notes), asked = VALUES(asked), updated_at = VALUES(updated_at)'
            : 'INSERT INTO interview_scorecards (interview_id, scores, recommendation, notes, asked, updated_at) VALUES (?, ?, ?, ?, ?, ?) ON CONFLICT(interview_id) DO UPDATE SET scores = excluded.scores, recommendation = excluded.recommendation, notes = excluded.notes, asked = excluded.asked, updated_at = excluded.updated_at';
        $pdo->prepare($sql)->execute([$interviewId, json_encode($scores), $rec, $notes, json_encode($asked), date('Y-m-d H:i:s')]);

        return ['scores' => $scores, 'recommendation' => $rec, 'notes' => $notes, 'asked' => $asked];
    }

    public static function getScorecard(int $interviewId): ?array
    {
        self::ensureTables();
        try {
            $stmt = self::pdo()->prepare('SELECT * FROM interview_scorecards WHERE interview_id = ?');
            $stmt->execute([$interviewId]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Throwable $e) {
            $row = false;
        }
        if (!$row) {
            return null;
        }
        return [
            'scores'         => json_decode((string)$row['scores'], true) ?: [],
            'recommendation' => $row['recommendation'],
            'notes'          => (string)$row['notes'],
            'asked'          => json_decode((string)$row['asked'], true) ?: [],
            'updated_at'     => (string)$row['updated_at'],
        ];
    }

    public static function scoreAverage(?array $scorecard): ?float
    {
        $vals = array_filter(array_map('intval', (array)($scorecard['scores'] ?? [])));
        return $vals ? round(array_sum($vals) / count($vals), 1) : null;
    }

    /* Legacy four-criteria evaluation API (kept for existing callers and tests). */

    public static function saveEvaluation(int $interviewId, array $data): array
    {
        if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
            @session_start();
        }
        self::ensureTables();

        $score = static fn (string $k): int => max(0, min(5, (int)($data[$k] ?? 0)));
        $record = [
            'interview_id'           => $interviewId,
            'technical_score'        => $score('technical_score'),
            'problem_solving_score'  => $score('problem_solving_score'),
            'communication_score'    => $score('communication_score'),
            'culture_score'          => $score('culture_score'),
            'overall_recommendation' => in_array($data['overall_recommendation'] ?? '', ['strong_hire', 'hire', 'hold', 'reject'], true) ? $data['overall_recommendation'] : 'hold',
            'notes'                  => mb_substr(trim((string)($data['notes'] ?? '')), 0, 5000),
            'updated_at'             => date('Y-m-d H:i:s'),
        ];
        $_SESSION['interview_evaluations'][$interviewId] = $record;

        try {
            $pdo = self::pdo();
            $sql = self::driver($pdo) === 'mysql'
                ? 'INSERT INTO interview_evaluations (interview_id, technical_score, problem_solving_score, communication_score, culture_score, overall_recommendation, notes) VALUES (?, ?, ?, ?, ?, ?, ?)
                   ON DUPLICATE KEY UPDATE technical_score = VALUES(technical_score), problem_solving_score = VALUES(problem_solving_score), communication_score = VALUES(communication_score), culture_score = VALUES(culture_score), overall_recommendation = VALUES(overall_recommendation), notes = VALUES(notes)'
                : 'INSERT INTO interview_evaluations (interview_id, technical_score, problem_solving_score, communication_score, culture_score, overall_recommendation, notes) VALUES (?, ?, ?, ?, ?, ?, ?)
                   ON CONFLICT(interview_id) DO UPDATE SET technical_score = excluded.technical_score, problem_solving_score = excluded.problem_solving_score, communication_score = excluded.communication_score, culture_score = excluded.culture_score, overall_recommendation = excluded.overall_recommendation, notes = excluded.notes, updated_at = CURRENT_TIMESTAMP';
            $pdo->prepare($sql)->execute([$interviewId, $record['technical_score'], $record['problem_solving_score'], $record['communication_score'], $record['culture_score'], $record['overall_recommendation'], $record['notes']]);
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
            $stmt = self::pdo()->prepare('SELECT * FROM interview_evaluations WHERE interview_id = ?');
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
        }
        return $_SESSION['interview_evaluations'][$interviewId] ?? null;
    }

    /* ══════════════════════════════════════════════════════════════════════
       ROOM PAGE DATA
       ══════════════════════════════════════════════════════════════════════ */

    public static function getRoomDetails(int $interviewId, string $userRole = 'employer'): array
    {
        self::ensureTables();
        $interview = self::getInterview($interviewId);
        $record = self::getRoomRecord($interviewId);
        $settings = self::settings();
        $dailyDomain = trim((string)($_ENV['DAILY_DOMAIN'] ?? 'internconnect'), "\"' ") ?: 'internconnect';

        $name   = $interview['candidate_name'] ?? 'Sarah Peters';
        $role   = $interview['candidate_role'] ?? 'UI/UX Design Intern';
        $setup  = $interview['setup'] ?? self::sanitizeSetup(['questions' => self::defaultQuestions($role)]);
        $timing = $interview ? self::timing($interview, $record, $settings) : null;
        $durationMin = $interview['duration_min'] ?? (int)$settings['default_duration_min'];

        $timeLabel = $interview && $interview['scheduled_at']
            ? date('M j, g:i A', $interview['scheduled_at']) . ' - ' . date('g:i A', $interview['scheduled_at'] + $durationMin * 60)
            : (trim(($interview['date'] ?? '') . ' ' . ($interview['time'] ?? '')) ?: 'Flexible time');

        $details = [
            'id'                  => $interviewId,
            'exists'              => $interview !== null,
            'title'               => $role . ' interview',
            'company_name'        => $interview['company_name'] ?? 'Nexura Technologies',
            'candidate_id'        => $interview['candidate_id'] ?? 1,
            'candidate_name'      => $name,
            'candidate_role'      => $role,
            'candidate_avatar'    => asset('assets/images/profile.png'),
            'candidate_school'    => 'University of Lagos (UNILAG)',
            'candidate_gpa'       => '4.65 / 5.0 (First Class Honours)',
            'candidate_duration'  => '6 Months SIWES / IT Placement',
            'interviewer_name'    => $interview['interviewer_name'] ?? 'Tunde Bakare',
            'interviewer_role'    => 'Talent Acquisition & Design Lead',
            'time'                => $timeLabel,
            'status'              => $interview['status'] ?? 'Scheduled',
            'duration_minutes'    => $durationMin,
            'timing'              => $timing,
            'room_status'         => $record['status'] ?? 'open',
            'room_ended_by'       => $record['ended_by'] ?? '',
            'daily_room_url'      => $record['room_url'] ?? ('https://' . $dailyDomain . '.daily.co/ic-int-' . $interviewId),
            'user_role'           => $userRole,
            'setup'               => $setup,
            'screening_questions' => $setup['questions'],
            'agenda'              => self::agenda($durationMin, (string)($interview['interviewer_name'] ?? 'the interviewer')),
            'portfolio_links'     => [
                ['label' => 'Figma Case Studies', 'url' => 'https://figma.com/@sarahpeters', 'icon' => 'figma'],
                ['label' => 'GitHub Repositories', 'url' => 'https://github.com/sarahpeters', 'icon' => 'github'],
                ['label' => 'Personal Portfolio', 'url' => 'https://sarahpeters.design', 'icon' => 'globe'],
            ],
            'scorecard'           => self::getScorecard($interviewId),
            'rubric'              => self::getEvaluation($interviewId) ?? [],
            'files'               => self::files($interviewId),
            'jenny_ai_tips'       => [
                ['title' => 'Use the STAR method', 'desc' => 'Situation, Task, Action, Result. Keep answers to about 90-120 seconds.'],
                ['title' => 'Show your reasoning', 'desc' => 'Explain why you made key decisions, not only what you built.'],
                ['title' => 'Stay relaxed and authentic', 'desc' => 'Take a short pause before complex questions to collect your thoughts.'],
                ['title' => 'Ask thoughtful questions', 'desc' => 'Ask about the team workflow, mentorship and what success looks like in the first month.'],
            ],
        ];
        return $details;
    }

    private static function agenda(int $minutes, string $interviewer): array
    {
        $a = max(1, (int)round($minutes * 0.2));
        $b = max($a + 1, (int)round($minutes * 0.65));
        $c = max($b + 1, (int)round($minutes * 0.85));
        return [
            ['range' => '0-' . $a . 'm',             'text' => 'Introductions with ' . $interviewer . '.'],
            ['range' => $a . '-' . $b . 'm',         'text' => 'Project walkthrough and role questions (you can share your screen).'],
            ['range' => $b . '-' . $c . 'm',         'text' => 'Follow-up questions.'],
            ['range' => $c . '-' . $minutes . 'm',   'text' => 'Your questions for the team.'],
        ];
    }

    /* ══════════════════════════════════════════════════════════════════════
       JENNY AI: SUGGESTIONS & REPORT
       ══════════════════════════════════════════════════════════════════════ */

    /** Three follow-up questions for the interviewer. */
    public static function suggestQuestions(int $interviewId, array $askedIds = [], string $notes = ''): array
    {
        $interview = self::getInterview($interviewId);
        $role = $interview['candidate_role'] ?? 'intern';
        $setup = $interview['setup'] ?? self::sanitizeSetup(null);
        $asked = array_values(array_filter($setup['questions'], static fn ($q) => in_array($q['id'], $askedIds, true)));

        $prompt = "You assist an employer interviewing a candidate for a {$role} internship on InternConnect.\n"
            . 'Criteria: ' . implode(', ', array_column($setup['criteria'], 'label')) . "\n"
            . 'Questions already asked: ' . (implode(' | ', array_column($asked, 'question')) ?: 'none') . "\n"
            . 'Interviewer notes so far: ' . (mb_substr($notes, 0, 1500) ?: 'none') . "\n"
            . 'Suggest exactly 3 short, specific follow-up questions that cover criteria not yet explored. '
            . 'Respond as JSON: {"questions":[{"question":"...","why":"..."}]}';

        $ai = GeminiClient::json($prompt, 8);
        $out = [];
        foreach ((array)($ai['questions'] ?? []) as $q) {
            $text = trim((string)($q['question'] ?? ''));
            if ($text !== '') {
                $out[] = ['question' => mb_substr($text, 0, 300), 'why' => mb_substr(trim((string)($q['why'] ?? '')), 0, 200)];
            }
        }
        if ($out) {
            AiService::logUsage(1, 'employer', 'Interview Follow-up Suggestions', 'Gemini', 200, 150, 0);
            return ['source' => 'gemini', 'questions' => array_slice($out, 0, 3)];
        }

        // Rule-based fallback: one probing question per criterion.
        $bank = [
            'Can you give a concrete example that shows your %s?',
            'What would you do differently next time to strengthen your %s?',
            'How would a teammate describe your %s?',
        ];
        $fallback = [];
        foreach (array_slice($setup['criteria'], 0, 3) as $i => $c) {
            $fallback[] = ['question' => sprintf($bank[$i % 3], strtolower($c['label'])), 'why' => 'Covers ' . $c['label'] . '.'];
        }
        return ['source' => 'rules', 'questions' => $fallback];
    }

    /** Joined/left pairs -> minutes per role, plus first join and last activity. */
    public static function attendance(array $events): array
    {
        $open = [];
        $minutes = ['employer' => 0.0, 'candidate' => 0.0];
        foreach ($events as $e) {
            $role = (string)$e['role'];
            $ts = strtotime((string)$e['created_at']);
            if ($e['event'] === 'joined') {
                $open[$role] = $ts;
            } elseif (in_array($e['event'], ['left', 'room_ended'], true)) {
                $targets = $e['event'] === 'room_ended' ? array_keys($open) : [$role];
                foreach ($targets as $r) {
                    if (isset($open[$r]) && isset($minutes[$r])) {
                        $minutes[$r] += max(0, $ts - $open[$r]) / 60;
                        unset($open[$r]);
                    }
                }
            }
        }
        return array_map(static fn ($m) => (int)round($m), $minutes);
    }

    /** Everything a history/report page needs. $audience: employer | candidate | admin. */
    public static function report(int $interviewId, string $audience): ?array
    {
        $interview = self::getInterview($interviewId);
        if (!$interview) {
            return null;
        }
        $events = self::events($interviewId);
        $scorecard = self::getScorecard($interviewId);
        $files = self::files($interviewId);
        $chat = self::chat($interviewId);
        $room = self::getRoomRecord($interviewId);

        $asked = array_values(array_filter($interview['setup']['questions'], static fn ($q) => in_array($q['id'], (array)($scorecard['asked'] ?? []), true)));
        $recLabel = '';
        foreach ($interview['setup']['recommendations'] as $r) {
            if ($r['key'] === ($scorecard['recommendation'] ?? '')) {
                $recLabel = $r['label'];
            }
        }

        $report = [
            'interview'  => $interview,
            'room'       => $room,
            'timeline'   => array_map(static fn ($e) => [
                'role' => $e['role'], 'event' => $e['event'], 'detail' => $e['detail'], 'at' => $e['created_at'],
                'place' => $e['city'] ? trim($e['city'] . ', ' . $e['country']) : '', 'device' => $e['device'] ?? '',
            ], $events),
            'attendance' => self::attendance($events),
            'files'      => $files,
            'chat_count' => count($chat),
            'asked'      => $asked,
            'question_total' => count($interview['setup']['questions']),
            'screen_shares'  => count(array_filter($events, static fn ($e) => $e['event'] === 'screen_share_started')),
            'ai'         => self::cachedSummary($interviewId),
        ];

        if ($audience !== 'candidate') {
            $report['scorecard'] = $scorecard;
            $report['score_average'] = self::scoreAverage($scorecard);
            $report['recommendation_label'] = $recLabel;
        }
        if ($audience === 'candidate') {
            $report['ai'] = null; // assessments are for the hiring team only
        }
        if ($audience !== 'admin') {
            $report['chat'] = $chat; // participants can reread their own chat; admins only see counts
        }
        return $report;
    }

    public static function cachedSummary(int $interviewId): ?array
    {
        self::ensureTables();
        $stmt = self::pdo()->prepare('SELECT summary, source, updated_at FROM interview_reports WHERE interview_id = ?');
        $stmt->execute([$interviewId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            return null;
        }
        $data = json_decode((string)$row['summary'], true) ?: [];
        return $data + ['source' => $row['source'], 'updated_at' => $row['updated_at']];
    }

    /** Build (or rebuild) the AI interview summary and suggested rating. */
    public static function generateSummary(int $interviewId): ?array
    {
        $interview = self::getInterview($interviewId);
        if (!$interview) {
            return null;
        }
        $scorecard = self::getScorecard($interviewId);
        $events = self::events($interviewId);
        $attendance = self::attendance($events);
        $files = self::files($interviewId);
        $chat = self::chat($interviewId);
        $setup = $interview['setup'];
        $asked = array_values(array_filter($setup['questions'], static fn ($q) => in_array($q['id'], (array)($scorecard['asked'] ?? []), true)));
        $recKeys = array_column($setup['recommendations'], 'key');

        $scoreLines = [];
        foreach ($setup['criteria'] as $c) {
            $scoreLines[] = $c['label'] . ': ' . ((int)($scorecard['scores'][$c['key']] ?? 0) ?: 'not rated') . '/5';
        }
        $chatLines = array_map(static fn ($m) => $m['role'] . ': ' . $m['body'], array_slice($chat, -30));

        $prompt = "Summarise a {$interview['duration_min']}-minute internship interview for the hiring team. Be factual and neutral; do not invent details. Refer to the candidate by name or as \"they\"; never assume gender.\n"
            . "Role: {$interview['candidate_role']}. Candidate: {$interview['candidate_name']}. Company: {$interview['company_name']}.\n"
            . 'Minutes present: employer ' . $attendance['employer'] . ', candidate ' . $attendance['candidate'] . "\n"
            . 'Questions asked (' . count($asked) . ' of ' . count($setup['questions']) . '): ' . (implode(' | ', array_column($asked, 'question')) ?: 'none recorded') . "\n"
            . 'Interviewer ratings: ' . implode('; ', $scoreLines) . "\n"
            . 'Interviewer notes: ' . (mb_substr((string)($scorecard['notes'] ?? ''), 0, 2500) ?: 'none') . "\n"
            . 'Files shared: ' . (implode(', ', array_column($files, 'name')) ?: 'none') . "\n"
            . 'Chat excerpts: ' . (implode(' / ', $chatLines) ?: 'none') . "\n"
            . 'Recommendation options (use one key): ' . implode(', ', $recKeys) . "\n"
            . 'Respond as JSON: {"overview":"2-3 sentences","highlights":["..."],"concerns":["..."],"suggested_rating":1-5,"suggested_recommendation":"key","confidence":"low|medium|high"}';

        $ai = GeminiClient::json($prompt, 15);
        $source = 'gemini';
        if (!is_array($ai) || empty($ai['overview'])) {
            $ai = self::ruleSummary($interview, $scorecard, $attendance, $asked, $files);
            $source = 'rules';
        }

        $summary = [
            'overview'                 => mb_substr(trim((string)$ai['overview']), 0, 800),
            'highlights'               => array_slice(array_map(static fn ($s) => mb_substr((string)$s, 0, 200), (array)($ai['highlights'] ?? [])), 0, 5),
            'concerns'                 => array_slice(array_map(static fn ($s) => mb_substr((string)$s, 0, 200), (array)($ai['concerns'] ?? [])), 0, 5),
            'suggested_rating'         => max(1, min(5, (int)($ai['suggested_rating'] ?? 3))),
            'suggested_recommendation' => in_array($ai['suggested_recommendation'] ?? '', $recKeys, true) ? $ai['suggested_recommendation'] : null,
            'confidence'               => in_array($ai['confidence'] ?? '', ['low', 'medium', 'high'], true) ? $ai['confidence'] : 'low',
        ];

        $pdo = self::pdo();
        $sql = self::driver($pdo) === 'mysql'
            ? 'INSERT INTO interview_reports (interview_id, summary, source, updated_at) VALUES (?, ?, ?, ?) ON DUPLICATE KEY UPDATE summary = VALUES(summary), source = VALUES(source), updated_at = VALUES(updated_at)'
            : 'INSERT INTO interview_reports (interview_id, summary, source, updated_at) VALUES (?, ?, ?, ?) ON CONFLICT(interview_id) DO UPDATE SET summary = excluded.summary, source = excluded.source, updated_at = excluded.updated_at';
        $pdo->prepare($sql)->execute([$interviewId, json_encode($summary), $source, date('Y-m-d H:i:s')]);
        if ($source === 'gemini') {
            AiService::logUsage(1, 'employer', 'Interview Summary', 'Gemini', 600, 300, 0);
        }
        return $summary + ['source' => $source, 'updated_at' => date('Y-m-d H:i:s')];
    }

    /** Deterministic summary used when Gemini is unavailable. */
    public static function ruleSummary(array $interview, ?array $scorecard, array $attendance, array $asked, array $files): array
    {
        $avg = self::scoreAverage($scorecard);
        $setup = $interview['setup'];
        $highlights = [];
        $concerns = [];
        foreach ($setup['criteria'] as $c) {
            $s = (int)($scorecard['scores'][$c['key']] ?? 0);
            if ($s >= 4) {
                $highlights[] = 'Rated ' . $s . '/5 for ' . strtolower($c['label']) . '.';
            } elseif ($s > 0 && $s <= 2) {
                $concerns[] = 'Rated ' . $s . '/5 for ' . strtolower($c['label']) . '.';
            }
        }
        if (count($asked) < count($setup['questions'])) {
            $concerns[] = (count($setup['questions']) - count($asked)) . ' prepared question(s) were not marked as asked.';
        }
        if ($files) {
            $highlights[] = count($files) . ' file(s) shared during the interview.';
        }

        $recs = array_column($setup['recommendations'], 'key');
        $suggested = null;
        if ($avg !== null && $recs) {
            $index = $avg >= 4.5 ? 0 : ($avg >= 3.5 ? 1 : ($avg >= 2.5 ? (int)floor((count($recs) - 1) / 2) + 1 : count($recs) - 1));
            $suggested = $recs[min($index, count($recs) - 1)];
        }

        $overview = sprintf(
            '%s interviewed for %s. The employer was present for about %d minutes and the candidate for about %d minutes; %d of %d prepared questions were marked as asked.%s',
            $interview['candidate_name'],
            $interview['candidate_role'],
            $attendance['employer'],
            $attendance['candidate'],
            count($asked),
            count($setup['questions']),
            $avg !== null ? ' Average interviewer rating: ' . $avg . '/5.' : ' No ratings were recorded yet.'
        );

        return [
            'overview'                 => $overview,
            'highlights'               => $highlights,
            'concerns'                 => $concerns,
            'suggested_rating'         => $avg !== null ? (int)round($avg) : 3,
            'suggested_recommendation' => $suggested,
            'confidence'               => $avg !== null ? 'medium' : 'low',
        ];
    }

    /* ══════════════════════════════════════════════════════════════════════
       LISTS, ADMIN & LIVE MAP
       ══════════════════════════════════════════════════════════════════════ */

    /**
     * Interview sessions, newest first. Filters: employer_id, candidate_id, limit.
     */
    public static function sessions(array $filters = []): array
    {
        self::ensureTables();
        // Only sessions created through the current scheduling flow (employer or admin).
        // Older demo/test rows without created_by (Google Meet era) are left untouched but hidden.
        $where = ['created_by IS NOT NULL'];
        $args = [];
        foreach (['employer_id', 'candidate_id'] as $f) {
            if (!empty($filters[$f])) {
                $where[] = "{$f} = ?";
                $args[] = (int)$filters[$f];
            }
        }
        $sql = 'SELECT id FROM scheduled_interviews' . ($where ? ' WHERE ' . implode(' AND ', $where) : '') . ' ORDER BY id DESC LIMIT ' . max(1, min(200, (int)($filters['limit'] ?? 100)));
        try {
            $stmt = self::pdo()->prepare($sql);
            $stmt->execute($args);
            $ids = $stmt->fetchAll(PDO::FETCH_COLUMN);
        } catch (Throwable $e) {
            return [];
        }

        $settings = self::settings();
        $now = time();
        $out = [];
        foreach ($ids as $id) {
            $i = self::getInterview((int)$id);
            if (!$i) {
                continue;
            }
            $room = self::getRoomRecord($i['id']);
            $timing = self::timing($i, $room, $settings);
            $sc = self::getScorecard($i['id']);
            $recLabel = '';
            foreach ($i['setup']['recommendations'] as $r) {
                if ($r['key'] === ($sc['recommendation'] ?? '')) {
                    $recLabel = $r['label'];
                }
            }
            $status = $i['status'];
            if ($status === 'In progress' && $timing['hard_end'] && $now > $timing['hard_end']) {
                $status = 'Completed';
            }
            if (($room['status'] ?? '') === 'ended' && $status !== 'Cancelled') {
                $status = 'Completed';
            }
            $out[] = [
                'id'               => $i['id'],
                'candidate_id'     => $i['candidate_id'],
                'candidate_name'   => $i['candidate_name'],
                'candidate_role'   => $i['candidate_role'],
                'company_name'     => $i['company_name'],
                'interviewer_name' => $i['interviewer_name'],
                'meeting_type'     => $i['meeting_type'],
                'status'           => $status,
                'when'             => $i['scheduled_at'] ? date('M j, Y', $i['scheduled_at']) : $i['date'],
                'when_time'        => $i['scheduled_at'] ? date('g:i A', $i['scheduled_at']) : $i['time'],
                'scheduled_at'     => $i['scheduled_at'],
                'duration_min'     => $i['duration_min'],
                'room_name'        => $room['room_name'] ?? '',
                'room_status'      => $room['status'] ?? 'none',
                'ended_by'         => $room['ended_by'] ?? '',
                'recommendation'   => $recLabel,
                'score_average'    => self::scoreAverage($sc),
                'created_by'       => $i['created_by'],
            ];
        }
        return $out;
    }

    /** Admin console data. */
    public static function adminOverview(): array
    {
        return [
            'sessions' => self::sessions(['limit' => 100]),
            'events'   => self::events(0, 60),
        ];
    }

    /**
     * Feed for the live interview map (same shape as AdminController::liveSessions()).
     */
    public static function liveMapFeed(): array
    {
        self::ensureTables();
        $presence = self::presence();
        $rooms = [];
        try {
            foreach (self::pdo()->query("SELECT interview_id, room_name FROM interview_rooms WHERE status = 'open'")->fetchAll(PDO::FETCH_ASSOC) as $r) {
                $rooms[$r['room_name']] = (int)$r['interview_id'];
            }
        } catch (Throwable $e) {
        }

        $sessions = [];
        $places = [];
        $countries = [];
        $candidates = 0;
        $employers = 0;
        foreach ($presence as $roomName => $people) {
            if (!isset($rooms[$roomName])) {
                continue;
            }
            $interviewId = $rooms[$roomName];
            foreach ($people as $p) {
                $role = $p['role'];
                $role === 'candidate' ? $candidates++ : $employers++; // admins count with the hosting side
                $stmt = self::pdo()->prepare("SELECT * FROM interview_room_events WHERE interview_id = ? AND role = ? AND event = 'joined' ORDER BY id DESC LIMIT 1");
                $stmt->execute([$interviewId, $role]);
                $e = $stmt->fetch(PDO::FETCH_ASSOC) ?: [];
                $city = (string)($e['city'] ?? '');
                $code = (string)($e['country_code'] ?? '--');
                $country = (string)($e['country'] ?? 'Unknown location');
                $sessions[] = [
                    'id'      => $interviewId . '-' . $role,
                    'code'    => $code,
                    'city'    => $city ?: 'Unknown city',
                    'country' => $country,
                    'ip'      => (string)($e['ip_masked'] ?? 'hidden'),
                    'device'  => (string)($e['device'] ?? 'Browser'),
                    'role'    => ucfirst($role) . ' · ' . $p['name'],
                    'page'    => 'Interview #' . $interviewId,
                    'active'  => 'Live',
                ];
                if ($city !== '' && isset($e['lat'])) {
                    $k = $city . '|' . $code;
                    $places[$k] = $places[$k] ?? ['city' => $city, 'country' => $country, 'code' => $code, 'lat' => (float)$e['lat'], 'lng' => (float)$e['lng'], 'online' => 0, 'students' => 0, 'employers' => 0];
                    $places[$k]['online']++;
                    $role === 'candidate' ? $places[$k]['students']++ : $places[$k]['employers']++;
                }
                $countries[$code] = $countries[$code] ?? ['code' => $code, 'name' => $country, 'online' => 0];
                $countries[$code]['online']++;
            }
        }
        $total = $candidates + $employers;
        $countries = array_values($countries);
        usort($countries, static fn ($a, $b) => $b['online'] <=> $a['online']);
        foreach ($countries as &$c) {
            $c['share'] = $total ? (int)round($c['online'] / $total * 100) : 0;
        }

        return [
            'is_sample' => false,
            'total'     => $total,
            'students'  => $candidates,
            'employers' => $employers,
            'countries' => $countries,
            'sessions'  => $sessions,
            'locations' => array_values($places),
        ];
    }
}
