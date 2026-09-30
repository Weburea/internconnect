<?php

declare(strict_types=1);

/**
 * Component: Landing Page - "Meet Jenny" AI Assistant Showcase Section
 * Location: Directly below "Streamlined Process & AI Workflow Bento Section" (process.php)
 * Continuous pipeline circuit with borderless GIF blend and 6 floating feature cards arranged via CSS Grid.
 */

$sessionUser = $_SESSION['profile_name'] ?? ($_SESSION['user']['first_name'] ?? null);
$firstName = null;
if (!empty($sessionUser)) {
    $parts = explode(' ', trim((string)$sessionUser));
    $firstName = $parts[0] ?? null;
}
?>
<style>
/* ─── Meet Jenny Showcase Section ─── */
.meet-jenny-section {
    position: relative;
    background-color: #FFFFFF;
    padding: 40px 0 80px 0;
    width: 100%;
    overflow: hidden;
    box-sizing: border-box;
}

@media (min-width: 1024px) {
    .meet-jenny-section {
        padding: 48px 0 100px 0;
    }
}

.meet-jenny-container {
    max-width: 1280px;
    margin: 0 auto;
    padding: 0 24px;
    width: 100%;
    box-sizing: border-box;
}

/* ─── Canvas Wrapper & Circuit Flow ─── */
.meet-jenny-canvas-wrap {
    position: relative;
    width: 100%;
    box-sizing: border-box;
}

@media (min-width: 1024px) {
    .meet-jenny-canvas-wrap {
        padding: 0 34px;
        min-height: 980px;
    }
}

@media (max-width: 1023px) {
    .meet-jenny-flow-svg {
        display: none !important;
    }
    .meet-jenny-canvas-wrap {
        padding: 0 !important;
    }
}

/* SVG Flow Circuit Stream Overlay (Desktop) */
.meet-jenny-flow-svg {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    pointer-events: none;
    z-index: 2;
    overflow: visible;
}

.meet-jenny-flow-track {
    stroke: #E2E8F0;
    stroke-width: 2px;
    stroke-dasharray: 6 6;
    opacity: 0.85;
    fill: none;
}

.meet-jenny-flow-beam {
    stroke-width: 3.5px;
    stroke-linecap: round;
    fill: none;
    stroke-dasharray: 220 1800;
    animation: jennyBeamTravel 8.5s linear infinite;
}

.meet-jenny-beam-left {
    animation-duration: 8.5s;
}

.meet-jenny-beam-right {
    animation-duration: 8.5s;
    animation-delay: 0.3s;
}

@keyframes jennyBeamTravel {
    0% { stroke-dashoffset: 2020; }
    100% { stroke-dashoffset: 0; }
}

.jenny-node-pulse {
    animation: jennyNodePulse 2.4s cubic-bezier(0, 0, 0.2, 1) infinite;
    transform-origin: 500px 930px;
}

@keyframes jennyNodePulse {
    0% { r: 6px; opacity: 0.8; }
    50% { r: 16px; opacity: 0.2; }
    100% { r: 24px; opacity: 0; }
}

/* ─── Section Header ─── */
.meet-jenny-header {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    max-width: 780px;
    margin: 0 auto;
    gap: 14px;
    width: 100%;
    box-sizing: border-box;
    position: relative;
    z-index: 5;
}

@media (min-width: 1024px) {
    .meet-jenny-header {
        padding-top: 56px;
        margin-bottom: 28px;
    }
}

@media (max-width: 1023px) {
    .meet-jenny-header {
        margin-bottom: 32px;
    }
}

