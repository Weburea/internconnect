<?php
declare(strict_types=1);
/**
 * Admin Overview / Mission Control Dashboard View
 * Polished to match modern dashboard standards (Reference Images 1-4)
 * 
 * @var array  $staff          Current admin profile
 * @var string $activeNav      Active navigation slug
 * @var array  $metrics        KPI counters and trends
 * @var array  $attentionQueue Action items requiring admin review
 * @var array  $funnel         Recruitment conversion funnel data
 * @var array  $recentActivity Platform audit log feed
 * @var array  $todaySnapshot  Activity counters for the current day
 * @var array  $finance        Revenue summary, trend and streams
 * @var bool   $canViewFinance Whether this staff role may see revenue
 * @var array  $topPerformers  Leading employers, universities and interns
 * @var array  $marketplace    Supply and demand indicators
 * @var array  $systemHealth   Status of platform services
 */
?>
<script>
function adminFinanceChart(chartData) {
    return {
        init() {
            const canvas = this.$refs.financeCanvas;
            if (!canvas || typeof Chart === 'undefined') return;

            new Chart(canvas, {
                type: 'bar',
                data: {
                    labels: chartData.labels,
                    datasets: [{
                        label: 'Monthly revenue',
                        data: chartData.values,
                        backgroundColor: '#4F48E6',
                        borderRadius: 6,
                        maxBarThickness: 32,
                    }],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#000623',
                            titleFont: { size: 12, family: 'Inter', weight: '700' },
                            bodyFont: { size: 12, family: 'Inter' },
                            padding: 10,
                            cornerRadius: 8,
                            displayColors: false,
                            callbacks: {
                                label: (context) => '₦' + context.parsed.y.toLocaleString(),
                            },
                        },
                    },
                    scales: {
                        x: { grid: { display: false }, border: { display: false }, ticks: { font: { size: 11, family: 'Inter' }, color: '#94A3B8' } },
                        y: {
                            grid: { color: '#F1F5F9' },
                            border: { display: false },
                            ticks: {
                                font: { size: 11, family: 'Inter' },
                                color: '#94A3B8',
                                maxTicksLimit: 4,
                                callback: (value) => '₦' + (value / 1000000) + 'M',
                            },
                        },
                    },
                },
            });
        },
    };
}

// Chart.js objects stay outside Alpine state because reactive proxies break them
let overviewChart = null;
let overviewSeries = {
    labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4', 'Week 5'],
    apps: [1120, 1340, 1580, 1690, 1480],
    placements: [180, 240, 310, 342, 240],
};

const overviewCrosshair = {
    id: 'overviewCrosshair',
    afterDatasetsDraw(chart) {
        const active = chart.tooltip ? chart.tooltip.getActiveElements() : [];
        if (!active.length) return;

        const x = active[0].element.x;
        const { top, bottom } = chart.chartArea;
        const ctx = chart.ctx;
        ctx.save();
        ctx.setLineDash([4, 4]);
        ctx.strokeStyle = '#CBD5E1';
        ctx.lineWidth = 1;
        ctx.beginPath();
        ctx.moveTo(x, top);
        ctx.lineTo(x, bottom);
        ctx.stroke();
        ctx.restore();
    },
};

