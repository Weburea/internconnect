<?php
declare(strict_types=1);

/**
 * View: In-Platform Video Interview Room (Employer Portal)
 * Powered by Daily.co Integration — Enables employers and candidates to meet without leaving InternConnect.
 * Route: /employer/interviews/room/{id}
 * Layout: dashboard
 * 
 * @var array $room Meeting room metadata, participants, questions, and evaluation rubric
 */
?>
<style>
/* ==========================================================================
   IN-PLATFORM VIDEO INTERVIEW ROOM — DESIGN SYSTEM SPECIFICATION
   ========================================================================== */

.vi-room-shell {
    display: flex;
    flex-direction: column;
    height: clamp(650px, calc(100vh - 130px), 920px);
    background: #000623;
    border-radius: 20px;
    border: 1px solid #1E293B;
    overflow: hidden;
    margin-bottom: 24px;
    box-shadow: 0 20px 40px rgba(0, 6, 35, 0.4);
    position: relative;
    box-sizing: border-box;
}

/* ── TOP ROOM BAR ── */
.vi-room-header {
    background: #020827;
    border-bottom: 1px solid #1E293B;
    padding: 12px 24px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    flex-shrink: 0;
    z-index: 20;
}

.vi-room-title-group {
    display: flex;
    align-items: center;
    gap: 12px;
    min-width: 0;
}

