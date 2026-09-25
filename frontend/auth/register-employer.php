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
                                <div class="relative flex items-center">
                                    <div class="absolute left-3 text-gray-400 pointer-events-none flex items-center">
                                        <iconify-icon icon="lucide:building-2" class="text-lg"></iconify-icon>
                                    </div>
                                    <input type="text" id="company-name" x-model="company_name" placeholder="e.g. Flux Technologies" 
                                           class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-brandBorder focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all text-sm text-[#191C1D] placeholder-gray-400">
                                </div>
                            </div>

                            <div>
                                <label for="email" class="block text-[12px] font-bold text-[#191C1D] uppercase tracking-wider mb-1.5">Work Email</label>
                                <div class="relative flex items-center">
                                    <div class="absolute left-3 text-gray-400 pointer-events-none flex items-center">
                                        <iconify-icon icon="lucide:mail" class="text-lg"></iconify-icon>
                                    </div>
                                    <input type="email" id="email" x-model="email" placeholder="e.g. contact@fluxtech.io" 
                                           class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-brandBorder focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all text-sm text-[#191C1D] placeholder-gray-400">
                                </div>
                            </div>

                            <!-- Password -->
                            <div>
                                <label for="reg-password" class="block text-[12px] font-bold text-[#191C1D] uppercase tracking-wider mb-1.5">Password</label>
                                <div class="relative flex items-center">
                                    <div class="absolute left-3 text-gray-400 pointer-events-none flex items-center">
                                        <iconify-icon icon="lucide:lock" class="text-lg"></iconify-icon>
                                    </div>
                                    <input :type="password_visible ? 'text' : 'password'" id="reg-password" x-model="password" placeholder="••••••••" 
                                           class="w-full pl-10 pr-10 py-2.5 rounded-lg border border-brandBorder focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all text-sm tracking-widest text-[#191C1D] placeholder-gray-400">
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
                                <div class="relative flex items-center">
                                    <div class="absolute left-3 text-gray-400 pointer-events-none flex items-center">
                                        <iconify-icon icon="lucide:lock" class="text-lg"></iconify-icon>
                                    </div>
                                    <input :type="confirm_password_visible ? 'text' : 'password'" id="confirm-password" x-model="confirm_password" placeholder="••••••••" 
                                           class="w-full pl-10 pr-10 py-2.5 rounded-lg border border-brandBorder focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all text-sm tracking-widest text-[#191C1D] placeholder-gray-400">
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
                                     class="border-2 border-dashed rounded-xl py-4 px-4 text-center cursor-pointer transition-all duration-200 flex flex-col items-center justify-center gap-1.5 select-none"
                                     :class="is_dragged_business_doc ? 'border-primary bg-primary/5' : 'border-brandBorder bg-white hover:border-gray-300'">
                                    
                                    <input type="file" x-ref="businessDocInput" accept=".pdf,.png,.jpg,.jpeg" class="hidden" @change="handleBusinessDocSelect($event)">
                                    
                                    <div class="w-9 h-9 rounded-full bg-primary/5 flex items-center justify-center text-primary flex-shrink-0">
                                        <iconify-icon icon="lucide:cloud-upload" class="text-xl"></iconify-icon>
                                    </div>
                                    <div class="text-xs font-semibold text-primary">
                                        Click to upload <span class="text-[#191C1D]">or drag and drop</span>
                                    </div>
                                    <div class="text-[11px] text-secondary-text">PDF, JPG or PNG (max. 10MB)</div>
                                </div>

                                <!-- Business Doc Preview Card with strict truncation and box containment -->
                                <div x-show="business_doc_uploaded" class="mt-3 w-full overflow-hidden" style="display: none;">
                                    <div class="border border-gray-200 rounded-xl p-3 flex gap-3 items-center justify-between bg-[#F8FAFC] shadow-sm w-full min-w-0">
                                        <div class="flex items-center gap-2.5 min-w-0 flex-1 overflow-hidden">
                                            <div class="h-10 w-8 bg-white border border-gray-300 rounded-lg shadow-sm flex-shrink-0 relative overflow-hidden flex flex-col p-1 justify-between">
                                                <div class="absolute right-0 top-0 h-3 w-3.5 bg-primary text-white font-bold text-[6px] flex items-center justify-center rounded-bl uppercase" x-text="business_doc_name.split('.').pop()">PDF</div>
                                                <div class="space-y-1 mt-2">
                                                    <div class="h-[1.5px] bg-gray-300 w-full rounded"></div>
                                                    <div class="h-[1.5px] bg-gray-300 w-5/6 rounded"></div>
                                                </div>
                                            </div>
                                            <div class="min-w-0 flex-1 overflow-hidden">
                                                <p class="text-xs font-bold text-[#191C1D] truncate block" :title="business_doc_name" x-text="business_doc_name"></p>
                                                <p class="text-[11px] text-gray-500 truncate" x-text="business_doc_size"></p>
                                            </div>
                                        </div>
                                        <button type="button" @click="removeBusinessDoc()" class="text-red-500 hover:text-red-700 flex-shrink-0 p-1.5 rounded-lg hover:bg-red-50 transition-colors" title="Delete document">
                                            <iconify-icon icon="lucide:trash-2" class="text-base"></iconify-icon>
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
                    <!-- Step 5: Company Details                         -->
                    <!-- ─────────────────────────────────────────────── -->
                    <div x-show="step === 5" class="space-y-6">
                        <h3 class="text-[24px] font-bold text-[#191C1D] tracking-tight text-left">Company details</h3>

                        <div class="space-y-4 text-left">
                            
                            <!-- Logo / Avatar Preview & Upload -->
                            <div class="flex items-center gap-4">
                                <div @click="$refs.logoInput.click()" 
                                     class="rounded-2xl border border-brandBorder bg-indigo-50/50 flex items-center justify-center cursor-pointer hover:opacity-95 flex-shrink-0 relative overflow-hidden transition-all shadow-sm group"
                                     style="width: 56px; height: 56px; min-width: 56px;"
                                     title="Click to upload or change company logo">
                                    <template x-if="logo_uploaded && logo_url">
                                        <div class="relative w-full h-full">
                                            <img :src="logo_url" @error="logo_uploaded = false; logo_url = ''" class="w-full h-full object-cover">
                                            <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 flex flex-col items-center justify-center transition-opacity text-white">
                                                <iconify-icon icon="lucide:camera" class="text-base"></iconify-icon>
                                            </div>
                                        </div>
                                    </template>
                                    <template x-if="!logo_uploaded || !logo_url">
                                        <div class="relative w-full h-full">
                                            <div class="w-full h-full bg-gradient-to-br from-indigo-500 to-primary flex items-center justify-center text-white font-bold text-base tracking-wider" x-text="getCompanyInitials()"></div>
                                            <div class="absolute inset-0 bg-primary/80 opacity-0 group-hover:opacity-100 flex flex-col items-center justify-center transition-opacity text-white">
                                                <iconify-icon icon="lucide:upload" class="text-base"></iconify-icon>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                                <input type="file" x-ref="logoInput" accept="image/png,image/jpeg" class="hidden" @change="handleLogoSelect($event)">
                                <div class="text-left min-w-0">
                                    <span class="text-xs font-bold block text-[#191C1D]">Company Logo</span>
                                    <span class="text-[11px] text-gray-500 block mt-0.5">High-res square image (PNG or JPG, max 5MB), or use initials avatar.</span>
                                    <div class="flex items-center gap-2 mt-1">
                                        <button type="button" @click="$refs.logoInput.click()" class="text-primary hover:text-primary-hover text-[11px] font-bold inline-flex items-center gap-1 transition-colors">
                                            <iconify-icon icon="lucide:upload" class="text-xs"></iconify-icon>
                                            <span x-text="logo_uploaded && logo_url ? 'Change logo' : 'Upload custom logo'"></span>
                                        </button>
                                        <template x-if="logo_uploaded && logo_url">
                                            <button type="button" @click.stop="removeLogo()" class="text-red-500 hover:text-red-700 text-[11px] font-bold inline-flex items-center gap-1 transition-colors ml-2">
                                                <iconify-icon icon="lucide:trash-2" class="text-xs"></iconify-icon>
                                                <span>Remove</span>
                                            </button>
                                        </template>
                                    </div>
                                </div>
                            </div>

                            <!-- Fields Row -->
                            <div>
                                <label for="company-name-details" class="block text-[12px] font-bold text-[#191C1D] uppercase tracking-wider mb-1.5">Company Name</label>
                                <div class="relative flex items-center">
                                    <div class="absolute left-3 text-gray-400 pointer-events-none flex items-center">
                                        <iconify-icon icon="lucide:building-2" class="text-lg"></iconify-icon>
                                    </div>
                                    <input type="text" id="company-name-details" x-model="company_name" placeholder="e.g. Flux Technologies" 
                                           class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-brandBorder focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all text-sm text-[#191C1D] placeholder-gray-400">
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="industry" class="block text-[12px] font-bold text-[#191C1D] uppercase tracking-wider mb-1.5">Industry</label>
                                    <div class="relative flex items-center">
                                        <div class="absolute left-3 text-gray-400 pointer-events-none flex items-center">
                                            <iconify-icon icon="lucide:briefcase" class="text-lg"></iconify-icon>
                                        </div>
                                        <input type="text" id="industry" x-model="industry" placeholder="e.g. Fintech / SaaS" 
                                               class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-brandBorder focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all text-sm text-[#191C1D] placeholder-gray-400">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-[12px] font-bold text-[#191C1D] uppercase tracking-wider mb-1.5">Company Size</label>
                                    <div class="relative w-full" x-data="{ open: false }" @click.outside="open = false">
                                        <button type="button" 
                                                class="w-full flex items-center justify-between px-4 py-2.5 rounded-lg border border-brandBorder focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all text-sm text-[#191C1D] bg-white text-left cursor-pointer"
                                                :class="{ 'border-primary ring-2 ring-primary/20': open }"
                                                @click="open = !open">
                                            <span :class="company_size ? 'text-[#191C1D]' : 'text-gray-400'" x-text="company_size || 'Select Company Size'"></span>
                                            <svg class="w-4 h-4 text-gray-500 transition-transform duration-200" :class="{ 'rotate-180 text-primary': open }" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="m6 9 6 6 6-6"/>
                                            </svg>
                                        </button>
                                        <div x-show="open" 
                                             x-transition:enter="transition ease-out duration-100"
                                             x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
                                             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                                             x-transition:leave="transition ease-in duration-75"
                                             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                                             x-transition:leave-end="opacity-0 scale-95 -translate-y-1"
                                             class="absolute top-[calc(100%+6px)] left-0 right-0 bg-white border border-brandBorder rounded-xl shadow-lg z-50 p-1.5 max-h-52 overflow-y-auto"
                                             style="display: none;">
                                            <template x-for="sOpt in company_size_options" :key="sOpt">
                                                <div class="flex items-center justify-between px-3 py-2 text-xs font-medium rounded-lg cursor-pointer transition-colors"
                                                     :class="company_size === sOpt ? 'bg-indigo-50 text-primary font-semibold' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900'"
                                                     @click="company_size = sOpt; open = false">
                                                    <span x-text="sOpt"></span>
                                                    <svg x-show="company_size === sOpt" class="w-3.5 h-3.5 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label for="website" class="block text-[12px] font-bold text-[#191C1D] uppercase tracking-wider mb-1.5">Website (Optional)</label>
                                <div class="relative flex items-center">
                                    <div class="absolute left-3 text-gray-400 pointer-events-none flex items-center">
                                        <iconify-icon icon="lucide:globe" class="text-lg"></iconify-icon>
                                    </div>
                                    <input type="text" id="website" x-model="website" placeholder="https://fluxtech.io" 
                                           class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-brandBorder focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all text-sm text-[#191C1D] placeholder-gray-400">
                                </div>
                            </div>

                            <div>
                                <div class="flex justify-between items-center mb-1.5">
                                    <label for="description" class="block text-[12px] font-bold text-[#191C1D] uppercase tracking-wider">Description</label>
                                    <span class="text-[11px] font-medium text-secondary-text" x-text="getWordCount(description) + '/250 words'"></span>
                                </div>
                                <textarea id="description" x-model="description" rows="4" 
                                          placeholder="Briefly describe your company, mission, and internship culture..."
                                          class="w-full px-4 py-3 rounded-lg border border-brandBorder focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all text-sm text-[#191C1D] placeholder-gray-400 custom-primary-scrollbar"
                                          style="min-height: 110px; resize: vertical; scrollbar-width: thin; scrollbar-color: #6366F1 transparent;"></textarea>
                            </div>
                        </div>

                        <!-- CTA Row (Website is Optional) -->
                        <div class="flex justify-between items-center pt-4 border-t border-brandBorder">
                            <button @click="step = 4; server_errors = []" class="text-sm font-semibold text-secondary-text hover:text-[#191C1D] transition-colors flex items-center gap-1.5">
                                <iconify-icon icon="lucide:arrow-left"></iconify-icon>
                                <span>Back</span>
                            </button>
                            
                            <button @click="submitStep5()" 
                                    :disabled="!company_name || !industry || !company_size || !description || submitting"
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

                                <!-- Add Department Dropdown List with Primary Colored Scrollbar & Premium Checkboxes -->
                                <div x-show="show_dept_dropdown" @click.away="show_dept_dropdown = false"
                                     class="absolute left-0 mt-2 w-full max-w-[300px] bg-white border border-brandBorder rounded-2xl shadow-xl z-40 p-3.5 space-y-2.5" style="display:none;">
                                    <div class="relative flex items-center">
                                        <iconify-icon icon="lucide:search" class="absolute left-2.5 text-gray-400"></iconify-icon>
                                        <input type="text" x-model="search_query" placeholder="Search departments..." 
                                               class="w-full pl-8 pr-3 py-1.5 rounded-lg border border-brandBorder text-xs focus:ring-1 focus:ring-primary focus:border-primary outline-none">
                                    </div>
                                    <div class="max-h-[170px] overflow-y-auto space-y-1 pr-1 custom-primary-scrollbar">
                                        <template x-for="dept in filteredDepartments()" :key="dept">
                                            <div @click="toggleDepartment(dept)"
                                                 class="flex items-center justify-between px-2.5 py-2 text-xs font-semibold rounded-lg cursor-pointer transition-all duration-150 select-none"
                                                 :class="departments.includes(dept) ? 'bg-indigo-50/80 text-primary' : 'text-[#191C1D] hover:bg-gray-50'">
                                                <span x-text="dept"></span>
                                                <div class="w-4 h-4 rounded border flex items-center justify-center transition-all duration-150"
                                                     :class="departments.includes(dept) ? 'bg-primary border-primary text-white scale-105' : 'border-gray-300 bg-white'">
                                                    <svg x-show="departments.includes(dept)" class="w-2.5 h-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                    <div class="flex justify-between items-center border-t border-[#E2E8F0] pt-2 text-[11px]">
                                        <button type="button" @click="clearAllDepartments()" class="text-gray-500 hover:text-red-500 font-semibold">Clear all</button>
                                        <button type="button" @click="show_dept_dropdown = false" class="bg-primary text-white px-3 py-1 rounded-md font-bold hover:bg-primary-hover">Apply</button>
                                    </div>
                                </div>
                            </div>

                            <!-- Hiring Volume -->
                            <div>
                                <label class="block text-[12px] font-bold text-[#191C1D] uppercase tracking-wider mb-1.5">Expected Hiring Volume (Per Year)</label>
                                <div class="relative w-full" x-data="{ open: false }" @click.outside="open = false">
                                    <button type="button" 
                                            class="w-full flex items-center justify-between px-4 py-2.5 rounded-lg border border-brandBorder focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all text-sm text-[#191C1D] bg-white text-left cursor-pointer"
                                            :class="{ 'border-primary ring-2 ring-primary/20': open }"
                                            @click="open = !open">
                                        <span :class="expected_volume ? 'text-[#191C1D]' : 'text-gray-400'" x-text="expected_volume || 'Select Hiring Volume'"></span>
                                        <svg class="w-4 h-4 text-gray-500 transition-transform duration-200" :class="{ 'rotate-180 text-primary': open }" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m6 9 6 6 6-6"/>
                                        </svg>
                                    </button>
                                    <div x-show="open" 
                                         x-transition:enter="transition ease-out duration-100"
                                         x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
                                         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                                         x-transition:leave="transition ease-in duration-75"
                                         x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                                         x-transition:leave-end="opacity-0 scale-95 -translate-y-1"
                                         class="absolute top-[calc(100%+6px)] left-0 right-0 bg-white border border-brandBorder rounded-xl shadow-lg z-50 p-1.5 max-h-52 overflow-y-auto"
                                         style="display: none;">
                                        <template x-for="vOpt in expected_volume_options" :key="vOpt">
                                            <div class="flex items-center justify-between px-3 py-2 text-xs font-medium rounded-lg cursor-pointer transition-colors"
                                                 :class="expected_volume === vOpt ? 'bg-indigo-50 text-primary font-semibold' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900'"
                                                 @click="expected_volume = vOpt; open = false">
                                                <span x-text="vOpt"></span>
                                                <svg x-show="expected_volume === vOpt" class="w-3.5 h-3.5 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                                </svg>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </div>

                            <!-- Redesigned Clean & Flexible Internship Type Selection Cards -->
                            <div>
                                <label class="block text-[12px] font-bold text-[#191C1D] uppercase tracking-wider mb-2">Primary Internship Type</label>
                                <div class="space-y-2">
                                    <!-- Paid Internships -->
                                    <div @click="internship_type = 'Paid'" 
                                         class="flex items-center justify-between rounded-xl border cursor-pointer transition-all duration-150 select-none"
                                         style="padding: 14px 16px;"
                                         :class="internship_type === 'Paid' ? 'border-primary bg-indigo-50/50 shadow-sm ring-1 ring-primary' : 'border-brandBorder bg-white hover:border-gray-300 hover:bg-gray-50/60'">
                                        <div class="flex items-center gap-3">
                                            <div class="w-9 h-9 rounded-lg flex items-center justify-center flex-shrink-0 transition-colors"
                                                 :class="internship_type === 'Paid' ? 'bg-primary text-white' : 'bg-gray-100 text-gray-500'">
                                                <iconify-icon icon="lucide:banknote" class="text-lg"></iconify-icon>
                                            </div>
                                            <div class="text-left">
                                                <h5 class="text-[13px] font-bold text-[#191C1D] leading-tight">Paid Internships</h5>
                                                <p class="text-[11px] text-secondary-text mt-0.5 leading-snug">Hourly wage or monthly base salary</p>
                                            </div>
                                        </div>
                                        <div class="w-[18px] h-[18px] rounded-full border-2 flex items-center justify-center flex-shrink-0 ml-3"
                                             :class="internship_type === 'Paid' ? 'border-primary bg-primary' : 'border-gray-300 bg-white'">
                                            <div x-show="internship_type === 'Paid'" class="w-1.5 h-1.5 bg-white rounded-full"></div>
                                        </div>
                                    </div>

                                    <!-- Stipend-based -->
                                    <div @click="internship_type = 'Stipend'" 
                                         class="flex items-center justify-between rounded-xl border cursor-pointer transition-all duration-150 select-none"
                                         style="padding: 14px 16px;"
                                         :class="internship_type === 'Stipend' ? 'border-primary bg-indigo-50/50 shadow-sm ring-1 ring-primary' : 'border-brandBorder bg-white hover:border-gray-300 hover:bg-gray-50/60'">
                                        <div class="flex items-center gap-3">
                                            <div class="w-9 h-9 rounded-lg flex items-center justify-center flex-shrink-0 transition-colors"
                                                 :class="internship_type === 'Stipend' ? 'bg-primary text-white' : 'bg-gray-100 text-gray-500'">
                                                <iconify-icon icon="lucide:coins" class="text-lg"></iconify-icon>
                                            </div>
                                            <div class="text-left">
                                                <h5 class="text-[13px] font-bold text-[#191C1D] leading-tight">Stipend-based</h5>
                                                <p class="text-[11px] text-secondary-text mt-0.5 leading-snug">Fixed allowance (transportation & meal support)</p>
                                            </div>
                                        </div>
                                        <div class="w-[18px] h-[18px] rounded-full border-2 flex items-center justify-center flex-shrink-0 ml-3"
                                             :class="internship_type === 'Stipend' ? 'border-primary bg-primary' : 'border-gray-300 bg-white'">
                                            <div x-show="internship_type === 'Stipend'" class="w-1.5 h-1.5 bg-white rounded-full"></div>
                                        </div>
                                    </div>

                                    <!-- Unpaid for Academic Credit -->
                                    <div @click="internship_type = 'Unpaid'" 
                                         class="flex items-center justify-between rounded-xl border cursor-pointer transition-all duration-150 select-none"
                                         style="padding: 14px 16px;"
                                         :class="internship_type === 'Unpaid' ? 'border-primary bg-indigo-50/50 shadow-sm ring-1 ring-primary' : 'border-brandBorder bg-white hover:border-gray-300 hover:bg-gray-50/60'">
                                        <div class="flex items-center gap-3">
                                            <div class="w-9 h-9 rounded-lg flex items-center justify-center flex-shrink-0 transition-colors"
                                                 :class="internship_type === 'Unpaid' ? 'bg-primary text-white' : 'bg-gray-100 text-gray-500'">
                                                <iconify-icon icon="lucide:graduation-cap" class="text-lg"></iconify-icon>
                                            </div>
                                            <div class="text-left">
                                                <h5 class="text-[13px] font-bold text-[#191C1D] leading-tight">Unpaid for Academic Credit</h5>
                                                <p class="text-[11px] text-secondary-text mt-0.5 leading-snug">University coursework or SIWES/IT accreditation</p>
                                            </div>
                                        </div>
                                        <div class="w-[18px] h-[18px] rounded-full border-2 flex items-center justify-center flex-shrink-0 ml-3"
                                             :class="internship_type === 'Unpaid' ? 'border-primary bg-primary' : 'border-gray-300 bg-white'">
                                            <div x-show="internship_type === 'Unpaid'" class="w-1.5 h-1.5 bg-white rounded-full"></div>
                                        </div>
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
                        <div class="border border-brandBorder rounded-2xl bg-white text-left shadow-sm" style="padding: 24px;">
                            <!-- Company Header -->
                            <div class="flex items-start gap-4" style="margin-bottom: 20px;">
                                <div class="rounded-2xl border border-brandBorder bg-indigo-50/50 flex items-center justify-center overflow-hidden flex-shrink-0 shadow-sm" style="width: 56px; height: 56px; min-width: 56px;">
                                    <template x-if="logo_uploaded && logo_url">
                                        <img :src="logo_url" @error="logo_uploaded = false; logo_url = ''" class="w-full h-full object-cover">
                                    </template>
                                    <template x-if="!logo_uploaded || !logo_url">
                                        <div class="w-full h-full bg-gradient-to-br from-indigo-500 to-primary flex items-center justify-center text-white font-bold text-base tracking-wider" x-text="getCompanyInitials()"></div>
                                    </template>
                                </div>
                                
                                <div class="min-w-0 flex-1" style="padding-top: 2px;">
                                    <div class="flex items-center gap-1.5">
                                        <h4 class="text-sm font-bold text-[#191C1D] truncate" x-text="company_name || 'Flux Technologies'"></h4>
                                        <iconify-icon icon="lucide:badge-check" class="text-primary flex-shrink-0" style="font-size: 14px;"></iconify-icon>
                                    </div>
                                    <div class="flex flex-wrap items-center gap-x-2 gap-y-0.5 text-xs text-secondary-text" style="margin-top: 3px;">
                                        <span class="inline-flex items-center gap-1">
                                            <iconify-icon icon="lucide:building" style="font-size: 12px;"></iconify-icon>
                                            <span x-text="industry || 'Fintech'"></span>
                                        </span>
                                        <span>•</span>
                                        <span class="inline-flex items-center gap-1">
                                            <iconify-icon icon="lucide:map-pin" style="font-size: 12px;"></iconify-icon>
                                            <span>Lagos</span>
                                        </span>
                                    </div>
                                    <!-- Profile Ready pill badge -->
                                    <div class="inline-flex items-center gap-1.5 px-2.5 rounded-full bg-indigo-50 text-primary font-bold border border-primary/20" style="margin-top: 8px; font-size: 10px; padding-top: 3px; padding-bottom: 3px;">
                                        <div class="rounded-full bg-primary animate-pulse" style="width: 6px; height: 6px;"></div>
                                        <span>Profile Ready • 0 Active Postings</span>
                                    </div>
                                </div>
                            </div>

                            <!-- About Us block -->
                            <div style="margin-bottom: 16px;">
                                <span class="block text-gray-400 uppercase font-bold" style="font-size: 10px; letter-spacing: 0.06em; margin-bottom: 4px;">About Us</span>
                                <p class="text-xs leading-relaxed text-secondary-text" x-text="description || 'No description provided yet.'"></p>
                            </div>

                            <!-- Target Departments block -->
                            <div style="margin-bottom: 18px;">
                                <span class="block text-gray-400 uppercase font-bold" style="font-size: 10px; letter-spacing: 0.06em; margin-bottom: 6px;">Target Departments</span>
                                <div class="flex flex-wrap" style="gap: 6px;">
                                    <template x-for="dept in departments" :key="dept">
                                        <span class="rounded-md border border-primary/20 bg-primary/5 text-primary font-bold" style="padding: 4px 10px; font-size: 10px;" x-text="dept"></span>
                                    </template>
                                    <span class="rounded-md border border-gray-200 bg-gray-50 text-[#191C1D] font-bold" style="padding: 4px 10px; font-size: 10px;">Remote Eligible</span>
                                </div>
                            </div>

                            <!-- Organization Details — Clean key-value list -->
                            <div class="border-t border-brandBorder" style="padding-top: 18px;">
                                <div class="flex items-center justify-between" style="margin-bottom: 12px;">
                                    <span class="text-gray-400 uppercase font-bold" style="font-size: 10px; letter-spacing: 0.06em;">Organization Details</span>
                                    <span class="text-primary bg-indigo-50 rounded-full border border-primary/15 inline-flex items-center gap-1 whitespace-nowrap flex-shrink-0 font-bold" style="font-size: 10px; padding: 3px 8px;">
                                        <iconify-icon icon="lucide:shield-check" style="width: 10px; height: 10px;" class="flex-shrink-0"></iconify-icon>
                                        <span>Verified</span>
                                    </span>
                                </div>

                                <!-- Key-value rows with zebra striping -->
                                <div class="rounded-xl border border-gray-200 overflow-hidden">
                                    <div class="flex items-center justify-between bg-white" style="padding: 12px 16px;">
                                        <div class="flex items-center text-secondary-text" style="gap: 8px;">
                                            <iconify-icon icon="lucide:users" style="width: 14px; height: 14px;" class="text-primary/60 flex-shrink-0"></iconify-icon>
                                            <span class="font-medium" style="font-size: 11px;">Target Intake</span>
                                        </div>
                                        <span class="font-bold text-[#191C1D]" style="font-size: 11px;" x-text="expected_volume || '6 - 15 Interns'"></span>
                                    </div>
                                    <div class="border-t border-gray-100"></div>
                                    <div class="flex items-center justify-between" style="padding: 12px 16px; background: rgba(248,250,252,0.6);">
                                        <div class="flex items-center text-secondary-text" style="gap: 8px;">
                                            <iconify-icon icon="lucide:banknote" style="width: 14px; height: 14px;" class="text-primary/60 flex-shrink-0"></iconify-icon>
                                            <span class="font-medium" style="font-size: 11px;">Compensation</span>
                                        </div>
                                        <span class="font-bold text-[#191C1D]" style="font-size: 11px;" x-text="internship_type ? internship_type + ' Program' : 'Paid Program'"></span>
                                    </div>
                                    <div class="border-t border-gray-100"></div>
                                    <div class="flex items-center justify-between bg-white" style="padding: 12px 16px;">
                                        <div class="flex items-center text-secondary-text" style="gap: 8px;">
                                            <iconify-icon icon="lucide:shield-check" style="width: 14px; height: 14px;" class="text-primary/60 flex-shrink-0"></iconify-icon>
                                            <span class="font-medium" style="font-size: 11px;">CAC / RC Number</span>
                                        </div>
                                        <span class="font-bold text-[#191C1D]" style="font-size: 11px;" x-text="cac_number || 'RC-Verified'"></span>
                                    </div>
                                    <div class="border-t border-gray-100"></div>
                                    <div class="flex items-center justify-between" style="padding: 12px 16px; background: rgba(248,250,252,0.6);">
                                        <div class="flex items-center text-secondary-text" style="gap: 8px;">
                                            <iconify-icon icon="lucide:building" style="width: 14px; height: 14px;" class="text-primary/60 flex-shrink-0"></iconify-icon>
                                            <span class="font-medium" style="font-size: 11px;">Organization Size</span>
                                        </div>
                                        <span class="font-bold text-[#191C1D]" style="font-size: 11px;" x-text="company_size || '11-50 employees'"></span>
                                    </div>
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