function buildOverviewChartConfig(canvas, mode, series) {
    const ctx = canvas.getContext('2d');
    const tooltip = {
        backgroundColor: '#000623',
        titleFont: { size: 12, family: 'Inter', weight: '700' },
        bodyFont: { size: 12, family: 'Inter' },
        padding: 10,
        cornerRadius: 8,
        boxWidth: 8,
        boxHeight: 8,
        usePointStyle: true,
        callbacks: {
            label: (context) => ' ' + context.dataset.label + ': ' + context.parsed.y.toLocaleString(),
        },
    };
    const options = {
        responsive: true,
        maintainAspectRatio: false,
        interaction: { mode: 'index', intersect: false },
        plugins: { legend: { display: false }, tooltip },
        scales: {
            x: { grid: { display: false }, border: { display: false }, ticks: { font: { size: 11, family: 'Inter' }, color: '#94A3B8' } },
            y: { beginAtZero: true, grid: { color: '#F1F5F9' }, border: { display: false }, ticks: { font: { size: 11, family: 'Inter' }, color: '#94A3B8', maxTicksLimit: 5 } },
        },
    };

    if (mode === 'bars') {
        const lastIndex = series.labels.length - 1;
        const tone = (solid, soft) => (context) => {
            const active = context.chart.$activeIndex ?? lastIndex;
            return context.dataIndex === active ? solid : soft;
        };

        options.onHover = (event, elements, chart) => {
            const index = elements.length ? elements[0].index : null;
            if (chart.$activeIndex !== index) {
                chart.$activeIndex = index;
                chart.update('none');
            }
        };

        return {
            type: 'bar',
            data: {
                labels: series.labels,
                datasets: [
                    { label: 'Applications', data: series.apps, backgroundColor: tone('#4F48E6', '#E0E7FF'), hoverBackgroundColor: tone('#4F48E6', '#E0E7FF'), borderRadius: 6, maxBarThickness: 32, categoryPercentage: 0.62, barPercentage: 0.86 },
                    { label: 'Placements', data: series.placements, backgroundColor: tone('#10B981', '#D1FAE5'), hoverBackgroundColor: tone('#10B981', '#D1FAE5'), borderRadius: 6, maxBarThickness: 32, categoryPercentage: 0.62, barPercentage: 0.86 },
                ],
            },
            options,
        };
    }

    if (mode === 'trend') {
        const tile = document.createElement('canvas');
        tile.width = 8;
        tile.height = 8;
        const tileCtx = tile.getContext('2d');
        tileCtx.strokeStyle = 'rgba(79, 72, 230, 0.18)';
        tileCtx.lineWidth = 1.5;
        tileCtx.beginPath();
        tileCtx.moveTo(0, 8);
        tileCtx.lineTo(8, 0);
        tileCtx.stroke();

        const hover = (color) => ({ pointRadius: 0, pointHoverRadius: 5, pointHoverBackgroundColor: color, pointHoverBorderColor: '#FFFFFF', pointHoverBorderWidth: 2 });

        return {
            type: 'line',
            data: {
                labels: series.labels,
                datasets: [
                    { label: 'Applications', data: series.apps, borderColor: '#4F48E6', borderWidth: 2.5, borderDash: [6, 5], tension: 0.45, fill: true, backgroundColor: ctx.createPattern(tile, 'repeat'), ...hover('#4F48E6') },
                    { label: 'Placements', data: series.placements, borderColor: '#10B981', borderWidth: 2, tension: 0.45, fill: false, ...hover('#10B981') },
                ],
            },
            options,
            plugins: [overviewCrosshair],
        };
    }

    const gradient = ctx.createLinearGradient(0, 0, 0, 260);
    gradient.addColorStop(0, 'rgba(79, 72, 230, 0.28)');
    gradient.addColorStop(1, 'rgba(79, 72, 230, 0.00)');
    const point = (color) => ({ pointBackgroundColor: color, pointBorderColor: '#FFFFFF', pointBorderWidth: 2, pointRadius: 4, pointHoverRadius: 6 });

    return {
        type: 'line',
        data: {
            labels: series.labels,
            datasets: [
                { label: 'Applications', data: series.apps, borderColor: '#4F48E6', backgroundColor: gradient, fill: true, tension: 0.35, borderWidth: 2.5, ...point('#4F48E6') },
                { label: 'Placements', data: series.placements, borderColor: '#10B981', fill: false, tension: 0.35, borderWidth: 2.5, ...point('#10B981') },
            ],
        },
        options,
    };
}

