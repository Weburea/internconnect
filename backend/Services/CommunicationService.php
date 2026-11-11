<?php
declare(strict_types=1);

namespace App\Services;

use App\Core\Database;
use PDO;
use Throwable;

/**
 * InternConnect Communication Service
 * Powers Direct 1-on-1 Messaging, In-App Notifications, and Interview Lifecycles
 * according to docs/13-notification-system.md, docs/14-messaging-system.md, and docs/15-interview-system.md.
 */
class CommunicationService
{
    private static bool $tablesInitialized = false;

    /**
     * Ensure database tables exist for notifications, messages, and interviews
     */
    public static function ensureTables(): void
    {
        if (self::$tablesInitialized) {
            return;
        }

        try {
            $pdo = Database::getConnection();

            // 1. Notifications Table
            $pdo->exec("
                CREATE TABLE IF NOT EXISTS in_app_notifications (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    user_id INTEGER NOT NULL DEFAULT 1,
                    user_role TEXT NOT NULL DEFAULT 'candidate',
                    title TEXT NOT NULL,
                    message TEXT NOT NULL,
                    type TEXT NOT NULL DEFAULT 'system',
                    action_url TEXT DEFAULT '',
                    is_read INTEGER NOT NULL DEFAULT 0,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                );
            ");

            // 2. Direct Messages Table
            $pdo->exec("
                CREATE TABLE IF NOT EXISTS direct_messages (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    thread_id INTEGER NOT NULL DEFAULT 1,
                    sender_role TEXT NOT NULL,
                    sender_name TEXT NOT NULL DEFAULT '',
                    content TEXT NOT NULL,
                    attachment TEXT DEFAULT NULL,
                    is_read INTEGER NOT NULL DEFAULT 0,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                );
            ");

            // 3. Interviews Table
            $pdo->exec("
                CREATE TABLE IF NOT EXISTS scheduled_interviews (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    candidate_id INTEGER NOT NULL DEFAULT 1,
                    employer_id INTEGER NOT NULL DEFAULT 1,
                    candidate_name TEXT NOT NULL DEFAULT 'Sarah Peters',
                    candidate_role TEXT NOT NULL DEFAULT 'UI/UX Design Intern',
                    company_name TEXT NOT NULL DEFAULT 'Nexura Technologies',
                    interviewer_name TEXT NOT NULL DEFAULT 'Tunde Bakare',
                    meeting_type TEXT NOT NULL DEFAULT 'virtual',
                    meeting_link TEXT DEFAULT 'https://meet.google.com/nxt-uxin-2026',
                    interview_date TEXT NOT NULL,
                    interview_time TEXT NOT NULL,
                    duration TEXT NOT NULL DEFAULT '45 mins',
                    status TEXT NOT NULL DEFAULT 'Scheduled',
                    notes TEXT DEFAULT '',
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                );
            ");

            self::$tablesInitialized = true;
        } catch (Throwable $e) {
            error_log('CommunicationService ensureTables error: ' . $e->getMessage());
        }
    }

    /* ══════════════════════════════════════════════════════════════════════════
       1. NOTIFICATIONS ENGINE (docs/13-notification-system.md)
       ══════════════════════════════════════════════════════════════════════════ */

    /**
     * Retrieve notifications for a specific user role
     */
    public static function getNotifications(string $userRole, int $userId = 1): array
    {
        if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
            @session_start();
        }

        self::ensureTables();

        $sessionKey = 'notifications_' . $userRole;
        if (!isset($_SESSION[$sessionKey])) {
            $_SESSION[$sessionKey] = self::getDefaultNotifications($userRole);
        }

        try {
            $pdo = Database::getConnection();
            $stmt = $pdo->prepare("
                SELECT * FROM in_app_notifications 
                WHERE user_role = ? AND user_id = ?
                ORDER BY id DESC
            ");
            $stmt->execute([$userRole, $userId]);
            $dbRows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (!empty($dbRows)) {
                return $dbRows;
            }
        } catch (Throwable $e) {
            // fallback to session
        }

        return $_SESSION[$sessionKey];
    }

    /**
     * Dispatch a new notification to a candidate, employer, or admin
     */
    public static function createNotification(
        string $recipientRole,
        int $userId,
        string $title,
        string $message,
        string $type,
        string $actionUrl = ''
    ): array {
        if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
            @session_start();
        }

        self::ensureTables();

        $item = [
            'id'         => (int) (microtime(true) * 1000),
            'user_id'    => $userId,
            'user_role'  => $recipientRole,
            'title'      => $title,
            'message'    => $message,
            'type'       => $type,
            'action_url' => $actionUrl,
            'is_read'    => 0,
            'created_at' => date('Y-m-d H:i:s'),
            'time_ago'   => 'Just now',
        ];

        // 1. Session Storage
        $sessionKey = 'notifications_' . $recipientRole;
        if (!isset($_SESSION[$sessionKey])) {
            $_SESSION[$sessionKey] = self::getDefaultNotifications($recipientRole);
        }
        array_unshift($_SESSION[$sessionKey], $item);

        // 2. Database Storage
        try {
            $pdo = Database::getConnection();
            $stmt = $pdo->prepare("
                INSERT INTO in_app_notifications (user_id, user_role, title, message, type, action_url, is_read)
                VALUES (?, ?, ?, ?, ?, ?, 0)
            ");
            $stmt->execute([$userId, $recipientRole, $title, $message, $type, $actionUrl]);
            $item['id'] = (int)$pdo->lastInsertId();
        } catch (Throwable $e) {
            error_log('createNotification error: ' . $e->getMessage());
        }

        return $item;
    }

    /**
     * Mark a single notification as read
     */
    public static function markNotificationRead(int $id, string $userRole): bool
    {
        if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
            @session_start();
        }

        self::ensureTables();

        $sessionKey = 'notifications_' . $userRole;
        if (isset($_SESSION[$sessionKey])) {
            foreach ($_SESSION[$sessionKey] as &$n) {
                if ((int)$n['id'] === $id) {
                    $n['is_read'] = 1;
                    break;
                }
            }
        }

        try {
            $pdo = Database::getConnection();
            $stmt = $pdo->prepare("UPDATE in_app_notifications SET is_read = 1 WHERE id = ?");
            return $stmt->execute([$id]);
        } catch (Throwable $e) {
            return true;
        }
    }

    /**
     * Mark all notifications as read for a role
     */
    public static function markAllNotificationsRead(string $userRole, int $userId = 1): bool
    {
        if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
            @session_start();
        }

        self::ensureTables();

        $sessionKey = 'notifications_' . $userRole;
        if (isset($_SESSION[$sessionKey])) {
            foreach ($_SESSION[$sessionKey] as &$n) {
                $n['is_read'] = 1;
            }
        }

        try {
            $pdo = Database::getConnection();
            $stmt = $pdo->prepare("UPDATE in_app_notifications SET is_read = 1 WHERE user_role = ? AND user_id = ?");
            return $stmt->execute([$userRole, $userId]);
        } catch (Throwable $e) {
            return true;
        }
    }

    /**
     * Count unread notifications
     */
    public static function getUnreadNotificationCount(string $userRole, int $userId = 1): int
    {
        $notifications = self::getNotifications($userRole, $userId);
        $count = 0;
        foreach ($notifications as $n) {
            if (empty($n['is_read'])) {
                $count++;
            }
        }
        return $count;
    }

    /* ══════════════════════════════════════════════════════════════════════════
       2. DIRECT 1-ON-1 MESSAGING ENGINE (docs/14-messaging-system.md)
       ══════════════════════════════════════════════════════════════════════════ */

    /**
     * Send a message and append to thread
     */
    public static function sendDirectMessage(
        int $threadId,
        string $senderRole,
        string $senderName,
        string $content,
        ?string $attachment = null
    ): array {
        if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
            @session_start();
        }

        self::ensureTables();

        $cleanText = trim($content);
        $tempId = 'msg_' . (int)(microtime(true) * 1000) . '_' . mt_rand(100, 999);
        $msg = [
            'id'          => $tempId,
            'thread_id'   => $threadId,
            'sender'      => $senderRole,
            'sender_name' => $senderName,
            'text'        => $cleanText,
            'attachment'  => $attachment,
            'time'        => date('g:i A'),
            'status'      => 'delivered',
            'created_at'  => date('Y-m-d H:i:s'),
        ];

        // 1. Session Storage
        if (!isset($_SESSION['direct_messages_store'])) {
            $_SESSION['direct_messages_store'] = [];
        }
        if (!isset($_SESSION['direct_messages_store'][$threadId])) {
            $_SESSION['direct_messages_store'][$threadId] = [];
        }
        $_SESSION['direct_messages_store'][$threadId][] = $msg;

        // 2. Database Storage
        try {
            $pdo = Database::getConnection();
            $stmt = $pdo->prepare("
                INSERT INTO direct_messages (thread_id, sender_role, sender_name, content, attachment, is_read)
                VALUES (?, ?, ?, ?, ?, 0)
            ");
            $stmt->execute([$threadId, $senderRole, $senderName, $cleanText, $attachment]);
            $msg['id'] = 'db_' . (int)$pdo->lastInsertId();
        } catch (Throwable $e) {
            error_log('sendDirectMessage error: ' . $e->getMessage());
        }

        // 3. Trigger In-App Notification to Recipient
        $recipientRole = ($senderRole === 'candidate') ? 'employer' : 'candidate';
        $notifTitle = ($senderRole === 'candidate')
            ? "New message from {$senderName}"
            : "New recruiter message from {$senderName}";
        $actionUrl = ($recipientRole === 'employer')
            ? url("/employer/messages/{$threadId}")
            : url("/candidate/messages/{$threadId}");

        self::createNotification(
            $recipientRole,
            1,
            $notifTitle,
            mb_substr($cleanText, 0, 75) . (mb_strlen($cleanText) > 75 ? '...' : ''),
            'message',
            $actionUrl
        );

        return $msg;
    }

    /**
     * Get dynamic messages for a thread (merging defaults with newly sent messages)
     */
    public static function getThreadMessages(int $threadId, array $seedMessages = []): array
    {
        if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
            @session_start();
        }

        self::ensureTables();

        if (empty($seedMessages)) {
            $seedMessages = self::getDefaultThreadMessages($threadId);
        }

        $messages = [];
        $seenKeys = [];

        // 1. Normalize seed messages with guaranteed unique IDs
        foreach ($seedMessages as $idx => $seed) {
            $uniqueId = 'seed_' . $threadId . '_' . ($seed['id'] ?? $idx);
            $seed['id'] = $uniqueId;
            $messages[] = $seed;
            $seenKeys[$uniqueId] = true;
        }

        // 2. Merge from database if exists
        try {
            $pdo = Database::getConnection();
            $stmt = $pdo->prepare("
                SELECT * FROM direct_messages 
                WHERE thread_id = ?
                ORDER BY id ASC
            ");
            $stmt->execute([$threadId]);
            $dbRows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (!empty($dbRows)) {
                foreach ($dbRows as $row) {
                    $uniqueId = 'db_' . (int)$row['id'];
                    if (isset($seenKeys[$uniqueId])) {
                        continue;
                    }
                    $seenKeys[$uniqueId] = true;
                    $messages[] = [
                        'id'          => $uniqueId,
                        'sender'      => $row['sender_role'],
                        'sender_name' => $row['sender_name'],
                        'text'        => $row['content'],
                        'attachment'  => $row['attachment'],
                        'time'        => date('g:i A', strtotime($row['created_at'])),
                        'status'      => $row['is_read'] ? 'read' : 'delivered',
                    ];
                }
                return $messages;
            }
        } catch (Throwable $e) {
            // fallback to session
        }

        // 3. Merge from session store
        if (isset($_SESSION['direct_messages_store'][$threadId])) {
            foreach ($_SESSION['direct_messages_store'][$threadId] as $sMsg) {
                $uniqueId = 'sess_' . ($sMsg['id'] ?? uniqid());
                if (isset($seenKeys[$uniqueId])) {
                    continue;
                }
                $seenKeys[$uniqueId] = true;
                $sMsg['id'] = $uniqueId;
                $messages[] = $sMsg;
            }
        }

        return $messages;
    }

    /* ══════════════════════════════════════════════════════════════════════════
       3. INTERVIEW SCHEDULING LIFECYCLE (docs/15-interview-system.md)
       ══════════════════════════════════════════════════════════════════════════ */

    /**
     * Schedule a new interview (invoked by Employer)
     */
    public static function scheduleInterview(array $data): array
    {
        if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
            @session_start();
        }

        self::ensureTables();

        $interview = [
            'id'               => (int) (microtime(true) * 1000),
            'candidate_id'     => (int) ($data['candidate_id'] ?? 1),
            'employer_id'      => (int) ($data['employer_id'] ?? 1),
            'candidate_name'   => $data['candidate_name'] ?? 'Sarah Peters',
            'candidate_role'   => $data['candidate_role'] ?? 'UI/UX Design Intern',
            'company_name'     => $data['company_name'] ?? 'Nexura Technologies',
            'interviewer_name' => $data['interviewer_name'] ?? 'Tunde Bakare',
            'meeting_type'     => $data['meeting_type'] ?? 'virtual',
            'meeting_link'     => $data['meeting_link'] ?? 'https://meet.google.com/nxt-uxin-2026',
            'interview_date'   => $data['date'] ?? '10/26/2026',
            'interview_time'   => $data['time'] ?? '02:30 PM',
            'duration'         => $data['duration'] ?? '45 mins',
            'status'           => 'Scheduled',
            'notes'            => $data['notes'] ?? 'Review case study, design system architecture, and UI prototypes.',
            'created_at'       => date('Y-m-d H:i:s'),
        ];

        // 1. Session Storage
        if (!isset($_SESSION['scheduled_interviews'])) {
            $_SESSION['scheduled_interviews'] = [];
        }
        $_SESSION['scheduled_interviews'][] = $interview;
        $_SESSION['active_upcoming_interview'] = $interview;

        // 2. Database Storage
        try {
            $pdo = Database::getConnection();
            $stmt = $pdo->prepare("
                INSERT INTO scheduled_interviews 
                (candidate_id, employer_id, candidate_name, candidate_role, company_name, interviewer_name, meeting_type, meeting_link, interview_date, interview_time, duration, status, notes)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([
                $interview['candidate_id'],
                $interview['employer_id'],
                $interview['candidate_name'],
                $interview['candidate_role'],
                $interview['company_name'],
                $interview['interviewer_name'],
                $interview['meeting_type'],
                $interview['meeting_link'],
                $interview['interview_date'],
                $interview['interview_time'],
                $interview['duration'],
                $interview['status'],
                $interview['notes'],
            ]);
            $interview['id'] = (int)$pdo->lastInsertId();
        } catch (Throwable $e) {
            error_log('scheduleInterview error: ' . $e->getMessage());
        }

        // 3. Dispatch Candidate Notification per docs/13-notification-system.md
        self::createNotification(
            'candidate',
            $interview['candidate_id'],
            "Interview Invitation from {$interview['company_name']}",
            "You have been invited for a {$interview['meeting_type']} interview for {$interview['candidate_role']} on {$interview['interview_date']} at {$interview['interview_time']}.",
            'interview',
            url('/candidate/interview/invite')
        );

        return $interview;
    }

    /**
     * Candidate responds to interview (Accept, Decline, or Request Reschedule)
     */
    public static function updateInterviewStatus(
        int $interviewId,
        string $status,
        ?array $meta = null
    ): array {
        if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
            @session_start();
        }

        self::ensureTables();

        $updatedInterview = null;

        // 1. Update Session
        if (isset($_SESSION['active_upcoming_interview'])) {
            $_SESSION['active_upcoming_interview']['status'] = $status;
            if ($meta) {
                $_SESSION['active_upcoming_interview'] = array_merge($_SESSION['active_upcoming_interview'], $meta);
            }
            $updatedInterview = $_SESSION['active_upcoming_interview'];
        }

        // 2. Update Database
        try {
            $pdo = Database::getConnection();
            $stmt = $pdo->prepare("UPDATE scheduled_interviews SET status = ? WHERE id = ?");
            $stmt->execute([$status, $interviewId]);
        } catch (Throwable $e) {
            // fallback
        }

        // 3. Trigger Employer Notification
        $candName = $meta['candidate_name'] ?? 'Sarah Peters';
        if ($status === 'Confirmed') {
            self::createNotification(
                'employer',
                1,
                "Candidate Confirmed Interview",
                "{$candName} accepted the interview invitation for UI/UX Design Intern.",
                'interview',
                url('/employer/interviews')
            );
        } elseif ($status === 'Reschedule Requested') {
            $reason = $meta['reason'] ?? 'Schedule conflict';
            $propDate = $meta['reschedule_date'] ?? 'New date proposed';
            self::createNotification(
                'employer',
                1,
                "Interview Reschedule Requested",
                "{$candName} requested to reschedule to {$propDate}. Reason: {$reason}.",
                'interview',
                url('/employer/interviews')
            );
        }

        return $updatedInterview ?? ['status' => $status];
    }

