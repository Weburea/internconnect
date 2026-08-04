<?php
declare(strict_types=1);
/**
 * View: Candidate Dashboard
 * Route: /dashboard
 * Layout: dashboard (custom sidebar layout — no global navbar/footer)
 * @var array  $candidate  Candidate profile data
 * @var array  $stats      KPI statistics
 * @var array  $recommendations Recommended internships
 * @var array  $applications   Recent applications
 * @var array  $alerts         Recent alerts/notifications
 */

$name      = $candidate['name'] ?? 'Sarah Peters';
$firstName = explode(' ', trim($name))[0];
$role      = $candidate['role'] ?? 'Student Applicant';
$avatar    = $candidate['avatar'] ?? null;
?>
<div class="db-shell">

    <!-- Sidebar Overlay (mobile) -->
    <div class="db-sidebar-overlay" id="db-sidebar-overlay"></div>

    <!-- ══ SIDEBAR ══ -->
    <aside class="db-sidebar" role="complementary" aria-label="Dashboard sidebar">
        <!-- Logo -->
        <div class="db-sidebar-logo">
            <img src="<?= asset('assets/images/logos/Logo_dark_dx00tg.svg') ?>" alt="InternConnect">
        </div>

        <!-- Profile Block -->
        <div class="db-profile">
            <div class="db-avatar-wrap" x-data="{ open: false }">
                <?php if ($avatar): ?>
                    <img class="db-avatar" src="<?= htmlspecialchars($avatar) ?>" alt="<?= htmlspecialchars($name) ?>">
                <?php else: ?>
                    <div class="db-avatar-initials" aria-hidden="true"><?= strtoupper(substr($firstName, 0, 1)) . strtoupper(substr(explode(' ', $name)[1] ?? '', 0, 1)) ?></div>
                <?php endif; ?>
                <div class="db-avatar-edit" role="button" aria-label="Edit profile photo" @click="open = !open">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                    </svg>
                </div>
                <!-- Upload Dropdown -->
                <div x-show="open" @click.outside="open = false" style="display: none; position: absolute; top: calc(100% + 5px); left: 50%; transform: translateX(-50%); width: max-content; min-width: 160px; white-space: nowrap; z-index: 100;" class="db-dropdown">
                    <div style="font-size: 13px; color: #191C1D; padding: 8px 16px; cursor: pointer; border-bottom: 1px solid #F0F2F7;" onmouseover="this.style.background='#F1F5F9'" onmouseout="this.style.background='transparent'">Upload Image</div>
                    <div style="font-size: 13px; color: #DC2626; padding: 8px 16px; cursor: pointer;" onmouseover="this.style.background='#FEE2E2'" onmouseout="this.style.background='transparent'">Remove Image</div>
                </div>
            </div>
            <div class="db-profile-name"><?= htmlspecialchars($name) ?></div>
            <div class="db-profile-role"><?= htmlspecialchars($role) ?></div>
        </div>

        <!-- Nav -->
        <nav class="db-nav" aria-label="Sidebar navigation">
            <a href="<?= url('/dashboard') ?>" class="db-nav-link is-active" aria-current="page">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                <span>Dashboard</span>
            </a>
            <a href="<?= url('/saved') ?>" class="db-nav-link">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/></svg>
                <span>Saved Internships</span>
            </a>
            <a href="<?= url('/applications') ?>" class="db-nav-link">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                <span>My Applications</span>
            </a>
            <a href="<?= url('/internships') ?>" class="db-nav-link">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <span>Browse Opportunities</span>
            </a>
            <a href="<?= url('/help') ?>" class="db-nav-link">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192L5.636 18.364M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                <span>Help Center</span>
            </a>
            <a href="<?= url('/profile') ?>" class="db-nav-link">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                <span>Profile Settings</span>
            </a>
        </nav>

        <!-- Promo -->
        <div class="db-promo">
            <h4>Find More Opportunities</h4>
            <p>Upgrade to unlock exclusive listings, smarter insights, and increase your chances of getting hired.</p>
            <a href="<?= url('/subscribe') ?>" class="db-promo-btn">Subscribe Now</a>
        </div>
    </aside>

    <!-- ══ MAIN ══ -->
    <div class="db-main">

        <!-- Top Navbar -->
        <header class="db-topnav" role="banner">
            <div class="db-topnav-inner">
                <!-- Mobile Hamburger -->
                <button class="db-hamburger" id="db-hamburger" type="button" aria-label="Open menu">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
                <div class="db-search">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input id="db-search-input" type="search" placeholder="Search internships, companies..." aria-label="Search">
                </div>
                <div class="db-topnav-spacer"></div>
                <!-- Notification -->
                <div class="db-topnav-icon" role="button" aria-label="Notifications" id="db-topnav-notif" tabindex="0" x-data="{ open: false }" @click.outside="open = false" @click="open = !open">
                    <div class="db-notif-dot" aria-hidden="true"></div>
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                    
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
                        <a href="<?= url('/notifications') ?>" style="display: block; text-align: center; font-size: 12px; color: #4F47E6; font-weight: 600; margin-top: 12px; text-decoration: none;" onmouseover="this.style.textDecoration='underline'" onmouseout="this.style.textDecoration='none'">View All Notifications</a>
                    </div>
                </div>
                <!-- Settings -->
                <div class="db-topnav-icon" role="button" aria-label="Settings" id="db-topnav-settings" tabindex="0" x-data="{ open: false }" @click.outside="open = false" @click="open = !open">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    
                    <div x-show="open" style="display: none; width: 200px;" class="db-dropdown" @click.stop>
                        <div class="db-dropdown-title">Account Settings</div>
                        <div style="display: flex; flex-direction: column; gap: 4px;">
                            <a href="<?= url('/profile') ?>" style="display: flex; align-items: center; gap: 8px; padding: 8px 10px; border-radius: 6px; cursor: pointer; font-size: 13px; color: #475569; text-decoration: none;" onmouseover="this.style.background='#F1F5F9'; this.style.color='#1E293B'" onmouseout="this.style.background='transparent'; this.style.color='#475569'">
                                <svg style="width: 14px; height: 14px; color: #64748B;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zm-4 7a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                <span>Profile Details</span>
                            </a>
                            <a href="<?= url('/profile') ?>" style="display: flex; align-items: center; gap: 8px; padding: 8px 10px; border-radius: 6px; cursor: pointer; font-size: 13px; color: #475569; text-decoration: none;" onmouseover="this.style.background='#F1F5F9'; this.style.color='#1E293B'" onmouseout="this.style.background='transparent'; this.style.color='#475569'">
                                <svg style="width: 14px; height: 14px; color: #64748B;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                <span>Security & Privacy</span>
                            </a>
                            <a href="<?= url('/profile') ?>" style="display: flex; align-items: center; gap: 8px; padding: 8px 10px; border-radius: 6px; cursor: pointer; font-size: 13px; color: #475569; text-decoration: none;" onmouseover="this.style.background='#F1F5F9'; this.style.color='#1E293B'" onmouseout="this.style.background='transparent'; this.style.color='#475569'">
                                <svg style="width: 14px; height: 14px; color: #64748B;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                                <span>Notification Settings</span>
                            </a>
                            <a href="<?= url('/profile') ?>" style="display: flex; align-items: center; gap: 8px; padding: 8px 10px; border-radius: 6px; cursor: pointer; font-size: 13px; color: #475569; text-decoration: none;" onmouseover="this.style.background='#F1F5F9'; this.style.color='#1E293B'" onmouseout="this.style.background='transparent'; this.style.color='#475569'">
                                <svg style="width: 14px; height: 14px; color: #64748B;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192L5.636 18.364M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                <span>Help & Support</span>
                            </a>
                        </div>
                    </div>
                </div>
                <!-- Sign Out -->
                <a href="<?= url('/logout') ?>" class="db-signout" id="db-signout-btn" title="Sign Out">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    <span class="db-signout-text">Sign Out</span>
                </a>
            </div>
        </header>

        <!-- Scrollable Content -->
        <div class="db-content" id="db-content">

            <!-- ── Welcome Banner ── -->
            <section class="db-banner" aria-labelledby="db-banner-heading">
                <h1 id="db-banner-heading">Welcome back, <?= htmlspecialchars($firstName) ?></h1>
                <p>Track your internship applications and discover new opportunities designed for your skill set.</p>
            </section>

            <!-- ── KPI Stats ── -->
            <div class="db-stats-row">
                <?php foreach ($stats as $stat): ?>
                <div class="db-stat-card">
                    <!-- Subtle background curve shape -->
                    <div style="position: absolute; top: -10px; right: -10px; width: 80px; height: 80px; background: #F8FAFC; border-radius: 50%; z-index: 1;"></div>
                    
                    <div class="db-stat-top">
                        <div class="db-stat-icon <?= $stat['color'] ?>">
                            <?= $stat['icon'] ?>
                        </div>
                        <span class="db-stat-badge <?= $stat['badge_type'] ?>"><?= $stat['badge'] ?></span>
                    </div>
                    <div class="db-stat-label"><?= $stat['label'] ?></div>
                    <div class="db-stat-val"><?= str_pad((string)$stat['value'], 2, '0', STR_PAD_LEFT) ?></div>
                    
                    <?php if ($stat['color'] === 'blue'): ?>
                        <div style="display: flex; height: 3px; background: #F1F5F9; border-radius: 4px; margin-bottom: 12px; width: 100%;">
                            <div style="width: 60%; background: #4F47E6; border-radius: 4px;"></div>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($stat['desc'])): ?>
                        <div class="db-stat-desc"><?= $stat['desc'] ?></div>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- ── Middle Row ── -->
            <div class="db-mid-row">
                <!-- Application Performance Chart -->
                <div class="db-card" x-data="{ view: 'monthly', charts: {
                    weekly: [
                        { label: 'APPLIED', pct: 45, class: 'blue' },
                        { label: 'REVIEW', pct: 30, class: 'lblue' },
                        { label: 'INTERVIEW', pct: 15, class: 'blue' },
                        { label: 'ACCEPT', pct: 10, class: 'green' },
                        { label: 'REJECT', pct: 5, class: 'red' }
                    ],
                    monthly: [
                        { label: 'APPLIED', pct: 90, class: 'blue' },
                        { label: 'REVIEW', pct: 60, class: 'lblue' },
                        { label: 'INTERVIEW', pct: 40, class: 'blue' },
                        { label: 'ACCEPT', pct: 25, class: 'green' },
                        { label: 'REJECT', pct: 20, class: 'red' }
                    ]
                }}">
                    <div class="db-card-header">
                        <h3 class="db-card-title">Application Performance</h3>
                        <div class="db-toggle-group">
                            <button class="db-toggle-btn" :class="{ 'active': view === 'weekly' }" @click="view = 'weekly'" type="button">Weekly</button>
                            <button class="db-toggle-btn" :class="{ 'active': view === 'monthly' }" @click="view = 'monthly'" type="button">Monthly</button>
                        </div>
                    </div>
                    <div class="db-chart-area" role="img" aria-label="Bar chart showing application stages">
                        <template x-for="bar in charts[view]" :key="bar.label">
                            <div class="db-chart-col">
                                <div class="db-chart-bar-wrap">
                                    <div class="db-chart-bar" :class="bar.class" :style="'height: ' + bar.pct + '%'" :title="bar.label"></div>
                                </div>
                                <span class="db-chart-label" x-text="bar.label"></span>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Complete Profile -->
                <div class="db-card db-profile-card">
                    <div class="db-card-header">
                        <h3 class="db-card-title">Complete Profile</h3>
                    </div>
                    <div class="db-progress-bar-wrap">
                        <div class="db-progress-label">
                            <span>Profile Completion</span>
                            <span class="db-progress-pct"><?= $candidate['profile_pct'] ?? 75 ?>%</span>
                        </div>
                        <div class="db-progress-track">
                            <div class="db-progress-fill" style="width: <?= $candidate['profile_pct'] ?? 75 ?>%;"></div>
                        </div>
                    </div>
                    <div class="db-checklist">
                        <?php foreach (($candidate['checklist'] ?? []) as $item): ?>
                        <div class="db-check-item">
                            <div class="db-check-dot <?= $item['done'] ? 'done' : 'undone' ?>">
                                <?php if ($item['done']): ?>
                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                <?php else: ?>
                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/></svg>
                                <?php endif; ?>
                            </div>
                            <span><?= htmlspecialchars($item['label']) ?></span>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <a href="<?= url('/profile') ?>" class="db-update-btn" id="db-update-profile-btn">Update Profile</a>
                </div>
            </div>

            <!-- ── Recommended For You ── -->
            <section aria-labelledby="db-recs-heading">
                <div class="db-section-header">
                    <h3 class="db-section-title" id="db-recs-heading">Recommended for You</h3>
                    <a href="<?= url('/internships') ?>" class="db-view-all">View All Matches</a>
                </div>
                <div class="db-rec-grid">
                    <?php foreach ($recommendations as $rec): ?>
                    <div class="db-rec-card" id="db-rec-card-<?= htmlspecialchars($rec['slug']) ?>" x-data="{ saved: false }">
                        <button class="db-rec-bookmark" @click="saved = !saved" style="background: none; border: none; padding: 4px; color: #CBD5E1; cursor: pointer; transition: color .12s;" onmouseover="this.style.color='#4F47E6'" onmouseout="this.style.color='#CBD5E1'" aria-label="Save <?= htmlspecialchars($rec['title']) ?>" id="db-rec-bookmark-<?= htmlspecialchars($rec['slug']) ?>">
                            <!-- Saved outline state -->
                            <svg x-show="!saved" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width: 16px; height: 16px;"><path stroke-linecap="round" stroke-linejoin="round" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/></svg>
                            <!-- Saved filled state -->
                            <svg x-show="saved" style="display: none; width: 16px; height: 16px;" class="text-primary" fill="currentColor" viewBox="0 0 24 24"><path d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/></svg>
                        </button>
                        <div class="db-rec-icon">
                            <?= $rec['icon'] ?>
                        </div>
                        <div class="db-rec-title"><?= htmlspecialchars($rec['title']) ?></div>
                        <div class="db-rec-company"><?= htmlspecialchars($rec['company']) ?> • <?= htmlspecialchars($rec['location']) ?></div>
                        <div class="db-rec-tags">
                            <?php foreach ($rec['tags'] as $tag): ?>
                                <span class="db-rec-tag"><?= htmlspecialchars($tag) ?></span>
                            <?php endforeach; ?>
                        </div>
                        <div class="db-rec-footer">
                            <span class="db-rec-salary"><?= htmlspecialchars($rec['salary']) ?></span>
                            <a href="<?= url('/apply/' . $rec['slug']) ?>" class="db-apply-btn" id="db-apply-btn-<?= htmlspecialchars($rec['slug']) ?>">Apply Now</a>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </section>

            <!-- ── Bottom Row ── -->
            <div class="db-bottom-row">
                <!-- Recent Applications -->
                <div class="db-card">
                    <div class="db-table-header">
                        <h3 class="db-card-title">Recent Applications</h3>
                        <div style="position: relative;" x-data="{ open: false }" @click.outside="open = false">
                            <button class="db-filter-btn" type="button" @click="open = !open">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                                Filter
                            </button>
                            <div x-show="open" style="display: none; position: absolute; top: calc(100% + 8px); right: 0; width: 180px; background: #fff; border: 1px solid #E8ECF2; border-radius: 10px; box-shadow: 0 8px 24px rgba(0,0,0,.08); z-index: 50; padding: 8px;">
                                <div style="padding: 6px 12px; font-size: 13px; cursor: pointer; border-radius: 6px;" onmouseover="this.style.background='#F1F5F9'" onmouseout="this.style.background='transparent'">All Statuses</div>
                                <div style="padding: 6px 12px; font-size: 13px; cursor: pointer; border-radius: 6px;" onmouseover="this.style.background='#F1F5F9'" onmouseout="this.style.background='transparent'">Interviewing</div>
                                <div style="padding: 6px 12px; font-size: 13px; cursor: pointer; border-radius: 6px;" onmouseover="this.style.background='#F1F5F9'" onmouseout="this.style.background='transparent'">Accepted</div>
                            </div>
                        </div>
                    </div>
                    <table class="db-table" aria-label="Recent applications">
                        <thead>
                            <tr>
                                <th>Company</th>
                                <th>Role</th>
                                <th class="col-date-applied">Date Applied</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($applications as $app): ?>
                            <tr>
                                <td class="company-name"><?= htmlspecialchars($app['company']) ?></td>
                                <td class="role-name"><?= htmlspecialchars($app['role']) ?></td>
                                <td class="date-cell col-date-applied"><?= htmlspecialchars($app['date']) ?></td>
                            <td><span class="db-status-pill <?= $app['status_class'] ?>"><?= htmlspecialchars($app['status']) ?></span></td>
                            <td style="text-align: right;">
                                <div style="position: relative; display: inline-block; text-align: left;" x-data="{ open: false }" @click.outside="open = false">
                                    <button class="db-action-btn" type="button" @click="open = !open">...</button>
                                    <div x-show="open" style="display: none; position: absolute; top: calc(100% - 5px); right: 0; width: 160px; background: #fff; border: 1px solid #E8ECF2; border-radius: 10px; box-shadow: 0 8px 24px rgba(0,0,0,.08); z-index: 50; padding: 8px;">
                                        <div style="padding: 6px 12px; font-size: 13px; color: #191C1D; cursor: pointer; border-radius: 6px; display: flex; align-items: center; gap: 8px;" onmouseover="this.style.background='#F1F5F9'" onmouseout="this.style.background='transparent'">
                                            <svg style="width: 14px; height: 14px; color: #64748B;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                            <span>View Details</span>
                                        </div>
                                        <div style="padding: 6px 12px; font-size: 13px; color: #DC2626; cursor: pointer; border-radius: 6px; display: flex; align-items: center; gap: 8px;" onmouseover="this.style.background='#FEE2E2'" onmouseout="this.style.background='transparent'">
                                            <svg style="width: 14px; height: 14px; color: #DC2626;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                            <span>Withdraw</span>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                    
                    <div class="db-pagination">
                        <button class="db-page-btn active">1</button>
                        <button class="db-page-btn">2</button>
                        <button class="db-page-btn">3</button>
                        <span class="db-page-dots">...</span>
                        <button class="db-page-btn" style="padding: 0 12px;">Next</button>
                    </div>
                </div>

                <!-- Recent Alerts -->
                <div class="db-card">
                    <div class="db-alerts-header">
                        <h3 class="db-card-title">Recent Alerts</h3>
                        <span class="db-alert-count" aria-label="7 unread alerts">7</span>
                    </div>
                    <div class="db-alert-list" role="list">
                        <?php foreach ($alerts as $alert): ?>
                        <div class="db-alert-item" role="listitem">
                            <div class="db-alert-icon-wrap <?= $alert['color'] ?>">
                                <?= $alert['icon'] ?>
                            </div>
                            <div class="db-alert-content">
                                <div class="db-alert-title"><?= htmlspecialchars($alert['title']) ?></div>
                                <div class="db-alert-desc"><?= htmlspecialchars($alert['desc']) ?></div>
                                <div class="db-alert-time"><?= htmlspecialchars($alert['time']) ?></div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <a href="<?= url('/notifications') ?>" class="db-mark-read">Mark all as read</a>
                </div>
            </div>
        </div><!-- /.db-content -->

        <!-- Global Footer -->
        <div class="db-footer-wrap" style="width: 100%; background: #fff; border-top: 1px solid #E8ECF2;">
            <?php include __DIR__ . '/../../shared/components/footer.php'; ?>
        </div>

        <!-- Mobile Sidebar Toggle Script -->
        <script>
        (function() {
            var hamburger = document.getElementById('db-hamburger');
            var sidebar   = document.querySelector('.db-sidebar');
            var overlay   = document.getElementById('db-sidebar-overlay');
            if (!hamburger || !sidebar || !overlay) return;
            function toggleSidebar() {
                sidebar.classList.toggle('is-open');
                overlay.classList.toggle('is-open');
            }
            hamburger.addEventListener('click', toggleSidebar);
            overlay.addEventListener('click', toggleSidebar);
        })();
        </script>
    </div><!-- /.db-main -->
</div><!-- /.db-shell -->
