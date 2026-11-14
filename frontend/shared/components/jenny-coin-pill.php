<?php
/**
 * Jenny Coins pill for page headers (docs/53, "Jenny Coins").
 * Set $coinType ('candidate' | 'employer') and $coinId before including.
 */
$jcp = \App\Services\PlanService::coins((string)($coinType ?? 'candidate'), (int)($coinId ?? 1));
$jcpWait = $jcp['unlimited'] ? 'Unlimited coins on your plan.'
    : ($jcp['refills_at'] ? 'Refills to ' . (int)$jcp['allowance'] . ' at ' . date('g:i A', strtotime((string)$jcp['refills_at'])) . '.' : 'Full. Refills ' . (int)$jcp['refill_hours'] . 'h after you start using them.');
?>
<span class="jcp" role="status" aria-label="Jenny Coins: <?= $jcp['unlimited'] ? 'unlimited' : (int)$jcp['balance'] . ' of ' . (int)$jcp['allowance'] ?>">
    <img src="<?= url('assets/images/jenny-coin.svg') ?>" width="20" height="20" alt="">
    <strong><?= $jcp['unlimited'] ? 'Unlimited' : (int)$jcp['balance'] . ' / ' . (int)$jcp['allowance'] ?></strong>
    <span class="jcp-tip">Jenny Coins. 1 per answer, 2 with a file. <?= htmlspecialchars($jcpWait, ENT_QUOTES, 'UTF-8') ?></span>
</span>
<?php if (empty($GLOBALS['jcpCss'])): $GLOBALS['jcpCss'] = true; ?>
<style>
    .jcp { position: relative; display: inline-flex; align-items: center; gap: 6px; height: 34px; padding: 0 12px 0 6px; border-radius: 999px; background: #FFFBEB; border: 1px solid #FDE68A; color: #92400E; font-size: 13px; white-space: nowrap; cursor: default; }
    .jcp img { width: 22px; height: 22px; display: block; }
    .jcp strong { font-weight: 800; }
    .jcp-tip { display: none; position: absolute; right: 0; top: calc(100% + 6px); width: 230px; white-space: normal; background: #000623; color: #FFFFFF; font-size: 11.5px; line-height: 1.45; padding: 8px 10px; border-radius: 10px; z-index: 30; box-shadow: 0 10px 25px -10px rgba(0, 6, 35, .5); }
    .jcp:hover .jcp-tip, .jcp:focus-within .jcp-tip { display: block; }
</style>
<?php endif; ?>
