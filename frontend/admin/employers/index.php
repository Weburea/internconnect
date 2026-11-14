<?php
declare(strict_types=1);
/**
 * Admin Employers & Global Verification Desk View
 * Polished to match modern dashboard standards (Reference Images 1-4)
 * 
 * @var array  $staff      Current admin staff profile
 * @var string $activeNav  Active navigation identifier
 * @var array  $metrics    Top metric counters
 * @var array  $queue      Pending verification requests (Top Shelf)
 * @var array  $employers  Directory of company accounts
 */
?>
<script>
window.__adminEmployersData = <?= json_encode($employers, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>;
function adminEmployers() {
    return {
        activeTab: <?= json_encode(($segment ?? '') ?: 'all') ?>,
        searchQuery: '',
        filterIndustry: '',
        selectedCompany: null,
        drawerOpen: false,
        downloadDoc(company) {
            const docName = company?.doc_name || (company?.company_name ? company.company_name.replace(/\s+/g, '_') + '_CAC_Certificate.pdf' : 'CAC_Certificate.pdf');
            if (typeof window.adminToast === 'function') {
                window.adminToast('Downloading corporate document', docName, null, 'info');
            }
        },
        showDocViewerModal: false,
        dateRangeText: 'Last 30 days · to 15 Oct 2026',
        viewDoc: { title: '', name: '', type: 'pdf', url: '', size: '1.4 MB' },
        openDocViewer(title, name, type, url, size = '1.4 MB') {
            this.viewDoc = { title, name, type: (type || 'pdf'), url, size };
            this.showDocViewerModal = true;
            document.body.style.overflow = 'hidden';
        },
        closeDocViewer() {
            this.showDocViewerModal = false;
            this.viewDoc = { title: '', name: '', type: 'pdf', url: '', size: '1.4 MB' };
            if (!this.drawerOpen) {
                document.body.style.overflow = '';
            }
        },
        selectedRows: [],
        selectAll: false,
        activeMenuId: null,
        kpi: { total: 0, roles: 0, placed: 0, pending: 0 },
        dismissedQueueIds: [],
        resetFilters() {
            this.searchQuery = '';
            this.activeTab = 'all';
            this.filterIndustry = '';
            this.showFilterPopover = false;
            window.adminToast('Filters reset to default', '', null, 'info');
        },
        toggleFilterTab(tab) {
            this.activeTab = (this.activeTab === tab ? 'all' : tab);
        },
        openCompanyDrawer(company) {
            this.selectedCompany = company;
            this.drawerOpen = true;
        },
        queue: [
            {
                id: 101,
                company_name: 'Nexura Technologies',
                industry: 'Fintech',
                location: 'Lagos, Nigeria',
                initials: 'NT',
                avatar_color: '#4F48E6',
                submitted_time: 'Submitted 2 days ago',
                cac_number: 'RC-1849204',
                registry_status: 'Verified (Active RC)',
                registry_chip: 'indigo',
                doc_type: 'pdf',
                doc_name: 'Nexura_Technologies_CAC_Certificate.pdf',
                doc_size: '1.4 MB',
                doc_url: '<?= asset('assets/images/dummb/testing.pdf.pdf') ?>',
                checks: [
                    { label: 'CAC certificate', status: 'valid' },
                    { label: 'Work email (domain match)', status: 'valid' },
                    { label: 'Website live & active', status: 'valid' }
                ]
            },
            {
                id: 102,
                company_name: 'Kiro Labs',
                industry: 'Design & AI',
                location: 'Remote / Lagos',
                initials: 'KL',
                avatar_color: '#0F172A',
                submitted_time: 'Submitted 3 days ago',
                cac_number: 'BN-938102',
                registry_status: 'Rechecking API',
                registry_chip: 'indigo',
                doc_type: 'image',
                doc_name: 'Kiro_Labs_Incorporation_License.png',
                doc_size: '840 KB',
                doc_url: '<?= asset('assets/images/dummb/testing_img.png') ?>',
                checks: [
                    { label: 'CAC certificate', status: 'valid' },
                    { label: 'Work email (domain match)', status: 'valid' },
                    { label: 'Website live & active', status: 'valid' }
                ]
            },
            {
                id: 103,
                company_name: 'PaySprint Global',
                industry: 'Financial Services',
                location: 'Abuja, Nigeria',
                initials: 'PS',
                avatar_color: '#059669',
                submitted_time: 'Submitted 3 days ago',
                cac_number: 'RC-PENDING',
                registry_status: 'Not found yet',
                registry_chip: 'amber',
                doc_type: 'docx',
                doc_name: 'PaySprint_Global_Registration.docx',
                doc_size: '520 KB',
                doc_url: '<?= asset('assets/images/dummb/testing.pdf.docx') ?>',
                checks: [
                    { label: 'Work email (domain match)', status: 'valid' },
                    { label: 'Website live & active', status: 'valid' },
                    { label: 'CAC Number unreadable', status: 'alert' }
                ]
            }
        ],
        employers: window.__adminEmployersData || [],
        init() {
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
                const datePill = document.getElementById('employers-datepicker');
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
                                    window.adminToast('Employer date range applied', `${d1} to ${d2}`, null, 'info');
                                }
                            }
                        }
                    });
                }
            });
        },
        initCountUp() {
            const targets = { total: 486, roles: 1038, placed: 1312, pending: 14 };
            const duration = 800;
            const steps = 30;
            const interval = duration / steps;
            let step = 0;
            const timer = setInterval(() => {
                step++;
                const progress = step / steps;
                const ease = 1 - Math.pow(1 - progress, 3);
                this.kpi.total = Math.round(targets.total * ease);
                this.kpi.roles = Math.round(targets.roles * ease);
                this.kpi.placed = Math.round(targets.placed * ease);
                this.kpi.pending = Math.round(targets.pending * ease);
                if (step >= steps) {
                    clearInterval(timer);
                    this.kpi.total = targets.total;
                    this.kpi.roles = targets.roles;
                    this.kpi.placed = targets.placed;
                    this.kpi.pending = targets.pending;
                }
            }, interval);
        },
        approveEmployer(item) {
            const compId = item.id;
            const compName = item.company_name;
            
            // Slide out from queue
            this.dismissedQueueIds.push(compId);

            // Update corresponding record in master directory
            const dirItem = this.employers.find(e => e.name.toLowerCase().includes(compName.toLowerCase().split(' ')[0]));
            if (dirItem) {
                dirItem.is_verified = true;
                dirItem.status = 'Verified';
                dirItem.status_class = 'verified';
            }

            // Trigger toast with undo
            window.adminToast(
                compName + ' approved & verified!',
                'Verified badge is now active across platform listings',
                () => {
                    // Undo action
                    const qIdx = this.dismissedQueueIds.indexOf(compId);
                    if (qIdx > -1) this.dismissedQueueIds.splice(qIdx, 1);
                    if (dirItem) {
                        dirItem.is_verified = false;
                        dirItem.status = 'Awaiting Verification';
                        dirItem.status_class = 'pending';
                    }
                    window.adminToast('Verification undone for ' + compName, 'Returned to pending queue', null, 'info');
                },
                'success'
            );
        },
        toggleSelectAll() {
            this.selectAll = !this.selectAll;
            if (this.selectAll) {
                this.selectedRows = this.filteredEmployers.map(e => e.id);
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
            this.selectAll = (this.selectedRows.length === this.filteredEmployers.length && this.filteredEmployers.length > 0);
        },
        get filteredEmployers() {
            return this.employers.filter(e => {
                const matchesSearch = !this.searchQuery || 
                    e.name.toLowerCase().includes(this.searchQuery.toLowerCase()) || 
                    e.industry.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
                    e.location.toLowerCase().includes(this.searchQuery.toLowerCase());
                
                const matchesIndustry = !this.filterIndustry ||
                    e.industry.toLowerCase().includes(this.filterIndustry.toLowerCase());

                if (!matchesSearch || !matchesIndustry) return false;
                if (this.activeTab === 'all') return true;
                if (this.activeTab === 'active') return e.status_class === 'verified' || e.status_class === 'pending';
                if (this.activeTab === 'verified') return e.is_verified === true;
                if (this.activeTab === 'pending') return e.is_verified === false && e.status_class === 'pending';
                if (this.activeTab === 'suspended') return e.status_class === 'suspended';
                return true;
            });
        }
    };
}
</script>
<div class="admin-shell" x-data="adminEmployers()" @keydown.escape.window="if (showDocViewerModal) closeDocViewer(); else if (drawerOpen) drawerOpen = false;">

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
                    <h1 class="admin-page-title"><?= htmlspecialchars($segmentInfo[0] ?? 'Employers', ENT_QUOTES, 'UTF-8') ?></h1>
                    <p class="admin-page-subtitle"><?= htmlspecialchars($segmentInfo[1] ?? 'Review employer accounts, track company registrations, and verify CAC documents', ENT_QUOTES, 'UTF-8') ?></p>
                </div>
                <div>
                    <button type="button" class="admin-date-pill cursor-pointer" id="employers-datepicker" aria-label="Select date range">
                        <svg class="w-4 h-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span x-text="dateRangeText">Last 30 days · to 15 Oct 2026</span>
                    </button>
                </div>
            </div>

            <!-- 2. KPI 4-Column Card Grid with Count-Up, Top-Right Chips & Delta Lines -->
            <section class="admin-kpi-grid" aria-label="Employer Statistics">
                <!-- Total Employers -->
                <div class="admin-kpi-card admin-fade-in admin-fade-in-1">
                    <div class="admin-kpi-head">
                        <span class="admin-kpi-label">TOTAL EMPLOYERS</span>
                        <div class="admin-kpi-icon-chip admin-kpi-icon-amber">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                    </div>
                    <div class="admin-kpi-row">
                        <span class="admin-kpi-value" x-text="kpi.total.toLocaleString()">486</span>
                        <span class="admin-trend-badge admin-trend-primary">+19 this month</span>
                    </div>
                    <div class="admin-kpi-delta">
                        <span class="text-indigo-600 font-semibold">&uarr; 4.1%</span>
                        <span>vs last month</span>
                    </div>
                </div>

                <!-- Open Roles -->
                <div class="admin-kpi-card admin-fade-in admin-fade-in-2">
                    <div class="admin-kpi-head">
                        <span class="admin-kpi-label">OPEN ROLES</span>
                        <div class="admin-kpi-icon-chip admin-kpi-icon-blue">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="admin-kpi-row">
                        <span class="admin-kpi-value" x-text="kpi.roles.toLocaleString()">1,038</span>
                        <span class="admin-trend-badge admin-trend-primary">+62 this week</span>
                    </div>
                    <div class="admin-kpi-delta">
                        <span class="text-indigo-600 font-semibold">&uarr; 6.3%</span>
                        <span>active internship listings</span>
                    </div>
                </div>

                <!-- Placed Interns -->
                <div class="admin-kpi-card admin-fade-in admin-fade-in-3">
                    <div class="admin-kpi-head">
                        <span class="admin-kpi-label">PLACED</span>
                        <div class="admin-kpi-icon-chip admin-kpi-icon-indigo">
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
                        <span>confirmed hires</span>
                    </div>
                </div>

                <!-- Pending Verification (Consistent 14) -->
                <div class="admin-kpi-card admin-fade-in admin-fade-in-4">
                    <div class="admin-kpi-head">
                        <span class="admin-kpi-label">PENDING VERIFICATION</span>
                        <div class="admin-kpi-icon-chip admin-kpi-icon-purple">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="admin-kpi-row">
                        <span class="admin-kpi-value" x-text="kpi.pending.toLocaleString()">14</span>
                        <span class="admin-trend-badge admin-trend-attention">Oldest: 3 days</span>
                    </div>
                    <div class="admin-kpi-delta">
                        <span class="text-amber-600 font-semibold">&bull; 14 queued</span>
                        <span>CAC & corporate audits</span>
                    </div>
                </div>
            </section>

            <!-- 3. Verification Queue Action Shelf with Registry Chips and Slide-Out (Image 3 & Requirement 10) -->
            <section aria-label="Verification Queue" class="admin-fade-in admin-fade-in-2">
                <div class="flex items-center justify-between mb-3 flex-wrap gap-2">
                    <div class="flex items-center gap-2 flex-wrap">
                        <h2 class="text-sm font-bold uppercase tracking-wider text-slate-700">Awaiting CAC & Corporate Verification</h2>
                        <span class="text-xs font-semibold text-purple-700 bg-purple-50 px-2.5 py-0.5 rounded-full border border-purple-100 flex-shrink-0">
                            3 Priority Desk Actions
                        </span>
                    </div>
                    <span class="text-xs text-slate-400">Verifying adds verified badge to company profile</span>
                </div>

                <div class="admin-verification-shelf">
                    <template x-for="item in queue" :key="item.id">
                        <div class="admin-verify-card transition-all duration-300 ease-out" 
                             x-show="!dismissedQueueIds.includes(item.id)"
                             x-transition:leave="transition ease-in duration-300"
                             x-transition:leave-start="opacity-100 translate-x-0 scale-100"
                             x-transition:leave-end="opacity-0 translate-x-12 scale-95">
                            
                            <!-- Header -->
                            <div class="admin-verify-card-header">
                                <div class="admin-company-logo" :style="'background-color: ' + item.avatar_color" x-text="item.initials"></div>
                                <div class="flex-1 min-w-0">
                                    <h3 class="admin-company-title truncate" x-text="item.company_name"></h3>
                                    <p class="admin-company-sub truncate" x-text="item.industry + ' · ' + item.location"></p>
                                </div>
                            </div>

                            <!-- Document & Registry Chips -->
                            <div class="admin-verify-checks">
                                <!-- Registry Chip (Requirement 10) -->
                                <div class="admin-verify-check-pill">
                                    <span class="text-[11px] font-bold px-2 py-0.5 rounded-full"
                                          :class="{
                                              'bg-emerald-50 text-emerald-700 border border-emerald-200': item.registry_chip === 'emerald',
                                              'bg-indigo-50 text-indigo-700 border border-indigo-200': item.registry_chip === 'indigo',
                                              'bg-amber-50 text-amber-700 border border-amber-200': item.registry_chip === 'amber'
                                          }">
                                        Registry: <span x-text="item.registry_status"></span>
                                    </span>
                                </div>

                                <!-- Document Checklist -->
                                <template x-for="chk in item.checks" :key="chk.label">
                                    <div class="admin-verify-check-pill">
                                        <template x-if="chk.status === 'valid'">
                                            <div class="flex items-center gap-1.5 text-slate-700 text-xs">
                                                <svg class="w-4 h-4 text-indigo-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                                </svg>
                                                <span x-text="chk.label"></span>
                                            </div>
                                        </template>
                                        <template x-if="chk.status === 'alert'">
                                            <div class="flex items-center gap-1.5 text-amber-700 text-xs font-semibold">
                                                <svg class="w-4 h-4 text-amber-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                                </svg>
                                                <span x-text="chk.label"></span>
                                            </div>
                                        </template>
                                    </div>
                                </template>
                            </div>

                            <!-- Footer Actions -->
                            <div class="admin-verify-footer">
                                <span class="admin-submitted-time" x-text="item.submitted_time"></span>
                                <div class="admin-verify-actions">
                                    <button type="button" 
                                            class="admin-btn-verify-review cursor-pointer" 
                                            @click="openCompanyDrawer(item)">
                                        Review
                                    </button>
                                    <button type="button" 
                                            class="admin-btn-verify-approve cursor-pointer" 
                                            @click="approveEmployer(item)">
                                        Approve &rarr;
                                    </button>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </section>

            <!-- 4. Master Employer Directory Table -->
            <div class="admin-table-container admin-fade-in admin-fade-in-3">
                <!-- Filter Tabs Bar with Table Search -->
                <div class="admin-table-filter-bar">
                    <div class="admin-filter-tabs">
                        <button type="button" class="admin-tab-item" :class="{ 'is-active': activeTab === 'all' }" @click="activeTab = 'all'">
                            All 486
                        </button>
                        <button type="button" class="admin-tab-item" :class="{ 'is-active': activeTab === 'active' }" @click="activeTab = 'active'">
                            Active
                        </button>
                        <button type="button" class="admin-tab-item" :class="{ 'is-active': activeTab === 'verified' }" @click="activeTab = 'verified'">
                            Verified
                        </button>
                        <button type="button" class="admin-tab-item" :class="{ 'is-active': activeTab === 'pending' }" @click="activeTab = 'pending'">
                            <span>Awaiting Verification</span>
                            <span class="admin-tab-badge-amber">14</span>
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
                                   placeholder="Filter employers..."
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
                                    <span class="text-xs font-bold text-slate-900">Filter Employers</span>
                                    <button type="button" class="text-[11px] font-semibold text-indigo-600 hover:text-indigo-800" @click="resetFilters()">Reset</button>
                                </div>
                                <div class="space-y-3 text-xs">
                                    <div>
                                        <label class="block font-semibold text-slate-700 mb-1 text-[11.5px]">Industry</label>
                                        <select data-ic-select class="w-full h-8 px-2.5 rounded-lg border border-slate-200 text-xs text-slate-800 bg-white outline-none focus:border-indigo-600"
                                                x-model="filterIndustry"
                                                @change="showFilterPopover = false;">
                                            <option value="">All Industries</option>
                                            <option value="Fintech">Fintech</option>
                                            <option value="Software">Software & Tech</option>
                                            <option value="Design">Media & Design</option>
                                            <option value="Healthtech">Healthtech</option>
                                            <option value="Financial">Financial Services</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block font-semibold text-slate-700 mb-1 text-[11.5px]">Verification Status</label>
                                        <div class="flex items-center gap-1.5 flex-wrap">
                                            <button type="button" 
                                                    class="px-2.5 py-1 rounded-md border text-[11px] font-semibold transition-colors" 
                                                    :class="activeTab === 'verified' ? 'bg-indigo-600 text-white border-indigo-600' : 'border-slate-200 text-slate-700 hover:border-indigo-600 hover:text-indigo-600'"
                                                    @click="toggleFilterTab('verified'); showFilterPopover = false;">Verified</button>
                                            <button type="button" 
                                                    class="px-2.5 py-1 rounded-md border text-[11px] font-semibold transition-colors" 
                                                    :class="activeTab === 'pending' ? 'bg-indigo-600 text-white border-indigo-600' : 'border-slate-200 text-slate-700 hover:border-indigo-600 hover:text-indigo-600'"
                                                    @click="toggleFilterTab('pending'); showFilterPopover = false;">Pending CAC</button>
                                            <button type="button" 
                                                    class="px-2.5 py-1 rounded-md border text-[11px] font-semibold transition-colors" 
                                                    :class="activeTab === 'suspended' ? 'bg-indigo-600 text-white border-indigo-600' : 'border-slate-200 text-slate-700 hover:border-indigo-600 hover:text-indigo-600'"
                                                    @click="toggleFilterTab('suspended'); showFilterPopover = false;">Suspended</button>
                                        </div>
                                    </div>
                                    <div class="pt-2 border-t border-slate-100 flex items-center justify-end gap-2">
                                        <button type="button" class="px-3 py-1.5 rounded-lg text-xs font-semibold text-slate-600 hover:bg-slate-100" @click="showFilterPopover = false">Cancel</button>
                                        <button type="button" class="px-3 py-1.5 rounded-lg text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-700" @click="showFilterPopover = false; window.adminToast('Employer filters applied', '', null, 'success');">Apply</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Table Content (Clean Fit & Responsive Custom Scrollbar) -->
                <div class="admin-table-wrap">
                    <table class="admin-table" role="table">
                        <thead style="position: sticky; top: 0; z-index: 10; background: #FAFAFA;">
                            <tr>
                                <th class="admin-th" style="width: 40px; padding: 12px 14px;">
                                    <input type="checkbox" 
                                           class="admin-checkbox cursor-pointer"
                                           :checked="selectAll"
                                           @click="toggleSelectAll()">
                                </th>
                                <th class="admin-th">COMPANY</th>
                                <th class="admin-th">INDUSTRY</th>
                                <th class="admin-th">ROLES</th>
                                <th class="admin-th">APPLICANTS</th>
                                <th class="admin-th">HIRES</th>
                                <th class="admin-th">STATUS</th>
                                <th class="admin-th text-right" style="width: 60px;">ACTION</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template x-for="emp in filteredEmployers" :key="emp.id">
                                <tr class="admin-tr cursor-pointer" 
                                    :class="{ 'is-selected': selectedCompany && selectedCompany.company_name === emp.name && drawerOpen }"
                                    @click="selectedCompany = { company_name: emp.name, initials: emp.initials, industry: emp.industry, location: emp.location, cac_number: emp.cac_number || 'RC-849201', registry_status: emp.is_verified ? 'Verified (Active RC)' : 'Under Audit', doc_type: emp.doc_type || 'pdf', doc_name: emp.doc_name || (emp.name.replace(/\s+/g, '_') + '_CAC_Certificate.pdf'), doc_size: emp.doc_size || '1.4 MB', doc_url: emp.doc_url || '<?= asset('assets/images/dummb/testing.pdf.pdf') ?>' }; drawerOpen = true;">
                                    <!-- Checkbox Selection -->
                                    <td class="admin-td" style="padding: 14px 14px;" @click.stop>
                                        <input type="checkbox" 
                                               class="admin-checkbox cursor-pointer"
                                               :checked="selectedRows.includes(emp.id)"
                                               @click="toggleRow(emp.id)">
                                    </td>

                                    <!-- Company Identity -->
                                    <td class="admin-td">
                                        <div class="admin-user-cell">
                                            <div class="admin-company-logo" :style="'background-color: ' + emp.avatar_color" style="width: 34px; height: 34px; font-size: 11px;" x-text="emp.initials"></div>
                                            <div>
                                                <div class="flex items-center gap-1.5">
                                                    <span class="admin-user-name" x-text="emp.name"></span>
                                                    <!-- Verified Badge (Strict Rule: Invisible when unverified!) -->
                                                    <template x-if="emp.is_verified">
                                                        <svg class="w-4 h-4 text-emerald-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20" aria-label="Verified Employer">
                                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                        </svg>
                                                    </template>
                                                </div>
                                                <div class="admin-user-email" x-text="emp.location + ' · ' + emp.cac_number"></div>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Industry -->
                                    <td class="admin-td font-medium text-slate-700" x-text="emp.industry"></td>

                                    <!-- Open Roles -->
                                    <td class="admin-td font-semibold text-slate-800" x-text="emp.open_roles"></td>

                                    <!-- Applicants -->
                                    <td class="admin-td text-slate-600" x-text="emp.applicants"></td>

                                    <!-- Placed Hires -->
                                    <td class="admin-td font-bold text-emerald-700" x-text="emp.hires"></td>

                                    <!-- Status Pill with Dot -->
                                    <td class="admin-td">
                                        <span class="admin-status-pill"
                                              :class="{
                                                  'admin-status-verified': emp.status_class === 'verified',
                                                  'admin-status-pending': emp.status_class === 'pending',
                                                  'admin-status-suspended': emp.status_class === 'suspended'
                                              }">
                                            <span class="w-1.5 h-1.5 rounded-full"
                                                  :class="{
                                                      'bg-emerald-600': emp.status_class === 'verified',
                                                      'bg-amber-600': emp.status_class === 'pending',
                                                      'bg-rose-600': emp.status_class === 'suspended'
                                                  }"></span>
                                            <span x-text="emp.status"></span>
                                        </span>
                                    </td>

                                    <!-- Actions Menu (Single Global activeMenuId, Standardized Card Dropdown) -->
                                    <td class="admin-td text-right" @click.stop style="position: relative; width: 60px;">
                                        <div class="relative inline-block text-left" @click.outside="if (activeMenuId === emp.id) activeMenuId = null">
                                            <button type="button" 
                                                    @click="activeMenuId = (activeMenuId === emp.id ? null : emp.id)"
                                                    class="admin-row-action-btn"
                                                    :class="{ 'is-active': activeMenuId === emp.id }"
                                                    aria-label="Row menu">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"/>
                                                </svg>
                                            </button>
                                            <div x-show="activeMenuId === emp.id" 
                                                 x-transition:enter="transition ease-out duration-150"
                                                 x-transition:enter-start="opacity-0 scale-95"
                                                 x-transition:enter-end="opacity-100 scale-100"
                                                 x-transition:leave="transition ease-in duration-100"
                                                 x-transition:leave-start="opacity-100 scale-100"
                                                 x-transition:leave-end="opacity-0 scale-95"
                                                 class="admin-action-dropdown"
                                                 style="display: none;">
                                                <button type="button" class="admin-action-dropdown-item" @click="selectedCompany = { company_name: emp.name, initials: emp.initials, industry: emp.industry, location: emp.location, cac_number: emp.cac_number || 'RC-849201', registry_status: emp.is_verified ? 'Verified (Active RC)' : 'Under Audit', doc_type: emp.doc_type || 'pdf', doc_name: emp.doc_name || (emp.name.replace(/\s+/g, '_') + '_CAC_Certificate.pdf'), doc_size: emp.doc_size || '1.4 MB', doc_url: emp.doc_url || '<?= asset('assets/images/dummb/testing.pdf.pdf') ?>' }; drawerOpen = true; activeMenuId = null;">
                                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                    </svg>
                                                    <span>Inspect Company</span>
                                                </button>
                                                <template x-if="!emp.is_verified">
                                                    <button type="button" class="admin-action-dropdown-item" style="color: #059669;" @click="emp.is_verified = true; emp.status = 'Verified'; emp.status_class = 'verified'; window.adminToast(emp.name + ' verified!', '', null, 'success'); activeMenuId = null;">
                                                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="color: #059669;">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                                        </svg>
                                                        <span>Quick Verify</span>
                                                    </button>
                                                </template>
                                                <button type="button" class="admin-action-dropdown-item" @click="window.adminToast('Copied company portal link', '', null, 'success'); activeMenuId = null;">
                                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                                                    </svg>
                                                    <span>Copy Link</span>
                                                </button>
                                                <div class="admin-action-dropdown-divider"></div>
                                                <button type="button" class="admin-action-dropdown-item is-danger" @click="emp.status = 'Suspended'; emp.status_class = 'suspended'; window.adminToast(emp.name + ' suspended', '', null, 'warning'); activeMenuId = null;">
                                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                                    </svg>
                                                    <span>Suspend Account</span>
                                                </button>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </template>

                            <!-- Empty State -->
                            <template x-if="filteredEmployers.length === 0">
                                <tr>
                                    <td colspan="8" class="text-center py-12 text-slate-500">
                                        <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center mx-auto mb-3 text-slate-400">
                                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                            </svg>
                                        </div>
                                        <p class="font-semibold text-slate-700">No employers match this filter</p>
                                        <p class="text-xs text-slate-400 mt-1">Try searching by another company name or industry category</p>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination Footer -->
                <div class="admin-table-pagination">
                    <span>Showing 1 to 6 of 486</span>
                    <div class="admin-page-btn-group">
                        <button type="button" class="admin-page-btn">Previous</button>
                        <button type="button" class="admin-page-btn is-active">1</button>
                        <button type="button" class="admin-page-btn">2</button>
                        <button type="button" class="admin-page-btn">3</button>
                        <span class="px-2 text-slate-400">...</span>
                        <button type="button" class="admin-page-btn">61</button>
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
                    <strong class="text-white" x-text="selectedRows.length"></strong> employers selected
                </span>
                <div class="h-4 w-px bg-slate-700"></div>
                <button type="button" class="hover:text-indigo-400 transition-colors" @click="window.adminToast('Exporting ' + selectedRows.length + ' companies (CSV)', '', null, 'info')">
                    Export
                </button>
                <button type="button" class="hover:text-indigo-400 transition-colors" @click="window.adminToast('Bulk message composer queued', '', null, 'info')">
                    Email Selected
                </button>
                <button type="button" class="text-slate-400 hover:text-white transition-colors ml-2" @click="selectedRows = []; selectAll = false;">
                    Deselect all
                </button>
            </div>

            <!-- 5. Verification Document Review Slide-Over Drawer with Backdrop (Requirement 10 & 12) -->
            <!-- Floating Backdrop -->
            <div x-show="drawerOpen && selectedCompany" 
                 x-transition:enter="transition ease-out duration-250"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @click="drawerOpen = false" 
                 class="fixed inset-0 z-50 bg-[#000623]/40 backdrop-blur-sm" 
                 style="display: none;"></div>

            <!-- Slide-Over Drawer -->
            <aside class="admin-inspector-drawer" 
                   x-show="drawerOpen && selectedCompany" 
                   x-transition:enter="transition ease-out duration-300"
                   x-transition:enter-start="translate-x-full"
                   x-transition:enter-end="translate-x-0"
                   x-transition:leave="transition ease-in duration-200"
                   x-transition:leave-start="translate-x-0"
                   x-transition:leave-end="translate-x-full"
                   style="display: none;">
                
                <!-- Drawer Header -->
                <div class="admin-drawer-header">
                    <div class="admin-drawer-user-info">
                        <div class="w-10 h-10 rounded-xl bg-indigo-600 text-white font-bold flex items-center justify-center text-sm flex-shrink-0" x-text="selectedCompany?.initials"></div>
                        <div class="min-w-0">
                            <h2 class="admin-drawer-title truncate" x-text="selectedCompany?.company_name"></h2>
                            <p class="admin-drawer-sub truncate" x-text="'CAC RC: ' + (selectedCompany?.cac_number || 'RC-849201') + ' · ' + (selectedCompany?.industry || 'Technology')"></p>
                        </div>
                    </div>
                    <button type="button" class="admin-drawer-close-btn" @click="drawerOpen = false" aria-label="Close inspector">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <!-- Scrollable Drawer Body -->
                <div class="admin-drawer-body">
                    <!-- Standardized Verification Document Preview Card -->
                    <div>
                        <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block mb-2">Verified Organization Document</span>
                        <div class="admin-doc-card">
                            <div class="admin-doc-card-left">
                                <div class="admin-doc-card-icon" :class="{
                                    'text-indigo-600 bg-indigo-50 border-indigo-100': !selectedCompany?.doc_type || selectedCompany?.doc_type === 'pdf',
                                    'text-emerald-600 bg-emerald-50 border-emerald-100': selectedCompany?.doc_type === 'image',
                                    'text-blue-600 bg-blue-50 border-blue-100': selectedCompany?.doc_type === 'docx'
                                }">
                                    <template x-if="!selectedCompany?.doc_type || selectedCompany?.doc_type === 'pdf'">
                                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                    </template>
                                    <template x-if="selectedCompany?.doc_type === 'image'">
                                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </template>
                                    <template x-if="selectedCompany?.doc_type === 'docx'">
                                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                        </svg>
                                    </template>
                                </div>
                                <div class="admin-doc-card-info">
                                    <span class="admin-doc-card-name" x-text="selectedCompany?.doc_name || (selectedCompany?.company_name ? selectedCompany.company_name.replace(/\s+/g, '_') + '_Document.pdf' : 'Verification_Document.pdf')"></span>
                                    <span class="admin-doc-card-meta" x-text="(selectedCompany?.doc_type ? selectedCompany.doc_type.toUpperCase() : 'PDF') + ' · ' + (selectedCompany?.doc_size || '1.4 MB') + ' · Authenticated Document'"></span>
                                </div>
                            </div>
                            <div class="flex items-center gap-1.5 flex-shrink-0">
                                <button type="button" 
                                        class="admin-doc-card-btn cursor-pointer" 
                                        @click="openDocViewer(selectedCompany?.company_name + ' Verification Document', selectedCompany?.doc_name || (selectedCompany?.company_name ? selectedCompany.company_name.replace(/\s+/g, '_') + '_Document.pdf' : 'Verification_Document.pdf'), selectedCompany?.doc_type || 'pdf', selectedCompany?.doc_url || '<?= url('assets/images/dummb/testing.pdf.pdf') ?>', selectedCompany?.doc_size || '1.4 MB')">
                                    <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    <span>View Document</span>
                                </button>
                                <a :href="selectedCompany?.doc_url || '<?= url('assets/images/dummb/testing.pdf.pdf') ?>'" 
                                   :download="selectedCompany?.doc_name || 'Verification_Document.pdf'" 
                                   class="p-2 text-slate-400 hover:text-indigo-600 rounded-lg hover:bg-slate-100 transition-colors cursor-pointer" 
                                  
                                   aria-label="Download Document"
                                   @click="downloadDoc(selectedCompany)">
                                    <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Verification Signals (Rule K Compliant Key-Value List) -->
                    <div>
                        <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block mb-2">Corporate Verification Audit</span>
                        <div class="admin-key-value-list">
                            <div class="admin-kv-row">
                                <div class="admin-kv-label">
                                    <svg class="w-3.5 h-3.5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                    <span>Work Domain Match</span>
                                </div>
                                <span class="admin-kv-value text-emerald-700 font-semibold flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Verified Domain
                                </span>
                            </div>
                            <div class="admin-kv-row">
                                <div class="admin-kv-label">
                                    <svg class="w-3.5 h-3.5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                    <span>Registry API Status</span>
                                </div>
                                <span class="admin-kv-value text-emerald-700 font-semibold" x-text="selectedCompany?.registry_status || 'Verified (Active RC)'"></span>
                            </div>
                            <div class="admin-kv-row">
                                <div class="admin-kv-label">
                                    <svg class="w-3.5 h-3.5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16"/>
                                    </svg>
                                    <span>Tax Identification (TIN)</span>
                                </div>
                                <span class="admin-kv-value text-slate-700">23940192-0001 (FIRS Checked)</span>
                            </div>
                            <div class="admin-kv-row">
                                <div class="admin-kv-label">
                                    <svg class="w-3.5 h-3.5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    <span>Authorized Representative</span>
                                </div>
                                <span class="admin-kv-value text-slate-800 font-medium">HR & Talent Operations</span>
                            </div>
                            <div class="admin-kv-row">
                                <div class="admin-kv-label">
                                    <svg class="w-3.5 h-3.5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    <span>Head Office</span>
                                </div>
                                <span class="admin-kv-value text-slate-700" x-text="selectedCompany?.location || 'Lagos, Nigeria'"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Jenny AI Corporate Risk Assessment -->
                    <div class="admin-drawer-ai-card">
                        <div class="admin-drawer-ai-head">
                            <img src="<?= asset('assets/images/jenny_ai/jenny_icon.png') ?>" alt="Jenny AI" class="w-4 h-4 rounded-full object-cover">
                            <span>Jenny AI Corporate Risk Check</span>
                        </div>
                        <p class="admin-drawer-ai-text">
                            Low risk score (0.02). Corporate domain matches incorporation registry record and digital seal is cryptographically valid. Recommended for immediate verification.
                        </p>
                    </div>
                </div>

                <!-- Sticky Drawer Footer Actions -->
                <div class="admin-drawer-actions">
                    <button type="button" class="admin-btn-dark-outline flex-1 cursor-pointer" @click="drawerOpen = false; window.adminToast('Rejection notice sent', 'Reason: Incomplete incorporation seal', null, 'warning');">
                        Reject & Request
                    </button>
                    <button type="button" class="admin-btn-indigo flex-1 cursor-pointer" @click="drawerOpen = false; approveEmployer(selectedCompany);">
                        Approve & Verify
                    </button>
                </div>
            </aside>
        </main>
    </div>

    <!-- Reusable Universal Multi-Format Document Viewer Modal (PDF, Image, DOCX) -->
    <?php include dirname(__DIR__, 2) . '/shared/components/admin-doc-modal.php'; ?>
</div>
