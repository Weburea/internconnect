<?php
declare(strict_types=1);
/**
 * Shared Notification Center (inbox layout)
 * Used by: frontend/candidate/notifications/index.php, frontend/employer/notifications/index.php
 * Spec: docs/13-notification-system.md — "Notification Center UI"
 *
 * Layout: category rail (left, becomes a scrollable chip bar below 1024px) + inbox panel
 * (search, date-grouped rows, pagination). Rows are compact; nothing scrolls forever.
 *
 * @var array  $notifications    Items from CommunicationService::presentNotifications()
 * @var array  $notifCategories  From CommunicationService::notificationCategories()
 * @var array  $notifConfig      ['subtitle', 'markReadUrl', 'markAllUrl', 'deleteUrl', 'settingsUrl', 'perPage']
 */

$notifConfig = $notifConfig ?? [];
$ncCategories = [];
foreach (($notifCategories ?? []) as $key => $cat) {
    $ncCategories[] = ['key' => $key, 'label' => $cat['label'], 'icon' => $cat['icon'], 'tone' => $cat['tone']];
}

$ncIcons = [
    'inbox'    => 'M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4',
    'unread'   => 'M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9',
    'calendar' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z',
    'badge'    => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
    'document' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
    'chat'     => 'M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z',
    'sparkles' => 'M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z',
    'shield'   => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z',
];

$ncPayload = [
    'items'       => array_values($notifications ?? []),
    'categories'  => $ncCategories,
    'icons'       => $ncIcons,
    'markReadUrl' => $notifConfig['markReadUrl'] ?? '',
    'markAllUrl'  => $notifConfig['markAllUrl'] ?? '',
    'deleteUrl'   => $notifConfig['deleteUrl'] ?? '',
    'perPage'     => (int)($notifConfig['perPage'] ?? 15),
];
?>
<style>
/* ==========================================================================
   NOTIFICATION CENTER — shared inbox (docs/13-notification-system.md)
   ========================================================================== */
.nc-wrap { max-width: 1240px; margin: 0 auto; padding-bottom: 32px; }
.nc-wrap *, .nc-wrap *::before, .nc-wrap *::after { box-sizing: border-box; }

