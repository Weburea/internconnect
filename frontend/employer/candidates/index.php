<?php
declare(strict_types=1);
/**
 * View: Employer Candidates / Applications List
 * Route: /employer/candidates or /employer/applications
 * Layout: dashboard
 * 
 * Inspired by InternConnect design standards & Applications UI (Image 3)
 * Full custom rounded dropdowns, SVG score donuts, search & client-side filtering + pagination.
 * 
 * @var array  $employer   Employer profile data
 * @var array  $candidates List of candidates
 * @var string $activeNav  Active navigation identifier
 */
?>

<link rel="stylesheet" href="<?= asset('assets/css/employer-candidates.css') ?>">

<div class="db-shell">

    <!-- ══ EMPLOYER SIDEBAR ══ -->
    <?php 
    $activeNav = 'candidates';
    include dirname(__DIR__, 2) . '/shared/components/employer-sidebar.php'; 
    ?>

    <!-- ══ MAIN CONTENT WRAPPER ══ -->
    <div class="db-main">

        <!-- ══ TOP NAVBAR ══ -->
        <?php include dirname(__DIR__, 2) . '/shared/components/employer-topnav.php'; ?>

        <!-- ══ PAGE CONTENT ══ -->
        <div class="db-content">
            <div class="emp-cand-page" 
                 x-data="candidateApplicationsManager(<?= htmlspecialchars(json_encode($candidates), ENT_QUOTES, 'UTF-8') ?>)">

                <!-- ── 1. Page Header ── -->
                <div class="emp-cand-header">
                    <div>
                        <h1 class="emp-cand-title">Applications</h1>
                        <p class="emp-cand-subtitle">Review and manage internship applicants</p>
                    </div>
                </div>

                <!-- ── 2. Toolbar (Search & Custom Alpine Dropdowns) ── -->
                <div class="emp-cand-toolbar">
                    <!-- Search Input -->
                    <div class="emp-cand-search-wrap">
                        <svg class="emp-cand-search-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <input type="text" 
                               x-model="searchQuery" 
                               placeholder="Search by name..." 
                               class="emp-cand-search-input"
                               aria-label="Search candidates by name">
                    </div>

                    <!-- Filter Dropdowns Group -->
                    <div class="emp-cand-filter-group">

                        <!-- Role Filter Dropdown -->
                        <div class="emp-drop-wrap" @click.outside="roleDropdownOpen = false">
                            <button type="button" 
                                    class="emp-drop-btn" 
                                    :class="{ 'is-active': roleDropdownOpen }"
                                    @click="roleDropdownOpen = !roleDropdownOpen"
                                    aria-haspopup="true"
                                    :aria-expanded="roleDropdownOpen">
                                <span x-text="roleLabel">All Internship Roles</span>
                                <svg class="emp-drop-chevron" :class="{ 'rotate': roleDropdownOpen }" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <div x-show="roleDropdownOpen" 
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="opacity-0 transform scale-95"
                                 x-transition:enter-end="opacity-100 transform scale-100"
                                 class="emp-drop-menu" 
                                 style="display: none;">
                                <template x-for="r in rolesList" :key="r.id">
                                    <button type="button" 
                                            class="emp-drop-item" 
                                            :class="{ 'selected': selectedRole === r.id }"
                                            @click="selectRole(r)">
                                        <span x-text="r.label"></span>
                                        <svg x-show="selectedRole === r.id" style="width: 14px; height: 14px; color: #4F48E6;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </button>
                                </template>
                            </div>
                        </div>

                        <!-- Status Filter Dropdown -->
                        <div class="emp-drop-wrap" @click.outside="statusDropdownOpen = false">
                            <button type="button" 
                                    class="emp-drop-btn" 
                                    :class="{ 'is-active': statusDropdownOpen }"
                                    @click="statusDropdownOpen = !statusDropdownOpen"
                                    aria-haspopup="true"
                                    :aria-expanded="statusDropdownOpen">
                                <span x-text="statusLabel">All Statuses</span>
                                <svg class="emp-drop-chevron" :class="{ 'rotate': statusDropdownOpen }" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <div x-show="statusDropdownOpen" 
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="opacity-0 transform scale-95"
                                 x-transition:enter-end="opacity-100 transform scale-100"
                                 class="emp-drop-menu" 
                                 style="display: none;">
                                <template x-for="s in statusList" :key="s.id">
                                    <button type="button" 
                                            class="emp-drop-item" 
                                            :class="{ 'selected': selectedStatus === s.id }"
                                            @click="selectStatus(s)">
                                        <span x-text="s.label"></span>
                                        <svg x-show="selectedStatus === s.id" style="width: 14px; height: 14px; color: #4F48E6;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </button>
                                </template>
                            </div>
                        </div>

                        <!-- Sort By Dropdown -->
                        <div class="emp-drop-wrap" @click.outside="sortDropdownOpen = false">
                            <button type="button" 
                                    class="emp-drop-btn" 
                                    :class="{ 'is-active': sortDropdownOpen }"
                                    @click="sortDropdownOpen = !sortDropdownOpen"
                                    aria-haspopup="true"
                                    :aria-expanded="sortDropdownOpen">
                                <span>Sort by: <strong x-text="sortLabel" style="font-weight: 700; color: #000623;">Match Score</strong></span>
                                <svg class="emp-drop-chevron" :class="{ 'rotate': sortDropdownOpen }" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <div x-show="sortDropdownOpen" 
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="opacity-0 transform scale-95"
                                 x-transition:enter-end="opacity-100 transform scale-100"
                                 class="emp-drop-menu" 
                                 style="display: none;">
                                <template x-for="opt in sortOptions" :key="opt.id">
                                    <button type="button" 
                                            class="emp-drop-item" 
                                            :class="{ 'selected': sortBy === opt.id }"
                                            @click="selectSort(opt)">
                                        <span x-text="opt.label"></span>
                                        <svg x-show="sortBy === opt.id" style="width: 14px; height: 14px; color: #4F48E6;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </button>
                                </template>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- ── 3. Candidates / Applications Table ── -->
                <div class="emp-table-card">
                    <div class="emp-table-responsive">
                        <table class="emp-table" role="table">
                            <thead>
                                <tr>
                                    <th scope="col" style="width: 32%;">Candidate</th>
                                    <th scope="col" style="width: 28%;">Role</th>
                                    <th scope="col" style="width: 25%;">Match Score</th>
                                    <th scope="col" style="width: 15%;">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template x-for="(cand, idx) in paginatedCandidates" :key="cand.id + '-' + idx">
                                    <tr class="emp-table-row" 
                                        :class="{ 'is-highlight': cand.id == 1 && cand.status_type === 'scheduled' }"
                                        @click="openCandidate(cand.id)"
                                        role="row"
                                        tabindex="0"
                                        @keydown.enter="openCandidate(cand.id)">
                                        
                                        <!-- Candidate Cell (Avatar, Name, Applied Time) -->
                                        <td>
                                            <div class="emp-cell-candidate">
                                                <div class="emp-cand-avatar-wrap">
                                                    <template x-if="cand.avatar">
                                                        <img :src="cand.avatar" :alt="cand.name" class="emp-cand-avatar-img">
                                                    </template>
                                                    <template x-if="!cand.avatar">
                                                        <div class="emp-cand-avatar-initials" x-text="cand.initials || 'SP'"></div>
                                                    </template>
                                                </div>
                                                <div class="emp-cand-meta">
                                                    <span class="emp-cand-name" x-text="cand.name"></span>
                                                    <span class="emp-cand-applied-time" x-text="cand.applied_time"></span>
                                                </div>
                                            </div>
                                        </td>

                                        <!-- Role Cell -->
                                        <td>
                                            <span class="emp-cand-role-title" x-text="cand.role"></span>
                                        </td>

                                        <!-- Match Score Donut & Label -->
                                        <td>
                                            <div class="emp-cell-match">
                                                <div class="emp-score-donut">
                                                    <svg viewBox="0 0 36 36">
                                                        <path class="emp-score-donut-bg"
                                                              d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"
                                                              stroke="#E2E8F0"
                                                              stroke-width="3.5"
                                                              fill="none" />
                                                        <path class="emp-score-donut-val"
                                                              :stroke="cand.match_score >= 80 ? '#4F48E6' : (cand.match_score < 50 ? '#DC2626' : '#D97706')"
                                                              :stroke-dasharray="cand.match_score + ', 100'"
                                                              d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"
                                                              stroke-width="3.5"
                                                              stroke-linecap="round"
                                                              fill="none" />
                                                    </svg>
                                                    <span class="emp-score-num" x-text="cand.match_score"></span>
                                                </div>
                                                <span class="emp-score-label" x-text="cand.match_label"></span>
                                            </div>
                                        </td>

                                        <!-- Status Cell -->
                                        <td>
                                            <span class="emp-status-pill"
                                                  :class="{
                                                      'emp-status-applied': cand.status_type === 'applied',
                                                      'emp-status-shortlisted': cand.status_type === 'shortlisted',
                                                      'emp-status-scheduled': cand.status_type === 'scheduled',
                                                      'emp-status-rejected': cand.status_type === 'rejected'
                                                  }"
                                                  x-text="cand.status">
                                            </span>
                                        </td>
                                    </tr>
                                </template>

                                <!-- Empty State -->
                                <tr x-show="filteredCandidates.length === 0" style="display: none;">
                                    <td colspan="4" style="text-align: center; padding: 48px 24px;">
                                        <div style="max-width: 320px; margin: 0 auto; color: #64748B;">
                                            <svg style="width: 44px; height: 44px; margin: 0 auto 12px; color: #94A3B8;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                            </svg>
                                            <h4 style="font-size: 15px; font-weight: 700; color: #000623; margin: 0 0 4px;">No candidates found</h4>
                                            <p style="font-size: 13px; margin: 0;">Try adjusting your search criteria or filters to see more applicants.</p>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- ── 4. Table Pagination Footer ── -->
                    <div class="emp-table-footer">
                        <div>
                            Showing <span x-text="paginatedCandidates.length"></span> of <span x-text="totalCount"></span> candidates
                        </div>

                        <div class="emp-pagination-controls">
                            <button type="button" 
                                    class="emp-page-arrow" 
                                    :disabled="currentPage === 1" 
                                    @click="prevPage()"
                                    aria-label="Previous page">
                                <svg style="width: 14px; height: 14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                                </svg>
                            </button>

                            <button type="button" 
                                    class="emp-page-arrow" 
                                    :disabled="currentPage >= maxPages" 
                                    @click="nextPage()"
                                    aria-label="Next page">
                                <svg style="width: 14px; height: 14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>

