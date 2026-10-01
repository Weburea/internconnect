<?php

declare(strict_types=1);

/**
 * Component: Landing Page Call-to-Action / Newsletter Section
 * Full-bleed dark navy theme (#000623) across entire section width,
 * inner tech bento grid card with animated laser circuit lines,
 * floating company job badges (Google & Paystack), and animated doodle arrow.
 */
?>
<style>
/* ─── Full-Bleed Dark Navy CTA & Newsletter Section ─── */
.cta-section-wrap {
    background-color: #000623 !important;
    padding: 88px 0 104px 0;
    width: 100%;
    overflow: hidden;
    position: relative;
    box-sizing: border-box;
}

@media (min-width: 1024px) {
    .cta-section-wrap {
        padding: 108px 0 128px 0;
    }
}

.cta-container {
    max-width: 1240px;
    margin: 0 auto;
    padding: 0 24px;
    width: 100%;
    box-sizing: border-box;
    position: relative;
    z-index: 2;
}

/* ─── Inner Bento Grid Card (High-Tech Dark Radial Glow) ─── */
.cta-card {
    position: relative;
    background: radial-gradient(circle at 50% 30%, rgba(79, 72, 230, 0.18) 0%, rgba(0, 6, 35, 0.95) 75%), #000623;
    border: 1px solid rgba(255, 255, 255, 0.12);
    border-radius: 28px;
    overflow: hidden;
    padding: 72px 28px 80px 28px;
    text-align: center;
    box-shadow: 0 25px 60px -15px rgba(0, 0, 0, 0.8), inset 0 1px 0 rgba(255, 255, 255, 0.15);
    box-sizing: border-box;
    width: 100%;
}

@media (min-width: 768px) {
    .cta-card {
        padding: 84px 48px 92px 48px;
    }
}

/* ─── High-Tech Animated Bento Grid Lines & Laser Beams ─── */
.cta-bento-grid-lines {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    pointer-events: none;
    overflow: hidden;
    z-index: 1;
}

.cta-grid-svg {
    width: 100%;
    height: 100%;
    display: block;
}

/* Traveling Animated Laser Pulses */
.cta-laser-beam {
    stroke-linecap: round;
    will-change: stroke-dashoffset;
}

.cta-beam-h1 {
    stroke-dasharray: 240 960;
    animation: ctaLaserBeamH1 6s cubic-bezier(0.4, 0, 0.2, 1) infinite;
}

.cta-beam-h2 {
    stroke-dasharray: 220 980;
    animation: ctaLaserBeamH2 7s cubic-bezier(0.4, 0, 0.2, 1) infinite 1.5s;
}

.cta-beam-v1 {
    stroke-dasharray: 140 300;
    animation: ctaLaserBeamV 5.2s cubic-bezier(0.4, 0, 0.2, 1) infinite 0.6s;
}

.cta-beam-v2 {
    stroke-dasharray: 140 300;
    animation: ctaLaserBeamV 5.8s cubic-bezier(0.4, 0, 0.2, 1) infinite 2.4s;
}

@keyframes ctaLaserBeamH1 {
    0% { stroke-dashoffset: 1200; opacity: 0; }
    15% { opacity: 1; }
    85% { opacity: 1; }
    100% { stroke-dashoffset: -240; opacity: 0; }
}

@keyframes ctaLaserBeamH2 {
    0% { stroke-dashoffset: -220; opacity: 0; }
    15% { opacity: 1; }
    85% { opacity: 1; }
    100% { stroke-dashoffset: 1200; opacity: 0; }
}

@keyframes ctaLaserBeamV {
    0% { stroke-dashoffset: 440; opacity: 0; }
    20% { opacity: 1; }
    80% { opacity: 1; }
    100% { stroke-dashoffset: -140; opacity: 0; }
}

/* ─── Central Content Stage ─── */
.cta-inner-content {
    position: relative;
    z-index: 10;
    max-width: 640px;
    margin: 0 auto;
    display: flex;
    flex-direction: column;
    align-items: center;
}

