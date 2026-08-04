<?php

declare(strict_types=1);

/**
 * Component: Landing Page — OUR MISSION Section
 *
 * Layout:  [Image + floating card] LEFT  |  [Text + checklist] RIGHT
 * Image:   homepage_hero_illustration_bnhu4c.png  (Cloudinary)
 * Fully responsive — stacks vertically on mobile.
 */
?>
<section id="features-section" class="mission-section">
    <div class="mission-container">
        <div class="mission-grid">

            <!-- ── LEFT ── Image block with light border & floating card ─────── -->
            <div class="mission-image-col">

                <!-- Soft border wrapper -->
                <div class="mission-image-wrapper">

                    <img
                        src="<?= asset('assets/images/homepage_hero_illustration_bnhu4c.png') ?>"
                        alt="InternConnect — career interview illustration"
                        class="mission-img"
                        loading="lazy">

                    <!-- Floating "Verified Partners" card -->
                    <div class="mission-card">
                        <div class="mission-card-header">
                            <div class="mission-card-icon">
                                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0
                                        0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02
                                        12.02 0 003 9c0 5.591 3.824 10.29 9 11.622
                                        5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                </svg>
                            </div>
                            <span class="mission-card-title">Verified Partners</span>
                        </div>
                        <p class="mission-card-body">
                            Every internship is hand-picked and vetted by our career experts for quality.
                        </p>
                    </div>

                </div>
            </div>

            <!-- ── RIGHT ── Text + checklist ─────────────────────────────────── -->
            <div class="mission-text-col">

                <!-- Eyebrow label -->
                <span class="mission-eyebrow">OUR MISSION</span>

                <!-- Headline -->
                <h2 class="mission-headline">
                    Expertly curated paths<br>for your career growth.
                </h2>

                <!-- Body paragraph -->
                <p class="mission-body">
                    InternConnect was built with one goal: to eliminate the noise of the modern job search.
                    We believe your first professional steps should be guided by clarity and quality,
                    not cluttered interfaces.
                </p>

                <!-- Checklist -->
                <ul class="mission-checklist">
                    <li class="mission-check-item">
                        <div class="mission-check-icon">
                            <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <span>Simplified application tracking dashboard</span>
                    </li>
                    <li class="mission-check-item">
                        <div class="mission-check-icon">
                            <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <span>Verified Opportunities</span>
                    </li>
                </ul>

            </div>

        </div>
    </div>
</section>