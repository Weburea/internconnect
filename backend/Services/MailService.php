<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\Database;
use App\Core\Env;
use PDO;
use PHPMailer\PHPMailer\Exception as MailException;
use PHPMailer\PHPMailer\PHPMailer;
use Throwable;

/**
 * E-mail (docs/54-email-newsletter-contact.md).
 *
 * Every message goes through `mail_queue`, so nothing is lost when SMTP is down and admin can see
 * what was sent. Transactional mail (confirmations, contact replies) is sent at once and retried by
 * the cron if that fails; bulk newsletter mail is sent by the cron in batches (`processQueue`).
 * SMTP settings come from .env (MAIL_*). Without MAIL_PASSWORD nothing is sent and messages wait in
 * the queue, so the site still works on a machine without mail.
 */
final class MailService
{
    public const MAX_ATTEMPTS = 5;
    private static bool $ready = false;
    /** Last SMTP error (admin test send shows it). */
    public static string $lastError = '';

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
            $pdo->exec('CREATE TABLE IF NOT EXISTS mail_queue (
                id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY, to_email VARCHAR(190) NOT NULL, to_name VARCHAR(120) NULL,
                subject VARCHAR(200) NOT NULL, html MEDIUMTEXT NOT NULL, text_body MEDIUMTEXT NULL, category VARCHAR(40) NOT NULL,
                campaign_id BIGINT UNSIGNED NULL, status VARCHAR(16) NOT NULL DEFAULT \'queued\', attempts TINYINT UNSIGNED NOT NULL DEFAULT 0,
                last_error VARCHAR(255) NULL, track_token CHAR(32) NOT NULL, opened_at DATETIME NULL, sent_at DATETIME NULL,
                send_after DATETIME NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL,
                UNIQUE KEY uq_mail_track (track_token), INDEX idx_mail_status (status, send_after), INDEX idx_mail_campaign (campaign_id, status)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');
        } else {
            $pdo->exec('CREATE TABLE IF NOT EXISTS mail_queue (id INTEGER PRIMARY KEY AUTOINCREMENT, to_email TEXT NOT NULL, to_name TEXT,
                subject TEXT NOT NULL, html TEXT NOT NULL, text_body TEXT, category TEXT NOT NULL, campaign_id INTEGER,
                status TEXT NOT NULL DEFAULT \'queued\', attempts INTEGER NOT NULL DEFAULT 0, last_error TEXT, track_token TEXT NOT NULL UNIQUE,
                opened_at TEXT, sent_at TEXT, send_after TEXT NOT NULL, created_at TEXT NOT NULL, updated_at TEXT NOT NULL)');
            $pdo->exec('CREATE INDEX IF NOT EXISTS idx_mail_status ON mail_queue (status, send_after)');
            $pdo->exec('CREATE INDEX IF NOT EXISTS idx_mail_campaign ON mail_queue (campaign_id, status)');
        }
        self::$ready = true;
    }

    public static function configured(): bool
    {
        return Env::get('MAIL_HOST') !== '' && Env::get('MAIL_USERNAME') !== '' && Env::get('MAIL_PASSWORD') !== '';
    }

    /** Public link for e-mails (APP_URL when set, so cron mail never points at localhost by accident). */
    public static function link(string $path): string
    {
        $base = rtrim(Env::get('APP_URL'), '/');
        return $base !== '' ? $base . '/' . ltrim($path, '/') : absolute_url($path);
    }

    /**
     * Render an e-mail: frontend/emails/{view}.php inside frontend/emails/layout.php.
     * $data is available to both; the layout reads $preheader, $hero, $unsubscribeUrl.
     */
    public static function render(string $view, array $data = []): string
    {
        $dir = dirname(__DIR__, 2) . '/frontend/emails';
        $data += ['preheader' => '', 'hero' => null, 'badge' => null, 'footer' => null, 'unsubscribeUrl' => null, 'trackUrl' => null];
        extract($data, EXTR_SKIP);
        ob_start();
        include $dir . '/' . $view . '.php';
        $content = (string)ob_get_clean();
        ob_start();
        include $dir . '/layout.php';
        return (string)ob_get_clean();
    }

    /** Plain-text version of an HTML e-mail (multipart improves deliverability). */
    public static function toText(string $html): string
    {
        $html = preg_replace('#<(style|head)[^>]*>.*?</\1>#is', '', $html) ?? $html;
        $html = preg_replace('#<a [^>]*href="([^"]+)"[^>]*>(.*?)</a>#is', '$2 ($1)', $html) ?? $html;
        $html = preg_replace('#<(br|/p|/tr|/h[1-6]|/li)[^>]*>#i', "\n", $html) ?? $html;
        $text = html_entity_decode(strip_tags($html), ENT_QUOTES, 'UTF-8');
        $text = preg_replace("/[ \t]+/", ' ', $text) ?? $text;
        return trim(preg_replace("/\n\s*\n+/", "\n\n", $text) ?? $text);
    }

    /**
     * Put a message in the queue. $html may contain {{track}} where the open pixel goes.
     * Returns the queue id.
     */
    public static function queue(string $to, string $subject, string $html, string $category, ?string $toName = null, ?int $campaignId = null, ?string $text = null): int
    {
        self::ensureTables();
        if (!filter_var($to, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException('Invalid recipient e-mail address.');
        }
        $token = bin2hex(random_bytes(16));
        $html = str_replace('{{track}}', self::link('/m/o/' . $token . '.gif'), $html);
        $now = date('Y-m-d H:i:s');
        $pdo = self::pdo();
        $pdo->prepare('INSERT INTO mail_queue (to_email, to_name, subject, html, text_body, category, campaign_id, status, attempts, track_token, send_after, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 0, ?, ?, ?, ?)')
            ->execute([strtolower(trim($to)), $toName, mb_substr($subject, 0, 200), $html, $text ?? self::toText($html), $category, $campaignId, 'queued', $token, $now, $now, $now]);
        return (int)$pdo->lastInsertId();
    }

    /** Queue and try to send right away (transactional). True when it left the server now. */
    public static function sendNow(string $to, string $subject, string $html, string $category, ?string $toName = null): bool
    {
        $id = self::queue($to, $subject, $html, $category, $toName);
        return self::deliver($id);
    }

    /** Send one queued message. */
    public static function deliver(int $id): bool
    {
        self::ensureTables();
        $stmt = self::pdo()->prepare("SELECT * FROM mail_queue WHERE id = ? AND status IN ('queued', 'failed')");
        $stmt->execute([$id]);
        $m = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$m) {
            return false;
        }
        if (!self::configured()) {
            self::$lastError = 'Mail is not configured: add MAIL_PASSWORD to .env.';
            self::mark($id, 'queued', (int)$m['attempts'], self::$lastError);
            return false;
        }
        $attempts = (int)$m['attempts'] + 1;
        try {
            $mail = self::mailer();
            $mail->addAddress($m['to_email'], (string)($m['to_name'] ?? ''));
            $mail->Subject = $m['subject'];
            $mail->Body = $m['html'];
            $mail->AltBody = (string)($m['text_body'] ?? '');
            if (in_array($m['category'], ['newsletter', 'campaign'], true)) {
                // One-click unsubscribe headers (RFC 8058) that Gmail and Yahoo expect for bulk mail.
                $oneClick = NewsletterService::oneClickUrlFor($m['to_email']);
                if ($oneClick) {
                    $mail->addCustomHeader('List-Unsubscribe', '<' . $oneClick . '>');
                    $mail->addCustomHeader('List-Unsubscribe-Post', 'List-Unsubscribe=One-Click');
                }
            }
            $mail->send();
            self::mark($id, 'sent', $attempts, null, true);
            self::$lastError = '';
            return true;
        } catch (MailException | Throwable $e) {
            self::$lastError = mb_substr($e->getMessage(), 0, 250);
            error_log('MailService #' . $id . ': ' . self::$lastError);
            // Retry later with back-off; give up after MAX_ATTEMPTS.
            $status = $attempts >= self::MAX_ATTEMPTS ? 'failed' : 'queued';
            self::mark($id, $status, $attempts, self::$lastError, false, date('Y-m-d H:i:s', time() + 60 * (2 ** $attempts)));
            return false;
        }
    }

    private static function mark(int $id, string $status, int $attempts, ?string $error, bool $sent = false, ?string $sendAfter = null): void
    {
        $now = date('Y-m-d H:i:s');
        self::pdo()->prepare('UPDATE mail_queue SET status = ?, attempts = ?, last_error = ?, sent_at = ' . ($sent ? '?' : 'sent_at') . ', send_after = COALESCE(?, send_after), updated_at = ? WHERE id = ?')
            ->execute(array_merge([$status, $attempts, $error], $sent ? [$now] : [], [$sendAfter, $now, $id]));
    }

    private static function mailer(): PHPMailer
    {
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = Env::get('MAIL_HOST');
        $mail->Port = (int)Env::get('MAIL_PORT', '465');
        $mail->SMTPAuth = true;
        $mail->Username = Env::get('MAIL_USERNAME');
        $mail->Password = Env::get('MAIL_PASSWORD');
        $enc = strtolower(Env::get('MAIL_ENCRYPTION', 'ssl'));
        $mail->SMTPSecure = $enc === 'tls' ? PHPMailer::ENCRYPTION_STARTTLS : PHPMailer::ENCRYPTION_SMTPS;
        $mail->Timeout = 20;
        $mail->CharSet = PHPMailer::CHARSET_UTF8;
        $mail->isHTML(true);
        $from = Env::get('MAIL_FROM_ADDRESS', Env::get('MAIL_USERNAME'));
        $mail->setFrom($from, Env::get('MAIL_FROM_NAME', 'InternConnect'));
        $mail->Sender = $from;
        $reply = Env::get('MAIL_REPLY_TO');
        if ($reply !== '') {
            $mail->addReplyTo($reply, Env::get('MAIL_FROM_NAME', 'InternConnect'));
        }
        $mail->XMailer = 'InternConnect';
        return $mail;
    }

    /** Cron / admin: send what is due. Returns ['sent' => n, 'failed' => n, 'left' => n]. */
    public static function processQueue(int $limit = 40): array
    {
        self::ensureTables();
        $out = ['sent' => 0, 'failed' => 0, 'left' => 0];
        if (!self::configured()) {
            $out['left'] = self::countStatus('queued');
            return $out;
        }
        $stmt = self::pdo()->prepare("SELECT id FROM mail_queue WHERE status = 'queued' AND send_after <= ? ORDER BY id LIMIT " . max(1, min(500, $limit)));
        $stmt->execute([date('Y-m-d H:i:s')]);
        foreach ($stmt->fetchAll(PDO::FETCH_COLUMN) as $id) {
            self::deliver((int)$id) ? $out['sent']++ : $out['failed']++;
        }
        $out['left'] = self::countStatus('queued');
        return $out;
    }

    public static function countStatus(string $status, ?int $campaignId = null): int
    {
        self::ensureTables();
        $sql = 'SELECT COUNT(*) FROM mail_queue WHERE status = ?' . ($campaignId !== null ? ' AND campaign_id = ?' : '');
        $stmt = self::pdo()->prepare($sql);
        $stmt->execute($campaignId !== null ? [$status, $campaignId] : [$status]);
        return (int)$stmt->fetchColumn();
    }

    /** Open pixel: first open time per message. */
    public static function markOpened(string $token): void
    {
        if (!preg_match('/^[a-f0-9]{32}$/', $token)) {
            return;
        }
        self::ensureTables();
        self::pdo()->prepare('UPDATE mail_queue SET opened_at = ? WHERE track_token = ? AND opened_at IS NULL')->execute([date('Y-m-d H:i:s'), $token]);
    }

    /** Admin monitoring: totals and the latest messages. */
    public static function stats(): array
    {
        self::ensureTables();
        $rows = self::pdo()->query('SELECT status, COUNT(*) c FROM mail_queue GROUP BY status')->fetchAll(PDO::FETCH_KEY_PAIR);
        $opened = (int)self::pdo()->query('SELECT COUNT(*) FROM mail_queue WHERE opened_at IS NOT NULL')->fetchColumn();
        return ['queued' => (int)($rows['queued'] ?? 0), 'sent' => (int)($rows['sent'] ?? 0), 'failed' => (int)($rows['failed'] ?? 0), 'opened' => $opened, 'configured' => self::configured()];
    }

    public static function recent(int $limit = 30, ?string $category = null): array
    {
        self::ensureTables();
        $sql = 'SELECT id, to_email, subject, category, status, attempts, last_error, sent_at, opened_at, created_at FROM mail_queue' . ($category ? ' WHERE category = ?' : '') . ' ORDER BY id DESC LIMIT ' . max(1, min(200, $limit));
        $stmt = self::pdo()->prepare($sql);
        $stmt->execute($category ? [$category] : []);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /** Cloudinary images used by the e-mail templates (folder "InternConnect", delivered as PNG/JPG for mail clients). */
    public static function image(string $name, int $width = 1200): string
    {
        $cloud = Env::get('CLOUDINARY_CLOUD_NAME', 'weburea');
        $map = [
            'logo'        => ['internConnect/images/logo/internconnect-icon.svg', 'f_png'],
            'celebrate'   => ['Celebrating_success_ouxlqf.png', 'f_jpg,q_auto,c_fill,ar_5:2,g_center'],
            'jenny_hi'    => ['internConnect/images/jenny_ai/jenny_ai_hi.png', 'f_png'],
            'jenny_point' => ['internConnect/images/jenny_ai/Pointing_jenny_ai.png', 'f_png'],
            'devices'     => ['internConnect/images/company/internconnect_multi_device_mockup.png', 'f_jpg,q_auto,c_fill,ar_2:1,g_center'],
            'focused'     => ['Woman_working_focused_sqvdeg.png', 'f_jpg,q_auto,c_fill,ar_2:1,g_auto'],
            'elearning'   => ['ipad_e-learn_aamnea.png', 'f_jpg,q_auto,c_fill,ar_2:1,g_center'],
            'banner'      => ['internconnect_banner_img_ik0cyy.png', 'f_jpg,q_auto,c_fill,ar_2:1,g_center'],
            // E-mail banners (scratch design, InternConnect/images/emails): 1200x400, footer band 1200x96.
            'email_welcome' => ['v1794577027/InternConnect/images/emails/email_welcome.jpg', 'f_jpg,q_auto'],
            'email_verify'  => ['v1794577029/InternConnect/images/emails/email_verify.jpg', 'f_jpg,q_auto'],
            'email_reset'   => ['v1794577032/InternConnect/images/emails/email_reset.jpg', 'f_jpg,q_auto'],
            'email_updates' => ['v1794577034/InternConnect/images/emails/email_updates.jpg', 'f_jpg,q_auto'],
            'email_footer'  => ['v1794577036/InternConnect/images/emails/email_footer.png', 'f_png'],
            'email_event'       => ['v1794581172/InternConnect/images/emails/email_event.jpg', 'f_jpg,q_auto'],
            'email_internships' => ['v1794581174/InternConnect/images/emails/email_internships.jpg', 'f_jpg,q_auto'],
            'email_employers'   => ['v1794581176/InternConnect/images/emails/email_employers.jpg', 'f_jpg,q_auto'],
            'email_training'    => ['v1794581178/InternConnect/images/emails/email_training.jpg', 'f_jpg,q_auto'],
            'email_offer'       => ['v1794581180/InternConnect/images/emails/email_offer.jpg', 'f_jpg,q_auto'],
            'email_footer_logo'  => ['v1794581182/InternConnect/images/emails/email_footer_logo.png', 'f_png'],
            'email_footer_waves' => ['v1794581183/InternConnect/images/emails/email_footer_waves.png', 'f_png'],
            'email_footer_dots'  => ['v1794581185/InternConnect/images/emails/email_footer_dots.png', 'f_png'],
        ];
        [$id, $t] = $map[$name] ?? $map['banner'];
        $path = preg_replace('/\.(svg|png|jpg|webp)$/', '', $id);
        $ext = str_contains($t, 'f_png') ? 'png' : 'jpg';
        return 'https://res.cloudinary.com/' . $cloud . '/image/upload/' . $t . ',w_' . $width . '/' . $path . '.' . $ext;
    }
}
