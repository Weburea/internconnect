<?php

declare(strict_types=1);

/**
 * Component: Landing Page - Streamlined Process & AI Workflow Bento Section
 * Location: Directly below "Explore by Category" (statistics.php)
 * Premium Bento layout illustrating InternConnect's end-to-end student & employer journey.
 */
?>
<style>
/* ─── Process & Workflow Bento Section ─── */
.process-section-wrap {
    position: relative;
    background-color: #FFFFFF;
    padding: 40px 0 80px 0;
    width: 100%;
    overflow: hidden;
    box-sizing: border-box;
}

@media (min-width: 768px) and (max-width: 1023px) {
    .process-section-wrap {
        padding: 56px 0 80px 0;
    }
}

@media (min-width: 1024px) {
    .process-section-wrap {
        padding: 56px 0 0 0;
    }
}

.process-inner-container {
    max-width: 1280px;
    margin: 0 auto;
    padding: 0 24px;
    width: 100%;
    box-sizing: border-box;
}

/* ─── Section Header ─── */
.process-header-box {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    max-width: 760px;
    margin: 0 auto 48px auto;
    gap: 16px;
    width: 100%;
    box-sizing: border-box;
}

.process-eyebrow-pill {
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

.process-pulse-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background-color: #4F48E6;
    animation: processDotPulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

@keyframes processDotPulse {
    0%, 100% { opacity: 1; transform: scale(1); }
    50% { opacity: 0.4; transform: scale(0.85); }
}

.process-headline-title {
    font-size: clamp(28px, 4vw, 48px);
    line-height: 1.18;
    font-weight: 800;
    color: #000623;
    letter-spacing: -0.025em;
    margin: 0;
    word-break: break-word;
}

.process-dream-accent {
    color: #4F48E6;
}

.process-subtext-para {
    font-size: 16px;
    line-height: 1.6;
    color: #475569;
    max-width: 640px;
    margin: 0 auto;
    font-weight: 400;
}

@media (max-width: 640px) {
    .process-inner-container {
        padding: 0 16px;
    }
    .process-header-box {
        margin-bottom: 32px;
    }
    .process-headline-title {
        font-size: clamp(24px, 6.2vw, 36px);
        line-height: 1.2;
    }
    .process-subtext-para {
        font-size: 14.5px;
    }
}

/* ─── Bento Grid Canvas & Pipeline Flow Wrap ─── */
.process-bento-canvas-wrap {
    position: relative;
    width: 100%;
    box-sizing: border-box;
}

@media (min-width: 1024px) {
    .process-bento-canvas-wrap {
        padding: 4px 34px 44px 34px;
    }
}

@media (max-width: 1023px) {
    .process-bento-flow-svg {
        display: none !important;
    }
    .process-bento-canvas-wrap {
        padding: 0 !important;
    }
}

/* SVG Flow Circuit Stream Overlay (Desktop & Tablet) */
.process-bento-flow-svg {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    pointer-events: none;
    z-index: 10;
    overflow: visible;
}

.process-flow-track {
    stroke: #E2E8F0;
    stroke-width: 2px;
    stroke-dasharray: 6 6;
    opacity: 0.85;
    fill: none;
}

.process-flow-beam {
    stroke-width: 3.5px;
    stroke-linecap: round;
    fill: none;
    opacity: 0;
    will-change: stroke-dashoffset, opacity;
}

.process-node-pulse {
    transform-origin: 500px 981px;
    transition: transform 0.3s ease, opacity 0.3s ease;
    opacity: 0;
}

/* ─── Bento Grid Layout ─── */
.process-bento-grid {
    position: relative;
    z-index: 2;
    display: grid;
    grid-template-columns: minmax(0, 1fr);
    gap: 28px;
    width: 100%;
    box-sizing: border-box;
}

@media (min-width: 1024px) {
    .process-bento-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
        row-gap: 40px;
        column-gap: 32px;
    }
}

/* ─── Shared Bento Card Styles ─── */
.process-card {
    background: #FFFFFF;
    border: 1px solid #E2E8F0;
    border-radius: 24px;
    padding: 30px;
    box-shadow: 0 4px 20px -2px rgba(0, 6, 35, 0.04);
    display: flex;
    flex-direction: column;
    justify-content: flex-start;
    transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.3s ease;
    box-sizing: border-box;
    position: relative;
    overflow: hidden;
}

.process-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 16px 36px -6px rgba(0, 6, 35, 0.08);
}

@media (max-width: 640px) {
    .process-card {
        padding: 20px 16px;
        border-radius: 20px;
    }
}

/* Generous Breathing Room between Visual Mockup and Header Text */
.process-card-content {
    display: flex;
    flex-direction: column;
    gap: 12px;
    margin-top: 36px;
    width: 100%;
    box-sizing: border-box;
}

@media (max-width: 640px) {
    .process-card-content {
        margin-top: 28px;
    }
}

.process-card-title {
    font-size: 21px;
    font-weight: 800;
    color: #000623;
    line-height: 1.35;
    margin: 0;
    letter-spacing: -0.015em;
    padding-top: 2px;
}

.process-card-desc {
    font-size: 14.5px;
    line-height: 1.6;
    color: #475569;
    margin: 0;
}

.process-card-tags {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin-top: 6px;
}

.process-card-tag {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 4px 10px;
    border-radius: 9999px;
    background: #F1F5F9;
    color: #334155;
    font-size: 11.5px;
    font-weight: 600;
}

/* ─── Card 1: Complete Your Profile (Visual Mockup) ─── */
.process-profile-stage {
    background: #F8FAFC;
    border: 1px solid #E2E8F0;
    border-radius: 18px;
    padding: 20px;
    display: flex;
    flex-direction: column;
    gap: 12px;
    width: 100%;
    box-sizing: border-box;
}

.process-profile-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 14px;
    padding: 12px 16px;
    background: #FFFFFF;
    border: 1px solid #E2E8F0;
    border-radius: 14px;
    box-shadow: 0 2px 6px rgba(0, 6, 35, 0.03);
    transition: all 0.3s ease;
    box-sizing: border-box;
}

.process-row-animated {
    animation: processRowCycle 6s infinite ease-in-out;
}

.process-row-animated:nth-child(1) { animation-delay: 0s; }
.process-row-animated:nth-child(2) { animation-delay: 2s; }
.process-row-animated:nth-child(3) { animation-delay: 4s; }

@keyframes processRowCycle {
    0%, 100% {
        border-color: #E2E8F0;
        box-shadow: 0 2px 6px rgba(0, 6, 35, 0.03);
    }
    15%, 35% {
        border-color: #C7D2FE;
        box-shadow: 0 6px 16px -2px rgba(79, 72, 230, 0.12);
        transform: translateY(-2px);
    }
    50% {
        border-color: #E2E8F0;
        box-shadow: 0 2px 6px rgba(0, 6, 35, 0.03);
        transform: translateY(0);
    }
}

