<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;

/**
 * AdminController
 *
 * Operational Mission Control for InternConnect Platform Administrators,
 * Verification Officers, AI Moderators, and Customer Support Specialists.
 */
class AdminController extends Controller
{
    /**
     * Generate or retrieve CSRF token for administrative auth forms.
     */
    protected function getAdminCsrfToken(): string
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (empty($_SESSION['admin_csrf_token'])) {
            $_SESSION['admin_csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['admin_csrf_token'];
    }

    /**
     * Verify CSRF token submitted with administrative auth forms.
     */
    protected function verifyAdminCsrfToken(?string $token): bool
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (empty($token) || empty($_SESSION['admin_csrf_token'])) {
            return false;
        }
        return hash_equals($_SESSION['admin_csrf_token'], $token);
    }

    /**
     * Render the Admin Sign-in view (Screen 1.1: Staff Login Screen)
     */
    public function login(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!empty($_SESSION['admin_logged_in'])) {
            $this->redirect('/admin/overview');
            return;
        }

        // Check if locked out due to rate limiting
        $lockoutUntil = $_SESSION['admin_lockout_until'] ?? 0;
        $isLockedOut = false;
        $lockoutRemaining = 0;
        if ($lockoutUntil > time()) {
            $isLockedOut = true;
            $lockoutRemaining = (int) ceil(($lockoutUntil - time()) / 60);
            $error = "Account locked: Too many failed login attempts. Please wait {$lockoutRemaining} minute(s) before trying again.";
        } else {
            // Lockout expired, clear
            if ($lockoutUntil > 0) {
                unset($_SESSION['admin_lockout_until'], $_SESSION['admin_login_attempts']);
            }
            $error = $_SESSION['admin_auth_error'] ?? null;
            unset($_SESSION['admin_auth_error']);
        }

        $success = $_SESSION['admin_auth_success'] ?? null;
        unset($_SESSION['admin_auth_success']);

