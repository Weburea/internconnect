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
            
            <!-- Billing Switcher -->
            <div class="flex items-center justify-center gap-4 pt-2">
                <span class="text-sm font-medium text-gray-500" :class="{ 'text-gray-900 font-bold': billing === 'monthly' }">Monthly</span>
                <button @click="billing = billing === 'monthly' ? 'yearly' : 'monthly'" type="button" class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none" :class="billing === 'yearly' ? 'bg-primary' : 'bg-gray-300'" role="switch" aria-checked="false">
                    <span aria-hidden="true" class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out" :style="billing === 'yearly' ? 'transform: translateX(20px)' : 'transform: translateX(0px)'"></span>
                </button>
                <span class="text-sm font-medium text-gray-500" :class="{ 'text-gray-900 font-bold': billing === 'yearly' }">
                    Yearly <span class="text-primary font-bold">(Save 20%)</span>
                </span>
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

        <!-- 3. Profile Visibility Cards (Directly Aligned Column-by-Column Under Pricing Cards) -->
        <div class="max-w-6xl mx-auto mt-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Free Visibility Card (Aligned under Free Card) -->
                <div style="background-color: #FAF8FF; border: 1px solid #ECE8F8; border-radius: 16px; padding: 22px 24px; box-shadow: 0 2px 8px rgba(0,0,0,0.02); display: flex; flex-direction: column; justify-content: center; min-height: 104px;">
                    <div style="font-size: 11px; font-weight: 800; color: #9797A3; letter-spacing: 0.08em; text-transform: uppercase; text-align: center; margin-bottom: 6px;">FREE — PROFILE VISIBILITY</div>
                    <div style="font-size: 14px; font-weight: 700; color: #191C1D; text-align: center; margin-bottom: 12px;">Standard</div>
                    <div style="width: 100%; background-color: #EAE8F2; height: 6px; border-radius: 9999px; overflow: hidden;">
                        <div style="width: 30%; background-color: #9797A3; height: 6px; border-radius: 9999px;"></div>
                    </div>
                </div>

                <!-- Pro Visibility Card (Aligned under Pro Card) -->
                <div style="background-color: #FAF8FF; border: 1px solid #ECE8F8; border-radius: 16px; padding: 22px 24px; box-shadow: 0 2px 8px rgba(0,0,0,0.02); display: flex; flex-direction: column; justify-content: center; min-height: 104px;">
                    <div style="font-size: 11px; font-weight: 800; color: #9797A3; letter-spacing: 0.08em; text-transform: uppercase; text-align: center; margin-bottom: 6px;">PRO — PROFILE VISIBILITY</div>
                    <div style="font-size: 14px; font-weight: 700; color: #4F48E6; text-align: center; margin-bottom: 12px;">Enhanced</div>
                    <div style="width: 100%; background-color: #EAE8F2; height: 6px; border-radius: 9999px; overflow: hidden;">
                        <div style="width: 70%; background-color: #4F48E6; height: 6px; border-radius: 9999px;"></div>
                    </div>
                </div>

                <!-- Premium Visibility Card (Aligned under Premium Card) -->
                <div style="background-color: #FAF8FF; border: 1px solid #ECE8F8; border-radius: 16px; padding: 22px 24px; box-shadow: 0 2px 8px rgba(0,0,0,0.02); display: flex; flex-direction: column; justify-content: center; min-height: 104px;">
                    <div style="font-size: 11px; font-weight: 800; color: #9797A3; letter-spacing: 0.08em; text-transform: uppercase; text-align: center; margin-bottom: 6px;">PREMIUM — PROFILE VISIBILITY</div>
                    <div style="font-size: 14px; font-weight: 700; color: #000623; text-align: center; margin-bottom: 12px;">Priority</div>
                    <div style="width: 100%; background-color: #EAE8F2; height: 6px; border-radius: 9999px; overflow: hidden;">
                        <div style="width: 100%; background-color: #000623; height: 6px; border-radius: 9999px;"></div>
                    </div>
                </div>
            </div>
            
            <p class="text-center italic text-xs text-gray-500 mt-6">
                Cancel anytime. No hidden fees. Student discounts available.
            </p>
        </div>
    </div>
</section>
