<?php

declare(strict_types=1);

namespace App\Core;

use App\Api\ApiResponse;
use App\Services\RateLimiter;

/**
 * Web security layer (docs/51-api-and-security.md):
 * - startSession(): hardened session cookie (HttpOnly, SameSite=Lax, Secure on HTTPS, strict mode).
 * - sendHeaders(): security headers on every response (no-sniff, frame, referrer, permissions, HSTS on HTTPS).
 * - CSRF: every POST to a web route must carry the session token (header X-CSRF-Token, field _csrf,
 *   or ?_csrf= for sendBeacon). Layouts print it in <meta name="csrf-token">; public/assets/js/ic-csrf.js
 *   adds it to fetch, XHR, forms and beacons automatically. Webhooks and /api/* are exempt
 *   (webhooks are signed; the API uses Bearer tokens, not cookies).
 * - apiCors(): CORS for /api/* limited to API_CORS_ORIGINS.
 * - throttleLogin(): rate limit for web sign-in forms.
 */
final class Security
{
    /** Paths that receive server-to-server callbacks (verified by signature instead). */
    private const CSRF_EXEMPT = ['/payment/webhook'];
    /** Prefixes authorised by a secret token in the URL instead (one-click newsletter unsubscribe, RFC 8058). */
    private const CSRF_EXEMPT_PREFIXES = ['/newsletter/one-click/'];

