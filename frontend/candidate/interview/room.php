<?php
declare(strict_types=1);

/**
 * View: In-Platform Video Interview Room (Candidate Portal)
 * Powered by Daily.co Integration — Enables candidates and employers to interview without leaving InternConnect.
 * Route: /candidate/interview/room/{id}
 * Layout: dashboard
 * 
 * @var array $room Meeting room metadata, company details, agenda, and Jenny AI tips
 */
?>
<style>
/* ==========================================================================
   CANDIDATE IN-PLATFORM VIDEO INTERVIEW ROOM — DESIGN SYSTEM SPECIFICATION
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

/* ── MAIN WORKSPACE ── */
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

/* ── CANDIDATE ASSISTANT DRAWER ── */
.vi-drawer {
    width: 380px;
    background: #FFFFFF;
    border-left: 1px solid #E2E8F0;
    display: flex;
    flex-direction: column;
    height: 100%;
    flex-shrink: 0;
    z-index: 25;
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
    gap: 16px;
    box-sizing: border-box;
}

/* ── JENNY AI TIPS CARDS ── */
.vi-jenny-card {
    background: #F8FAFC;
    border: 1px solid #E2E8F0;
    border-radius: 12px;
    padding: 14px;
    display: flex;
    gap: 12px;
    align-items: flex-start;
}

.vi-jenny-icon-wrap {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: #EEF2FF;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

/* ── PRE-CALL LOBBY ── */
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
    width: 60%;
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
    activeDrawerTab: 'agenda',
    callSeconds: 0,
    timerInterval: null,
    inCallChat: '',
    chatMessages: [
        { sender: 'interviewer', text: 'Welcome Sarah! Please take a moment to settle in. We will begin with the portfolio walkthrough.', time: '10:01 AM' }
    ],
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

    sendInCallMessage() {
        const text = this.inCallChat.trim();
        if (!text) return;
        this.chatMessages.push({
            sender: 'candidate',
            text: text,
            time: 'Just now'
        });
        this.inCallChat = '';
        this.$nextTick(() => {
            const el = document.getElementById('incall-chat-stream');
            if (el) el.scrollTop = el.scrollHeight;
        });
    },

    leaveCall() {
        if (this.timerInterval) clearInterval(this.timerInterval);
        this.inCall = false;
        window.location.href = '<?= url('/candidate/interview/invite') ?>';
    }
}" x-init="
    setTimeout(() => { if (!inCall) joinCall(); }, 2500);
