<?php
declare(strict_types=1);
/**
 * View: Employer Hiring Analytics & Enterprise Reports (Phase 5)
 * Route: /employer/analytics
 * Layout: dashboard
 * 
 * Complies with docs/01-roadmap.md (Phase 5 Enterprise Features)
 * and .agents/rules/agent_mistakes_preventions.md.
 * 
 * @var array  $employer     Employer profile info
 * @var array  $kpis         Key recruitment performance metrics
 * @var array  $funnel       Hiring conversion funnel stages
 * @var array  $departments  Department recruitment velocity data
 * @var array  $sources      Top candidate university sources
 */

$companyName = $employer['company_name'] ?? 'Nexura Technologies';
$role        = $employer['role'] ?? 'Talent Acquisition Team';
?>
<style>
/* ==========================================================================
   EMPLOYER ENTERPRISE ANALYTICS & REPORTS — DESIGN SYSTEM STYLES
   ========================================================================== */

.analytics-container {
    padding: 0 0 32px 0;
    max-width: 100%;
    box-sizing: border-box;
}

/* Page Header Row */
.analytics-header-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    flex-wrap: wrap;
    margin-bottom: 24px;
    box-sizing: border-box;
}

.analytics-title {
    font-size: 24px;
    font-weight: 800;
    color: #000623;
    letter-spacing: -0.02em;
    margin: 0 0 4px;
    line-height: 1.25;
}

.analytics-sub {
    font-size: 13.5px;
    color: #64748B;
    margin: 0;
}

.analytics-actions {
    display: flex;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
}

/* Standardized Export Button */
.analytics-export-btn {
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    gap: 8px !important;
    white-space: nowrap !important;
    flex-shrink: 0 !important;
    font-size: 13px;
    font-weight: 700;
    padding: 9px 18px;
    border-radius: 10px;
    background: #000623;
    color: #FFFFFF;
    border: 1px solid #000623;
    cursor: pointer;
    text-decoration: none;
    transition: all 0.15s ease;
    box-shadow: 0 1px 3px rgba(0, 6, 35, 0.15);
}

.analytics-export-btn:hover {
    background: #4F48E6;
    border-color: #4F48E6;
    color: #FFFFFF;
}

/* KPI Metric Cards Grid */
.analytics-kpi-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(210px, 1fr));
    gap: 16px;
    margin-bottom: 24px;
    width: 100%;
    box-sizing: border-box;
}

.analytics-kpi-card {
    background: #FFFFFF;
    border: 1px solid #E2E8F0;
    border-radius: 14px;
    padding: 20px 22px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    box-shadow: 0 1px 3px rgba(0,0,0,0.02);
    min-width: 0;
}

.analytics-kpi-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 10px;
}

.analytics-kpi-lbl {
    font-size: 12px;
    font-weight: 700;
    color: #64748B;
    text-transform: uppercase;
    letter-spacing: 0.04em;
}

.analytics-kpi-badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    font-size: 11.5px;
    font-weight: 700;
    padding: 2px 8px;
    border-radius: 9999px;
    white-space: nowrap !important;
    flex-shrink: 0 !important;
}

.analytics-kpi-badge.up {
    background: #ECFDF5;
    color: #059669;
    border: 1px solid #A7F3D0;
}

.analytics-kpi-badge.down {
    background: #EFF6FF;
    color: #2563EB;
    border: 1px solid #BFDBFE;
}

.analytics-kpi-val {
    font-size: 26px;
    font-weight: 800;
    color: #000623;
    line-height: 1.2;
    margin-bottom: 4px;
}

.analytics-kpi-sub {
    font-size: 12px;
    color: #94A3B8;
}

/* 2-Column Analytic Shelves Grid */
.analytics-sections-grid {
    display: grid;
    grid-template-columns: 3fr 2fr;
    gap: 20px;
    margin-bottom: 24px;
    width: 100%;
    box-sizing: border-box;
}

/* Section Card Standard: Minimum 24px 28px padding */
.analytics-card {
    background: #FFFFFF;
    border: 1px solid #E2E8F0;
    border-radius: 16px;
    padding: 24px 28px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.02);
    box-sizing: border-box;
    width: 100%;
    min-width: 0;
}

.analytics-card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 20px;
    padding-bottom: 14px;
    border-bottom: 1px solid #F1F5F9;
    flex-wrap: wrap;
    gap: 12px;
}

.analytics-card-title {
    font-size: 16px;
    font-weight: 800;
    color: #000623;
    margin: 0 0 2px;
}

