<?php

declare(strict_types=1);

/**
 * Component: Reusable Navbar (Header)
 */

$currentPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
$scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
$baseDir = dirname($scriptName);
if ($baseDir !== '/' && $baseDir !== '\\') {
    $currentPath = '/' . ltrim(substr($currentPath, strlen($baseDir)), '/');
}
if ($currentPath !== '/' && str_ends_with($currentPath, '/')) {
    $currentPath = rtrim($currentPath, '/');
}
?>
<header id="site-header" class="bg-white border-b border-gray-100 sticky top-0 z-50" x-data="{ open: false }">
    <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <!-- Logo -->
                <a href="<?= url('/') ?>" class="flex-shrink-0 flex items-center">
                    <img src="<?= asset('assets/images/logos/Logo_dark_dx00tg.svg') ?>" alt="InternConnect" class="h-6 w-auto">
                </a>
                <!-- Desktop Navigation Links -->
                <div class="hidden md:ml-10 md:flex md:space-x-8">
                    <a href="<?= url('/internships') ?>" class="<?= $currentPath === '/internships' ? 'border-primary text-primary' : 'border-transparent text-gray-500 hover:border-primary-hover hover:text-primary' ?> inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition duration-150 ease-in-out">Find Internships</a>
                    <a href="<?= url('/#features') ?>" class="border-transparent text-gray-500 hover:border-primary-hover hover:text-primary inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition duration-150 ease-in-out">How it Works</a>
                    <a href="<?= url('/#pricing') ?>" class="border-transparent text-gray-500 hover:border-primary-hover hover:text-primary inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition duration-150 ease-in-out">For Employers</a>
                    <a href="<?= url('/#elearning') ?>" class="border-transparent text-gray-500 hover:border-primary-hover hover:text-primary inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition duration-150 ease-in-out">E-Learning</a>
                    <a href="<?= url('/#testimonials') ?>" class="border-transparent text-gray-500 hover:border-primary-hover hover:text-primary inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition duration-150 ease-in-out">Success Stories</a>
                </div>
            </div>
            <!-- Desktop Buttons -->
            <div class="hidden md:flex items-center space-x-6">
                <a href="<?= url('/login') ?>" class="<?= $currentPath === '/login' ? 'text-primary' : 'text-gray-600 hover:text-primary' ?> text-sm font-medium transition duration-150 ease-in-out">Log In</a>
                <a href="<?= url('/register') ?>" class="bg-primary text-white hover:bg-primary-hover px-5 py-2.5 rounded-lg text-sm font-medium transition duration-150 ease-in-out shadow-sm">Sign Up</a>
            </div>
            
            <!-- Mobile hamburger menu button -->
            <div class="flex items-center md:hidden">
                <button @click="open = !open" type="button" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-primary-hover transition duration-150 ease-in-out" aria-controls="mobile-menu" aria-expanded="false">
                    <span class="sr-only">Open main menu</span>
                    <!-- Icon when menu is closed -->
                    <svg :class="{'hidden': open, 'block': !open }" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                    <!-- Icon when menu is open -->
                    <svg :class="{'block': open, 'hidden': !open }" class="h-6 w-6 hidden" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </nav>

    <!-- Mobile menu, show/hide based on menu state. -->
    <div x-show="open" @click.away="open = false" class="md:hidden" id="mobile-menu" x-transition style="display: none;">
        <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3 border-t border-gray-100 bg-white">
            <a href="<?= url('/internships') ?>" class="<?= $currentPath === '/internships' ? 'bg-primary-light text-primary font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-primary' ?> block px-3 py-2 rounded-md text-base font-medium transition duration-150 ease-in-out">Find Internships</a>
            <a href="<?= url('/#features') ?>" class="text-gray-600 hover:bg-gray-50 hover:text-primary block px-3 py-2 rounded-md text-base font-medium transition duration-150 ease-in-out">How it Works</a>
            <a href="<?= url('/#pricing') ?>" class="text-gray-600 hover:bg-gray-50 hover:text-primary block px-3 py-2 rounded-md text-base font-medium transition duration-150 ease-in-out">For Employers</a>
            <a href="<?= url('/#elearning') ?>" class="text-gray-600 hover:bg-gray-50 hover:text-primary block px-3 py-2 rounded-md text-base font-medium transition duration-150 ease-in-out">E-Learning</a>
            <a href="<?= url('/#testimonials') ?>" class="text-gray-600 hover:bg-gray-50 hover:text-primary block px-3 py-2 rounded-md text-base font-medium transition duration-150 ease-in-out">Success Stories</a>
            <div class="pt-4 pb-2 border-t border-gray-100 mt-2 flex flex-col space-y-2 px-3">
                <a href="<?= url('/login') ?>" class="<?= $currentPath === '/login' ? 'text-primary bg-gray-50' : 'text-gray-600 hover:text-primary hover:bg-gray-50' ?> text-center block py-2 rounded-md text-base font-medium transition duration-150 ease-in-out">Login</a>
                <a href="<?= url('/register') ?>" class="text-center bg-primary text-white hover:bg-primary-hover block py-2.5 rounded-md text-base font-medium transition duration-150 ease-in-out shadow-sm">Sign Up</a>
            </div>
        </div>
    </div>
</header>
