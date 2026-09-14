<?php
declare(strict_types=1);
/**
 * Shared Candidate Dashboard Top Navbar Component
 * Matches latest visual design specs (Image 1)
 * 
 * @var string $activeNav   Active navigation identifier
 * @var array  $candidate   Candidate user info (name, role, avatar)
 */

$candName      = $candidate['name'] ?? ($_SESSION['profile_name'] ?? 'Sarah Peters');
$candFirstName = explode(' ', trim($candName))[0];
$candRole      = $candidate['role'] ?? ($_SESSION['profile_role'] ?? 'Student Applicant');
$candAvatar    = $candidate['avatar'] ?? ($_SESSION['profile_avatar'] ?? asset('assets/images/profile.png'));
$activeNav     = $activeNav ?? '';
?>

<!-- ══ CANDIDATE TOP NAVBAR ══ -->
<header class="db-cand-topnav" role="banner">
    <div class="db-cand-topnav-inner">
        <!-- Left: Mobile Hamburger & Logo -->
        <div class="db-cand-topnav-left">
            <button class="db-hamburger" id="db-hamburger" type="button" aria-label="Open sidebar menu">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
            <a href="<?= url('/') ?>" class="db-cand-nav-brand">
                <img src="<?= asset('assets/images/logos/Logo_dark_dx00tg.svg') ?>" alt="InternConnect" class="db-cand-nav-logo">
            </a>
        </div>

        <!-- Center: Nav Links (Image 1) -->
        <nav class="db-cand-nav-links" aria-label="Candidate top navigation">
            <a href="<?= url('/internships') ?>" class="db-cand-nav-item <?= $activeNav === 'internships' ? 'is-active' : '' ?>">Find Internships</a>
            <a href="<?= url('/#how-it-works') ?>" class="db-cand-nav-item">How it Works</a>
            <a href="<?= url('/pricing') ?>" class="db-cand-nav-item">E-Learning</a>
            <a href="<?= url('/pricing') ?>" class="db-cand-nav-item <?= in_array($activeNav, ['pricing', 'payments'], true) ? 'is-active' : '' ?>">Payments</a>
            <a href="<?= url('/#testimonials') ?>" class="db-cand-nav-item">Success Stories</a>
        </nav>

        <!-- Right: Search + Notifications + Profile (Image 1) -->
        <div class="db-cand-topnav-right">
            <!-- Search Internships Input -->
            <div class="db-cand-nav-search">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input id="db-cand-search-input" type="search" placeholder="Search Internships" aria-label="Search Internships">
            </div>

            <!-- Notifications Bell Icon with Red Dot -->
            <div class="db-cand-notif-btn" role="button" aria-label="Notifications" id="db-topnav-notif" tabindex="0" x-data="{ open: false }" @click.outside="open = false" @click="open = !open">
                <div class="db-cand-notif-dot" aria-hidden="true"></div>
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
                
                <!-- Notification Dropdown -->
                <div x-show="open" style="display: none; width: 320px;" class="db-dropdown" @click.stop>
                    <div class="db-dropdown-title" style="margin-bottom: 12px; display: flex; justify-content: space-between; align-items: center;">
                        <span>Notifications</span>
                        <span style="background: #FEE2E2; color: #EF4444; font-size: 10px; padding: 2px 6px; border-radius: 99px; font-weight: 700;">7 New</span>
                    </div>
                    <div style="display: flex; flex-direction: column; gap: 8px; max-height: 240px; overflow-y: auto;">
                        <div style="display: flex; gap: 10px; padding: 8px; border-radius: 8px; cursor: pointer; transition: background .12s;" onmouseover="this.style.background='#F8FAFC'" onmouseout="this.style.background='transparent'">
                            <div style="width: 28px; height: 28px; border-radius: 50%; background: #EFF6FF; display: flex; align-items: center; justify-content: center; color: #3B82F6; flex-shrink: 0;">
                                <svg style="width: 14px; height: 14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                            <div>
                                <div style="font-size: 12px; font-weight: 600; color: #191C1D;">Slack Interview Scheduled</div>
                                <div style="font-size: 11px; color: #64748B;">Tomorrow at 10:00 AM</div>
                            </div>
                        </div>
                        <div style="display: flex; gap: 10px; padding: 8px; border-radius: 8px; cursor: pointer; transition: background .12s;" onmouseover="this.style.background='#F8FAFC'" onmouseout="this.style.background='transparent'">
                            <div style="width: 28px; height: 28px; border-radius: 50%; background: #DCFCE7; display: flex; align-items: center; justify-content: center; color: #16A34A; flex-shrink: 0;">
                                <svg style="width: 14px; height: 14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <div>
                                <div style="font-size: 12px; font-weight: 600; color: #191C1D;">Uber Application Received</div>
                                <div style="font-size: 11px; color: #64748B;">2 hours ago</div>
                            </div>
                        </div>
                        <div style="display: flex; gap: 10px; padding: 8px; border-radius: 8px; cursor: pointer; transition: background .12s;" onmouseover="this.style.background='#F8FAFC'" onmouseout="this.style.background='transparent'">
                            <div style="width: 28px; height: 28px; border-radius: 50%; background: #F5F3FF; display: flex; align-items: center; justify-content: center; color: #7C3AED; flex-shrink: 0;">
                                <svg style="width: 14px; height: 14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                            </div>
                            <div>
                                <div style="font-size: 12px; font-weight: 600; color: #191C1D;">New Message from Figma</div>
                                <div style="font-size: 11px; color: #64748B;">4 hours ago</div>
                            </div>
                        </div>
                    </div>
                    <a href="<?= url('/help') ?>" style="display: block; text-align: center; font-size: 12px; color: #4F47E6; font-weight: 600; margin-top: 12px; text-decoration: none;" onmouseover="this.style.textDecoration='underline'" onmouseout="this.style.textDecoration='none'">View All Notifications</a>
                </div>
            </div>

            <!-- Profile Avatar with Dropdown -->
            <div class="db-cand-nav-profile" x-data="{ open: false }" @click.outside="open = false">
                <div class="db-cand-nav-avatar" role="button" aria-label="Candidate account options" tabindex="0" @click="open = !open">
                    <?php if ($candAvatar): ?>
                        <img src="<?= htmlspecialchars($candAvatar) ?>" alt="<?= htmlspecialchars($candName) ?>">
                    <?php else: ?>
                        <div class="db-avatar-initials-sm"><?= strtoupper(substr($candFirstName, 0, 1)) ?></div>
                    <?php endif; ?>
                </div>

                <!-- Account Settings Dropdown -->
                <div x-show="open" style="display: none; width: 220px;" class="db-dropdown" @click.stop>
                    <div class="db-dropdown-title"><?= htmlspecialchars($candName) ?></div>
                    <div style="font-size: 11px; color: #94A3B8; padding: 0 10px 8px; margin-top: -4px; border-bottom: 1px solid #F1F5F9;"><?= htmlspecialchars($candRole) ?></div>
                    <div style="display: flex; flex-direction: column; gap: 4px; padding-top: 6px;">
                        <a href="<?= url('/candidate/dashboard') ?>" style="display: flex; align-items: center; gap: 8px; padding: 8px 10px; border-radius: 6px; cursor: pointer; font-size: 13px; color: #475569; text-decoration: none;" onmouseover="this.style.background='#F1F5F9'; this.style.color='#1E293B'" onmouseout="this.style.background='transparent'; this.style.color='#475569'">
                            <svg style="width: 14px; height: 14px; color: #64748B;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                            <span>Dashboard</span>
                        </a>
                        <a href="<?= url('/candidate/profile') ?>" style="display: flex; align-items: center; gap: 8px; padding: 8px 10px; border-radius: 6px; cursor: pointer; font-size: 13px; color: #475569; text-decoration: none;" onmouseover="this.style.background='#F1F5F9'; this.style.color='#1E293B'" onmouseout="this.style.background='transparent'; this.style.color='#475569'">
                            <svg style="width: 14px; height: 14px; color: #64748B;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zm-4 7a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            <span>Profile Details</span>
                        </a>
                        <a href="<?= url('/pricing') ?>" style="display: flex; align-items: center; gap: 8px; padding: 8px 10px; border-radius: 6px; cursor: pointer; font-size: 13px; color: #475569; text-decoration: none;" onmouseover="this.style.background='#F1F5F9'; this.style.color='#1E293B'" onmouseout="this.style.background='transparent'; this.style.color='#475569'">
                            <svg style="width: 14px; height: 14px; color: #64748B;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                            <span>Billings & Subscription</span>
                        </a>
                        <div style="border-top: 1px solid #F1F5F9; margin: 4px 0;"></div>
                        <a href="<?= url('/logout') ?>" style="display: flex; align-items: center; gap: 8px; padding: 8px 10px; border-radius: 6px; cursor: pointer; font-size: 13px; color: #DC2626; text-decoration: none;" onmouseover="this.style.background='#FEE2E2'" onmouseout="this.style.background='transparent'">
                            <svg style="width: 14px; height: 14px; color: #DC2626;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                            <span>Log Out</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