.nc-head { display: flex; align-items: flex-end; justify-content: space-between; gap: 12px 20px; flex-wrap: wrap; margin-bottom: 20px; }
.nc-head-text { min-width: 0; flex: 1 1 320px; }
.nc-title { font-size: 24px; font-weight: 800; letter-spacing: -0.02em; color: #000623; margin: 0 0 4px; line-height: 1.25; }
.nc-sub { font-size: 13.5px; color: #64748B; margin: 0; line-height: 1.5; }
.nc-head-actions { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; }

.nc-btn { display: inline-flex; align-items: center; justify-content: center; gap: 7px; height: 38px; padding: 0 14px; border-radius: 10px; border: 1px solid #E2E8F0; background: #FFFFFF; font-size: 13px; font-weight: 600; color: #334155; cursor: pointer; text-decoration: none; white-space: nowrap; flex-shrink: 0; transition: background .15s ease, border-color .15s ease, color .15s ease; }
.nc-btn:hover:not(:disabled) { background: #F8FAFC; border-color: #CBD5E1; color: #0F172A; }
.nc-btn:disabled { opacity: .5; cursor: not-allowed; }
.nc-btn svg { width: 15px; height: 15px; flex-shrink: 0; }
.nc-btn.is-primary { background: #4F48E6; border-color: #4F48E6; color: #FFFFFF; }
.nc-btn.is-primary:hover:not(:disabled) { background: #4338CA; border-color: #4338CA; color: #FFFFFF; }

.nc-layout { display: grid; grid-template-columns: 240px minmax(0, 1fr); gap: 20px; align-items: start; }

/* Category rail */
.nc-rail { position: sticky; top: 88px; background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 16px; padding: 10px; }
.nc-rail-label { font-size: 10.5px; font-weight: 700; letter-spacing: .07em; text-transform: uppercase; color: #94A3B8; padding: 10px 10px 6px; }
.nc-rail-item { display: flex; align-items: center; gap: 10px; width: 100%; height: 38px; padding: 0 10px; border: 0; border-radius: 9px; background: transparent; font-size: 13px; font-weight: 600; color: #475569; cursor: pointer; text-align: left; white-space: nowrap; transition: background .15s ease, color .15s ease; }
.nc-rail-item:hover { background: #F8FAFC; color: #0F172A; }
.nc-rail-item.is-active { background: #EEF2FF; color: #4F48E6; }
.nc-rail-item svg { width: 16px; height: 16px; flex-shrink: 0; }
.nc-rail-item .nc-rail-text { flex: 1; min-width: 0; overflow: hidden; text-overflow: ellipsis; }
.nc-count { min-width: 22px; height: 20px; padding: 0 6px; border-radius: 9999px; font-size: 11px; font-weight: 700; display: inline-flex; align-items: center; justify-content: center; background: #F1F5F9; color: #64748B; flex-shrink: 0; }
.nc-count.is-unread { background: #4F48E6; color: #FFFFFF; }
.nc-rail-foot { margin-top: 8px; padding: 10px 10px 4px; border-top: 1px solid #F1F5F9; }
.nc-rail-foot a { display: inline-flex; align-items: center; gap: 6px; font-size: 12.5px; font-weight: 600; color: #64748B; text-decoration: none; }
.nc-rail-foot a:hover { color: #4F48E6; }
.nc-rail-foot svg { width: 14px; height: 14px; }

/* Inbox panel */
.nc-panel { background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 16px; min-width: 0; overflow: hidden; }
.nc-toolbar { display: flex; align-items: center; gap: 12px; padding: 14px 16px; border-bottom: 1px solid #F1F5F9; flex-wrap: wrap; }
.nc-search { position: relative; flex: 1 1 260px; min-width: 0; }
.nc-search svg { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); width: 15px; height: 15px; color: #94A3B8; pointer-events: none; }
.nc-search input { width: 100%; height: 38px; padding: 0 34px 0 36px; border: 1px solid #E2E8F0; border-radius: 10px; background: #F8FAFC; font-size: 13px; color: #0F172A; outline: none; transition: border-color .15s ease, box-shadow .15s ease, background .15s ease; }
.nc-search input:focus { border-color: #4F48E6; background: #FFFFFF; box-shadow: 0 0 0 3px rgba(79,72,230,.1); }
.nc-search-clear { position: absolute; right: 6px; top: 50%; transform: translateY(-50%); width: 26px; height: 26px; border: 0; border-radius: 7px; background: transparent; color: #94A3B8; display: flex; align-items: center; justify-content: center; cursor: pointer; }
.nc-search-clear:hover { background: #F1F5F9; color: #475569; }
.nc-search-clear svg { position: static; transform: none; width: 13px; height: 13px; }
.nc-summary { font-size: 12.5px; color: #64748B; white-space: nowrap; }
.nc-summary strong { color: #0F172A; font-weight: 700; }

.nc-group-label { display: flex; align-items: center; justify-content: space-between; padding: 14px 18px 6px; font-size: 11px; font-weight: 700; letter-spacing: .07em; text-transform: uppercase; color: #94A3B8; background: #FFFFFF; }

.nc-row { position: relative; display: flex; align-items: flex-start; gap: 14px; padding: 14px 18px; transition: background .15s ease; cursor: pointer; }
.nc-row + .nc-row { border-top: 1px solid #F4F5F8; }
.nc-row:hover { background: #F8FAFC; }
.nc-row.is-unread { background: #FAFAFF; }
.nc-row.is-unread:hover { background: #F4F4FF; }
.nc-row:focus-visible { outline: 2px solid #4F48E6; outline-offset: -2px; }

.nc-icon { width: 38px; height: 38px; border-radius: 11px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.nc-icon svg { width: 18px; height: 18px; }
.nc-tone-indigo { background: #EEF2FF; color: #4F48E6; }
.nc-tone-green  { background: #ECFDF5; color: #059669; }
.nc-tone-blue   { background: #EFF6FF; color: #2563EB; }
.nc-tone-sky    { background: #F0F9FF; color: #0284C7; }
.nc-tone-violet { background: #F5F3FF; color: #7C3AED; }
.nc-tone-amber  { background: #FFFBEB; color: #D97706; }

.nc-body { flex: 1; min-width: 0; }
.nc-row-top { display: flex; align-items: baseline; gap: 10px; }
.nc-row-title { flex: 1; min-width: 0; font-size: 13.5px; font-weight: 600; color: #334155; line-height: 1.4; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; margin: 0; }
.nc-row.is-unread .nc-row-title { font-weight: 700; color: #0F172A; }
.nc-time { font-size: 12px; color: #94A3B8; white-space: nowrap; flex-shrink: 0; }
.nc-row.is-unread .nc-time { color: #4F48E6; font-weight: 600; }
.nc-row-text { font-size: 13px; color: #64748B; line-height: 1.5; margin: 2px 0 0; display: -webkit-box; -webkit-line-clamp: 1; -webkit-box-orient: vertical; overflow: hidden; }
.nc-row-meta { display: flex; align-items: center; gap: 10px; margin-top: 6px; flex-wrap: wrap; }
.nc-tag { font-size: 11px; font-weight: 600; color: #64748B; background: #F1F5F9; padding: 2px 8px; border-radius: 9999px; white-space: nowrap; }
.nc-link { display: inline-flex; align-items: center; gap: 4px; font-size: 12px; font-weight: 700; color: #4F48E6; text-decoration: none; white-space: nowrap; }
.nc-link:hover { color: #3730A3; }
.nc-link svg { width: 12px; height: 12px; }

.nc-dot { position: absolute; left: 6px; top: 50%; width: 6px; height: 6px; margin-top: -3px; border-radius: 50%; background: #4F48E6; }

.nc-row-actions { display: flex; align-items: center; gap: 2px; flex-shrink: 0; opacity: 0; transition: opacity .15s ease; }
.nc-row:hover .nc-row-actions, .nc-row:focus-within .nc-row-actions { opacity: 1; }
@media (hover: none) { .nc-row-actions { opacity: 1; } }
.nc-icon-btn { width: 30px; height: 30px; border: 0; border-radius: 8px; background: transparent; color: #94A3B8; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: background .15s ease, color .15s ease; }
.nc-icon-btn:hover { background: #EEF0F4; color: #334155; }
.nc-icon-btn.is-danger:hover { background: #FEF2F2; color: #DC2626; }
.nc-icon-btn svg { width: 15px; height: 15px; }

/* Pagination */
.nc-foot { display: flex; align-items: center; justify-content: space-between; gap: 12px; padding: 12px 16px; border-top: 1px solid #F1F5F9; flex-wrap: wrap; }
.nc-range { font-size: 12.5px; color: #64748B; white-space: nowrap; }
.nc-pager { display: flex; align-items: center; gap: 4px; }
.nc-page { min-width: 34px; height: 34px; padding: 0 8px; border: 1px solid transparent; border-radius: 9px; background: transparent; font-size: 13px; font-weight: 600; color: #475569; cursor: pointer; display: inline-flex; align-items: center; justify-content: center; }
.nc-page:hover:not(:disabled):not(.is-active) { background: #F1F5F9; }
.nc-page.is-active { background: #EEF2FF; color: #4F48E6; border-color: #C7D2FE; }
.nc-page:disabled { color: #CBD5E1; cursor: not-allowed; }
.nc-page svg { width: 15px; height: 15px; }
.nc-ellipsis { min-width: 22px; text-align: center; color: #94A3B8; font-size: 13px; }

/* Empty state */
.nc-empty { padding: 56px 24px; text-align: center; }
.nc-empty-icon { width: 52px; height: 52px; border-radius: 16px; background: #F1F5F9; color: #94A3B8; display: flex; align-items: center; justify-content: center; margin: 0 auto 14px; }
.nc-empty-icon svg { width: 24px; height: 24px; }
.nc-empty h3 { font-size: 15px; font-weight: 700; color: #0F172A; margin: 0 0 4px; }
.nc-empty p { font-size: 13px; color: #64748B; margin: 0; }

/* Undo bar */
.nc-undo { position: fixed; left: 50%; bottom: 24px; transform: translateX(-50%); z-index: 60; display: flex; align-items: center; gap: 14px; padding: 10px 10px 10px 16px; border-radius: 12px; background: #0F172A; color: #E2E8F0; font-size: 13px; box-shadow: 0 16px 40px rgba(15,23,42,.3); white-space: nowrap; max-width: calc(100vw - 32px); }
.nc-undo button { height: 30px; padding: 0 12px; border: 0; border-radius: 8px; background: rgba(255,255,255,.12); color: #FFFFFF; font-size: 12.5px; font-weight: 700; cursor: pointer; }
.nc-undo button:hover { background: rgba(255,255,255,.2); }

/* Tablet and phone: rail becomes a horizontal chip bar */
@media (max-width: 1024px) {
    .nc-layout { grid-template-columns: minmax(0, 1fr); gap: 14px; }
    .nc-rail { position: static; display: flex; gap: 6px; padding: 6px; overflow-x: auto; border-radius: 14px; scrollbar-width: none; }
    .nc-rail::-webkit-scrollbar { display: none; }
    .nc-rail-label, .nc-rail-foot { display: none; }
    .nc-rail-item { width: auto; flex-shrink: 0; height: 36px; padding: 0 12px; }
    .nc-rail-item .nc-rail-text { overflow: visible; }
}
@media (max-width: 640px) {
    .nc-title { font-size: 21px; }
    .nc-head-actions { width: 100%; }
    .nc-head-actions .nc-btn { flex: 1; }
    .nc-toolbar { padding: 12px; }
    .nc-summary { display: none; }
    .nc-row { padding: 13px 12px 13px 16px; gap: 12px; }
    .nc-icon { width: 34px; height: 34px; border-radius: 10px; }
    .nc-icon svg { width: 16px; height: 16px; }
    .nc-row-text { -webkit-line-clamp: 2; }
    .nc-row-actions { flex-direction: column; gap: 0; margin: -4px -4px 0 0; }
    .nc-group-label { padding: 12px 16px 4px; }
    .nc-foot { justify-content: center; }
    .nc-range { width: 100%; text-align: center; }
    .nc-row-title { white-space: normal; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; }
    .nc-row-top { align-items: flex-start; }
}
</style>

<?php if (empty($GLOBALS['__ncScript'])): $GLOBALS['__ncScript'] = true; ?>
<script>
function notificationCenter(cfg) {
    const GROUPS = { today: 'Today', yesterday: 'Yesterday', week: 'Earlier this week', earlier: 'Older' };
    return {
        items: cfg.items || [],
        categories: cfg.categories || [],
        icons: cfg.icons || {},
        perPage: cfg.perPage || 15,
        active: 'all',
        query: '',
        page: 1,
        pendingDelete: null,
        deleteTimer: null,

        init() {
            try {
                const params = new URLSearchParams(window.location.search);
                const filter = params.get('filter');
                if (filter && (filter === 'unread' || this.categories.some((c) => c.key === filter))) this.active = filter;
            } catch (e) {}
            this.$watch('query', () => { this.page = 1; });
        },

        category(key) { return this.categories.find((c) => c.key === key) || { label: 'Update', icon: 'shield', tone: 'amber' }; },
        iconPath(name) { return this.icons[name] || this.icons.inbox; },

        get unreadTotal() { return this.items.filter((i) => !i.is_read).length; },
        countFor(key) {
            if (key === 'all') return this.items.length;
            if (key === 'unread') return this.unreadTotal;
            return this.items.filter((i) => i.category === key).length;
        },
        unreadFor(key) { return this.items.filter((i) => i.category === key && !i.is_read).length; },

        get filtered() {
            const q = this.query.trim().toLowerCase();
            return this.items.filter((i) => {
                if (this.active === 'unread' && i.is_read) return false;
                if (this.active !== 'all' && this.active !== 'unread' && i.category !== this.active) return false;
                if (q && !(i.title + ' ' + i.body).toLowerCase().includes(q)) return false;
                return true;
            });
        },
        get totalPages() { return Math.max(1, Math.ceil(this.filtered.length / this.perPage)); },
        get pageItems() {
            const start = (this.page - 1) * this.perPage;
            return this.filtered.slice(start, start + this.perPage);
        },
        get groups() {
            const out = [];
            this.pageItems.forEach((item) => {
                const last = out[out.length - 1];
                if (last && last.key === item.group) last.items.push(item);
                else out.push({ key: item.group, label: GROUPS[item.group] || 'Older', items: [item] });
            });
            return out;
        },
        get rangeLabel() {
            const total = this.filtered.length;
            if (!total) return 'No notifications';
            const start = (this.page - 1) * this.perPage + 1;
            return 'Showing ' + start + '-' + Math.min(total, start + this.perPage - 1) + ' of ' + total;
        },
        get pageList() {
            const total = this.totalPages, cur = this.page, pages = [];
            for (let p = 1; p <= total; p++) {
                if (p === 1 || p === total || Math.abs(p - cur) <= 1) pages.push({ n: p, far: Math.abs(p - cur) > 1 });
                else if (pages[pages.length - 1] !== '...') pages.push('...');
            }
            return pages;
        },
        get activeLabel() {
            if (this.active === 'all') return 'All notifications';
            if (this.active === 'unread') return 'Unread';
            return this.category(this.active).label;
        },

        setFilter(key) {
            this.active = key;
            this.page = 1;
            try {
                const url = new URL(window.location.href);
                if (key === 'all') url.searchParams.delete('filter'); else url.searchParams.set('filter', key);
                window.history.replaceState({}, '', url);
            } catch (e) {}
        },
        goTo(p) {
            if (p < 1 || p > this.totalPages) return;
            this.page = p;
            this.$nextTick(() => { const el = this.$refs.panel; if (el && el.getBoundingClientRect().top < 0) el.scrollIntoView({ behavior: 'smooth', block: 'start' }); });
        },

        post(url, body) {
            if (!url) return Promise.resolve();
            return fetch(url, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                body: JSON.stringify(body || {})
            }).catch((err) => console.error('Notification request failed', err));
        },

        open(item) {
            if (!item.is_read) {
                item.is_read = true;
                this.post(cfg.markReadUrl, { id: item.id });
            }
            if (item.action_url) window.location.href = item.action_url;
        },
        markRead(item) {
            if (item.is_read) return;
            item.is_read = true;
            this.post(cfg.markReadUrl, { id: item.id });
        },
        markAllRead() {
            if (!this.unreadTotal) return;
            this.items.forEach((i) => { i.is_read = true; });
            this.post(cfg.markAllUrl);
            window.dispatchEvent(new CustomEvent('show-toast', { detail: { text: 'All notifications marked as read.', type: 'success' } }));
        },

        // Delete with a short undo window instead of a blocking dialog
        remove(item) {
            this.flushDelete();
            const index = this.items.indexOf(item);
            if (index === -1) return;
            this.items.splice(index, 1);
            this.pendingDelete = { item, index };
            if (this.page > this.totalPages) this.page = this.totalPages;
            this.deleteTimer = setTimeout(() => this.flushDelete(), 5000);
        },
        undoDelete() {
            if (!this.pendingDelete) return;
            clearTimeout(this.deleteTimer);
            this.items.splice(this.pendingDelete.index, 0, this.pendingDelete.item);
            this.pendingDelete = null;
        },
        flushDelete() {
            if (!this.pendingDelete) return;
            clearTimeout(this.deleteTimer);
            this.post(cfg.deleteUrl, { id: this.pendingDelete.item.id });
            this.pendingDelete = null;
        }
    };
}
</script>
<?php endif; ?>

<div class="nc-wrap" x-data="notificationCenter(<?= htmlspecialchars(json_encode($ncPayload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE), ENT_QUOTES, 'UTF-8') ?>)" @beforeunload.window="flushDelete()">

    <!-- Header -->
    <div class="nc-head">
        <div class="nc-head-text">
            <h1 class="nc-title">Notifications</h1>
            <p class="nc-sub"><?= htmlspecialchars($notifConfig['subtitle'] ?? 'Stay up to date with everything happening on your account.') ?></p>
        </div>
        <div class="nc-head-actions">
            <?php if (!empty($notifConfig['settingsUrl'])): ?>
            <a href="<?= htmlspecialchars($notifConfig['settingsUrl']) ?>" class="nc-btn">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
                <span>Preferences</span>
            </a>
            <?php endif; ?>
            <button type="button" class="nc-btn is-primary" @click="markAllRead()" :disabled="!unreadTotal">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.4"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                <span>Mark all as read</span>
            </button>
        </div>
    </div>

    <div class="nc-layout">

        <!-- Category Rail -->
        <nav class="nc-rail no-scrollbar" aria-label="Notification categories">
            <div class="nc-rail-label">Inbox</div>
            <button type="button" class="nc-rail-item" :class="{ 'is-active': active === 'all' }" :aria-current="active === 'all'" @click="setFilter('all')">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" :d="iconPath('inbox')"/></svg>
                <span class="nc-rail-text">All</span>
                <span class="nc-count" x-text="countFor('all')"></span>
            </button>
            <button type="button" class="nc-rail-item" :class="{ 'is-active': active === 'unread' }" :aria-current="active === 'unread'" @click="setFilter('unread')">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" :d="iconPath('unread')"/></svg>
                <span class="nc-rail-text">Unread</span>
                <span class="nc-count" :class="{ 'is-unread': unreadTotal > 0 }" x-text="unreadTotal"></span>
            </button>

            <div class="nc-rail-label">Categories</div>
            <template x-for="cat in categories" :key="cat.key">
                <button type="button" class="nc-rail-item" :class="{ 'is-active': active === cat.key }" :aria-current="active === cat.key" @click="setFilter(cat.key)">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" :d="iconPath(cat.icon)"/></svg>
                    <span class="nc-rail-text" x-text="cat.label"></span>
                    <span class="nc-count" :class="{ 'is-unread': unreadFor(cat.key) > 0 }" x-text="unreadFor(cat.key) > 0 ? unreadFor(cat.key) : countFor(cat.key)"></span>
                </button>
            </template>

            <?php if (!empty($notifConfig['settingsUrl'])): ?>
            <div class="nc-rail-foot">
                <a href="<?= htmlspecialchars($notifConfig['settingsUrl']) ?>">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065zM15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <span>Notification preferences</span>
                </a>
            </div>
            <?php endif; ?>
        </nav>

        <!-- Inbox Panel -->
        <section class="nc-panel" x-ref="panel" aria-live="polite">
            <div class="nc-toolbar">
                <label class="nc-search">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input type="search" x-model.debounce.200ms="query" placeholder="Search notifications" aria-label="Search notifications">
                    <button type="button" class="nc-search-clear" x-show="query" style="display: none;" @click="query = ''" aria-label="Clear search">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </label>
                <span class="nc-summary"><strong x-text="activeLabel"></strong> &middot; <span x-text="filtered.length + (filtered.length === 1 ? ' item' : ' items')"></span></span>
            </div>

            <!-- Date-grouped rows -->
            <template x-for="group in groups" :key="group.key + '-' + page">
                <div role="list" :aria-label="group.label">
                    <div class="nc-group-label"><span x-text="group.label"></span></div>
                    <template x-for="item in group.items" :key="item.id">
                        <article class="nc-row" role="listitem" tabindex="0" :class="{ 'is-unread': !item.is_read }" @click="open(item)" @keydown.enter.self="open(item)">
                            <span class="nc-dot" x-show="!item.is_read" aria-hidden="true"></span>
                            <span class="nc-icon" :class="'nc-tone-' + category(item.category).tone" aria-hidden="true">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" :d="iconPath(category(item.category).icon)"/></svg>
                            </span>
                            <div class="nc-body">
                                <div class="nc-row-top">
                                    <h2 class="nc-row-title" x-text="item.title"></h2>
                                    <time class="nc-time" :datetime="new Date(item.timestamp * 1000).toISOString()" :aria-label="item.full_time" x-text="item.time_label"></time>
                                </div>
                                <p class="nc-row-text" x-text="item.body"></p>
                                <div class="nc-row-meta">
                                    <span class="nc-tag" x-text="category(item.category).label"></span>
                                    <a class="nc-link" :href="item.action_url" @click.stop="markRead(item)">
                                        <span x-text="item.action_label"></span>
                                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                                    </a>
                                </div>
                            </div>
                            <div class="nc-row-actions">
                                <button type="button" class="nc-icon-btn" x-show="!item.is_read" @click.stop="markRead(item)" aria-label="Mark as read">
                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.4"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                </button>
                                <button type="button" class="nc-icon-btn is-danger" @click.stop="remove(item)" aria-label="Delete notification">
                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </div>
                        </article>
                    </template>
                </div>
            </template>

            <!-- Empty state -->
            <div class="nc-empty" x-show="filtered.length === 0" style="display: none;">
                <div class="nc-empty-icon">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" :d="query ? 'M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z' : iconPath('inbox')"/></svg>
                </div>
                <h3 x-text="query ? 'No matching notifications' : (active === 'unread' ? 'You are all caught up' : 'Nothing here yet')"></h3>
                <p x-text="query ? 'Try a different search term or category.' : 'New updates in this category will appear here.'"></p>
            </div>

            <!-- Pagination -->
            <div class="nc-foot" x-show="filtered.length > 0">
                <span class="nc-range" x-text="rangeLabel"></span>
                <div class="nc-pager" x-show="totalPages > 1">
                    <button type="button" class="nc-page" :disabled="page === 1" @click="goTo(page - 1)" aria-label="Previous page">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                    </button>
                    <template x-for="(p, idx) in pageList" :key="'p' + idx + '-' + (p.n || p)">
                        <span style="display: contents;">
                            <span class="nc-ellipsis" x-show="p === '...'">...</span>
                            <button type="button" class="nc-page" x-show="p !== '...'" :class="{ 'is-active': p.n === page, 'is-far': p.far }" @click="goTo(p.n)" :aria-current="p.n === page ? 'page' : null" x-text="p.n"></button>
                        </span>
                    </template>
                    <button type="button" class="nc-page" :disabled="page === totalPages" @click="goTo(page + 1)" aria-label="Next page">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                    </button>
                </div>
            </div>
        </section>
    </div>

    <!-- Undo bar for deletes -->
    <div class="nc-undo" x-show="pendingDelete" x-transition.opacity style="display: none;" role="status">
        <span>Notification deleted</span>
        <button type="button" @click="undoDelete()">Undo</button>
    </div>
</div>
