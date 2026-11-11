<?php
declare(strict_types=1);
/**
 * View: Interview Scheduler (Employer Portal)
 * Route: /employer/interviews/schedule/{id} or /employer/interviews/schedule
 * Layout: dashboard
 *
 * Exact 1:1 match with Interview Scheduler (2).png and (1).png
 * Features:
 * - Header with title & subtitle
 * - Candidate summary header bar with Virtual / Physical toggle
 * - Interview Details (Date, Time, Duration, Virtual Meeting Link, Notes)
 * - AI Scheduling Assistant card with Jenny AI mascot avatar and Auto Schedule action
 * - Communication card with interactive checkboxes and dynamic Summary
 * - Animated Success Checkmark Modal with ripple effect and auto-redirect countdown
 *
 * @var array  $employer  Employer profile data
 * @var array  $candidate Candidate application data
 * @var string $activeNav Active navigation identifier
 */
?>

<link rel="stylesheet" href="<?= asset('assets/css/employer-candidates.css') ?>?v=<?= time() ?>">

<style>
    /* Cloudinary Graphic Tinting for AI Suggested Time Box */
    .emp-ai-suggest-box {
        background: linear-gradient(135deg, #F8FAFC 55%, #EEF2FF 100%) !important;
        border-radius: 14px !important;
        border: 1px solid #E2E8F0 !important;
        padding: 18px !important;
        margin-bottom: 14px !important;
        position: relative !important;
        overflow: hidden !important;
    }
    .emp-ai-suggest-box::after {
        content: '' !important;
        position: absolute !important;
        top: 0 !important;
        right: 0 !important;
        bottom: 0 !important;
        width: 52% !important;
        background-image: url('https://res.cloudinary.com/weburea/image/upload/v1790982394/inter_1_ikbjo1.png') !important;
        background-repeat: no-repeat !important;
        background-position: right center !important;
        background-size: cover !important;
        opacity: 0.22 !important;
        mix-blend-mode: multiply !important;
        pointer-events: none !important;
        z-index: 1 !important;
    }
    .emp-ai-suggest-box > * {
        position: relative !important;
        z-index: 2 !important;
    }
</style>

<style>
.emp-sched-modal-secondary { display: inline-flex; align-items: center; justify-content: center; height: 40px; padding: 0 16px; border-radius: 10px; border: 1px solid #E2E8F0; background: #FFFFFF; color: #334155; font-size: 13px; font-weight: 600; text-decoration: none; cursor: pointer; white-space: nowrap; flex: 1 1 140px; }
.emp-sched-modal-secondary:hover { background: #F8FAFC; border-color: #CBD5E1; color: #0F172A; }
</style>
<div class="db-shell">

    <!-- ══ EMPLOYER SIDEBAR ══ -->
    <?php
    $activeNav = 'interviews';
    include dirname(__DIR__, 2) . '/shared/components/employer-sidebar.php';
    ?>

    <!-- ══ MAIN CONTENT WRAPPER ══ -->
    <div class="db-main">

        <!-- ══ TOP NAVBAR ══ -->
        <?php include dirname(__DIR__, 2) . '/shared/components/employer-topnav.php'; ?>

        <!-- ══ PAGE CONTENT ══ -->
        <div class="db-content">
            <div class="emp-cand-page"
                 x-data="interviewSchedulerManager(<?= htmlspecialchars(json_encode($candidate), ENT_QUOTES, 'UTF-8') ?>)">

                <!-- ── 0. Breadcrumbs ── -->
                <div class="emp-breadcrumbs" style="font-size: 13.5px; color: #64748B; margin-bottom: 16px; display: flex; align-items: center; gap: 8px;">
                    <a href="<?= url('/employer/interviews') ?>" style="color: #4F48E6; text-decoration: none; font-weight: 500;">Candidates</a>
                    <span style="color: #94A3B8;">/</span>
                    <a :href="'<?= url('/employer/candidate/') ?>' + candidate.id" style="color: #64748B; text-decoration: none; font-weight: 500;">Profile information</a>
                    <span style="color: #94A3B8;">/</span>
                    <span style="color: #0F172A; font-weight: 600;">Schedule interview</span>
                </div>

                <!-- ── 1. Page Header ── -->
                <div class="emp-sched-header">
                    <h1 class="emp-sched-title">Schedule Interview</h1>
                    <p class="emp-sched-subtitle">Coordinate interviews efficiently with AI assistance.</p>
                </div>

                <!-- ── 2. Candidate Header Card with Virtual / Physical Switcher ── -->
                <div class="emp-sched-cand-card">
                    <div class="emp-sched-cand-info">
                        <div class="emp-sched-avatar">
                            <img :src="candidate.avatar || 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=160&h=160&fit=crop&crop=faces'"
                                 :alt="candidate.name">
                        </div>
                        <div class="emp-sched-cand-meta">
                            <div class="emp-sched-name-row">
                                <span class="emp-sched-cand-name" x-text="candidate.name">Sarah Peters</span>
                                <span class="emp-status-pill emp-status-shortlisted" x-text="candidate.status || 'Shortlisted'">Shortlisted</span>
                            </div>
                            <div class="emp-sched-cand-role" x-text="candidate.role || 'Frontend Developer'">Frontend Developer</div>
                            <div class="emp-sched-match-tag">
                                <svg style="width: 14px; height: 14px; flex-shrink: 0;" viewBox="0 0 74 83" fill="currentColor">
                                    <path d="M73.74 32.4627V59.9427C67.93 59.7673 62.2767 61.8422 57.9596 65.7344C53.6426 69.6267 50.9952 75.0356 50.57 80.8327C50.52 81.3027 50.48 81.8327 50.46 82.2827H23.57C23.57 82.0227 23.48 81.7627 23.43 81.5127C20.73 67.1427 16.41 63.2427 0 59.6527V32.6527C13.34 31.5727 27.12 37.6527 36.92 47.9327C46 38.3527 58.99 31.8927 73.74 32.4627Z"/>
                                    <path d="M37.0001 0.0031237C40.4833 0.0367014 43.8785 1.10075 46.7578 3.06112C49.637 5.02149 51.8715 7.79043 53.1794 11.0188C54.4873 14.2473 54.8101 17.7906 54.1071 21.2023C53.4042 24.6139 51.7069 27.741 49.2293 30.1894C46.7516 32.6378 43.6046 34.2979 40.1849 34.9604C36.7652 35.6228 33.2259 35.258 30.0132 33.9119C26.8005 32.5658 24.0583 30.2987 22.1322 27.3963C20.2061 24.494 19.1824 21.0864 19.1901 17.6031C19.1737 15.2695 19.6247 12.9562 20.5166 10.7997C21.4084 8.64315 22.7231 6.68703 24.383 5.04669C26.0429 3.40635 28.0145 2.11499 30.1814 1.24877C32.3484 0.382549 34.6669 -0.040991 37.0001 0.0031237Z"/>
                                </svg>
                                <span>Match Score: <strong x-text="candidate.match_score + '%'">92%</strong></span>
                            </div>
                        </div>
                    </div>

                    <!-- Virtual / Physical Mode Switcher -->
                    <div class="emp-sched-mode-switcher" role="radiogroup" aria-label="Meeting Format">
                        <button type="button"
                                class="emp-sched-mode-btn"
                                :class="{ 'is-active': meetingType === 'virtual' }"
                                @click="meetingType = 'virtual'"
                                role="radio"
                                :aria-checked="meetingType === 'virtual'">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                            </svg>
                            <span>Virtual</span>
                        </button>

                        <button type="button"
                                class="emp-sched-mode-btn"
                                :class="{ 'is-active': meetingType === 'physical' }"
                                @click="meetingType = 'physical'"
                                role="radio"
                                :aria-checked="meetingType === 'physical'">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span>Physical</span>
                        </button>
                    </div>
                </div>

                <!-- ── 3. Two-Column Layout ── -->
                <div class="emp-sched-grid">

                    <!-- ════ LEFT COLUMN: Interview Details + Setup ════ -->
                    <div style="display: flex; flex-direction: column; gap: 20px; min-width: 0;">
                    <div class="emp-card">
                        <h2 class="emp-card-title" style="margin-bottom: 20px;">Interview Details</h2>

                        <form @submit.prevent="confirmSchedule()">
                            <!-- Row 1: Date & Time -->
                            <div class="emp-form-row-2">
                                <!-- Date Input -->
                                <div class="emp-form-group">
                                    <label class="emp-form-label" for="sched-date">Date</label>
                                    <div class="emp-input-icon-wrap">
                                        <svg class="emp-input-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <input type="date"
                                               id="sched-date"
                                               x-model="interviewDate"
                                               :min="todayIso"
                                               class="emp-input"
                                               required>
                                    </div>
                                </div>

                                <!-- Time Input -->
                                <div class="emp-form-group">
                                    <label class="emp-form-label" for="sched-time">Time</label>
                                    <div class="emp-input-icon-wrap">
                                        <svg class="emp-input-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <input type="time"
                                               id="sched-time"
                                               x-model="interviewTime"
                                               step="300"
                                               class="emp-input"
                                               required>
                                    </div>
                                </div>
                            </div>

                            <!-- Row 2: Duration & Meeting Link -->
                            <div class="emp-form-row-2">
                                <!-- Duration Input / Dropdown -->
                                <div class="emp-form-group">
                                    <label class="emp-form-label" for="sched-duration">Duration</label>
                                    <div class="emp-drop-wrap" style="width: 100%;" @click.outside="durationOpen = false">
                                        <button type="button"
                                                id="sched-duration"
                                                class="emp-drop-btn"
                                                style="width: 100%;"
                                                :class="{ 'is-active': durationOpen }"
                                                @click="durationOpen = !durationOpen">
                                            <span x-text="duration">30 Minutes</span>
                                            <svg class="emp-drop-chevron" :class="{ 'rotate': durationOpen }" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                            </svg>
                                        </button>

                                        <div x-show="durationOpen" class="emp-drop-menu" style="width: 100%; display: none;">
                                            <template x-for="d in ['30 Minutes', '45 Minutes', '60 Minutes', '90 Minutes']" :key="d">
                                                <button type="button"
                                                        class="emp-drop-item"
                                                        :class="{ 'selected': duration === d }"
                                                        @click="duration = d; durationOpen = false">
                                                    <span x-text="d"></span>
                                                    <svg x-show="duration === d" style="width: 14px; height: 14px; color: #4F48E6;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                </button>
                                            </template>
                                        </div>
                                    </div>
                                </div>

                                <!-- Meeting Link (Virtual) / Location (Physical) -->
                                <div class="emp-form-group">
                                    <label class="emp-form-label" for="sched-link" x-text="meetingType === 'virtual' ? 'Meeting Link' : 'Office Location'">Meeting Link</label>
                                    <div class="emp-input-icon-wrap">
                                        <template x-if="meetingType === 'virtual'">
                                            <svg class="emp-input-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                                            </svg>
                                        </template>
                                        <template x-if="meetingType === 'physical'">
                                            <svg class="emp-input-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                        </template>
                                        <input type="text"
                                               id="sched-link"
                                               x-show="meetingType === 'physical'"
                                               x-model="meetingLink"
                                               class="emp-input"
                                               placeholder="TechFlow HQ, 4th Floor, Victoria Island">
                                        <input type="text"
                                               x-show="meetingType === 'virtual'"
                                               value="InternConnect video room"
                                               class="emp-input"
                                               readonly
                                               aria-label="InternConnect video room link"
                                               style="background: #F8FAFC; color: #4F48E6; font-weight: 600;">
                                    </div>
                                    <p x-show="meetingType === 'virtual'" style="font-size: 11.5px; color: #64748B; margin: 6px 0 0; line-height: 1.45;">
                                        Created automatically. You and <span x-text="candidate.name"></span> meet inside InternConnect, no Google Meet or Zoom needed.
                                    </p>
                                </div>
                            </div>

                            <!-- Notes for Candidate Textarea -->
                            <div class="emp-form-group">
                                <label class="emp-form-label" for="sched-notes">Notes for Candidate (Optional)</label>
                                <textarea id="sched-notes"
                                          x-model="notes"
                                          class="emp-textarea"
                                          placeholder="Any specific instructions or topics to prepare..."></textarea>
                            </div>
                        </form>
                    </div>

                    <!-- Interview setup: how this employer evaluates (drives the in-call scorecard) -->
                    <div class="emp-card">
                        <div class="isu-head">
                            <div>
                                <h2 class="emp-card-title" style="margin: 0;">Interview setup</h2>
                                <p class="isu-sub">What you rate, how you decide and what you will ask. It appears in your scorecard during the call.</p>
                            </div>
                        </div>

                        <div class="isu-block">
                            <div class="isu-label-row">
                                <span class="emp-form-label" style="margin: 0;">Rating criteria</span>
                                <button type="button" class="isu-link" @click="resetCriteria()">Use defaults</button>
                            </div>
                            <div class="isu-chips">
                                <template x-for="(c, i) in criteria" :key="c + i">
                                    <span class="isu-chip">
                                        <span x-text="c"></span>
                                        <button type="button" @click="criteria.splice(i, 1)" :aria-label="'Remove ' + c" x-show="criteria.length > 1">
                                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                        </button>
                                    </span>
                                </template>
                            </div>
                            <div class="isu-add" x-show="criteria.length < 8">
                                <input type="text" class="emp-input" x-model="newCriterion" maxlength="60" placeholder="Add a criterion, e.g. Attention to detail" @keydown.enter.prevent="addCriterion()" aria-label="New rating criterion">
                                <button type="button" class="isu-btn" @click="addCriterion()" :disabled="!newCriterion.trim()">Add</button>
                            </div>
                            <p class="isu-hint">Each criterion is rated 1 to 5 stars. Up to 8.</p>
                        </div>

                        <div class="isu-block">
                            <span class="emp-form-label">Decision options</span>
                            <div class="isu-presets" role="radiogroup" aria-label="Decision options">
                                <template x-for="p in recPresets" :key="p.key">
                                    <button type="button" role="radio" class="isu-preset" :class="{ 'is-on': recPreset === p.key }" :aria-checked="recPreset === p.key" @click="recPreset = p.key">
                                        <strong x-text="p.label"></strong>
                                        <span x-text="p.options.map(o => o.label).join(' · ')"></span>
                                    </button>
                                </template>
                            </div>
                        </div>

                        <div class="isu-block">
                            <div class="isu-label-row">
                                <span class="emp-form-label" style="margin: 0;">Your questions</span>
                                <button type="button" class="isu-jenny" @click="suggestQuestions()" :disabled="suggesting">
                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                                    <span x-text="suggesting ? 'Jenny is thinking...' : 'Suggest with Jenny'"></span>
                                </button>
                            </div>
                            <ol class="isu-questions" x-show="questions.length">
                                <template x-for="(q, i) in questions" :key="i">
                                    <li>
                                        <span class="isu-qnum" x-text="i + 1"></span>
                                        <span class="isu-qbody">
                                            <span x-text="q.question"></span>
                                            <small x-show="q.hint" x-text="'Listen for: ' + q.hint"></small>
                                        </span>
                                        <button type="button" class="isu-remove" @click="questions.splice(i, 1)" :aria-label="'Remove question ' + (i + 1)">
                                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                        </button>
                                    </li>
                                </template>
                            </ol>
                            <p class="isu-hint" x-show="!questions.length">No questions yet. Add your own or let Jenny suggest some. If you leave this empty, a standard set is used.</p>
                            <div class="isu-add" x-show="questions.length < 15">
                                <input type="text" class="emp-input" x-model="newQuestion" maxlength="300" placeholder="Type a question and press Enter" @keydown.enter.prevent="addQuestion()" aria-label="New interview question">
                                <button type="button" class="isu-btn" @click="addQuestion()" :disabled="!newQuestion.trim()">Add</button>
                            </div>
                        </div>
                    </div>
                    </div>

                    <!-- ════ RIGHT COLUMN: AI Assistant & Communication ════ -->
                    <div>
                        <!-- 1. AI Scheduling Assistant Card -->
                        <div class="emp-ai-sched-card">
                            <div class="emp-ai-sched-header">
                                <div class="emp-ai-sched-avatar">
                                    <img src="<?= asset('assets/images/jenny_ai/jenny_ai_hi.png') ?>" alt="Jenny AI Scheduling Assistant">
                                </div>
                                <h3 class="emp-ai-sched-title">AI Scheduling Assistant</h3>
                            </div>

                            <div class="emp-ai-suggest-box">
                                <div class="emp-ai-suggest-lbl">SUGGESTED BEST TIME</div>
                                <div class="emp-ai-suggest-val">Tomorrow at 10:00 AM</div>

                                <div class="emp-ai-feature-row">
                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span>80% availability match</span>
                                </div>

                                <div class="emp-ai-feature-row">
                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                    </svg>
                                    <span>High response likelihood</span>
                                </div>
                            </div>

                            <!-- Auto Schedule Button with Jenny AI Mark -->
                            <button type="button"
                                    class="emp-ai-auto-btn"
                                    @click="autoSchedule()">
                                <svg style="width: 14px; height: 14px; flex-shrink: 0;" viewBox="0 0 74 83" fill="currentColor">
                                    <path d="M73.74 32.4627V59.9427C67.93 59.7673 62.2767 61.8422 57.9596 65.7344C53.6426 69.6267 50.9952 75.0356 50.57 80.8327C50.52 81.3027 50.48 81.8327 50.46 82.2827H23.57C23.57 82.0227 23.48 81.7627 23.43 81.5127C20.73 67.1427 16.41 63.2427 0 59.6527V32.6527C13.34 31.5727 27.12 37.6527 36.92 47.9327C46 38.3527 58.99 31.8927 73.74 32.4627Z"/>
                                    <path d="M37.0001 0.0031237C40.4833 0.0367014 43.8785 1.10075 46.7578 3.06112C49.637 5.02149 51.8715 7.79043 53.1794 11.0188C54.4873 14.2473 54.8101 17.7906 54.1071 21.2023C53.4042 24.6139 51.7069 27.741 49.2293 30.1894C46.7516 32.6378 43.6046 34.2979 40.1849 34.9604C36.7652 35.6228 33.2259 35.258 30.0132 33.9119C26.8005 32.5658 24.0583 30.2987 22.1322 27.3963C20.2061 24.494 19.1824 21.0864 19.1901 17.6031C19.1737 15.2695 19.6247 12.9562 20.5166 10.7997C21.4084 8.64315 22.7231 6.68703 24.383 5.04669C26.0429 3.40635 28.0145 2.11499 30.1814 1.24877C32.3484 0.382549 34.6669 -0.040991 37.0001 0.0031237Z"/>
                                </svg>
                                <span>AUTO SCHEDULE</span>
                            </button>
                        </div>

                        <!-- 2. Communication Card -->
                        <div class="emp-comm-card">
                            <h3 class="emp-comm-title">Communication</h3>

                            <!-- Checkboxes Group -->
                            <div class="emp-checkbox-group">
                                <label class="emp-checkbox-label" @click="sendEmail = !sendEmail">
                                    <span>Send email invite</span>
                                    <div class="emp-custom-checkbox" :class="{ 'checked': sendEmail }">
                                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </div>
                                </label>

                                <label class="emp-checkbox-label" @click="sendInApp = !sendInApp">
                                    <span>Send in-app notification</span>
                                    <div class="emp-custom-checkbox" :class="{ 'checked': sendInApp }">
                                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </div>
                                </label>

                                <label class="emp-checkbox-label" @click="addReminder = !addReminder">
                                    <span>Add reminder (1 day before)</span>
                                    <div class="emp-custom-checkbox" :class="{ 'checked': addReminder }">
                                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </div>
                                </label>
                            </div>

                            <!-- Summary Box -->
                            <div class="emp-summary-box">
                                <div class="emp-summary-lbl">Summary</div>
                                <p class="emp-summary-txt" x-text="summaryText">
                                    Video Interview with Sarah Peters on May 25, 2026 at 10:00 AM (45 mins).
                                </p>
                            </div>

                            <!-- Confirm & Schedule Button -->
                            <p class="isu-error" x-show="scheduleError" x-text="scheduleError" role="alert" style="display: none;"></p>
                            <button type="button"
                                    class="emp-btn-confirm-interview"
                                    :disabled="scheduling"
                                    @click="confirmSchedule()">
                                <span x-text="scheduling ? 'SCHEDULING...' : 'CONFIRM & SCHEDULE INTERVIEW'">CONFIRM & SCHEDULE INTERVIEW</span>
                            </button>
                        </div>
                    </div>

                </div>

                <!-- ══════════════════════════════════════════════════════════════════
                     SUCCESS CONFIRMATION MODAL (MATCHING INTERVIEW SCHEDULER (1).PNG)
                     Includes Official Animated Ripple Checkmark & Redirect Timer
                     ══════════════════════════════════════════════════════════════════ -->
                <div x-show="showSuccessModal"
                     class="emp-sched-modal-backdrop"
                     style="display: none;"
                     @keydown.escape.window="closeModal()">
                    <div class="emp-sched-modal-dialog" @click.outside="closeModal()">

                        <!-- Official Animated Green Ripple Checkmark Badge -->
                        <div class="emp-sched-modal-icon-outer">
                            <div class="emp-sched-modal-icon-inner">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                        </div>

                        <!-- Modal Heading & Text -->
                        <h2 class="emp-sched-modal-title">Interview Scheduled</h2>
                        <p class="emp-sched-modal-sub">
                            One conversation could change everything. Your interview with <span x-text="candidate.name" style="font-weight: 700; color: #000623;">Sarah Peters</span> is locked in
                        </p>

                        <!-- Next steps -->
                        <a x-show="meetingType === 'virtual' && joinUrl" :href="joinUrl" class="emp-sched-modal-cta" style="text-decoration: none;">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                            </svg>
                            <span>Join interview room</span>
                        </a>
                        <div style="display: flex; gap: 10px; margin-top: 10px; flex-wrap: wrap; justify-content: center;">
                            <a href="<?= url('/employer/interviews?tab=upcoming') ?>" class="emp-sched-modal-secondary">View interviews</a>
                            <button type="button" class="emp-sched-modal-secondary" @click="resetForAnother()">Schedule another</button>
                        </div>
                        <p x-show="meetingType === 'virtual'" style="font-size: 12px; color: #64748B; margin: 14px 0 0; line-height: 1.5;">
                            <span x-text="candidate.name"></span> has been notified and can join from their interview invitation.
                        </p>

                    </div>
                </div>

            </div>
        </div>

    </div>

</div>

<style>
.isu-head { margin-bottom: 18px; }
.isu-sub { font-size: 13px; color: #64748B; margin: 4px 0 0; line-height: 1.5; }
.isu-block { padding: 16px 0; border-top: 1px solid #F1F5F9; display: flex; flex-direction: column; gap: 10px; }
.isu-label-row { display: flex; align-items: center; justify-content: space-between; gap: 10px; flex-wrap: wrap; }
.isu-link { border: 0; background: transparent; color: #4F48E6; font-size: 12.5px; font-weight: 700; cursor: pointer; padding: 0; }
.isu-chips { display: flex; flex-wrap: wrap; gap: 8px; }
.isu-chip { display: inline-flex; align-items: center; gap: 6px; padding: 6px 8px 6px 12px; border-radius: 9999px; background: #EEF2FF; color: #3730A3; font-size: 12.5px; font-weight: 600; }
.isu-chip button { width: 20px; height: 20px; border: 0; border-radius: 50%; background: transparent; color: #6366F1; display: inline-flex; align-items: center; justify-content: center; cursor: pointer; padding: 0; }
.isu-chip button:hover { background: #E0E7FF; }
.isu-chip svg { width: 11px; height: 11px; }
.isu-add { display: flex; gap: 8px; }
.isu-add .emp-input { flex: 1; min-width: 0; }
.isu-btn { height: 44px; padding: 0 16px; border: 1px solid #E2E8F0; border-radius: 10px; background: #FFFFFF; color: #4F48E6; font-size: 13px; font-weight: 700; cursor: pointer; white-space: nowrap; flex-shrink: 0; }
.isu-btn:hover:not(:disabled) { background: #EEF2FF; border-color: #C7D2FE; }
.isu-btn:disabled { color: #94A3B8; cursor: not-allowed; }
.isu-hint { font-size: 12px; color: #64748B; margin: 0; line-height: 1.5; }
.isu-presets { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 8px; }
.isu-preset { display: flex; flex-direction: column; gap: 4px; padding: 12px; border: 1px solid #E2E8F0; border-radius: 12px; background: #FFFFFF; text-align: left; cursor: pointer; font: inherit; }
.isu-preset strong { font-size: 13px; color: #0F172A; }
.isu-preset span { font-size: 11.5px; color: #64748B; line-height: 1.4; }
.isu-preset:hover { border-color: #CBD5E1; }
.isu-preset.is-on { border-color: #4F48E6; background: #F5F5FF; box-shadow: 0 0 0 3px rgba(79, 72, 230, 0.08); }
.isu-jenny { display: inline-flex; align-items: center; gap: 6px; height: 34px; padding: 0 12px; border: 1px solid #C7D2FE; border-radius: 9px; background: #F5F5FF; color: #4F48E6; font-size: 12.5px; font-weight: 700; cursor: pointer; white-space: nowrap; }
.isu-jenny:hover:not(:disabled) { background: #EEF2FF; }
.isu-jenny:disabled { opacity: .6; cursor: progress; }
.isu-jenny svg { width: 15px; height: 15px; }
.isu-questions { list-style: none; margin: 0; padding: 0; display: flex; flex-direction: column; gap: 8px; }
.isu-questions li { display: flex; align-items: flex-start; gap: 10px; padding: 10px 12px; border: 1px solid #E2E8F0; border-radius: 10px; }
.isu-qnum { width: 22px; height: 22px; border-radius: 50%; background: #EEF2FF; color: #4F48E6; font-size: 11.5px; font-weight: 800; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.isu-qbody { flex: 1; min-width: 0; display: flex; flex-direction: column; gap: 3px; font-size: 13px; color: #0F172A; line-height: 1.45; }
.isu-qbody small { font-size: 11.5px; color: #64748B; }
.isu-remove { width: 28px; height: 28px; border: 0; border-radius: 7px; background: transparent; color: #94A3B8; display: flex; align-items: center; justify-content: center; cursor: pointer; flex-shrink: 0; }
.isu-remove:hover { background: #FEF2F2; color: #DC2626; }
.isu-remove svg { width: 14px; height: 14px; }
.isu-error { margin: 0 0 10px; padding: 10px 12px; border-radius: 10px; background: #FEF2F2; color: #B91C1C; font-size: 12.5px; font-weight: 600; }
.emp-btn-confirm-interview:disabled { opacity: .65; cursor: progress; }
@media (max-width: 640px) { .isu-presets { grid-template-columns: minmax(0, 1fr); } }
</style>

<!-- ══ SCRIPT: INTERVIEW SCHEDULER MANAGER ══ -->
<script>
function interviewSchedulerManager(initialCandidate) {
    return {
        candidate: initialCandidate || {
            id: 1,
            name: 'Sarah Peters',
            role: 'Frontend Developer',
            status: 'Shortlisted',
            match_score: 92,
            avatar: 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=160&h=160&fit=crop&crop=faces'
        },
        meetingType: 'virtual',
        interviewDate: '',
        interviewTime: '',
        todayIso: '',
        duration: '30 Minutes',
        durationOpen: false,
        meetingLink: '',
        joinUrl: '',
        scheduling: false,
        scheduleError: '',
        notes: '',

        // Interview setup (sent with the schedule; drives the in-call scorecard)
        defaultCriteria: ['Technical competence', 'Problem solving', 'Communication', 'Culture & team fit'],
        criteria: ['Technical competence', 'Problem solving', 'Communication', 'Culture & team fit'],
        newCriterion: '',
        recPresets: [
            { key: 'hiring', label: 'Hiring decision', options: [
                { label: 'Strong hire', tone: 'good' }, { label: 'Hire', tone: 'good' }, { label: 'Hold', tone: 'neutral' }, { label: 'Do not proceed', tone: 'bad' } ] },
            { key: 'stage', label: 'Next stage', options: [
                { label: 'Advance', tone: 'good' }, { label: 'Hold', tone: 'neutral' }, { label: 'Decline', tone: 'bad' } ] },
            { key: 'fit', label: 'Fit level', options: [
                { label: 'Excellent fit', tone: 'good' }, { label: 'Good fit', tone: 'good' }, { label: 'Partial fit', tone: 'neutral' }, { label: 'Not a fit', tone: 'bad' } ] },
        ],
        recPreset: 'hiring',
        questions: [],
        newQuestion: '',
        suggesting: false,

        init() {
            const pad = (n) => String(n).padStart(2, '0');
            const now = new Date();
            this.todayIso = now.getFullYear() + '-' + pad(now.getMonth() + 1) + '-' + pad(now.getDate());
            const next = new Date(now.getTime() + 30 * 60000);
            next.setMinutes(next.getMinutes() < 30 ? 30 : 60, 0, 0);
            this.interviewDate = next.getFullYear() + '-' + pad(next.getMonth() + 1) + '-' + pad(next.getDate());
            this.interviewTime = pad(next.getHours()) + ':' + pad(next.getMinutes());
        },

        resetCriteria() { this.criteria = [...this.defaultCriteria]; },
        addCriterion() {
            const v = this.newCriterion.trim();
            if (v && this.criteria.length < 8 && !this.criteria.some((c) => c.toLowerCase() === v.toLowerCase())) this.criteria.push(v);
            this.newCriterion = '';
        },
        addQuestion() {
            const v = this.newQuestion.trim();
            if (v && this.questions.length < 15) this.questions.push({ question: v, hint: '' });
            this.newQuestion = '';
        },
        async suggestQuestions() {
            if (this.suggesting) return;
            this.suggesting = true;
            try {
                const res = await fetch('<?= url('/employer/interviews/suggest-questions') ?>', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify({ role: this.candidate.role, criteria: this.criteria })
                });
                const json = await res.json();
                const fresh = (json.questions || []).filter((q) => !this.questions.some((x) => x.question === q.question));
                this.questions.push(...fresh.slice(0, Math.max(0, 15 - this.questions.length)));
                window.dispatchEvent(new CustomEvent('show-toast', { detail: { text: fresh.length ? 'Jenny added ' + fresh.length + ' questions.' : 'No new suggestions this time.', type: 'success' } }));
            } catch (e) {
                window.dispatchEvent(new CustomEvent('show-toast', { detail: { text: 'Jenny could not suggest questions right now.', type: 'error' } }));
            } finally {
                this.suggesting = false;
            }
        },
        get setupPayload() {
            const preset = this.recPresets.find((p) => p.key === this.recPreset) || this.recPresets[0];
            return { criteria: this.criteria, questions: this.questions, recommendations: preset.options };
        },
        sendEmail: true,
        sendInApp: true,
        addReminder: true,

        showSuccessModal: false,
        countdown: 5,
        countdownTimer: null,

        autoSchedule() {
            const pad = (n) => String(n).padStart(2, '0');
            const t = new Date();
            t.setDate(t.getDate() + 1);
            this.meetingType = 'virtual';
            this.interviewDate = t.getFullYear() + '-' + pad(t.getMonth() + 1) + '-' + pad(t.getDate());
            this.interviewTime = '10:00';
            this.duration = '30 Minutes';
            this.meetingLink = '';
        },

        get startDate() {
            if (!this.interviewDate || !this.interviewTime) return null;
            const d = new Date(this.interviewDate + 'T' + this.interviewTime);
            return isNaN(d.getTime()) ? null : d;
        },

        get summaryText() {
            const format = this.meetingType === 'virtual' ? 'Video interview' : 'In-person interview';
            const mins = parseInt(this.duration, 10) || 30;
            const d = this.startDate;
            const when = d ? d.toLocaleDateString('en-GB', { weekday: 'short', day: 'numeric', month: 'short', year: 'numeric' }) + ' at ' + d.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit' }) : 'a date you choose';
            return `${format} with ${this.candidate.name} on ${when} (${mins} mins, plus a 1-minute wrap-up).`;
        },

        async confirmSchedule() {
            if (this.scheduling) return;
            this.scheduleError = '';
            const d = this.startDate;
            if (!d) { this.scheduleError = 'Choose a date and time.'; return; }
            if (d.getTime() < Date.now() - 60000) { this.scheduleError = 'Choose a time in the future.'; return; }
            if (this.meetingType === 'physical' && !this.meetingLink.trim()) { this.scheduleError = 'Add the office address for an in-person interview.'; return; }
            this.scheduling = true;
            try {
                const res = await fetch('<?= url('/employer/interviews/schedule') ?>', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify({
                        candidate_id: this.candidate.id || 1,
                        candidate_name: this.candidate.name,
                        candidate_role: this.candidate.role,
                        meeting_type: this.meetingType,
                        meeting_link: this.meetingType === 'virtual' ? '' : this.meetingLink,
                        date: this.interviewDate,
                        time: this.interviewTime,
                        duration_min: parseInt(this.duration, 10) || 30,
                        notes: this.notes,
                        setup: this.setupPayload
                    })
                });
                const json = await res.json().catch(() => ({}));
                if (!res.ok || !json.success) throw new Error(json.error || 'The interview could not be scheduled. Please try again.');
                this.joinUrl = json.employer_room || '';
                this.showSuccessModal = true;
            } catch (e) {
                this.scheduleError = e.message;
            } finally {
                this.scheduling = false;
            }
        },

        closeModal() {
            if (this.countdownTimer) clearInterval(this.countdownTimer);
            this.showSuccessModal = false;
        },

        resetForAnother() {
            if (this.countdownTimer) clearInterval(this.countdownTimer);
            this.showSuccessModal = false;
            window.location.href = '<?= url('/employer/candidates') ?>';
        }
    };
}
</script>