    /* ══════════════════════════════════════════════════════════════════════════
       DEFAULT SEED DATA
       ══════════════════════════════════════════════════════════════════════════ */

    /**
     * Canonical default seed conversation threads
     */
    public static function getDefaultThreadMessages(int $threadId): array
    {
        switch ($threadId) {
            case 1:
                return [
                    [
                        'id'       => 1,
                        'sender'   => 'candidate',
                        'text'     => "Hello! Thank you for reviewing my design portfolio. I was very excited to see the UI/UX internship opening at Nexura Technologies.",
                        'time'     => '10:15 AM',
                        'status'   => 'read'
                    ],
                    [
                        'id'       => 2,
                        'sender'   => 'employer',
                        'text'     => "Hi Sarah! We reviewed your Oakland banking app case study and were really impressed with the user flow and design tokens.",
                        'time'     => '10:22 AM',
                        'status'   => 'read'
                    ],
                    [
                        'id'       => 3,
                        'sender'   => 'candidate',
                        'text'     => "Thank you so much! I really enjoyed working on the accessibility guidelines for that project. I'm available anytime this week for the technical round.",
                        'time'     => '10:28 AM',
                        'status'   => 'read'
                    ],
                    [
                        'id'       => 4,
                        'sender'   => 'employer',
                        'text'     => "Wonderful. We just sent an interview invitation for Thursday at 10:00 AM via Google Meet. Looking forward to speaking!",
                        'time'     => '10:35 AM',
                        'status'   => 'read'
                    ],
                    [
                        'id'       => 5,
                        'sender'   => 'candidate',
                        'text'     => "Received and confirmed! I've accepted the calendar invite for Thursday.",
                        'time'     => '10:42 AM',
                        'status'   => 'delivered'
                    ]
                ];
            case 2:
                return [
                    [
                        'id'       => 1,
                        'sender'   => 'employer',
                        'text'     => "Hi David, could you share the GitHub repository link for your distributed cache project?",
                        'time'     => 'Yesterday, 2:15 PM',
                        'status'   => 'read'
                    ],
                    [
                        'id'       => 2,
                        'sender'   => 'candidate',
                        'text'     => "Hello! Yes, absolutely. Here is the link: github.com/davidadeleke/redis-cache-go. It includes benchmark tests and Docker compose setups.",
                        'time'     => 'Yesterday, 2:30 PM',
                        'status'   => 'read'
                    ],
                    [
                        'id'       => 3,
                        'sender'   => 'employer',
                        'text'     => "Thanks David, reviewing this with our lead engineer now.",
                        'time'     => 'Yesterday, 3:00 PM',
                        'status'   => 'read'
                    ]
                ];
            case 3:
                return [
                    [
                        'id'       => 1,
                        'sender'   => 'candidate',
                        'text'     => "Good afternoon! I had a quick question regarding the 6-month SIWES duration for the PM internship.",
                        'time'     => 'Yesterday, 4:20 PM',
                        'status'   => 'read'
                    ],
                    [
                        'id'       => 2,
                        'sender'   => 'candidate',
                        'text'     => "Does Nexura support hybrid work arrangements for students traveling from Ibadan to Lagos twice a month?",
                        'time'     => '1h ago',
                        'status'   => 'delivered'
                    ]
                ];
            case 4:
                return [
                    [
                        'id'       => 1,
                        'sender'   => 'employer',
                        'text'     => "Hi Tunde, we loved your SQL analytics case study. We'd like to invite you for an introductory call next week.",
                        'time'     => '2 days ago',
                        'status'   => 'read'
                    ],
                    [
                        'id'       => 2,
                        'sender'   => 'candidate',
                        'text'     => "Thank you! I am ready whenever your team is free.",
                        'time'     => '2 days ago',
                        'status'   => 'read'
                    ]
                ];
            default:
                return [];
        }
    }

