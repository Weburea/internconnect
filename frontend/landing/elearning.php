<?php

declare(strict_types=1);

/**
 * View: E-Learning & Certified Partner Directory
 * Route: /elearning
 * Layout: app
 */
?>
<style>
/* ==========================================================================
   E-LEARNING ECOSYSTEM & LANDING STYLES (MATCHING REFERENCE DESIGN)
   ========================================================================== */

/* 1. Hero Section Layout */
.elearn-hero-wrap {
    position: relative;
    padding: 32px 0 64px;
    overflow: hidden;
    background: #FFFFFF;
}
.elearn-hero-header {
    text-align: center;
    max-width: 780px;
    margin: 0 auto 40px;
}
.elearn-hero-title {
    font-size: clamp(32px, 4.2vw, 52px);
    font-weight: 800;
    color: #000623;
    line-height: 1.18;
    letter-spacing: -0.025em;
    margin: 16px 0 16px;
}
.elearn-hero-title .text-highlight {
    color: #4F48E6;
    position: relative;
    display: inline-block;
}
.elearn-hero-subtitle {
    font-size: clamp(15px, 1.6vw, 17px);
    color: #475569;
    line-height: 1.65;
    max-width: 660px;
    margin: 0 auto 28px;
}
.elearn-hero-ctas {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 14px;
    flex-wrap: wrap;
    margin-bottom: 24px;
}
.elearn-hero-btn-primary {
    background: #4F48E6;
    color: #FFFFFF !important;
    font-size: 14.5px;
    font-weight: 700;
    padding: 12px 28px;
    border-radius: 9999px;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    text-decoration: none;
    box-shadow: 0 4px 14px rgba(79, 72, 230, 0.28);
    transition: all 0.2s ease;
    white-space: nowrap !important;
}
.elearn-hero-btn-primary:hover {
    background: #4338CA;
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(79, 72, 230, 0.35);
}
.elearn-hero-btn-secondary {
    background: #FFFFFF;
    color: #000623 !important;
    font-size: 14.5px;
    font-weight: 700;
    padding: 12px 24px;
    border-radius: 9999px;
    border: 1px solid #E2E8F0;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    text-decoration: none;
    transition: all 0.2s ease;
    white-space: nowrap !important;
}
.elearn-hero-btn-secondary:hover {
    background: #F8FAFC;
    border-color: #CBD5E1;
}

/* Hero Centerpiece Graphic & Floating Cards */
.elearn-hero-showcase {
    position: relative;
    max-width: 1180px;
    margin: 0 auto;
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 650px;
    padding: 24px 0 36px;
}
.elearn-hero-visual-stage {
    position: relative;
    display: flex;
    justify-content: center;
    align-items: center;
    width: 100%;
    max-width: 720px;
}
.elearn-hero-glow-bg {
    position: absolute;
    width: 700px;
    height: 700px;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(79, 72, 230, 0.22) 0%, rgba(99, 102, 241, 0.09) 50%, rgba(255, 255, 255, 0) 72%);
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    pointer-events: none;
    z-index: 1;
    animation: heroGlowPulse 8s ease-in-out infinite alternate;
}
@keyframes heroGlowPulse {
    0% { transform: translate(-50%, -50%) scale(0.96); opacity: 0.82; }
    100% { transform: translate(-50%, -50%) scale(1.06); opacity: 1; }
}

.elearn-hero-person-img {
    position: relative;
    z-index: 2;
    height: 630px;
    max-height: 650px;
    width: auto;
    max-width: 100%;
    object-fit: contain;
    display: block;
    filter: drop-shadow(0 20px 48px rgba(0, 6, 35, 0.16));
    animation: ladyGentleBalance 6s ease-in-out infinite alternate;
    transform-origin: center bottom;
}
@keyframes ladyGentleBalance {
    0% { transform: translateY(0px) rotate(0deg); }
    50% { transform: translateY(-7px) rotate(0.4deg); }
    100% { transform: translateY(-13px) rotate(-0.35deg); }
}

/* Floating Cards Wrapper */
.elearn-hero-cards-row {
    position: static;
}

/* Floating Card 1: Left Quote Card - Shifted Closely Inward */
.elearn-float-card-left {
    position: absolute;
    top: 50%;
    left: calc(50% - 370px);
    transform: translateY(-50%);
    z-index: 3;
    background: #FFFFFF;
    border: 1px solid #E2E8F0;
    border-radius: 16px;
    padding: 16px 18px;
    max-width: 245px;
    box-shadow: 0 14px 32px -4px rgba(0, 6, 35, 0.09);
    transition: box-shadow 0.25s ease;
    animation: cardFloatLeft 6s ease-in-out infinite alternate;
}
.elearn-float-card-left:hover {
    box-shadow: 0 18px 40px -4px rgba(0, 6, 35, 0.14);
}
@keyframes cardFloatLeft {
    0% { transform: translateY(-50%); }
    100% { transform: translateY(calc(-50% - 8px)); }
}

.elearn-float-quote-icon {
    width: 26px;
    height: 26px;
    border-radius: 7px;
    background: #EEF2FF;
    color: #4F48E6;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 8px;
}
.elearn-float-quote-text {
    font-size: 11.5px;
    color: #475569;
    line-height: 1.5;
    margin-bottom: 10px;
}
.elearn-float-stat-badge {
    display: flex;
    align-items: center;
    gap: 8px;
    padding-top: 8px;
    border-top: 1px solid #F1F5F9;
}
.elearn-float-stat-icon {
    width: 26px;
    height: 26px;
    border-radius: 50%;
    background: #EEF2FF;
    color: #4F48E6;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.elearn-float-stat-info {
    font-size: 11.5px;
    font-weight: 700;
    color: #000623;
    line-height: 1.2;
}
.elearn-float-stat-info span {
    font-size: 10px;
    font-weight: 500;
    color: #64748B;
    display: block;
}

/* Floating Card 2: Right Review Card - Shifted Closely Inward */
.elearn-float-card-right {
    position: absolute;
    top: 52%;
    right: calc(50% - 370px);
    transform: translateY(-50%);
    z-index: 3;
    background: #FFFFFF;
    border: 1px solid #E2E8F0;
    border-radius: 16px;
    padding: 16px 18px;
    max-width: 245px;
    box-shadow: 0 14px 32px -4px rgba(0, 6, 35, 0.09);
    transition: box-shadow 0.25s ease;
    animation: cardFloatRight 6.5s ease-in-out 1s infinite alternate;
}
.elearn-float-card-right:hover {
    box-shadow: 0 18px 40px -4px rgba(0, 6, 35, 0.14);
}
@keyframes cardFloatRight {
    0% { transform: translateY(-50%); }
    100% { transform: translateY(calc(-50% + 8px)); }
}

.elearn-float-stars {
    display: flex;
    align-items: center;
    gap: 3px;
    color: #F59E0B;
    margin-bottom: 8px;
}
.elearn-float-review-text {
    font-size: 11.5px;
    color: #475569;
    line-height: 1.5;
    margin-bottom: 10px;
}
.elearn-float-reviewer {
    display: flex;
    align-items: center;
    gap: 10px;
    padding-top: 8px;
    border-top: 1px solid #F1F5F9;
}
.elearn-float-reviewer img {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    object-fit: cover;
    border: 1px solid #E2E8F0;
}
.elearn-float-reviewer-meta {
    font-size: 11.5px;
    font-weight: 700;
    color: #000623;
    line-height: 1.2;
}
.elearn-float-reviewer-meta span {
    font-size: 10px;
    font-weight: 500;
    color: #64748B;
    display: block;
}

/* Floating Vector Doodles - Rounded Playful Badges with Multi-Color Bouncing */
.elearn-doodle {
    position: absolute;
    z-index: 2;
    pointer-events: none;
}
.elearn-doodle-circle {
    width: 58px;
    height: 58px;
    border-radius: 50%;
    background: #FFFFFF;
    border: 1.5px solid #E2E8F0;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 10px 25px -4px rgba(0, 6, 35, 0.12);
    transition: transform 0.2s ease;
}

