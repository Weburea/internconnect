<?php

declare(strict_types=1);

/**
 * View: How It Works
 * Route: /how-it-works
 * Layout: app
 */
?>
<style>
/* Local style guarantee for How It Works narrative layout */
.hiw-tab-wrap {
    display: flex;
    justify-content: center;
    margin-bottom: 56px;
}
.hiw-tab-container {
    background: #FFFFFF;
    border: 1px solid #E2E8F0;
    border-radius: 9999px;
    padding: 6px;
    display: inline-flex;
    gap: 8px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
}
.hiw-tab-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 12px 28px;
    border-radius: 9999px;
    font-size: 14px;
    font-weight: 700;
    color: #64748B;
    border: none;
    background: transparent;
    cursor: pointer;
    transition: all 0.2s ease;
    white-space: nowrap !important;
}
.hiw-tab-btn.is-active {
    background: #4F48E6;
    color: #FFFFFF;
    box-shadow: 0 4px 14px rgba(79, 72, 230, 0.28);
}
.hiw-narrative-flow {
    display: flex;
    flex-direction: column;
    gap: 72px;
    padding-bottom: 64px;
}
.hiw-chapter {
    position: relative;
}
.hiw-chapter-grid {
    display: grid;
    grid-template-columns: minmax(0, 1.15fr) minmax(0, 1fr);
    gap: 56px;
    align-items: center;
}
.hiw-chapter-grid.is-reversed {
    grid-template-columns: minmax(0, 1fr) minmax(0, 1.15fr);
}
.hiw-chapter-grid.is-reversed .hiw-chapter-visual {
    order: 1;
}
.hiw-chapter-grid.is-reversed .hiw-chapter-text {
    order: 2;
}
.hiw-chapter-text {
    display: flex;
    flex-direction: column;
    min-width: 0;
}
.hiw-chapter-meta {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 16px;
}
.hiw-chapter-num {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 38px;
    height: 38px;
    border-radius: 12px;
    background: #000623;
    color: #FFFFFF;
    font-size: 15px;
    font-weight: 800;
    letter-spacing: -0.02em;
    flex-shrink: 0;
}
.hiw-chapter-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 12px;
    font-weight: 700;
    color: #4F48E6;
    text-transform: uppercase;
    letter-spacing: 0.06em;
    background: #EEF2FF;
    border: 1px solid #C7D2FE;
    padding: 4px 12px;
    border-radius: 9999px;
    white-space: nowrap !important;
}
.hiw-chapter-title {
    font-size: clamp(24px, 2.7vw, 34px);
    font-weight: 800;
    color: #000623;
    line-height: 1.25;
    margin: 0 0 16px;
    letter-spacing: -0.02em;
}
.hiw-chapter-desc {
    font-size: 15.5px;
    color: #475569;
    line-height: 1.7;
    margin: 0 0 24px;
}
.hiw-points-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
    margin: 0 0 28px;
    padding: 0;
    list-style: none;
}
.hiw-point-item {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    font-size: 14px;
    color: #1E293B;
    line-height: 1.5;
}
.hiw-point-icon {
    width: 22px;
    height: 22px;
    border-radius: 50%;
    background: #ECFDF5;
    color: #059669;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    margin-top: 1px;
}
.hiw-chapter-quote {
    background: #F8FAFC;
    border: 1px solid #E2E8F0;
    border-radius: 12px;
    padding: 16px 20px;
    font-size: 13.5px;
    color: #64748B;
    line-height: 1.6;
    display: flex;
    align-items: center;
    gap: 12px;
}
.hiw-chapter-visual {
    position: relative;
    min-width: 0;
}
.hiw-visual-frame {
    background: #FFFFFF;
    border: 1px solid #E2E8F0;
    border-radius: 24px;
    overflow: hidden;
    box-shadow: 0 12px 36px -4px rgba(0, 6, 35, 0.08);
    position: relative;
    transition: transform 0.25s ease, box-shadow 0.25s ease;
    height: 440px;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    min-width: 0;
    box-sizing: border-box;
}
.hiw-visual-frame:hover {
    transform: translateY(-4px);
    box-shadow: 0 20px 45px -6px rgba(0, 6, 35, 0.12);
}
.hiw-visual-frame img {
    display: block;
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center;
}
.hiw-visual-frame.is-contained {
    background: #000623;
}
.hiw-visual-frame.is-contained img {
    object-fit: contain;
    padding: 24px;
}
.hiw-visual-frame.is-purple-contained {
    background: #4F48E6;
}
.hiw-visual-frame.is-purple-contained img {
    object-fit: contain;
    padding: 24px;
}
.hiw-visual-frame.is-dark {
    background: #000000;
}
.hiw-cta-banner {
    background: linear-gradient(135deg, #000623 0%, #0F172A 100%);
    border-radius: 24px;
    padding: 56px 40px;
    text-align: center;
    color: #FFFFFF;
    margin: 40px 0 80px;
    position: relative;
    overflow: hidden;
    border: 1px solid rgba(255, 255, 255, 0.08);
}
.hiw-cta-title {
    font-size: clamp(26px, 3.2vw, 36px);
    font-weight: 800;
    color: #FFFFFF;
    margin: 0 0 14px;
    letter-spacing: -0.02em;
}
.hiw-cta-desc {
    font-size: 15.5px;
    color: #CBD5E1;
    max-width: 620px;
    margin: 0 auto 32px;
    line-height: 1.65;
}

@media (max-width: 1024px) {
    .hiw-chapter-grid,
    .hiw-chapter-grid.is-reversed {
        grid-template-columns: minmax(0, 1fr) !important;
        gap: 36px;
    }
    .hiw-chapter-grid.is-reversed .hiw-chapter-visual {
        order: initial;
    }
    .hiw-chapter-grid.is-reversed .hiw-chapter-text {
        order: initial;
    }
    .hiw-visual-frame {
        height: 380px;
    }
}
@media (max-width: 768px) {
    .hiw-narrative-flow {
        gap: 48px;
    }
}
@media (max-width: 640px) {
    .hiw-cta-banner {
        padding: 24px 16px;
    }
    .hiw-visual-frame {
        height: 270px;
        border-radius: 18px;
    }
    .hiw-visual-frame.is-contained img,
    .hiw-visual-frame.is-purple-contained img {
        padding: 14px;
    }
    .hiw-tab-container {
        width: 100%;
        display: flex;
        flex-direction: column;
        border-radius: 14px;
    }
    .hiw-tab-btn {
        width: 100%;
        justify-content: center;
        border-radius: 10px;
    }
}
</style>
<section class="ic-page-shell" x-data="{ journey: 'candidates' }">

    <!-- Hero Header Banner -->
    <header class="ic-page-hero">
        <div class="ic-container">
            <span class="ic-badge">
                <svg style="width: 14px; height: 14px; flex-shrink: 0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
                Platform Architecture &amp; Workflow
            </span>
            <h1 class="ic-page-hero-title">A Transparent, Frictionless Path from Application to Placement</h1>
            <p class="ic-page-hero-subtitle">
                No ghost applications, no endless paperwork, and no unverified employers. Here is how InternConnect powers high-trust internships for both students and organizations.
            </p>
        </div>
    </header>

    <div class="ic-container" style="padding-top: 48px;">

        <!-- Journey Switcher Toggle -->
        <div class="hiw-tab-wrap">
            <div class="hiw-tab-container">
                <button type="button" class="hiw-tab-btn" :class="{ 'is-active': journey === 'candidates' }" @click="journey = 'candidates'">
                    <svg style="width: 16px; height: 16px; flex-shrink: 0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    <span>For Candidates &amp; Students</span>
                </button>
                <button type="button" class="hiw-tab-btn" :class="{ 'is-active': journey === 'employers' }" @click="journey = 'employers'">
                    <svg style="width: 16px; height: 16px; flex-shrink: 0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    <span>For Employers &amp; Organizations</span>
                </button>
            </div>
        </div>

        <!-- ====================================================================
             CANDIDATE JOURNEY: EDITORIAL NARRATIVE CHAPTERS (NO BOX CARDS)
             ==================================================================== -->
        <div x-show="journey === 'candidates'" class="hiw-narrative-flow">

            <!-- Chapter 1 -->
            <section class="hiw-chapter">
                <div class="hiw-chapter-grid">
                    <div class="hiw-chapter-text">
                        <div class="hiw-chapter-meta">
                            <span class="hiw-chapter-num">01</span>
                            <span class="hiw-chapter-badge">Identity &amp; Credentials</span>
                        </div>
                        <h2 class="hiw-chapter-title">Build an Accredited Profile That Hiring Managers Trust</h2>
                        <p class="hiw-chapter-desc">
                            Generic job boards force you to re-type your education on dozens of confusing forms. InternConnect replaces that friction with a unified, verified candidate profile. Select your accredited university, degree type, and target disciplines using our relational interest taxonomy.
                        </p>
                        <ul class="hiw-points-list">
                            <li class="hiw-point-item">
                                <span class="hiw-point-icon">
                                    <svg style="width: 13px; height: 13px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </span>
                                <div><strong>Pre-Verified Academic Institution:</strong> Search and connect your tertiary institution with automated CGPA verification.</div>
                            </li>
                            <li class="hiw-point-item">
                                <span class="hiw-point-icon">
                                    <svg style="width: 13px; height: 13px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </span>
                                <div><strong>Adaptive Skills Taxonomy:</strong> Highlight programming languages, design tools, or financial modeling frameworks with relational sub-disciplines.</div>
                            </li>
                            <li class="hiw-point-item">
                                <span class="hiw-point-icon">
                                    <svg style="width: 13px; height: 13px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </span>
                                <div><strong>One-Time Document Dropzone:</strong> Upload your CV/Resume and portfolio links once — they sync instantly across all active applications.</div>
                            </li>
                        </ul>
                        <div class="hiw-chapter-quote">
                            <svg style="width: 18px; height: 18px; color: var(--ic-primary); flex-shrink: 0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>Over 85% of verified profiles receive their first employer evaluation review within 7 days.</span>
                        </div>
                    </div>

                    <div class="hiw-chapter-visual">
                        <div class="hiw-visual-frame">
                            <img src="<?= asset('assets/images/company/internconnect_ipad_on_chair.webp') ?>" alt="Student Accredited Profile Setup on iPad" loading="lazy">
                        </div>
                    </div>
                </div>
            </section>

            <!-- Chapter 2 -->
            <section class="hiw-chapter">
                <div class="hiw-chapter-grid is-reversed">
                    <div class="hiw-chapter-text">
                        <div class="hiw-chapter-meta">
                            <span class="hiw-chapter-num">02</span>
                            <span class="hiw-chapter-badge">Matchmaking &amp; Transparency</span>
                        </div>
                        <h2 class="hiw-chapter-title">Discover Opportunities with Jenny AI &amp; Real Stipends</h2>
                        <p class="hiw-chapter-desc">
                            Say goodbye to roles that hide their pay until the final round. Every single paid internship on InternConnect explicitly displays its monthly stipend range in Nigerian Naira (NGN), along with work modality (Remote, Hybrid, or On-site in Lagos, Abuja, Port Harcourt, and beyond).
                        </p>
                        <ul class="hiw-points-list">
                            <li class="hiw-point-item">
                                <span class="hiw-point-icon">
                                    <svg style="width: 13px; height: 13px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </span>
                                <div><strong>Jenny AI Career Guidance:</strong> Our interactive AI assistant analyzes your coursework and projects to recommend positions where your profile ranks highest.</div>
                            </li>
                            <li class="hiw-point-item">
                                <span class="hiw-point-icon">
                                    <svg style="width: 13px; height: 13px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </span>
                                <div><strong>Zero Hidden Compensation:</strong> Verified monthly stipends (e.g. ₦80,000 – ₦150,000/mo) are shown directly on the job cards before you apply.</div>
                            </li>
                            <li class="hiw-point-item">
                                <span class="hiw-point-icon">
                                    <svg style="width: 13px; height: 13px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </span>
                                <div><strong>Domain Keyword Matching:</strong> Filter effortlessly by engineering frameworks, design systems, or data analytics toolchains.</div>
                            </li>
                        </ul>
                        <div class="hiw-chapter-quote">
                            <svg style="width: 18px; height: 18px; color: var(--ic-primary); flex-shrink: 0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                            <span>100% of employers on InternConnect are verified against Corporate Affairs Commission (CAC) registries.</span>
                        </div>
                    </div>

                    <div class="hiw-chapter-visual">
                        <div class="hiw-visual-frame is-contained">
                            <img src="<?= asset('assets/images/company/holding_phone_jenny_ai.png') ?>" alt="Jenny AI Career Assistant on Mobile" loading="lazy">
                        </div>
                    </div>
                </div>
            </section>

            <!-- Chapter 3 -->
            <section class="hiw-chapter">
                <div class="hiw-chapter-grid">
                    <div class="hiw-chapter-text">
                        <div class="hiw-chapter-meta">
                            <span class="hiw-chapter-num">03</span>
                            <span class="hiw-chapter-badge">Applications &amp; Live Tracking</span>
                        </div>
                        <h2 class="hiw-chapter-title">One-Click Applications with End-to-End Status Visibility</h2>
                        <p class="hiw-chapter-desc">
                            Applying takes seconds. Once you find an internship matching your goals, your synced credentials and portfolio are packaged into an executive applicant brief. Answer the employer's specific screening questions directly and monitor every phase of review.
                        </p>
                        <ul class="hiw-points-list">
                            <li class="hiw-point-item">
                                <span class="hiw-point-icon">
                                    <svg style="width: 13px; height: 13px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </span>
                                <div><strong>No Black Hole Silence:</strong> Receive immediate email &amp; in-app alerts whenever a recruiter opens, reviews, or shortlists your profile.</div>
                            </li>
                            <li class="hiw-point-item">
                                <span class="hiw-point-icon">
                                    <svg style="width: 13px; height: 13px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </span>
                                <div><strong>Targeted Screening Responses:</strong> Answer concise technical and situational questions set by hiring managers to demonstrate immediate value.</div>
                            </li>
                            <li class="hiw-point-item">
                                <span class="hiw-point-icon">
                                    <svg style="width: 13px; height: 13px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </span>
                                <div><strong>Live Kanban Pipeline:</strong> Track all your active submissions across Submitted, Under Review, Shortlisted, and Interview Invited stages.</div>
                            </li>
                        </ul>
                        <div class="hiw-chapter-quote">
                            <svg style="width: 18px; height: 18px; color: var(--ic-primary); flex-shrink: 0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                            </svg>
                            <span>Live notification engine triggers SMS and in-app alerts within 30 seconds of an employer status change.</span>
                        </div>
                    </div>

                    <div class="hiw-chapter-visual">
                        <div class="hiw-visual-frame">
                            <img src="<?= asset('assets/images/company/internconnect_website_browser_mockup.png') ?>" alt="InternConnect Web Platform &amp; Application Workspace" loading="lazy">
                        </div>
                    </div>
                </div>
            </section>

            <!-- Chapter 4 -->
            <section class="hiw-chapter">
                <div class="hiw-chapter-grid is-reversed">
                    <div class="hiw-chapter-text">
                        <div class="hiw-chapter-meta">
                            <span class="hiw-chapter-num">04</span>
                            <span class="hiw-chapter-badge">Interviews &amp; Placements</span>
                        </div>
                        <h2 class="hiw-chapter-title">Calendar-Synced Interviews &amp; Digital Acceptance Letters</h2>
                        <p class="hiw-chapter-desc">
                            When an employer wants to interview you, they send an integrated calendar invitation directly through our Interview Scheduler. Pick your preferred time slot, join the interview in a private InternConnect video room, and formalize employment with legitimate digital offer documents.
                        </p>
                        <ul class="hiw-points-list">
                            <li class="hiw-point-item">
                                <span class="hiw-point-icon">
                                    <svg style="width: 13px; height: 13px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </span>
                                <div><strong>Built-In Scheduler:</strong> View available recruiter meeting slots and confirm interview times with one click — no email ping-pong.</div>
                            </li>
                            <li class="hiw-point-item">
                                <span class="hiw-point-icon">
                                    <svg style="width: 13px; height: 13px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </span>
                                <div><strong>Reschedule Requests:</strong> Easily propose alternative meeting times with a professional reason prompt if an academic conflict arises.</div>
                            </li>
                            <li class="hiw-point-item">
                                <span class="hiw-point-icon">
                                    <svg style="width: 13px; height: 13px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </span>
                                <div><strong>Accredited Offer Letters:</strong> Review binding employment offers complete with monthly stipend amounts, duration, supervisor details, and digital signing.</div>
                            </li>
                        </ul>
                        <div class="hiw-chapter-quote">
                            <svg style="width: 18px; height: 18px; color: var(--ic-primary); flex-shrink: 0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>Official offer letters are stored securely in your candidate vault for university SIWES and academic credit submission.</span>
                        </div>
                    </div>

                    <div class="hiw-chapter-visual">
                        <div class="hiw-visual-frame">
                            <img src="<?= asset('assets/images/company/ipad_Scheduling_Interview_yfgy0h.png') ?>" alt="Calendar-Synced Interviews &amp; Digital Acceptance Letters on iPad" loading="lazy">
                        </div>
                    </div>
                </div>
            </section>

        </div>

        <!-- ====================================================================
             EMPLOYER JOURNEY: EDITORIAL NARRATIVE CHAPTERS (NO BOX CARDS)
             ==================================================================== -->
        <div x-show="journey === 'employers'" style="display: none;" class="hiw-narrative-flow">

            <!-- Chapter 1 -->
            <section class="hiw-chapter">
                <div class="hiw-chapter-grid">
                    <div class="hiw-chapter-text">
                        <div class="hiw-chapter-meta">
                            <span class="hiw-chapter-num">01</span>
                            <span class="hiw-chapter-badge">Corporate Verification</span>
                        </div>
                        <h2 class="hiw-chapter-title">Establish Corporate Legitimacy with CAC Compliance</h2>
                        <p class="hiw-chapter-desc">
                            Top university talent avoids unverified job listings. InternConnect requires all hiring organizations to submit their Corporate Affairs Commission (CAC) RC Number and corporate email address. Our compliance officers audit credentials within 24 hours to award the Verified Employer seal.
                        </p>
                        <ul class="hiw-points-list">
                            <li class="hiw-point-item">
                                <span class="hiw-point-icon">
                                    <svg style="width: 13px; height: 13px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </span>
                                <div><strong>CAC RC / BN Number Verification:</strong> Cross-checked directly against official company registration records to eliminate ghost employers.</div>
                            </li>
                            <li class="hiw-point-item">
                                <span class="hiw-point-icon">
                                    <svg style="width: 13px; height: 13px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </span>
                                <div><strong>Verified Recruiter Badge:</strong> Gain an instant 3.4x higher application response rate by displaying your official trust mark.</div>
                            </li>
                            <li class="hiw-point-item">
                                <span class="hiw-point-icon">
                                    <svg style="width: 13px; height: 13px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </span>
                                <div><strong>Clean Team Collaboration:</strong> Invite hiring managers, HR specialists, and technical interviewers into a consolidated organization workspace.</div>
                            </li>
                        </ul>
                        <div class="hiw-chapter-quote">
                            <svg style="width: 18px; height: 18px; color: var(--ic-primary); flex-shrink: 0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                            <span>100% of corporate accounts pass manual KYC validation before publishing public internship openings.</span>
                        </div>
                    </div>

                    <div class="hiw-chapter-visual">
                        <div class="hiw-visual-frame" style="background: #F8FAFC;">
                            <img src="<?= asset('assets/images/company/Corporate_Legitimacy_image_ninwep.png') ?>" alt="Establish Corporate Legitimacy with CAC Compliance" style="object-fit: contain; padding: 24px;" loading="lazy">
                        </div>
                    </div>
                </div>
            </section>

            <!-- Chapter 2 -->
            <section class="hiw-chapter">
                <div class="hiw-chapter-grid is-reversed">
                    <div class="hiw-chapter-text">
                        <div class="hiw-chapter-meta">
                            <span class="hiw-chapter-num">02</span>
                            <span class="hiw-chapter-badge">AI Role Builder</span>
                        </div>
                        <h2 class="hiw-chapter-title">Publish Role Openings with AI Optimization &amp; Screening Rules</h2>
                        <p class="hiw-chapter-desc">
                            Writing internship job descriptions is tedious. Our 3-step publishing wizard uses Jenny AI (powered by Google Gemini) to suggest industry-standard responsibilities, required qualifications, and custom screening questions tailored to undergraduate and graduate cohorts.
                        </p>
                        <ul class="hiw-points-list">
                            <li class="hiw-point-item">
                                <span class="hiw-point-icon">
                                    <svg style="width: 13px; height: 13px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </span>
                                <div><strong>AI Role Assistant:</strong> Generate clean responsibilities and project expectations tailored to Nigerian market standards in seconds.</div>
                            </li>
                            <li class="hiw-point-item">
                                <span class="hiw-point-icon">
                                    <svg style="width: 13px; height: 13px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </span>
                                <div><strong>Custom Screening Questions:</strong> Add mandatory text, multiple-choice, or portfolio link requirements to filter candidates up front.</div>
                            </li>
                            <li class="hiw-point-item">
                                <span class="hiw-point-icon">
                                    <svg style="width: 13px; height: 13px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </span>
                                <div><strong>Flexible Stipend Structures:</strong> Choose fixed monthly compensation or designate volunteer roles with complete transparency.</div>
                            </li>
                        </ul>
                        <div class="hiw-chapter-quote">
                            <svg style="width: 18px; height: 18px; color: var(--ic-primary); flex-shrink: 0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                            <span>Employers configure and publish verified roles in an average of 4 minutes and 15 seconds.</span>
                        </div>
                    </div>

                    <div class="hiw-chapter-visual">
                        <div class="hiw-visual-frame">
                            <img src="<?= asset('assets/images/company/AI_Optimization_Screening_Rules_tn60ad.png') ?>" alt="Publish Role Openings with AI Optimization &amp; Screening Rules" loading="lazy">
                        </div>
                    </div>
                </div>
            </section>

            <!-- Chapter 3 -->
            <section class="hiw-chapter">
                <div class="hiw-chapter-grid">
                    <div class="hiw-chapter-text">
                        <div class="hiw-chapter-meta">
                            <span class="hiw-chapter-num">03</span>
                            <span class="hiw-chapter-badge">Evaluation &amp; Match Scoring</span>
                        </div>
                        <h2 class="hiw-chapter-title">Rank Applications Automatically with Match Score (0–100%)</h2>
                        <p class="hiw-chapter-desc">
                            Don't spend days manually sifting through hundreds of unorganized PDF resumes. InternConnect automatically scores incoming candidates against your required skills, degree type, and screening answers, presenting a ranked talent workspace ready for fast shortlisting.
                        </p>
                        <ul class="hiw-points-list">
                            <li class="hiw-point-item">
                                <span class="hiw-point-icon">
                                    <svg style="width: 13px; height: 13px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </span>
                                <div><strong>Algorithmic Match Scoring:</strong> Immediately see candidates who score 90%+ match based on coursework, portfolio, and skills.</div>
                            </li>
                            <li class="hiw-point-item">
                                <span class="hiw-point-icon">
                                    <svg style="width: 13px; height: 13px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </span>
                                <div><strong>One-Click Stage Transitions:</strong> Move applicants seamlessly from Applied &rarr; Shortlisted &rarr; Interview Scheduled with automated notifications.</div>
                            </li>
                            <li class="hiw-point-item">
                                <span class="hiw-point-icon">
                                    <svg style="width: 13px; height: 13px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </span>
                                <div><strong>Integrated Candidate Notes:</strong> Leave private evaluation scores, interviewer feedback, and ratings visible only to your internal team.</div>
                            </li>
                        </ul>
                        <div class="hiw-chapter-quote">
                            <svg style="width: 18px; height: 18px; color: var(--ic-primary); flex-shrink: 0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>Reduces recruiter resume screening time by over 68% compared to traditional inboxes.</span>
                        </div>
                    </div>

                    <div class="hiw-chapter-visual">
                        <div class="hiw-visual-frame">
                            <img src="<?= asset('assets/images/company/Match_Score_brqqxk.png') ?>" alt="Rank Applications Automatically with Match Score (0–100%)" loading="lazy">
                        </div>
                    </div>
                </div>
            </section>

            <!-- Chapter 4 -->
            <section class="hiw-chapter">
                <div class="hiw-chapter-grid is-reversed">
                    <div class="hiw-chapter-text">
                        <div class="hiw-chapter-meta">
                            <span class="hiw-chapter-num">04</span>
                            <span class="hiw-chapter-badge">Interviews &amp; Digital Offers</span>
                        </div>
                        <h2 class="hiw-chapter-title">Schedule Interviews Seamlessly &amp; Issue Official Offers</h2>
                        <p class="hiw-chapter-desc">
                            Coordinate interviews effortlessly without external calendar tools. Interviews run in private InternConnect video rooms with preset durations, a live scorecard and Jenny AI follow-up questions. Once you identify your top intern, generate a legally sound digital offer letter right inside your employer dashboard.
                        </p>
                        <ul class="hiw-points-list">
                            <li class="hiw-point-item">
                                <span class="hiw-point-icon">
                                    <svg style="width: 13px; height: 13px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </span>
                                <div><strong>Built-in Video Interviews:</strong> Every interview gets its own secure in-platform video room, with screen sharing, chat and file sharing for both parties.</div>
                            </li>
                            <li class="hiw-point-item">
                                <span class="hiw-point-icon">
                                    <svg style="width: 13px; height: 13px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </span>
                                <div><strong>Reschedule Management:</strong> Review and approve candidate reschedule requests with a single tap, updating calendar invites live.</div>
                            </li>
                            <li class="hiw-point-item">
                                <span class="hiw-point-icon">
                                    <svg style="width: 13px; height: 13px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </span>
                                <div><strong>Digital Offer Generation:</strong> Specify monthly stipend, work schedule, reporting manager, and expiry date. Candidate accepts digitally.</div>
                            </li>
                        </ul>
                        <div class="hiw-chapter-quote">
                            <svg style="width: 18px; height: 18px; color: var(--ic-primary); flex-shrink: 0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>Digital offer letters eliminate administrative bottlenecks, achieving an average 92% offer acceptance speed within 48 hours.</span>
                        </div>
                    </div>

                    <div class="hiw-chapter-visual">
                        <div class="hiw-visual-frame">
                            <img src="<?= asset('assets/images/company/Schedule_Interviews_cllzqd.png') ?>" alt="Schedule Interviews Seamlessly &amp; Issue Official Offers" loading="lazy">
                        </div>
                    </div>
                </div>
            </section>

        </div>

        <!-- Call to Action Banner -->
        <div class="hiw-cta-banner">
            <h2 class="hiw-cta-title">Ready to Experience a Higher Standard of Hiring?</h2>
            <p class="hiw-cta-desc">
                Whether you're starting your career journey or building the next generation of your company, InternConnect is designed for you.
            </p>
            <div style="display: flex; justify-content: center; gap: 14px; flex-wrap: wrap;">
                <a href="<?= url('/register/student') ?>" class="ic-btn ic-btn-primary" style="padding: 12px 28px; font-size: 15px;">
                    <span>Sign Up as a Student</span>
                    <svg style="width: 15px; height: 15px; flex-shrink: 0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
                <a href="<?= url('/register/employer') ?>" class="ic-btn ic-btn-secondary" style="padding: 12px 28px; font-size: 15px;">
                    <span>Post Internship Roles</span>
                </a>
            </div>
        </div>

    </div>

</section>