    private static function getDefaultNotifications(string $role): array
    {
        if ($role === 'employer') {
            return [
                [
                    'id'         => 201,
                    'user_id'    => 1,
                    'user_role'  => 'employer',
                    'title'      => 'New Application Received',
                    'message'    => 'Sarah Peters submitted an application with a 96% AI match score for UI/UX Design Intern.',
                    'type'       => 'application',
                    'action_url' => url('/employer/candidates/1'),
                    'is_read'    => 0,
                    'created_at' => date('Y-m-d H:i:s', strtotime('-15 minutes')),
                    'time_ago'   => '15m ago',
                ],
                [
                    'id'         => 202,
                    'user_id'    => 1,
                    'user_role'  => 'employer',
                    'title'      => 'Interview Slot Confirmed',
                    'message'    => 'Michael Adeyemi accepted your interview invitation for Cloud DevOps Intern.',
                    'type'       => 'interview',
                    'action_url' => url('/employer/interviews'),
                    'is_read'    => 0,
                    'created_at' => date('Y-m-d H:i:s', strtotime('-2 hours')),
                    'time_ago'   => '2h ago',
                ],
                [
                    'id'         => 203,
                    'user_id'    => 1,
                    'user_role'  => 'employer',
                    'title'      => 'Pro Subscription Active',
                    'message'    => 'Your monthly plan renewed successfully via Paystack. 15 job postings active.',
                    'type'       => 'system',
                    'action_url' => url('/employer/profile'),
                    'is_read'    => 1,
                    'created_at' => date('Y-m-d H:i:s', strtotime('-1 day')),
                    'time_ago'   => '1d ago',
                ],
            ];
        }

        return [
            [
                'id'         => 101,
                'user_id'    => 1,
                'user_role'  => 'candidate',
                'title'      => 'Interview Invitation Received',
                'message'    => 'Nexura Technologies invited you to a video interview for UI/UX Design Intern on Oct 26.',
                'type'       => 'interview',
                'action_url' => url('/candidate/interview/invite'),
                'is_read'    => 0,
                'created_at' => date('Y-m-d H:i:s', strtotime('-10 minutes')),
                'time_ago'   => '10m ago',
            ],
            [
                'id'         => 102,
                'user_id'    => 1,
                'user_role'  => 'candidate',
                'title'      => 'Application Viewed',
                'message'    => 'Paystack viewed your Product Design application and highlighted your Figma skills.',
                'type'       => 'application',
                'action_url' => url('/candidate/applications'),
                'is_read'    => 0,
                'created_at' => date('Y-m-d H:i:s', strtotime('-1 hour')),
                'time_ago'   => '1h ago',
            ],
            [
                'id'         => 103,
                'user_id'    => 1,
                'user_role'  => 'candidate',
                'title'      => 'AI CV Optimization Ready',
                'message'    => 'Your ATS score improved to 86/100 for Product Designer roles. 4 action items identified.',
                'type'       => 'system',
                'action_url' => url('/candidate/ai-assistant/cv-results'),
                'is_read'    => 1,
                'created_at' => date('Y-m-d H:i:s', strtotime('-1 day')),
                'time_ago'   => '1d ago',
            ],
        ];
    }
}

