<?php

declare(strict_types=1);

/**
 * Component: Landing Page - Frequently Asked Questions (FAQ) Section
 * Location: Directly below Call-to-Action / Newsletter Section (cta.php)
 * Two-column desktop layout:
 * - Left column: Top headline aligned with first item, bottom subtext & support button aligned with last item.
 * - Right column: 6 interactive single-open accordion cards with morphing plus/minus toggle icon.
 */

$faqItems = [
    [
        'question' => 'What payment methods does InternConnect support?',
        'answer' => 'InternConnect uses Paystack to process all payments securely. You can pay using your debit or credit card, bank transfer, or USSD, depending on what\'s available in your region. All transactions are encrypted and handled directly through Paystack\'s secure payment gateway, InternConnect does not store your card details.'
    ],
    [
        'question' => 'Is it safe to make payments on InternConnect?',
        'answer' => 'Yes. All payments are processed through Paystack, a trusted and widely used payment platform across Africa. Your card and bank information are never stored on InternConnect\'s servers, they are handled entirely by Paystack\'s secure, encrypted system.'
    ],
    [
        'question' => 'How do I create an account on InternConnect?',
        'answer' => 'Click "Sign Up" on the homepage, choose whether you\'re registering as a candidate or an employer, then enter your name, email address, and a password. You\'ll receive a confirmation email to verify your account before you can log in and start using the platform.'
    ],
    [
        'question' => 'I\'m having trouble logging in, what should I do?',
        'answer' => 'First, double-check that your email and password are correct. If you\'ve forgotten your password, click "Forgot Password" on the login page to receive a reset link by email. If you\'re still unable to log in after resetting your password, reach out to our support team for help.'
    ],
    [
        'question' => 'Can I switch between a candidate account and an employer account?',
        'answer' => 'Each InternConnect account is set up as either a candidate account or an employer account, based on what you selected during sign-up. If you need a different account type, you\'ll need to create a separate account with a different email address for that purpose.'
    ],
    [
        'question' => 'How does InternConnect match me with internships?',
        'answer' => 'Our AI matching engine analyzes your profile skills, academic background, and career interests against verified employer role specifications. You receive scored candidate rankings, tailored position recommendations, and instant alerts whenever matching opportunities go live.'
    ]
];
?>
<style>
/* ─── Frequently Asked Questions (FAQ) Section ─── */
.faq-section-wrap {
    position: relative;
    background-color: #F8FAFC;
    padding: 80px 0 108px 0;
    width: 100%;
    overflow: hidden;
    box-sizing: border-box;
    border-top: 1px solid #E2E8F0;
}

@media (min-width: 1024px) {
    .faq-section-wrap {
        padding: 104px 0 128px 0;
    }
}

.faq-container {
    max-width: 1240px;
    margin: 0 auto;
    padding: 0 24px;
    width: 100%;
    box-sizing: border-box;
}

/* ─── Two-Column Grid Layout (Desktop: 35% / 65%, Stretch for Top & Bottom Alignment) ─── */
.faq-grid {
    display: grid;
    grid-template-columns: 360px 1fr;
    gap: 48px;
    align-items: stretch;
    width: 100%;
    box-sizing: border-box;
}

@media (min-width: 1200px) {
    .faq-grid {
        grid-template-columns: 390px 1fr;
        gap: 64px;
    }
}

@media (max-width: 1023px) {
    .faq-grid {
        grid-template-columns: minmax(0, 1fr) !important;
        gap: 40px;
        align-items: start;
    }
}

/* ─── Left Column (Top Headline & Bottom-Anchored Support Block per Pixwave Reference) ─── */
.faq-left-col {
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    height: 100%;
    min-height: 100%;
    width: 100%;
    box-sizing: border-box;
}

@media (max-width: 1023px) {
    .faq-left-col {
        height: auto;
        min-height: auto;
        gap: 24px;
        justify-content: flex-start;
    }
}

.faq-left-top {
    display: flex;
    flex-direction: column;
    gap: 16px;
    width: 100%;
    box-sizing: border-box;
}

.faq-eyebrow-pill {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 6px 14px;
    border-radius: 9999px;
    background-color: #EEF2FF;
    color: #4F48E6;
    border: 1px solid #C7D2FE;
    font-size: 12px;
    font-weight: 700;
    letter-spacing: 0.04em;
    text-transform: uppercase;
    white-space: nowrap;
    width: fit-content;
}

