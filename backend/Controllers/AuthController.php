<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Services\UploadStorage;

class AuthController extends Controller
{
    public function login(): void
    {
        $this->renderView('auth/login', [
            'title'      => 'Login - InternConnect',
            'showLoader' => false,
        ], 'auth');
    }

    public function loginPost(): void
    {
        $email = $_POST['email'] ?? '';
        $name = 'Sarah';
        if (!empty($email)) {
            $parts = explode('@', $email);
            $name = ucfirst($parts[0]);
        }
        
        $role = 'candidate';
        $lowerEmail = strtolower($email);
        if (strpos($lowerEmail, 'employer') !== false || 
            strpos($lowerEmail, 'tech') !== false || 
            strpos($lowerEmail, 'company') !== false || 
            strpos($lowerEmail, 'solutions') !== false ||
            strpos($lowerEmail, 'global') !== false) {
            $role = 'employer';
        }

        header('Location: ' . url('/welcome-back?name=' . urlencode($name) . '&role=' . $role));
        exit;
    }

    public function register(): void
    {
        $this->renderView('auth/register', [
            'title'      => 'Register - InternConnect',
            'showLoader' => false,
        ], 'auth');
    }

    public function registerPost(): void
    {
        $name = $_POST['name'] ?? 'Sarah';
        header('Location: ' . url('/welcome?name=' . urlencode($name)));
        exit;
    }

    public function registerStudent(): void
    {
        $this->renderView('auth/register-student', [
            'title'      => 'Create Account - InternConnect',
            'showLoader' => false,
        ], 'auth');
    }

