<?php

declare(strict_types=1);

/**
 * View: Cookie Policy
 * Route: /cookies
 * Layout: app (renders navbar, main content, and footer)
 */
?>
<section class="ic-page-shell" x-data="{
    activeSection: 'overview',
    cookieConsent: {
        essential: true,
        functional: true,
        analytics: false
    },
    savedToast: false,
    savePreferences() {
        this.savedToast = true;
        setTimeout(() => { this.savedToast = false; }, 3500);
    }
}">

    <!-- Page Hero Banner -->
    <header class="ic-page-hero">
        <div class="ic-container">
            <span class="ic-badge">
                <svg style="width: 14px; height: 14px; flex-shrink: 0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
                Legal &amp; Compliance
            </span>
            <h1 class="ic-page-hero-title">Cookie Policy</h1>
            <p class="ic-page-hero-subtitle">
                Learn how InternConnect uses essential cookies, local tokens, and secure session identifiers to power seamless internship discovery, recruiter communication, and data protection.
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
                    <span>On This Page</span>
                </div>
                <nav>
                    <ul class="legal-toc-list">
                        <li><a href="#overview" class="legal-toc-link" :class="{ 'is-active': activeSection === 'overview' }" @click="activeSection = 'overview'">1. Overview</a></li>
                        <li><a href="#what-are-cookies" class="legal-toc-link" :class="{ 'is-active': activeSection === 'what-are-cookies' }" @click="activeSection = 'what-are-cookies'">2. What Are Cookies?</a></li>
                        <li><a href="#cookies-we-use" class="legal-toc-link" :class="{ 'is-active': activeSection === 'cookies-we-use' }" @click="activeSection = 'cookies-we-use'">3. Categories of Cookies</a></li>
                        <li><a href="#cookie-table" class="legal-toc-link" :class="{ 'is-active': activeSection === 'cookie-table' }" @click="activeSection = 'cookie-table'">4. Specific Cookies Table</a></li>
                        <li><a href="#third-party-services" class="legal-toc-link" :class="{ 'is-active': activeSection === 'third-party-services' }" @click="activeSection = 'third-party-services'">5. Third-Party Services</a></li>
                        <li><a href="#manage-preferences" class="legal-toc-link" :class="{ 'is-active': activeSection === 'manage-preferences' }" @click="activeSection = 'manage-preferences'">6. Manage Preferences</a></li>
                        <li><a href="#browser-controls" class="legal-toc-link" :class="{ 'is-active': activeSection === 'browser-controls' }" @click="activeSection = 'browser-controls'">7. Browser Instructions</a></li>
                        <li><a href="#contact-dpo" class="legal-toc-link" :class="{ 'is-active': activeSection === 'contact-dpo' }" @click="activeSection = 'contact-dpo'">8. Contact &amp; Queries</a></li>
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
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>Estimated Reading Time: <strong>4 mins</strong></span>
                    </div>
                    <div class="legal-meta-item">
                        <span class="ic-badge ic-badge-success">Version 2.4 Active</span>
                    </div>
                </div>

                <!-- Section 1: Overview -->
                <section id="overview" class="legal-section">
                    <h2 class="legal-section-title">
                        <svg style="width: 20px; height: 20px; color: var(--ic-primary); flex-shrink: 0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>1. Overview</span>
                    </h2>
                    <div class="legal-section-body">
                        <p>
                            InternConnect ("we", "us", or "our") uses cookies, local storage tokens, and related web technologies across our platform (including <code>internconnect.ng</code> and subdomains) to ensure system reliability, prevent account hijacking, maintain candidate and recruiter sessions, and remember your personalized preferences.
                        </p>
                        <p>
                            This Cookie Policy explains what these technologies are, why we use them, and the specific choices available to you to govern their operation. This policy works in tandem with our <a href="<?= url('/privacy') ?>" class="text-primary font-semibold hover:underline">Privacy Policy</a> and <a href="<?= url('/terms') ?>" class="text-primary font-semibold hover:underline">Terms of Service</a>.
                        </p>
                    </div>
                </section>

                <!-- Section 2: What Are Cookies? -->
                <section id="what-are-cookies" class="legal-section">
                    <h2 class="legal-section-title">
                        <svg style="width: 20px; height: 20px; color: var(--ic-primary); flex-shrink: 0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                        </svg>
                        <span>2. What Are Cookies &amp; Tracking Technologies?</span>
                    </h2>
                    <div class="legal-section-body">
                        <p>
                            A cookie is a small alphanumeric text file sent by a web server and stored on your computer, smartphone, or tablet when you visit our website. Cookies enable the platform to recognize your browser, persist your authenticated session when navigating between pages, and retain your selected filters (such as target internship cities or salary ranges).
                        </p>
                        <p>
                            We also utilize <strong>HTML5 Local Storage</strong> and <strong>Session Storage</strong>. These browser-based data stores hold temporary onboarding draft progress (for example, Steps 1 through 7 of candidate profile setup) so that accidentally refreshing or closing your tab does not cause loss of work.
                        </p>
                    </div>
                </section>

                <!-- Section 3: Categories of Cookies -->
                <section id="cookies-we-use" class="legal-section">
                    <h2 class="legal-section-title">
                        <svg style="width: 20px; height: 20px; color: var(--ic-primary); flex-shrink: 0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                        </svg>
                        <span>3. Categories of Cookies We Use</span>
                    </h2>
                    <div class="legal-section-body">
                        <ul>
                            <li>
                                <strong>Strictly Necessary Cookies:</strong> Critical for the operation of InternConnect. They include authentication tokens that keep candidates logged into their dashboard, CSRF security tokens that prevent forged form submissions, and load-balancer route tokens. These cookies cannot be turned off.
                            </li>
                            <li>
                                <strong>Functional &amp; Preference Cookies:</strong> Used to recognize you when you return to our platform, remember your active profile role (Student Applicant vs Employer Portal), and maintain Jenny AI state (such as greeting status and unread tips).
                            </li>
                            <li>
                                <strong>Analytics &amp; Performance Cookies:</strong> Help us anonymously monitor which internship categories receive the highest search traffic, track button response times, and diagnose broken links without profiling your individual identity.
                            </li>
                        </ul>
                    </div>
                </section>

                <!-- Section 4: Specific Cookies Table -->
                <section id="cookie-table" class="legal-section">
                    <h2 class="legal-section-title">
                        <svg style="width: 20px; height: 20px; color: var(--ic-primary); flex-shrink: 0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                        </svg>
                        <span>4. Specific Cookies Deployed on InternConnect</span>
                    </h2>
                    <div class="legal-section-body">
                        <div class="legal-table-responsive">
                            <table class="legal-table" aria-label="Cookies inventory table">
                                <thead>
                                    <tr>
                                        <th>Cookie Name</th>
                                        <th>Provider</th>
                                        <th>Category</th>
                                        <th>Purpose</th>
                                        <th>Lifespan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><code>PHPSESSID</code></td>
                                        <td>InternConnect</td>
                                        <td>Essential</td>
                                        <td>Stores encrypted session ID for candidate/recruiter authentication.</td>
                                        <td>Session (browser close)</td>
                                    </tr>
                                    <tr>
                                        <td><code>ic_csrf_token</code></td>
                                        <td>InternConnect</td>
                                        <td>Essential</td>
                                        <td>Guards form submissions against Cross-Site Request Forgery attacks.</td>
                                        <td>Session</td>
                                    </tr>
                                    <tr>
                                        <td><code>ic_remember_device</code></td>
                                        <td>InternConnect</td>
                                        <td>Functional</td>
                                        <td>Maintains multi-factor authenticated status for recognized hardware.</td>
                                        <td>30 Days</td>
                                    </tr>
                                    <tr>
                                        <td><code>jenny_ai_state</code></td>
                                        <td>InternConnect</td>
                                        <td>Functional</td>
                                        <td>Stores chat tray open/closed state and persona role selection.</td>
                                        <td>90 Days</td>
                                    </tr>
                                    <tr>
                                        <td><code>pstk_session</code></td>
                                        <td>Paystack Inc.</td>
                                        <td>Third-Party Essential</td>
                                        <td>Validates fraud risk and manages card payment processing during checkout.</td>
                                        <td>Session</td>
                                    </tr>
                                    <tr>
                                        <td><code>cld_asset_token</code></td>
                                        <td>Cloudinary Inc.</td>
                                        <td>Third-Party Performance</td>
                                        <td>Optimizes edge delivery of company logos and verification documents.</td>
                                        <td>7 Days</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </section>

                <!-- Section 5: Third-Party Services -->
                <section id="third-party-services" class="legal-section">
                    <h2 class="legal-section-title">
                        <svg style="width: 20px; height: 20px; color: var(--ic-primary); flex-shrink: 0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                        <span>5. Third-Party Integrations</span>
                    </h2>
                    <div class="legal-section-body">
                        <p>
                            We partner with specialized, security-certified third parties to deliver core features:
                        </p>
                        <ul>
                            <li><strong>Paystack Payment Gateway:</strong> When you purchase candidate Pro access or employer posting plans, Paystack drops fraud prevention cookies to authorize bank cards and tokenized transactions.</li>
                            <li><strong>Cloudinary Asset CDN:</strong> Media files, company badges, and documents are served via Cloudinary's encrypted global CDN for fast page loading.</li>
                            <li><strong>DeepSeek AI Processing:</strong> Jenny AI queries sent to DeepSeek run over encrypted server-to-server TLS connections and do not deploy persistent cookies on your client device.</li>
                        </ul>
                    </div>
                </section>

                <!-- Section 6: Manage Preferences (Interactive Box) -->
                <section id="manage-preferences" class="legal-section">
                    <h2 class="legal-section-title">
                        <svg style="width: 20px; height: 20px; color: var(--ic-primary); flex-shrink: 0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                        </svg>
                        <span>6. Your Cookie Preferences</span>
                    </h2>
                    <div class="legal-section-body">
                        <p>
                            You have total control over non-essential cookies. You can adjust your consent below at any time:
                        </p>

                        <div class="legal-highlight-box">
                            <!-- Essential -->
                            <div style="display: flex; align-items: center; justify-content: space-between; padding: 12px 0; border-bottom: 1px solid #E2E8F0;">
                                <div>
                                    <div style="font-weight: 700; color: #000623; font-size: 14px;">Strictly Essential Cookies</div>
                                    <div style="font-size: 12.5px; color: #64748B;">Authentication, CSRF defense, and database session stability.</div>
                                </div>
                                <span class="ic-badge ic-badge-neutral">Always Active</span>
                            </div>

                            <!-- Functional -->
                            <div style="display: flex; align-items: center; justify-content: space-between; padding: 14px 0; border-bottom: 1px solid #E2E8F0;">
                                <div>
                                    <div style="font-weight: 700; color: #000623; font-size: 14px;">Functional &amp; Persona Memory</div>
                                    <div style="font-size: 12.5px; color: #64748B;">Remembers your role, filters, and Jenny AI chat tray settings.</div>
                                </div>
                                <label style="position: relative; display: inline-block; width: 44px; height: 24px; cursor: pointer;">
                                    <input type="checkbox" x-model="cookieConsent.functional" style="opacity: 0; width: 0; height: 0;">
                                    <span style="position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; border-radius: 99px; transition: .2s;" :style="cookieConsent.functional ? 'background-color: #4F48E6;' : 'background-color: #CBD5E1;'"></span>
                                    <span style="position: absolute; height: 18px; width: 18px; left: 3px; bottom: 3px; background-color: white; border-radius: 50%; transition: .2s;" :style="cookieConsent.functional ? 'transform: translateX(20px);' : 'transform: translateX(0);'"></span>
                                </label>
                            </div>

                            <!-- Analytics -->
                            <div style="display: flex; align-items: center; justify-content: space-between; padding: 14px 0;">
                                <div>
                                    <div style="font-weight: 700; color: #000623; font-size: 14px;">Anonymous Analytics</div>
                                    <div style="font-size: 12.5px; color: #64748B;">Help our engineering team identify slow-loading pages and search gaps.</div>
                                </div>
                                <label style="position: relative; display: inline-block; width: 44px; height: 24px; cursor: pointer;">
                                    <input type="checkbox" x-model="cookieConsent.analytics" style="opacity: 0; width: 0; height: 0;">
                                    <span style="position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; border-radius: 99px; transition: .2s;" :style="cookieConsent.analytics ? 'background-color: #4F48E6;' : 'background-color: #CBD5E1;'"></span>
                                    <span style="position: absolute; height: 18px; width: 18px; left: 3px; bottom: 3px; background-color: white; border-radius: 50%; transition: .2s;" :style="cookieConsent.analytics ? 'transform: translateX(20px);' : 'transform: translateX(0);'"></span>
                                </label>
                            </div>

                            <div style="margin-top: 18px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px;">
                                <button type="button" @click="savePreferences()" class="ic-btn ic-btn-primary">
                                    <span>Save Cookie Preferences</span>
                                    <svg style="width: 14px; height: 14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </button>
                                <span x-show="savedToast" x-transition style="font-size: 13px; font-weight: 600; color: #059669; display: flex; align-items: center; gap: 6px;">
                                    <svg style="width: 16px; height: 16px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Preferences saved successfully
                                </span>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Section 7: Browser Instructions -->
                <section id="browser-controls" class="legal-section">
                    <h2 class="legal-section-title">
                        <svg style="width: 20px; height: 20px; color: var(--ic-primary); flex-shrink: 0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        <span>7. How to Clear or Disable Cookies in Your Browser</span>
                    </h2>
                    <div class="legal-section-body">
                        <p>
                            You can also manage or delete cookies via your browser's security settings. Note that disabling essential cookies will prevent logging in and submitting applications:
                        </p>
                        <ul>
                            <li><strong>Google Chrome:</strong> Settings &gt; Privacy and Security &gt; Third-party cookies &gt; Clear browsing data.</li>
                            <li><strong>Mozilla Firefox:</strong> Settings &gt; Privacy &amp; Security &gt; Cookies and Site Data &gt; Clear Data.</li>
                            <li><strong>Apple Safari:</strong> Preferences &gt; Privacy &gt; Manage Website Data &gt; Remove All.</li>
                            <li><strong>Microsoft Edge:</strong> Settings &gt; Cookies and Site Permissions &gt; Manage and delete cookies.</li>
                        </ul>
                    </div>
                </section>

                <!-- Section 8: Contact -->
                <section id="contact-dpo" class="legal-section" style="margin-bottom: 0;">
                    <h2 class="legal-section-title">
                        <svg style="width: 20px; height: 20px; color: var(--ic-primary); flex-shrink: 0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        <span>8. Contact Our Data Protection Team</span>
                    </h2>
                    <div class="legal-section-body">
                        <p>
                            For legal inquiries, data subject access requests, or questions regarding this Cookie Policy, contact our Data Protection Officer:
                        </p>
                        <div class="legal-highlight-box" style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 16px;">
                            <div>
                                <div style="font-weight: 700; color: #000623; font-size: 14.5px;">Data Protection &amp; Legal Compliance Office</div>
                                <div style="font-size: 13px; color: #64748B;">InternConnect Global Limited &bull; Victoria Island, Lagos, Nigeria</div>
                                <div style="font-size: 13px; color: #4F48E6; font-weight: 600; margin-top: 4px;">legal@internconnect.ng</div>
                            </div>
                            <a href="<?= url('/contact') ?>" class="ic-btn ic-btn-secondary">
                                <span>Contact Support Desk</span>
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
