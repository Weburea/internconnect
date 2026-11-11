<?php
declare(strict_types=1);
/**
 * Shared Candidate Dashboard Sidebar Component
 * Compact profile, grouped navigation that scrolls on its own, and a pinned footer
 * holding Logout and a closable plan card.
 *
 * @var string $activeNav   Active navigation identifier
 * @var array  $candidate   Candidate user info (name, role, avatar, plan)
 */

if (isset($_GET['plan'])) {
    $requestedPlan = strtolower(trim((string)$_GET['plan']));
    if (in_array($requestedPlan, ['free', 'pro', 'premium'], true)) {
        $_SESSION['candidate_plan'] = $requestedPlan;
    }
}

$candName      = $candidate['name'] ?? ($_SESSION['profile_name'] ?? 'Sarah Peters');
$candFirstName = explode(' ', trim($candName))[0];
$candRole      = $candidate['role'] ?? ($_SESSION['profile_role'] ?? 'Student Applicant');
$candAvatar    = $candidate['avatar'] ?? ($_SESSION['profile_avatar'] ?? asset('assets/images/profile.png'));
$candPlan      = $_SESSION['candidate_plan'] ?? ($candidate['plan'] ?? 'pro');
if (!in_array($candPlan, ['free', 'pro', 'premium'], true)) {
    $candPlan = 'pro';
}
$activeNav     = $activeNav ?? 'dashboard';

$isActive = static fn (array $keys): bool => in_array($activeNav, $keys, true);
$promoKey = 'icPromoClosed_candidate_' . $candPlan;
?>

<!-- Sidebar Overlay (mobile) -->
<div class="db-sidebar-overlay" id="db-sidebar-overlay"></div>

