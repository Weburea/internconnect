<?php
declare(strict_types=1);
/**
 * View: Payment receipt, ticket style (docs/54-email-newsletter-contact.md, "Receipt")
 * Route: /candidate/billing/receipt/{reference}, /employer/billing/receipt/{reference}
 * Layout: dashboard
 *
 * One design for every outcome: paid, declined, pending, cancelled, refunded.
 * Downloads: vector PDF drawn with jsPDF (crisp text, scannable barcode) and a PNG image (html2canvas).
 *
 * @var array|null $tx          billing_transactions row (owner already checked)
 * @var string     $accountType candidate | employer
 * @var string     $payerName
 */

use App\Core\Env;
use App\Services\Barcode;
use App\Services\BillingService;
use App\Services\PaystackService;

$backUrl = url('/' . $accountType . '/billing');
$e = static fn ($v): string => htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');
$support = Env::get('MAIL_ADMIN_ADDRESS', 'internconnect@weburea.com');

if ($tx) {
    $plan = PaystackService::getPlanDetails((string)$tx['plan']);
    $status = (string)$tx['status'];
    $state = [
        'success'   => ['ok',      'Thank you!',          'Your payment has been processed successfully.'],
        'failed'    => ['bad',     'Payment declined',    'Your bank or card declined this payment. You have not been charged.'],
        'abandoned' => ['neutral', 'Payment cancelled',   'This checkout was closed before payment. You have not been charged.'],
        'pending'   => ['wait',    'Payment pending',     'We are waiting for Paystack to confirm this payment. This page updates once it does.'],
        'reversed'  => ['neutral', 'Payment refunded',    'This payment was refunded to your original payment method.'],
    ][$status] ?? ['neutral', ucfirst($status), ''];
    $when = strtotime((string)($tx['paid_at'] ?: $tx['created_at']));
    $brand = trim((string)($tx['card_brand'] ?? ''));
    $brandKey = strtolower($brand);
    $isCard = !empty($tx['card_last4']);
    $channel = strtolower((string)($tx['channel'] ?? ''));
    $methodTitle = $isCard
        ? ($brand !== '' ? $brand : 'Card') . ' ending in ' . $tx['card_last4']
        : (['bank' => 'Bank account', 'bank_transfer' => 'Bank transfer', 'ussd' => 'USSD', 'qr' => 'QR payment', 'mobile_money' => 'Mobile money'][$channel] ?? 'Paystack checkout');
    $methodSub = $isCard
        ? (!empty($tx['card_exp']) ? 'Expiry ' . $tx['card_exp'] : 'Card payment') . (!empty($tx['card_bank']) ? ' · ' . $tx['card_bank'] : '')
        : (!empty($tx['card_bank']) ? $tx['card_bank'] : 'Processed by Paystack');
    $planLabel = ($plan['name'] ?? ucfirst((string)$tx['plan'])) . ' · ' . ($tx['cycle'] === 'annual' ? 'Annual' : 'Monthly');
    $receiptData = [
        'reference' => (string)$tx['reference'],
        'state'     => $state[0],
        'title'     => $state[1],
        'subtitle'  => $state[2],
        'amount'    => 'NGN ' . number_format((int)$tx['amount_kobo'] / 100, 2),
        'date'      => date('j M Y, g:i A', $when),
        'plan'      => $planLabel,
        'billedTo'  => $payerName,
        'email'     => (string)($tx['email'] ?? ''),
        'method'    => $methodTitle,
        'methodSub' => $methodSub,
        'brand'     => $brandKey,
        'reason'    => in_array($status, ['failed', 'abandoned'], true) ? (string)($tx['gateway_response'] ?? '') : '',
        'support'   => $support,
        'pattern'   => Barcode::pattern((string)$tx['reference']),
        'logo'      => asset('assets/images/logos/Logo_single_dark_dj6vhf.png'),
        'file'      => 'InternConnect-receipt-' . preg_replace('/[^A-Za-z0-9_-]/', '', (string)$tx['reference']),
    ];
}
?>
<style>
.rc-page { min-height: 100vh; background: #F1F3F9; padding: 28px 16px 48px; box-sizing: border-box; }
.rc-page * { box-sizing: border-box; }
.rc-bar { max-width: 460px; margin: 0 auto 18px; display: flex; justify-content: space-between; align-items: center; gap: 10px; flex-wrap: wrap; }
.rc-actions { display: flex; gap: 8px; flex-wrap: wrap; }
.rc-btn { display: inline-flex; align-items: center; gap: 7px; height: 38px; padding: 0 14px; border-radius: 10px; border: 1px solid #E2E8F0; background: #FFFFFF; font-size: 13px; font-weight: 700; color: #334155; text-decoration: none; cursor: pointer; font-family: inherit; white-space: nowrap; }
.rc-btn:hover { background: #F8FAFC; color: #0F172A; }
.rc-btn.is-primary { background: #4F48E6; border-color: #4F48E6; color: #FFFFFF; }
.rc-btn.is-primary:hover { background: #4338CA; }
.rc-btn[disabled] { opacity: .6; cursor: progress; }
.rc-btn svg { width: 15px; height: 15px; flex-shrink: 0; }

/* Ticket */
.tk-capture { max-width: 460px; margin: 0 auto; padding: 14px; background: #F1F3F9; }
.tk { position: relative; background: #FFFFFF; border-radius: 22px 22px 0 0; box-shadow: 0 18px 40px -22px rgba(0, 6, 35, .35); font-family: 'Inter', system-ui, -apple-system, 'Segoe UI', Arial, sans-serif; }
.tk-head { padding: 34px 26px 22px; text-align: center; }
.tk-logo { position: relative; width: 76px; height: 76px; margin: 0 auto 18px; border-radius: 50%; background: #EEF0FF; display: flex; align-items: center; justify-content: center; box-shadow: 0 0 0 8px #F6F7FF; }
.tk-logo img { width: 32px; height: 37px; display: block; }
.tk-badge { position: absolute; right: -4px; bottom: -2px; width: 28px; height: 28px; border-radius: 50%; border: 3px solid #FFFFFF; display: flex; align-items: center; justify-content: center; color: #FFFFFF; }
.tk-badge svg { width: 13px; height: 13px; }
.tk-badge.ok { background: #16A34A; }
.tk-badge.bad { background: #DC2626; }
.tk-badge.wait { background: #D97706; }
.tk-badge.neutral { background: #64748B; }
.tk-title { margin: 0; font-size: 26px; line-height: 1.2; font-weight: 800; color: #000623; letter-spacing: -0.02em; }
.tk-sub { margin: 8px auto 0; max-width: 300px; font-size: 14px; line-height: 1.55; color: #64748B; }
.tk-perf { position: relative; height: 26px; }
.tk-perf::before, .tk-perf::after { content: ''; position: absolute; top: 0; width: 26px; height: 26px; border-radius: 50%; background: #F1F3F9; }
.tk-perf::before { left: -13px; }
.tk-perf::after { right: -13px; }
.tk-perf span { position: absolute; left: 22px; right: 22px; top: 12px; border-top: 2px dashed #E2E8F0; }
.tk-body { padding: 14px 26px 6px; }
.tk-grid { display: grid; grid-template-columns: minmax(0, 1fr) minmax(0, 1fr); gap: 18px 16px; }
.tk-cell { min-width: 0; }
.tk-cell.full { grid-column: 1 / -1; }
.tk-label { display: block; font-size: 11px; font-weight: 700; letter-spacing: .08em; text-transform: uppercase; color: #94A3B8; margin-bottom: 5px; }
.tk-value { display: block; font-size: 15px; font-weight: 700; color: #000623; overflow-wrap: anywhere; line-height: 1.35; }
.tk-value small { display: block; font-size: 12.5px; font-weight: 500; color: #64748B; margin-top: 2px; }
.tk-amount { font-size: 19px; }
.tk-reason { margin-top: 16px; padding: 11px 13px; border-radius: 12px; background: #FEF2F2; color: #991B1B; font-size: 13px; line-height: 1.45; }
.tk-card { margin-top: 20px; display: flex; align-items: center; gap: 14px; padding: 14px 16px; border: 1.5px solid #E2E8F0; border-radius: 16px; }
.tk-card-mark { width: 50px; height: 34px; flex-shrink: 0; border-radius: 8px; background: #F8FAFC; display: flex; align-items: center; justify-content: center; }
.tk-card-text { min-width: 0; }
.tk-card-text strong { display: block; font-size: 14.5px; color: #000623; overflow-wrap: anywhere; }
.tk-card-text span { display: block; font-size: 12.5px; color: #64748B; margin-top: 2px; }
.tk-code { padding: 22px 26px 30px; text-align: center; }
.tk-code-bars { width: 100%; max-width: 380px; margin: 0 auto; }
.tk-code-ref { margin-top: 8px; font-family: 'SFMono-Regular', Consolas, 'Liberation Mono', monospace; font-size: 12.5px; letter-spacing: .14em; color: #334155; overflow-wrap: anywhere; }
.tk-edge { display: block; width: 100%; height: 12px; }
.tk-foot { max-width: 432px; margin: 14px auto 0; font-size: 12px; line-height: 1.6; color: #64748B; text-align: center; }
.tk-foot a { color: #4F48E6; font-weight: 600; text-decoration: none; overflow-wrap: anywhere; }
.rc-missing { max-width: 460px; margin: 0 auto; background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 18px; padding: 28px; }
@media (max-width: 420px) {
    .tk-head { padding: 28px 18px 18px; }
    .tk-body { padding: 12px 18px 4px; }
    .tk-code { padding: 20px 18px 26px; }
    .tk-title { font-size: 23px; }
    .tk-capture { padding: 8px; }
    .tk-id { grid-column: 1 / -1; }
    .rc-page { padding-bottom: 110px; }
}
@media print {
    .rc-bar { display: none; }
    .rc-page { background: #FFFFFF; padding: 0; }
    .tk { box-shadow: none; border: 1px solid #E2E8F0; }
}
</style>

<div class="rc-page">
    <div class="rc-bar">
        <a class="rc-btn" href="<?= $backUrl ?>"><svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>Back to billing</a>
        <?php if ($tx): ?>
        <div class="rc-actions">
            <button type="button" class="rc-btn is-primary" id="rc-pdf"><svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg><span>Download PDF</span></button>
            <button type="button" class="rc-btn" id="rc-png"><svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg><span>Save image</span></button>
        </div>
        <?php endif; ?>
    </div>

    <?php if (!$tx): ?>
        <div class="rc-missing">
            <h1 style="font-size: 18px; color: #0F172A; margin: 0 0 6px;">Receipt not found</h1>
            <p style="font-size: 13px; color: #64748B; margin: 0;">This payment does not exist or belongs to another account.</p>
        </div>
    <?php else: ?>
        <div class="tk-capture" id="rc-ticket">
            <div class="tk" role="document" aria-label="Payment receipt <?= $e($tx['reference']) ?>">
                <div class="tk-head">
                    <div class="tk-logo">
                        <img src="<?= $e($receiptData['logo']) ?>" width="32" height="37" alt="InternConnect" crossorigin="anonymous">
                        <span class="tk-badge <?= $state[0] ?>" aria-hidden="true">
                            <?php if ($state[0] === 'ok'): ?>
                                <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            <?php elseif ($state[0] === 'bad'): ?>
                                <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                            <?php elseif ($state[0] === 'wait'): ?>
                                <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M12 7v5l3 2"/></svg>
                            <?php else: ?>
                                <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14"/></svg>
                            <?php endif; ?>
                        </span>
                    </div>
                    <h1 class="tk-title"><?= $e($state[1]) ?></h1>
                    <p class="tk-sub"><?= $e($state[2]) ?></p>
                </div>

                <div class="tk-perf" aria-hidden="true"><span></span></div>

                <div class="tk-body">
                    <div class="tk-grid">
                        <div class="tk-cell tk-id"><span class="tk-label">Ticket ID</span><span class="tk-value"><?= $e($tx['reference']) ?></span></div>
                        <div class="tk-cell"><span class="tk-label">Amount</span><span class="tk-value tk-amount"><?= BillingService::naira((int)$tx['amount_kobo']) ?></span></div>
                        <div class="tk-cell"><span class="tk-label">Date &amp; time</span><span class="tk-value"><?= date('j M Y', $when) ?><small><?= date('g:i A', $when) ?> WAT</small></span></div>
                        <div class="tk-cell"><span class="tk-label">Plan</span><span class="tk-value"><?= $e($plan['name'] ?? ucfirst((string)$tx['plan'])) ?><small><?= $tx['cycle'] === 'annual' ? 'Annual' : 'Monthly' ?></small></span></div>
                        <div class="tk-cell full"><span class="tk-label">Billed to</span><span class="tk-value"><?= $e($payerName) ?><?php if ($tx['email']): ?><small><?= $e($tx['email']) ?></small><?php endif; ?></span></div>
                    </div>

                    <?php if ($receiptData['reason'] !== ''): ?>
                        <div class="tk-reason"><strong>Reason:</strong> <?= $e($receiptData['reason']) ?></div>
                    <?php endif; ?>

                    <div class="tk-card">
                        <div class="tk-card-mark" aria-hidden="true">
                            <?php if (str_contains($brandKey, 'master')): ?>
                                <svg width="34" height="22" viewBox="0 0 34 22"><circle cx="12" cy="11" r="9" fill="#EB001B"/><circle cx="22" cy="11" r="9" fill="#F79E1B"/><path d="M17 3.6a9 9 0 010 14.8 9 9 0 010-14.8z" fill="#FF5F00"/></svg>
                            <?php elseif (str_contains($brandKey, 'visa')): ?>
                                <svg width="38" height="14" viewBox="0 0 38 14"><text x="19" y="12" text-anchor="middle" font-family="Arial, Helvetica, sans-serif" font-size="14" font-weight="800" font-style="italic" fill="#1A1F71">VISA</text></svg>
                            <?php elseif (str_contains($brandKey, 'verve')): ?>
                                <svg width="40" height="14" viewBox="0 0 40 14"><text x="20" y="12" text-anchor="middle" font-family="Arial, Helvetica, sans-serif" font-size="13" font-weight="800" fill="#00425F">Verve</text></svg>
                            <?php elseif ($isCard): ?>
                                <svg width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="#4F48E6" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                            <?php else: ?>
                                <svg width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="#4F48E6" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 21h18M3 10h18M5 6l7-3 7 3M4 10v11m16-11v11M8 14v3m4-3v3m4-3v3"/></svg>
                            <?php endif; ?>
                        </div>
                        <div class="tk-card-text">
                            <strong><?= $e($methodTitle) ?></strong>
                            <span><?= $e($methodSub) ?></span>
                        </div>
                    </div>
                </div>

                <div class="tk-code">
                    <div class="tk-code-bars"><?= Barcode::svg((string)$tx['reference'], 58) ?></div>
                    <div class="tk-code-ref"><?= $e($tx['reference']) ?></div>
                </div>

            </div>
            <!-- Torn-ticket bottom edge (in normal flow so the saved image includes it) -->
            <svg class="tk-edge" viewBox="0 0 432 12" preserveAspectRatio="none" aria-hidden="true" style="margin-top: -1px;">
                <path fill="#FFFFFF" d="M0 0H432V0<?php for ($x = 432; $x > 0; $x -= 16): ?> Q<?= $x - 8 ?> 14 <?= $x - 16 ?> 0<?php endfor; ?>Z"/>
            </svg>
            <p class="tk-foot">Processed securely by Paystack. InternConnect never sees your full card number. Questions about this payment? Write to <a href="mailto:<?= $e($support) ?>"><?= $e($support) ?></a> with the ticket ID.</p>
        </div>

        <script type="application/json" id="rc-data"><?= json_encode($receiptData, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE) ?></script>
        <script>
        (function () {
            var data = JSON.parse(document.getElementById('rc-data').textContent);
            var libs = {
                jspdf: '<?= asset('assets/js/jspdf.umd.min.js') ?>?v=<?= @filemtime(dirname(__DIR__, 3) . '/public/assets/js/jspdf.umd.min.js') ?: 1 ?>',
                h2c: '<?= asset('assets/js/html2canvas.min.js') ?>?v=<?= @filemtime(dirname(__DIR__, 3) . '/public/assets/js/html2canvas.min.js') ?: 1 ?>'
            };
            function load(src) {
                return new Promise(function (resolve, reject) {
                    var s = document.createElement('script');
                    s.src = src; s.onload = resolve; s.onerror = reject;
                    document.head.appendChild(s);
                });
            }
            function toast(text, type) {
                window.dispatchEvent(new CustomEvent('show-toast', { detail: { text: text, message: text, type: type || 'success' } }));
            }
            function busy(btn, on) {
                btn.disabled = on;
                btn.querySelector('span').textContent = on ? 'Preparing...' : btn.dataset.label;
            }
            function imageData(src) {
                return fetch(src).then(function (r) { return r.blob(); }).then(function (b) {
                    return new Promise(function (resolve) { var fr = new FileReader(); fr.onload = function () { resolve(fr.result); }; fr.readAsDataURL(b); });
                });
            }

            // Vector PDF: same layout as the page, real text and a scannable barcode.
            function buildPdf(logo, bottom) {
                var jsPDF = window.jspdf.jsPDF;
                bottom = bottom || 182;
                var W = 120, H = bottom + 26, X = 10, TW = 100;
                var doc = new jsPDF({ unit: 'mm', format: [W, H], orientation: 'portrait' });
                var bg = [241, 243, 249], navy = [0, 6, 35], slate = [100, 116, 139], light = [148, 163, 184], line = [226, 232, 240];
                var tone = { ok: [22, 163, 74], bad: [220, 38, 38], wait: [217, 119, 6], neutral: [100, 116, 139] }[data.state] || [100, 116, 139];
                doc.setProperties({ title: 'InternConnect receipt ' + data.reference, author: 'InternConnect', subject: 'Payment receipt' });
                doc.setFillColor.apply(doc, bg); doc.rect(0, 0, W, H, 'F');

                doc.setFillColor(255, 255, 255); doc.roundedRect(X, 10, TW, bottom - 10, 5, 5, 'F');
                doc.rect(X, bottom - 6, TW, 6, 'F');
                // Logo badge
                doc.setFillColor(246, 247, 255); doc.circle(60, 31, 12.5, 'F');
                doc.setFillColor(238, 240, 255); doc.circle(60, 31, 10.5, 'F');
                if (logo) { doc.addImage(logo, 'PNG', 60 - 4.3, 31 - 5, 8.6, 10); }
                doc.setFillColor(255, 255, 255); doc.circle(68, 38.5, 3.6, 'F');
                doc.setFillColor.apply(doc, tone); doc.circle(68, 38.5, 2.8, 'F');
                doc.setDrawColor(255, 255, 255); doc.setLineWidth(0.6);
                if (data.state === 'ok') { doc.lines([[0.9, 0.9], [1.6, -1.8]], 66.8, 38.6); }
                else if (data.state === 'bad') { doc.line(67, 37.5, 69, 39.5); doc.line(69, 37.5, 67, 39.5); }
                else { doc.line(66.9, 38.5, 69.1, 38.5); }
                // Title
                doc.setFont('helvetica', 'bold'); doc.setFontSize(19); doc.setTextColor.apply(doc, navy);
                doc.text(data.title, 60, 55, { align: 'center' });
                doc.setFont('helvetica', 'normal'); doc.setFontSize(9.5); doc.setTextColor.apply(doc, slate);
                doc.text(doc.splitTextToSize(data.subtitle, 76), 60, 61.5, { align: 'center', lineHeightFactor: 1.4 });
                // Perforation
                var py = 74;
                doc.setFillColor.apply(doc, bg); doc.circle(X, py, 3.6, 'F'); doc.circle(X + TW, py, 3.6, 'F');
                doc.setDrawColor.apply(doc, line); doc.setLineWidth(0.45); doc.setLineDashPattern([1.6, 1.4], 0);
                doc.line(X + 6, py, X + TW - 6, py); doc.setLineDashPattern([], 0);
                // Details
                // Returns the height used, so long values (Paystack references) wrap instead of being cut.
                function cell(label, value, sub, x, y, w) {
                    doc.setFont('helvetica', 'bold'); doc.setFontSize(7); doc.setTextColor.apply(doc, light);
                    doc.text(label.toUpperCase(), x, y, { charSpace: 0.35 });
                    doc.setFontSize(10.5); doc.setTextColor.apply(doc, navy);
                    var lines = [], parts = doc.splitTextToSize(value, w);
                    // Break unspaced references by width too.
                    parts.forEach(function (p) {
                        while (doc.getTextWidth(p) > w && p.length > 1) {
                            var n = p.length; while (n > 1 && doc.getTextWidth(p.slice(0, n)) > w) n--;
                            lines.push(p.slice(0, n)); p = p.slice(n);
                        }
                        lines.push(p);
                    });
                    lines = lines.slice(0, 2);
                    lines.forEach(function (l, i) { doc.text(l, x, y + 5.5 + i * 4.6); });
                    var h = 5.5 + (lines.length - 1) * 4.6;
                    if (sub) { doc.setFont('helvetica', 'normal'); doc.setFontSize(8.5); doc.setTextColor.apply(doc, slate); doc.text(doc.splitTextToSize(sub, w)[0], x, y + h + 4.5); h += 4.5; }
                    return h;
                }
                var c1 = X + 8, c2 = X + 54, y = 84;
                y += Math.max(cell('Ticket ID', data.reference, '', c1, y, 42), cell('Amount', data.amount, '', c2, y, 40)) + 9;
                y += Math.max(cell('Date & time', data.date.split(', ')[0], (data.date.split(', ')[1] || '') + ' WAT', c1, y, 42), cell('Plan', data.plan.split(' · ')[0], data.plan.split(' · ')[1] || '', c2, y, 40)) + 9;
                y += cell('Billed to', data.billedTo, data.email, c1, y, 84) + 9;
                if (data.reason) {
                    doc.setFillColor(254, 242, 242); doc.roundedRect(c1, y - 3.5, 84, 8, 2, 2, 'F');
                    doc.setFont('helvetica', 'normal'); doc.setFontSize(8.5); doc.setTextColor(153, 27, 27);
                    doc.text(doc.splitTextToSize('Reason: ' + data.reason, 80)[0], c1 + 2.5, y + 1.6);
                    y += 9;
                }
                // Payment method box
                doc.setDrawColor.apply(doc, line); doc.setLineWidth(0.4); doc.roundedRect(c1, y, 84, 15, 3, 3, 'S');
                doc.setFillColor(248, 250, 252); doc.roundedRect(c1 + 3, y + 3, 14, 9, 1.8, 1.8, 'F');
                if (data.brand.indexOf('master') !== -1) {
                    doc.setFillColor(235, 0, 27); doc.circle(c1 + 8.2, y + 7.5, 3, 'F');
                    doc.setFillColor(247, 158, 27); doc.circle(c1 + 11.8, y + 7.5, 3, 'F');
                } else {
                    doc.setFont('helvetica', 'bolditalic'); doc.setFontSize(7.5); doc.setTextColor(26, 31, 113);
                    doc.text(data.brand.indexOf('visa') !== -1 ? 'VISA' : (data.brand.indexOf('verve') !== -1 ? 'Verve' : 'PAY'), c1 + 10, y + 8.6, { align: 'center' });
                }
                doc.setFont('helvetica', 'bold'); doc.setFontSize(9.5); doc.setTextColor.apply(doc, navy);
                doc.text(doc.splitTextToSize(data.method, 62)[0], c1 + 20, y + 6.6);
                doc.setFont('helvetica', 'normal'); doc.setFontSize(8); doc.setTextColor.apply(doc, slate);
                doc.text(doc.splitTextToSize(data.methodSub, 62)[0], c1 + 20, y + 10.8);
                // Barcode
                var by = y + 22, bw = 72, total = 20, i;
                for (i = 0; i < data.pattern.length; i++) total += data.pattern[i];
                var unit = bw / total, bx = 60 - bw / 2 + 10 * unit;
                doc.setFillColor.apply(doc, navy);
                for (i = 0; i < data.pattern.length; i++) {
                    if (i % 2 === 0) doc.rect(bx, by, data.pattern[i] * unit, 13, 'F');
                    bx += data.pattern[i] * unit;
                }
                doc.setFont('courier', 'normal'); doc.setFontSize(8.5); doc.setTextColor(51, 65, 85);
                doc.setFontSize(data.reference.length > 18 ? 7.5 : 8.5);
                doc.text(data.reference.length > 18 ? data.reference : data.reference.split('').join(' '), 60, by + 18, { align: 'center' });
                // Torn bottom edge
                doc.setFillColor.apply(doc, bg);
                for (var sx = X + 2; sx <= X + TW; sx += 4) doc.circle(sx, bottom, 1.7, 'F');
                // Footer
                doc.setFont('helvetica', 'normal'); doc.setFontSize(7.5); doc.setTextColor.apply(doc, slate);
                doc.text(doc.splitTextToSize('Processed securely by Paystack. InternConnect never sees your full card number. Questions about this payment? Write to ' + data.support + ' with the ticket ID.', 96), 60, bottom + 9, { align: 'center', lineHeightFactor: 1.45 });
                doc.icEnd = by + 25;
                return doc;
            }

            var pdfBtn = document.getElementById('rc-pdf');
            var pngBtn = document.getElementById('rc-png');
            pdfBtn.dataset.label = 'Download PDF';
            pngBtn.dataset.label = 'Save image';

            pdfBtn.addEventListener('click', function () {
                busy(pdfBtn, true);
                var ready = window.jspdf ? Promise.resolve() : load(libs.jspdf);
                ready.then(function () { return imageData(data.logo).catch(function () { return null; }); })
                    .then(function (logo) { var end = buildPdf(logo).icEnd; buildPdf(logo, Math.max(182, end)).save(data.file + '.pdf'); toast('Receipt downloaded as PDF.'); })
                    .catch(function () { toast('The PDF could not be created. Please try again.', 'error'); })
                    .then(function () { busy(pdfBtn, false); });
            });

            pngBtn.addEventListener('click', function () {
                busy(pngBtn, true);
                var ready = window.html2canvas ? Promise.resolve() : load(libs.h2c);
                ready.then(function () {
                    return window.html2canvas(document.getElementById('rc-ticket'), { scale: Math.max(2, window.devicePixelRatio || 1), backgroundColor: '#F1F3F9', useCORS: true, logging: false });
                }).then(function (canvas) {
                    var a = document.createElement('a');
                    a.download = data.file + '.png';
                    a.href = canvas.toDataURL('image/png');
                    document.body.appendChild(a); a.click(); a.remove();
                    toast('Receipt saved as an image.');
                }).catch(function () {
                    toast('The image could not be created. Please try again.', 'error');
                }).then(function () { busy(pngBtn, false); });
            });
        })();
        </script>
    <?php endif; ?>
</div>
