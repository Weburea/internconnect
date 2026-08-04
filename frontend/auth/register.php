<?php

declare(strict_types=1);

/**
 * View: Multi-step Student Onboarding Wizard — Step 1 (Role Selection)
 * Layout: auth
 */
?>
<!-- Load Iconify CDN -->
<script src="https://code.iconify.design/iconify-icon/2.1.0/iconify-icon.min.js"></script>

<!-- Register styles -->
<link rel="stylesheet" href="<?= asset('assets/css/register.css') ?>">

<div x-data="onboardingWizard()" x-init="init()" class="w-full min-h-screen bg-[#F8F9FA] text-[#191C1D] flex flex-col justify-between py-8 select-none" style="font-family: 'Inter', sans-serif;">
    
    <!-- Outer Margin constraint for consistent page width alignment -->
    <div class="w-full max-w-7xl mx-auto px-6 md:px-12 flex-grow flex flex-col justify-between">
        
        <!-- ── Header ── -->
        <header class="w-full flex items-center justify-between mb-8 py-4">
            <a href="<?= url('/') ?>">
                <img src="<?= asset('assets/images/logos/Logo_dark_dx00tg.svg') ?>" alt="InternConnect" class="h-6 w-auto">
            </a>
        </header>

        <!-- ── Main Content Container ── -->
        <main class="flex-grow flex flex-col md:flex-row justify-between gap-12 py-4 custom-main">
            
            <!-- Left Side: Step 1 Marketing Content (Active on mobile for Step 1) -->
            <div class="flex flex-col space-y-6 w-full md:w-1/2 mb-6 md:mb-0">
                <div class="space-y-6">
                    <div class="inline-flex items-center gap-2 border border-primary/20 rounded-full px-4 py-1.5 bg-primary/5">
                        <span class="text-[12px] font-bold tracking-wider text-primary uppercase">Get Started</span>
                    </div>
                    <h2 class="text-[40px] lg:text-[48px] leading-[44px] lg:leading-[50px] font-bold tracking-tight text-[#191C1D]">
                        Your next opportunity starts <span class="text-primary">right here.</span>
                    </h2>
                    <p class="text-[16px] leading-[26px] text-secondary-text">
                        We match ambitious students with top programs. Build your profile once, and let the opportunities come to you.
                    </p>
                    <div class="border-t border-[#E2E8F0] pt-6">
                        <div class="flex items-center gap-3">
                            <div class="flex -space-x-3">
                                <img class="w-10 h-10 rounded-full border-2 border-[#F8F9FA] object-cover shadow-sm bg-white" src="https://res.cloudinary.com/weburea/image/upload/v1785728653/preson_5_kwlmn2.svg" alt="Student 1">
                                <img class="w-10 h-10 rounded-full border-2 border-[#F8F9FA] object-cover shadow-sm bg-white" src="https://res.cloudinary.com/weburea/image/upload/v1785728653/preson_4_omuvrk.svg" alt="Student 2">
                                <img class="w-10 h-10 rounded-full border-2 border-[#F8F9FA] object-cover shadow-sm bg-white" src="https://res.cloudinary.com/weburea/image/upload/v1785728652/preson_6_rqycvb.svg" alt="Student 3">
                            </div>
                            <span class="text-[13px] font-semibold text-secondary-text">12,400+ students found their internship this year</span>
                        </div>
                    </div>
                    <!-- Benefits (Label only, matching mockup exactly) -->
                    <ul class="space-y-4 pt-2">
                        <li class="flex items-center gap-3 text-[15px] font-semibold text-[#191C1D] text-left">
                            <div class="w-10 h-10 rounded-full bg-primary-light flex items-center justify-center text-primary flex-shrink-0">
                                <iconify-icon icon="lucide:shield" class="text-lg"></iconify-icon>
                            </div>
                            <span>Vetted Companies</span>
                        </li>
                        <li class="flex items-center gap-3 text-[15px] font-semibold text-[#191C1D] text-left">
                            <div class="w-10 h-10 rounded-full bg-primary-light flex items-center justify-center text-primary flex-shrink-0">
                                <iconify-icon icon="lucide:heart" class="text-lg"></iconify-icon>
                            </div>
                            <span>Student-First Design</span>
                        </li>
                        <li class="flex items-center gap-3 text-[15px] font-semibold text-[#191C1D] text-left">
                            <div class="w-10 h-10 rounded-full bg-primary-light flex items-center justify-center text-primary flex-shrink-0">
                                <iconify-icon icon="lucide:tag" class="text-lg"></iconify-icon>
                            </div>
                            <span>Free Forever</span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Right Side: Step Progress & Form Card -->
            <div class="custom-card-container flex flex-col justify-center min-h-[500px]">
                
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

                    <!-- ─────────────────────────────────────────────── -->
                    <!-- Step 1: Role Selection                          -->
                    <!-- ─────────────────────────────────────────────── -->
                    <div class="space-y-6">
                        <h3 class="text-[24px] font-bold text-[#191C1D] tracking-tight text-left">I am joining as a...</h3>
                        
                        <div class="space-y-4">
                            <!-- Student Option -->
                            <div @click="role = 'student'" 
                                 class="flex items-center justify-between p-5 rounded-2xl border-2 cursor-pointer transition-all duration-200"
                                 :class="role === 'student' ? 'border-primary bg-primary/5' : 'border-brandBorder bg-white hover:border-gray-300'">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0" :class="role === 'student' ? 'bg-primary text-white' : 'bg-gray-100 text-gray-500'">
                                        <iconify-icon icon="lucide:graduation-cap" class="text-2xl"></iconify-icon>
                                    </div>
                                    <div class="text-left">
                                        <h4 class="font-bold text-[#191C1D] text-[16px]">Student</h4>
                                        <p class="text-[13px] text-secondary-text">Find internships and build your profile</p>
                                    </div>
                                </div>
                                <div class="w-5 h-5 rounded-full border-2 flex items-center justify-center transition-all duration-200 flex-shrink-0" :class="role === 'student' ? 'border-primary bg-primary' : 'border-gray-300'">
                                    <iconify-icon icon="lucide:check" class="text-white text-xs" x-show="role === 'student'"></iconify-icon>
                                </div>
                            </div>

                            <!-- Employer Option -->
                            <div @click="role = 'employer'" 
                                 class="flex items-center justify-between p-5 rounded-2xl border-2 cursor-pointer transition-all duration-200"
                                 :class="role === 'employer' ? 'border-primary bg-primary/5' : 'border-brandBorder bg-white hover:border-gray-300'">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0" :class="role === 'employer' ? 'bg-primary text-white' : 'bg-gray-100 text-gray-500'">
                                        <iconify-icon icon="lucide:building" class="text-2xl"></iconify-icon>
                                    </div>
                                    <div class="text-left">
                                        <h4 class="font-bold text-[#191C1D] text-[16px]">Employer</h4>
                                        <p class="text-[13px] text-secondary-text">Post opportunities and find talent</p>
                                    </div>
                                </div>
                                <div class="w-5 h-5 rounded-full border-2 flex items-center justify-center transition-all duration-200 flex-shrink-0" :class="role === 'employer' ? 'border-primary bg-primary' : 'border-gray-300'">
                                    <iconify-icon icon="lucide:check" class="text-white text-xs" x-show="role === 'employer'"></iconify-icon>
                                </div>
                            </div>
                        </div>

                        <!-- Info Box -->
                        <div class="flex gap-3 bg-primary/5 border border-primary/10 rounded-2xl p-4 text-[13.5px] text-primary">
                            <iconify-icon icon="lucide:info" class="text-lg flex-shrink-0 mt-0.5"></iconify-icon>
                            <span class="text-left" x-text="role === 'employer' ? 'You are on the employer path—tailored for finding top talent.' : 'You are on the student path—tailored for your career growth.'">You are on the student path—tailored for your career growth.</span>
                        </div>

                        <!-- CTA -->
                        <button @click="submitStep1()" 
                                class="w-full bg-primary text-white py-3 rounded-lg font-semibold hover:bg-primary-hover active:scale-[0.99] transition-all duration-200 shadow-sm flex items-center justify-center gap-2">
                            <span x-text="role === 'employer' ? 'Continue as Employer' : 'Continue as Student'">Continue as Student</span>
                            <iconify-icon icon="lucide:arrow-right" class="text-lg"></iconify-icon>
                        </button>

                        <div class="text-center text-[13.5px] text-secondary-text mt-4">
                            Already have an account? <a href="<?= url('/login') ?>" class="text-primary font-semibold hover:underline">Sign in</a>
                        </div>
                    </div>

                </div>
            </div>

        </main>

        <!-- ── Footer ── -->
        <footer class="w-full border-t border-[#E2E8F0] pt-6 flex flex-col md:flex-row justify-between items-center text-xs text-secondary-text mt-8 gap-4 py-4">
            <span>© <span id="copyright-year-register">2026</span> INTERNCONNECT. ALL RIGHTS RESERVED.</span>
            <div class="flex gap-6">
                <a href="#" class="hover:text-primary transition-colors uppercase font-semibold">Privacy Policy</a>
                <a href="#" class="hover:text-primary transition-colors uppercase font-semibold">Terms of Service</a>
            </div>
        </footer>

    </div>

</div>

<!-- Minimal Alpine data for Step 1 only -->
<script>
function onboardingWizard() {
    return {
        step: 1,
        role: 'student',

        init() {},

        submitStep1() {
            // Persist role selection and redirect to appropriate onboarding steps
            localStorage.setItem('internconnect_onboarding_draft', JSON.stringify({ step: 2, role: this.role }));
            if (this.role === 'employer') {
                window.location.href = '<?= url('/register/employer') ?>';
            } else {
                window.location.href = '<?= url('/register/student') ?>';
            }
        }
    };
}
</script>
<script>
    const copyrightRegister = document.getElementById('copyright-year-register');
    if (copyrightRegister) {
        copyrightRegister.textContent = new Date().getFullYear();
    }
</script>
