<?php

declare(strict_types=1);

namespace Tests\PHPUnit\Unit;

use App\Services\AuthCodeService;
use PHPUnit\Framework\TestCase;

final class AuthCodeServiceTest extends TestCase
{
    public function testCodeWorksOnceAndWrongCodesFail(): void
    {
        $email = 'reset-' . bin2hex(random_bytes(4)) . '@test.dev';
        $code = AuthCodeService::issue($email, 'reset');
        $this->assertMatchesRegularExpression('/^\d{6}$/', $code);
        $wrong = str_pad((string)(((int)$code + 1) % 1000000), 6, '0', STR_PAD_LEFT);
        $this->assertFalse(AuthCodeService::verify($email, 'reset', $wrong));
        $this->assertFalse(AuthCodeService::verify($email, 'verify', $code), 'Codes are per purpose.');
        $this->assertTrue(AuthCodeService::verify(strtoupper($email), 'reset', $code), 'E-mail is case-insensitive.');
        $this->assertFalse(AuthCodeService::verify($email, 'reset', $code), 'Used up.');
    }

    public function testTooManyWrongTriesLocksTheCode(): void
    {
        $email = 'lock-' . bin2hex(random_bytes(4)) . '@test.dev';
        $code = AuthCodeService::issue($email, 'reset');
        for ($i = 0; $i < 5; $i++) {
            AuthCodeService::verify($email, 'reset', $code === '000000' ? '111111' : '000000');
        }
        $this->assertFalse(AuthCodeService::verify($email, 'reset', $code));
    }

    public function testEmailsRenderWithBannerBadgeAndCode(): void
    {
        $html = AuthCodeService::resetEmail('Sarah', '123456');
        $this->assertStringContainsString('email_reset', $html);
        $this->assertStringContainsString('Password reset', $html);
        $this->assertStringContainsString('1 2 3 4 5 6', $html);
        $this->assertStringContainsString('email_footer', $html);
        $this->assertStringContainsString('Welcome to InternConnect, Sarah', AuthCodeService::welcomeEmail('Sarah'));
    }
}
