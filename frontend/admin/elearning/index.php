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
