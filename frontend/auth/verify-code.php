<?php

declare(strict_types=1);

/**
 * Page: Verify Code
 *
 * Allows users to verify their 6-digit recovery code.
 */

$isError = ($_GET['error'] ?? '') === 'invalid';
$email = $_GET['email'] ?? 'fluxtech.io';
?>
<!-- Load Iconify CDN -->
<script src="https://code.iconify.design/iconify-icon/2.1.0/iconify-icon.min.js"></script>

<!-- Register styles -->
<link rel="stylesheet" href="<?= asset('assets/css/register.css') ?>">


<section id="verify-code-page" x-data="verifyCodeWizard()" x-init="init()" class="flex w-full h-screen overflow-hidden font-sans" style="background-color: #F8F9FA;">

    <!-- ═══════════════════════════════════════════════════ -->
    <!-- Left Section: Form                                   -->
    <!-- ═══════════════════════════════════════════════════ -->
    <div class="w-full md:w-1/2 flex flex-col p-8 lg:p-12 overflow-y-auto" style="background-color: #F8F9FA;">

        <!-- Logo -->
        <a href="<?= url('/') ?>" class="flex-shrink-0 flex items-center mb-6">
            <img src="<?= asset('assets/images/logos/Logo_dark_dx00tg.svg') ?>" alt="InternConnect" class="h-6 w-auto">
        </a>

        <!-- Card (Padding reduced on mobile to px-4) -->
        <div class="w-full max-w-[460px] mx-auto my-auto rounded-3xl flex flex-col items-center px-4 sm:px-10 py-10 sm:py-12 relative z-10" style="background-color: #ffffff; border: 1px solid #E2E8F0;">

            <!-- Icon -->
            <div class="w-16 h-16 rounded-full flex items-center justify-center mb-6" style="background-color: #EEF2FF;">
                <svg class="w-8 h-8" style="color: #4F47E6;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2" />
                    <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                </svg>
            </div>

            <!-- Title & Description -->
            <h1 class="text-[26px] leading-[32px] font-bold text-[#191C1D] mb-2 text-center tracking-tight">
                Enter Verification Code
            </h1>
            <p class="text-[14px] leading-[22px] text-[#464555] mb-8 text-center max-w-[320px]">
                We've sent a 6-digit verification code to your email. Please also check your spam or trash folder.
            </p>

            <!-- Global Error Display -->
            <template x-if="server_errors.length > 0">
                <div class="w-full p-4 bg-red-50 border border-red-200 rounded-xl text-red-700 text-[13px] flex flex-col gap-1 mb-5">
                    <template x-for="err in server_errors" :key="err">
                        <span class="font-medium" x-text="err"></span>
                    </template>
                </div>
            </template>

            <!-- Form -->
            <form @submit.prevent="submitCode()" class="w-full max-w-[380px]" style="display: flex; flex-direction: column; gap: 1.25rem;">

                <!-- Hidden Input to store full code -->
                <input type="hidden" name="code" id="verification-code" required>

                <!-- Robust hidden-input sequential verification boxes (from student view, Gap adjusted for mobile to fit screen) -->
                <div class="relative flex justify-between gap-1 sm:gap-2 max-w-[360px] mx-auto py-2 cursor-pointer w-full" @click="$refs.verificationInput.focus()">
                    
                    <!-- Hidden actual input to receive text sequentially -->
                    <input type="text" maxlength="6" x-model="verification_input" x-ref="verificationInput"
                           @input="handleVerificationInput()"
                           class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" 
                           pattern="[0-9]*" inputmode="numeric">
                    
                    <!-- 6 Styled boxes (w-12 h-14) -->
                    <template x-for="i in 6" :key="i">
                        <div class="w-12 h-14 border rounded-lg flex items-center justify-center text-xl font-bold transition-all duration-200 text-[#191C1D] bg-white"
                             :class="verification_input.length === i - 1 ? 'border-primary ring-2 ring-primary/20' : 'border-[#E2E8F0]'">
                             <span x-text="verification_input[i - 1] || ''"></span>
                        </div>
                    </template>
                </div>

                <!-- Timer / Expiration Countdown -->
                <div class="text-center text-[14px] text-[#464555] mt-2 mb-4">
                    <span class="text-secondary-text" x-show="resend_cooldown > 0">
                        Resend code in <strong class="text-primary font-mono" x-text="formatTime(resend_cooldown)"></strong>
                    </span>
                    <button type="button" @click="resend()" x-show="resend_cooldown === 0" class="text-primary hover:underline font-semibold">
                        Resend verification code
                    </button>
                </div>

                <!-- Submit Button -->
                <button
                    type="submit"
                    id="verify-code-btn"
                    :disabled="verification_input.length < 6 || submitting"
                    class="w-full bg-primary text-white py-3 rounded-lg text-[16px] font-semibold hover:bg-primary-hover disabled:opacity-50 disabled:cursor-not-allowed transition-colors shadow-sm flex items-center justify-center gap-2"
                >
                    <span x-text="submitting ? 'Verifying...' : 'Continue'"></span>
                    <svg x-show="!submitting" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                    </svg>
                </button>

            </form>

            <!-- Back to Forgot Password -->
            <div class="mt-8 text-center">
                <a href="<?= url('/forgot-password') ?>" class="inline-flex items-center gap-1.5 text-[14px] font-medium transition-colors hover:text-primary-hover" style="color: #4F47E6;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                    </svg>
                    Back to Forgot Password
                </a>
            </div>

        </div>
        <!-- /Card -->

        <!-- Footer -->
        <div class="flex-shrink-0 flex justify-between items-center text-[12px] text-[#464555] font-medium uppercase tracking-wider pt-4 mt-auto">
            <span>&copy; <span id="copyright-year-verify">2026</span> INTERNCONNECT.</span>
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
                Verify your<br>
                identity to<br>
                protect your <span class="font-serif italic font-light" style="color: rgba(255,255,255,0.9);">account.</span>
            </h2>

            <!-- Description -->
            <p class="mb-12 max-w-md" style="font-size: 18px; line-height: 28px; color: rgba(255,255,255,0.7);">
                Enter the six-digit verification code sent to your email to verify your access.
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

    <!-- ═══════════════════════════════════════════════════ -->
    <!-- Verification Modals (Overlay backdrop)             -->
    <!-- ═══════════════════════════════════════════════════ -->
    
    <!-- Success Modal -->
    <div x-show="show_success_modal" 
         @click.self="window.location.href = '<?= url('/reset-password') ?>'"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-sm shadow-xl"
         x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" style="display:none;">
        
        <div class="bg-white rounded-3xl border border-brandBorder max-w-md w-full p-8 shadow-2xl flex flex-col items-center text-center space-y-6 relative"
             x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
            
            <!-- Cancellation Close Button (Proceeds immediately to reset-password) -->
            <button @click="window.location.href = '<?= url('/reset-password') ?>'" type="button" class="text-gray-400 hover:text-gray-600" style="position: absolute; right: 16px; top: 16px;">
                <iconify-icon icon="lucide:x" class="text-xl"></iconify-icon>
            </button>
            
            <div class="modal-wlc-badge">
                <svg fill="none" viewBox="0 0 24 24" stroke="#4F47E6" stroke-width="2.8" style="width: 26px; height: 26px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
            </div>

            <div class="space-y-2">
                <h4 class="text-xl font-bold text-[#191C1D]">Email verified!</h4>
                <p class="text-sm text-secondary-text">
                    Your email <span class="font-semibold text-[#191C1D]" x-text="email"></span> has been verified. Let's set up your new password next.
                </p>
            </div>

            <button type="button" @click="window.location.href = '<?= url('/reset-password') ?>'"
                    class="w-full bg-primary text-white py-3 rounded-lg font-semibold hover:bg-primary-hover transition-colors flex items-center justify-center gap-2 shadow-sm">
                <!-- Premium animated twotone loop loading spinner -->
                <iconify-icon icon="line-md:loading-twotone-loop" class="text-xl"></iconify-icon>
                <span>Processing</span>
            </button>

            <!-- Autoredirect Progress Bar Indicator -->
            <div class="w-full space-y-2">
                <span class="text-[11px] text-gray-400">Continuing automatically in 5 seconds...</span>
                <div class="w-full h-1 bg-gray-100 rounded-full overflow-hidden">
                    <div class="h-full bg-primary transition-all duration-75" :style="'width: ' + countdown_progress + '%'"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Failure Modal -->
    <div x-show="show_failure_modal" 
         @click.self="show_failure_modal = false"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-sm shadow-xl"
         x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" style="display:none;">
        
        <div class="bg-white rounded-3xl border border-brandBorder max-w-md w-full p-8 shadow-2xl flex flex-col items-center text-center space-y-6 relative"
             x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
            
            <!-- Cancellation Close Button -->
            <button @click="show_failure_modal = false" type="button" class="text-gray-400 hover:text-gray-600" style="position: absolute; right: 16px; top: 16px;">
                <iconify-icon icon="lucide:x" class="text-xl"></iconify-icon>
            </button>
            
            <div class="modal-wlc-badge-fail">
                <svg fill="none" viewBox="0 0 24 24" stroke="#EF4444" stroke-width="3" style="width: 24px; height: 24px;">
                    <path class="modal-check-animated" stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </div>

            <div class="space-y-2">
                <h4 class="text-xl font-bold text-[#191C1D]">Email verification failed!</h4>
                <p class="text-sm text-secondary-text">
                    We couldn't verify <span class="font-semibold text-[#191C1D]" x-text="email"></span>. Check the code in your inbox and try again, or request a new one.
                </p>
            </div>

            <button type="button" @click="show_failure_modal = false"
                    class="w-full bg-primary text-white py-3 rounded-lg font-semibold hover:bg-primary-hover transition-colors flex items-center justify-center gap-1.5 shadow-sm">
                <iconify-icon icon="lucide:arrow-left"></iconify-icon>
                <span>Go Back</span>
            </button>
        </div>
    </div>