.meet-jenny-pill {
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

.meet-jenny-pulse-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background-color: #4F48E6;
    animation: jennyDotPulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

@keyframes jennyDotPulse {
    0%, 100% { opacity: 1; transform: scale(1); }
    50% { opacity: 0.4; transform: scale(0.85); }
}

.meet-jenny-title {
    font-size: clamp(26px, 3.8vw, 44px);
    line-height: 1.18;
    font-weight: 800;
    color: #000623;
    letter-spacing: -0.025em;
    margin: 0;
    word-break: break-word;
}

.meet-jenny-accent {
    color: #4F48E6;
}

.meet-jenny-brand-accent {
    color: #000623;
}

.meet-jenny-subtext {
    font-size: 16px;
    line-height: 1.6;
    color: #475569;
    max-width: 660px;
    margin: 0 auto;
    font-weight: 400;
}

@media (max-width: 640px) {
    .meet-jenny-container {
        padding: 0 16px;
    }
    .meet-jenny-header {
        margin-bottom: 24px;
        gap: 10px;
    }
    .meet-jenny-title {
        font-size: clamp(22px, 5.8vw, 32px);
    }
    .meet-jenny-subtext {
        font-size: 14.5px;
    }
}

/* ─── CSS Grid Container for Exact Mathematical Card Alignment ─── */
.meet-jenny-grid-container {
    position: relative;
    z-index: 5;
    width: 100%;
    box-sizing: border-box;
}

@media (min-width: 1024px) {
    .meet-jenny-grid-container {
        display: grid;
        grid-template-columns: minmax(270px, 320px) minmax(320px, 450px) minmax(270px, 320px);
        grid-template-rows: auto 1fr auto;
        grid-template-areas:
            "top-left    .         top-right"
            "mid-left    center    mid-right"
            "bot-left    .         bot-right";
        align-items: center;
        justify-content: space-between;
        max-width: 1160px;
        width: 100%;
        height: 500px;
        margin: 0 auto;
    }

    .card-top-left  { grid-area: top-left;  align-self: start; justify-self: start; }
    .card-top-right { grid-area: top-right; align-self: start; justify-self: end; }
    .card-mid-left  { grid-area: mid-left;  align-self: center; justify-self: start; }
    .card-mid-right { grid-area: mid-right; align-self: center; justify-self: end; }
    .card-bot-left  { grid-area: bot-left;  align-self: end; justify-self: start; }
    .card-bot-right { grid-area: bot-right; align-self: end; justify-self: end; }
}

@media (min-width: 1024px) and (max-width: 1199px) {
    .meet-jenny-card {
        width: 270px !important;
        padding: 10px 12px !important;
    }
    .meet-jenny-card-title {
        font-size: 12.5px !important;
    }
    .meet-jenny-card-sub {
        font-size: 11px !important;
    }
}

/* ─── Center GIF Container (Frameless, Borderless, Soft Mask Blend) ─── */
.meet-jenny-media-box {
    position: relative;
    background-color: #FFFFFF;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    box-sizing: border-box;
}

@media (min-width: 1024px) {
    .meet-jenny-media-box {
        grid-area: center;
        align-self: center;
        justify-self: center;
        width: 450px;
        height: 290px;
    }
}

@media (max-width: 1023px) {
    .meet-jenny-media-box {
        max-width: 440px;
        margin: 0 auto 28px auto;
        aspect-ratio: 16 / 9;
    }
}

/* Subtle Ambient Glow Behind Jenny */
.meet-jenny-media-glow {
    position: absolute;
    width: 100%;
    height: 100%;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(79, 72, 230, 0.05) 0%, rgba(255, 255, 255, 0) 70%);
    pointer-events: none;
    z-index: 1;
}

