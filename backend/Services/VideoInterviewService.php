<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\Database;
use PDO;
use Throwable;

/**
 * Service: In-Platform Video Interviews (Powered by Daily.co)
 * Provides room token creation, participant routing, live evaluation scorecards,
 * and seamless in-platform video calling so users never leave InternConnect.
 */
class VideoInterviewService
{
    private static bool $tablesChecked = false;

    /**
     * Ensure database table for evaluations exists
     */
    public static function ensureTables(): void
    {
        if (self::$tablesChecked) {
            return;
        }

        try {
            $pdo = Database::getConnection();
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
            self::$tablesChecked = true;
        } catch (Throwable $e) {
            error_log('VideoInterviewService table creation error: ' . $e->getMessage());
        }
    }

    /**
     * Get meeting room configuration and participant data
     */
    public static function getRoomDetails(int $interviewId, string $userRole = 'employer'): array
    {
        self::ensureTables();

        $dailyDomain = $_ENV['DAILY_DOMAIN'] ?? 'internconnect';
        $dailyRoomUrl = "https://{$dailyDomain}.daily.co/nxt-uxin-{$interviewId}";

        // Canonical interview details
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
            'status'              => 'Live',
            'daily_room_url'      => $dailyRoomUrl,
            'user_role'           => $userRole,
            'portfolio_links'     => [
                ['label' => 'Figma Case Studies', 'url' => 'https://figma.com/@sarahpeters', 'icon' => 'figma'],
                ['label' => 'GitHub Repositories', 'url' => 'https://github.com/sarahpeters', 'icon' => 'github'],
                ['label' => 'Personal Portfolio', 'url' => 'https://sarahpeters.design', 'icon' => 'globe'],
            ],
            'screening_questions' => [
                [
                    'id'       => 1,
                    'question' => 'Can you walk us through your design process for the Oakland banking app project?',
                    'hint'     => 'Focus on user journey mapping, design tokens, and accessibility contrast standards.',
                    'asked'    => true
                ],
                [
                    'id'       => 2,
                    'question' => 'How do you handle developer handoffs and spacing consistency in auto-layout?',
                    'hint'     => 'Listen for 8pt grid system, Figma variables, component variants, and token documentation.',
                    'asked'    => false
                ],
                [
                    'id'       => 3,
                    'question' => 'Tell us about a time you received critical design critique and iterated on feedback.',
                    'hint'     => 'Assess ego, receptiveness, data-driven reasoning, and collaboration with product teams.',
                    'asked'    => false
                ],
                [
                    'id'       => 4,
                    'question' => 'Are you available full-time on-site/hybrid at our Victoria Island office for the 6-month duration?',
                    'hint'     => 'SIWES availability check (Mon-Fri 9AM-5PM starting November 2026).',
                    'asked'    => false
                ],
            ],
            'rubric' => [
                'technical_score'        => 4,
                'problem_solving_score'   => 4,
                'communication_score'    => 5,
                'culture_score'          => 5,
                'overall_recommendation' => 'hire',
                'notes'                  => "Strong grasp of design tokens and accessibility contrast ratios. Clear presentation of mobile banking user flows.\nRecommended for final offer review.",
            ],
            'jenny_ai_tips' => [
                [
                    'title' => 'Use the STAR Method for Questions',
                    'desc'  => 'Situation, Task, Action, Result. Keep your answers concise within 90-120 seconds.'
                ],
                [
                    'title' => 'Highlight Accessibility Standards',
                    'desc'  => 'Mention WCAG 2.1 AAA color contrast and tactile tap targets when discussing UI components.'
                ],
                [
                    'title' => 'Stay Relaxed & Authentic',
                    'desc'  => 'Take a 2-second pause before answering complex questions to collect your thoughts.'
                ],
                [
                    'title' => 'Ask Thoughtful Questions at the End',
                    'desc'  => 'Ask about the team design review workflow and opportunities for SIWES mentorship.'
                ]
            ]
        ];

        // Load persisted evaluation if available
        $savedRubric = self::getEvaluation($interviewId);
        if (!empty($savedRubric)) {
            $details['rubric'] = array_merge($details['rubric'], $savedRubric);
        }

        return $details;
    }

    /**
     * Save interview scorecard and evaluation
     */
    public static function saveEvaluation(int $interviewId, array $data): array
    {
        if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
            @session_start();
        }

        self::ensureTables();

        $tech = max(1, min(5, (int)($data['technical_score'] ?? 4)));
        $prob = max(1, min(5, (int)($data['problem_solving_score'] ?? 4)));
        $comm = max(1, min(5, (int)($data['communication_score'] ?? 5)));
        $cult = max(1, min(5, (int)($data['culture_score'] ?? 5)));
        $rec  = in_array($data['overall_recommendation'] ?? '', ['strong_hire', 'hire', 'hold', 'reject'])
            ? $data['overall_recommendation']
            : 'hire';
        $notes = trim((string)($data['notes'] ?? ''));

        $record = [
            'interview_id'           => $interviewId,
            'technical_score'        => $tech,
            'problem_solving_score'   => $prob,
            'communication_score'    => $comm,
            'culture_score'          => $cult,
            'overall_recommendation' => $rec,
            'notes'                  => $notes,
            'updated_at'             => date('Y-m-d H:i:s'),
        ];

        // 1. Session store
        if (!isset($_SESSION['interview_evaluations'])) {
            $_SESSION['interview_evaluations'] = [];
        }
        $_SESSION['interview_evaluations'][$interviewId] = $record;

        // 2. Database store
        try {
            $pdo = Database::getConnection();
            $stmt = $pdo->prepare("
                INSERT INTO interview_evaluations (interview_id, technical_score, problem_solving_score, communication_score, culture_score, overall_recommendation, notes)
                VALUES (?, ?, ?, ?, ?, ?, ?)
                ON CONFLICT(interview_id) DO UPDATE SET
                    technical_score = excluded.technical_score,
                    problem_solving_score = excluded.problem_solving_score,
                    communication_score = excluded.communication_score,
                    culture_score = excluded.culture_score,
                    overall_recommendation = excluded.overall_recommendation,
                    notes = excluded.notes,
                    updated_at = CURRENT_TIMESTAMP
            ");
            $stmt->execute([$interviewId, $tech, $prob, $comm, $cult, $rec, $notes]);
        } catch (Throwable $e) {
            error_log('saveEvaluation error: ' . $e->getMessage());
        }

        return $record;
    }

    /**
     * Retrieve evaluation for an interview
     */
    public static function getEvaluation(int $interviewId): ?array
    {
        if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
            @session_start();
        }

        self::ensureTables();

        try {
            $pdo = Database::getConnection();
            $stmt = $pdo->prepare("SELECT * FROM interview_evaluations WHERE interview_id = ?");
            $stmt->execute([$interviewId]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($row) {
                return [
                    'technical_score'        => (int)$row['technical_score'],
                    'problem_solving_score'   => (int)$row['problem_solving_score'],
                    'communication_score'    => (int)$row['communication_score'],
                    'culture_score'          => (int)$row['culture_score'],
                    'overall_recommendation' => $row['overall_recommendation'],
                    'notes'                  => $row['notes'],
                ];
            }
        } catch (Throwable $e) {
            // fallback to session
        }

        return $_SESSION['interview_evaluations'][$interviewId] ?? null;
    }
}