    /**
     * Final step of the student onboarding wizard.
     * Saves every answer into the candidate profile so Profile Settings shows
     * (and can edit) exactly what the student entered. Accepts multipart form data:
     * `data` (JSON of wizard fields) and an optional `resume` file.
     */
    public function registerStudentComplete(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        header('Content-Type: application/json');

        $data = json_decode((string)($_POST['data'] ?? ''), true);
        if (!is_array($data)) {
            http_response_code(422);
            echo json_encode(['success' => false, 'error' => 'Missing onboarding data.']);
            exit;
        }

        $text = static fn (string $key, int $max = 160): string => mb_substr(trim(strip_tags((string)($data[$key] ?? ''))), 0, $max);
        $list = static function (string $key, int $maxItems = 30) use ($data): array {
            $out = [];
            foreach ((array)($data[$key] ?? []) as $v) {
                $v = mb_substr(trim(strip_tags((string)$v)), 0, 80);
                if ($v !== '' && !in_array($v, $out, true)) {
                    $out[] = $v;
                }
            }
            return array_slice($out, 0, $maxItems);
        };

        $first = $text('first_name', 60);
        $last  = $text('last_name', 60);
        $email = filter_var($text('email', 190), FILTER_VALIDATE_EMAIL) ?: '';
        if ($first === '' || $email === '') {
            http_response_code(422);
            echo json_encode(['success' => false, 'error' => 'Name and email are required.']);
            exit;
        }

        $_SESSION['profile_first_name'] = $first;
        $_SESSION['profile_last_name']  = $last;
        $_SESSION['profile_name']       = trim($first . ' ' . $last);
        $_SESSION['profile_email']      = $email;
        $_SESSION['profile_role']       = 'Student Applicant';

        $password = (string)($data['password'] ?? '');
        if (strlen($password) >= 8) {
            $_SESSION['profile_password_hash'] = password_hash($password, PASSWORD_DEFAULT);
        }

        // Step 4: academic
        $_SESSION['profile_university']      = $text('university');
        $_SESSION['profile_degree_type']     = $text('degree_level', 60);
        $_SESSION['profile_graduation_year'] = preg_match('/^\d{4}$/', $text('graduation_year', 4)) ? $text('graduation_year', 4) : '';
        $_SESSION['profile_field_of_study']  = $text('field_of_study');
        $_SESSION['profile_gpa']             = $text('gpa', 20);

        // Step 5: links
        foreach (['portfolio', 'linkedin', 'github'] as $key) {
            $_SESSION['profile_' . $key] = $text($key, 300);
        }

        // Step 6: interests and work arrangement
        $_SESSION['profile_interests'] = $list('interests');
        $_SESSION['profile_work_arrangements'] = array_values(array_intersect($list('work_arrangements', 3), ['Remote', 'Hybrid', 'On-site']));

        // Step 7: location
        $_SESSION['profile_location']            = $text('location', 120);
        $_SESSION['profile_open_to_relocating']  = ($data['open_to_relocating'] ?? 'Yes') === 'No' ? 'No' : 'Yes';
        $_SESSION['profile_preferred_locations'] = $list('job_suggestions_locations', 20);
        $_SESSION['profile_include_remote']      = !empty($data['include_remote']);

        // Step 5: resume (optional)
        $resumeError = null;
        if (!empty($_FILES['resume']) && ($_FILES['resume']['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE) {
            $result = UploadStorage::store($_FILES['resume'], 'resume');
            if ($result['ok']) {
                UploadStorage::delete($_SESSION['profile_resume'] ?? null);
                $_SESSION['profile_resume'] = $result['meta'];
            } else {
                $resumeError = $result['error'];
            }
        }

        $_SESSION['is_logged_in'] = true;
        $_SESSION['candidate_onboarded'] = true;

        echo json_encode([
            'success'      => true,
            'resume_error' => $resumeError,
            'redirect'     => url('/welcome?name=' . urlencode($_SESSION['profile_name'])),
        ]);
        exit;
    }

    public function registerEmployer(): void
    {
        $this->renderView('auth/register-employer', [
            'title'      => 'Employer Registration - InternConnect',
            'showLoader' => false,
        ], 'auth');
    }

    /**
     * Final step of the employer onboarding wizard.
     * Saves the organization details into the employer profile so Profile Settings
     * shows (and can edit) everything entered during signup. Multipart form data:
     * `data` (JSON), optional `logo` and `business_doc` files.
     */
    public function registerEmployerComplete(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        header('Content-Type: application/json');

        $data = json_decode((string)($_POST['data'] ?? ''), true);
        if (!is_array($data)) {
            http_response_code(422);
            echo json_encode(['success' => false, 'error' => 'Missing onboarding data.']);
            exit;
        }

        $text = static fn (string $key, int $max = 160): string => mb_substr(trim(strip_tags((string)($data[$key] ?? ''))), 0, $max);

        $company = $text('company_name', 120);
        $email = filter_var($text('email', 190), FILTER_VALIDATE_EMAIL) ?: '';
        if ($company === '' || $email === '') {
            http_response_code(422);
            echo json_encode(['success' => false, 'error' => 'Company name and work email are required.']);
            exit;
        }

        $_SESSION['employer_company'] = $company;
        $_SESSION['employer_email']   = $email;
        $password = (string)($data['password'] ?? '');
        if (strlen($password) >= 8) {
            $_SESSION['employer_password_hash'] = password_hash($password, PASSWORD_DEFAULT);
        }

        $_SESSION['employer_cac_number']   = $text('cac_number', 40);
        $_SESSION['employer_industry']     = $text('industry', 80);
        $_SESSION['employer_company_size'] = $text('company_size', 40);
        $_SESSION['employer_website']      = $text('website', 200);
        $_SESSION['employer_description']  = mb_substr(trim(strip_tags((string)($data['description'] ?? ''))), 0, 2000);

        $departments = [];
        foreach ((array)($data['departments'] ?? []) as $d) {
            $d = mb_substr(trim(strip_tags((string)$d)), 0, 60);
            if ($d !== '' && !in_array($d, $departments, true)) {
                $departments[] = $d;
            }
        }
        $_SESSION['employer_departments']     = array_slice($departments, 0, 30);
        $_SESSION['employer_expected_volume'] = $text('expected_volume', 40);
        $type = $text('internship_type', 20);
        $_SESSION['employer_internship_type'] = in_array($type, ['Paid', 'Stipend', 'Unpaid'], true) ? $type : '';

        $fileErrors = [];
        foreach (['logo' => 'employer_logo_file', 'business_doc' => 'employer_business_doc'] as $kind => $key) {
            if (!empty($_FILES[$kind]) && ($_FILES[$kind]['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE) {
                $result = UploadStorage::store($_FILES[$kind], $kind);
                if ($result['ok']) {
                    UploadStorage::delete($_SESSION[$key] ?? null);
                    $_SESSION[$key] = $result['meta'];
                } else {
                    $fileErrors[$kind] = $result['error'];
                }
            }
        }

        $_SESSION['is_logged_in'] = true;
        $_SESSION['employer_onboarded'] = true;

        echo json_encode([
            'success'     => true,
            'file_errors' => $fileErrors,
            'redirect'    => url('/welcome/employer?name=' . urlencode($company)),
        ]);
        exit;
    }

    public function welcome(): void
    {
        $name = $_GET['name'] ?? 'Sarah';
        $this->renderView('auth/welcome', [
            'title' => 'Welcome - InterConnect',
            'name' => $name
        ], 'auth');
    }

    public function welcomeEmployer(): void
    {
        $name = $_GET['name'] ?? 'Sarah';
        $this->renderView('auth/welcome-employer', [
            'title' => 'Ready to Hire - InternConnect',
            'name'  => $name
        ], 'auth');
    }

    public function welcomeSuccess(): void
    {
        $name = $_GET['name'] ?? 'Sarah';
        $this->renderView('auth/welcome_success', [
            'title' => 'Success - InternConnect',
            'name' => $name
        ], 'auth');
    }

    public function welcomeBack(): void
    {
        $name = $_GET['name'] ?? 'Sarah';
        $role = $_GET['role'] ?? 'candidate';
        $this->renderView('auth/welcome-back', [
            'title' => 'Welcome Back - InternConnect',
            'name' => $name,
            'role' => $role
        ], 'auth');
    }

    public function forgotPassword(): void
    {
        $this->renderView('auth/forgot-password', [
            'title'      => 'Forgot Password - InternConnect',
            'showLoader' => false,
        ], 'auth');
    }

    public function forgotPasswordPost(): void
    {
        // TODO: Integrate with mailer service to dispatch a password reset email.
        // For now, redirect back to the form with a success indicator.
        $email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);

        if ($email) {
            // Placeholder: password reset email logic will go here.
            header('Location: ' . url('/verify-code'));
        } else {
            header('Location: ' . url('/forgot-password?error=invalid'));
        }
        exit;
    }

    public function verifyCode(): void
    {
        $this->renderView('auth/verify-code', [
            'title'      => 'Verify Code - InternConnect',
            'showLoader' => false,
        ], 'auth');
    }

    public function verifyCodePost(): void
    {
        $code = $_POST['code'] ?? '';
        
        // Placeholder check: verify 6-digit code
        if (preg_match('/^\d{6}$/', $code)) {
            header('Location: ' . url('/reset-password'));
        } else {
            header('Location: ' . url('/verify-code?error=invalid'));
        }
        exit;
    }

    public function resetPassword(): void
    {
        $this->renderView('auth/reset-password', [
            'title'      => 'Create New Password - InternConnect',
            'showLoader' => false,
        ], 'auth');
    }

    public function resetPasswordPost(): void
    {
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        // Server-side validation of password requirements:
        // - At least 8 characters
        // - One special character
        // - One number
        $hasMinLength = strlen($password) >= 8;
        $hasSpecialChar = (bool)preg_match('/[!@#$%^&*(),.?":{}|<>]/', $password);
        $hasNumber = (bool)preg_match('/\d/', $password);

        if ($hasMinLength && $hasSpecialChar && $hasNumber && $password === $confirmPassword) {
            // Placeholder: password updated in DB logic will go here.
            header('Location: ' . url('/password-reset-success'));
        } else {
            header('Location: ' . url('/reset-password?error=invalid'));
        }
        exit;
    }

    public function passwordResetSuccess(): void
    {
        $this->renderView('auth/password-reset-success', [
            'title'      => 'Password Reset Success - InternConnect',
            'name'       => 'Sarah',
            'showLoader' => false,
        ], 'auth');
    }

    public function logout(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION['is_logged_in'] = false;
        unset($_SESSION['profile_name'], $_SESSION['profile_email'], $_SESSION['profile_avatar']);
        header('Location: ' . url('/?logout=true'));
        exit;
    }
}

