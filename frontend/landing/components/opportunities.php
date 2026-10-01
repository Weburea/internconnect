<?php

declare(strict_types=1);

/**
 * Component: Landing Page - Featured Opportunities Section
 * Merged existing design with 3-column x 2-row reference layout.
 * 
 * Features:
 * - Header: "LATEST ROLES" eyebrow pill, "Featured Opportunities" headline, subtitle text.
 * - Interactive Filter Bar: Removable filter tag pills with ✕ icons + search bar with "Keywords, Position, Skills" placeholder and filter icon.
 * - 3x2 Card Grid (6 Roles Total):
 *   - Profile photo in top-left (Cloudinary assets)
 *   - Work type badge (Full-Time, Part-Time, Remote, Hybrid)
 *   - Role title & company subtitle
 *   - 1-2 line description
 *   - Stat row: Work type, duration (e.g. 6 Months), stipend in Naira (₦150,000/mo - ₦220,000/mo)
 *   - Full-width "Apply Now" button at the bottom
 * - Footer: "View All Opportunities ->" text-link CTA
 */

$featuredRoles = [
    [
        'title'       => 'UX/UI Designer',
        'company'     => 'Creative Minds Agency',
        'location'    => 'Remote / Lagos',
        'avatar'      => 'https://res.cloudinary.com/weburea/image/upload/v1790818231/01_hpf9or.jpg',
        'description' => 'Design beautiful, intuitive user journeys and high-fidelity interactive prototypes for global web applications.',
        'type'        => 'Full-Time',
        'type_class'  => 'badge-fulltime',
        'duration'    => '6 Months',
        'stipend'     => '₦180,000/mo',
        'category'    => 'UI Designer',
    ],
    [
        'title'       => 'Digital Marketing Specialist',
        'company'     => 'GrowthHackers Inc',
        'location'    => 'Hybrid / Abuja',
        'avatar'      => 'https://res.cloudinary.com/weburea/image/upload/v1790640489/09_woufvl.jpg',
        'description' => 'Manage multi-channel campaigns, optimize SEO funnels, and scale organic social engagement across tech communities.',
        'type'        => 'Part-Time',
        'type_class'  => 'badge-parttime',
        'duration'    => '3 Months',
        'stipend'     => '₦130,000/mo',
        'category'    => 'Marketing',
    ],
    [
        'title'       => 'Backend Developer',
        'company'     => 'TechStack Solutions',
        'location'    => 'Remote / Nigeria',
        'avatar'      => 'https://res.cloudinary.com/weburea/image/upload/v1790640487/08_kd0a4w.jpg',
        'description' => 'Build resilient REST APIs, optimize high-throughput database queries, and streamline core infrastructure microservices.',
        'type'        => 'Remote',
        'type_class'  => 'badge-remote',
        'duration'    => '6 Months',
        'stipend'     => '₦220,000/mo',
        'category'    => 'Engineering',
    ],
    [
        'title'       => 'Mobile App Engineer',
        'company'     => 'Apex Mobility',
        'location'    => 'Remote / Lagos',
        'avatar'      => 'https://res.cloudinary.com/weburea/image/upload/v1790640486/07_ky0sza.jpg',
        'description' => 'Develop high-performance Flutter & React Native mobile applications with offline sync and crisp native UI components.',
        'type'        => 'Full-Time',
        'type_class'  => 'badge-fulltime',
        'duration'    => '6 Months',
        'stipend'     => '₦195,000/mo',
        'category'    => 'Engineering',
    ],
    [
        'title'       => 'Data Analyst & AI Associate',
        'company'     => 'MonieAnalytics',
        'location'    => 'Hybrid / Lagos',
        'avatar'      => 'https://res.cloudinary.com/weburea/image/upload/v1790640485/06_phha53.jpg',
        'description' => 'Clean and model complex operational datasets, build automated dashboards, and assist with predictive candidate matching.',
        'type'        => 'Hybrid',
        'type_class'  => 'badge-hybrid',
        'duration'    => '4 Months',
        'stipend'     => '₦160,000/mo',
        'category'    => 'Data',
    ],
    [
        'title'       => 'Product Management Intern',
        'company'     => 'PayVenture Labs',
        'location'    => 'Remote / Nigeria',
        'avatar'      => 'https://res.cloudinary.com/weburea/image/upload/v1790640484/05_fdbsxq.jpg',
        'description' => 'Conduct user discovery interviews, define product feature specs, and bridge cross-functional sprint teams toward delivery.',
        'type'        => 'Remote',
        'type_class'  => 'badge-remote',
        'duration'    => '6 Months',
        'stipend'     => '₦200,000/mo',
        'category'    => 'Product',
    ],
];
?>

