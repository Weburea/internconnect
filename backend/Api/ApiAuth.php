<?php

declare(strict_types=1);

namespace App\Api;

use App\Core\Database;
use PDO;
use Throwable;

/**
 * /api/v1 authentication for mobile and third-party clients (docs/45 section 2.3, docs/51).
 *
 * - Access token: HS256 JWT (JWT_SECRET), 15 minutes, claims sub, role, jti, iat, exp, iss.
 * - Refresh token: opaque random string, 30 days, stored only as a SHA-256 hash, single use.
 *   Every refresh rotates the pair; re-using an old refresh token revokes the whole family
 *   (stolen-token detection).
 * - Logout revokes the refresh family and the current access token (jti denylist).
 *
 * Accounts: until users live in MySQL (backend phase) the API signs in the three demo accounts
 * (candidate, employer, admin) with the password in API_DEMO_PASSWORD. Without JWT_SECRET or
 * API_DEMO_PASSWORD the API refuses to sign anyone in.
 */
final class ApiAuth
{
    public const ACCESS_TTL = 900;          // 15 minutes
    public const REFRESH_TTL = 2592000;     // 30 days
    private const ISSUER = 'internconnect';

    /** Demo directory: email => account (id is the platform account id used across services). */
    private const ACCOUNTS = [
        'candidate@internconnect.ng' => ['id' => 1, 'role' => 'candidate', 'name' => 'Sarah Peters'],
        'employer@internconnect.ng'  => ['id' => 1, 'role' => 'employer', 'name' => 'Tunde Bakare', 'company' => 'Nexura Technologies'],
        'admin@internconnect.ng'     => ['id' => 1, 'role' => 'admin', 'name' => 'Jayden Okafor'],
    ];

    private static bool $ready = false;

    private static function pdo(): PDO
    {
        return Database::getConnection();
    }

    public static function secret(): string
    {
        return trim((string)($_ENV['JWT_SECRET'] ?? ''), "\"' ");
    }

    public static function isConfigured(): bool
    {
        return strlen(self::secret()) >= 32 && trim((string)($_ENV['API_DEMO_PASSWORD'] ?? '')) !== '';
    }