</section>

<!-- Alpine.js functions for countdown timer and hidden input code handler -->
<script>
    function verifyCodeWizard() {
        return {
            verification_input: '',
            submitting: false,
            show_success_modal: false,
            show_failure_modal: false,
            countdown_progress: 100,
            email: '<?= htmlspecialchars($email) ?>',
            resend_cooldown: 55,
            countdown_timer_id: null,
            server_errors: [],

            init() {
                // Start countdown
                this.countdown_timer_id = setInterval(() => {
                    if (this.resend_cooldown > 0) {
                        this.resend_cooldown--;
                    }
                }, 1000);
                
                // Focus on load
                this.$nextTick(() => {
                    if (this.$refs.verificationInput) this.$refs.verificationInput.focus();
                });
            },

            clearTimers() {
                if (this.countdown_timer_id) {
                    clearInterval(this.countdown_timer_id);
                    this.countdown_timer_id = null;
                }
            },

            handleVerificationInput() {
                // Keep numeric only and max 6 digits
                this.verification_input = this.verification_input.replace(/[^0-9]/g, '').substring(0, 6);
                document.getElementById('verification-code').value = this.verification_input;
                
                // Auto submit when all 6 digits are typed
                if (this.verification_input.length === 6) {
                    this.submitCode();
                }
            },

            formatTime(secs) {
                const minutes = Math.floor(secs / 60);
                const seconds = secs % 60;
                return `${minutes}:${seconds.toString().padStart(2, '0')}`;
            },

            resend() {
                this.resend_cooldown = 55;
                this.verification_input = '';
                this.server_errors = [];
                document.getElementById('verification-code').value = '';
                this.$nextTick(() => {
                    if (this.$refs.verificationInput) this.$refs.verificationInput.focus();
                });
            },

            submitCode() {
                this.submitting = true;
                this.server_errors = [];
                setTimeout(() => {
                    this.submitting = false;
                    // Any entered code proceeds successfully for UI prototype demonstration
                    this.show_success_modal = true;
                    this.startSuccessModalRedirect();
                }, 600);
            },

            startSuccessModalRedirect() {
                this.countdown_progress = 100;
                const duration = 5000; // 5s
                const interval = 10;
                let elapsed = 0;
                const timer = setInterval(() => {
                    elapsed += interval;
                    this.countdown_progress = 100 - (elapsed / duration) * 100;
                    if (elapsed >= duration) {
                        clearInterval(timer);
                        this.show_success_modal = false;
                        window.location.href = '<?= url('/reset-password') ?>';
                    }
                }, interval);
            }
        };
    }
</script>
<script>
    const copyrightYearVerify = document.getElementById('copyright-year-verify');
    if (copyrightYearVerify) {
        copyrightYearVerify.textContent = new Date().getFullYear();
    }
</script>
