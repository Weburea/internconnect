<?php

declare(strict_types=1);

namespace Tests\PHPUnit\Unit;

use App\Api\ApiAuth;
use App\Api\Jwt;
use App\Services\RateLimiter;
use PHPUnit\Framework\TestCase;

/** JWT, API sign-in, refresh rotation, revocation and the rate limiter. */
final class ApiAuthTest extends TestCase
{
    private const SECRET = 'test-secret-0123456789abcdef0123456789abcdef0123456789abcdef';

    protected function setUp(): void
    {
        $_ENV['JWT_SECRET'] = self::SECRET;
        $_ENV['API_DEMO_PASSWORD'] = 'Correct-Horse-9';
        unset($_SERVER['HTTP_AUTHORIZATION']);
    }

    public function testJwtRoundTripAndTampering(): void
    {
        $token = Jwt::encode(['sub' => 7, 'exp' => time() + 60], self::SECRET);
        $this->assertSame(7, Jwt::decode($token, self::SECRET)['sub']);
        $this->assertNull(Jwt::decode($token, 'another-secret'), 'Wrong secret is rejected.');
        [$h, $p, $s] = explode('.', $token);
        $forged = $h . '.' . rtrim(strtr(base64_encode(json_encode(['sub' => 1, 'exp' => time() + 60])), '+/', '-_'), '=') . '.' . $s;
        $this->assertNull(Jwt::decode($forged, self::SECRET), 'Changed claims break the signature.');
        $none = rtrim(strtr(base64_encode('{"alg":"none","typ":"JWT"}'), '+/', '-_'), '=') . '.' . $p . '.';
        $this->assertNull(Jwt::decode($none, self::SECRET), 'alg=none is never accepted.');
    }

    public function testJwtExpiry(): void
    {
        $expired = Jwt::encode(['sub' => 1, 'exp' => time() - 120], self::SECRET);
        $this->assertNull(Jwt::decode($expired, self::SECRET));
        $leeway = Jwt::encode(['sub' => 1, 'exp' => time() - 5], self::SECRET);
        $this->assertNotNull(Jwt::decode($leeway, self::SECRET, 30), 'Small clock skew is tolerated.');
    }

    public function testCredentials(): void
    {
        $this->assertTrue(ApiAuth::isConfigured());
        $this->assertSame('candidate', ApiAuth::verifyCredentials('Candidate@InternConnect.ng', 'Correct-Horse-9')['role']);
        $this->assertNull(ApiAuth::verifyCredentials('candidate@internconnect.ng', 'wrong'));
        $this->assertNull(ApiAuth::verifyCredentials('nobody@example.com', 'Correct-Horse-9'));
        $_ENV['API_DEMO_PASSWORD'] = '';
        $this->assertNull(ApiAuth::verifyCredentials('candidate@internconnect.ng', ''), 'No password configured: nobody signs in.');
        $this->assertFalse(ApiAuth::isConfigured());
    }

    public function testIssueRefreshRotationAndReuseDetection(): void
    {
        $account = ApiAuth::verifyCredentials('employer@internconnect.ng', 'Correct-Horse-9');
        $pair = ApiAuth::issue($account);
        $this->assertSame('Bearer', $pair['token_type']);
        $this->assertSame(900, $pair['expires_in']);
        $this->assertSame('employer', ApiAuth::fromToken($pair['access_token'])['role']);

        $second = ApiAuth::refresh($pair['refresh_token']);
        $this->assertNotNull($second);
        $this->assertNotSame($pair['refresh_token'], $second['refresh_token'], 'Refresh rotates the token.');

        $this->assertNull(ApiAuth::refresh($pair['refresh_token']), 'A spent refresh token is refused.');
        $this->assertNull(ApiAuth::refresh($second['refresh_token']), 'Reuse revokes the whole family.');
    }

    public function testLogoutRevokesAccessToken(): void
    {
        $pair = ApiAuth::issue(ApiAuth::verifyCredentials('candidate@internconnect.ng', 'Correct-Horse-9'));
        $me = ApiAuth::fromToken($pair['access_token']);
        $this->assertNotNull($me);
        ApiAuth::logout($me['claims'], $pair['refresh_token']);
        $this->assertNull(ApiAuth::fromToken($pair['access_token']), 'Access token is denied after logout.');
        $this->assertNull(ApiAuth::refresh($pair['refresh_token']), 'Refresh token is revoked after logout.');
    }

    public function testBearerHeaderParsing(): void
    {
        $pair = ApiAuth::issue(ApiAuth::verifyCredentials('admin@internconnect.ng', 'Correct-Horse-9'));
        $_SERVER['HTTP_AUTHORIZATION'] = 'Bearer ' . $pair['access_token'];
        $this->assertSame('admin', ApiAuth::fromRequest()['role']);
        $_SERVER['HTTP_AUTHORIZATION'] = 'Basic abc';
        $this->assertNull(ApiAuth::fromRequest());
        $_SERVER['HTTP_AUTHORIZATION'] = 'Bearer not.a-jwt';
        $this->assertNull(ApiAuth::fromRequest());
    }

    public function testRateLimiterWindow(): void
    {
        $key = 'test:' . bin2hex(random_bytes(6));
        for ($i = 1; $i <= 3; $i++) {
            $r = RateLimiter::hit($key, 3, 60);
            $this->assertTrue($r['allowed']);
            $this->assertSame(3 - $i, $r['remaining']);
        }
        $blocked = RateLimiter::hit($key, 3, 60);
        $this->assertFalse($blocked['allowed']);
        $this->assertGreaterThan(0, $blocked['retry_after']);
        RateLimiter::clear($key);
        $this->assertTrue(RateLimiter::hit($key, 3, 60)['allowed'], 'clear() resets the window.');
    }
}
