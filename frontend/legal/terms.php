<?php

declare(strict_types=1);

/**
 * View: Terms of Service
 * Route: /terms
 * Layout: app
 */
?>
<section class="ic-page-shell" x-data="{ activeSection: 'acceptance' }">

    <!-- Page Hero Banner -->
    <header class="ic-page-hero">
        <div class="ic-container">
            <span class="ic-badge">
                <svg style="width: 14px; height: 14px; flex-shrink: 0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Legal Agreement &amp; Operating Rules
            </span>
            <h1 class="ic-page-hero-title">Terms of Service</h1>
            <p class="ic-page-hero-subtitle">
                Please read these terms carefully. By registering an account as a student applicant or employer organization on InternConnect, you agree to be bound by these operating rules.
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
                        <li><a href="#acceptance" class="legal-toc-link" :class="{ 'is-active': activeSection === 'acceptance' }" @click="activeSection = 'acceptance'">1. Acceptance &amp; Eligibility</a></li>
                        <li><a href="#candidate-rules" class="legal-toc-link" :class="{ 'is-active': activeSection === 'candidate-rules' }" @click="activeSection = 'candidate-rules'">2. Candidate Obligations</a></li>
                        <li><a href="#employer-rules" class="legal-toc-link" :class="{ 'is-active': activeSection === 'employer-rules' }" @click="activeSection = 'employer-rules'">3. Employer Obligations</a></li>
                        <li><a href="#verification-policy" class="legal-toc-link" :class="{ 'is-active': activeSection === 'verification-policy' }" @click="activeSection = 'verification-policy'">4. Corporate Verification</a></li>
                        <li><a href="#payments-billing" class="legal-toc-link" :class="{ 'is-active': activeSection === 'payments-billing' }" @click="activeSection = 'payments-billing'">5. Subscriptions &amp; Billing</a></li>
                        <li><a href="#intellectual-prop" class="legal-toc-link" :class="{ 'is-active': activeSection === 'intellectual-prop' }" @click="activeSection = 'intellectual-prop'">6. Intellectual Property</a></li>
                        <li><a href="#suspension-rules" class="legal-toc-link" :class="{ 'is-active': activeSection === 'suspension-rules' }" @click="activeSection = 'suspension-rules'">7. Termination &amp; Bans</a></li>
                        <li><a href="#liability-law" class="legal-toc-link" :class="{ 'is-active': activeSection === 'liability-law' }" @click="activeSection = 'liability-law'">8. Governing Law</a></li>
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
                        <span>Effective Date: <strong>November 12, 2026</strong></span>
                    </div>
                    <div class="legal-meta-item">
                        <svg style="width: 15px; height: 15px; color: var(--ic-text-subtle); flex-shrink: 0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/>
                        </svg>
                        <span>Jurisdiction: <strong>Federal Republic of Nigeria</strong></span>
                    </div>
                    <div class="legal-meta-item">
                        <span class="ic-badge ic-badge-neutral">Standard Term 2026</span>
                    </div>
                </div>

                <!-- Section 1 -->
                <section id="acceptance" class="legal-section">
                    <h2 class="legal-section-title">
                        <svg style="width: 20px; height: 20px; color: var(--ic-primary); flex-shrink: 0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>1. Acceptance of Terms &amp; Eligibility</span>
                    </h2>
                    <div class="legal-section-body">
                        <p>
                            These Terms of Service constitute a legally binding agreement between you and InternConnect Global Limited ("InternConnect", "we", "us"). By accessing or using our websites, APIs, or mobile applications, you confirm that you have read, understood, and agreed to these terms.
                        </p>
                        <p>
                            <strong>Eligibility Requirements:</strong>
                        </p>
                        <ul>
                            <li><strong>Candidate Accounts:</strong> You must be at least 16 years of age and currently enrolled in, or recently graduated from, a recognized tertiary education institution (university, polytechnic, or vocational college).</li>
                            <li><strong>Employer Accounts:</strong> You must be an authorized representative of a legitimate, legally registered business entity or organization holding a valid Certificate of Incorporation or commercial registry number.</li>
                        </ul>
                    </div>
                </section>

                <!-- Section 2 -->
                <section id="candidate-rules" class="legal-section">
                    <h2 class="legal-section-title">
                        <svg style="width: 20px; height: 20px; color: var(--ic-primary); flex-shrink: 0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        <span>2. Candidate Obligations &amp; Professional Conduct</span>
                    </h2>
                    <div class="legal-section-body">
                        <p>Candidates agree to:</p>
                        <ul>
                            <li>Provide accurate, truthful academic credentials, graduation dates, and work history. Fabricating university degrees or impersonating other individuals constitutes fraud and leads to immediate permanent account bans.</li>
                            <li>Attend scheduled interviews punctually or issue reschedule notices via the platform at least 24 hours in advance.</li>
                            <li>Maintain professional communication with recruiters and fellow platform members without harassment, spam, or inappropriate language.</li>
                        </ul>
                    </div>
                </section>

                <!-- Section 3 -->
                <section id="employer-rules" class="legal-section">
                    <h2 class="legal-section-title">
                        <svg style="width: 20px; height: 20px; color: var(--ic-primary); flex-shrink: 0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                        <span>3. Employer Obligations &amp; Fair Hiring Standards</span>
                    </h2>
                    <div class="legal-section-body">
                        <p>
                            InternConnect enforces strict quality safeguards for students. Employers agree that:
                        </p>
                        <ul>
                            <li><strong>Zero Student Fees:</strong> Employers must NEVER charge fees, training costs, application fees, or equipment deposit payments to candidates. Any posting requiring candidates to pay money will be immediately terminated with referral to law enforcement.</li>
                            <li><strong>Stipend Transparency:</strong> All roles must clearly disclose whether they are Paid (with explicit monthly stipend range in NGN) or Unpaid / Volunteering.</li>
                            <li><strong>Safe Work Environment:</strong> All physical and remote internships must comply with occupational health, safety, and non-discrimination employment laws.</li>
                        </ul>
                    </div>
                </section>

                <!-- Section 4 -->
                <section id="verification-policy" class="legal-section">
                    <h2 class="legal-section-title">
                        <svg style="width: 20px; height: 20px; color: var(--ic-primary); flex-shrink: 0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                        <span>4. Corporate Verification Policy</span>
                    </h2>
                    <div class="legal-section-body">
                        <p>
                            To maintain the highest tier of platform safety, all employers must complete organizational verification by providing a Corporate Affairs Commission (CAC) Registration / RC Number and verifiable company email domain. Unverified employers are restricted from publishing live roles or contacting candidates until our compliance team approves their credentials.
                        </p>
                    </div>
                </section>

                <!-- Section 5 -->
                <section id="payments-billing" class="legal-section">
                    <h2 class="legal-section-title">
                        <svg style="width: 20px; height: 20px; color: var(--ic-primary); flex-shrink: 0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                        </svg>
                        <span>5. Subscriptions, Payments &amp; Gateway Terms</span>
                    </h2>
                    <div class="legal-section-body">
                        <p>
                            Paid services, such as the candidate Pro and Premium plans and employer hiring plans, are billed in Nigerian Naira (NGN) and processed securely through Paystack. What each plan includes, and its daily or monthly limits, is shown on the <a href="<?= url('/pricing') ?>" class="text-primary font-semibold hover:underline">Pricing page</a>.
                        </p>
                        <ul>
                            <li><strong>No automatic renewal:</strong> a plan runs for the period you paid for (one month or one year). We remind you by e-mail before it ends; you choose whether to pay again.</li>
                            <li><strong>Plans earned through training partners:</strong> a partner course result can unlock Pro or Premium for a set time at no cost. These grants never replace or shorten a plan you paid for, and they end on the date shown in your account.</li>
                            <li><strong>Fair use of AI features:</strong> Jenny AI limits per day or month depend on your plan and protect the service for everyone.</li>
                            <li><strong>Price and plan changes:</strong> we give at least 30 days' notice before changing prices or reducing what a plan includes. Changes never affect a period you have already paid for.</li>
                            <li><strong>Refunds:</strong> All purchases are governed by our <a href="<?= url('/refund') ?>" class="text-primary font-semibold hover:underline">Refund Policy</a>.</li>
                        </ul>
                    </div>
                </section>

                <!-- Section 6 -->
                <section id="intellectual-prop" class="legal-section">
                    <h2 class="legal-section-title">
                        <svg style="width: 20px; height: 20px; color: var(--ic-primary); flex-shrink: 0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A13.916 13.916 0 008 11a4 4 0 118 0c0 1.017-.07 2.019-.203 3m-2.118 6.844A21.88 21.88 0 0015.171 17m3.839 1.132c.645-2.266.99-4.659.99-7.132A8 8 0 008 4.07M3 15.364c.64-1.319 1-2.8 1-4.364 0-1.457.39-2.823 1.07-4"/>
                        </svg>
                        <span>6. Intellectual Property &amp; Platform License</span>
                    </h2>
                    <div class="legal-section-body">
                        <p>
                            All trademarks, branding, UI designs, codebases, Jenny AI assets, and algorithms on InternConnect are the exclusive intellectual property of InternConnect Global Limited. Users retain ownership of their uploaded resumes and job descriptions, granting us a limited, worldwide license solely to operate and match internship placements.
                        </p>
                    </div>
                </section>

                <!-- Section 7 -->
                <section id="suspension-rules" class="legal-section">
                    <h2 class="legal-section-title">
                        <svg style="width: 20px; height: 20px; color: var(--ic-primary); flex-shrink: 0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                        </svg>
                        <span>7. Account Suspension &amp; Permanent Termination</span>
                    </h2>
                    <div class="legal-section-body">
                        <p>
                            We reserve the right to suspend or permanently terminate any candidate or employer account without notice for:
                        </p>
                        <ul>
                            <li>Submitting fraudulent CAC documents or posting deceptive opportunities.</li>
                            <li>Attempting to circumvent platform security, scrape data, or launch DDoS attacks.</li>
                            <li>Soliciting off-platform fees or engaging in discriminatory hiring practices.</li>
                        </ul>
                    </div>
                </section>

                <!-- Section 8 -->
                <section id="liability-law" class="legal-section" style="margin-bottom: 0;">
                    <h2 class="legal-section-title">
                        <svg style="width: 20px; height: 20px; color: var(--ic-primary); flex-shrink: 0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/>
                        </svg>
                        <span>8. Limitation of Liability &amp; Governing Law</span>
                    </h2>
                    <div class="legal-section-body">
                        <p>
                            InternConnect acts as an introductory technology platform and does not guarantee that a candidate will receive an offer, nor do we guarantee an employer will find a suitable intern. Jenny AI suggestions (CV feedback, cover letters, match scores, interview tips) are aids, may contain mistakes, and should be checked before you rely on them. Training partners are independent organisations responsible for their own courses and certificates. These terms are governed exclusively by the laws of the Federal Republic of Nigeria, and any disputes shall be resolved in arbitration in Lagos State, Nigeria.
                        </p>
                        <div class="legal-highlight-box" style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 16px;">
                            <div>
                                <div style="font-weight: 700; color: #000623; font-size: 14.5px;">Have questions about these terms?</div>
                                <div style="font-size: 13px; color: #64748B;">Our legal operations team is available Monday through Friday.</div>
                                <div style="font-size: 13px; color: #4F48E6; font-weight: 600; margin-top: 4px; overflow-wrap: anywhere;"><?= htmlspecialchars(\App\Core\Env::get('MAIL_ADMIN_ADDRESS', 'internconnect@weburea.com'), ENT_QUOTES) ?></div>
                            </div>
                            <a href="<?= url('/contact') ?>" class="ic-btn ic-btn-primary">
                                <span>Contact Legal Team</span>
                                <svg style="width: 14px; height: 14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </section>

            </article>

        </div>
    </div>

</section>
