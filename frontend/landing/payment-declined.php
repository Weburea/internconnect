<?php

declare(strict_types=1);

/**
 * View: Payment Declined Screen
 * Route: /payment/declined
 * Layout: app
 */

$planKey = strtolower($plan ?? 'pro');
$planName = $planKey === 'premium' ? 'Premium' : 'Pro';
$cycleKey = strtolower($cycle ?? 'monthly');
$cycleName = $cycleKey === 'annual' ? 'Annual' : 'Monthly';
$formattedAmount = number_format((float)($amount ?? ($cycleKey === 'annual' ? ($planKey === 'premium' ? 47990 : 24999) : ($planKey === 'premium' ? 4999 : 2499))));
$declineReason = $reason ?? 'Card declined by issuing bank';
?>

<div class="payment-result-wrapper">
    <div class="payment-result-container">
        <div class="payment-result-grid">
            
            <!-- Left Column: Photography Card -->
            <div class="payment-result-visual-col">
                <img src="https://res.cloudinary.com/weburea/image/upload/v1788574693/payment_declined_jyso8t.png" 
                     alt="Payment Declined - InternConnect" 
                     class="payment-result-img" />
            </div>

            <!-- Right Column: Status & Details -->
            <div class="payment-result-content-col">
                
                <!-- Status Icon Badge (Red Circle with 'X') -->
                <div class="payment-status-icon-badge error" aria-hidden="true">
                    <svg class="payment-status-icon-svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </div>

                <!-- Headings -->
                <div>
                    <h1 class="payment-result-title">Payment Declined</h1>
                    <p class="payment-result-subtitle">
                        Your payment could not be completed. You have not been charged.
                    </p>
                </div>

                <!-- Receipt / Detail Box -->
                <div class="payment-result-card">
                    <div class="payment-result-row">
                        <span class="payment-result-label">Plan</span>
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
                        <span class="payment-result-label">Reason</span>
                        <span class="payment-result-value payment-val-danger payment-val-reason"><?= htmlspecialchars($declineReason) ?></span>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="payment-result-actions">
                    <a href="<?= url('/payments?plan=' . $planKey . '&cycle=' . $cycleKey) ?>" class="payment-result-btn-primary">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        <span>Try Again</span>
                    </a>
                    <a href="<?= url('/pricing') ?>" class="payment-result-btn-secondary">
                        <span>Choose another plan</span>
                    </a>
                </div>

            </div>

        </div>
    </div>
</div>
