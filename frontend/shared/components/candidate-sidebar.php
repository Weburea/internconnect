<?php
declare(strict_types=1);
/**
 * Shared Candidate Dashboard Sidebar Component
 * Matches latest visual design specs (Image 2 & Image 3)
 * 
 * @var string $activeNav   Active navigation identifier
 * @var array  $candidate   Candidate user info (name, role, avatar)
 */

$candName      = $candidate['name'] ?? ($_SESSION['profile_name'] ?? 'Sarah Peters');
$candFirstName = explode(' ', trim($candName))[0];
$candRole      = $candidate['role'] ?? ($_SESSION['profile_role'] ?? 'Student Applicant');
$candAvatar    = $candidate['avatar'] ?? ($_SESSION['profile_avatar'] ?? asset('assets/images/profile.png'));
$activeNav     = $activeNav ?? 'dashboard';
?>

<!-- Sidebar Overlay (mobile) -->
<div class="db-sidebar-overlay" id="db-sidebar-overlay"></div>

<!-- ══ CANDIDATE SIDEBAR ══ -->
<aside class="db-sidebar" role="complementary" aria-label="Candidate sidebar navigation">
    <!-- Brand Logo -->
    <div class="db-sidebar-logo">
        <a href="<?= url('/') ?>" class="db-logo-link">
            <img src="<?= asset('assets/images/logos/Logo_dark_dx00tg.svg') ?>" alt="InternConnect" class="db-sidebar-logo-img">
        </a>
    </div>

    <!-- Candidate Profile Section -->
    <div class="db-profile">
        <div class="db-avatar-wrap" x-data="{ open: false }">
            <?php if ($candAvatar): ?>
                <img class="db-avatar" src="<?= htmlspecialchars($candAvatar) ?>" alt="<?= htmlspecialchars($candName) ?>">
            <?php else: ?>
                <div class="db-avatar-initials" aria-hidden="true"><?= strtoupper(substr($candFirstName, 0, 1)) . strtoupper(substr(explode(' ', $candName)[1] ?? '', 0, 1)) ?></div>
            <?php endif; ?>
            <div class="db-avatar-edit" role="button" aria-label="Edit profile photo" @click="open = !open" title="Update photo">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                </svg>
            </div>
            <!-- Upload Dropdown -->
            <div x-show="open" @click.outside="open = false" style="display: none; position: absolute; top: calc(100% + 6px); left: 50%; transform: translateX(-50%); width: max-content; min-width: 160px; white-space: nowrap; z-index: 100;" class="db-dropdown">
                <div style="font-size: 13px; color: #191C1D; padding: 9px 16px; cursor: pointer; border-bottom: 1px solid #F0F2F7;" onmouseover="this.style.background='#F1F5F9'" onmouseout="this.style.background='transparent'">Upload Image</div>
                <div style="font-size: 13px; color: #DC2626; padding: 9px 16px; cursor: pointer;" onmouseover="this.style.background='#FEE2E2'" onmouseout="this.style.background='transparent'">Remove Image</div>
            </div>
        </div>
        <div class="db-profile-name"><?= htmlspecialchars($candName) ?></div>
        <div class="db-profile-role"><?= htmlspecialchars($candRole) ?></div>
    </div>

    <!-- Navigation Menu (Image 2) -->
    <nav class="db-nav" aria-label="Sidebar navigation">
        <!-- 1. Dashboard -->
        <a href="<?= url('/candidate/dashboard') ?>" class="db-nav-link <?= $activeNav === 'dashboard' ? 'is-active' : '' ?>" <?= $activeNav === 'dashboard' ? 'aria-current="page"' : '' ?>>
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
            </svg>
            <span>Dashboard</span>
        </a>

        <!-- 2. Saved Internships -->
        <a href="<?= url('/candidate/saved') ?>" class="db-nav-link <?= $activeNav === 'saved' ? 'is-active' : '' ?>" <?= $activeNav === 'saved' ? 'aria-current="page"' : '' ?>>
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/>
            </svg>
            <span>Saved Internships</span>
        </a>

        <!-- 3. My Application -->
        <a href="<?= url('/candidate/applications') ?>" class="db-nav-link <?= in_array($activeNav, ['applications', 'application', 'my-application', 'my-applications'], true) ? 'is-active' : '' ?>" <?= in_array($activeNav, ['applications', 'application', 'my-application', 'my-applications'], true) ? 'aria-current="page"' : '' ?>>
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <span>My Application</span>
        </a>

        <!-- 4. Browse Opportunities -->
        <a href="<?= url('/internships') ?>" class="db-nav-link <?= $activeNav === 'internships' ? 'is-active' : '' ?>" <?= $activeNav === 'internships' ? 'aria-current="page"' : '' ?>>
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <span>Browse Opportunities</span>
        </a>

        <!-- 5. Profile Settings -->
        <a href="<?= url('/candidate/profile') ?>" class="db-nav-link <?= $activeNav === 'profile' ? 'is-active' : '' ?>" <?= $activeNav === 'profile' ? 'aria-current="page"' : '' ?>>
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
            </svg>
            <span>Profile Settings</span>
        </a>

        <!-- 6. Billings & Subscription -->
        <a href="<?= url('/pricing') ?>" class="db-nav-link <?= in_array($activeNav, ['billings', 'billing', 'subscription'], true) ? 'is-active' : '' ?>" <?= in_array($activeNav, ['billings', 'billing', 'subscription'], true) ? 'aria-current="page"' : '' ?>>
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
            </svg>
            <span>Billings & Subscription</span>
        </a>

        <!-- 7. Help Center -->
        <a href="<?= url('/help') ?>" class="db-nav-link <?= $activeNav === 'help' ? 'is-active' : '' ?>" <?= $activeNav === 'help' ? 'aria-current="page"' : '' ?>>
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span>Help Center</span>
        </a>

        <!-- 8. Logout -->
        <a href="<?= url('/logout') ?>" class="db-nav-link db-nav-link-logout">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
            </svg>
            <span>Logout</span>
        </a>
    </nav>

    <!-- Bottom Upgrade / Promo Card (Image 3) -->
    <div class="db-promo-dark">
        <h4 class="db-promo-dark-title">Go Further</h4>
        <p class="db-promo-dark-desc">Unlock AI interview prep and priority application reviews.</p>
        <a href="<?= url('/payments?plan=pro') ?>" class="db-promo-dark-btn">Upgrade Now</a>
    </div>
</aside>

<!-- Global Mobile Sidebar Toggle Script -->
<script>
(function() {
    if (window._internConnectSidebarInit) return;
    window._internConnectSidebarInit = true;

    document.addEventListener('click', function(e) {
        var hamburger = e.target.closest('#db-hamburger, .db-hamburger');
        var overlay = e.target.closest('#db-sidebar-overlay, .db-sidebar-overlay');
        var sidebar = document.querySelector('.db-sidebar');
        var overlayEl = document.querySelector('.db-sidebar-overlay') || document.getElementById('db-sidebar-overlay');

        if (hamburger && sidebar) {
            e.preventDefault();
            e.stopPropagation();
            sidebar.classList.toggle('is-open');
            if (overlayEl) overlayEl.classList.toggle('is-open');
        } else if (overlay && sidebar) {
            e.preventDefault();
            sidebar.classList.remove('is-open');
            if (overlayEl) overlayEl.classList.remove('is-open');
        }
    });
})();
</script>
