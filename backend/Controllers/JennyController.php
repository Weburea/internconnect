<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Services\GeminiClient;
use App\Services\JennyService;
use App\Services\PlanService;
use App\Services\RateLimiter;
use App\Services\UploadStorage;

/**
 * Jenny AI endpoints (docs/55-jenny-ai-chat.md). All POSTs are CSRF-protected (router).
 *
 * POST /jenny/chat                           widget (visitors, candidates, employers)
 * POST /candidate/ai-assistant/chat          AI Assistant workspace (paid candidates, attachments)
 * GET  /candidate/ai-assistant/chats/{id}    one of my conversations (JSON)
 * POST /admin/ai/chat/send                   staff console
 * GET  /admin/ai/chat/sessions/{id}          any conversation (JSON, staff only)
 * GET  /jenny/files/{conversation}/{message} an attachment (owner or staff)
 * POST /jenny/search                         natural-language search on Find Jobs (Pro/Premium)
 */
class JennyController extends Controller
{
    private function input(): array
    {
        if (str_contains(strtolower((string)($_SERVER['CONTENT_TYPE'] ?? '')), 'application/json')) {
            $data = json_decode((string)file_get_contents('php://input'), true);
            return is_array($data) ? $data : [];
        }
        return $_POST;
    }

    private function respond(array $r): void
    {
        $status = (int)($r['status'] ?? 200);
        unset($r['status']);
        if (!$r['ok']) {
            $r = ['success' => false] + $r;
            unset($r['ok']);
            if (!empty($r['upgrade'])) {
                $r['upgrade_url'] = url('/pricing');
            }
        } else {
            unset($r['ok']);
            $r = ['success' => true] + $r;
        }
        $this->jsonResponse($r, $status);
    }

    /** Short burst limit for every channel (on top of plan quotas). */
    private function burst(string $channel): bool
    {
        $hit = RateLimiter::hit('jenny:burst:' . $channel . ':' . session_id() . ':' . RateLimiter::ip(), 12, 60);
        if (!$hit['allowed']) {
            header('Retry-After: ' . $hit['retry_after']);
            $this->jsonResponse(['success' => false, 'error' => 'You are sending messages very fast. Please wait a moment.'], 429);
            return false;
        }
        return true;
    }

    public function chat(): void
    {
        if (!$this->burst('widget')) {
            return;
        }
        $this->respond(JennyService::chat('widget', $this->input(), $_FILES['file'] ?? null));
    }

    public function assistantChat(): void
    {
        if (!$this->burst('assistant')) {
            return;
        }
        $this->respond(JennyService::chat('assistant', $this->input(), $_FILES['file'] ?? null));
    }

    public function assistantConversation(array $params = []): void
    {
        $actor = JennyService::actor('assistant');
        $conv = JennyService::conversation((string)($params['id'] ?? ''), $actor);
        if (!$conv) {
            $this->jsonResponse(['success' => false, 'error' => 'Conversation not found.'], 404);
            return;
        }
        $this->jsonResponse(['success' => true, 'conversation' => $conv['public_id'], 'title' => $conv['title'], 'messages' => $this->publicMessages($conv['public_id'], JennyService::messages((int)$conv['id'], 100))]);
    }

    /** POST /candidate/ai-assistant/chats/{id}/pin (pinned=1|0) */
    public function assistantPin(array $params = []): void
    {
        $ok = JennyService::pinConversation((string)($params['id'] ?? ''), JennyService::actor('assistant'), !empty($_POST['pinned']));
        $this->jsonResponse(['success' => $ok], $ok ? 200 : 404);
    }

    /** POST /candidate/ai-assistant/chats/{id}/delete */
    public function assistantDelete(array $params = []): void
    {
        $ok = JennyService::deleteConversation((string)($params['id'] ?? ''), JennyService::actor('assistant'));
        $this->jsonResponse(['success' => $ok], $ok ? 200 : 404);
    }

    /** Messages for the browser: no storage names. */
    private function publicMessages(string $publicId, array $messages): array
    {
        return array_map(static fn (array $m) => [
            'id'         => (int)$m['id'],
            'role'       => $m['role'],
            'content'    => $m['content'],
            'links'      => $m['links'],
            'source'     => $m['source'],
            'time'       => date('g:i A', strtotime($m['created_at'])),
            'attachment' => $m['attachment'] ? ['name' => $m['attachment']['name'], 'size' => $m['attachment']['size'], 'url' => url('/jenny/files/' . $publicId . '/' . (int)$m['id'])] : null,
        ], $messages);
    }

    public function file(array $params = []): void
    {
        $isAdmin = !empty($_SESSION['admin_logged_in']);
        $actor = JennyService::actor($isAdmin && str_starts_with((string)($_SERVER['HTTP_REFERER'] ?? ''), absolute_url('/admin')) ? 'admin' : 'widget');
        $meta = JennyService::attachmentFor((string)($params['conversation'] ?? ''), (int)($params['message'] ?? 0), $actor);
        if (!$meta && $isAdmin) {
            $meta = JennyService::attachmentFor((string)($params['conversation'] ?? ''), (int)($params['message'] ?? 0), JennyService::actor('admin'));
        }
        if (!$meta) {
            http_response_code(404);
            echo 'Not found';
            return;
        }
        UploadStorage::stream($meta, false);
    }

    // ── Admin ───────────────────────────────────────────────────────────────

    private function requireAdmin(): bool
    {
        if (empty($_SESSION['admin_logged_in'])) {
            $this->jsonResponse(['success' => false, 'error' => 'Admin sign-in required.'], 401);
            return false;
        }
        return true;
    }

