<?php
declare(strict_types=1);
/**
 * View: My Internships (Employer Portal)
 * Route: /employer/internships
 * Layout: dashboard (Employer Portal)
 * 
 * @var array  $employer      Employer profile data
 * @var array  $internships   List of internships (Active, Closed, Draft)
 * @var string $currentFilter Filter identifier ('all', 'active', 'closed', 'draft')
 * @var string $activeNav     Active navigation identifier ('internships')
 */
?>

<link rel="stylesheet" href="<?= asset('assets/css/employer-post-internship.css') ?>">

<div class="db-shell">

    <!-- ══ EMPLOYER SIDEBAR ══ -->
    <?php 
    $activeNav = 'internships';
    include dirname(__DIR__, 2) . '/shared/components/employer-sidebar.php'; 
    ?>

    <!-- ══ MAIN CONTENT WRAPPER ══ -->
    <div class="db-main">

        <!-- ══ TOP NAVBAR ══ -->
        <?php include dirname(__DIR__, 2) . '/shared/components/employer-topnav.php'; ?>

        <!-- ══ PAGE CONTENT ══ -->
        <div class="db-content">
            <div class="emp-post-container" 
                 x-data="myInternshipsManager(<?= htmlspecialchars(json_encode($internships)) ?>, '<?= htmlspecialchars($currentFilter) ?>')">

                <!-- ── 1. Top Header (Title, Subtitle & Launch Button) ── -->
                <div class="emp-myint-header">
                    <div>
                        <h1 class="emp-myint-title">My Internships</h1>
                        <p class="emp-myint-subtitle">Manage and track your posted internships</p>
                    </div>

                    <a href="<?= url('/employer/internships/create') ?>" class="emp-btn-launch">
                        <svg style="width: 16px; height: 16px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                        <span>Launch New Opening</span>
                    </a>
                </div>

                <!-- ── 2. Top Filter Bar & Search ── -->
                <div class="emp-filter-bar">
                    <div class="emp-filter-tabs">
                        <button type="button" 
                                class="emp-filter-tab" 
                                :class="{ 'is-active': activeFilter === 'all' }" 
                                @click="activeFilter = 'all'">
                            All
                        </button>
                        <button type="button" 
                                class="emp-filter-tab" 
                                :class="{ 'is-active': activeFilter === 'active' }" 
                                @click="activeFilter = 'active'">
                            Active
                        </button>
                        <button type="button" 
                                class="emp-filter-tab" 
                                :class="{ 'is-active': activeFilter === 'closed' }" 
                                @click="activeFilter = 'closed'">
                            Closed
                        </button>
                        <button type="button" 
                                class="emp-filter-tab" 
                                :class="{ 'is-active': activeFilter === 'draft' }" 
                                @click="activeFilter = 'draft'">
                            Draft
                        </button>
                    </div>

                    <div class="emp-myint-search-wrap">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <input type="text" 
                               x-model="searchQuery" 
                               class="emp-myint-search-input" 
                               placeholder="Search internships, departments...">
                    </div>
                </div>

                <!-- ── 3. AI Internship Insights Banner (Matches Screenshot 5 + Cloudinary Banner) ── -->
                <div class="emp-myint-banner" x-show="activeFilter === 'all' || activeFilter === 'active'" x-transition>
                    <div class="emp-banner-content-side">
                        <div class="emp-banner-tag">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                            <span>AI Internship Insights</span>
                        </div>

                        <h2 class="emp-banner-heading">UI Design Intern role is receiving high-quality applicants</h2>
                        <p class="emp-banner-subtext">Our algorithm suggests reviewing the latest 5 candidates who match 95% of your skill requirements.</p>

                        <a href="<?= url('/employer/dashboard') ?>" class="emp-banner-btn">
                            Apply Now
                        </a>
                    </div>

                    <div class="emp-banner-media-side">
                        <!-- Cloudinary Banner Asset (Waving Jenny AI Robot illustration with headphones) -->
                        <img src="<?= asset('assets/images/banner_my_internship.jpg') ?>" 
                             alt="AI Internship Insights" 
                             loading="lazy">
                    </div>
                </div>

                <!-- ── 4. Internship Listings ── -->
                <div class="emp-myint-list">
                    <template x-for="item in filteredInternships()" :key="item.id">
                        <div class="emp-internship-card" :class="{ 'is-draft': item.is_draft }">
                            
                            <!-- Left: Title, Badges & Meta -->
                            <div class="emp-card-left">
                                <div class="emp-card-title-row">
                                    <h3 class="emp-card-title" x-text="item.title"></h3>
                                    
                                    <span class="emp-status-badge" 
                                          :class="{
                                              'active': item.status === 'ACTIVE',
                                              'closed': item.status === 'CLOSED',
                                              'draft': item.status === 'DRAFT'
                                          }">
                                        <template x-if="item.status === 'ACTIVE'">
                                            <span style="width: 6px; height: 6px; border-radius: 50%; background: #059669; display: inline-block;"></span>
                                        </template>
                                        <span x-text="item.status"></span>
                                    </span>
                                </div>

                                <div class="emp-card-meta-row">
                                    <!-- Department -->
                                    <span class="emp-card-meta-item">
                                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                        </svg>
                                        <span x-text="item.department"></span>
                                    </span>

                                    <!-- Location Type -->
                                    <span class="emp-card-meta-item">
                                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span x-text="item.location_type"></span>
                                    </span>
                                </div>

                                <div class="emp-card-postdate" x-text="item.posted_at"></div>
                            </div>

                            <!-- Middle: Metrics (or Ready Prompt for Draft) -->
                            <template x-if="!item.is_draft && item.stats">
                                <div class="emp-card-stats">
                                    <div class="emp-stat-col">
                                        <div class="emp-stat-number" x-text="item.stats.applicants"></div>
                                        <div class="emp-stat-label">Applicants</div>
                                    </div>
                                    <div class="emp-stat-col">
                                        <div class="emp-stat-number" x-text="item.stats.shortlist"></div>
                                        <div class="emp-stat-label">Shortlist</div>
                                    </div>
                                    <div class="emp-stat-col">
                                        <div class="emp-stat-number" x-text="item.stats.interviews"></div>
                                        <div class="emp-stat-label">Interviews</div>
                                    </div>
                                    <div class="emp-stat-col">
                                        <div class="emp-stat-number" x-text="item.stats.hired"></div>
                                        <div class="emp-stat-label">Hired</div>
                                    </div>
                                </div>
                            </template>

                            <template x-if="item.is_draft">
                                <div class="emp-draft-ready-prompt">
                                    Ready to post?
                                </div>
                            </template>

                            <!-- Right: Actions -->
                            <div class="emp-card-actions">
                                <!-- Active Card CTA -->
                                <template x-if="item.status === 'ACTIVE'">
                                    <a href="<?= url('/employer/dashboard') ?>" class="emp-btn-view-apps">
                                        View Applications
                                    </a>
                                </template>

                                <!-- Closed Card CTA -->
                                <template x-if="item.status === 'CLOSED'">
                                    <button type="button" class="emp-btn-archive">
                                        Archive Role
                                    </button>
                                </template>

                                <!-- Draft Card CTA -->
                                <template x-if="item.is_draft">
                                    <a href="<?= url('/employer/internships/create') ?>" class="emp-btn-finish-post">
                                        Finish & Post
                                    </a>
                                </template>

                                <!-- Closed Reopen Button -->
                                <template x-if="item.status === 'CLOSED'">
                                    <button type="button" class="emp-icon-action-btn" title="Reopen Internship">
                                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                        </svg>
                                    </button>
                                </template>

                                <!-- Edit Button -->
                                <template x-if="item.status !== 'CLOSED'">
                                    <a href="<?= url('/employer/internships/create') ?>" class="emp-icon-action-btn" title="Edit Internship">
                                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>
                                </template>

                                <!-- Delete Button -->
                                <button type="button" class="emp-icon-action-btn" title="Delete Internship" @click="deleteInternship(item.id)">
                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>

                        </div>
                    </template>

                    <!-- Empty State when filtering returns 0 items -->
                    <div x-show="filteredInternships().length === 0" 
                         style="background: #FFFFFF; border: 1px dashed #CBD5E1; border-radius: 16px; padding: 48px 24px; text-align: center;">
                        <div style="width: 48px; height: 48px; border-radius: 12px; background: #EEF2FF; color: #4F48E6; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px;">
                            <svg style="width: 24px; height: 24px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h4 style="font-size: 16px; font-weight: 700; color: #000623; margin: 0 0 6px 0;">No internships found</h4>
                        <p style="font-size: 13.5px; color: #64748B; margin: 0 0 20px 0;">No listings match your search or selected filter.</p>
                        <button type="button" class="emp-btn-secondary" @click="activeFilter = 'all'; searchQuery = ''">Clear Filters</button>
                    </div>
                </div>

                <!-- ── 5. Pagination (Matches Screenshot 5) ── -->
                <div class="emp-pagination-wrap">
                    <div>
                        Showing <span x-text="filteredInternships().length">3</span> of 12 internships
                    </div>

                    <div class="emp-pagination-controls">
                        <button type="button" class="emp-page-btn" aria-label="Previous Page">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                            </svg>
                        </button>
                        <button type="button" class="emp-page-btn is-active">1</button>
                        <button type="button" class="emp-page-btn">2</button>
                        <button type="button" class="emp-page-btn">3</button>
                        <button type="button" class="emp-page-btn" aria-label="Next Page">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                            </svg>
                        </button>
                    </div>
                </div>

            </div>
        </div>

    </div>

</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('myInternshipsManager', (initialList, defaultFilter) => ({
        internships: initialList || [],
        activeFilter: defaultFilter || 'all',
        searchQuery: '',

        filteredInternships() {
            return this.internships.filter(item => {
                const matchesFilter = (this.activeFilter === 'all') || 
                                      (item.status.toLowerCase() === this.activeFilter.toLowerCase());
                
                const q = this.searchQuery.trim().toLowerCase();
                const matchesSearch = !q || 
                                      item.title.toLowerCase().includes(q) || 
                                      item.department.toLowerCase().includes(q) ||
                                      item.location_type.toLowerCase().includes(q);

                return matchesFilter && matchesSearch;
            });
        },

        deleteInternship(id) {
            if (confirm('Are you sure you want to remove this internship listing?')) {
                this.internships = this.internships.filter(i => i.id !== id);
            }
        }
    }));
});
</script>
