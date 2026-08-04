<?php
declare(strict_types=1);
/**
 * View: Employer Welcome Screen (Signup Success)
 * Layout: auth (no navbar / footer — clean slate)
 * @var string $name  Company name of the employer
 */

$displayName = $name ?? ($_GET['name'] ?? 'Flux Technologies');
?>
<?php
$extraCss = ['assets/css/welcome.css'];
?>
<!-- Load Iconify CDN -->
<script src="https://code.iconify.design/iconify-icon/2.1.0/iconify-icon.min.js"></script>

<style>
/* CSS Styles inlined to guarantee instant un-cached loading */
.wlc-benefits-grid {
    display: grid !important;
    grid-template-columns: repeat(3, 1fr) !important;
    gap: 16px !important;
    margin-top: 24px !important;
    margin-bottom: 32px !important;
    width: 100% !important;
}
.wlc-benefit-card {
    background: #ffffff !important;
    border: 1.5px solid #E2E8F0 !important;
    border-radius: 16px !important;
    padding: 20px !important;
    display: flex !important;
    flex-direction: column !important;
    align-items: center !important;
    text-align: center !important;
    box-sizing: border-box !important;
}
.wlc-benefit-icon-wrapper {
    width: 40px !important;
    height: 40px !important;
    border-radius: 12px !important;
    background: rgba(79, 71, 230, 0.05) !important;
    color: #4F47E6 !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    margin-bottom: 12px !important;
}
.wlc-benefit-icon {
    font-size: 20px !important;
}
.wlc-benefit-title {
    font-size: 14px !important;
    font-weight: 700 !important;
    color: #191C1D !important;
    margin-bottom: 4px !important;
}
.wlc-benefit-desc {
    font-size: 12px !important;
    color: #464555 !important;
    line-height: 1.4 !important;
}

@media (max-width: 600px) {
    .wlc-benefits-grid {
        grid-template-columns: 1fr !important;
        gap: 12px !important;
    }
    .wlc-footer-content {
        flex-direction: column !important;
        gap: 12px !important;
        text-align: center !important;
        padding: 14px 0 !important;
    }
    .wlc-footer {
        padding: 0 20px !important;
    }
}
</style>

