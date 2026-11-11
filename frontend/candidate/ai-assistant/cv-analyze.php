<?php
declare(strict_types=1);
/**
 * View: Candidate AI CV Analyze Setup
 * Route: /candidate/ai-assistant/cv-analyze
 * Layout: dashboard
 *
 * @var array  $candidate      Candidate profile data
 * @var string $candPlan       Active plan ('pro' or 'premium')
 * @var array  $currentCv      Current uploaded CV info
 * @var array  $targetRoles    Available target roles
 * @var array  $suggestedPills Suggested role pills
 */

$candName      = $candidate['name'] ?? 'Sarah Peters';
$candFirstName = $candidate['first_name'] ?? 'Sarah';
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
            selectedCv: 'Sarah_Peters_CV.pdf',
            targetRole: 'Product Designer',
            opportunity: 'Google, I\'m applying as a product designer Product Design Intern',
            isAnalyzing: false,
            roleDropdownOpen: false
        }">
            <style>
            /* ── Page Header ── */
            .cv-an-header {
                margin-bottom: 24px;
            }
            .cv-an-title {
                font-size: 28px;
                font-weight: 800;
                color: #000623;
                margin: 0 0 6px;
                letter-spacing: -0.02em;
            }
            .cv-an-sub {
                font-size: 14.5px;
                color: #64748B;
                margin: 0;
            }

            /* ── Two-Column Layout ── */
            .cv-an-layout {
                display: grid;
                grid-template-columns: minmax(0, 1fr) !important;
                gap: 28px;
                width: 100%;
                box-sizing: border-box;
            }
            @media (min-width: 1024px) {
                .cv-an-layout {
                    grid-template-columns: minmax(0, 1.8fr) minmax(0, 1.2fr) !important;
                }
            }

            /* ── Form Cards (Left Column) ── */
            .cv-an-card {
                background: #FFFFFF;
                border: 1px solid #E2E8F0;
                border-radius: 20px;
                padding: 24px;
                margin-bottom: 20px;
                box-shadow: 0 2px 8px rgba(0, 6, 35, 0.03);
                box-sizing: border-box;
            }
            .cv-an-card-title {
                font-size: 16px;
                font-weight: 700;
                color: #000623;
                margin: 0 0 16px;
                display: flex;
                align-items: center;
                gap: 8px;
            }
            .cv-an-card-sub {
                font-size: 12.5px;
                color: #64748B;
                margin: -8px 0 16px;
                line-height: 1.45;
            }

            /* Existing CV Box */
            .cv-file-box {
                background: #F8FAFC;
                border: 1.5px solid #C7D2FE;
                border-radius: 14px;
                padding: 14px 18px;
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 12px;
                transition: border-color 0.15s ease;
            }
            .cv-file-left {
                display: flex;
                align-items: center;
                gap: 12px;
                min-width: 0;
            }
            .cv-pdf-badge {
                width: 36px;
                height: 36px;
                border-radius: 8px;
                background: #FEE2E2;
                color: #DC2626;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 10px;
                font-weight: 800;
                letter-spacing: 0.05em;
                flex-shrink: 0;
            }
            .cv-file-name {
                font-size: 14px;
                font-weight: 700;
                color: #000623;
                margin: 0 0 2px;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }
            .cv-file-meta {
                font-size: 12px;
                color: #64748B;
                margin: 0;
            }
            .cv-use-btn {
                background: #EEF2FF;
                color: #4338CA;
                border: 1px solid #C7D2FE;
                border-radius: 9999px;
                padding: 6px 14px;
                font-size: 12px;
                font-weight: 700;
                cursor: pointer;
                white-space: nowrap !important;
                flex-shrink: 0 !important;
                transition: all 0.15s ease;
            }
            .cv-use-btn.is-active {
                background: #4F48E6;
                color: #FFFFFF;
                border-color: #4F48E6;
            }

            /* Divider */
            .cv-or-divider {
                display: flex;
                align-items: center;
                gap: 12px;
                margin: 18px 0;
                color: #94A3B8;
                font-size: 11px;
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: 0.05em;
            }
            .cv-or-divider::before,
            .cv-or-divider::after {
                content: '';
                flex: 1;
                height: 1px;
                background: #E2E8F0;
            }

            /* Dropzone */
            .cv-dropzone {
                border: 1.5px dashed #CBD5E1;
                border-radius: 14px;
                padding: 24px 18px;
                text-align: center;
                background: #FFFFFF;
                cursor: pointer;
                transition: border-color 0.15s ease, background-color 0.15s ease;
            }
            .cv-dropzone:hover {
                border-color: #4F48E6;
                background: #F8FAFC;
            }
            .cv-drop-icon {
                width: 44px;
                height: 44px;
                border-radius: 50%;
                background: #EEF2FF;
                color: #4F48E6;
                display: flex;
                align-items: center;
                justify-content: center;
                margin: 0 auto 10px;
            }
            .cv-drop-text {
                font-size: 13.5px;
                color: #475569;
                margin: 0 0 4px;
            }
            .cv-drop-hint {
                font-size: 11.5px;
                color: #94A3B8;
                margin: 0;
            }

            /* Custom Alpine Dropdown */
            .cv-dropdown-btn {
                width: 100%;
                height: 46px;
                border: 1px solid #E2E8F0;
                border-radius: 12px;
                background: #FFFFFF;
                padding: 0 16px;
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 12px;
                font-size: 14px;
                font-weight: 600;
                color: #000623;
                cursor: pointer;
                box-sizing: border-box;
                white-space: nowrap !important;
                transition: border-color 0.15s ease, box-shadow 0.15s ease;
            }
            .cv-dropdown-btn span {
                white-space: nowrap !important;
                overflow: hidden;
                text-overflow: ellipsis;
                flex: 1;
                text-align: left;
            }
            .cv-dropdown-btn svg {
                width: 16px !important;
                height: 16px !important;
                max-width: 16px !important;
                max-height: 16px !important;
                min-width: 16px !important;
                min-height: 16px !important;
                flex-shrink: 0 !important;
                display: block !important;
            }
            .cv-dropdown-btn:focus,
            .cv-dropdown-btn.is-open {
                border-color: #4F48E6;
                box-shadow: 0 0 0 3px rgba(79, 72, 230, 0.1);
            }
            .cv-dropdown-menu {
                position: absolute;
                top: calc(100% + 6px);
                left: 0;
                right: 0;
                background: #FFFFFF;
                border: 1px solid #E2E8F0;
                border-radius: 12px;
                box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
                z-index: 50;
                max-height: 220px;
                overflow-y: auto;
                padding: 6px;
            }
            .cv-dropdown-item {
                padding: 9px 12px;
                border-radius: 8px;
                font-size: 13.5px;
                color: #334155;
                cursor: pointer;
                display: flex;
                align-items: center;
                justify-content: space-between;
                transition: background 0.1s ease;
            }
            .cv-dropdown-item:hover {
                background: #F1F5F9;
                color: #000623;
            }
            .cv-dropdown-item.is-selected {
                background: #EEF2FF;
                color: #4F48E6;
                font-weight: 700;
            }

            /* Suggested Pills */
            .cv-pills-row {
                display: flex;
                align-items: center;
                flex-wrap: wrap;
                gap: 8px;
                margin-top: 12px;
            }
            .cv-pill-tag {
                background: #F1F5F9;
                color: #475569;
                border: 1px solid #E2E8F0;
                border-radius: 9999px;
                padding: 4px 12px;
                font-size: 12px;
                font-weight: 600;
                cursor: pointer;
                transition: all 0.15s ease;
                white-space: nowrap !important;
                flex-shrink: 0 !important;
            }
            .cv-pill-tag:hover {
                background: #EEF2FF;
                color: #4F48E6;
                border-color: #C7D2FE;
            }
            .cv-pill-tag.is-active {
                background: #4F48E6;
                color: #FFFFFF;
                border-color: #4F48E6;
            }

            /* Specific Opportunity Input */
            .cv-input-wrap {
                position: relative;
                display: flex;
                align-items: center;
            }
            .cv-text-input {
                width: 100%;
                height: 48px;
                background: #FFFFFF;
                border: 1px solid #E2E8F0;
                border-radius: 12px;
                padding: 0 42px 0 16px;
                font-size: 13.5px;
                color: #000623;
                font-family: inherit;
                box-sizing: border-box;
                transition: border-color 0.15s ease, box-shadow 0.15s ease;
            }
            .cv-text-input:focus {
                border-color: #4F48E6;
                outline: none;
                box-shadow: 0 0 0 3px rgba(79, 72, 230, 0.1);
            }
            .cv-input-clear {
                position: absolute;
                right: 12px;
                background: none;
                border: none;
                color: #94A3B8;
                cursor: pointer;
                padding: 4px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
            }
            .cv-input-clear:hover {
                color: #0F172A;
            }

            /* Submit Button */
            .cv-submit-btn {
                width: 100%;
                height: 52px;
                background: #4F48E6;
                color: #FFFFFF;
                border: none;
                border-radius: 14px;
                font-size: 15px;
                font-weight: 700;
                display: flex !important;
                flex-direction: row !important;
                align-items: center !important;
                justify-content: center !important;
                gap: 8px;
                cursor: pointer;
                box-shadow: 0 8px 20px -4px rgba(79, 72, 230, 0.35);
                transition: background 0.15s ease, transform 0.15s ease;
                white-space: nowrap !important;
            }
            .cv-submit-btn:hover {
                background: #3730A3;
                transform: translateY(-1px);
            }
            .cv-btn-inner {
                display: inline-flex;
                flex-direction: row;
                align-items: center;
                justify-content: center;
                gap: 8px;
                white-space: nowrap !important;
                flex-shrink: 0 !important;
            }
            .cv-btn-inner svg {
                flex-shrink: 0 !important;
                vertical-align: middle !important;
                display: inline-block !important;
            }
            .cv-btn-inner[style*="display: none"],
            [x-cloak] {
                display: none !important;
            }
            @keyframes cv-spin {
                from { transform: rotate(0deg); }
                to { transform: rotate(360deg); }
            }
            .cv-spin-icon {
                animation: cv-spin 1s linear infinite;
            }
            .cv-submit-hint {
                text-align: center;
                font-size: 12px;
                color: #64748B;
                margin-top: 8px;
            }

            /* ── Info Cards (Right Column) ── */
            .cv-guide-card {
                background: #FFFFFF;
                border: 1px solid #E2E8F0;
                border-radius: 20px;
                padding: 24px;
                margin-bottom: 20px;
                box-shadow: 0 2px 8px rgba(0, 6, 35, 0.03);
                box-sizing: border-box;
            }
            .cv-guide-hero {
                display: flex;
                align-items: center;
                gap: 12px;
                margin-bottom: 12px;
            }
            .cv-guide-badge {
                width: 40px;
                height: 40px;
                border-radius: 10px;
                background: #EEF2FF;
                color: #4F48E6;
                display: flex;
                align-items: center;
                justify-content: center;
                flex-shrink: 0;
            }
            .cv-guide-title {
                font-size: 16px;
                font-weight: 800;
                color: #000623;
                margin: 0;
            }
            .cv-guide-desc {
                font-size: 13px;
                color: #64748B;
                line-height: 1.5;
                margin: 0 0 18px;
            }
            .cv-guide-step {
                background: #F8FAFC;
                border: 1px solid #F1F5F9;
                border-radius: 12px;
                padding: 12px 14px;
                font-size: 13px;
                font-weight: 600;
                color: #1E293B;
                margin-bottom: 10px;
                display: flex;
                align-items: center;
                gap: 10px;
            }
            .cv-guide-step-dot {
                width: 6px;
                height: 6px;
                border-radius: 50%;
                background: #4F48E6;
                flex-shrink: 0;
            }

            /* What AI Will Check Grid */
            .cv-check-grid {
                display: grid;
                grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
                gap: 12px;
            }
            .cv-check-tile {
                background: #FFFFFF;
                border: 1px solid #E2E8F0;
                border-radius: 12px;
                padding: 14px 12px;
                display: flex;
                align-items: center;
                gap: 10px;
                box-sizing: border-box;
            }
            .cv-check-icon {
                width: 32px;
                height: 32px;
                border-radius: 8px;
                background: #EEF2FF;
                color: #4F48E6;
                display: flex;
                align-items: center;
                justify-content: center;
                flex-shrink: 0;
            }
            .cv-check-label {
                font-size: 13px;
                font-weight: 700;
                color: #000623;
            }
            </style>

            <!-- ── Page Title & Subtitle ── -->
            <div class="cv-an-header">
                <h1 class="cv-an-title">Analyze Your CV</h1>
                <p class="cv-an-sub">Let AI analyze your CV and help you make it stronger for the roles you're targeting.</p>
            </div>

            <!-- Form submission to process analysis -->
            <form action="<?= url('/candidate/ai-assistant/cv-analyze') ?>" method="POST" @submit="isAnalyzing = true">
                <div class="cv-an-layout">
                    <!-- Left Column: Settings and Inputs -->
                    <div>
                        <!-- Card 1: Your Current CV -->
                        <div class="cv-an-card">
                            <h2 class="cv-an-card-title">
                                <svg style="width: 18px; height: 18px; color: #4F48E6;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <span>Your Current CV</span>
                            </h2>

                            <!-- Pre-loaded File Item -->
                            <div class="cv-file-box">
                                <div class="cv-file-left">
                                    <div class="cv-pdf-badge">PDF</div>
                                    <div style="min-width: 0;">
                                        <div class="cv-file-name"><?= htmlspecialchars($currentCv['name']) ?></div>
                                        <div class="cv-file-meta">Uploaded <?= htmlspecialchars($currentCv['uploaded_at']) ?></div>
                                    </div>
                                </div>
                                <button type="button" class="cv-use-btn" :class="{ 'is-active': selectedCv === '<?= htmlspecialchars($currentCv['name']) ?>' }" @click="selectedCv = '<?= htmlspecialchars($currentCv['name']) ?>'">
                                    <span x-text="selectedCv === '<?= htmlspecialchars($currentCv['name']) ?>' ? 'Using This CV' : 'Use This CV'"></span>
                                </button>
                            </div>

                            <!-- OR Divider -->
                            <div class="cv-or-divider">OR</div>

                            <!-- Drag & Drop Zone -->
                            <div class="cv-dropzone" @click="$refs.cvFileInput.click()">
                                <div class="cv-drop-icon">
                                    <svg style="width: 20px; height: 20px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                                    </svg>
                                </div>
                                <p class="cv-drop-text">
                                    <span>Drag & drop your CV here or </span>
                                    <span style="color: #4F48E6; font-weight: 700;">Choose a file</span>
                                </p>
                                <p class="cv-drop-hint">PDF, DOC or DOCX · Maximum 10MB</p>
                                <input type="file" x-ref="cvFileInput" name="cv_file" style="display: none;" accept=".pdf,.doc,.docx" @change="if ($event.target.files.length) selectedCv = $event.target.files[0].name">
                            </div>
                        </div>

                        <!-- Card 2: What role are you targeting? -->
                        <div class="cv-an-card">
                            <h2 class="cv-an-card-title">What role are you targeting?</h2>

                            <!-- Custom Alpine Dropdown (No native select) -->
                            <div style="position: relative;" @click.outside="roleDropdownOpen = false">
                                <button type="button" class="cv-dropdown-btn" :class="{ 'is-open': roleDropdownOpen }" @click="roleDropdownOpen = !roleDropdownOpen">
                                    <span x-text="targetRole"></span>
                                    <svg style="width: 16px; height: 16px; transition: transform 0.2s;" :style="roleDropdownOpen ? 'transform: rotate(180deg);' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>
                                <input type="hidden" name="target_role" :value="targetRole">

                                <div class="cv-dropdown-menu" x-show="roleDropdownOpen" style="display: none;">
                                    <?php foreach ($targetRoles as $role): ?>
                                    <div class="cv-dropdown-item" :class="{ 'is-selected': targetRole === '<?= htmlspecialchars($role) ?>' }" @click="targetRole = '<?= htmlspecialchars($role) ?>'; roleDropdownOpen = false">
                                        <span><?= htmlspecialchars($role) ?></span>
                                        <svg x-show="targetRole === '<?= htmlspecialchars($role) ?>'" style="width: 14px; height: 14px; color: #4F48E6;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <!-- Suggested Pills -->
                            <div class="cv-pills-row">
                                <span style="font-size: 12px; font-weight: 600; color: #64748B;">Suggested:</span>
                                <?php foreach ($suggestedPills as $pill): ?>
                                <button type="button" class="cv-pill-tag" :class="{ 'is-active': targetRole === '<?= htmlspecialchars($pill) ?>' }" @click="targetRole = '<?= htmlspecialchars($pill) ?>'">
                                    <?= htmlspecialchars($pill) ?>
                                </button>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <!-- Card 3: Are you applying for a specific opportunity? -->
                        <div class="cv-an-card">
                            <h2 class="cv-an-card-title">Are you applying for a specific opportunity?</h2>
                            <p class="cv-an-card-sub">We'll tailor the analysis specifically to this job description. (Optional)</p>

                            <div class="cv-input-wrap">
                                <input type="text" name="opportunity" class="cv-text-input" x-model="opportunity" placeholder="e.g. Google, Product Design Intern">
                                <button type="button" class="cv-input-clear" x-show="opportunity.length > 0" @click="opportunity = ''" title="Clear">
                                    <svg style="width: 16px; height: 16px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- CTA Button -->
                        <div>
                            <button type="submit" class="cv-submit-btn" :disabled="isAnalyzing">
                                <template x-if="!isAnalyzing">
                                    <span class="cv-btn-inner">
                                        <!-- Jenny AI Logo -->
                                        <svg style="width: 17px; height: 18px; flex-shrink: 0; vertical-align: middle;" viewBox="0 0 74 83" fill="currentColor">
                                            <path d="M73.74 32.4627V59.9427C67.93 59.7673 62.2767 61.8422 57.9596 65.7344C53.6426 69.6267 50.9952 75.0356 50.57 80.8327C50.52 81.3027 50.48 81.8327 50.46 82.2827H23.57C23.57 82.0227 23.48 81.7627 23.43 81.5127C20.73 67.1427 16.41 63.2427 0 59.6527V32.6527C13.34 31.5727 27.12 37.6527 36.92 47.9327C46 38.3527 58.99 31.8927 73.74 32.4627Z"/>
                                            <path d="M37.0001 0.0031237C40.4833 0.0367014 43.8785 1.10075 46.7578 3.06112C49.637 5.02149 51.8715 7.79043 53.1794 11.0188C54.4873 14.2473 54.8101 17.7906 54.1071 21.2023C53.4042 24.6139 51.7069 27.741 49.2293 30.1894C46.7516 32.6378 43.6046 34.2979 40.1849 34.9604C36.7652 35.6228 33.2259 35.258 30.0132 33.9119C26.8005 32.5658 24.0583 30.2987 22.1322 27.3963C20.2061 24.494 19.1824 21.0864 19.1901 17.6031C19.1737 15.2695 19.6247 12.9562 20.5166 10.7997C21.4084 8.64315 22.7231 6.68703 24.383 5.04669C26.0429 3.40635 28.0145 2.11499 30.1814 1.24877C32.3484 0.382549 34.6669 -0.040991 37.0001 0.0031237Z"/>
                                        </svg>
                                        <span>Analyze My CV</span>
                                    </span>
                                </template>
                                <template x-if="isAnalyzing">
                                    <span class="cv-btn-inner">
                                        <svg class="animate-spin" style="width: 18px; height: 18px; flex-shrink: 0; vertical-align: middle;" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        <span>Analyzing CV against role...</span>
                                    </span>
                                </template>
                            </button>
                            <p class="cv-submit-hint">Usually takes less than a minute</p>
                        </div>
                    </div>

                    <!-- Right Column: How it Works & Criteria Cards -->
                    <div>
                        <!-- Card 1: How AI CV Improvement Works -->
                        <div class="cv-guide-card">
                            <div class="cv-guide-hero">
                                <div class="cv-guide-badge" style="background: #000623; border: 2px solid #4F48E6; color: #FFFFFF;">
                                    <!-- Jenny AI Logo -->
                                    <svg style="width: 18px; height: 19px; flex-shrink: 0;" viewBox="0 0 74 83" fill="currentColor">
                                        <path d="M73.74 32.4627V59.9427C67.93 59.7673 62.2767 61.8422 57.9596 65.7344C53.6426 69.6267 50.9952 75.0356 50.57 80.8327C50.52 81.3027 50.48 81.8327 50.46 82.2827H23.57C23.57 82.0227 23.48 81.7627 23.43 81.5127C20.73 67.1427 16.41 63.2427 0 59.6527V32.6527C13.34 31.5727 27.12 37.6527 36.92 47.9327C46 38.3527 58.99 31.8927 73.74 32.4627Z"/>
                                        <path d="M37.0001 0.0031237C40.4833 0.0367014 43.8785 1.10075 46.7578 3.06112C49.637 5.02149 51.8715 7.79043 53.1794 11.0188C54.4873 14.2473 54.8101 17.7906 54.1071 21.2023C53.4042 24.6139 51.7069 27.741 49.2293 30.1894C46.7516 32.6378 43.6046 34.2979 40.1849 34.9604C36.7652 35.6228 33.2259 35.258 30.0132 33.9119C26.8005 32.5658 24.0583 30.2987 22.1322 27.3963C20.2061 24.494 19.1824 21.0864 19.1901 17.6031C19.1737 15.2695 19.6247 12.9562 20.5166 10.7997C21.4084 8.64315 22.7231 6.68703 24.383 5.04669C26.0429 3.40635 28.0145 2.11499 30.1814 1.24877C32.3484 0.382549 34.6669 -0.040991 37.0001 0.0031237Z"/>
                                    </svg>
                                </div>
                                <h3 class="cv-guide-title">How AI CV Improvement Works</h3>
                            </div>
                            <p class="cv-guide-desc">Let our smart assistant analyze and optimize your CV for your target role in seconds.</p>

                            <div class="cv-guide-step">
                                <span class="cv-guide-step-dot"></span>
                                <span>Analyze your CV against your target role</span>
                            </div>
                            <div class="cv-guide-step">
                                <span class="cv-guide-step-dot"></span>
                                <span>Identify strengths, weaknesses and missing opportunities</span>
                            </div>
                            <div class="cv-guide-step">
                                <span class="cv-guide-step-dot"></span>
                                <span>Get actionable recommendations</span>
                            </div>
                        </div>

                        <!-- Card 2: What AI will check -->
                        <div class="cv-guide-card">
                            <h3 class="cv-guide-title" style="margin-bottom: 16px;">What AI will check</h3>

                            <div class="cv-check-grid">
                                <!-- Content -->
                                <div class="cv-check-tile">
                                    <div class="cv-check-icon">
                                        <svg style="width: 16px; height: 16px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16m-7 6h7"/>
                                        </svg>
                                    </div>
                                    <span class="cv-check-label">Content</span>
                                </div>

                                <!-- Skills -->
                                <div class="cv-check-tile">
                                    <div class="cv-check-icon">
                                        <svg style="width: 16px; height: 16px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                                        </svg>
                                    </div>
                                    <span class="cv-check-label">Skills</span>
                                </div>

                                <!-- ATS Score -->
                                <div class="cv-check-tile">
                                    <div class="cv-check-icon">
                                        <svg style="width: 16px; height: 16px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                                        </svg>
                                    </div>
                                    <span class="cv-check-label">ATS Score</span>
                                </div>

                                <!-- Relevance -->
                                <div class="cv-check-tile">
                                    <div class="cv-check-icon">
                                        <svg style="width: 16px; height: 16px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </div>
                                    <span class="cv-check-label">Relevance</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

        </div>
    </div>
</div>
