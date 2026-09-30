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

@media (min-width: 768px) {
    .process-section-wrap {
        padding: 56px 0 100px 0;
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

/* ─── Bento Grid Layout ─── */
.process-bento-grid {
    display: grid;
    grid-template-columns: minmax(0, 1fr);
    gap: 28px;
    width: 100%;
    box-sizing: border-box;
}

@media (min-width: 1024px) {
    .process-bento-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 32px;
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
    justify-content: space-between;
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
        border-radius: 20px;
    }
}

.process-card-content {
    display: flex;
    flex-direction: column;
    gap: 10px;
    margin-top: 24px;
    width: 100%;
    box-sizing: border-box;
}

.process-card-title {
    font-size: 20px;
    font-weight: 800;
    color: #000623;
    line-height: 1.3;
    margin: 0;
    letter-spacing: -0.015em;
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
        box-shadow: 0 6px 18px rgba(79, 72, 230, 0.12);
        transform: scale(1.015);
    }
}

.process-profile-left {
    display: flex;
    align-items: center;
    gap: 12px;
    flex: 1;
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
    background: #FFF1F2;
    color: #E11D48;
    border: 1px solid #FFE4E6;
}

.process-icon-blue {
    background: #EEF2FF;
    color: #4F48E6;
    border: 1px solid #E0E7FF;
}

.process-icon-green {
    background: #F0FDF4;
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
    bottom: 24px;
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

/* ─── Card 3: Full Width AI-Powered Scheduling ─── */
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
    background: #FFFFFF;
    border: 1px solid #E2E8F0;
    border-radius: 16px;
    padding: 20px;
    box-shadow: 0 8px 24px -4px rgba(0, 6, 35, 0.06);
    width: 100%;
    max-width: 320px;
    box-sizing: border-box;
}

.process-cal-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 14px;
}

.process-cal-month {
    font-size: 14.5px;
    font-weight: 700;
    color: #000623;
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
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    font-weight: 500;
}

.process-cal-day.is-muted {
    color: #CBD5E1;
}

.process-cal-day.is-active {
    background: #4F48E6;
    color: #FFFFFF;
    font-weight: 700;
    border-radius: 50%;
    position: relative;
    box-shadow: 0 3px 10px rgba(79, 72, 230, 0.4);
    animation: processDatePulse 2.5s infinite ease-in-out;
}

@keyframes processDatePulse {
    0%, 100% {
        box-shadow: 0 0 0 0 rgba(79, 72, 230, 0.4);
    }
    50% {
        box-shadow: 0 0 0 6px rgba(79, 72, 230, 0);
    }
}

/* Floating Upcoming Interview Card */
.process-floating-interview {
    position: absolute;
    bottom: -16px;
    left: 12px;
    background: #FFFFFF;
    border: 1px solid #E2E8F0;
    border-radius: 16px;
    padding: 12px 16px;
    box-shadow: 0 14px 34px -4px rgba(0, 6, 35, 0.12);
    display: flex;
    flex-direction: column;
    gap: 8px;
    max-width: 280px;
    width: calc(100% - 24px);
    box-sizing: border-box;
    z-index: 10;
    animation: processFloatUp 4s ease-in-out infinite;
}

@keyframes processFloatUp {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-5px); }
}

.process-interview-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.process-interview-title {
    font-size: 11.5px;
    font-weight: 700;
    color: #000623;
}

.process-interview-time {
    font-size: 11px;
    font-weight: 600;
    color: #4F48E6;
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
        margin-top: 4px;
        padding: 10px 12px;
        animation: none;
    }
    .process-interview-name {
        font-size: 11.5px;
    }
    .process-interview-time {
        font-size: 10.5px;
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

                    <!-- Row 3: Academic Credentials -->
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
                                <span class="process-profile-sub">University Transcript & Major</span>
                            </div>
                        </div>
                        <div class="process-check-circle" title="Verified Asset">
                            <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Card Content -->
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

                <!-- Card Content -->
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
                                <strong>Direct Google Meet Provisioning:</strong> Generates secure calendar links delivered to candidate dashboards.
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

                <!-- Right Column: Interactive Calendar & Floating Cards -->
                <div class="process-calendar-stage">
                    <!-- Calendar Widget Card -->
                    <div class="process-calendar-card">
                        <div class="process-cal-header">
                            <span class="process-cal-month">November 2026</span>
                            <span class="process-cal-tz">WAT (UTC+1)</span>
                        </div>

                        <div class="process-cal-weekdays">
                            <span>Mo</span>
                            <span>Tu</span>
                            <span>We</span>
                            <span>Th</span>
                            <span>Fr</span>
                            <span>Sa</span>
                            <span>Su</span>
                        </div>

                        <div class="process-cal-grid">
                            <!-- Week 1 -->
                            <span class="process-cal-day is-muted">27</span>
                            <span class="process-cal-day is-muted">28</span>
                            <span class="process-cal-day is-muted">29</span>
                            <span class="process-cal-day is-muted">30</span>
                            <span class="process-cal-day">1</span>
                            <span class="process-cal-day">2</span>
                            <span class="process-cal-day">3</span>
                            <!-- Week 2 -->
                            <span class="process-cal-day">4</span>
                            <span class="process-cal-day">5</span>
                            <span class="process-cal-day">6</span>
                            <span class="process-cal-day">7</span>
                            <span class="process-cal-day">8</span>
                            <span class="process-cal-day">9</span>
                            <span class="process-cal-day">10</span>
                            <!-- Week 3 -->
                            <span class="process-cal-day">11</span>
                            <span class="process-cal-day">12</span>
                            <span class="process-cal-day">13</span>
                            <span class="process-cal-day">14</span>
                            <span class="process-cal-day">15</span>
                            <span class="process-cal-day">16</span>
                            <span class="process-cal-day">17</span>
                            <!-- Week 4 -->
                            <span class="process-cal-day is-active" title="Confirmed Interview Date">18</span>
                            <span class="process-cal-day">19</span>
                            <span class="process-cal-day">20</span>
                            <span class="process-cal-day">21</span>
                            <span class="process-cal-day">22</span>
                            <span class="process-cal-day">23</span>
                            <span class="process-cal-day">24</span>
                            <!-- Week 5 -->
                            <span class="process-cal-day">25</span>
                            <span class="process-cal-day">26</span>
                            <span class="process-cal-day">27</span>
                            <span class="process-cal-day">28</span>
                            <span class="process-cal-day">29</span>
                            <span class="process-cal-day">30</span>
                            <span class="process-cal-day is-muted">1</span>
                        </div>
                    </div>

                    <!-- Floating AI Candidate Match Score Badge (Top Right) -->
                    <div class="process-floating-match">
                        <img src="<?= asset('assets/images/jenny_ai/jenny_icon.png') ?>" alt="Jenny AI" class="process-match-icon">
                        <span class="process-match-text">95% Match Score</span>
                        <span class="process-match-sub">· UI/UX Role</span>
                    </div>

                    <!-- Floating Upcoming Interview Card (Bottom Left) -->
                    <div class="process-floating-interview">
                        <div class="process-interview-header">
                            <span class="process-interview-title">Upcoming Interview</span>
                            <span class="process-interview-time">Wed · 10:30–11:15</span>
                        </div>
                        <div class="process-interview-body">
                            <div class="process-interview-candidate">
                                <img src="<?= asset('assets/images/jenny_ai/jenny_ai_hi.png') ?>" alt="Candidate" class="process-interview-avatar">
                                <span class="process-interview-name">Sarah Peters</span>
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
</section>
