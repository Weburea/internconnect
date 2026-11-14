<?php

declare(strict_types=1);

namespace App\Api;

/**
 * Minimal HS256 JSON Web Token encoder/decoder (RFC 7519). No external dependency.
 * Only HS256 is accepted on decode ("alg" is never trusted from the token).
 */
final class Jwt
{
    public static function encode(array $claims, string $secret): string
    {
        $header = ['alg' => 'HS256', 'typ' => 'JWT'];
        $segments = [self::b64(json_encode($header)), self::b64(json_encode($claims, JSON_UNESCAPED_SLASHES))];
        $segments[] = self::b64(hash_hmac('sha256', implode('.', $segments), $secret, true));
        return implode('.', $segments);
    }

    /**
     * Verify signature, algorithm and time claims. Returns the claims or null.
     * $leeway allows small clock differences between servers (seconds).
     */
    public static function decode(string $token, string $secret, int $leeway = 30): ?array
    {
        $parts = explode('.', $token);
        if (count($parts) !== 3 || $secret === '') {
            return null;
        }
        [$h, $p, $s] = $parts;
        $header = json_decode(self::unb64($h), true);
        if (!is_array($header) || ($header['alg'] ?? '') !== 'HS256') {
            return null;
        }
        $expected = self::b64(hash_hmac('sha256', $h . '.' . $p, $secret, true));
        if (!hash_equals($expected, $s)) {
            return null;
        }
        $claims = json_decode(self::unb64($p), true);
        if (!is_array($claims)) {
            return null;
        }
        $now = time();
        if (isset($claims['exp']) && $now - $leeway >= (int)$claims['exp']) {
            return null;
        }
        if (isset($claims['nbf']) && $now + $leeway < (int)$claims['nbf']) {
            return null;
        }
        return $claims;
    }

    private static function b64(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    private static function unb64(string $data): string
    {
        $pad = strlen($data) % 4;
        return (string)base64_decode(strtr($data . ($pad ? str_repeat('=', 4 - $pad) : ''), '-_', '+/'), true);
    }
}
