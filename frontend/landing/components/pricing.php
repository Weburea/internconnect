<?php

declare(strict_types=1);

/**
 * Component: Landing Page Pricing Section
 */
?>
<section id="pricing-section" class="py-16 md:py-24 bg-gray-50 border-t border-gray-100" x-data="{ billing: 'monthly' }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Section Header -->
        <div class="text-center max-w-3xl mx-auto mb-16 flex flex-col space-y-4">
            <span class="text-xs font-semibold text-primary uppercase tracking-widest">Pricing Plans</span>
            <h2 class="text-3xl sm:text-4xl lg:text-[48px] lg:leading-[50px] font-bold text-gray-900 tracking-tight">
                Flexible Pricing Plans
            </h2>
            <p class="text-base text-gray-500 leading-relaxed">
                Choose the right plan to accelerate your career or find the best interns for your organization.
            </p>
            
            <!-- Billing Switcher (Modern Segmented Pill Toggle with Floating Save 20% Tag) -->
            <div class="flex items-center justify-center pt-2">
                <div style="position: relative; display: inline-flex; align-items: center; background-color: #EEF2FF; padding: 4px; border-radius: 9999px; border: 1px solid #E0E7FF; user-select: none;">
                    <!-- Monthly Option -->
                    <button type="button" 
                            @click="billing = 'monthly'" 
                            :style="billing === 'monthly' ? 'background-color: #FFFFFF; color: #4F48E6; font-weight: 700; box-shadow: 0 2px 8px rgba(79, 72, 230, 0.12);' : 'background-color: transparent; color: #475569; font-weight: 600;'"
                            style="padding: 8px 24px; border-radius: 9999px; font-size: 14px; border: none; cursor: pointer; transition: all 0.2s ease; outline: none;">
                        Monthly
                    </button>

                    <!-- Yearly Option Container (Relative for Badge positioning) -->
                    <div style="position: relative;">
                        <button type="button" 
                                @click="billing = 'yearly'" 
                                :style="billing === 'yearly' ? 'background-color: #FFFFFF; color: #4F48E6; font-weight: 700; box-shadow: 0 2px 8px rgba(79, 72, 230, 0.12);' : 'background-color: transparent; color: #475569; font-weight: 600;'"
                                style="padding: 8px 24px; border-radius: 9999px; font-size: 14px; border: none; cursor: pointer; transition: all 0.2s ease; outline: none;">
                            Yearly
                        </button>

                        <!-- Floating Green Tag Badge (Save 20%) -->
                        <div style="position: absolute; top: -14px; right: -12px; z-index: 10; pointer-events: none; transform: rotate(4deg);">
                            <span style="display: inline-flex; align-items: center; gap: 4px; background-color: #5EEAD4; color: #047857; font-size: 11px; font-weight: 700; padding: 3px 8px; border-radius: 9999px; box-shadow: 0 2px 6px rgba(13, 148, 136, 0.25); white-space: nowrap; flex-shrink: 0;">
                                <svg style="width: 12px; height: 12px; flex-shrink: 0;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path>
                                    <line x1="7" y1="7" x2="7.01" y2="7"></line>
                                </svg>
                                <span>Save 20%</span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 3 Pricing Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-stretch max-w-6xl mx-auto pt-6">
            
            <!-- Free Plan Card -->
            <div style="background-color: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 16px; padding: 32px; box-shadow: 0 2px 8px rgba(0,0,0,0.02); display: flex; flex-direction: column; justify-content: space-between; position: relative; min-height: 600px;">
                <div class="space-y-6">
                    <div>
                        <h3 style="font-size: 24px; font-weight: 800; color: #0F172A;">Free</h3>
                    </div>
                    <div style="display: flex; align-items: baseline; color: #0F172A;">
                        <span style="font-size: 36px; font-weight: 800; letter-spacing: -0.02em;">₦0</span>
                        <span style="font-size: 14px; font-weight: 600; color: #64748B; margin-left: 4px;">/month</span>
                    </div>
                    
                    <!-- Features List (Equal 16px SVG Outlined Stars with explicit margin-right and vertical gap) -->
                    <ul style="list-style: none; padding: 0; margin: 0; padding-top: 24px; border-top: 1px solid #F1F5F9; display: flex; flex-direction: column; gap: 18px;">
                        <li style="display: flex; align-items: center; font-size: 14px; color: #475569; line-height: 1.4;">
                            <svg style="width: 16px; height: 16px; min-width: 16px; margin-right: 14px; color: #6366F1;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                            </svg>
                            <span>Create professional profile</span>
                        </li>
                        <li style="display: flex; align-items: center; font-size: 14px; color: #475569; line-height: 1.4;">
                            <svg style="width: 16px; height: 16px; min-width: 16px; margin-right: 14px; color: #6366F1;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                            </svg>
                            <span>Search Internships</span>
                        </li>
                        <li style="display: flex; align-items: center; font-size: 14px; color: #475569; line-height: 1.4;">
                            <svg style="width: 16px; height: 16px; min-width: 16px; margin-right: 14px; color: #6366F1;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                            </svg>
                            <span>Limited applications (5/mo)</span>
                        </li>
                        <li style="display: flex; align-items: center; font-size: 14px; color: #475569; line-height: 1.4;">
                            <svg style="width: 16px; height: 16px; min-width: 16px; margin-right: 14px; color: #6366F1;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                            </svg>
                            <span>Basic recommendations</span>
                        </li>
                        <li style="display: flex; align-items: center; font-size: 14px; color: #475569; line-height: 1.4;">
                            <svg style="width: 16px; height: 16px; min-width: 16px; margin-right: 14px; color: #6366F1;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                            </svg>
                            <span>Track application status</span>
                        </li>
                    </ul>
                </div>
                <div class="mt-8 pt-4">
                    <a href="<?= url('/register') ?>" style="display: flex; align-items: center; justify-content: center; width: 100%; height: 48px; border: 2px solid #CBD5E1; background: #FFFFFF; color: #0F172A; font-weight: 700; font-size: 15px; border-radius: 12px; text-decoration: none; transition: all 0.15s ease;" class="hover:border-primary hover:text-primary hover:bg-slate-50">
                        Get Started
                    </a>
                </div>
            </div>

            <!-- Pro Plan Card (Soft Light Purple Background #F4F3FF & Centered MOST POPULAR Badge) -->
            <div style="background-color: #F4F3FF; border: 2px solid #4F48E6; border-radius: 16px; padding: 32px; box-shadow: 0 4px 20px rgba(79, 72, 230, 0.12); display: flex; flex-direction: column; justify-content: space-between; position: relative; min-height: 600px;" class="transform md:-translate-y-2 hover:shadow-xl transition duration-200">
                <!-- MOST POPULAR Badge (Centered horizontally) -->
                <div style="position: absolute; top: -14px; left: 50%; transform: translateX(-50%); z-index: 20;">
                    <span style="display: inline-flex; align-items: center; justify-content: center; background-color: #4441CC; color: #ffffff; padding: 4px 18px; border-radius: 9999px; font-size: 11px; font-weight: 800; letter-spacing: 0.08em; text-transform: uppercase; box-shadow: 0 2px 8px rgba(68, 65, 204, 0.4); white-space: nowrap;">
                        MOST POPULAR
                    </span>
                </div>
                <div class="space-y-6">
                    <div>
                        <h3 style="font-size: 24px; font-weight: 800; color: #4F48E6;">Pro</h3>
                    </div>
                    <div style="display: flex; align-items: baseline; color: #0F172A;">
                        <span style="font-size: 36px; font-weight: 800; letter-spacing: -0.02em;" x-text="billing === 'monthly' ? '₦2,499' : '₦23,990'">₦2,499</span>
                        <span style="font-size: 14px; font-weight: 600; color: #64748B; margin-left: 4px;" x-text="billing === 'monthly' ? '/month' : '/year'">/month</span>
                    </div>
                    
                    <!-- Features List (Equal 16px Solid Purple Stars with generous breathing gap) -->
                    <ul style="list-style: none; padding: 0; margin: 0; padding-top: 24px; border-top: 1px solid #E0E7FF; display: flex; flex-direction: column; gap: 18px;">
                        <li style="display: flex; align-items: center; font-size: 14px; color: #0F172A; font-weight: 700; line-height: 1.4;">
                            <svg style="width: 16px; height: 16px; min-width: 16px; margin-right: 14px; color: #4F48E6;" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            <span>Everything in Free</span>
                        </li>
                        <li style="display: flex; align-items: center; font-size: 14px; color: #475569; line-height: 1.4;">
                            <svg style="width: 16px; height: 16px; min-width: 16px; margin-right: 14px; color: #4F48E6;" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            <span>Limited applications (15/mo)</span>
                        </li>
                        <li style="display: flex; align-items: center; font-size: 14px; color: #475569; line-height: 1.4;">
                            <svg style="width: 16px; height: 16px; min-width: 16px; margin-right: 14px; color: #4F48E6;" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            <span>AI career features</span>
                        </li>
                        <li style="display: flex; align-items: center; font-size: 14px; color: #475569; line-height: 1.4;">
                            <svg style="width: 16px; height: 16px; min-width: 16px; margin-right: 14px; color: #4F48E6;" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            <span>Increased recruiter visibility</span>
                        </li>
                        <li style="display: flex; align-items: center; font-size: 14px; color: #475569; line-height: 1.4;">
                            <svg style="width: 16px; height: 16px; min-width: 16px; margin-right: 14px; color: #4F48E6;" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            <span>Better internship recommendations</span>
                        </li>
                        <li style="display: flex; align-items: center; font-size: 14px; color: #475569; line-height: 1.4;">
                            <svg style="width: 16px; height: 16px; min-width: 16px; margin-right: 14px; color: #4F48E6;" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            <span>CV &amp; application assistance</span>
                        </li>
                    </ul>
                </div>
                <div class="mt-8 pt-4">
                    <a href="<?= url('/payments?plan=pro') ?>" style="display: flex; align-items: center; justify-content: center; width: 100%; height: 48px; background: #4F48E6; color: #FFFFFF; font-weight: 700; font-size: 15px; border-radius: 12px; text-decoration: none; box-shadow: 0 4px 14px rgba(79, 72, 230, 0.35); transition: all 0.15s ease;" class="hover:bg-[#4338CA]">
                        Upgrade to Pro
                    </a>
                </div>
            </div>

            <!-- Premium Plan Card (Centered BEST VALUE Badge) -->
            <div style="background-color: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 16px; padding: 32px; box-shadow: 0 2px 8px rgba(0,0,0,0.02); display: flex; flex-direction: column; justify-content: space-between; position: relative; min-height: 600px;">
                <!-- BEST VALUE Badge (Centered horizontally) -->
                <div style="position: absolute; top: -14px; left: 50%; transform: translateX(-50%); z-index: 20;">
                    <span style="display: inline-flex; align-items: center; justify-content: center; background-color: #000623; color: #ffffff; padding: 4px 18px; border-radius: 9999px; font-size: 11px; font-weight: 800; letter-spacing: 0.08em; text-transform: uppercase; box-shadow: 0 2px 8px rgba(0, 6, 35, 0.4); white-space: nowrap;">
                        BEST VALUE
                    </span>
                </div>
                <div class="space-y-6">
                    <div>
                        <h3 style="font-size: 24px; font-weight: 800; color: #000623;">Premium</h3>
                    </div>
                    <div style="display: flex; align-items: baseline; color: #0F172A;">
                        <span style="font-size: 36px; font-weight: 800; letter-spacing: -0.02em;" x-text="billing === 'monthly' ? '₦4,999' : '₦47,990'">₦4,999</span>
                        <span style="font-size: 14px; font-weight: 600; color: #64748B; margin-left: 4px;" x-text="billing === 'monthly' ? '/month' : '/year'">/month</span>
                    </div>
                    
                    <!-- Features List (Equal 16px Solid Dark Stars with generous breathing gap) -->
                    <ul style="list-style: none; padding: 0; margin: 0; padding-top: 24px; border-top: 1px solid #F1F5F9; display: flex; flex-direction: column; gap: 18px;">
                        <li style="display: flex; align-items: center; font-size: 14px; color: #0F172A; font-weight: 700; line-height: 1.4;">
                            <svg style="width: 16px; height: 16px; min-width: 16px; margin-right: 14px; color: #000623;" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            <span>Everything in Pro</span>
                        </li>
                        <li style="display: flex; align-items: center; font-size: 14px; color: #475569; line-height: 1.4;">
                            <svg style="width: 16px; height: 16px; min-width: 16px; margin-right: 14px; color: #000623;" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            <span>Unlimited applications</span>
                        </li>
                        <li style="display: flex; align-items: center; font-size: 14px; color: #475569; line-height: 1.4;">
                            <svg style="width: 16px; height: 16px; min-width: 16px; margin-right: 14px; color: #000623;" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            <span>Advanced AI career features</span>
                        </li>
                        <li style="display: flex; align-items: center; font-size: 14px; color: #475569; line-height: 1.4;">
                            <svg style="width: 16px; height: 16px; min-width: 16px; margin-right: 14px; color: #000623;" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            <span>Priority recruiter visibility</span>
                        </li>
                        <li style="display: flex; align-items: center; font-size: 14px; color: #475569; line-height: 1.4;">
                            <svg style="width: 16px; height: 16px; min-width: 16px; margin-right: 14px; color: #000623;" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            <span>AI Interview preparation</span>
                        </li>
                        <li style="display: flex; align-items: center; font-size: 14px; color: #475569; line-height: 1.4;">
                            <svg style="width: 16px; height: 16px; min-width: 16px; margin-right: 14px; color: #000623;" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            <span>Advanced career insight</span>
                        </li>
                    </ul>
                </div>
                <div class="mt-8 pt-4">
                    <a href="<?= url('/payments?plan=premium') ?>" style="display: flex; align-items: center; justify-content: center; width: 100%; height: 48px; background: linear-gradient(157deg, #4F48E6 0%, #000623 100%); color: #FFFFFF !important; font-weight: 700; font-size: 15px; border-radius: 12px; text-decoration: none; box-shadow: 0 4px 14px rgba(0, 6, 35, 0.4); transition: all 0.15s ease;" class="hover:opacity-95">
                        Go Premium
                    </a>
                </div>
            </div>

        </div>

        <!-- Disclaimer Note -->
        <div class="max-w-6xl mx-auto mt-10">
            <p class="text-center italic text-xs text-gray-500">
                Cancel anytime. No hidden fees. Student discounts available.
            </p>
        </div>
    </div>
</section>
