<?php
declare(strict_types=1);
/**
 * View: Candidate Saved Internships
 * Route: /saved
 * Layout: dashboard (custom sidebar layout — no global navbar/footer)
 * @var array  $candidate        Candidate profile data
 * @var array  $savedInternships Saved internships list
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
            <a href="<?= url('/dashboard') ?>" class="db-nav-link">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                <span>Dashboard</span>
            </a>
            <a href="<?= url('/saved') ?>" class="db-nav-link is-active" aria-current="page">
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
                            <div style="display: flex; gap: 10px; padding: 8px; border-radius: 8px; cursor: pointer; transition: background .12s;" onmouseover="this.style.background='#F5F3FF'" onmouseout="this.style.background='transparent'">
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

            <!-- ── Header Section ── -->
            <div class="db-saved-header">
                <div class="db-saved-header-left">
                    <h1>Saved Internships</h1>
                    <p>A curated list of roles you're interested in. Review and apply whenever you're ready.</p>
                </div>
                <div class="db-saved-badge">6 SAVED</div>
            </div>

            <!-- ── Saved Internships Grid ── -->
            <div class="db-saved-grid">
                <?php foreach ($savedInternships as $index => $internship): ?>
                <div class="db-saved-card" id="db-saved-card-<?= $index ?>">
                    <div class="db-saved-card-header">
                        <div class="db-saved-card-logo <?= htmlspecialchars($internship['logo_class']) ?>">
                            <?= $internship['logo_svg'] ?>
                        </div>
                        <button class="db-saved-card-bookmark" aria-label="Unsave <?= htmlspecialchars($internship['title']) ?>">
                            <!-- Bookmark filled state -->
                            <svg fill="currentColor" viewBox="0 0 24 24"><path d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/></svg>
                        </button>
                    </div>
                    
                    <div class="db-saved-card-title"><?= htmlspecialchars($internship['title']) ?></div>
                    <div class="db-saved-card-company"><?= htmlspecialchars($internship['company']) ?> • <?= htmlspecialchars($internship['location']) ?></div>
                    
                    <div class="db-saved-card-tags">
                        <?php foreach ($internship['tags'] as $tag): ?>
                            <span class="db-rec-tag"><?= htmlspecialchars($tag) ?></span>
                        <?php endforeach; ?>
                    </div>
                    
                    <div class="db-saved-card-footer">
                        <button class="db-saved-quick-apply-btn">Quick Apply</button>
                        <button class="db-saved-share-btn" aria-label="Share role">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.684 10.742l4.622-2.312m0 7.14l-4.622-2.312M19 12a3 3 0 11-6 0 3 3 0 016 0zm-6-7a3 3 0 11-6 0 3 3 0 016 0zm0 14a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </button>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- ── Based on Saved Section ── -->
            <div class="db-section-header" style="margin-top: 16px;">
                <h3 class="db-section-title">Based on your saved items</h3>
            </div>
            
            <div class="db-saved-bottom-grid">
                <!-- Resume Optimization Card -->
                <div class="db-saved-banner-card">
                    <div class="db-saved-banner-card-top">
                        <p>Resume Optimization</p>
                        <h4>Tailor your profile for Tech Internships</h4>
                        <span>Our AI tool analyzes your saved roles and suggests keywords to help you stand out to recruiters.</span>
                    </div>
                    <a href="#" class="db-saved-banner-btn">Try AI Optimizer</a>
                </div>

                <!-- Get Alerts Card -->
                <div class="db-saved-alerts-card">
                    <div class="db-saved-alerts-icon">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                    </div>
                    <div class="db-saved-alerts-title">Get Alerts</div>
                    <div class="db-saved-alerts-desc">Never miss a deadline for these companies.</div>
                    <a class="db-saved-alerts-link">Enable Deadlines</a>
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
