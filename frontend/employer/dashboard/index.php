<?php
declare(strict_types=1);
/**
 * View: Employer Dashboard
 * Route: /employer/dashboard
 * Layout: dashboard (custom sidebar layout — no global navbar/footer)
 * @var array  $employer        Employer profile data
 * @var array  $stats           KPI statistics
 * @var array  $reviewQueue     Pending review queue candidates
 * @var array  $recommendations AI recommended candidates
 * @var array  $interviews      Upcoming interviews
 * @var array  $activity        Recent activities
 */

$companyName = $employer['company_name'] ?? 'Global Tech Solutions';
$role        = $employer['role'] ?? 'Manage Talent';
$avatar      = $employer['avatar'] ?? null;
?>
<style>
    /* ── Dashboard Base Styles ── */
    .emp-banner {
        background: linear-gradient(135deg, #FAFBFD, #F1F5F9);
        border: 1px solid #E8ECF2;
        border-radius: 20px;
        padding: 32px;
        margin-bottom: 32px;
        display: flex;
        flex-direction: row;
        justify-content: space-between;
        align-items: center;
        gap: 24px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }
    
    .emp-stats-grid {
        display: grid;
        grid-template-columns: repeat(5, minmax(0, 1fr));
        gap: 16px;
        margin-bottom: 32px;
    }

    .emp-kpi-card {
        background-color: #FFFFFF;
        border: 1px solid #E2E8F0;
        border-radius: 16px;
        padding: 18px 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
        transition: transform 0.15s ease, box-shadow 0.15s ease;
    }

    .emp-kpi-card:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04);
    }

    .emp-kpi-card-info {
        display: flex;
        flex-direction: column;
    }

    .emp-kpi-title {
        font-size: 13px;
        font-weight: 500;
        color: #64748B;
        margin-bottom: 4px;
    }

    .emp-kpi-num {
        font-size: 26px;
        font-weight: 800;
        color: #0F172A;
        line-height: 1.1;
        letter-spacing: -0.02em;
        margin-bottom: 2px;
    }

    .emp-kpi-sub {
        font-size: 12px;
        font-weight: 500;
        color: #94A3B8;
    }

    .emp-kpi-icon-wrap {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .emp-kpi-icon-wrap svg {
        width: 22px;
        height: 22px;
    }

    /* ── Applications Performance Chart ── */
    .chart-legend-wrap {
        display: flex;
        justify-content: flex-end;
        gap: 16px;
        font-size: 11px;
        font-weight: 600;
        margin-bottom: 16px;
    }

    .chart-legend-item {
        display: flex;
        align-items: center;
        gap: 6px;
        color: #64748b;
    }

    .chart-legend-dot {
        display: inline-block;
        height: 8px;
        width: 8px;
        border-radius: 50%;
    }

    .emp-chart-container {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        height: 260px;
        padding-bottom: 12px;
        border-bottom: 1px solid #F1F5F9;
        gap: 8px;
    }

    .emp-chart-col {
        display: flex;
        flex-direction: column;
        align-items: center;
        flex: 1;
        height: 100%;
        justify-content: flex-end;
        gap: 8px;
    }

    .emp-chart-bars {
        display: flex;
        align-items: flex-end;
        gap: 4px;
        height: 100%;
        width: 100%;
        justify-content: center;
    }

    .emp-chart-bar-goal {
        width: 24px; /* Increased bar width on desktop */
        background: #4F47E6;
        border-radius: 4px 4px 0 0;
        transition: height 0.3s ease;
        animation: growUp 0.8s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
    }

    .emp-chart-bar-actual {
        width: 24px; /* Increased bar width on desktop */
        background: #C7D2FE;
        border-radius: 4px 4px 0 0;
        transition: height 0.3s ease;
        animation: growUp 0.8s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
    }

    .emp-chart-label {
        font-size: 11px;
        font-weight: 600;
        color: #475569;
        text-align: center;
        white-space: nowrap;
    }

    @keyframes growUp {
        from {
            height: 0;
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }

    /* ── Chart Hover Tooltips ── */
    .emp-chart-col {
        position: relative;
    }

    .emp-chart-tooltip {
        visibility: hidden;
        opacity: 0;
        position: absolute;
        bottom: 235px; /* Positioned above the chart bars */
        left: 50%;
        transform: translate(-50%, 10px);
        background: #0F172A; /* Slate 900 background */
        color: #FFFFFF;
        padding: 8px 12px;
        border-radius: 8px;
        font-size: 11px;
        font-weight: 600;
        white-space: nowrap;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        z-index: 50;
        pointer-events: none;
    }

    .emp-chart-tooltip::after {
        content: "";
        position: absolute;
        top: 100%;
        left: 50%;
        transform: translateX(-50%);
        border-width: 5px;
        border-style: solid;
        border-color: #0F172A transparent transparent transparent;
    }

    .emp-chart-col:hover .emp-chart-tooltip {
        visibility: visible;
        opacity: 1;
        transform: translate(-50%, 0);
    }

    /* ── AI Recommended Candidates ── */
    .cand-card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        width: 100%;
    }

    .cand-profile-block {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .cand-match-badge {
        font-size: 10px;
        font-weight: 700;
        color: #4F47E6;
        background: #EEF2FF;
        padding: 4px 10px;
        border-radius: 99px;
        white-space: nowrap;
        border: 1px solid #E0E7FF;
        text-transform: uppercase;
        display: inline-block;
    }

    .cand-status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 10px;
        font-weight: 700;
        padding: 4px 10px;
        border-radius: 99px;
        border: 1px solid transparent;
        white-space: nowrap;
    }

    .cand-status-available {
        color: #059669;
        background: #ECFDF5;
        border-color: #A7F3D0;
    }

    .cand-status-interviewed {
        color: #D97706;
        background: #FFFBEB;
        border-color: #FDE68A;
    }

    .status-dot {
        height: 6px;
        width: 6px;
        border-radius: 50%;
        display: inline-block;
    }

    .status-dot-available {
        background: #059669;
    }

    .status-dot-interviewed {
        background: #D97706;
    }

    .mobile-only-date {
        display: none;
    }

    .activity-flex-container {
        display: flex;
        flex-direction: column;
        gap: 20px;
        margin-bottom: 16px;
    }

    /* ── Responsive Media Queries ── */
    @media (max-width: 1024px) {
        .emp-stats-grid {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }

        .activity-flex-container {
            gap: 16px;
        }
    }
    
    @media (max-width: 640px) {
        .emp-banner {
            flex-direction: column;
            align-items: flex-start;
            padding: 24px;
            gap: 16px;
        }
        
        .emp-stats-grid {
            grid-template-columns: repeat(1, minmax(0, 1fr));
        }

        /* Restores slim chart bar widths on mobile device mode */
        .emp-chart-bar-goal, .emp-chart-bar-actual {
            width: 12px !important;
        }

        /* ── Mobile Review Queue Optimization ── */
        .db-table-responsive {
            border: none;
        }
        .db-table, .db-table thead, .db-table tbody, .db-table tr, .db-table td {
            display: block;
            width: 100%;
        }
        .db-table thead {
            display: none; /* Hide header row on mobile */
        }
        .db-table tr {
            padding: 16px 0;
            border-bottom: 1px solid #F1F5F9;
        }
        .db-table td {
            padding: 0 !important;
            margin-bottom: 6px;
            text-align: left !important;
        }
        .db-table td.td-applicant {
            margin-bottom: 8px;
        }
        .db-table td.td-role {
            font-size: 13px;
            color: #475569;
            padding-left: 48px !important; /* Left aligns clean with name */
        }
        .db-table td.td-date {
            display: none !important; /* Hides separate date cell on mobile */
        }
        .mobile-only-date {
            display: inline-block !important;
        }
        .db-table td.td-action {
            text-align: left !important;
            padding-left: 48px !important;
            margin-top: 8px;
            margin-bottom: 0;
        }
        .db-table td.td-action .btn-review {
            display: block !important;
            width: 100% !important;
            text-align: center !important;
        }
    }
</style>

<div class="db-shell">

    <!-- ══ SIDEBAR ══ -->
    <?php 
    $activeNav = 'dashboard';
    include dirname(__DIR__, 2) . '/shared/components/employer-sidebar.php'; 
    ?>

    <!-- ══ MAIN ══ -->
    <div class="db-main">

        <!-- ══ TOP NAVBAR ══ -->
        <?php include dirname(__DIR__, 2) . '/shared/components/employer-topnav.php'; ?>

        <!-- Content Area -->
        <div class="db-content">

            <!-- ── Welcome Banner ── -->
            <div class="emp-banner">
                <div>
                    <h1 style="font-size: 28px; font-weight: 800; color: #0F172A; margin: 0; line-height: 1.2;">Welcome back, <?= htmlspecialchars($companyName) ?></h1>
                    <p style="font-size: 14px; color: #475569; margin-top: 8px; font-weight: 500;">Ready to scale your team? You have 12 new applications waiting for review.</p>
                </div>
                <a href="<?= url('/employer/internships/create') ?>" style="background: #4F47E6; color: #fff; text-decoration: none; font-size: 13px; font-weight: 700; padding: 12px 24px; border-radius: 12px; display: flex; align-items: center; gap: 8px; box-shadow: 0 4px 12px rgba(79, 70, 230, 0.25); transition: transform 0.2s, background 0.2s; flex-shrink: 0;" onmouseover="this.style.background='#4338ca'; this.style.transform='translateY(-1px)';" onmouseout="this.style.background='#4F47E6'; this.style.transform='translateY(0)';">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                    <span>Launch New Opening</span>
                </a>
            </div>

            <!-- ── Stats Row ── -->
            <div class="emp-stats-grid">
                <!-- Card 1 -->
                <div class="emp-kpi-card">
                    <div class="emp-kpi-card-info">
                        <span class="emp-kpi-title">ACTIVE POSTS</span>
                        <span class="emp-kpi-num"><?= htmlspecialchars((string)($stats['active_posts'] ?? 12)) ?></span>
                        <span class="emp-kpi-sub">Live listings</span>
                    </div>
                    <div class="emp-kpi-icon-wrap" style="background: #EEF2FF; color: #4F47E6;">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                    </div>
                </div>
                <!-- Card 2 -->
                <div class="emp-kpi-card">
                    <div class="emp-kpi-card-info">
                        <span class="emp-kpi-title">APPLICATIONS</span>
                        <span class="emp-kpi-num"><?= htmlspecialchars((string)($stats['applications'] ?? '1,284')) ?></span>
                        <span class="emp-kpi-sub">Total received</span>
                    </div>
                    <div class="emp-kpi-icon-wrap" style="background: #F1F5F9; color: #475569;">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                </div>
                <!-- Card 3 -->
                <div class="emp-kpi-card">
                    <div class="emp-kpi-card-info">
                        <span class="emp-kpi-title">SHORTLISTED</span>
                        <span class="emp-kpi-num"><?= htmlspecialchars((string)($stats['shortlisted'] ?? 48)) ?></span>
                        <span class="emp-kpi-sub">Under review</span>
                    </div>
                    <div class="emp-kpi-icon-wrap" style="background: #FEF3C7; color: #D97706;">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.9 1.519-.9 1.818 0l1.518 4.674a1 1 0 00.95.69h4.907c.961 0 1.357 1.236.588 1.81l-3.97 2.887a1 1 0 00-.364 1.118l1.518 4.674c.3.9-.755 1.688-1.538 1.118l-3.97-2.887a1 1 0 00-1.176 0l-3.97 2.887c-.783.57-1.838-.218-1.538-1.118l1.518-4.674a1 1 0 00-.364-1.118l-3.97-2.887c-.768-.574-.372-1.81.588-1.81h4.907a1 1 0 00.95-.69l1.518-4.674z"/>
                        </svg>
                    </div>
                </div>
                <!-- Card 4 -->
                <div class="emp-kpi-card">
                    <div class="emp-kpi-card-info">
                        <span class="emp-kpi-title">INTERVIEWS</span>
                        <span class="emp-kpi-num"><?= htmlspecialchars((string)($stats['interviews'] ?? 15)) ?></span>
                        <span class="emp-kpi-sub">Scheduled rounds</span>
                    </div>
                    <div class="emp-kpi-icon-wrap" style="background: #E0F2FE; color: #0284C7;">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                        </svg>
                    </div>
                </div>
                <!-- Card 5 -->
                <div class="emp-kpi-card">
                    <div class="emp-kpi-card-info">
                        <span class="emp-kpi-title">HIRED</span>
                        <span class="emp-kpi-num"><?= htmlspecialchars((string)($stats['hired'] ?? '08')) ?></span>
                        <span class="emp-kpi-sub">Offers accepted</span>
                    </div>
                    <div class="emp-kpi-icon-wrap" style="background: #D1FAE5; color: #059669;">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- ── Review Queue Card ── -->
            <div class="db-card" style="margin-bottom: 32px; padding: 24px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                    <div>
                        <h3 class="db-card-title" style="margin: 0; font-size: 16px; font-weight: 700; color: #0f172a; display: flex; align-items: center; gap: 8px;">
                            <svg class="h-5 w-5 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            <span>Review Queue</span>
                        </h3>
                    </div>
                    <a href="#" style="font-size: 13px; font-weight: 600; color: #4F47E6; text-decoration: none; hover:underline;">View All (12)</a>
                </div>

                <div class="db-table-responsive" style="overflow-x: auto;">
                    <table class="db-table" style="width: 100%; border-collapse: collapse; text-align: left;">
                        <thead>
                            <tr style="border-bottom: 1px solid #F1F5F9;">
                                <th style="padding: 12px 16px; font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase;">APPLICANT</th>
                                <th style="padding: 12px 16px; font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase;">ROLE</th>
                                <th style="padding: 12px 16px; font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase;">APPLIED DATE</th>
                                <th style="padding: 12px 16px; font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; text-align: right;">ACTIONS</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($reviewQueue as $applicant): ?>
                            <tr style="border-bottom: 1px solid #F8FAFC; transition: background 0.15s;" onmouseover="this.style.background='#FAFBFD'" onmouseout="this.style.background='transparent'">
                                <td class="td-applicant" style="padding: 16px; display: flex; align-items: center; gap: 12px;">
                                    <img src="<?= htmlspecialchars($applicant['avatar']) ?>" alt="<?= htmlspecialchars($applicant['name']) ?>" style="height: 36px; width: 36px; border-radius: 50%; object-fit: cover; flex-shrink: 0;">
                                    <span style="font-size: 14px; font-weight: 600; color: #0F172A;"><?= htmlspecialchars($applicant['name']) ?></span>
                                </td>
                                <td class="td-role" style="padding: 16px; font-size: 13px; color: #475569; font-weight: 500;">
                                    <div class="role-date-wrapper" style="display: flex; justify-content: space-between; align-items: center; width: 100%;">
                                        <span><?= htmlspecialchars($applicant['role']) ?></span>
                                        <span class="mobile-only-date" style="font-size: 11px; color: #64748b; font-weight: 500;"><?= htmlspecialchars($applicant['applied_date']) ?></span>
                                    </div>
                                </td>
                                <td class="td-date" style="padding: 16px; font-size: 13px; color: #64748b; font-weight: 500;"><?= htmlspecialchars($applicant['applied_date']) ?></td>
                                <td class="td-action" style="padding: 16px; text-align: right;">
                                    <a href="#" class="btn-review" style="background: #4F47E6; color: #fff; text-decoration: none; font-size: 12px; font-weight: 600; padding: 8px 16px; border-radius: 8px; transition: background 0.15s;" onmouseover="this.style.background='#4338ca'" onmouseout="this.style.background='#4F47E6'">Review Now</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- ── Bottom Grid (Performance, Candidates, Activity) ── -->
            <div style="display: grid; grid-template-columns: 2.4fr 1fr; gap: 32px; align-items: start; max-width: 100%;" class="grid-cols-mobile-stack">
                <style>
                    @media (max-width: 1024px) {
                        .grid-cols-mobile-stack {
                            grid-template-columns: 1fr !important;
                        }
                    }
                </style>

                <!-- Left Column -->
                <div style="display: flex; flex-direction: column; gap: 32px;">
                    <!-- Applications Performance Chart Card -->
                    <div class="db-card" style="padding: 24px;">
                        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 4px;">
                            <div>
                                <h3 class="db-card-title" style="margin: 0; font-size: 16px; font-weight: 700; color: #0f172a;">Applications Performance</h3>
                                <p style="font-size: 11px; color: #64748b; margin-top: 4px; margin-bottom: 0; font-weight: 500;">Application volume vs. Conversion rate by department</p>
                            </div>
                            
                            <!-- Applications Performance Dropdown (Matches Candidate Filter Dropdown UI) -->
                            <div style="position: relative;" x-data="{ open: false, selectedLabel: 'Last 30 Days', selectedValue: '30days' }" @click.outside="open = false">
                                <button class="db-filter-btn" type="button" @click="open = !open" style="display: flex; align-items: center; gap: 8px;">
                                    <span x-text="selectedLabel">Last 30 Days</span>
                                    <svg style="width: 12px; height: 12px; transition: transform 0.2s;" :class="{ 'rotate-180': open }" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                                </button>
                                <div x-show="open" style="display: none; position: absolute; top: calc(100% + 8px); right: 0; width: 160px; background: #fff; border: 1px solid #E8ECF2; border-radius: 10px; box-shadow: 0 8px 24px rgba(0,0,0,.08); z-index: 50; padding: 6px;">
                                    <div @click="selectedLabel = '3 Days'; selectedValue = '3days'; open = false" style="padding: 8px 12px; font-size: 13px; font-weight: 500; color: #191C1D; cursor: pointer; border-radius: 6px; transition: background 0.12s;" onmouseover="this.style.background='#F1F5F9'" onmouseout="this.style.background='transparent'">3 Days</div>
                                    <div @click="selectedLabel = '7 Days'; selectedValue = '7days'; open = false" style="padding: 8px 12px; font-size: 13px; font-weight: 500; color: #191C1D; cursor: pointer; border-radius: 6px; transition: background 0.12s;" onmouseover="this.style.background='#F1F5F9'" onmouseout="this.style.background='transparent'">7 Days</div>
                                    <div @click="selectedLabel = 'Last 30 Days'; selectedValue = '30days'; open = false" style="padding: 8px 12px; font-size: 13px; font-weight: 500; color: #191C1D; cursor: pointer; border-radius: 6px; transition: background 0.12s;" onmouseover="this.style.background='#F1F5F9'" onmouseout="this.style.background='transparent'">Last 30 Days</div>
                                    <div @click="selectedLabel = 'Last 6 Months'; selectedValue = '6months'; open = false" style="padding: 8px 12px; font-size: 13px; font-weight: 500; color: #191C1D; cursor: pointer; border-radius: 6px; transition: background 0.12s;" onmouseover="this.style.background='#F1F5F9'" onmouseout="this.style.background='transparent'">Last 6 Months</div>
                                    <div @click="selectedLabel = 'This Year'; selectedValue = 'year'; open = false" style="padding: 8px 12px; font-size: 13px; font-weight: 500; color: #191C1D; cursor: pointer; border-radius: 6px; transition: background 0.12s;" onmouseover="this.style.background='#F1F5F9'" onmouseout="this.style.background='transparent'">This Year</div>
                                    <div @click="selectedLabel = '2025'; selectedValue = '2025'; open = false" style="padding: 8px 12px; font-size: 13px; font-weight: 500; color: #191C1D; cursor: pointer; border-radius: 6px; transition: background 0.12s;" onmouseover="this.style.background='#F1F5F9'" onmouseout="this.style.background='transparent'">2025</div>
                                </div>
                            </div>
                        </div>

                        <!-- Chart Legends -->
                        <div class="chart-legend-wrap" style="margin-top: 16px;">
                            <span class="chart-legend-item">
                                <span class="chart-legend-dot" style="background: #4F47E6;"></span>
                                Goal
                            </span>
                            <span class="chart-legend-item">
                                <span class="chart-legend-dot" style="background: #C7D2FE;"></span>
                                Actual
                            </span>
                        </div>

                        <!-- Bar Chart Representation -->
                        <div class="emp-chart-container">
                            <!-- UX Design -->
                            <div class="emp-chart-col">
                                <div class="emp-chart-tooltip">UX Design - Goal: 90%, Actual: 75%</div>
                                <div class="emp-chart-bars">
                                    <div class="emp-chart-bar-goal" style="height: 90%;" title="UX Design Goal: 90%"></div>
                                    <div class="emp-chart-bar-actual" style="height: 75%;" title="UX Design Actual: 75%"></div>
                                </div>
                                <span class="emp-chart-label">UX Design</span>
                            </div>
                            <!-- Frontend -->
                            <div class="emp-chart-col">
                                <div class="emp-chart-tooltip">Frontend - Goal: 60%, Actual: 95%</div>
                                <div class="emp-chart-bars">
                                    <div class="emp-chart-bar-goal" style="height: 60%;" title="Frontend Goal: 60%"></div>
                                    <div class="emp-chart-bar-actual" style="height: 95%;" title="Frontend Actual: 95%"></div>
                                </div>
                                <span class="emp-chart-label">Frontend</span>
                            </div>
                            <!-- Backend -->
                            <div class="emp-chart-col">
                                <div class="emp-chart-tooltip">Backend - Goal: 78%, Actual: 62%</div>
                                <div class="emp-chart-bars">
                                    <div class="emp-chart-bar-goal" style="height: 78%;" title="Backend Goal: 78%"></div>
                                    <div class="emp-chart-bar-actual" style="height: 62%;" title="Backend Actual: 62%"></div>
                                </div>
                                <span class="emp-chart-label">Backend</span>
                            </div>
                            <!-- Data Science -->
                            <div class="emp-chart-col">
                                <div class="emp-chart-tooltip">Data Science - Goal: 95%, Actual: 80%</div>
                                <div class="emp-chart-bars">
                                    <div class="emp-chart-bar-goal" style="height: 95%;" title="Data Science Goal: 95%"></div>
                                    <div class="emp-chart-bar-actual" style="height: 80%;" title="Data Science Actual: 80%"></div>
                                </div>
                                <span class="emp-chart-label">Data Sci</span>
                            </div>
                            <!-- Digital Marketing -->
                            <div class="emp-chart-col">
                                <div class="emp-chart-tooltip">Marketing - Goal: 70%, Actual: 48%</div>
                                <div class="emp-chart-bars">
                                    <div class="emp-chart-bar-goal" style="height: 70%;" title="Digital Marketing Goal: 70%"></div>
                                    <div class="emp-chart-bar-actual" style="height: 48%;" title="Digital Marketing Actual: 48%"></div>
                                </div>
                                <span class="emp-chart-label">Marketing</span>
                            </div>
                            <!-- Web Dev -->
                            <div class="emp-chart-col">
                                <div class="emp-chart-tooltip">Web Dev - Goal: 40%, Actual: 65%</div>
                                <div class="emp-chart-bars">
                                    <div class="emp-chart-bar-goal" style="height: 40%;" title="Web Dev Goal: 40%"></div>
                                    <div class="emp-chart-bar-actual" style="height: 65%;" title="Web Dev Actual: 65%"></div>
                                </div>
                                <span class="emp-chart-label">Web Dev</span>
                            </div>
                        </div>
                    </div>

                    <!-- AI Recommended Candidates Card -->
                    <div class="db-card" style="padding: 24px;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                            <h3 class="db-card-title" style="margin: 0; font-size: 16px; font-weight: 700; color: #0f172a;">AI Recommended Candidates</h3>
                            <a href="#" style="font-size: 13px; font-weight: 600; color: #4F47E6; text-decoration: none;">Explore All Talent</a>
                        </div>

                        <!-- Recommendations list -->
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px;">
                            <?php foreach ($recommendations as $candidate): ?>
                            <?php 
                                $isAvailable = (stripos($candidate['status'], 'available') !== false); 
                                $badgeClass = $isAvailable ? 'cand-status-available' : 'cand-status-interviewed';
                                $dotClass = $isAvailable ? 'status-dot-available' : 'status-dot-interviewed';
                            ?>
                            <div style="border: 1px solid #F1F5F9; border-radius: 16px; padding: 20px; display: flex; flex-direction: column; justify-content: space-between; gap: 16px; transition: all 0.2s;" onmouseover="this.style.borderColor='#E2E8F0'; this.style.boxShadow='0 4px 6px -1px rgba(0,0,0,0.02)';" onmouseout="this.style.borderColor='#F1F5F9'; this.style.boxShadow='none';">
                                <!-- Top Info -->
                                <div class="cand-card-header">
                                    <div class="cand-profile-block">
                                        <img src="<?= htmlspecialchars($candidate['avatar']) ?>" alt="<?= htmlspecialchars($candidate['name']) ?>" style="height: 48px; width: 48px; border-radius: 50%; object-fit: cover;">
                                        <div>
                                            <h4 style="font-size: 14px; font-weight: 700; color: #0F172A; margin: 0;"><?= htmlspecialchars($candidate['name']) ?></h4>
                                            <p style="font-size: 12px; color: #64748b; margin-top: 2px; font-weight: 500;"><?= htmlspecialchars($candidate['role']) ?></p>
                                        </div>
                                    </div>
                                    <span class="cand-match-badge"><?= htmlspecialchars($candidate['match']) ?></span>
                                </div>

                                <!-- Description -->
                                <p style="font-size: 12px; color: #475569; line-height: 1.6; font-style: italic; margin: 0;">
                                    <?= htmlspecialchars($candidate['bio']) ?>
                                </p>

                                <!-- Skills Tags -->
                                <div style="display: flex; flex-wrap: wrap; gap: 8px;">
                                    <?php foreach ($candidate['tags'] as $tag): ?>
                                    <span style="font-size: 11px; font-weight: 600; color: #4F47E6; background: #EEF2FF; padding: 4px 10px; border-radius: 8px;"><?= htmlspecialchars($tag) ?></span>
                                    <?php endforeach; ?>
                                </div>

                                <!-- Action Buttons row -->
                                <div style="display: flex; justify-content: space-between; align-items: center; border-top: 1px solid #F1F5F9; padding-top: 12px; margin-top: 8px;">
                                    <span class="cand-status-badge <?= $badgeClass ?>">
                                        <span class="status-dot <?= $dotClass ?>"></span>
                                        <?= htmlspecialchars($candidate['status']) ?>
                                    </span>
                                    <a href="#" style="background: #4F47E6; color: #fff; text-decoration: none; font-size: 11px; font-weight: 600; padding: 8px 16px; border-radius: 8px; transition: background 0.15s;" onmouseover="this.style.background='#4338ca'" onmouseout="this.style.background='#4F47E6'">View Profile</a>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div style="display: flex; flex-direction: column; gap: 32px;">
                    <!-- Upcoming Interviews Card -->
                    <div class="db-card" style="padding: 24px;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                            <h3 style="margin: 0; font-size: 12px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em;">Upcoming Interviews</h3>
                            <span style="display: inline-flex; align-items: center; gap: 4px; background: #FEE2E2; color: #EF4444; font-size: 9px; font-weight: 700; text-transform: uppercase; padding: 2px 8px; border-radius: 99px;">
                                <span style="height: 4px; width: 4px; background: #EF4444; border-radius: 50%; display: inline-block;" class="animate-pulse"></span>
                                Live
                            </span>
                        </div>

                        <!-- Schedule List -->
                        <div style="display: flex; flex-direction: column; gap: 16px;">
                            <?php foreach ($interviews as $interview): ?>
                            <div style="border: 1px solid #F1F5F9; border-radius: 16px; padding: 16px; display: flex; flex-direction: column; gap: 12px;">
                                <div style="display: flex; justify-content: space-between; items-start; gap: 8px;">
                                    <div>
                                        <h4 style="font-size: 13px; font-weight: 700; color: #0F172A; margin: 0;"><?= htmlspecialchars($interview['candidate']) ?></h4>
                                        <p style="font-size: 11px; color: #64748b; margin-top: 2px; font-weight: 500;"><?= htmlspecialchars($interview['role']) ?></p>
                                    </div>
                                    <div style="text-align: right;">
                                        <span style="font-size: 11px; font-weight: 700; color: #4F47E6; display: block;"><?= htmlspecialchars($interview['day']) ?></span>
                                        <span style="font-size: 11px; color: #64748b; font-weight: 500; display: block; margin-top: 2px;"><?= htmlspecialchars($interview['time']) ?></span>
                                    </div>
                                </div>
                                <?php if ($interview['live']): ?>
                                <a href="#" style="background: #4F47E6; color: #fff; text-decoration: none; font-size: 12px; font-weight: 600; padding: 10px; border-radius: 10px; text-align: center; display: flex; align-items: center; justify-content: center; gap: 8px; box-shadow: 0 4px 10px rgba(79, 70, 230, 0.15); transition: background 0.15s;" onmouseover="this.style.background='#4338ca'" onmouseout="this.style.background='#4F47E6'">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                    <span>Join Interview</span>
                                </a>
                                <?php endif; ?>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="db-card" style="padding: 24px; display: flex; flex-direction: column; justify-content: space-between;">
                        <div>
                            <h3 class="db-card-title" style="margin-bottom: 20px; font-size: 16px; font-weight: 700; color: #0f172a; flex-shrink: 0;">Recent Activity</h3>

                            <div class="activity-flex-container">
                                <?php foreach ($activity as $act): ?>
                                <div style="display: flex; gap: 12px; align-items: start;">
                                    <div style="height: 32px; width: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0;
                                        <?php
                                        switch ($act['type']) {
                                            case 'candidate':
                                                echo 'background: #EEF2FF; color: #4F47E6;';
                                                $icon = '<svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>';
                                                break;
                                            case 'interview':
                                                echo 'background: #FEF3C7; color: #D97706;';
                                                $icon = '<svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>';
                                                break;
                                            case 'message':
                                                echo 'background: #E0F2FE; color: #0284C7;';
                                                $icon = '<svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>';
                                                break;
                                            case 'position':
                                            default:
                                                echo 'background: #D1FAE5; color: #059669;';
                                                $icon = '<svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>';
                                                break;
                                        }
                                        ?>
                                    ">
                                        <?= $icon ?>
                                    </div>
                                    <div style="display: flex; flex-direction: column;">
                                        <span style="font-size: 13px; font-weight: 700; color: #0f172a; line-height: 1.4;"><?= htmlspecialchars($act['title']) ?></span>
                                        <span style="font-size: 11px; color: #64748b; margin-top: 2px; font-weight: 500;"><?= htmlspecialchars($act['desc']) ?></span>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <a href="#" style="display: block; font-size: 12px; font-weight: 700; color: #4F47E6; text-decoration: none; text-align: center; margin-top: 24px; padding-top: 16px; border-top: 1px solid #F1F5F9; hover:underline; flex-shrink: 0;">View All Notifications</a>
                    </div>
                </div>
            </div>

        </div><!-- /.db-content -->

        <!-- Dashboard Footer -->
        <?php include __DIR__ . '/../../shared/components/db-footer.php'; ?>

        <!-- Mobile Sidebar Toggle Script -->
        <script>
        (function() {
            var hamburger = document.getElementById('db-hamburger');
            var sidebar   = document.querySelector('.db-sidebar');
            var overlay   = document.getElementById('db-sidebar-overlay');
            if (!hamburger || !sidebar || !overlay) return;
            function toggleSidebar() {
                sidebar.classList.toggle('is-open');
                overlay.classList.toggle('is-open');
            }
            hamburger.addEventListener('click', toggleSidebar);
            overlay.addEventListener('click', toggleSidebar);
        })();
        </script>
    </div><!-- /.db-main -->
</div><!-- /.db-shell -->
