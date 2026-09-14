<?php
declare(strict_types=1);
/**
 * View: Welcome Screen (After Signup / Subscription Success)
 * Layout: auth (no navbar / footer — clean slate)
 * @var string $name  First name of the user
 */

$firstName = explode(' ', trim($name))[0];
?>
<?php
$extraCss = ['assets/css/welcome_pro.css'];
?>

<div class="wlc-page">

    <!-- ── Header ── -->
    <header class="wlc-header">
        <!-- Logo -->
        <a href="<?= url('/') ?>" class="wlc-logo" aria-label="InternConnect home">
            <img src="<?= asset('assets/images/logos/Logo_dark_dx00tg.svg') ?>" alt="InternConnect">
        </a>
        <div style="width: 120px;" aria-hidden="true"></div>
    </header>

    <!-- ── Main ── -->
    <main class="wlc-main">
        <div class="wlc-grid">

            <!-- Left: Image Card -->
            <div class="wlc-card" role="img" aria-label="Professional career illustration">
                <img src="<?= asset('assets/images/welcome_smiling_woman.png') ?>"
                     alt="A smiling professional with a laptop">
                <div class="wlc-card-overlay" aria-hidden="true"></div>
                <div class="wlc-card-quote">
                    <h3>The next chapter of your<br>career starts here.</h3>
                    <div class="wlc-card-bar" aria-hidden="true"></div>
                </div>
            </div>

            <!-- Right: Content -->
            <div class="wlc-right">

                <!-- Checkmark Badge -->
                <div class="wlc-badge" aria-hidden="true">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                </div>

                <!-- Heading -->
                <h1 class="wlc-h1">
                    Welcome to InternConnect,<br>
                    <span style="text-transform: uppercase;"><?= htmlspecialchars($firstName) ?>.</span>
                </h1>

                <!-- Subtitle -->
                <p class="wlc-sub">
                    Your account is ready. Explore top internship openings, leverage AI personalization, and build your profile to stand out.
                </p>

                <!-- CTA Buttons -->
                <div class="wlc-actions">
                    <a href="<?= url('/candidate/dashboard') ?>" class="wlc-btn-dash">
                        <span>Go to Dashboard</span>
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </a>
                    <a href="<?= url('/internships') ?>" class="wlc-btn-track">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                        </svg>
                        <span>Track Application</span>
                    </a>
                </div>

            </div>
        </div>
    </main>

    <!-- ── Footer ── -->
    <footer class="wlc-footer">
        <span>© <?= date('Y') ?> InternConnect. All rights reserved.</span>
    </footer>

</div>