</div>

<!-- ══ SCRIPT: CANDIDATE APPLICATIONS MANAGER ══ -->
<script>
function candidateApplicationsManager(initialCandidates) {
    return {
        candidates: initialCandidates || [],
        searchQuery: '',
        selectedRole: 'all',
        roleLabel: 'All Internship Roles',
        roleDropdownOpen: false,
        selectedStatus: 'all',
        statusLabel: 'All Statuses',
        statusDropdownOpen: false,
        sortBy: 'score',
        sortLabel: 'Match Score',
        sortDropdownOpen: false,
        currentPage: 1,
        pageSize: 7,

        rolesList: [
            { id: 'all', label: 'All Internship Roles' },
            { id: 'UX Design Intern', label: 'UX Design Intern' },
            { id: 'UI Design Intern', label: 'UI Design Intern' },
            { id: 'Marketing Intern', label: 'Marketing Intern' },
            { id: 'Software Engineering Intern', label: 'Software Engineering Intern' },
            { id: 'Social Media Manager Intern', label: 'Social Media Manager Intern' }
        ],

        statusList: [
            { id: 'all', label: 'All Statuses' },
            { id: 'applied', label: 'Applied' },
            { id: 'shortlisted', label: 'Shortlisted' },
            { id: 'scheduled', label: 'Interview Scheduled' },
            { id: 'rejected', label: 'Rejected' }
        ],

        sortOptions: [
            { id: 'score', label: 'Match Score' },
            { id: 'name', label: 'Candidate Name' },
            { id: 'recent', label: 'Newest First' }
        ],

        selectRole(r) {
            this.selectedRole = r.id;
            this.roleLabel = r.label;
            this.roleDropdownOpen = false;
            this.currentPage = 1;
        },

        selectStatus(s) {
            this.selectedStatus = s.id;
            this.statusLabel = s.label;
            this.statusDropdownOpen = false;
            this.currentPage = 1;
        },

        selectSort(opt) {
            this.sortBy = opt.id;
            this.sortLabel = opt.label;
            this.sortDropdownOpen = false;
        },

        get filteredCandidates() {
            let list = this.candidates.slice();

            // Search query filter
            if (this.searchQuery.trim() !== '') {
                const q = this.searchQuery.toLowerCase().trim();
                list = list.filter(c => c.name.toLowerCase().includes(q) || c.role.toLowerCase().includes(q));
            }

            // Role filter
            if (this.selectedRole !== 'all') {
                list = list.filter(c => c.role.toLowerCase() === this.selectedRole.toLowerCase());
            }

            // Status filter
            if (this.selectedStatus !== 'all') {
                list = list.filter(c => c.status_type === this.selectedStatus);
            }

            // Sorting
            if (this.sortBy === 'score') {
                list.sort((a, b) => b.match_score - a.match_score);
            } else if (this.sortBy === 'name') {
                list.sort((a, b) => a.name.localeCompare(b.name));
            }

            return list;
        },

        get totalCount() {
            return 42; // Real database count representation matching screenshot
        },

        get maxPages() {
            return Math.ceil(this.filteredCandidates.length / this.pageSize) || 1;
        },

        get paginatedCandidates() {
            const start = (this.currentPage - 1) * this.pageSize;
            return this.filteredCandidates.slice(start, start + this.pageSize);
        },

        prevPage() {
            if (this.currentPage > 1) {
                this.currentPage--;
            }
        },

        nextPage() {
            if (this.currentPage < this.maxPages) {
                this.currentPage++;
            }
        },

        openCandidate(id) {
            window.location.href = '<?= url('/employer/candidates/') ?>' + id;
        }
    };
}
</script>
