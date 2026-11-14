<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\Database;
use App\Core\Env;
use PDO;

/**
 * Contact form (docs/54-email-newsletter-contact.md): stored in `contact_messages`, acknowledged
 * to the sender by e-mail, forwarded to MAIL_ADMIN_ADDRESS, answered from Admin > Communications.
 */
final class ContactService
{
    public const ROLES = ['candidate' => 'Candidate', 'employer' => 'Employer', 'partner' => 'University / partner'];
    public const STATUSES = ['new' => 'New', 'open' => 'Open', 'replied' => 'Replied', 'closed' => 'Closed'];
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
            $pdo->exec('CREATE TABLE IF NOT EXISTS contact_messages (
                id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY, reference VARCHAR(16) NOT NULL, name VARCHAR(120) NOT NULL, email VARCHAR(190) NOT NULL,
                role VARCHAR(20) NOT NULL, topic VARCHAR(80) NOT NULL, subject VARCHAR(160) NULL, message TEXT NOT NULL,
                status VARCHAR(16) NOT NULL DEFAULT \'new\', reply TEXT NULL, replied_by VARCHAR(120) NULL, replied_at DATETIME NULL,
                ip_hash CHAR(64) NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL,
                UNIQUE KEY uq_contact_ref (reference), INDEX idx_contact_status (status, created_at)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');
        } else {
            $pdo->exec('CREATE TABLE IF NOT EXISTS contact_messages (id INTEGER PRIMARY KEY AUTOINCREMENT, reference TEXT NOT NULL UNIQUE, name TEXT NOT NULL,
                email TEXT NOT NULL, role TEXT NOT NULL, topic TEXT NOT NULL, subject TEXT, message TEXT NOT NULL, status TEXT NOT NULL DEFAULT \'new\',
                reply TEXT, replied_by TEXT, replied_at TEXT, ip_hash TEXT, created_at TEXT NOT NULL, updated_at TEXT NOT NULL)');
            $pdo->exec('CREATE INDEX IF NOT EXISTS idx_contact_status ON contact_messages (status, created_at)');
        }
        self::$ready = true;
    }

    /** @return array{ok: bool, errors?: array, reference?: string} */
    public static function submit(array $in): array
    {
        self::ensureTables();
        $clean = static fn ($v, int $max) => mb_substr(trim(strip_tags((string)$v)), 0, $max);
        $name = $clean($in['name'] ?? '', 120);
        $email = strtolower($clean($in['email'] ?? '', 190));
        $role = array_key_exists((string)($in['role'] ?? ''), self::ROLES) ? (string)$in['role'] : 'candidate';
        $topic = $clean($in['topic'] ?? 'support', 80) ?: 'support';
        $subject = $clean($in['subject'] ?? '', 160);
        $message = mb_substr(trim(strip_tags((string)($in['message'] ?? ''))), 0, 5000);

        $errors = [];
        if (mb_strlen($name) < 2) {
            $errors['name'] = 'Enter your full name.';
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Enter a valid e-mail address.';
        }
        if (mb_strlen($message) < 10) {
            $errors['message'] = 'Tell us a little more (at least 10 characters).';
        }
        if ($errors) {
            return ['ok' => false, 'errors' => $errors];
        }

        $ref = 'IC-' . strtoupper(bin2hex(random_bytes(3)));
        $now = date('Y-m-d H:i:s');
        self::pdo()->prepare("INSERT INTO contact_messages (reference, name, email, role, topic, subject, message, status, ip_hash, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, 'new', ?, ?, ?)")
            ->execute([$ref, $name, $email, $role, $topic, $subject, $message, hash('sha256', RateLimiter::ip()), $now, $now]);

        $first = explode(' ', $name)[0];
        // To the sender.
        MailService::sendNow($email, 'We received your message (' . $ref . ')', MailService::render('message', [
            'preheader'  => 'Our team will reply within 4 business hours.',
            'title'      => 'Thanks, ' . $first . '. We have your message.',
            'paragraphs' => ['Our support team reads every message and replies within 4 business hours (Monday to Friday, 8am to 6pm WAT). Your reference is below in case you need to follow up.'],
            'details'    => ['Reference' => $ref, 'Topic' => $subject !== '' ? $subject : ucfirst($topic), 'Your message' => mb_strimwidth($message, 0, 600, '...')],
            'cta'        => ['label' => 'Visit the Help Center', 'url' => MailService::link('/help')],
        ]), 'contact_ack', $name);
        // To the team.
        $admin = Env::get('MAIL_ADMIN_ADDRESS', Env::get('MAIL_USERNAME'));
        if ($admin !== '' && filter_var($admin, FILTER_VALIDATE_EMAIL)) {
            MailService::sendNow($admin, 'New contact message ' . $ref . ': ' . ($subject !== '' ? $subject : ucfirst($topic)), MailService::render('message', [
                'title'      => 'New message from ' . $name,
                'paragraphs' => [$message],
                'details'    => ['Reference' => $ref, 'From' => $name . ' <' . $email . '>', 'Account type' => self::ROLES[$role], 'Topic' => $subject !== '' ? $subject : ucfirst($topic)],
                'cta'        => ['label' => 'Open in admin', 'url' => MailService::link('/admin/communications?tab=inbox&id=' . self::pdo()->lastInsertId())],
                'signoff'    => 'InternConnect website',
            ]), 'contact_admin');
        }
        return ['ok' => true, 'reference' => $ref];
    }

    public static function counts(): array
    {
        self::ensureTables();
        return array_map('intval', self::pdo()->query('SELECT status, COUNT(*) FROM contact_messages GROUP BY status')->fetchAll(PDO::FETCH_KEY_PAIR)) + ['new' => 0, 'open' => 0, 'replied' => 0, 'closed' => 0];
    }

    /** @return array{rows: array, total: int} */
    public static function list(string $status = '', int $page = 1, int $per = 20): array
    {
        self::ensureTables();
        $w = array_key_exists($status, self::STATUSES) ? ' WHERE status = ?' : '';
        $args = $w ? [$status] : [];
        $count = self::pdo()->prepare('SELECT COUNT(*) FROM contact_messages' . $w);
        $count->execute($args);
        $stmt = self::pdo()->prepare('SELECT * FROM contact_messages' . $w . ' ORDER BY id DESC LIMIT ' . max(1, min(100, $per)) . ' OFFSET ' . max(0, ($page - 1) * $per));
        $stmt->execute($args);
        return ['rows' => $stmt->fetchAll(PDO::FETCH_ASSOC), 'total' => (int)$count->fetchColumn()];
    }

    public static function find(int $id): ?array
    {
        self::ensureTables();
        $stmt = self::pdo()->prepare('SELECT * FROM contact_messages WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public static function setStatus(int $id, string $status): bool
    {
        if (!array_key_exists($status, self::STATUSES)) {
            return false;
        }
        self::ensureTables();
        $stmt = self::pdo()->prepare('UPDATE contact_messages SET status = ?, updated_at = ? WHERE id = ?');
        $stmt->execute([$status, date('Y-m-d H:i:s'), $id]);
        return $stmt->rowCount() > 0;
    }

    /** Reply by e-mail from admin. Returns ['ok', 'sent' => bool, 'error'?]. */
    public static function reply(int $id, string $text, string $by): array
    {
        $m = self::find($id);
        $text = mb_substr(trim(strip_tags($text)), 0, 5000);
        if (!$m || mb_strlen($text) < 2) {
            return ['ok' => false, 'error' => 'Write a reply first.'];
        }
        $sent = MailService::sendNow($m['email'], 'Re: ' . ($m['subject'] !== '' && $m['subject'] !== null ? $m['subject'] : 'Your message to InternConnect') . ' (' . $m['reference'] . ')', MailService::render('message', [
            'title'      => 'Hi ' . explode(' ', $m['name'])[0] . ',',
            'paragraphs' => [$text],
            'details'    => ['Reference' => $m['reference'], 'You wrote' => mb_strimwidth((string)$m['message'], 0, 500, '...')],
            'note'       => 'Reply to this e-mail if you need anything else.',
            'signoff'    => $by . ', InternConnect support',
        ]), 'contact_reply', $m['name']);
        $now = date('Y-m-d H:i:s');
        self::pdo()->prepare("UPDATE contact_messages SET status = 'replied', reply = ?, replied_by = ?, replied_at = ?, updated_at = ? WHERE id = ?")
            ->execute([$text, $by, $now, $now, $id]);
        return ['ok' => true, 'sent' => $sent, 'error' => $sent ? null : MailService::$lastError];
    }
}
