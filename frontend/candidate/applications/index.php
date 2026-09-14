<?php
declare(strict_types=1);
/**
 * View: Candidate Applications Tracking
 * Route: /candidate, /candidate/applications, /candidate/application, /applications
 * Layout: dashboard (custom candidate sidebar layout)
 * 
 * @var array  $candidate      Candidate profile information
 * @var array  $metrics        Top level KPI metric numbers
 * @var array  $tabs           Category filter tabs
 * @var array  $urgentBanners  Active urgent action banners
 * @var array  $applications   List of current applications
 */

$name      = $candidate['name'] ?? 'Sarah Peters';
$firstName = explode(' ', trim($name))[0];
$role      = $candidate['role'] ?? 'Student Applicant';
$avatar    = $candidate['avatar'] ?? null;
?>

<div class="db-shell" x-data="{
    activeTab: 'all',
    searchQuery: '',
    filterOpen: false,
    deleteModal: false,
    selectedAppId: null,
    selectedAppName: '',
    removedIds: [],
    currentPage: 1,
    perPage: 6,
    apps: <?= htmlspecialchars(json_encode($applications), ENT_QUOTES, 'UTF-8') ?>,

    filteredApps() {
        const q = this.searchQuery.trim().toLowerCase();
        return this.apps.filter(app => {
            if (this.removedIds.includes(app.id)) return false;
            const matchesSearch = q === '' || 
                (app.company && app.company.toLowerCase().includes(q)) || 
                (app.role && app.role.toLowerCase().includes(q));
            const matchesTab = this.activeTab === 'all' || 
                (app.status_type && app.status_type.toLowerCase() === this.activeTab.toLowerCase());
            return matchesSearch && matchesTab;
        });
    },

    paginatedApps() {
        const filtered = this.filteredApps();
        const start = (this.currentPage - 1) * this.perPage;
        return filtered.slice(start, start + this.perPage);
    },

    totalPages() {
        return Math.max(1, Math.ceil(this.filteredApps().length / this.perPage));
    },

    setTab(tabId) {
        this.activeTab = tabId;
        this.currentPage = 1;
    },

    setPage(p) {
        this.currentPage = p;
        const container = document.querySelector('.app-cards-container');
        if (container) {
            container.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }
    },

    prevPage() {
        if (this.currentPage > 1) {
            this.setPage(this.currentPage - 1);
        }
    },

    nextPage() {
        if (this.currentPage < this.totalPages()) {
            this.setPage(this.currentPage + 1);
        }
    },

    confirmRemove(id, name) {
        this.selectedAppId = id;
        this.selectedAppName = name;
        this.deleteModal = true;
    },

    executeRemove() {
        if (this.selectedAppId !== null) {
            this.removedIds.push(this.selectedAppId);
            this.deleteModal = false;
            this.selectedAppId = null;
            if (this.currentPage > this.totalPages()) {
                this.currentPage = Math.max(1, this.totalPages());
            }
            if (typeof window.$dispatch === 'function') {
                window.$dispatch('show-toast', { text: 'Application removed successfully', type: 'info' });
            }
        }
    }
}">

    <!-- ══ SIDEBAR ══ -->
    <?php 
    $activeNav = 'applications';
    include dirname(__DIR__, 2) . '/shared/components/candidate-sidebar.php'; 
    ?>

    <!-- ══ MAIN ══ -->
    <div class="db-main">

        <!-- ══ TOP NAVBAR ══ -->
        <?php include dirname(__DIR__, 2) . '/shared/components/candidate-topnav.php'; ?>

        <!-- ══ CONTENT ══ -->
        <div class="db-content">

            <!-- Page Title Header Block -->
            <div class="app-journey-header" style="margin-bottom: 24px;">
                <h1 class="app-journey-title">Your Internship Journey</h1>
                <p class="app-journey-subtitle">Stay organized and ahead of the curve, <?= htmlspecialchars($firstName) ?>. 12 active opportunities found.</p>
            </div>

            <!-- 5 KPI Metrics Row (Latest Visual Design) -->
            <section class="app-metrics-grid" aria-label="Application summary metrics" style="margin-bottom: 24px;">
                <?php foreach ($metrics as $metric): ?>
                    <div class="app-metric-card">
                        <div class="app-metric-card-info">
                            <span class="app-metric-title"><?= htmlspecialchars($metric['title']) ?></span>
                            <span class="app-metric-num"><?= (int)$metric['value'] ?></span>
                            <span class="app-metric-sub"><?= htmlspecialchars($metric['subtitle']) ?></span>
                        </div>
                        <div class="app-metric-icon-box" style="background-color: <?= htmlspecialchars($metric['bg_color']) ?>; color: <?= htmlspecialchars($metric['color']) ?>;">
                            <?php if ($metric['icon'] === 'document'): ?>
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            <?php elseif ($metric['icon'] === 'clock'): ?>
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            <?php elseif ($metric['icon'] === 'calendar'): ?>
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            <?php elseif ($metric['icon'] === 'briefcase'): ?>
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            <?php elseif ($metric['icon'] === 'x-circle'): ?>
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </section>

            <!-- 2 Urgent Action Banners -->
            <section class="app-banners-stack" aria-label="Urgent notifications" style="margin-bottom: 24px;">
                <!-- 1. Green Offer Banner -->
                <?php if (!empty($urgentBanners['offer'])): $offer = $urgentBanners['offer']; ?>
                    <div class="app-action-banner app-banner-offer">
                        <div class="app-banner-left">
                            <div class="app-banner-icon-circle bg-emerald">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                                </svg>
                            </div>
                            <div class="app-banner-text">
                                <h4 class="app-banner-title text-emerald-dark"><?= htmlspecialchars($offer['title']) ?></h4>
                                <p class="app-banner-desc text-emerald-sub"><?= htmlspecialchars($offer['subtitle']) ?></p>
                            </div>
                        </div>
                        <div class="app-banner-right">
                            <span class="app-banner-deadline"><?= htmlspecialchars($offer['deadline']) ?></span>
                            <a href="<?= htmlspecialchars($offer['action_url']) ?>" class="app-banner-btn-offer"><?= htmlspecialchars($offer['action_text']) ?></a>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- 2. Lavender Interview Banner -->
                <?php if (!empty($urgentBanners['interview'])): $interview = $urgentBanners['interview']; ?>
                    <div class="app-action-banner app-banner-interview">
                        <div class="app-banner-left">
                            <div class="app-banner-icon-circle bg-indigo">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div class="app-banner-text">
                                <h4 class="app-banner-title text-indigo-dark"><?= htmlspecialchars($interview['title']) ?></h4>
                            </div>
                        </div>
                        <div class="app-banner-right">
                            <span class="app-banner-datetime text-indigo-sub"><?= htmlspecialchars($interview['datetime']) ?></span>
                            <a href="<?= htmlspecialchars($interview['action_url']) ?>" class="app-banner-btn-interview"><?= htmlspecialchars($interview['action_text']) ?></a>
                        </div>
                    </div>
                <?php endif; ?>
            </section>

            <!-- All Applications Section Header Bar with Search & Filter -->
            <div class="app-list-header-bar" style="margin-bottom: 16px;">
                <h2 class="app-list-heading">All Applications</h2>
                
                <div class="app-list-controls">
                    <!-- Search Input -->
                    <div class="app-search-input-wrap">
                        <svg class="app-search-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <input type="text" x-model="searchQuery" @input="currentPage = 1" placeholder="Search companies..." class="app-search-input" aria-label="Search companies">
                    </div>

                    <!-- Filter Button & Dropdown -->
                    <div class="relative" @click.outside="filterOpen = false">
                        <button type="button" class="app-filter-btn" @click="filterOpen = !filterOpen" aria-label="Filter applications">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                            </svg>
                            <span>Filter</span>
                        </button>
                        
                        <div x-show="filterOpen" class="app-filter-dropdown" style="display: none;">
                            <div class="app-filter-dropdown-title">Filter by Status</div>
                            <button type="button" class="app-filter-option" :class="{ 'is-active': activeTab === 'all' }" @click="setTab('all'); filterOpen = false">All Applications</button>
                            <button type="button" class="app-filter-option" :class="{ 'is-active': activeTab === 'applied' }" @click="setTab('applied'); filterOpen = false">Applied</button>
                            <button type="button" class="app-filter-option" :class="{ 'is-active': activeTab === 'in_review' }" @click="setTab('in_review'); filterOpen = false">In Review</button>
                            <button type="button" class="app-filter-option" :class="{ 'is-active': activeTab === 'interview' }" @click="setTab('interview'); filterOpen = false">Interview Stage</button>
                            <button type="button" class="app-filter-option" :class="{ 'is-active': activeTab === 'offer' }" @click="setTab('offer'); filterOpen = false">Offer Received</button>
                            <button type="button" class="app-filter-option" :class="{ 'is-active': activeTab === 'rejected' }" @click="setTab('rejected'); filterOpen = false">Not Selected</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Category Filter Tabs Navigation -->
            <div class="app-category-tabs-bar" style="margin-bottom: 20px;">
                <?php foreach ($tabs as $tab): ?>
                    <button type="button" 
                            class="app-category-tab" 
                            :class="{ 'is-active': activeTab === '<?= htmlspecialchars($tab['id']) ?>' }"
                            @click="setTab('<?= htmlspecialchars($tab['id']) ?>')">
                        <?= htmlspecialchars($tab['label']) ?> (<?= (int)$tab['count'] ?>)
                    </button>
                <?php endforeach; ?>
            </div>

            <!-- Application Cards Stack -->
            <div class="app-cards-container">
                <template x-for="app in paginatedApps()" :key="app.id">
                    <div class="app-card-item-v2">
                        
                        <!-- Col 1: Avatar -->
                        <div class="app-card-initial" :style="'background-color: ' + app.letter_bg + '; color: ' + app.letter_color + ';'" x-text="app.letter">
                        </div>

                        <!-- Col 2: Company & Role -->
                        <div class="app-card-company-col">
                            <div class="app-company-title-row">
                                <span class="app-company-name" x-text="app.company"></span>
                                <template x-if="app.verified">
                                    <span class="app-verified-badge" title="Verified Employer">
                                        <svg fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                    </span>
                                </template>
                            </div>
                            <div class="app-role-subtitle" x-text="app.role"></div>
                            <div class="app-tags-row">
                                <template x-for="tag in app.tags" :key="tag">
                                    <span class="app-tag-pill" x-text="tag"></span>
                                </template>
                            </div>
                        </div>

                        <!-- Col 3: Status -->
                        <div class="app-card-status-col">
                            <span class="app-col-header">STATUS</span>
                            <span class="app-status-text" :class="'status-' + app.status_type" x-text="app.status"></span>
                        </div>

                        <!-- Col 4: Current Stage -->
                        <div class="app-card-stage-col">
                            <span class="app-col-header">CURRENT STAGE</span>
                            <span class="app-stage-text" :class="'stage-' + app.stage_type" x-text="app.current_stage"></span>
                        </div>

                        <!-- Col 5: Next Step Box -->
                        <div class="app-card-action-col">
                            <template x-if="app.next_type === 'blue'">
                                <div class="app-action-box-info">
                                    <span class="app-box-label">NEXT STEP</span>
                                    <span class="app-box-val" x-text="app.next_step"></span>
                                </div>
                            </template>
                            <template x-if="app.next_type === 'purple'">
                                <div class="app-action-box-purple-v2">
                                    <span class="app-box-label-purple">NEXT STEP</span>
                                    <span class="app-box-val-purple" x-text="app.next_step"></span>
                                    <template x-if="app.next_step_sub">
                                        <span class="app-box-sub-purple" x-text="app.next_step_sub"></span>
                                    </template>
                                </div>
                            </template>
                            <template x-if="app.next_type === 'green'">
                                <div class="app-action-box-green">
                                    <span class="app-box-label-green">NEXT STEP</span>
                                    <span class="app-box-val-green" x-text="app.next_step"></span>
                                </div>
                            </template>
                            <template x-if="app.next_type === 'gray'">
                                <div class="app-action-box-gray">
                                    <span class="app-box-label-gray">NEXT STEP</span>
                                    <span class="app-box-val-gray" x-text="app.next_step"></span>
                                </div>
                            </template>
                            <template x-if="app.next_type === 'amber'">
                                <div class="app-action-box-amber">
                                    <span class="app-box-label-amber">NEXT STEP</span>
                                    <span class="app-box-val-amber" x-text="app.next_step"></span>
                                </div>
                            </template>
                        </div>

                        <!-- Col 6: Applied On -->
                        <div class="app-card-date-col">
                            <span class="app-col-header">APPLIED ON</span>
                            <span class="app-applied-date" x-text="app.date"></span>
                        </div>

                        <!-- Col 7: 3-dots Menu Action -->
                        <div class="app-card-menu-col" x-data="{ openMenu: false }">
                            <button type="button" class="app-card-dots-btn" @click="openMenu = !openMenu" aria-label="Application Options">
                                <svg fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"/>
                                </svg>
                            </button>
                            <div x-show="openMenu" @click.outside="openMenu = false" class="app-card-dropdown" style="display: none;">
                                <a :href="app.listing_url || '<?= url('/internships') ?>'" class="app-dropdown-link">
                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    <span>View Listing</span>
                                </a>
                                <button type="button" class="app-dropdown-link text-danger" @click="confirmRemove(app.id, app.company); openMenu = false">
                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                    <span>Withdraw Application</span>
                                </button>
                            </div>
                        </div>

                    </div>
                </template>
            </div>

            <!-- Empty State -->
            <div x-show="filteredApps().length === 0" class="app-empty-state" style="display: none;">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <h3>No applications found</h3>
                <p>Try adjusting your search query or status filter.</p>
            </div>

            <!-- ══ REUSABLE PAGINATION BAR (Image 1 Specs) ══ -->
            <div class="app-pagination-bar" x-show="filteredApps().length > 0" role="navigation" aria-label="Applications pagination">
                <!-- Left: Showing Info -->
                <div class="app-pagination-info">
                    Showing <span x-text="Math.min(perPage, filteredApps().length)"></span> of <span x-text="filteredApps().length"></span> applications
                </div>

                <!-- Right: Pagination Controls -->
                <div class="app-pagination-controls" x-show="totalPages() > 1">
                    <!-- Prev Button -->
                    <button type="button" 
                            class="app-pagination-btn app-pagination-btn-prev" 
                            :disabled="currentPage <= 1" 
                            @click="prevPage()"
                            aria-label="Previous page">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </button>

                    <!-- Page Numbers -->
                    <template x-for="p in totalPages()" :key="p">
                        <button type="button" 
                                class="app-pagination-btn" 
                                :class="{ 'is-active': currentPage === p }" 
                                @click="setPage(p)"
                                x-text="p"
                                :aria-current="currentPage === p ? 'page' : false">
                        </button>
                    </template>

                    <!-- Next Button -->
                    <button type="button" 
                            class="app-pagination-btn app-pagination-btn-next" 
                            :disabled="currentPage >= totalPages()" 
                            @click="nextPage()"
                            aria-label="Next page">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>
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

    <!-- Confirmation Modal for Cancel/Delete Application -->
    <div x-show="deleteModal" class="app-modal-backdrop" style="display: none;" @keydown.escape.window="deleteModal = false">
        <div class="app-modal-dialog" @click.outside="deleteModal = false">
            <div class="app-modal-icon-wrap">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <h3 class="app-modal-title">Withdraw / Remove Application</h3>
            <p class="app-modal-body">
                Are you sure you want to withdraw or remove your application for <strong x-text="selectedAppName"></strong>? This action cannot be undone.
            </p>
            <div class="app-modal-actions">
                <button type="button" class="app-modal-btn-cancel" @click="deleteModal = false">Keep Application</button>
                <button type="button" class="app-modal-btn-confirm" @click="executeRemove()">Yes, Remove</button>
            </div>
        </div>
    </div>

</div>

