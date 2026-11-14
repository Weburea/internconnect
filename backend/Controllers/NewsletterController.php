<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Services\ContactService;
use App\Services\MailService;
use App\Services\NewsletterService;
use App\Services\RateLimiter;

/**
 * Public newsletter + contact endpoints (docs/54-email-newsletter-contact.md).
 * POST /newsletter/subscribe            {email, source?}  JSON
 * GET  /newsletter/confirm/{token}      double opt-in link
 * GET  /newsletter/unsubscribe/{token}  page with a confirm button (POST, CSRF)
 * POST /newsletter/one-click/{token}    RFC 8058 one-click (mail providers, no CSRF: token-authorised)
 * GET  /m/o/{token}                     open pixel
 * POST /contact                         contact form JSON
 */
class NewsletterController extends Controller
{
    private function input(): array
    {
        $raw = file_get_contents('php://input');
        $json = $raw ? json_decode($raw, true) : null;
        return is_array($json) ? $json : $_POST;
    }

    public function subscribe(): void
    {
        $in = $this->input();
        // Bots fill the hidden "website" field.
        if (!empty($in['website'])) {
            $this->jsonResponse(['success' => true, 'state' => 'pending', 'message' => 'Almost done. Check your inbox and confirm your subscription.']);
            return;
        }
        $limit = RateLimiter::hit('newsletter:' . RateLimiter::ip(), 8, 3600);
        if (!$limit['allowed']) {
            header('Retry-After: ' . $limit['retry_after']);
            $this->jsonResponse(['success' => false, 'error' => 'Too many attempts. Please try again later.'], 429);
            return;
        }
        $r = NewsletterService::subscribe((string)($in['email'] ?? ''), (string)($in['source'] ?? 'website'));
        $this->jsonResponse($r['ok'] ? ['success' => true, 'state' => $r['state'], 'message' => $r['message']] : ['success' => false, 'error' => $r['message']], $r['ok'] ? 200 : 422);
    }

    public function confirm(array $params = []): void
    {
        $row = NewsletterService::confirm((string)($params['token'] ?? ''));
        $this->page($row ? 'confirmed' : 'invalid', $row);
    }

    public function unsubscribePage(array $params = []): void
    {
        $token = (string)($params['token'] ?? '');
        $row = null;
        if (preg_match('/^[a-f0-9]{40}$/', $token)) {
            NewsletterService::ensureTables();
            $stmt = \App\Core\Database::getConnection()->prepare('SELECT email, status FROM newsletter_subscribers WHERE token = ?');
            $stmt->execute([$token]);
            $row = $stmt->fetch(\PDO::FETCH_ASSOC) ?: null;
        }
        if (!$row) {
            $this->page('invalid', null);
            return;
        }
        $this->page($row['status'] === 'unsubscribed' ? 'unsubscribed' : 'confirm-unsubscribe', $row + ['token' => $token]);
    }

    public function unsubscribe(array $params = []): void
    {
        $row = NewsletterService::unsubscribe((string)($params['token'] ?? ''));
        $this->page($row ? 'unsubscribed' : 'invalid', $row);
    }

    public function oneClick(array $params = []): void
    {
        NewsletterService::unsubscribe((string)($params['token'] ?? ''));
        http_response_code(200);
        header('Content-Type: text/plain; charset=utf-8');
        echo 'Unsubscribed';
    }

    private function page(string $state, ?array $row): void
    {
        $this->renderView('landing/newsletter-status', [
            'title'   => 'Newsletter — InternConnect',
            'state'   => $state,
            'email'   => $row['email'] ?? null,
            'token'   => $row['token'] ?? null,
            'noindex' => true,
        ]);
    }

    public function pixel(array $params = []): void
    {
        MailService::markOpened(preg_replace('/\.gif$/', '', (string)($params['token'] ?? '')) ?? '');
        header('Content-Type: image/gif');
        header('Cache-Control: no-store, max-age=0');
        echo base64_decode('R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7');
    }

    public function contact(): void
    {
        $in = $this->input();
        if (!empty($in['website'])) {
            $this->jsonResponse(['success' => true, 'reference' => 'IC-' . strtoupper(bin2hex(random_bytes(3)))]);
            return;
        }
        $limit = RateLimiter::hit('contact:' . RateLimiter::ip(), 5, 3600);
        if (!$limit['allowed']) {
            header('Retry-After: ' . $limit['retry_after']);
            $this->jsonResponse(['success' => false, 'error' => 'You have sent several messages in the last hour. Please try again later.'], 429);
            return;
        }
        $r = ContactService::submit($in);
        if (!$r['ok']) {
            $this->jsonResponse(['success' => false, 'error' => reset($r['errors']), 'errors' => $r['errors']], 422);
            return;
        }
        $this->jsonResponse(['success' => true, 'reference' => $r['reference']]);
    }
}