<div class="wlc-page">

    <!-- ── Header (Navbar Removed & Left Logo Aligned) ── -->
    <header class="wlc-header" style="display: flex; justify-content: space-between; align-items: center; width: 100%;">
        <a href="<?= url('/') ?>" class="wlc-logo" aria-label="InternConnect home">
            <img src="<?= asset('assets/images/logos/Logo_dark_dx00tg.svg') ?>" alt="InternConnect">
        </a>
        <!-- Empty placeholder to balance header flex justify-between -->
        <div style="width: 140px;" class="hidden md:block" aria-hidden="true"></div>
    </header>

    <!-- ── Main ── -->
    <main class="wlc-main">
        <div class="wlc-grid">

            <!-- Left: Image Card (Unchanged Layout) -->
            <div class="wlc-card" role="img" aria-label="Employer recruitment illustration">
                <img src="<?= asset('assets/images/welcome_basic_user.png') ?>" alt="Welcome">
                <div class="wlc-card-overlay" aria-hidden="true"></div>
                <div class="wlc-card-quote">
                    <h3>Empower your business with<br>the brightest young minds.</h3>
                    <div class="wlc-card-bar" aria-hidden="true"></div>
                </div>
            </div>

            <!-- Right: Content -->
            <div class="wlc-right" style="align-items: flex-start;">

                <!-- Checkmark Badge (Styled in Light and Thick Brand Purple Theme) -->
                <div class="wlc-badge animate-scale-in" aria-hidden="true" style="background: #EEF2FF; border: 4px solid #ffffff; color: #4F47E6; box-shadow: 0 0 0 8px rgba(79, 71, 230, 0.05); margin-bottom: 24px;">
                    <svg fill="none" viewBox="0 0 24 24" stroke="#4F47E6" stroke-width="2.8" style="width: 26px; height: 26px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                </div>

                <!-- Heading -->
                <h1 class="wlc-h1" style="font-size: 36px; line-height: 44px; text-align: left; letter-spacing: -0.8px; margin-bottom: 12px;">
                    You're ready to hire,<br><span><?= htmlspecialchars($displayName) ?></span>
                </h1>

                <!-- Subtitle -->
                <p class="wlc-sub" style="text-align: left; max-width: 460px; margin-bottom: 24px;">
                    Your employer account is fully verified and live. You now have access to a network of top-tier student talent ready to accelerate your projects.
                </p>

                <!-- Benefits Cards (Static, No Hover Effects, Single Line Grid) -->
                <div class="wlc-benefits-grid">
                    <!-- Post a role -->
                    <div class="wlc-benefit-card">
                        <div class="wlc-benefit-icon-wrapper">
                            <iconify-icon icon="lucide:plus-circle" class="wlc-benefit-icon"></iconify-icon>
                        </div>
                        <h4 class="wlc-benefit-title">Post a role</h4>
                        <p class="wlc-benefit-desc">Create detailed internship listings with our AI tools.</p>
                    </div>

                    <!-- Review applicants -->
                    <div class="wlc-benefit-card">
                        <div class="wlc-benefit-icon-wrapper">
                            <iconify-icon icon="lucide:users" class="wlc-benefit-icon"></iconify-icon>
                        </div>
                        <h4 class="wlc-benefit-title">Review applicants</h4>
                        <p class="wlc-benefit-desc">Filter and evaluate candidates through our tracker.</p>
                    </div>

                    <!-- Message candidates -->
                    <div class="wlc-benefit-card">
                        <div class="wlc-benefit-icon-wrapper">
                            <iconify-icon icon="lucide:message-square" class="wlc-benefit-icon"></iconify-icon>
                        </div>
                        <h4 class="wlc-benefit-title">Message candidates</h4>
                        <p class="wlc-benefit-desc">Communicate directly and schedule interviews.</p>
                    </div>
                </div>

                <!-- CTA Buttons -->
                <div class="wlc-actions" style="width: 100%;">
                    <a href="<?= url('/internships/create') ?>" class="wlc-btn-dash">
                        <span>Post your first internship</span>
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </a>
                    <a href="<?= url('/dashboard') ?>" class="wlc-btn-track">
                        <span>Enter dashboard</span>
                    </a>
                </div>

            </div>
        </div>
    </main>

    <!-- ── Footer (Aligned symmetric gutters) ── -->
    <footer class="wlc-footer" style="background: #ffffff; border-top: 1px solid #E2E8F0; width: 100%; padding: 0 32px; box-sizing: border-box;">
        <div class="wlc-footer-content" style="max-width: 1140px; margin: 0 auto; padding: 18px 0; display: flex; flex-direction: row; justify-content: space-between; align-items: center; width: 100%; box-sizing: border-box;">
            <span>© <span id="copyright-year-welcome-employer">2026</span> INTERNCONNECT. ALL RIGHTS RESERVED.</span>
            <div style="display: flex; gap: 24px;">
                <a href="#" style="text-transform: uppercase; font-weight: 600; color: #9CA3AF; text-decoration: none; transition: color 0.15s; font-size: 13px;" onmouseover="this.style.color='#191C1D'" onmouseout="this.style.color='#9CA3AF'">Privacy Policy</a>
                <a href="#" style="text-transform: uppercase; font-weight: 600; color: #9CA3AF; text-decoration: none; transition: color 0.15s; font-size: 13px;" onmouseover="this.style.color='#191C1D'" onmouseout="this.style.color='#9CA3AF'">Terms of Service</a>
            </div>
        </div>
    </footer>

</div>

<script>
    document.getElementById('copyright-year-welcome-employer').textContent = new Date().getFullYear();
</script>
