<?php
declare(strict_types=1);
/**
 * View: Candidate AI CV Analysis Results
 * Route: /candidate/ai-assistant/cv-results
 * Layout: dashboard
 *
 * @var array  $candidate   Candidate profile data
 * @var string $candPlan    Active plan ('pro' or 'premium')
 * @var array  $analysis    Analysis summary and score data
 */

$candName      = $candidate['name'] ?? 'Sarah Peters';
$candFirstName = $candidate['first_name'] ?? 'Sarah';
$candPlan      = $candPlan ?? ($candidate['plan'] ?? 'pro');
$targetRole    = $analysis['target_role'] ?? 'Product Designer';
$targetScore   = (int)($analysis['score'] ?? 86);
$scoreRating   = $analysis['score_rating'] ?? 'Above Average';
$scoreHeadline = $analysis['score_headline'] ?? 'Good foundation';
$scoreSubtext  = $analysis['score_subtext'] ?? "Your CV demonstrates strong {$targetRole} experience, but there are several opportunities to improve its impact and ATS compatibility.";
$aiSummary     = $analysis['ai_summary'] ?? "Your CV has a strong foundation for {$targetRole} internships. Your experience and core design skills align well with the role you're targeting.";
$metrics       = $analysis['metrics'] ?? [
    'role_relevance'    => 91,
    'experience'        => 89,
    'content_quality'   => 88,
    'skills_mapping'    => 84,
    'ats_compatibility' => 76,
];
$detectedSkills = $analysis['detected_skills'] ?? ['Figma', 'UX Research', 'Prototyping', 'Wireframing'];
$missingSkills  = $analysis['missing_skills'] ?? ['Design Systems', 'Interaction Design', 'A/B Testing'];
$whatsWorking   = $analysis['whats_working'] ?? [
    'Strong ' . $targetRole . ' Skills',
    'Relevant Project Experience',
    'Clear Experience Structure',
    'Strong Role Alignment',
];
$suggestions    = $analysis['suggestions'] ?? [];
?>