.meet-jenny-gif-el {
    position: relative;
    z-index: 2;
    width: 100%;
    height: 100%;
    object-fit: contain;
    background-color: #FFFFFF;
    border: none !important;
    outline: none !important;
    box-shadow: none !important;
    -webkit-mask-image: radial-gradient(ellipse 60% 60% at 50% 50%, #000000 28%, rgba(0, 0, 0, 0.82) 48%, rgba(0, 0, 0, 0.35) 68%, transparent 84%);
    mask-image: radial-gradient(ellipse 60% 60% at 50% 50%, #000000 28%, rgba(0, 0, 0, 0.82) 48%, rgba(0, 0, 0, 0.35) 68%, transparent 84%);
    pointer-events: none;
    user-select: none;
}

/* ─── Floating Feature Cards (Clean SaaS Styling & Staggered Scroll-In) ─── */
.meet-jenny-card {
    background: #FFFFFF;
    border: 1px solid #E2E8F0;
    border-radius: 16px;
    padding: 12px 16px;
    box-shadow: 0 4px 20px -2px rgba(0, 6, 35, 0.05), 0 1px 3px rgba(0, 6, 35, 0.03);
    display: flex;
    align-items: center;
    gap: 12px;
    box-sizing: border-box;
    user-select: none;
}

@media (min-width: 1024px) {
    .meet-jenny-card {
        width: 320px;
        opacity: 0;
        transform: translateY(20px);
        transition: opacity 0.55s cubic-bezier(0.16, 1, 0.3, 1), transform 0.55s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.28s ease;
    }

    /* Staggered Scroll-in Entrance Triggered by IntersectionObserver */
    .meet-jenny-grid-container.is-visible .card-top-left  { opacity: 1; transform: translateY(0); transition-delay: 0ms; }
    .meet-jenny-grid-container.is-visible .card-top-right { opacity: 1; transform: translateY(0); transition-delay: 130ms; }
    .meet-jenny-grid-container.is-visible .card-mid-left  { opacity: 1; transform: translateY(0); transition-delay: 260ms; }
    .meet-jenny-grid-container.is-visible .card-mid-right { opacity: 1; transform: translateY(0); transition-delay: 390ms; }
    .meet-jenny-grid-container.is-visible .card-bot-left  { opacity: 1; transform: translateY(0); transition-delay: 520ms; }
    .meet-jenny-grid-container.is-visible .card-bot-right { opacity: 1; transform: translateY(0); transition-delay: 650ms; }
}

.meet-jenny-card:hover {
    transform: translateY(-3px) scale(1.015);
    box-shadow: 0 12px 28px -4px rgba(0, 6, 35, 0.09);
}

.meet-jenny-card-icon {
    width: 38px;
    height: 38px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.meet-jenny-icon-purple {
    background-color: #EEF2FF;
    color: #4F48E6;
}

.meet-jenny-icon-navy {
    background-color: #F1F5F9;
    color: #000623;
}

.meet-jenny-icon-emerald {
    background-color: #ECFDF5;
    color: #10B981;
}

.meet-jenny-icon-violet {
    background-color: #F5F3FF;
    color: #7C3AED;
}

.meet-jenny-card-text {
    display: flex;
    flex-direction: column;
    gap: 2px;
    min-width: 0;
    flex: 1;
}

.meet-jenny-card-title {
    font-size: 13.5px;
    font-weight: 700;
    color: #000623;
    line-height: 1.25;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.meet-jenny-card-sub {
    font-size: 11.5px;
    font-weight: 500;
    color: #64748B;
    line-height: 1.25;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.meet-jenny-card-badge {
    font-size: 10px;
    font-weight: 700;
    padding: 3px 8px;
    border-radius: 9999px;
    background: #F8FAFC;
    color: #475569;
    border: 1px solid #E2E8F0;
    white-space: nowrap;
    flex-shrink: 0;
    margin-left: auto;
}

/* Tablet & Mobile Layout */
@media (max-width: 1023px) {
    .meet-jenny-grid-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        width: 100%;
    }
    
    .meet-jenny-cards-mobile-wrap {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 12px;
        width: 100%;
        box-sizing: border-box;
    }
    
    .meet-jenny-card {
        width: 100%;
    }
}

@media (max-width: 640px) {
    .meet-jenny-cards-mobile-wrap {
        grid-template-columns: minmax(0, 1fr) !important;
        gap: 10px;
        width: 100% !important;
    }
    
    .meet-jenny-card {
        padding: 11px 14px;
        border-radius: 14px;
        width: 100% !important;
    }
    
    .meet-jenny-card-icon {
        width: 34px;
        height: 34px;
    }
    
    .meet-jenny-card-title {
        font-size: 13px;
    }
    
    .meet-jenny-card-sub {
        font-size: 11px;
    }
}
</style>

<section class="meet-jenny-section" id="meet-jenny-block">
    <div class="meet-jenny-container">
        
        <!-- Canvas Wrapper (Holds Continuous Flow Circuit & CSS Grid Stage) -->
        <div class="meet-jenny-canvas-wrap">

            <!-- SVG Flow Circuit Stream Overlay (Desktop: Clean Vertical Distance Entry, Wide Flank Enveloping, 120px+ Under-Card Bottom Clearance) -->
            <svg class="meet-jenny-flow-svg" viewBox="0 0 1000 970" preserveAspectRatio="none" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <defs>
                    <!-- Left Stream Gradient: Vibrant Purple/Violet into Brand Indigo -->
                    <linearGradient id="jennyFlowLeftGrad" x1="50%" y1="0%" x2="50%" y2="100%">
                        <stop offset="0%" stop-color="#4F48E6"/>
                        <stop offset="30%" stop-color="#7C3AED"/>
                        <stop offset="70%" stop-color="#8B5CF6"/>
                        <stop offset="100%" stop-color="#4F48E6"/>
                    </linearGradient>

                    <!-- Right Stream Gradient: Official InternConnect Brand Dark Mode Navy -->
                    <linearGradient id="jennyFlowDarkGrad" x1="50%" y1="0%" x2="50%" y2="100%">
                        <stop offset="0%" stop-color="#000623"/>
                        <stop offset="30%" stop-color="#1E293B"/>
                        <stop offset="70%" stop-color="#0F172A"/>
                        <stop offset="100%" stop-color="#000623"/>
                    </linearGradient>

                    <!-- Glow Filters -->
                    <filter id="jennyGlowPurple" x="-25%" y="-25%" width="150%" height="150%">
                        <feGaussianBlur stdDeviation="3" result="blur"/>
                        <feMerge>
                            <feMergeNode in="blur"/>
                            <feMergeNode in="SourceGraphic"/>
                        </feMerge>
                    </filter>

                    <filter id="jennyGlowDark" x="-25%" y="-25%" width="150%" height="150%">
                        <feGaussianBlur stdDeviation="2.5" result="blur"/>
                        <feMerge>
                            <feMergeNode in="blur"/>
                            <feMergeNode in="SourceGraphic"/>
                        </feMerge>
                    </filter>
                </defs>

                <!-- Top Center Entry Origin Node (500, 0) -->
                <circle cx="500" cy="0" r="5" fill="#4F48E6" stroke="#FFFFFF" stroke-width="2" />

                <!-- Left Branch (Purple): Enters from top at (497, 0), travels down 48px vertical corridor, branches left at Y=68, curves down left flank at X=11, curves along bottom at Y=900 (120px+ clear below bottom cards) to center convergence (497, 930), then exits toward next section -->
                <path class="meet-jenny-flow-track" d="M 497 0 L 497 48 Q 497 68, 475 68 L 33 68 Q 11 68, 11 88 L 11 878 Q 11 900, 33 900 L 475 900 Q 497 900, 497 915 L 497 930 L 497 970" />
                <path class="meet-jenny-flow-beam meet-jenny-beam-left" d="M 497 0 L 497 48 Q 497 68, 475 68 L 33 68 Q 11 68, 11 88 L 11 878 Q 11 900, 33 900 L 475 900 Q 497 900, 497 915 L 497 930 L 497 970" stroke="url(#jennyFlowLeftGrad)" filter="url(#jennyGlowPurple)" />

                <!-- Right Branch (Dark Mode Navy): Enters from top at (503, 0), travels down 48px vertical corridor, branches right at Y=68, curves down right flank at X=989, curves along bottom at Y=900 (120px+ clear below bottom cards) to center convergence (503, 930), then exits toward next section -->
                <path class="meet-jenny-flow-track" d="M 503 0 L 503 48 Q 503 68, 525 68 L 967 68 Q 989 68, 989 88 L 989 878 Q 989 900, 967 900 L 525 900 Q 503 900, 503 915 L 503 930 L 503 970" />
                <path class="meet-jenny-flow-beam meet-jenny-beam-right" d="M 503 0 L 503 48 Q 503 68, 525 68 L 967 68 Q 989 68, 989 88 L 989 878 Q 989 900, 967 900 L 525 900 Q 503 900, 503 915 L 503 930 L 503 970" stroke="url(#jennyFlowDarkGrad)" filter="url(#jennyGlowDark)" />

                <!-- Bottom Center Destination Convergence Node (500, 930) -->
                <circle cx="500" cy="930" r="6" fill="#000623" stroke="#FFFFFF" stroke-width="2" />
                <circle class="jenny-node-pulse" cx="500" cy="930" r="14" fill="#4F48E6" opacity="0.25" />
            </svg>

            <!-- Section Header (Positioned at Top-Center of Stage) -->
            <div class="meet-jenny-header">
                <div class="meet-jenny-pill">
                    <span class="meet-jenny-pulse-dot"></span>
                    <span>Autonomous AI Internship Partner</span>
                </div>
                <h2 class="meet-jenny-title">
                    Hi<?= $firstName ? ' ' . htmlspecialchars($firstName) : '' ?>, I'm <span class="meet-jenny-accent">Jenny</span> — welcome to <span class="meet-jenny-brand-accent">InternConnect</span>
                </h2>
                <p class="meet-jenny-subtext">
                    Your 24/7 dedicated AI partner guiding your career path—optimizing your CV, automating recruiter interviews, and delivering verified internship placements.
                </p>
            </div>

            <!-- CSS Grid Container with Named Areas for Consistent Card Alignment -->
            <div class="meet-jenny-grid-container" id="meet-jenny-grid-wrap">

                <!-- Center Media Box: Jenny AI Native Looping GIF (Frameless, Borderless, Soft Mask Blend) -->
                <div class="meet-jenny-media-box">
                    <div class="meet-jenny-media-glow"></div>
                    <img 
                        src="https://res.cloudinary.com/weburea/image/upload/v1790735673/jenny_ai_intro_frykbn.gif" 
                        alt="Jenny AI assistant" 
                        class="meet-jenny-gif-el"
                        loading="eager"
                    >
                </div>

                <!-- Card 1: Top-Left (Update Your CV) -->
                <div class="meet-jenny-card card-top-left">
                    <div class="meet-jenny-card-icon meet-jenny-icon-purple">
                        <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <div class="meet-jenny-card-text">
                        <span class="meet-jenny-card-title">Update Your CV</span>
                        <span class="meet-jenny-card-sub">ATS format & keyword scoring</span>
                    </div>
                    <span class="meet-jenny-card-badge">96% ATS</span>
                </div>

                <!-- Card 2: Middle-Left (Interview Link Sync — Renamed from Google Meet Sync) -->
                <div class="meet-jenny-card card-mid-left">
                    <div class="meet-jenny-card-icon meet-jenny-icon-violet">
                        <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div class="meet-jenny-card-text">
                        <span class="meet-jenny-card-title">Interview Link Sync</span>
                        <span class="meet-jenny-card-sub">Instant video call links for any platform</span>
                    </div>
                    <span class="meet-jenny-card-badge">Any Platform</span>
                </div>

                <!-- Card 3: Bottom-Left (AI Competency Match) -->
                <div class="meet-jenny-card card-bot-left">
                    <div class="meet-jenny-card-icon meet-jenny-icon-purple">
                        <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <div class="meet-jenny-card-text">
                        <span class="meet-jenny-card-title">AI Competency Match</span>
                        <span class="meet-jenny-card-sub">Role alignment & skill depth</span>
                    </div>
                    <span class="meet-jenny-card-badge">Top 5%</span>
                </div>

                <!-- Card 4: Top-Right (Schedule Interviews) -->
                <div class="meet-jenny-card card-top-right">
                    <div class="meet-jenny-card-icon meet-jenny-icon-navy">
                        <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div class="meet-jenny-card-text">
                        <span class="meet-jenny-card-title">Schedule Interviews</span>
                        <span class="meet-jenny-card-sub">Automated slot coordination</span>
                    </div>
                    <span class="meet-jenny-card-badge">Zero Lag</span>
                </div>

                <!-- Card 5: Middle-Right (Track Applications) -->
                <div class="meet-jenny-card card-mid-right">
                    <div class="meet-jenny-card-icon meet-jenny-icon-emerald">
                        <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                        </svg>
                    </div>
                    <div class="meet-jenny-card-text">
                        <span class="meet-jenny-card-title">Track Applications</span>
                        <span class="meet-jenny-card-sub">Live milestone status alerts</span>
                    </div>
                    <span class="meet-jenny-card-badge">Real-Time</span>
                </div>

                <!-- Card 6: Bottom-Right (24/7 Career Guidance) -->
                <div class="meet-jenny-card card-bot-right">
                    <div class="meet-jenny-card-icon meet-jenny-icon-navy">
                        <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                    </div>
                    <div class="meet-jenny-card-text">
                        <span class="meet-jenny-card-title">24/7 Career Guidance</span>
                        <span class="meet-jenny-card-sub">Proactive recruiter mentoring</span>
                    </div>
                    <span class="meet-jenny-card-badge">Always On</span>
                </div>

            </div>

        </div>

    </div>
</section>

<script>
/**
 * Staggered Scroll-In Entrance Animation for Jenny Feature Cards
 */
document.addEventListener('DOMContentLoaded', function() {
    const gridWrap = document.getElementById('meet-jenny-grid-wrap');
    if (!gridWrap) return;

    if ('IntersectionObserver' in window) {
        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    gridWrap.classList.add('is-visible');
                    observer.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.22,
            rootMargin: '0px 0px -40px 0px'
        });

        observer.observe(gridWrap);
    } else {
        // Fallback for older browsers
        gridWrap.classList.add('is-visible');
    }
});
</script>
