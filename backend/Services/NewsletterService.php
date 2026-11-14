<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\Database;
use PDO;

/**
 * Newsletter (docs/54-email-newsletter-contact.md).
 *
 * Double opt-in: subscribe -> confirmation e-mail -> subscribed (+ welcome e-mail).
 * Every subscriber has a secret token used for the confirm and unsubscribe links, so nobody can
 * unsubscribe someone else by guessing an e-mail. Campaigns are written by admin, queued one
 * e-mail per subscriber in `mail_queue` and sent by the cron (or "Send next batch" in admin).
 */
final class NewsletterService
{
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
            $pdo->exec('CREATE TABLE IF NOT EXISTS newsletter_subscribers (
                id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY, email VARCHAR(190) NOT NULL, name VARCHAR(120) NULL,
                status VARCHAR(16) NOT NULL DEFAULT \'pending\', source VARCHAR(40) NOT NULL DEFAULT \'website\', token CHAR(40) NOT NULL,
                confirmed_at DATETIME NULL, unsubscribed_at DATETIME NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL,
                UNIQUE KEY uq_newsletter_email (email), UNIQUE KEY uq_newsletter_token (token), INDEX idx_newsletter_status (status)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');
            $pdo->exec('CREATE TABLE IF NOT EXISTS newsletter_campaigns (
                id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY, subject VARCHAR(200) NOT NULL, preheader VARCHAR(200) NULL, title VARCHAR(200) NOT NULL,
                hero VARCHAR(40) NULL, body MEDIUMTEXT NOT NULL, cta_label VARCHAR(60) NULL, cta_url VARCHAR(500) NULL, jenny_tip VARCHAR(600) NULL,
                status VARCHAR(16) NOT NULL DEFAULT \'draft\', recipients INT UNSIGNED NOT NULL DEFAULT 0, created_by VARCHAR(120) NULL,
                created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, queued_at DATETIME NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');
        } else {
            $pdo->exec('CREATE TABLE IF NOT EXISTS newsletter_subscribers (id INTEGER PRIMARY KEY AUTOINCREMENT, email TEXT NOT NULL UNIQUE, name TEXT,
                status TEXT NOT NULL DEFAULT \'pending\', source TEXT NOT NULL DEFAULT \'website\', token TEXT NOT NULL UNIQUE,
                confirmed_at TEXT, unsubscribed_at TEXT, created_at TEXT NOT NULL, updated_at TEXT NOT NULL)');
            $pdo->exec('CREATE INDEX IF NOT EXISTS idx_newsletter_status ON newsletter_subscribers (status)');
            $pdo->exec('CREATE TABLE IF NOT EXISTS newsletter_campaigns (id INTEGER PRIMARY KEY AUTOINCREMENT, subject TEXT NOT NULL, preheader TEXT, title TEXT NOT NULL,
                hero TEXT, body TEXT NOT NULL, cta_label TEXT, cta_url TEXT, jenny_tip TEXT, status TEXT NOT NULL DEFAULT \'draft\',
                recipients INTEGER NOT NULL DEFAULT 0, created_by TEXT, created_at TEXT NOT NULL, updated_at TEXT NOT NULL, queued_at TEXT)');
        }
        // Additive columns (2026-11-13): campaign types, audiences and uploaded images.
        $isMy = (string)$pdo->getAttribute(PDO::ATTR_DRIVER_NAME) === 'mysql';
        $cols = static fn (string $t) => $isMy ? $pdo->query('SHOW COLUMNS FROM ' . $t)->fetchAll(PDO::FETCH_COLUMN) : array_column($pdo->query('PRAGMA table_info(' . $t . ')')->fetchAll(PDO::FETCH_ASSOC), 'name');
        $add = [
            'newsletter_campaigns' => ['type' => ["VARCHAR(16) NOT NULL DEFAULT 'general'", "TEXT NOT NULL DEFAULT 'general'"], 'audience' => ["VARCHAR(16) NOT NULL DEFAULT 'all'", "TEXT NOT NULL DEFAULT 'all'"], 'hero_url' => ['VARCHAR(500) NULL', 'TEXT'], 'body_image_url' => ['VARCHAR(500) NULL', 'TEXT'], 'extra' => ['TEXT NULL', 'TEXT']],
            'newsletter_subscribers' => ['audience' => ["VARCHAR(16) NOT NULL DEFAULT 'student'", "TEXT NOT NULL DEFAULT 'student'"]],
        ];
        foreach ($add as $table => $columns) {
            $have = $cols($table);
            foreach ($columns as $col => [$my, $lite]) {
                if (!in_array($col, $have, true)) {
                    $pdo->exec('ALTER TABLE ' . $table . ' ADD COLUMN ' . $col . ' ' . ($isMy ? $my : $lite));
                }
            }
        }
        self::$ready = true;
    }

