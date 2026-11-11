<?php
declare(strict_types=1);
/**
 * Shared interview report (history) body.
 * Used by: employer/interviews/report.php, candidate/interview/report.php, admin/interviews/show.php
 * Spec: docs/47-video-interviews.md (Interview history)
 *
 * Audience rules (enforced in VideoInterviewService::report()):
 * - employer: everything, including private notes, scorecard, AI summary, chat.
 * - candidate: timeline, files, chat. No scorecard or AI assessment.
 * - admin: timeline (with locations), scores, decision, AI summary. No chat content or private notes.
 *
 * @var array|null $report
 * @var string     $audience  employer | candidate | admin
 * @var string     $fileBase  URL prefix for file downloads (+ {fileId})
 * @var string     $aiUrl     POST endpoint to (re)generate the AI summary ('' to hide)
 */

use App\Services\VideoInterviewService;

$rp = $report;
$isCandidate = $audience === 'candidate';
$isAdmin = $audience === 'admin';
$toneClass = static fn (string $tone): string => 'irp-tone-' . $tone;
$fmt = static fn (string $ts): string => date('M j, g:i A', strtotime($ts));
?>
<style>
.irp { display: flex; flex-direction: column; gap: 20px; }
.irp * { box-sizing: border-box; }
.irp-head { display: flex; align-items: flex-start; justify-content: space-between; gap: 14px 20px; flex-wrap: wrap; }
.irp-head h1 { font-size: 24px; font-weight: 800; color: #000623; margin: 0 0 4px; letter-spacing: -0.02em; line-height: 1.25; }
.irp-head p { font-size: 13.5px; color: #64748B; margin: 0; line-height: 1.5; }
.irp-head-actions { display: flex; gap: 8px; flex-wrap: wrap; }
.irp-btn { display: inline-flex; align-items: center; justify-content: center; gap: 7px; height: 38px; padding: 0 14px; border-radius: 10px; border: 1px solid #E2E8F0; background: #FFFFFF; color: #334155; font-size: 13px; font-weight: 600; text-decoration: none; cursor: pointer; white-space: nowrap; }
.irp-btn:hover:not(:disabled) { background: #F8FAFC; border-color: #CBD5E1; }
.irp-btn:disabled { opacity: .6; cursor: progress; }
.irp-btn.is-primary { background: #4F48E6; border-color: #4F48E6; color: #FFFFFF; }
.irp-btn.is-primary:hover:not(:disabled) { background: #4338CA; }
.irp-btn svg { width: 15px; height: 15px; }
.irp-badge { display: inline-flex; align-items: center; gap: 6px; padding: 3px 10px; border-radius: 9999px; font-size: 11.5px; font-weight: 700; white-space: nowrap; }
.irp-tone-green { background: #ECFDF5; color: #047857; }
.irp-tone-slate { background: #F1F5F9; color: #475569; }
.irp-tone-indigo { background: #EEF2FF; color: #4F48E6; }
.irp-tone-sky { background: #F0F9FF; color: #0369A1; }
.irp-tone-violet { background: #F5F3FF; color: #6D28D9; }
.irp-tone-amber { background: #FFFBEB; color: #B45309; }
.irp-tone-red { background: #FEF2F2; color: #B91C1C; }
.irp-kpis { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 14px; }
.irp-kpi { background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 14px; padding: 16px 18px; min-width: 0; }
.irp-kpi span { display: block; font-size: 11.5px; font-weight: 700; color: #64748B; text-transform: uppercase; letter-spacing: .05em; }
.irp-kpi strong { display: block; font-size: 22px; font-weight: 800; color: #000623; margin-top: 6px; line-height: 1.1; }
.irp-kpi small { display: block; font-size: 12px; color: #94A3B8; margin-top: 4px; }
.irp-grid { display: grid; grid-template-columns: minmax(0, 1.4fr) minmax(0, 1fr); gap: 20px; align-items: start; }
.irp-col { display: flex; flex-direction: column; gap: 20px; min-width: 0; }
.irp-card { background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 16px; min-width: 0; }
.irp-card-head { display: flex; align-items: center; justify-content: space-between; gap: 10px; padding: 16px 18px; border-bottom: 1px solid #F1F5F9; flex-wrap: wrap; }
.irp-card-head h2 { font-size: 15px; font-weight: 800; color: #000623; margin: 0; }
.irp-card-head p { font-size: 12.5px; color: #64748B; margin: 2px 0 0; }
.irp-card-body { padding: 16px 18px; }
.irp-overview { font-size: 14px; line-height: 1.6; color: #1E293B; margin: 0; }
.irp-list-title { font-size: 12px; font-weight: 800; color: #0F172A; margin: 14px 0 6px; }
.irp-bullets { margin: 0; padding-left: 18px; list-style: disc; font-size: 13px; color: #334155; line-height: 1.55; }
.irp-ai-meta { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; margin-top: 14px; padding-top: 12px; border-top: 1px solid #F1F5F9; font-size: 12px; color: #64748B; }
.irp-stars { display: inline-flex; gap: 2px; color: #CBD5E1; }
.irp-stars svg { width: 14px; height: 14px; }
.irp-stars .on { color: #F59E0B; }
.irp-score-row { display: grid; grid-template-columns: minmax(0, 1fr) 120px 34px; align-items: center; gap: 12px; padding: 8px 0; font-size: 13px; color: #334155; }
.irp-bar { height: 6px; border-radius: 9999px; background: #EEF0F4; overflow: hidden; }
.irp-bar span { display: block; height: 100%; border-radius: 9999px; background: #4F48E6; }
.irp-notes { margin: 12px 0 0; padding: 12px; border-radius: 10px; background: #F8FAFC; font-size: 13px; color: #334155; line-height: 1.55; white-space: pre-wrap; }
.irp-q { display: flex; gap: 10px; padding: 8px 0; font-size: 13px; color: #1E293B; line-height: 1.45; }
.irp-q svg { width: 16px; height: 16px; color: #10B981; flex-shrink: 0; margin-top: 1px; }
.irp-timeline { list-style: none; margin: 0; padding: 0; }
.irp-timeline li { display: grid; grid-template-columns: auto minmax(0, 1fr); gap: 10px; padding: 10px 0; }
.irp-timeline li + li { border-top: 1px solid #F4F5F8; }
.irp-tl-main { min-width: 0; font-size: 13px; color: #1E293B; line-height: 1.45; }
.irp-tl-main small { display: block; font-size: 11.5px; color: #64748B; margin-top: 2px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.irp-file { display: flex; align-items: center; gap: 10px; padding: 10px 0; text-decoration: none; color: #0F172A; }
.irp-file + .irp-file { border-top: 1px solid #F4F5F8; }
.irp-file-ext { width: 36px; height: 36px; border-radius: 9px; background: #EEF2FF; color: #4F48E6; font-size: 10px; font-weight: 800; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.irp-file-body { min-width: 0; flex: 1; }
.irp-file-body strong { display: block; font-size: 13px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
.irp-file-body small { font-size: 11.5px; color: #64748B; }
.irp-file:hover strong { color: #4F48E6; }
.irp-chat { display: flex; flex-direction: column; gap: 8px; max-height: 360px; overflow-y: auto; }
.irp-msg { max-width: 88%; padding: 8px 12px; border-radius: 12px; background: #F1F5F9; font-size: 13px; color: #0F172A; line-height: 1.45; word-break: break-word; }
.irp-msg.is-mine { align-self: flex-end; background: #EEF2FF; }
.irp-msg small { display: block; font-size: 10.5px; color: #64748B; margin-bottom: 2px; }
.irp-empty { font-size: 13px; color: #64748B; margin: 0; }
.irp-missing { background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 16px; padding: 48px 20px; text-align: center; color: #64748B; }
@media (max-width: 1100px) { .irp-grid { grid-template-columns: minmax(0, 1fr); } }
@media (max-width: 760px) { .irp-kpis { grid-template-columns: repeat(2, minmax(0, 1fr)); } .irp-score-row { grid-template-columns: minmax(0, 1fr) 80px 30px; } }
</style>

<?php if (!$rp): ?>
<div class="irp-missing">
    <h1 style="font-size: 18px; color: #0F172A; margin: 0 0 6px;">Interview not found</h1>
    <p style="margin: 0;">This interview does not exist or was removed.</p>
</div>
<?php else:
    $i = $rp['interview'];
    $statusTone = ['Completed' => 'slate', 'Cancelled' => 'red', 'In progress' => 'green'][$i['status']] ?? 'indigo';
    $when = $i['scheduled_at'] ? date('D, M j, Y \a\t g:i A', $i['scheduled_at']) : trim($i['date'] . ' ' . $i['time']);
    $ai = $rp['ai'] ?? null;
    $recLabels = array_column($i['setup']['recommendations'], 'label', 'key');
?>
<div class="irp" x-data="{
    ai: <?= htmlspecialchars(json_encode($ai, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE), ENT_QUOTES, 'UTF-8') ?>,
    recLabels: <?= htmlspecialchars(json_encode($recLabels, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE), ENT_QUOTES, 'UTF-8') ?>,
    generating: false,
    async generate() {
        if (this.generating) return;
        this.generating = true;
        try {
            const res = await fetch(<?= htmlspecialchars(json_encode($aiUrl), ENT_QUOTES, 'UTF-8') ?>, { method: 'POST', headers: { 'Accept': 'application/json' } });
            const json = await res.json();
            if (!json.success) throw new Error('failed');
            this.ai = json.summary;
            window.dispatchEvent(new CustomEvent('show-toast', { detail: { text: 'Interview summary updated.', type: 'success' } }));
            if (typeof window.adminToast === 'function') window.adminToast('Interview summary updated', '', null, 'success');
        } catch (e) {
            window.dispatchEvent(new CustomEvent('show-toast', { detail: { text: 'The summary could not be generated. Please try again.', type: 'error' } }));
            if (typeof window.adminToast === 'function') window.adminToast('The summary could not be generated', '', null, 'error');
        } finally {
            this.generating = false;
        }
    }
}">
    <div class="irp-head">
        <div>
            <h1><?= htmlspecialchars($isCandidate ? 'Interview with ' . $i['company_name'] : 'Interview with ' . $i['candidate_name']) ?></h1>
            <p>
                <?= htmlspecialchars($i['candidate_role']) ?> &middot; <?= htmlspecialchars($isCandidate ? $i['interviewer_name'] : $i['company_name']) ?> &middot; <?= htmlspecialchars($when) ?> &middot; <?= (int)$i['duration_min'] ?> min
                &nbsp;<span class="irp-badge <?= $toneClass($statusTone) ?>"><?= htmlspecialchars($i['status'] === 'In progress' ? 'Live now' : $i['status']) ?></span>
            </p>
        </div>
        <div class="irp-head-actions">
            <?php if (!empty($backUrl)): ?>
                <a class="irp-btn" href="<?= htmlspecialchars($backUrl) ?>"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg><span>Back</span></a>
            <?php endif; ?>
            <?php if (!empty($roomUrl) && in_array($i['status'], ['Scheduled', 'Confirmed', 'Ready', 'In progress'], true)): ?>
                <a class="irp-btn is-primary" href="<?= htmlspecialchars($roomUrl) ?>">Open video room</a>
            <?php endif; ?>
        </div>
    </div>

    <div class="irp-kpis">
        <div class="irp-kpi"><span>Time in call</span><strong><?= (int)max($rp['attendance']['employer'], $rp['attendance']['candidate']) ?> min</strong><small>Employer <?= (int)$rp['attendance']['employer'] ?> · Candidate <?= (int)$rp['attendance']['candidate'] ?> min</small></div>
        <div class="irp-kpi"><span>Questions asked</span><strong><?= count($rp['asked']) ?> / <?= (int)$rp['question_total'] ?></strong><small>From the prepared list</small></div>
        <div class="irp-kpi"><span>Files shared</span><strong><?= count($rp['files']) ?></strong><small><?= (int)$rp['screen_shares'] ?> screen share<?= $rp['screen_shares'] === 1 ? '' : 's' ?></small></div>
        <?php if (!$isCandidate): ?>
            <div class="irp-kpi"><span>Average score</span><strong><?= $rp['score_average'] !== null ? htmlspecialchars((string)$rp['score_average']) . ' / 5' : '-' ?></strong><small><?= htmlspecialchars($rp['recommendation_label'] ?: 'No decision yet') ?></small></div>
        <?php else: ?>
            <div class="irp-kpi"><span>Messages</span><strong><?= (int)$rp['chat_count'] ?></strong><small>In-call chat</small></div>
        <?php endif; ?>
    </div>

    <div class="irp-grid">
        <div class="irp-col">
            <?php if (!$isCandidate): ?>
            <!-- Jenny AI summary -->
            <section class="irp-card">
                <div class="irp-card-head">
                    <div>
                        <h2>Jenny AI summary</h2>
                        <p>A brief of what happened. It suggests, your team decides.</p>
                    </div>
                    <?php if ($aiUrl !== ''): ?>
                    <button type="button" class="irp-btn" @click="generate()" :disabled="generating">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                        <span x-text="generating ? 'Writing summary...' : (ai ? 'Refresh summary' : 'Generate summary')"></span>
                    </button>
                    <?php endif; ?>
                </div>
                <div class="irp-card-body">
                    <p class="irp-empty" x-show="!ai">No summary yet. Generate one after the interview to get a short brief and a suggested rating.</p>
                    <template x-if="ai">
                        <div>
                            <p class="irp-overview" x-text="ai.overview"></p>
                            <template x-if="ai.highlights && ai.highlights.length">
                                <div><p class="irp-list-title">Highlights</p><ul class="irp-bullets"><template x-for="(h, n) in ai.highlights" :key="n"><li x-text="h"></li></template></ul></div>
                            </template>
                            <template x-if="ai.concerns && ai.concerns.length">
                                <div><p class="irp-list-title">Worth a closer look</p><ul class="irp-bullets"><template x-for="(h, n) in ai.concerns" :key="n"><li x-text="h"></li></template></ul></div>
                            </template>
                            <div class="irp-ai-meta">
                                <span>Suggested rating</span>
                                <span class="irp-stars" :aria-label="ai.suggested_rating + ' of 5'">
                                    <template x-for="n in 5" :key="n"><svg :class="{ on: n <= ai.suggested_rating }" viewBox="0 0 20 20" fill="currentColor"><path d="M9.05 2.93c.3-.92 1.6-.92 1.9 0l1.07 3.29a1 1 0 00.95.69h3.46c.97 0 1.37 1.24.59 1.81l-2.8 2.03a1 1 0 00-.36 1.12l1.07 3.29c.3.92-.76 1.69-1.54 1.12l-2.8-2.03a1 1 0 00-1.18 0l-2.8 2.03c-.78.57-1.84-.2-1.54-1.12l1.07-3.29a1 1 0 00-.36-1.12L2.98 8.72c-.78-.57-.38-1.81.59-1.81h3.46a1 1 0 00.95-.69l1.07-3.29z"/></svg></template>
                                </span>
                                <span class="irp-badge irp-tone-indigo" x-show="ai.suggested_recommendation" x-text="'Suggests: ' + (recLabels[ai.suggested_recommendation] || ai.suggested_recommendation)"></span>
                                <span class="irp-badge irp-tone-slate" x-text="'Confidence: ' + ai.confidence"></span>
                                <span x-text="ai.source === 'gemini' ? 'Written by Jenny AI' : 'Built from interview data (AI was unavailable)'"></span>
                            </div>
                        </div>
                    </template>
                </div>
            </section>

            <!-- Scorecard -->
            <section class="irp-card">
                <div class="irp-card-head"><div><h2>Scorecard</h2><p>Ratings against the criteria set for this interview.</p></div>
                    <?php if ($rp['recommendation_label']): ?><span class="irp-badge irp-tone-indigo"><?= htmlspecialchars($rp['recommendation_label']) ?></span><?php endif; ?>
                </div>
                <div class="irp-card-body">
                    <?php foreach ($i['setup']['criteria'] as $cr):
                        $s = (int)($rp['scorecard']['scores'][$cr['key']] ?? 0); ?>
                        <div class="irp-score-row">
                            <span><?= htmlspecialchars($cr['label']) ?></span>
                            <span class="irp-bar"><span style="width: <?= $s * 20 ?>%;"></span></span>
                            <strong style="text-align: right;"><?= $s ?: '-' ?></strong>
                        </div>
                    <?php endforeach; ?>
                    <?php if (!$isAdmin && !empty($rp['scorecard']['notes'])): ?>
                        <p class="irp-list-title">Private notes</p>
                        <p class="irp-notes"><?= htmlspecialchars($rp['scorecard']['notes']) ?></p>
                    <?php endif; ?>
                    <?php if (!$rp['scorecard']): ?><p class="irp-empty">No scorecard was saved for this interview.</p><?php endif; ?>
                </div>
            </section>
            <?php endif; ?>

            <!-- Questions asked -->
            <section class="irp-card">
                <div class="irp-card-head"><div><h2>Questions covered</h2><p><?= count($rp['asked']) ?> of <?= (int)$rp['question_total'] ?> prepared questions were marked as asked.</p></div></div>
                <div class="irp-card-body">
                    <?php if ($rp['asked']): foreach ($rp['asked'] as $q): ?>
                        <div class="irp-q"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.4"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg><span><?= htmlspecialchars($q['question']) ?></span></div>
                    <?php endforeach; else: ?>
                        <p class="irp-empty">No questions were marked as asked.</p>
                    <?php endif; ?>
                </div>
            </section>

            <?php if (!$isAdmin): ?>
            <!-- Chat -->
            <section class="irp-card">
                <div class="irp-card-head"><div><h2>Chat</h2><p>Messages sent during the call.</p></div></div>
                <div class="irp-card-body">
                    <?php if (!empty($rp['chat'])): ?>
                        <div class="irp-chat">
                            <?php foreach ($rp['chat'] as $m): ?>
                                <div class="irp-msg <?= $m['role'] === $audience ? 'is-mine' : '' ?>">
                                    <small><?= htmlspecialchars($m['role'] === $audience ? 'You' : $m['sender']) ?> · <?= htmlspecialchars(date('g:i A', strtotime($m['created_at']))) ?></small>
                                    <?= htmlspecialchars($m['body']) ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p class="irp-empty">No messages were sent.</p>
                    <?php endif; ?>
                </div>
            </section>
            <?php endif; ?>
        </div>

        <div class="irp-col">
            <!-- Files -->
            <section class="irp-card">
                <div class="irp-card-head"><div><h2>Shared files</h2><p>Documents and images shared in the call.</p></div></div>
                <div class="irp-card-body">
                    <?php if ($rp['files']): foreach ($rp['files'] as $f): ?>
                        <a class="irp-file" href="<?= htmlspecialchars($fileBase . $f['id']) ?>" target="_blank" rel="noopener">
                            <span class="irp-file-ext"><?= htmlspecialchars(strtoupper($f['ext'])) ?></span>
                            <span class="irp-file-body"><strong><?= htmlspecialchars($f['name']) ?></strong><small><?= htmlspecialchars($f['size_label']) ?> · <?= htmlspecialchars(ucfirst($f['role'])) ?> · <?= htmlspecialchars($fmt($f['created_at'])) ?></small></span>
                        </a>
                    <?php endforeach; else: ?>
                        <p class="irp-empty">No files were shared.</p>
                    <?php endif; ?>
                </div>
            </section>

            <!-- Timeline -->
            <section class="irp-card">
                <div class="irp-card-head"><div><h2>Activity</h2><p>What happened, in order<?= $isAdmin ? ', with approximate location' : '' ?>.</p></div></div>
                <div class="irp-card-body">
                    <?php if ($rp['timeline']): ?>
                        <ul class="irp-timeline">
                            <?php foreach ($rp['timeline'] as $e):
                                [$label, $tone] = VideoInterviewService::eventMeta($e['event']); ?>
                                <li>
                                    <span class="irp-badge <?= $toneClass($tone) ?>"><?= htmlspecialchars($label) ?></span>
                                    <span class="irp-tl-main">
                                        <strong><?= htmlspecialchars(ucfirst($e['role'])) ?></strong><?= $e['event'] === 'file_shared' && $e['detail'] ? ' · ' . htmlspecialchars($e['detail']) : '' ?>
                                        <small><?= htmlspecialchars($fmt($e['at'])) ?><?= $isAdmin && $e['place'] ? ' · ' . htmlspecialchars($e['place']) : '' ?><?= $isAdmin && $e['device'] ? ' · ' . htmlspecialchars($e['device']) : '' ?></small>
                                    </span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <p class="irp-empty">No activity recorded yet.</p>
                    <?php endif; ?>
                </div>
            </section>
        </div>
    </div>
</div>
<?php endif; ?>
