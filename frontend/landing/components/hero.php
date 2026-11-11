<?php

declare(strict_types=1);

/**
 * Component: Landing Page Hero Section
 * Fully robust, self-contained layout matching the reference mockup.
 */
?>
<style>
/* ─── InternConnect Premium Purple Custom Scrollbar ─── */
::-webkit-scrollbar {
    width: 9px;
    height: 9px;
}
::-webkit-scrollbar-track {
    background: #F8FAFC;
}
::-webkit-scrollbar-thumb {
    background: #4F48E6;
    border-radius: 9999px;
    border: 2px solid #F8FAFC;
    transition: background 0.2s ease;
}
::-webkit-scrollbar-thumb:hover {
    background: #3730A3;
}
html, body {
    scrollbar-width: thin;
    scrollbar-color: #4F48E6 #F8FAFC;
}

/* ─── Hero Container & Flex Grid System ─── */
.hero-section-wrap {
    position: relative;
    background-color: #FFFFFF;
    padding: 32px 0 48px 0;
    width: 100%;
    overflow: hidden;
    box-sizing: border-box;
    border-bottom: none !important;
}

@media (min-width: 768px) {
    .hero-section-wrap {
        padding: 44px 0 64px 0;
    }
}

.hero-inner-container {
    max-width: 1280px;
    margin: 0 auto;
    padding: 0 24px;
    width: 100%;
    box-sizing: border-box;
    position: relative;
    z-index: 10;
}

.hero-flex-layout {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: flex-start;
    gap: 36px;
    width: 100%;
    box-sizing: border-box;
}

@media (min-width: 1024px) {
    .hero-flex-layout {
        flex-direction: row;
        align-items: center;
        justify-content: flex-start;
        gap: 20px;
    }
    
    .hero-text-column {
        flex: 0 0 540px;
        max-width: 560px;
        min-width: 0;
        width: 100%;
        box-sizing: border-box;
    }
    
    /* Graphic column placed closely to the left side alongside the text */
    .hero-graphic-column {
        flex: 1 1 540px;
        max-width: 650px;
        width: 100%;
        display: flex;
        justify-content: flex-start;
        align-items: center;
        box-sizing: border-box;
        margin-left: 0;
    }
}

@media (min-width: 1280px) {
    .hero-inner-container {
        max-width: 1320px;
    }

    .hero-flex-layout {
        gap: 24px;
    }

    .hero-text-column {
        flex: 0 0 560px;
        max-width: 580px;
    }

    .hero-graphic-column {
        flex: 1 1 600px;
        max-width: 680px;
    }
}

@media (max-width: 1023px) {
    .hero-text-column {
        width: 100%;
        max-width: 640px;
        box-sizing: border-box;
    }
    
    /* Strictly hide the right-side graphic banner on mobile/tablet per user instruction */
    .hero-graphic-column {
        display: none !important;
    }
}

.hero-content-group {
    display: flex;
    flex-direction: column;
    gap: 22px;
    width: 100%;
    box-sizing: border-box;
}

/* ─── Badge (Platform Primary Colors) ─── */
.hero-badge-pill {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 6px 14px;
    border-radius: 9999px;
    background-color: #EEF2FF;
    color: #4F48E6;
    border: 1px solid #C7D2FE;
    font-size: 12.5px;
    font-weight: 600;
    letter-spacing: 0.01em;
    white-space: nowrap;
    width: fit-content;
}

.hero-pulse-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background-color: #4F48E6;
    animation: heroDotPulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

@keyframes heroDotPulse {
    0%, 100% { opacity: 1; transform: scale(1); }
    50% { opacity: 0.4; transform: scale(0.85); }
}

/* ─── Main Headline ─── */
.hero-headline-title {
    font-size: 38px;
    line-height: 1.15;
    font-weight: 800;
    color: #000623;
    letter-spacing: -0.025em;
    margin: 0;
}

@media (min-width: 640px) {
    .hero-headline-title {
        font-size: 48px;
    }
}

@media (min-width: 1024px) {
    .hero-headline-title {
        font-size: 56px;
        line-height: 1.12;
    }
}

.hero-dream-accent {
    color: #4F48E6;
}

