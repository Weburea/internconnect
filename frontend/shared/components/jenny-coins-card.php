<?php
declare(strict_types=1);
/**
 * Jenny Coins card (docs/55 §1): balance, refill countdown and plan allowance.
 * Include on candidate pages; reads the signed-in candidate (demo #1 until accounts move to MySQL).
 */
$jcCoins = \App\Services\PlanService::coins('candidate', (int)($_SESSION['candidate_id'] ?? 1));
$jcPlan = ucfirst($jcCoins['plan']);
$jcPct = $jcCoins['unlimited'] ? 100 : (int)round(($jcCoins['balance'] / max(1, $jcCoins['allowance'])) * 100);
?>
<div class="db-card" style="margin-bottom: 24px; padding: 20px;"
     x-data="{ left: <?= (int)($jcCoins['refills_in'] ?? 0) ?>, init() { if (this.left > 0) setInterval(() => { if (this.left > 0) this.left--; }, 1000); }, fmt(s) { const h = Math.floor(s / 3600), m = Math.floor((s % 3600) / 60), x = s % 60; return (h ? h + 'h ' : '') + m + 'm ' + (h ? '' : x + 's'); } }">
    <div style="display: flex; align-items: center; gap: 16px; flex-wrap: wrap;">
        <img src="<?= url('assets/images/jenny-coin.svg') ?>" width="64" height="64" alt="Jenny Coin" style="width: 64px; height: 64px; flex-shrink: 0;">
        <div style="flex: 1 1 220px; min-width: 0;">
            <div style="display: flex; align-items: baseline; gap: 8px; flex-wrap: wrap;">
                <h3 style="font-size: 16px; font-weight: 800; color: #000623; margin: 0;">Jenny Coins</h3>
                <span style="font-size: 11px; font-weight: 800; letter-spacing: .05em; color: #4F48E6; background: #EEF0FF; padding: 2px 8px; border-radius: 999px; text-transform: uppercase;"><?= htmlspecialchars($jcPlan) ?> plan</span>
            </div>
            <div style="display: flex; align-items: baseline; gap: 6px; margin-top: 6px;">
                <?php if ($jcCoins['unlimited']): ?>
                    <span style="font-size: 26px; font-weight: 800; color: #000623;">Unlimited</span>
                <?php else: ?>
                    <span style="font-size: 26px; font-weight: 800; color: #000623;"><?= (int)$jcCoins['balance'] ?></span>
                    <span style="font-size: 14px; font-weight: 600; color: #64748B;">of <?= (int)$jcCoins['allowance'] ?> coins left</span>
                <?php endif; ?>
            </div>
            <div style="height: 8px; background: #F1F5F9; border-radius: 999px; overflow: hidden; margin-top: 8px;" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="<?= $jcPct ?>" aria-label="Jenny Coins left">
                <div style="height: 100%; width: <?= $jcPct ?>%; background: linear-gradient(90deg, #F59E0B, #FCD34D); border-radius: 999px;"></div>
            </div>
            <p style="font-size: 12.5px; color: #64748B; margin: 8px 0 0; line-height: 1.5;">
                <?php if ($jcCoins['unlimited']): ?>
                    Chat with Jenny as much as you need on your plan.
                <?php elseif ($jcCoins['refills_in']): ?>
                    Refills to <?= (int)$jcCoins['allowance'] ?> coins in <strong style="color: #0F172A;" x-text="left > 0 ? fmt(left) : 'a moment (reload)'"><?= htmlspecialchars(\App\Services\PlanService::duration((int)$jcCoins['refills_in'])) ?></strong>.
                <?php else: ?>
                    Full. Your coins refill <?= (int)$jcCoins['refill_hours'] ?> hours after you start using them.
                <?php endif; ?>
                1 coin per answer, 2 when Jenny reads a file.
            </p>
        </div>
        <div style="display: flex; gap: 8px; flex-wrap: wrap;">
            <button type="button" @click="window.dispatchEvent(new CustomEvent('open-jenny-ai'))" style="height: 38px; padding: 0 16px; border: 0; border-radius: 10px; background: #4F48E6; color: #FFFFFF; font-size: 13px; font-weight: 700; cursor: pointer; font-family: inherit; white-space: nowrap;">Chat with Jenny</button>
            <?php if ($jcCoins['plan'] !== 'premium'): ?>
                <a href="<?= url('/pricing?ref=jenny-coins') ?>" style="display: inline-flex; align-items: center; height: 38px; padding: 0 16px; border-radius: 10px; border: 1px solid #CBD5E1; color: #0F172A; font-size: 13px; font-weight: 700; text-decoration: none; white-space: nowrap;">Get more coins</a>
            <?php endif; ?>
        </div>
    </div>
</div>
