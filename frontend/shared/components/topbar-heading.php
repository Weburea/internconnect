<?php
declare(strict_types=1);
/**
 * Shared Top Bar Page Heading
 * Icon tile + "Workspace / Section" trail + current page title.
 * Used by the candidate, employer and admin top bars.
 *
 * @var array $pageHeading ['workspace' => string, 'group' => string, 'title' => string, 'icon' => string]
 */

$pageHeading = $pageHeading ?? [];
$phWorkspace = $pageHeading['workspace'] ?? '';
$phGroup     = $pageHeading['group'] ?? '';
$phTitle     = $pageHeading['title'] ?? 'Dashboard';
$phIcon      = $pageHeading['icon'] ?? 'dashboard';

$phIcons = [
    'dashboard' => 'M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z',
    'search'    => 'M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z',
    'bookmark'  => 'M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z',
    'document'  => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
    'sparkles'  => 'M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z',
    'chat'      => 'M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z',
    'bell'      => 'M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9',
    'user'      => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z',
    'card'      => 'M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z',
    'help'      => 'M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
    'plus'      => 'M12 4v16m8-8H4',
    'briefcase' => 'M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z',
    'users'     => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z',
    'calendar'  => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z',
    'building'  => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4',
    'chart'     => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z',
    'book'      => 'M12 6.253v13C10.832 18.477 9.246 18 7.5 18S4.168 18.477 3 19.253v-13C4.168 5.477 5.754 5 7.5 5s3.332.477 4.5 1.253zm0 0C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253',
    'shield'    => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z',
    'cog'       => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065zM15 12a3 3 0 11-6 0 3 3 0 016 0z',
];
$phPath = $phIcons[$phIcon] ?? $phIcons['dashboard'];
?>
<?php if (empty($GLOBALS['__icPageHeadingStyles'])): $GLOBALS['__icPageHeadingStyles'] = true; ?>
<style>
.ic-page-heading { display: flex; flex-direction: row; align-items: center; line-height: normal; gap: 12px; min-width: 0; flex-shrink: 1; }
.ic-page-heading-icon { width: 38px; height: 38px; border-radius: 11px; background: #EEF2FF; color: #4F48E6; display: flex; align-items: center; justify-content: center; flex-shrink: 0; box-shadow: inset 0 0 0 1px rgba(79, 72, 230, 0.08); }
.ic-page-heading-icon svg { width: 18px; height: 18px; display: block; }
.ic-page-heading-text { display: flex; flex-direction: column; gap: 3px; min-width: 0; }
.ic-page-heading-trail { display: flex; align-items: center; gap: 5px; font-size: 11.5px; font-weight: 500; line-height: 1; color: #94A3B8; white-space: nowrap; min-width: 0; }
.ic-page-heading-trail svg { width: 10px; height: 10px; flex-shrink: 0; color: #CBD5E1; }
.ic-page-heading-trail span { overflow: hidden; text-overflow: ellipsis; }
.ic-page-heading-trail .is-group { color: #64748B; font-weight: 600; }
@media (max-width: 900px) { .db-cand-topnav .ic-page-heading { display: none; } }
.ic-page-heading-title { margin: 0; font-size: 16px; font-weight: 700; line-height: 1.2; letter-spacing: -0.01em; color: #0F172A; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
</style>
<?php endif; ?>
<div class="ic-page-heading db-topbar-heading">
    <span class="ic-page-heading-icon" aria-hidden="true">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.9"><path stroke-linecap="round" stroke-linejoin="round" d="<?= $phPath ?>"/></svg>
    </span>
    <span class="ic-page-heading-text">
        <span class="ic-page-heading-trail">
            <?php if ($phWorkspace !== ''): ?>
                <span><?= htmlspecialchars($phWorkspace) ?></span>
            <?php endif; ?>
            <?php if ($phWorkspace !== '' && $phGroup !== ''): ?>
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
            <?php endif; ?>
            <?php if ($phGroup !== ''): ?>
                <span class="is-group"><?= htmlspecialchars($phGroup) ?></span>
            <?php endif; ?>
        </span>
        <span class="ic-page-heading-title"><?= htmlspecialchars($phTitle) ?></span>
    </span>
</div>