.cta-pill {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 6px 14px;
    border-radius: 9999px;
    background-color: rgba(79, 72, 230, 0.16);
    color: #A5B4FC;
    border: 1px solid rgba(165, 180, 252, 0.25);
    font-size: 11.5px;
    font-weight: 700;
    letter-spacing: 0.05em;
    text-transform: uppercase;
    white-space: nowrap;
    margin-bottom: 20px;
    user-select: none;
}

.cta-pill-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background-color: #818CF8;
    box-shadow: 0 0 8px #818CF8;
}

.cta-headline {
    font-size: clamp(28px, 4vw, 46px);
    line-height: 1.15;
    font-weight: 800;
    color: #FFFFFF;
    letter-spacing: -0.025em;
    margin: 0 0 14px 0;
}

.cta-subtext {
    font-size: clamp(14px, 1.8vw, 17px);
    color: #94A3B8;
    line-height: 1.6;
    max-width: 520px;
    margin: 0 0 36px 0;
}

/* ─── Form Stage ─── */
.cta-form-stage {
    position: relative;
    width: 100%;
    max-width: 490px;
    margin: 0 auto;
}

.cta-form {
    width: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
    position: relative;
    z-index: 5;
}

.cta-input-row {
    display: flex;
    flex-direction: column;
    width: 100%;
    gap: 10px;
    background-color: rgba(255, 255, 255, 0.06);
    padding: 6px;
    border-radius: 12px;
    border: 1px solid rgba(255, 255, 255, 0.14);
    transition: border-color 0.2s ease, background-color 0.2s ease;
    box-sizing: border-box;
}

.cta-input-row:focus-within {
    border-color: rgba(129, 140, 248, 0.5);
    background-color: rgba(255, 255, 255, 0.09);
    box-shadow: 0 0 0 3px rgba(79, 72, 230, 0.2);
}

@media (min-width: 640px) {
    .cta-input-row {
        flex-direction: row;
        align-items: center;
        gap: 8px;
    }
}

.cta-input-field {
    flex: 1;
    background: transparent;
    border: none;
    color: #FFFFFF;
    font-size: 14.5px;
    padding: 11px 18px;
    outline: none;
    width: 100%;
    min-width: 0;
    box-sizing: border-box;
}

.cta-input-field::placeholder {
    color: #94A3B8;
}

/* InternConnect Standard Rounded Button (border-radius: 8px) */
.cta-subscribe-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 12px 28px;
    font-size: 14.5px;
    font-weight: 700;
    color: #000623;
    background-color: #FFFFFF;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.15s ease;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    white-space: nowrap;
    flex-shrink: 0;
}

.cta-subscribe-btn:hover {
    background-color: #F8FAFC;
    transform: translateY(-1px);
    box-shadow: 0 6px 18px rgba(0, 0, 0, 0.3);
}

.cta-disclaimer {
    font-size: 12px;
    color: #64748B;
    margin-top: 14px;
    text-align: center;
    line-height: 1.5;
}

/* ─── Premium Floating Job Badges with Stars (Right Side Placement) ─── */
.cta-job-badge {
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    min-width: 215px;
    padding: 14px 18px;
    border-radius: 16px;
    user-select: none;
    will-change: transform;
    box-sizing: border-box;
    z-index: 20;
    transition: transform 0.2s ease;
}

/* Top Row: 5 Stars + Brand Logo */
.cta-badge-top-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 10px;
    gap: 12px;
}

.cta-badge-stars {
    display: inline-flex;
    align-items: center;
    gap: 3px;
}

.cta-star-icon {
    width: 13.5px;
    height: 13.5px;
    flex-shrink: 0;
}

.cta-badge-logo {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 22px;
    height: 22px;
    flex-shrink: 0;
}

/* Bottom Row: Company Label & Bold Role */
.cta-badge-bottom-row {
    display: flex;
    flex-direction: column;
    text-align: left;
    gap: 3px;
}

