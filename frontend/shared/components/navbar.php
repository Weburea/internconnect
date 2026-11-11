<?php

declare(strict_types=1);

/**
 * Component: Reusable Navbar (Header)
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Default state is TRUE (shows Notification Bell and Avatar Dropdown)
$isLoggedIn = true;
if (isset($_GET['logout']) && ($_GET['logout'] === 'true' || $_GET['logout'] === '1')) {
    $isLoggedIn = false;
}

// Compute normalized route path matching App\Core\Router
$rawPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
$scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
$basePath = str_replace(['/public/index.php', '/index.php'], '', $scriptName);

$currentPath = $rawPath;
if ($basePath !== '' && str_starts_with($currentPath, $basePath)) {
    $currentPath = substr($currentPath, strlen($basePath));
    if ($currentPath === '') {
        $currentPath = '/';
    }
}

if (str_starts_with($currentPath, '/public')) {
    $currentPath = substr($currentPath, 7);
    if ($currentPath === '') {
        $currentPath = '/';
    }
}

if ($currentPath !== '/' && str_ends_with($currentPath, '/')) {
    $currentPath = rtrim($currentPath, '/');
}

$isJobsActive        = ($currentPath === '/internships' || $currentPath === '/search' || str_starts_with($currentPath, '/internship') || str_starts_with($currentPath, '/apply'));
$isPricingActive     = ($currentPath === '/pricing' || $currentPath === '/payments' || $currentPath === '/pricing/upgrade' || $currentPath === '/checkout');
$isElearningActive   = ($currentPath === '/elearning' || str_starts_with($currentPath, '/elearning'));
$isHowItWorksActive  = ($currentPath === '/how-it-works' || $currentPath === '/#features');
$isContactActive     = ($currentPath === '/contact');

$userName = $_SESSION['profile_name'] ?? 'Sarah Peters';
$userEmail = $_SESSION['profile_email'] ?? 'sarah.peters@example.com';
$userAvatar = $_SESSION['profile_avatar'] ?? asset('assets/images/profile.png');
?>
<header id="site-header" class="site-main-header" x-data="{ open: false, userMenuOpen: false }">
    <nav class="header-nav-container">
        <div class="header-inner">
            <div class="header-left-group">
                <!-- Logo -->
                <a href="<?= url('/') ?>" class="header-brand-logo" aria-label="InternConnect Home">
                    <img src="<?= asset('assets/images/logos/Logo_dark_dx00tg.svg') ?>" alt="InternConnect" class="header-logo-img">
                </a>
            </div>

            <!-- Desktop Navigation Links (Centered) -->
            <div class="header-desktop-links">
                <a href="<?= url('/internships') ?>" class="header-nav-link <?= $isJobsActive ? 'active' : '' ?>">Find Jobs</a>
                <a href="<?= url('/pricing') ?>" class="header-nav-link <?= $isPricingActive ? 'active' : '' ?>">Pricing</a>
                <a href="<?= url('/elearning') ?>" class="header-nav-link <?= $isElearningActive ? 'active' : '' ?>">E-Learning</a>
                <a href="<?= url('/how-it-works') ?>" class="header-nav-link <?= $isHowItWorksActive ? 'active' : '' ?>">How It Works</a>
                <a href="<?= url('/contact') ?>" class="header-nav-link <?= $isContactActive ? 'active' : '' ?>">Contact Us</a>
            </div>

            <!-- Desktop Right Group -->
            <div class="header-right-group">
                <?php if ($isLoggedIn): ?>

                    <!-- Notification Bell Button with Interactive Dropdown -->
                    <div class="relative" x-data="{ notifOpen: false }" @click.outside="notifOpen = false">
                        <button @click="notifOpen = !notifOpen" type="button" class="header-notif-btn" aria-label="View notifications" aria-expanded="false">
                            <svg class="header-notif-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                            <span class="header-notif-dot"></span>
                        </button>

                        <!-- Notification Dropdown Menu -->
                        <div x-show="notifOpen"
                             x-transition:enter="transition ease-out duration-150"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-100"
                             x-transition:leave-start="transform opacity-100 scale-100"
                             x-transition:leave-end="transform opacity-0 scale-95"
                             class="header-notif-dropdown"
                             style="display: none; width: 320px; position: absolute; right: 0; top: calc(100% + 10px); background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); padding: 14px; z-index: 60;"
                             @click.stop>
                            <div style="margin-bottom: 12px; display: flex; justify-content: space-between; align-items: center;">
                                <span style="font-size: 13px; font-weight: 700; color: #000623;">Notifications</span>
                                <span style="background: #EEF2FF; color: #4F48E6; font-size: 11px; padding: 2px 8px; border-radius: 99px; font-weight: 700;">3 New</span>
                            </div>
                            <div style="display: flex; flex-direction: column; gap: 8px; max-height: 240px; overflow-y: auto;">
                                <a href="<?= url('/candidate/dashboard') ?>" style="display: flex; gap: 10px; padding: 8px; border-radius: 8px; text-decoration: none; transition: background .12s;" onmouseover="this.style.background='#F8FAFC'" onmouseout="this.style.background='transparent'">
                                    <div style="width: 30px; height: 30px; border-radius: 50%; background: #EFF6FF; display: flex; align-items: center; justify-content: center; color: #3B82F6; flex-shrink: 0;">
                                        <svg style="width: 14px; height: 14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    </div>
                                    <div>
                                        <div style="font-size: 12px; font-weight: 600; color: #191C1D;">Interview Scheduled</div>
                                        <div style="font-size: 11px; color: #64748B;">Tomorrow at 10:00 AM</div>
                                    </div>
                                </a>
                                <a href="<?= url('/candidate/applications') ?>" style="display: flex; gap: 10px; padding: 8px; border-radius: 8px; text-decoration: none; transition: background .12s;" onmouseover="this.style.background='#F8FAFC'" onmouseout="this.style.background='transparent'">
                                    <div style="width: 30px; height: 30px; border-radius: 50%; background: #DCFCE7; display: flex; align-items: center; justify-content: center; color: #16A34A; flex-shrink: 0;">
                                        <svg style="width: 14px; height: 14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    </div>
                                    <div>
                                        <div style="font-size: 12px; font-weight: 600; color: #191C1D;">Application Shortlisted</div>
                                        <div style="font-size: 11px; color: #64748B;">2 hours ago</div>
                                    </div>
                                </a>
                                <a href="<?= url('/help') ?>" style="display: flex; gap: 10px; padding: 8px; border-radius: 8px; text-decoration: none; transition: background .12s;" onmouseover="this.style.background='#F8FAFC'" onmouseout="this.style.background='transparent'">
                                    <div style="width: 30px; height: 30px; border-radius: 50%; background: #F5F3FF; display: flex; align-items: center; justify-content: center; color: #7C3AED; flex-shrink: 0;">
                                        <svg style="width: 14px; height: 14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                                    </div>
                                    <div>
                                        <div style="font-size: 12px; font-weight: 600; color: #191C1D;">New Message from Recruiter</div>
                                        <div style="font-size: 11px; color: #64748B;">4 hours ago</div>
                                    </div>
                                </a>
                            </div>
                            <div style="border-top: 1px solid #F1F5F9; margin-top: 10px; padding-top: 8px;">
                                <a href="<?= url('/candidate/dashboard') ?>" style="display: block; text-align: center; font-size: 12px; color: #4F48E6; font-weight: 600; text-decoration: none;">View All Notifications</a>
                            </div>
                        </div>
                    </div>

                    <!-- Avatar with Dropdown -->
                    <div class="relative">
                        <button @click="userMenuOpen = !userMenuOpen" @click.away="userMenuOpen = false" type="button" class="header-avatar-btn" id="user-menu-button" aria-expanded="false" aria-haspopup="true">
                            <img class="header-avatar-img" src="<?= htmlspecialchars($userAvatar) ?>" alt="<?= htmlspecialchars($userName) ?>" onerror="this.src='https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&q=80&w=256'">
                        </button>
                        
                        <!-- Dropdown Menu -->
                        <div x-show="userMenuOpen"
                             x-transition:enter="transition ease-out duration-150"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-100"
                             x-transition:leave-start="transform opacity-100 scale-100"
                             x-transition:leave-end="transform opacity-0 scale-95"
                             class="header-user-dropdown"
                             style="display: none;" role="menu" aria-orientation="vertical" tabindex="-1">
                            
                            <div class="header-dropdown-user-info">
                                <p class="header-dropdown-name"><?= htmlspecialchars($userName) ?></p>
                                <p class="header-dropdown-email"><?= htmlspecialchars($userEmail) ?></p>
                            </div>
                            
                            <a href="<?= url('/candidate/dashboard') ?>" class="header-dropdown-item" role="menuitem">
                                <svg class="header-dropdown-svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                </svg>
                                Dashboard
                            </a>
                            <a href="<?= url('/candidate/saved-internships') ?>" class="header-dropdown-item" role="menuitem">
                                <svg class="header-dropdown-svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                                </svg>
                                Saved Internships
                            </a>
                            <a href="<?= url('/candidate/applications') ?>" class="header-dropdown-item" role="menuitem">
                                <svg class="header-dropdown-svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Applications
                            </a>
                            <a href="<?= url('/help') ?>" class="header-dropdown-item" role="menuitem">
                                <svg class="header-dropdown-svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Help Center
                            </a>
                            <a href="<?= url('/candidate/settings') ?>" class="header-dropdown-item" role="menuitem">
                                <svg class="header-dropdown-svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                Profile Settings
                            </a>
                            
                            <div class="header-dropdown-divider"></div>
                            
                            <a href="<?= url('/logout') ?>" class="header-dropdown-item" role="menuitem">
                                <svg class="header-dropdown-svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                                Log Out
                            </a>
                        </div>
                    </div>
                <?php else: ?>
                    <!-- Logged Out Auth Buttons -->
                    <div class="header-auth-buttons">
                        <a href="<?= url('/login') ?>" class="header-login-btn">Log In</a>
                        <a href="<?= url('/register') ?>" class="header-signup-btn">Sign Up</a>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- Mobile hamburger menu button -->
            <div class="header-mobile-toggle">
                <button @click="open = !open" type="button" class="header-hamburger-btn" aria-controls="mobile-menu" aria-expanded="false">
                    <span class="sr-only">Open main menu</span>
                    <svg :class="{'hidden': open, 'block': !open }" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                    <svg :class="{'block': open, 'hidden': !open }" class="h-6 w-6 hidden" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </nav>

    <!-- Mobile Menu Drawer -->
    <div x-show="open" @click.away="open = false" class="lg:hidden" id="mobile-menu" x-transition style="display: none;">
        <div class="header-mobile-drawer">
            <!-- Mobile Navigation Links -->
            <div class="header-mobile-links">
                <a href="<?= url('/internships') ?>" class="header-mobile-link <?= $isJobsActive ? 'active' : '' ?>">Find Jobs</a>
                <a href="<?= url('/pricing') ?>" class="header-mobile-link <?= $isPricingActive ? 'active' : '' ?>">Pricing</a>
                <a href="<?= url('/elearning') ?>" class="header-mobile-link <?= $isElearningActive ? 'active' : '' ?>">E-Learning</a>
                <a href="<?= url('/how-it-works') ?>" class="header-mobile-link <?= $isHowItWorksActive ? 'active' : '' ?>">How It Works</a>
                <a href="<?= url('/contact') ?>" class="header-mobile-link <?= $isContactActive ? 'active' : '' ?>">Contact Us</a>
            </div>

            <?php if ($isLoggedIn): ?>
                <!-- Mobile User Profile & Links Section -->
                <div class="header-mobile-user-section">
                    <div class="header-mobile-user-card">
                        <img class="header-mobile-avatar" src="<?= htmlspecialchars($userAvatar) ?>" alt="<?= htmlspecialchars($userName) ?>" onerror="this.src='https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&q=80&w=256'">
                        <div>
                            <p class="text-sm font-bold text-gray-900 leading-tight"><?= htmlspecialchars($userName) ?></p>
                            <p class="text-xs text-gray-500 truncate"><?= htmlspecialchars($userEmail) ?></p>
                        </div>
                    </div>
                    <div class="space-y-1 mt-2">
                        <a href="<?= url('/candidate/dashboard') ?>" class="header-mobile-sublink">
                            <svg class="header-mobile-sublink-svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                            Dashboard
                        </a>
                        <a href="<?= url('/candidate/saved-internships') ?>" class="header-mobile-sublink">
                            <svg class="header-mobile-sublink-svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                            </svg>
                            Saved Internships
                        </a>
                        <a href="<?= url('/candidate/applications') ?>" class="header-mobile-sublink">
                            <svg class="header-mobile-sublink-svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Applications
                        </a>
                        <a href="<?= url('/help') ?>" class="header-mobile-sublink">
                            <svg class="header-mobile-sublink-svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Help Center
                        </a>
                        <a href="<?= url('/candidate/settings') ?>" class="header-mobile-sublink">
                            <svg class="header-mobile-sublink-svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            Profile Settings
                        </a>
                        <div class="border-t border-gray-200 my-2"></div>
                        <a href="<?= url('/logout') ?>" class="header-mobile-sublink">
                            <svg class="header-mobile-sublink-svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            Log Out
                        </a>
                    </div>
                </div>
            <?php else: ?>
                <!-- Mobile Auth Buttons -->
                <div class="header-mobile-auth-section">
                    <a href="<?= url('/login') ?>" class="header-mobile-login-btn">Log In</a>
                    <a href="<?= url('/register') ?>" class="header-mobile-signup-btn">Sign Up</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</header>

