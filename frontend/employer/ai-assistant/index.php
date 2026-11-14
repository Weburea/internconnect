<?php
declare(strict_types=1);
/**
 * View: Employer AI Talent Assistant
 * Route: /employer/ai-assistant
 * Layout: dashboard (employer-sidebar + candidate-topnav/header)
 *
 * @var array  $employer            Employer profile data
 * @var array  $aiMatchedCandidates Ranked matching candidate profiles
 * @var array  $activeRoles         Active roles assisted by AI
 */

$companyName = $employer['company_name'] ?? 'Global Tech Solutions';
$recruiterFirstName = $employer['first_name'] ?? 'Tunde';
?>

<div class="db-shell">
    <div class="db-sidebar-overlay" id="db-sidebar-overlay"></div>

    <?php 
    $activeNav = 'ai-assistant';
    include dirname(__DIR__, 2) . '/shared/components/employer-sidebar.php'; 
    ?>

    <!-- ══ MAIN CONTENT ══ -->
    <div class="db-main">
        <!-- Top Navbar -->
        <header class="db-topnav" style="display: flex; align-items: center; justify-content: space-between; padding: 16px 28px; background: #FFFFFF; border-bottom: 1px solid #E2E8F0;">
            <div style="display: flex; align-items: center; gap: 14px;">
                <button type="button" class="db-hamburger" id="db-hamburger" aria-label="Toggle navigation drawer" style="background: none; border: none; cursor: pointer; display: flex; align-items: center; justify-content: center; padding: 6px; border-radius: 8px;">
                    <svg style="width: 20px; height: 20px; color: #000623;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                <div style="font-size: 14px; font-weight: 600; color: #64748B;">
                    <span>Employer Portal</span>
                    <span style="margin: 0 6px;">/</span>
                    <span style="color: #000623; font-weight: 700;">AI Assistant</span>
                </div>
            </div>

            <div style="display: flex; align-items: center; gap: 14px;">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <div style="width: 36px; height: 36px; border-radius: 50%; background: #4F48E6; color: #FFFFFF; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 13px;">
                        <?= strtoupper(substr($companyName, 0, 1)) ?>
                    </div>
                    <div style="display: none;" class="md:block">
                        <div style="font-size: 13px; font-weight: 700; color: #000623; line-height: 1.2;"><?= htmlspecialchars($companyName) ?></div>
                        <div style="font-size: 11px; color: #64748B;">Hiring Lead</div>
                    </div>
                </div>
            </div>
        </header>

        <div class="db-content" id="db-content" style="padding: 32px 28px; box-sizing: border-box; width: 100%; max-width: 1200px; margin: 0 auto;">
            <style>
            /* ── Page Header with Jenny AI Branding ── */
            .emp-ai-header {
                margin-bottom: 24px;
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 20px;
                position: relative;
            }
            .emp-ai-title {
                font-size: 28px;
                font-weight: 800;
                color: #000623;
                margin: 0 0 6px;
                letter-spacing: -0.02em;
            }
            .emp-ai-sub {
                font-size: 14.5px;
                color: #64748B;
                margin: 0;
            }
            .emp-ai-mascot-img {
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
                .emp-ai-mascot-img {
                    display: none;
                }
            }

            /* ── Main Hero Card ── */
            .emp-ai-hero-card {
                background: #FFFFFF;
                border: 1px solid #E2E8F0;
                border-radius: 20px;
                padding: 28px;
                box-shadow: 0 4px 20px -2px rgba(0, 6, 35, 0.05);
                margin-bottom: 36px;
                box-sizing: border-box;
                width: 100%;
            }
            .emp-ai-hero-top {
                display: flex;
                align-items: center;
                gap: 14px;
                margin-bottom: 22px;
            }
            .emp-ai-icon-badge {
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
            .emp-ai-greeting {
                font-size: 21px;
                font-weight: 800;
                color: #000623;
                margin: 0 0 4px;
            }
            .emp-ai-question {
                font-size: 13.5px;
                color: #64748B;
                margin: 0;
            }

            /* 4 Quick Actions Grid */
            .emp-ai-actions-grid {
                display: grid;
                grid-template-columns: minmax(0, 1fr) !important;
                gap: 16px;
                width: 100%;
                box-sizing: border-box;
            }
            @media (min-width: 640px) {
                .emp-ai-actions-grid {
                    grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
                }
            }
            @media (min-width: 1024px) {
                .emp-ai-actions-grid {
                    grid-template-columns: repeat(4, minmax(0, 1fr)) !important;
                }
            }
            .emp-ai-action-card {
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
            .emp-ai-action-card:hover {
                transform: translateY(-2px);
                border-color: #C7D2FE;
                box-shadow: 0 8px 20px -4px rgba(79, 72, 230, 0.12);
            }
            .emp-ai-action-icon-wrap {
                width: 34px;
                height: 34px;
                border-radius: 10px;
                background: #EEF2FF;
                color: #4F48E6;
                display: flex;
                align-items: center;
                justify-content: center;
                margin-bottom: 12px;
                flex-shrink: 0;
            }
            .emp-ai-action-title {
                font-size: 14.5px;
                font-weight: 700;
                color: #000623;
                margin: 0 0 4px;
                line-height: 1.3;
            }
            .emp-ai-action-desc {
                font-size: 12px;
                color: #64748B;
                margin: 0;
                line-height: 1.45;
            }

            /* Prompt Box */
            .emp-ai-prompt-box {
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
            .emp-ai-prompt-box:focus-within {
                border-color: #4F48E6;
                box-shadow: 0 0 0 3px rgba(79, 72, 230, 0.12);
            }
            .emp-ai-prompt-input {
                flex: 1;
                min-width: 0;
                background: transparent;
                border: none;
                outline: none;
                font-size: 13.5px;
                color: #0F172A;
                font-family: inherit;
            }
            .emp-ai-prompt-input::placeholder {
                color: #94A3B8;
            }
            .emp-ai-prompt-btn {
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
            .emp-ai-prompt-btn:hover {
                background: #3730A3;
                transform: translateY(-1px);
            }

            /* ── Section Headers ── */
            .emp-ai-section-head {
                display: flex;
                align-items: center;
                justify-content: space-between;
                margin-bottom: 18px;
            }
            .emp-ai-section-title {
                font-size: 20px;
                font-weight: 800;
                color: #000623;
                margin: 0;
                letter-spacing: -0.01em;
            }
            .emp-ai-view-all-link {
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
            .emp-ai-view-all-link:hover {
                color: #3730A3;
            }

            /* ── Matched Candidate Cards ── */
            .emp-cand-list {
                display: flex;
                flex-direction: column;
                gap: 14px;
                margin-bottom: 38px;
            }
            .emp-cand-card {
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
            .emp-cand-card:hover {
                border-color: #CBD5E1;
                box-shadow: 0 4px 16px -2px rgba(0, 6, 35, 0.05);
            }
            .emp-cand-left {
                display: flex;
                align-items: center;
                gap: 16px;
                min-width: 0;
                flex: 1;
            }
            .emp-cand-avatar {
                width: 48px;
                height: 48px;
                border-radius: 14px;
                object-fit: cover;
                border: 1px solid #E2E8F0;
                flex-shrink: 0;
            }
            .emp-cand-name {
                font-size: 15.5px;
                font-weight: 700;
                color: #000623;
                margin: 0 0 3px;
                line-height: 1.25;
            }
            .emp-cand-meta {
                font-size: 13px;
                color: #64748B;
                margin: 0 0 6px;
            }
            .emp-cand-reason {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                font-size: 12px;
                color: #4F48E6;
                font-weight: 600;
            }
            .emp-cand-icon-pill {
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
            .emp-cand-actions {
                display: flex;
                align-items: center;
                gap: 12px;
                flex-shrink: 0;
            }
            .emp-cand-score-pill {
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
            .emp-cand-btn-primary {
                background: #4F48E6;
                color: #FFFFFF !important;
                font-size: 12.5px;
                font-weight: 700;
                padding: 7px 16px;
                border-radius: 10px;
                text-decoration: none;
                display: inline-flex;
                align-items: center;
                gap: 6px;
                white-space: nowrap !important;
                flex-shrink: 0 !important;
                transition: background 0.15s ease;
            }
            .emp-cand-btn-primary:hover {
                background: #3730A3;
            }

            /* ── Active Roles Grid ── */
            .emp-roles-grid {
                display: grid;
                grid-template-columns: minmax(0, 1fr) !important;
                gap: 18px;
                width: 100%;
                box-sizing: border-box;
            }
            @media (min-width: 640px) {
                .emp-roles-grid {
                    grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
                }
            }
            .emp-role-card {
                background: #FFFFFF;
                border: 1px solid #E2E8F0;
                border-radius: 16px;
                padding: 20px 22px;
                box-sizing: border-box;
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 16px;
            }

            @media (max-width: 640px) {
                .emp-cand-card {
                    flex-direction: column;
                    align-items: flex-start;
                }
                .emp-cand-actions {
                    width: 100%;
                    justify-content: space-between;
                }
            }
            </style>

            <!-- ── Page Title & Subtitle with Jenny AI Mascot ── -->
            <div class="emp-ai-header">
                <div>
                    <h1 class="emp-ai-title">Jenny AI Talent Assistant</h1>
                    <p class="emp-ai-sub">Your intelligent hiring partner for posting roles, ranking top candidates, and auto-scheduling interviews.</p>
                </div>
                <div style="display: flex; align-items: center; gap: 14px; flex-shrink: 0;">
                    <?php $coinType = 'employer'; $coinId = (int)($_SESSION['employer_id'] ?? 1); include dirname(__DIR__, 2) . '/shared/components/jenny-coin-pill.php'; ?>
                    <img src="https://res.cloudinary.com/weburea/image/upload/v1793434500/jenny_ai_hi_chat_mafe7r.png" alt="Jenny AI Companion" class="emp-ai-mascot-img" loading="eager">
                </div>
            </div>

            <!-- ── Hero Action Card ── -->
            <div class="emp-ai-hero-card">
                <div class="emp-ai-hero-top">
                    <div class="emp-ai-icon-badge" aria-label="Jenny AI Employer Partner">
                        <!-- Official Jenny AI Vector Mark inside Circle Badge -->
                        <svg style="width: 22px; height: 22px; flex-shrink: 0;" viewBox="0 0 74 83" fill="currentColor">
                            <path d="M73.74 32.4627V59.9427C67.93 59.7673 62.2767 61.8422 57.9596 65.7344C53.6426 69.6267 50.9952 75.0356 50.57 80.8327C50.52 81.3027 50.48 81.8327 50.46 82.2827H23.57C23.57 82.0227 23.48 81.7627 23.43 81.5127C20.73 67.1427 16.41 63.2427 0 59.6527V32.6527C13.34 31.5727 27.12 37.6527 36.92 47.9327C46 38.3527 58.99 31.8927 73.74 32.4627Z"/>
                            <path d="M37.0001 0.0031237C40.4833 0.0367014 43.8785 1.10075 46.7578 3.06112C49.637 5.02149 51.8715 7.79043 53.1794 11.0188C54.4873 14.2473 54.8101 17.7906 54.1071 21.2023C53.4042 24.6139 51.7069 27.741 49.2293 30.1894C46.7516 32.6378 43.6046 34.2979 40.1849 34.9604C36.7652 35.6228 33.2259 35.258 30.0132 33.9119C26.8005 32.5658 24.0583 30.2987 22.1322 27.3963C20.2061 24.494 19.1824 21.0864 19.1901 17.6031C19.1737 15.2695 19.6247 12.9562 20.5166 10.7997C21.4084 8.64315 22.7231 6.68703 24.383 5.04669C26.0429 3.40635 28.0145 2.11499 30.1814 1.24877C32.3484 0.382549 34.6669 -0.040991 37.0001 0.0031237Z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="emp-ai-greeting">Hi <?= htmlspecialchars($recruiterFirstName) ?></h2>
                        <p class="emp-ai-question">How can Jenny AI assist your hiring team today?</p>
                    </div>
                </div>

                <!-- 4 Quick Action Cards for Employers -->
                <div class="emp-ai-actions-grid">
                    <!-- 1. Post Internship with AI Builder -->
                    <a href="<?= url('/employer/internships/create') ?>" class="emp-ai-action-card">
                        <div>
                            <div class="emp-ai-action-icon-wrap">
                                <svg style="width: 18px; height: 18px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <h3 class="emp-ai-action-title">Draft Role Opening</h3>
                            <p class="emp-ai-action-desc">Let AI generate duties, skills &amp; screening rules for your post.</p>
                        </div>
                    </a>

                    <!-- 2. Find Best-Matched Candidates -->
                    <a href="<?= url('/employer/candidates') ?>" class="emp-ai-action-card">
                        <div>
                            <div class="emp-ai-action-icon-wrap">
                                <svg style="width: 18px; height: 18px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                            </div>
                            <h3 class="emp-ai-action-title">Match Best Candidates</h3>
                            <p class="emp-ai-action-desc">Review pre-ranked applicants with 90%+ match scores.</p>
                        </div>
                    </a>

                    <!-- 3. Auto-Schedule Interviews -->
                    <a href="<?= url('/employer/interviews/schedule') ?>" class="emp-ai-action-card">
                        <div>
                            <div class="emp-ai-action-icon-wrap">
                                <svg style="width: 18px; height: 18px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <h3 class="emp-ai-action-title">Auto-Schedule Interview</h3>
                            <p class="emp-ai-action-desc">AI suggests optimal meeting slots and generates invites.</p>
                        </div>
                    </a>

                    <!-- 4. Review Applications -->
                    <a href="<?= url('/employer/candidates') ?>" class="emp-ai-action-card">
                        <div>
                            <div class="emp-ai-action-icon-wrap">
                                <svg style="width: 18px; height: 18px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                </svg>
                            </div>
                            <h3 class="emp-ai-action-title">Smart Screening</h3>
                            <p class="emp-ai-action-desc">Automatically evaluate applicants against role requirements.</p>
                        </div>
                    </a>
                </div>

                <!-- Chat Prompt Input Bar -->
                <div class="emp-ai-prompt-box">
                    <input type="text" class="emp-ai-prompt-input" placeholder="Ask Jenny AI to draft a role, summarize applicants, or find candidates..." aria-label="Ask Jenny AI">
                    <button type="button" class="emp-ai-prompt-btn" aria-label="Send prompt to Jenny AI">
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

            <!-- ── Top AI-Matched Candidates for Your Roles ── -->
            <div>
                <div class="emp-ai-section-head">
                    <h2 class="emp-ai-section-title">Top AI-Matched Candidates for Your Openings</h2>
                    <a href="<?= url('/employer/candidates') ?>" class="emp-ai-view-all-link">
                        <span>View All Candidates</span>
                        <svg style="width: 14px; height: 14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </a>
                </div>

                <div class="emp-cand-list">
                    <?php foreach ($aiMatchedCandidates as $cand): ?>
                    <div class="emp-cand-card">
                        <div class="emp-cand-left">
                            <img src="<?= htmlspecialchars($cand['avatar']) ?>" alt="<?= htmlspecialchars($cand['name']) ?>" class="emp-cand-avatar">
                            <div>
                                <h3 class="emp-cand-name"><?= htmlspecialchars($cand['name']) ?> · <span style="font-weight: 500; color: #475569;"><?= htmlspecialchars($cand['role']) ?></span></h3>
                                <div class="emp-cand-meta"><?= htmlspecialchars($cand['school']) ?> · <span style="color: #059669; font-weight: 700;"><?= htmlspecialchars($cand['status']) ?></span></div>
                                <div class="emp-cand-reason">
                                    <div class="emp-cand-icon-pill">
                                        <svg style="width: 10px; height: 11px; flex-shrink: 0;" viewBox="0 0 74 83" fill="currentColor">
                                            <path d="M73.74 32.4627V59.9427C67.93 59.7673 62.2767 61.8422 57.9596 65.7344C53.6426 69.6267 50.9952 75.0356 50.57 80.8327C50.52 81.3027 50.48 81.8327 50.46 82.2827H23.57C23.57 82.0227 23.48 81.7627 23.43 81.5127C20.73 67.1427 16.41 63.2427 0 59.6527V32.6527C13.34 31.5727 27.12 37.6527 36.92 47.9327C46 38.3527 58.99 31.8927 73.74 32.4627Z"/>
                                            <path d="M37.0001 0.0031237C40.4833 0.0367014 43.8785 1.10075 46.7578 3.06112C49.637 5.02149 51.8715 7.79043 53.1794 11.0188C54.4873 14.2473 54.8101 17.7906 54.1071 21.2023C53.4042 24.6139 51.7069 27.741 49.2293 30.1894C46.7516 32.6378 43.6046 34.2979 40.1849 34.9604C36.7652 35.6228 33.2259 35.258 30.0132 33.9119C26.8005 32.5658 24.0583 30.2987 22.1322 27.3963C20.2061 24.494 19.1824 21.0864 19.1901 17.6031C19.1737 15.2695 19.6247 12.9562 20.5166 10.7997C21.4084 8.64315 22.7231 6.68703 24.383 5.04669C26.0429 3.40635 28.0145 2.11499 30.1814 1.24877C32.3484 0.382549 34.6669 -0.040991 37.0001 0.0031237Z"/>
                                        </svg>
                                    </div>
                                    <span><?= htmlspecialchars($cand['match_reason']) ?></span>
                                </div>
                            </div>
                        </div>

                        <div class="emp-cand-actions">
                            <div class="emp-cand-score-pill">
                                <?= htmlspecialchars((string)$cand['match_score']) ?>% Match
                            </div>
                            <a href="<?= url('/employer/interviews/schedule?candidate_id=' . $cand['id']) ?>" class="emp-cand-btn-primary">
                                <span>Schedule Interview</span>
                                <svg style="width: 13px; height: 13px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- ── Active Role Optimizations ── -->
            <div>
                <div class="emp-ai-section-head">
                    <h2 class="emp-ai-section-title">Active Role Openings Under AI Optimization</h2>
                    <a href="<?= url('/employer/internships') ?>" class="emp-ai-view-all-link">
                        <span>Manage All Openings</span>
                        <svg style="width: 14px; height: 14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </a>
                </div>

                <div class="emp-roles-grid">
                    <?php foreach ($activeRoles as $role): ?>
                    <div class="emp-role-card">
                        <div>
                            <span style="display: inline-block; font-size: 11px; font-weight: 800; color: #4F48E6; background: #EEF2FF; border: 1px solid #C7D2FE; padding: 2px 8px; border-radius: 99px; margin-bottom: 6px;">
                                <?= htmlspecialchars($role['status']) ?>
                            </span>
                            <h3 style="font-size: 16px; font-weight: 700; color: #000623; margin: 0 0 4px;"><?= htmlspecialchars($role['title']) ?></h3>
                            <p style="font-size: 12.5px; color: #64748B; margin: 0;"><?= htmlspecialchars((string)$role['applicants']) ?> Total Applicants · <strong style="color: #059669;"><?= htmlspecialchars((string)$role['high_matches']) ?> High Matches</strong></p>
                        </div>
                        <a href="<?= url('/employer/candidates?role=' . urlencode($role['title'])) ?>" style="font-size: 12.5px; font-weight: 700; color: #4F48E6; text-decoration: none; display: inline-flex; align-items: center; gap: 4px; white-space: nowrap !important; flex-shrink: 0 !important;">
                            <span>Review</span>
                            <svg style="width: 13px; height: 13px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </a>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

        </div>
    </div>
</div>

