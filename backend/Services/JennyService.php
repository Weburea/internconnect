<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\Database;
use PDO;
use Throwable;

/**
 * Jenny AI chat on Google Gemini (docs/55-jenny-ai-chat.md).
 *
 * Channels: "widget" (floating chat on every page), "assistant" (candidate AI Assistant page,
 * paid plans, file attachments), "admin" (staff Gemini chat). Every conversation and message is
 * stored (ai_conversations / ai_messages) so admin can review sessions; attachments stay private.
 *
 * Answers are grounded: the prompt carries the real listings (backend/Data/internships.php,
 * Premium pay hidden from non-Premium viewers), plans and limits (PlanService), training partners
 * (ElearningService) and help topics. When Gemini is unavailable a rule-based reply is used,
 * so the chat never breaks.
 */
final class JennyService
{
    public const CHANNELS = ['widget', 'assistant', 'admin'];
    public const PERSONAS = ['jobseeker', 'recruiter', 'admin'];
    public const MAX_MESSAGES_PER_CHAT = 40;
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
            $pdo->exec('CREATE TABLE IF NOT EXISTS ai_conversations (
                id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY, public_id CHAR(24) NOT NULL, channel VARCHAR(16) NOT NULL, persona VARCHAR(16) NOT NULL,
                owner_key CHAR(64) NOT NULL, account_type VARCHAR(16) NOT NULL, account_id BIGINT UNSIGNED NULL, display_name VARCHAR(120) NULL,
                title VARCHAR(160) NULL, page VARCHAR(200) NULL, city VARCHAR(80) NULL, country VARCHAR(80) NULL, lat DECIMAL(9,5) NULL, lng DECIMAL(9,5) NULL,
                message_count INT UNSIGNED NOT NULL DEFAULT 0, ai_source VARCHAR(16) NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL,
                UNIQUE KEY uq_ai_conv_public (public_id), INDEX idx_ai_conv_owner (owner_key, channel), INDEX idx_ai_conv_updated (updated_at)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');
            $pdo->exec('CREATE TABLE IF NOT EXISTS ai_messages (
                id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY, conversation_id BIGINT UNSIGNED NOT NULL, role VARCHAR(12) NOT NULL,
                content MEDIUMTEXT NOT NULL, attachment TEXT NULL, links TEXT NULL, source VARCHAR(16) NULL, created_at DATETIME NOT NULL,
                INDEX idx_ai_msg_conv (conversation_id, id)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');
        } else {
            $pdo->exec('CREATE TABLE IF NOT EXISTS ai_conversations (id INTEGER PRIMARY KEY AUTOINCREMENT, public_id TEXT NOT NULL UNIQUE, channel TEXT NOT NULL,
                persona TEXT NOT NULL, owner_key TEXT NOT NULL, account_type TEXT NOT NULL, account_id INTEGER, display_name TEXT, title TEXT, page TEXT,
                city TEXT, country TEXT, lat REAL, lng REAL, message_count INTEGER NOT NULL DEFAULT 0, ai_source TEXT, created_at TEXT NOT NULL, updated_at TEXT NOT NULL)');
            $pdo->exec('CREATE INDEX IF NOT EXISTS idx_ai_conv_owner ON ai_conversations (owner_key, channel)');
            $pdo->exec('CREATE INDEX IF NOT EXISTS idx_ai_conv_updated ON ai_conversations (updated_at)');
            $pdo->exec('CREATE TABLE IF NOT EXISTS ai_messages (id INTEGER PRIMARY KEY AUTOINCREMENT, conversation_id INTEGER NOT NULL, role TEXT NOT NULL,
                content TEXT NOT NULL, attachment TEXT, links TEXT, source TEXT, created_at TEXT NOT NULL)');
            $pdo->exec('CREATE INDEX IF NOT EXISTS idx_ai_msg_conv ON ai_messages (conversation_id, id)');
        }
        // Additive: users can pin chats (2026-11-13).
        $cols = self::isMysql($pdo)
            ? array_column($pdo->query('SHOW COLUMNS FROM ai_conversations')->fetchAll(PDO::FETCH_ASSOC), 'Field')
            : array_column($pdo->query('PRAGMA table_info(ai_conversations)')->fetchAll(PDO::FETCH_ASSOC), 'name');
        if (!in_array('pinned', $cols, true)) {
            $pdo->exec('ALTER TABLE ai_conversations ADD COLUMN pinned ' . (self::isMysql($pdo) ? 'TINYINT(1)' : 'INTEGER') . ' NOT NULL DEFAULT 0');
        }
        self::$ready = true;
    }