">

    <!-- Sidebar Overlay (mobile) -->
    <div class="db-sidebar-overlay" id="db-sidebar-overlay"></div>

    <!-- ══ SIDEBAR ══ -->
    <?php 
    $activeNav = 'applications';
    include dirname(__DIR__, 2) . '/shared/components/candidate-sidebar.php'; 
    ?>

    <!-- ══ MAIN ══ -->
    <div class="db-main">

        <!-- ══ TOP NAVBAR ══ -->
        <?php include dirname(__DIR__, 2) . '/shared/components/candidate-topnav.php'; ?>

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
                                <span style="color: #FFFFFF; font-weight: 700;" x-text="room.company_name"></span>
                                <span>&bull;</span>
                                <span x-text="'Interviewer: ' + room.interviewer_name"></span>
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
                            <span>Encrypted &bull; 60 FPS</span>
                        </div>
                    </div>

                    <div style="display: flex; align-items: center; gap: 10px;">
                        <button type="button" @click="drawerOpen = !drawerOpen" class="vi-tab-btn" :class="{ 'is-active': drawerOpen }" style="background: rgba(255,255,255,0.1); color: #F8FAFC; border: 1px solid rgba(255,255,255,0.15);">
                            <span x-text="drawerOpen ? 'Hide Assistant' : 'Show Assistant'"></span>
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
                                <img :src="room.candidate_avatar" alt="Camera Preview" style="width: 100%; height: 100%; object-fit: cover; opacity: 0.9;">
                            </template>
                            <template x-if="cameraMuted">
                                <div class="vi-avatar-fallback">
                                    <span style="color: #94A3B8; font-size: 13px;">Camera Off</span>
                                </div>
                            </template>
                            <div class="vi-tile-tag">
                                <span>Preview: Sarah Peters (You)</span>
                            </div>
                        </div>

                        <div>
                            <h2 style="font-size: 18px; font-weight: 800; color: #FFFFFF; margin: 0 0 6px;">Ready to join Nexura Technologies?</h2>
                            <p style="font-size: 13px; color: #94A3B8; margin: 0;">Tunde Bakare is in the room. Make sure your camera and mic are working comfortably.</p>
                        </div>

                        <!-- Audio Visualizer Test -->
                        <div style="display: flex; align-items: center; gap: 10px; background: rgba(15, 23, 42, 0.7); border: 1px solid #334155; padding: 8px 16px; border-radius: 99px;">
                            <svg style="width: 16px; height: 16px; color: #10B981;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"/>
                            </svg>
                            <div class="vi-mic-meter-bar">
                                <div class="vi-mic-meter-fill"></div>
                            </div>
                            <span style="font-size: 11.5px; font-weight: 600; color: #34D399;">Microphone Active</span>
                        </div>

                        <!-- Join Button -->
                        <button type="button" @click="joinCall()" class="vi-lobby-join-btn">
                            <svg style="width: 18px; height: 18px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                            </svg>
                            <span>Join In-Platform Video Call</span>
                        </button>
                    </div>
                </div>

                <!-- ══ 3. LIVE INTERVIEW WORKSPACE ══ -->
                <div class="vi-room-main" x-show="inCall">
                    
                    <!-- ── VIDEO STAGE ── -->
                    <div class="vi-stage">
                        
                        <div class="vi-video-grid">
                            
                            <!-- Interviewer Tile (Active Speaker) -->
                            <div class="vi-participant-tile is-active-speaker">
                                <img :src="room.interviewer_avatar" :alt="room.interviewer_name" class="vi-participant-video" style="filter: brightness(0.95);">
                                <div class="vi-tile-tag">
                                    <span style="width: 8px; height: 8px; border-radius: 50%; background: #10B981;"></span>
                                    <span x-text="room.interviewer_name + ' (Interviewer)'"></span>
                                </div>
                            </div>

                            <!-- Self Tile (Candidate) -->
                            <div class="vi-participant-tile">
                                <template x-if="!cameraMuted">
                                    <img :src="room.candidate_avatar" :alt="room.candidate_name" class="vi-participant-video">
                                </template>
                                <template x-if="cameraMuted">
                                    <div class="vi-avatar-fallback">
                                        <img :src="room.candidate_avatar" :alt="room.candidate_name">
                                        <span class="vi-avatar-name" x-text="room.candidate_name"></span>
                                        <span style="font-size: 11px; color: #94A3B8;">Camera Off</span>
                                    </div>
                                </template>
                                <div class="vi-tile-tag">
                                    <span x-text="room.candidate_name + ' (You)'"></span>
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

                            <!-- Share Screen (e.g. for Figma Walkthrough) -->
                            <button type="button" @click="toggleScreenShare()" class="vi-control-btn" :class="{ 'is-active-tab': screenSharing }" title="Share Screen / Portfolio">
                                <svg style="width: 18px; height: 18px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </button>

                            <!-- End Call -->
                            <button type="button" @click="leaveModalOpen = true" class="vi-control-btn is-end-call" title="Leave Call">
                                <svg style="width: 20px; height: 20px;" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 9c-1.6 0-3.15.25-4.6.72v3.1c0 .39-.23.74-.56.9-.98.49-1.87 1.12-2.66 1.85-.18.18-.43.28-.7.28-.28 0-.53-.11-.71-.29L.29 13.08a.996.996 0 0 1 0-1.41C3.34 8.78 7.46 7 12 7s8.66 1.78 11.71 4.67c.39.39.39 1.02 0 1.41l-2.48 2.48c-.18.18-.43.29-.71.29-.27 0-.52-.11-.7-.28-.79-.74-1.69-1.36-2.67-1.85-.33-.16-.56-.5-.56-.9v-3.1C15.15 9.25 13.6 9 12 9z"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- ── CANDIDATE ASSISTANT DRAWER (RIGHT) ── -->
                    <aside class="vi-drawer" x-show="drawerOpen">
                        
                        <div class="vi-drawer-header">
                            <h2 class="vi-drawer-title">
                                <svg style="width: 16px; height: 16px; color: #4F48E6;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span>Interview Companion</span>
                            </h2>
                            <span style="font-size: 11px; background: #EEF2FF; color: #4F48E6; padding: 2px 8px; border-radius: 99px; font-weight: 700;">Live Guide</span>
                        </div>

                        <!-- Drawer Tab Switcher -->
                        <div class="vi-drawer-tabs">
                            <button type="button" @click="activeDrawerTab = 'agenda'" class="vi-tab-btn" :class="{ 'is-active': activeDrawerTab === 'agenda' }">Agenda</button>
                            <button type="button" @click="activeDrawerTab = 'tips'" class="vi-tab-btn" :class="{ 'is-active': activeDrawerTab === 'tips' }">Jenny AI Tips</button>
                            <button type="button" @click="activeDrawerTab = 'chat'" class="vi-tab-btn" :class="{ 'is-active': activeDrawerTab === 'chat' }">In-Call Chat</button>
                        </div>

                        <!-- TAB 1: AGENDA & COMPANY OVERVIEW -->
                        <div class="vi-drawer-content" x-show="activeDrawerTab === 'agenda'">
                            <div style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 12px; padding: 14px;">
                                <div style="font-size: 11px; font-weight: 700; color: #64748B; text-transform: uppercase; margin-bottom: 4px;">Host Organization</div>
                                <div style="font-size: 14px; font-weight: 800; color: #000623; margin-bottom: 2px;" x-text="room.company_name"></div>
                                <div style="font-size: 12px; color: #4F48E6; font-weight: 600;" x-text="room.candidate_role"></div>
                            </div>

                            <div>
                                <div style="font-size: 12.5px; font-weight: 800; color: #000623; margin-bottom: 8px;">Session Structure (45 mins)</div>
                                <div style="display: flex; flex-direction: column; gap: 8px; font-size: 12px;">
                                    <div style="display: flex; gap: 10px; align-items: flex-start;">
                                        <span style="background: #EEF2FF; color: #4F48E6; font-weight: 800; padding: 2px 6px; border-radius: 6px; font-size: 10px;">0–10m</span>
                                        <span>Introductions &amp; background review with Tunde Bakare.</span>
                                    </div>
                                    <div style="display: flex; gap: 10px; align-items: flex-start;">
                                        <span style="background: #EEF2FF; color: #4F48E6; font-weight: 800; padding: 2px 6px; border-radius: 6px; font-size: 10px;">10–30m</span>
                                        <span>Oakland banking app case study walkthrough (Share your screen).</span>
                                    </div>
                                    <div style="display: flex; gap: 10px; align-items: flex-start;">
                                        <span style="background: #EEF2FF; color: #4F48E6; font-weight: 800; padding: 2px 6px; border-radius: 6px; font-size: 10px;">30–40m</span>
                                        <span>Technical questions: Figma tokens, auto-layout, design systems.</span>
                                    </div>
                                    <div style="display: flex; gap: 10px; align-items: flex-start;">
                                        <span style="background: #EEF2FF; color: #4F48E6; font-weight: 800; padding: 2px 6px; border-radius: 6px; font-size: 10px;">40–45m</span>
                                        <span>Your questions for the Nexura design team.</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- TAB 2: JENNY AI LIVE COACHING TIPS -->
                        <div class="vi-drawer-content" x-show="activeDrawerTab === 'tips'">
                            <p style="font-size: 12px; color: #64748B; margin: 0;">Jenny AI recommendations to help you ace this session:</p>
                            
                            <template x-for="tip in room.jenny_ai_tips" :key="tip.title">
                                <div class="vi-jenny-card">
                                    <div class="vi-jenny-icon-wrap">
                                        <svg style="width: 16px; height: 16px; color: #4F48E6;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 style="font-size: 12.5px; font-weight: 800; color: #000623; margin: 0 0 3px;" x-text="tip.title"></h4>
                                        <p style="font-size: 11.5px; color: #64748B; margin: 0; line-height: 1.45;" x-text="tip.desc"></p>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <!-- TAB 3: IN-CALL DIRECT CHAT -->
                        <div class="vi-drawer-content" x-show="activeDrawerTab === 'chat'" style="padding: 14px;">
                            <div style="flex: 1; overflow-y: auto; display: flex; flex-direction: column; gap: 10px;" id="incall-chat-stream">
                                <template x-for="(m, idx) in chatMessages" :key="idx">
                                    <div :style="m.sender === 'candidate' ? 'align-self: flex-end; max-width: 80%;' : 'align-self: flex-start; max-width: 80%;'">
                                        <div :style="m.sender === 'candidate' ? 'background: #000623; color: #FFFFFF; border-radius: 12px; padding: 8px 12px; font-size: 12px;' : 'background: #F1F5F9; color: #0F172A; border-radius: 12px; padding: 8px 12px; font-size: 12px;'" x-text="m.text"></div>
                                        <div style="font-size: 10px; color: #94A3B8; margin-top: 2px;" :style="m.sender === 'candidate' ? 'text-align: right;' : 'text-align: left;'" x-text="m.time"></div>
                                    </div>
                                </template>
                            </div>

                            <div style="display: flex; gap: 8px; margin-top: 10px; border-top: 1px solid #E2E8F0; padding-top: 10px;">
                                <input type="text" x-model="inCallChat" @keydown.enter.prevent="sendInCallMessage()" placeholder="Type a message or share link..." style="flex: 1; border: 1px solid #E2E8F0; border-radius: 8px; padding: 8px 10px; font-size: 12px; outline: none;">
                                <button type="button" @click="sendInCallMessage()" style="background: #4F48E6; color: #FFFFFF; border: none; border-radius: 8px; padding: 8px 12px; font-size: 12px; font-weight: 700; cursor: pointer;">
                                    Send
                                </button>
                            </div>
                        </div>

                    </aside>

                </div><!-- /.vi-room-main -->

            </div><!-- /.vi-room-shell -->

        </div><!-- /.db-content -->

        <!-- ══ LEAVE CONFIRMATION MODAL ══ -->
        <div x-show="leaveModalOpen" x-cloak style="position: fixed; inset: 0; background: rgba(0, 6, 35, 0.6); backdrop-filter: blur(4px); z-index: 999; display: flex; align-items: center; justify-content: center; padding: 20px;">
            <div @click.outside="leaveModalOpen = false" style="background: #FFFFFF; border-radius: 20px; width: 100%; max-width: 440px; padding: 28px; box-shadow: 0 20px 40px rgba(0,0,0,0.25); text-align: center;">
                <div style="width: 52px; height: 52px; border-radius: 50%; background: #FEE2E2; color: #EF4444; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px;">
                    <svg style="width: 26px; height: 26px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                </div>
                <h3 style="font-size: 18px; font-weight: 800; color: #000623; margin: 0 0 8px;">Leave Video Interview?</h3>
                <p style="font-size: 13px; color: #64748B; margin: 0 0 24px; line-height: 1.5;">You will exit the video room. You can rejoin at any time before the session concludes.</p>
                <div style="display: flex; gap: 12px;">
                    <button type="button" @click="leaveModalOpen = false" style="flex: 1; padding: 10px; border-radius: 10px; border: 1px solid #CBD5E1; background: #FFFFFF; font-size: 13px; font-weight: 700; color: #475569; cursor: pointer;">
                        Stay in Call
                    </button>
                    <button type="button" @click="leaveCall()" style="flex: 1; padding: 10px; border-radius: 10px; border: none; background: #DC2626; font-size: 13px; font-weight: 700; color: #FFFFFF; cursor: pointer;">
                        Exit Room
                    </button>
                </div>
            </div>
        </div>

        <!-- Dashboard Footer -->
        <?php include dirname(__DIR__, 2) . '/shared/components/db-footer.php'; ?>

    </div><!-- /.db-main -->
</div><!-- /.db-shell -->

