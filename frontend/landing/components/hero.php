<?php

declare(strict_types=1);

/**
 * Component: Landing Page Hero Section
 */
?>
<section id="hero-section" class="relative overflow-hidden bg-white py-16 md:py-24 bento-grid-container">
    <!-- Bento Grid Background Lines Overlay -->
    <div class="bento-grid-lines"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-8 items-center">
            <!-- Left: Text content -->
            <div class="flex flex-col space-y-6 max-w-2xl">
                <!-- Badge -->
                <div class="inline-flex items-center self-start">
                    <span
                        class="inline-flex items-center px-2.5 py-1.5 rounded-full text-xs font-semibold bg-primary-light text-primary uppercase tracking-wider border border-primary-light">
                        Over 10,000+ Students Connected
                    </span>
                </div>
                <!-- Headline -->
                <h1
                    class="text-3xl sm:text-5xl lg:text-[72px] lg:leading-[68px] font-bold text-gray-900 tracking-tight">
                    Find and Track Your <span class="text-primary">Internship Journey</span> in One Place
                </h1>
                <!-- Subheadline -->
                <p class="text-base sm:text-lg text-gray-500 leading-relaxed max-w-lg">
                    The ultimate destination for ambitious applicants. Manage applications, discover premium
                    opportunities, and launch your career with professional precision.
                </p>
                <!-- Buttons -->
                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-4 pt-2">
                    <a href="<?= url('/internships') ?>"
                        class="inline-flex justify-center items-center px-6 py-3 border border-transparent text-base font-semibold rounded-lg text-white bg-primary hover:bg-primary-hover transition duration-150 ease-in-out shadow-md text-center">
                        Find Internships
                    </a>
                    <a href="<?= url('/#pricing') ?>"
                        class="inline-flex justify-center items-center px-6 py-3 border border-gray-300 text-base font-semibold rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition duration-150 ease-in-out text-center">
                        For Employers
                    </a>
                </div>
            </div>

            <!-- Right: Hero Visual Showcase with Bento Card Border & Floating Overlay -->
            <div class="relative flex justify-center lg:justify-end">
                <div
                    class="w-full max-w-[540px] lg:max-w-full relative rounded-3xl overflow-hidden shadow-2xl border border-slate-200/80 bg-slate-50 bento-card-wrapper">
                    <!-- Bento Corner Crosshair Accents -->
                    <div class="bento-plus-corner -top-1 -left-1"></div>
                    <div class="bento-plus-corner -top-1 -right-1"></div>
                    <div class="bento-plus-corner -bottom-1 -left-1"></div>
                    <div class="bento-plus-corner -bottom-1 -right-1"></div>

                    <img src="https://res.cloudinary.com/weburea/image/upload/v1785645677/internConnect/images/homepage_feature_showcase.png"
                        alt="InternConnect Feature Showcase" class="w-full h-auto object-cover rounded-3xl"
                        loading="eager">

                    <!-- Floating Card Overlay (500+ Top Talent Placed This Month) -->
                    <div class="bento-hero-card-overlay">
                        <!-- 3 Icon Avatars Stack -->
                        <div class="bento-avatar-stack">
                            <img class="bento-avatar-img"
                                src="https://res.cloudinary.com/weburea/image/upload/v1785645709/internConnect/images/homepage_icon_star.png"
                                alt="Star Talent">
                            <img class="bento-avatar-img"
                                src="https://res.cloudinary.com/weburea/image/upload/v1785645701/internConnect/images/homepage_icon_margin_alt.png"
                                alt="Margin Alt Talent">
                            <img class="bento-avatar-img"
                                src="https://res.cloudinary.com/weburea/image/upload/v1785645693/internConnect/images/homepage_icon_margin.png"
                                alt="Margin Talent">
                        </div>
                        <span class="text-sm sm:text-base font-bold text-slate-900 tracking-tight">
                            500+ Top Talent Placed This Month
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>