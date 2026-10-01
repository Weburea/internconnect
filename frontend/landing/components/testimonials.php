<?php

declare(strict_types=1);

/**
 * Component: Landing Page - Interactive Video & Vertical Marquee Testimonials Section
 * Location: Directly below Featured Opportunities (opportunities.php)
 * 
 * Architecture:
 * - Left & Right Columns: Vertical marquee auto-scrolling social testimonial cards with premium shaded platform badges (LinkedIn, X, Instagram, Facebook).
 * - Center Column: Autoplaying vertical video story carousel (completely silent/no audio, no hover arrows, IDM-shielded, ultra-fast 1.5MB stream).
 * - Bottom CTA: Standardized primary pill CTA button matching .process-btn-cta.
 */

$videoClips = [
    [
        'url' => 'https://res.cloudinary.com/weburea/video/upload/w_400,q_auto:eco,ac_none/v1790815553/test_1_z86bwq.mp4',
        'name' => 'Emily R.',
        'role' => 'Marketing & Growth Intern',
        'avatar' => 'https://res.cloudinary.com/weburea/image/upload/v1790816969/test_1_mgvqdi.png',
    ],
    [
        'url' => 'https://res.cloudinary.com/weburea/video/upload/w_400,q_auto:eco,ac_none/v1790815546/test_2_uypp2k.mp4',
        'name' => 'Chioma B.',
        'role' => 'Brand & Strategy Intern',
        'avatar' => 'https://res.cloudinary.com/weburea/image/upload/v1790816968/test_2_hi8rmz.png',
    ],
    [
        'url' => 'https://res.cloudinary.com/weburea/video/upload/w_400,q_auto:eco,ac_none/v1790815553/test_4_l0uhcx.mp4',
        'name' => 'Anastasia K.',
        'role' => 'Product Design Intern',
        'avatar' => 'https://res.cloudinary.com/weburea/image/upload/v1790816969/test_4_adtliv.png',
    ],
    [
        'url' => 'https://res.cloudinary.com/weburea/video/upload/w_400,q_auto:eco,ac_none/v1790815544/test_3_radsqu.mp4',
        'name' => 'Sipho D.',
        'role' => 'Software Engineering Intern',
        'avatar' => 'https://res.cloudinary.com/weburea/image/upload/v1790816968/test_3_kakx5q.png',
    ],
];
?>

<style>
/* ─── Testimonials Section Wrapper ─── */
.testimonials-section-wrap {
    position: relative;
    background-color: #FFFFFF;
    padding: 72px 0 96px 0;
    width: 100%;
    overflow: hidden;
    box-sizing: border-box;
}

@media (min-width: 1024px) {
    .testimonials-section-wrap {
        padding: 96px 0 112px 0;
    }
}

.testimonials-container {
    max-width: 1280px;
    margin: 0 auto;
    padding: 0 24px;
    width: 100%;
    box-sizing: border-box;
}

/* ─── Section Header ─── */
.testimonials-header-box {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    max-width: 760px;
    margin: 0 auto 52px auto;
    gap: 14px;
    width: 100%;
    box-sizing: border-box;
}

.testimonials-eyebrow-pill {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 6px 14px;
    border-radius: 9999px;
    background-color: #EEF2FF;
    color: #4F48E6;
    border: 1px solid #C7D2FE;
    font-size: 12px;
    font-weight: 700;
    letter-spacing: 0.04em;
    text-transform: uppercase;
    white-space: nowrap;
    width: fit-content;
}

.testimonials-pulse-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background-color: #4F48E6;
    animation: testDotPulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

@keyframes testDotPulse {
    0%, 100% { opacity: 1; transform: scale(1); }
    50% { opacity: 0.4; transform: scale(0.85); }
}

.testimonials-title {
    font-size: clamp(28px, 4vw, 44px);
    line-height: 1.18;
    font-weight: 800;
    color: #000623;
    letter-spacing: -0.025em;
    margin: 0;
    word-break: break-word;
}

.testimonials-subtext {
    font-size: 16px;
    line-height: 1.6;
    color: #475569;
    max-width: 660px;
    margin: 0 auto;
    font-weight: 400;
}

