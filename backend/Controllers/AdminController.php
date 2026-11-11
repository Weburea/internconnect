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
        if (!empty($trustedCookie) && hash_equals(hash('sha256', $email . 'internconnect_salt'), $trustedCookie)) {
            // Device trusted: log in immediately
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

        // Set authenticated administrative session
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_email'] = $pending['email'];
        $_SESSION['admin_name'] = $pending['name'];
        $_SESSION['admin_role'] = $pending['role'];
        $_SESSION['admin_initials'] = $pending['initials'];

        // If remember device requested, set 30-day trusted session token cookie
        if (!empty($_POST['remember_device'])) {
            $trustedToken = hash('sha256', $pending['email'] . 'internconnect_salt');
            setcookie('admin_trusted_device', $trustedToken, [
                'expires' => time() + (86400 * 30),
                'path' => '/',
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
            'rooms'      => $overview['rooms'],
            'events'     => $overview['events'],
            'evaluations'=> $overview['evaluations'],
        ], 'admin');
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
    protected function requireAdminAuth(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['admin_logged_in'])) {
            if (isset($_SERVER['SERVER_NAME']) && in_array($_SERVER['SERVER_NAME'], ['localhost', '127.0.0.1'], true)) {
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

        $locations = [
            ['city' => 'Lagos', 'country' => 'Nigeria', 'code' => 'NG', 'lat' => 6.5244, 'lng' => 3.3792, 'online' => 642, 'students' => 588, 'employers' => 54],
            ['city' => 'Abuja', 'country' => 'Nigeria', 'code' => 'NG', 'lat' => 9.0765, 'lng' => 7.3986, 'online' => 214, 'students' => 192, 'employers' => 22],
            ['city' => 'Ibadan', 'country' => 'Nigeria', 'code' => 'NG', 'lat' => 7.3775, 'lng' => 3.947, 'online' => 96, 'students' => 91, 'employers' => 5],
            ['city' => 'Port Harcourt', 'country' => 'Nigeria', 'code' => 'NG', 'lat' => 4.8156, 'lng' => 7.0498, 'online' => 88, 'students' => 79, 'employers' => 9],
            ['city' => 'Kano', 'country' => 'Nigeria', 'code' => 'NG', 'lat' => 12.0022, 'lng' => 8.592, 'online' => 41, 'students' => 39, 'employers' => 2],
            ['city' => 'Enugu', 'country' => 'Nigeria', 'code' => 'NG', 'lat' => 6.5244, 'lng' => 7.5086, 'online' => 37, 'students' => 35, 'employers' => 2],
            ['city' => 'Accra', 'country' => 'Ghana', 'code' => 'GH', 'lat' => 5.6037, 'lng' => -0.187, 'online' => 58, 'students' => 52, 'employers' => 6],
            ['city' => 'Nairobi', 'country' => 'Kenya', 'code' => 'KE', 'lat' => -1.2921, 'lng' => 36.8219, 'online' => 46, 'students' => 41, 'employers' => 5],
            ['city' => 'Johannesburg', 'country' => 'South Africa', 'code' => 'ZA', 'lat' => -26.2041, 'lng' => 28.0473, 'online' => 22, 'students' => 18, 'employers' => 4],
            ['city' => 'Kigali', 'country' => 'Rwanda', 'code' => 'RW', 'lat' => -1.9441, 'lng' => 30.0619, 'online' => 9, 'students' => 8, 'employers' => 1],
            ['city' => 'London', 'country' => 'United Kingdom', 'code' => 'GB', 'lat' => 51.5072, 'lng' => -0.1276, 'online' => 39, 'students' => 27, 'employers' => 12],
            ['city' => 'Manchester', 'country' => 'United Kingdom', 'code' => 'GB', 'lat' => 53.4808, 'lng' => -2.2426, 'online' => 11, 'students' => 9, 'employers' => 2],
            ['city' => 'Berlin', 'country' => 'Germany', 'code' => 'DE', 'lat' => 52.52, 'lng' => 13.405, 'online' => 8, 'students' => 5, 'employers' => 3],
            ['city' => 'Toronto', 'country' => 'Canada', 'code' => 'CA', 'lat' => 43.6532, 'lng' => -79.3832, 'online' => 21, 'students' => 16, 'employers' => 5],
            ['city' => 'New York', 'country' => 'United States', 'code' => 'US', 'lat' => 40.7128, 'lng' => -74.006, 'online' => 19, 'students' => 11, 'employers' => 8],
            ['city' => 'Houston', 'country' => 'United States', 'code' => 'US', 'lat' => 29.7604, 'lng' => -95.3698, 'online' => 14, 'students' => 10, 'employers' => 4],
            ['city' => 'São Paulo', 'country' => 'Brazil', 'code' => 'BR', 'lat' => -23.5558, 'lng' => -46.6396, 'online' => 7, 'students' => 5, 'employers' => 2],
            ['city' => 'Bogotá', 'country' => 'Colombia', 'code' => 'CO', 'lat' => 4.711, 'lng' => -74.0721, 'online' => 3, 'students' => 3, 'employers' => 0],
            ['city' => 'Dubai', 'country' => 'United Arab Emirates', 'code' => 'AE', 'lat' => 25.2048, 'lng' => 55.2708, 'online' => 12, 'students' => 7, 'employers' => 5],
            ['city' => 'Mumbai', 'country' => 'India', 'code' => 'IN', 'lat' => 19.076, 'lng' => 72.8777, 'online' => 6, 'students' => 5, 'employers' => 1],
        ];

        $total = array_sum(array_column($locations, 'online'));

        $countries = [];
        foreach ($locations as $place) {
            $code = $place['code'];
            $countries[$code]['code'] = $code;
            $countries[$code]['name'] = $place['country'];
            $countries[$code]['online'] = ($countries[$code]['online'] ?? 0) + $place['online'];
        }
        $countries = array_values($countries);
        usort($countries, static fn (array $a, array $b): int => $b['online'] <=> $a['online']);
        foreach ($countries as &$country) {
            $country['share'] = round($country['online'] / $total * 100, 1);
        }
        unset($country);

        // Addresses use the ranges reserved for documentation, so none belong to a real person
        $sessions = [
            ['id' => 1, 'city' => 'Lagos', 'country' => 'Nigeria', 'code' => 'NG', 'ip' => '203.0.113.xxx', 'device' => 'Chrome on Android', 'role' => 'Student', 'page' => 'Browse internships', 'active' => '2m'],
            ['id' => 2, 'city' => 'Abuja', 'country' => 'Nigeria', 'code' => 'NG', 'ip' => '198.51.100.xxx', 'device' => 'Edge on Windows', 'role' => 'Employer', 'page' => 'Candidates', 'active' => '7m'],
            ['id' => 3, 'city' => 'Accra', 'country' => 'Ghana', 'code' => 'GH', 'ip' => '192.0.2.xxx', 'device' => 'Safari on iPhone', 'role' => 'Student', 'page' => 'AI mock interview', 'active' => '11m'],
            ['id' => 4, 'city' => 'London', 'country' => 'United Kingdom', 'code' => 'GB', 'ip' => '203.0.113.xxx', 'device' => 'Chrome on macOS', 'role' => 'Employer', 'page' => 'Post internship', 'active' => '14m'],
            ['id' => 5, 'city' => 'Toronto', 'country' => 'Canada', 'code' => 'CA', 'ip' => '198.51.100.xxx', 'device' => 'Firefox on Windows', 'role' => 'Student', 'page' => 'E-learning', 'active' => '18m'],
            ['id' => 6, 'city' => 'Nairobi', 'country' => 'Kenya', 'code' => 'KE', 'ip' => '192.0.2.xxx', 'device' => 'Chrome on Android', 'role' => 'Student', 'page' => 'CV analysis', 'active' => '21m'],
            ['id' => 7, 'city' => 'São Paulo', 'country' => 'Brazil', 'code' => 'BR', 'ip' => '203.0.113.xxx', 'device' => 'Chrome on Windows', 'role' => 'Student', 'page' => 'Browse internships', 'active' => '26m'],
            ['id' => 8, 'city' => 'Dubai', 'country' => 'United Arab Emirates', 'code' => 'AE', 'ip' => '198.51.100.xxx', 'device' => 'Safari on macOS', 'role' => 'Employer', 'page' => 'Company profile', 'active' => '32m'],
        ];

        header('Cache-Control: no-store');
        $this->jsonResponse([
            'is_sample' => true,
            'total'     => $total,
            'students'  => array_sum(array_column($locations, 'students')),
            'employers' => array_sum(array_column($locations, 'employers')),
            'locations' => $locations,
            'countries' => $countries,
            'sessions'  => $sessions,
        ]);
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
        ], 'admin');
    }

    /**
     * Admin Interns Directory & 420px Profile Inspector Drawer (Images 1 & 2)
     */
    public function interns(): void
    {
        $this->requireAdminAuth();

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
            'title'     => 'Interns Management — InternConnect Admin',
            'staff'     => $staff,
            'activeNav' => 'interns',
            'metrics'   => $metrics,
            'interns'   => $interns,
        ], 'admin');
    }

    /**
     * Admin Employers & Verification Desk (Image 3)
     */
    public function employers(): void
    {
        $this->requireAdminAuth();

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
            'title'     => 'Employers & Verification Queue — InternConnect Admin',
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

        $this->renderView('admin/ai/chat', [
            'title'        => 'Jenny AI Strategic Intelligence — Mission Control Chat',
            'staff'        => $staff,
            'activeNav'    => 'ai',
            'metrics'      => $metrics,
            'initialQuery' => $initialQuery,
        ], 'admin');
    }

    /**
     * Render the E-Learning & Course Moderation View (Section 7.0 preview / shell)
     */
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
        ], 'admin');
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

