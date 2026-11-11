<?php
declare(strict_types=1);

/**
 * View: Search Results Page — "Find Internships"
 * Route: /search
 * Layout: app (Public navbar, footer, toast, Jenny AI chatbot)
 * 
 * @var string $title
 * @var string $initialSearch
 * @var string $initialLocation
 * @var string $initialCategory
 */

$initialSearch   = $initialSearch ?? '';
$initialLocation = $initialLocation ?? '';
$initialCategory = $initialCategory ?? '';
?>

<!-- Load Google Font Inter & Iconify CDN -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<script src="https://code.iconify.design/iconify-icon/2.1.0/iconify-icon.min.js"></script>

<style>
    /* ─── Search Results Page Design System ─── */
    .int-search-page-wrap {
        background-color: #F8FAFC;
        min-height: calc(100vh - 80px);
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        box-sizing: border-box;
    }

    /* ─── Hero Header Banner (Cloudinary Background with Gradient Overlay) ─── */
    .int-header-hero {
        background-color: #000623;
        background-image: linear-gradient(90deg, rgba(0, 6, 35, 0.90) 0%, rgba(0, 6, 35, 0.76) 45%, rgba(0, 6, 35, 0.50) 100%),
                          url('https://res.cloudinary.com/weburea/image/upload/v1790982402/inter_5_hktky2.png');
        background-size: cover;
        background-position: center right;
        background-repeat: no-repeat;
        padding: 44px 0 48px;
        position: relative;
        overflow: hidden;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        box-sizing: border-box;
    }

    .int-header-hero::before {
        content: '';
        position: absolute;
        inset: 0;
        background-image: radial-gradient(rgba(255, 255, 255, 0.04) 1px, transparent 1px);
        background-size: 24px 24px;
        pointer-events: none;
    }

    .int-container {
        max-width: 1240px;
        margin: 0 auto;
        padding: 0 24px;
        box-sizing: border-box;
        position: relative;
        z-index: 2;
    }

    .int-breadcrumb-row {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
        font-weight: 500;
        color: #94A3B8;
        margin-bottom: 16px;
        flex-wrap: wrap;
    }

    .int-breadcrumb-link {
        color: #CBD5E1;
        text-decoration: none;
        transition: color 0.15s ease;
    }

    .int-breadcrumb-link:hover {
        color: #FFFFFF;
    }

    .int-breadcrumb-sep {
        color: #64748B;
        font-size: 12px;
    }

    .int-breadcrumb-current {
        color: #FFFFFF;
        font-weight: 600;
    }

    .int-header-title {
        font-size: clamp(26px, 3.5vw, 36px);
        font-weight: 800;
        color: #FFFFFF;
        letter-spacing: -0.025em;
        line-height: 1.25;
        margin: 0 0 8px;
        text-shadow: 0 2px 8px rgba(0, 6, 35, 0.4);
    }

    .int-header-subtext {
        font-size: 15px;
        color: #E2E8F0;
        max-width: 640px;
        margin: 0;
        line-height: 1.55;
        text-shadow: 0 1px 4px rgba(0, 6, 35, 0.5);
    }

    /* ─── Main Two-Column Layout Grid ─── */
    .int-layout-wrap {
        padding: 32px 0 72px;
        box-sizing: border-box;
    }

    .int-main-grid {
        display: grid;
        grid-template-columns: 310px minmax(0, 1fr);
        gap: 28px;
        align-items: start;
        box-sizing: border-box;
    }

    /* Mobile Filter Toggle Drawer Trigger */
    .int-mobile-filter-bar {
        display: none;
        align-items: center;
        justify-content: space-between;
        background: #FFFFFF;
        border: 1px solid #E2E8F0;
        border-radius: 12px;
        padding: 12px 16px;
        margin-bottom: 20px;
        box-shadow: 0 1px 3px rgba(0, 6, 35, 0.04);
    }

    .int-mobile-filter-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #4F48E6;
        color: #FFFFFF;
        border: none;
        padding: 9px 18px;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        white-space: nowrap !important;
        flex-shrink: 0 !important;
        box-shadow: 0 2px 8px rgba(79, 72, 230, 0.25);
        transition: all 0.2s ease;
    }

    @keyframes filterBtnPulse {
        0%, 100% {
            box-shadow: 0 2px 8px rgba(79, 72, 230, 0.25);
            transform: scale(1);
        }
        50% {
            box-shadow: 0 4px 16px rgba(79, 72, 230, 0.45);
            transform: scale(1.02);
        }
    }

    @keyframes filterIconWiggle {
        0%, 100% { transform: rotate(0deg); }
        20% { transform: rotate(-8deg); }
        40% { transform: rotate(8deg); }
        60% { transform: rotate(-4deg); }
        80% { transform: rotate(4deg); }
    }

    /* ─── Left Sidebar Filters Card ─── */
    .int-filter-card {
        background: #FFFFFF;
        border: 1px solid #E2E8F0;
        border-radius: 18px;
        padding: 24px;
        box-shadow: 0 1px 3px rgba(0, 6, 35, 0.02);
        box-sizing: border-box;
        margin-bottom: 20px;
    }

    .int-filter-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding-bottom: 16px;
        border-bottom: 1px solid #F1F5F9;
        margin-bottom: 20px;
    }

    .int-filter-title {
        font-size: 16px;
        font-weight: 800;
        color: #0F172A;
        display: flex;
        align-items: center;
        gap: 8px;
        margin: 0;
    }

    .int-filter-reset {
        font-size: 12.5px;
        font-weight: 700;
        color: #4F48E6;
        background: transparent;
        border: none;
        cursor: pointer;
        padding: 4px 6px;
        border-radius: 6px;
        transition: all 0.15s ease;
    }

    .int-filter-reset:hover {
        background: #EEF2FF;
    }

    .int-filter-section {
        margin-bottom: 22px;
        padding-bottom: 20px;
        border-bottom: 1px solid #F1F5F9;
    }

    .int-filter-section:last-child {
        margin-bottom: 0;
        padding-bottom: 0;
        border-bottom: none;
    }

    .int-filter-label {
        font-size: 13.5px;
        font-weight: 700;
        color: #0F172A;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    /* Search Input inside Sidebar */
    .int-search-input-wrap {
        position: relative;
        width: 100%;
        box-sizing: border-box;
    }

    .int-search-input-icon {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        width: 16px;
        height: 16px;
        color: #94A3B8;
        pointer-events: none;
    }

    .int-search-input {
        width: 100%;
        height: 42px;
        background: #F8FAFC;
        border: 1px solid #CBD5E1;
        border-radius: 10px;
        padding: 0 14px 0 38px;
        font-size: 13.5px;
        color: #0F172A;
        font-family: inherit;
        box-sizing: border-box;
        transition: all 0.15s ease;
    }

    .int-search-input:focus {
        outline: none;
        background: #FFFFFF;
        border-color: #4F48E6;
        box-shadow: 0 0 0 3px rgba(79, 72, 230, 0.12);
    }

    /* Standardized Alpine Dropdown Component */
    .db-dropdown-wrap {
        position: relative;
        width: 100%;
        box-sizing: border-box;
    }

    .db-dropdown-btn {
        width: 100%;
        height: 42px;
        background: #F8FAFC;
        border: 1px solid #CBD5E1;
        border-radius: 10px;
        padding: 0 14px;
        font-size: 13.5px;
        color: #0F172A;
        font-family: inherit;
        display: flex;
        align-items: center;
        justify-content: space-between;
        cursor: pointer;
        box-sizing: border-box;
        transition: all 0.15s ease;
        text-align: left;
    }

    .db-dropdown-btn:hover {
        background: #FFFFFF;
        border-color: #94A3B8;
    }

    .db-dropdown-btn.is-open {
        border-color: #4F48E6;
        box-shadow: 0 0 0 3px rgba(79, 72, 230, 0.12);
        background: #FFFFFF;
    }

    .db-dropdown-menu {
        position: absolute;
        top: calc(100% + 6px);
        left: 0;
        right: 0;
        background: #FFFFFF;
        border: 1px solid #E2E8F0;
        border-radius: 12px;
        box-shadow: 0 12px 30px rgba(0, 6, 35, 0.12);
        z-index: 50;
        max-height: 240px;
        overflow-y: auto !important;
        padding: 6px;
        box-sizing: border-box;
    }

    .db-dropdown-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 9px 12px;
        border-radius: 8px;
        font-size: 13px;
        color: #334155;
        cursor: pointer;
        transition: all 0.15s ease;
    }

    .db-dropdown-item:hover {
        background: #EEF2FF;
        color: #4F48E6;
    }

    .db-dropdown-item.is-selected {
        background: #EEF2FF;
        color: #4F48E6;
        font-weight: 700;
    }

    /* Searchable Dropdown Extensions */
    .db-dropdown-menu-searchable {
        padding: 0 !important;
        overflow: hidden !important;
        max-height: 290px !important;
        display: flex;
        flex-direction: column;
    }

    .db-dropdown-search-box {
        padding: 8px 10px;
        border-bottom: 1px solid #F1F5F9;
        background: #FFFFFF;
        flex-shrink: 0;
    }

    .db-dropdown-search-input-wrap {
        position: relative;
        display: flex;
        align-items: center;
    }

    .db-dropdown-search-input {
        width: 100%;
        height: 36px;
        padding: 6px 10px 6px 32px;
        border: 1px solid #E2E8F0;
        border-radius: 8px;
        font-size: 12.5px;
        background: #F8FAFC;
        outline: none;
        box-sizing: border-box;
        font-family: inherit;
        transition: all 0.15s ease;
    }

    .db-dropdown-search-input:focus {
        border-color: #4F48E6;
        background: #FFFFFF;
        box-shadow: 0 0 0 2px rgba(79, 72, 230, 0.1);
    }

    .db-dropdown-items-scroll {
        max-height: 220px;
        overflow-y: auto !important;
        padding: 6px;
        scrollbar-gutter: stable;
        flex: 1;
    }

    /* Checkbox list */
    .int-check-list {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .int-check-label {
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: 13.5px;
        color: #334155;
        cursor: pointer;
        user-select: none;
        gap: 8px;
    }

    .int-check-left {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .int-checkbox {
        accent-color: #4F48E6;
        width: 17px;
        height: 17px;
        border-radius: 5px;
        cursor: pointer;
        flex-shrink: 0;
    }

    .int-count-pill {
        font-size: 11px;
        font-weight: 700;
        padding: 2px 7px;
        border-radius: 99px;
        background: #F1F5F9;
        color: #64748B;
        white-space: nowrap !important;
        flex-shrink: 0 !important;
    }

    .int-show-more-btn {
        background: transparent;
        border: none;
        color: #4F48E6;
        font-size: 12.5px;
        font-weight: 700;
        padding: 6px 0 0;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        transition: color 0.15s ease;
    }

    .int-show-more-btn:hover {
        color: #3730A3;
        text-decoration: underline;
    }

    /* Stipend Slider (in Naira) */
    .int-slider-wrap {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .int-range-slider {
        width: 100%;
        accent-color: #4F48E6;
        height: 6px;
        border-radius: 4px;
        background: #E2E8F0;
        cursor: pointer;
    }

    .int-slider-actions {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
    }

    .int-slider-display {
        font-size: 13px;
        font-weight: 700;
        color: #0F172A;
    }

    .int-btn-apply-slider {
        background: #4F48E6;
        color: #FFFFFF;
        border: none;
        padding: 5px 14px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.15s ease;
        white-space: nowrap !important;
        flex-shrink: 0 !important;
    }

    .int-btn-apply-slider:hover {
        background: #4338CA;
    }

    /* Tags Pill Cloud */
    .int-tags-cloud {
        display: flex;
        flex-wrap: wrap;
        gap: 7px;
    }

    .int-tag-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        border-radius: 99px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.15s ease;
        user-select: none;
        background: #F8FAFC;
        border: 1px solid #E2E8F0;
        color: #334155;
        white-space: nowrap !important;
        flex-shrink: 0 !important;
    }

    .int-tag-pill:hover {
        background: #EEF2FF;
        border-color: #C7D2FE;
        color: #4F48E6;
    }

    .int-tag-pill.is-active {
        background: #4F48E6;
        border-color: #4F48E6;
        color: #FFFFFF;
    }

    /* ─── Jenny AI Promo Card (Replaces "WE ARE HIRING") ─── */
    .int-jenny-promo-card {
        background: #000623;
        border: 1px solid #1E293B;
        border-radius: 18px;
        padding: 22px;
        color: #FFFFFF;
        position: relative;
        overflow: hidden;
        box-shadow: 0 10px 25px rgba(0, 6, 35, 0.1);
        box-sizing: border-box;
    }

    .int-jenny-promo-card::before {
        content: '';
        position: absolute;
        top: -30px;
        right: -30px;
        width: 120px;
        height: 120px;
        background: radial-gradient(circle, rgba(79, 72, 230, 0.35) 0%, transparent 70%);
        pointer-events: none;
    }

    .int-jenny-promo-header {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 12px;
    }

    .int-jenny-avatar-box {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        background: rgba(255, 255, 255, 0.12);
        padding: 4px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .int-jenny-avatar-box img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }

    .int-jenny-headline {
        font-size: 15px;
        font-weight: 800;
        color: #FFFFFF;
        margin: 0 0 2px;
        line-height: 1.3;
    }

    .int-jenny-badge {
        font-size: 10.5px;
        font-weight: 700;
        color: #818CF8;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .int-jenny-subtext {
        font-size: 12.5px;
        color: #CBD5E1;
        line-height: 1.5;
        margin: 0 0 16px;
    }

    .int-jenny-cta-btn {
        width: 100%;
        background: #4F48E6;
        color: #FFFFFF;
        border: none;
        padding: 10px 16px;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: all 0.15s ease;
        white-space: nowrap !important;
        flex-shrink: 0 !important;
        box-shadow: 0 4px 14px rgba(79, 72, 230, 0.35);
    }

    .int-jenny-cta-btn:hover {
        background: #4338CA;
        transform: translateY(-1px);
        box-shadow: 0 6px 18px rgba(79, 72, 230, 0.45);
    }

    /* ─── Right Column: Results List ─── */
    .int-results-col {
        min-width: 0;
        width: 100%;
    }

    .int-results-top-bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 18px;
        gap: 16px;
        flex-wrap: wrap;
    }

    .int-results-count-text {
        font-size: 14px;
        color: #475569;
        margin: 0;
    }

    .int-results-count-text strong {
        color: #0F172A;
        font-weight: 700;
    }

    .int-sort-wrap {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .int-sort-label {
        font-size: 13px;
        color: #64748B;
        font-weight: 600;
        white-space: nowrap;
    }

    /* ─── Result Cards ─── */
    .int-cards-list {
        display: flex;
        flex-direction: column;
        gap: 16px;
        margin-bottom: 32px;
    }

    .int-result-card {
        background: #FFFFFF;
        border: 1px solid #E2E8F0;
        border-radius: 16px;
        padding: 22px 24px;
        transition: all 0.2s ease;
        box-sizing: border-box;
        position: relative;
    }

    .int-result-card:hover {
        border-color: #CBD5E1;
        box-shadow: 0 6px 20px rgba(0, 6, 35, 0.05);
        transform: translateY(-1px);
    }

    .int-card-top-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 14px;
    }

    .int-time-badge {
        font-size: 11.5px;
        font-weight: 600;
        color: #64748B;
        background: #F1F5F9;
        padding: 3px 10px;
        border-radius: 6px;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        white-space: nowrap !important;
        flex-shrink: 0 !important;
    }

    .int-time-badge svg {
        width: 12px;
        height: 12px;
        color: #94A3B8;
        flex-shrink: 0;
    }

    .int-bookmark-btn {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        background: #F8FAFC;
        border: 1px solid #E2E8F0;
        color: #64748B;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.15s ease;
        padding: 0;
    }

    .int-bookmark-btn:hover {
        background: #EEF2FF;
        border-color: #C7D2FE;
        color: #4F48E6;
    }

    .int-bookmark-btn.is-saved {
        background: #EEF2FF;
        border-color: #C7D2FE;
        color: #4F48E6;
    }

    .int-bookmark-btn svg {
        width: 16px;
        height: 16px;
    }

    .int-card-body-row {
        display: flex;
        align-items: flex-start;
        gap: 16px;
        margin-bottom: 16px;
    }

    .int-company-logo {
        width: 46px;
        height: 46px;
        border-radius: 12px;
        overflow: hidden;
        border: 1px solid #E2E8F0;
        background: #F8FAFC;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        font-weight: 800;
        flex-shrink: 0;
        text-decoration: none;
    }

    .int-company-logo img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .int-card-body-titles {
        flex: 1;
        min-width: 0;
    }

    .int-card-role-title {
        font-size: 17px;
        font-weight: 800;
        color: #000623;
        margin: 0 0 4px;
        line-height: 1.3;
    }

    .int-card-role-link {
        color: #000623;
        text-decoration: none;
        transition: color 0.15s ease;
    }

    .int-card-role-link:hover {
        color: #4F48E6;
    }

    .int-card-company-name {
        font-size: 13.5px;
        color: #64748B;
        font-weight: 500;
        margin: 0;
    }

    .int-card-company-link {
        color: #64748B;
        text-decoration: none;
        transition: color 0.15s ease;
    }

    .int-card-company-link:hover {
        color: #4F48E6;
        text-decoration: underline;
    }

    /* Details row with small icon + labels */
    .int-details-row {
        display: flex;
        align-items: center;
        gap: 16px;
        flex-wrap: wrap;
        padding-top: 14px;
        border-top: 1px solid #F1F5F9;
    }

    .int-detail-item {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 13px;
        color: #475569;
        font-weight: 500;
    }

    .int-detail-item svg {
        width: 15px;
        height: 15px;
        color: #94A3B8;
        flex-shrink: 0;
    }

    .int-detail-stipend {
        font-weight: 700;
        color: #0F172A;
    }

    .int-card-bottom-actions {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-top: 16px;
        gap: 12px;
        flex-wrap: wrap;
    }

    .int-card-desc-snippet {
        font-size: 13px;
        color: #64748B;
        line-height: 1.55;
        margin: 0;
        flex: 1;
        min-width: 200px;
    }

    .int-btn-view-details {
        background: #4F48E6;
        color: #FFFFFF;
        padding: 9px 20px;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 700;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.15s ease;
        white-space: nowrap !important;
        flex-shrink: 0 !important;
        box-shadow: 0 2px 8px rgba(79, 72, 230, 0.25);
    }

    .int-btn-view-details:hover {
        background: #4338CA;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(79, 72, 230, 0.35);
    }

    .int-btn-view-details svg {
        width: 14px;
        height: 14px;
        flex-shrink: 0;
    }

    /* ─── Pagination (Standardized Circular Design Matching Internships) ─── */
    .int-pagination-wrap {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        margin-top: 32px;
        flex-wrap: wrap;
    }

    .int-page-btn {
        width: 34px;
        height: 34px;
        min-width: 34px;
        padding: 0;
        border-radius: 9999px !important;
        background: #FFFFFF;
        border: 1px solid #CBD5E1;
        color: #334155;
        font-size: 12.5px;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.15s ease;
        white-space: nowrap !important;
        flex-shrink: 0 !important;
    }

    .int-page-btn:hover {
        border-color: #4F48E6;
        color: #4F48E6;
        background: #F8FAFC;
    }

    .int-page-btn.is-active {
        background: #4F48E6;
        border-color: #4F48E6;
        color: #FFFFFF;
        font-weight: 700;
        box-shadow: 0 2px 8px rgba(79, 72, 230, 0.25);
    }

    .int-page-nav-text-btn {
        height: 34px;
        padding: 0 14px;
        border-radius: 9999px;
        background: transparent;
        border: none;
        color: #64748B;
        font-size: 13px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.15s ease;
        white-space: nowrap !important;
        flex-shrink: 0 !important;
    }

    .int-page-nav-text-btn:hover {
        color: #4F48E6;
    }

    .int-page-nav-text-btn:disabled,
    .int-page-btn:disabled {
        opacity: 0.4;
        cursor: not-allowed;
    }

    /* Empty state */
    .int-empty-state {
        background: #FFFFFF;
        border: 1px solid #E2E8F0;
        border-radius: 18px;
        padding: 48px 24px;
        text-align: center;
        box-shadow: 0 1px 3px rgba(0, 6, 35, 0.02);
    }

    .int-empty-icon {
        width: 56px;
        height: 56px;
        border-radius: 16px;
        background: #EEF2FF;
        color: #4F48E6;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 16px;
    }

    .int-empty-icon svg {
        width: 28px;
        height: 28px;
    }

    .int-empty-title {
        font-size: 18px;
        font-weight: 800;
        color: #0F172A;
        margin: 0 0 6px;
    }

    .int-empty-desc {
        font-size: 14px;
        color: #64748B;
        max-width: 440px;
        margin: 0 auto 20px;
        line-height: 1.55;
    }

    /* ─── Responsive Media Queries (Zero Mobile Horizontal Blowout) ─── */
    @media (max-width: 1024px) {
        .int-main-grid {
            grid-template-columns: minmax(0, 1fr) !important;
        }

        .int-mobile-filter-bar {
            display: flex;
        }

        .int-mobile-filter-btn {
            animation: filterBtnPulse 2.8s infinite ease-in-out;
        }

        .int-mobile-filter-btn svg {
            animation: filterIconWiggle 2.8s infinite ease-in-out;
        }

        .int-sidebar-col {
            display: none;
        }

        .int-sidebar-col.is-mobile-open {
            display: block;
        }
    }

    @media (max-width: 640px) {
        .int-header-hero {
            padding: 30px 0 34px;
        }

        .int-details-row {
            gap: 10px 14px;
        }

        .int-result-card {
            padding: 18px 16px;
        }

        .int-card-bottom-actions {
            flex-direction: column;
            align-items: stretch;
            gap: 12px;
        }

        .int-btn-view-details {
            width: 100% !important;
            justify-content: center !important;
        }

        .int-pagination-wrap {
            justify-content: center;
        }
    }
</style>

<div class="int-search-page-wrap" 
     x-data="internshipSearchManager({
         initialSearch: '<?= htmlspecialchars($initialSearch, ENT_QUOTES, 'UTF-8') ?>',
         initialLocation: '<?= htmlspecialchars($initialLocation, ENT_QUOTES, 'UTF-8') ?>',
         initialCategory: '<?= htmlspecialchars($initialCategory, ENT_QUOTES, 'UTF-8') ?>'
     })">

    <!-- ══ Hero Header Banner (Navy Brand Gradient) ══ -->
    <header class="int-header-hero">
        <div class="int-container">
            <!-- Breadcrumbs -->
            <div class="int-breadcrumb-row">
                <a href="<?= url('/') ?>" class="int-breadcrumb-link">Home</a>
                <span class="int-breadcrumb-sep">/</span>
                <a href="<?= url('/internships') ?>" class="int-breadcrumb-link">Internships</a>
                <span class="int-breadcrumb-sep">/</span>
                <span class="int-breadcrumb-current">Search Results</span>
            </div>

            <!-- Dynamic Header Title -->
            <h1 class="int-header-title" x-text="headerTitle">Find Internships</h1>
            <p class="int-header-subtext">Discover verified internship opportunities across high-growth startups and established enterprises.</p>
        </div>
    </header>

    <!-- ══ Main Content Area ══ -->
    <main class="int-layout-wrap">
        <div class="int-container">

            <!-- Mobile Filter Toggle Bar (Visible on mobile/tablets) -->
            <div class="int-mobile-filter-bar">
                <span style="font-size: 13.5px; font-weight: 700; color: #0F172A;" x-text="filteredTotal + ' Opportunities Found'"></span>
                <button type="button" @click="mobileFiltersOpen = !mobileFiltersOpen" class="int-mobile-filter-btn">
                    <svg style="width: 15px; height: 15px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                    </svg>
                    <span x-text="mobileFiltersOpen ? 'Hide Filters' : 'Filter Results'">Filter Results</span>
                </button>
            </div>

            <div class="int-main-grid">

                <!-- ════ Left Sidebar Filter Column ════ -->
                <aside class="int-sidebar-col" :class="{ 'is-mobile-open': mobileFiltersOpen }">
                    
                    <div class="int-filter-card">
                        
                        <!-- Filter Header -->
                        <div class="int-filter-header">
                            <h3 class="int-filter-title">
                                <svg style="width: 18px; height: 18px; color: #4F48E6;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                                </svg>
                                <span>Filter by</span>
                            </h3>
                            <button type="button" class="int-filter-reset" @click="resetAllFilters()">Reset All</button>
                        </div>

                        <!-- 1. Search by Job Title / Keyword -->
                        <div class="int-filter-section">
                            <label class="int-filter-label" for="int-search-title">Search by Job Title</label>
                            <div class="int-search-input-wrap">
                                <svg class="int-search-input-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                <input type="text" 
                                       id="int-search-title" 
                                       class="int-search-input" 
                                       placeholder="Role or company" 
                                       x-model.debounce.250ms="searchTitle"
                                       aria-label="Search by job title or company">
                            </div>
                        </div>

                        <!-- 2. Location (City & International Search Dropdown) -->
                        <div class="int-filter-section">
                            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px;">
                                <label class="int-filter-label" style="margin-bottom: 0;">Location</label>
                                <span x-show="selectedLocation && selectedLocation !== 'All Locations'" 
                                      @click="selectedLocation = ''; locationSearch = ''" 
                                      style="font-size: 11.5px; font-weight: 600; color: #4F48E6; cursor: pointer; text-decoration: underline;" 
                                      x-cloak>
                                    Clear
                                </span>
                            </div>

                            <div class="db-dropdown-wrap" x-data="{ open: false }" @click.outside="open = false">
                                <button type="button" 
                                        class="db-dropdown-btn" 
                                        :class="{ 'is-open': open }" 
                                        @click="open = !open; if(open) { $nextTick(() => { const el = $refs.locSearchInput; if(el) el.focus(); }); }"
                                        aria-label="Filter by location">
                                    <span style="display: inline-flex; align-items: center; gap: 8px; min-width: 0; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                        <svg style="width: 15px; height: 15px; color: #94A3B8; flex-shrink: 0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                        <span x-text="selectedLocation ? selectedLocation : 'Choose city or country'" 
                                              :style="!selectedLocation ? 'color: #94A3B8;' : 'color: #0F172A; font-weight: 600;'"
                                              style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">Choose city or country</span>
                                    </span>
                                    <svg style="width: 16px; height: 16px; color: #94A3B8; transition: transform 0.2s ease; flex-shrink: 0;" :class="{ 'rotate-180': open }" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m6 9 6 6 6-6"/>
                                    </svg>
                                </button>

                                <div x-show="open" x-transition class="db-dropdown-menu db-dropdown-menu-searchable" style="display: none;">
                                    <div class="db-dropdown-search-box">
                                        <div class="db-dropdown-search-input-wrap">
                                            <svg style="width: 14px; height: 14px; color: #94A3B8; position: absolute; left: 10px; pointer-events: none;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                            </svg>
                                            <input type="text" 
                                                   x-ref="locSearchInput"
                                                   x-model="locationSearch" 
                                                   @click.stop 
                                                   placeholder="Search city, state or country..." 
                                                   class="db-dropdown-search-input">
                                            <button type="button" 
                                                    x-show="locationSearch" 
                                                    @click.stop="locationSearch = ''" 
                                                    style="position: absolute; right: 8px; background: transparent; border: none; color: #94A3B8; cursor: pointer; padding: 2px;"
                                                    aria-label="Clear location search"
                                                    x-cloak>
                                                <svg style="width: 13px; height: 13px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="db-dropdown-items-scroll">
                                        <template x-for="loc in filteredLocationOptions" :key="loc">
                                            <div class="db-dropdown-item" 
                                                 :class="{ 'is-selected': selectedLocation === loc }"
                                                 @click="selectedLocation = (selectedLocation === loc ? '' : loc); open = false;">
                                                <span x-text="loc" style="font-size: 13px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"></span>
                                                <svg x-show="selectedLocation === loc" style="width: 14px; height: 14px; flex-shrink: 0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                                            </div>
                                        </template>

                                        <div x-show="locationSearch && !filteredLocationOptions.includes(locationSearch.trim())" 
                                             class="db-dropdown-item" 
                                             style="font-style: italic; color: #4F48E6;"
                                             @click="selectedLocation = locationSearch.trim(); open = false;">
                                            <span x-text="'Select \'' + locationSearch.trim() + '\''"></span>
                                            <svg style="width: 14px; height: 14px; flex-shrink: 0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                                        </div>

                                        <div x-show="filteredLocationOptions.length === 0 && !locationSearch" 
                                             style="padding: 16px 12px; text-align: center; color: #94A3B8; font-size: 12.5px;">
                                            No locations found
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 3. Category (Searchable Multi-Select Dropdown) -->
                        <div class="int-filter-section">
                            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px;">
                                <label class="int-filter-label" style="margin-bottom: 0;">Category</label>
                                <span x-show="selectedCategories.length > 0" 
                                      @click="selectedCategories = []" 
                                      style="font-size: 11.5px; font-weight: 600; color: #4F48E6; cursor: pointer; text-decoration: underline;" 
                                      x-cloak>
                                    Clear (<span x-text="selectedCategories.length"></span>)
                                </span>
                            </div>

                            <div class="db-dropdown-wrap" x-data="{ open: false }" @click.outside="open = false">
                                <button type="button" 
                                        class="db-dropdown-btn" 
                                        :class="{ 'is-open': open }" 
                                        @click="open = !open; if(open) { $nextTick(() => { const el = $refs.catSearchInput; if(el) el.focus(); }); }"
                                        aria-label="Filter by category">
                                    <span style="display: inline-flex; align-items: center; gap: 8px; min-width: 0; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                        <svg style="width: 15px; height: 15px; color: #94A3B8; flex-shrink: 0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                                        </svg>
                                        <span x-text="getCategoryButtonLabel()" 
                                              :style="selectedCategories.length === 0 ? 'color: #94A3B8;' : 'color: #0F172A; font-weight: 600;'"
                                              style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                            Choose category
                                        </span>
                                    </span>
                                    <div style="display: flex; align-items: center; gap: 6px; flex-shrink: 0;">
                                        <span x-show="selectedCategories.length > 0" 
                                              class="int-count-pill" 
                                              style="background: #EEF2FF; color: #4F48E6; font-weight: 700;"
                                              x-text="selectedCategories.length" 
                                              x-cloak>
                                        </span>
                                        <svg style="width: 16px; height: 16px; color: #94A3B8; transition: transform 0.2s ease; flex-shrink: 0;" :class="{ 'rotate-180': open }" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m6 9 6 6 6-6"/>
                                        </svg>
                                    </div>
                                </button>

                                <div x-show="open" x-transition class="db-dropdown-menu db-dropdown-menu-searchable" style="display: none;">
                                    <div class="db-dropdown-search-box">
                                        <div class="db-dropdown-search-input-wrap">
                                            <svg style="width: 14px; height: 14px; color: #94A3B8; position: absolute; left: 10px; pointer-events: none;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                            </svg>
                                            <input type="text" 
                                                   x-ref="catSearchInput"
                                                   x-model="categorySearch" 
                                                   @click.stop 
                                                   placeholder="Search categories..." 
                                                   class="db-dropdown-search-input">
                                            <button type="button" 
                                                    x-show="categorySearch" 
                                                    @click.stop="categorySearch = ''" 
                                                    style="position: absolute; right: 8px; background: transparent; border: none; color: #94A3B8; cursor: pointer; padding: 2px;"
                                                    aria-label="Clear category search"
                                                    x-cloak>
                                                <svg style="width: 13px; height: 13px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="db-dropdown-items-scroll">
                                        <template x-for="cat in filteredCategoriesList" :key="cat.id">
                                            <label class="db-dropdown-item" 
                                                   style="cursor: pointer; user-select: none; margin-bottom: 2px;"
                                                   :class="{ 'is-selected': selectedCategories.includes(cat.id) }"
                                                   @click.stop>
                                                <div class="int-check-left" style="gap: 9px; min-width: 0;">
                                                    <input type="checkbox" 
                                                           class="int-checkbox" 
                                                           :value="cat.id" 
                                                           x-model="selectedCategories"
                                                           style="width: 16px; height: 16px;">
                                                    <span x-text="cat.name" style="font-size: 13px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"></span>
                                                </div>
                                                <span class="int-count-pill" x-text="getCategoryCount(cat.id)"></span>
                                            </label>
                                        </template>

                                        <div x-show="filteredCategoriesList.length === 0" 
                                             style="padding: 16px 12px; text-align: center; color: #94A3B8; font-size: 12.5px;">
                                            No matching categories found
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 4. Internship Type Checklist (Renamed from Job Type) -->
                        <div class="int-filter-section">
                            <label class="int-filter-label">Internship Type</label>
                            <div class="int-check-list">
                                <template x-for="type in internshipTypesList" :key="type.id">
                                    <label class="int-check-label">
                                        <div class="int-check-left">
                                            <input type="checkbox" 
                                                   class="int-checkbox" 
                                                   :value="type.id" 
                                                   x-model="selectedTypes">
                                            <span x-text="type.name"></span>
                                        </div>
                                        <span class="int-count-pill" x-text="getTypeCount(type.id)"></span>
                                    </label>
                                </template>
                            </div>
                        </div>

                        <!-- 5. Date Posted Checklist -->
                        <div class="int-filter-section">
                            <label class="int-filter-label">Date Posted</label>
                            <div class="int-check-list">
                                <template x-for="dp in datePostedOptions" :key="dp.id">
                                    <label class="int-check-label">
                                        <div class="int-check-left">
                                            <input type="radio" 
                                                   name="date_posted_filter" 
                                                   class="int-checkbox" 
                                                   :value="dp.id" 
                                                   x-model="selectedDatePosted">
                                            <span x-text="dp.name"></span>
                                        </div>
                                        <span class="int-count-pill" x-text="getDateCount(dp.id)"></span>
                                    </label>
                                </template>
                            </div>
                        </div>

                        <!-- 6. Stipend Range Slider (Naira ₦ Standard) -->
                        <div class="int-filter-section">
                            <label class="int-filter-label">Monthly Stipend</label>
                            <div class="int-slider-wrap">
                                <input type="range" 
                                       class="int-range-slider" 
                                       min="0" 
                                       max="500000" 
                                       step="25000" 
                                       x-model="maxStipendSlider">
                                
                                <div class="int-slider-actions">
                                    <span class="int-slider-display" x-text="'₦0 - ₦' + Number(maxStipendSlider).toLocaleString() + '/mo'"></span>
                                    <button type="button" 
                                            class="int-btn-apply-slider" 
                                            @click="appliedMaxStipend = Number(maxStipendSlider); $dispatch('show-toast', { text: 'Stipend filter applied.', type: 'info' })">
                                        Apply
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- 7. Tags Pill Cloud -->
                        <div class="int-filter-section">
                            <label class="int-filter-label">Popular Skills & Tags</label>
                            <div class="int-tags-cloud">
                                <template x-for="tag in availableTags" :key="tag">
                                    <button type="button" 
                                            class="int-tag-pill" 
                                            :class="{ 'is-active': selectedTags.includes(tag) }"
                                            @click="toggleTag(tag)">
                                        <span x-text="tag"></span>
                                    </button>
                                </template>
                            </div>
                        </div>

                    </div>

                    <!-- ════ Jenny AI Promo Card (Replaces "WE ARE HIRING") ════ -->
                    <div class="int-jenny-promo-card">
                        <div class="int-jenny-promo-header">
                            <div class="int-jenny-avatar-box">
                                <img src="<?= asset('assets/images/jenny_ai/jenny_ai_hi.png') ?>" alt="Jenny AI Mascot">
                            </div>
                            <div>
                                <h4 class="int-jenny-headline">Can't find the right fit?</h4>
                                <span class="int-jenny-badge">AI Internship Matcher</span>
                            </div>
                        </div>
                        <p class="int-jenny-subtext">
                            Jenny can match you with internships based on your skills and goals, no scrolling required.
                        </p>
                        <button type="button" 
                                class="int-jenny-cta-btn" 
                                @click="openJennyChat()">
                            <svg style="width: 15px; height: 15px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                            </svg>
                            <span>Chat with Jenny</span>
                        </button>
                    </div>

                </aside>

                <!-- ════ Right Column: Search Results List ════ -->
                <section class="int-results-col">

                    <!-- Top Bar: Result Counts & Sort By Dropdown -->
                    <div class="int-results-top-bar">
                        <p class="int-results-count-text">
                            Showing <strong x-text="resultsStart">1</strong>–<strong x-text="resultsEnd">10</strong> of <strong x-text="filteredTotal">10</strong> results
                        </p>

                        <!-- Sort By Dropdown Component -->
                        <div class="int-sort-wrap" x-data="{ sortOpen: false }" @click.outside="sortOpen = false">
                            <span class="int-sort-label">Sort by:</span>
                            <div class="db-dropdown-wrap" style="width: 170px;">
                                <button type="button" 
                                        class="db-dropdown-btn" 
                                        :class="{ 'is-open': sortOpen }" 
                                        style="height: 38px; padding: 0 12px; font-size: 13px;"
                                        @click="sortOpen = !sortOpen">
                                    <span x-text="getSortLabel(sortBy)" style="font-weight: 600; color: #0F172A;"></span>
                                    <svg style="width: 15px; height: 15px; color: #94A3B8; transition: transform 0.2s ease;" :class="{ 'rotate-180': sortOpen }" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="m6 9 6 6 6-6"/></svg>
                                </button>

                                <div x-show="sortOpen" x-transition class="db-dropdown-menu" style="display: none; width: 180px; right: 0; left: auto;">
                                    <div class="db-dropdown-item" :class="{ 'is-selected': sortBy === 'latest' }" @click="sortBy = 'latest'; sortOpen = false;">
                                        <span>Latest</span>
                                        <svg x-show="sortBy === 'latest'" style="width: 13px; height: 13px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                                    </div>
                                    <div class="db-dropdown-item" :class="{ 'is-selected': sortBy === 'stipend' }" @click="sortBy = 'stipend'; sortOpen = false;">
                                        <span>Highest Stipend</span>
                                        <svg x-show="sortBy === 'stipend'" style="width: 13px; height: 13px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                                    </div>
                                    <div class="db-dropdown-item" :class="{ 'is-selected': sortBy === 'relevance' }" @click="sortBy = 'relevance'; sortOpen = false;">
                                        <span>Most Relevant</span>
                                        <svg x-show="sortBy === 'relevance'" style="width: 13px; height: 13px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                                    </div>
                                    <div class="db-dropdown-item" :class="{ 'is-selected': sortBy === 'company' }" @click="sortBy = 'company'; sortOpen = false;">
                                        <span>Company Name</span>
                                        <svg x-show="sortBy === 'company'" style="width: 13px; height: 13px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Internship Cards List -->
                    <div class="int-cards-list" x-show="paginatedInternships.length > 0">
                        <template x-for="item in paginatedInternships" :key="item.id">
                            <article class="int-result-card">
                                
                                <!-- Top Row: Posted Timestamp & Save Bookmark Button -->
                                <div class="int-card-top-row">
                                    <div class="int-time-badge">
                                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <circle cx="12" cy="12" r="9" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 7v5l3 2" />
                                        </svg>
                                        <span x-text="item.posted"></span>
                                    </div>

                                    <button type="button" 
                                            class="int-bookmark-btn" 
                                            :class="{ 'is-saved': savedItems[item.id] }"
                                            @click="toggleSave(item)"
                                            :aria-label="savedItems[item.id] ? 'Unsave role' : 'Save role'">
                                        <svg x-show="!savedItems[item.id]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                                        </svg>
                                        <svg x-show="savedItems[item.id]" style="display: none;" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                                        </svg>
                                    </button>
                                </div>

                                <!-- Body Row: Company Logo, Role Title & Company Name Link -->
                                <div class="int-card-body-row">
                                    <a :href="'<?= url('/company/') ?>' + (item.companySlug || 'techflow')" 
                                       class="int-company-logo"
                                       :style="item.logoBg ? 'background-color:' + item.logoBg + '; color:' + (item.logoColor || '#FFFFFF') : ''"
                                       title="View Company Profile">
                                        <template x-if="item.logoUrl">
                                            <img :src="item.logoUrl" :alt="item.company" loading="lazy">
                                        </template>
                                        <template x-if="!item.logoUrl">
                                            <span x-text="item.initial || item.company.charAt(0)"></span>
                                        </template>
                                    </a>

                                    <div class="int-card-body-titles">
                                        <h2 class="int-card-role-title">
                                            <a :href="'<?= url('/internship/') ?>' + item.slug" class="int-card-role-link" x-text="item.title"></a>
                                        </h2>
                                        <p class="int-card-company-name">
                                            <a :href="'<?= url('/company/') ?>' + (item.companySlug || 'techflow')" class="int-card-company-link" x-text="item.company"></a>
                                        </p>
                                    </div>
                                </div>

                                <!-- Details Row: Category, Type, Naira Stipend, Location -->
                                <div class="int-details-row">
                                    <div class="int-detail-item">
                                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                        </svg>
                                        <span x-text="item.categoryLabel"></span>
                                    </div>

                                    <div class="int-detail-item">
                                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <circle cx="12" cy="12" r="9"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2"/>
                                        </svg>
                                        <span x-text="item.typeLabel"></span>
                                    </div>

                                    <div class="int-detail-item">
                                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                        <span class="int-detail-stipend" x-text="item.stipendNaira"></span>
                                    </div>

                                    <div class="int-detail-item">
                                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                        <span x-text="item.location"></span>
                                    </div>
                                </div>

                                <!-- Bottom Row: Description Snippet & View Details Button -->
                                <div class="int-card-bottom-actions">
                                    <p class="int-card-desc-snippet" x-text="item.description"></p>
                                    <a :href="'<?= url('/internship/') ?>' + item.slug" class="int-btn-view-details">
                                        <span>Apply</span>
                                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </a>
                                </div>

                            </article>
                        </template>
                    </div>

                    <!-- Clean Empty State if Zero Matches -->
                    <div class="int-empty-state" x-show="filteredTotal === 0">
                        <div class="int-empty-icon">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <h3 class="int-empty-title">No Internships Found</h3>
                        <p class="int-empty-desc">We couldn't find any opportunities matching your active filters. Try adjusting your search query, location, or clearing specific filter tags.</p>
                        <button type="button" @click="resetAllFilters()" class="int-btn-view-details" style="display: inline-flex;">
                            <span>Reset All Filters</span>
                        </button>
                    </div>

                    <!-- Pagination -->
                    <div class="int-pagination-wrap" x-show="totalPages > 1">
                        <button type="button" 
                                class="int-page-nav-text-btn" 
                                :disabled="currentPage === 1" 
                                @click="currentPage--; window.scrollTo({top: 0, behavior: 'smooth'});" 
                                aria-label="Previous page">
                            Previous
                        </button>

                        <template x-for="p in totalPages" :key="p">
                            <button type="button" 
                                    class="int-page-btn" 
                                    :class="{ 'is-active': currentPage === p }" 
                                    @click="currentPage = p; window.scrollTo({top: 0, behavior: 'smooth'});" 
                                    x-text="p">
                            </button>
                        </template>

                        <button type="button" 
                                class="int-page-nav-text-btn" 
                                :disabled="currentPage === totalPages" 
                                @click="currentPage++; window.scrollTo({top: 0, behavior: 'smooth'});" 
                                aria-label="Next page">
                            Next
                        </button>
                    </div>

                </section>

            </div>

        </div>
    </main>

