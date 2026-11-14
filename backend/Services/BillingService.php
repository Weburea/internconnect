<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\Database;
use PDO;
use Throwable;

/**
 * Billing history and subscriptions for candidates and employers (Paystack).
 *
 * Every checkout is recorded when it starts (pending) and updated when Paystack confirms it,
 * through the browser callback or the signed webhook, whichever arrives first. Updates are
 * keyed by the unique Paystack reference, so the two paths never double-count a payment.
 *
 * Tables `billing_transactions` and `billing_subscriptions` are created on first use (SQLite
 * locally, MySQL in production) and map onto `payment_transactions` / `subscriptions` in the
 * MySQL phase. Spec: docs/08-payment-system.md, docs/paystack-integration-architecture.md
 */
class BillingService
{
    private static bool $tablesChecked = false;

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
        if (self::$tablesChecked) {
            return;
        }
        try {
            $pdo = self::pdo();
            if (!self::isMysql($pdo)) {
                $pdo->exec('CREATE TABLE IF NOT EXISTS billing_transactions (
                    id INTEGER PRIMARY KEY AUTOINCREMENT, account_type TEXT NOT NULL, account_id INTEGER NOT NULL,
                    reference TEXT NOT NULL UNIQUE, plan TEXT NOT NULL, cycle TEXT NOT NULL, amount_kobo INTEGER NOT NULL,
                    currency TEXT NOT NULL DEFAULT \'NGN\', status TEXT NOT NULL DEFAULT \'pending\', channel TEXT,
                    card_brand TEXT, card_last4 TEXT, gateway_response TEXT, email TEXT,
                    paid_at TEXT, created_at TEXT NOT NULL, updated_at TEXT NOT NULL)');
                $pdo->exec('CREATE INDEX IF NOT EXISTS idx_billing_tx_account ON billing_transactions (account_type, account_id, created_at)');
                $pdo->exec('CREATE TABLE IF NOT EXISTS billing_subscriptions (
                    account_type TEXT NOT NULL, account_id INTEGER NOT NULL, plan TEXT NOT NULL, cycle TEXT NOT NULL,
                    status TEXT NOT NULL, amount_kobo INTEGER NOT NULL, started_at TEXT NOT NULL, renews_at TEXT NOT NULL,
                    last_reference TEXT, updated_at TEXT NOT NULL, PRIMARY KEY (account_type, account_id))');
            } else {
                $pdo->exec('CREATE TABLE IF NOT EXISTS billing_transactions (
                    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY, account_type VARCHAR(16) NOT NULL, account_id BIGINT UNSIGNED NOT NULL,
                    reference VARCHAR(100) NOT NULL UNIQUE, plan VARCHAR(32) NOT NULL, cycle VARCHAR(16) NOT NULL, amount_kobo INT UNSIGNED NOT NULL,
                    currency CHAR(3) NOT NULL DEFAULT \'NGN\', status VARCHAR(16) NOT NULL DEFAULT \'pending\', channel VARCHAR(32) NULL,
                    card_brand VARCHAR(32) NULL, card_last4 VARCHAR(4) NULL, gateway_response VARCHAR(255) NULL, email VARCHAR(190) NULL,
                    paid_at DATETIME NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL,
                    INDEX idx_billing_tx_account (account_type, account_id, created_at)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');
                $pdo->exec('CREATE TABLE IF NOT EXISTS billing_subscriptions (
                    account_type VARCHAR(16) NOT NULL, account_id BIGINT UNSIGNED NOT NULL, plan VARCHAR(32) NOT NULL, cycle VARCHAR(16) NOT NULL,
                    status VARCHAR(16) NOT NULL, amount_kobo INT UNSIGNED NOT NULL, started_at DATETIME NOT NULL, renews_at DATETIME NOT NULL,
                    last_reference VARCHAR(100) NULL, updated_at DATETIME NOT NULL, PRIMARY KEY (account_type, account_id)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');
            }
            // Additive columns (2026-11-12): card expiry and issuing bank for the receipt.
            $cols = self::isMysql($pdo)
                ? $pdo->query('SHOW COLUMNS FROM billing_transactions')->fetchAll(PDO::FETCH_COLUMN)
                : array_column($pdo->query('PRAGMA table_info(billing_transactions)')->fetchAll(PDO::FETCH_ASSOC), 'name');
            foreach (['card_exp' => 'VARCHAR(7) NULL', 'card_bank' => 'VARCHAR(80) NULL'] as $col => $def) {
                if (!in_array($col, $cols, true)) {
                    $pdo->exec('ALTER TABLE billing_transactions ADD COLUMN ' . $col . ' ' . (self::isMysql($pdo) ? $def : 'TEXT'));
                }
            }
            self::$tablesChecked = true;
        } catch (Throwable $e) {
            error_log('BillingService tables: ' . $e->getMessage());
        }
    }

    /** Who is paying: candidate or employer (demo accounts are #1 until accounts move to MySQL). */
    public static function account(?string $type = null): array
    {
        $type = in_array($type, ['candidate', 'employer'], true) ? $type : 'candidate';
        $id = $type === 'employer' ? (int)($_SESSION['employer_id'] ?? 1) : (int)($_SESSION['candidate_id'] ?? 1);
        return ['type' => $type, 'id' => $id];
    }

    public static function recordPending(array $account, string $reference, string $plan, string $cycle, int $amountKobo, string $email): void
    {
        self::ensureTables();
        $now = date('Y-m-d H:i:s');
        try {
            self::pdo()->prepare('INSERT INTO billing_transactions
                (account_type, account_id, reference, plan, cycle, amount_kobo, status, email, created_at, updated_at)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)')
                ->execute([$account['type'], $account['id'], $reference, $plan, $cycle, $amountKobo, 'pending', $email, $now, $now]);
        } catch (Throwable $e) {
            error_log('BillingService pending: ' . $e->getMessage());
        }
    }

    /**
     * Apply a verified Paystack transaction (callback verify or webhook data).
     * Returns the stored transaction, or null if the reference is unknown.
     */
    public static function applyPaystackResult(array $data): ?array
    {
        self::ensureTables();
        $reference = (string)($data['reference'] ?? '');
        $tx = self::transaction($reference);
        if (!$tx) {
            // Webhook for a checkout started elsewhere: record it from the metadata.
            $meta = is_array($data['metadata'] ?? null) ? $data['metadata'] : [];
            $plan = (string)($meta['plan'] ?? '');
            if ($reference === '' || PaystackService::getPlanDetails($plan) === null) {
                return null;
            }
            $account = self::account((string)($meta['account_type'] ?? 'candidate'));
            $account['id'] = (int)($meta['account_id'] ?? $account['id']);
            self::recordPending($account, $reference, $plan, (string)($meta['cycle'] ?? 'monthly'), (int)($data['amount'] ?? 0), (string)($data['customer']['email'] ?? ''));
            $tx = self::transaction($reference);
            if (!$tx) {
                return null;
            }
        }

        $status = strtolower((string)($data['status'] ?? 'failed'));
        $status = in_array($status, ['success', 'failed', 'abandoned', 'reversed'], true) ? $status : 'failed';
        // Never trust a success whose amount differs from what was charged for the plan.
        if ($status === 'success' && (int)($data['amount'] ?? 0) !== (int)$tx['amount_kobo']) {
            $status = 'failed';
            $data['gateway_response'] = 'Amount mismatch';
        }
        if ($tx['status'] === 'success') {
            return $tx; // already applied (callback and webhook both arrive)
        }

        $auth = is_array($data['authorization'] ?? null) ? $data['authorization'] : [];
        $paidAt = $status === 'success' ? date('Y-m-d H:i:s', strtotime((string)($data['paid_at'] ?? 'now')) ?: time()) : null;
        $expM = (string)($auth['exp_month'] ?? '');
        $expY = (string)($auth['exp_year'] ?? '');
        $cardExp = preg_match('/^\d{1,2}$/', $expM) && preg_match('/^\d{2,4}$/', $expY) ? str_pad($expM, 2, '0', STR_PAD_LEFT) . '/' . substr($expY, -2) : null;
        self::pdo()->prepare('UPDATE billing_transactions SET status = ?, channel = ?, card_brand = ?, card_last4 = ?, card_exp = ?, card_bank = ?, gateway_response = ?, paid_at = ?, updated_at = ? WHERE reference = ?')
            ->execute([
                $status,
                mb_substr((string)($data['channel'] ?? ''), 0, 32) ?: null,
                mb_substr(ucfirst(trim((string)($auth['card_type'] ?? $auth['brand'] ?? ''))), 0, 32) ?: null,
                preg_match('/^\d{4}$/', (string)($auth['last4'] ?? '')) ? $auth['last4'] : null,
                $cardExp,
                mb_substr(trim((string)($auth['bank'] ?? '')), 0, 80) ?: null,
                mb_substr((string)($data['gateway_response'] ?? ''), 0, 255) ?: null,
                $paidAt,
                date('Y-m-d H:i:s'),
                $reference,
            ]);

        if ($status === 'success') {
            self::activate($tx['account_type'], (int)$tx['account_id'], $tx['plan'], $tx['cycle'], (int)$tx['amount_kobo'], $reference, (string)$paidAt);
        }
        return self::transaction($reference);
    }

    /** Start or extend a plan. Paying again before renewal adds the new period on top. */
    private static function activate(string $type, int $id, string $plan, string $cycle, int $amountKobo, string $reference, string $paidAt): void
    {
        $current = self::subscription($type, $id);
        $from = strtotime($paidAt) ?: time();
        if ($current && $current['status'] === 'active' && $current['plan'] === $plan && strtotime($current['renews_at']) > $from) {
            $from = strtotime($current['renews_at']);
        }
        $renews = strtotime($cycle === 'annual' ? '+1 year' : '+1 month', $from);
        $started = ($current && $current['status'] === 'active' && $current['plan'] === $plan) ? $current['started_at'] : $paidAt;
        $now = date('Y-m-d H:i:s');
        $pdo = self::pdo();
        $sql = self::isMysql($pdo)
            ? 'INSERT INTO billing_subscriptions (account_type, account_id, plan, cycle, status, amount_kobo, started_at, renews_at, last_reference, updated_at)
               VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE plan = VALUES(plan), cycle = VALUES(cycle), status = VALUES(status),
               amount_kobo = VALUES(amount_kobo), started_at = VALUES(started_at), renews_at = VALUES(renews_at), last_reference = VALUES(last_reference), updated_at = VALUES(updated_at)'
            : 'INSERT INTO billing_subscriptions (account_type, account_id, plan, cycle, status, amount_kobo, started_at, renews_at, last_reference, updated_at)
               VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?) ON CONFLICT(account_type, account_id) DO UPDATE SET plan = excluded.plan, cycle = excluded.cycle,
               status = excluded.status, amount_kobo = excluded.amount_kobo, started_at = excluded.started_at, renews_at = excluded.renews_at,
               last_reference = excluded.last_reference, updated_at = excluded.updated_at';
        $pdo->prepare($sql)->execute([$type, $id, $plan, $cycle, 'active', $amountKobo, $started, date('Y-m-d H:i:s', $renews), $reference, $now]);
    }

