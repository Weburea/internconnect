# Paystack Integration

> Updated 2026-11-11. Setup and security details: `docs/paystack-integration-architecture.md`.

## Plans

Candidate: Free, Pro, Premium. Employer: Standard, Premium (planned; employers currently buy the same Pro / Premium ledger plans).

Prices live only on the server in `PaystackService::PRICING_LEDGER` (kobo). The browser sends a plan and cycle, never an amount.

## Requirements

- Webhook verification (HMAC SHA512 `x-paystack-signature`)
- Transaction logging
- Subscription history
- Auto renewal support (not built yet, see Gaps)
- Never trust redirect URLs: the callback page re-verifies with `GET /transaction/verify/{reference}` (server to server) before anything is recorded; the signed webhook is the second confirmation path. Both update the same row by reference, so a payment is never counted twice.

## Flow

1. `/payments?plan=pro&cycle=monthly&account=candidate|employer` (checkout). The billing pages build this link.
2. `POST /payment/initialize` computes the amount, creates reference `IC_{PLAN}_{M|A}_{time}_{hex}`, sends `account_type` / `account_id` in Paystack metadata, and records a **pending** transaction (`BillingService::recordPending`).
3. Paystack redirects to `/payment/callback`; the controller verifies, then `BillingService::applyPaystackResult()` stores status, channel, card brand and last 4 digits, and activates the plan.
4. `POST /payment/webhook` (`charge.success`) calls the same `applyPaystackResult()`. Unknown references are filed from the metadata; unknown plans are ignored.

Rules in `applyPaystackResult()`:

- A "success" whose amount differs from the recorded plan price is stored as **failed** (`Amount mismatch`).
- A reference already marked success is not applied again.
- Paying again before the renewal date adds the new period after the current one (monthly = +1 month, annual = +1 year).
- A plan past its renewal date reads as **expired**.

## Billing & Subscription pages

| Route | Who |
|---|---|
| `/candidate/billing` | Candidate sidebar "Billings & Subscription" |
| `/employer/billing` | Employer sidebar "Billings & Subscription" |
| `/{role}/billing/receipt/{reference}` | Printable receipt (owner only; other references show "Receipt not found") |
| `/employer/analytics/export/billing` | CSV of the employer's real ledger |

Page sections (`frontend/shared/components/billing-center.php`): current plan with status (Active / Expired / Free), renewal date, member since, cycle, plan choices (choose / switch / renew); summary (total paid, payments, next renewal); payment method (last card or channel used, no card numbers stored); billing history with Paystack reference, amount, status (Paid, Pending, Failed, Cancelled, Refunded), receipt or "Try again", 8 per page.

## Data

`BillingService` creates two tables on first use (SQLite locally, MySQL in production). They map onto `payment_transactions` / `subscriptions` in the MySQL phase.

- `billing_transactions`: account_type, account_id, reference (unique), plan, cycle, amount_kobo, currency, status, channel, card_brand, card_last4, gateway_response, email, paid_at, created_at, updated_at
- `billing_subscriptions`: (account_type, account_id) primary key, plan, cycle, status, amount_kobo, started_at, renews_at, last_reference, updated_at

## Gaps

- Auto renewal: plans are one-time charges per period. Recurring billing needs Paystack Plans/Subscriptions (`subscription.create`, `invoice.payment_failed` webhooks are stubbed in `PaymentController::webhook`).
- Accounts are the demo candidate/employer #1 until users move to MySQL (`BillingService::account()`).
- Refunds/reversals are shown when Paystack reports them, but there is no admin refund action yet.

Tests: `tests/Unit/BillingAndApplicationsTest.php`.