/* ─── Brand Selection Box & Animated Color-Synchronized Spinning Cursor ─── */
.hero-brand-highlight {
    position: relative;
    display: inline-flex;
    align-items: center;
    padding: 2px 14px 4px 12px;
    border: 1.5px solid #4F48E6;
    border-radius: 8px;
    background: rgba(79, 72, 230, 0.04);
    color: #4F48E6;
    box-shadow: 0 0 0 1px rgba(79, 72, 230, 0.12);
    vertical-align: middle;
    margin-left: 2px;
    animation: heroBoxColorCircuit 8s cubic-bezier(0.4, 0, 0.2, 1) infinite;
}

.hero-brand-highlight .hero-handle {
    position: absolute;
    width: 6px;
    height: 6px;
    background-color: #FFFFFF;
    border: 1.5px solid #4F48E6;
    border-radius: 1px;
    z-index: 2;
    transition: all 0.3s ease;
    animation: heroHandleColorCircuit 8s cubic-bezier(0.4, 0, 0.2, 1) infinite;
}

.hero-handle-tl { top: -4px; left: -4px; }
.hero-handle-tr { top: -4px; right: -4px; }
.hero-handle-bl { bottom: -4px; left: -4px; }
.hero-handle-br { bottom: -4px; right: -4px; }

/* Large sharp cursor pointer with color-synchronized fill and glow, spinning & hovering across 4 corners */
.hero-cursor-pointer {
    position: absolute;
    width: 30px;
    height: 30px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    pointer-events: none;
    z-index: 10;
    animation: heroCursorCircuit 8s cubic-bezier(0.4, 0, 0.2, 1) infinite;
    transform-origin: 20% 20%;
}

.hero-cursor-svg {
    width: 30px;
    height: 30px;
    color: #4F48E6;
    animation: heroCursorColorCircuit 8s cubic-bezier(0.4, 0, 0.2, 1) infinite;
}

.hero-cursor-path {
    fill: currentColor;
    stroke: #FFFFFF;
    stroke-width: 1.6;
    stroke-linejoin: round;
}

@keyframes heroCursorCircuit {
    /* Corner 1: Bottom-Right (Resting position like Figma) */
    0%, 100% {
        top: calc(100% - 6px);
        left: calc(100% - 6px);
        transform: rotate(0deg) scale(1);
    }
    8% {
        top: calc(100% - 6px);
        left: calc(100% - 6px);
        transform: rotate(-12deg) scale(0.86);
    }
    14% {
        top: calc(100% - 6px);
        left: calc(100% - 6px);
        transform: rotate(0deg) scale(1);
    }
    /* Spin & Glide to Bottom-Left */
    20% {
        transform: rotate(180deg) scale(1.12);
    }
    28% {
        top: calc(100% - 6px);
        left: -14px;
        transform: rotate(360deg) scale(1);
    }
    33% {
        top: calc(100% - 6px);
        left: -14px;
        transform: rotate(348deg) scale(0.86);
    }
    38% {
        top: calc(100% - 6px);
        left: -14px;
        transform: rotate(360deg) scale(1);
    }
    /* Spin & Glide to Top-Left */
    44% {
        transform: rotate(540deg) scale(1.12);
    }
    50% {
        top: -18px;
        left: -14px;
        transform: rotate(720deg) scale(1);
    }
    55% {
        top: -18px;
        left: -14px;
        transform: rotate(708deg) scale(0.86);
    }
    60% {
        top: -18px;
        left: -14px;
        transform: rotate(720deg) scale(1);
    }
    /* Spin & Glide to Top-Right */
    66% {
        transform: rotate(900deg) scale(1.12);
    }
    72% {
        top: -18px;
        left: calc(100% - 6px);
        transform: rotate(1080deg) scale(1);
    }
    77% {
        top: -18px;
        left: calc(100% - 6px);
        transform: rotate(1068deg) scale(0.86);
    }
    82% {
        top: -18px;
        left: calc(100% - 6px);
        transform: rotate(1080deg) scale(1);
    }
    /* Spin & Glide back to Bottom-Right */
    87% {
        transform: rotate(1260deg) scale(1.12);
    }
    92% {
        top: calc(100% - 6px);
        left: calc(100% - 6px);
        transform: rotate(1440deg) scale(1);
    }
}

