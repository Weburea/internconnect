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

.cc-container {
    padding: 0 0 40px 0;
    max-width: 100%;
    box-sizing: border-box;
}

/* Page Header */
.cc-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    margin-bottom: 24px;
    flex-wrap: wrap;
}

.cc-back-link {
    display: inline-flex !important;
    align-items: center !important;
    gap: 6px !important;
    font-size: 13px;
    font-weight: 700;
    color: #4F48E6;
    text-decoration: none;
    margin-bottom: 6px;
    white-space: nowrap !important;
    flex-shrink: 0 !important;
}

.cc-back-link:hover {
    color: #3730A3;
}

.cc-title {
    font-size: 24px;
    font-weight: 800;
    color: #000623;
    letter-spacing: -0.02em;
    margin: 0 0 4px;
}

.cc-sub {
    font-size: 13.5px;
    color: #64748B;
    margin: 0;
}

/* Standardized Custom Alpine Dropdown */
.db-dropdown-wrap {
    position: relative;
    display: inline-block;
}

.db-dropdown-btn {
    display: inline-flex !important;
    align-items: center !important;
    justify-content: space-between !important;
    gap: 8px !important;
    background: #FFFFFF;
    border: 1px solid #E2E8F0;
    border-radius: 10px;
    padding: 9px 14px;
    font-size: 13px;
    font-weight: 600;
    color: #334155;
    cursor: pointer;
    box-shadow: 0 1px 2px rgba(0,0,0,0.03);
    white-space: nowrap !important;
    flex-shrink: 0 !important;
}

.db-dropdown-btn svg {
    width: 14px !important;
    height: 14px !important;
    max-width: 14px !important;
    max-height: 14px !important;
    flex-shrink: 0 !important;
    transition: transform 0.2s ease;
}

.db-dropdown-btn svg.rotate-180 {
    transform: rotate(180deg) !important;
}

.db-dropdown-menu {
    position: absolute;
    right: 0;
    top: calc(100% + 6px);
    background: #FFFFFF;
    border: 1px solid #E2E8F0;
    border-radius: 12px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.08);
    min-width: 200px;
    z-index: 50;
    overflow: hidden;
    padding: 6px;
}

.db-dropdown-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 9px 12px;
    font-size: 12.5px;
    font-weight: 600;
    color: #1E293B;
    text-decoration: none;
    border-radius: 8px;
    transition: background 0.15s ease;
    cursor: pointer;
}

.db-dropdown-item:hover {
    background: #F1F5F9;
    color: #4F48E6;
}

/* Hero Diagnostic Card */
.cc-hero-card {
    background: #FFFFFF;
    border: 1px solid #E2E8F0;
    border-radius: 18px;
    padding: 24px 28px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.02);
    margin-bottom: 24px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 24px;
    flex-wrap: wrap;
    box-sizing: border-box;
    width: 100%;
}

@media (max-width: 640px) {
    .cc-hero-card {
        padding: 18px 16px !important;
        flex-direction: column !important;
        align-items: stretch !important;
    }
}

.cc-hero-left {
    display: flex;
    align-items: center;
    gap: 18px;
    min-width: 0;
    flex: 1;
}

.cc-hero-badge {
    width: 52px;
    height: 52px;
    border-radius: 14px;
    background: #EEF2FF;
    border: 1px solid #C7D2FE;
    color: #4F48E6;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.cc-hero-title {
    font-size: 18px;
    font-weight: 800;
    color: #000623;
    margin: 0 0 4px;
}

.cc-hero-sub {
    font-size: 13px;
    color: #64748B;
    margin: 0;
    line-height: 1.45;
}

.cc-score-box {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 12px 20px;
    background: #F8FAFC;
    border: 1px solid #E2E8F0;
    border-radius: 14px;
    flex-shrink: 0;
}

.cc-score-num {
    font-size: 32px;
    font-weight: 800;
    color: #4F48E6;
    line-height: 1;
}

.cc-score-meta {
    font-size: 11.5px;
    font-weight: 700;
    color: #64748B;
    text-transform: uppercase;
    letter-spacing: 0.04em;
}

