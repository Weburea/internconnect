<?php
declare(strict_types=1);
/**
 * View: Candidate AI Mock Interview Assistant
 * Route: /candidate/ai-assistant/interview-prep
 * Layout: dashboard (Candidate Portal)
 *
 * @var array  $candidate      Candidate profile data
 * @var string $candPlan       Active plan ('pro' or 'premium')
 * @var string $targetRole     Current target role
 * @var string $targetCompany  Target company context
 * @var array  $rolesList      Available target roles
 * @var array  $companiesList  Target companies list
 * @var array  $questions      Array of interview questions
 */

$candName      = $candidate['name'] ?? 'Sarah Peters';
$candFirstName = $candidate['first_name'] ?? 'Sarah';
$candPlan      = $candPlan ?? ($candidate['plan'] ?? 'pro');
$targetRole    = $targetRole ?? 'Product Designer';
$targetCompany = $targetCompany ?? 'Paystack';
?>

<div class="db-shell">
    <div class="db-sidebar-overlay" id="db-sidebar-overlay"></div>

    <?php 
    $activeNav = 'ai-assistant';
    include dirname(__DIR__, 2) . '/shared/components/candidate-sidebar.php'; 
    ?>

    <div class="db-main">
        <?php include dirname(__DIR__, 2) . '/shared/components/candidate-topnav.php'; ?>

        <script>
        function initMockInterview() {
            return {
                currentRole: <?= json_encode($targetRole) ?>,
                currentCompany: <?= json_encode($targetCompany) ?>,
                roleDropdownOpen: false,
                companyDropdownOpen: false,
                currentQuestionIdx: 0,
                questions: <?= json_encode($questions) ?>,
                answers: {},
                evaluations: {},
                isEvaluating: false,
                isCompleted: false,
                showModelAnswer: false,

                get currentQuestion() {
                    return this.questions[this.currentQuestionIdx] || this.questions[0];
                },
                get questionParts() {
                    const full = this.currentQuestion.question;
                    const sentences = full.split(/(?<=[.?!])\s+(?=[A-Z])/);
                    if (sentences.length < 2) {
                        return { lead: full, steps: [] };
                    }

                    const steps = sentences.slice(1).join(' ')
                        .split(/,\s*(?:and\s+)?(?=(?:how|what|why|when|where|who|which)\b)/i)
                        .map((part) => part.trim().replace(/[.?!]$/, ''))
                        .map((part) => part.charAt(0).toUpperCase() + part.slice(1) + '?');

                    return steps.length > 1 ? { lead: sentences[0], steps } : { lead: full, steps: [] };
                },
                get totalQuestions() {
                    return this.questions.length;
                },
                get currentAnswer() {
                    return this.answers[this.currentQuestion.id] || '';
                },
                set currentAnswer(val) {
                    this.answers[this.currentQuestion.id] = val;
                },
                get currentEvaluation() {
                    return this.evaluations[this.currentQuestion.id] || null;
                },
                get overallScore() {
                    let evals = Object.values(this.evaluations);
                    if (evals.length === 0) return 0;
                    let sum = evals.reduce((acc, curr) => acc + (curr.score || 0), 0);
                    return Math.round(sum / evals.length);
                },
                insertHelper(text) {
                    if (!this.answers[this.currentQuestion.id]) {
                        this.answers[this.currentQuestion.id] = text + ' ';
                    } else {
                        this.answers[this.currentQuestion.id] += '\n' + text + ' ';
                    }
                },
                async submitAnswer() {
                    let ans = (this.answers[this.currentQuestion.id] || '').trim();
                    if (ans.length < 25) {
                        window.dispatchEvent(new CustomEvent('show-toast', {
                            detail: { message: 'Please write a more detailed response before evaluation.', type: 'error' }
                        }));
                        return;
                    }

                    this.isEvaluating = true;
                    this.showModelAnswer = false;

                    try {
                        let response = await fetch('<?= url('/candidate/ai-assistant/interview-prep/evaluate') ?>', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: JSON.stringify({
                                role: this.currentRole,
                                question: this.currentQuestion.question,
                                answer: ans
                            })
                        });

                        let data = await response.json();
                        if (data.success && data.evaluation) {
                            this.evaluations[this.currentQuestion.id] = data.evaluation;
                            window.dispatchEvent(new CustomEvent('show-toast', {
                                detail: { message: 'Jenny AI evaluated your response successfully!', type: 'success' }
                            }));
                        } else {
                            window.dispatchEvent(new CustomEvent('show-toast', {
                                detail: { message: data.error || 'Evaluation failed. Please try again.', type: 'error' }
                            }));
                        }
                    } catch (e) {
                        window.dispatchEvent(new CustomEvent('show-toast', {
                            detail: { message: 'Network error communicating with AI engine.', type: 'error' }
                        }));
                    } finally {
                        this.isEvaluating = false;
                    }
                },
                nextQuestion() {
                    if (this.currentQuestionIdx < this.totalQuestions - 1) {
                        this.currentQuestionIdx++;
                        this.showModelAnswer = false;
                    } else {
                        this.isCompleted = true;
                    }
                },
                prevQuestion() {
                    if (this.currentQuestionIdx > 0) {
                        this.currentQuestionIdx--;
                        this.showModelAnswer = false;
                    }
                },
                switchRole(role) {
                    window.location.href = '<?= url('/candidate/ai-assistant/interview-prep') ?>?role=' + encodeURIComponent(role) + '&company=' + encodeURIComponent(this.currentCompany);
                },
                switchCompany(company) {
                    window.location.href = '<?= url('/candidate/ai-assistant/interview-prep') ?>?role=' + encodeURIComponent(this.currentRole) + '&company=' + encodeURIComponent(company);
                }
            };
        }
        </script>

        <div class="db-content" id="db-content" x-data="initMockInterview()">
            <style>
            /* ── Header ── */
            .mock-header {
                margin-bottom: 24px;
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 20px;
                position: relative;
            }
            .mock-title {
                font-size: 28px;
                font-weight: 800;
                color: #000623;
                margin: 0 0 6px;
                letter-spacing: -0.02em;
            }
            .mock-sub {
                font-size: 14.5px;
                color: #64748B;
                margin: 0;
            }
            .mock-mascot-img {
                width: 82px;
                height: 82px;
                object-fit: contain;
                flex-shrink: 0;
                filter: drop-shadow(0 10px 20px rgba(79, 72, 230, 0.22));
                animation: mockFloat 4s ease-in-out infinite alternate;
            }
            @keyframes mockFloat {
                0% { transform: translateY(0px) rotate(0deg); }
                100% { transform: translateY(-6px) rotate(2deg); }
            }
            @media (max-width: 640px) {
                .mock-mascot-img {
                    display: none;
                }
            }

            /* ── Role & Company Filter Bar ── */
            .mock-config-bar {
                background: #FFFFFF;
                border: 1px solid #E2E8F0;
                border-radius: 16px;
                padding: 14px 20px;
                margin-bottom: 24px;
                display: flex;
                align-items: center;
                justify-content: space-between;
                flex-wrap: wrap;
                gap: 16px;
                box-shadow: 0 1px 3px rgba(0, 6, 35, 0.02);
            }
            .mock-config-left {
                display: flex;
                align-items: center;
                gap: 12px;
                flex-wrap: wrap;
            }
            .mock-config-label {
                font-size: 13px;
                font-weight: 700;
                color: #475569;
                display: flex;
                align-items: center;
                gap: 6px;
            }

            /* Standardized Alpine Dropdowns */
            .db-dropdown-wrap {
                position: relative;
            }
            .db-dropdown-btn {
                display: inline-flex !important;
                align-items: center !important;
                gap: 8px;
                padding: 8px 16px;
                background: #FFFFFF;
                border: 1px solid #CBD5E1;
                border-radius: 10px;
                font-size: 13px;
                font-weight: 700;
                color: #000623;
                cursor: pointer;
                transition: all 0.15s ease;
                white-space: nowrap !important;
                flex-shrink: 0 !important;
            }
            .db-dropdown-btn:hover {
                background: #F8FAFC;
                border-color: #94A3B8;
            }
            .db-dropdown-menu {
                position: absolute;
                top: calc(100% + 6px);
                left: 0;
                background: #FFFFFF;
                border: 1px solid #E2E8F0;
                border-radius: 12px;
                box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
                min-width: 190px;
                padding: 6px;
                z-index: 50;
            }
            .db-dropdown-item {
                display: block;
                width: 100%;
                text-align: left;
                padding: 8px 12px;
                font-size: 13px;
                font-weight: 600;
                color: #334155;
                border-radius: 6px;
                background: transparent;
                border: none;
                cursor: pointer;
                transition: background 0.12s ease;
            }
            .db-dropdown-item:hover {
                background: #F1F5F9;
                color: #4F48E6;
            }
            .db-dropdown-item.is-selected {
                background: #EEF2FF;
                color: #4F48E6;
                font-weight: 700;
            }

            /* Progress Strip */
            .mock-progress-strip {
                display: flex;
                align-items: center;
                gap: 12px;
                flex-shrink: 0;
            }
            .mock-progress-track {
                width: 120px;
                height: 8px;
                background: #EEF2FF;
                border-radius: 99px;
                overflow: hidden;
            }
            .mock-progress-fill {
                height: 100%;
                background: #4F48E6;
                border-radius: 99px;
                transition: width 0.3s ease;
            }
            .mock-progress-count {
                font-size: 12.5px;
                font-weight: 800;
                color: #4F48E6;
                white-space: nowrap !important;
                flex-shrink: 0 !important;
            }

            /* ── Layout Grid ── */
            .mock-grid {
                display: grid;
                grid-template-columns: minmax(0, 1fr) !important;
                gap: 24px;
                width: 100%;
                box-sizing: border-box;
                margin-bottom: 32px;
            }
            @media (min-width: 1024px) {
                .mock-grid {
                    grid-template-columns: minmax(0, 1.4fr) minmax(0, 1fr) !important;
                }
            }

            /* ── Cards ── */
            .mock-card {
                background: #FFFFFF;
                border: 1px solid #E2E8F0;
                border-radius: 18px;
                padding: 24px 28px;
                box-shadow: 0 2px 8px rgba(0, 6, 35, 0.03);
                box-sizing: border-box;
                width: 100%;
                margin-bottom: 24px;
            }
            @media (max-width: 640px) {
                .mock-card {
                    padding: 18px 16px;
                }
            }

            /* Question Header */
            .mock-q-meta {
                display: flex;
                align-items: center;
                justify-content: space-between;
                flex-wrap: wrap;
                gap: 10px;
                margin-bottom: 14px;
            }
            .mock-q-badge {
                display: inline-flex !important;
                align-items: center !important;
                gap: 6px;
                padding: 4px 12px;
                background: #EEF2FF;
                color: #4F48E6;
                border: 1px solid #C7D2FE;
                border-radius: 99px;
                font-size: 11.5px;
                font-weight: 800;
                text-transform: uppercase;
                letter-spacing: 0.03em;
                white-space: nowrap !important;
                flex-shrink: 0 !important;
            }
            .mock-q-category {
                font-size: 12px;
                font-weight: 700;
                color: #64748B;
            }
            .mock-ask {
                display: flex;
                align-items: flex-start;
                gap: 12px;
                margin-bottom: 18px;
                animation: mockAskIn 0.35s ease-out;
            }
            .mock-ask-avatar {
                width: 36px;
                height: 36px;
                flex-shrink: 0;
                border-radius: 50%;
                object-fit: cover;
                box-shadow: 0 2px 8px rgba(79, 72, 230, 0.22);
            }
            .mock-ask-body {
                flex: 1 1 auto;
                min-width: 0;
            }
            .mock-ask-name {
                display: block;
                margin-bottom: 6px;
                font-size: 12px;
                font-weight: 600;
                color: #4F48E6;
            }
            .mock-ask-bubble {
                padding: 16px 18px;
                background: #F7F7FE;
                border: 1px solid #E8E9FB;
                border-radius: 4px 16px 16px 16px;
            }
            .mock-q-title {
                font-size: 16px;
                font-weight: 500;
                color: #1E293B;
                line-height: 1.6;
                margin: 0;
            }
            .mock-ask-steps {
                margin-top: 14px;
                padding-top: 14px;
                border-top: 1px solid #E8E9FB;
            }
            .mock-ask-steps-label {
                display: block;
                margin-bottom: 10px;
                font-size: 11px;
                font-weight: 600;
                letter-spacing: 0.04em;
                text-transform: uppercase;
                color: #64748B;
            }
            .mock-ask-steps ol {
                display: flex;
                flex-direction: column;
                gap: 10px;
                margin: 0;
                padding: 0;
                list-style: none;
            }
            .mock-ask-steps li {
                display: flex;
                align-items: flex-start;
                gap: 10px;
                font-size: 14px;
                font-weight: 400;
                line-height: 1.5;
                color: #334155;
            }
            .mock-ask-step-num {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                width: 22px;
                height: 22px;
                flex-shrink: 0;
                border: 1px solid #DCDDF8;
                border-radius: 50%;
                background: #FFFFFF;
                font-size: 11px;
                font-weight: 600;
                color: #4F48E6;
            }
            @keyframes mockAskIn {
                from { opacity: 0; transform: translateY(6px); }
                to { opacity: 1; transform: translateY(0); }
            }
            @media (prefers-reduced-motion: reduce) {
                .mock-ask { animation: none; }
            }
            @media (max-width: 640px) {
                .mock-ask { gap: 10px; }
                .mock-ask-avatar { width: 30px; height: 30px; }
                .mock-ask-bubble { padding: 14px; }
            }

            /* Tip Box */
            .mock-tip-box {
                background: #F8FAFC;
                border: 1px solid #E2E8F0;
                border-radius: 12px;
                padding: 12px 16px;
                display: flex;
                align-items: flex-start;
                gap: 10px;
                margin-bottom: 20px;
            }
            .mock-tip-icon {
                width: 22px;
                height: 22px;
                border-radius: 50%;
                background: #000623;
                border: 1.5px solid #4F48E6;
                color: #FFFFFF;
                display: flex;
                align-items: center;
                justify-content: center;
                flex-shrink: 0;
                margin-top: 2px;
            }
            .mock-tip-text {
                font-size: 13px;
                color: #475569;
                line-height: 1.5;
                margin: 0;
            }

            /* Textarea */
            .mock-textarea-wrap {
                margin-bottom: 16px;
                position: relative;
            }
            .mock-textarea {
                width: 100%;
                min-width: 0;
                min-height: 180px;
                border: 1px solid #CBD5E1;
                border-radius: 12px;
                padding: 16px;
                font-size: 14px;
                color: #0F172A;
                font-family: inherit;
                line-height: 1.6;
                box-sizing: border-box;
                resize: vertical;
                transition: border-color 0.15s ease, box-shadow 0.15s ease;
                background: #FFFFFF;
            }
            .mock-textarea:focus {
                outline: none;
                border-color: #4F48E6;
                box-shadow: 0 0 0 3px rgba(79, 72, 230, 0.12);
            }
            .mock-char-counter {
                position: absolute;
                bottom: 12px;
                right: 14px;
                font-size: 11.5px;
                font-weight: 600;
                color: #94A3B8;
                pointer-events: none;
            }

            /* STAR Starter Helper Chips */
            .mock-chips-bar {
                display: flex;
                align-items: center;
                gap: 8px;
                flex-wrap: wrap;
                margin-bottom: 20px;
            }
            .mock-chip-btn {
                background: #FFFFFF;
                border: 1px solid #E2E8F0;
                border-radius: 99px;
                padding: 5px 12px;
                font-size: 11.5px;
                font-weight: 600;
                color: #475569;
                cursor: pointer;
                transition: all 0.12s ease;
                display: inline-flex !important;
                align-items: center !important;
                gap: 5px;
                white-space: nowrap !important;
                flex-shrink: 0 !important;
            }
            .mock-chip-btn:hover {
                border-color: #4F48E6;
                color: #4F48E6;
                background: #EEF2FF;
            }

            /* Actions Row */
            .mock-config-label > span {
                white-space: nowrap;
            }
            .mock-actions-right {
                display: flex;
                align-items: center;
                gap: 12px;
                margin-left: auto;
            }
            @media (max-width: 640px) {
                .mock-actions-row > .mock-btn-secondary,
                .mock-actions-right {
                    width: 100%;
                }
                .mock-actions-right {
                    flex-direction: column;
                    align-items: stretch;
                    margin-left: 0;
                }
                .mock-actions-row button {
                    justify-content: center;
                }
            }
            .mock-btn-secondary[style*="display: none"] {
                display: none !important;
            }
            .mock-actions-row {
                display: flex;
                align-items: center;
                justify-content: space-between;
                flex-wrap: wrap;
                gap: 12px;
                border-top: 1px solid #F1F5F9;
                padding-top: 18px;
            }
            .mock-btn-primary {
                display: inline-flex !important;
                align-items: center !important;
                justify-content: center !important;
                gap: 8px !important;
                padding: 10px 22px;
                background: #4F48E6;
                color: #FFFFFF;
                border: none;
                border-radius: 10px;
                font-size: 13.5px;
                font-weight: 700;
                cursor: pointer;
                box-shadow: 0 3px 10px rgba(79, 72, 230, 0.25);
                transition: background 0.15s ease, transform 0.15s ease;
                white-space: nowrap !important;
                flex-shrink: 0 !important;
            }
            .mock-btn-primary:hover:not(:disabled) {
                background: #3730A3;
                transform: translateY(-1px);
            }
            .mock-btn-primary:disabled {
                opacity: 0.6;
                cursor: not-allowed;
            }
            .mock-btn-secondary {
                display: inline-flex !important;
                align-items: center !important;
                justify-content: center !important;
                gap: 6px !important;
                padding: 9px 18px;
                background: #FFFFFF;
                border: 1px solid #CBD5E1;
                border-radius: 10px;
                font-size: 13px;
                font-weight: 700;
                color: #475569;
                cursor: pointer;
                transition: all 0.15s ease;
                white-space: nowrap !important;
                flex-shrink: 0 !important;
            }
            .mock-btn-secondary:hover {
                background: #F8FAFC;
                color: #000623;
                border-color: #94A3B8;
            }

            /* ── Evaluation Results Column ── */
            .mock-eval-head {
                display: flex;
                align-items: center;
                justify-content: space-between;
                margin-bottom: 18px;
            }
            .mock-score-pill {
                display: inline-flex !important;
                align-items: center !important;
                gap: 6px;
                padding: 6px 14px;
                border-radius: 99px;
                font-size: 13px;
                font-weight: 800;
                background: #EEF2FF;
                color: #4F48E6;
                border: 1px solid #C7D2FE;
                white-space: nowrap !important;
                flex-shrink: 0 !important;
            }
            .mock-star-list {
                display: flex;
                flex-direction: column;
                gap: 10px;
                margin-bottom: 20px;
            }
            .mock-star-item {
                display: flex;
                align-items: center;
                gap: 10px;
                font-size: 13px;
                color: #334155;
                font-weight: 600;
            }
            .mock-star-check {
                width: 18px;
                height: 18px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                flex-shrink: 0;
            }
            .mock-star-check.passed {
                background: #DCFCE7;
                color: #16A34A;
            }
            .mock-star-check.missing {
                background: #FEF3C7;
                color: #D97706;
            }

            .mock-subhead {
                font-size: 13px;
                font-weight: 800;
                text-transform: uppercase;
                letter-spacing: 0.04em;
                color: #64748B;
                margin-bottom: 8px;
            }
            .mock-feedback-list {
                list-style: none;
                padding: 0;
                margin: 0 0 20px;
            }
            .mock-feedback-item {
                display: flex;
                align-items: flex-start;
                gap: 8px;
                font-size: 13px;
                line-height: 1.5;
                color: #475569;
                margin-bottom: 8px;
            }
            .mock-feedback-item:last-child {
                margin-bottom: 0;
            }

            /* Model Answer Accordion */
            .mock-model-wrap {
                background: #F8FAFC;
                border: 1px solid #E2E8F0;
                border-radius: 12px;
                padding: 14px 16px;
                box-sizing: border-box;
            }
            .mock-model-toggle {
                display: flex;
                align-items: center;
                justify-content: space-between;
                cursor: pointer;
                background: transparent;
                border: none;
                width: 100%;
                padding: 0;
                font-size: 13px;
                font-weight: 700;
                color: #4F48E6;
            }
            .mock-model-body {
                margin-top: 10px;
                font-size: 13px;
                line-height: 1.6;
                color: #334155;
            }

            /* ── Completed Screen Card ── */
            .mock-complete-card {
                background: #FFFFFF;
                border: 1px solid #E2E8F0;
                border-radius: 20px;
                padding: 36px 32px;
                text-align: center;
                box-shadow: 0 4px 20px -2px rgba(0, 6, 35, 0.05);
                max-width: 640px;
                margin: 0 auto 36px;
            }
            .mock-complete-badge {
                width: 68px;
                height: 68px;
                border-radius: 50%;
                background: #EEF2FF;
                border: 2px solid #C7D2FE;
                color: #4F48E6;
                display: flex;
                align-items: center;
                justify-content: center;
                margin: 0 auto 16px;
            }
            .mock-complete-title {
                font-size: 24px;
                font-weight: 800;
                color: #000623;
                margin: 0 0 8px;
            }
            .mock-complete-desc {
                font-size: 14px;
                color: #64748B;
                line-height: 1.5;
                margin: 0 0 24px;
            }
            </style>

            <!-- ── Page Title & Subtitle with Jenny AI Mascot ── -->
            <div class="mock-header">
                <div>
                    <h1 class="mock-title">AI Mock Interview Assistant</h1>
                    <p class="mock-sub">Practice role-specific interview questions with instant STAR evaluation, confidence feedback, and sample answers.</p>
                </div>
                <img src="https://res.cloudinary.com/weburea/image/upload/v1793434500/jenny_ai_hi_chat_mafe7r.png" alt="Jenny AI Interview Coach" class="mock-mascot-img" loading="eager">
            </div>

            <!-- ── Role & Company Configuration Bar ── -->
            <div class="mock-config-bar">
                <div class="mock-config-left">
                    <!-- Target Role Dropdown (Rule K Custom Alpine) -->
                    <div class="mock-config-label">
                        <span>Role:</span>
                        <div class="db-dropdown-wrap" @click.outside="roleDropdownOpen = false">
                            <button type="button" class="db-dropdown-btn" @click="roleDropdownOpen = !roleDropdownOpen">
                                <span x-text="currentRole"><?= htmlspecialchars($targetRole) ?></span>
                                <svg style="width: 13px; height: 13px; flex-shrink: 0; transition: transform 0.2s;" :style="{ transform: roleDropdownOpen ? 'rotate(180deg)' : 'rotate(0deg)' }" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                            <div class="db-dropdown-menu" x-show="roleDropdownOpen" style="display: none;">
                                <?php foreach ($rolesList as $r): ?>
                                <button type="button" class="db-dropdown-item" :class="{ 'is-selected': currentRole === '<?= $r ?>' }" @click="switchRole('<?= $r ?>'); roleDropdownOpen = false;">
                                    <?= htmlspecialchars($r) ?>
                                </button>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Target Company Dropdown (Rule K Custom Alpine) -->
                    <div class="mock-config-label">
                        <span>Company Context:</span>
                        <div class="db-dropdown-wrap" @click.outside="companyDropdownOpen = false">
                            <button type="button" class="db-dropdown-btn" @click="companyDropdownOpen = !companyDropdownOpen">
                                <span x-text="currentCompany"><?= htmlspecialchars($targetCompany) ?></span>
                                <svg style="width: 13px; height: 13px; flex-shrink: 0; transition: transform 0.2s;" :style="{ transform: companyDropdownOpen ? 'rotate(180deg)' : 'rotate(0deg)' }" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                            <div class="db-dropdown-menu" x-show="companyDropdownOpen" style="display: none;">
                                <?php foreach ($companiesList as $c): ?>
                                <button type="button" class="db-dropdown-item" :class="{ 'is-selected': currentCompany === '<?= $c ?>' }" @click="switchCompany('<?= $c ?>'); companyDropdownOpen = false;">
                                    <?= htmlspecialchars($c) ?>
                                </button>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Progress Strip -->
                <div class="mock-progress-strip" x-show="!isCompleted">
                    <div class="mock-progress-track">
                        <div class="mock-progress-fill" :style="'width: ' + (((currentQuestionIdx + 1) / totalQuestions) * 100) + '%'"></div>
                    </div>
                    <span class="mock-progress-count" x-text="'Question ' + (currentQuestionIdx + 1) + ' of ' + totalQuestions">Question 1 of <?= count($questions) ?></span>
                </div>
            </div>

            <!-- ── Main Interview View (When In-Progress) ── -->
            <div x-show="!isCompleted">
                <div class="mock-grid">
                    
                    <!-- Left Column: Active Question & Answer Input -->
                    <div>
                        <div class="mock-card">
                            <!-- Question Metadata -->
                            <div class="mock-q-meta">
                                <span class="mock-q-badge">
                                    <!-- Jenny AI Mark SVG -->
                                    <svg style="width: 11px; height: 11px; flex-shrink: 0;" viewBox="0 0 74 83" fill="currentColor">
                                        <path d="M73.74 32.4627V59.9427C67.93 59.7673 62.2767 61.8422 57.9596 65.7344C53.6426 69.6267 50.9952 75.0356 50.57 80.8327C50.52 81.3027 50.48 81.8327 50.46 82.2827H23.57C23.57 82.0227 23.48 81.7627 23.43 81.5127C20.73 67.1427 16.41 63.2427 0 59.6527V32.6527C13.34 31.5727 27.12 37.6527 36.92 47.9327C46 38.3527 58.99 31.8927 73.74 32.4627Z"/>
                                        <path d="M37.0001 0.0031237C40.4833 0.0367014 43.8785 1.10075 46.7578 3.06112C49.637 5.02149 51.8715 7.79043 53.1794 11.0188C54.4873 14.2473 54.8101 17.7906 54.1071 21.2023C53.4042 24.6139 51.7069 27.741 49.2293 30.1894C46.7516 32.6378 43.6046 34.2979 40.1849 34.9604C36.7652 35.6228 33.2259 35.258 30.0132 33.9119C26.8005 32.5658 24.0583 30.2987 22.1322 27.3963C20.2061 24.494 19.1824 21.0864 19.1901 17.6031C19.1737 15.2695 19.6247 12.9562 20.5166 10.7997C21.4084 8.64315 22.7231 6.68703 24.383 5.04669C26.0429 3.40635 28.0145 2.11499 30.1814 1.24877C32.3484 0.382549 34.6669 -0.040991 37.0001 0.0031237Z"/>
                                    </svg>
                                    <span x-text="currentQuestion.category"><?= htmlspecialchars($questions[0]['category'] ?? 'Case Study & STAR') ?></span>
                                </span>
                                <span class="mock-q-category" x-text="'Focus: ' + currentQuestion.focus">Focus: <?= htmlspecialchars($questions[0]['focus'] ?? '') ?></span>
                            </div>

                            <!-- Question Title -->
                            <template x-for="questionId in [currentQuestion.id]" :key="questionId">
                                <div class="mock-ask">
                                    <img class="mock-ask-avatar" src="<?= asset('assets/images/jenny_ai/jenny_icon.png') ?>" alt="">
                                    <div class="mock-ask-body">
                                        <span class="mock-ask-name">Jenny AI is asking</span>
                                        <div class="mock-ask-bubble">
                                            <h2 class="mock-q-title" x-text="questionParts.lead"></h2>
                                            <template x-if="questionParts.steps.length">
                                                <div class="mock-ask-steps">
                                                    <span class="mock-ask-steps-label">Cover these in your answer</span>
                                                    <ol>
                                                        <template x-for="(step, index) in questionParts.steps" :key="index">
                                                            <li>
                                                                <span class="mock-ask-step-num" x-text="index + 1"></span>
                                                                <span x-text="step"></span>
                                                            </li>
                                                        </template>
                                                    </ol>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                            </template>

                            <!-- Jenny AI Interview Tip -->
                            <div class="mock-tip-box">
                                <div class="mock-tip-icon">
                                    <svg style="width: 12px; height: 12px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <p class="mock-tip-text">
                                    <strong style="color: #000623;">Jenny AI Coach Tip:</strong> <span x-text="currentQuestion.sample_tip"><?= htmlspecialchars($questions[0]['sample_tip'] ?? '') ?></span>
                                </p>
                            </div>

                            <!-- Textarea for Answer -->
                            <div class="mock-textarea-wrap">
                                <textarea class="mock-textarea" x-model="currentAnswer" placeholder="Type your response here... Structure your story around the Situation, your specific Action, and the measurable Result." aria-label="Interview Answer Response"></textarea>
                                <span class="mock-char-counter" x-text="currentAnswer.length + ' characters'">0 characters</span>
                            </div>

                            <!-- STAR Framework Helper Starter Chips -->
                            <div class="mock-chips-bar">
                                <span style="font-size: 11.5px; font-weight: 700; color: #64748B;">Quick STAR starters:</span>
                                <button type="button" class="mock-chip-btn" @click="insertHelper('In my previous project, the core situation was...')">
                                    <span>+ Situation</span>
                                </button>
                                <button type="button" class="mock-chip-btn" @click="insertHelper('My specific responsibility and task was to...')">
                                    <span>+ Task</span>
                                </button>
                                <button type="button" class="mock-chip-btn" @click="insertHelper('To resolve this, I personally designed and executed...')">
                                    <span>+ Action</span>
                                </button>
                                <button type="button" class="mock-chip-btn" @click="insertHelper('As a direct result, the team achieved an improvement of...')">
                                    <span>+ Result</span>
                                </button>
                            </div>

                            <!-- Action Buttons Row -->
                            <div class="mock-actions-row">
                                <button type="button" class="mock-btn-secondary" x-show="currentQuestionIdx > 0" @click="prevQuestion()">
                                    <svg style="width: 14px; height: 14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                                    </svg>
                                    <span>Previous</span>
                                </button>

                                <div class="mock-actions-right">
                                    <button type="button" class="mock-btn-primary" :disabled="isEvaluating || currentAnswer.trim().length < 20" @click="submitAnswer()">
                                        <template x-if="isEvaluating">
                                            <svg style="width: 16px; height: 16px; animation: spin 1s linear infinite;" fill="none" viewBox="0 0 24 24">
                                                <circle style="opacity: 0.25;" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path style="opacity: 0.75;" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                                            </svg>
                                        </template>
                                        <span x-text="isEvaluating ? 'Jenny AI is evaluating...' : 'Submit for AI Evaluation'">Submit for AI Evaluation</span>
                                        <svg x-show="!isEvaluating" style="width: 14px; height: 14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                        </svg>
                                    </button>

                                    <button type="button" class="mock-btn-secondary" x-show="currentEvaluation" @click="nextQuestion()">
                                        <span x-text="currentQuestionIdx < totalQuestions - 1 ? 'Next Question' : 'View Final Summary'">Next Question</span>
                                        <svg style="width: 14px; height: 14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column: Instant AI Evaluation Feedback -->
                    <div>
                        <!-- Empty State Before Submission -->
                        <div class="mock-card" x-show="!currentEvaluation && !isEvaluating" style="text-align: center; padding: 36px 24px;">
                            <div style="width: 48px; height: 48px; border-radius: 50%; background: #F8FAFC; border: 1px solid #E2E8F0; display: flex; align-items: center; justify-content: center; margin: 0 auto 14px; color: #64748B;">
                                <svg style="width: 22px; height: 22px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                                </svg>
                            </div>
                            <h3 style="font-size: 16px; font-weight: 700; color: #000623; margin: 0 0 6px;">Evaluation Awaiting Response</h3>
                            <p style="font-size: 13px; color: #64748B; margin: 0; line-height: 1.5;">Type your response and click <strong>Submit for AI Evaluation</strong>. Jenny AI will review your STAR structure, detect skills, and provide instant coaching feedback.</p>
                        </div>

                        <!-- Live Loading Shimmer -->
                        <div class="mock-card" x-show="isEvaluating" style="display: none; text-align: center; padding: 40px 24px;">
                            <img src="https://res.cloudinary.com/weburea/image/upload/v1793434500/jenny_ai_hi_chat_mafe7r.png" alt="Jenny AI Thinking" style="width: 60px; height: 60px; object-fit: contain; margin: 0 auto 14px; animation: mockFloat 2s infinite alternate;">
                            <h3 style="font-size: 16px; font-weight: 700; color: #000623; margin: 0 0 4px;">Analyzing your interview response...</h3>
                            <p style="font-size: 12.5px; color: #64748B; margin: 0;">Checking STAR clarity, role competencies, and impact metrics.</p>
                        </div>

                        <!-- Evaluation Results Card -->
                        <div class="mock-card" x-show="currentEvaluation && !isEvaluating" style="display: none;">
                            <div class="mock-eval-head">
                                <div>
                                    <span style="font-size: 11px; font-weight: 800; color: #64748B; text-transform: uppercase; letter-spacing: 0.04em;">AI Evaluation</span>
                                    <div style="font-size: 17px; font-weight: 800; color: #000623;" x-text="currentEvaluation?.rating"></div>
                                </div>
                                <div class="mock-score-pill">
                                    <span style="font-size: 16px; font-weight: 800;" x-text="currentEvaluation?.score + '%'"></span>
                                    <span>Score</span>
                                </div>
                            </div>

                            <!-- STAR Framework Adherence -->
                            <div class="mock-subhead">STAR Method Checklist</div>
                            <div class="mock-star-list">
                                <div class="mock-star-item">
                                    <div class="mock-star-check" :class="currentEvaluation?.star_adherence?.situation ? 'passed' : 'missing'">
                                        <svg style="width: 12px; height: 12px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    </div>
                                    <span>Situation &amp; Context established</span>
                                </div>
                                <div class="mock-star-item">
                                    <div class="mock-star-check" :class="currentEvaluation?.star_adherence?.task ? 'passed' : 'missing'">
                                        <svg style="width: 12px; height: 12px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    </div>
                                    <span>Objective &amp; Task clearly specified</span>
                                </div>
                                <div class="mock-star-item">
                                    <div class="mock-star-check" :class="currentEvaluation?.star_adherence?.action ? 'passed' : 'missing'">
                                        <svg style="width: 12px; height: 12px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    </div>
                                    <span>Individual Action &amp; execution highlighted</span>
                                </div>
                                <div class="mock-star-item">
                                    <div class="mock-star-check" :class="currentEvaluation?.star_adherence?.result ? 'passed' : 'missing'">
                                        <svg style="width: 12px; height: 12px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    </div>
                                    <span>Measurable Result or impact quantified</span>
                                </div>
                            </div>

                            <!-- Strengths -->
                            <div class="mock-subhead">What Went Well</div>
                            <ul class="mock-feedback-list">
                                <template x-for="(str, idx) in (currentEvaluation?.strengths || [])" :key="idx">
                                    <li class="mock-feedback-item">
                                        <svg style="width: 15px; height: 15px; color: #16A34A; flex-shrink: 0; margin-top: 2px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <span x-text="str"></span>
                                    </li>
                                </template>
                            </ul>

                            <!-- Suggestions for Improvement -->
                            <div class="mock-subhead">Recommendations to Level Up</div>
                            <ul class="mock-feedback-list">
                                <template x-for="(imp, idx) in (currentEvaluation?.improvements || [])" :key="idx">
                                    <li class="mock-feedback-item">
                                        <svg style="width: 15px; height: 15px; color: #4F48E6; flex-shrink: 0; margin-top: 2px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <span x-text="imp"></span>
                                    </li>
                                </template>
                            </ul>

                            <!-- Expandable Model Answer Accordion -->
                            <div class="mock-model-wrap">
                                <button type="button" class="mock-model-toggle" @click="showModelAnswer = !showModelAnswer">
                                    <span>Study Sample High-Scoring Answer</span>
                                    <svg style="width: 14px; height: 14px; transition: transform 0.2s;" :style="{ transform: showModelAnswer ? 'rotate(180deg)' : 'rotate(0deg)' }" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>
                                <div class="mock-model-body" x-show="showModelAnswer" style="display: none;" x-text="currentEvaluation?.model_answer"></div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- ── Completion Summary Screen ── -->
            <div x-show="isCompleted" style="display: none;">
                <div class="mock-complete-card">
                    <div class="mock-complete-badge">
                        <svg style="width: 32px; height: 32px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>

                    <h2 class="mock-complete-title">Mock Interview Completed!</h2>
                    <p class="mock-complete-desc">You practiced all questions for <strong><span x-text="currentRole"></span></strong> at <strong><span x-text="currentCompany"></span></strong>.</p>

                    <div style="display: inline-flex; align-items: baseline; gap: 4px; margin-bottom: 24px; padding: 12px 24px; background: #EEF2FF; border-radius: 16px; border: 1px solid #C7D2FE;">
                        <span style="font-size: 34px; font-weight: 800; color: #4F48E6;" x-text="overallScore"></span>
                        <span style="font-size: 16px; font-weight: 700; color: #64748B;">/ 100 Overall Readiness</span>
                    </div>

                    <div style="display: flex; align-items: center; justify-content: center; gap: 14px; flex-wrap: wrap;">
                        <button type="button" class="mock-btn-primary" @click="isCompleted = false; currentQuestionIdx = 0;">
                            <span>Practice Again</span>
                            <svg style="width: 14px; height: 14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                        </button>

                        <a href="<?= url('/candidate/ai-assistant/matched') ?>" class="mock-btn-secondary">
                            <span>Browse Matched Internships</span>
                            <svg style="width: 14px; height: 14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