@keyframes heroCursorColorCircuit {
    /* Corner 1: Bottom-Right -> Brand Blue */
    0%, 16% {
        color: #4F48E6;
        filter: drop-shadow(0 4px 10px rgba(79, 72, 230, 0.5));
    }
    /* Corner 2: Bottom-Left -> Vibrant Orange */
    26%, 40% {
        color: #FF5722;
        filter: drop-shadow(0 4px 10px rgba(255, 87, 34, 0.5));
    }
    /* Corner 3: Top-Left -> Brand Blue */
    48%, 62% {
        color: #4F48E6;
        filter: drop-shadow(0 4px 10px rgba(79, 72, 230, 0.5));
    }
    /* Corner 4: Top-Right -> Vibrant Orange */
    70%, 84% {
        color: #FF5722;
        filter: drop-shadow(0 4px 10px rgba(255, 87, 34, 0.5));
    }
    /* Corner 1: Bottom-Right -> Brand Blue */
    92%, 100% {
        color: #4F48E6;
        filter: drop-shadow(0 4px 10px rgba(79, 72, 230, 0.5));
    }
}

@keyframes heroBoxColorCircuit {
    /* Corner 1: Blue */
    0%, 16% {
        border-color: #4F48E6;
        background: rgba(79, 72, 230, 0.05);
        box-shadow: 0 0 0 2px rgba(79, 72, 230, 0.18), 0 8px 20px -4px rgba(79, 72, 230, 0.14);
    }
    /* Corner 2: Orange */
    26%, 40% {
        border-color: #FF5722;
        background: rgba(255, 87, 34, 0.05);
        box-shadow: 0 0 0 2px rgba(255, 87, 34, 0.18), 0 8px 20px -4px rgba(255, 87, 34, 0.14);
    }
    /* Corner 3: Blue */
    48%, 62% {
        border-color: #4F48E6;
        background: rgba(79, 72, 230, 0.05);
        box-shadow: 0 0 0 2px rgba(79, 72, 230, 0.18), 0 8px 20px -4px rgba(79, 72, 230, 0.14);
    }
    /* Corner 4: Orange */
    70%, 84% {
        border-color: #FF5722;
        background: rgba(255, 87, 34, 0.05);
        box-shadow: 0 0 0 2px rgba(255, 87, 34, 0.18), 0 8px 20px -4px rgba(255, 87, 34, 0.14);
    }
    /* Corner 1: Blue */
    92%, 100% {
        border-color: #4F48E6;
        background: rgba(79, 72, 230, 0.05);
        box-shadow: 0 0 0 2px rgba(79, 72, 230, 0.18), 0 8px 20px -4px rgba(79, 72, 230, 0.14);
    }
}

@keyframes heroHandleColorCircuit {
    0%, 16% {
        border-color: #4F48E6;
    }
    26%, 40% {
        border-color: #FF5722;
    }
    48%, 62% {
        border-color: #4F48E6;
    }
    70%, 84% {
        border-color: #FF5722;
    }
    92%, 100% {
        border-color: #4F48E6;
    }
}

/* ─── Subtext (Max 2 lines) ─── */
.hero-subtext-para {
    font-size: 16px;
    line-height: 1.55;
    color: #475569;
    max-width: 520px;
    margin: 0;
    font-weight: 400;
}

@media (min-width: 768px) {
    .hero-subtext-para {
        font-size: 16.5px;
    }
}

/* ─── Metric Counter Pills (All White Icons, Strictly Same-Line on Mobile) ─── */
.hero-counters-row {
    display: flex;
    flex-wrap: nowrap;
    align-items: center;
    gap: 12px;
    width: 100%;
    margin: 4px 0;
}

.hero-counter-pill {
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    gap: 10px !important;
    padding: 10px 18px !important;
    border-radius: 9999px !important;
    background-color: #000623 !important;
    color: #FFFFFF !important;
    font-size: 13.5px !important;
    font-weight: 600 !important;
    letter-spacing: 0.01em !important;
    white-space: nowrap !important;
    flex-shrink: 0 !important;
    box-shadow: 0 2px 8px rgba(0, 6, 35, 0.15) !important;
}

