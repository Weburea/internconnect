<?php
declare(strict_types=1);
/**
 * Admin Jenny AI Operations Console View
 * Complies with Section 6.0 of docs/10-admin-system.md
 * 
 * Features:
 * - Nightly Match Throughput & Engine Health Gauges
 * - Interactive Chart.js Batch Scoring & Match Acceptance History
 * - Working Flagged Discrepancy Queue with instant Suppress / Approve state actions
 * - Gemini Model Hyperparameter Sliders with Save Configuration feedback
 * - Premium 44px KPI icon chips with clean 1px neutral borders (Zero side borders)
 * 
 * @var array  $staff     Staff profile data
 * @var string $activeNav Navigation identifier
 * @var array  $metrics   AI engine health metrics
 */
?>
<div class="admin-shell" x-data="adminAiOps()">
    <!-- Sidebar Navigation -->
    <?php include dirname(__DIR__, 2) . '/shared/components/admin-sidebar.php'; ?>

    <!-- Main Content Area -->
    <div class="admin-main-wrap">
        <!-- Sticky Top Navigation -->
        <?php include dirname(__DIR__, 2) . '/shared/components/admin-topnav.php'; ?>

        <!-- Page Body -->
        <main class="admin-content-body">
            <!-- Page Header with Official Jenny AI Brand Avatar -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-5 mb-6">
                <div class="flex items-center gap-3.5 min-w-0">
                    <div class="w-12 h-12 rounded-2xl bg-indigo-50 border border-indigo-100 flex items-center justify-center p-1.5 flex-shrink-0 shadow-xs">
                        <img src="<?= asset('assets/images/jenny_ai/jenny_icon.png') ?>" alt="Jenny AI" class="w-full h-full object-contain">
                    </div>
                    <div class="min-w-0">
                        <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight leading-tight">Jenny AI Operations Console</h1>
                        <p class="text-xs sm:text-sm text-slate-500 mt-1">Oversee Gemini 1.5 Pro algorithmic scoring, audit flagged match anomalies, and monitor token consumption</p>
                    </div>
                </div>
                <!-- Right-Aligned Clean Live Pill & Scoring Batch Button -->
                <div class="flex items-center gap-2.5 sm:gap-3 flex-wrap md:ml-auto flex-shrink-0">
                    <span class="inline-flex items-center gap-2 px-3 py-1.5 sm:px-3.5 sm:py-2 rounded-xl bg-indigo-50 text-indigo-700 text-xs font-bold border border-indigo-200" style="white-space: nowrap !important; flex-shrink: 0 !important;">
                        <span class="w-2 h-2 rounded-full bg-indigo-600 animate-pulse"></span>
                        <span>Gemini 1.5 Pro &middot; 99.98% Live</span>
                    </span>
                    <button type="button" 
                            @click="triggerBatchRun()"
                            :disabled="isRunningBatch"
                            class="admin-btn-indigo text-xs px-3.5 py-1.5 sm:px-4 sm:py-2 cursor-pointer flex items-center gap-2 whitespace-nowrap shadow-xs"
                            style="white-space: nowrap !important; flex-shrink: 0 !important;">
                        <svg class="w-3.5 h-3.5 flex-shrink-0" :class="{ 'animate-spin': isRunningBatch }" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        <span x-text="isRunningBatch ? 'Processing...' : 'Run Scoring Batch'"></span>
                    </button>
                </div>
            </div>

            <!-- 4 Premium KPI Metric Cards (No side borders, 44px rounded icon chips) -->
            <section class="admin-kpi-grid mb-6" aria-label="AI Engine Health Metrics">
                <!-- Card 1: Model Uptime -->
                <div class="admin-kpi-card">
                    <div class="admin-kpi-head">
                        <span class="admin-kpi-label">MODEL UPTIME &amp; HEALTH</span>
                        <div class="admin-kpi-icon-chip admin-kpi-icon-purple">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="admin-kpi-row">
                        <span class="admin-kpi-value">99.98%</span>
                    </div>
                    <div class="flex items-center gap-1.5 text-[11px] font-semibold text-emerald-600">
                        <span>Latency: 240ms avg</span>
                        <span class="text-slate-400 font-normal">&middot; Gemini Pro</span>
                    </div>
                </div>

                <!-- Card 2: Nightly Scoring -->
                <div class="admin-kpi-card">
                    <div class="admin-kpi-head">
                        <span class="admin-kpi-label">NIGHTLY SCORING RUN</span>
                        <div class="admin-kpi-icon-chip admin-kpi-icon-blue">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                        </div>
                    </div>
                    <div class="admin-kpi-row">
                        <span class="admin-kpi-value">1,904</span>
                    </div>
                    <div class="flex items-center gap-1.5 text-[11px] font-semibold text-blue-600">
                        <span>Candidate-Role Pairs</span>
                        <span class="text-slate-400 font-normal">at 06:00 WAT</span>
                    </div>
                </div>

                <!-- Card 3: Match Acceptance Rate -->
                <div class="admin-kpi-card">
                    <div class="admin-kpi-head">
                        <span class="admin-kpi-label">MATCH ACCEPTANCE RATE</span>
                        <div class="admin-kpi-icon-chip admin-kpi-icon-emerald">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="admin-kpi-row">
                        <span class="admin-kpi-value">71.4%</span>
                    </div>
                    <div class="flex items-center gap-1.5 text-[11px] font-semibold text-emerald-600">
                        <span>&uarr; 3.2% vs target</span>
                        <span class="text-slate-400 font-normal">&middot; 65% benchmark</span>
                    </div>
                </div>

                <!-- Card 4: Flagged Discrepancies -->
                <div class="admin-kpi-card">
                    <div class="admin-kpi-head">
                        <span class="admin-kpi-label">FLAGGED FOR AUDIT</span>
                        <div class="admin-kpi-icon-chip admin-kpi-icon-amber">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="admin-kpi-row">
                        <span class="admin-kpi-value" x-text="flaggedItems.length">6</span>
                    </div>
                    <div class="flex items-center gap-1.5 text-[11px] font-semibold text-amber-600">
                        <span>Score &lt;65% anomalies</span>
                        <span class="text-slate-400 font-normal">pending desk action</span>
                    </div>
                </div>
            </section>

            <!-- Split Analytics Grid: Nightly Scoring Throughput Chart & Hyperparameter Configuration -->
            <div class="admin-grid-2col mb-6">
                <!-- Left: Throughput Line Chart -->
                <div class="admin-card flex flex-col justify-between">
                    <div>
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
                            <div>
                                <h2 class="text-sm font-bold text-slate-900">Nightly Matching Throughput &amp; Precision</h2>
                                <p class="text-xs text-slate-500">Pairs processed per night vs candidate offer conversion rate</p>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="flex items-center gap-1.5 text-xs font-semibold text-slate-600">
                                    <span class="w-2.5 h-2.5 rounded-full bg-purple-600"></span>
                                    <span>Pairs Scored</span>
                                </span>
                                <span class="flex items-center gap-1.5 text-xs font-semibold text-slate-600 ml-2">
                                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                                    <span>Acceptance %</span>
                                </span>
                            </div>
                        </div>

                        <!-- Canvas -->
                        <div class="relative w-full" style="height: 250px;">
                            <canvas id="admin-ai-chart"></canvas>
                        </div>
                    </div>

                    <!-- Bottom Insight Footer (Responsive Stacking) -->
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 text-xs text-slate-500" style="margin-top: 24px; padding-top: 18px; border-top: 1px solid #F1F5F9;">
                        <span>Gemini 1.5 Pro Flash Fallback: <strong class="text-slate-900">Ready</strong> if quota &gt; 80%</span>
                        <span class="text-purple-600 font-semibold">&bull; Vector Match Precision: 0.92</span>
                    </div>
                </div>

                <!-- Right: Hyperparameters & Configuration Card -->
                <div class="admin-card flex flex-col justify-between p-5 sm:p-7">
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <h2 class="text-sm font-bold text-slate-900">Model Tuning Parameters</h2>
                            <span class="text-[11px] font-bold text-purple-700 bg-purple-50 border border-purple-100 px-2 py-0.5 rounded-full">Gemini 1.5</span>
                        </div>
                        <p class="text-xs text-slate-500 mb-5">Fine-tune nightly algorithmic matching behavior</p>

                        <!-- Premium Custom Range Sliders -->
                        <div class="space-y-5 text-xs">
                            <!-- Slider 1: Temperature -->
                            <div>
                                <div class="flex items-center justify-between mb-1">
                                    <div>
                                        <span class="font-semibold text-slate-800">Temperature</span>
                                        <span class="text-[11px] text-slate-400 ml-1">(Creativity vs Determinism)</span>
                                    </div>
                                    <span class="font-mono font-bold text-indigo-700 bg-indigo-50 border border-indigo-100 px-2 py-0.5 rounded-md" x-text="params.temperature">0.20</span>
                                </div>
                                <input type="range" min="0" max="1" step="0.05" x-model="params.temperature" class="admin-range-slider">
                                <div class="flex justify-between text-[10px] text-slate-400 mt-1">
                                    <span>0.0 (Strict / Deterministic)</span>
                                    <span>1.0 (Creative)</span>
                                </div>
                            </div>

                            <!-- Slider 2: Top-P Sampling -->
                            <div>
                                <div class="flex items-center justify-between mb-1">
                                    <div>
                                        <span class="font-semibold text-slate-800">Top-P Sampling</span>
                                        <span class="text-[11px] text-slate-400 ml-1">(Nucleus Probability Cutoff)</span>
                                    </div>
                                    <span class="font-mono font-bold text-indigo-700 bg-indigo-50 border border-indigo-100 px-2 py-0.5 rounded-md" x-text="params.topP">0.95</span>
                                </div>
                                <input type="range" min="0.5" max="1" step="0.01" x-model="params.topP" class="admin-range-slider">
                                <div class="flex justify-between text-[10px] text-slate-400 mt-1">
                                    <span>0.50 (Focused)</span>
                                    <span>1.00 (Exhaustive)</span>
                                </div>
                            </div>

                            <!-- Slider 3: Flag Threshold -->
                            <div>
                                <div class="flex items-center justify-between mb-1">
                                    <div>
                                        <span class="font-semibold text-slate-800">Flag Threshold</span>
                                        <span class="text-[11px] text-slate-400 ml-1">(Anomaly Cutoff % Match)</span>
                                    </div>
                                    <span class="font-mono font-bold text-indigo-700 bg-indigo-50 border border-indigo-100 px-2 py-0.5 rounded-md" x-text="params.flagThreshold + '%'">65%</span>
                                </div>
                                <input type="range" min="50" max="80" step="1" x-model="params.flagThreshold" class="admin-range-slider">
                                <div class="flex justify-between text-[10px] text-slate-400 mt-1">
                                    <span>50% (Permissive)</span>
                                    <span>80% (Strict Flagging)</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="pt-5 border-t border-slate-100 mt-5">
                        <button type="button" 
                                @click="saveParams()"
                                class="admin-btn-indigo w-full text-xs py-2.5 cursor-pointer text-center justify-center whitespace-nowrap shadow-xs">
                            Save Tuning Hyperparameters
                        </button>
                    </div>
                </div>
            </div>

            <!-- Flagged Match Discrepancy Queue Shelf (Searchable & Paginated) -->
            <div class="bg-white rounded-2xl border border-slate-200 admin-shelf-card">
                <!-- Shelf Header & Search Controls -->
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-6 pb-4 border-b border-slate-100">
                    <div>
                        <div class="flex items-center gap-2.5 flex-wrap">
                            <h2 class="text-base font-bold text-slate-900">Flagged Match Discrepancy Queue</h2>
                            <span class="inline-flex items-center justify-center px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-amber-50 text-amber-700 border border-amber-200"
                                  style="white-space: nowrap !important; flex-shrink: 0 !important;"
                                  x-text="filteredItems.length + ' Flagged'"></span>
                        </div>
                        <p class="text-xs text-slate-500 mt-1">Candidate-internship matches flagged for algorithmic score anomalies or domain misalignment</p>
                    </div>

                    <!-- Filter Controls (Search + Severity Tabs) -->
                    <div class="flex items-center gap-2.5 sm:gap-3 flex-wrap">
                        <!-- Search Box with Fluid Mobile Width -->
                        <div class="relative w-full sm:w-auto sm:min-w-[240px] flex-1">
                            <svg class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <input type="text" 
                                   x-model="searchQuery" 
                                   placeholder="Filter candidate, role, company..."
                                   style="padding-left: 36px !important; padding-right: 14px !important; height: 38px;"
                                   class="w-full bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:outline-none focus:border-indigo-600 focus:bg-white transition-all">
                        </div>

                        <!-- Severity Filter Tabs with No Scrollbar -->
                        <div class="flex items-center gap-1 p-1 bg-slate-100 rounded-xl text-xs font-semibold overflow-x-auto no-scrollbar w-full sm:w-auto">
                            <button type="button" 
                                    class="flex-1 sm:flex-initial px-3 py-1.5 rounded-lg transition-all cursor-pointer whitespace-nowrap text-center"
                                    :class="activeSeverity === 'all' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-500 hover:text-slate-900'"
                                    @click="activeSeverity = 'all'; currentPage = 1">
                                All
                            </button>
                            <button type="button" 
                                    class="flex-1 sm:flex-initial px-3 py-1.5 rounded-lg transition-all cursor-pointer whitespace-nowrap text-center"
                                    :class="activeSeverity === 'critical' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-500 hover:text-slate-900'"
                                    @click="activeSeverity = 'critical'; currentPage = 1">
                                Critical (&lt;60%)
                            </button>
                            <button type="button" 
                                    class="flex-1 sm:flex-initial px-3 py-1.5 rounded-lg transition-all cursor-pointer whitespace-nowrap text-center"
                                    :class="activeSeverity === 'moderate' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-500 hover:text-slate-900'"
                                    @click="activeSeverity = 'moderate'; currentPage = 1">
                                Moderate (60-65%)
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Flagged Items List -->
                <div class="space-y-3">
                    <template x-if="paginatedItems.length === 0">
                        <div class="py-12 text-center">
                            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 mx-auto flex items-center justify-center mb-2">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <h3 class="text-sm font-bold text-slate-900">No matching discrepancy items</h3>
                            <p class="text-xs text-slate-500 mt-0.5">All flagged candidates in this view have been resolved.</p>
                        </div>
                    </template>

                    <template x-for="item in paginatedItems" :key="item.id">
                        <div class="rounded-2xl border border-slate-200 hover:border-slate-300 transition-all flex flex-col md:flex-row md:items-center justify-between gap-3.5 sm:gap-4 p-4 sm:p-5"
                             style="margin-bottom: 12px; background: #FAFBFC;">
                            <div class="flex items-start sm:items-center gap-3 sm:gap-4 min-w-0 flex-1">
                                <!-- Jenny AI Avatar Chip -->
                                <div class="w-10 h-10 rounded-xl bg-indigo-50/70 border border-indigo-100 flex items-center justify-center flex-shrink-0 p-1.5 shadow-xs">
                                    <img src="<?= asset('assets/images/jenny_ai/jenny_icon.png') ?>" alt="Jenny AI" class="w-full h-full object-contain">
                                </div>
                                
                                <div class="min-w-0 flex-1 w-full">
                                    <!-- Candidate, Role, Company & Score -->
                                    <div class="flex items-center gap-2 flex-wrap min-w-0">
                                        <span class="text-sm font-bold text-slate-900 whitespace-nowrap" x-text="item.candidate"></span>
                                        <span class="text-slate-300 text-xs font-semibold">&rarr;</span>
                                        <span class="text-xs font-medium text-slate-600 whitespace-nowrap" x-text="item.role"></span>
                                        <span class="text-slate-300 text-xs">&bull;</span>
                                        <span class="text-xs font-semibold text-indigo-600 whitespace-nowrap" x-text="item.company"></span>
                                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-slate-100 text-slate-600 border border-slate-200 whitespace-nowrap"
                                              x-text="'Score: ' + item.score + '%'"></span>
                                    </div>
                                    <!-- Flagged Reason with Subtle Indicator -->
                                    <div class="flex items-start sm:items-center gap-1.5 mt-1.5 min-w-0 w-full">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-400 flex-shrink-0 mt-1 sm:mt-0"></span>
                                        <p class="text-[11.5px] text-slate-500 font-normal leading-relaxed min-w-0 break-words" x-text="item.reason"></p>
                                    </div>
                                </div>
                            </div>

                            <!-- Single-Line Working Action Buttons Responsive Row -->
                            <div class="flex items-center gap-2 flex-shrink-0 w-full md:w-auto pt-2.5 md:pt-0 border-t border-slate-200/60 md:border-0">
                                <button type="button" 
                                        @click="suppressMatch(item)"
                                        class="flex-1 md:flex-initial inline-flex items-center justify-center cursor-pointer whitespace-nowrap transition-colors text-xs font-semibold px-4 py-2 rounded-xl border border-slate-200 text-slate-700 bg-white hover:bg-slate-50 shadow-xs"
                                        style="white-space: nowrap !important; flex-shrink: 0 !important;">
                                    Suppress Match
                                </button>
                                <button type="button" 
                                        @click="approveMatch(item)"
                                        class="flex-1 md:flex-initial inline-flex items-center justify-center gap-1.5 cursor-pointer whitespace-nowrap transition-all shadow-xs text-xs font-semibold px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white"
                                        style="white-space: nowrap !important; flex-shrink: 0 !important;">
                                    <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    <span>Confirm Match</span>
                                </button>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- Pagination Footer -->
                <div class="flex items-center justify-between pt-5 mt-4 border-t border-slate-100 flex-wrap gap-3">
                    <div class="text-xs text-slate-500 font-medium">
                        Showing <span class="font-bold text-slate-800" x-text="Math.min((currentPage - 1) * perPage + 1, filteredItems.length)"></span>
                        to <span class="font-bold text-slate-800" x-text="Math.min(currentPage * perPage, filteredItems.length)"></span>
                        of <span class="font-bold text-slate-800" x-text="filteredItems.length"></span> flagged pairs
                    </div>

                    <div class="flex items-center gap-1.5">
                        <button type="button"
                                @click="if (currentPage > 1) currentPage--"
                                :disabled="currentPage <= 1"
                                class="px-3 py-1.5 text-xs font-semibold rounded-lg border border-slate-200 transition-colors"
                                :class="currentPage <= 1 ? 'opacity-40 cursor-not-allowed text-slate-400 bg-slate-50' : 'text-slate-700 hover:bg-slate-50 cursor-pointer'">
                            &larr; Prev
                        </button>
                        
                        <template x-for="p in totalPages" :key="p">
                            <button type="button"
                                    @click="currentPage = p"
                                    class="w-7 h-7 flex items-center justify-center text-xs font-bold rounded-lg transition-colors cursor-pointer"
                                    :class="currentPage === p ? 'bg-indigo-600 text-white shadow-xs' : 'text-slate-600 hover:bg-slate-100'"
                                    x-text="p"></button>
                        </template>

                        <button type="button"
                                @click="if (currentPage < totalPages) currentPage++"
                                :disabled="currentPage >= totalPages"
                                class="px-3 py-1.5 text-xs font-semibold rounded-lg border border-slate-200 transition-colors"
                                :class="currentPage >= totalPages ? 'opacity-40 cursor-not-allowed text-slate-400 bg-slate-50' : 'text-slate-700 hover:bg-slate-50 cursor-pointer'">
                            Next &rarr;
                        </button>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<script>