    public static function transaction(string $reference): ?array
    {
        self::ensureTables();
        try {
            $stmt = self::pdo()->prepare('SELECT * FROM billing_transactions WHERE reference = ?');
            $stmt->execute([$reference]);
            return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
        } catch (Throwable $e) {
            return null;
        }
    }

    /** Current plan; an active plan past its renewal date is reported as expired. */
    public static function subscription(string $type, int $id): ?array
    {
        self::ensureTables();
        try {
            $stmt = self::pdo()->prepare('SELECT * FROM billing_subscriptions WHERE account_type = ? AND account_id = ?');
            $stmt->execute([$type, $id]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
        } catch (Throwable $e) {
            return null;
        }
        if ($row && $row['status'] === 'active' && strtotime($row['renews_at']) < time()) {
            $row['status'] = 'expired';
        }
        return $row;
    }

    /** @return array{rows: array, total: int} newest first */
    public static function transactions(string $type, int $id, int $limit = 10, int $offset = 0): array
    {
        self::ensureTables();
        try {
            $count = self::pdo()->prepare('SELECT COUNT(*) FROM billing_transactions WHERE account_type = ? AND account_id = ?');
            $count->execute([$type, $id]);
            $stmt = self::pdo()->prepare('SELECT * FROM billing_transactions WHERE account_type = ? AND account_id = ? ORDER BY id DESC LIMIT ' . max(1, min(100, $limit)) . ' OFFSET ' . max(0, $offset));
            $stmt->execute([$type, $id]);
            return ['rows' => $stmt->fetchAll(PDO::FETCH_ASSOC), 'total' => (int)$count->fetchColumn()];
        } catch (Throwable $e) {
            return ['rows' => [], 'total' => 0];
        }
    }

    /** Everything the billing page needs for one account. */
    public static function overview(string $type, int $id, int $page = 1, int $perPage = 8): array
    {
        $sub = self::subscription($type, $id);
        $page = max(1, $page);
        $tx = self::transactions($type, $id, $perPage, ($page - 1) * $perPage);
        $paid = 0;
        try {
            $stmt = self::pdo()->prepare("SELECT COALESCE(SUM(amount_kobo), 0) FROM billing_transactions WHERE account_type = ? AND account_id = ? AND status = 'success'");
            $stmt->execute([$type, $id]);
            $paid = (int)$stmt->fetchColumn();
        } catch (Throwable $e) {
        }
        return [
            'subscription' => $sub,
            'plan'         => $sub ? PaystackService::getPlanDetails($sub['plan']) : null,
            'transactions' => $tx['rows'],
            'total'        => $tx['total'],
            'page'         => $page,
            'pages'        => max(1, (int)ceil($tx['total'] / $perPage)),
            'paid_kobo'    => $paid,
        ];
    }

    public static function naira(int $kobo): string
    {
        return '₦' . number_format($kobo / 100, $kobo % 100 === 0 ? 0 : 2);
    }

    /** Status label + tone for the history table. */
    public static function statusMeta(string $status): array
    {
        return [
            'success'   => ['Paid', 'green'],
            'pending'   => ['Pending', 'amber'],
            'abandoned' => ['Cancelled', 'slate'],
            'failed'    => ['Failed', 'red'],
            'reversed'  => ['Refunded', 'slate'],
        ][$status] ?? [ucfirst($status), 'slate'];
    }
}
