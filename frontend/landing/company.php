<?php
declare(strict_types=1);
/**
 * View: Public Company Profile (Candidate & Visitor Facing)
 * Route: /company, /company/{slug}, /companies, /companies/{slug}
 * Layout: app (Public navbar, footer, toast, Jenny AI chatbot)
 * 
 * @var array $company          Company details, culture, gallery, openings, reviews, privacy
 * @var array $similarCompanies Similar organizations for exploration
 */

$companyName = $company['name'] ?? 'TechFlow Systems';
$slogan      = $company['slogan'] ?? 'Building innovative digital experiences for the future';
$industry    = $company['industry'] ?? 'Software Development';
$location    = $company['location'] ?? 'Victoria Island, Lagos, Nigeria';
$size        = $company['size'] ?? '201–500 employees';
$website     = $company['website'] ?? 'https://techflow.io';
$webDisplay  = $company['web_display'] ?? 'techflow.io';
$email       = $company['email'] ?? 'careers@techflow.io';
$phone       = $company['phone'] ?? '+234 801 234 5678';
$logoUrl     = $company['logo'] ?? null;
$logoBg      = $company['logo_bg'] ?? '#4F47E6';
$logoColor   = $company['logo_color'] ?? '#FFFFFF';
$initial     = $company['initial'] ?? strtoupper(substr($companyName, 0, 1));
$bannerUrl   = $company['banner'] ?? 'https://images.unsplash.com/photo-1497366216548-37526070297c?w=1200&auto=format&fit=crop&q=80';
$rating      = $company['rating'] ?? '4.9';
$reviewsCnt  = (int)($company['reviews_cnt'] ?? 24);
$privacy     = $company['privacy'] ?? [];
$openings    = $company['openings'] ?? [];
$reviews     = $company['reviews'] ?? [];
$gallery     = $company['gallery'] ?? [];
$departments = $company['departments'] ?? [];
$culture     = $company['culture'] ?? [];
$socials     = $company['socials'] ?? [];

$showEmail    = !empty($privacy['show_email']);
$showPhone    = !empty($privacy['show_phone']);
$showSocials  = !empty($privacy['show_socials']);
$showSize     = !empty($privacy['show_size']);
$showGallery  = !empty($privacy['show_gallery']) && !empty($gallery);
$showLocation = !empty($privacy['show_location']);
$hasContact   = $showEmail || $showPhone || ($showSocials && !empty($socials));

// Extract company specific role titles for the review modal role dropdown
$companyRoleTitles = [];
if (!empty($openings)) {
    foreach ($openings as $op) {
        if (!empty($op['title'])) {
            $companyRoleTitles[] = $op['title'];
        }
    }
}
if (empty($companyRoleTitles)) {
    if (!empty($departments)) {
        foreach ($departments as $dept) {
            $companyRoleTitles[] = $dept . ' Intern';
        }
    } else {
        $companyRoleTitles = [
            'Software Engineer Intern',
            'Product Design Intern',
            'Growth Marketing Intern',
            'Data Science & AI Intern',
            'Operations & Logistics Intern'
        ];
    }
}
$companyRoleTitles = array_values(array_unique($companyRoleTitles));

// Logged-in candidate profile session data
$loggedInUserName = $_SESSION['profile_name'] ?? $_SESSION['user_name'] ?? 'Sarah Peters';
$isLoggedInCandidate = !empty($_SESSION['user_id']) || !empty($_SESSION['profile_name']) || !empty($_SESSION['user_name']);
?>

