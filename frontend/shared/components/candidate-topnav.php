<?php
declare(strict_types=1);
/**
 * Shared Candidate Dashboard Top Bar Component
 * Tools only: page title, internship search, primary action, notifications and account menu.
 * Navigation lives in the candidate sidebar.
 *
 * @var string $activeNav   Active navigation identifier
 * @var array  $candidate   Candidate user info (name, role, avatar)
 */

$candName      = $candidate['name'] ?? ($_SESSION['profile_name'] ?? 'Sarah Peters');
$candFirstName = explode(' ', trim($candName))[0];
$candRole      = $candidate['role'] ?? ($_SESSION['profile_role'] ?? 'Student Applicant');
$candAvatar    = $candidate['avatar'] ?? ($_SESSION['profile_avatar'] ?? asset('assets/images/profile.png'));
$candPlan      = $_SESSION['candidate_plan'] ?? ($candidate['plan'] ?? 'pro');
$activeNav     = $activeNav ?? '';

// Page heading: title, sidebar group and icon for each candidate area
$candidatePages = [
    ['title' => 'Saved Internships',       'group' => 'Job Search',    'icon' => 'bookmark', 'keys' => ['saved']],
    ['title' => 'My Applications',         'group' => 'Job Search',    'icon' => 'document', 'keys' => ['applications', 'application', 'my-application', 'my-applications']],
    ['title' => 'Browse Opportunities',    'group' => 'Job Search',    'icon' => 'search',   'keys' => ['internships']],
    ['title' => 'AI Assistant',            'group' => 'Career Tools',  'icon' => 'sparkles', 'keys' => ['ai-assistant', 'ai-career-assistant', 'cv-analyze', 'cv-results']],
    ['title' => 'Messages',                'group' => 'Communication', 'icon' => 'chat',     'keys' => ['messages', 'message']],
    ['title' => 'Notifications',           'group' => 'Communication', 'icon' => 'bell',     'keys' => ['notifications', 'notification']],
    ['title' => 'Profile Settings',        'group' => 'Account',       'icon' => 'user',     'keys' => ['profile']],
    ['title' => 'Billings & Subscription', 'group' => 'Account',       'icon' => 'card',     'keys' => ['billings', 'billing', 'subscription', 'pricing', 'payments']],
    ['title' => 'Help Center',             'group' => 'Account',       'icon' => 'help',     'keys' => ['help']],
];
$pageHeading = ['workspace' => 'Candidate', 'group' => 'Overview', 'title' => 'Dashboard', 'icon' => 'dashboard'];
foreach ($candidatePages as $page) {
    if (in_array($activeNav, $page['keys'], true)) {
        $pageHeading = ['workspace' => 'Candidate', 'group' => $page['group'], 'title' => $page['title'], 'icon' => $page['icon']];
        break;
    }
}

$candWords = preg_split('/\s+/', trim($candName));
$candInitials = (count($candWords) >= 2) ? strtoupper(substr($candWords[0], 0, 1) . substr($candWords[1], 0, 1)) : strtoupper(substr($candName, 0, 2));
?>

