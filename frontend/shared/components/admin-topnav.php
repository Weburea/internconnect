<?php
declare(strict_types=1);
/**
 * Shared Admin Mission Control Top Navigation Bar
 * Command-style search with Ctrl+K, grouped results, bell notification panel, and role badge profile menu.
 * 
 * @var array $staff Current admin staff member data
 */

$staffName = $staff['name'] ?? 'Jayden Okafor';
$staffRole = $staff['role'] ?? 'Platform Admin';
$staffInitials = $staff['initials'] ?? 'JO';
$notifCount = $staff['notifications_count'] ?? 3;

// Page heading: title, sidebar group and icon for each admin area
$adminActive = $activeNav ?? 'overview';
$adminOnChat = str_contains($_SERVER['REQUEST_URI'] ?? '', '/admin/ai/chat');
$adminPages = [
    ['title' => 'Interns',          'group' => 'People',         'icon' => 'users',    'keys' => ['interns', 'candidates']],
    ['title' => 'Employers',        'group' => 'People',         'icon' => 'building', 'keys' => ['employers', 'verification']],
    ['title' => 'Jenny AI',         'group' => 'Platform',       'icon' => 'sparkles', 'keys' => ['ai', 'ai_chat']],
    ['title' => 'E-Learning',       'group' => 'Platform',       'icon' => 'book',     'keys' => ['elearning']],
    ['title' => 'Video Interviews', 'group' => 'Platform',       'icon' => 'calendar', 'keys' => ['interviews']],
    ['title' => 'Subscriptions',    'group' => 'Platform',       'icon' => 'card',     'keys' => ['subscriptions']],
    ['title' => 'Testimonials',     'group' => 'Platform',       'icon' => 'chat',     'keys' => ['testimonials']],
    ['title' => 'Communications',   'group' => 'Platform',       'icon' => 'chat',     'keys' => ['communications']],
    ['title' => 'API Reference',    'group' => 'Administration', 'icon' => 'cog',      'keys' => ['api_docs']],
    ['title' => 'Settings & Team',  'group' => 'Administration', 'icon' => 'cog',      'keys' => ['settings', 'team']],
];
$pageHeading = ['workspace' => 'Admin', 'group' => 'Mission Control', 'title' => 'Overview', 'icon' => 'dashboard'];
foreach ($adminPages as $page) {
    if (in_array($adminActive, $page['keys'], true)) {
        $pageHeading = ['workspace' => 'Admin', 'group' => $page['group'], 'title' => $page['title'], 'icon' => $page['icon']];
        break;
    }
}
if ($adminOnChat) {
    $pageHeading = ['workspace' => 'Admin', 'group' => 'Jenny AI', 'title' => 'Gemini Chat', 'icon' => 'chat'];
}
?>
<header class="admin-topnav" x-data="{
    notifOpen: false,
    profileOpen: false
}"
@keydown.window="
    if (event.key === 'Escape') {
        notifOpen = false;
        profileOpen = false;
    }