.process-profile-left {
    display: flex;
    align-items: center;
    gap: 12px;
    min-width: 0;
}

.process-icon-box {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.process-icon-red {
    background-color: #FFF1F2;
    color: #E11D48;
    border: 1px solid #FFE4E6;
}

.process-icon-blue {
    background-color: #EFF6FF;
    color: #2563EB;
    border: 1px solid #DBEAFE;
}

.process-icon-green {
    background-color: #F0FDF4;
    color: #16A34A;
    border: 1px solid #DCFCE7;
}

.process-profile-info {
    display: flex;
    flex-direction: column;
    min-width: 0;
}

.process-profile-label {
    font-size: 13.5px;
    font-weight: 700;
    color: #000623;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.process-profile-sub {
    font-size: 11.5px;
    color: #64748B;
    margin-top: 1px;
}

.process-check-circle {
    width: 24px;
    height: 24px;
    border-radius: 50%;
    background: #16A34A;
    color: #FFFFFF;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    box-shadow: 0 2px 8px rgba(22, 163, 74, 0.35);
}

/* ─── Card 2: Smart Portfolio Upload & Radar (Visual Canvas) ─── */
.process-radar-stage {
    position: relative;
    background: linear-gradient(145deg, #4F48E6 0%, #3730A3 55%, #000623 100%);
    border-radius: 20px;
    height: 236px;
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    box-sizing: border-box;
}

/* Concentric Radar Breathing Waves */
.process-radar-ring {
    position: absolute;
    border-radius: 50%;
    border: 1.5px solid rgba(255, 255, 255, 0.28);
    pointer-events: none;
    box-sizing: border-box;
}

.process-radar-ring-1 {
    width: 96px;
    height: 96px;
    animation: processRadarPulse 4s ease-in-out infinite;
}

.process-radar-ring-2 {
    width: 160px;
    height: 160px;
    border-color: rgba(255, 255, 255, 0.2);
    animation: processRadarPulse 4s ease-in-out infinite 1s;
}

.process-radar-ring-3 {
    width: 230px;
    height: 230px;
    border-color: rgba(255, 255, 255, 0.12);
    animation: processRadarPulse 4s ease-in-out infinite 2s;
}

@keyframes processRadarPulse {
    0%, 100% {
        transform: scale(0.96);
        opacity: 0.6;
    }
    50% {
        transform: scale(1.06);
        opacity: 1;
    }
}

/* Center Upload Hub */
.process-upload-hub {
    width: 58px;
    height: 58px;
    border-radius: 50%;
    background: #FFFFFF;
    color: #4F48E6;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 0 28px rgba(255, 255, 255, 0.5), 0 8px 20px rgba(0, 6, 35, 0.3);
    z-index: 5;
    animation: processHubBob 3s ease-in-out infinite;
}

@keyframes processHubBob {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-4px); }
}

/* Orbiting / Floating Document Pills */
.process-orbit-pill {
    position: absolute;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 5px 12px;
    border-radius: 9999px;
    background: rgba(255, 255, 255, 0.16);
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
    border: 1px solid rgba(255, 255, 255, 0.28);
    color: #FFFFFF;
    font-size: 11px;
    font-weight: 600;
    letter-spacing: 0.01em;
    z-index: 6;
    white-space: nowrap;
}

.process-orbit-1 {
    top: 22px;
    left: 20px;
    animation: processOrbitDrift1 5s ease-in-out infinite;
}

.process-orbit-2 {
    top: 26px;
    right: 20px;
    animation: processOrbitDrift2 5s ease-in-out infinite 0.7s;
}

.process-orbit-3 {
    bottom: 84px;
    right: 22px;
    animation: processOrbitDrift3 5s ease-in-out infinite 1.4s;
}

@keyframes processOrbitDrift1 {
    0%, 100% { transform: translate(0, 0); }
    50% { transform: translate(4px, -5px); }
}

@keyframes processOrbitDrift2 {
    0%, 100% { transform: translate(0, 0); }
    50% { transform: translate(-4px, 4px); }
}

@keyframes processOrbitDrift3 {
    0%, 100% { transform: translate(0, 0); }
    50% { transform: translate(-3px, -4px); }
}

/* Floating Intake Complete Status Bar */
.process-intake-badge {
    position: absolute;
    bottom: 16px;
    left: 50%;
    transform: translateX(-50%);
    background: #FFFFFF;
    border-radius: 9999px;
    padding: 6px 14px 6px 8px;
    display: inline-flex;
    align-items: center;
    gap: 10px;
    box-shadow: 0 10px 25px rgba(0, 6, 35, 0.28);
    z-index: 10;
    white-space: nowrap;
}

.process-intake-avatar {
    width: 28px;
    height: 28px;
    border-radius: 50%;
    object-fit: contain;
    background: #EEF2FF;
    border: 1.5px solid #C7D2FE;
    flex-shrink: 0;
}

.process-intake-text {
    font-size: 12px;
    font-weight: 700;
    color: #000623;
}

.process-intake-check {
    width: 18px;
    height: 18px;
    border-radius: 50%;
    background: #16A34A;
    color: #FFFFFF;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

/* ─── Card 3: Full Width AI Scheduling & Select Candidate ─── */
.process-card-full {
    grid-column: 1 / -1;
    display: grid;
    grid-template-columns: minmax(0, 1fr);
    gap: 36px;
    align-items: center;
    padding: 36px;
}

@media (min-width: 1024px) {
    .process-card-full {
        grid-template-columns: minmax(0, 1.1fr) minmax(0, 0.9fr);
        gap: 44px;
        padding: 44px;
    }
}

@media (max-width: 640px) {
    .process-card-full {
        padding: 24px 18px;
        gap: 28px;
    }
}

.process-full-left {
    display: flex;
    flex-direction: column;
    gap: 18px;
    width: 100%;
    box-sizing: border-box;
}

.process-ai-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 5px 12px;
    border-radius: 9999px;
    background: #EEF2FF;
    border: 1px solid #C7D2FE;
    color: #4F48E6;
    font-size: 12px;
    font-weight: 700;
    letter-spacing: 0.01em;
    width: fit-content;
}

.process-ai-icon {
    width: 16px;
    height: 16px;
    object-fit: contain;
    border-radius: 50%;
}

.process-full-title {
    font-size: clamp(24px, 2.8vw, 34px);
    font-weight: 800;
    color: #000623;
    line-height: 1.25;
    margin: 0;
    letter-spacing: -0.02em;
}

