<?php
declare(strict_types=1);
/**
 * View: AI Cover Letter Generator (Version 2.0 / Phase 5 AI Feature)
 * Route: /candidate/ai-assistant/cover-letter
 * Layout: dashboard
 * 
 * Complies with docs/01-roadmap.md (Phase 5 Enterprise Features)
 * and .agents/rules/agent_mistakes_preventions.md.
 * 
 * @var array $candidate     Candidate profile
 * @var array $targetJobs    Curated internship opportunities
 * @var array $initialLetter Pre-generated initial cover letter
 */

$candName     = $candidate['name'] ?? 'Sarah Peters';
$candSchool   = $candidate['school'] ?? 'University of Lagos (UNILAG)';
$candDegree   = $candidate['degree'] ?? 'B.Sc Systems Engineering';
$candSkills   = $candidate['skills'] ?? ['Figma', 'UI Design', 'Wireframing', 'User Research', 'Prototyping'];
?>

<style>
/* ==========================================================================
   AI COVER LETTER GENERATOR — DESIGN SYSTEM STYLES
   ========================================================================== */

.cl-container {
    padding: 0 0 40px 0;
    max-width: 100%;
    box-sizing: border-box;
}

/* Page Header */
.cl-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    margin-bottom: 24px;
    flex-wrap: wrap;
}

.cl-back-link {
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

.cl-back-link:hover {
    color: #3730A3;
}

.cl-title {
    font-size: 24px;
    font-weight: 800;
    color: #000623;
    letter-spacing: -0.02em;
    margin: 0 0 4px;
}

.cl-sub {
    font-size: 13.5px;
    color: #64748B;
    margin: 0;
}

/* 2-Column Responsive Split */
.cl-workspace-grid {
    display: grid;
    grid-template-columns: 420px minmax(0, 1fr);
    gap: 24px;
    width: 100%;
    box-sizing: border-box;
    align-items: start;
}

@media (max-width: 1100px) {
    .cl-workspace-grid {
        grid-template-columns: minmax(0, 1fr) !important;
    }
}

/* Section Card Standard: Minimum 24px 28px padding */
.cl-card {
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
    .cl-card {
        padding: 18px 16px !important;
    }
}

.cl-card-header {
    margin-bottom: 20px;
    padding-bottom: 14px;
    border-bottom: 1px solid #F1F5F9;
}

.cl-card-title {
    font-size: 16px;
    font-weight: 800;
    color: #000623;
    margin: 0 0 2px;
}

.cl-card-desc {
    font-size: 12.5px;
    color: #64748B;
    margin: 0;
}

/* Form Controls */
.cl-field-group {
    margin-bottom: 18px;
}

.cl-label {
    display: block;
    font-size: 12.5px;
    font-weight: 700;
    color: #334155;
    margin-bottom: 8px;
}

.cl-input {
    width: 100%;
    height: 44px;
    padding: 10px 14px;
    background: #FFFFFF;
    border: 1px solid #CBD5E1;
    border-radius: 10px;
    font-size: 13.5px;
    color: #0F172A;
    box-sizing: border-box;
    min-width: 0;
    outline: none;
    transition: border-color 0.15s ease, box-shadow 0.15s ease;
}

.cl-input:focus {
    border-color: #4F48E6;
    box-shadow: 0 0 0 3px rgba(79, 72, 230, 0.1);
}

/* Quick Selectable Role Cards */
.cl-role-pills {
    display: flex;
    flex-direction: column;
    gap: 8px;
    margin-bottom: 18px;
}

.cl-role-pill {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 10px 12px;
    border: 1px solid #E2E8F0;
    border-radius: 10px;
    background: #F8FAFC;
    cursor: pointer;
    transition: all 0.15s ease;
}

.cl-role-pill:hover {
    border-color: #CBD5E1;
    background: #FFFFFF;
}

.cl-role-pill.active {
    border-color: #4F48E6;
    background: #EEF2FF;
}

.cl-role-pill-meta {
    display: flex;
    align-items: center;
    gap: 10px;
}

.cl-role-logo {
    width: 28px;
    height: 28px;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #FFFFFF;
    font-size: 11px;
    font-weight: 800;
    flex-shrink: 0;
}

.cl-role-title {
    font-size: 12.5px;
    font-weight: 700;
    color: #0F172A;
    line-height: 1.2;
}

.cl-role-sub {
    font-size: 11.5px;
    color: #64748B;
}

/* Tone Selector Grid */
.cl-tone-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 8px;
    margin-bottom: 20px;
}

