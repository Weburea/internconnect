<?php

declare(strict_types=1);

/**
 * Component: Landing Page Featured Opportunities Section
 */
?>
<section id="opportunities-section" class="py-16 md:py-24 bg-gray-50 border-t border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Section Header -->
        <div class="text-center max-w-3xl mx-auto mb-16 flex flex-col space-y-4">
            <span class="text-xs font-semibold text-primary uppercase tracking-widest">Latest Roles</span>
            <h2 class="text-3xl sm:text-4xl lg:text-[48px] lg:leading-[50px] font-bold text-gray-900 tracking-tight">
                Featured Opportunities
            </h2>
            <p class="text-base text-gray-500 leading-relaxed">
                Explore a handpicked selection of top-tier internships from fast-growing startups and established tech leaders.
            </p>
        </div>

        <!-- Opportunities Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Card 1: UX Designer -->
            <div class="bg-white rounded-xl p-6 border border-gray-100 shadow-sm hover:shadow-md transition-all duration-300 flex flex-col justify-between group">
                <div class="flex flex-col space-y-4">
                    <!-- Icon and Tag -->
                    <div class="flex justify-between items-start">
                        <div class="h-12 w-12 rounded-lg bg-primary-light flex items-center justify-center p-2 group-hover:bg-primary/10 transition-colors">
                            <img src="<?= asset('assets/images/homepage_icon_margin.png') ?>" alt="UX Designer Icon" class="h-full w-full object-contain">
                        </div>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-50 text-green-700">
                            Full-Time
                        </span>
                    </div>
                    <!-- Details -->
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 group-hover:text-primary transition-colors">UX Designer</h3>
                        <p class="text-sm font-medium text-gray-500">Creative Minds Agency</p>
                    </div>
                    <!-- Description -->
                    <p class="text-sm text-gray-500 leading-relaxed">
                        Design beautiful interfaces and user experiences for global web applications. Collaborate with engineering to build prototypes.
                    </p>
                </div>
                <!-- Location & Action -->
                <div class="mt-6 pt-4 border-t border-gray-50 flex justify-between items-center">
                    <span class="text-xs text-gray-400 flex items-center">
                        <svg class="h-4 w-4 mr-1 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        Remote / Lagos
                    </span>
                    <a href="<?= url('/login') ?>" class="text-sm font-semibold text-primary hover:text-primary-hover transition-colors inline-flex items-center gap-1">
                        Apply Now
                        <svg class="h-4 w-4 transform group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Card 2: Digital Marketing -->
            <div class="bg-white rounded-xl p-6 border border-gray-100 shadow-sm hover:shadow-md transition-all duration-300 flex flex-col justify-between group">
                <div class="flex flex-col space-y-4">
                    <!-- Icon and Tag -->
                    <div class="flex justify-between items-start">
                        <div class="h-12 w-12 rounded-lg bg-primary-light flex items-center justify-center p-2 group-hover:bg-primary/10 transition-colors">
                            <img src="<?= asset('assets/images/homepage_icon_margin_alt.png') ?>" alt="Digital Marketing Icon" class="h-full w-full object-contain">
                        </div>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary-light text-primary-hover">
                            Part-Time
                        </span>
                    </div>
                    <!-- Details -->
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 group-hover:text-primary transition-colors">Digital Marketing</h3>
                        <p class="text-sm font-medium text-gray-500">GrowthHackers Inc</p>
                    </div>
                    <!-- Description -->
                    <p class="text-sm text-gray-500 leading-relaxed">
                        Manage social media campaigns, drive newsletter engagement, optimize search rankings, and track acquisition metrics.
                    </p>
                </div>
                <!-- Location & Action -->
                <div class="mt-6 pt-4 border-t border-gray-50 flex justify-between items-center">
                    <span class="text-xs text-gray-400 flex items-center">
                        <svg class="h-4 w-4 mr-1 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        Hybrid / Abuja
                    </span>
                    <a href="<?= url('/login') ?>" class="text-sm font-semibold text-primary hover:text-primary-hover transition-colors inline-flex items-center gap-1">
                        Apply Now
                        <svg class="h-4 w-4 transform group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Card 3: Backend Developer -->
            <div class="bg-white rounded-xl p-6 border border-gray-100 shadow-sm hover:shadow-md transition-all duration-300 flex flex-col justify-between group">
                <div class="flex flex-col space-y-4">
                    <!-- Icon and Tag -->
                    <div class="flex justify-between items-start">
                        <div class="h-12 w-12 rounded-lg bg-primary-light flex items-center justify-center p-2 group-hover:bg-primary/10 transition-colors">
                            <img src="<?= asset('assets/images/homepage_icon_star.png') ?>" alt="Backend Developer Icon" class="h-full w-full object-contain">
                        </div>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-50 text-purple-700">
                            Remote
                        </span>
                    </div>
                    <!-- Details -->
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 group-hover:text-primary transition-colors">Backend Developer</h3>
                        <p class="text-sm font-medium text-gray-500">TechStack Solutions</p>
                    </div>
                    <!-- Description -->
                    <p class="text-sm text-gray-500 leading-relaxed">
                        Build scalable APIs using modern patterns, optimize SQL database queries, and integrate core infrastructure workflows.
                    </p>
                </div>
                <!-- Location & Action -->
                <div class="mt-6 pt-4 border-t border-gray-50 flex justify-between items-center">
                    <span class="text-xs text-gray-400 flex items-center">
                        <svg class="h-4 w-4 mr-1 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        Remote / Nigeria
                    </span>
                    <a href="<?= url('/login') ?>" class="text-sm font-semibold text-primary hover:text-primary-hover transition-colors inline-flex items-center gap-1">
                        Apply Now
                        <svg class="h-4 w-4 transform group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
        
        <!-- View All Link -->
        <div class="mt-12 text-center">
            <a href="<?= url('/internships') ?>" class="inline-flex items-center gap-2 font-semibold text-primary hover:text-primary-hover transition-colors">
                View All Opportunities
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                </svg>
            </a>
        </div>
    </div>
</section>
