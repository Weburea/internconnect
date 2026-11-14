<?php
declare(strict_types=1);
/**
 * Admin: Video Interviews console
 * Route: /admin/interviews
 * Spec: docs/47-video-interviews.md (Admin controls)
 *
 * - Service status (Daily API key), KPIs, live interview map (where participants join from)
 * - Interview sessions (one row per scheduled interview): status, who is in the room, result,
 *   actions (view, mark ready, cancel, end room, reopen) and admin scheduling
 * - Platform video settings and recent activity with badges, location and device
 *
 * @var array $staff
 * @var array $settings    VideoInterviewService::settings()
 * @var bool  $configured  DAILY_API_KEY present
 * @var array $sessions    VideoInterviewService::sessions()
 * @var array $events      latest interview_room_events rows
 * @var array $people      ['candidates' => [...], 'employers' => [...]]
 */

use App\Services\VideoInterviewService;

$eventRows = array_map(static function (array $e) {
    [$label, $tone] = VideoInterviewService::eventMeta((string)$e['event']);
    $event = (string)$e['event'];
    $cat = in_array($event, ['joined', 'left'], true) ? 'people'
        : (in_array($event, ['screen_share_started', 'screen_share_stopped', 'file_shared'], true) ? 'sharing' : 'room');
    return [
        'label'  => $label,
        'tone'   => $tone,
        'cat'    => $cat,
        'who'    => ucfirst((string)$e['role']),
        'id'     => (int)$e['interview_id'],
        'url'    => $e['interview_id'] ? url('/admin/interviews/' . (int)$e['interview_id']) : '',
        'when'   => date('M j, g:i A', strtotime((string)$e['created_at'])),
        'place'  => $e['city'] ? trim($e['city'] . ', ' . $e['country']) : '',
        'device' => (string)($e['device'] ?? ''),
        'ip'     => (string)($e['ip_masked'] ?? ''),
    ];
}, $events);

