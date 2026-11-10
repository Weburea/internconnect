<?php
declare(strict_types=1);
/**
 * View: Candidate AI Career Assistant
 * Route: /candidate/ai-assistant
 * Layout: dashboard (sidebar + topnav)
 *
 * @var array  $candidate            Candidate profile data
 * @var string $candPlan             Active plan ('pro' or 'premium')
 * @var array  $aiMatchedInternships 2 High-match internships
 * @var array  $premiumRoles         3 High-pay global remote roles
 */

$candName      = $candidate['name'] ?? 'Sarah Peters';
$candFirstName = $candidate['first_name'] ?? 'Sarah';
$candRole      = $candidate['role'] ?? 'Student Applicant';
$candPlan      = $candPlan ?? ($candidate['plan'] ?? 'pro');
?>

<div class="db-shell">
    <!-- Sidebar Overlay (mobile) -->
    <div class="db-sidebar-overlay" id="db-sidebar-overlay"></div>

    <?php 
    $activeNav = 'ai-assistant';
    include dirname(__DIR__, 2) . '/shared/components/candidate-sidebar.php'; 
    ?>

    <!-- ══ MAIN CONTENT ══ -->
    <div class="db-main">
        <!-- Top Navbar -->
        <?php include dirname(__DIR__, 2) . '/shared/components/candidate-topnav.php'; ?>

        <div class="db-content" id="db-content" x-data="{ showPremiumModal: false }">
            <style>
            /* ── Page Header with Jenny AI Branding ── */
            .ai-hub-header {
                margin-bottom: 24px;
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 20px;
                position: relative;
            }
            .ai-hub-title {
                font-size: 28px;
                font-weight: 800;
                color: #000623;
                margin: 0 0 6px;
                letter-spacing: -0.02em;
            }
            .ai-hub-sub {
                font-size: 14.5px;
                color: #64748B;
                margin: 0;
            }
            .ai-hub-mascot-img {
                width: 90px;
                height: 90px;
                object-fit: contain;
                flex-shrink: 0;
                filter: drop-shadow(0 10px 20px rgba(79, 72, 230, 0.2));
                animation: jennyFloat 4s ease-in-out infinite alternate;
            }
            @keyframes jennyFloat {
                0% { transform: translateY(0px) rotate(0deg); }
                100% { transform: translateY(-6px) rotate(2deg); }
            }
            @media (max-width: 640px) {
                .ai-hub-mascot-img {
                    display: none;
                }
            }

            /* ── Main Chat / Action Hero Card ── */
            .ai-hero-card {
                background: #FFFFFF;
                border: 1px solid #E2E8F0;
                border-radius: 20px;
                padding: 28px;
                box-shadow: 0 4px 20px -2px rgba(0, 6, 35, 0.05);
                margin-bottom: 36px;
                box-sizing: border-box;
                width: 100%;
            }
            .ai-hero-top {
                display: flex;
                align-items: center;
                gap: 14px;
                margin-bottom: 22px;
            }
            .ai-hero-icon-badge {
                width: 46px;
                height: 46px;
                border-radius: 50%;
                background: #000623;
                border: 2.5px solid #4F48E6;
                color: #FFFFFF;
                display: flex;
                align-items: center;
                justify-content: center;
                flex-shrink: 0;
                box-shadow: 0 4px 14px rgba(79, 72, 230, 0.35);
            }
            .ai-hero-greeting {
                font-size: 21px;
                font-weight: 800;
                color: #000623;
                margin: 0 0 4px;
                display: flex;
                align-items: center;
                gap: 8px;
            }
            .ai-hero-question {
                font-size: 13.5px;
                color: #64748B;
                margin: 0;
            }

            /* 4 Quick Actions Grid */
            .ai-actions-grid {
                display: grid;
                grid-template-columns: minmax(0, 1fr) !important;
                gap: 16px;
                width: 100%;
                box-sizing: border-box;
            }
            @media (min-width: 640px) {
                .ai-actions-grid {
                    grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
                }
            }
            @media (min-width: 1024px) {
                .ai-actions-grid {
                    grid-template-columns: repeat(3, minmax(0, 1fr)) !important;
                }
            }
            .ai-action-card {
                background: #FFFFFF;
                border: 1px solid #E2E8F0;
                border-radius: 16px;
                padding: 18px 16px;
                text-decoration: none;
                display: flex;
                flex-direction: column;
                justify-content: space-between;
                min-height: 120px;
                transition: transform 0.2s ease, border-color 0.2s ease, box-shadow 0.2s ease;
                box-sizing: border-box;
            }
            .ai-action-card:hover {
                transform: translateY(-2px);
                border-color: #C7D2FE;
                box-shadow: 0 8px 20px -4px rgba(79, 72, 230, 0.12);
            }
            .ai-action-icon-wrap {
                width: 32px;
                height: 32px;
                border-radius: 9px;
                background: #EEF2FF;
                color: #4F48E6;
                display: flex;
                align-items: center;
                justify-content: center;
                margin-bottom: 12px;
                flex-shrink: 0;
            }
            .ai-action-title {
                font-size: 14.5px;
                font-weight: 700;
                color: #000623;
                margin: 0 0 4px;
                line-height: 1.3;
            }
            .ai-action-desc {
                font-size: 12px;
                color: #64748B;
                margin: 0;
                line-height: 1.45;
            }

            /* Chat Input Prompt Box */
            .ai-prompt-box {
                margin-top: 22px;
                background: #F8FAFC;
                border: 1px solid #E2E8F0;
                border-radius: 14px;
                padding: 6px 8px 6px 16px;
                display: flex;
                align-items: center;
                gap: 12px;
                transition: border-color 0.15s ease, box-shadow 0.15s ease;
            }
            .ai-prompt-box:focus-within {
                border-color: #4F48E6;
                box-shadow: 0 0 0 3px rgba(79, 72, 230, 0.12);
            }
            .ai-prompt-input {
                flex: 1;
                min-width: 0;
                background: transparent;
                border: none;
                outline: none;
                font-size: 13.5px;
                color: #0F172A;
                font-family: inherit;
            }
            .ai-prompt-input::placeholder {
                color: #94A3B8;
            }
            .ai-prompt-btn {
                height: 38px;
                padding: 0 14px;
                border-radius: 10px;
                background: #4F48E6;
                color: #FFFFFF;
                border: none;
                display: inline-flex;
                align-items: center;
                gap: 8px;
                cursor: pointer;
                flex-shrink: 0;
                font-size: 13px;
                font-weight: 700;
                white-space: nowrap !important;
                box-shadow: 0 3px 10px rgba(79, 72, 230, 0.3);
                transition: background 0.15s ease, transform 0.15s ease;
            }
            .ai-prompt-btn:hover {
                background: #3730A3;
                transform: translateY(-1px);
            }

            /* ── Section Headers ── */
            .ai-section-head {
                display: flex;
                align-items: center;
                justify-content: space-between;
                margin-bottom: 18px;
            }
            .ai-section-title {
                font-size: 20px;
                font-weight: 800;
                color: #000623;
                margin: 0;
                letter-spacing: -0.01em;
            }
            .ai-view-all-link {
                font-size: 13px;
                font-weight: 700;
                color: #4F48E6;
                text-decoration: none;
                display: inline-flex;
                align-items: center;
                gap: 6px;
                white-space: nowrap !important;
                flex-shrink: 0 !important;
            }
            .ai-view-all-link:hover {
                color: #3730A3;
            }

            /* ── AI Matched List Cards ── */
            .ai-matched-list {
                display: flex;
                flex-direction: column;
                gap: 14px;
                margin-bottom: 38px;
            }
            .ai-matched-card {
                background: #FFFFFF;
                border: 1px solid #E2E8F0;
                border-radius: 16px;
                padding: 18px 22px;
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 16px;
                transition: border-color 0.15s ease, box-shadow 0.15s ease;
                box-sizing: border-box;
            }
            .ai-matched-card:hover {
                border-color: #CBD5E1;
                box-shadow: 0 4px 16px -2px rgba(0, 6, 35, 0.05);
            }
            .ai-matched-left {
                display: flex;
                align-items: center;
                gap: 16px;
                min-width: 0;
                flex: 1;
            }
            .ai-matched-logo {
                width: 44px;
                height: 44px;
                border-radius: 12px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 15px;
                font-weight: 800;
                flex-shrink: 0;
            }
            .ai-matched-info {
                min-width: 0;
                flex: 1;
            }
            .ai-matched-role {
                font-size: 15.5px;
                font-weight: 700;
                color: #000623;
                margin: 0 0 4px;
                line-height: 1.25;
            }
            .ai-matched-meta {
                font-size: 13px;
                color: #64748B;
                margin: 0 0 6px;
            }
            .ai-matched-reason {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                font-size: 12px;
                color: #4F48E6;
                font-weight: 600;
            }
            .ai-matched-icon-pill {
                width: 18px;
                height: 18px;
                border-radius: 50%;
                background: #000623;
                border: 1.5px solid #4F48E6;
                color: #FFFFFF;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                flex-shrink: 0;
            }
            .ai-matched-score-pill {
                background: #EEF2FF;
                color: #4338CA;
                border: 1px solid #C7D2FE;
                border-radius: 9999px;
                padding: 6px 14px;
                font-size: 12px;
                font-weight: 800;
                white-space: nowrap !important;
                flex-shrink: 0 !important;
            }

            /* ── Premium Features 3-Column Grid (Homepage Standard) ── */
            .prem-cards-grid {
                display: grid;
                grid-template-columns: minmax(0, 1fr) !important;
                gap: 24px;
                width: 100% !important;
                box-sizing: border-box !important;
            }
            @media (min-width: 640px) {
                .prem-cards-grid {
                    grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
                    gap: 20px;
                }
            }
            @media (min-width: 1100px) {
                .prem-cards-grid {
                    grid-template-columns: repeat(3, minmax(0, 1fr)) !important;
                    gap: 24px;
                }
            }

            .prem-opp-card {
                background: #FFFFFF;
                border: 1px solid #E2E8F0;
                border-radius: 20px;
                padding: 24px;
                box-shadow: 0 4px 20px -2px rgba(0, 6, 35, 0.05);
                display: flex;
                flex-direction: column;
                justify-content: space-between;
                box-sizing: border-box;
                width: 100%;
                min-width: 0;
                transition: transform 0.25s cubic-bezier(0.4, 0, 0.2, 1), box-shadow 0.25s ease, border-color 0.25s ease;
                position: relative;
            }
            .prem-opp-card:hover {
                transform: translateY(-3px);
                border-color: #CBD5E1;
                box-shadow: 0 16px 36px -4px rgba(0, 6, 35, 0.09);
            }
            .prem-opp-top {
                display: flex;
                align-items: flex-start;
                justify-content: space-between;
                gap: 12px;
            }
            .prem-opp-logo-box {
                width: 52px;
                height: 52px;
                border-radius: 14px;
                border: 1px solid #E2E8F0;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 9px;
                box-sizing: border-box;
                box-shadow: 0 2px 8px rgba(0, 6, 35, 0.04);
                flex-shrink: 0;
                overflow: hidden;
            }
            .prem-opp-logo-img {
                width: 32px !important;
                height: 32px !important;
                max-width: 32px !important;
                max-height: 32px !important;
                object-fit: contain !important;
                display: block !important;
                flex-shrink: 0 !important;
            }
            .prem-tag-pill {
                display: inline-flex;
                align-items: center;
                gap: 5px;
                padding: 4px 11px;
                border-radius: 9999px;
                font-size: 11px;
                font-weight: 800;
                background: linear-gradient(135deg, #FEF3C7, #FDE68A);
                color: #92400E;
                border: 1px solid #FCD34D;
                white-space: nowrap !important;
                flex-shrink: 0 !important;
                letter-spacing: 0.03em;
            }
            .prem-opp-title {
                font-size: 18.5px;
                font-weight: 700;
                color: #000623;
                line-height: 1.3;
                margin: 14px 0 4px 0;
            }
            .prem-opp-company {
                font-size: 13.5px;
                font-weight: 500;
                color: #64748B;
                margin: 0 0 14px 0;
                display: flex;
                align-items: center;
                gap: 6px;
            }
            .prem-opp-desc {
                font-size: 13.5px;
                line-height: 1.58;
                color: #475569;
                margin: 0 0 18px 0;
                display: -webkit-box;
                -webkit-line-clamp: 2;
                -webkit-box-orient: vertical;
                overflow: hidden;
            }
            .prem-opp-stats {
                display: flex;
                align-items: center;
                justify-content: space-between;
                background: #F8FAFC;
                border: 1px solid #F1F5F9;
                border-radius: 12px;
                padding: 8px 10px;
                margin-bottom: 20px;
                gap: 4px;
                box-sizing: border-box;
                min-width: 0;
            }
            .prem-opp-stat-item {
                display: flex;
                align-items: center;
                gap: 4px;
                font-size: 11px;
                font-weight: 500;
                color: #475569;
                white-space: nowrap !important;
                flex-shrink: 0 !important;
            }
            .prem-opp-stat-item.stat-stipend {
                font-weight: 800;
                color: #059669;
            }
            .prem-opp-stat-icon {
                width: 13px !important;
                height: 13px !important;
                color: #4F48E6;
                flex-shrink: 0 !important;
            }
            .prem-opp-btn {
                display: flex !important;
                align-items: center !important;
                justify-content: center !important;
                gap: 8px !important;
                width: 100% !important;
                padding: 11px 20px !important;
                background-color: #EEF2FF !important;
                color: #4F48E6 !important;
                border: 1.5px solid #C7D2FE !important;
                font-weight: 700 !important;
                font-size: 13.5px !important;
                border-radius: 12px !important;
                text-decoration: none !important;
                transition: all 0.2s ease !important;
                white-space: nowrap !important;
                flex-shrink: 0 !important;
                cursor: pointer;
                box-sizing: border-box !important;
            }
            .prem-opp-btn:hover {
                background-color: #E0E7FF !important;
                border-color: #A5B4FC !important;
                color: #3730A3 !important;
                transform: translateY(-1px);
            }

            @media (max-width: 640px) {
                .ai-matched-card {
                    flex-direction: column;
                    align-items: flex-start;
                }
                .ai-matched-score-pill {
                    align-self: flex-start;
                }
            }
            </style>

            <!-- ── Page Title & Subtitle with Jenny AI Mascot ── -->
            <div class="ai-hub-header">
                <div>
                    <h1 class="ai-hub-title">AI Career Assistant</h1>
                    <p class="ai-hub-sub">Your AI-powered career companion for getting your next internship.</p>
                </div>
                <img src="https://res.cloudinary.com/weburea/image/upload/v1793434500/jenny_ai_hi_chat_mafe7r.png" alt="Jenny AI Companion" class="ai-hub-mascot-img" loading="eager">
            </div>

            <!-- ── Hero Action Card (Screenshot 1) ── -->
            <div class="ai-hero-card">
                <div class="ai-hero-top">
                    <div class="ai-hero-icon-badge" title="Jenny AI Career Assistant">
                        <!-- Official Jenny AI Vector Mark inside Circle Badge -->
                        <svg style="width: 22px; height: 22px; flex-shrink: 0;" viewBox="0 0 74 83" fill="currentColor">
                            <path d="M73.74 32.4627V59.9427C67.93 59.7673 62.2767 61.8422 57.9596 65.7344C53.6426 69.6267 50.9952 75.0356 50.57 80.8327C50.52 81.3027 50.48 81.8327 50.46 82.2827H23.57C23.57 82.0227 23.48 81.7627 23.43 81.5127C20.73 67.1427 16.41 63.2427 0 59.6527V32.6527C13.34 31.5727 27.12 37.6527 36.92 47.9327C46 38.3527 58.99 31.8927 73.74 32.4627Z"/>
                            <path d="M37.0001 0.0031237C40.4833 0.0367014 43.8785 1.10075 46.7578 3.06112C49.637 5.02149 51.8715 7.79043 53.1794 11.0188C54.4873 14.2473 54.8101 17.7906 54.1071 21.2023C53.4042 24.6139 51.7069 27.741 49.2293 30.1894C46.7516 32.6378 43.6046 34.2979 40.1849 34.9604C36.7652 35.6228 33.2259 35.258 30.0132 33.9119C26.8005 32.5658 24.0583 30.2987 22.1322 27.3963C20.2061 24.494 19.1824 21.0864 19.1901 17.6031C19.1737 15.2695 19.6247 12.9562 20.5166 10.7997C21.4084 8.64315 22.7231 6.68703 24.383 5.04669C26.0429 3.40635 28.0145 2.11499 30.1814 1.24877C32.3484 0.382549 34.6669 -0.040991 37.0001 0.0031237Z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="ai-hero-greeting">
                            <span>Hi <?= htmlspecialchars($candFirstName) ?></span>
                        </h2>
                        <p class="ai-hero-question">What would you like to work on today?</p>
                    </div>
                </div>

                <!-- 6 Quick Action Cards (Version 2.0 AI Tools) -->
                <div class="ai-actions-grid">
                    <!-- 1. Analyze My CV -->
                    <a href="<?= url('/candidate/ai-assistant/cv-analyze') ?>" class="ai-action-card">
                        <div>
                            <div class="ai-action-icon-wrap">
                                <svg style="width: 17px; height: 17px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <h3 class="ai-action-title">Analyze My CV</h3>
                            <p class="ai-action-desc">Get AI feedback &amp; keyword gap scoring on your CV.</p>
                        </div>
                    </a>

                    <!-- 2. AI Cover Letter Generator (New) -->
                    <a href="<?= url('/candidate/ai-assistant/cover-letter') ?>" class="ai-action-card">
                        <div>
                            <div class="ai-action-icon-wrap" style="background: #FDF2F8; color: #DB2777;">
                                <svg style="width: 17px; height: 17px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <h3 class="ai-action-title">AI Cover Letter</h3>
                            <p class="ai-action-desc">Generate tailored, ATS-ready letters with 4 tones.</p>
                        </div>
                    </a>

                    <!-- 3. AI Career Coach & Roadmap (New) -->
                    <a href="<?= url('/candidate/ai-assistant/career-coach') ?>" class="ai-action-card">
                        <div>
                            <div class="ai-action-icon-wrap" style="background: #ECFDF5; color: #059669;">
                                <svg style="width: 17px; height: 17px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                </svg>
                            </div>
                            <h3 class="ai-action-title">Career Coach &amp; Roadmap</h3>
                            <p class="ai-action-desc">Skill diagnostic &amp; 12-week progression milestones.</p>
                        </div>
                    </a>

                    <!-- 4. Find Internships -->
                    <a href="<?= url('/candidate/ai-assistant/matched') ?>" class="ai-action-card">
                        <div>
                            <div class="ai-action-icon-wrap">
                                <svg style="width: 17px; height: 17px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                            <h3 class="ai-action-title">Find Internships</h3>
                            <p class="ai-action-desc">Discover opportunities matched to your profile.</p>
                        </div>
                    </a>

                    <!-- 5. Prepare for an Interview -->
                    <a href="<?= url('/candidate/ai-assistant/interview-prep') ?>" class="ai-action-card">
                        <div>
                            <div class="ai-action-icon-wrap">
                                <svg style="width: 17px; height: 17px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 100-6 3 3 0 000 6z"/>
                                </svg>
                            </div>
                            <h3 class="ai-action-title">Mock Interview Prep</h3>
                            <p class="ai-action-desc">Practice questions tailored to your target role.</p>
                        </div>
                    </a>

                    <!-- 6. Improve My Application -->
                    <a href="<?= url('/candidate/applications') ?>" class="ai-action-card">
                        <div>
                            <div class="ai-action-icon-wrap">
                                <svg style="width: 17px; height: 17px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </div>
                            <h3 class="ai-action-title">Improve Application</h3>
                            <p class="ai-action-desc">Get feedback before submitting an application.</p>
                        </div>
                    </a>
                </div>

                <!-- Chat Prompt Input Bar with Jenny AI Action Button -->
                <div class="ai-prompt-box">
                    <input type="text" class="ai-prompt-input" placeholder="Ask your career assistant anything..." aria-label="Ask your career assistant anything">
                    <button type="button" class="ai-prompt-btn" aria-label="Send prompt to Jenny AI">
                        <!-- Jenny AI Mark inside Button -->
                        <svg style="width: 14px; height: 14px; flex-shrink: 0;" viewBox="0 0 74 83" fill="currentColor">
                            <path d="M73.74 32.4627V59.9427C67.93 59.7673 62.2767 61.8422 57.9596 65.7344C53.6426 69.6267 50.9952 75.0356 50.57 80.8327C50.52 81.3027 50.48 81.8327 50.46 82.2827H23.57C23.57 82.0227 23.48 81.7627 23.43 81.5127C20.73 67.1427 16.41 63.2427 0 59.6527V32.6527C13.34 31.5727 27.12 37.6527 36.92 47.9327C46 38.3527 58.99 31.8927 73.74 32.4627Z"/>
                            <path d="M37.0001 0.0031237C40.4833 0.0367014 43.8785 1.10075 46.7578 3.06112C49.637 5.02149 51.8715 7.79043 53.1794 11.0188C54.4873 14.2473 54.8101 17.7906 54.1071 21.2023C53.4042 24.6139 51.7069 27.741 49.2293 30.1894C46.7516 32.6378 43.6046 34.2979 40.1849 34.9604C36.7652 35.6228 33.2259 35.258 30.0132 33.9119C26.8005 32.5658 24.0583 30.2987 22.1322 27.3963C20.2061 24.494 19.1824 21.0864 19.1901 17.6031C19.1737 15.2695 19.6247 12.9562 20.5166 10.7997C21.4084 8.64315 22.7231 6.68703 24.383 5.04669C26.0429 3.40635 28.0145 2.11499 30.1814 1.24877C32.3484 0.382549 34.6669 -0.040991 37.0001 0.0031237Z"/>
                        </svg>
                        <svg style="width: 14px; height: 14px; flex-shrink: 0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- ── AI-Matched Internships Section (Screenshot 1) ── -->
            <div id="ai-matched-internships">
                <div class="ai-section-head">
                    <h2 class="ai-section-title">AI-Matched Internships</h2>
                    <a href="<?= url('/candidate/ai-assistant/matched') ?>" class="ai-view-all-link">
                        <span>View All Matches</span>
                        <svg style="width: 14px; height: 14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </a>
                </div>

                <div class="ai-matched-list">
                    <?php foreach ($aiMatchedInternships as $match): ?>
                    <div class="ai-matched-card">
                        <div class="ai-matched-left">
                            <div class="ai-matched-logo" style="background-color: <?= htmlspecialchars($match['logo_bg']) ?>; color: <?= htmlspecialchars($match['logo_color']) ?>;">
                                <?= htmlspecialchars($match['logo_text']) ?>
                            </div>
                            <div class="ai-matched-info">
                                <h3 class="ai-matched-role"><?= htmlspecialchars($match['role']) ?> — <?= htmlspecialchars($match['company']) ?></h3>
                                <div class="ai-matched-meta"><?= htmlspecialchars($match['stipend']) ?> · <?= htmlspecialchars($match['location']) ?> · <?= htmlspecialchars($match['duration']) ?></div>
                                <div class="ai-matched-reason">
                                    <div class="ai-matched-icon-pill">
                                        <svg style="width: 10px; height: 11px; flex-shrink: 0;" viewBox="0 0 74 83" fill="currentColor">
                                            <path d="M73.74 32.4627V59.9427C67.93 59.7673 62.2767 61.8422 57.9596 65.7344C53.6426 69.6267 50.9952 75.0356 50.57 80.8327C50.52 81.3027 50.48 81.8327 50.46 82.2827H23.57C23.57 82.0227 23.48 81.7627 23.43 81.5127C20.73 67.1427 16.41 63.2427 0 59.6527V32.6527C13.34 31.5727 27.12 37.6527 36.92 47.9327C46 38.3527 58.99 31.8927 73.74 32.4627Z"/>
                                            <path d="M37.0001 0.0031237C40.4833 0.0367014 43.8785 1.10075 46.7578 3.06112C49.637 5.02149 51.8715 7.79043 53.1794 11.0188C54.4873 14.2473 54.8101 17.7906 54.1071 21.2023C53.4042 24.6139 51.7069 27.741 49.2293 30.1894C46.7516 32.6378 43.6046 34.2979 40.1849 34.9604C36.7652 35.6228 33.2259 35.258 30.0132 33.9119C26.8005 32.5658 24.0583 30.2987 22.1322 27.3963C20.2061 24.494 19.1824 21.0864 19.1901 17.6031C19.1737 15.2695 19.6247 12.9562 20.5166 10.7997C21.4084 8.64315 22.7231 6.68703 24.383 5.04669C26.0429 3.40635 28.0145 2.11499 30.1814 1.24877C32.3484 0.382549 34.6669 -0.040991 37.0001 0.0031237Z"/>
                                        </svg>
                                    </div>
                                    <span><?= htmlspecialchars($match['match_reason']) ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="ai-matched-score-pill">
                            <?= htmlspecialchars((string)$match['match_pct']) ?>% match
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- ── Premium Features Section (3-Column Grid Matching Homepage per User Request) ── -->
            <div>
                <div class="ai-section-head">
                    <div>
                        <h2 class="ai-section-title">Premium Features &amp; Global High-Pay Roles</h2>
                    </div>
                    <?php if ($candPlan !== 'premium'): ?>
                        <a href="<?= url('/pricing?plan=premium') ?>" class="ai-view-all-link">
                            <span>Upgrade to Premium</span>
                            <svg style="width: 14px; height: 14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </a>
                    <?php endif; ?>
                </div>

                <!-- 3-Column Opportunity Cards -->
                <div class="prem-cards-grid">
                    <?php foreach ($premiumRoles as $role): ?>
                    <div class="prem-opp-card">
                        <div>
                            <div class="prem-opp-top">
                                <div class="prem-opp-logo-box" style="background-color: <?= htmlspecialchars($role['logo_bg']) ?>; <?= $role['logo_bg'] === '#000623' ? 'border-color: #1E293B;' : '' ?>">
                                    <img src="<?= htmlspecialchars($role['logo']) ?>" alt="<?= htmlspecialchars($role['company']) ?>" class="prem-opp-logo-img" loading="lazy">
                                </div>
                                <span class="prem-tag-pill">
                                    <svg style="width: 11px; height: 11px;" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                                    </svg>
                                    PREMIUM
                                </span>
                            </div>

                            <h3 class="prem-opp-title"><?= htmlspecialchars($role['title']) ?></h3>
                            <p class="prem-opp-company">
                                <span style="font-weight: 600; color: #1E293B;"><?= htmlspecialchars($role['company']) ?></span>
                                <span>&bull;</span>
                                <span><?= htmlspecialchars($role['location']) ?></span>
                            </p>

                            <p class="prem-opp-desc"><?= htmlspecialchars($role['description']) ?></p>
                        </div>

                        <div>
                            <!-- Stat Row -->
                            <div class="prem-opp-stats">
                                <div class="prem-opp-stat-item">
                                    <svg class="prem-opp-stat-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5a2 2 0 011.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V5a2 2 0 012-2z"/>
                                    </svg>
                                    <span><?= htmlspecialchars($role['category']) ?></span>
                                </div>

                                <div style="width: 1px; height: 14px; background: #E2E8F0;"></div>

                                <div class="prem-opp-stat-item">
                                    <svg class="prem-opp-stat-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5z" />
                                    </svg>
                                    <span><?= htmlspecialchars($role['experience']) ?></span>
                                </div>

                                <div style="width: 1px; height: 14px; background: #E2E8F0;"></div>

                                <div class="prem-opp-stat-item stat-stipend">
                                    <span><?= htmlspecialchars($role['stipend']) ?></span>
                                </div>
                            </div>

                            <!-- Button -->
                            <?php if ($candPlan === 'premium'): ?>
                                <a href="<?= url('/internship/' . $role['slug']) ?>" class="prem-opp-btn">
                                    <span>Apply with Premium Priority</span>
                                    <svg style="width: 14px; height: 14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                    </svg>
                                </a>
                            <?php else: ?>
                                <button type="button" @click="showPremiumModal = true" class="prem-opp-btn">
                                    <svg style="width: 13px; height: 13px; color: #D97706;" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                                    </svg>
                                    <span>Unlock with Premium</span>
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Premium Upgrade Modal -->
            <div x-show="showPremiumModal" style="display: none; position: fixed; inset: 0; z-index: 100; align-items: center; justify-content: center;" class="flex">
                <div style="position: absolute; inset: 0; background: rgba(0,6,35,0.65); backdrop-filter: blur(6px);" @click="showPremiumModal = false"></div>
                <div style="position: relative; background: #FFFFFF; border-radius: 24px; padding: 32px; max-width: 480px; width: 90%; z-index: 10; box-shadow: 0 25px 50px -12px rgba(0, 6, 35, 0.25); text-align: center;">
                    <div style="width: 54px; height: 54px; border-radius: 16px; background: #EEF2FF; color: #4F48E6; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px; border: 1px solid #C7D2FE;">
                        <svg style="width: 26px; height: 26px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </div>
                    <h3 style="font-size: 20px; font-weight: 800; color: #000623; margin: 0 0 8px;">Unlock Global High-Pay Roles</h3>
                    <p style="font-size: 13.5px; color: #64748B; line-height: 1.5; margin: 0 0 22px;">Upgrade to InternConnect Premium to apply directly to high-paying international remote roles, get AI automated CV rewriting, and priority recruiter direct messaging.</p>
                    <div style="display: flex; gap: 10px; justify-content: center;">
                        <button type="button" @click="showPremiumModal = false" style="background: #F1F5F9; color: #475569; border: none; font-size: 13.5px; font-weight: 700; padding: 10px 18px; border-radius: 12px; cursor: pointer;">Maybe Later</button>
                        <a href="<?= url('/pricing?plan=premium') ?>" style="background: #4F48E6; color: #FFFFFF; font-size: 13.5px; font-weight: 700; padding: 10px 22px; border-radius: 12px; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">Upgrade to Premium →</a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