    public function adminSend(): void
    {
        if (!$this->requireAdmin() || !$this->burst('admin')) {
            return;
        }
        $this->respond(JennyService::chat('admin', $this->input(), $_FILES['file'] ?? null));
    }

    public function adminSession(array $params = []): void
    {
        if (!$this->requireAdmin()) {
            return;
        }
        $conv = JennyService::adminConversation((string)($params['id'] ?? ''));
        if (!$conv) {
            $this->jsonResponse(['success' => false, 'error' => 'Conversation not found.'], 404);
            return;
        }
        $messages = $this->publicMessages($conv['public_id'], $conv['messages']);
        unset($conv['messages'], $conv['owner_key'], $conv['id']);
        $this->jsonResponse(['success' => true, 'conversation' => $conv, 'messages' => $messages]);
    }

    // ── Search assist (Find Jobs) ───────────────────────────────────────────

    /**
     * Turn a sentence ("remote design internship in London paying over 250k") into Find Jobs filters.
     * Pro: 10 a day, Premium: unlimited, Free: upgrade prompt (PlanService "ai_search").
     */
    public function search(): void
    {
        $in = $this->input();
        $q = trim(mb_substr(strip_tags((string)($in['q'] ?? '')), 0, 300));
        if ($q === '') {
            $this->jsonResponse(['success' => false, 'error' => 'Describe the internship you are looking for.'], 422);
            return;
        }
        if (empty($_SESSION['is_logged_in'])) {
            $this->jsonResponse(['success' => false, 'error' => 'Sign in on a Pro or Premium plan to let Jenny search for you.', 'code' => 'sign_in', 'upgrade' => true, 'upgrade_url' => url('/login')], 401);
            return;
        }
        $id = (int)($_SESSION['candidate_id'] ?? 1);
        $check = PlanService::check('candidate', $id, 'ai_search');
        if (!$check['ok']) {
            $this->jsonResponse(['success' => false, 'error' => $check['message'], 'code' => 'plan_limit', 'upgrade' => $check['upgrade'], 'upgrade_url' => url('/pricing?plan=pro&ref=jenny-search'), 'usage' => $check['usage']], $check['usage']['limit'] === 0 ? 403 : 429);
            return;
        }
        $filters = null;
        $source = 'rules';
        if (GeminiClient::isConfigured()) {
            $filters = GeminiClient::json(
                "Convert this internship search request from a student (anywhere in the world) into filters. Request: \"{$q}\"\n"
                . "Categories: ENGINEERING, DESIGN, MARKETING, FINANCE, DATA, BUSINESS, PRODUCT, OPERATIONS. Work modes: REMOTE, HYBRID, ON SITE.\n"
                . 'Return JSON only: {"keywords": "1-3 key words for the role or skill, or empty", "location": "city or empty", "category": "one category or empty", "work_mode": "REMOTE|HYBRID|ON SITE or empty", "min_stipend": number in naira or 0, "summary": "one short sentence describing what you searched for"}',
                12, [], 300
            );
            $source = is_array($filters) ? 'gemini' : 'rules';
        }
        if (!is_array($filters)) {
            $filters = self::ruleFilters($q);
        }
        $clean = [
            'keywords'    => mb_substr(trim((string)($filters['keywords'] ?? '')), 0, 60),
            'location'    => mb_substr(trim((string)($filters['location'] ?? '')), 0, 60),
            'category'    => strtoupper(trim((string)($filters['category'] ?? ''))),
            'work_mode'   => in_array(strtoupper((string)($filters['work_mode'] ?? '')), ['REMOTE', 'HYBRID', 'ON SITE'], true) ? strtoupper((string)$filters['work_mode']) : '',
            'min_stipend' => max(0, min(2000000, (int)($filters['min_stipend'] ?? 0))),
            'summary'     => mb_substr(trim(strip_tags((string)($filters['summary'] ?? ''))), 0, 160),
        ];
        $usage = PlanService::consume('candidate', $id, 'ai_search')['usage'];
        $this->jsonResponse(['success' => true, 'filters' => $clean, 'source' => $source, 'usage' => $usage]);
    }

    private static function ruleFilters(string $q): array
    {
        $l = strtolower($q);
        $mode = str_contains($l, 'remote') ? 'REMOTE' : (str_contains($l, 'hybrid') ? 'HYBRID' : (preg_match('/on[ -]?site|in[ -]office/', $l) ? 'ON SITE' : ''));
        $loc = '';
        foreach (['lagos', 'abuja', 'port harcourt', 'ibadan', 'kano', 'enugu', 'benin', 'kaduna'] as $c) {
            if (str_contains($l, $c)) {
                $loc = ucwords($c);
            }
        }
        $min = 0;
        if (preg_match('/(\d{2,3})\s*k\b/', $l, $m)) {
            $min = (int)$m[1] * 1000;
        } elseif (preg_match('/(\d{5,7})/', str_replace(',', '', $l), $m)) {
            $min = (int)$m[1];
        }
        $cats = ['design' => 'DESIGN', 'engineer' => 'ENGINEERING', 'software' => 'ENGINEERING', 'developer' => 'ENGINEERING', 'marketing' => 'MARKETING', 'finance' => 'FINANCE', 'data' => 'DATA', 'product' => 'PRODUCT'];
        $cat = '';
        $kw = '';
        foreach ($cats as $k => $v) {
            if (str_contains($l, $k)) {
                $cat = $v;
                $kw = $k;
                break;
            }
        }
        return ['keywords' => $kw, 'location' => $loc, 'category' => $cat, 'work_mode' => $mode, 'min_stipend' => $min, 'summary' => 'Filters from your request.'];
    }
}
