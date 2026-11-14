<?php

declare(strict_types=1);

namespace Tests\PHPUnit\Feature;

/**
 * Security checks against the running site: private files are not served, security headers
 * and cookie flags are set, CSRF blocks token-less POSTs, login is rate limited.
 */
final class SecurityTest extends HttpTestCase
{
    public function testPrivateFilesAreNeverServed(): void
    {
        $paths = ['/database/database.sqlite', '/database/schema_mysql.sql', '/.env', '/.env.example', '/composer.json', '/composer.lock',
            '/phpunit.xml', '/docs/08-payment-system.md', '/tests/run_tests.php', '/tests/bootstrap.php', '/backend/routes.php',
            '/backend/Core/Database.php', '/vendor/autoload.php', '/frontend/landing/index.php', '/storage/uploads/resume', '/.agents/rules/rules.md',
            '/.git/config', '/README.md'];
        foreach ($paths as $p) {
            $r = $this->request('GET', $p, null, [], false);
            $this->assertContains($r['status'], [403, 404], "{$p} must not be served (got {$r['status']}).");
        }
        // Public assets still work.
        $this->assertSame(200, $this->request('GET', '/assets/js/ic-csrf.js', null, [], false)['status']);
        $this->assertSame(200, $this->request('GET', '/sitemap.xml', null, [], false)['status']);
    }

    public function testSecurityHeadersAndSessionCookie(): void
    {
        $r = $this->request('GET', '/', null, [], false);
        $this->assertSame(200, $r['status']);
        $this->assertSame('nosniff', $r['headers']['x-content-type-options'] ?? '');
        $this->assertSame('SAMEORIGIN', $r['headers']['x-frame-options'] ?? '');
        $this->assertSame('strict-origin-when-cross-origin', $r['headers']['referrer-policy'] ?? '');
        $this->assertStringContainsString('camera=(self)', $r['headers']['permissions-policy'] ?? '');
        $cookie = strtolower($r['headers']['set-cookie'] ?? '');
        $this->assertStringContainsString('httponly', $cookie);
        $this->assertStringContainsString('samesite=lax', $cookie);
        $this->assertArrayNotHasKey('x-powered-by', $r['headers']);
    }

    public function testCsrfBlocksPostsWithoutToken(): void
    {
        $r = $this->request('POST', '/candidate/notifications/mark-all-read', '', ['Accept: application/json']);
        $this->assertSame(403, $r['status']);
        $this->assertSame('csrf_mismatch', $r['json']['code'] ?? null);

        $r = $this->request('POST', '/login', ['email' => 'x@y.z', '_csrf' => str_repeat('0', 64)]);
        $this->assertSame(403, $r['status'], 'A wrong token is refused.');

        $token = $this->csrfFrom('/login');
        $this->assertSame(64, strlen($token), 'Pages publish the token in <meta name="csrf-token">.');
        $r = $this->request('POST', '/login', ['email' => 'csrf.test@example.com', '_csrf' => $token]);
        $this->assertSame(302, $r['status'], 'The same form with the page token is accepted.');

        // Paystack webhook is exempt from CSRF (it is signed instead): no token -> signature check (401), not 403.
        $r = $this->request('POST', '/payment/webhook', '{}', ['Content-Type: application/json'], false);
        $this->assertSame(401, $r['status']);
    }

    public function testWebLoginIsRateLimited(): void
    {
        $token = $this->csrfFrom('/login');
        $email = 'ratelimit.' . bin2hex(random_bytes(4)) . '@example.com';
        $last = null;
        for ($i = 0; $i < 6; $i++) {
            $last = $this->request('POST', '/login', ['email' => $email, '_csrf' => $token]);
        }
        $this->assertSame(302, $last['status']);
        $this->assertStringContainsString('too_many_attempts', (string)($last['headers']['location'] ?? ''), 'The 6th attempt in a minute is refused.');
    }
}
