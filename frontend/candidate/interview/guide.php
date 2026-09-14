<?php
declare(strict_types=1);
/**
 * View: Candidate Interview Preparation Guide
 * Route: /candidate/interview/guide, /interview/guide
 * Layout: dashboard (Candidate Portal)
 * Matches Image 2 exact visual specifications and interactive accordion cards
 * 
 * @var array $candidate Candidate profile information
 */

$name      = $candidate['name'] ?? 'Sarah Peters';
$firstName = explode(' ', trim($name))[0];
$role      = $candidate['role'] ?? 'Student Applicant';
$avatar    = $candidate['avatar'] ?? null;
?>

<div class="db-shell" x-data="{
    openSections: {
        1: true,
        2: false,
        3: false,
        4: false,
        5: false
    },
    tasks: {
        1: { id: 1, section: 1, name: 'Research the company & role', desc: 'Learn about their products, culture and recent news.', done: true },
        2: { id: 2, section: 1, name: 'Verify timezone', desc: 'Confirm the interview time and adjust for your local time.', done: true },
        3: { id: 3, section: 1, name: 'Audio, video & environment check', desc: 'Test your camera, mic and internet connection.', done: true },
        4: { id: 4, section: 2, name: 'Prepare STAR answers', desc: 'Structure your responses with clarity and impact.', done: false },
        5: { id: 5, section: 2, name: 'Prepare 2-3 specific case studies', desc: 'Quantify metrics and explain technical trade-offs.', done: false },
        6: { id: 6, section: 3, name: 'Review portfolio / code', desc: 'Set up clean browser windows and practice screen sharing.', done: false },
        7: { id: 7, section: 3, name: 'Highlight design & architecture decisions', desc: 'Focus on reasons behind decisions rather than final mockups.', done: false },
        8: { id: 8, section: 4, name: 'Prepare questions for the interviewer', desc: 'Ask about mentorship, team dynamics, and 30-day goals.', done: false },
        9: { id: 9, section: 4, name: 'Team culture & velocity inquiries', desc: 'Understand engineering quality and sprint cycles.', done: false },
        10: { id: 10, section: 5, name: 'Plan post-interview follow-up', desc: 'Draft a personalized thank-you email referencing discussions.', done: false },
        11: { id: 11, section: 5, name: 'Track application status on InternConnect', desc: 'Monitor recruiter updates and offer alerts in real-time.', done: false }
    },
    toggleSection(sec) {
        this.openSections[sec] = !this.openSections[sec];
    },
    toggleTask(id) {
        if (this.tasks[id]) {
            this.tasks[id].done = !this.tasks[id].done;
        }
    },
    get completedTotal() {
        return Object.values(this.tasks).filter(t => t.done).length;
    },
    get totalTasks() {
        return Object.values(this.tasks).length;
    },
    get readinessPct() {
        return Math.round((this.completedTotal / this.totalTasks) * 100);
    },
    sectionDoneCount(sec) {
        return Object.values(this.tasks).filter(t => t.section === sec && t.done).length;
    },
    sectionTotalCount(sec) {
        return Object.values(this.tasks).filter(t => t.section === sec).length;
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

            <!-- Breadcrumb Navigation -->
            <div class="db-offer-breadcrumb" style="margin-bottom: 14px;">
                <a href="<?= url('/candidate/interview') ?>" class="db-offer-breadcrumb-link">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                    </svg>
                    <span>My Application</span>
                    <span style="color: #94A3B8; margin: 0 2px;">/</span>
                    <span style="color: #4F46E5; font-weight: 700;">Interview Preparation</span>
                </a>
            </div>

            <!-- Page Title Header Block -->
            <div class="db-prep-header">
                <h1 class="db-prep-title">Interview Preparation</h1>
                <p class="db-prep-subtitle">Get ready for your interview with practical steps, key topics and useful resources.</p>
            </div>

            <!-- Overview Readiness Stats Strip (Matching Image 2) -->
            <div class="db-prep-stats-strip">
                
                <!-- Left Block: Interview readiness & progress bar -->
                <div class="db-prep-readiness-left">
                    <div class="db-prep-readiness-icon">
                        <svg style="width: 20px; height: 20px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                            <circle cx="12" cy="12" r="9"/>
                            <circle cx="12" cy="12" r="5"/>
                            <circle cx="12" cy="12" r="1.5" fill="currentColor"/>
                        </svg>
                    </div>
                    <div class="db-prep-readiness-info">
                        <div class="db-prep-readiness-header">
                            <span class="db-prep-readiness-title">Interview readiness</span>
                            <span class="db-prep-readiness-pct" x-text="readinessPct + '%'">60%</span>
                        </div>
                        <div class="db-prep-progress-track">
                            <div class="db-prep-progress-fill" :style="'width: ' + readinessPct + '%'"></div>
                        </div>
                    </div>
                </div>

                <div class="db-prep-stat-divider"></div>

                <!-- Stat 1: 5 sections -->
                <div class="db-prep-stat-item">
                    <div class="db-prep-stat-icon-circle">
                        <svg style="width: 16px; height: 16px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                    </div>
                    <span>5 sections</span>
                </div>

                <div class="db-prep-stat-divider"></div>

                <!-- Stat 2: 7 checklist items -->
                <div class="db-prep-stat-item">
                    <div class="db-prep-stat-icon-circle">
                        <svg style="width: 16px; height: 16px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                        </svg>
                    </div>
                    <span>7 checklist items</span>
                </div>

                <div class="db-prep-stat-divider"></div>

                <!-- Stat 3: ~15 min read -->
                <div class="db-prep-stat-item">
                    <div class="db-prep-stat-icon-circle">
                        <svg style="width: 16px; height: 16px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <span>~15 min read</span>
                </div>

            </div>

            <!-- 2-Column Main Preparation Grid -->
            <div class="db-prep-grid">
                
                <!-- Main Accordions Column (Left) -->
                <div class="db-prep-col-main">

                    <!-- Section 01: Pre-Interview Preparation -->
                    <div class="db-prep-accordion-card">
                        <button type="button" class="db-prep-accordion-header" @click="toggleSection(1)">
                            <div class="db-prep-header-left">
                                <span class="db-prep-phase-num">01</span>
                                <div class="db-prep-phase-icon">
                                    <svg style="width: 16px; height: 16px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <div class="db-prep-header-titles">
                                    <h3 class="db-prep-card-title">Pre-Interview Preparation</h3>
                                    <p class="db-prep-card-subtitle">Set yourself up for success by handling the basics and getting organized.</p>
                                </div>
                            </div>
                            <div class="db-prep-header-right">
                                <span class="db-prep-badge-done" x-text="sectionDoneCount(1) + ' / ' + sectionTotalCount(1) + ' done'">3 / 3 done</span>
                                <svg class="db-prep-chevron" :style="openSections[1] ? 'transform: rotate(180deg);' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>
                        </button>

                        <div x-show="openSections[1]" x-collapse class="db-prep-accordion-body">
                            <!-- Task 1 -->
                            <div class="db-prep-task-row">
                                <div class="db-prep-task-left">
                                    <div class="db-prep-check-circle" :class="tasks[1].done ? 'is-done' : 'is-pending'" @click="toggleTask(1)">
                                        <template x-if="tasks[1].done">
                                            <svg style="width: 14px; height: 14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                        </template>
                                    </div>
                                    <div class="db-prep-task-titles">
                                        <div class="db-prep-task-name" :style="tasks[1].done ? 'color: #0F172A;' : ''">Research the company & role</div>
                                        <p class="db-prep-task-desc">Learn about their products, culture and recent news.</p>
                                    </div>
                                </div>
                                <div class="db-prep-task-action">
                                    <button type="button" :class="tasks[1].done ? 'db-prep-btn-done' : 'db-prep-btn-start'" @click="toggleTask(1)">
                                        <span x-text="tasks[1].done ? 'Done' : 'Start'">Done</span>
                                        <svg style="width: 12px; height: 12px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                                    </button>
                                </div>
                            </div>

                            <!-- Task 2 -->
                            <div class="db-prep-task-row">
                                <div class="db-prep-task-left">
                                    <div class="db-prep-check-circle" :class="tasks[2].done ? 'is-done' : 'is-pending'" @click="toggleTask(2)">
                                        <template x-if="tasks[2].done">
                                            <svg style="width: 14px; height: 14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                        </template>
                                    </div>
                                    <div class="db-prep-task-titles">
                                        <div class="db-prep-task-name">Verify timezone</div>
                                        <p class="db-prep-task-desc">Confirm the interview time and adjust for your local time.</p>
                                    </div>
                                </div>
                                <div class="db-prep-task-action">
                                    <button type="button" :class="tasks[2].done ? 'db-prep-btn-done' : 'db-prep-btn-start'" @click="toggleTask(2)">
                                        <span x-text="tasks[2].done ? 'Done' : 'Start'">Done</span>
                                        <svg style="width: 12px; height: 12px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                                    </button>
                                </div>
                            </div>

                            <!-- Task 3 -->
                            <div class="db-prep-task-row">
                                <div class="db-prep-task-left">
                                    <div class="db-prep-check-circle" :class="tasks[3].done ? 'is-done' : 'is-pending'" @click="toggleTask(3)">
                                        <template x-if="tasks[3].done">
                                            <svg style="width: 14px; height: 14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                        </template>
                                    </div>
                                    <div class="db-prep-task-titles">
                                        <div class="db-prep-task-name">Audio, video & environment check</div>
                                        <p class="db-prep-task-desc">Test your camera, mic and internet connection.</p>
                                    </div>
                                </div>
                                <div class="db-prep-task-action">
                                    <button type="button" :class="tasks[3].done ? 'db-prep-btn-done' : 'db-prep-btn-start'" @click="toggleTask(3)">
                                        <span x-text="tasks[3].done ? 'Done' : 'Start'">Done</span>
                                        <svg style="width: 12px; height: 12px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Section 02: Answering Questions with the STAR Framework -->
                    <div class="db-prep-accordion-card">
                        <button type="button" class="db-prep-accordion-header" @click="toggleSection(2)">
                            <div class="db-prep-header-left">
                                <span class="db-prep-phase-num">02</span>
                                <div class="db-prep-phase-icon">
                                    <svg style="width: 16px; height: 16px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                    </svg>
                                </div>
                                <div class="db-prep-header-titles">
                                    <h3 class="db-prep-card-title">Answering Questions with the STAR Framework</h3>
                                    <p class="db-prep-card-subtitle">Structure your responses with clarity and impact.</p>
                                </div>
                            </div>
                            <div class="db-prep-header-right">
                                <span class="db-prep-badge-done" x-text="sectionDoneCount(2) + ' / ' + sectionTotalCount(2) + ' done'">1 / 2 done</span>
                                <svg class="db-prep-chevron" :style="openSections[2] ? 'transform: rotate(180deg);' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>
                        </button>

                        <div x-show="openSections[2]" x-collapse class="db-prep-accordion-body">
                            <!-- Task 4 -->
                            <div class="db-prep-task-row">
                                <div class="db-prep-task-left">
                                    <div class="db-prep-check-circle" :class="tasks[4].done ? 'is-done' : 'is-pending'" @click="toggleTask(4)">
                                        <template x-if="tasks[4].done">
                                            <svg style="width: 14px; height: 14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                        </template>
                                    </div>
                                    <div class="db-prep-task-titles">
                                        <div class="db-prep-task-name">Prepare STAR answers</div>
                                        <p class="db-prep-task-desc">Structure behavioral stories around Situation, Task, Action, and Result.</p>
                                    </div>
                                </div>
                                <div class="db-prep-task-action">
                                    <button type="button" :class="tasks[4].done ? 'db-prep-btn-done' : 'db-prep-btn-start'" @click="toggleTask(4)">
                                        <span x-text="tasks[4].done ? 'Done' : 'Start'">Start</span>
                                        <svg style="width: 12px; height: 12px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                                    </button>
                                </div>
                            </div>

                            <!-- Task 5 -->
                            <div class="db-prep-task-row">
                                <div class="db-prep-task-left">
                                    <div class="db-prep-check-circle" :class="tasks[5].done ? 'is-done' : 'is-pending'" @click="toggleTask(5)">
                                        <template x-if="tasks[5].done">
                                            <svg style="width: 14px; height: 14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                        </template>
                                    </div>
                                    <div class="db-prep-task-titles">
                                        <div class="db-prep-task-name">Prepare 2-3 specific case studies</div>
                                        <p class="db-prep-task-desc">Quantify metrics and explain technical trade-offs.</p>
                                    </div>
                                </div>
                                <div class="db-prep-task-action">
                                    <button type="button" :class="tasks[5].done ? 'db-prep-btn-done' : 'db-prep-btn-start'" @click="toggleTask(5)">
                                        <span x-text="tasks[5].done ? 'Done' : 'Start'">Start</span>
                                        <svg style="width: 12px; height: 12px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Section 03: Presenting Your Portfolio or Code -->
                    <div class="db-prep-accordion-card">
                        <button type="button" class="db-prep-accordion-header" @click="toggleSection(3)">
                            <div class="db-prep-header-left">
                                <span class="db-prep-phase-num">03</span>
                                <div class="db-prep-phase-icon">
                                    <svg style="width: 16px; height: 16px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>
                                    </svg>
                                </div>
                                <div class="db-prep-header-titles">
                                    <h3 class="db-prep-card-title">Presenting Your Portfolio or Code</h3>
                                    <p class="db-prep-card-subtitle">Showcase your work and technical skills with confidence.</p>
                                </div>
                            </div>
                            <div class="db-prep-header-right">
                                <span class="db-prep-badge-done" x-text="sectionDoneCount(3) + ' / ' + sectionTotalCount(3) + ' done'">0 / 2 done</span>
                                <svg class="db-prep-chevron" :style="openSections[3] ? 'transform: rotate(180deg);' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>
                        </button>

                        <div x-show="openSections[3]" x-collapse class="db-prep-accordion-body">
                            <!-- Task 6 -->
                            <div class="db-prep-task-row">
                                <div class="db-prep-task-left">
                                    <div class="db-prep-check-circle" :class="tasks[6].done ? 'is-done' : 'is-pending'" @click="toggleTask(6)">
                                        <template x-if="tasks[6].done">
                                            <svg style="width: 14px; height: 14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                        </template>
                                    </div>
                                    <div class="db-prep-task-titles">
                                        <div class="db-prep-task-name">Review portfolio / code</div>
                                        <p class="db-prep-task-desc">Set up clean browser windows and practice screen sharing.</p>
                                    </div>
                                </div>
                                <div class="db-prep-task-action">
                                    <button type="button" :class="tasks[6].done ? 'db-prep-btn-done' : 'db-prep-btn-start'" @click="toggleTask(6)">
                                        <span x-text="tasks[6].done ? 'Done' : 'Start'">Start</span>
                                        <svg style="width: 12px; height: 12px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                                    </button>
                                </div>
                            </div>

                            <!-- Task 7 -->
                            <div class="db-prep-task-row">
                                <div class="db-prep-task-left">
                                    <div class="db-prep-check-circle" :class="tasks[7].done ? 'is-done' : 'is-pending'" @click="toggleTask(7)">
                                        <template x-if="tasks[7].done">
                                            <svg style="width: 14px; height: 14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                        </template>
                                    </div>
                                    <div class="db-prep-task-titles">
                                        <div class="db-prep-task-name">Highlight design & architecture decisions</div>
                                        <p class="db-prep-task-desc">Focus on reasons behind decisions rather than final mockups.</p>
                                    </div>
                                </div>
                                <div class="db-prep-task-action">
                                    <button type="button" :class="tasks[7].done ? 'db-prep-btn-done' : 'db-prep-btn-start'" @click="toggleTask(7)">
                                        <span x-text="tasks[7].done ? 'Done' : 'Start'">Start</span>
                                        <svg style="width: 12px; height: 12px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Section 04: Smart Questions to Ask the Interviewer -->
                    <div class="db-prep-accordion-card">
                        <button type="button" class="db-prep-accordion-header" @click="toggleSection(4)">
                            <div class="db-prep-header-left">
                                <span class="db-prep-phase-num">04</span>
                                <div class="db-prep-phase-icon">
                                    <svg style="width: 16px; height: 16px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                    </svg>
                                </div>
                                <div class="db-prep-header-titles">
                                    <h3 class="db-prep-card-title">Smart Questions to Ask the Interviewer</h3>
                                    <p class="db-prep-card-subtitle">Show your curiosity and learn about the role and team.</p>
                                </div>
                            </div>
                            <div class="db-prep-header-right">
                                <span class="db-prep-badge-done" x-text="sectionDoneCount(4) + ' / ' + sectionTotalCount(4) + ' done'">0 / 2 done</span>
                                <svg class="db-prep-chevron" :style="openSections[4] ? 'transform: rotate(180deg);' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>
                        </button>

                        <div x-show="openSections[4]" x-collapse class="db-prep-accordion-body">
                            <!-- Task 8 -->
                            <div class="db-prep-task-row">
                                <div class="db-prep-task-left">
                                    <div class="db-prep-check-circle" :class="tasks[8].done ? 'is-done' : 'is-pending'" @click="toggleTask(8)">
                                        <template x-if="tasks[8].done">
                                            <svg style="width: 14px; height: 14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                        </template>
                                    </div>
                                    <div class="db-prep-task-titles">
                                        <div class="db-prep-task-name">Prepare questions for the interviewer</div>
                                        <p class="db-prep-task-desc">Ask about mentorship, team dynamics, and 30-day goals.</p>
                                    </div>
                                </div>
                                <div class="db-prep-task-action">
                                    <button type="button" :class="tasks[8].done ? 'db-prep-btn-done' : 'db-prep-btn-start'" @click="toggleTask(8)">
                                        <span x-text="tasks[8].done ? 'Done' : 'Start'">Start</span>
                                        <svg style="width: 12px; height: 12px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                                    </button>
                                </div>
                            </div>

                            <!-- Task 9 -->
                            <div class="db-prep-task-row">
                                <div class="db-prep-task-left">
                                    <div class="db-prep-check-circle" :class="tasks[9].done ? 'is-done' : 'is-pending'" @click="toggleTask(9)">
                                        <template x-if="tasks[9].done">
                                            <svg style="width: 14px; height: 14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                        </template>
                                    </div>
                                    <div class="db-prep-task-titles">
                                        <div class="db-prep-task-name">Team culture & velocity inquiries</div>
                                        <p class="db-prep-task-desc">Understand engineering quality and sprint cycles.</p>
                                    </div>
                                </div>
                                <div class="db-prep-task-action">
                                    <button type="button" :class="tasks[9].done ? 'db-prep-btn-done' : 'db-prep-btn-start'" @click="toggleTask(9)">
                                        <span x-text="tasks[9].done ? 'Done' : 'Start'">Start</span>
                                        <svg style="width: 12px; height: 12px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Section 05: Post-Interview Follow-Up -->
                    <div class="db-prep-accordion-card">
                        <button type="button" class="db-prep-accordion-header" @click="toggleSection(5)">
                            <div class="db-prep-header-left">
                                <span class="db-prep-phase-num">05</span>
                                <div class="db-prep-phase-icon">
                                    <svg style="width: 16px; height: 16px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <div class="db-prep-header-titles">
                                    <h3 class="db-prep-card-title">Post-Interview Follow-Up</h3>
                                    <p class="db-prep-card-subtitle">Leave a lasting impression and keep the momentum going.</p>
                                </div>
                            </div>
                            <div class="db-prep-header-right">
                                <span class="db-prep-badge-done" x-text="sectionDoneCount(5) + ' / ' + sectionTotalCount(5) + ' done'">0 / 2 done</span>
                                <svg class="db-prep-chevron" :style="openSections[5] ? 'transform: rotate(180deg);' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>
                        </button>

                        <div x-show="openSections[5]" x-collapse class="db-prep-accordion-body">
                            <!-- Task 10 -->
                            <div class="db-prep-task-row">
                                <div class="db-prep-task-left">
                                    <div class="db-prep-check-circle" :class="tasks[10].done ? 'is-done' : 'is-pending'" @click="toggleTask(10)">
                                        <template x-if="tasks[10].done">
                                            <svg style="width: 14px; height: 14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                        </template>
                                    </div>
                                    <div class="db-prep-task-titles">
                                        <div class="db-prep-task-name">Plan post-interview follow-up</div>
                                        <p class="db-prep-task-desc">Draft a personalized thank-you note referencing discussions.</p>
                                    </div>
                                </div>
                                <div class="db-prep-task-action">
                                    <button type="button" :class="tasks[10].done ? 'db-prep-btn-done' : 'db-prep-btn-start'" @click="toggleTask(10)">
                                        <span x-text="tasks[10].done ? 'Done' : 'Start'">Start</span>
                                        <svg style="width: 12px; height: 12px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                                    </button>
                                </div>
                            </div>

                            <!-- Task 11 -->
                            <div class="db-prep-task-row">
                                <div class="db-prep-task-left">
                                    <div class="db-prep-check-circle" :class="tasks[11].done ? 'is-done' : 'is-pending'" @click="toggleTask(11)">
                                        <template x-if="tasks[11].done">
                                            <svg style="width: 14px; height: 14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                        </template>
                                    </div>
                                    <div class="db-prep-task-titles">
                                        <div class="db-prep-task-name">Track application status on InternConnect</div>
                                        <p class="db-prep-task-desc">Monitor evaluation updates and offer alerts in real-time.</p>
                                    </div>
                                </div>
                                <div class="db-prep-task-action">
                                    <button type="button" :class="tasks[11].done ? 'db-prep-btn-done' : 'db-prep-btn-start'" @click="toggleTask(11)">
                                        <span x-text="tasks[11].done ? 'Done' : 'Start'">Start</span>
                                        <svg style="width: 12px; height: 12px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Right Sidebar Widgets Column -->
                <div class="db-prep-col-sidebar">
                    
                    <!-- Widget 1: Interview Readiness Checklist -->
                    <div class="db-prep-sidebar-card">
                        <div class="db-prep-side-header">
                            <div class="db-prep-side-icon-circle">
                                <svg style="width: 18px; height: 18px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                </svg>
                            </div>
                            <div class="db-prep-side-titles">
                                <h3 class="db-prep-side-title">Interview Readiness</h3>
                                <p class="db-prep-side-sub">Complete the checklist to be fully prepared.</p>
                            </div>
                        </div>

                        <!-- Progress Bar with Counter -->
                        <div class="db-prep-side-progress-wrap">
                            <div class="db-prep-side-progress-bar">
                                <div class="db-prep-progress-track" style="height: 6px; flex: 1;">
                                    <div class="db-prep-progress-fill" :style="'width: ' + ((completedTotal / totalTasks) * 100) + '%'"></div>
                                </div>
                                <span style="font-size: 11.5px; font-weight: 800; color: #64748B;" x-text="completedTotal + ' / ' + totalTasks">3 / 7</span>
                            </div>
                        </div>

                        <!-- Interactive Checkboxes (Synced with Tasks) -->
                        <div class="db-prep-side-items-stack">
                            <label class="db-prep-side-item" @click.prevent="toggleTask(1)">
                                <input type="checkbox" :checked="tasks[1].done" readonly>
                                <span :style="tasks[1].done ? 'color: #0F172A; font-weight: 700;' : ''">Research the company & role</span>
                            </label>

                            <label class="db-prep-side-item" @click.prevent="toggleTask(2)">
                                <input type="checkbox" :checked="tasks[2].done" readonly>
                                <span :style="tasks[2].done ? 'color: #0F172A; font-weight: 700;' : ''">Verify timezone</span>
                            </label>

                            <label class="db-prep-side-item" @click.prevent="toggleTask(3)">
                                <input type="checkbox" :checked="tasks[3].done" readonly>
                                <span :style="tasks[3].done ? 'color: #0F172A; font-weight: 700;' : ''">Audio, video & environment check</span>
                            </label>

                            <label class="db-prep-side-item" @click.prevent="toggleTask(4)">
                                <input type="checkbox" :checked="tasks[4].done" readonly>
                                <span :style="tasks[4].done ? 'color: #0F172A; font-weight: 700;' : ''">Prepare STAR answers</span>
                            </label>

                            <label class="db-prep-side-item" @click.prevent="toggleTask(6)">
                                <input type="checkbox" :checked="tasks[6].done" readonly>
                                <span :style="tasks[6].done ? 'color: #0F172A; font-weight: 700;' : ''">Review portfolio / code</span>
                            </label>

                            <label class="db-prep-side-item" @click.prevent="toggleTask(8)">
                                <input type="checkbox" :checked="tasks[8].done" readonly>
                                <span :style="tasks[8].done ? 'color: #0F172A; font-weight: 700;' : ''">Prepare questions for the interviewer</span>
                            </label>

                            <label class="db-prep-side-item" @click.prevent="toggleTask(10)">
                                <input type="checkbox" :checked="tasks[10].done" readonly>
                                <span :style="tasks[10].done ? 'color: #0F172A; font-weight: 700;' : ''">Plan post-interview follow-up</span>
                            </label>
                        </div>
                    </div>

                    <!-- Widget 2: Quick Tips -->
                    <div class="db-prep-sidebar-card">
                        <div class="db-prep-side-header">
                            <div class="db-prep-side-icon-circle" style="background: #EEF2FF; color: #4F46E5;">
                                <svg style="width: 18px; height: 18px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                                </svg>
                            </div>
                            <div class="db-prep-side-titles">
                                <h3 class="db-prep-side-title">Quick tips</h3>
                            </div>
                        </div>

                        <div class="db-prep-tips-stack">
                            <div class="db-prep-tip-item">
                                <div class="db-prep-tip-icon">
                                    <svg style="width: 11px; height: 11px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                                <div>
                                    <strong style="color: #1E293B; font-weight: 700;">Be specific with your examples</strong>
                                    <div style="color: #64748B; font-size: 11.5px; margin-top: 1px;">(Use numbers and results)</div>
                                </div>
                            </div>

                            <div class="db-prep-tip-item">
                                <div class="db-prep-tip-icon">
                                    <svg style="width: 11px; height: 11px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                                <div>
                                    <strong style="color: #1E293B; font-weight: 700;">Keep your answers concise</strong>
                                    <div style="color: #64748B; font-size: 11.5px; margin-top: 1px;">(1-2 minutes per question)</div>
                                </div>
                            </div>

                            <div class="db-prep-tip-item">
                                <div class="db-prep-tip-icon">
                                    <svg style="width: 11px; height: 11px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                                <div>
                                    <strong style="color: #1E293B; font-weight: 700;">Show enthusiasm and genuine interest</strong>
                                    <div style="color: #64748B; font-size: 11.5px; margin-top: 1px;">in the role and mission</div>
                                </div>
                            </div>

                            <div class="db-prep-tip-item">
                                <div class="db-prep-tip-icon">
                                    <svg style="width: 11px; height: 11px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                                <div>
                                    <strong style="color: #1E293B; font-weight: 700;">Be yourself</strong>
                                    <span style="color: #64748B;">— authenticity goes a long way.</span>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>

        </div><!-- /.db-content -->

        <!-- Dashboard Footer -->
        <?php include __DIR__ . '/../../shared/components/db-footer.php'; ?>

    </div><!-- /.db-main -->

</div>