/* 3-Column Skill Gap Diagnostic Grid */
.cc-gap-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 18px;
    margin-bottom: 24px;
    width: 100%;
    box-sizing: border-box;
}

@media (max-width: 960px) {
    .cc-gap-grid {
        grid-template-columns: minmax(0, 1fr) !important;
    }
}

.cc-card {
    background: #FFFFFF;
    border: 1px solid #E2E8F0;
    border-radius: 16px;
    padding: 24px 28px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.02);
    box-sizing: border-box;
    width: 100%;
    min-width: 0;
}

@media (max-width: 640px) {
    .cc-card {
        padding: 18px 16px !important;
    }
}

.cc-card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 16px;
    padding-bottom: 12px;
    border-bottom: 1px solid #F1F5F9;
}

.cc-card-title {
    font-size: 14.5px;
    font-weight: 800;
    color: #000623;
    margin: 0;
}

.cc-count-pill {
    font-size: 11px;
    font-weight: 700;
    padding: 2px 8px;
    border-radius: 9999px;
    white-space: nowrap !important;
    flex-shrink: 0 !important;
}

.cc-count-pill.green {
    background: #ECFDF5;
    color: #059669;
    border: 1px solid #A7F3D0;
}

.cc-count-pill.blue {
    background: #EEF2FF;
    color: #4F48E6;
    border: 1px solid #C7D2FE;
}

.cc-count-pill.amber {
    background: #FFFBEB;
    color: #D97706;
    border: 1px solid #FDE68A;
}

/* Diagnostic Item Rows */
.cc-item-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.cc-item-row {
    display: flex;
    flex-direction: column;
    gap: 6px;
    padding: 10px 12px;
    background: #F8FAFC;
    border: 1px solid #F1F5F9;
    border-radius: 10px;
}

.cc-item-meta {
    display: flex;
    align-items: center;
    justify-content: space-between;
    font-size: 12.5px;
}

.cc-item-name {
    font-weight: 700;
    color: #1E293B;
}

.cc-item-val {
    font-weight: 800;
    color: #4F48E6;
}

.cc-prog-track {
    width: 100%;
    height: 6px;
    background: #E2E8F0;
    border-radius: 9999px;
    overflow: hidden;
}

.cc-prog-fill {
    height: 100%;
    border-radius: 9999px;
    background: #4F48E6;
}

.cc-missing-reason {
    font-size: 11.5px;
    color: #64748B;
    line-height: 1.4;
    margin-top: 2px;
}

/* 4-Phase Roadmap Timeline */
.cc-roadmap-card {
    background: #FFFFFF;
    border: 1px solid #E2E8F0;
    border-radius: 16px;
    padding: 24px 28px;
    margin-bottom: 24px;
    box-sizing: border-box;
    width: 100%;
}

.cc-phase-list {
    display: flex;
    flex-direction: column;
    gap: 18px;
    margin-top: 20px;
}

.cc-phase-item {
    border: 1px solid #E2E8F0;
    border-radius: 14px;
    padding: 18px 20px;
    background: #FFFFFF;
    transition: border-color 0.15s ease;
}

.cc-phase-item:hover {
    border-color: #CBD5E1;
}

.cc-phase-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    margin-bottom: 8px;
    flex-wrap: wrap;
}

.cc-phase-title-wrap {
    display: flex;
    align-items: center;
    gap: 10px;
}