<!-- ══ CANDIDATE TOP BAR ══ -->
<header class="db-cand-topnav" role="banner">
    <div class="db-cand-topnav-inner">
        <!-- Left: Mobile Menu, Logo and Page Title -->
        <div class="db-cand-topnav-left">
            <button class="db-hamburger" id="db-hamburger" type="button" aria-label="Open sidebar menu">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
            <a href="<?= url('/candidate/dashboard') ?>" class="db-cand-nav-brand">
                <img src="<?= asset('assets/images/logos/Logo_dark_dx00tg.svg') ?>" alt="InternConnect" class="db-cand-nav-logo">
            </a>
            <?php include __DIR__ . '/topbar-heading.php'; ?>

        </div>

        <!-- Center: Internship Search -->
        <form class="db-cand-nav-search db-topbar-search" action="<?= url('/internships') ?>" method="get" role="search">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input id="db-cand-search-input" type="search" name="search" placeholder="Search Internships" aria-label="Search Internships">
        </form>

        <!-- Right: Primary Action, Notifications and Account -->
        <div class="db-cand-topnav-right">
            <?php if ($candPlan === 'free'): ?>
                <a href="<?= url('/pricing?plan=pro') ?>" class="db-topbar-cta">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
                    <span>Upgrade to Pro</span>
                </a>
            <?php else: ?>
                <a href="<?= url('/internships') ?>" class="db-topbar-cta">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <span>Find Internships</span>
                </a>
            <?php endif; ?>

            <!-- Notifications -->
            <div class="db-cand-notif-btn" role="button" aria-label="Notifications" id="db-topnav-notif" tabindex="0" x-data="{ open: false }" @click.outside="open = false" @click="open = !open">
                <div class="db-cand-notif-dot" aria-hidden="true"></div>
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>

                <div x-show="open" style="display: none;" class="db-dropdown db-topbar-menu db-topbar-notif-menu" @click.stop>
                    <div class="db-topbar-menu-head">
                        <span>Notifications</span>
                        <span class="db-topbar-menu-pill">7 New</span>
                    </div>
                    <div class="db-topbar-notif-list">
                        <a href="<?= url('/candidate/applications') ?>" class="db-topbar-notif-item">
                            <span class="db-topbar-notif-icon is-blue"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg></span>
                            <span class="db-topbar-notif-body"><strong>Slack Interview Scheduled</strong><span>Tomorrow at 10:00 AM</span></span>
                        </a>
                        <a href="<?= url('/candidate/applications') ?>" class="db-topbar-notif-item">
                            <span class="db-topbar-notif-icon is-emerald"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></span>
                            <span class="db-topbar-notif-body"><strong>Uber Application Received</strong><span>2 hours ago</span></span>
                        </a>
                        <a href="<?= url('/candidate/messages') ?>" class="db-topbar-notif-item">
                            <span class="db-topbar-notif-icon is-indigo"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg></span>
                            <span class="db-topbar-notif-body"><strong>New Message from Figma</strong><span>4 hours ago</span></span>
                        </a>
                    </div>
                    <a href="<?= url('/candidate/notifications') ?>" class="db-topbar-menu-footer">View All Notifications</a>
                </div>
            </div>

            <!-- Account Menu -->
            <div class="db-cand-nav-profile" x-data="{ open: false }" @click.outside="open = false">
                <button type="button" class="db-topbar-account" aria-label="Candidate account options" :aria-expanded="open" @click="open = !open">
                    <span class="db-cand-nav-avatar">
                        <?php if ($candAvatar): ?>
                            <img src="<?= htmlspecialchars($candAvatar) ?>" alt="">
                        <?php else: ?>
                            <span class="db-avatar-initials-sm"><?= htmlspecialchars($candInitials ?: 'SP') ?></span>
                        <?php endif; ?>
                    </span>
                    <span class="db-topbar-account-text">
                        <span class="db-topbar-account-name"><?= htmlspecialchars($candName) ?></span>
                        <span class="db-topbar-account-role"><?= htmlspecialchars($candRole) ?></span>
                    </span>
                    <svg class="db-topbar-account-chevron" :class="{ 'is-open': open }" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                </button>

                <div x-show="open" style="display: none;" class="db-dropdown db-topbar-menu db-topbar-account-menu" @click.stop>
                    <div class="db-topbar-menu-identity">
                        <strong><?= htmlspecialchars($candName) ?></strong>
                        <span><?= htmlspecialchars($candRole) ?></span>
                    </div>
                    <a href="<?= url('/candidate/dashboard') ?>" class="db-topbar-menu-link">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                        <span>Dashboard</span>
                    </a>
                    <a href="<?= url('/candidate/profile') ?>" class="db-topbar-menu-link">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zm-4 7a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        <span>Profile Details</span>
                    </a>
                    <a href="<?= url('/pricing') ?>" class="db-topbar-menu-link">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                        <span>Billings & Subscription</span>
                    </a>
                    <div class="db-topbar-menu-divider"></div>
                    <a href="<?= url('/logout') ?>" class="db-topbar-menu-link is-danger">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        <span>Log Out</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</header>