.faq-eyebrow-dot {
    width: 7px;
    height: 7px;
    border-radius: 50%;
    background-color: #4F48E6;
}

.faq-headline {
    font-size: clamp(28px, 3.6vw, 42px);
    font-weight: 800;
    line-height: 1.15;
    color: #000623;
    letter-spacing: -0.025em;
    margin: 0;
    word-break: break-word;
}

/* Anchored to the very bottom of the FAQ section on desktop */
.faq-left-bottom {
    margin-top: auto;
    padding-top: 48px;
    display: flex;
    flex-direction: column;
    gap: 20px;
    width: 100%;
    box-sizing: border-box;
}

@media (max-width: 1023px) {
    .faq-left-bottom {
        margin-top: 0;
        padding-top: 0;
        gap: 18px;
    }
}

.faq-subtext {
    font-size: 15.5px;
    line-height: 1.6;
    color: #475569;
    margin: 0;
    max-width: 360px;
}

.faq-cta-row {
    display: flex;
    align-items: center;
}

/* Standard InternConnect Button (Rounded-lg, NOT pill) */
.faq-contact-btn {
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    gap: 8px !important;
    padding: 12px 24px !important;
    border-radius: 8px !important;
    background-color: #4F48E6 !important;
    color: #FFFFFF !important;
    font-size: 14.5px !important;
    font-weight: 600 !important;
    line-height: 1 !important;
    text-decoration: none !important;
    white-space: nowrap !important;
    flex-shrink: 0 !important;
    box-shadow: 0 4px 14px rgba(79, 72, 230, 0.25) !important;
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1) !important;
    width: fit-content;
}

.faq-contact-btn:hover {
    background-color: #4338CA !important;
    transform: translateY(-1px) !important;
    box-shadow: 0 6px 20px rgba(79, 72, 230, 0.35) !important;
}

.faq-contact-arrow {
    width: 16px;
    height: 16px;
    flex-shrink: 0;
    transition: transform 0.2s ease;
}

.faq-contact-btn:hover .faq-contact-arrow {
    transform: translateX(3px);
}

/* ─── Right Column (Accordion List) ─── */
.faq-accordion-list {
    display: flex;
    flex-direction: column;
    gap: 14px;
    width: 100%;
    min-width: 0;
    box-sizing: border-box;
}

/* ─── Accordion Item Card ─── */
.faq-item-card {
    background-color: #FFFFFF;
    border: 1px solid #E2E8F0;
    border-radius: 18px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0, 6, 35, 0.03);
    transition: border-color 0.25s ease, box-shadow 0.25s ease;
    width: 100%;
    box-sizing: border-box;
}

.faq-item-card.is-active {
    border-color: #CBD5E1;
    box-shadow: 0 6px 22px rgba(0, 6, 35, 0.06);
}

/* ─── Accordion Question Trigger Button ─── */
.faq-question-btn {
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    padding: 22px 26px;
    background: none;
    border: none;
    cursor: pointer;
    text-align: left;
    font-family: inherit;
    user-select: none;
    box-sizing: border-box;
    transition: background-color 0.15s ease;
}

.faq-question-btn:hover {
    background-color: #FAFAFC;
}

.faq-question-text {
    font-size: 16.5px;
    font-weight: 700;
    color: #000623;
    line-height: 1.35;
    margin: 0;
    flex: 1;
    min-width: 0;
}

/* ─── Circular Toggle Button with Morphing Animation (+ to −) ─── */
.faq-toggle-circle {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    border: 1.5px solid #E2E8F0;
    background-color: #FFFFFF;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    color: #000623;
    transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
}

.faq-item-card.is-active .faq-toggle-circle {
    background-color: #EEF2FF;
    border-color: #C7D2FE;
    color: #4F48E6;
}

.faq-toggle-svg {
    width: 16px;
    height: 16px;
    display: block;
    flex-shrink: 0;
}

/* Vertical bar scales down and fades out, morphing + into − */
.faq-icon-vbar {
    transition: transform 0.25s cubic-bezier(0.4, 0, 0.2, 1), opacity 0.2s ease;
    transform-origin: 12px 12px;
}

.faq-item-card.is-active .faq-icon-vbar {
    transform: scaleY(0);
    opacity: 0;
}

.faq-icon-hbar {
    transition: transform 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    transform-origin: 12px 12px;
}

