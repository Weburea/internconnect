<?php

declare(strict_types=1);

/**
 * View: Student Onboarding Wizard — Steps 2 through 7
 * Layout: auth
 */
?>
<!-- Load Iconify CDN -->
<script src="https://code.iconify.design/iconify-icon/2.1.0/iconify-icon.min.js"></script>

<!-- Register styles with cache buster -->
<link rel="stylesheet" href="<?= asset('assets/css/register.css') ?>?v=<?= file_exists(dirname(__DIR__, 2) . '/public/assets/css/register.css') ? filemtime(dirname(__DIR__, 2) . '/public/assets/css/register.css') : time() ?>">

<style>
/* ── InternConnect Bulletproof Form Input System (Prevents Tailwind Preflight Collapses) ── */
[x-cloak] { display: none !important; }

.emp-field-group {
    margin-bottom: 16px !important;
    text-align: left !important;
}
.emp-field-group:last-child {
    margin-bottom: 0 !important;
}
.emp-field-label {
    display: block !important;
    font-size: 12px !important;
    font-weight: 600 !important;
    color: #334155 !important;
    margin-bottom: 7px !important;
    text-transform: none !important;
    letter-spacing: 0.01em !important;
    text-align: left !important;
}
.emp-input-text {
    width: 100% !important;
    height: 44px !important;
    min-height: 44px !important;
    background: #FFFFFF !important;
    border: 1px solid #E2E8F0 !important;
    border-radius: 10px !important;
    padding: 0 14px !important;
    font-size: 13.5px !important;
    color: #0F172A !important;
    outline: none !important;
    box-sizing: border-box !important;
    font-family: inherit !important;
    line-height: normal !important;
    transition: border-color 0.15s ease, box-shadow 0.15s ease !important;
}
.emp-input-text:focus {
    border-color: #4F48E6 !important;
    box-shadow: 0 0 0 3px rgba(79, 72, 230, 0.12) !important;
}
.emp-input-text::placeholder {
    color: #94A3B8 !important;
}
.emp-input-with-icon {
    position: relative !important;
    display: flex !important;
    align-items: center !important;
    width: 100% !important;
}
.emp-input-icon-slot {
    position: absolute !important;
    left: 14px !important;
    top: 50% !important;
    transform: translateY(-50%) !important;
    color: #94A3B8 !important;
    pointer-events: none !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    z-index: 2 !important;
}
.emp-input-icon-slot svg,
.emp-input-icon-slot iconify-icon {
    width: 16px !important;
    height: 16px !important;
    font-size: 16px !important;
    flex-shrink: 0 !important;
}
.emp-input-with-icon .emp-input-text {
    padding-left: 40px !important;
}
.emp-input-with-right-icon .emp-input-text {
    padding-right: 44px !important;
}
.emp-input-right-slot {
    position: absolute !important;
    right: 14px !important;
    top: 50% !important;
    transform: translateY(-50%) !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    z-index: 2 !important;
    cursor: pointer !important;
    background: transparent !important;
    border: none !important;
    padding: 0 !important;
    color: #94A3B8 !important;
    transition: color 0.15s ease !important;
}
.emp-input-right-slot:hover {
    color: #475569 !important;
}
.emp-input-right-slot svg,
.emp-input-right-slot iconify-icon {
    width: 18px !important;
    height: 18px !important;
    font-size: 18px !important;
    flex-shrink: 0 !important;
}
.emp-textarea {
    width: 100% !important;
    background: #FFFFFF !important;
    border: 1px solid #E2E8F0 !important;
    border-radius: 10px !important;
    padding: 11px 14px !important;
    font-size: 13.5px !important;
    color: #0F172A !important;
    outline: none !important;
    transition: border-color 0.15s ease, box-shadow 0.15s ease !important;
    font-family: inherit !important;
    line-height: 1.6 !important;
    box-sizing: border-box !important;
    resize: vertical !important;
}
.emp-textarea:focus {
    border-color: #4F48E6 !important;
    box-shadow: 0 0 0 3px rgba(79, 72, 230, 0.12) !important;
}
.emp-textarea::placeholder {
    color: #94A3B8 !important;
}
.emp-segmented-control {
    display: flex !important;
    align-items: center !important;
    background: #F8FAFC !important;
    border: 1px solid #E2E8F0 !important;
    border-radius: 10px !important;
    padding: 3px !important;
    gap: 3px !important;
    width: 100% !important;
    box-sizing: border-box !important;
}
.emp-segment-btn {
    flex: 1 !important;
    min-width: 0 !important;
    border: none !important;
    background: transparent !important;
    padding: 8px 10px !important;
    font-size: 12.5px !important;
    font-weight: 600 !important;
    color: #64748B !important;
    border-radius: 8px !important;
    cursor: pointer !important;
    text-align: center !important;
    transition: all 0.15s ease !important;
    white-space: nowrap !important;
    overflow: hidden !important;
    text-overflow: ellipsis !important;
}
.emp-segment-btn:hover {
    color: #0F172A !important;
}
.emp-segment-btn.active {
    background: #FFFFFF !important;
    color: #4F48E6 !important;
    border: 1px solid #E2E8F0 !important;
    box-shadow: 0 1px 3px rgba(0,0,0,0.06) !important;
    font-weight: 700 !important;
}
.db-dropdown-wrap {
    position: relative !important;
    width: 100% !important;
}
.db-dropdown-btn {
    width: 100% !important;
    background: #FFFFFF !important;
    border: 1px solid #E2E8F0 !important;
    border-radius: 10px !important;
    padding: 11px 14px !important;
    font-size: 13.5px !important;
    font-weight: 500 !important;
    color: #0F172A !important;
    display: flex !important;
    align-items: center !important;
    justify-content: space-between !important;
    cursor: pointer !important;
    transition: border-color 0.15s ease, box-shadow 0.15s ease !important;
    outline: none !important;
    box-sizing: border-box !important;
    font-family: inherit !important;
    text-align: left !important;
}
.db-dropdown-btn:hover {
    border-color: #CBD5E1 !important;
}
.db-dropdown-btn:focus,
.db-dropdown-btn.active {
    border-color: #4F48E6 !important;
    box-shadow: 0 0 0 3px rgba(79, 72, 230, 0.12) !important;
}
.emp-input-with-icon .db-dropdown-btn {
    padding-left: 40px !important;
}
.db-dropdown-menu {
    position: absolute !important;
    top: calc(100% + 6px) !important;
    left: 0 !important;
    right: 0 !important;
    background: #FFFFFF !important;
    border: 1px solid #E2E8F0 !important;
    border-radius: 12px !important;
    box-shadow: 0 10px 28px rgba(15, 23, 42, 0.12) !important;
    z-index: 100 !important;
    padding: 6px !important;
    box-sizing: border-box !important;
}
.db-dropdown-item {
    display: flex !important;
    align-items: center !important;
    justify-content: space-between !important;
    padding: 8px 12px !important;
    font-size: 12.5px !important;
    font-weight: 500 !important;
    color: #334155 !important;
    border-radius: 8px !important;
    cursor: pointer !important;
    transition: background 0.15s ease, color 0.15s ease !important;
}
.db-dropdown-item:hover {
    background: #F8FAFC !important;
    color: #0F172A !important;
}
.db-dropdown-item.is-selected {
    background: #EEF2FF !important;
    color: #4F48E6 !important;
    font-weight: 600 !important;
}
</style>

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
                        A 6-digit code is on its way to <strong class="text-[#191C1D]" x-text="email || 'your email'"></strong>. It expires in 2 minutes, enter it on the right to confirm your email.
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
                                <strong class="text-[#191C1D] block">Code expires in 2 minutes</strong>
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
                        <li class="flex items-center gap-3.5 text-[14px] text-secondary-text text-left">
                            <div class="w-10 h-10 rounded-full bg-primary-light flex items-center justify-center text-primary flex-shrink-0">
                                <iconify-icon icon="lucide:sparkles" class="text-lg"></iconify-icon>
                            </div>
                            <div>
                                <strong class="text-[#191C1D] block">AI Matchmaking</strong>
                                <span class="text-[13px]">Personalized role recommendations.</span>
                            </div>
                        </li>
                        <li class="flex items-center gap-3.5 text-[14px] text-secondary-text text-left">
                            <div class="w-10 h-10 rounded-full bg-primary-light flex items-center justify-center text-primary flex-shrink-0">
                                <iconify-icon icon="lucide:bell" class="text-lg"></iconify-icon>
                            </div>
                            <div>
                                <strong class="text-[#191C1D] block">Real-time Alerts</strong>
                                <span class="text-[13px]">Instant updates as matching roles open.</span>
                            </div>
                        </li>
                        <li class="flex items-center gap-3.5 text-[14px] text-secondary-text text-left">
                            <div class="w-10 h-10 rounded-full bg-primary-light flex items-center justify-center text-primary flex-shrink-0">
                                <iconify-icon icon="lucide:briefcase" class="text-lg"></iconify-icon>
                            </div>
                            <div>
                                <strong class="text-[#191C1D] block">New Jobs Daily</strong>
                                <span class="text-[13px]">Verified employer roles added 24/7.</span>
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
                            <svg class="w-5 h-5" style="width:20px;height:20px;flex-shrink:0" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/><path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/><path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/><path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/></svg>
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
                            <div class="emp-field-group">
                                <label for="email" class="emp-field-label">Email Address</label>
                                <div class="emp-input-with-icon">
                                    <span class="emp-input-icon-slot">
                                        <iconify-icon icon="lucide:mail"></iconify-icon>
                                    </span>
                                    <input type="email" id="email" x-model="email" placeholder="e.g. jane@university.edu" 
                                           class="emp-input-text"
                                           style="height: 44px; min-height: 44px; border: 1px solid #E2E8F0 !important; border-radius: 10px !important; background: #FFFFFF !important; padding-left: 40px !important; padding-right: 14px !important; font-size: 13.5px !important; width: 100% !important; box-sizing: border-box !important;">
                                </div>
                            </div>

                            <!-- Password -->
                            <div class="emp-field-group">
                                <label for="reg-password" class="emp-field-label">Password</label>
                                <div class="emp-input-with-icon emp-input-with-right-icon">
                                    <span class="emp-input-icon-slot">
                                        <iconify-icon icon="lucide:lock"></iconify-icon>
                                    </span>
                                    <input :type="password_visible ? 'text' : 'password'" id="reg-password" x-model="password" placeholder="••••••••" 
                                           class="emp-input-text"
                                           style="height: 44px; min-height: 44px; border: 1px solid #E2E8F0 !important; border-radius: 10px !important; background: #FFFFFF !important; padding-left: 40px !important; padding-right: 44px !important; font-size: 13.5px !important; width: 100% !important; box-sizing: border-box !important; letter-spacing: 0.15em;">
                                    <button @click="password_visible = !password_visible" type="button" 
                                            class="emp-input-right-slot" :title="password_visible ? 'Hide password' : 'Show password'">
                                        <iconify-icon :icon="password_visible ? 'lucide:eye-off' : 'lucide:eye'"></iconify-icon>
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
                            <div class="emp-field-group">
                                <label for="confirm-password" class="emp-field-label">Confirm Password</label>
                                <div class="emp-input-with-icon emp-input-with-right-icon">
                                    <span class="emp-input-icon-slot">
                                        <iconify-icon icon="lucide:lock"></iconify-icon>
                                    </span>
                                    <input :type="confirm_password_visible ? 'text' : 'password'" id="confirm-password" x-model="confirm_password" placeholder="••••••••" 
                                           class="emp-input-text"
                                           style="height: 44px; min-height: 44px; border: 1px solid #E2E8F0 !important; border-radius: 10px !important; background: #FFFFFF !important; padding-left: 40px !important; padding-right: 44px !important; font-size: 13.5px !important; width: 100% !important; box-sizing: border-box !important; letter-spacing: 0.15em;">
                                    <button @click="confirm_password_visible = !confirm_password_visible" type="button" 
                                            class="emp-input-right-slot" :title="confirm_password_visible ? 'Hide password' : 'Show password'">
                                        <iconify-icon :icon="confirm_password_visible ? 'lucide:eye-off' : 'lucide:eye'"></iconify-icon>
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
                                    I agree to the <a href="<?= url('/terms') ?>" class="text-primary font-semibold hover:underline">Terms of Service</a> and <a href="<?= url('/privacy') ?>" class="text-primary font-semibold hover:underline">Privacy Policy</a>.
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
                            
                            <!-- Hidden actual input to receive text sequentially & handle paste -->
                            <input type="text" maxlength="6" x-model="verification_input" x-ref="verificationInput"
                                   @input="handleVerificationInput()"
                                   @paste="handlePaste($event)"
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
                                <div class="emp-field-group">
                                    <label for="first-name" class="emp-field-label">First Name</label>
                                    <div class="emp-input-with-icon">
                                        <span class="emp-input-icon-slot">
                                            <iconify-icon icon="lucide:user"></iconify-icon>
                                        </span>
                                        <input type="text" id="first-name" x-model="first_name" placeholder="e.g. Sarah" 
                                               class="emp-input-text"
                                               style="height: 44px; min-height: 44px; border: 1px solid #E2E8F0 !important; border-radius: 10px !important; background: #FFFFFF !important; padding-left: 40px !important; padding-right: 14px !important; font-size: 13.5px !important; width: 100% !important; box-sizing: border-box !important;">
                                    </div>
                                </div>
                                <div class="emp-field-group">
                                    <label for="last-name" class="emp-field-label">Last Name</label>
                                    <div class="emp-input-with-icon">
                                        <span class="emp-input-icon-slot">
                                            <iconify-icon icon="lucide:user"></iconify-icon>
                                        </span>
                                        <input type="text" id="last-name" x-model="last_name" placeholder="e.g. Peters" 
                                               class="emp-input-text"
                                               style="height: 44px; min-height: 44px; border: 1px solid #E2E8F0 !important; border-radius: 10px !important; background: #FFFFFF !important; padding-left: 40px !important; padding-right: 14px !important; font-size: 13.5px !important; width: 100% !important; box-sizing: border-box !important;">
                                    </div>
                                </div>
                            </div>

                            <!-- University with Autocomplete Dropdown -->
                            <div class="emp-field-group relative" @click.outside="show_university_dropdown = false">
                                <label for="university" class="emp-field-label">University</label>
                                <div class="emp-input-with-icon">
                                    <span class="emp-input-icon-slot">
                                        <iconify-icon icon="lucide:building"></iconify-icon>
                                    </span>
                                    <input type="text" id="university" x-model="university" 
                                           @input="searchUniversities()" 
                                           @focus="if(university_results.length > 0) show_university_dropdown = true"
                                           placeholder="e.g. University of Lagos" 
                                           autocomplete="off"
                                           class="emp-input-text"
                                           style="height: 44px; min-height: 44px; border: 1px solid #E2E8F0 !important; border-radius: 10px !important; background: #FFFFFF !important; padding-left: 40px !important; padding-right: 14px !important; font-size: 13.5px !important; width: 100% !important; box-sizing: border-box !important;">
                                </div>

                                <!-- Autocomplete Results Dropdown -->
                                <div x-show="show_university_dropdown && university_results.length > 0"
                                     x-transition:enter="transition ease-out duration-100"
                                     x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
                                     x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                                     x-transition:leave="transition ease-in duration-75"
                                     x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                                     x-transition:leave-end="opacity-0 scale-95 -translate-y-1"
                                     class="db-dropdown-menu custom-primary-scrollbar"
                                     style="display: none; max-height: 240px; overflow-y: auto;">
                                    <template x-for="item in university_results" :key="item.name + (item.country || '')">
                                        <div class="db-dropdown-item" @click="selectUniversity(item.name)">
                                            <div class="flex flex-col text-left truncate mr-2">
                                                <span class="font-semibold text-gray-900 truncate" x-text="item.name"></span>
                                                <span class="text-[11px] text-gray-500" x-text="item.country ? (item.country + (item.state ? ' • ' + item.state : '')) : ''"></span>
                                            </div>
                                            <span class="text-[10px] text-primary/80 font-mono bg-primary/5 px-2 py-0.5 rounded-full flex-shrink-0" x-show="item.domains && item.domains[0]" x-text="item.domains ? item.domains[0] : ''"></span>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            <!-- Degree / Graduation Year Row -->
                            <div class="grid grid-cols-2 gap-4">
                                <div class="emp-field-group">
                                    <label class="emp-field-label">Degree Level</label>
                                    <div class="db-dropdown-wrap" x-data="{ open: false }" @click.outside="open = false">
                                        <div class="emp-input-with-icon">
                                            <span class="emp-input-icon-slot">
                                                <iconify-icon icon="lucide:graduation-cap"></iconify-icon>
                                            </span>
                                            <button type="button" 
                                                    class="db-dropdown-btn"
                                                    :class="{ 'active': open }"
                                                    @click="open = !open"
                                                    style="height: 44px; min-height: 44px; padding-left: 40px !important; padding-right: 14px !important; width: 100% !important; box-sizing: border-box !important;">
                                                <span class="truncate whitespace-nowrap min-w-0" :class="degree_level ? 'text-[#0F172A] font-medium' : 'text-gray-400 font-normal'" x-text="degree_level || 'Select Degree'"></span>
                                                <svg class="db-dropdown-chevron" :style="open ? 'transform: rotate(180deg)' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="m6 9 6 6 6-6"/>
                                                </svg>
                                            </button>
                                        </div>
                                        <div x-show="open" 
                                             x-transition:enter="transition ease-out duration-100"
                                             x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
                                             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                                             x-transition:leave="transition ease-in duration-75"
                                             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                                             x-transition:leave-end="opacity-0 scale-95 -translate-y-1"
                                             class="db-dropdown-menu custom-primary-scrollbar"
                                             style="display: none; max-height: 220px; overflow-y: auto;">
                                            <template x-for="dOpt in degree_options" :key="dOpt">
                                                <div class="db-dropdown-item"
                                                     :class="{ 'is-selected': degree_level === dOpt }"
                                                     @click="degree_level = dOpt; open = false">
                                                    <span x-text="dOpt"></span>
                                                    <svg x-show="degree_level === dOpt" class="w-3.5 h-3.5 text-primary flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </div>

                                <div class="emp-field-group">
                                    <label class="emp-field-label">Graduation Year</label>
                                    <div class="db-dropdown-wrap" x-data="{ open: false }" @click.outside="open = false">
                                        <div class="emp-input-with-icon">
                                            <span class="emp-input-icon-slot">
                                                <iconify-icon icon="lucide:calendar"></iconify-icon>
                                            </span>
                                            <button type="button" 
                                                    class="db-dropdown-btn"
                                                    :class="{ 'active': open }"
                                                    @click="open = !open"
                                                    style="height: 44px; min-height: 44px; padding-left: 40px !important; padding-right: 14px !important; width: 100% !important; box-sizing: border-box !important;">
                                                <span class="truncate whitespace-nowrap min-w-0" :class="graduation_year ? 'text-[#0F172A] font-medium' : 'text-gray-400 font-normal'" x-text="graduation_year || 'Select Year'"></span>
                                                <svg class="db-dropdown-chevron" :style="open ? 'transform: rotate(180deg)' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="m6 9 6 6 6-6"/>
                                                </svg>
                                            </button>
                                        </div>
                                        <div x-show="open" 
                                             x-transition:enter="transition ease-out duration-100"
                                             x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
                                             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                                             x-transition:leave="transition ease-in duration-75"
                                             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                                             x-transition:leave-end="opacity-0 scale-95 -translate-y-1"
                                             class="db-dropdown-menu custom-primary-scrollbar"
                                             style="display: none; max-height: 220px; overflow-y: auto;">
                                            <template x-for="yOpt in graduation_year_options" :key="yOpt">
                                                <div class="db-dropdown-item"
                                                     :class="{ 'is-selected': graduation_year === yOpt }"
                                                     @click="graduation_year = yOpt; open = false">
                                                    <span x-text="yOpt"></span>
                                                    <svg x-show="graduation_year === yOpt" class="w-3.5 h-3.5 text-primary flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Field of Study -->
                            <div class="emp-field-group">
                                <label for="field-of-study" class="emp-field-label">Field of Study</label>
                                <div class="emp-input-with-icon">
                                    <span class="emp-input-icon-slot">
                                        <iconify-icon icon="lucide:book-open"></iconify-icon>
                                    </span>
                                    <input type="text" id="field-of-study" x-model="field_of_study" placeholder="e.g. Computer Science" 
                                           class="emp-input-text"
                                           style="height: 44px; min-height: 44px; border: 1px solid #E2E8F0 !important; border-radius: 10px !important; background: #FFFFFF !important; padding-left: 40px !important; padding-right: 14px !important; font-size: 13.5px !important; width: 100% !important; box-sizing: border-box !important;">
                                </div>
                            </div>

                            <!-- GPA (Optional) -->
                            <div class="emp-field-group">
                                <label for="gpa" class="emp-field-label">GPA (Optional)</label>
                                <div class="emp-input-with-icon">
                                    <span class="emp-input-icon-slot">
                                        <iconify-icon icon="lucide:award"></iconify-icon>
                                    </span>
                                    <input type="text" id="gpa" x-model="gpa" placeholder="e.g. 3.8 / 4.0" 
                                           class="emp-input-text"
                                           style="height: 44px; min-height: 44px; border: 1px solid #E2E8F0 !important; border-radius: 10px !important; background: #FFFFFF !important; padding-left: 40px !important; padding-right: 14px !important; font-size: 13.5px !important; width: 100% !important; box-sizing: border-box !important;">
                                </div>
                            </div>
                        </div>

                        <!-- CTA Row -->
                        <div class="flex justify-between items-center pt-4 border-t border-brandBorder">
                            <button @click="step = 3; server_errors = []; resetModals();" class="text-sm font-semibold text-secondary-text hover:text-[#191C1D] transition-colors flex items-center gap-1.5">
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
                                        <p class="text-xs font-bold text-[#191C1D] truncate block" style="line-height: 1.35;" x-text="resume_name"></p>
                                        <!-- Size, status and AI badge on one aligned row -->
                                        <div class="flex items-center flex-wrap" style="gap: 6px; margin-top: 4px;">
                                            <span class="text-gray-500 whitespace-nowrap" style="font-size: 11px; line-height: 1.35;" x-text="resume_size + ' • Ready'"></span>
                                            <span class="inline-flex items-center whitespace-nowrap rounded-full bg-green-50 text-green-700 border border-green-200" style="gap: 4px; padding: 2px 8px; font-size: 10px; font-weight: 600; line-height: 1.4;">
                                                <svg style="width: 10px; height: 10px; flex-shrink: 0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                                <span>Parsed by AI</span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <!-- Delete button -->
                                <button type="button" @click="removeSelectedFile()" class="text-red-500 hover:text-red-700 flex items-center flex-shrink-0 p-1.5 rounded-lg hover:bg-red-50 transition-colors" aria-label="Remove resume">
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
                            <div class="emp-field-group">
                                <label for="portfolio-url" class="emp-field-label">Portfolio URL (Optional)</label>
                                <div class="emp-input-with-icon">
                                    <span class="emp-input-icon-slot">
                                        <iconify-icon icon="lucide:globe"></iconify-icon>
                                    </span>
                                    <input type="text" id="portfolio-url" x-model="portfolio" placeholder="https://yourportfolio.com" 
                                           class="emp-input-text"
                                           style="height: 44px; min-height: 44px; border: 1px solid #E2E8F0 !important; border-radius: 10px !important; background: #FFFFFF !important; padding-left: 40px !important; padding-right: 14px !important; font-size: 13.5px !important; width: 100% !important; box-sizing: border-box !important;">
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div class="emp-field-group">
                                    <label for="linkedin-url" class="emp-field-label">LinkedIn (Optional)</label>
                                    <div class="emp-input-with-icon">
                                        <span class="emp-input-icon-slot">
                                            <iconify-icon icon="lucide:linkedin"></iconify-icon>
                                        </span>
                                        <input type="text" id="linkedin-url" x-model="linkedin" placeholder="linkedin.com/in/username" 
                                               class="emp-input-text"
                                               style="height: 44px; min-height: 44px; border: 1px solid #E2E8F0 !important; border-radius: 10px !important; background: #FFFFFF !important; padding-left: 40px !important; padding-right: 14px !important; font-size: 13.5px !important; width: 100% !important; box-sizing: border-box !important;">
                                    </div>
                                </div>

                                <div class="emp-field-group">
                                    <label for="github-url" class="emp-field-label">GitHub (Optional)</label>
                                    <div class="emp-input-with-icon">
                                        <span class="emp-input-icon-slot">
                                            <iconify-icon icon="lucide:github"></iconify-icon>
                                        </span>
                                        <input type="text" id="github-url" x-model="github" placeholder="github.com/username" 
                                               class="emp-input-text"
                                               style="height: 44px; min-height: 44px; border: 1px solid #E2E8F0 !important; border-radius: 10px !important; background: #FFFFFF !important; padding-left: 40px !important; padding-right: 14px !important; font-size: 13.5px !important; width: 100% !important; box-sizing: border-box !important;">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- CTA Row -->
                        <div class="flex flex-col items-center gap-4 pt-4 border-t border-brandBorder">
                            <div class="w-full flex justify-between items-center">
                                <button @click="step = 4; server_errors = []; resetModals();" class="text-sm font-semibold text-secondary-text hover:text-[#191C1D] transition-colors flex items-center gap-1.5">
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
                        <!-- Hidden inputs for form POST and database synchronization -->
                        <input type="hidden" name="interests" :value="JSON.stringify(interests)">
                        <input type="hidden" name="interest_keywords" :value="JSON.stringify(interest_keywords)">

                        <div class="text-left">
                            <h3 class="text-[24px] font-bold text-[#191C1D] tracking-tight">Pick your areas of interest</h3>
                            <p class="text-xs text-secondary-text mt-1">Select fields you're passionate about. We'll surface related specializations automatically.</p>
                        </div>

                        <!-- Selected Interests Container (Matching Step 7 Standard) -->
                        <div x-show="interests.length > 0" class="space-y-1.5 text-left" style="display: none;">
                            <div class="flex items-center justify-between">
                                <label class="emp-field-label">Selected Interests (<span x-text="interests.length"></span>)</label>
                                <button type="button" @click="clearInterests()" class="text-xs font-semibold text-gray-500 hover:text-red-600 transition-colors cursor-pointer">
                                    Reset selection
                                </button>
                            </div>
                            <div class="flex flex-wrap gap-2 p-3 border border-brandBorder rounded-xl min-h-[50px] bg-white items-center">
                                <template x-for="item in interests" :key="item">
                                    <div class="inline-flex items-center gap-1.5 rounded-full bg-primary/10 text-primary text-xs font-semibold border border-primary/20" style="padding: 6px 14px;">
                                        <span x-text="item"></span>
                                        <button type="button" @click="toggleInterest(item)" class="flex items-center text-primary/80 hover:text-primary cursor-pointer">
                                            <iconify-icon icon="lucide:x" class="text-sm"></iconify-icon>
                                        </button>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <!-- Filter Search with Floating Auto-Suggest Dropdown -->
                        <div class="emp-field-group relative text-left" @click.outside="show_interest_dropdown = false">
                            <label class="emp-field-label">Search Disciplines or Specializations</label>
                            <div class="emp-input-with-icon emp-input-with-right-icon">
                                <span class="emp-input-icon-slot">
                                    <iconify-icon icon="lucide:search"></iconify-icon>
                                </span>
                                <input type="text" x-model="search_query" 
                                       @input="show_interest_dropdown = true"
                                       @focus="if(search_query.trim().length > 0) show_interest_dropdown = true"
                                       placeholder="Search roles, disciplines, or keywords (e.g. Figma, React, DevOps)..." 
                                       class="emp-input-text"
                                       style="height: 44px; min-height: 44px; border: 1px solid #E2E8F0 !important; border-radius: 10px !important; background: #FFFFFF !important; padding-left: 40px !important; padding-right: 40px !important; font-size: 13.5px !important; width: 100% !important; box-sizing: border-box !important;">
                                <span class="emp-input-right-slot" x-show="search_query" style="display: none;">
                                    <button type="button" @click="search_query = ''; show_interest_dropdown = false;" class="text-gray-400 hover:text-gray-600 transition-colors flex items-center cursor-pointer">
                                        <iconify-icon icon="lucide:x" class="text-base"></iconify-icon>
                                    </button>
                                </span>
                            </div>

                            <!-- Floating Auto-Suggest Dropdown attached to Search Bar -->
                            <div x-show="show_interest_dropdown && search_query.trim().length > 0"
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
                                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                                 x-transition:leave-end="opacity-0 scale-95 -translate-y-1"
                                 class="absolute left-0 right-0 mt-1.5 w-full bg-white border border-[#E2E8F0] rounded-xl shadow-xl z-50 p-2"
                                 style="display: none; max-height: 200px; overflow-y: auto !important; scrollbar-gutter: stable; box-sizing: border-box;">
                                
                                <template x-if="searchResults().length > 0">
                                    <div class="space-y-1">
                                        <template x-for="item in searchResults()" :key="item">
                                            <div @click="toggleInterest(item); search_query = ''; show_interest_dropdown = false;"
                                                 class="flex items-center justify-between px-3.5 py-2 text-xs font-semibold rounded-lg cursor-pointer transition-all duration-150 select-none hover:bg-gray-50"
                                                 :class="interests.includes(item) ? 'bg-indigo-50/80 text-primary font-bold' : 'text-gray-700'">
                                                <div class="flex items-center gap-2">
                                                    <span x-text="item"></span>
                                                    <span class="text-[10px] text-gray-400 font-normal" x-text="interests_taxonomy[item]?.category ? '(' + interests_taxonomy[item].category + ')' : ''"></span>
                                                </div>
                                                <div class="w-4 h-4 rounded-md border flex items-center justify-center transition-all duration-150 flex-shrink-0"
                                                     :class="interests.includes(item) ? 'bg-primary border-primary text-white' : 'border-gray-300 bg-white'">
                                                    <svg x-show="interests.includes(item)" class="w-2.5 h-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                </template>
                                <template x-if="searchResults().length === 0">
                                    <div class="py-4 text-center text-xs text-gray-400">
                                        No matching disciplines found for "<span class="font-semibold text-gray-600" x-text="search_query"></span>"
                                    </div>
                                </template>
                            </div>
                        </div>

                        <!-- Foundational Disciplines with generous, explicit padding (6px 14px) and subtle borders -->
                        <div class="text-left space-y-2">
                            <div class="flex items-center justify-between">
                                <span class="emp-field-label text-xs font-medium text-gray-500 mb-0">Foundational Disciplines</span>
                                <span x-show="interests.length === 0" class="text-[11px] text-gray-400">Select one or more to begin</span>
                            </div>
                            
                            <div class="flex flex-wrap gap-2.5 py-1">
                                <template x-for="item in foundationalDisciplines" :key="item">
                                    <button type="button" @click="toggleInterest(item)"
                                            class="rounded-full text-xs font-semibold transition-all duration-150 inline-flex items-center gap-1.5 cursor-pointer select-none"
                                            style="padding: 6px 14px;"
                                            :class="interests.includes(item) 
                                                ? 'bg-primary text-white border border-primary shadow-sm' 
                                                : 'bg-white text-secondary-text border border-[#E2E8F0] hover:border-primary/40 hover:text-[#191C1D]'">
                                        <span x-text="item"></span>
                                        <template x-if="interests.includes(item)">
                                            <svg class="w-3 h-3 text-white flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                            </svg>
                                        </template>
                                        <template x-if="!interests.includes(item)">
                                            <iconify-icon icon="lucide:plus" class="text-xs text-gray-400 flex-shrink-0"></iconify-icon>
                                        </template>
                                    </button>
                                </template>
                            </div>
                        </div>

                        <!-- Work Arrangement -->
                        <div class="space-y-2 border-t border-[#E2E8F0] pt-4 text-left">
                            <span class="emp-field-label block">Work arrangement</span>
                            
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
                            <button @click="step = 5; server_errors = []; resetModals();" class="text-sm font-semibold text-secondary-text hover:text-[#191C1D] transition-colors flex items-center gap-1.5">
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
                            <div class="emp-field-group">
                                <label for="current-location" class="emp-field-label">Current location</label>
                                <div class="emp-input-with-icon">
                                    <span class="emp-input-icon-slot">
                                        <iconify-icon icon="lucide:map-pin"></iconify-icon>
                                    </span>
                                    <input type="text" id="current-location" x-model="location" placeholder="e.g. Lagos, Nigeria" 
                                           class="emp-input-text"
                                           style="height: 44px; min-height: 44px; border: 1px solid #E2E8F0 !important; border-radius: 10px !important; background: #FFFFFF !important; padding-left: 40px !important; padding-right: 14px !important; font-size: 13.5px !important; width: 100% !important; box-sizing: border-box !important;">
                                </div>
                            </div>

                            <!-- Relocating toggle switcher -->
                            <div class="emp-field-group">
                                <label class="emp-field-label">Open to relocating?</label>
                                <div class="emp-segmented-control">
                                    <button type="button" @click="open_to_relocating = 'Yes'" 
                                            class="emp-segment-btn"
                                            :class="open_to_relocating === 'Yes' ? 'active' : ''">
                                        Yes
                                    </button>
                                    <button type="button" @click="open_to_relocating = 'No'" 
                                            class="emp-segment-btn"
                                            :class="open_to_relocating === 'No' ? 'active' : ''">
                                        No, stay local
                                    </button>
                                </div>
                            </div>

                            <!-- Where do you want job suggestions from (tag input) -->
                            <div class="emp-field-group">
                                <label class="emp-field-label">Where do you want job suggestions from?</label>
                                
                                <!-- Suggestions Pills Container -->
                                <div class="flex flex-wrap gap-2 p-3 border border-brandBorder rounded-xl min-h-[50px] bg-white items-center">
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
                            <button @click="step = 6; server_errors = []; resetModals();" class="text-sm font-semibold text-secondary-text hover:text-[#191C1D] transition-colors flex items-center gap-1.5">
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
                <a href="<?= url('/privacy') ?>" class="hover:text-primary transition-colors uppercase font-semibold">Privacy Policy</a>
                <a href="<?= url('/terms') ?>" class="hover:text-primary transition-colors uppercase font-semibold">Terms of Service</a>
            </div>
        </footer>

    </div>

    <!-- ═══════════════════════════════════════════════════ -->
    <!-- Verification Modals (Overlay backdrop)             -->
    <!-- ═══════════════════════════════════════════════════ -->
    
    <!-- Success Modal -->
    <div x-show="show_success_modal" 
         x-cloak
         style="display: none;"
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
         x-cloak
         style="display: none;"
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
    window.INTERN_ONBOARD_COMPLETE_URL = '<?= url('/register/student/complete') ?>';
    window.INTERN_UNIVERSITIES_API = '<?= url('/api/universities') ?>';
</script>
<script src="<?= asset('assets/js/onboarding-student.js') ?>?v=<?= filemtime(dirname(__DIR__, 2) . '/public/assets/js/onboarding-student.js') ?>"></script>
<script>
    const copyrightRegisterStudent = document.getElementById('copyright-year-register-student');
    if (copyrightRegisterStudent) {
        copyrightRegisterStudent.textContent = new Date().getFullYear();
    }
</script>