<style>
/* ─── Featured Opportunities Wrapper ─── */
.opportunities-section-wrap {
    position: relative;
    background-color: #F8FAFC;
    padding: 80px 0 96px 0;
    width: 100%;
    overflow: hidden;
    box-sizing: border-box;
    border-top: 1px solid #F1F5F9;
}

@media (min-width: 1024px) {
    .opportunities-section-wrap {
        padding: 96px 0 112px 0;
    }
}

.opportunities-container {
    max-width: 1280px;
    margin: 0 auto;
    padding: 0 24px;
    width: 100%;
    box-sizing: border-box;
}

/* ─── Section Header ─── */
.opportunities-header-box {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    max-width: 760px;
    margin: 0 auto 40px auto;
    gap: 12px;
    width: 100%;
    box-sizing: border-box;
}

.opportunities-eyebrow {
    font-size: 12px;
    font-weight: 700;
    color: #4F48E6;
    letter-spacing: 0.08em;
    text-transform: uppercase;
}

.opportunities-title {
    font-size: clamp(28px, 4vw, 44px);
    line-height: 1.18;
    font-weight: 800;
    color: #000623;
    letter-spacing: -0.025em;
    margin: 0;
    word-break: break-word;
}

.opportunities-subtext {
    font-size: 16px;
    line-height: 1.6;
    color: #475569;
    max-width: 640px;
    margin: 0 auto;
    font-weight: 400;
}

/* ─── Interactive Filter & Search Bar Row ─── */
.opportunities-filter-bar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    margin-bottom: 36px;
    width: 100%;
    box-sizing: border-box;
    flex-wrap: wrap;
}

.opportunities-pills-wrap {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
    min-width: 0;
}

.opportunity-filter-pill {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 7px 14px;
    border-radius: 9999px;
    background: #FFFFFF;
    border: 1px solid #E2E8F0;
    color: #1E293B;
    font-size: 13.5px;
    font-weight: 600;
    white-space: nowrap;
    box-shadow: 0 1px 3px rgba(0, 6, 35, 0.04);
    transition: all 0.2s ease;
}

.opportunity-filter-pill:hover {
    border-color: #CBD5E1;
    background: #F8FAFC;
}

.opportunity-filter-remove {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 18px;
    height: 18px;
    border-radius: 50%;
    background: #F1F5F9;
    color: #64748B;
    border: none;
    cursor: pointer;
    transition: all 0.15s ease;
    padding: 0;
}

.opportunity-filter-remove:hover {
    background: #E2E8F0;
    color: #0F172A;
}

.opportunity-pill-suggestion {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 12px;
    border-radius: 9999px;
    background: transparent;
    border: 1px dashed #CBD5E1;
    color: #64748B;
    font-size: 13px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s ease;
}

.opportunity-pill-suggestion:hover {
    border-color: #4F48E6;
    color: #4F48E6;
    background: #EEF2FF;
}

/* Search Input Box */
.opportunity-search-box {
    position: relative;
    display: flex;
    align-items: center;
    background: #FFFFFF;
    border: 1px solid #E2E8F0;
    border-radius: 9999px;
    padding: 4px 6px 4px 14px;
    box-shadow: 0 2px 8px -2px rgba(0, 6, 35, 0.05);
    transition: all 0.2s ease;
    width: 100%;
    max-width: 350px;
    box-sizing: border-box;
}

.opportunity-search-box:focus-within {
    border-color: #4F48E6;
    box-shadow: 0 0 0 3px rgba(79, 72, 230, 0.12);
}

.opportunity-search-icon {
    width: 16px;
    height: 16px;
    color: #94A3B8;
    flex-shrink: 0;
    margin-right: 8px;
}