    /** Pin or unpin one of my chats. */
    public static function pinConversation(string $publicId, array $actor, bool $pinned): bool
    {
        $conv = self::conversation($publicId, $actor);
        if (!$conv) {
            return false;
        }
        self::pdo()->prepare('UPDATE ai_conversations SET pinned = ? WHERE id = ?')->execute([$pinned ? 1 : 0, (int)$conv['id']]);
        return true;
    }

    /** Delete one of my chats and its messages. */
    public static function deleteConversation(string $publicId, array $actor): bool
    {
        $conv = self::conversation($publicId, $actor);
        if (!$conv) {
            return false;
        }
        self::pdo()->prepare('DELETE FROM ai_messages WHERE conversation_id = ?')->execute([(int)$conv['id']]);
        self::pdo()->prepare('DELETE FROM ai_conversations WHERE id = ?')->execute([(int)$conv['id']]);
        return true;
    }

    // ── Who is chatting ─────────────────────────────────────────────────────

    /** @return array{type: string, id: ?int, name: string, owner: string} */
    public static function actor(string $channel): array
    {
        if ($channel === 'admin') {
            $email = (string)($_SESSION['admin_email'] ?? 'admin');
            return ['type' => 'admin', 'id' => null, 'name' => (string)($_SESSION['admin_name'] ?? 'Admin'), 'owner' => hash('sha256', 'admin:' . $email)];
        }
        // The candidate area (and so the AI Assistant) uses the demo candidate until accounts move to MySQL,
        // exactly like CandidateController and PlanService::candidatePlan().
        if ($channel === 'assistant' && ($_SESSION['user_role'] ?? 'candidate') !== 'employer') {
            $id = (int)($_SESSION['candidate_id'] ?? 1);
            return ['type' => 'candidate', 'id' => $id, 'name' => (string)($_SESSION['profile_name'] ?? 'Candidate'), 'owner' => hash('sha256', 'candidate:' . $id)];
        }
        if (!empty($_SESSION['is_logged_in'])) {
            $role = (string)($_SESSION['user_role'] ?? 'candidate');
            if ($role === 'employer') {
                $id = (int)($_SESSION['employer_id'] ?? 1);
                return ['type' => 'employer', 'id' => $id, 'name' => (string)($_SESSION['employer_company'] ?? $_SESSION['profile_name'] ?? 'Employer'), 'owner' => hash('sha256', 'employer:' . $id)];
            }
            $id = (int)($_SESSION['candidate_id'] ?? 1);
            return ['type' => 'candidate', 'id' => $id, 'name' => (string)($_SESSION['profile_name'] ?? 'Candidate'), 'owner' => hash('sha256', 'candidate:' . $id)];
        }
        // Visitors: tied to their browser session (no account, nothing personal stored).
        if (empty($_SESSION['jenny_visitor'])) {
            $_SESSION['jenny_visitor'] = bin2hex(random_bytes(16));
        }
        return ['type' => 'visitor', 'id' => null, 'name' => 'Visitor', 'owner' => hash('sha256', 'visitor:' . $_SESSION['jenny_visitor'])];
    }

    // ── Conversations ───────────────────────────────────────────────────────

