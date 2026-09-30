<?php

declare(strict_types=1);

/**
 * Component: Landing Page - "See It On Your Phone" Mobile Device Preview Section
 * Location: Directly below "Meet Jenny" AI Showcase Section
 * Features a realistic iPhone device frame with Dynamic Island and screen content.
 */
?>
<style>
/* ─── Mobile Preview Showcase Section ─── */
.mobile-preview-section {
    position: relative;
    background-color: #FFFFFF;
    padding: 60px 0 100px 0;
    width: 100%;
    overflow: hidden;
    box-sizing: border-box;
}

@media (min-width: 1024px) {
    .mobile-preview-section {
        padding: 80px 0 120px 0;
    }
}

.mobile-preview-container {
    max-width: 1280px;
    margin: 0 auto;
    padding: 0 24px;
    width: 100%;
    box-sizing: border-box;
}

/* ─── Section Header ─── */
.mobile-preview-header {
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

.mobile-preview-pill {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 6px 14px;
    border-radius: 9999px;
    background-color: #EEF2FF;
    color: #4F48E6;
    border: 1px solid #C7D2FE;
    font-size: 12.5px;
    font-weight: 700;
    letter-spacing: 0.02em;
    text-transform: uppercase;
    white-space: nowrap;
    width: fit-content;
}

.mobile-preview-pulse-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background-color: #4F48E6;
    animation: mobileDotPulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

@keyframes mobileDotPulse {
    0%, 100% { opacity: 1; transform: scale(1); }
    50% { opacity: 0.4; transform: scale(0.85); }
}

.mobile-preview-title {
    font-size: clamp(26px, 3.8vw, 44px);
    line-height: 1.18;
    font-weight: 800;
    color: #000623;
    letter-spacing: -0.025em;
    margin: 0;
    word-break: break-word;
}

.mobile-preview-accent {
    color: #4F48E6;
}

.mobile-preview-subtext {
    font-size: 16px;
    line-height: 1.6;
    color: #475569;
    max-width: 640px;
    margin: 0 auto;
    font-weight: 400;
}

/* ─── Two-Column Layout: Features List & Device Mockup ─── */
.mobile-preview-grid {
    display: grid;
    grid-template-columns: minmax(0, 1fr);
    gap: 40px;
    align-items: center;
    width: 100%;
    box-sizing: border-box;
}

@media (min-width: 1024px) {
    .mobile-preview-grid {
        grid-template-columns: minmax(0, 1fr) 380px;
        column-gap: 64px;
        max-width: 1100px;
        margin: 0 auto;
    }
}

/* ─── Left Side: Feature Benefit Cards ─── */
.mobile-features-col {
    display: flex;
    flex-direction: column;
    gap: 20px;
    width: 100%;
    box-sizing: border-box;
}

.mobile-feature-card {
    background: #FFFFFF;
    border: 1px solid #E2E8F0;
    border-radius: 20px;
    padding: 22px 24px;
    box-shadow: 0 4px 20px -2px rgba(0, 6, 35, 0.04);
    display: flex;
    align-items: flex-start;
    gap: 16px;
    box-sizing: border-box;
    transition: transform 0.25s ease, box-shadow 0.25s ease;
}

.mobile-feature-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 28px -4px rgba(0, 6, 35, 0.08);
}

