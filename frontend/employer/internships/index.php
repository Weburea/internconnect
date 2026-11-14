<?php
declare(strict_types=1);
/**
 * View: My Internships (Employer Portal)
 * Route: /employer/internships
 * Layout: dashboard (Employer Portal)
 * 
 * Inspired by Candidate Applications (/candidate/applications)
 * Seamless AI Insights Banner with Zero Split / Halving
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
                 x-data="myInternshipsManager(<?= htmlspecialchars(json_encode($internships), ENT_QUOTES, 'UTF-8') ?>, '<?= htmlspecialchars($currentFilter) ?>')">

                <!-- ── 1. Top Page Header (Title, Subtitle & Launch Button) ── -->
                <div class="emp-myint-header" style="margin-bottom: 28px;">
                    <div>
                        <h1 class="emp-myint-title">Posted Internships</h1>
                        <p class="emp-myint-subtitle">Manage and track your posted internships</p>
                    </div>

                    <a href="<?= url('/employer/internships/create') ?>" class="emp-btn-launch">
                        <svg style="width: 16px; height: 16px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                        <span>Launch New Opening</span>
                    </a>
                </div>

                <!-- ── 2. AI Internship Insights Banner (Solely on its own, Seamless Canvas Blend) ── -->
                <div class="emp-myint-banner" 
                     style="background-image: linear-gradient(90deg, #4F47DA 0%, #4F47DA 38%, rgba(79, 71, 218, 0.8) 58%, transparent 80%), url('<?= asset('assets/images/banner_my_internship.jpg') ?>');">
                    <div class="emp-banner-inner">
                        <div class="emp-banner-tag">
                            <div class="emp-banner-ai-badge">
                                <img src="<?= asset('assets/images/jenny_ai/jenny_ai_hi.png') ?>" alt="Jenny AI" class="emp-banner-ai-img">
                            </div>
                            <span>AI Internship Insights</span>
                        </div>

                        <h2 class="emp-banner-heading">UI Design Intern role is receiving high-quality applicants</h2>
                        <p class="emp-banner-subtext">Our algorithm suggests reviewing the latest 5 candidates who match 95% of your skill requirements.</p>

                        <a href="<?= url('/employer/candidates?internship_id=2&sort=match_score') ?>" class="emp-banner-btn" style="display: inline-flex !important; align-items: center !important; gap: 8px !important; white-space: nowrap !important; flex-shrink: 0 !important;">
                            <span>View Top Matches</span>
                            <svg style="width: 14px; height: 14px; flex-shrink: 0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- ── 3. All Internships Section Header Bar (Candidate Applications Inspiration) ── -->
                <div class="emp-list-header-bar">
                    <h2 class="emp-list-heading">All Internships</h2>
                    
                    <div class="emp-list-controls">
                        <!-- Search Input -->
                        <div class="emp-search-input-wrap">
                            <svg class="emp-search-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <input type="text" 
                                   x-model="searchQuery" 
                                   placeholder="Search internships, departments..." 
                                   class="emp-search-input" 
                                   aria-label="Search internships">
                        </div>

                        <!-- Filter Button & Dropdown -->
                        <div class="relative" style="position: relative;" @click.outside="filterOpen = false">
                            <button type="button" class="emp-filter-btn" @click="filterOpen = !filterOpen" aria-label="Filter internships">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                                </svg>
                                <span>Filter</span>
                            </button>
                            
                            <div x-show="filterOpen" class="emp-filter-dropdown" style="display: none;">
                                <div class="emp-filter-dropdown-title">Filter by Status</div>
                                <button type="button" class="emp-filter-option" :class="{ 'is-active': activeFilter === 'all' }" @click="setTab('all'); filterOpen = false">All Internships</button>
                                <button type="button" class="emp-filter-option" :class="{ 'is-active': activeFilter === 'active' }" @click="setTab('active'); filterOpen = false">Active</button>
                                <button type="button" class="emp-filter-option" :class="{ 'is-active': activeFilter === 'closed' }" @click="setTab('closed'); filterOpen = false">Closed</button>
                                <button type="button" class="emp-filter-option" :class="{ 'is-active': activeFilter === 'draft' }" @click="setTab('draft'); filterOpen = false">Draft</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ── 4. Category Filter Tabs Bar ── -->
                <div class="emp-category-tabs-bar">
                    <button type="button" 
                            class="emp-category-tab" 
                            :class="{ 'is-active': activeFilter === 'all' }" 
                            @click="setTab('all')">
                        All (<span x-text="countAll()">12</span>)
                    </button>
                    <button type="button" 
                            class="emp-category-tab" 
                            :class="{ 'is-active': activeFilter === 'active' }" 
                            @click="setTab('active')">
                        Active (<span x-text="countActive()">8</span>)
                    </button>
                    <button type="button" 
                            class="emp-category-tab" 
                            :class="{ 'is-active': activeFilter === 'closed' }" 
                            @click="setTab('closed')">
                        Closed (<span x-text="countClosed()">3</span>)
                    </button>
                    <button type="button" 
                            class="emp-category-tab" 
                            :class="{ 'is-active': activeFilter === 'draft' }" 
                            @click="setTab('draft')">
                        Draft (<span x-text="countDraft()">1</span>)
                    </button>
                </div>

                <!-- ── 5. Internship Listings Stack ── -->
                <div class="emp-myint-list" style="margin-bottom: 24px;">
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
                                        <template x-if="item.status === 'CLOSED'">
                                            <span style="width: 6px; height: 6px; border-radius: 50%; background: #DC2626; display: inline-block;"></span>
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

                            <!-- Middle: Metrics (Fixed Grid Alignment) or Ready Prompt for Draft -->
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

                            <!-- Right: Actions by Status -->
                            <div class="emp-card-actions">
                                <!-- Active Card CTA Row: View Applicants + View Posted Internship + Options Dropdown -->
                                <template x-if="item.status === 'ACTIVE'">
                                    <div class="emp-action-buttons-group">
                                        <a :href="'<?= url('/employer/candidates') ?>?internship_id=' + item.id" class="emp-btn-view-apps">
                                            View Applicants
                                        </a>

                                        <!-- View Posted Internship (Preview) -->
                                        <a :href="'<?= url('/employer/internships/review') ?>?preview_id=' + item.id" class="emp-icon-action-btn" aria-label="View Posted Internship">
                                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>

                                        <!-- Options Menu Dropdown for Active Role -->
                                        <div class="emp-card-menu-wrap" style="position: relative;" @click.outside="activeMenuId = (activeMenuId === item.id ? null : activeMenuId)">
                                            <button type="button" class="emp-icon-action-btn" aria-label="More Actions" @click="activeMenuId = (activeMenuId === item.id ? null : item.id)">
                                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                                                </svg>
                                            </button>

                                            <div x-show="activeMenuId === item.id" class="emp-card-menu-dropdown" style="display: none;">
                                                <a :href="'<?= url('/employer/internships/create') ?>?edit_id=' + item.id" class="emp-menu-item">
                                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                    <span>Edit Post</span>
                                                </a>
                                                <button type="button" class="emp-menu-item" @click="moveToDraft(item); activeMenuId = null;">
                                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                    </svg>
                                                    <span>Move to Draft</span>
                                                </button>
                                                <button type="button" class="emp-menu-item" @click="closeRole(item); activeMenuId = null;">
                                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    <span>Close Role</span>
                                                </button>
                                                <div class="emp-menu-divider"></div>
                                                <button type="button" class="emp-menu-item is-danger" @click="promptDelete(item); activeMenuId = null;">
                                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                    <span>Delete Listing</span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </template>

                                <!-- Closed Card: Archive Role + View Preview + Reopen + Delete -->
                                <template x-if="item.status === 'CLOSED'">
                                    <div class="emp-action-buttons-group">
                                        <button type="button" class="emp-btn-archive" @click="archiveRole(item)">
                                            Archive Role
                                        </button>

                                        <!-- View Posted Internship -->
                                        <a :href="'<?= url('/employer/internships/review') ?>?preview_id=' + item.id" class="emp-icon-action-btn" aria-label="View Posted Internship">
                                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>

                                        <!-- Reopen Button -->
                                        <button type="button" class="emp-icon-action-btn" aria-label="Reopen Internship" @click="reopenRole(item)">
                                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                            </svg>
                                        </button>

                                        <!-- Delete Button -->
                                        <button type="button" class="emp-icon-action-btn is-danger" aria-label="Delete Listing" @click="promptDelete(item)">
                                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </div>
                                </template>

                                <!-- Draft Card: Finish & Post + Edit + Delete -->
                                <template x-if="item.is_draft">
                                    <div class="emp-action-buttons-group">
                                        <a :href="'<?= url('/employer/internships/create') ?>?draft_id=' + item.id" class="emp-btn-finish-post">
                                            Finish & Post
                                        </a>

                                        <a :href="'<?= url('/employer/internships/create') ?>?draft_id=' + item.id" class="emp-icon-action-btn" aria-label="Edit Draft">
                                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>

                                        <button type="button" class="emp-icon-action-btn is-danger" aria-label="Delete Draft" @click="promptDelete(item)">
                                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </div>
                                </template>
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

                <!-- ── 6. Pagination ── -->
                <div class="emp-pagination-wrap">
                    <div>
                        Showing <span x-text="filteredInternships().length">4</span> of <span x-text="countAll()">12</span> internships
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

                <!-- ── 7. Custom Alpine Delete Confirmation Modal (NO NATIVE POPUPS) ── -->
                <div x-show="deleteModalOpen" 
                     x-cloak
                     style="display: none; position: fixed; inset: 0; background: rgba(0, 6, 35, 0.6); backdrop-filter: blur(4px); z-index: 9999; align-items: center; justify-content: center; padding: 20px;"
                     :style="deleteModalOpen ? 'display: flex;' : 'display: none;'"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0">
                    
                    <div class="emp-modal-card" 
                         @click.outside="deleteModalOpen = false"
                         style="background: #FFFFFF; border-radius: 16px; border: 1px solid #E2E8F0; width: 100%; max-width: 440px; padding: 24px; box-shadow: 0 20px 40px rgba(0, 6, 35, 0.15);">
                        
                        <div style="width: 44px; height: 44px; border-radius: 12px; background: #FEE2E2; color: #DC2626; display: flex; align-items: center; justify-content: center; margin-bottom: 16px;">
                            <svg style="width: 22px; height: 22px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </div>

                        <h3 style="font-size: 17px; font-weight: 800; color: #000623; margin: 0 0 8px 0;">Delete Internship Listing</h3>
                        <p style="font-size: 13.5px; color: #64748B; margin: 0 0 24px 0; line-height: 1.5;">Are you sure you want to delete <strong style="color: #000623;" x-text="internshipToDelete ? internshipToDelete.title : 'this listing'"></strong>? This action will remove the listing and cannot be undone.</p>

                        <div style="display: flex; align-items: center; justify-content: flex-end; gap: 10px;">
                            <button type="button" @click="deleteModalOpen = false" class="emp-btn-cancel-modal">Cancel</button>
                            <button type="button" @click="confirmDelete()" class="emp-btn-confirm-delete">Delete Listing</button>
                        </div>
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
        filterOpen: false,
        activeMenuId: null,
        deleteModalOpen: false,
        internshipToDelete: null,

        triggerToast(msg, type = 'success') {
            window.dispatchEvent(new CustomEvent('show-toast', { 
                detail: { text: msg, type: type } 
            }));
        },

        setTab(tab) {
            this.activeFilter = tab;
        },

        countAll() {
            return this.internships.length;
        },

        countActive() {
            return this.internships.filter(i => i.status === 'ACTIVE').length;
        },

        countClosed() {
            return this.internships.filter(i => i.status === 'CLOSED').length;
        },

        countDraft() {
            return this.internships.filter(i => i.is_draft || i.status === 'DRAFT').length;
        },

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

        moveToDraft(item) {
            item.status = 'DRAFT';
            item.is_draft = true;
            this.triggerToast(`"${item.title}" moved to Draft.`);
        },

        closeRole(item) {
            item.status = 'CLOSED';
            item.is_draft = false;
            this.triggerToast(`"${item.title}" is now Closed.`);
        },

        reopenRole(item) {
            item.status = 'ACTIVE';
            item.is_draft = false;
            this.triggerToast(`"${item.title}" reopened as Active.`);
        },

        archiveRole(item) {
            this.triggerToast(`"${item.title}" archived successfully.`);
        },

        promptDelete(item) {
            this.internshipToDelete = item;
            this.deleteModalOpen = true;
        },

        confirmDelete() {
            if (this.internshipToDelete) {
                const title = this.internshipToDelete.title;
                this.internships = this.internships.filter(i => i.id !== this.internshipToDelete.id);
                this.deleteModalOpen = false;
                this.internshipToDelete = null;
                this.triggerToast(`"${title}" deleted successfully.`);
            }
        }
    }));
});
</script>
