<?php
declare(strict_types=1);
/**
 * Shared Employer Dashboard Top Bar Component
 * Tools only: page title, candidate search, primary action, notifications and account menu.
 * Navigation lives in the employer sidebar.
 *
 * @var string $activeNav     Active navigation identifier
 * @var array  $employer      Employer info (company_name, role, avatar)
 */

$companyName = $employer['company_name'] ?? ($_SESSION['company_name'] ?? 'Global Tech Solutions');
$compRole    = $employer['role'] ?? ($_SESSION['company_role'] ?? 'Employer Portal');
$compAvatar  = $employer['avatar'] ?? ($_SESSION['company_avatar'] ?? null);
$activeNav   = $activeNav ?? 'dashboard';

// Page heading: title, sidebar group and icon for each employer area
$employerPages = [
    ['title' => 'Post Internship',     'group' => 'Hiring',        'icon' => 'plus',      'keys' => ['post', 'create_internship', 'post_internship']],
    ['title' => 'Posted Internships',  'group' => 'Hiring',        'icon' => 'briefcase', 'keys' => ['internships', 'my_internships', 'posted_internships']],
    ['title' => 'Candidates',          'group' => 'Hiring',        'icon' => 'users',     'keys' => ['candidates', 'applications']],
    ['title' => 'Interviews',          'group' => 'Hiring',        'icon' => 'calendar',  'keys' => ['interviews', 'schedule_interview']],
    ['title' => 'Company Overview',    'group' => 'Company',       'icon' => 'building',  'keys' => ['company', 'companies', 'company_overview']],
    ['title' => 'Analytics & Reports', 'group' => 'Company',       'icon' => 'chart',     'keys' => ['analytics', 'reports', 'hiring_analytics']],
    ['title' => 'Messages',            'group' => 'Communication', 'icon' => 'chat',      'keys' => ['messages']],
    ['title' => 'AI Assistant',        'group' => 'Communication', 'icon' => 'sparkles',  'keys' => ['ai_assistant', 'ai-assistant']],
    ['title' => 'Notifications',       'group' => 'Communication', 'icon' => 'bell',      'keys' => ['notifications', 'notification']],
    ['title' => 'Profile Settings',    'group' => 'Account',       'icon' => 'user',      'keys' => ['profile', 'settings', 'profile_settings']],
];
$pageHeading = ['workspace' => 'Employer', 'group' => 'Overview', 'title' => 'Dashboard', 'icon' => 'dashboard'];
foreach ($employerPages as $page) {
    if (in_array($activeNav, $page['keys'], true)) {
        $pageHeading = ['workspace' => 'Employer', 'group' => $page['group'], 'title' => $page['title'], 'icon' => $page['icon']];
        break;
    }
}

$compWords = preg_split('/\s+/', trim($companyName));
$compInitials = (count($compWords) >= 2) ? strtoupper(substr($compWords[0], 0, 1) . substr($compWords[1], 0, 1)) : strtoupper(substr($companyName, 0, 2));
?>

