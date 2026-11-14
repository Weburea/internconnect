<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\Database;
use PDO;
use Throwable;

/**
 * Fixed-window rate limiter backed by the database (works on shared hosting: no Redis/APCu needed).
 *
 *   $r = RateLimiter::hit('login:' . $ip, 5, 60);   // 5 attempts per 60 seconds
 *   if (!$r['allowed']) { ... retry after $r['retry_after'] seconds ... }
 *
 * Keys are hashed before storage, so e-mail addresses and IPs are never kept in clear text.
 * Spec: docs/51-api-and-security.md
 */
class RateLimiter
{
    private static bool $ready = false;

    private static function pdo(): PDO
    {
        return Database::getConnection();
    }

    private static function ensureTable(): void
    {
        if (self::$ready) {
            return;
        }
        $pdo = self::pdo();
        if ((string)$pdo->getAttribute(PDO::ATTR_DRIVER_NAME) === 'mysql') {
            $pdo->exec('CREATE TABLE IF NOT EXISTS rate_limits (
                bucket CHAR(64) NOT NULL PRIMARY KEY, window_start INT UNSIGNED NOT NULL, hits INT UNSIGNED NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');
        } else {
            $pdo->exec('CREATE TABLE IF NOT EXISTS rate_limits (bucket TEXT PRIMARY KEY, window_start INTEGER NOT NULL, hits INTEGER NOT NULL)');
        }
        self::$ready = true;
    }

    /**
     * Count one attempt for $key. Fails open (allowed) if the database is unavailable,
     * so a storage problem never locks every user out.
     *
     * @return array{allowed: bool, remaining: int, retry_after: int, limit: int}
     */
    public static function hit(string $key, int $limit, int $windowSeconds): array
    {
        $now = time();
        $bucket = hash('sha256', $key);
        try {
            self::ensureTable();
            $pdo = self::pdo();
            $stmt = $pdo->prepare('SELECT window_start, hits FROM rate_limits WHERE bucket = ?');
            $stmt->execute([$bucket]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$row || (int)$row['window_start'] + $windowSeconds <= $now) {
                $pdo->prepare('DELETE FROM rate_limits WHERE bucket = ?')->execute([$bucket]);
                $pdo->prepare('INSERT INTO rate_limits (bucket, window_start, hits) VALUES (?, ?, 1)')->execute([$bucket, $now]);
                $hits = 1;
                $start = $now;
            } else {
                $pdo->prepare('UPDATE rate_limits SET hits = hits + 1 WHERE bucket = ?')->execute([$bucket]);
                $hits = (int)$row['hits'] + 1;
                $start = (int)$row['window_start'];
            }
            // Occasional cleanup of expired windows.
            if (random_int(1, 200) === 1) {
                $pdo->prepare('DELETE FROM rate_limits WHERE window_start < ?')->execute([$now - 86400]);
            }
        } catch (Throwable $e) {
            error_log('RateLimiter: ' . $e->getMessage());
            return ['allowed' => true, 'remaining' => $limit, 'retry_after' => 0, 'limit' => $limit];
        }
        return [
            'allowed'     => $hits <= $limit,
            'remaining'   => max(0, $limit - $hits),
            'retry_after' => $hits <= $limit ? 0 : max(1, $start + $windowSeconds - $now),
            'limit'       => $limit,
        ];
    }

    /** Forget a key (e.g. after a successful login). */
    public static function clear(string $key): void
    {
        try {
            self::ensureTable();
            self::pdo()->prepare('DELETE FROM rate_limits WHERE bucket = ?')->execute([hash('sha256', $key)]);
        } catch (Throwable $e) {
        }
    }

    /** Client IP (REMOTE_ADDR only: forwarded headers are spoofable unless a trusted proxy is configured). */
    public static function ip(): string
    {
        return (string)($_SERVER['REMOTE_ADDR'] ?? '0.0.0.0');
    }
}
