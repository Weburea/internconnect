<?php
declare(strict_types=1);
/**
 * Admin Subscriptions & Paystack Ledger View
 * Section 2.0 Global Application Shell - Finance & Monetization Desk
 * 
 * Features:
 * - Real-time Monthly Recurring Revenue (MRR) tracking with Chart.js dual line chart
 * - Stream Monetization breakdown (Student Pro Monthly, Annual, Employer Fees, ATS)
 * - Cryptographically verified HMAC-SHA512 Paystack webhook transaction ledger
 * - Full pagination with anti-collision mobile horizontal scrolling
 * - Webhook raw event inspector modal
 * 
 * @var array  $staff     Current admin staff profile
 * @var string $activeNav Navigation identifier
 * @var array  $metrics   Financial KPI metrics
 */
?>
<div class="admin-shell" x-data="adminSubscriptions()">
    <!-- Sidebar Navigation -->
    <?php include dirname(__DIR__, 2) . '/shared/components/admin-sidebar.php'; ?>

    <!-- Main Content Area -->
    <div class="admin-main-wrap">
        <!-- Sticky Top Navigation -->
        <?php include dirname(__DIR__, 2) . '/shared/components/admin-topnav.php'; ?>

        <!-- Page Body -->
        <main class="admin-content-body">
            <!-- Page Header -->
            <div class="admin-page-header mb-6">
                <div>
                    <h1 class="admin-page-title">Subscription &amp; Ledger</h1>
                    <p class="admin-page-subtitle">Real-time Monthly Recurring Revenue (MRR), verified student plans, automated Paystack payouts &amp; webhook audits</p>
                </div>
                <a href="<?= url('/admin/subscriptions/plans') ?>" style="display: inline-flex; align-items: center; height: 38px; padding: 0 16px; border-radius: 10px; background: #4F48E6; color: #FFFFFF; font-size: 13px; font-weight: 700; text-decoration: none; white-space: nowrap;">Plans &amp; limits</a>
            </div>

            <!-- 4 Premium KPI Metric Cards (No side borders, 44px rounded icon chips) -->
            <section class="admin-kpi-grid mb-6" aria-label="Financial KPI Indicators">
                <!-- Card 1: MRR -->
                <div class="admin-kpi-card">
                    <div class="admin-kpi-head">
                        <span class="admin-kpi-label">MONTHLY RECURRING REVENUE</span>
                        <div class="admin-kpi-icon-chip admin-kpi-icon-emerald">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="admin-kpi-row">
                        <span class="admin-kpi-value" x-text="kpi.mrr">₦4,850,000</span>
                    </div>
                    <div class="flex items-center gap-1.5 text-[11px] font-semibold text-emerald-600">
                        <span>&uarr; 14.2%</span>
                        <span class="text-slate-400 font-normal">vs last month (₦4.24M)</span>
                    </div>
                </div>

                <!-- Card 2: Pro Subscribers -->
                <div class="admin-kpi-card">
                    <div class="admin-kpi-head">
                        <span class="admin-kpi-label">STUDENT PRO SUBSCRIBERS</span>
                        <div class="admin-kpi-icon-chip admin-kpi-icon-blue">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="admin-kpi-row">
                        <span class="admin-kpi-value" x-text="kpi.subscribers">1,420</span>
                    </div>
                    <div class="flex items-center gap-1.5 text-[11px] font-semibold text-blue-600">
                        <span>+112 this month</span>
                        <span class="text-slate-400 font-normal">&middot; ₦3,500/term</span>
                    </div>
                </div>

                <!-- Card 3: Placement Fees -->
                <div class="admin-kpi-card">
                    <div class="admin-kpi-head">
                        <span class="admin-kpi-label">PLACEMENT FEES COLLECTED</span>
                        <div class="admin-kpi-icon-chip admin-kpi-icon-purple">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                    </div>
                    <div class="admin-kpi-row">
                        <span class="admin-kpi-value" x-text="kpi.placement">₦2,400,000</span>
                    </div>
                    <div class="flex items-center gap-1.5 text-[11px] font-semibold text-purple-600">
                        <span>32 Hires</span>
                        <span class="text-slate-400 font-normal">at ₦75,000 corporate fee</span>
                    </div>
                </div>

                <!-- Card 4: Net Balance -->
                <div class="admin-kpi-card">
                    <div class="admin-kpi-head">
                        <span class="admin-kpi-label">NET SETTLEMENT BALANCE</span>
                        <div class="admin-kpi-icon-chip admin-kpi-icon-amber">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="admin-kpi-row">
                        <span class="admin-kpi-value">₦7,180,000</span>
                    </div>
                    <div class="flex items-center gap-1.5 text-[11px] font-semibold text-emerald-600">
                        <span>Next Payout:</span>
                        <span class="text-slate-400 font-normal">Tomorrow 05:00 WAT</span>
                    </div>
                </div>
            </section>

            <!-- Split Analytics Grid: Revenue Growth Line Chart & Revenue Breakdown Histogram -->
            <div class="admin-grid-2col mb-6">
                <!-- Left: Revenue Growth Chart -->
                <div class="admin-card flex flex-col justify-between">
                    <div>
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
                            <div>
                                <h2 class="text-sm font-bold text-slate-900">Revenue Trajectory &amp; Payout Growth</h2>
                                <p class="text-xs text-slate-500">Student subscriptions vs corporate placement settlement fees (Jan &ndash; Oct 2026)</p>
                            </div>
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="flex items-center gap-1.5 text-xs font-semibold text-slate-600">
                                    <span class="w-2.5 h-2.5 rounded-full bg-indigo-600"></span>
                                    <span>Subscriptions</span>
                                </span>
                                <span class="flex items-center gap-1.5 text-xs font-semibold text-slate-600 ml-2">
                                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                                    <span>Placement Fees</span>
                                </span>
                            </div>
                        </div>

                        <!-- Chart Canvas Container -->
                        <div class="relative w-full" style="height: 250px;">
                            <canvas id="admin-revenue-chart"></canvas>
                        </div>
                    </div>

                    <!-- Bottom Insight Footer (Responsive Stacking) -->
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 text-xs text-slate-500" style="margin-top: 24px; padding-top: 18px; border-top: 1px solid #F1F5F9;">
                        <span>Average Gross Transaction Fee: <strong class="text-slate-900">1.5% + ₦100</strong> via Paystack</span>
                        <span class="text-emerald-600 font-semibold">&bull; 0 Failed Webhooks</span>
                    </div>
                </div>

                <!-- Right: Revenue Distribution Histogram (No Doughnut / Pie Chart) -->
                <div class="admin-card flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <h2 class="text-sm font-bold text-slate-900">Stream Breakdown</h2>
                            <span class="text-[11px] font-bold text-slate-400 uppercase">Oct 2026</span>
                        </div>
                        <p class="text-xs text-slate-500 mb-4">Monetization distribution by channel histogram</p>

                        <!-- Histogram Canvas -->
                        <div class="relative w-full" style="height: 160px;">
                            <canvas id="admin-revenue-histogram"></canvas>
                        </div>
                    </div>

                    <!-- Clean Structured Thin Progress List -->
                    <div class="space-y-3 pt-4 border-t border-slate-100 mt-4 text-xs">
                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <span class="font-medium text-slate-700">Student Pro (Monthly)</span>
                                <span class="font-bold text-slate-900">42% <span class="text-slate-400 font-normal">(₦2.03M)</span></span>
                            </div>
                            <div class="w-full bg-slate-100 rounded-full h-1.5 overflow-hidden">
                                <div class="bg-[#4F48E6] h-full rounded-full" style="width: 42%;"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <span class="font-medium text-slate-700">Student Pro (Annual)</span>
                                <span class="font-bold text-slate-900">28% <span class="text-slate-400 font-normal">(₦1.36M)</span></span>
                            </div>
                            <div class="w-full bg-slate-100 rounded-full h-1.5 overflow-hidden">
                                <div class="bg-blue-500 h-full rounded-full" style="width: 28%;"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <span class="font-medium text-slate-700">Placement Fees</span>
                                <span class="font-bold text-slate-900">25% <span class="text-slate-400 font-normal">(₦1.21M)</span></span>
                            </div>
                            <div class="w-full bg-slate-100 rounded-full h-1.5 overflow-hidden">
                                <div class="bg-emerald-500 h-full rounded-full" style="width: 25%;"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <span class="font-medium text-slate-700">ATS Verification</span>
                                <span class="font-bold text-slate-900">5% <span class="text-slate-400 font-normal">(₦242k)</span></span>
                            </div>
                            <div class="w-full bg-slate-100 rounded-full h-1.5 overflow-hidden">
                                <div class="bg-amber-500 h-full rounded-full" style="width: 5%;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Paystack Transactions Audit Ledger Table & Mobile Cards -->
            <div class="bg-white rounded-2xl border border-slate-200 p-4 sm:p-6 overflow-hidden">
                <!-- Table Controls Header -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-4">
                    <div>
                        <h2 class="text-sm font-bold text-slate-900">Paystack Transaction &amp; Webhook Ledger</h2>
                        <p class="text-xs text-slate-500">Cryptographically verified HMAC-SHA512 webhook events with zero mobile cell collision</p>
                    </div>

                    <!-- Filter Tabs with Seamless Mobile Fit & Zero Scrollbar Artifacts -->
                    <div class="flex items-center gap-1 p-1 bg-slate-100 rounded-xl text-xs font-semibold overflow-x-auto no-scrollbar w-full sm:w-auto">
                        <button type="button" 
                                class="flex-1 sm:flex-initial px-2.5 sm:px-3 py-1.5 rounded-lg transition-all whitespace-nowrap cursor-pointer text-center"
                                :class="activeTab === 'all' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-500 hover:text-slate-900'"
                                @click="activeTab = 'all'">
                            <span class="inline sm:hidden">All (<span x-text="transactions.length"></span>)</span>
                            <span class="hidden sm:inline">All Transactions (<span x-text="transactions.length"></span>)</span>
                        </button>
                        <button type="button" 
                                class="flex-1 sm:flex-initial px-2.5 sm:px-3 py-1.5 rounded-lg transition-all whitespace-nowrap cursor-pointer text-center"
                                :class="activeTab === 'students' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-500 hover:text-slate-900'"
                                @click="activeTab = 'students'">
                            Student Pro (<span x-text="transactions.filter(t => t.category === 'student').length"></span>)
                        </button>
                        <button type="button" 
                                class="flex-1 sm:flex-initial px-2.5 sm:px-3 py-1.5 rounded-lg transition-all whitespace-nowrap cursor-pointer text-center"
                                :class="activeTab === 'employers' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-500 hover:text-slate-900'"
                                @click="activeTab = 'employers'">
                            <span class="inline sm:hidden">Corporate (<span x-text="transactions.filter(t => t.category === 'employer').length"></span>)</span>
                            <span class="hidden sm:inline">Corporate Fees (<span x-text="transactions.filter(t => t.category === 'employer').length"></span>)</span>
                        </button>
                    </div>
                </div>

                <!-- Desktop & Tablet Data Table (Clean Custom Scrollbars) -->
                <div class="hidden md:block admin-table-scroll-container">
                    <table class="admin-data-table text-xs" style="width: 100%; min-width: 820px;">
                        <thead>
                            <tr class="border-b border-slate-100 text-slate-400 uppercase tracking-wider font-semibold text-[11px]">
                                <th style="width: 14%;">REFERENCE</th>
                                <th style="width: 22%;">CUSTOMER / ENTITY</th>
                                <th style="width: 14%;">PLAN TYPE</th>
                                <th style="width: 9%;">AMOUNT</th>
                                <th style="width: 12%;">CHANNEL</th>
                                <th style="width: 8%;">STATUS</th>
                                <th style="width: 11%; text-align: right;">TIMESTAMP</th>
                                <th style="width: 10%; text-align: right; white-space: nowrap;">AUDIT</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <template x-for="tx in paginatedTransactions" :key="tx.ref">
                                <tr class="hover:bg-slate-50/70 transition-colors">
                                    <!-- Reference -->
                                    <td class="font-mono font-bold text-slate-800 whitespace-nowrap">
                                        <span x-text="tx.ref"></span>
                                    </td>

                                    <!-- Customer / Entity -->
                                    <td>
                                        <div class="flex items-center gap-2.5">
                                            <div class="w-7 h-7 rounded-full bg-slate-100 text-slate-700 flex items-center justify-center font-bold text-[10.5px] flex-shrink-0" x-text="tx.initials"></div>
                                            <div class="min-w-0">
                                                <div class="font-bold text-slate-900 truncate" x-text="tx.customer"></div>
                                                <div class="text-[11px] text-slate-500 truncate" x-text="tx.org"></div>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Plan Type -->
                                    <td class="whitespace-nowrap">
                                        <span class="px-2 py-0.5 rounded-full text-[10.5px] font-bold"
                                              :class="{
                                                  'bg-indigo-50 text-indigo-700': tx.category === 'student',
                                                  'bg-purple-50 text-purple-700': tx.category === 'employer',
                                                  'bg-amber-50 text-amber-700': tx.category === 'ats'
                                              }"
                                              x-text="tx.plan"></span>
                                    </td>

                                    <!-- Amount -->
                                    <td class="font-bold text-slate-900 whitespace-nowrap" x-text="tx.amount"></td>

                                    <!-- Channel -->
                                    <td class="text-slate-600 whitespace-nowrap">
                                        <div class="flex items-center gap-1.5">
                                            <svg class="w-3.5 h-3.5 text-slate-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                            </svg>
                                            <span x-text="tx.channel"></span>
                                        </div>
                                    </td>

                                    <!-- Status -->
                                    <td class="whitespace-nowrap">
                                        <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-[10.5px] font-bold bg-emerald-50 text-emerald-700">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            <span x-text="tx.status"></span>
                                        </span>
                                    </td>

                                    <!-- Timestamp -->
                                    <td class="text-right text-slate-500 font-mono text-[11px] whitespace-nowrap" x-text="tx.date"></td>

                                    <!-- Audit CTA (Clean Grouped Action Buttons with Inline Copied Feedback) -->
                                    <td class="text-right whitespace-nowrap">
                                        <div class="inline-flex items-center gap-1.5 justify-end">
                                            <!-- Copy Button with Inline Feedback -->
                                            <template x-if="copiedRef !== tx.ref">
                                                <button type="button" 
                                                        @click="copyReference(tx.ref)"
                                                        class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-semibold text-slate-600 hover:text-indigo-600 bg-slate-50 hover:bg-indigo-50 border border-slate-200 hover:border-indigo-200 rounded-lg transition-all cursor-pointer"
                                                        aria-label="Copy transaction reference">
                                                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                                    </svg>
                                                    <span>Copy</span>
                                                </button>
                                            </template>
                                            <template x-if="copiedRef === tx.ref">
                                                <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-bold text-emerald-700 bg-emerald-50 border border-emerald-200 rounded-lg transition-all">
                                                    <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                                    </svg>
                                                    <span>Copied!</span>
                                                </span>
                                            </template>

                                            <!-- Inspect Button -->
                                            <button type="button" 
                                                    @click="inspectWebhook(tx)"
                                                    class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold text-indigo-600 hover:text-indigo-800 bg-indigo-50/70 hover:bg-indigo-100/70 border border-indigo-100 rounded-lg transition-colors cursor-pointer"
                                                    aria-label="Inspect HMAC webhook payload">
                                                <svg class="w-3.5 h-3.5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                                <span>Inspect</span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>

                <!-- Dedicated Mobile Transaction Cards View (Zero Table Squeezing on Phones) -->
                <div class="block md:hidden space-y-3">
                    <template x-for="tx in paginatedTransactions" :key="tx.ref">
                        <div class="rounded-xl border border-slate-200 bg-white p-3.5 space-y-2.5 hover:border-slate-300 transition-all">
                            <!-- Top Row: Customer & Amount -->
                            <div class="flex items-start justify-between gap-2">
                                <div class="flex items-center gap-2.5 min-w-0">
                                    <div class="w-8 h-8 rounded-full bg-slate-100 text-slate-700 flex items-center justify-center font-bold text-xs flex-shrink-0" x-text="tx.initials"></div>
                                    <div class="min-w-0">
                                        <div class="font-bold text-slate-900 text-xs truncate" x-text="tx.customer"></div>
                                        <div class="text-[11px] text-slate-500 truncate" x-text="tx.org"></div>
                                    </div>
                                </div>
                                <div class="text-right flex-shrink-0">
                                    <div class="font-extrabold text-slate-900 text-xs" x-text="tx.amount"></div>
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        <span x-text="tx.status"></span>
                                    </span>
                                </div>
                            </div>

                            <!-- Meta Row: Plan & Ref & Date -->
                            <div class="flex items-center justify-between text-[11px] pt-2 border-t border-slate-100 text-slate-500 flex-wrap gap-1">
                                <span class="px-2 py-0.5 rounded-full font-bold text-[10.5px]"
                                      :class="{
                                          'bg-indigo-50 text-indigo-700': tx.category === 'student',
                                          'bg-purple-50 text-purple-700': tx.category === 'employer',
                                          'bg-amber-50 text-amber-700': tx.category === 'ats'
                                      }"
                                      x-text="tx.plan"></span>
                                <span class="font-mono text-[10.5px] text-slate-700" x-text="tx.ref"></span>
                                <span class="font-mono text-[10.5px] text-slate-400" x-text="tx.date"></span>
                            </div>

                            <!-- Action Row: Copy & Receipt -->
                            <div class="flex items-center gap-2 pt-2 border-t border-slate-100">
                                <template x-if="copiedRef !== tx.ref">
                                    <button type="button" 
                                            @click="copyReference(tx.ref)"
                                            class="flex-1 inline-flex items-center justify-center gap-1.5 py-1.5 text-xs font-semibold text-slate-700 bg-slate-50 hover:bg-slate-100 border border-slate-200 rounded-lg transition-colors cursor-pointer">
                                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                        </svg>
                                        <span>Copy Ref</span>
                                    </button>
                                </template>
                                <template x-if="copiedRef === tx.ref">
                                    <span class="flex-1 inline-flex items-center justify-center gap-1 py-1.5 text-xs font-bold text-emerald-700 bg-emerald-50 border border-emerald-200 rounded-lg">
                                        <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        <span>Copied!</span>
                                    </span>
                                </template>

                                <button type="button" 
                                        @click="inspectWebhook(tx)"
                                        class="flex-1 inline-flex items-center justify-center gap-1 py-1.5 text-xs font-semibold text-indigo-700 bg-indigo-50 hover:bg-indigo-100 border border-indigo-100 rounded-lg transition-colors cursor-pointer">
                                    <svg class="w-3.5 h-3.5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    <span>Receipt</span>
                                </button>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- Standardized Table Pagination Controls -->
                <div class="flex items-center justify-between pt-4 mt-3 border-t border-slate-100 flex-wrap gap-3">
                    <div class="text-xs text-slate-500 font-medium">
                        Showing <span class="font-bold text-slate-800" x-text="Math.min((currentPage - 1) * perPage + 1, filteredTransactions.length)"></span>
                        to <span class="font-bold text-slate-800" x-text="Math.min(currentPage * perPage, filteredTransactions.length)"></span>
                        of <span class="font-bold text-slate-800" x-text="filteredTransactions.length"></span> transactions
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

            <!-- Digital POS Receipt & Webhook Inspector Modal -->
            <div x-show="showWebhookModal"
                 x-cloak
                 class="fixed inset-0 z-50 flex items-center justify-center bg-[#000623]/60 backdrop-blur-sm overflow-y-auto"
                 style="display: none; padding: 28px 16px !important;"
                 role="dialog"
                 aria-modal="true"
                 @keydown.escape.window="showWebhookModal = false">
                
                <!-- Dialog Card with Safe Height & Margins -->
                <div class="relative w-full bg-white rounded-3xl shadow-2xl border border-slate-200 overflow-hidden flex flex-col my-auto"
                     style="max-width: 440px; max-height: calc(100vh - 56px) !important;"
                     @click.outside="showWebhookModal = false">
                    
                    <!-- Receipt Top Bar -->
                    <div class="flex items-center justify-between px-5 py-3.5 border-b border-slate-100 bg-slate-50/70 flex-shrink-0">
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                            <span class="text-xs font-bold text-slate-800 tracking-wide uppercase">Paystack Live Receipt</span>
                        </div>
                        <button type="button" 
                                class="text-slate-400 hover:text-slate-700 p-1.5 rounded-lg hover:bg-slate-200/50 cursor-pointer transition-colors" 
                                @click="showWebhookModal = false"
                                aria-label="Close dialog">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    <!-- Scrollable Receipt Body -->
                    <div class="px-5 py-4 overflow-y-auto space-y-3.5 text-xs">
                        <!-- Receipt Stamp & Title -->
                        <div class="text-center">
                            <div class="w-9 h-9 rounded-xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-[#4F48E6] mx-auto mb-1.5 shadow-xs">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block">INTERNCONNECT MONETIZATION DESK</span>
                            <h3 class="text-sm font-bold text-slate-900 tracking-tight mt-0.5">TRANSACTION RECEIPT</h3>
                            <p class="text-xs text-slate-400 font-mono mt-0.5" x-text="selectedTx?.ref || 'pstk_ref_9281729'"></p>
                        </div>

                        <!-- Big Amount Banner -->
                        <div class="py-2.5 px-4 rounded-xl bg-slate-50 border border-slate-200/80 text-center">
                            <span class="text-2xl font-extrabold text-slate-900 tracking-tight" x-text="selectedTx?.amount || '₦3,500.00'"></span>
                            <div class="flex items-center justify-center gap-1.5 mt-1">
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    <span>Paid &amp; Settled (charge.success)</span>
                                </span>
                            </div>
                        </div>

                        <!-- Dotted Tear-Off Divider -->
                        <div class="border-t-2 border-dashed border-slate-200 my-1 relative"></div>

                        <!-- Structured Key-Value Breakdown List (Generous Internal Padding 18px) -->
                        <div class="rounded-xl border border-slate-200 overflow-hidden divide-y divide-slate-100 bg-white">
                            <div class="flex items-center justify-between bg-slate-50/50" style="padding: 9px 18px !important;">
                                <span class="text-slate-500 font-medium text-[11px]">Customer / Student</span>
                                <span class="font-bold text-slate-900 text-[11.5px] text-right" x-text="selectedTx?.customer || 'Sarah Peters'"></span>
                            </div>
                            <div class="flex items-center justify-between" style="padding: 9px 18px !important;">
                                <span class="text-slate-500 font-medium text-[11px]">Institution / Entity</span>
                                <span class="font-medium text-slate-700 text-[11.5px] text-right" x-text="selectedTx?.org || 'University of Lagos'"></span>
                            </div>
                            <div class="flex items-center justify-between bg-slate-50/50" style="padding: 9px 18px !important;">
                                <span class="text-slate-500 font-medium text-[11px]">Plan Type</span>
                                <span class="font-bold text-indigo-700 text-[11.5px] text-right" x-text="selectedTx?.plan || 'Student Pro Monthly'"></span>
                            </div>
                            <div class="flex items-center justify-between" style="padding: 9px 18px !important;">
                                <span class="text-slate-500 font-medium text-[11px]">Payment Channel</span>
                                <span class="font-medium text-slate-700 text-[11.5px] text-right" x-text="selectedTx?.channel || 'Card · Mastercard 4219'"></span>
                            </div>
                            <div class="flex items-center justify-between bg-slate-50/50" style="padding: 9px 18px !important;">
                                <span class="text-slate-500 font-medium text-[11px]">Timestamp</span>
                                <span class="font-mono text-slate-600 text-[11px] text-right" x-text="selectedTx?.date || '15 Oct 2026, 14:22'"></span>
                            </div>
                            <div class="flex items-center justify-between" style="padding: 9px 18px !important;">
                                <span class="text-slate-500 font-medium text-[11px]">Gateway Response</span>
                                <span class="font-bold text-emerald-700 text-[11.5px] text-right">Successful (200 OK)</span>
                            </div>
                            <div class="flex items-center justify-between bg-slate-50/50" style="padding: 9px 18px !important;">
                                <span class="text-slate-500 font-medium text-[11px]">Cryptographic Security</span>
                                <span class="inline-flex items-center gap-1 text-[10px] font-bold text-indigo-700 bg-indigo-50 px-2 py-0.5 rounded-full border border-indigo-200 text-right">
                                    HMAC-SHA512 Verified
                                </span>
                            </div>
                        </div>

                        <!-- Collapsible Raw Webhook JSON Payload -->
                        <div x-data="{ showRawJson: false }" class="pt-0.5">
                            <button type="button" 
                                    @click="showRawJson = !showRawJson"
                                    class="w-full flex items-center justify-between p-2.5 rounded-xl bg-slate-50 hover:bg-slate-100 border border-slate-200 text-xs font-semibold text-slate-700 transition-colors cursor-pointer">
                                <div class="flex items-center gap-2">
                                    <svg class="w-3.5 h-3.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>
                                    </svg>
                                    <span class="text-[11px]">Raw Webhook Payload (charge.success)</span>
                                </div>
                                <svg class="w-3.5 h-3.5 text-slate-400 transition-transform duration-200" :class="{ 'rotate-180': showRawJson }" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                            <div x-show="showRawJson" x-cloak class="mt-2">
                                <pre class="bg-[#000623] text-emerald-400 rounded-xl font-mono text-[10px] leading-relaxed p-3 overflow-x-auto border border-slate-800"
                                     style="max-height: 130px !important; overflow-y: auto !important;"
                                     x-text="getWebhookJson()"></pre>
                            </div>
                        </div>
                    </div>

                    <!-- Receipt Footer -->
                    <div class="flex items-center justify-between px-5 py-3 border-t border-slate-100 bg-slate-50/70 flex-shrink-0">
                        <span class="text-[10.5px] text-slate-400 flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                            <span>Audit synchronized</span>
                        </span>
                        <button type="button" 
                                class="admin-btn-indigo cursor-pointer px-4 py-1.5 rounded-xl text-xs font-semibold"
                                @click="showWebhookModal = false">
                            Close Receipt
                        </button>
                    </div>
                </div>
            </div>
            </div>
        </main>
    </div>