/* 1. Top-Left: Warm Amber/Orange Lightbulb */
.elearn-doodle-1 {
    top: 6%;
    left: 27%;
    animation: doodleBounce1 4.5s ease-in-out infinite alternate;
}
.elearn-doodle-1 .elearn-doodle-circle {
    background: #FFFBEB;
    border-color: #FDE68A;
    color: #EA580C;
    filter: drop-shadow(0 6px 18px rgba(245, 158, 11, 0.35));
}
@keyframes doodleBounce1 {
    0% { transform: translateY(0px) rotate(0deg); }
    100% { transform: translateY(-13px) rotate(7deg); }
}

/* 2. Top-Right: Vibrant Cyan/Blue Concentric Target */
.elearn-doodle-2 {
    top: 7%;
    right: 27%;
    animation: doodleBounce2 5.2s ease-in-out 0.4s infinite alternate;
}
.elearn-doodle-2 .elearn-doodle-circle {
    background: #F0F9FF;
    border-color: #BAE6FD;
    color: #0284C7;
    filter: drop-shadow(0 6px 18px rgba(14, 165, 233, 0.35));
}
@keyframes doodleBounce2 {
    0% { transform: translateY(0px) rotate(0deg); }
    100% { transform: translateY(-11px) rotate(-6deg); }
}

/* 3. Bottom-Left: Emerald Green Clock */
.elearn-doodle-3 {
    bottom: 12%;
    left: 21%;
    animation: doodleBounce3 4.8s ease-in-out 0.8s infinite alternate;
}
.elearn-doodle-3 .elearn-doodle-circle {
    background: #ECFDF5;
    border-color: #A7F3D0;
    color: #10B981;
    filter: drop-shadow(0 6px 18px rgba(16, 185, 129, 0.35));
}
@keyframes doodleBounce3 {
    0% { transform: translateY(0px) rotate(0deg); }
    100% { transform: translateY(-12px) rotate(9deg); }
}

/* 4. Bottom-Right: Vibrant Coral/Orange Achievement Star */
.elearn-doodle-4 {
    bottom: 8%;
    right: 20%;
    animation: doodleBounce4 6s ease-in-out 1.2s infinite alternate;
}
.elearn-doodle-4 .elearn-doodle-circle {
    background: #FFF7ED;
    border-color: #FED7AA;
    color: #F97316;
    filter: drop-shadow(0 6px 18px rgba(249, 115, 22, 0.35));
}
@keyframes doodleBounce4 {
    0% { transform: translateY(0px) rotate(0deg) scale(0.96); }
    100% { transform: translateY(-10px) rotate(180deg) scale(1.05); }
}

/* 5. Mid-Right: Electric Violet Graduation Cap */
.elearn-doodle-5 {
    top: 28%;
    right: 18%;
    animation: doodleBounce5 5s ease-in-out 0.6s infinite alternate;
}
.elearn-doodle-5 .elearn-doodle-circle {
    background: #EEF2FF;
    border-color: #C7D2FE;
    color: #6366F1;
    filter: drop-shadow(0 6px 18px rgba(99, 102, 241, 0.35));
}
@keyframes doodleBounce5 {
    0% { transform: translateY(0px) rotate(0deg); }
    100% { transform: translateY(-11px) rotate(-8deg); }
}

/* 6. Mid-Left: Bright Golden Starburst */
.elearn-doodle-6 {
    top: 25%;
    left: 18%;
    animation: doodleBounce6 5.5s ease-in-out 1s infinite alternate;
}
.elearn-doodle-6 .elearn-doodle-circle {
    background: #FEFCE8;
    border-color: #FEF08A;
    color: #EAB308;
    filter: drop-shadow(0 6px 18px rgba(234, 179, 8, 0.35));
}
@keyframes doodleBounce6 {
    0% { transform: translateY(0px) rotate(0deg) scale(0.94); }
    100% { transform: translateY(-12px) rotate(12deg) scale(1.06); }
}

/* ==========================================================================
   2. OUR TRUSTED PARTNERS (LOGO GRID / MARQUEE MATCHING REFERENCE)
   ========================================================================== */
.elearn-trusted-wrap {
    padding: 48px 0 56px;
    background: #FAFAFC;
    border-top: 1px solid #F1F5F9;
    border-bottom: 1px solid #F1F5F9;
}
.elearn-trusted-header {
    text-align: center;
    max-width: 600px;
    margin: 0 auto 32px;
}
.elearn-trusted-title {
    font-size: clamp(22px, 2.6vw, 30px);
    font-weight: 800;
    color: #000623;
    margin: 8px 0 8px;
    letter-spacing: -0.02em;
}
.elearn-trusted-subtitle {
    font-size: 14px;
    color: #64748B;
    margin: 0;
}
.elearn-partner-pill-grid {
    display: flex;
    align-items: center;
    justify-content: center;
    flex-wrap: wrap;
    gap: 14px;
    max-width: 1040px;
    margin: 0 auto;
}
.elearn-partner-pill-card {
    background: #FFFFFF;
    border: 1px solid #E2E8F0;
    border-radius: 14px;
    padding: 10px 22px;
    display: inline-flex;
    align-items: center;
    gap: 10px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
    transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
    text-decoration: none;
    color: #0F172A;
}
.elearn-partner-pill-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(0, 6, 35, 0.08);
    border-color: #CBD5E1;
}
.elearn-partner-pill-card span {
    font-size: 13.5px;
    font-weight: 700;
    color: #1E293B;
    letter-spacing: -0.01em;
}

/* ==========================================================================
   3. FEATURES SECTION: "LEARNING THAT WORKS FOR YOU" (MATCHING REFERENCE)
   ========================================================================== */
.elearn-features-wrap {
    padding: 64px 0 72px;
    background: #FFFFFF;
}
.elearn-features-header {
    text-align: center;
    max-width: 600px;
    margin: 0 auto 48px;
}
.elearn-features-title {
    font-size: clamp(24px, 3vw, 34px);
    font-weight: 800;
    color: #000623;
    margin: 8px 0 8px;
    letter-spacing: -0.02em;
}
.elearn-features-subtitle {
    font-size: 14.5px;
    color: #64748B;
    margin: 0;
}
.elearn-features-grid {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 20px;
}
.elearn-feature-card {
    background: #FFFFFF;
    border: 1px solid #E2E8F0;
    border-radius: 18px;
    padding: 24px 20px;
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
    transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
}
.elearn-feature-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 24px rgba(0, 6, 35, 0.08);
    border-color: #CBD5E1;
}
.elearn-feature-icon-badge {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 16px;
    flex-shrink: 0;
    background: #EEF2FF;
    color: #4F48E6;
}
.elearn-feature-icon-badge.is-orange,
.elearn-feature-icon-badge.is-pink,
.elearn-feature-icon-badge.is-green,
.elearn-feature-icon-badge.is-indigo {
    background: #EEF2FF;
    color: #4F48E6;
}
.elearn-feature-title {
    font-size: 15.5px;
    font-weight: 800;
    color: #000623;
    margin: 0 0 8px;
    line-height: 1.3;
}
.elearn-feature-desc {
    font-size: 12.5px;
    color: #64748B;
    line-height: 1.6;
    margin: 0;
}

/* ==========================================================================
   4. SEARCH, FILTER BAR & PROGRAM CARDS DIRECTORY
   ========================================================================== */