.analytics-card-desc {
    font-size: 12.5px;
    color: #64748B;
    margin: 0;
}

/* Conversion Funnel Bars */
.funnel-list {
    display: flex;
    flex-direction: column;
    gap: 14px;
}

.funnel-row {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.funnel-row-meta {
    display: flex;
    align-items: center;
    justify-content: space-between;
    font-size: 13px;
}

.funnel-stage-name {
    font-weight: 700;
    color: #0F172A;
}

.funnel-counts {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    color: #64748B;
    font-size: 12.5px;
}

.funnel-count-bold {
    font-weight: 800;
    color: #000623;
}

.funnel-bar-track {
    width: 100%;
    height: 10px;
    background: #F1F5F9;
    border-radius: 9999px;
    overflow: hidden;
}

.funnel-bar-fill {
    height: 100%;
    border-radius: 9999px;
    transition: width 0.4s ease;
}

/* University Sources Progress Bars */
.source-item {
    display: flex;
    flex-direction: column;
    gap: 6px;
    margin-bottom: 14px;
}

.source-item:last-child {
    margin-bottom: 0;
}

.source-meta {
    display: flex;
    align-items: center;
    justify-content: space-between;
    font-size: 12.5px;
}

.source-name {
    font-weight: 600;
    color: #334155;
    truncate: true;
}

.source-pct {
    font-weight: 800;
    color: #000623;
    white-space: nowrap !important;
}

/* Table Card */
.analytics-table-wrap {
    width: 100%;
    overflow-x: auto;
    border-radius: 10px;
    border: 1px solid #F1F5F9;
}

.analytics-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 13px;
    text-align: left;
}

.analytics-table th {
    background: #FAFAFC;
    color: #64748B;
    font-weight: 700;
    font-size: 11.5px;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    padding: 12px 16px;
    border-bottom: 1px solid #E2E8F0;
    white-space: nowrap !important;
}

.analytics-table td {
    padding: 14px 16px;
    border-bottom: 1px solid #F1F5F9;
    color: #334155;
    vertical-align: middle;
}

.analytics-table tr:last-child td {
    border-bottom: none;
}

.analytics-status-pill {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-size: 11.5px;
    font-weight: 700;
    padding: 3px 10px;
    border-radius: 9999px;
    background: #EEF2FF;
    color: #4F48E6;
    border: 1px solid #C7D2FE;
    white-space: nowrap !important;
    flex-shrink: 0 !important;
}

/* Dropdown Menu Component (.db-dropdown-wrap) */
.db-dropdown-wrap {
    position: relative;
    display: inline-block;
}

.db-dropdown-btn {
    display: inline-flex !important;
    align-items: center !important;
    justify-content: space-between !important;
    gap: 8px !important;
    background: #FFFFFF;
    border: 1px solid #E2E8F0;
    border-radius: 10px;
    padding: 9px 14px;
    font-size: 13px;
    font-weight: 600;
    color: #334155;
    cursor: pointer;
    box-shadow: 0 1px 2px rgba(0,0,0,0.03);
    white-space: nowrap !important;
    flex-shrink: 0 !important;
}

.db-dropdown-btn svg,
.analytics-export-btn svg {
    width: 14px !important;
    height: 14px !important;
    max-width: 14px !important;
    max-height: 14px !important;
    flex-shrink: 0 !important;
    transition: transform 0.2s ease;
}

.db-dropdown-btn svg.rotate-180,
.analytics-export-btn svg.rotate-180 {
    transform: rotate(180deg) !important;
}

.db-dropdown-btn:hover {
    border-color: #CBD5E1;
    background: #F8FAFC;
}

.db-dropdown-menu {
    position: absolute;
    right: 0;
    top: calc(100% + 6px);
    background: #FFFFFF;
    border: 1px solid #E2E8F0;
    border-radius: 12px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.08);
    min-width: 220px;
    z-index: 50;
    overflow: hidden;
    padding: 6px;
}

.db-dropdown-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 9px 12px;
    font-size: 12.5px;
    font-weight: 600;
    color: #1E293B;
    text-decoration: none;
    border-radius: 8px;
    transition: background 0.15s ease;
    cursor: pointer;
}

.db-dropdown-item:hover {
    background: #F1F5F9;
    color: #4F48E6;
}

.db-dropdown-item svg {
    width: 15px;
    height: 15px;
    color: #64748B;
    flex-shrink: 0;
}

/* Header actions: period picker and export side by side, right-aligned */
.analytics-actions {
    flex-wrap: nowrap;
    justify-content: flex-end;
    margin-left: auto;
}

