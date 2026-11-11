<?php
declare(strict_types=1);
/**
 * Employer interview room drawer: Scorecard, Questions (+ Jenny AI follow-ups), Candidate profile.
 * Criteria, recommendation options and questions come from the employer's interview setup
 * (VideoInterviewService::getInterview()['setup']). Rendered inside video-room.php
 * (parent scope provides `drawerTab`).
 *
 * @var array $room Room details from VideoInterviewService::getRoomDetails()
 */

$scSetup = $room['setup'];
$scSaved = $room['scorecard'] ?? null;
$scPayload = [
    'interviewId'     => (int)$room['id'],
    'saveUrl'         => url('/employer/interviews/room/' . (int)$room['id'] . '/scorecard'),
    'suggestUrl'      => url('/employer/interviews/room/' . (int)$room['id'] . '/suggest'),
    'criteria'        => $scSetup['criteria'],
    'recommendations' => $scSetup['recommendations'],
    'questions'       => $scSetup['questions'],
    'saved'           => $scSaved,
];
?>
<style>
.sc-card { border: 1px solid #E2E8F0; border-radius: 12px; padding: 14px; display: flex; flex-direction: column; gap: 10px; }
.sc-label { font-size: 12px; font-weight: 800; color: #0F172A; }
.sc-hint { font-size: 11.5px; color: #64748B; line-height: 1.45; }
.sc-row { display: flex; align-items: center; justify-content: space-between; gap: 10px; }
.sc-row-label { font-size: 12.5px; font-weight: 600; color: #334155; min-width: 0; }
.sc-stars { display: flex; gap: 2px; flex-shrink: 0; }
.sc-star { width: 26px; height: 26px; border: 0; background: transparent; color: #CBD5E1; cursor: pointer; padding: 3px; border-radius: 6px; }
.sc-star:hover { background: #F8FAFC; }
.sc-star.is-on { color: #F59E0B; }
.sc-star svg { width: 100%; height: 100%; }
.sc-chips { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 8px; }
.sc-chip { min-height: 38px; padding: 6px 8px; border: 1px solid #E2E8F0; border-radius: 10px; background: #FFFFFF; font-size: 12.5px; font-weight: 700; color: #475569; cursor: pointer; line-height: 1.3; }
.sc-chip:hover { border-color: #CBD5E1; }
.sc-chip.is-on { border-color: #4F48E6; background: #EEF2FF; color: #4F48E6; }
.sc-chip.is-on.is-good { border-color: #10B981; background: #ECFDF5; color: #047857; }
.sc-chip.is-on.is-bad { border-color: #FCA5A5; background: #FEF2F2; color: #B91C1C; }
.sc-notes { width: 100%; min-height: 120px; resize: vertical; border: 1px solid #E2E8F0; border-radius: 10px; padding: 10px 12px; font-size: 13px; line-height: 1.5; color: #0F172A; outline: none; font-family: inherit; }
.sc-notes:focus { border-color: #4F48E6; box-shadow: 0 0 0 3px rgba(79,72,230,0.1); }
.sc-save-row { display: flex; align-items: center; justify-content: space-between; gap: 10px; flex-wrap: wrap; }
.sc-status { font-size: 11.5px; color: #64748B; display: inline-flex; align-items: center; gap: 6px; }
.sc-status .dot { width: 6px; height: 6px; border-radius: 50%; }
.sc-save { height: 40px; padding: 0 16px; border: 0; border-radius: 10px; background: #4F48E6; color: #FFFFFF; font-size: 13px; font-weight: 700; cursor: pointer; white-space: nowrap; }
.sc-save:hover:not(:disabled) { background: #4338CA; }
.sc-save:disabled { opacity: .5; cursor: not-allowed; }
.sc-q { display: flex; gap: 10px; width: 100%; padding: 12px; border: 1px solid #E2E8F0; border-radius: 12px; background: #FFFFFF; text-align: left; cursor: pointer; font: inherit; }
.sc-q:hover { border-color: #CBD5E1; }
.sc-q.is-done { background: #F8FAFC; }
.sc-q-check { width: 20px; height: 20px; border-radius: 6px; border: 1.5px solid #CBD5E1; display: flex; align-items: center; justify-content: center; flex-shrink: 0; color: #FFFFFF; margin-top: 1px; }
.sc-q.is-done .sc-q-check { background: #4F48E6; border-color: #4F48E6; }
.sc-q-check svg { width: 12px; height: 12px; }
.sc-q-text { font-size: 13px; font-weight: 600; color: #0F172A; line-height: 1.45; }
.sc-q.is-done .sc-q-text { color: #64748B; }
.sc-q-hint { font-size: 11.5px; color: #64748B; line-height: 1.45; margin-top: 4px; }
.sc-q-tag { display: inline-block; margin-top: 6px; padding: 1px 8px; border-radius: 9999px; background: #F5F3FF; color: #6D28D9; font-size: 10.5px; font-weight: 700; }
.sc-jenny { border: 1px solid #E0E7FF; border-radius: 12px; padding: 12px; background: #F8F9FF; display: flex; flex-direction: column; gap: 10px; }
.sc-jenny-head { display: flex; align-items: center; justify-content: space-between; gap: 8px; }
.sc-jenny-head strong { font-size: 12.5px; color: #0F172A; display: inline-flex; align-items: center; gap: 6px; }
.sc-jenny-head svg { width: 15px; height: 15px; color: #4F48E6; }
.sc-jenny-btn { height: 32px; padding: 0 12px; border: 1px solid #C7D2FE; border-radius: 8px; background: #FFFFFF; color: #4F48E6; font-size: 12px; font-weight: 700; cursor: pointer; white-space: nowrap; }
.sc-jenny-btn:hover:not(:disabled) { background: #EEF2FF; }
.sc-jenny-btn:disabled { opacity: .6; cursor: progress; }
.sc-suggest { padding: 10px; border-radius: 10px; background: #FFFFFF; border: 1px solid #E2E8F0; display: flex; gap: 10px; align-items: flex-start; }
.sc-suggest p { margin: 0; font-size: 12.5px; color: #0F172A; line-height: 1.45; font-weight: 600; }
.sc-suggest small { display: block; font-size: 11.5px; color: #64748B; margin-top: 3px; font-weight: 400; }
.sc-suggest button { flex-shrink: 0; height: 28px; padding: 0 10px; border: 0; border-radius: 7px; background: #4F48E6; color: #FFFFFF; font-size: 11.5px; font-weight: 700; cursor: pointer; }
.sc-profile { display: flex; align-items: center; gap: 12px; }
.sc-profile img { width: 48px; height: 48px; border-radius: 50%; object-fit: cover; flex-shrink: 0; }
.sc-profile h3 { font-size: 14px; font-weight: 800; color: #000623; margin: 0; }
.sc-profile p { font-size: 12px; color: #4F48E6; font-weight: 700; margin: 2px 0 0; }
.sc-facts { display: grid; gap: 8px; font-size: 12.5px; margin: 0; }
.sc-facts div { display: flex; justify-content: space-between; gap: 10px; }
.sc-facts dt { color: #64748B; }
.sc-facts dd { margin: 0; font-weight: 600; color: #0F172A; text-align: right; }
.sc-link { display: flex; align-items: center; justify-content: space-between; gap: 10px; padding: 10px 12px; border: 1px solid #E2E8F0; border-radius: 10px; text-decoration: none; color: #0F172A; font-size: 12.5px; font-weight: 600; }
.sc-link:hover { border-color: #C7D2FE; color: #4F48E6; }
.sc-link svg { width: 14px; height: 14px; color: #94A3B8; flex-shrink: 0; }
</style>

<div x-data="interviewScorecard(<?= htmlspecialchars(json_encode($scPayload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE), ENT_QUOTES, 'UTF-8') ?>)" style="display: contents;">

    <!-- Scorecard (criteria from the employer's interview setup) -->
    <div class="vr-panel" x-show="drawerTab === 'scorecard'">
        <div class="sc-card">
            <span class="sc-label">Your criteria</span>
            <template x-for="c in criteria" :key="c.key">
                <div class="sc-row">
                    <span class="sc-row-label" x-text="c.label"></span>
                    <div class="sc-stars" role="radiogroup" :aria-label="c.label">
                        <template x-for="n in 5" :key="n">
                            <button type="button" class="sc-star" role="radio" :class="{ 'is-on': n <= (scores[c.key] || 0) }" :aria-checked="scores[c.key] === n" :aria-label="c.label + ': ' + n + ' of 5'" @click="rate(c.key, n)">
                                <svg viewBox="0 0 20 20" fill="currentColor"><path d="M9.05 2.93c.3-.92 1.6-.92 1.9 0l1.07 3.29a1 1 0 00.95.69h3.46c.97 0 1.37 1.24.59 1.81l-2.8 2.03a1 1 0 00-.36 1.12l1.07 3.29c.3.92-.76 1.69-1.54 1.12l-2.8-2.03a1 1 0 00-1.18 0l-2.8 2.03c-.78.57-1.84-.2-1.54-1.12l1.07-3.29a1 1 0 00-.36-1.12L2.98 8.72c-.78-.57-.38-1.81.59-1.81h3.46a1 1 0 00.95-.69l1.07-3.29z"/></svg>
                            </button>
                        </template>
                    </div>
                </div>
            </template>
            <span class="sc-hint" x-text="'Average: ' + average"></span>
        </div>

        <div>
            <span class="sc-label" style="display: block; margin-bottom: 8px;">Recommendation</span>
            <div class="sc-chips" role="radiogroup" aria-label="Recommendation">
                <template x-for="r in recommendations" :key="r.key">
                    <button type="button" role="radio" class="sc-chip" :class="{ 'is-on': recommendation === r.key, 'is-good': r.tone === 'good', 'is-bad': r.tone === 'bad' }" :aria-checked="recommendation === r.key" @click="recommend(r.key)" x-text="r.label"></button>
                </template>
            </div>
        </div>

        <div>
            <label for="sc-notes" class="sc-label" style="display: block; margin-bottom: 8px;">Private notes</label>
            <textarea id="sc-notes" class="sc-notes" x-model="notes" @input.debounce.1200ms="autosave()" placeholder="Key observations. Only your hiring team can see these."></textarea>
        </div>

        <div class="sc-save-row">
            <span class="sc-status" aria-live="polite">
                <span class="dot" :style="{ background: status === 'saved' ? '#10B981' : (status === 'error' ? '#EF4444' : '#CBD5E1') }"></span>
                <span x-text="statusLabel"></span>
            </span>
            <button type="button" class="sc-save" @click="save(true)" :disabled="saving" x-text="saving ? 'Saving...' : 'Save scorecard'"></button>
        </div>
    </div>

    <!-- Questions (employer's prepared list + Jenny AI follow-ups) -->
    <div class="vr-panel" x-show="drawerTab === 'questions'">
        <span class="sc-hint" x-text="askedCount + ' of ' + questions.length + ' asked. Tap a question to tick it off.'"></span>
        <template x-for="q in questions" :key="q.id">
            <button type="button" class="sc-q" :class="{ 'is-done': q.asked }" role="checkbox" :aria-checked="q.asked" @click="toggleQuestion(q)">
                <span class="sc-q-check"><svg x-show="q.asked" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3.2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg></span>
                <span>
                    <span class="sc-q-text" style="display: block;" x-text="q.question"></span>
                    <span class="sc-q-hint" style="display: block;" x-show="q.hint" x-text="'Listen for: ' + q.hint"></span>
                    <span class="sc-q-tag" x-show="q.fromJenny">Suggested by Jenny</span>
                </span>
            </button>
        </template>

        <div class="sc-jenny">
            <div class="sc-jenny-head">
                <strong>
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                    Jenny AI follow-ups
                </strong>
                <button type="button" class="sc-jenny-btn" @click="suggest()" :disabled="suggesting" x-text="suggesting ? 'Thinking...' : (suggestions.length ? 'Refresh' : 'Suggest')"></button>
            </div>
            <span class="sc-hint" x-show="!suggestions.length && !suggesting">Jenny reads your notes and the questions you have asked, then suggests what to probe next.</span>
            <template x-for="(s, i) in suggestions" :key="i">
                <div class="sc-suggest">
                    <div style="flex: 1; min-width: 0;">
                        <p x-text="s.question"></p>
                        <small x-show="s.why" x-text="s.why"></small>
                    </div>
                    <button type="button" @click="addSuggestion(i)">Add</button>
                </div>
            </template>
        </div>
    </div>

    <!-- Candidate profile -->
    <div class="vr-panel" x-show="drawerTab === 'profile'">
        <div class="sc-card">
            <div class="sc-profile">
                <img src="<?= htmlspecialchars($room['candidate_avatar']) ?>" alt="">
                <div>
                    <h3><?= htmlspecialchars($room['candidate_name']) ?></h3>
                    <p><?= htmlspecialchars($room['candidate_role']) ?></p>
                </div>
            </div>
            <dl class="sc-facts">
                <div><dt>Institution</dt><dd><?= htmlspecialchars($room['candidate_school']) ?></dd></div>
                <div><dt>Academic standing</dt><dd><?= htmlspecialchars($room['candidate_gpa']) ?></dd></div>
                <div><dt>Placement</dt><dd><?= htmlspecialchars($room['candidate_duration']) ?></dd></div>
            </dl>
        </div>
        <span class="sc-label">Work and portfolio</span>
        <?php foreach ($room['portfolio_links'] as $link): ?>
        <a class="sc-link" href="<?= htmlspecialchars($link['url']) ?>" target="_blank" rel="noopener noreferrer">
            <span><?= htmlspecialchars($link['label']) ?></span>
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
        </a>
        <?php endforeach; ?>
        <a class="sc-link" href="<?= url('/employer/candidates/' . (int)$room['candidate_id']) ?>" target="_blank" rel="noopener">
            <span>Open full candidate profile</span>
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
        </a>
    </div>
</div>

<?php if (empty($GLOBALS['__scScript'])): $GLOBALS['__scScript'] = true; ?>
<script>
function interviewScorecard(cfg) {
    const draftKey = 'icInterviewNotesDraft_' + cfg.interviewId;
    const saved = cfg.saved || {};
    const asked = new Set((saved.asked || []).map(Number));
    const scores = {};
    cfg.criteria.forEach((c) => { scores[c.key] = Number((saved.scores || {})[c.key] || 0); });
    let notes = saved.notes || '';
    try { if (!notes) notes = localStorage.getItem(draftKey) || ''; } catch (e) {}

    return {
        criteria: cfg.criteria,
        recommendations: cfg.recommendations,
        questions: cfg.questions.map((q) => ({ ...q, asked: asked.has(Number(q.id)), fromJenny: false })),
        scores,
        recommendation: saved.recommendation || '',
        notes,
        saving: false,
        status: saved.updated_at ? 'saved' : 'idle',
        savedAt: saved.updated_at ? String(saved.updated_at).slice(11, 16) : '',
        suggesting: false,
        suggestions: [],

        get askedCount() { return this.questions.filter((q) => q.asked).length; },
        get average() {
            const vals = Object.values(this.scores).filter(Boolean);
            return vals.length ? (vals.reduce((a, b) => a + b, 0) / vals.length).toFixed(1) + ' / 5' : 'not rated yet';
        },
        get statusLabel() {
            if (this.saving) return 'Saving...';
            if (this.status === 'saved') return this.savedAt ? 'Saved at ' + this.savedAt : 'Saved';
            if (this.status === 'error') return 'Not saved. Check your connection.';
            if (this.status === 'dirty') return 'Unsaved changes';
            return 'Changes save automatically';
        },

        rate(key, n) { this.scores[key] = this.scores[key] === n ? 0 : n; this.autosave(); },
        recommend(key) { this.recommendation = this.recommendation === key ? '' : key; this.autosave(); },
        toggleQuestion(q) { q.asked = !q.asked; this.autosave(); },

        autosave() {
            this.status = 'dirty';
            try { localStorage.setItem(draftKey, this.notes || ''); } catch (e) {}
            clearTimeout(this._t);
            this._t = setTimeout(() => this.save(false), 600);
        },

        async save(manual) {
            if (this.saving) return;
            this.saving = true;
            try {
                const res = await fetch(cfg.saveUrl, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify({
                        scores: this.scores,
                        recommendation: this.recommendation,
                        notes: this.notes,
                        asked: this.questions.filter((q) => q.asked && !q.fromJenny).map((q) => q.id),
                    })
                });
                if (!res.ok) throw new Error('save failed');
                this.status = 'saved';
                const d = new Date();
                this.savedAt = String(d.getHours()).padStart(2, '0') + ':' + String(d.getMinutes()).padStart(2, '0');
                try { localStorage.removeItem(draftKey); } catch (e) {}
                if (manual) window.dispatchEvent(new CustomEvent('show-toast', { detail: { text: 'Scorecard saved.', type: 'success' } }));
            } catch (e) {
                this.status = 'error';
            } finally {
                this.saving = false;
            }
        },

        async suggest() {
            if (this.suggesting) return;
            this.suggesting = true;
            try {
                const res = await fetch(cfg.suggestUrl, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify({ asked: this.questions.filter((q) => q.asked && !q.fromJenny).map((q) => q.id), notes: this.notes })
                });
                const json = await res.json();
                this.suggestions = (json.questions || []).slice(0, 3);
                if (!this.suggestions.length) throw new Error('empty');
            } catch (e) {
                window.dispatchEvent(new CustomEvent('show-toast', { detail: { text: 'Jenny could not suggest questions right now.', type: 'error' } }));
            } finally {
                this.suggesting = false;
            }
        },

        addSuggestion(i) {
            const s = this.suggestions[i];
            if (!s) return;
            this.questions.push({ id: 1000 + this.questions.length, question: s.question, hint: s.why || '', asked: false, fromJenny: true });
            this.suggestions.splice(i, 1);
        }
    };
}
</script>
<?php endif; ?>