.elearn-directory-wrap {
    padding: 64px 0 80px;
    background: #FAFAFC;
    border-top: 1px solid #E2E8F0;
}
.elearn-dir-header {
    text-align: center;
    max-width: 680px;
    margin: 0 auto 36px;
}
.elearn-dir-title {
    font-size: clamp(24px, 3vw, 34px);
    font-weight: 800;
    color: #000623;
    margin: 8px 0 8px;
    letter-spacing: -0.02em;
}
.elearn-dir-subtitle {
    font-size: 14.5px;
    color: #64748B;
    margin: 0;
}
.elearn-search-filter-wrap {
    display: flex;
    flex-direction: column;
    gap: 20px;
    margin-bottom: 40px;
}
.elearn-search-input-box {
    display: flex;
    align-items: center;
    gap: 12px;
    background: #FFFFFF;
    border: 1px solid #E2E8F0;
    border-radius: 9999px;
    padding: 10px 22px;
    max-width: 560px;
    margin: 0 auto;
    width: 100%;
    box-sizing: border-box;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    transition: border-color 0.18s ease;
}
.elearn-search-input-box:focus-within {
    border-color: #4F48E6;
    box-shadow: 0 0 0 3px rgba(79, 72, 230, 0.12);
}
.elearn-search-input-box input {
    border: none;
    outline: none;
    background: transparent;
    font-size: 14px;
    color: #0F172A;
    width: 100%;
    min-width: 0;
}
.elearn-filter-bar {
    display: flex;
    align-items: center;
    justify-content: center;
    flex-wrap: wrap;
    gap: 8px;
}
.elearn-filter-pill {
    padding: 8px 18px;
    border-radius: 9999px;
    font-size: 13px;
    font-weight: 600;
    background: #FFFFFF;
    border: 1px solid #E2E8F0;
    color: #64748B;
    cursor: pointer;
    transition: all 0.15s ease;
    white-space: nowrap !important;
}
.elearn-filter-pill:hover,
.elearn-filter-pill.is-active {
    background: #4F48E6;
    color: #FFFFFF;
    border-color: #4F48E6;
}

/* Partner Program Cards Grid */
.elearn-partner-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 24px;
    margin-bottom: 56px;
}
.elearn-partner-card {
    background: #FFFFFF;
    border: 1px solid #E2E8F0;
    border-radius: 20px;
    padding: 24px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
    transition: transform 0.2s ease, border-color 0.2s ease, box-shadow 0.2s ease;
    min-width: 0;
    box-sizing: border-box;
}
.elearn-partner-card:hover {
    border-color: #CBD5E1;
    transform: translateY(-3px);
    box-shadow: 0 8px 24px rgba(0, 6, 35, 0.08);
}
.elearn-card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 8px;
    margin-bottom: 14px;
    width: 100%;
}
.elearn-platform-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: #F8FAFC;
    border: 1px solid #E2E8F0;
    color: #000623;
    font-size: 12px;
    font-weight: 700;
    padding: 4px 10px;
    border-radius: 8px;
    white-space: nowrap !important;
    max-width: 100%;
}
.elearn-tier-badge {
    font-size: 11px;
    font-weight: 700;
    color: #4F48E6;
    background: #EEF2FF;
    border: 1px solid #E0E7FF;
    padding: 3px 9px;
    border-radius: 9999px;
    white-space: nowrap !important;
}
.elearn-track-title {
    font-size: clamp(16px, 1.8vw, 18px);
    font-weight: 800;
    color: #000623;
    line-height: 1.35;
    margin: 0 0 8px;
}
.elearn-track-desc {
    font-size: 13px;
    color: #64748B;
    line-height: 1.6;
    margin: 0 0 14px;
    flex: 1;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.elearn-meta-tags-row {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 6px;
    margin-bottom: 16px;
}
.elearn-tag-pill {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-size: 11.5px;
    font-weight: 600;
    color: #475569;
    background: #F1F5F9;
    padding: 4px 9px;
    border-radius: 6px;
    white-space: nowrap !important;
}
.elearn-perk-badge {
    background: #EEF2FF;
    border: 1px solid #C7D2FE;
    border-radius: 10px;
    padding: 10px 12px;
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 12px;
    font-weight: 700;
    color: #4F48E6;
    margin-bottom: 16px;
}
.elearn-perk-badge span {
    color: #3730A3;
}
.elearn-card-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    padding-top: 14px;
    border-top: 1px solid #F1F5F9;
}
.elearn-meta-spec {
    font-size: 12px;
    color: #64748B;
    font-weight: 600;
}

/* ==========================================================================
   5. BENTO GRID: INSTITUTIONAL COLLABORATION (ANIMATED BACKGROUND)
   ========================================================================== */
.elearn-bento-section {
    position: relative;
    border-radius: 24px;
    padding: 44px 40px;
    color: #FFFFFF;
    background: linear-gradient(135deg, #000623 0%, #0B1938 50%, #000623 100%);
    border: 1px solid rgba(255, 255, 255, 0.1);
    box-shadow: 0 20px 45px -8px rgba(0, 6, 35, 0.25);
    overflow: hidden;
    margin-bottom: 64px;
}
.elearn-bento-ambient-glow {
    position: absolute;
    width: 650px;
    height: 650px;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(79, 72, 230, 0.25) 0%, rgba(37, 99, 235, 0.1) 50%, transparent 70%);
    top: -150px;
    right: -100px;
    pointer-events: none;
    animation: bentoAmbientPulse 8s ease-in-out infinite alternate;
}
@keyframes bentoAmbientPulse {
    0% { transform: scale(1) translate(0, 0); opacity: 0.6; }
    100% { transform: scale(1.15) translate(-30px, 30px); opacity: 0.9; }
}

/* Top Row: Narrative Left + iPad Showcase Right */
.elearn-bento-top-grid {
    position: relative;
    z-index: 2;
    display: grid;
    grid-template-columns: minmax(0, 1.2fr) minmax(0, 1fr);
    gap: 36px;
    align-items: center;
    margin-bottom: 36px;
}
.elearn-bento-card-hero {
    display: flex;
    flex-direction: column;
}
.elearn-bento-title {
    font-size: clamp(24px, 3vw, 34px);
    font-weight: 800;
    color: #FFFFFF;
    line-height: 1.25;
    margin: 12px 0 16px;
    letter-spacing: -0.02em;
}
.elearn-bento-desc {
    font-size: 14.5px;
    color: #CBD5E1;
    line-height: 1.65;
    margin: 0 0 24px;
    max-width: 560px;
}
.elearn-bento-actions {
    display: flex;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
}
.elearn-bento-btn-primary {
    background: #4F48E6;
    color: #FFFFFF !important;
    font-size: 14px;
    font-weight: 700;
    padding: 12px 24px;
    border-radius: 9999px;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    white-space: nowrap !important;
    transition: all 0.2s ease;
}
.elearn-bento-btn-primary:hover {
    background: #4338CA;
    transform: translateY(-2px);
}
.elearn-bento-btn-secondary {
    background: rgba(255, 255, 255, 0.08);
    color: #FFFFFF !important;
    font-size: 14px;
    font-weight: 700;
    padding: 12px 22px;
    border-radius: 9999px;
    border: 1px solid rgba(255, 255, 255, 0.2);
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    white-space: nowrap !important;
    transition: all 0.2s ease;
}
.elearn-bento-btn-secondary:hover {
    background: rgba(255, 255, 255, 0.15);
}

/* iPad Device Showcase */
.elearn-bento-ipad-showcase {
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
}
.elearn-bento-ipad-img {
    max-height: 380px;
    width: auto;
    max-width: 100%;
    object-fit: contain;
    filter: drop-shadow(0 20px 40px rgba(0, 0, 0, 0.45));
    transition: transform 0.3s ease;
}
.elearn-bento-ipad-img:hover {
    transform: scale(1.02);
}