.faq-item-card.is-active .faq-icon-hbar {
    transform: rotate(180deg);
}

/* ─── Smooth Height & Opacity Collapse ─── */
.faq-answer-collapse {
    display: grid;
    grid-template-rows: 0fr;
    transition: grid-template-rows 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.faq-answer-collapse.is-open {
    grid-template-rows: 1fr;
}

.faq-answer-inner {
    overflow: hidden;
}

.faq-answer-text {
    font-size: 15px;
    line-height: 1.65;
    color: #64748B;
    margin: 0;
    padding: 0 26px 22px 26px;
    opacity: 0;
    transform: translateY(-4px);
    transition: opacity 0.25s ease 0.05s, transform 0.25s ease 0.05s;
}

.faq-answer-collapse.is-open .faq-answer-text {
    opacity: 1;
    transform: translateY(0);
}

/* ─── Mobile Viewport Responsiveness (360px - 640px) ─── */
@media (max-width: 640px) {
    .faq-section-wrap {
        padding: 48px 0 72px 0;
    }
    
    .faq-container {
        padding: 0 16px;
    }
    
    .faq-question-btn {
        padding: 18px 18px;
        gap: 12px;
    }
    
    .faq-question-text {
        font-size: 15px;
    }
    
    .faq-toggle-circle {
        width: 32px;
        height: 32px;
    }
    
    .faq-answer-text {
        font-size: 14px;
        padding: 0 18px 18px 18px;
        line-height: 1.6;
    }
    
    .faq-contact-btn {
        width: 100% !important;
        justify-content: center !important;
    }
}
</style>

<section class="faq-section-wrap" id="faq-section" x-data="{ activeFaq: 0, toggleFaq(idx) { this.activeFaq = this.activeFaq === idx ? null : idx; } }">
    <div class="faq-container">
        
        <div class="faq-grid">
            
            <!-- Left Column: Top Headline & Bottom Support Block (Pixwave Reference) -->
            <div class="faq-left-col">
                <div class="faq-left-top">
                    <div class="faq-eyebrow-pill">
                        <span class="faq-eyebrow-dot" aria-hidden="true"></span>
                        <span>Frequently Asked Questions</span>
                    </div>
                    
                    <h2 class="faq-headline">
                        Questions our users ask us.
                    </h2>
                </div>
                
                <div class="faq-left-bottom">
                    <p class="faq-subtext">
                        Our friendly team is always here to help you with quick, clear, and reliable answers whenever you need them.
                    </p>
                    
                    <div class="faq-cta-row">
                        <a href="<?= url('/help') ?>" class="faq-contact-btn" id="faq-help-support-btn">
                            <span>Help & Support</span>
                            <svg class="faq-contact-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                                <polyline points="12 5 19 12 12 19"></polyline>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Right Column: Accordion List (6 High-Impact Questions, 1st Pre-Opened) -->
            <div class="faq-accordion-list" role="region" aria-label="FAQ Accordion">
                <?php foreach ($faqItems as $idx => $item): ?>
                    <div 
                        class="faq-item-card" 
                        :class="{ 'is-active': activeFaq === <?= $idx ?> }"
                    >
                        <button 
                            type="button"
                            class="faq-question-btn"
                            @click="toggleFaq(<?= $idx ?>)"
                            :aria-expanded="activeFaq === <?= $idx ?> ? 'true' : 'false'"
                            aria-controls="faq-answer-<?= $idx ?>"
                            id="faq-header-<?= $idx ?>"
                        >
                            <span class="faq-question-text"><?= htmlspecialchars($item['question']) ?></span>
                            <span class="faq-toggle-circle" aria-hidden="true">
                                <svg class="faq-toggle-svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round">
                                    <line class="faq-icon-hbar" x1="5" y1="12" x2="19" y2="12"></line>
                                    <line class="faq-icon-vbar" x1="12" y1="5" x2="12" y2="19"></line>
                                </svg>
                            </span>
                        </button>
                        
                        <div 
                            class="faq-answer-collapse" 
                            :class="{ 'is-open': activeFaq === <?= $idx ?> }"
                            id="faq-answer-<?= $idx ?>"
                            role="region"
                            aria-labelledby="faq-header-<?= $idx ?>"
                        >
                            <div class="faq-answer-inner">
                                <p class="faq-answer-text">
                                    <?= htmlspecialchars($item['answer']) ?>
                                </p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

        </div>

    </div>
</section>
