<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\Database;
use App\Core\Env;
use PDO;
use Throwable;

/**
 * Training partners (Jigide first, any partner later). docs/57-training-partners.md (+ partner API: docs/58).
 *
 * Flow: a candidate starts a partner course from InternConnect -> partner_applications row with a
 * private reference -> the student is sent to the partner with that reference (signed) -> the partner
 * reports status and the final score to /api/v1/partners/* -> a training_results row -> tier rules turn
 * the score into a free plan (plan_grants via PlanService::grant, never Paystack, never a downgrade).
 *
 * Everything that changes between partners is data, edited in Admin > E-Learning > Training partners:
 * pass mark, score ranges, plan, duration (per partner, or the global defaults), status, keys.
 */
final class PartnerService
{
    public const APP_STATUSES = ['started', 'enrolled', 'in_progress', 'completed', 'failed', 'withdrawn', 'expired'];
    public const REPORTABLE_STATUSES = ['enrolled', 'in_progress', 'withdrawn'];
    public const TIMESTAMP_SKEW = 300;
    private static bool $ready = false;

    private static function pdo(): PDO
    {
        return Database::getConnection();
    }

    private static function isMysql(PDO $pdo): bool
    {
        return (string)$pdo->getAttribute(PDO::ATTR_DRIVER_NAME) === 'mysql';
    }

