<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Services\ContactService;
use App\Services\MailService;
use App\Services\NewsletterService;

/**
 * Admin > Communications (docs/54-email-newsletter-contact.md):
 * newsletter campaigns (compose, preview, test, send in batches), subscribers, contact inbox, mail log.
 */
class AdminCommunicationsController extends AdminController
{
    private const TABS = ['campaigns', 'compose', 'subscribers', 'inbox', 'mail'];

    private function flash(bool $ok, string $text): void
    {
        $_SESSION['admin_comms_flash'] = ['ok' => $ok, 'text' => $text];
    }

    private function back(string $tab, array $query = []): void
    {
        $this->redirect('/admin/communications?' . http_build_query(['tab' => $tab] + $query));
    }

    private function adminName(): string
    {
        return (string)($_SESSION['admin_name'] ?? 'InternConnect admin');
    }

    public function index(): void
    {
        $this->requireAdminAuth();
        $tab = in_array($_GET['tab'] ?? '', self::TABS, true) ? $_GET['tab'] : 'campaigns';
        $page = max(1, (int)($_GET['page'] ?? 1));
        $flash = $_SESSION['admin_comms_flash'] ?? null;
        unset($_SESSION['admin_comms_flash']);

        $data = [
            'title'     => 'Communications — InternConnect Admin',
            'staff'     => $this->staffProfile(),
            'activeNav' => 'communications',
            'tab'       => $tab,
            'flash'     => $flash,
            'mailStats' => MailService::stats(),
            'subStats'  => NewsletterService::stats(),
            'inboxCounts' => ContactService::counts(),
        ];
        if ($tab === 'campaigns') {
            $data['campaigns'] = NewsletterService::campaigns(50);
        } elseif ($tab === 'compose') {
            $id = (int)($_GET['id'] ?? 0);
            $data['campaign'] = $id ? NewsletterService::campaign($id) : null;
            $data['draft'] = $_SESSION['admin_comms_draft'] ?? null;
            unset($_SESSION['admin_comms_draft']);
            $data['listingOptions'] = array_map(static fn ($l) => ['id' => $l['id'], 'title' => $l['title'], 'company' => $l['company'], 'location' => $l['location']], require dirname(__DIR__) . '/Data/internships.php');
            $data['audienceCounts'] = array_map(static fn ($a) => NewsletterService::audienceCount($a), array_combine(array_keys(NewsletterService::AUDIENCES), array_keys(NewsletterService::AUDIENCES)));
            $data['footerLibrary'] = [];
            foreach (NewsletterService::FOOTERS as $key => $label) {
                $data['footerLibrary'][] = ['key' => $key, 'label' => $label, 'thumb' => MailService::image($key === 'circles' ? 'email_footer' : 'email_footer_' . $key, 600)];
            }
            $data['library'] = [];
            foreach (NewsletterService::HEROES as $key => $label) {
                if ($key !== '') {
                    $data['library'][] = ['key' => $key, 'label' => $label, 'thumb' => MailService::image($key, 360)];
                }
            }
        } elseif ($tab === 'subscribers') {
            $status = (string)($_GET['status'] ?? '');
            $q = mb_substr(trim((string)($_GET['q'] ?? '')), 0, 100);
            $data += ['subscribers' => NewsletterService::subscribers($status, $q, $page, 20), 'status' => $status, 'q' => $q, 'pageNo' => $page];
        } elseif ($tab === 'inbox') {
            $status = (string)($_GET['status'] ?? '');
            $data += ['messages' => ContactService::list($status, $page, 20), 'status' => $status, 'pageNo' => $page];
            $open = (int)($_GET['id'] ?? 0);
            $data['openMessage'] = $open ? ContactService::find($open) : null;
            if ($data['openMessage'] && $data['openMessage']['status'] === 'new') {
                ContactService::setStatus($open, 'open');
                $data['openMessage']['status'] = 'open';
            }
        } else {
            $data['mailLog'] = MailService::recent(50);
        }
        $this->renderView('admin/communications/index', $data, 'admin');
    }

    // ── Campaigns ───────────────────────────────────────────────────────────

    public function saveCampaign(array $params = []): void
    {
        $this->requireAdminAuth();
        $id = isset($params['id']) ? (int)$params['id'] : null;
        $r = NewsletterService::saveCampaign($_POST, $id, $this->adminName());
        if (!$r['ok']) {
            $_SESSION['admin_comms_draft'] = $_POST;
            $this->flash(false, $r['error']);
            $this->back('compose', $id ? ['id' => $id] : []);
            return;
        }
        $this->flash(true, 'Draft saved. Preview it, send yourself a test, then send it to subscribers.');
        $this->back('compose', ['id' => $r['id']]);
    }

