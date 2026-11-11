<?php

declare(strict_types=1);

/**
 * View: Refund Policy
 * Route: /refund
 * Layout: app
 */
?>
<section class="ic-page-shell" x-data="{ activeSection: 'overview' }">

    <!-- Page Hero Banner -->
    <header class="ic-page-hero">
        <div class="ic-container">
            <span class="ic-badge">
                <svg style="width: 14px; height: 14px; flex-shrink: 0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                </svg>
                Billing Transparency &amp; Protection
            </span>
            <h1 class="ic-page-hero-title">Refund &amp; Cancellation Policy</h1>
            <p class="ic-page-hero-subtitle">
                Clear, transparent terms regarding candidate Pro subscriptions, employer hiring tiers, Paystack payment processing, and dispute resolutions.
            </p>
        </div>
    </header>

    <!-- Main Content Grid -->
    <div class="ic-container">
        <div class="legal-grid-wrap">

            <!-- Sticky Navigation Column (TOC) -->
            <aside class="legal-toc-card" aria-label="Table of contents">
                <div class="legal-toc-title">
                    <svg style="width: 14px; height: 14px; color: var(--ic-primary); flex-shrink: 0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h7"/>
                    </svg>
                    <span>Contents</span>
                </div>
                <nav>
                    <ul class="legal-toc-list">
                        <li><a href="#overview" class="legal-toc-link" :class="{ 'is-active': activeSection === 'overview' }" @click="activeSection = 'overview'">1. Overview</a></li>
                        <li><a href="#candidate-refunds" class="legal-toc-link" :class="{ 'is-active': activeSection === 'candidate-refunds' }" @click="activeSection = 'candidate-refunds'">2. Candidate Pro Plans</a></li>
                        <li><a href="#employer-refunds" class="legal-toc-link" :class="{ 'is-active': activeSection === 'employer-refunds' }" @click="activeSection = 'employer-refunds'">3. Employer Plans</a></li>
                        <li><a href="#non-refundable" class="legal-toc-link" :class="{ 'is-active': activeSection === 'non-refundable' }" @click="activeSection = 'non-refundable'">4. Non-Refundable Cases</a></li>
                        <li><a href="#processing-times" class="legal-toc-link" :class="{ 'is-active': activeSection === 'processing-times' }" @click="activeSection = 'processing-times'">5. Reversal Timelines</a></li>
                        <li><a href="#how-to-request" class="legal-toc-link" :class="{ 'is-active': activeSection === 'how-to-request' }" @click="activeSection = 'how-to-request'">6. How to Request</a></li>
                    </ul>
                </nav>
            </aside>

            <!-- Main Legal Article -->
            <article class="legal-content-card">
                
                <!-- Metadata Strip -->
                <div class="legal-meta-strip">
                    <div class="legal-meta-item">
                        <svg style="width: 15px; height: 15px; color: var(--ic-text-subtle); flex-shrink: 0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span>Last Updated: <strong>October 20, 2026</strong></span>
                    </div>
                    <div class="legal-meta-item">
                        <svg style="width: 15px; height: 15px; color: var(--ic-text-subtle); flex-shrink: 0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        <span>Payment Processor: <strong>Paystack Gateway</strong></span>
                    </div>
                    <div class="legal-meta-item">
                        <span class="ic-badge ic-badge-success">7-Day Cooling Off</span>
                    </div>
                </div>

                <!-- Section 1 -->
                <section id="overview" class="legal-section">
                    <h2 class="legal-section-title">
                        <svg style="width: 20px; height: 20px; color: var(--ic-primary); flex-shrink: 0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>1. Overview &amp; Commitment</span>
                    </h2>
                    <div class="legal-section-body">
                        <p>
                            InternConnect strives to provide the highest-value internship matching platform for both ambitious students and growth-oriented employers. We believe in clear, fair billing without hidden fees or lock-ins.
                        </p>
                        <p>
                            This policy outlines the exact conditions under which subscription fees or posting upgrades may be canceled, credited, or refunded to your original payment method.
                        </p>
                    </div>
                </section>

                <!-- Section 2 -->
                <section id="candidate-refunds" class="legal-section">
                    <h2 class="legal-section-title">
                        <svg style="width: 20px; height: 20px; color: var(--ic-primary); flex-shrink: 0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        <span>2. Candidate Pro Plan Refunds</span>
                    </h2>
                    <div class="legal-section-body">
                        <p>
                            Candidates who purchase a <strong>Pro Student Upgrade</strong> are entitled to:
                        </p>
                        <ul>
                            <li><strong>7-Day Cooling-Off Period:</strong> You may request a 100% full refund within 7 days of initial subscription purchase, provided you have not utilized premium features (such as generating more than 2 AI mock interview sessions or requesting priority human application review).</li>
                            <li><strong>Subscription Cancellation:</strong> You can cancel auto-renewal at any time from your Account Settings. Your Pro access remains active until the end of your prepaid billing month with no further renewal charges.</li>
                        </ul>
                    </div>
                </section>

                <!-- Section 3 -->
                <section id="employer-refunds" class="legal-section">
                    <h2 class="legal-section-title">
                        <svg style="width: 20px; height: 20px; color: var(--ic-primary); flex-shrink: 0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                        <span>3. Employer Hiring Plans (Starter, Growth, Enterprise)</span>
                    </h2>
                    <div class="legal-section-body">
                        <p>
                            Employers purchasing tiered job posting plans are eligible for refunds under the following guidelines:
                        </p>
                        <ul>
                            <li><strong>Unpublished Role Quota:</strong> If you purchase a plan and do not publish an internship role within 14 days, you may request a full refund of your subscription fee.</li>
                            <li><strong>Unresolved Technical Downtime:</strong> If a documented technical platform fault prevents candidates from applying to your active postings for more than 72 consecutive hours, you will receive a pro-rata billing credit or full monthly refund upon request.</li>
                        </ul>
                    </div>
                </section>

                <!-- Section 4 -->
                <section id="non-refundable" class="legal-section">
                    <h2 class="legal-section-title">
                        <svg style="width: 20px; height: 20px; color: var(--ic-primary); flex-shrink: 0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        <span>4. Non-Refundable Scenarios</span>
                    </h2>
                    <div class="legal-section-body">
                        <p>Refunds will NOT be granted in the following situations:</p>
                        <ul>
                            <li>The employer has published an internship role and received verified candidate applications.</li>
                            <li>An account has been suspended or banned due to fraudulent CAC verification, deceptive job descriptions, or charging fees to students.</li>
                            <li>Refund requests submitted after the standard 7-day (Candidate) or 14-day (Employer) eligibility windows.</li>
                            <li>Failure of a candidate to secure an interview offer or an employer to hire an intern, as placement outcomes depend on mutual candidate-recruiter evaluations.</li>
                        </ul>
                    </div>
                </section>

                <!-- Section 5 -->
                <section id="processing-times" class="legal-section">
                    <h2 class="legal-section-title">
                        <svg style="width: 20px; height: 20px; color: var(--ic-primary); flex-shrink: 0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>5. Reversal Timelines &amp; Gateway Fees</span>
                    </h2>
                    <div class="legal-section-body">
                        <p>
                            Approved refunds are credited directly back to the original funding source (debit card, bank transfer, or account) via the Paystack gateway:
                        </p>
                        <ul>
                            <li><strong>Processing Window:</strong> Once approved by our finance department, refunds typically settle within <strong>5 to 10 business days</strong>, depending on your commercial bank's interbank clearing cycles.</li>
                            <li><strong>Currency:</strong> Refunds are remitted in the original transaction currency (NGN). We are not responsible for foreign exchange variations or international bank conversion charges.</li>
                        </ul>
                    </div>
                </section>

                <!-- Section 6 -->
                <section id="how-to-request" class="legal-section" style="margin-bottom: 0;">
                    <h2 class="legal-section-title">
                        <svg style="width: 20px; height: 20px; color: var(--ic-primary); flex-shrink: 0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        <span>6. How to Submit a Refund Request</span>
                    </h2>
                    <div class="legal-section-body">
                        <p>
                            To initiate a review, email our dedicated billing resolution desk with your transaction details:
                        </p>
                        <div class="legal-highlight-box">
                            <div style="font-weight: 700; color: #000623; font-size: 14.5px; margin-bottom: 8px;">Required Submission Information:</div>
                            <ol style="margin: 0 0 16px 20px; font-size: 13.5px; color: #475569; line-height: 1.6;">
                                <li>Registered account email address and role (Candidate or Employer).</li>
                                <li>Paystack Transaction Reference Number (e.g. <code>T1234567890</code> found in your receipt email).</li>
                                <li>Brief reason for cancellation or refund request.</li>
                            </ol>
                            <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px; padding-top: 12px; border-top: 1px solid #E2E8F0;">
                                <div style="font-size: 13px; color: #4F48E6; font-weight: 700;">billing@internconnect.ng</div>
                                <a href="<?= url('/contact') ?>" class="ic-btn ic-btn-primary">
                                    <span>Submit Billing Ticket</span>
                                    <svg style="width: 14px; height: 14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </section>

            </article>

        </div>
    </div>

</section>