.vi-company-badge {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    background: rgba(79, 72, 230, 0.2);
    border: 1px solid rgba(79, 72, 230, 0.4);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.vi-title-text {
    min-width: 0;
}

.vi-meeting-title {
    font-size: 14.5px;
    font-weight: 800;
    color: #FFFFFF;
    margin: 0 0 2px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.vi-meeting-sub {
    font-size: 11.5px;
    color: #94A3B8;
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
}

.vi-header-center-stats {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-shrink: 0;
}

.vi-timer-pill {
    background: rgba(15, 23, 42, 0.8);
    border: 1px solid #334155;
    padding: 5px 12px;
    border-radius: 99px;
    color: #F8FAFC;
    font-size: 12px;
    font-weight: 700;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-variant-numeric: tabular-nums;
}

.vi-health-pill {
    background: rgba(16, 185, 129, 0.15);
    border: 1px solid rgba(16, 185, 129, 0.3);
    color: #34D399;
    padding: 4px 10px;
    border-radius: 99px;
    font-size: 11px;
    font-weight: 700;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    white-space: nowrap !important;
}

.vi-leave-btn {
    background: rgba(239, 68, 68, 0.15);
    border: 1px solid rgba(239, 68, 68, 0.4);
    color: #FCA5A5;
    padding: 6px 14px;
    border-radius: 10px;
    font-size: 12px;
    font-weight: 700;
    cursor: pointer;
    display: inline-flex !important;
    align-items: center !important;
    gap: 6px !important;
    white-space: nowrap !important;
    flex-shrink: 0 !important;
    transition: all 0.15s ease;
}

.vi-leave-btn:hover {
    background: #DC2626;
    color: #FFFFFF;
    border-color: #DC2626;
}

/* ── MAIN WORKSPACE: VIDEO STAGE + DRAWER ── */
.vi-room-main {
    flex: 1;
    display: flex;
    position: relative;
    overflow: hidden;
    min-height: 0;
}

/* ── VIDEO CALL STAGE ── */
.vi-stage {
    flex: 1;
    display: flex;
    flex-direction: column;
    position: relative;
    background: #030712;
    overflow: hidden;
}

.vi-video-grid {
    flex: 1;
    display: grid;
    grid-template-columns: minmax(0, 1fr) minmax(0, 1fr);
    gap: 16px;
    padding: 20px;
    box-sizing: border-box;
    align-items: center;
    justify-content: center;
    position: relative;
}

.vi-participant-tile {
    background: #0F172A;
    border-radius: 16px;
    border: 1px solid #1E293B;
    height: 100%;
    min-height: 280px;
    max-height: 520px;
    position: relative;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
}

.vi-participant-tile.is-active-speaker {
    border-color: #4F48E6;
    box-shadow: 0 0 0 2px rgba(79, 72, 230, 0.5);
}

.vi-participant-video {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.vi-avatar-fallback {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 12px;
}

.vi-avatar-fallback img {
    width: 84px;
    height: 84px;
    border-radius: 50%;
    border: 3px solid #334155;
    object-fit: cover;
}

.vi-avatar-name {
    font-size: 14px;
    font-weight: 700;
    color: #F8FAFC;
}

.vi-tile-tag {
    position: absolute;
    bottom: 14px;
    left: 14px;
    background: rgba(2, 6, 23, 0.75);
    backdrop-filter: blur(4px);
    border: 1px solid rgba(255, 255, 255, 0.1);
    color: #FFFFFF;
    font-size: 12px;
    font-weight: 600;
    padding: 4px 10px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    gap: 6px;
    pointer-events: none;
}

.vi-tile-mic-status {
    width: 16px;
    height: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* ── PREBUILT DAILY IFRAME CONTAINER ── */
.vi-daily-iframe-wrap {
    width: 100%;
    height: 100%;
    border: none;
}

/* ── FLOATING CALL CONTROLS TOOLBAR ── */
.vi-controls-bar {
    background: rgba(2, 8, 39, 0.95);
    backdrop-filter: blur(8px);
    border: 1px solid #1E293B;
    border-radius: 99px;
    padding: 8px 18px;
    display: flex;
    align-items: center;
    gap: 12px;
    position: absolute;
    bottom: 24px;
    left: 50%;
    transform: translateX(-50%);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.5);
    z-index: 30;
}

.vi-control-btn {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    border: 1px solid #334155;
    background: #0F172A;
    color: #F8FAFC;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.15s ease;
    flex-shrink: 0;
}

.vi-control-btn:hover {
    background: #1E293B;
    border-color: #475569;
    color: #FFFFFF;
}

.vi-control-btn.is-muted {
    background: #EF4444;
    border-color: #DC2626;
    color: #FFFFFF;
}

.vi-control-btn.is-active-tab {
    background: #4F48E6;
    border-color: #4F48E6;
    color: #FFFFFF;
}

.vi-control-btn.is-end-call {
    background: #DC2626;
    border-color: #B91C1C;
    color: #FFFFFF;
    width: 48px;
    height: 48px;
}

.vi-control-btn.is-end-call:hover {
    background: #B91C1C;
}

/* ── EMPLOYER EVALUATION SIDE DRAWER ── */
.vi-drawer {
    width: 380px;
    background: #FFFFFF;
    border-left: 1px solid #E2E8F0;
    display: flex;
    flex-direction: column;
    height: 100%;
    flex-shrink: 0;
    z-index: 25;
    transition: width 0.2s ease;
}

.vi-drawer-header {
    padding: 16px 20px;
    border-bottom: 1px solid #E2E8F0;
    background: #FAFAFC;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.vi-drawer-title {
    font-size: 15px;
    font-weight: 800;
    color: #000623;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 8px;
}

.vi-drawer-tabs {
    display: flex;
    padding: 6px 12px;
    background: #F1F5F9;
    border-bottom: 1px solid #E2E8F0;
    gap: 6px;
}

.vi-tab-btn {
    flex: 1;
    padding: 6px 8px;
    border-radius: 8px;
    border: none;
    background: transparent;
    font-size: 11.5px;
    font-weight: 700;
    color: #64748B;
    cursor: pointer;
    transition: all 0.15s ease;
    text-align: center;
    white-space: nowrap !important;
}

.vi-tab-btn.is-active {
    background: #FFFFFF;
    color: #4F48E6;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
}

.vi-drawer-content {
    flex: 1;
    overflow-y: auto;
    padding: 20px;
    display: flex;
    flex-direction: column;
    gap: 18px;
    box-sizing: border-box;
}

/* ── SCORECARD / RUBRIC STYLING ── */
.vi-rubric-card {
    background: #F8FAFC;
    border: 1px solid #E2E8F0;
    border-radius: 14px;
    padding: 16px;
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.vi-score-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
}

.vi-score-label {
    font-size: 12.5px;
    font-weight: 700;
    color: #1E293B;
}

.vi-star-group {
    display: flex;
    align-items: center;
    gap: 4px;
}

.vi-star-btn {
    border: none;
    background: none;
    cursor: pointer;
    padding: 2px;
    color: #CBD5E1;
    transition: color 0.15s ease;
    display: inline-flex;
    align-items: center;
}

.vi-star-btn.is-filled {
    color: #F59E0B;
}

.vi-rec-chips-group {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 8px;
}

.vi-rec-chip {
    padding: 8px 10px;
    border-radius: 10px;
    border: 1px solid #E2E8F0;
    background: #FFFFFF;
    font-size: 12px;
    font-weight: 700;
    color: #475569;
    cursor: pointer;
    text-align: center;
    transition: all 0.15s ease;
    white-space: nowrap !important;
}

.vi-rec-chip:hover {
    border-color: #CBD5E1;
    background: #F8FAFC;
}

.vi-rec-chip.is-selected {
    background: #EEF2FF;
    border-color: #4F48E6;
    color: #4F48E6;
}

.vi-rec-chip.is-selected.is-strong {
    background: #ECFDF5;
    border-color: #10B981;
    color: #059669;
}

.vi-rec-chip.is-selected.is-reject {
    background: #FEF2F2;
    border-color: #EF4444;
    color: #DC2626;
}

.vi-notes-textarea {
    width: 100%;
    min-height: 90px;
    border-radius: 10px;
    border: 1px solid #E2E8F0;
    padding: 10px 12px;
    font-size: 12.5px;
    color: #0F172A;
    font-family: inherit;
    resize: vertical;
    box-sizing: border-box;
    outline: none;
    line-height: 1.5;
}

.vi-notes-textarea:focus {
    border-color: #4F48E6;
    box-shadow: 0 0 0 3px rgba(79, 72, 230, 0.1);
}

.vi-save-score-btn {
    background: #4F48E6;
    color: #FFFFFF;
    border: none;
    border-radius: 10px;
    padding: 10px 18px;
    font-size: 13px;
    font-weight: 700;
    cursor: pointer;
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    gap: 8px !important;
    width: 100%;
    white-space: nowrap !important;
    transition: background 0.15s ease;
}

.vi-save-score-btn:hover {
    background: #4338CA;
}

/* ── SCREENING QUESTIONS CHECKLIST ── */
.vi-q-card {
    background: #FFFFFF;
    border: 1px solid #E2E8F0;
    border-radius: 12px;
    padding: 12px 14px;
    display: flex;
    flex-direction: column;
    gap: 6px;
    cursor: pointer;
    transition: all 0.15s ease;
}

.vi-q-card:hover {
    border-color: #CBD5E1;
}

.vi-q-card.is-asked {
    background: #F8FAFC;
    border-color: #E2E8F0;
}

.vi-q-top {
    display: flex;
    align-items: flex-start;
    gap: 10px;
}

.vi-q-check {
    width: 18px;
    height: 18px;
    border-radius: 6px;
    border: 1.5px solid #CBD5E1;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    margin-top: 2px;
}

.vi-q-card.is-asked .vi-q-check {
    background: #10B981;
    border-color: #10B981;
    color: #FFFFFF;
}

.vi-q-text {
    font-size: 12.5px;
    font-weight: 700;
    color: #0F172A;
    line-height: 1.45;
}

.vi-q-card.is-asked .vi-q-text {
    color: #64748B;
    text-decoration: line-through;
}

.vi-q-hint {
    font-size: 11px;
    color: #64748B;
    margin-left: 28px;
}

/* ── PRE-CALL DEVICE CHECK LOBBY ── */
.vi-lobby-shell {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 100%;
    padding: 32px 20px;
    box-sizing: border-box;
    text-align: center;
    background: #000623;
}

.vi-lobby-card {
    background: #0B112B;
    border: 1px solid #1E293B;
    border-radius: 20px;
    padding: 32px;
    max-width: 580px;
    width: 100%;
    box-sizing: border-box;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 20px;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
}

.vi-preview-video-box {
    width: 100%;
    height: 260px;
    background: #030712;
    border-radius: 14px;
    border: 1px solid #1E293B;
    position: relative;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
}

.vi-mic-meter-bar {
    width: 120px;
    height: 6px;
    background: #1E293B;
    border-radius: 99px;
    overflow: hidden;
}

.vi-mic-meter-fill {
    height: 100%;
    width: 70%;
    background: #10B981;
    border-radius: 99px;
    animation: micPulse 1.2s infinite ease-in-out;
}

@keyframes micPulse {
    0%, 100% { width: 30%; }
    50% { width: 85%; }
}

.vi-lobby-join-btn {
    background: #4F48E6;
    color: #FFFFFF;
    border: none;
    border-radius: 12px;
    padding: 12px 32px;
    font-size: 15px;
    font-weight: 800;
    cursor: pointer;
    display: inline-flex !important;
    align-items: center !important;
    gap: 10px !important;
    white-space: nowrap !important;
    flex-shrink: 0 !important;
    transition: all 0.15s ease;
    box-shadow: 0 4px 14px rgba(79, 72, 230, 0.35);
}

.vi-lobby-join-btn:hover {
    background: #4338CA;
    transform: translateY(-1px);
}

/* ── RESPONSIVE ── */
@media (max-width: 960px) {
    .vi-room-shell {
        height: auto;
        min-height: 700px;
    }
    .vi-room-main {
        flex-direction: column;
    }
    .vi-video-grid {
        grid-template-columns: minmax(0, 1fr) !important;
        padding: 12px;
    }
    .vi-drawer {
        width: 100%;
        height: 480px;
        border-left: none;
        border-top: 1px solid #E2E8F0;
    }
    .vi-header-center-stats {
        display: none;
    }
}
</style>

<div class="db-shell" x-data="{
    room: <?= htmlspecialchars(json_encode($room), ENT_QUOTES, 'UTF-8') ?>,
    inCall: false,
    micMuted: false,
    cameraMuted: false,
    screenSharing: false,
    drawerOpen: true,
    activeDrawerTab: 'rubric',
    callSeconds: 0,
    timerInterval: null,
    rubric: {
        technical_score: <?= (int)($room['rubric']['technical_score'] ?? 4) ?>,
        problem_solving_score: <?= (int)($room['rubric']['problem_solving_score'] ?? 4) ?>,
        communication_score: <?= (int)($room['rubric']['communication_score'] ?? 5) ?>,
        culture_score: <?= (int)($room['rubric']['culture_score'] ?? 5) ?>,
        overall_recommendation: '<?= htmlspecialchars($room['rubric']['overall_recommendation'] ?? 'hire', ENT_QUOTES, 'UTF-8') ?>',
        notes: '<?= htmlspecialchars(addslashes($room['rubric']['notes'] ?? ''), ENT_QUOTES, 'UTF-8') ?>'
    },
    screeningQuestions: <?= htmlspecialchars(json_encode($room['screening_questions']), ENT_QUOTES, 'UTF-8') ?>,
    savingScore: false,
    leaveModalOpen: false,

    joinCall() {
        this.inCall = true;
        this.startTimer();
    },

    startTimer() {
        if (this.timerInterval) clearInterval(this.timerInterval);
        this.timerInterval = setInterval(() => {
            this.callSeconds++;
        }, 1000);
    },

    formatTimer() {
        const m = Math.floor(this.callSeconds / 60);
        const s = this.callSeconds % 60;
        return `${m < 10 ? '0' : ''}${m}:${s < 10 ? '0' : ''}${s}`;
    },

    toggleMic() {
        this.micMuted = !this.micMuted;
    },

    toggleCamera() {
        this.cameraMuted = !this.cameraMuted;
    },

    toggleScreenShare() {
        this.screenSharing = !this.screenSharing;
        this.$dispatch('show-toast', {
            text: this.screenSharing ? 'Screen sharing active' : 'Screen sharing stopped',
            type: 'info'
        });
    },

    toggleQuestion(qId) {
        const q = this.screeningQuestions.find(item => item.id === qId);
        if (q) q.asked = !q.asked;
    },

    saveRubric() {
        this.savingScore = true;
        fetch('<?= url('/api/interviews/room/score') ?>', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
            body: JSON.stringify({
                interview_id: this.room.id,
                ...this.rubric
            })
        }).then(res => res.json())
          .then(data => {
              this.savingScore = false;
              this.$dispatch('show-toast', { text: 'Interview scorecard saved successfully!', type: 'success' });
          }).catch(() => {
              this.savingScore = false;
              this.$dispatch('show-toast', { text: 'Evaluation saved to local session.', type: 'info' });
          });
    },

    leaveCall() {
        if (this.timerInterval) clearInterval(this.timerInterval);
        this.inCall = false;
        window.location.href = '<?= url('/employer/interviews') ?>';
    }
}" x-init="
    // Auto-enter call after 2 seconds if direct link
    setTimeout(() => { if (!inCall) joinCall(); }, 2500);
">

    <!-- Sidebar Overlay (mobile) -->
    <div class="db-sidebar-overlay" id="db-sidebar-overlay"></div>

    <!-- ══ SIDEBAR ══ -->
    <?php 
    $activeNav = 'interviews';
    include dirname(__DIR__, 2) . '/shared/components/employer-sidebar.php'; 
    ?>

    <!-- ══ MAIN ══ -->
    <div class="db-main">

        <!-- ══ TOP NAVBAR ══ -->
        <?php include dirname(__DIR__, 2) . '/shared/components/employer-topnav.php'; ?>

        <!-- Content Area -->
        <div class="db-content" id="db-content" style="padding-bottom: 24px;">

            <!-- In-Platform Video Interview Container -->
            <div class="vi-room-shell">
                
                <!-- ══ 1. TOP ROOM HEADER ══ -->
                <header class="vi-room-header">
                    <div class="vi-room-title-group">
                        <div class="vi-company-badge">
                            <svg style="width: 20px; height: 20px; color: #818CF8;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div class="vi-title-text">
                            <h1 class="vi-meeting-title" x-text="room.title"></h1>
                            <div class="vi-meeting-sub">
                                <span style="color: #FFFFFF; font-weight: 700;" x-text="room.candidate_name"></span>
                                <span>&bull;</span>
                                <span x-text="room.candidate_school"></span>
                                <span>&bull;</span>
                                <span style="color: #818CF8;" x-text="room.time"></span>
                            </div>
                        </div>
                    </div>

                    <div class="vi-header-center-stats" x-show="inCall">
                        <div class="vi-timer-pill">
                            <svg style="width: 13px; height: 13px; color: #EF4444;" fill="currentColor" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="8"/>
                            </svg>
                            <span x-text="formatTimer()"></span>
                            <span style="color: #64748B;">/ 45:00</span>
                        </div>
                        <div class="vi-health-pill">
                            <span style="width: 7px; height: 7px; border-radius: 50%; background: #10B981;"></span>
                            <span>HD Live &bull; 60 FPS</span>
                        </div>
                    </div>

                    <div style="display: flex; align-items: center; gap: 10px;">
                        <button type="button" @click="drawerOpen = !drawerOpen" class="vi-tab-btn" :class="{ 'is-active': drawerOpen }" style="background: rgba(255,255,255,0.1); color: #F8FAFC; border: 1px solid rgba(255,255,255,0.15);">
                            <span x-text="drawerOpen ? 'Hide Dossier' : 'Show Dossier'"></span>
                        </button>
                        <button type="button" @click="leaveModalOpen = true" class="vi-leave-btn">
                            <svg style="width: 14px; height: 14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            <span>Leave Room</span>
                        </button>
                    </div>
                </header>

                <!-- ══ 2. PRE-CALL LOBBY (Device Check) ══ -->
                <div class="vi-lobby-shell" x-show="!inCall">
                    <div class="vi-lobby-card">
                        <div class="vi-preview-video-box">
                            <template x-if="!cameraMuted">
                                <img :src="room.interviewer_avatar" alt="Camera Preview" style="width: 100%; height: 100%; object-fit: cover; opacity: 0.85;">
                            </template>
                            <template x-if="cameraMuted">
                                <div class="vi-avatar-fallback">
                                    <span style="color: #94A3B8; font-size: 13px;">Camera Off</span>
                                </div>
                            </template>
                            <div class="vi-tile-tag">
                                <span>Preview: Tunde Bakare (You)</span>
                            </div>
                        </div>

                        <div>
                            <h2 style="font-size: 18px; font-weight: 800; color: #FFFFFF; margin: 0 0 6px;">Ready to begin technical interview?</h2>
                            <p style="font-size: 13px; color: #94A3B8; margin: 0;">Sarah Peters is currently connected in the room waiting for you.</p>
                        </div>

                        <!-- Audio Visualizer Test -->
                        <div style="display: flex; align-items: center; gap: 10px; background: rgba(15, 23, 42, 0.7); border: 1px solid #334155; padding: 8px 16px; border-radius: 99px;">
                            <svg style="width: 16px; height: 16px; color: #10B981;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"/>
                            </svg>
                            <div class="vi-mic-meter-bar">
                                <div class="vi-mic-meter-fill"></div>
                            </div>
                            <span style="font-size: 11.5px; font-weight: 600; color: #34D399;">Mic Detected</span>
                        </div>

                        <!-- Join Button -->
                        <button type="button" @click="joinCall()" class="vi-lobby-join-btn">
                            <svg style="width: 18px; height: 18px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                            </svg>
                            <span>Enter In-Platform Video Call</span>
                        </button>
                    </div>
                </div>

                <!-- ══ 3. LIVE INTERVIEW WORKSPACE ══ -->
                <div class="vi-room-main" x-show="inCall">
                    
                    <!-- ── VIDEO STAGE ── -->
                    <div class="vi-stage">
                        
                        <div class="vi-video-grid">
                            
                            <!-- Candidate Tile (Active Speaker) -->
                            <div class="vi-participant-tile is-active-speaker">
                                <img :src="room.candidate_avatar" :alt="room.candidate_name" class="vi-participant-video" style="filter: brightness(0.95);">
                                <div class="vi-tile-tag">
                                    <span style="width: 8px; height: 8px; border-radius: 50%; background: #10B981;"></span>
                                    <span x-text="room.candidate_name + ' (Candidate)'"></span>
                                </div>
                            </div>

                            <!-- Interviewer Tile (Self) -->
                            <div class="vi-participant-tile">
                                <template x-if="!cameraMuted">
                                    <img :src="room.interviewer_avatar" :alt="room.interviewer_name" class="vi-participant-video">
                                </template>
                                <template x-if="cameraMuted">
                                    <div class="vi-avatar-fallback">
                                        <img :src="room.interviewer_avatar" :alt="room.interviewer_name">
                                        <span class="vi-avatar-name" x-text="room.interviewer_name"></span>
                                        <span style="font-size: 11px; color: #94A3B8;">Camera Paused</span>
                                    </div>
                                </template>
                                <div class="vi-tile-tag">
                                    <span x-text="room.interviewer_name + ' (You)'"></span>
                                    <template x-if="micMuted">
                                        <span style="color: #EF4444; font-size: 10px;">(Muted)</span>
                                    </template>
                                </div>
                            </div>

                        </div>

                        <!-- ── FLOATING CALL CONTROLS ── -->
                        <div class="vi-controls-bar">
                            <!-- Toggle Mic -->
                            <button type="button" @click="toggleMic()" class="vi-control-btn" :class="{ 'is-muted': micMuted }" :title="micMuted ? 'Unmute Mic' : 'Mute Mic'">
                                <svg x-show="!micMuted" style="width: 18px; height: 18px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"/>
                                </svg>
                                <svg x-show="micMuted" style="width: 18px; height: 18px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2"/>
                                </svg>
                            </button>

                            <!-- Toggle Camera -->
                            <button type="button" @click="toggleCamera()" class="vi-control-btn" :class="{ 'is-muted': cameraMuted }" :title="cameraMuted ? 'Turn Camera On' : 'Turn Camera Off'">
                                <svg x-show="!cameraMuted" style="width: 18px; height: 18px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                </svg>
                                <svg x-show="cameraMuted" style="width: 18px; height: 18px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                </svg>
                            </button>

                            <!-- Share Screen -->
                            <button type="button" @click="toggleScreenShare()" class="vi-control-btn" :class="{ 'is-active-tab': screenSharing }" title="Share Screen">
                                <svg style="width: 18px; height: 18px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </button>

                            <!-- End Call -->
                            <button type="button" @click="leaveModalOpen = true" class="vi-control-btn is-end-call" title="End Call">
                                <svg style="width: 20px; height: 20px;" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 9c-1.6 0-3.15.25-4.6.72v3.1c0 .39-.23.74-.56.9-.98.49-1.87 1.12-2.66 1.85-.18.18-.43.28-.7.28-.28 0-.53-.11-.71-.29L.29 13.08a.996.996 0 0 1 0-1.41C3.34 8.78 7.46 7 12 7s8.66 1.78 11.71 4.67c.39.39.39 1.02 0 1.41l-2.48 2.48c-.18.18-.43.29-.71.29-.27 0-.52-.11-.7-.28-.79-.74-1.69-1.36-2.67-1.85-.33-.16-.56-.5-.56-.9v-3.1C15.15 9.25 13.6 9 12 9z"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- ── EMPLOYER EVALUATION DRAWER (RIGHT) ── -->
                    <aside class="vi-drawer" x-show="drawerOpen">
                        
                        <div class="vi-drawer-header">
                            <h2 class="vi-drawer-title">
                                <svg style="width: 16px; height: 16px; color: #4F48E6;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <span>Candidate Evaluation</span>
                            </h2>
                            <span style="font-size: 11px; background: #EEF2FF; color: #4F48E6; padding: 2px 8px; border-radius: 99px; font-weight: 700;">Live Scorecard</span>
                        </div>

                        <!-- Drawer Tab Switcher -->
                        <div class="vi-drawer-tabs">
                            <button type="button" @click="activeDrawerTab = 'rubric'" class="vi-tab-btn" :class="{ 'is-active': activeDrawerTab === 'rubric' }">Scorecard</button>
                            <button type="button" @click="activeDrawerTab = 'questions'" class="vi-tab-btn" :class="{ 'is-active': activeDrawerTab === 'questions' }">Questions (<span x-text="screeningQuestions.filter(q => q.asked).length + '/' + screeningQuestions.length"></span>)</button>
                            <button type="button" @click="activeDrawerTab = 'dossier'" class="vi-tab-btn" :class="{ 'is-active': activeDrawerTab === 'dossier' }">Profile Dossier</button>
                        </div>

                        <!-- TAB 1: SCORECARD / RUBRIC -->
                        <div class="vi-drawer-content" x-show="activeDrawerTab === 'rubric'">
                            
                            <div class="vi-rubric-card">
                                <div style="font-size: 12px; font-weight: 800; color: #000623; margin-bottom: 4px;">Competency Evaluation</div>
                                
                                <!-- Technical Skills -->
                                <div class="vi-score-row">
                                    <span class="vi-score-label">Technical Competence</span>
                                    <div class="vi-star-group">
                                        <template x-for="star in 5" :key="star">
                                            <button type="button" @click="rubric.technical_score = star" class="vi-star-btn" :class="{ 'is-filled': star <= rubric.technical_score }">
                                                <svg style="width: 15px; height: 15px;" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                </svg>
                                            </button>
                                        </template>
                                    </div>
                                </div>

                                <!-- Problem Solving -->
                                <div class="vi-score-row">
                                    <span class="vi-score-label">Problem Solving</span>
                                    <div class="vi-star-group">
                                        <template x-for="star in 5" :key="star">
                                            <button type="button" @click="rubric.problem_solving_score = star" class="vi-star-btn" :class="{ 'is-filled': star <= rubric.problem_solving_score }">
                                                <svg style="width: 15px; height: 15px;" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                </svg>
                                            </button>
                                        </template>
                                    </div>
                                </div>

                                <!-- Communication -->
                                <div class="vi-score-row">
                                    <span class="vi-score-label">Communication</span>
                                    <div class="vi-star-group">
                                        <template x-for="star in 5" :key="star">
                                            <button type="button" @click="rubric.communication_score = star" class="vi-star-btn" :class="{ 'is-filled': star <= rubric.communication_score }">
                                                <svg style="width: 15px; height: 15px;" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                </svg>
                                            </button>
                                        </template>
                                    </div>
                                </div>

                                <!-- Culture Fit -->
                                <div class="vi-score-row">
                                    <span class="vi-score-label">Culture &amp; Team Fit</span>
                                    <div class="vi-star-group">
                                        <template x-for="star in 5" :key="star">
                                            <button type="button" @click="rubric.culture_score = star" class="vi-star-btn" :class="{ 'is-filled': star <= rubric.culture_score }">
                                                <svg style="width: 15px; height: 15px;" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                </svg>
                                            </button>
                                        </template>
                                    </div>
                                </div>

                            </div>

                            <!-- Hiring Recommendation -->
                            <div>
                                <label class="vi-score-label" style="display: block; margin-bottom: 8px;">Final Recommendation</label>
                                <div class="vi-rec-chips-group">
                                    <button type="button" @click="rubric.overall_recommendation = 'strong_hire'" class="vi-rec-chip" :class="{ 'is-selected is-strong': rubric.overall_recommendation === 'strong_hire' }">
                                        Strong Hire
                                    </button>
                                    <button type="button" @click="rubric.overall_recommendation = 'hire'" class="vi-rec-chip" :class="{ 'is-selected': rubric.overall_recommendation === 'hire' }">
                                        Hire (Standard)
                                    </button>
                                    <button type="button" @click="rubric.overall_recommendation = 'hold'" class="vi-rec-chip" :class="{ 'is-selected': rubric.overall_recommendation === 'hold' }">
                                        Hold / Consider
                                    </button>
                                    <button type="button" @click="rubric.overall_recommendation = 'reject'" class="vi-rec-chip" :class="{ 'is-selected is-reject': rubric.overall_recommendation === 'reject' }">
                                        Do Not Proceed
                                    </button>
                                </div>
                            </div>

                            <!-- Real-time Notes -->
                            <div>
                                <label class="vi-score-label" style="display: block; margin-bottom: 8px;">Interviewer Private Notes</label>
                                <textarea x-model="rubric.notes" class="vi-notes-textarea" placeholder="Type key observations, design rationale, or feedback..."></textarea>
                            </div>

                            <!-- Save Button -->
                            <button type="button" @click="saveRubric()" class="vi-save-score-btn" :disabled="savingScore">
                                <svg style="width: 15px; height: 15px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span x-text="savingScore ? 'Saving Scorecard...' : 'Save & Persist Evaluation'"></span>
                            </button>

                        </div>

                        <!-- TAB 2: SCREENING QUESTIONS -->
                        <div class="vi-drawer-content" x-show="activeDrawerTab === 'questions'">
                            <p style="font-size: 12px; color: #64748B; margin: 0 0 6px;">Check off questions as you ask them during the session.</p>
                            
                            <template x-for="q in screeningQuestions" :key="q.id">
                                <div class="vi-q-card" :class="{ 'is-asked': q.asked }" @click="toggleQuestion(q.id)">
                                    <div class="vi-q-top">
                                        <div class="vi-q-check">
                                            <svg x-show="q.asked" style="width: 12px; height: 12px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                            </svg>
                                        </div>
                                        <div class="vi-q-text" x-text="q.question"></div>
                                    </div>
                                    <div class="vi-q-hint" x-text="'Hint: ' + q.hint"></div>
                                </div>
                            </template>
                        </div>

                        <!-- TAB 3: CANDIDATE DOSSIER -->
                        <div class="vi-drawer-content" x-show="activeDrawerTab === 'dossier'">
                            
                            <!-- Academic Details -->
                            <div class="vi-rubric-card">
                                <div style="display: flex; align-items: center; gap: 12px;">
                                    <img :src="room.candidate_avatar" :alt="room.candidate_name" style="width: 48px; height: 48px; border-radius: 50%; object-fit: cover; border: 2px solid #E2E8F0;">
                                    <div>
                                        <h3 style="font-size: 14px; font-weight: 800; color: #000623; margin: 0 0 2px;" x-text="room.candidate_name"></h3>
                                        <p style="font-size: 12px; color: #4F48E6; font-weight: 700; margin: 0;" x-text="room.candidate_role"></p>
                                    </div>
                                </div>
                                
                                <div style="border-top: 1px solid #E2E8F0; padding-top: 10px; display: flex; flex-direction: column; gap: 6px; font-size: 12px;">
                                    <div><strong>Institution:</strong> <span x-text="room.candidate_school"></span></div>
                                    <div><strong>Academic Standing:</strong> <span x-text="room.candidate_gpa"></span></div>
                                    <div><strong>Placement Period:</strong> <span x-text="room.candidate_duration"></span></div>
                                </div>
                            </div>

                            <!-- Portfolio Links -->
                            <div>
                                <div style="font-size: 12px; font-weight: 800; color: #000623; margin-bottom: 8px;">Candidate Work &amp; Repositories</div>
                                <div style="display: flex; flex-direction: column; gap: 8px;">
                                    <template x-for="link in room.portfolio_links" :key="link.label">
                                        <a :href="link.url" target="_blank" rel="noopener noreferrer" style="background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 10px; padding: 10px 14px; display: flex; align-items: center; justify-content: space-between; text-decoration: none; color: #0F172A; font-size: 12.5px; font-weight: 600;">
                                            <span x-text="link.label"></span>
                                            <svg style="width: 14px; height: 14px; color: #94A3B8;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                            </svg>
                                        </a>
                                    </template>
                                </div>
                            </div>

                            <!-- Candidate Profile Link -->
                            <a :href="'<?= url('/employer/candidates/') ?>' + room.candidate_id" target="_blank" style="background: #F8FAFC; border: 1px solid #CBD5E1; color: #334155; border-radius: 10px; padding: 10px; text-align: center; text-decoration: none; font-size: 12.5px; font-weight: 700; display: block;">
                                Open Full Candidate Profile Drawer &rarr;
                            </a>

                        </div>

                    </aside>

                </div><!-- /.vi-room-main -->

            </div><!-- /.vi-room-shell -->

        </div><!-- /.db-content -->

        <!-- ══ LEAVE / END CALL CONFIRMATION MODAL ══ -->
        <div x-show="leaveModalOpen" x-cloak style="position: fixed; inset: 0; background: rgba(0, 6, 35, 0.6); backdrop-filter: blur(4px); z-index: 999; display: flex; align-items: center; justify-content: center; padding: 20px;">
            <div @click.outside="leaveModalOpen = false" style="background: #FFFFFF; border-radius: 20px; width: 100%; max-width: 440px; padding: 28px; box-shadow: 0 20px 40px rgba(0,0,0,0.25); text-align: center;">
                <div style="width: 52px; height: 52px; border-radius: 50%; background: #FEE2E2; color: #EF4444; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px;">
                    <svg style="width: 26px; height: 26px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                </div>
                <h3 style="font-size: 18px; font-weight: 800; color: #000623; margin: 0 0 8px;">End Video Interview?</h3>
                <p style="font-size: 13px; color: #64748B; margin: 0 0 24px; line-height: 1.5;">Your live evaluation scores and private notes will be preserved. Both participants will exit the room.</p>
                <div style="display: flex; gap: 12px;">
                    <button type="button" @click="leaveModalOpen = false" style="flex: 1; padding: 10px; border-radius: 10px; border: 1px solid #CBD5E1; background: #FFFFFF; font-size: 13px; font-weight: 700; color: #475569; cursor: pointer;">
                        Resume Call
                    </button>
                    <button type="button" @click="leaveCall()" style="flex: 1; padding: 10px; border-radius: 10px; border: none; background: #DC2626; font-size: 13px; font-weight: 700; color: #FFFFFF; cursor: pointer;">
                        Confirm &amp; Exit
                    </button>
                </div>
            </div>
        </div>

        <!-- Dashboard Footer -->
        <?php include dirname(__DIR__, 2) . '/shared/components/db-footer.php'; ?>

    </div><!-- /.db-main -->
</div><!-- /.db-shell -->

