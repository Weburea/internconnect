<?php

declare(strict_types=1);

namespace Tests\PHPUnit\Feature;

/**
 * /api/v1 over HTTP: envelope, errors, auth flow, roles, ownership, CSRF exemption.
 * Sign-in tests need API_DEMO_PASSWORD in .env (skipped otherwise).
 */
final class ApiV1Test extends HttpTestCase
{
    private function api(string $method, string $path, ?array $json = null, ?string $token = null): array
    {
        $headers = ['Accept: application/json'];
        if ($json !== null) {
            $headers[] = 'Content-Type: application/json';
        }
        if ($token) {
            $headers[] = 'Authorization: Bearer ' . $token;
        }
        return $this->request($method, '/api/v1' . $path, $json !== null ? json_encode($json) : null, $headers, false);
    }

    private function signIn(string $email): array
    {
        $password = self::demoPassword();
        if ($password === '') {
            $this->markTestSkipped('API_DEMO_PASSWORD is not set in .env.');
        }
        $r = $this->api('POST', '/auth/login', ['email' => $email, 'password' => $password]);
        $this->assertSame(200, $r['status'], 'Sign-in works: ' . $r['body']);
        return $r['json']['data'];
    }

    public function testPublicEndpointsAndEnvelope(): void
    {
        $r = $this->api('GET', '/health');
        $this->assertSame(200, $r['status']);
        $this->assertSame(['success', 'code', 'message', 'data'], array_keys($r['json']));
        $this->assertSame('application/json; charset=utf-8', $r['headers']['content-type'] ?? '');

        $r = $this->api('GET', '/internships?per_page=3&location=lagos');
        $this->assertSame(200, $r['status']);
        $this->assertLessThanOrEqual(3, count($r['json']['data']));
        $this->assertArrayHasKey('total_pages', $r['json']['meta']);

        $r = $this->api('GET', '/elearning/programs');
        $this->assertSame(200, $r['status']);
        $this->assertStringStartsWith('http', $r['json']['data'][0]['logo']);
    }

    public function testErrorsAreJson(): void
    {
        $this->assertSame('not_found', $this->api('GET', '/does-not-exist')['json']['error']);
        $r = $this->api('POST', '/health');
        $this->assertSame(405, $r['status']);
        $this->assertSame('GET', $r['headers']['allow'] ?? '');
        $r = $this->api('GET', '/auth/me');
        $this->assertSame(401, $r['status']);
        $this->assertSame('Bearer', $r['headers']['www-authenticate'] ?? '');
        $r = $this->request('POST', '/api/v1/auth/login', '{bad json', ['Content-Type: application/json'], false);
        $this->assertSame(400, $r['status']);
        $this->assertSame('invalid_json', $r['json']['error']);
    }

    public function testApiPostsDoNotNeedCsrfButNeedAuth(): void
    {
        $r = $this->api('POST', '/employer/candidates/1/stage', ['stage' => 'Shortlisted']);
        $this->assertSame(401, $r['status'], 'No cookie/CSRF involved: the API answers 401, not a CSRF 403.');
    }

    public function testCandidateFlowRolesAndOwnership(): void
    {
        $pair = $this->signIn('candidate@internconnect.ng');
        $t = $pair['access_token'];
        $this->assertSame('candidate', $this->api('GET', '/auth/me', null, $t)['json']['data']['role']);
        $this->assertSame(200, $this->api('GET', '/candidate/profile', null, $t)['status']);
        $this->assertSame(200, $this->api('GET', '/candidate/applications', null, $t)['status']);
        $this->assertSame(200, $this->api('GET', '/billing', null, $t)['status']);
        $this->assertSame(403, $this->api('GET', '/employer/candidates/1/application', null, $t)['status'], 'Candidates cannot use employer endpoints.');
        $this->assertSame(404, $this->api('GET', '/candidate/applications/999999999', null, $t)['status']);
        $this->assertSame(404, $this->api('GET', '/interviews/999999999/status', null, $t)['status']);

        $refreshed = $this->api('POST', '/auth/refresh-token', ['refresh_token' => $pair['refresh_token']]);
        $this->assertSame(200, $refreshed['status']);
        $this->assertSame(401, $this->api('POST', '/auth/refresh-token', ['refresh_token' => $pair['refresh_token']])['status'], 'Refresh tokens work once.');

        $this->assertSame(200, $this->api('POST', '/auth/logout', [], $t)['status']);
        $this->assertSame(401, $this->api('GET', '/auth/me', null, $t)['status'], 'Logged-out tokens stop working.');
    }

    public function testEmployerStageValidation(): void
    {
        $t = $this->signIn('employer@internconnect.ng')['access_token'];
        $r = $this->api('POST', '/employer/candidates/1/stage', ['stage' => 'Hired?'], $t);
        $this->assertSame(422, $r['status']);
        $this->assertArrayHasKey('stage', $r['json']['errors']);
    }
}
