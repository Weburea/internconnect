<?php

declare(strict_types=1);

/**
 * Component: Landing Page Call-to-Action / Newsletter Section
 * Dark Navy theme (#000623) with floating animated job badges & curved doodle arrow
 */
?>
<style>
/* ─── CTA & Newsletter Section ─── */
.cta-section-wrap {
    padding: 60px 0 90px 0;
    background-color: #FFFFFF;
    width: 100%;
    box-sizing: border-box;
}

@media (min-width: 1024px) {
    .cta-section-wrap {
        padding: 80px 0 110px 0;
    }
}

.cta-container {
    max-width: 1240px;
    margin: 0 auto;
    padding: 0 20px;
    width: 100%;
    box-sizing: border-box;
}

/* Dark Navy Card Container */
.cta-card {
    position: relative;
    background-color: #000623;
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 28px;
    overflow: hidden;
    padding: 64px 24px 68px 24px;
    text-align: center;
    box-shadow: 0 24px 60px -12px rgba(0, 6, 35, 0.45);
    box-sizing: border-box;
    width: 100%;
}

@media (min-width: 768px) {
    .cta-card {
        padding: 72px 48px 80px 48px;
    }
}

/* Subtle Cross Line Pattern */
.cta-bg-lines {
    position: absolute;
    inset: 0;
    pointer-events: none;
    opacity: 0.18;
}

.cta-inner-content {
    position: relative;
    z-index: 10;
    max-width: 760px;
    margin: 0 auto;
    display: flex;
    flex-direction: column;
    align-items: center;
}

/* Typography */
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
    max-width: 560px;
    margin: 0 0 44px 0;
}

/* Form Stage with Floating Elements */
.cta-form-stage {
    position: relative;
    width: 100%;
    max-width: 520px;
    margin: 0 auto;
}

/* ─── Floating Badges Cluster (Desktop / Tablet) ─── */
.cta-floating-cluster {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    pointer-events: none;
    z-index: 20;
}

/* Job Badges Styling */
.cta-job-badge {
    position: absolute;
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 9px 16px;
    border-radius: 14px;
    border: 1px solid rgba(255, 255, 255, 0.85);
    pointer-events: auto;
    cursor: default;
    user-select: none;
    will-change: transform;
}

.cta-badge-info {
    display: flex;
    flex-direction: column;
    text-align: left;
    gap: 2px;
}

.cta-badge-company {
    font-size: 11px;
    font-weight: 600;
    color: #64748B;
    line-height: 1.2;
    letter-spacing: 0.01em;
}

.cta-badge-title {
    font-size: 13.5px;
    font-weight: 700;
    color: #000623;
    line-height: 1.2;
    white-space: nowrap;
}

.cta-badge-logo {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 24px;
    height: 24px;
    flex-shrink: 0;
}

/* Badge 1 (Google — Higher, slightly left) */
.cta-badge-one {
    top: -56px;
    right: 70px;
    background-color: #FFFFFF;
    box-shadow: 0 12px 28px -4px rgba(0, 0, 0, 0.38), 0 3px 8px rgba(0, 0, 0, 0.12);
    animation: ctaFloatOne 4.5s ease-in-out infinite;
}

/* Badge 2 (Paystack — Lower, slightly right, soft mint tint) */
.cta-badge-two {
    top: -18px;
    right: -24px;
    background-color: #F0FDF9;
    border-color: #D1FAE5;
    box-shadow: 0 14px 32px -4px rgba(0, 0, 0, 0.42), 0 4px 10px rgba(0, 0, 0, 0.14);
    animation: ctaFloatTwo 5s ease-in-out infinite 0.7s;
}

/* Slow, Continuous Floating Animations */
@keyframes ctaFloatOne {
    0%, 100% {
        transform: translateY(0px) rotate(-1.5deg);
    }
    50% {
        transform: translateY(-8px) rotate(-1.5deg);
    }
}

@keyframes ctaFloatTwo {
    0%, 100% {
        transform: translateY(0px) rotate(2deg);
    }
    50% {
        transform: translateY(-9px) rotate(2deg);
    }
}

/* ─── Animated Hand-Drawn Curved Doodle Arrow ─── */
.cta-curved-arrow {
    position: absolute;
    top: -50px;
    right: 250px;
    width: 72px;
    height: 58px;
    pointer-events: none;
    z-index: 15;
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

/* ─── Subscription Input + Button Row ─── */
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
    padding: 7px;
    border-radius: 14px;
    border: 1px solid rgba(255, 255, 255, 0.12);
    transition: border-color 0.2s ease, background-color 0.2s ease;
    box-sizing: border-box;
}