/* Bottom Horizontal Row: 3 Colored Metric Cards */
.elearn-bento-metric-row {
    position: relative;
    z-index: 2;
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 18px;
}
.elearn-bento-metric-card {
    border-radius: 16px;
    padding: 20px;
    display: flex;
    flex-direction: column;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.elearn-bento-metric-card:hover {
    transform: translateY(-3px);
}

/* Emerald Green Accent Card */
.elearn-bento-metric-card.is-green {
    background: rgba(16, 185, 129, 0.08);
    border: 1px solid rgba(16, 185, 129, 0.40);
    box-shadow: 0 10px 25px -4px rgba(16, 185, 129, 0.12);
}
.elearn-bento-metric-card.is-green .elearn-bento-metric-icon {
    background: rgba(16, 185, 129, 0.18);
    color: #34D399;
}
.elearn-bento-metric-card.is-green .elearn-bento-metric-num {
    color: #34D399;
}

/* Vibrant Orange Accent Card */
.elearn-bento-metric-card.is-orange {
    background: rgba(249, 115, 22, 0.08);
    border: 1px solid rgba(249, 115, 22, 0.40);
    box-shadow: 0 10px 25px -4px rgba(249, 115, 22, 0.12);
}
.elearn-bento-metric-card.is-orange .elearn-bento-metric-icon {
    background: rgba(249, 115, 22, 0.18);
    color: #FB923C;
}
.elearn-bento-metric-card.is-orange .elearn-bento-metric-num {
    color: #FB923C;
}

/* Electric Indigo Accent Card */
.elearn-bento-metric-card.is-indigo {
    background: rgba(99, 102, 241, 0.08);
    border: 1px solid rgba(99, 102, 241, 0.40);
    box-shadow: 0 10px 25px -4px rgba(99, 102, 241, 0.12);
}
.elearn-bento-metric-card.is-indigo .elearn-bento-metric-icon {
    background: rgba(99, 102, 241, 0.18);
    color: #A5B4FC;
}
.elearn-bento-metric-card.is-indigo .elearn-bento-metric-num {
    color: #A5B4FC;
}

.elearn-bento-metric-header {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 10px;
}
.elearn-bento-metric-icon {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.elearn-bento-metric-num {
    font-size: 22px;
    font-weight: 800;
    line-height: 1;
}
.elearn-bento-metric-title {
    font-size: 13.5px;
    font-weight: 700;
    color: #FFFFFF;
    margin-bottom: 4px;
}
.elearn-bento-metric-label {
    font-size: 12px;
    color: #94A3B8;
    line-height: 1.5;
    margin: 0;
}

/* ==========================================================================
   6. STANDARDIZED MODAL (STRICT CENTERING & RICH DETAILS)
   ========================================================================== */
.elearn-modal-backdrop {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    width: 100vw;
    height: 100vh;
    background: rgba(0, 6, 35, 0.72);
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 999999;
    padding: 20px;
    box-sizing: border-box;
}
.elearn-modal-dialog {
    background: #FFFFFF;
    border: 1px solid #E2E8F0;
    border-radius: 24px;
    max-width: 640px;
    width: 100%;
    padding: 32px;
    box-shadow: 0 25px 50px -12px rgba(0, 6, 35, 0.25);
    position: relative;
    max-height: 90vh;
    overflow-y: auto;
    margin: auto;
    box-sizing: border-box;
    animation: elearnModalPop 0.25s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}
@keyframes elearnModalPop {
    from { opacity: 0; transform: scale(0.95) translateY(10px); }
    to { opacity: 1; transform: scale(1) translateY(0); }
}
.elearn-modal-close-btn {
    position: absolute;
    top: 20px;
    right: 20px;
    background: #F8FAFC;
    border: 1px solid #E2E8F0;
    border-radius: 50%;
    width: 34px;
    height: 34px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #64748B;
    cursor: pointer;
    transition: all 0.15s ease;
}
.elearn-modal-close-btn:hover {
    background: #F1F5F9;
    color: #000623;
}

/* ==========================================================================
   7. RESPONSIVE MEDIA QUERIES (IPAD, TABLET & MOBILE VIEWPORTS)
   ========================================================================== */

/* iPad & Tablet Landscape / Portrait (641px - 1024px) */
@media (max-width: 1024px) {
    .elearn-hero-showcase {
        min-height: auto;
        display: flex !important;
        flex-direction: column !important;
        align-items: center !important;
        padding: 24px 0 36px;
    }
    .elearn-hero-visual-stage {
        order: 1 !important;
        max-width: 580px;
        margin-bottom: 28px;
        width: 100%;
    }
    .elearn-hero-person-img {
        height: 480px;
        max-height: 500px;
    }
    .elearn-hero-glow-bg {
        width: 480px;
        height: 480px;
    }
    .elearn-hero-cards-row {
        order: 2 !important;
        display: grid !important;
        grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
        gap: 18px;
        width: 100%;
        max-width: 680px;
        margin: 0 auto;
        padding: 0 16px;
        box-sizing: border-box;
    }
    .elearn-float-card-left,
    .elearn-float-card-right {
        position: static !important;
        transform: none !important;
        max-width: 100% !important;
        width: 100% !important;
        margin: 0 !important;
        animation: none !important;
    }
    .elearn-doodle-1 { top: 4%; left: 14%; }
    .elearn-doodle-2 { top: 5%; right: 14%; }
    .elearn-doodle-3 { bottom: 6%; left: 12%; }
    .elearn-doodle-4 { bottom: 6%; right: 12%; }
    .elearn-doodle-5 { top: 26%; right: 10%; }
    .elearn-doodle-6 { top: 24%; left: 10%; }

    .elearn-features-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
    }
    .elearn-partner-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
    }
    .elearn-bento-top-grid {
        grid-template-columns: minmax(0, 1fr) !important;
        gap: 28px;
    }
    .elearn-bento-ipad-img {
        max-height: 320px;
    }
    .elearn-bento-metric-row {
        grid-template-columns: repeat(3, minmax(0, 1fr)) !important;
        gap: 12px;
    }
}

/* Mobile Devices (<= 640px) */
@media (max-width: 640px) {
    .elearn-hero-wrap {
        padding: 24px 0 84px !important; /* Generous clearance for Jenny AI launcher */
    }
    .elearn-hero-title {
        font-size: 30px;
        line-height: 1.22;
        margin: 12px 0 14px;
    }
    .elearn-hero-subtitle {
        font-size: 14px;
        line-height: 1.6;
        margin-bottom: 22px;
    }
    .elearn-hero-showcase {
        display: flex !important;
        flex-direction: column !important;
        align-items: center !important;
        padding: 8px 0 20px !important;
        min-height: auto !important;
    }
    .elearn-hero-visual-stage {
        order: 1 !important;
        max-width: 360px !important;
        width: 100% !important;
        margin: 0 auto 20px !important;
    }
    .elearn-hero-person-img {
        height: 350px !important;
        max-height: 370px !important;
        width: auto !important;
        margin: 0 auto !important;
    }
    .elearn-hero-glow-bg {
        width: 320px !important;
        height: 320px !important;
    }
    .elearn-hero-cards-row {
        order: 2 !important;
        display: flex !important;
        flex-direction: column !important;
        gap: 14px !important;
        width: 100% !important;
        max-width: 380px !important;
        margin: 0 auto !important;
        padding: 0 8px !important;
        box-sizing: border-box !important;
    }
    .elearn-float-card-left,
    .elearn-float-card-right {
        position: static !important;
        transform: none !important;
        width: 100% !important;
        max-width: 100% !important;
        margin: 0 !important;
        padding: 16px 18px !important;
        border-radius: 14px !important;
        animation: none !important;
    }

    /* Keep 2 scaled playful floating badges by girl's hands on mobile */
    .elearn-doodle-1 {
        top: 2%;
        left: 6%;
        display: block !important;
    }
    .elearn-doodle-1 .elearn-doodle-circle {
        width: 38px;
        height: 38px;
    }
    .elearn-doodle-1 svg {
        width: 20px;
        height: 20px;
    }

    .elearn-doodle-2 {
        top: 4%;
        right: 6%;
        display: block !important;
    }
    .elearn-doodle-2 .elearn-doodle-circle {
        width: 38px;
        height: 38px;
    }
    .elearn-doodle-2 svg {
        width: 20px;
        height: 20px;
    }

    /* Hide lower doodles so they never overlap cards or beanbag */
    .elearn-doodle-3,
    .elearn-doodle-4,
    .elearn-doodle-5,
    .elearn-doodle-6 {
        display: none !important;
    }

    .elearn-partner-grid {
        grid-template-columns: minmax(0, 1fr) !important;
    }
    .elearn-features-grid {
        grid-template-columns: minmax(0, 1fr) !important;
    }
    .elearn-bento-section {
        padding: 28px 18px;
    }
    .elearn-bento-top-grid {
        grid-template-columns: minmax(0, 1fr) !important;
        gap: 22px;
        text-align: center;
    }
    .elearn-bento-actions {
        justify-content: center;
    }
    .elearn-bento-ipad-img {
        max-height: 230px;
    }
    .elearn-bento-metric-row {
        grid-template-columns: minmax(0, 1fr) !important;
        gap: 14px;
    }
    .elearn-modal-dialog {
        padding: 24px 18px !important;
        border-radius: 20px !important;
    }
    .elearn-filter-pill {
        font-size: 12px;
        padding: 6px 14px;
    }
}
</style>

