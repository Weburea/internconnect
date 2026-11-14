<?php
declare(strict_types=1);
/**
 * Billing & Subscription (shared by candidate and employer portals)
 * Used by: frontend/candidate/billing/index.php, frontend/employer/billing/index.php
 * Data: BillingService::overview() — Paystack payments recorded at checkout, callback and webhook.
 * Spec: docs/08-payment-system.md
 *
 * @var array $billing  subscription, plan, transactions, total, page, pages, paid_kobo
 * @var array $account  ['type' => candidate|employer, 'id' => int]
 */

use App\Services\BillingService;
use App\Services\PaystackService;

$bcType = $account['type'];
$bcBase = '/' . $bcType . '/billing';
$bcSub = $billing['subscription'];
$bcPlan = $billing['plan'];
$bcTx = $billing['transactions'];
$bcLedger = PaystackService::PRICING_LEDGER;
$bcUpgrade = static fn (string $plan, string $cycle = 'monthly'): string => url('/payments?plan=' . $plan . '&cycle=' . $cycle . '&account=' . $bcType);
$bcDate = static fn (?string $d): string => $d ? date('M j, Y', strtotime($d)) : '—';
$bcStatus = $bcSub['status'] ?? 'free';
$bcStatusMeta = ['active' => ['Active', 'green'], 'expired' => ['Expired', 'amber'], 'free' => ['Free plan', 'slate']][$bcStatus] ?? [ucfirst($bcStatus), 'slate'];

