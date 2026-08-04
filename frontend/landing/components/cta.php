<?php

declare(strict_types=1);

/**
 * Component: Landing Page Call-to-Action Section
 */
?>
<section id="cta-section" class="py-12 md:py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="relative rounded-2xl bg-gray-900 overflow-hidden py-16 px-6 md:px-12 text-center shadow-xl">
            <!-- Background effects -->
            <div class="absolute inset-0 opacity-10 pointer-events-none">
                <svg class="absolute top-0 left-0 w-full h-full" fill="none" viewBox="0 0 100 100" preserveAspectRatio="none">
                    <path d="M0 0 L100 100 M100 0 L0 100" stroke="currentColor" stroke-width="0.5" class="text-primary-hover"></path>
                </svg>
            </div>
            
            <div class="relative z-10 max-w-3xl mx-auto flex flex-col space-y-6 items-center">
                <!-- Headline -->
                <h2 class="text-3xl sm:text-4xl lg:text-[48px] lg:leading-[50px] font-bold text-white tracking-tight">
                    Ready to curate your future?
                </h2>
                <!-- Subtext -->
                <p class="text-base sm:text-lg text-gray-300 leading-relaxed max-w-xl">
                    Join 50,000+ applicants receiving weekly curated internship listings
                </p>
                <!-- Subscription Form -->
                <form class="w-full max-w-md pt-4 flex flex-col items-center">
                    <div class="flex flex-col sm:flex-row w-full gap-3 bg-white/5 p-2 rounded-xl border border-white/10 focus-within:border-white/20 transition-colors">
                        <input type="email" placeholder="you@internconnect.com" required
                               class="flex-1 bg-transparent border-none text-white placeholder-gray-400 px-4 py-3 focus:outline-none focus:ring-0 text-sm w-full" />
                        <button type="submit" 
                                class="w-full sm:w-auto inline-flex justify-center items-center px-6 py-3 font-bold rounded-lg text-gray-900 bg-white hover:bg-gray-50 transition duration-150 ease-in-out shadow-sm text-sm whitespace-nowrap">
                            Subscribe
                        </button>
                    </div>
                    <!-- Anti-spam text -->
                    <p class="text-xs text-gray-500 mt-4 text-center">
                        No spam. Only high end career opportunities. Unsubscribe anytime.
                    </p>
                </form>
            </div>
        </div>
    </div>
</section>