<section class="ic-page-shell" x-data="{
    searchQuery: '',
    activeCategory: 'all',
    selectedPartner: null,
    partnerModal: false,
    copiedCode: false,
    partners: [
        {
            id: 1,
            platform: 'ALX Africa',
            tier: 'Accredited Partner',
            title: 'ALX Full-Stack Software Engineering & Cloud',
            category: 'tech',
            category_label: 'Software & Cloud',
            duration: '9 Months &bull; Intensive Cohort',
            delivery: 'Hybrid Hubs &amp; 100% Online',
            cities: 'Lagos, Nairobi, Kigali, Accra, Johannesburg &amp; Remote',
            level: 'Beginner to Advanced',
            desc: 'World-class software engineering program covering low-level programming, data structures, full-stack web applications, and DevOps workflows.',
            about_platform: 'ALX Africa is a leading tech training initiative transforming young African leaders through rigorous, market-aligned technical training programs.',
            perk: 'Direct Fast-Track to Employer Tech Shortlists',
            perk_code: 'IC-ALX-FAST',
            external_url: 'https://www.alxafrica.com',
            highlights: [
                'C, Python, and modern JavaScript foundations',
                'System design and database architectures',
                'Peer-to-peer code reviews & agile team projects',
                'Alumni network of 50,000+ African technologists'
            ],
            outcomes: 'Full-Stack Developer, Backend Software Engineer, Cloud Practitioner'
        },
        {
            id: 2,
            platform: 'Coursera',
            tier: 'Global Partner',
            title: 'Google UX Design Professional Certificate',
            category: 'design',
            category_label: 'Product & Design',
            duration: '3 - 6 Months &bull; Self-Paced',
            delivery: '100% Online &amp; Self-Paced',
            cities: 'Global &amp; Fully Remote',
            level: 'Beginner',
            desc: 'Master the complete UX design process, conduct usability studies, build wireframes and high-fidelity prototypes in Figma, and build 3 portfolio case studies.',
            about_platform: 'Coursera partners with more than 300 leading universities and companies to bring flexible, affordable, job-relevant online learning.',
            perk: 'Free Financial Aid & 100% Audit Available',
            perk_code: 'COURSERA-AID',
            external_url: 'https://www.coursera.org/professional-certificates/google-ux-design',
            highlights: [
                'User research, personas, and journey maps',
                'Figma design system components and auto-layout',
                'Cross-platform mobile and web responsiveness',
                'Official Google Professional Certificate badge'
            ],
            outcomes: 'Entry-level UX Designer, UI Designer, Product Design Intern'
        },
        {
            id: 3,
            platform: 'DataCamp',
            tier: 'Data & AI Partner',
            title: 'Associate Data Analyst with Python & SQL',
            category: 'data',
            category_label: 'Data Science & AI',
            duration: '2 - 4 Months &bull; Interactive Labs',
            delivery: 'Hands-on Browser Labs &amp; Online',
            cities: 'Global &amp; Fully Remote',
            level: 'Intermediate',
            desc: 'Interactive coding environment to query relational databases with SQL, clean data using pandas, and build data visualization stories for business teams.',
            about_platform: 'DataCamp helps individuals and businesses acquire the in-demand data and AI skills needed to thrive in the modern economy.',
            perk: 'Exclusive: 20% Off Premium for InternConnect Students',
            perk_code: 'ICDATA20',
            external_url: 'https://www.datacamp.com',
            highlights: [
                'PostgreSQL querying, joins, and aggregations',
                'Exploratory data analysis with Python & pandas',
                'Statistical hypothesis testing and dashboard reporting',
                'Industry-recognized DataCamp certification'
            ],
            outcomes: 'Junior Data Analyst, BI Intern, SQL Reporting Specialist'
        },
        {
            id: 4,
            platform: 'freeCodeCamp',
            tier: 'Free Certifications',
            title: 'Scientific Computing with Python & Algorithms',
            category: 'free',
            category_label: 'Free Certifications',
            duration: '300 Hours &bull; Self-Paced',
            delivery: '100% Free &amp; Open-Source',
            cities: 'Global &amp; Fully Remote',
            level: 'All Levels',
            desc: 'Acclaimed non-profit curriculum covering Python algorithms, data structures, and numerical computing through 5 verified portfolio projects.',
            about_platform: 'freeCodeCamp is a proven donor-supported non-profit community that has helped more than 40,000 graduates land jobs at tech companies.',
            perk: '100% Free Credential Recognized by Verified Recruiters',
            perk_code: 'FREE-ALWAYS',
            external_url: 'https://www.freecodecamp.org/learn/scientific-computing-with-python/',
            highlights: [
                'Algorithm time and space complexity (Big O)',
                'Object-oriented programming patterns in Python',
                'Hands-on computational problem solving projects',
                'Free verifiable cryptographic certificate'
            ],
            outcomes: 'Python Developer Intern, Algorithm Engineer, Software Apprentice'
        },
        {
            id: 5,
            platform: 'Google Certificates',
            tier: 'Global Tech Partner',
            title: 'Google IT Support & Cybersecurity Specialization',
            category: 'tech',
            category_label: 'Software & Cloud',
            duration: '3 - 6 Months &bull; Self-Paced',
            delivery: '100% Online &amp; Self-Paced',
            cities: 'Global &amp; Fully Remote',
            level: 'Beginner',
            desc: 'Hands-on practical training covering hardware diagnostics, networking protocols, Linux terminal administration, and enterprise cyber defense.',
            about_platform: 'Google Career Certificates provide pathways to great jobs in high-growth fields, without requiring a 4-year degree.',
            perk: 'Fast-Tracks Applications for Corporate IT Roles',
            perk_code: 'GOOGLE-CERT',
            external_url: 'https://grow.google/certificates',
            highlights: [
                'TCP/IP networking and DNS server configuration',
                'Linux and Windows command line troubleshooting',
                'System security encryption and incident response',
                'Direct access to Google Hiring Consortium'
            ],
            outcomes: 'IT Support Specialist, Systems Administrator, Cyber Defense Analyst'
        },
        {
            id: 6,
            platform: 'CFI Institute',
            tier: 'Accredited Partner',
            title: 'Financial Modeling & Valuation Analyst (FMVA)',
            category: 'business',
            category_label: 'Business & Finance',
            duration: '4 - 6 Months &bull; Self-Paced',
            delivery: '100% Online &amp; Interactive Models',
            cities: 'Global &amp; Fully Remote',
            level: 'Intermediate to Advanced',
            desc: 'The global standard certification for corporate finance, teaching 3-statement financial modeling, DCF valuations, and Excel shortcuts for investment analysts.',
            about_platform: 'Corporate Finance Institute (CFI) is the leading provider of online financial analyst certification programs and corporate training.',
            perk: 'Unlocks Verified Financial Analyst Badge on InternConnect',
            perk_code: 'CFI-FMVA-IC',
            external_url: 'https://corporatefinanceinstitute.com',
            highlights: [
                'Dynamic 3-statement forecasting in Excel',
                'Discounted cash flow (DCF) models & sensitivity analysis',
                'M&amp;A and corporate pitch deck structuring',
                'Global FMVA certification recognized by top investment banks'
            ],
            outcomes: 'Investment Banking Intern, Financial Analyst, Private Equity Trainee'
        }
    ],
    filteredPartners() {
        return this.partners.filter(p => {
            const matchesCat = (this.activeCategory === 'all') || 
                               (this.activeCategory === 'free' && p.category === 'free') || 
                               (p.category === this.activeCategory);
            const query = this.searchQuery.toLowerCase().trim();
            const matchesQuery = !query || 
                                 p.title.toLowerCase().includes(query) || 
                                 p.platform.toLowerCase().includes(query) || 
                                 p.category_label.toLowerCase().includes(query) || 
                                 p.desc.toLowerCase().includes(query);
            return matchesCat && matchesQuery;
        });
    },
    openPartner(partner) {
        this.selectedPartner = partner;
        this.partnerModal = true;
        this.copiedCode = false;
        document.body.style.overflow = 'hidden';
    },
    closeModal() {
        this.partnerModal = false;
        this.selectedPartner = null;
        document.body.style.overflow = '';
    },
    copyCode(code) {
        if (navigator.clipboard) {
            navigator.clipboard.writeText(code);
            this.copiedCode = true;
            setTimeout(() => { this.copiedCode = false; }, 2500);
        }
    }
}" @keydown.escape.window="closeModal()">

    <!-- ====================================================================
         1. HERO SECTION (MATCHING REFERENCE DESIGN: GIRL WITH LAPTOP & FLOAT CARDS)
         ==================================================================== -->
    <header class="elearn-hero-wrap">
        <div class="ic-container">
            <div class="elearn-hero-header">
                <span class="ic-badge">
                    <svg style="width: 14px; height: 14px; flex-shrink: 0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                    Certified Learning Pathways
                </span>
                <h1 class="elearn-hero-title">
                    Up Your <span class="text-highlight">Skills</span> To Advance Your Career Path
                </h1>
                <p class="elearn-hero-subtitle">
                    Join 10,000+ ambitious students and graduates. Gain practical, career-boosting knowledge through accredited EdTech partners and global tech academies to boost your internship Match Score.
                </p>
                <div class="elearn-hero-ctas">
                    <a href="#partner-directory" class="elearn-hero-btn-primary">
                        <span>Browse Courses</span>
                        <svg style="width: 15px; height: 15px; flex-shrink: 0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                        </svg>
                    </a>
                    <a href="#trusted-partners" class="elearn-hero-btn-secondary">
                        <span>Our Partners</span>
                    </a>
                </div>
            </div>

            <!-- Visual Showcase with Center Image & Floating Interactive Cards -->
            <div class="elearn-hero-showcase">

                <!-- Center Stage Visual (Beanbag Student + Glow + Animated Doodles) -->
                <div class="elearn-hero-visual-stage">
                    <div class="elearn-hero-glow-bg"></div>

                    <!-- Floating Doodles - Rounded Playful Badges with Multi-Color Bouncing -->
                    <!-- 1. Top-Left: Warm Amber/Orange Lightbulb -->
                    <div class="elearn-doodle elearn-doodle-1">
                        <div class="elearn-doodle-circle">
                            <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M9 18h6m-4 4h2M12 2a7 7 0 0 0-4 12.7V17h8v-2.3A7 7 0 0 0 12 2z"/>
                            </svg>
                        </div>
                    </div>
                    <!-- 2. Top-Right: Vibrant Cyan/Blue Progress Target -->
                    <div class="elearn-doodle elearn-doodle-2">
                        <div class="elearn-doodle-circle">
                            <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="9"/>
                                <circle cx="12" cy="12" r="4"/>
                                <path d="M12 3a9 9 0 0 1 9 9"/>
                            </svg>
                        </div>
                    </div>
                    <!-- 3. Bottom-Left: Emerald Green Clock -->
                    <div class="elearn-doodle elearn-doodle-3">
                        <div class="elearn-doodle-circle">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"/>
                                <polyline points="12 6 12 12 16 14"/>
                            </svg>
                        </div>
                    </div>
                    <!-- 4. Bottom-Right: Vibrant Coral/Orange Achievement Star -->
                    <div class="elearn-doodle elearn-doodle-4">
                        <div class="elearn-doodle-circle">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                            </svg>
                        </div>
                    </div>
                    <!-- 5. Mid-Right: Electric Violet Graduation Cap -->
                    <div class="elearn-doodle elearn-doodle-5">
                        <div class="elearn-doodle-circle">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 3L1 9l11 6 9-4.91V17h2V9L12 3z"/>
                                <path d="M5 13.18v4L12 21l7-3.82v-4"/>
                            </svg>
                        </div>
                    </div>
                    <!-- 6. Mid-Left: Bright Golden Starburst -->
                    <div class="elearn-doodle elearn-doodle-6">
                        <div class="elearn-doodle-circle">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 3v3m0 12v3M3 12h3m12 0h3M5.636 5.636l2.122 2.122m8.484 8.484l2.122 2.122M5.636 18.364l2.122-2.122m8.484-8.484l2.122-2.122"/>
                            </svg>
                        </div>
                    </div>

                    <!-- Center: Smiling Student Celebrating in Bean Bag with Laptop -->
                    <img src="<?= asset('assets/images/company/learning_student_beanbag.png') ?>" alt="Ambitious student celebrating career skills on InternConnect" class="elearn-hero-person-img" loading="eager">
                </div>

                <!-- Social Proof Cards Wrapper (Floating on Desktop / Responsive Clean Grid on iPad & Mobile) -->
                <div class="elearn-hero-cards-row">
                    <!-- Floating Card 1 (Left): Quote & Active Users -->
                    <div class="elearn-float-card-left">
                        <div class="elearn-float-quote-icon">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/>
                            </svg>
                        </div>
                        <div class="elearn-float-quote-text">
                            From AI-enhanced lessons to real-world projects, our platform empowers you to learn.
                        </div>
                        <div class="elearn-float-stat-badge">
                            <div class="elearn-float-stat-icon">
                                <svg style="width: 14px; height: 14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div class="elearn-float-stat-info">
                                100K+
                                <span>Worldwide Active Users</span>
                            </div>
                        </div>
                    </div>

                    <!-- Floating Card 2 (Right): Review Rating -->
                    <div class="elearn-float-card-right">
                        <div class="elearn-float-stars">
                            <?php for ($i = 0; $i < 5; $i++): ?>
                                <svg style="width: 13px; height: 13px; fill: currentColor;" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            <?php endfor; ?>
                        </div>
                        <div class="elearn-float-review-text">
                            &ldquo;The flexibility to learn at my own pace was a game-changer. I landed my first frontend internship within 2 weeks.&rdquo;
                        </div>
                        <div class="elearn-float-reviewer">
                            <img src="<?= asset('assets/images/profile.png') ?>" alt="Student avatar">
                            <div class="elearn-float-reviewer-meta">
                                Chidinma O.
                                <span>Frontend Intern @ Paystack</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </header>

    <!-- ====================================================================
         2. OUR TRUSTED PARTNERS (MATCHING REFERENCE PILL CARDS)
         ==================================================================== -->
    <section id="trusted-partners" class="elearn-trusted-wrap">
        <div class="ic-container">
            <div class="elearn-trusted-header">
                <span class="ic-badge">Our Partners</span>
                <h2 class="elearn-trusted-title">Our Trusted Partners</h2>
                <p class="elearn-trusted-subtitle">Proudly partnered with leading organizations &amp; accredited academies.</p>
            </div>

            <div class="elearn-partner-pill-grid">
                <!-- Coursera -->
                <div class="elearn-partner-pill-card">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="#0056D2">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 17c-3.87 0-7-3.13-7-7s3.13-7 7-7 7 3.13 7 7-3.13 7-7 7z"/>
                    </svg>
                    <span>Coursera</span>
                </div>
                <!-- ALX Africa -->
                <div class="elearn-partner-pill-card">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="#EA580C">
                        <path d="M12 2L2 22h20L12 2zm0 5l6.5 13H5.5L12 7z"/>
                    </svg>
                    <span>ALX Africa</span>
                </div>
                <!-- Google -->
                <div class="elearn-partner-pill-card">
                    <svg width="20" height="20" viewBox="0 0 24 24">
                        <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                        <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                        <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
                        <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
                    </svg>
                    <span>Google Certificates</span>
                </div>
                <!-- DataCamp -->
                <div class="elearn-partner-pill-card">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="#059669">
                        <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
                    </svg>
                    <span>DataCamp</span>
                </div>
                <!-- AltSchool Africa -->
                <div class="elearn-partner-pill-card">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="#4F48E6">
                        <path d="M12 3L1 9l11 6 9-4.91V17h2V9L12 3z"/>
                    </svg>
                    <span>AltSchool Africa</span>
                </div>
                <!-- Udacity -->
                <div class="elearn-partner-pill-card">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="#0284C7">
                        <path d="M12 2a10 10 0 100 20 10 10 0 000-20zm0 18a8 8 0 110-16 8 8 0 010 16zm-1-13h2v7h-2zm0 8h2v2h-2z"/>
                    </svg>
                    <span>Udacity</span>
                </div>
                <!-- Utiva -->
                <div class="elearn-partner-pill-card">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="#0D9488">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 14.5v-9l6 4.5-6 4.5z"/>
                    </svg>
                    <span>Utiva</span>
                </div>
                <!-- CFI -->
                <div class="elearn-partner-pill-card">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="#1E40AF">
                        <path d="M12 2L4 5v6.09c0 5.05 3.41 9.76 8 10.91 4.59-1.15 8-5.86 8-10.91V5l-8-3zm0 15c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5z"/>
                    </svg>
                    <span>CFI (FMVA)</span>
                </div>
                <!-- freeCodeCamp -->
                <div class="elearn-partner-pill-card">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="#0A0A23">
                        <path d="M12.4 2a9.6 9.6 0 00-6.8 2.8A9.6 9.6 0 002.8 11.6c0 3.8 2.2 7.1 5.4 8.7a9.6 9.6 0 007.4.3c3.2-1.2 5.6-3.8 6.4-7.1.8-3.3-.2-6.8-2.6-9.1A9.5 9.5 0 0012.4 2z"/>
                    </svg>
                    <span>freeCodeCamp</span>
                </div>
                <!-- AWS Academy -->
                <div class="elearn-partner-pill-card">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="#FF9900">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 15h-2v-6h2v6zm4 0h-2v-6h2v6zm-2-8a1 1 0 110-2 1 1 0 010 2z"/>
                    </svg>
                    <span>AWS Academy</span>
                </div>
            </div>
        </div>
    </section>

    <!-- ====================================================================
         3. FEATURES SECTION: "LEARNING THAT WORKS FOR YOU" (MATCHING REFERENCE)
         ==================================================================== -->
    <section class="elearn-features-wrap">
        <div class="ic-container">
            <div class="elearn-features-header">
                <span class="ic-badge">Features</span>
                <h2 class="elearn-features-title">Learning That Works for You</h2>
                <p class="elearn-features-subtitle">Real skills, accredited certifications, direct internship shortlists.</p>
            </div>

            <div class="elearn-features-grid">
                <!-- Feature 1 -->
                <div class="elearn-feature-card">
                    <div class="elearn-feature-icon-badge is-orange">
                        <svg style="width: 20px; height: 20px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"/>
                            <polyline points="12 6 12 12 16 14"/>
                        </svg>
                    </div>
                    <h3 class="elearn-feature-title">Learn at Your Own Pace</h3>
                    <p class="elearn-feature-desc">
                        Flexible self-paced modules and weekend cohorts designed to fit comfortably around university semesters.
                    </p>
                </div>

                <!-- Feature 2 -->
                <div class="elearn-feature-card">
                    <div class="elearn-feature-icon-badge is-pink">
                        <svg style="width: 20px; height: 20px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                        </svg>
                    </div>
                    <h3 class="elearn-feature-title">Certificate on Completion</h3>
                    <p class="elearn-feature-desc">
                        Verifiable accredited credentials that automatically embed directly into your candidate profile and vault.
                    </p>
                </div>

                <!-- Feature 3 -->
                <div class="elearn-feature-card">
                    <div class="elearn-feature-icon-badge is-green">
                        <svg style="width: 20px; height: 20px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>
                        </svg>
                    </div>
                    <h3 class="elearn-feature-title">Real-world Projects</h3>
                    <p class="elearn-feature-desc">
                        Build portfolio case studies, GitHub repositories, and financial models that impress hiring managers.
                    </p>
                </div>

                <!-- Feature 4 -->
                <div class="elearn-feature-card">
                    <div class="elearn-feature-icon-badge is-indigo">
                        <svg style="width: 20px; height: 20px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <h3 class="elearn-feature-title">+25% Match Boost</h3>
                    <p class="elearn-feature-desc">
                        Certified candidates receive an automatic Match Score boost, priority ranking, and verified recruiter visibility.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- ====================================================================
         4. PARTNER DIRECTORY (SEARCH, FILTERS & CARDS)
         ==================================================================== -->
    <section id="partner-directory" class="elearn-directory-wrap">
        <div class="ic-container">
            <div class="elearn-dir-header">
                <span class="ic-badge">Curated Catalog</span>
                <h2 class="elearn-dir-title">Explore Certified Programs</h2>
                <p class="elearn-dir-subtitle">Search by tool, engineering discipline, or specific partner platform.</p>
            </div>

            <!-- Search & Filters -->
            <div class="elearn-search-filter-wrap">
                <div class="elearn-search-input-box">
                    <svg style="width: 18px; height: 18px; color: #94A3B8; flex-shrink: 0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text" x-model="searchQuery" placeholder="Search by skill, certification, or partner (e.g., React, Python, ALX, Figma)..." aria-label="Search partner learning programs">
                </div>

                <div class="elearn-filter-bar">
                    <button type="button" class="elearn-filter-pill" :class="{ 'is-active': activeCategory === 'all' }" @click="activeCategory = 'all'">All Partners</button>
                    <button type="button" class="elearn-filter-pill" :class="{ 'is-active': activeCategory === 'tech' }" @click="activeCategory = 'tech'">Software &amp; Cloud</button>
                    <button type="button" class="elearn-filter-pill" :class="{ 'is-active': activeCategory === 'design' }" @click="activeCategory = 'design'">Product &amp; Design</button>
                    <button type="button" class="elearn-filter-pill" :class="{ 'is-active': activeCategory === 'data' }" @click="activeCategory = 'data'">Data Science &amp; AI</button>
                    <button type="button" class="elearn-filter-pill" :class="{ 'is-active': activeCategory === 'business' }" @click="activeCategory = 'business'">Business &amp; Finance</button>
                    <button type="button" class="elearn-filter-pill" :class="{ 'is-active': activeCategory === 'free' }" @click="activeCategory = 'free'">100% Free Certifications</button>
                </div>
            </div>

            <!-- Partner Programs Grid -->
            <div class="elearn-partner-grid">
                <template x-for="partner in filteredPartners()" :key="partner.id">
                    <article class="elearn-partner-card">
                        <div>
                            <div class="elearn-card-header">
                                <span class="elearn-platform-badge" x-text="partner.platform"></span>
                                <span class="elearn-tier-badge" x-text="partner.tier"></span>
                            </div>

                            <h3 class="elearn-track-title" x-text="partner.title"></h3>
                            <p class="elearn-track-desc" x-text="partner.desc"></p>

                            <!-- Meta Tags Row -->
                            <div class="elearn-meta-tags-row">
                                <span class="elearn-tag-pill">
                                    <svg style="width: 12px; height: 12px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <circle cx="12" cy="12" r="10"/>
                                        <polyline points="12 6 12 12 16 14"/>
                                    </svg>
                                    <span x-html="partner.duration"></span>
                                </span>
                                <span class="elearn-tag-pill">
                                    <svg style="width: 12px; height: 12px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    <span x-html="partner.delivery"></span>
                                </span>
                            </div>

                            <!-- Student Perk Callout -->
                            <div class="elearn-perk-badge">
                                <svg style="width: 15px; height: 15px; flex-shrink: 0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"/>
                                </svg>
                                <span x-text="partner.perk"></span>
                            </div>
                        </div>

                        <div class="elearn-card-footer">
                            <span class="elearn-meta-spec" x-text="partner.level"></span>
                            <button type="button" @click="openPartner(partner)" class="ic-btn ic-btn-secondary" style="padding: 7px 16px; font-size: 13px;">
                                <span>View Details</span>
                                <svg style="width: 13px; height: 13px; flex-shrink: 0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                                </svg>
                            </button>
                        </div>
                    </article>
                </template>
            </div>

            <!-- ================================================================
                 5. INSTITUTIONAL COLLABORATION BENTO GRID (WITH PLATFORM MOCKUP)
                 ================================================================ -->
            <section class="elearn-bento-section">
                <div class="elearn-bento-ambient-glow"></div>

                <!-- Top Row: Narrative Story (Left) + iPad Device Mockup (Right) -->
                <div class="elearn-bento-top-grid">
                    
                    <!-- Left: Hero Collaboration Story -->
                    <div class="elearn-bento-card-hero">
                        <span class="ic-badge" style="background: rgba(255, 255, 255, 0.12); color: #FFFFFF; border-color: rgba(255, 255, 255, 0.22); align-self: flex-start;">
                            Institutional Collaboration
                        </span>
                        <h2 class="elearn-bento-title">Are You an EdTech Platform, Bootcamp, or University?</h2>
                        <p class="elearn-bento-desc">
                            Partner with InternConnect to showcase your curriculum to over 10,000+ ambitious candidates. Help your graduates bridge the transition from certification directly into verified, paid corporate internships across Nigeria and Africa.
                        </p>
                        
                        <div class="elearn-bento-actions">
                            <a href="<?= url('/contact') ?>" class="elearn-bento-btn-primary">
                                <span>Become a Learning Partner</span>
                                <svg style="width: 14px; height: 14px; flex-shrink: 0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                            <a href="<?= url('/internships') ?>" class="elearn-bento-btn-secondary">
                                <span>Explore Active Roles</span>
                            </a>
                        </div>
                    </div>

                    <!-- Right: iPad Device Platform Mockup (Clean Transparent Background) -->
                    <div class="elearn-bento-ipad-showcase">
                        <img src="<?= asset('assets/images/company/ipad_e-learn-removebg-preview_zjxx87.png') ?>" alt="InternConnect candidate credential verification and learning ecosystem on iPad" class="elearn-bento-ipad-img" loading="lazy">
                    </div>

                </div>

                <!-- Bottom Row: 3 Full-Width Horizontal Metric Cards with Colored Borders -->
                <div class="elearn-bento-metric-row">
                    <!-- Metric 1: Emerald Green Card -->
                    <div class="elearn-bento-metric-card is-green">
                        <div class="elearn-bento-metric-header">
                            <div class="elearn-bento-metric-icon">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                                    <polyline points="22 4 12 14.01 9 11.01"/>
                                </svg>
                            </div>
                            <span class="elearn-bento-metric-num">85%+</span>
                        </div>
                        <div class="elearn-bento-metric-title">Alumni Placement Rate</div>
                        <p class="elearn-bento-metric-label">Internship shortlist rate for verified graduates within 14 days of program graduation.</p>
                    </div>

                    <!-- Metric 2: Vibrant Orange Card -->
                    <div class="elearn-bento-metric-card is-orange">
                        <div class="elearn-bento-metric-header">
                            <div class="elearn-bento-metric-icon">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                                </svg>
                            </div>
                            <span class="elearn-bento-metric-num">Tamper-Proof</span>
                        </div>
                        <div class="elearn-bento-metric-title">Automated Badges</div>
                        <p class="elearn-bento-metric-label">Cryptographic credential verification badges permanently embedded on candidate profiles.</p>
                    </div>

                    <!-- Metric 3: Electric Indigo Card -->
                    <div class="elearn-bento-metric-card is-indigo">
                        <div class="elearn-bento-metric-header">
                            <div class="elearn-bento-metric-icon">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                    <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                                </svg>
                            </div>
                            <span class="elearn-bento-metric-num">Demo Days</span>
                        </div>
                        <div class="elearn-bento-metric-title">Recruiter Spotlights</div>
                        <p class="elearn-bento-metric-label">Direct recruiter showcase sessions connecting partner graduates directly with CAC-verified companies.</p>
                    </div>
                </div></section>

        </div>
    </section>

    <!-- ====================================================================
         6. STANDARDIZED PARTNER DETAILS MODAL (PERFECTLY CENTERED)
         ==================================================================== -->
    <template x-if="partnerModal && selectedPartner">
        <div class="elearn-modal-backdrop" @click.self="closeModal()" x-cloak>
            <div class="elearn-modal-dialog" @click.stop>
                <button type="button" @click="closeModal()" class="elearn-modal-close-btn" aria-label="Close dialog">
                    <svg style="width: 18px; height: 18px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>

                <div>
                    <!-- Header Meta -->
                    <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 14px; flex-wrap: wrap;">
                        <span class="elearn-platform-badge" x-text="selectedPartner.platform"></span>
                        <span class="elearn-tier-badge" x-text="selectedPartner.tier"></span>
                        <span style="font-size: 11.5px; font-weight: 600; color: #475569; background: #F1F5F9; padding: 3px 8px; border-radius: 9999px;" x-text="selectedPartner.category_label"></span>
                    </div>

                    <!-- Title & Overview -->
                    <h3 style="font-size: 22px; font-weight: 800; color: #000623; margin: 0 0 10px; line-height: 1.3;" x-text="selectedPartner.title"></h3>
                    <p style="font-size: 14px; color: #475569; margin: 0 0 18px; line-height: 1.6;" x-text="selectedPartner.desc"></p>
                    
                    <!-- Academy Profile Box -->
                    <div style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 14px; padding: 14px 16px; margin-bottom: 18px;">
                        <div style="font-size: 11px; font-weight: 800; text-transform: uppercase; color: #000623; margin-bottom: 4px; letter-spacing: 0.05em;">About the Academy &amp; Hub Locations:</div>
                        <p style="font-size: 12.5px; color: #64748B; margin: 0 0 8px; line-height: 1.5;" x-text="selectedPartner.about_platform"></p>
                        <div style="display: flex; align-items: center; gap: 6px; font-size: 12px; font-weight: 600; color: #1E293B;">
                            <svg style="width: 14px; height: 14px; color: #4F48E6; flex-shrink: 0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span x-html="selectedPartner.cities"></span>
                        </div>
                    </div>

                    <!-- Candidate Advantage & Promo Voucher -->
                    <div style="background: #EEF2FF; border: 1px solid #C7D2FE; border-radius: 14px; padding: 16px; margin-bottom: 20px;">
                        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 6px; flex-wrap: wrap; gap: 8px;">
                            <span style="font-size: 11px; font-weight: 800; text-transform: uppercase; color: #4F48E6; letter-spacing: 0.05em;">InternConnect Student Advantage:</span>
                            <span style="font-size: 11px; font-weight: 700; background: #E0E7FF; color: #3730A3; padding: 3px 8px; border-radius: 6px;" x-text="selectedPartner.perk_code"></span>
                        </div>
                        <div style="font-size: 13.5px; font-weight: 700; color: #1E1B4B; margin-bottom: 10px;" x-text="selectedPartner.perk"></div>
                        <button type="button" @click="copyCode(selectedPartner.perk_code)" class="ic-btn" style="background: #FFFFFF; border: 1px solid #C7D2FE; color: #4F48E6; font-size: 12px; padding: 6px 14px; border-radius: 8px; font-weight: 600;">
                            <svg style="width: 13px; height: 13px; flex-shrink: 0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"/>
                            </svg>
                            <span x-text="copiedCode ? 'Voucher Copied!' : 'Copy Student Voucher Code'"></span>
                        </button>
                    </div>

                    <!-- Curriculum Outline -->
                    <div style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 14px; padding: 18px; margin-bottom: 22px;">
                        <div style="font-size: 12px; font-weight: 800; text-transform: uppercase; color: #000623; margin-bottom: 10px; letter-spacing: 0.04em;">Key Curriculum &amp; Practical Modules:</div>
                        <ul style="margin: 0; padding-left: 18px; font-size: 13px; color: #334155; line-height: 1.8;">
                            <template x-for="(highlight, idx) in selectedPartner.highlights" :key="idx">
                                <li x-text="highlight"></li>
                            </template>
                        </ul>
                    </div>

                    <!-- Target Career Pathways -->
                    <div style="margin-bottom: 24px;">
                        <span style="font-size: 12px; font-weight: 700; color: #64748B; display: block; margin-bottom: 4px;">Target Internship Outcomes:</span>
                        <span style="font-size: 13px; font-weight: 700; color: #000623;" x-text="selectedPartner.outcomes"></span>
                    </div>

                    <!-- Dialog Actions -->
                    <div style="display: flex; gap: 12px; justify-content: flex-end; flex-wrap: wrap; border-top: 1px solid #F1F5F9; padding-top: 20px;">
                        <button type="button" @click="closeModal()" class="ic-btn ic-btn-secondary">Close</button>
                        <a :href="selectedPartner.external_url" target="_blank" rel="noopener noreferrer" class="ic-btn ic-btn-primary" style="background: #4F48E6; color: #FFFFFF !important;">
                            <span>Visit Platform &amp; Enroll</span>
                            <svg style="width: 14px; height: 14px; flex-shrink: 0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </template>

</section>
