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
                     activeTab: 'shortlisted',
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
                             link: 'https://meet.google.com/sarah-peters-ux'
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
                             link: 'https://meet.google.com/elena-daniels-ds'
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
                                            <a :href="'<?= url('/employer/interviews/schedule/') ?>' + c.id" 
                                               class="emp-btn-schedule-link">
                                                Schedule Interview
                                            </a>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>

                        <!-- TAB B: Upcoming Table (Screenshot 4) -->
                        <table class="emp-int-table" x-show="activeTab === 'upcoming'">
                            <thead>
                                <tr>
                                    <th style="width: 30%;">Candidates</th>
                                    <th style="width: 24%;">Internship Role</th>
                                    <th style="width: 14%;">Type</th>
                                    <th style="width: 18%;">Date & Time</th>
                                    <th style="width: 14%; text-align: right;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template x-for="c in allCandidates" :key="'up-' + c.id">
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

                                        <!-- Type -->
                                        <td>
                                            <span class="emp-int-meta-text" x-text="c.type"></span>
                                        </td>

                                        <!-- Date & Time -->
                                        <td>
                                            <span class="emp-int-meta-text" x-text="c.date_time"></span>
                                        </td>

                                        <!-- Start Interview Button -->
                                        <td style="text-align: right;">
                                            <button type="button" 
                                                    :class="c.is_primary ? 'emp-btn-start-primary' : 'emp-btn-start-outline'"
                                                    @click="startInterview(c)">
                                                Start Interview
                                            </button>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>

                        <!-- TAB C: Cancelled Table -->
                        <table class="emp-int-table" x-show="activeTab === 'cancelled'">
                            <thead>
                                <tr>
                                    <th style="width: 32%;">Candidates</th>
                                    <th style="width: 26%;">Internship Role</th>
                                    <th style="width: 18%;">Status</th>
                                    <th style="width: 24%; text-align: right;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <div class="emp-int-cand-cell">
                                            <img src="https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=160&h=160&fit=crop&crop=faces" alt="David Adeleke" class="emp-int-cand-avatar">
                                            <div>
                                                <div class="emp-int-cand-name">David Adeleke</div>
                                                <div class="emp-int-cand-email">david.a@intconnect.com</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="emp-int-role-text">Backend Engineering Intern</span></td>
                                    <td><span class="emp-status-pill emp-status-rejected">Cancelled</span></td>
                                    <td style="text-align: right;">
                                        <a href="<?= url('/employer/interviews/schedule/1') ?>" class="emp-btn-schedule-link">
                                            Reschedule
                                        </a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                    </div>
                </div>

                <!-- ── 5. Start Interview Dialog Modal ── -->
                <div class="emp-int-modal-backdrop" 
                     x-show="meetingModalOpen" 
                     x-cloak
                     @click.self="meetingModalOpen = false"
                     x-transition:enter="transition ease-out duration-150"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="transition ease-in duration-100"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     style="display: none;">
                    
                    <div class="emp-int-modal-card" 
                         x-show="meetingModalOpen"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 transform scale-95"
                         x-transition:enter-end="opacity-100 transform scale-100">
                        
                        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px;">
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <div style="width: 36px; height: 36px; border-radius: 10px; background: #EEF2FF; color: #4F48E6; display: flex; align-items: center; justify-content: center;">
                                    <svg style="width: 18px; height: 18px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 style="font-size: 16px; font-weight: 800; color: #0F172A; margin: 0;">Start Virtual Interview</h3>
                                    <p style="font-size: 12px; color: #64748B; margin: 0;">Candidate: <strong x-text="currentMeeting ? currentMeeting.name : ''"></strong></p>
                                </div>
                            </div>
                            <button type="button" @click="meetingModalOpen = false" style="background: transparent; border: none; color: #94A3B8; cursor: pointer; padding: 4px;">
                                <svg style="width: 18px; height: 18px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                            </button>
                        </div>

                        <div style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 12px; padding: 14px; margin-bottom: 20px;">
                            <div style="font-size: 11px; font-weight: 700; text-transform: uppercase; color: #64748B; margin-bottom: 6px;">In-Platform Video Room Link</div>
                            <div style="display: flex; align-items: center; justify-content: space-between; gap: 8px;">
                                <span style="font-size: 13px; font-weight: 600; color: #4F48E6; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" x-text="'<?= url('/employer/interviews/room/') ?>' + (currentMeeting ? currentMeeting.id : 1)"></span>
                                <button type="button" 
                                        @click="navigator.clipboard.writeText('<?= url('/candidate/interview/room/') ?>' + (currentMeeting ? currentMeeting.id : 1)); $dispatch('show-toast', { text: 'In-platform meeting link copied to clipboard!', type: 'info' })" 
                                        style="background: #FFFFFF; border: 1px solid #CBD5E1; color: #334155; padding: 5px 10px; border-radius: 8px; font-size: 12px; font-weight: 600; cursor: pointer; flex-shrink: 0; white-space: nowrap !important;">
                                    Copy Link
                                </button>
                            </div>
                        </div>

                        <div style="display: flex; gap: 10px;">
                            <button type="button" 
                                    @click="meetingModalOpen = false" 
                                    style="flex: 1; height: 42px; border: 1px solid #CBD5E1; background: #FFFFFF; color: #475569; font-size: 13px; font-weight: 600; border-radius: 10px; cursor: pointer; white-space: nowrap !important; flex-shrink: 0 !important;">
                                Close
                            </button>
                            <a :href="'<?= url('/employer/interviews/room/') ?>' + (currentMeeting ? currentMeeting.id : 1)" 
                               style="flex: 2; height: 42px; background: #4F48E6; color: #FFFFFF; font-size: 13px; font-weight: 700; border-radius: 10px; text-decoration: none; display: inline-flex; align-items: center; justify-content: center; gap: 8px; box-shadow: 0 2px 8px rgba(79, 72, 230, 0.25); white-space: nowrap !important; flex-shrink: 0 !important;">
                                <svg style="width: 15px; height: 15px; flex-shrink: 0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                <span>Enter Video Room</span>
                            </a>
                        </div>

                    </div>
                </div>

            </div>
        </div>

    </div>
</div>
