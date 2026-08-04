<?php

declare(strict_types=1);

/**
 * Component: Landing Page Testimonials Section
 */
?>
<section id="testimonials-section" class="py-16 md:py-24 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Section Header -->
        <div class="text-center max-w-3xl mx-auto mb-16 flex flex-col space-y-4">
            <span class="text-xs font-semibold text-primary uppercase tracking-widest">Success Stories</span>
            <h2 class="text-3xl sm:text-4xl lg:text-[48px] lg:leading-[50px] font-bold text-gray-900 tracking-tight">
                Loved by Students &amp; Employers
            </h2>
            <p class="text-base text-gray-500 leading-relaxed">
                Read how we are helping students kickstart their careers and employers find top entry-level talent.
            </p>
        </div>

        <!-- Testimonials Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Testimonial 1 -->
            <div class="bg-gray-50 rounded-xl p-8 border border-gray-100 flex flex-col justify-between hover:shadow-sm transition-shadow">
                <div class="flex flex-col space-y-4">
                    <!-- Rating -->
                    <div class="flex text-amber-400">
                        <?php for ($i = 0; $i < 5; $i++): ?>
                            <svg class="h-5 w-5 fill-current" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                        <?php endfor; ?>
                    </div>
                    <!-- Review text -->
                    <p class="text-gray-600 italic leading-relaxed text-sm md:text-base">
                        "InternConnect made it so easy to showcase my projects. Within a week of creating my profile, I was matched with an amazing agency where I am learning every day!"
                    </p>
                </div>
                <!-- Author Info -->
                <div class="mt-6 flex items-center space-x-3">
                    <div class="h-10 w-10 rounded-full bg-primary-light text-primary flex items-center justify-center font-bold text-sm">
                        AY
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold text-gray-900">Aisha Yusuf</h4>
                        <p class="text-xs text-gray-400">UX Design Intern at Creative Minds Agency</p>
                    </div>
                </div>
            </div>

            <!-- Testimonial 2 -->
            <div class="bg-gray-50 rounded-xl p-8 border border-gray-100 flex flex-col justify-between hover:shadow-sm transition-shadow">
                <div class="flex flex-col space-y-4">
                    <!-- Rating -->
                    <div class="flex text-amber-400">
                        <?php for ($i = 0; $i < 5; $i++): ?>
                            <svg class="h-5 w-5 fill-current" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                        <?php endfor; ?>
                    </div>
                    <!-- Review text -->
                    <p class="text-gray-600 italic leading-relaxed text-sm md:text-base">
                        "Finding qualified entry-level interns used to be a long, tedious process. The structured portfolio verification on InternConnect saves us hours of screening time."
                    </p>
                </div>
                <!-- Author Info -->
                <div class="mt-6 flex items-center space-x-3">
                    <div class="h-10 w-10 rounded-full bg-primary-light text-primary flex items-center justify-center font-bold text-sm">
                        TB
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold text-gray-900">Tunde Balogun</h4>
                        <p class="text-xs text-gray-400">HR Director at TechStack Solutions</p>
                    </div>
                </div>
            </div>

            <!-- Testimonial 3 -->
            <div class="bg-gray-50 rounded-xl p-8 border border-gray-100 flex flex-col justify-between hover:shadow-sm transition-shadow">
                <div class="flex flex-col space-y-4">
                    <!-- Rating -->
                    <div class="flex text-amber-400">
                        <?php for ($i = 0; $i < 5; $i++): ?>
                            <svg class="h-5 w-5 fill-current" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                        <?php endfor; ?>
                    </div>
                    <!-- Review text -->
                    <p class="text-gray-600 italic leading-relaxed text-sm md:text-base">
                        "The structured internship pathways and AI-powered profile recommendations helped me secure a Software Engineering role. I can now track all my application steps in real time."
                    </p>
                </div>
                <!-- Author Info -->
                <div class="mt-6 flex items-center space-x-3">
                    <div class="h-10 w-10 rounded-full bg-primary-light text-primary flex items-center justify-center font-bold text-sm">
                        DJ
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold text-gray-900">Jonathan Davis</h4>
                        <p class="text-xs text-gray-400">Software Engineering Intern at TechCorp Inc.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