.cl-tone-card {
    border: 1px solid #E2E8F0;
    border-radius: 10px;
    padding: 10px 12px;
    background: #FFFFFF;
    cursor: pointer;
    transition: all 0.15s ease;
    display: flex;
    flex-direction: column;
    gap: 2px;
}

.cl-tone-card:hover {
    border-color: #CBD5E1;
    background: #F8FAFC;
}

.cl-tone-card.active {
    border-color: #4F48E6;
    background: #EEF2FF;
}

.cl-tone-title {
    font-size: 12.5px;
    font-weight: 700;
    color: #0F172A;
}

.cl-tone-desc {
    font-size: 11px;
    color: #64748B;
    line-height: 1.3;
}

/* Primary Action Button */
.cl-primary-btn {
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    gap: 8px !important;
    width: 100% !important;
    height: 46px;
    background: #4F48E6;
    color: #FFFFFF;
    border: 1px solid #4F48E6;
    border-radius: 12px;
    font-size: 14px;
    font-weight: 700;
    cursor: pointer;
    box-shadow: 0 4px 14px rgba(79, 72, 230, 0.25);
    transition: all 0.2s ease;
    white-space: nowrap !important;
    flex-shrink: 0 !important;
}

.cl-primary-btn:hover {
    background: #4338CA;
    border-color: #4338CA;
    transform: translateY(-1px);
}

.cl-primary-btn:disabled {
    opacity: 0.65;
    cursor: not-allowed;
    transform: none;
}

/* Paper Preview Document */
.cl-preview-paper {
    background: #FFFFFF;
    border: 1px solid #E2E8F0;
    border-radius: 16px;
    padding: 32px 36px;
    box-shadow: 0 4px 20px -2px rgba(0, 6, 35, 0.04);
    box-sizing: border-box;
    width: 100%;
    min-width: 0;
}

@media (max-width: 640px) {
    .cl-preview-paper {
        padding: 20px 16px !important;
    }
}

.cl-preview-toolbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    flex-wrap: wrap;
    margin-bottom: 24px;
    padding-bottom: 16px;
    border-bottom: 1px solid #F1F5F9;
}

.cl-meta-pills {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
}

.cl-pill {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-size: 11.5px;
    font-weight: 700;
    padding: 3px 10px;
    border-radius: 9999px;
    background: #F1F5F9;
    color: #475569;
    border: 1px solid #E2E8F0;
    white-space: nowrap !important;
    flex-shrink: 0 !important;
}

.cl-pill.match {
    background: #ECFDF5;
    color: #059669;
    border-color: #A7F3D0;
}

.cl-actions {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
}

.cl-tool-btn {
    display: inline-flex !important;
    align-items: center !important;
    gap: 6px !important;
    padding: 7px 12px;
    background: #FFFFFF;
    border: 1px solid #CBD5E1;
    border-radius: 8px;
    font-size: 12px;
    font-weight: 600;
    color: #334155;
    cursor: pointer;
    transition: all 0.15s ease;
    white-space: nowrap !important;
    flex-shrink: 0 !important;
    text-decoration: none;
}

.cl-tool-btn:hover {
    background: #F8FAFC;
    border-color: #94A3B8;
}

.cl-tool-btn.copied {
    background: #ECFDF5;
    border-color: #10B981;
    color: #059669;
}

/* Document Typography */
.cl-doc-body {
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    color: #1E293B;
    font-size: 14px;
    line-height: 1.75;
}

