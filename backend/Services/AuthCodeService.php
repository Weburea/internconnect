<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\Database;
use PDO;

/**
 * Six-digit one-time codes sent by e-mail (password reset, e-mail verification).
 * Codes are stored hashed, last 10 minutes, allow 5 tries, and a new code replaces the old one.
 */
final class AuthCodeService
{
    public const MINUTES = 10;
    private const MAX_ATTEMPTS = 5;
    private static bool $ready = false;

    private static function pdo(): PDO
    {
        $pdo = Database::getConnection();
        if (!self::$ready) {
            $mysql = $pdo->getAttribute(PDO::ATTR_DRIVER_NAME) === 'mysql';
            $pdo->exec($mysql
                ? 'CREATE TABLE IF NOT EXISTS auth_codes (email VARCHAR(190) NOT NULL, purpose VARCHAR(16) NOT NULL, code_hash CHAR(64) NOT NULL,
                    attempts TINYINT UNSIGNED NOT NULL DEFAULT 0, expires_at DATETIME NOT NULL, created_at DATETIME NOT NULL,
                    PRIMARY KEY (email, purpose)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci'
                : 'CREATE TABLE IF NOT EXISTS auth_codes (email TEXT NOT NULL, purpose TEXT NOT NULL, code_hash TEXT NOT NULL,
                    attempts INTEGER NOT NULL DEFAULT 0, expires_at TEXT NOT NULL, created_at TEXT NOT NULL, PRIMARY KEY (email, purpose))');
            self::$ready = true;
        }
        return $pdo;
    }

    public static function ensureTables(): void
    {
        self::pdo();
    }

    private static function hash(string $email, string $purpose, string $code): string
    {
        return hash_hmac('sha256', strtolower($email) . '|' . $purpose . '|' . $code, (string)\App\Core\Env::get('APP_KEY', 'internconnect'));
    }

    /** Create (or replace) a code and return it. */
    public static function issue(string $email, string $purpose): string
    {
        $email = strtolower(trim($email));
        $code = str_pad((string)random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $pdo = self::pdo();
        $pdo->prepare('DELETE FROM auth_codes WHERE email = ? AND purpose = ?')->execute([$email, $purpose]);
        $pdo->prepare('INSERT INTO auth_codes (email, purpose, code_hash, attempts, expires_at, created_at) VALUES (?, ?, ?, 0, ?, ?)')
            ->execute([$email, $purpose, self::hash($email, $purpose, $code), date('Y-m-d H:i:s', time() + self::MINUTES * 60), date('Y-m-d H:i:s')]);
        return $code;
    }

    /** True once for a correct, unexpired code (the code is then used up). */
    public static function verify(string $email, string $purpose, string $code): bool
    {
        $email = strtolower(trim($email));
        if (!preg_match('/^\d{6}$/', $code)) {
            return false;
        }
        $pdo = self::pdo();
        $stmt = $pdo->prepare('SELECT * FROM auth_codes WHERE email = ? AND purpose = ?');
        $stmt->execute([$email, $purpose]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row || strtotime((string)$row['expires_at']) < time() || (int)$row['attempts'] >= self::MAX_ATTEMPTS) {
            return false;
        }
        if (!hash_equals((string)$row['code_hash'], self::hash($email, $purpose, $code))) {
            $pdo->prepare('UPDATE auth_codes SET attempts = attempts + 1 WHERE email = ? AND purpose = ?')->execute([$email, $purpose]);
            return false;
        }
        $pdo->prepare('DELETE FROM auth_codes WHERE email = ? AND purpose = ?')->execute([$email, $purpose]);
        return true;
    }

    /** The branded e-mails. */
    public static function resetEmail(string $name, string $code): string
    {
        return MailService::render('code', [
            'preheader' => 'Your InternConnect password reset code is ' . $code . '.',
            'hero'      => ['src' => MailService::image('email_reset', 1200), 'alt' => 'Forgot your password?'],
            'badge'     => 'Password reset',
            'title'     => 'Reset your password',
            'greeting'  => 'Hi ' . $name . ',',
            'intro'     => 'We received a request to reset your InternConnect password. Enter the 6-digit code below to set a new one.',
            'code'      => $code,
            'codeLabel' => 'Your reset code',
            'minutes'   => self::MINUTES,
            'note'      => 'If you did not ask to reset your password, you can ignore this e-mail. Your password stays the same.',
        ]);
    }

    public static function verifyEmail(string $name, string $code): string
    {
        return MailService::render('code', [
            'preheader' => 'Your InternConnect verification code is ' . $code . '.',
            'hero'      => ['src' => MailService::image('email_verify', 1200), 'alt' => 'Verify your e-mail'],
            'badge'     => 'Account verification',
            'title'     => 'Verify your e-mail address',
            'greeting'  => 'Hi ' . $name . ', welcome to InternConnect!',
            'intro'     => 'Enter the 6-digit code below to confirm your e-mail and activate your account.',
            'code'      => $code,
            'codeLabel' => 'Your verification code',
            'minutes'   => self::MINUTES,
            'note'      => 'If you did not create an InternConnect account, you can safely ignore this message.',
        ]);
    }

    public static function welcomeEmail(string $name, string $role = 'candidate'): string
    {
        $employer = $role === 'employer';
        return MailService::render('message', [
            'preheader'  => $employer ? 'Post your first internship and meet ready-to-work talent.' : 'Your next internship starts here. Here is how to get going.',
            'hero'       => ['src' => MailService::image('email_welcome', 1200), 'alt' => 'Welcome to InternConnect'],
            'badge'      => 'Welcome',
            'title'      => 'Welcome to InternConnect, ' . $name . '!',
            'paragraphs' => $employer
                ? ['Your employer account is ready. Post an internship, let Jenny AI rank the best applicants, and run interviews right inside InternConnect.',
                   'Verified companies get a badge on every listing, so complete your company verification to stand out.']
                : ['Your account is ready. Here is how to make the most of it:',
                   "1. Finish your profile and upload your CV so Jenny AI can match you.\n2. Browse internships and save the ones you like.\n3. Practise with mock interviews and grow your skills with our training partners."],
            'cta'        => $employer ? ['label' => 'Post an internship', 'url' => MailService::link('/employer/internships/create')] : ['label' => 'Find internships', 'url' => MailService::link('/internships')],
            'note'       => 'Questions? Just reply to this e-mail or ask Jenny, our AI assistant, any time.',
        ]);
    }
}