<!-- ══ CANDIDATE SIDEBAR ══ -->
<aside class="db-sidebar db-sidebar-v2" role="complementary" aria-label="Candidate sidebar navigation">
    <!-- Brand Logo -->
    <div class="db-sidebar-logo">
        <a href="<?= url('/') ?>" class="db-logo-link">
            <img src="<?= asset('assets/images/logos/Logo_dark_dx00tg.svg') ?>" alt="InternConnect" class="db-sidebar-logo-img">
        </a>
    </div>

    <!-- Candidate Profile -->
    <div class="db-profile db-profile-compact">
        <div class="db-avatar-wrap">
            <?php if ($candAvatar): ?>
                <img class="db-avatar" src="<?= htmlspecialchars($candAvatar) ?>" alt="<?= htmlspecialchars($candName) ?>">
            <?php else: ?>
                <div class="db-avatar-initials" aria-hidden="true" style="background: #EEF2FF; color: #4F48E6;"><?= strtoupper(substr($candFirstName, 0, 1)) . strtoupper(substr(explode(' ', $candName)[1] ?? '', 0, 1)) ?></div>
            <?php endif; ?>

            <a href="<?= url('/candidate/profile') ?>" class="db-avatar-edit-btn" aria-label="Edit Profile">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
            </a>
        </div>
        <div class="db-profile-text">
            <div class="db-profile-name-row">
                <div class="db-profile-name"><?= htmlspecialchars($candName) ?></div>
                <?php if ($candPlan === 'pro'): ?>
                    <span class="db-plan-badge-avatar is-pro">PRO</span>
                <?php elseif ($candPlan === 'premium'): ?>
                    <span class="db-plan-badge-avatar is-premium">PREMIUM</span>
                <?php endif; ?>
            </div>
            <div class="db-profile-role"><?= htmlspecialchars($candRole) ?></div>
        </div>
    </div>

    <!-- Navigation Menu (scrolls independently) -->
    <nav class="db-nav db-sidebar-scroll" aria-label="Sidebar navigation">
        <a href="<?= url('/candidate/dashboard') ?>" class="db-nav-link <?= $activeNav === 'dashboard' ? 'is-active' : '' ?>" <?= $activeNav === 'dashboard' ? 'aria-current="page"' : '' ?>>
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
            </svg>
            <span>Dashboard</span>
        </a>

        <div class="db-nav-heading">Job Search</div>

        <a href="<?= url('/internships') ?>" class="db-nav-link <?= $activeNav === 'internships' ? 'is-active' : '' ?>" <?= $activeNav === 'internships' ? 'aria-current="page"' : '' ?>>
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <span>Browse Opportunities</span>
        </a>

        <a href="<?= url('/candidate/saved') ?>" class="db-nav-link <?= $activeNav === 'saved' ? 'is-active' : '' ?>" <?= $activeNav === 'saved' ? 'aria-current="page"' : '' ?>>
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/>
            </svg>
            <span>Saved Internships</span>
        </a>

        <a href="<?= url('/candidate/applications') ?>" class="db-nav-link <?= $isActive(['applications', 'application', 'my-application', 'my-applications']) ? 'is-active' : '' ?>" <?= $isActive(['applications', 'application', 'my-application', 'my-applications']) ? 'aria-current="page"' : '' ?>>
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <span>My Applications</span>
        </a>

        <?php if ($candPlan !== 'free'): ?>
        <div class="db-nav-heading">Career Tools</div>

        <a href="<?= url('/candidate/ai-assistant') ?>" class="db-nav-link <?= $isActive(['ai-assistant', 'ai-career-assistant', 'cv-analyze', 'cv-results']) ? 'is-active' : '' ?>">
            <svg viewBox="0 0 74 83" fill="currentColor">
                <path d="M73.74 32.4627V59.9427C67.93 59.7673 62.2767 61.8422 57.9596 65.7344C53.6426 69.6267 50.9952 75.0356 50.57 80.8327C50.52 81.3027 50.48 81.8327 50.46 82.2827H23.57C23.57 82.0227 23.48 81.7627 23.43 81.5127C20.73 67.1427 16.41 63.2427 0 59.6527V32.6527C13.34 31.5727 27.12 37.6527 36.92 47.9327C46 38.3527 58.99 31.8927 73.74 32.4627Z"/>
                <path d="M37.0001 0.0031237C40.4833 0.0367014 43.8785 1.10075 46.7578 3.06112C49.637 5.02149 51.8715 7.79043 53.1794 11.0188C54.4873 14.2473 54.8101 17.7906 54.1071 21.2023C53.4042 24.6139 51.7069 27.741 49.2293 30.1894C46.7516 32.6378 43.6046 34.2979 40.1849 34.9604C36.7652 35.6228 33.2259 35.258 30.0132 33.9119C26.8005 32.5658 24.0583 30.2987 22.1322 27.3963C20.2061 24.494 19.1824 21.0864 19.1901 17.6031C19.1737 15.2695 19.6247 12.9562 20.5166 10.7997C21.4084 8.64315 22.7231 6.68703 24.383 5.04669C26.0429 3.40635 28.0145 2.11499 30.1814 1.24877C32.3484 0.382549 34.6669 -0.040991 37.0001 0.0031237Z"/>
            </svg>
            <span class="db-nav-label">AI Assistant</span>
            <?php if ($candPlan === 'premium'): ?>
                <span class="db-nav-tag is-premium">PREMIUM</span>
            <?php else: ?>
                <span class="db-nav-tag">PRO</span>
            <?php endif; ?>
        </a>
        <?php endif; ?>

        <div class="db-nav-heading">Communication</div>

        <a href="<?= url('/candidate/messages') ?>" class="db-nav-link <?= $isActive(['messages', 'message']) ? 'is-active' : '' ?>">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
            </svg>
            <span class="db-nav-label">Messages</span>
            <span class="db-nav-count">2</span>
        </a>

        <a href="<?= url('/candidate/notifications') ?>" class="db-nav-link <?= $isActive(['notifications', 'notification']) ? 'is-active' : '' ?>" <?= $isActive(['notifications', 'notification']) ? 'aria-current="page"' : '' ?>>
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
            </svg>
            <span>Notifications</span>
        </a>

        <div class="db-nav-heading">Account</div>

        <a href="<?= url('/candidate/profile') ?>" class="db-nav-link <?= $activeNav === 'profile' ? 'is-active' : '' ?>" <?= $activeNav === 'profile' ? 'aria-current="page"' : '' ?>>
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
            </svg>
            <span>Profile Settings</span>
        </a>

        <a href="<?= url('/pricing') ?>" class="db-nav-link <?= $isActive(['billings', 'billing', 'subscription']) ? 'is-active' : '' ?>" <?= $isActive(['billings', 'billing', 'subscription']) ? 'aria-current="page"' : '' ?>>
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
            </svg>
            <span>Billings & Subscription</span>
        </a>

        <a href="<?= url('/help') ?>" class="db-nav-link <?= $activeNav === 'help' ? 'is-active' : '' ?>" <?= $activeNav === 'help' ? 'aria-current="page"' : '' ?>>
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span>Help Center</span>
        </a>
    </nav>

    <!-- Pinned Footer: Logout and Closable Plan Card -->
    <div class="db-sidebar-footer">
        <a href="<?= url('/logout') ?>" class="db-nav-link db-nav-link-logout">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
            </svg>
            <span>Logout</span>
        </a>

        <div class="db-promo-dark db-promo-compact<?= $candPlan === 'premium' ? ' is-premium' : ' is-upgrade' ?>" x-data="{
            hidden: false,
            init() {
                try { this.hidden = localStorage.getItem('<?= $promoKey ?>') === '1'; } catch (e) {}
            },
            close() {
                this.hidden = true;
                try { localStorage.setItem('<?= $promoKey ?>', '1'); } catch (e) {}
            }
        }" x-show="!hidden">
            <button type="button" class="db-promo-close" aria-label="Close promotion" @click="close()">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
            <?php if ($candPlan === 'free'): ?>
                <h4 class="db-promo-dark-title">Upgrade to Pro</h4>
                <p class="db-promo-dark-desc">Unlock AI Career Assistant, smart CV analysis, and priority placement.</p>
                <a href="<?= url('/pricing?plan=pro') ?>" class="db-promo-dark-btn">Upgrade to Pro</a>
            <?php elseif ($candPlan === 'pro'): ?>
                <h4 class="db-promo-dark-title">Find More Opportunities</h4>
                <p class="db-promo-dark-desc">Upgrade to unlock exclusive listings, smarter insights, and increase your chances of getting hired</p>
                <a href="<?= url('/pricing?plan=premium') ?>" class="db-promo-dark-btn">Upgrade to Premium</a>
            <?php else: ?>
                <h4 class="db-promo-dark-title"><span class="db-promo-status-dot"></span>Premium Active</h4>
                <p class="db-promo-dark-desc">You have full access to high-pay global roles, AI CV improvements, and interview prep.</p>
            <?php endif; ?>
        </div>
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
