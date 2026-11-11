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

                    <!-- ════ LEFT COLUMN: Interview Details ════ -->
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
                                        <input type="text" 
                                               id="sched-date" 
                                               x-model="interviewDate" 
                                               class="emp-input" 
                                               placeholder="10/05/2026">
                                    </div>
                                </div>

                                <!-- Time Input -->
                                <div class="emp-form-group">
                                    <label class="emp-form-label" for="sched-time">Time</label>
                                    <div class="emp-input-icon-wrap">
                                        <svg class="emp-input-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <input type="text" 
                                               id="sched-time" 
                                               x-model="interviewTime" 
                                               class="emp-input" 
                                               placeholder="10:00 AM">
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
                                            <span x-text="duration">45 Minutes</span>
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
                                               x-model="meetingLink" 
                                               class="emp-input" 
                                               :placeholder="meetingType === 'virtual' ? 'http://googlemeet/vjm' : 'TechFlow HQ, 4th Floor, Victoria Island'">
                                    </div>
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
                            <button type="button" 
                                    class="emp-btn-confirm-interview"
                                    @click="confirmSchedule()">
                                <span>CONFIRM & SCHEDULE INTERVIEW</span>
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

                        <!-- CTA Button -->
                        <button type="button" 
                                class="emp-sched-modal-cta" 
                                @click="resetForAnother()">
                            <span>Schedule another Interview</span>
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </button>

                        <!-- Automatic Redirect Timer & Progress Bar -->
                        <div class="emp-sched-modal-timer">
                            Move to Dashboard automatically in <span x-text="countdown">5</span> second...
                        </div>
                        <div class="emp-sched-progress-bar">
                            <div class="emp-sched-progress-fill" :style="'width: ' + ((countdown / 5) * 100) + '%;'"></div>
                        </div>

                    </div>
                </div>

            </div>
        </div>

    </div>

</div>

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
        interviewDate: '10/05 /2026',
        interviewTime: '10:00 AM',
        duration: '45 Minutes',
        durationOpen: false,
        meetingLink: 'http://googlemeet/vjm',
        notes: '',
        sendEmail: true,
        sendInApp: true,
        addReminder: true,

        showSuccessModal: false,
        countdown: 5,
        countdownTimer: null,

        autoSchedule() {
            this.meetingType = 'virtual';
            this.interviewDate = 'Tomorrow';
            this.interviewTime = '10:00 AM';
            this.duration = '45 Minutes';
            this.meetingLink = 'https://meet.google.com/vjm-internconnect';
        },

        get summaryText() {
            const format = this.meetingType === 'virtual' ? 'Video Interview' : 'In-Person Interview';
            const dur = this.duration.toLowerCase().replace('minutes', 'mins');
            return `${format} with ${this.candidate.name} on May 25, 2026 at ${this.interviewTime} (${dur}).`;
        },

        confirmSchedule() {
            this.showSuccessModal = true;
            this.countdown = 5;

            fetch('<?= url('/employer/interviews/schedule') ?>', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                body: JSON.stringify({
                    candidate_id: this.candidate.id || 1,
                    candidate_name: this.candidate.name,
                    candidate_role: this.candidate.role,
                    meeting_type: this.meetingType,
                    meeting_link: this.meetingLink,
                    date: this.interviewDate,
                    time: this.interviewTime,
                    duration: this.duration,
                    notes: this.notes
                })
            }).catch(err => console.error('Failed to schedule interview:', err));

            if (this.countdownTimer) {
                clearInterval(this.countdownTimer);
            }

            this.countdownTimer = setInterval(() => {
                this.countdown--;
                if (this.countdown <= 0) {
                    clearInterval(this.countdownTimer);
                    window.location.href = '<?= url('/employer/dashboard') ?>';
                }
            }, 1000);
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