.opportunity-search-input {
    width: 100%;
    min-width: 0;
    border: none;
    outline: none;
    background: transparent;
    font-size: 13.5px;
    color: #0F172A;
    font-family: inherit;
    padding: 6px 0;
}

.opportunity-search-input::placeholder {
    color: #94A3B8;
    font-weight: 400;
}

.opportunity-search-filter-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: #F1F5F9;
    color: #475569;
    border: none;
    cursor: pointer;
    flex-shrink: 0;
    transition: all 0.2s ease;
}

.opportunity-search-filter-btn:hover {
    background: #EEF2FF;
    color: #4F48E6;
}

/* ─── 3-Column x 2-Row Opportunities Grid ─── */
.opportunities-cards-grid {
    display: grid;
    grid-template-columns: minmax(0, 1fr) !important;
    gap: 24px;
    width: 100% !important;
    max-width: 100% !important;
    min-width: 0 !important;
    box-sizing: border-box !important;
}

@media (min-width: 640px) {
    .opportunities-cards-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
        gap: 24px;
    }
}

@media (min-width: 1024px) {
    .opportunities-cards-grid {
        grid-template-columns: repeat(3, minmax(0, 1fr)) !important;
        gap: 28px;
    }
}

/* ─── Opportunity Card ─── */
.opportunity-card {
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

.opportunity-card:hover {
    transform: translateY(-3px);
    border-color: #CBD5E1;
    box-shadow: 0 16px 36px -4px rgba(0, 6, 35, 0.09), 0 4px 12px -2px rgba(0, 6, 35, 0.03);
}

/* Card Header: Avatar & Badge */
.opportunity-card-top {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 12px;
}

.opportunity-avatar-img {
    width: 54px;
    height: 54px;
    border-radius: 50%;
    object-fit: cover;
    flex-shrink: 0;
    border: 2px solid #F8FAFC;
    box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
}

.opportunity-type-badge {
    display: inline-flex;
    align-items: center;
    padding: 4px 12px;
    border-radius: 9999px;
    font-size: 11.5px;
    font-weight: 700;
    letter-spacing: 0.02em;
    white-space: nowrap;
}

.badge-fulltime {
    background-color: #ECFDF5;
    color: #059669;
    border: 1px solid #D1FAE5;
}

.badge-parttime {
    background-color: #EEF2FF;
    color: #4F48E6;
    border: 1px solid #E0E7FF;
}

.badge-remote {
    background-color: #F5F3FF;
    color: #7C3AED;
    border: 1px solid #EDE9FE;
}

.badge-hybrid {
    background-color: #EFF6FF;
    color: #2563EB;
    border: 1px solid #DBEAFE;
}

/* Card Titles & Description */
.opportunity-role-title {
    font-size: 18.5px;
    font-weight: 700;
    color: #000623;
    line-height: 1.3;
    margin: 14px 0 3px 0;
    transition: color 0.15s ease;
}

.opportunity-card:hover .opportunity-role-title {
    color: #4F48E6;
}

.opportunity-role-company {
    font-size: 13.5px;
    font-weight: 500;
    color: #64748B;
    margin: 0 0 14px 0;
    display: flex;
    align-items: center;
    gap: 6px;
}

.opportunity-desc-text {
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

/* Stats Row: Employment Type | Duration | Stipend */
.opportunity-stats-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: #F8FAFC;
    border: 1px solid #F1F5F9;
    border-radius: 12px;
    padding: 10px 14px;
    margin-bottom: 20px;
    gap: 8px;
    box-sizing: border-box;
}

.opportunity-stat-item {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 12px;
    font-weight: 500;
    color: #475569;
    white-space: nowrap;
    min-width: 0;
}

.opportunity-stat-item.stat-stipend {
    font-weight: 700;
    color: #000623;
}

.opportunity-stat-icon {
    width: 14px;
    height: 14px;
    color: #4F48E6;
    flex-shrink: 0;
}

.opportunity-stat-divider {
    width: 1px;
    height: 16px;
    background: #E2E8F0;
    flex-shrink: 0;
}

/* Full-Width Action Button */
.opportunity-card-btn {
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    gap: 8px !important;
    width: 100% !important;
    padding: 12px 20px !important;
    background-color: #4F48E6 !important;
    color: #FFFFFF !important;
    font-weight: 600 !important;
    font-size: 14px !important;
    border-radius: 12px !important;
    text-decoration: none !important;
    transition: all 0.2s ease !important;
    white-space: nowrap !important;
    flex-shrink: 0 !important;
    box-shadow: 0 4px 14px rgba(79, 72, 230, 0.22) !important;
    box-sizing: border-box !important;
}

.opportunity-card-btn:hover {
    background-color: #4338CA !important;
    box-shadow: 0 6px 18px rgba(79, 72, 230, 0.35) !important;
    transform: translateY(-1px);
}

.opportunity-btn-icon {
    width: 15px;
    height: 15px;
    flex-shrink: 0;
    transition: transform 0.2s ease;
}

.opportunity-card-btn:hover .opportunity-btn-icon {
    transform: translateX(3px);
}

/* ─── Footer: View All Opportunities ─── */
.opportunities-footer-wrap {
    margin-top: 48px;
    text-align: center;
    display: flex;
    justify-content: center;
}

.opportunities-view-all-link {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-size: 15px;
    font-weight: 600;
    color: #4F48E6;
    text-decoration: none;
    transition: color 0.2s ease;
    white-space: nowrap !important;
    flex-shrink: 0 !important;
}

.opportunities-view-all-link:hover {
    color: #3730A3;
}

.opportunities-view-all-icon {
    width: 18px;
    height: 18px;
    flex-shrink: 0;
    transition: transform 0.2s ease;
}

.opportunities-view-all-link:hover .opportunities-view-all-icon {
    transform: translateX(4px);
}

/* Mobile Responsiveness */
@media (max-width: 768px) {
    .opportunities-filter-bar {
        flex-direction: column;
        align-items: stretch;
        gap: 14px;
    }
    
    .opportunity-search-box {
        max-width: 100%;
    }
    
    .opportunities-pills-wrap {
        width: 100%;
    }
}

@media (max-width: 640px) {
    .opportunities-section-wrap {
        padding: 56px 0 72px 0;
    }
    
    .opportunities-container {
        padding: 0 16px;
    }

    .opportunity-stats-row {
        padding: 8px 10px;
        gap: 4px;
    }

    .opportunity-stat-item {
        font-size: 11px;
        gap: 4px;
    }
}
</style>

<section id="opportunities-section" class="opportunities-section-wrap" x-data="opportunitiesFilterComponent()">
    <div class="opportunities-container">
        
        <!-- Section Header (Preserved as requested) -->
        <div class="opportunities-header-box">
            <span class="opportunities-eyebrow">Latest Roles</span>
            <h2 class="opportunities-title">
                Featured Opportunities
            </h2>
            <p class="opportunities-subtext">
                Explore a handpicked selection of top-tier internships from fast-growing startups and established tech leaders.
            </p>
        </div>

        <!-- Filter Bar: Removable Tags & Search Bar -->
        <div class="opportunities-filter-bar">
            
            <!-- Removable Tag Pills -->
            <div class="opportunities-pills-wrap">
                <template x-for="(tag, index) in activeFilters" :key="index">
                    <span class="opportunity-filter-pill">
                        <span x-text="tag"></span>
                        <button type="button" @click="removeFilter(tag)" class="opportunity-filter-remove" :title="'Remove ' + tag">
                            <svg width="11" height="11" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </span>
                </template>

                <!-- Suggested Quick Categories when filters are removed -->
                <button type="button" x-show="!activeFilters.includes('UI Designer')" @click="addFilter('UI Designer')" class="opportunity-pill-suggestion">
                    + UI Designer
                </button>
                <button type="button" x-show="!activeFilters.includes('Remote')" @click="addFilter('Remote')" class="opportunity-pill-suggestion">
                    + Remote
                </button>
                <button type="button" x-show="!activeFilters.includes('Full-Time')" @click="addFilter('Full-Time')" class="opportunity-pill-suggestion">
                    + Full-Time
                </button>
                <button type="button" x-show="!activeFilters.includes('Engineering')" @click="addFilter('Engineering')" class="opportunity-pill-suggestion">
                    + Engineering
                </button>
            </div>

            <!-- Search Input with Filter Dropdown Icon -->
            <div class="opportunity-search-box">
                <svg class="opportunity-search-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input 
                    type="text" 
                    x-model="searchQuery"
                    placeholder="Keywords, Position, Skills" 
                    class="opportunity-search-input"
                    aria-label="Search opportunities"
                />
                <button type="button" class="opportunity-search-filter-btn" title="Filter options" @click="toggleFilterDropdown()">
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                </button>
            </div>

        </div>

        <!-- 3-Column x 2-Row Opportunities Grid (6 Cards Total) -->
        <div class="opportunities-cards-grid">
            <?php foreach ($featuredRoles as $role): ?>
                <div class="opportunity-card" x-show="matchesSearch(<?= htmlspecialchars(json_encode($role), ENT_QUOTES) ?>)">
                    <div>
                        <!-- Card Top: Avatar & Employment Badge -->
                        <div class="opportunity-card-top">
                            <img src="<?= htmlspecialchars($role['avatar']) ?>" alt="<?= htmlspecialchars($role['company']) ?>" class="opportunity-avatar-img" loading="lazy">
                            <span class="opportunity-type-badge <?= htmlspecialchars($role['type_class']) ?>">
                                <?= htmlspecialchars($role['type']) ?>
                            </span>
                        </div>

                        <!-- Role Title & Subtitle -->
                        <h3 class="opportunity-role-title">
                            <?= htmlspecialchars($role['title']) ?>
                        </h3>
                        <p class="opportunity-role-company">
                            <span><?= htmlspecialchars($role['company']) ?></span>
                            <span>•</span>
                            <span><?= htmlspecialchars($role['location']) ?></span>
                        </p>

                        <!-- Description -->
                        <p class="opportunity-desc-text">
                            <?= htmlspecialchars($role['description']) ?>
                        </p>
                    </div>

                    <div>
                        <!-- Stat Row: Employment Type | Duration | Pay -->
                        <div class="opportunity-stats-row">
                            <!-- Type -->
                            <div class="opportunity-stat-item">
                                <svg class="opportunity-stat-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                <span><?= htmlspecialchars($role['type']) ?></span>
                            </div>

                            <div class="opportunity-stat-divider"></div>

                            <!-- Duration -->
                            <div class="opportunity-stat-item">
                                <svg class="opportunity-stat-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span><?= htmlspecialchars($role['duration']) ?></span>
                            </div>

                            <div class="opportunity-stat-divider"></div>

                            <!-- Stipend/Pay -->
                            <div class="opportunity-stat-item stat-stipend">
                                <svg class="opportunity-stat-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                <span><?= htmlspecialchars($role['stipend']) ?></span>
                            </div>
                        </div>

                        <!-- Full-Width Apply Button -->
                        <a href="<?= url('/login') ?>" class="opportunity-card-btn">
                            <span>Apply Now</span>
                            <svg class="opportunity-btn-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Footer: View All Opportunities -->
        <div class="opportunities-footer-wrap">
            <a href="<?= url('/internships') ?>" class="opportunities-view-all-link">
                <span>View All Opportunities</span>
                <svg class="opportunities-view-all-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                </svg>
            </a>
        </div>

    </div>
</section>

<script>
/**
 * Interactive Filter and Search Controller for Opportunities
 */
function opportunitiesFilterComponent() {
    return {
        searchQuery: '',
        activeFilters: ['UI Designer', 'Remote', 'Full-Time'],

        removeFilter(tag) {
            this.activeFilters = this.activeFilters.filter(t => t !== tag);
        },

        addFilter(tag) {
            if (!this.activeFilters.includes(tag)) {
                this.activeFilters.push(tag);
            }
        },

        toggleFilterDropdown() {
            window.location.href = '<?= url('/internships') ?>';
        },

        matchesSearch(role) {
            if (!this.searchQuery) return true;
            const q = this.searchQuery.toLowerCase();
            return role.title.toLowerCase().includes(q) ||
                   role.company.toLowerCase().includes(q) ||
                   role.location.toLowerCase().includes(q) ||
                   role.description.toLowerCase().includes(q) ||
                   role.category.toLowerCase().includes(q);
        }
    };
}
</script>
