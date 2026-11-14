<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\Database;
use PDO;

/**
 * Who is account candidate #N or employer #N? (docs/53, "Grants").
 *
 * There is no accounts table yet (accounts move to MySQL later), so names and e-mails come from
 * what the platform already stores: the demo profiles, submitted applications and parsed CVs.
 * When real accounts land, only this class changes; the admin grant screens keep working.
 */
final class AccountDirectory
{
    /** @return array{type: string, id: int, name: string, email: string, label: string} */
    public static function describe(string $type, int $id): array
    {
        $all = self::all();
        $row = $all[$type . ':' . $id] ?? null;
        $name = $row['name'] ?? ($type === 'employer' ? 'Employer' : 'Candidate') . ' #' . $id;
        $email = $row['email'] ?? '';
        return ['type' => $type, 'id' => $id, 'name' => $name, 'email' => $email,
            'label' => ucfirst($type) . ' #' . $id];
    }

    /** Search by name, e-mail or number. @return list<array> */
    public static function search(string $q, string $type = '', int $limit = 8): array
    {
        $q = mb_strtolower(trim($q));
        $out = [];
        foreach (self::all() as $row) {
            if ($type !== '' && $row['type'] !== $type) {
                continue;
            }
            $hay = mb_strtolower($row['name'] . ' ' . $row['email'] . ' #' . $row['id'] . ' ' . $row['id']);
            if ($q === '' || str_contains($hay, $q)) {
                $out[] = $row + ['label' => ucfirst($row['type']) . ' #' . $row['id']];
            }
            if (count($out) >= $limit) {
                break;
            }
        }
        return $out;
    }

    /** @return array<string, array{type: string, id: int, name: string, email: string}> keyed "type:id" */
    private static function all(): array
    {
        static $cache = null;
        if ($cache !== null) {
            return $cache;
        }
        $rows = [];
        $add = static function (string $type, int $id, ?string $name, ?string $email) use (&$rows): void {
            if ($id < 1) {
                return;
            }
            $key = $type . ':' . $id;
            $name = trim((string)$name);
            $email = trim((string)$email);
            $rows[$key] ??= ['type' => $type, 'id' => $id, 'name' => '', 'email' => ''];
            if ($rows[$key]['name'] === '' && $name !== '') {
                $rows[$key]['name'] = $name;
            }
            if ($rows[$key]['email'] === '' && $email !== '') {
                $rows[$key]['email'] = $email;
            }
        };

        // Demo accounts used across the candidate and employer areas.
        $add('candidate', 1, 'Sarah Peters', 'sarah.peters@gmail.com');
        $add('employer', 1, 'Global Tech Solutions', '');

        try {
            $pdo = Database::getConnection();
            foreach ($pdo->query('SELECT candidate_id, full_name, email FROM application_submissions ORDER BY id DESC')->fetchAll(PDO::FETCH_ASSOC) as $r) {
                $add('candidate', (int)$r['candidate_id'], $r['full_name'], $r['email']);
            }
            foreach ($pdo->query('SELECT candidate_id, parsed_json FROM candidate_cv_profiles')->fetchAll(PDO::FETCH_ASSOC) as $r) {
                $j = json_decode((string)$r['parsed_json'], true) ?: [];
                $add('candidate', (int)$r['candidate_id'], $j['name'] ?? null, $j['email'] ?? null);
            }
            foreach ($pdo->query('SELECT DISTINCT account_type, account_id FROM billing_subscriptions')->fetchAll(PDO::FETCH_ASSOC) as $r) {
                $add($r['account_type'] === 'employer' ? 'employer' : 'candidate', (int)$r['account_id'], null, null);
            }
            foreach ($pdo->query('SELECT DISTINCT account_type, account_id FROM plan_grants')->fetchAll(PDO::FETCH_ASSOC) as $r) {
                $add($r['account_type'] === 'employer' ? 'employer' : 'candidate', (int)$r['account_id'], null, null);
            }
        } catch (\Throwable) {
            // Tables are created on first use; the demo accounts are enough until then.
        }
        foreach ($rows as $k => $r) {
            if ($r['name'] === '') {
                $rows[$k]['name'] = ucfirst($r['type']) . ' #' . $r['id'];
            }
        }
        return $cache = $rows;
    }
}