    public static function ensureTables(): void
    {
        if (self::$ready) {
            return;
        }
        $pdo = self::pdo();
        if (self::isMysql($pdo)) {
            $t = ' ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci';
            $pdo->exec('CREATE TABLE IF NOT EXISTS training_partners (
                id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY, slug VARCHAR(60) NOT NULL, name VARCHAR(120) NOT NULL, website VARCHAR(255) NULL,
                application_url VARCHAR(500) NULL, contact_email VARCHAR(190) NULL, elearning_partner_id BIGINT UNSIGNED NULL,
                status VARCHAR(16) NOT NULL DEFAULT \'active\', pass_mark DECIMAL(5,2) NOT NULL DEFAULT 50, application_days INT UNSIGNED NOT NULL DEFAULT 120,
                api_key_prefix VARCHAR(16) NULL, api_key_hash CHAR(64) NULL, signing_secret_enc TEXT NULL, key_rotated_at DATETIME NULL,
                notes VARCHAR(500) NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL,
                UNIQUE KEY uq_tp_slug (slug), UNIQUE KEY uq_tp_prefix (api_key_prefix))' . $t);
            $pdo->exec('CREATE TABLE IF NOT EXISTS partner_applications (
                id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY, partner_id BIGINT UNSIGNED NOT NULL, candidate_id BIGINT UNSIGNED NOT NULL,
                reference VARCHAR(24) NOT NULL, program VARCHAR(160) NULL, status VARCHAR(16) NOT NULL DEFAULT \'started\', status_at DATETIME NOT NULL,
                created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL,
                UNIQUE KEY uq_pa_reference (reference), INDEX idx_pa_candidate (candidate_id, partner_id), INDEX idx_pa_partner (partner_id, status))' . $t);
            $pdo->exec('CREATE TABLE IF NOT EXISTS training_results (
                id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY, partner_id BIGINT UNSIGNED NOT NULL, application_id BIGINT UNSIGNED NOT NULL,
                candidate_id BIGINT UNSIGNED NOT NULL, reference VARCHAR(24) NOT NULL, score DECIMAL(5,2) NOT NULL, passed TINYINT(1) NOT NULL,
                course VARCHAR(160) NULL, certificate_id VARCHAR(80) NULL, certificate_url VARCHAR(500) NULL, completed_at DATETIME NULL,
                status VARCHAR(16) NOT NULL DEFAULT \'active\', supersedes_id BIGINT UNSIGNED NULL, rule_id BIGINT UNSIGNED NULL, grant_id BIGINT UNSIGNED NULL,
                plan VARCHAR(16) NULL, plan_days INT UNSIGNED NULL, source VARCHAR(16) NOT NULL DEFAULT \'api\', created_by VARCHAR(120) NULL,
                created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL,
                INDEX idx_tr_candidate (candidate_id, status), INDEX idx_tr_application (application_id, status), INDEX idx_tr_partner (partner_id, created_at))' . $t);
            $pdo->exec('CREATE TABLE IF NOT EXISTS tier_rules (
                id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY, partner_id BIGINT UNSIGNED NULL, min_score DECIMAL(5,2) NOT NULL, max_score DECIMAL(5,2) NOT NULL,
                plan VARCHAR(16) NOT NULL, duration_days INT UNSIGNED NOT NULL DEFAULT 0, is_active TINYINT(1) NOT NULL DEFAULT 1,
                created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX idx_tier_partner (partner_id, is_active))' . $t);
            $pdo->exec('CREATE TABLE IF NOT EXISTS api_audit_log (
                id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY, partner_id BIGINT UNSIGNED NULL, method VARCHAR(8) NOT NULL, path VARCHAR(200) NOT NULL,
                status SMALLINT UNSIGNED NOT NULL, error_code VARCHAR(40) NULL, idempotency_key VARCHAR(80) NULL, reference VARCHAR(24) NULL,
                ip_masked VARCHAR(45) NULL, duration_ms INT UNSIGNED NULL, created_at DATETIME NOT NULL,
                INDEX idx_audit_partner (partner_id, created_at), INDEX idx_audit_created (created_at))' . $t);
            $pdo->exec('CREATE TABLE IF NOT EXISTS partner_idempotency (
                partner_id BIGINT UNSIGNED NOT NULL, idem_key VARCHAR(80) NOT NULL, request_hash CHAR(64) NOT NULL, status SMALLINT UNSIGNED NOT NULL,
                response MEDIUMTEXT NOT NULL, created_at DATETIME NOT NULL, PRIMARY KEY (partner_id, idem_key))' . $t);
        } else {
            $pdo->exec('CREATE TABLE IF NOT EXISTS training_partners (id INTEGER PRIMARY KEY AUTOINCREMENT, slug TEXT NOT NULL UNIQUE, name TEXT NOT NULL, website TEXT,
                application_url TEXT, contact_email TEXT, elearning_partner_id INTEGER, status TEXT NOT NULL DEFAULT \'active\', pass_mark REAL NOT NULL DEFAULT 50,
                application_days INTEGER NOT NULL DEFAULT 120, api_key_prefix TEXT UNIQUE, api_key_hash TEXT, signing_secret_enc TEXT, key_rotated_at TEXT,
                notes TEXT, created_at TEXT NOT NULL, updated_at TEXT NOT NULL)');
            $pdo->exec('CREATE TABLE IF NOT EXISTS partner_applications (id INTEGER PRIMARY KEY AUTOINCREMENT, partner_id INTEGER NOT NULL, candidate_id INTEGER NOT NULL,
                reference TEXT NOT NULL UNIQUE, program TEXT, status TEXT NOT NULL DEFAULT \'started\', status_at TEXT NOT NULL, created_at TEXT NOT NULL, updated_at TEXT NOT NULL)');
            $pdo->exec('CREATE INDEX IF NOT EXISTS idx_pa_candidate ON partner_applications (candidate_id, partner_id)');
            $pdo->exec('CREATE INDEX IF NOT EXISTS idx_pa_partner ON partner_applications (partner_id, status)');
            $pdo->exec('CREATE TABLE IF NOT EXISTS training_results (id INTEGER PRIMARY KEY AUTOINCREMENT, partner_id INTEGER NOT NULL, application_id INTEGER NOT NULL,
                candidate_id INTEGER NOT NULL, reference TEXT NOT NULL, score REAL NOT NULL, passed INTEGER NOT NULL, course TEXT, certificate_id TEXT, certificate_url TEXT,
                completed_at TEXT, status TEXT NOT NULL DEFAULT \'active\', supersedes_id INTEGER, rule_id INTEGER, grant_id INTEGER, plan TEXT, plan_days INTEGER,
                source TEXT NOT NULL DEFAULT \'api\', created_by TEXT, created_at TEXT NOT NULL, updated_at TEXT NOT NULL)');
            $pdo->exec('CREATE INDEX IF NOT EXISTS idx_tr_candidate ON training_results (candidate_id, status)');
            $pdo->exec('CREATE INDEX IF NOT EXISTS idx_tr_application ON training_results (application_id, status)');
            $pdo->exec('CREATE TABLE IF NOT EXISTS tier_rules (id INTEGER PRIMARY KEY AUTOINCREMENT, partner_id INTEGER, min_score REAL NOT NULL, max_score REAL NOT NULL,
                plan TEXT NOT NULL, duration_days INTEGER NOT NULL DEFAULT 0, is_active INTEGER NOT NULL DEFAULT 1, created_at TEXT NOT NULL, updated_at TEXT NOT NULL)');
            $pdo->exec('CREATE TABLE IF NOT EXISTS api_audit_log (id INTEGER PRIMARY KEY AUTOINCREMENT, partner_id INTEGER, method TEXT NOT NULL, path TEXT NOT NULL,
                status INTEGER NOT NULL, error_code TEXT, idempotency_key TEXT, reference TEXT, ip_masked TEXT, duration_ms INTEGER, created_at TEXT NOT NULL)');
            $pdo->exec('CREATE INDEX IF NOT EXISTS idx_audit_partner ON api_audit_log (partner_id, created_at)');
            $pdo->exec('CREATE TABLE IF NOT EXISTS partner_idempotency (partner_id INTEGER NOT NULL, idem_key TEXT NOT NULL, request_hash TEXT NOT NULL,
                status INTEGER NOT NULL, response TEXT NOT NULL, created_at TEXT NOT NULL, PRIMARY KEY (partner_id, idem_key))');
        }
        self::$ready = true;
        self::seed($pdo);
    }