    /** Existing conversation of this actor, or null. */
    public static function conversation(string $publicId, array $actor): ?array
    {
        if (!preg_match('/^[a-f0-9]{24}$/', $publicId)) {
            return null;
        }
        self::ensureTables();
        $stmt = self::pdo()->prepare('SELECT * FROM ai_conversations WHERE public_id = ?');
        $stmt->execute([$publicId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
        // Only the owner (or staff, read-only through the admin pages) may continue a chat.
        return $row && hash_equals($row['owner_key'], $actor['owner']) ? $row : null;
    }

    private static function startConversation(array $actor, string $channel, string $persona, string $page): array
    {
        self::ensureTables();
        $geo = null;
        try {
            $geo = GeoService::lookup(GeoService::clientIp());
        } catch (Throwable $e) {
            $geo = null;
        }
        $now = date('Y-m-d H:i:s');
        $publicId = bin2hex(random_bytes(12));
        self::pdo()->prepare('INSERT INTO ai_conversations (public_id, channel, persona, owner_key, account_type, account_id, display_name, page, city, country, lat, lng, message_count, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 0, ?, ?)')
            ->execute([$publicId, $channel, $persona, $actor['owner'], $actor['type'], $actor['id'], mb_substr($actor['name'], 0, 120), mb_substr($page, 0, 200),
                $geo['city'] ?? null, $geo['country'] ?? null, $geo['lat'] ?? null, $geo['lng'] ?? null, $now, $now]);
        return self::conversation($publicId, $actor);
    }

    public static function messages(int $conversationId, int $limit = 60): array
    {
        self::ensureTables();
        $stmt = self::pdo()->prepare('SELECT * FROM (SELECT * FROM ai_messages WHERE conversation_id = ? ORDER BY id DESC LIMIT ' . max(1, min(200, $limit)) . ') t ORDER BY id');
        $stmt->execute([$conversationId]);
        return array_map(static function (array $m): array {
            $m['attachment'] = $m['attachment'] ? json_decode((string)$m['attachment'], true) : null;
            $m['links'] = $m['links'] ? (json_decode((string)$m['links'], true) ?: []) : [];
            return $m;
        }, $stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    /** Conversations of the signed-in actor on one channel (AI Assistant sidebar). */
    public static function listFor(array $actor, string $channel, int $limit = 30): array
    {
        self::ensureTables();
        $stmt = self::pdo()->prepare('SELECT public_id, title, message_count, updated_at, pinned FROM ai_conversations WHERE owner_key = ? AND channel = ? AND message_count > 0 ORDER BY pinned DESC, updated_at DESC LIMIT ' . max(1, min(100, $limit)));
        $stmt->execute([$actor['owner'], $channel]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private static function addMessage(int $conversationId, string $role, string $content, ?array $attachment = null, array $links = [], ?string $source = null): void
    {
        $now = date('Y-m-d H:i:s');
        self::pdo()->prepare('INSERT INTO ai_messages (conversation_id, role, content, attachment, links, source, created_at) VALUES (?, ?, ?, ?, ?, ?, ?)')
            ->execute([$conversationId, $role, $content, $attachment ? json_encode($attachment) : null, $links ? json_encode($links) : null, $source, $now]);
        self::pdo()->prepare('UPDATE ai_conversations SET message_count = message_count + 1, updated_at = ?' . ($source ? ', ai_source = ?' : '') . ' WHERE id = ?')
            ->execute($source ? [$now, $source, $conversationId] : [$now, $conversationId]);
    }

    // ── Chat ────────────────────────────────────────────────────────────────

    /**
     * One turn. $in: message, conversation, persona, page. $file: an entry from $_FILES or null.
     * Returns ['ok', 'status', 'error'?, 'conversation', 'reply', 'links', 'listings', 'source', 'usage'?].
     */
    public static function chat(string $channel, array $in, ?array $file = null): array
    {
        self::ensureTables();
        $channel = in_array($channel, self::CHANNELS, true) ? $channel : 'widget';
        $actor = self::actor($channel);
        $message = trim(mb_substr(strip_tags((string)($in['message'] ?? '')), 0, 4000));
        $hasFile = $file && ($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE;
        if ($message === '' && !$hasFile) {
            return ['ok' => false, 'status' => 422, 'error' => 'Type a message or attach a file.'];
        }
        $persona = in_array($in['persona'] ?? '', self::PERSONAS, true) ? (string)$in['persona'] : ($actor['type'] === 'employer' ? 'recruiter' : 'jobseeker');
        if ($channel === 'admin') {
            $persona = 'admin';
        }

        // Who may use what.
        if ($channel === 'assistant' && $actor['type'] !== 'candidate') {
            return ['ok' => false, 'status' => 403, 'error' => 'Sign in as a candidate to use the AI Assistant.'];
        }
        if ($hasFile && $actor['type'] === 'visitor') {
            return ['ok' => false, 'status' => 403, 'error' => 'Sign in to share files with Jenny.'];
        }

        $conv = !empty($in['conversation']) ? self::conversation((string)$in['conversation'], $actor) : null;
        $isNew = $conv === null;
        if (!$isNew && (int)$conv['message_count'] >= self::MAX_MESSAGES_PER_CHAT * 2) {
            return ['ok' => false, 'status' => 429, 'error' => 'This chat is full. Start a new chat to keep going.', 'code' => 'chat_full'];
        }

        // Jenny Coins (docs/55 §1): 1 coin per answer, 2 with a file. Visitors share the Free allowance per IP;
        // staff are not limited. AI Assistant new chats also need the plan's daily chat allowance.
        $usage = null;
        $wallet = self::wallet($actor);
        $cost = $hasFile ? 2 : 1;
        if ($wallet) {
            $can = PlanService::canSpendCoins($wallet[0], $wallet[1], $cost);
            if (!$can['ok']) {
                $res = ['ok' => false, 'status' => 429, 'error' => $can['message'], 'code' => 'no_coins', 'coins' => self::coinState($can['coins'])];
                if ($actor['type'] === 'visitor') {
                    $res['error'] .= ' Create a free account to keep your own coins.';
                    $res['code'] = 'visitor_limit';
                } elseif (!empty($can['upgrade'])) {
                    $res['upgrade'] = true;
                }
                return $res;
            }
        }
        if ($actor['type'] === 'candidate' && $channel === 'assistant' && $isNew) {
            $q = PlanService::check('candidate', (int)$actor['id'], 'assistant_chats');
            if (!$q['ok']) {
                return ['ok' => false, 'status' => $q['usage']['limit'] === 0 ? 403 : 429, 'error' => $q['message'], 'code' => 'plan_limit', 'upgrade' => $q['upgrade'], 'usage' => $q['usage']];
            }
        }

        // Attachment (private storage; read by Gemini).
        $attachment = null;
        $geminiFiles = [];
        $extraText = '';
        if ($hasFile) {
            $stored = UploadStorage::store($file, 'jenny');
            if (!$stored['ok']) {
                return ['ok' => false, 'status' => 422, 'error' => (string)$stored['error']];
            }
            $meta = $stored['meta'];
            $attachment = ['name' => $meta['name'], 'size' => $meta['size'], 'ext' => $meta['ext'], 'file' => $meta['file']];
            $path = UploadStorage::path($meta);
            $mime = ['pdf' => 'application/pdf', 'png' => 'image/png', 'jpg' => 'image/jpeg', 'jpeg' => 'image/jpeg', 'webp' => 'image/webp'][$meta['ext']] ?? null;
            if ($mime && $path) {
                $geminiFiles[] = ['path' => $path, 'mime' => $mime];
            } elseif ($path) {
                $text = $meta['ext'] === 'txt' ? (string)file_get_contents($path, false, null, 0, 60000) : AiService::extractCvText($path, $meta['ext']);
                $extraText = mb_substr(trim($text), 0, 20000);
            }
        }

        if ($isNew) {
            $conv = self::startConversation($actor, $channel, $persona, (string)($in['page'] ?? ''));
        }
        $history = self::messages((int)$conv['id'], 12);
        self::addMessage((int)$conv['id'], 'user', $message !== '' ? $message : 'Please review the attached file.', $attachment);
        if ($isNew || empty($conv['title'])) {
            $title = $message !== '' ? $message : ('File: ' . ($attachment['name'] ?? 'attachment'));
            self::pdo()->prepare('UPDATE ai_conversations SET title = ? WHERE id = ?')->execute([mb_strimwidth($title, 0, 80, '...'), $conv['id']]);
        }

        $viewerPlan = $actor['type'] === 'candidate' ? PlanService::effectivePlan('candidate', (int)$actor['id']) : 'free';
        $listings = PlanService::annotateListings(require dirname(__DIR__) . '/Data/internships.php', $viewerPlan);
        $answer = null;
        if (GeminiClient::isConfigured()) {
            $prompt = self::prompt($channel, $persona, $actor, $viewerPlan, $listings, $history, $message, $attachment, $extraText, (string)($in['page'] ?? ''));
            // One model, up to 22 s (one retry on overload); otherwise the rule-based answer below.
            $answer = GeminiClient::json($prompt, 22, $geminiFiles, 1400, true);
        }
        $source = 'gemini';
        if (!is_array($answer) || trim((string)($answer['reply'] ?? '')) === '') {
            $answer = self::fallback($message, $persona, $listings, $attachment);
            $source = 'rules';
        }

        $reply = mb_substr(trim((string)$answer['reply']), 0, 6000);
        $links = self::cleanLinks((array)($answer['links'] ?? []));
        $byId = array_column($listings, null, 'id');
        $cards = [];
        foreach (array_slice((array)($answer['listing_ids'] ?? []), 0, 4) as $id) {
            if (isset($byId[$id])) {
                $l = $byId[$id];
                $cards[] = [
                    'id'       => $l['id'], 'title' => $l['title'], 'company' => $l['company'], 'location' => $l['location'], 'mode' => $l['tag'],
                    'pay'      => $l['locked'] ? 'Premium listing' : '₦' . number_format((int)$l['stipendAmount']) . '/mo',
                    'premium'  => $l['premium'], 'locked' => $l['locked'],
                    'url'      => $l['locked'] ? url('/pricing?plan=premium&ref=jenny') : url('/internship/' . $l['id']),
                ];
            }
        }
        self::addMessage((int)$conv['id'], 'assistant', $reply, null, array_merge($links, array_map(static fn ($c) => ['label' => $c['title'] . ' at ' . $c['company'], 'url' => $c['url'], 'listing' => true], $cards)), $source);

        // Count usage only after a successful reply. Built-in (rule-based) answers cost no coins.
        $coinState = $wallet ? self::coinState(PlanService::coins($wallet[0], $wallet[1])) : null;
        if ($wallet && $source === 'gemini') {
            $coinState = self::coinState(PlanService::spendCoins($wallet[0], $wallet[1], $cost));
        }
        if ($actor['type'] === 'candidate' && $channel === 'assistant' && $isNew) {
            $usage = PlanService::consume('candidate', (int)$actor['id'], 'assistant_chats')['usage'];
        }
        if ($attachment || $source === 'gemini') {
            AiService::logUsage((int)($actor['id'] ?? 0), $actor['type'], 'jenny_' . $channel, $source, (int)ceil(mb_strlen($message) / 4), (int)ceil(mb_strlen($reply) / 4));
        }

        return ['ok' => true, 'status' => 200, 'conversation' => $conv['public_id'], 'reply' => $reply, 'links' => $links, 'listings' => $cards, 'source' => $source, 'usage' => $usage, 'coins' => $coinState,
            'attachment' => $attachment ? ['name' => $attachment['name'], 'size' => $attachment['size']] : null];
    }

    /** Whose coins pay for this chat: [type, id], or null for staff. Visitors share one Free wallet per IP. */
    public static function wallet(array $actor): ?array
    {
        return match ($actor['type']) {
            'admin'     => null,
            'visitor'   => ['visitor', (int)sprintf('%u', crc32('ip:' . RateLimiter::ip()))],
            default     => [$actor['type'], (int)$actor['id']],
        };
    }

    /** Coin state for the browser. */
    public static function coinState(array $c): array
    {
        return [
            'balance'    => $c['balance'],
            'allowance'  => $c['allowance'],
            'unlimited'  => $c['unlimited'],
            'plan'       => $c['plan'],
            'refill_hours' => $c['refill_hours'],
            'refills_in' => $c['refills_in'],
        ];
    }

    /** Links Jenny may show: pages on this site only (no external or script URLs). */
    private static function cleanLinks(array $links): array
    {
        $out = [];
        foreach (array_slice($links, 0, 4) as $l) {
            $label = mb_substr(trim(strip_tags((string)($l['label'] ?? ''))), 0, 60);
            $path = trim((string)($l['url'] ?? $l['path'] ?? ''));
            if ($label === '' || !preg_match('#^/[A-Za-z0-9/_\-?=&.%]*$#', $path) || str_starts_with($path, '//')) {
                continue;
            }
            $out[] = ['label' => $label, 'url' => url($path)];
        }
        return $out;
    }

    private static function prompt(string $channel, string $persona, array $actor, string $plan, array $listings, array $history, string $message, ?array $attachment, string $extraText, string $page): string
    {
        $lines = [];
        foreach ($listings as $l) {
            $lines[] = '- id=' . $l['id'] . ' | ' . $l['title'] . ' | ' . $l['company'] . ' | ' . $l['location'] . ' | ' . $l['tag'] . ' | ' . $l['category']
                . ' | ' . ($l['locked'] ? 'PREMIUM listing (pay hidden: needs Premium)' : 'NGN ' . number_format((int)$l['stipendAmount']) . '/month' . ($l['premium'] ? ' (Premium listing)' : ''))
                . ' | skills: ' . implode(', ', (array)$l['tags']);
        }
        $partners = [];
        try {
            foreach (ElearningService::catalog() as $p) {
                $partners[] = '- ' . $p['platform'] . ': ' . $p['title'] . ' (' . $p['duration'] . ', ' . $p['delivery'] . ')';
            }
        } catch (Throwable $e) {
        }
        $m = PlanService::matrix();
        $planLine = static fn (string $p) => ucfirst($p) . ': applications ' . self::fmt($m['applications'][$p] ?? '') . '/month, AI documents ' . self::fmt($m['ai_documents'][$p] ?? '') . '/day, Jenny Coins ' . self::fmt($m['jenny_coins'][$p] ?? '') . ' (refill every ' . ($m['jenny_refill_hours'][$p] ?? '?') . 'h), Jenny search ' . self::fmt($m['ai_search'][$p] ?? '') . '/day, mock interviews ' . self::fmt($m['mock_interviews'][$p] ?? '') . '/week, Premium listings ' . (($m['premium_listings'][$p] ?? '0') === '1' ? 'yes' : 'no');
        $conversation = [];
        foreach ($history as $h) {
            $conversation[] = ($h['role'] === 'user' ? 'User' : 'Jenny') . ': ' . mb_substr((string)$h['content'], 0, 1200);
        }
        $who = match ($channel) {
            'admin'     => 'an InternConnect staff member using the admin console. Help with platform operations, drafting messages, summaries and analysis. You can be more detailed.',
            'assistant' => 'a signed-in candidate on the ' . ucfirst($plan) . ' plan using the AI Assistant workspace. Give thorough, practical career help: CV and cover letter feedback, interview practice, career plans.',
            default     => $persona === 'recruiter'
                ? 'an employer or recruiter (' . ($actor['type'] === 'visitor' ? 'not signed in' : 'signed in') . '). Help with posting internships, screening, shortlisting, interviews and employer plans.'
                : 'a student or graduate looking for internships (' . ($actor['type'] === 'visitor' ? 'not signed in' : 'signed in, ' . ucfirst($plan) . ' plan') . ').',
        };

        return "You are Jenny, the friendly AI assistant of InternConnect, a global internship and early-career platform used by students, graduates and employers in every country (the company was founded in Lagos, Nigeria, but the platform is for everyone worldwide). You are talking to {$who}\n"
            . "Current page: {$page}\n\n"
            . "RULES:\n"
            . "- Reply in clear, warm, plain English for a global audience. Never assume the user is in Nigeria or any country unless they say so; when location matters, ask or give options. Prices are charged in Naira (NGN) through Paystack, which converts from other currencies. Short paragraphs; use '- ' bullets for lists and **bold** sparingly. No emojis. Never invent internships, companies, prices or features that are not listed below.\n"
            . "- Never assume or mention gender, age, religion or ethnicity.\n"
            . "- If asked about something you cannot do (e.g. submit an application for them), say how they can do it on InternConnect.\n"
            . "- For a Premium listing marked 'pay hidden', do not reveal the pay; say it is visible on the Premium plan.\n"
            . "- Keep replies under 180 words in the chat widget; up to 400 words in the AI Assistant or admin.\n"
            . "- Recommend internships only from the list, by id, when relevant (at most 4).\n\n"
            . "INTERNSHIPS (id | title | company | location | mode | category | pay | skills):\n" . implode("\n", $lines) . "\n\n"
            . "PLANS (prices per month: Pro NGN 2,499, Premium NGN 4,999; -1 means unlimited):\n" . $planLine('free') . "\n" . $planLine('pro') . "\n" . $planLine('premium') . "\n\n"
            . "TRAINING PARTNERS (E-learning page; certificates can unlock free Pro/Premium time):\n" . implode("\n", $partners) . "\n\n"
            . "SITE PAGES you may link to: /internships (find jobs), /pricing, /elearning, /help, /contact, /how-it-works, /candidate/dashboard, /candidate/applications, /candidate/ai-assistant, /candidate/ai-assistant/cover-letter, /candidate/ai-assistant/interview-prep, /candidate/ai-assistant/career-coach, /candidate/profile, /candidate/billing, /employer/dashboard, /employer/post-internship, /employer/candidates, /register/student, /register/employer, /login.\n\n"
            . ($channel === 'admin' ? "LIVE PLATFORM DATA (use these numbers; say when something is not in the data):\n" . self::adminContext() . "\n\n" : '')
            . ($conversation ? "CONVERSATION SO FAR:\n" . implode("\n", $conversation) . "\n\n" : '')
            . ($attachment ? 'The user attached a file: ' . $attachment['name'] . ($extraText !== '' ? " (text below)\n--- FILE TEXT ---\n" . $extraText . "\n--- END FILE ---\n" : " (attached above)\n") . "Read it carefully and refer to its actual content.\n\n" : '')
            . "USER: {$message}\n\n"
            . 'Return JSON only: {"reply": "your answer", "links": [{"label": "short button text", "url": "/path from SITE PAGES"}], "listing_ids": ["ids of recommended internships"]}';
    }

    /** Real numbers for the staff console (each source optional, never fatal). */
    private static function adminContext(): string
    {
        $pdo = self::pdo();
        $count = static function (string $sql) use ($pdo): ?int {
            try {
                return (int)$pdo->query($sql)->fetchColumn();
            } catch (Throwable $e) {
                return null;
            }
        };
        $out = [];
        $apps = $count('SELECT COUNT(*) FROM application_submissions');
        if ($apps !== null) {
            $out[] = '- Applications submitted: ' . $apps;
        }
        $paid = $count("SELECT COUNT(*) FROM billing_transactions WHERE status = 'success'");
        $revenue = $count("SELECT COALESCE(SUM(amount_kobo), 0) FROM billing_transactions WHERE status = 'success'");
        if ($paid !== null) {
            $out[] = '- Successful payments: ' . $paid . ' (total NGN ' . number_format(($revenue ?? 0) / 100) . '); failed or declined: ' . (int)$count("SELECT COUNT(*) FROM billing_transactions WHERE status = 'failed'");
        }
        $active = $count("SELECT COUNT(*) FROM billing_subscriptions WHERE status = 'active'");
        if ($active !== null) {
            $out[] = '- Active paid subscriptions: ' . $active;
        }
        try {
            $n = NewsletterService::stats();
            $out[] = '- Newsletter: ' . $n['subscribed'] . ' subscribed, ' . $n['pending'] . ' unconfirmed, ' . $n['unsubscribed'] . ' unsubscribed';
            $c = ContactService::counts();
            $out[] = '- Contact inbox: ' . $c['new'] . ' new, ' . $c['open'] . ' open, ' . $c['replied'] . ' replied';
            $m = MailService::stats();
            $out[] = '- E-mail: ' . $m['sent'] . ' sent, ' . $m['queued'] . ' queued, ' . $m['failed'] . ' failed' . ($m['configured'] ? '' : ' (MAIL_PASSWORD not set yet)');
        } catch (Throwable $e) {
        }
        $grants = $count("SELECT COUNT(*) FROM plan_grants WHERE status = 'active'");
        if ($grants !== null) {
            $out[] = '- Active plan grants (admin or training partner): ' . $grants;
        }
        $s = self::stats();
        $out[] = '- Jenny last 24h: ' . $s['conversations_24h'] . ' chats, ' . $s['messages_24h'] . ' user messages, ' . $s['gemini_share'] . '% answered by Gemini';
        $out[] = '- Internship listings: ' . count(require dirname(__DIR__) . '/Data/internships.php');
        return implode("\n", $out);
    }

    private static function fmt(string $v): string
    {
        return $v === '-1' ? 'unlimited' : $v;
    }

    /** Rule-based answer when Gemini is unavailable. */
    private static function fallback(string $message, string $persona, array $listings, ?array $attachment): array
    {
        $q = strtolower($message);
        $pick = static function (array $words) use ($listings): array {
            $scored = [];
            foreach ($listings as $l) {
                $hay = strtolower($l['title'] . ' ' . $l['category'] . ' ' . implode(' ', (array)$l['tags']) . ' ' . $l['location'] . ' ' . $l['tag']);
                $s = 0;
                foreach ($words as $w) {
                    if (strlen($w) > 2 && str_contains($hay, $w)) {
                        $s++;
                    }
                }
                if ($s > 0) {
                    $scored[$l['id']] = $s;
                }
            }
            arsort($scored);
            return array_slice(array_keys($scored), 0, 3);
        };
        if ($attachment) {
            return ['reply' => "I received **{$attachment['name']}**, but my document reader is busy right now. Please try again in a minute, or use the CV analysis tool in the AI Assistant for a full review.", 'links' => [['label' => 'CV analysis', 'url' => '/candidate/ai-assistant/cv-analyze']], 'listing_ids' => []];
        }
        if (preg_match('/\b(price|pricing|plan|pro|premium|pay|cost|subscription)\b/', $q)) {
            return ['reply' => "InternConnect has three plans:\n- **Free**: 5 applications a month and the basics.\n- **Pro** (NGN 2,499/month): 15 applications, AI cover letters and CV help, Jenny search and boosted visibility.\n- **Premium** (NGN 4,999/month): unlimited applications, Premium listings with higher pay, hybrid and remote roles, and priority visibility.", 'links' => [['label' => 'Compare plans', 'url' => '/pricing']], 'listing_ids' => []];
        }
        if ($persona === 'recruiter' || preg_match('/\b(post|hire|hiring|recruit|candidate|shortlist)\b/', $q)) {
            return ['reply' => 'I can help you hire interns. Post an internship in a few minutes, review applicants with AI summaries, and invite the best ones to a video interview inside InternConnect.', 'links' => [['label' => 'Post an internship', 'url' => '/employer/post-internship'], ['label' => 'Employer sign-up', 'url' => '/register/employer']], 'listing_ids' => []];
        }
        if (preg_match('/\b(course|learn|training|certif|elearning|e-learning)\b/', $q)) {
            return ['reply' => 'Our training partners offer programmes in software, data, design and business. A certificate from a partner can also unlock free Pro or Premium time on InternConnect.', 'links' => [['label' => 'See training partners', 'url' => '/elearning']], 'listing_ids' => []];
        }
        if (preg_match('/\b(cv|resume|cover letter|interview)\b/', $q)) {
            return ['reply' => 'I can help with your CV, cover letters and interview practice in the AI Assistant. Upload your CV there for a detailed review.', 'links' => [['label' => 'Open AI Assistant', 'url' => '/candidate/ai-assistant'], ['label' => 'Interview practice', 'url' => '/candidate/ai-assistant/interview-prep']], 'listing_ids' => []];
        }
        $ids = $pick(preg_split('/\W+/', $q) ?: []);
        if ($ids) {
            return ['reply' => 'Here are internships that match what you asked for. Open one to see the details and apply.', 'links' => [['label' => 'See all internships', 'url' => '/internships']], 'listing_ids' => $ids];
        }
        return ['reply' => "I'm Jenny, your InternConnect assistant. I can find internships for you, explain our plans, help with your CV and interviews, or point you to training partners. What would you like to do?", 'links' => [['label' => 'Find internships', 'url' => '/internships'], ['label' => 'Help Center', 'url' => '/help']], 'listing_ids' => []];
    }

    // ── Admin ───────────────────────────────────────────────────────────────

    public static function adminSessions(string $channel = '', int $limit = 50): array
    {
        self::ensureTables();
        $w = in_array($channel, self::CHANNELS, true) ? ' WHERE channel = ?' : '';
        $stmt = self::pdo()->prepare('SELECT * FROM ai_conversations' . $w . ($w ? ' AND' : ' WHERE') . ' message_count > 0 ORDER BY updated_at DESC LIMIT ' . max(1, min(200, $limit)));
        $stmt->execute($w ? [$channel] : []);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function adminConversation(string $publicId): ?array
    {
        if (!preg_match('/^[a-f0-9]{24}$/', $publicId)) {
            return null;
        }
        self::ensureTables();
        $stmt = self::pdo()->prepare('SELECT * FROM ai_conversations WHERE public_id = ?');
        $stmt->execute([$publicId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            return null;
        }
        $row['messages'] = self::messages((int)$row['id'], 200);
        return $row;
    }

    public static function stats(): array
    {
        self::ensureTables();
        $pdo = self::pdo();
        $since = date('Y-m-d H:i:s', strtotime('-24 hours'));
        $q = static function (string $sql, array $args = []) use ($pdo) {
            $s = $pdo->prepare($sql);
            $s->execute($args);
            return (int)$s->fetchColumn();
        };
        return [
            'conversations_24h' => $q('SELECT COUNT(*) FROM ai_conversations WHERE created_at >= ? AND message_count > 0', [$since]),
            'messages_24h'      => $q("SELECT COUNT(*) FROM ai_messages WHERE created_at >= ? AND role = 'user'", [$since]),
            'gemini_share'      => (function () use ($q, $since) {
                $all = $q("SELECT COUNT(*) FROM ai_messages WHERE created_at >= ? AND role = 'assistant'", [$since]);
                return $all ? (int)round($q("SELECT COUNT(*) FROM ai_messages WHERE created_at >= ? AND role = 'assistant' AND source = 'gemini'", [$since]) / $all * 100) : 0;
            })(),
            'visitors_24h'      => $q("SELECT COUNT(*) FROM ai_conversations WHERE created_at >= ? AND account_type = 'visitor' AND message_count > 0", [$since]),
        ];
    }

    /** Signed-in actor may download their own attachment. */
    public static function attachmentFor(string $publicId, int $messageId, array $actor): ?array
    {
        $conv = $actor['type'] === 'admin' ? self::adminConversation($publicId) : self::conversation($publicId, $actor);
        if (!$conv) {
            return null;
        }
        $stmt = self::pdo()->prepare('SELECT attachment FROM ai_messages WHERE id = ? AND conversation_id = ?');
        $stmt->execute([$messageId, $conv['id']]);
        $a = $stmt->fetchColumn();
        $meta = $a ? json_decode((string)$a, true) : null;
        return $meta ? ['kind' => 'jenny', 'file' => $meta['file'], 'name' => $meta['name'], 'ext' => $meta['ext'], 'size' => $meta['size']] : null;
    }
}