function adminAiOps() {
    return {
        isRunningBatch: false,
        searchQuery: '',
        activeSeverity: 'all',
        currentPage: 1,
        perPage: 4,
        params: {
            temperature: 0.20,
            topP: 0.95,
            flagThreshold: 65
        },
        flaggedItems: [
            { id: 1, candidate: 'Chinedu Okafor', role: 'Backend Developer', company: 'PaySprint Global', score: 62, reason: 'Flagged: Student GPA (3.42) slightly below stated preference, but strong GitHub REST API portfolio.' },
            { id: 2, candidate: 'Zainab Al-Hassan', role: 'UI/UX Design Intern', company: 'Kiro Labs', score: 59, reason: 'Flagged: Target department preference differs from role title (Product vs Design).' },
            { id: 3, candidate: 'Michael Adeyemi', role: 'Cloud DevOps Intern', company: 'Nexura Technologies', score: 64, reason: 'Flagged: Experience level listed as Entry / Student while employer role requested intermediate AWS knowledge.' },
            { id: 4, candidate: 'Blessing Chukwuma', role: 'Data Analytics Intern', company: 'Moniepoint', score: 61, reason: 'Flagged: Course background in Mathematics instead of Computer Science, but passed Python assessment with 96%.' },
            { id: 5, candidate: 'Ibrahim Danladi', role: 'Frontend React Intern', company: 'DesignHub Creative', score: 63, reason: 'Flagged: Portfolio contains Vue.js projects while role is strictly React.' },
            { id: 6, candidate: 'Ngozi Eze', role: 'Product Marketing Intern', company: 'PaySprint Global', score: 58, reason: 'Flagged: Target intake city (Abuja) differs from corporate office location (Lagos).' }
        ],
        get filteredItems() {
            let list = this.flaggedItems;
            if (this.activeSeverity === 'critical') {
                list = list.filter(i => i.score < 60);
            } else if (this.activeSeverity === 'moderate') {
                list = list.filter(i => i.score >= 60 && i.score <= 65);
            }
            if (this.searchQuery.trim()) {
                const q = this.searchQuery.toLowerCase();
                list = list.filter(i => i.candidate.toLowerCase().includes(q) || i.role.toLowerCase().includes(q) || i.company.toLowerCase().includes(q));
            }
            return list;
        },
        get totalPages() {
            return Math.max(1, Math.ceil(this.filteredItems.length / this.perPage));
        },
        get paginatedItems() {
            const start = (this.currentPage - 1) * this.perPage;
            return this.filteredItems.slice(start, start + this.perPage);
        },
        triggerBatchRun() {
            this.isRunningBatch = true;
            setTimeout(() => {
                this.isRunningBatch = false;
                window.dispatchEvent(new CustomEvent('show-toast', { detail: { type: 'success', text: 'Nightly scoring batch executed: 1,904 pairs scored with Gemini 1.5 Pro' } }));
                if (typeof window.adminToast === 'function') {
                    window.adminToast('Nightly scoring batch executed', '1,904 candidate-internship pairs scored with Gemini 1.5 Pro', null, 'success');
                }
            }, 900);
        },
        suppressMatch(item) {
            this.flaggedItems = this.flaggedItems.filter(i => i.id !== item.id);
            if (typeof window.adminToast === 'function') {
                window.adminToast('Match suppressed', item.candidate + ' will not be presented to ' + item.company, null, 'info');
            }
        },
        approveMatch(item) {
            this.flaggedItems = this.flaggedItems.filter(i => i.id !== item.id);
            if (typeof window.adminToast === 'function') {
                window.adminToast('Match confirmed', item.candidate + ' approved and pushed to employer pool', null, 'success');
            }
        },
        saveParams() {
            window.dispatchEvent(new CustomEvent('show-toast', { detail: { type: 'success', text: 'Model hyperparameters updated successfully' } }));
            if (typeof window.adminToast === 'function') {
                window.adminToast('Model hyperparameters saved', 'Updated Temperature to ' + this.params.temperature + ', Threshold to ' + this.params.flagThreshold + '%', null, 'success');
            }
        },
        initCharts() {
            this.$nextTick(() => {
                const ctx = document.getElementById('admin-ai-chart');
                if (ctx && typeof Chart !== 'undefined') {
                    new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                            datasets: [
                                {
                                    label: 'Pairs Scored',
                                    data: [1420, 1680, 1890, 1750, 2100, 1540, 1904],
                                    borderColor: '#9333EA',
                                    backgroundColor: 'rgba(147, 51, 234, 0.08)',
                                    fill: true,
                                    tension: 0.35,
                                    borderWidth: 2.5,
                                    pointRadius: 3
                                },
                                {
                                    label: 'Match Acceptance (%)',
                                    data: [67, 69, 72, 70, 74, 69, 71.4],
                                    borderColor: '#10B981',
                                    backgroundColor: 'rgba(16, 185, 129, 0.04)',
                                    fill: true,
                                    tension: 0.35,
                                    borderWidth: 2,
                                    pointRadius: 3
                                }
                            ]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: { display: false }
                            },
                            scales: {
                                x: { grid: { display: false } },
                                y: { grid: { color: '#F1F5F9' } }
                            }
                        }
                    });
                }
            });
        },
        init() {
            this.initCharts();
        }
    };
}
</script>
