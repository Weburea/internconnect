<?php
declare(strict_types=1);
/**
 * View: AI Career Coach & Skill Roadmap (Version 2.0 / Phase 5 AI Feature)
 * Route: /candidate/ai-assistant/career-coach
 * Layout: dashboard
 * 
 * Complies with docs/01-roadmap.md (Phase 5 Enterprise Features)
 * and .agents/rules/agent_mistakes_preventions.md.
 * 
 * @var array  $candidate    Candidate profile
 * @var array  $disciplines  Supported career disciplines
 * @var string $selectedRole Active discipline
 * @var array  $roadmap      Roadmap & diagnostic data
 */

$candName     = $candidate['name'] ?? 'Sarah Peters';
$candSchool   = $candidate['school'] ?? 'University of Lagos (UNILAG)';
$candRole     = $candidate['role'] ?? 'UI/UX Design Intern';
$readinessPct = $roadmap['readiness_score'] ?? 84;
?>

<style>
/* ==========================================================================
   AI CAREER COACH & SKILL ROADMAP — DESIGN SYSTEM STYLES
   ========================================================================== */

.cc-container { padding: 0 0 40px; max-width: 100%; box-sizing: border-box; }
.cc-container *, .cc-container *::before, .cc-container *::after { box-sizing: border-box; }