// Last card used (from the newest successful payment).
$bcCard = null;
foreach ($bcTx as $t) {
    if ($t['status'] === 'success') {
        $bcCard = $t;
        break;
    }
}
$bcDaysLeft = ($bcSub && $bcStatus === 'active') ? max(0, (int)ceil((strtotime($bcSub['renews_at']) - time()) / 86400)) : 0;
?>
<style>
.bc { display: flex; flex-direction: column; gap: 20px; }
.bc * { box-sizing: border-box; }
.bc-head { display: flex; align-items: flex-end; justify-content: space-between; gap: 12px 20px; flex-wrap: wrap; }
.bc-head h1 { font-size: 24px; font-weight: 800; color: #000623; margin: 0 0 4px; letter-spacing: -0.02em; }
.bc-head p { font-size: 13.5px; color: #64748B; margin: 0; max-width: 620px; line-height: 1.5; }
.bc-btn { display: inline-flex; align-items: center; justify-content: center; gap: 7px; height: 40px; padding: 0 16px; border-radius: 10px; border: 1px solid #E2E8F0; background: #FFFFFF; font-size: 13px; font-weight: 700; color: #334155; text-decoration: none; white-space: nowrap; cursor: pointer; transition: background .15s ease, border-color .15s ease; }
.bc-btn:hover { background: #F8FAFC; border-color: #CBD5E1; color: #0F172A; }
.bc-btn svg { width: 15px; height: 15px; flex-shrink: 0; }
.bc-btn.is-primary { background: #4F48E6; border-color: #4F48E6; color: #FFFFFF; }
.bc-btn.is-primary:hover { background: #4338CA; border-color: #4338CA; color: #FFFFFF; }
.bc-grid { display: grid; grid-template-columns: minmax(0, 1.6fr) minmax(0, 1fr); gap: 20px; align-items: stretch; }
.bc-card { background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 16px; padding: 20px; min-width: 0; }
.bc-card-title { font-size: 15px; font-weight: 800; color: #000623; margin: 0; }
.bc-card-sub { font-size: 12.5px; color: #64748B; margin: 3px 0 0; }
.bc-plan { display: flex; flex-direction: column; gap: 18px; }
.bc-plan-top { display: flex; align-items: flex-start; justify-content: space-between; gap: 12px; flex-wrap: wrap; }
.bc-plan-name { display: flex; align-items: center; gap: 12px; }
.bc-plan-icon { width: 44px; height: 44px; border-radius: 12px; background: #EEF2FF; color: #4F48E6; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.bc-plan-icon svg { width: 22px; height: 22px; }
.bc-plan-name h2 { font-size: 18px; font-weight: 800; color: #000623; margin: 0; }
.bc-plan-name p { font-size: 13px; color: #64748B; margin: 2px 0 0; }
.bc-badge { display: inline-flex; align-items: center; gap: 6px; height: 26px; padding: 0 10px; border-radius: 9999px; font-size: 11.5px; font-weight: 700; white-space: nowrap; }
.bc-badge::before { content: ''; width: 6px; height: 6px; border-radius: 50%; background: currentColor; }
.bc-tone-green { background: #ECFDF5; color: #047857; }
.bc-tone-amber { background: #FFFBEB; color: #B45309; }
.bc-tone-slate { background: #F1F5F9; color: #475569; }
.bc-tone-red { background: #FEF2F2; color: #B91C1C; }
.bc-facts { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 12px; }
.bc-fact { padding: 12px 14px; border-radius: 12px; background: #F8FAFC; min-width: 0; }
.bc-fact span { display: block; font-size: 11px; font-weight: 700; color: #64748B; text-transform: uppercase; letter-spacing: .05em; }
.bc-fact strong { display: block; font-size: 14.5px; font-weight: 800; color: #0F172A; margin-top: 4px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.bc-actions { display: flex; gap: 10px; flex-wrap: wrap; }
.bc-note { font-size: 12.5px; color: #64748B; line-height: 1.55; margin: 0; }
.bc-side { display: flex; flex-direction: column; gap: 14px; }
.bc-stat { display: flex; align-items: center; justify-content: space-between; gap: 10px; padding: 12px 0; }
.bc-stat + .bc-stat { border-top: 1px solid #F1F5F9; }
.bc-stat span { font-size: 13px; color: #64748B; }
.bc-stat strong { font-size: 14px; font-weight: 800; color: #0F172A; white-space: nowrap; }
.bc-method { display: flex; align-items: center; gap: 12px; padding: 12px 14px; border: 1px solid #E2E8F0; border-radius: 12px; }
.bc-method-icon { width: 40px; height: 28px; border-radius: 6px; background: #000623; color: #FFFFFF; font-size: 10px; font-weight: 800; display: flex; align-items: center; justify-content: center; letter-spacing: .04em; flex-shrink: 0; text-transform: uppercase; }
.bc-method strong { display: block; font-size: 13.5px; color: #0F172A; }
.bc-method small { display: block; font-size: 12px; color: #64748B; margin-top: 1px; }
.bc-secure { display: flex; gap: 8px; align-items: flex-start; font-size: 12px; color: #64748B; line-height: 1.5; }
.bc-secure svg { width: 15px; height: 15px; color: #10B981; flex-shrink: 0; margin-top: 1px; }
.bc-table-card { padding: 0; overflow: hidden; }
.bc-table-head { display: flex; align-items: center; justify-content: space-between; gap: 10px; padding: 18px 20px; border-bottom: 1px solid #F1F5F9; flex-wrap: wrap; }
.bc-table { width: 100%; border-collapse: collapse; }
.bc-table th { text-align: left; font-size: 11px; font-weight: 700; color: #64748B; text-transform: uppercase; letter-spacing: .05em; padding: 10px 20px; background: #F8FAFC; white-space: nowrap; }
.bc-table td { padding: 14px 20px; border-top: 1px solid #F1F5F9; font-size: 13px; color: #334155; vertical-align: middle; }
.bc-table td strong { color: #0F172A; font-weight: 700; }
.bc-ref { font-family: ui-monospace, SFMono-Regular, Menlo, monospace; font-size: 12px; color: #64748B; }
.bc-sub { display: block; font-size: 12px; color: #94A3B8; margin-top: 2px; }
.bc-link { display: inline-flex; align-items: center; gap: 4px; font-size: 12.5px; font-weight: 700; color: #4F48E6; text-decoration: none; white-space: nowrap; }
.bc-link:hover { color: #3730A3; }
.bc-empty { padding: 36px 20px; text-align: center; }
.bc-empty strong { display: block; font-size: 14.5px; color: #0F172A; margin-bottom: 4px; }
.bc-empty p { font-size: 13px; color: #64748B; margin: 0 0 14px; }
.bc-pager { display: flex; align-items: center; justify-content: space-between; gap: 10px; padding: 12px 20px; border-top: 1px solid #F1F5F9; font-size: 12.5px; color: #64748B; font-weight: 600; }
.bc-pager-btns { display: flex; gap: 6px; }
.bc-pager .bc-btn { height: 34px; width: 34px; padding: 0; }
.bc-pager .bc-btn.is-disabled { opacity: .45; pointer-events: none; }
.bc-plans { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 12px; }
.bc-plan-opt { border: 1px solid #E2E8F0; border-radius: 12px; padding: 14px; display: flex; flex-direction: column; gap: 6px; }
.bc-plan-opt.is-current { border-color: #A5B4FC; background: #F5F7FF; }
.bc-plan-opt strong { font-size: 14px; color: #0F172A; }
.bc-plan-opt span { font-size: 12.5px; color: #64748B; }
.bc-plan-opt .bc-btn { margin-top: 6px; height: 36px; }
@media (max-width: 1100px) { .bc-grid { grid-template-columns: minmax(0, 1fr); } }
@media (max-width: 760px) {
    .bc-facts { grid-template-columns: minmax(0, 1fr); }
    .bc-plans { grid-template-columns: minmax(0, 1fr); }
    .bc-table thead { display: none; }
    .bc-table, .bc-table tbody, .bc-table tr, .bc-table td { display: block; width: 100%; }
    .bc-table tr { padding: 12px 16px; border-top: 1px solid #F1F5F9; }
    .bc-table td { border: 0; padding: 3px 0; display: flex; justify-content: space-between; gap: 12px; align-items: center; text-align: right; }
    .bc-table td::before { content: attr(data-label); font-size: 11px; font-weight: 700; color: #94A3B8; text-transform: uppercase; letter-spacing: .05em; text-align: left; flex-shrink: 0; }
    .bc-table td.bc-td-main { display: block; text-align: left; }
    .bc-table td.bc-td-main::before { content: none; }
    .bc-head .bc-btn { flex: 1 1 auto; }
}
</style>

<div class="bc">
    <div class="bc-head">
        <div>
            <h1>Billing &amp; Subscription</h1>
            <p><?= $bcType === 'employer' ? 'Your company plan, payment history and receipts.' : 'Your plan, payment history and receipts.' ?> Payments are processed securely by Paystack.</p>
        </div>
        <div class="bc-actions">
            <a class="bc-btn" href="<?= url('/pricing') ?>">Compare plans</a>
            <a class="bc-btn is-primary" href="<?= htmlspecialchars($bcUpgrade($bcSub['plan'] ?? 'pro', $bcSub['cycle'] ?? 'monthly')) ?>">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                <span><?= $bcStatus === 'active' ? 'Renew or change plan' : ($bcStatus === 'expired' ? 'Renew plan' : 'Upgrade') ?></span>
            </a>
        </div>
    </div>

    <div class="bc-grid">
        <!-- Current plan -->
        <section class="bc-card bc-plan" aria-labelledby="bc-plan-title">
            <div class="bc-plan-top">
                <div class="bc-plan-name">
                    <span class="bc-plan-icon" aria-hidden="true"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg></span>
                    <div>
                        <h2 id="bc-plan-title"><?= htmlspecialchars($bcPlan['name'] ?? 'Free plan') ?></h2>
                        <p>
                            <?php if ($bcSub): ?>
                                <?= BillingService::naira((int)$bcSub['amount_kobo']) ?> / <?= $bcSub['cycle'] === 'annual' ? 'year' : 'month' ?>
                            <?php else: ?>
                                Core features at no cost
                            <?php endif; ?>
                        </p>
                    </div>
                </div>
                <span class="bc-badge bc-tone-<?= $bcStatusMeta[1] ?>"><?= htmlspecialchars($bcStatusMeta[0]) ?></span>
            </div>

            <?php if ($bcSub): ?>
            <div class="bc-facts">
                <div class="bc-fact"><span><?= $bcStatus === 'active' ? 'Renews on' : 'Ended on' ?></span><strong><?= $bcDate($bcSub['renews_at']) ?></strong></div>
                <div class="bc-fact"><span>Member since</span><strong><?= $bcDate($bcSub['started_at']) ?></strong></div>
                <div class="bc-fact"><span>Billing cycle</span><strong><?= $bcSub['cycle'] === 'annual' ? 'Annual' : 'Monthly' ?></strong></div>
            </div>
            <p class="bc-note">
                <?php if ($bcStatus === 'active'): ?>
                    <?= $bcDaysLeft ?> day<?= $bcDaysLeft === 1 ? '' : 's' ?> left in this period. Plans do not renew automatically; renew any time and the new period is added after the current one.
                <?php else: ?>
                    Your plan has ended. Renew to get your premium features back.
                <?php endif; ?>
            </p>
            <?php else: ?>
            <p class="bc-note">You are on the free plan. Upgrade to unlock premium features. Choose a plan below; you pay once per period with Paystack and can renew whenever you like.</p>
            <?php endif; ?>

            <div class="bc-plans">
                <?php foreach ($bcLedger as $key => $p): $isCurrent = $bcSub && $bcSub['plan'] === $key && $bcStatus === 'active'; ?>
                <div class="bc-plan-opt <?= $isCurrent ? 'is-current' : '' ?>">
                    <strong><?= htmlspecialchars($p['name']) ?><?= $isCurrent ? ' · Current' : '' ?></strong>
                    <span><?= BillingService::naira((int)$p['monthly_kobo']) ?> / month or <?= BillingService::naira((int)$p['annual_kobo']) ?> / year</span>
                    <a class="bc-btn <?= $isCurrent ? '' : 'is-primary' ?>" href="<?= htmlspecialchars($bcUpgrade($key, $bcSub['cycle'] ?? 'monthly')) ?>"><?= $isCurrent ? 'Renew' : ($bcSub ? 'Switch to ' . htmlspecialchars($p['name']) : 'Choose ' . htmlspecialchars($p['name'])) ?></a>
                </div>
                <?php endforeach; ?>
            </div>
        </section>

        <!-- Summary + payment method -->
        <aside class="bc-side">
            <section class="bc-card">
                <h2 class="bc-card-title">Summary</h2>
                <div class="bc-stat"><span>Total paid</span><strong><?= BillingService::naira((int)$billing['paid_kobo']) ?></strong></div>
                <div class="bc-stat"><span>Payments</span><strong><?= (int)$billing['total'] ?></strong></div>
                <div class="bc-stat"><span>Next renewal</span><strong><?= $bcStatus === 'active' ? $bcDate($bcSub['renews_at']) : '—' ?></strong></div>
            </section>
            <section class="bc-card">
                <h2 class="bc-card-title" style="margin-bottom: 12px;">Payment method</h2>
                <?php if ($bcCard && ($bcCard['card_last4'] || $bcCard['channel'])): ?>
                <div class="bc-method">
                    <span class="bc-method-icon"><?= htmlspecialchars(mb_substr((string)($bcCard['card_brand'] ?: $bcCard['channel'] ?: 'card'), 0, 6)) ?></span>
                    <div>
                        <strong><?= $bcCard['card_last4'] ? htmlspecialchars(($bcCard['card_brand'] ?: 'Card') . ' ending ' . $bcCard['card_last4']) : htmlspecialchars(ucfirst((string)$bcCard['channel'])) ?></strong>
                        <small>Last used <?= $bcDate($bcCard['paid_at']) ?></small>
                    </div>
                </div>
                <?php else: ?>
                <p class="bc-note" style="margin-bottom: 12px;">No payment method on record yet. You choose card, bank transfer or USSD on Paystack when you pay.</p>
                <?php endif; ?>
                <p class="bc-secure" style="margin-top: 12px;">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    <span>InternConnect never sees or stores your card number. Paystack handles it under PCI DSS.</span>
                </p>
            </section>
        </aside>
    </div>

    <!-- Billing history -->
    <section class="bc-card bc-table-card" aria-labelledby="bc-history-title">
        <div class="bc-table-head">
            <div>
                <h2 class="bc-card-title" id="bc-history-title">Billing history</h2>
                <p class="bc-card-sub">Every payment attempt with its Paystack reference. Open a receipt to print or save it.</p>
            </div>
        </div>
        <?php if ($bcTx): ?>
        <table class="bc-table">
            <thead><tr><th>Date</th><th>Description</th><th>Reference</th><th>Amount</th><th>Status</th><th><span style="position:absolute;width:1px;height:1px;overflow:hidden;clip:rect(0 0 0 0);white-space:nowrap;">Receipt</span></th></tr></thead>
            <tbody>
                <?php foreach ($bcTx as $t): [$label, $tone] = BillingService::statusMeta((string)$t['status']); $pd = PaystackService::getPlanDetails((string)$t['plan']); ?>
                <tr>
                    <td data-label="Date" class="bc-td-main"><strong><?= date('M j, Y', strtotime($t['paid_at'] ?: $t['created_at'])) ?></strong><span class="bc-sub"><?= date('g:i A', strtotime($t['paid_at'] ?: $t['created_at'])) ?></span></td>
                    <td data-label="Plan"><?= htmlspecialchars(($pd['name'] ?? ucfirst((string)$t['plan'])) . ' · ' . ($t['cycle'] === 'annual' ? 'Annual' : 'Monthly')) ?></td>
                    <td data-label="Reference"><span class="bc-ref"><?= htmlspecialchars((string)$t['reference']) ?></span></td>
                    <td data-label="Amount"><strong><?= BillingService::naira((int)$t['amount_kobo']) ?></strong></td>
                    <td data-label="Status"><span class="bc-badge bc-tone-<?= $tone ?>"><?= htmlspecialchars($label) ?></span></td>
                    <td data-label="">
                        <?php if ($t['status'] === 'success'): ?>
                        <a class="bc-link" href="<?= url($bcBase . '/receipt/' . rawurlencode((string)$t['reference'])) ?>">Receipt</a>
                        <?php elseif ($t['status'] !== 'pending'): ?>
                        <a class="bc-link" href="<?= url($bcBase . '/receipt/' . rawurlencode((string)$t['reference'])) ?>">Receipt</a>
                        <span class="bc-sub" style="margin: 0 4px; display: inline;">&middot;</span>
                        <a class="bc-link" href="<?= htmlspecialchars($bcUpgrade((string)$t['plan'], (string)$t['cycle'])) ?>">Try again</a>
                        <?php else: ?>
                        <span class="bc-sub" style="margin: 0;">Awaiting Paystack</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php if ($billing['pages'] > 1): $pg = (int)$billing['page']; ?>
        <div class="bc-pager">
            <span>Page <?= $pg ?> of <?= (int)$billing['pages'] ?> · <?= (int)$billing['total'] ?> payments</span>
            <div class="bc-pager-btns">
                <a class="bc-btn <?= $pg <= 1 ? 'is-disabled' : '' ?>" href="<?= url($bcBase . '?page=' . max(1, $pg - 1)) ?>" aria-label="Newer payments"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg></a>
                <a class="bc-btn <?= $pg >= $billing['pages'] ? 'is-disabled' : '' ?>" href="<?= url($bcBase . '?page=' . min((int)$billing['pages'], $pg + 1)) ?>" aria-label="Older payments"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg></a>
            </div>
        </div>
        <?php endif; ?>
        <?php else: ?>
        <div class="bc-empty">
            <strong>No payments yet</strong>
            <p>When you upgrade, each payment and its receipt appears here.</p>
            <a class="bc-btn is-primary" href="<?= htmlspecialchars($bcUpgrade('pro')) ?>">Upgrade to Pro</a>
        </div>
        <?php endif; ?>
    </section>
</div>
