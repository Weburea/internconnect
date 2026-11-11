<?php
declare(strict_types=1);
/**
 * View: Candidate AI Matched Internships
 * Route: /candidate/ai-assistant/matched
 * Layout: dashboard (sidebar + topnav)
 *
 * @var array  $candidate          Candidate profile data
 * @var string $candPlan           Active plan ('pro' or 'premium')
 * @var array  $matchedInternships List of AI-matched internships
 * @var int    $totalCount         Total matches count
 */

$candName      = $candidate['name'] ?? 'Sarah Peters';
$candFirstName = $candidate['first_name'] ?? 'Sarah';
$candRole      = $candidate['role'] ?? 'Student Applicant';
$candPlan      = $candPlan ?? ($candidate['plan'] ?? 'pro');
$totalCount    = $totalCount ?? count($matchedInternships);
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

        <div class="db-content" id="db-content" x-data="{
            activeTab: 'best_match',
            selectedLocation: 'All',
            selectedCategory: 'All',
            selectedRole: 'All',
            selectedDuration: 'All',
            locationOpen: false,
            categoryOpen: false,
            roleOpen: false,
            durationOpen: false
        }">
            <style>
            /* ── Header ── */
            .ai-match-page-header {
                margin-bottom: 24px;
            }
            .ai-match-page-title {
                font-size: 28px;
                font-weight: 800;
                color: #000623;
                margin: 0 0 6px;
                letter-spacing: -0.02em;
            }
            .ai-match-page-sub {
                font-size: 14.5px;
                color: #64748B;
                margin: 0;
            }

            /* ── Filter Bar ── */
            .ai-filter-bar {
                background: #FFFFFF;
                border: 1px solid #E2E8F0;
                border-radius: 16px;
                padding: 8px 12px;
                margin-bottom: 24px;
                display: flex;
                align-items: center;
                justify-content: space-between;
                flex-wrap: wrap;
                gap: 12px;
                box-shadow: 0 1px 3px rgba(0, 6, 35, 0.02);
            }
            .ai-filter-tabs {
                display: flex;
                align-items: center;
                background: #F8FAFC;
                padding: 4px;
                border-radius: 12px;
                gap: 4px;
                flex-wrap: wrap;
            }
            .ai-filter-tab-btn {
                padding: 7px 14px;
                border-radius: 8px;
                font-size: 13px;
                font-weight: 700;
                color: #64748B;
                background: transparent;
                border: none;
                cursor: pointer;
                transition: all 0.15s ease;
                white-space: nowrap !important;
                flex-shrink: 0 !important;
            }
            .ai-filter-tab-btn:hover {
                color: #000623;
            }
            .ai-filter-tab-btn.is-active {
                background: #FFFFFF;
                color: #4F48E6;
                box-shadow: 0 1px 3px rgba(0, 6, 35, 0.08);
            }
            .ai-filter-dropdowns {
                display: flex;
                align-items: center;
                gap: 8px;
                flex-wrap: wrap;
            }
            .ai-dd-wrap {
                position: relative;
            }
            .ai-dd-btn {
                display: inline-flex !important;
                align-items: center !important;
                gap: 6px;
                padding: 7px 14px;
                background: #FFFFFF;
                border: 1px solid #CBD5E1;
                border-radius: 99px;
                font-size: 12.5px;
                font-weight: 600;
                color: #334155;
                cursor: pointer;
                transition: all 0.15s ease;
                white-space: nowrap !important;
                flex-shrink: 0 !important;
            }
            .ai-dd-btn:hover {
                background: #F8FAFC;
                border-color: #94A3B8;
            }
            .ai-dd-menu {
                position: absolute;
                top: calc(100% + 6px);
                right: 0;
                background: #FFFFFF;
                border: 1px solid #E2E8F0;
                border-radius: 12px;
                box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
                min-width: 160px;
                padding: 6px;
                z-index: 50;
            }
            .ai-dd-item {
                display: block;
                width: 100%;
                text-align: left;
                padding: 7px 12px;
                font-size: 12.5px;
                font-weight: 600;
                color: #334155;
                border-radius: 6px;
                background: transparent;
                border: none;
                cursor: pointer;
                transition: background 0.12s ease;
            }
            .ai-dd-item:hover {
                background: #F1F5F9;
                color: #4F48E6;
            }
            .ai-dd-item.is-selected {
                background: #EEF2FF;
                color: #4F48E6;
                font-weight: 700;
            }

            /* ── Internship Cards List ── */
            .ai-cards-stack {
                display: flex;
                flex-direction: column;
                gap: 20px;
                margin-bottom: 40px;
            }
            .ai-opp-card {
                background: #FFFFFF;
                border: 1px solid #E2E8F0;
                border-radius: 18px;
                padding: 24px;
                box-shadow: 0 2px 8px -2px rgba(0, 6, 35, 0.04);
                transition: transform 0.15s ease, box-shadow 0.15s ease, border-color 0.15s ease;
                box-sizing: border-box;
            }
            .ai-opp-card:hover {
                box-shadow: 0 8px 24px -4px rgba(0, 6, 35, 0.08);
                border-color: #CBD5E1;
            }
            .ai-opp-top {
                display: flex;
                align-items: flex-start;
                justify-content: space-between;
                gap: 16px;
                margin-bottom: 20px;
            }
            .ai-opp-company-meta {
                display: flex;
                align-items: center;
                gap: 16px;
                flex: 1;
                min-width: 0;
            }
            .ai-opp-logo {
                width: 48px;
                height: 48px;
                border-radius: 12px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-weight: 800;
                font-size: 15px;
                color: #FFFFFF;
                flex-shrink: 0;
                letter-spacing: -0.02em;
            }
            .ai-opp-titles {
                flex: 1;
                min-width: 0;
            }
            .ai-opp-role-title {
                font-size: 17px;
                font-weight: 800;
                color: #000623;
                margin: 0 0 4px;
                letter-spacing: -0.01em;
            }
            .ai-opp-subline {
                font-size: 13.5px;
                color: #64748B;
                margin: 0;
                display: flex;
                align-items: center;
                gap: 6px;
                flex-wrap: wrap;
                font-weight: 500;
            }
            .ai-opp-subline-dot {
                color: #CBD5E1;
            }

            /* Match Score Badge */
            .ai-opp-score-col {
                display: flex;
                flex-direction: column;
                align-items: flex-end;
                flex-shrink: 0;
            }
            .ai-score-pill {
                display: inline-flex !important;
                align-items: center !important;
                gap: 5px;
                padding: 4px 12px;
                border-radius: 99px;
                font-size: 12px;
                font-weight: 800;
                letter-spacing: 0.02em;
                white-space: nowrap !important;
                flex-shrink: 0 !important;
            }
            .ai-score-pill.tier-high {
                background: #4F48E6;
                color: #FFFFFF;
                box-shadow: 0 2px 6px rgba(79, 72, 230, 0.25);
            }
            .ai-score-pill.tier-mid {
                background: #EEF2FF;
                color: #4F48E6;
                border: 1px solid #C7D2FE;
            }
            .ai-score-pill.tier-fair {
                background: #F1F5F9;
                color: #475569;
                border: 1px solid #E2E8F0;
            }
            .ai-score-tier-label {
                font-size: 11px;
                font-weight: 700;
                color: #4F48E6;
                margin-top: 3px;
                text-align: right;
            }
            .ai-score-tier-label.tier-mid {
                color: #3730A3;
            }
            .ai-score-tier-label.tier-fair {
                color: #64748B;
            }

            /* Body Grid (Why match + Potential Gap) */
            .ai-opp-body-grid {
                display: grid;
                grid-template-columns: minmax(0, 1fr);
                gap: 16px;
                margin-bottom: 20px;
            }
            .ai-opp-body-grid.has-gap {
                grid-template-columns: minmax(0, 1.2fr) minmax(0, 1fr);
            }
            @media (max-width: 768px) {
                .ai-opp-body-grid.has-gap {
                    grid-template-columns: minmax(0, 1fr);
                }
            }

            /* Match Insight Box */
            .ai-insight-box {
                background: #F8FAFC;
                border: 1px solid #EEF2F6;
                border-radius: 14px;
                padding: 16px;
                box-sizing: border-box;
            }
            .ai-insight-box-head {
                display: flex;
                align-items: center;
                gap: 8px;
                margin-bottom: 8px;
            }
            .ai-insight-box-title {
                font-size: 12.5px;
                font-weight: 700;
                color: #000623;
                margin: 0;
                display: flex;
                align-items: center;
                gap: 6px;
            }
            .ai-insight-box-desc {
                font-size: 13.5px;
                color: #475569;
                margin: 0 0 12px;
                line-height: 1.5;
            }
            .ai-skills-wrap {
                display: flex;
                flex-wrap: wrap;
                gap: 6px;
            }
            .ai-skill-tag {
                display: inline-flex !important;
                align-items: center !important;
                gap: 4px;
                padding: 4px 10px;
                background: #FFFFFF;
                border: 1px solid #E2E8F0;
                border-radius: 99px;
                font-size: 11.5px;
                font-weight: 600;
                color: #334155;
                white-space: nowrap !important;
                flex-shrink: 0 !important;
            }

            /* Potential Gap Box */
            .ai-gap-box {
                background: #FFFBF7;
                border: 1px solid #FED7AA;
                border-radius: 14px;
                padding: 16px;
                box-sizing: border-box;
            }
            .ai-gap-head {
                display: flex;
                align-items: center;
                gap: 6px;
                margin-bottom: 8px;
                color: #C2410C;
                font-size: 12.5px;
                font-weight: 700;
            }
            .ai-gap-title {
                font-size: 13.5px;
                font-weight: 700;
                color: #000623;
                margin: 0 0 4px;
            }
            .ai-gap-desc {
                font-size: 12.5px;
                color: #64748B;
                margin: 0;
                line-height: 1.5;
            }

            /* Actions Row */
            .ai-opp-footer {
                border-top: 1px solid #F1F5F9;
                padding-top: 16px;
                display: flex;
                align-items: center;
                justify-content: flex-start;
                gap: 12px;
            }
            .ai-btn-view {
                display: inline-flex !important;
                align-items: center !important;
                justify-content: center !important;
                gap: 6px;
                padding: 9px 18px;
                background: #4F48E6;
                color: #FFFFFF;
                border-radius: 10px;
                font-size: 13px;
                font-weight: 700;
                text-decoration: none;
                transition: background 0.15s ease, transform 0.15s ease;
                white-space: nowrap !important;
                flex-shrink: 0 !important;
                box-shadow: 0 2px 6px rgba(79, 72, 230, 0.2);
            }
            .ai-btn-view:hover {
                background: #3730A3;
                transform: translateY(-1px);
            }
            .ai-btn-save {
                display: inline-flex !important;
                align-items: center !important;
                justify-content: center !important;
                gap: 6px;
                padding: 8px 16px;
                background: #FFFFFF;
                border: 1px solid #CBD5E1;
                border-radius: 10px;
                font-size: 13px;
                font-weight: 700;
                color: #475569;
                cursor: pointer;
                transition: all 0.15s ease;
                white-space: nowrap !important;
                flex-shrink: 0 !important;
            }
            .ai-btn-save:hover {
                background: #F8FAFC;
                border-color: #94A3B8;
                color: #000623;
            }
            .ai-btn-save.is-saved {
                background: #EEF2FF;
                border-color: #A5B4FC;
                color: #4338CA;
            }
            </style>

            <!-- Page Header -->
            <div class="ai-match-page-header">
                <h1 class="ai-match-page-title">AI Matched Internships</h1>
                <p class="ai-match-page-sub">We found <?= htmlspecialchars((string)$totalCount) ?> opportunities that match your profile</p>
            </div>

            <!-- Filter & Sort Bar -->
            <div class="ai-filter-bar">
                <!-- Left Tabs: Sort Criteria -->
                <div class="ai-filter-tabs">
                    <button type="button" class="ai-filter-tab-btn" :class="{ 'is-active': activeTab === 'best_match' }" @click="activeTab = 'best_match'">Best Match</button>
                    <button type="button" class="ai-filter-tab-btn" :class="{ 'is-active': activeTab === 'newest' }" @click="activeTab = 'newest'">Newest</button>
                    <button type="button" class="ai-filter-tab-btn" :class="{ 'is-active': activeTab === 'deadline' }" @click="activeTab = 'deadline'">Deadline</button>
                </div>

                <!-- Right Filter Dropdowns (Custom Alpine Dropdowns — Rule K) -->
                <div class="ai-filter-dropdowns">
                    <!-- 1. Location -->
                    <div class="ai-dd-wrap" @click.outside="locationOpen = false">
                        <button type="button" class="ai-dd-btn" @click="locationOpen = !locationOpen">
                            <span x-text="'Location: ' + selectedLocation"></span>
                            <svg style="width: 13px; height: 13px; transition: transform 0.2s;" :style="locationOpen ? 'transform: rotate(180deg)' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div class="ai-dd-menu" x-show="locationOpen" style="display: none;">
                            <button type="button" class="ai-dd-item" :class="{ 'is-selected': selectedLocation === 'All' }" @click="selectedLocation = 'All'; locationOpen = false">All Locations</button>
                            <button type="button" class="ai-dd-item" :class="{ 'is-selected': selectedLocation === 'Lagos' }" @click="selectedLocation = 'Lagos'; locationOpen = false">Lagos</button>
                            <button type="button" class="ai-dd-item" :class="{ 'is-selected': selectedLocation === 'Abuja' }" @click="selectedLocation = 'Abuja'; locationOpen = false">Abuja</button>
                            <button type="button" class="ai-dd-item" :class="{ 'is-selected': selectedLocation === 'Remote' }" @click="selectedLocation = 'Remote'; locationOpen = false">Remote</button>
                        </div>
                    </div>

                    <!-- 2. Categories -->
                    <div class="ai-dd-wrap" @click.outside="categoryOpen = false">
                        <button type="button" class="ai-dd-btn" @click="categoryOpen = !categoryOpen">
                            <span x-text="'Categories: ' + selectedCategory"></span>
                            <svg style="width: 13px; height: 13px; transition: transform 0.2s;" :style="categoryOpen ? 'transform: rotate(180deg)' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div class="ai-dd-menu" x-show="categoryOpen" style="display: none;">
                            <button type="button" class="ai-dd-item" :class="{ 'is-selected': selectedCategory === 'All' }" @click="selectedCategory = 'All'; categoryOpen = false">All Categories</button>
                            <button type="button" class="ai-dd-item" :class="{ 'is-selected': selectedCategory === 'Design' }" @click="selectedCategory = 'Design'; categoryOpen = false">Product & Design</button>
                            <button type="button" class="ai-dd-item" :class="{ 'is-selected': selectedCategory === 'Engineering' }" @click="selectedCategory = 'Engineering'; categoryOpen = false">Engineering & Tech</button>
                            <button type="button" class="ai-dd-item" :class="{ 'is-selected': selectedCategory === 'Marketing' }" @click="selectedCategory = 'Marketing'; categoryOpen = false">Growth & Marketing</button>
                        </div>
                    </div>

                    <!-- 3. Role -->
                    <div class="ai-dd-wrap" @click.outside="roleOpen = false">
                        <button type="button" class="ai-dd-btn" @click="roleOpen = !roleOpen">
                            <span x-text="'Role: ' + selectedRole"></span>
                            <svg style="width: 13px; height: 13px; transition: transform 0.2s;" :style="roleOpen ? 'transform: rotate(180deg)' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div class="ai-dd-menu" x-show="roleOpen" style="display: none;">
                            <button type="button" class="ai-dd-item" :class="{ 'is-selected': selectedRole === 'All' }" @click="selectedRole = 'All'; roleOpen = false">All Roles</button>
                            <button type="button" class="ai-dd-item" :class="{ 'is-selected': selectedRole === 'Product Design' }" @click="selectedRole = 'Product Design'; roleOpen = false">Product Designer</button>
                            <button type="button" class="ai-dd-item" :class="{ 'is-selected': selectedRole === 'UX Design' }" @click="selectedRole = 'UX Design'; roleOpen = false">UX Design</button>
                            <button type="button" class="ai-dd-item" :class="{ 'is-selected': selectedRole === 'UI Engineer' }" @click="selectedRole = 'UI Engineer'; roleOpen = false">UI Engineer</button>
                        </div>
                    </div>

                    <!-- 4. Duration -->
                    <div class="ai-dd-wrap" @click.outside="durationOpen = false">
                        <button type="button" class="ai-dd-btn" @click="durationOpen = !durationOpen">
                            <span x-text="'Duration: ' + selectedDuration"></span>
                            <svg style="width: 13px; height: 13px; transition: transform 0.2s;" :style="durationOpen ? 'transform: rotate(180deg)' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div class="ai-dd-menu" x-show="durationOpen" style="display: none;">
                            <button type="button" class="ai-dd-item" :class="{ 'is-selected': selectedDuration === 'All' }" @click="selectedDuration = 'All'; durationOpen = false">All Durations</button>
                            <button type="button" class="ai-dd-item" :class="{ 'is-selected': selectedDuration === '3 months' }" @click="selectedDuration = '3 months'; durationOpen = false">3 Months</button>
                            <button type="button" class="ai-dd-item" :class="{ 'is-selected': selectedDuration === '6 months' }" @click="selectedDuration = '6 months'; durationOpen = false">6 Months</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Internship Cards Stack -->
            <div class="ai-cards-stack">
                <?php foreach ($matchedInternships as $internship): 
                    $score = (int)($internship['match_score'] ?? 80);
                    $tierClass = $score >= 90 ? 'tier-high' : ($score >= 75 ? 'tier-mid' : 'tier-fair');
                    $hasGap = !empty($internship['potential_gap']);
                ?>
                <div class="ai-opp-card" x-data="{ isSaved: <?= ($internship['is_saved'] ?? false) ? 'true' : 'false' ?> }">
                    <!-- Top Row -->
                    <div class="ai-opp-top">
                        <div class="ai-opp-company-meta">
                            <div class="ai-opp-logo" style="background-color: <?= htmlspecialchars($internship['logo_bg']) ?>;">
                                <?= htmlspecialchars($internship['initials']) ?>
                            </div>
                            <div class="ai-opp-titles">
                                <h2 class="ai-opp-role-title"><?= htmlspecialchars($internship['role']) ?></h2>
                                <p class="ai-opp-subline">
                                    <span><?= htmlspecialchars($internship['company']) ?></span>
                                    <span class="ai-opp-subline-dot">•</span>
                                    <span><?= htmlspecialchars($internship['stipend']) ?></span>
                                    <span class="ai-opp-subline-dot">•</span>
                                    <span><?= htmlspecialchars($internship['location']) ?></span>
                                    <span class="ai-opp-subline-dot">•</span>
                                    <span><?= htmlspecialchars($internship['duration']) ?></span>
                                </p>
                            </div>
                        </div>

                        <!-- Match Score Pill -->
                        <div class="ai-opp-score-col">
                            <div class="ai-score-pill <?= $tierClass ?>">
                                <!-- Jenny AI SVG Icon Mark -->
                                <svg style="width: 13px; height: 13px; flex-shrink: 0;" viewBox="0 0 74 83" fill="currentColor">
                                    <path d="M73.74 32.4627V59.9427C67.93 59.7673 62.2767 61.8422 57.9596 65.7344C53.6426 69.6267 50.9952 75.0356 50.57 80.8327C50.52 81.3027 50.48 81.8327 50.46 82.2827H23.57C23.57 82.0227 23.48 81.7627 23.43 81.5127C20.73 67.1427 16.41 63.2427 0 59.6527V32.6527C13.34 31.5727 27.12 37.6527 36.92 47.9327C46 38.3527 58.99 31.8927 73.74 32.4627Z"/>
                                    <path d="M37.0001 0.0031237C40.4833 0.0367014 43.8785 1.10075 46.7578 3.06112C49.637 5.02149 51.8715 7.79043 53.1794 11.0188C54.4873 14.2473 54.8101 17.7906 54.1071 21.2023C53.4042 24.6139 51.7069 27.741 49.2293 30.1894C46.7516 32.6378 43.6046 34.2979 40.1849 34.9604C36.7652 35.6228 33.2259 35.258 30.0132 33.9119C26.8005 32.5658 24.0583 30.2987 22.1322 27.3963C20.2061 24.494 19.1824 21.0864 19.1901 17.6031C19.1737 15.2695 19.6247 12.9562 20.5166 10.7997C21.4084 8.64315 22.7231 6.68703 24.383 5.04669C26.0429 3.40635 28.0145 2.11499 30.1814 1.24877C32.3484 0.382549 34.6669 -0.040991 37.0001 0.0031237Z"/>
                                </svg>
                                <span><?= htmlspecialchars((string)$score) ?>% Match</span>
                            </div>
                            <span class="ai-score-tier-label <?= $tierClass ?>"><?= htmlspecialchars($internship['match_tier']) ?></span>
                        </div>
                    </div>

                    <!-- Body Grid: Why match + (Optional) Potential Gap for 60-70% scores -->
                    <div class="ai-opp-body-grid <?= $hasGap ? 'has-gap' : '' ?>">
                        <!-- Column 1: Why you're a match -->
                        <div class="ai-insight-box">
                            <div class="ai-insight-box-head">
                                <h3 class="ai-insight-box-title">
                                    <svg style="width: 15px; height: 15px; color: #4F48E6; flex-shrink: 0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                                    </svg>
                                    <span>Why you're a match</span>
                                </h3>
                            </div>
                            <p class="ai-insight-box-desc"><?= htmlspecialchars($internship['match_reason']) ?></p>
                            
                            <!-- Skill Pills -->
                            <div class="ai-skills-wrap">
                                <?php foreach ($internship['skills'] as $skill): ?>
                                <span class="ai-skill-tag">
                                    <svg style="width: 12px; height: 12px; color: #4F48E6; flex-shrink: 0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    <span><?= htmlspecialchars($skill) ?></span>
                                </span>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <!-- Column 2: Potential Gap (Only rendered for roles with gap analysis) -->
                        <?php if ($hasGap): ?>
                        <div class="ai-gap-box">
                            <div class="ai-gap-head">
                                <svg style="width: 15px; height: 15px; color: #EA580C; flex-shrink: 0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                                <span>Potential Gap</span>
                            </div>
                            <h4 class="ai-gap-title"><?= htmlspecialchars($internship['potential_gap']['title']) ?></h4>
                            <p class="ai-gap-desc"><?= htmlspecialchars($internship['potential_gap']['desc']) ?></p>
                        </div>
                        <?php endif; ?>
                    </div>

                    <!-- Footer Actions -->
                    <div class="ai-opp-footer">
                        <a href="<?= url('/internship/' . $internship['slug']) ?>" class="ai-btn-view">
                            <span>View Opportunity</span>
                            <svg style="width: 14px; height: 14px; flex-shrink: 0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </a>
                        <button type="button" class="ai-btn-save" :class="{ 'is-saved': isSaved }" 
                                @click="isSaved = !isSaved; $dispatch('show-toast', { message: isSaved ? 'Saved to your collection' : 'Removed from saved internships', type: 'success' })">
                            <svg style="width: 15px; height: 15px; flex-shrink: 0;" :fill="isSaved ? '#4338CA' : 'none'" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/>
                            </svg>
                            <span x-text="isSaved ? 'Saved' : 'Save'"></span>
                        </button>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