/* ─── 3-Column Stage Grid ─── */
.testimonials-stage-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 24px;
    width: 100%;
    box-sizing: border-box;
    align-items: center;
}

@media (min-width: 1024px) {
    .testimonials-stage-grid {
        grid-template-columns: minmax(0, 1fr) 350px minmax(0, 1fr);
        gap: 28px;
        min-height: 580px;
    }
}

/* ─── Vertical Marquee Column ─── */
.testimonials-marquee-col {
    position: relative;
    height: 580px;
    overflow: hidden;
    mask-image: linear-gradient(to bottom, transparent 0%, black 8%, black 92%, transparent 100%);
    -webkit-mask-image: linear-gradient(to bottom, transparent 0%, black 8%, black 92%, transparent 100%);
    box-sizing: border-box;
}

.testimonials-marquee-track {
    display: flex;
    flex-direction: column;
    gap: 20px;
    animation: testMarqueeScroll 32s linear infinite;
    will-change: transform;
}

.testimonials-marquee-track.track-alt {
    animation-duration: 40s;
    animation-delay: -12s;
}

.testimonials-marquee-col:hover .testimonials-marquee-track {
    animation-play-state: paused;
}

@keyframes testMarqueeScroll {
    0% {
        transform: translateY(0);
    }
    100% {
        transform: translateY(-50%);
    }
}

/* ─── Testimonial Social Card ─── */
.testimonial-social-card {
    background: #FFFFFF;
    border: 1px solid #E2E8F0;
    border-radius: 20px;
    padding: 22px 24px;
    box-shadow: 0 4px 20px -2px rgba(0, 6, 35, 0.05), 0 2px 6px -1px rgba(0, 6, 35, 0.02);
    display: flex;
    flex-direction: column;
    gap: 14px;
    transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease;
    box-sizing: border-box;
    user-select: none;
    width: 100%;
}

.testimonial-social-card:hover {
    transform: translateY(-2px);
    border-color: #CBD5E1;
    box-shadow: 0 12px 30px -4px rgba(0, 6, 35, 0.08), 0 4px 10px -2px rgba(0, 6, 35, 0.03);
}

.testimonial-card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
}

.testimonial-author-box {
    display: flex;
    align-items: center;
    gap: 12px;
    min-width: 0;
}

.testimonial-avatar-img {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    object-fit: cover;
    flex-shrink: 0;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    border: 1.5px solid #F1F5F9;
}

.testimonial-author-meta {
    display: flex;
    flex-direction: column;
    min-width: 0;
}

