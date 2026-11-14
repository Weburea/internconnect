<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\Database;
use PDO;
use Throwable;

/**
 * Visitor tracking for the admin live map and "Near me" (docs/56-location-and-visits.md).
 *
 * The browser sends a beacon after each page load and a heartbeat every minute while the tab is
 * visible (public/assets/js/ic-visit.js), so a slow geolocation lookup never delays a page.
 * One row per browser session: masked IP, approximate city (MaxMind GeoLite2, cached 7 days per IP),
 * device, role, current page, first/last seen. "Online" = seen in the last 5 minutes.
 * The returning-visitor id cookie `ic_vid` is only set when the visitor allowed analytics cookies.
 */
final class VisitService
{
    public const ONLINE_SECONDS = 300;
    private static bool $ready = false;

    private static function pdo(): PDO
    {
        return Database::getConnection();
    }

    public static function ensureTables(): void
    {
        if (self::$ready) {
            return;
        }
        $pdo = self::pdo();
        if ((string)$pdo->getAttribute(PDO::ATTR_DRIVER_NAME) === 'mysql') {
            $pdo->exec('CREATE TABLE IF NOT EXISTS visit_sessions (
                id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY, session_key CHAR(64) NOT NULL, visitor_key CHAR(64) NULL,
                role VARCHAR(16) NOT NULL DEFAULT \'visitor\', ip_masked VARCHAR(45) NULL, city VARCHAR(80) NULL, country VARCHAR(80) NULL,
                country_code CHAR(2) NULL, lat DECIMAL(9,5) NULL, lng DECIMAL(9,5) NULL, device VARCHAR(60) NULL, page VARCHAR(200) NULL,
                pages INT UNSIGNED NOT NULL DEFAULT 1, first_seen DATETIME NOT NULL, last_seen DATETIME NOT NULL,
                UNIQUE KEY uq_visit_session (session_key), INDEX idx_visit_last (last_seen), INDEX idx_visit_visitor (visitor_key)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');
        } else {
            $pdo->exec('CREATE TABLE IF NOT EXISTS visit_sessions (id INTEGER PRIMARY KEY AUTOINCREMENT, session_key TEXT NOT NULL UNIQUE, visitor_key TEXT,
                role TEXT NOT NULL DEFAULT \'visitor\', ip_masked TEXT, city TEXT, country TEXT, country_code TEXT, lat REAL, lng REAL, device TEXT, page TEXT,
                pages INTEGER NOT NULL DEFAULT 1, first_seen TEXT NOT NULL, last_seen TEXT NOT NULL)');
            $pdo->exec('CREATE INDEX IF NOT EXISTS idx_visit_last ON visit_sessions (last_seen)');
            $pdo->exec('CREATE INDEX IF NOT EXISTS idx_visit_visitor ON visit_sessions (visitor_key)');
        }
        self::$ready = true;
    }

    /** Analytics consent from the ic_consent cookie (public/assets/js/ic-consent.js). */
    public static function analyticsAllowed(): bool
    {
        $raw = (string)($_COOKIE['ic_consent'] ?? '');
        $c = $raw !== '' ? json_decode($raw, true) : null;
        return is_array($c) && !empty($c['analytics']);
    }

    private static function role(): string
    {
        if (!empty($_SESSION['admin_logged_in'])) {
            return 'staff';
        }
        if (!empty($_SESSION['is_logged_in'])) {
            return ($_SESSION['user_role'] ?? 'candidate') === 'employer' ? 'employer' : 'student';
        }
        return 'visitor';
    }

    /** Record one page view or heartbeat. $page is the path the browser reports. */
    public static function track(string $page, bool $heartbeat = false): void
    {
        self::ensureTables();
        if (session_id() === '') {
            return;
        }
        $page = mb_substr(preg_replace('#[^A-Za-z0-9/_\-.]#', '', (string)parse_url($page, PHP_URL_PATH)) ?? '/', 0, 200) ?: '/';
        $sessionKey = hash('sha256', 'visit:' . session_id());
        $visitorKey = null;
        if (self::analyticsAllowed()) {
            $vid = (string)($_COOKIE['ic_vid'] ?? '');
            if (!preg_match('/^[a-f0-9]{32}$/', $vid)) {
                $vid = bin2hex(random_bytes(16));
                setcookie('ic_vid', $vid, ['expires' => time() + 365 * 86400, 'path' => '/', 'samesite' => 'Lax', 'secure' => \App\Core\Security::isHttps(), 'httponly' => true]);
            }
            $visitorKey = hash('sha256', 'vid:' . $vid);
        }
        $now = date('Y-m-d H:i:s');
        $pdo = self::pdo();
        $stmt = $pdo->prepare('SELECT id FROM visit_sessions WHERE session_key = ?');
        $stmt->execute([$sessionKey]);
        $id = $stmt->fetchColumn();
        if ($id) {
            $pdo->prepare('UPDATE visit_sessions SET last_seen = ?, page = ?, role = ?, visitor_key = COALESCE(?, visitor_key)' . ($heartbeat ? '' : ', pages = pages + 1') . ' WHERE id = ?')
                ->execute([$now, $page, self::role(), $visitorKey, $id]);
            return;
        }
        $ip = GeoService::clientIp();
        $geo = null;
        try {
            $geo = GeoService::lookup($ip);
        } catch (Throwable $e) {
            $geo = null;
        }
        $pdo->prepare('INSERT INTO visit_sessions (session_key, visitor_key, role, ip_masked, city, country, country_code, lat, lng, device, page, pages, first_seen, last_seen) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1, ?, ?)')
            ->execute([$sessionKey, $visitorKey, self::role(), GeoService::maskIp($ip), $geo['city'] ?? null, $geo['country'] ?? null, $geo['code'] ?? null,
                $geo['lat'] ?? null, $geo['lng'] ?? null, mb_substr(GeoService::deviceLabel((string)($_SERVER['HTTP_USER_AGENT'] ?? '')), 0, 60), $page, $now, $now]);
    }

    /** Approximate location of the current visitor (for "Near me"). */
    public static function here(): ?array
    {
        self::ensureTables();
        $stmt = self::pdo()->prepare('SELECT city, country, lat, lng FROM visit_sessions WHERE session_key = ?');
        $stmt->execute([hash('sha256', 'visit:' . session_id())]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row && $row['city']) {
            return $row;
        }
        try {
            $g = GeoService::lookup(GeoService::clientIp());
        } catch (Throwable $e) {
            $g = null;
        }
        return $g ? ['city' => $g['city'], 'country' => $g['country'], 'lat' => $g['lat'], 'lng' => $g['lng']] : null;
    }

    /** Live map data for admin (same shape the overview map already reads). */
    public static function live(): array
    {
        self::ensureTables();
        $since = date('Y-m-d H:i:s', time() - self::ONLINE_SECONDS);
        $stmt = self::pdo()->prepare('SELECT * FROM visit_sessions WHERE last_seen >= ? ORDER BY last_seen DESC LIMIT 500');
        $stmt->execute([$since]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $locations = [];
        foreach ($rows as $r) {
            if ($r['lat'] === null || $r['lng'] === null) {
                continue;
            }
            $key = ($r['city'] ?? '?') . '|' . ($r['country_code'] ?? '');
            $locations[$key] ??= ['city' => $r['city'] ?: 'Unknown city', 'country' => $r['country'] ?: 'Unknown', 'code' => $r['country_code'] ?: '--',
                'lat' => (float)$r['lat'], 'lng' => (float)$r['lng'], 'online' => 0, 'students' => 0, 'employers' => 0];
            $locations[$key]['online']++;
            if ($r['role'] === 'student') {
                $locations[$key]['students']++;
            } elseif ($r['role'] === 'employer') {
                $locations[$key]['employers']++;
            }
        }
        $locations = array_values($locations);
        usort($locations, static fn ($a, $b) => $b['online'] <=> $a['online']);
        $total = count($rows);

        $countries = [];
        foreach ($rows as $r) {
            $code = $r['country_code'] ?: '--';
            $countries[$code]['code'] = $code;
            $countries[$code]['name'] = $r['country'] ?: 'Unknown';
            $countries[$code]['online'] = ($countries[$code]['online'] ?? 0) + 1;
        }
        $countries = array_values($countries);
        usort($countries, static fn ($a, $b) => $b['online'] <=> $a['online']);
        foreach ($countries as &$c) {
            $c['share'] = $total ? round($c['online'] / $total * 100, 1) : 0;
        }
        unset($c);

        $labels = ['/' => 'Home', '/internships' => 'Browse internships', '/pricing' => 'Pricing', '/elearning' => 'E-learning', '/contact' => 'Contact'];
        $sessions = [];
        foreach (array_slice($rows, 0, 50) as $r) {
            $page = (string)$r['page'];
            $label = $labels[preg_replace('#^/InternConnect#', '', $page) ?: '/'] ?? ucwords(str_replace(['-', '/'], [' ', ' / '], trim((string)preg_replace('#^/InternConnect#', '', $page), '/'))) ?: 'Home';
            $mins = max(0, (int)floor((time() - strtotime((string)$r['last_seen'])) / 60));
            $sessions[] = [
                'id'      => (int)$r['id'],
                'city'    => $r['city'] ?: 'Unknown city',
                'country' => $r['country'] ?: 'Unknown',
                'code'    => $r['country_code'] ?: '--',
                'ip'      => $r['ip_masked'] ?: 'hidden',
                'device'  => $r['device'] ?: 'Unknown device',
                'role'    => ['student' => 'Student', 'employer' => 'Employer', 'staff' => 'Staff'][$r['role']] ?? 'Visitor',
                'page'    => mb_strimwidth($label, 0, 40, '...'),
                'active'  => $mins < 1 ? 'now' : $mins . 'm',
            ];
        }
        return [
            'is_sample' => false,
            'total'     => $total,
            'students'  => count(array_filter($rows, static fn ($r) => $r['role'] === 'student')),
            'employers' => count(array_filter($rows, static fn ($r) => $r['role'] === 'employer')),
            'locations' => $locations,
            'countries' => $countries,
            'sessions'  => $sessions,
            'today'     => self::countSince(date('Y-m-d 00:00:00')),
        ];
    }

    public static function countSince(string $since): int
    {
        self::ensureTables();
        $stmt = self::pdo()->prepare('SELECT COUNT(*) FROM visit_sessions WHERE first_seen >= ?');
        $stmt->execute([$since]);
        return (int)$stmt->fetchColumn();
    }

    /** Cron: drop session rows older than 90 days (privacy policy: security logs 12 months, visits 90 days). */
    public static function prune(int $days = 90): int
    {
        self::ensureTables();
        $stmt = self::pdo()->prepare('DELETE FROM visit_sessions WHERE last_seen < ?');
        $stmt->execute([date('Y-m-d H:i:s', strtotime('-' . $days . ' days'))]);
        return $stmt->rowCount();
    }
}