    /** Defaults from the Jigide brief: global tier rules and the first partner (keys created by admin). */
    private static function seed(PDO $pdo): void
    {
        $now = date('Y-m-d H:i:s');
        if ((int)$pdo->query('SELECT COUNT(*) FROM tier_rules WHERE partner_id IS NULL')->fetchColumn() === 0) {
            $ins = $pdo->prepare('INSERT INTO tier_rules (partner_id, min_score, max_score, plan, duration_days, is_active, created_at, updated_at) VALUES (NULL, ?, ?, ?, ?, 1, ?, ?)');
            foreach ([[85, 100, 'premium', 180], [65, 84.99, 'pro', 180], [50, 64.99, 'pro', 30], [0, 49.99, 'none', 0]] as [$min, $max, $plan, $days]) {
                $ins->execute([$min, $max, $plan, $days, $now, $now]);
            }
        }
        if ((int)$pdo->query("SELECT COUNT(*) FROM training_partners WHERE slug = 'jigide'")->fetchColumn() === 0) {
            $pdo->prepare('INSERT INTO training_partners (slug, name, website, application_url, status, pass_mark, application_days, notes, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)')
                ->execute(['jigide', 'Jigide', 'https://jigide.com', 'https://jigide.com/apply', 'paused', 50, 120, 'First certifying partner. Create API keys and set the application link, then activate.', $now, $now]);
        }
    }

    // ── Partners & keys ─────────────────────────────────────────────────────

