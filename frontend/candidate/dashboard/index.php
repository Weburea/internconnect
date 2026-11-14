<?php
declare(strict_types=1);
/**
 * View: Candidate Dashboard
 * Route: /dashboard
 * Layout: dashboard (custom sidebar layout — no global navbar/footer)
 * @var array  $candidate  Candidate profile data
 * @var array  $stats      KPI statistics
 * @var array  $recommendations Recommended internships
 * @var array  $applications   Recent applications
 * @var array  $alerts         Recent alerts/notifications
 */

$name      = $candidate['name'] ?? 'Sarah Peters';
$firstName = explode(' ', trim($name))[0];
$role      = $candidate['role'] ?? 'Student Applicant';
$avatar    = $candidate['avatar'] ?? null;
?>
<div class="db-shell">

    <!-- Sidebar Overlay (mobile) -->
    <div class="db-sidebar-overlay" id="db-sidebar-overlay"></div>    <!-- ══ SIDEBAR ══ -->
    <?php 
    $activeNav = 'dashboard';
    include dirname(__DIR__, 2) . '/shared/components/candidate-sidebar.php'; 
    ?>

    <!-- ══ MAIN ══ -->
    <div class="db-main">

        <!-- ══ TOP NAVBAR ══ -->
        <?php include dirname(__DIR__, 2) . '/shared/components/candidate-topnav.php'; ?>

        <!-- Scrollable Content -->
        <div class="db-content" id="db-content">

            <!-- ── Welcome Banner ── -->
            <section class="db-banner" aria-labelledby="db-banner-heading">
                <h1 id="db-banner-heading">Welcome back, <?= htmlspecialchars($firstName) ?></h1>
                <p>Track your internship applications and discover new opportunities designed for your skill set.</p>
            </section>

            <!-- ── KPI Stats ── -->
            <div class="db-stats-row">
                <?php foreach ($stats as $stat): ?>
                <div class="db-stat-card">
                    <!-- Subtle background curve shape -->
                    <div style="position: absolute; top: -10px; right: -10px; width: 80px; height: 80px; background: #F8FAFC; border-radius: 50%; z-index: 1;"></div>
                    
                    <div class="db-stat-top">
                        <div class="db-stat-icon <?= $stat['color'] ?>">
                            <?= $stat['icon'] ?>
                        </div>
                        <span class="db-stat-badge <?= $stat['badge_type'] ?>"><?= $stat['badge'] ?></span>
                    </div>
                    <div class="db-stat-label"><?= $stat['label'] ?></div>
                    <div class="db-stat-val"><?= str_pad((string)$stat['value'], 2, '0', STR_PAD_LEFT) ?></div>
                    
                    <?php if ($stat['color'] === 'blue'): ?>
                        <div style="display: flex; height: 3px; background: #F1F5F9; border-radius: 4px; margin-bottom: 12px; width: 100%;">
                            <div style="width: 60%; background: #4F47E6; border-radius: 4px;"></div>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($stat['desc'])): ?>
                        <div class="db-stat-desc"><?= $stat['desc'] ?></div>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- ── Middle Row: Your Application Pipeline & Profile / Pro Career Insights ── -->
            <div class="db-mid-row" x-data="{ 
                isProfileComplete: <?= ($candidate['profile_pct'] ?? 75) >= 100 ? 'true' : 'false' ?>, 
                isPro: <?= !empty($candidate['is_pro']) ? 'true' : 'false' ?> 
            }">
                <style>
                /* ── Pipeline Card Styling ── */
                .cand-pipeline-card {
                    background: linear-gradient(135deg, #3730A3 0%, #4338CA 55%, #4F48E6 100%);
                    border-radius: 20px;
                    padding: 28px 30px;
                    color: #FFFFFF;
                    display: flex;
                    flex-direction: column;
                    justify-content: space-between;
                    box-shadow: 0 10px 25px -4px rgba(67, 56, 202, 0.28);
                    position: relative;
                    overflow: hidden;
                    min-width: 0;
                    box-sizing: border-box;
                }
                .cand-pipeline-top {
                    display: flex;
                    align-items: flex-start;
                    justify-content: space-between;
                    gap: 16px;
                    margin-bottom: 24px;
                }
                .cand-pipeline-title {
                    font-size: 22px;
                    font-weight: 800;
                    color: #FFFFFF;
                    margin: 0 0 6px;
                    letter-spacing: -0.01em;
                    line-height: 1.25;
                }
                .cand-pipeline-sub {
                    font-size: 13.5px;
                    color: rgba(255, 255, 255, 0.88);
                    margin: 0;
                    line-height: 1.5;
                    max-width: 500px;
                }
                .cand-pipeline-badge {
                    display: inline-flex;
                    align-items: center;
                    gap: 6px;
                    background: rgba(255, 255, 255, 0.16);
                    border: 1px solid rgba(255, 255, 255, 0.28);
                    border-radius: 9999px;
                    padding: 5px 12px;
                    font-size: 11.5px;
                    font-weight: 700;
                    color: #FFFFFF;
                    white-space: nowrap !important;
                    flex-shrink: 0 !important;
                    backdrop-filter: blur(6px);
                }
                .cand-pipeline-bars {
                    display: flex;
                    flex-direction: column;
                    gap: 16px;
                    margin-bottom: 26px;
                }
                .cand-pipeline-row {
                    display: flex;
                    align-items: center;
                    gap: 16px;
                    min-width: 0;
                }
                .cand-pipeline-label {
                    width: 105px;
                    font-size: 14px;
                    font-weight: 700;
                    color: #FFFFFF;
                    flex-shrink: 0;
                }
                .cand-pipeline-bar-col {
                    flex: 1;
                    min-width: 0;
                    display: flex;
                    flex-direction: column;
                }
                .cand-pipeline-track {
                    width: 100%;
                    height: 32px;
                    background: rgba(255, 255, 255, 0.18);
                    border-radius: 9px;
                    overflow: hidden;
                    display: flex;
                }
                .cand-pipeline-track.is-full {
                    background: #FFFFFF;
                }
                .cand-pipeline-fill.is-white {
                    background: #FFFFFF;
                    height: 100%;
                    border-radius: 9px;
                }
                .cand-pipeline-fill.is-lavender {
                    background: rgba(255, 255, 255, 0.62);
                    height: 100%;
                    border-radius: 9px;
                    transition: width 0.4s ease;
                }
                .cand-pipeline-meta {
                    font-size: 11px;
                    color: rgba(255, 255, 255, 0.78);
                    text-align: right;
                    margin-top: 4px;
                    font-weight: 500;
                }
                .cand-pipeline-val {
                    width: 32px;
                    text-align: right;
                    font-size: 19px;
                    font-weight: 800;
                    color: #FFFFFF;
                    flex-shrink: 0;
                }
                .cand-pipeline-actions {
                    display: flex;
                    align-items: center;
                    gap: 12px;
                    flex-wrap: wrap;
                }
                .cand-pipeline-btn-primary {
                    background: #FFFFFF;
                    color: #4338CA !important;
                    font-size: 13.5px;
                    font-weight: 700;
                    padding: 10px 20px;
                    border-radius: 12px;
                    text-decoration: none;
                    display: inline-flex;
                    align-items: center;
                    gap: 8px;
                    white-space: nowrap !important;
                    flex-shrink: 0 !important;
                    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
                    transition: transform 0.15s ease, background 0.15s ease;
                }
                .cand-pipeline-btn-primary:hover {
                    background: #F8FAFC;
                    transform: translateY(-1px);
                }
                .cand-pipeline-btn-secondary {
                    background: rgba(255, 255, 255, 0.12);
                    color: #FFFFFF !important;
                    border: 1px solid rgba(255, 255, 255, 0.28);
                    font-size: 13.5px;
                    font-weight: 700;
                    padding: 10px 20px;
                    border-radius: 12px;
                    text-decoration: none;
                    display: inline-flex;
                    align-items: center;
                    gap: 8px;
                    white-space: nowrap !important;
                    flex-shrink: 0 !important;
                    transition: background 0.15s ease;
                }
                .cand-pipeline-btn-secondary:hover {
                    background: rgba(255, 255, 255, 0.2);
                }

                /* ── Pro Career Insights Card ── */
                .db-mid-row {
                    display: grid !important;
                    grid-template-columns: minmax(0, 1.45fr) minmax(320px, 1fr) !important;
                    gap: 20px !important;
                    align-items: stretch;
                }
                .cand-insights-card {
                    background: #FFFFFF;
                    border: 1px solid #E2E8F0;
                    border-radius: 20px;
                    padding: 22px 20px;
                    display: flex;
                    flex-direction: column;
                    justify-content: space-between;
                    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
                    position: relative;
                    min-width: 0;
                    box-sizing: border-box;
                    overflow: hidden;
                }
                .cand-insights-header {
                    margin-bottom: 20px;
                }
                .cand-insights-top {
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                    gap: 8px;
                    margin-bottom: 6px;
                    width: 100%;
                    box-sizing: border-box;
                }
                .cand-insights-title {
                    font-size: 14.5px !important;
                    font-weight: 800 !important;
                    color: #000623 !important;
                    margin: 0 !important;
                    letter-spacing: -0.01em !important;
                    white-space: nowrap !important;
                    line-height: 1.2 !important;
                }
                .cand-insights-sub {
                    font-size: 12px;
                    color: #64748B;
                    line-height: 1.45;
                    margin: 0;
                }
                .cand-pro-pill {
                    display: inline-flex !important;
                    align-items: center !important;
                    gap: 5px !important;
                    padding: 3px 8px !important;
                    border-radius: 9999px !important;
                    font-size: 9.5px !important;
                    font-weight: 800 !important;
                    letter-spacing: 0.03em !important;
                    white-space: nowrap !important;
                    flex-shrink: 0 !important;
                    cursor: pointer !important;
                    transition: all 0.15s ease !important;
                    border: 1px solid !important;
                    line-height: 1.1 !important;
                    text-transform: uppercase !important;
                    outline: none !important;
                }
                .cand-pro-pill.is-pro {
                    background: #EEF2FF !important;
                    border-color: #C7D2FE !important;
                    color: #4F46E5 !important;
                }
                .cand-pro-pill.is-pro:hover {
                    background: #E0E7FF !important;
                    border-color: #A5B4FC !important;
                }
                .cand-pro-pill.is-free {
                    background: #F8FAFC !important;
                    border-color: #E2E8F0 !important;
                    color: #64748B !important;
                }
                .cand-pro-pill.is-free:hover {
                    background: #F1F5F9 !important;
                    border-color: #CBD5E1 !important;
                }
                .cand-pro-dot {
                    width: 5px;
                    height: 5px;
                    border-radius: 50%;
                    flex-shrink: 0;
                    display: inline-block;
                }
                .cand-pro-pill.is-pro .cand-pro-dot {
                    background: #4F46E5;
                }
                .cand-pro-pill.is-free .cand-pro-dot {
                    background: #94A3B8;
                }
                .cand-metric-item {
                    margin-bottom: 14px;
                }
                .cand-metric-head {
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                    font-size: 13px;
                    font-weight: 600;
                    color: #1E293B;
                    margin-bottom: 6px;
                }
                .cand-metric-val {
                    font-size: 13px;
                    font-weight: 800;
                }
                .cand-metric-val.blue   { color: #4338CA; }
                .cand-metric-val.green  { color: #059669; }
                .cand-metric-val.orange { color: #EA580C; }

                .cand-metric-track {
                    width: 100%;
                    height: 7px;
                    background: #F1F5F9;
                    border-radius: 9999px;
                    overflow: hidden;
                }
                .cand-metric-bar {
                    height: 100%;
                    border-radius: 9999px;
                    transition: width 0.6s ease;
                }
                .cand-metric-bar.blue   { background: #4338CA; }
                .cand-metric-bar.green  { background: #10B981; }
                .cand-metric-bar.orange { background: #F97316; }

                /* Pro Locked Overlay */
                .cand-pro-gated-wrap {
                    position: relative;
                }
                .cand-pro-blurred {
                    filter: blur(4px);
                    opacity: 0.55;
                    pointer-events: none;
                    user-select: none;
                }
                .cand-pro-overlay {
                    position: absolute;
                    top: 0;
                    left: 0;
                    right: 0;
                    bottom: 0;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    z-index: 10;
                    padding: 8px;
                }
                .cand-upgrade-btn {
                    display: inline-flex !important;
                    align-items: center !important;
                    justify-content: center !important;
                    gap: 8px !important;
                    background: #EEF2FF !important;
                    color: #4338CA !important;
                    border: 1.5px solid #C7D2FE !important;
                    border-radius: 12px !important;
                    padding: 10px 20px !important;
                    font-size: 13.5px !important;
                    font-weight: 700 !important;
                    text-decoration: none !important;
                    box-shadow: 0 4px 16px rgba(67, 56, 202, 0.12) !important;
                    white-space: nowrap !important;
                    flex-shrink: 0 !important;
                    transition: all 0.2s ease !important;
                }
                .cand-upgrade-btn:hover {
                    background: #E0E7FF !important;
                    border-color: #A5B4FC !important;
                    transform: translateY(-1px);
                }

                .cand-insights-link {
                    display: inline-flex;
                    align-items: center;
                    gap: 6px;
                    color: #4338CA;
                    font-size: 13px;
                    font-weight: 700;
                    text-decoration: none;
                    margin-top: 12px;
                    transition: color 0.15s ease;
                }
                .cand-insights-link:hover {
                    color: #3730A3;
                }

                /* ─── AI Match Section Styles (Homepage Featured Opportunities Standard) ─── */
                .ai-match-section {
                    margin-top: 36px;
                    width: 100%;
                    box-sizing: border-box;
                }
                .ai-match-header-wrap {
                    margin-bottom: 22px;
                    display: flex;
                    align-items: flex-end;
                    justify-content: space-between;
                    flex-wrap: wrap;
                    gap: 16px;
                }
                .ai-match-eyebrow {
                    display: inline-flex;
                    align-items: center;
                    gap: 6px;
                    font-size: 11px;
                    font-weight: 700;
                    color: #4F48E6;
                    background: #EEF2FF;
                    border: 1px solid #C7D2FE;
                    border-radius: 9999px;
                    padding: 3px 10px;
                    margin-bottom: 6px;
                    text-transform: uppercase;
                    letter-spacing: 0.05em;
                    white-space: nowrap !important;
                    flex-shrink: 0 !important;
                }
                .ai-match-title {
                    font-size: 20px;
                    font-weight: 800;
                    color: #000623;
                    margin: 0;
                    letter-spacing: -0.01em;
                }
                .ai-match-view-all {
                    font-size: 13.5px;
                    font-weight: 700;
                    color: #4F48E6;
                    text-decoration: none;
                    display: inline-flex;
                    align-items: center;
                    gap: 6px;
                    white-space: nowrap !important;
                    flex-shrink: 0 !important;
                    transition: color 0.15s ease;
                }
                .ai-match-view-all:hover {
                    color: #3730A3;
                }

                /* 3-Column Responsive Grid */
                .ai-opps-cards-grid {
                    display: grid;
                    grid-template-columns: minmax(0, 1fr) !important;
                    gap: 24px;
                    width: 100% !important;
                    max-width: 100% !important;
                    min-width: 0 !important;
                    box-sizing: border-box !important;
                }
                @media (min-width: 640px) {
                    .ai-opps-cards-grid {
                        grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
                        gap: 20px;
                    }
                }
                @media (min-width: 1100px) {
                    .ai-opps-cards-grid {
                        grid-template-columns: repeat(3, minmax(0, 1fr)) !important;
                        gap: 24px;
                    }
                }

                /* Card Container */
                .cand-ai-card {
                    background: #FFFFFF;
                    border: 1px solid #E2E8F0;
                    border-radius: 20px;
                    padding: 24px;
                    box-shadow: 0 4px 20px -2px rgba(0, 6, 35, 0.05), 0 2px 6px -1px rgba(0, 6, 35, 0.02);
                    display: flex;
                    flex-direction: column;
                    justify-content: space-between;
                    transition: transform 0.25s cubic-bezier(0.4, 0, 0.2, 1), box-shadow 0.25s ease, border-color 0.25s ease;
                    box-sizing: border-box;
                    width: 100%;
                    min-width: 0;
                }
                .cand-ai-card:hover {
                    transform: translateY(-3px);
                    border-color: #CBD5E1;
                    box-shadow: 0 16px 36px -4px rgba(0, 6, 35, 0.09), 0 4px 12px -2px rgba(0, 6, 35, 0.03);
                }
                .cand-ai-card-top {
                    display: flex;
                    align-items: flex-start;
                    justify-content: space-between;
                    gap: 12px;
                }
                .cand-ai-logo-box {
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
                    transition: transform 0.2s ease;
                }
                .cand-ai-card:hover .cand-ai-logo-box {
                    transform: scale(1.04);
                }
                .cand-ai-logo-img {
                    width: 32px !important;
                    height: 32px !important;
                    max-width: 32px !important;
                    max-height: 32px !important;
                    object-fit: contain !important;
                    display: block !important;
                    flex-shrink: 0 !important;
                }
                .cand-ai-type-badge {
                    display: inline-flex;
                    align-items: center;
                    padding: 4px 12px;
                    border-radius: 9999px;
                    font-size: 11.5px;
                    font-weight: 700;
                    letter-spacing: 0.02em;
                    white-space: nowrap !important;
                    flex-shrink: 0 !important;
                }
                .cand-ai-badge-fulltime {
                    background-color: #ECFDF5;
                    color: #059669;
                    border: 1px solid #D1FAE5;
                }
                .cand-ai-badge-parttime {
                    background-color: #EEF2FF;
                    color: #4F48E6;
                    border: 1px solid #E0E7FF;
                }
                .cand-ai-badge-remote {
                    background-color: #F5F3FF;
                    color: #7C3AED;
                    border: 1px solid #EDE9FE;
                }
                .cand-ai-badge-hybrid {
                    background-color: #EFF6FF;
                    color: #2563EB;
                    border: 1px solid #DBEAFE;
                }
                .cand-ai-role-title {
                    font-size: 18.5px;
                    font-weight: 700;
                    color: #000623;
                    line-height: 1.3;
                    margin: 14px 0 4px 0;
                    transition: color 0.15s ease;
                }
                .cand-ai-card:hover .cand-ai-role-title {
                    color: #4F48E6;
                }
                .cand-ai-role-company {
                    font-size: 13.5px;
                    font-weight: 500;
                    color: #64748B;
                    margin: 0 0 14px 0;
                    display: flex;
                    align-items: center;
                    gap: 6px;
                }
                .cand-ai-desc-text {
                    font-size: 13.5px;
                    line-height: 1.58;
                    color: #475569;
                    margin: 0 0 18px 0;
                    font-weight: 400;
                    display: -webkit-box;
                    -webkit-line-clamp: 2;
                    -webkit-box-orient: vertical;
                    overflow: hidden;
                }
                .cand-ai-stats-row {
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
                    overflow: hidden;
                }
                .cand-ai-stat-item {
                    display: flex;
                    align-items: center;
                    gap: 4px;
                    font-size: 11px;
                    font-weight: 500;
                    color: #475569;
                    white-space: nowrap !important;
                    min-width: 0;
                    flex-shrink: 0 !important;
                }
                .cand-ai-stat-item.stat-stipend {
                    font-weight: 700;
                    color: #000623;
                }
                .cand-ai-stat-icon {
                    width: 13px !important;
                    height: 13px !important;
                    color: #4F48E6;
                    flex-shrink: 0 !important;
                }
                .cand-ai-stat-divider {
                    width: 1px;
                    height: 14px;
                    background: #E2E8F0;
                    flex-shrink: 0;
                }
                .cand-ai-card-btn {
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
                    box-shadow: none !important;
                    box-sizing: border-box !important;
                }
                .cand-ai-card-btn:hover {
                    background-color: #E0E7FF !important;
                    border-color: #A5B4FC !important;
                    color: #3730A3 !important;
                    transform: translateY(-1px);
                }

                @media (max-width: 1024px) {
                    .db-mid-row {
                        grid-template-columns: minmax(0, 1fr) !important;
                    }
                }
                @media (max-width: 640px) {
                    .cand-pipeline-card {
                        padding: 22px 18px;
                    }
                    .cand-pipeline-top {
                        flex-direction: column;
                        align-items: flex-start;
                        gap: 10px;
                    }
                    .cand-pipeline-title {
                        font-size: 20px;
                    }
                    .cand-pipeline-row {
                        gap: 10px;
                    }
                    .cand-pipeline-label {
                        width: 85px;
                        font-size: 12.5px;
                    }
                    .cand-pipeline-track {
                        height: 28px;
                    }
                    .cand-pipeline-actions {
                        flex-direction: column;
                        width: 100%;
                    }
                    .cand-pipeline-btn-primary,
                    .cand-pipeline-btn-secondary {
                        width: 100%;
                        justify-content: center;
                        box-sizing: border-box;
                    }
                }
                </style>

                <!-- 1. Left Card: Your Application Pipeline -->
                <div class="cand-pipeline-card">
                    <div>
                        <div class="cand-pipeline-top">
                            <div>
                                <h2 class="cand-pipeline-title">Your Application Pipeline</h2>
                                <p class="cand-pipeline-sub">You're making steady progress. Applications are converting to interviews 18% better than last month.</p>
                            </div>
                            <span class="cand-pipeline-badge">
                                <svg style="width: 14px; height: 14px; flex-shrink: 0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                                </svg>
                                <span>+18% vs last month</span>
                            </span>
                        </div>

                        <!-- Pipeline Progress Bars -->
                        <div class="cand-pipeline-bars">
                            <!-- Row 1: Applications -->
                            <div class="cand-pipeline-row">
                                <div class="cand-pipeline-label">Applications</div>
                                <div class="cand-pipeline-bar-col">
                                    <div class="cand-pipeline-track is-full">
                                        <div class="cand-pipeline-fill is-white" style="width: 100%;"></div>
                                    </div>
                                    <div class="cand-pipeline-meta">100% of pipeline</div>
                                </div>
                                <div class="cand-pipeline-val">15</div>
                            </div>

                            <!-- Row 2: Accepted -->
                            <div class="cand-pipeline-row">
                                <div class="cand-pipeline-label">Accepted</div>
                                <div class="cand-pipeline-bar-col">
                                    <div class="cand-pipeline-track">
                                        <div class="cand-pipeline-fill is-lavender" style="width: 53%;"></div>
                                    </div>
                                    <div class="cand-pipeline-meta">33% of pipeline</div>
                                </div>
                                <div class="cand-pipeline-val">8</div>
                            </div>

                            <!-- Row 3: Interviews -->
                            <div class="cand-pipeline-row">
                                <div class="cand-pipeline-label">Interviews</div>
                                <div class="cand-pipeline-bar-col">
                                    <div class="cand-pipeline-track">
                                        <div class="cand-pipeline-fill is-lavender" style="width: 40%;"></div>
                                    </div>
                                    <div class="cand-pipeline-meta">25% of pipeline</div>
                                </div>
                                <div class="cand-pipeline-val">6</div>
                            </div>
                        </div>
                    </div>

                    <!-- Bottom Action Buttons -->
                    <div class="cand-pipeline-actions">
                        <a href="#ai-recommended-roles" class="cand-pipeline-btn-primary">
                            <span>Explore AI Recommendations</span>
                            <svg style="width: 14px; height: 14px; flex-shrink: 0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </a>
                        <a href="<?= url('/candidate/applications') ?>" class="cand-pipeline-btn-secondary">
                            <span>View All Applications</span>
                        </a>
                    </div>
                </div>

                <!-- 2. Right Card: Conditional Render (Complete Profile vs Your Pro Career Insights) -->
                <div>
                    <!-- State A: Complete Profile (Rendered when profile is not completed) -->
                    <div class="db-card db-profile-card" x-show="!isProfileComplete">
                        <div class="db-card-header">
                            <h3 class="db-card-title">Complete Profile</h3>
                            <button type="button" @click="isProfileComplete = true" aria-label="Preview completed profile with Career Insights" style="background: none; border: none; font-size: 11px; color: #4F48E6; cursor: pointer; text-decoration: underline; padding: 0;">
                                Test 100%
                            </button>
                        </div>
                        <div class="db-progress-bar-wrap">
                            <div class="db-progress-label">
                                <span>Profile Completion</span>
                                <span class="db-progress-pct">75%</span>
                            </div>
                            <div class="db-progress-track">
                                <div class="db-progress-fill" style="width: 75%;"></div>
                            </div>
                        </div>
                        <div class="db-checklist">
                            <div class="db-check-item">
                                <div class="db-check-dot done">
                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                </div>
                                <span>Upload Resume</span>
                            </div>
                            <div class="db-check-item">
                                <div class="db-check-dot done">
                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                </div>
                                <span>Technical Skills</span>
                            </div>
                            <div class="db-check-item">
                                <div class="db-check-dot undone">
                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/></svg>
                                </div>
                                <span>Education Details</span>
                            </div>
                            <div class="db-check-item">
                                <div class="db-check-dot done">
                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                </div>
                                <span>Experience History</span>
                            </div>
                        </div>
                        <a href="<?= url('/profile') ?>" class="db-update-btn" id="db-update-profile-btn">Update Profile</a>
                    </div>

                    <!-- State B: Your Pro Career Insights (Replaces Complete Profile when profile is completed) -->
                    <div class="cand-insights-card" x-show="isProfileComplete" style="display: none;">
                        <div>
                            <div class="cand-insights-header">
                                <div class="cand-insights-top">
                                    <h3 class="cand-insights-title">Your Pro Career Insights</h3>
                                    <span class="cand-pro-pill" :class="isPro ? 'is-pro' : 'is-free'">
                                        <span class="cand-pro-dot"></span>
                                        <span><?= ($candidate['plan'] ?? 'free') === 'premium' ? 'PREMIUM UNLOCKED' : (!empty($candidate['is_pro']) ? 'PRO UNLOCKED' : 'FREE MODE') ?></span>
                                    </span>
                                </div>
                                <p class="cand-insights-sub">See how your profile and applications are performing against peers.</p>
                            </div>

                            <!-- Metric 1: Profile Strength (Always visible for both Free and Pro) -->
                            <div class="cand-metric-item">
                                <div class="cand-metric-head">
                                    <span>Profile Strength</span>
                                    <span class="cand-metric-val blue">86%</span>
                                </div>
                                <div class="cand-metric-track">
                                    <div class="cand-metric-bar blue" style="width: 86%;"></div>
                                </div>
                            </div>

                            <!-- Gated Section for Metrics 2, 3, 4 and Insights Link -->
                            <div class="cand-pro-gated-wrap">
                                <!-- Inner Metrics (Blurred when isPro is false) -->
                                <div :class="{ 'cand-pro-blurred': !isPro }">
                                    <!-- Metric 2: Recruiter Visibility -->
                                    <div class="cand-metric-item">
                                        <div class="cand-metric-head">
                                            <span>Recruiter Visibility</span>
                                            <span class="cand-metric-val blue">82%</span>
                                        </div>
                                        <div class="cand-metric-track">
                                            <div class="cand-metric-bar blue" style="width: 82%;"></div>
                                        </div>
                                    </div>

                                    <!-- Metric 3: Application Strength -->
                                    <div class="cand-metric-item">
                                        <div class="cand-metric-head">
                                            <span>Application Strength</span>
                                            <span class="cand-metric-val green">91%</span>
                                        </div>
                                        <div class="cand-metric-track">
                                            <div class="cand-metric-bar green" style="width: 91%;"></div>
                                        </div>
                                    </div>

                                    <!-- Metric 4: Interview Readiness -->
                                    <div class="cand-metric-item">
                                        <div class="cand-metric-head">
                                            <span>Interview Readiness</span>
                                            <span class="cand-metric-val orange">78%</span>
                                        </div>
                                        <div class="cand-metric-track">
                                            <div class="cand-metric-bar orange" style="width: 78%;"></div>
                                        </div>
                                    </div>

                                    <!-- View Career Insights Link -->
                                    <a href="<?= url('/profile') ?>" class="cand-insights-link">
                                        <span>View Career Insights</span>
                                        <svg style="width: 14px; height: 14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                        </svg>
                                    </a>
                                </div>

                                <!-- Overlay Button for Non-Pro Users -->
                                <div class="cand-pro-overlay" x-show="!isPro">
                                    <a href="<?= url('/pricing') ?>" class="cand-upgrade-btn">
                                        <span>Upgrade to Pro Plan</span>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Reset back to Complete Profile preview toggle -->
                        <div style="margin-top: 14px; border-top: 1px solid #F1F5F9; padding-top: 8px; text-align: right;">
                            <button type="button" @click="isProfileComplete = false" style="background: none; border: none; font-size: 11px; color: #94A3B8; cursor: pointer; text-decoration: underline; padding: 0;">
                                View 75% Checklist
                            </button>
                        </div>
                    </div>
                </div>

            </div>

            <!-- ── Jenny Coins (docs/55) ── -->
            <?php include dirname(__DIR__, 2) . '/shared/components/jenny-coins-card.php'; ?>

            <!-- ── Training programmes with partners (docs/57-training-partners.md) ── -->
            <?php
            $icTraining = \App\Services\PartnerService::forCandidate((int)($_SESSION['candidate_id'] ?? 1));
            $icTrainingOpen = \App\Services\PartnerService::activePartners();
            $icStatus = ['started' => 'Started', 'enrolled' => 'Enrolled', 'in_progress' => 'In progress', 'completed' => 'Completed', 'failed' => 'Not passed', 'withdrawn' => 'Withdrawn', 'expired' => 'Expired'];
            ?>
            <?php if ($icTraining || $icTrainingOpen): ?>
            <div class="db-card" style="margin-bottom: 24px; padding: 20px;">
                <div style="display: flex; align-items: flex-start; justify-content: space-between; gap: 12px; flex-wrap: wrap; margin-bottom: 14px;">
                    <div style="min-width: 0;">
                        <h3 style="font-size: 16px; font-weight: 800; color: #000623; margin: 0;">Training programmes</h3>
                        <p style="font-size: 13px; color: #64748B; margin: 4px 0 0;">Pass a partner course to unlock free Pro or Premium time and a "Certified by" badge for employers.</p>
                    </div>
                    <a href="<?= url('/elearning#certified-training') ?>" style="display: inline-flex; align-items: center; height: 36px; padding: 0 14px; border-radius: 10px; background: #EEF0FF; color: #4F48E6; font-size: 13px; font-weight: 700; text-decoration: none; white-space: nowrap;">Browse partners</a>
                </div>
                <?php if (!$icTraining): ?>
                    <p style="font-size: 13px; color: #94A3B8; margin: 0;">You have not started a partner course yet.</p>
                <?php else: ?>
                    <div style="display: flex; flex-direction: column; gap: 8px;">
                        <?php foreach ($icTraining as $t): $res = $t['result']; ?>
                            <div style="display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap; padding: 12px 14px; border: 1px solid #F1F5F9; border-radius: 12px;">
                                <div style="min-width: 0;">
                                    <div style="font-size: 14px; font-weight: 700; color: #0F172A;"><?= htmlspecialchars($t['partner_name']) ?><?= $t['program'] ? ' · ' . htmlspecialchars($t['program']) : '' ?></div>
                                    <div style="font-size: 12px; color: #64748B; margin-top: 2px;">Reference <?= htmlspecialchars($t['reference']) ?> · <?= htmlspecialchars($icStatus[$t['status']] ?? ucfirst($t['status'])) ?></div>
                                </div>
                                <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                                    <?php if ($res): ?>
                                        <span style="font-size: 13px; font-weight: 800; color: #000623;"><?= rtrim(rtrim(number_format((float)$res['score'], 2), '0'), '.') ?>%</span>
                                        <?php if ($res['plan']): ?>
                                            <span style="font-size: 11px; font-weight: 800; color: #FFFFFF; background: #4F48E6; padding: 3px 9px; border-radius: 999px; white-space: nowrap;"><?= htmlspecialchars(ucfirst($res['plan'])) ?> for <?= (int)$res['plan_days'] ?> days</span>
                                        <?php elseif ($res['passed']): ?>
                                            <span style="font-size: 11px; font-weight: 800; color: #047857; background: #ECFDF5; padding: 3px 9px; border-radius: 999px;">Certified</span>
                                        <?php endif; ?>
                                        <?php if ($res['certificate_url']): ?>
                                            <a href="<?= htmlspecialchars($res['certificate_url']) ?>" target="_blank" rel="noopener" style="font-size: 12.5px; font-weight: 700; color: #4F48E6; text-decoration: none;">Certificate</a>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <span style="font-size: 12px; color: #94A3B8;">Result pending</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
            <?php endif; ?>

            <!-- ── Recommended For You (Original Section Restored) ── -->
            <section aria-labelledby="db-recs-heading" style="margin-top: 36px;">
                <div class="db-section-header">
                    <h3 class="db-section-title" id="db-recs-heading">Recommended for You</h3>
                    <a href="<?= url('/internships') ?>" class="db-view-all">View All Matches</a>
                </div>
                <div class="db-rec-grid">
                    <?php foreach ($recommendations as $rec): ?>
                    <div class="db-rec-card" id="db-rec-card-<?= htmlspecialchars($rec['slug']) ?>" x-data="{ saved: false }" style="background: #ffffff; border: 1px solid #E5E7EB; border-radius: 16px; padding: 22px; display: flex; flex-direction: column; justify-content: space-between; transition: all 0.2s ease;">
                        
                        <!-- Top Header Info -->
                        <div>
                            <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 12px; margin-bottom: 12px;">
                                <!-- Brand Logo / Category / Title -->
                                <div style="display: flex; align-items: center; gap: 12px; min-width: 0; flex: 1;">
                                    <?php if (!empty($rec['logo_url'])): ?>
                                        <img src="<?= htmlspecialchars($rec['logo_url']) ?>" alt="<?= htmlspecialchars($rec['company']) ?>" style="width: 40px; height: 40px; border-radius: 12px; object-fit: cover; flex-shrink: 0; box-shadow: 0 1px 3px rgba(0,0,0,0.06);">
                                    <?php else: ?>
                                        <div style="<?= $rec['logo_bg'] ?> width: 40px; height: 40px; min-width: 40px; min-height: 40px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 16px; flex-shrink: 0; box-shadow: 0 1px 3px rgba(0,0,0,0.06);">
                                            <?= htmlspecialchars($rec['initial'] ?? substr($rec['company'], 0, 1)) ?>
                                        </div>
                                    <?php endif; ?>
                                    <div style="min-width: 0; flex: 1;">
                                        <span style="display: block; font-size: 10px; font-weight: 700; color: #4F47E6; text-transform: uppercase; letter-spacing: 0.08em; line-height: 1.2; margin-bottom: 2px;">
                                            <?= htmlspecialchars($rec['category']) ?>
                                        </span>
                                        <h4 style="font-size: 14.5px; font-weight: 700; color: #111827; margin: 0; line-height: 1.25; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" aria-label="<?= htmlspecialchars($rec['title']) ?>">
                                            <?= htmlspecialchars($rec['title']) ?>
                                        </h4>
                                    </div>
                                </div>

                                <!-- Bookmark Icon Button -->
                                <button type="button" @click="saved = !saved" style="background: none; border: none; padding: 4px; color: #9CA3AF; cursor: pointer; border-radius: 8px; flex-shrink: 0; transition: color 0.15s;" :style="saved ? 'color: #4F47E6;' : ''" aria-label="Save <?= htmlspecialchars($rec['title']) ?>" id="db-rec-bookmark-<?= htmlspecialchars($rec['slug']) ?>">
                                    <!-- Saved outline state -->
                                    <svg x-show="!saved" style="width: 20px; height: 20px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/>
                                    </svg>
                                    <!-- Saved filled state -->
                                    <svg x-show="saved" style="display: none; width: 20px; height: 20px; color: #4F47E6;" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/>
                                    </svg>
                                </button>
                            </div>

                            <!-- Company, Location & Commitment -->
                            <div style="display: flex; align-items: center; gap: 6px; font-size: 12px; color: #6B7280; margin-bottom: 12px; flex-wrap: wrap;">
                                <a href="<?= url('/company/' . ($rec['company_slug'] ?? '')) ?>" style="font-weight: 600; color: #374151; text-decoration: none; transition: color 0.12s;"><?= htmlspecialchars($rec['company']) ?></a>
                                <span style="color: #D1D5DB; font-size: 10px;">•</span>
                                <span><?= htmlspecialchars($rec['location']) ?></span>
                                <span style="color: #D1D5DB; font-size: 10px;">•</span>
                                <span style="font-style: italic; font-size: 11px;"><?= htmlspecialchars($rec['commitment']) ?></span>
                            </div>

                            <!-- Brief Description Excerpt -->
                            <p style="font-size: 12px; color: #4B5563; line-height: 1.55; margin: 0 0 16px 0; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                <?= htmlspecialchars($rec['description']) ?>
                            </p>
                        </div>

                        <!-- Footer: Work Arrangement Tag + Apply Button -->
                        <div style="display: flex; align-items: center; justify-content: space-between; border-top: 1px solid #F3F4F6; padding-top: 12px; margin-top: auto;">
                            <span style="font-size: 10px; font-weight: 700; padding: 4px 10px; border-radius: 8px; background: #F3F4F6; color: #374151; letter-spacing: 0.05em; text-transform: uppercase; white-space: nowrap; flex-shrink: 0;">
                                <?= htmlspecialchars($rec['arrangement']) ?>
                            </span>
                            <a href="<?= url('/internship/' . $rec['slug']) ?>" class="db-apply-btn" id="db-apply-btn-<?= htmlspecialchars($rec['slug']) ?>" style="background: #4F47E6; color: #ffffff; font-size: 12px; font-weight: 700; padding: 6px 14px; border-radius: 10px; text-decoration: none; display: inline-flex; align-items: center; justify-content: center; white-space: nowrap !important; flex-shrink: 0 !important; transition: all 0.15s ease;">
                                Apply
                            </a>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </section>

            <!-- ── Recommended AI Match Internships Section (Directly Beneath Recommended for You) ── -->
            <section id="ai-recommended-roles" aria-labelledby="db-ai-recs-heading" class="ai-match-section">
                <div class="ai-match-header-wrap">
                    <div>
                        <span class="ai-match-eyebrow">
                            <span style="width: 16px; height: 16px; border-radius: 50%; background: #000623; border: 1.5px solid #4F48E6; color: #FFFFFF; display: inline-flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                <svg style="width: 9px; height: 10px; flex-shrink: 0;" viewBox="0 0 74 83" fill="currentColor">
                                    <path d="M73.74 32.4627V59.9427C67.93 59.7673 62.2767 61.8422 57.9596 65.7344C53.6426 69.6267 50.9952 75.0356 50.57 80.8327C50.52 81.3027 50.48 81.8327 50.46 82.2827H23.57C23.57 82.0227 23.48 81.7627 23.43 81.5127C20.73 67.1427 16.41 63.2427 0 59.6527V32.6527C13.34 31.5727 27.12 37.6527 36.92 47.9327C46 38.3527 58.99 31.8927 73.74 32.4627Z"/>
                                    <path d="M37.0001 0.0031237C40.4833 0.0367014 43.8785 1.10075 46.7578 3.06112C49.637 5.02149 51.8715 7.79043 53.1794 11.0188C54.4873 14.2473 54.8101 17.7906 54.1071 21.2023C53.4042 24.6139 51.7069 27.741 49.2293 30.1894C46.7516 32.6378 43.6046 34.2979 40.1849 34.9604C36.7652 35.6228 33.2259 35.258 30.0132 33.9119C26.8005 32.5658 24.0583 30.2987 22.1322 27.3963C20.2061 24.494 19.1824 21.0864 19.1901 17.6031C19.1737 15.2695 19.6247 12.9562 20.5166 10.7997C21.4084 8.64315 22.7231 6.68703 24.383 5.04669C26.0429 3.40635 28.0145 2.11499 30.1814 1.24877C32.3484 0.382549 34.6669 -0.040991 37.0001 0.0031237Z"/>
                                </svg>
                            </span>
                            AI Match Engine
                        </span>
                        <h3 class="ai-match-title" id="db-ai-recs-heading">Recommended AI Match Internships</h3>
                    </div>
                    <a href="<?= url('/internships') ?>" class="ai-match-view-all">
                        <span>View All AI Matches</span>
                        <svg style="width: 14px; height: 14px; flex-shrink: 0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </a>
                </div>

                <!-- 3-Card Grid Matching Homepage Opportunities -->
                <div class="ai-opps-cards-grid">
                    <?php foreach (($aiRecommendations ?? []) as $rec): ?>
                    <div class="cand-ai-card" id="db-ai-card-<?= htmlspecialchars($rec['slug']) ?>">
                        <div>
                            <!-- Card Top: Company Logo & Employment Badge -->
                            <div class="cand-ai-card-top">
                                <a href="<?= url('/company/' . ($rec['slug'] ?? 'kiro-labs')) ?>" class="cand-ai-logo-box" style="background-color: <?= htmlspecialchars($rec['logo_bg'] ?? '#FFFFFF') ?>; <?= ($rec['logo_bg'] ?? '') === '#000623' ? 'border-color: #1E293B;' : '' ?>" aria-label="View <?= htmlspecialchars($rec['company']) ?> Profile">
                                    <img src="<?= htmlspecialchars($rec['logo']) ?>" alt="<?= htmlspecialchars($rec['company']) ?>" class="cand-ai-logo-img" loading="lazy">
                                </a>
                                <span class="cand-ai-type-badge cand-ai-<?= htmlspecialchars($rec['type_class']) ?>">
                                    <?= htmlspecialchars($rec['type']) ?>
                                </span>
                            </div>

                            <!-- Role Title & Subtitle -->
                            <h4 class="cand-ai-role-title">
                                <?= htmlspecialchars($rec['title']) ?>
                            </h4>
                            <p class="cand-ai-role-company">
                                <a href="<?= url('/company/' . ($rec['slug'] ?? 'kiro-labs')) ?>" style="color: inherit; text-decoration: none; font-weight: 600;"><?= htmlspecialchars($rec['company']) ?></a>
                                <span>&bull;</span>
                                <span><?= htmlspecialchars($rec['location']) ?></span>
                            </p>

                            <!-- Description -->
                            <p class="cand-ai-desc-text">
                                <?= htmlspecialchars($rec['description']) ?>
                            </p>
                        </div>

                        <div>
                            <!-- Stat Row: Category | Experience | Stipend -->
                            <div class="cand-ai-stats-row">
                                <!-- Category -->
                                <div class="cand-ai-stat-item">
                                    <svg class="cand-ai-stat-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5a2 2 0 011.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V5a2 2 0 012-2z"/>
                                    </svg>
                                    <span><?= htmlspecialchars($rec['category']) ?></span>
                                </div>

                                <div class="cand-ai-stat-divider"></div>

                                <!-- Experience Level -->
                                <div class="cand-ai-stat-item">
                                    <svg class="cand-ai-stat-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                                    </svg>
                                    <span><?= htmlspecialchars($rec['experience']) ?></span>
                                </div>

                                <div class="cand-ai-stat-divider"></div>

                                <!-- Stipend / Pay -->
                                <div class="cand-ai-stat-item stat-stipend">
                                    <svg class="cand-ai-stat-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                    <span><?= htmlspecialchars($rec['stipend']) ?></span>
                                </div>
                            </div>

                            <!-- Full-Width Apply Button -->
                            <a href="<?= url('/internship/' . $rec['slug']) ?>" class="cand-ai-card-btn">
                                <span>Apply Now</span>
                                <svg style="width: 15px; height: 15px; flex-shrink: 0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                            </a>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </section>

            <!-- ── Bottom Row ── -->
            <div class="db-bottom-row">
                <!-- Recent Applications -->
                <div class="db-card">
                    <div class="db-table-header">
                        <h3 class="db-card-title">Recent Applications</h3>
                        <div style="position: relative;" x-data="{ open: false }" @click.outside="open = false">
                            <button class="db-filter-btn" type="button" @click="open = !open">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                                Filter
                            </button>
                            <div x-show="open" style="display: none; position: absolute; top: calc(100% + 8px); right: 0; width: 180px; background: #fff; border: 1px solid #E8ECF2; border-radius: 10px; box-shadow: 0 8px 24px rgba(0,0,0,.08); z-index: 50; padding: 8px;">
                                <div style="padding: 6px 12px; font-size: 13px; cursor: pointer; border-radius: 6px;" onmouseover="this.style.background='#F1F5F9'" onmouseout="this.style.background='transparent'">All Statuses</div>
                                <div style="padding: 6px 12px; font-size: 13px; cursor: pointer; border-radius: 6px;" onmouseover="this.style.background='#F1F5F9'" onmouseout="this.style.background='transparent'">Interviewing</div>
                                <div style="padding: 6px 12px; font-size: 13px; cursor: pointer; border-radius: 6px;" onmouseover="this.style.background='#F1F5F9'" onmouseout="this.style.background='transparent'">Accepted</div>
                            </div>
                        </div>
                    </div>
                    <div class="db-table-responsive">
                        <table class="db-table" aria-label="Recent applications">
                            <thead>
                                <tr>
                                    <th>Company</th>
                                    <th>Role</th>
                                    <th class="col-date-applied">Date Applied</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($applications as $app): ?>
                                <tr>
                                    <td class="company-name"><?= htmlspecialchars($app['company']) ?></td>
                                    <td class="role-name"><?= htmlspecialchars($app['role']) ?></td>
                                    <td class="date-cell col-date-applied"><?= htmlspecialchars($app['date']) ?></td>
                                    <td><span class="db-status-pill <?= $app['status_class'] ?>"><?= htmlspecialchars($app['status']) ?></span></td>
                                    <td style="text-align: right;">
                                        <div style="position: relative; display: inline-block; text-align: left;" x-data="{ open: false }" @click.outside="open = false">
                                            <button class="db-action-btn" type="button" @click="open = !open">...</button>
                                            <div x-show="open" style="display: none; position: absolute; top: calc(100% - 5px); right: 0; width: 160px; background: #fff; border: 1px solid #E8ECF2; border-radius: 10px; box-shadow: 0 8px 24px rgba(0,0,0,.08); z-index: 50; padding: 8px;">
                                                <div style="padding: 6px 12px; font-size: 13px; color: #191C1D; cursor: pointer; border-radius: 6px; display: flex; align-items: center; gap: 8px;" onmouseover="this.style.background='#F1F5F9'" onmouseout="this.style.background='transparent'">
                                                    <svg style="width: 14px; height: 14px; color: #64748B;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                    </svg>
                                                    <span>View Details</span>
                                                </div>
                                                <div style="padding: 6px 12px; font-size: 13px; color: #DC2626; cursor: pointer; border-radius: 6px; display: flex; align-items: center; gap: 8px;" onmouseover="this.style.background='#FEE2E2'" onmouseout="this.style.background='transparent'">
                                                    <svg style="width: 14px; height: 14px; color: #DC2626;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                    <span>Withdraw</span>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="db-pagination">
                        <button class="db-page-btn active">1</button>
                        <button class="db-page-btn">2</button>
                        <button class="db-page-btn">3</button>
                        <span class="db-page-dots">...</span>
                        <button class="db-page-btn" style="padding: 0 12px;">Next</button>
                    </div>
                </div>

                <!-- Recent Alerts -->
                <div class="db-card">
                    <div class="db-alerts-header">
                        <h3 class="db-card-title">Recent Alerts</h3>
                        <span class="db-alert-count" aria-label="7 unread alerts">7</span>
                    </div>
                    <div class="db-alert-list" role="list">
                        <?php foreach ($alerts as $alert): ?>
                        <div class="db-alert-item" role="listitem">
                            <div class="db-alert-icon-wrap <?= $alert['color'] ?>">
                                <?= $alert['icon'] ?>
                            </div>
                            <div class="db-alert-content">
                                <div class="db-alert-title"><?= htmlspecialchars($alert['title']) ?></div>
                                <div class="db-alert-desc"><?= htmlspecialchars($alert['desc']) ?></div>
                                <div class="db-alert-time"><?= htmlspecialchars($alert['time']) ?></div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <a href="<?= url('/notifications') ?>" class="db-mark-read">Mark all as read</a>
                </div>
            </div>
        </div><!-- /.db-content -->

        <!-- Dashboard Footer -->
        <?php include __DIR__ . '/../../shared/components/db-footer.php'; ?>


    </div><!-- /.db-main -->
</div><!-- /.db-shell -->
