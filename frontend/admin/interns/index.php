<?php
declare(strict_types=1);
/**
 * Admin Interns Management & Profile Inspector Drawer View
 * Polished to match modern dashboard standards (Reference Images 1-4)
 * 
 * @var array  $staff      Current admin staff profile
 * @var string $activeNav  Active navigation identifier
 * @var array  $metrics    Top metric counters
 * @var array  $interns    List of candidate records
 */
?>
<script>
window.__adminInternsData = <?= json_encode($interns, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>;
function adminInterns() {
    return {
        interns: window.__adminInternsData || [],
        selectedIntern: null,
        drawerOpen: false,
        activeTab: 'all',
        drawerTab: 'academics',
        searchQuery: '',
        filterUniversity: '',
        dateRangeText: 'Last 30 days · to 15 Oct 2026',
        downloadResume(intern) {
            const docName = intern?.cv_document?.name || (intern?.name ? intern.name.replace(/\s+/g, '_') + '_Resume.pdf' : 'Candidate_Resume.pdf');
            if (typeof window.adminToast === 'function') {
                window.adminToast('Downloading candidate resume', docName, null, 'info');
            }
        },
        showDocViewerModal: false,
        viewDoc: { title: '', name: '', type: 'pdf', url: '', size: '1.2 MB' },
        openDocViewer(title, name, type, url, size = '1.2 MB') {
            this.viewDoc = { title, name, type: (type || 'pdf'), url, size };
            this.showDocViewerModal = true;
            document.body.style.overflow = 'hidden';
        },
        closeDocViewer() {
            this.showDocViewerModal = false;
            this.viewDoc = { title: '', name: '', type: 'pdf', url: '', size: '1.2 MB' };
            if (!this.drawerOpen) {
                document.body.style.overflow = '';
            }
        },
        selectedRows: [],
        selectAll: false,
        showSuspendModal: false,
        suspendTarget: null,
        suspendReason: 'terms',
        suspendNotes: '',
        isLoading: false,
        activeMenuId: null,
        kpi: { total: 0, active: 0, placed: 0, pending: 0 },
        init() {
            this.selectedIntern = null;
            this.drawerOpen = false;
            this.initCountUp();
            this.$watch('drawerOpen', val => {
                if (!val && !this.showDocViewerModal) document.body.style.overflow = '';
                else document.body.style.overflow = 'hidden';
            });
            this.$watch('showDocViewerModal', val => {
                if (!val && !this.drawerOpen) document.body.style.overflow = '';
                else document.body.style.overflow = 'hidden';
            });
            this.$nextTick(() => {
                const datePill = document.getElementById('interns-datepicker');
                if (datePill && typeof flatpickr !== 'undefined') {
                    flatpickr(datePill, {
                        mode: 'range',
                        dateFormat: 'd M Y',
                        monthSelectorType: 'static',
                        defaultDate: ['2026-09-15', '2026-10-15'],
                        onClose: (selectedDates) => {
                            if (selectedDates.length === 2) {
                                const d1 = selectedDates[0].toLocaleDateString('en-GB', { day: 'numeric', month: 'short' });
                                const d2 = selectedDates[1].toLocaleDateString('en-GB', { day: 'numeric', month: 'short', year: 'numeric' });
                                this.dateRangeText = `${d1} to ${d2}`;
                                if (typeof window.adminToast === 'function') {
                                    window.adminToast('Candidate date range applied', `${d1} to ${d2}`, null, 'info');
                                }
                            }
                        }
                    });
                }
            });
        },
        openInternDrawer(intern) {
            this.selectedIntern = intern;
            this.drawerOpen = true;
        },
        closeInternDrawer() {
            this.drawerOpen = false;
        },
        resetFilters() {
            this.searchQuery = '';
            this.activeTab = 'all';
            this.filterUniversity = '';
            this.showFilterPopover = false;
            window.adminToast('Filters reset to default', '', null, 'info');
        },
        toggleFilterTab(tab) {
            this.activeTab = (this.activeTab === tab ? 'all' : tab);
        },
        initCountUp() {
            const targets = { total: 12480, active: 8134, placed: 1312, pending: 37 };
            const duration = 800;
            const steps = 30;
            const interval = duration / steps;
            let step = 0;
            const timer = setInterval(() => {
                step++;
                const progress = step / steps;
                const ease = 1 - Math.pow(1 - progress, 3);
                this.kpi.total = Math.round(targets.total * ease);
                this.kpi.active = Math.round(targets.active * ease);
                this.kpi.placed = Math.round(targets.placed * ease);
                this.kpi.pending = Math.round(targets.pending * ease);
                if (step >= steps) {
                    clearInterval(timer);
                    this.kpi.total = targets.total;
                    this.kpi.active = targets.active;
                    this.kpi.placed = targets.placed;
                    this.kpi.pending = targets.pending;
                }
            }, interval);
        },
        toggleSelectAll() {
            this.selectAll = !this.selectAll;
            if (this.selectAll) {
                this.selectedRows = this.filteredInterns.map(i => i.id);
            } else {
                this.selectedRows = [];
            }
        },
        toggleRow(id) {
            const idx = this.selectedRows.indexOf(id);
            if (idx > -1) {
                this.selectedRows.splice(idx, 1);
            } else {
                this.selectedRows.push(id);
            }
            this.selectAll = (this.selectedRows.length === this.filteredInterns.length && this.filteredInterns.length > 0);
        },
        openSuspend(intern) {
            this.suspendTarget = intern;
            this.suspendReason = 'terms';
            this.suspendNotes = '';
            this.showSuspendModal = true;
        },
        confirmSuspend() {
            if (!this.suspendTarget) return;
            const targetName = this.suspendTarget.name;
            const targetId = this.suspendTarget.id;
            
            // Update local status
            const item = this.interns.find(i => i.id === targetId);
            if (item) {
                item.status = 'Suspended';
                item.status_class = 'suspended';
            }
            this.showSuspendModal = false;

            window.adminToast(
                targetName + ' account suspended',
                'Reason: ' + this.getReasonLabel(this.suspendReason),
                () => {
                    if (item) {
                        item.status = 'Active';
                        item.status_class = 'active';
                        window.adminToast('Suspension undone for ' + targetName, 'Account access restored', null, 'info');
                    }
                },
                'warning'
            );
        },
        getReasonLabel(val) {
            switch(val) {
                case 'terms': return 'Terms of service violation';
                case 'fraud': return 'Suspected fraudulent activity';
                case 'academic': return 'Inaccurate academic credentials';
                default: return 'Administrative review';
            }
        },
        get filteredInterns() {
            return this.interns.filter(i => {
                const query = this.searchQuery.toLowerCase().trim();
                const matchesSearch = !query || 
                    i.name.toLowerCase().includes(query) || 
                    i.university.toLowerCase().includes(query) ||
                    (i.degree && i.degree.toLowerCase().includes(query)) ||
                    (i.track && i.track.toLowerCase().includes(query)) ||
                    i.email.toLowerCase().includes(query);
                
                const matchesUniv = !this.filterUniversity || 
                    i.university.toLowerCase().includes(this.filterUniversity.toLowerCase());

                if (!matchesSearch || !matchesUniv) return false;
                if (this.activeTab === 'all') return true;
                if (this.activeTab === 'active') return i.status_class === 'active';
                if (this.activeTab === 'placed') return i.status_class === 'placed';
                if (this.activeTab === 'pending') return i.status_class === 'interview' || i.status === 'Awaiting Review' || i.status === 'Awaiting Interview';
                if (this.activeTab === 'suspended') return i.status_class === 'suspended';
                return true;
            });
        }
    };
}
</script>
<div class="admin-shell" x-data="adminInterns()" @keydown.escape.window="if (showDocViewerModal) closeDocViewer(); else if (drawerOpen) drawerOpen = false; if (showSuspendModal) showSuspendModal = false;">

    <!-- Left Navigation Sidebar -->
    <?php include dirname(__DIR__, 2) . '/shared/components/admin-sidebar.php'; ?>

    <!-- Main Content Wrapper -->
    <div class="admin-main-wrap">
        <!-- Top Navigation Control Bar -->
        <?php include dirname(__DIR__, 2) . '/shared/components/admin-topnav.php'; ?>

        <!-- Page Body Content -->
        <main class="admin-content-body">
            <!-- 1. Header Row -->
            <div class="admin-page-header">
                <div>
                    <h1 class="admin-page-title">Interns</h1>
                    <p class="admin-page-subtitle">Review profile, applications and placement status for every student and applicant</p>
                </div>
                <div>
                    <button type="button" class="admin-date-pill cursor-pointer" id="interns-datepicker" aria-label="Select date range">
                        <svg class="w-4 h-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span x-text="dateRangeText">Last 30 days · to 15 Oct 2026</span>
                    </button>
                </div>
            </div>

            <!-- 2. KPI 4-Column Card Grid with Count-Up, Top-Right Chips & Delta Lines -->
            <section class="admin-kpi-grid" aria-label="Intern Statistics">
                <!-- Total Interns -->
                <div class="admin-kpi-card admin-fade-in admin-fade-in-1">
                    <div class="admin-kpi-head">
                        <span class="admin-kpi-label">TOTAL INTERNS</span>
                        <div class="admin-kpi-icon-chip admin-kpi-icon-blue">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="admin-kpi-row">
                        <span class="admin-kpi-value" x-text="kpi.total.toLocaleString()">12,480</span>
                        <span class="admin-trend-badge admin-trend-primary">+312 this week</span>
                    </div>
                    <div class="admin-kpi-delta">
                        <span class="text-indigo-600 font-semibold">&uarr; 2.6%</span>
                        <span>vs last month</span>
                    </div>
                </div>

                <!-- Active This Month -->
                <div class="admin-kpi-card admin-fade-in admin-fade-in-2">
                    <div class="admin-kpi-head">
                        <span class="admin-kpi-label">ACTIVE THIS MONTH</span>
                        <div class="admin-kpi-icon-chip admin-kpi-icon-purple">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="admin-kpi-row">
                        <span class="admin-kpi-value" x-text="kpi.active.toLocaleString()">8,134</span>
                        <span class="admin-trend-badge admin-trend-neutral">65% of total</span>
                    </div>
                    <div class="admin-kpi-delta">
                        <span class="text-indigo-600 font-semibold">&bull; Stable</span>
                        <span>daily active rate</span>
                    </div>
                </div>

                <!-- Placed -->
                <div class="admin-kpi-card admin-fade-in admin-fade-in-3">
                    <div class="admin-kpi-head">
                        <span class="admin-kpi-label">PLACED</span>
                        <div class="admin-kpi-icon-chip admin-kpi-icon-emerald">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="admin-kpi-row">
                        <span class="admin-kpi-value" x-text="kpi.placed.toLocaleString()">1,312</span>
                        <span class="admin-trend-badge admin-trend-primary">+150 this week</span>
                    </div>
                    <div class="admin-kpi-delta">
                        <span class="text-indigo-600 font-semibold">&uarr; 12.8%</span>
                        <span>vs last month</span>
                    </div>
                </div>

                <!-- Awaiting Review (Standardized name) -->
                <div class="admin-kpi-card admin-fade-in admin-fade-in-4">
                    <div class="admin-kpi-head">
                        <span class="admin-kpi-label">AWAITING REVIEW</span>
                        <div class="admin-kpi-icon-chip admin-kpi-icon-amber">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="admin-kpi-row">
                        <span class="admin-kpi-value" x-text="kpi.pending.toLocaleString()">37</span>
                        <span class="admin-trend-badge admin-trend-attention">Needs decision</span>
                    </div>
                    <div class="admin-kpi-delta">
                        <span class="text-amber-600 font-semibold">&bull; 37 queued</span>
                        <span>profile & credential audits</span>
                    </div>
                </div>
            </section>

            <!-- 3. Master Table (100% Full Width Table) -->
            <div class="admin-table-container admin-fade-in admin-fade-in-2 w-full">
                <!-- Filter Tabs Bar with Search & Export -->
                <div class="admin-table-filter-bar">
                    <div class="admin-filter-tabs">
                        <button type="button" class="admin-tab-item" :class="{ 'is-active': activeTab === 'all' }" @click="activeTab = 'all'">
                            All 12,480
                        </button>
                        <button type="button" class="admin-tab-item" :class="{ 'is-active': activeTab === 'active' }" @click="activeTab = 'active'">
                            Active
                        </button>
                        <button type="button" class="admin-tab-item" :class="{ 'is-active': activeTab === 'placed' }" @click="activeTab = 'placed'">
                            Placed
                        </button>
                        <button type="button" class="admin-tab-item" :class="{ 'is-active': activeTab === 'pending' }" @click="activeTab = 'pending'">
                            <span>Awaiting Review</span>
                            <span class="admin-tab-badge-amber">37</span>
                        </button>
                        <button type="button" class="admin-tab-item" :class="{ 'is-active': activeTab === 'suspended' }" @click="activeTab = 'suspended'">
                            Suspended
                        </button>
                    </div>

                    <div class="flex items-center gap-2.5" x-data="{ showFilterPopover: false }">
                        <!-- Standardized Search Input with Inset Icon -->
                        <div class="admin-table-search-wrap">
                            <svg class="admin-table-search-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <input type="text" 
                                   class="admin-table-search-input" 
                                   placeholder="Filter candidates..."
                                   x-model="searchQuery">
                        </div>

                        <!-- Standardized Filter Button & Interactive Popover (Zero native browser alerts) -->
                        <div class="relative" @click.outside="showFilterPopover = false">
                            <button type="button" 
                                    class="admin-table-filter-btn" 
                                    :class="{ 'is-active': showFilterPopover }"
                                    @click="showFilterPopover = !showFilterPopover">
                                <svg class="w-3.5 h-3.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                                </svg>
                                <span>Filters</span>
                            </button>

                            <div x-show="showFilterPopover"
                                 x-transition:enter="transition ease-out duration-150"
                                 x-transition:enter-start="opacity-0 scale-95"
                                 x-transition:enter-end="opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-100"
                                 x-transition:leave-start="opacity-100 scale-100"
                                 x-transition:leave-end="opacity-0 scale-95"
                                 class="admin-filter-popover"
                                 style="display: none;">
                                <div class="flex items-center justify-between pb-2.5 border-b border-slate-100 mb-3">
                                    <span class="text-xs font-bold text-slate-900">Filter Candidates</span>
                                    <button type="button" class="text-[11px] font-semibold text-indigo-600 hover:text-indigo-800" @click="resetFilters()">Reset</button>
                                </div>
                                <div class="space-y-3 text-xs">
                                    <div>
                                        <label class="block font-semibold text-slate-700 mb-1 text-[11.5px]">University</label>
                                        <select class="w-full h-8 px-2.5 rounded-lg border border-slate-200 text-xs text-slate-800 bg-white outline-none focus:border-indigo-600"
                                                x-model="filterUniversity"
                                                @change="showFilterPopover = false;">
                                            <option value="">All Universities</option>
                                            <option value="Lagos">University of Lagos</option>
                                            <option value="Ibadan">University of Ibadan</option>
                                            <option value="Covenant">Covenant University</option>
                                            <option value="Ghana">University of Ghana</option>
                                            <option value="Ahmadu Bello">Ahmadu Bello University</option>
                                            <option value="Nairobi">University of Nairobi</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block font-semibold text-slate-700 mb-1 text-[11.5px]">Placement Status</label>
                                        <div class="flex items-center gap-1.5 flex-wrap">
                                            <button type="button" 
                                                    class="px-2.5 py-1 rounded-md border text-[11px] font-semibold transition-colors" 
                                                    :class="activeTab === 'placed' ? 'bg-indigo-600 text-white border-indigo-600' : 'border-slate-200 text-slate-700 hover:border-indigo-600 hover:text-indigo-600'"
                                                    @click="toggleFilterTab('placed'); showFilterPopover = false;">Placed</button>
                                            <button type="button" 
                                                    class="px-2.5 py-1 rounded-md border text-[11px] font-semibold transition-colors" 
                                                    :class="activeTab === 'active' ? 'bg-indigo-600 text-white border-indigo-600' : 'border-slate-200 text-slate-700 hover:border-indigo-600 hover:text-indigo-600'"
                                                    @click="toggleFilterTab('active'); showFilterPopover = false;">Active</button>
                                            <button type="button" 
                                                    class="px-2.5 py-1 rounded-md border text-[11px] font-semibold transition-colors" 
                                                    :class="activeTab === 'pending' ? 'bg-indigo-600 text-white border-indigo-600' : 'border-slate-200 text-slate-700 hover:border-indigo-600 hover:text-indigo-600'"
                                                    @click="toggleFilterTab('pending'); showFilterPopover = false;">Awaiting</button>
                                        </div>
                                    </div>
                                    <div class="pt-2 border-t border-slate-100 flex items-center justify-end gap-2">
                                        <button type="button" class="px-3 py-1.5 rounded-lg text-xs font-semibold text-slate-600 hover:bg-slate-100" @click="showFilterPopover = false">Cancel</button>
                                        <button type="button" class="px-3 py-1.5 rounded-lg text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-700" @click="showFilterPopover = false; window.adminToast('Candidate filters applied', '', null, 'success');">Apply</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Data Table with Full 100% Width & Rich Onboarding Data -->
                <div class="admin-table-wrap">
                    <table class="admin-table" role="table">
                        <thead style="position: sticky; top: 0; z-index: 10; background: #FAFAFA;">
                            <tr>
                                <th class="admin-th" style="width: 44px; padding: 12px 14px;">
                                    <input type="checkbox" 
                                           class="admin-checkbox"
                                           :checked="selectAll"
                                           @click="toggleSelectAll()">
                                </th>
                                <th class="admin-th">CANDIDATE</th>
                                <th class="admin-th" style="min-width: 200px;">ACADEMIC BACKGROUND</th>
                                <th class="admin-th">WORK PREFERENCE</th>
                                <th class="admin-th text-center">APPS</th>
                                <th class="admin-th">
                                    <div class="flex items-center gap-1">
                                        <span>AI MATCH</span>
                                        <svg class="w-3 h-3 text-slate-400 cursor-help" title="Calculated by Jenny AI from skills, education, and portfolio match" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                </th>
                                <th class="admin-th">PROFILE</th>
                                <th class="admin-th">STATUS</th>
                                <th class="admin-th text-right" style="width: 60px;">ACTION</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template x-for="intern in filteredInterns" :key="intern.id">
                                <tr class="admin-tr cursor-pointer" 
                                    :class="{ 'is-selected': selectedIntern && selectedIntern.id === intern.id && drawerOpen }"
                                    @click="openInternDrawer(intern)">
                                    <!-- Checkbox Selection -->
                                    <td class="admin-td" style="padding: 14px 14px;" @click.stop>
                                        <input type="checkbox" 
                                               class="admin-checkbox"
                                               :checked="selectedRows.includes(intern.id)"
                                               @click="toggleRow(intern.id)">
                                    </td>

                                    <!-- Candidate Identity Cell (Name, Email, Track) -->
                                    <td class="admin-td">
                                        <div class="admin-user-cell">
                                            <div class="admin-user-avatar" x-text="intern.initials"></div>
                                            <div class="min-w-0">
                                                <div class="admin-user-name truncate" x-text="intern.name"></div>
                                                <div class="admin-user-email truncate" x-text="intern.email"></div>
                                                <span class="inline-block mt-1 text-[10.5px] font-semibold px-2 py-0.5 rounded-full bg-slate-100 text-slate-600 border border-slate-200" x-text="intern.track || 'Intern'"></span>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Academic Background Cell (Degree + Year on top, Institution below) -->
                                    <td class="admin-td" style="min-width: 200px; white-space: nowrap;">
                                        <div class="font-semibold text-xs text-slate-900" x-text="(intern.degree || 'B.Sc. Candidate') + ' · ' + (intern.grad_year || '2025')"></div>
                                        <div class="text-[11.5px] text-slate-500 mt-0.5" x-text="intern.university"></div>
                                    </td>

                                    <!-- Work Preference Pill -->
                                    <td class="admin-td">
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-700 border border-slate-200">
                                            <span class="w-1.5 h-1.5 rounded-full"
                                                  :class="{
                                                      'bg-indigo-500': intern.work_mode === 'Remote',
                                                      'bg-amber-500': intern.work_mode === 'Hybrid',
                                                      'bg-emerald-500': intern.work_mode === 'Onsite' || intern.work_mode === 'Remote / Hybrid'
                                                  }"></span>
                                            <span x-text="intern.work_mode || 'Remote'"></span>
                                        </span>
                                    </td>

                                    <!-- Apps Count Cell -->
                                    <td class="admin-td text-center font-bold text-slate-800 text-xs" x-text="intern.applications_count"></td>

                                    <!-- AI Match Pill with Tooltip -->
                                    <td class="admin-td">
                                        <span class="admin-score-pill group relative cursor-help" 
                                              :class="{
                                                  'admin-score-purple': intern.ai_match_score >= 90,
                                                  'admin-score-blue': intern.ai_match_score >= 80 && intern.ai_match_score < 90,
                                                  'admin-score-slate': intern.ai_match_score >= 70 && intern.ai_match_score < 80,
                                                  'admin-score-amber': intern.ai_match_score < 70
                                              }" 
                                              title="Calculated by Jenny AI from skills, education, and portfolio match"
                                              x-text="intern.ai_match_score">
                                        </span>
                                    </td>

                                    <!-- Profile Completeness Bar -->
                                    <td class="admin-td">
                                        <div class="admin-profile-bar-wrap">
                                            <div class="admin-profile-track">
                                                <div class="admin-profile-bar" :style="'width: ' + intern.profile_completeness + '%;'"></div>
                                            </div>
                                            <span class="admin-profile-pct" x-text="intern.profile_completeness + '%'"></span>
                                        </div>
                                    </td>

                                    <!-- Status Pill with Dot -->
                                    <td class="admin-td">
                                        <span class="admin-status-pill"
                                              :class="{
                                                  'admin-status-placed': intern.status_class === 'placed',
                                                  'admin-status-active': intern.status_class === 'active',
                                                  'admin-status-interview': intern.status_class === 'interview' || intern.status === 'Awaiting Review',
                                                  'admin-status-suspended': intern.status_class === 'suspended'
                                              }">
                                            <span class="w-1.5 h-1.5 rounded-full"
                                                  :class="{
                                                      'bg-purple-600': intern.status_class === 'placed',
                                                      'bg-emerald-600': intern.status_class === 'active',
                                                      'bg-amber-600': intern.status_class === 'interview' || intern.status === 'Awaiting Review',
                                                      'bg-rose-600': intern.status_class === 'suspended'
                                                  }"></span>
                                            <span x-text="intern.status"></span>
                                        </span>
                                    </td>

                                    <!-- Row Menu Action -->
                                    <td class="admin-td text-right" @click.stop style="position: relative; width: 60px;">
                                        <div class="relative inline-block text-left" @click.outside="if (activeMenuId === intern.id) activeMenuId = null">
                                            <button type="button" 
                                                    @click="activeMenuId = (activeMenuId === intern.id ? null : intern.id)"
                                                    class="admin-row-action-btn"
                                                    :class="{ 'is-active': activeMenuId === intern.id }"
                                                    aria-label="Row menu">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"/>
                                                </svg>
                                            </button>
                                            <div x-show="activeMenuId === intern.id" 
                                                 x-transition:enter="transition ease-out duration-150"
                                                 x-transition:enter-start="opacity-0 scale-95"
                                                 x-transition:enter-end="opacity-100 scale-100"
                                                 x-transition:leave="transition ease-in duration-100"
                                                 x-transition:leave-start="opacity-100 scale-100"
                                                 x-transition:leave-end="opacity-0 scale-95"
                                                 class="admin-action-dropdown"
                                                 style="display: none;">
                                                <button type="button" class="admin-action-dropdown-item" @click="openInternDrawer(intern); activeMenuId = null;">
                                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                    </svg>
                                                    <span>Inspect Profile</span>
                                                </button>
                                                <button type="button" class="admin-action-dropdown-item" @click="window.adminToast('Opening message channel with ' + intern.name, '', null, 'info'); activeMenuId = null;">
                                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                                    </svg>
                                                    <span>Send Message</span>
                                                </button>
                                                <button type="button" class="admin-action-dropdown-item" @click="window.adminToast('Profile link copied to clipboard', '', null, 'success'); activeMenuId = null;">
                                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                                                    </svg>
                                                    <span>Copy Public Link</span>
                                                </button>
                                                <div class="admin-action-dropdown-divider"></div>
                                                <button type="button" class="admin-action-dropdown-item is-danger" @click="openSuspend(intern); activeMenuId = null;">
                                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                                    </svg>
                                                    <span>Suspend Account...</span>
                                                </button>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </template>

                            <!-- Empty State -->
                            <template x-if="filteredInterns.length === 0">
                                <tr>
                                    <td colspan="9" class="text-center py-12 text-slate-500">
                                        <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center mx-auto mb-3 text-slate-400">
                                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                            </svg>
                                        </div>
                                        <p class="font-semibold text-slate-700">No candidates match this filter</p>
                                        <p class="text-xs text-slate-400 mt-1">Try resetting your search query or selecting "All 12,480"</p>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination Footer -->
                <div class="admin-table-pagination">
                    <span>Showing 1 to 8 of 12,480</span>
                    <div class="admin-page-btn-group">
                        <button type="button" class="admin-page-btn">Previous</button>
                        <button type="button" class="admin-page-btn is-active">1</button>
                        <button type="button" class="admin-page-btn">2</button>
                        <button type="button" class="admin-page-btn">3</button>
                        <span class="px-2 text-slate-400">...</span>
                        <button type="button" class="admin-page-btn">1,560</button>
                        <button type="button" class="admin-page-btn">Next</button>
                    </div>
                </div>
            </div>

            <!-- Floating Bulk Action Bar (Fixed at Viewport Bottom Center, High Z-Index) -->
            <div x-show="selectedRows.length > 0" 
                 x-transition 
                 class="admin-floating-bulk-bar"
                 style="display: none;">
                <span class="text-slate-300">
                    <strong class="text-white" x-text="selectedRows.length"></strong> interns selected
                </span>
                <div class="h-4 w-px bg-slate-700"></div>
                <button type="button" class="hover:text-indigo-400 transition-colors" @click="window.adminToast('Exporting ' + selectedRows.length + ' candidates (CSV)', '', null, 'info')">
                    Export
                </button>
                <button type="button" class="hover:text-indigo-400 transition-colors" @click="window.adminToast('Bulk message composer queued', '', null, 'info')">
                    Bulk Email
                </button>
                <button type="button" class="hover:text-indigo-400 transition-colors" @click="window.adminToast('Tags updated for ' + selectedRows.length + ' candidates', '', null, 'success')">
                    Add Tag
                </button>
                <button type="button" class="text-slate-400 hover:text-white transition-colors ml-2" @click="selectedRows = []; selectAll = false;">
                    Deselect all
                </button>
            </div>

            <!-- 4. Profile Inspector Off-Canvas Slide-Over Drawer with Floating Backdrop -->
            <!-- Backdrop -->
            <div x-show="drawerOpen && selectedIntern" 
                 x-transition:enter="transition ease-out duration-250"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @click="drawerOpen = false" 
                 class="fixed inset-0 z-50 bg-[#000623]/40 backdrop-blur-sm" 
                 style="display: none;"></div>

            <!-- Off-Canvas Drawer (Responsive for Mobile & Desktop) -->
            <aside class="admin-inspector-drawer" 
                   x-show="drawerOpen && selectedIntern" 
                   x-transition:enter="transition ease-out duration-300"
                   x-transition:enter-start="translate-x-full"
                   x-transition:enter-end="translate-x-0"
                   x-transition:leave="transition ease-in duration-200"
                   x-transition:leave-start="translate-x-0"
                   x-transition:leave-end="translate-x-full"
                   style="display: none; width: 520px; max-width: 100vw;">
                
                <!-- Drawer Header -->
                <div class="admin-drawer-header">
                    <div class="admin-drawer-user-info">
                        <div class="admin-drawer-avatar" x-text="selectedIntern?.initials"></div>
                        <div class="min-w-0">
                            <div class="flex items-center gap-2">
                                <h2 class="admin-drawer-title truncate" x-text="selectedIntern?.name"></h2>
                                <template x-if="selectedIntern?.verified_email">
                                    <svg class="w-4 h-4 text-emerald-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20" title="Verified Student Email">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                </template>
                            </div>
                            <p class="admin-drawer-sub truncate" x-text="(selectedIntern?.degree ? selectedIntern?.degree + ' · ' : '') + selectedIntern?.university"></p>
                        </div>
                    </div>
                    <button type="button" class="admin-drawer-close-btn" @click="drawerOpen = false" aria-label="Close inspector">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <!-- Scrollable Body with Tabs & Full Onboarding Credentials -->
                <div class="admin-drawer-body">
                    <!-- Structured Tabs (Academics, Skills & Links, Pipeline, Activity) -->
                    <div class="flex items-center gap-1 p-1 bg-slate-100 rounded-lg text-xs font-semibold">
                        <button type="button" 
                                class="flex-1 py-1.5 rounded-md text-center transition-all whitespace-nowrap"
                                :class="drawerTab === 'academics' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-500 hover:text-slate-900'"
                                @click="drawerTab = 'academics'">
                            Academics
                        </button>
                        <button type="button" 
                                class="flex-1 py-1.5 rounded-md text-center transition-all whitespace-nowrap"
                                :class="drawerTab === 'skills' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-500 hover:text-slate-900'"
                                @click="drawerTab = 'skills'">
                            Skills & Links
                        </button>
                        <button type="button" 
                                class="flex-1 py-1.5 rounded-md text-center transition-all whitespace-nowrap"
                                :class="drawerTab === 'applications' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-500 hover:text-slate-900'"
                                @click="drawerTab = 'applications'">
                            Pipeline (<span x-text="selectedIntern?.applications_count || 0"></span>)
                        </button>
                        <button type="button" 
                                class="flex-1 py-1.5 rounded-md text-center transition-all whitespace-nowrap"
                                :class="drawerTab === 'activity' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-500 hover:text-slate-900'"
                                @click="drawerTab = 'activity'">
                            Audit
                        </button>
                    </div>

                    <!-- Tab 1: Academics & Verified Onboarding Credentials (Rule K Compliant) -->
                    <div x-show="drawerTab === 'academics'" class="flex flex-col gap-4">
                        <!-- Key-Value Academic & Placement Preferences List -->
                        <div>
                            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block mb-2">Academic & Onboarding Record</span>
                            <div class="admin-key-value-list">
                                <div class="admin-kv-row">
                                    <div class="admin-kv-label">
                                        <svg class="w-3.5 h-3.5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"/>
                                        </svg>
                                        <span>Institution</span>
                                    </div>
                                    <span class="admin-kv-value text-slate-900 font-semibold" x-text="selectedIntern?.university"></span>
                                </div>
                                <div class="admin-kv-row">
                                    <div class="admin-kv-label">
                                        <svg class="w-3.5 h-3.5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                        <span>Degree & Major</span>
                                    </div>
                                    <span class="admin-kv-value text-slate-800" x-text="(selectedIntern?.degree || 'Undergraduate') + (selectedIntern?.major ? ' (' + selectedIntern.major + ')' : '')"></span>
                                </div>
                                <div class="admin-kv-row">
                                    <div class="admin-kv-label">
                                        <svg class="w-3.5 h-3.5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        <span>Current Level</span>
                                    </div>
                                    <span class="admin-kv-value text-indigo-600 font-semibold" x-text="selectedIntern?.level || '400 Level (Final Year)'"></span>
                                </div>
                                <div class="admin-kv-row">
                                    <div class="admin-kv-label">
                                        <svg class="w-3.5 h-3.5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <span>Academic Standing / CGPA</span>
                                    </div>
                                    <span class="admin-kv-value text-emerald-700 font-bold" x-text="selectedIntern?.cgpa || '4.50 / 5.00 (First Class)'"></span>
                                </div>
                                <div class="admin-kv-row">
                                    <div class="admin-kv-label">
                                        <svg class="w-3.5 h-3.5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                        </svg>
                                        <span>Student Email</span>
                                    </div>
                                    <span class="admin-kv-value text-slate-800 font-mono text-[11px]" x-text="selectedIntern?.email"></span>
                                </div>
                                <div class="admin-kv-row">
                                    <div class="admin-kv-label">
                                        <svg class="w-3.5 h-3.5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                        </svg>
                                        <span>Phone Number</span>
                                    </div>
                                    <span class="admin-kv-value text-slate-700 font-mono text-[11px]" x-text="selectedIntern?.phone || '+234 800 000 0000'"></span>
                                </div>
                                <div class="admin-kv-row">
                                    <div class="admin-kv-label">
                                        <svg class="w-3.5 h-3.5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                        </svg>
                                        <span>Internship Track</span>
                                    </div>
                                    <span class="admin-kv-value text-indigo-700 font-semibold" x-text="selectedIntern?.track || 'General'"></span>
                                </div>
                                <div class="admin-kv-row">
                                    <div class="admin-kv-label">
                                        <svg class="w-3.5 h-3.5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <span>Duration & Work Mode</span>
                                    </div>
                                    <span class="admin-kv-value text-slate-800" x-text="(selectedIntern?.duration || '6 Months') + ' · ' + (selectedIntern?.work_mode || 'Remote')"></span>
                                </div>
                            </div>
                        </div>

                        <!-- Uploaded Documents (CV / Resume Submission Only - Rule: No View ID) -->
                        <div>
                            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block mb-2">Verified Onboarding Document</span>
                            <div>
                                <!-- CV / Resume Preview Card (Standardized .admin-doc-card) -->
                                <div class="admin-doc-card">
                                    <div class="admin-doc-card-left">
                                        <div class="admin-doc-card-icon">
                                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                        </div>
                                        <div class="admin-doc-card-info">
                                            <span class="admin-doc-card-name" x-text="selectedIntern?.cv_document?.name || (selectedIntern?.name ? selectedIntern.name.replace(/\s+/g, '_') + '_Resume.pdf' : 'Candidate_Resume.pdf')">Candidate_Resume.pdf</span>
                                            <span class="admin-doc-card-meta" x-text="(selectedIntern?.cv_document?.size || '1.2 MB') + ' · ' + (selectedIntern?.cv_document?.status || 'Verified by AI Parser')">1.2 MB · Verified by AI Parser</span>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-1.5 flex-shrink-0">
                                        <button type="button" 
                                                class="admin-doc-card-btn cursor-pointer" 
                                                @click="openDocViewer(selectedIntern?.name + ' Resume', selectedIntern?.cv_document?.name || (selectedIntern?.name ? selectedIntern.name.replace(/\s+/g, '_') + '_Resume.pdf' : 'Candidate_Resume.pdf'), 'pdf', selectedIntern?.cv_document?.url || '<?= url('assets/images/dummb/testing.pdf.pdf') ?>', selectedIntern?.cv_document?.size || '1.2 MB')">
                                            <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                            <span>View Document</span>
                                        </button>
                                        <a :href="selectedIntern?.cv_document?.url || '<?= url('assets/images/dummb/testing.pdf.pdf') ?>'" 
                                           :download="selectedIntern?.cv_document?.name || 'Candidate_Resume.pdf'" 
                                           class="p-2 text-slate-400 hover:text-indigo-600 rounded-lg hover:bg-slate-100 transition-colors cursor-pointer" 
                                           title="Download Document" 
                                           aria-label="Download Document" 
                                           @click="downloadResume(selectedIntern)">
                                            <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tab 2: Skills Taxonomy, Languages & Portfolio Links -->
                    <div x-show="drawerTab === 'skills'" class="flex flex-col gap-4">
                        <!-- Technical Skills -->
                        <div>
                            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block mb-2">Technical Skills & Disciplines</span>
                            <div class="flex flex-wrap gap-1.5">
                                <template x-for="skill in selectedIntern?.skills" :key="skill">
                                    <span class="admin-tech-pill" x-text="skill"></span>
                                </template>
                            </div>
                        </div>

                        <!-- Soft Skills (from onboarding step) -->
                        <div>
                            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block mb-2">Verified Soft Skills</span>
                            <div class="flex flex-wrap gap-1.5">
                                <template x-for="soft in (selectedIntern?.soft_skills || ['Critical Thinking', 'Cross-functional Collaboration', 'Adaptability'])" :key="soft">
                                    <span class="admin-soft-pill" x-text="soft"></span>
                                </template>
                            </div>
                        </div>

                        <!-- Languages -->
                        <div>
                            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block mb-2">Languages Spoken</span>
                            <div class="flex flex-wrap gap-1.5">
                                <template x-for="lang in (selectedIntern?.languages || ['English (Fluent)'])" :key="lang">
                                    <span class="admin-soft-pill" x-text="lang"></span>
                                </template>
                            </div>
                        </div>

                        <!-- External Links & Portfolios -->
                        <div>
                            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block mb-2">Portfolio & Public Profiles</span>
                            <div class="admin-key-value-list">
                                <div class="admin-kv-row">
                                    <div class="admin-kv-label">
                                        <svg class="w-3.5 h-3.5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                                        </svg>
                                        <span>Portfolio</span>
                                    </div>
                                    <a :href="selectedIntern?.portfolio_url || '#'" target="_blank" class="admin-kv-value text-indigo-600 hover:underline flex items-center gap-1 font-mono text-[11px]">
                                        <span x-text="selectedIntern?.portfolio_url || 'portfolio.internconnect.ng'"></span>
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                        </svg>
                                    </a>
                                </div>
                                <div class="admin-kv-row">
                                    <div class="admin-kv-label">
                                        <svg class="w-3.5 h-3.5 text-slate-700" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M12 0C5.37 0 0 5.37 0 12c0 5.31 3.435 9.795 8.205 11.385.6.105.825-.255.825-.57 0-.285-.015-1.23-.015-2.235-3.015.555-3.795-.735-4.035-1.41-.135-.345-.72-1.41-1.23-1.695-.42-.225-1.02-.78-.015-.795.945-.015 1.62.87 1.845 1.23 1.08 1.815 2.805 1.305 3.495.99.105-.78.42-1.305.765-1.605-2.67-.3-5.46-1.335-5.46-5.925 0-1.305.465-2.385 1.23-3.225-.12-.3-.54-1.53.12-3.18 0 0 1.005-.315 3.3 1.23.96-.27 1.98-.405 3-.405s2.04.135 3 .405c2.295-1.56 3.3-1.23 3.3-1.23.66 1.65.24 2.88.12 3.18.765.84 1.23 1.905 1.23 3.225 0 4.605-2.805 5.625-5.475 5.925.435.375.81 1.095.81 2.22 0 1.605-.015 2.895-.015 3.3 0 .315.225.69.825.57A12.02 12.02 0 0024 12c0-6.63-5.37-12-12-12z"/>
                                        </svg>
                                        <span>GitHub</span>
                                    </div>
                                    <a :href="selectedIntern?.github_url || '#'" target="_blank" class="admin-kv-value text-indigo-600 hover:underline flex items-center gap-1 font-mono text-[11px]">
                                        <span x-text="selectedIntern?.github_url || 'github.com/profile'"></span>
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                        </svg>
                                    </a>
                                </div>
                                <div class="admin-kv-row">
                                    <div class="admin-kv-label">
                                        <svg class="w-3.5 h-3.5 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/>
                                        </svg>
                                        <span>LinkedIn</span>
                                    </div>
                                    <a :href="selectedIntern?.linkedin_url || '#'" target="_blank" class="admin-kv-value text-indigo-600 hover:underline flex items-center gap-1 font-mono text-[11px]">
                                        <span x-text="selectedIntern?.linkedin_url || 'linkedin.com/in/profile'"></span>
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Jenny AI Fit Analysis Card -->
                        <div class="admin-drawer-ai-card">
                            <div class="admin-drawer-ai-head">
                                <img src="<?= asset('assets/images/jenny_ai/jenny_icon.png') ?>" alt="Jenny AI" class="w-4 h-4 rounded-full object-cover">
                                <span>Jenny AI Fit Analysis</span>
                            </div>
                            <p class="admin-drawer-ai-text" x-text="selectedIntern?.ai_insight"></p>
                        </div>
                    </div>

                    <!-- Tab 3: Applications Pipeline -->
                    <div x-show="drawerTab === 'applications'" class="flex flex-col gap-4">
                        <!-- Stat Boxes -->
                        <div class="admin-drawer-stats-row">
                            <div>
                                <div class="admin-drawer-stat-val text-indigo-600" x-text="selectedIntern?.applications_count"></div>
                                <div class="admin-drawer-stat-lbl">Applications</div>
                            </div>
                            <div>
                                <div class="admin-drawer-stat-val text-slate-800" x-text="selectedIntern?.interviews_count"></div>
                                <div class="admin-drawer-stat-lbl">Interviews</div>
                            </div>
                            <div>
                                <div class="admin-drawer-stat-val text-emerald-600" x-text="selectedIntern?.offers_count"></div>
                                <div class="admin-drawer-stat-lbl">Offers</div>
                            </div>
                        </div>

                        <span class="admin-timeline-title">Recruitment Pipeline History</span>
                        <div class="space-y-2.5">
                            <template x-for="app in selectedIntern?.recent_applications" :key="app.company + app.role">
                                <div class="p-3 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 transition-colors shadow-xs">
                                    <div class="flex items-center justify-between">
                                        <span class="font-bold text-xs text-slate-900" x-text="app.company"></span>
                                        <span class="text-[10.5px] text-slate-400" x-text="app.date"></span>
                                    </div>
                                    <div class="text-xs text-slate-600 mt-0.5" x-text="app.role"></div>
                                    <div class="flex items-center gap-1.5 mt-2">
                                        <span class="w-2 h-2 rounded-full"
                                              :class="{
                                                  'bg-emerald-500': app.status_dot === 'green',
                                                  'bg-amber-500': app.status_dot === 'amber',
                                                  'bg-rose-500': app.status_dot === 'red',
                                                  'bg-blue-500': app.status_dot === 'blue'
                                              }"></span>
                                        <span class="text-[11px] font-semibold text-slate-700" x-text="app.status"></span>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- Tab 4: Activity & Audit -->
                    <div x-show="drawerTab === 'activity'" class="flex flex-col gap-4 text-xs text-slate-600">
                        <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Security & Activity Audit</span>
                        <div class="admin-key-value-list">
                            <div class="admin-kv-row">
                                <div class="admin-kv-label">
                                    <svg class="w-3.5 h-3.5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <span>Account Created</span>
                                </div>
                                <span class="admin-kv-value text-slate-800 font-semibold">14 August 2026</span>
                            </div>
                            <div class="admin-kv-row">
                                <div class="admin-kv-label">
                                    <svg class="w-3.5 h-3.5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span>Last Active</span>
                                </div>
                                <span class="admin-kv-value text-emerald-600 font-semibold">Active today (2h ago)</span>
                            </div>
                            <div class="admin-kv-row">
                                <div class="admin-kv-label">
                                    <svg class="w-3.5 h-3.5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                    </svg>
                                    <span>Institution Verification</span>
                                </div>
                                <span class="admin-kv-value text-emerald-600 font-bold flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Verified via .edu.ng
                                </span>
                            </div>
                            <div class="admin-kv-row">
                                <div class="admin-kv-label">
                                    <svg class="w-3.5 h-3.5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                    </svg>
                                    <span>Profile Completeness</span>
                                </div>
                                <span class="admin-kv-value text-indigo-600 font-bold" x-text="selectedIntern?.profile_completeness + '%'"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sticky Drawer Actions -->
                <div class="admin-drawer-actions">
                    <button type="button" class="admin-btn-indigo flex-1" @click="window.adminToast('Opening candidate public portfolio...', '', null, 'info')">
                        <span>Open Profile</span>
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </button>
                    <button type="button" class="admin-btn-dark" @click="openSuspend(selectedIntern)">
                        Suspend account
                    </button>
                </div>
            </aside>

            <!-- 5. Suspend Account Modal (Requirement 11 & 12) -->
            <div x-show="showSuspendModal" 
                 x-transition:enter="transition ease-out duration-180"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-[#000623]/50 backdrop-blur-sm"
                 style="display: none;">
                
                <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl border border-slate-200 transform transition-transform duration-180"
                     x-show="showSuspendModal"
                     x-transition:enter="transition ease-out duration-180"
                     x-transition:enter-start="scale-95"
                     x-transition:enter-end="scale-100"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="scale-100"
                     x-transition:leave-end="scale-95"
                     @click.outside="showSuspendModal = false">
                    
                    <div class="flex items-center gap-3 pb-3 border-b border-slate-100">
                        <div class="w-10 h-10 rounded-full bg-slate-100 text-slate-800 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-900 text-base">Suspend Intern Account</h3>
                            <p class="text-xs text-slate-500" x-text="'Target: ' + (suspendTarget?.name || '')"></p>
                        </div>
                    </div>

                    <div class="py-4 space-y-3 text-xs">
                        <label class="block font-semibold text-slate-700">Reason for Account Suspension *</label>
                        
                        <div class="space-y-2">
                            <label class="flex items-center gap-2.5 p-2 rounded-lg border border-slate-200 hover:bg-slate-50 cursor-pointer">
                                <input type="radio" name="suspend_reason" value="terms" x-model="suspendReason" class="text-indigo-600 focus:ring-indigo-500">
                                <div>
                                    <span class="font-semibold text-slate-900 block">Terms of Service Violation</span>
                                    <span class="text-slate-500 text-[11px]">Unsolicited spamming or unprofessional conduct</span>
                                </div>
                            </label>

                            <label class="flex items-center gap-2.5 p-2 rounded-lg border border-slate-200 hover:bg-slate-50 cursor-pointer">
                                <input type="radio" name="suspend_reason" value="fraud" x-model="suspendReason" class="text-indigo-600 focus:ring-indigo-500">
                                <div>
                                    <span class="font-semibold text-slate-900 block">Suspected Fraudulent Activity</span>
                                    <span class="text-slate-500 text-[11px]">Identity impersonation or duplicate accounts</span>
                                </div>
                            </label>

                            <label class="flex items-center gap-2.5 p-2 rounded-lg border border-slate-200 hover:bg-slate-50 cursor-pointer">
                                <input type="radio" name="suspend_reason" value="academic" x-model="suspendReason" class="text-indigo-600 focus:ring-indigo-500">
                                <div>
                                    <span class="font-semibold text-slate-900 block">Inaccurate Academic Credentials</span>
                                    <span class="text-slate-500 text-[11px]">Falsified university enrollment or graduation proof</span>
                                </div>
                            </label>
                        </div>

                        <div class="pt-3">
                            <label class="block font-semibold text-slate-700 text-xs mb-2">Additional Staff Notes (Optional)</label>
                            <textarea class="admin-modal-textarea" 
                                      rows="3" 
                                      style="padding-top: 8px !important; padding-bottom: 8px !important; padding-left: 14px !important; padding-right: 14px !important; min-height: 85px !important; line-height: 1.45 !important; font-family: inherit !important; resize: vertical !important; box-sizing: border-box !important;"
                                      x-model="suspendNotes"
                                      placeholder="Provide context for audit logging..."></textarea>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
                        <button type="button" 
                                class="px-4 py-2 rounded-lg text-xs font-semibold text-slate-600 hover:bg-slate-100 transition-colors"
                                @click="showSuspendModal = false">
                            Cancel
                        </button>
                        <button type="button" 
                                class="px-4 py-2 rounded-lg text-xs font-semibold text-white bg-slate-900 hover:bg-slate-800 transition-colors shadow-xs"
                                @click="confirmSuspend()">
                            Confirm Suspension
                        </button>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Reusable Document Viewer Modal (Curriculum Vitae Preview) -->
    <?php include dirname(__DIR__, 2) . '/shared/components/admin-doc-modal.php'; ?>
</div>
