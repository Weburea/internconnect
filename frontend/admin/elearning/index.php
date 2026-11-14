<?php
declare(strict_types=1);
/**
 * Admin E-Learning & Course Moderation View
 * Complies with Section 7.0 of docs/10-admin-system.md
 * 
 * Features:
 * - Real-time Course Moderation & Student Enrollment Analytics
 * - Dual-series Chart.js Student Learning Hours & Active Viewers Area Chart
 * - Discipline Distribution Donut Chart (Design, Backend, Frontend, Data)
 * - Working Request Revision Modal with Structured Feedback Checklist
 * - One-Click Approve & Publish State Machine with Live Toast
 * 
 * @var array  $staff     Staff profile data
 * @var string $activeNav Navigation identifier
 * @var array  $metrics   Course moderation metrics
 */
?>
<div class="admin-shell" x-data="adminElearning()">
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
                    <h1 class="admin-page-title">E-Learning &amp; Course Moderation</h1>
                    <p class="admin-page-subtitle">Review mentor curriculum submissions, video modules, and oversee student completions across 4 primary tracks</p>
                </div>
                <a href="<?= url('/admin/elearning/partners') ?>" style="display: inline-flex; align-items: center; height: 38px; padding: 0 16px; border-radius: 10px; background: #4F48E6; color: #FFFFFF; font-size: 13px; font-weight: 700; text-decoration: none; white-space: nowrap;">Training partners</a>
            </div>

            <!-- 4 Premium KPI Metric Cards (No side borders, 44px rounded icon chips) -->
            <section class="admin-kpi-grid mb-6" aria-label="Course Moderation Metrics">
                <!-- Card 1: Awaiting Moderation -->
                <div class="admin-kpi-card">
                    <div class="admin-kpi-head">
                        <span class="admin-kpi-label">AWAITING MODERATION</span>
                        <div class="admin-kpi-icon-chip admin-kpi-icon-amber">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="admin-kpi-row">
                        <span class="admin-kpi-value" x-text="pendingCourses.length">3</span>
                    </div>
                    <div class="flex items-center gap-1.5 text-[11px] font-semibold text-amber-600">
                        <span>Queued for audit</span>
                        <span class="text-slate-400 font-normal">&middot; Mentor syllabuses</span>
                    </div>
                </div>

                <!-- Card 2: Total Learners -->
                <div class="admin-kpi-card">
                    <div class="admin-kpi-head">
                        <span class="admin-kpi-label">ACTIVE LEARNERS</span>
                        <div class="admin-kpi-icon-chip admin-kpi-icon-emerald">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="admin-kpi-row">
                        <span class="admin-kpi-value">4,920</span>
                    </div>
                    <div class="flex items-center gap-1.5 text-[11px] font-semibold text-emerald-600">
                        <span>&uarr; 14.8%</span>
                        <span class="text-slate-400 font-normal">active university interns</span>
                    </div>
                </div>

                <!-- Card 3: Completion Rate -->
                <div class="admin-kpi-card">
                    <div class="admin-kpi-head">
                        <span class="admin-kpi-label">COURSE COMPLETION RATE</span>
                        <div class="admin-kpi-icon-chip admin-kpi-icon-blue">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="admin-kpi-row">
                        <span class="admin-kpi-value">68.2%</span>
                    </div>
                    <div class="flex items-center gap-1.5 text-[11px] font-semibold text-blue-600">
                        <span>+4.1 pts</span>
                        <span class="text-slate-400 font-normal">vs platform average</span>
                    </div>
                </div>

                <!-- Card 4: Total Modules Published -->
                <div class="admin-kpi-card">
                    <div class="admin-kpi-head">
                        <span class="admin-kpi-label">MODULES PUBLISHED</span>
                        <div class="admin-kpi-icon-chip admin-kpi-icon-purple">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                        </div>
                    </div>
                    <div class="admin-kpi-row">
                        <span class="admin-kpi-value">284</span>
                    </div>
                    <div class="flex items-center gap-1.5 text-[11px] font-semibold text-purple-600">
                        <span>Across 48 courses</span>
                        <span class="text-slate-400 font-normal">&middot; HD Video</span>
                    </div>
                </div>
            </section>

            <!-- Split Analytics Grid: Student Learning Engagement Chart & Track Distribution Histogram -->
            <div class="admin-grid-2col mb-6">
                <!-- Left: Learning Engagement Area Chart -->
                <div class="admin-card flex flex-col justify-between">
                    <div>
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
                            <div>
                                <h2 class="text-sm font-bold text-slate-900">Student Learning Hours &amp; Activity</h2>
                                <p class="text-xs text-slate-500">Weekly active student study hours &amp; completed course modules</p>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="flex items-center gap-1.5 text-xs font-semibold text-slate-600">
                                    <span class="w-2.5 h-2.5 rounded-full bg-indigo-600"></span>
                                    <span>Study Hours</span>
                                </span>
                                <span class="flex items-center gap-1.5 text-xs font-semibold text-slate-600 ml-2">
                                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                                    <span>Completed Quizzes</span>
                                </span>
                            </div>
                        </div>

                        <!-- Canvas -->
                        <div class="relative w-full" style="height: 250px;">
                            <canvas id="admin-elearning-chart"></canvas>
                        </div>
                    </div>

                    <!-- Bottom Insight Footer (Generous Top Spacing) -->
                    <div class="flex items-center justify-between text-xs text-slate-500" style="margin-top: 24px; padding-top: 18px; border-top: 1px solid #F1F5F9;">
                        <span>Top Enrolled Curriculum: <strong class="text-slate-900">Modern Frontend Engineering &middot; 1,420 Active Students</strong></span>
                        <span class="text-indigo-600 font-semibold">&bull; 98.4% Module Completion</span>
                    </div>
                </div>

                <!-- Right: Course Track Distribution Histogram (Replaced Doughnut Chart) -->
                <div class="admin-card flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <h2 class="text-sm font-bold text-slate-900">Track Distribution</h2>
                            <span class="text-[11px] font-bold text-slate-400 uppercase">Catalog</span>
                        </div>
                        <p class="text-xs text-slate-500 mb-4">Course offerings by career discipline histogram</p>

                        <!-- Histogram Canvas -->
                        <div class="relative w-full" style="height: 160px;">
                            <canvas id="admin-elearning-histogram"></canvas>
                        </div>
                    </div>

                    <!-- Clean Structured Thin Progress List -->
                    <div class="space-y-3 pt-4 border-t border-slate-100 mt-4 text-xs">
                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <span class="font-medium text-slate-700">UI/UX &amp; Product Design</span>
                                <span class="font-bold text-slate-900">38% <span class="text-slate-400 font-normal">(18 Courses)</span></span>
                            </div>
                            <div class="w-full bg-slate-100 rounded-full h-1.5 overflow-hidden">
                                <div class="bg-[#4F48E6] h-full rounded-full" style="width: 38%;"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <span class="font-medium text-slate-700">Backend &amp; Cloud APIs</span>
                                <span class="font-bold text-slate-900">32% <span class="text-slate-400 font-normal">(15 Courses)</span></span>
                            </div>
                            <div class="w-full bg-slate-100 rounded-full h-1.5 overflow-hidden">
                                <div class="bg-blue-500 h-full rounded-full" style="width: 32%;"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <span class="font-medium text-slate-700">Frontend Engineering</span>
                                <span class="font-bold text-slate-900">18% <span class="text-slate-400 font-normal">(9 Courses)</span></span>
                            </div>
                            <div class="w-full bg-slate-100 rounded-full h-1.5 overflow-hidden">
                                <div class="bg-emerald-500 h-full rounded-full" style="width: 18%;"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <span class="font-medium text-slate-700">Data Science &amp; AI</span>
                                <span class="font-bold text-slate-900">12% <span class="text-slate-400 font-normal">(6 Courses)</span></span>
                            </div>
                            <div class="w-full bg-slate-100 rounded-full h-1.5 overflow-hidden">
                                <div class="bg-amber-500 h-full rounded-full" style="width: 12%;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Learning Partners: what students see on /elearning (logo, services, visibility) + referral counts -->
            <style>
            .alp-card { background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 16px; margin-bottom: 24px; overflow: hidden; }
            .alp-head { display: flex; align-items: flex-end; justify-content: space-between; gap: 10px; padding: 18px 20px; border-bottom: 1px solid #F1F5F9; flex-wrap: wrap; }
            .alp-head h2 { font-size: 16px; font-weight: 800; color: #0F172A; margin: 0; }
            .alp-head p { font-size: 12.5px; color: #64748B; margin: 4px 0 0; }
            .alp-link { font-size: 12.5px; font-weight: 700; color: #4F48E6; text-decoration: none; }
            .alp-grid { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 14px; padding: 18px 20px 20px; }
            .alp-item { border: 1px solid #E2E8F0; border-radius: 14px; padding: 14px; display: flex; flex-direction: column; gap: 12px; min-width: 0; }
            .alp-item.is-hidden { background: #F8FAFC; }
            .alp-item-top { display: flex; align-items: center; gap: 10px; min-width: 0; }
            .alp-logo { width: 42px; height: 42px; border-radius: 12px; border: 1px solid #E2E8F0; background: #FFFFFF; padding: 5px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; overflow: hidden; color: #FFFFFF; font-weight: 800; font-size: 13px; }
            .alp-logo img { width: 100%; height: 100%; object-fit: contain; display: block; }
            .alp-name { min-width: 0; flex: 1; }
            .alp-name strong { display: block; font-size: 14px; color: #0F172A; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
            .alp-name span { display: block; font-size: 12px; color: #64748B; }
            .alp-pill { display: inline-flex; align-items: center; gap: 5px; height: 22px; padding: 0 9px; border-radius: 9999px; font-size: 11px; font-weight: 700; white-space: nowrap; }
            .alp-pill::before { content: ''; width: 6px; height: 6px; border-radius: 50%; background: currentColor; }
            .alp-pill.is-on { background: #ECFDF5; color: #047857; }
            .alp-pill.is-off { background: #F1F5F9; color: #64748B; }
            .alp-stats { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 8px; }
            .alp-stat { background: #F8FAFC; border-radius: 10px; padding: 8px 10px; }
            .alp-stat strong { display: block; font-size: 15px; font-weight: 800; color: #0F172A; }
            .alp-stat span { display: block; font-size: 11px; color: #64748B; }
            .alp-btn { display: inline-flex; align-items: center; justify-content: center; gap: 6px; height: 34px; padding: 0 12px; border-radius: 9px; border: 1px solid #E2E8F0; background: #FFFFFF; font-size: 12.5px; font-weight: 700; color: #334155; cursor: pointer; }
            .alp-btn:hover:not(:disabled) { background: #F8FAFC; color: #0F172A; }
            .alp-btn.is-primary { background: #4F48E6; border-color: #4F48E6; color: #FFFFFF; }
            .alp-btn.is-primary:hover:not(:disabled) { background: #4338CA; color: #FFFFFF; }
            .alp-btn:disabled { opacity: .55; cursor: not-allowed; }
            .alp-modal { position: fixed; inset: 0; z-index: 3000; background: rgba(0, 6, 35, .55); display: flex; align-items: center; justify-content: center; padding: 20px; }
            .alp-modal-card { width: 100%; max-width: 620px; max-height: calc(100vh - 40px); overflow-y: auto; background: #FFFFFF; border-radius: 18px; padding: 24px; box-shadow: 0 24px 60px rgba(0, 0, 0, .25); }
            .alp-modal-card h3 { font-size: 17px; font-weight: 800; color: #000623; margin: 0 0 4px; }
            .alp-modal-card .alp-sub { font-size: 13px; color: #64748B; margin: 0 0 18px; }
            .alp-field { display: flex; flex-direction: column; gap: 6px; margin-bottom: 14px; }
            .alp-field label, .alp-field .alp-label { font-size: 12px; font-weight: 700; color: #334155; }
            .alp-field input[type=text], .alp-field input[type=url], .alp-field textarea { border: 1px solid #E2E8F0; border-radius: 10px; padding: 9px 12px; font-size: 13px; color: #0F172A; background: #FFFFFF; width: 100%; font-family: inherit; }
            .alp-field textarea { min-height: 76px; resize: vertical; }
            .alp-field input:focus, .alp-field textarea:focus { outline: none; border-color: #4F48E6; box-shadow: 0 0 0 3px rgba(79, 72, 230, .1); }
            .alp-row2 { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
            .alp-logo-row { display: flex; align-items: center; gap: 12px; }
            .alp-logo-row .alp-logo { width: 56px; height: 56px; }
            .alp-hint { font-size: 11.5px; color: #94A3B8; }
            .alp-services { display: flex; flex-direction: column; gap: 8px; }
            .alp-service { display: grid; grid-template-columns: minmax(0, 1fr) minmax(0, 1.4fr) 34px; gap: 8px; }
            .alp-icon-btn { width: 34px; height: 38px; border: 1px solid #E2E8F0; border-radius: 9px; background: #FFFFFF; color: #64748B; cursor: pointer; display: flex; align-items: center; justify-content: center; }
            .alp-icon-btn:hover { background: #F8FAFC; color: #0F172A; }
            .alp-switch { display: flex; align-items: center; justify-content: space-between; gap: 12px; padding: 12px 14px; border: 1px solid #E2E8F0; border-radius: 12px; margin-bottom: 16px; }
            .alp-switch strong { display: block; font-size: 13px; color: #0F172A; }
            .alp-switch span { display: block; font-size: 12px; color: #64748B; }
            .alp-toggle { position: relative; width: 40px; height: 22px; flex-shrink: 0; }
            .alp-toggle input { position: absolute; inset: 0; opacity: 0; margin: 0; cursor: pointer; z-index: 1; }
            .alp-toggle i { position: absolute; inset: 0; border-radius: 9999px; background: #CBD5E1; transition: background .2s ease; }
            .alp-toggle i::after { content: ''; position: absolute; top: 3px; left: 3px; width: 16px; height: 16px; border-radius: 50%; background: #FFFFFF; transition: transform .2s ease; }
            .alp-toggle input:checked + i { background: #4F48E6; }
            .alp-toggle input:checked + i::after { transform: translateX(18px); }
            .alp-error { margin: 0 0 12px; padding: 10px 12px; border-radius: 10px; background: #FEF2F2; color: #B91C1C; font-size: 12.5px; font-weight: 600; }
            .alp-actions { display: flex; justify-content: flex-end; gap: 10px; margin-top: 6px; }
            @media (max-width: 1100px) { .alp-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); } }
            @media (max-width: 680px) { .alp-grid, .alp-row2 { grid-template-columns: minmax(0, 1fr); } .alp-service { grid-template-columns: minmax(0, 1fr) 34px; } .alp-service input:nth-child(2) { grid-column: 1 / 2; } }
            </style>
            <section class="alp-card" x-data="adminLearningPartners(<?= htmlspecialchars(json_encode($learningPartners ?? [], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE), ENT_QUOTES, 'UTF-8') ?>)">
                <div class="alp-head">
                    <div>
                        <h2>Learning Partners</h2>
                        <p>Platforms students are sent to from E-Learning. Referrals count "Visit Platform &amp; Enroll" clicks.</p>
                    </div>
                    <a class="alp-link" href="<?= url('/elearning') ?>" target="_blank" rel="noopener">View public page</a>
                </div>
                <div class="alp-grid">
                    <template x-for="p in partners" :key="p.id">
                        <div class="alp-item" :class="{ 'is-hidden': !p.is_active }">
                            <div class="alp-item-top">
                                <span class="alp-logo" :style="p.logo_url ? '' : 'background:' + p.brand_color">
                                    <template x-if="p.logo_url"><img :src="p.logo_url" :alt="p.name + ' logo'" width="32" height="32"></template>
                                    <template x-if="!p.logo_url"><span x-text="p.initials"></span></template>
                                </span>
                                <div class="alp-name">
                                    <strong x-text="p.name"></strong>
                                    <span x-text="p.tier"></span>
                                </div>
                                <span class="alp-pill" :class="p.is_active ? 'is-on' : 'is-off'" x-text="p.is_active ? 'Visible' : 'Hidden'"></span>
                            </div>
                            <div class="alp-stats">
                                <div class="alp-stat"><strong x-text="p.programs"></strong><span>Programs</span></div>
                                <div class="alp-stat"><strong x-text="p.referrals_30d"></strong><span>Referrals 30d</span></div>
                                <div class="alp-stat"><strong x-text="p.referrals"></strong><span>All referrals</span></div>
                            </div>
                            <button type="button" class="alp-btn" @click="edit(p)" :aria-label="'Edit ' + p.name">Edit partner</button>
                        </div>
                    </template>
                </div>

                <!-- Edit partner -->
                <div class="alp-modal" x-show="form" x-cloak style="display: none;" @keydown.escape.window="form = null">
                    <form class="alp-modal-card" role="dialog" aria-modal="true" @click.outside="form = null" @submit.prevent="save()" x-show="form">
                        <template x-if="form">
                            <div>
                                <h3 x-text="'Edit ' + form.name"></h3>
                                <p class="alp-sub">Shown on the E-Learning page and in each program's details.</p>
                                <p class="alp-error" x-show="error" x-text="error"></p>
                                <div class="alp-field">
                                    <span class="alp-label">Logo</span>
                                    <div class="alp-logo-row">
                                        <span class="alp-logo" :style="(logoPreview || form.logo_url) ? '' : 'background:' + form.brand_color">
                                            <template x-if="logoPreview || form.logo_url"><img :src="logoPreview || form.logo_url" alt="Logo preview" width="44" height="44"></template>
                                            <template x-if="!(logoPreview || form.logo_url)"><span x-text="form.initials"></span></template>
                                        </span>
                                        <div>
                                            <button type="button" class="alp-btn" @click="$refs.logoInput.click()">Upload new logo</button>
                                            <div class="alp-hint" style="margin-top: 4px;">PNG, JPG or WebP, square works best, max 5MB.</div>
                                        </div>
                                        <input type="file" x-ref="logoInput" accept=".png,.jpg,.jpeg,.webp" hidden @change="pickLogo($event)">
                                    </div>
                                </div>
                                <div class="alp-row2">
                                    <div class="alp-field"><label for="alp-name">Name</label><input id="alp-name" type="text" x-model="form.name" required maxlength="120"></div>
                                    <div class="alp-field"><label for="alp-tier">Badge</label><input id="alp-tier" type="text" x-model="form.tier" maxlength="60"></div>
                                </div>
                                <div class="alp-row2">
                                    <div class="alp-field"><label for="alp-web">Website (https)</label><input id="alp-web" type="url" x-model="form.website" required pattern="https://.*"></div>
                                    <div class="alp-field"><label for="alp-hubs">Hubs / locations</label><input id="alp-hubs" type="text" x-model="form.hubs" maxlength="255"></div>
                                </div>
                                <div class="alp-field"><label for="alp-about">About the platform</label><textarea id="alp-about" x-model="form.about" maxlength="1200"></textarea></div>
                                <div class="alp-field">
                                    <span class="alp-label">Services offered</span>
                                    <div class="alp-services">
                                        <template x-for="(s, i) in form.services" :key="i">
                                            <div class="alp-service">
                                                <input type="text" x-model="s.name" placeholder="Service" maxlength="80" :aria-label="'Service ' + (i + 1)">
                                                <input type="text" x-model="s.desc" placeholder="Short description" maxlength="200" :aria-label="'Description ' + (i + 1)">
                                                <button type="button" class="alp-icon-btn" @click="form.services.splice(i, 1)" :aria-label="'Remove service ' + (i + 1)">
                                                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.4"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                                </button>
                                            </div>
                                        </template>
                                        <button type="button" class="alp-btn" style="align-self: flex-start;" @click="form.services.push({ name: '', desc: '' })" :disabled="form.services.length >= 12">Add service</button>
                                    </div>
                                </div>
                                <div class="alp-switch">
                                    <div><strong>Show on E-Learning</strong><span>Hidden partners and their programs disappear from the public page.</span></div>
                                    <label class="alp-toggle"><input type="checkbox" x-model="form.is_active" aria-label="Show on E-Learning"><i></i></label>
                                </div>
                                <div class="alp-actions">
                                    <button type="button" class="alp-btn" @click="form = null">Cancel</button>
                                    <button type="submit" class="alp-btn is-primary" :disabled="saving" x-text="saving ? 'Saving...' : 'Save partner'"></button>
                                </div>
                            </div>
                        </template>
                    </form>
                </div>
            </section>

            <!-- Course Moderation Queue Section -->
            <div class="bg-white rounded-2xl border border-slate-200 admin-shelf-card">
                <!-- Tabs -->
                <div class="admin-shelf-header">
                    <div>
                        <h2 class="text-base sm:text-lg font-bold text-slate-900">Course Moderation Shelf</h2>
                        <p class="text-xs text-slate-500 mt-1">Review video modules, syllabuses, and interactive quiz answer keys</p>
                    </div>

                    <!-- Category Tabs with Clean Wrapped Indigo Badges & Zero Scrollbar Artifacts -->
                    <div class="admin-shelf-tabs">
                        <button type="button"
                                class="admin-shelf-tab"
                                :class="{ 'is-active': activeTab === 'pending' }"
                                @click="activeTab = 'pending'">
                            <span class="admin-shelf-tab-short">Submissions</span>
                            <span class="admin-shelf-tab-full">Submissions Awaiting Review</span>
                            <span class="admin-shelf-tab-count" x-text="pendingCourses.length"></span>
                        </button>
                        <button type="button"
                                class="admin-shelf-tab"
                                :class="{ 'is-active': activeTab === 'published' }"
                                @click="activeTab = 'published'">
                            <span class="admin-shelf-tab-short">Published</span>
                            <span class="admin-shelf-tab-full">Published Catalog</span>
                            <span class="admin-shelf-tab-count" x-text="publishedCourses.length"></span>
                        </button>
                    </div>
                </div>

                <!-- Submissions Awaiting Review Tab -->
                <div x-show="activeTab === 'pending'" class="space-y-4">
                    <template x-if="pendingCourses.length === 0">
                        <div class="py-16 text-center">
                            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 mx-auto flex items-center justify-center mb-3">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <h3 class="text-sm font-bold text-slate-900">Queue is clear!</h3>
                            <p class="text-xs text-slate-500 mt-1">All submitted mentor courses have been reviewed and published.</p>
                        </div>
                    </template>

                    <template x-for="course in pendingCourses" :key="course.id">
                        <div class="admin-course-row">
                            <div class="admin-course-info">
                                <div class="rounded-xl flex items-center justify-center font-extrabold text-xs flex-shrink-0"
                                     style="width: 44px; height: 44px; min-width: 44px; background: #EEF2FF; color: #4F48E6; border: 1px solid #E0E7FF;">
                                    <span x-text="course.initials"></span>
                                </div>
                                <div class="admin-course-body">
                                    <div class="admin-course-heading">
                                        <h3 class="admin-course-title" x-text="course.title"></h3>
                                        <span class="admin-tech-pill admin-course-track" x-text="course.track"></span>
                                    </div>
                                    <div class="admin-course-meta">
                                        <span class="admin-course-meta-item">Mentor: <strong x-text="course.mentor"></strong></span>
                                        <span class="admin-course-meta-item" x-text="course.modules + ' Modules (' + course.lessons + ' Lessons)'"></span>
                                        <span class="admin-course-meta-item" x-text="course.duration"></span>
                                        <span class="admin-course-meta-item" x-text="course.quizzes + ' Quizzes'"></span>
                                    </div>
                                </div>
                            </div>

                            <!-- Working Action Buttons Responsive Row -->
                            <div class="admin-course-actions">
                                <button type="button"
                                        @click="openRevisionModal(course)"
                                        class="admin-btn-dark-outline admin-course-action">
                                    Request Revision
                                </button>
                                <button type="button"
                                        @click="approveCourse(course)"
                                        class="admin-btn-indigo admin-course-action">
                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    <span>Approve &amp; Publish</span>
                                </button>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- Published Catalog Tab -->
                <div x-show="activeTab === 'published'" class="space-y-3" style="display: none;">
                    <template x-for="course in publishedCourses" :key="course.id">
                        <div class="rounded-2xl border border-slate-200 bg-slate-50/70 hover:bg-white hover:border-slate-300 transition-all flex items-center justify-between gap-4 mb-3"
                             style="padding: 20px 24px;">
                            <div class="admin-course-published-main">
                                <div class="rounded-xl flex items-center justify-center font-bold text-xs flex-shrink-0"
                                     style="width: 44px; height: 44px; min-width: 44px; background: #EEF2FF; color: #4F48E6; border: 1px solid #E0E7FF;"
                                     x-text="course.initials"></div>
                                <div class="admin-course-body">
                                    <div class="admin-course-title" x-text="course.title"></div>
                                    <div class="text-xs text-slate-500 mt-1" x-text="course.mentor + ' &middot; ' + course.enrolled + ' students enrolled'"></div>
                                </div>
                            </div>
                            <span class="admin-tech-pill admin-course-track flex-shrink-0">
                                <span class="admin-course-status-dot"></span>
                                <span>Published</span>
                            </span>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Standardized Request Revision Modal (Design System Compliant) -->
            <div x-show="showRevisionModal"
                 x-cloak
                 class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-[#000623]/60 backdrop-blur-sm"
                 style="display: none;"
                 role="dialog"
                 aria-modal="true"
                 @keydown.escape.window="showRevisionModal = false">
                
                <!-- Modal Dialog -->
                <div class="relative w-full max-w-xl bg-white rounded-3xl shadow-2xl border border-slate-200 overflow-hidden"
                     @click.outside="showRevisionModal = false">
                    
                    <!-- Header with 44px Icon Chip -->
                    <div class="border-b border-slate-100 flex items-start justify-between bg-slate-50/70"
                         style="padding: 24px 28px;">
                        <div class="flex items-start gap-4 min-w-0">
                            <div class="w-11 h-11 rounded-xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-[#4F48E6] flex-shrink-0 shadow-xs">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </div>
                            <div class="min-w-0">
                                <h3 class="text-base sm:text-lg font-bold text-slate-900 leading-tight">Request Course Revision</h3>
                                <p class="text-xs text-slate-500 mt-1 truncate" x-text="'Course: ' + (selectedCourse?.title || '')"></p>
                            </div>
                        </div>
                        <button type="button" 
                                class="text-slate-400 hover:text-slate-700 p-1.5 rounded-lg transition-colors cursor-pointer" 
                                @click="showRevisionModal = false"
                                aria-label="Close dialog">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    <!-- Modal Body with Generous Internal Spacing -->
                    <div class="space-y-6" style="padding: 28px 30px;">
                        <!-- Checklist Criteria with Explicit Admin Styling -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-3">Revision Action Items</label>
                            <div class="space-y-3 text-xs">
                                <label class="admin-revision-check-card"
                                       :class="{ 'is-checked': revisionChecklist.audio }">
                                    <input type="checkbox" x-model="revisionChecklist.audio" class="admin-revision-checkbox">
                                    <span class="admin-revision-check-label">Audio / Video resolution needs re-recording</span>
                                </label>
                                <label class="admin-revision-check-card"
                                       :class="{ 'is-checked': revisionChecklist.quiz }">
                                    <input type="checkbox" x-model="revisionChecklist.quiz" class="admin-revision-checkbox">
                                    <span class="admin-revision-check-label">Interactive quiz answers require key corrections</span>
                                </label>
                                <label class="admin-revision-check-card"
                                       :class="{ 'is-checked': revisionChecklist.syllabus }">
                                    <input type="checkbox" x-model="revisionChecklist.syllabus" class="admin-revision-checkbox">
                                    <span class="admin-revision-check-label">Syllabus missing project assignments or assets</span>
                                </label>
                            </div>
                        </div>

                        <!-- Feedback Notes with Dedicated Top Margin -->
                        <div style="margin-top: 24px;">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2.5">Staff Feedback Notes to Mentor</label>
                            <textarea x-model="revisionNotes"
                                      rows="3"
                                      placeholder="Provide specific module timestamps or instructions for the mentor..."
                                      style="padding: 14px 16px; min-height: 105px; border-radius: 12px; font-family: inherit; line-height: 1.5; box-sizing: border-box;"
                                      class="w-full text-xs rounded-xl border border-slate-200 focus:outline-none focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 leading-relaxed"></textarea>
                        </div>
                    </div>

                    <!-- Footer Actions with Generous Margins -->
                    <div class="border-t border-slate-100 flex items-center justify-end gap-3 bg-slate-50/60"
                         style="padding: 20px 28px;">
                        <button type="button" 
                                class="admin-btn-dark-outline cursor-pointer text-xs" 
                                style="padding: 8px 18px; border-radius: 10px; font-weight: 600;"
                                @click="showRevisionModal = false">Cancel</button>
                        <button type="button" 
                                class="admin-btn-indigo cursor-pointer text-xs flex items-center gap-1.5" 
                                style="padding: 8px 20px; border-radius: 10px; font-weight: 600;"
                                @click="submitRevision()">
                            <span>Dispatch Feedback</span>
                            <span class="text-sm">&rarr;</span>
                        </button>
                    </div>
                </div>
            </section>
        </main>
    </div>
</div>

<script>
// Learning partners card: edit details, services, visibility and logo (POST /admin/elearning/partners/{id}).
function adminLearningPartners(partners) {
    return {
        partners,
        form: null,
        logoFile: null,
        logoPreview: '',
        saving: false,
        error: '',
        edit(p) {
            this.form = JSON.parse(JSON.stringify(p));
            if (!Array.isArray(this.form.services)) this.form.services = [];
            this.logoFile = null;
            this.logoPreview = '';
            this.error = '';
        },
        pickLogo(e) {
            const file = e.target.files[0];
            if (!file) return;
            if (!/\.(png|jpe?g|webp)$/i.test(file.name) || file.size > 5 * 1024 * 1024) {
                this.error = 'Choose a PNG, JPG or WebP image under 5MB.';
                return;
            }
            this.error = '';
            this.logoFile = file;
            this.logoPreview = URL.createObjectURL(file);
        },
        async save() {
            if (!this.form || this.saving) return;
            this.saving = true;
            this.error = '';
            const fd = new FormData();
            ['name', 'tier', 'website', 'brand_color', 'about', 'hubs'].forEach((k) => fd.append(k, this.form[k] || ''));
            fd.append('services_json', JSON.stringify(this.form.services.filter((s) => (s.name || '').trim())));
            if (this.form.is_active) fd.append('is_active', '1');
            if (this.logoFile) fd.append('logo', this.logoFile);
            try {
                const res = await fetch(<?= json_encode(url('/admin/elearning/partners/')) ?> + this.form.id, { method: 'POST', headers: { 'Accept': 'application/json' }, body: fd });
                const json = await res.json().catch(() => ({}));
                if (!res.ok || !json.success) throw new Error(json.error || 'The partner could not be saved.');
                const i = this.partners.findIndex((p) => p.id === this.form.id);
                if (i >= 0) this.partners[i] = Object.assign({}, this.partners[i], json.partner);
                const name = this.form.name;
                this.form = null;
                if (typeof window.adminToast === 'function') window.adminToast('Partner saved', name + ' is updated on the E-Learning page.', null, 'success');
            } catch (err) {
                this.error = err.message;
            } finally {
                this.saving = false;
            }
        },
    };
}
function adminElearning() {
    return {
        activeTab: 'pending',
        showRevisionModal: false,
        selectedCourse: null,
        revisionNotes: '',
        revisionChecklist: {
            audio: false,
            quiz: true,
            syllabus: false
        },
        pendingCourses: [
            { id: 1, title: 'UX Research & Prototyping Basics', track: 'Product Design', trackColor: 'bg-indigo-50 text-indigo-700', mentor: 'Somto Chukwu', initials: 'UX', badgeColor: 'bg-indigo-100 text-indigo-700', modules: 6, lessons: 18, duration: '4h 30m', quizzes: 4 },
            { id: 2, title: 'Python for Backend REST APIs with FastAPI', track: 'Backend Engineering', trackColor: 'bg-blue-50 text-blue-700', mentor: 'Emeka Danladi', initials: 'PY', badgeColor: 'bg-blue-100 text-blue-700', modules: 8, lessons: 24, duration: '6h 15m', quizzes: 6 },
            { id: 3, title: 'Modern Frontend Development with Vue 3 & Tailwind', track: 'Frontend Engineering', trackColor: 'bg-emerald-50 text-emerald-700', mentor: 'Farida Olanrewaju', initials: 'FE', badgeColor: 'bg-emerald-100 text-emerald-700', modules: 5, lessons: 16, duration: '3h 50m', quizzes: 3 }
        ],
        publishedCourses: [
            { id: 101, title: 'Design Handoff & Developer Collaboration', mentor: 'Jayden Okafor', enrolled: 1240, initials: 'DH' },
            { id: 102, title: 'PostgreSQL Database Modeling & Optimization', mentor: 'Bolanle Alabi', enrolled: 890, initials: 'PG' },
            { id: 103, title: 'Product Analytics & User Telemetry', mentor: 'Tariq Bello', enrolled: 670, initials: 'PA' }
        ],
        openRevisionModal(course) {
            this.selectedCourse = course;
            this.revisionNotes = '';
            this.showRevisionModal = true;
        },
        submitRevision() {
            if (!this.selectedCourse) return;
            const title = this.selectedCourse.title;
            this.pendingCourses = this.pendingCourses.filter(c => c.id !== this.selectedCourse.id);
            this.showRevisionModal = false;
            if (typeof window.adminToast === 'function') {
                window.adminToast('Revision feedback dispatched', 'Mentor will receive instructions for ' + title, null, 'warning');
            } else {
                window.dispatchEvent(new CustomEvent('show-toast', { detail: { type: 'warning', text: 'Revision feedback dispatched for ' + title } }));
            }
        },
        approveCourse(course) {
            this.pendingCourses = this.pendingCourses.filter(c => c.id !== course.id);
            this.publishedCourses.unshift({
                id: course.id,
                title: course.title,
                mentor: course.mentor,
                enrolled: 1,
                initials: course.initials
            });
            if (typeof window.adminToast === 'function') {
                window.adminToast('Course approved & published', course.title + ' is now live in student catalog', null, 'success');
            } else {
                window.dispatchEvent(new CustomEvent('show-toast', { detail: { type: 'success', text: 'Course approved & published: ' + course.title } }));
            }
        },
        initCharts() {
            this.$nextTick(() => {
                // Learning hours chart
                const ctx = document.getElementById('admin-elearning-chart');
                if (ctx && typeof Chart !== 'undefined') {
                    new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: ['Wk 1', 'Wk 2', 'Wk 3', 'Wk 4', 'Wk 5', 'Wk 6', 'Wk 7', 'Wk 8'],
                            datasets: [
                                {
                                    label: 'Study Hours',
                                    data: [1200, 1540, 1890, 2300, 2800, 3400, 4100, 4920],
                                    borderColor: '#4F48E6',
                                    backgroundColor: 'rgba(79, 72, 230, 0.08)',
                                    fill: true,
                                    tension: 0.35,
                                    borderWidth: 2.5,
                                    pointRadius: 3
                                },
                                {
                                    label: 'Quizzes Completed',
                                    data: [320, 480, 610, 780, 950, 1180, 1420, 1740],
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

                // Track Distribution Histogram Bar Chart (Replaced Doughnut Chart)
                const histCtx = document.getElementById('admin-elearning-histogram');
                if (histCtx && typeof Chart !== 'undefined') {
                    new Chart(histCtx, {
                        type: 'bar',
                        data: {
                            labels: ['UI/UX Design', 'Backend APIs', 'Frontend Dev', 'Data Science'],
                            datasets: [{
                                label: 'Courses',
                                data: [18, 15, 9, 6],
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
                                        label: function(ctx) { return ctx.parsed.y + ' Courses published'; }
                                    }
                                }
                            },
                            scales: {
                                x: {
                                    grid: { display: false },
                                    ticks: { font: { size: 10.5, family: 'Inter' }, color: '#64748B' }
                                },
                                y: {
                                    ticks: {
                                        font: { size: 10, family: 'Inter' },
                                        color: '#94A3B8',
                                        stepSize: 5
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
        }
    };
}
</script>
