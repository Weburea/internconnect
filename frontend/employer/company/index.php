<?php
declare(strict_types=1);
/**
 * View: Company Profile (Employer Portal)
 * Route: /employer/company, /companies, /company
 * Layout: dashboard (custom employer sidebar layout — white background theme)
 * 
 * @var array $employer Company & profile information
 * @var array $about    About description and tags
 * @var array $culture  Culture & values description and tags
 * @var array $gallery  Gallery image URLs
 * @var array $reviews  Candidate and intern reviews
 */

$companyName = $employer['company_name'] ?? 'TechFlow Systems';
$slogan      = $employer['slogan'] ?? 'Building innovative digital experiences for the future';
$industry    = $employer['industry'] ?? 'Software Development';
$location    = $employer['location'] ?? 'Lagos, Nigeria';
$size        = $employer['size'] ?? '201–500 employees';
$website     = $employer['website'] ?? 'https://techflow.io';
$webDisplay  = $employer['website_display'] ?? 'techflow.io';
$email       = $employer['email'] ?? 'careers@techflow.io';
$phone       = $employer['phone'] ?? '+234 801 234 5678';
$logoUrl     = $employer['logo'] ?? 'https://res.cloudinary.com/weburea/image/upload/v1789424092/internConnect/images/hk_logo.jpg';
$bannerUrl   = $employer['banner'] ?? 'https://res.cloudinary.com/weburea/image/upload/v1789424121/internConnect/images/hk_banner.png';
$strength    = (int)($employer['profile_strength'] ?? 78);
?>
<style>
    /* ── Company Profile Page Custom Styles (White Background Theme) ── */
    .comp-profile-page {
        max-width: 1240px;
        margin: 0 auto;
        padding-bottom: 40px;
    }

    .comp-breadcrumb-bar {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 20px;
    }

    .comp-back-link {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 13.5px;
        font-weight: 600;
        color: #475569;
        text-decoration: none;
        transition: color 0.15s ease;
    }

    .comp-back-link:hover {
        color: #4F47E6;
    }

    .comp-back-link svg {
        width: 16px;
        height: 16px;
    }

    /* ── Hero Card & Cover Banner ── */
    .comp-hero-card {
        background: #FFFFFF;
        border: 1px solid #E2E8F0;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.03);
        margin-bottom: 24px;
    }

    .comp-cover-wrap {
        position: relative;
        height: 220px;
        background: #0F172A;
        overflow: hidden;
    }

    .comp-cover-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center;
        display: block;
    }

    .comp-cover-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(180deg, rgba(15, 23, 42, 0.15) 0%, rgba(15, 23, 42, 0.6) 100%);
    }

    .comp-cover-edit-btn {
        position: absolute;
        top: 16px;
        right: 16px;
        background: rgba(15, 23, 42, 0.75);
        backdrop-filter: blur(8px);
        color: #FFFFFF;
        border: 1px solid rgba(255, 255, 255, 0.25);
        padding: 7px 14px;
        border-radius: 10px;
        font-size: 12px;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        text-decoration: none;
        cursor: pointer;
        z-index: 15;
        transition: all 0.2s ease;
    }

    .comp-cover-edit-btn:hover {
        background: rgba(15, 23, 42, 0.95);
        border-color: #FFFFFF;
        color: #FFFFFF;
        transform: translateY(-1px);
    }

    .comp-cover-edit-btn svg {
        width: 14px;
        height: 14px;
    }

    .comp-hero-body {
        position: relative;
        padding: 0 32px 28px;
    }

    .comp-logo-avatar-wrap {
        position: absolute;
        top: -52px;
        left: 32px;
        width: 104px;
        height: 104px;
        border-radius: 50%;
        background: #FFFFFF;
        padding: 4px;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.1);
        z-index: 10;
    }

    .comp-logo-avatar {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        object-fit: cover;
        display: block;
        background: #0F172A;
    }

    .comp-logo-edit-trigger {
        position: absolute;
        top: 2px;
        right: 2px;
        width: 26px;
        height: 26px;
        background: #4F47E6;
        color: #FFFFFF;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px solid #FFFFFF;
        box-shadow: 0 2px 5px rgba(0,0,0,0.15);
        cursor: pointer;
        text-decoration: none;
        transition: transform 0.15s ease;
        z-index: 12;
    }

    .comp-logo-edit-trigger:hover {
        transform: scale(1.1);
        background: #4338CA;
    }

    .comp-logo-edit-trigger svg {
        width: 13px;
        height: 13px;
    }

    .comp-logo-badge-check {
        position: absolute;
        bottom: 4px;
        right: 4px;
        width: 22px;
        height: 22px;
        background: #10B981;
        color: #FFFFFF;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2.5px solid #FFFFFF;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .comp-logo-badge-check svg {
        width: 12px;
        height: 12px;
    }

    .comp-hero-header-row {
        padding-top: 60px;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 24px;
        flex-wrap: wrap;
    }

    .comp-hero-titles h1 {
        font-size: 24px;
        font-weight: 800;
        color: #0F172A;
        margin: 0 0 6px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .comp-verified-badge-inline {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 18px;
        height: 18px;
        color: #4F47E6;
    }

    .comp-hero-slogan {
        font-size: 14.5px;
        color: #475569;
        margin: 0 0 14px;
        font-weight: 500;
    }

    .comp-meta-pills {
        display: flex;
        align-items: center;
        gap: 18px;
        flex-wrap: wrap;
    }

    .comp-meta-pill-item {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 13px;
        color: #64748B;
        font-weight: 500;
    }

    .comp-meta-pill-item svg {
        width: 16px;
        height: 16px;
        color: #94A3B8;
        flex-shrink: 0;
    }

    .comp-hero-actions {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-shrink: 0;
    }

    .comp-btn-outline {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #FFFFFF;
        color: #1E293B;
        border: 1.5px solid #CBD5E1;
        font-size: 13.5px;
        font-weight: 700;
        padding: 9px 18px;
        border-radius: 10px;
        text-decoration: none;
        cursor: pointer;
        transition: all 0.15s ease;
    }

    .comp-btn-outline:hover {
        background: #F8FAFC;
        border-color: #94A3B8;
    }

    .comp-btn-primary {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #4F47E6;
        color: #FFFFFF;
        border: none;
        font-size: 13.5px;
        font-weight: 700;
        padding: 10px 20px;
        border-radius: 10px;
        text-decoration: none;
        cursor: pointer;
        box-shadow: 0 4px 12px rgba(79, 70, 230, 0.25);
        transition: all 0.15s ease;
    }

    .comp-btn-primary:hover {
        background: #4338CA;
        transform: translateY(-1px);
    }

    .comp-btn-primary svg, .comp-btn-outline svg {
        width: 16px;
        height: 16px;
    }

    /* ── Single Overview Tab Strip ── */
    .comp-tabs-bar {
        display: flex;
        align-items: center;
        gap: 24px;
        border-bottom: 1px solid #E2E8F0;
        margin-bottom: 24px;
        padding-bottom: 2px;
    }

    .comp-tab-link {
        font-size: 14.5px;
        font-weight: 700;
        color: #4F47E6;
        padding: 8px 4px 12px;
        text-decoration: none;
        position: relative;
        display: inline-block;
    }

    .comp-tab-link::after {
        content: "";
        position: absolute;
        bottom: -2px;
        left: 0;
        right: 0;
        height: 2.5px;
        background: #4F47E6;
        border-radius: 99px;
    }

    /* ── 2-Column Grid Layout ── */
    .comp-layout-grid {
        display: grid;
        grid-template-columns: 1fr 360px;
        gap: 24px;
        align-items: start;
    }

    /* ── Shared Card Styles ── */
    .comp-section-card {
        background: #FFFFFF;
        border: 1px solid #E2E8F0;
        border-radius: 16px;
        padding: 24px 26px;
        margin-bottom: 24px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
    }

    .comp-section-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        margin-bottom: 16px;
    }

    .comp-section-title-wrap {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .comp-section-icon-box {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        background: #EEF2FF;
        color: #4F47E6;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .comp-section-icon-box svg {
        width: 18px;
        height: 18px;
    }

    .comp-section-title {
        font-size: 16px;
        font-weight: 800;
        color: #0F172A;
        margin: 0;
    }

    .comp-section-desc {
        font-size: 13.5px;
        color: #475569;
        line-height: 1.65;
        margin: 0 0 16px;
    }

    /* ── Pill Tags ── */
    .comp-tags-row {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }

    .comp-tag-pill {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-size: 12px;
        font-weight: 600;
        color: #4F47E6;
        background: #F5F3FF;
        border: 1px solid #EDE9FE;
        padding: 4px 12px;
        border-radius: 99px;
    }

    .comp-tag-pill svg {
        width: 13px;
        height: 13px;
    }

    /* ── Gallery Grid ── */
    .comp-gallery-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 12px;
    }

    .comp-gallery-item {
        position: relative;
        aspect-ratio: 4/3;
        border-radius: 12px;
        overflow: hidden;
        background: #F1F5F9;
    }

    .comp-gallery-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        transition: transform 0.3s ease;
    }

    .comp-gallery-item:hover img {
        transform: scale(1.05);
    }

    .comp-gallery-overlay-more {
        position: absolute;
        inset: 0;
        background: rgba(15, 23, 42, 0.6);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #FFFFFF;
        font-size: 16px;
        font-weight: 800;
        cursor: pointer;
    }

    /* ── Review Cards Inside Overview ── */
    .comp-review-item {
        padding: 16px 0;
        border-bottom: 1px solid #F1F5F9;
    }

    .comp-review-item:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }

    .comp-review-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 8px;
    }

    .comp-reviewer-info {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .comp-reviewer-avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        object-fit: cover;
    }

    .comp-reviewer-name {
        font-size: 13.5px;
        font-weight: 700;
        color: #1E293B;
        margin: 0;
    }

    .comp-reviewer-role {
        font-size: 11.5px;
        color: #64748B;
        margin: 0;
    }

    .comp-stars-wrap {
        display: inline-flex;
        align-items: center;
        gap: 2px;
        color: #F59E0B;
    }

    .comp-stars-wrap svg {
        width: 14px;
        height: 14px;
        fill: currentColor;
    }

    .comp-review-text {
        font-size: 13px;
        color: #475569;
        line-height: 1.55;
        margin: 0;
    }

    /* ── Profile Insights (Right Column) ── */
    .comp-insights-bar-bg {
        width: 100%;
        height: 8px;
        background: #F1F5F9;
        border-radius: 99px;
        overflow: hidden;
        margin: 10px 0 16px;
    }

    .comp-insights-bar-fill {
        height: 100%;
        background: #4F47E6;
        border-radius: 99px;
    }

    .comp-insights-list {
        display: flex;
        flex-direction: column;
        gap: 10px;
        margin-bottom: 18px;
    }

    .comp-insights-item {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 12.5px;
        color: #334155;
        font-weight: 500;
    }

    .comp-insights-check {
        width: 16px;
        height: 16px;
        color: #10B981;
        flex-shrink: 0;
    }

    /* ── Contact Info & Socials ── */
    .comp-contact-row {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 13px;
        color: #334155;
        margin-bottom: 12px;
    }

    .comp-contact-row svg {
        width: 16px;
        height: 16px;
        color: #64748B;
        flex-shrink: 0;
    }

    .comp-social-icons-row {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-top: 14px;
        padding-top: 12px;
        border-top: 1px solid #F1F5F9;
    }

    .comp-social-btn {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        background: #F8FAFC;
        border: 1px solid #E2E8F0;
        color: #475569;
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        transition: all 0.15s ease;
    }

    .comp-social-btn:hover {
        background: #EEF2FF;
        border-color: #C7D2FE;
        color: #4F47E6;
    }

    .comp-social-btn svg {
        width: 15px;
        height: 15px;
    }

    /* ── Company Details Key-Value ── */
    .comp-details-list {
        display: flex;
        flex-direction: column;
        gap: 14px;
    }

    .comp-detail-item {
        display: flex;
        align-items: flex-start;
        gap: 12px;
    }

    .comp-detail-icon {
        width: 30px;
        height: 30px;
        border-radius: 8px;
        background: #F8FAFC;
        border: 1px solid #E2E8F0;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #64748B;
        flex-shrink: 0;
    }

    .comp-detail-icon svg {
        width: 15px;
        height: 15px;
    }

    .comp-detail-label {
        font-size: 11px;
        font-weight: 700;
        color: #94A3B8;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 2px;
    }

    .comp-detail-val {
        font-size: 13px;
        font-weight: 600;
        color: #1E293B;
    }

    .comp-detail-val a {
        color: #4F47E6;
        text-decoration: none;
    }

    .comp-detail-val a:hover {
        text-decoration: underline;
    }

    /* Responsive Queries */
    @media (max-width: 1024px) {
        .comp-layout-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 640px) {
        .comp-cover-wrap {
            height: 140px;
        }
        .comp-cover-edit-btn {
            top: 10px;
            right: 10px;
            padding: 5px 10px;
            font-size: 11px;
        }
        .comp-cover-edit-btn span {
            display: none;
        }
        .comp-hero-body {
            padding: 0 16px 20px;
        }
        .comp-logo-avatar-wrap {
            left: 16px;
            top: -40px;
            width: 80px;
            height: 80px;
            padding: 3px;
        }
        .comp-logo-edit-trigger {
            width: 22px;
            height: 22px;
        }
        .comp-logo-edit-trigger svg {
            width: 11px;
            height: 11px;
        }
        .comp-hero-header-row {
            padding-top: 46px;
            flex-direction: column;
            gap: 16px;
        }
        .comp-gallery-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
</style>

<div class="db-shell">

    <!-- ══ SIDEBAR ══ -->
    <?php 
    $activeNav = 'company_overview';
    include dirname(__DIR__, 2) . '/shared/components/employer-sidebar.php'; 
    ?>

    <!-- ══ MAIN ══ -->
    <div class="db-main">

        <!-- ══ TOP NAVBAR ══ -->
        <?php include dirname(__DIR__, 2) . '/shared/components/employer-topnav.php'; ?>

        <!-- Content Area -->
        <div class="db-content">

            <div class="comp-profile-page">

                <!-- Breadcrumb Back Link -->
                <div class="comp-breadcrumb-bar">
                    <a href="<?= url('/employer/dashboard') ?>" class="comp-back-link">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                        </svg>
                        <span>Company Overview</span>
                    </a>
                </div>

                <!-- ── Hero Cover Card ── -->
                <div class="comp-hero-card">
                    <div class="comp-cover-wrap">
                        <img src="<?= htmlspecialchars($bannerUrl) ?>" alt="<?= htmlspecialchars($companyName) ?> Cover" class="comp-cover-img">
                        <div class="comp-cover-overlay"></div>
                    </div>

                    <div class="comp-hero-body">
                        <!-- Circular Avatar with Verified Badge -->
                        <div class="comp-logo-avatar-wrap">
                            <img src="<?= htmlspecialchars($logoUrl) ?>" alt="<?= htmlspecialchars($companyName) ?>" class="comp-logo-avatar">
                            <div class="comp-logo-badge-check" aria-label="Verified Employer">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                        </div>

                        <div class="comp-hero-header-row">
                            <div class="comp-hero-titles">
                                <h1>
                                    <span><?= htmlspecialchars($companyName) ?></span>
                                    <span class="comp-verified-badge-inline" aria-label="Verified Company">
                                        <svg fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                        </svg>
                                    </span>
                                </h1>
                                <p class="comp-hero-slogan"><?= htmlspecialchars($slogan) ?></p>
                                
                                <div class="comp-meta-pills">
                                    <div class="comp-meta-pill-item">
                                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                        </svg>
                                        <span><?= htmlspecialchars($industry) ?></span>
                                    </div>
                                    <div class="comp-meta-pill-item">
                                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                        <span><?= htmlspecialchars($location) ?></span>
                                    </div>
                                    <div class="comp-meta-pill-item">
                                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                        </svg>
                                        <span><?= htmlspecialchars($size) ?></span>
                                    </div>
                                </div>
                            </div>

                            <div class="comp-hero-actions">
                                <a href="<?= url('/employer/profile') ?>" class="comp-btn-outline">
                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                    </svg>
                                    <span>Edit Profile</span>
                                </a>
                                <a href="<?= url('/employer/dashboard') ?>" class="comp-btn-primary">
                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span>Post Opening</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ── Overview Tab Header Strip ── -->
                <div class="comp-tabs-bar">
                    <span class="comp-tab-link">Overview</span>
                </div>

                <!-- ── 2-Column Content Grid ── -->
                <div class="comp-layout-grid">

                    <!-- Left / Main Column (White Cards) -->
                    <div class="comp-left-col">

                        <!-- 1. About Company Card -->
                        <div class="comp-section-card">
                            <div class="comp-section-header">
                                <div class="comp-section-title-wrap">
                                    <div class="comp-section-icon-box">
                                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                        </svg>
                                    </div>
                                    <h3 class="comp-section-title">About Company</h3>
                                </div>
                            </div>
                            <p class="comp-section-desc"><?= htmlspecialchars($about['description'] ?? '') ?></p>
                            <div class="comp-tags-row">
                                <?php foreach (($about['tags'] ?? []) as $tag): ?>
                                    <span class="comp-tag-pill">
                                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14" />
                                        </svg>
                                        <?= htmlspecialchars($tag) ?>
                                    </span>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <!-- 2. Culture & Values Card -->
                        <div class="comp-section-card">
                            <div class="comp-section-header">
                                <div class="comp-section-title-wrap">
                                    <div class="comp-section-icon-box">
                                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                        </svg>
                                    </div>
                                    <h3 class="comp-section-title">Culture & Values</h3>
                                </div>
                            </div>
                            <p class="comp-section-desc"><?= htmlspecialchars($culture['description'] ?? '') ?></p>
                            <div class="comp-tags-row">
                                <?php foreach (($culture['tags'] ?? []) as $tag): ?>
                                    <span class="comp-tag-pill">
                                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        <?= htmlspecialchars($tag) ?>
                                    </span>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <!-- 3. Company Gallery Card -->
                        <div class="comp-section-card">
                            <div class="comp-section-header">
                                <div class="comp-section-title-wrap">
                                    <div class="comp-section-icon-box">
                                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                    <h3 class="comp-section-title">Company Gallery</h3>
                                </div>
                            </div>

                            <div class="comp-gallery-grid">
                                <?php 
                                $galleryList = $gallery ?? [];
                                foreach (array_slice($galleryList, 0, 3) as $imgUrl): ?>
                                    <div class="comp-gallery-item">
                                        <img src="<?= htmlspecialchars($imgUrl) ?>" alt="Company Workspace">
                                    </div>
                                <?php endforeach; ?>
                                
                                <?php if (isset($galleryList[3])): ?>
                                    <div class="comp-gallery-item">
                                        <img src="<?= htmlspecialchars($galleryList[3]) ?>" alt="Company Workspace">
                                        <div class="comp-gallery-overlay-more">
                                            <span>+5</span>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- 4. Company Reviews & Ratings (Integrated Inside Overview) -->
                        <div class="comp-section-card">
                            <div class="comp-section-header">
                                <div class="comp-section-title-wrap">
                                    <div class="comp-section-icon-box">
                                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.9 1.519-.9 1.818 0l1.518 4.674a1 1 0 00.95.69h4.907c.961 0 1.357 1.236.588 1.81l-3.97 2.887a1 1 0 00-.364 1.118l1.518 4.674c.3.9-.755 1.688-1.538 1.118l-3.97-2.887a1 1 0 00-1.176 0l-3.97 2.887c-.783.57-1.838-.218-1.538-1.118l1.518-4.674a1 1 0 00-.364-1.118l-3.97-2.887c-.768-.574-.372-1.81.588-1.81h4.907a1 1 0 00.95-.69l1.518-4.674z"/>
                                        </svg>
                                    </div>
                                    <h3 class="comp-section-title">Candidate & Intern Reviews</h3>
                                </div>
                                <span style="font-size: 12px; font-weight: 700; color: #166534; background: #DCFCE7; padding: 4px 10px; border-radius: 6px;">
                                    4.9 ★ (24 Reviews)
                                </span>
                            </div>

                            <div class="comp-reviews-list">
                                <?php foreach (($reviews ?? []) as $review): ?>
                                    <div class="comp-review-item">
                                        <div class="comp-review-top">
                                            <div class="comp-reviewer-info">
                                                <img src="<?= htmlspecialchars($review['avatar']) ?>" alt="<?= htmlspecialchars($review['name']) ?>" class="comp-reviewer-avatar">
                                                <div>
                                                    <h5 class="comp-reviewer-name"><?= htmlspecialchars($review['name']) ?></h5>
                                                    <p class="comp-reviewer-role"><?= htmlspecialchars($review['role'] . ' • ' . $review['date']) ?></p>
                                                </div>
                                            </div>
                                            <div class="comp-stars-wrap">
                                                <?php for ($s = 0; $s < ($review['rating'] ?? 5); $s++): ?>
                                                    <svg viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.9 1.519-.9 1.818 0l1.286 3.957a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.37 2.448a1 1 0 00-.364 1.118l1.287 3.957c.3.9-.755 1.688-1.54 1.118l-3.37-2.448a1 1 0 00-1.175 0l-3.37 2.448c-.784.57-1.838-.218-1.54-1.118l1.287-3.957a1 1 0 00-.364-1.118L2.05 9.384c-.783-.57-.38-1.81.588-1.81h4.162a1 1 0 00.95-.69l1.286-3.957z"/></svg>
                                                <?php endfor; ?>
                                            </div>
                                        </div>
                                        <p class="comp-review-text">"<?= htmlspecialchars($review['comment']) ?>"</p>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                    </div><!-- /.comp-left-col -->

                    <!-- Right Column (White Cards) -->
                    <div class="comp-right-col">

                        <!-- 1. Profile Insights Card -->
                        <div class="comp-section-card">
                            <div class="comp-section-header" style="margin-bottom: 8px;">
                                <div class="comp-section-title-wrap">
                                    <div class="comp-section-icon-box" style="background: #FDF2F8; color: #DB2777;">
                                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                                        </svg>
                                    </div>
                                    <h3 class="comp-section-title">Profile Insights</h3>
                                </div>
                            </div>

                            <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 12px;">
                                <span style="font-size: 13px; font-weight: 600; color: #475569;">Profile Strength</span>
                                <span style="font-size: 15px; font-weight: 800; color: #4F47E6;"><?= $strength ?>%</span>
                            </div>
                            <div class="comp-insights-bar-bg">
                                <div class="comp-insights-bar-fill" style="width: <?= $strength ?>%;"></div>
                            </div>

                            <div style="font-size: 11px; font-weight: 700; color: #94A3B8; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 10px;">
                                Suggestions
                            </div>
                            <div class="comp-insights-list">
                                <div class="comp-insights-item">
                                    <svg class="comp-insights-check" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span>Complete company description</span>
                                </div>
                                <div class="comp-insights-item">
                                    <svg class="comp-insights-check" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span>Add verified social links</span>
                                </div>
                                <div class="comp-insights-item">
                                    <svg class="comp-insights-check" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span>Upload company high-res logo</span>
                                </div>
                            </div>

                            <div style="display: flex; flex-direction: column; gap: 8px; margin-top: 18px; padding-top: 16px; border-top: 1px solid #F1F5F9;">
                                <a href="<?= url('/company/techflow') ?>" target="_blank" class="comp-btn-primary" style="justify-content: center;">
                                    <span>Preview Public Profile →</span>
                                </a>
                                <a href="<?= url('/employer/profile') ?>" class="comp-btn-outline" style="justify-content: center;">
                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                    </svg>
                                    <span>Edit Profile</span>
                                </a>
                            </div>
                        </div>

                        <!-- 2. Contact Information Card -->
                        <div class="comp-section-card">
                            <div class="comp-section-header" style="margin-bottom: 14px;">
                                <div class="comp-section-title-wrap">
                                    <div class="comp-section-icon-box">
                                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                    <h3 class="comp-section-title">Contact Information</h3>
                                </div>
                            </div>

                            <div class="comp-contact-row">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                <a href="mailto:<?= htmlspecialchars($email) ?>" style="color: #1E293B; text-decoration: none; font-weight: 600;"><?= htmlspecialchars($email) ?></a>
                            </div>

                            <div class="comp-contact-row">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                                <span style="color: #1E293B; font-weight: 600;"><?= htmlspecialchars($phone) ?></span>
                            </div>

                            <!-- Social Icons -->
                            <div class="comp-social-icons-row">
                                <a href="<?= htmlspecialchars($website) ?>" target="_blank" rel="noopener" class="comp-social-btn" aria-label="Website">
                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                                    </svg>
                                </a>
                                <a href="https://linkedin.com" target="_blank" rel="noopener" class="comp-social-btn" aria-label="LinkedIn">
                                    <svg fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/>
                                    </svg>
                                </a>
                                <a href="https://x.com" target="_blank" rel="noopener" class="comp-social-btn" aria-label="X (Twitter)">
                                    <svg fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                                    </svg>
                                </a>
                                <a href="https://instagram.com" target="_blank" rel="noopener" class="comp-social-btn" aria-label="Instagram">
                                    <svg fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                                    </svg>
                                </a>
                                <a href="https://dribbble.com" target="_blank" rel="noopener" class="comp-social-btn" aria-label="Dribbble">
                                    <svg fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 0c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm10.124 10.741c-.48-.093-2.45-.444-4.832-.153.256-.632.492-1.283.699-1.942 2.457.485 3.967 1.583 4.133 2.095zm-5.074-3.328c-.227.697-.482 1.385-.758 2.051-2.709-1.077-5.59-1.579-7.854-1.721.737-1.386 1.838-2.529 3.167-3.303 2.062.775 4.184 1.956 5.445 2.973zm-8.895-1.517c2.193.138 4.975.623 7.604 1.666-.35 1.011-.749 2.001-1.206 2.951-4.004-1.378-7.794-.969-8.473-.872.23-1.458 1.017-2.748 2.075-3.745zm-6.155 6.104c.749-.107 4.98-.679 9.356.828-.312.871-.659 1.724-1.037 2.552-3.864 1.157-7.502 3.655-7.985 4.02-.68-1.748-.734-4.88-.334-7.4zm2.148 8.877c.563-.43 3.947-2.825 7.683-3.921.666 1.622 1.205 3.324 1.613 5.093-3.666 1.047-7.142-.041-9.296-1.172zm11.026-.145c-.394-1.714-.913-3.364-1.558-4.935 2.215-.357 4.145-.043 4.606.045-.446 2.216-1.553 4.008-3.048 4.89z"/>
                                    </svg>
                                </a>
                            </div>
                        </div>

                        <!-- 3. Company Details Card -->
                        <div class="comp-section-card">
                            <div class="comp-section-header" style="margin-bottom: 14px;">
                                <div class="comp-section-title-wrap">
                                    <div class="comp-section-icon-box">
                                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                        </svg>
                                    </div>
                                    <h3 class="comp-section-title">Company Details</h3>
                                </div>
                            </div>

                            <div class="comp-details-list">
                                <div class="comp-detail-item">
                                    <div class="comp-detail-icon">
                                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="comp-detail-label">CAC / RC Registration</div>
                                        <div class="comp-detail-val" style="display: flex; align-items: center; gap: 6px;">
                                            <span><?= htmlspecialchars($employer['cac_number'] ?? 'RC-1849204') ?></span>
                                            <span style="font-size: 11px; background: #DCFCE7; color: #166534; font-weight: 700; padding: 2px 6px; border-radius: 4px;">Verified</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="comp-detail-item">
                                    <div class="comp-detail-icon">
                                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="comp-detail-label">Industry</div>
                                        <div class="comp-detail-val"><?= htmlspecialchars($industry) ?></div>
                                    </div>
                                </div>

                                <div class="comp-detail-item">
                                    <div class="comp-detail-icon">
                                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="comp-detail-label">Company Size</div>
                                        <div class="comp-detail-val"><?= htmlspecialchars($size) ?></div>
                                    </div>
                                </div>

                                <div class="comp-detail-item">
                                    <div class="comp-detail-icon">
                                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="comp-detail-label">Location</div>
                                        <div class="comp-detail-val"><?= htmlspecialchars($location) ?></div>
                                    </div>
                                </div>

                                <div class="comp-detail-item">
                                    <div class="comp-detail-icon">
                                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="comp-detail-label">Website</div>
                                        <div class="comp-detail-val">
                                            <a href="<?= htmlspecialchars($website) ?>" target="_blank" rel="noopener"><?= htmlspecialchars($webDisplay) ?></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div><!-- /.comp-right-col -->

                </div><!-- /.comp-layout-grid -->

            </div><!-- /.comp-profile-page -->

        </div><!-- /.db-content -->

        <!-- Dashboard Footer -->
        <?php include dirname(__DIR__, 2) . '/shared/components/db-footer.php'; ?>

    </div><!-- /.db-main -->

</div><!-- /.db-shell -->