.hero-counter-icon {
    width: 16px;
    height: 16px;
    color: #FFFFFF !important;
    stroke: #FFFFFF !important;
    flex-shrink: 0 !important;
}

@media (max-width: 640px) {
    .hero-counters-row {
        gap: 8px;
        flex-wrap: nowrap !important;
        overflow-x: auto;
        scrollbar-width: none;
    }
    .hero-counters-row::-webkit-scrollbar {
        display: none;
    }
    .hero-counter-pill {
        padding: 8px 12px !important;
        font-size: 12px !important;
        gap: 6px !important;
    }
    .hero-counter-icon {
        width: 14px !important;
        height: 14px !important;
    }
}

@media (max-width: 380px) {
    .hero-counters-row {
        gap: 6px;
    }
    .hero-counter-pill {
        padding: 7px 9px !important;
        font-size: 11px !important;
        gap: 5px !important;
    }
    .hero-counter-icon {
        width: 13px !important;
        height: 13px !important;
    }
}

/* ─── Floating Split Hero Search Bar ─── */
.hero-search-box {
    display: flex;
    align-items: center;
    background: #FFFFFF;
    border: 1px solid #E2E8F0;
    border-radius: 9999px;
    padding: 6px 8px 6px 20px;
    box-shadow: 0 14px 34px -4px rgba(0, 6, 35, 0.08), 0 4px 12px -2px rgba(0, 6, 35, 0.04);
    max-width: 580px;
    width: 100%;
    transition: all 0.25s ease;
    box-sizing: border-box;
}

.hero-search-box:focus-within {
    border-color: #4F48E6;
    box-shadow: 0 16px 36px -4px rgba(79, 72, 230, 0.16), 0 0 0 3px rgba(79, 72, 230, 0.1);
}

.hero-search-field {
    display: flex;
    align-items: center;
    gap: 10px;
    flex: 1;
    min-width: 0;
    padding: 6px 6px;
}

.hero-search-icon {
    width: 18px;
    height: 18px;
    color: #94A3B8;
    flex-shrink: 0;
}

.hero-search-input {
    width: 100%;
    border: none;
    outline: none;
    background: transparent;
    font-size: 14px;
    color: #0F172A;
    min-width: 0;
    font-family: inherit;
}

.hero-search-input::placeholder {
    color: #94A3B8;
}

.hero-search-divider {
    width: 1px;
    height: 28px;
    background: #E2E8F0;
    flex-shrink: 0;
    margin: 0 4px;
}

.hero-search-btn {
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    gap: 8px !important;
    padding: 11px 26px !important;
    border-radius: 9999px !important;
    background-color: #4F48E6 !important;
    color: #FFFFFF !important;
    font-weight: 600 !important;
    font-size: 14px !important;
    border: none !important;
    cursor: pointer !important;
    transition: all 0.2s ease !important;
    white-space: nowrap !important;
    flex-shrink: 0 !important;
    box-shadow: 0 4px 14px rgba(79, 72, 230, 0.35) !important;
}

.hero-search-btn:hover {
    background-color: #4338CA !important;
    box-shadow: 0 6px 18px rgba(79, 72, 230, 0.45) !important;
    transform: translateY(-1px);
}

@media (max-width: 640px) {
    .hero-search-box {
        flex-direction: column;
        border-radius: 20px;
        padding: 12px 14px;
        gap: 8px;
    }
    .hero-search-divider {
        display: none;
    }
    .hero-search-field {
        width: 100%;
        padding: 6px 4px;
        border-bottom: 1px solid #F1F5F9;
    }
    .hero-search-btn {
        width: 100% !important;
        margin-top: 4px;
        padding: 12px 20px !important;
    }
}

/* ─── Consolidated Right Banner Image (Snug & Premium Beside Text Column) ─── */
.hero-single-banner-wrap {
    position: relative;
    width: 100%;
    max-width: 680px;
    display: flex;
    align-items: center;
    justify-content: flex-start;
}

