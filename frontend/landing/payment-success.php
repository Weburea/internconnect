<?php

declare(strict_types=1);

/**
 * View: Payment Successful Screen
 * Route: /payment/success or /payment/callback
 * Layout: app
 */

$planKey = strtolower($plan ?? 'pro');
$planName = $planKey === 'premium' ? 'Premium' : 'Pro';
$cycleKey = strtolower($cycle ?? 'monthly');
$cycleName = $cycleKey === 'annual' ? 'Annual' : 'Monthly';
$formattedAmount = number_format((float)($amount ?? ($cycleKey === 'annual' ? ($planKey === 'premium' ? 47990 : 24999) : ($planKey === 'premium' ? 4999 : 2499))));
$renewalDate = date('d M Y', strtotime($cycleKey === 'annual' ? '+1 year' : '+1 month'));
?>

<div class="payment-result-wrapper">
    <div class="payment-result-container">
        <div class="payment-result-grid">
            
            <!-- Left Column: Photography Card -->
            <div class="payment-result-visual-col">
                <img src="https://res.cloudinary.com/weburea/image/upload/v1788574692/pricing_success_n0cbzk.png" 
                     alt="Payment Successful - InternConnect" 
                     class="payment-result-img" />
            </div>

            <!-- Right Column: Status & Details -->
            <div class="payment-result-content-col">
                
                <!-- Status Icon Badge (Blue Circle with Checkmark) -->
                <div class="payment-status-icon-badge success" aria-hidden="true">
                    <svg class="payment-status-icon-svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                    </svg>
                </div>

                <!-- Headings -->
                <div>
                    <h1 class="payment-result-title">Payment Successful</h1>
                    <p class="payment-result-subtitle">
                        Your account is upgraded. You are now on <span class="payment-plan-highlight"><?= htmlspecialchars($planName) ?></span> plan
                    </p>
                </div>

                <!-- Receipt / Detail Box -->
                <div class="payment-result-card">
                    <div class="payment-result-row">
                        <span class="payment-result-label">Plan activated</span>
                        <span class="payment-result-value"><span class="payment-val-pill"><?= htmlspecialchars($planName) ?></span></span>
                    </div>
                    <div class="payment-result-row">
                        <span class="payment-result-label">Amount paid</span>
                        <span class="payment-result-value payment-val-price">₦<?= $formattedAmount ?></span>
                    </div>
                    <div class="payment-result-row">
                        <span class="payment-result-label">Billing frequency</span>
                        <span class="payment-result-value payment-val-cycle"><?= htmlspecialchars($cycleName) ?></span>
                    </div>
                    <div class="payment-result-row">
                        <span class="payment-result-label">Next renewal</span>
                        <span class="payment-result-value payment-val-date"><?= $renewalDate ?></span>
                    </div>
                </div>

                <!-- Action Button -->
                <div class="payment-result-actions">
                    <a href="<?= url('/candidate/dashboard') ?>" class="payment-result-btn-primary">
                        <span>Go to Dashboard</span>
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </a>
                </div>

            </div>

        </div>
    </div>
</div>