<!-- ══ EMPLOYER TOP BAR ══ -->
<header class="db-cand-topnav" role="banner">
    <div class="db-cand-topnav-inner">
        <!-- Left: Mobile Menu, Logo and Page Title -->
        <div class="db-cand-topnav-left">
            <button class="db-hamburger" id="db-hamburger" type="button" aria-label="Open sidebar menu">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
            <a href="<?= url('/employer/dashboard') ?>" class="db-cand-nav-brand">
                <img src="<?= asset('assets/images/logos/Logo_dark_dx00tg.svg') ?>" alt="InternConnect" class="db-cand-nav-logo">
            </a>
            <?php include __DIR__ . '/topbar-heading.php'; ?>

        </div>

        <!-- Center: Candidate Search -->
        <form class="db-cand-nav-search db-topbar-search" action="<?= url('/employer/candidates') ?>" method="get" role="search">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input id="db-emp-search-input" type="search" name="search" placeholder="Search candidates, skills..." aria-label="Search candidates">
        </form>

        <!-- Right: Primary Action, Notifications and Account -->
        <div class="db-cand-topnav-right">
            <a href="<?= url('/employer/internships/create') ?>" class="db-topbar-cta">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                <span>Post Internship</span>
            </a>

            <!-- Notifications -->
            <div class="db-cand-notif-btn" role="button" aria-label="Notifications" id="db-topnav-notif" tabindex="0" x-data="{ open: false }" @click.outside="open = false" @click="open = !open">
                <div class="db-cand-notif-dot" aria-hidden="true"></div>
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>

                <div x-show="open" style="display: none;" class="db-dropdown db-topbar-menu db-topbar-notif-menu" @click.stop>
                    <div class="db-topbar-menu-head">
                        <span>Employer Alerts</span>
                        <span class="db-topbar-menu-pill">3 New</span>
                    </div>
                    <div class="db-topbar-notif-list">
                        <a href="<?= url('/employer/candidates') ?>" class="db-topbar-notif-item">
                            <span class="db-topbar-notif-icon is-indigo"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg></span>
                            <span class="db-topbar-notif-body"><strong>New Applicant: Sandra Alex</strong><span>Senior UX Designer • 2m ago</span></span>
                        </a>
                        <a href="<?= url('/employer/interviews') ?>" class="db-topbar-notif-item">
                            <span class="db-topbar-notif-icon is-amber"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg></span>
                            <span class="db-topbar-notif-body"><strong>Interview Confirmed: Sarah Peters</strong><span>Tomorrow at 10:00 AM</span></span>
                        </a>
                        <a href="<?= url('/employer/messages') ?>" class="db-topbar-notif-item">
                            <span class="db-topbar-notif-icon is-blue"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg></span>
                            <span class="db-topbar-notif-body"><strong>Message from Marcus Jordan</strong><span>2 hours ago</span></span>
                        </a>
                    </div>
                    <a href="<?= url('/employer/notifications') ?>" class="db-topbar-menu-footer">View All Notifications</a>
                </div>
            </div>

            <!-- Account Menu -->
            <div class="db-cand-nav-profile" x-data="{ open: false }" @click.outside="open = false">
                <button type="button" class="db-topbar-account" aria-label="Employer account options" :aria-expanded="open" @click="open = !open">
                    <span class="db-cand-nav-avatar">
                        <?php if ($compAvatar): ?>
                            <img src="<?= htmlspecialchars($compAvatar) ?>" alt="">
                        <?php else: ?>
                            <span class="db-avatar-initials-sm" style="background: linear-gradient(135deg, #0052CC, #4F46E5);"><?= htmlspecialchars($compInitials ?: 'IC') ?></span>
                        <?php endif; ?>
                    </span>
                    <span class="db-topbar-account-text">
                        <span class="db-topbar-account-name"><?= htmlspecialchars($companyName) ?></span>
                        <span class="db-topbar-account-role"><?= htmlspecialchars($compRole) ?></span>
                    </span>
                    <svg class="db-topbar-account-chevron" :class="{ 'is-open': open }" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                </button>

                <div x-show="open" style="display: none;" class="db-dropdown db-topbar-menu db-topbar-account-menu" @click.stop>
                    <div class="db-topbar-menu-identity">
                        <strong><?= htmlspecialchars($companyName) ?></strong>
                        <span><?= htmlspecialchars($compRole) ?></span>
                    </div>
                    <a href="<?= url('/employer/dashboard') ?>" class="db-topbar-menu-link">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                        <span>Dashboard</span>
                    </a>
                    <a href="<?= url('/employer/company') ?>" class="db-topbar-menu-link">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        <span>Company Profile</span>
                    </a>
                    <a href="<?= url('/pricing') ?>" class="db-topbar-menu-link">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                        <span>Plans & Billing</span>
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
