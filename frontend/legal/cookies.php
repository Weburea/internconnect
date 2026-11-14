<?php

declare(strict_types=1);

/**
 * View: Cookie Policy
 * Route: /cookies
 * Layout: app
 * The table lists the cookies and browser storage the platform really sets (checked 2026-11-12).
 * Preferences are saved by public/assets/js/ic-consent.js in the `ic_consent` cookie.
 */
$contactEmail = \App\Core\Env::get('MAIL_ADMIN_ADDRESS', 'internconnect@weburea.com');
$toc = [
    'overview'           => '1. Overview',
    'what-are-cookies'   => '2. What Are Cookies?',
    'cookies-we-use'     => '3. Categories',
    'cookie-table'       => '4. Cookies We Set',
    'third-party'        => '5. Other Services',
    'manage-preferences' => '6. Your Preferences',
    'browser-controls'   => '7. Browser Controls',
    'contact-dpo'        => '8. Contact',
];
$icon = static fn (string $d): string => '<svg style="width: 20px; height: 20px; color: var(--ic-primary); flex-shrink: 0;" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="' . $d . '"/></svg>';
$rows = [
    ['PHPSESSID', 'InternConnect', 'Essential', 'Keeps you signed in and protects forms against forged requests (security token kept in the session).', 'Until you close the browser'],
    ['admin_trusted_device', 'InternConnect', 'Essential', 'Staff only: remembers a verified staff device after two-step sign-in.', '30 days'],
    ['ic_consent', 'InternConnect', 'Essential', 'Remembers the cookie choices you make on this page or in the banner.', '12 months'],
    ['ic_vid', 'InternConnect', 'Analytics', 'A random visitor number that recognises you when you come back, so we can count unique visitors. Only set if you allow analytics.', '12 months'],
    ['Browser storage: internconnect_onboarding_draft', 'InternConnect', 'Preferences', 'Saves your sign-up form as you type so you do not lose it. Cleared when you finish.', 'Until finished'],
    ['Browser storage: icPromoClosed_*', 'InternConnect', 'Preferences', 'Remembers that you closed the plan card in your dashboard menu.', 'Until cleared'],
];
?>
<section class="ic-page-shell" x-data="{
    activeSection: 'overview',
    cookieConsent: { essential: true, functional: true, analytics: false },
    savedToast: false,
    init() {
        const saved = window.icConsent ? window.icConsent.get() : null;
        if (saved) { this.cookieConsent.functional = !!saved.functional; this.cookieConsent.analytics = !!saved.analytics; }
    },
    savePreferences() {
        if (window.icConsent) { window.icConsent.set({ functional: this.cookieConsent.functional, analytics: this.cookieConsent.analytics }); }
        this.savedToast = true;
        setTimeout(() => { this.savedToast = false; }, 3500);
    }
}">

    <!-- Page Hero Banner -->
    <header class="ic-page-hero">
        <div class="ic-container">
            <span class="ic-badge">
                <svg style="width: 14px; height: 14px; flex-shrink: 0;" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
                Cookies &amp; Browser Storage
            </span>
            <h1 class="ic-page-hero-title">Cookie Policy</h1>
            <p class="ic-page-hero-subtitle">
                The few cookies InternConnect uses, what each one does, and how to change your choices at any time.
            </p>
        </div>
    </header>

    <div class="ic-container">
        <div class="legal-grid-wrap">

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

            <article class="legal-content-card">

                <div class="legal-meta-strip">
                    <div class="legal-meta-item">
                        <svg style="width: 15px; height: 15px; color: var(--ic-text-subtle); flex-shrink: 0;" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span>Last Updated: <strong>November 12, 2026</strong></span>
                    </div>
                    <div class="legal-meta-item">
                        <span class="ic-badge ic-badge-success">No advertising cookies</span>
                    </div>
                </div>

                <section id="overview" class="legal-section">
                    <h2 class="legal-section-title"><?= $icon('M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z') ?><span>1. Overview</span></h2>
                    <div class="legal-section-body">
                        <p>InternConnect uses a small number of cookies and browser storage to keep you signed in, keep your account safe and remember your choices. We do not use advertising or cross-site tracking cookies, and we never sell cookie data.</p>
                    </div>
                </section>

                <section id="what-are-cookies" class="legal-section">
                    <h2 class="legal-section-title"><?= $icon('M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z') ?><span>2. What Are Cookies and Browser Storage?</span></h2>
                    <div class="legal-section-body">
                        <p>A cookie is a small text file a website saves in your browser. Browser storage ("local storage") works the same way but stays on your device and is never sent to our servers on its own. Both let a site remember you between pages and visits.</p>
                    </div>
                </section>

                <section id="cookies-we-use" class="legal-section">
                    <h2 class="legal-section-title"><?= $icon('M4 6h16M4 10h16M4 14h16M4 18h16') ?><span>3. Categories We Use</span></h2>
                    <div class="legal-section-body">
                        <ul>
                            <li><strong>Essential:</strong> sign-in, security and your cookie choice. The site cannot work without them, so they are always on.</li>
                            <li><strong>Preferences:</strong> remember small things you chose, such as a closed card or a half-finished form.</li>
                            <li><strong>Analytics:</strong> a visitor number that recognises returning visitors, so we can count unique visitors. Off until you allow it. (Each visit's approximate city, worked out from the IP address, is recorded without a cookie for security and to show live activity; see the Privacy Policy.)</li>
                        </ul>
                    </div>
                </section>

                <section id="cookie-table" class="legal-section">
                    <h2 class="legal-section-title"><?= $icon('M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z') ?><span>4. Cookies and Storage We Set</span></h2>
                    <div class="legal-section-body">
                        <div class="no-scrollbar" style="width: 100%; overflow-x: auto;">
                            <table style="width: 100%; min-width: 640px; border-collapse: collapse; font-size: 13px;">
                                <thead>
                                    <tr style="text-align: left; color: #64748B; font-size: 11px; text-transform: uppercase; letter-spacing: .04em;">
                                        <th style="padding: 10px 8px; border-bottom: 1px solid #E2E8F0;">Name</th>
                                        <th style="padding: 10px 8px; border-bottom: 1px solid #E2E8F0;">Set by</th>
                                        <th style="padding: 10px 8px; border-bottom: 1px solid #E2E8F0;">Category</th>
                                        <th style="padding: 10px 8px; border-bottom: 1px solid #E2E8F0;">Purpose</th>
                                        <th style="padding: 10px 8px; border-bottom: 1px solid #E2E8F0;">Kept for</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($rows as [$name, $by, $cat, $purpose, $life]): ?>
                                        <tr>
                                            <td style="padding: 10px 8px; border-bottom: 1px solid #F1F5F9; vertical-align: top;"><code style="overflow-wrap: anywhere;"><?= htmlspecialchars($name, ENT_QUOTES) ?></code></td>
                                            <td style="padding: 10px 8px; border-bottom: 1px solid #F1F5F9; vertical-align: top;"><?= $by ?></td>
                                            <td style="padding: 10px 8px; border-bottom: 1px solid #F1F5F9; vertical-align: top;"><?= $cat ?></td>
                                            <td style="padding: 10px 8px; border-bottom: 1px solid #F1F5F9; vertical-align: top; color: #475569;"><?= $purpose ?></td>
                                            <td style="padding: 10px 8px; border-bottom: 1px solid #F1F5F9; vertical-align: top; white-space: nowrap;"><?= $life ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </section>

                <section id="third-party" class="legal-section">
                    <h2 class="legal-section-title"><?= $icon('M8.684 10.742l4.622-2.312m0 7.14l-4.622-2.312M19 12a3 3 0 11-6 0 3 3 0 016 0zm-6-7a3 3 0 11-6 0 3 3 0 016 0zm0 14a3 3 0 11-6 0 3 3 0 016 0z') ?><span>5. Other Services on Our Pages</span></h2>
                    <div class="legal-section-body">
                        <ul>
                            <li><strong>Paystack:</strong> when you pay, Paystack's checkout may set its own cookies to process the payment and prevent fraud (Paystack privacy policy).</li>
                            <li><strong>Daily.co:</strong> inside a video interview room, Daily may use storage needed for the call to work.</li>
                            <li><strong>Google Gemini:</strong> Jenny AI runs on our servers; Google sets no cookies in your browser.</li>
                            <li><strong>Cloudinary and Google Fonts:</strong> deliver images and fonts. They receive your IP address, as any web server does, and set no cookies on InternConnect.</li>
                        </ul>
                    </div>
                </section>

                <section id="manage-preferences" class="legal-section">
                    <h2 class="legal-section-title"><?= $icon('M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4') ?><span>6. Your Cookie Preferences</span></h2>
                    <div class="legal-section-body">
                        <p>Change your choices here at any time. They apply straight away on this device.</p>

                        <div class="legal-highlight-box">
                            <div style="display: flex; align-items: center; justify-content: space-between; gap: 12px; padding: 12px 0; border-bottom: 1px solid #E2E8F0;">
                                <div style="min-width: 0;">
                                    <div style="font-weight: 700; color: #000623; font-size: 14px;">Essential</div>
                                    <div style="font-size: 12.5px; color: #64748B;">Sign-in, security and your cookie choice.</div>
                                </div>
                                <span class="ic-badge ic-badge-neutral" style="flex-shrink: 0;">Always on</span>
                            </div>

                            <div style="display: flex; align-items: center; justify-content: space-between; gap: 12px; padding: 14px 0; border-bottom: 1px solid #E2E8F0;">
                                <div style="min-width: 0;">
                                    <div style="font-weight: 700; color: #000623; font-size: 14px;">Preferences</div>
                                    <div style="font-size: 12.5px; color: #64748B;">Remembers closed cards and unfinished forms.</div>
                                </div>
                                <label style="position: relative; display: inline-block; width: 44px; height: 24px; cursor: pointer; flex-shrink: 0;">
                                    <input type="checkbox" x-model="cookieConsent.functional" aria-label="Allow preference storage" style="opacity: 0; width: 0; height: 0;">
                                    <span style="position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; border-radius: 99px; transition: .2s;" :style="cookieConsent.functional ? 'background-color: #4F48E6;' : 'background-color: #CBD5E1;'"></span>
                                    <span style="position: absolute; height: 18px; width: 18px; left: 3px; bottom: 3px; background-color: white; border-radius: 50%; transition: .2s;" :style="cookieConsent.functional ? 'transform: translateX(20px);' : 'transform: translateX(0);'"></span>
                                </label>
                            </div>

                            <div style="display: flex; align-items: center; justify-content: space-between; gap: 12px; padding: 14px 0;">
                                <div style="min-width: 0;">
                                    <div style="font-weight: 700; color: #000623; font-size: 14px;">Analytics</div>
                                    <div style="font-size: 12.5px; color: #64748B;">Counts visits and the city they come from. No advertising.</div>
                                </div>
                                <label style="position: relative; display: inline-block; width: 44px; height: 24px; cursor: pointer; flex-shrink: 0;">
                                    <input type="checkbox" x-model="cookieConsent.analytics" aria-label="Allow analytics" style="opacity: 0; width: 0; height: 0;">
                                    <span style="position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; border-radius: 99px; transition: .2s;" :style="cookieConsent.analytics ? 'background-color: #4F48E6;' : 'background-color: #CBD5E1;'"></span>
                                    <span style="position: absolute; height: 18px; width: 18px; left: 3px; bottom: 3px; background-color: white; border-radius: 50%; transition: .2s;" :style="cookieConsent.analytics ? 'transform: translateX(20px);' : 'transform: translateX(0);'"></span>
                                </label>
                            </div>

                            <div style="margin-top: 18px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px;">
                                <button type="button" @click="savePreferences()" class="ic-btn ic-btn-primary">
                                    <span>Save Cookie Preferences</span>
                                    <svg style="width: 14px; height: 14px;" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </button>
                                <span x-show="savedToast" x-transition style="font-size: 13px; font-weight: 600; color: #059669; display: flex; align-items: center; gap: 6px;">
                                    <svg style="width: 16px; height: 16px;" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Preferences saved
                                </span>
                            </div>
                        </div>
                    </div>
                </section>

                <section id="browser-controls" class="legal-section">
                    <h2 class="legal-section-title"><?= $icon('M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z') ?><span>7. Clearing Cookies in Your Browser</span></h2>
                    <div class="legal-section-body">
                        <p>You can also delete cookies in your browser. Deleting essential cookies signs you out.</p>
                        <ul>
                            <li><strong>Google Chrome:</strong> Settings &gt; Privacy and security &gt; Delete browsing data.</li>
                            <li><strong>Mozilla Firefox:</strong> Settings &gt; Privacy &amp; Security &gt; Cookies and Site Data &gt; Clear Data.</li>
                            <li><strong>Apple Safari:</strong> Settings &gt; Privacy &gt; Manage Website Data.</li>
                            <li><strong>Microsoft Edge:</strong> Settings &gt; Cookies and site permissions &gt; Manage and delete cookies.</li>
                        </ul>
                    </div>
                </section>

                <section id="contact-dpo" class="legal-section" style="margin-bottom: 0;">
                    <h2 class="legal-section-title"><?= $icon('M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z') ?><span>8. Questions</span></h2>
                    <div class="legal-section-body">
                        <p>Questions about cookies or your data? See our <a href="<?= url('/privacy') ?>">Privacy Policy</a> or contact us.</p>
                        <div class="legal-highlight-box" style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 16px;">
                            <div style="min-width: 0;">
                                <div style="font-weight: 700; color: #000623; font-size: 14.5px;">InternConnect Privacy Team</div>
                                <div style="font-size: 13px; color: #64748B;">Victoria Island, Lagos, Nigeria</div>
                                <div style="font-size: 13px; color: #4F48E6; font-weight: 600; margin-top: 4px; overflow-wrap: anywhere;"><?= htmlspecialchars($contactEmail, ENT_QUOTES) ?></div>
                            </div>
                            <a href="<?= url('/contact') ?>" class="ic-btn ic-btn-secondary">
                                <span>Contact us</span>
                                <svg style="width: 14px; height: 14px;" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                            </a>
                        </div>
                    </div>
                </section>

            </article>
        </div>
    </div>

</section>