.process-full-desc {
    font-size: 15px;
    line-height: 1.65;
    color: #475569;
    margin: 0;
}

.process-features-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
    margin-top: 4px;
}

.process-feature-item {
    display: flex;
    align-items: flex-start;
    gap: 12px;
}

.process-feature-dot {
    width: 24px;
    height: 24px;
    border-radius: 50%;
    background: #EEF2FF;
    color: #4F48E6;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    margin-top: 2px;
}

.process-feature-text {
    font-size: 13.5px;
    color: #334155;
    line-height: 1.5;
}

.process-feature-text strong {
    color: #000623;
    font-weight: 700;
}

.process-btn-cta {
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    gap: 8px !important;
    padding: 12px 26px !important;
    border-radius: 12px !important;
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
    margin-top: 8px;
}

.process-btn-cta:hover {
    background-color: #4338CA !important;
    box-shadow: 0 6px 18px rgba(79, 72, 230, 0.45) !important;
    transform: translateY(-1px);
}

/* ─── Right Calendar Mockup Stage ─── */
.process-calendar-stage {
    position: relative;
    background: #F8FAFC;
    border: 1px solid #E2E8F0;
    border-radius: 20px;
    padding: 28px 24px 34px 24px;
    display: flex;
    justify-content: center;
    align-items: center;
    width: 100%;
    box-sizing: border-box;
}

.process-calendar-card {
    position: relative;
    background: #FFFFFF;
    border: 1px solid #E2E8F0;
    border-radius: 16px;
    padding: 20px;
    box-shadow: 0 8px 24px -4px rgba(0, 6, 35, 0.06);
    width: 100%;
    max-width: 320px;
    box-sizing: border-box;
}

/* Calendar Header with Interactive Month Switching */
.process-cal-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 14px;
    gap: 8px;
}

.process-cal-month-nav {
    display: flex;
    align-items: center;
    gap: 8px;
}

.process-cal-month {
    font-size: 14.5px;
    font-weight: 700;
    color: #000623;
    min-width: 116px;
}

.process-cal-arrow-btn {
    width: 24px;
    height: 24px;
    border-radius: 50%;
    border: 1px solid #E2E8F0;
    background: #FFFFFF;
    color: #94A3B8;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    pointer-events: none !important;
    cursor: default !important;
    padding: 0;
    flex-shrink: 0;
}

.process-cal-tz {
    font-size: 11px;
    font-weight: 600;
    color: #4F48E6;
    background: #EEF2FF;
    padding: 3px 8px;
    border-radius: 9999px;
    border: 1px solid #C7D2FE;
    white-space: nowrap;
}

.process-cal-weekdays {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    text-align: center;
    font-size: 11px;
    font-weight: 600;
    color: #94A3B8;
    margin-bottom: 8px;
}

.process-cal-grid {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 4px;
    text-align: center;
}

.process-cal-day {
    font-size: 12px;
    color: #334155;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    padding: 0;
    pointer-events: none !important;
    cursor: default !important;
    user-select: none !important;
}

/* Strict 1:1 Mathematical Circle for Date Numbers */
.process-cal-day-num {
    width: 28px !important;
    height: 28px !important;
    min-width: 28px !important;
    min-height: 28px !important;
    max-width: 28px !important;
    max-height: 28px !important;
    border-radius: 50% !important;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    font-weight: 500;
    color: #334155;
    transition: all 0.22s cubic-bezier(0.16, 1, 0.3, 1);
    position: relative;
    box-sizing: border-box !important;
}

.process-cal-day.is-muted .process-cal-day-num {
    color: #CBD5E1;
}

.process-cal-day.is-active .process-cal-day-num {
    background: #4F48E6 !important;
    color: #FFFFFF !important;
    font-weight: 700 !important;
    box-shadow: 0 4px 14px rgba(79, 72, 230, 0.45) !important;
    animation: processDatePulse 2.5s infinite ease-in-out;
}

.process-cal-day.is-accepted .process-cal-day-num {
    animation: processDateRipple 0.55s ease-out;
}

@keyframes processDateRipple {
    0% {
        transform: scale(0.8);
        box-shadow: 0 0 0 0 rgba(79, 72, 230, 0.7);
    }
    50% {
        transform: scale(1.18);
        box-shadow: 0 0 0 7px rgba(79, 72, 230, 0);
    }
    100% {
        transform: scale(1);
    }
}

.process-cal-day.is-available:not(.is-active):not(.is-muted) .process-cal-day-num::after {
    content: '';
    position: absolute;
    bottom: 1.5px;
    width: 4px;
    height: 4px;
    border-radius: 50%;
    background: #4F48E6;
}

@keyframes processDatePulse {
    0%, 100% {
        box-shadow: 0 0 0 0 rgba(79, 72, 230, 0.4);
    }
    50% {
        box-shadow: 0 0 0 6px rgba(79, 72, 230, 0);
    }
}

/* Simulated Banner-Style Interactive Demo Cursor */
.process-cal-cursor {
    position: absolute;
    pointer-events: none;
    z-index: 25;
    display: flex;
    align-items: center;
    gap: 4px;
    transition: top 0.65s cubic-bezier(0.25, 1, 0.5, 1), left 0.65s cubic-bezier(0.25, 1, 0.5, 1), transform 0.2s ease;
}

.process-cal-cursor.is-clicking {
    transform: scale(0.82);
}

.process-cursor-pill {
    font-size: 10px;
    font-weight: 700;
    background: #000623;
    color: #FFFFFF;
    padding: 2px 7px;
    border-radius: 9999px;
    box-shadow: 0 2px 8px rgba(0, 6, 35, 0.25);
    white-space: nowrap;
}

/* Floating Upcoming Interview Card */
.process-floating-interview {
    position: absolute;
    bottom: -18px;
    left: -8px;
    background: #FFFFFF;
    border: 1px solid #E2E8F0;
    border-radius: 16px;
    padding: 11px 14px;
    box-shadow: 0 14px 34px -4px rgba(0, 6, 35, 0.12), 0 3px 10px -2px rgba(0, 6, 35, 0.04);
    display: flex;
    flex-direction: column;
    gap: 8px;
    width: calc(100% + 16px);
    max-width: 352px;
    box-sizing: border-box;
    z-index: 10;
    animation: processFloatUp 4s ease-in-out infinite;
    transition: border-color 0.3s ease;
}

.process-floating-interview.is-confirmed {
    border-color: #BBF7D0;
}

@keyframes processFloatUp {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-5px); }
}

.process-interview-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 6px;
    width: 100%;
    white-space: nowrap !important;
    flex-shrink: 0 !important;
}

.process-interview-title-wrap {
    display: flex;
    align-items: center;
    gap: 6px;
    white-space: nowrap !important;
    flex-shrink: 0 !important;
}

