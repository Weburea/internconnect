<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Services\BillingService;
use App\Services\PaystackService;

class PaymentController extends Controller
{
    private PaystackService $paystack;

    public function __construct()
    {
        $this->paystack = new PaystackService();
    }

    /**
     * View: 2-Column Checkout / Upgrade Selection View
     */
    public function checkout(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $plan = $_GET['plan'] ?? 'pro';
        $cycle = $_GET['cycle'] ?? 'monthly';
        $account = BillingService::account((string)($_GET['account'] ?? ''));

        $this->renderView('landing/payment', [
            'title'   => 'Upgrade Plan - InternConnect',
            'plan'    => htmlspecialchars((string)$plan),
            'cycle'   => htmlspecialchars((string)$cycle),
            'account' => $account['type'],
        ]);
    }

    /**
     * API / Web: Initialize Paystack Payment Transaction
     */
    public function initialize(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Support both JSON body and standard Form POST
        $rawInput = file_get_contents('php://input');
        $jsonData = json_decode($rawInput, true);

        $plan  = $jsonData['plan'] ?? $_POST['plan'] ?? 'pro';
        $cycle = $jsonData['cycle'] ?? $_POST['cycle'] ?? 'monthly';
        $email = $jsonData['email'] ?? $_POST['email'] ?? ($_SESSION['profile_email'] ?? 'sarah.peters@example.com');

        $plan = strtolower(trim((string)$plan));
        $cycle = strtolower(trim((string)$cycle)) === 'annual' ? 'annual' : 'monthly';
        $account = BillingService::account((string)($jsonData['account'] ?? $_POST['account'] ?? ''));
        if ($account['type'] === 'employer' && empty($jsonData['email']) && empty($_POST['email'])) {
            $email = $_SESSION['employer_email'] ?? $email;
        }

        // Compute authoritative server-side amount in kobo
        $amountInKobo = PaystackService::getAmountInKobo($plan, $cycle);

        if ($amountInKobo === null) {
            $this->jsonResponse([
                'status'  => false,
                'message' => 'Invalid subscription plan or billing cycle specified.',
            ], 400);
            return;
        }

        // Generate unique transaction reference
        $reference = 'IC_' . strtoupper($plan) . '_' . strtoupper(substr($cycle, 0, 1)) . '_' . time() . '_' . bin2hex(random_bytes(3));
        $callbackUrl = absolute_url('/payment/callback');

        $metadata = [
            'plan'       => $plan,
            'cycle'      => $cycle,
            'user_name'  => $_SESSION['profile_name'] ?? 'Sarah Peters',
            'user_email' => $email,
            'initiated'  => date('c'),
            // Read back by the webhook to file the payment under the right account.
            'account_type' => $account['type'],
            'account_id'   => $account['id'],
        ];

        // Store pending transaction in session for verification cross-checking
        $_SESSION['pending_payment'] = [
            'reference'      => $reference,
            'plan'           => $plan,
            'cycle'          => $cycle,
            'amount_kobo'    => $amountInKobo,
            'email'          => $email,
            'account'        => $account['type'],
            'created_at'     => time(),
        ];
        BillingService::recordPending($account, $reference, $plan, $cycle, $amountInKobo, (string)$email);

        $result = $this->paystack->initializeTransaction(
            email: $email,
            amountInKobo: $amountInKobo,
            callbackUrl: $callbackUrl,
            metadata: $metadata,
            reference: $reference
        );

        if (!empty($result['status']) && !empty($result['data']['authorization_url'])) {
            $this->jsonResponse([
                'status'  => true,
                'message' => $result['message'] ?? 'Authorization URL created successfully',
                'data'    => [
                    'authorization_url' => $result['data']['authorization_url'],
                    'access_code'       => $result['data']['access_code'] ?? '',
                    'reference'         => $reference,
                    'amount_kobo'       => $amountInKobo,
                    'currency'          => 'NGN',
                    'plan'              => $plan,
                    'cycle'             => $cycle,
                ],
            ], 200);
        } else {
            $this->jsonResponse([
                'status'  => false,
                'message' => $result['message'] ?? 'Failed to initialize payment transaction with Paystack.',
            ], 502);
        }
    }