    public function preview(array $params = []): void
    {
        $this->requireAdminAuth();
        $c = NewsletterService::campaign((int)($params['id'] ?? 0));
        header('Content-Type: text/html; charset=utf-8');
        // Shown in a sandboxed iframe on the compose tab.
        header('Content-Security-Policy: default-src \'none\'; img-src https: data:; style-src \'unsafe-inline\'');
        echo $c ? NewsletterService::renderCampaign($c, null, false) : '<p style="font-family: sans-serif;">Campaign not found.</p>';
    }

    public function sendTest(array $params = []): void
    {
        $this->requireAdminAuth();
        $id = (int)($params['id'] ?? 0);
        $to = trim((string)($_POST['test_email'] ?? ''));
        if (!filter_var($to, FILTER_VALIDATE_EMAIL)) {
            $this->flash(false, 'Enter a valid e-mail address for the test.');
        } elseif (NewsletterService::sendTest($id, $to)) {
            $this->flash(true, 'Test sent to ' . $to . '. Check the inbox (and the spam folder the first time).');
        } else {
            $this->flash(false, 'The test could not be sent: ' . (MailService::$lastError ?: 'unknown error') . ' It stays in the queue and will be retried.');
        }
        $this->back('compose', ['id' => $id]);
    }

    public function send(array $params = []): void
    {
        $this->requireAdminAuth();
        $id = (int)($params['id'] ?? 0);
        $n = NewsletterService::queueCampaign($id);
        if ($n > 0) {
            $batch = MailService::processQueue(20);
            $this->flash(true, $n . ' e-mails queued; ' . $batch['sent'] . ' sent now. The rest go out in batches (cron every 5 minutes, or "Send next batch").');
        } else {
            $this->flash(false, 'Nothing was queued: the campaign was already sent, or there are no confirmed subscribers yet.');
        }
        $this->back('campaigns');
    }

    public function processQueue(): void
    {
        $this->requireAdminAuth();
        if (!MailService::configured()) {
            $this->flash(false, 'Mail is not configured yet: add MAIL_PASSWORD to .env.');
        } else {
            $r = MailService::processQueue(40);
            $this->flash($r['failed'] === 0, $r['sent'] . ' sent, ' . $r['failed'] . ' failed, ' . $r['left'] . ' still queued.');
        }
        $this->back((string)($_POST['tab'] ?? 'campaigns'));
    }

    public function testMail(): void
    {
        $this->requireAdminAuth();
        $to = trim((string)($_POST['to'] ?? ''));
        if (!filter_var($to, FILTER_VALIDATE_EMAIL)) {
            $this->flash(false, 'Enter a valid e-mail address.');
            $this->back('mail');
            return;
        }
        $ok = MailService::sendNow($to, 'InternConnect mail test', MailService::render('message', [
            'preheader'  => 'Your InternConnect mail settings work.',
            'hero'       => ['src' => MailService::image('devices', 1200), 'alt' => 'InternConnect on laptop, tablet and phone'],
            'title'      => 'Mail is working',
            'paragraphs' => ['This test was sent from Admin > Communications at ' . date('j M Y, g:i A') . '. Newsletter, contact and account e-mails will be delivered the same way.'],
            'details'    => ['Server' => \App\Core\Env::get('MAIL_HOST') . ':' . \App\Core\Env::get('MAIL_PORT'), 'From' => \App\Core\Env::get('MAIL_FROM_ADDRESS')],
        ]), 'test');
        $this->flash($ok, $ok ? 'Test e-mail sent to ' . $to . '.' : 'Not sent: ' . (MailService::$lastError ?: 'unknown error'));
        $this->back('mail');
    }