/* Page Header */
.cc-header { display: flex; align-items: flex-end; justify-content: space-between; gap: 16px 24px; margin-bottom: 20px; flex-wrap: wrap; }
.cc-header-text { min-width: 0; flex: 1 1 360px; }
.cc-back-link { display: inline-flex !important; align-items: center !important; gap: 6px !important; font-size: 13px; font-weight: 700; color: #4F48E6; text-decoration: none; margin-bottom: 8px; white-space: nowrap !important; }
.cc-back-link:hover { color: #3730A3; }
.cc-title { font-size: 24px; font-weight: 800; color: #000623; letter-spacing: -0.02em; margin: 0 0 4px; line-height: 1.25; }
.cc-sub { font-size: 13.5px; color: #64748B; margin: 0; line-height: 1.5; }

/* Compact Target Role Picker */
.cc-role-picker { position: relative; flex: 0 0 auto; width: 260px; max-width: 100%; }
.cc-role-label { display: block; font-size: 11px; font-weight: 700; color: #64748B; text-transform: uppercase; letter-spacing: 0.06em; margin-bottom: 6px; }
.cc-role-btn { display: flex; align-items: center; gap: 10px; width: 100%; height: 42px; padding: 0 12px; background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 10px; font-size: 13px; font-weight: 700; color: #0F172A; cursor: pointer; box-shadow: 0 1px 2px rgba(0,0,0,0.03); transition: border-color .15s ease, box-shadow .15s ease; }
.cc-role-btn:hover, .cc-role-btn.is-open { border-color: #4F48E6; box-shadow: 0 0 0 3px rgba(79,72,230,0.1); }
.cc-role-btn > span { flex: 1; min-width: 0; text-align: left; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.cc-role-btn svg { width: 15px; height: 15px; flex-shrink: 0; color: #64748B; transition: transform .2s ease; }
.cc-role-btn.is-open .cc-role-chevron { transform: rotate(180deg); }
.cc-role-menu { position: absolute; right: 0; left: 0; top: calc(100% + 6px); background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 12px; box-shadow: 0 12px 28px rgba(15,23,42,0.1); z-index: 40; padding: 6px; max-height: 300px; overflow-y: auto; }
.cc-role-option { display: flex; align-items: center; justify-content: space-between; gap: 8px; width: 100%; padding: 9px 10px; border: 0; background: transparent; border-radius: 8px; font-size: 13px; font-weight: 600; color: #1E293B; cursor: pointer; text-align: left; }
.cc-role-option:hover { background: #F1F5F9; }
.cc-role-option.is-active { background: #EEF2FF; color: #4F48E6; font-weight: 700; }
.cc-role-option svg { width: 14px; height: 14px; flex-shrink: 0; }

@media (max-width: 640px) {
    .cc-role-picker { width: 100%; }
}

/* Loading veil while a new roadmap is generated */
.cc-body { transition: opacity .2s ease; }
.cc-body.is-updating { opacity: .45; pointer-events: none; }

/* Summary Strip */
.cc-summary { display: grid; grid-template-columns: minmax(0, 1fr) minmax(0, 1fr); gap: 16px; margin-bottom: 20px; }
@media (max-width: 1100px) { .cc-summary { grid-template-columns: minmax(0, 1fr); } }

.cc-panel { background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 16px; box-shadow: 0 1px 3px rgba(0,0,0,0.02); min-width: 0; }

.cc-readiness { display: flex; align-items: flex-start; gap: 16px; padding: 20px 22px; }
.cc-jenny-badge { width: 46px; height: 46px; border-radius: 13px; background: #EEF2FF; color: #4F48E6; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.cc-readiness-body { flex: 1; min-width: 0; }
.cc-readiness-top { display: flex; align-items: flex-start; justify-content: space-between; gap: 12px; }
.cc-readiness-title { font-size: 14.5px; font-weight: 800; color: #000623; margin: 0; line-height: 1.35; }
.cc-readiness-score { font-size: 26px; font-weight: 800; color: #4F48E6; line-height: 1; white-space: nowrap; }
.cc-readiness-sub { font-size: 12.5px; color: #64748B; margin: 4px 0 12px; line-height: 1.45; }
.cc-readiness-meta { display: flex; align-items: center; justify-content: space-between; gap: 8px; margin-top: 10px; font-size: 11.5px; font-weight: 600; color: #64748B; flex-wrap: wrap; }

.cc-stats { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); }
.cc-stat { padding: 20px 22px; display: flex; flex-direction: column; gap: 6px; min-width: 0; }
.cc-stat + .cc-stat { border-left: 1px solid #F1F5F9; }
.cc-stat-label { font-size: 11px; font-weight: 700; color: #64748B; text-transform: uppercase; letter-spacing: 0.06em; white-space: nowrap; }
.cc-stat-value { font-size: 24px; font-weight: 800; color: #000623; line-height: 1.15; letter-spacing: -0.01em; white-space: nowrap; }
.cc-stat-value small { font-size: 14px; color: #94A3B8; font-weight: 700; }
.cc-stat-hint { font-size: 12px; color: #64748B; line-height: 1.4; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.cc-stat .cc-bar { margin-top: 2px; }
@media (max-width: 640px) {
    .cc-readiness { padding: 16px; gap: 12px; }
    .cc-jenny-badge { width: 40px; height: 40px; }
    .cc-stats { grid-template-columns: repeat(3, minmax(0, 1fr)); }
    .cc-stat { padding: 14px 12px; }
    .cc-stat-value { font-size: 18px; }
    .cc-stat-value small { font-size: 12px; }
    .cc-stat-label { font-size: 10px; letter-spacing: 0.04em; }
    .cc-stat-hint { font-size: 11px; white-space: normal; }
}
/* Progress bars */
.cc-bar { width: 100%; height: 6px; background: #EEF0F4; border-radius: 9999px; overflow: hidden; }
.cc-bar > span { display: block; height: 100%; border-radius: 9999px; background: #4F48E6; transition: width .4s ease; }
.cc-bar.is-thin { height: 4px; }

/* Pills */
.cc-pill { display: inline-flex; align-items: center; gap: 5px; font-size: 11px; font-weight: 700; padding: 3px 9px; border-radius: 9999px; white-space: nowrap !important; flex-shrink: 0 !important; line-height: 1.3; }
.cc-pill svg { width: 11px; height: 11px; flex-shrink: 0; }
.cc-pill.green { background: #ECFDF5; color: #047857; }
.cc-pill.indigo { background: #EEF2FF; color: #4F48E6; }
.cc-pill.amber { background: #FFFBEB; color: #B45309; }
.cc-pill.slate { background: #F1F5F9; color: #475569; }

/* Section header (shared) */
.cc-section-head { display: flex; align-items: flex-start; justify-content: space-between; gap: 12px; padding: 20px 22px 16px; border-bottom: 1px solid #F1F5F9; flex-wrap: wrap; }
.cc-section-title { font-size: 15px; font-weight: 800; color: #000623; margin: 0; }
.cc-section-desc { font-size: 12.5px; color: #64748B; margin: 3px 0 0; line-height: 1.45; }
.cc-section-body { padding: 18px 22px 22px; }
@media (max-width: 640px) { .cc-section-head { padding: 16px 16px 14px; } .cc-section-body { padding: 14px 16px 18px; } }

/* Skills grid */
.cc-skills-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 16px; margin-bottom: 20px; }
@media (max-width: 960px) { .cc-skills-grid { grid-template-columns: minmax(0, 1fr); } }

.cc-skill-group + .cc-skill-group { margin-top: 18px; padding-top: 16px; border-top: 1px dashed #E2E8F0; }
.cc-skill-group-title .cc-pill { text-transform: none; letter-spacing: 0; }
.cc-skill-group-title { display: flex; align-items: center; justify-content: space-between; gap: 8px; font-size: 11px; font-weight: 700; color: #64748B; text-transform: uppercase; letter-spacing: 0.06em; margin-bottom: 12px; }
.cc-skill-row + .cc-skill-row { margin-top: 12px; }
.cc-skill-meta { display: flex; align-items: center; justify-content: space-between; gap: 10px; margin-bottom: 6px; }
.cc-skill-name { font-size: 13px; font-weight: 700; color: #1E293B; min-width: 0; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
.cc-skill-val { font-size: 12.5px; font-weight: 800; color: #0F172A; white-space: nowrap; }

.cc-focus-list { display: flex; flex-direction: column; }
.cc-focus-item { display: flex; align-items: flex-start; gap: 12px; padding: 12px 0; }
.cc-focus-item + .cc-focus-item { border-top: 1px solid #F1F5F9; }
.cc-focus-item:first-child { padding-top: 0; }
.cc-focus-item:last-child { padding-bottom: 0; }
.cc-focus-icon { width: 32px; height: 32px; border-radius: 9px; background: #F8FAFC; border: 1px solid #EEF0F4; color: #4F48E6; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.cc-focus-icon svg { width: 15px; height: 15px; }
.cc-focus-body { flex: 1; min-width: 0; }
.cc-focus-top { display: flex; align-items: center; justify-content: space-between; gap: 8px; flex-wrap: wrap; }
.cc-focus-name { font-size: 13px; font-weight: 700; color: #1E293B; }
.cc-focus-reason { font-size: 12px; color: #64748B; line-height: 1.45; margin-top: 3px; }

/* Roadmap */
.cc-roadmap { margin-bottom: 20px; }
.cc-roadmap-progress { display: flex; align-items: center; gap: 12px; min-width: 220px; flex: 0 1 300px; }
.cc-roadmap-progress .cc-bar { flex: 1; }
.cc-roadmap-progress strong { font-size: 12.5px; color: #0F172A; white-space: nowrap; }
@media (max-width: 640px) { .cc-roadmap-progress { flex: 1 1 100%; min-width: 0; } }

.cc-stepper { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 10px; margin-bottom: 18px; }
@media (max-width: 900px) {
    .cc-stepper { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    .cc-stepper .cc-step-title { white-space: normal; display: -webkit-box !important; -webkit-line-clamp: 2; -webkit-box-orient: vertical; }
    .cc-step-top { align-items: flex-start; }
}
@media (max-width: 420px) { .cc-step { padding: 10px; } .cc-step-num { width: 24px; height: 24px; font-size: 11px; } .cc-step-top { gap: 8px; } }

.cc-step { display: flex; flex-direction: column; gap: 10px; padding: 12px 14px; border: 1px solid #E2E8F0; border-radius: 12px; background: #FFFFFF; cursor: pointer; text-align: left; transition: border-color .15s ease, background .15s ease, box-shadow .15s ease; min-width: 0; font: inherit; }
.cc-step:hover { border-color: #CBD5E1; background: #F8FAFC; }
.cc-step.is-selected { border-color: #4F48E6; background: #F5F5FF; box-shadow: 0 0 0 3px rgba(79,72,230,0.08); }
.cc-step-top { display: flex; align-items: center; gap: 10px; min-width: 0; }
.cc-step-num { width: 28px; height: 28px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 12px; font-weight: 800; flex-shrink: 0; background: #F1F5F9; color: #475569; }
.cc-step-num svg { width: 14px; height: 14px; }
.cc-step.is-done .cc-step-num { background: #10B981; color: #FFFFFF; }
.cc-step.is-active .cc-step-num { background: #4F48E6; color: #FFFFFF; }
.cc-step-text { min-width: 0; flex: 1; }
.cc-step-weeks { font-size: 11px; font-weight: 700; color: #64748B; white-space: nowrap; }
.cc-step-title { font-size: 12.5px; font-weight: 700; color: #0F172A; line-height: 1.3; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.cc-step-foot { display: flex; align-items: center; gap: 8px; }
.cc-step-foot .cc-bar { flex: 1; }
.cc-step-count { font-size: 11px; font-weight: 700; color: #475569; white-space: nowrap; }

.cc-phase-panel { border: 1px solid #E2E8F0; border-radius: 14px; overflow: hidden; }
.cc-phase-head { display: flex; align-items: flex-start; justify-content: space-between; gap: 12px 16px; padding: 16px 18px; background: #F8FAFC; border-bottom: 1px solid #EEF0F4; flex-wrap: wrap; }
.cc-phase-head-text { min-width: 0; flex: 1 1 320px; }
.cc-phase-kicker { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; margin-bottom: 6px; }
.cc-phase-kicker span.cc-phase-step { font-size: 11px; font-weight: 700; color: #64748B; text-transform: uppercase; letter-spacing: 0.06em; }
.cc-phase-title { font-size: 15px; font-weight: 800; color: #000623; margin: 0; line-height: 1.35; }
.cc-phase-desc { font-size: 12.5px; color: #64748B; margin: 4px 0 0; line-height: 1.5; }
.cc-phase-actions { display: flex; align-items: center; gap: 8px; flex-shrink: 0; }

.cc-task-list { list-style: none; margin: 0; padding: 6px 0; }
.cc-task { display: flex; align-items: flex-start; gap: 12px; width: 100%; padding: 12px 18px; border: 0; background: transparent; cursor: pointer; text-align: left; font: inherit; transition: background .15s ease; }
.cc-task:hover { background: #F8FAFC; }
.cc-task-check { width: 20px; height: 20px; border-radius: 6px; border: 1.5px solid #CBD5E1; background: #FFFFFF; display: flex; align-items: center; justify-content: center; flex-shrink: 0; margin-top: 1px; transition: all .15s ease; color: #FFFFFF; }
.cc-task-check svg { width: 12px; height: 12px; }
.cc-task.is-done .cc-task-check { background: #4F48E6; border-color: #4F48E6; }
.cc-task-text { font-size: 13px; color: #1E293B; line-height: 1.5; flex: 1; min-width: 0; }
.cc-task.is-done .cc-task-text { color: #94A3B8; text-decoration: line-through; }
.cc-task-state { font-size: 11px; font-weight: 700; color: #94A3B8; white-space: nowrap; margin-top: 2px; }
.cc-task.is-done .cc-task-state { color: #047857; }
@media (max-width: 640px) { .cc-task { padding: 12px 14px; } .cc-task-state { display: none; } .cc-phase-head { padding: 14px; } }

.cc-phase-foot { display: flex; align-items: center; justify-content: space-between; gap: 10px; padding: 12px 18px; border-top: 1px solid #EEF0F4; flex-wrap: wrap; }
.cc-ghost-btn { display: inline-flex; align-items: center; gap: 6px; height: 36px; padding: 0 12px; border: 1px solid #E2E8F0; background: #FFFFFF; border-radius: 9px; font-size: 12.5px; font-weight: 700; color: #334155; cursor: pointer; white-space: nowrap !important; flex-shrink: 0 !important; transition: all .15s ease; }
.cc-ghost-btn:hover:not(:disabled) { border-color: #CBD5E1; background: #F8FAFC; }
.cc-ghost-btn:disabled { opacity: .45; cursor: not-allowed; }
.cc-ghost-btn svg { width: 14px; height: 14px; }
.cc-primary-btn { display: inline-flex; align-items: center; gap: 6px; height: 36px; padding: 0 14px; border: 1px solid #4F48E6; background: #4F48E6; border-radius: 9px; font-size: 12.5px; font-weight: 700; color: #FFFFFF; cursor: pointer; white-space: nowrap !important; flex-shrink: 0 !important; transition: background .15s ease; }
.cc-primary-btn:hover { background: #4338CA; border-color: #4338CA; }
.cc-primary-btn svg { width: 14px; height: 14px; }
@media (max-width: 640px) { .cc-phase-foot { padding: 12px 14px; } .cc-phase-foot > * { flex: 1 1 auto; justify-content: center; } }

/* Courses */
.cc-courses-grid { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 14px; }
@media (max-width: 1100px) { .cc-courses-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); } }
@media (max-width: 640px) { .cc-courses-grid { grid-template-columns: minmax(0, 1fr); } }
.cc-course-card { display: flex; flex-direction: column; gap: 10px; padding: 16px; border: 1px solid #E2E8F0; border-radius: 12px; background: #FFFFFF; text-decoration: none; transition: border-color .15s ease, box-shadow .15s ease; min-width: 0; }
.cc-course-card:hover { border-color: #C7D2FE; box-shadow: 0 6px 18px rgba(15,23,42,0.06); }
.cc-course-title { font-size: 13.5px; font-weight: 700; color: #000623; margin: 0; line-height: 1.4; }
.cc-course-provider { font-size: 12px; color: #64748B; }
.cc-course-meta { display: flex; align-items: center; justify-content: space-between; gap: 8px; margin-top: auto; padding-top: 10px; border-top: 1px solid #F1F5F9; font-size: 12px; color: #64748B; }
.cc-course-meta span { display: inline-flex; align-items: center; gap: 5px; white-space: nowrap; }
.cc-course-meta svg { width: 13px; height: 13px; flex-shrink: 0; }
.cc-course-rating { font-weight: 700; color: #0F172A; }
.cc-course-rating svg { color: #F59E0B; }
.cc-link { display: inline-flex; align-items: center; gap: 5px; font-size: 13px; font-weight: 700; color: #4F48E6; text-decoration: none; white-space: nowrap; }
.cc-link:hover { color: #3730A3; }
.cc-link svg { width: 14px; height: 14px; }

@keyframes spin { to { transform: rotate(360deg); } }
</style>

<script>
window.__CAREER_COACH_INIT__ = <?= json_encode([
    'selectedRole' => $selectedRole,
    'roadmap'      => $roadmap,
    'roadmapUrl'   => url('/candidate/ai-assistant/career-coach/roadmap')
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?>;

function careerCoachComponent() {
    const init = window.__CAREER_COACH_INIT__ || {};
    return {
        roleOpen: false,
        selectedRole: init.selectedRole || 'Product Designer',
        roadmap: init.roadmap || {},
        updating: false,
        activePhase: 0,

        init() {
            this.restoreProgress();
            this.activePhase = this.currentPhaseIndex;
        },

        storageKey() {
            return 'icCareerRoadmap_' + this.selectedRole;
        },

        restoreProgress() {
            try {
                const saved = JSON.parse(localStorage.getItem(this.storageKey()) || 'null');
                if (!Array.isArray(saved)) return;
                (this.roadmap.phases || []).forEach((phase, p) => {
                    (phase.tasks || []).forEach((task, t) => {
                        if (saved[p] && typeof saved[p][t] === 'boolean') task.done = saved[p][t];
                    });
                });
            } catch (e) {}
        },

        saveProgress() {
            try {
                const state = (this.roadmap.phases || []).map((phase) => (phase.tasks || []).map((task) => !!task.done));
                localStorage.setItem(this.storageKey(), JSON.stringify(state));
            } catch (e) {}
        },

        get phases() { return this.roadmap.phases || []; },
        get totalTasks() { return this.phases.reduce((n, p) => n + (p.tasks || []).length, 0); },
        get doneTasks() { return this.phases.reduce((n, p) => n + (p.tasks || []).filter((t) => t.done).length, 0); },
        get completionPct() { return this.totalTasks ? Math.round((this.doneTasks / this.totalTasks) * 100) : 0; },
        get currentPhaseIndex() {
            const i = this.phases.findIndex((p) => (p.tasks || []).some((t) => !t.done));
            return i === -1 ? Math.max(this.phases.length - 1, 0) : i;
        },
        get currentPhase() { return this.phases[this.currentPhaseIndex] || {}; },
        get selectedPhase() { return this.phases[this.activePhase] || {}; },
        get nextTask() {
            for (const p of this.phases) {
                const t = (p.tasks || []).find((x) => !x.done);
                if (t) return t.title;
            }
            return 'All milestones complete';
        },

        phaseDone(p) { return (p.tasks || []).filter((t) => t.done).length; },
        phaseState(i) {
            const p = this.phases[i] || {};
            const total = (p.tasks || []).length;
            if (total && this.phaseDone(p) === total) return 'done';
            if (i === this.currentPhaseIndex) return 'active';
            return 'upcoming';
        },
        phaseStateLabel(i) {
            return { done: 'Completed', active: 'In progress', upcoming: 'Up next' }[this.phaseState(i)];
        },
        phasePct(p) {
            const total = (p.tasks || []).length;
            return total ? Math.round((this.phaseDone(p) / total) * 100) : 0;
        },

        async switchRole(role) {
            this.roleOpen = false;
            if (role === this.selectedRole) return;
            this.selectedRole = role;
            this.updating = true;
            try {
                const resp = await fetch(init.roadmapUrl, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ target_role: role })
                });
                const data = await resp.json();
                if (data.success && data.roadmap) {
                    this.roadmap = data.roadmap;
                    this.restoreProgress();
                    this.activePhase = this.currentPhaseIndex;
                    window.dispatchEvent(new CustomEvent('show-toast', {
                        detail: { text: 'Career Roadmap customized for ' + role, type: 'info' }
                    }));
                }
            } catch (e) {
                console.error('Error switching roadmap role', e);
            } finally {
                this.updating = false;
            }
        },

        toggleTask(pIdx, tIdx) {
            const phase = this.phases[pIdx];
            if (!phase || !phase.tasks || !phase.tasks[tIdx]) return;
            const t = phase.tasks[tIdx];
            t.done = !t.done;
            this.saveProgress();
            if (t.done) {
                const finishedPhase = this.phaseDone(phase) === phase.tasks.length;
                window.dispatchEvent(new CustomEvent('show-toast', {
                    detail: { text: finishedPhase ? 'Phase ' + phase.phase_number + ' completed!' : 'Milestone task completed!', type: 'success' }
                }));
            }
        },

        goToPhase(i) {
            if (i < 0 || i >= this.phases.length) return;
            this.activePhase = i;
        }
    };
}
</script>

<div class="db-shell" x-data="careerCoachComponent()">

    <!-- Mobile Drawer Overlay -->
    <div class="db-sidebar-overlay" id="db-sidebar-overlay"></div>

    <!-- ══ SIDEBAR ══ -->
    <?php
    $activeNav = 'ai-assistant';
    include dirname(__DIR__, 2) . '/shared/components/candidate-sidebar.php';
    ?>

    <!-- ══ MAIN CONTENT WRAPPER ══ -->
    <div class="db-main">

        <!-- Top Navigation -->
        <?php include dirname(__DIR__, 2) . '/shared/components/candidate-topnav.php'; ?>

        <!-- Content Body -->
        <main class="db-content" id="db-content">
            <div class="cc-container">

                <!-- ── 1. Page Header + Compact Role Picker ── -->
                <div class="cc-header">
                    <div class="cc-header-text">
                        <a href="<?= url('/candidate/ai-assistant') ?>" class="cc-back-link">
                            <svg style="width: 14px; height: 14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            <span>Back to AI Career Assistant</span>
                        </a>
                        <h1 class="cc-title">AI Career Coach &amp; Skill Roadmap</h1>
                        <p class="cc-sub">Skill gap diagnostic, progression milestones, and hiring preparation tailored to your target discipline.</p>
                    </div>

                    <div class="cc-role-picker" @click.outside="roleOpen = false" @keydown.escape.window="roleOpen = false">
                        <span class="cc-role-label" id="cc-role-label">Target role</span>
                        <button type="button" class="cc-role-btn" :class="{ 'is-open': roleOpen }" @click="roleOpen = !roleOpen" aria-haspopup="listbox" :aria-expanded="roleOpen" aria-labelledby="cc-role-label">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            <span x-text="selectedRole"><?= htmlspecialchars($selectedRole) ?></span>
                            <svg x-show="updating" style="display: none; animation: spin 1s linear infinite;" fill="none" viewBox="0 0 24 24"><circle style="opacity: .25;" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path style="opacity: .75;" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                            <svg class="cc-role-chevron" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div x-show="roleOpen" x-transition.opacity.duration.150ms class="cc-role-menu" role="listbox" style="display: none;">
                            <?php foreach ($disciplines as $d): ?>
                            <button type="button" role="option" class="cc-role-option" :class="{ 'is-active': selectedRole === <?= htmlspecialchars(json_encode($d), ENT_QUOTES, 'UTF-8') ?> }" :aria-selected="selectedRole === <?= htmlspecialchars(json_encode($d), ENT_QUOTES, 'UTF-8') ?>" @click="switchRole(<?= htmlspecialchars(json_encode($d), ENT_QUOTES, 'UTF-8') ?>)">
                                <span><?= htmlspecialchars($d) ?></span>
                                <svg x-show="selectedRole === <?= htmlspecialchars(json_encode($d), ENT_QUOTES, 'UTF-8') ?>" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            </button>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <div class="cc-body" :class="{ 'is-updating': updating }">

                    <!-- ── 2. Summary Strip ── -->
                    <div class="cc-summary">
                        <div class="cc-panel cc-readiness">
                            <div class="cc-jenny-badge">
                                <!-- Official Jenny AI Brand Vector -->
                                <svg style="width: 24px; height: 24px;" viewBox="0 0 74 83" fill="currentColor" aria-hidden="true">
                                    <path d="M73.74 32.4627V59.9427C67.93 59.7673 62.2767 61.8422 57.9596 65.7344C53.6426 69.6267 50.9952 75.0356 50.57 80.8327C50.52 81.3027 50.48 81.8327 50.46 82.2827H23.57C23.57 82.0227 23.48 81.7627 23.43 81.5127C20.73 67.1427 16.41 63.2427 0 59.6527V32.6527C13.34 31.5727 27.12 37.6527 36.92 47.9327C46 38.3527 58.99 31.8927 73.74 32.4627Z"/>
                                    <path d="M37.0001 0.0031237C40.4833 0.0367014 43.8785 1.10075 46.7578 3.06112C49.637 5.02149 51.8715 7.79043 53.1794 11.0188C54.4873 14.2473 54.8101 17.7906 54.1071 21.2023C53.4042 24.6139 51.7069 27.741 49.2293 30.1894C46.7516 32.6378 43.6046 34.2979 40.1849 34.9604C36.7652 35.6228 33.2259 35.258 30.0132 33.9119C26.8005 32.5658 24.0583 30.2987 22.1322 27.3963C20.2061 24.494 19.1824 21.0864 19.1901 17.6031C19.1737 15.2695 19.6247 12.9562 20.5166 10.7997C21.4084 8.64315 22.7231 6.68703 24.383 5.04669C26.0429 3.40635 28.0145 2.11499 30.1814 1.24877C32.3484 0.382549 34.6669 -0.040991 37.0001 0.0031237Z"/>
                                </svg>
                            </div>
                            <div class="cc-readiness-body">
                                <div class="cc-readiness-top">
                                    <h2 class="cc-readiness-title">Career Readiness Diagnostic: <span x-text="roadmap.target_role"><?= htmlspecialchars($roadmap['target_role']) ?></span></h2>
                                    <span class="cc-readiness-score" x-text="roadmap.readiness_score + '%'"><?= $readinessPct ?>%</span>
                                </div>
                                <p class="cc-readiness-sub">AI has evaluated your profile against 140+ active hiring criteria from leading Nigerian tech and enterprise employers.</p>
                                <div class="cc-bar"><span :style="{ width: (roadmap.readiness_score || 0) + '%' }" style="width: <?= (int)$readinessPct ?>%;"></span></div>
                                <div class="cc-readiness-meta">
                                    <span>Overall Match</span>
                                    <span class="cc-pill green">
                                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                        <span>Candidate Tier 1</span>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="cc-panel cc-stats">
                        <div class="cc-stat">
                            <span class="cc-stat-label">Roadmap</span>
                            <span class="cc-stat-value" x-text="completionPct + '%'">0%</span>
                            <div class="cc-bar is-thin"><span :style="{ width: completionPct + '%' }"></span></div>
                            <span class="cc-stat-hint">Overall completion</span>
                        </div>

                        <div class="cc-stat">
                            <span class="cc-stat-label">Tasks done</span>
                            <span class="cc-stat-value"><span x-text="doneTasks">0</span><small> / <span x-text="totalTasks">0</span></small></span>
                            <span class="cc-stat-hint" x-text="(totalTasks - doneTasks) + ' milestones left'"></span>
                        </div>

                        <div class="cc-stat">
                            <span class="cc-stat-label">Current phase</span>
                            <span class="cc-stat-value" x-text="'Phase ' + (currentPhase.phase_number || 1)">Phase 1</span>
                            <span class="cc-stat-hint" x-text="currentPhase.timeline"></span>
                        </div>
                        </div>
                    </div>

                    <!-- ── 3. Skill Gap Diagnostic ── -->
                    <div class="cc-skills-grid">
                        <section class="cc-panel">
                            <div class="cc-section-head">
                                <div>
                                    <h2 class="cc-section-title">Skill Levels</h2>
                                    <p class="cc-section-desc">Where your verified profile stands today.</p>
                                </div>
                            </div>
                            <div class="cc-section-body">
                                <div class="cc-skill-group">
                                    <div class="cc-skill-group-title">
                                        <span>Mastered Competencies</span>
                                        <span class="cc-pill green" x-text="(roadmap.mastered_skills || []).length + ' Verified'"></span>
                                    </div>
                                    <template x-for="(s, idx) in (roadmap.mastered_skills || [])" :key="'m' + idx">
                                        <div class="cc-skill-row">
                                            <div class="cc-skill-meta">
                                                <span class="cc-skill-name" x-text="s.name"></span>
                                                <span class="cc-skill-val" x-text="s.level + '%'"></span>
                                            </div>
                                            <div class="cc-bar"><span :style="{ width: s.level + '%', background: '#10B981' }"></span></div>
                                        </div>
                                    </template>
                                </div>
                                <div class="cc-skill-group">
                                    <div class="cc-skill-group-title">
                                        <span>Skills in Progress</span>
                                        <span class="cc-pill indigo" x-text="(roadmap.in_progress_skills || []).length + ' Building'"></span>
                                    </div>
                                    <template x-for="(s, idx) in (roadmap.in_progress_skills || [])" :key="'p' + idx">
                                        <div class="cc-skill-row">
                                            <div class="cc-skill-meta">
                                                <span class="cc-skill-name" x-text="s.name"></span>
                                                <span class="cc-skill-val" x-text="s.level + '%'"></span>
                                            </div>
                                            <div class="cc-bar"><span :style="{ width: s.level + '%' }"></span></div>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </section>

                        <section class="cc-panel">
                            <div class="cc-section-head">
                                <div>
                                    <h2 class="cc-section-title">Priority Focus Areas</h2>
                                    <p class="cc-section-desc">Skills employers ask for that are missing from your profile.</p>
                                </div>
                                <span class="cc-pill amber" x-text="(roadmap.missing_skills || []).length + ' Recommended'"></span>
                            </div>
                            <div class="cc-section-body">
                                <div class="cc-focus-list">
                                    <template x-for="(s, idx) in (roadmap.missing_skills || [])" :key="'f' + idx">
                                        <div class="cc-focus-item">
                                            <span class="cc-focus-icon">
                                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                            </span>
                                            <div class="cc-focus-body">
                                                <div class="cc-focus-top">
                                                    <span class="cc-focus-name" x-text="s.name"></span>
                                                    <span class="cc-pill" :class="s.priority === 'High Priority' ? 'amber' : 'slate'" x-text="s.priority"></span>
                                                </div>
                                                <div class="cc-focus-reason" x-text="s.reason"></div>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </section>
                    </div>

                    <!-- ── 4. Trackable 12-Week Roadmap ── -->
                    <section class="cc-panel cc-roadmap" id="cc-roadmap">
                        <div class="cc-section-head">
                            <div>
                                <h2 class="cc-section-title">12-Week Progressive Career Roadmap</h2>
                                <p class="cc-section-desc">Structured milestone checklist to bridge your skill gaps and secure verified internship offers.</p>
                            </div>
                            <div class="cc-roadmap-progress">
                                <div class="cc-bar"><span :style="{ width: completionPct + '%' }"></span></div>
                                <strong x-text="doneTasks + ' of ' + totalTasks + ' done'"></strong>
                            </div>
                        </div>

                        <div class="cc-section-body">
                            <!-- Phase stepper -->
                            <div class="cc-stepper" role="tablist" aria-label="Roadmap phases">
                                <template x-for="(phase, pIdx) in phases" :key="'s' + pIdx">
                                    <button type="button" role="tab" class="cc-step"
                                        :class="{ 'is-selected': activePhase === pIdx, 'is-done': phaseState(pIdx) === 'done', 'is-active': phaseState(pIdx) === 'active' }"
                                        :aria-selected="activePhase === pIdx"
                                        @click="goToPhase(pIdx)">
                                        <span class="cc-step-top">
                                            <span class="cc-step-num">
                                                <svg x-show="phaseState(pIdx) === 'done'" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                                <span x-show="phaseState(pIdx) !== 'done'" x-text="phase.phase_number"></span>
                                            </span>
                                            <span class="cc-step-text">
                                                <span class="cc-step-weeks" x-text="phase.timeline"></span>
                                                <span class="cc-step-title" style="display: block;" x-text="phase.title"></span>
                                            </span>
                                        </span>
                                        <span class="cc-step-foot">
                                            <span class="cc-bar is-thin"><span :style="{ width: phasePct(phase) + '%', background: phaseState(pIdx) === 'done' ? '#10B981' : '#4F48E6' }"></span></span>
                                            <span class="cc-step-count" x-text="phaseDone(phase) + '/' + (phase.tasks || []).length"></span>
                                        </span>
                                    </button>
                                </template>
                            </div>

                            <!-- Selected phase detail -->
                            <div class="cc-phase-panel" role="tabpanel">
                                <div class="cc-phase-head">
                                    <div class="cc-phase-head-text">
                                        <div class="cc-phase-kicker">
                                            <span class="cc-phase-step" x-text="'Phase ' + selectedPhase.phase_number + ' of ' + phases.length"></span>
                                            <span class="cc-pill" :class="{ 'green': phaseState(activePhase) === 'done', 'indigo': phaseState(activePhase) === 'active', 'slate': phaseState(activePhase) === 'upcoming' }" x-text="phaseStateLabel(activePhase)"></span>
                                            <span class="cc-pill slate" x-text="selectedPhase.timeline"></span>
                                        </div>
                                        <h3 class="cc-phase-title" x-text="selectedPhase.title"></h3>
                                        <p class="cc-phase-desc" x-text="selectedPhase.description"></p>
                                    </div>
                                </div>

                                <ul class="cc-task-list">
                                    <template x-for="(task, tIdx) in (selectedPhase.tasks || [])" :key="activePhase + '-' + tIdx">
                                        <li>
                                            <button type="button" class="cc-task" :class="{ 'is-done': task.done }" role="checkbox" :aria-checked="task.done" @click="toggleTask(activePhase, tIdx)">
                                                <span class="cc-task-check">
                                                    <svg x-show="task.done" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3.2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                                </span>
                                                <span class="cc-task-text" x-text="task.title"></span>
                                                <span class="cc-task-state" x-text="task.done ? 'Done' : 'To do'"></span>
                                            </button>
                                        </li>
                                    </template>
                                </ul>

                                <div class="cc-phase-foot">
                                    <button type="button" class="cc-ghost-btn" :disabled="activePhase === 0" @click="goToPhase(activePhase - 1)">
                                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                                        <span>Previous phase</span>
                                    </button>
                                    <button type="button" class="cc-primary-btn" x-show="activePhase < phases.length - 1" @click="goToPhase(activePhase + 1)">
                                        <span>Next phase</span>
                                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                                    </button>
                                    <button type="button" class="cc-primary-btn" x-show="activePhase === phases.length - 1 && activePhase !== currentPhaseIndex" style="display: none;" @click="goToPhase(currentPhaseIndex)">
                                        <span>Back to current phase</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- ── 5. Recommended E-Learning Courses ── -->
                    <section class="cc-panel">
                        <div class="cc-section-head">
                            <div>
                                <h2 class="cc-section-title">Curated E-Learning Modules</h2>
                                <p class="cc-section-desc">Bridge your skill gaps directly with accredited course certifications on InternConnect.</p>
                            </div>
                            <a href="<?= url('/elearning') ?>" class="cc-link">
                                <span>View All Courses</span>
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </a>
                        </div>
                        <div class="cc-section-body">
                            <div class="cc-courses-grid">
                                <template x-for="(course, cIdx) in (roadmap.courses || [])" :key="'c' + cIdx">
                                    <a :href="course.url" class="cc-course-card">
                                        <span class="cc-pill indigo" style="align-self: flex-start;" x-text="course.badge"></span>
                                        <div>
                                            <h3 class="cc-course-title" x-text="course.title"></h3>
                                            <div class="cc-course-provider" x-text="course.provider"></div>
                                        </div>
                                        <div class="cc-course-meta">
                                            <span>
                                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                <span x-text="course.duration"></span>
                                            </span>
                                            <span class="cc-course-rating">
                                                <svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.5l2.94 5.96 6.56.95-4.75 4.63 1.12 6.54L12 17.5l-5.87 3.08 1.12-6.54L2.5 9.41l6.56-.95L12 2.5z"/></svg>
                                                <span x-text="course.rating"></span>
                                            </span>
                                        </div>
                                    </a>
                                </template>
                            </div>
                        </div>
                    </section>

                </div>
            </div>
        </main>

        <!-- Footer -->
        <?php include dirname(__DIR__, 2) . '/shared/components/db-footer.php'; ?>

    </div>
</div>
