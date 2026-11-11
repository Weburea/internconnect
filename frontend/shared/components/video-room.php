<?php
declare(strict_types=1);
/**
 * Shared In-Platform Video Room (Daily.co call object, custom UI)
 * Used by: frontend/employer/interviews/room.php, frontend/candidate/interview/room.php
 * Spec: docs/47-video-interviews.md
 *
 * Flow: lobby (real camera preview, mic meter, device pickers) -> join (server issues a
 * Daily room URL + participant token) -> call (tiles, mic/camera/screen share, chat over
 * Daily app-messages, network quality) -> ended screen (rejoin / back).
 *
 * @var array  $room          Room details from VideoInterviewService::getRoomDetails()
 * @var array  $vrConfig      role, joinUrl, leftUrl, endUrl?, exitUrl, exitLabel, selfName, peerName,
 *                            peerLabel, subtitle, drawerTitle, drawerBadge, tabs [[key,label]], defaultTab
 * @var string $vrDrawerView  Absolute path of the role-specific drawer partial
 */

$vrSettings = \App\Services\VideoInterviewService::settings();
$vrPayload = [
    'role'        => $vrConfig['role'],
    'interviewId' => (int)$room['id'],
    'joinUrl'     => $vrConfig['joinUrl'],
    'leftUrl'     => $vrConfig['leftUrl'],
    'endUrl'      => $vrConfig['endUrl'] ?? '',
    'exitUrl'     => $vrConfig['exitUrl'],
    'selfName'    => $vrConfig['selfName'],
    'peerName'    => $vrConfig['peerName'],
    'tabs'        => $vrConfig['tabs'],
    'defaultTab'  => $vrConfig['defaultTab'],
    'durationMin' => (int)($room['duration_minutes'] ?? $vrSettings['default_duration_min']),
    'graceMin'    => (int)$vrSettings['grace_min'],
    'earlyMin'    => (int)$vrSettings['join_early_min'],
    'opensAt'     => !empty($room['timing']['opens_at']) ? (int)$room['timing']['opens_at'] * 1000 : 0,
    'serverNow'   => time() * 1000,
    'allowFiles'  => (bool)$vrSettings['allow_files'],
    'notFound'    => empty($room['exists']),
    'filesUrl'    => $vrConfig['filesUrl'],
    'chatUrl'     => $vrConfig['chatUrl'],
    'eventUrl'    => $vrConfig['eventUrl'],
    'reportUrl'   => $vrConfig['reportUrl'] ?? '',
    'allowChat'   => (bool)$vrSettings['allow_chat'],
    'allowScreen' => (bool)$vrSettings['allow_screenshare'],
    'videoOn'     => (bool)$vrSettings['video_enabled'],
    // Closed rooms block candidates; the employer can restart a room they ended (not one an admin closed).
    'roomEnded'   => ($room['room_status'] ?? 'open') === 'ended' && ($vrConfig['role'] !== 'employer' || ($room['room_ended_by'] ?? '') === 'admin'),
    'devHint'     => (($_ENV['APP_ENV'] ?? '') === 'local') && !\App\Services\VideoInterviewService::isConfigured(),
];
?>
<style>
/* ==========================================================================
   VIDEO ROOM (shared) — docs/47-video-interviews.md
   ========================================================================== */
.vr { --vr-bg: #0B1020; --vr-panel: #111832; --vr-line: rgba(255,255,255,0.08); --vr-text: #E2E8F0; --vr-muted: #94A3B8;
      background: var(--vr-bg); border-radius: 20px; overflow: hidden; display: flex; flex-direction: column; min-height: calc(100vh - 150px); box-shadow: 0 20px 50px -20px rgba(0, 6, 35, 0.45); color: var(--vr-text); position: relative; }
.vr *, .vr *::before, .vr *::after { box-sizing: border-box; }

