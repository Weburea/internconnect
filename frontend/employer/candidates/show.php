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

<link rel="stylesheet" href="<?= asset('assets/css/employer-candidates.css') ?>">

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
                     toastMessage: '',
                     showToast: false,
                     triggerToast(msg) {
                         this.toastMessage = msg;
                         this.showToast = true;
                         setTimeout(() => { this.showToast = false; }, 3000);
                     }
                 }">

                <!-- ── Back Link ── -->
                <div class="emp-profile-back-bar">
                    <a href="<?= url('/employer/candidates') ?>" class="emp-back-link">
                        <svg style="width: 16px; height: 16px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        <span>Back to Applications</span>
                    </a>
                </div>

                <!-- ── 1. Candidate Header Card (Matching Image 4) ── -->
                <div class="emp-profile-head-card">
                    <div class="emp-profile-left-meta">
                        <!-- Avatar with Active Dot -->
                        <div class="emp-profile-avatar-box">
                            <img src="<?= htmlspecialchars($candidate['avatar'] ?? 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=160&h=160&fit=crop&crop=faces') ?>" 
                                 alt="<?= htmlspecialchars($candidate['name'] ?? 'Sarah Peters') ?>">
                            <div class="emp-online-status-dot" title="Active Candidate"></div>
                        </div>

                        <!-- Name & Academic Subtitle -->
                        <div class="emp-profile-name-group">
                            <h1 class="emp-profile-cand-name"><?= htmlspecialchars($candidate['name'] ?? 'Sarah Peters') ?></h1>
                            <p class="emp-profile-cand-sub"><?= htmlspecialchars($candidate['student_title'] ?? 'UI/UX Design Student • Class of 2026 • Lagos, NG') ?></p>
                            <span class="emp-pill-active-applicant">ACTIVE APPLICANT</span>
                        </div>
                    </div>

                    <!-- Circular Match Score Widget -->
                    <div class="emp-profile-score-badge" title="Match score based on skills and criteria">
                        <span class="emp-profile-score-pct"><?= htmlspecialchars((string)($candidate['match_score'] ?? 95)) ?>%</span>
                        <span class="emp-profile-score-text">MATCH<br>SCORE</span>
                    </div>

                    <!-- Action Buttons Row -->
                    <div class="emp-profile-actions">
                        <!-- Shortlist Button -->
                        <button type="button" 
                                class="emp-btn-shortlist"
                                @click="isShortlisted = !isShortlisted; triggerToast(isShortlisted ? 'Candidate Shortlisted!' : 'Shortlist removed')">
                            <svg style="width: 15px; height: 15px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                            </svg>
                            <span x-text="isShortlisted ? 'Shortlisted' : 'Shortlist'">Shortlist</span>
                        </button>

                        <!-- Reject Button -->
                        <button type="button" 
                                class="emp-btn-reject"
                                @click="isRejected = !isRejected; triggerToast(isRejected ? 'Application Marked as Rejected' : 'Rejection reverted')">
                            <svg style="width: 15px; height: 15px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            <span x-text="isRejected ? 'Rejected' : 'Reject'">Reject</span>
                        </button>

                        <!-- Message Button -->
                        <a href="<?= url('/employer/dashboard') ?>" class="emp-btn-message">
                            <svg style="width: 15px; height: 15px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                            </svg>
                            <span>Message</span>
                        </a>

                        <!-- Schedule Interview CTA Button -->
                        <a href="<?= url('/employer/interviews/schedule/' . ($candidate['id'] ?? 1)) ?>" class="emp-btn-schedule-action">
                            <svg style="width: 15px; height: 15px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span>Schedule Interview</span>
                        </a>
                    </div>
                </div>

                <!-- ── 2. Two-Column Grid Content ── -->
                <div class="emp-profile-grid">

                    <!-- ════ LEFT COLUMN (Main Application Data) ════ -->
                    <div class="emp-profile-col">

                        <!-- Card 1: Application Answers -->
                        <div class="emp-card">
                            <div class="emp-card-header">
                                <svg class="emp-card-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <h2 class="emp-card-title">Application Answers</h2>
                            </div>

                            <div class="emp-answer-item">
                                <h3 class="emp-question-text">Why are you interested in Flux Technologies?</h3>
                                <p class="emp-answer-text emp-answer-italic">
                                    "<?= htmlspecialchars($candidate['questions'][0]['a'] ?? "Flux's commitment to accessible enterprise software aligns perfectly with my thesis project. I've been following your design team's blog on systemic UX for two years.") ?>"
                                </p>
                            </div>

                            <div class="emp-answer-item">
                                <h3 class="emp-question-text">Describe your proudest design project.</h3>
                                <p class="emp-answer-text">
                                    <?= htmlspecialchars($candidate['questions'][1]['a'] ?? "Created a community banking app for unbanked youth in Oakland. It focused on ultra-clear visual hierarchy and reduced cognitive load, which increased savings rates by 12% in user testing.") ?>
                                </p>
                            </div>

                            <div class="emp-answer-item">
                                <h3 class="emp-question-text">What are your primary learning goals?</h3>
                                <p class="emp-answer-text">
                                    <?= htmlspecialchars($candidate['questions'][2]['a'] ?? "I want to master design-to-code handoff workflows and learn how to leverage AI/ML in predicting user navigation friction points.") ?>
                                </p>
                            </div>
                        </div>

                        <!-- Card 2: Professional Summary -->
                        <div class="emp-card">
                            <h2 class="emp-card-title" style="margin-bottom: 12px;">Professional Summary</h2>
                            <p class="emp-summary-text">
                                <?= htmlspecialchars($candidate['summary'] ?? "Passionate designer focused on creating intuitive digital experiences. Currently exploring the intersection of accessibility and high-end visual design. Strong foundation in design systems and user-centric research methodologies.") ?>
                            </p>
                        </div>

                        <!-- Card 3: Work Experience -->
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
                                        <span class="emp-exp-badge"><?= htmlspecialchars($exp['period']) ?></span>
                                    </div>
                                    <div class="emp-exp-company"><?= htmlspecialchars($exp['company']) ?></div>
                                    <p class="emp-exp-desc"><?= htmlspecialchars($exp['desc']) ?></p>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <!-- Card 4: Application Details -->
                        <div class="emp-card">
                            <h2 class="emp-card-title" style="margin-bottom: 16px;">Application Details</h2>
                            <div class="emp-app-details-grid">
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
                                        @click="currentStage = '<?= $stage ?>'; triggerToast('Stage moved to <?= $stage ?>')">
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
                                    <span class="emp-doc-name"><?= htmlspecialchars($candidate['documents']['resume_name'] ?? 'Sarah_Peters_Resume.pdf') ?></span>
                                </div>
                                <button type="button" class="emp-doc-dl-btn" title="Download Resume" @click="triggerToast('Downloading Resume...')">
                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                    </svg>
                                </button>
                            </div>

                            <!-- Links Row -->
                            <div class="emp-links-row">
                                <a href="<?= htmlspecialchars($candidate['documents']['portfolio_url'] ?? '#') ?>" 
                                   target="_blank" 
                                   rel="noopener noreferrer" 
                                   class="emp-link-pill">
                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                                    </svg>
                                    <span>Portfolio</span>
                                </a>

                                <a href="<?= htmlspecialchars($candidate['documents']['linkedin_url'] ?? '#') ?>" 
                                   target="_blank" 
                                   rel="noopener noreferrer" 
                                   class="emp-link-pill">
                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                                    </svg>
                                    <span>LinkedIn</span>
                                </a>
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

                            <div class="emp-edu-badges">
                                <span class="emp-badge-grade"><?= htmlspecialchars($candidate['education']['grade'] ?? 'FIRST CLASS') ?></span>
                                <span class="emp-edu-gpa"><?= htmlspecialchars($candidate['education']['gpa'] ?? 'GPA 3.8/4.0') ?></span>
                            </div>

                            <p class="emp-edu-sub"><?= htmlspecialchars($candidate['education']['graduation'] ?? 'Expected Graduation: May 2027') ?></p>
                        </div>

                        <!-- Card 5: Skills & Interests -->
                        <div class="emp-card">
                            <div class="emp-card-header">
                                <svg class="emp-card-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
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

                <!-- Toast Notification -->
                <div x-show="showToast" 
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 transform translate-y-4"
                     x-transition:enter-end="opacity-100 transform translate-y-0"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 transform translate-y-0"
                     x-transition:leave-end="opacity-0 transform translate-y-4"
                     style="position: fixed; bottom: 28px; right: 28px; background: #000623; color: #FFFFFF; padding: 12px 20px; border-radius: 12px; font-size: 13.5px; font-weight: 600; box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2); z-index: 1000; display: none;"
                     x-text="toastMessage">
                </div>

            </div>
        </div>

    </div>

</div>
