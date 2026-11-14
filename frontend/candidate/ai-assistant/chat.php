<?php
declare(strict_types=1);
/**
 * View: AI Assistant chat workspace (Jenny on Google Gemini)
 * Route: GET /candidate/ai-assistant/chat
 * Layout: dashboard
 * Spec: docs/55-jenny-ai-chat.md. Same idea as the admin Jenny console: history on the left,
 * conversation in the middle, attachments (PDF, Word, text, images) and voice input in the composer.
 *
 * @var array  $candidate
 * @var string $plan       free | pro | premium
 * @var array  $chatUsage  PlanService::usage(..., 'assistant_chats')
 * @var array  $coins      JennyService::coinState(...) Jenny Coins wallet
 * @var array  $history    my conversations [public_id, title, message_count, updated_at]
 * @var string $openId
 * @var string $question
 */
$e = static fn ($v): string => htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');
$firstName = explode(' ', trim((string)($candidate['name'] ?? 'there')))[0];
$locked = $chatUsage['limit'] === 0;
$cfg = [
    'send'      => url('/candidate/ai-assistant/chat'),
    'load'      => url('/candidate/ai-assistant/chats/'),
    'chatsUrl'  => url('/candidate/ai-assistant/chats'),
    'pricing'   => url('/pricing?plan=pro&ref=ai-assistant-chat'),
    'history'   => array_map(static fn ($h) => ['id' => $h['public_id'], 'title' => $h['title'] ?: 'Conversation', 'pinned' => (int)($h['pinned'] ?? 0) === 1, 'time' => date('j M, g:i A', strtotime((string)$h['updated_at']))], $history),
    'openId'    => $openId,
    'question'  => $question,
    'chatsLeft' => $chatUsage['unlimited'] ? null : $chatUsage['remaining'],
    'coins'     => $coins,
    'locked'    => $locked,
];
$jennyIcon = asset('assets/images/jenny_ai/jenny_icon.png');
?>
<style>
.ac-wrap { display: grid; grid-template-columns: 280px minmax(0, 1fr); gap: 16px; height: calc(100vh - 150px); min-height: 520px; }
.ac-rail, .ac-main { background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 18px; min-width: 0; min-height: 0; }
.ac-rail { display: flex; flex-direction: column; padding: 14px; }
.ac-rail-head { display: flex; align-items: center; justify-content: space-between; gap: 8px; margin-bottom: 10px; }
.ac-rail-head h2 { font-size: 13px; font-weight: 800; color: #000623; margin: 0; letter-spacing: .02em; text-transform: uppercase; }
.ac-new { display: inline-flex; align-items: center; gap: 5px; border: 0; border-radius: 10px; background: #4F48E6; color: #FFFFFF; font-size: 12px; font-weight: 700; padding: 7px 11px; cursor: pointer; font-family: inherit; }
.ac-new:hover { background: #4338CA; }
.ac-history { flex: 1; overflow-y: auto; display: flex; flex-direction: column; gap: 4px; }
.ac-hitem { text-align: left; border: 1px solid transparent; background: transparent; border-radius: 12px; padding: 9px 10px; cursor: pointer; font-family: inherit; }
.ac-hitem:hover { background: #F8FAFC; }
.ac-hitem.is-on { background: #EEF0FF; border-color: #C7D2FE; }
.ac-hitem strong { display: block; font-size: 12.5px; color: #0F172A; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
.ac-hitem > span { display: block; font-size: 11px; color: #94A3B8; margin-top: 2px; }
.ac-quota { margin-top: 10px; padding: 10px 12px; border-radius: 12px; background: #F8FAFC; font-size: 12px; color: #475569; line-height: 1.5; }
.ac-quota strong { color: #000623; }
.ac-main { display: flex; flex-direction: column; overflow: hidden; }
.ac-head { display: flex; align-items: center; justify-content: space-between; gap: 10px; padding: 12px 18px; border-bottom: 1px solid #F1F5F9; }
.ac-head-left { display: flex; align-items: center; gap: 10px; min-width: 0; }
.ac-head-left img { width: 34px; height: 34px; border-radius: 50%; flex-shrink: 0; }
.ac-head-left h1 { font-size: 14px; font-weight: 800; color: #000623; margin: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.ac-head-left p { font-size: 11.5px; color: #64748B; margin: 1px 0 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.ac-hist-btn { display: none; border: 1px solid #E2E8F0; background: #FFFFFF; border-radius: 10px; padding: 7px 10px; font-size: 12px; font-weight: 700; color: #334155; cursor: pointer; font-family: inherit; flex-shrink: 0; }
.ac-canvas { flex: 1; overflow-y: auto; padding: 24px 20px; background: radial-gradient(circle at 50% 28%, rgba(241, 245, 249, .7) 0%, #FFFFFF 62%); }
.ac-thread { max-width: 760px; margin: 0 auto; display: flex; flex-direction: column; gap: 14px; }
.ac-welcome { max-width: 560px; margin: 4vh auto 0; text-align: center; }
.ac-welcome img { width: 110px; height: 110px; object-fit: contain; }
.ac-welcome h2 { font-size: 24px; font-weight: 800; color: #000623; margin: 10px 0 4px; letter-spacing: -.02em; }
.ac-welcome p { font-size: 14px; color: #64748B; margin: 0 0 18px; line-height: 1.55; }
.ac-chips { display: flex; flex-wrap: wrap; gap: 8px; justify-content: center; }
.ac-chip { border: 1px solid #E2E8F0; background: #FFFFFF; border-radius: 999px; padding: 8px 13px; font-size: 12.5px; font-weight: 600; color: #334155; cursor: pointer; font-family: inherit; }
.ac-chip:hover { border-color: #4F48E6; color: #4F48E6; }
.ac-row { display: flex; gap: 10px; }
.ac-row.user { justify-content: flex-end; }
.ac-row img.ac-av { width: 30px; height: 30px; border-radius: 50%; flex-shrink: 0; margin-top: 2px; }
.ac-bubble { max-width: min(620px, 86%); padding: 12px 15px; border-radius: 16px; font-size: 14px; line-height: 1.6; overflow-wrap: break-word; word-break: normal; }
.ac-row.user > div { max-width: min(620px, 86%); }
.ac-row.user .ac-bubble { max-width: 100%; }
.jcp { position: relative; display: inline-flex; align-items: center; gap: 6px; height: 32px; padding: 0 11px 0 5px; border-radius: 999px; background: #FFFBEB; border: 1px solid #FDE68A; color: #92400E; font-size: 13px; white-space: nowrap; cursor: default; }
.jcp img { width: 20px; height: 20px; display: block; }
.jcp strong { font-weight: 800; }
.jcp-tip { display: none; position: absolute; right: 0; top: calc(100% + 6px); width: 220px; white-space: normal; background: #000623; color: #FFFFFF; font-size: 11.5px; line-height: 1.45; padding: 8px 10px; border-radius: 10px; z-index: 30; }
.jcp:hover .jcp-tip, .jcp:focus-within .jcp-tip { display: block; }
.ac-hrow { position: relative; display: flex; align-items: center; border-radius: 12px; }
.ac-hrow .ac-hitem { flex: 1; min-width: 0; padding-right: 64px; }
.ac-hacts { position: absolute; right: 6px; top: 50%; transform: translateY(-50%); display: flex; gap: 2px; opacity: 0; transition: opacity .15s ease; }
.ac-hrow:hover .ac-hacts, .ac-hrow:focus-within .ac-hacts, .ac-hacts.is-shown { opacity: 1; }
.ac-hact { width: 26px; height: 26px; border: 0; border-radius: 8px; background: transparent; color: #64748B; display: inline-flex; align-items: center; justify-content: center; cursor: pointer; padding: 0; }
.ac-hact:hover { background: #E2E8F0; color: #0F172A; }
.ac-hact.is-pin { color: #4F48E6; }
.ac-hact.is-del:hover { background: #FEE2E2; color: #B91C1C; }
.ac-hsure { display: flex; gap: 6px; padding: 4px 10px 8px; font-size: 11.5px; color: #B91C1C; align-items: center; flex-wrap: wrap; }
.ac-hsure button { border: 0; border-radius: 7px; padding: 3px 9px; font-size: 11.5px; font-weight: 700; cursor: pointer; font-family: inherit; }
.ac-pin-dot { display: inline-flex; vertical-align: -1px; margin-right: 4px; color: #4F48E6; }
@media (hover: none) { .ac-hacts { opacity: 1; } }
.ac-row.bot .ac-bubble { background: #FFFFFF; border: 1px solid #E2E8F0; color: #1E293B; border-top-left-radius: 4px; }
.ac-row.user .ac-bubble { background: #000623; color: #FFFFFF; border-top-right-radius: 4px; }
.ac-bubble.is-error { background: #FEF2F2 !important; color: #991B1B !important; border-color: #FECACA !important; }
.ac-md ul { margin: 6px 0; padding-left: 20px; list-style: disc; }
.ac-md li { margin: 3px 0; }
.ac-md strong { font-weight: 700; color: #000623; }
.ac-md .gap { display: block; height: 8px; }
.ac-time { font-size: 10.5px; color: #94A3B8; margin-top: 4px; }
.ac-file { display: inline-flex; align-items: center; gap: 6px; margin-top: 8px; padding: 6px 10px; border-radius: 9px; background: rgba(255, 255, 255, .14); font-size: 12px; font-weight: 700; color: inherit; text-decoration: none; max-width: 100%; }
.ac-file span { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
.ac-links { display: flex; flex-wrap: wrap; gap: 6px; margin-top: 10px; }
.ac-links a { display: inline-flex; padding: 6px 11px; border-radius: 999px; background: #EEF0FF; color: #4F48E6; font-size: 12px; font-weight: 700; text-decoration: none; }
.ac-cards { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 8px; margin-top: 10px; }
.ac-card { display: block; padding: 10px 12px; border: 1px solid #E2E8F0; border-radius: 12px; text-decoration: none; background: #FFFFFF; }
.ac-card:hover { border-color: #4F48E6; }
.ac-card strong { display: block; font-size: 13px; color: #000623; }
.ac-card span { display: block; font-size: 11.5px; color: #64748B; margin-top: 2px; }
.ac-card em { display: block; font-style: normal; font-size: 12px; font-weight: 700; color: #4F48E6; margin-top: 4px; }
.ac-typing { display: inline-flex; align-items: center; gap: 8px; font-size: 13px; color: #64748B; background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 14px; padding: 10px 14px; }
.ac-typing i { width: 7px; height: 7px; border-radius: 50%; background: #4F48E6; display: inline-block; animation: acBlink 1.2s infinite ease-in-out; }
.ac-typing i:nth-child(2) { animation-delay: .15s; }
.ac-typing i:nth-child(3) { animation-delay: .3s; }
@keyframes acBlink { 0%, 80%, 100% { opacity: .25; } 40% { opacity: 1; } }
.ac-composer-wrap { padding: 12px 16px 16px; border-top: 1px solid #F1F5F9; }
.ac-attached { max-width: 760px; margin: 0 auto 8px; display: flex; align-items: center; gap: 8px; padding: 7px 10px; border-radius: 10px; background: #EEF0FF; color: #312E81; font-size: 12.5px; }
.ac-attached span { flex: 1; min-width: 0; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; font-weight: 700; }
.ac-attached button { border: 0; background: transparent; color: #4F48E6; cursor: pointer; display: flex; padding: 2px; }
.ac-composer { max-width: 760px; margin: 0 auto; display: flex; align-items: flex-end; gap: 6px; border: 1px solid #E2E8F0; border-radius: 18px; padding: 6px 6px 6px 8px; background: #FFFFFF; box-shadow: 0 6px 24px -14px rgba(0, 6, 35, .25); }
.ac-composer.is-drag { border-color: #4F48E6; background: #F5F6FF; }
.ac-composer textarea { flex: 1; min-width: 0; border: 0; outline: none; resize: none; font-family: inherit; font-size: 14px; line-height: 1.5; padding: 8px 4px; max-height: 160px; color: #0F172A; background: transparent; }
.ac-icon-btn { width: 36px; height: 36px; border-radius: 50%; border: 0; background: transparent; color: #64748B; display: flex; align-items: center; justify-content: center; cursor: pointer; flex-shrink: 0; }
.ac-icon-btn:hover { color: #4F48E6; background: #F1F5F9; }
.ac-icon-btn.is-live { background: #4F48E6; color: #FFFFFF; }
.ac-send { width: 38px; height: 38px; border-radius: 50%; border: 0; background: #4F48E6; color: #FFFFFF; display: flex; align-items: center; justify-content: center; cursor: pointer; flex-shrink: 0; }
.ac-send[disabled] { opacity: .45; cursor: not-allowed; }
.ac-hint { max-width: 760px; margin: 6px auto 0; font-size: 11px; color: #94A3B8; text-align: center; }
.ac-lock { max-width: 520px; margin: 8vh auto; text-align: center; padding: 28px; }
.ac-lock h2 { font-size: 22px; font-weight: 800; color: #000623; margin: 12px 0 6px; }
.ac-lock p { font-size: 14px; color: #64748B; line-height: 1.6; margin: 0 0 18px; }
.ac-lock a { display: inline-flex; align-items: center; justify-content: center; height: 44px; padding: 0 22px; border-radius: 12px; background: #4F48E6; color: #FFFFFF; font-weight: 700; text-decoration: none; font-size: 14px; }
@media (max-width: 960px) {
    .ac-wrap { grid-template-columns: minmax(0, 1fr); height: calc(100vh - 130px); }
    .ac-rail { display: none; position: fixed; inset: 70px 12px auto 12px; max-height: 70vh; z-index: 60; box-shadow: 0 20px 50px -20px rgba(0, 6, 35, .4); }
    .ac-rail.is-open { display: flex; }
    .ac-hist-btn { display: inline-flex; }
    .ac-canvas { padding: 18px 12px; }
    .ac-composer-wrap { padding: 10px; }
}
@media (prefers-reduced-motion: reduce) { .ac-typing i { animation: none; } }
</style>

<script type="application/json" id="ac-data"><?= json_encode($cfg, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?></script>
<script>
function aiAssistantChat() {
    const cfg = JSON.parse(document.getElementById('ac-data').textContent);
    const esc = (s) => String(s).replace(/[&<>"']/g, (c) => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]));
    const now = () => new Date().toLocaleTimeString([], { hour: 'numeric', minute: '2-digit' });
    const Recognition = window.SpeechRecognition || window.webkitSpeechRecognition;
    return {
        history: cfg.history,
        sureId: null,
        async post(url, data) {
            const fd = new FormData(); Object.entries(data || {}).forEach(([k, v]) => fd.append(k, v));
            const r = await fetch(url, { method: 'POST', headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }, body: fd });
            return r.ok;
        },
        async pin(h) {
            h.pinned = !h.pinned;
            this.history.sort((a, b) => (b.pinned ? 1 : 0) - (a.pinned ? 1 : 0));
            if (!(await this.post(cfg.chatsUrl + '/' + h.id + '/pin', { pinned: h.pinned ? 1 : 0 }))) h.pinned = !h.pinned;
        },
        async remove(h) {
            this.sureId = null;
            if (!(await this.post(cfg.chatsUrl + '/' + h.id + '/delete'))) return;
            this.history = this.history.filter((x) => x.id !== h.id);
            if (this.conversation === h.id) this.newChat();
        },
        conversation: null,
        messages: [],
        input: '',
        file: null,
        busy: false,
        loading: false,
        drag: false,
        railOpen: false,
        listening: false,
        voice: !!Recognition,
        chatsLeft: cfg.chatsLeft,
        coins: cfg.coins,
        coinLabel() { const c = this.coins; if (!c) return ''; return c.unlimited ? 'Unlimited' : c.balance + ' of ' + c.allowance; },
        coinWait() { const c = this.coins; if (!c || c.unlimited) return ''; if (!c.refills_in) return 'Refills ' + c.refill_hours + 'h after you start using them.'; const h = Math.floor(c.refills_in / 3600), m = Math.floor((c.refills_in % 3600) / 60); return 'Refills in ' + (h ? h + 'h ' : '') + m + 'm.'; },
        locked: cfg.locked,
        init() {
            if (cfg.openId) this.open(cfg.openId);
            else if (cfg.question && !this.locked) { this.input = cfg.question; this.send(); }
        },
        format(text) {
            const lines = esc(text || '').split(/\n/);
            let html = '', inList = false;
            lines.forEach((line) => {
                const t = line.trim();
                if (/^([-*]|\d+[.)])\s+/.test(t)) {
                    if (!inList) { html += '<ul>'; inList = true; }
                    html += '<li>' + t.replace(/^([-*]|\d+[.)])\s+/, '') + '</li>';
                } else {
                    if (inList) { html += '</ul>'; inList = false; }
                    html += t === '' ? '<span class="gap"></span>' : t + '<br>';
                }
            });
            if (inList) html += '</ul>';
            return html.replace(/\*\*([^*]+)\*\*/g, '<strong>$1</strong>').replace(/(<br>)+$/, '');
        },
        size(n) { return n > 1048576 ? (n / 1048576).toFixed(1) + ' MB' : Math.max(1, Math.round(n / 1024)) + ' KB'; },
        newChat() { this.conversation = null; this.messages = []; this.file = null; this.railOpen = false; window.history.replaceState(null, '', location.pathname); },
        async open(id) {
            this.loading = true;
            this.railOpen = false;
            try {
                const r = await fetch(cfg.load + encodeURIComponent(id), { headers: { 'Accept': 'application/json' } });
                const d = await r.json();
                if (!d.success) throw new Error(d.error);
                this.conversation = d.conversation;
                this.messages = d.messages.map((m) => ({ role: m.role === 'assistant' ? 'bot' : 'user', text: m.content, time: m.time, links: (m.links || []).filter((l) => !l.listing), cards: [], file: m.attachment }));
                window.history.replaceState(null, '', location.pathname + '?c=' + d.conversation);
            } catch (e) {
                this.toast('That conversation could not be opened.', 'error');
            }
            this.loading = false;
            this.scroll();
        },
        pick(f) {
            if (!f) return;
            if (f.size > 8 * 1024 * 1024) { this.toast('Attach a file under 8 MB.', 'error'); return; }
            if (!/\.(pdf|docx|txt|png|jpe?g|webp)$/i.test(f.name)) { this.toast('Attach a PDF, Word, text or image file.', 'error'); return; }
            this.file = f;
        },
        drop(e) { this.drag = false; this.pick(e.dataTransfer && e.dataTransfer.files && e.dataTransfer.files[0]); },
        ask(text) { this.input = text; this.send(); },
        keydown(e) { if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); this.send(); } },
        autosize(el) { el.style.height = 'auto'; el.style.height = Math.min(160, el.scrollHeight) + 'px'; },
        async send() {
            const text = this.input.trim();
            if ((!text && !this.file) || this.busy || this.locked) return;
            const file = this.file;
            this.messages.push({ role: 'user', text: text || 'Please review the attached file.', time: now(), file: file ? { name: file.name } : null });
            this.input = '';
            this.file = null;
            this.busy = true;
            this.$nextTick(() => { if (this.$refs.box) this.autosize(this.$refs.box); });
            this.scroll();
            const body = new FormData();
            body.append('message', text);
            body.append('page', location.pathname);
            if (this.conversation) body.append('conversation', this.conversation);
            if (file) body.append('file', file);
            try {
                const r = await fetch(cfg.send, { method: 'POST', headers: { 'Accept': 'application/json' }, body: body });
                const d = await r.json().catch(() => ({}));
                if (d.coins) this.coins = d.coins;
                if (d.success) {
                    const isNew = !this.conversation;
                    this.conversation = d.conversation;
                    this.messages.push({ role: 'bot', text: d.reply, time: now(), links: d.links || [], cards: d.listings || [] });
                    if (isNew) {
                        this.history.unshift({ id: d.conversation, title: (text || (file ? 'File: ' + file.name : 'Conversation')).slice(0, 80), time: 'Just now' });
                        window.history.replaceState(null, '', location.pathname + '?c=' + d.conversation);
                    }
                    if (d.usage && d.usage.remaining !== null && d.usage.remaining !== undefined) {
                        if (isNew && d.usage.period === 'day' && d.usage.limit <= 50) this.chatsLeft = d.usage.remaining;
                    }
                } else {
                    if (d.code === 'chat_full') this.conversation = null;
                    this.messages.push({ role: 'bot', text: d.error || 'Jenny could not answer just now. Please try again.', time: now(), isError: true, links: d.upgrade ? [{ label: 'See plans', url: d.upgrade_url || cfg.pricing }] : [], cards: [] });
                }
            } catch (e) {
                this.messages.push({ role: 'bot', text: 'Network error. Check your connection and try again.', time: now(), isError: true, links: [], cards: [] });
            }
            this.busy = false;
            this.scroll();
        },
        voiceToggle() {
            if (!Recognition) return;
            if (this.listening && this.rec) { this.rec.stop(); return; }
            const r = new Recognition();
            r.lang = 'en-NG'; r.interimResults = true;
            let finalText = '';
            r.onresult = (e) => { let interim = ''; for (let i = e.resultIndex; i < e.results.length; i++) { if (e.results[i].isFinal) finalText += e.results[i][0].transcript; else interim += e.results[i][0].transcript; } this.input = (finalText + interim).trim(); };
            r.onerror = (e) => { this.listening = false; if (e.error === 'not-allowed') this.toast('Allow the microphone for this site to talk to Jenny.', 'error'); };
            r.onend = () => { this.listening = false; };
            this.rec = r; this.listening = true;
            try { r.start(); } catch (e) { this.listening = false; }
        },
        toast(text, type) { window.dispatchEvent(new CustomEvent('show-toast', { detail: { text: text, message: text, type: type || 'info' } })); },
        scroll() { this.$nextTick(() => { const el = this.$refs.canvas; if (el) el.scrollTop = el.scrollHeight; }); }
    };
}
</script>

<div class="db-shell" x-data="aiAssistantChat()">
    <div class="db-sidebar-overlay" id="db-sidebar-overlay"></div>
    <?php $activeNav = 'ai-assistant'; include dirname(__DIR__, 2) . '/shared/components/candidate-sidebar.php'; ?>

    <div class="db-main">
        <?php include dirname(__DIR__, 2) . '/shared/components/candidate-topnav.php'; ?>

        <main class="db-content" id="db-content">
            <div class="ac-wrap">
                <!-- History -->
                <aside class="ac-rail" :class="{ 'is-open': railOpen }" aria-label="Your chats with Jenny">
                    <div class="ac-rail-head">
                        <h2>Your chats</h2>
                        <button type="button" class="ac-new" @click="newChat()" :disabled="locked">
                            <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.6"><path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14M5 12h14"/></svg>
                            New chat
                        </button>
                    </div>
                    <div class="ac-history">
                        <template x-for="h in history" :key="h.id">
                            <div>
                                <div class="ac-hrow">
                                    <button type="button" class="ac-hitem" :class="{ 'is-on': conversation === h.id }" @click="open(h.id)">
                                        <strong><span class="ac-pin-dot" x-show="h.pinned"><svg width="11" height="11" viewBox="0 0 24 24" fill="currentColor"><path d="M16 3l5 5-3 1-4 4 1 5-2 2-4-4-5 5-1-1 5-5-4-4 2-2 5 1 4-4z"/></svg></span><span x-text="h.title"></span></strong>
                                        <span x-text="h.time"></span>
                                    </button>
                                    <div class="ac-hacts" x-show="sureId !== h.id">
                                        <button type="button" class="ac-hact" :class="{ 'is-pin': h.pinned }" @click="pin(h)" :aria-label="h.pinned ? 'Unpin chat' : 'Pin chat'"><svg width="14" height="14" viewBox="0 0 24 24" :fill="h.pinned ? 'currentColor' : 'none'" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"><path d="M16 3l5 5-3 1-4 4 1 5-2 2-4-4-5 5-1-1 5-5-4-4 2-2 5 1 4-4z"/></svg></button>
                                        <button type="button" class="ac-hact is-del" @click="sureId = h.id" aria-label="Delete chat"><svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
                                    </div>
                                </div>
                                <div class="ac-hsure" x-show="sureId === h.id" x-cloak>
                                    <span>Delete this chat?</span>
                                    <button type="button" style="background: #B91C1C; color: #FFFFFF;" @click="remove(h)">Delete</button>
                                    <button type="button" style="background: #F1F5F9; color: #0F172A;" @click="sureId = null">Cancel</button>
                                </div>
                            </div>
                        </template>
                        <p x-show="history.length === 0" style="font-size: 12.5px; color: #94A3B8; padding: 6px 4px; margin: 0;">Your conversations with Jenny will appear here.</p>
                    </div>
                    <div class="ac-quota">
                        <div>Plan: <strong><?= $e(ucfirst($plan)) ?></strong></div>
                        <div x-show="chatsLeft !== null">New chats left today: <strong x-text="chatsLeft"></strong></div>
                        <div x-show="chatsLeft === null">New chats: <strong>unlimited</strong></div>
                        <div style="display: flex; align-items: center; gap: 8px; margin-top: 8px; padding-top: 8px; border-top: 1px solid #E2E8F0;">
                            <img src="<?= $e(url('assets/images/jenny-coin.svg')) ?>" width="28" height="28" alt="" style="width: 28px; height: 28px; flex-shrink: 0;">
                            <div style="min-width: 0;"><div>Jenny Coins: <strong x-text="coinLabel()"></strong></div><div style="font-size: 11.5px; color: #94A3B8;" x-text="coinWait()"></div></div>
                        </div>
                        <div style="margin-top: 4px; font-size: 11.5px; color: #94A3B8;">1 coin per answer, 2 with a file. Up to <?= \App\Services\JennyService::MAX_MESSAGES_PER_CHAT ?> messages in each chat.</div>
                    </div>
                </aside>

                <!-- Conversation -->
                <section class="ac-main">
                    <div class="ac-head">
                        <div class="ac-head-left">
                            <img src="<?= $e($jennyIcon) ?>" alt="" width="34" height="34">
                            <div style="min-width: 0;">
                                <h1>Chat with Jenny</h1>
                                <p>Career help, CV and cover letter feedback, interview practice. Attach files for Jenny to read.</p>
                            </div>
                        </div>
                        <div style="display: flex; align-items: center; gap: 8px; flex-shrink: 0;">
                            <span class="jcp" role="status" :aria-label="'Jenny Coins: ' + coinLabel()"><img src="<?= $e(url('assets/images/jenny-coin.svg')) ?>" width="20" height="20" alt=""><strong x-text="coinLabel()"></strong><span class="jcp-tip" x-text="coinWait() || '1 coin per answer, 2 with a file.'"></span></span>
                            <button type="button" class="ac-hist-btn" @click="railOpen = !railOpen" :aria-expanded="railOpen">History</button>
                        </div>
                    </div>

                    <?php if ($locked): ?>
                        <div class="ac-canvas">
                            <div class="ac-lock">
                                <img src="<?= $e(asset('assets/images/jenny_ai/Pointing_jenny_ai.png')) ?>" alt="" width="120" height="111" style="width: 120px; height: auto;">
                                <h2>Chat with Jenny on Pro and Premium</h2>
                                <p>Upload your CV, cover letter or an internship description and get detailed, personal feedback. Pro includes <?= (int)\App\Services\PlanService::limit('assistant_chats', 'pro') ?> chats a day, Premium <?= \App\Services\PlanService::limit('assistant_chats', 'premium') < 0 ? 'unlimited' : (int)\App\Services\PlanService::limit('assistant_chats', 'premium') ?>.</p>
                                <a href="<?= url('/pricing?plan=pro&ref=ai-assistant-chat') ?>">See plans</a>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="ac-canvas" x-ref="canvas">
                            <div class="ac-welcome" x-show="messages.length === 0 && !loading">
                                <img src="<?= $e(asset('assets/images/jenny_ai/jenny_ai_hi.png')) ?>" alt="" width="110" height="110">
                                <h2>Hi <?= $e($firstName) ?>, what are we working on?</h2>
                                <p>Ask anything about your career, or attach your CV or a job description and Jenny will read it.</p>
                                <div class="ac-chips">
                                    <button type="button" class="ac-chip" @click="ask('Review my CV and tell me the three most important improvements.')">Review my CV</button>
                                    <button type="button" class="ac-chip" @click="ask('Which internships on InternConnect fit my profile best, and why?')">Find my best matches</button>
                                    <button type="button" class="ac-chip" @click="ask('Give me five likely interview questions for a product design internship, with tips.')">Practise interview questions</button>
                                    <button type="button" class="ac-chip" @click="ask('Make me a 30-day plan to be ready for a data analyst internship.')">30-day career plan</button>
                                </div>
                            </div>
                            <div class="ac-thread">
                                <template x-for="(m, i) in messages" :key="i">
                                    <div class="ac-row" :class="m.role">
                                        <template x-if="m.role === 'bot'"><img class="ac-av" src="<?= $e($jennyIcon) ?>" alt="" width="30" height="30"></template>
                                        <div style="min-width: 0; display: flex; flex-direction: column;" :style="m.role === 'user' ? 'align-items: flex-end;' : ''">
                                            <div class="ac-bubble" :class="{ 'is-error': m.isError }">
                                                <template x-if="m.role === 'bot'"><div class="ac-md" x-html="format(m.text)"></div></template>
                                                <template x-if="m.role === 'user'"><div style="white-space: pre-wrap;" x-text="m.text"></div></template>
                                                <template x-if="m.file">
                                                    <a class="ac-file" :href="m.file.url || null" target="_blank" rel="noopener">
                                                        <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                                                        <span x-text="m.file.name"></span>
                                                    </a>
                                                </template>
                                                <template x-if="m.cards && m.cards.length">
                                                    <div class="ac-cards">
                                                        <template x-for="c in m.cards" :key="c.id">
                                                            <a class="ac-card" :href="c.url"><strong x-text="c.title"></strong><span x-text="c.company + ' · ' + c.location + ' · ' + c.mode"></span><em x-text="c.locked ? 'Pay shown on Premium' : c.pay"></em></a>
                                                        </template>
                                                    </div>
                                                </template>
                                                <template x-if="m.links && m.links.length">
                                                    <div class="ac-links"><template x-for="l in m.links" :key="l.url + l.label"><a :href="l.url" x-text="l.label"></a></template></div>
                                                </template>
                                            </div>
                                            <span class="ac-time" x-text="m.time"></span>
                                        </div>
                                    </div>
                                </template>
                                <div class="ac-row bot" x-show="busy || loading">
                                    <img class="ac-av" src="<?= $e($jennyIcon) ?>" alt="" width="30" height="30">
                                    <div class="ac-typing"><i></i><i></i><i></i><span x-text="loading ? 'Opening chat...' : 'Jenny is thinking...'"></span></div>
                                </div>
                            </div>
                        </div>

                        <div class="ac-composer-wrap">
                            <div class="ac-attached" x-show="file" x-cloak>
                                <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                <span x-text="file ? file.name + ' (' + size(file.size) + ')' : ''"></span>
                                <button type="button" @click="file = null" aria-label="Remove attachment"><svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg></button>
                            </div>
                            <div class="ac-composer" :class="{ 'is-drag': drag }" @dragover.prevent="drag = true" @dragleave.prevent="drag = false" @drop.prevent="drop($event)">
                                <label class="ac-icon-btn" aria-label="Attach a file">
                                    <input type="file" accept=".pdf,.docx,.txt,.png,.jpg,.jpeg,.webp" style="display: none;" @change="pick($event.target.files[0]); $event.target.value = ''">
                                    <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                                </label>
                                <textarea x-ref="box" rows="1" x-model="input" @keydown="keydown($event)" @input="autosize($event.target)" maxlength="4000" :placeholder="listening ? 'Listening...' : 'Message Jenny, or drop a file here'" aria-label="Message Jenny"></textarea>
                                <button type="button" class="ac-icon-btn" :class="{ 'is-live': listening }" x-show="voice" @click="voiceToggle()" :aria-label="listening ? 'Stop listening' : 'Speak your message'">
                                    <svg width="17" height="17" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 1a3 3 0 00-3 3v8a3 3 0 006 0V4a3 3 0 00-3-3zM19 10v2a7 7 0 01-14 0v-2M12 19v4M8 23h8"/></svg>
                                </button>
                                <button type="button" class="ac-send" @click="send()" :disabled="busy || (!input.trim() && !file)" aria-label="Send">
                                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
                                </button>
                            </div>
                            <p class="ac-hint">Jenny can make mistakes. Check important details before you rely on them.</p>
                        </div>
                    <?php endif; ?>
                </section>
            </div>
        </main>
    </div>
</div>
