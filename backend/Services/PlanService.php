<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\Database;
use PDO;
use Throwable;

/**
 * Plans: the single source of truth for what an account can do (docs/53-plans-and-quotas.md).
 *
 * effectivePlan() = the highest of the active paid subscription (BillingService, Paystack) and any
 * active plan grant (training partners, admin). Nothing reads plans from the session or the URL.
 *
 * Every limit lives in `plan_features` (admin-editable, seeded with the defaults below); -1 means
 * unlimited. Usage is counted per account, feature and period in `usage_counters`.
 */
final class PlanService
{
    public const PLANS = ['free', 'pro', 'premium'];
    private const RANK = ['free' => 0, 'pro' => 1, 'premium' => 2];

    /**
     * feature => [label, period (day|week|month|flag|text), free, pro, premium, group]
     * Numbers: per period, -1 unlimited, 0 not included. Flags: 1/0. Text: shown as-is.
     */
    public const DEFAULTS = [
        'applications'          => ['Internship applications', 'month', 5, 15, -1, 'Apply'],
        'premium_listings'      => ['Premium listings (higher pay, hybrid/remote)', 'flag', 0, 0, 1, 'Discover'],
        'early_access_hours'    => ['Early access to new listings', 'text', 0, 0, 48, 'Discover'],
        'ai_search'             => ['Jenny search assist', 'day', 0, 10, -1, 'AI help'],
        'ai_documents'          => ['AI document create / edit (CV, cover letter)', 'day', 0, 5, 15, 'AI help'],
        // Jenny Coins: one coin per chat message (two with a file). When they run out, they refill
        // in full a set number of hours after the first coin of the cycle was spent.
        'jenny_coins'           => ['Jenny Coins (chat credits)', 'coins', 5, 20, 60, 'AI help'],
        'jenny_refill_hours'    => ['Jenny Coins refill after (hours)', 'hours', 24, 12, 5, 'AI help'],
        'assistant_chats'       => ['AI Assistant chats with file attachments', 'day', 0, 5, 15, 'AI help'],
        'mock_interviews'       => ['AI mock interviews with feedback', 'week', 0, 1, -1, 'AI help'],
        'recruiter_visibility'  => ['Recruiter visibility', 'text', 'Standard', 'Boosted', 'Priority + Featured badge', 'Visibility'],
        'career_insights'       => ['Career insights', 'text', 'Profile strength', 'Match %, skill gaps', 'Salary benchmarks, market demand, readiness', 'Insights'],
    ];

    /** Global settings (admin-editable): key => default. */
    public const SETTINGS = [
        'premium_stipend_min' => 300000,   // Premium listing: stipend at or above this (NGN/month) ...
        'premium_needs_flex'  => 1,        // ... and hybrid or remote work
    ];

