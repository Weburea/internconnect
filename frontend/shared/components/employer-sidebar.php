<?php
declare(strict_types=1);
/**
 * Shared Employer Dashboard Sidebar Component
 * Matches design system consistency with candidate sidebar
 * 
 * @var string $activeNav     Active navigation identifier
 * @var array  $employer      Employer info (company_name, role, avatar)
 */

$companyName = $employer['company_name'] ?? ($_SESSION['company_name'] ?? 'Global Tech Solutions');
$compRole    = $employer['role'] ?? ($_SESSION['company_role'] ?? 'Employer Portal');
$compAvatar  = $employer['avatar'] ?? ($_SESSION['company_avatar'] ?? null);
$activeNav   = $activeNav ?? 'dashboard';
?>

<!-- Sidebar Overlay (mobile) -->
<div class="db-sidebar-overlay" id="db-sidebar-overlay"></div>

<!-- ══ EMPLOYER SIDEBAR ══ -->
<aside class="db-sidebar" role="complementary" aria-label="Employer sidebar navigation">
    <!-- Brand Logo -->
    <div class="db-sidebar-logo">
        <a href="<?= url('/') ?>" class="db-logo-link">
            <img src="<?= asset('assets/images/logos/Logo_dark_dx00tg.svg') ?>" alt="InternConnect" class="db-sidebar-logo-img">
        </a>
    </div>

    <!-- Employer Company Profile Section -->
    <div class="db-profile">
        <div class="db-avatar-wrap" x-data="{ open: false }">
            <?php if ($compAvatar): ?>
                <img class="db-avatar" src="<?= htmlspecialchars($compAvatar) ?>" alt="<?= htmlspecialchars($companyName) ?>">
            <?php else: ?>
                <div class="db-avatar-initials" style="background: linear-gradient(135deg, #0052CC, #4F46E5);" aria-hidden="true">
                    <?= strtoupper(substr($companyName, 0, 1)) . strtoupper(substr(explode(' ', $companyName)[1] ?? '', 0, 1)) ?>
                </div>
            <?php endif; ?>
            <div class="db-avatar-edit" role="button" aria-label="Update company logo" @click="open = !open" title="Update company logo">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                </svg>
            </div>
            <!-- Upload Dropdown -->
            <div x-show="open" @click.outside="open = false" style="display: none; position: absolute; top: calc(100% + 6px); left: 50%; transform: translateX(-50%); width: max-content; min-width: 160px; white-space: nowrap; z-index: 100;" class="db-dropdown">
                <div style="font-size: 13px; color: #191C1D; padding: 9px 16px; cursor: pointer; border-bottom: 1px solid #F0F2F7;" onmouseover="this.style.background='#F1F5F9'" onmouseout="this.style.background='transparent'">Upload Logo</div>
                <div style="font-size: 13px; color: #DC2626; padding: 9px 16px; cursor: pointer;" onmouseover="this.style.background='#FEE2E2'" onmouseout="this.style.background='transparent'">Remove Logo</div>
            </div>
        </div>
        <div class="db-profile-name"><?= htmlspecialchars($companyName) ?></div>
        <div class="db-profile-role"><?= htmlspecialchars($compRole) ?></div>
    </div>

    <!-- Navigation Menu -->
    <nav class="db-nav" aria-label="Employer navigation">
        <!-- 1. Dashboard -->
        <a href="<?= url('/employer/dashboard') ?>" class="db-nav-link <?= $activeNav === 'dashboard' ? 'is-active' : '' ?>" <?= $activeNav === 'dashboard' ? 'aria-current="page"' : '' ?>>
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            <span>Dashboard</span>
        </a>

        <!-- 2. Post Internship -->
        <a href="<?= url('/employer/dashboard') ?>" class="db-nav-link <?= $activeNav === 'post' ? 'is-active' : '' ?>">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span>Post Internship</span>
        </a>

        <!-- 3. My Internships -->
        <a href="<?= url('/employer/dashboard') ?>" class="db-nav-link <?= $activeNav === 'internships' ? 'is-active' : '' ?>">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
            </svg>
            <span>My Internships</span>
        </a>

        <!-- 4. Candidates -->
        <a href="<?= url('/employer/dashboard') ?>" class="db-nav-link <?= $activeNav === 'candidates' ? 'is-active' : '' ?>">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
            </svg>
            <span>Candidates</span>
        </a>

        <!-- 5. Companies (Replaces Analytics) -->
        <a href="<?= url('/employer/company') ?>" class="db-nav-link <?= $activeNav === 'companies' ? 'is-active' : '' ?>">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
            </svg>
            <span>Companies</span>
        </a>

        <!-- 6. Interviews -->
        <a href="<?= url('/employer/dashboard') ?>" class="db-nav-link <?= $activeNav === 'interviews' ? 'is-active' : '' ?>">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <span>Interviews</span>
        </a>

        <!-- 7. Messages -->
        <a href="<?= url('/employer/dashboard') ?>" class="db-nav-link <?= $activeNav === 'messages' ? 'is-active' : '' ?>" style="display: flex; align-items: center; justify-content: space-between;">
            <div style="display: flex; align-items: center; gap: 12px;">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                </svg>
                <span>Messages</span>
            </div>
            <span style="background: #4F47E6; color: #fff; font-size: 10px; font-weight: 800; padding: 2px 7px; border-radius: 99px;">3</span>
        </a>

        <!-- 8. Saved -->
        <a href="<?= url('/employer/dashboard') ?>" class="db-nav-link <?= $activeNav === 'saved' ? 'is-active' : '' ?>">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/>
            </svg>
            <span>Saved</span>
        </a>

        <!-- 9. Resources -->
        <a href="<?= url('/employer/dashboard') ?>" class="db-nav-link <?= $activeNav === 'resources' ? 'is-active' : '' ?>">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
            </svg>
            <span>Resources</span>
        </a>

        <!-- Quick Access Sub-Header -->
        <div style="font-size: 11px; font-weight: 700; color: #94A3B8; text-transform: uppercase; letter-spacing: 0.6px; padding: 18px 16px 8px; margin-top: 4px;">
            Quick Access
        </div>

        <a href="<?= url('/employer/dashboard') ?>" class="db-nav-link" style="font-size: 13px; color: #64748B;">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.9 1.519-.9 1.818 0l1.518 4.674a1 1 0 00.95.69h4.907c.961 0 1.357 1.236.588 1.81l-3.97 2.887a1 1 0 00-.364 1.118l1.518 4.674c.3.9-.755 1.688-1.538 1.118l-3.97-2.887a1 1 0 00-1.176 0l-3.97 2.887c-.783.57-1.838-.218-1.538-1.118l1.518-4.674a1 1 0 00-.364-1.118l-3.97-2.887c-.768-.574-.372-1.81.588-1.81h4.907a1 1 0 00.95-.69l1.518-4.674z"/>
            </svg>
            <span>Recommended for you</span>
        </a>

        <a href="<?= url('/employer/company') ?>" class="db-nav-link" style="font-size: 13px; color: #64748B;">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/>
            </svg>
            <span>Saved companies</span>
        </a>

        <a href="<?= url('/employer/dashboard') ?>" class="db-nav-link" style="font-size: 13px; color: #64748B;">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <span>Upcoming interviews</span>
        </a>

        <!-- Logout -->
        <a href="<?= url('/logout') ?>" class="db-nav-link db-nav-link-logout" style="margin-top: 12px;">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
            </svg>
            <span>Logout</span>
        </a>
    </nav>

    <!-- Bottom Upgrade / Promo Card -->
    <div class="db-promo-dark" style="background: linear-gradient(155deg, #0A1E3F 0%, #0F2D6B 100%);">
        <h4 class="db-promo-dark-title" style="color: #60A5FA;">Build your future</h4>
        <p class="db-promo-dark-desc">Discover top companies, apply to internships and grow your career.</p>
        <a href="<?= url('/internships') ?>" class="db-promo-dark-btn" style="background: #2563EB;">Explore Now →</a>
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
