<?php

declare(strict_types=1);

/**
 * View: Pricing Page
 * Route: /pricing
 * Layout: app
 */
?>

<!-- 1. Pricing Plans & Billing Hero Section -->
<section id="pricing-plans-section" class="py-16 md:py-24 bg-gray-50/50" x-data="{ billing: 'monthly' }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Section Header / Banner -->
        <div class="text-center max-w-3xl mx-auto mb-16 flex flex-col space-y-4">
            <span class="text-xs font-semibold text-primary uppercase tracking-widest">Pricing Plans</span>
            <h1 class="text-3xl sm:text-4xl lg:text-[48px] lg:leading-[50px] font-bold text-gray-900 tracking-tight">
                Flexible Pricing Plans
            </h1>
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

<!-- 4. Side-by-Side Comparison Section (Generous 1020px container with tall breathing row padding) -->
<section id="comparison-section" class="py-16 md:py-24 bg-white border-t border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Unified Header: Why Interns Upgrade & Side by Side Compare Plans -->
        <div class="text-center max-w-3xl mx-auto mb-12 flex flex-col space-y-3">
            <h2 class="text-3xl sm:text-5xl font-extrabold text-primary tracking-tight mb-1">
                Why Interns Upgrade
            </h2>
            <p class="text-sm sm:text-base text-gray-600 leading-relaxed max-w-2xl mx-auto pb-4">
                78% of Pro members report hearing back from recruiters faster. AI-tailored applications and priority visibility mean your profile reaches the right people before your peers do.
            </p>
            <span style="color: #6366F1; font-size: 13px; font-weight: 800; letter-spacing: 0.1em; text-transform: uppercase;">SIDE BY SIDE</span>
            <h3 style="color: #0F172A; font-size: 38px; font-weight: 800; letter-spacing: -0.02em;">
                Compare Plans
            </h3>
        </div>

        <!-- Comparison Table Card (Increased width to max-w-[1020px] and spacious row padding) -->
        <div style="max-width: 1020px; margin: 0 auto; background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 16px; box-shadow: 0px 4px 20px -2px rgba(0, 0, 0, 0.05); overflow: hidden;">
            <div class="overflow-x-auto">
                <table style="width: 100%; border-collapse: collapse; table-layout: fixed;">
                    <thead>
                        <tr style="border-bottom: 1px solid #F1F5F9; background-color: #FAFAFA;">
                            <th style="width: 34%; padding: 22px 28px; text-align: left; font-size: 15px; font-weight: 700; color: #0F172A;">Feature</th>
                            <th style="width: 21%; padding: 22px 16px; text-align: center; font-size: 15px; font-weight: 700; color: #0F172A;">Free</th>
                            <th style="width: 20%; padding: 22px 16px; text-align: center; font-size: 15px; font-weight: 700; color: #4F48E6;">Pro</th>
                            <th style="width: 25%; padding: 22px 28px; text-align: center; font-size: 15px; font-weight: 700; color: #000623;">Premium</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Row 1: Applications -->
                        <tr style="border-bottom: 1px solid #F1F5F9;">
                            <td style="padding: 20px 28px; font-size: 14px; font-weight: 700; color: #1E293B;">Applications</td>
                            <td style="padding: 20px 16px; font-size: 14px; color: #64748B; text-align: center;">5/month</td>
                            <td style="padding: 20px 16px; font-size: 14px; color: #64748B; text-align: center;">15/month</td>
                            <td style="padding: 20px 28px; text-align: center;">
                                <span style="font-size: 24px; font-weight: 700; color: #000623; line-height: 1;">∞</span>
                            </td>
                        </tr>
                        <!-- Row 2: Application Tracking -->
                        <tr style="border-bottom: 1px solid #F1F5F9;">
                            <td style="padding: 20px 28px; font-size: 14px; font-weight: 700; color: #1E293B;">Application Tracking</td>
                            <td style="padding: 20px 16px; font-size: 14px; color: #94A3B8; text-align: center;">Basic</td>
                            <td style="padding: 20px 16px; text-align: center;">
                                <span style="color: #4F48E6; font-size: 18px; font-weight: 800;">✓</span>
                            </td>
                            <td style="padding: 20px 28px; text-align: center;">
                                <span style="font-size: 24px; font-weight: 700; color: #000623; line-height: 1;">∞</span>
                            </td>
                        </tr>
                        <!-- Row 3: AI Career feature -->
                        <tr style="border-bottom: 1px solid #F1F5F9;">
                            <td style="padding: 20px 28px; font-size: 14px; font-weight: 700; color: #1E293B;">AI Career feature</td>
                            <td style="padding: 20px 16px; color: #CBD5E1; text-align: center;">—</td>
                            <td style="padding: 20px 16px; text-align: center;">
                                <span style="color: #4F48E6; font-size: 18px; font-weight: 800;">✓</span>
                            </td>
                            <td style="padding: 20px 28px; text-align: center;">
                                <span style="font-size: 24px; font-weight: 700; color: #000623; line-height: 1;">∞</span>
                            </td>
                        </tr>
                        <!-- Row 4: Increased recruiter visibility -->
                        <tr style="border-bottom: 1px solid #F1F5F9;">
                            <td style="padding: 20px 28px; font-size: 14px; font-weight: 700; color: #1E293B;">Increased recruiter visibility</td>
                            <td style="padding: 20px 16px; color: #CBD5E1; text-align: center;">—</td>
                            <td style="padding: 20px 16px; text-align: center;">
                                <span style="color: #4F48E6; font-size: 18px; font-weight: 800;">✓</span>
                            </td>
                            <td style="padding: 20px 28px; text-align: center;">
                                <span style="font-size: 24px; font-weight: 700; color: #000623; line-height: 1;">∞</span>
                            </td>
                        </tr>
                        <!-- Row 5: Better internship recommendations -->
                        <tr style="border-bottom: 1px solid #F1F5F9;">
                            <td style="padding: 20px 28px; font-size: 14px; font-weight: 700; color: #1E293B;">Better internship recommendations</td>
                            <td style="padding: 20px 16px; font-size: 14px; color: #94A3B8; text-align: center;">Basic</td>
                            <td style="padding: 20px 16px; text-align: center;">
                                <span style="color: #4F48E6; font-size: 18px; font-weight: 800;">✓</span>
                            </td>
                            <td style="padding: 20px 28px; text-align: center;">
                                <span style="font-size: 24px; font-weight: 700; color: #000623; line-height: 1;">∞</span>
                            </td>
                        </tr>
                        <!-- Row 6: CV & application assistance -->
                        <tr style="border-bottom: 1px solid #F1F5F9;">
                            <td style="padding: 20px 28px; font-size: 14px; font-weight: 700; color: #1E293B;">CV &amp; application assistance</td>
                            <td style="padding: 20px 16px; color: #CBD5E1; text-align: center;">—</td>
                            <td style="padding: 20px 16px; text-align: center;">
                                <span style="color: #4F48E6; font-size: 18px; font-weight: 800;">✓</span>
                            </td>
                            <td style="padding: 20px 28px; text-align: center;">
                                <span style="font-size: 24px; font-weight: 700; color: #000623; line-height: 1;">∞</span>
                            </td>
                        </tr>
                        <!-- Row 7: AI Interview Prep -->
                        <tr style="border-bottom: 1px solid #F1F5F9;">
                            <td style="padding: 20px 28px; font-size: 14px; font-weight: 700; color: #1E293B;">AI Interview Prep</td>
                            <td style="padding: 20px 16px; color: #CBD5E1; text-align: center;">—</td>
                            <td style="padding: 20px 16px; color: #CBD5E1; text-align: center;">—</td>
                            <td style="padding: 20px 28px; text-align: center;">
                                <span style="font-size: 24px; font-weight: 700; color: #000623; line-height: 1;">∞</span>
                            </td>
                        </tr>
                        <!-- Row 8: Advanced career insight -->
                        <tr>
                            <td style="padding: 20px 28px; font-size: 14px; font-weight: 700; color: #1E293B;">Advanced career insight</td>
                            <td style="padding: 20px 16px; color: #CBD5E1; text-align: center;">—</td>
                            <td style="padding: 20px 16px; color: #CBD5E1; text-align: center;">—</td>
                            <td style="padding: 20px 28px; text-align: center;">
                                <span style="font-size: 24px; font-weight: 700; color: #000623; line-height: 1;">∞</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<!-- 5. Features Showcase Section (Woman Working Focused + Staggered Overlapping AI Feature Cards) -->