    private static bool $ready = false;
    private static array $cache = [];

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
            $pdo->exec('CREATE TABLE IF NOT EXISTS plan_features (
                feature_key VARCHAR(40) NOT NULL, plan VARCHAR(16) NOT NULL, value VARCHAR(120) NOT NULL, updated_at DATETIME NOT NULL,
                PRIMARY KEY (feature_key, plan)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');
            $pdo->exec('CREATE TABLE IF NOT EXISTS plan_settings (
                setting_key VARCHAR(60) NOT NULL PRIMARY KEY, setting_value VARCHAR(255) NOT NULL, updated_at DATETIME NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');
            $pdo->exec('CREATE TABLE IF NOT EXISTS plan_grants (
                id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY, account_type VARCHAR(16) NOT NULL, account_id BIGINT UNSIGNED NOT NULL,
                plan VARCHAR(16) NOT NULL, source VARCHAR(40) NOT NULL, source_ref VARCHAR(80) NULL, note VARCHAR(255) NULL,
                starts_at DATETIME NOT NULL, expires_at DATETIME NOT NULL, status VARCHAR(16) NOT NULL DEFAULT \'active\',
                created_by VARCHAR(80) NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL,
                INDEX idx_pg_account (account_type, account_id, status)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');
            $pdo->exec('CREATE TABLE IF NOT EXISTS usage_counters (
                account_type VARCHAR(16) NOT NULL, account_id BIGINT UNSIGNED NOT NULL, feature_key VARCHAR(40) NOT NULL,
                period_key VARCHAR(16) NOT NULL, used INT UNSIGNED NOT NULL DEFAULT 0, updated_at DATETIME NOT NULL,
                PRIMARY KEY (account_type, account_id, feature_key, period_key)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');
            $pdo->exec('CREATE TABLE IF NOT EXISTS plan_reminders (account_type VARCHAR(16) NOT NULL, account_id BIGINT UNSIGNED NOT NULL, period_end DATETIME NOT NULL, sent_at DATETIME NOT NULL, PRIMARY KEY (account_type, account_id, period_end)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');
            $pdo->exec('CREATE TABLE IF NOT EXISTS jenny_wallets (account_type VARCHAR(16) NOT NULL, account_id BIGINT UNSIGNED NOT NULL, spent INT UNSIGNED NOT NULL DEFAULT 0, cycle_started_at DATETIME NULL, updated_at DATETIME NOT NULL, PRIMARY KEY (account_type, account_id)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');
        } else {
            $pdo->exec('CREATE TABLE IF NOT EXISTS plan_features (feature_key TEXT NOT NULL, plan TEXT NOT NULL, value TEXT NOT NULL, updated_at TEXT NOT NULL, PRIMARY KEY (feature_key, plan))');
            $pdo->exec('CREATE TABLE IF NOT EXISTS plan_settings (setting_key TEXT PRIMARY KEY, setting_value TEXT NOT NULL, updated_at TEXT NOT NULL)');
            $pdo->exec('CREATE TABLE IF NOT EXISTS plan_grants (id INTEGER PRIMARY KEY AUTOINCREMENT, account_type TEXT NOT NULL, account_id INTEGER NOT NULL,
                plan TEXT NOT NULL, source TEXT NOT NULL, source_ref TEXT, note TEXT, starts_at TEXT NOT NULL, expires_at TEXT NOT NULL,
                status TEXT NOT NULL DEFAULT \'active\', created_by TEXT, created_at TEXT NOT NULL, updated_at TEXT NOT NULL)');
            $pdo->exec('CREATE INDEX IF NOT EXISTS idx_pg_account ON plan_grants (account_type, account_id, status)');
            $pdo->exec('CREATE TABLE IF NOT EXISTS usage_counters (account_type TEXT NOT NULL, account_id INTEGER NOT NULL, feature_key TEXT NOT NULL,
                period_key TEXT NOT NULL, used INTEGER NOT NULL DEFAULT 0, updated_at TEXT NOT NULL, PRIMARY KEY (account_type, account_id, feature_key, period_key))');
            $pdo->exec('CREATE TABLE IF NOT EXISTS plan_reminders (account_type TEXT NOT NULL, account_id INTEGER NOT NULL, period_end TEXT NOT NULL, sent_at TEXT NOT NULL, PRIMARY KEY (account_type, account_id, period_end))');
            $pdo->exec('CREATE TABLE IF NOT EXISTS jenny_wallets (account_type TEXT NOT NULL, account_id INTEGER NOT NULL, spent INTEGER NOT NULL DEFAULT 0, cycle_started_at TEXT, updated_at TEXT NOT NULL, PRIMARY KEY (account_type, account_id))');
        }
        self::$ready = true;
        // Seed missing defaults (never overwrites admin edits).
        $now = date('Y-m-d H:i:s');
        $existing = [];
        foreach ($pdo->query('SELECT feature_key, plan FROM plan_features')->fetchAll(PDO::FETCH_ASSOC) as $r) {
            $existing[$r['feature_key'] . '|' . $r['plan']] = true;
        }
        $ins = $pdo->prepare('INSERT INTO plan_features (feature_key, plan, value, updated_at) VALUES (?, ?, ?, ?)');
        foreach (self::DEFAULTS as $key => $d) {
            foreach (['free' => $d[2], 'pro' => $d[3], 'premium' => $d[4]] as $plan => $value) {
                if (!isset($existing[$key . '|' . $plan])) {
                    $ins->execute([$key, $plan, (string)$value, $now]);
                }
            }
        }
        $have = $pdo->query('SELECT setting_key FROM plan_settings')->fetchAll(PDO::FETCH_COLUMN);
        $insS = $pdo->prepare('INSERT INTO plan_settings (setting_key, setting_value, updated_at) VALUES (?, ?, ?)');
        foreach (self::SETTINGS as $k => $v) {
            if (!in_array($k, $have, true)) {
                $insS->execute([$k, (string)$v, $now]);
            }
        }
    }

    /** Forget per-request answers (after a payment, in tests). */
    public static function flush(): void
    {
        self::$cache = [];
    }

    // ── Effective plan ──────────────────────────────────────────────────────

    public static function rank(string $plan): int
    {
        return self::RANK[$plan] ?? 0;
    }

    /** Highest of the active paid plan and active grants. */
    public static function effectivePlan(string $type, int $id): string
    {
        $key = 'plan:' . $type . ':' . $id;
        if (isset(self::$cache[$key])) {
            return self::$cache[$key];
        }
        $best = 'free';
        $paid = BillingService::subscription($type, $id);
        if ($paid && $paid['status'] === 'active' && self::rank((string)$paid['plan']) > self::rank($best)) {
            $best = (string)$paid['plan'];
        }
        foreach (self::activeGrants($type, $id) as $g) {
            if (self::rank($g['plan']) > self::rank($best)) {
                $best = $g['plan'];
            }
        }
        return self::$cache[$key] = $best;
    }

    /** Effective plan of the signed-in candidate (demo account #1 until accounts move to MySQL). */
    public static function candidatePlan(): string
    {
        return self::effectivePlan('candidate', (int)($_SESSION['candidate_id'] ?? 1));
    }

    /** Where the current plan comes from: ['plan', 'source' => paid|grant|free, 'until', 'label']. */
    public static function planSource(string $type, int $id): array
    {
        $plan = self::effectivePlan($type, $id);
        if ($plan === 'free') {
            return ['plan' => 'free', 'source' => 'free', 'until' => null, 'label' => 'Free plan'];
        }
        $paid = BillingService::subscription($type, $id);
        if ($paid && $paid['status'] === 'active' && $paid['plan'] === $plan) {
            return ['plan' => $plan, 'source' => 'paid', 'until' => $paid['renews_at'], 'label' => ucfirst($plan) . ' (paid)'];
        }
        foreach (self::activeGrants($type, $id) as $g) {
            if ($g['plan'] === $plan) {
                return ['plan' => $plan, 'source' => 'grant', 'until' => $g['expires_at'], 'label' => ucfirst($plan) . ' (' . self::sourceLabel($g) . ')', 'grant' => $g];
            }
        }
        return ['plan' => $plan, 'source' => 'paid', 'until' => null, 'label' => ucfirst($plan)];
    }

    public static function sourceLabel(array $grant): string
    {
        $note = trim((string)($grant['note'] ?? ''));
        return match ((string)$grant['source']) {
            'training'  => $note !== '' ? $note : 'granted by a training partner',
            'admin'     => 'granted by InternConnect',
            default     => $note !== '' ? $note : 'granted',
        };
    }

    // ── Grants ──────────────────────────────────────────────────────────────

    /** Active grants now (expired ones are reported as such and closed by the cron). */
    public static function activeGrants(string $type, int $id): array
    {
        self::ensureTables();
        try {
            $stmt = self::pdo()->prepare("SELECT * FROM plan_grants WHERE account_type = ? AND account_id = ? AND status = 'active' AND starts_at <= ? AND expires_at > ? ORDER BY expires_at DESC");
            $now = date('Y-m-d H:i:s');
            $stmt->execute([$type, $id, $now, $now]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Throwable $e) {
            return [];
        }
    }

    public static function grants(string $type, int $id): array
    {
        self::ensureTables();
        $stmt = self::pdo()->prepare('SELECT * FROM plan_grants WHERE account_type = ? AND account_id = ? ORDER BY id DESC');
        $stmt->execute([$type, $id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Grant a plan for $days (training result, admin, promotion). Policy (docs/53):
     * never downgrade; same plan as an active paid/granted plan stacks after it; a higher plan starts now;
     * a lower plan starts when the higher one ends. Returns the grant row.
     */
    public static function grant(string $type, int $id, string $plan, int $days, string $source, ?string $sourceRef = null, ?string $note = null, ?string $by = null): array
    {
        self::ensureTables();
        if (!in_array($plan, ['pro', 'premium'], true) || $days < 1) {
            throw new \InvalidArgumentException('Grants need plan pro or premium and at least 1 day.');
        }
        $now = time();
        $start = $now;
        // Latest end of anything at the same or higher level decides when this grant can start.
        $paid = BillingService::subscription($type, $id);
        if ($paid && $paid['status'] === 'active') {
            $paidEnd = strtotime((string)$paid['renews_at']);
            if (self::rank($paid['plan']) >= self::rank($plan) && $paidEnd > $start) {
                $start = $paidEnd;
            }
        }
        foreach (self::grants($type, $id) as $g) {
            if ($g['status'] !== 'active') {
                continue;
            }
            $gEnd = strtotime((string)$g['expires_at']);
            if (self::rank($g['plan']) >= self::rank($plan) && $gEnd > $start && strtotime((string)$g['starts_at']) <= $start + 1) {
                $start = $gEnd;
            }
        }
        $starts = date('Y-m-d H:i:s', $start);
        $expires = date('Y-m-d H:i:s', strtotime('+' . $days . ' days', $start));
        $ts = date('Y-m-d H:i:s', $now);
        $pdo = self::pdo();
        $pdo->prepare('INSERT INTO plan_grants (account_type, account_id, plan, source, source_ref, note, starts_at, expires_at, status, created_by, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)')
            ->execute([$type, $id, $plan, $source, $sourceRef, $note, $starts, $expires, 'active', $by, $ts, $ts]);
        unset(self::$cache['plan:' . $type . ':' . $id]);
        $stmt = $pdo->prepare('SELECT * FROM plan_grants WHERE id = ?');
        $stmt->execute([(int)$pdo->lastInsertId()]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /** Latest grants across all accounts (admin list). */
    public static function recentGrants(int $limit = 50): array
    {
        self::ensureTables();
        return self::pdo()->query('SELECT * FROM plan_grants ORDER BY id DESC LIMIT ' . max(1, min(500, $limit)))->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function revokeGrant(int $grantId, ?string $by = null): bool
    {
        self::ensureTables();
        $stmt = self::pdo()->prepare("UPDATE plan_grants SET status = 'revoked', created_by = COALESCE(created_by, ?), updated_at = ? WHERE id = ? AND status = 'active'");
        $stmt->execute([$by, date('Y-m-d H:i:s'), $grantId]);
        self::$cache = [];
        return $stmt->rowCount() > 0;
    }

    /**
     * Turn a revoked or expired grant back on. Revoked with time left: the same grant continues to its
     * original end date. Otherwise a new grant of the same plan and length starts (normal grant rules).
     * Returns the active grant row, or null when the grant does not exist or is already active.
     */
    public static function reactivateGrant(int $grantId, ?string $by = null): ?array
    {
        self::ensureTables();
        $stmt = self::pdo()->prepare('SELECT * FROM plan_grants WHERE id = ?');
        $stmt->execute([$grantId]);
        $g = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$g) {
            return null;
        }
        $expired = strtotime((string)$g['expires_at']) <= time();
        if ($g['status'] === 'active' && !$expired) {
            return null;
        }
        self::$cache = [];
        if ($g['status'] === 'revoked' && !$expired) {
            self::pdo()->prepare("UPDATE plan_grants SET status = 'active', updated_at = ? WHERE id = ?")->execute([date('Y-m-d H:i:s'), $grantId]);
            $g['status'] = 'active';
            return $g;
        }
        $days = max(1, (int)round((strtotime((string)$g['expires_at']) - strtotime((string)$g['starts_at'])) / 86400));
        return self::grant((string)$g['account_type'], (int)$g['account_id'], (string)$g['plan'], $days, 'admin', 'reactivated:' . $grantId, $g['note'] ?: 'Reactivated', $by);
    }

    /** Cron: close grants whose expiry has passed. Returns how many were expired. */
    public static function expireGrants(): int
    {
        self::ensureTables();
        $stmt = self::pdo()->prepare("UPDATE plan_grants SET status = 'expired', updated_at = ? WHERE status = 'active' AND expires_at <= ?");
        $now = date('Y-m-d H:i:s');
        $stmt->execute([$now, $now]);
        self::$cache = [];
        return $stmt->rowCount();
    }

    /**
     * Cron (daily): e-mail paid accounts 3 days before their plan ends (plans never renew automatically).
     * One reminder per account and period. Returns how many were queued.
     */
    public static function sendExpiryReminders(int $daysBefore = 3): int
    {
        self::ensureTables();
        BillingService::ensureTables();
        $pdo = self::pdo();
        if (self::isMysql($pdo)) {
            $pdo->exec('CREATE TABLE IF NOT EXISTS plan_reminders (account_type VARCHAR(16) NOT NULL, account_id BIGINT UNSIGNED NOT NULL, period_end DATETIME NOT NULL, sent_at DATETIME NOT NULL, PRIMARY KEY (account_type, account_id, period_end)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');
            $pdo->exec('CREATE TABLE IF NOT EXISTS jenny_wallets (account_type VARCHAR(16) NOT NULL, account_id BIGINT UNSIGNED NOT NULL, spent INT UNSIGNED NOT NULL DEFAULT 0, cycle_started_at DATETIME NULL, updated_at DATETIME NOT NULL, PRIMARY KEY (account_type, account_id)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');
        } else {
            $pdo->exec('CREATE TABLE IF NOT EXISTS plan_reminders (account_type TEXT NOT NULL, account_id INTEGER NOT NULL, period_end TEXT NOT NULL, sent_at TEXT NOT NULL, PRIMARY KEY (account_type, account_id, period_end))');
            $pdo->exec('CREATE TABLE IF NOT EXISTS jenny_wallets (account_type TEXT NOT NULL, account_id INTEGER NOT NULL, spent INTEGER NOT NULL DEFAULT 0, cycle_started_at TEXT, updated_at TEXT NOT NULL, PRIMARY KEY (account_type, account_id))');
        }
        $now = date('Y-m-d H:i:s');
        $stmt = $pdo->prepare("SELECT s.* FROM billing_subscriptions s WHERE s.status = 'active' AND s.renews_at > ? AND s.renews_at <= ?
            AND NOT EXISTS (SELECT 1 FROM plan_reminders r WHERE r.account_type = s.account_type AND r.account_id = s.account_id AND r.period_end = s.renews_at)");
        $stmt->execute([$now, date('Y-m-d H:i:s', strtotime('+' . $daysBefore . ' days'))]);
        $email = $pdo->prepare("SELECT email FROM billing_transactions WHERE account_type = ? AND account_id = ? AND status = 'success' AND email IS NOT NULL ORDER BY id DESC LIMIT 1");
        $mark = $pdo->prepare('INSERT INTO plan_reminders (account_type, account_id, period_end, sent_at) VALUES (?, ?, ?, ?)');
        $n = 0;
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $s) {
            $email->execute([$s['account_type'], $s['account_id']]);
            $to = (string)$email->fetchColumn();
            $mark->execute([$s['account_type'], $s['account_id'], $s['renews_at'], $now]);
            if (!filter_var($to, FILTER_VALIDATE_EMAIL)) {
                continue;
            }
            $plan = ucfirst((string)$s['plan']);
            $billing = $s['account_type'] === 'employer' ? '/employer/billing' : '/candidate/billing';
            MailService::queue($to, 'Your ' . $plan . ' plan ends on ' . date('j M', strtotime($s['renews_at'])), MailService::render('message', [
                'preheader'  => 'Renew to keep your ' . $plan . ' features. Nothing renews automatically.',
                'title'      => 'Your ' . $plan . ' plan ends soon',
                'paragraphs' => [
                    'Your InternConnect ' . $plan . ' plan ends on ' . date('l, j F Y', strtotime($s['renews_at'])) . '. Plans never renew on their own, so you will not be charged.',
                    'Renew if you want to keep your ' . $plan . ' limits, Premium listings and priority visibility. Otherwise your account moves to the Free plan and keeps all your applications.',
                ],
                'cta'        => ['label' => 'Renew my plan', 'url' => MailService::link('/payments?plan=' . $s['plan'])],
                'note'       => 'See your receipts and plan history in Billing: ' . MailService::link($billing),
            ]), 'plan_reminder');
            $n++;
        }
        return $n;
    }

    // ── Jenny Coins ─────────────────────────────────────────────────────────

    /**
     * Coin wallet: ['plan', 'allowance', 'balance', 'spent', 'unlimited', 'refill_hours', 'refills_at' (Y-m-d H:i:s|null),
     * 'refills_in' seconds|null]. Visitors use the Free allowance, counted per IP (type 'visitor').
     */
    public static function coins(string $type, int $id): array
    {
        self::ensureTables();
        $plan = $type === 'visitor' ? 'free' : self::effectivePlan($type, $id);
        $allowance = self::limit('jenny_coins', $plan);
        $hours = max(1, self::limit('jenny_refill_hours', $plan));
        $stmt = self::pdo()->prepare('SELECT spent, cycle_started_at FROM jenny_wallets WHERE account_type = ? AND account_id = ?');
        $stmt->execute([$type, $id]);
        $w = $stmt->fetch(PDO::FETCH_ASSOC) ?: ['spent' => 0, 'cycle_started_at' => null];
        $spent = (int)$w['spent'];
        $refillsAt = null;
        if ($w['cycle_started_at']) {
            $refillTs = strtotime((string)$w['cycle_started_at']) + $hours * 3600;
            if ($refillTs <= time()) {
                // The refill time has passed: the wallet is full again.
                self::pdo()->prepare('UPDATE jenny_wallets SET spent = 0, cycle_started_at = NULL, updated_at = ? WHERE account_type = ? AND account_id = ?')
                    ->execute([date('Y-m-d H:i:s'), $type, $id]);
                $spent = 0;
            } else {
                $refillsAt = date('Y-m-d H:i:s', $refillTs);
            }
        }
        $unlimited = $allowance < 0;
        return [
            'plan'         => $plan,
            'allowance'    => $allowance,
            'balance'      => $unlimited ? null : max(0, $allowance - $spent),
            'spent'        => $spent,
            'unlimited'    => $unlimited,
            'refill_hours' => $hours,
            'refills_at'   => $refillsAt,
            'refills_in'   => $refillsAt ? max(0, strtotime($refillsAt) - time()) : null,
        ];
    }

    /** "3h 12m" / "45m" / "less than a minute". */
    public static function duration(int $seconds): string
    {
        $h = intdiv($seconds, 3600);
        $m = intdiv($seconds % 3600, 60);
        if ($h === 0 && $m === 0) {
            return 'less than a minute';
        }
        return trim(($h ? $h . 'h ' : '') . ($m ? $m . 'm' : ''));
    }

    /** Can this account spend $cost coins now? ['ok', 'message', 'coins']. Nothing is spent. */
    public static function canSpendCoins(string $type, int $id, int $cost = 1): array
    {
        $c = self::coins($type, $id);
        if ($c['unlimited'] || $c['balance'] >= $cost) {
            return ['ok' => true, 'message' => '', 'coins' => $c];
        }
        $wait = $c['refills_in'] !== null ? ' They refill in ' . self::duration((int)$c['refills_in']) . '.' : '';
        $more = $c['plan'] === 'premium' ? '' : ' Upgrade for more coins and faster refills.';
        $msg = $c['balance'] > 0
            ? 'This needs ' . $cost . ' Jenny Coins and you have ' . $c['balance'] . '.' . $wait
            : 'You have used all your Jenny Coins.' . $wait . $more;
        return ['ok' => false, 'message' => $msg, 'coins' => $c, 'upgrade' => $c['plan'] !== 'premium'];
    }

    /** Spend coins after a successful answer. Starts the refill clock on the first coin of a cycle. */
    public static function spendCoins(string $type, int $id, int $cost = 1): array
    {
        $c = self::coins($type, $id);
        if ($c['unlimited']) {
            return $c;
        }
        $now = date('Y-m-d H:i:s');
        $pdo = self::pdo();
        $sql = self::isMysql($pdo)
            ? 'INSERT INTO jenny_wallets (account_type, account_id, spent, cycle_started_at, updated_at) VALUES (?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE spent = spent + VALUES(spent), cycle_started_at = COALESCE(cycle_started_at, VALUES(cycle_started_at)), updated_at = VALUES(updated_at)'
            : 'INSERT INTO jenny_wallets (account_type, account_id, spent, cycle_started_at, updated_at) VALUES (?, ?, ?, ?, ?) ON CONFLICT(account_type, account_id) DO UPDATE SET spent = spent + excluded.spent, cycle_started_at = COALESCE(cycle_started_at, excluded.cycle_started_at), updated_at = excluded.updated_at';
        $pdo->prepare($sql)->execute([$type, $id, $cost, $now, $now]);
        return self::coins($type, $id);
    }

    /** Admin: top up (refill) an account's coins now. */
    public static function refillCoins(string $type, int $id): void
    {
        self::ensureTables();
        self::pdo()->prepare('UPDATE jenny_wallets SET spent = 0, cycle_started_at = NULL, updated_at = ? WHERE account_type = ? AND account_id = ?')
            ->execute([date('Y-m-d H:i:s'), $type, $id]);
    }

    // ── Features & quotas ───────────────────────────────────────────────────

    /** @return array<string, array<string, string>> feature => [plan => value] */
    public static function matrix(): array
    {
        self::ensureTables();
        $out = [];
        foreach (self::pdo()->query('SELECT feature_key, plan, value FROM plan_features')->fetchAll(PDO::FETCH_ASSOC) as $r) {
            $out[$r['feature_key']][$r['plan']] = $r['value'];
        }
        return $out;
    }

    public static function value(string $feature, string $plan): string
    {
        return (string)(self::matrix()[$feature][$plan] ?? (self::DEFAULTS[$feature][2 + array_search($plan, self::PLANS, true)] ?? '0'));
    }

    /** Numeric limit for a feature (-1 = unlimited, 0 = not included). */
    public static function limit(string $feature, string $plan): int
    {
        return (int)self::value($feature, $plan);
    }

    public static function setting(string $key): string
    {
        self::ensureTables();
        $stmt = self::pdo()->prepare('SELECT setting_value FROM plan_settings WHERE setting_key = ?');
        $stmt->execute([$key]);
        $v = $stmt->fetchColumn();
        return $v === false ? (string)(self::SETTINGS[$key] ?? '') : (string)$v;
    }

    /** Admin edits (validated; text features keep their text, numeric ones must be integers >= -1). */
    public static function saveMatrix(array $values, array $settings): void
    {
        self::ensureTables();
        $pdo = self::pdo();
        $now = date('Y-m-d H:i:s');
        $up = $pdo->prepare('UPDATE plan_features SET value = ?, updated_at = ? WHERE feature_key = ? AND plan = ?');
        foreach (self::DEFAULTS as $key => $d) {
            foreach (self::PLANS as $plan) {
                if (!isset($values[$key][$plan])) {
                    continue;
                }
                $raw = trim((string)$values[$key][$plan]);
                if ($d[1] === 'text') {
                    $v = mb_substr(strip_tags($raw), 0, 120);
                } elseif ($d[1] === 'flag') {
                    $v = in_array(strtolower($raw), ['1', 'yes', 'true', 'on'], true) ? '1' : '0';
                } else {
                    if ($d[1] === 'hours' && (!preg_match('/^\d+$/', $raw) || (int)$raw < 1 || (int)$raw > 168)) {
                        throw new \InvalidArgumentException($d[0] . ' (' . $plan . ') must be between 1 and 168 hours.');
                    }
                    if (!preg_match('/^-?\d+$/', $raw) || (int)$raw < -1) {
                        throw new \InvalidArgumentException($d[0] . ' (' . $plan . ') must be a whole number, or -1 for unlimited.');
                    }
                    $v = (string)(int)$raw;
                }
                $up->execute([$v, $now, $key, $plan]);
            }
        }
        $upS = $pdo->prepare('UPDATE plan_settings SET setting_value = ?, updated_at = ? WHERE setting_key = ?');
        foreach (self::SETTINGS as $k => $default) {
            if (isset($settings[$k])) {
                $upS->execute([(string)max(0, (int)$settings[$k]), $now, $k]);
            }
        }
        self::$cache = [];
    }

    private static function periodKey(string $period): string
    {
        return match ($period) {
            'day'   => date('Y-m-d'),
            'week'  => date('o-\WW'),
            'month' => date('Y-m'),
            default => 'all',
        };
    }

    /** @return array{limit:int, used:int, remaining:int|null, unlimited:bool, period:string} */
    public static function usage(string $type, int $id, string $feature): array
    {
        self::ensureTables();
        $period = self::DEFAULTS[$feature][1] ?? 'day';
        $limit = self::limit($feature, self::effectivePlan($type, $id));
        $stmt = self::pdo()->prepare('SELECT used FROM usage_counters WHERE account_type = ? AND account_id = ? AND feature_key = ? AND period_key = ?');
        $stmt->execute([$type, $id, $feature, self::periodKey($period)]);
        $used = (int)$stmt->fetchColumn();
        return ['limit' => $limit, 'used' => $used, 'remaining' => $limit < 0 ? null : max(0, $limit - $used), 'unlimited' => $limit < 0, 'period' => $period];
    }

    /**
     * Use one unit of a feature. Returns ['ok' => bool, 'message' => string, 'usage' => usage()].
     * Not included in the plan or used up: ok = false with an upgrade message.
     */
    public static function consume(string $type, int $id, string $feature): array
    {
        $check = self::check($type, $id, $feature);
        if (!$check['ok']) {
            return $check;
        }
        $u = $check['usage'];
        $pdo = self::pdo();
        $key = self::periodKey($u['period']);
        $now = date('Y-m-d H:i:s');
        $sql = self::isMysql($pdo)
            ? 'INSERT INTO usage_counters (account_type, account_id, feature_key, period_key, used, updated_at) VALUES (?, ?, ?, ?, 1, ?) ON DUPLICATE KEY UPDATE used = used + 1, updated_at = VALUES(updated_at)'
            : 'INSERT INTO usage_counters (account_type, account_id, feature_key, period_key, used, updated_at) VALUES (?, ?, ?, ?, 1, ?) ON CONFLICT(account_type, account_id, feature_key, period_key) DO UPDATE SET used = used + 1, updated_at = excluded.updated_at';
        $pdo->prepare($sql)->execute([$type, $id, $feature, $key, $now]);
        $u['used']++;
        $u['remaining'] = $u['unlimited'] ? null : max(0, $u['limit'] - $u['used']);
        return ['ok' => true, 'usage' => $u, 'upgrade' => false, 'message' => ''];
    }

    /** Same answer as consume() without using anything (check first, count after the work succeeded). */
    public static function check(string $type, int $id, string $feature): array
    {
        $u = self::usage($type, $id, $feature);
        $plan = self::effectivePlan($type, $id);
        if ($u['limit'] === 0) {
            return ['ok' => false, 'usage' => $u, 'upgrade' => true,
                'message' => self::DEFAULTS[$feature][0] . ' is not included in the ' . ucfirst($plan) . ' plan. Upgrade to unlock it.'];
        }
        if (!$u['unlimited'] && $u['used'] >= $u['limit']) {
            $per = ['day' => 'today', 'week' => 'this week', 'month' => 'this month'][$u['period']] ?? '';
            return ['ok' => false, 'usage' => $u, 'upgrade' => $plan !== 'premium',
                'message' => 'You have used all ' . $u['limit'] . ' ' . strtolower(self::DEFAULTS[$feature][0]) . ' ' . $per . ' on the ' . ucfirst($plan) . ' plan.'];
        }
        return ['ok' => true, 'usage' => $u, 'upgrade' => false, 'message' => ''];
    }

    /** Candidate shortcut used by controllers: consume() for the signed-in candidate. */
    public static function useCandidate(string $feature, bool $count = true): array
    {
        $id = (int)($_SESSION['candidate_id'] ?? 1);
        return $count ? self::consume('candidate', $id, $feature) : self::check('candidate', $id, $feature);
    }

    /** JSON 402-style refusal for AI endpoints (HTTP 429 when used up, 403 when not included). */
    public static function denyJson(array $result): void
    {
        http_response_code($result['usage']['limit'] === 0 ? 403 : 429);
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'error' => $result['message'], 'code' => 'plan_limit', 'upgrade' => $result['upgrade'],
            'upgrade_url' => url('/pricing'), 'usage' => $result['usage']]);
        exit;
    }

    /** Plan of whoever is browsing a public page: signed-in candidate's plan, otherwise Free. */
    public static function viewerPlan(): string
    {
        return !empty($_SESSION['is_logged_in']) ? self::candidatePlan() : 'free';
    }

    /**
     * Mark listings (backend/Data/internships.php shape) as Premium and locked for this viewer.
     * Locked listings keep title/company visible but hide the pay and the apply link.
     */
    public static function annotateListings(array $listings, ?string $viewerPlan = null): array
    {
        $viewerPlan ??= self::viewerPlan();
        $canSee = (int)self::value('premium_listings', $viewerPlan) === 1;
        foreach ($listings as &$l) {
            $l['premium'] = self::isPremiumListing((int)($l['stipendAmount'] ?? 0), (string)($l['tag'] ?? ''));
            $l['locked'] = $l['premium'] && !$canSee;
            if ($l['locked']) {
                // Never send the hidden pay to the browser (page source, API, Jenny).
                $l['stipendAmount'] = 0;
                $l['stipendText'] = 'Premium listing';
            }
        }
        return $listings;
    }

    /** Is a listing id from the shared data a Premium listing the viewer cannot open? */
    public static function listingLocked(string $id): bool
    {
        foreach (self::annotateListings(require dirname(__DIR__) . '/Data/internships.php') as $l) {
            if ($l['id'] === $id) {
                return $l['locked'];
            }
        }
        return false;
    }

    /** Is this listing a Premium listing (higher pay + flexible work)? */
    public static function isPremiumListing(int $stipend, string $workMode): bool
    {
        $min = (int)self::setting('premium_stipend_min');
        $flex = in_array(strtoupper($workMode), ['REMOTE', 'HYBRID'], true);
        return $stipend >= $min && ((int)self::setting('premium_needs_flex') === 0 || $flex);
    }
}