    public static function find(string $email): ?array
    {
        self::ensureTables();
        $stmt = self::pdo()->prepare('SELECT * FROM newsletter_subscribers WHERE email = ?');
        $stmt->execute([strtolower(trim($email))]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    private static function byToken(string $token): ?array
    {
        if (!preg_match('/^[a-f0-9]{40}$/', $token)) {
            return null;
        }
        self::ensureTables();
        $stmt = self::pdo()->prepare('SELECT * FROM newsletter_subscribers WHERE token = ?');
        $stmt->execute([$token]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    /**
     * Start a subscription. Returns ['ok', 'state' => pending|already|invalid, 'message'].
     * Re-subscribing after an unsubscribe needs a fresh confirmation.
     */
    public static function subscribe(string $email, string $source = 'website', ?string $name = null): array
    {
        self::ensureTables();
        $email = strtolower(trim($email));
        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 190) {
            return ['ok' => false, 'state' => 'invalid', 'message' => 'Enter a valid e-mail address.'];
        }
        $now = date('Y-m-d H:i:s');
        $row = self::find($email);
        if ($row && $row['status'] === 'subscribed') {
            return ['ok' => true, 'state' => 'already', 'message' => 'You are already subscribed. Thank you.'];
        }
        $token = bin2hex(random_bytes(20));
        $source = mb_substr(preg_replace('/[^a-z0-9_-]/', '', strtolower($source)) ?: 'website', 0, 40);
        if ($row) {
            self::pdo()->prepare("UPDATE newsletter_subscribers SET status = 'pending', token = ?, source = ?, name = COALESCE(?, name), unsubscribed_at = NULL, updated_at = ? WHERE id = ?")
                ->execute([$token, $source, $name, $now, $row['id']]);
        } else {
            self::pdo()->prepare("INSERT INTO newsletter_subscribers (email, name, status, source, audience, token, created_at, updated_at) VALUES (?, ?, 'pending', ?, ?, ?, ?, ?)")
                ->execute([$email, $name, $source, str_contains($source, 'employer') ? 'employer' : 'student', $token, $now, $now]);
        }
        $html = MailService::render('message', [
            'preheader'  => 'One click to confirm your InternConnect newsletter subscription.',
            'hero'       => ['src' => MailService::image('email_updates', 1200), 'alt' => 'InternConnect updates, delivered to you'],
            'badge'      => 'Newsletter',
            'title'      => 'Confirm your subscription',
            'paragraphs' => [
                'Thanks for joining the InternConnect newsletter. Confirm your e-mail address and we will send you new internships, training programmes from our partners and career tips from Jenny, our AI assistant.',
                'We send at most two e-mails a month, and you can leave at any time.',
            ],
            'cta'        => ['label' => 'Confirm my subscription', 'url' => MailService::link('/newsletter/confirm/' . $token)],
            'note'       => 'If you did not ask to subscribe, ignore this e-mail and nothing will happen.',
        ]);
        MailService::sendNow($email, 'Confirm your InternConnect newsletter subscription', $html, 'newsletter_confirm', $name);
        return ['ok' => true, 'state' => 'pending', 'message' => 'Almost done. Check your inbox and confirm your subscription.'];
    }

    /** Confirm link. Returns the subscriber or null for an unknown/expired token. */
    public static function confirm(string $token): ?array
    {
        $row = self::byToken($token);
        if (!$row) {
            return null;
        }
        if ($row['status'] !== 'subscribed') {
            $now = date('Y-m-d H:i:s');
            self::pdo()->prepare("UPDATE newsletter_subscribers SET status = 'subscribed', confirmed_at = ?, unsubscribed_at = NULL, updated_at = ? WHERE id = ?")
                ->execute([$now, $now, $row['id']]);
            $html = MailService::render('message', [
                'preheader'      => 'You are in. Here is what to expect from InternConnect.',
                'hero'           => ['src' => MailService::image('email_welcome', 1200), 'alt' => 'Welcome to InternConnect'],
                'badge'          => 'You are subscribed',
                'title'          => 'Welcome to the InternConnect newsletter',
                'paragraphs'     => [
                    'You are subscribed. Each issue brings new internships from verified companies, training programmes from our learning partners, and practical tips to help you get hired.',
                    'Want a head start? Complete your profile and let Jenny match you with internships that fit your skills.',
                ],
                'cta'            => ['label' => 'Find internships', 'url' => MailService::link('/internships')],
                'unsubscribeUrl' => self::unsubscribeUrlFor($row['email']),
                'trackUrl'       => '{{track}}',
            ]);
            MailService::sendNow($row['email'], 'Welcome to InternConnect', $html, 'newsletter', $row['name']);
            $row['status'] = 'subscribed';
        }
        return $row;
    }

    public static function unsubscribe(string $token): ?array
    {
        $row = self::byToken($token);
        if (!$row) {
            return null;
        }
        if ($row['status'] !== 'unsubscribed') {
            $now = date('Y-m-d H:i:s');
            self::pdo()->prepare("UPDATE newsletter_subscribers SET status = 'unsubscribed', unsubscribed_at = ?, updated_at = ? WHERE id = ?")
                ->execute([$now, $now, $row['id']]);
            // Nothing still queued for this address goes out.
            MailService::ensureTables();
            self::pdo()->prepare("UPDATE mail_queue SET status = 'cancelled', updated_at = ? WHERE to_email = ? AND status = 'queued' AND category IN ('newsletter', 'campaign')")
                ->execute([$now, $row['email']]);
            $row['status'] = 'unsubscribed';
        }
        return $row;
    }

    public static function tokenFor(string $email): ?string
    {
        return self::find($email)['token'] ?? null;
    }

    public static function unsubscribeUrlFor(string $email): string
    {
        $token = self::tokenFor($email);
        return MailService::link($token ? '/newsletter/unsubscribe/' . $token : '/contact');
    }

    /** RFC 8058 one-click target (mail providers POST here; the token is the authorisation). */
    public static function oneClickUrlFor(string $email): ?string
    {
        $token = self::tokenFor($email);
        return $token ? MailService::link('/newsletter/one-click/' . $token) : null;
    }

    // ── Admin ───────────────────────────────────────────────────────────────

    public static function stats(): array
    {
        self::ensureTables();
        $rows = self::pdo()->query('SELECT status, COUNT(*) c FROM newsletter_subscribers GROUP BY status')->fetchAll(PDO::FETCH_KEY_PAIR);
        $week = self::pdo()->prepare("SELECT COUNT(*) FROM newsletter_subscribers WHERE status = 'subscribed' AND confirmed_at >= ?");
        $week->execute([date('Y-m-d H:i:s', strtotime('-7 days'))]);
        return [
            'subscribed'   => (int)($rows['subscribed'] ?? 0),
            'pending'      => (int)($rows['pending'] ?? 0),
            'unsubscribed' => (int)($rows['unsubscribed'] ?? 0),
            'new_7d'       => (int)$week->fetchColumn(),
        ];
    }

    /** @return array{rows: array, total: int} */
    public static function subscribers(string $status = '', string $q = '', int $page = 1, int $per = 20): array
    {
        self::ensureTables();
        $where = [];
        $args = [];
        if (in_array($status, ['subscribed', 'pending', 'unsubscribed'], true)) {
            $where[] = 'status = ?';
            $args[] = $status;
        }
        if ($q !== '') {
            $where[] = '(email LIKE ? OR name LIKE ?)';
            $args[] = '%' . $q . '%';
            $args[] = '%' . $q . '%';
        }
        $w = $where ? ' WHERE ' . implode(' AND ', $where) : '';
        $count = self::pdo()->prepare('SELECT COUNT(*) FROM newsletter_subscribers' . $w);
        $count->execute($args);
        $stmt = self::pdo()->prepare('SELECT id, email, name, status, source, audience, confirmed_at, unsubscribed_at, created_at FROM newsletter_subscribers' . $w . ' ORDER BY id DESC LIMIT ' . max(1, min(200, $per)) . ' OFFSET ' . max(0, ($page - 1) * $per));
        $stmt->execute($args);
        return ['rows' => $stmt->fetchAll(PDO::FETCH_ASSOC), 'total' => (int)$count->fetchColumn()];
    }

    /** Admin removes an address from the list (kept as unsubscribed so it is never mailed again). */
    public static function adminUnsubscribe(int $id): bool
    {
        self::ensureTables();
        $stmt = self::pdo()->prepare('SELECT token FROM newsletter_subscribers WHERE id = ?');
        $stmt->execute([$id]);
        $token = $stmt->fetchColumn();
        return $token ? self::unsubscribe((string)$token) !== null : false;
    }

    public static function exportCsv(): string
    {
        self::ensureTables();
        $out = fopen('php://temp', 'w+');
        fputcsv($out, ['email', 'name', 'status', 'source', 'confirmed_at', 'unsubscribed_at', 'created_at']);
        foreach (self::pdo()->query('SELECT email, name, status, source, confirmed_at, unsubscribed_at, created_at FROM newsletter_subscribers ORDER BY id') as $r) {
            // Neutralise spreadsheet formulas in user-supplied text.
            fputcsv($out, array_map(static fn ($v) => is_string($v) && preg_match('/^[=+\-@]/', $v) ? "'" . $v : $v, $r));
        }
        rewind($out);
        return (string)stream_get_contents($out);
    }

    public const HEROES = [
        ''                  => 'No image',
        'email_updates'     => 'Updates, delivered to you',
        'email_welcome'     => 'Welcome to InternConnect',
        'email_event'       => 'Event invitation',
        'email_internships' => 'New internships for you',
        'email_employers'   => 'For employers: hire interns',
        'email_training'    => 'Training and certificates',
        'email_offer'       => 'Pro and Premium offer',
    ];

    /** Footer bands (Cloudinary InternConnect/images/emails). */
    public const FOOTERS = ['circles' => 'Circles', 'logo' => 'Logo pattern', 'waves' => 'Waves', 'dots' => 'Dots and logo'];

    /** Button colours: white text on each, readable in light and dark mode. 'auto' = the layout's own colour. */
    public const BUTTONS = ['auto' => ['Layout default', ''], 'indigo' => ['Indigo', '#4F48E6'], 'navy' => ['Navy', '#000623'], 'violet' => ['Violet', '#7C3AED'], 'ocean' => ['Ocean', '#0E7490'], 'sunset' => ['Sunset', '#C2410C']];

    /** Campaign types: each has its own e-mail layout (frontend/emails/campaign.php). */
    public const TYPES = [
        'general'   => ['General update', 'Heading, message, optional image and a button.', 'all'],
        'marketing' => ['Marketing', 'Big image, short pitch, feature tiles and a strong button.', 'all'],
        'event'     => ['Event', 'Date, time and place in a highlighted box, plus a register button.', 'all'],
        'interns'   => ['For interns', 'Handpicked internships as cards, with a tip from Jenny.', 'student'],
        'employers' => ['For employers', 'A navy "For employers" band, benefits with ticks, post-an-internship button.', 'employer'],
    ];
    public const AUDIENCES = ['all' => 'All subscribers', 'student' => 'Students and graduates', 'employer' => 'Employers'];

    /** Validate and save a campaign (new or draft). Returns ['ok', 'id'|'error']. */
    public static function saveCampaign(array $in, ?int $id = null, ?string $by = null): array
    {
        self::ensureTables();
        $clean = static fn ($v, int $max) => mb_substr(trim(strip_tags((string)$v)), 0, $max);
        $type = array_key_exists((string)($in['type'] ?? ''), self::TYPES) ? (string)$in['type'] : 'general';
        $audience = array_key_exists((string)($in['audience'] ?? ''), self::AUDIENCES) ? (string)$in['audience'] : self::TYPES[$type][2];
        $httpsOrEmpty = static fn (string $u): bool => $u === '' || (bool)preg_match('#^https://[^\s"<>]+$#i', $u);
        $heroUrl = trim((string)($in['hero_url'] ?? ''));
        $bodyImage = trim((string)($in['body_image_url'] ?? ''));
        if (!$httpsOrEmpty($heroUrl) || !$httpsOrEmpty($bodyImage)) {
            return ['ok' => false, 'error' => 'Images must be https:// links (upload them with the Upload button).'];
        }
        $extra = [];
        if ($type === 'event') {
            $extra = [
                'event_date'  => preg_match('/^\d{4}-\d{2}-\d{2}$/', (string)($in['event_date'] ?? '')) ? (string)$in['event_date'] : '',
                'event_time'  => preg_match('/^\d{2}:\d{2}$/', (string)($in['event_time'] ?? '')) ? (string)$in['event_time'] : '',
                'event_tz'    => $clean($in['event_tz'] ?? 'WAT', 20),
                'event_place' => $clean($in['event_place'] ?? '', 160),
            ];
            if ($extra['event_date'] === '') {
                return ['ok' => false, 'error' => 'Add the event date.'];
            }
        }
        $extra['footer'] = array_key_exists((string)($in['footer'] ?? ''), self::FOOTERS) ? (string)$in['footer'] : 'circles';
        $extra['button'] = array_key_exists((string)($in['button'] ?? ''), self::BUTTONS) ? (string)$in['button'] : 'auto';
        if ($type === 'interns') {
            $known = array_column(require dirname(__DIR__) . '/Data/internships.php', 'id');
            $extra['listings'] = array_values(array_slice(array_intersect((array)($in['listings'] ?? []), $known), 0, 4));
        }
        $data = [
            'subject'   => $clean($in['subject'] ?? '', 200),
            'preheader' => $clean($in['preheader'] ?? '', 200),
            'title'     => $clean($in['title'] ?? '', 200),
            'hero'      => array_key_exists($hero = (string)($in['hero'] ?? ''), self::HEROES) ? $hero : '',
            'body'      => mb_substr(trim(strip_tags((string)($in['body'] ?? ''))), 0, 20000),
            'cta_label' => $clean($in['cta_label'] ?? '', 60),
            'cta_url'   => trim((string)($in['cta_url'] ?? '')),
            'jenny_tip' => $clean($in['jenny_tip'] ?? '', 600),
            'type'      => $type,
            'audience'  => $audience,
            'hero_url'  => $heroUrl ?: null,
            'body_image_url' => $bodyImage ?: null,
            'extra'     => $extra ? json_encode($extra) : null,
        ];
        if ($data['subject'] === '' || $data['title'] === '' || mb_strlen($data['body']) < 20) {
            return ['ok' => false, 'error' => 'Add a subject, a heading and a message of at least 20 characters.'];
        }
        if ($data['cta_url'] !== '' && !preg_match('#^https?://#i', $data['cta_url']) && !str_starts_with($data['cta_url'], '/')) {
            return ['ok' => false, 'error' => 'The button link must start with https:// or / (a page on InternConnect).'];
        }
        if ($data['cta_url'] !== '' && str_starts_with($data['cta_url'], '/')) {
            $data['cta_url'] = MailService::link($data['cta_url']);
        }
        $now = date('Y-m-d H:i:s');
        $pdo = self::pdo();
        if ($id) {
            $c = self::campaign($id);
            if (!$c || $c['status'] !== 'draft') {
                return ['ok' => false, 'error' => 'Only drafts can be edited.'];
            }
            $pdo->prepare('UPDATE newsletter_campaigns SET subject = ?, preheader = ?, title = ?, hero = ?, body = ?, cta_label = ?, cta_url = ?, jenny_tip = ?, type = ?, audience = ?, hero_url = ?, body_image_url = ?, extra = ?, updated_at = ? WHERE id = ?')
                ->execute([...array_values($data), $now, $id]);
            return ['ok' => true, 'id' => $id];
        }
        $pdo->prepare("INSERT INTO newsletter_campaigns (subject, preheader, title, hero, body, cta_label, cta_url, jenny_tip, type, audience, hero_url, body_image_url, extra, status, recipients, created_by, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'draft', 0, ?, ?, ?)")
            ->execute([...array_values($data), $by, $now, $now]);
        return ['ok' => true, 'id' => (int)$pdo->lastInsertId()];
    }

    public static function campaign(int $id): ?array
    {
        self::ensureTables();
        $stmt = self::pdo()->prepare('SELECT * FROM newsletter_campaigns WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    /** Campaigns with delivery numbers from mail_queue. */
    public static function campaigns(int $limit = 30): array
    {
        self::ensureTables();
        MailService::ensureTables();
        $rows = self::pdo()->query('SELECT * FROM newsletter_campaigns ORDER BY id DESC LIMIT ' . max(1, min(200, $limit)))->fetchAll(PDO::FETCH_ASSOC);
        $stat = self::pdo()->prepare("SELECT SUM(status = 'sent'), SUM(status = 'queued'), SUM(status = 'failed'), SUM(opened_at IS NOT NULL) FROM mail_queue WHERE campaign_id = ? AND category = 'campaign'");
        foreach ($rows as &$r) {
            $stat->execute([$r['id']]);
            [$sent, $queued, $failed, $opened] = array_map('intval', $stat->fetch(PDO::FETCH_NUM) ?: [0, 0, 0, 0]);
            $r += ['sent' => $sent, 'queued' => $queued, 'failed' => $failed, 'opened' => $opened];
            if ($r['status'] === 'sending' && $queued === 0) {
                self::pdo()->prepare("UPDATE newsletter_campaigns SET status = 'sent', updated_at = ? WHERE id = ?")->execute([date('Y-m-d H:i:s'), $r['id']]);
                $r['status'] = 'sent';
            }
        }
        return $rows;
    }

    /** Branded HTML for a campaign (preview, test, and each recipient). */
    public static function renderCampaign(array $c, ?string $unsubscribeUrl = null, bool $track = true): string
    {
        $extra = is_array($c['extra'] ?? null) ? $c['extra'] : (json_decode((string)($c['extra'] ?? ''), true) ?: []);
        $type = array_key_exists((string)($c['type'] ?? ''), self::TYPES) ? (string)$c['type'] : 'general';
        $hero = !empty($c['hero_url'])
            ? ['src' => (string)$c['hero_url'], 'alt' => (string)$c['title']]
            : (!empty($c['hero']) ? ['src' => MailService::image((string)$c['hero'], 1200), 'alt' => self::HEROES[$c['hero']] ?? 'InternConnect'] : null);
        $listings = [];
        if ($type === 'interns' && !empty($extra['listings'])) {
            foreach (PlanService::annotateListings(require dirname(__DIR__) . '/Data/internships.php', 'free') as $l) {
                if (in_array($l['id'], $extra['listings'], true)) {
                    $listings[] = ['title' => $l['title'], 'company' => $l['company'], 'meta' => $l['location'] . ' · ' . ucwords(strtolower((string)$l['tag'])),
                        'pay' => $l['locked'] ? 'Premium listing' : 'NGN ' . number_format((int)$l['stipendAmount']) . '/month', 'url' => MailService::link('/internship/' . $l['id'])];
                }
            }
        }
        return MailService::render('campaign', [
            'preheader'      => (string)($c['preheader'] ?? ''),
            'hero'           => $hero,
            'type'           => $type,
            'extra'          => $extra,
            'listings'       => $listings,
            'bodyImage'      => !empty($c['body_image_url']) ? (string)$c['body_image_url'] : null,
            'title'          => $c['title'],
            'body'           => $c['body'],
            'cta'            => !empty($c['cta_url']) ? ['label' => $c['cta_label'] ?: 'Open InternConnect', 'url' => $c['cta_url']] : null,
            'jenny'          => !empty($c['jenny_tip']) ? ['text' => $c['jenny_tip']] : null,
            'footer'         => (string)($extra['footer'] ?? 'circles'),
            'buttonColor'    => self::BUTTONS[$extra['button'] ?? 'auto'][1] ?? '',
            'unsubscribeUrl' => $unsubscribeUrl ?? MailService::link('/contact'),
            'trackUrl'       => $track ? '{{track}}' : null,
        ]);
    }

    public static function sendTest(int $id, string $to): bool
    {
        $c = self::campaign($id);
        if (!$c) {
            return false;
        }
        return MailService::sendNow($to, '[Test] ' . $c['subject'], self::renderCampaign($c), 'campaign_test');
    }

    /** How many confirmed subscribers an audience reaches. */
    public static function audienceCount(string $audience): int
    {
        self::ensureTables();
        $sql = "SELECT COUNT(*) FROM newsletter_subscribers WHERE status = 'subscribed'" . ($audience === 'all' ? '' : ' AND audience = ?');
        $stmt = self::pdo()->prepare($sql);
        $stmt->execute($audience === 'all' ? [] : [$audience]);
        return (int)$stmt->fetchColumn();
    }

    /** Queue a draft for every confirmed subscriber in its audience. Returns the number queued. */
    public static function queueCampaign(int $id): int
    {
        $c = self::campaign($id);
        if (!$c || $c['status'] !== 'draft') {
            return 0;
        }
        $pdo = self::pdo();
        // Lock the draft first so a double click never sends twice.
        $lock = $pdo->prepare("UPDATE newsletter_campaigns SET status = 'sending', queued_at = ?, updated_at = ? WHERE id = ? AND status = 'draft'");
        $now = date('Y-m-d H:i:s');
        $lock->execute([$now, $now, $id]);
        if ($lock->rowCount() !== 1) {
            return 0;
        }
        $audience = array_key_exists((string)($c['audience'] ?? ''), self::AUDIENCES) ? (string)$c['audience'] : 'all';
        $stmt = $pdo->prepare("SELECT email, name, token FROM newsletter_subscribers WHERE status = 'subscribed'" . ($audience === 'all' ? '' : ' AND audience = ?') . ' ORDER BY id');
        $stmt->execute($audience === 'all' ? [] : [$audience]);
        $n = 0;
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $s) {
            $html = self::renderCampaign($c, MailService::link('/newsletter/unsubscribe/' . $s['token']));
            MailService::queue($s['email'], $c['subject'], $html, 'campaign', $s['name'], $id);
            $n++;
        }
        $pdo->prepare('UPDATE newsletter_campaigns SET recipients = ?, status = ? WHERE id = ?')->execute([$n, $n ? 'sending' : 'sent', $id]);
        return $n;
    }

    /** Admin: mark a subscriber as student/graduate or employer (decides which campaigns reach them). */
    public static function setAudience(int $id, string $audience): bool
    {
        if (!in_array($audience, ['student', 'employer'], true)) {
            return false;
        }
        self::ensureTables();
        $stmt = self::pdo()->prepare('UPDATE newsletter_subscribers SET audience = ?, updated_at = ? WHERE id = ?');
        $stmt->execute([$audience, date('Y-m-d H:i:s'), $id]);
        return $stmt->rowCount() > 0;
    }

    /**
     * Upload a campaign image to Cloudinary (folder InternConnect/images/newsletter) so it shows in every inbox.
     * Returns ['ok', 'url'|'error']. PNG, JPG, WebP or GIF up to 5 MB, checked by content, not just name.
     */
    public static function uploadImage(?array $file): array
    {
        if (!$file || ($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK || !is_uploaded_file((string)$file['tmp_name'])) {
            return ['ok' => false, 'error' => 'Choose an image to upload.'];
        }
        if ((int)$file['size'] > 5 * 1024 * 1024) {
            return ['ok' => false, 'error' => 'Images must be under 5 MB.'];
        }
        $info = @getimagesize((string)$file['tmp_name']);
        if (!$info || !in_array($info['mime'] ?? '', ['image/png', 'image/jpeg', 'image/webp', 'image/gif'], true)) {
            return ['ok' => false, 'error' => 'Upload a PNG, JPG, WebP or GIF image.'];
        }
        $cloud = \App\Core\Env::get('CLOUDINARY_CLOUD_NAME');
        $key = \App\Core\Env::get('CLOUDINARY_API_KEY');
        $secret = \App\Core\Env::get('CLOUDINARY_API_SECRET');
        if ($cloud === '' || $key === '' || $secret === '') {
            return ['ok' => false, 'error' => 'Image hosting (Cloudinary) is not configured.'];
        }
        $params = ['folder' => 'InternConnect/images/newsletter', 'timestamp' => (string)time()];
        ksort($params);
        $toSign = implode('&', array_map(static fn ($k, $v) => $k . '=' . $v, array_keys($params), $params));
        $params['signature'] = sha1($toSign . $secret);
        $params['api_key'] = $key;
        $params['file'] = new \CURLFile((string)$file['tmp_name'], (string)$info['mime'], 'campaign-image');
        $ch = curl_init('https://api.cloudinary.com/v1_1/' . rawurlencode($cloud) . '/image/upload');
        curl_setopt_array($ch, [CURLOPT_POST => true, CURLOPT_POSTFIELDS => $params, CURLOPT_RETURNTRANSFER => true, CURLOPT_TIMEOUT => 40]);
        $raw = curl_exec($ch);
        $status = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        $res = json_decode((string)$raw, true) ?: [];
        if ($status !== 200 || empty($res['secure_url'])) {
            error_log('Cloudinary upload failed: HTTP ' . $status . ' ' . substr((string)$raw, 0, 200));
            return ['ok' => false, 'error' => 'The image could not be uploaded. Please try again.'];
        }
        // Delivered as an optimised JPG/PNG, at most 1200 px wide (e-mail clients do not all support WebP).
        $url = preg_replace('#/image/upload/#', '/image/upload/f_auto,q_auto,w_1200,c_limit/', (string)$res['secure_url'], 1);
        $url = str_replace('f_auto', ($info['mime'] === 'image/png' ? 'f_png' : 'f_jpg'), $url);
        return ['ok' => true, 'url' => $url, 'width' => (int)($res['width'] ?? 0), 'height' => (int)($res['height'] ?? 0)];
    }

    /**
     * Jenny drafts a campaign from a short brief. Returns ['ok', 'draft' => fields, 'tips' => [...], 'source'].
     */
    public static function jennyDraft(string $type, string $audience, string $brief, string $tone): array
    {
        $type = array_key_exists($type, self::TYPES) ? $type : 'general';
        $audience = array_key_exists($audience, self::AUDIENCES) ? $audience : 'all';
        $brief = mb_substr(trim(strip_tags($brief)), 0, 1500);
        $tone = in_array($tone, ['friendly', 'professional', 'exciting', 'urgent'], true) ? $tone : 'friendly';
        if (mb_strlen($brief) < 5) {
            return ['ok' => false, 'error' => 'Tell Jenny in a sentence what the campaign is about.'];
        }
        $listings = array_map(static fn ($l) => $l['id'] . ' | ' . $l['title'] . ' | ' . $l['company'] . ' | ' . $l['location'] . ' | ' . $l['tag'], require dirname(__DIR__) . '/Data/internships.php');
        $draft = null;
        if (GeminiClient::isConfigured()) {
            $draft = GeminiClient::json(
                "You are Jenny, the e-mail copywriter for InternConnect, a global internship and early-career platform (students, graduates and employers in every country).\n"
                . "Write a newsletter campaign.\nType: " . self::TYPES[$type][0] . ' (' . self::TYPES[$type][1] . ")\nAudience: " . self::AUDIENCES[$audience] . "\nTone: {$tone}\nBrief: {$brief}\n\n"
                . "Rules: plain text only. Body: short paragraphs separated by a blank line; a line starting with '## ' is a sub-heading; lines starting with '- ' are bullets (for marketing and employer campaigns give 3-4 bullets: benefits). No emojis, no invented statistics, no gender assumptions. Subject under 60 characters; preview text under 90 characters. Button link must be a page path from: /internships, /pricing, /elearning, /register/student, /register/employer, /employer/post-internship, /how-it-works, /contact.\n"
                . ($type === 'interns' ? "Pick 2-3 internship ids that fit the brief from this list (id | title | company | location | mode):\n" . implode("\n", $listings) . "\n" : '')
                . 'Return JSON only: {"subject": "", "preheader": "", "title": "", "body": "", "cta_label": "", "cta_url": "/path", "jenny_tip": "one practical tip, or empty", "listing_ids": [], "tips": ["2-4 short suggestions for the admin about sending this campaign (timing, audience, subject testing)"]}',
                25, [], 1500, true
            );
        }
        $source = 'gemini';
        if (!is_array($draft) || empty($draft['subject']) || empty($draft['body'])) {
            $source = 'rules';
            $draft = self::templateDraft($type, $brief);
        }
        $clean = static fn ($v, int $max) => mb_substr(trim(strip_tags((string)$v)), 0, $max);
        $path = (string)($draft['cta_url'] ?? '');
        return ['ok' => true, 'source' => $source, 'draft' => [
            'subject'   => $clean($draft['subject'] ?? '', 200),
            'preheader' => $clean($draft['preheader'] ?? '', 200),
            'title'     => $clean($draft['title'] ?? '', 200),
            'body'      => mb_substr(trim(strip_tags((string)($draft['body'] ?? ''))), 0, 20000),
            'cta_label' => $clean($draft['cta_label'] ?? '', 60),
            'cta_url'   => preg_match('#^/[a-z0-9/\-]*$#i', $path) ? $path : '/internships',
            'jenny_tip' => $clean($draft['jenny_tip'] ?? '', 600),
            'listings'  => array_values(array_slice(array_intersect((array)($draft['listing_ids'] ?? []), array_column(require dirname(__DIR__) . '/Data/internships.php', 'id')), 0, 4)),
        ], 'tips' => array_values(array_slice(array_map(static fn ($t) => mb_substr(trim(strip_tags((string)$t)), 0, 200), (array)($draft['tips'] ?? [])), 0, 4))];
    }

    /** Built-in drafts when Gemini is unavailable. */
    private static function templateDraft(string $type, string $brief): array
    {
        $t = [
            'marketing' => ['subject' => 'Your next internship starts here', 'title' => 'Get hired faster with InternConnect', 'body' => $brief . "\n\n## Why students choose InternConnect\n- Verified internships from real companies\n- Jenny AI helps with your CV and cover letters\n- Train with partners and earn certificates", 'cta_label' => 'Explore internships', 'cta_url' => '/internships'],
            'event'     => ['subject' => 'You are invited', 'title' => 'Join us live', 'body' => $brief . "\n\nSave your seat and bring your questions.", 'cta_label' => 'Register now', 'cta_url' => '/contact'],
            'interns'   => ['subject' => 'New internships picked for you', 'title' => 'Fresh roles this week', 'body' => $brief . "\n\nHere are a few roles worth a look before they close.", 'cta_label' => 'See all internships', 'cta_url' => '/internships'],
            'employers' => ['subject' => 'Hire great interns, faster', 'title' => 'Your next intern is on InternConnect', 'body' => $brief . "\n\n## What you get\n- Verified, motivated candidates\n- AI summaries of every application\n- Video interviews inside the platform", 'cta_label' => 'Post an internship', 'cta_url' => '/employer/post-internship'],
            'general'   => ['subject' => 'News from InternConnect', 'title' => 'What is new on InternConnect', 'body' => $brief . "\n\nThank you for being part of InternConnect.", 'cta_label' => 'Open InternConnect', 'cta_url' => '/'],
        ][$type];
        return $t + ['preheader' => '', 'jenny_tip' => '', 'listing_ids' => [], 'tips' => ['Send a test to yourself first and read it on your phone.', 'Tuesday to Thursday mornings usually get the most opens.']];
    }
}
