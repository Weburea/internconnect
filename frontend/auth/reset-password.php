<?php

declare(strict_types=1);

/**
 * Page: Create New Password
 *
 * Allows users to input their new password with validation checklist.
 */

$isError = ($_GET['error'] ?? '') === 'invalid';
?>
<section id="reset-password-page" class="flex w-full h-screen overflow-hidden font-sans" style="background-color: #F8F9FA;">

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
                Create New Password
            </h1>
            <p class="text-[14px] leading-[22px] text-[#464555] mb-10 text-center max-w-[320px]">
                Please enter and confirm your new password below.
            </p>

            <!-- Error Alert -->
            <?php if ($isError): ?>
                <div class="w-full text-center py-2.5 px-4 rounded-lg text-[13px] font-medium mb-4" style="background-color: #FDE8E8; color: #E02424; border: 1px solid #F8B4B4;">
                    Validation failed. Please satisfy all password requirements.
                </div>
            <?php endif; ?>

            <!-- Form -->
            <form x-data="passwordForm()" action="<?= url('/reset-password') ?>" method="POST" @submit="if (!canSubmit()) { $event.preventDefault(); alert('Please satisfy all password requirements!'); }" class="w-full max-w-[380px]" style="display: flex; flex-direction: column; gap: 1.25rem;">

                <!-- New Password Field -->
                <div>
                    <label for="password" class="block text-[14px] font-semibold text-[#191C1D] mb-2">
                        New Password
                    </label>
                    <div style="position: relative; display: flex; align-items: center;">
                        <!-- Lock Icon (left) -->
                        <div style="position: absolute; left: 14px; display: flex; align-items: center; pointer-events: none;">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#9CA3AF" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2" />
                                <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                            </svg>
                        </div>
                        <input
                            :type="showPassword ? 'text' : 'password'"
                            id="password"
                            name="password"
                            placeholder="••••••••"
                            required
                            x-model="password"
                            @input="validate()"
                            class="w-full rounded-lg border border-brandBorder bg-white focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all text-[14px] text-[#191C1D] placeholder-[#464555]/50"
                            style="padding: 10px 40px 10px 40px;"
                        >
                        <!-- Eye Icon (right toggle) -->
                        <button
                            type="button"
                            @click="showPassword = !showPassword"
                            style="position: absolute; right: 14px; display: flex; align-items: center;"
                            class="text-gray-400 hover:text-gray-600 focus:outline-none"
                        >
                            <template x-if="showPassword">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24" />
                                    <line x1="1" y1="1" x2="23" y2="23" />
                                </svg>
                            </template>
                            <template x-if="!showPassword">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                                    <circle cx="12" cy="12" r="3" />
                                </svg>
                            </template>
                        </button>
                    </div>
                </div>

                <!-- Confirm Password Field -->
                <div>
                    <label for="confirm_password" class="block text-[14px] font-semibold text-[#191C1D] mb-2">
                        Confirm Password
                    </label>
                    <div style="position: relative; display: flex; align-items: center;">
                        <!-- Lock Icon (left) -->
                        <div style="position: absolute; left: 14px; display: flex; align-items: center; pointer-events: none;">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#9CA3AF" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2" />
                                <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                            </svg>
                        </div>
                        <input
                            :type="showConfirmPassword ? 'text' : 'password'"
                            id="confirm_password"
                            name="confirm_password"
                            placeholder="••••••••"
                            required
                            x-model="confirmPassword"
                            class="w-full rounded-lg border border-brandBorder bg-white focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all text-[14px] text-[#191C1D] placeholder-[#464555]/50"
                            style="padding: 10px 40px 10px 40px;"
                        >
                        <!-- Eye Icon (right toggle) -->
                        <button
                            type="button"
                            @click="showConfirmPassword = !showConfirmPassword"
                            style="position: absolute; right: 14px; display: flex; align-items: center;"
                            class="text-gray-400 hover:text-gray-600 focus:outline-none"
                        >
                            <template x-if="showConfirmPassword">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24" />
                                    <line x1="1" y1="1" x2="23" y2="23" />
                                </svg>
                            </template>
                            <template x-if="!showConfirmPassword">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                                    <circle cx="12" cy="12" r="3" />
                                </svg>
                            </template>
                        </button>
                    </div>
                </div>

                <!-- Password Requirements Box -->
                <div class="rounded-lg p-4 bg-[#F8F9FA] border border-[#E2E8F0] mb-2">
                    <h3 class="text-[13px] font-bold text-[#191C1D] mb-3">Password Requirements:</h3>
                    <div class="space-y-2.5">
                        
                        <!-- 8 Characters -->
                        <div class="flex items-center gap-2.5 text-[13px]">
                            <template x-if="hasMinLength">
                                <svg class="w-4 h-4" style="color: #4F47E6;" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                            </template>
                            <template x-if="!hasMinLength">
                                <div class="w-4 h-4 rounded-full border-2 border-brandBorder bg-white"></div>
                            </template>
                            <span :class="hasMinLength ? 'text-[#191C1D] font-semibold' : 'text-[#464555]'">At least 8 characters</span>
                        </div>

                        <!-- Special Character -->
                        <div class="flex items-center gap-2.5 text-[13px]">
                            <template x-if="hasSpecialChar">
                                <svg class="w-4 h-4" style="color: #4F47E6;" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                            </template>
                            <template x-if="!hasSpecialChar">
                                <div class="w-4 h-4 rounded-full border-2 border-brandBorder bg-white"></div>
                            </template>
                            <span :class="hasSpecialChar ? 'text-[#191C1D] font-semibold' : 'text-[#464555]'">One special character</span>
                        </div>

                        <!-- Number -->
                        <div class="flex items-center gap-2.5 text-[13px]">
                            <template x-if="hasNumber">
                                <svg class="w-4 h-4" style="color: #4F47E6;" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                            </template>
                            <template x-if="!hasNumber">
                                <div class="w-4 h-4 rounded-full border-2 border-brandBorder bg-white"></div>
                            </template>
                            <span :class="hasNumber ? 'text-[#191C1D] font-semibold' : 'text-[#464555]'">One number</span>
                        </div>

                    </div>
                </div>

                <!-- Submit Button -->
                <button
                    type="submit"
                    id="reset-password-btn"
                    :disabled="!canSubmit()"
                    :class="canSubmit() ? 'opacity-100 cursor-pointer' : 'opacity-60 cursor-not-allowed'"
                    class="w-full bg-primary text-white py-3 rounded-lg text-[16px] font-medium hover:bg-primary-hover transition-colors shadow-sm mt-2 flex items-center justify-center gap-2"
                >
                    Reset Password
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
                <span class="text-[12px] font-semibold tracking-wider uppercase" style="color: rgba(255,255,255,0.9);">Create New Password</span>
            </div>

            <!-- Headline -->
            <h2 class="font-bold mb-4 tracking-tight text-white" style="font-size: 48px; line-height: 50px;">
                Set a stronger<br>
                password.
            </h2>

            <!-- Description -->
            <p class="mb-12 max-w-md" style="font-size: 18px; line-height: 28px; color: rgba(255,255,255,0.7);">
                Choose a new password that keeps your account protected and easy to access.
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
                    <div class="text-[10px] font-bold tracking-widest uppercase mb-1" style="color: rgba(255,255,255,0.6);">Strong Security</div>
                    <div class="font-semibold text-white text-[15px]">Better protection</div>
                </div>

                <!-- Card 2: Quick Recovery -->
                <div class="rounded-xl p-4 flex-1 transition-all cursor-default" style="background-color: rgba(36,42,77,0.8); border: 1px solid rgba(255,255,255,0.1); backdrop-filter: blur(12px);">
                    <div class="mb-3">
                        <svg class="w-5 h-5" style="color: rgba(255,255,255,0.8);" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.75 13.5 10.5 6.75 14.25 10.5 20.25 4.5M3.75 13.5H9m-5.25 0V9" />
                        </svg>
                    </div>
                    <div class="text-[10px] font-bold tracking-widest uppercase mb-1" style="color: rgba(255,255,255,0.6);">Simple Setup</div>
                    <div class="font-semibold text-white text-[15px]">Complete in seconds</div>
                </div>

            </div>
            <!-- /Info Cards -->

        </div>
        <!-- /Content -->

    </div>
    <!-- /Right Section -->

</section>

<!-- Alpine.js script for dynamic password verification -->
<script>
    function passwordForm() {
        return {
            password: '',
            confirmPassword: '',
            showPassword: false,
            showConfirmPassword: false,
            hasMinLength: false,
            hasSpecialChar: false,
            hasNumber: false,

            validate() {
                const pwd = this.password;
                this.hasMinLength = pwd.length >= 8;
                this.hasSpecialChar = /[!@#$%^&*(),.?":{}|<>]/.test(pwd);
                this.hasNumber = /\d/.test(pwd);
            },

            canSubmit() {
                return this.hasMinLength && this.hasSpecialChar && this.hasNumber && (this.password === this.confirmPassword) && this.confirmPassword.length > 0;
            }
        };
    }
</script>