.testimonial-author-name {
    font-size: 14.5px;
    font-weight: 700;
    color: #000623;
    line-height: 1.25;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.testimonial-author-handle {
    font-size: 12.5px;
    color: #64748B;
    font-weight: 500;
    line-height: 1.25;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

/* ─── Premium Shaded Social Platform Badges ─── */
.testimonial-platform-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    border-radius: 9px;
    flex-shrink: 0;
    transition: all 0.2s ease;
}

.badge-linkedin {
    background: #F0F7FF;
    border: 1px solid #D0E7FF;
    color: #0A66C2;
}

.badge-instagram {
    background: #FFF1F5;
    border: 1px solid #FFE0EB;
    color: #E1306C;
}

.badge-x {
    background: #F8FAFC;
    border: 1px solid #E2E8F0;
    color: #0F172A;
}

.badge-facebook {
    background: #EFF6FF;
    border: 1px solid #DBEAFE;
    color: #1877F2;
}

.testimonial-text-content {
    font-size: 13.5px;
    line-height: 1.62;
    color: #334155;
    margin: 0;
    font-weight: 400;
}

/* ─── Center Column: Vertical Video Story Carousel ─── */
.testimonials-video-stage {
    position: relative;
    width: 100%;
    max-width: 350px;
    height: 580px;
    margin: 0 auto;
    border-radius: 24px;
    overflow: hidden;
    background: #000623;
    box-shadow: 0 25px 60px -12px rgba(0, 6, 35, 0.22), 0 0 0 1px rgba(255, 255, 255, 0.1) inset;
    box-sizing: border-box;
    user-select: none;
}

/* Protective transparent shield that prevents IDM hover interception & mouse click blocks */
.testimonials-video-shield {
    position: absolute;
    inset: 0;
    z-index: 15;
    pointer-events: auto;
    background: transparent;
    user-select: none;
}

/* Segmented Story Progress Bar (Instagram / Snapchat Style) */
.testimonials-story-bar-wrap {
    position: absolute;
    top: 14px;
    left: 14px;
    right: 14px;
    display: flex;
    gap: 6px;
    z-index: 30;
    pointer-events: none;
}

.testimonials-story-seg {
    flex: 1;
    height: 3.5px;
    background: rgba(255, 255, 255, 0.35);
    border-radius: 9999px;
    overflow: hidden;
    position: relative;
    backdrop-filter: blur(4px);
}

.testimonials-story-seg-fill {
    position: absolute;
    top: 0;
    left: 0;
    height: 100%;
    background: #FFFFFF;
    border-radius: 9999px;
    width: 0%;
    transition: width 0.08s linear;
}

/* Speaker Overlay (Top-Left) */
.testimonials-speaker-badge {
    position: absolute;
    top: 28px;
    left: 14px;
    right: 14px;
    display: flex;
    align-items: center;
    gap: 10px;
    z-index: 25;
    pointer-events: none;
    padding: 7px 12px;
    background: rgba(0, 6, 35, 0.55);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    border-radius: 9999px;
    border: 1px solid rgba(255, 255, 255, 0.15);
    width: fit-content;
    max-width: calc(100% - 28px);
    box-sizing: border-box;
}

.testimonials-speaker-avatar {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    object-fit: cover;
    border: 1.5px solid rgba(255, 255, 255, 0.85);
    flex-shrink: 0;
    background-color: #000623;
}

.testimonials-speaker-info {
    display: flex;
    align-items: baseline;
    gap: 6px;
    min-width: 0;
    overflow: hidden;
}

.testimonials-speaker-name {
    font-size: 13px;
    font-weight: 700;
    color: #FFFFFF;
    white-space: nowrap;
}

.testimonials-speaker-role {
    font-size: 11.5px;
    color: rgba(255, 255, 255, 0.85);
    font-weight: 500;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

/* Video Player Layer & Crossfading Stack */
.testimonials-video-viewport {
    position: relative;
    width: 100%;
    height: 100%;
    overflow: hidden;
}

.testimonials-video-clip {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    opacity: 0;
    transition: opacity 0.4s cubic-bezier(0.16, 1, 0.3, 1);
    pointer-events: none !important;
    background: #000623;
}

.testimonials-video-clip.is-active {
    opacity: 1;
}

/* Bottom Floating Status Pill */
.testimonials-bottom-bar {
    position: absolute;
    bottom: 16px;
    left: 16px;
    right: 16px;
    display: flex;
    align-items: center;
    justify-content: flex-start;
    z-index: 25;
    pointer-events: none;
}

.testimonials-status-pill {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 4px 10px;
    border-radius: 9999px;
    background: rgba(0, 6, 35, 0.5);
    backdrop-filter: blur(8px);
    border: 1px solid rgba(255, 255, 255, 0.15);
    color: #FFFFFF;
    font-size: 11px;
    font-weight: 600;
}

.testimonials-live-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: #10B981;
    box-shadow: 0 0 6px #10B981;
}

/* ─── Bottom CTA: Standard Platform Button Style ─── */
.testimonials-cta-wrap {
    margin-top: 48px;
    text-align: center;
    display: flex;
    justify-content: center;
}

.testimonials-btn-cta {
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    gap: 8px !important;
    padding: 12px 28px !important;
    border-radius: 9999px !important;
    background-color: #4F48E6 !important;
    color: #FFFFFF !important;
    font-weight: 600 !important;
    font-size: 14px !important;
    border: none !important;
    cursor: pointer !important;
    text-decoration: none !important;
    transition: all 0.2s ease !important;
    white-space: nowrap !important;
    flex-shrink: 0 !important;
    box-shadow: 0 4px 14px rgba(79, 72, 230, 0.35) !important;
    width: fit-content !important;
}

.testimonials-btn-cta:hover {
    background-color: #4338CA !important;
    box-shadow: 0 6px 18px rgba(79, 72, 230, 0.45) !important;
    transform: translateY(-1px);
}

/* Responsive Breakpoints */
@media (max-width: 1023px) {
    .testimonials-stage-grid {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 32px;
    }
    
    .testimonials-video-stage {
        order: 1;
        max-width: 330px;
        height: 540px;
    }

    .testimonials-marquee-col {
        order: 2;
        width: 100%;
        max-width: 480px;
        height: 400px;
    }

    .testimonials-marquee-col.col-secondary {
        display: none;
    }
}

@media (max-width: 640px) {
    .testimonials-section-wrap {
        padding: 56px 0 72px 0;
    }

    .testimonials-container {
        padding: 0 16px;
    }

    .testimonials-header-box {
        margin-bottom: 36px;
    }

    .testimonials-video-stage {
        max-width: 310px;
        height: 500px;
    }
    
    .testimonials-marquee-col {
        max-width: 100%;
        height: 360px;
    }
}
</style>

<section id="testimonials-section" class="testimonials-section-wrap">
    <div class="testimonials-container">
        
        <!-- Section Header (Image 1 copy & Dribbble pill) -->
        <div class="testimonials-header-box">
            <div class="testimonials-eyebrow-pill">
                <span class="testimonials-pulse-dot"></span>
                <span>Testimonial</span>
            </div>
            <h2 class="testimonials-title">
                Trusted by Ambitious Talent &amp; Top Employers
            </h2>
            <p class="testimonials-subtext">
                See how students are finding opportunities and employers are discovering talented individuals through InternConnect.
            </p>
        </div>

        <!-- 3-Column Stage Grid -->
        <div class="testimonials-stage-grid" x-data="testimonialsStoryCarousel()">

            <!-- Left Column: Vertical Marquee Auto-Scroll (Group A) -->
            <div class="testimonials-marquee-col">
                <div class="testimonials-marquee-track">
                    
                    <!-- Card 1: Daniella O. (Flux.tech - LinkedIn) -->
                    <div class="testimonial-social-card">
                        <div class="testimonial-card-header">
                            <div class="testimonial-author-box">
                                <img src="https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=150&auto=format&fit=crop&q=80" alt="Daniella O." class="testimonial-avatar-img">
                                <div class="testimonial-author-meta">
                                    <span class="testimonial-author-name">Daniella O.</span>
                                    <span class="testimonial-author-handle">@Flux.tech</span>
                                </div>
                            </div>
                            <div class="testimonial-platform-badge badge-linkedin" title="LinkedIn">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M19 3a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h14m-.5 15.5v-5.3a3.26 3.26 0 0 0-3.26-3.26c-.85 0-1.84.52-2.28 1.3v-1.11h-2.79v8.37h2.79v-4.93c0-.77.62-1.4 1.39-1.4a1.4 1.4 0 0 1 1.4 1.4v4.93h2.75M6.88 8.56a1.68 1.68 0 0 0 1.68-1.68c0-.93-.75-1.69-1.68-1.69a1.69 1.69 0 0 0-1.69 1.69c0 .93.76 1.68 1.69 1.68m1.39 9.94v-8.37H5.5v8.37h2.77z"/>
                                </svg>
                            </div>
                        </div>
                        <p class="testimonial-text-content">
                            "The quality of candidates on InternConnect is impressive. We're able to connect with motivated students who have the right skills, attitude, and willingness to learn"
                        </p>
                    </div>

                    <!-- Card 2: David M. (Google.com - Instagram) -->
                    <div class="testimonial-social-card">
                        <div class="testimonial-card-header">
                            <div class="testimonial-author-box">
                                <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=150&auto=format&fit=crop&q=80" alt="David M." class="testimonial-avatar-img">
                                <div class="testimonial-author-meta">
                                    <span class="testimonial-author-name">David M.</span>
                                    <span class="testimonial-author-handle">@Google.com</span>
                                </div>
                            </div>
                            <div class="testimonial-platform-badge badge-instagram" title="Instagram">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect width="20" height="20" x="2" y="2" rx="5" ry="5"/>
                                    <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/>
                                    <line x1="17.5" x2="17.51" y1="6.5" y2="6.5" stroke-width="2.5" stroke-linecap="round"/>
                                </svg>
                            </div>
                        </div>
                        <p class="testimonial-text-content">
                            "Finding the right intern can take a lot of time, but InternConnect has made the process much more efficient for us. The candidates are ambitious, prepared, and eager to gain real-world experience."
                        </p>
                    </div>

                    <!-- Card 3: Anita K. (Prescot LTD - LinkedIn) -->
                    <div class="testimonial-social-card">
                        <div class="testimonial-card-header">
                            <div class="testimonial-author-box">
                                <img src="https://images.unsplash.com/photo-1524504388940-b1c1722653e1?w=150&auto=format&fit=crop&q=80" alt="Anita K." class="testimonial-avatar-img">
                                <div class="testimonial-author-meta">
                                    <span class="testimonial-author-name">Anita K.</span>
                                    <span class="testimonial-author-handle">@AnitaK_design</span>
                                </div>
                            </div>
                            <div class="testimonial-platform-badge badge-linkedin" title="LinkedIn">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M19 3a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h14m-.5 15.5v-5.3a3.26 3.26 0 0 0-3.26-3.26c-.85 0-1.84.52-2.28 1.3v-1.11h-2.79v8.37h2.79v-4.93c0-.77.62-1.4 1.39-1.4a1.4 1.4 0 0 1 1.4 1.4v4.93h2.75M6.88 8.56a1.68 1.68 0 0 0 1.68-1.68c0-.93-.75-1.69-1.68-1.69a1.69 1.69 0 0 0-1.69 1.69c0 .93.76 1.68 1.69 1.68m1.39 9.94v-8.37H5.5v8.37h2.77z"/>
                                </svg>
                            </div>
                        </div>
                        <p class="testimonial-text-content">
                            "I really liked how simple the application process was. I could explore different opportunities, understand what employers were looking for, and apply without endless paperwork."
                        </p>
                    </div>

                    <!-- Duplicate Cards for Seamless Infinite Vertical Marquee Loop -->
                    <div class="testimonial-social-card" aria-hidden="true">
                        <div class="testimonial-card-header">
                            <div class="testimonial-author-box">
                                <img src="https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=150&auto=format&fit=crop&q=80" alt="Daniella O." class="testimonial-avatar-img">
                                <div class="testimonial-author-meta">
                                    <span class="testimonial-author-name">Daniella O.</span>
                                    <span class="testimonial-author-handle">@Flux.tech</span>
                                </div>
                            </div>
                            <div class="testimonial-platform-badge badge-linkedin">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M19 3a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h14m-.5 15.5v-5.3a3.26 3.26 0 0 0-3.26-3.26c-.85 0-1.84.52-2.28 1.3v-1.11h-2.79v8.37h2.79v-4.93c0-.77.62-1.4 1.39-1.4a1.4 1.4 0 0 1 1.4 1.4v4.93h2.75M6.88 8.56a1.68 1.68 0 0 0 1.68-1.68c0-.93-.75-1.69-1.68-1.69a1.69 1.69 0 0 0-1.69 1.69c0 .93.76 1.68 1.69 1.68m1.39 9.94v-8.37H5.5v8.37h2.77z"/></svg>
                            </div>
                        </div>
                        <p class="testimonial-text-content">
                            "The quality of candidates on InternConnect is impressive. We're able to connect with motivated students who have the right skills, attitude, and willingness to learn"
                        </p>
                    </div>

                    <div class="testimonial-social-card" aria-hidden="true">
                        <div class="testimonial-card-header">
                            <div class="testimonial-author-box">
                                <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=150&auto=format&fit=crop&q=80" alt="David M." class="testimonial-avatar-img">
                                <div class="testimonial-author-meta">
                                    <span class="testimonial-author-name">David M.</span>
                                    <span class="testimonial-author-handle">@Google.com</span>
                                </div>
                            </div>
                            <div class="testimonial-platform-badge badge-instagram">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="20" height="20" x="2" y="2" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" x2="17.51" y1="6.5" y2="6.5" stroke-width="2.5" stroke-linecap="round"/></svg>
                            </div>
                        </div>
                        <p class="testimonial-text-content">
                            "Finding the right intern can take a lot of time, but InternConnect has made the process much more efficient for us. The candidates are ambitious, prepared, and eager to gain real-world experience."
                        </p>
                    </div>

                    <div class="testimonial-social-card" aria-hidden="true">
                        <div class="testimonial-card-header">
                            <div class="testimonial-author-box">
                                <img src="https://images.unsplash.com/photo-1524504388940-b1c1722653e1?w=150&auto=format&fit=crop&q=80" alt="Anita K." class="testimonial-avatar-img">
                                <div class="testimonial-author-meta">
                                    <span class="testimonial-author-name">Anita K.</span>
                                    <span class="testimonial-author-handle">@AnitaK_design</span>
                                </div>
                            </div>
                            <div class="testimonial-platform-badge badge-linkedin">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M19 3a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h14m-.5 15.5v-5.3a3.26 3.26 0 0 0-3.26-3.26c-.85 0-1.84.52-2.28 1.3v-1.11h-2.79v8.37h2.79v-4.93c0-.77.62-1.4 1.39-1.4a1.4 1.4 0 0 1 1.4 1.4v4.93h2.75M6.88 8.56a1.68 1.68 0 0 0 1.68-1.68c0-.93-.75-1.69-1.68-1.69a1.69 1.69 0 0 0-1.69 1.69c0 .93.76 1.68 1.69 1.68m1.39 9.94v-8.37H5.5v8.37h2.77z"/></svg>
                            </div>
                        </div>
                        <p class="testimonial-text-content">
                            "I really liked how simple the application process was. I could explore different opportunities, understand what employers were looking for, and apply without endless paperwork."
                        </p>
                    </div>

                </div>
            </div>

            <!-- Center Column: Vertical Video Story Carousel (Instagram / Snapchat Style) -->
            <div class="testimonials-video-stage">
                
                <!-- Transparent shield layer against IDM hover detection & click interference -->
                <div class="testimonials-video-shield" aria-hidden="true" oncontextmenu="return false;"></div>

                <!-- Story Progress Bars (4 Segments) -->
                <div class="testimonials-story-bar-wrap">
                    <?php for ($s = 0; $s < 4; $s++): ?>
                        <div class="testimonials-story-seg">
                            <div class="testimonials-story-seg-fill"
                                 :style="activeClip > <?= $s ?> ? 'width: 100%' : (activeClip === <?= $s ?> ? 'width: ' + progress + '%' : 'width: 0%')">
                            </div>
                        </div>
                    <?php endfor; ?>
                </div>

                <!-- Speaker Overlay (Top-Left) -->
                <div class="testimonials-speaker-badge">
                    <img :src="clips[activeClip].avatar" :alt="clips[activeClip].name" class="testimonials-speaker-avatar">
                    <div class="testimonials-speaker-info">
                        <span class="testimonials-speaker-name" x-text="clips[activeClip].name"></span>
                        <span class="testimonials-speaker-role" x-text="'— ' + clips[activeClip].role"></span>
                    </div>
                </div>

                <!-- Stacked Crossfading Video Player -->
                <div class="testimonials-video-viewport" oncontextmenu="return false;">
                    <?php foreach ($videoClips as $idx => $clip): ?>
                        <video 
                            id="testimonial-vid-<?= $idx ?>"
                            class="testimonials-video-clip" 
                            :class="{ 'is-active': activeClip === <?= $idx ?> }"
                            src="<?= htmlspecialchars($clip['url']) ?>"
                            playsinline
                            muted
                            preload="metadata"
                            disablePictureInPicture
                            controlsList="nodownload nofullscreen noremoteplayback"
                            oncontextmenu="return false;"
                            tabindex="-1"
                            aria-hidden="true"
                        ></video>
                    <?php endforeach; ?>
                </div>

                <!-- Bottom Floating Bar: Active Indicator Only (No Audio Controls) -->
                <div class="testimonials-bottom-bar">
                    <div class="testimonials-status-pill">
                        <span class="testimonials-live-dot"></span>
                        <span x-text="'Story ' + (activeClip + 1) + ' of ' + clips.length"></span>
                    </div>
                </div>

            </div>

            <!-- Right Column: Vertical Marquee Auto-Scroll (Group B) -->
            <div class="testimonials-marquee-col col-secondary">
                <div class="testimonials-marquee-track track-alt">
                    
                    <!-- Card 4: Sarah A. (Prescot LTD - X) -->
                    <div class="testimonial-social-card">
                        <div class="testimonial-card-header">
                            <div class="testimonial-author-box">
                                <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=150&auto=format&fit=crop&q=80" alt="Sarah A." class="testimonial-avatar-img">
                                <div class="testimonial-author-meta">
                                    <span class="testimonial-author-name">Sarah A.</span>
                                    <span class="testimonial-author-handle">@Prescot LTD</span>
                                </div>
                            </div>
                            <div class="testimonial-platform-badge badge-x" title="X (Twitter)">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                                </svg>
                            </div>
                        </div>
                        <p class="testimonial-text-content">
                            "The platform makes it easier to find students who are genuinely interested in growing with our team."
                        </p>
                    </div>

                    <!-- Card 5: Michael T. (TechStack - X) -->
                    <div class="testimonial-social-card">
                        <div class="testimonial-card-header">
                            <div class="testimonial-author-box">
                                <img src="https://images.unsplash.com/photo-1539571696357-5a69c17a67c6?w=150&auto=format&fit=crop&q=80" alt="Michael T." class="testimonial-avatar-img">
                                <div class="testimonial-author-meta">
                                    <span class="testimonial-author-name">Michael T.</span>
                                    <span class="testimonial-author-handle">@MichaelT_dev</span>
                                </div>
                            </div>
                            <div class="testimonial-platform-badge badge-x" title="X (Twitter)">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                                </svg>
                            </div>
                        </div>
                        <p class="testimonial-text-content">
                            "InternConnect made my internship search so much easier. I found opportunities that actually matched my skills and career goals, and I could track every application without feeling overwhelmed."
                        </p>
                    </div>

                    <!-- Card 6: Grace E. (Creative Minds - Facebook) -->
                    <div class="testimonial-social-card">
                        <div class="testimonial-card-header">
                            <div class="testimonial-author-box">
                                <img src="https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=150&auto=format&fit=crop&q=80" alt="Grace E." class="testimonial-avatar-img">
                                <div class="testimonial-author-meta">
                                    <span class="testimonial-author-name">Grace E.</span>
                                    <span class="testimonial-author-handle">@GraceE_tech</span>
                                </div>
                            </div>
                            <div class="testimonial-platform-badge badge-facebook" title="Facebook">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                </svg>
                            </div>
                        </div>
                        <p class="testimonial-text-content">
                            "Before InternConnect, I spent so much time jumping between different job boards. Now I can discover internships, apply quickly, and keep track of my progress in one place."
                        </p>
                    </div>

                    <!-- Duplicate Cards for Seamless Infinite Vertical Marquee Loop -->
                    <div class="testimonial-social-card" aria-hidden="true">
                        <div class="testimonial-card-header">
                            <div class="testimonial-author-box">
                                <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=150&auto=format&fit=crop&q=80" alt="Sarah A." class="testimonial-avatar-img">
                                <div class="testimonial-author-meta">
                                    <span class="testimonial-author-name">Sarah A.</span>
                                    <span class="testimonial-author-handle">@Prescot LTD</span>
                                </div>
                            </div>
                            <div class="testimonial-platform-badge badge-x">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                            </div>
                        </div>
                        <p class="testimonial-text-content">
                            "The platform makes it easier to find students who are genuinely interested in growing with our team."
                        </p>
                    </div>

                    <div class="testimonial-social-card" aria-hidden="true">
                        <div class="testimonial-card-header">
                            <div class="testimonial-author-box">
                                <img src="https://images.unsplash.com/photo-1539571696357-5a69c17a67c6?w=150&auto=format&fit=crop&q=80" alt="Michael T." class="testimonial-avatar-img">
                                <div class="testimonial-author-meta">
                                    <span class="testimonial-author-name">Michael T.</span>
                                    <span class="testimonial-author-handle">@MichaelT_dev</span>
                                </div>
                            </div>
                            <div class="testimonial-platform-badge badge-x">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                            </div>
                        </div>
                        <p class="testimonial-text-content">
                            "InternConnect made my internship search so much easier. I found opportunities that actually matched my skills and career goals, and I could track every application without feeling overwhelmed."
                        </p>
                    </div>

                    <div class="testimonial-social-card" aria-hidden="true">
                        <div class="testimonial-card-header">
                            <div class="testimonial-author-box">
                                <img src="https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=150&auto=format&fit=crop&q=80" alt="Grace E." class="testimonial-avatar-img">
                                <div class="testimonial-author-meta">
                                    <span class="testimonial-author-name">Grace E.</span>
                                    <span class="testimonial-author-handle">@GraceE_tech</span>
                                </div>
                            </div>
                            <div class="testimonial-platform-badge badge-facebook">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                            </div>
                        </div>
                        <p class="testimonial-text-content">
                            "Before InternConnect, I spent so much time jumping between different job boards. Now I can discover internships, apply quickly, and keep track of my progress in one place."
                        </p>
                    </div>

                </div>
            </div>

        </div>

        <!-- Section Bottom CTA using Standard Platform Button Style -->
        <div class="testimonials-cta-wrap">
            <a href="<?= url('/internships') ?>" class="testimonials-btn-cta">
                <span>View More</span>
                <svg style="width: 16px; height: 16px; flex-shrink: 0 !important;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                </svg>
            </a>
        </div>

    </div>
</section>

<script>
/**
 * Instagram/Snapchat Story-Style Video Carousel Alpine Component
 * - Pure autoplay & loop (No audio, no hover arrows, no click blocks)
 * - Ultra-fast lightweight Cloudinary video streams with audio completely stripped (ac_none)
 * - Transparent shield layer preventing IDM download popups
 */
function testimonialsStoryCarousel() {
    return {
        activeClip: 0,
        progress: 0,
        clips: <?= json_encode($videoClips, JSON_UNESCAPED_SLASHES) ?>,
        rafId: null,
        fallbackTimer: null,

        init() {
            this.$nextTick(() => {
                this.setupVideoEvents();
                this.playClip(0);
                this.startProgressTracker();
            });
        },

        setupVideoEvents() {
            this.clips.forEach((_, idx) => {
                const vid = document.getElementById('testimonial-vid-' + idx);
                if (!vid) return;

                vid.muted = true;

                vid.addEventListener('ended', () => {
                    this.nextClip();
                });
            });
        },

        startProgressTracker() {
            const track = () => {
                const currentVid = document.getElementById('testimonial-vid-' + this.activeClip);
                if (currentVid && currentVid.duration && !isNaN(currentVid.duration) && currentVid.duration > 0) {
                    this.progress = Math.min(100, (currentVid.currentTime / currentVid.duration) * 100);
                }
                this.rafId = requestAnimationFrame(track);
            };
            this.rafId = requestAnimationFrame(track);
        },

        playClip(index) {
            clearTimeout(this.fallbackTimer);
            const prevIndex = this.activeClip;
            this.activeClip = index;
            this.progress = 0;

            const newVid = document.getElementById('testimonial-vid-' + index);
            if (newVid) {
                newVid.muted = true;
                newVid.currentTime = 0;
                const playPromise = newVid.play();
                if (playPromise !== undefined) {
                    playPromise.catch(() => {
                        newVid.muted = true;
                        newVid.play().catch(() => {});
                    });
                }
            }

            // Smooth crossfade: let previous video finish fading out over 400ms before pausing
            if (prevIndex !== index) {
                const oldVid = document.getElementById('testimonial-vid-' + prevIndex);
                setTimeout(() => {
                    if (this.activeClip !== prevIndex && oldVid) {
                        oldVid.pause();
                        oldVid.currentTime = 0;
                    }
                }, 400);
            }

            // Fallback timer in case video metadata / ended event stalls
            this.fallbackTimer = setTimeout(() => {
                this.nextClip();
            }, 8500);
        },

        nextClip() {
            const nextIdx = (this.activeClip + 1) % this.clips.length;
            this.playClip(nextIdx);
        },

        destroy() {
            if (this.rafId) {
                cancelAnimationFrame(this.rafId);
            }
            if (this.fallbackTimer) {
                clearTimeout(this.fallbackTimer);
            }
        }
    };
}
</script>