.cl-doc-salutation {
    font-weight: 700;
    margin-bottom: 16px;
    color: #0F172A;
}

.cl-doc-para {
    margin-bottom: 16px;
    color: #334155;
}

.cl-doc-signoff {
    margin-top: 24px;
    font-weight: 700;
    color: #0F172A;
    white-space: pre-line;
}

.cl-textarea-edit {
    width: 100%;
    min-height: 380px;
    padding: 16px;
    border: 1px solid #CBD5E1;
    border-radius: 12px;
    font-family: inherit;
    font-size: 14px;
    line-height: 1.75;
    color: #1E293B;
    background: #FAFAFC;
    box-sizing: border-box;
    resize: vertical;
    outline: none;
}

.cl-textarea-edit:focus {
    border-color: #4F48E6;
    background: #FFFFFF;
    box-shadow: 0 0 0 3px rgba(79, 72, 230, 0.1);
}
</style>

<script>
window.__COVER_LETTER_INIT__ = <?= json_encode([
    'company'      => $targetJobs[0]['company'],
    'role'         => $targetJobs[0]['role'],
    'location'     => $targetJobs[0]['location'],
    'activeJobId'  => (int)$targetJobs[0]['id'],
    'letter'       => $initialLetter,
    'editableText' => $initialLetter['full_letter'],
    'generateUrl'  => url('/candidate/ai-assistant/cover-letter/generate')
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?>;

function coverLetterComponent() {
    const init = window.__COVER_LETTER_INIT__ || {};
    return {
        company: init.company || 'Paystack',
        role: init.role || 'UI/UX Design Intern',
        location: init.location || 'Lagos, Nigeria',
        tone: 'professional',
        generating: false,
        copied: false,
        isEditing: false,
        activeJobId: init.activeJobId || 1,
        letter: init.letter || {},
        editableText: init.editableText || '',

        selectJob(job) {
            this.activeJobId = job.id;
            this.company = job.company;
            this.role = job.role;
            this.location = job.location;
            this.generate();
        },

        setTone(t) {
            this.tone = t;
            this.generate();
        },

        async generate() {
            this.generating = true;
            try {
                const resp = await fetch(init.generateUrl, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        company: this.company,
                        role: this.role,
                        location: this.location,
                        tone: this.tone
                    })
                });
                const data = await resp.json();
                if (data.success && data.letter) {
                    this.letter = data.letter;
                    this.editableText = data.letter.full_letter;
                    window.dispatchEvent(new CustomEvent('show-toast', {
                        detail: { text: 'Cover letter updated with ' + this.tone + ' tone!', type: 'success' }
                    }));
                }
            } catch (e) {
                console.error('Error generating letter', e);
            } finally {
                this.generating = false;
            }
        },

        copyToClipboard() {
            const textToCopy = this.isEditing ? this.editableText : this.letter.full_letter;
            navigator.clipboard.writeText(textToCopy).then(() => {
                this.copied = true;
                setTimeout(() => { this.copied = false; }, 2500);
                window.dispatchEvent(new CustomEvent('show-toast', {
                    detail: { text: 'Cover letter copied to clipboard!', type: 'success' }
                }));
            });
        },

        downloadText() {
            const text = this.isEditing ? this.editableText : this.letter.full_letter;
            const blob = new Blob([text], { type: 'text/plain;charset=utf-8' });
            const u = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = u;
            a.download = 'Cover_Letter_' + this.company.replace(/[^a-zA-Z0-9]/g, '_') + '.txt';
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            URL.revokeObjectURL(u);
        }
    };
}
</script>