    private static function ensureTables(): void
    {
        if (self::$ready) {
            return;
        }
        $pdo = self::pdo();
        if ((string)$pdo->getAttribute(PDO::ATTR_DRIVER_NAME) === 'mysql') {
            $pdo->exec('CREATE TABLE IF NOT EXISTS api_refresh_tokens (
                id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY, token_hash CHAR(64) NOT NULL UNIQUE, family CHAR(32) NOT NULL,
                role VARCHAR(16) NOT NULL, account_id BIGINT UNSIGNED NOT NULL, expires_at INT UNSIGNED NOT NULL,
                used_at INT UNSIGNED NULL, revoked_at INT UNSIGNED NULL, created_at INT UNSIGNED NOT NULL, INDEX idx_art_family (family)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');
            $pdo->exec('CREATE TABLE IF NOT EXISTS api_revoked_jti (
                jti CHAR(32) NOT NULL PRIMARY KEY, expires_at INT UNSIGNED NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');
        } else {
            $pdo->exec('CREATE TABLE IF NOT EXISTS api_refresh_tokens (
                id INTEGER PRIMARY KEY AUTOINCREMENT, token_hash TEXT NOT NULL UNIQUE, family TEXT NOT NULL, role TEXT NOT NULL,
                account_id INTEGER NOT NULL, expires_at INTEGER NOT NULL, used_at INTEGER, revoked_at INTEGER, created_at INTEGER NOT NULL)');
            $pdo->exec('CREATE INDEX IF NOT EXISTS idx_art_family ON api_refresh_tokens (family)');
            $pdo->exec('CREATE TABLE IF NOT EXISTS api_revoked_jti (jti TEXT PRIMARY KEY, expires_at INTEGER NOT NULL)');
        }
        self::$ready = true;
    }

    /** @return array{id:int, role:string, name:string, email:string}|null */
    public static function verifyCredentials(string $email, string $password): ?array
    {
        $email = strtolower(trim($email));
        $expected = trim((string)($_ENV['API_DEMO_PASSWORD'] ?? ''), "\"' ");
        $account = self::ACCOUNTS[$email] ?? null;
        // Always compare (constant time) so unknown e-mails take as long as wrong passwords.
        $ok = hash_equals(hash('sha256', $expected), hash('sha256', $password));
        if (!$account || !$ok || $expected === '') {
            return null;
        }
        return $account + ['email' => $email];
    }

    public static function account(string $role, int $id): ?array
    {
        foreach (self::ACCOUNTS as $email => $a) {
            if ($a['role'] === $role && $a['id'] === $id) {
                return $a + ['email' => $email];
            }
        }
        return null;
    }

    /** Issue an access + refresh pair (new family unless $family is given). */
    public static function issue(array $account, ?string $family = null): array
    {
        self::ensureTables();
        $now = time();
        $jti = bin2hex(random_bytes(16));
        $access = Jwt::encode([
            'iss' => self::ISSUER, 'sub' => (int)$account['id'], 'role' => $account['role'],
            'jti' => $jti, 'iat' => $now, 'nbf' => $now, 'exp' => $now + self::ACCESS_TTL,
        ], self::secret());
        $refresh = bin2hex(random_bytes(32));
        $family = $family ?? bin2hex(random_bytes(16));
        self::pdo()->prepare('INSERT INTO api_refresh_tokens (token_hash, family, role, account_id, expires_at, created_at) VALUES (?, ?, ?, ?, ?, ?)')
            ->execute([hash('sha256', $refresh), $family, $account['role'], (int)$account['id'], $now + self::REFRESH_TTL, $now]);
        return [
            'token_type'         => 'Bearer',
            'access_token'       => $access,
            'expires_in'         => self::ACCESS_TTL,
            'refresh_token'      => $refresh,
            'refresh_expires_in' => self::REFRESH_TTL,
        ];
    }

    /** Rotate a refresh token. Re-use of a spent token revokes its whole family. */
    public static function refresh(string $refreshToken): ?array
    {
        self::ensureTables();
        $now = time();
        $stmt = self::pdo()->prepare('SELECT * FROM api_refresh_tokens WHERE token_hash = ?');
        $stmt->execute([hash('sha256', $refreshToken)]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row || $row['revoked_at'] !== null || (int)$row['expires_at'] <= $now) {
            return null;
        }
        if ($row['used_at'] !== null) {
            self::revokeFamily((string)$row['family']);
            return null;
        }
        self::pdo()->prepare('UPDATE api_refresh_tokens SET used_at = ? WHERE id = ?')->execute([$now, (int)$row['id']]);
        $account = self::account((string)$row['role'], (int)$row['account_id']);
        return $account ? self::issue($account, (string)$row['family']) : null;
    }

    public static function revokeFamily(string $family): void
    {
        self::ensureTables();
        self::pdo()->prepare('UPDATE api_refresh_tokens SET revoked_at = ? WHERE family = ? AND revoked_at IS NULL')->execute([time(), $family]);
    }

    /** Logout: revoke the refresh family (if given) and deny the current access token until it expires. */
    public static function logout(array $claims, ?string $refreshToken): void
    {
        self::ensureTables();
        if ($refreshToken) {
            $stmt = self::pdo()->prepare('SELECT family FROM api_refresh_tokens WHERE token_hash = ? AND account_id = ? AND role = ?');
            $stmt->execute([hash('sha256', $refreshToken), (int)$claims['sub'], (string)$claims['role']]);
            $family = $stmt->fetchColumn();
            if ($family) {
                self::revokeFamily((string)$family);
            }
        }
        try {
            self::pdo()->prepare('INSERT INTO api_revoked_jti (jti, expires_at) VALUES (?, ?)')->execute([(string)$claims['jti'], (int)$claims['exp']]);
        } catch (Throwable $e) {
            // already revoked
        }
    }

    /** Validate "Authorization: Bearer <jwt>". Returns the account (with claims) or null. */
    public static function fromRequest(): ?array
    {
        $header = (string)($_SERVER['HTTP_AUTHORIZATION'] ?? $_SERVER['REDIRECT_HTTP_AUTHORIZATION'] ?? '');
        if ($header === '' && function_exists('getallheaders')) {
            foreach ((array)getallheaders() as $k => $v) {
                if (strtolower((string)$k) === 'authorization') {
                    $header = (string)$v;
                }
            }
        }
        if (!preg_match('/^Bearer\s+([A-Za-z0-9\-_]+\.[A-Za-z0-9\-_]+\.[A-Za-z0-9\-_]+)$/', trim($header), $m)) {
            return null;
        }
        return self::fromToken($m[1]);
    }

    public static function fromToken(string $jwt): ?array
    {
        $claims = Jwt::decode($jwt, self::secret());
        if (!$claims || ($claims['iss'] ?? '') !== self::ISSUER || empty($claims['jti'])) {
            return null;
        }
        self::ensureTables();
        $stmt = self::pdo()->prepare('SELECT 1 FROM api_revoked_jti WHERE jti = ?');
        $stmt->execute([(string)$claims['jti']]);
        if ($stmt->fetchColumn()) {
            return null;
        }
        $account = self::account((string)($claims['role'] ?? ''), (int)($claims['sub'] ?? 0));
        return $account ? $account + ['claims' => $claims] : null;
    }
}