    /** POST /admin/communications/preview-draft: the branded e-mail for the form as it is now (not saved). */
    public function previewDraft(): void
    {
        $this->requireAdminAuth();
        $in = $_POST;
        $https = static fn ($u) => preg_match('#^https://[^\s"<>]+$#i', (string)$u) ? (string)$u : '';
        $clean = static fn ($v, int $max) => mb_substr(trim(strip_tags((string)$v)), 0, $max);
        $type = array_key_exists((string)($in['type'] ?? ''), NewsletterService::TYPES) ? (string)$in['type'] : 'general';
        $cta = trim((string)($in['cta_url'] ?? ''));
        $c = [
            'type' => $type, 'subject' => $clean($in['subject'] ?? '', 200), 'preheader' => $clean($in['preheader'] ?? '', 200),
            'title' => $clean($in['title'] ?? '', 200) ?: 'Your heading', 'body' => mb_substr(trim(strip_tags((string)($in['body'] ?? ''))), 0, 20000) ?: 'Your message appears here.',
            'hero' => array_key_exists((string)($in['hero'] ?? ''), NewsletterService::HEROES) ? (string)$in['hero'] : '',
            'hero_url' => $https($in['hero_url'] ?? ''), 'body_image_url' => $https($in['body_image_url'] ?? ''),
            'cta_label' => $clean($in['cta_label'] ?? '', 60),
            'cta_url' => $cta === '' ? '' : (str_starts_with($cta, '/') ? MailService::link($cta) : $https($cta)),
            'jenny_tip' => $clean($in['jenny_tip'] ?? '', 600),
            'extra' => ['event_date' => preg_match('/^\d{4}-\d{2}-\d{2}$/', (string)($in['event_date'] ?? '')) ? $in['event_date'] : '',
                'event_time' => preg_match('/^\d{2}:\d{2}$/', (string)($in['event_time'] ?? '')) ? $in['event_time'] : '',
                'event_tz' => $clean($in['event_tz'] ?? '', 20), 'event_place' => $clean($in['event_place'] ?? '', 160),
                'listings' => array_values(array_slice((array)($in['listings'] ?? []), 0, 4)),
                'footer' => array_key_exists((string)($in['footer'] ?? ''), NewsletterService::FOOTERS) ? (string)$in['footer'] : 'circles',
                'button' => array_key_exists((string)($in['button'] ?? ''), NewsletterService::BUTTONS) ? (string)$in['button'] : 'auto'],
        ];
        header('Content-Type: text/html; charset=utf-8');
        echo NewsletterService::renderCampaign($c, null, false);
    }

    /** POST /admin/communications/images: upload a campaign image to Cloudinary (JSON). */
    public function uploadImage(): void
    {
        $this->requireAdminAuth();
        $r = NewsletterService::uploadImage($_FILES['image'] ?? null);
        $this->jsonResponse($r['ok'] ? ['success' => true, 'url' => $r['url']] : ['success' => false, 'error' => $r['error']], $r['ok'] ? 200 : 422);
    }

    /** POST /admin/communications/jenny-draft: Jenny writes a campaign from a brief (JSON). */
    public function jennyDraft(): void
    {
        $this->requireAdminAuth();
        $in = json_decode((string)file_get_contents('php://input'), true) ?: $_POST;
        $r = NewsletterService::jennyDraft((string)($in['type'] ?? 'general'), (string)($in['audience'] ?? 'all'), (string)($in['brief'] ?? ''), (string)($in['tone'] ?? 'friendly'));
        $this->jsonResponse($r['ok'] ? ['success' => true] + $r : ['success' => false, 'error' => $r['error']], $r['ok'] ? 200 : 422);
    }

    public function setAudience(array $params = []): void
    {
        $this->requireAdminAuth();
        $ok = NewsletterService::setAudience((int)($params['id'] ?? 0), (string)($_POST['audience'] ?? ''));
        $this->flash($ok, $ok ? 'Subscriber group updated.' : 'Could not update that subscriber.');
        $this->back('subscribers');
    }

    // ── Subscribers ─────────────────────────────────────────────────────────

    public function exportSubscribers(): void
    {
        $this->requireAdminAuth();
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="newsletter-subscribers-' . date('Y-m-d') . '.csv"');
        echo NewsletterService::exportCsv();
    }

    public function unsubscribe(array $params = []): void
    {
        $this->requireAdminAuth();
        $ok = NewsletterService::adminUnsubscribe((int)($params['id'] ?? 0));
        $this->flash($ok, $ok ? 'Removed from the newsletter.' : 'Subscriber not found.');
        $this->back('subscribers');
    }

    // ── Inbox ───────────────────────────────────────────────────────────────

    public function reply(array $params = []): void
    {
        $this->requireAdminAuth();
        $id = (int)($params['id'] ?? 0);
        $r = ContactService::reply($id, (string)($_POST['reply'] ?? ''), $this->adminName());
        if (!$r['ok']) {
            $this->flash(false, $r['error']);
        } else {
            $this->flash($r['sent'], $r['sent'] ? 'Reply sent.' : 'Reply saved but not sent yet: ' . ($r['error'] ?: 'mail error') . ' It will be retried automatically.');
        }
        $this->back('inbox', ['id' => $id]);
    }

    public function setStatus(array $params = []): void
    {
        $this->requireAdminAuth();
        $id = (int)($params['id'] ?? 0);
        ContactService::setStatus($id, (string)($_POST['status'] ?? ''));
        $this->back('inbox', ['id' => $id]);
    }
}