<section id="features-showcase-section" class="features-showcase-section">
    <div class="features-showcase-container">
        <div class="features-showcase-wrapper">
            
            <!-- Left Side: Image Showcase -->
            <div class="features-showcase-image-col">
                <div class="features-showcase-image-box">
                    <img src="https://res.cloudinary.com/weburea/image/upload/v1788539822/Woman_working_focused_sqvdeg.png" 
                         alt="Candidate working focused with InternConnect AI" 
                         class="features-showcase-img"
                         loading="lazy">
                </div>
            </div>

            <!-- Right Side: Overlapping & Offset Floating Cards -->
            <div class="features-showcase-cards-col">
                
                <!-- Card 1: AI Recommendations (Overlaps the top-right of the image) -->
                <div class="showcase-floating-card feature-card-overlap">
                    <div class="showcase-card-header">
                        <svg class="showcase-card-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 00-2.456 2.456zM16.894 20.567L16.5 21.75l-.394-1.183a2.25 2.25 0 00-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 001.423-1.423l.394-1.183.394 1.183a2.25 2.25 0 001.423 1.423l1.183.394-1.183.394a2.25 2.25 0 00-1.423 1.423z" />
                        </svg>
                        <h4 class="showcase-card-title">AI Recommendations</h4>
                    </div>
                    <p class="showcase-card-desc">
                        Our matching algorithm finds the perfect roles tailored to your unique skills and ambitions before they even hit the main job boards.
                    </p>
                </div>

                <!-- Card 2: Resume Optimization (Offset to the right, outside image) -->
                <div class="showcase-floating-card feature-card-offset">
                    <div class="showcase-card-header">
                        <svg class="showcase-card-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <h4 class="showcase-card-title">Resume Optimization</h4>
                    </div>
                    <p class="showcase-card-desc">
                        Get feedback on your CV based on what top recruiters in your industry are actively looking for right now.
                    </p>
                </div>

                <!-- Card 3: Interview Preparation (Overlaps the bottom-right of the image) -->
                <div class="showcase-floating-card feature-card-overlap">
                    <div class="showcase-card-header">
                        <svg class="showcase-card-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z" />
                        </svg>
                        <h4 class="showcase-card-title">Interview Preparation</h4>
                    </div>
                    <p class="showcase-card-desc">
                        Practice with AI-driven mock sessions and receive scored feedback on answers and delivery.
                    </p>
                </div>

                <!-- Card 4: Priority Application (Offset to the right, outside image) -->
                <div class="showcase-floating-card feature-card-offset">
                    <div class="showcase-card-header">
                        <svg class="showcase-card-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                        <h4 class="showcase-card-title">Priority Application</h4>
                    </div>
                    <p class="showcase-card-desc">
                        Skip the queue. Your application goes straight to the top of the pile for our partner companies.
                    </p>
                </div>

            </div>

        </div>
    </div>
