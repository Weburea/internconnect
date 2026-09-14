<?php

declare(strict_types=1);

/**
 * View: Employer Onboarding Wizard — Steps 2 through 7
 * Layout: auth
 */
?>
<!-- Load Iconify CDN -->
<script src="https://code.iconify.design/iconify-icon/2.1.0/iconify-icon.min.js"></script>

<!-- Register styles -->
<link rel="stylesheet" href="<?= asset('assets/css/register.css') ?>">

<div x-data="employerOnboardingWizard()" x-init="init()" class="w-full min-h-screen bg-[#F8F9FA] text-[#191C1D] flex flex-col justify-between py-8 select-none" style="font-family: 'Inter', sans-serif;">
    
    <!-- Outer Margin constraint for consistent page width alignment -->
    <div class="w-full max-w-7xl mx-auto px-6 md:px-12 flex-grow flex flex-col justify-between">
        
        <!-- ── Header ── -->
        <header class="w-full flex items-center justify-between mb-8 py-4">
            <a href="<?= url('/') ?>">
                <img src="<?= asset('assets/images/logos/Logo_dark_dx00tg.svg') ?>" alt="InternConnect" class="h-[18px] md:h-[22px] w-auto">
            </a>
        </header>

        <!-- ── Main Content Container ── -->
        <main class="flex-grow flex flex-col md:flex-row justify-between gap-12 py-4 custom-main">
            
            <!-- Left Side: Marketing Content (Hidden on mobile/tablet viewports < 768px, visible on desktop screens >= 768px) -->
            <div class="custom-sidebar flex-col space-y-6" x-show="step <= 7">
                
                <!-- Step 2 Marketing -->
                <div x-show="step === 2" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-6">
                    <div class="inline-flex items-center gap-2 border border-primary/20 rounded-full px-4 py-1.5 bg-primary/5">
                        <span class="text-[12px] font-bold tracking-wider text-primary uppercase">Security</span>
                    </div>
                    <h2 class="text-[40px] lg:text-[48px] leading-[44px] lg:leading-[50px] font-bold tracking-tight text-[#191C1D]">
                        Find your next great <span class="text-primary">intern, faster.</span>
                    </h2>
                    <p class="text-[16px] leading-[26px] text-secondary-text">
                        Connect with thousands of pre-vetted students from top universities ready to contribute to your team.
                    </p>
                    <div class="border-t border-[#E2E8F0] pt-6"></div>
                    <ul class="space-y-4">
                        <li class="flex items-start gap-3 text-[14px] text-secondary-text text-left">
                            <div class="w-10 h-10 rounded-full bg-primary-light flex items-center justify-center text-primary flex-shrink-0 mt-0.5">
                                <iconify-icon icon="lucide:shield-check" class="text-lg"></iconify-icon>
                            </div>
                            <div>
                                <strong class="text-[#191C1D] block">Free to post first internship</strong>
                                <span class="text-[13px]">Test the waters risk-free.</span>
                            </div>
                        </li>
                        <li class="flex items-start gap-3 text-[14px] text-secondary-text text-left">
                            <div class="w-10 h-10 rounded-full bg-primary-light flex items-center justify-center text-primary flex-shrink-0 mt-0.5">
                                <iconify-icon icon="lucide:users" class="text-lg"></iconify-icon>
                            </div>
                            <div>
                                <strong class="text-[#191C1D] block">12,000+ active students</strong>
                                <span class="text-[13px]">A diverse pool of fresh talents.</span>
                            </div>
                        </li>
                        <li class="flex items-start gap-3 text-[14px] text-secondary-text text-left">
                            <div class="w-10 h-10 rounded-full bg-primary-light flex items-center justify-center text-primary flex-shrink-0 mt-0.5">
                                <iconify-icon icon="lucide:clock" class="text-lg"></iconify-icon>
                            </div>
                            <div>
                                <strong class="text-[#191C1D] block">48-hours response guarantee</strong>
                                <span class="text-[13px]">Quick turnaround on applications.</span>
                            </div>
                        </li>
                    </ul>
                </div>

                <!-- Step 3 Marketing -->
                <div x-show="step === 3" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-6">
                    <div class="inline-flex items-center gap-2 border border-primary/20 rounded-full px-4 py-1.5 bg-primary/5">
                        <span class="text-[12px] font-bold tracking-wider text-primary uppercase">Email Verification</span>
                    </div>
                    <h2 class="text-[40px] lg:text-[48px] leading-[44px] lg:leading-[50px] font-bold tracking-tight text-[#191C1D]">
                        We sent you a code, <span class="text-primary">check your inbox.</span>
                    </h2>
                    <p class="text-[16px] leading-[26px] text-secondary-text">
                        A 6-digit code is on its way to <strong class="text-[#191C1D]" x-text="email || 'your email'"></strong>. It expires in 10 minutes, enter it on the right to confirm your email.
                    </p>
                    <div class="border-t border-[#E2E8F0] pt-6"></div>
                    <ul class="space-y-4">
                        <li class="flex items-start gap-3 text-[14px] text-secondary-text text-left">
                            <div class="w-10 h-10 rounded-full bg-primary-light flex items-center justify-center text-primary flex-shrink-0 mt-0.5">
                                <iconify-icon icon="lucide:mail" class="text-lg"></iconify-icon>
                            </div>
                            <div>
                                <strong class="text-[#191C1D] block">Sent from <span x-text="email_sender"></span></strong>
                                <span class="text-[13px]">Check spam if missing.</span>
                            </div>
                        </li>
                        <li class="flex items-start gap-3 text-[14px] text-secondary-text text-left">
                            <div class="w-10 h-10 rounded-full bg-primary-light flex items-center justify-center text-primary flex-shrink-0 mt-0.5">
                                <iconify-icon icon="lucide:clock" class="text-lg"></iconify-icon>
                            </div>
                            <div>
                                <strong class="text-[#191C1D] block">Code expires in 5 minutes</strong>
                            </div>
                        </li>
                        <li class="flex items-start gap-3 text-[14px] text-secondary-text text-left">
                            <div class="w-10 h-10 rounded-full bg-primary-light flex items-center justify-center text-primary flex-shrink-0 mt-0.5">
                                <iconify-icon icon="lucide:refresh-cw" class="text-lg"></iconify-icon>
                            </div>
                            <div>
                                <strong class="text-[#191C1D] block">Didn't get it?</strong>
                                <span class="text-[13px]">You can request a new one after cooldown.</span>
                            </div>
                        </li>
                    </ul>
                </div>

                <!-- Step 4 Marketing -->
                <div x-show="step === 4" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-6">
                    <div class="inline-flex items-center gap-2 border border-primary/20 rounded-full px-4 py-1.5 bg-primary/5">
                        <span class="text-[12px] font-bold tracking-wider text-primary uppercase">Verification</span>
                    </div>
                    <h2 class="text-[40px] lg:text-[48px] leading-[44px] lg:leading-[50px] font-bold tracking-tight text-[#191C1D]">
                        Let's verify your <span class="text-primary">company</span>
                    </h2>
                    <p class="text-[16px] leading-[26px] text-secondary-text">
                        Verification builds trust with applicants and unlocks premium platform features. Verified employers attract top talent faster.
                    </p>
                    <div class="border-t border-[#E2E8F0] pt-6"></div>
                    <ul class="space-y-4">
                        <li class="flex items-start gap-3 text-[14px] text-secondary-text text-left">
                            <div class="w-10 h-10 rounded-full bg-primary-light flex items-center justify-center text-primary flex-shrink-0 mt-0.5">
                                <iconify-icon icon="lucide:trending-up" class="text-lg"></iconify-icon>
                            </div>
                            <div>
                                <strong class="text-[#191C1D] block">3x more applicants</strong>
                            </div>
                        </li>
                        <li class="flex items-start gap-3 text-[14px] text-secondary-text text-left">
                            <div class="w-10 h-10 rounded-full bg-primary-light flex items-center justify-center text-primary flex-shrink-0 mt-0.5">
                                <iconify-icon icon="lucide:clock" class="text-lg"></iconify-icon>
                            </div>
                            <div>
                                <strong class="text-[#191C1D] block">24h verification</strong>
                            </div>
                        </li>
                        <li class="flex items-start gap-3 text-[14px] text-secondary-text text-left">
                            <div class="w-10 h-10 rounded-full bg-primary-light flex items-center justify-center text-primary flex-shrink-0 mt-0.5">
                                <iconify-icon icon="lucide:lock" class="text-lg"></iconify-icon>
                            </div>
                            <div>
                                <strong class="text-[#191C1D] block">Data privacy</strong>
                            </div>
                        </li>
                    </ul>
                </div>

                <!-- Step 5 Marketing -->
                <div x-show="step === 5" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-6">
                    <div class="inline-flex items-center gap-2 border border-primary/20 rounded-full px-4 py-1.5 bg-primary/5">
                        <span class="text-[12px] font-bold tracking-wider text-primary uppercase">Company Profile</span>
                    </div>
                    <h2 class="text-[40px] lg:text-[48px] leading-[44px] lg:leading-[50px] font-bold tracking-tight text-[#191C1D]">
                        Tell students <span class="text-primary">who you are.</span>
                    </h2>
                    <p class="text-[16px] leading-[26px] text-secondary-text">
                        A complete profile builds trust and helps you attract the right candidates to your organization.
                    </p>
                    <div class="border-t border-[#E2E8F0] pt-6"></div>
                    <ul class="space-y-4">
                        <li class="flex items-start gap-3 text-[14px] text-secondary-text text-left">
                            <div class="w-10 h-10 rounded-full bg-primary-light flex items-center justify-center text-primary flex-shrink-0 mt-0.5">
                                <iconify-icon icon="lucide:eye" class="text-lg"></iconify-icon>
                            </div>
                            <div>
                                <strong class="text-[#191C1D] block">Logos get 2x views</strong>
                                <span class="text-[13px]">Profiles with logos are clicked significantly more.</span>
                            </div>
                        </li>
                        <li class="flex items-start gap-3 text-[14px] text-secondary-text text-left">
                            <div class="w-10 h-10 rounded-full bg-primary-light flex items-center justify-center text-primary flex-shrink-0 mt-0.5">
                                <iconify-icon icon="lucide:heart" class="text-lg"></iconify-icon>
                            </div>
                            <div>
                                <strong class="text-[#191C1D] block">Add culture and mission</strong>
                                <span class="text-[13px]">Candidates want to know what you stand for.</span>
                            </div>
                        </li>
                        <li class="flex items-start gap-3 text-[14px] text-secondary-text text-left">
                            <div class="w-10 h-10 rounded-full bg-primary-light flex items-center justify-center text-primary flex-shrink-0 mt-0.5">
                                <iconify-icon icon="lucide:edit" class="text-lg"></iconify-icon>
                            </div>
                            <div>
                                <strong class="text-[#191C1D] block">Edit anytime</strong>
                                <span class="text-[13px]">You can always update these details later.</span>
                            </div>
                        </li>
                    </ul>
                </div>

                <!-- Step 6 Marketing -->
                <div x-show="step === 6" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-6">
                    <div class="inline-flex items-center gap-2 border border-primary/20 rounded-full px-4 py-1.5 bg-primary/5">
                        <span class="text-[12px] font-bold tracking-wider text-primary uppercase">Hiring</span>
                    </div>
                    <h2 class="text-[40px] lg:text-[48px] leading-[44px] lg:leading-[50px] font-bold tracking-tight text-[#191C1D]">
                        What kind of talent are you <span class="text-primary">looking for?</span>
                    </h2>
                    <p class="text-[16px] leading-[26px] text-secondary-text">
                        Tell us about your upcoming opportunities. We'll use this to personalize your dashboard and match you with the most relevant student profiles.
                    </p>
                    <!-- Pro Tip Box -->
                    <div class="flex gap-3 bg-primary/5 border border-primary/10 rounded-2xl p-5 text-[13.5px] text-primary">
                        <iconify-icon icon="lucide:lightbulb" class="text-xl flex-shrink-0 mt-0.5"></iconify-icon>
                        <div class="text-left space-y-1">
                            <strong class="block font-bold">Pro Tip</strong>
                            <span>Selecting specific roles helps us tailor candidates to your exact needs, increasing match success by up to 40%.</span>
                        </div>
                    </div>
                </div>

                <!-- Step 7 Marketing -->
                <div x-show="step === 7" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-6">
                    <div class="inline-flex items-center gap-2 border border-primary/20 rounded-full px-4 py-1.5 bg-primary/5">
                        <span class="text-[12px] font-bold tracking-wider text-primary uppercase">Preview</span>
                    </div>
                    <h2 class="text-[40px] lg:text-[48px] leading-[44px] lg:leading-[50px] font-bold tracking-tight text-[#191C1D]">
                        Here's how students <span class="text-primary">will see you.</span>
                    </h2>
                    <p class="text-[16px] leading-[26px] text-secondary-text">
                        Review your public profile. This is the impression you'll make on prospective interns exploring opportunities at your company. Ensure all details are accurate and compelling.
                    </p>
                    <!-- Pro Tip Box -->
                    <div class="flex gap-3 bg-primary/5 border border-primary/10 rounded-2xl p-5 text-[13.5px] text-primary">
                        <iconify-icon icon="lucide:lightbulb" class="text-xl flex-shrink-0 mt-0.5"></iconify-icon>
                        <div class="text-left space-y-1">
                            <strong class="block font-bold">Pro Tip</strong>
                            <span>Profiles with a clear, engaging description and an up-to-date logo attract 40% more internship applications.</span>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Right Side: Step Progress & Form Card -->
            <div class="custom-card-container flex flex-col justify-center min-h-[500px]" x-show="step <= 7">
                
                <!-- Step Progress Indicator -->
                <div class="w-full max-w-[460px] flex flex-col items-end mb-4 custom-card-align">
                    <span class="text-[13px] font-bold text-secondary-text uppercase tracking-wider">
                        Step <span x-text="step"></span> of 7
                    </span>
                    <div class="flex items-center gap-1.5 mt-2">
                        <template x-for="i in 7" :key="i">
                            <div class="h-1.5 w-10 rounded-full transition-all duration-300"
                                 :class="i <= step ? 'bg-primary' : 'bg-gray-200'"></div>
                        </template>
                    </div>
                </div>

                <!-- White Card Wrapper -->
                <div class="w-full max-w-[460px] bg-white rounded-3xl border border-brandBorder px-8 py-10 shadow-sm flex flex-col relative z-10 custom-card-align">

                    <!-- Global Error Display -->
                    <template x-if="server_errors.length > 0">
                        <div class="mb-5 p-4 bg-red-50 border border-red-200 rounded-xl text-red-700 text-[13px] flex flex-col gap-1">
                            <template x-for="err in server_errors" :key="err">
                                <span class="font-medium" x-text="err"></span>
                            </template>
                        </div>
                    </template>

                    <!-- ─────────────────────────────────────────────── -->
                    <!-- Step 2: Create Account                          -->
                    <!-- ─────────────────────────────────────────────── -->
                    <div x-show="step === 2" class="space-y-6">
                        <h3 class="text-[24px] font-bold text-[#191C1D] tracking-tight text-left">Create your account</h3>

                        <!-- Google OAuth -->
                        <button @click="continueWithGoogle()" class="w-full flex items-center justify-center gap-3 border border-brandBorder rounded-lg py-2.5 text-[14px] font-semibold text-[#191C1D] hover:bg-gray-50 transition-colors shadow-sm">
                            <img src="https://api.iconify.design/flat-color-icons:google.svg" class="w-5 h-5" alt="Google">
                            <span>Sign up with Google</span>
                        </button>

                        <!-- Inline OAuth error if simulated cancel/failure -->
                        <div x-show="oauth_error" class="text-xs text-red-500 font-semibold text-left" style="display:none;" x-text="oauth_error"></div>

                        <!-- Divider -->
                        <div class="flex items-center py-2">
                            <div class="flex-grow border-t border-brandBorder"></div>
                            <span class="px-4 text-[11px] font-bold text-secondary-text tracking-widest uppercase">Or use email</span>
                            <div class="flex-grow border-t border-brandBorder"></div>
                        </div>

                        <!-- Fields -->
                        <div class="space-y-4 text-left">
                            <div>
                                <label for="company-name" class="block text-[12px] font-bold text-[#191C1D] uppercase tracking-wider mb-1.5">Company Name</label>
                                <input type="text" id="company-name" x-model="company_name" placeholder="Flux technology" 
                                       class="w-full px-4 py-2.5 rounded-lg border border-brandBorder focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all text-sm text-[#191C1D] placeholder-gray-400">
                            </div>

                            <div>
                                <label for="email" class="block text-[12px] font-bold text-[#191C1D] uppercase tracking-wider mb-1.5">Work Email</label>
                                <input type="email" id="email" x-model="email" placeholder="you@company.com" 
                                       class="w-full px-4 py-2.5 rounded-lg border border-brandBorder focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all text-sm text-[#191C1D] placeholder-gray-400">
                            </div>

                            <!-- Password -->
                            <div>
                                <label for="reg-password" class="block text-[12px] font-bold text-[#191C1D] uppercase tracking-wider mb-1.5">Password</label>
                                <div class="relative">
                                    <input :type="password_visible ? 'text' : 'password'" id="reg-password" x-model="password" placeholder="••••••••" 
                                           class="w-full px-4 pr-10 py-2.5 rounded-lg border border-brandBorder focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all text-sm tracking-widest text-[#191C1D] placeholder-gray-400">
                                    <button @click="password_visible = !password_visible" type="button" 
                                            style="position: absolute; right: 16px; top: 50%; transform: translateY(-50%); cursor: pointer;"
                                            class="text-gray-400 hover:text-gray-600 flex items-center border-none bg-transparent outline-none">
                                        <iconify-icon :icon="password_visible ? 'lucide:eye-off' : 'lucide:eye'" class="text-lg"></iconify-icon>
                                    </button>
                                </div>
                                <!-- 5 Stacked Lines Strength Indicator -->
                                <div class="mt-2.5 space-y-1">
                                    <div class="h-[2px] transition-all duration-300" :class="password.length >= 2 ? 'bg-[#22C55E]' : 'bg-gray-200'"></div>
                                    <div class="h-[2px] transition-all duration-300" :class="password.length >= 4 ? 'bg-[#22C55E]' : 'bg-gray-200'"></div>
                                    <div class="h-[2px] transition-all duration-300" :class="password.length >= 6 ? 'bg-[#22C55E]' : 'bg-gray-200'"></div>
                                    <div class="h-[2px] transition-all duration-300" :class="password.length >= 8 ? 'bg-[#22C55E]' : 'bg-gray-200'"></div>
                                    <div class="h-[2px] transition-all duration-300" :class="password.length >= 10 ? 'bg-[#22C55E]' : 'bg-gray-200'"></div>
                                    <span class="text-[11px] font-semibold text-secondary-text block pt-0.5" x-text="getPasswordStrengthText(password, 'Enter a password')"></span>
                                </div>
                            </div>

                            <!-- Confirm Password -->
                            <div>
                                <label for="confirm-password" class="block text-[12px] font-bold text-[#191C1D] uppercase tracking-wider mb-1.5">Confirm Password</label>
                                <div class="relative">
                                    <input :type="confirm_password_visible ? 'text' : 'password'" id="confirm-password" x-model="confirm_password" placeholder="••••••••" 
                                           class="w-full px-4 pr-10 py-2.5 rounded-lg border border-brandBorder focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all text-sm tracking-widest text-[#191C1D] placeholder-gray-400">
                                    <button @click="confirm_password_visible = !confirm_password_visible" type="button" 
                                            style="position: absolute; right: 16px; top: 50%; transform: translateY(-50%); cursor: pointer;"
                                            class="text-gray-400 hover:text-gray-600 flex items-center border-none bg-transparent outline-none">
                                        <iconify-icon :icon="confirm_password_visible ? 'lucide:eye-off' : 'lucide:eye'" class="text-lg"></iconify-icon>
                                    </button>
                                </div>
                                <!-- 5 Stacked Lines Confirm Indicator -->
                                <div class="mt-2.5 space-y-1">
                                    <div class="h-[2px] transition-all duration-300" :class="confirm_password.length >= 2 ? 'bg-[#22C55E]' : 'bg-gray-200'"></div>
                                    <div class="h-[2px] transition-all duration-300" :class="confirm_password.length >= 4 ? 'bg-[#22C55E]' : 'bg-gray-200'"></div>
                                    <div class="h-[2px] transition-all duration-300" :class="confirm_password.length >= 6 ? 'bg-[#22C55E]' : 'bg-gray-200'"></div>
                                    <div class="h-[2px] transition-all duration-300" :class="confirm_password.length >= 8 ? 'bg-[#22C55E]' : 'bg-gray-200'"></div>
                                    <div class="h-[2px] transition-all duration-300" :class="confirm_password.length >= 10 ? 'bg-[#22C55E]' : 'bg-gray-200'"></div>
                                    <span class="text-[11px] font-semibold text-secondary-text block pt-0.5" x-text="getPasswordStrengthText(confirm_password, 'Confirm your password')"></span>
                                </div>
                            </div>

                            <!-- Consent -->
                            <label class="flex items-start gap-3 cursor-pointer pt-2">
                                <input type="checkbox" x-model="terms" class="mt-1 rounded text-primary focus:ring-primary border-brandBorder w-4 h-4">
                                <span class="text-[13px] leading-[18px] text-secondary-text">
                                    I agree to the <a href="#" class="text-primary font-semibold hover:underline">Terms of Service</a> and <a href="#" class="text-primary font-semibold hover:underline">Privacy Policy</a>.
                                </span>
                            </label>
                        </div>

                        <!-- CTA Row -->
                        <div class="flex justify-between items-center pt-4 border-t border-brandBorder">
                            <a href="<?= url('/register') ?>" class="text-sm font-semibold text-secondary-text hover:text-[#191C1D] transition-colors flex items-center gap-1.5">
                                <iconify-icon icon="lucide:arrow-left"></iconify-icon>
                                <span>Back</span>
                            </a>
                            
                            <button @click="submitStep2()" 
                                    :disabled="!company_name || !email || !password || !confirm_password || !terms || submitting"
                                    class="bg-primary text-white px-6 py-3 rounded-lg font-semibold hover:bg-primary-hover disabled:opacity-50 disabled:cursor-not-allowed transition-all shadow-sm flex items-center gap-1.5">
                                <span>Verify Email</span>
                                <iconify-icon icon="lucide:arrow-right"></iconify-icon>
                            </button>
                        </div>
                    </div>

                    <!-- ─────────────────────────────────────────────── -->
                    <!-- Step 3: Email Verification                      -->
                    <!-- ─────────────────────────────────────────────── -->
                    <div x-show="step === 3" class="space-y-6">
                        <h3 class="text-[24px] font-bold text-[#191C1D] tracking-tight text-left">Enter verification code</h3>
                        
                        <p class="text-[13.5px] text-secondary-text leading-[20px] text-left">
                            We've sent a 6-digit verification code to <span class="font-semibold text-[#191C1D]" x-text="email"></span>. 
                        </p>

                        <!-- OTP boxes -->
                        <div class="relative flex justify-between gap-2 max-w-[360px] mx-auto py-2 cursor-pointer" @click="$refs.verificationInput.focus()">
                            <input type="text" maxlength="6" x-model="verification_input" x-ref="verificationInput"
                                   @input="handleVerificationInput()"
                                   class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" 
                                   pattern="[0-9]*" inputmode="numeric">
                            
                            <template x-for="i in 6" :key="i">
                                <div class="w-12 h-14 border rounded-lg flex items-center justify-center text-xl font-bold transition-all duration-200 text-[#191C1D] bg-white"
                                     :class="verification_input.length === i - 1 ? 'border-primary ring-2 ring-primary/20' : 'border-[#E2E8F0]'">
                                     <span x-text="verification_input[i - 1] || ''"></span>
                                </div>
                            </template>
                        </div>

                        <!-- Timer / Resend Code -->
                        <div class="text-center text-sm font-semibold">
                            <span class="text-secondary-text" x-show="resend_cooldown > 0">
                                Resend code in <strong class="text-primary font-mono" x-text="formatCooldown(resend_cooldown)"></strong>
                            </span>
                            <button type="button" @click="resendCode()" x-show="resend_cooldown === 0" class="text-primary hover:underline">
                                Resend verification code
                            </button>
                        </div>

                        <!-- CTA Row -->
                        <div class="flex justify-between items-center pt-4 border-t border-brandBorder">
                            <button @click="goBackToStep2()" class="text-sm font-semibold text-secondary-text hover:text-[#191C1D] transition-colors flex items-center gap-1.5">
                                <iconify-icon icon="lucide:arrow-left"></iconify-icon>
                                <span>Back</span>
                            </button>
                            
                            <button @click="submitStep3()" 
                                    :disabled="verification_input.length < 6 || submitting"
                                    class="bg-primary text-white px-6 py-3 rounded-lg font-semibold hover:bg-primary-hover disabled:opacity-50 disabled:cursor-not-allowed transition-all shadow-sm flex items-center gap-1.5">
                                <span x-text="submitting ? 'Verifying...' : 'Continue'"></span>
                                <iconify-icon icon="lucide:arrow-right" x-show="!submitting"></iconify-icon>
                            </button>
                        </div>
                    </div>

                    <!-- ─────────────────────────────────────────────── -->
                    <!-- Step 4: Organization Verification              -->
                    <!-- ─────────────────────────────────────────────── -->
                    <div x-show="step === 4" class="space-y-6">
                        <h3 class="text-[24px] font-bold text-[#191C1D] tracking-tight text-left">Verify your organization</h3>
                        <p class="text-[13px] text-secondary-text leading-[18px] text-left">Provide details to expedite the verification process.</p>

                        <!-- Verified Email Alert Box -->
                        <div class="flex items-center gap-3 bg-green-50 border border-green-200 rounded-2xl p-4 text-[13px] text-green-700 text-left">
                            <iconify-icon icon="lucide:check-circle" class="text-lg flex-shrink-0 text-green-600"></iconify-icon>
                            <div>
                                <span class="font-bold block">Email verified</span>
                                <span class="text-xs text-gray-500" x-text="email ? '@' + email.split('@').pop() : '@fluxtech.io'"></span>
                            </div>
                        </div>

                        <div class="space-y-4 text-left">
                            <!-- CAC Number -->
                            <div>
                                <label for="cac-number" class="block text-[12px] font-bold text-[#191C1D] uppercase tracking-wider mb-1.5">
                                    CAC Number (Registration Number) <span x-text="isPersonalEmail(email) ? '(Required)' : '(Optional)'"></span>
                                </label>
                                <div class="relative flex items-center">
                                    <div class="absolute left-3 text-gray-400 pointer-events-none flex items-center">
                                        <iconify-icon icon="lucide:building" class="text-lg"></iconify-icon>
                                    </div>
                                    <input type="text" id="cac-number" x-model="cac_number" placeholder="Enter company registration number" 
                                           class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-brandBorder focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all text-sm text-[#191C1D] placeholder-gray-400">
                                </div>
                            </div>

                            <!-- Document Upload -->
                            <div>
                                <label class="block text-[12px] font-bold text-[#191C1D] uppercase tracking-wider mb-1.5">
                                    Business Registration Document (Required)
                                </label>
                                
                                <div @dragover.prevent="is_dragged_business_doc = true"
                                     @dragleave.prevent="is_dragged_business_doc = false"
                                     @drop.prevent="handleBusinessDocDrop($event)"
                                     @click="$refs.businessDocInput.click()"
                                     class="border-2 border-dashed rounded-2xl p-6 text-center cursor-pointer transition-all duration-200 flex flex-col items-center justify-center gap-2 select-none"
                                     :class="is_dragged_business_doc ? 'border-primary bg-primary/5' : 'border-brandBorder bg-white hover:border-gray-300'">
                                    
                                    <input type="file" x-ref="businessDocInput" accept=".pdf,.png,.jpg,.jpeg" class="hidden" @change="handleBusinessDocSelect($event)">
                                    
                                    <div class="w-12 h-12 rounded-full bg-primary/5 flex items-center justify-center text-primary">
                                        <iconify-icon icon="lucide:cloud-upload" class="text-2xl"></iconify-icon>
                                    </div>
                                    <div class="text-sm font-semibold text-primary">
                                        Click to upload <span class="text-[#191C1D]">or drag and drop</span>
                                    </div>
                                    <div class="text-[12px] text-secondary-text">PDF, JPG or PNG (max. 10MB)</div>
                                </div>

                                <!-- Business Doc Preview Card -->
                                <div x-show="business_doc_uploaded" class="mt-4" style="display: none;">
                                    <div class="border border-gray-200 rounded-xl p-4 flex gap-4 items-start justify-between bg-[#F8FAFC] shadow-sm">
                                        <div class="flex items-start gap-3 min-w-0 flex-1">
                                            <div class="h-12 w-9 bg-white border border-gray-300 rounded-lg shadow-sm flex-shrink-0 relative overflow-hidden flex flex-col p-1 justify-between">
                                                <div class="absolute right-0 top-0 h-3 w-3.5 bg-primary text-white font-bold text-[6px] flex items-center justify-center rounded-bl uppercase" x-text="business_doc_name.split('.').pop()">PDF</div>
                                                <div class="space-y-1 mt-2.5">
                                                    <div class="h-[1.5px] bg-gray-300 w-full rounded"></div>
                                                    <div class="h-[1.5px] bg-gray-300 w-5/6 rounded"></div>
                                                </div>
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <p class="text-sm font-bold text-primary-text mb-0.5 break-all truncate" x-text="business_doc_name"></p>
                                                <p class="text-xs text-gray-500" x-text="business_doc_size"></p>
                                            </div>
                                        </div>
                                        <button type="button" @click="removeBusinessDoc()" class="text-red-500 hover:text-red-700">
                                            <iconify-icon icon="lucide:trash-2" class="text-lg"></iconify-icon>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Info Box -->
                        <div class="flex gap-3 bg-primary/5 border border-primary/10 rounded-2xl p-4 text-[13px] text-primary">
                            <iconify-icon icon="lucide:info" class="text-lg flex-shrink-0 mt-0.5"></iconify-icon>
                            <span class="text-left">Browse immediately, badge appears after 24h once our team reviews your details.</span>
                        </div>

                        <!-- CTA Row -->
                        <div class="flex justify-between items-center pt-4 border-t border-brandBorder">
                            <button @click="signup_method === 'google' ? goBackToStep2() : step = 3" class="text-sm font-semibold text-secondary-text hover:text-[#191C1D] transition-colors flex items-center gap-1.5">
                                <iconify-icon icon="lucide:arrow-left"></iconify-icon>
                                <span>Back</span>
                            </button>
                            
                            <button @click="submitStep4()" 
                                    :disabled="submitting"
                                    class="bg-primary text-white px-6 py-3 rounded-lg font-semibold hover:bg-primary-hover transition-all shadow-sm flex items-center gap-1.5">
                                <span>Continue</span>
                                <iconify-icon icon="lucide:arrow-right"></iconify-icon>
                            </button>
                        </div>
                    </div>

                    <!-- ─────────────────────────────────────────────── -->
                    <!-- Step 5: Company Profile                         -->
                    <!-- ─────────────────────────────────────────────── -->
                    <div x-show="step === 5" class="space-y-6">
                        <h3 class="text-[24px] font-bold text-[#191C1D] tracking-tight text-left">Company details</h3>

                        <div class="space-y-4 text-left">
                            
                            <!-- Logo Upload -->
                            <div class="flex items-center gap-4">
                                <div @click="$refs.logoInput.click()" 
                                     class="w-16 h-16 rounded-full border border-brandBorder bg-gray-50 flex flex-col items-center justify-center cursor-pointer hover:bg-gray-100 flex-shrink-0 relative overflow-hidden">
                                    <template x-if="!logo_uploaded">
                                        <div class="flex flex-col items-center justify-center text-[10px] text-gray-500">
                                            <iconify-icon icon="lucide:image" class="text-lg"></iconify-icon>
                                            <span>Upload</span>
                                        </div>
                                    </template>
                                    <template x-if="logo_uploaded">
                                        <img :src="logo_url" class="w-full h-full object-cover">
                                    </template>
                                </div>
                                <input type="file" x-ref="logoInput" accept="image/*" class="hidden" @change="handleLogoSelect($event)">
                                <div class="text-left">
                                    <span class="text-xs font-bold block text-[#191C1D]">Company Logo</span>
                                    <span class="text-[11px] text-gray-500">High-res square image (PNG or JPG). Max 5MB.</span>
                                    <button type="button" x-show="logo_uploaded" @click.stop="removeLogo()" class="text-red-500 hover:text-red-700 text-[11px] font-bold block mt-1">Remove logo</button>
                                </div>
                            </div>

                            <!-- Fields Row -->
                            <div>
                                <label for="company-name-details" class="block text-[12px] font-bold text-[#191C1D] uppercase tracking-wider mb-1.5">Company Name</label>
                                <input type="text" id="company-name-details" x-model="company_name" placeholder="Flux Technologies" 
                                       class="w-full px-4 py-2.5 rounded-lg border border-brandBorder focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all text-sm text-[#191C1D]">
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="industry" class="block text-[12px] font-bold text-[#191C1D] uppercase tracking-wider mb-1.5">Industry</label>
                                    <input type="text" id="industry" x-model="industry" placeholder="Fintech" 
                                           class="w-full px-4 py-2.5 rounded-lg border border-brandBorder focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all text-sm text-[#191C1D]">
                                </div>
                                <div>
                                    <label for="company-size" class="block text-[12px] font-bold text-[#191C1D] uppercase tracking-wider mb-1.5">Company Size</label>
                                    <select id="company-size" x-model="company_size" 
                                            class="w-full px-4 py-2.5 rounded-lg border border-brandBorder focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all text-sm text-[#191C1D] bg-white">
                                        <option>1-10 employees</option>
                                        <option>11-50 employees</option>
                                        <option>51-200 employees</option>
                                        <option>201-500 employees</option>
                                        <option>500+ employees</option>
                                    </select>
                                </div>
                            </div>

                            <div>
                                <label for="website" class="block text-[12px] font-bold text-[#191C1D] uppercase tracking-wider mb-1.5">Website</label>
                                <input type="text" id="website" x-model="website" placeholder="fluxtech.io" 
                                       class="w-full px-4 py-2.5 rounded-lg border border-brandBorder focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all text-sm text-[#191C1D] placeholder-gray-400">
                            </div>

                            <div>
                                <label for="description" class="block text-[12px] font-bold text-[#191C1D] uppercase tracking-wider mb-1.5">Description</label>
                                <textarea id="description" x-model="description" rows="3" placeholder="Fintech startup based in Lagos focused on democratizing access to cross-border payments for small businesses."
                                          class="w-full px-4 py-2.5 rounded-lg border border-brandBorder focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all text-sm text-[#191C1D] placeholder-gray-400"></textarea>
                            </div>
                        </div>

                        <!-- CTA Row -->
                        <div class="flex justify-between items-center pt-4 border-t border-brandBorder">
                            <button @click="step = 4; server_errors = []" class="text-sm font-semibold text-secondary-text hover:text-[#191C1D] transition-colors flex items-center gap-1.5">
                                <iconify-icon icon="lucide:arrow-left"></iconify-icon>
                                <span>Back</span>
                            </button>
                            
                            <button @click="submitStep5()" 
                                    :disabled="!company_name || !industry || !website || !description || submitting"
                                    class="bg-primary text-white px-6 py-3 rounded-lg font-semibold hover:bg-primary-hover disabled:opacity-50 disabled:cursor-not-allowed transition-all shadow-sm flex items-center gap-1.5">
                                <span>Continue</span>
                                <iconify-icon icon="lucide:arrow-right"></iconify-icon>
                            </button>
                        </div>
                    </div>

                    <!-- ─────────────────────────────────────────────── -->
                    <!-- Step 6: Hiring Focus                           -->
                    <!-- ─────────────────────────────────────────────── -->
                    <div x-show="step === 6" class="space-y-6">
                        <h3 class="text-[24px] font-bold text-[#191C1D] tracking-tight text-left">Your hiring focus</h3>

                        <div class="space-y-4 text-left relative">
                            <!-- Target Departments pills -->
                            <div>
                                <label class="block text-[12px] font-bold text-[#191C1D] uppercase tracking-wider mb-1.5">Target Departments</label>
                                <div class="flex flex-wrap gap-2 py-1 items-center">
                                    <template x-for="dept in departments" :key="dept">
                                        <div class="inline-flex items-center gap-1.5 rounded-full bg-primary/10 text-primary text-xs font-semibold border border-primary/20" style="padding: 6px 14px;">
                                            <span x-text="dept"></span>
                                            <button type="button" @click="toggleDepartment(dept)" class="flex items-center text-primary/80 hover:text-primary">
                                                <iconify-icon icon="lucide:x" class="text-sm"></iconify-icon>
                                            </button>
                                        </div>
                                    </template>
                                    <button type="button" @click="show_dept_dropdown = !show_dept_dropdown" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-full border border-dashed border-gray-300 text-xs font-semibold text-secondary-text hover:text-[#191C1D] hover:border-gray-400">
                                        <iconify-icon icon="lucide:plus" class="text-sm"></iconify-icon>
                                        <span>Add Department</span>
                                    </button>
                                </div>

                                <!-- Add Department Dropdown List -->
                                <div x-show="show_dept_dropdown" @click.away="show_dept_dropdown = false"
                                     class="absolute left-0 mt-2 w-full max-w-[280px] bg-white border border-brandBorder rounded-2xl shadow-xl z-30 p-4 space-y-3" style="display:none;">
                                    <div class="relative flex items-center">
                                        <iconify-icon icon="lucide:search" class="absolute left-2.5 text-gray-400"></iconify-icon>
                                        <input type="text" x-model="search_query" placeholder="Search departments..." 
                                               class="w-full pl-8 pr-3 py-1.5 rounded-lg border border-brandBorder text-xs focus:ring-1 focus:ring-primary">
                                    </div>
                                    <div class="max-h-[160px] overflow-y-auto space-y-1.5">
                                        <template x-for="dept in filteredDepartments()" :key="dept">
                                            <label class="flex items-center gap-2 text-xs font-semibold text-[#191C1D] cursor-pointer hover:bg-gray-50 p-1.5 rounded-lg">
                                                <input type="checkbox" :checked="departments.includes(dept)" @change="toggleDepartment(dept)" class="rounded text-primary focus:ring-primary border-brandBorder w-3.5 h-3.5">
                                                <span x-text="dept"></span>
                                            </label>
                                        </template>
                                    </div>
                                    <div class="flex justify-between items-center border-t border-[#E2E8F0] pt-2 text-[11px]">
                                        <button type="button" @click="clearAllDepartments()" class="text-gray-500 hover:text-red-500">Clear all</button>
                                        <button type="button" @click="show_dept_dropdown = false" class="bg-primary text-white px-3 py-1 rounded-md font-bold hover:bg-primary-hover">Apply</button>
                                    </div>
                                </div>
                            </div>

                            <!-- Hiring Volume -->
                            <div>
                                <label for="expected-volume" class="block text-[12px] font-bold text-[#191C1D] uppercase tracking-wider mb-1.5">Expected Hiring Volume (Per Year)</label>
                                <select id="expected-volume" x-model="expected_volume" 
                                        class="w-full px-4 py-2.5 rounded-lg border border-brandBorder focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all text-sm text-[#191C1D] bg-white">
                                    <option>1 - 5 Interns</option>
                                    <option>6 - 15 Interns</option>
                                    <option>16 - 50 Interns</option>
                                    <option>51+ Interns</option>
                                </select>
                            </div>

                            <!-- Internship Type cards -->
                            <div>
                                <label class="block text-[12px] font-bold text-[#191C1D] uppercase tracking-wider mb-1.5">Primary Internship Type</label>
                                <div class="grid grid-cols-3 gap-3">
                                    <div @click="internship_type = 'Paid'" 
                                         class="border border-brandBorder rounded-xl p-3 cursor-pointer text-center transition-all flex flex-col justify-center gap-1 select-none"
                                         :class="internship_type === 'Paid' ? 'border-primary bg-primary/5 ring-1 ring-primary' : 'bg-white hover:border-gray-300'">
                                        <span class="text-xs font-bold text-[#191C1D]">Paid Internships</span>
                                        <span class="text-[9px] text-secondary-text">Hourly or Salary</span>
                                    </div>
                                    
                                    <div @click="internship_type = 'Stipend'" 
                                         class="border border-brandBorder rounded-xl p-3 cursor-pointer text-center transition-all flex flex-col justify-center gap-1 select-none"
                                         :class="internship_type === 'Stipend' ? 'border-primary bg-primary/5 ring-1 ring-primary' : 'bg-white hover:border-gray-300'">
                                        <span class="text-xs font-bold text-[#191C1D]">Stipend-based</span>
                                        <span class="text-[9px] text-secondary-text">Fixed amount</span>
                                    </div>

                                    <div @click="internship_type = 'Unpaid'" 
                                         class="border border-brandBorder rounded-xl p-3 cursor-pointer text-center transition-all flex flex-col justify-center gap-1 select-none"
                                         :class="internship_type === 'Unpaid' ? 'border-primary bg-primary/5 ring-1 ring-primary' : 'bg-white hover:border-gray-300'">
                                        <span class="text-xs font-bold text-[#191C1D]">Unpaid for Credit</span>
                                        <span class="text-[9px] text-secondary-text">Academic only</span>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <!-- CTA Row -->
                        <div class="flex justify-between items-center pt-4 border-t border-brandBorder">
                            <button @click="step = 5; server_errors = []" class="text-sm font-semibold text-secondary-text hover:text-[#191C1D] transition-colors flex items-center gap-1.5">
                                <iconify-icon icon="lucide:arrow-left"></iconify-icon>
                                <span>Back</span>
                            </button>
                            
                            <button @click="submitStep6()" 
                                    :disabled="departments.length === 0 || submitting"
                                    class="bg-primary text-white px-6 py-3 rounded-lg font-semibold hover:bg-primary-hover disabled:opacity-50 disabled:cursor-not-allowed transition-all shadow-sm flex items-center gap-1.5">
                                <span>Continue</span>
                                <iconify-icon icon="lucide:arrow-right"></iconify-icon>
                            </button>
                        </div>
                    </div>

                    <!-- ─────────────────────────────────────────────── -->
                    <!-- Step 7: Profile Preview                         -->
                    <!-- ─────────────────────────────────────────────── -->
                    <div x-show="step === 7" class="space-y-6">
                        
                        <!-- Mini Card Company Header Profile Preview -->
                        <div class="border border-brandBorder rounded-2xl p-5 bg-white text-left space-y-4 shadow-sm">
                            <div class="flex items-center gap-4">
                                <div class="w-14 h-14 rounded-xl border border-brandBorder bg-gray-50 flex items-center justify-center overflow-hidden flex-shrink-0">
                                    <template x-if="logo_uploaded">
                                        <img :src="logo_url" class="w-full h-full object-cover">
                                    </template>
                                    <template x-if="!logo_uploaded">
                                        <iconify-icon icon="lucide:building-2" class="text-2xl text-gray-400"></iconify-icon>
                                    </template>
                                </div>
                                
                                <div class="min-w-0">
                                    <div class="flex items-center gap-1.5">
                                        <h4 class="text-md font-bold text-[#191C1D] truncate" x-text="company_name || 'Flux Technologies'"></h4>
                                        <iconify-icon icon="lucide:badge-check" class="text-primary text-sm flex-shrink-0"></iconify-icon>
                                    </div>
                                    <div class="flex flex-wrap items-center gap-x-2 gap-y-0.5 text-xs text-secondary-text mt-0.5">
                                        <span class="inline-flex items-center gap-1">
                                            <iconify-icon icon="lucide:building"></iconify-icon>
                                            <span x-text="industry || 'Fintech'"></span>
                                        </span>
                                        <span>•</span>
                                        <span class="inline-flex items-center gap-1">
                                            <iconify-icon icon="lucide:map-pin"></iconify-icon>
                                            <span>Lagos</span>
                                        </span>
                                    </div>
                                    <!-- 0 Open Internships status pill badge -->
                                    <div class="mt-2 inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-gray-100 text-[10px] text-secondary-text font-bold">
                                        <div class="w-1.5 h-1.5 rounded-full bg-gray-400"></div>
                                        <span>0 Open Internships</span>
                                    </div>
                                </div>
                            </div>

                            <!-- About us block -->
                            <div class="space-y-1">
                                <span class="text-[10px] font-bold text-gray-500 uppercase tracking-widest block">About Us</span>
                                <p class="text-xs leading-relaxed text-secondary-text" x-text="description || 'No description provided yet.'"></p>
                            </div>

                            <!-- Hiring Needs block -->
                            <div class="space-y-1.5">
                                <span class="text-[10px] font-bold text-gray-500 uppercase tracking-widest block">Hiring Needs (Preview)</span>
                                <div class="flex flex-wrap gap-1.5">
                                    <template x-for="dept in departments" :key="dept">
                                        <span class="px-2.5 py-0.5 rounded-md border border-primary/20 bg-primary/5 text-primary text-[10px] font-bold" x-text="dept"></span>
                                    </template>
                                    <span class="px-2.5 py-0.5 rounded-md border border-gray-200 bg-gray-50 text-[#191C1D] text-[10px] font-bold">Remote Eligible</span>
                                </div>
                            </div>

                            <!-- Open opportunities block -->
                            <div class="space-y-2 pt-2 border-t border-brandBorder">
                                <span class="text-[10px] font-bold text-[#191C1D] tracking-wide block">Open Opportunities</span>
                                <div class="border border-dashed border-gray-200 rounded-xl p-6 text-center text-xs text-secondary-text flex flex-col items-center justify-center gap-1 bg-[#FDFDFD]">
                                    <iconify-icon icon="lucide:briefcase" class="text-lg text-gray-400"></iconify-icon>
                                    <span>No roles posted yet</span>
                                </div>
                            </div>
                        </div>

                        <!-- CTA Row -->
                        <div class="flex justify-between items-center pt-4 border-t border-brandBorder">
                            <button @click="step = 6; server_errors = []" class="text-sm font-semibold text-secondary-text hover:text-[#191C1D] transition-colors flex items-center gap-1.5">
                                <iconify-icon icon="lucide:arrow-left"></iconify-icon>
                                <span>Back</span>
                            </button>
                            
                            <button @click="submitStep7()" 
                                    :disabled="submitting"
                                    class="bg-primary text-white px-6 py-3 rounded-lg font-semibold hover:bg-primary-hover transition-all shadow-sm flex items-center gap-1.5">
                                <span>Continue</span>
                                <iconify-icon icon="lucide:arrow-right"></iconify-icon>
                            </button>
                        </div>
                    </div>

                </div>
            </div>

        </main>

        <!-- ── Footer ── -->
        <footer class="w-full border-t border-[#E2E8F0] pt-6 flex flex-col md:flex-row justify-between items-center text-xs text-secondary-text mt-8 gap-4 py-4">
            <span>© <span id="copyright-year-register-employer">2026</span> INTERNCONNECT. ALL RIGHTS RESERVED.</span>
            <div class="flex gap-6">
                <a href="#" class="hover:text-primary transition-colors uppercase font-semibold">Privacy Policy</a>
                <a href="#" class="hover:text-primary transition-colors uppercase font-semibold">Terms of Service</a>
            </div>
        </footer>

    </div>

    <!-- ═══════════════════════════════════════════════════ -->
    <!-- Verification Modals (Overlay backdrop)             -->
    <!-- ═══════════════════════════════════════════════════ -->
    
    <!-- Success Modal -->
    <div x-show="show_success_modal" 
         @click.self="show_success_modal = false; step = 4; clearTimers(); saveDraftToLocal()"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-sm shadow-xl"
         x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
        
        <div class="bg-white rounded-3xl border border-brandBorder max-w-md w-full p-8 shadow-2xl flex flex-col items-center text-center space-y-6 relative"
             x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
            
            <!-- Cancellation Close Button -->
            <button @click="show_success_modal = false; step = 4; clearTimers(); saveDraftToLocal()" type="button" class="text-gray-400 hover:text-gray-600" style="position: absolute; right: 16px; top: 16px;">
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
                    Your email <span class="font-semibold text-[#191C1D]" x-text="email"></span> has been verified. Let's set up your company profile next.
                </p>
            </div>

            <button type="button" @click="show_success_modal = false; step = 4; clearTimers(); saveDraftToLocal()"
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
         x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
        
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

</div>

<!-- PHP dynamic welcome URL config — BEFORE onboarding-employer.js -->
<script>
    window.INTERN_WELCOME_EMPLOYER_URL = '<?= url('/welcome/employer') ?>';
</script>
<script src="<?= asset('assets/js/onboarding-employer.js') ?>"></script>
<script>
    const copyrightRegisterEmployer = document.getElementById('copyright-year-register-employer');
    if (copyrightRegisterEmployer) {
        copyrightRegisterEmployer.textContent = new Date().getFullYear();
    }
</script>
