<?php
declare(strict_types=1);
/**
 * Shared Employer Dashboard Top Navbar Component
 * Matches latest visual design specs and candidate topbar design language
 * 
 * @var string $activeNav     Active navigation identifier
 * @var array  $employer      Employer info (company_name, role, avatar)
 */

$companyName = $employer['company_name'] ?? ($_SESSION['company_name'] ?? 'Global Tech Solutions');
$compRole    = $employer['role'] ?? ($_SESSION['company_role'] ?? 'Employer Portal');
$compAvatar  = $employer['avatar'] ?? ($_SESSION['company_avatar'] ?? null);
$activeNav   = $activeNav ?? 'dashboard';
?>

<!-- ══ EMPLOYER TOP NAVBAR ══ -->
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

        <!-- Center: Nav Links -->
        <nav class="db-cand-nav-links" aria-label="Employer top navigation">
            <a href="<?= url('/employer/dashboard') ?>" class="db-cand-nav-item <?= $activeNav === 'dashboard' ? 'is-active' : '' ?>">Dashboard</a>
            <a href="<?= url('/employer/dashboard') ?>" class="db-cand-nav-item">Talent Pool</a>
            <a href="<?= url('/employer/dashboard') ?>" class="db-cand-nav-item">Job Postings</a>
            <a href="<?= url('/pricing') ?>" class="db-cand-nav-item">Hiring Plans</a>
            <a href="<?= url('/#testimonials') ?>" class="db-cand-nav-item">Success Stories</a>
        </nav>

        <!-- Right: Search + Notifications + Profile -->
        <div class="db-cand-topnav-right">
            <!-- Search Input -->
            <div class="db-cand-nav-search">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input id="db-emp-search-input" type="search" placeholder="Search candidates, skills..." aria-label="Search candidates">
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
                        <span>Employer Alerts</span>
                        <span style="background: #FEE2E2; color: #EF4444; font-size: 10px; padding: 2px 6px; border-radius: 99px; font-weight: 700;">3 New</span>
                    </div>
                    <div style="display: flex; flex-direction: column; gap: 8px; max-height: 240px; overflow-y: auto;">
                        <div style="display: flex; gap: 10px; padding: 8px; border-radius: 8px; cursor: pointer; transition: background .12s;" onmouseover="this.style.background='#F8FAFC'" onmouseout="this.style.background='transparent'">
                            <div style="width: 28px; height: 28px; border-radius: 50%; background: #EEF2FF; display: flex; align-items: center; justify-content: center; color: #4F47E6; flex-shrink: 0;">
                                <svg style="width: 14px; height: 14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                            </div>
                            <div>
                                <div style="font-size: 12px; font-weight: 600; color: #191C1D;">New Applicant: Sandra Alex</div>
                                <div style="font-size: 11px; color: #64748B;">Senior UX Designer • 2m ago</div>
                            </div>
                        </div>
                        <div style="display: flex; gap: 10px; padding: 8px; border-radius: 8px; cursor: pointer; transition: background .12s;" onmouseover="this.style.background='#F8FAFC'" onmouseout="this.style.background='transparent'">
                            <div style="width: 28px; height: 28px; border-radius: 50%; background: #FEF3C7; display: flex; align-items: center; justify-content: center; color: #D97706; flex-shrink: 0;">
                                <svg style="width: 14px; height: 14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                            <div>
                                <div style="font-size: 12px; font-weight: 600; color: #191C1D;">Interview Confirmed: Sarah Peters</div>
                                <div style="font-size: 11px; color: #64748B;">Tomorrow at 10:00 AM</div>
                            </div>
                        </div>
                        <div style="display: flex; gap: 10px; padding: 8px; border-radius: 8px; cursor: pointer; transition: background .12s;" onmouseover="this.style.background='#F8FAFC'" onmouseout="this.style.background='transparent'">
                            <div style="width: 28px; height: 28px; border-radius: 50%; background: #E0F2FE; display: flex; align-items: center; justify-content: center; color: #0284C7; flex-shrink: 0;">
                                <svg style="width: 14px; height: 14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                            </div>
                            <div>
                                <div style="font-size: 12px; font-weight: 600; color: #191C1D;">Message from Marcus Jordan</div>
                                <div style="font-size: 11px; color: #64748B;">2 hours ago</div>
                            </div>
                        </div>
                    </div>
                    <a href="<?= url('/help') ?>" style="display: block; text-align: center; font-size: 12px; color: #4F47E6; font-weight: 600; margin-top: 12px; text-decoration: none;" onmouseover="this.style.textDecoration='underline'" onmouseout="this.style.textDecoration='none'">View All Notifications</a>
                </div>
            </div>

            <!-- Profile Avatar with Dropdown -->
            <div class="db-cand-nav-profile" x-data="{ open: false }" @click.outside="open = false">
                <div class="db-cand-nav-avatar" role="button" aria-label="Employer account options" tabindex="0" @click="open = !open">
                    <?php if ($compAvatar): ?>
                        <img src="<?= htmlspecialchars($compAvatar) ?>" alt="<?= htmlspecialchars($companyName) ?>">
                    <?php else: ?>
                        <?php
                        $compWords = preg_split('/\s+/', trim($companyName));
                        $compInitials = (count($compWords) >= 2) ? (strtoupper(substr($compWords[0], 0, 1) . substr($compWords[1], 0, 1))) : strtoupper(substr($companyName, 0, 2));
                        ?>
                        <div class="db-avatar-initials-sm" style="background: linear-gradient(135deg, #0052CC, #4F46E5);"><?= htmlspecialchars($compInitials ?: 'IC') ?></div>
                    <?php endif; ?>
                </div>

                <!-- Account Settings Dropdown -->
                <div x-show="open" style="display: none; width: 230px;" class="db-dropdown" @click.stop>
                    <div class="db-dropdown-title"><?= htmlspecialchars($companyName) ?></div>
                    <div style="font-size: 11px; color: #94A3B8; padding: 0 10px 8px; margin-top: -4px; border-bottom: 1px solid #F1F5F9;"><?= htmlspecialchars($compRole) ?></div>
                    <div style="display: flex; flex-direction: column; gap: 4px; padding-top: 6px;">
                        <a href="<?= url('/employer/dashboard') ?>" style="display: flex; align-items: center; gap: 8px; padding: 8px 10px; border-radius: 6px; cursor: pointer; font-size: 13px; color: #475569; text-decoration: none;" onmouseover="this.style.background='#F1F5F9'; this.style.color='#1E293B'" onmouseout="this.style.background='transparent'; this.style.color='#475569'">
                            <svg style="width: 14px; height: 14px; color: #64748B;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                            <span>Dashboard</span>
                        </a>
                        <a href="<?= url('/employer/dashboard') ?>" style="display: flex; align-items: center; gap: 8px; padding: 8px 10px; border-radius: 6px; cursor: pointer; font-size: 13px; color: #475569; text-decoration: none;" onmouseover="this.style.background='#F1F5F9'; this.style.color='#1E293B'" onmouseout="this.style.background='transparent'; this.style.color='#475569'">
                            <svg style="width: 14px; height: 14px; color: #64748B;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            <span>Company Profile</span>
                        </a>
                        <a href="<?= url('/pricing') ?>" style="display: flex; align-items: center; gap: 8px; padding: 8px 10px; border-radius: 6px; cursor: pointer; font-size: 13px; color: #475569; text-decoration: none;" onmouseover="this.style.background='#F1F5F9'; this.style.color='#1E293B'" onmouseout="this.style.background='transparent'; this.style.color='#475569'">
                            <svg style="width: 14px; height: 14px; color: #64748B;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                            <span>Plans & Billing</span>
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