<div class="db-shell">
    <div class="db-sidebar-overlay" id="db-sidebar-overlay"></div>

    <?php 
    $activeNav = 'ai-assistant';
    include dirname(__DIR__, 2) . '/shared/components/candidate-sidebar.php'; 
    ?>

    <div class="db-main">
        <?php include dirname(__DIR__, 2) . '/shared/components/candidate-topnav.php'; ?>

        <div class="db-content" id="db-content" x-data="{ 
            animatedScore: 0,
            barsAnimated: false,
            init() {
                let current = 0;
                let target = <?= $targetScore ?>;
                let interval = setInterval(() => {
                    if (current >= target) {
                        current = target;
                        clearInterval(interval);
                    } else {
                        current++;
                    }
                    this.animatedScore = current;
                }, 18);
                setTimeout(() => {
                    this.barsAnimated = true;
                }, 200);
            }
        }">
            <style>
            /* ── Page Header ── */
            .res-header {
                margin-bottom: 24px;
            }
            .res-title {
                font-size: 28px;
                font-weight: 800;
                color: #000623;
                margin: 0 0 6px;
                letter-spacing: -0.02em;
            }
            .res-sub {
                font-size: 14.5px;
                color: #64748B;
                margin: 0;
            }

            /* ── Top Layout Grid ── */
            .res-top-grid {
                display: grid;
                grid-template-columns: minmax(0, 1fr) !important;
                gap: 24px;
                margin-bottom: 32px;
                width: 100%;
                box-sizing: border-box;
            }
            @media (min-width: 1024px) {
                .res-top-grid {
                    grid-template-columns: minmax(0, 1.4fr) minmax(0, 1fr) !important;
                }
            }

            /* ── Card Containers ── */
            .res-card {
                background: #FFFFFF;
                border: 1px solid #E2E8F0;
                border-radius: 20px;
                padding: 24px;
                margin-bottom: 20px;
                box-shadow: 0 2px 8px rgba(0, 6, 35, 0.03);
                box-sizing: border-box;
            }

            /* Score Card */
            .res-score-card {
                display: flex;
                align-items: center;
                gap: 22px;
            }
            .res-circle-wrap {
                width: 100px;
                height: 100px;
                position: relative;
                flex-shrink: 0;
                display: flex;
                align-items: center;
                justify-content: center;
            }
            .res-circle-svg {
                width: 100px;
                height: 100px;
                transform: rotate(-90deg);
            }
            .res-circle-bg {
                fill: none;
                stroke: #EEF2FF;
                stroke-width: 9;
            }
            .res-circle-bar {
                fill: none;
                stroke: #4F48E6;
                stroke-width: 9;
                stroke-linecap: round;
                stroke-dasharray: 264;
                stroke-dashoffset: 264;
                transition: stroke-dashoffset 1.4s cubic-bezier(0.4, 0, 0.2, 1);
            }
            .res-circle-text {
                position: absolute;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
            }
            .res-score-num {
                font-size: 26px;
                font-weight: 800;
                color: #000623;
                line-height: 1;
            }
            .res-score-max {
                font-size: 11px;
                font-weight: 700;
                color: #94A3B8;
            }
            .res-score-badge {
                display: inline-flex;
                align-items: center;
                gap: 4px;
                background: #EFF6FF;
                color: #2563EB;
                border: 1px solid #DBEAFE;
                border-radius: 9999px;
                padding: 3px 10px;
                font-size: 11px;
                font-weight: 800;
                text-transform: uppercase;
                letter-spacing: 0.04em;
                margin-bottom: 6px;
            }
            .res-score-title {
                font-size: 18px;
                font-weight: 800;
                color: #000623;
                margin: 0 0 4px;
            }
            .res-score-desc {
                font-size: 13px;
                color: #64748B;
                line-height: 1.5;
                margin: 0;
            }

            /* AI Summary Card */
            .res-summary-head {
                display: flex;
                align-items: center;
                gap: 8px;
                font-size: 16px;
                font-weight: 800;
                color: #000623;
                margin-bottom: 12px;
            }
            .res-summary-text {
                font-size: 13.5px;
                line-height: 1.6;
                color: #475569;
                margin: 0;
            }

            /* What's Working Grid */
            .res-working-title {
                font-size: 15.5px;
                font-weight: 800;
                color: #000623;
                margin: 0 0 16px;
            }
            .res-working-grid {
                display: grid;
                grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
                gap: 14px;
            }
            .res-working-item {
                display: flex;
                align-items: center;
                gap: 10px;
                font-size: 13px;
                font-weight: 600;
                color: #1E293B;
            }
            .res-check-icon {
                width: 20px;
                height: 20px;
                border-radius: 50%;
                background: #EEF2FF;
                color: #4F48E6;
                display: flex;
                align-items: center;
                justify-content: center;
                flex-shrink: 0;
            }

            /* Performance Bars */
            .res-metric-item {
                margin-bottom: 16px;
            }
            .res-metric-item:last-child {
                margin-bottom: 0;
            }
            .res-metric-head {
                display: flex;
                align-items: center;
                justify-content: space-between;
                font-size: 13px;
                font-weight: 600;
                color: #1E293B;
                margin-bottom: 6px;
            }
            .res-metric-val {
                font-size: 13px;
                font-weight: 800;
                color: #4F48E6;
            }
            .res-metric-track {
                width: 100%;
                height: 7px;
                background: #F1F5F9;
                border-radius: 9999px;
                overflow: hidden;
            }
            .res-metric-fill {
                height: 100%;
                background: #4F48E6;
                border-radius: 9999px;
                width: 0%;
                transition: width 1s cubic-bezier(0.4, 0, 0.2, 1);
            }

            /* Skills Pills */
            .res-skills-sub {
                font-size: 11px;
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: 0.05em;
                color: #94A3B8;
                margin: 0 0 8px;
            }
            .res-pills-wrap {
                display: flex;
                flex-wrap: wrap;
                gap: 8px;
                margin-bottom: 18px;
            }
            .res-skill-pill {
                background: #F8FAFC;
                color: #334155;
                border: 1px solid #E2E8F0;
                border-radius: 9999px;
                padding: 4px 12px;
                font-size: 12px;
                font-weight: 600;
            }
            .res-skill-pill.missing {
                background: #FEF2F2;
                color: #DC2626;
                border-color: #FECACA;
            }

            /* ── Areas to Improve ── */
            .res-section-head {
                display: flex;
                align-items: center;
                justify-content: space-between;
                margin-bottom: 18px;
            }
            .res-section-title {
                font-size: 20px;
                font-weight: 800;
                color: #000623;
                margin: 0;
            }
            .res-count-badge {
                background: #EEF2FF;
                color: #4338CA;
                border: 1px solid #C7D2FE;
                border-radius: 9999px;
                padding: 4px 12px;
                font-size: 12px;
                font-weight: 800;
            }

            .res-improve-grid {
                display: grid;
                grid-template-columns: minmax(0, 1fr) !important;
                gap: 18px;
                margin-bottom: 36px;
                width: 100%;
                box-sizing: border-box;
            }
            @media (min-width: 640px) {
                .res-improve-grid {
                    grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
                }
            }

            .res-improve-card {
                background: #FFFFFF;
                border: 1px solid #E2E8F0;
                border-radius: 18px;
                padding: 22px;
                box-shadow: 0 2px 8px rgba(0, 6, 35, 0.03);
                box-sizing: border-box;
            }
            .res-prio-badge {
                display: inline-flex;
                align-items: center;
                gap: 4px;
                border-radius: 9999px;
                padding: 3px 9px;
                font-size: 10.5px;
                font-weight: 800;
                letter-spacing: 0.04em;
                margin-bottom: 10px;
            }
            .res-prio-high {
                background: #FEF2F2;
                color: #DC2626;
                border: 1px solid #FECACA;
            }
            .res-prio-med {
                background: #EFF6FF;
                color: #2563EB;
                border: 1px solid #DBEAFE;
            }
            .res-improve-heading {
                font-size: 15px;
                font-weight: 800;
                color: #000623;
                margin: 0 0 10px;
                line-height: 1.35;
            }
            .res-improve-row {
                font-size: 12.5px;
                line-height: 1.5;
                color: #475569;
                margin-bottom: 6px;
            }
            .res-improve-label {
                font-weight: 700;
                color: #000623;
            }

            /* ── Bottom Premium Feature Banner ── */
            .res-prem-banner {
                background: linear-gradient(135deg, #EEF2FF 0%, #E0E7FF 60%, #EDE9FE 100%);
                border: 1.5px solid #C7D2FE;
                border-radius: 24px;
                padding: 36px 32px;
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 28px;
                box-shadow: 0 10px 30px -4px rgba(79, 72, 230, 0.12);
                position: relative;
                overflow: hidden;
                box-sizing: border-box;
            }
            .res-prem-left {
                max-width: 540px;
                z-index: 2;
            }
            .res-prem-badge {
                display: inline-flex;
                align-items: center;
                gap: 6px;
                background: #FFFFFF;
                color: #4F48E6;
                border: 1px solid #C7D2FE;
                border-radius: 9999px;
                padding: 4px 12px;
                font-size: 11px;
                font-weight: 800;
                letter-spacing: 0.05em;
                margin-bottom: 12px;
            }
            .res-prem-title {
                font-size: 26px;
                font-weight: 800;
                color: #1E1B4B;
                margin: 0 0 14px;
                line-height: 1.25;
                letter-spacing: -0.01em;
            }
            .res-prem-checks {
                display: flex;
                flex-direction: column;
                gap: 8px;
                margin-bottom: 24px;
            }
            .res-prem-check-item {
                display: flex;
                align-items: center;
                gap: 8px;
                font-size: 13.5px;
                font-weight: 600;
                color: #312E81;
            }
            .res-prem-actions {
                display: flex;
                align-items: center;
                gap: 12px;
                flex-wrap: wrap;
            }
            .res-prem-btn {
                background: #4F48E6;
                color: #FFFFFF;
                border: none;
                border-radius: 12px;
                padding: 12px 24px;
                font-size: 14px;
                font-weight: 700;
                text-decoration: none;
                display: inline-flex;
                align-items: center;
                gap: 8px;
                box-shadow: 0 4px 16px rgba(79, 72, 230, 0.3);
                transition: all 0.15s ease;
            }
            .res-prem-btn:hover {
                background: #3730A3;
                transform: translateY(-1px);
            }
            .res-prem-btn-sec {
                background: #FFFFFF;
                color: #475569;
                border: 1px solid #CBD5E1;
                border-radius: 12px;
                padding: 12px 20px;
                font-size: 14px;
                font-weight: 700;
                text-decoration: none;
                cursor: pointer;
                transition: background 0.15s ease;
            }
            .res-prem-btn-sec:hover {
                background: #F8FAFC;
                color: #0F172A;
            }

            /* Right Illustration Container with Float Animation */
            .res-prem-right {
                flex-shrink: 0;
                display: flex;
                align-items: center;
                justify-content: center;
                position: relative;
                margin-left: -15px;
            }
            .res-svg-artwork {
                width: 275px;
                height: 275px;
                animation: floatArtwork 4s ease-in-out infinite;
                filter: drop-shadow(0 18px 32px rgba(53, 37, 205, 0.25));
                max-width: 100%;
                object-fit: contain;
            }
            @keyframes floatArtwork {
                0%, 100% {
                    transform: translateY(0px) rotate(0deg);
                }
                50% {
                    transform: translateY(-8px) rotate(1deg);
                }
            }

            @media (max-width: 768px) {
                .res-score-card {
                    flex-direction: column;
                    text-align: center;
                }
                .res-prem-banner {
                    flex-direction: column;
                    text-align: center;
                }
                .res-prem-actions {
                    justify-content: center;
                }
                .res-prem-check-item {
                    justify-content: center;
                }
            }
            </style>

            <!-- ── Page Title & Subtitle ── -->
            <div class="res-header">
                <h1 class="res-title">AI CV Analysis</h1>
                <p class="res-sub">Understand how your CV performs and discover opportunities to make it stronger for your target role</p>
            </div>

            <!-- ── Top 2-Column Grid ── -->
            <div class="res-top-grid">
                <!-- Left Column -->
                <div>
                    <!-- Card 1: Overall Score Gauge -->
                    <div class="res-card res-score-card">
                        <div class="res-circle-wrap">
                            <svg class="res-circle-svg" viewBox="0 0 100 100">
                                <circle class="res-circle-bg" cx="50" cy="50" r="42" />
                                <circle class="res-circle-bar" cx="50" cy="50" r="42" :style="barsAnimated ? 'stroke-dashoffset: calc(264 - (264 * <?= $targetScore ?>) / 100);' : 'stroke-dashoffset: 264;'" />
                            </svg>
                            <div class="res-circle-text">
                                <span class="res-score-num" x-text="animatedScore"><?= $targetScore ?></span>
                                <span class="res-score-max">/100</span>
                            </div>
                        </div>

                        <div>
                            <span class="res-score-badge">
                                <svg style="width: 11px; height: 11px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                                </svg>
                                <?= htmlspecialchars($scoreRating) ?>
                            </span>
                            <h2 class="res-score-title"><?= htmlspecialchars($scoreHeadline) ?></h2>
                            <p class="res-score-desc"><?= htmlspecialchars($scoreSubtext) ?></p>
                        </div>
                    </div>

                    <!-- Card 2: AI Summary -->
                    <div class="res-card">
                        <div class="res-summary-head">
                            <span style="width: 24px; height: 24px; border-radius: 50%; background: #000623; border: 1.5px solid #4F48E6; color: #FFFFFF; display: inline-flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                <svg style="width: 12px; height: 13px; flex-shrink: 0;" viewBox="0 0 74 83" fill="currentColor">
                                    <path d="M73.74 32.4627V59.9427C67.93 59.7673 62.2767 61.8422 57.9596 65.7344C53.6426 69.6267 50.9952 75.0356 50.57 80.8327C50.52 81.3027 50.48 81.8327 50.46 82.2827H23.57C23.57 82.0227 23.48 81.7627 23.43 81.5127C20.73 67.1427 16.41 63.2427 0 59.6527V32.6527C13.34 31.5727 27.12 37.6527 36.92 47.9327C46 38.3527 58.99 31.8927 73.74 32.4627Z"/>
                                    <path d="M37.0001 0.0031237C40.4833 0.0367014 43.8785 1.10075 46.7578 3.06112C49.637 5.02149 51.8715 7.79043 53.1794 11.0188C54.4873 14.2473 54.8101 17.7906 54.1071 21.2023C53.4042 24.6139 51.7069 27.741 49.2293 30.1894C46.7516 32.6378 43.6046 34.2979 40.1849 34.9604C36.7652 35.6228 33.2259 35.258 30.0132 33.9119C26.8005 32.5658 24.0583 30.2987 22.1322 27.3963C20.2061 24.494 19.1824 21.0864 19.1901 17.6031C19.1737 15.2695 19.6247 12.9562 20.5166 10.7997C21.4084 8.64315 22.7231 6.68703 24.383 5.04669C26.0429 3.40635 28.0145 2.11499 30.1814 1.24877C32.3484 0.382549 34.6669 -0.040991 37.0001 0.0031237Z"/>
                                </svg>
                            </span>
                            <span>AI Summary</span>
                        </div>
                        <p class="res-summary-text">
                            <?= nl2br(htmlspecialchars($aiSummary)) ?>
                        </p>
                    </div>

                    <!-- Card 3: What's Working -->
                    <div class="res-card">
                        <h3 class="res-working-title">What's Working</h3>
                        <div class="res-working-grid">
                            <?php foreach ($whatsWorking as $ww): ?>
                            <div class="res-working-item">
                                <div class="res-check-icon">
                                    <svg style="width: 12px; height: 12px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                                <span><?= htmlspecialchars($ww) ?></span>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div>
                    <!-- Card 4: CV Performance -->
                    <div class="res-card">
                        <h3 class="res-working-title">CV Performance</h3>

                        <!-- 1. Role Relevance -->
                        <div class="res-metric-item">
                            <div class="res-metric-head">
                                <span>Role Relevance</span>
                                <span class="res-metric-val"><?= (int)($metrics['role_relevance'] ?? 90) ?>%</span>
                            </div>
                            <div class="res-metric-track">
                                <div class="res-metric-fill" :style="barsAnimated ? 'width: <?= (int)($metrics['role_relevance'] ?? 90) ?>%;' : 'width: 0%;'"></div>
                            </div>
                        </div>

                        <!-- 2. Experience -->
                        <div class="res-metric-item">
                            <div class="res-metric-head">
                                <span>Experience</span>
                                <span class="res-metric-val"><?= (int)($metrics['experience'] ?? 85) ?>%</span>
                            </div>
                            <div class="res-metric-track">
                                <div class="res-metric-fill" :style="barsAnimated ? 'width: <?= (int)($metrics['experience'] ?? 85) ?>%;' : 'width: 0%;'"></div>
                            </div>
                        </div>

                        <!-- 3. Content Quality -->
                        <div class="res-metric-item">
                            <div class="res-metric-head">
                                <span>Content Quality</span>
                                <span class="res-metric-val"><?= (int)($metrics['content_quality'] ?? 85) ?>%</span>
                            </div>
                            <div class="res-metric-track">
                                <div class="res-metric-fill" :style="barsAnimated ? 'width: <?= (int)($metrics['content_quality'] ?? 85) ?>%;' : 'width: 0%;'"></div>
                            </div>
                        </div>

                        <!-- 4. Skills Mapping -->
                        <div class="res-metric-item">
                            <div class="res-metric-head">
                                <span>Skills Mapping</span>
                                <span class="res-metric-val"><?= (int)($metrics['skills_mapping'] ?? 80) ?>%</span>
                            </div>
                            <div class="res-metric-track">
                                <div class="res-metric-fill" :style="barsAnimated ? 'width: <?= (int)($metrics['skills_mapping'] ?? 80) ?>%;' : 'width: 0%;'"></div>
                            </div>
                        </div>

                        <!-- 5. ATS Compatibility -->
                        <div class="res-metric-item">
                            <div class="res-metric-head">
                                <span>ATS Compatibility</span>
                                <span class="res-metric-val"><?= (int)($metrics['ats_compatibility'] ?? 75) ?>%</span>
                            </div>
                            <div class="res-metric-track">
                                <div class="res-metric-fill" :style="barsAnimated ? 'width: <?= (int)($metrics['ats_compatibility'] ?? 75) ?>%;' : 'width: 0%;'"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Card 5: Skills & Keywords -->
                    <div class="res-card">
                        <h3 class="res-working-title">Skills & Keywords</h3>

                        <div class="res-skills-sub">DETECTED IN YOUR CV</div>
                        <div class="res-pills-wrap">
                            <?php if (empty($detectedSkills)): ?>
                                <span style="font-size: 12px; color: #94A3B8;">No role-specific skills detected</span>
                            <?php else: ?>
                                <?php foreach ($detectedSkills as $ds): ?>
                                    <span class="res-skill-pill"><?= htmlspecialchars($ds) ?></span>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>

                        <div class="res-skills-sub">RELEVANT BUT MISSING</div>
                        <div class="res-pills-wrap">
                            <?php if (empty($missingSkills)): ?>
                                <span style="font-size: 12px; color: #16A34A; font-weight: 600;">All key skills covered!</span>
                            <?php else: ?>
                                <?php foreach ($missingSkills as $ms): ?>
                                    <span class="res-skill-pill missing"><?= htmlspecialchars($ms) ?></span>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>

                        <div style="display: flex; align-items: center; gap: 6px; font-size: 11.5px; color: #64748B;">
                            <svg style="width: 14px; height: 14px; flex-shrink: 0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>Only include skills you genuinely have experience with.</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ── Areas to Improve Section ── -->
            <div>
                <div class="res-section-head">
                    <h2 class="res-section-title">Areas to Improve</h2>
                    <span class="res-count-badge"><?= count($suggestions) ?> Suggestions</span>
                </div>

                <div class="res-improve-grid">
                    <?php if (empty($suggestions)): ?>
                        <div class="res-improve-card">
                            <span class="res-prio-badge res-prio-low">OPTIMIZED</span>
                            <h3 class="res-improve-heading">No critical issues found</h3>
                            <div class="res-improve-row">Your CV aligns with high ATS standards for <?= htmlspecialchars($targetRole) ?>.</div>
                        </div>
                    <?php else: ?>
                        <?php foreach ($suggestions as $sug): ?>
                            <div class="res-improve-card">
                                <span class="res-prio-badge <?= htmlspecialchars($sug['prio_class'] ?? 'res-prio-med') ?>"><?= htmlspecialchars($sug['priority'] ?? 'MEDIUM PRIORITY') ?></span>
                                <h3 class="res-improve-heading"><?= htmlspecialchars($sug['heading'] ?? '') ?></h3>
                                <?php if (!empty($sug['problem'])): ?>
                                    <div class="res-improve-row"><span class="res-improve-label">Problem:</span> <?= htmlspecialchars($sug['problem']) ?></div>
                                <?php endif; ?>
                                <?php if (!empty($sug['recommendation'])): ?>
                                    <div class="res-improve-row"><span class="res-improve-label">Recommendation:</span> <?= htmlspecialchars($sug['recommendation']) ?></div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <!-- ── Bottom Premium Feature Banner (Screenshot 2 + Cloudinary SVG) ── -->
            <div class="res-prem-banner">
                <div class="res-prem-left">
                    <div class="res-prem-badge">
                        <svg style="width: 12px; height: 12px;" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                        </svg>
                        <span>PREMIUM FEATURE</span>
                    </div>

                    <h2 class="res-prem-title">Want AI to improve your CV for you?</h2>

                    <div class="res-prem-checks">
                        <div class="res-prem-check-item">
                            <svg style="width: 16px; height: 16px; color: #4F48E6; flex-shrink: 0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>AI-assisted CV rewriting tailored to roles</span>
                        </div>
                        <div class="res-prem-check-item">
                            <svg style="width: 16px; height: 16px; color: #4F48E6; flex-shrink: 0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>Role-specific ATS optimization</span>
                        </div>
                        <div class="res-prem-check-item">
                            <svg style="width: 16px; height: 16px; color: #4F48E6; flex-shrink: 0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>1-click apply with optimized CV</span>
                        </div>
                    </div>

                    <div class="res-prem-actions">
                        <a href="<?= url('/pricing?plan=premium') ?>" class="res-prem-btn">
                            <span>Upgrade to Premium</span>
                            <svg style="width: 15px; height: 15px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </a>
                        <a href="<?= url('/candidate/ai-assistant') ?>" class="res-prem-btn-sec">Maybe Later</a>
                    </div>
                </div>

                <!-- Right Artwork using User Cloudinary Asset -->
                <div class="res-prem-right">
                    <img src="https://res.cloudinary.com/weburea/image/upload/v1793432011/Overlay_Border_OverlayBlur_iqanl4.svg" alt="AI CV Optimizer Overlay" class="res-svg-artwork" loading="lazy">
                </div>
            </div>

        </div>
    </div>
</div>