<style>
    /* ─── Public Company Profile Design System ─── */
    .pub-company-wrap {
        background-color: #F8FAFC;
        min-height: calc(100vh - 80px);
        padding: 32px 0 72px;
        box-sizing: border-box;
    }

    .pub-container {
        max-width: 1240px;
        margin: 0 auto;
        padding: 0 24px;
        box-sizing: border-box;
    }

    /* Breadcrumbs */
    .pub-breadcrumb-row {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
        font-weight: 500;
        color: #64748B;
        margin-bottom: 24px;
        flex-wrap: wrap;
    }

    .pub-breadcrumb-link {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        color: #475569;
        text-decoration: none;
        font-weight: 600;
        transition: color 0.15s ease;
    }

    .pub-breadcrumb-link:hover {
        color: #4F48E6;
    }

    .pub-breadcrumb-link svg {
        width: 15px;
        height: 15px;
        flex-shrink: 0;
    }

    .pub-breadcrumb-sep {
        color: #CBD5E1;
        font-size: 12px;
    }

    .pub-breadcrumb-current {
        color: #0F172A;
        font-weight: 600;
    }

    /* ── Hero Card ── */
    .pub-hero-card {
        background: #FFFFFF;
        border: 1px solid #E2E8F0;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0, 6, 35, 0.04);
        margin-bottom: 24px;
        box-sizing: border-box;
    }

    .pub-cover-wrap {
        position: relative;
        height: 240px;
        background: #0F172A;
        overflow: hidden;
    }

    .pub-cover-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center;
        display: block;
    }

    .pub-cover-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(180deg, rgba(0, 6, 35, 0.1) 0%, rgba(0, 6, 35, 0.55) 100%);
    }

    .pub-hero-body {
        position: relative;
        padding: 0 32px 30px;
        box-sizing: border-box;
    }

    .pub-avatar-wrap {
        position: absolute;
        top: -56px;
        left: 32px;
        width: 110px;
        height: 110px;
        border-radius: 24px;
        background: #FFFFFF;
        padding: 5px;
        box-shadow: 0 6px 18px rgba(0, 6, 35, 0.12);
        z-index: 10;
        box-sizing: border-box;
    }

    .pub-avatar-inner {
        width: 100%;
        height: 100%;
        border-radius: 20px;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 38px;
    }

    .pub-avatar-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .pub-avatar-check {
        position: absolute;
        bottom: -2px;
        right: -2px;
        width: 26px;
        height: 26px;
        background: #10B981;
        color: #FFFFFF;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 3px solid #FFFFFF;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.12);
    }

    .pub-avatar-check svg {
        width: 13px;
        height: 13px;
    }

    .pub-hero-content {
        padding-top: 68px;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 24px;
        flex-wrap: wrap;
    }

    .pub-hero-titles {
        flex: 1;
        min-width: 0;
    }

    .pub-hero-h1 {
        font-size: 26px;
        font-weight: 800;
        color: #0F172A;
        margin: 0 0 6px;
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }

    .pub-verified-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-size: 11.5px;
        font-weight: 700;
        color: #4F48E6;
        background: #EEF2FF;
        padding: 3px 10px;
        border-radius: 99px;
        white-space: nowrap !important;
        flex-shrink: 0 !important;
    }

    .pub-verified-badge svg {
        width: 14px;
        height: 14px;
        flex-shrink: 0;
    }

    .pub-hero-slogan {
        font-size: 15px;
        color: #475569;
        margin: 0 0 16px;
        line-height: 1.5;
        font-weight: 500;
    }

    .pub-meta-pills {
        display: flex;
        align-items: center;
        gap: 16px;
        flex-wrap: wrap;
    }

    .pub-meta-pill-item {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 13px;
        color: #64748B;
        font-weight: 500;
        white-space: nowrap !important;
        flex-shrink: 0 !important;
    }

    .pub-meta-pill-item svg {
        width: 16px;
        height: 16px;
        color: #94A3B8;
        flex-shrink: 0;
    }

    .pub-openings-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 12px;
        font-weight: 700;
        color: #166534;
        background: #DCFCE7;
        padding: 4px 10px;
        border-radius: 99px;
        white-space: nowrap !important;
        flex-shrink: 0 !important;
    }

    .pub-pulse-dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: #16A34A;
        box-shadow: 0 0 0 2px rgba(22, 163, 74, 0.2);
    }

    /* Hero Action Buttons */
    .pub-hero-actions {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-shrink: 0;
    }

    .pub-btn-outline {
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        gap: 6px !important;
        background: #FFFFFF !important;
        color: #1E293B !important;
        border: 1.5px solid #CBD5E1 !important;
        font-size: 13.5px !important;
        font-weight: 700 !important;
        padding: 10px 18px !important;
        border-radius: 12px !important;
        text-decoration: none !important;
        cursor: pointer !important;
        white-space: nowrap !important;
        flex-shrink: 0 !important;
        transition: all 0.15s ease !important;
    }

    .pub-btn-outline:hover {
        background: #F8FAFC !important;
        border-color: #94A3B8 !important;
    }

    .pub-btn-primary {
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        gap: 8px !important;
        background: #4F48E6 !important;
        color: #FFFFFF !important;
        border: none !important;
        font-size: 13.5px !important;
        font-weight: 700 !important;
        padding: 11px 22px !important;
        border-radius: 12px !important;
        text-decoration: none !important;
        cursor: pointer !important;
        box-shadow: 0 4px 14px rgba(79, 72, 230, 0.28) !important;
        white-space: nowrap !important;
        flex-shrink: 0 !important;
        transition: all 0.15s ease !important;
    }

    .pub-btn-primary:hover {
        background: #4338CA !important;
        transform: translateY(-1px);
        box-shadow: 0 6px 18px rgba(79, 72, 230, 0.35) !important;
    }

    .pub-btn-primary svg, .pub-btn-outline svg {
        width: 16px;
        height: 16px;
        flex-shrink: 0;
    }

    /* ── Single Overview Navigation Bar ── */
    .pub-tabs-bar {
        display: flex;
        align-items: center;
        gap: 28px;
        border-bottom: 1px solid #E2E8F0;
        margin-bottom: 28px;
        padding-bottom: 2px;
        overflow-x: auto;
        white-space: nowrap;
        scrollbar-width: none;
    }

    .pub-tabs-bar::-webkit-scrollbar {
        display: none;
    }

    .pub-tab-item {
        font-size: 14.5px;
        font-weight: 700;
        color: #4F48E6;
        padding: 8px 4px 14px;
        text-decoration: none;
        position: relative;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        white-space: nowrap !important;
        flex-shrink: 0 !important;
    }

    .pub-tab-item::after {
        content: "";
        position: absolute;
        bottom: -2px;
        left: 0;
        right: 0;
        height: 2.5px;
        background: #4F48E6;
        border-radius: 99px;
    }

    .pub-tab-item-muted {
        font-size: 14.5px;
        font-weight: 600;
        color: #64748B;
        padding: 8px 4px 14px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: color 0.15s ease;
        white-space: nowrap !important;
        flex-shrink: 0 !important;
    }

    .pub-tab-item-muted:hover {
        color: #0F172A;
    }

    .pub-tab-count {
        font-size: 11px;
        font-weight: 700;
        padding: 2px 7px;
        border-radius: 99px;
        background: #EEF2FF;
        color: #4F48E6;
    }

    /* ── 2-Column Content Grid ── */
    .pub-layout-grid {
        display: grid;
        grid-template-columns: minmax(0, 1fr) 360px;
        gap: 28px;
        align-items: start;
        box-sizing: border-box;
    }

    /* Shared Card Styles */
    .pub-card {
        background: #FFFFFF;
        border: 1px solid #E2E8F0;
        border-radius: 18px;
        padding: 26px 28px;
        margin-bottom: 24px;
        box-shadow: 0 1px 3px rgba(0, 6, 35, 0.02);
        box-sizing: border-box;
        width: 100%;
        min-width: 0;
    }

    .pub-card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        margin-bottom: 18px;
        flex-wrap: wrap;
    }

    .pub-card-title-wrap {
        display: flex;
        align-items: center;
        gap: 12px;
        min-width: 0;
    }

    .pub-card-icon-box {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        background: #EEF2FF;
        color: #4F48E6;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .pub-card-icon-box svg {
        width: 18px;
        height: 18px;
    }

    .pub-card-title {
        font-size: 17px;
        font-weight: 800;
        color: #0F172A;
        margin: 0;
    }

    .pub-card-desc {
        font-size: 14.5px;
        color: #475569;
        line-height: 1.7;
        margin: 0 0 18px;
    }

    /* Department / Skill Pills */
    .pub-tags-row {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    .pub-tag-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #F8FAFC;
        border: 1px solid #E2E8F0;
        border-radius: 99px;
        padding: 6px 14px;
        font-size: 12.5px;
        font-weight: 600;
        color: #334155;
        white-space: nowrap !important;
        flex-shrink: 0 !important;
    }

    .pub-tag-pill svg {
        width: 13px;
        height: 13px;
        color: #64748B;
        flex-shrink: 0;
    }

    /* ── Open Internships Cards ── */
    .pub-openings-list {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .pub-opening-card {
        background: #FFFFFF;
        border: 1px solid #E2E8F0;
        border-radius: 14px;
        padding: 20px;
        transition: all 0.2s ease;
        box-sizing: border-box;
    }

    .pub-opening-card:hover {
        border-color: #CBD5E1;
        box-shadow: 0 6px 20px rgba(0, 6, 35, 0.05);
        transform: translateY(-2px);
    }

    .pub-opening-top {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 16px;
        margin-bottom: 10px;
        flex-wrap: wrap;
    }

    .pub-opening-title {
        font-size: 16px;
        font-weight: 800;
        color: #0F172A;
        margin: 0 0 4px;
    }

    .pub-opening-meta {
        display: flex;
        align-items: center;
        gap: 12px;
        font-size: 12.5px;
        color: #64748B;
        flex-wrap: wrap;
    }

    .pub-opening-meta-item {
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .pub-opening-meta-item svg {
        width: 14px;
        height: 14px;
        color: #94A3B8;
        flex-shrink: 0;
    }

    .pub-opening-badge-mode {
        font-size: 10.5px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        padding: 3px 8px;
        border-radius: 6px;
        background: #F1F5F9;
        color: #334155;
        white-space: nowrap !important;
        flex-shrink: 0 !important;
    }

    .pub-opening-stipend {
        font-size: 14px;
        font-weight: 800;
        color: #0F172A;
        white-space: nowrap !important;
        flex-shrink: 0 !important;
    }

    .pub-opening-desc {
        font-size: 13.5px;
        color: #475569;
        line-height: 1.6;
        margin: 0 0 16px;
    }

    .pub-opening-bottom {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding-top: 14px;
        border-top: 1px solid #F1F5F9;
        gap: 12px;
        flex-wrap: wrap;
    }

    .pub-opening-applicants {
        font-size: 12px;
        color: #64748B;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .pub-opening-actions {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .pub-btn-sm-primary {
        background: #4F48E6;
        color: #FFFFFF;
        padding: 7px 16px;
        border-radius: 10px;
        font-size: 12.5px;
        font-weight: 700;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        white-space: nowrap !important;
        flex-shrink: 0 !important;
        transition: all 0.15s ease;
    }

    .pub-btn-sm-primary:hover {
        background: #4338CA;
        transform: translateY(-1px);
    }

    .pub-btn-sm-outline {
        background: #FFFFFF;
        color: #334155;
        border: 1px solid #CBD5E1;
        padding: 6px 14px;
        border-radius: 10px;
        font-size: 12.5px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        white-space: nowrap !important;
        flex-shrink: 0 !important;
        transition: all 0.15s ease;
    }

    .pub-btn-sm-outline:hover {
        background: #F8FAFC;
        border-color: #94A3B8;
    }

    /* ── Gallery Grid ── */
    .pub-gallery-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 14px;
    }

    .pub-gallery-item {
        position: relative;
        height: 140px;
        border-radius: 12px;
        overflow: hidden;
        background: #F1F5F9;
        cursor: pointer;
        border: 1px solid #E2E8F0;
    }

    .pub-gallery-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        transition: transform 0.25s ease;
    }

    .pub-gallery-item:hover img {
        transform: scale(1.05);
    }

    /* ── Reviews ── */
    .pub-reviews-list {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .pub-review-item {
        padding: 18px;
        border-radius: 14px;
        background: #F8FAFC;
        border: 1px solid #E2E8F0;
    }

    .pub-review-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 12px;
        gap: 12px;
    }

    .pub-reviewer-info {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .pub-reviewer-avatar {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        object-fit: cover;
    }

    .pub-reviewer-name {
        font-size: 13.5px;
        font-weight: 700;
        color: #0F172A;
        margin: 0;
    }

    .pub-reviewer-role {
        font-size: 11.5px;
        color: #64748B;
        margin: 0;
    }

    .pub-stars-wrap {
        display: inline-flex;
        align-items: center;
        gap: 2px;
        color: #F59E0B;
    }

    .pub-stars-wrap svg {
        width: 14px;
        height: 14px;
        fill: currentColor;
    }

    .pub-review-text {
        font-size: 13.5px;
        color: #475569;
        line-height: 1.6;
        margin: 0;
        font-style: italic;
    }

    /* ── Right Column Cards ── */
    .pub-detail-list {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .pub-detail-item {
        display: flex;
        align-items: flex-start;
        gap: 12px;
    }

    .pub-detail-icon {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        background: #F8FAFC;
        border: 1px solid #E2E8F0;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #64748B;
        flex-shrink: 0;
    }

    .pub-detail-icon svg {
        width: 16px;
        height: 16px;
    }

    .pub-detail-label {
        font-size: 11px;
        font-weight: 700;
        color: #94A3B8;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 2px;
    }

    .pub-detail-val {
        font-size: 13.5px;
        font-weight: 600;
        color: #1E293B;
    }

    .pub-detail-val a {
        color: #4F48E6;
        text-decoration: none;
    }

    .pub-detail-val a:hover {
        text-decoration: underline;
    }

    .pub-contact-row {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 13.5px;
        color: #334155;
        margin-bottom: 12px;
        word-break: break-all;
    }

    .pub-contact-row svg {
        width: 16px;
        height: 16px;
        color: #64748B;
        flex-shrink: 0;
    }

    .pub-social-icons-row {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-top: 14px;
        padding-top: 14px;
        border-top: 1px solid #F1F5F9;
        flex-wrap: wrap;
    }

    .pub-social-btn {
        width: 34px;
        height: 34px;
        border-radius: 10px;
        background: #F8FAFC;
        border: 1px solid #E2E8F0;
        color: #475569;
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        transition: all 0.15s ease;
    }

    .pub-social-btn:hover {
        background: #EEF2FF;
        border-color: #C7D2FE;
        color: #4F48E6;
    }

    .pub-social-btn svg {
        width: 16px;
        height: 16px;
    }

    /* Similar Companies Card */
    .pub-similar-list {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .pub-similar-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px 12px;
        border-radius: 12px;
        background: #F8FAFC;
        border: 1px solid #E2E8F0;
        text-decoration: none;
        transition: all 0.15s ease;
    }

    .pub-similar-item:hover {
        background: #FFFFFF;
        border-color: #CBD5E1;
        box-shadow: 0 2px 6px rgba(0, 6, 35, 0.04);
        transform: translateX(2px);
    }

    .pub-similar-left {
        display: flex;
        align-items: center;
        gap: 10px;
        min-width: 0;
    }

    .pub-similar-avatar {
        width: 34px;
        height: 34px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 14px;
        flex-shrink: 0;
        overflow: hidden;
    }

    .pub-similar-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .pub-similar-name {
        font-size: 13px;
        font-weight: 700;
        color: #0F172A;
        margin: 0 0 2px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .pub-similar-industry {
        font-size: 11.5px;
        color: #64748B;
        margin: 0;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .pub-similar-pill {
        font-size: 11px;
        font-weight: 700;
        color: #4F48E6;
        background: #EEF2FF;
        padding: 3px 8px;
        border-radius: 6px;
        white-space: nowrap !important;
        flex-shrink: 0 !important;
    }

    /* Jenny AI Callout Card */
    .pub-jenny-card {
        background: #000623;
        border: 1px solid #1E293B;
        border-radius: 18px;
        padding: 24px;
        color: #FFFFFF;
        position: relative;
        overflow: hidden;
        margin-bottom: 24px;
        box-sizing: border-box;
    }

    .pub-jenny-header {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 12px;
    }

    .pub-jenny-avatar-box {
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

    .pub-jenny-avatar-box img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }

    .pub-jenny-btn {
        width: 100%;
        background: #4F48E6;
        color: #FFFFFF;
        border: none;
        padding: 10px 16px;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: all 0.15s ease;
        margin-top: 14px;
        white-space: nowrap !important;
        flex-shrink: 0 !important;
    }

    .pub-jenny-btn:hover {
        background: #4338CA;
        transform: translateY(-1px);
    }

    /* Modal Lightbox for Workspace Gallery */
    .pub-lightbox-backdrop {
        position: fixed;
        inset: 0;
        background: rgba(0, 6, 35, 0.85);
        backdrop-filter: blur(8px);
        z-index: 2500;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 24px;
        box-sizing: border-box;
    }

    .pub-lightbox-dialog {
        background: #FFFFFF;
        border-radius: 18px;
        overflow: hidden;
        max-width: 860px;
        width: 100%;
        position: relative;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
    }

    .pub-lightbox-img {
        width: 100%;
        max-height: 540px;
        object-fit: contain;
        background: #0F172A;
        display: block;
    }

    .pub-lightbox-close {
        position: absolute;
        top: 14px;
        right: 14px;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: rgba(15, 23, 42, 0.7);
        color: #FFFFFF;
        border: none;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background 0.15s ease;
        z-index: 10;
    }

    .pub-lightbox-close:hover {
        background: #0F172A;
    }

    /* ─── Leave a Review Stacked Card Modal ─── */
    .pub-modal-backdrop {
        position: fixed;
        inset: 0;
        background: rgba(0, 6, 35, 0.72);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        z-index: 9999;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 28px 20px;
        box-sizing: border-box;
        overflow-y: auto;
    }

    .pub-modal-stacked-wrap {
        position: relative;
        max-width: 530px;
        width: 100%;
        margin: auto;
    }

    /* Stacked card layers behind main dialog (subtle card-behind-card depth effect) */
    .pub-modal-card-stack-bg {
        position: absolute;
        left: 0;
        right: 0;
        height: 100%;
        border-radius: 26px;
        pointer-events: none;
        transition: transform 0.25s cubic-bezier(0.16, 1, 0.3, 1), opacity 0.25s ease;
    }

    .pub-modal-card-stack-2 {
        top: -14px;
        left: 18px;
        right: 18px;
        background: rgba(255, 255, 255, 0.42);
        border: 1px solid rgba(255, 255, 255, 0.6);
        box-shadow: 0 10px 25px rgba(0, 6, 35, 0.06);
        backdrop-filter: blur(6px);
        -webkit-backdrop-filter: blur(6px);
        z-index: 1;
        transform: scale(0.96);
    }

    .pub-modal-card-stack-1 {
        top: -7px;
        left: 9px;
        right: 9px;
        background: rgba(255, 255, 255, 0.72);
        border: 1px solid rgba(255, 255, 255, 0.85);
        box-shadow: 0 12px 30px rgba(0, 6, 35, 0.09);
        backdrop-filter: blur(4px);
        -webkit-backdrop-filter: blur(4px);
        z-index: 2;
        transform: scale(0.98);
    }

    .pub-modal-dialog {
        position: relative;
        z-index: 3;
        background: #FFFFFF;
        border: 1px solid #E2E8F0;
        border-radius: 24px;
        max-width: 530px;
        width: 100%;
        padding: 34px 34px 30px;
        box-shadow: 0 25px 60px -15px rgba(0, 6, 35, 0.22), 0 0 0 1px rgba(226, 232, 240, 0.75);
        box-sizing: border-box;
    }

    .pub-modal-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        margin-bottom: 22px;
        gap: 16px;
    }

    .pub-modal-header-icon-box {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        background: #EEF2FF;
        color: #4F48E6;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .pub-modal-title {
        font-size: 19px;
        font-weight: 800;
        color: #0F172A;
        margin: 0;
        letter-spacing: -0.02em;
    }

    .pub-modal-subtext {
        font-size: 13px;
        color: #64748B;
        margin: 3px 0 0;
        line-height: 1.45;
    }

    .pub-modal-close-btn {
        background: transparent;
        border: none;
        color: #94A3B8;
        cursor: pointer;
        padding: 6px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.15s ease;
        flex-shrink: 0;
    }

    .pub-modal-close-btn:hover {
        color: #334155;
        background: #F1F5F9;
    }

    /* Rating Component Box */
    .pub-modal-rating-card {
        background: #F8FAFC;
        border: 1px solid #F1F5F9;
        border-radius: 16px;
        padding: 18px 16px;
        text-align: center;
        margin-bottom: 22px;
    }

    .pub-modal-rating-title {
        display: block;
        font-size: 11px;
        font-weight: 700;
        color: #64748B;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        margin-bottom: 10px;
    }

    .pub-star-row {
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .pub-star-btn {
        background: transparent;
        border: none;
        cursor: pointer;
        padding: 4px;
        border-radius: 8px;
        outline: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: transform 0.18s cubic-bezier(0.34, 1.56, 0.64, 1), opacity 0.15s ease;
    }

    .pub-star-btn:hover {
        transform: scale(1.18);
    }

    .pub-star-svg {
        width: 32px;
        height: 32px;
        transition: fill 0.15s ease, stroke 0.15s ease;
    }

    .pub-modal-rating-label {
        font-size: 13.5px;
        font-weight: 700;
        color: #0F172A;
        margin-top: 8px;
        transition: all 0.15s ease;
    }

    /* Form Inputs */
    .pub-modal-input {
        width: 100%;
        height: 46px;
        padding: 10px 14px;
        border: 1px solid #CBD5E1;
        border-radius: 12px;
        font-size: 13.5px;
        color: #0F172A;
        background: #FFFFFF;
        box-sizing: border-box;
        font-family: inherit;
        outline: none;
        transition: border-color 0.15s ease, box-shadow 0.15s ease;
    }

    .pub-modal-input:focus {
        border-color: #4F48E6;
        box-shadow: 0 0 0 3px rgba(79, 72, 230, 0.12);
    }

    .pub-modal-textarea {
        width: 100%;
        padding: 12px 14px;
        border: 1px solid #CBD5E1;
        border-radius: 12px;
        font-size: 13.5px;
        line-height: 1.55;
        color: #0F172A;
        background: #FFFFFF;
        box-sizing: border-box;
        font-family: inherit;
        outline: none;
        resize: vertical;
        min-height: 105px;
        transition: border-color 0.15s ease, box-shadow 0.15s ease;
    }

    .pub-modal-textarea:focus {
        border-color: #4F48E6;
        box-shadow: 0 0 0 3px rgba(79, 72, 230, 0.12);
    }

    /* Standardized Custom Alpine Dropdowns (Matching Onboarding & Dashboard) */
    .db-dropdown-wrap {
        position: relative;
        width: 100%;
        box-sizing: border-box;
    }

    .db-dropdown-btn {
        width: 100% !important;
        background: #FFFFFF !important;
        border: 1px solid #CBD5E1 !important;
        border-radius: 12px !important;
        padding: 0 14px !important;
        height: 46px !important;
        font-size: 13.5px !important;
        font-weight: 500 !important;
        color: #0F172A !important;
        display: flex !important;
        align-items: center !important;
        justify-content: space-between !important;
        cursor: pointer !important;
        transition: border-color 0.15s ease, box-shadow 0.15s ease !important;
        outline: none !important;
        box-sizing: border-box !important;
        font-family: inherit !important;
        text-align: left !important;
    }

    .db-dropdown-btn:hover {
        border-color: #94A3B8 !important;
    }

    .db-dropdown-btn:focus,
    .db-dropdown-btn.active,
    .db-dropdown-btn.is-open {
        border-color: #4F48E6 !important;
        box-shadow: 0 0 0 3px rgba(79, 72, 230, 0.12) !important;
    }

    .db-dropdown-chevron {
        width: 16px !important;
        height: 16px !important;
        color: #64748B !important;
        transition: transform 0.2s ease !important;
        flex-shrink: 0 !important;
    }

    .db-dropdown-menu {
        position: absolute !important;
        top: calc(100% + 6px) !important;
        left: 0 !important;
        right: 0 !important;
        background: #FFFFFF !important;
        border: 1px solid #E2E8F0 !important;
        border-radius: 12px !important;
        box-shadow: 0 12px 30px rgba(15, 23, 42, 0.12) !important;
        z-index: 100 !important;
        padding: 6px !important;
        box-sizing: border-box !important;
        max-height: 230px !important;
        overflow-y: auto !important;
    }

    .db-dropdown-item {
        display: flex !important;
        align-items: center !important;
        justify-content: space-between !important;
        padding: 9px 12px !important;
        font-size: 13px !important;
        font-weight: 500 !important;
        color: #334155 !important;
        border-radius: 8px !important;
        cursor: pointer !important;
        transition: background 0.15s ease, color 0.15s ease !important;
        width: 100% !important;
        box-sizing: border-box !important;
        border: none !important;
        background: transparent !important;
        text-align: left !important;
    }

    .db-dropdown-item:hover {
        background: #F8FAFC !important;
        color: #0F172A !important;
    }

    .db-dropdown-item.is-selected,
    .db-dropdown-item.active {
        background: #EEF2FF !important;
        color: #4F48E6 !important;
        font-weight: 600 !important;
    }

    .pub-modal-checkbox-label {
        display: flex;
        align-items: center;
        gap: 9px;
        font-size: 13px;
        font-weight: 500;
        color: #334155;
        cursor: pointer;
        user-select: none;
    }

    .pub-modal-checkbox {
        accent-color: #4F48E6;
        width: 16px;
        height: 16px;
        cursor: pointer;
        border-radius: 4px;
        flex-shrink: 0;
    }

    /* Modal Footer Buttons */
    .pub-modal-footer {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 12px;
        margin-top: 24px;
        padding-top: 20px;
        border-top: 1px solid #F1F5F9;
    }

    .pub-btn-modal-cancel {
        background: #FFFFFF;
        border: 1px solid #CBD5E1;
        color: #334155;
        padding: 10px 20px;
        border-radius: 12px;
        font-size: 13.5px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.15s ease;
        white-space: nowrap !important;
        flex-shrink: 0 !important;
    }

    .pub-btn-modal-cancel:hover {
        background: #F8FAFC;
        border-color: #94A3B8;
        color: #0F172A;
    }

    .pub-btn-modal-submit {
        background: #4F48E6;
        border: 1px solid #4F48E6;
        color: #FFFFFF;
        padding: 10px 24px;
        border-radius: 12px;
        font-size: 13.5px;
        font-weight: 700;
        cursor: pointer;
        box-shadow: 0 4px 12px rgba(79, 72, 230, 0.25);
        transition: all 0.15s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        white-space: nowrap !important;
        flex-shrink: 0 !important;
    }

    .pub-btn-modal-submit:hover {
        background: #4338CA;
        border-color: #4338CA;
        box-shadow: 0 6px 16px rgba(79, 72, 230, 0.35);
        transform: translateY(-1px);
    }

    /* Section Trigger Score Pill Polish (Image 3) */
    .pub-feedback-score-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 12.5px;
        font-weight: 700;
        color: #92400E;
        background: #FEF3C7;
        border: 1px solid #FDE68A;
        padding: 5px 12px;
        border-radius: 99px;
        white-space: nowrap !important;
        flex-shrink: 0 !important;
    }

    .pub-feedback-score-star {
        width: 14px;
        height: 14px;
        color: #D97706;
        flex-shrink: 0;
    }

    .pub-write-review-btn {
        background: #4F48E6;
        color: #FFFFFF;
        padding: 7px 16px;
        border-radius: 10px;
        font-size: 12.5px;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        border: none;
        cursor: pointer;
        transition: all 0.15s ease;
        white-space: nowrap !important;
        flex-shrink: 0 !important;
    }

    .pub-write-review-btn:hover {
        background: #4338CA;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(79, 72, 230, 0.22);
    }

    /* Responsive queries */
    @media (max-width: 1024px) {
        .pub-layout-grid {
            grid-template-columns: minmax(0, 1fr) !important;
        }
    }

    @media (max-width: 640px) {
        .pub-cover-wrap {
            height: 150px;
        }

        .pub-avatar-wrap {
            left: 18px;
            top: -42px;
            width: 84px;
            height: 84px;
            border-radius: 18px;
            padding: 4px;
        }

        .pub-avatar-inner {
            border-radius: 14px;
            font-size: 28px;
        }

        .pub-hero-body {
            padding: 0 16px 20px;
        }

        .pub-hero-content {
            padding-top: 50px;
            flex-direction: column;
            gap: 16px;
        }

        .pub-hero-actions {
            width: 100%;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
        }

        .pub-hero-actions .pub-btn-primary,
        .pub-hero-actions .pub-btn-outline {
            width: 100% !important;
            justify-content: center !important;
            padding: 9px 12px !important;
            font-size: 12.5px !important;
        }

        .pub-gallery-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .pub-card {
            padding: 20px 16px;
        }

        .pub-modal-dialog {
            padding: 24px 18px 22px;
            border-radius: 20px;
        }

        .pub-modal-card-stack-2 {
            top: -10px;
            left: 12px;
            right: 12px;
            border-radius: 22px;
        }

        .pub-modal-card-stack-1 {
            top: -5px;
            left: 6px;
            right: 6px;
            border-radius: 20px;
        }

        .pub-modal-footer {
            flex-direction: column-reverse;
            gap: 10px;
        }

        .pub-btn-modal-cancel,
        .pub-btn-modal-submit {
            width: 100% !important;
            justify-content: center !important;
        }
    }
</style>

<div class="pub-company-wrap" x-data="{ 
    saved: false,
    activeTab: 'overview',
    lightboxOpen: false,
    lightboxImg: '',
    writeReviewModal: false,
    reviewRating: 5,
    hoverRating: 5,
    candidateRegisteredName: '<?= htmlspecialchars($loggedInUserName, ENT_QUOTES, 'UTF-8') ?>',
    reviewName: '<?= htmlspecialchars($loggedInUserName, ENT_QUOTES, 'UTF-8') ?>',
    companyRoles: <?= htmlspecialchars(json_encode($companyRoleTitles), ENT_QUOTES, 'UTF-8') ?>,
    selectedRole: '',
    customRoleText: '',
    roleDropdownOpen: false,
    reviewComment: '',
    reviewRecommend: true,
    reviewsList: <?= htmlspecialchars(json_encode($reviews), ENT_QUOTES, 'UTF-8') ?>,

    get ratingLabel() {
        const r = this.hoverRating || this.reviewRating;
        const map = {
            5: '5 out of 5 — Excellent',
            4: '4 out of 5 — Very Good',
            3: '3 out of 5 — Good',
            2: '2 out of 5 — Fair',
            1: '1 out of 5 — Poor'
        };
        return map[r] || (r + ' out of 5 Stars');
    },

    get totalReviews() {
        return this.reviewsList.length;
    },

    get avgRating() {
        if (!this.reviewsList.length) return '0.0';
        const sum = this.reviewsList.reduce((acc, r) => acc + (Number(r.rating) || 5), 0);
        return (sum / this.reviewsList.length).toFixed(1);
    },

    selectRole(role) {
        this.selectedRole = role;
        this.roleDropdownOpen = false;
        if (role !== 'Other / Not listed') {
            this.customRoleText = '';
        }
    },

    submitReview() {
        if (!this.reviewComment.trim()) {
            this.$dispatch('show-toast', { text: 'Please write your review feedback.', type: 'warning' });
            return;
        }

        let finalRole = this.selectedRole;
        if (this.selectedRole === 'Other / Not listed') {
            finalRole = this.customRoleText.trim() || 'Former Intern';
        } else if (!finalRole) {
            finalRole = 'Internship Candidate';
        }

        let finalName = this.candidateRegisteredName || 'Sarah Peters';

        const newRev = {
            name: finalName,
            role: finalRole,
            rating: this.reviewRating,
            date: 'Just now',
            comment: this.reviewComment.trim(),
            recommend: this.reviewRecommend,
            avatar: 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?w=100&h=100&fit=crop&crop=faces'
        };

        this.reviewsList.unshift(newRev);
        this.writeReviewModal = false;
        
        // Reset form fields
        this.reviewComment = '';
        this.selectedRole = '';
        this.customRoleText = '';
        this.reviewRating = 5;
        this.hoverRating = 5;
        this.reviewRecommend = true;

        this.$dispatch('show-toast', { text: 'Your review was published successfully! Thank you for helping students.', type: 'success' });
    }
}">

    <div class="pub-container">

        <!-- ══ Breadcrumb Navigation ══ -->
        <div class="pub-breadcrumb-row">
            <a href="<?= url('/internships') ?>" class="pub-breadcrumb-link">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
                <span>Browse Internships</span>
            </a>
            <span class="pub-breadcrumb-sep">/</span>
            <span class="pub-breadcrumb-current"><?= htmlspecialchars($companyName) ?></span>
        </div>

        <!-- ══ Hero Cover Card ══ -->
        <div class="pub-hero-card">
            <div class="pub-cover-wrap">
                <img src="<?= htmlspecialchars($bannerUrl) ?>" alt="<?= htmlspecialchars($companyName) ?> Cover" class="pub-cover-img">
                <div class="pub-cover-overlay"></div>
            </div>

            <div class="pub-hero-body">
                <!-- Circular/Rounded Logo Avatar -->
                <div class="pub-avatar-wrap">
                    <div class="pub-avatar-inner" style="background-color: <?= htmlspecialchars($logoBg) ?>; color: <?= htmlspecialchars($logoColor) ?>;">
                        <?php if (!empty($logoUrl)): ?>
                            <img src="<?= htmlspecialchars($logoUrl) ?>" alt="<?= htmlspecialchars($companyName) ?>" class="pub-avatar-img">
                        <?php else: ?>
                            <span><?= htmlspecialchars($initial) ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="pub-avatar-check" title="Verified Employer on InternConnect">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                </div>

                <div class="pub-hero-content">
                    <div class="pub-hero-titles">
                        <h1 class="pub-hero-h1">
                            <span><?= htmlspecialchars($companyName) ?></span>
                            <span class="pub-verified-badge">
                                <svg fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                <span>Verified Employer</span>
                            </span>
                        </h1>

                        <p class="pub-hero-slogan"><?= htmlspecialchars($slogan) ?></p>

                        <div class="pub-meta-pills">
                            <div class="pub-meta-pill-item">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                                <span><?= htmlspecialchars($industry) ?></span>
                            </div>

                            <?php if ($showLocation): ?>
                                <div class="pub-meta-pill-item">
                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    <span><?= htmlspecialchars($location) ?></span>
                                </div>
                            <?php endif; ?>

                            <?php if ($showSize): ?>
                                <div class="pub-meta-pill-item">
                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                    <span><?= htmlspecialchars($size) ?></span>
                                </div>
                            <?php endif; ?>

                            <?php if (!empty($openings)): ?>
                                <div class="pub-openings-badge">
                                    <span class="pub-pulse-dot"></span>
                                    <span><?= count($openings) ?> Active <?= count($openings) === 1 ? 'Role' : 'Roles' ?></span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Action CTAs -->
                    <div class="pub-hero-actions">
                        <button type="button" class="pub-btn-outline" @click="saved = !saved; $dispatch('show-toast', { text: saved ? 'Company added to your saved list!' : 'Company removed from saved list.', type: 'info' })">
                            <svg x-show="!saved" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                            </svg>
                            <svg x-show="saved" style="display: none; color: #4F48E6;" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                            </svg>
                            <span x-text="saved ? 'Saved' : 'Save Company'">Save Company</span>
                        </button>

                        <button type="button" class="pub-btn-outline" @click="navigator.clipboard.writeText(window.location.href); $dispatch('show-toast', { text: 'Company profile link copied to clipboard!', type: 'success' })" title="Share Company Profile">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" />
                            </svg>
                            <span>Share</span>
                        </button>

                        <?php if (!empty($openings)): ?>
                            <a href="#section-openings" class="pub-btn-primary">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                                </svg>
                                <span>View Roles (<?= count($openings) ?>)</span>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- ══ Navigation Tabs Strip ══ -->
        <div class="pub-tabs-bar">
            <a href="#section-overview" class="pub-tab-item">
                <span>Company Overview</span>
            </a>
            <a href="#section-openings" class="pub-tab-item-muted">
                <span>Open Internships</span>
                <?php if (!empty($openings)): ?>
                    <span class="pub-tab-count"><?= count($openings) ?></span>
                <?php endif; ?>
            </a>
            <a href="#section-culture" class="pub-tab-item-muted">
                <span>Culture & Values</span>
            </a>
            <?php if ($showGallery): ?>
                <a href="#section-gallery" class="pub-tab-item-muted">
                    <span>Workspace Gallery</span>
                </a>
            <?php endif; ?>
            <a href="#section-reviews" class="pub-tab-item-muted">
                <span>Candidate Reviews</span>
                <span class="pub-tab-count" x-text="totalReviews"><?= $reviewsCnt ?></span>
            </a>
        </div>

        <!-- ══ 2-Column Content Layout ══ -->
        <div class="pub-layout-grid">

            <!-- Left / Primary Column -->
            <div class="pub-left-col">

                <!-- 1. About Company Section -->
                <div class="pub-card" id="section-overview">
                    <div class="pub-card-header">
                        <div class="pub-card-title-wrap">
                            <div class="pub-card-icon-box">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                            </div>
                            <h3 class="pub-card-title">About <?= htmlspecialchars($companyName) ?></h3>
                        </div>
                    </div>

                    <p class="pub-card-desc"><?= nl2br(htmlspecialchars($company['description'] ?? '')) ?></p>

                    <?php if (!empty($departments)): ?>
                        <div style="font-size: 11.5px; font-weight: 700; color: #94A3B8; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 10px;">
                            Active Internship Departments
                        </div>
                        <div class="pub-tags-row">
                            <?php foreach ($departments as $dept): ?>
                                <span class="pub-tag-pill">
                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14" />
                                    </svg>
                                    <?= htmlspecialchars($dept) ?>
                                </span>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- 2. Active Internship Openings Section -->
                <div class="pub-card" id="section-openings">
                    <div class="pub-card-header">
                        <div class="pub-card-title-wrap">
                            <div class="pub-card-icon-box" style="background: #ECFDF5; color: #059669;">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="pub-card-title">Open Internship Positions</h3>
                                <p style="font-size: 12.5px; color: #64748B; margin: 2px 0 0;">
                                    Verified opportunities directly posted by the <?= htmlspecialchars($companyName) ?> hiring team
                                </p>
                            </div>
                        </div>
                        <span class="pub-openings-badge">
                            <?= count($openings) ?> <?= count($openings) === 1 ? 'Opening' : 'Openings' ?>
                        </span>
                    </div>

                    <?php if (!empty($openings)): ?>
                        <div class="pub-openings-list">
                            <?php foreach ($openings as $role): ?>
                                <div class="pub-opening-card">
                                    <div class="pub-opening-top">
                                        <div>
                                            <h4 class="pub-opening-title"><?= htmlspecialchars($role['title']) ?></h4>
                                            <div class="pub-opening-meta">
                                                <span class="pub-opening-badge-mode"><?= htmlspecialchars($role['work_mode'] ?? 'Remote') ?></span>
                                                <span class="pub-opening-meta-item">
                                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    </svg>
                                                    <?= htmlspecialchars($role['location'] ?? $location) ?>
                                                </span>
                                                <span class="pub-opening-meta-item">
                                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    <?= htmlspecialchars($role['type'] ?? 'Full-Time') ?>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="pub-opening-stipend">
                                            <?= htmlspecialchars($role['stipend'] ?? 'Stipend Included') ?>
                                        </div>
                                    </div>

                                    <p class="pub-opening-desc"><?= htmlspecialchars($role['desc'] ?? '') ?></p>

                                    <div class="pub-opening-bottom">
                                        <div class="pub-opening-applicants">
                                            <svg style="width: 14px; height: 14px; color: #94A3B8;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                            </svg>
                                            <span><?= (int)($role['applicants'] ?? 45) ?> Candidates Applied</span>
                                        </div>

                                        <div class="pub-opening-actions">
                                            <a href="<?= url('/internship/' . $role['id']) ?>" class="pub-btn-sm-outline">
                                                <span>View Details</span>
                                            </a>
                                            <a href="<?= url('/apply/' . $role['id']) ?>" class="pub-btn-sm-primary">
                                                <span>Apply Now</span>
                                                <svg style="width: 13px; height: 13px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div style="text-align: center; padding: 36px 20px; background: #F8FAFC; border-radius: 12px; border: 1px dashed #CBD5E1;">
                            <svg style="width: 36px; height: 36px; color: #94A3B8; margin: 0 auto 10px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                            </svg>
                            <h4 style="font-size: 15px; font-weight: 700; color: #1E293B; margin: 0 0 4px;">No Active Openings Currently</h4>
                            <p style="font-size: 13px; color: #64748B; margin: 0 0 16px;">This organization does not have public openings right now. Save the company to receive alerts when new roles launch.</p>
                            <button type="button" class="pub-btn-sm-primary" @click="saved = true; $dispatch('show-toast', { text: 'Alerts enabled! We will notify you when <?= htmlspecialchars($companyName) ?> posts new roles.', type: 'success' })">
                                <span>Notify Me When Openings Launch</span>
                            </button>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- 3. Culture & Values Section -->
                <?php if (!empty($culture['description'])): ?>
                    <div class="pub-card" id="section-culture">
                        <div class="pub-card-header">
                            <div class="pub-card-title-wrap">
                                <div class="pub-card-icon-box" style="background: #FEF3C7; color: #B45309;">
                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                    </svg>
                                </div>
                                <h3 class="pub-card-title">Workplace Culture & Values</h3>
                            </div>
                        </div>

                        <p class="pub-card-desc"><?= nl2br(htmlspecialchars($culture['description'] ?? '')) ?></p>

                        <?php if (!empty($culture['values'])): ?>
                            <div class="pub-tags-row">
                                <?php foreach ($culture['values'] as $val): ?>
                                    <span class="pub-tag-pill">
                                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                        </svg>
                                        <?= htmlspecialchars($val) ?>
                                    </span>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <!-- 4. Company Workspace Gallery Section -->
                <?php if ($showGallery): ?>
                    <div class="pub-card" id="section-gallery">
                        <div class="pub-card-header">
                            <div class="pub-card-title-wrap">
                                <div class="pub-card-icon-box" style="background: #FDF2F8; color: #DB2777;">
                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="pub-card-title">Life at <?= htmlspecialchars($companyName) ?></h3>
                                    <p style="font-size: 12.5px; color: #64748B; margin: 2px 0 0;">
                                        Inside our work spaces, team hackathons, and collaborative environment
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="pub-gallery-grid">
                            <?php foreach ($gallery as $imgUrl): ?>
                                <div class="pub-gallery-item" @click="lightboxImg = '<?= htmlspecialchars($imgUrl, ENT_QUOTES) ?>'; lightboxOpen = true;">
                                    <img src="<?= htmlspecialchars($imgUrl) ?>" alt="<?= htmlspecialchars($companyName) ?> Workspace" loading="lazy">
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- 5. Candidate & Intern Reviews Section -->
                <div class="pub-card" id="section-reviews">
                    <div class="pub-card-header">
                        <div class="pub-card-title-wrap">
                            <div class="pub-card-icon-box" style="background: #EEF2FF; color: #4F48E6;">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.9 1.519-.9 1.818 0l1.518 4.674a1 1 0 00.95.69h4.907c.961 0 1.357 1.236.588 1.81l-3.97 2.887a1 1 0 00-.364 1.118l1.518 4.674c.3.9-.755 1.688-1.538 1.118l-3.97-2.887a1 1 0 00-1.176 0l-3.97 2.887c-.783.57-1.838-.218-1.538-1.118l1.518-4.674a1 1 0 00-.364-1.118l-3.97-2.887c-.768-.574-.372-1.81.588-1.81h4.907a1 1 0 00.95-.69l1.518-4.674z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="pub-card-title">Candidate &amp; Intern Feedback</h3>
                                <p style="font-size: 12.5px; color: #64748B; margin: 2px 0 0;">
                                    Verified ratings from students and interns who completed placements here
                                </p>
                            </div>
                        </div>

                        <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
                            <span class="pub-feedback-score-pill">
                                <svg class="pub-feedback-score-star" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.9 1.519-.9 1.818 0l1.286 3.957a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.37 2.448a1 1 0 00-.364 1.118l1.287 3.957c.3.9-.755 1.688-1.54 1.118l-3.37-2.448a1 1 0 00-1.175 0l-3.37 2.448c-.784.57-1.838-.218-1.54-1.118l1.287-3.957a1 1 0 00-.364-1.118L2.05 9.384c-.783-.57-.38-1.81.588-1.81h4.162a1 1 0 00.95-.69l1.286-3.957z"/>
                                </svg>
                                <span x-text="avgRating + ' (' + totalReviews + ' Reviews)'"><?= htmlspecialchars($rating) ?> (<?= $reviewsCnt ?> Reviews)</span>
                            </span>
                            <button type="button" @click="writeReviewModal = true" class="pub-write-review-btn">
                                <svg style="width: 13.5px; height: 13.5px; flex-shrink: 0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.3">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                <span>Write a Review</span>
                            </button>
                        </div>
                    </div>

                    <!-- Reviews List -->
                    <div class="pub-reviews-list">
                        <template x-for="(rev, idx) in reviewsList" :key="idx">
                            <div class="pub-review-item">
                                <div class="pub-review-top">
                                    <div class="pub-reviewer-info">
                                        <img :src="rev.avatar || 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?w=100&h=100&fit=crop&crop=faces'" :alt="rev.name" class="pub-reviewer-avatar">
                                        <div>
                                            <h5 class="pub-reviewer-name" x-text="rev.name"></h5>
                                            <p class="pub-reviewer-role" x-text="rev.role + ' • ' + rev.date"></p>
                                        </div>
                                    </div>
                                    <div class="pub-stars-wrap">
                                        <template x-for="s in (Number(rev.rating) || 5)" :key="s">
                                            <svg viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.9 1.519-.9 1.818 0l1.286 3.957a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.37 2.448a1 1 0 00-.364 1.118l1.287 3.957c.3.9-.755 1.688-1.54 1.118l-3.37-2.448a1 1 0 00-1.175 0l-3.37 2.448c-.784.57-1.838-.218-1.54-1.118l1.287-3.957a1 1 0 00-.364-1.118L2.05 9.384c-.783-.57-.38-1.81.588-1.81h4.162a1 1 0 00.95-.69l1.286-3.957z"/></svg>
                                        </template>
                                    </div>
                                </div>
                                <p class="pub-review-text">"<span x-text="rev.comment"></span>"</p>
                            </div>
                        </template>

                        <div x-show="reviewsList.length === 0" style="font-size: 13.5px; color: #64748B; margin: 0; text-align: center; padding: 24px;">
                            No candidate reviews recorded yet. Be the first intern to leave feedback!
                        </div>
                    </div>
                </div>

            </div><!-- /.pub-left-col -->

            <!-- Right Column (Sidebar details) -->
            <div class="pub-right-col">

                <!-- 1. Quick Facts Card -->
                <div class="pub-card">
                    <div class="pub-card-header" style="margin-bottom: 16px;">
                        <div class="pub-card-title-wrap">
                            <div class="pub-card-icon-box">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                            </div>
                            <h3 class="pub-card-title">Company Facts</h3>
                        </div>
                    </div>

                    <div class="pub-detail-list">
                        <div class="pub-detail-item">
                            <div class="pub-detail-icon">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>
                                </svg>
                            </div>
                            <div>
                                <div class="pub-detail-label">Industry</div>
                                <div class="pub-detail-val"><?= htmlspecialchars($industry) ?></div>
                            </div>
                        </div>

                        <?php if ($showSize): ?>
                            <div class="pub-detail-item">
                                <div class="pub-detail-icon">
                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <div class="pub-detail-label">Organization Size</div>
                                    <div class="pub-detail-val"><?= htmlspecialchars($size) ?></div>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if ($showLocation): ?>
                            <div class="pub-detail-item">
                                <div class="pub-detail-icon">
                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <div class="pub-detail-label">Headquarters</div>
                                    <div class="pub-detail-val"><?= htmlspecialchars($location) ?></div>
                                </div>
                            </div>
                        <?php endif; ?>

                        <div class="pub-detail-item">
                            <div class="pub-detail-icon">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                                </svg>
                            </div>
                            <div>
                                <div class="pub-detail-label">Official Website</div>
                                <div class="pub-detail-val">
                                    <a href="<?= htmlspecialchars($website) ?>" target="_blank" rel="noopener">
                                        <?= htmlspecialchars($webDisplay) ?> ↗
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="pub-detail-item">
                            <div class="pub-detail-icon" style="color: #10B981;">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                            </div>
                            <div>
                                <div class="pub-detail-label">Verification Status</div>
                                <div class="pub-detail-val" style="display: flex; align-items: center; gap: 6px;">
                                    <span style="color: #166534; font-weight: 700;">Verified Company</span>
                                    <span style="font-size: 11px; background: #DCFCE7; color: #166534; font-weight: 700; padding: 2px 6px; border-radius: 4px;">Active</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 2. Contact Information (Respects Privacy Toggles) -->
                <?php if ($hasContact): ?>
                    <div class="pub-card">
                        <div class="pub-card-header" style="margin-bottom: 14px;">
                            <div class="pub-card-title-wrap">
                                <div class="pub-card-icon-box">
                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <h3 class="pub-card-title">Get in Touch</h3>
                            </div>
                        </div>

                        <?php if ($showEmail): ?>
                            <div class="pub-contact-row">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                <a href="mailto:<?= htmlspecialchars($email) ?>" style="color: #1E293B; text-decoration: none; font-weight: 600;"><?= htmlspecialchars($email) ?></a>
                            </div>
                        <?php endif; ?>

                        <?php if ($showPhone): ?>
                            <div class="pub-contact-row">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                                <span style="color: #1E293B; font-weight: 600;"><?= htmlspecialchars($phone) ?></span>
                            </div>
                        <?php endif; ?>

                        <?php if ($showSocials && !empty($socials)): ?>
                            <div class="pub-social-icons-row">
                                <?php if (!empty($socials['website'])): ?>
                                    <a href="<?= htmlspecialchars($socials['website']) ?>" target="_blank" rel="noopener" class="pub-social-btn" title="Website">
                                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                                        </svg>
                                    </a>
                                <?php endif; ?>
                                <?php if (!empty($socials['linkedin'])): ?>
                                    <a href="<?= htmlspecialchars($socials['linkedin']) ?>" target="_blank" rel="noopener" class="pub-social-btn" title="LinkedIn">
                                        <svg fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/>
                                        </svg>
                                    </a>
                                <?php endif; ?>
                                <?php if (!empty($socials['twitter'])): ?>
                                    <a href="<?= htmlspecialchars($socials['twitter']) ?>" target="_blank" rel="noopener" class="pub-social-btn" title="X (Twitter)">
                                        <svg fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                                        </svg>
                                    </a>
                                <?php endif; ?>
                                <?php if (!empty($socials['instagram'])): ?>
                                    <a href="<?= htmlspecialchars($socials['instagram']) ?>" target="_blank" rel="noopener" class="pub-social-btn" title="Instagram">
                                        <svg fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                                        </svg>
                                    </a>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <!-- 3. Jenny AI Guide Card -->
                <div class="pub-jenny-card">
                    <div class="pub-jenny-header">
                        <div class="pub-jenny-avatar-box">
                            <img src="<?= asset('assets/images/jenny_ai/jenny_ai_hi.png') ?>" alt="Jenny AI Assistant">
                        </div>
                        <div>
                            <h4 style="font-size: 15px; font-weight: 800; margin: 0; color: #FFFFFF;">Jenny AI Assistant</h4>
                            <p style="font-size: 11px; color: #A5B4FC; margin: 2px 0 0; font-weight: 600;">Platform Career Co-pilot</p>
                        </div>
                    </div>
                    <p style="font-size: 13px; color: #CBD5E1; line-height: 1.55; margin: 0;">
                        Have questions about <?= htmlspecialchars($companyName) ?>? Ask Jenny for culture insights, interview preparation tips, or resume tailoring recommendations.
                    </p>
                    <button type="button" class="pub-jenny-btn" @click="window.dispatchEvent(new CustomEvent('open-jenny-chat', { detail: { prompt: 'Can you tell me more about <?= addslashes(htmlspecialchars($companyName)) ?> and what they look for in interns?' } }))">
                        <svg style="width: 15px; height: 15px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                        <span>Ask Jenny About <?= htmlspecialchars($companyName) ?></span>
                    </button>
                </div>

                <!-- 4. Similar Organizations to Explore -->
                <?php if (!empty($similarCompanies)): ?>
                    <div class="pub-card">
                        <div class="pub-card-header" style="margin-bottom: 14px;">
                            <div class="pub-card-title-wrap">
                                <div class="pub-card-icon-box">
                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                </div>
                                <h3 class="pub-card-title">Explore Other Companies</h3>
                            </div>
                        </div>

                        <div class="pub-similar-list">
                            <?php foreach ($similarCompanies as $sim): ?>
                                <a href="<?= url('/company/' . $sim['slug']) ?>" class="pub-similar-item">
                                    <div class="pub-similar-left">
                                        <div class="pub-similar-avatar" style="background-color: <?= htmlspecialchars($sim['logo_bg']) ?>; color: <?= htmlspecialchars($sim['logo_color']) ?>;">
                                            <?php if (!empty($sim['logo'])): ?>
                                                <img src="<?= htmlspecialchars($sim['logo']) ?>" alt="<?= htmlspecialchars($sim['name']) ?>">
                                            <?php else: ?>
                                                <span><?= htmlspecialchars($sim['initial']) ?></span>
                                            <?php endif; ?>
                                        </div>
                                        <div style="min-width: 0;">
                                            <h5 class="pub-similar-name"><?= htmlspecialchars($sim['name']) ?></h5>
                                            <p class="pub-similar-industry"><?= htmlspecialchars($sim['industry']) ?></p>
                                        </div>
                                    </div>
                                    <?php if ($sim['openings_cnt'] > 0): ?>
                                        <span class="pub-similar-pill"><?= $sim['openings_cnt'] ?> <?= $sim['openings_cnt'] === 1 ? 'role' : 'roles' ?></span>
                                    <?php endif; ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

            </div><!-- /.pub-right-col -->

        </div><!-- /.pub-layout-grid -->

    </div><!-- /.pub-container -->

    <!-- ══ Modal: Write a Candidate Review (Layered Stacked Cards Effect) ══ -->
    <div x-show="writeReviewModal" 
         x-cloak
         style="display: none;"
         class="pub-modal-backdrop"
         @click.self="writeReviewModal = false"
         @keydown.escape.window="writeReviewModal = false"
         x-transition:enter="transition ease-out duration-250"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        
        <div class="pub-modal-stacked-wrap" @click.stop
             x-show="writeReviewModal"
             x-transition:enter="transition ease-out duration-250"
             x-transition:enter-start="opacity-0 translate-y-3 scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0 scale-100"
             x-transition:leave-end="opacity-0 translate-y-2 scale-95">

            <!-- Stacked Faint Card 2 (Backmost) -->
            <div class="pub-modal-card-stack-bg pub-modal-card-stack-2"></div>
            <!-- Stacked Faint Card 1 (Middle) -->
            <div class="pub-modal-card-stack-bg pub-modal-card-stack-1"></div>

            <!-- Main Dialog Card -->
            <div class="pub-modal-dialog">
                <!-- Header -->
                <div class="pub-modal-header">
                    <div style="display: flex; align-items: flex-start; gap: 14px;">
                        <div class="pub-modal-header-icon-box">
                            <svg style="width: 22px; height: 22px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.9 1.519-.9 1.818 0l1.518 4.674a1 1 0 00.95.69h4.907c.961 0 1.357 1.236.588 1.81l-3.97 2.887a1 1 0 00-.364 1.118l1.518 4.674c.3.9-.755 1.688-1.538 1.118l-3.97-2.887a1 1 0 00-1.176 0l-3.97 2.887c-.783.57-1.838-.218-1.538-1.118l1.518-4.674a1 1 0 00-.364-1.118l-3.97-2.887c-.768-.574-.372-1.81.588-1.81h4.907a1 1 0 00.95-.69l1.518-4.674z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="pub-modal-title">Share Your Experience</h3>
                            <p class="pub-modal-subtext">Help other students by sharing what it was really like interning at <?= htmlspecialchars($companyName) ?>.</p>
                        </div>
                    </div>
                    <button type="button" @click="writeReviewModal = false" class="pub-modal-close-btn" aria-label="Close modal">
                        <svg style="width: 20px; height: 20px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Star Rating Selector (Custom SVG Star Set & Dynamic Label) -->
                <div class="pub-modal-rating-card">
                    <span class="pub-modal-rating-title">OVERALL INTERNSHIP RATING</span>
                    <div class="pub-star-row">
                        <template x-for="star in [1, 2, 3, 4, 5]" :key="star">
                            <button type="button" 
                                    @click="reviewRating = star"
                                    @mouseenter="hoverRating = star"
                                    @mouseleave="hoverRating = reviewRating"
                                    class="pub-star-btn"
                                    :style="(hoverRating >= star || (!hoverRating && reviewRating >= star)) ? 'opacity: 1;' : 'opacity: 0.3;'">
                                <svg class="pub-star-svg" 
                                     :fill="(hoverRating >= star || (!hoverRating && reviewRating >= star)) ? '#F59E0B' : '#CBD5E1'" 
                                     :stroke="(hoverRating >= star || (!hoverRating && reviewRating >= star)) ? '#D97706' : '#94A3B8'" 
                                     stroke-width="0.5"
                                     viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.9 1.519-.9 1.818 0l1.286 3.957a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.37 2.448a1 1 0 00-.364 1.118l1.287 3.957c.3.9-.755 1.688-1.54 1.118l-3.37-2.448a1 1 0 00-1.175 0l-3.37 2.448c-.784.57-1.838-.218-1.54-1.118l1.287-3.957a1 1 0 00-.364-1.118L2.05 9.384c-.783-.57-.38-1.81.588-1.81h4.162a1 1 0 00.95-.69l1.286-3.957z"/>
                                </svg>
                            </button>
                        </template>
                    </div>
                    <div class="pub-modal-rating-label" x-text="ratingLabel"></div>
                </div>

                <!-- 
                    BACKEND & DATABASE INTEGRATION CONTRACT:
                    ===================================================================
                    Candidate Authentication & Identity Contract:
                    1. Only authenticated candidate accounts can write and submit reviews.
                    2. The 'Your Name' field is permanently locked (readonly) and bound to
                       the authenticated candidate's registered name ($_SESSION['profile_name'] 
                       or CandidateProfile database record).
                    3. Anonymous posting is strictly disabled across the platform to ensure
                       review authenticity, employer trust, and verified student feedback.
                    4. Upon database integration, the submission payload binds candidate_id,
                       company_id, role, rating (1-5), comments, and recommend flag into 
                       the `company_reviews` database table.
                    ===================================================================
                -->
                <div style="display: flex; flex-direction: column; gap: 16px;">
                    <!-- 1. Name Field (Permanently Locked for Authenticated Candidate) -->
                    <div>
                        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 6px;">
                            <label style="font-size: 12.5px; font-weight: 700; color: #334155;">Your Name / Initials</label>
                            <span style="font-size: 11px; font-weight: 700; color: #4F48E6; background: #EEF2FF; padding: 2px 8px; border-radius: 6px; display: inline-flex; align-items: center; gap: 4px; white-space: nowrap !important; flex-shrink: 0 !important;">
                                <svg style="width: 12px; height: 12px; flex-shrink: 0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                                <span>Verified Candidate</span>
                            </span>
                        </div>
                        <div style="position: relative;">
                            <input type="text" 
                                   :value="candidateRegisteredName" 
                                   readonly 
                                   style="background: #F8FAFC; color: #334155; font-weight: 600; cursor: not-allowed; border: 1px solid #E2E8F0;"
                                   class="pub-modal-input"
                                   title="Your name is synced with your verified candidate account and cannot be modified.">
                        </div>
                    </div>

                    <!-- 2. Role / Department Dropdown (Image 1 Rectification) -->
                    <div>
                        <label style="display: block; font-size: 12.5px; font-weight: 700; color: #334155; margin-bottom: 6px;">
                            Which role did you apply for? <span style="font-weight: 500; color: #94A3B8;">(optional)</span>
                        </label>

                        <div class="db-dropdown-wrap" @click.outside="roleDropdownOpen = false" style="position: relative; width: 100%;">
                            <button type="button" 
                                    @click="roleDropdownOpen = !roleDropdownOpen" 
                                    class="db-dropdown-btn"
                                    :class="{ 'active': roleDropdownOpen }">
                                <span class="truncate whitespace-nowrap min-w-0" 
                                      :style="selectedRole ? 'color: #0F172A; font-weight: 600;' : 'color: #94A3B8; font-weight: 400;'"
                                      x-text="selectedRole ? selectedRole : 'Select the role you applied for...'"></span>
                                <svg class="db-dropdown-chevron" 
                                     :style="roleDropdownOpen ? 'transform: rotate(180deg)' : ''" 
                                     fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m6 9 6 6 6-6"/>
                                </svg>
                            </button>

                            <!-- Custom Rounded Menu with Column Layout -->
                            <div x-show="roleDropdownOpen" 
                                 x-transition:enter="transition ease-out duration-100" 
                                 x-transition:enter-start="transform opacity-0 scale-95" 
                                 x-transition:enter-end="transform opacity-100 scale-100" 
                                 x-transition:leave="transition ease-in duration-75" 
                                 x-transition:leave-start="transform opacity-100 scale-100" 
                                 x-transition:leave-end="transform opacity-0 scale-95"
                                 class="db-dropdown-menu"
                                 style="display: none;">
                                
                                <!-- Header Strip -->
                                <div style="display: flex; align-items: center; justify-content: space-between; padding: 6px 10px 8px; border-bottom: 1px solid #F1F5F9; margin-bottom: 2px;">
                                    <span style="font-size: 10.5px; font-weight: 700; color: #64748B; text-transform: uppercase; letter-spacing: 0.06em;">COMPANY ROLES</span>
                                    <span style="font-size: 10.5px; font-weight: 700; color: #4F48E6;" x-text="companyRoles.length + ' AVAILABLE'"></span>
                                </div>

                                <!-- Roles List -->
                                <div style="display: flex; flex-direction: column; gap: 2px;">
                                    <template x-for="(rTitle, rIdx) in companyRoles" :key="rIdx">
                                        <div @click="selectRole(rTitle)"
                                             class="db-dropdown-item"
                                             :class="{ 'is-selected': selectedRole === rTitle }">
                                            <span style="text-align: left; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; flex: 1; min-width: 0;" x-text="rTitle"></span>
                                            <svg x-show="selectedRole === rTitle" style="width: 14px; height: 14px; color: #4F48E6; flex-shrink: 0; margin-left: 10px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                            </svg>
                                        </div>
                                    </template>
                                </div>

                                <!-- Other / Not listed -->
                                <div style="border-top: 1px solid #F1F5F9; margin-top: 3px; padding-top: 3px;">
                                    <div @click="selectRole('Other / Not listed')"
                                         class="db-dropdown-item"
                                         :class="{ 'is-selected': selectedRole === 'Other / Not listed' }">
                                        <span style="text-align: left; flex: 1;">Other / Not listed</span>
                                        <svg x-show="selectedRole === 'Other / Not listed'" style="width: 14px; height: 14px; color: #4F48E6; flex-shrink: 0; margin-left: 10px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Custom text input if Other is selected -->
                        <div x-show="selectedRole === 'Other / Not listed'" 
                             x-transition:enter="transition ease-out duration-150"
                             x-transition:enter-start="opacity-0 -translate-y-1"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             style="margin-top: 8px;">
                            <input type="text" x-model="customRoleText" placeholder="Specify the role you applied for (e.g. Finance Intern)" class="pub-modal-input" style="font-size: 13px;">
                        </div>
                    </div>

                    <!-- 3. Feedback Field -->
                    <div>
                        <label style="display: block; font-size: 12.5px; font-weight: 700; color: #334155; margin-bottom: 6px;">Your Honest Feedback &amp; Experience</label>
                        <textarea x-model="reviewComment" rows="4" 
                                  placeholder="What was mentorship, work culture, and the projects you worked on like?" 
                                  class="pub-modal-textarea" required></textarea>
                    </div>

                    <!-- 4. Recommendation Checkbox -->
                    <label class="pub-modal-checkbox-label">
                        <input type="checkbox" x-model="reviewRecommend" class="pub-modal-checkbox">
                        <span>I would recommend this internship to other students.</span>
                    </label>
                </div>

                <!-- Footer Buttons -->
                <div class="pub-modal-footer">
                    <button type="button" @click="writeReviewModal = false" class="pub-btn-modal-cancel">
                        Cancel
                    </button>
                    <button type="button" @click="submitReview()" class="pub-btn-modal-submit">
                        Submit Review
                    </button>
                </div>

            </div><!-- /.pub-modal-dialog -->
        </div><!-- /.pub-modal-stacked-wrap -->
    </div>

    <!-- Lightbox Photo Preview Modal -->
    <div x-show="lightboxOpen" 
        style="display: none;"
        class="pub-lightbox-backdrop"
        @click.self="lightboxOpen = false"
        @keydown.escape.window="lightboxOpen = false"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0">
        
        <div class="pub-lightbox-dialog" @click.stop>
            <button type="button" class="pub-lightbox-close" @click="lightboxOpen = false" aria-label="Close photo preview">
                <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
            <img :src="lightboxImg" alt="Workspace Photo Preview" class="pub-lightbox-img">
        </div>
    </div>

</div>
