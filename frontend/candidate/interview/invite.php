<?php
declare(strict_types=1);
/**
 * View: Candidate View Interview Invite (Upcoming Interview)
 * Route: /candidate/interview, /candidate/interview/{slug}
 * Layout: dashboard (Candidate Portal)
 * Matches latest visual design specs & submission drawer styling
 * 
 * @var array $candidate Candidate profile information
 * @var array $interview Interview details, schedule, submission info, and tips
 */

$name      = $candidate['name'] ?? 'Sarah Peters';
$firstName = explode(' ', trim($name))[0];
$role      = $candidate['role'] ?? 'Student Applicant';
$avatar    = $candidate['avatar'] ?? null;

$interviewerName = $interview['interviewer_name'] ?? 'Alex Chen';
$interviewerWords = explode(' ', trim($interviewerName));
$interviewerInitials = strtoupper(substr($interviewerWords[0] ?? 'A', 0, 1) . (isset($interviewerWords[1]) ? substr($interviewerWords[1], 0, 1) : ''));
?>

<div class="db-shell" x-data="{
    copiedLink: false,
    showRescheduleModal: false,
    showSuccessModal: false,
    showMessageModal: false,
    openReasonSelect: false,
    rescheduleReason: '',
    rescheduleReasonLabel: 'Select a reason',
    rescheduleDate: '10/26/2026',
    rescheduleTime: '02:30 PM',
    rescheduleMessage: '',
    messageSubject: 'Question regarding <?= htmlspecialchars($interview['role'] ?? 'Interview') ?>',
    messageBody: 'Hello <?= htmlspecialchars($interviewerWords[0] ?? 'Alex') ?>,\n\nI am preparing for our interview and had a quick question regarding...',
    micTested: true,
    cameraTested: false,
    accordionOpen: true,

    init() {
        this.$nextTick(() => {
            if (typeof flatpickr !== 'undefined') {
                if (this.$refs.datePicker) {
                    flatpickr(this.$refs.datePicker, {
                        dateFormat: 'm/d/Y',
                        defaultDate: '10/26/2026',
                        minDate: 'today',
                        monthSelectorType: 'static',
                        disableMobile: true,
                        onChange: (selectedDates, dateStr) => {
                            this.rescheduleDate = dateStr;
                        }
                    });
                }
                if (this.$refs.timePicker) {
                    flatpickr(this.$refs.timePicker, {
                        enableTime: true,
                        noCalendar: true,
                        dateFormat: 'h:i K',
                        defaultDate: '02:30 PM',
                        disableMobile: true,
                        onChange: (selectedDates, dateStr) => {
                            this.rescheduleTime = dateStr;
                        }
                    });
                }
            }
        });
    },

    copyMeetLink() {
        navigator.clipboard.writeText('<?= htmlspecialchars($interview['meet_link']) ?>');
        this.copiedLink = true;
        if (typeof window.$dispatch === 'function') {
            window.$dispatch('show-toast', { 
                text: 'Meeting link copied to clipboard!', 
                type: 'success' 
            });
        }
        setTimeout(() => { this.copiedLink = false; }, 2500);
    },

    submitReschedule() {
        this.showRescheduleModal = false;
        this.showSuccessModal = true;
        fetch('<?= url('/candidate/interview/respond') ?>', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
            body: JSON.stringify({
                interview_id: 1,
                status: 'Reschedule Requested',
                reason: this.rescheduleReasonLabel,
                reschedule_date: this.rescheduleDate,
                reschedule_time: this.rescheduleTime,
                notes: this.rescheduleMessage
            })
        }).catch(err => console.error('Failed to submit reschedule:', err));
    },

    sendMessage() {
        this.showMessageModal = false;
        const body = (this.messageSubject ? this.messageSubject + ': ' : '') + this.messageBody;
        fetch('<?= url('/candidate/messages/send') ?>', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
            body: JSON.stringify({
                thread_id: 1,
                content: body
            })
        }).catch(err => console.error('Failed to send recruiter message:', err));
        if (typeof window.$dispatch === 'function') {
            window.$dispatch('show-toast', { 
                text: 'Your message has been sent to <?= htmlspecialchars($interview['interviewer_name']) ?>.', 
                type: 'success' 
            });
        }
    },

    confirmInterview() {
        fetch('<?= url('/candidate/interview/respond') ?>', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
            body: JSON.stringify({
                interview_id: 1,
                status: 'Confirmed'
            })
        }).catch(err => console.error('Failed to confirm interview:', err));
        if (typeof window.$dispatch === 'function') {
            window.$dispatch('show-toast', { 
                text: 'Interview invitation confirmed! Calendar notification dispatched.', 
                type: 'success' 
            });
        }
    },

    addToCalendar() {
        const title = encodeURIComponent('Interview: <?= htmlspecialchars($interview['company']) ?> - <?= htmlspecialchars($interview['role']) ?>');
        const details = encodeURIComponent('Link: <?= htmlspecialchars($interview['meet_link']) ?>\nInterviewer: <?= htmlspecialchars($interview['interviewer_name']) ?>');
        const location = encodeURIComponent('<?= htmlspecialchars($interview['meet_link']) ?>');
        const gcalUrl = `https://calendar.google.com/calendar/render?action=TEMPLATE&text=${title}&details=${details}&location=${location}&dates=20261024T100000Z/20261024T110000Z`;
        window.open(gcalUrl, '_blank');
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
            <div class="db-offer-breadcrumb" style="margin-bottom: 16px;">
                <a href="<?= url('/candidate/applications') ?>" class="db-offer-breadcrumb-link">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                    </svg>
                    <span>Back to Applications</span>
                </a>
            </div>

            <!-- Page Title Header Block -->
            <div class="db-interview-header" style="margin-bottom: 24px;">
                <h1 class="db-interview-title">Upcoming Interview</h1>
                <p class="db-interview-subtitle">Your Interview with <?= htmlspecialchars($interview['company']) ?></p>
            </div>

            <!-- Hero Countdown Banner (TIME REMAINING) -->
            <div class="db-interview-countdown-banner">
                <div class="db-countdown-left">
                    <div class="db-countdown-icon-circle">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <span class="db-countdown-label">TIME REMAINING</span>
                </div>
                <div class="db-countdown-tiles">
                    <div class="db-countdown-tile">
                        <span class="db-tile-num"><?= str_pad((string)$interview['days_remaining'], 2, '0', STR_PAD_LEFT) ?></span>
                        <span class="db-tile-sub">DAYS</span>
                    </div>
                    <div class="db-countdown-tile">
                        <span class="db-tile-num"><?= str_pad((string)$interview['hours_remaining'], 2, '0', STR_PAD_LEFT) ?></span>
                        <span class="db-tile-sub">HOURS</span>
                    </div>
                    <div class="db-countdown-tile">
                        <span class="db-tile-num"><?= str_pad((string)($interview['minutes_remaining'] ?? 15), 2, '0', STR_PAD_LEFT) ?></span>
                        <span class="db-tile-sub">MINS</span>
                    </div>
                </div>
            </div>

            <!-- 2-Column Content Grid -->
            <div class="db-interview-grid-layout">
                
                <!-- Left Main Column (65%) -->
                <div class="db-interview-col-main">
                    
                    <!-- Card 1: Company & Role Details + Joining Instructions -->
                    <div class="db-card db-interview-card">
                        <div class="db-interview-company-header">
                            <div class="db-interview-avatar" style="background-color: <?= htmlspecialchars($interview['letter_bg']) ?>;">
                                <?= htmlspecialchars($interview['letter']) ?>
                            </div>
                            <div class="db-interview-meta">
                                <h3 class="db-interview-company-name"><?= htmlspecialchars($interview['company']) ?></h3>
                                <p class="db-interview-role-text"><?= htmlspecialchars($interview['role']) ?> • <?= htmlspecialchars($interview['type']) ?></p>
                            </div>
                            <span class="db-interview-status-pill">
                                <?= htmlspecialchars($interview['status']) ?>
                            </span>
                        </div>

                        <!-- Joining Instructions Sub-box -->
                        <div class="db-join-instructions-box">
                            <h4 class="db-join-title">Joining Instructions</h4>
                            <div class="db-join-link-row">
                                <div class="db-join-input-box">
                                    <span class="db-join-url"><?= htmlspecialchars($interview['meet_code']) ?></span>
                                    <button type="button" class="db-join-copy-btn" @click="copyMeetLink()" title="Copy Meeting Link">
                                        <svg x-show="!copiedLink" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                        </svg>
                                        <svg x-show="copiedLink" style="display: none; color: #16A34A;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    </button>
                                </div>
                                <a href="<?= url('/candidate/interview/room') ?>" class="db-join-meet-btn" style="white-space: nowrap !important; flex-shrink: 0 !important;">
                                    <svg style="width: 15px; height: 15px; flex-shrink: 0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                    </svg>
                                    <span>Enter Video Room</span>
                                </a>
                            </div>
                            <p class="db-join-footnote">Button will be active 10 minutes before the interview start time.</p>
                        </div>
                    </div>

                    <!-- Card 2: About this interview -->
                    <div class="db-card db-interview-card">
                        <h3 class="db-card-section-heading">About this interview</h3>
                        
                        <!-- 2 Sub-cards: Interviewer + Format -->
                        <div class="db-interview-about-grid">
                            <div class="db-about-subcard">
                                <div class="db-about-icon-box">
                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </div>
                                <div class="db-about-subcard-info">
                                    <span class="db-about-subcard-label">Interviewer</span>
                                    <span class="db-about-subcard-val"><?= htmlspecialchars($interview['interviewer_name']) ?>, <?= htmlspecialchars($interview['interviewer_role']) ?></span>
                                </div>
                            </div>

                            <div class="db-about-subcard">
                                <div class="db-about-icon-box">
                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <div class="db-about-subcard-info">
                                    <span class="db-about-subcard-label">Format</span>
                                    <span class="db-about-subcard-val"><?= htmlspecialchars($interview['format_title']) ?></span>
                                </div>
                            </div>
                        </div>

                        <!-- "What you submitted" Accordion Section -->
                        <div class="db-submitted-accordion-box">
                            <button type="button" class="db-submitted-header-btn" @click="accordionOpen = !accordionOpen" :aria-expanded="accordionOpen">
                                <div class="db-submitted-header-left">
                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    <span>What you submitted</span>
                                </div>
                                <svg class="db-submitted-chevron" :class="{ 'rotate-180': accordionOpen }" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>

                            <div x-show="accordionOpen" class="db-submitted-content" style="display: block;">
                                
                                <!-- File 1: Resume -->
                                <div class="db-submission-file-row">
                                    <div class="db-submission-file-info">
                                        <div class="db-pdf-badge">PDF</div>
                                        <div>
                                            <span class="db-submission-filename"><?= htmlspecialchars($interview['submission']['resume_file']) ?></span>
                                            <span class="db-submission-filesize">Official Candidate Resume</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- File 2: Portfolio Case Study -->
                                <div class="db-submission-file-row">
                                    <div class="db-submission-file-info">
                                        <div class="db-pdf-badge">PDF</div>
                                        <div>
                                            <span class="db-submission-filename"><?= htmlspecialchars($interview['submission']['portfolio_file']) ?></span>
                                            <span class="db-submission-filesize">Design Portfolio Case Study</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- External Links Row (Figma + Live Portfolio) -->
                                <div class="db-submission-links-grid">
                                    <div class="db-submission-link-card">
                                        <span class="db-sub-link-label">Figma Design Spec</span>
                                        <a href="https://<?= htmlspecialchars($interview['submission']['figma_link']) ?>" target="_blank" rel="noopener noreferrer" class="db-sub-link-url">
                                            <span><?= htmlspecialchars($interview['submission']['figma_link']) ?></span>
                                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                        </a>
                                    </div>
                                    <div class="db-submission-link-card">
                                        <span class="db-sub-link-label">Live Portfolio URL</span>
                                        <a href="https://<?= htmlspecialchars($interview['submission']['portfolio_url']) ?>" target="_blank" rel="noopener noreferrer" class="db-sub-link-url">
                                            <span><?= htmlspecialchars($interview['submission']['portfolio_url']) ?></span>
                                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                        </a>
                                    </div>
                                </div>

                                <!-- Submitted Questionnaire Responses -->
                                <?php if (!empty($interview['submission']['interest_reason'])): ?>
                                <div class="db-submission-qa-grid">
                                    <div class="db-sub-qa-item">
                                        <span class="db-sub-qa-question">Why Vanguard Design?</span>
                                        <p class="db-sub-qa-answer"><?= htmlspecialchars($interview['submission']['interest_reason']) ?></p>
                                    </div>
                                    <div class="db-sub-qa-item">
                                        <span class="db-sub-qa-question">Design Methodology</span>
                                        <p class="db-sub-qa-answer"><?= htmlspecialchars($interview['submission']['design_process']) ?></p>
                                    </div>
                                </div>
                                <?php endif; ?>

                                <!-- Cover Letter Attached Status (Clean Badge) -->
                                <div class="db-submission-attached-row">
                                    <div class="db-submission-attached-info">
                                        <div class="db-cl-icon-box">
                                            <svg style="width: 18px; height: 18px; max-width: 18px; max-height: 18px; display: block;" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <span class="db-submission-filename">Cover Letter</span>
                                            <span class="db-submission-filesize">Personalized candidate statement submitted</span>
                                        </div>
                                    </div>
                                    <span class="db-submission-attached-badge">
                                        <svg style="width: 14px; height: 14px; max-width: 14px; max-height: 14px; display: block;" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        <span>Cover letter was attached</span>
                                    </span>
                                </div>

                            </div>
                        </div>

                    </div>

                </div>

                <!-- Right Sidebar Column (35%) -->
                <div class="db-interview-col-sidebar">
                    
                    <!-- Card 1: Meeting Details -->
                    <div class="db-card db-interview-card">
                        <div class="db-sidecard-header">
                            <h3 class="db-sidecard-title">Meeting Details</h3>
                            <span class="db-sidecard-pill">3 Items</span>
                        </div>
                        <div class="db-meeting-details-list">
                            <div class="db-meeting-item">
                                <span class="db-meeting-label">Date</span>
                                <span class="db-meeting-val"><?= htmlspecialchars($interview['date']) ?></span>
                            </div>
                            <div class="db-meeting-item">
                                <span class="db-meeting-label">Time</span>
                                <span class="db-meeting-val"><?= htmlspecialchars($interview['time']) ?></span>
                            </div>
                            <div class="db-meeting-item">
                                <span class="db-meeting-label">Platform</span>
                                <span class="db-meeting-val db-val-platform" style="display: inline-flex; align-items: center; gap: 6px;">
                                    <svg style="width: 17px; height: 17px; flex-shrink: 0;" viewBox="0 0 24 24">
                                        <rect x="2" y="5" width="13" height="14" rx="2" fill="#00832D"/>
                                        <polygon points="15,9.5 22,5.5 22,18.5 15,14.5" fill="#0066DA"/>
                                        <path d="M15 5H4a2 2 0 0 0-2 2v2.5h13V5z" fill="#EA4335"/>
                                        <path d="M2 9.5h13v5H2z" fill="#FFBA00"/>
                                        <path d="M15 14.5H2V17a2 2 0 0 0 2 2h11v-4.5z" fill="#00AC47"/>
                                    </svg>
                                    <span><?= htmlspecialchars($interview['platform']) ?></span>
                                </span>
                            </div>
                            <div class="db-meeting-item">
                                <span class="db-meeting-label">Timezone</span>
                                <span class="db-meeting-val" style="font-size: 12px; color: #64748B; font-weight: 500;"><?= htmlspecialchars($interview['timezone']) ?></span>
                            </div>
                        </div>
                    </div>

                    <!-- Card 2: Prep Checklist -->
                    <div class="db-prep-card">
                        <div class="db-prep-header">
                            <span class="db-prep-title">Prep Checklist</span>
                            <span class="db-prep-count" x-text="((micTested ? 1 : 0) + (cameraTested ? 1 : 0)) + ' of 2 completed'">1 of 2 completed</span>
                        </div>
                        <div class="db-prep-bar-track">
                            <div class="db-prep-bar-fill" :style="'width: ' + (((micTested ? 1 : 0) + (cameraTested ? 1 : 0)) / 2 * 100) + '%;'"></div>
                        </div>
                        <label class="db-prep-item">
                            <input type="checkbox" x-model="micTested">
                            <span>Test Mic</span>
                        </label>
                        <label class="db-prep-item">
                            <input type="checkbox" x-model="cameraTested">
                            <span>Test Camera</span>
                        </label>
                    </div>

                    <!-- Card 3: Manage Interview -->
                    <div class="db-card db-interview-card">
                        <h3 class="db-sidecard-title" style="margin-bottom: 14px;">Manage Interview</h3>
                        <div class="db-manage-btns-stack">
                            <button type="button" class="db-manage-btn-primary" @click="addToCalendar()">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <span>Add to Calendar</span>
                            </button>
                            <button type="button" class="db-manage-btn-outline" @click="showMessageModal = true">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                </svg>
                                <span>Message Vanguard</span>
                            </button>
                            <button type="button" class="db-manage-btn-outline" @click="showRescheduleModal = true">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span>Request Reschedule</span>
                            </button>
                        </div>
                    </div>

                    <!-- Card 4: Quick Tips Card -->
                    <div class="db-card db-tips-card">
                        <div class="db-tips-header">
                            <div class="db-tips-icon-wrap">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                                </svg>
                            </div>
                            <span class="db-tips-header-title">Quick Tips</span>
                        </div>
                        <div class="db-tips-body">
                            <ul class="db-tips-list">
                                <?php foreach ($interview['tips'] as $tip): ?>
                                <li class="db-tip-item">
                                    <span class="db-tip-bullet">•</span>
                                    <div>
                                        <strong class="db-tip-title"><?= htmlspecialchars($tip['title']) ?></strong>
                                        <p class="db-tip-desc"><?= htmlspecialchars($tip['desc']) ?></p>
                                    </div>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                            <a href="<?= url('/candidate/interview/guide') ?>" class="db-tips-guide-link">Read Interview Guide &rsaquo;</a>
                        </div>
                    </div>

                </div>

            </div>

        </div><!-- /.db-content -->

        <!-- Dashboard Footer -->
        <?php include __DIR__ . '/../../shared/components/db-footer.php'; ?>

    </div><!-- /.db-main -->

    <!-- ══ 1. REQUEST RESCHEDULE MODAL (Matching Image 1 Design) ══ -->
    <div x-show="showRescheduleModal" class="db-modal-backdrop" style="display: none;" @click.self="showRescheduleModal = false; openReasonSelect = false;" @keydown.escape.window="showRescheduleModal = false; openReasonSelect = false;">
        <div class="db-modal-card" style="max-width: 520px; padding: 28px;">
            
            <!-- Modal Header with Clock Icon -->
            <div style="display: flex; align-items: flex-start; gap: 14px; margin-bottom: 16px;">
                <div style="width: 44px; height: 44px; border-radius: 50%; background: #EEF2FF; color: #4F46E5; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    <svg style="width: 22px; height: 22px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <h3 style="font-size: 20px; font-weight: 800; color: #0F172A; margin: 0 0 4px 0;">Request a Reschedule</h3>
                    <p style="font-size: 13px; color: #64748B; margin: 0;">For your interview with <?= htmlspecialchars($interview['company']) ?></p>
                </div>
            </div>

            <!-- Current Schedule Info Bar -->
            <div class="db-reschedule-current-bar">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <span>Currently scheduled <?= explode(',', $interview['date'])[0] ?> at <?= explode(' -', $interview['time'])[0] ?> WAT</span>
            </div>

            <!-- Warning Callout Banner -->
            <div class="db-reschedule-warning-callout">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <span>Frequent rescheduling may impact the interview timeline. Please ensure your new proposed time is highly accurate.</span>
            </div>

            <!-- Form: Reason for rescheduling (Custom Dropdown) -->
            <div class="db-modal-form-group" style="margin-bottom: 16px;">
                <label class="db-modal-field-label">Reason for rescheduling</label>
                <div class="db-custom-select" @click.outside="openReasonSelect = false">
                    <button type="button" 
                            class="db-custom-select-trigger" 
                            :class="{ 'is-active': openReasonSelect, 'has-value': rescheduleReason !== '' }"
                            @click="openReasonSelect = !openReasonSelect"
                            aria-haspopup="listbox" 
                            :aria-expanded="openReasonSelect">
                        <span x-text="rescheduleReasonLabel">Select a reason</span>
                        <svg class="db-custom-select-chevron" :class="{ 'is-rotated': openReasonSelect }" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    <div x-show="openReasonSelect" 
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 translate-y-[-6px] scale-98"
                         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                         x-transition:leave="transition ease-in duration-100"
                         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                         x-transition:leave-end="opacity-0 translate-y-[-6px] scale-98"
                         class="db-custom-select-dropdown" 
                         style="display: none;">
                        
                        <div class="db-custom-select-option" 
                             :class="{ 'is-selected': rescheduleReason === '' }"
                             @click="rescheduleReason = ''; rescheduleReasonLabel = 'Select a reason'; openReasonSelect = false">
                            <span>Select a reason</span>
                            <template x-if="rescheduleReason === ''">
                                <svg class="db-option-check" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            </template>
                        </div>
                        
                        <div class="db-custom-select-option" 
                             :class="{ 'is-selected': rescheduleReason === 'academic' }"
                             @click="rescheduleReason = 'academic'; rescheduleReasonLabel = 'Academic / University Exam Conflict'; openReasonSelect = false">
                            <span>Academic / University Exam Conflict</span>
                            <template x-if="rescheduleReason === 'academic'">
                                <svg class="db-option-check" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            </template>
                        </div>

                        <div class="db-custom-select-option" 
                             :class="{ 'is-selected': rescheduleReason === 'medical' }"
                             @click="rescheduleReason = 'medical'; rescheduleReasonLabel = 'Illness / Medical Emergency'; openReasonSelect = false">
                            <span>Illness / Medical Emergency</span>
                            <template x-if="rescheduleReason === 'medical'">
                                <svg class="db-option-check" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            </template>
                        </div>

                        <div class="db-custom-select-option" 
                             :class="{ 'is-selected': rescheduleReason === 'technical' }"
                             @click="rescheduleReason = 'technical'; rescheduleReasonLabel = 'Technical / Internet Connectivity Issue'; openReasonSelect = false">
                            <span>Technical / Internet Connectivity Issue</span>
                            <template x-if="rescheduleReason === 'technical'">
                                <svg class="db-option-check" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            </template>
                        </div>

                        <div class="db-custom-select-option" 
                             :class="{ 'is-selected': rescheduleReason === 'timezone' }"
                             @click="rescheduleReason = 'timezone'; rescheduleReasonLabel = 'Timezone / Travel Conflict'; openReasonSelect = false">
                            <span>Timezone / Travel Conflict</span>
                            <template x-if="rescheduleReason === 'timezone'">
                                <svg class="db-option-check" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            </template>
                        </div>

                        <div class="db-custom-select-option" 
                             :class="{ 'is-selected': rescheduleReason === 'other' }"
                             @click="rescheduleReason = 'other'; rescheduleReasonLabel = 'Personal / Other reasons'; openReasonSelect = false">
                            <span>Personal / Other reasons</span>
                            <template x-if="rescheduleReason === 'other'">
                                <svg class="db-option-check" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            </template>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form: 2-Column Preferred Date & Preferred Time -->
            <div class="db-modal-field-grid-2">
                <div>
                    <label class="db-modal-field-label">Preferred Date</label>
                    <div class="db-input-icon-wrap">
                        <input type="text" x-ref="datePicker" x-model="rescheduleDate" class="db-modal-input" placeholder="MM/DD/YYYY" readonly>
                        <svg class="db-input-right-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                </div>
                <div>
                    <label class="db-modal-field-label">Preferred Time</label>
                    <div class="db-input-icon-wrap">
                        <input type="text" x-ref="timePicker" x-model="rescheduleTime" class="db-modal-input" placeholder="--:-- --" readonly>
                        <svg class="db-input-right-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Form: Message to recruiter with counter -->
            <div class="db-modal-form-group" style="margin-bottom: 16px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                    <label class="db-modal-field-label" style="margin-bottom: 0;">Message to recruiter</label>
                    <span style="font-size: 11.5px; color: #94A3B8;" x-text="rescheduleMessage.length + '/300'">0/300</span>
                </div>
                <textarea x-model="rescheduleMessage" maxlength="300" rows="3" class="db-modal-textarea" style="min-height: 80px;" placeholder="Briefly explain your request and offer additional availability..."></textarea>
            </div>

            <!-- Notice Line -->
            <div class="db-reschedule-notice-line">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>Sending this does not cancel your interview</span>
            </div>

            <!-- Action Buttons -->
            <div class="db-modal-actions" style="display: flex; justify-content: flex-end; gap: 12px;">
                <button type="button" class="db-modal-btn-cancel" @click="showRescheduleModal = false; openReasonSelect = false;">Cancel</button>
                <button type="button" class="db-manage-btn-primary" @click="submitReschedule()" style="padding: 10px 20px; font-size: 13.5px; border-radius: 8px;">
                    <span>Send Request</span>
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width: 14px; height: 14px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </button>
            </div>

        </div>
    </div>

    <!-- ══ 2. REQUEST SENT SUCCESS MODAL (Matching Image 2 Design) ══ -->
    <div x-show="showSuccessModal" class="db-modal-backdrop" style="display: none;" @click.self="showSuccessModal = false" @keydown.escape.window="showSuccessModal = false">
        <div class="db-modal-card" style="max-width: 460px; padding: 32px 28px; text-align: center;">
            
            <!-- Big Green Checkmark Icon -->
            <div style="width: 58px; height: 58px; background: #059669; border-radius: 50%; color: #FFFFFF; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px; box-shadow: 0 4px 14px rgba(5, 150, 105, 0.3);">
                <svg style="width: 30px; height: 30px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
            </div>

            <h3 style="font-size: 20px; font-weight: 800; color: #0F172A; margin: 0 0 8px 0;">Request Sent</h3>
            <p style="font-size: 13px; color: #64748B; line-height: 1.5; margin: 0 0 20px 0;">
                Your reschedule request has been sent to <?= htmlspecialchars($interview['company'] ?? 'the hiring team') ?>. Your original interview remains confirmed until they approve the change.
            </p>

            <!-- Summary Card -->
            <div style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 12px; padding: 16px 18px; margin-bottom: 24px; text-align: left;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px;">
                    <span style="font-size: 11px; font-weight: 800; color: #64748B; text-transform: uppercase; letter-spacing: 0.5px;">CURRENT APPOINTMENT</span>
                    <span style="background: #FEF3C7; color: #D97706; border: 1px solid #FDE68A; padding: 3px 10px; border-radius: 20px; font-size: 11px; font-weight: 700;">Awaiting employer response</span>
                </div>
                <div style="font-size: 13.5px; font-weight: 700; color: #0F172A; margin-bottom: 12px;">
                    <?= htmlspecialchars($interview['date']) ?> • <?= explode(' -', $interview['time'])[0] ?>
                </div>

                <div style="border-top: 1px solid #E2E8F0; padding-top: 12px;">
                    <div style="display: flex; align-items: center; gap: 6px; font-size: 11px; font-weight: 800; color: #64748B; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">
                        <svg style="width: 14px; height: 14px; color: #4F46E5;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        <span>REQUESTED FOR</span>
                    </div>
                    <div style="font-size: 13.5px; font-weight: 700; color: #0F172A;" x-text="(rescheduleDate || 'Oct 26, 2026') + ' • ' + (rescheduleTime || '02:30 PM')"></div>
                </div>
            </div>

            <!-- Got it Button -->
            <button type="button" class="db-manage-btn-primary" style="width: 100%; justify-content: center; background: #0052CC; padding: 12px; font-size: 14px; border-radius: 8px;" @click="showSuccessModal = false">
                Got it
            </button>

        </div>
    </div>

    <!-- ══ 3. MESSAGE RECRUITER MODAL (Strictly Matching Image 2 Design) ══ -->
    <div x-show="showMessageModal" class="db-modal-backdrop" style="display: none;" @click.self="showMessageModal = false" @keydown.escape.window="showMessageModal = false">
        <div class="db-modal-card" style="max-width: 520px; padding: 28px;">
            
            <!-- Modal Header with Chat Icon Circle -->
            <div style="width: 44px; height: 44px; border-radius: 50%; background: #EEF2FF; color: #4F46E5; display: flex; align-items: center; justify-content: center; margin-bottom: 16px;">
                <svg style="width: 22px; height: 22px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                </svg>
            </div>
            
            <h3 style="font-size: 20px; font-weight: 800; color: #0F172A; margin: 0 0 16px 0;">Message Recruiter</h3>

            <!-- Recruiter Profile Badge Box (Matching Image 2) -->
            <div style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 12px; padding: 12px 16px; display: flex; align-items: center; gap: 14px; margin-bottom: 20px;">
                <div style="width: 42px; height: 42px; border-radius: 50%; background: #4F46E5; color: #FFFFFF; font-size: 14px; font-weight: 800; display: flex; align-items: center; justify-content: center; flex-shrink: 0; letter-spacing: 0.5px;">
                    <?= htmlspecialchars($interviewerInitials) ?>
                </div>
                <div style="min-width: 0;">
                    <div style="font-size: 14px; font-weight: 700; color: #0F172A; margin-bottom: 2px;">
                        <?= htmlspecialchars($interview['interviewer_name']) ?>
                    </div>
                    <div style="font-size: 12px; color: #64748B;">
                        <?= htmlspecialchars($interview['interviewer_role']) ?>, <?= htmlspecialchars($interview['company']) ?>
                    </div>
                </div>
            </div>

            <!-- Form: Subject -->
            <div class="db-modal-form-group" style="margin-bottom: 16px;">
                <label class="db-modal-field-label" style="font-size: 11px; font-weight: 800; color: #475569; letter-spacing: 0.5px; text-transform: uppercase; margin-bottom: 6px;">SUBJECT</label>
                <input type="text" x-model="messageSubject" class="db-modal-input" style="padding: 11px 14px; font-size: 13.5px;" placeholder="Question regarding interview details...">
            </div>

            <!-- Form: Message Body -->
            <div class="db-modal-form-group" style="margin-bottom: 24px;">
                <label class="db-modal-field-label" style="font-size: 11px; font-weight: 800; color: #475569; letter-spacing: 0.5px; text-transform: uppercase; margin-bottom: 6px;">MESSAGE</label>
                <textarea x-model="messageBody" rows="5" class="db-modal-textarea" style="padding: 12px 14px; font-size: 13.5px; min-height: 110px;" placeholder="Write your message here..."></textarea>
            </div>

            <!-- Actions -->
            <div class="db-modal-actions" style="display: flex; justify-content: flex-end; gap: 12px;">
                <button type="button" class="db-modal-btn-cancel" @click="showMessageModal = false" style="padding: 10px 18px; border-radius: 8px;">Cancel</button>
                <button type="button" class="db-manage-btn-primary" @click="sendMessage()" style="background: #0052CC; padding: 10px 22px; font-size: 13.5px; border-radius: 8px; display: inline-flex; align-items: center; gap: 8px;">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2" style="width: 15px; height: 15px; transform: rotate(45deg); margin-top: -2px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                    </svg>
                    <span>Send Message</span>
                </button>
            </div>
        </div>
    </div>

</div>

