<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Services\BillingService;

/**
 * Billing & Subscription pages for candidates and employers.
 * The account type comes from the URL prefix (/candidate/... or /employer/...).
 * Routes: GET /{role}/billing, GET /{role}/billing/receipt/{id} (id = Paystack reference)
 * Spec: docs/08-payment-system.md
 */
class BillingController extends Controller
{
    private function boot(): string
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $path = (string)parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH);
        return str_contains($path, '/employer/') ? 'employer' : 'candidate';
    }

    private function people(string $type): array
    {
        return $type === 'employer'
            ? ['employer' => [
                'company_name' => $_SESSION['employer_company'] ?? 'Nexura Technologies',
                'role'         => 'Talent Acquisition Team',
                'first_name'   => $_SESSION['employer_first_name'] ?? 'Tunde',
                'avatar'       => $_SESSION['employer_avatar'] ?? null,
            ]]
            : ['candidate' => [
                'name'   => $_SESSION['profile_name'] ?? 'Sarah Peters',
                'role'   => $_SESSION['profile_role'] ?? 'Student Applicant',
                'avatar' => $_SESSION['profile_avatar'] ?? asset('assets/images/profile.png'),
            ]];
    }

    public function index(): void
    {
        $type = $this->boot();
        $account = BillingService::account($type);
        $billing = BillingService::overview($account['type'], $account['id'], (int)($_GET['page'] ?? 1));

        $this->renderView($type . '/billing/index', [
            'title'     => 'Billing & Subscription — InternConnect',
            'activeNav' => 'billing',
            'billing'   => $billing,
            'account'   => $account,
        ] + $this->people($type), 'dashboard');
    }

    public function receipt(array $params = []): void
    {
        $type = $this->boot();
        $account = BillingService::account($type);
        $tx = BillingService::transaction((string)($params['id'] ?? ''));
        // Only the owner of the payment can open its receipt.
        if (!$tx || $tx['account_type'] !== $account['type'] || (int)$tx['account_id'] !== $account['id']) {
            http_response_code(404);
            $tx = null;
        }
        $this->renderView('shared/billing/receipt', [
            'title'      => 'Receipt — InternConnect',
            'tx'         => $tx,
            'accountType'=> $type,
            'payerName'  => $type === 'employer' ? ($_SESSION['employer_company'] ?? 'Nexura Technologies') : ($_SESSION['profile_name'] ?? 'Sarah Peters'),
        ], 'dashboard');
    }
}
