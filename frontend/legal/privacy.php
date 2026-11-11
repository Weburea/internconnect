<?php

declare(strict_types=1);

/**
 * View: Privacy Policy
 * Route: /privacy
 * Layout: app
 */
?>
<section class="ic-page-shell" x-data="{ activeSection: 'collection' }">

    <!-- Page Hero Banner -->
    <header class="ic-page-hero">
        <div class="ic-container">
            <span class="ic-badge">
                <svg style="width: 14px; height: 14px; flex-shrink: 0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
                Data Protection &amp; Confidentiality
            </span>
            <h1 class="ic-page-hero-title">Privacy Policy</h1>
            <p class="ic-page-hero-subtitle">
                Your personal and academic data is sacred. Discover how InternConnect safeguards student resumes, corporate CAC documents, and recruiter interactions under international data privacy standards.
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
                        <li><a href="#principles" class="legal-toc-link" :class="{ 'is-active': activeSection === 'principles' }" @click="activeSection = 'principles'">1. Core Principles</a></li>
                        <li><a href="#collection" class="legal-toc-link" :class="{ 'is-active': activeSection === 'collection' }" @click="activeSection = 'collection'">2. Data We Collect</a></li>
                        <li><a href="#usage" class="legal-toc-link" :class="{ 'is-active': activeSection === 'usage' }" @click="activeSection = 'usage'">3. How We Use Data</a></li>
                        <li><a href="#ai-protection" class="legal-toc-link" :class="{ 'is-active': activeSection === 'ai-protection' }" @click="activeSection = 'ai-protection'">4. AI &amp; DeepSeek Safety</a></li>
                        <li><a href="#disclosure" class="legal-toc-link" :class="{ 'is-active': activeSection === 'disclosure' }" @click="activeSection = 'disclosure'">5. Information Sharing</a></li>
                        <li><a href="#security" class="legal-toc-link" :class="{ 'is-active': activeSection === 'security' }" @click="activeSection = 'security'">6. Storage &amp; Encryption</a></li>
                        <li><a href="#user-rights" class="legal-toc-link" :class="{ 'is-active': activeSection === 'user-rights' }" @click="activeSection = 'user-rights'">7. Your Legal Rights</a></li>
                        <li><a href="#contact" class="legal-toc-link" :class="{ 'is-active': activeSection === 'contact' }" @click="activeSection = 'contact'">8. Privacy Officer</a></li>
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
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                        <span>Compliance: <strong>NDPR &amp; GDPR Certified</strong></span>
                    </div>
                    <div class="legal-meta-item">
                        <span class="ic-badge ic-badge-success">Encrypted SSL/TLS</span>
                    </div>
                </div>

                <!-- Section 1: Core Principles -->
                <section id="principles" class="legal-section">
                    <h2 class="legal-section-title">
                        <svg style="width: 20px; height: 20px; color: var(--ic-primary); flex-shrink: 0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                        <span>1. Core Privacy Principles</span>
                    </h2>
                    <div class="legal-section-body">
                        <p>
                            InternConnect was founded on trust. Unlike standard social networks or job boards that monetize member profiles, we operate on four immutable privacy principles:
                        </p>
                        <div class="legal-highlight-box" style="display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 16px;">
                            <div>
                                <div style="font-weight: 700; color: #000623; font-size: 14px;">We Never Sell Your Data</div>
                                <div style="font-size: 13px; color: #64748B;">Your resume, phone number, and academic history are never shared with advertising networks or third-party data brokers.</div>
                            </div>
                            <div>
                                <div style="font-weight: 700; color: #000623; font-size: 14px;">Zero AI Model Training</div>
                                <div style="font-size: 13px; color: #64748B;">Candidate resumes, transcripts, and screening responses are never used to train public Large Language Models.</div>
                            </div>
                            <div>
                                <div style="font-weight: 700; color: #000623; font-size: 14px;">Explicit Disclosure</div>
                                <div style="font-size: 13px; color: #64748B;">Your profile is only exposed to registered, verified organizations when you explicitly click "Apply" on an internship.</div>
                            </div>
                            <div>
                                <div style="font-weight: 700; color: #000623; font-size: 14px;">Total Right to Deletion</div>
                                <div style="font-size: 13px; color: #64748B;">You maintain the absolute right to purge your account, uploaded documents, and application history at any time.</div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Section 2: Data We Collect -->
                <section id="collection" class="legal-section">
                    <h2 class="legal-section-title">
                        <svg style="width: 20px; height: 20px; color: var(--ic-primary); flex-shrink: 0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                        <span>2. Information We Collect</span>
                    </h2>
                    <div class="legal-section-body">
                        <p>
                            We collect only the minimum required information to provide verified career matching:
                        </p>
                        <ul>
                            <li><strong>Candidate Information:</strong> Legal name, email, phone number, tertiary institution, degree program, graduation year, technical/soft skills, portfolio links (GitHub, LinkedIn, Behance), and uploaded PDF/DOCX resumes.</li>
                            <li><strong>Employer Information:</strong> Corporate registered entity name, Corporate Affairs Commission (CAC) / RC number, certificate of incorporation, official company email, website, and representative contact details.</li>
                            <li><strong>Transaction Data:</strong> Payment verification status, transaction reference numbers, and subscription tier identifiers. Full credit/debit card numbers are handled directly by Paystack PCI-DSS certified vaults and never stored on InternConnect servers.</li>
                            <li><strong>Technical Metadata:</strong> IP address, device type, browser user agent, and timestamp logs for account security and anti-fraud monitoring.</li>
                        </ul>
                    </div>
                </section>

                <!-- Section 3: How We Use Data -->
                <section id="usage" class="legal-section">
                    <h2 class="legal-section-title">
                        <svg style="width: 20px; height: 20px; color: var(--ic-primary); flex-shrink: 0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                        <span>3. How We Use Your Information</span>
                    </h2>
                    <div class="legal-section-body">
                        <p>Your data is used strictly for the following operational workflows:</p>
                        <ul>
                            <li>Facilitating direct internship applications between verified students and hiring teams.</li>
                            <li>Powering the AI Candidate Match score (0–100%) so recruiters can discover relevant applicants faster.</li>
                            <li>Sending critical transaction emails: application status changes, interview invitations, and password reset codes.</li>
                            <li>Validating employer legitimacy and blocking fraudulent job scams or fee-charging recruiters.</li>
                        </ul>
                    </div>
                </section>

                <!-- Section 4: AI & DeepSeek Safety -->
                <section id="ai-protection" class="legal-section">
                    <h2 class="legal-section-title">
                        <svg style="width: 20px; height: 20px; color: var(--ic-primary); flex-shrink: 0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        <span>4. AI Data Processing Standards (DeepSeek Protocol)</span>
                    </h2>
                    <div class="legal-section-body">
                        <p>
                            In compliance with <code>docs/09-ai-system.md</code>, our artificial intelligence features (Resume Optimizer, Candidate Matching, Jenny AI Assistant) operate under strict isolation rules:
                        </p>
                        <ul>
                            <li><strong>No Autonomous Modification:</strong> AI recommendations are purely assistive. The AI cannot edit your profile, submit an application, or make hiring decisions without your explicit manual approval.</li>
                            <li><strong>Zero Retention in Foundation Models:</strong> Inquiries sent to DeepSeek API are processed statelessly in ephemeral memory with zero-day retention. Your resume text is never retained in training corpora.</li>
                            <li><strong>Audit Logging:</strong> Every AI request logs only metadata (user ID, token count, feature name, timestamp) for rate-limiting and billing audits.</li>
                        </ul>
                    </div>
                </section>

                <!-- Section 5: Disclosure -->
                <section id="disclosure" class="legal-section">
                    <h2 class="legal-section-title">
                        <svg style="width: 20px; height: 20px; color: var(--ic-primary); flex-shrink: 0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.684 10.742l4.622-2.312m0 7.14l-4.622-2.312M19 12a3 3 0 11-6 0 3 3 0 016 0zm-6-7a3 3 0 11-6 0 3 3 0 016 0zm0 14a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span>5. Information Sharing &amp; Recruiter Visibility</span>
                    </h2>
                    <div class="legal-section-body">
                        <p>
                            When you browse internships, your profile remains completely private. When you submit an application:
                        </p>
                        <ul>
                            <li>The hiring manager at that specific organization receives access to your profile, contact details, resume, and answers to screening questions.</li>
                            <li>Employers cannot view what other roles you have applied for, nor can other candidates see your profile.</li>
                            <li>We comply with valid court orders and law enforcement requests strictly as required under Nigerian and applicable international laws.</li>
                        </ul>
                    </div>
                </section>

                <!-- Section 6: Security -->
                <section id="security" class="legal-section">
                    <h2 class="legal-section-title">
                        <svg style="width: 20px; height: 20px; color: var(--ic-primary); flex-shrink: 0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        <span>6. Storage, Encryption &amp; Security Standards</span>
                    </h2>
                    <div class="legal-section-body">
                        <p>
                            We apply enterprise-grade defenses across our infrastructure:
                        </p>
                        <ul>
                            <li><strong>Encryption in Transit:</strong> 256-bit TLS/SSL encryption for all data exchanged between your browser and our servers.</li>
                            <li><strong>Encryption at Rest:</strong> Passwords hashed with standard Argon2id/Bcrypt algorithms; sensitive documents encrypted in dedicated Cloudinary asset storage.</li>
                            <li><strong>Session Isolation:</strong> Automatic session timeout, device fingerprinting alerts, and CSRF protection on all forms.</li>
                        </ul>
                    </div>
                </section>

                <!-- Section 7: User Rights -->
                <section id="user-rights" class="legal-section">
                    <h2 class="legal-section-title">
                        <svg style="width: 20px; height: 20px; color: var(--ic-primary); flex-shrink: 0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        <span>7. Your Legal Rights (NDPR &amp; GDPR)</span>
                    </h2>
                    <div class="legal-section-body">
                        <p>Under the Nigeria Data Protection Act (NDPA) and GDPR, you have the right to:</p>
                        <ul>
                            <li><strong>Access:</strong> Request a full copy of all personal records we hold about you.</li>
                            <li><strong>Rectification:</strong> Correct any inaccurate academic, skill, or corporate information directly via Profile Settings.</li>
                            <li><strong>Erasure (Right to be Forgotten):</strong> Request the permanent deletion of your account and all associated applications.</li>
                            <li><strong>Withdrawal of Consent:</strong> Unsubscribe from marketing notifications and revoke non-essential cookie consent at any time.</li>
                        </ul>
                    </div>
                </section>

                <!-- Section 8: Contact -->
                <section id="contact" class="legal-section" style="margin-bottom: 0;">
                    <h2 class="legal-section-title">
                        <svg style="width: 20px; height: 20px; color: var(--ic-primary); flex-shrink: 0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        <span>8. Contact Our Privacy Team</span>
                    </h2>
                    <div class="legal-section-body">
                        <p>
                            To submit a data access request or contact our Data Protection Officer:
                        </p>
                        <div class="legal-highlight-box" style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 16px;">
                            <div>
                                <div style="font-weight: 700; color: #000623; font-size: 14.5px;">InternConnect Global Privacy Office</div>
                                <div style="font-size: 13px; color: #64748B;">Victoria Island, Lagos, Nigeria &bull; NDPR Compliance Reference: IC-DPO-2026</div>
                                <div style="font-size: 13px; color: #4F48E6; font-weight: 600; margin-top: 4px;">privacy@internconnect.ng</div>
                            </div>
                            <div style="display: flex; gap: 10px;">
                                <a href="<?= url('/terms') ?>" class="ic-btn ic-btn-secondary">
                                    <span>Terms of Service</span>
                                </a>
                                <a href="<?= url('/cookies') ?>" class="ic-btn ic-btn-primary">
                                    <span>Cookie Policy</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </section>

            </article>

        </div>
    </div>

</section>
