<?php

declare(strict_types=1);

/**
 * Page: Forgot Password
 *
 * Allows users to request a password reset link via email.
 */

$isSent = ($_GET['sent'] ?? '') === '1';
$isError = ($_GET['error'] ?? '') === 'invalid';
?>
<section id="forgot-password-page" class="flex w-full h-screen overflow-hidden font-sans" style="background-color: #F8F9FA;">

    <!-- ═══════════════════════════════════════════════════ -->
    <!-- Left Section: Form                                   -->
    <!-- ═══════════════════════════════════════════════════ -->
    <div class="w-full md:w-1/2 flex flex-col p-8 lg:p-12 overflow-y-auto" style="background-color: #F8F9FA;">

        <!-- Logo -->
        <a href="<?= url('/') ?>" class="flex-shrink-0 flex items-center mb-6">
            <img src="<?= asset('assets/images/logos/Logo_dark_dx00tg.svg') ?>" alt="InternConnect" class="h-6 w-auto">
        </a>

        <!-- Card -->
        <div class="w-full max-w-[460px] mx-auto my-auto rounded-3xl flex flex-col items-center px-10 py-12" style="background-color: #ffffff; border: 1px solid #E2E8F0;">

            <?php if ($isSent): ?>
                <!-- Success State -->
                <div class="w-16 h-16 rounded-full flex items-center justify-center mb-6" style="background-color: #DEF7EC;">
                    <svg class="w-8 h-8" style="color: #0E9F6E;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="20 6 9 17 4 12" />
                    </svg>
                </div>

                <!-- Title & Description -->
                <h1 class="text-[26px] leading-[32px] font-bold text-[#191C1D] mb-2 text-center tracking-tight">
                    Reset Link Sent
                </h1>
                <p class="text-[14px] leading-[22px] text-[#464555] mb-8 text-center max-w-[320px]">
                    We've sent a password reset link to your email address. Please check your inbox and spam folder.
                </p>

                <!-- Back to Login Button -->
                <a
                    href="<?= url('/login') ?>"
                    class="w-full bg-primary text-white py-3 rounded-lg text-[16px] font-medium hover:bg-primary-hover transition-colors shadow-sm text-center block"
                    style="text-decoration: none;"
                >
                    Back to Login
                </a>

            <?php else: ?>
                <!-- Form State -->
                
                <!-- Icon -->
                <div class="w-16 h-16 rounded-full flex items-center justify-center mb-6" style="background-color: #EEF2FF;">
                    <svg class="w-8 h-8" style="color: #4F47E6;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <!-- Counter-clockwise circular arrow -->
                        <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8" />
                        <path d="M3 3v5h5" />
                        <!-- Lock in the center -->
                        <rect x="9" y="12" width="6" height="5" rx="1" fill="none" stroke="currentColor" stroke-width="2" />
                        <path d="M10 12V10a2 2 0 1 1 4 0v2" />
                    </svg>
                </div>

                <!-- Title & Description -->
                <h1 class="text-[26px] leading-[32px] font-bold text-[#191C1D] mb-2 text-center tracking-tight">
                    Forgot Password?
                </h1>
                <p class="text-[14px] leading-[22px] text-[#464555] mb-10 text-center max-w-[320px]">
                    Enter your email address and we'll send you a link to reset your password.
                </p>

                <!-- Error Alert -->
                <?php if ($isError): ?>
                    <div class="w-full text-center py-2.5 px-4 rounded-lg text-[13px] font-medium mb-4" style="background-color: #FDE8E8; color: #E02424; border: 1px solid #F8B4B4;">
                        Invalid email address. Please try again.
                    </div>
                <?php endif; ?>

                <!-- Form -->
                <form action="<?= url('/forgot-password') ?>" method="POST" class="w-full max-w-[380px]" style="display: flex; flex-direction: column; gap: 1.25rem;">

                    <!-- Email Field -->
                    <div>
                        <label for="email" class="block text-[14px] font-semibold text-[#191C1D] mb-2">
                            Email Address
                        </label>
                        <div style="position: relative; display: flex; align-items: center;">
                            <!-- Envelope Icon (inside the input) -->
                            <div style="position: absolute; left: 14px; display: flex; align-items: center; pointer-events: none;">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#9CA3AF" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                                </svg>
                            </div>
                            <input
                                type="email"
                                id="email"
                                name="email"
                                placeholder="name@company.com"
                                autocomplete="email"
                                required
                                class="w-full rounded-lg border border-brandBorder bg-white focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all text-[14px] text-[#191C1D] placeholder-[#464555]/50"
                                style="padding: 10px 16px 10px 40px;"
                            >
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button
                        type="submit"
                        id="send-reset-link-btn"
                        class="w-full bg-primary text-white py-3 rounded-lg text-[16px] font-medium hover:bg-primary-hover transition-colors shadow-sm mt-2 flex items-center justify-center gap-2"
                    >
                        Send Reset Link
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                        </svg>
                    </button>

                </form>

                <!-- Back to Login -->
                <div class="mt-8 text-center">
                    <a href="<?= url('/login') ?>" class="inline-flex items-center gap-1.5 text-[14px] font-medium transition-colors" style="color: #4F47E6;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                        </svg>
                        Back to Login
                    </a>
                </div>

            <?php endif; ?>

        </div>
        <!-- /Card -->

        <!-- Footer -->
        <div class="flex-shrink-0 flex justify-between items-center text-[12px] text-[#464555] font-medium uppercase tracking-wider pt-4">
            <span>&copy; 2024 INTERNCONNECT.</span>
            <div class="space-x-6">
                <a href="#" class="hover:text-[#191C1D] transition-colors">PRIVACY</a>
                <a href="#" class="hover:text-[#191C1D] transition-colors">TERMS</a>
            </div>
        </div>

    </div>
    <!-- /Left Section -->

    <!-- ═══════════════════════════════════════════════════ -->
    <!-- Right Section: Presentation                          -->
    <!-- ═══════════════════════════════════════════════════ -->
    <div class="hidden md:flex w-1/2 text-white flex-col relative items-center justify-center p-12 overflow-hidden" style="background-color: #1A1C3C;">

        <!-- Background gradient -->
        <div class="absolute inset-0 bg-gradient-to-br from-[#1E234B] to-[#12142B] z-0"></div>

        <!-- Plant image backdrop blended -->
        <div class="absolute inset-0 bg-cover bg-bottom mix-blend-overlay z-0" style="opacity: 0.35; background-image: url('<?= asset('assets/images/plant.png') ?>');"></div>

        <!-- Subtle pattern overlay -->
        <div class="absolute inset-0 mix-blend-overlay z-0" style="opacity: 0.05; background-image: url('https://www.transparenttextures.com/patterns/cubes.png');"></div>

        <!-- Large abstract geometry in background -->
        <div class="absolute z-0" style="bottom: -10%; right: -10%; width: 75%; height: 75%; border: 40px solid rgba(255,255,255,0.05); border-top-left-radius: 80px;"></div>

        <!-- Content -->
        <div class="relative z-10 w-full max-w-[500px]">

            <!-- Badge -->
            <div class="inline-flex items-center gap-2 rounded-full px-4 py-1.5 mb-6" style="border: 1px solid rgba(255,255,255,0.2); background-color: rgba(255,255,255,0.05); backdrop-filter: blur(4px);">
                <svg class="w-4 h-4" style="color: rgba(255,255,255,0.8);" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="text-[12px] font-semibold tracking-wider uppercase" style="color: rgba(255,255,255,0.9);">Secure Account Recovery</span>
            </div>

            <!-- Headline -->
            <h2 class="font-bold mb-4 tracking-tight text-white" style="font-size: 48px; line-height: 50px;">
                Regain access<br>
                to your account<br>
                with <span class="font-serif italic font-light" style="color: rgba(255,255,255,0.9);">ease.</span>
            </h2>

            <!-- Description -->
            <p class="mb-12 max-w-md" style="font-size: 18px; line-height: 28px; color: rgba(255,255,255,0.7);">
                Enter your email address and we'll help you reset your password securely and quickly.
            </p>

            <!-- Info Cards -->
            <div class="flex gap-4">

                <!-- Card 1: Protected Access -->
                <div class="rounded-xl p-4 flex-1 transition-all cursor-default" style="background-color: rgba(36,42,77,0.8); border: 1px solid rgba(255,255,255,0.1); backdrop-filter: blur(12px);">
                    <div class="mb-3">
                        <svg class="w-5 h-5" style="color: rgba(255,255,255,0.8);" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z" />
                        </svg>
                    </div>
                    <div class="text-[10px] font-bold tracking-widest uppercase mb-1" style="color: rgba(255,255,255,0.6);">Protected Access</div>
                    <div class="font-semibold text-white text-[15px]">Your account stays secure</div>
                </div>

                <!-- Card 2: Quick Recovery -->
                <div class="rounded-xl p-4 flex-1 transition-all cursor-default" style="background-color: rgba(36,42,77,0.8); border: 1px solid rgba(255,255,255,0.1); backdrop-filter: blur(12px);">
                    <div class="mb-3">
                        <svg class="w-5 h-5" style="color: rgba(255,255,255,0.8);" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.75 13.5 10.5 6.75 14.25 10.5 20.25 4.5M3.75 13.5H9m-5.25 0V9" />
                        </svg>
                    </div>
                    <div class="text-[10px] font-bold tracking-widest uppercase mb-1" style="color: rgba(255,255,255,0.6);">Quick Recovery</div>
                    <div class="font-semibold text-white text-[15px]">Reset in just a few steps</div>
                </div>

            </div>
            <!-- /Info Cards -->

        </div>
        <!-- /Content -->

    </div>
    <!-- /Right Section -->

</section>
