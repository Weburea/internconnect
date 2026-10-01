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
        padding: 0 0 90px 0;
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
    opacity: 0;
    will-change: stroke-dashoffset, opacity;
}

.jenny-node-pulse {
    transform-origin: 500px 860px;
    transition: transform 0.3s ease, opacity 0.3s ease;
    opacity: 0;
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

/* ─── Brand Selection Box & Animated Color-Synchronized Spinning Cursor for Jenny (Figma Style) ─── */
.jenny-brand-highlight {
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
    margin: 0 4px;
    animation: jennyBoxColorCircuit 8s cubic-bezier(0.4, 0, 0.2, 1) infinite;
}

.jenny-brand-highlight .jenny-handle {
    position: absolute;
    width: 6px;
    height: 6px;
    background-color: #FFFFFF;
    border: 1.5px solid #4F48E6;
    border-radius: 1px;
    z-index: 2;
    transition: all 0.3s ease;
    animation: jennyHandleColorCircuit 8s cubic-bezier(0.4, 0, 0.2, 1) infinite;
}

.jenny-handle-tl { top: -4px; left: -4px; }
.jenny-handle-tr { top: -4px; right: -4px; }
.jenny-handle-bl { bottom: -4px; left: -4px; }
.jenny-handle-br { bottom: -4px; right: -4px; }

/* Large sharp cursor pointer with color-synchronized fill and glow, spinning & hovering across 4 corners */
.jenny-cursor-pointer {
    position: absolute;
    width: 28px;
    height: 28px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    pointer-events: none;
    z-index: 10;
    animation: jennyCursorCircuit 8s cubic-bezier(0.4, 0, 0.2, 1) infinite;
    transform-origin: 20% 20%;
}

.jenny-cursor-svg {
    width: 28px;
    height: 28px;
    color: #4F48E6;
    animation: jennyCursorColorCircuit 8s cubic-bezier(0.4, 0, 0.2, 1) infinite;
}

.jenny-cursor-path {
    fill: currentColor;
    stroke: #FFFFFF;
    stroke-width: 1.6;
    stroke-linejoin: round;
}

@keyframes jennyCursorCircuit {
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
    87% {
        transform: rotate(1260deg) scale(1.12);
    }
    92% {
        top: calc(100% - 6px);
        left: calc(100% - 6px);
        transform: rotate(1440deg) scale(1);
    }
}

@keyframes jennyCursorColorCircuit {
    0%, 16% {
        color: #4F48E6;
        filter: drop-shadow(0 4px 10px rgba(79, 72, 230, 0.5));
    }
    26%, 40% {
        color: #FF5722;
        filter: drop-shadow(0 4px 10px rgba(255, 87, 34, 0.5));
    }
    48%, 62% {
        color: #4F48E6;
        filter: drop-shadow(0 4px 10px rgba(79, 72, 230, 0.5));
    }
    70%, 84% {
        color: #FF5722;
        filter: drop-shadow(0 4px 10px rgba(255, 87, 34, 0.5));
    }
    92%, 100% {
        color: #4F48E6;
        filter: drop-shadow(0 4px 10px rgba(79, 72, 230, 0.5));
    }
}

@keyframes jennyBoxColorCircuit {
    0%, 16% {
        border-color: #4F48E6;
        background: rgba(79, 72, 230, 0.05);
        box-shadow: 0 0 0 2px rgba(79, 72, 230, 0.18), 0 8px 20px -4px rgba(79, 72, 230, 0.14);
    }
    26%, 40% {
        border-color: #FF5722;
        background: rgba(255, 87, 34, 0.05);
        box-shadow: 0 0 0 2px rgba(255, 87, 34, 0.18), 0 8px 20px -4px rgba(255, 87, 34, 0.14);
    }
    48%, 62% {
        border-color: #4F48E6;
        background: rgba(79, 72, 230, 0.05);
        box-shadow: 0 0 0 2px rgba(79, 72, 230, 0.18), 0 8px 20px -4px rgba(79, 72, 230, 0.14);
    }
    70%, 84% {
        border-color: #FF5722;
        background: rgba(255, 87, 34, 0.05);
        box-shadow: 0 0 0 2px rgba(255, 87, 34, 0.18), 0 8px 20px -4px rgba(255, 87, 34, 0.14);
    }
    92%, 100% {
        border-color: #4F48E6;
        background: rgba(79, 72, 230, 0.05);
        box-shadow: 0 0 0 2px rgba(79, 72, 230, 0.18), 0 8px 20px -4px rgba(79, 72, 230, 0.14);
    }
}

@keyframes jennyHandleColorCircuit {
    0%, 16% { border-color: #4F48E6; }
    26%, 40% { border-color: #FF5722; }
    48%, 62% { border-color: #4F48E6; }
    70%, 84% { border-color: #FF5722; }
    92%, 100% { border-color: #4F48E6; }
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

/* ─── CSS Grid Container for Scattered Card Alignment Around Phone Mockup ─── */
.meet-jenny-grid-container {
    position: relative;
    z-index: 5;
    width: 100%;
    box-sizing: border-box;
}

@media (min-width: 1024px) {
    .meet-jenny-grid-container {
        display: grid;
        grid-template-columns: minmax(300px, 330px) 296px minmax(300px, 330px);
        grid-template-rows: auto 1fr auto;
        grid-template-areas:
            "top-left    center    top-right"
            "mid-left    center    mid-right"
            "bot-left    center    bot-right";
        align-items: center;
        justify-content: center;
        max-width: 970px;
        width: 100%;
        min-height: 560px;
        margin: 0 auto;
        column-gap: 0;
        row-gap: 26px;
        position: relative;
        z-index: 5;
    }

    /* Staggered & Overlapping Offsets (Cards enter/float over the phone mockup per annotations) */
    .card-top-left {
        --card-x: -12px;
        --card-y: -20px;
        grid-area: top-left;
        align-self: start;
        justify-self: end;
        z-index: 12;
    }
    .card-mid-left {
        --card-x: 58px; /* Pushed into phone mockup screen */
        --card-y: -4px;
        grid-area: mid-left;
        align-self: center;
        justify-self: end;
        z-index: 16;
    }
    .card-bot-left {
        --card-x: 28px; /* Pushed close to phone, overlapping bottom-left corner */
        --card-y: 20px;
        grid-area: bot-left;
        align-self: end;
        justify-self: end;
        z-index: 14;
    }

    .card-top-right {
        --card-x: -54px; /* Pushed into phone mockup screen */
        --card-y: -18px;
        grid-area: top-right;
        align-self: start;
        justify-self: start;
        z-index: 16;
    }
    .card-mid-right {
        --card-x: -14px; /* Close to phone right flank */
        --card-y: 6px;
        grid-area: mid-right;
        align-self: center;
        justify-self: start;
        z-index: 12;
    }
    .card-bot-right {
        --card-x: -140px; /* Shifted left across phone bottom to cover and hide jitter.video watermark */
        --card-y: 6px;
        grid-area: bot-right;
        align-self: end;
        justify-self: start;
        z-index: 16;
    }
}

@media (min-width: 1024px) and (max-width: 1199px) {
    .meet-jenny-grid-container {
        grid-template-columns: minmax(280px, 305px) 280px minmax(280px, 305px);
        max-width: 890px;
        column-gap: 0;
    }
    .meet-jenny-card {
        width: 100% !important;
        max-width: 305px !important;
        padding: 10px 14px !important;
    }
    .card-bot-right {
        --card-x: -130px;
        --card-y: 6px;
    }
    .meet-jenny-card-title {
        font-size: 13px !important;
    }
    .meet-jenny-card-sub {
        font-size: 11px !important;
    }
}

/* ─── Center Phone Mockup (Portrait Format, Blended Background) ─── */
.meet-jenny-phone-mockup {
    grid-area: center;
    align-self: center;
    justify-self: center;
    position: relative;
    width: 296px;
    height: 540px;
    background: linear-gradient(145deg, #2D2926 0%, #1A1816 50%, #2D2926 100%); /* Premium titanium frame */
    border-radius: 48px;
    padding: 9px;
    box-shadow: 
        0 25px 60px -15px rgba(0, 6, 35, 0.22),
        0 0 0 1px rgba(255, 255, 255, 0.16) inset,
        0 0 0 3px #1F1D1B inset;
    box-sizing: border-box;
    z-index: 5;
}

/* Side Hardware Buttons */
.meet-jenny-phone-btn-action {
    position: absolute;
    left: -3px;
    top: 90px;
    width: 3px;
    height: 22px;
    background: #44403C;
    border-radius: 2px 0 0 2px;
}
.meet-jenny-phone-btn-vol-up {
    position: absolute;
    left: -3px;
    top: 125px;
    width: 3px;
    height: 42px;
    background: #44403C;
    border-radius: 2px 0 0 2px;
}
.meet-jenny-phone-btn-vol-down {
    position: absolute;
    left: -3px;
    top: 180px;
    width: 3px;
    height: 42px;
    background: #44403C;
    border-radius: 2px 0 0 2px;
}
.meet-jenny-phone-btn-power {
    position: absolute;
    right: -3px;
    top: 140px;
    width: 3px;
    height: 56px;
    background: #44403C;
    border-radius: 0 2px 2px 0;
}

/* Inner Phone Screen (Seamlessly blended with GIF's studio background!) */
.meet-jenny-phone-screen {
    position: relative;
    width: 100%;
    height: 100%;
    background: #E0E0E5;
    border-radius: 40px;
    overflow: hidden;
    box-shadow: 0 0 0 1px rgba(0, 0, 0, 0.12) inset;
    box-sizing: border-box;
}

/* Dynamic Island */
.meet-jenny-phone-island {
    position: absolute;
    top: 10px;
    left: 50%;
    transform: translateX(-50%);
    width: 86px;
    height: 22px;
    background: #000000;
    border-radius: 14px;
    z-index: 25;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 9px;
    box-sizing: border-box;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.45);
}

.meet-jenny-island-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #111827;
    position: relative;
}
.meet-jenny-island-dot::after {
    content: '';
    position: absolute;
    top: 2px;
    left: 2px;
    width: 2.5px;
    height: 2.5px;
    border-radius: 50%;
    background: rgba(79, 72, 230, 0.55);
}

.meet-jenny-island-sensor {
    width: 5px;
    height: 5px;
    border-radius: 50%;
    background: #0F172A;
}

/* Inner Screen Content - Full Length Screen Coverage with Vertical Mobile Jenny */
.meet-jenny-phone-body {
    position: relative;
    width: 100%;
    height: 100%;
    background: #E0E0E5;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    box-sizing: border-box;
}

/* Full Bleed GIF Container with Studio Background Blend */
.meet-jenny-phone-gif-wrap {
    position: relative;
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #E0E0E5;
    overflow: hidden;
    margin: 0;
}

.meet-jenny-phone-gif {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center top;
    display: block;
    background: #E0E0E5;
}

/* Watermark Mask on Desktop & Tablet (Blends perfectly with studio background) */
.meet-jenny-watermark-mask {
    position: absolute;
    bottom: 14px;
    right: 36px;
    width: 96px;
    height: 32px;
    background: #E0E0E5;
    border-radius: 4px;
    z-index: 8;
    pointer-events: none;
}

/* Home Bar */
.meet-jenny-phone-home {
    position: absolute;
    bottom: 8px;
    left: 50%;
    transform: translateX(-50%);
    width: 92px;
    height: 4px;
    background: #0F172A;
    border-radius: 2px;
    opacity: 0.35;
    z-index: 25;
}

@media (max-width: 1023px) {
    .meet-jenny-phone-mockup {
        width: 270px;
        height: 480px;
        margin: 0 auto 28px auto;
    }
    
    /* Mobile Zoom: Zooms into Jenny's smiling face, headphones, and chest; crops out jitter.video below screen (Image 2) */
    .meet-jenny-phone-gif {
        transform: scale(1.72) !important;
        transform-origin: center 10% !important;
    }
    
    .meet-jenny-watermark-mask {
        display: none; /* Already pushed off-screen and cropped by zoom */
    }
}

/* ─── Floating Feature Cards (Clean SaaS Styling & Staggered Scroll-In) ─── */
.meet-jenny-card {
    background: #FFFFFF;
    border: 1px solid #E2E8F0;
    border-radius: 16px;
    padding: 12px 16px;
    box-shadow: 0 12px 28px -4px rgba(0, 6, 35, 0.08), 0 3px 8px -2px rgba(0, 6, 35, 0.04);
    display: flex;
    align-items: center;
    gap: 12px;
    box-sizing: border-box;
    user-select: none;
}

@media (min-width: 1024px) {
    .meet-jenny-card {
        width: max-content;
        min-width: 295px;
        max-width: 335px;
        opacity: 0;
        transform: translate(var(--card-x, 0), calc(var(--card-y, 0) + 24px));
        transition: opacity 0.55s cubic-bezier(0.16, 1, 0.3, 1), transform 0.55s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.28s ease;
    }

    /* Staggered Scroll-in Entrance Triggered by IntersectionObserver */
    .meet-jenny-grid-container.is-visible .card-top-left  { opacity: 1; transform: translate(var(--card-x, 0), var(--card-y, 0)); transition-delay: 0ms; }
    .meet-jenny-grid-container.is-visible .card-top-right { opacity: 1; transform: translate(var(--card-x, 0), var(--card-y, 0)); transition-delay: 130ms; }
    .meet-jenny-grid-container.is-visible .card-mid-left  { opacity: 1; transform: translate(var(--card-x, 0), var(--card-y, 0)); transition-delay: 260ms; }
    .meet-jenny-grid-container.is-visible .card-mid-right { opacity: 1; transform: translate(var(--card-x, 0), var(--card-y, 0)); transition-delay: 390ms; }
    .meet-jenny-grid-container.is-visible .card-bot-left  { opacity: 1; transform: translate(var(--card-x, 0), var(--card-y, 0)); transition-delay: 520ms; }
    .meet-jenny-grid-container.is-visible .card-bot-right { opacity: 1; transform: translate(var(--card-x, 0), var(--card-y, 0)); transition-delay: 650ms; }
}

.meet-jenny-card:hover {
    transform: translate(var(--card-x, 0), calc(var(--card-y, 0) - 4px)) scale(1.02);
    box-shadow: 0 18px 38px -4px rgba(0, 6, 35, 0.14), 0 4px 12px -2px rgba(0, 6, 35, 0.06);
    z-index: 25;
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
}

.meet-jenny-card-sub {
    font-size: 11.5px;
    font-weight: 500;
    color: #64748B;
    line-height: 1.25;
    white-space: nowrap;
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

                <!-- Left Branch (Purple): Enters from top at (497, 0), travels down 48px vertical corridor, branches left at Y=68, curves down left flank at X=11, curves along bottom at Y=900, curves inward/upward into section to center convergence (497, 860) -->
                <path class="meet-jenny-flow-track" d="M 497 0 L 497 48 Q 497 68, 475 68 L 33 68 Q 11 68, 11 88 L 11 878 Q 11 900, 33 900 L 460 900 Q 480 900, 480 885 L 480 875 Q 480 860, 497 860" />
                <path class="meet-jenny-flow-beam meet-jenny-beam-left" d="M 497 0 L 497 48 Q 497 68, 475 68 L 33 68 Q 11 68, 11 88 L 11 878 Q 11 900, 33 900 L 460 900 Q 480 900, 480 885 L 480 875 Q 480 860, 497 860" stroke="url(#jennyFlowLeftGrad)" filter="url(#jennyGlowPurple)" />

                <!-- Right Branch (Dark Mode Navy): Enters from top at (503, 0), travels down 48px vertical corridor, branches right at Y=68, curves down right flank at X=989, curves along bottom at Y=900, curves inward/upward into section to center convergence (503, 860) -->
                <path class="meet-jenny-flow-track" d="M 503 0 L 503 48 Q 503 68, 525 68 L 967 68 Q 989 68, 989 88 L 989 878 Q 989 900, 967 900 L 540 900 Q 520 900, 520 885 L 520 875 Q 520 860, 503 860" />
                <path class="meet-jenny-flow-beam meet-jenny-beam-right" d="M 503 0 L 503 48 Q 503 68, 525 68 L 967 68 Q 989 68, 989 88 L 989 878 Q 989 900, 967 900 L 540 900 Q 520 900, 520 885 L 520 875 Q 520 860, 503 860" stroke="url(#jennyFlowDarkGrad)" filter="url(#jennyGlowDark)" />

                <!-- Bottom Center Destination Convergence Node safely contained inside section (500, 860) -->
                <circle cx="500" cy="860" r="6" fill="#000623" stroke="#FFFFFF" stroke-width="2" />
                <circle class="jenny-node-pulse" cx="500" cy="860" r="14" fill="#4F48E6" opacity="0.25" />
            </svg>

            <!-- Section Header (Positioned at Top-Center of Stage) -->
            <div class="meet-jenny-header">
                <div class="meet-jenny-pill">
                    <span class="meet-jenny-pulse-dot"></span>
                    <span>Autonomous AI Internship Partner</span>
                </div>
                <h2 class="meet-jenny-title">
                    Hi<?= $firstName ? ' ' . htmlspecialchars($firstName) : '' ?>, I'm <span class="jenny-brand-highlight"><span>Jenny</span><span class="jenny-handle jenny-handle-tl" aria-hidden="true"></span><span class="jenny-handle jenny-handle-tr" aria-hidden="true"></span><span class="jenny-handle jenny-handle-bl" aria-hidden="true"></span><span class="jenny-handle jenny-handle-br" aria-hidden="true"></span><span class="jenny-cursor-pointer" aria-hidden="true"><svg class="jenny-cursor-svg" viewBox="0 0 24 24"><path class="jenny-cursor-path" d="M3 3l7 18 3.5-7.5L21 10 3 3z"/></svg></span></span> — welcome to <span class="meet-jenny-brand-accent">InternConnect</span>
                </h2>
                <p class="meet-jenny-subtext">
                    Your 24/7 dedicated AI partner guiding your career path—optimizing your CV, automating recruiter interviews, and delivering verified internship placements.
                </p>
            </div>

            <!-- CSS Grid Container with Named Areas for Consistent Card Alignment Around Phone Mockup -->
            <div class="meet-jenny-grid-container" id="meet-jenny-grid-wrap">

                <!-- Center: Realistic Portrait Phone Mockup with Jenny Looping GIF -->
                <div class="meet-jenny-phone-mockup">
                    <!-- Physical Hardware Buttons -->
                    <div class="meet-jenny-phone-btn-action"></div>
                    <div class="meet-jenny-phone-btn-vol-up"></div>
                    <div class="meet-jenny-phone-btn-vol-down"></div>
                    <div class="meet-jenny-phone-btn-power"></div>

                    <!-- Inner Phone Screen (Seamlessly blended with GIF's studio background!) -->
                    <div class="meet-jenny-phone-screen">
                        <!-- Dynamic Island -->
                        <div class="meet-jenny-phone-island">
                            <div class="meet-jenny-island-dot"></div>
                            <div class="meet-jenny-island-sensor"></div>
                        </div>

                        <!-- Inner Screen Content - Full Screen Mobile Jenny Showcase -->
                        <div class="meet-jenny-phone-body">
                            <!-- Jenny Looping GIF - Fills phone screen completely from edge to edge -->
                            <div class="meet-jenny-phone-gif-wrap">
                                <img 
                                    src="https://res.cloudinary.com/weburea/image/upload/v1790745255/jenny_ai_mob_eds5by.gif" 
                                    alt="Jenny AI assistant" 
                                    class="meet-jenny-phone-gif"
                                    loading="eager"
                                >
                                <!-- Studio watermark mask (seamlessly concealed under 24/7 Career Guidance card) -->
                                <div class="meet-jenny-watermark-mask" aria-hidden="true"></div>
                            </div>

                            <!-- Home Indicator Bar -->
                            <div class="meet-jenny-phone-home"></div>
                        </div>
                    </div>
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