.cc-phase-num {
    width: 26px;
    height: 26px;
    border-radius: 50%;
    background: #000623;
    color: #FFFFFF;
    font-size: 12px;
    font-weight: 800;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.cc-phase-title {
    font-size: 15px;
    font-weight: 800;
    color: #000623;
}

.cc-phase-timeline {
    font-size: 12px;
    font-weight: 700;
    color: #4F48E6;
    background: #EEF2FF;
    padding: 3px 10px;
    border-radius: 9999px;
    border: 1px solid #C7D2FE;
    white-space: nowrap !important;
    flex-shrink: 0 !important;
}

.cc-phase-desc {
    font-size: 12.5px;
    color: #64748B;
    margin: 0 0 14px;
    line-height: 1.5;
}

.cc-tasks-list {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.cc-task-row {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 12.5px;
    color: #334155;
    cursor: pointer;
    user-select: none;
}

.cc-task-check {
    width: 16px;
    height: 16px;
    border-radius: 4px;
    border: 1.5px solid #CBD5E1;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    transition: all 0.15s ease;
}

.cc-task-check.checked {
    background: #4F48E6;
    border-color: #4F48E6;
    color: #FFFFFF;
}

.cc-task-text.checked {
    text-decoration: line-through;
    color: #94A3B8;
}

/* Recommended Courses Shelf */
.cc-courses-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 16px;
    margin-top: 16px;
}

@media (max-width: 960px) {
    .cc-courses-grid {
        grid-template-columns: minmax(0, 1fr) !important;
    }
}

.cc-course-card {
    background: #F8FAFC;
    border: 1px solid #E2E8F0;
    border-radius: 12px;
    padding: 16px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    transition: transform 0.15s ease, border-color 0.15s ease;
    text-decoration: none;
}

.cc-course-card:hover {
    transform: translateY(-2px);
    border-color: #CBD5E1;
    background: #FFFFFF;
}

.cc-course-badge {
    align-self: flex-start;
    font-size: 10.5px;
    font-weight: 700;
    padding: 2px 8px;
    border-radius: 6px;
    background: #EEF2FF;
    color: #4F48E6;
    border: 1px solid #C7D2FE;
    margin-bottom: 8px;
    white-space: nowrap !important;
}

.cc-course-title {
    font-size: 13.5px;
    font-weight: 700;
    color: #000623;
    margin: 0 0 6px;
    line-height: 1.35;
}

.cc-course-meta {
    font-size: 11.5px;
    color: #64748B;
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-top: 12px;
    padding-top: 8px;
    border-top: 1px solid #E2E8F0;
}
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
        roleDropdownOpen: false,
        selectedRole: init.selectedRole || 'Product Designer',
        roadmap: init.roadmap || {},
        updating: false,

        async switchRole(role) {
            this.selectedRole = role;
            this.roleDropdownOpen = false;
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
            if (this.roadmap && this.roadmap.phases && this.roadmap.phases[pIdx] && this.roadmap.phases[pIdx].tasks[tIdx]) {
                const t = this.roadmap.phases[pIdx].tasks[tIdx];
                t.done = !t.done;
                if (t.done) {
                    window.dispatchEvent(new CustomEvent('show-toast', {
                        detail: { text: 'Milestone task completed!', type: 'success' }
                    }));
                }
            }
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

                <!-- ── 1. Page Header ── -->
                <div class="cc-header">
                    <div>
                        <a href="<?= url('/candidate/ai-assistant') ?>" class="cc-back-link">
                            <svg style="width: 14px; height: 14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            <span>Back to AI Career Assistant</span>
                        </a>
                        <h1 class="cc-title">AI Career Coach &amp; Skill Roadmap</h1>
                        <p class="cc-sub">Skill gap diagnostic, progression milestones, and hiring preparation tailored to your target discipline.</p>
                    </div>

                    <!-- Role Switcher Custom Alpine Dropdown -->
                    <div class="db-dropdown-wrap" @click.outside="roleDropdownOpen = false">
                        <button type="button" class="db-dropdown-btn" @click="roleDropdownOpen = !roleDropdownOpen" aria-label="Target Discipline Switcher">
                            <svg style="color: #64748B;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <span x-text="selectedRole">Product Designer</span>
                            <svg :class="{ 'rotate-180': roleDropdownOpen }" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        <div x-show="roleDropdownOpen" class="db-dropdown-menu" style="display: none;" x-transition>
                            <?php foreach ($disciplines as $d): ?>
                            <div class="db-dropdown-item" @click="switchRole('<?= addslashes($d) ?>')">
                                <?= htmlspecialchars($d) ?>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <!-- ── 2. Diagnostic Summary Hero Card ── -->
                <div class="cc-hero-card">
                    <div class="cc-hero-left">
                        <div class="cc-hero-badge">
                            <!-- Official Jenny AI Brand Vector -->
                            <svg style="width: 26px; height: 26px;" viewBox="0 0 74 83" fill="currentColor">
                                <path d="M73.74 32.4627V59.9427C67.93 59.7673 62.2767 61.8422 57.9596 65.7344C53.6426 69.6267 50.9952 75.0356 50.57 80.8327C50.52 81.3027 50.48 81.8327 50.46 82.2827H23.57C23.57 82.0227 23.48 81.7627 23.43 81.5127C20.73 67.1427 16.41 63.2427 0 59.6527V32.6527C13.34 31.5727 27.12 37.6527 36.92 47.9327C46 38.3527 58.99 31.8927 73.74 32.4627Z"/>
                                <path d="M37.0001 0.0031237C40.4833 0.0367014 43.8785 1.10075 46.7578 3.06112C49.637 5.02149 51.8715 7.79043 53.1794 11.0188C54.4873 14.2473 54.8101 17.7906 54.1071 21.2023C53.4042 24.6139 51.7069 27.741 49.2293 30.1894C46.7516 32.6378 43.6046 34.2979 40.1849 34.9604C36.7652 35.6228 33.2259 35.258 30.0132 33.9119C26.8005 32.5658 24.0583 30.2987 22.1322 27.3963C20.2061 24.494 19.1824 21.0864 19.1901 17.6031C19.1737 15.2695 19.6247 12.9562 20.5166 10.7997C21.4084 8.64315 22.7231 6.68703 24.383 5.04669C26.0429 3.40635 28.0145 2.11499 30.1814 1.24877C32.3484 0.382549 34.6669 -0.040991 37.0001 0.0031237Z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="cc-hero-title">
                                Career Readiness Diagnostic: <span x-text="roadmap.target_role"><?= htmlspecialchars($roadmap['target_role']) ?></span>
                            </div>
                            <p class="cc-hero-sub">
                                AI has evaluated your profile against 140+ active hiring criteria from leading Nigerian tech and enterprise employers.
                            </p>
                        </div>
                    </div>

                    <div class="cc-score-box">
                        <div class="cc-score-num" x-text="roadmap.readiness_score + '%'"><?= $readinessPct ?>%</div>
                        <div class="cc-score-meta">
                            <div>Overall Match</div>
                            <div style="color: #059669; font-size: 11px;">Candidate Tier 1</div>
                        </div>
                    </div>
                </div>

                <!-- ── 3. Skill Gap Diagnostic (3-Column Shelf) ── -->
                <div class="cc-gap-grid">
                    <!-- Column 1: Mastered Skills -->
                    <div class="cc-card">
                        <div class="cc-card-header">
                            <h2 class="cc-card-title">Mastered Competencies</h2>
                            <span class="cc-count-pill green" x-text="roadmap.mastered_skills.length + ' Verified'"></span>
                        </div>
                        <div class="cc-item-list">
                            <template x-for="(s, idx) in roadmap.mastered_skills" :key="idx">
                                <div class="cc-item-row">
                                    <div class="cc-item-meta">
                                        <span class="cc-item-name" x-text="s.name"></span>
                                        <span class="cc-item-val" style="color: #059669;" x-text="s.level + '%'"></span>
                                    </div>
                                    <div class="cc-prog-track">
                                        <div class="cc-prog-fill" style="background: #10B981;" :style="'width: ' + s.level + '%'"></div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- Column 2: In-Progress Skills -->
                    <div class="cc-card">
                        <div class="cc-card-header">
                            <h2 class="cc-card-title">Skills in Progress</h2>
                            <span class="cc-count-pill blue" x-text="roadmap.in_progress_skills.length + ' Building'"></span>
                        </div>
                        <div class="cc-item-list">
                            <template x-for="(s, idx) in roadmap.in_progress_skills" :key="idx">
                                <div class="cc-item-row">
                                    <div class="cc-item-meta">
                                        <span class="cc-item-name" x-text="s.name"></span>
                                        <span class="cc-item-val" x-text="s.level + '%'"></span>
                                    </div>
                                    <div class="cc-prog-track">
                                        <div class="cc-prog-fill" :style="'width: ' + s.level + '%'"></div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- Column 3: Critical Missing Skills -->
                    <div class="cc-card">
                        <div class="cc-card-header">
                            <h2 class="cc-card-title">Priority Focus Areas</h2>
                            <span class="cc-count-pill amber" x-text="roadmap.missing_skills.length + ' Recommended'"></span>
                        </div>
                        <div class="cc-item-list">
                            <template x-for="(s, idx) in roadmap.missing_skills" :key="idx">
                                <div class="cc-item-row">
                                    <div class="cc-item-meta">
                                        <span class="cc-item-name" x-text="s.name"></span>
                                        <span style="font-size: 11px; font-weight: 700; color: #D97706;" x-text="s.priority"></span>
                                    </div>
                                    <div class="cc-missing-reason" x-text="s.reason"></div>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                <!-- ── 4. 4-Phase Progressive Roadmap ── -->
                <div class="cc-roadmap-card">
                    <div class="cc-card-header">
                        <div>
                            <h2 class="cc-card-title" style="font-size: 16px;">12-Week Progressive Career Roadmap</h2>
                            <p style="font-size: 12.5px; color: #64748B; margin: 4px 0 0;">
                                Structured milestone checklist to bridge your skill gaps and secure verified internship offers.
                            </p>
                        </div>
                    </div>

                    <div class="cc-phase-list">
                        <template x-for="(phase, pIdx) in roadmap.phases" :key="pIdx">
                            <div class="cc-phase-item">
                                <div class="cc-phase-header">
                                    <div class="cc-phase-title-wrap">
                                        <div class="cc-phase-num" x-text="phase.phase_number"></div>
                                        <div class="cc-phase-title" x-text="phase.title"></div>
                                    </div>
                                    <span class="cc-phase-timeline" x-text="phase.timeline"></span>
                                </div>
                                <p class="cc-phase-desc" x-text="phase.description"></p>

                                <!-- Checklist Tasks -->
                                <div class="cc-tasks-list">
                                    <template x-for="(task, tIdx) in phase.tasks" :key="tIdx">
                                        <div class="cc-task-row" @click="toggleTask(pIdx, tIdx)">
                                            <div class="cc-task-check" :class="{ 'checked': task.done }">
                                                <svg x-show="task.done" style="width: 12px; height: 12px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                                </svg>
                                            </div>
                                            <span class="cc-task-text" :class="{ 'checked': task.done }" x-text="task.title"></span>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- ── 5. Recommended E-Learning Courses Shelf ── -->
                <div class="cc-card">
                    <div class="cc-card-header">
                        <div>
                            <h2 class="cc-card-title" style="font-size: 16px;">Curated E-Learning Modules</h2>
                            <p style="font-size: 12.5px; color: #64748B; margin: 4px 0 0;">
                                Bridge your skill gaps directly with accredited course certifications on InternConnect.
                            </p>
                        </div>
                        <a href="<?= url('/elearning') ?>" style="font-size: 13px; font-weight: 700; color: #4F48E6; text-decoration: none;">
                            View All Courses &rarr;
                        </a>
                    </div>

                    <div class="cc-courses-grid">
                        <template x-for="(course, cIdx) in roadmap.courses" :key="cIdx">
                            <a :href="course.url" class="cc-course-card">
                                <div>
                                    <span class="cc-course-badge" x-text="course.badge"></span>
                                    <div class="cc-course-title" x-text="course.title"></div>
                                    <div style="font-size: 12px; color: #64748B;" x-text="course.provider"></div>
                                </div>
                                <div class="cc-course-meta">
                                    <span x-text="course.duration"></span>
                                    <span style="font-weight: 700; color: #0F172A;" x-text="'★ ' + course.rating"></span>
                                </div>
                            </a>
                        </template>
                    </div>
                </div>

            </div>
        </main>

        <!-- Footer -->
        <?php include dirname(__DIR__, 2) . '/shared/components/db-footer.php'; ?>

    </div>
</div>