</section>

<!-- 6. Statistics Banner Section (Vibrant Primary Purple #4F48E6 with generous 64px vertical padding) -->
<section style="background-color: #4F48E6; padding: 64px 24px; color: #FFFFFF;">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-8 text-center">
            <div>
                <div style="font-size: 48px; font-weight: 800; line-height: 1.1; margin-bottom: 8px; color: #FFFFFF;">90%</div>
                <div style="font-size: 11px; font-weight: 700; letter-spacing: 0.1em; text-transform: uppercase; color: #E0E7FF;">PROFILE COMPLETION RATE</div>
            </div>
            <div>
                <div style="font-size: 48px; font-weight: 800; line-height: 1.1; margin-bottom: 8px; color: #FFFFFF;">10K+</div>
                <div style="font-size: 11px; font-weight: 700; letter-spacing: 0.1em; text-transform: uppercase; color: #E0E7FF;">APPLICATIONS SUBMITTED</div>
            </div>
            <div>
                <div style="font-size: 48px; font-weight: 800; line-height: 1.1; margin-bottom: 8px; color: #FFFFFF;">500+</div>
                <div style="font-size: 11px; font-weight: 700; letter-spacing: 0.1em; text-transform: uppercase; color: #E0E7FF;">PARTNER COMPANIES</div>
            </div>
            <div>
                <div style="font-size: 48px; font-weight: 800; line-height: 1.1; margin-bottom: 8px; color: #FFFFFF;">85%</div>
                <div style="font-size: 11px; font-weight: 700; letter-spacing: 0.1em; text-transform: uppercase; color: #E0E7FF;">USER SATISFACTION</div>
            </div>
        </div>
    </div>
</section>

<!-- 7. Real Results / Testimonials Section (Embedded Cloudinary Avatars) -->
<section id="results-testimonials-section" class="py-16 md:py-20 bg-white border-t border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center max-w-3xl mx-auto mb-12 flex flex-col space-y-3">
            <div style="color: #4F48E6; font-size: 13px; font-weight: 800; letter-spacing: 0.1em; text-transform: uppercase; text-align: center;">REAL RESULTS</div>
            <h2 style="color: #0F172A; font-size: 40px; font-weight: 800; text-align: center; letter-spacing: -0.02em;">
                Candidates Who Made the Leap
            </h2>
        </div>

        <!-- Grid of 3 Testimonials -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-stretch max-w-6xl mx-auto">
            <!-- Testimonial 1: Aisha Okonkwo -->
            <div style="background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 16px; padding: 32px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.03); display: flex; flex-direction: column; justify-content: space-between;">
                <div class="space-y-4">
                    <!-- 5 Yellow Stars -->
                    <div class="flex text-amber-400 space-x-1">
                        <?php for ($i = 0; $i < 5; $i++): ?>
                            <svg class="h-4 w-4 fill-current" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                        <?php endfor; ?>
                    </div>
                    <!-- Quote -->
                    <p style="font-size: 14px; color: #475569; font-style: italic; line-height: 1.6;">
                        "The AI recommendations helped me discover opportunities I would have completely missed. I landed my dream internship at a startup I'd never heard of."
                    </p>
                </div>
                <!-- Author Info with Cloudinary Avatar -->
                <div class="mt-8 flex items-center space-x-3">
                    <img src="https://res.cloudinary.com/weburea/image/upload/v1787643431/Aisha_Okonkwo_o7nntq.png"
                        alt="Aisha Okonkwo" class="w-10 h-10 rounded-full object-cover border border-gray-100 flex-shrink-0">
                    <div>
                        <h4 style="font-size: 14px; font-weight: 700; color: #0F172A;">Aisha Okonkwo</h4>
                        <p style="font-size: 12px; color: #94A3B8;">Software Engineering Intern, Google</p>
                    </div>
                </div>
            </div>

            <!-- Testimonial 2: Daniel Silva (Highlighted Card) -->
            <div style="background: #F2F3FF; border: 2px solid #4F48E6; border-radius: 16px; padding: 32px; box-shadow: 0 4px 14px rgba(79, 72, 230, 0.1); display: flex; flex-direction: column; justify-content: space-between; position: relative;">
                <div class="space-y-4">
                    <!-- 5 Yellow Stars -->
                    <div class="flex text-amber-400 space-x-1">
                        <?php for ($i = 0; $i < 5; $i++): ?>
                            <svg class="h-4 w-4 fill-current" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                        <?php endfor; ?>
                    </div>
                    <!-- Quote -->
                    <p style="font-size: 14px; color: #1E293B; font-style: italic; line-height: 1.6; font-weight: 500;">
                        "The resume tool flagged exactly what was holding my applications back. Within two weeks of switching to Pro, I got three interview invites."
                    </p>
                </div>
                <!-- Author Info with Cloudinary Avatar -->
                <div class="mt-8 flex items-center space-x-3">
                    <img src="https://res.cloudinary.com/weburea/image/upload/v1787643474/Daniel_Silva_aluzlk.png"
                        alt="Daniel Silva" class="w-10 h-10 rounded-full object-cover border border-white flex-shrink-0">
                    <div>
                        <h4 style="font-size: 14px; font-weight: 700; color: #0F172A;">Daniel Silva</h4>
                        <p style="font-size: 12px; color: #64748B;">Product Design Intern, Meta</p>
                    </div>
                </div>
            </div>

            <!-- Testimonial 3: Yemi Philips -->
            <div style="background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 16px; padding: 32px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.03); display: flex; flex-direction: column; justify-content: space-between;">
                <div class="space-y-4">
                    <!-- 5 Yellow Stars -->
                    <div class="flex text-amber-400 space-x-1">
                        <?php for ($i = 0; $i < 5; $i++): ?>
                            <svg class="h-4 w-4 fill-current" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                        <?php endfor; ?>
                    </div>
                    <!-- Quote -->
                    <p style="font-size: 14px; color: #475569; font-style: italic; line-height: 1.6;">
                        "Mock interview mode is genuinely scary-good. It asked follow-ups, caught my weak spots, and the feedback was more useful than anything a career advisor gave me."
                    </p>
                </div>
                <!-- Author Info with Cloudinary Avatar -->
                <div class="mt-8 flex items-center space-x-3">
                    <img src="https://res.cloudinary.com/weburea/image/upload/v1787643474/Yemi_Philips_he7mnx.png"
                        alt="Yemi Philips" class="w-10 h-10 rounded-full object-cover border border-gray-100 flex-shrink-0">
                    <div>
                        <h4 style="font-size: 14px; font-weight: 700; color: #0F172A;">Yemi Philips</h4>
                        <p style="font-size: 12px; color: #94A3B8;">Finance Intern, Goldman Sachs</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 8. Call to Action (CTA) Section with Background Image & Dark Overlay -->
<section id="cta-section" class="pricing-cta-section">
    <div class="pricing-cta-container">
        <h2 class="pricing-cta-title">
            Ready to Find Your Next Opportunity?
        </h2>
        <p class="pricing-cta-desc">
            Join thousands of candidates using InternConnect to discover and secure internships faster than ever before.
        </p>
        <div>
            <a href="<?= url('/register') ?>" class="pricing-cta-btn">
                Get Started For Free
            </a>
        </div>
    </div>
</section>