    /**
     * Web: Callback Redirect Handler after Customer Completes / Cancels Payment
     */
    public function callback(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $reference = $_GET['reference'] ?? $_GET['trxref'] ?? '';
        $cancelled = isset($_GET['cancelled']) || ($_GET['status'] ?? '') === 'cancelled';

        $pending = $_SESSION['pending_payment'] ?? [];
        $plan = $pending['plan'] ?? ($_GET['plan'] ?? 'pro');
        $cycle = $pending['cycle'] ?? ($_GET['cycle'] ?? 'monthly');
        $amount = (int)round(($pending['amount_kobo'] ?? 249900) / 100);

        if ($cancelled) {
            $this->renderView('landing/payment-cancelled', [
                'title'     => 'Payment Cancelled - InternConnect',
                'reference' => htmlspecialchars((string)$reference),
                'plan'      => htmlspecialchars((string)$plan),
                'cycle'     => htmlspecialchars((string)$cycle),
                'amount'    => $amount,
                'method'    => 'Paystack',
            ]);
            return;
        }

        if (empty($reference)) {
            $this->renderView('landing/payment-declined', [
                'title'     => 'Payment Declined - InternConnect',
                'plan'      => htmlspecialchars((string)$plan),
                'cycle'     => htmlspecialchars((string)$cycle),
                'amount'    => $amount,
                'reason'    => 'No transaction reference was returned by the payment gateway.',
            ]);
            return;
        }

        // Verify transaction with Paystack official API
        $verifyResult = $this->paystack->verifyTransaction((string)$reference);

        if (!empty($verifyResult['status']) && isset($verifyResult['data'])) {
            $data = $verifyResult['data'];
            $status = strtolower($data['status'] ?? '');
            $metadata = $data['metadata'] ?? [];
            $plan = $metadata['plan'] ?? $plan;
            $cycle = $metadata['cycle'] ?? $cycle;
            $paidAmountKobo = (int)($data['amount'] ?? 249900);
            $amount = (int)round($paidAmountKobo / 100);
            // Billing history + subscription (amount is checked against the plan price).
            $stored = BillingService::applyPaystackResult($data);
            if ($stored) {
                $status = (string)$stored['status'];
            }
            $billingUrl = url((($stored['account_type'] ?? ($pending['account'] ?? 'candidate')) === 'employer' ? '/employer' : '/candidate') . '/billing');

            if ($status === 'success') {
                // Activate subscription in session
                $_SESSION['subscription_plan']      = $plan;
                $_SESSION['subscription_cycle']     = $cycle;
                $_SESSION['subscription_status']    = 'active';
                $_SESSION['subscription_reference'] = $reference;
                $_SESSION['subscription_amount']   = $amount;
                $_SESSION['subscription_activated'] = date('Y-m-d H:i:s');
                $_SESSION['is_logged_in']           = true;

                unset($_SESSION['pending_payment']);

                $this->renderView('landing/payment-success', [
                    'title'      => 'Payment Successful - InternConnect',
                    'reference'  => htmlspecialchars((string)$reference),
                    'plan'       => htmlspecialchars((string)$plan),
                    'cycle'      => htmlspecialchars((string)$cycle),
                    'amount'     => $amount,
                    'billingUrl' => $billingUrl,
                ]);
                return;
            } elseif ($status === 'abandoned') {
                $this->renderView('landing/payment-cancelled', [
                    'title'     => 'Payment Cancelled - InternConnect',
                    'reference' => htmlspecialchars((string)$reference),
                    'plan'      => htmlspecialchars((string)$plan),
                    'cycle'     => htmlspecialchars((string)$cycle),
                    'amount'    => $amount,
                    'method'    => 'Paystack',
                ]);
                return;
            } else {
                $gatewayReason = $data['gateway_response'] ?? ($verifyResult['message'] ?? 'Card declined by issuing bank');
                $this->renderView('landing/payment-declined', [
                    'title'     => 'Payment Declined - InternConnect',
                    'reference' => htmlspecialchars((string)$reference),
                    'plan'      => htmlspecialchars((string)$plan),
                    'cycle'     => htmlspecialchars((string)$cycle),
                    'amount'    => $amount,
                    'reason'    => htmlspecialchars((string)$gatewayReason),
                ]);
                return;
            }
        }

        // Fallback for failed verification
        $errorMessage = $verifyResult['message'] ?? 'Card declined by issuing bank';
        $this->renderView('landing/payment-declined', [
            'title'     => 'Payment Declined - InternConnect',
            'reference' => htmlspecialchars((string)$reference),
            'plan'      => htmlspecialchars((string)$plan),
            'cycle'     => htmlspecialchars((string)$cycle),
            'amount'    => $amount,
            'reason'    => htmlspecialchars((string)$errorMessage),
        ]);
    }