.analytics-actions .db-dropdown-wrap {
    position: relative;
    width: auto;
}

.analytics-actions .db-dropdown-btn {
    width: auto;
    min-width: 172px;
    height: 40px;
    justify-content: space-between;
    gap: 10px;
    box-sizing: border-box;
}

.analytics-actions .analytics-export-btn {
    height: 40px;
    box-sizing: border-box;
}

/* KPI cards: five across on desktop, 3 + 2 on tablets, stacked on phones */
.analytics-kpi-grid {
    grid-template-columns: repeat(5, minmax(0, 1fr));
}

@media (max-width: 1180px) {
    .analytics-kpi-grid {
        grid-template-columns: repeat(3, minmax(0, 1fr));
    }
}

/* KPI cards: label, value, trend and note sit on the same lines in every card */
.analytics-kpi-card {
    padding: 18px;
    display: grid;
    grid-template-columns: minmax(0, 1fr);
    align-content: start;
    justify-content: stretch;
    row-gap: 8px;
}

.analytics-kpi-top {
    display: contents;
}

.analytics-kpi-lbl {
    grid-row: 1;
    overflow: hidden;
    white-space: nowrap;
    text-overflow: ellipsis;
}

.analytics-kpi-val {
    grid-row: 2;
    margin-bottom: 0;
    white-space: nowrap;
}

.analytics-kpi-badge {
    grid-row: 3;
    justify-self: start;
}

.analytics-kpi-sub {
    grid-row: 4;
    line-height: 1.45;
}

/* Responsive Media Queries */
@media (max-width: 960px) {
    .analytics-sections-grid {
        grid-template-columns: minmax(0, 1fr) !important;
    }
}

@media (max-width: 640px) {
    .analytics-card {
        padding: 18px 16px !important;
    }
    .analytics-header-row {
        flex-direction: column !important;
        align-items: stretch !important;
        gap: 14px !important;
    }
    .analytics-actions {
        width: 100% !important;
        flex-direction: column !important;
    }
    .analytics-actions .db-dropdown-wrap,
    .analytics-actions .db-dropdown-btn,
    .analytics-actions .analytics-export-btn {
        width: 100% !important;
        justify-content: center !important;
    }
    .analytics-kpi-grid {
        grid-template-columns: minmax(0, 1fr) !important;
    }
}
</style>