.process-interview-title {
    font-size: 11px;
    font-weight: 700;
    color: #000623;
    white-space: nowrap !important;
    flex-shrink: 0 !important;
    letter-spacing: -0.01em;
}

.process-interview-confirmed-badge {
    display: inline-flex;
    align-items: center;
    gap: 3px;
    padding: 2px 6px;
    border-radius: 9999px;
    background: #DCFCE7;
    color: #15803D;
    font-size: 9.5px;
    font-weight: 700;
    white-space: nowrap !important;
    flex-shrink: 0 !important;
    animation: processPulseCheck 0.35s cubic-bezier(0.16, 1, 0.3, 1);
}

@keyframes processPulseCheck {
    0% { transform: scale(0.8); opacity: 0; }
    100% { transform: scale(1); opacity: 1; }
}

.process-interview-time {
    font-size: 10.5px;
    font-weight: 600;
    color: #4F48E6;
    white-space: nowrap !important;
    flex-shrink: 0 !important;
    margin-left: auto;
}

.process-interview-body {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
}

.process-interview-candidate {
    display: flex;
    align-items: center;
    gap: 8px;
    min-width: 0;
}

.process-interview-avatar {
    width: 28px;
    height: 28px;
    border-radius: 50%;
    object-fit: cover;
    flex-shrink: 0;
}

.process-interview-name {
    font-size: 12px;
    font-weight: 700;
    color: #0F172A;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.process-interview-btn {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 4px 10px;
    border-radius: 9999px;
    background: #F0FDF4;
    color: #16A34A;
    border: 1px solid #DCFCE7;
    font-size: 11px;
    font-weight: 700;
    white-space: nowrap;
    flex-shrink: 0;
}

.process-interview-live-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: #16A34A;
}

/* Floating AI Match Pill */
.process-floating-match {
    position: absolute;
    top: -12px;
    right: 14px;
    background: #000623;
    color: #FFFFFF;
    border-radius: 9999px;
    padding: 6px 14px;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    box-shadow: 0 8px 24px rgba(0, 6, 35, 0.2);
    z-index: 10;
    white-space: nowrap;
    animation: processFloatDown 4s ease-in-out infinite 0.8s;
}

@keyframes processFloatDown {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(4px); }
}

.process-match-icon {
    width: 14px;
    height: 14px;
    object-fit: contain;
    border-radius: 50%;
}

.process-match-text {
    font-size: 11.5px;
    font-weight: 700;
    color: #FFFFFF;
}

.process-match-sub {
    color: #94A3B8;
    font-weight: 500;
}

@media (max-width: 640px) {
    .process-section-wrap {
        padding: 32px 0 60px 0;
    }
    .process-inner-container {
        padding: 0 16px;
    }
    .process-header-box {
        margin-bottom: 28px;
        gap: 12px;
    }
    .process-headline-title {
        font-size: clamp(24px, 6.2vw, 34px);
        line-height: 1.22;
    }
    .process-subtext-para {
        font-size: 14px;
        line-height: 1.55;
    }
    .process-card {
        padding: 18px 14px;
        border-radius: 18px;
    }
    .process-card-full {
        padding: 20px 14px;
        gap: 24px;
    }
    .process-profile-stage {
        padding: 12px 10px;
        gap: 10px;
    }
    .process-profile-row {
        padding: 10px 12px;
        gap: 8px;
    }
    .process-profile-label {
        font-size: 12.5px;
    }
    .process-profile-sub {
        font-size: 11px;
    }
    .process-radar-stage {
        height: 200px;
    }
    .process-orbit-pill {
        font-size: 9.5px;
        padding: 3px 8px;
        gap: 4px;
    }
    .process-orbit-1 {
        top: 12px;
        left: 10px;
    }
    .process-orbit-2 {
        top: 12px;
        right: 10px;
    }
    .process-orbit-3 {
        display: none !important;
    }
    .process-intake-badge {
        bottom: 10px;
        padding: 4px 10px 4px 6px;
        gap: 6px;
        max-width: calc(100% - 20px);
    }
    .process-intake-avatar {
        width: 24px;
        height: 24px;
    }
    .process-intake-text {
        font-size: 10.5px;
    }
    .process-calendar-stage {
        padding: 24px 12px;
        flex-direction: column;
        gap: 14px;
        overflow: hidden;
    }
    .process-floating-match {
        position: relative;
        top: auto;
        right: auto;
        margin-bottom: 4px;
        font-size: 10.5px;
        padding: 4px 10px;
        align-self: center;
    }
    .process-calendar-card {
        max-width: 100%;
        padding: 14px 12px;
    }
    .process-floating-interview {
        position: relative;
        bottom: auto;
        left: auto;
        width: 100%;
        max-width: 100%;
        margin-top: 8px;
        padding: 9px 12px;
        animation: none;
        box-sizing: border-box;
    }
    .process-interview-header {
        white-space: nowrap !important;
        flex-shrink: 0 !important;
        gap: 4px !important;
    }
    .process-interview-title {
        font-size: 10.5px !important;
        white-space: nowrap !important;
        flex-shrink: 0 !important;
    }
    .process-interview-confirmed-badge {
        font-size: 8.5px !important;
        padding: 1.5px 5px !important;
        white-space: nowrap !important;
        flex-shrink: 0 !important;
    }
    .process-interview-time {
        font-size: 9.5px !important;
        white-space: nowrap !important;
        flex-shrink: 0 !important;
    }
    .process-interview-name {
        font-size: 11px !important;
        white-space: nowrap !important;
    }
    .process-interview-btn {
        font-size: 9.5px !important;
        padding: 3px 8px !important;
        white-space: nowrap !important;
        flex-shrink: 0 !important;
    }
    .process-cal-cursor {
        display: none !important; /* Hide simulated cursor on mobile touch */
    }
}
</style>

