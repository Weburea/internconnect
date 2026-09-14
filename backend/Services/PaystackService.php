<?php

declare(strict_types=1);

namespace App\Services;

/**
 * Paystack Payment Gateway Service
 * Handles API communication with Paystack (Test & Live modes)
 */
class PaystackService
{
    private string $secretKey;
    private string $publicKey;
    private string $baseUrl;

    /**
     * Immutable Server-side Pricing Ledger (in Kobo: 1 NGN = 100 kobo)
     */
    public const PRICING_LEDGER = [
        'pro' => [
            'name'           => 'Pro Plan',
            'monthly_kobo'   => 249900,  // ₦2,499.00
            'annual_kobo'    => 2499900, // ₦24,999.00
            'monthly_amount' => 2499,
            'annual_amount'  => 24999,
        ],
        'premium' => [
            'name'           => 'Premium Plan',
            'monthly_kobo'   => 499900,  // ₦4,999.00
            'annual_kobo'    => 4799000, // ₦47,990.00
            'monthly_amount' => 4999,
            'annual_amount'  => 47990,
        ],
    ];

    public function __construct()
    {
        $this->secretKey = $_ENV['PAYSTACK_SECRET_KEY'] ?? $_SERVER['PAYSTACK_SECRET_KEY'] ?? '';
        $this->publicKey = $_ENV['PAYSTACK_PUBLIC_KEY'] ?? $_SERVER['PAYSTACK_PUBLIC_KEY'] ?? '';
        $this->baseUrl   = rtrim($_ENV['PAYSTACK_BASE_URL'] ?? $_SERVER['PAYSTACK_BASE_URL'] ?? 'https://api.paystack.co', '/');
    }

    /**
     * Calculate price in kobo based on plan and billing cycle
     */
    public static function getAmountInKobo(string $plan, string $cycle): ?int
    {
        $normalizedPlan = strtolower(trim($plan));
        $normalizedCycle = strtolower(trim($cycle));

        if (!isset(self::PRICING_LEDGER[$normalizedPlan])) {
            return null;
        }

        return $normalizedCycle === 'annual'
            ? self::PRICING_LEDGER[$normalizedPlan]['annual_kobo']
            : self::PRICING_LEDGER[$normalizedPlan]['monthly_kobo'];
    }

    /**
     * Get plan human-readable details
     */
    public static function getPlanDetails(string $plan): ?array
    {
        $normalizedPlan = strtolower(trim($plan));
        return self::PRICING_LEDGER[$normalizedPlan] ?? null;
    }

    /**
     * Initialize transaction with Paystack
     */
    public function initializeTransaction(
        string $email,
        int $amountInKobo,
        string $callbackUrl,
        array $metadata = [],
        ?string $reference = null
    ): array {
        if (empty($this->secretKey)) {
            // Fallback for demo/test mode when secret key is not yet configured in .env
            $mockRef = $reference ?? 'ic_' . bin2hex(random_bytes(8));
            return [
                'status'  => true,
                'message' => 'Authorization URL created (Simulation Mode)',
                'data'    => [
                    'authorization_url' => url('/payment/callback?reference=' . $mockRef . '&mock=1'),
                    'access_code'       => 'mock_access_' . bin2hex(random_bytes(6)),
                    'reference'         => $mockRef,
                ],
                'is_mock' => true,
            ];
        }

        $ref = $reference ?? 'ic_' . uniqid('', true) . '_' . bin2hex(random_bytes(4));

        $payload = [
            'email'        => $email,
            'amount'       => $amountInKobo,
            'reference'    => $ref,
            'callback_url' => $callbackUrl,
            'metadata'     => $metadata,
            'currency'     => 'NGN',
        ];

        return $this->sendRequest('/transaction/initialize', 'POST', $payload);
    }

    /**
     * Verify a completed transaction with Paystack API
     */
    public function verifyTransaction(string $reference): array
    {
        if (empty($this->secretKey) || str_contains($reference, 'mock')) {
            return [
                'status'  => true,
                'message' => 'Verification successful (Simulation Mode)',
                'data'    => [
                    'status'    => 'success',
                    'reference' => $reference,
                    'amount'    => 249900,
                    'currency'  => 'NGN',
                    'customer'  => [
                        'email' => 'sarah.peters@example.com',
                    ],
                    'metadata'  => [
                        'plan'  => 'pro',
                        'cycle' => 'monthly',
                    ],
                ],
                'is_mock' => true,
            ];
        }

        $cleanRef = rawurlencode($reference);
        return $this->sendRequest("/transaction/verify/{$cleanRef}", 'GET');
    }

    /**
     * Validate Paystack Webhook HMAC SHA512 Signature
     */
    public function verifyWebhookSignature(string $rawPayload, string $signature): bool
    {
        if (empty($this->secretKey) || empty($signature)) {
            return false;
        }

        $computedSignature = hash_hmac('sha512', $rawPayload, $this->secretKey);
        return hash_equals($computedSignature, $signature);
    }

    /**
     * Send HTTP cURL Request to Paystack REST API
     */
    private function sendRequest(string $endpoint, string $method = 'GET', array $data = []): array
    {
        $url = $this->baseUrl . $endpoint;
        $ch = curl_init();

        $headers = [
            'Authorization: Bearer ' . $this->secretKey,
            'Content-Type: application/json',
            'Cache-Control: no-cache',
        ];

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);

        if ($method === 'POST') {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            return [
                'status'  => false,
                'message' => 'Paystack Network Error: ' . $error,
            ];
        }

        $result = json_decode((string)$response, true);
        if (!is_array($result)) {
            return [
                'status'  => false,
                'message' => 'Invalid response from Paystack API (HTTP ' . $httpCode . ')',
            ];
        }

        return $result;
    }
}