function adminOverview() {
    return {
        period: 'Month',
        dateRangeText: 'Last 30 days · to 15 Oct 2026',
        chartMode: 'area',
        hiddenSeries: [false, false],
        kpi: {
            interns: 12480,
            employers: 486,
            aiRate: 71,
            learners: 4920
        },
        trends: {
            interns: '+312 this week',
            internsDelta: '↑ 2.6% vs last month',
            employers: '+19 this month',
            employersDelta: '↑ 4.1% vs last month',
            aiDelta: '↑ 3.2% vs last month',
            learners: '+8% this month'
        },
        setPeriod(newPeriod) {
            if (this.period === newPeriod) return;
            this.period = newPeriod;

            if (newPeriod === 'Week') {
                this.dateRangeText = 'This Week · 10 Oct to 15 Oct 2026';
                this.kpi = { interns: 312, employers: 19, aiRate: 74, learners: 185 };
                this.trends = {
                    interns: '+84 today',
                    internsDelta: '↑ 5.2% vs last week',
                    employers: '+4 this week',
                    employersDelta: '↑ 2.0% vs last week',
                    aiDelta: '↑ 4.1% vs last week',
                    learners: '+12% this week'
                };
            } else if (newPeriod === 'Quarter') {
                this.dateRangeText = 'Q3 2026 · 1 Jul to 30 Sep 2026';
                this.kpi = { interns: 36400, employers: 1240, aiRate: 69, learners: 14200 };
                this.trends = {
                    interns: '+1,820 this quarter',
                    internsDelta: '↑ 14.2% vs last quarter',
                    employers: '+142 this quarter',
                    employersDelta: '↑ 9.8% vs last quarter',
                    aiDelta: '↑ 1.8% vs last quarter',
                    learners: '+24% this quarter'
                };
            } else if (newPeriod === 'Year') {
                this.dateRangeText = 'Annual 2026 · YTD Oct 2026';
                this.kpi = { interns: 142000, employers: 4820, aiRate: 72, learners: 58000 };
                this.trends = {
                    interns: '+34,200 YTD',
                    internsDelta: '↑ 28.5% YoY',
                    employers: '+820 YTD',
                    employersDelta: '↑ 18.2% YoY',
                    aiDelta: '↑ 6.4% YoY',
                    learners: '+42% YoY'
                };
            } else {
                this.dateRangeText = 'Last 30 days · to 15 Oct 2026';
                this.kpi = { interns: 12480, employers: 486, aiRate: 71, learners: 4920 };
                this.trends = {
                    interns: '+312 this week',
                    internsDelta: '↑ 2.6% vs last month',
                    employers: '+19 this month',
                    employersDelta: '↑ 4.1% vs last month',
                    aiDelta: '↑ 3.2% vs last month',
                    learners: '+8% this month'
                };
            }

            this.updateChartForPeriod(newPeriod);
            if (typeof window.adminToast === 'function') {
                window.adminToast('Analytics period updated', newPeriod + ' view active', null, 'info');
            }
        },
        initCountUp() {
            const targets = { interns: 12480, employers: 486, aiRate: 71, learners: 4920 };
            const duration = 800;
            const steps = 30;
            const interval = duration / steps;
            let step = 0;
            const timer = setInterval(() => {
                step++;
                const progress = step / steps;
                const ease = 1 - Math.pow(1 - progress, 3);
                this.kpi.interns = Math.round(targets.interns * ease);
                this.kpi.employers = Math.round(targets.employers * ease);
                this.kpi.aiRate = Math.round(targets.aiRate * ease);
                this.kpi.learners = Math.round(targets.learners * ease);
                if (step >= steps) {
                    clearInterval(timer);
                    this.kpi.interns = targets.interns;
                    this.kpi.employers = targets.employers;
                    this.kpi.aiRate = targets.aiRate;
                    this.kpi.learners = targets.learners;
                }
            }, interval);
        },
        updateChartForPeriod(p) {
            let labels, apps, placements;
            if (p === 'Week') {
                labels = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
                apps = [140, 210, 320, 290, 380, 190, 150];
                placements = [22, 35, 48, 42, 60, 28, 18];
            } else if (p === 'Quarter') {
                labels = ['July', 'August', 'September', 'October'];
                apps = [4200, 5800, 6900, 7210];
                placements = [720, 980, 1150, 1312];
            } else if (p === 'Year') {
                labels = ['Q1', 'Q2', 'Q3', 'Q4 (Projected)'];
                apps = [14200, 19800, 24500, 31000];
                placements = [2100, 3200, 4400, 5800];
            } else {
                labels = ['Week 1', 'Week 2', 'Week 3', 'Week 4', 'Week 5'];
                apps = [1120, 1340, 1580, 1690, 1480];
                placements = [180, 240, 310, 342, 240];
            }
            overviewSeries = { labels, apps, placements };
            this.renderChart();
        },
        init() {
            this.initCountUp();
            this.$nextTick(() => {
                // Initialize Flatpickr Range Calendar
                const datePill = document.getElementById('overview-datepicker');
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
                                    window.adminToast('Custom date filter active', `${d1} to ${d2}`, null, 'info');
                                }
                            }
                        }
                    });
                }
                try {
                    const savedMode = localStorage.getItem('adminOverviewChartMode');
                    if (['area', 'bars', 'trend'].includes(savedMode)) this.chartMode = savedMode;
                } catch (error) {
                    // Storage can be unavailable; the default style is used
                }
                this.renderChart();
            });
        },
        renderChart() {
            const canvas = document.getElementById('adminOverviewChart');
            if (!canvas || typeof Chart === 'undefined') return;

            if (overviewChart) overviewChart.destroy();
            overviewChart = new Chart(canvas, buildOverviewChartConfig(canvas, this.chartMode, overviewSeries));
            this.hiddenSeries.forEach((hidden, index) => overviewChart.setDatasetVisibility(index, !hidden));
            overviewChart.update('none');
        },
        setChartMode(mode) {
            if (this.chartMode === mode) return;
            this.chartMode = mode;
            try {
                localStorage.setItem('adminOverviewChartMode', mode);
            } catch (error) {
                // Storage can be unavailable; the choice still applies to this visit
            }
            this.renderChart();
        },
        toggleSeries(index) {
            this.hiddenSeries[index] = !this.hiddenSeries[index];
            if (!overviewChart) return;
            overviewChart.setDatasetVisibility(index, !this.hiddenSeries[index]);
            overviewChart.update();
        }
    };
}
</script>
<div class="admin-shell" x-data="adminOverview()">
    <!-- Left Navigation Sidebar -->
    <?php include dirname(__DIR__, 2) . '/shared/components/admin-sidebar.php'; ?>

    <!-- Main Content Wrapper -->
    <div class="admin-main-wrap">
        <!-- Top Navigation Control Bar -->
        <?php include dirname(__DIR__, 2) . '/shared/components/admin-topnav.php'; ?>

        <!-- Page Body Content -->
        <main class="admin-content-body">
            <!-- 1. Header Row & Period Control -->
            <div class="admin-page-header">
                <div>
                    <h1 class="admin-page-title">Overview</h1>
                    <p class="admin-page-subtitle">Welcome back, <?= htmlspecialchars(explode(' ', $staff['name'])[0] ?? 'Jayden') ?>. Here's what's happening across the platform today.</p>
                </div>
                
                <div class="flex items-center gap-3 flex-wrap">
                    <!-- Segmented Period Control (Reference 3) -->
                    <div class="admin-segmented-ctrl" role="group" aria-label="Select Analytics Period">
                        <button type="button" class="admin-segmented-btn" :class="{ 'is-active': period === 'Week' }" @click="setPeriod('Week')">Week</button>
                        <button type="button" class="admin-segmented-btn" :class="{ 'is-active': period === 'Month' }" @click="setPeriod('Month')">Month</button>
                        <button type="button" class="admin-segmented-btn" :class="{ 'is-active': period === 'Quarter' }" @click="setPeriod('Quarter')">Quarter</button>
                        <button type="button" class="admin-segmented-btn" :class="{ 'is-active': period === 'Year' }" @click="setPeriod('Year')">Year</button>
                    </div>

                    <!-- Date Range Pill (Flatpickr Range Calendar) -->
                    <button type="button" class="admin-date-pill cursor-pointer" id="overview-datepicker" aria-label="Select date range">
                        <svg class="w-4 h-4 text-slate-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span x-text="dateRangeText">Last 30 days · to 15 Oct 2026</span>
                    </button>
                </div>
            </div>

            <!-- Dynamic Content Wrap -->
            <div class="flex flex-col gap-6">
                <!-- Today Activity Strip -->
                <section class="admin-today-strip admin-fade-in" aria-label="Today's activity">
                    <?php foreach ($todaySnapshot as $today): ?>
                        <div class="admin-today-tile">
                            <span class="admin-today-label"><?= htmlspecialchars($today['label']) ?></span>
                            <span class="admin-today-value"><?= htmlspecialchars($today['value']) ?></span>
                            <span class="admin-today-delta<?= $today['trend'] === 'up' ? ' is-up' : '' ?>"><?= htmlspecialchars($today['delta']) ?></span>
                        </div>
                    <?php endforeach; ?>
                </section>

                <!-- 2. KPI 4-Column Card Grid with Top-Right Icon Chips -->
                <section class="admin-kpi-grid" aria-label="Key Performance Indicators">
                    <!-- Card 1: Total Interns -->
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
                            <span class="admin-kpi-value" x-text="kpi.interns.toLocaleString()">12,480</span>
                            <span class="admin-trend-badge admin-trend-primary" x-text="trends.interns">+312 this week</span>
                        </div>
                        <div class="admin-kpi-delta">
                            <span class="text-indigo-600 font-semibold" x-text="trends.internsDelta">&uarr; 2.6% vs last month</span>
                        </div>
                    </div>

                    <!-- Card 2: Active Employers -->
                    <div class="admin-kpi-card admin-fade-in admin-fade-in-2">
                        <div class="admin-kpi-head">
                            <span class="admin-kpi-label">ACTIVE EMPLOYERS</span>
                            <div class="admin-kpi-icon-chip admin-kpi-icon-amber">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                            </div>
                        </div>
                        <div class="admin-kpi-row">
                            <span class="admin-kpi-value" x-text="kpi.employers.toLocaleString()">486</span>
                            <span class="admin-trend-badge admin-trend-primary" x-text="trends.employers">+19 this month</span>
                        </div>
                        <div class="admin-kpi-delta">
                            <span class="text-indigo-600 font-semibold" x-text="trends.employersDelta">&uarr; 4.1% vs last month</span>
                        </div>
                    </div>

                    <!-- Card 3: AI Match Acceptance -->
                    <div class="admin-kpi-card admin-fade-in admin-fade-in-3">
                        <div class="admin-kpi-head">
                            <span class="admin-kpi-label">AI MATCH ACCEPTANCE</span>
                            <div class="admin-kpi-icon-chip admin-kpi-icon-purple">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="admin-kpi-row">
                            <span class="admin-kpi-value" x-text="kpi.aiRate + '%'">71%</span>
                            <span class="admin-trend-badge admin-trend-primary">+3 pts vs Sept</span>
                        </div>
                        <div class="admin-kpi-delta">
                            <span class="text-indigo-600 font-semibold" x-text="trends.aiDelta">&uarr; 3.2% vs last month</span>
                        </div>
                    </div>

                    <!-- Card 4: Active Learners -->
                    <div class="admin-kpi-card admin-fade-in admin-fade-in-4">
                        <div class="admin-kpi-head">
                            <span class="admin-kpi-label">ACTIVE LEARNERS</span>
                            <div class="admin-kpi-icon-chip admin-kpi-icon-emerald">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"/>
                                </svg>
                            </div>
                        </div>
                        <div class="admin-kpi-row">
                            <span class="admin-kpi-value" x-text="kpi.learners.toLocaleString()">4,920</span>
                            <span class="admin-trend-badge admin-trend-primary" x-text="trends.learners">+8% this month</span>
                        </div>
                        <div class="admin-kpi-delta">
                            <span class="text-indigo-600 font-semibold">&uarr; 8.0%</span>
                            <span>vs last month</span>
                        </div>
                    </div>
                </section>

                <!-- 3. Applications Chart & Needs Your Attention -->
                <?php
                $queueIcons = [
                    'building' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4',
                    'sparkle' => 'M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z',
                    'book' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253',
                    'user' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z',
                ];
                $chartModes = [
                    'area' => ['label' => 'Area', 'icon' => 'M3 17l5-6 4 4 4-7 5 6v4H3z'],
                    'bars' => ['label' => 'Bars', 'icon' => 'M5 20V10m7 10V4m7 16v-7'],
                    'trend' => ['label' => 'Trend', 'icon' => 'M3 15c3-6 6-6 9-2s6 4 9-4'],
                ];
                $highPriorityCount = count(array_filter($attentionQueue, static fn (array $item): bool => $item['priority'] === 'High'));
                ?>
                <div class="admin-grid-2col">
                    <!-- Applications & Placements Chart with Style Switcher -->
                    <div class="admin-card admin-fade-in admin-fade-in-2">
                        <div class="admin-card-header admin-chart-header">
                            <div class="admin-live-heading">
                                <h2 class="admin-card-title">Applications &amp; placements</h2>
                                <p class="admin-card-subtitle">Recruitment throughput across all domains</p>
                            </div>
                            <div class="admin-segmented-ctrl admin-chart-modes" role="group" aria-label="Chart style">
                                <?php foreach ($chartModes as $modeKey => $mode): ?>
                                    <button type="button" class="admin-segmented-btn" :class="{ 'is-active': chartMode === '<?= $modeKey ?>' }" @click="setChartMode('<?= $modeKey ?>')">
                                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="<?= $mode['icon'] ?>"/></svg>
                                        <span><?= $mode['label'] ?></span>
                                    </button>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <div class="admin-chart-legend">
                            <button type="button" class="admin-chart-series" :class="{ 'is-muted': hiddenSeries[0] }" @click="toggleSeries(0)" :aria-pressed="!hiddenSeries[0]">
                                <span class="admin-chart-swatch is-apps"></span>
                                <span>Applications</span>
                                <strong>7,210</strong>
                                <span class="admin-chart-delta is-apps">
                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>12.4%
                                </span>
                            </button>
                            <button type="button" class="admin-chart-series" :class="{ 'is-muted': hiddenSeries[1] }" @click="toggleSeries(1)" :aria-pressed="!hiddenSeries[1]">
                                <span class="admin-chart-swatch is-placements"></span>
                                <span>Placements</span>
                                <strong>1,312</strong>
                                <span class="admin-chart-delta is-placements">
                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>8.6%
                                </span>
                            </button>
                        </div>

                        <div class="admin-chart-canvas">
                            <canvas id="adminOverviewChart" role="img" aria-label="Applications and placements over the selected period"></canvas>
                        </div>
                    </div>

                    <!-- Needs Your Attention Queue -->
                    <div class="admin-card admin-fade-in admin-fade-in-3">
                        <div class="admin-card-header">
                            <div class="admin-live-heading">
                                <h2 class="admin-card-title">Needs your attention</h2>
                                <p class="admin-card-subtitle">Work waiting on staff, most urgent first</p>
                            </div>
                            <span class="admin-triage-total"><?= $highPriorityCount ?> high priority</span>
                        </div>

                        <div class="admin-triage-list">
                            <?php foreach ($attentionQueue as $item): ?>
                                <a href="<?= htmlspecialchars($item['action_url']) ?>" class="admin-triage-item">
                                    <span class="admin-triage-icon is-<?= htmlspecialchars($item['badge_color']) ?>">
                                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="<?= $queueIcons[$item['icon']] ?>"/></svg>
                                    </span>
                                    <span class="admin-triage-body">
                                        <span class="admin-triage-top">
                                            <span class="admin-triage-title"><?= htmlspecialchars($item['title']) ?></span>
                                            <span class="admin-triage-priority is-<?= strtolower(htmlspecialchars($item['priority'])) ?>"><?= htmlspecialchars($item['priority']) ?></span>
                                        </span>
                                        <span class="admin-triage-meta"><?= htmlspecialchars($item['subtitle']) ?></span>
                                        <span class="admin-triage-owner"><?= htmlspecialchars($item['owner']) ?></span>
                                    </span>
                                    <span class="admin-triage-side">
                                        <strong class="admin-triage-count"><?= htmlspecialchars($item['badge']) ?></strong>
                                        <span class="admin-triage-action">
                                            <?= htmlspecialchars($item['action_label']) ?>
                                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                                        </span>
                                    </span>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <!-- Live Visitors Map -->
                <?php include dirname(__DIR__, 2) . '/shared/components/admin-live-map.php'; ?>

                <!-- Live Interviews Map (same component, interview feed) -->
                <?php
                $liveMap = [
                    'feed'       => url('/admin/api/interviews/live-map'),
                    'title'      => 'Live interviews',
                    'subtitle'   => 'Where people in live video interviews are joining from',
                    'totalLabel' => 'in interviews',
                    'labelA'     => 'candidates',
                    'labelB'     => 'employers',
                    'emptyText'  => 'No interviews are live right now.',
                ];
                include dirname(__DIR__, 2) . '/shared/components/admin-live-map.php';
                ?>

                <!-- Finance, Top Performers & Marketplace -->
                <div class="admin-grid-insights">
                    <?php if ($canViewFinance): ?>
                        <div class="admin-card admin-fade-in admin-fade-in-3" x-data='adminFinanceChart(<?= htmlspecialchars(json_encode($finance['chart']), ENT_QUOTES, 'UTF-8') ?>)'>
                            <div class="admin-card-header">
                                <div class="admin-live-heading">
                                    <h2 class="admin-card-title">Finance performance</h2>
                                    <p class="admin-card-subtitle">Monthly recurring revenue</p>
                                </div>
                                <a href="<?= url('/admin/subscriptions') ?>" class="admin-card-link">View ledger</a>
                            </div>

                            <div class="admin-finance-total">
                                <span class="admin-finance-value"><?= htmlspecialchars($finance['mrr']) ?></span>
                                <span class="admin-trend-badge admin-trend-primary"><?= htmlspecialchars($finance['mrr_trend']) ?></span>
                            </div>

                            <div class="admin-finance-chart">
                                <canvas x-ref="financeCanvas" aria-label="Monthly revenue from May to October" role="img"></canvas>
                            </div>

                            <div class="admin-bar-list">
                                <?php foreach ($finance['streams'] as $stream): ?>
                                    <div class="admin-bar-row">
                                        <div class="admin-bar-meta">
                                            <span class="admin-bar-name"><?= htmlspecialchars($stream['name']) ?></span>
                                            <span class="admin-bar-value"><strong><?= (int) $stream['pct'] ?>%</strong> <?= htmlspecialchars($stream['value']) ?></span>
                                        </div>
                                        <div class="admin-progress-bg admin-bar-track">
                                            <div class="admin-progress-fill" style="width: <?= (int) $stream['pct'] ?>%;"></div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>

                            <div class="admin-stat-trio">
                                <div><span>Net settlement</span><strong><?= htmlspecialchars($finance['net_settlement']) ?></strong></div>
                                <div><span>Failed payments</span><strong><?= htmlspecialchars($finance['failed_payments']) ?></strong></div>
                                <div><span>Refund requests</span><strong><?= htmlspecialchars($finance['refund_requests']) ?></strong></div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="admin-card admin-fade-in admin-fade-in-4" x-data="{ performerTab: 'employers' }">
                        <div class="admin-card-header">
                            <div class="admin-live-heading">
                                <h2 class="admin-card-title">Top performers</h2>
                                <p class="admin-card-subtitle">Leaders this period</p>
                            </div>
                        </div>

                        <div class="admin-segmented-ctrl admin-live-tabs">
                            <?php foreach (['employers' => 'Employers', 'universities' => 'Universities', 'interns' => 'Interns'] as $tabKey => $tabLabel): ?>
                                <button type="button" class="admin-segmented-btn" :class="{ 'is-active': performerTab === '<?= $tabKey ?>' }" @click="performerTab = '<?= $tabKey ?>'"><?= $tabLabel ?></button>
                            <?php endforeach; ?>
                        </div>

                        <?php foreach ($topPerformers as $group => $people): ?>
                            <div class="admin-performer-list" x-show="performerTab === '<?= $group ?>'"<?= $group === 'employers' ? '' : ' style="display: none;"' ?>>
                                <?php foreach ($people as $person): ?>
                                    <div class="admin-performer-row">
                                        <span class="admin-performer-avatar"><?= htmlspecialchars($person['initials']) ?></span>
                                        <div class="admin-performer-body">
                                            <span class="admin-performer-name"><?= htmlspecialchars($person['name']) ?></span>
                                            <span class="admin-performer-meta"><?= htmlspecialchars($person['meta']) ?></span>
                                        </div>
                                        <span class="admin-performer-score is-<?= htmlspecialchars($person['tone']) ?>"><?= htmlspecialchars($person['score']) ?></span>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="admin-card admin-fade-in admin-fade-in-5">
                        <div class="admin-card-header">
                            <div class="admin-live-heading">
                                <h2 class="admin-card-title">Marketplace balance</h2>
                                <p class="admin-card-subtitle">Are employers getting candidates?</p>
                            </div>
                        </div>

                        <div class="admin-market-grid">
                            <?php foreach ($marketplace['stats'] as $stat): ?>
                                <div class="admin-market-tile">
                                    <span class="admin-market-value"><?= htmlspecialchars($stat['value']) ?></span>
                                    <span class="admin-market-label"><?= htmlspecialchars($stat['label']) ?></span>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <div class="admin-bar-list">
                            <span class="admin-bar-heading">Open roles by category</span>
                            <?php foreach ($marketplace['demand'] as $demand): ?>
                                <div class="admin-bar-row">
                                    <div class="admin-bar-meta">
                                        <span class="admin-bar-name"><?= htmlspecialchars($demand['name']) ?></span>
                                        <span class="admin-bar-value"><strong><?= (int) $demand['pct'] ?>%</strong></span>
                                    </div>
                                    <div class="admin-progress-bg admin-bar-track">
                                        <div class="admin-progress-fill" style="width: <?= (int) $demand['pct'] ?>%;"></div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <!-- 4. NEW Jenny Insights Card (Requirement 6) -->
                <div class="admin-ai-insights-card admin-fade-in admin-fade-in-3">
                    <div class="flex items-center justify-between flex-wrap gap-2">
                        <div class="flex items-center gap-3">
                            <img src="<?= asset('assets/images/jenny_ai/jenny_icon.png') ?>" alt="Jenny AI" class="w-8 h-8 rounded-full object-cover flex-shrink-0 border border-purple-200 shadow-sm">
                            <div>
                                <h2 class="text-sm font-bold text-slate-900 tracking-tight">Jenny AI Strategic Insights</h2>
                                <p class="text-xs text-slate-500">Algorithmic talent intelligence based on the last 30 days of activity</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-[11px] font-semibold text-purple-700 bg-purple-100/80 px-2.5 py-1 rounded-full border border-purple-200">
                                Cached daily · Next run 06:00 WAT
                            </span>
                            <a href="<?= url('/admin/ai/chat') ?>" 
                               class="text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 px-3 py-1.5 rounded-lg transition-colors flex items-center gap-1.5 shadow-sm cursor-pointer"
                               style="text-decoration: none;">
                                <span>Ask Jenny about these numbers</span>
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                </svg>
                            </a>
                        </div>
                    </div>

                    <!-- 3 Specific InternConnect AI Insights (Clean, Compact, Finer Layout) -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                        <!-- Insight 1: Profile Completeness -->
                        <div class="admin-insight-item">
                            <div>
                                <div class="flex items-center gap-2 mb-1.5">
                                    <span class="admin-insight-num">01</span>
                                    <h3 class="admin-insight-title">2,620 Incomplete Profiles</h3>
                                </div>
                                <p class="admin-insight-desc">
                                    Candidates with incomplete profiles drop algorithmic match rank by 44%. Automated reminder campaign ready.
                                </p>
                            </div>
                            <button type="button" 
                                    class="admin-insight-link"
                                    @click="window.adminToast('Reminder campaign scheduled for 2,620 candidates', 'Nudge dispatched via email & in-app', null, 'success')">
                                <span>Send reminder</span>
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                </svg>
                            </button>
                        </div>

                        <!-- Insight 2: Employer Optimization -->
                        <div class="admin-insight-item">
                            <div>
                                <div class="flex items-center gap-2 mb-1.5">
                                    <span class="admin-insight-num">02</span>
                                    <h3 class="admin-insight-title">14 Zero-Applicant Roles</h3>
                                </div>
                                <p class="admin-insight-desc">
                                    Overly generic job titles received 0 applicants after 7 days. AI role keyword enhancement suggested.
                                </p>
                            </div>
                            <button type="button" 
                                    class="admin-insight-link"
                                    @click="window.adminToast('Role title recommendations queued', 'Employers notified with keyword enhancements', null, 'success')">
                                <span>Optimize roles</span>
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                </svg>
                            </button>
                        </div>

                        <!-- Insight 3: Interview Scheduling Pattern -->
                        <div class="admin-insight-item">
                            <div>
                                <div class="flex items-center gap-2 mb-1.5">
                                    <span class="admin-insight-num">03</span>
                                    <h3 class="admin-insight-title">Friday 2–5 PM Peak Show-Up</h3>
                                </div>
                                <p class="admin-insight-desc">
                                    Interviews scheduled Friday afternoon show 88% attendance vs 62% on Mondays. Calendar recommendation active.
                                </p>
                            </div>
                            <button type="button" 
                                    class="admin-insight-link"
                                    @click="window.adminToast('Scheduling preference rule applied', 'Candidate calendars will prioritize recommended time slots', null, 'info')">
                                <span>Apply timing rule</span>
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- 5. Bottom Row: Conversion Funnel, System Health & Activity Feed -->
                <?php
                $funnelCounts = array_map(static fn (array $step): int => (int) str_replace(',', '', $step['count']), $funnel);
                $largestDropIdx = 1;
                $largestDrop = 0.0;
                foreach ($funnelCounts as $stepIdx => $stepCount) {
                    if ($stepIdx === 0 || $funnelCounts[$stepIdx - 1] === 0) {
                        continue;
                    }
                    $stepDrop = 100 - ($stepCount / $funnelCounts[$stepIdx - 1] * 100);
                    if ($stepDrop > $largestDrop) {
                        $largestDrop = $stepDrop;
                        $largestDropIdx = $stepIdx;
                    }
                }
                $finalStep = $funnel[count($funnel) - 1];
                $activityFilters = ['all' => 'All', 'verification' => 'Verification', 'ai' => 'Jenny AI', 'hiring' => 'Hiring', 'support' => 'Support'];
                ?>
                <div class="admin-grid-2col">
                    <div class="admin-card-stack">
                        <!-- Conversion Funnel -->
                        <div class="admin-card admin-fade-in admin-fade-in-4" x-data="{
                            animated: false,
                            init() {
                                setTimeout(() => { this.animated = true; }, 150);
                            }
                        }">
                            <div class="admin-card-header">
                                <div class="admin-live-heading">
                                    <h2 class="admin-card-title">From sign-up to placement</h2>
                                    <p class="admin-card-subtitle">How candidates move through each stage</p>
                                </div>
                            </div>

                            <div class="admin-funnel-summary">
                                <div class="admin-funnel-summary-tile">
                                    <span>Overall conversion</span>
                                    <strong><?= number_format($finalStep['pct'], 1) ?>%</strong>
                                </div>
                                <div class="admin-funnel-summary-tile">
                                    <span>Placed this period</span>
                                    <strong><?= htmlspecialchars($finalStep['count']) ?></strong>
                                </div>
                                <div class="admin-funnel-summary-tile is-alert">
                                    <span>Largest drop-off</span>
                                    <strong><?= htmlspecialchars($funnel[$largestDropIdx]['stage']) ?></strong>
                                </div>
                            </div>

                            <ol class="admin-funnel-steps">
                                <?php foreach ($funnel as $idx => $step): ?>
                                    <?php if ($idx > 0): ?>
                                        <?php $continued = $funnelCounts[$idx - 1] > 0 ? $funnelCounts[$idx] / $funnelCounts[$idx - 1] * 100 : 0; ?>
                                        <li class="admin-funnel-conv<?= $idx === $largestDropIdx ? ' is-alert' : '' ?>" aria-hidden="true">
                                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
                                            <span><?= number_format($continued, 1) ?>% moved on</span>
                                            <?php if ($idx === $largestDropIdx): ?>
                                                <span class="admin-funnel-conv-flag">Largest drop-off</span>
                                            <?php endif; ?>
                                        </li>
                                    <?php endif; ?>
                                    <li class="admin-funnel-step">
                                        <span class="admin-funnel-index"><?= $idx + 1 ?></span>
                                        <div class="admin-funnel-step-body">
                                            <div class="admin-funnel-step-head">
                                                <span class="admin-funnel-name"><?= htmlspecialchars($step['stage']) ?></span>
                                                <span class="admin-funnel-figure">
                                                    <strong><?= htmlspecialchars($step['count']) ?></strong>
                                                    <span><?= number_format($step['pct'], 1) ?>%</span>
                                                </span>
                                            </div>
                                            <div class="admin-progress-bg admin-funnel-track">
                                                <div class="admin-progress-fill admin-funnel-fill" :style="{ width: animated ? '<?= (int) $step['bar_pct'] ?>%' : '0%' }"></div>
                                            </div>
                                        </div>
                                    </li>
                                <?php endforeach; ?>
                            </ol>
                        </div>

                        <!-- System Health with 30-Day History -->
                        <div class="admin-card admin-card-grow admin-fade-in admin-fade-in-5">
                            <div class="admin-card-header">
                                <div class="admin-live-heading">
                                    <h2 class="admin-card-title">System health</h2>
                                    <p class="admin-card-subtitle">Services the platform depends on</p>
                                </div>
                                <span class="admin-health-pill"><span class="admin-health-dot"></span>All operational</span>
                            </div>

                            <div class="admin-status-list">
                                <?php foreach ($systemHealth as $service): ?>
                                    <div class="admin-status-row">
                                        <div class="admin-status-info">
                                            <span class="admin-health-dot"></span>
                                            <div class="admin-performer-body">
                                                <span class="admin-performer-name"><?= htmlspecialchars($service['name']) ?></span>
                                                <span class="admin-performer-meta"><?= htmlspecialchars($service['detail']) ?></span>
                                            </div>
                                        </div>
                                        <div class="admin-status-history">
                                            <div class="admin-status-bars" role="img" aria-label="<?= htmlspecialchars($service['name']) ?> uptime over the last 30 days: <?= htmlspecialchars($service['uptime']) ?>">
                                                <?php foreach ($service['history'] as $day): ?>
                                                    <span class="<?= $day === 'up' ? 'is-up' : 'is-degraded' ?>"></span>
                                                <?php endforeach; ?>
                                            </div>
                                            <span class="admin-status-uptime"><?= htmlspecialchars($service['uptime']) ?></span>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>

                            <div class="admin-status-legend">
                                <span>30 days ago</span>
                                <span class="admin-status-legend-keys">
                                    <span><i class="is-up"></i>Operational</span>
                                    <span><i class="is-degraded"></i>Degraded</span>
                                </span>
                                <span>Today</span>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Activity Feed -->
                    <div class="admin-card admin-fade-in admin-fade-in-5" x-data="{ activityFilter: 'all' }">
                        <div class="admin-card-header">
                            <div class="admin-live-heading">
                                <h2 class="admin-card-title">Recent activity</h2>
                                <p class="admin-card-subtitle">Staff actions and platform events</p>
                            </div>
                            <a href="<?= url('/admin/settings/team') ?>" class="admin-card-link">View log</a>
                        </div>

                        <div class="admin-map-regions no-scrollbar" role="group" aria-label="Filter activity">
                            <?php foreach ($activityFilters as $filterKey => $filterLabel): ?>
                                <button type="button" class="admin-map-region-btn" :class="{ 'is-active': activityFilter === '<?= $filterKey ?>' }" @click="activityFilter = '<?= $filterKey ?>'"><?= $filterLabel ?></button>
                            <?php endforeach; ?>
                        </div>

                        <ol class="admin-feed">
                            <?php foreach ($recentActivity as $act): ?>
                                <li class="admin-feed-item" x-show="activityFilter === 'all' || activityFilter === '<?= htmlspecialchars($act['category']) ?>'">
                                    <span class="admin-feed-avatar is-<?= htmlspecialchars($act['tone']) ?>"><?= htmlspecialchars($act['initials']) ?></span>
                                    <div class="admin-feed-body">
                                        <p class="admin-feed-text"><strong><?= htmlspecialchars($act['actor']) ?></strong> <?= htmlspecialchars($act['action']) ?></p>
                                        <div class="admin-feed-meta">
                                            <span class="admin-feed-tag is-<?= htmlspecialchars($act['tone']) ?>"><?= htmlspecialchars($act['badge']) ?></span>
                                            <span><?= htmlspecialchars($act['time']) ?></span>
                                        </div>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ol>
                    </div>
                </div>
            </div>
        </main>
    </div>

</div>