/* Header */
.vr-head { display: flex; align-items: center; gap: 12px 16px; padding: 14px 18px; border-bottom: 1px solid var(--vr-line); flex-wrap: wrap; }
.vr-head-main { display: flex; align-items: center; gap: 12px; min-width: 0; flex: 1 1 280px; }
.vr-head-icon { width: 40px; height: 40px; border-radius: 12px; background: rgba(99, 102, 241, 0.16); color: #A5B4FC; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.vr-head-icon svg { width: 20px; height: 20px; }
.vr-head-text { min-width: 0; }
.vr-title { font-size: 15.5px; font-weight: 800; color: #FFFFFF; margin: 0; line-height: 1.3; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.vr-sub { font-size: 12.5px; color: var(--vr-muted); margin: 2px 0 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.vr-sub strong { color: #FFFFFF; font-weight: 700; }
.vr-head-stats { display: flex; align-items: center; gap: 8px; }
.vr-pill { display: inline-flex; align-items: center; gap: 6px; height: 30px; padding: 0 12px; border-radius: 9999px; background: rgba(255,255,255,0.06); border: 1px solid var(--vr-line); font-size: 12px; font-weight: 700; color: #F8FAFC; white-space: nowrap; font-variant-numeric: tabular-nums; }
.vr-pill .vr-dot { width: 7px; height: 7px; border-radius: 50%; flex-shrink: 0; }
.vr-pill small { color: #64748B; font-weight: 600; font-size: 12px; }
.vr-head-actions { display: flex; align-items: center; gap: 8px; }
.vr-btn { display: inline-flex; align-items: center; justify-content: center; gap: 7px; height: 36px; padding: 0 14px; border-radius: 10px; border: 1px solid var(--vr-line); background: rgba(255,255,255,0.06); color: #F8FAFC; font-size: 12.5px; font-weight: 700; cursor: pointer; white-space: nowrap; flex-shrink: 0; text-decoration: none; transition: background .15s ease, border-color .15s ease; }
.vr-btn:hover { background: rgba(255,255,255,0.12); }
.vr-btn svg { width: 15px; height: 15px; flex-shrink: 0; }
.vr-btn.is-danger { background: rgba(239, 68, 68, 0.14); border-color: rgba(239, 68, 68, 0.35); color: #FCA5A5; }
.vr-btn.is-danger:hover { background: rgba(239, 68, 68, 0.24); }
.vr-btn.is-primary { background: #4F48E6; border-color: #4F48E6; color: #FFFFFF; }
.vr-btn.is-primary:hover { background: #4338CA; }
.vr-btn.is-light { background: #FFFFFF; border-color: #FFFFFF; color: #0F172A; }

/* Lobby */
.vr-lobby { flex: 1; display: grid; grid-template-columns: minmax(0, 1.25fr) minmax(0, 1fr); gap: 28px; padding: 28px; align-items: center; max-width: 1080px; width: 100%; margin: 0 auto; }
.vr-preview { position: relative; aspect-ratio: 16 / 10; border-radius: 18px; overflow: hidden; background: #050814; border: 1px solid var(--vr-line); }
.vr-preview video { width: 100%; height: 100%; object-fit: cover; transform: scaleX(-1); display: block; }
.vr-preview-off { position: absolute; inset: 0; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 10px; color: var(--vr-muted); font-size: 13px; text-align: center; padding: 20px; }
.vr-avatar { width: 72px; height: 72px; border-radius: 50%; background: linear-gradient(135deg, #4F48E6, #7C3AED); color: #FFFFFF; display: flex; align-items: center; justify-content: center; font-size: 24px; font-weight: 800; flex-shrink: 0; }
.vr-preview-bar { position: absolute; left: 12px; right: 12px; bottom: 12px; display: flex; align-items: center; justify-content: center; gap: 10px; }
.vr-round { width: 44px; height: 44px; border-radius: 50%; border: 1px solid rgba(255,255,255,0.14); background: rgba(15, 23, 42, 0.72); backdrop-filter: blur(8px); color: #FFFFFF; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: background .15s ease, transform .15s ease; flex-shrink: 0; position: relative; }
.vr-round:hover { background: rgba(51, 65, 85, 0.9); }
.vr-round svg { width: 19px; height: 19px; }
.vr-round.is-off { background: #DC2626; border-color: #DC2626; }
.vr-round.is-on { background: #4F48E6; border-color: #4F48E6; }
.vr-round.is-end { background: #DC2626; border-color: #DC2626; width: 52px; }
.vr-round.is-end:hover { background: #B91C1C; }
.vr-round:disabled { opacity: .4; cursor: not-allowed; }
.vr-badge { position: absolute; top: -3px; right: -3px; min-width: 18px; height: 18px; padding: 0 5px; border-radius: 9999px; background: #4F48E6; color: #FFFFFF; font-size: 10.5px; font-weight: 800; display: flex; align-items: center; justify-content: center; border: 2px solid var(--vr-bg); }
.vr-lobby-panel { display: flex; flex-direction: column; gap: 16px; min-width: 0; }
.vr-lobby-kicker { font-size: 11px; font-weight: 700; letter-spacing: .08em; text-transform: uppercase; color: #A5B4FC; }
.vr-lobby-title { font-size: 22px; font-weight: 800; color: #FFFFFF; margin: 0; line-height: 1.3; letter-spacing: -0.01em; }
.vr-lobby-text { font-size: 13.5px; color: var(--vr-muted); margin: 0; line-height: 1.55; }
.vr-field { display: flex; flex-direction: column; gap: 6px; }
.vr-field label { font-size: 11.5px; font-weight: 700; color: var(--vr-muted); }
.vr-select-wrap { position: relative; display: flex; align-items: center; }
.vr-select-wrap > svg { position: absolute; pointer-events: none; color: #A5B4FC; }
.vr-select-wrap > .vr-select-icon { left: 14px; width: 16px; height: 16px; }
.vr-select-wrap > .vr-select-caret { right: 14px; width: 14px; height: 14px; transition: transform .15s ease; }
.vr-select { -webkit-appearance: none; -moz-appearance: none; appearance: none; width: 100%; height: 44px; padding: 0 42px 0 40px; border-radius: 12px; border: 1px solid rgba(255,255,255,0.12); background: rgba(255,255,255,0.04); color: #F8FAFC; font-size: 13px; font-weight: 600; outline: none; cursor: pointer; text-overflow: ellipsis; white-space: nowrap; overflow: hidden; transition: border-color .15s ease, background .15s ease, box-shadow .15s ease; }
.vr-select::-ms-expand { display: none; }
.vr-select:hover { border-color: rgba(165, 180, 252, 0.45); background: rgba(255,255,255,0.07); }
.vr-select:focus { border-color: #6366F1; box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.25); }
.vr-select option { background: #111832; color: #F8FAFC; }
.vr-meter { display: flex; align-items: center; gap: 10px; font-size: 12px; color: var(--vr-muted); }
.vr-meter-bar { flex: 1; height: 6px; border-radius: 9999px; background: rgba(255,255,255,0.08); overflow: hidden; }
.vr-meter-bar span { display: block; height: 100%; background: #10B981; border-radius: 9999px; transition: width .08s linear; }
.vr-join { height: 48px; border-radius: 12px; border: 0; background: #4F48E6; color: #FFFFFF; font-size: 14.5px; font-weight: 700; display: inline-flex; align-items: center; justify-content: center; gap: 9px; cursor: pointer; box-shadow: 0 10px 24px -8px rgba(79, 72, 230, 0.7); transition: background .15s ease; }
.vr-join:hover:not(:disabled) { background: #4338CA; }
.vr-join:disabled { opacity: .55; cursor: not-allowed; box-shadow: none; }
.vr-join svg { width: 18px; height: 18px; }
.vr-alert { display: flex; gap: 10px; padding: 12px 14px; border-radius: 12px; font-size: 12.5px; line-height: 1.5; background: rgba(245, 158, 11, 0.1); border: 1px solid rgba(245, 158, 11, 0.3); color: #FCD34D; }
.vr-alert svg { width: 16px; height: 16px; flex-shrink: 0; margin-top: 1px; }
.vr-alert code { background: rgba(0,0,0,0.3); padding: 1px 6px; border-radius: 6px; color: #FDE68A; font-size: 12px; }
.vr-spin { animation: vrSpin 1s linear infinite; }
@keyframes vrSpin { to { transform: rotate(360deg); } }

/* Call */
.vr-call { flex: 1; display: flex; min-height: 0; position: relative; }
.vr-stage { flex: 1; min-width: 0; display: flex; flex-direction: column; padding: 16px; gap: 14px; position: relative; }
.vr-grid { flex: 1; min-height: 0; display: grid; gap: 12px; grid-template-columns: repeat(var(--vr-cols, 1), minmax(0, 1fr)); grid-auto-rows: minmax(0, 1fr); }
.vr-tile { position: relative; border-radius: 16px; overflow: hidden; background: #050814; border: 2px solid transparent; min-height: 200px; transition: border-color .2s ease; }
.vr-tile.is-speaking { border-color: #6366F1; }
.vr-tile video { width: 100%; height: 100%; object-fit: cover; display: block; background: #050814; }
.vr-tile.is-local video { transform: scaleX(-1); }
.vr-tile.is-screen video { object-fit: contain; transform: none; }
.vr-tile-off { position: absolute; inset: 0; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 10px; color: var(--vr-muted); font-size: 12.5px; }
.vr-tag { position: absolute; left: 10px; bottom: 10px; display: inline-flex; align-items: center; gap: 6px; max-width: calc(100% - 20px); padding: 5px 10px; border-radius: 8px; background: rgba(2, 6, 23, 0.72); backdrop-filter: blur(6px); font-size: 12px; font-weight: 700; color: #FFFFFF; }
.vr-tag span { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
.vr-tag svg { width: 13px; height: 13px; flex-shrink: 0; color: #F87171; }
.vr-waiting { position: relative; border-radius: 16px; border: 1px dashed rgba(255,255,255,0.14); display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 12px; text-align: center; padding: 24px; color: var(--vr-muted); font-size: 13px; min-height: 200px; }
.vr-waiting strong { color: #FFFFFF; font-size: 15px; }
.vr-pulse { width: 64px; height: 64px; border-radius: 50%; background: rgba(99, 102, 241, 0.15); display: flex; align-items: center; justify-content: center; color: #A5B4FC; animation: vrPulse 2s ease-in-out infinite; }
.vr-pulse svg { width: 26px; height: 26px; }
@keyframes vrPulse { 0%, 100% { box-shadow: 0 0 0 0 rgba(99,102,241,0.35); } 50% { box-shadow: 0 0 0 14px rgba(99,102,241,0); } }
.vr-filmstrip { display: flex; gap: 10px; height: 120px; flex-shrink: 0; overflow-x: auto; }
.vr-filmstrip .vr-tile { width: 180px; min-height: 0; flex-shrink: 0; }
.vr-controls { display: flex; align-items: center; justify-content: center; gap: 10px; flex-shrink: 0; padding: 4px 0 2px; }

/* Drawer */
.vr-drawer { width: 380px; flex-shrink: 0; background: #FFFFFF; color: #0F172A; display: flex; flex-direction: column; min-height: 0; border-left: 1px solid #E2E8F0; }
.vr-drawer-head { display: flex; align-items: center; justify-content: space-between; gap: 10px; padding: 14px 16px 10px; }
.vr-drawer-title { font-size: 14.5px; font-weight: 800; color: #000623; margin: 0; display: flex; align-items: center; gap: 8px; }
.vr-drawer-title svg { width: 16px; height: 16px; color: #4F48E6; }
.vr-drawer-close { display: none; width: 32px; height: 32px; border: 0; border-radius: 8px; background: #F1F5F9; color: #475569; align-items: center; justify-content: center; cursor: pointer; }
.vr-drawer-close svg { width: 15px; height: 15px; }
.vr-tabs { display: flex; gap: 4px; margin: 0 16px; padding: 4px; background: #F1F5F9; border-radius: 10px; overflow-x: auto; scrollbar-width: none; flex-shrink: 0; }
.vr-tabs::-webkit-scrollbar { display: none; }
.vr-tab { flex: 1 0 auto; height: 32px; padding: 0 10px; border: 0; border-radius: 8px; background: transparent; font-size: 12px; font-weight: 700; color: #64748B; cursor: pointer; white-space: nowrap; display: inline-flex; align-items: center; justify-content: center; gap: 6px; }
.vr-tab.is-active { background: #FFFFFF; color: #4F48E6; box-shadow: 0 1px 3px rgba(15,23,42,0.08); }
.vr-tab .vr-tab-count { min-width: 16px; height: 16px; padding: 0 4px; border-radius: 9999px; background: #4F48E6; color: #FFFFFF; font-size: 10px; display: inline-flex; align-items: center; justify-content: center; }
.vr-panel { flex: 1; min-height: 0; overflow-y: auto; padding: 16px; display: flex; flex-direction: column; gap: 14px; }

/* Chat */
.vr-chat { flex: 1; min-height: 0; display: flex; flex-direction: column; }
.vr-chat-stream { flex: 1; min-height: 0; overflow-y: auto; padding: 16px; display: flex; flex-direction: column; gap: 10px; }
.vr-chat-empty { margin: auto; text-align: center; color: #94A3B8; font-size: 12.5px; max-width: 220px; line-height: 1.5; }
.vr-msg { max-width: 85%; display: flex; flex-direction: column; gap: 3px; }
.vr-msg.is-mine { align-self: flex-end; align-items: flex-end; }
.vr-msg-bubble { padding: 8px 12px; border-radius: 14px; font-size: 13px; line-height: 1.45; background: #F1F5F9; color: #0F172A; word-break: break-word; white-space: pre-wrap; }
.vr-msg.is-mine .vr-msg-bubble { background: #4F48E6; color: #FFFFFF; }
.vr-msg-meta { font-size: 10.5px; color: #94A3B8; }
.vr-chat-form { display: flex; gap: 8px; padding: 12px 16px; border-top: 1px solid #F1F5F9; }
.vr-chat-form input { flex: 1; min-width: 0; height: 40px; padding: 0 12px; border: 1px solid #E2E8F0; border-radius: 10px; font-size: 13px; outline: none; }
.vr-chat-form input:focus { border-color: #4F48E6; box-shadow: 0 0 0 3px rgba(79,72,230,0.1); }
.vr-chat-form button { height: 40px; padding: 0 14px; border: 0; border-radius: 10px; background: #4F48E6; color: #FFFFFF; font-size: 12.5px; font-weight: 700; cursor: pointer; white-space: nowrap; }
.vr-chat-form button:disabled { opacity: .5; cursor: not-allowed; }
.vr-chat-note { font-size: 11.5px; color: #94A3B8; padding: 0 16px 10px; }

/* Timing */
.vr-pill.is-warn { border-color: rgba(245, 158, 11, 0.45); }
.vr-pill.is-final, .vr-pill.is-grace { border-color: rgba(239, 68, 68, 0.5); background: rgba(239, 68, 68, 0.14); }
.vr-notice { display: flex; align-items: center; gap: 10px; padding: 10px 18px; font-size: 13px; font-weight: 600; background: rgba(245, 158, 11, 0.12); color: #FCD34D; border-bottom: 1px solid rgba(245, 158, 11, 0.25); }
.vr-notice.is-grace { background: rgba(239, 68, 68, 0.14); color: #FCA5A5; border-bottom-color: rgba(239, 68, 68, 0.3); }
.vr-notice svg { width: 16px; height: 16px; flex-shrink: 0; }
.vr-opens { display: flex; flex-direction: column; gap: 4px; padding: 12px 14px; border-radius: 12px; background: rgba(99, 102, 241, 0.12); border: 1px solid rgba(99, 102, 241, 0.3); font-size: 12.5px; color: #C7D2FE; line-height: 1.5; }
.vr-opens strong { font-size: 14px; color: #FFFFFF; font-variant-numeric: tabular-nums; }

/* Full screen (native :fullscreen, or a fixed overlay where the browser has no element full screen) */
.vr.is-full { position: fixed; inset: 0; z-index: 1500; border-radius: 0; min-height: 0; height: 100vh; height: 100dvh; box-shadow: none; }
.vr:fullscreen { border-radius: 0; min-height: 0; height: 100%; width: 100%; }

/* Screen share stage */
.vr-share { position: relative; flex: 1; min-height: 0; display: flex; flex-direction: column; }
.vr-share > .vr-tile.is-screen { flex: 1; min-height: 0; border-color: rgba(255,255,255,0.06); }
.vr-stage.is-strip-hidden.is-sharing > .vr-filmstrip { display: none; }
.vr-share-tools { position: absolute; top: 12px; right: 12px; z-index: 3; display: flex; gap: 6px; flex-wrap: wrap; justify-content: flex-end; max-width: calc(100% - 24px); }
.vr-tool { display: inline-flex; align-items: center; gap: 6px; height: 34px; padding: 0 12px; border-radius: 10px; border: 1px solid rgba(255,255,255,0.14); background: rgba(2, 6, 23, 0.72); backdrop-filter: blur(8px); color: #FFFFFF; font-size: 12px; font-weight: 700; cursor: pointer; white-space: nowrap; transition: background .15s ease, border-color .15s ease; }
.vr-tool:hover { background: rgba(30, 41, 59, 0.92); border-color: rgba(165, 180, 252, 0.45); }
.vr-tool[aria-pressed="true"] { background: #4F48E6; border-color: #4F48E6; }
.vr-tool svg { width: 15px; height: 15px; flex-shrink: 0; }
/* Minimized: a floating window above the controls; people take the stage again */
.vr-share.is-mini { position: absolute; right: 16px; bottom: 78px; z-index: 6; flex: none; width: 300px; aspect-ratio: 16 / 9; border-radius: 14px; box-shadow: 0 18px 40px rgba(0, 0, 0, 0.5); }
.vr-share.is-mini > .vr-tile.is-screen { min-height: 0; border-radius: 14px; border: 1px solid rgba(255,255,255,0.18); cursor: pointer; }
.vr-share.is-mini .vr-share-tools { top: 8px; right: 8px; }
.vr-share.is-mini .vr-tool { height: 30px; padding: 0 10px; }
.vr-share.is-mini.is-card { width: auto; aspect-ratio: auto; }
.vr-share.is-mini.is-card .vr-share-tools { position: static; }
.vr-share.is-mini.is-card { display: flex; flex-direction: row; align-items: center; gap: 8px; padding: 8px; background: rgba(15, 21, 48, 0.94); border: 1px solid rgba(255,255,255,0.14); backdrop-filter: blur(8px); }
.vr-presenting-mini { display: flex; align-items: center; gap: 10px; padding-left: 6px; font-size: 12.5px; font-weight: 700; color: #FFFFFF; white-space: nowrap; }
.vr-presenting-mini .vr-btn { height: 30px; padding: 0 12px; }
.vr-live-dot { width: 8px; height: 8px; border-radius: 50%; background: #EF4444; box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.2); flex-shrink: 0; }
.vr-presenting-actions { display: flex; gap: 8px; flex-wrap: wrap; justify-content: center; }

/* Presenter card */
.vr-presenting { flex: 1; min-height: 220px; border-radius: 16px; border: 1px dashed rgba(255, 255, 255, 0.18); background: rgba(99, 102, 241, 0.08); display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 10px; text-align: center; padding: 56px 24px 24px; }
.vr-presenting h3 { font-size: 17px; font-weight: 800; color: #FFFFFF; margin: 0; }
.vr-presenting p { font-size: 13px; color: var(--vr-muted); margin: 0; max-width: 380px; line-height: 1.5; }

/* Files in chat */
.vr-chat { position: relative; }
.vr-file { display: flex; align-items: center; gap: 10px; padding: 8px 10px; border: 1px solid #E2E8F0; border-radius: 12px; background: #FFFFFF; text-decoration: none; color: #0F172A; max-width: 260px; min-width: 0; }
.vr-file:hover { border-color: #C7D2FE; }
.vr-msg.is-mine .vr-file { background: #EEF2FF; border-color: #C7D2FE; }
.vr-file-ext { width: 34px; height: 34px; border-radius: 8px; background: #EEF2FF; color: #4F48E6; font-size: 9.5px; font-weight: 800; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.vr-msg.is-mine .vr-file-ext { background: #FFFFFF; }
.vr-file-body { display: flex; flex-direction: column; min-width: 0; }
.vr-file-body strong { font-size: 12.5px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
.vr-file-body small { font-size: 11px; color: #64748B; }
.vr-drop { position: absolute; inset: 8px; border: 2px dashed #A5B4FC; border-radius: 14px; background: rgba(238, 242, 255, 0.94); display: flex; align-items: center; justify-content: center; font-size: 13px; font-weight: 700; color: #4F48E6; z-index: 2; pointer-events: none; }
.vr-chat-form .vr-attach { width: 40px; padding: 0; background: #F1F5F9; color: #475569; display: inline-flex; align-items: center; justify-content: center; flex-shrink: 0; }
.vr-chat-form .vr-attach:hover:not(:disabled) { background: #E2E8F0; }
.vr-chat-form .vr-attach svg { width: 17px; height: 17px; }

/* Ended / status screens */
.vr-center { flex: 1; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 14px; text-align: center; padding: 40px 20px; }
.vr-center-icon { width: 64px; height: 64px; border-radius: 18px; background: rgba(255,255,255,0.06); display: flex; align-items: center; justify-content: center; color: #A5B4FC; }
.vr-center-icon svg { width: 28px; height: 28px; }
.vr-center h2 { font-size: 20px; font-weight: 800; color: #FFFFFF; margin: 0; }
.vr-center p { font-size: 13.5px; color: var(--vr-muted); margin: 0; max-width: 420px; line-height: 1.55; }
.vr-center-actions { display: flex; gap: 10px; flex-wrap: wrap; justify-content: center; margin-top: 6px; }

/* Leave modal */
.vr-modal { position: fixed; inset: 0; z-index: 2000; background: rgba(0, 6, 35, 0.6); backdrop-filter: blur(4px); display: flex; align-items: center; justify-content: center; padding: 20px; }
.vr-modal-card { width: 100%; max-width: 420px; background: #FFFFFF; border-radius: 18px; padding: 26px; text-align: center; color: #0F172A; box-shadow: 0 24px 60px rgba(0,0,0,0.3); }
.vr-modal-card h3 { font-size: 17px; font-weight: 800; margin: 0 0 6px; color: #000623; }
.vr-modal-card p { font-size: 13px; color: #64748B; margin: 0 0 20px; line-height: 1.5; }
.vr-modal-actions { display: flex; flex-direction: column; gap: 8px; }
.vr-modal-actions button { height: 42px; border-radius: 10px; font-size: 13px; font-weight: 700; cursor: pointer; border: 1px solid #E2E8F0; background: #FFFFFF; color: #334155; }
.vr-modal-actions .is-danger { background: #000623; border-color: #000623; color: #FFFFFF; }
.vr-modal-actions .is-danger:hover:not(:disabled) { background: #1E2A5A; }
.vr-modal-actions button:not(.is-danger):not(.is-danger-soft):hover { background: #F8FAFC; color: #0F172A; }
.vr-modal-actions .is-danger-soft:hover { background: #FEE2E2; }
.vr-modal-actions .is-danger-soft { background: #FEF2F2; border-color: #FECACA; color: #B91C1C; }

/* Responsive */
@media (max-width: 1100px) {
    .vr-drawer { position: absolute; top: 0; right: 0; bottom: 0; z-index: 20; width: min(400px, 100%); box-shadow: -16px 0 40px rgba(0,0,0,0.35); }
    .vr-drawer-close { display: inline-flex; }
}
@media (max-width: 900px) {
    .vr-lobby { grid-template-columns: minmax(0, 1fr); padding: 18px; gap: 18px; }
    .vr-head-stats { order: 3; width: 100%; }
}
@media (max-width: 640px) {
    .vr { border-radius: 14px; min-height: calc(100vh - 120px); }
    .vr-head { padding: 12px; }
    .vr-head-icon { display: none; }
    .vr-head-main { flex: 1 1 0; }
    .vr-head-stats { justify-content: flex-start; }
    .vr-btn .vr-btn-label { display: none; }
    .vr-btn { padding: 0 10px; }
    .vr-stage { padding: 10px; }
    .vr-lobby-title { font-size: 19px; }
    .vr-grid.is-pip { display: block; position: relative; }
    .vr-grid.is-pip > .vr-tile:not(.is-local), .vr-grid.is-pip > .vr-waiting { position: absolute; inset: 0; }
    .vr-grid.is-pip > .vr-tile.is-local { position: absolute; right: 10px; top: 10px; width: 34%; max-width: 150px; aspect-ratio: 3 / 4; min-height: 0; z-index: 2; border-color: rgba(255,255,255,0.25); }
    .vr-controls { gap: 8px; }
    .vr-round { width: 42px; height: 42px; }
    .vr-filmstrip { height: 90px; }
    .vr-filmstrip .vr-tile { width: 130px; }
    .vr-tool { padding: 0 9px; }
    .vr-tool .vr-tool-label { display: none; }
    .vr-share-tools { top: 8px; right: 8px; }
    .vr-share.is-mini { right: 10px; bottom: 66px; width: 50%; max-width: 220px; }
    .vr-share.is-mini.is-card { width: auto; max-width: calc(100% - 20px); }
    .vr-presenting { padding-top: 52px; }
}
</style>

<?php if (empty($GLOBALS['__vrScript'])): $GLOBALS['__vrScript'] = true; ?>
<script src="<?= asset('assets/js/daily-js-0.72.2.js') ?>" defer></script>
<script>
function videoRoom(cfg) {
    // Media objects live outside Alpine's reactive state.
    let call = null, previewStream = null, audioCtx = null, meterRaf = null, timer = null, joinErrorMsg = '';
    let endsAt = 0, hardEnd = 0, warned = {}, lastSharing = false, drawerBeforeFull = false;
    const audioEls = new Map();

    const initials = (name) => (name || '?').split(/\s+/).filter(Boolean).slice(0, 2).map((w) => w[0]).join('').toUpperCase();
    const pad = (n) => String(n).padStart(2, '0');

    return {
        cfg,
        phase: cfg.notFound ? 'missing' : (cfg.roomEnded ? 'closed' : 'lobby'),   // lobby | joining | call | left | ended | closed | missing
        lobbyError: !cfg.videoOn ? 'Video interviews are temporarily disabled by the InternConnect team.' : '',
        mediaError: '',
        endedReason: '',
        cams: [], mics: [], camId: '', micId: '',
        camOn: true, micOn: true, micLevel: 0,
        participants: [], speakerSid: '', screenSid: '',
        sharing: false, network: 'good',
        screenMin: false, stripHidden: false, showSelf: false, isFull: false,
        remainingMs: 0, graceMs: 0, timeState: 'normal', notice: '',
        clockOffset: cfg.serverNow - Date.now(), opensIn: 0,
        uploading: false, dragFile: false,
        drawerOpen: window.matchMedia('(min-width: 1101px)').matches,
        drawerTab: cfg.defaultTab,
        chat: [], draft: '', unread: 0,
        leaveModal: false, ending: false,
        canShare: !!(navigator.mediaDevices && navigator.mediaDevices.getDisplayMedia) && cfg.allowScreen,

        init() {
            if (this.phase === 'closed' || this.phase === 'missing') return;
            this.tickOpening();
            setInterval(() => this.tickOpening(), 1000);
            this.startPreview();
            if (navigator.mediaDevices && navigator.mediaDevices.addEventListener) {
                navigator.mediaDevices.addEventListener('devicechange', () => this.listDevices());
            }
            window.addEventListener('pagehide', () => this.notifyLeft());
            // Leaving browser full screen with Esc must also reset our state.
            document.addEventListener('fullscreenchange', () => { if (!document.fullscreenElement) this.leftFull(); });
        },

        initials,
        get timeLabel() {
            const ms = this.timeState === 'grace' ? this.graceMs : this.remainingMs;
            const s = Math.max(0, Math.ceil(ms / 1000));
            return pad(Math.floor(s / 60)) + ':' + pad(s % 60);
        },
        get timeCaption() { return this.timeState === 'grace' ? 'extra minute' : 'left'; },
        get timeColor() { return { normal: '#10B981', warn: '#F59E0B', final: '#F97316', grace: '#EF4444' }[this.timeState] || '#10B981'; },
        get opensLabel() {
            const s = Math.max(0, Math.ceil(this.opensIn / 1000));
            const h = Math.floor(s / 3600);
            return (h ? h + 'h ' : '') + pad(Math.floor((s % 3600) / 60)) + 'm ' + pad(s % 60) + 's';
        },
        get isPresenting() {
            const me = this.participants.find((p) => p.local);
            return !!(me && this.screenSid && me.sid === this.screenSid);
        },
        get peerPresenterName() {
            const p = this.participants.find((x) => x.sid === this.screenSid);
            return p ? p.name : '';
        },
        get sharingBig() { return !!this.screenSid && !this.screenMin; },

        /* ── Full screen (native when the browser allows it, CSS overlay otherwise, e.g. iPhone) ── */
        toggleFull() {
            if (this.isFull) {
                this.leftFull();
                if (document.fullscreenElement && document.exitFullscreen) document.exitFullscreen().catch(() => {});
                return;
            }
            // Full screen gives the stage the whole width; the panel can still be reopened from the header.
            drawerBeforeFull = this.drawerOpen;
            this.drawerOpen = false;
            this.isFull = true;
            const el = this.$root;
            if (el.requestFullscreen && document.fullscreenEnabled) el.requestFullscreen().catch(() => {});
        },
        leftFull() {
            if (!this.isFull) return;
            this.isFull = false;
            if (drawerBeforeFull) this.drawerOpen = true;
        },

        /* ── Join window & call timing ─────────────────────── */
        tickOpening() {
            const now = Date.now() + this.clockOffset;
            this.opensIn = cfg.opensAt && now < cfg.opensAt ? cfg.opensAt - now : 0;
        },
        tickTime() {
            if (!endsAt) return;
            const now = Date.now() + this.clockOffset;
            const left = endsAt - now;
            this.remainingMs = Math.max(0, left);
            this.graceMs = Math.max(0, hardEnd - now);
            if (hardEnd && now >= hardEnd) { this.timeUp(); return; }
            if (left <= 0) {
                this.timeState = 'grace';
                this.notice = 'Time is up. Please wrap up now: the room closes in ' + this.timeLabel + '.';
                this.warnOnce('grace', 'Interview time is up. You have one extra minute to wrap up.');
            } else if (left <= 60000) {
                this.timeState = 'final';
                this.notice = 'Less than 1 minute left. Start wrapping up the interview.';
                this.warnOnce('one', 'One minute left in this interview.');
            } else if (left <= 300000) {
                this.timeState = 'warn';
                this.notice = '';
                this.warnOnce('five', 'Five minutes left in this interview.');
            } else {
                this.timeState = 'normal';
                this.notice = '';
            }
        },
        warnOnce(key, text) {
            if (warned[key]) return;
            warned[key] = true;
            window.dispatchEvent(new CustomEvent('show-toast', { detail: { text, type: key === 'five' ? 'info' : 'warning' } }));
        },
        async timeUp() {
            this.endedReason = 'The interview time is over. Thank you both for your time.';
            this.phase = 'ended';
            if (call) { try { await call.leave(); } catch (e) {} }
            this.cleanupCall();
        },
        post(url, body) {
            if (!url) return;
            fetch(url, { method: 'POST', headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' }, body: JSON.stringify(body || {}) }).catch(() => {});
        },
        get remoteCount() { return this.participants.filter((p) => !p.local).length; },
        get networkLabel() { return { good: 'Connection good', low: 'Connection weak', 'very-low': 'Connection poor' }[this.network] || 'Connecting'; },
        get networkColor() { return { good: '#10B981', low: '#F59E0B', 'very-low': '#EF4444' }[this.network] || '#94A3B8'; },
        get gridCols() { const n = this.participants.length + (this.remoteCount === 0 ? 1 : 0); return n <= 1 ? 1 : (n <= 4 ? 2 : 3); },

        /* ── Lobby ─────────────────────────────────────────── */
        async listDevices() {
            try {
                const devices = await navigator.mediaDevices.enumerateDevices();
                this.cams = devices.filter((d) => d.kind === 'videoinput').map((d, i) => ({ id: d.deviceId, label: d.label || ('Camera ' + (i + 1)) }));
                this.mics = devices.filter((d) => d.kind === 'audioinput').map((d, i) => ({ id: d.deviceId, label: d.label || ('Microphone ' + (i + 1)) }));
                if (!this.camId && this.cams[0]) this.camId = this.cams[0].id;
                if (!this.micId && this.mics[0]) this.micId = this.mics[0].id;
            } catch (e) {}
        },

        async startPreview() {
            this.stopPreview();
            if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                this.mediaError = 'This browser cannot access a camera. Please use a recent version of Chrome, Edge, Firefox or Safari.';
                return;
            }
            try {
                previewStream = await navigator.mediaDevices.getUserMedia({
                    video: this.camId ? { deviceId: { exact: this.camId } } : true,
                    audio: this.micId ? { deviceId: { exact: this.micId } } : true,
                });
                this.mediaError = '';
                previewStream.getVideoTracks().forEach((t) => { t.enabled = this.camOn; });
                previewStream.getAudioTracks().forEach((t) => { t.enabled = this.micOn; });
                this.$nextTick(() => { if (this.$refs.preview) this.$refs.preview.srcObject = previewStream; });
                this.startMeter();
                this.listDevices();
            } catch (e) {
                this.mediaError = e && e.name === 'NotAllowedError'
                    ? 'Camera and microphone access is blocked. Allow access in your browser address bar, then reload this page.'
                    : 'We could not find a working camera or microphone. You can still join and turn them on later.';
                this.listDevices();
            }
        },

        stopPreview() {
            if (meterRaf) cancelAnimationFrame(meterRaf);
            meterRaf = null;
            if (audioCtx) { audioCtx.close().catch(() => {}); audioCtx = null; }
            if (previewStream) previewStream.getTracks().forEach((t) => t.stop());
            previewStream = null;
            this.micLevel = 0;
        },

        startMeter() {
            const track = previewStream && previewStream.getAudioTracks()[0];
            if (!track) return;
            try {
                audioCtx = new (window.AudioContext || window.webkitAudioContext)();
                const analyser = audioCtx.createAnalyser();
                analyser.fftSize = 512;
                audioCtx.createMediaStreamSource(new MediaStream([track])).connect(analyser);
                const data = new Uint8Array(analyser.frequencyBinCount);
                const tick = () => {
                    analyser.getByteTimeDomainData(data);
                    let peak = 0;
                    for (let i = 0; i < data.length; i++) peak = Math.max(peak, Math.abs(data[i] - 128));
                    this.micLevel = this.micOn ? Math.min(100, Math.round((peak / 128) * 160)) : 0;
                    meterRaf = requestAnimationFrame(tick);
                };
                tick();
            } catch (e) {}
        },

        togglePreviewCam() {
            this.camOn = !this.camOn;
            if (previewStream) previewStream.getVideoTracks().forEach((t) => { t.enabled = this.camOn; });
        },
        togglePreviewMic() {
            this.micOn = !this.micOn;
            if (previewStream) previewStream.getAudioTracks().forEach((t) => { t.enabled = this.micOn; });
        },

        /* ── Join / call ───────────────────────────────────── */
        async join() {
            if (this.phase === 'joining') return;
            this.lobbyError = '';
            joinErrorMsg = '';
            this.phase = 'joining';
            let info;
            try {
                const res = await fetch(cfg.joinUrl, { method: 'POST', headers: { 'Accept': 'application/json' } });
                info = await res.json();
            } catch (e) {
                info = { ok: false, error: 'Network error. Check your internet connection and try again.' };
            }
            if (!info || !info.ok) {
                this.phase = 'lobby';
                this.lobbyError = (info && info.error) || 'We could not open the room. Please try again.';
                if (info && info.configured === false) this.lobbyError = 'The video service is not set up yet. Please try again later or contact InternConnect support.';
                if (info && info.code === 'too_early' && info.opens_at) { cfg.opensAt = info.opens_at; this.tickOpening(); }
                return;
            }
            if (!window.Daily) {
                this.phase = 'lobby';
                this.lobbyError = 'The video library did not load. Please refresh the page and try again.';
                return;
            }
            if (info.settings) {
                cfg.allowChat = !!info.settings.allow_chat;
                cfg.allowScreen = !!info.settings.allow_screenshare;
                cfg.allowFiles = !!info.settings.allow_files;
                this.canShare = this.canShare && cfg.allowScreen;
            }

            this.stopPreview();
            try {
                call = window.Daily.createCallObject({
                    audioSource: this.micId || true,
                    videoSource: this.camId || true,
                    subscribeToTracksAutomatically: true,
                });
                this.bindCallEvents();
                await call.join({ url: info.url, token: info.token, startVideoOff: !this.camOn, startAudioOff: !this.micOn });
                this.phase = 'call';
                this.clockOffset = (info.serverNow || Date.now()) - Date.now();
                endsAt = info.endsAt || 0;
                hardEnd = info.hardEnd || 0;
                warned = {};
                this.tickTime();
                timer = setInterval(() => this.tickTime(), 1000);
                this.sync();
            } catch (e) {
                this.cleanupCall();
                this.phase = 'lobby';
                this.lobbyError = this.describeJoinError(joinErrorMsg || (e && (e.errorMsg || e.message)) || '');
                this.startPreview();
            }
        },

// Turn Daily join errors into messages people can act on.
        describeJoinError(msg) {
            const m = String(msg || '').toLowerCase();
            if (m.includes('payment-method') || m.includes('account')) {
                return cfg.role === 'employer'
                    ? 'The video service account needs attention before calls can start. Please contact InternConnect support.'
                    : 'The video service is temporarily unavailable. Please contact InternConnect support.';
            }
            if (m.includes('exp') || m.includes('token')) return 'Your access to this room has expired. Refresh the page and join again.';
            if (m.includes('not allowed') || m.includes('permission')) return 'You do not have access to this room.';
            if (m.includes('full') || m.includes('max')) return 'This room is full.';
            return 'We could not connect to the video room. Check your internet connection and try again.';
        },

        bindCallEvents() {
            const sync = () => this.sync();
            ['participant-joined', 'participant-updated', 'participant-left', 'track-started', 'track-stopped'].forEach((ev) => call.on(ev, sync));
            call.on('participant-joined', (e) => {
                if (!e.participant.local) window.dispatchEvent(new CustomEvent('show-toast', { detail: { text: (e.participant.user_name || 'Participant') + ' joined the interview', type: 'info' } }));
            });
            call.on('participant-left', (e) => {
                if (!e.participant.local) window.dispatchEvent(new CustomEvent('show-toast', { detail: { text: (e.participant.user_name || 'Participant') + ' left the interview', type: 'info' } }));
            });
            call.on('active-speaker-change', (e) => { this.speakerSid = (e.activeSpeaker && e.activeSpeaker.peerId) || ''; });
            call.on('network-quality-change', (e) => { this.network = e.threshold || 'good'; });
            call.on('app-message', (e) => this.receiveMessage(e));
            call.on('local-screen-share-stopped', () => { this.sharing = false; });
            call.on('error', (e) => {
                // Errors while still joining are reported in the lobby by join().
                if (this.phase !== 'call') { joinErrorMsg = (e && e.errorMsg) || ''; return; }
                this.endedReason = (e && e.errorMsg && /eject|ended|deleted|expired|not exist/i.test(e.errorMsg))
                    ? 'The interview room was closed by the host.'
                    : 'The connection to the interview was lost.';
                this.cleanupCall();
                this.phase = 'ended';
            });
            call.on('left-meeting', () => {
                if (this.phase === 'call') { this.phase = 'left'; }
                this.cleanupCall();
            });
        },

        sync() {
            if (!call) return;
            const all = Object.values(call.participants());
            let screen = '';
            this.participants = all.map((p) => {
                const t = p.tracks || {};
                const video = t.video && t.video.state === 'playable';
                const audio = t.audio && t.audio.state === 'playable';
                const scr = t.screenVideo && t.screenVideo.state === 'playable';
                if (scr && !screen) screen = p.session_id;
                return { sid: p.session_id, name: p.user_name || 'Guest', local: !!p.local, owner: !!p.owner, video, audio, screen: scr };
            }).sort((a, b) => (a.local === b.local ? 0 : a.local ? 1 : -1));
            if (!screen) { this.screenMin = false; this.showSelf = false; }
            this.screenSid = screen;
            const me = this.participants.find((p) => p.local);
            if (me) { this.camOn = me.video; this.micOn = me.audio; this.sharing = me.screen; }
            if (this.sharing !== lastSharing) {
                lastSharing = this.sharing;
                this.post(cfg.eventUrl, { event: this.sharing ? 'screen_share_started' : 'screen_share_stopped' });
            }
            this.$nextTick(() => this.attachMedia(all));
        },

        attachMedia(all) {
            const root = this.$root;
            all.forEach((p) => {
                const t = p.tracks || {};
                const set = (kind, track) => {
                    root.querySelectorAll('video[data-vr-sid="' + p.session_id + '"][data-vr-kind="' + kind + '"]').forEach((el) => {
                        if (!track) { el.srcObject = null; return; }
                        const cur = el.srcObject && el.srcObject.getTracks()[0];
                        if (cur !== track) { el.srcObject = new MediaStream([track]); el.play().catch(() => {}); }
                    });
                };
                set('video', t.video && t.video.state === 'playable' ? t.video.persistentTrack : null);
                set('screen', t.screenVideo && t.screenVideo.state === 'playable' ? t.screenVideo.persistentTrack : null);

                if (!p.local) {
                    const track = t.audio && t.audio.state === 'playable' ? t.audio.persistentTrack : null;
                    let el = audioEls.get(p.session_id);
                    if (track) {
                        if (!el) { el = document.createElement('audio'); el.autoplay = true; this.$refs.audioSink.appendChild(el); audioEls.set(p.session_id, el); }
                        const cur = el.srcObject && el.srcObject.getTracks()[0];
                        if (cur !== track) { el.srcObject = new MediaStream([track]); el.play().catch(() => {}); }
                    } else if (el) {
                        el.srcObject = null;
                    }
                }
            });
            for (const [sid, el] of audioEls) {
                if (!all.some((p) => p.session_id === sid)) { el.remove(); audioEls.delete(sid); }
            }
        },

        toggleMic() { if (call) call.setLocalAudio(!this.micOn); },
        toggleCam() { if (call) call.setLocalVideo(!this.camOn); },
        async toggleShare() {
            if (!call || !this.canShare) return;
            try {
                if (this.sharing) call.stopScreenShare(); else await call.startScreenShare();
            } catch (e) {
                window.dispatchEvent(new CustomEvent('show-toast', { detail: { text: 'Screen sharing was cancelled or is not allowed.', type: 'info' } }));
            }
        },

        /* ── Chat over Daily app messages ──────────────────── */
        openChat() {
            this.drawerOpen = true;
            this.drawerTab = 'chat';
            this.unread = 0;
            this.$nextTick(() => this.$refs.chatInput && this.$refs.chatInput.focus());
        },
        stamp() { const d = new Date(); return pad(d.getHours()) + ':' + pad(d.getMinutes()); },
        sendChat() {
            const text = this.draft.trim();
            if (!text || !call || this.phase !== 'call' || !cfg.allowChat) return;
            call.sendAppMessage({ type: 'ic-chat', text: text.slice(0, 1000), name: cfg.selfName }, '*');
            this.chat.push({ mine: true, name: cfg.selfName, text: text.slice(0, 1000), time: this.stamp() });
            this.post(cfg.chatUrl, { body: text.slice(0, 1000) });
            this.draft = '';
            this.scrollChat();
        },
        async uploadFile(file) {
            if (!file || this.uploading || !cfg.allowFiles) return;
            const toast = (text, type) => window.dispatchEvent(new CustomEvent('show-toast', { detail: { text, type: type || 'warning' } }));
            const ext = (file.name.split('.').pop() || '').toLowerCase();
            if (!['pdf', 'doc', 'docx', 'txt', 'png', 'jpg', 'jpeg', 'webp'].includes(ext)) { toast('Share PDF, Word, text or image files only.'); return; }
            if (file.size > 10 * 1024 * 1024) { toast('Files must be under 10MB.'); return; }
            this.uploading = true;
            try {
                const form = new FormData();
                form.append('file', file);
                const res = await fetch(cfg.filesUrl, { method: 'POST', headers: { 'Accept': 'application/json' }, body: form });
                const json = await res.json().catch(() => ({}));
                if (!res.ok || !json.success) throw new Error(json.error || 'The file could not be shared. Please try again.');
                const f = { id: json.file.id, name: json.file.name, ext: json.file.ext, size: json.file.size_label };
                if (call) call.sendAppMessage({ type: 'ic-file', file: f, name: cfg.selfName }, '*');
                this.chat.push({ mine: true, name: cfg.selfName, text: '', file: f, time: this.stamp() });
                this.scrollChat();
                toast('File shared.', 'success');
            } catch (err) {
                toast(err.message, 'error');
            } finally {
                this.uploading = false;
            }
        },
        receiveMessage(e) {
            const d = e && e.data;
            if (d && d.type === 'ic-file' && d.file && Number.isInteger(d.file.id)) {
                const f = { id: d.file.id, name: String(d.file.name || 'File').slice(0, 120), ext: String(d.file.ext || '').slice(0, 5), size: String(d.file.size || '').slice(0, 12) };
                this.chat.push({ mine: false, name: String(d.name || 'Participant').slice(0, 60), text: '', file: f, time: this.stamp() });
                if (!(this.drawerOpen && this.drawerTab === 'chat')) this.unread++;
                this.scrollChat();
                return;
            }
            if (!d || d.type !== 'ic-chat' || typeof d.text !== 'string') return;
            this.chat.push({ mine: false, name: String(d.name || 'Participant').slice(0, 60), text: d.text.slice(0, 1000), time: this.stamp() });
            if (!(this.drawerOpen && this.drawerTab === 'chat')) this.unread++;
            this.scrollChat();
        },
        scrollChat() { this.$nextTick(() => { const el = this.$refs.chatStream; if (el) el.scrollTop = el.scrollHeight; }); },
        setTab(key) { this.drawerTab = key; if (key === 'chat') this.unread = 0; },

        /* ── Leave / end ───────────────────────────────────── */
        notifyLeft() {
            if (this.phase === 'call' && navigator.sendBeacon) navigator.sendBeacon(cfg.leftUrl);
        },
        async leave() {
            this.leaveModal = false;
            this.notifyLeft();
            if (call) { try { await call.leave(); } catch (e) {} }
            this.cleanupCall();
            this.phase = 'left';
        },
        async endForEveryone() {
            if (!cfg.endUrl || this.ending) return;
            this.ending = true;
            try { await fetch(cfg.endUrl, { method: 'POST', headers: { 'Accept': 'application/json' } }); } catch (e) {}
            this.ending = false;
            this.leaveModal = false;
            if (call) { try { await call.leave(); } catch (e) {} }
            this.cleanupCall();
            this.endedReason = 'You ended the interview for everyone.';
            this.phase = 'ended';
        },
        cleanupCall() {
            if (timer) clearInterval(timer);
            timer = null;
            if (call) { try { call.destroy(); } catch (e) {} call = null; }
            for (const el of audioEls.values()) el.remove();
            audioEls.clear();
            this.participants = [];
            this.screenSid = '';
            this.sharing = false;
            this.screenMin = false;
            this.showSelf = false;
            if (this.isFull) this.toggleFull();
        },
        rejoin() {
            this.phase = 'lobby';
            this.startPreview();
        }
    };
}
</script>
<?php endif; ?>

<div class="vr" x-data="videoRoom(<?= htmlspecialchars(json_encode($vrPayload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE), ENT_QUOTES, 'UTF-8') ?>)">
    <div x-ref="audioSink" hidden></div>

    <!-- Header -->
    <header class="vr-head">
        <div class="vr-head-main">
            <span class="vr-head-icon" aria-hidden="true">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
            </span>
            <div class="vr-head-text">
                <h1 class="vr-title"><?= htmlspecialchars($room['title']) ?></h1>
                <p class="vr-sub"><?= $vrConfig['subtitle'] ?></p>
            </div>
        </div>
        <div class="vr-head-stats" x-show="phase === 'call'" style="display: none;">
            <span class="vr-pill" :class="'is-' + timeState" aria-live="off"><span class="vr-dot" :style="{ background: timeColor }"></span><span x-text="timeLabel"></span><small x-text="timeCaption"></small></span>
            <span class="vr-pill"><span class="vr-dot" :style="{ background: networkColor }"></span><span x-text="networkLabel"></span></span>
        </div>
        <div class="vr-head-actions">
            <button type="button" class="vr-btn" x-show="phase === 'call'" style="display: none;" @click="drawerOpen = !drawerOpen" :aria-expanded="drawerOpen">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-8a2 2 0 00-2 2"/></svg>
                <span class="vr-btn-label" x-text="drawerOpen ? 'Hide panel' : <?= htmlspecialchars(json_encode('Show ' . strtolower($vrConfig['drawerTitle'])), ENT_QUOTES, 'UTF-8') ?>"></span>
            </button>
            <button type="button" class="vr-btn is-danger" x-show="phase === 'call'" style="display: none;" @click="leaveModal = true">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                <span class="vr-btn-label">Leave</span>
            </button>
            <a class="vr-btn" x-show="phase !== 'call'" href="<?= htmlspecialchars($vrConfig['exitUrl']) ?>">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                <span class="vr-btn-label"><?= htmlspecialchars($vrConfig['exitLabel']) ?></span>
            </a>
        </div>
    </header>

    <!-- Time warnings (last minute and the extra wrap-up minute) -->
    <div class="vr-notice" :class="'is-' + timeState" x-show="phase === 'call' && notice" style="display: none;" role="status">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <span x-text="notice"></span>
    </div>

    <!-- Lobby -->
    <section class="vr-lobby" x-show="phase === 'lobby' || phase === 'joining'">
        <div class="vr-preview">
            <video x-ref="preview" autoplay playsinline muted x-show="camOn && !mediaError"></video>
            <div class="vr-preview-off" x-show="!camOn || mediaError">
                <span class="vr-avatar" x-text="initials(cfg.selfName)"></span>
                <span x-text="mediaError ? 'Camera unavailable' : 'Your camera is off'"></span>
            </div>
            <div class="vr-preview-bar">
                <button type="button" class="vr-round" :class="{ 'is-off': !micOn }" @click="togglePreviewMic()" :aria-label="micOn ? 'Mute microphone' : 'Unmute microphone'" :aria-pressed="!micOn">
                    <svg x-show="micOn" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"/></svg>
                    <svg x-show="!micOn" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11a7 7 0 01-1.2 3.9M12 18a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4M9 9v2a3 3 0 004.7 2.5M15 11V5a3 3 0 00-5.8-1.1M3 3l18 18"/></svg>
                </button>
                <button type="button" class="vr-round" :class="{ 'is-off': !camOn }" @click="togglePreviewCam()" :aria-label="camOn ? 'Turn camera off' : 'Turn camera on'" :aria-pressed="!camOn">
                    <svg x-show="camOn" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                    <svg x-show="!camOn" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M3 3l18 18M10.5 6H13a2 2 0 012 2v2.5M15 14v2a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 011.2-1.8"/></svg>
                </button>
            </div>
        </div>

        <div class="vr-lobby-panel">
            <span class="vr-lobby-kicker"><?= htmlspecialchars($vrConfig['lobbyKicker']) ?></span>
            <h2 class="vr-lobby-title"><?= htmlspecialchars($vrConfig['lobbyTitle']) ?></h2>
            <p class="vr-lobby-text"><?= htmlspecialchars($vrConfig['lobbyText']) ?></p>

            <div class="vr-field" x-show="cams.length > 1">
                <label for="vr-cam">Camera</label>
                <div class="vr-select-wrap">
                    <svg class="vr-select-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                    <select id="vr-cam" class="vr-select" x-model="camId" @change="startPreview()">
                        <template x-for="d in cams" :key="d.id"><option :value="d.id" x-text="d.label"></option></template>
                    </select>
                    <svg class="vr-select-caret" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                </div>
            </div>
            <div class="vr-field" x-show="mics.length > 1">
                <label for="vr-mic">Microphone</label>
                <div class="vr-select-wrap">
                    <svg class="vr-select-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"/></svg>
                    <select id="vr-mic" class="vr-select" x-model="micId" @change="startPreview()">
                        <template x-for="d in mics" :key="d.id"><option :value="d.id" x-text="d.label"></option></template>
                    </select>
                    <svg class="vr-select-caret" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                </div>
            </div>
            <div class="vr-meter" x-show="!mediaError">
                <span>Mic</span>
                <div class="vr-meter-bar" role="meter" aria-label="Microphone level" aria-valuemin="0" aria-valuemax="100" :aria-valuenow="micLevel"><span :style="{ width: micLevel + '%' }"></span></div>
                <span x-text="micOn ? (micLevel > 4 ? 'Picking up sound' : 'Say something to test') : 'Muted'"></span>
            </div>

            <div class="vr-alert" x-show="mediaError" style="display: none;">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
                <span x-text="mediaError"></span>
            </div>
            <div class="vr-alert" x-show="lobbyError" style="display: none;">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
                <span>
                    <span x-text="lobbyError"></span>
                    <template x-if="cfg.devHint">
                        <span style="display: block; margin-top: 6px;">Local setup: add your key as <code>DAILY_API_KEY</code> in <code>.env</code> (see docs/47-video-interviews.md).</span>
                    </template>
                </span>
            </div>

            <div class="vr-opens" x-show="opensIn > 0" style="display: none;" role="status">
                <strong>Room opens in <span x-text="opensLabel"></span></strong>
                <span x-text="'You can join from ' + cfg.earlyMin + ' minutes before the start time. This page will let you in automatically.'"></span>
            </div>

            <button type="button" class="vr-join" @click="join()" :disabled="phase === 'joining' || !cfg.videoOn || opensIn > 0">
                <svg x-show="phase !== 'joining'" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                <svg x-show="phase === 'joining'" class="vr-spin" style="display: none;" fill="none" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3" opacity=".25"/><path fill="currentColor" d="M4 12a8 8 0 018-8v3a5 5 0 00-5 5H4z"/></svg>
                <span x-text="phase === 'joining' ? 'Connecting to the room...' : <?= htmlspecialchars(json_encode($vrConfig['joinLabel']), ENT_QUOTES, 'UTF-8') ?>"></span>
            </button>
        </div>
    </section>

    <!-- Call -->
    <section class="vr-call" x-show="phase === 'call'" style="display: none;">
        <div class="vr-stage" :class="{ 'is-sharing': sharingBig, 'is-strip-hidden': stripHidden }">
            <!-- Screen share view: shared screen large + people in a filmstrip. It can be minimized to a
                 floating window, the filmstrip can be hidden, and the whole room can go full screen. -->
            <template x-if="screenSid">
                <div class="vr-share" :class="{ 'is-mini': screenMin, 'is-card': isPresenting && !showSelf }">
                    <!-- Presenter sees a card instead of their own screen (no mirror effect) unless they open the preview -->
                    <div class="vr-presenting" x-show="isPresenting && !showSelf && !screenMin">
                        <span class="vr-center-icon"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg></span>
                        <h3>You are presenting</h3>
                        <p x-text="cfg.peerName + ' can see your screen. Preview it here if you want to check what they see.'"></p>
                        <div class="vr-presenting-actions">
                            <button type="button" class="vr-btn" @click="showSelf = true">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                <span>Preview my screen</span>
                            </button>
                            <button type="button" class="vr-btn is-light" @click="toggleShare()">Stop presenting</button>
                        </div>
                    </div>
                    <div class="vr-presenting-mini" x-show="isPresenting && !showSelf && screenMin">
                        <span class="vr-live-dot" aria-hidden="true"></span>
                        <span>You are presenting</span>
                        <button type="button" class="vr-btn is-light" @click="toggleShare()">Stop</button>
                    </div>
                    <div class="vr-tile is-screen" x-show="!isPresenting || showSelf" @dblclick="screenMin ? (screenMin = false) : toggleFull()">
                        <video :data-vr-sid="screenSid" data-vr-kind="screen" autoplay playsinline muted></video>
                        <span class="vr-tag" x-show="!screenMin"><span x-text="isPresenting ? 'Your screen (preview)' : peerPresenterName + ' is presenting'"></span></span>
                    </div>

                    <div class="vr-share-tools" role="toolbar" aria-label="Shared screen view">
                        <button type="button" class="vr-tool" x-show="isPresenting && showSelf && !screenMin" @click="showSelf = false" aria-label="Hide the preview of my screen">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                            <span class="vr-tool-label">Hide preview</span>
                        </button>
                        <button type="button" class="vr-tool" x-show="!screenMin" @click="stripHidden = !stripHidden" :aria-pressed="stripHidden" :aria-label="stripHidden ? 'Show the people strip' : 'Hide the people strip'">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <span class="vr-tool-label" x-text="stripHidden ? 'Show people' : 'Hide people'"></span>
                        </button>
                        <button type="button" class="vr-tool" @click="screenMin = !screenMin" :aria-label="screenMin ? 'Expand the shared screen' : 'Minimize the shared screen'">
                            <svg x-show="!screenMin" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 9V4.5M9 9H4.5M9 9L3.75 3.75M9 15v4.5M9 15H4.5M9 15l-5.25 5.25M15 9h4.5M15 9V4.5M15 9l5.25-5.25M15 15h4.5M15 15v4.5m0-4.5l5.25 5.25"/></svg>
                            <svg x-show="screenMin" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3.75v4.5m0-4.5h4.5m-4.5 0L9 9M3.75 20.25v-4.5m0 4.5h4.5m-4.5 0L9 15M20.25 3.75h-4.5m4.5 0v4.5m0-4.5L15 9m5.25 11.25h-4.5m4.5 0v-4.5m0 4.5L15 15"/></svg>
                            <span class="vr-tool-label" x-text="screenMin ? 'Expand' : 'Minimize'"></span>
                        </button>
                        <button type="button" class="vr-tool" x-show="!screenMin" @click="toggleFull()" :aria-pressed="isFull" :aria-label="isFull ? 'Exit full screen' : 'Full screen'">
                            <svg x-show="!isFull" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3.75v4.5m0-4.5h4.5m-4.5 0L9 9M3.75 20.25v-4.5m0 4.5h4.5m-4.5 0L9 15M20.25 3.75h-4.5m4.5 0v4.5m0-4.5L15 9m5.25 11.25h-4.5m4.5 0v-4.5m0 4.5L15 15"/></svg>
                            <svg x-show="isFull" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 9V4.5M9 9H4.5M9 9L3.75 3.75M9 15v4.5M9 15H4.5M9 15l-5.25 5.25M15 9h4.5M15 9V4.5M15 9l5.25-5.25M15 15h4.5M15 15v4.5m0-4.5l5.25 5.25"/></svg>
                            <span class="vr-tool-label" x-text="isFull ? 'Exit full screen' : 'Full screen'"></span>
                        </button>
                    </div>
                </div>
            </template>
            <div :class="sharingBig ? 'vr-filmstrip' : ('vr-grid' + (participants.length + (remoteCount === 0 ? 1 : 0) === 2 ? ' is-pip' : ''))" :style="sharingBig ? {} : { '--vr-cols': gridCols }">
                <template x-for="p in participants" :key="p.sid">
                    <div class="vr-tile" :class="{ 'is-local': p.local, 'is-speaking': speakerSid === p.sid && !p.local }">
                        <video :data-vr-sid="p.sid" data-vr-kind="video" autoplay playsinline muted x-show="p.video"></video>
                        <div class="vr-tile-off" x-show="!p.video">
                            <span class="vr-avatar" x-text="initials(p.name)"></span>
                            <span x-text="p.local ? 'Your camera is off' : 'Camera off'"></span>
                        </div>
                        <span class="vr-tag">
                            <svg x-show="!p.audio" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.4" aria-label="Muted"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11a7 7 0 01-1.2 3.9M12 18a7 7 0 01-7-7m7 7v4M9 9v2a3 3 0 004.7 2.5M15 11V5a3 3 0 00-5.8-1.1M3 3l18 18"/></svg>
                            <span x-text="p.local ? 'You' : p.name"></span>
                        </span>
                    </div>
                </template>
                <div class="vr-waiting" x-show="remoteCount === 0 && !sharingBig">
                    <span class="vr-pulse"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></span>
                    <strong x-text="'Waiting for ' + cfg.peerName"></strong>
                    <span>You are connected. They will appear here as soon as they join.</span>
                </div>
            </div>

            <div class="vr-controls">
                <button type="button" class="vr-round" :class="{ 'is-off': !micOn }" @click="toggleMic()" :aria-label="micOn ? 'Mute microphone' : 'Unmute microphone'" :aria-pressed="!micOn">
                    <svg x-show="micOn" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"/></svg>
                    <svg x-show="!micOn" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11a7 7 0 01-1.2 3.9M12 18a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4M9 9v2a3 3 0 004.7 2.5M15 11V5a3 3 0 00-5.8-1.1M3 3l18 18"/></svg>
                </button>
                <button type="button" class="vr-round" :class="{ 'is-off': !camOn }" @click="toggleCam()" :aria-label="camOn ? 'Turn camera off' : 'Turn camera on'" :aria-pressed="!camOn">
                    <svg x-show="camOn" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                    <svg x-show="!camOn" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M3 3l18 18M10.5 6H13a2 2 0 012 2v2.5M15 14v2a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 011.2-1.8"/></svg>
                </button>
                <button type="button" class="vr-round" x-show="canShare" :class="{ 'is-on': sharing }" @click="toggleShare()" :aria-label="sharing ? 'Stop sharing your screen' : 'Share your screen'" :aria-pressed="sharing">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                </button>
                <button type="button" class="vr-round" x-show="cfg.allowChat" @click="openChat()" aria-label="Open chat">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                    <span class="vr-badge" x-show="unread > 0" x-text="unread"></span>
                </button>
                <button type="button" class="vr-round" :class="{ 'is-on': isFull }" @click="toggleFull()" :aria-pressed="isFull" :aria-label="isFull ? 'Exit full screen' : 'Full screen'">
                    <svg x-show="!isFull" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3.75v4.5m0-4.5h4.5m-4.5 0L9 9M3.75 20.25v-4.5m0 4.5h4.5m-4.5 0L9 15M20.25 3.75h-4.5m4.5 0v4.5m0-4.5L15 9m5.25 11.25h-4.5m4.5 0v-4.5m0 4.5L15 15"/></svg>
                    <svg x-show="isFull" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 9V4.5M9 9H4.5M9 9L3.75 3.75M9 15v4.5M9 15H4.5M9 15l-5.25 5.25M15 9h4.5M15 9V4.5M15 9l5.25-5.25M15 15h4.5M15 15v4.5m0-4.5l5.25 5.25"/></svg>
                </button>
                <button type="button" class="vr-round is-end" @click="leaveModal = true" aria-label="Leave the interview">
                    <svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 9c-1.6 0-3.15.25-4.6.72v3.1c0 .39-.23.74-.56.9-.98.49-1.87 1.12-2.66 1.85-.18.18-.43.28-.7.28-.28 0-.53-.11-.71-.29L.29 13.08a.996.996 0 0 1 0-1.41C3.34 8.78 7.46 7 12 7s8.66 1.78 11.71 4.67c.39.39.39 1.02 0 1.41l-2.48 2.48c-.18.18-.43.29-.71.29-.27 0-.52-.11-.7-.28-.79-.74-1.69-1.36-2.67-1.85-.33-.16-.56-.5-.56-.9v-3.1C15.15 9.25 13.6 9 12 9z"/></svg>
                </button>
            </div>
        </div>

        <!-- Role drawer -->
        <aside class="vr-drawer" x-show="drawerOpen" x-transition.opacity.duration.150ms aria-label="<?= htmlspecialchars($vrConfig['drawerTitle']) ?>">
            <div class="vr-drawer-head">
                <h2 class="vr-drawer-title">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <span><?= htmlspecialchars($vrConfig['drawerTitle']) ?></span>
                </h2>
                <button type="button" class="vr-drawer-close" @click="drawerOpen = false" aria-label="Close panel">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <div class="vr-tabs no-scrollbar" role="tablist">
                <template x-for="t in cfg.tabs" :key="t.key">
                    <button type="button" role="tab" class="vr-tab" :class="{ 'is-active': drawerTab === t.key }" :aria-selected="drawerTab === t.key" @click="setTab(t.key)" x-text="t.label"></button>
                </template>
                <button type="button" role="tab" class="vr-tab" x-show="cfg.allowChat" :class="{ 'is-active': drawerTab === 'chat' }" :aria-selected="drawerTab === 'chat'" @click="setTab('chat')">
                    <span>Chat</span><span class="vr-tab-count" x-show="unread > 0" x-text="unread"></span>
                </button>
            </div>

            <?php include $vrDrawerView; ?>

            <!-- Chat (both roles) -->
            <div class="vr-chat" x-show="drawerTab === 'chat'" @dragover.prevent="dragFile = cfg.allowFiles" @dragleave.prevent="dragFile = false" @drop.prevent="dragFile = false; uploadFile($event.dataTransfer.files[0])">
                <div class="vr-chat-stream" x-ref="chatStream" aria-live="polite">
                    <p class="vr-chat-empty" x-show="chat.length === 0" x-text="'Messages you send here go straight to ' + cfg.peerName + ' during the call.'"></p>
                    <template x-for="(m, i) in chat" :key="i">
                        <div class="vr-msg" :class="{ 'is-mine': m.mine }">
                            <div class="vr-msg-bubble" x-show="!m.file" x-text="m.text"></div>
                            <template x-if="m.file">
                                <a class="vr-file" :href="cfg.filesUrl + '/' + m.file.id" target="_blank" rel="noopener">
                                    <span class="vr-file-ext" x-text="m.file.ext.toUpperCase()"></span>
                                    <span class="vr-file-body"><strong x-text="m.file.name"></strong><small x-text="m.file.size + ' · Open'"></small></span>
                                </a>
                            </template>
                            <span class="vr-msg-meta" x-text="(m.mine ? 'You' : m.name) + ' · ' + m.time"></span>
                        </div>
                    </template>
                </div>
                <form class="vr-chat-form" @submit.prevent="sendChat()">
                    <input type="file" x-ref="fileInput" hidden accept=".pdf,.doc,.docx,.txt,.png,.jpg,.jpeg,.webp" @change="uploadFile($event.target.files[0]); $event.target.value = ''">
                    <button type="button" class="vr-attach" x-show="cfg.allowFiles" @click="$refs.fileInput.click()" :disabled="uploading" :aria-label="uploading ? 'Uploading file' : 'Share a file'">
                        <svg x-show="!uploading" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                        <svg x-show="uploading" class="vr-spin" style="display: none;" fill="none" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3" opacity=".25"/><path fill="currentColor" d="M4 12a8 8 0 018-8v3a5 5 0 00-5 5H4z"/></svg>
                    </button>
                    <input type="text" x-ref="chatInput" x-model="draft" maxlength="1000" placeholder="Type a message" aria-label="Chat message">
                    <button type="submit" :disabled="!draft.trim()">Send</button>
                </form>
                <p class="vr-chat-note" x-text="cfg.allowFiles ? 'Drop PDFs, documents or images here to share them. Messages and files are saved to the interview history.' : 'Messages are saved to the interview history.'"></p>
                <div class="vr-drop" x-show="dragFile" style="display: none;">Drop to share this file</div>
            </div>
        </aside>
    </section>

    <!-- Left / ended / closed -->
    <section class="vr-center" x-show="phase === 'left' || phase === 'ended' || phase === 'closed' || phase === 'missing'" style="display: none;">
        <span class="vr-center-icon">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
        </span>
        <h2 x-text="phase === 'missing' ? 'Interview not found' : (phase === 'left' ? 'You left the interview' : (phase === 'closed' ? 'This interview room is closed' : 'The interview has ended'))"></h2>
        <p x-text="phase === 'missing' ? 'This interview link is not valid. Open the interview from your interviews page instead.' : (phase === 'left' ? 'You can rejoin while the interview is still running.' : (phase === 'closed' ? 'The host or the InternConnect team has closed this room. Contact the other party if you need a new session.' : endedReason))"></p>
        <div class="vr-center-actions">
            <button type="button" class="vr-btn is-primary" x-show="phase === 'left'" @click="rejoin()">Rejoin interview</button>
            <a class="vr-btn is-primary" x-show="cfg.reportUrl && (phase === 'ended' || phase === 'closed')" :href="cfg.reportUrl">View interview summary</a>
            <a class="vr-btn is-light" href="<?= htmlspecialchars($vrConfig['exitUrl']) ?>"><?= htmlspecialchars($vrConfig['exitLabel']) ?></a>
        </div>
    </section>

    <!-- Leave confirmation -->
    <div class="vr-modal" x-show="leaveModal" x-cloak style="display: none;" @keydown.escape.window="leaveModal = false">
        <div class="vr-modal-card" @click.outside="leaveModal = false" role="dialog" aria-modal="true" aria-labelledby="vr-leave-title">
            <h3 id="vr-leave-title">Leave the interview?</h3>
            <p x-text="cfg.endUrl ? 'Leave just for yourself, or end the call and close the room for everyone.' : 'You can rejoin while the interview is still running.'"></p>
            <div class="vr-modal-actions">
                <button type="button" class="is-danger-soft" @click="leave()">Leave call</button>
                <button type="button" class="is-danger" x-show="cfg.endUrl" @click="endForEveryone()" :disabled="ending" x-text="ending ? 'Ending...' : 'End interview for everyone'"></button>
                <button type="button" @click="leaveModal = false">Stay in call</button>
            </div>
        </div>
    </div>
</div>
