<?php
declare(strict_types=1);
/**
 * Shared Admin Mission Control Sidebar Component
 * Grouped navigation with live queue counts, collapsible submenus and a pinned staff card.
 *
 * @var string $activeNav Active navigation identifier
 * @var array  $staff     Current admin staff member data
 */

$staffName = $staff['name'] ?? 'Jayden Okafor';
$staffRole = $staff['role'] ?? 'Platform Admin';
$staffInitials = $staff['initials'] ?? 'JO';
$activeNav = $activeNav ?? 'overview';
$currentTab = $_GET['tab'] ?? '';
$onAiChat = str_contains($_SERVER['REQUEST_URI'] ?? '', '/admin/ai/chat');

$isInterns = in_array($activeNav, ['interns', 'candidates'], true);
$isEmployers = in_array($activeNav, ['employers', 'verification'], true);
$isAi = in_array($activeNav, ['ai', 'ai_chat'], true);

$chevron = '<svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>';
?>

<!-- Mobile Sidebar Backdrop Overlay -->
<div class="admin-mobile-overlay" id="admin-sidebar-overlay" aria-hidden="true"></div>

<!-- ══ ADMIN SIDEBAR NAVIGATION ══ -->
<aside class="admin-sidebar" id="admin-sidebar" role="navigation" aria-label="Admin Navigation">
    <!-- Brand Logo -->
    <div class="admin-sidebar-header">
        <a href="<?= url('/admin') ?>" class="admin-logo-link" aria-label="InternConnect Admin Home">
            <img src="<?= asset('assets/images/logos/Logo_dark_dx00tg.svg') ?>" onerror="this.onerror=null; this.src='<?= url('assets/images/logos/Logo_dark_dx00tg.svg') ?>';" alt="InternConnect" class="admin-logo-img">
        </a>
        <button type="button" id="admin-sidebar-close" class="admin-sidebar-close" aria-label="Close navigation">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>

    <!-- Scrollable Navigation Menu -->
    <nav class="admin-sidebar-scroll" x-data="{
        openInterns: <?= $isInterns ? 'true' : 'false' ?>,
        openEmployers: <?= $isEmployers ? 'true' : 'false' ?>,
        openAi: <?= $isAi ? 'true' : 'false' ?>
    }">
        <!-- Overview -->
        <div class="admin-nav-group">
            <a href="<?= url('/admin') ?>" class="admin-nav-link <?= $activeNav === 'overview' ? 'is-active' : '' ?>">
                <span class="admin-nav-left">
                    <svg class="admin-nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                    </svg>
                    <span class="admin-nav-text">Overview</span>
                </span>
            </a>
        </div>

        <!-- People -->
        <div class="admin-nav-group">
            <div class="admin-nav-section-label">People</div>

            <div class="admin-nav-item">
                <div class="admin-nav-link <?= $isInterns ? 'is-active' : '' ?>">
                    <a href="<?= url('/admin/interns') ?>" class="admin-nav-left admin-nav-anchor">
                        <svg class="admin-nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                        <span class="admin-nav-text">Interns</span>
                    </a>
                    <span class="admin-nav-count">37</span>
                    <button type="button" class="admin-nav-toggle" :class="{ 'is-open': openInterns }" :aria-expanded="openInterns" @click.prevent.stop="openInterns = !openInterns" aria-label="Toggle Interns submenu"><?= $chevron ?></button>
                </div>
                <div class="admin-submenu" x-show="openInterns" x-transition.opacity.duration.150ms style="display: none;">
                    <a href="<?= url('/admin/interns') ?>" class="admin-subnav-link <?= ($activeNav === 'interns' && $currentTab === '') ? 'is-active' : '' ?>"><span>All Interns</span></a>
                    <a href="<?= url('/admin/interns?tab=active') ?>" class="admin-subnav-link <?= ($activeNav === 'interns' && $currentTab === 'active') ? 'is-active' : '' ?>"><span>Active Candidates</span></a>
                    <a href="<?= url('/admin/interns?tab=placed') ?>" class="admin-subnav-link <?= ($activeNav === 'interns' && $currentTab === 'placed') ? 'is-active' : '' ?>"><span>Placed Interns</span></a>
                    <a href="<?= url('/admin/interns?tab=pending') ?>" class="admin-subnav-link <?= ($activeNav === 'interns' && $currentTab === 'pending') ? 'is-active' : '' ?>">
                        <span>Awaiting Review</span>
                        <span class="admin-subnav-count">37</span>
                    </a>
                </div>
            </div>

            <div class="admin-nav-item">
                <div class="admin-nav-link <?= $isEmployers ? 'is-active' : '' ?>">
                    <a href="<?= url('/admin/employers') ?>" class="admin-nav-left admin-nav-anchor">
                        <svg class="admin-nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                        <span class="admin-nav-text">Employers</span>
                    </a>
                    <span class="admin-nav-count is-amber">14</span>
                    <button type="button" class="admin-nav-toggle" :class="{ 'is-open': openEmployers }" :aria-expanded="openEmployers" @click.prevent.stop="openEmployers = !openEmployers" aria-label="Toggle Employers submenu"><?= $chevron ?></button>
                </div>
                <div class="admin-submenu" x-show="openEmployers" x-transition.opacity.duration.150ms style="display: none;">
                    <a href="<?= url('/admin/employers') ?>" class="admin-subnav-link <?= ($activeNav === 'employers' && $currentTab === '') ? 'is-active' : '' ?>"><span>All Employers</span></a>
                    <a href="<?= url('/admin/employers?tab=pending') ?>" class="admin-subnav-link <?= ($activeNav === 'employers' && $currentTab === 'pending') ? 'is-active' : '' ?>">
                        <span>Verification Queue</span>
                        <span class="admin-subnav-count is-amber">14</span>
                    </a>
                    <a href="<?= url('/admin/employers?tab=verified') ?>" class="admin-subnav-link <?= ($activeNav === 'employers' && $currentTab === 'verified') ? 'is-active' : '' ?>"><span>Verified Employers</span></a>
                </div>
            </div>
        </div>

        <!-- Platform -->
        <div class="admin-nav-group">
            <div class="admin-nav-section-label">Platform</div>

            <div class="admin-nav-item">
                <div class="admin-nav-link <?= $isAi ? 'is-active' : '' ?>">
                    <a href="<?= url('/admin/ai') ?>" class="admin-nav-left admin-nav-anchor">
                        <svg class="admin-nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                        </svg>
                        <span class="admin-nav-text">Jenny AI</span>
                    </a>
                    <span class="admin-nav-count">6</span>
                    <button type="button" class="admin-nav-toggle" :class="{ 'is-open': openAi }" :aria-expanded="openAi" @click.prevent.stop="openAi = !openAi" aria-label="Toggle Jenny AI submenu"><?= $chevron ?></button>
                </div>
                <div class="admin-submenu" x-show="openAi" x-transition.opacity.duration.150ms style="display: none;">
                    <a href="<?= url('/admin/ai') ?>" class="admin-subnav-link <?= ($activeNav === 'ai' && !$onAiChat) ? 'is-active' : '' ?>"><span>Operations Console</span></a>
                    <a href="<?= url('/admin/ai/chat') ?>" class="admin-subnav-link <?= $onAiChat ? 'is-active' : '' ?>">
                        <span>Gemini Chat</span>
                        <img src="<?= asset('assets/images/jenny_ai/jenny_icon.png') ?>" onerror="this.onerror=null; this.src='<?= url('assets/images/jenny_ai/jenny_icon.png') ?>';" alt="" class="admin-subnav-avatar">
                    </a>
                </div>
            </div>

            <a href="<?= url('/admin/elearning') ?>" class="admin-nav-link <?= $activeNav === 'elearning' ? 'is-active' : '' ?>">
                <span class="admin-nav-left">
                    <svg class="admin-nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"/>
                    </svg>
                    <span class="admin-nav-text">E-Learning</span>
                </span>
            </a>

            <a href="<?= url('/admin/interviews') ?>" class="admin-nav-link <?= $activeNav === 'interviews' ? 'is-active' : '' ?>">
                <span class="admin-nav-left">
                    <svg class="admin-nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                    </svg>
                    <span class="admin-nav-text">Video Interviews</span>
                </span>
            </a>

            <a href="<?= url('/admin/subscriptions') ?>" class="admin-nav-link <?= $activeNav === 'subscriptions' ? 'is-active' : '' ?>">
                <span class="admin-nav-left">
                    <svg class="admin-nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                    </svg>
                    <span class="admin-nav-text">Subscriptions</span>
                </span>
            </a>
        </div>

        <!-- Administration -->
        <div class="admin-nav-group">
            <div class="admin-nav-section-label">Administration</div>

            <a href="<?= url('/admin/settings/team') ?>" class="admin-nav-link <?= in_array($activeNav, ['settings', 'team'], true) ? 'is-active' : '' ?>">
                <span class="admin-nav-left">
                    <svg class="admin-nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <span class="admin-nav-text">Settings &amp; Team</span>
                </span>
            </a>
        </div>
    </nav>

    <!-- Pinned Bottom Staff Profile Card -->
    <div class="admin-sidebar-footer">
        <div class="admin-profile-card">
            <div class="admin-profile-left">
                <div class="admin-avatar-circle"><?= htmlspecialchars($staffInitials) ?></div>
                <div class="admin-profile-info">
                    <span class="admin-profile-name"><?= htmlspecialchars($staffName) ?></span>
                    <span class="admin-profile-role"><?= htmlspecialchars($staffRole) ?></span>
                </div>
            </div>
            <a href="<?= url('/admin/logout') ?>" class="admin-logout-btn" aria-label="Sign out">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
            </a>
        </div>
    </div>
</aside>

<!-- Global Mobile Drawer Handler (Centralized Delegation Only) -->
<script>
(function() {
    if (window._adminSidebarBound) return;
    window._adminSidebarBound = true;

    document.addEventListener('click', function(e) {
        var hamburger = e.target.closest('#admin-hamburger');
        var dismiss = e.target.closest('#admin-sidebar-overlay') || e.target.closest('#admin-sidebar-close');
        var sidebar = document.getElementById('admin-sidebar');
        var overlayEl = document.getElementById('admin-sidebar-overlay');

        if (hamburger && sidebar) {
            e.preventDefault();
            var isOpen = sidebar.classList.contains('is-open');
            sidebar.classList.toggle('is-open', !isOpen);
            if (overlayEl) overlayEl.classList.toggle('is-open', !isOpen);
        } else if (dismiss && sidebar) {
            sidebar.classList.remove('is-open');
            if (overlayEl) overlayEl.classList.remove('is-open');
        }
    });
})();
</script>
