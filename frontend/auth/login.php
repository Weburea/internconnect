<?php

declare(strict_types=1);

/**
 * Page: Login Placeholder
 */
?>
<section id="login-page" class="flex w-full h-screen overflow-hidden font-sans bg-white">
    <!-- Left Section: Form -->
    <div class="w-full md:w-1/2 flex flex-col p-8 lg:p-12 overflow-y-auto">
        <!-- Logo -->
        <a href="<?= url('/') ?>" class="flex-shrink-0 flex items-center">
            <img src="<?= asset('assets/images/logos/Logo_dark_dx00tg.svg') ?>" alt="InternConnect" class="h-[18px] md:h-[22px] w-auto">
        </a>

        <!-- Form Container -->
        <div class="w-full max-w-[460px] mx-auto my-auto">
            <h1 class="text-4xl lg:text-5xl leading-tight font-bold text-[#191C1D] mb-3 tracking-tight">Secure your future.</h1>
            <p class="text-[14px] leading-[22px] text-[#464555] mb-6">
                Log in to track your applications and discover the most exclusive internship opportunities curated for you.
            </p>

            <!-- Google button -->
            <a href="<?= url('/welcome?name=Sarah') ?>" class="w-full flex items-center justify-center gap-3 border border-brandBorder rounded-lg py-2.5 text-[14px] font-medium text-[#191C1D] hover:bg-gray-50 transition-colors mb-6 shadow-sm">
                <svg class="w-5 h-5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                    <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                    <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                    <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                </svg>
                Continue with Google
            </a>

            <div class="flex items-center mb-6">
                <div class="flex-grow border-t border-brandBorder"></div>
                <span class="px-4 text-[12px] font-medium text-[#464555] tracking-widest uppercase">Or use email</span>
                <div class="flex-grow border-t border-brandBorder"></div>
            </div>

            <form action="<?= url('/login') ?>" method="POST" class="space-y-4">
                <div>
                    <label for="email" class="block text-[12px] font-bold text-[#191C1D] uppercase tracking-wider mb-1.5">Email Address</label>
                    <input type="email" id="email" name="email" placeholder="applicant@internconnect.com" class="w-full px-4 py-2.5 rounded-lg border border-brandBorder focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all text-[14px] text-[#191C1D] placeholder-[#464555]/50" required>
                </div>
                
                <div>
                    <div class="flex justify-between items-center mb-1.5">
                        <label for="password" class="block text-[12px] font-bold text-[#191C1D] uppercase tracking-wider">Password</label>
                        <a href="<?= url('/forgot-password') ?>" class="text-[14px] font-medium text-primary hover:text-primary-hover">Forgot password?</a>
                    </div>
                    <input type="password" id="password" name="password" placeholder="••••••••" class="w-full px-4 py-2.5 rounded-lg border border-brandBorder focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all text-[14px] tracking-widest text-[#191C1D] placeholder-[#464555]/50" required>
                </div>

                <button type="submit" class="w-full bg-primary text-white py-3 rounded-lg text-[16px] font-medium hover:bg-primary-hover transition-colors shadow-sm mt-2">
                    Log In
                </button>
            </form>

            <div class="mt-6 text-center text-[14px] text-[#464555]">
                Don't have an account? <a href="<?= url('/register') ?>" class="text-primary font-medium hover:text-primary-hover">Sign Up</a>
            </div>
        </div>

        <!-- Footer -->
        <div class="flex justify-between items-center text-[12px] text-[#464555] font-medium uppercase tracking-wider mt-auto pt-4">
            <span>&copy; 2024 INTERNCONNECT.</span>
            <div class="space-x-6">
                <a href="<?= url('/privacy') ?>" class="hover:text-[#191C1D] transition-colors">PRIVACY</a>
                <a href="<?= url('/terms') ?>" class="hover:text-[#191C1D] transition-colors">TERMS</a>
            </div>
        </div>
    </div>

    <!-- Right Section: Presentation -->
    <div class="hidden md:flex w-1/2 bg-[#1A1C3C] text-white flex-col relative items-center justify-center p-12 overflow-hidden">
        
        <!-- Subtle background styling based on image -->
        <div class="absolute inset-0 bg-gradient-to-br from-[#1E234B] to-[#12142B] z-0"></div>
        <div class="absolute inset-0 bg-cover bg-left mix-blend-overlay z-0" style="opacity: 0.95; background-image: url('<?= asset('assets/images/Visual_Identity_7_ptgohs.png') ?>'); transform: scale(1.2); transform-origin: left 80px;"></div>
        <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-5 mix-blend-overlay z-0"></div>
        
        <!-- Large abstract geometry in background -->
        <div class="absolute bottom-[-10%] right-[-10%] w-3/4 h-3/4 border-[40px] border-white/5 rounded-tl-[80px] z-0"></div>
        
        <!-- Content -->
        <div class="relative z-10 w-full max-w-[500px]">
            <div class="inline-flex items-center gap-2 border border-white/20 rounded-full px-4 py-1.5 mb-6 bg-white/5 backdrop-blur-sm">
                <svg class="w-4 h-4 text-white/80" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="text-[12px] font-semibold tracking-wider text-white/90 uppercase">Premium Curator Access</span>
            </div>

            <h2 class="text-[40px] lg:text-[48px] leading-[44px] lg:leading-[50px] font-bold mb-4 tracking-tight text-white">
                Where academic<br>
                rigor meets<br>
                industrial <span class="font-serif italic text-white/90 font-light">excellence.</span>
            </h2>

            <p class="text-[16px] lg:text-[18px] leading-[26px] lg:leading-[28px] text-white/70 mb-12 max-w-md">
                Our curated network connects top-tier applicant with internships at Fortune 500 companies and visionary startups.
            </p>

            <!-- Cards -->
            <div class="flex gap-4">
                <!-- Card 1 -->
                <div class="bg-[#242A4D]/80 border border-white/10 backdrop-blur-md rounded-xl p-4 flex-1 hover:bg-[#2A305A]/90 transition-all cursor-pointer">
                    <div class="mb-3">
                        <svg class="w-5 h-5 text-white/80" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div class="text-[10px] font-bold text-white/60 tracking-widest uppercase mb-1">New Role</div>
                    <div class="font-semibold text-white text-[15px]">UX Researcher</div>
                </div>
                
                <!-- Card 2 -->
                <div class="bg-[#242A4D]/80 border border-white/10 backdrop-blur-md rounded-xl p-4 flex-1 hover:bg-[#2A305A]/90 transition-all cursor-pointer">
                    <div class="mb-3">
                        <svg class="w-5 h-5 text-white/80" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                        </svg>
                    </div>
                    <div class="text-[10px] font-bold text-white/60 tracking-widest uppercase mb-1">High Match</div>
                    <div class="font-semibold text-white text-[15px]">Data Science</div>
                </div>
            </div>
        </div>
    </div>
</section>
