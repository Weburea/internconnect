<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;

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

    public function registerEmployer(): void
    {
        $this->renderView('auth/register-employer', [
            'title'      => 'Employer Registration - InternConnect',
            'showLoader' => false,
        ], 'auth');
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
        header('Location: ' . url('/?logged_out=1'));
        exit;
    }
}