    public static function isHttps(): bool
    {
        return (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
            || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https')
            || ((int)($_SERVER['SERVER_PORT'] ?? 0) === 443);
    }

    public static function startSession(): void
    {
        if (session_status() !== PHP_SESSION_NONE) {
            return;
        }
        ini_set('session.use_strict_mode', '1');
        ini_set('session.use_only_cookies', '1');
        ini_set('session.cookie_httponly', '1');
        session_set_cookie_params([
            'lifetime' => 0,
            'path'     => '/',
            'secure'   => self::isHttps(),
            'httponly' => true,
            'samesite' => 'Lax',
        ]);
        session_start();
    }

    /** New session id after sign-in (prevents session fixation); keeps the session data. */
    public static function regenerateSession(): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_regenerate_id(true);
        }
    }

    public static function sendHeaders(): void
    {
        if (headers_sent()) {
            return;
        }
        header('X-Content-Type-Options: nosniff');
        header('X-Frame-Options: SAMEORIGIN');
        header('Referrer-Policy: strict-origin-when-cross-origin');
        // Camera/mic/screen only for this site (video interviews); nothing else may use them.
        header('Permissions-Policy: camera=(self), microphone=(self), display-capture=(self), geolocation=(self), payment=(), usb=()');
        header('Cross-Origin-Opener-Policy: same-origin-allow-popups');
        if (self::isHttps()) {
            header('Strict-Transport-Security: max-age=31536000; includeSubDomains');
        }
        header_remove('X-Powered-By');
    }

    public static function csrfToken(): string
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            self::startSession();
        }
        if (empty($_SESSION['csrf_token']) || !is_string($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    /** Token sent with the request (header, form field or query for beacons). */
    private static function submittedToken(): string
    {
        $candidates = [
            $_SERVER['HTTP_X_CSRF_TOKEN'] ?? null,
            $_POST['_csrf'] ?? null,
            $_POST['csrf_token'] ?? null,
            $_GET['_csrf'] ?? null,
        ];
        foreach ($candidates as $t) {
            if (is_string($t) && $t !== '') {
                return $t;
            }
        }
        return '';
    }

    public static function verifyCsrf(string $method, string $path): void
    {
        if (!in_array($method, ['POST', 'PUT', 'PATCH', 'DELETE'], true) || in_array($path, self::CSRF_EXEMPT, true)
            || array_filter(self::CSRF_EXEMPT_PREFIXES, static fn ($p) => str_starts_with($path, $p))) {
            return;
        }
        $token = self::submittedToken();
        $valid = $token !== '' && (
            hash_equals(self::csrfToken(), $token)
            // The admin sign-in form predates the global token and posts its own.
            || (!empty($_SESSION['admin_csrf_token']) && is_string($_SESSION['admin_csrf_token']) && hash_equals($_SESSION['admin_csrf_token'], $token))
        );
        if ($valid) {
            return;
        }
        error_log('CSRF rejected: ' . $method . ' ' . $path);
        http_response_code(403); // 419 is not a standard status: Apache turns it into 500
        header('Cache-Control: no-store');
        $wantsJson = str_contains(strtolower((string)($_SERVER['HTTP_ACCEPT'] ?? '')), 'json')
            || !empty($_SERVER['HTTP_X_CSRF_TOKEN'])
            || str_contains(strtolower((string)($_SERVER['CONTENT_TYPE'] ?? '')), 'json')
            || in_array(strtolower((string)($_SERVER['HTTP_SEC_FETCH_MODE'] ?? '')), ['cors', 'same-origin'], true);
        if ($wantsJson) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['success' => false, 'error' => 'Your session expired or the page is out of date. Refresh the page and try again.', 'code' => 'csrf_mismatch']);
        } else {
            header('Content-Type: text/html; charset=utf-8');
            $back = htmlspecialchars((string)($_SERVER['HTTP_REFERER'] ?? url('/')), ENT_QUOTES);
            echo '<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Page expired - InternConnect</title></head>'
                . '<body style="font-family:Inter,system-ui,sans-serif;background:#F8FAFC;display:flex;align-items:center;justify-content:center;min-height:100vh;margin:0;padding:16px;">'
                . '<div style="max-width:420px;background:#fff;border:1px solid #E2E8F0;border-radius:16px;padding:28px;text-align:center;">'
                . '<h1 style="font-size:19px;color:#000623;margin:0 0 8px;">This page expired</h1>'
                . '<p style="font-size:14px;color:#64748B;line-height:1.55;margin:0 0 18px;">For your security the form could not be sent. Go back, refresh the page and try again.</p>'
                . '<a href="' . $back . '" style="display:inline-block;background:#4F48E6;color:#fff;text-decoration:none;font-weight:700;font-size:14px;padding:10px 18px;border-radius:10px;">Go back</a>'
                . '</div></body></html>';
        }
        exit;
    }

    /** CORS for /api/*: only origins listed in API_CORS_ORIGINS (comma separated). Answers preflights. */
    public static function apiCors(string $method): void
    {
        $origin = (string)($_SERVER['HTTP_ORIGIN'] ?? '');
        $allowed = array_filter(array_map('trim', explode(',', (string)($_ENV['API_CORS_ORIGINS'] ?? ''))));
        if ($origin !== '' && in_array($origin, $allowed, true)) {
            header('Access-Control-Allow-Origin: ' . $origin);
            header('Vary: Origin');
            header('Access-Control-Allow-Headers: Authorization, Content-Type, Accept');
            header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
            header('Access-Control-Max-Age: 600');
        }
        if ($method === 'OPTIONS') {
            http_response_code(204);
            exit;
        }
    }

    /**
     * Web sign-in throttle: 5 tries per minute per e-mail+IP and 20 per 15 minutes per IP.
     * Returns null when allowed, or a message to show.
     */
    public static function throttleLogin(string $scope, string $email): ?string
    {
        $ip = RateLimiter::ip();
        foreach ([[$scope . ':ip:' . $ip, 20, 900], [$scope . ':acct:' . strtolower(trim($email)) . ':' . $ip, 5, 60]] as [$key, $limit, $window]) {
            $r = RateLimiter::hit($key, $limit, $window);
            if (!$r['allowed']) {
                $mins = (int)ceil($r['retry_after'] / 60);
                return 'Too many sign-in attempts. Please wait ' . ($r['retry_after'] < 60 ? $r['retry_after'] . ' seconds' : $mins . ' minute' . ($mins === 1 ? '' : 's')) . ' and try again.';
            }
        }
        return null;
    }
}