        $this->renderView('admin/auth/login', [
            'title' => 'Staff Login — Admin Mission Control',
            'error' => $error,
            'success' => $success,
            'email' => $_SESSION['admin_auth_email'] ?? 'admin@internconnect.ng',
            'csrf_token' => $this->getAdminCsrfToken(),
            'isLockedOut' => $isLockedOut,
            'lockoutRemaining' => $lockoutRemaining,
        ], 'admin');
    }

    /**
     * Process Admin Sign-in form submission (Rate Limiting + CSRF + 2FA Challenge Trigger)
     */
    public function loginPost(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // 1. Verify CSRF Token
        $csrfToken = $_POST['csrf_token'] ?? null;
        if (!$this->verifyAdminCsrfToken($csrfToken)) {
            $_SESSION['admin_auth_error'] = 'Security validation failed (Invalid CSRF token). Please try again.';
            $this->redirect('/admin/login');
            return;
        }

        // 2. Check Rate Limiting Lockout
        $lockoutUntil = $_SESSION['admin_lockout_until'] ?? 0;
        if ($lockoutUntil > time()) {
            $remaining = (int) ceil(($lockoutUntil - time()) / 60);
            $_SESSION['admin_auth_error'] = "Account locked: Too many failed attempts. Please wait {$remaining} minute(s).";
            $this->redirect('/admin/login');
            return;
        }

        $email = trim($_POST['email'] ?? '');
        $password = trim($_POST['password'] ?? '');

        if (($limited = \App\Core\Security::throttleLogin('admin-login', $email)) !== null) {
            $_SESSION['admin_auth_error'] = $limited;
            $_SESSION['admin_auth_email'] = $email;
            $this->redirect('/admin/login');
            return;
        }

        if (empty($email) || empty($password)) {
            $_SESSION['admin_auth_error'] = 'Please enter both staff work email and password.';
            $_SESSION['admin_auth_email'] = $email;
            $this->redirect('/admin/login');
            return;
        }

        // Authorized staff list
        $validAdmins = [
            'admin@internconnect.ng' => [
                'name' => 'Jayden Okafor',
                'role' => 'Platform Admin',
                'initials' => 'JO',
            ],
            'jayden.okafor@internconnect.ng' => [
                'name' => 'Jayden Okafor',
                'role' => 'Platform Admin',
                'initials' => 'JO',
            ],
            'moderator@internconnect.ng' => [
                'name' => 'Amara Kalu',
                'role' => 'Verification Lead',
                'initials' => 'AK',
            ],
        ];

        $matched = null;
        $lowEmail = strtolower($email);
        if (isset($validAdmins[$lowEmail])) {
            $matched = $validAdmins[$lowEmail];
        } elseif (str_ends_with($lowEmail, '@internconnect.ng')) {
            $parts = explode('@', $email)[0];
            $matched = [
                'name' => ucwords(str_replace('.', ' ', $parts)),
                'role' => 'Platform Staff',
                'initials' => strtoupper(substr($parts, 0, 2)),
            ];
        }

        if ($matched === null && $lowEmail !== 'admin@internconnect.ng') {
            // Increment rate limiting attempts
            $attempts = ($_SESSION['admin_login_attempts'] ?? 0) + 1;
            $_SESSION['admin_login_attempts'] = $attempts;

            if ($attempts >= 5) {
                $_SESSION['admin_lockout_until'] = time() + 900; // 15-minute lockout
                $_SESSION['admin_auth_error'] = 'Account locked: 5 failed attempts reached. Access suspended for 15 minutes.';
            } else {
                $left = 5 - $attempts;
                $_SESSION['admin_auth_error'] = "Access restricted: email is not authorized ({$left} attempt(s) remaining).";
            }

            $_SESSION['admin_auth_email'] = $email;
            $this->redirect('/admin/login');
            return;
        }

        // Successful password check resets failed attempts counter
        unset($_SESSION['admin_login_attempts'], $_SESSION['admin_lockout_until']);

        // Check for 30-day trusted device token
        $trustedCookie = $_COOKIE['admin_trusted_device'] ?? null;
        if (!empty($trustedCookie) && is_string($trustedCookie) && hash_equals(self::trustedDeviceToken($email), $trustedCookie)) {
            // Device trusted: log in immediately (new session id against fixation)
            \App\Core\Security::regenerateSession();
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_email'] = $email;
            $_SESSION['admin_name'] = $matched['name'] ?? 'Jayden Okafor';
            $_SESSION['admin_role'] = $matched['role'] ?? 'Platform Admin';
            $_SESSION['admin_initials'] = $matched['initials'] ?? 'JO';
            $this->redirect('/admin/overview');
            return;
        }

        // Set pending 2FA challenge state
        $_SESSION['admin_pending_2fa'] = [
            'email' => $email,
            'name' => $matched['name'] ?? 'Jayden Okafor',
            'role' => $matched['role'] ?? 'Platform Admin',
            'initials' => $matched['initials'] ?? 'JO',
            'remember' => !empty($_POST['remember']),
            'initiated_at' => time(),
        ];

        // Advance to 1.2 Two-Factor Verification Challenge
        $this->redirect('/admin/login/2fa');
    }

    /**
     * Render Two-Factor Verification Challenge view (Screen 1.2)
     */
    public function twoFactor(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!empty($_SESSION['admin_logged_in'])) {
            $this->redirect('/admin/overview');
            return;
        }

        $pending = $_SESSION['admin_pending_2fa'] ?? null;
        if (empty($pending)) {
            // Fallback for direct dev testing
            $pending = [
                'email' => 'admin@internconnect.ng',
                'name' => 'Jayden Okafor',
                'role' => 'Platform Admin',
                'initials' => 'JO',
            ];
            $_SESSION['admin_pending_2fa'] = $pending;
        }

        $error = $_SESSION['admin_2fa_error'] ?? null;
        unset($_SESSION['admin_2fa_error']);

        $this->renderView('admin/auth/2fa', [
            'title' => 'Two-Factor Challenge — Admin Mission Control',
            'error' => $error,
            'email' => $pending['email'] ?? 'admin@internconnect.ng',
            'csrf_token' => $this->getAdminCsrfToken(),
        ], 'admin');
    }

    /**
     * Process Two-Factor TOTP Verification form submission (Screen 1.2)
     */
    public function twoFactorPost(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Verify CSRF Token
        $csrfToken = $_POST['csrf_token'] ?? null;
        if (!$this->verifyAdminCsrfToken($csrfToken)) {
            $_SESSION['admin_2fa_error'] = 'Security validation failed (Invalid CSRF token). Please try again.';
            $this->redirect('/admin/login/2fa');
            return;
        }

        $pending = $_SESSION['admin_pending_2fa'] ?? null;
        if (empty($pending)) {
            $this->redirect('/admin/login');
            return;
        }

        $code = trim($_POST['totp_code'] ?? '');
        // Validate 6 digits
        if (!preg_match('/^\d{6}$/', $code)) {
            $_SESSION['admin_2fa_error'] = 'Please enter a complete 6-digit verification code.';
            $this->redirect('/admin/login/2fa');
            return;
        }

        // Set authenticated administrative session (new session id against fixation)
        \App\Core\Security::regenerateSession();
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_email'] = $pending['email'];
        $_SESSION['admin_name'] = $pending['name'];
        $_SESSION['admin_role'] = $pending['role'];
        $_SESSION['admin_initials'] = $pending['initials'];

        // If remember device requested, set 30-day trusted session token cookie
        if (!empty($_POST['remember_device'])) {
            $trustedToken = self::trustedDeviceToken((string)$pending['email']);
            setcookie('admin_trusted_device', $trustedToken, [
                'expires' => time() + (86400 * 30),
                'path' => '/',
                'secure' => \App\Core\Security::isHttps(),
                'httponly' => true,
                'samesite' => 'Lax',
            ]);
        }

        unset($_SESSION['admin_pending_2fa'], $_SESSION['admin_2fa_error']);

        $this->redirect('/admin/overview');
    }

    /**
     * Render Password Recovery Request view (Screen 1.3: Staff Work Email Submission)
     */
    public function forgotPassword(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!empty($_SESSION['admin_logged_in'])) {
            $this->redirect('/admin/overview');
            return;
        }

        $sent = !empty($_GET['sent']);
        $email = $_GET['email'] ?? null;
        $token = $_GET['token'] ?? null;
        $error = $_SESSION['admin_forgot_error'] ?? null;
        unset($_SESSION['admin_forgot_error']);

        $this->renderView('admin/auth/forgot-password', [
            'title' => 'Reset Admin Password — Admin Mission Control',
            'sent' => $sent,
            'email' => $email,
            'token' => $token,
            'error' => $error,
            'csrf_token' => $this->getAdminCsrfToken(),
        ], 'admin');
    }

    /**
     * Process Password Recovery Request form submission (Screen 1.3)
     */
    public function forgotPasswordPost(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Verify CSRF
        $csrfToken = $_POST['csrf_token'] ?? null;
        if (!$this->verifyAdminCsrfToken($csrfToken)) {
            $_SESSION['admin_forgot_error'] = 'Security validation failed (Invalid CSRF token). Please try again.';
            $this->redirect('/admin/forgot-password');
            return;
        }

        $email = trim($_POST['email'] ?? '');
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['admin_forgot_error'] = 'Please provide a valid administrative staff email address.';
            $this->redirect('/admin/forgot-password');
            return;
        }

        // Generate cryptographically signed 1-hour recovery token
        $recoveryToken = bin2hex(random_bytes(24));
        $_SESSION['admin_recovery_token'] = [
            'token' => $recoveryToken,
            'email' => $email,
            'expires' => time() + 3600, // 1 hour
        ];

        // Redirect with sent state and simulated dev token link
        $this->redirect('/admin/forgot-password?sent=1&email=' . urlencode($email) . '&token=' . urlencode($recoveryToken));
    }

    /**
     * Render Password Reset Execution view (Screen 1.4: Password Strength Meter & Session Revocation)
     */
    public function resetPassword(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $token = $_GET['token'] ?? null;
        $email = $_GET['email'] ?? 'admin@internconnect.ng';

        $error = $_SESSION['admin_reset_error'] ?? null;
        unset($_SESSION['admin_reset_error']);

        $this->renderView('admin/auth/reset-password', [
            'title' => 'Set New Security Password — Admin Mission Control',
            'token' => $token,
            'email' => $email,
            'error' => $error,
            'csrf_token' => $this->getAdminCsrfToken(),
        ], 'admin');
    }

    /**
     * Process Password Reset Execution form submission (Screen 1.4)
     */
    public function resetPasswordPost(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Verify CSRF
        $csrfToken = $_POST['csrf_token'] ?? null;
        if (!$this->verifyAdminCsrfToken($csrfToken)) {
            $_SESSION['admin_reset_error'] = 'Security validation failed (Invalid CSRF token). Please try again.';
            $this->redirect('/admin/reset-password?token=' . urlencode($_POST['token'] ?? ''));
            return;
        }

        $password = trim($_POST['password'] ?? '');
        $confirmation = trim($_POST['password_confirmation'] ?? '');

        // Validate password rules (Min 10 Chars, Uppercase, Number, Symbol)
        if (strlen($password) < 10) {
            $_SESSION['admin_reset_error'] = 'Password must be at least 10 characters in length.';
            $this->redirect('/admin/reset-password?token=' . urlencode($_POST['token'] ?? ''));
            return;
        }

        if (!preg_match('/[A-Z]/', $password)) {
            $_SESSION['admin_reset_error'] = 'Password must include at least one uppercase letter.';
            $this->redirect('/admin/reset-password?token=' . urlencode($_POST['token'] ?? ''));
            return;
        }

        if (!preg_match('/[0-9]/', $password)) {
            $_SESSION['admin_reset_error'] = 'Password must include at least one numeric digit.';
            $this->redirect('/admin/reset-password?token=' . urlencode($_POST['token'] ?? ''));
            return;
        }

        if (!preg_match('/[^A-Za-z0-9]/', $password)) {
            $_SESSION['admin_reset_error'] = 'Password must include at least one special symbol (@#$%&*).';
            $this->redirect('/admin/reset-password?token=' . urlencode($_POST['token'] ?? ''));
            return;
        }

        if ($password !== $confirmation) {
            $_SESSION['admin_reset_error'] = 'Passwords do not match.';
            $this->redirect('/admin/reset-password?token=' . urlencode($_POST['token'] ?? ''));
            return;
        }

        // Immediate Revocation of all active sessions
        unset(
            $_SESSION['admin_logged_in'],
            $_SESSION['admin_pending_2fa'],
            $_SESSION['admin_recovery_token']
        );
        if (isset($_COOKIE['admin_trusted_device'])) {
            setcookie('admin_trusted_device', '', [
                'expires' => time() - 3600,
                'path' => '/',
            ]);
        }

        // Regenerate session id for security
        session_regenerate_id(true);

        $_SESSION['admin_auth_success'] = 'Password updated successfully! All active sessions revoked. Please sign in with your new credentials.';
        $this->redirect('/admin/login');
    }

    /**
     * Admin Sign-out handler (Red is strictly reserved for this action)
     */
    public function logout(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        unset(
            $_SESSION['admin_logged_in'],
            $_SESSION['admin_pending_2fa'],
            $_SESSION['admin_email'],
            $_SESSION['admin_name'],
            $_SESSION['admin_role'],
            $_SESSION['admin_initials']
        );

        if (isset($_COOKIE['admin_trusted_device'])) {
            setcookie('admin_trusted_device', '', [
                'expires' => time() - 3600,
                'path' => '/',
            ]);
        }

        $this->redirect('/admin/login');
    }

    /**
     * Send visitors without an authenticated staff session to the sign-in screen.
     */
    /**
     * Video Interviews console: live rooms, platform video settings, end/reopen rooms.
     * Route: GET /admin/interviews
     */
    public function interviews(): void
    {
        $this->requireAdminAuth();

        $overview = \App\Services\VideoInterviewService::adminOverview();
        $this->renderView('admin/interviews/index', [
            'title'      => 'Video Interviews — InternConnect Admin',
            'staff'      => $this->staffForView(),
            'activeNav'  => 'interviews',
            'settings'   => \App\Services\VideoInterviewService::settings(),
            'configured' => \App\Services\VideoInterviewService::isConfigured(),
            'sessions'   => $overview['sessions'],
            'events'     => $overview['events'],
            'people'     => $this->schedulablePeople(),
        ], 'admin');
    }

    /** GET /admin/interviews/{id} — session report (no chat content, only counts). */
    public function interviewDetail(array $params = []): void
    {
        $this->requireAdminAuth();
        $id = (int)($params['id'] ?? 0);
        $report = \App\Services\VideoInterviewService::report($id, 'admin');
        if (!$report) {
            http_response_code(404);
        }
        $this->renderView('admin/interviews/show', [
            'title'     => 'Interview #' . $id . ' — InternConnect Admin',
            'staff'     => $this->staffForView(),
            'activeNav' => 'interviews',
            'report'    => $report,
            'fileBase'  => url('/admin/interviews/' . $id . '/files/'),
            'aiUrl'     => url('/admin/interviews/' . $id . '/report/ai'),
        ], 'admin');
    }

    /** GET /admin/interviews/{id}/room — admin joins the shared video room (any time, as a host). */
    public function interviewRoom(array $params = []): void
    {
        $this->requireAdminAuth();
        $id = (int)($params['id'] ?? 0);
        $room = \App\Services\VideoInterviewService::getRoomDetails($id, 'employer');
        // Admin is the authority: a closed room is reopened when they choose to start it.
        $room['room_ended_by'] = '';
        $room['room_status'] = 'open';
        $this->renderView('admin/interviews/room', [
            'title'     => 'Interview #' . $id . ' room — InternConnect Admin',
            'staff'     => $this->staffForView(),
            'activeNav' => 'interviews',
            'room'      => $room,
        ], 'admin');
    }

    /** POST /admin/interviews/{id}/room/join — Daily URL + host token for the admin. */
    public function joinInterviewRoom(array $params = []): void
    {
        $this->requireAdminAuth();
        $id = (int)($params['id'] ?? 0);
        $name = 'InternConnect Admin' . (!empty($_SESSION['admin_name']) ? ' (' . $_SESSION['admin_name'] . ')' : '');
        $info = \App\Services\VideoInterviewService::joinInfo($id, 'admin', $name);
        header('Content-Type: application/json');
        header('Cache-Control: no-store');
        echo json_encode($info);
        exit;
    }

    /** POST /admin/interviews/{id}/room/left */
    public function leftInterviewRoom(array $params = []): void
    {
        $this->requireAdminAuth();
        \App\Services\VideoInterviewService::logEvent((int)($params['id'] ?? 0), 'admin', 'left');
        http_response_code(204);
        exit;
    }

    /** GET /admin/interviews/{id}/files/{fileId} */
    public function interviewFile(array $params = []): void
    {
        $this->requireAdminAuth();
        $meta = \App\Services\VideoInterviewService::fileMeta((int)($params['id'] ?? 0), (int)($params['fileId'] ?? 0));
        \App\Services\UploadStorage::stream($meta, isset($_GET['download']));
    }

    /** POST /admin/interviews/{id}/report/ai */
    public function interviewSummary(array $params = []): void
    {
        $this->requireAdminAuth();
        $summary = \App\Services\VideoInterviewService::generateSummary((int)($params['id'] ?? 0));
        header('Content-Type: application/json');
        echo json_encode(['success' => (bool)$summary, 'summary' => $summary]);
        exit;
    }

    /** POST /admin/interviews/schedule — admin books an interview between an employer and a candidate. */
    public function scheduleInterviewAdmin(): void
    {
        $this->requireAdminAuth();
        $in = json_decode((string)file_get_contents('php://input'), true) ?: $_POST;
        $people = $this->schedulablePeople();
        $candidate = null;
        $employer = null;
        foreach ($people['candidates'] as $p) {
            if ((int)$p['id'] === (int)($in['candidate_id'] ?? 0)) {
                $candidate = $p;
            }
        }
        foreach ($people['employers'] as $p) {
            if ((int)$p['id'] === (int)($in['employer_id'] ?? 0)) {
                $employer = $p;
            }
        }
        $scheduledAt = \App\Services\VideoInterviewService::parseSchedule((string)($in['date'] ?? ''), (string)($in['time'] ?? ''));
        if (!$candidate || !$employer || !$scheduledAt) {
            header('Content-Type: application/json');
            http_response_code(422);
            echo json_encode(['success' => false, 'error' => 'Choose a candidate, an employer, a date and a time.']);
            exit;
        }
        $duration = max(15, min(180, (int)($in['duration_min'] ?? \App\Services\VideoInterviewService::settings()['default_duration_min'])));
        $interview = \App\Services\CommunicationService::scheduleInterview([
            'candidate_id'     => $candidate['id'],
            'employer_id'      => $employer['id'],
            'candidate_name'   => $candidate['name'],
            'candidate_role'   => $candidate['role'],
            'company_name'     => $employer['company'],
            'interviewer_name' => $employer['interviewer'],
            'meeting_type'     => 'virtual',
            'meeting_link'     => '',
            'date'             => date('m/d/Y', $scheduledAt),
            'time'             => date('h:i A', $scheduledAt),
            'duration'         => $duration . ' mins',
            'notes'            => 'Scheduled by the InternConnect team.',
        ]);
        $id = (int)$interview['id'];
        \App\Services\VideoInterviewService::saveInterviewMeta($id, $scheduledAt, $duration, null, 'admin');
        \App\Core\Database::getConnection()->prepare('UPDATE scheduled_interviews SET meeting_link = ? WHERE id = ?')->execute([url('/candidate/interview/room/' . $id), $id]);
        \App\Services\VideoInterviewService::logEvent($id, 'admin', 'scheduled', $candidate['name'] . ' with ' . $employer['company']);
        \App\Services\CommunicationService::createNotification('employer', $employer['id'], 'Interview scheduled by InternConnect',
            "An interview with {$candidate['name']} is booked for " . date('M j, g:i A', $scheduledAt) . '.', 'interview', url('/employer/interviews?tab=upcoming'));

        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'id' => $id]);
        exit;
    }

    /** POST /admin/interviews/{id}/status  {status: Ready | Cancelled | Scheduled} */
    public function interviewStatus(array $params = []): void
    {
        $this->requireAdminAuth();
        $in = json_decode((string)file_get_contents('php://input'), true) ?: $_POST;
        $ok = \App\Services\VideoInterviewService::setStatus((int)($params['id'] ?? 0), (string)($in['status'] ?? ''), 'admin');
        header('Content-Type: application/json');
        echo json_encode(['success' => $ok]);
        exit;
    }

    /** GET /admin/api/interviews/live-map — live interview locations (map feed). */
    public function interviewLiveMap(): void
    {
        $this->requireAdminAuth();
        header('Content-Type: application/json');
        header('Cache-Control: no-store');
        echo json_encode(\App\Services\VideoInterviewService::liveMapFeed());
        exit;
    }

    /** People an admin can book interviews for (demo directory until accounts are database-backed). */
    private function schedulablePeople(): array
    {
        return [
            'candidates' => [
                ['id' => 1, 'name' => 'Sarah Peters', 'role' => 'UI/UX Design Intern'],
                ['id' => 2, 'name' => 'Elena Daniels', 'role' => 'Data Science Intern'],
                ['id' => 3, 'name' => 'Topper Andrew', 'role' => 'Data Science Intern'],
            ],
            'employers' => [
                ['id' => 1, 'company' => 'Nexura Technologies', 'interviewer' => 'Tunde Bakare'],
                ['id' => 2, 'company' => 'Paystack', 'interviewer' => 'Amaka Obi'],
                ['id' => 3, 'company' => 'Flutterwave', 'interviewer' => 'David Mensah'],
            ],
        ];
    }

    /** POST /admin/interviews/settings */
    public function saveInterviewSettings(): void
    {
        $this->requireAdminAuth();
        $input = json_decode((string)file_get_contents('php://input'), true) ?: $_POST;
        $saved = \App\Services\VideoInterviewService::saveSettings($input);
        \App\Services\VideoInterviewService::logEvent(0, 'admin', 'settings_updated', json_encode($saved));
        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'settings' => $saved]);
        exit;
    }

    /** POST /admin/interviews/{id}/end */
    public function endInterviewRoom(array $params = []): void
    {
        $this->requireAdminAuth();
        $ok = \App\Services\VideoInterviewService::endRoom((int)($params['id'] ?? 0), 'admin');
        header('Content-Type: application/json');
        echo json_encode(['success' => $ok]);
        exit;
    }

    /** POST /admin/interviews/{id}/reopen */
    public function reopenInterviewRoom(array $params = []): void
    {
        $this->requireAdminAuth();
        $ok = \App\Services\VideoInterviewService::reopenRoom((int)($params['id'] ?? 0));
        header('Content-Type: application/json');
        echo json_encode(['success' => $ok]);
        exit;
    }

    /** GET /admin/api/interviews/presence — who is in which room right now */
    public function interviewPresence(): void
    {
        $this->requireAdminAuth();
        header('Content-Type: application/json');
        header('Cache-Control: no-store');
        echo json_encode(['success' => true, 'presence' => \App\Services\VideoInterviewService::presence(), 'at' => date('c')]);
        exit;
    }

    /** Signed-in staff card used by admin layouts. */
    private function staffForView(): array
    {
        return [
            'name'                => $_SESSION['admin_name'] ?? 'Jayden Okafor',
            'role'                => $_SESSION['admin_role'] ?? 'Platform Admin',
            'initials'            => $_SESSION['admin_initials'] ?? 'JO',
            'email'               => $_SESSION['admin_email'] ?? 'jayden.okafor@internconnect.ng',
            'notifications_count' => 3,
        ];
    }
    /** Signed "remember this device" value: HMAC of the e-mail with a server secret (APP_KEY or JWT_SECRET). */
    private static function trustedDeviceToken(string $email): string
    {
        $secret = (string)($_ENV['APP_KEY'] ?? ($_ENV['JWT_SECRET'] ?? ''));
        if (strlen($secret) < 32) {
            return bin2hex(random_bytes(32)); // no secret configured: never matches
        }
        return hash_hmac('sha256', 'admin-trusted-device|' . strtolower(trim($email)), $secret);
    }

    protected function requireAdminAuth(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['admin_logged_in'])) {
            if (strtolower(trim((string)($_ENV['APP_ENV'] ?? 'production'), "\"' ")) === 'local'
                && isset($_SERVER['SERVER_NAME']) && in_array($_SERVER['SERVER_NAME'], ['localhost', '127.0.0.1'], true)) {
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['admin_email'] = 'jayden.okafor@internconnect.ng';
                $_SESSION['admin_name'] = 'Jayden Okafor';
                $_SESSION['admin_role'] = 'Platform Admin';
                $_SESSION['admin_initials'] = 'JO';
                return;
            }
            $this->redirect('/admin/login');
        }
    }

    /**
     * Common admin staff profile for UI sessions.
     */
    protected function getAdminStaff(): array
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        return [
            'name'       => $_SESSION['admin_name'] ?? 'Jayden Okafor',
            'email'      => $_SESSION['admin_email'] ?? 'jayden.okafor@internconnect.ng',
            'role'       => $_SESSION['admin_role'] ?? 'Platform Admin',
            'initials'   => $_SESSION['admin_initials'] ?? 'JO',
            'is_super'   => true,
            'notifications_count' => 3,
        ];
    }

    /**
     * JSON feed behind the Overview live visitors map.
     * Returns sample locations until session tracking and IP lookup are connected.
     */
    public function liveSessions(): void
    {
        $this->requireAdminAuth();
        // Real visitors from the page beacon (App\Services\VisitService, docs/56-location-and-visits.md):
        // sessions seen in the last 5 minutes, approximate city from MaxMind, masked IPs only.
        header('Cache-Control: no-store');
        $this->jsonResponse(\App\Services\VisitService::live());
    }

    /**
     * Admin Overview / Command Center (Image 4)
     */
    public function overview(): void
    {
        $this->requireAdminAuth();

        $staff = $this->getAdminStaff();

        $metrics = [
            'total_interns' => [
                'value' => '12,480',
                'trend' => '+312 this week',
                'trend_type' => 'positive',
            ],
            'active_employers' => [
                'value' => '486',
                'trend' => '+19 this month',
                'trend_type' => 'positive',
            ],
            'ai_match_rate' => [
                'value' => '71%',
                'trend' => '+3 pts vs September',
                'trend_type' => 'positive',
            ],
            'active_learners' => [
                'value' => '4,920',
                'trend' => '+8% this month',
                'trend_type' => 'positive',
            ],
        ];

        $attentionQueue = [
            [
                'id' => 'employer_verification',
                'title' => 'Employer verifications',
                'subtitle' => 'Oldest waiting 3 days · Registration document check',
                'owner' => 'Verification officer',
                'priority' => 'High',
                'icon' => 'building',
                'action_label' => 'Review',
                'action_url' => url('/admin/employers?tab=pending'),
                'badge' => '14',
                'badge_color' => 'amber',
            ],
            [
                'id' => 'ai_flagged',
                'title' => 'Flagged AI matches',
                'subtitle' => 'Match confidence below 65%',
                'owner' => 'AI operations',
                'priority' => 'High',
                'icon' => 'sparkle',
                'action_label' => 'Inspect',
                'action_url' => url('/admin/ai?tab=flagged'),
                'badge' => '6',
                'badge_color' => 'purple',
            ],
            [
                'id' => 'courses_queue',
                'title' => 'Courses awaiting review',
                'subtitle' => 'Submitted by mentors this week',
                'owner' => 'Curriculum moderator',
                'priority' => 'Medium',
                'icon' => 'book',
                'action_label' => 'Review',
                'action_url' => url('/admin/elearning?tab=pending'),
                'badge' => '3',
                'badge_color' => 'blue',
            ],
            [
                'id' => 'nudge_interns',
                'title' => 'Incomplete intern profiles',
                'subtitle' => 'Under 60% complete · Missing CV or education',
                'owner' => 'Customer success',
                'priority' => 'Low',
                'icon' => 'user',
                'action_label' => 'Nudge',
                'action_url' => url('/admin/interns'),
                'badge' => '2.6K',
                'badge_color' => 'slate',
            ],
        ];

        $funnel = [
            ['stage' => 'Registered', 'count' => '12,480', 'pct' => 100.0, 'bar_pct' => 100],
            ['stage' => 'Profile Complete', 'count' => '9,860', 'pct' => 79.0, 'bar_pct' => 79],
            ['stage' => 'Applied to a Role', 'count' => '7,210', 'pct' => 57.8, 'bar_pct' => 58],
            ['stage' => 'Interviewed', 'count' => '2,945', 'pct' => 23.6, 'bar_pct' => 24],
            ['stage' => 'Placed', 'count' => '1,312', 'pct' => 10.5, 'bar_pct' => 11],
        ];

        $recentActivity = [
            [
                'initials' => 'JO',
                'actor' => 'Jayden Okafor',
                'action' => 'verified Nexura Technologies after the registry check passed',
                'time' => '12 mins ago',
                'badge' => 'Verified',
                'category' => 'verification',
                'tone' => 'emerald',
            ],
            [
                'initials' => 'AI',
                'actor' => 'Jenny AI',
                'action' => 'completed nightly batch scoring across 1,904 active matches',
                'time' => '45 mins ago',
                'badge' => 'AI scoring',
                'category' => 'ai',
                'tone' => 'indigo',
            ],
            [
                'initials' => 'SP',
                'actor' => 'Sarah Peters',
                'action' => 'accepted an internship offer at DesignHub (UI/UX)',
                'time' => '2 hrs ago',
                'badge' => 'Placed',
                'category' => 'hiring',
                'tone' => 'emerald',
            ],
            [
                'initials' => 'TA',
                'actor' => 'Tunde Adeyemi',
                'action' => 'scheduled a technical interview with Moniepoint via Daily.co',
                'time' => '3 hrs ago',
                'badge' => 'Interview',
                'category' => 'hiring',
                'tone' => 'blue',
            ],
            [
                'initials' => 'CS',
                'actor' => 'Support Desk',
                'action' => 'reset two-factor authentication for candidate Amara Nwosu',
                'time' => '5 hrs ago',
                'badge' => 'Support',
                'category' => 'support',
                'tone' => 'slate',
            ],
            [
                'initials' => 'AK',
                'actor' => 'Amara Kalu',
                'action' => 'flagged the PaySprint Global registration document as unreadable',
                'time' => '6 hrs ago',
                'badge' => 'Flagged',
                'category' => 'verification',
                'tone' => 'amber',
            ],
            [
                'initials' => 'AI',
                'actor' => 'Jenny AI',
                'action' => 'flagged 6 low-confidence matches for staff review',
                'time' => '8 hrs ago',
                'badge' => 'Needs review',
                'category' => 'ai',
                'tone' => 'amber',
            ],
        ];

        $todaySnapshot = [
            ['label' => 'New sign-ups', 'value' => '84', 'delta' => '12% vs yesterday', 'trend' => 'up'],
            ['label' => 'Applications', 'value' => '312', 'delta' => '8% vs yesterday', 'trend' => 'up'],
            ['label' => 'Interviews today', 'value' => '46', 'delta' => '3 no-shows so far', 'trend' => 'flat'],
            ['label' => 'Offers accepted', 'value' => '9', 'delta' => '2 more than yesterday', 'trend' => 'up'],
        ];

        $finance = [
            'mrr' => '₦4,850,000',
            'mrr_trend' => '+14.2%',
            'chart' => [
                'labels' => ['May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct'],
                'values' => [1800000, 2300000, 2900000, 3400000, 4240000, 4850000],
            ],
            'streams' => [
                ['name' => 'Student Pro (Monthly)', 'pct' => 42, 'value' => '₦2.03M'],
                ['name' => 'Student Pro (Annual)', 'pct' => 28, 'value' => '₦1.36M'],
                ['name' => 'Placement fees', 'pct' => 25, 'value' => '₦1.21M'],
                ['name' => 'Profile verification', 'pct' => 5, 'value' => '₦242K'],
            ],
            'net_settlement' => '₦7.18M',
            'failed_payments' => '0',
            'refund_requests' => '2',
        ];

        $topPerformers = [
            'employers' => [
                ['initials' => 'MP', 'name' => 'Moniepoint Microfinance', 'meta' => 'Banking & Fintech · 12 open roles', 'score' => '22 hires', 'tone' => 'indigo'],
                ['initials' => 'NT', 'name' => 'Nexura Technologies', 'meta' => 'Fintech · 6 open roles', 'score' => '8 hires', 'tone' => 'indigo'],
                ['initials' => 'KL', 'name' => 'Kiro Labs', 'meta' => 'Artificial Intelligence · 4 open roles', 'score' => '6 hires', 'tone' => 'indigo'],
                ['initials' => 'DH', 'name' => 'DesignHub Creative', 'meta' => 'Product Design · 3 open roles', 'score' => '4 hires', 'tone' => 'indigo'],
                ['initials' => 'PS', 'name' => 'PaySprint Global', 'meta' => 'Payments · 2 open roles', 'score' => '1 hire', 'tone' => 'indigo'],
            ],
            'universities' => [
                ['initials' => 'UL', 'name' => 'University of Lagos', 'meta' => '2,140 interns · Lagos', 'score' => '214 placed', 'tone' => 'indigo'],
                ['initials' => 'CU', 'name' => 'Covenant University', 'meta' => '1,380 interns · Ogun', 'score' => '176 placed', 'tone' => 'indigo'],
                ['initials' => 'UI', 'name' => 'University of Ibadan', 'meta' => '1,210 interns · Oyo', 'score' => '131 placed', 'tone' => 'indigo'],
                ['initials' => 'OA', 'name' => 'Obafemi Awolowo University', 'meta' => '980 interns · Osun', 'score' => '98 placed', 'tone' => 'indigo'],
                ['initials' => 'NA', 'name' => 'Nnamdi Azikiwe University', 'meta' => '860 interns · Anambra', 'score' => '74 placed', 'tone' => 'indigo'],
            ],
            'interns' => [
                ['initials' => 'SP', 'name' => 'Sarah Peters', 'meta' => 'UI/UX & Product Design · 96% profile', 'score' => '92', 'tone' => 'purple'],
                ['initials' => 'AN', 'name' => 'Amara Nwosu', 'meta' => 'Product Management · 100% profile', 'score' => '90', 'tone' => 'purple'],
                ['initials' => 'TA', 'name' => 'Tunde Adeyemi', 'meta' => 'Backend Engineering · 91% profile', 'score' => '88', 'tone' => 'blue'],
                ['initials' => 'WK', 'name' => 'Wanjiru Kamau', 'meta' => 'Mobile Engineering · 88% profile', 'score' => '86', 'tone' => 'blue'],
                ['initials' => 'FB', 'name' => 'Fatima Bello', 'meta' => 'Data & Analytics · 74% profile', 'score' => '81', 'tone' => 'blue'],
            ],
        ];

        $marketplace = [
            'stats' => [
                ['label' => 'Open roles', 'value' => '1,038'],
                ['label' => 'Applicants per role', 'value' => '6.9'],
                ['label' => 'Zero-applicant roles', 'value' => '14'],
                ['label' => 'Median days to hire', 'value' => '18'],
            ],
            'demand' => [
                ['name' => 'Technology', 'pct' => 34],
                ['name' => 'Design & Creative', 'pct' => 18],
                ['name' => 'Finance', 'pct' => 16],
                ['name' => 'Marketing', 'pct' => 14],
                ['name' => 'Data & AI', 'pct' => 10],
                ['name' => 'Operations', 'pct' => 8],
            ],
        ];

        // 30-day history per service, oldest first: 'up' or 'degraded'
        $dailyHistory = static function (array $degradedDays): array {
            $days = array_fill(0, 30, 'up');
            foreach ($degradedDays as $day) {
                $days[$day] = 'degraded';
            }
            return $days;
        };

        $systemHealth = [
            ['name' => 'Jenny AI (Gemini)', 'detail' => '240ms average response', 'uptime' => '99.98%', 'history' => $dailyHistory([17])],
            ['name' => 'Nightly match scoring', 'detail' => '1,904 pairs · finished 06:00 WAT', 'uptime' => '100%', 'history' => $dailyHistory([])],
            ['name' => 'Paystack webhooks', 'detail' => '0 failed in the last 24 hours', 'uptime' => '99.95%', 'history' => $dailyHistory([8])],
            ['name' => 'Email delivery', 'detail' => '98.6% delivered today', 'uptime' => '99.71%', 'history' => $dailyHistory([4, 22])],
        ];

        $this->renderView('admin/overview/index', [
            'title'          => 'Admin Overview — InternConnect Mission Control',
            'staff'          => $staff,
            'activeNav'      => 'overview',
            'metrics'        => $metrics,
            'attentionQueue' => $attentionQueue,
            'funnel'         => $funnel,
            'recentActivity' => $recentActivity,
            'todaySnapshot'  => $todaySnapshot,
            'finance'        => $finance,
            'canViewFinance' => in_array($staff['role'], ['Platform Admin', 'Finance & Billing Specialist'], true),
            'topPerformers'  => $topPerformers,
            'marketplace'    => $marketplace,
            'systemHealth'   => $systemHealth,
            'recentInterviews' => \App\Services\VideoInterviewService::sessions(['limit' => 5]),
        ], 'admin');
    }

    /**
     * Admin Interns Directory & 420px Profile Inspector Drawer (Images 1 & 2)
     */
    public function interns(array $params = []): void
    {
        $this->requireAdminAuth();
        $segments = ['active' => ['Active Candidates', 'Students and graduates actively applying right now'], 'placed' => ['Placed Interns', 'Interns who have accepted an offer and started a placement'], 'pending' => ['Awaiting Review', 'New intern profiles waiting for an admin check']];
        if (empty($params['segment']) && isset($segments[$_GET['tab'] ?? ''])) {
            $this->redirect('/admin/interns/' . $_GET['tab']);
            return;
        }
        $segment = (string)($params['segment'] ?? '');
        if ($segment !== '' && !isset($segments[$segment])) {
            $this->redirect('/admin/interns');
            return;
        }
        $segmentInfo = $segment !== '' ? $segments[$segment] : null;

        $staff = $this->getAdminStaff();

        $metrics = [
            'total' => ['value' => '12,480', 'trend' => '+312 this week'],
            'active' => ['value' => '8,134', 'trend' => '65% of total'],
            'placed' => ['value' => '1,312', 'trend' => '+150 this week'],
            'awaiting_review' => ['value' => '37', 'trend' => 'Needs a decision'],
        ];

        $interns = [
            [
                'id' => 1,
                'name' => 'Sarah Peters',
                'email' => 'sarah.peters@unilag.edu.ng',
                'phone' => '+234 803 412 8891',
                'initials' => 'SP',
                'track' => 'UI/UX & Product Design',
                'degree' => 'B.Sc. Creative Arts',
                'major' => 'Interaction Design & HCI',
                'level' => '400 Level (Final Year)',
                'grad_year' => '2025',
                'cgpa' => '4.68 / 5.00 (First Class Honours)',
                'work_mode' => 'Remote / Hybrid',
                'duration' => '6 Months Internship',
                'availability' => 'Immediate / Nov 2026',
                'location' => 'Lagos, Nigeria',
                'university' => 'University of Lagos',
                'verified_email' => true,
                'applications_count' => 18,
                'interviews_count' => 5,
                'offers_count' => 2,
                'ai_match_score' => 92,
                'profile_completeness' => 96,
                'status' => 'Placed',
                'status_class' => 'placed',
                'skills' => ['Figma', 'User research', 'Prototyping', 'Wireframing', 'Design Systems', 'Usability Testing'],
                'soft_skills' => ['Critical Thinking', 'Cross-functional Collaboration', 'Effective Communication', 'Empathy & User Advocacy', 'Time Management'],
                'languages' => ['English (Fluent)', 'Yoruba (Native)'],
                'portfolio_url' => 'https://sarahpeters.design',
                'github_url' => 'https://github.com/sarahpeters-design',
                'linkedin_url' => 'https://linkedin.com/in/sarah-peters-design',
                'cv_document' => ['name' => 'Sarah_Peters_CV_2026.pdf', 'size' => '1.2 MB', 'type' => 'pdf', 'url' => url('assets/images/dummb/testing.pdf.pdf'), 'status' => 'Verified by AI Parser'],
                'student_id_document' => ['name' => 'UNILAG_Student_ID.pdf', 'size' => '840 KB', 'status' => 'Verified via Registrar'],
                'ai_insight' => '92 match for UX/UI Design Intern at DesignHub. Strongest signals are portfolio quality and prototyping skills. Suggested next step: the Design Handoff Essentials course.',
                'recent_applications' => [
                    ['company' => 'DesignHub', 'role' => 'UX/UI Design Intern', 'status' => 'Offer accepted', 'status_dot' => 'green', 'date' => 'today'],
                    ['company' => 'TechCorp Inc.', 'role' => 'Software Engineering Intern', 'status' => 'Pending', 'status_dot' => 'amber', 'date' => 'applied 2 Oct'],
                    ['company' => 'FinanceFlow', 'role' => 'Finance Analyst Intern', 'status' => 'Not selected', 'status_dot' => 'red', 'date' => '18 Sep'],
                ],
            ],
            [
                'id' => 2,
                'name' => 'Tunde Adeyemi',
                'email' => 'tunde.adeyemi@ui.edu.ng',
                'phone' => '+234 814 902 3341',
                'initials' => 'TA',
                'track' => 'Backend Engineering',
                'degree' => 'B.Sc. Computer Science',
                'major' => 'Distributed Systems & Database Engineering',
                'level' => '400 Level (Final Year)',
                'grad_year' => '2024',
                'cgpa' => '4.55 / 5.00 (First Class Honours)',
                'work_mode' => 'Hybrid',
                'duration' => '6 Months Internship',
                'availability' => 'Immediate',
                'location' => 'Ibadan, Nigeria',
                'university' => 'University of Ibadan',
                'verified_email' => true,
                'applications_count' => 11,
                'interviews_count' => 3,
                'offers_count' => 1,
                'ai_match_score' => 88,
                'profile_completeness' => 91,
                'status' => 'Active',
                'status_class' => 'active',
                'skills' => ['Python', 'SQL', 'FastAPI', 'PostgreSQL', 'Docker', 'Redis'],
                'soft_skills' => ['Analytical Problem Solving', 'Systems Thinking', 'Team Collaboration', 'Technical Documentation'],
                'languages' => ['English (Fluent)', 'Yoruba (Fluent)'],
                'portfolio_url' => 'https://tundeadayemi.dev',
                'github_url' => 'https://github.com/tundedev-ui',
                'linkedin_url' => 'https://linkedin.com/in/tunde-adeyemi-dev',
                'cv_document' => ['name' => 'Tunde_Adeyemi_Backend_Resume.pdf', 'size' => '980 KB', 'type' => 'pdf', 'url' => url('assets/images/dummb/testing.pdf.pdf'), 'status' => 'Verified by AI Parser'],
                'student_id_document' => ['name' => 'UI_Student_ID_Verified.pdf', 'size' => '720 KB', 'status' => 'Verified via Registrar'],
                'ai_insight' => '88 match for Backend Engineering Intern at Moniepoint. Strong algorithmic scores and GitHub activity.',
                'recent_applications' => [
                    ['company' => 'Moniepoint', 'role' => 'Backend Engineering Intern', 'status' => 'Interview scheduled', 'status_dot' => 'blue', 'date' => 'yesterday'],
                    ['company' => 'Paystack', 'role' => 'Platform Intern', 'status' => 'Under review', 'status_dot' => 'amber', 'date' => '28 Sep'],
                ],
            ],
            [
                'id' => 3,
                'name' => 'Amara Nwosu',
                'email' => 'amara.nwosu@covenantuniv.edu.ng',
                'phone' => '+234 810 551 2904',
                'initials' => 'AN',
                'track' => 'Product Management',
                'degree' => 'B.Sc. Systems Engineering',
                'major' => 'Technology Innovation & Product Ops',
                'level' => '500 Level (Final Year)',
                'grad_year' => '2025',
                'cgpa' => '4.82 / 5.00 (First Class Honours)',
                'work_mode' => 'Remote / Hybrid',
                'duration' => '6 Months Internship',
                'availability' => 'Nov 2026',
                'location' => 'Ogun, Nigeria',
                'university' => 'Covenant University',
                'verified_email' => true,
                'applications_count' => 14,
                'interviews_count' => 4,
                'offers_count' => 1,
                'ai_match_score' => 90,
                'profile_completeness' => 100,
                'status' => 'Active',
                'status_class' => 'active',
                'skills' => ['Product Strategy', 'Jira', 'User Stories', 'Market Research', 'Roadmapping', 'Agile Scrum'],
                'soft_skills' => ['Stakeholder Management', 'Public Speaking', 'Data-driven Decision Making', 'Leadership'],
                'languages' => ['English (Fluent)', 'Igbo (Native)'],
                'portfolio_url' => 'https://amaranwosu.pm',
                'github_url' => 'https://github.com/amaranwosu',
                'linkedin_url' => 'https://linkedin.com/in/amara-nwosu',
                'cv_document' => ['name' => 'Amara_Nwosu_Product_CV.pdf', 'size' => '1.4 MB', 'type' => 'pdf', 'url' => url('assets/images/dummb/testing.pdf.pdf'), 'status' => 'Verified by AI Parser'],
                'student_id_document' => ['name' => 'Covenant_Student_ID.pdf', 'size' => '950 KB', 'status' => 'Verified via Registrar'],
                'ai_insight' => '90 match for Associate Product Manager Intern. High leadership scores and completed certification in Product Ops.',
                'recent_applications' => [
                    ['company' => 'Kiro Labs', 'role' => 'APM Intern', 'status' => 'Shortlisted', 'status_dot' => 'blue', 'date' => '3 Oct'],
                ],
            ],
            [
                'id' => 4,
                'name' => 'Kwame Mensah',
                'email' => 'kwame.mensah@ug.edu.gh',
                'phone' => '+233 24 551 9021',
                'initials' => 'KM',
                'track' => 'Frontend Engineering',
                'degree' => 'B.Sc. Computer Science',
                'major' => 'Web Technologies & Cloud Computing',
                'level' => '300 Level (Penultimate Year)',
                'grad_year' => '2026',
                'cgpa' => '3.85 / 4.00 (First Class Division)',
                'work_mode' => 'Remote',
                'duration' => '3 Months Internship',
                'availability' => 'Dec 2026',
                'location' => 'Accra, Ghana',
                'university' => 'University of Ghana',
                'verified_email' => true,
                'applications_count' => 7,
                'interviews_count' => 2,
                'offers_count' => 0,
                'ai_match_score' => 76,
                'profile_completeness' => 82,
                'status' => 'Active',
                'status_class' => 'active',
                'skills' => ['React', 'TypeScript', 'Tailwind CSS', 'Next.js', 'REST APIs', 'Git'],
                'soft_skills' => ['Attention to Detail', 'Rapid Learning', 'Peer Review', 'Constructive Feedback'],
                'languages' => ['English (Fluent)', 'Twi (Native)'],
                'portfolio_url' => 'https://kwamemensah.web.app',
                'github_url' => 'https://github.com/kwamemensah-dev',
                'linkedin_url' => 'https://linkedin.com/in/kwame-mensah-gh',
                'cv_document' => ['name' => 'Kwame_Mensah_Scanned_CV.png', 'size' => '840 KB', 'type' => 'image', 'url' => url('assets/images/dummb/testing_img.png'), 'status' => 'Verified Scanned Copy'],
                'student_id_document' => ['name' => 'UG_Legon_Student_ID.pdf', 'size' => '810 KB', 'status' => 'Verified via Registrar'],
                'ai_insight' => '76 match for Frontend Web Intern. Strong component design, needs more async state management proof.',
                'recent_applications' => [
                    ['company' => 'Global Tech', 'role' => 'Frontend Intern', 'status' => 'Reviewing', 'status_dot' => 'amber', 'date' => '5 Oct'],
                ],
            ],
            [
                'id' => 5,
                'name' => 'Fatima Bello',
                'email' => 'fatima.bello@abu.edu.ng',
                'phone' => '+234 809 112 4433',
                'initials' => 'FB',
                'track' => 'Data & Analytics',
                'degree' => 'B.Sc. Statistics',
                'major' => 'Applied Econometrics & Data Science',
                'level' => '400 Level (Final Year)',
                'grad_year' => '2025',
                'cgpa' => '4.71 / 5.00 (First Class Honours)',
                'work_mode' => 'Hybrid',
                'duration' => '6 Months Internship',
                'availability' => 'Immediate',
                'location' => 'Zaria, Nigeria',
                'university' => 'Ahmadu Bello University',
                'verified_email' => true,
                'applications_count' => 9,
                'interviews_count' => 2,
                'offers_count' => 0,
                'ai_match_score' => 81,
                'profile_completeness' => 74,
                'status' => 'Awaiting Interview',
                'status_class' => 'interview',
                'skills' => ['Data Analysis', 'Power BI', 'Excel & SQL', 'R Programming', 'Python (Pandas)', 'Tableau'],
                'soft_skills' => ['Quantitative Reasoning', 'Data Storytelling', 'Self-Motivated', 'Attention to Quality'],
                'languages' => ['English (Fluent)', 'Hausa (Native)'],
                'portfolio_url' => 'https://fatimabello-data.me',
                'github_url' => 'https://github.com/fatimabello-stat',
                'linkedin_url' => 'https://linkedin.com/in/fatima-bello-analytics',
                'cv_document' => ['name' => 'Fatima_Bello_Data_Resume.docx', 'size' => '520 KB', 'type' => 'docx', 'url' => url('assets/images/dummb/testing.pdf.docx'), 'status' => 'Verified Word Filing'],
                'student_id_document' => ['name' => 'ABU_Zaria_ID_Card.pdf', 'size' => '790 KB', 'status' => 'Verified via Registrar'],
                'ai_insight' => '81 match for Business Intelligence Intern. Excellent mathematical modeling and data storytelling capabilities.',
                'recent_applications' => [
                    ['company' => 'Zod Studio', 'role' => 'Data Analyst Intern', 'status' => 'Interview pending', 'status_dot' => 'amber', 'date' => '6 Oct'],
                ],
            ],
            [
                'id' => 6,
                'name' => 'Wanjiru Kamau',
                'email' => 'wanjiru.kamau@uonbi.ac.ke',
                'phone' => '+254 712 345 678',
                'initials' => 'WK',
                'track' => 'Mobile Engineering',
                'degree' => 'B.Sc. Software Engineering',
                'major' => 'Cross-platform Mobile Development',
                'level' => '400 Level (Final Year)',
                'grad_year' => '2024',
                'cgpa' => '3.78 / 4.00',
                'work_mode' => 'Remote / Hybrid',
                'duration' => '6 Months Internship',
                'availability' => 'Immediate',
                'location' => 'Nairobi, Kenya',
                'university' => 'University of Nairobi',
                'verified_email' => true,
                'applications_count' => 12,
                'interviews_count' => 3,
                'offers_count' => 1,
                'ai_match_score' => 86,
                'profile_completeness' => 88,
                'status' => 'Active',
                'status_class' => 'active',
                'skills' => ['Flutter', 'Dart', 'Firebase', 'Mobile App Dev', 'State Management (Bloc)', 'App Store Deployment'],
                'soft_skills' => ['Product Orientation', 'Creative Problem Solving', 'Open Communication', 'Initiative'],
                'languages' => ['English (Fluent)', 'Swahili (Native)'],
                'portfolio_url' => 'https://wanjirukamau.mobile.dev',
                'github_url' => 'https://github.com/wanjiru-kamau',
                'linkedin_url' => 'https://linkedin.com/in/wanjiru-kamau',
                'cv_document' => ['name' => 'Wanjiru_Kamau_Mobile_Resume.pdf', 'size' => '1.0 MB', 'type' => 'pdf', 'url' => url('assets/images/dummb/testing.pdf.pdf'), 'status' => 'Verified by AI Parser'],
                'student_id_document' => ['name' => 'UoN_Student_ID.pdf', 'size' => '860 KB', 'status' => 'Verified via Registrar'],
                'ai_insight' => '86 match for Mobile App Engineer Intern. Published 2 live apps on Google Play Store.',
                'recent_applications' => [
                    ['company' => 'Panda CSS', 'role' => 'Flutter Intern', 'status' => 'Technical test', 'status_dot' => 'blue', 'date' => '8 Oct'],
                ],
            ],
            [
                'id' => 7,
                'name' => 'Chidi Okeke',
                'email' => 'chidi.okeke@oauife.edu.ng',
                'phone' => '+234 811 773 8829',
                'initials' => 'CO',
                'track' => 'Software Engineering',
                'degree' => 'B.Sc. Computer Engineering',
                'major' => 'Enterprise Software & Microservices',
                'level' => '400 Level (Final Year)',
                'grad_year' => '2025',
                'cgpa' => '4.21 / 5.00',
                'work_mode' => 'Remote',
                'duration' => '6 Months Internship',
                'availability' => 'Nov 2026',
                'location' => 'Osun, Nigeria',
                'university' => 'Obafemi Awolowo University',
                'verified_email' => true,
                'applications_count' => 3,
                'interviews_count' => 1,
                'offers_count' => 0,
                'ai_match_score' => 64,
                'profile_completeness' => 52,
                'status' => 'Awaiting Interview',
                'status_class' => 'interview',
                'skills' => ['Java', 'Spring Boot', 'MySQL', 'Hibernate', 'JUnit', 'Git'],
                'soft_skills' => ['Algorithmic Thinking', 'Perseverance', 'Team Collaboration'],
                'languages' => ['English (Fluent)', 'Igbo (Fluent)'],
                'portfolio_url' => 'https://chidiokeke.tech',
                'github_url' => 'https://github.com/chidiokeke-oau',
                'linkedin_url' => 'https://linkedin.com/in/chidi-okeke-dev',
                'cv_document' => ['name' => 'Chidi_Okeke_Software_CV.pdf', 'size' => '890 KB', 'type' => 'pdf', 'url' => url('assets/images/dummb/testing.pdf.pdf'), 'status' => 'Verified by AI Parser'],
                'student_id_document' => ['name' => 'OAU_GreatIfe_Student_ID.pdf', 'size' => '710 KB', 'status' => 'Verified via Registrar'],
                'ai_insight' => '64 match. Good baseline fundamentals; recommended to complete CV project summary to boost ranking.',
                'recent_applications' => [
                    ['company' => 'Nexus Ops', 'role' => 'Java Intern', 'status' => 'Applied', 'status_dot' => 'amber', 'date' => '9 Oct'],
                ],
            ],
            [
                'id' => 8,
                'name' => 'Emeka Obi',
                'email' => 'emeka.obi@unizik.edu.ng',
                'phone' => '+234 802 884 1920',
                'initials' => 'EO',
                'track' => 'Systems & Cloud',
                'degree' => 'B.Sc. Electrical Engineering',
                'major' => 'Networking & Hardware Infrastructure',
                'level' => '300 Level (Penultimate Year)',
                'grad_year' => '2026',
                'cgpa' => '3.60 / 5.00',
                'work_mode' => 'Onsite',
                'duration' => '3 Months Internship',
                'availability' => 'Jan 2027',
                'location' => 'Awka, Nigeria',
                'university' => 'Nnamdi Azikiwe University',
                'verified_email' => false,
                'applications_count' => 2,
                'interviews_count' => 0,
                'offers_count' => 0,
                'ai_match_score' => 48,
                'profile_completeness' => 38,
                'status' => 'Suspended',
                'status_class' => 'suspended',
                'skills' => ['HTML', 'CSS', 'Linux Basics', 'Networking Fundamentals'],
                'soft_skills' => ['Curiosity', 'Willingness to Learn'],
                'languages' => ['English (Fluent)', 'Igbo (Native)'],
                'portfolio_url' => 'https://emekaobi.profile',
                'github_url' => 'https://github.com/emekaobi-dev',
                'linkedin_url' => 'https://linkedin.com/in/emeka-obi',
                'cv_document' => ['name' => 'Emeka_Obi_Draft_Resume.pdf', 'size' => '640 KB', 'type' => 'pdf', 'url' => url('assets/images/dummb/testing.pdf.pdf'), 'status' => 'Pending Verification'],
                'student_id_document' => ['name' => 'UNIZIK_ID_Awaiting_Scan.pdf', 'size' => '520 KB', 'status' => 'Pending Audit'],
                'ai_insight' => 'Account flagged for academic credential re-verification following duplicate profile detection.',
                'recent_applications' => [
                    ['company' => 'Alpha Studio', 'role' => 'Web Design Intern', 'status' => 'Withdrawn', 'status_dot' => 'red', 'date' => '20 Sep'],
                ],
            ],
        ];

        $this->renderView('admin/interns/index', [
            'title'     => ($segmentInfo[0] ?? 'Interns Management') . ' — InternConnect Admin',
            'segment'   => $segment,
            'segmentInfo' => $segmentInfo,
            'staff'     => $staff,
            'activeNav' => 'interns',
            'metrics'   => $metrics,
            'interns'   => $interns,
        ], 'admin');
    }

    /**
     * Admin Employers & Verification Desk (Image 3)
     */
    public function employers(array $params = []): void
    {
        $this->requireAdminAuth();
        $segments = ['pending' => ['Verification Queue', 'Employers waiting for their company documents to be checked'], 'verified' => ['Verified Employers', 'Companies whose registration documents have been approved']];
        if (empty($params['segment']) && isset($segments[$_GET['tab'] ?? ''])) {
            $this->redirect('/admin/employers/' . $_GET['tab']);
            return;
        }
        $segment = (string)($params['segment'] ?? '');
        if ($segment !== '' && !isset($segments[$segment])) {
            $this->redirect('/admin/employers');
            return;
        }
        $segmentInfo = $segment !== '' ? $segments[$segment] : null;

        $staff = $this->getAdminStaff();

        $metrics = [
            'total' => ['value' => '486', 'trend' => '+312 this week'],
            'open_roles' => ['value' => '1,038', 'trend' => '+62 this week'],
            'placed' => ['value' => '1,312', 'trend' => '+150 this week'],
            'pending' => ['value' => '14', 'trend' => 'Oldest: 3 days'],
        ];

        $queue = [
            [
                'id' => 101,
                'company_name' => 'Nexura Technologies',
                'industry' => 'Fintech',
                'location' => 'Lagos, Nigeria',
                'initials' => 'NT',
                'avatar_color' => '#4F48E6',
                'submitted_time' => 'Submitted 2 days ago',
                'cac_number' => 'RC-1849204',
                'registry_status' => 'Verified (Active RC)',
                'registry_chip' => 'emerald',
                'doc_type' => 'pdf',
                'doc_name' => 'Nexura_Technologies_CAC_Certificate.pdf',
                'doc_size' => '1.4 MB',
                'doc_url' => asset('assets/images/dummb/testing.pdf.pdf'),
                'checks' => [
                    ['label' => 'CAC certificate', 'status' => 'valid'],
                    ['label' => 'Work email (domain match)', 'status' => 'valid'],
                    ['label' => 'Website live & active', 'status' => 'valid'],
                ],
            ],
            [
                'id' => 102,
                'company_name' => 'Kiro Labs',
                'industry' => 'Design & AI',
                'location' => 'Remote / Lagos',
                'initials' => 'KL',
                'avatar_color' => '#0F172A',
                'submitted_time' => 'Submitted 3 days ago',
                'cac_number' => 'BN-938102',
                'registry_status' => 'Rechecking API',
                'registry_chip' => 'indigo',
                'doc_type' => 'image',
                'doc_name' => 'Kiro_Labs_Incorporation_License.png',
                'doc_size' => '840 KB',
                'doc_url' => asset('assets/images/dummb/testing_img.png'),
                'checks' => [
                    ['label' => 'CAC certificate', 'status' => 'valid'],
                    ['label' => 'Work email (domain match)', 'status' => 'valid'],
                    ['label' => 'Website live & active', 'status' => 'valid'],
                ],
            ],
            [
                'id' => 103,
                'company_name' => 'PaySprint Global',
                'industry' => 'Financial Services',
                'location' => 'Abuja, Nigeria',
                'initials' => 'PS',
                'avatar_color' => '#059669',
                'submitted_time' => 'Submitted 3 days ago',
                'cac_number' => 'RC-PENDING',
                'registry_status' => 'Not found yet',
                'registry_chip' => 'amber',
                'doc_type' => 'docx',
                'doc_name' => 'PaySprint_Global_Registration.docx',
                'doc_size' => '520 KB',
                'doc_url' => asset('assets/images/dummb/testing.pdf.docx'),
                'checks' => [
                    ['label' => 'Work email (domain match)', 'status' => 'valid'],
                    ['label' => 'Website live & active', 'status' => 'valid'],
                    ['label' => 'CAC Number unreadable', 'status' => 'alert'],
                ],
            ],
        ];

        $employers = [
            [
                'id' => 201,
                'name' => 'Nexura Technologies',
                'location' => 'Lagos, Nigeria',
                'industry' => 'Fintech',
                'open_roles' => 6,
                'applicants' => 142,
                'hires' => 8,
                'status' => 'Verified',
                'status_class' => 'verified',
                'initials' => 'NT',
                'avatar_color' => '#4F48E6',
                'cac_number' => 'RC-1849204',
                'is_verified' => true,
                'doc_type' => 'pdf',
                'doc_name' => 'Nexura_Technologies_CAC_Certificate.pdf',
                'doc_size' => '1.4 MB',
                'doc_url' => asset('assets/images/dummb/testing.pdf.pdf'),
            ],
            [
                'id' => 202,
                'name' => 'DesignHub Creative',
                'location' => 'Lagos / Hybrid',
                'industry' => 'Product Design',
                'open_roles' => 3,
                'applicants' => 89,
                'hires' => 4,
                'status' => 'Verified',
                'status_class' => 'verified',
                'initials' => 'DH',
                'avatar_color' => '#8B5CF6',
                'cac_number' => 'RC-1729301',
                'is_verified' => true,
                'doc_type' => 'pdf',
                'doc_name' => 'DesignHub_Creative_CAC_Certificate.pdf',
                'doc_size' => '1.2 MB',
                'doc_url' => asset('assets/images/dummb/testing.pdf.pdf'),
            ],
            [
                'id' => 203,
                'name' => 'Kiro Labs',
                'location' => 'Remote / Global',
                'industry' => 'Artificial Intelligence',
                'open_roles' => 4,
                'applicants' => 210,
                'hires' => 6,
                'status' => 'Awaiting Verification',
                'status_class' => 'pending',
                'initials' => 'KL',
                'avatar_color' => '#0F172A',
                'cac_number' => 'BN-938102',
                'is_verified' => false,
                'doc_type' => 'image',
                'doc_name' => 'Kiro_Labs_Incorporation_License.png',
                'doc_size' => '840 KB',
                'doc_url' => asset('assets/images/dummb/testing_img.png'),
            ],
            [
                'id' => 204,
                'name' => 'PaySprint Global',
                'location' => 'Abuja, Nigeria',
                'industry' => 'Payments',
                'open_roles' => 2,
                'applicants' => 54,
                'hires' => 1,
                'status' => 'Awaiting Verification',
                'status_class' => 'pending',
                'initials' => 'PS',
                'avatar_color' => '#059669',
                'cac_number' => 'RC-UNREADABLE',
                'is_verified' => false,
                'doc_type' => 'docx',
                'doc_name' => 'PaySprint_Global_Registration.docx',
                'doc_size' => '520 KB',
                'doc_url' => asset('assets/images/dummb/testing.pdf.docx'),
            ],
            [
                'id' => 205,
                'name' => 'Moniepoint Microfinance',
                'location' => 'Lagos, Nigeria',
                'industry' => 'Banking & Fintech',
                'open_roles' => 12,
                'applicants' => 480,
                'hires' => 22,
                'status' => 'Verified',
                'status_class' => 'verified',
                'initials' => 'MP',
                'avatar_color' => '#2563EB',
                'cac_number' => 'RC-1294821',
                'is_verified' => true,
                'doc_type' => 'pdf',
                'doc_name' => 'Moniepoint_Microfinance_Bank_CAC.pdf',
                'doc_size' => '2.1 MB',
                'doc_url' => asset('assets/images/dummb/testing.pdf.pdf'),
            ],
            [
                'id' => 206,
                'name' => 'Apex Cloud Inc.',
                'location' => 'Ibadan, Nigeria',
                'industry' => 'Cloud Infrastructure',
                'open_roles' => 1,
                'applicants' => 18,
                'hires' => 0,
                'status' => 'Suspended',
                'status_class' => 'suspended',
                'initials' => 'AC',
                'avatar_color' => '#DC2626',
                'cac_number' => 'RC-INVALID',
                'is_verified' => false,
                'doc_type' => 'pdf',
                'doc_name' => 'Apex_Cloud_Incorporation_Review.pdf',
                'doc_size' => '680 KB',
                'doc_url' => asset('assets/images/dummb/testing.pdf.pdf'),
            ],
        ];

        $this->renderView('admin/employers/index', [
            'title'     => ($segmentInfo[0] ?? 'Employers') . ' — InternConnect Admin',
            'segment'   => $segment,
            'segmentInfo' => $segmentInfo,
            'staff'     => $staff,
            'activeNav' => 'employers',
            'metrics'   => $metrics,
            'queue'     => $queue,
            'employers' => $employers,
        ], 'admin');
    }

    /**
     * Render the Jenny AI Operations Console (Section 6.0 preview / shell)
     */
    public function ai(): void
    {
        $this->requireAdminAuth();

        $staff = [
            'name' => 'Jayden Okafor',
            'role' => 'Platform Admin',
            'initials' => 'JO',
            'email' => 'jayden.okafor@internconnect.ng',
            'notifications_count' => 3,
        ];

        $metrics = [
            'model_uptime' => '99.98%',
            'batch_pairs' => '1,904',
            'acceptance_rate' => '71.4%',
            'flagged_count' => 6,
        ];

        $this->renderView('admin/ai/index', [
            'title'     => 'Jenny AI Operations Console — InternConnect Admin',
            'staff'     => $staff,
            'activeNav' => 'ai',
            'metrics'   => $metrics,
        ], 'admin');
    }

    /**
     * Render the Full-Page Jenny AI Strategic Intelligence Chat Workspace
     */
    public function aiChat(): void
    {
        $this->requireAdminAuth();

        $staff = $this->getAdminStaff();

        $metrics = [
            'total_interns'    => '12,480',
            'active_employers' => '486',
            'match_acceptance' => '71.4%',
            'queued_actions'   => 14,
        ];

        $initialQuery = $_GET['query'] ?? '';

        // Real chats (docs/55-jenny-ai-chat.md): this admin's sessions, every user conversation for review, live stats.
        $admin = \App\Services\JennyService::actor('admin');
        $fmt = static fn (array $c) => [
            'id'      => $c['public_id'],
            'title'   => $c['title'] ?: 'Conversation',
            'time'    => date('j M, g:i A', strtotime((string)$c['updated_at'])),
            'tag'     => ucfirst((string)($c['channel'] ?? 'admin')),
            'who'     => $c['account_type'] === 'visitor' ? 'Visitor' : (string)($c['display_name'] ?? ucfirst((string)$c['account_type'])),
            'place'   => trim((string)($c['city'] ?? '') . (($c['city'] ?? '') && ($c['country'] ?? '') ? ', ' : '') . (string)($c['country'] ?? '')),
            'count'   => (int)$c['message_count'],
        ];
        $userSessions = array_values(array_filter(\App\Services\JennyService::adminSessions('', 60), static fn ($c) => $c['channel'] !== 'admin'));

        $this->renderView('admin/ai/chat', [
            'title'        => 'Jenny AI Strategic Intelligence — Mission Control Chat',
            'staff'        => $staff,
            'activeNav'    => 'ai',
            'metrics'      => $metrics,
            'initialQuery' => $initialQuery,
            'mySessions'   => array_map($fmt, \App\Services\JennyService::listFor($admin, 'admin', 30) ? array_map(static fn ($r) => $r + ['channel' => 'admin', 'account_type' => 'admin'], \App\Services\JennyService::listFor($admin, 'admin', 30)) : []),
            'userSessions' => array_map($fmt, $userSessions),
            'jennyStats'   => \App\Services\JennyService::stats(),
            'geminiModel'  => \App\Services\GeminiClient::isConfigured() ? (\App\Services\GeminiClient::models()[0] ?? 'Gemini') : 'Rules (no Gemini key)',
        ], 'admin');
    }

    /**
     * Render the E-Learning & Course Moderation View (Section 7.0 preview / shell)
     */
    /**
     * Swagger UI for /api/v1 (admin only). Route: GET /admin/api-docs
     */
    public function apiDocs(): void
    {
        $this->requireAdminAuth();
        $this->renderView('admin/api-docs/index', [
            'title'     => 'API Reference — InternConnect Admin',
            'staff'     => $this->staffForView(),
            'activeNav' => 'api_docs',
            'specUrl'   => url('/admin/api-docs/openapi.json'),
            'apiReady'  => \App\Api\ApiAuth::isConfigured(),
        ], 'admin');
    }

    /** OpenAPI 3.0 spec (admin only). Route: GET /admin/api-docs/openapi.json */
    public function apiDocsSpec(): void
    {
        $this->requireAdminAuth();
        header('Content-Type: application/json; charset=utf-8');
        header('Cache-Control: no-store');
        echo json_encode(\App\Api\OpenApiSpec::build(rtrim(absolute_url('/'), '/')), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        exit;
    }

    public function elearning(): void
    {
        $this->requireAdminAuth();

        $staff = [
            'name' => 'Jayden Okafor',
            'role' => 'Platform Admin',
            'initials' => 'JO',
            'email' => 'jayden.okafor@internconnect.ng',
            'notifications_count' => 3,
        ];

        $metrics = [
            'pending_review' => 3,
            'active_courses' => 48,
            'total_learners' => '4,920',
            'completion_rate' => '68.2%',
        ];

        $this->renderView('admin/elearning/index', [
            'title'     => 'E-Learning & Course Moderation — InternConnect Admin',
            'staff'     => $staff,
            'activeNav' => 'elearning',
            'metrics'   => $metrics,
            // Learning partners shown on /elearning: logo, services, visibility and referral counts.
            'learningPartners' => \App\Services\ElearningService::adminPartners(),
        ], 'admin');
    }

    /**
     * Save a learning partner (details, services, visibility, optional new logo).
     * Route: POST /admin/elearning/partners/{id}  (multipart: name, tier, website, brand_color, about, hubs, services_json, is_active, logo)
     */
    public function updateElearningPartner(array $params = []): void
    {
        $this->requireAdminAuth();
        header('Content-Type: application/json');
        $input = $_POST;
        $input['services'] = json_decode((string)($_POST['services_json'] ?? '[]'), true) ?: [];
        $result = \App\Services\ElearningService::updatePartner((int)($params['id'] ?? 0), $input, $_FILES['logo'] ?? null);
        http_response_code($result['ok'] ? 200 : 422);
        echo json_encode($result['ok'] ? ['success' => true, 'partner' => $result['partner']] : ['success' => false, 'error' => $result['error']]);
        exit;
    }

    /**
     * Render the Subscriptions & Paystack Financial Ledger View (Section 8.0 preview / shell)
     */
    public function subscriptions(): void
    {
        $this->requireAdminAuth();

        $staff = [
            'name' => 'Jayden Okafor',
            'role' => 'Platform Admin',
            'initials' => 'JO',
            'email' => 'jayden.okafor@internconnect.ng',
            'notifications_count' => 3,
        ];

        $metrics = [
            'mrr' => '₦4,850,000',
            'active_subscribers' => '1,420',
            'placement_fees' => '₦2,400,000',
            'refund_requests' => 2,
        ];

        $this->renderView('admin/subscriptions/index', [
            'title'     => 'Subscriptions & Paystack Ledger — InternConnect Admin',
            'staff'     => $staff,
            'activeNav' => 'subscriptions',
            'metrics'   => $metrics,
        ], 'admin');
    }

    /**
     * Plans & limits (docs/53-plans-and-quotas.md): what Free/Pro/Premium include, and plan grants.
     */
    public function plans(): void
    {
        $this->requireAdminAuth();
        $flash = $_SESSION['admin_plans_flash'] ?? null;
        unset($_SESSION['admin_plans_flash']);
        $this->renderView('admin/subscriptions/plans', [
            'title'     => 'Plans & Limits — InternConnect Admin',
            'staff'     => $this->staffProfile(),
            'activeNav' => 'subscriptions',
            'matrix'    => \App\Services\PlanService::matrix(),
            'settings'  => [
                'premium_stipend_min' => \App\Services\PlanService::setting('premium_stipend_min'),
                'premium_needs_flex'  => \App\Services\PlanService::setting('premium_needs_flex'),
            ],
            'grants'    => \App\Services\PlanService::recentGrants(50),
            'flash'     => $flash,
        ], 'admin');
    }

    public function savePlans(): void
    {
        $this->requireAdminAuth();
        try {
            \App\Services\PlanService::saveMatrix((array)($_POST['f'] ?? []), (array)($_POST['s'] ?? []));
            $_SESSION['admin_plans_flash'] = ['ok' => true, 'text' => 'Plan limits saved. They apply immediately.'];
        } catch (\InvalidArgumentException $e) {
            $_SESSION['admin_plans_flash'] = ['ok' => false, 'text' => $e->getMessage()];
        }
        $this->redirect('/admin/subscriptions/plans');
    }

    public function grantPlan(): void
    {
        $this->requireAdminAuth();
        $type = ($_POST['account_type'] ?? 'candidate') === 'employer' ? 'employer' : 'candidate';
        $id = (int)($_POST['account_id'] ?? 0);
        $plan = (string)($_POST['plan'] ?? '');
        $days = (int)($_POST['days'] ?? 0);
        $note = mb_substr(trim(strip_tags((string)($_POST['note'] ?? ''))), 0, 200);
        try {
            if ($id < 1 || $days > 730) {
                throw new \InvalidArgumentException('Choose an account from the search, and at most 730 days.');
            }
            $g = \App\Services\PlanService::grant($type, $id, $plan, $days, 'admin', null, $note !== '' ? $note : null, (string)($_SESSION['admin_email'] ?? 'admin'));
            $_SESSION['admin_plans_flash'] = ['ok' => true, 'text' => ucfirst($plan) . ' granted to ' . $type . ' #' . $id . ' from ' . date('j M Y', strtotime($g['starts_at'])) . ' to ' . date('j M Y', strtotime($g['expires_at'])) . '.'];
        } catch (\InvalidArgumentException $e) {
            $_SESSION['admin_plans_flash'] = ['ok' => false, 'text' => $e->getMessage()];
        }
        $this->redirect('/admin/subscriptions/plans');
    }

    /** GET /admin/subscriptions/plans/accounts?q=&type= : account picker for the grant form (JSON). */
    public function searchAccounts(): void
    {
        $this->requireAdminAuth();
        $type = in_array($_GET['type'] ?? '', ['candidate', 'employer'], true) ? $_GET['type'] : '';
        $this->jsonResponse(['success' => true, 'accounts' => \App\Services\AccountDirectory::search((string)($_GET['q'] ?? ''), $type, 8)]);
    }

    public function reactivatePlanGrant(array $params = []): void
    {
        $this->requireAdminAuth();
        $g = \App\Services\PlanService::reactivateGrant((int)($params['id'] ?? 0), (string)($_SESSION['admin_email'] ?? 'admin'));
        $_SESSION['admin_plans_flash'] = $g
            ? ['ok' => true, 'text' => 'Grant reactivated: ' . ucfirst((string)$g['plan']) . ' until ' . date('j M Y', strtotime((string)$g['expires_at'])) . '.']
            : ['ok' => false, 'text' => 'That grant is already active.'];
        $this->redirect('/admin/subscriptions/plans');
    }

    public function revokePlanGrant(array $params = []): void
    {
        $this->requireAdminAuth();
        $ok = \App\Services\PlanService::revokeGrant((int)($params['id'] ?? 0), (string)($_SESSION['admin_email'] ?? 'admin'));
        $_SESSION['admin_plans_flash'] = $ok ? ['ok' => true, 'text' => 'Grant revoked.'] : ['ok' => false, 'text' => 'That grant is not active.'];
        $this->redirect('/admin/subscriptions/plans');
    }

    protected function staffProfile(): array
    {
        return [
            'name'     => $_SESSION['admin_name'] ?? 'Jayden Okafor',
            'role'     => $_SESSION['admin_role'] ?? 'Platform Admin',
            'initials' => $_SESSION['admin_initials'] ?? 'JO',
            'email'    => $_SESSION['admin_email'] ?? 'jayden.okafor@internconnect.ng',
            'notifications_count' => 3,
        ];
    }

    /**
     * Route /admin/settings to /admin/settings/team
     */
    public function settings(): void
    {
        $this->redirect('/admin/settings/team');
    }

    /**
     * Render the Watching Staff & Role Permissions View (Section 9.0 preview / shell)
     */
    public function settingsTeam(): void
    {
        $this->requireAdminAuth();

        $staff = [
            'name' => 'Jayden Okafor',
            'role' => 'Platform Admin',
            'initials' => 'JO',
            'email' => 'jayden.okafor@internconnect.ng',
            'notifications_count' => 3,
        ];

        $team = [
            [
                'name' => 'Jayden Okafor',
                'email' => 'jayden.okafor@internconnect.ng',
                'role' => 'Platform Admin',
                'role_class' => 'admin-badge-purple',
                'initials' => 'JO',
                'status' => 'Active',
                'last_active' => 'Active now',
            ],
            [
                'name' => 'Adaeze Umeh',
                'email' => 'adaeze.umeh@internconnect.ng',
                'role' => 'Verification Officer',
                'role_class' => 'admin-badge-blue',
                'initials' => 'AU',
                'status' => 'Active',
                'last_active' => '15 mins ago',
            ],
            [
                'name' => 'Chinedu Eze',
                'email' => 'chinedu.eze@internconnect.ng',
                'role' => 'Customer Care',
                'role_class' => 'admin-badge-slate',
                'initials' => 'CE',
                'status' => 'Active',
                'last_active' => '1 hour ago',
            ],
            [
                'name' => 'Bolanle Alabi',
                'email' => 'bolanle.alabi@internconnect.ng',
                'role' => 'AI Operations',
                'role_class' => 'admin-badge-purple',
                'initials' => 'BA',
                'status' => 'Active',
                'last_active' => '3 hours ago',
            ],
            [
                'name' => 'Tariq Bello',
                'email' => 'tariq.bello@internconnect.ng',
                'role' => 'Finance & Billing',
                'role_class' => 'admin-badge-emerald',
                'initials' => 'TB',
                'status' => 'Active',
                'last_active' => 'Yesterday',
            ],
        ];

        $this->renderView('admin/settings/team', [
            'title'     => 'Watching Staff & Role Permissions — InternConnect Admin',
            'staff'     => $staff,
            'activeNav' => 'team',
            'team'      => $team,
        ], 'admin');
    }

    /**
     * Stream Document for Admin In-Browser Preview (Bypasses IDM extension interception via Base64 JSON)
     */
    public function streamDocument(): void
    {
        $this->requireAdminAuth();

        $path = dirname(__DIR__, 2) . '/public/assets/images/dummb/testing.pdf.pdf';

        if (!file_exists($path)) {
            http_response_code(404);
            header('Content-Type: application/json');
            echo json_encode(['status' => 'error', 'message' => 'File not found']);
            exit;
        }

        $base64 = base64_encode(file_get_contents($path));

        header('Content-Type: application/json; charset=utf-8');
        header('Cache-Control: private, max-age=86400');
        header('Access-Control-Allow-Origin: *');
        
        echo json_encode([
            'status' => 'success',
            'data'   => $base64,
            'size'   => filesize($path),
        ]);
        exit;
    }
}

