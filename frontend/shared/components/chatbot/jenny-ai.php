<?php
declare(strict_types=1);
/**
 * Global Component: Jenny AI Assistant
 * Location: frontend/shared/components/chatbot/jenny-ai.php
 * 
 * Implements the official Jenny AI chatbot specification:
 * - Exact Brand Palette: Navy #000623, Indigo #4F48E6, Light Grey #F2F2F2, White #FFFFFF
 * - Asset Mappings (Cloudinary + Local Fallback):
 *     jenny_icon.png                        => Launcher button badge & header avatar (v1790300712)
 *     jenny_ai_hi.png (waving)              => Welcome screen & greeting bubble
 *     Thinking_jenny_ai.png (thinking)      => Typing / loading indicator
 *     Pointing_jenny_ai.png (pointing)      => Tips & suggestion callouts
 *     Full-body_standing_pose_jenny_ai.png => Empty states & overview
 * - Semantic HTML typography for 'Jenny' and 'InternConnect' (no reliance on baked image text)
 * - Zero Emoji Policy: All indicators, avatars, actions & status badges use clean inline SVGs
 * - Single-Line Integrity: Badges and buttons enforce white-space: nowrap and flex-shrink: 0
 * - Dual Persona Intelligence:
 *     Role A: Job Seeker (Adaeze / User First Name) - CV Update, Progress, 78% Score Card, Preview, Matches
 *     Role B: Recruiter (Tunde / Employer First Name) - Shortlist candidates, Schedule interview, Summary metrics
 * - Modes: 'ai' (Default platform-wide) and 'support' (Help Center)
 */

$userRole = $_SESSION['user_role'] ?? 'candidate';
$sessionName = $_SESSION['profile_name'] ?? ($candidate['name'] ?? 'Sarah Peters');
$candFirstName = explode(' ', trim($sessionName))[0];

// Dynamic First Name targeting for personas
$jobSeekerName = ($userRole === 'candidate' && !empty($candFirstName)) ? $candFirstName : 'Adaeze';
$recruiterName = ($userRole === 'employer' && !empty($candFirstName)) ? $candFirstName : 'Tunde';

// Determine active mode: check if explicitly passed or on /help route
$isHelpRoute = str_contains($_SERVER['REQUEST_URI'] ?? '', '/help');
$initialPersona = ($userRole === 'employer') ? 'recruiter' : 'jobseeker';

// Asset URLs via asset() helper with Cloudinary integration
$iconBadgeImg   = asset('assets/images/jenny_ai/jenny_icon.png');
$wavingImg      = asset('assets/images/jenny_ai/jenny_ai_hi.png');
$thinkingImg    = asset('assets/images/jenny_ai/Thinking_jenny_ai.png');
$pointingImg    = asset('assets/images/jenny_ai/Pointing_jenny_ai.png');
$fullBodyImg    = asset('assets/images/jenny_ai/Full-body_standing_pose_jenny_ai.png');
?>