.cta-badge-company {
    font-size: 11px;
    font-weight: 700;
    line-height: 1.2;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.cta-badge-title {
    font-size: 14px;
    font-weight: 700;
    color: #000623;
    line-height: 1.25;
    white-space: nowrap;
}

/* Desktop Positioning: Top-Right and Lower-Right */
@media (min-width: 900px) {
    .cta-badge-top-right {
        position: absolute;
        top: 42px;
        right: 48px;
        background-color: #FFFFFF;
        border: 1px solid rgba(255, 255, 255, 0.95);
        box-shadow: 0 16px 36px -6px rgba(0, 0, 0, 0.55), 0 4px 12px rgba(0, 0, 0, 0.25);
        animation: ctaFloatTopRight 4.8s ease-in-out infinite;
    }

    .cta-badge-top-right .cta-badge-company {
        color: #64748B;
    }

    .cta-badge-bottom-right {
        position: absolute;
        bottom: 48px;
        right: 48px;
        background-color: #F0FDF4;
        border: 1px solid #BBF7D0;
        box-shadow: 0 18px 38px -6px rgba(0, 0, 0, 0.55), 0 4px 12px rgba(0, 0, 0, 0.25);
        animation: ctaFloatBottomRight 5.2s ease-in-out infinite 0.8s;
    }

    .cta-badge-bottom-right .cta-badge-company {
        color: #059669;
    }
}

@media (min-width: 900px) and (max-width: 1140px) {
    .cta-badge-top-right {
        top: 28px;
        right: 24px;
        transform: scale(0.92);
    }
    .cta-badge-bottom-right {
        bottom: 28px;
        right: 24px;
        transform: scale(0.92);
    }
}

/* Floating Animations */
@keyframes ctaFloatTopRight {
    0%, 100% {
        transform: translateY(0px) rotate(-1deg);
    }
    50% {
        transform: translateY(-8px) rotate(-1deg);
    }
}

@keyframes ctaFloatBottomRight {
    0%, 100% {
        transform: translateY(0px) rotate(1.5deg);
    }
    50% {
        transform: translateY(-9px) rotate(1.5deg);
    }
}

/* ─── Animated Hand-Drawn Curved Doodle Arrow ─── */
.cta-curved-arrow {
    position: absolute;
    bottom: 96px;
    right: 295px;
    width: 68px;
    height: 52px;
    pointer-events: none;
    z-index: 15;
}

@media (max-width: 1140px) {
    .cta-curved-arrow {
        right: 245px;
        bottom: 86px;
    }
}

.cta-arrow-stem {
    stroke-dasharray: 140;
    stroke-dashoffset: 140;
    animation: ctaDrawStem 4s cubic-bezier(0.4, 0, 0.2, 1) infinite;
}

.cta-arrow-tip {
    opacity: 0;
    animation: ctaFadeTip 4s cubic-bezier(0.4, 0, 0.2, 1) infinite;
}

@keyframes ctaDrawStem {
    0% {
        stroke-dashoffset: 140;
        opacity: 0;
    }
    15% {
        opacity: 1;
    }
    55%, 85% {
        stroke-dashoffset: 0;
        opacity: 1;
    }
    100% {
        stroke-dashoffset: 0;
        opacity: 0;
    }
}

@keyframes ctaFadeTip {
    0%, 45% {
        opacity: 0;
        transform: scale(0.85);
    }
    55%, 85% {
        opacity: 1;
        transform: scale(1);
    }
    100% {
        opacity: 0;
        transform: scale(0.9);
    }
}

/* ─── Tablet & Mobile Adaptation ─── */
@media (max-width: 899px) {
    .cta-curved-arrow {
        display: none !important;
    }

    .cta-mobile-badges-row {
        display: flex;
        flex-direction: row;
        flex-wrap: wrap;
        justify-content: center;
        gap: 12px;
        margin-top: 32px;
        width: 100%;
        box-sizing: border-box;
    }

    .cta-job-badge {
        position: static !important;
        transform: none !important;
        animation: none !important;
        min-width: 180px;
        flex: 1 1 200px;
        max-width: 260px;
        padding: 12px 16px;
    }

    .cta-badge-top-right {
        background-color: #FFFFFF;
        border: 1px solid rgba(255, 255, 255, 0.95);
    }

    .cta-badge-bottom-right {
        background-color: #F0FDF4;
        border: 1px solid #BBF7D0;
    }
}
</style>

<section id="cta-section" class="cta-section-wrap">
    <div class="cta-container">
        <div class="cta-card">
            
            <!-- High-Tech Animated Bento Grid Background Lines -->
            <div class="cta-bento-grid-lines" aria-hidden="true">
                <svg class="cta-grid-svg" viewBox="0 0 1200 440" preserveAspectRatio="none" fill="none">
                    <defs>
                        <!-- Traveling Laser Beams -->
                        <linearGradient id="ctaLaserGradH1" x1="0%" y1="0%" x2="100%" y2="0%">
                            <stop offset="0%" stop-color="#4F48E6" stop-opacity="0"/>
                            <stop offset="50%" stop-color="#818CF8" stop-opacity="1"/>
                            <stop offset="100%" stop-color="#4F48E6" stop-opacity="0"/>
                        </linearGradient>
                        <linearGradient id="ctaLaserGradH2" x1="0%" y1="0%" x2="100%" y2="0%">
                            <stop offset="0%" stop-color="#38BDF8" stop-opacity="0"/>
                            <stop offset="50%" stop-color="#38BDF8" stop-opacity="1"/>
                            <stop offset="100%" stop-color="#38BDF8" stop-opacity="0"/>
                        </linearGradient>
                        <linearGradient id="ctaLaserGradV" x1="0%" y1="0%" x2="0%" y2="100%">
                            <stop offset="0%" stop-color="#4F48E6" stop-opacity="0"/>
                            <stop offset="50%" stop-color="#818CF8" stop-opacity="1"/>
                            <stop offset="100%" stop-color="#4F48E6" stop-opacity="0"/>
                        </linearGradient>
                    </defs>

                    <!-- Static Bento Grid Structural Tracks -->
                    <line x1="0" y1="110" x2="1200" y2="110" stroke="rgba(255, 255, 255, 0.06)" stroke-width="1"/>
                    <line x1="0" y1="330" x2="1200" y2="330" stroke="rgba(255, 255, 255, 0.06)" stroke-width="1"/>
                    <line x1="280" y1="0" x2="280" y2="440" stroke="rgba(255, 255, 255, 0.06)" stroke-width="1"/>
                    <line x1="920" y1="0" x2="920" y2="440" stroke="rgba(255, 255, 255, 0.06)" stroke-width="1"/>

                    <!-- Animated Traveling Laser Beams on Bento Lines -->
                    <line class="cta-laser-beam cta-beam-h1" x1="0" y1="110" x2="1200" y2="110" stroke="url(#ctaLaserGradH1)" stroke-width="2"/>
                    <line class="cta-laser-beam cta-beam-h2" x1="0" y1="330" x2="1200" y2="330" stroke="url(#ctaLaserGradH2)" stroke-width="2"/>
                    <line class="cta-laser-beam cta-beam-v1" x1="280" y1="0" x2="280" y2="440" stroke="url(#ctaLaserGradV)" stroke-width="2"/>
                    <line class="cta-laser-beam cta-beam-v2" x1="920" y1="0" x2="920" y2="440" stroke="url(#ctaLaserGradV)" stroke-width="2"/>

                    <!-- Grid Intersection Tech Crosshairs (+) -->
                    <path d="M 274 110 L 286 110 M 280 104 L 280 116" stroke="#4F48E6" stroke-width="1.5" opacity="0.6"/>
                    <path d="M 914 110 L 926 110 M 920 104 L 920 116" stroke="#4F48E6" stroke-width="1.5" opacity="0.6"/>
                    <path d="M 274 330 L 286 330 M 280 324 L 280 336" stroke="#4F48E6" stroke-width="1.5" opacity="0.6"/>
                    <path d="M 914 330 L 926 330 M 920 324 L 920 336" stroke="#4F48E6" stroke-width="1.5" opacity="0.6"/>
                </svg>
            </div>

            <!-- Desktop: Badge 1 (Google) at Top-Right -->
            <div class="cta-job-badge cta-badge-top-right" aria-hidden="true">
                <div class="cta-badge-top-row">
                    <div class="cta-badge-stars">
                        <?php for ($i = 0; $i < 5; $i++): ?>
                            <svg class="cta-star-icon" viewBox="0 0 20 20" fill="#F59E0B">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        <?php endfor; ?>
                    </div>
                    <div class="cta-badge-logo">
                        <svg width="22" height="22" viewBox="0 0 24 24">
                            <path fill="#4285F4" d="M23.745 12.27c0-.7-.06-1.4-.19-2.07H12v4.51h6.6c-.29 1.52-1.14 2.8-2.4 3.68v3.05h3.88c2.27-2.09 3.665-5.17 3.665-9.17z"/>
                            <path fill="#34A853" d="M12 24c3.24 0 5.95-1.08 7.93-2.91l-3.88-3.05c-1.08.72-2.45 1.16-4.05 1.16-3.12 0-5.77-2.1-6.72-4.93H1.25v3.15C3.26 21.36 7.33 24 12 24z"/>
                            <path fill="#FBBC05" d="M5.28 14.27c-.25-.72-.38-1.49-.38-2.27s.13-1.55.38-2.27V6.58H1.25C.45 8.18 0 10.03 0 12s.45 3.82 1.25 5.42l4.03-3.15z"/>
                            <path fill="#EA4335" d="M12 4.75c1.77 0 3.35.61 4.6 1.8l3.42-3.42C17.95 1.19 15.24 0 12 0 7.33 0 3.26 2.64 1.25 6.58l4.03 3.15c.95-2.83 3.6-4.98 6.72-4.98z"/>
                        </svg>
                    </div>
                </div>
                <div class="cta-badge-bottom-row">
                    <span class="cta-badge-company">Google</span>
                    <span class="cta-badge-title">Sr. UI/UX Designer</span>
                </div>
            </div>

            <!-- Desktop: Badge 2 (Paystack) at Lower-Right -->
            <div class="cta-job-badge cta-badge-bottom-right" aria-hidden="true">
                <div class="cta-badge-top-row">
                    <div class="cta-badge-stars">
                        <?php for ($i = 0; $i < 5; $i++): ?>
                            <svg class="cta-star-icon" viewBox="0 0 20 20" fill="#10B981">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        <?php endfor; ?>
                    </div>
                    <div class="cta-badge-logo">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none">
                            <rect width="24" height="24" rx="6" fill="#00C3F7" fill-opacity="0.16"/>
                            <path d="M5 8h14M5 12h10M5 16h14" stroke="#00C3F7" stroke-width="2.4" stroke-linecap="round"/>
                        </svg>
                    </div>
                </div>
                <div class="cta-badge-bottom-row">
                    <span class="cta-badge-company">Paystack</span>
                    <span class="cta-badge-title">Senior Motion Designer</span>
                </div>
            </div>

            <!-- Animated Curved Hand-Drawn Doodle Arrow pointing toward the Subscribe CTA -->
            <div class="cta-curved-arrow" aria-hidden="true">
                <svg width="68" height="52" viewBox="0 0 68 52" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path class="cta-arrow-stem" d="M 8 16 C 16 6, 32 4, 35 15 C 37 25, 25 30, 20 23 C 15 15, 26 7, 40 12 C 49 15, 56 26, 61 40" stroke="#4F48E6" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"/>
                    <path class="cta-arrow-tip" d="M 50 38 L 62 42 L 62 30" stroke="#4F48E6" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
            
            <!-- Center Content (Pill, Headline, Subtext, Form — 100% Unobstructed) -->
            <div class="cta-inner-content">
                <div class="cta-pill">
                    <span class="cta-pill-dot" aria-hidden="true"></span>
                    <span>Weekly Internship Dispatch</span>
                </div>

                <!-- Headline -->
                <h2 class="cta-headline">
                    Ready to curate your future?
                </h2>
                
                <!-- Subtext -->
                <p class="cta-subtext">
                    Join 50,000+ applicants receiving weekly curated internship listings
                </p>

                <!-- Subscription Form Stage -->
                <div class="cta-form-stage">
                    <form class="cta-form" onsubmit="event.preventDefault(); this.querySelector('button').textContent = 'Subscribed';">
                        <div class="cta-input-row">
                            <input type="email" placeholder="you@internconnect.com" required class="cta-input-field" />
                            <button type="submit" class="cta-subscribe-btn">
                                Subscribe
                            </button>
                        </div>
                        
                        <!-- Anti-spam disclaimer -->
                        <p class="cta-disclaimer">
                            No spam. Only high end career opportunities. Unsubscribe anytime.
                        </p>
                    </form>
                </div>

                <!-- Mobile Badges Row -->
                <div class="cta-mobile-badges-row lg:hidden">
                    <div class="cta-job-badge cta-badge-top-right">
                        <div class="cta-badge-top-row">
                            <div class="cta-badge-stars">
                                <?php for ($i = 0; $i < 5; $i++): ?>
                                    <svg class="cta-star-icon" viewBox="0 0 20 20" fill="#F59E0B"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                <?php endfor; ?>
                            </div>
                            <div class="cta-badge-logo">
                                <svg width="22" height="22" viewBox="0 0 24 24">
                                    <path fill="#4285F4" d="M23.745 12.27c0-.7-.06-1.4-.19-2.07H12v4.51h6.6c-.29 1.52-1.14 2.8-2.4 3.68v3.05h3.88c2.27-2.09 3.665-5.17 3.665-9.17z"/>
                                    <path fill="#34A853" d="M12 24c3.24 0 5.95-1.08 7.93-2.91l-3.88-3.05c-1.08.72-2.45 1.16-4.05 1.16-3.12 0-5.77-2.1-6.72-4.93H1.25v3.15C3.26 21.36 7.33 24 12 24z"/>
                                    <path fill="#FBBC05" d="M5.28 14.27c-.25-.72-.38-1.49-.38-2.27s.13-1.55.38-2.27V6.58H1.25C.45 8.18 0 10.03 0 12s.45 3.82 1.25 5.42l4.03-3.15z"/>
                                    <path fill="#EA4335" d="M12 4.75c1.77 0 3.35.61 4.6 1.8l3.42-3.42C17.95 1.19 15.24 0 12 0 7.33 0 3.26 2.64 1.25 6.58l4.03 3.15c.95-2.83 3.6-4.98 6.72-4.98z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="cta-badge-bottom-row">
                            <span class="cta-badge-company">Google</span>
                            <span class="cta-badge-title">Sr. UI/UX Designer</span>
                        </div>
                    </div>

                    <div class="cta-job-badge cta-badge-bottom-right">
                        <div class="cta-badge-top-row">
                            <div class="cta-badge-stars">
                                <?php for ($i = 0; $i < 5; $i++): ?>
                                    <svg class="cta-star-icon" viewBox="0 0 20 20" fill="#10B981"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                <?php endfor; ?>
                            </div>
                            <div class="cta-badge-logo">
                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none">
                                    <rect width="24" height="24" rx="6" fill="#00C3F7" fill-opacity="0.16"/>
                                    <path d="M5 8h14M5 12h10M5 16h14" stroke="#00C3F7" stroke-width="2.4" stroke-linecap="round"/>
                                </svg>
                            </div>
                        </div>
                        <div class="cta-badge-bottom-row">
                            <span class="cta-badge-company">Paystack</span>
                            <span class="cta-badge-title">Senior Motion Designer</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>
