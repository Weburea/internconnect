<?php

declare(strict_types=1);

namespace App\Controllers\Api\V1;

use App\Api\ApiAuth;
use App\Api\ApiException;
use App\Api\ApiResponse;
use App\Services\RateLimiter;

/**
 * POST /api/v1/auth/login          {email, password}      -> token pair
 * POST /api/v1/auth/refresh-token  {refresh_token}        -> new token pair (rotation)
 * POST /api/v1/auth/logout         {refresh_token?}       (Bearer) -> revoke
 * GET  /api/v1/auth/me             (Bearer)               -> account
 */
final class AuthApiController extends ApiController
{
    public function login(): void
    {
        if (!ApiAuth::isConfigured()) {
            throw new ApiException('API sign-in is not configured on this server (JWT_SECRET / API_DEMO_PASSWORD).', 503, [], 'service_unavailable');
        }
        $in = $this->validate(['email' => 'required|string|email|max:190', 'password' => 'required|string|max:200']);
        $ip = RateLimiter::ip();
        foreach ([['login:ip:' . $ip, 20, 900], ['login:acct:' . strtolower($in['email']) . ':' . $ip, 5, 60]] as [$key, $limit, $window]) {
            $r = RateLimiter::hit($key, $limit, $window);
            if (!$r['allowed']) {
                throw new ApiException('Too many sign-in attempts. Try again in ' . $r['retry_after'] . ' seconds.', 429, [], 'too_many_requests', ['Retry-After' => (string)$r['retry_after']]);
            }
        }
        $account = ApiAuth::verifyCredentials($in['email'], $in['password']);
        if (!$account) {
            throw new ApiException('The e-mail or password is incorrect.', 401, [], 'invalid_credentials');
        }
        RateLimiter::clear('login:acct:' . strtolower($in['email']) . ':' . $ip);
        ApiResponse::ok(ApiAuth::issue($account) + ['user' => $this->present($account)], 'Signed in.');
    }

    public function refresh(): void
    {
        $in = $this->validate(['refresh_token' => 'required|string|max:200']);
        $pair = ApiAuth::refresh($in['refresh_token']);
        if (!$pair) {
            throw new ApiException('The refresh token is invalid, expired or already used. Sign in again.', 401, [], 'invalid_refresh_token');
        }
        ApiResponse::ok($pair, 'Token refreshed.');
    }

    public function logout(): void
    {
        $account = $this->user();
        $in = $this->input();
        ApiAuth::logout($account['claims'], is_string($in['refresh_token'] ?? null) ? $in['refresh_token'] : null);
        ApiResponse::ok(null, 'Signed out.');
    }

    public function me(): void
    {
        ApiResponse::ok($this->present($this->user()), 'Current account.');
    }

    private function present(array $account): array
    {
        return array_filter([
            'id'      => (int)$account['id'],
            'role'    => $account['role'],
            'name'    => $account['name'],
            'email'   => $account['email'],
            'company' => $account['company'] ?? null,
        ], static fn ($v) => $v !== null);
    }
}
