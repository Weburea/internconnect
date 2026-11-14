<?php

declare(strict_types=1);

/**
 * View: Privacy Policy
 * Route: /privacy
 * Layout: app
 * Content reviewed 2026-11-12 against the running platform (docs/54-email-newsletter-contact.md section "Legal").
 * Have a Nigerian data-protection lawyer review before launch.
 */
$contactEmail = \App\Core\Env::get('MAIL_ADMIN_ADDRESS', 'internconnect@weburea.com');
$toc = [
    'principles'  => '1. Core Principles',
    'collection'  => '2. Data We Collect',
    'usage'       => '3. How We Use Data',
    'ai'          => '4. Jenny AI (Google Gemini)',
    'disclosure'  => '5. Sharing &amp; Providers',
    'partners'    => '6. Training Partners',
    'email'       => '7. E-mail &amp; Newsletter',
    'security'    => '8. Security &amp; Retention',
    'user-rights' => '9. Your Rights',
    'contact'     => '10. Contact Us',
];
$icon = static fn (string $d): string => '<svg style="width: 20px; height: 20px; color: var(--ic-primary); flex-shrink: 0;" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="' . $d . '"/></svg>';
$shield = 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z';
?>
<section class="ic-page-shell" x-data="{ activeSection: 'principles' }">

    <!-- Page Hero Banner -->
    <header class="ic-page-hero">
        <div class="ic-container">
            <span class="ic-badge">
                <svg style="width: 14px; height: 14px; flex-shrink: 0;" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
                Data Protection &amp; Confidentiality
            </span>
            <h1 class="ic-page-hero-title">Privacy Policy</h1>
            <p class="ic-page-hero-subtitle">
                What we collect, why, who helps us run InternConnect, and the choices you have. Written in plain language under the Nigeria Data Protection Act 2023.
            </p>
        </div>
    </header>

    <!-- Main Content Grid -->
    <div class="ic-container">
        <div class="legal-grid-wrap">

            <!-- Sticky Navigation Column (TOC) -->
            <aside class="legal-toc-card" aria-label="Table of contents">
                <div class="legal-toc-title">
                    <svg style="width: 14px; height: 14px; color: var(--ic-primary); flex-shrink: 0;" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h7"/>
                    </svg>
                    <span>Contents</span>
                </div>
                <nav>
                    <ul class="legal-toc-list">
                        <?php foreach ($toc as $id => $label): ?>
                            <li><a href="#<?= $id ?>" class="legal-toc-link" :class="{ 'is-active': activeSection === '<?= $id ?>' }" @click="activeSection = '<?= $id ?>'"><?= $label ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                </nav>
            </aside>

            <!-- Main Legal Article -->
            <article class="legal-content-card">

                <!-- Metadata Strip -->
                <div class="legal-meta-strip">
                    <div class="legal-meta-item">
                        <svg style="width: 15px; height: 15px; color: var(--ic-text-subtle); flex-shrink: 0;" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span>Last Updated: <strong>November 12, 2026</strong></span>
                    </div>
                    <div class="legal-meta-item">
                        <svg style="width: 15px; height: 15px; color: var(--ic-text-subtle); flex-shrink: 0;" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="<?= $shield ?>"/>
                        </svg>
                        <span>Framework: <strong>NDPA 2023 &amp; GDPR principles</strong></span>
                    </div>
                    <div class="legal-meta-item">
                        <span class="ic-badge ic-badge-success">Encrypted SSL/TLS</span>
                    </div>
                </div>

                <!-- 1 -->
                <section id="principles" class="legal-section">
                    <h2 class="legal-section-title"><?= $icon($shield) ?><span>1. Core Privacy Principles</span></h2>
                    <div class="legal-section-body">
                        <p>InternConnect connects students and graduates with verified employers. We run on four principles:</p>
                        <div class="legal-highlight-box" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(210px, 1fr)); gap: 16px;">
                            <div>
                                <div style="font-weight: 700; color: #000623; font-size: 14px;">We never sell your data</div>
                                <div style="font-size: 13px; color: #64748B;">Your CV, phone number and academic history are never sold or given to advertisers or data brokers.</div>
                            </div>
                            <div>
                                <div style="font-weight: 700; color: #000623; font-size: 14px;">No AI training on your CV</div>
                                <div style="font-size: 13px; color: #64748B;">We do not use your documents to train AI models, and we do not allow our AI provider to do so.</div>
                            </div>
                            <div>
                                <div style="font-weight: 700; color: #000623; font-size: 14px;">You decide who sees you</div>
                                <div style="font-size: 13px; color: #64748B;">An employer receives your application only when you click Apply for their internship.</div>
                            </div>
                            <div>
                                <div style="font-weight: 700; color: #000623; font-size: 14px;">You can leave at any time</div>
                                <div style="font-size: 13px; color: #64748B;">Ask us to delete your account, documents and applications, and we will.</div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- 2 -->
                <section id="collection" class="legal-section">
                    <h2 class="legal-section-title"><?= $icon('M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10') ?><span>2. Information We Collect</span></h2>
                    <div class="legal-section-body">
                        <p>We collect what we need to run the service, and nothing more:</p>
                        <ul>
                            <li><strong>Candidates:</strong> name, e-mail, phone, university, degree, graduation year, skills, work preferences, portfolio links, CV and cover letters (PDF or Word), applications and answers to screening questions.</li>
                            <li><strong>Employers:</strong> company name, CAC/RC number, incorporation documents, company e-mail and website, and the details of the people who hire.</li>
                            <li><strong>Payments:</strong> plan, amount, Paystack reference, status, card brand and last four digits. Full card numbers go straight to Paystack and never reach our servers.</li>
                            <li><strong>Video interviews:</strong> who joined, when, for how long, interview notes and scorecards. Audio and video are streamed live through Daily and are not recorded by InternConnect.</li>
                            <li><strong>Training results:</strong> if you take a course with a training partner through InternConnect, the result they send us (see section 6).</li>
                            <li><strong>Device and usage data:</strong> IP address, approximate city and country worked out from the IP address, browser type, pages visited and security logs.</li>
                            <li><strong>Messages:</strong> what you send through the Contact page, chats with Jenny, and messages with employers.</li>
                        </ul>
                    </div>
                </section>

                <!-- 3 -->
                <section id="usage" class="legal-section">
                    <h2 class="legal-section-title"><?= $icon('M13 10V3L4 14h7v7l9-11h-7z') ?><span>3. How We Use Your Information</span></h2>
                    <div class="legal-section-body">
                        <ul>
                            <li><strong>To provide the service</strong> (contract): applications, interviews, messaging, your plan and its limits, receipts.</li>
                            <li><strong>To recommend internships</strong> (legitimate interest): matching your profile, skills and location to listings.</li>
                            <li><strong>To keep the platform safe</strong> (legitimate interest and legal duty): verifying employers, blocking scams and fee-charging recruiters, rate limiting and fraud checks.</li>
                            <li><strong>To send e-mails you expect</strong>: account, application and payment e-mails; the newsletter only with your consent.</li>
                            <li><strong>To improve InternConnect</strong>: anonymous, aggregated statistics such as visits per city or the most searched skills.</li>
                        </ul>
                    </div>
                </section>

                <!-- 4 -->
                <section id="ai" class="legal-section">
                    <h2 class="legal-section-title"><?= $icon('M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z') ?><span>4. Jenny AI and Google Gemini</span></h2>
                    <div class="legal-section-body">
                        <p>Jenny, our AI assistant, uses the Google Gemini API to read CVs, write cover letters, prepare interview questions, summarise applications and answer chat questions.</p>
                        <ul>
                            <li><strong>Only what is needed:</strong> we send the text or document a feature needs, for example your CV when you ask Jenny to fill in an application.</li>
                            <li><strong>Assistive, never automatic:</strong> Jenny suggests; you decide. AI never submits an application, changes your profile or makes a hiring decision on its own.</li>
                            <li><strong>Google as our processor:</strong> Google processes these requests for us under the Gemini API terms and may keep them for a limited time to detect abuse. We do not permit your content to be used to train Google's models.</li>
                            <li><strong>Fairness:</strong> Jenny is instructed never to guess gender, age, religion or ethnicity, and employers see AI summaries labelled as AI.</li>
                            <li><strong>Usage records:</strong> we log the feature used and the time, to apply your plan's daily limits.</li>
                        </ul>
                    </div>
                </section>

                <!-- 5 -->
                <section id="disclosure" class="legal-section">
                    <h2 class="legal-section-title"><?= $icon('M8.684 10.742l4.622-2.312m0 7.14l-4.622-2.312M19 12a3 3 0 11-6 0 3 3 0 016 0zm-6-7a3 3 0 11-6 0 3 3 0 016 0zm0 14a3 3 0 11-6 0 3 3 0 016 0z') ?><span>5. Who Sees Your Data, and Our Service Providers</span></h2>
                    <div class="legal-section-body">
                        <ul>
                            <li><strong>Employers you apply to</strong> see your profile, CV, cover letter, screening answers and contact details. They cannot see your other applications.</li>
                            <li><strong>Premium visibility:</strong> on Pro and Premium plans, verified employers searching for candidates may see your profile summary. Free profiles are shown only to employers you apply to.</li>
                            <li><strong>The law:</strong> we answer valid court orders and lawful requests from Nigerian authorities, and only for what they require.</li>
                        </ul>
                        <p>We use these providers to run InternConnect. Each gets only what its job needs:</p>
                        <div class="legal-highlight-box" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(210px, 1fr)); gap: 14px;">
                            <?php foreach ([
                                ['Paystack', 'Card, bank and transfer payments for Pro and Premium.'],
                                ['Google (Gemini API)', 'Jenny AI features described in section 4.'],
                                ['Daily.co', 'Live video for interviews inside InternConnect.'],
                                ['MaxMind', 'Approximate city and country from IP addresses.'],
                                ['Cloudinary', 'Images on our website and in our e-mails.'],
                                ['Our hosting and mail provider', 'Servers in a secured data centre, and sending our e-mails.'],
                            ] as [$name, $what]): ?>
                                <div>
                                    <div style="font-weight: 700; color: #000623; font-size: 14px;"><?= $name ?></div>
                                    <div style="font-size: 13px; color: #64748B;"><?= $what ?></div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <p style="margin-top: 14px;">Some providers process data outside Nigeria. When they do, we rely on the safeguards allowed by the NDPA, such as contractual protections.</p>
                    </div>
                </section>

                <!-- 6 -->
                <section id="partners" class="legal-section">
                    <h2 class="legal-section-title"><?= $icon('M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z') ?><span>6. Training Partners</span></h2>
                    <div class="legal-section-body">
                        <p>When you choose a course from a training partner on our E-learning page, you sign up on the partner's own website, under the partner's privacy policy.</p>
                        <ul>
                            <li><strong>What we send:</strong> a private reference code that links the course to your InternConnect account. We do not send your CV.</li>
                            <li><strong>What the partner sends back:</strong> your score, completion date and certificate number, so we can show a "Certified by" badge to employers and unlock any plan you earned.</li>
                            <li><strong>Your choice:</strong> you can ask us to hide or delete a training result at any time.</li>
                        </ul>
                    </div>
                </section>

                <!-- 7 -->
                <section id="email" class="legal-section">
                    <h2 class="legal-section-title"><?= $icon('M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z') ?><span>7. E-mail, Newsletter and Location</span></h2>
                    <div class="legal-section-body">
                        <ul>
                            <li><strong>Account e-mails</strong> (applications, interviews, payments, security) are part of the service.</li>
                            <li><strong>The newsletter</strong> is sent only after you confirm your address, and every issue has an unsubscribe link that works in one click.</li>
                            <li><strong>Open tracking:</strong> our e-mails contain a small image that tells us whether an e-mail was opened, so we can send fewer, better e-mails. Blocking images in your mail app turns this off.</li>
                            <li><strong>Location:</strong> we estimate your city from your IP address to show nearby internships. Your exact location is used only if you press "Near me" and allow it in your browser, and it is not stored.</li>
                        </ul>
                    </div>
                </section>

                <!-- 8 -->
                <section id="security" class="legal-section">
                    <h2 class="legal-section-title"><?= $icon('M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z') ?><span>8. Security and How Long We Keep Data</span></h2>
                    <div class="legal-section-body">
                        <ul>
                            <li><strong>In transit:</strong> all traffic uses HTTPS (TLS).</li>
                            <li><strong>Documents:</strong> CVs and company documents are stored in private storage that is never publicly reachable; only you, the employers you apply to and authorised staff can open them.</li>
                            <li><strong>Accounts:</strong> passwords are hashed, sessions are protected against hijacking and forged requests, and sign-in attempts are rate limited.</li>
                            <li><strong>Retention:</strong> your account data stays while your account is open. Applications are kept for 2 years after the internship closes. Payment records are kept for 6 years for tax law. Security logs are kept for 12 months. Visit records (approximate city, device, pages) are kept for 90 days. Contact messages are kept for 2 years.</li>
                            <li><strong>Breaches:</strong> if a breach puts your rights at risk, we will tell you and the Nigeria Data Protection Commission as the law requires.</li>
                        </ul>
                    </div>
                </section>

                <!-- 9 -->
                <section id="user-rights" class="legal-section">
                    <h2 class="legal-section-title"><?= $icon('M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z') ?><span>9. Your Rights</span></h2>
                    <div class="legal-section-body">
                        <p>Under the Nigeria Data Protection Act 2023 (and the GDPR where it applies) you can:</p>
                        <ul>
                            <li><strong>Access</strong> a copy of the personal data we hold about you.</li>
                            <li><strong>Correct</strong> it, most of it directly in your profile settings.</li>
                            <li><strong>Delete</strong> your account, documents and applications.</li>
                            <li><strong>Object or withdraw consent</strong>, for example to the newsletter or optional cookies, at any time.</li>
                            <li><strong>Take your data with you</strong> in a common format.</li>
                            <li><strong>Complain</strong> to the Nigeria Data Protection Commission (ndpc.gov.ng).</li>
                        </ul>
                        <p>We answer requests within 30 days. We may ask you to confirm your identity first.</p>
                    </div>
                </section>

                <!-- 10 -->
                <section id="contact" class="legal-section" style="margin-bottom: 0;">
                    <h2 class="legal-section-title"><?= $icon('M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z') ?><span>10. Contact Us About Privacy</span></h2>
                    <div class="legal-section-body">
                        <p>For a data request or any privacy question, write to us through the Contact page or by e-mail. Start the subject with "Privacy".</p>
                        <div class="legal-highlight-box" style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 16px;">
                            <div style="min-width: 0;">
                                <div style="font-weight: 700; color: #000623; font-size: 14.5px;">InternConnect Privacy Team</div>
                                <div style="font-size: 13px; color: #64748B;">Victoria Island, Lagos, Nigeria</div>
                                <div style="font-size: 13px; color: #4F48E6; font-weight: 600; margin-top: 4px; overflow-wrap: anywhere;"><?= htmlspecialchars($contactEmail, ENT_QUOTES) ?></div>
                            </div>
                            <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                                <a href="<?= url('/contact') ?>" class="ic-btn ic-btn-secondary"><span>Contact page</span></a>
                                <a href="<?= url('/cookies') ?>" class="ic-btn ic-btn-primary"><span>Cookie Policy</span></a>
                            </div>
                        </div>
                    </div>
                </section>

            </article>

        </div>
    </div>

</section>