<div class="db-shell" x-data="coverLetterComponent()">

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
            <div class="cl-container">

                <!-- ── 1. Page Header ── -->
                <div class="cl-header">
                    <div>
                        <a href="<?= url('/candidate/ai-assistant') ?>" class="cl-back-link">
                            <svg style="width: 14px; height: 14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            <span>Back to AI Career Assistant</span>
                        </a>
                        <h1 class="cl-title">AI Cover Letter Generator</h1>
                        <p class="cl-sub">Generate tailored, ATS-ready cover letters for Nigerian and global tech internships.</p>
                    </div>

                    <div style="display: flex; align-items: center; gap: 10px;">
                        <span class="cl-pill match">
                            <svg style="width: 12px; height: 12px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span>Jenny AI Model Active</span>
                        </span>
                    </div>
                </div>

                <!-- ── 2. Workspace Grid ── -->
                <div class="cl-workspace-grid">

                    <!-- Left Column: Generator Controls -->
                    <div class="cl-card">
                        <div class="cl-card-header">
                            <h2 class="cl-card-title">Target Application</h2>
                            <p class="cl-card-desc">Select an active opportunity or customize company parameters.</p>
                        </div>

                        <!-- Quick Role Selection -->
                        <div class="cl-field-group">
                            <label class="cl-label">Quick-Pick Verified Openings</label>
                            <div class="cl-role-pills">
                                <?php foreach ($targetJobs as $job): ?>
                                <div class="cl-role-pill" :class="{ 'active': activeJobId === <?= $job['id'] ?> }" @click="selectJob(<?= htmlspecialchars(json_encode($job), ENT_QUOTES, 'UTF-8') ?>)">
                                    <div class="cl-role-pill-meta">
                                        <div class="cl-role-logo" style="background: <?= $job['logo_bg'] ?>;">
                                            <?= $job['initials'] ?>
                                        </div>
                                        <div>
                                            <div class="cl-role-title"><?= htmlspecialchars($job['role']) ?></div>
                                            <div class="cl-role-sub"><?= htmlspecialchars($job['company']) ?> &bull; <?= htmlspecialchars($job['location']) ?></div>
                                        </div>
                                    </div>
                                    <svg style="width: 14px; height: 14px; color: #4F48E6; <?= $job['id'] === $targetJobs[0]['id'] ? '' : 'display: none;' ?>" x-show="activeJobId === <?= $job['id'] ?>" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <!-- Custom Inputs -->
                        <div class="cl-field-group">
                            <label class="cl-label" for="cl-company">Company Name</label>
                            <input type="text" id="cl-company" class="cl-input" x-model="company" placeholder="e.g. Paystack, Interswitch, Moniepoint">
                        </div>

                        <div class="cl-field-group">
                            <label class="cl-label" for="cl-role">Target Role Title</label>
                            <input type="text" id="cl-role" class="cl-input" x-model="role" placeholder="e.g. UI/UX Design Intern">
                        </div>

                        <!-- Tone Grid -->
                        <div class="cl-field-group">
                            <label class="cl-label">Writing Tone &amp; Style</label>
                            <div class="cl-tone-grid">
                                <div class="cl-tone-card" :class="{ 'active': tone === 'professional' }" @click="setTone('professional')">
                                    <span class="cl-tone-title">Professional</span>
                                    <span class="cl-tone-desc">Polished, structured, balanced.</span>
                                </div>
                                <div class="cl-tone-card" :class="{ 'active': tone === 'confident' }" @click="setTone('confident')">
                                    <span class="cl-tone-title">Confident &amp; Bold</span>
                                    <span class="cl-tone-desc">Direct, value-driven, proactive.</span>
                                </div>
                                <div class="cl-tone-card" :class="{ 'active': tone === 'technical' }" @click="setTone('technical')">
                                    <span class="cl-tone-title">Technical</span>
                                    <span class="cl-tone-desc">Architecture, metrics, test suites.</span>
                                </div>
                                <div class="cl-tone-card" :class="{ 'active': tone === 'enthusiastic' }" @click="setTone('enthusiastic')">
                                    <span class="cl-tone-title">Enthusiastic</span>
                                    <span class="cl-tone-desc">High passion, mission alignment.</span>
                                </div>
                            </div>
                        </div>

                        <!-- Generate Button -->
                        <button type="button" class="cl-primary-btn" @click="generate()" :disabled="generating">
                            <svg x-show="!generating" style="width: 16px; height: 16px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                            <svg x-show="generating" style="width: 16px; height: 16px; animation: spin 1s linear infinite;" fill="none" viewBox="0 0 24 24">
                                <circle style="opacity: 0.25;" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path style="opacity: 0.75;" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                            </svg>
                            <span x-text="generating ? 'Generating Letter...' : 'Regenerate Cover Letter'"></span>
                        </button>
                    </div>

                    <!-- Right Column: Live Paper Preview -->
                    <div class="cl-preview-paper">
                        <div class="cl-preview-toolbar">
                            <div class="cl-meta-pills">
                                <span class="cl-pill match">
                                    <svg style="width: 12px; height: 12px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                    <span>ATS Score: 96%</span>
                                </span>
                                <span class="cl-pill">
                                    <span x-text="letter.word_count"><?= $initialLetter['word_count'] ?></span> Words
                                </span>
                                <span class="cl-pill">
                                    <span x-text="'~' + letter.read_time_min + ' min read'"><?= '~' . $initialLetter['read_time_min'] . ' min read' ?></span>
                                </span>
                            </div>

                            <div class="cl-actions">
                                <!-- In-place edit toggle -->
                                <button type="button" class="cl-tool-btn" @click="isEditing = !isEditing">
                                    <svg style="width: 13px; height: 13px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                    <span x-text="isEditing ? 'Done Editing' : 'Edit In-Place'"></span>
                                </button>

                                <!-- Copy button with animated checkmark feedback -->
                                <button type="button" class="cl-tool-btn" :class="{ 'copied': copied }" @click="copyToClipboard()">
                                    <svg x-show="!copied" style="width: 13px; height: 13px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/>
                                    </svg>
                                    <svg x-show="copied" style="width: 13px; height: 13px; color: #059669;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    <span x-text="copied ? 'Copied!' : 'Copy Letter'"></span>
                                </button>

                                <!-- Download TXT -->
                                <button type="button" class="cl-tool-btn" @click="downloadText()">
                                    <svg style="width: 13px; height: 13px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                    </svg>
                                    <span>Download</span>
                                </button>
                            </div>
                        </div>

                        <!-- In-place edit textarea -->
                        <div x-show="isEditing" style="display: none;">
                            <textarea class="cl-textarea-edit" x-model="editableText" aria-label="Edit cover letter content"></textarea>
                        </div>

                        <!-- Rendered Document View -->
                        <div x-show="!isEditing" class="cl-doc-body">
                            <div class="cl-doc-salutation" x-text="letter.salutation"><?= htmlspecialchars($initialLetter['salutation']) ?></div>
                            <template x-for="(para, pIdx) in letter.paragraphs" :key="pIdx">
                                <p class="cl-doc-para" x-text="para"></p>
                            </template>
                            <div class="cl-doc-signoff" x-text="letter.signoff" style="white-space: pre-line;"><?= htmlspecialchars($initialLetter['signoff']) ?></div>
                        </div>

                        <!-- Highlighted Matched Skills Shelf -->
                        <div style="margin-top: 28px; padding-top: 16px; border-top: 1px solid #F1F5F9; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px;">
                            <div style="font-size: 12px; font-weight: 700; color: #64748B;">
                                AI Verified Keywords Integrated:
                            </div>
                            <div style="display: flex; gap: 6px; flex-wrap: wrap;">
                                <template x-for="(sk, sIdx) in letter.matched_skills" :key="sIdx">
                                    <span style="font-size: 11px; font-weight: 700; padding: 2px 8px; border-radius: 6px; background: #EEF2FF; color: #4F48E6; border: 1px solid #C7D2FE;" x-text="sk"></span>
                                </template>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </main>

        <!-- Footer -->
        <?php include dirname(__DIR__, 2) . '/shared/components/db-footer.php'; ?>

    </div>
</div>
