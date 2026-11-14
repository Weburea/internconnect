<?php

declare(strict_types=1);

/**
 * View: Payment & Upgrade Selection Page
 * Route: /payments
 * Layout: app
 */

$selectedPlan = strtolower($plan ?? 'pro');
?>

<div class="checkout-page-wrapper" x-data="{ 
    cycle: '<?= htmlspecialchars($cycle ?? 'monthly') ?>', 
    plan: '<?= $selectedPlan ?>', 
    isRedirecting: false,
    errorMessage: '',
    async startCheckout() {
        this.errorMessage = '';
        this.isRedirecting = true;
        
        try {
            const response = await fetch('<?= url('/payment/initialize') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    plan: this.plan,
                    cycle: this.cycle,
                    account: '<?= ($account ?? 'candidate') === 'employer' ? 'employer' : 'candidate' ?>'
                })
            });
            
            const result = await response.json();
            
            if (result.status && result.data && result.data.authorization_url) {
                // Short timeout for seamless visual transition on the modal
                setTimeout(() => {
                    window.location.href = result.data.authorization_url;
                }, 800);
            } else {
                this.isRedirecting = false;
                this.errorMessage = result.message || 'Unable to start checkout with Paystack. Please try again.';
            }
        } catch (err) {
            this.isRedirecting = false;
            this.errorMessage = 'Network error: Failed to connect to payment gateway.';
            console.error('Payment initialization error:', err);
        }
    }
}">
    <div class="checkout-container">
        <div class="checkout-grid">
            
            <!-- Left Column: Visual Image Card -->
            <div class="checkout-visual-col">
                <div class="checkout-visual-overlay"></div>
                <div class="checkout-visual-content">
                    <h2 class="checkout-visual-title">Invest in your potential.</h2>
                    <p class="checkout-visual-desc">
                        Join thousands of students securing top-tier internships through our advanced matching and preparation tools.
                    </p>
                </div>
            </div>

            <!-- Right Column: Upgrade Selection Card -->
            <div class="checkout-card-col">
                <div class="checkout-card">
                    <!-- Error Notification Alert -->
                    <div x-show="errorMessage" 
                         x-cloak
                         class="mb-6 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-sm flex items-center gap-3">
                        <svg class="w-5 h-5 flex-shrink-0 text-rose-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                        <span x-text="errorMessage"></span>
                    </div>

                    <!-- Card Header with Icon -->
                    <div class="checkout-card-header">
                        <div class="checkout-badge-icon" aria-hidden="true">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <h1 class="checkout-card-title">
                            Elevate Your Career with <span x-text="plan === 'premium' ? 'Premium' : 'Pro'">Pro</span>
                        </h1>
                        <p class="checkout-card-subtitle">
                            Confirm your billing cycle to unlock premium features and accelerate your internship search.
                        </p>
                    </div>

                    <!-- Billing Cycle Radio Cards -->
                    <div class="checkout-billing-options">
                        <!-- Monthly Card -->
                        <div @click="cycle = 'monthly'" 
                             class="checkout-option-card" 
                             :class="{ 'active': cycle === 'monthly' }"
                             role="radio" 
                             :aria-checked="cycle === 'monthly'" 
                             tabindex="0">
                            <div class="checkout-option-info">
                                <span class="checkout-option-label">Monthly</span>
                                <span class="checkout-option-price" x-text="plan === 'premium' ? '₦4,999' : '₦2,499'">₦2,499</span>
                                <span class="checkout-option-sub">Billed every month</span>
                            </div>
                            <div class="checkout-radio-indicator" :class="{ 'active': cycle === 'monthly' }">
                                <div class="checkout-radio-dot" x-show="cycle === 'monthly'"></div>
                            </div>
                        </div>

                        <!-- Annual Card (with Best Value Badge) -->
                        <div @click="cycle = 'annual'" 
                             class="checkout-option-card annual-card" 
                             :class="{ 'active': cycle === 'annual' }"
                             role="radio" 
                             :aria-checked="cycle === 'annual'" 
                             tabindex="0">
                            <!-- Best Value Badge -->
                            <span class="checkout-best-value-badge">Best Value</span>
                            
                            <div class="checkout-option-info">
                                <span class="checkout-option-label">Annual</span>
                                <span class="checkout-option-price" x-text="plan === 'premium' ? '₦47,990' : '₦24,999'">₦24,999</span>
                                <span class="checkout-option-savings" x-text="plan === 'premium' ? 'Save ₦11,998/yr' : 'Save ₦4,989/yr'">Save ₦4,989/yr</span>
                            </div>
                            <div class="checkout-radio-indicator" :class="{ 'active': cycle === 'annual' }">
                                <div class="checkout-radio-dot" x-show="cycle === 'annual'"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Features Checkpoints -->
                    <div class="checkout-features-list">
                        <div class="checkout-feature-item">
                            <svg class="checkout-check-svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>AI-powered CV and application assistance</span>
                        </div>
                        <div class="checkout-feature-item">
                            <svg class="checkout-check-svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>Enhanced recruiter visibility</span>
                        </div>
                        <div class="checkout-feature-item">
                            <svg class="checkout-check-svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>Smarter internship recommendations</span>
                        </div>
                    </div>

                    <!-- Subscription Disclaimer -->
                    <div class="checkout-disclaimer">
                        <p>
                            Your plan runs for the billing cycle you choose. Renew or change it any time from Billing & Subscription.
                        </p>
                    </div>

                    <!-- Action Buttons -->
                    <div class="checkout-actions">
                        <button @click="startCheckout()" type="button" class="checkout-btn-primary">
                            Continue to payment
                        </button>
                        <a href="<?= url('/pricing') ?>" class="checkout-btn-secondary">
                            Back to plans
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Paystack Redirection Popup Modal -->
    <div x-show="isRedirecting" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="paystack-modal-backdrop" 
         style="display: none;" 
         role="dialog" 
         aria-modal="true">
        
        <div class="paystack-modal-card"
             x-show="isRedirecting"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 transform scale-95"
             x-transition:enter-end="opacity-100 transform scale-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 transform scale-100"
             x-transition:leave-end="opacity-0 transform scale-95"
             @click.away="isRedirecting = false">
            
            <!-- Payment Icon Badge (replacing plain lock with payment icon) -->
            <div class="paystack-redirect-icon-badge" aria-hidden="true">
                <svg class="paystack-payment-svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                </svg>
            </div>

            <!-- Title & Subtitle -->
            <h3 class="paystack-redirect-title">Redirecting you to Paystack</h3>
            <p class="paystack-redirect-desc">
                You’re being securely redirected to Paystack to complete your payment.
            </p>

            <!-- Secured by Paystack Pill -->
            <div class="paystack-secured-pill">
                <svg class="paystack-shield-svg" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 1.944A11.954 11.954 0 012.166 5C2.056 5.649 2 6.319 2 7c0 5.225 3.34 9.67 8 11.317C14.66 16.67 18 12.225 18 7c0-.682-.057-1.35-.166-2.001A11.954 11.954 0 0110 1.944zM11 14a1 1 0 11-2 0 1 1 0 012 0zm0-7a1 1 0 10-2 0v3a1 1 0 102 0V7z" clip-rule="evenodd" />
                </svg>
                <span>Secured by Paystack</span>
            </div>

            <!-- Trust / Encryption Badges -->
            <div class="paystack-trust-badges">
                <div class="paystack-trust-item">
                    <svg class="paystack-trust-svg" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                    </svg>
                    <span>256-bit encryption</span>
                </div>
                <div class="paystack-trust-item">
                    <svg class="paystack-trust-svg" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10.496 2.132a1 1 0 00-.992 0l-7 4A1 1 0 002 7v1a1 1 0 001 1h14a1 1 0 001-1V7a1 1 0 00-.504-.868l-7-4zM4 11a1 1 0 011 1v4a1 1 0 11-2 0v-4a1 1 0 011-1zm4 0a1 1 0 011 1v4a1 1 0 11-2 0v-4a1 1 0 011-1zm4 0a1 1 0 011 1v4a1 1 0 11-2 0v-4a1 1 0 011-1zm4 0a1 1 0 011 1v4a1 1 0 11-2 0v-4a1 1 0 011-1zM2 19a1 1 0 011-1h14a1 1 0 110 2H3a1 1 0 01-1-1z" clip-rule="evenodd" />
                    </svg>
                    <span>PCI-DSS compliant</span>
                </div>
            </div>

            <!-- Animated Loading Progress Track -->
            <div class="paystack-progress-track">
                <div class="paystack-progress-bar"></div>
            </div>
        </div>
    </div>
</div>