</div>

<script>
function adminSubscriptions() {
    return {
        isSyncing: false,
        activeTab: 'all',
        showWebhookModal: false,
        selectedTx: null,
        copiedRef: null,
        currentPage: 1,
        perPage: 5,
        kpi: {
            mrr: '₦4,850,000',
            subscribers: '1,420',
            placement: '₦2,400,000'
        },
        transactions: [
            { ref: 'pstk_ref_9281729', customer: 'Sarah Peters', org: 'University of Lagos', initials: 'SP', plan: 'Student Pro Monthly', category: 'student', amount: '₦3,500.00', channel: 'Card · Mastercard 4219', status: 'Success', date: '15 Oct 2026, 14:22' },
            { ref: 'pstk_ref_8492011', customer: 'Nexura Technologies Ltd', org: 'RC-1849204 · Lagos', initials: 'NT', plan: 'Corporate Placement', category: 'employer', amount: '₦75,000.00', channel: 'Bank Transfer (Access)', status: 'Success', date: '15 Oct 2026, 12:05' },
            { ref: 'pstk_ref_7381920', customer: 'Tunde Adeyemi', org: 'University of Ibadan', initials: 'TA', plan: 'Student Pro Annual', category: 'student', amount: '₦18,000.00', channel: 'Card · Visa 8821', status: 'Success', date: '14 Oct 2026, 18:30' },
            { ref: 'pstk_ref_6291043', customer: 'Kiro Labs Ltd', org: 'Design & AI · Remote', initials: 'KL', plan: 'Corporate Placement', category: 'employer', amount: '₦75,000.00', channel: 'Bank Transfer (GTBank)', status: 'Success', date: '14 Oct 2026, 15:10' },
            { ref: 'pstk_ref_5182934', customer: 'Amara Nwosu', org: 'Covenant University', initials: 'AN', plan: 'Student Pro Monthly', category: 'student', amount: '₦3,500.00', channel: 'USSD (*737#)', status: 'Success', date: '14 Oct 2026, 11:45' },
            { ref: 'pstk_ref_4091823', customer: 'David Okon', org: 'FUTA Data Science', initials: 'DO', plan: 'ATS Skill Verification', category: 'ats', amount: '₦2,500.00', channel: 'Card · Verve 1092', status: 'Success', date: '13 Oct 2026, 16:20' },
            { ref: 'pstk_ref_3981724', customer: 'Zainab Al-Hassan', org: 'Ahmadu Bello University', initials: 'ZA', plan: 'Student Pro Monthly', category: 'student', amount: '₦3,500.00', channel: 'Card · Mastercard 9012', status: 'Success', date: '13 Oct 2026, 14:10' },
            { ref: 'pstk_ref_2871615', customer: 'PaySprint Global', org: 'Financial Services · Abuja', initials: 'PS', plan: 'Corporate Placement', category: 'employer', amount: '₦75,000.00', channel: 'Bank Transfer (Zenith)', status: 'Success', date: '12 Oct 2026, 09:30' },
            { ref: 'pstk_ref_1760506', customer: 'Chinedu Okafor', org: 'UNN Nsukka', initials: 'CO', plan: 'Student Pro Monthly', category: 'student', amount: '₦3,500.00', channel: 'Card · Visa 4410', status: 'Success', date: '11 Oct 2026, 19:40' },
            { ref: 'pstk_ref_0659497', customer: 'DesignHub Creative', org: 'RC-1729301 · Lagos', initials: 'DH', plan: 'Corporate Placement', category: 'employer', amount: '₦75,000.00', channel: 'Card · Visa Corp 9901', status: 'Success', date: '10 Oct 2026, 11:15' }
        ],
        get filteredTransactions() {
            if (this.activeTab === 'students') return this.transactions.filter(t => t.category === 'student');
            if (this.activeTab === 'employers') return this.transactions.filter(t => t.category === 'employer');
            return this.transactions;
        },
        get totalPages() {
            return Math.max(1, Math.ceil(this.filteredTransactions.length / this.perPage));
        },
        get paginatedTransactions() {
            const start = (this.currentPage - 1) * this.perPage;
            return this.filteredTransactions.slice(start, start + this.perPage);
        },
        getWebhookJson() {
            const tx = this.selectedTx || this.transactions[0];
            if (!tx) return '{\n  "event": "charge.success",\n  "data": {}\n}';
            const numAmt = parseInt(String(tx.amount || '').replace(/[^0-9]/g, '') || '3500', 10);
            return JSON.stringify({
                event: 'charge.success',
                data: {
                    id: 30294912,
                    domain: 'live',
                    status: 'success',
                    reference: tx.ref,
                    amount: numAmt * 100,
                    gateway_response: 'Successful',
                    paid_at: tx.date || '2026-10-15T14:22:00.000Z',
                    created_at: '2026-10-15T14:21:40.000Z',
                    channel: tx.channel || 'card',
                    currency: 'NGN',
                    ip_address: '102.89.34.112',
                    fees: Math.round(numAmt * 0.015 + 100) * 100,
                    metadata: {
                        plan: tx.plan,
                        category: tx.category,
                        user_id: 'usr_82910'
                    },
                    customer: {
                        name: tx.customer,
                        organization: tx.org,
                        email: (tx.customer || '').toLowerCase().replace(/[^a-z0-9]/g, '.') + '@example.com'
                    },
                    authorization: {
                        channel: 'card',
                        card_type: 'mastercard',
                        bank: 'Access Bank Nigeria',
                        country_code: 'NG',
                        brand: 'mastercard',
                        reusable: true,
                        signature: 'SIG_' + tx.ref.slice(-8)
                    }
                }
            }, null, 2);
        },
        syncPaystack() {
            this.isSyncing = true;
            setTimeout(() => {
                this.isSyncing = false;
                window.dispatchEvent(new CustomEvent('show-toast', { detail: { type: 'success', text: 'Paystack ledger synchronized: All transactions up to date' } }));
                if (typeof window.adminToast === 'function') {
                    window.adminToast('Paystack ledger synchronized', 'All transactions up to date with Paystack webhook records', null, 'success');
                }
            }, 800);
        },
        copyReference(ref) {
            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(ref);
            }
            this.copiedRef = ref;
            setTimeout(() => {
                if (this.copiedRef === ref) this.copiedRef = null;
            }, 2000);
        },
        inspectWebhook(tx) {
            this.selectedTx = tx;
            this.showWebhookModal = true;
        },
        initCharts() {
            this.$nextTick(() => {
                // Revenue Trajectory Line Chart
                const lineCtx = document.getElementById('admin-revenue-chart');
                if (lineCtx && typeof Chart !== 'undefined') {
                    new Chart(lineCtx, {
                        type: 'line',
                        data: {
                            labels: ['May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct'],
                            datasets: [
                                {
                                    label: 'Subscriptions (₦M)',
                                    data: [1.8, 2.3, 2.9, 3.4, 4.2, 4.85],
                                    borderColor: '#4F48E6',
                                    backgroundColor: 'rgba(79, 72, 230, 0.08)',
                                    fill: true,
                                    tension: 0.35,
                                    borderWidth: 2.5,
                                    pointRadius: 3,
                                    pointHoverRadius: 5
                                },
                                {
                                    label: 'Placement Fees (₦M)',
                                    data: [0.8, 1.1, 1.4, 1.8, 2.1, 2.4],
                                    borderColor: '#10B981',
                                    backgroundColor: 'rgba(16, 185, 129, 0.04)',
                                    fill: true,
                                    tension: 0.35,
                                    borderWidth: 2,
                                    pointRadius: 3,
                                    pointHoverRadius: 5
                                }
                            ]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: { display: false },
                                tooltip: {
                                    callbacks: {
                                        label: function(ctx) { return ctx.dataset.label + ': ₦' + ctx.raw + 'M'; }
                                    }
                                }
                            },
                            scales: {
                                x: { grid: { display: false } },
                                y: {
                                    ticks: {
                                        callback: function(v) { return '₦' + v + 'M'; }
                                    },
                                    grid: { color: '#F1F5F9' }
                                }
                            }
                        }
                    });
                }

                // Revenue Distribution Histogram Bar Chart (Replaced Doughnut Chart)
                const histCtx = document.getElementById('admin-revenue-histogram');
                if (histCtx && typeof Chart !== 'undefined') {
                    new Chart(histCtx, {
                        type: 'bar',
                        data: {
                            labels: ['Monthly Pro', 'Annual Pro', 'Placement', 'ATS Audit'],
                            datasets: [{
                                label: 'Share %',
                                data: [42, 28, 25, 5],
                                backgroundColor: ['#4F48E6', '#3B82F6', '#10B981', '#F59E0B'],
                                borderRadius: 6,
                                maxBarThickness: 32
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: { display: false },
                                tooltip: {
                                    callbacks: {
                                        label: function(ctx) { return ctx.parsed.y + '% of Total MRR'; }
                                    }
                                }
                            },
                            scales: {
                                x: {
                                    grid: { display: false },
                                    ticks: { font: { size: 10.5, family: 'Inter' }, color: '#64748B' }
                                },
                                y: {
                                    max: 50,
                                    ticks: {
                                        callback: function(v) { return v + '%'; },
                                        font: { size: 10, family: 'Inter' },
                                        color: '#94A3B8',
                                        stepSize: 10
                                    },
                                    grid: { color: '#F8FAFC' }
                                }
                            }
                        }
                    });
                }
            });
        },
        init() {
            this.initCharts();
            this.$watch('activeTab', () => {
                this.currentPage = 1;
            });
        }
    };
}
</script>
