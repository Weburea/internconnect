<?php

declare(strict_types=1);

/**
 * View: Student Onboarding Wizard — Steps 2 through 7
 * Layout: auth
 */
?>
<!-- Load Iconify CDN -->
<script src="https://code.iconify.design/iconify-icon/2.1.0/iconify-icon.min.js"></script>

<!-- Register styles -->
<link rel="stylesheet" href="<?= asset('assets/css/register.css') ?>">

<div x-data="studentOnboardingWizard()" x-init="init()" class="w-full min-h-screen bg-[#F8F9FA] text-[#191C1D] flex flex-col justify-between py-8 select-none" style="font-family: 'Inter', sans-serif;">
    
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
                        Secure your <span class="text-primary">career profile.</span>
                    </h2>
                    <p class="text-[16px] leading-[26px] text-secondary-text">
                        We take your privacy seriously. Your data is encrypted and never shared without your explicit permission. Focus on building your career, we'll handle the security.
                    </p>
                    <div class="border-t border-[#E2E8F0] pt-6"></div>
                    <ul class="space-y-4">
                        <li class="flex items-start gap-3 text-[14px] text-secondary-text text-left">
                            <div class="w-10 h-10 rounded-full bg-primary-light flex items-center justify-center text-primary flex-shrink-0 mt-0.5">
                                <iconify-icon icon="lucide:lock" class="text-lg"></iconify-icon>
                            </div>
                            <div>
                                <strong class="text-[#191C1D] block">256-bit encrypted sign-in</strong>
                                <span class="text-[13px]">Bank-level security for your personal data.</span>
                            </div>
                        </li>
                        <li class="flex items-start gap-3 text-[14px] text-secondary-text text-left">
                            <div class="w-10 h-10 rounded-full bg-primary-light flex items-center justify-center text-primary flex-shrink-0 mt-0.5">
                                <iconify-icon icon="lucide:ban" class="text-lg"></iconify-icon>
                            </div>
                            <div>
                                <strong class="text-[#191C1D] block">No spam ever</strong>
                                <span class="text-[13px]">We only send you relevant opportunities.</span>
                            </div>
                        </li>
                        <li class="flex items-start gap-3 text-[14px] text-secondary-text text-left">
                            <div class="w-10 h-10 rounded-full bg-primary-light flex items-center justify-center text-primary flex-shrink-0 mt-0.5">
                                <iconify-icon icon="lucide:eye-off" class="text-lg"></iconify-icon>
                            </div>
                            <div>
                                <strong class="text-[#191C1D] block">Profile hidden until ready</strong>
                                <span class="text-[13px]">You control when employers can see you.</span>
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
                        <span class="text-[12px] font-bold tracking-wider text-primary uppercase">Profile</span>
                    </div>
                    <h2 class="text-[40px] lg:text-[48px] leading-[44px] lg:leading-[50px] font-bold tracking-tight text-[#191C1D]">
                        Tell us about <span class="text-primary">yourself.</span>
                    </h2>
                    <p class="text-[16px] leading-[26px] text-secondary-text">
                        Complete your academic profile to unlock opportunities. Candidates with a fully detailed background receive <strong>3x more recruiter attention</strong> on average.
                    </p>
                    <div class="border-t border-[#E2E8F0] pt-6"></div>
                    <ul class="space-y-4">
                        <li class="flex items-start gap-3 text-[14px] text-secondary-text text-left">
                            <div class="w-10 h-10 rounded-full bg-primary-light flex items-center justify-center text-primary flex-shrink-0 mt-0.5">
                                <iconify-icon icon="lucide:shield" class="text-lg"></iconify-icon>
                            </div>
                            <div>
                                <strong class="text-[#191C1D] block">Verified Universities</strong>
                                <span class="text-[13px]">Access exclusive roles matched to your institution.</span>
                            </div>
                        </li>
                        <li class="flex items-start gap-3 text-[14px] text-secondary-text text-left">
                            <div class="w-10 h-10 rounded-full bg-primary-light flex items-center justify-center text-primary flex-shrink-0 mt-0.5">
                                <iconify-icon icon="lucide:mail" class="text-lg"></iconify-icon>
                            </div>
                            <div>
                                <strong class="text-[#191C1D] block">Direct Messaging</strong>
                                <span class="text-[13px]">Skip the line and connect straight with hiring managers.</span>
                            </div>
                        </li>
                        <li class="flex items-start gap-3 text-[14px] text-secondary-text text-left">
                            <div class="w-10 h-10 rounded-full bg-primary-light flex items-center justify-center text-primary flex-shrink-0 mt-0.5">
                                <iconify-icon icon="lucide:graduation-cap" class="text-lg"></iconify-icon>
                            </div>
                            <div>
                                <strong class="text-[#191C1D] block">Interview Prep</strong>
                                <span class="text-[13px]">Unlock personalized resources based on your major.</span>
                            </div>
                        </li>
                    </ul>
                </div>

                <!-- Step 5 Marketing -->
                <div x-show="step === 5" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-6">
                    <div class="inline-flex items-center gap-2 border border-primary/20 rounded-full px-4 py-1.5 bg-primary/5">
                        <span class="text-[12px] font-bold tracking-wider text-primary uppercase">Resume</span>
                    </div>
                    <h2 class="text-[40px] lg:text-[48px] leading-[44px] lg:leading-[50px] font-bold tracking-tight text-[#191C1D]">
                        Show them <span class="text-primary">what you've got.</span>
                    </h2>
                    <p class="text-[16px] leading-[26px] text-secondary-text">
                        A strong resume speaks volumes before you even say a word. Profiles with an attached resume and portfolio see up to 5x higher response rate from hiring managers looking for top talent.
                    </p>
                    <div class="border-t border-[#E2E8F0] pt-6"></div>
                    <ul class="space-y-4">
                        <li class="flex items-start gap-3 text-[14px] text-secondary-text text-left">
                            <div class="w-10 h-10 rounded-full bg-primary-light flex items-center justify-center text-primary flex-shrink-0 mt-0.5">
                                <iconify-icon icon="lucide:file-text" class="text-lg"></iconify-icon>
                            </div>
                            <div>
                                <strong class="text-[#191C1D] block">PDF/DOCX support</strong>
                                <span class="text-[13px]">We accept standard professional formats.</span>
                            </div>
                        </li>
                        <li class="flex items-start gap-3 text-[14px] text-secondary-text text-left">
                            <div class="w-10 h-10 rounded-full bg-primary-light flex items-center justify-center text-primary flex-shrink-0 mt-0.5">
                                <iconify-icon icon="lucide:lock" class="text-lg"></iconify-icon>
                            </div>
                            <div>
                                <strong class="text-[#191C1D] block">Safe Storage</strong>
                                <span class="text-[13px]">Your documents are encrypted and secure.</span>
                            </div>
                        </li>
                        <li class="flex items-start gap-3 text-[14px] text-secondary-text text-left">
                            <div class="w-10 h-10 rounded-full bg-primary-light flex items-center justify-center text-primary flex-shrink-0 mt-0.5">
                                <iconify-icon icon="lucide:link" class="text-lg"></iconify-icon>
                            </div>
                            <div>
                                <strong class="text-[#191C1D] block">Easy Portfolio Link</strong>
                                <span class="text-[13px]">Connect your best work with one click.</span>
                            </div>
                        </li>
                    </ul>
                </div>

                <!-- Step 6 Marketing -->
                <div x-show="step === 6" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-6">
                    <div class="inline-flex items-center gap-2 border border-primary/20 rounded-full px-4 py-1.5 bg-primary/5">
                        <span class="text-[12px] font-bold tracking-wider text-primary uppercase">Interests</span>
                    </div>
                    <h2 class="text-[40px] lg:text-[48px] leading-[44px] lg:leading-[50px] font-bold tracking-tight text-[#191C1D]">
                        What kind of work <span class="text-primary">excites you?</span>
                    </h2>
                    <p class="text-[16px] leading-[26px] text-secondary-text">
                        Our AI-powered matching engine uses your selected interests to surface the most relevant internships, saving you hours of scrolling. Tell us what you love to do.
                    </p>
                    <div class="border-t border-[#E2E8F0] pt-6"></div>
                    <ul class="space-y-4">
                        <li class="flex items-start gap-3 text-[14px] text-secondary-text text-left">
                            <div class="w-10 h-10 rounded-full bg-primary-light flex items-center justify-center text-primary flex-shrink-0 mt-0.5">
                                <iconify-icon icon="lucide:sparkles" class="text-lg"></iconify-icon>
                            </div>
                            <div>
                                <strong class="text-[#191C1D] block">AI Matchmaking</strong>
                            </div>
                        </li>
                        <li class="flex items-start gap-3 text-[14px] text-secondary-text text-left">
                            <div class="w-10 h-10 rounded-full bg-primary-light flex items-center justify-center text-primary flex-shrink-0 mt-0.5">
                                <iconify-icon icon="lucide:bell" class="text-lg"></iconify-icon>
                            </div>
                            <div>
                                <strong class="text-[#191C1D] block">Real-time Alerts</strong>
                            </div>
                        </li>
                        <li class="flex items-start gap-3 text-[14px] text-secondary-text text-left">
                            <div class="w-10 h-10 rounded-full bg-primary-light flex items-center justify-center text-primary flex-shrink-0 mt-0.5">
                                <iconify-icon icon="lucide:briefcase" class="text-lg"></iconify-icon>
                            </div>
                            <div>
                                <strong class="text-[#191C1D] block">New Jobs Daily</strong>
                            </div>
                        </li>
                    </ul>
                </div>

                <!-- Step 7 Marketing -->
                <div x-show="step === 7" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-6">
                    <div class="inline-flex items-center gap-2 border border-primary/20 rounded-full px-4 py-1.5 bg-primary/5">
                        <span class="text-[12px] font-bold tracking-wider text-primary uppercase">Location</span>
                    </div>
                    <h2 class="text-[40px] lg:text-[48px] leading-[44px] lg:leading-[50px] font-bold tracking-tight text-[#191C1D]">
                        Where should we <span class="text-primary">look?</span>
                    </h2>
                    <p class="text-[16px] leading-[26px] text-secondary-text">
                        Telling us where you want to work helps us show you the most relevant internship opportunities. Don't worry, you can always change this later.
                    </p>
                    <div class="border-t border-[#E2E8F0] pt-6"></div>
                    <ul class="space-y-4">
                        <li class="flex items-start gap-3 text-[14px] text-secondary-text text-left">
                            <div class="w-10 h-10 rounded-full bg-primary-light flex items-center justify-center text-primary flex-shrink-0 mt-0.5">
                                <iconify-icon icon="lucide:refresh-cw" class="text-lg"></iconify-icon>
                            </div>
                            <div>
                                <strong class="text-[#191C1D] block">Update this anytime from Settings</strong>
                            </div>
                        </li>
                        <li class="flex items-start gap-3 text-[14px] text-secondary-text text-left">
                            <div class="w-10 h-10 rounded-full bg-primary-light flex items-center justify-center text-primary flex-shrink-0 mt-0.5">
                                <iconify-icon icon="lucide:star" class="text-lg"></iconify-icon>
                            </div>
                            <div>
                                <strong class="text-[#191C1D] block">We'll prioritize roles near you first</strong>
                            </div>
                        </li>
                        <li class="flex items-start gap-3 text-[14px] text-secondary-text text-left">
                            <div class="w-10 h-10 rounded-full bg-primary-light flex items-center justify-center text-primary flex-shrink-0 mt-0.5">
                                <iconify-icon icon="lucide:map-pin" class="text-lg"></iconify-icon>
                            </div>
                            <div>
                                <strong class="text-[#191C1D] block">Add multiple cities to widen your search</strong>
                            </div>
                        </li>
                    </ul>
                </div>

            </div>

            <!-- Right Side: Step Progress & Form Card (Centered on mobile, aligned on desktop) -->
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

                        <!-- Divider -->
                        <div class="flex items-center py-2">
                            <div class="flex-grow border-t border-brandBorder"></div>
                            <span class="px-4 text-[11px] font-bold text-secondary-text tracking-widest uppercase">Or use email</span>
                            <div class="flex-grow border-t border-brandBorder"></div>
                        </div>

                        <!-- Fields with standard left icons -->
                        <div class="space-y-4 text-left">
                            <div>
                                <label for="email" class="block text-[12px] font-bold text-[#191C1D] uppercase tracking-wider mb-1.5">Email Address</label>
                                <div class="relative flex items-center">
                                    <div class="absolute left-3 text-gray-400 pointer-events-none flex items-center">
                                        <iconify-icon icon="lucide:mail" class="text-lg"></iconify-icon>
                                    </div>
                                    <input type="email" id="email" x-model="email" placeholder="e.g. jane@university.edu" 
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
                                <!-- 5 Stacked Thin Horizontal Lines Strength Indicator -->
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
                                <!-- 5 Stacked Thin Horizontal Lines Confirm Indicator -->
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
                                    :disabled="!email || !password || !confirm_password || !terms || submitting"
                                    class="bg-primary text-white px-6 py-3 rounded-lg font-semibold hover:bg-primary-hover disabled:opacity-50 disabled:cursor-not-allowed transition-all shadow-sm flex items-center gap-1.5">
                                <span x-text="submitting ? 'Creating...' : 'Create account'"></span>
                                <iconify-icon icon="lucide:arrow-right" x-show="!submitting"></iconify-icon>
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

                        <!-- Robust hidden-input sequential verification boxes -->
                        <div class="relative flex justify-between gap-2 max-w-[360px] mx-auto py-2 cursor-pointer" @click="$refs.verificationInput.focus()">
                            
                            <!-- Hidden actual input to receive text sequentially -->
                            <input type="text" maxlength="6" x-model="verification_input" x-ref="verificationInput"
                                   @input="handleVerificationInput()"
                                   class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" 
                                   pattern="[0-9]*" inputmode="numeric">
                            
                            <!-- 6 Styled boxes -->
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
                            <button @click="step = 2; server_errors = []" class="text-sm font-semibold text-secondary-text hover:text-[#191C1D] transition-colors flex items-center gap-1.5">
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
                    <!-- Step 4: Academic Profile                        -->
                    <!-- ─────────────────────────────────────────────── -->
                    <div x-show="step === 4" class="space-y-6">
                        <h3 class="text-[24px] font-bold text-[#191C1D] tracking-tight text-left">Academic background</h3>

                        <!-- Verified Email Alert Box for Student -->
                        <div class="flex items-center gap-3 bg-green-50 border border-green-200 rounded-2xl p-4 text-[13px] text-green-700 text-left mb-4">
                            <iconify-icon icon="lucide:check-circle" class="text-lg flex-shrink-0 text-green-600"></iconify-icon>
                            <div>
                                <span class="font-bold block">Email verified</span>
                                <span class="text-xs text-gray-500" x-text="email ? '@' + email.split('@').pop() : '@student.edu'"></span>
                            </div>
                        </div>

                        <div class="space-y-4 text-left">
                            <!-- Names Row -->
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="first-name" class="block text-[12px] font-bold text-[#191C1D] uppercase tracking-wider mb-1.5">First Name</label>
                                    <div class="relative flex items-center">
                                        <div class="absolute left-3 text-gray-400 pointer-events-none flex items-center">
                                            <iconify-icon icon="lucide:user" class="text-lg"></iconify-icon>
                                        </div>
                                        <input type="text" id="first-name" x-model="first_name" placeholder="e.g. Sarah" 
                                               class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-brandBorder focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all text-sm text-[#191C1D] placeholder-gray-400">
                                    </div>
                                </div>
                                <div>
                                    <label for="last-name" class="block text-[12px] font-bold text-[#191C1D] uppercase tracking-wider mb-1.5">Last Name</label>
                                    <div class="relative flex items-center">
                                        <div class="absolute left-3 text-gray-400 pointer-events-none flex items-center">
                                            <iconify-icon icon="lucide:user" class="text-lg"></iconify-icon>
                                        </div>
                                        <input type="text" id="last-name" x-model="last_name" placeholder="e.g. Peters" 
                                               class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-brandBorder focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all text-sm text-[#191C1D] placeholder-gray-400">
                                    </div>
                                </div>
                            </div>

                            <!-- University -->
                            <div>
                                <label for="university" class="block text-[12px] font-bold text-[#191C1D] uppercase tracking-wider mb-1.5">University</label>
                                <div class="relative flex items-center">
                                    <div class="absolute left-3 text-gray-400 pointer-events-none flex items-center">
                                        <iconify-icon icon="lucide:building" class="text-lg"></iconify-icon>
                                    </div>
                                    <input type="text" id="university" x-model="university" placeholder="e.g. University of Lagos" 
                                           class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-brandBorder focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all text-sm text-[#191C1D] placeholder-gray-400">
                                </div>
                            </div>

                            <!-- Degree / Graduation Year Row -->
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-[12px] font-bold text-[#191C1D] uppercase tracking-wider mb-1.5">Degree Level</label>
                                    <div class="relative w-full" x-data="{ open: false }" @click.outside="open = false">
                                        <button type="button" 
                                                class="w-full h-[42px] min-h-[42px] max-h-[42px] flex items-center justify-between px-3.5 py-2 rounded-lg border border-brandBorder focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all text-sm text-[#191C1D] bg-white text-left cursor-pointer overflow-hidden"
                                                :class="{ 'border-primary ring-2 ring-primary/20': open }"
                                                @click="open = !open">
                                            <span class="truncate whitespace-nowrap min-w-0 mr-1" :class="degree_level ? 'text-[#191C1D]' : 'text-gray-400'" x-text="degree_level || 'Select Degree'"></span>
                                            <svg class="w-4 h-4 text-gray-500 transition-transform duration-200 flex-shrink-0" :class="{ 'rotate-180 text-primary': open }" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
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
                                            <template x-for="dOpt in degree_options" :key="dOpt">
                                                <div class="flex items-center justify-between px-3 py-2 text-xs font-medium rounded-lg cursor-pointer transition-colors"
                                                     :class="degree_level === dOpt ? 'bg-indigo-50 text-primary font-semibold' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900'"
                                                     @click="degree_level = dOpt; open = false">
                                                    <span x-text="dOpt"></span>
                                                    <svg x-show="degree_level === dOpt" class="w-3.5 h-3.5 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-[12px] font-bold text-[#191C1D] uppercase tracking-wider mb-1.5">Graduation Year</label>
                                    <div class="relative w-full" x-data="{ open: false }" @click.outside="open = false">
                                        <button type="button" 
                                                class="w-full h-[42px] min-h-[42px] max-h-[42px] flex items-center justify-between px-3.5 py-2 rounded-lg border border-brandBorder focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all text-sm text-[#191C1D] bg-white text-left cursor-pointer overflow-hidden"
                                                :class="{ 'border-primary ring-2 ring-primary/20': open }"
                                                @click="open = !open">
                                            <span class="truncate whitespace-nowrap min-w-0 mr-1" :class="graduation_year ? 'text-[#191C1D]' : 'text-gray-400'" x-text="graduation_year || 'Select Year'"></span>
                                            <svg class="w-4 h-4 text-gray-500 transition-transform duration-200 flex-shrink-0" :class="{ 'rotate-180 text-primary': open }" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
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
                                            <template x-for="yOpt in graduation_year_options" :key="yOpt">
                                                <div class="flex items-center justify-between px-3 py-2 text-xs font-medium rounded-lg cursor-pointer transition-colors"
                                                     :class="graduation_year === yOpt ? 'bg-indigo-50 text-primary font-semibold' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900'"
                                                     @click="graduation_year = yOpt; open = false">
                                                    <span x-text="yOpt"></span>
                                                    <svg x-show="graduation_year === yOpt" class="w-3.5 h-3.5 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Field of Study -->
                            <div>
                                <label for="field-of-study" class="block text-[12px] font-bold text-[#191C1D] uppercase tracking-wider mb-1.5">Field of Study</label>
                                <div class="relative flex items-center">
                                    <div class="absolute left-3 text-gray-400 pointer-events-none flex items-center">
                                        <iconify-icon icon="lucide:book-open" class="text-lg"></iconify-icon>
                                    </div>
                                    <input type="text" id="field-of-study" x-model="field_of_study" placeholder="e.g. Computer Science" 
                                           class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-brandBorder focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all text-sm text-[#191C1D] placeholder-gray-400">
                                </div>
                            </div>

                            <!-- GPA (Optional) -->
                            <div>
                                <label for="gpa" class="block text-[12px] font-bold text-[#191C1D] uppercase tracking-wider mb-1.5">GPA (Optional)</label>
                                <div class="relative flex items-center">
                                    <div class="absolute left-3 text-gray-400 pointer-events-none flex items-center">
                                        <iconify-icon icon="lucide:award" class="text-lg"></iconify-icon>
                                    </div>
                                    <input type="text" id="gpa" x-model="gpa" placeholder="e.g. 3.8 / 4.0" 
                                           class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-brandBorder focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all text-sm text-[#191C1D] placeholder-gray-400">
                                </div>
                            </div>
                        </div>

                        <!-- CTA Row -->
                        <div class="flex justify-between items-center pt-4 border-t border-brandBorder">
                            <button @click="step = 3; server_errors = []" class="text-sm font-semibold text-secondary-text hover:text-[#191C1D] transition-colors flex items-center gap-1.5">
                                <iconify-icon icon="lucide:arrow-left"></iconify-icon>
                                <span>Back</span>
                            </button>
                            
                            <button @click="submitStep4()" 
                                    :disabled="!first_name || !last_name || !university || !degree_level || !graduation_year || !field_of_study || submitting"
                                    class="bg-primary text-white px-6 py-3 rounded-lg font-semibold hover:bg-primary-hover disabled:opacity-50 disabled:cursor-not-allowed transition-all shadow-sm flex items-center gap-1.5">
                                <span x-text="submitting ? 'Saving...' : 'Continue'"></span>
                                <iconify-icon icon="lucide:arrow-right" x-show="!submitting"></iconify-icon>
                            </button>
                        </div>
                    </div>

                    <!-- ─────────────────────────────────────────────── -->
                    <!-- Step 5: Resume & Portfolio                      -->
                    <!-- ─────────────────────────────────────────────── -->
                    <div x-show="step === 5" class="space-y-6">
                        <h3 class="text-[24px] font-bold text-[#191C1D] tracking-tight text-left">Upload your resume</h3>

                        <!-- Drag & Drop Zone -->
                        <div @dragover.prevent="is_dragged = true"
                             @dragleave.prevent="is_dragged = false"
                             @drop.prevent="handleFileDrop($event)"
                             @click="$refs.fileInput.click()"
                             class="border-2 border-dashed rounded-xl py-4 px-4 text-center cursor-pointer transition-all duration-200 flex flex-col items-center justify-center gap-1.5 select-none"
                             :class="is_dragged ? 'border-primary bg-primary/5' : 'border-brandBorder bg-white hover:border-gray-300'">
                            
                            <!-- Hidden File Input -->
                            <input type="file" x-ref="fileInput" accept=".pdf,.docx" class="hidden" @change="handleFileSelect($event)">
                            
                            <div class="w-9 h-9 rounded-full bg-primary/5 flex items-center justify-center text-primary flex-shrink-0">
                                <iconify-icon icon="lucide:cloud-upload" class="text-xl"></iconify-icon>
                            </div>

                            <div class="text-xs font-semibold text-primary">
                                Click to upload <span class="text-[#191C1D]">or drag and drop</span>
                            </div>
                            <div class="text-[11px] text-secondary-text">PDF or DOCX (Max. 10MB)</div>
                        </div>

                        <!-- Visually Rich Uploaded File Preview Card with strict truncation -->
                        <div x-show="fileUploaded" class="text-left mt-3 w-full overflow-hidden" style="display: none;">
                            <p class="text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Uploaded File</p>
                            <div class="border border-gray-200 rounded-xl p-3 flex gap-3 items-center justify-between bg-[#F8FAFC] shadow-sm w-full min-w-0">
                                <div class="flex items-center gap-2.5 min-w-0 flex-1 overflow-hidden">
                                    <!-- Visual file icon -->
                                    <div class="h-10 w-8 bg-white border border-gray-300 rounded-lg shadow-sm flex-shrink-0 relative overflow-hidden flex flex-col p-1 justify-between">
                                        <div class="absolute right-0 top-0 h-3 w-3.5 bg-red-500 text-white font-bold text-[6px] flex items-center justify-center rounded-bl uppercase" x-text="resume_name.split('.').pop()">PDF</div>
                                        <div class="space-y-1 mt-2">
                                            <div class="h-[1.5px] bg-gray-300 w-full rounded"></div>
                                            <div class="h-[1.5px] bg-gray-300 w-5/6 rounded"></div>
                                        </div>
                                    </div>
                                    <div class="min-w-0 flex-1 overflow-hidden">
                                        <p class="text-xs font-bold text-[#191C1D] truncate block" :title="resume_name" x-text="resume_name"></p>
                                        <p class="text-[11px] text-gray-500 truncate" x-text="resume_size + ' • Ready'"></p>
                                        <!-- Status badge -->
                                        <div class="flex items-center gap-1.5 mt-0.5">
                                            <span class="inline-flex items-center px-1.5 py-0.2 rounded-full text-[9px] font-semibold bg-green-50 text-green-700 border border-green-200">Parsed by AI</span>
                                        </div>
                                    </div>
                                </div>
                                <!-- Delete button -->
                                <button type="button" @click="removeSelectedFile()" class="text-red-500 hover:text-red-700 flex items-center flex-shrink-0 p-1.5 rounded-lg hover:bg-red-50 transition-colors" title="Remove Resume">
                                    <iconify-icon icon="lucide:trash-2" class="text-base"></iconify-icon>
                                </button>
                            </div>
                        </div>

                        <!-- Divider -->
                        <div class="flex items-center py-1">
                            <div class="flex-grow border-t border-brandBorder"></div>
                            <span class="px-4 text-[11px] font-bold text-secondary-text tracking-widest uppercase">Portfolio Links</span>
                            <div class="flex-grow border-t border-brandBorder"></div>
                        </div>

                        <!-- Links Fields -->
                        <div class="space-y-4 text-left">
                            <div>
                                <label for="portfolio-url" class="block text-[12px] font-bold text-[#191C1D] uppercase tracking-wider mb-1.5">Portfolio URL (Optional)</label>
                                <div class="relative flex items-center">
                                    <div class="absolute left-3 text-gray-400 pointer-events-none flex items-center">
                                        <iconify-icon icon="lucide:globe" class="text-lg"></iconify-icon>
                                    </div>
                                    <input type="text" id="portfolio-url" x-model="portfolio" placeholder="https://yourportfolio.com" 
                                           class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-brandBorder focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all text-sm text-[#191C1D] placeholder-gray-400">
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="linkedin-url" class="block text-[12px] font-bold text-[#191C1D] uppercase tracking-wider mb-1.5">LinkedIn (Optional)</label>
                                    <div class="relative flex items-center">
                                        <div class="absolute left-3 text-gray-400 pointer-events-none flex items-center">
                                            <iconify-icon icon="lucide:linkedin" class="text-lg"></iconify-icon>
                                        </div>
                                        <input type="text" id="linkedin-url" x-model="linkedin" placeholder="linkedin.com/in/username" 
                                               class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-brandBorder focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all text-sm text-[#191C1D] placeholder-gray-400">
                                    </div>
                                </div>

                                <div>
                                    <label for="github-url" class="block text-[12px] font-bold text-[#191C1D] uppercase tracking-wider mb-1.5">GitHub (Optional)</label>
                                    <div class="relative flex items-center">
                                        <div class="absolute left-3 text-gray-400 pointer-events-none flex items-center">
                                            <iconify-icon icon="lucide:github" class="text-lg"></iconify-icon>
                                        </div>
                                        <input type="text" id="github-url" x-model="github" placeholder="github.com/username" 
                                               class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-brandBorder focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all text-sm text-[#191C1D] placeholder-gray-400">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- CTA Row -->
                        <div class="flex flex-col items-center gap-4 pt-4 border-t border-brandBorder">
                            <div class="w-full flex justify-between items-center">
                                <button @click="step = 4; server_errors = []" class="text-sm font-semibold text-secondary-text hover:text-[#191C1D] transition-colors flex items-center gap-1.5">
                                    <iconify-icon icon="lucide:arrow-left"></iconify-icon>
                                    <span>Back</span>
                                </button>
                                
                                <button @click="submitStep5()" 
                                        :disabled="submitting"
                                        class="bg-primary text-white px-6 py-3 rounded-lg font-semibold hover:bg-primary-hover disabled:opacity-50 disabled:cursor-not-allowed transition-all shadow-sm flex items-center gap-1.5">
                                    <span x-text="submitting ? 'Uploading...' : 'Continue'"></span>
                                    <iconify-icon icon="lucide:arrow-right" x-show="!submitting"></iconify-icon>
                                </button>
                            </div>

                            <button type="button" @click="skipStep5()" class="text-sm text-[#464555] font-semibold hover:text-[#191C1D] hover:underline">
                                Skip for now — I'll add this later
                            </button>
                        </div>
                    </div>

                    <!-- ─────────────────────────────────────────────── -->
                    <!-- Step 6: Career Interests                        -->
                    <!-- ─────────────────────────────────────────────── -->
                    <div x-show="step === 6" class="space-y-6">
                        <h3 class="text-[24px] font-bold text-[#191C1D] tracking-tight text-left">Pick your areas of interest</h3>

                        <!-- Filter search -->
                        <div class="relative flex items-center">
                            <div class="absolute left-3 text-gray-400 pointer-events-none flex items-center">
                                <iconify-icon icon="lucide:search" class="text-lg"></iconify-icon>
                            </div>
                            <input type="text" x-model="search_query" placeholder="Search" 
                                   class="w-full pl-10 pr-4 py-2 rounded-lg border border-brandBorder focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all text-sm text-[#191C1D] placeholder-gray-400">
                        </div>

                        <!-- Interest Tags Grid with Primary Scrollbar -->
                        <div class="flex flex-wrap gap-2 py-2 max-h-[180px] overflow-y-auto pr-1 custom-primary-scrollbar">
                            <template x-for="item in filteredInterests()" :key="item">
                                <button type="button" @click="toggleInterest(item)"
                                        class="px-4 py-2 rounded-full text-xs font-semibold border transition-all duration-200"
                                        :class="interests.includes(item) ? 'bg-primary text-white border-primary' : 'bg-white text-secondary-text border-[#E2E8F0] hover:border-gray-300'">
                                    <span x-text="item"></span>
                                </button>
                            </template>
                        </div>

                        <!-- Work Arrangement -->
                        <div class="space-y-2 border-t border-[#E2E8F0] pt-4 text-left">
                            <span class="text-[12px] font-bold text-secondary-text uppercase tracking-wider block">Work arrangement</span>
                            
                            <div class="flex gap-6">
                                <label class="flex items-center gap-2 cursor-pointer text-sm font-semibold text-[#191C1D]">
                                    <input type="checkbox" value="Remote" x-model="work_arrangements" class="rounded text-primary focus:ring-primary border-brandBorder w-4 h-4">
                                    <span>Remote</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer text-sm font-semibold text-[#191C1D]">
                                    <input type="checkbox" value="Hybrid" x-model="work_arrangements" class="rounded text-primary focus:ring-primary border-brandBorder w-4 h-4">
                                    <span>Hybrid</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer text-sm font-semibold text-[#191C1D]">
                                    <input type="checkbox" value="On-site" x-model="work_arrangements" class="rounded text-primary focus:ring-primary border-brandBorder w-4 h-4">
                                    <span>On-site</span>
                                </label>
                            </div>
                        </div>

                        <!-- CTA Row -->
                        <div class="flex justify-between items-center pt-4 border-t border-[#E2E8F0]">
                            <button @click="step = 5; server_errors = []" class="text-sm font-semibold text-secondary-text hover:text-[#191C1D] transition-colors flex items-center gap-1.5">
                                <iconify-icon icon="lucide:arrow-left"></iconify-icon>
                                <span>Back</span>
                            </button>
                            
                            <button @click="submitStep6()" 
                                    :disabled="interests.length === 0 || work_arrangements.length === 0 || submitting"
                                    class="bg-primary text-white px-6 py-3 rounded-lg font-semibold hover:bg-primary-hover disabled:opacity-50 disabled:cursor-not-allowed transition-all shadow-sm flex items-center gap-1.5">
                                <span x-text="submitting ? 'Saving...' : 'Continue'"></span>
                                <iconify-icon icon="lucide:arrow-right" x-show="!submitting"></iconify-icon>
                            </button>
                        </div>
                    </div>

                    <!-- ─────────────────────────────────────────────── -->
                    <!-- Step 7: Location Preferences                    -->
                    <!-- ─────────────────────────────────────────────── -->
                    <div x-show="step === 7" class="space-y-6">
                        <h3 class="text-[24px] font-bold text-[#191C1D] tracking-tight text-left">Set your location preferences</h3>

                        <div class="space-y-4 text-left">
                            <!-- Current Location -->
                            <div>
                                <label for="current-location" class="block text-[12px] font-bold text-[#191C1D] uppercase tracking-wider mb-1.5">Current location</label>
                                <div class="relative flex items-center">
                                    <div class="absolute left-3 text-gray-400 pointer-events-none flex items-center">
                                        <iconify-icon icon="lucide:map-pin" class="text-lg"></iconify-icon>
                                    </div>
                                    <input type="text" id="current-location" x-model="location" placeholder="Lagos, Nigeria" 
                                           class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-brandBorder focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all text-sm text-[#191C1D] placeholder-gray-400">
                                </div>
                            </div>

                            <!-- Relocating toggle switcher -->
                            <div>
                                <label class="block text-[12px] font-bold text-[#191C1D] uppercase tracking-wider mb-1.5">Open to relocating?</label>
                                <div class="flex bg-gray-100 rounded-lg p-1 border border-brandBorder">
                                    <button type="button" @click="open_to_relocating = 'Yes'" 
                                            class="flex-1 py-2 text-sm font-semibold rounded-lg transition-all"
                                            :class="open_to_relocating === 'Yes' ? 'bg-white text-primary shadow-sm' : 'text-secondary-text hover:text-[#191C1D]'">
                                        Yes
                                    </button>
                                    <button type="button" @click="open_to_relocating = 'No'" 
                                            class="flex-1 py-2 text-sm font-semibold rounded-lg transition-all"
                                            :class="open_to_relocating === 'No' ? 'bg-white text-primary shadow-sm' : 'text-secondary-text hover:text-[#191C1D]'">
                                        No, stay local
                                    </button>
                                </div>
                            </div>

                            <!-- Where do you want job suggestions from (tag input) -->
                            <div>
                                <label class="block text-[12px] font-bold text-[#191C1D] uppercase tracking-wider mb-1.5">Where do you want job suggestions from?</label>
                                
                                <!-- Suggestions Pills Container -->
                                <div class="flex flex-wrap gap-2 p-3 border border-brandBorder rounded-lg min-h-[50px] bg-white items-center">
                                    <template x-for="city in job_suggestions_locations" :key="city">
                                        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-primary/10 text-primary text-xs font-semibold border border-primary/20">
                                            <span x-text="city"></span>
                                            <button type="button" @click="removeSuggestionLocation(city)" class="flex items-center text-primary/80 hover:text-primary">
                                                <iconify-icon icon="lucide:x" class="text-sm"></iconify-icon>
                                            </button>
                                        </div>
                                    </template>

                                    <!-- Mini Inline Input Wrapper with explicit reset styles -->
                                    <div class="flex items-center gap-2 flex-1 min-w-[140px] px-1 py-0.5">
                                        <input type="text" x-model="new_suggestion_location" @keydown.enter.prevent="addSuggestionLocation()" placeholder="Add city..." 
                                               style="border: none !important; outline: none !important; box-shadow: none !important; background: transparent !important; padding: 4px 8px !important;"
                                               class="w-full text-xs text-[#191C1D] placeholder-gray-400 focus:ring-0">
                                        <button type="button" @click="addSuggestionLocation()" class="w-6 h-6 rounded-full border border-dashed border-gray-300 flex items-center justify-center text-secondary-text hover:text-[#191C1D] hover:border-gray-400 flex-shrink-0">
                                            <iconify-icon icon="lucide:plus" class="text-sm"></iconify-icon>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Remote roles checkbox -->
                            <label class="flex items-start gap-3 cursor-pointer pt-2">
                                <input type="checkbox" x-model="include_remote" class="mt-1 rounded text-primary focus:ring-primary border-brandBorder w-4 h-4">
                                <span class="text-[13px] leading-[18px] text-secondary-text">
                                    Include remote-only roles regardless of location
                                </span>
                            </label>
                        </div>

                        <!-- CTA Row -->
                        <div class="flex justify-between items-center pt-4 border-t border-[#E2E8F0]">
                            <button @click="step = 6; server_errors = []" class="text-sm font-semibold text-secondary-text hover:text-[#191C1D] transition-colors flex items-center gap-1.5">
                                <iconify-icon icon="lucide:arrow-left"></iconify-icon>
                                <span>Back</span>
                            </button>
                            
                            <button @click="submitStep7()" 
                                    :disabled="!location || submitting"
                                    class="bg-primary text-white px-6 py-3 rounded-lg font-semibold hover:bg-primary-hover disabled:opacity-50 disabled:cursor-not-allowed transition-all shadow-sm flex items-center gap-1.5">
                                <span x-text="submitting ? 'Finishing...' : 'Finish'"></span>
                                <iconify-icon icon="lucide:arrow-right" x-show="!submitting"></iconify-icon>
                            </button>
                        </div>
                    </div>

                </div>
            </div>

        </main>

        <!-- ── Footer ── -->
        <footer class="w-full border-t border-[#E2E8F0] pt-6 flex flex-col md:flex-row justify-between items-center text-xs text-secondary-text mt-8 gap-4 py-4">
            <span>© <span id="copyright-year-register-student">2026</span> INTERNCONNECT. ALL RIGHTS RESERVED.</span>
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
                    Your email <span class="font-semibold text-[#191C1D]" x-text="email"></span> has been verified. Let's set up your academic profile next.
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
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
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

<!-- External JS: Student Onboarding Wizard -->
<!-- PHP-rendered URL config — must come BEFORE onboarding-student.js -->
<script>
    window.INTERN_WELCOME_URL = '<?= url('/welcome') ?>';
</script>
<script src="<?= asset('assets/js/onboarding-student.js') ?>?v=<?= filemtime(dirname(__DIR__, 2) . '/public/assets/js/onboarding-student.js') ?>"></script>
<script>
    const copyrightRegisterStudent = document.getElementById('copyright-year-register-student');
    if (copyrightRegisterStudent) {
        copyrightRegisterStudent.textContent = new Date().getFullYear();
    }
</script>