<section id="process-section" class="process-section-wrap">
    <div class="process-inner-container">
        <!-- Section Header -->
        <div class="process-header-box">
            <span class="process-eyebrow-pill">
                <span class="process-pulse-dot"></span>
                Streamlined Platform Architecture
            </span>
            <h2 class="process-headline-title">
                Effortless Process,<br>
                <span class="process-dream-accent">Exceptional Results</span>
            </h2>
            <p class="process-subtext-para">
                Our AI-guided ecosystem simplifies how candidates discover, verify, and interview for top internships—delivering clarity, speed, and confidence at every step.
            </p>
        </div>

        <!-- Bento Grid Canvas Wrapper with Animated Flowing Pipeline Beams -->
        <div class="process-bento-canvas-wrap">
            
            <!-- SVG Flow Circuit Stream Overlay (Desktop & Tablet: Center Origin, Left Purple / Right Dark Mode Navy Split, Generous Card Clearance) -->
            <svg class="process-bento-flow-svg" viewBox="0 0 1000 1000" preserveAspectRatio="none" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <defs>
                    <!-- Left Stream Gradient: Vibrant Purple/Violet into Brand Indigo -->
                    <linearGradient id="processFlowLeftGrad" x1="50%" y1="0%" x2="50%" y2="100%">
                        <stop offset="0%" stop-color="#4F48E6"/>
                        <stop offset="30%" stop-color="#7C3AED"/>
                        <stop offset="70%" stop-color="#8B5CF6"/>
                        <stop offset="100%" stop-color="#4F48E6"/>
                    </linearGradient>

                    <!-- Right Stream Gradient: Official InternConnect Brand Dark Mode Navy -->
                    <linearGradient id="processFlowDarkGrad" x1="50%" y1="0%" x2="50%" y2="100%">
                        <stop offset="0%" stop-color="#000623"/>
                        <stop offset="30%" stop-color="#1E293B"/>
                        <stop offset="70%" stop-color="#0F172A"/>
                        <stop offset="100%" stop-color="#000623"/>
                    </linearGradient>

                    <!-- Glow Filters -->
                    <filter id="processGlowPurple" x="-25%" y="-25%" width="150%" height="150%">
                        <feGaussianBlur stdDeviation="3" result="blur"/>
                        <feMerge>
                            <feMergeNode in="blur"/>
                            <feMergeNode in="SourceGraphic"/>
                        </feMerge>
                    </filter>

                    <filter id="processGlowDark" x="-25%" y="-25%" width="150%" height="150%">
                        <feGaussianBlur stdDeviation="2.5" result="blur"/>
                        <feMerge>
                            <feMergeNode in="blur"/>
                            <feMergeNode in="SourceGraphic"/>
                        </feMerge>
                    </filter>
                </defs>

                <!-- Left Branch (Purple): Starts at Top-Center (500, 4), runs down center gap to 445, turns left across horizontal gap at Y=467, runs down left outside Card 3 at X=11, curves along bottom at Y=981 to (497, 981) and continues down to (497, 1000) -->
                <path class="process-flow-track" d="M 500 4 L 500 445 Q 500 467, 475 467 L 33 467 Q 11 467, 11 489 L 11 959 Q 11 981, 33 981 L 475 981 Q 497 981, 497 988 L 497 1000" />
                <path class="process-flow-beam process-beam-left" d="M 500 4 L 500 445 Q 500 467, 475 467 L 33 467 Q 11 467, 11 489 L 11 959 Q 11 981, 33 981 L 475 981 Q 497 981, 497 988 L 497 1000" stroke="url(#processFlowLeftGrad)" filter="url(#processGlowPurple)" />

                <!-- Right Branch (Dark Mode Navy): Starts at Top-Center (500, 4), runs down center gap to 445, turns right across horizontal gap at Y=467, runs down right outside Card 3 at X=989, curves along bottom at Y=981 to (503, 981) and continues down to (503, 1000) -->
                <path class="process-flow-track" d="M 500 4 L 500 445 Q 500 467, 525 467 L 967 467 Q 989 467, 989 489 L 989 959 Q 989 981, 967 981 L 525 981 Q 503 981, 503 988 L 503 1000" />
                <path class="process-flow-beam process-beam-right" d="M 500 4 L 500 445 Q 500 467, 525 467 L 967 467 Q 989 467, 989 489 L 989 959 Q 989 981, 967 981 L 525 981 Q 503 981, 503 988 L 503 1000" stroke="url(#processFlowDarkGrad)" filter="url(#processGlowDark)" />

                <!-- Top Center Origin Node (500, 4) -->
                <circle cx="500" cy="4" r="5" fill="#4F48E6" stroke="#FFFFFF" stroke-width="2" />

                <!-- Bottom Center Destination Convergence Node where Enterprise flow cleanly finishes -->
                <circle cx="500" cy="981" r="6" fill="#000623" stroke="#FFFFFF" stroke-width="2" />
                <circle class="process-node-pulse" cx="500" cy="981" r="14" fill="#000623" opacity="0.2" />
            </svg>

            <!-- Bento Grid System -->
            <div class="process-bento-grid">

                <!-- Card 1 (Top Left): Complete Your Profile -->
                <div class="process-card">

                    <!-- Visual Stage -->
                    <div class="process-profile-stage">
                        <!-- Row 1: Curriculum Vitae -->
                        <div class="process-profile-row process-row-animated">
                            <div class="process-profile-left">
                                <div class="process-icon-box process-icon-red">
                                    <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v6a1 1 0 001 1h6" />
                                    </svg>
                                </div>
                                <div class="process-profile-info">
                                    <span class="process-profile-label">Curriculum Vitae</span>
                                    <span class="process-profile-sub">PDF · ATS Optimized</span>
                                </div>
                            </div>
                            <div class="process-check-circle" title="Verified Asset">
                                <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                        </div>

                        <!-- Row 2: Personal & Identity Data -->
                        <div class="process-profile-row process-row-animated">
                            <div class="process-profile-left">
                                <div class="process-icon-box process-icon-blue">
                                    <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <div class="process-profile-info">
                                    <span class="process-profile-label">Personal & Identity Data</span>
                                    <span class="process-profile-sub">Verified Candidate Dossier</span>
                                </div>
                            </div>
                            <div class="process-check-circle" title="Verified Asset">
                                <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                        </div>

                        <!-- Row 3: Academic Credentials (Updated subtext: University Certification Checkup) -->
                        <div class="process-profile-row process-row-animated">
                            <div class="process-profile-left">
                                <div class="process-icon-box process-icon-green">
                                    <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                                    </svg>
                                </div>
                                <div class="process-profile-info">
                                    <span class="process-profile-label">Academic Information</span>
                                    <span class="process-profile-sub">University Certification Checkup</span>
                                </div>
                            </div>
                            <div class="process-check-circle" title="Verified Asset">
                                <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Card Content (Generously Spaced) -->
                    <div class="process-card-content">
                        <h3 class="process-card-title">Complete Your Profile</h3>
                        <p class="process-card-desc">
                            Maximize your interview prospects. InternConnect structures your academic history, verified achievements, and resumes into a recruiter-ready dossier.
                        </p>
                        <div class="process-card-tags">
                            <span class="process-card-tag">ATS Scored</span>
                            <span class="process-card-tag">Credential Verified</span>
                            <span class="process-card-tag">Auto-Sync</span>
                        </div>
                    </div>
                </div>

                <!-- Card 2 (Top Right): Direct Portfolio Upload & Radar -->
                <div class="process-card">

                    <!-- Visual Canvas with Concentric Radar Waves -->
                    <div class="process-radar-stage">
                        <!-- Concentric Breathing Rings -->
                        <div class="process-radar-ring process-radar-ring-1" aria-hidden="true"></div>
                        <div class="process-radar-ring process-radar-ring-2" aria-hidden="true"></div>
                        <div class="process-radar-ring process-radar-ring-3" aria-hidden="true"></div>

                        <!-- Center Glowing Upload Hub -->
                        <div class="process-upload-hub">
                            <svg width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                            </svg>
                        </div>

                        <!-- Floating Orbiting Artifact Pills -->
                        <div class="process-orbit-pill process-orbit-1">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10" />
                                <path d="M12 2a14.5 14.5 0 0 0 0 20 14.5 14.5 0 0 0 0-20" />
                                <path d="M2 12h20" />
                            </svg>
                            <span>Figma Prototype</span>
                        </div>

                        <div class="process-orbit-pill process-orbit-2">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="16 18 22 12 16 6" />
                                <polyline points="8 6 2 12 8 18" />
                            </svg>
                            <span>GitHub Code</span>
                        </div>

                        <div class="process-orbit-pill process-orbit-3">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                                <polyline points="14 2 14 8 20 8" />
                            </svg>
                            <span>Case Study PDF</span>
                        </div>

                        <!-- Floating Jenny AI Status Pill -->
                        <div class="process-intake-badge">
                            <img src="<?= asset('assets/images/jenny_ai/jenny_ai_hi.png') ?>" alt="Jenny AI" class="process-intake-avatar">
                            <span class="process-intake-text">AI Intake Complete</span>
                            <div class="process-intake-check">
                                <svg width="11" height="11" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Card Content (Generously Spaced) -->
                    <div class="process-card-content">
                        <h3 class="process-card-title">Direct Portfolio Upload</h3>
                        <p class="process-card-desc">
                            Showcase real-world capability. Upload interactive prototypes, code repositories, and project portfolios that recruiters can explore directly in-platform.
                        </p>
                        <div class="process-card-tags">
                            <span class="process-card-tag">Live Artifacts</span>
                            <span class="process-card-tag">Instant Indexing</span>
                            <span class="process-card-tag">Recruiter Ready</span>
                        </div>
                    </div>
                </div>

                <!-- Card 3 (Bottom): AI-Powered Scheduling & Match Coordination -->
                <div class="process-card process-card-full">
                    <!-- Left Column: Copy & Actions -->
                    <div class="process-full-left">
                        <div class="process-ai-badge">
                            <img src="<?= asset('assets/images/jenny_ai/jenny_icon.png') ?>" alt="Jenny AI" class="process-ai-icon">
                            <span>Powered by Jenny AI Coordination</span>
                        </div>

                        <h3 class="process-full-title">
                            Scheduling Interview & Select Candidate
                        </h3>

                        <p class="process-full-desc">
                            Eliminate hiring friction. Jenny AI harmonizes candidate and recruiter time zones, coordinates slot availability, and provisions instant video interview rooms without endless emails.
                        </p>

                        <div class="process-features-list">
                            <div class="process-feature-item">
                                <div class="process-feature-dot">
                                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                                <div class="process-feature-text">
                                    <strong>Automatic Timezone Alignment:</strong> Coordinates candidate and recruiter hours seamlessly.
                                </div>
                            </div>

                            <div class="process-feature-item">
                                <div class="process-feature-dot">
                                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                                <div class="process-feature-text">
                                    <strong>In-Platform Video Rooms:</strong> Every interview gets a secure InternConnect video room, delivered to candidate dashboards.
                                </div>
                            </div>

                            <div class="process-feature-item">
                                <div class="process-feature-dot">
                                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                                <div class="process-feature-text">
                                    <strong>Instant AI Scorecard:</strong> Review 0–100% competency match scores prior to the interview call.
                                </div>
                            </div>
                        </div>

                        <a href="<?= url('/internships') ?>" class="process-btn-cta">
                            <span>Explore Opportunities</span>
                            <svg style="width: 16px; height: 16px; flex-shrink: 0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </a>
                    </div>

                    <!-- Right Column: Interactive Animated Calendar & Live Floating Cards -->
                    <div class="process-calendar-stage" 
                         x-data="processSchedulerComponent()" 
                         x-init="initScheduler()">

                        <!-- Calendar Widget Card -->
                        <div class="process-calendar-card" id="process-cal-container">
                            <!-- Simulated Banner-Style Interactive Demo Cursor -->
                            <div class="process-cal-cursor" 
                                 :class="{ 'is-clicking': isCursorClicking }"
                                 :style="cursorStyle" 
                                 x-show="showDemoCursor" 
                                 x-transition:enter="transition ease-out duration-300"
                                 x-transition:enter-start="opacity-0 scale-75"
                                 x-transition:enter-end="opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-200"
                                 x-transition:leave-start="opacity-100 scale-100"
                                 x-transition:leave-end="opacity-0 scale-75">
                                <svg class="process-cursor-arrow" width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M4.5 3L11.5 21L14.5 13.5L22 10.5L4.5 3Z" fill="#000623" stroke="#FFFFFF" stroke-width="2" stroke-linejoin="round"/>
                                </svg>
                                <span class="process-cursor-pill">Recruiter</span>
                            </div>

                            <!-- Header with Month Display -->
                            <div class="process-cal-header">
                                <div class="process-cal-month-nav">
                                    <div class="process-cal-arrow-btn" aria-hidden="true">
                                        <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                                        </svg>
                                    </div>
                                    <span class="process-cal-month" x-text="currentMonthData.name">December 2026</span>
                                    <div class="process-cal-arrow-btn" aria-hidden="true">
                                        <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </div>
                                </div>
                                <span class="process-cal-tz" x-text="currentMonthData.tz">WAT (UTC+1)</span>
                            </div>

                            <!-- Weekday Column Headers -->
                            <div class="process-cal-weekdays">
                                <span>Mo</span>
                                <span>Tu</span>
                                <span>We</span>
                                <span>Th</span>
                                <span>Fr</span>
                                <span>Sa</span>
                                <span>Su</span>
                            </div>

                            <!-- Dynamic Autonomous Days Grid (True 1:1 Circle, Non-Clickable) -->
                            <div class="process-cal-grid">
                                <!-- Leading Muted Days -->
                                <template x-for="prevDay in currentMonthData.leadingMuted" :key="'prev-' + prevDay">
                                    <div class="process-cal-day is-muted">
                                        <span class="process-cal-day-num" x-text="prevDay"></span>
                                    </div>
                                </template>

                                <!-- Active Month Days -->
                                <template x-for="day in currentMonthData.daysCount" :key="'day-' + day">
                                    <div class="process-cal-day" 
                                         :id="'cal-day-' + monthIndex + '-' + day"
                                         :class="{ 
                                             'is-active': selectedDay === day && selectedMonth === monthIndex,
                                             'is-accepted': justAccepted && selectedDay === day && selectedMonth === monthIndex,
                                             'is-available': isDayAvailable(day)
                                         }">
                                        <span class="process-cal-day-num" x-text="day"></span>
                                    </div>
                                </template>

                                <!-- Trailing Muted Days -->
                                <template x-for="nextDay in currentMonthData.trailingMuted" :key="'next-' + nextDay">
                                    <div class="process-cal-day is-muted">
                                        <span class="process-cal-day-num" x-text="nextDay"></span>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <!-- Floating AI Candidate Match Score Badge (Top Right) -->
                        <div class="process-floating-match">
                            <img src="<?= asset('assets/images/jenny_ai/jenny_icon.png') ?>" alt="Jenny AI" class="process-match-icon">
                            <span class="process-match-text" x-text="currentCandidate.matchScore + ' Match Score'">95% Match Score</span>
                            <span class="process-match-sub" x-text="'· ' + currentCandidate.role">· UI/UX Role</span>
                        </div>

                        <!-- Floating Upcoming Interview Card (Bottom Left) -->
                        <div class="process-floating-interview" :class="{ 'is-confirmed': justAccepted }">
                            <div class="process-interview-header">
                                <div class="process-interview-title-wrap">
                                    <span class="process-interview-title">Upcoming Interview</span>
                                    <span class="process-interview-confirmed-badge" x-show="justAccepted" x-transition>
                                        <svg width="10" height="10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                        </svg>
                                        Slot Reserved
                                    </span>
                                </div>
                                <span class="process-interview-time" x-text="currentFormattedTime">Tue · 09:30–10:15</span>
                            </div>
                            <div class="process-interview-body">
                                <div class="process-interview-candidate">
                                    <img src="<?= asset('assets/images/jenny_ai/jenny_ai_hi.png') ?>" alt="Candidate" class="process-interview-avatar">
                                    <span class="process-interview-name" x-text="currentCandidate.name">Sarah Peters</span>
                                </div>
                                <span class="process-interview-btn">
                                    <span class="process-interview-live-dot"></span>
                                    <span>Join Now</span>
                                </span>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>