<div class="db-shell" x-data="{
    periodOpen: false,
    selectedPeriod: 'Last 30 Days',
    exportOpen: false,
    selectPeriod(p) {
        this.selectedPeriod = p;
        this.periodOpen = false;
        if (typeof window.$dispatch === 'function') {
            window.$dispatch('show-toast', { text: 'Report updated for ' + p, type: 'info' });
        }
    }
}">

    <!-- Mobile Drawer Overlay -->
    <div class="db-sidebar-overlay" id="db-sidebar-overlay"></div>

    <!-- ══ SIDEBAR ══ -->
    <?php 
    $activeNav = 'analytics';
    include dirname(__DIR__, 2) . '/shared/components/employer-sidebar.php'; 
    ?>

    <!-- ══ MAIN CONTENT WRAPPER ══ -->
    <div class="db-main">

        <!-- Top Navigation -->
        <?php include dirname(__DIR__, 2) . '/shared/components/employer-topnav.php'; ?>

        <!-- Content Body -->
        <main class="db-content" id="db-content">
            <div class="analytics-container">

                <!-- ── 1. Page Header ── -->
                <div class="analytics-header-row">
                    <div>
                        <h1 class="analytics-title">Hiring Analytics &amp; Reports</h1>
                        <p class="analytics-sub">Monitor recruitment velocity, candidate funnel drop-offs, and export enterprise hiring ledgers.</p>
                    </div>

                    <div class="analytics-actions">
                        <!-- Custom Period Dropdown -->
                        <div class="db-dropdown-wrap" @click.outside="periodOpen = false">
                            <button type="button" class="db-dropdown-btn" @click="periodOpen = !periodOpen" aria-label="Select report time window">
                                <svg style="color: #64748B;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <span x-text="selectedPeriod">Last 30 Days</span>
                                <svg :class="{ 'rotate-180': periodOpen }" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>

                            <div x-show="periodOpen" class="db-dropdown-menu" style="display: none;" x-transition>
                                <div class="db-dropdown-item" @click="selectPeriod('Last 7 Days')">Last 7 Days</div>
                                <div class="db-dropdown-item" @click="selectPeriod('Last 30 Days')">Last 30 Days</div>
                                <div class="db-dropdown-item" @click="selectPeriod('Last 90 Days')">Last 90 Days</div>
                                <div class="db-dropdown-item" @click="selectPeriod('Year to Date')">Year to Date (2026)</div>
                            </div>
                        </div>

                        <!-- Export Dropdown -->
                        <div class="db-dropdown-wrap" @click.outside="exportOpen = false">
                            <button type="button" class="analytics-export-btn" @click="exportOpen = !exportOpen" aria-label="Export hiring reports">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                </svg>
                                <span>Export Reports</span>
                                <svg :class="{ 'rotate-180': exportOpen }" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>

                            <div x-show="exportOpen" class="db-dropdown-menu" style="display: none;" x-transition>
                                <a href="<?= url('/employer/analytics/export/candidates') ?>" class="db-dropdown-item" @click="exportOpen = false">
                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                    <span>Candidates Pipeline (CSV)</span>
                                </a>
                                <a href="<?= url('/employer/analytics/export/interviews') ?>" class="db-dropdown-item" @click="exportOpen = false">
                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <span>Interview Schedule (CSV)</span>
                                </a>
                                <a href="<?= url('/employer/analytics/export/billing') ?>" class="db-dropdown-item" @click="exportOpen = false">
                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    <span>Billing Ledger (CSV)</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ── 2. 5 Core Recruitment KPIs ── -->
                <div class="analytics-kpi-grid">
                    <!-- KPI 1 -->
                    <div class="analytics-kpi-card">
                        <div class="analytics-kpi-top">
                            <span class="analytics-kpi-lbl">TOTAL VIEWS</span>
                            <span class="analytics-kpi-badge up">
                                <svg style="width: 10px; height: 10px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
                                <span><?= $kpis['total_views']['change'] ?></span>
                            </span>
                        </div>
                        <div class="analytics-kpi-val"><?= $kpis['total_views']['val'] ?></div>
                        <div class="analytics-kpi-sub"><?= $kpis['total_views']['sub'] ?></div>
                    </div>

                    <!-- KPI 2 -->
                    <div class="analytics-kpi-card">
                        <div class="analytics-kpi-top">
                            <span class="analytics-kpi-lbl">APPLICATIONS</span>
                            <span class="analytics-kpi-badge up">
                                <svg style="width: 10px; height: 10px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
                                <span><?= $kpis['applications_received']['change'] ?></span>
                            </span>
                        </div>
                        <div class="analytics-kpi-val"><?= $kpis['applications_received']['val'] ?></div>
                        <div class="analytics-kpi-sub"><?= $kpis['applications_received']['sub'] ?></div>
                    </div>

                    <!-- KPI 3 -->
                    <div class="analytics-kpi-card">
                        <div class="analytics-kpi-top">
                            <span class="analytics-kpi-lbl">INTERVIEWS</span>
                            <span class="analytics-kpi-badge up">
                                <svg style="width: 10px; height: 10px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
                                <span><?= $kpis['interviews_scheduled']['change'] ?></span>
                            </span>
                        </div>
                        <div class="analytics-kpi-val"><?= $kpis['interviews_scheduled']['val'] ?></div>
                        <div class="analytics-kpi-sub"><?= $kpis['interviews_scheduled']['sub'] ?></div>
                    </div>

                    <!-- KPI 4 -->
                    <div class="analytics-kpi-card">
                        <div class="analytics-kpi-top">
                            <span class="analytics-kpi-lbl">AVG TIME-TO-HIRE</span>
                            <span class="analytics-kpi-badge down">
                                <svg style="width: 10px; height: 10px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
                                <span><?= $kpis['avg_time_to_hire']['change'] ?></span>
                            </span>
                        </div>
                        <div class="analytics-kpi-val"><?= $kpis['avg_time_to_hire']['val'] ?></div>
                        <div class="analytics-kpi-sub"><?= $kpis['avg_time_to_hire']['sub'] ?></div>
                    </div>

                    <!-- KPI 5 -->
                    <div class="analytics-kpi-card">
                        <div class="analytics-kpi-top">
                            <span class="analytics-kpi-lbl">OFFERS ACCEPTED</span>
                            <span class="analytics-kpi-badge up">
                                <span><?= $kpis['offers_accepted']['change'] ?></span>
                            </span>
                        </div>
                        <div class="analytics-kpi-val"><?= $kpis['offers_accepted']['val'] ?></div>
                        <div class="analytics-kpi-sub"><?= $kpis['offers_accepted']['sub'] ?></div>
                    </div>
                </div>

                <!-- ── 3. Funnel & Talent Sources Grid ── -->
                <div class="analytics-sections-grid">
                    <!-- Funnel Section (Left) -->
                    <div class="analytics-card">
                        <div class="analytics-card-header">
                            <div>
                                <h2 class="analytics-card-title">Hiring Conversion Funnel</h2>
                                <p class="analytics-card-desc">Tracking applicant volume through each pipeline checkpoint.</p>
                            </div>
                            <span class="analytics-status-pill">Overall Yield: 8.5%</span>
                        </div>

                        <div class="funnel-list">
                            <?php 
                            $colors = ['#4F48E6', '#6366F1', '#818CF8', '#0EA5E9', '#F59E0B', '#10B981'];
                            foreach ($funnel as $idx => $stg): 
                                $col = $colors[$idx % count($colors)];
                            ?>
                            <div class="funnel-row">
                                <div class="funnel-row-meta">
                                    <span class="funnel-stage-name"><?= htmlspecialchars($stg['stage']) ?></span>
                                    <div class="funnel-counts">
                                        <span class="funnel-count-bold"><?= number_format($stg['count']) ?></span>
                                        <span>(<?= $stg['percentage'] ?>%)</span>
                                        <?php if ($stg['dropoff'] !== '0%'): ?>
                                            <span style="color: #EF4444; font-size: 11px;"><?= $stg['dropoff'] ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="funnel-bar-track">
                                    <div class="funnel-bar-fill" style="width: <?= $stg['percentage'] ?>%; background: <?= $col ?>;"></div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Talent Sources (Right) -->
                    <div class="analytics-card">
                        <div class="analytics-card-header">
                            <div>
                                <h2 class="analytics-card-title">Top University Sources</h2>
                                <p class="analytics-card-desc">Leading Nigerian tertiary institutions supplying candidates.</p>
                            </div>
                        </div>

                        <div>
                            <?php foreach ($sources as $src): ?>
                            <div class="source-item">
                                <div class="source-meta">
                                    <span class="source-name"><?= htmlspecialchars($src['name']) ?></span>
                                    <span class="source-pct"><?= $src['percentage'] ?>%</span>
                                </div>
                                <div class="funnel-bar-track" style="height: 8px;">
                                    <div class="funnel-bar-fill" style="width: <?= $src['percentage'] ?>%; background: <?= $src['color'] ?>;"></div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>

                        <div style="margin-top: 24px; padding: 14px; background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 12px; display: flex; align-items: center; gap: 12px;">
                            <div style="width: 32px; height: 32px; border-radius: 8px; background: #EEF2FF; color: #4F48E6; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                <svg style="width: 16px; height: 16px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                            </div>
                            <div style="font-size: 12px; color: #475569; line-height: 1.45;">
                                <strong>AI Sourcing Insight:</strong> UNILAG candidates demonstrate the fastest turnaround from invitation to confirmed interview (avg 4.2 hours).
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ── 4. Department Recruitment Velocity Table ── -->
                <div class="analytics-card">
                    <div class="analytics-card-header">
                        <div>
                            <h2 class="analytics-card-title">Hiring Velocity by Discipline</h2>
                            <p class="analytics-card-desc">Comparative breakdown of pipeline volume, interview frequency, and time-to-hire by department.</p>
                        </div>
                    </div>

                    <div class="analytics-table-wrap">
                        <table class="analytics-table" style="min-width: 640px;">
                            <thead>
                                <tr>
                                    <th>Department Role</th>
                                    <th>Active Openings</th>
                                    <th>Total Applicants</th>
                                    <th>Interviews</th>
                                    <th>Avg Time-to-Hire</th>
                                    <th>Primary Talent Source</th>
                                    <th>Current Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($departments as $dept): ?>
                                <tr>
                                    <td style="font-weight: 700; color: #000623;">
                                        <?= htmlspecialchars($dept['name']) ?>
                                    </td>
                                    <td><?= $dept['openings'] ?> Role<?= $dept['openings'] > 1 ? 's' : '' ?></td>
                                    <td style="font-weight: 700;"><?= $dept['applicants'] ?> Candidates</td>
                                    <td><?= $dept['interviews'] ?> Scheduled</td>
                                    <td style="color: #4F48E6; font-weight: 700;"><?= $dept['avg_days'] ?></td>
                                    <td><?= htmlspecialchars($dept['top_source']) ?></td>
                                    <td>
                                        <span class="analytics-status-pill">
                                            <?= htmlspecialchars($dept['status']) ?>
                                        </span>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </main>

        <!-- Footer -->
        <?php include dirname(__DIR__, 2) . '/shared/components/db-footer.php'; ?>

    </div>
</div>