.cta-input-row:focus-within {
    border-color: rgba(255, 255, 255, 0.28);
    background-color: rgba(255, 255, 255, 0.08);
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
    border-radius: 10px;
    cursor: pointer;
    transition: all 0.15s ease;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    white-space: nowrap;
    flex-shrink: 0;
}

.cta-subscribe-btn:hover {
    background-color: #F8FAFC;
    transform: translateY(-1px);
    box-shadow: 0 6px 16px rgba(0, 0, 0, 0.25);
}

.cta-disclaimer {
    font-size: 12px;
    color: #64748B;
    margin-top: 14px;
    text-align: center;
    line-height: 1.5;
}

/* ─── Responsive Mobile Adjustments (Strict Zero Horizontal Overflow) ─── */
@media (max-width: 768px) {
    .cta-subtext {
        margin-bottom: 28px;
    }
    
    .cta-badge-one {
        right: 40px;
        top: -50px;
        padding: 7px 12px;
    }
    
    .cta-badge-two {
        right: 0px;
        top: -15px;
        padding: 7px 12px;
    }
    
    .cta-curved-arrow {
        right: 190px;
        top: -44px;
        width: 60px;
        height: 48px;
    }
}

@media (max-width: 640px) {
    .cta-floating-cluster {
        position: static;
        display: flex;
        justify-content: center;
        gap: 8px;
        margin-bottom: 18px;
        width: 100%;
        pointer-events: auto;
    }

    .cta-curved-arrow {
        display: none !important;
    }

    .cta-job-badge {
        position: static !important;
        animation: none !important;
        transform: none !important;
        padding: 6px 10px;
        gap: 8px;
        border-radius: 10px;
    }

    .cta-badge-company {
        font-size: 10px;
    }

    .cta-badge-title {
        font-size: 11.5px;
    }

    .cta-badge-logo {
        width: 18px;
        height: 18px;
    }
}
</style>

<section id="cta-section" class="cta-section-wrap">
    <div class="cta-container">
        <div class="cta-card">
            
            <!-- Subtle Diagonal Cross Line Pattern -->
            <div class="cta-bg-lines" aria-hidden="true">
                <svg class="w-full h-full" fill="none" viewBox="0 0 100 100" preserveAspectRatio="none">
                    <path d="M0 0 L100 100 M100 0 L0 100" stroke="#4F48E6" stroke-width="0.45"></path>
                </svg>
            </div>
            
            <div class="cta-inner-content">
                <!-- Headline -->
                <h2 class="cta-headline">
                    Ready to curate your future?
                </h2>
                
                <!-- Subtext -->
                <p class="cta-subtext">
                    Join 50,000+ applicants receiving weekly curated internship listings
                </p>

                <!-- Subscription Form Stage with Floating Elements -->
                <div class="cta-form-stage">
                    
                    <!-- Floating Job Badges & Animated Curved Arrow -->
                    <div class="cta-floating-cluster" aria-hidden="true">
                        
                        <!-- Animated Hand-Drawn Curved Doodle Arrow (recolored in brand #4F48E6) -->
                        <div class="cta-curved-arrow">
                            <svg width="68" height="54" viewBox="0 0 68 54" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path class="cta-arrow-stem" d="M 8 16 C 16 6, 32 4, 35 15 C 37 25, 25 30, 20 23 C 15 15, 26 7, 40 12 C 49 15, 56 26, 61 40" stroke="#4F48E6" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"/>
                                <path class="cta-arrow-tip" d="M 50 38 L 62 42 L 62 30" stroke="#4F48E6" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>

                        <!-- Badge 1: Google — Sr. UI/UX Designer -->
                        <div class="cta-job-badge cta-badge-one">
                            <div class="cta-badge-info">
                                <span class="cta-badge-company">Google</span>
                                <span class="cta-badge-title">Sr. UI/UX Designer</span>
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

                        <!-- Badge 2: Paystack — Senior Motion Designer -->
                        <div class="cta-job-badge cta-badge-two">
                            <div class="cta-badge-info">
                                <span class="cta-badge-company">Paystack</span>
                                <span class="cta-badge-title">Senior Motion Designer</span>
                            </div>
                            <div class="cta-badge-logo">
                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none">
                                    <rect width="24" height="24" rx="6" fill="#00C3F7" fill-opacity="0.16"/>
                                    <path d="M5 8h14M5 12h10M5 16h14" stroke="#00C3F7" stroke-width="2.4" stroke-linecap="round"/>
                                </svg>
                            </div>
                        </div>

                    </div>

                    <!-- Subscription Form -->
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

            </div>
        </div>
    </div>
</section>