</section>

<script>
/**
 * Autonomous Scheduler Showcase Component with True 1:1 Circle & Auto-Tour
 */
function processSchedulerComponent() {
    return {
        monthIndex: 1, // Default December 2026 (matches user screenshot)
        selectedDay: 15,
        selectedMonth: 1,
        justAccepted: true,
        showDemoCursor: true,
        isCursorClicking: false,
        cursorX: 180,
        cursorY: 140,
        demoStep: 0,
        demoTimer: null,

        candidates: {
            '1-15': { name: 'Sarah Peters', role: 'UI/UX Role', matchScore: '95%' },
            '1-18': { name: 'David Okonjo', role: 'Frontend Engineer', matchScore: '98%' },
            '1-8': { name: 'Amara Vance', role: 'Product Design', matchScore: '92%' },
            '0-20': { name: 'Kelechi Eze', role: 'Backend Intern', matchScore: '94%' },
            '0-18': { name: 'Sarah Peters', role: 'UI/UX Role', matchScore: '95%' }
        },

        monthsData: [
            {
                name: 'November 2026',
                tz: 'WAT (UTC+1)',
                daysCount: 30,
                leadingMuted: [27, 28, 29, 30],
                trailingMuted: [1],
                availableDays: [18, 19, 20, 21, 24, 26],
                slotTimes: {
                    18: 'Wed · 10:30–11:15',
                    19: 'Thu · 14:00–14:45',
                    20: 'Fri · 11:30–12:15',
                    21: 'Sat · 10:00–10:45',
                    24: 'Tue · 15:30–16:15',
                    26: 'Thu · 13:00–13:45'
                }
            },
            {
                name: 'December 2026',
                tz: 'WAT (UTC+1)',
                daysCount: 31,
                leadingMuted: [30],
                trailingMuted: [1, 2, 3],
                availableDays: [3, 8, 10, 15, 18, 22],
                slotTimes: {
                    3: 'Thu · 10:00–10:45',
                    8: 'Tue · 11:30–12:15',
                    10: 'Thu · 14:00–14:45',
                    15: 'Tue · 09:30–10:15',
                    18: 'Fri · 16:00–16:45',
                    22: 'Tue · 11:00–11:45'
                }
            }
        ],

        get currentMonthData() {
            return this.monthsData[this.monthIndex] || this.monthsData[1];
        },

        get currentCandidate() {
            const key = `${this.selectedMonth}-${this.selectedDay}`;
            return this.candidates[key] || { name: 'Sarah Peters', role: 'UI/UX Role', matchScore: '95%' };
        },

        get currentFormattedTime() {
            const m = this.monthsData[this.selectedMonth] || this.currentMonthData;
            return m.slotTimes[this.selectedDay] || (this.selectedDay + ' ' + m.name.substring(0, 3) + ' · 10:00–10:45');
        },

        get cursorStyle() {
            return `top: ${this.cursorY}px; left: ${this.cursorX}px;`;
        },

        isDayAvailable(day) {
            return this.currentMonthData.availableDays.includes(day);
        },

        initScheduler() {
            this.$nextTick(() => {
                this.updateCursorPositionToDay(15);
                setTimeout(() => {
                    this.justAccepted = false;
                }, 2000);
                this.startDemoLoop(2400);
            });
        },

        startDemoLoop(initialDelay = 2400) {
            if (window.innerWidth <= 640) return; // Skip simulated cursor on small mobile

            const demoSequence = [
                { month: 1, day: 15 },
                { month: 1, day: 18 },
                { month: 1, day: 8 },
                { month: 0, day: 20 },
                { month: 0, day: 18 }
            ];

            setTimeout(() => {
                this.demoTimer = setInterval(() => {
                    this.demoStep = (this.demoStep + 1) % demoSequence.length;
                    const target = demoSequence[this.demoStep];

                    // Switch month if needed
                    if (this.monthIndex !== target.month) {
                        this.monthIndex = target.month;
                    }

                    this.$nextTick(() => {
                        this.updateCursorPositionToDay(target.day);

                        // Trigger click tap after cursor arrives
                        setTimeout(() => {
                            this.isCursorClicking = true;
                            this.selectedDay = target.day;
                            this.selectedMonth = target.month;
                            this.justAccepted = true;

                            setTimeout(() => {
                                this.isCursorClicking = false;
                            }, 220);

                            setTimeout(() => {
                                this.justAccepted = false;
                            }, 2000);
                        }, 500);
                    });
                }, 3800);
            }, initialDelay);
        },

        updateCursorPositionToDay(day) {
            const container = document.getElementById('process-cal-container');
            const dayEl = document.getElementById(`cal-day-${this.monthIndex}-${day}`);

            if (container && dayEl) {
                const numEl = dayEl.querySelector('.process-cal-day-num') || dayEl;
                const cRect = container.getBoundingClientRect();
                const dRect = numEl.getBoundingClientRect();
                this.cursorX = Math.round(dRect.left - cRect.left + dRect.width / 2 - 3);
                this.cursorY = Math.round(dRect.top - cRect.top + dRect.height / 2 - 2);
            }
        }
    };
}

