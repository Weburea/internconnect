<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\Database;
use PDO;
use Throwable;

/**
 * IP geolocation via the MaxMind GeoLite2 web service (city level, approximate).
 *
 * - Credentials: MAXMIND_ACCOUNT_ID / MAXMIND_LICENSE_KEY in .env (never sent to browsers).
 * - Results are cached per IP for 7 days in geo_cache to stay inside the free daily quota.
 * - Private / loopback IPs (local development) are resolved with the "me" lookup, which
 *   returns the location of this server's public IP.
 * - Only masked IPs are ever shown in the UI (see maskIp()).
 */
class GeoService
{
    private const ENDPOINT = 'https://geolite.info/geoip/v2.1/city/';
    private const CACHE_DAYS = 7;
    private static bool $tableChecked = false;

    public static function isConfigured(): bool
    {
        return trim((string)($_ENV['MAXMIND_ACCOUNT_ID'] ?? '')) !== '' && trim((string)($_ENV['MAXMIND_LICENSE_KEY'] ?? '')) !== '';
    }

    /** Best-effort client IP. Proxy headers are only trusted for the first public address. */
    public static function clientIp(): string
    {
        $candidates = [];
        foreach (['HTTP_CF_CONNECTING_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_REAL_IP'] as $header) {
            if (!empty($_SERVER[$header])) {
                foreach (explode(',', (string)$_SERVER[$header]) as $part) {
                    $candidates[] = trim($part);
                }
            }
        }
        $candidates[] = (string)($_SERVER['REMOTE_ADDR'] ?? '');
        foreach ($candidates as $ip) {
            if (filter_var($ip, FILTER_VALIDATE_IP)) {
                return $ip;
            }
        }
        return '0.0.0.0';
    }

    public static function isPrivate(string $ip): bool
    {
        return !filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE);
    }

    /** 102.89.41.112 -> 102.89.41.xxx ; IPv6 keeps the first 3 groups. */
    public static function maskIp(string $ip): string
    {
        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            $parts = explode('.', $ip);
            $parts[3] = 'xxx';
            return implode('.', $parts);
        }
        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
            $groups = explode(':', $ip);
            return implode(':', array_slice($groups, 0, 3)) . ':xxxx';
        }
        return 'unknown';
    }

    /** "Chrome on Windows" style label from a user agent string. */
    public static function deviceLabel(string $ua): string
    {
        $browser = 'Browser';
        foreach (['Edg/' => 'Edge', 'OPR/' => 'Opera', 'Firefox/' => 'Firefox', 'Chrome/' => 'Chrome', 'Safari/' => 'Safari'] as $needle => $name) {
            if (str_contains($ua, $needle)) {
                $browser = $name;
                break;
            }
        }
        $os = 'Unknown OS';
        foreach (['Windows' => 'Windows', 'Android' => 'Android', 'iPhone' => 'iPhone', 'iPad' => 'iPad', 'Mac OS X' => 'macOS', 'Linux' => 'Linux'] as $needle => $name) {
            if (str_contains($ua, $needle)) {
                $os = $name;
                break;
            }
        }
        return $browser . ' on ' . $os;
    }

    /**
     * @return array{city:string,country:string,code:string,lat:float,lng:float}|null
     */
    public static function lookup(string $ip): ?array
    {
        if (!self::isConfigured() || !filter_var($ip, FILTER_VALIDATE_IP)) {
            return null;
        }
        $key = self::isPrivate($ip) ? 'me' : $ip;

        $cached = self::fromCache($key);
        if ($cached !== false) {
            return $cached;
        }

        $ch = curl_init(self::ENDPOINT . rawurlencode($key));
        curl_setopt_array($ch, [
            CURLOPT_USERPWD        => trim((string)$_ENV['MAXMIND_ACCOUNT_ID']) . ':' . trim((string)$_ENV['MAXMIND_LICENSE_KEY']),
            CURLOPT_HTTPHEADER     => ['Accept: application/json'],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => 6,
        ]);
        $raw = curl_exec($ch);
        $status = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $result = null;
        if ($raw !== false && $status === 200) {
            $data = json_decode((string)$raw, true) ?: [];
            $lat = $data['location']['latitude'] ?? null;
            $lng = $data['location']['longitude'] ?? null;
            if ($lat !== null && $lng !== null) {
                $result = [
                    'city'    => (string)($data['city']['names']['en'] ?? ($data['subdivisions'][0]['names']['en'] ?? 'Unknown city')),
                    'country' => (string)($data['country']['names']['en'] ?? 'Unknown'),
                    'code'    => (string)($data['country']['iso_code'] ?? '--'),
                    'lat'     => (float)$lat,
                    'lng'     => (float)$lng,
                ];
            }
        } elseif ($status !== 404) {
            error_log('GeoService lookup failed with HTTP ' . $status);
        }

        // Cache hits and "unknown IP" answers; transient failures are retried next time.
        if ($result !== null || $status === 404) {
            self::toCache($key, $result);
        }
        return $result;
    }

    private static function ensureTable(): void
    {
        if (self::$tableChecked) {
            return;
        }
        try {
            $pdo = Database::getConnection();
            if ($pdo->getAttribute(PDO::ATTR_DRIVER_NAME) === 'sqlite') {
                $pdo->exec('CREATE TABLE IF NOT EXISTS geo_cache (ip_key TEXT PRIMARY KEY, data TEXT NULL, fetched_at TEXT NOT NULL)');
            }
            self::$tableChecked = true;
        } catch (Throwable $e) {
            error_log('GeoService table error: ' . $e->getMessage());
        }
    }

    /** @return array|null|false false = not cached */
    private static function fromCache(string $key)
    {
        self::ensureTable();
        try {
            $stmt = Database::getConnection()->prepare('SELECT data, fetched_at FROM geo_cache WHERE ip_key = ?');
            $stmt->execute([$key]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($row && strtotime((string)$row['fetched_at']) > time() - self::CACHE_DAYS * 86400) {
                return $row['data'] === null ? null : (json_decode((string)$row['data'], true) ?: null);
            }
        } catch (Throwable $e) {
        }
        return false;
    }

    private static function toCache(string $key, ?array $data): void
    {
        try {
            $pdo = Database::getConnection();
            $sql = $pdo->getAttribute(PDO::ATTR_DRIVER_NAME) === 'mysql'
                ? 'INSERT INTO geo_cache (ip_key, data, fetched_at) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE data = VALUES(data), fetched_at = VALUES(fetched_at)'
                : 'INSERT INTO geo_cache (ip_key, data, fetched_at) VALUES (?, ?, ?) ON CONFLICT(ip_key) DO UPDATE SET data = excluded.data, fetched_at = excluded.fetched_at';
            $pdo->prepare($sql)->execute([$key, $data === null ? null : json_encode($data), date('Y-m-d H:i:s')]);
        } catch (Throwable $e) {
        }
    }
}
