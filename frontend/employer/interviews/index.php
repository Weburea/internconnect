<?php

declare(strict_types=1);

/**
 * View: Employer Interviews Dashboard
 * Route: /employer/interviews
 * Layout: dashboard
 *
 * Exact 1:1 implementation matching Screenshots 3 and 4:
 * - 4 Top Metrics Cards: Scheduled Interviews, Completed Interviews, Upcoming Interviews, Cancelled Interviews
 * - Pill Tabs: All Interviews, Shortlisted, Upcoming, Cancelled
 * - Interview Schedule Card with dynamic table columns and candidates list
 * - Interactive actions: "Schedule Interview" navigating to /employer/interviews/schedule/{id},
 *   and "Start Interview" opening a rich meeting join modal.
 *
 * @var array  $employer
 * @var array  $interviews
 * @var array  $stats
 * @var string $activeNav
 */
?>

<style>
    /* ─── Interviews Page Design System ─── */
    .emp-interviews-page {
        padding: 0 0 40px;
        box-sizing: border-box;
    }

    .emp-int-header {
        margin-bottom: 24px;
    }

    .emp-int-title {
        font-size: 26px;
        font-weight: 800;
        color: #000623;
        margin: 0 0 6px;
        letter-spacing: -0.02em;
    }

    .emp-int-subtitle {
        font-size: 14px;
        color: #64748B;
        margin: 0;
    }

    /* ─── Top 4 Metrics Cards ─── */
    .emp-int-metrics-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
        margin-bottom: 28px;
    }

    .emp-int-metric-card {
        background: #FFFFFF;
        border: 1px solid #E2E8F0;
        border-radius: 16px;
        padding: 20px 22px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
        box-sizing: border-box;
    }

    .emp-int-metric-card::after {
        content: '';
        position: absolute;
        bottom: -24px;
        right: -24px;
        width: 90px;
        height: 90px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(79, 72, 230, 0.09) 0%, rgba(79, 72, 230, 0) 70%);
        pointer-events: none;
    }

    .emp-int-metric-card.is-danger::after {
        background: radial-gradient(circle, rgba(239, 68, 68, 0.08) 0%, rgba(239, 68, 68, 0) 70%);
    }

    .emp-int-metric-icon {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 12px;
        flex-shrink: 0;
    }

    .emp-int-metric-icon.icon-purple {
        background: #EEF2FF;
        color: #4F48E6;
    }

    .emp-int-metric-icon.icon-danger {
        background: #FEE2E2;
        color: #DC2626;
    }

    .emp-int-metric-lbl {
        font-size: 13px;
        font-weight: 600;
        color: #64748B;
        margin-bottom: 4px;
    }

    .emp-int-metric-val {
        font-size: 28px;
        font-weight: 800;
        color: #0F172A;
        line-height: 1.1;
    }

    /* ─── Filter Tabs (Pill Switcher) ─── */
    .emp-int-tabs-row {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 24px;
        flex-wrap: wrap;
    }

    .emp-int-tab-btn {
        background: #F1F5F9;
        color: #475569;
        font-size: 13.5px;
        font-weight: 600;
        padding: 9px 22px;
        border-radius: 99px;
        border: none;
        cursor: pointer;
        transition: all 0.15s ease;
        user-select: none;
        white-space: nowrap !important;
        flex-shrink: 0 !important;
    }

    .emp-int-tab-btn:hover {
        background: #E2E8F0;
        color: #0F172A;
    }

    .emp-int-tab-btn.is-active {
        background: #4F48E6;
        color: #FFFFFF;
        font-weight: 700;
        box-shadow: 0 2px 8px rgba(79, 72, 230, 0.25);
    }

    /* ─── Schedule Card & Table ─── */
    .emp-int-card {
        background: #FFFFFF;
        border: 1px solid #E2E8F0;
        border-radius: 18px;
        padding: 24px 28px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
        box-sizing: border-box;
    }

    .emp-int-card-title {
        font-size: 18px;
        font-weight: 800;
        color: #0F172A;
        margin: 0 0 20px;
    }

    .emp-int-table-wrap {
        width: 100%;
        overflow-x: auto;
    }

    .emp-int-table {
        width: 100%;
        border-collapse: collapse;
        text-align: left;
    }

    .emp-int-table th {
        font-size: 11.5px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: #64748B;
        padding: 12px 16px 14px 0;
        border-bottom: 1px solid #F1F5F9;
    }

    .emp-int-table td {
        padding: 18px 16px 18px 0;
        border-bottom: 1px solid #F8FAFC;
        vertical-align: middle;
    }

    .emp-int-table tr:last-child td {
        border-bottom: none;
    }

    /* Candidate info cell */
    .emp-int-cand-cell {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .emp-int-cand-avatar {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        object-fit: cover;
        flex-shrink: 0;
        border: 1px solid #E2E8F0;
    }

    .emp-int-cand-name {
        font-size: 14px;
        font-weight: 700;
        color: #0F172A;
        margin: 0 0 2px;
        line-height: 1.25;
    }

    .emp-int-cand-email {
        font-size: 12px;
        color: #64748B;
        margin: 0;
    }

    .emp-int-role-text {
        font-size: 13.5px;
        color: #334155;
        font-weight: 500;
    }

    .emp-int-meta-text {
        font-size: 13px;
        color: #475569;
        font-weight: 500;
    }

    /* Buttons */
    .emp-btn-schedule-link {
        color: #4F48E6;
        font-size: 13.5px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.15s ease;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        white-space: nowrap !important;
        flex-shrink: 0 !important;
    }

    .emp-btn-schedule-link:hover {
        color: #3730A3;
        text-decoration: underline;
    }

    .emp-btn-start-primary {
        background: #4F48E6;
        color: #FFFFFF;
        font-size: 13px;
        font-weight: 700;
        padding: 9px 20px;
        border-radius: 10px;
        border: none;
        cursor: pointer;
        transition: all 0.15s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        box-shadow: 0 2px 8px rgba(79, 72, 230, 0.25);
        white-space: nowrap !important;
        flex-shrink: 0 !important;
    }

    .emp-btn-start-primary:hover {
        background: #4338CA;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(79, 72, 230, 0.35);
    }

    .emp-btn-start-outline {
        background: #EEF2FF;
        color: #4F48E6;
        border: 1px solid #C7D2FE;
        font-size: 13px;
        font-weight: 600;
        padding: 9px 20px;
        border-radius: 10px;
        cursor: pointer;
        transition: all 0.15s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        white-space: nowrap !important;
        flex-shrink: 0 !important;
    }

    .emp-btn-start-outline:hover {
        background: #E0E7FF;
        border-color: #A5B4FC;
    }

    /* Modal Backdrop */
    .emp-int-modal-backdrop {
        position: fixed;
        inset: 0;
        background: rgba(0, 6, 35, 0.55);
        backdrop-filter: blur(4px);
        z-index: 999;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
        box-sizing: border-box;
    }

    .emp-int-modal-card {
        background: #FFFFFF;
        border-radius: 20px;
        width: 100%;
        max-width: 480px;
        padding: 28px;
        box-shadow: 0 20px 40px rgba(0, 6, 35, 0.2);
        position: relative;
        box-sizing: border-box;
    }

    /* Responsive */
    @media (max-width: 1024px) {
        .emp-int-metrics-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 640px) {
        .emp-int-metrics-grid {
            grid-template-columns: 1fr;
        }
        .emp-int-card {
            padding: 18px 16px;
        }
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
            <div class="emp-interviews-page"
                 x-data="{
                     activeTab: (new URLSearchParams(window.location.search).get('tab') || 'shortlisted'),
                     sessions: <?= htmlspecialchars(json_encode($sessions ?? [], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE), ENT_QUOTES, 'UTF-8') ?>,
                     reportBase: '<?= url('/employer/interviews/') ?>',
                     employerRoomBase: '<?= url('/employer/interviews/room/') ?>',
                     candidateRoomBase: '<?= url('/candidate/interview/room/') ?>',
                     openStatuses: ['Scheduled', 'Confirmed', 'Ready', 'In progress'],
                     openSession(c) { return this.sessions.find((s) => s.candidate_id === c.id && s.meeting_type === 'virtual' && this.openStatuses.includes(s.status)) || null; },
                     isScheduled(c) { return !!this.openSession(c); },
                     get upcoming() { return this.sessions.filter((s) => this.openStatuses.includes(s.status)); },
                     get history() { return this.sessions.filter((s) => s.status === 'Completed'); },
                     get cancelled() { return this.sessions.filter((s) => s.status === 'Cancelled'); },
                     statusClass(s) { return { 'In progress': 'is-live', Completed: 'is-done', Cancelled: 'is-cancelled' }[s] || 'is-upcoming'; },
                     statusLabel(s) { return s === 'In progress' ? 'Live now' : s; },
                     copyLink(s) { navigator.clipboard.writeText(new URL(this.candidateRoomBase + s.id, window.location.origin).href).then(() => window.dispatchEvent(new CustomEvent('show-toast', { detail: { text: 'Candidate join link copied.', type: 'success' } }))); },
                     meetingModalOpen: false,
                     currentMeeting: null,

                     allCandidates: [
                         {
                             id: 1,
                             name: 'Sarah Peters',
                             email: 'sarah.p@intconnect.com',
                             avatar: 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=160&h=160&fit=crop&crop=faces',
                             role: 'UI Designer Intern',
                             status: 'shortlisted',
                             type: 'Virtual',
                             date_time: '27/09/2026 10:00 AM',
                             is_primary: true,
                             link: ''
                         },
                         {
                             id: 2,
                             name: 'Elena Daniels',
                             email: 'e.daniels@intconnect.com',
                             avatar: 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=160&h=160&fit=crop&crop=faces',
                             role: 'Data Science Intern',
                             status: 'shortlisted',
                             type: 'Virtual',
                             date_time: '30/09/2026 03:30 PM',
                             is_primary: false,
                             link: ''
                         },
                         {
                             id: 3,
                             name: 'Topper Andrew',
                             email: 'topper@intconnect.com',
                             avatar: 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=160&h=160&fit=crop&crop=faces',
                             role: 'Data Science Intern',
                             status: 'shortlisted',
                             type: 'Physical',
                             date_time: '02/10/2026 10:00 AM',
                             is_primary: false,
                             link: 'TechFlow HQ, 4th Floor, Victoria Island'
                         }
                     ],

                     startInterview(candidate) {
                         this.currentMeeting = candidate;
                         this.meetingModalOpen = true;
                     }
                 }">

                <!-- ── 1. Page Header ── -->
                <div class="emp-int-header">
                    <h1 class="emp-int-title">Interviews</h1>
                    <p class="emp-int-subtitle">Schedule and manage candidate interviews</p>
                </div>

                <!-- ── 2. Top 4 Metrics Cards ── -->
                <div class="emp-int-metrics-grid">
                    <!-- 1. Scheduled Interviews -->
                    <div class="emp-int-metric-card">
                        <div class="emp-int-metric-icon icon-purple">
                            <svg style="width: 20px; height: 20px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div class="emp-int-metric-lbl">Scheduled Interviews</div>
                        <div class="emp-int-metric-val">12</div>
                    </div>

                    <!-- 2. Completed Interviews -->
                    <div class="emp-int-metric-card">
                        <div class="emp-int-metric-icon icon-purple">
                            <svg style="width: 20px; height: 20px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="emp-int-metric-lbl">Completed Interviews</div>
                        <div class="emp-int-metric-val">08</div>
                    </div>

                    <!-- 3. Upcoming Interviews -->
                    <div class="emp-int-metric-card">
                        <div class="emp-int-metric-icon icon-purple">
                            <svg style="width: 20px; height: 20px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="emp-int-metric-lbl">Upcoming Interviews</div>
                        <div class="emp-int-metric-val">04</div>
                    </div>

                    <!-- 4. Cancelled Interviews -->
                    <div class="emp-int-metric-card is-danger">
                        <div class="emp-int-metric-icon icon-danger">
                            <svg style="width: 20px; height: 20px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="emp-int-metric-lbl">Cancelled Interviews</div>
                        <div class="emp-int-metric-val">04</div>
                    </div>
                </div>

                <!-- ── 3. Pill Tabs Row ── -->
                <div class="emp-int-tabs-row">
                    <button type="button"
                            class="emp-int-tab-btn"
                            :class="{ 'is-active': activeTab === 'all' }"
                            @click="activeTab = 'all'">
                        All Interviews
                    </button>

                    <button type="button"
                            class="emp-int-tab-btn"
                            :class="{ 'is-active': activeTab === 'shortlisted' }"
                            @click="activeTab = 'shortlisted'">
                        Shortlisted
                    </button>

                    <button type="button"
                            class="emp-int-tab-btn"
                            :class="{ 'is-active': activeTab === 'upcoming' }"
                            @click="activeTab = 'upcoming'">
                        Upcoming
                    </button>

                    <button type="button"
                            class="emp-int-tab-btn"
                            :class="{ 'is-active': activeTab === 'history' }"
                            @click="activeTab = 'history'">
                        History
                    </button>

                    <button type="button"
                            class="emp-int-tab-btn"
                            :class="{ 'is-active': activeTab === 'cancelled' }"
                            @click="activeTab = 'cancelled'">
                        Cancelled
                    </button>
                </div>

                <!-- ── 4. Main Interview Schedule Card ── -->
                <div class="emp-int-card">
                    <h2 class="emp-int-card-title">Interview Schedule</h2>

                    <div class="emp-int-table-wrap">

                        <!-- TAB A: Shortlisted Table (Screenshot 3) -->
                        <table class="emp-int-table" x-show="activeTab === 'shortlisted' || activeTab === 'all'">
                            <thead>
                                <tr>
                                    <th style="width: 42%;">Candidates</th>
                                    <th style="width: 38%;">Internship Role</th>
                                    <th style="width: 20%; text-align: right;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template x-for="c in allCandidates" :key="c.id">
                                    <tr>
                                        <!-- Candidate Info -->
                                        <td>
                                            <div class="emp-int-cand-cell">
                                                <img :src="c.avatar" :alt="c.name" class="emp-int-cand-avatar">
                                                <div>
                                                    <div class="emp-int-cand-name" x-text="c.name"></div>
                                                    <div class="emp-int-cand-email" x-text="c.email"></div>
                                                </div>
                                            </div>
                                        </td>

                                        <!-- Internship Role -->
                                        <td>
                                            <span class="emp-int-role-text" x-text="c.role"></span>
                                        </td>

                                        <!-- Action Link to Schedule Interview -->
                                        <td style="text-align: right;">
                                            <div style="display: inline-flex; align-items: center; gap: 12px; justify-content: flex-end; flex-wrap: wrap;">
                                                <a x-show="isScheduled(c)" :href="isScheduled(c) ? employerRoomBase + openSession(c).id : '#'" class="emp-btn-start-primary" style="text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
                                                    <svg style="width: 14px; height: 14px; flex-shrink: 0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                                    <span>Join room</span>
                                                </a>
                                                <a :href="'<?= url('/employer/interviews/schedule/') ?>' + c.id"
                                                   class="emp-btn-schedule-link" x-text="isScheduled(c) ? 'Reschedule' : 'Schedule Interview'">
                                                    Schedule Interview
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>

                        <!-- TAB B: Upcoming sessions (each has its own video room) -->
                        <div x-show="activeTab === 'upcoming'" style="display: none;">
                            <table class="emp-int-table eis-table" x-show="upcoming.length">
                                <thead>
                                    <tr><th>Candidate</th><th>Date &amp; time</th><th>Length</th><th>Status</th><th style="text-align: right;">Action</th></tr>
                                </thead>
                                <tbody>
                                    <template x-for="s in upcoming" :key="'up-' + s.id">
                                        <tr>
                                            <td><div class="emp-int-cand-name" x-text="s.candidate_name"></div><div class="emp-int-cand-email" x-text="s.candidate_role"></div></td>
                                            <td class="eis-nowrap"><span x-text="s.when"></span><span class="eis-sub" x-text="s.when_time"></span></td>
                                            <td class="eis-nowrap" x-text="s.duration_min + ' min'"></td>
                                            <td><span class="eis-badge" :class="statusClass(s.status)" x-text="statusLabel(s.status)"></span></td>
                                            <td style="text-align: right;">
                                                <div class="eis-actions">
                                                    <template x-if="s.meeting_type === 'virtual'">
                                                        <a :href="employerRoomBase + s.id" class="emp-btn-start-primary" style="text-decoration: none;">Join room</a>
                                                    </template>
                                                    <template x-if="s.meeting_type === 'virtual'">
                                                        <button type="button" class="eis-ghost" @click="copyLink(s)">Copy link</button>
                                                    </template>
                                                    <span x-show="s.meeting_type !== 'virtual'" class="eis-sub">In person</span>
                                                </div>
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                            <p class="eis-empty" x-show="!upcoming.length">No upcoming interviews. Schedule one from the Shortlisted tab.</p>
                        </div>

                        <!-- TAB C: History (completed sessions with reports) -->
                        <div x-show="activeTab === 'history'" style="display: none;">
                            <table class="emp-int-table eis-table" x-show="history.length">
                                <thead>
                                    <tr><th>Candidate</th><th>Date</th><th>Length</th><th>Result</th><th style="text-align: right;">Report</th></tr>
                                </thead>
                                <tbody>
                                    <template x-for="s in history" :key="'h-' + s.id">
                                        <tr>
                                            <td><div class="emp-int-cand-name" x-text="s.candidate_name"></div><div class="emp-int-cand-email" x-text="s.candidate_role"></div></td>
                                            <td class="eis-nowrap"><span x-text="s.when"></span><span class="eis-sub" x-text="s.when_time"></span></td>
                                            <td class="eis-nowrap" x-text="s.duration_min + ' min'"></td>
                                            <td>
                                                <span class="eis-result" x-show="s.recommendation" x-text="s.recommendation + (s.score_average ? ' · ' + s.score_average + '/5' : '')"></span>
                                                <span class="eis-sub" x-show="!s.recommendation">Not scored</span>
                                            </td>
                                            <td style="text-align: right;"><a :href="reportBase + s.id + '/report'" class="emp-btn-schedule-link">View report</a></td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                            <p class="eis-empty" x-show="!history.length">Finished interviews appear here with their summary, scorecard and shared files.</p>
                        </div>

                        <!-- TAB D: Cancelled -->
                        <div x-show="activeTab === 'cancelled'" style="display: none;">
                            <table class="emp-int-table eis-table" x-show="cancelled.length">
                                <thead>
                                    <tr><th>Candidate</th><th>Was scheduled for</th><th>Status</th><th style="text-align: right;">Action</th></tr>
                                </thead>
                                <tbody>
                                    <template x-for="s in cancelled" :key="'c-' + s.id">
                                        <tr>
                                            <td><div class="emp-int-cand-name" x-text="s.candidate_name"></div><div class="emp-int-cand-email" x-text="s.candidate_role"></div></td>
                                            <td class="eis-nowrap"><span x-text="s.when"></span><span class="eis-sub" x-text="s.when_time"></span></td>
                                            <td><span class="eis-badge is-cancelled">Cancelled</span></td>
                                            <td style="text-align: right;"><a :href="'<?= url('/employer/interviews/schedule/') ?>' + s.candidate_id" class="emp-btn-schedule-link">Reschedule</a></td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                            <p class="eis-empty" x-show="!cancelled.length">No cancelled interviews.</p>
                        </div>

                    </div>
                </div>

                <style>
                .eis-table th, .eis-table td { white-space: normal; }
                .eis-nowrap { white-space: nowrap !important; }
                .eis-sub { display: block; font-size: 12px; color: #64748B; margin-top: 2px; white-space: nowrap; }
                .eis-actions { display: inline-flex; align-items: center; gap: 8px; justify-content: flex-end; flex-wrap: wrap; }
                .eis-ghost { height: 34px; padding: 0 12px; border: 1px solid #E2E8F0; border-radius: 9px; background: #FFFFFF; color: #334155; font-size: 12.5px; font-weight: 600; cursor: pointer; white-space: nowrap; }
                .eis-ghost:hover { background: #F8FAFC; border-color: #CBD5E1; }
                .eis-badge { display: inline-flex; align-items: center; padding: 3px 10px; border-radius: 9999px; font-size: 11.5px; font-weight: 700; white-space: nowrap; }
                .eis-badge.is-upcoming { background: #EEF2FF; color: #4F48E6; }
                .eis-badge.is-live { background: #ECFDF5; color: #047857; }
                .eis-badge.is-done { background: #F1F5F9; color: #475569; }
                .eis-badge.is-cancelled { background: #FEF2F2; color: #B91C1C; }
                .eis-result { font-size: 13px; font-weight: 700; color: #0F172A; white-space: nowrap; }
                .eis-empty { margin: 0; padding: 32px 12px; text-align: center; font-size: 13px; color: #64748B; }
                </style>
            </div>
        </div>

    </div>
</div>
