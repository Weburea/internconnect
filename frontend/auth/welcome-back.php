<?php
declare(strict_types=1);
/**
 * View: Welcome Back Screen (After Login Success)
 * Layout: auth (no navbar / footer — clean slate)
 * @var string $name  First name of the user
 * @var string $role  User role (candidate | employer)
 */

$firstName = explode(' ', trim($name))[0];
$role = $role ?? 'candidate';

$dashboardUrl = ($role === 'employer') ? url('/employer/dashboard') : url('/candidate/dashboard');
$secondaryLabel = ($role === 'employer') ? 'My Internships' : 'Track Application';
$secondaryUrl = ($role === 'employer') ? url('/employer/dashboard') : url('/internships');
$subText = ($role === 'employer') 
    ? 'Ready to scale your team? Review applications, manage openings, and find your next talent match.' 
    : 'Your dashboard is ready. Explore personal recommendations, check pending applications, and level up your career.';
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
            <div class="wlc-card" role="img" aria-label="Professional workspace illustration">
                <img src="<?= asset('assets/images/welcome_smiling_woman.png') ?>"
                     alt="A smiling professional working at their desk">
                <div class="wlc-card-overlay" aria-hidden="true"></div>
                <div class="wlc-card-quote">
                    <h3>Welcome back to your<br>professional hub.</h3>
                    <div class="wlc-card-bar" aria-hidden="true"></div>
                </div>
            </div>

            <!-- Right: Content -->
            <div class="wlc-right">

                <!-- Checkmark Badge -->
                <div class="wlc-badge" aria-hidden="true" style="background: #4F47E6;">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.8" class="text-white">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                </div>

                <!-- Heading -->
                <h1 class="wlc-h1">
                    Welcome back,<br>
                    <span style="text-transform: uppercase; color: #4F47E6;"><?= htmlspecialchars($firstName) ?>.</span>
                </h1>

                <!-- Subtitle -->
                <p class="wlc-sub">
                    <?= htmlspecialchars($subText) ?>
                </p>

                <!-- CTA Buttons -->
                <div class="wlc-actions">
                    <a href="<?= $dashboardUrl ?>" class="wlc-btn-dash">
                        <span>Go to Dashboard</span>
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </a>
                    <a href="<?= $secondaryUrl ?>" class="wlc-btn-track">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                        </svg>
                        <span><?= htmlspecialchars($secondaryLabel) ?></span>
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
