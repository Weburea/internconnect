<?php
declare(strict_types=1);
/**
 * Shared Employer Dashboard Sidebar Component
 * Compact profile, grouped navigation that scrolls on its own, and a pinned footer
 * holding Logout and a closable promo card.
 *
 * @var string $activeNav     Active navigation identifier
 * @var array  $employer      Employer info (company_name, role, avatar)
 */

$companyName = $employer['company_name'] ?? ($_SESSION['company_name'] ?? 'Global Tech Solutions');
$compRole    = $employer['role'] ?? ($_SESSION['company_role'] ?? 'Employer Portal');
$compAvatar  = $employer['avatar'] ?? ($_SESSION['company_avatar'] ?? null);
$activeNav   = $activeNav ?? 'dashboard';

$isActive = static fn (array $keys): bool => in_array($activeNav, $keys, true);
?>

<!-- Sidebar Overlay (mobile) -->
<div class="db-sidebar-overlay" id="db-sidebar-overlay"></div>

<!-- ══ EMPLOYER SIDEBAR ══ -->
<aside class="db-sidebar db-sidebar-v2" role="complementary" aria-label="Employer sidebar navigation">
    <!-- Brand Logo -->
    <div class="db-sidebar-logo">
        <a href="<?= url('/') ?>" class="db-logo-link">
            <img src="<?= asset('assets/images/logos/Logo_dark_dx00tg.svg') ?>" alt="InternConnect" class="db-sidebar-logo-img">
        </a>
    </div>

    <!-- Employer Company Profile -->
    <div class="db-profile db-profile-compact">
        <div class="db-avatar-wrap">
            <?php if ($compAvatar): ?>
                <img class="db-avatar" src="<?= htmlspecialchars($compAvatar) ?>" alt="<?= htmlspecialchars($companyName) ?>">
            <?php else: ?>
                <div class="db-avatar-initials" style="background: linear-gradient(135deg, #0052CC, #4F46E5);" aria-hidden="true">
                    <?= strtoupper(substr($companyName, 0, 1)) . strtoupper(substr(explode(' ', $companyName)[1] ?? '', 0, 1)) ?>
                </div>
            <?php endif; ?>
        </div>
        <div class="db-profile-text">
            <div class="db-profile-name"><?= htmlspecialchars($companyName) ?></div>
            <div class="db-profile-role"><?= htmlspecialchars($compRole) ?></div>
        </div>
    </div>

    <!-- Navigation Menu (scrolls independently) -->
    <nav class="db-nav db-sidebar-scroll" aria-label="Employer navigation">
        <a href="<?= url('/employer/dashboard') ?>" class="db-nav-link <?= $activeNav === 'dashboard' ? 'is-active' : '' ?>" <?= $activeNav === 'dashboard' ? 'aria-current="page"' : '' ?>>
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            <span>Dashboard</span>
        </a>

        <div class="db-nav-heading">Hiring</div>

        <a href="<?= url('/employer/internships/create') ?>" class="db-nav-link <?= $isActive(['post', 'create_internship', 'post_internship']) ? 'is-active' : '' ?>">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span>Post Internship</span>
        </a>

        <a href="<?= url('/employer/internships') ?>" class="db-nav-link <?= $isActive(['internships', 'my_internships', 'posted_internships']) ? 'is-active' : '' ?>">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
            </svg>
            <span>Posted Internships</span>
        </a>

        <a href="<?= url('/employer/candidates') ?>" class="db-nav-link <?= $isActive(['candidates', 'applications']) ? 'is-active' : '' ?>">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
            </svg>
            <span>Candidates</span>
        </a>

        <a href="<?= url('/employer/interviews') ?>" class="db-nav-link <?= $isActive(['interviews', 'schedule_interview']) ? 'is-active' : '' ?>">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <span>Interviews</span>
        </a>

        <div class="db-nav-heading">Company</div>

        <a href="<?= url('/employer/company') ?>" class="db-nav-link <?= $isActive(['company', 'companies', 'company_overview']) ? 'is-active' : '' ?>">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
            </svg>
            <span>Company Overview</span>
        </a>

        <a href="<?= url('/employer/analytics') ?>" class="db-nav-link <?= $isActive(['analytics', 'reports', 'hiring_analytics']) ? 'is-active' : '' ?>" <?= $isActive(['analytics', 'reports', 'hiring_analytics']) ? 'aria-current="page"' : '' ?>>
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
            </svg>
            <span>Analytics &amp; Reports</span>
        </a>

        <div class="db-nav-heading">Communication</div>

        <a href="<?= url('/employer/messages') ?>" class="db-nav-link <?= $activeNav === 'messages' ? 'is-active' : '' ?>">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
            </svg>
            <span class="db-nav-label">Messages</span>
            <span class="db-nav-count">3</span>
        </a>

        <a href="<?= url('/employer/ai-assistant') ?>" class="db-nav-link <?= $isActive(['ai_assistant', 'ai-assistant']) ? 'is-active' : '' ?>" <?= $isActive(['ai_assistant', 'ai-assistant']) ? 'aria-current="page"' : '' ?>>
            <svg viewBox="0 0 74 83" fill="currentColor">
                <path d="M73.74 32.4627V59.9427C67.93 59.7673 62.2767 61.8422 57.9596 65.7344C53.6426 69.6267 50.9952 75.0356 50.57 80.8327C50.52 81.3027 50.48 81.8327 50.46 82.2827H23.57C23.57 82.0227 23.48 81.7627 23.43 81.5127C20.73 67.1427 16.41 63.2427 0 59.6527V32.6527C13.34 31.5727 27.12 37.6527 36.92 47.9327C46 38.3527 58.99 31.8927 73.74 32.4627Z"/>
                <path d="M37.0001 0.0031237C40.4833 0.0367014 43.8785 1.10075 46.7578 3.06112C49.637 5.02149 51.8715 7.79043 53.1794 11.0188C54.4873 14.2473 54.8101 17.7906 54.1071 21.2023C53.4042 24.6139 51.7069 27.741 49.2293 30.1894C46.7516 32.6378 43.6046 34.2979 40.1849 34.9604C36.7652 35.6228 33.2259 35.258 30.0132 33.9119C26.8005 32.5658 24.0583 30.2987 22.1322 27.3963C20.2061 24.494 19.1824 21.0864 19.1901 17.6031C19.1737 15.2695 19.6247 12.9562 20.5166 10.7997C21.4084 8.64315 22.7231 6.68703 24.383 5.04669C26.0429 3.40635 28.0145 2.11499 30.1814 1.24877C32.3484 0.382549 34.6669 -0.040991 37.0001 0.0031237Z"/>
            </svg>
            <span>AI Assistant</span>
        </a>

        <a href="<?= url('/employer/notifications') ?>" class="db-nav-link <?= $isActive(['notifications', 'notification']) ? 'is-active' : '' ?>">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
            </svg>
            <span class="db-nav-label">Notifications</span>
            <span class="db-nav-count is-soft">4</span>
        </a>

        <div class="db-nav-heading">Account</div>

        <a href="<?= url('/employer/profile') ?>" class="db-nav-link <?= $isActive(['profile', 'settings', 'profile_settings']) ? 'is-active' : '' ?>" <?= $isActive(['profile', 'settings', 'profile_settings']) ? 'aria-current="page"' : '' ?>>
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <span>Profile Settings</span>
        </a>

        <a href="<?= url('/employer/billing') ?>" class="db-nav-link <?= $isActive(['billings', 'billing', 'subscription']) ? 'is-active' : '' ?>" <?= $isActive(['billings', 'billing', 'subscription']) ? 'aria-current="page"' : '' ?>>
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
            </svg>
            <span>Billings & Subscription</span>
        </a>
    </nav>

    <!-- Pinned Footer: Logout and Closable Promo -->
    <div class="db-sidebar-footer">
        <a href="<?= url('/logout') ?>" class="db-nav-link db-nav-link-logout">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
            </svg>
            <span>Logout</span>
        </a>

        <div class="db-promo-dark db-promo-compact" x-data="{
            hidden: false,
            init() {
                try { this.hidden = localStorage.getItem('icPromoClosed_employer') === '1'; } catch (e) {}
            },
            close() {
                this.hidden = true;
                try { localStorage.setItem('icPromoClosed_employer', '1'); } catch (e) {}
            }
        }" x-show="!hidden">
            <button type="button" class="db-promo-close" aria-label="Close promotion" @click="close()">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
            <h4 class="db-promo-dark-title">Build your future</h4>
            <p class="db-promo-dark-desc">Discover top talent, post internships and grow your organization.</p>
            <a href="<?= url('/employer/dashboard') ?>" class="db-promo-dark-btn">Explore Now →</a>
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
