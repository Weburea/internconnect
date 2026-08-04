<?php
/**
 * View: Application Success
 * Rendered after a candidate successfully applies for an internship.
 * Layout: app (default layout includes navbar and footer)
 */

$companyName = $internship['company'] ?? 'Innovate Tech Group';
?>
<?php
$extraCss = ['assets/css/application-success.css'];
?>

<div class="app-succ-page">
    <div class="app-succ-grid">

        <!-- Left: Image Card -->
        <!-- Note: We are using a placeholder or an existing image with a CSS tint to mimic the mockup's aesthetic -->
        <div class="app-succ-card" role="img" aria-label="Professional office">
            <img src="<?= asset('assets/images/appy_image.png') ?>" alt="Office environment">
            <div class="app-succ-card-overlay" aria-hidden="true"></div>
            <div class="app-succ-card-overlay-2" aria-hidden="true"></div>
            <div class="app-succ-card-quote">
                <h3>The next chapter of your<br>career starts here.</h3>
                <div class="app-succ-card-bar" aria-hidden="true"></div>
            </div>
        </div>

        <!-- Right: Content -->
        <div class="app-succ-right">

            <!-- Checkmark Badge -->
            <div class="app-succ-badge" aria-hidden="true">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
            </div>

            <!-- Heading -->
            <h1 class="app-succ-h1">
                Application Submitted<br>Successfully!
            </h1>

            <!-- Subtitle -->
            <p class="app-succ-sub">
                We've sent your application to <strong><?= htmlspecialchars($companyName) ?></strong>. You can track its
                status and communicate with recruiters in your dashboard.
            </p>

            <!-- CTA Buttons -->
            <div class="app-succ-actions">
                <a href="<?= url('/dashboard') ?>" class="app-succ-btn-dash">
                    <span>Go to Dashboard</span>
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </a>
                <a href="<?= url('/internships') ?>" class="app-succ-btn-track">
                    <span>Track Application</span>
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                    </svg>
                </a>
            </div>

            <div class="app-succ-divider"></div>

            <!-- Current Status -->
            <div class="app-succ-status">
                <div class="app-succ-icons">
                    <!-- Document icon -->
                    <div class="app-succ-icon-circle">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <!-- Send icon (active) -->
                    <div class="app-succ-icon-circle is-active">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                        </svg>
                    </div>
                    <!-- Clock icon -->
                    <div class="app-succ-icon-circle">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <div class="app-succ-status-text">
                    <span class="app-succ-status-label">Current Status</span>
                    <span class="app-succ-status-val">Application under initial review</span>
                </div>
            </div>

        </div>
    </div>
</div>