.mobile-feature-icon {
    width: 44px;
    height: 44px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.icon-bg-indigo { background: #EEF2FF; color: #4F48E6; }
.icon-bg-navy   { background: #F1F5F9; color: #000623; }
.icon-bg-emerald{ background: #ECFDF5; color: #10B981; }

.mobile-feature-body {
    display: flex;
    flex-direction: column;
    gap: 6px;
    min-width: 0;
    flex: 1;
}

.mobile-feature-heading {
    font-size: 16px;
    font-weight: 700;
    color: #000623;
    line-height: 1.3;
}

.mobile-feature-desc {
    font-size: 13.5px;
    color: #64748B;
    line-height: 1.5;
}

/* ─── Right Side: Realistic iPhone 17 Pro Max Chassis & Attached Floating Cards ─── */
.mobile-device-col {
    display: flex;
    justify-content: center;
    align-items: center;
    width: 100%;
    box-sizing: border-box;
}

.mobile-device-stage {
    position: relative;
    display: flex;
    justify-content: center;
    align-items: center;
}

.iphone-frame-outer {
    position: relative;
    width: 310px;
    height: 620px;
    background: linear-gradient(145deg, #2D2926 0%, #1A1816 50%, #2D2926 100%); /* Titanium chassis */
    border-radius: 50px;
    padding: 9px;
    box-shadow: 
        0 25px 60px -15px rgba(0, 6, 35, 0.28),
        0 0 0 1px rgba(255, 255, 255, 0.18) inset,
        0 0 0 3px #1F1D1B inset;
    box-sizing: border-box;
}

/* Floating Attached Overlapping Cards (Blends with phone) */
.iphone-floating-card {
    position: absolute;
    background: #FFFFFF;
    border: 1px solid #E2E8F0;
    border-radius: 16px;
    padding: 10px 14px;
    box-shadow: 0 16px 36px -4px rgba(0, 6, 35, 0.14), 0 2px 6px rgba(0, 6, 35, 0.04);
    display: flex;
    align-items: center;
    gap: 10px;
    z-index: 35;
    user-select: none;
    pointer-events: auto;
    transition: transform 0.25s ease, box-shadow 0.25s ease;
}

.iphone-floating-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 20px 42px -6px rgba(0, 6, 35, 0.18);
}

.floating-card-top {
    top: 75px;
    left: -55px;
    animation: floatBobTop 4s ease-in-out infinite alternate;
}

.floating-card-bot {
    bottom: 85px;
    right: -50px;
    animation: floatBobBot 4.5s ease-in-out infinite alternate;
}

@keyframes floatBobTop {
    0% { transform: translateY(0); }
    100% { transform: translateY(-6px); }
}

@keyframes floatBobBot {
    0% { transform: translateY(0); }
    100% { transform: translateY(6px); }
}

@media (max-width: 1023px) {
    .iphone-floating-card {
        display: none !important;
    }
}

.floating-card-icon {
    width: 34px;
    height: 34px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.floating-card-text {
    display: flex;
    flex-direction: column;
    gap: 2px;
    min-width: 0;
}

.floating-card-title {
    font-size: 12.5px;
    font-weight: 700;
    color: #000623;
    line-height: 1.2;
    white-space: nowrap;
}

.floating-card-sub {
    font-size: 11px;
    color: #64748B;
    line-height: 1.2;
    white-space: nowrap;
}

.floating-card-badge {
    font-size: 10px;
    font-weight: 700;
    padding: 3px 8px;
    border-radius: 9999px;
    background: #EEF2FF;
    color: #4F48E6;
    border: 1px solid #C7D2FE;
    white-space: nowrap;
    flex-shrink: 0;
    margin-left: 4px;
}

/* Side Hardware Buttons */
.iphone-button-action {
    position: absolute;
    left: -3px;
    top: 105px;
    width: 3px;
    height: 24px;
    background: #44403C;
    border-radius: 2px 0 0 2px;
}

.iphone-button-vol-up {
    position: absolute;
    left: -3px;
    top: 145px;
    width: 3px;
    height: 46px;
    background: #44403C;
    border-radius: 2px 0 0 2px;
}

.iphone-button-vol-down {
    position: absolute;
    left: -3px;
    top: 205px;
    width: 3px;
    height: 46px;
    background: #44403C;
    border-radius: 2px 0 0 2px;
}

.iphone-button-power {
    position: absolute;
    right: -3px;
    top: 165px;
    width: 3px;
    height: 60px;
    background: #44403C;
    border-radius: 0 2px 2px 0;
}

/* Inner Screen Cutout */
.iphone-screen {
    position: relative;
    width: 100%;
    height: 100%;
    background: #000623;
    border-radius: 42px;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    box-shadow: 0 0 0 1px rgba(0, 0, 0, 0.9) inset;
}

/* Dynamic Island */
.iphone-dynamic-island {
    position: absolute;
    top: 9px;
    left: 50%;
    transform: translateX(-50%);
    width: 105px;
    height: 27px;
    background: #000000;
    border-radius: 20px;
    z-index: 30;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 10px;
    box-sizing: border-box;
    box-shadow: 0 1px 4px rgba(0, 0, 0, 0.4);
}

.iphone-island-camera {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    background: #111827;
    position: relative;
}

.iphone-island-camera::after {
    content: '';
    position: absolute;
    top: 2px;
    left: 2px;
    width: 3px;
    height: 3px;
    border-radius: 50%;
    background: rgba(79, 72, 230, 0.5);
}

.iphone-island-sensor {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: #0F172A;
}

/* iOS Top Status Bar */
.iphone-status-bar {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 40px;
    padding: 0 22px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    z-index: 25;
    pointer-events: none;
    box-sizing: border-box;
}

.iphone-status-time {
    font-size: 12.5px;
    font-weight: 700;
    color: #FFFFFF;
    letter-spacing: -0.02em;
}

.iphone-status-icons {
    display: flex;
    align-items: center;
    gap: 5px;
    color: #FFFFFF;
}

/* Inside Screen Content Area */
.iphone-screen-content {
    position: relative;
    width: 100%;
    height: 100%;
    background: #000623;
    display: flex;
    flex-direction: column;
    padding: 44px 12px 14px 12px;
    box-sizing: border-box;
    gap: 10px;
    justify-content: space-between;
}

/* App In-Screen Header */
.iphone-inapp-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 4px 6px;
    width: 100%;
    box-sizing: border-box;
}

.iphone-inapp-left {
    display: flex;
    align-items: center;
    gap: 8px;
}

.iphone-inapp-logo {
    width: 26px;
    height: 26px;
    border-radius: 7px;
    background: #4F48E6;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #FFFFFF;
    flex-shrink: 0;
}

.iphone-inapp-meta {
    display: flex;
    flex-direction: column;
}

.iphone-inapp-title {
    font-size: 12px;
    font-weight: 700;
    color: #FFFFFF;
    line-height: 1.2;
}

.iphone-inapp-sub {
    font-size: 9.5px;
    color: #94A3B8;
    line-height: 1.2;
}

.iphone-inapp-status {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 2px 7px;
    border-radius: 9999px;
    background: rgba(16, 185, 129, 0.15);
    border: 1px solid rgba(16, 185, 129, 0.3);
    color: #34D399;
    font-size: 9px;
    font-weight: 700;
}

.iphone-inapp-dot {
    width: 5px;
    height: 5px;
    border-radius: 50%;
    background: #34D399;
}

/* Jenny Media Inside iPhone Screen Cutout */
.iphone-media-wrap {
    position: relative;
    width: 100%;
    height: 180px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 16px;
    overflow: hidden;
    background: #FFFFFF;
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.2);
    box-sizing: border-box;
    flex-shrink: 0;
}

.iphone-media-img {
    width: 100%;
    height: 100%;
    object-fit: contain;
    background: #FFFFFF;
}

/* Inside Screen AI Assistant Dialog Box */
.iphone-app-dialog {
    background: rgba(255, 255, 255, 0.08);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.12);
    border-radius: 14px;
    padding: 10px 12px;
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.iphone-dialog-bubble {
    font-size: 11px;
    color: #E2E8F0;
    line-height: 1.45;
}

.iphone-dialog-actions {
    display: flex;
    align-items: center;
    gap: 6px;
}

.iphone-dialog-btn-primary {
    font-size: 10px;
    font-weight: 700;
    color: #FFFFFF;
    background: #4F48E6;
    padding: 4px 10px;
    border-radius: 9999px;
    white-space: nowrap;
    display: inline-flex;
    align-items: center;
    gap: 4px;
}

.iphone-dialog-btn-sec {
    font-size: 10px;
    font-weight: 600;
    color: #94A3B8;
    background: rgba(255, 255, 255, 0.08);
    padding: 4px 8px;
    border-radius: 9999px;
    white-space: nowrap;
}

/* Inside Screen App Dock Nav */
.iphone-app-dock {
    display: flex;
    align-items: center;
    justify-content: space-around;
    padding: 6px 12px;
    background: rgba(15, 23, 42, 0.7);
    border-radius: 20px;
    border: 1px solid rgba(255, 255, 255, 0.08);
}

.iphone-dock-item {
    color: #94A3B8;
    display: flex;
    flex-direction: column;
    align-items: center;
    font-size: 8.5px;
    gap: 2px;
}

.iphone-dock-item.is-active {
    color: #4F48E6;
}

/* Home Bar Indicator */
.iphone-home-bar {
    position: absolute;
    bottom: 5px;
    left: 50%;
    transform: translateX(-50%);
    width: 110px;
    height: 4px;
    border-radius: 2px;
    background: #FFFFFF;
    opacity: 0.6;
    z-index: 30;
}

@media (max-width: 640px) {
    .mobile-preview-container {
        padding: 0 16px;
    }
    .mobile-preview-header {
        margin-bottom: 32px;
        gap: 10px;
    }
    .iphone-frame-outer {
        width: 285px;
        height: 570px;
        border-radius: 46px;
    }
    .iphone-media-wrap {
        height: 160px;
    }
}
</style>

<section class="mobile-preview-section" id="mobile-preview-block">
    <div class="mobile-preview-container">

        <!-- Section Header -->
        <div class="mobile-preview-header">
            <div class="mobile-preview-pill">
                <span class="mobile-preview-pulse-dot"></span>
                <span>Mobile Experience</span>
            </div>
            <h2 class="mobile-preview-title">
                Your AI Career Co-Pilot, <span class="mobile-preview-accent">Always in Your Pocket</span>
            </h2>
            <p class="mobile-preview-subtext">
                Review applicant dossiers, confirm interview time slots, and chat with Jenny in real time—crafted for seamless mobile performance.
            </p>
        </div>

        <!-- Two-Column Grid: Feature Benefits & Realistic iPhone Mockup -->
        <div class="mobile-preview-grid">

            <!-- Left Column: Feature Highlights -->
            <div class="mobile-features-col">

                <div class="mobile-feature-card">
                    <div class="mobile-feature-icon icon-bg-indigo">
                        <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                    </div>
                    <div class="mobile-feature-body">
                        <span class="mobile-feature-heading">Instant Push Notifications</span>
                        <span class="mobile-feature-desc">Never miss an interview invitation, status upgrade, or recruiter message with real-time mobile push delivery.</span>
                    </div>
                </div>

                <div class="mobile-feature-card">
                    <div class="mobile-feature-icon icon-bg-navy">
                        <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div class="mobile-feature-body">
                        <span class="mobile-feature-heading">1-Tap Video Room Access</span>
                        <span class="mobile-feature-desc">Join Google Meet and candidate interview rooms directly from your mobile browser without installing third-party apps.</span>
                    </div>
                </div>

                <div class="mobile-feature-card">
                    <div class="mobile-feature-icon icon-bg-emerald">
                        <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="mobile-feature-body">
                        <span class="mobile-feature-heading">On-the-Go CV Upload & ATS Scoring</span>
                        <span class="mobile-feature-desc">Snap, import, and score your resume anytime. Jenny analyzes formatting and competency alignment in seconds.</span>
                    </div>
                </div>

            </div>

            <!-- Right Column: Realistic iPhone 17 Pro Max Mockup with Blended Floating Cards -->
            <div class="mobile-device-col">
                <div class="mobile-device-stage">

                    <!-- Floating Attached Card 1 (Top Left Overlapping Bezel) -->
                    <div class="iphone-floating-card floating-card-top">
                        <div class="floating-card-icon icon-bg-indigo">
                            <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="floating-card-text">
                            <span class="floating-card-title">98% ATS Match</span>
                            <span class="floating-card-sub">Top Tier Candidate</span>
                        </div>
                    </div>

                    <!-- Floating Attached Card 2 (Bottom Right Overlapping Bezel) -->
                    <div class="iphone-floating-card floating-card-bot">
                        <div class="floating-card-icon icon-bg-emerald">
                            <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div class="floating-card-text">
                            <span class="floating-card-title">Interview Link Ready</span>
                            <span class="floating-card-sub">Today, 2:30 PM</span>
                        </div>
                        <span class="floating-card-badge">Sync</span>
                    </div>

                    <!-- Phone Chassis -->
                    <div class="iphone-frame-outer">
                        <!-- Physical Hardware Buttons -->
                        <div class="iphone-button-action"></div>
                        <div class="iphone-button-vol-up"></div>
                        <div class="iphone-button-vol-down"></div>
                        <div class="iphone-button-power"></div>

                        <!-- Inner Screen Cutout -->
                        <div class="iphone-screen">
                            <!-- Dynamic Island -->
                            <div class="iphone-dynamic-island">
                                <div class="iphone-island-camera"></div>
                                <div class="iphone-island-sensor"></div>
                            </div>

                            <!-- Top Status Bar -->
                            <div class="iphone-status-bar">
                                <span class="iphone-status-time">9:41</span>
                                <div class="iphone-status-icons">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M12 3C7.5 3 3.7 4.9 1 8l11 13 11-13C20.3 4.9 16.5 3 12 3z"/>
                                    </svg>
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M2 17h20v4H2v-4zm0-6h14v4H2v-4zm0-6h8v4H2V5z"/>
                                    </svg>
                                </div>
                            </div>

                            <!-- Screen Content Area with Real iOS SaaS Experience -->
                            <div class="iphone-screen-content">

                                <!-- App In-Screen Header -->
                                <div class="iphone-inapp-header">
                                    <div class="iphone-inapp-left">
                                        <div class="iphone-inapp-logo">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                            </svg>
                                        </div>
                                        <div class="iphone-inapp-meta">
                                            <span class="iphone-inapp-title">Jenny AI</span>
                                            <span class="iphone-inapp-sub">Internship Partner</span>
                                        </div>
                                    </div>
                                    <div class="iphone-inapp-status">
                                        <span class="iphone-inapp-dot"></span>
                                        <span>Active</span>
                                    </div>
                                </div>

                                <!-- Screen Cutout: Jenny GIF Inside Phone -->
                                <div class="iphone-media-wrap">
                                    <img 
                                        src="https://res.cloudinary.com/weburea/image/upload/v1790735673/jenny_ai_intro_frykbn.gif" 
                                        alt="Jenny AI Mobile Screen Preview" 
                                        class="iphone-media-img"
                                        loading="lazy"
                                    >
                                </div>

                                <!-- In-Screen AI Dialogue & Recommendation Card -->
                                <div class="iphone-app-dialog">
                                    <div class="iphone-dialog-bubble">
                                        Jenny matched your profile to 3 verified design internships in Lagos.
                                    </div>
                                    <div class="iphone-dialog-actions">
                                        <span class="iphone-dialog-btn-primary">
                                            <span>View Matches</span>
                                            <svg width="11" height="11" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                            </svg>
                                        </span>
                                        <span class="iphone-dialog-btn-sec">Later</span>
                                    </div>
                                </div>

                                <!-- In-Screen App Dock Nav -->
                                <div class="iphone-app-dock">
                                    <div class="iphone-dock-item is-active">
                                        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                        </svg>
                                        <span>Home</span>
                                    </div>
                                    <div class="iphone-dock-item">
                                        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                        </svg>
                                        <span>Explore</span>
                                    </div>
                                    <div class="iphone-dock-item">
                                        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        <span>Schedule</span>
                                    </div>
                                    <div class="iphone-dock-item">
                                        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                        <span>Profile</span>
                                    </div>
                                </div>

                            </div>

                            <!-- Home Bar Indicator -->
                            <div class="iphone-home-bar"></div>
                        </div>
                    </div>

                </div>
            </div>

        </div>

    </div>
</section>