$aivPayload = [
    'settings'    => $settings,
    'sessions'    => $sessions,
    'people'      => $people,
    'urls'        => [
        'settings' => url('/admin/interviews/settings'),
        'schedule' => url('/admin/interviews/schedule'),
        'end'      => url('/admin/interviews/__ID__/end'),
        'reopen'   => url('/admin/interviews/__ID__/reopen'),
        'status'   => url('/admin/interviews/__ID__/status'),
        'presence' => url('/admin/api/interviews/presence'),
        'detail'   => url('/admin/interviews/__ID__'),
        'room'     => url('/admin/interviews/__ID__/room'),
    ],
    'configured'  => $configured,
];
?>
<div class="admin-shell">
    <?php include dirname(__DIR__, 2) . '/shared/components/admin-sidebar.php'; ?>

    <div class="admin-main-wrap">
        <?php include dirname(__DIR__, 2) . '/shared/components/admin-topnav.php'; ?>

        <main class="admin-content-body">
            <style>
            .aiv { display: flex; flex-direction: column; gap: 20px; }
            .aiv * { box-sizing: border-box; }
            .aiv-head { display: flex; align-items: flex-end; justify-content: space-between; gap: 12px 20px; flex-wrap: wrap; }
            .aiv-head h1 { font-size: 24px; font-weight: 800; color: #000623; margin: 0 0 4px; letter-spacing: -0.02em; }
            .aiv-head p { font-size: 13.5px; color: #64748B; margin: 0; max-width: 640px; line-height: 1.5; }
            .aiv-head-actions { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }
            .aiv-status { display: inline-flex; align-items: center; gap: 8px; height: 34px; padding: 0 14px; border-radius: 9999px; font-size: 12.5px; font-weight: 700; white-space: nowrap; }
            .aiv-status .dot { width: 8px; height: 8px; border-radius: 50%; }
            .aiv-status.is-ok { background: #ECFDF5; color: #047857; }
            .aiv-status.is-warn { background: #FFFBEB; color: #B45309; }
            .aiv-setup { font-size: 12.5px; color: #92400E; background: #FFFBEB; border: 1px solid #FDE68A; border-radius: 12px; padding: 12px 14px; line-height: 1.55; }
            .aiv-setup code { background: #FEF3C7; padding: 1px 6px; border-radius: 6px; }
            .aiv-kpis { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 14px; }
            .aiv-kpi { background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 14px; padding: 16px 18px; min-width: 0; }
            .aiv-kpi span { display: block; font-size: 11.5px; font-weight: 700; color: #64748B; text-transform: uppercase; letter-spacing: .05em; }
            .aiv-kpi strong { display: block; font-size: 24px; font-weight: 800; color: #000623; line-height: 1.1; margin-top: 6px; }
            .aiv-kpi small { display: block; font-size: 12px; color: #94A3B8; margin-top: 4px; }
            .aiv-grid { display: flex; flex-direction: column; gap: 20px; }
            .aiv-side { display: grid; grid-template-columns: minmax(0, 1fr) minmax(0, 1fr); gap: 20px; align-items: start; min-width: 0; }
            .aiv-card { background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 16px; min-width: 0; }
            .aiv-card-head { display: flex; align-items: center; justify-content: space-between; gap: 10px; padding: 16px 18px; border-bottom: 1px solid #F1F5F9; flex-wrap: wrap; }
            .aiv-card-head h2 { font-size: 15px; font-weight: 800; color: #000623; margin: 0; }
            .aiv-card-head p { font-size: 12.5px; color: #64748B; margin: 2px 0 0; }
            .aiv-card-body { padding: 16px 18px; }
            .aiv-btn { display: inline-flex; align-items: center; justify-content: center; gap: 6px; height: 36px; padding: 0 14px; border-radius: 9px; border: 1px solid #E2E8F0; background: #FFFFFF; font-size: 12.5px; font-weight: 700; color: #334155; cursor: pointer; white-space: nowrap; flex-shrink: 0; text-decoration: none; }
            .aiv-btn:hover:not(:disabled) { background: #F8FAFC; border-color: #CBD5E1; color: #0F172A; }
            .aiv-btn:disabled { opacity: .5; cursor: not-allowed; }
            .aiv-btn svg { width: 15px; height: 15px; }
            .aiv-btn.is-primary { background: #4F48E6; border-color: #4F48E6; color: #FFFFFF; }
            .aiv-btn.is-primary:hover:not(:disabled) { background: #4338CA; border-color: #4338CA; color: #FFFFFF; }
            .aiv-btn.is-block { width: 100%; height: 40px; margin-top: 8px; }
            /* End / cancel actions use the brand secondary (navy) instead of red */
            .aiv-btn.is-danger { color: #000623; border-color: #000623; background: #FFFFFF; }
            .aiv-btn.is-danger:hover:not(:disabled) { background: #000623; border-color: #000623; color: #FFFFFF; }
            .aiv-filters { display: flex; gap: 6px; padding: 12px 18px 0; overflow-x: auto; scrollbar-width: none; }
            .aiv-filters::-webkit-scrollbar { display: none; }
            .aiv-filter { height: 32px; padding: 0 12px; border-radius: 9999px; border: 1px solid #E2E8F0; background: #FFFFFF; font-size: 12.5px; font-weight: 600; color: #475569; cursor: pointer; white-space: nowrap; flex-shrink: 0; }
            .aiv-filter.is-on { background: #EEF2FF; border-color: #C7D2FE; color: #4F48E6; }
            .aiv-filter b { font-weight: 800; margin-left: 4px; }
            .aiv-table { width: 100%; border-collapse: collapse; }
            .aiv-table th { text-align: left; font-size: 11px; font-weight: 700; color: #64748B; text-transform: uppercase; letter-spacing: .05em; padding: 10px 14px; background: #F8FAFC; white-space: nowrap; }
            .aiv-table td { padding: 12px 14px; border-top: 1px solid #F1F5F9; font-size: 13px; color: #334155; vertical-align: middle; }
            .aiv-name { font-weight: 700; color: #0F172A; white-space: nowrap; }
            .aiv-sub { display: block; font-size: 12px; color: #64748B; margin-top: 2px; white-space: nowrap; }
            .aiv-nowrap { white-space: nowrap; }
            .aiv-badge { display: inline-flex; align-items: center; gap: 6px; padding: 3px 10px; border-radius: 9999px; font-size: 11.5px; font-weight: 700; white-space: nowrap; }
            .aiv-badge .dot { width: 6px; height: 6px; border-radius: 50%; background: currentColor; }
            .aiv-tone-green { background: #ECFDF5; color: #047857; }
            .aiv-tone-slate { background: #F1F5F9; color: #475569; }
            .aiv-tone-indigo { background: #EEF2FF; color: #4F48E6; }
            .aiv-tone-sky { background: #F0F9FF; color: #0369A1; }
            .aiv-tone-violet { background: #F5F3FF; color: #6D28D9; }
            .aiv-tone-amber { background: #FFFBEB; color: #B45309; }
            .aiv-tone-red { background: #FEF2F2; color: #B91C1C; }
            .aiv-actions { display: flex; align-items: center; justify-content: flex-end; gap: 6px; }
            .aiv-more { position: relative; }
            .aiv-menu { position: absolute; right: 0; top: calc(100% + 6px); min-width: 180px; background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 12px; box-shadow: 0 12px 28px rgba(15,23,42,.12); padding: 6px; z-index: 20; }
            .aiv-menu button, .aiv-menu a { display: block; width: 100%; text-align: left; padding: 9px 10px; border: 0; border-radius: 8px; background: transparent; font-size: 13px; font-weight: 600; color: #334155; cursor: pointer; text-decoration: none; }
            .aiv-menu button:hover, .aiv-menu a:hover { background: #F1F5F9; color: #0F172A; }
            .aiv-icon-btn { width: 36px; padding: 0; }
            .aiv-empty { padding: 36px 20px; text-align: center; color: #64748B; font-size: 13px; }
            .aiv-setting { display: flex; align-items: center; justify-content: space-between; gap: 14px; padding: 12px 0; }
            .aiv-setting + .aiv-setting { border-top: 1px solid #F1F5F9; }
            .aiv-setting strong { display: block; font-size: 13px; color: #0F172A; }
            .aiv-setting span { display: block; font-size: 12px; color: #64748B; margin-top: 2px; line-height: 1.4; }
            .aiv-switch { position: relative; width: 40px; height: 22px; flex-shrink: 0; }
            .aiv-switch input { position: absolute; opacity: 0; width: 100%; height: 100%; margin: 0; cursor: pointer; z-index: 1; }
            .aiv-switch i { position: absolute; inset: 0; border-radius: 9999px; background: #CBD5E1; transition: background .2s ease; }
            .aiv-switch i::after { content: ''; position: absolute; top: 3px; left: 3px; width: 16px; height: 16px; border-radius: 50%; background: #FFFFFF; transition: transform .2s ease; box-shadow: 0 1px 2px rgba(0,0,0,.15); }
            .aiv-switch input:checked + i { background: #4F48E6; }
            .aiv-switch input:checked + i::after { transform: translateX(18px); }
            .aiv-switch input:focus-visible + i { box-shadow: 0 0 0 3px rgba(79,72,230,.25); }
            .aiv-num { width: 80px; height: 36px; border: 1px solid #E2E8F0; border-radius: 9px; padding: 0 10px; font-size: 13px; text-align: right; flex-shrink: 0; }
            .aiv-log { list-style: none; margin: 0; padding: 0; }
            .aiv-log li { display: grid; grid-template-columns: 124px minmax(0, 1fr); gap: 12px; padding: 10px 0; align-items: center; }
            .aiv-log li + li { border-top: 1px solid #F1F5F9; }
            .aiv-log li .aiv-badge { justify-self: start; max-width: 100%; overflow: hidden; text-overflow: ellipsis; }
            .aiv-log-main { min-width: 0; font-size: 12.5px; color: #334155; line-height: 1.45; }
            .aiv-log-who { display: flex; align-items: baseline; gap: 6px; min-width: 0; }
            .aiv-log-who strong { color: #0F172A; }
            .aiv-log-who time { margin-left: auto; font-size: 11.5px; color: #94A3B8; white-space: nowrap; }
            .aiv-log-main a { color: #4F48E6; font-weight: 700; text-decoration: none; }
            .aiv-log-main small { display: block; font-size: 11.5px; color: #94A3B8; margin-top: 2px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
            .aiv-pager { display: flex; align-items: center; justify-content: space-between; gap: 10px; padding: 10px 18px 14px; border-top: 1px solid #F1F5F9; font-size: 12px; color: #64748B; font-weight: 600; }
            .aiv-pager-btns { display: flex; gap: 6px; }
            .aiv-pager .aiv-btn { height: 32px; width: 32px; }
            @media (max-width: 420px) {
                .aiv-log li { grid-template-columns: minmax(0, 1fr); gap: 6px; }
            }
            .aiv-modal { position: fixed; inset: 0; z-index: 3000; background: rgba(0, 6, 35, .55); display: flex; align-items: center; justify-content: center; padding: 20px; }
            .aiv-modal-card { background: #FFFFFF; border-radius: 18px; width: 100%; max-width: 460px; padding: 24px; box-shadow: 0 24px 60px rgba(0,0,0,.25); max-height: calc(100vh - 40px); overflow-y: auto; }
            .aiv-modal-card h3 { font-size: 17px; font-weight: 800; color: #000623; margin: 0 0 6px; }
            .aiv-modal-card p { font-size: 13px; color: #64748B; margin: 0 0 18px; line-height: 1.5; }
            .aiv-field { display: flex; flex-direction: column; gap: 6px; margin-bottom: 14px; }
            .aiv-field label { font-size: 12px; font-weight: 700; color: #334155; }
            .aiv-field select, .aiv-field input { height: 40px; border: 1px solid #E2E8F0; border-radius: 10px; padding: 0 12px; font-size: 13px; color: #0F172A; background: #FFFFFF; width: 100%; }
            .aiv-field .ic-sel-btn { height: 40px; border: 1px solid #E2E8F0; border-radius: 10px; padding: 0 12px; font-size: 13px; color: #0F172A; background: #FFFFFF; width: 100%; }
            .aiv-field .ic-sel-btn:focus { outline: none; border-color: #4F48E6; box-shadow: 0 0 0 3px rgba(79,72,230,.1); }
            .aiv-field select:focus, .aiv-field input:focus { outline: none; border-color: #4F48E6; box-shadow: 0 0 0 3px rgba(79,72,230,.1); }
            .aiv-row2 { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
            .aiv-modal-actions { display: flex; gap: 10px; justify-content: flex-end; margin-top: 6px; }
            .aiv-modal-actions .aiv-btn.is-confirm { background: #000623; border-color: #000623; color: #FFFFFF; }
            .aiv-modal-actions .aiv-btn.is-confirm:hover:not(:disabled) { background: #1E2A5A; border-color: #1E2A5A; color: #FFFFFF; }
            .aiv-error { margin: 0 0 12px; padding: 10px 12px; border-radius: 10px; background: #FEF2F2; color: #B91C1C; font-size: 12.5px; font-weight: 600; }
            @media (max-width: 1100px) { .aiv-side { grid-template-columns: minmax(0, 1fr); } }
            @media (max-width: 760px) {
                .aiv-kpis { grid-template-columns: repeat(2, minmax(0, 1fr)); }
                /* Sessions table becomes stacked cards on phones */
                .aiv-table thead { display: none; }
                .aiv-table, .aiv-table tbody, .aiv-table tr, .aiv-table td { display: block; width: 100%; }
                .aiv-table tr { padding: 12px 14px; border-top: 1px solid #F1F5F9; }
                .aiv-table td { border: 0; padding: 4px 0; display: flex; justify-content: space-between; gap: 12px; align-items: center; }
                .aiv-table td::before { content: attr(data-label); font-size: 11px; font-weight: 700; color: #94A3B8; text-transform: uppercase; letter-spacing: .05em; flex-shrink: 0; }
                .aiv-table td.aiv-td-main { display: block; }
                .aiv-table td.aiv-td-main::before { content: none; }
                .aiv-actions { justify-content: flex-start; padding-top: 6px; }
                .aiv-when { text-align: right; }
                .aiv-row2 { grid-template-columns: 1fr; }
            }
            </style>

            <div class="aiv" x-data="adminVideoInterviews(<?= htmlspecialchars(json_encode($aivPayload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE), ENT_QUOTES, 'UTF-8') ?>)">
                <div class="aiv-head">
                    <div>
                        <h1>Video Interviews</h1>
                        <p>Every interview session on InternConnect: who is live, how it went, and where people joined from. Schedule, mark ready, cancel or close any room.</p>
                    </div>
                    <div class="aiv-head-actions">
                        <?php if ($configured): ?>
                            <span class="aiv-status is-ok"><span class="dot" style="background:#10B981;"></span>Daily.co connected</span>
                        <?php else: ?>
                            <span class="aiv-status is-warn"><span class="dot" style="background:#F59E0B;"></span>Daily.co not configured</span>
                        <?php endif; ?>
                        <button type="button" class="aiv-btn is-primary" @click="openSchedule()">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                            <span>Schedule interview</span>
                        </button>
                    </div>
                </div>

                <?php if (!$configured): ?>
                <div class="aiv-setup">Calls cannot start until the Daily API key is added. Put it in <code>.env</code> as <code>DAILY_API_KEY</code> (local and cPanel). Steps are in <code>docs/47-video-interviews.md</code>.</div>
                <?php endif; ?>

                <div class="aiv-kpis">
                    <div class="aiv-kpi"><span>Live now</span><strong x-text="count('live')"></strong><small x-text="livePeople + ' people in calls'"></small></div>
                    <div class="aiv-kpi"><span>Upcoming</span><strong x-text="count('upcoming')"></strong><small>Scheduled or ready</small></div>
                    <div class="aiv-kpi"><span>Completed</span><strong x-text="count('completed')"></strong><small x-text="sessions.filter(s => s.recommendation).length + ' with a scorecard'"></small></div>
                    <div class="aiv-kpi"><span>Cancelled</span><strong x-text="count('cancelled')"></strong><small>By employer or admin</small></div>
                </div>

                <?php
                $liveMap = [
                    'feed'       => url('/admin/api/interviews/live-map'),
                    'title'      => 'Live interviews',
                    'subtitle'   => 'Where candidates and employers in live interviews are joining from (approximate, by IP)',
                    'totalLabel' => 'in interviews',
                    'labelA'     => 'candidates',
                    'labelB'     => 'employers',
                    'emptyText'  => 'No interviews are live right now.',
                ];
                include dirname(__DIR__, 2) . '/shared/components/admin-live-map.php';
                ?>

                <div class="aiv-grid">
                    <section class="aiv-card">
                        <div class="aiv-card-head">
                            <div>
                                <h2>Interview sessions</h2>
                                <p x-text="presenceNote"></p>
                            </div>
                            <button type="button" class="aiv-btn" @click="refreshPresence()" :disabled="loadingPresence || !cfg.configured" x-text="loadingPresence ? 'Refreshing...' : 'Refresh'"></button>
                        </div>
                        <div class="aiv-filters" role="tablist" aria-label="Filter sessions">
                            <template x-for="f in filters" :key="f.key">
                                <button type="button" role="tab" class="aiv-filter" :class="{ 'is-on': filter === f.key }" :aria-selected="filter === f.key" @click="filter = f.key; page = 0">
                                    <span x-text="f.label"></span><b x-text="count(f.key)"></b>
                                </button>
                            </template>
                        </div>
                        <div style="padding-top: 12px;" x-show="visible.length">
                            <table class="aiv-table">
                                <thead><tr><th>Interview</th><th>Employer</th><th>When</th><th>Status</th><th>In the room</th><th>Result</th><th></th></tr></thead>
                                <tbody>
                                    <template x-for="s in pageSessions" :key="s.id">
                                        <tr>
                                            <td class="aiv-td-main" data-label="Interview">
                                                <span class="aiv-name" x-text="'#' + s.id + ' · ' + s.candidate_name"></span>
                                                <span class="aiv-sub" x-text="s.candidate_role"></span>
                                            </td>
                                            <td data-label="Employer"><span class="aiv-nowrap" x-text="s.company_name"></span></td>
                                            <td data-label="When" class="aiv-nowrap"><span class="aiv-when"><span x-text="s.when"></span><span class="aiv-sub" x-text="s.when_time + ' · ' + s.duration_min + ' min'"></span></span></td>
                                            <td data-label="Status"><span class="aiv-badge" :class="'aiv-tone-' + statusTone(s)"><span class="dot"></span><span x-text="statusLabel(s)"></span></span></td>
                                            <td data-label="In the room"><span class="aiv-sub" style="white-space: normal; margin: 0;" x-text="peopleIn(s) || 'Nobody'"></span></td>
                                            <td data-label="Result"><span class="aiv-nowrap" x-text="s.recommendation ? s.recommendation + (s.score_average ? ' · ' + s.score_average + '/5' : '') : '-'"></span></td>
                                            <td data-label="">
                                                <div class="aiv-actions">
                                                    <a class="aiv-btn is-primary" x-show="!['Completed', 'Cancelled'].includes(s.status)" :href="url('room', s.id)" :aria-label="'Join the room for interview ' + s.id">Join</a>
                                                    <a class="aiv-btn" :href="url('detail', s.id)">View</a>
                                                    <div class="aiv-more" x-data="{ open: false }" @click.outside="open = false" @keydown.escape="open = false">
                                                        <button type="button" class="aiv-btn aiv-icon-btn" @click="open = !open" :aria-expanded="open" :aria-label="'More actions for interview ' + s.id">
                                                            <svg fill="currentColor" viewBox="0 0 24 24"><circle cx="5" cy="12" r="2"/><circle cx="12" cy="12" r="2"/><circle cx="19" cy="12" r="2"/></svg>
                                                        </button>
                                                        <div class="aiv-menu" x-show="open" style="display: none;" role="menu">
                                                            <button type="button" role="menuitem" x-show="['Scheduled', 'Confirmed'].includes(s.status)" @click="open = false; setStatus(s, 'Ready')">Mark as ready</button>
                                                            <button type="button" role="menuitem" x-show="s.room_status === 'open'" @click="open = false; confirmAction = { type: 'end', s }">End room now</button>
                                                            <button type="button" role="menuitem" x-show="s.room_status === 'ended' && s.status !== 'Cancelled'" @click="open = false; reopen(s)">Reopen room</button>
                                                            <button type="button" role="menuitem" x-show="!['Completed', 'Cancelled'].includes(s.status)" @click="open = false; confirmAction = { type: 'cancel', s }">Cancel interview</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                        <div class="aiv-pager" x-show="pages > 1">
                            <span x-text="(Math.min(page, pages - 1) * perPage + 1) + '-' + Math.min(visible.length, (Math.min(page, pages - 1) + 1) * perPage) + ' of ' + visible.length + ' sessions'"></span>
                            <div class="aiv-pager-btns">
                                <button type="button" class="aiv-btn aiv-icon-btn" @click="page = Math.max(0, page - 1)" :disabled="page === 0" aria-label="Previous sessions">
                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                                </button>
                                <button type="button" class="aiv-btn aiv-icon-btn" @click="page = Math.min(pages - 1, page + 1)" :disabled="page >= pages - 1" aria-label="Next sessions">
                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                                </button>
                            </div>
                        </div>
                        <div class="aiv-empty" x-show="!visible.length" x-text="sessions.length ? 'No sessions match this filter.' : 'No interviews yet. Employers schedule them from their Interviews page, or use Schedule interview above.'"></div>
                    </section>

                    <div class="aiv-side">
                        <section class="aiv-card">
                            <div class="aiv-card-head"><div><h2>Video settings</h2><p>Applies to every new join.</p></div></div>
                            <div class="aiv-card-body">
                                <div class="aiv-setting">
                                    <div><strong>Video interviews</strong><span>Turn off to stop anyone joining a call.</span></div>
                                    <label class="aiv-switch"><input type="checkbox" x-model="settings.video_enabled" aria-label="Video interviews enabled"><i></i></label>
                                </div>
                                <div class="aiv-setting">
                                    <div><strong>Screen sharing</strong><span>Lets candidates present portfolios.</span></div>
                                    <label class="aiv-switch"><input type="checkbox" x-model="settings.allow_screenshare" aria-label="Allow screen sharing"><i></i></label>
                                </div>
                                <div class="aiv-setting">
                                    <div><strong>In-call chat</strong><span>Text chat between participants.</span></div>
                                    <label class="aiv-switch"><input type="checkbox" x-model="settings.allow_chat" aria-label="Allow in-call chat"><i></i></label>
                                </div>
                                <div class="aiv-setting">
                                    <div><strong>File sharing</strong><span>PDFs, documents and images (no video).</span></div>
                                    <label class="aiv-switch"><input type="checkbox" x-model="settings.allow_files" aria-label="Allow file sharing"><i></i></label>
                                </div>
                                <div class="aiv-setting">
                                    <div><strong>Default length</strong><span>Minutes per interview (15-180).</span></div>
                                    <input type="number" class="aiv-num" min="15" max="180" step="5" x-model.number="settings.default_duration_min" aria-label="Default interview length in minutes">
                                </div>
                                <div class="aiv-setting">
                                    <div><strong>Wrap-up time</strong><span>Extra minutes after the end, with a warning (0-10).</span></div>
                                    <input type="number" class="aiv-num" min="0" max="10" step="1" x-model.number="settings.grace_min" aria-label="Wrap-up minutes">
                                </div>
                                <div class="aiv-setting">
                                    <div><strong>Candidate early join</strong><span>Minutes before the start a candidate can enter (0-60). Employers and admins can open the room any time.</span></div>
                                    <input type="number" class="aiv-num" min="0" max="60" step="1" x-model.number="settings.join_early_min" aria-label="Early join window in minutes">
                                </div>
                                <button type="button" class="aiv-btn is-primary is-block" @click="saveSettings()" :disabled="savingSettings" x-text="savingSettings ? 'Saving...' : 'Save settings'"></button>
                            </div>
                        </section>

                        <section class="aiv-card">
                            <div class="aiv-card-head"><div><h2>Recent activity</h2><p>Joins, files, screen shares and admin actions.</p></div></div>
                            <?php if ($eventRows): ?>
                            <div x-data="aivActivity(<?= htmlspecialchars(json_encode($eventRows, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE), ENT_QUOTES, 'UTF-8') ?>)">
                                <div class="aiv-filters no-scrollbar" role="tablist" aria-label="Filter activity">
                                    <template x-for="f in filters" :key="f.key">
                                        <button type="button" role="tab" class="aiv-filter" :class="{ 'is-on': cat === f.key }" :aria-selected="cat === f.key" @click="setCat(f.key)">
                                            <span x-text="f.label"></span><b x-text="count(f.key)"></b>
                                        </button>
                                    </template>
                                </div>
                                <div class="aiv-card-body">
                                    <ul class="aiv-log">
                                        <template x-for="(e, i) in pageRows" :key="cat + page + '-' + i">
                                            <li>
                                                <span class="aiv-badge" :class="'aiv-tone-' + e.tone" x-text="e.label"></span>
                                                <span class="aiv-log-main">
                                                    <span class="aiv-log-who">
                                                        <strong x-text="e.who"></strong>
                                                        <template x-if="e.id"><a :href="e.url" x-text="'#' + e.id"></a></template>
                                                        <time x-text="e.when"></time>
                                                    </span>
                                                    <small x-show="e.place || e.device" x-text="[e.place, e.device].filter(Boolean).join(' · ')"></small>
                                                </span>
                                            </li>
                                        </template>
                                    </ul>
                                    <p class="aiv-empty" style="padding: 12px 0;" x-show="!filtered.length">Nothing in this category yet.</p>
                                </div>
                                <div class="aiv-pager" x-show="pages > 1">
                                    <span x-text="(page * per + 1) + '-' + Math.min(filtered.length, (page + 1) * per) + ' of ' + filtered.length"></span>
                                    <div class="aiv-pager-btns">
                                        <button type="button" class="aiv-btn aiv-icon-btn" @click="page--" :disabled="page === 0" aria-label="Newer activity">
                                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                                        </button>
                                        <button type="button" class="aiv-btn aiv-icon-btn" @click="page++" :disabled="page >= pages - 1" aria-label="Older activity">
                                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <?php else: ?>
                            <div class="aiv-card-body"><p class="aiv-empty" style="padding: 12px 0;">No activity yet.</p></div>
                            <?php endif; ?>
                        </section>
                    </div>
                </div>

                <!-- Confirm end / cancel -->
                <div class="aiv-modal" x-show="confirmAction" x-cloak style="display: none;" @keydown.escape.window="confirmAction = null">
                    <div class="aiv-modal-card" role="dialog" aria-modal="true" @click.outside="confirmAction = null">
                        <h3 x-text="confirmAction && confirmAction.type === 'cancel' ? 'Cancel interview #' + confirmAction.s.id : 'End room for interview #' + (confirmAction ? confirmAction.s.id : '')"></h3>
                        <p x-text="confirmAction && confirmAction.type === 'cancel' ? 'The interview is marked as cancelled and its room is closed. Both people can see the change.' : 'Everyone in the call is disconnected immediately and nobody can rejoin until you reopen the room.'"></p>
                        <div class="aiv-modal-actions">
                            <button type="button" class="aiv-btn" @click="confirmAction = null">Keep it</button>
                            <button type="button" class="aiv-btn is-confirm" @click="runPendingAction()" :disabled="busy" x-text="busy ? 'Working...' : (confirmAction && confirmAction.type === 'cancel' ? 'Cancel interview' : 'End room')"></button>
                        </div>
                    </div>
                </div>

                <!-- Admin schedule -->
                <div class="aiv-modal" x-show="scheduleOpen" x-cloak style="display: none;" @keydown.escape.window="scheduleOpen = false">
                    <form class="aiv-modal-card" role="dialog" aria-modal="true" aria-labelledby="aiv-sched-title" @click.outside="scheduleOpen = false" @submit.prevent="schedule()">
                        <h3 id="aiv-sched-title">Schedule an interview</h3>
                        <p>Book a video interview between an employer and a candidate. Both are notified. The candidate can join <span x-text="settings.join_early_min"></span> minutes before the start; the employer and admins can open the room any time.</p>
                        <p class="aiv-error" x-show="scheduleError" x-text="scheduleError" role="alert" style="display: none;"></p>
                        <div class="aiv-field">
                            <label for="aiv-cand">Candidate</label>
                            <select data-ic-select id="aiv-cand" x-model.number="form.candidate_id" required>
                                <template x-for="p in cfg.people.candidates" :key="p.id"><option :value="p.id" x-text="p.name + ' · ' + p.role"></option></template>
                            </select>
                        </div>
                        <div class="aiv-field">
                            <label for="aiv-emp">Employer</label>
                            <select data-ic-select id="aiv-emp" x-model.number="form.employer_id" required>
                                <template x-for="p in cfg.people.employers" :key="p.id"><option :value="p.id" x-text="p.company + ' · ' + p.interviewer"></option></template>
                            </select>
                        </div>
                        <div class="aiv-row2">
                            <div class="aiv-field"><label for="aiv-date">Date</label><input id="aiv-date" type="date" x-model="form.date" :min="todayIso" required></div>
                            <div class="aiv-field"><label for="aiv-time">Time</label><input id="aiv-time" type="time" x-model="form.time" step="300" required></div>
                        </div>
                        <div class="aiv-field">
                            <label for="aiv-dur">Length (minutes)</label>
                            <input id="aiv-dur" type="number" min="15" max="180" step="5" x-model.number="form.duration_min" required>
                        </div>
                        <div class="aiv-modal-actions">
                            <button type="button" class="aiv-btn" @click="scheduleOpen = false">Close</button>
                            <button type="submit" class="aiv-btn is-primary" :disabled="busy" x-text="busy ? 'Scheduling...' : 'Schedule interview'"></button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</div>

<script>
// Recent activity: category chips + 6 rows per page so the card keeps a fixed, short height.
function aivActivity(rows) {
    return {
        rows, cat: 'all', page: 0, per: 6,
        filters: [
            { key: 'all', label: 'All' },
            { key: 'people', label: 'Joins' },
            { key: 'sharing', label: 'Screens & files' },
            { key: 'room', label: 'Room & admin' },
        ],
        count(key) { return key === 'all' ? this.rows.length : this.rows.filter((r) => r.cat === key).length; },
        get filtered() { return this.cat === 'all' ? this.rows : this.rows.filter((r) => r.cat === this.cat); },
        get pages() { return Math.max(1, Math.ceil(this.filtered.length / this.per)); },
        get pageRows() { return this.filtered.slice(this.page * this.per, (this.page + 1) * this.per); },
        setCat(key) { this.cat = key; this.page = 0; },
    };
}

function adminVideoInterviews(cfg) {
    const pad = (n) => String(n).padStart(2, '0');
    const today = new Date();
    return {
        cfg,
        settings: { ...cfg.settings },
        sessions: cfg.sessions,
        presence: {},
        loadingPresence: false,
        presenceAt: '',
        savingSettings: false,
        filter: 'all',
        page: 0, perPage: 10,
        filters: [
            { key: 'all', label: 'All' }, { key: 'live', label: 'Live' }, { key: 'upcoming', label: 'Upcoming' },
            { key: 'completed', label: 'Completed' }, { key: 'cancelled', label: 'Cancelled' },
        ],
        confirmAction: null,
        busy: false,
        scheduleOpen: false,
        scheduleError: '',
        todayIso: today.getFullYear() + '-' + pad(today.getMonth() + 1) + '-' + pad(today.getDate()),
        form: { candidate_id: 1, employer_id: 1, date: '', time: '10:00', duration_min: cfg.settings.default_duration_min },

        init() {
            if (cfg.configured) {
                this.refreshPresence();
                setInterval(() => { if (!document.hidden) this.refreshPresence(); }, 20000);
            }
        },

        url(key, id) { return cfg.urls[key].replace('__ID__', encodeURIComponent(id)); },
        toast(title, type) { if (typeof window.adminToast === 'function') window.adminToast(title, '', null, type || 'success'); },
        post(url, body) {
            return fetch(url, { method: 'POST', headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' }, body: JSON.stringify(body || {}) })
                .then((r) => r.json().then((j) => ({ ok: r.ok, json: j })).catch(() => ({ ok: false, json: {} })));
        },

        isLive(s) { return (this.presence[s.room_name] || []).length > 0 && s.room_status === 'open'; },
        bucket(s) {
            if (this.isLive(s)) return 'live';
            if (s.status === 'Cancelled') return 'cancelled';
            if (s.status === 'Completed') return 'completed';
            return 'upcoming';
        },
        count(key) { return key === 'all' ? this.sessions.length : this.sessions.filter((s) => this.bucket(s) === key).length; },
        get visible() { return this.filter === 'all' ? this.sessions : this.sessions.filter((s) => this.bucket(s) === this.filter); },
        // Sessions table shows 10 per page so a busy day does not stretch the page.
        get pages() { return Math.max(1, Math.ceil(this.visible.length / this.perPage)); },
        get pageSessions() { const p = Math.min(this.page, this.pages - 1); return this.visible.slice(p * this.perPage, (p + 1) * this.perPage); },
        get livePeople() { return this.sessions.reduce((n, s) => n + (this.presence[s.room_name] || []).length, 0); },
        statusTone(s) {
            if (this.isLive(s)) return 'green';
            return { Ready: 'green', Completed: 'slate', Cancelled: 'red', 'In progress': 'amber' }[s.status] || 'indigo';
        },
        statusLabel(s) {
            if (this.isLive(s)) return 'Live';
            return s.status === 'In progress' ? 'Started' : s.status;
        },
        peopleIn(s) { return (this.presence[s.room_name] || []).map((p) => p.name).join(', '); },
        get presenceNote() {
            if (!cfg.configured) return 'Live presence appears once Daily.co is configured.';
            return this.presenceAt ? 'Live participants updated at ' + this.presenceAt + ' (every 20 seconds).' : 'Loading live participants...';
        },

        async refreshPresence() {
            this.loadingPresence = true;
            try {
                const res = await fetch(cfg.urls.presence, { headers: { 'Accept': 'application/json' } });
                const json = await res.json();
                this.presence = json.presence || {};
                const d = new Date();
                this.presenceAt = pad(d.getHours()) + ':' + pad(d.getMinutes()) + ':' + pad(d.getSeconds());
            } catch (e) {
            } finally {
                this.loadingPresence = false;
            }
        },

        async saveSettings() {
            this.savingSettings = true;
            const { ok, json } = await this.post(cfg.urls.settings, this.settings);
            this.savingSettings = false;
            if (ok && json.success) { this.settings = { ...json.settings }; this.toast('Video settings saved'); }
            else this.toast('Could not save settings', 'error');
        },

        async setStatus(s, status) {
            const { ok, json } = await this.post(this.url('status', s.id), { status });
            if (ok && json.success) {
                s.status = status;
                if (status === 'Cancelled') s.room_status = s.room_status === 'none' ? 'none' : 'ended';
                this.toast('Interview #' + s.id + ' ' + (status === 'Ready' ? 'marked as ready' : 'cancelled'));
            } else this.toast('That change could not be saved', 'error');
        },

        async runPendingAction() {
            const a = this.confirmAction;
            if (!a || this.busy) return;
            this.busy = true;
            if (a.type === 'cancel') {
                await this.setStatus(a.s, 'Cancelled');
            } else {
                const { ok, json } = await this.post(this.url('end', a.s.id));
                if (ok && json.success) {
                    a.s.room_status = 'ended';
                    a.s.status = a.s.status === 'Cancelled' ? 'Cancelled' : 'Completed';
                    this.presence[a.s.room_name] = [];
                    this.toast('Room for interview #' + a.s.id + ' closed');
                } else this.toast('Could not end the room', 'error');
            }
            this.busy = false;
            this.confirmAction = null;
        },

        async reopen(s) {
            const { ok, json } = await this.post(this.url('reopen', s.id));
            if (ok && json.success) { s.room_status = 'open'; if (s.status === 'Completed') s.status = 'Ready'; this.toast('Room for interview #' + s.id + ' reopened'); }
            else this.toast('Could not reopen the room', 'error');
        },

        openSchedule() {
            const t = new Date(Date.now() + 86400000);
            this.form.date = t.getFullYear() + '-' + pad(t.getMonth() + 1) + '-' + pad(t.getDate());
            this.form.duration_min = this.settings.default_duration_min;
            this.scheduleError = '';
            this.scheduleOpen = true;
        },
        async schedule() {
            if (this.busy) return;
            const start = new Date(this.form.date + 'T' + this.form.time);
            if (isNaN(start.getTime()) || start.getTime() < Date.now() - 60000) { this.scheduleError = 'Choose a date and time in the future.'; return; }
            this.busy = true;
            this.scheduleError = '';
            const { ok, json } = await this.post(cfg.urls.schedule, this.form);
            this.busy = false;
            if (ok && json.success) {
                this.scheduleOpen = false;
                this.toast('Interview #' + json.id + ' scheduled');
                setTimeout(() => window.location.reload(), 700);
            } else {
                this.scheduleError = json.error || 'The interview could not be scheduled.';
            }
        }
    };
}
</script>
