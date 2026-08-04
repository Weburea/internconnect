<?php

declare(strict_types=1);

/**
 * View: Password Reset Success Screen
 * Layout: auth (no navbar / footer — clean slate)
 */

$extraCss = ['assets/css/welcome.css'];
?>

<style>
@keyframes wlcBadgeRipple {
    0% { box-shadow: 0 0 0 0 rgba(79, 71, 230, 0.4); }
    50% { box-shadow: 0 0 0 16px rgba(79, 71, 230, 0); }
    100% { box-shadow: 0 0 0 0 rgba(79, 71, 230, 0); }
}
@keyframes wlcCheckDraw {
    0% { stroke-dashoffset: 40; opacity: 0; }
    20% { stroke-dashoffset: 0; opacity: 1; }
    80% { stroke-dashoffset: 0; opacity: 1; }
    100% { stroke-dashoffset: -40; opacity: 0; }
}
.wlc-badge-animated {
    animation: wlcBadgeRipple 2.5s infinite ease-in-out !important;
}
.wlc-check-animated {
    stroke-dasharray: 40;
    stroke-dashoffset: 40;
    animation: wlcCheckDraw 2.5s infinite ease-in-out !important;
}
</style>

<div class="wlc-page">

    <!-- ── Header ── -->
    <header class="wlc-header">
        <a href="<?= url('/') ?>" class="wlc-logo" aria-label="InternConnect home">
            <img src="<?= asset('assets/images/logos/Logo_dark_dx00tg.svg') ?>" alt="InternConnect">
        </a>

        <!-- Empty placeholder to balance header flex justify-between -->
        <div style="width: 140px;" aria-hidden="true"></div>
    </header>

    <!-- ── Main ── -->
    <main class="wlc-main">
        <div class="wlc-grid">

            <!-- Left: Image Card (Keep identical illustration image) -->
            <div class="wlc-card" role="img" aria-label="Professional career illustration">
                <img src="<?= asset('assets/images/password_image.jpg') ?>" alt="Welcome">
                <div class="wlc-card-overlay" aria-hidden="true"></div>
                <div class="wlc-card-quote">
                    <h3>Your next step begins<br>with secure access.</h3>
                    <div class="wlc-card-bar" aria-hidden="true"></div>
                </div>
            </div>

            <!-- Right: Content -->
            <div class="wlc-right">

                <!-- Checkmark Badge (Updated to brand purple theme) -->
                <div class="wlc-badge wlc-badge-animated" aria-hidden="true" style="background-color: #EEF2FF; border: none; display: flex; align-items: center; justify-content: center; width: 56px; height: 56px; border-radius: 9999px; box-shadow: none; margin-bottom: 24px;">
                    <svg fill="none" viewBox="0 0 24 24" stroke="#4F47E6" stroke-width="3" style="width: 28px; height: 28px; color: #4F47E6;">
                        <path class="wlc-check-animated" stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" style="stroke: #4F47E6;" />
                    </svg>
                </div>

                <!-- Heading (Updated to match mockup exactly) -->
                <h1 class="wlc-h1" style="font-size: 56px; line-height: 58px; font-weight: 700; margin-bottom: 24px; color: #191C1D;">
                    You're ready to<br>
                    continue
                </h1>

                <!-- Subtitle -->
                <p class="wlc-sub" style="font-size: 16px; line-height: 26px; color: #464555; margin-bottom: 32px; max-w-md;">
                    Your password has been successfully updated. Sign in and continue where you left off.
                </p>

                <!-- CTA Buttons (Single purple Continue to Log In button) -->
                <div class="wlc-actions" style="width: 100%;">
                    <a href="<?= url('/login') ?>" class="wlc-btn-dash" style="justify-content: center; width: auto; min-width: 200px;">
                        <span>Continue to Log In</span>
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </a>
                </div>

            </div>
        </div>
    </main>

    <!-- ── Footer ── -->
    <footer class="wlc-footer" style="background: #ffffff; border-top: 1px solid #E2E8F0; width: 100%; padding: 0 32px; box-sizing: border-box;">
        <div class="wlc-footer-content" style="max-width: 1140px; margin: 0 auto; padding: 18px 0; display: flex; flex-direction: row; justify-content: space-between; align-items: center; width: 100%; box-sizing: border-box; font-size: 12px; color: #464555; font-weight: 500; uppercase; tracking-wider;">
            <span>© <span id="copyright-year-pwd-success">2026</span> InternConnect. All rights reserved.</span>
            <div style="display: flex; gap: 24px;">
                <a href="#" style="text-transform: uppercase; font-weight: 600; color: #9CA3AF; text-decoration: none; transition: color 0.15s; font-size: 13px;" onmouseover="this.style.color='#191C1D'" onmouseout="this.style.color='#9CA3AF'">Privacy</a>
                <a href="#" style="text-transform: uppercase; font-weight: 600; color: #9CA3AF; text-decoration: none; transition: color 0.15s; font-size: 13px;" onmouseover="this.style.color='#191C1D'" onmouseout="this.style.color='#9CA3AF'">Terms</a>
            </div>
        </div>
    </footer>

</div>

<script>
    document.getElementById('copyright-year-pwd-success').textContent = new Date().getFullYear();
</script>