/**
 * Scroll-Driven Circuit Flow Engine (Effortless Process -> Meet Jenny Conduit Flow)
 * Dynamically binds SVG laser pulse progression to page scroll position with seamless inter-section handoff.
 */
document.addEventListener('DOMContentLoaded', function() {
    // Only execute on desktop displays where SVG flow circuits are active
    if (window.innerWidth < 1024) return;

    const processSection = document.getElementById('process-section');
    const jennySection = document.getElementById('meet-jenny-block');
    if (!processSection || !jennySection) return;

    const pBeamLeft = processSection.querySelector('.process-beam-left');
    const pBeamRight = processSection.querySelector('.process-beam-right');
    const jBeamLeft = jennySection.querySelector('.meet-jenny-beam-left');
    const jBeamRight = jennySection.querySelector('.meet-jenny-beam-right');
    const pPulseNode = processSection.querySelector('.process-node-pulse');
    const jPulseNode = jennySection.querySelector('.jenny-node-pulse');

    if (!pBeamLeft || !jBeamLeft) return;

    let lenPLeft = 0;
    let lenPRight = 0;
    let lenJLeft = 0;
    let lenJRight = 0;
    const beamLen = 220; // Length of traveling energy pulse

    function initPathLengths() {
        lenPLeft = pBeamLeft.getTotalLength();
        lenPRight = pBeamRight ? pBeamRight.getTotalLength() : lenPLeft;
        lenJLeft = jBeamLeft.getTotalLength();
        lenJRight = jBeamRight ? jBeamRight.getTotalLength() : lenJLeft;

        pBeamLeft.style.strokeDasharray = `${beamLen} ${lenPLeft + beamLen}`;
        if (pBeamRight) pBeamRight.style.strokeDasharray = `${beamLen} ${lenPRight + beamLen}`;
        jBeamLeft.style.strokeDasharray = `${beamLen} ${lenJLeft + beamLen}`;
        if (jBeamRight) jBeamRight.style.strokeDasharray = `${beamLen} ${lenJRight + beamLen}`;
    }

    initPathLengths();

    let ticking = false;

    function updateCircuitFlow() {
        const vh = window.innerHeight;
        const scrollY = window.pageYOffset || document.documentElement.scrollTop;

        // Viewport trigger focus line (55% down screen)
        const triggerY = scrollY + vh * 0.55;

        // Measure Section Geometry
        const pRect = processSection.getBoundingClientRect();
        const pTop = pRect.top + scrollY;
        const pHeight = processSection.offsetHeight;

        const jRect = jennySection.getBoundingClientRect();
        const jTop = jRect.top + scrollY;
        const jHeight = jennySection.offsetHeight;

        // 1. Process Section Scroll Progress [0, 1]
        // Kicks in when reading line enters process section, completes at section bottom
        let pProg = (triggerY - (pTop + 40)) / (pHeight - 80);
        pProg = Math.max(0, Math.min(1, pProg));

        // 2. Jenny Section Scroll Progress [0, 1]
        // Starts exactly when process section concludes (seamless handoff through conduit)
        let jProg = (triggerY - jTop) / (jHeight - 100);
        jProg = Math.max(0, Math.min(1, jProg));

        // Animate Process Energy Beams
        if (pProg > 0 && pProg < 1) {
            pBeamLeft.style.opacity = '1';
            if (pBeamRight) pBeamRight.style.opacity = '1';

            const offsetPLeft = beamLen - pProg * (lenPLeft + beamLen);
            const offsetPRight = beamLen - pProg * (lenPRight + beamLen);

            pBeamLeft.style.strokeDashoffset = offsetPLeft;
            if (pBeamRight) pBeamRight.style.strokeDashoffset = offsetPRight;
        } else if (pProg >= 1) {
            pBeamLeft.style.opacity = '0';
            if (pBeamRight) pBeamRight.style.opacity = '0';
        } else {
            pBeamLeft.style.opacity = '0';
            if (pBeamRight) pBeamRight.style.opacity = '0';
        }

        // Process Convergence Node Glow Trigger
        if (pPulseNode) {
            if (pProg > 0.88 && pProg < 1.0) {
                pPulseNode.style.opacity = '0.75';
                pPulseNode.style.transform = 'scale(1.35)';
            } else {
                pPulseNode.style.opacity = '0';
                pPulseNode.style.transform = 'scale(1)';
            }
        }

        // Animate Jenny Energy Beams (crawls through vertical conduit lines first, then branches out)
        if (jProg > 0 && jProg < 1) {
            jBeamLeft.style.opacity = '1';
            if (jBeamRight) jBeamRight.style.opacity = '1';

            const offsetJLeft = beamLen - jProg * (lenJLeft + beamLen);
            const offsetJRight = beamLen - jProg * (lenJRight + beamLen);

            jBeamLeft.style.strokeDashoffset = offsetJLeft;
            if (jBeamRight) jBeamRight.style.strokeDashoffset = offsetJRight;
        } else {
            jBeamLeft.style.opacity = '0';
            if (jBeamRight) jBeamRight.style.opacity = '0';
        }

        // Jenny Convergence Node Glow Trigger
        if (jPulseNode) {
            if (jProg > 0.90 && jProg < 1.0) {
                jPulseNode.style.opacity = '0.75';
                jPulseNode.style.transform = 'scale(1.35)';
            } else {
                jPulseNode.style.opacity = '0';
                jPulseNode.style.transform = 'scale(1)';
            }
        }

        ticking = false;
    }

    function onScroll() {
        if (!ticking) {
            window.requestAnimationFrame(updateCircuitFlow);
            ticking = true;
        }
    }

    window.addEventListener('scroll', onScroll, { passive: true });
    window.addEventListener('resize', () => {
        if (window.innerWidth >= 1024) {
            initPathLengths();
            updateCircuitFlow();
        }
    });

    // Initial positioning
    updateCircuitFlow();
});
</script>