<div id="ic-jenny-ai-widget" 
     x-data="{
        isOpen: false,
        isMinimized: false,
        unreadCount: 3,
        mode: '<?= $isHelpRoute ? 'support' : 'ai' ?>',
        persona: '<?= $initialPersona ?>',
        screen: 'welcome',
        inputText: '',
        isTyping: false,
        messages: [],
        
        // CV Flow States (Job Seeker)
        uploadProgress: 0,
        uploadDone: false,
        cvAccepted: false,
        appliedSuggestions: [],

        // Recruiter Flow States
        selectedTimeSlot: '10:30 AM',
        interviewConfirmed: false,

        init() {
            if (this.mode === 'support') {
                this.messages = [
                    {
                        id: 1,
                        sender: 'bot',
                        pose: 'waving',
                        text: 'Hi <?= htmlspecialchars($candFirstName) ?>! I\'m Jenny from Student Support. How can I help with your account or applications today?',
                        time: '10:24 AM'
                    }
                ];
            }
        },

        openChat() {
            this.isOpen = true;
            this.isMinimized = false;
            this.unreadCount = 0;
            this.scrollToBottom();
        },

        closeChat() {
            this.isOpen = false;
        },

        minimizeChat() {
            this.isMinimized = !this.isMinimized;
        },

        togglePersona() {
            this.persona = (this.persona === 'jobseeker' ? 'recruiter' : 'jobseeker');
            this.screen = 'welcome';
            this.messages = [];
            this.appliedSuggestions = [];
            this.cvAccepted = false;
            this.interviewConfirmed = false;
            this.scrollToBottom();
        },

        triggerAction(actionKey) {
            this.screen = 'chat';
            if (actionKey === 'update_cv') {
                this.messages.push({
                    id: Date.now(),
                    sender: 'user',
                    text: 'Update my CV',
                    time: '10:25 AM'
                });
                this.isTyping = true;
                this.scrollToBottom();

                setTimeout(() => {
                    this.isTyping = false;
                    this.messages.push({
                        id: Date.now() + 1,
                        sender: 'bot',
                        pose: 'pointing',
                        text: 'Great! Please upload your CV or paste your details, and I\'ll help you optimize it.',
                        time: '10:26 AM',
                        specialType: 'upload_card'
                    });
                    this.simulateUpload();
                    this.scrollToBottom();
                }, 800);
            } else if (actionKey === 'find_internships') {
                this.messages.push({
                    id: Date.now(),
                    sender: 'user',
                    text: 'I want to find internship opportunities in tech.',
                    time: '10:26 AM'
                });
                this.isTyping = true;
                this.scrollToBottom();

                setTimeout(() => {
                    this.isTyping = false;
                    this.messages.push({
                        id: Date.now() + 1,
                        sender: 'bot',
                        pose: 'waving',
                        text: 'Great! Here are some top internship opportunities in tech right now. Would you like me to show you the latest openings or help you filter by location or skills?',
                        time: '10:27 AM',
                        specialType: 'internship_suggestions'
                    });
                    this.scrollToBottom();
                }, 900);
            } else if (actionKey === 'shortlist_candidates') {
                this.messages.push({
                    id: Date.now(),
                    sender: 'user',
                    text: 'Shortlist top candidates for Product Design',
                    time: '10:16 AM'
                });
                this.isTyping = true;
                this.scrollToBottom();

                setTimeout(() => {
                    this.isTyping = false;
                    this.messages.push({
                        id: Date.now() + 1,
                        sender: 'bot',
                        pose: 'pointing',
                        text: 'Here are 3 top candidates based on your criteria:',
                        time: '10:17 AM',
                        specialType: 'candidate_shortlist'
                    });
                    this.scrollToBottom();
                }, 1000);
            } else if (actionKey === 'schedule_interview') {
                this.messages.push({
                    id: Date.now(),
                    sender: 'user',
                    text: 'Schedule interview with Chinonso Eze',
                    time: '10:19 AM'
                });
                this.isTyping = true;
                this.scrollToBottom();

                setTimeout(() => {
                    this.isTyping = false;
                    this.messages.push({
                        id: Date.now() + 1,
                        sender: 'bot',
                        pose: 'pointing',
                        text: 'Here are available time slots for an online interview with Chinonso:',
                        time: '10:20 AM',
                        specialType: 'schedule_card'
                    });
                    this.scrollToBottom();
                }, 800);
            }
        },

        simulateUpload() {
            this.uploadProgress = 15;
            this.uploadDone = false;
            const timer = setInterval(() => {
                if (this.uploadProgress < 75) {
                    this.uploadProgress += 30;
                } else if (this.uploadProgress < 100) {
                    this.uploadProgress = 100;
                    this.uploadDone = true;
                    clearInterval(timer);
                    this.showCvReviewCard();
                }
            }, 600);
        },

        showCvReviewCard() {
            setTimeout(() => {
                this.messages.push({
                    id: Date.now(),
                    sender: 'bot',
                    pose: 'pointing',
                    text: 'I have analyzed your resume against current high-demand internship criteria:',
                    time: '10:28 AM',
                    specialType: 'cv_review_card'
                });
                this.scrollToBottom();
            }, 800);
        },

        applySuggestion(idx) {
            if (!this.appliedSuggestions.includes(idx)) {
                this.appliedSuggestions.push(idx);
            }
            if (this.appliedSuggestions.length >= 2 && !this.messages.some(m => m.specialType === 'before_after_card')) {
                setTimeout(() => {
                    this.messages.push({
                        id: Date.now(),
                        sender: 'bot',
                        pose: 'pointing',
                        text: 'Here is your improved experience section preview:',
                        time: '10:30 AM',
                        specialType: 'before_after_card'
                    });
                    this.scrollToBottom();
                }, 700);
            }
        },

        acceptImprovedCv() {
            this.cvAccepted = true;
            this.messages.push({
                id: Date.now(),
                sender: 'user',
                text: 'Accept Changes',
                time: '10:31 AM'
            });
            this.isTyping = true;
            this.scrollToBottom();

            setTimeout(() => {
                this.isTyping = false;
                this.messages.push({
                    id: Date.now() + 1,
                    sender: 'bot',
                    pose: 'waving',
                    text: 'Your CV is updated. Want me to match you with top internship openings now?',
                    time: '10:31 AM',
                    specialType: 'cv_success_card'
                });
                this.scrollToBottom();
            }, 900);
        },

        confirmInterview() {
            this.interviewConfirmed = true;
            this.messages.push({
                id: Date.now(),
                sender: 'bot',
                pose: 'waving',
                text: 'Interview confirmed for Tuesday, 18 Nov at ' + this.selectedTimeSlot + '. Calendar invites sent to both parties!',
                time: '10:21 AM'
            });
            this.scrollToBottom();
        },

        sendMessage() {
            const text = this.inputText.trim();
            if (!text) return;

            this.screen = 'chat';
            this.messages.push({
                id: Date.now(),
                sender: 'user',
                text: text,
                time: 'Just now'
            });
            this.inputText = '';
            this.isTyping = true;
            this.scrollToBottom();

            setTimeout(() => {
                this.isTyping = false;
                let reply = 'I\'m processing that for you right now. You can also explore our quick career shortcuts.';
                let pose = 'waving';
                const lower = text.toLowerCase();

                if (lower.includes('cv') || lower.includes('resume')) {
                    reply = 'I can optimize your CV to boost recruiter visibility by up to 60%. Click \'Update my CV\' to begin!';
                    pose = 'pointing';
                } else if (lower.includes('interview')) {
                    reply = 'Need interview prep? I can run mock behavioral and technical questions tailored to your field.';
                    pose = 'pointing';
                } else if (lower.includes('intern') || lower.includes('job')) {
                    reply = 'We currently have 450+ verified internship opportunities open across Nigeria. Would you like to view tech or finance roles?';
                    pose = 'waving';
                }

                this.messages.push({
                    id: Date.now() + 1,
                    sender: 'bot',
                    pose: pose,
                    text: reply,
                    time: 'Just now'
                });
                this.scrollToBottom();
            }, 1100);
        },

        scrollToBottom() {
            this.$nextTick(() => {
                const el = this.$refs.chatScroll;
                if (el) el.scrollTop = el.scrollHeight;
            });
        }
     }"
     @open-jenny-ai.window="openChat();"
     @open-support-chat.window="mode = 'support'; openChat();"
     class="jenny-ai-root">

    <style>
    /* ═══════════════════════════════════════════════════════════════════════════
       JENNY AI BRAND DESIGN SYSTEM
       Strict InternConnect Palette:
       - Navy:       #000623
       - Indigo:     #4F48E6
       - Light Grey: #F2F2F2
       - White:      #FFFFFF
       ═══════════════════════════════════════════════════════════════════════════ */

    .jenny-ai-root {
        position: fixed;
        bottom: 24px;
        right: 24px;
        z-index: 99999;
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        color: #000623;
    }

    /* ─── Floating Launcher Badge Button (Perfect Circle) ─── */
    .jenny-launcher-btn {
        position: relative;
        background: transparent;
        border: none;
        padding: 0;
        cursor: pointer;
        outline: none;
        transition: transform 0.25s cubic-bezier(0.16, 1, 0.3, 1), filter 0.25s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 62px;
        height: 62px;
        border-radius: 50%;
    }

    .jenny-launcher-btn:hover {
        transform: translateY(-3px) scale(1.04);
        filter: drop-shadow(0 12px 24px rgba(79, 72, 230, 0.38));
    }

    .jenny-launcher-img {
        width: 60px;
        height: 60px;
        object-fit: contain;
        display: block;
        filter: drop-shadow(0 8px 18px rgba(79, 72, 230, 0.32));
    }

    .jenny-launcher-badge {
        position: absolute;
        top: 0px;
        right: 2px;
        background: #EF4444;
        color: #FFFFFF;
        font-size: 11px;
        font-weight: 800;
        min-width: 20px;
        height: 20px;
        border-radius: 999px;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0 5px;
        border: 2px solid #FFFFFF;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
        animation: jennyPulse 2s infinite ease-in-out;
    }

    @keyframes jennyPulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.1); }
    }

    /* ─── Chat Window Container ─── */
    .jenny-window {
        position: fixed;
        bottom: 24px;
        right: 24px;
        width: 390px;
        max-width: calc(100vw - 32px);
        height: 600px;
        max-height: calc(100vh - 40px);
        background: #FFFFFF;
        border-radius: 24px;
        box-shadow: 0 24px 60px -12px rgba(0, 6, 35, 0.25), 0 0 0 1px #F2F2F2;
        display: flex;
        flex-direction: column;
        overflow: hidden;
        z-index: 100000;
        animation: jennyScaleIn 0.28s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }

    .jenny-window.is-minimized {
        height: 64px !important;
        overflow: hidden;
    }

    @keyframes jennyScaleIn {
        from {
            opacity: 0;
            transform: translateY(20px) scale(0.95);
        }
        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    /* ─── Header: Navy #000623 ─── */
    .jenny-header {
        background: #000623;
        color: #FFFFFF;
        padding: 14px 18px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        user-select: none;
    }

    .jenny-header-left {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .jenny-avatar-wrap {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        position: relative;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .jenny-avatar-wrap img {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        object-fit: cover;
        display: block;
    }

    .jenny-online-dot {
        position: absolute;
        bottom: 0px;
        right: 0px;
        width: 10px;
        height: 10px;
        background: #10B981;
        border: 2px solid #000623;
        border-radius: 50%;
    }

    .jenny-header-text {
        display: flex;
        flex-direction: column;
    }

    .jenny-header-title-row {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .jenny-name-text {
        font-size: 15px;
        font-weight: 700;
        color: #FFFFFF;
        letter-spacing: -0.01em;
    }

    .jenny-online-label {
        font-size: 11px;
        color: #10B981;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .jenny-subtitle-text {
        font-size: 12px;
        color: rgba(255, 255, 255, 0.72);
        font-weight: 400;
    }

    .jenny-header-controls {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .jenny-control-btn {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        border: none;
        background: rgba(255, 255, 255, 0.12);
        color: #FFFFFF;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: background 0.15s ease;
        outline: none;
    }

    .jenny-control-btn:hover {
        background: rgba(255, 255, 255, 0.22);
    }

    .jenny-control-btn svg {
        width: 14px;
        height: 14px;
    }

    /* ─── Mode / Persona Switcher Pill Row ─── */
    .jenny-role-pill-bar {
        background: #F8FAFC;
        border-bottom: 1px solid #E2E8F0;
        padding: 8px 14px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
    }

    .jenny-role-tag {
        color: #000623;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 12px;
        font-weight: 700;
        white-space: nowrap;
        flex-shrink: 0;
    }

    .jenny-role-tag svg {
        width: 14px;
        height: 14px;
        color: #4F48E6;
        flex-shrink: 0;
    }

    .jenny-role-switch-btn {
        background: #FFFFFF;
        border: 1px solid #E2E8F0;
        color: #4F48E6;
        padding: 4px 12px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        display: inline-flex;
        align-items: center;
        gap: 5px;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.04);
        white-space: nowrap !important;
        flex-shrink: 0 !important;
        outline: none;
    }

    .jenny-role-switch-btn:hover {
        background: #4F48E6;
        color: #FFFFFF;
        border-color: #4F48E6;
        box-shadow: 0 4px 10px rgba(79, 72, 230, 0.25);
        transform: translateY(-1px);
    }

    /* ─── Scrollable Body ─── */
    .jenny-body {
        flex: 1;
        overflow-y: auto;
        padding: 16px;
        background: #FFFFFF;
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    /* ─── Welcome Screen Hero ─── */
    .jenny-welcome-hero {
        text-align: center;
        padding: 12px 10px 6px 10px;
    }

    .jenny-hero-mascot-wrap {
        width: 96px;
        height: 96px;
        margin: 0 auto 12px auto;
        position: relative;
    }

    .jenny-hero-mascot-img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }

    .jenny-welcome-heading {
        font-size: 20px;
        font-weight: 800;
        color: #000623;
        margin: 0 0 6px 0;
        line-height: 1.3;
    }

    .jenny-welcome-sub {
        font-size: 13.5px;
        color: #64748B;
        line-height: 1.5;
        margin: 0 0 20px 0;
    }

    /* 4 Action Cards Grid */
    .jenny-actions-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 10px;
    }

    .jenny-action-card {
        background: #F2F2F2;
        border: 1px solid transparent;
        border-radius: 12px;
        padding: 12px 14px;
        display: flex;
        align-items: center;
        gap: 8px;
        text-align: left;
        cursor: pointer;
        transition: all 0.18s ease;
        font-size: 12.5px;
        font-weight: 600;
        color: #000623;
        outline: none;
    }

    .jenny-action-card:hover {
        background: #FFFFFF;
        border-color: #4F48E6;
        color: #4F48E6;
        box-shadow: 0 4px 12px rgba(79, 72, 230, 0.12);
        transform: translateY(-1px);
    }

    .jenny-action-icon {
        width: 18px;
        height: 18px;
        color: #4F48E6;
        flex-shrink: 0;
    }

    /* ─── Message Stream Items ─── */
    .jenny-msg-row {
        display: flex;
        align-items: flex-end;
        gap: 8px;
        width: 100%;
    }

    .jenny-msg-row.user {
        justify-content: flex-end;
    }

    .jenny-msg-row.bot {
        justify-content: flex-start;
    }

    .jenny-bot-avatar-mini {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        overflow: hidden;
        flex-shrink: 0;
        background: #4F48E6;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .jenny-bot-avatar-mini img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .jenny-bubble {
        max-width: 82%;
        padding: 10px 14px;
        font-size: 13.5px;
        line-height: 1.5;
        border-radius: 16px;
        word-break: break-word;
    }

    /* Bot bubble: Light Grey #F2F2F2, Text: Navy #000623 */
    .jenny-msg-row.bot .jenny-bubble {
        background: #F2F2F2;
        color: #000623;
        border-bottom-left-radius: 4px;
    }

    /* User bubble: Indigo #4F48E6, Text: White #FFFFFF */
    .jenny-msg-row.user .jenny-bubble {
        background: #4F48E6;
        color: #FFFFFF;
        border-bottom-right-radius: 4px;
    }

    /* ─── Typing / Loading Indicator with Thinking Jenny Mascot ─── */
    .jenny-typing-row {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .jenny-thinking-mascot {
        width: 32px;
        height: 32px;
        object-fit: contain;
    }

    .jenny-dots-wrap {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        background: #F2F2F2;
        padding: 8px 12px;
        border-radius: 14px;
    }

    .jenny-dot {
        width: 6px;
        height: 6px;
        background: #4F48E6;
        border-radius: 50%;
        animation: jennyBounce 1.4s infinite ease-in-out both;
    }

    .jenny-dot:nth-child(1) { animation-delay: -0.32s; }
    .jenny-dot:nth-child(2) { animation-delay: -0.16s; }
    .jenny-dot:nth-child(3) { animation-delay: 0s; }

    @keyframes jennyBounce {
        0%, 80%, 100% { transform: scale(0); }
        40% { transform: scale(1); }
    }

    /* ─── 1. CV Upload Card ─── */
    .jenny-upload-card {
        background: #FFFFFF;
        border: 1px solid #E2E8F0;
        border-radius: 12px;
        padding: 12px 14px;
        margin-top: 8px;
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .jenny-upload-file-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .jenny-upload-file-left {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .jenny-progress-track {
        height: 5px;
        background: #F2F2F2;
        border-radius: 4px;
        overflow: hidden;
    }

    .jenny-progress-fill {
        height: 100%;
        background: #4F48E6;
        transition: width 0.3s ease;
    }

    /* ─── 2. CV Review Card (Radial Score 78% + Suggestions) ─── */
    .jenny-review-card {
        background: #FFFFFF;
        border: 1px solid #E2E8F0;
        border-radius: 14px;
        padding: 14px;
        margin-top: 8px;
    }

    .jenny-score-row {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 12px;
        padding-bottom: 10px;
        border-bottom: 1px solid #F2F2F2;
    }

    .jenny-score-circle {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        border: 4px solid #4F48E6;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 15px;
        font-weight: 800;
        color: #4F48E6;
        flex-shrink: 0;
    }

    .jenny-suggestion-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        font-size: 12px;
        line-height: 1.4;
        padding: 8px 0;
        border-bottom: 1px dashed #E2E8F0;
    }

    .jenny-suggestion-text {
        flex: 1;
        min-width: 0;
        color: #000623;
    }

    /* Single-Line Guaranteed Applied Button */
    .jenny-apply-btn {
        background: #4F48E6;
        color: #FFFFFF;
        border: none;
        border-radius: 8px;
        padding: 5px 12px;
        font-size: 11.5px;
        font-weight: 700;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 5px;
        white-space: nowrap !important;
        flex-shrink: 0 !important;
        min-width: 74px;
        height: 28px;
        transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        text-decoration: none;
        outline: none;
    }

    .jenny-apply-btn:hover {
        background: #3B34CC;
        transform: translateY(-1px);
    }

    .jenny-apply-btn.is-applied {
        background: #10B981 !important;
        color: #FFFFFF !important;
        cursor: default;
        box-shadow: 0 2px 6px rgba(16, 185, 129, 0.25);
    }

    /* ─── 3. Before & After Preview Card ─── */
    .jenny-compare-card {
        background: #FFFFFF;
        border: 1px solid #E2E8F0;
        border-radius: 14px;
        padding: 14px;
        margin-top: 8px;
    }

    .jenny-compare-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
        font-size: 11.5px;
        margin-bottom: 12px;
    }

    .jenny-compare-col {
        background: #F2F2F2;
        padding: 8px 10px;
        border-radius: 8px;
        line-height: 1.4;
    }

    .jenny-compare-col strong {
        display: block;
        font-size: 11px;
        color: #000623;
        margin-bottom: 4px;
        text-transform: uppercase;
    }

    /* ─── 4. Candidate Shortlist (Recruiter) ─── */
    .jenny-candidate-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 8px;
        border-radius: 10px;
        background: #F2F2F2;
        margin-bottom: 6px;
        font-size: 12px;
    }

    /* ─── 5. Schedule Interview Card (Premium Redesign) ─── */
    .jenny-schedule-card {
        background: #FFFFFF;
        border: 1px solid #E2E8F0;
        border-radius: 16px;
        padding: 16px;
        margin-top: 10px;
        box-shadow: 0 4px 16px rgba(0, 6, 35, 0.05);
    }

    .jenny-schedule-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 10px;
    }

    .jenny-schedule-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 11px;
        font-weight: 700;
        color: #4F48E6;
        background: rgba(79, 72, 230, 0.08);
        padding: 4px 10px;
        border-radius: 999px;
    }

    .jenny-schedule-duration {
        font-size: 11px;
        font-weight: 600;
        color: #64748B;
    }

    .jenny-schedule-candidate-info {
        display: flex;
        flex-direction: column;
        gap: 2px;
        margin-bottom: 12px;
        padding-bottom: 10px;
        border-bottom: 1px solid #F1F5F9;
    }

    .jenny-schedule-label {
        font-size: 10.5px;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: #94A3B8;
        font-weight: 700;
    }

    .jenny-schedule-name {
        font-size: 13px;
        font-weight: 700;
        color: #000623;
    }

    .jenny-schedule-date-box {
        background: #F8FAFC;
        border: 1px solid #E2E8F0;
        border-radius: 12px;
        padding: 10px 12px;
        margin-bottom: 12px;
    }

    .jenny-schedule-date-left {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .jenny-schedule-cal-icon {
        width: 18px;
        height: 18px;
        color: #4F48E6;
        flex-shrink: 0;
    }

    .jenny-schedule-date-day {
        font-size: 12.5px;
        font-weight: 700;
        color: #000623;
        line-height: 1.2;
    }

    .jenny-schedule-date-tz {
        font-size: 11px;
        color: #64748B;
    }

    .jenny-schedule-slots-label {
        font-size: 11.5px;
        font-weight: 700;
        color: #000623;
        margin-bottom: 8px;
    }

    .jenny-schedule-slots-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 8px;
        margin-bottom: 14px;
    }

    .jenny-slot-btn {
        background: #FFFFFF;
        border: 1.5px solid #E2E8F0;
        border-radius: 10px;
        padding: 7px 4px;
        font-size: 11.5px;
        font-weight: 600;
        color: #000623;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 4px;
        transition: all 0.18s ease;
        outline: none;
        white-space: nowrap;
    }

    .jenny-slot-btn:hover {
        border-color: #4F48E6;
        color: #4F48E6;
        background: #F8FAFC;
    }

    .jenny-slot-btn.is-selected {
        background: #4F48E6;
        border-color: #4F48E6;
        color: #FFFFFF;
        font-weight: 700;
        box-shadow: 0 4px 12px rgba(79, 72, 230, 0.28);
    }

    .jenny-schedule-confirm-btn {
        width: 100%;
        background: #4F48E6;
        color: #FFFFFF;
        border: none;
        border-radius: 10px;
        padding: 10px 14px;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: all 0.2s ease;
        box-shadow: 0 4px 14px rgba(79, 72, 230, 0.25);
    }

    .jenny-schedule-confirm-btn:hover {
        background: #000623;
        transform: translateY(-1px);
        box-shadow: 0 6px 18px rgba(0, 6, 35, 0.2);
    }

    .jenny-schedule-success-card {
        background: #F0FDF4;
        border: 1px solid #BBF7D0;
        border-radius: 12px;
        padding: 12px 14px;
        margin-top: 10px;
        display: flex;
        align-items: flex-start;
        gap: 10px;
    }

    .jenny-success-icon-wrap {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        background: #DCFCE7;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .jenny-success-title {
        font-size: 13px;
        font-weight: 800;
        color: #166534;
        line-height: 1.2;
    }

    .jenny-success-time {
        font-size: 12px;
        font-weight: 600;
        color: #15803D;
        margin: 2px 0;
    }

    .jenny-success-note {
        font-size: 11px;
        color: #4B5563;
    }

    /* ─── Footer Controls: White #FFFFFF, Light Grey #F2F2F2 ─── */
    .jenny-footer {
        background: #FFFFFF;
        border-top: 1px solid #F2F2F2;
        padding: 10px 14px 12px 14px;
    }

    .jenny-input-form {
        display: flex;
        align-items: center;
        background: #F2F2F2;
        border-radius: 999px;
        padding: 4px 6px 4px 14px;
        gap: 8px;
        border: 1px solid transparent;
        transition: border-color 0.15s ease, background 0.15s ease;
    }

    .jenny-input-form:focus-within {
        background: #FFFFFF;
        border-color: #4F48E6;
        box-shadow: 0 0 0 2px rgba(79, 72, 230, 0.15);
    }

    .jenny-attach-icon {
        color: #64748B;
        cursor: pointer;
        display: flex;
        align-items: center;
    }

    .jenny-input-field {
        flex: 1;
        border: none;
        background: transparent;
        font-size: 13.5px;
        color: #000623;
        font-family: inherit;
        outline: none;
    }

    .jenny-input-field::placeholder {
        color: #94A3B8;
    }

    .jenny-send-btn {
        width: 34px;
        height: 34px;
        border-radius: 50%;
        background: #4F48E6;
        border: none;
        color: #FFFFFF;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: transform 0.15s ease, background 0.15s ease;
        flex-shrink: 0;
    }

    .jenny-send-btn:hover {
        background: #000623;
        transform: scale(1.06);
    }

    .jenny-send-btn svg {
        width: 15px;
        height: 15px;
    }

    .jenny-powered-tag {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        font-size: 11px;
        color: #64748B;
        margin-top: 8px;
        font-weight: 500;
        user-select: none;
    }

    .jenny-powered-tag svg {
        flex-shrink: 0;
    }
    </style>

    <!-- ════ 1. FLOATING LAUNCHER BUTTON ════ -->
    <div x-show="!isOpen" x-cloak>
        <button type="button" 
                @click="openChat()" 
                class="jenny-launcher-btn" 
                aria-label="Open Jenny AI Assistant">
            <img src="<?= $iconBadgeImg ?>" alt="Jenny AI" class="jenny-launcher-img">
            <template x-if="unreadCount > 0">
                <span class="jenny-launcher-badge" x-text="unreadCount"></span>
            </template>
        </button>
    </div>

    <!-- ════ 2. CHATBOT WINDOW ════ -->
    <div x-show="isOpen" x-cloak class="jenny-window" :class="{ 'is-minimized': isMinimized }">
        
        <!-- Header (Navy #000623) -->
        <div class="jenny-header">
            <div class="jenny-header-left">
                <div class="jenny-avatar-wrap">
                    <img src="<?= $iconBadgeImg ?>" alt="Jenny AI">
                    <span class="jenny-online-dot" title="Online"></span>
                </div>
                <div class="jenny-header-text">
                    <div class="jenny-header-title-row">
                        <span class="jenny-name-text">Jenny</span>
                        <span class="jenny-online-label">&bull; Online</span>
                    </div>
                    <span class="jenny-subtitle-text" x-text="mode === 'support' ? 'InternConnect Support' : 'Your InternConnect assistant'"></span>
                </div>
            </div>

            <div class="jenny-header-controls">
                <!-- Minimize Button -->
                <button type="button" @click="minimizeChat()" class="jenny-control-btn" aria-label="Minimize">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5"/></svg>
                </button>
                <!-- Close Button -->
                <button type="button" @click="closeChat()" class="jenny-control-btn" aria-label="Close">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </div>

        <!-- Role / Persona Banner & Switcher (Job Seeker vs Recruiter - No Emojis, Clean SVGs) -->
        <div class="jenny-role-pill-bar" x-show="!isMinimized">
            <div class="jenny-role-tag">
                <template x-if="persona === 'jobseeker'">
                    <span style="display: inline-flex; align-items: center; gap: 6px;">
                        <svg class="jenny-role-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        <span>Job Seeker (<?= htmlspecialchars($jobSeekerName) ?>)</span>
                    </span>
                </template>
                <template x-if="persona === 'recruiter'">
                    <span style="display: inline-flex; align-items: center; gap: 6px;">
                        <svg class="jenny-role-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="14" x="2" y="7" rx="2" ry="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>
                        <span>Recruiter (<?= htmlspecialchars($recruiterName) ?>)</span>
                    </span>
                </template>
            </div>
            <button type="button" 
                    @click="togglePersona()"
                    class="jenny-role-switch-btn"
                    aria-label="Switch Persona">
                <span>Switch to <span x-text="persona === 'jobseeker' ? 'Recruiter' : 'Job Seeker'"></span></span>
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M7 16V4m0 0L3 8m4-4l4 4m6 4v12m0 0l4-4m-4 4l-4-4"/></svg>
            </button>
        </div>

        <!-- Chat Stream Body -->
        <div class="jenny-body" x-ref="chatScroll" x-show="!isMinimized">

            <!-- ── A. Welcome Screen View ── -->
            <div x-show="screen === 'welcome' && messages.length === 0" class="jenny-welcome-hero">
                <div class="jenny-hero-mascot-wrap">
                    <img src="<?= $wavingImg ?>" alt="Jenny waving" class="jenny-hero-mascot-img">
                </div>
                <h3 class="jenny-welcome-heading">Hi, I'm Jenny.<br>How can I help you today?</h3>
                <p class="jenny-welcome-sub">I'm your AI assistant for internships, jobs and career support on InternConnect.</p>

                <!-- Job Seeker Action Buttons -->
                <template x-if="persona === 'jobseeker'">
                    <div class="jenny-actions-grid">
                        <button type="button" @click="triggerAction('update_cv')" class="jenny-action-card">
                            <svg class="jenny-action-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            <span>Update my CV</span>
                        </button>
                        <button type="button" @click="triggerAction('find_internships')" class="jenny-action-card">
                            <svg class="jenny-action-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            <span>Find internships</span>
                        </button>
                        <button type="button" @click="triggerAction('find_internships')" class="jenny-action-card">
                            <svg class="jenny-action-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <span>Prepare for interview</span>
                        </button>
                        <button type="button" @click="triggerAction('find_internships')" class="jenny-action-card">
                            <svg class="jenny-action-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                            <span>Track applications</span>
                        </button>
                    </div>
                </template>

                <!-- Recruiter Action Buttons -->
                <template x-if="persona === 'recruiter'">
                    <div class="jenny-actions-grid">
                        <button type="button" @click="triggerAction('shortlist_candidates')" class="jenny-action-card">
                            <svg class="jenny-action-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            <span>Shortlist candidates</span>
                        </button>
                        <button type="button" @click="triggerAction('schedule_interview')" class="jenny-action-card">
                            <svg class="jenny-action-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <span>Schedule interviews</span>
                        </button>
                        <button type="button" @click="triggerAction('find_internships')" class="jenny-action-card">
                            <svg class="jenny-action-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            <span>Draft a job post</span>
                        </button>
                        <button type="button" @click="triggerAction('shortlist_candidates')" class="jenny-action-card">
                            <svg class="jenny-action-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                            <span>Compare applicants</span>
                        </button>
                    </div>
                </template>
            </div>

            <!-- ── B. Dynamic Messages Stream ── -->
            <template x-for="item in messages" :key="item.id">
                <div>
                    <div class="jenny-msg-row" :class="item.sender">
                        <!-- Mini bot avatar -->
                        <template x-if="item.sender === 'bot'">
                            <div class="jenny-bot-avatar-mini" title="Jenny">
                                <img src="<?= $iconBadgeImg ?>" alt="Jenny">
                            </div>
                        </template>

                        <!-- Bubble -->
                        <div class="jenny-bubble" x-text="item.text"></div>
                    </div>

                    <!-- 1. Special: Upload File Progress Card -->
                    <template x-if="item.specialType === 'upload_card'">
                        <div class="jenny-upload-card">
                            <div class="jenny-upload-file-row">
                                <div class="jenny-upload-file-left">
                                    <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="#4F48E6" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    <div>
                                        <div style="font-weight: 700; font-size: 13px; color: #000623;"><?= htmlspecialchars($jobSeekerName) ?>_CV.pdf</div>
                                        <div style="font-size: 11px; color: #64748B;">2.4 MB</div>
                                    </div>
                                </div>
                                <div style="font-size: 12px; font-weight: 700; color: #4F48E6;">
                                    <span x-show="!uploadDone" x-text="'Uploading... ' + uploadProgress + '%'"></span>
                                    <span x-show="uploadDone" style="color: #10B981; display: inline-flex; align-items: center; gap: 4px; white-space: nowrap;">
                                        <span>Upload complete</span>
                                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#10B981" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6L9 17l-5-5"/></svg>
                                    </span>
                                </div>
                            </div>
                            <div class="jenny-progress-track">
                                <div class="jenny-progress-fill" :style="'width: ' + uploadProgress + '%;'"></div>
                            </div>
                        </div>
                    </template>

                    <!-- 2. Special: CV Review Score Card (Radial Score 78% + Suggestions) -->
                    <template x-if="item.specialType === 'cv_review_card'">
                        <div class="jenny-review-card">
                            <div style="font-weight: 800; font-size: 14px; color: #000623; margin-bottom: 8px;">CV Review</div>
                            <div class="jenny-score-row">
                                <div class="jenny-score-circle">78%</div>
                                <div>
                                    <div style="font-weight: 700; font-size: 13px; color: #000623;">Overall Score</div>
                                    <div style="font-size: 12px; color: #64748B;">Good! Your CV is competitive.</div>
                                </div>
                            </div>

                            <div style="font-weight: 700; font-size: 12px; color: #000623; margin-bottom: 6px;">Suggestions:</div>
                            
                            <!-- Suggestion 1 -->
                            <div class="jenny-suggestion-item">
                                <div class="jenny-suggestion-text">Add a professional summary to showcase your career goals.</div>
                                <button type="button" 
                                        @click="applySuggestion(1)" 
                                        class="jenny-apply-btn" 
                                        :class="{ 'is-applied': appliedSuggestions.includes(1) }"
                                        aria-label="Apply suggestion 1">
                                    <template x-if="!appliedSuggestions.includes(1)">
                                        <span>Apply</span>
                                    </template>
                                    <template x-if="appliedSuggestions.includes(1)">
                                        <span style="display: inline-flex; align-items: center; gap: 4px; white-space: nowrap;">
                                            <span>Applied</span>
                                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink: 0;"><path d="M20 6L9 17l-5-5"/></svg>
                                        </span>
                                    </template>
                                </button>
                            </div>

                            <!-- Suggestion 2 -->
                            <div class="jenny-suggestion-item">
                                <div class="jenny-suggestion-text">Quantify achievements with clear numbers and percentage results.</div>
                                <button type="button" 
                                        @click="applySuggestion(2)" 
                                        class="jenny-apply-btn" 
                                        :class="{ 'is-applied': appliedSuggestions.includes(2) }"
                                        aria-label="Apply suggestion 2">
                                    <template x-if="!appliedSuggestions.includes(2)">
                                        <span>Apply</span>
                                    </template>
                                    <template x-if="appliedSuggestions.includes(2)">
                                        <span style="display: inline-flex; align-items: center; gap: 4px; white-space: nowrap;">
                                            <span>Applied</span>
                                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink: 0;"><path d="M20 6L9 17l-5-5"/></svg>
                                        </span>
                                    </template>
                                </button>
                            </div>

                            <!-- Suggestion 3 -->
                            <div class="jenny-suggestion-item" style="border-bottom: none;">
                                <div class="jenny-suggestion-text">Include relevant industry tools and frameworks.</div>
                                <button type="button" 
                                        @click="applySuggestion(3)" 
                                        class="jenny-apply-btn" 
                                        :class="{ 'is-applied': appliedSuggestions.includes(3) }"
                                        aria-label="Apply suggestion 3">
                                    <template x-if="!appliedSuggestions.includes(3)">
                                        <span>Apply</span>
                                    </template>
                                    <template x-if="appliedSuggestions.includes(3)">
                                        <span style="display: inline-flex; align-items: center; gap: 4px; white-space: nowrap;">
                                            <span>Applied</span>
                                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink: 0;"><path d="M20 6L9 17l-5-5"/></svg>
                                        </span>
                                    </template>
                                </button>
                            </div>
                        </div>
                    </template>

                    <!-- 3. Special: Before & After Card -->
                    <template x-if="item.specialType === 'before_after_card' && !cvAccepted">
                        <div class="jenny-compare-card">
                            <div style="font-weight: 800; font-size: 13.5px; color: #000623; margin-bottom: 8px;">Your Improved CV Preview</div>
                            <div class="jenny-compare-grid">
                                <div class="jenny-compare-col">
                                    <strong>Before</strong>
                                    Assisted in daily tasks at a small business. Learned new skills and worked with a team.
                                </div>
                                <div class="jenny-compare-col" style="background: #EEF2FF; border: 1px solid #4F48E6;">
                                    <strong style="color: #4F48E6;">After</strong>
                                    Supported a team of 5 to deliver projects 20% ahead of schedule. Improved process efficiency by 15%.
                                </div>
                            </div>
                            <button type="button" @click="acceptImprovedCv()" style="width: 100%; background: #4F48E6; color: #FFFFFF; font-weight: 700; font-size: 13px; border: none; border-radius: 8px; padding: 10px; cursor: pointer; margin-bottom: 6px;">
                                Accept Changes
                            </button>
                            <div style="display: flex; gap: 8px;">
                                <button type="button" style="flex: 1; background: #F2F2F2; color: #000623; border: none; border-radius: 8px; padding: 7px; font-size: 12px; font-weight: 600; cursor: pointer;">Edit</button>
                                <button type="button" style="flex: 1; background: #F2F2F2; color: #000623; border: none; border-radius: 8px; padding: 7px; font-size: 12px; font-weight: 600; cursor: pointer;">Regenerate</button>
                            </div>
                        </div>
                    </template>

                    <!-- 4. Special: CV Success Card -->
                    <template x-if="item.specialType === 'cv_success_card'">
                        <div style="margin-top: 8px; text-align: center;">
                            <a href="<?= url('/internships') ?>" style="display: inline-block; width: 100%; background: #4F48E6; color: #FFFFFF; font-weight: 700; font-size: 13.5px; padding: 12px; border-radius: 10px; text-decoration: none; text-align: center; box-shadow: 0 4px 14px rgba(79, 72, 230, 0.28);">
                                Show matches &rarr;
                            </a>
                        </div>
                    </template>

                    <!-- 5. Special: Candidate Shortlist (Recruiter) -->
                    <template x-if="item.specialType === 'candidate_shortlist'">
                        <div style="margin-top: 8px;">
                            <!-- Candidate 1 -->
                            <div class="jenny-candidate-item">
                                <div style="display: flex; align-items: center; gap: 8px;">
                                    <div style="width: 32px; height: 32px; border-radius: 50%; background: #4F48E6; color: #FFFFFF; display: flex; align-items: center; justify-content: center; font-weight: 700;">CE</div>
                                    <div>
                                        <div style="font-weight: 700; color: #000623;">Chinonso Eze</div>
                                        <div style="font-size: 11px; color: #64748B;">Lagos, Nigeria</div>
                                    </div>
                                </div>
                                <div style="text-align: right;">
                                    <span style="font-weight: 800; color: #4F48E6; font-size: 11.5px;">96% match</span>
                                    <button type="button" @click="triggerAction('schedule_interview')" style="display: block; margin-top: 3px; background: #4F48E6; color: #FFFFFF; border: none; border-radius: 6px; padding: 3px 8px; font-size: 10.5px; font-weight: 600; cursor: pointer;">
                                        Invite to interview
                                    </button>
                                </div>
                            </div>

                            <!-- Candidate 2 -->
                            <div class="jenny-candidate-item">
                                <div style="display: flex; align-items: center; gap: 8px;">
                                    <div style="width: 32px; height: 32px; border-radius: 50%; background: #10B981; color: #FFFFFF; display: flex; align-items: center; justify-content: center; font-weight: 700;">AO</div>
                                    <div>
                                        <div style="font-weight: 700; color: #000623;">Amara Okafor</div>
                                        <div style="font-size: 11px; color: #64748B;">Abuja, Nigeria</div>
                                    </div>
                                </div>
                                <div style="text-align: right;">
                                    <span style="font-weight: 800; color: #4F48E6; font-size: 11.5px;">88% match</span>
                                    <button type="button" @click="triggerAction('schedule_interview')" style="display: block; margin-top: 3px; background: #4F48E6; color: #FFFFFF; border: none; border-radius: 6px; padding: 3px 8px; font-size: 10.5px; font-weight: 600; cursor: pointer;">
                                        Invite to interview
                                    </button>
                                </div>
                            </div>
                        </div>
                    </template>

                    <!-- 6. Special: Schedule Interview Card (Premium Redesign - Zero Emojis) -->
                    <template x-if="item.specialType === 'schedule_card' && !interviewConfirmed">
                        <div class="jenny-schedule-card">
                            <!-- Header Row -->
                            <div class="jenny-schedule-header">
                                <div class="jenny-schedule-badge">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polygon points="23 7 16 12 23 17 23 7"></polygon><rect x="1" y="5" width="15" height="14" rx="2" ry="2"></rect></svg>
                                    <span>Online Video Interview</span>
                                </div>
                                <span class="jenny-schedule-duration">30 mins</span>
                            </div>

                            <div class="jenny-schedule-candidate-info">
                                <span class="jenny-schedule-label">Candidate</span>
                                <span class="jenny-schedule-name">Chinonso Eze &bull; Product Design Intern</span>
                            </div>

                            <!-- Date Box with clean SVG Calendar Icon (NO EMOJIS) -->
                            <div class="jenny-schedule-date-box">
                                <div class="jenny-schedule-date-left">
                                    <svg class="jenny-schedule-cal-icon" viewBox="0 0 24 24" fill="none" stroke="#4F48E6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                        <line x1="16" y1="2" x2="16" y2="6"></line>
                                        <line x1="8" y1="2" x2="8" y2="6"></line>
                                        <line x1="3" y1="10" x2="21" y2="10"></line>
                                    </svg>
                                    <div class="jenny-schedule-date-text">
                                        <div class="jenny-schedule-date-day">Tuesday, 18 Nov 2026</div>
                                        <div class="jenny-schedule-date-tz">West Africa Time (WAT)</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Available Slots Selection Grid -->
                            <div class="jenny-schedule-slots-label">Select Available Slot:</div>
                            <div class="jenny-schedule-slots-grid">
                                <template x-for="slot in ['9:00 AM', '10:30 AM', '2:00 PM', '4:00 PM', '5:30 PM']" :key="slot">
                                    <button type="button" 
                                            @click="selectedTimeSlot = slot"
                                            class="jenny-slot-btn"
                                            :class="{ 'is-selected': selectedTimeSlot === slot }"
                                            :aria-pressed="selectedTimeSlot === slot">
                                        <svg x-show="selectedTimeSlot === slot" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink: 0;"><path d="M20 6L9 17l-5-5"/></svg>
                                        <span x-text="slot"></span>
                                    </button>
                                </template>
                            </div>

                            <!-- Confirm CTA -->
                            <button type="button" @click="confirmInterview()" class="jenny-schedule-confirm-btn">
                                <span>Confirm Slot: <strong x-text="selectedTimeSlot"></strong></span>
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14m-7-7l7 7-7 7"/></svg>
                            </button>
                        </div>
                    </template>

                    <template x-if="item.specialType === 'schedule_card' && interviewConfirmed">
                        <div class="jenny-schedule-success-card">
                            <div class="jenny-success-icon-wrap">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#10B981" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                            </div>
                            <div class="jenny-success-details">
                                <div class="jenny-success-title">Interview Scheduled</div>
                                <div class="jenny-success-time">Tue, 18 Nov 2026 at <span x-text="selectedTimeSlot"></span></div>
                                <div class="jenny-success-note">Google Meet invitation dispatched to Chinonso Eze</div>
                            </div>
                        </div>
                    </template>
                </div>
            </template>

            <!-- ── Typing Indicator with Thinking Mascot Pose ── -->
            <div x-show="isTyping" class="jenny-typing-row" x-cloak>
                <img src="<?= $thinkingImg ?>" alt="Thinking Jenny" class="jenny-thinking-mascot">
                <div class="jenny-dots-wrap">
                    <span class="jenny-dot"></span>
                    <span class="jenny-dot"></span>
                    <span class="jenny-dot"></span>
                </div>
            </div>

        </div> <!-- /jenny-body -->

        <!-- Footer Input (White #FFFFFF, Light Grey #F2F2F2) -->
        <div class="jenny-footer" x-show="!isMinimized">
            <form @submit.prevent="sendMessage()" class="jenny-input-form">
                <!-- Attachment Icon (Clip) -->
                <label class="jenny-attach-icon" title="Upload Document / Image">
                    <input type="file" @change="triggerAction('update_cv')" style="display: none;">
                    <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                    </svg>
                </label>

                <!-- Input Text -->
                <input type="text" 
                       x-model="inputText" 
                       placeholder="Ask Jenny anything..." 
                       class="jenny-input-field" 
                       aria-label="Ask Jenny anything">

                <!-- Send Button -->
                <button type="submit" class="jenny-send-btn" aria-label="Send">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </button>
            </form>

            <!-- Powered by InternConnect AI with Guaranteed Inline Brand SVG -->
            <div class="jenny-powered-tag">
                <svg width="13" height="15" viewBox="0 0 28 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M13.9334 0.00117504C15.2436 0.013806 16.5208 0.414069 17.6039 1.1515C18.687 1.88893 19.5275 2.93052 20.0195 4.14496C20.5115 5.35939 20.633 6.69231 20.3685 7.97565C20.1041 9.259 19.4656 10.4353 18.5336 11.3564C17.6016 12.2774 16.4178 12.9018 15.1314 13.151C13.845 13.4002 12.5136 13.263 11.3051 12.7566C10.0966 12.2503 9.06505 11.3974 8.34052 10.3057C7.61598 9.21391 7.2309 7.93207 7.23381 6.62177C7.22762 5.74393 7.39728 4.87374 7.73277 4.06252C8.06826 3.25129 8.56279 2.51546 9.1872 1.89841C9.81161 1.28136 10.5533 0.795594 11.3684 0.469749C12.1835 0.143903 13.0557 -0.0154196 13.9334 0.00117504Z" fill="#4F48E6"/>
                    <path d="M27.7388 13.2846V23.6218C25.5532 23.5558 23.4266 24.3363 21.8027 25.8005C20.1787 27.2646 19.1829 29.2993 19.0229 31.48C19.0041 31.6568 18.9891 31.8561 18.9815 32.0254H8.86632C8.86632 31.9276 8.83247 31.8298 8.81366 31.7358C7.798 26.3302 6.17295 24.8631 0 23.5127V13.3561C5.01811 12.9498 10.2017 15.2369 13.8882 19.104C17.3038 15.5003 22.1903 13.0702 27.7388 13.2846Z" fill="#4F48E6"/>
                </svg>
                <span>Powered by InternConnect AI</span>
            </div>
        </div>

    </div> <!-- /jenny-window -->

</div> <!-- /jenny-ai-root -->