    public static function partners(): array
    {
        self::ensureTables();
        return self::pdo()->query('SELECT * FROM training_partners ORDER BY name')->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function partner(int $id): ?array
    {
        self::ensureTables();
        $stmt = self::pdo()->prepare('SELECT * FROM training_partners WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public static function activePartners(): array
    {
        self::ensureTables();
        return self::pdo()->query("SELECT * FROM training_partners WHERE status = 'active' AND application_url IS NOT NULL AND application_url <> '' ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);
    }

    /** Create or update a partner (admin). Returns ['ok', 'id'|'error']. */
    public static function savePartner(array $in, ?int $id = null): array
    {
        self::ensureTables();
        $name = mb_substr(trim(strip_tags((string)($in['name'] ?? ''))), 0, 120);
        $slug = strtolower(trim(preg_replace('/[^a-z0-9]+/i', '-', (string)($in['slug'] ?? $name)) ?? '', '-'));
        $url = trim((string)($in['application_url'] ?? ''));
        $site = trim((string)($in['website'] ?? ''));
        $email = trim((string)($in['contact_email'] ?? ''));
        $pass = (float)($in['pass_mark'] ?? 50);
        $days = (int)($in['application_days'] ?? 120);
        $status = in_array($in['status'] ?? '', ['active', 'paused'], true) ? $in['status'] : 'paused';
        if ($name === '' || $slug === '') {
            return ['ok' => false, 'error' => 'Enter the partner name.'];
        }
        foreach (['application link' => $url, 'website' => $site] as $label => $u) {
            if ($u !== '' && !preg_match('#^https://#i', $u)) {
                return ['ok' => false, 'error' => 'The ' . $label . ' must start with https://'];
            }
        }
        if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['ok' => false, 'error' => 'Enter a valid contact e-mail.'];
        }
        if ($pass < 0 || $pass > 100) {
            return ['ok' => false, 'error' => 'The pass mark must be between 0 and 100.'];
        }
        if ($status === 'active' && $url === '') {
            return ['ok' => false, 'error' => 'Add the application link before activating the partner.'];
        }
        $now = date('Y-m-d H:i:s');
        $pdo = self::pdo();
        try {
            if ($id) {
                $pdo->prepare('UPDATE training_partners SET name = ?, slug = ?, website = ?, application_url = ?, contact_email = ?, status = ?, pass_mark = ?, application_days = ?, notes = ?, elearning_partner_id = ?, updated_at = ? WHERE id = ?')
                    ->execute([$name, $slug, $site ?: null, $url ?: null, $email ?: null, $status, $pass, max(7, min(730, $days)), mb_substr((string)($in['notes'] ?? ''), 0, 500) ?: null, ((int)($in['elearning_partner_id'] ?? 0)) ?: null, $now, $id]);
                return ['ok' => true, 'id' => $id];
            }
            $pdo->prepare('INSERT INTO training_partners (slug, name, website, application_url, contact_email, status, pass_mark, application_days, notes, elearning_partner_id, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)')
                ->execute([$slug, $name, $site ?: null, $url ?: null, $email ?: null, $status, $pass, max(7, min(730, $days)), mb_substr((string)($in['notes'] ?? ''), 0, 500) ?: null, ((int)($in['elearning_partner_id'] ?? 0)) ?: null, $now, $now]);
            return ['ok' => true, 'id' => (int)$pdo->lastInsertId()];
        } catch (Throwable $e) {
            return ['ok' => false, 'error' => 'A partner with that short name already exists.'];
        }
    }

    private static function cipherKey(): string
    {
        $k = Env::get('APP_KEY', Env::get('JWT_SECRET'));
        if (strlen($k) < 16) {
            throw new \RuntimeException('Set APP_KEY (or JWT_SECRET) in .env before creating partner keys.');
        }
        return hash('sha256', 'partner-secrets|' . $k, true);
    }

    private static function encrypt(string $plain): string
    {
        $iv = random_bytes(12);
        $tag = '';
        $ct = openssl_encrypt($plain, 'aes-256-gcm', self::cipherKey(), OPENSSL_RAW_DATA, $iv, $tag);
        return base64_encode($iv . $tag . $ct);
    }

    private static function decrypt(string $enc): ?string
    {
        $raw = base64_decode($enc, true);
        if ($raw === false || strlen($raw) < 29) {
            return null;
        }
        $plain = openssl_decrypt(substr($raw, 28), 'aes-256-gcm', self::cipherKey(), OPENSSL_RAW_DATA, substr($raw, 0, 12), substr($raw, 12, 16));
        return $plain === false ? null : $plain;
    }

    /**
     * New API key + signing secret for a partner (replaces the old pair immediately).
     * Returned once; only a hash of the key and an encrypted secret are stored.
     */
    public static function rotateKeys(int $partnerId): array
    {
        self::ensureTables();
        $prefix = bin2hex(random_bytes(4));
        $key = 'icp_' . $prefix . '_' . bin2hex(random_bytes(20));
        $secret = 'whsec_' . bin2hex(random_bytes(24));
        $now = date('Y-m-d H:i:s');
        self::pdo()->prepare('UPDATE training_partners SET api_key_prefix = ?, api_key_hash = ?, signing_secret_enc = ?, key_rotated_at = ?, updated_at = ? WHERE id = ?')
            ->execute([$prefix, hash('sha256', $key), self::encrypt($secret), $now, $now, $partnerId]);
        return ['api_key' => $key, 'signing_secret' => $secret];
    }

    /** Partner for an API key, or null. */
    public static function partnerForKey(string $key): ?array
    {
        if (!preg_match('/^icp_([a-f0-9]{8})_[a-f0-9]{40}$/', $key, $m)) {
            return null;
        }
        self::ensureTables();
        $stmt = self::pdo()->prepare('SELECT * FROM training_partners WHERE api_key_prefix = ?');
        $stmt->execute([$m[1]]);
        $p = $stmt->fetch(PDO::FETCH_ASSOC);
        return $p && $p['api_key_hash'] && hash_equals($p['api_key_hash'], hash('sha256', $key)) ? $p : null;
    }

    public static function signingSecret(array $partner): ?string
    {
        return empty($partner['signing_secret_enc']) ? null : self::decrypt((string)$partner['signing_secret_enc']);
    }

    /** X-Signature = hex(HMAC-SHA256(secret, timestamp + "." + raw body)). */
    public static function verifySignature(array $partner, string $timestamp, string $body, string $signature): bool
    {
        $secret = self::signingSecret($partner);
        if ($secret === null || !preg_match('/^\d{9,11}$/', $timestamp) || abs(time() - (int)$timestamp) > self::TIMESTAMP_SKEW) {
            return false;
        }
        $expected = hash_hmac('sha256', $timestamp . '.' . $body, $secret);
        return hash_equals($expected, strtolower(trim(preg_replace('/^sha256=/i', '', $signature) ?? '')));
    }

    // ── Applications (student side) ─────────────────────────────────────────

    /** Start (or reuse) an application and return it with the signed hand-off URL. */
    public static function startApplication(int $partnerId, int $candidateId, ?string $program = null): array
    {
        self::ensureTables();
        $p = self::partner($partnerId);
        if (!$p || $p['status'] !== 'active' || empty($p['application_url'])) {
            throw new \InvalidArgumentException('This training partner is not taking applications right now.');
        }
        $pdo = self::pdo();
        $stmt = $pdo->prepare("SELECT * FROM partner_applications WHERE partner_id = ? AND candidate_id = ? AND status IN ('started', 'enrolled', 'in_progress') ORDER BY id DESC LIMIT 1");
        $stmt->execute([$partnerId, $candidateId]);
        $app = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$app) {
            $now = date('Y-m-d H:i:s');
            $ref = 'ICT' . strtoupper(bin2hex(random_bytes(6)));
            $pdo->prepare("INSERT INTO partner_applications (partner_id, candidate_id, reference, program, status, status_at, created_at, updated_at) VALUES (?, ?, ?, ?, 'started', ?, ?, ?)")
                ->execute([$partnerId, $candidateId, $ref, $program ? mb_substr($program, 0, 160) : null, $now, $now, $now]);
            $app = self::application($ref);
        }
        $app['handoff_url'] = self::handoffUrl($p, $app['reference']);
        return $app;
    }

    /** Partner page + ?ic_ref=REFERENCE&ic_ts=...&ic_sig=HMAC so the partner can check the link came from us. */
    public static function handoffUrl(array $partner, string $reference): string
    {
        $ts = (string)time();
        $secret = self::signingSecret($partner) ?? '';
        $sig = $secret !== '' ? hash_hmac('sha256', $reference . '.' . $ts, $secret) : '';
        $sep = str_contains((string)$partner['application_url'], '?') ? '&' : '?';
        return $partner['application_url'] . $sep . http_build_query(['ic_ref' => $reference, 'ic_ts' => $ts, 'ic_sig' => $sig]);
    }

    public static function application(string $reference): ?array
    {
        self::ensureTables();
        $stmt = self::pdo()->prepare('SELECT * FROM partner_applications WHERE reference = ?');
        $stmt->execute([strtoupper(trim($reference))]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public static function setApplicationStatus(array $app, string $status): array
    {
        if (!in_array($status, self::APP_STATUSES, true)) {
            throw new \InvalidArgumentException('Unknown status.');
        }
        $now = date('Y-m-d H:i:s');
        self::pdo()->prepare('UPDATE partner_applications SET status = ?, status_at = ?, updated_at = ? WHERE id = ?')->execute([$status, $now, $now, $app['id']]);
        return self::application($app['reference']);
    }

    // ── Results & tier rules ────────────────────────────────────────────────

    /** Active rules for a partner: its own if it has any, otherwise the global defaults. */
    public static function rulesFor(?int $partnerId): array
    {
        self::ensureTables();
        $pdo = self::pdo();
        if ($partnerId) {
            $stmt = $pdo->prepare('SELECT * FROM tier_rules WHERE partner_id = ? AND is_active = 1 ORDER BY min_score DESC');
            $stmt->execute([$partnerId]);
            $own = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if ($own) {
                return $own;
            }
        }
        return $pdo->query('SELECT * FROM tier_rules WHERE partner_id IS NULL AND is_active = 1 ORDER BY min_score DESC')->fetchAll(PDO::FETCH_ASSOC);
    }

    /** What a score earns at this partner: ['passed', 'plan' => pro|premium|null, 'days', 'rule_id']. */
    public static function evaluate(array $partner, float $score): array
    {
        if ($score < (float)$partner['pass_mark']) {
            return ['passed' => false, 'plan' => null, 'days' => 0, 'rule_id' => null];
        }
        foreach (self::rulesFor((int)$partner['id']) as $r) {
            if ($score >= (float)$r['min_score'] && $score <= (float)$r['max_score']) {
                $plan = in_array($r['plan'], ['pro', 'premium'], true) && (int)$r['duration_days'] > 0 ? $r['plan'] : null;
                return ['passed' => true, 'plan' => $plan, 'days' => $plan ? (int)$r['duration_days'] : 0, 'rule_id' => (int)$r['id']];
            }
        }
        return ['passed' => true, 'plan' => null, 'days' => 0, 'rule_id' => null];
    }

    /**
     * Record a final result for an application (partner API or admin). A different score for the same
     * application supersedes the previous result: its plan grant is revoked and the new one issued.
     * Returns ['result' => row, 'outcome' => created|corrected|unchanged].
     */
    public static function recordResult(array $partner, array $app, float $score, array $extra = [], string $source = 'api', ?string $by = null): array
    {
        self::ensureTables();
        if ($score < 0 || $score > 100 || is_nan($score)) {
            throw new \InvalidArgumentException('Score must be between 0 and 100.');
        }
        if ((int)$app['partner_id'] !== (int)$partner['id']) {
            throw new \InvalidArgumentException('This reference belongs to another partner.');
        }
        // Create any missing tables now: on MySQL a CREATE TABLE inside the transaction would commit it early.
        PlanService::ensureTables();
        BillingService::ensureTables();
        $pdo = self::pdo();
        $stmt = $pdo->prepare("SELECT * FROM training_results WHERE application_id = ? AND status = 'active' ORDER BY id DESC LIMIT 1");
        $stmt->execute([$app['id']]);
        $previous = $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
        if ($previous && abs((float)$previous['score'] - $score) < 0.001
            && (string)($previous['certificate_id'] ?? '') === (string)($extra['certificate_id'] ?? $previous['certificate_id'] ?? '')) {
            return ['result' => self::result((int)$previous['id']), 'outcome' => 'unchanged'];
        }

        $eval = self::evaluate($partner, $score);
        $now = date('Y-m-d H:i:s');
        $completed = !empty($extra['completed_at']) && strtotime((string)$extra['completed_at']) ? date('Y-m-d H:i:s', strtotime((string)$extra['completed_at'])) : $now;
        $certUrl = trim((string)($extra['certificate_url'] ?? ''));
        if ($certUrl !== '' && !preg_match('#^https://#i', $certUrl)) {
            throw new \InvalidArgumentException('certificate_url must be an https:// link.');
        }

        $pdo->beginTransaction();
        try {
            if ($previous) {
                $pdo->prepare("UPDATE training_results SET status = 'superseded', updated_at = ? WHERE id = ?")->execute([$now, $previous['id']]);
                if (!empty($previous['grant_id'])) {
                    PlanService::revokeGrant((int)$previous['grant_id'], 'correction');
                }
            }
            $pdo->prepare('INSERT INTO training_results (partner_id, application_id, candidate_id, reference, score, passed, course, certificate_id, certificate_url, completed_at, status, supersedes_id, rule_id, plan, plan_days, source, created_by, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)')
                ->execute([$partner['id'], $app['id'], $app['candidate_id'], $app['reference'], round($score, 2), $eval['passed'] ? 1 : 0,
                    mb_substr(trim((string)($extra['course'] ?? $app['program'] ?? '')), 0, 160) ?: null,
                    mb_substr(trim((string)($extra['certificate_id'] ?? '')), 0, 80) ?: null, $certUrl ?: null, $completed, 'active',
                    $previous['id'] ?? null, $eval['rule_id'], $eval['plan'], $eval['days'] ?: null, $source, $by, $now, $now]);
            $resultId = (int)$pdo->lastInsertId();
            if ($eval['plan']) {
                $g = PlanService::grant('candidate', (int)$app['candidate_id'], $eval['plan'], $eval['days'], 'training', 'result:' . $resultId, $partner['name'] . ' certificate', $by ?? ('partner:' . $partner['slug']));
                $pdo->prepare('UPDATE training_results SET grant_id = ? WHERE id = ?')->execute([$g['id'], $resultId]);
            }
            $pdo->prepare('UPDATE partner_applications SET status = ?, status_at = ?, updated_at = ? WHERE id = ?')
                ->execute([$eval['passed'] ? 'completed' : 'failed', $now, $now, $app['id']]);
            $pdo->commit();
        } catch (Throwable $e) {
            $pdo->rollBack();
            throw $e;
        }
        return ['result' => self::result($resultId), 'outcome' => $previous ? 'corrected' : 'created'];
    }

    public static function result(int $id): ?array
    {
        $stmt = self::pdo()->prepare('SELECT r.*, p.name AS partner_name, p.slug AS partner_slug FROM training_results r JOIN training_partners p ON p.id = r.partner_id WHERE r.id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    /** Admin: revoke a result (and its plan grant). */
    public static function revokeResult(int $id, string $by): bool
    {
        $r = self::result($id);
        if (!$r || $r['status'] !== 'active') {
            return false;
        }
        self::pdo()->prepare("UPDATE training_results SET status = 'revoked', updated_at = ? WHERE id = ?")->execute([date('Y-m-d H:i:s'), $id]);
        if (!empty($r['grant_id'])) {
            PlanService::revokeGrant((int)$r['grant_id'], $by);
        }
        return true;
    }

    /** Public shape for the partner API. */
    public static function publicResult(array $r): array
    {
        return [
            'id'              => (int)$r['id'],
            'reference'       => $r['reference'],
            'score'           => (float)$r['score'],
            'passed'          => (bool)$r['passed'],
            'status'          => $r['status'],
            'course'          => $r['course'],
            'certificate_id'  => $r['certificate_id'],
            'plan_unlocked'   => $r['plan'],
            'plan_days'       => $r['plan_days'] ? (int)$r['plan_days'] : null,
            'supersedes'      => $r['supersedes_id'] ? (int)$r['supersedes_id'] : null,
            'recorded_at'     => date(DATE_ATOM, strtotime((string)$r['created_at'])),
        ];
    }

    // ── Candidate & employer views ──────────────────────────────────────────

    /** Candidate's training: applications with their latest active result. */
    public static function forCandidate(int $candidateId): array
    {
        self::ensureTables();
        $stmt = self::pdo()->prepare('SELECT a.*, p.name AS partner_name, p.slug AS partner_slug, p.website AS partner_website FROM partner_applications a JOIN training_partners p ON p.id = a.partner_id WHERE a.candidate_id = ? ORDER BY a.id DESC LIMIT 20');
        $stmt->execute([$candidateId]);
        $apps = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $res = self::pdo()->prepare("SELECT * FROM training_results WHERE application_id = ? AND status = 'active' ORDER BY id DESC LIMIT 1");
        foreach ($apps as &$a) {
            $res->execute([$a['id']]);
            $a['result'] = $res->fetch(PDO::FETCH_ASSOC) ?: null;
        }
        return $apps;
    }

    /** "Certified by" badges for employers: passed, active results. */
    public static function certifications(int $candidateId): array
    {
        self::ensureTables();
        $stmt = self::pdo()->prepare("SELECT r.course, r.score, r.certificate_id, r.certificate_url, r.completed_at, p.name AS partner_name FROM training_results r JOIN training_partners p ON p.id = r.partner_id WHERE r.candidate_id = ? AND r.status = 'active' AND r.passed = 1 ORDER BY r.completed_at DESC");
        $stmt->execute([$candidateId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ── Admin data ──────────────────────────────────────────────────────────

    public static function applications(int $limit = 50, ?int $partnerId = null): array
    {
        self::ensureTables();
        $sql = 'SELECT a.*, p.name AS partner_name FROM partner_applications a JOIN training_partners p ON p.id = a.partner_id' . ($partnerId ? ' WHERE a.partner_id = ?' : '') . ' ORDER BY a.id DESC LIMIT ' . max(1, min(500, $limit));
        $stmt = self::pdo()->prepare($sql);
        $stmt->execute($partnerId ? [$partnerId] : []);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function results(int $limit = 50): array
    {
        self::ensureTables();
        return self::pdo()->query('SELECT r.*, p.name AS partner_name FROM training_results r JOIN training_partners p ON p.id = r.partner_id ORDER BY r.id DESC LIMIT ' . max(1, min(500, $limit)))->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * A partner's students: each application with its latest active result. No names or e-mails
     * (the partner identifies students by reference). Filters: status, passed (true/false), from, to (Y-m-d, by start date).
     */
    public static function partnerStudents(int $partnerId, array $f = []): array
    {
        self::ensureTables();
        $where = ['a.partner_id = ?'];
        $args = [$partnerId];
        if (!empty($f['status']) && in_array($f['status'], self::APP_STATUSES, true)) {
            $where[] = 'a.status = ?';
            $args[] = $f['status'];
        }
        foreach (['from' => '>=', 'to' => '<='] as $k => $op) {
            if (!empty($f[$k]) && preg_match('/^\d{4}-\d{2}-\d{2}$/', (string)$f[$k])) {
                $where[] = 'a.created_at ' . $op . ' ?';
                $args[] = $f[$k] . ($k === 'to' ? ' 23:59:59' : ' 00:00:00');
            }
        }
        $stmt = self::pdo()->prepare('SELECT a.* FROM partner_applications a WHERE ' . implode(' AND ', $where) . ' ORDER BY a.id DESC LIMIT 5000');
        $stmt->execute($args);
        $apps = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $latest = [];
        $res = self::pdo()->prepare("SELECT * FROM training_results WHERE partner_id = ? AND status = 'active' ORDER BY id ASC");
        $res->execute([$partnerId]);
        foreach ($res->fetchAll(PDO::FETCH_ASSOC) as $r) {
            $latest[(int)$r['application_id']] = $r;
        }
        $out = [];
        foreach ($apps as $a) {
            $r = $latest[(int)$a['id']] ?? null;
            if (isset($f['passed']) && $f['passed'] !== null && (!$r || (bool)$r['passed'] !== (bool)$f['passed'])) {
                continue;
            }
            $out[] = [
                'reference'      => $a['reference'],
                'program'        => $a['program'],
                'status'         => $a['status'],
                'started_at'     => date(DATE_ATOM, strtotime((string)$a['created_at'])),
                'status_at'      => $a['status_at'] ? date(DATE_ATOM, strtotime((string)$a['status_at'])) : null,
                'score'          => $r ? (float)$r['score'] : null,
                'passed'         => $r ? (bool)$r['passed'] : null,
                'course'         => $r['course'] ?? null,
                'certificate_id' => $r['certificate_id'] ?? null,
                'plan_unlocked'  => $r['plan'] ?? null,
                'completed_at'   => $r && $r['completed_at'] ? date(DATE_ATOM, strtotime((string)$r['completed_at'])) : null,
            ];
        }
        return $out;
    }

    /** Totals for one partner: students by status, results, pass rate, average score, plans unlocked. */
    public static function partnerStats(int $partnerId, array $f = []): array
    {
        $students = self::partnerStudents($partnerId, ['from' => $f['from'] ?? null, 'to' => $f['to'] ?? null]);
        $byStatus = array_fill_keys(self::APP_STATUSES, 0);
        $scores = [];
        $passed = 0;
        $plans = ['pro' => 0, 'premium' => 0];
        foreach ($students as $s) {
            $byStatus[$s['status']] = ($byStatus[$s['status']] ?? 0) + 1;
            if ($s['score'] !== null) {
                $scores[] = $s['score'];
                $passed += $s['passed'] ? 1 : 0;
                if (isset($plans[$s['plan_unlocked'] ?? ''])) {
                    $plans[$s['plan_unlocked']]++;
                }
            }
        }
        $partner = self::partner($partnerId) ?? [];
        $n = count($scores);
        return [
            'students'        => count($students),
            'by_status'       => $byStatus,
            'results'         => $n,
            'passed'          => $passed,
            'failed'          => $n - $passed,
            'pass_rate'       => $n ? round($passed / $n * 100, 1) : null,
            'average_score'   => $n ? round(array_sum($scores) / $n, 1) : null,
            'highest_score'   => $n ? max($scores) : null,
            'lowest_score'    => $n ? min($scores) : null,
            'plans_unlocked'  => $plans,
            'pass_mark'       => isset($partner['pass_mark']) ? (float)$partner['pass_mark'] : null,
            'period'          => ['from' => $f['from'] ?? null, 'to' => $f['to'] ?? null],
        ];
    }

    /** Score distribution of active results in 10-point bands. */
    public static function distribution(): array
    {
        self::ensureTables();
        $bands = array_fill(0, 10, 0);
        foreach (self::pdo()->query("SELECT score FROM training_results WHERE status = 'active'")->fetchAll(PDO::FETCH_COLUMN) as $s) {
            $bands[min(9, (int)floor((float)$s / 10))]++;
        }
        return $bands;
    }

    public static function auditLog(int $limit = 50): array
    {
        self::ensureTables();
        return self::pdo()->query('SELECT l.*, p.name AS partner_name FROM api_audit_log l LEFT JOIN training_partners p ON p.id = l.partner_id ORDER BY l.id DESC LIMIT ' . max(1, min(500, $limit)))->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function audit(?int $partnerId, string $method, string $path, int $status, ?string $error, ?string $idemKey, ?string $reference, int $ms): void
    {
        try {
            self::ensureTables();
            self::pdo()->prepare('INSERT INTO api_audit_log (partner_id, method, path, status, error_code, idempotency_key, reference, ip_masked, duration_ms, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)')
                ->execute([$partnerId, $method, mb_substr($path, 0, 200), $status, $error, $idemKey ? mb_substr($idemKey, 0, 80) : null, $reference ? mb_substr($reference, 0, 24) : null, GeoService::maskIp(GeoService::clientIp()), $ms, date('Y-m-d H:i:s')]);
        } catch (Throwable $e) {
            error_log('Partner audit: ' . $e->getMessage());
        }
    }

    /** Stored answer for a repeated Idempotency-Key, or null. Same key + different body = conflict. */
    public static function idempotent(int $partnerId, string $key, string $body): ?array
    {
        self::ensureTables();
        $stmt = self::pdo()->prepare('SELECT * FROM partner_idempotency WHERE partner_id = ? AND idem_key = ?');
        $stmt->execute([$partnerId, $key]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            return null;
        }
        if (!hash_equals($row['request_hash'], hash('sha256', $body))) {
            return ['conflict' => true];
        }
        return ['status' => (int)$row['status'], 'body' => json_decode((string)$row['response'], true)];
    }

    public static function remember(int $partnerId, string $key, string $body, int $status, array $response): void
    {
        try {
            self::pdo()->prepare('INSERT INTO partner_idempotency (partner_id, idem_key, request_hash, status, response, created_at) VALUES (?, ?, ?, ?, ?, ?)')
                ->execute([$partnerId, $key, hash('sha256', $body), $status, json_encode($response), date('Y-m-d H:i:s')]);
        } catch (Throwable $e) {
            // Two identical requests at the same moment: the first one wins.
        }
    }

    /** Tier rules editor: replace one partner's rules (or the global defaults when $partnerId is null). */
    public static function saveRules(?int $partnerId, array $rows): array
    {
        self::ensureTables();
        $clean = [];
        foreach ($rows as $r) {
            if (($r['min_score'] ?? '') === '' && ($r['max_score'] ?? '') === '') {
                continue;
            }
            $min = (float)$r['min_score'];
            $max = (float)$r['max_score'];
            $plan = in_array($r['plan'] ?? '', ['pro', 'premium', 'none'], true) ? $r['plan'] : 'none';
            $days = (int)($r['duration_days'] ?? 0);
            if ($min < 0 || $max > 100 || $min > $max) {
                return ['ok' => false, 'error' => 'Each range needs 0 <= from <= to <= 100.'];
            }
            if ($plan !== 'none' && ($days < 1 || $days > 730)) {
                return ['ok' => false, 'error' => 'Plans need a duration between 1 and 730 days.'];
            }
            $clean[] = [$min, $max, $plan, $plan === 'none' ? 0 : $days, !empty($r['is_active']) ? 1 : 0];
        }
        usort($clean, static fn ($a, $b) => $a[0] <=> $b[0]);
        for ($i = 1; $i < count($clean); $i++) {
            if ($clean[$i][4] && $clean[$i - 1][4] && $clean[$i][0] <= $clean[$i - 1][1]) {
                return ['ok' => false, 'error' => 'Score ranges overlap (' . $clean[$i - 1][0] . '-' . $clean[$i - 1][1] . ' and ' . $clean[$i][0] . '-' . $clean[$i][1] . ').'];
            }
        }
        $pdo = self::pdo();
        $now = date('Y-m-d H:i:s');
        $pdo->beginTransaction();
        // Old rules are kept (switched off) so past results still point at the rule that applied.
        $pdo->prepare('UPDATE tier_rules SET is_active = 0, updated_at = ? WHERE ' . ($partnerId ? 'partner_id = ?' : 'partner_id IS NULL'))->execute($partnerId ? [$now, $partnerId] : [$now]);
        $ins = $pdo->prepare('INSERT INTO tier_rules (partner_id, min_score, max_score, plan, duration_days, is_active, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
        foreach ($clean as [$min, $max, $plan, $days, $active]) {
            if ($active) {
                $ins->execute([$partnerId, $min, $max, $plan, $days, 1, $now, $now]);
            }
        }
        $pdo->commit();
        return ['ok' => true];
    }

    /** Cron: applications with no result after the partner's window become "expired". */
    public static function dailyMaintenance(): array
    {
        self::ensureTables();
        $n = 0;
        foreach (self::partners() as $p) {
            $stmt = self::pdo()->prepare("UPDATE partner_applications SET status = 'expired', status_at = ?, updated_at = ? WHERE partner_id = ? AND status IN ('started', 'enrolled', 'in_progress') AND created_at < ?");
            $now = date('Y-m-d H:i:s');
            $stmt->execute([$now, $now, $p['id'], date('Y-m-d H:i:s', strtotime('-' . (int)$p['application_days'] . ' days'))]);
            $n += $stmt->rowCount();
        }
        return ['applications expired' => $n];
    }
}
