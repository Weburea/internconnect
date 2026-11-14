<?php
declare(strict_types=1);
/**
 * Admin: Plans & Limits (docs/53-plans-and-quotas.md).
 * Edit what Free / Pro / Premium include (-1 = unlimited, 0 = not included), the Premium listing rule,
 * and grant or revoke plans for an account (training partner grants appear here too).
 *
 * @var array      $staff
 * @var string     $activeNav
 * @var array      $matrix    feature => [plan => value]
 * @var array      $settings
 * @var array      $grants
 * @var array|null $flash
 */
use App\Services\PlanService;

$periodLabel = ['day' => 'per day', 'week' => 'per week', 'month' => 'per month', 'flag' => 'on / off', 'text' => 'shown as text', 'coins' => 'coins per refill (1 per answer, 2 with a file)', 'hours' => 'hours until coins refill (1-168)'];
$e = static fn ($v): string => htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');
?>
<style>
    .pl-card { background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 16px; padding: 20px; margin-bottom: 20px; }
    .pl-card h2 { font-size: 15px; font-weight: 700; color: #000623; margin: 0 0 4px; }
    .pl-card .pl-sub { font-size: 12.5px; color: #64748B; margin: 0 0 16px; line-height: 1.5; }
    .pl-table-wrap { width: 100%; overflow-x: auto; }
    .pl-table { width: 100%; min-width: 640px; border-collapse: collapse; font-size: 13px; }
    .pl-table th { text-align: left; font-size: 11px; font-weight: 700; letter-spacing: .04em; text-transform: uppercase; color: #64748B; padding: 8px 10px; border-bottom: 1px solid #E2E8F0; }
    .pl-table td { padding: 10px; border-bottom: 1px solid #F1F5F9; vertical-align: middle; color: #0F172A; }
    .pl-table td small { display: block; font-size: 11px; color: #94A3B8; margin-top: 2px; }
    .pl-input { width: 100%; height: 36px; border: 1px solid #CBD5E1; border-radius: 10px; padding: 0 10px; font-size: 13px; color: #0F172A; background: #FFFFFF; box-sizing: border-box; }
    .pl-input:focus { outline: none; border-color: #4F48E6; box-shadow: 0 0 0 3px rgba(79, 72, 230, .15); }
    .pl-btn { display: inline-flex; align-items: center; justify-content: center; height: 38px; padding: 0 16px; border-radius: 10px; border: 0; background: #4F48E6; color: #FFFFFF; font-size: 13px; font-weight: 700; cursor: pointer; text-decoration: none; }
    .pl-btn:hover { background: #4338CA; }
    .pl-btn-ghost { background: #FFFFFF; color: #0F172A; border: 1px solid #CBD5E1; }
    .pl-btn-ghost:hover { background: #F8FAFC; }
    .pl-btn-sm { height: 30px; padding: 0 12px; font-size: 12px; }
    .pl-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 12px; }
    .pl-field label { display: block; font-size: 12px; font-weight: 600; color: #334155; margin-bottom: 6px; }
    .pl-flash { border-radius: 12px; padding: 12px 14px; font-size: 13px; font-weight: 600; margin-bottom: 16px; }
    .pl-flash.ok { background: #ECFDF5; color: #047857; }
    .pl-flash.err { background: #FEF2F2; color: #B91C1C; }
    .pl-pill { display: inline-flex; align-items: center; padding: 2px 9px; border-radius: 999px; font-size: 11px; font-weight: 700; }
    .pl-pill.premium { background: #000623; color: #FFFFFF; }
    .pl-pill.pro { background: #EEF2FF; color: #4F48E6; }
    .pl-pill.active { background: #ECFDF5; color: #047857; }
    .pl-pill.revoked, .pl-pill.expired { background: #F1F5F9; color: #64748B; }
    .pl-acct-chip { display: flex; align-items: center; gap: 10px; min-height: 40px; padding: 6px 8px 6px 6px; border: 1px solid #C7D2FE; background: #F5F6FF; border-radius: 10px; }
    .pl-acct-chip strong, .pl-acct-opt strong { display: block; font-size: 13px; color: #0F172A; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .pl-acct-chip small, .pl-acct-opt small { display: block; font-size: 11.5px; color: #64748B; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .pl-acct-chip > span:nth-child(2) { flex: 1; min-width: 0; }
    .pl-acct-av { width: 30px; height: 30px; border-radius: 50%; background: #4F48E6; color: #FFFFFF; font-size: 13px; font-weight: 800; display: inline-flex; align-items: center; justify-content: center; flex-shrink: 0; text-transform: uppercase; }
    .pl-acct-menu { position: absolute; left: 0; right: 0; top: calc(100% + 6px); z-index: 30; background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 12px; box-shadow: 0 18px 40px -20px rgba(0, 6, 35, .35); padding: 6px; max-height: 300px; overflow-y: auto; }
    .pl-acct-opt { width: 100%; display: flex; align-items: center; gap: 10px; padding: 8px; border: 0; background: transparent; border-radius: 8px; cursor: pointer; text-align: left; font-family: inherit; }
    .pl-acct-opt:hover, .pl-acct-opt:focus-visible { background: #F5F6FF; outline: none; }
    .pl-acct-none { margin: 0; padding: 10px; font-size: 12.5px; color: #64748B; }
    .pl-who strong { display: block; color: #0F172A; }
    .pl-who small { display: block; font-size: 11.5px; color: #94A3B8; }
    @media (max-width: 720px) { .pl-acct { grid-column: auto !important; } }
    .pl-actions { display: flex; gap: 8px; flex-wrap: wrap; justify-content: flex-end; margin-top: 16px; }
    .pl-head { display: flex; align-items: flex-start; justify-content: space-between; gap: 12px; flex-wrap: wrap; }
    .pl-empty { font-size: 13px; color: #64748B; padding: 16px 0; text-align: center; }
    @media (max-width: 640px) { .pl-card { padding: 16px; } }
</style>

<div class="admin-shell">
    <?php include dirname(__DIR__, 2) . '/shared/components/admin-sidebar.php'; ?>

    <div class="admin-main-wrap">
        <?php include dirname(__DIR__, 2) . '/shared/components/admin-topnav.php'; ?>

        <main class="admin-content-body">
            <div class="admin-page-header mb-6 pl-head">
                <div style="min-width: 0;">
                    <h1 class="admin-page-title">Plans &amp; Limits</h1>
                    <p class="admin-page-subtitle">What Free, Pro and Premium include. Changes apply to every account immediately.</p>
                </div>
                <a href="<?= url('/admin/subscriptions') ?>" class="pl-btn pl-btn-ghost">Back to Subscriptions</a>
            </div>

            <?php if ($flash): ?>
                <div class="pl-flash <?= $flash['ok'] ? 'ok' : 'err' ?>" role="status"><?= $e($flash['text']) ?></div>
            <?php endif; ?>

            <!-- Limits matrix -->
            <form method="POST" action="<?= url('/admin/subscriptions/plans') ?>" class="pl-card">
                <h2>Plan limits</h2>
                <p class="pl-sub">Numbers are per period. Use <strong>-1</strong> for unlimited and <strong>0</strong> when the plan does not include the feature. Prices stay in the payment settings.</p>
                <div class="pl-table-wrap no-scrollbar">
                    <table class="pl-table">
                        <thead>
                            <tr><th style="width: 38%;">Feature</th><th>Free</th><th>Pro</th><th>Premium</th></tr>
                        </thead>
                        <tbody>
                        <?php foreach (PlanService::DEFAULTS as $key => $d): ?>
                            <tr>
                                <td><?= $e($d[0]) ?><small><?= $e($periodLabel[$d[1]] ?? '') ?></small></td>
                                <?php foreach (PlanService::PLANS as $plan): $val = $matrix[$key][$plan] ?? ''; ?>
                                    <td>
                                        <?php if ($d[1] === 'flag'): ?>
                                            <select data-ic-select class="pl-input" name="f[<?= $e($key) ?>][<?= $plan ?>]" aria-label="<?= $e($d[0] . ' ' . $plan) ?>">
                                                <option value="1" <?= $val === '1' ? 'selected' : '' ?>>Included</option>
                                                <option value="0" <?= $val !== '1' ? 'selected' : '' ?>>Not included</option>
                                            </select>
                                        <?php else: ?>
                                            <input class="pl-input" name="f[<?= $e($key) ?>][<?= $plan ?>]" value="<?= $e($val) ?>" <?= $d[1] === 'text' ? 'maxlength="120"' : 'inputmode="numeric" pattern="-?[0-9]+"' ?> aria-label="<?= $e($d[0] . ' ' . $plan) ?>">
                                        <?php endif; ?>
                                    </td>
                                <?php endforeach; ?>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <h2 style="margin-top: 22px;">Premium listings</h2>
                <p class="pl-sub">Listings that only Premium members can open. Everyone else sees them locked with an upgrade prompt.</p>
                <div class="pl-grid">
                    <div class="pl-field">
                        <label for="pl-stipend">Minimum monthly stipend (NGN)</label>
                        <input id="pl-stipend" class="pl-input" name="s[premium_stipend_min]" inputmode="numeric" pattern="[0-9]+" value="<?= $e($settings['premium_stipend_min']) ?>">
                    </div>
                    <div class="pl-field">
                        <label for="pl-flex">Work mode</label>
                        <select id="pl-flex" name="s[premium_needs_flex]" class="pl-input" data-ic-select>
                            <option value="1" <?= $settings['premium_needs_flex'] === '1' ? 'selected' : '' ?>>Hybrid or remote only</option>
                            <option value="0" <?= $settings['premium_needs_flex'] !== '1' ? 'selected' : '' ?>>Any work mode</option>
                        </select>
                    </div>
                </div>
                <div class="pl-actions"><button type="submit" class="pl-btn">Save limits</button></div>
            </form>

            <!-- Manual grant -->
            <form method="POST" action="<?= url('/admin/subscriptions/plans/grant') ?>" class="pl-card">
                <h2>Grant a plan</h2>
                <p class="pl-sub">Free access for an account (scholarship, support case, testing). A grant never replaces a paid plan: the same plan is added after it ends, a higher plan applies now.</p>
                <div class="pl-grid">
                    <div class="pl-field pl-acct" style="grid-column: span 2;" x-data="{ q: '', list: [], open: false, pick: null, busy: false, t: null,
                            find() { clearTimeout(this.t); this.t = setTimeout(async () => { this.busy = true;
                                try { const r = await fetch(<?= $e(json_encode(url('/admin/subscriptions/plans/accounts'))) ?> + '?q=' + encodeURIComponent(this.q), { headers: { Accept: 'application/json' } }); const d = await r.json(); this.list = d.accounts || []; this.open = true; } catch (e) { this.list = []; }
                                this.busy = false; }, 180); },
                            choose(a) { this.pick = a; this.open = false; this.q = ''; } }" @click.outside="open = false">
                        <label for="pl-acct">Account</label>
                        <input type="hidden" name="account_type" :value="pick ? pick.type : ''">
                        <input type="hidden" name="account_id" :value="pick ? pick.id : ''">
                        <div class="pl-acct-chip" x-show="pick" x-cloak>
                            <span class="pl-acct-av" x-text="pick ? pick.name.charAt(0) : ''"></span>
                            <span style="min-width: 0;"><strong x-text="pick && pick.name"></strong><small x-text="pick ? (pick.email ? pick.email + ' · ' : '') + pick.label : ''"></small></span>
                            <button type="button" class="pl-btn pl-btn-ghost pl-btn-sm" @click="pick = null; $nextTick(() => $refs.acct.focus())">Change</button>
                        </div>
                        <div x-show="!pick" style="position: relative;">
                            <input id="pl-acct" x-ref="acct" class="pl-input" x-model="q" @input="find()" @focus="find()" autocomplete="off" placeholder="Search by name, e-mail or account number" :required="!pick">
                            <div class="pl-acct-menu" x-show="open" x-cloak>
                                <template x-for="a in list" :key="a.type + a.id">
                                    <button type="button" class="pl-acct-opt" @click="choose(a)">
                                        <span class="pl-acct-av" x-text="a.name.charAt(0)"></span>
                                        <span style="min-width: 0;"><strong x-text="a.name"></strong><small x-text="(a.email ? a.email + ' · ' : '') + a.label"></small></span>
                                    </button>
                                </template>
                                <p x-show="!busy && list.length === 0" class="pl-acct-none">No account matches that search.</p>
                            </div>
                        </div>
                    </div>
                    <div class="pl-field">
                        <label for="pl-plan">Plan</label>
                        <select id="pl-plan" name="plan" class="pl-input" data-ic-select>
                            <option value="pro">Pro</option>
                            <option value="premium">Premium</option>
                        </select>
                    </div>
                    <div class="pl-field">
                        <label for="pl-days">Days</label>
                        <input id="pl-days" class="pl-input" name="days" inputmode="numeric" pattern="[0-9]+" required value="30">
                    </div>
                </div>
                <div class="pl-field" style="margin-top: 12px;">
                    <label for="pl-note">Reason (shown to the account)</label>
                    <input id="pl-note" class="pl-input" name="note" maxlength="200" placeholder="For example: Scholarship, Support credit, Testing access">
                </div>
                <div class="pl-actions"><button type="submit" class="pl-btn">Grant plan</button></div>
            </form>

            <!-- Grants -->
            <div class="pl-card">
                <h2>Recent grants</h2>
                <p class="pl-sub">Admin grants and plans unlocked through training partners. Revoking ends the grant now; paid plans are not affected.</p>
                <?php if (!$grants): ?>
                    <div class="pl-empty">No grants yet.</div>
                <?php else: ?>
                    <div class="pl-table-wrap no-scrollbar">
                        <table class="pl-table">
                            <thead><tr><th>Account</th><th>Plan</th><th>Source</th><th>Period</th><th>Status</th><th></th></tr></thead>
                            <tbody>
                            <?php foreach ($grants as $g):
                                $status = $g['status'] === 'active' && strtotime($g['expires_at']) <= time() ? 'expired' : $g['status'];
                                $upcoming = $status === 'active' && strtotime($g['starts_at']) > time(); ?>
                                <tr>
                                    <?php $who = \App\Services\AccountDirectory::describe((string)$g['account_type'], (int)$g['account_id']); ?>
                                    <td class="pl-who"><strong><?= $e($who['name']) ?></strong><small><?= $e(($who['email'] !== '' ? $who['email'] . ' · ' : '') . $who['label']) ?></small></td>
                                    <td><span class="pl-pill <?= $e($g['plan']) ?>"><?= $e(ucfirst($g['plan'])) ?></span></td>
                                    <td><?= $e(ucfirst($g['source'])) ?><small><?= $e($g['note'] ?? '') ?></small></td>
                                    <td><?= $e(date('j M Y', strtotime($g['starts_at']))) ?> to <?= $e(date('j M Y', strtotime($g['expires_at']))) ?></td>
                                    <td><span class="pl-pill <?= $e($status) ?>"><?= $upcoming ? 'Starts later' : $e(ucfirst($status)) ?></span></td>
                                    <td style="text-align: right;">
                                        <?php if ($status === 'active'): ?>
                                            <form method="POST" action="<?= url('/admin/subscriptions/plans/grants/' . (int)$g['id'] . '/revoke') ?>" style="margin: 0;">
                                                <button type="submit" class="pl-btn pl-btn-ghost pl-btn-sm">Revoke</button>
                                            </form>
                                        <?php elseif (in_array($status, ['revoked', 'expired'], true)): ?>
                                            <form method="POST" action="<?= url('/admin/subscriptions/plans/grants/' . (int)$g['id'] . '/reactivate') ?>" style="margin: 0;">
                                                <button type="submit" class="pl-btn pl-btn-ghost pl-btn-sm">Reactivate</button>
                                            </form>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </main>
    </div>
</div>