">
    <div class="flex items-center gap-2 sm:gap-3 flex-1 min-w-0">
        <!-- Mobile Drawer Hamburger Toggle (lg:hidden) -->
        <button type="button" id="admin-hamburger" class="lg:hidden p-2 rounded-lg text-slate-600 hover:bg-slate-100 transition-colors flex-shrink-0" aria-label="Toggle Navigation Drawer">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>

        <!-- Current Page Heading -->
        <?php include __DIR__ . '/topbar-heading.php'; ?>

        <!-- Command-Style Search Box (Triggers Global Command Palette) -->
        <div class="admin-search-wrap cursor-pointer" @click="$dispatch('open-admin-command-palette')" role="button" tabindex="0" aria-label="Open Command Palette (Ctrl+K)">
            <div class="admin-search-ai-chip" aria-hidden="true">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                </svg>
            </div>
            
            <input type="text" 
                   readonly
                   class="admin-search-input cursor-pointer" 
                   placeholder="Ask Jenny or search interns, employers, courses" 
                   aria-label="Ask Jenny or search interns, employers, courses (Ctrl+K)">

            <div class="admin-search-k-chip hidden sm:flex">
                <span>Ctrl K</span>
            </div>
        </div>
    </div>

    <!-- Right Controls -->
    <div class="admin-topnav-right">
        <!-- Notification Bell Dropdown -->
        <div class="relative" @click.outside="notifOpen = false">
            <button type="button" 
                    class="admin-icon-btn" 
                    @click="notifOpen = !notifOpen"
                   
                    aria-label="Platform Notifications">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
                <?php if ($notifCount > 0): ?>
                    <span class="admin-notif-dot"></span>
                <?php endif; ?>
            </button>

            <!-- Dropdown Notifications Panel -->
            <div class="admin-notif-panel" x-show="notifOpen" x-transition style="display: none;">
                <div class="admin-notif-header">
                    <div class="flex items-center gap-2">
                        <span class="font-bold text-sm text-slate-900">Notifications</span>
                        <span class="px-2 py-0.5 rounded-full text-[11px] font-bold bg-indigo-50 text-indigo-700">3 unread</span>
                    </div>
                    <button type="button" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800" @click="notifOpen = false; window.adminToast('All notifications marked as read', '', null, 'success');">
                        Mark all as read
                    </button>
                </div>
                <div class="admin-notif-list">
                    <a href="<?= url('/admin/employers?tab=pending') ?>" class="admin-notif-item is-unread">
                        <div class="w-8 h-8 rounded-full bg-amber-100 text-amber-700 flex items-center justify-center flex-shrink-0 text-xs font-bold">
                            CAC
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="text-xs font-bold text-slate-900">14 employers awaiting verification</div>
                            <div class="text-[11px] text-slate-500 mt-0.5">Oldest submitted 3 days ago by Nexura Technologies.</div>
                            <div class="text-[10px] text-slate-400 mt-1">10 mins ago</div>
                        </div>
                    </a>

                    <a href="<?= url('/admin/ai?tab=flagged') ?>" class="admin-notif-item is-unread">
                        <div class="w-8 h-8 rounded-full bg-purple-100 text-purple-700 flex items-center justify-center flex-shrink-0 text-xs font-bold">
                            AI
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="text-xs font-bold text-slate-900">6 AI matches flagged for review</div>
                            <div class="text-[11px] text-slate-500 mt-0.5">Confidence scored below 65% on backend developer roles.</div>
                            <div class="text-[10px] text-slate-400 mt-1">45 mins ago</div>
                        </div>
                    </a>

                    <a href="<?= url('/admin/interns') ?>" class="admin-notif-item">
                        <div class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center flex-shrink-0 text-xs font-bold">
                            SP
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="text-xs font-bold text-slate-900">Sarah Peters accepted offer</div>
                            <div class="text-[11px] text-slate-500 mt-0.5">UI/UX Design Intern placement confirmed at DesignHub.</div>
                            <div class="text-[10px] text-slate-400 mt-1">2 hours ago</div>
                        </div>
                    </a>
                </div>
                <div class="admin-notif-footer" style="padding: 14px 18px !important; background-color: #F8FAFC !important; border-top: 1px solid #E2E8F0 !important;">
                    <a href="<?= url('/admin/settings/team') ?>" class="admin-notif-footer-btn" style="display: inline-flex !important; align-items: center !important; justify-content: center !important; gap: 8px !important; width: 100% !important; padding: 9px 16px !important; border-radius: 10px !important; background-color: #EEF2FF !important; border: 1px solid #E0E7FF !important; color: #4F48E6 !important; font-size: 12.5px !important; font-weight: 600 !important; text-decoration: none !important; white-space: nowrap !important;">
                        <span>View all platform audit logs</span>
                        <svg class="w-3.5 h-3.5 flex-shrink-0" style="width: 14px; height: 14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>

        <!-- Profile Menu with Admin Role Badge -->
        <div class="relative" @click.outside="profileOpen = false">
            <div class="admin-top-profile" @click="profileOpen = !profileOpen" role="button" tabindex="0">
                <div class="admin-avatar-circle" style="width: 32px; height: 32px; font-size: 11.5px;">
                    <?= htmlspecialchars($staffInitials) ?>
                </div>
                <div class="hidden sm:flex flex-col items-start leading-tight">
                    <span class="text-xs font-bold text-slate-900"><?= htmlspecialchars($staffName) ?></span>
                    <span class="admin-role-badge-pill"><?= htmlspecialchars($staffRole) ?></span>
                </div>
                <svg class="w-3.5 h-3.5 text-slate-400 transition-transform duration-150" :class="{ 'rotate-180': profileOpen }" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                </svg>
            </div>

            <!-- Profile Dropdown Menu -->
            <div class="admin-top-profile-menu" x-show="profileOpen" x-transition style="display: none;">
                <div class="px-3 py-2 border-b border-slate-100">
                    <div class="text-xs font-bold text-slate-900"><?= htmlspecialchars($staffName) ?></div>
                    <div class="text-[11px] text-slate-500 truncate"><?= htmlspecialchars($staff['email'] ?? 'admin@internconnect.ng') ?></div>
                </div>
                <a href="<?= url('/admin/settings/team') ?>" class="admin-top-profile-item">
                    <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <span>Staff & Roles</span>
                </a>
                <a href="<?= url('/admin/logout') ?>" class="admin-top-profile-item text-rose-600">
                    <svg class="w-4 h-4 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    <span>Sign out</span>
                </a>
            </div>
        </div>
    </div>
</header>
