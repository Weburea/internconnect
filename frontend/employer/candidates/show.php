<?php
declare(strict_types=1);
/**
 * View: Candidate Profile / Application Review (Employer Portal)
 * Route: /employer/candidates/{id} or /employer/candidate/{id}
 * Layout: dashboard
 * 
 * Exact 1:1 match with Image 4 & Design System standards:
 * - Header card with avatar, active dot, match score circle gauge, and action buttons
 * - 2-Column layout:
 *   Left: Application Answers, Professional Summary, Work Experience, Application Details
 *   Right: Pipeline Stage, Documents & Links, Contact, Education, Skills & Interests
 * 
 * @var array  $employer  Employer profile data
 * @var array  $candidate Candidate application data
 * @var string $activeNav Active navigation identifier
 */
?>

<script src="<?= asset('assets/js/jszip.min.js') ?>"></script>
<script src="<?= asset('assets/js/docx-preview.min.js') ?>"></script>
<script src="<?= asset('assets/js/ic-docx-preview.js') ?>?v=<?= @filemtime(dirname(__DIR__, 3) . '/public/assets/js/ic-docx-preview.js') ?: 1 ?>"></script>
<link rel="stylesheet" href="<?= asset('assets/css/employer-candidates.css') ?>?v=<?= @filemtime(dirname(__DIR__, 3) . '/public/assets/css/employer-candidates.css') ?: 1 ?>">
<style>
/* Submission states (inline so a cached stylesheet can never leave them unstyled) */
.emp-cover-letter-body { border-left: 0 !important; border: 1px solid #EEF0F4; background: #F8FAFC; border-radius: 12px; padding: 18px 20px; font-size: 14px; line-height: 1.75; color: #334155; white-space: pre-line; }
.emp-card-head-row { display: flex; align-items: flex-start; justify-content: space-between; gap: 12px; margin-bottom: 16px; }
.emp-card-head-row .emp-card-header { margin: 0; }
.emp-state-pill { display: inline-flex; align-items: center; gap: 6px; flex-shrink: 0; height: 24px; padding: 0 10px; border-radius: 9999px; font-size: 11px; font-weight: 700; line-height: 1; white-space: nowrap; }
.emp-state-pill .dot { width: 6px; height: 6px; border-radius: 50%; background: currentColor; }
.emp-state-pill.is-yes { background: #ECFDF5; color: #047857; }
.emp-state-pill.is-no { background: #F1F5F9; color: #64748B; }
.emp-state-pill.is-ai { background: #EEF2FF; color: #4F48E6; }
.emp-state-pill svg { width: 12px; height: 12px; flex-shrink: 0; }
.emp-muted-line { font-size: 12.5px; color: #64748B; line-height: 1.55; margin: 0; }
.emp-muted-line a { color: #4F48E6; font-weight: 700; text-decoration: none; }
a.emp-doc-name { color: #0F172A; text-decoration: none; }
a.emp-doc-name:hover { color: #4F48E6; }
a.emp-doc-dl-btn { display: inline-flex; align-items: center; justify-content: center; text-decoration: none; }
.emp-doc-item { gap: 6px; }
.emp-doc-item .emp-doc-left { flex: 1; min-width: 0; }
.emp-doc-item .emp-doc-name { font-size: 13px !important; font-weight: 600; min-width: 0; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
button.emp-doc-open { border: 0; background: none; padding: 0; font-family: inherit; text-align: left; cursor: pointer; color: #0F172A; }
.emp-doc-view { border: 0; cursor: pointer; }
.emp-doc-item .emp-doc-dl-btn svg { width: 15px; height: 15px; }
button.emp-doc-open:hover { color: #4F48E6; text-decoration: underline; }
.emp-inline-doc { border: 1px solid #EEF0F4; border-radius: 12px; overflow: hidden; background: #F1F5F9; height: 460px; }
.emp-inline-doc iframe { width: 100%; height: 100%; border: 0; display: block; background: #FFFFFF; }
.emp-inline-doc img { width: 100%; height: 100%; object-fit: contain; display: block; background: #FFFFFF; }
.emp-docx-host { height: 100%; overflow: auto; padding: 12px; }
.emp-docx-host .docx-wrapper, .emp-docmodal-body .docx-wrapper { background: transparent !important; padding: 0 !important; }
.emp-docx-host section.docx, .emp-docmodal-body section.docx { box-shadow: 0 1px 4px rgba(15, 23, 42, .12); margin: 0 auto 12px !important; }
.emp-inline-doc-bar { display: flex; align-items: center; justify-content: space-between; gap: 10px; flex-wrap: wrap; margin-top: 12px; }
.emp-mini-btn { display: inline-flex; align-items: center; height: 32px; padding: 0 12px; border-radius: 8px; border: 1px solid #E2E8F0; background: #FFFFFF; font-size: 12.5px; font-weight: 700; color: #334155; text-decoration: none; cursor: pointer; white-space: nowrap; }
.emp-mini-btn:hover { background: #F8FAFC; color: #0F172A; }
.emp-mini-btn.is-primary { background: #4F48E6; border-color: #4F48E6; color: #FFFFFF; }
.emp-mini-btn.is-primary:hover { background: #4338CA; color: #FFFFFF; }
.emp-cl-file { display: flex; align-items: center; gap: 12px; padding: 12px 14px; border: 1px solid #EEF0F4; border-radius: 12px; background: #F8FAFC; flex-wrap: wrap; }
.emp-cl-file-icon { width: 38px; height: 44px; border-radius: 8px; background: #FFFFFF; border: 1px solid #E2E8F0; display: flex; align-items: flex-end; justify-content: center; padding-bottom: 6px; font-size: 9px; font-weight: 800; color: #4F48E6; flex-shrink: 0; }
.emp-cl-file-text { flex: 1; min-width: 160px; display: flex; flex-direction: column; gap: 2px; }
.emp-cl-file-text strong { font-size: 13px; color: #0F172A; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
.emp-cl-file-text span { font-size: 12px; color: #64748B; }
.emp-cl-file-actions { display: flex; gap: 8px; }
.emp-docmodal { position: fixed; inset: 0; z-index: 3000; background: rgba(0, 6, 35, .6); display: flex; align-items: center; justify-content: center; padding: 20px; }
.emp-docmodal-card { width: 100%; max-width: 920px; height: min(88vh, 900px); background: #FFFFFF; border-radius: 18px; display: flex; flex-direction: column; overflow: hidden; box-shadow: 0 24px 60px rgba(0, 0, 0, .3); }
.emp-docmodal-head { display: flex; align-items: center; justify-content: space-between; gap: 12px; padding: 14px 18px; border-bottom: 1px solid #F1F5F9; }
.emp-docmodal-head strong { display: block; font-size: 14.5px; color: #0F172A; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
.emp-docmodal-head span { font-size: 12px; color: #64748B; }
.emp-docmodal-body { flex: 1; min-height: 0; background: #F1F5F9; overflow: auto; position: relative; }
.emp-docmodal-body iframe { width: 100%; height: 100%; border: 0; display: block; background: #FFFFFF; }
.emp-docmodal-body img { max-width: 100%; max-height: 100%; display: block; margin: 0 auto; object-fit: contain; }
.emp-docmodal-close { width: 34px; height: 34px; border-radius: 9px; border: 0; background: #F1F5F9; color: #475569; display: inline-flex; align-items: center; justify-content: center; cursor: pointer; flex-shrink: 0; }
.emp-docmodal-close:hover { background: #E2E8F0; }
@media (max-width: 640px) { .emp-docmodal { padding: 0; } .emp-docmodal-card { height: 100vh; border-radius: 0; } .emp-inline-doc { height: 380px; } }
</style>

<div class="db-shell">

    <!-- ══ EMPLOYER SIDEBAR ══ -->
    <?php 
    $activeNav = 'candidates';
    include dirname(__DIR__, 2) . '/shared/components/employer-sidebar.php'; 
    ?>

    <!-- ══ MAIN CONTENT WRAPPER ══ -->
    <div class="db-main">

        <!-- ══ TOP NAVBAR ══ -->
        <?php include dirname(__DIR__, 2) . '/shared/components/employer-topnav.php'; ?>

        <!-- ══ PAGE CONTENT ══ -->
        <div class="db-content">
            <div class="emp-cand-page" 
                 x-data="{
                     currentStage: '<?= htmlspecialchars($candidate['pipeline_stage'] ?? 'Under Review') ?>',
                     isShortlisted: <?= ($candidate['status_type'] ?? '') === 'shortlisted' ? 'true' : 'false' ?>,
                     isRejected: <?= ($candidate['status_type'] ?? '') === 'rejected' ? 'true' : 'false' ?>,
                     hasApp: <?= !empty($candidate['has_submission']) ? 'true' : 'false' ?>,
                     stageUrl: '<?= htmlspecialchars(url('/employer/candidates/' . (int)($candidate['id'] ?? 1) . '/stage'), ENT_QUOTES) ?>',
                     savingStage: false,
                     viewDoc: null,
                     toastMessage: '',
                     showToast: false,
                     triggerToast(msg) {
                         this.toastMessage = msg;
                         this.showToast = true;
                         setTimeout(() => { this.showToast = false; }, 3000);
                     },
                     /* Pipeline stage is saved on the application and the candidate is notified. */
                     async setStage(stage) {
                         if (this.savingStage) return;
                         const apply = () => { this.currentStage = stage; this.isShortlisted = stage === 'Shortlisted'; this.isRejected = stage === 'Rejected'; };
                         if (!this.hasApp) { apply(); this.triggerToast('Sample profile: stage changes are not saved.'); return; }
                         this.savingStage = true;
                         try {
                             const res = await fetch(this.stageUrl, { method: 'POST', headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' }, body: JSON.stringify({ stage }) });
                             const json = await res.json().catch(() => ({}));
                             if (!res.ok || !json.success) throw new Error(json.error || 'Could not update the stage.');
                             apply();
                             this.triggerToast(stage === 'Rejected' ? 'Application marked as rejected' : 'Moved to ' + stage + '. The candidate has been notified.');
                         } catch (e) {
                             this.triggerToast(e.message);
                         } finally {
                             this.savingStage = false;
                         }
                     },
                     /* Document viewer: PDF, Word (docx-preview) and images open in the page. */
                     openDoc(name, url, ext) {
                         this.viewDoc = { name, url, ext: String(ext || '').toLowerCase() };
                         if (this.viewDoc.ext === 'docx') this.$nextTick(() => window.icRenderDocx && window.icRenderDocx(url, this.$refs.docModalHost, name));
                     },
                     closeDoc() { this.viewDoc = null; }
                 }"
                 @keydown.escape.window="closeDoc()">

                <!-- ── Back Link ── -->
                <div class="emp-profile-back-bar">
                    <a href="<?= url('/employer/candidates') ?>" class="emp-back-link">
                        <svg style="width: 16px; height: 16px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        <span>Back to Applications</span>
                    </a>
                </div>

                <!-- ── 1. Candidate Header Card (Clean Header with Precision SVG Score Gauge) ── -->
                <div class="emp-profile-head-card">
                    <div class="emp-profile-left-meta">
                        <!-- Avatar with Active Dot -->
                        <div class="emp-profile-avatar-box">
                            <img src="<?= htmlspecialchars($candidate['avatar'] ?? 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=160&h=160&fit=crop&crop=faces') ?>" 
                                 alt="<?= htmlspecialchars($candidate['name'] ?? 'Sarah Peters') ?>">
                            <div class="emp-online-status-dot" aria-hidden="true"></div>
                        </div>

                        <!-- Name & Academic Subtitle -->
                        <div class="emp-profile-name-group">
                            <h1 class="emp-profile-cand-name"><?= htmlspecialchars($candidate['name'] ?? 'Sarah Peters') ?></h1>
                            <p class="emp-profile-cand-sub"><?= htmlspecialchars($candidate['student_title'] ?? 'UI/UX Design Student • Class of 2026 • Lagos, NG') ?></p>
                            <span class="emp-pill-active-applicant">ACTIVE APPLICANT</span>
                            <?php // "Certified by" badges from training partners (docs/57-training-partners.md)
                            foreach (\App\Services\PartnerService::certifications((int)($candidate['id'] ?? 0)) as $cert): ?>
                                <span style="display: inline-flex; align-items: center; gap: 5px; margin: 6px 6px 0 0; padding: 3px 10px; border-radius: 999px; background: #000623; color: #FFFFFF; font-size: 11px; font-weight: 700; white-space: nowrap;">
                                    <svg width="12" height="12" style="width: 12px; height: 12px; flex-shrink: 0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                    Certified by <?= htmlspecialchars($cert['partner_name']) ?><?= $cert['course'] ? ' · ' . htmlspecialchars($cert['course']) : '' ?>
                                </span>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Precision Circular SVG Match Score Widget -->
                    <?php 
                        $score = (int)($candidate['match_score'] ?? 95);
                        $radius = 32;
                        $circ = 2 * M_PI * $radius; // ~201.06
                        $dashOffset = $circ - ($circ * $score / 100);
                        $strokeColor = $score >= 90 ? '#059669' : ($score >= 75 ? '#4F48E6' : '#D97706');
                    ?>
                    <div class="emp-score-gauge-wrap" role="img" aria-label="Candidate match score: <?= $score ?>%">
                        <div class="emp-score-gauge-circle">
                            <svg viewBox="0 0 76 76" class="emp-score-gauge-svg">
                                <circle class="emp-score-gauge-bg" cx="38" cy="38" r="<?= $radius ?>" stroke-width="5" fill="none" />
                                <circle class="emp-score-gauge-bar" cx="38" cy="38" r="<?= $radius ?>" stroke-width="5" fill="none"
                                        stroke="<?= $strokeColor ?>"
                                        stroke-dasharray="<?= round($circ, 2) ?>"
                                        stroke-dashoffset="<?= round($dashOffset, 2) ?>" />
                            </svg>
                            <div class="emp-score-gauge-inner">
                                <span class="emp-score-gauge-pct"><?= $score ?>%</span>
                                <span class="emp-score-gauge-lbl">MATCH</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ── 2. Two-Column Grid Content ── -->
                <div class="emp-profile-grid">

                    <!-- ════ LEFT COLUMN (Main Application Data) ════ -->
                    <div class="emp-profile-col">

                        <!-- Card 1: Application Answers (Step 3 Screening Questions) -->
                        <div class="emp-card">
                            <div class="emp-card-header">
                                <svg class="emp-card-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <div>
                                    <h2 class="emp-card-title" style="margin: 0;">Application Answers</h2>
                                    <p style="font-size: 11.5px; color: #64748B; margin: 2px 0 0 0;">Responses to screening questions configured in Step 3</p>
                                </div>
                            </div>

                            <?php 
                            $questions = $candidate['questions'] ?? [
                                ['q' => 'Why are you interested in Flux Technologies?', 'a' => "Flux's commitment to accessible enterprise software aligns perfectly with my thesis project."],
                                ['q' => 'Describe your proudest design project.', 'a' => 'Created a community banking app for unbanked youth in Oakland.'],
                                ['q' => 'What are your primary learning goals?', 'a' => 'I want to master design-to-code handoff workflows and learn how to leverage AI/ML.']
                            ];
                            foreach ($questions as $idx => $qItem): 
                            ?>
                            <div class="emp-answer-item">
                                <h3 class="emp-question-text"><?= htmlspecialchars($qItem['q']) ?></h3>
                                <p class="emp-answer-text <?= $idx === 0 ? 'emp-answer-italic' : '' ?>">
                                    "<?= htmlspecialchars($qItem['a']) ?>"
                                </p>
                            </div>
                            <?php endforeach; ?>
                        </div>

                        <!-- Card 2: Relevant Experience (Candidate Step 1 Configured Fields) -->
                        <?php 
                        $expAnswers = $candidate['relevant_experience_answers'] ?? [
                            ['field' => 'Target Job Title', 'value' => 'UI/UX Design Intern', 'type' => 'Text Input'],
                            ['field' => 'Previous Company / Organization', 'value' => 'TechFlow Solutions (Design Intern)', 'type' => 'Text Input'],
                            ['field' => 'Salary / Stipend Expectation ($/month)', 'value' => '$2,000 / month', 'type' => 'Currency / Number'],
                            ['field' => 'Prior Industry Experience', 'value' => '1 Previous Internship • 2 Years Academic Studio Projects', 'type' => 'Text Input']
                        ];
                        if (!empty($expAnswers)):
                        ?>
                        <div class="emp-card">
                            <div class="emp-card-header">
                                <svg class="emp-card-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                <div>
                                    <h2 class="emp-card-title" style="margin: 0;">Relevant Experience Details</h2>
                                    <p style="font-size: 11.5px; color: #64748B; margin: 2px 0 0 0;">Configured candidate responses submitted in Step 1</p>
                                </div>
                            </div>

                            <div class="emp-exp-response-list">
                                <?php foreach ($expAnswers as $ans): ?>
                                <div class="emp-exp-response-item">
                                    <div class="emp-exp-response-left">
                                        <span class="emp-exp-response-lbl"><?= htmlspecialchars($ans['field']) ?></span>
                                    </div>
                                    <div style="display: flex; align-items: center; gap: 8px;">
                                        <span class="emp-exp-response-val"><?= htmlspecialchars($ans['value']) ?></span>
                                        <?php if (!empty($ans['type'])): ?>
                                            <span class="emp-exp-response-pill"><?= htmlspecialchars($ans['type']) ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <?php endif; ?>

                        <!-- Card 3: Cover Letter (Written Statement) — always shown with its submission state -->
                        <?php $hasCoverText = trim((string)($candidate['cover_letter'] ?? '')) !== ''; $hasCoverFile = !empty($candidate['documents']['cover_letter_url']); ?>
                        <div class="emp-card">
                            <div class="emp-card-head-row">
                                <div class="emp-card-header">
                                    <svg class="emp-card-icon" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                                    </svg>
                                    <div>
                                        <h2 class="emp-card-title" style="margin: 0;">Candidate Statement / Cover Letter</h2>
                                        <p style="font-size: 11.5px; color: #64748B; margin: 2px 0 0 0;">Written candidate introduction and motivation</p>
                                    </div>
                                </div>
                                <span class="emp-state-pill <?= $hasCoverText || $hasCoverFile ? 'is-yes' : 'is-no' ?>"><span class="dot"></span><?= $hasCoverText || $hasCoverFile ? 'Submitted' : 'Not submitted' ?></span>
                            </div>
                            <?php if ($hasCoverText): ?>
                            <!-- pre-line keeps the candidate's line breaks; no nl2br (it would double them) -->
                            <div class="emp-cover-letter-body"><?= htmlspecialchars(trim((string)$candidate['cover_letter'])) ?></div>
                            <?php elseif ($hasCoverFile): $clExt = $candidate['documents']['cover_letter_ext'] ?? ''; $clUrl = $candidate['documents']['cover_letter_url']; ?>
                            <div class="emp-cl-file">
                                <span class="emp-cl-file-icon" aria-hidden="true"><?= htmlspecialchars(strtoupper($clExt ?: 'FILE')) ?></span>
                                <div class="emp-cl-file-text">
                                    <strong><?= htmlspecialchars($candidate['documents']['cover_letter_name']) ?></strong>
                                    <span>Submitted as a file instead of a typed letter</span>
                                </div>
                                <div class="emp-cl-file-actions">
                                    <button type="button" class="emp-mini-btn is-primary" @click="openDoc(<?= htmlspecialchars(json_encode($candidate['documents']['cover_letter_name']), ENT_QUOTES) ?>, <?= htmlspecialchars(json_encode($clUrl), ENT_QUOTES) ?>, '<?= htmlspecialchars($clExt, ENT_QUOTES) ?>')">View cover letter</button>
                                    <a class="emp-mini-btn" href="<?= htmlspecialchars($clUrl) ?>?download=1">Download</a>
                                </div>
                            </div>
<?php else: ?>
                            <p class="emp-muted-line">No cover letter was included with this application.</p>
                            <?php endif; ?>
                        </div>

                        <!-- Card 4: Professional Summary -->
                        <div class="emp-card">
                            <div class="emp-card-head-row" style="margin-bottom: 12px; align-items: center;">
                                <h2 class="emp-card-title" style="margin: 0;">Professional Summary</h2>
                                <?php if (($candidate['summary_source'] ?? '') === 'ai'): ?>
                                <span class="emp-state-pill is-ai">
                                    <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                                    Jenny AI summary
                                </span>
                                <?php endif; ?>
                            </div>
                            <p class="emp-summary-text">
                                <?= htmlspecialchars($candidate['summary'] ?? "Passionate designer focused on creating intuitive digital experiences. Currently exploring the intersection of accessibility and high-end visual design. Strong foundation in design systems and user-centric research methodologies.") ?>
                            </p>
                        </div>

                        <!-- Card 5: Work Experience -->
                        <div class="emp-card">
                            <h2 class="emp-card-title" style="margin-bottom: 20px;">Work Experience</h2>

                            <div class="emp-timeline">
                                <?php 
                                $experiences = $candidate['experience'] ?? [
                                    [
                                        'title' => 'UI Design Intern',
                                        'period' => 'JUN 2026 - AUG 2026',
                                        'company' => 'TechFlow Solutions • Remote',
                                        'desc' => 'Led the redesign of the client dashboard, resulting in a 15% increase in user engagement. Collaborated with engineers to implement a new component library.'
                                    ],
                                    [
                                        'title' => 'Freelance UX Researcher',
                                        'period' => 'JAN 2026 - MAY 2026',
                                        'company' => 'Independent • Hybrid',
                                        'desc' => 'Conducted user interviews and usability testing for local startups. Delivered actionable insights through journey maps and wireframes.'
                                    ]
                                ];
                                foreach ($experiences as $exp): 
                                ?>
                                <div class="emp-timeline-item">
                                    <div class="emp-timeline-dot"></div>
                                    <div class="emp-exp-head">
                                        <span class="emp-exp-title"><?= htmlspecialchars($exp['title']) ?></span>
                                        <?php if (($exp['period'] ?? '') !== ''): ?>
                                        <span class="emp-exp-badge"><?= htmlspecialchars($exp['period']) ?></span>
                                        <?php endif; ?>
                                    </div>
                                    <?php if (($exp['company'] ?? '') !== ''): ?>
                                    <div class="emp-exp-company"><?= htmlspecialchars($exp['company']) ?></div>
                                    <?php endif; ?>
                                    <?php
                                    // CV highlights read as the original description paragraph.
                                    $expDesc = ($exp['desc'] ?? '') !== '' ? $exp['desc']
                                        : implode(' ', array_map(static fn ($h) => rtrim(trim($h), '.') . '.', $exp['highlights'] ?? []));
                                    ?>
                                    <?php if ($expDesc !== ''): ?>
                                    <p class="emp-exp-desc"><?= htmlspecialchars($expDesc) ?></p>
                                    <?php endif; ?>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <!-- Card 6: Application Details -->
                        <div class="emp-card">
                            <h2 class="emp-card-title" style="margin-bottom: 16px;">Application Details</h2>
                            <div class="emp-app-details-grid">
                                <?php if (!empty($candidate['application'])): ?>
                                <div class="emp-app-detail-col">
                                    <span class="emp-detail-lbl">APPLIED FOR</span>
                                    <span class="emp-detail-val"><?= htmlspecialchars($candidate['application']['applied_for']) ?></span>
                                </div>
                                <div class="emp-app-detail-col">
                                    <span class="emp-detail-lbl">SUBMITTED</span>
                                    <span class="emp-detail-val"><?= htmlspecialchars($candidate['application']['submitted']) ?></span>
                                </div>
                                <div class="emp-app-detail-col">
                                    <span class="emp-detail-lbl">APPLICATION</span>
                                    <span class="emp-detail-val">#<?= (int)$candidate['application']['id'] ?> &middot; <?= htmlspecialchars($candidate['application']['status']) ?></span>
                                </div>
                                <?php endif; ?>
                                <div class="emp-app-detail-col">
                                    <span class="emp-detail-lbl">AVAILABILITY</span>
                                    <span class="emp-detail-val"><?= htmlspecialchars($candidate['availability'] ?? 'Immediate Start') ?></span>
                                </div>
                                <div class="emp-app-detail-col">
                                    <span class="emp-detail-lbl">RELOCATION</span>
                                    <span class="emp-detail-val"><?= htmlspecialchars($candidate['relocation'] ?? 'Open to Relocate') ?></span>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- ════ RIGHT COLUMN (Sidebar / Metadata) ════ -->
                    <div class="emp-profile-col">

                        <!-- Card 1: Pipeline Stage -->
                        <div class="emp-pipeline-card">
                            <div class="emp-pipeline-title-row">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 11l7-7 7 7M5 19l7-7 7 7" />
                                </svg>
                                <h2 class="emp-pipeline-title">Pipeline Stage</h2>
                            </div>

                            <div class="emp-pipeline-options">
                                <?php 
                                $stages = ['Under Review', 'Shortlisted', 'Interviewing', 'Offer Extended'];
                                foreach ($stages as $stage): 
                                ?>
                                <button type="button" 
                                        class="emp-pipeline-btn" 
                                        :class="{ 'is-active': currentStage === '<?= $stage ?>' }"
                                        @click="setStage('<?= $stage ?>')" :disabled="savingStage">
                                    <div class="emp-pipeline-radio">
                                        <div class="emp-pipeline-radio-inner"></div>
                                    </div>
                                    <span><?= $stage ?></span>
                                </button>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <!-- Card 2: Documents & Links -->
                        <div class="emp-card">
                            <h2 class="emp-card-title" style="margin-bottom: 14px;">Documents & Links</h2>

                            <!-- Resume Item -->
                            <div class="emp-doc-item">
                                <div class="emp-doc-left">
                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <?php if (!empty($candidate['documents']['resume_url'])): ?>
                                    <button type="button" class="emp-doc-name emp-doc-open" @click="openDoc(<?= htmlspecialchars(json_encode($candidate['documents']['resume_name']), ENT_QUOTES) ?>, <?= htmlspecialchars(json_encode($candidate['documents']['resume_url']), ENT_QUOTES) ?>, '<?= htmlspecialchars($candidate['documents']['resume_ext'] ?? '', ENT_QUOTES) ?>')"><?= htmlspecialchars($candidate['documents']['resume_name']) ?></button>
                                    <?php else: ?>
                                    <span class="emp-doc-name"><?= htmlspecialchars($candidate['documents']['resume_name'] ?? 'Sarah_Peters_Resume.pdf') ?></span>
                                    <?php endif; ?>
                                </div>
                                <?php if (!empty($candidate['documents']['resume_url'])): ?>
                                <button type="button" class="emp-doc-dl-btn emp-doc-view" aria-label="View resume" @click="openDoc(<?= htmlspecialchars(json_encode($candidate['documents']['resume_name']), ENT_QUOTES) ?>, <?= htmlspecialchars(json_encode($candidate['documents']['resume_url']), ENT_QUOTES) ?>, '<?= htmlspecialchars($candidate['documents']['resume_ext'] ?? '', ENT_QUOTES) ?>')"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg></button>
                                <a class="emp-doc-dl-btn" href="<?= htmlspecialchars($candidate['documents']['resume_url']) ?>?download=1" aria-label="Download resume">
                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                    </svg>
                                </a>
                                <?php else: ?>
                                <button type="button" class="emp-doc-dl-btn" aria-label="Download resume" @click="triggerToast('Sample profile: no resume file on record yet.')">
                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                    </svg>
                                </button>
                                <?php endif; ?>
                            </div>

                            <!-- Cover Letter Document Item (if attached as file) -->
                            <?php if (!empty($candidate['documents']['cover_letter_name'])): ?>
                            <div class="emp-doc-item" style="margin-top: 10px;">
                                <div class="emp-doc-left">
                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                    </svg>
                                    <?php if (!empty($candidate['documents']['cover_letter_url'])): ?>
                                    <button type="button" class="emp-doc-name emp-doc-open" @click="openDoc(<?= htmlspecialchars(json_encode($candidate['documents']['cover_letter_name']), ENT_QUOTES) ?>, <?= htmlspecialchars(json_encode($candidate['documents']['cover_letter_url']), ENT_QUOTES) ?>, '<?= htmlspecialchars($candidate['documents']['cover_letter_ext'] ?? '', ENT_QUOTES) ?>')"><?= htmlspecialchars($candidate['documents']['cover_letter_name']) ?></button>
                                    <?php else: ?>
                                    <span class="emp-doc-name"><?= htmlspecialchars($candidate['documents']['cover_letter_name']) ?></span>
                                    <?php endif; ?>
                                </div>
                                <?php if (!empty($candidate['documents']['cover_letter_url'])): ?>
                                <button type="button" class="emp-doc-dl-btn emp-doc-view" aria-label="View cover letter" @click="openDoc(<?= htmlspecialchars(json_encode($candidate['documents']['cover_letter_name']), ENT_QUOTES) ?>, <?= htmlspecialchars(json_encode($candidate['documents']['cover_letter_url']), ENT_QUOTES) ?>, '<?= htmlspecialchars($candidate['documents']['cover_letter_ext'] ?? '', ENT_QUOTES) ?>')"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg></button>
                                <a class="emp-doc-dl-btn" href="<?= htmlspecialchars($candidate['documents']['cover_letter_url']) ?>?download=1" aria-label="Download cover letter">
                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                    </svg>
                                </a>
                                <?php endif; ?>
                            </div>
                            <?php endif; ?>

                            <?php if (!empty($candidate['documents']['portfolio_file_url'])): ?>
                            <div class="emp-doc-item" style="margin-top: 10px;">
                                <div class="emp-doc-left">
                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                    </svg>
                                    <button type="button" class="emp-doc-name emp-doc-open" @click="openDoc(<?= htmlspecialchars(json_encode($candidate['documents']['portfolio_name']), ENT_QUOTES) ?>, <?= htmlspecialchars(json_encode($candidate['documents']['portfolio_file_url']), ENT_QUOTES) ?>, '<?= htmlspecialchars($candidate['documents']['portfolio_ext'] ?? '', ENT_QUOTES) ?>')"><?= htmlspecialchars($candidate['documents']['portfolio_name']) ?></button>
                                </div>
                                <button type="button" class="emp-doc-dl-btn emp-doc-view" aria-label="View attachment" @click="openDoc(<?= htmlspecialchars(json_encode($candidate['documents']['portfolio_name']), ENT_QUOTES) ?>, <?= htmlspecialchars(json_encode($candidate['documents']['portfolio_file_url']), ENT_QUOTES) ?>, '<?= htmlspecialchars($candidate['documents']['portfolio_ext'] ?? '', ENT_QUOTES) ?>')"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg></button>
                                <a class="emp-doc-dl-btn" href="<?= htmlspecialchars($candidate['documents']['portfolio_file_url']) ?>?download=1" aria-label="Download attachment">
                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                    </svg>
                                </a>
                            </div>
                            <?php endif; ?>

                            <!-- Links Row -->
                            <div class="emp-links-row" style="margin-top: 14px;">
                                <?php if (!empty($candidate['documents']['portfolio_url'])): ?>
                                <a href="<?= htmlspecialchars($candidate['documents']['portfolio_url']) ?>" 
                                   target="_blank" 
                                   rel="noopener noreferrer" 
                                   class="emp-link-pill">
                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                                    </svg>
                                    <span>Portfolio</span>
                                </a>
                                <?php endif; ?>

                                <?php if (!empty($candidate['documents']['figma_url'])): ?>
                                <a href="<?= htmlspecialchars($candidate['documents']['figma_url']) ?>" target="_blank" rel="noopener noreferrer" class="emp-link-pill">
                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                                    </svg>
                                    <span>Figma</span>
                                </a>
                                <?php endif; ?>

                                <?php if (!empty($candidate['documents']['linkedin_url'])): ?>
                                <a href="<?= htmlspecialchars($candidate['documents']['linkedin_url']) ?>" 
                                   target="_blank" 
                                   rel="noopener noreferrer" 
                                   class="emp-link-pill">
                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                                    </svg>
                                    <span>LinkedIn</span>
                                </a>
                                <?php endif; ?>

                                <?php if (!empty($candidate['documents']['github_url'])): ?>
                                <a href="<?= htmlspecialchars($candidate['documents']['github_url']) ?>" 
                                   target="_blank" 
                                   rel="noopener noreferrer" 
                                   class="emp-link-pill">
                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                                    </svg>
                                    <span>GitHub</span>
                                </a>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Card 3: Contact -->
                        <div class="emp-card">
                            <h2 class="emp-card-title" style="margin-bottom: 16px;">Contact</h2>

                            <div class="emp-contact-item">
                                <div class="emp-contact-icon-wrap">
                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div>
                                    <div class="emp-contact-lbl">EMAIL</div>
                                    <div class="emp-contact-val"><?= htmlspecialchars($candidate['contact']['email'] ?? 'sarahp@university.edu') ?></div>
                                </div>
                            </div>

                            <div class="emp-contact-item">
                                <div class="emp-contact-icon-wrap">
                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                    </svg>
                                </div>
                                <div>
                                    <div class="emp-contact-lbl">PHONE</div>
                                    <div class="emp-contact-val"><?= htmlspecialchars($candidate['contact']['phone'] ?? '+1 (415) 555-0198') ?></div>
                                </div>
                            </div>
                        </div>

                        <!-- Card 4: Education -->
                        <div class="emp-card">
                            <div class="emp-card-header">
                                <svg class="emp-card-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path d="M12 14l9-5-9-5-9 5 9 5z" />
                                    <path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5zm0 0v6" />
                                </svg>
                                <h2 class="emp-card-title">Education</h2>
                            </div>

                            <h3 class="emp-edu-title"><?= htmlspecialchars($candidate['education']['degree'] ?? 'BFA in Interaction Design') ?></h3>
                            <div class="emp-edu-school"><?= htmlspecialchars($candidate['education']['school'] ?? 'California College of the Arts') ?></div>

                            <?php $eduGrade = $candidate['education']['grade'] ?? 'FIRST CLASS'; $eduGpa = $candidate['education']['gpa'] ?? 'GPA 3.8/4.0'; $eduGrad = $candidate['education']['graduation'] ?? 'Expected Graduation: May 2027'; ?>
                            <?php if ($eduGrade !== '' || $eduGpa !== ''): ?>
                            <div class="emp-edu-badges">
                                <?php if ($eduGrade !== ''): ?><span class="emp-badge-grade"><?= htmlspecialchars($eduGrade) ?></span><?php endif; ?>
                                <?php if ($eduGpa !== ''): ?><span class="emp-edu-gpa"><?= htmlspecialchars($eduGpa) ?></span><?php endif; ?>
                            </div>
                            <?php endif; ?>

                            <?php if ($eduGrad !== ''): ?>
                            <p class="emp-edu-sub"><?= htmlspecialchars($eduGrad) ?></p>
                            <?php endif; ?>
                        </div>

                        <!-- Card 5: Skills & Interests -->
                        <div class="emp-card">
                            <div class="emp-card-header">
                                <svg class="emp-card-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                                </svg>
                                <h2 class="emp-card-title">Skills & Interests</h2>
                            </div>

                            <div class="emp-skills-wrap">
                                <?php 
                                $skills = $candidate['skills'] ?? ['UI/UX', 'Figma', 'Adobe XD', 'AI/ML', 'Marketing', 'React.js', 'Tailwind CSS'];
                                foreach ($skills as $skill): 
                                ?>
                                <span class="emp-skill-pill"><?= htmlspecialchars($skill) ?></span>
                                <?php endforeach; ?>
                            </div>
                        </div>

                    </div>

                </div>

                <!-- ── 3. Bottom Review & Decision Action Bar (Recruiter Decision Workflow) ── -->
                <div class="emp-profile-bottom-decision-bar">
                    <div class="emp-decision-bar-left">
                        <div class="emp-decision-badge">
                            <svg style="width: 20px; height: 20px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="emp-decision-title">Advance Candidate in Pipeline</h3>
                            <p class="emp-decision-sub">You've completed the applicant dossier review. Choose an action to advance or reject this candidate.</p>
                        </div>
                    </div>

                    <div class="emp-decision-actions-row">
                        <!-- Reject Button -->
                        <button type="button" 
                                class="emp-btn-decision-reject"
                                @click="setStage(isRejected ? 'Under Review' : 'Rejected')" :disabled="savingStage">
                            <svg style="width: 15px; height: 15px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            <span x-text="isRejected ? 'Application Rejected' : 'Reject Application'">Reject Application</span>
                        </button>

                        <!-- Message Button -->
                        <a href="<?= url('/employer/messages/' . ($candidate['id'] ?? 1)) ?>" class="emp-btn-decision-message">
                            <svg style="width: 15px; height: 15px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                            </svg>
                            <span>Message Candidate</span>
                        </a>

                        <!-- Shortlist Button -->
                        <button type="button" 
                                class="emp-btn-decision-shortlist"
                                :class="{ 'is-active': isShortlisted }"
                                @click="setStage(isShortlisted ? 'Under Review' : 'Shortlisted')" :disabled="savingStage">
                            <svg style="width: 15px; height: 15px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                            </svg>
                            <span x-text="isShortlisted ? 'Candidate Shortlisted' : 'Shortlist Candidate'">Shortlist Candidate</span>
                        </button>

                        <!-- Schedule Interview CTA Button -->
                        <a href="<?= url('/employer/interviews/schedule/' . ($candidate['id'] ?? 1)) ?>" class="emp-btn-decision-schedule">
                            <svg style="width: 15px; height: 15px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span>Schedule Interview</span>
                            <svg style="width: 14px; height: 14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Document viewer: PDF, Word and images -->
                <div class="emp-docmodal" x-show="viewDoc" x-cloak style="display: none;" @click.self="closeDoc()" role="dialog" aria-modal="true" aria-label="Document preview">
                    <div class="emp-docmodal-card">
                        <div class="emp-docmodal-head">
                            <div style="min-width: 0;">
                                <strong x-text="viewDoc ? viewDoc.name : ''"></strong>
                                <span x-text="viewDoc ? (viewDoc.ext.toUpperCase() + ' document') : ''"></span>
                            </div>
                            <div style="display: flex; gap: 8px; align-items: center;">
                                <a class="emp-mini-btn" :href="viewDoc ? viewDoc.url + '?download=1' : '#'">Download</a>
                                <button type="button" class="emp-docmodal-close" @click="closeDoc()" aria-label="Close preview">
                                    <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </div>
                        </div>
                        <div class="emp-docmodal-body">
                            <template x-if="viewDoc && viewDoc.ext === 'pdf'">
                                <iframe :src="viewDoc.url + '#toolbar=0&navpanes=0'" title="Document preview"></iframe>
                            </template>
                            <template x-if="viewDoc && ['png', 'jpg', 'jpeg', 'webp'].includes(viewDoc.ext)">
                                <img :src="viewDoc.url" :alt="viewDoc.name">
                            </template>
                            <div x-show="viewDoc && viewDoc.ext === 'docx'" x-ref="docModalHost" style="padding: 12px;"></div>
                            <template x-if="viewDoc && !['pdf', 'docx', 'png', 'jpg', 'jpeg', 'webp'].includes(viewDoc.ext)">
                                <p class="emp-muted-line" style="padding: 40px 20px; text-align: center;">This file type cannot be previewed in the browser. Use Download to open it.</p>
                            </template>
                        </div>
                    </div>
                </div>

                <!-- Toast Notification -->
                <div x-show="showToast" 
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 transform translate-y-4"
                     x-transition:enter-end="opacity-100 transform translate-y-0"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 transform translate-y-0"
                     x-transition:leave-end="opacity-0 transform translate-y-4"
                     style="position: fixed; bottom: 28px; right: 28px; background: #000623; color: #FFFFFF; padding: 12px 20px; border-radius: 12px; font-size: 13.5px; font-weight: 600; box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2); z-index: 10000; display: none;"
                     x-text="toastMessage">
                </div>

            </div>
        </div>

    </div>

</div>