    /**
     * Preview / Direct Route: Payment Successful Screen
     */
    public function success(): void
    {
        $plan = $_GET['plan'] ?? 'pro';
        $cycle = $_GET['cycle'] ?? 'monthly';
        $amount = (int)round((PaystackService::getAmountInKobo($plan, $cycle) ?? 249900) / 100);

        $this->renderView('landing/payment-success', [
            'title'     => 'Payment Successful - InternConnect',
            'reference' => htmlspecialchars((string)($_GET['reference'] ?? 'IC_TEST_SUCCESS_'.time())),
            'plan'      => htmlspecialchars((string)$plan),
            'cycle'     => htmlspecialchars((string)$cycle),
            'amount'    => $amount,
        ]);
    }

    /**
     * Preview / Direct Route: Payment Declined Screen
     */
    public function declined(): void
    {
        $plan = $_GET['plan'] ?? 'pro';
        $cycle = $_GET['cycle'] ?? 'monthly';
        $amount = (int)round((PaystackService::getAmountInKobo($plan, $cycle) ?? 249900) / 100);
        $reason = $_GET['reason'] ?? 'Card declined by issuing bank';

        $this->renderView('landing/payment-declined', [
            'title'     => 'Payment Declined - InternConnect',
            'reference' => htmlspecialchars((string)($_GET['reference'] ?? 'IC_TEST_DECLINED_'.time())),
            'plan'      => htmlspecialchars((string)$plan),
            'cycle'     => htmlspecialchars((string)$cycle),
            'amount'    => $amount,
            'reason'    => htmlspecialchars((string)$reason),
        ]);
    }

    /**
     * Preview / Direct Route: Payment Cancelled Screen
     */
    public function cancelled(): void
    {
        $plan = $_GET['plan'] ?? 'pro';
        $cycle = $_GET['cycle'] ?? 'monthly';
        $amount = (int)round((PaystackService::getAmountInKobo($plan, $cycle) ?? 249900) / 100);

        $this->renderView('landing/payment-cancelled', [
            'title'     => 'Payment Cancelled - InternConnect',
            'reference' => htmlspecialchars((string)($_GET['reference'] ?? 'IC_TEST_CANCELLED_'.time())),
            'plan'      => htmlspecialchars((string)$plan),
            'cycle'     => htmlspecialchars((string)$cycle),
            'amount'    => $amount,
            'method'    => 'Paystack',
        ]);
    }

    /**
     * Webhook: Asynchronous Webhook Event Ingestion
     */
    public function webhook(): void
    {
        $rawPayload = (string)file_get_contents('php://input');
        $signature = $_SERVER['HTTP_X_PAYSTACK_SIGNATURE'] ?? '';

        // Enforce cryptographic HMAC SHA512 signature validation
        if (!$this->paystack->verifyWebhookSignature($rawPayload, $signature)) {
            http_response_code(401);
            echo json_encode(['status' => 'error', 'message' => 'Invalid Paystack signature']);
            exit;
        }

        $event = json_decode($rawPayload, true);
        $eventType = $event['event'] ?? '';

        // Process Paystack Webhook Event
        switch ($eventType) {
            case 'charge.success':
                $data = $event['data'] ?? [];
                $reference = $data['reference'] ?? '';
                $customerEmail = $data['customer']['email'] ?? '';
                // Signature already verified above; the same reference may also arrive through the callback.
                BillingService::applyPaystackResult($data + ['status' => 'success']);
                error_log("Paystack Webhook [charge.success]: Verified {$reference} for {$customerEmail}");
                break;

            case 'subscription.create':
            case 'subscription.enable':
                // Recurring subscription activated
                break;

            case 'subscription.disable':
            case 'invoice.payment_failed':
                // Handle renewal failure
                break;
        }

        http_response_code(200);
        echo json_encode(['status' => 'success', 'message' => 'Webhook received and processed']);
        exit;
    }
}