.hero-single-banner-img {
    width: 100%;
    height: auto;
    max-height: 520px;
    object-fit: contain;
    object-position: left center;
    display: block;
    filter: drop-shadow(0 14px 32px rgba(0, 6, 35, 0.06));
}
</style>

<section id="hero-section" class="hero-section-wrap">
    <div class="hero-inner-container">
        <div class="hero-flex-layout">
            <!-- Left: Hero Text, Counters & Floating Search Bar -->
            <div class="hero-text-column">
                <div class="hero-content-group">
                    <!-- Over 10,000+ Students Connected Badge -->
                    <div>
                        <span class="hero-badge-pill">
                            <span class="hero-pulse-dot"></span>
                            Over 10,000+ Students Connected
                        </span>
                    </div>

                    <!-- Main Headline with Brand Selection Frame & Color-Synchronized Cursor -->
                    <h1 class="hero-headline-title">
                        Get Your <span class="hero-dream-accent">Dream</span><br>
                        Job Through
                        <span class="hero-brand-highlight">
                            <span>InternConnect</span>
                            <span class="hero-handle hero-handle-tl" aria-hidden="true"></span>
                            <span class="hero-handle hero-handle-tr" aria-hidden="true"></span>
                            <span class="hero-handle hero-handle-bl" aria-hidden="true"></span>
                            <span class="hero-handle hero-handle-br" aria-hidden="true"></span>
                            <!-- Animated 4-Corner Pointer Cursor with Color-Synchronized Fill & Spin -->
                            <span class="hero-cursor-pointer" aria-hidden="true">
                                <svg class="hero-cursor-svg" viewBox="0 0 24 24">
                                    <path class="hero-cursor-path"
                                          d="M4 2l14 10-6.5 1.5 4 8-3 1.5-4-8-4.5 5.5V2z" />
                                </svg>
                            </span>
                        </span>
                    </h1>

                    <!-- Subheadline (Clean 2-line summary) -->
                    <p class="hero-subtext-para">
                        Easily search thousands of verified roles worldwide and connect directly with top hiring teams.
                    </p>

                    <!-- Metrics Counter Pills (Strictly Single Line on Mobile) -->
                    <div class="hero-counters-row">
                        <div class="hero-counter-pill">
                            <svg class="hero-counter-icon" fill="none" viewBox="0 0 24 24" stroke="#FFFFFF" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20 7h-4V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v2H4a2 2 0 00-2 2v10a2 2 0 002 2h16a2 2 0 002-2V9a2 2 0 00-2-2z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10 7V5h4v2" />
                            </svg>
                            <span>10,000+ Active Roles</span>
                        </div>
                        <div class="hero-counter-pill">
                            <svg class="hero-counter-icon" fill="none" viewBox="0 0 24 24" stroke="#FFFFFF" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            <span>5,000+ Students Placed</span>
                        </div>
                    </div>

                    <!-- Integrated Hero Split Search Bar -->
                    <div style="padding-top: 4px; width: 100%;">
                        <form action="<?= url('/search') ?>" method="GET" class="hero-search-box">
                            <div class="hero-search-field">
                                <svg class="hero-search-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                <input type="text" name="search" placeholder="Job Title or keywords" class="hero-search-input" autocomplete="off" aria-label="Job Title or Keyword">
                            </div>
                            <div class="hero-search-divider" aria-hidden="true"></div>
                            <div class="hero-search-field">
                                <svg class="hero-search-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <input type="text" name="location" placeholder="City, state, or Remote" class="hero-search-input" autocomplete="off" aria-label="Job Location">
                            </div>
                            <button type="submit" class="hero-search-btn">
                                <svg style="width: 16px; height: 16px; flex-shrink: 0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                <span>Search</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Right: Single Consolidated Cloudinary Banner Graphic (Shifted Closely Beside Text) -->
            <div class="hero-graphic-column">
                <div class="hero-single-banner-wrap">
                    <img src="<?= asset('assets/images/internconnect_banner_img.png') ?>" 
                         alt="InternConnect Banner" 
                         class="hero-single-banner-img"
                         loading="eager">
                </div>
            </div>
        </div>
    </div>
</section>