</div>

<script>
/**
 * Internship Search Results Page Manager (Alpine.js)
 * Implements reactive multi-filter logic, Naira stipends, sort orders, and pagination.
 */
function internshipSearchManager(config) {
    return {
        searchTitle: config.initialSearch || '',
        selectedLocation: config.initialLocation || '',
        locationSearch: '',
        selectedCategories: config.initialCategory ? [config.initialCategory.toLowerCase()] : [],
        categorySearch: '',
        selectedTypes: [],
        selectedDatePosted: 'all',
        maxStipendSlider: 500000,
        appliedMaxStipend: 500000,
        selectedTags: [],
        sortBy: 'latest',
        currentPage: 1,
        pageSize: 6,
        mobileFiltersOpen: false,
        savedItems: {
            'nexura-payments': true
        },

        // Locations List (Local/Regional Nigerian Cities + Remote + International Hubs)
        locationOptions: [
            'All Locations',
            // Nigerian Cities
            'Lagos, Nigeria',
            'Abuja, Nigeria',
            'Port Harcourt, Nigeria',
            'Ibadan, Nigeria',
            'Enugu, Nigeria',
            'Kano, Nigeria',
            'Benin City, Nigeria',
            'Calabar, Nigeria',
            // Remote
            'Remote',
            // Outside / International Locations
            'United Kingdom',
            'Canada',
            'United States',
            'Germany',
            'Ghana',
            'Kenya',
            'Rwanda',
            'South Africa',
            'Australia',
            'United Arab Emirates'
        ],

        // Comprehensive Categories List (24 Core Industry Disciplines)
        categoriesList: [
            { id: 'tech', name: 'Tech/Engineering' },
            { id: 'design', name: 'Design & Creative' },
            { id: 'finance', name: 'Financial Services' },
            { id: 'marketing', name: 'Marketing & Growth' },
            { id: 'data', name: 'Data Science & AI' },
            { id: 'commerce', name: 'Commerce & Retail' },
            { id: 'telecom', name: 'Telecommunications' },
            { id: 'hospitality', name: 'Hotels & Tourism' },
            { id: 'education', name: 'Education Technology' },
            { id: 'healthcare', name: 'Healthcare & Medical' },
            { id: 'product', name: 'Product Management' },
            { id: 'cybersecurity', name: 'Cybersecurity & InfoSec' },
            { id: 'cloud', name: 'Cloud & DevOps' },
            { id: 'legal', name: 'Legal & Corporate Affairs' },
            { id: 'hr', name: 'Human Resources & People' },
            { id: 'media', name: 'Media & Journalism' },
            { id: 'logistics', name: 'Logistics & Supply Chain' },
            { id: 'agriculture', name: 'Agriculture & Agtech' },
            { id: 'energy', name: 'Energy & Sustainability' },
            { id: 'realestate', name: 'Real Estate & Property' },
            { id: 'consulting', name: 'Business Consulting' },
            { id: 'architecture', name: 'Architecture & Construction' },
            { id: 'content', name: 'Content & Copywriting' },
            { id: 'qa', name: 'Quality Assurance & Testing' }
        ],

        // Internship Types (Renamed from Job Type per instructions)
        internshipTypesList: [
            { id: 'full-time', name: 'Full-Time' },
            { id: 'part-time', name: 'Part-Time' },
            { id: 'remote', name: 'Remote' },
            { id: 'hybrid', name: 'Hybrid' },
            { id: 'onsite', name: 'On-site' }
        ],

        // Date Posted Options
        datePostedOptions: [
            { id: 'all', name: 'All' },
            { id: 'hour', name: 'Last Hour' },
            { id: '24h', name: 'Last 24 Hours' },
            { id: '7d', name: 'Last 7 Days' },
            { id: '30d', name: 'Last 30 Days' }
        ],

        // Available Tag Cloud Items
        availableTags: [
            'Engineering', 'Design', 'UI/UX', 'Marketing', 'Management', 
            'Python', 'React', 'Figma', 'Fintech', 'Data Analysis', 'Soft Skills'
        ],

        // Comprehensive Mock Dataset of Internships (Naira ₦ currency, real companies)
        allInternships: [
            {
                id: 'techflow-security',
                slug: 'forward-security-engineering-intern',
                title: 'Forward Security Engineering Intern',
                company: 'TechFlow Systems',
                companySlug: 'techflow',
                initial: 'T',
                logoBg: '#000623',
                logoColor: '#FFFFFF',
                logoUrl: 'https://res.cloudinary.com/weburea/image/upload/v1789424092/internConnect/images/hk_logo.jpg',
                category: 'tech',
                categoryLabel: 'Tech/Engineering',
                type: 'full-time',
                typeLabel: 'Full-Time',
                workMode: 'hybrid',
                location: 'Lagos, Nigeria',
                city: 'Lagos',
                country: 'Nigeria',
                stipendValue: 350000,
                stipendNaira: '₦280,000 - ₦350,000/mo',
                posted: '10 min ago',
                postedMinutes: 10,
                tags: ['Engineering', 'Python', 'Security'],
                description: 'Build robust cloud access gateways, analyze network anomalies, and collaborate with enterprise infrastructure engineers.'
            },
            {
                id: 'nexura-payments',
                slug: 'software-engineer-intern',
                title: 'Software Engineer Intern (Payments Core)',
                company: 'Nexura',
                companySlug: 'nexura',
                initial: 'N',
                logoBg: '#0F172A',
                logoColor: '#FFFFFF',
                logoUrl: '',
                category: 'finance',
                categoryLabel: 'Financial Services',
                type: 'full-time',
                typeLabel: 'Full-Time',
                workMode: 'remote',
                location: 'Lagos, Nigeria',
                city: 'Lagos',
                country: 'Nigeria',
                stipendValue: 380000,
                stipendNaira: '₦320,000 - ₦400,000/mo',
                posted: '12 min ago',
                postedMinutes: 12,
                tags: ['Engineering', 'React', 'Fintech'],
                description: 'Join our high-scale financial infrastructure team to build real-time transaction pipelines and developer APIs.'
            },
            {
                id: 'airbnb-product-design',
                slug: 'product-designer-ux-ui-intern',
                title: 'Product Design & UX/UI Intern',
                company: 'Airbnb',
                companySlug: 'airbnb',
                initial: 'A',
                logoBg: '#FFE4E6',
                logoColor: '#E11D48',
                logoUrl: '',
                category: 'design',
                categoryLabel: 'Design & Creative',
                type: 'part-time',
                typeLabel: 'Part-Time',
                workMode: 'hybrid',
                location: 'Lagos, Nigeria',
                city: 'Lagos',
                country: 'Nigeria',
                stipendValue: 300000,
                stipendNaira: '₦250,000 - ₦320,000/mo',
                posted: '15 min ago',
                postedMinutes: 15,
                tags: ['Design', 'UI/UX', 'Figma', 'Web Design'],
                description: 'Craft frictionless booking journeys, iterate on host management tools, and participate in global design critique sessions.'
            },
            {
                id: 'wellspring-health',
                slug: 'senior-product-design-intern',
                title: 'Health Informatics & Systems Intern',
                company: 'WellSpring Care',
                companySlug: 'wellspring-care',
                initial: 'W',
                logoBg: '#D1FAE5',
                logoColor: '#059669',
                logoUrl: '',
                category: 'tech',
                categoryLabel: 'Tech/Engineering',
                type: 'full-time',
                typeLabel: 'Full-Time',
                workMode: 'onsite',
                location: 'Abuja, Nigeria',
                city: 'Abuja',
                country: 'Nigeria',
                stipendValue: 240000,
                stipendNaira: '₦200,000 - ₦260,000/mo',
                posted: '24 min ago',
                postedMinutes: 24,
                tags: ['Data Analysis', 'Engineering', 'Soft Skills'],
                description: 'Support electronic health record workflows, analyze clinician usage metrics, and ensure healthcare data integrity.'
            },
            {
                id: 'brightwave-marketing',
                slug: 'growth-marketing-intern',
                title: 'Corporate Tactics & Growth Facilitator',
                company: 'BrightWave',
                companySlug: 'brightwave',
                initial: 'B',
                logoBg: '#FEF3C7',
                logoColor: '#D97706',
                logoUrl: '',
                category: 'marketing',
                categoryLabel: 'Marketing & Growth',
                type: 'full-time',
                typeLabel: 'Full-Time',
                workMode: 'remote',
                location: 'Port Harcourt, Nigeria',
                city: 'Port Harcourt',
                country: 'Nigeria',
                stipendValue: 220000,
                stipendNaira: '₦180,000 - ₦240,000/mo',
                posted: '26 min ago',
                postedMinutes: 26,
                tags: ['Marketing', 'Management', 'Soft Skills'],
                description: 'Scale content distribution engines, analyze conversion funnels, and coordinate digital engagement programs.'
            },
            {
                id: 'finance-accounts',
                slug: 'corporate-finance-analyst-intern',
                title: 'Forward Accounts & Financial Consultant',
                company: 'Miller Group',
                companySlug: 'techflow',
                initial: 'M',
                logoBg: '#E0E7FF',
                logoColor: '#4338CA',
                logoUrl: '',
                category: 'finance',
                categoryLabel: 'Financial Services',
                type: 'full-time',
                typeLabel: 'Full-Time',
                workMode: 'onsite',
                location: 'Lagos, Nigeria',
                city: 'Lagos',
                country: 'Nigeria',
                stipendValue: 260000,
                stipendNaira: '₦220,000 - ₦280,000/mo',
                posted: '30 min ago',
                postedMinutes: 30,
                tags: ['Fintech', 'Management'],
                description: 'Assist in budget allocations, reconcile transactional ledgers, and prepare weekly management cash reports.'
            },
            {
                id: 'kiro-cloud-ops',
                slug: 'cloud-systems-devops-intern',
                title: 'District Intranet & Cloud Ops Director',
                company: 'Kiro Labs',
                companySlug: 'kiro-labs',
                initial: 'K',
                logoBg: '#EDE9FE',
                logoColor: '#7C3AED',
                logoUrl: '',
                category: 'tech',
                categoryLabel: 'Tech/Engineering',
                type: 'full-time',
                typeLabel: 'Full-Time',
                workMode: 'remote',
                location: 'Remote',
                city: 'Remote',
                country: 'Remote',
                stipendValue: 420000,
                stipendNaira: '₦350,000 - ₦450,000/mo',
                posted: '45 min ago',
                postedMinutes: 45,
                tags: ['Engineering', 'Python'],
                description: 'Deploy Kubernetes pods, automate CI/CD pipeline triggers, and ensure 99.9% uptime across production clusters.'
            },
            {
                id: 'zod-creative-brand',
                slug: 'creative-brand-designer-intern',
                title: 'Web & Interface Design Intern',
                company: 'Zod Studio',
                companySlug: 'zod-studio',
                initial: 'Z',
                logoBg: '#FCE7F3',
                logoColor: '#BE185D',
                logoUrl: '',
                category: 'design',
                categoryLabel: 'Design & Creative',
                type: 'hybrid',
                typeLabel: 'Hybrid',
                workMode: 'hybrid',
                location: 'Lagos, Nigeria',
                city: 'Lagos',
                country: 'Nigeria',
                stipendValue: 200000,
                stipendNaira: '₦160,000 - ₦220,000/mo',
                posted: '2 hours ago',
                postedMinutes: 120,
                tags: ['Design', 'UI/UX', 'Figma', 'Web Design'],
                description: 'Create high-impact brand identities, typography guidelines, and design responsive layouts for tech startups.'
            },
            {
                id: 'commerce-logistics',
                slug: 'ecommerce-operations-intern',
                title: 'Retail Commerce & Operations Planner',
                company: 'Commerce Hub Africa',
                companySlug: 'techflow',
                initial: 'C',
                logoBg: '#FEF3C7',
                logoColor: '#B45309',
                logoUrl: '',
                category: 'commerce',
                categoryLabel: 'Commerce & Retail',
                type: 'full-time',
                typeLabel: 'Full-Time',
                workMode: 'onsite',
                location: 'Ibadan, Nigeria',
                city: 'Ibadan',
                country: 'Nigeria',
                stipendValue: 180000,
                stipendNaira: '₦150,000 - ₦200,000/mo',
                posted: '3 hours ago',
                postedMinutes: 180,
                tags: ['Management', 'Soft Skills'],
                description: 'Optimize merchant fulfillment lifecycles, monitor warehouse dispatch metrics, and handle store inquiries.'
            },
            {
                id: 'telco-network',
                slug: 'telecom-network-systems-intern',
                title: 'Telecommunications Network Intern',
                company: 'Telco Networks',
                companySlug: 'techflow',
                initial: 'T',
                logoBg: '#DBEAFE',
                logoColor: '#1D4ED8',
                logoUrl: '',
                category: 'telecom',
                categoryLabel: 'Telecommunications',
                type: 'full-time',
                typeLabel: 'Full-Time',
                workMode: 'onsite',
                location: 'Abuja, Nigeria',
                city: 'Abuja',
                country: 'Nigeria',
                stipendValue: 230000,
                stipendNaira: '₦200,000 - ₦250,000/mo',
                posted: '5 hours ago',
                postedMinutes: 300,
                tags: ['Engineering', 'Data Analysis'],
                description: 'Perform broadband spectrum tests, troubleshoot tower optical routers, and assist field telecommunication leads.'
            },
            {
                id: 'edtech-curriculum',
                slug: 'education-technology-intern',
                title: 'Educational Technology & Learning Intern',
                company: 'EdTech Africa',
                companySlug: 'techflow',
                initial: 'E',
                logoBg: '#E0F2FE',
                logoColor: '#0284C7',
                logoUrl: '',
                category: 'education',
                categoryLabel: 'Education Technology',
                type: 'remote',
                typeLabel: 'Remote',
                workMode: 'remote',
                location: 'Remote',
                city: 'Remote',
                country: 'Remote',
                stipendValue: 190000,
                stipendNaira: '₦160,000 - ₦210,000/mo',
                posted: '1 day ago',
                postedMinutes: 1440,
                tags: ['Soft Skills', 'Figma'],
                description: 'Create interactive STEM modules, assist teachers on student learning portals, and evaluate classroom feedback.'
            },
            {
                id: 'tourism-hospitality',
                slug: 'hospitality-guest-experience-intern',
                title: 'Hospitality & Tourism Experience Lead',
                company: 'Grand Horizon Resorts',
                companySlug: 'techflow',
                initial: 'G',
                logoBg: '#F3E8FF',
                logoColor: '#7E22CE',
                logoUrl: '',
                category: 'hospitality',
                categoryLabel: 'Hotels & Tourism',
                type: 'full-time',
                typeLabel: 'Full-Time',
                workMode: 'onsite',
                location: 'Lagos, Nigeria',
                city: 'Lagos',
                country: 'Nigeria',
                stipendValue: 175000,
                stipendNaira: '₦140,000 - ₦180,000/mo',
                posted: '2 days ago',
                postedMinutes: 2880,
                tags: ['Management', 'Soft Skills'],
                description: 'Coordinate VIP visitor check-ins, streamline concierge reservations, and ensure five-star hospitality standards.'
            },
            // International Opportunities (UK, Canada, US, Ghana, Kenya, Rwanda)
            {
                id: 'monzo-fintech-uk',
                slug: 'fintech-software-engineering-intern',
                title: 'Fintech Core Systems Intern',
                company: 'Monzo',
                companySlug: 'techflow',
                initial: 'M',
                logoBg: '#FFE4E6',
                logoColor: '#E11D48',
                logoUrl: '',
                category: 'finance',
                categoryLabel: 'Financial Services',
                type: 'full-time',
                typeLabel: 'Full-Time',
                workMode: 'hybrid',
                location: 'London, United Kingdom',
                city: 'London',
                country: 'United Kingdom',
                stipendValue: 480000,
                stipendNaira: '₦400,000 - ₦550,000/mo',
                posted: '3 days ago',
                postedMinutes: 4320,
                tags: ['Engineering', 'Fintech', 'Python'],
                description: 'Collaborate with UK distributed engineering teams on core ledger scaling, microservice resilience, and automated settlement protocols.'
            },
            {
                id: 'shopify-cloud-canada',
                slug: 'cloud-infrastructure-devops-intern',
                title: 'Global Cloud Systems & DevOps Intern',
                company: 'Shopify',
                companySlug: 'techflow',
                initial: 'S',
                logoBg: '#ECFDF5',
                logoColor: '#059669',
                logoUrl: '',
                category: 'cloud',
                categoryLabel: 'Cloud & DevOps',
                type: 'full-time',
                typeLabel: 'Full-Time',
                workMode: 'remote',
                location: 'Toronto, Canada',
                city: 'Toronto',
                country: 'Canada',
                stipendValue: 450000,
                stipendNaira: '₦380,000 - ₦500,000/mo',
                posted: '4 days ago',
                postedMinutes: 5760,
                tags: ['Engineering', 'Security', 'Management'],
                description: 'Build automated Kubernetes ingress controllers, observe distributed tracer logs, and optimize global merchant CDN caching.'
            },
            {
                id: 'deepmind-ai-us',
                slug: 'ai-machine-learning-research-intern',
                title: 'Generative AI & LLM Systems Intern',
                company: 'Anthropic Labs',
                companySlug: 'techflow',
                initial: 'A',
                logoBg: '#F3E8FF',
                logoColor: '#7E22CE',
                logoUrl: '',
                category: 'data',
                categoryLabel: 'Data Science & AI',
                type: 'full-time',
                typeLabel: 'Full-Time',
                workMode: 'hybrid',
                location: 'New York, United States',
                city: 'New York',
                country: 'United States',
                stipendValue: 500000,
                stipendNaira: '₦450,000 - ₦600,000/mo',
                posted: '5 days ago',
                postedMinutes: 7200,
                tags: ['Data Analysis', 'Python', 'Engineering'],
                description: 'Conduct inference benchmarks on frontier neural architectures, evaluate agentic task trajectories, and build semantic retrieval pipelines.'
            },
            {
                id: 'flutterwave-ghana',
                slug: 'pan-african-product-operations-intern',
                title: 'Pan-African Product Operations Intern',
                company: 'Flutterwave',
                companySlug: 'techflow',
                initial: 'F',
                logoBg: '#FEF3C7',
                logoColor: '#D97706',
                logoUrl: '',
                category: 'product',
                categoryLabel: 'Product Management',
                type: 'full-time',
                typeLabel: 'Full-Time',
                workMode: 'onsite',
                location: 'Accra, Ghana',
                city: 'Accra',
                country: 'Ghana',
                stipendValue: 280000,
                stipendNaira: '₦240,000 - ₦320,000/mo',
                posted: '6 days ago',
                postedMinutes: 8640,
                tags: ['Management', 'Fintech', 'Soft Skills'],
                description: 'Track cross-border payment merchant metrics, coordinate localized KYC rollout flows, and assist West Africa expansion leads.'
            },
            {
                id: 'cyber-kenya',
                slug: 'cybersecurity-defense-operations-intern',
                title: 'Cybersecurity & Threat Analysis Intern',
                company: 'Safaricom Cloud',
                companySlug: 'techflow',
                initial: 'S',
                logoBg: '#DCFCE7',
                logoColor: '#166534',
                logoUrl: '',
                category: 'cybersecurity',
                categoryLabel: 'Cybersecurity & InfoSec',
                type: 'full-time',
                typeLabel: 'Full-Time',
                workMode: 'remote',
                location: 'Nairobi, Kenya',
                city: 'Nairobi',
                country: 'Kenya',
                stipendValue: 310000,
                stipendNaira: '₦260,000 - ₦350,000/mo',
                posted: '1 week ago',
                postedMinutes: 10080,
                tags: ['Security', 'Python', 'Engineering'],
                description: 'Analyze telemetry logs from firewall endpoints, investigate unauthorized intrusion alerts, and draft incident response playbooks.'
            },
            {
                id: 'health-rwanda',
                slug: 'health-systems-digital-informatics-intern',
                title: 'Health Systems & Digital Informatics Intern',
                company: 'Babyl Health',
                companySlug: 'techflow',
                initial: 'B',
                logoBg: '#E0F2FE',
                logoColor: '#0369A1',
                logoUrl: '',
                category: 'healthcare',
                categoryLabel: 'Healthcare & Medical',
                type: 'hybrid',
                typeLabel: 'Hybrid',
                workMode: 'hybrid',
                location: 'Kigali, Rwanda',
                city: 'Kigali',
                country: 'Rwanda',
                stipendValue: 270000,
                stipendNaira: '₦220,000 - ₦300,000/mo',
                posted: '1 week ago',
                postedMinutes: 10080,
                tags: ['Data Analysis', 'Soft Skills'],
                description: 'Support telehealth appointment triage workflows, verify diagnostic data entry standards, and draft clinician training guides.'
            }
        ],

        // Dynamic Header Title Computation
        get headerTitle() {
            if (this.searchTitle && this.selectedLocation && this.selectedLocation !== 'All Locations') {
                return `Results for "${this.searchTitle}" in ${this.selectedLocation.split(',')[0]}`;
            } else if (this.searchTitle) {
                return `Results for "${this.searchTitle}"`;
            } else if (this.selectedLocation && this.selectedLocation !== 'All Locations') {
                return `Internships in ${this.selectedLocation.split(',')[0]}`;
            } else if (this.selectedCategories.length === 1) {
                const cat = this.categoriesList.find(c => c.id === this.selectedCategories[0]);
                if (cat) return `${cat.name} Internships`;
            }
            return 'Find Internships';
        },

        // Helper count getters for categories, types, and date posted
        getCategoryCount(catId) {
            return this.allInternships.filter(i => i.category === catId).length;
        },

        getTypeCount(typeId) {
            return this.allInternships.filter(i => {
                if (typeId === 'remote') return i.workMode === 'remote' || i.type === 'remote';
                if (typeId === 'hybrid') return i.workMode === 'hybrid';
                if (typeId === 'onsite') return i.workMode === 'onsite';
                return i.type === typeId;
            }).length;
        },

        getDateCount(dpId) {
            if (dpId === 'all') return this.allInternships.length;
            if (dpId === 'hour') return this.allInternships.filter(i => i.postedMinutes <= 60).length;
            if (dpId === '24h') return this.allInternships.filter(i => i.postedMinutes <= 1440).length;
            if (dpId === '7d') return this.allInternships.filter(i => i.postedMinutes <= 10080).length;
            if (dpId === '30d') return this.allInternships.filter(i => i.postedMinutes <= 43200).length;
            return 0;
        },

        // Filtered Lists for Dropdown Search
        get filteredLocationOptions() {
            if (!this.locationSearch.trim()) {
                return this.locationOptions;
            }
            const q = this.locationSearch.trim().toLowerCase();
            return this.locationOptions.filter(loc => loc.toLowerCase().includes(q));
        },

        get filteredCategoriesList() {
            if (!this.categorySearch.trim()) {
                return this.categoriesList;
            }
            const q = this.categorySearch.trim().toLowerCase();
            return this.categoriesList.filter(cat => cat.name.toLowerCase().includes(q));
        },

        getCategoryButtonLabel() {
            if (this.selectedCategories.length === 0) {
                return 'Choose category';
            }
            if (this.selectedCategories.length === 1) {
                const found = this.categoriesList.find(c => c.id === this.selectedCategories[0]);
                return found ? found.name : '1 Category Selected';
            }
            return `${this.selectedCategories.length} Categories Selected`;
        },

        // Multi-Filter Pipeline
        get filteredInternships() {
            let list = this.allInternships.filter(item => {
                // 1. Search Query (Title, Company, Tags, Description, Category)
                if (this.searchTitle.trim()) {
                    const q = this.searchTitle.trim().toLowerCase();
                    const combined = (item.title + ' ' + item.company + ' ' + (item.categoryLabel || '') + ' ' + item.description + ' ' + item.tags.join(' ')).toLowerCase();
                    const directMatch = combined.includes(q);
                    const words = q.split(/\s+/).filter(w => w.length > 2);
                    const wordMatch = words.length > 0 && words.some(w => combined.includes(w));
                    if (!directMatch && !wordMatch) return false;
                }

                // 2. Location (City, State, Country, or Remote)
                if (this.selectedLocation && this.selectedLocation !== 'All Locations') {
                    if (this.selectedLocation === 'Remote') {
                        if (item.workMode !== 'remote' && item.location !== 'Remote') return false;
                    } else {
                        const locNeedle = this.selectedLocation.toLowerCase().split(',')[0].trim();
                        const matchLoc = item.location.toLowerCase().includes(locNeedle);
                        const matchCountry = item.country && item.country.toLowerCase().includes(locNeedle);
                        const matchCity = item.city && item.city.toLowerCase().includes(locNeedle);
                        if (!matchLoc && !matchCountry && !matchCity) return false;
                    }
                }

                // 3. Category Checklist
                if (this.selectedCategories.length > 0) {
                    if (!this.selectedCategories.includes(item.category)) return false;
                }

                // 4. Internship Type Checklist
                if (this.selectedTypes.length > 0) {
                    const match = this.selectedTypes.some(t => {
                        if (t === 'remote') return item.workMode === 'remote' || item.type === 'remote';
                        if (t === 'hybrid') return item.workMode === 'hybrid';
                        if (t === 'onsite') return item.workMode === 'onsite';
                        return item.type === t;
                    });
                    if (!match) return false;
                }

                // 5. Date Posted
                if (this.selectedDatePosted !== 'all') {
                    if (this.selectedDatePosted === 'hour' && item.postedMinutes > 60) return false;
                    if (this.selectedDatePosted === '24h' && item.postedMinutes > 1440) return false;
                    if (this.selectedDatePosted === '7d' && item.postedMinutes > 10080) return false;
                    if (this.selectedDatePosted === '30d' && item.postedMinutes > 43200) return false;
                }

                // 6. Stipend Ceiling (Naira)
                if (item.stipendValue > this.appliedMaxStipend) {
                    return false;
                }

                // 7. Tags Cloud
                if (this.selectedTags.length > 0) {
                    const hasAnyTag = this.selectedTags.some(t => item.tags.includes(t));
                    if (!hasAnyTag) return false;
                }

                return true;
            });

            // Sorting
            if (this.sortBy === 'latest') {
                list.sort((a, b) => a.postedMinutes - b.postedMinutes);
            } else if (this.sortBy === 'stipend') {
                list.sort((a, b) => b.stipendValue - a.stipendValue);
            } else if (this.sortBy === 'company') {
                list.sort((a, b) => a.company.localeCompare(b.company));
            } else if (this.sortBy === 'relevance') {
                list.sort((a, b) => a.id.localeCompare(b.id));
            }

            return list;
        },

        get filteredTotal() {
            return this.filteredInternships.length;
        },

        get totalPages() {
            return Math.ceil(this.filteredTotal / this.pageSize) || 1;
        },

        get paginatedInternships() {
            const start = (this.currentPage - 1) * this.pageSize;
            return this.filteredInternships.slice(start, start + this.pageSize);
        },

        get resultsStart() {
            if (this.filteredTotal === 0) return 0;
            return (this.currentPage - 1) * this.pageSize + 1;
        },

        get resultsEnd() {
            return Math.min(this.currentPage * this.pageSize, this.filteredTotal);
        },

        getSortLabel(val) {
            const map = {
                'latest': 'Latest',
                'stipend': 'Highest Stipend',
                'relevance': 'Most Relevant',
                'company': 'Company Name'
            };
            return map[val] || 'Latest';
        },

        toggleTag(tag) {
            if (this.selectedTags.includes(tag)) {
                this.selectedTags = this.selectedTags.filter(t => t !== tag);
            } else {
                this.selectedTags.push(tag);
            }
            this.currentPage = 1;
        },

        toggleSave(item) {
            this.savedItems[item.id] = !this.savedItems[item.id];
            const msg = this.savedItems[item.id] 
                ? `"${item.title}" added to your saved roles!` 
                : `"${item.title}" removed from saved list.`;
            this.$dispatch('show-toast', { text: msg, type: 'info' });
        },

        resetAllFilters() {
            this.searchTitle = '';
            this.selectedLocation = '';
            this.locationSearch = '';
            this.selectedCategories = [];
            this.categorySearch = '';
            this.selectedTypes = [];
            this.selectedDatePosted = 'all';
            this.maxStipendSlider = 500000;
            this.appliedMaxStipend = 500000;
            this.selectedTags = [];
            this.sortBy = 'latest';
            this.currentPage = 1;
            this.$dispatch('show-toast', { text: 'All search filters reset.', type: 'info' });
        },

        openJennyChat() {
            // Check if Jenny launcher exists on page and click it
            const launcher = document.getElementById('jenny-chat-launcher');
            if (launcher) {
                launcher.click();
            } else {
                window.dispatchEvent(new CustomEvent('open-jenny-chat'));
            }
        }
    };
}
</script>
