<?php
declare(strict_types=1);
/**
 * Admin > E-Learning > Training partners (docs/57-training-partners.md, partner API: docs/58-partner-api.md)
 *
 * @var string     $tab        partners | rules | applications | results | activity
 * @var array|null $flash
 * @var array|null $newKeys    shown once after a key rotation
 * @var bool       $canEdit    false for Customer Care (read-only)
 * @var array      $partners
 * @var array|null $edit
 * @var array      $directory  e-learning directory partners (to link a listing)
 * @var int|null   $rulesFor
 * @var array      $rules
 * @var bool       $hasOwnRules
 * @var array      $applications
 * @var array      $results
 * @var array      $distribution 10 bands
 * @var array      $auditLog
 * @var string     $apiBase
 */
$e = static fn ($v): string => htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');
$when = static fn ($v): string => $v ? date('j M Y, g:i A', strtotime((string)$v)) : '—';
$tabs = ['partners' => 'Partners', 'rules' => 'Pass mark & tiers', 'applications' => 'Applications', 'results' => 'Results', 'activity' => 'Activity & API log'];
$pill = static function (string $s): string {
    $m = ['active' => ['#ECFDF5', '#047857'], 'completed' => ['#ECFDF5', '#047857'], 'paused' => ['#FFFBEB', '#B45309'], 'started' => ['#EEF2FF', '#4F48E6'], 'enrolled' => ['#EEF2FF', '#4F48E6'], 'in_progress' => ['#EEF2FF', '#4F48E6'],
        'failed' => ['#FEF2F2', '#B91C1C'], 'revoked' => ['#FEF2F2', '#B91C1C'], 'superseded' => ['#F1F5F9', '#64748B'], 'withdrawn' => ['#F1F5F9', '#64748B'], 'expired' => ['#F1F5F9', '#64748B']];
    [$bg, $fg] = $m[$s] ?? ['#F1F5F9', '#475569'];
    return '<span class="tp-pill" style="background:' . $bg . ';color:' . $fg . ';">' . htmlspecialchars(ucfirst(str_replace('_', ' ', $s)), ENT_QUOTES) . '</span>';
};
$form = $edit ?? [];
$partnerName = [];
foreach ($partners as $p) {
    $partnerName[(int)$p['id']] = $p['name'];
}
$maxBand = max(1, ...$distribution);
// 100.00 -> 100, 84.99 -> 84.99, 0 -> 0 (never trims zeros of whole numbers).
$num = static fn ($v): string => ($v === '' || $v === null) ? '' : rtrim(rtrim(number_format((float)$v, 2, '.', ''), '0'), '.');
?>
<style>
    .tp-card { background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 16px; padding: 20px; margin-bottom: 20px; min-width: 0; }
    .tp-card h2 { font-size: 15px; font-weight: 700; color: #000623; margin: 0 0 4px; }
    .tp-sub { font-size: 12.5px; color: #64748B; margin: 0 0 16px; line-height: 1.55; }
    .tp-tabs { display: flex; gap: 4px; padding: 4px; background: #F1F5F9; border-radius: 12px; overflow-x: auto; margin-bottom: 20px; }
    .tp-tab { flex: 0 0 auto; padding: 8px 14px; border-radius: 9px; font-size: 13px; font-weight: 600; color: #64748B; text-decoration: none; white-space: nowrap; }
    .tp-tab.is-active { background: #FFFFFF; color: #000623; box-shadow: 0 1px 2px rgba(15, 23, 42, .08); }
    .tp-wrap { width: 100%; overflow-x: auto; }
    .tp-table { width: 100%; min-width: 720px; border-collapse: collapse; font-size: 13px; }
    .tp-table th { text-align: left; font-size: 11px; font-weight: 700; letter-spacing: .04em; text-transform: uppercase; color: #64748B; padding: 8px 10px; border-bottom: 1px solid #E2E8F0; white-space: nowrap; }
    .tp-table td { padding: 10px; border-bottom: 1px solid #F1F5F9; vertical-align: middle; color: #0F172A; }
    .tp-table td small { display: block; font-size: 11.5px; color: #94A3B8; margin-top: 2px; }
    .tp-pill { display: inline-flex; padding: 2px 9px; border-radius: 999px; font-size: 11px; font-weight: 700; white-space: nowrap; }
    .tp-input { width: 100%; height: 38px; border: 1px solid #CBD5E1; border-radius: 10px; padding: 0 11px; font-size: 13.5px; color: #0F172A; background: #FFFFFF; box-sizing: border-box; font-family: inherit; }
    .tp-input:focus { outline: none; border-color: #4F48E6; box-shadow: 0 0 0 3px rgba(79, 72, 230, .15); }
    textarea.tp-input { height: 72px; padding: 9px 11px; resize: vertical; }
    .tp-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(210px, 1fr)); gap: 12px; }
    .tp-field label { display: block; font-size: 12px; font-weight: 600; color: #334155; margin-bottom: 5px; }
    .tp-hint { font-size: 11.5px; color: #94A3B8; margin-top: 4px; }
    .tp-btn { display: inline-flex; align-items: center; justify-content: center; height: 38px; padding: 0 16px; border-radius: 10px; border: 0; background: #4F48E6; color: #FFFFFF; font-size: 13px; font-weight: 700; cursor: pointer; text-decoration: none; white-space: nowrap; font-family: inherit; }
    .tp-btn:hover { background: #4338CA; }
    .tp-btn-ghost { background: #FFFFFF; color: #0F172A; border: 1px solid #CBD5E1; }
    .tp-btn-ghost:hover { background: #F8FAFC; }
    .tp-btn-danger { background: #B91C1C; }
    .tp-btn-danger:hover { background: #991B1B; }
    .tp-btn-sm { height: 30px; padding: 0 11px; font-size: 12px; }
    .tp-row { display: flex; gap: 8px; flex-wrap: wrap; align-items: center; }
    .tp-between { display: flex; gap: 12px; flex-wrap: wrap; align-items: flex-start; justify-content: space-between; }
    .tp-flash { border-radius: 12px; padding: 12px 14px; font-size: 13px; font-weight: 600; margin-bottom: 16px; }
    .tp-flash.ok { background: #ECFDF5; color: #047857; }
    .tp-flash.err { background: #FEF2F2; color: #B91C1C; }
    .tp-note { background: #F8FAFC; border-radius: 12px; padding: 12px 14px; font-size: 12.5px; color: #475569; line-height: 1.6; }
    .tp-keys { background: #000623; color: #FFFFFF; border-radius: 16px; padding: 18px; margin-bottom: 20px; }
    .tp-keys code { display: block; background: rgba(255, 255, 255, .08); border-radius: 10px; padding: 10px 12px; font-size: 12.5px; overflow-wrap: anywhere; margin-top: 6px; }
    .tp-empty { font-size: 13px; color: #64748B; padding: 22px 0; text-align: center; }
    .tp-bars { display: grid; grid-template-columns: repeat(10, minmax(0, 1fr)); gap: 6px; align-items: end; height: 140px; }
    .tp-bar { background: #EEF0FF; border-radius: 8px 8px 0 0; position: relative; min-height: 4px; }
    .tp-bar.is-pass { background: #4F48E6; }
    .tp-bar span { position: absolute; top: -18px; left: 0; right: 0; text-align: center; font-size: 11px; font-weight: 700; color: #334155; }
    .tp-bar-labels { display: grid; grid-template-columns: repeat(10, minmax(0, 1fr)); gap: 6px; margin-top: 6px; font-size: 10.5px; color: #94A3B8; text-align: center; }
    @media (max-width: 640px) { .tp-card { padding: 16px; } }
</style>

<div class="admin-shell">
    <?php include dirname(__DIR__, 2) . '/shared/components/admin-sidebar.php'; ?>
    <div class="admin-main-wrap">
        <?php include dirname(__DIR__, 2) . '/shared/components/admin-topnav.php'; ?>
        <main class="admin-content-body">
            <div class="admin-page-header mb-6 tp-between">
                <div style="min-width: 0;">
                    <h1 class="admin-page-title">Training Partners</h1>
                    <p class="admin-page-subtitle">Certifying partners such as Jigide. Their students' scores unlock free Pro or Premium time, using the rules you set here.</p>
                </div>
                <a href="<?= url('/admin/elearning') ?>" class="tp-btn tp-btn-ghost">Back to E-Learning</a>
            </div>

            <?php if ($flash): ?><div class="tp-flash <?= $flash['ok'] ? 'ok' : 'err' ?>" role="status"><?= $e($flash['text']) ?></div><?php endif; ?>
            <?php if (!$canEdit): ?><div class="tp-note" style="margin-bottom: 16px;"><strong>View only.</strong> Your role can see partners, results and logs. A Platform Admin makes changes.</div><?php endif; ?>

            <?php if ($newKeys): ?>
                <div class="tp-keys" x-data="{ copied: '' }">
                    <div style="font-size: 14px; font-weight: 800;">New credentials for <?= $e($newKeys['partner']) ?></div>
                    <p style="font-size: 12.5px; color: #CBD5E1; margin: 4px 0 10px;">Copy both now and send them to the partner's developers over a secure channel. They are shown only once; InternConnect stores a hash of the key and an encrypted secret.</p>
                    <div style="font-size: 11px; font-weight: 700; letter-spacing: .05em; color: #A5B4FC;">API KEY (X-Partner-Key)</div>
                    <code id="tp-key"><?= $e($newKeys['api_key']) ?></code>
                    <div style="font-size: 11px; font-weight: 700; letter-spacing: .05em; color: #A5B4FC; margin-top: 10px;">SIGNING SECRET (HMAC-SHA256)</div>
                    <code id="tp-secret"><?= $e($newKeys['signing_secret']) ?></code>
                    <div class="tp-row" style="margin-top: 12px;">
                        <button type="button" class="tp-btn tp-btn-sm" @click="navigator.clipboard.writeText(document.getElementById('tp-key').textContent); copied = 'key'">Copy key</button>
                        <button type="button" class="tp-btn tp-btn-sm" @click="navigator.clipboard.writeText(document.getElementById('tp-secret').textContent); copied = 'secret'">Copy secret</button>
                        <span x-show="copied" style="font-size: 12px; color: #86EFAC;" x-text="copied === 'key' ? 'Key copied' : 'Secret copied'"></span>
                    </div>
                </div>
            <?php endif; ?>

            <nav class="tp-tabs no-scrollbar" aria-label="Training partner sections">
                <?php foreach ($tabs as $k => $label): ?>
                    <a href="<?= url('/admin/elearning/partners?tab=' . $k) ?>" class="tp-tab <?= $tab === $k ? 'is-active' : '' ?>"><?= $e($label) ?></a>
                <?php endforeach; ?>
            </nav>

            <?php if ($tab === 'partners'): ?>
                <div class="tp-card">
                    <div class="tp-between" style="margin-bottom: 12px;">
                        <div><h2>Partners</h2><p class="tp-sub" style="margin: 0;">Only active partners with an application link appear to students.</p></div>
                        <?php if ($canEdit): ?><a class="tp-btn" href="<?= url('/admin/elearning/partners?tab=partners&edit=0#tp-form') ?>">Add partner</a><?php endif; ?>
                    </div>
                    <div class="tp-wrap no-scrollbar">
                        <table class="tp-table">
                            <thead><tr><th>Partner</th><th>Status</th><th>Pass mark</th><th>API key</th><th>Application window</th><th></th></tr></thead>
                            <tbody>
                            <?php foreach ($partners as $p): ?>
                                <tr x-data="{ confirm: false }">
                                    <td><strong><?= $e($p['name']) ?></strong><small><?= $e($p['application_url'] ?: 'No application link yet') ?></small></td>
                                    <td><?= $pill($p['status']) ?></td>
                                    <td><?= rtrim(rtrim(number_format((float)$p['pass_mark'], 2), '0'), '.') ?>%</td>
                                    <td><?= $p['api_key_prefix'] ? '<code>icp_' . $e($p['api_key_prefix']) . '_...</code><small>Rotated ' . $e($when($p['key_rotated_at'])) . '</small>' : '<span style="color:#94A3B8;">No key yet</span>' ?></td>
                                    <td><?= (int)$p['application_days'] ?> days</td>
                                    <td style="text-align: right;">
                                        <?php if ($canEdit): ?>
                                            <div class="tp-row" style="justify-content: flex-end;">
                                                <a class="tp-btn tp-btn-ghost tp-btn-sm" href="<?= url('/admin/elearning/partners?tab=partners&edit=' . (int)$p['id'] . '#tp-form') ?>">Edit</a>
                                                <button type="button" class="tp-btn tp-btn-ghost tp-btn-sm" x-show="!confirm" @click="confirm = true"><?= $p['api_key_prefix'] ? 'Rotate key' : 'Create key' ?></button>
                                                <form x-show="confirm" style="display: none; margin: 0;" method="POST" action="<?= url('/admin/elearning/training-partners/' . (int)$p['id'] . '/keys') ?>" class="tp-row">
                                                    <span style="font-size: 12px; color: #B91C1C;"><?= $p['api_key_prefix'] ? 'The current key stops working.' : 'Create credentials?' ?></span>
                                                    <button type="submit" class="tp-btn tp-btn-danger tp-btn-sm">Confirm</button>
                                                    <button type="button" class="tp-btn tp-btn-ghost tp-btn-sm" @click="confirm = false">Cancel</button>
                                                </form>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <?php if ($canEdit && $edit !== null || ($canEdit && isset($_GET['edit']))): ?>
                    <form id="tp-form" class="tp-card" method="POST" action="<?= url('/admin/elearning/training-partners' . (!empty($form['id']) ? '/' . (int)$form['id'] : '')) ?>">
                        <h2><?= !empty($form['id']) ? 'Edit ' . $e($form['name']) : 'Add a training partner' ?></h2>
                        <p class="tp-sub">Students are sent to the application link with <code>ic_ref</code> (their reference), <code>ic_ts</code> and <code>ic_sig</code> (HMAC of reference + "." + timestamp with the signing secret) so the partner can check the link came from InternConnect.</p>
                        <div class="tp-grid">
                            <div class="tp-field"><label for="tp-name">Name</label><input id="tp-name" class="tp-input" name="name" required maxlength="120" value="<?= $e($form['name'] ?? '') ?>"></div>
                            <div class="tp-field"><label for="tp-slug">Short name</label><input id="tp-slug" class="tp-input" name="slug" maxlength="60" value="<?= $e($form['slug'] ?? '') ?>" placeholder="jigide"></div>
                            <div class="tp-field"><label for="tp-status">Status</label>
                                <select data-ic-select id="tp-status" name="status" class="tp-input">
                                    <option value="active" <?= ($form['status'] ?? '') === 'active' ? 'selected' : '' ?>>Active</option>
                                    <option value="paused" <?= ($form['status'] ?? 'paused') !== 'active' ? 'selected' : '' ?>>Paused</option>
                                </select>
                            </div>
                            <div class="tp-field"><label for="tp-pass">Pass mark (%)</label><input id="tp-pass" class="tp-input" name="pass_mark" inputmode="decimal" value="<?= $e(isset($form['pass_mark']) ? $num($form['pass_mark']) : '50') ?>"><div class="tp-hint">Scores below this never unlock a plan or a badge.</div></div>
                        </div>
                        <div class="tp-grid" style="margin-top: 12px;">
                            <div class="tp-field"><label for="tp-url">Application link</label><input id="tp-url" class="tp-input" name="application_url" maxlength="500" value="<?= $e($form['application_url'] ?? '') ?>" placeholder="https://partner.com/apply"></div>
                            <div class="tp-field"><label for="tp-web">Website</label><input id="tp-web" class="tp-input" name="website" maxlength="255" value="<?= $e($form['website'] ?? '') ?>" placeholder="https://partner.com"></div>
                            <div class="tp-field"><label for="tp-mail">Technical contact e-mail</label><input id="tp-mail" class="tp-input" name="contact_email" type="email" maxlength="190" value="<?= $e($form['contact_email'] ?? '') ?>"></div>
                            <div class="tp-field"><label for="tp-days">Application window (days)</label><input id="tp-days" class="tp-input" name="application_days" inputmode="numeric" value="<?= (int)($form['application_days'] ?? 120) ?>"><div class="tp-hint">Unfinished applications expire after this.</div></div>
                        </div>
                        <div class="tp-grid" style="margin-top: 12px;">
                            <div class="tp-field"><label for="tp-dir">Show with E-learning listing</label>
                                <select data-ic-select id="tp-dir" name="elearning_partner_id" class="tp-input">
                                    <option value="0">Not linked</option>
                                    <?php foreach ($directory as $d): ?>
                                        <option value="<?= (int)$d['id'] ?>" <?= (int)($form['elearning_partner_id'] ?? 0) === (int)$d['id'] ? 'selected' : '' ?>><?= $e($d['name'] ?? $d['platform'] ?? ('#' . $d['id'])) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="tp-field"><label for="tp-notes">Internal notes</label><textarea id="tp-notes" class="tp-input" name="notes" maxlength="500"><?= $e($form['notes'] ?? '') ?></textarea></div>
                        </div>
                        <div class="tp-row" style="justify-content: flex-end; margin-top: 14px;">
                            <a class="tp-btn tp-btn-ghost" href="<?= url('/admin/elearning/partners?tab=partners') ?>">Cancel</a>
                            <button type="submit" class="tp-btn">Save partner</button>
                        </div>
                    </form>
                <?php endif; ?>

                <div class="tp-card">
                    <h2>Integration for partner developers</h2>
                    <p class="tp-sub">Send the partner the API key and signing secret (Create key above), this base URL and the developer guide (docs/58-partner-api.md). The endpoints are also in the API reference under "Training partners".</p>
                    <div class="tp-note"><strong>Base URL:</strong> <code><?= $e($apiBase) ?></code><br><strong>Endpoints:</strong> POST /results, POST /applications/status, GET /students/{reference}<br><strong>Headers:</strong> X-Partner-Key, X-Timestamp, X-Signature, Idempotency-Key (POST)</div>
                    <div class="tp-row" style="margin-top: 12px;"><a class="tp-btn tp-btn-ghost" href="<?= url('/admin/api-docs') ?>">Open API reference</a></div>
                </div>

            <?php elseif ($tab === 'rules'): ?>
                <div class="tp-card">
                    <div class="tp-between" style="margin-bottom: 12px;">
                        <div style="min-width: 0;"><h2>Score to plan rules</h2><p class="tp-sub" style="margin: 0;">Global rules apply to every partner without its own. Give a partner its own rules to change only that partner. Old rules are kept for the history of past results.</p></div>
                        <form method="GET" action="<?= url('/admin/elearning/partners') ?>" class="tp-row">
                            <input type="hidden" name="tab" value="rules">
                            <select data-ic-select name="partner" class="tp-input" style="width: 220px;" onchange="this.form.submit()" aria-label="Rules for">
                                <option value="0" <?= !$rulesFor ? 'selected' : '' ?>>Global defaults</option>
                                <?php foreach ($partners as $p): ?><option value="<?= (int)$p['id'] ?>" <?= $rulesFor === (int)$p['id'] ? 'selected' : '' ?>><?= $e($p['name']) ?></option><?php endforeach; ?>
                            </select>
                        </form>
                    </div>
                    <?php if ($rulesFor && !$hasOwnRules): ?>
                        <div class="tp-note" style="margin-bottom: 12px;"><?= $e($partnerName[$rulesFor] ?? 'This partner') ?> uses the global defaults below. Saving here gives it its own rules.</div>
                    <?php endif; ?>
                    <?php if ($rulesFor): ?>
                        <div class="tp-note" style="margin-bottom: 12px;">Pass mark for <?= $e($partnerName[$rulesFor] ?? '') ?>: <strong><?= rtrim(rtrim(number_format((float)(array_values(array_filter($partners, fn ($p) => (int)$p['id'] === $rulesFor))[0]['pass_mark'] ?? 50), 2), '0'), '.') ?>%</strong> (change it in Partners).</div>
                    <?php endif; ?>
                    <form method="POST" action="<?= url('/admin/elearning/tier-rules') ?>">
                        <input type="hidden" name="partner_id" value="<?= (int)$rulesFor ?>">
                        <fieldset style="border: 0; padding: 0; margin: 0;" <?= $canEdit ? '' : 'disabled' ?>>
                            <div class="tp-wrap no-scrollbar">
                                <table class="tp-table" style="min-width: 600px;">
                                    <thead><tr><th>From score</th><th>To score</th><th>Plan unlocked</th><th>Days</th><th>On</th></tr></thead>
                                    <tbody>
                                    <?php $rows = $rules; for ($i = 0; $i < 2; $i++) { $rows[] = ['min_score' => '', 'max_score' => '', 'plan' => 'none', 'duration_days' => '', 'is_active' => 1]; } ?>
                                    <?php foreach ($rows as $i => $r): ?>
                                        <tr>
                                            <td><input class="tp-input" name="rules[<?= $i ?>][min_score]" inputmode="decimal" value="<?= $e($num($r['min_score'])) ?>" aria-label="From score"></td>
                                            <td><input class="tp-input" name="rules[<?= $i ?>][max_score]" inputmode="decimal" value="<?= $e($num($r['max_score'])) ?>" aria-label="To score"></td>
                                            <td>
                                                <select data-ic-select class="tp-input" name="rules[<?= $i ?>][plan]" aria-label="Plan">
                                                    <?php foreach (['premium' => 'Premium', 'pro' => 'Pro', 'none' => 'No plan (badge only)'] as $k => $label): ?><option value="<?= $k ?>" <?= $r['plan'] === $k ? 'selected' : '' ?>><?= $label ?></option><?php endforeach; ?>
                                                </select>
                                            </td>
                                            <td><input class="tp-input" name="rules[<?= $i ?>][duration_days]" inputmode="numeric" value="<?= $e($r['duration_days']) ?>" aria-label="Days" style="max-width: 110px;"></td>
                                            <td><input type="checkbox" name="rules[<?= $i ?>][is_active]" value="1" <?= !empty($r['is_active']) && $r['min_score'] !== '' ? 'checked' : '' ?> style="width: 17px; height: 17px;" aria-label="Rule on"></td>
                                        </tr>
                                    <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            <p class="tp-hint" style="margin-top: 8px;">Leave a row empty to remove it. Ranges must not overlap. Grants never replace a paid plan: the same plan is added after it, a higher plan applies at once.</p>
                            <?php if ($canEdit): ?>
                                <div class="tp-row" style="justify-content: flex-end; margin-top: 12px;">
                                    <?php if ($rulesFor && $hasOwnRules): ?><button type="submit" name="use_defaults" value="1" class="tp-btn tp-btn-ghost">Use global defaults</button><?php endif; ?>
                                    <button type="submit" class="tp-btn">Save rules</button>
                                </div>
                            <?php endif; ?>
                        </fieldset>
                    </form>
                </div>

            <?php elseif ($tab === 'applications'): ?>
                <div class="tp-card">
                    <h2>Applications</h2>
                    <p class="tp-sub">Students who started a partner course from InternConnect. The partner sees only the reference.</p>
                    <?php if (!$applications): ?><div class="tp-empty">No applications yet.</div><?php else: ?>
                        <div class="tp-wrap no-scrollbar"><table class="tp-table">
                            <thead><tr><th>Reference</th><th>Partner</th><th>Candidate</th><th>Programme</th><th>Status</th><th>Started</th><th>Last update</th></tr></thead>
                            <tbody><?php foreach ($applications as $a): ?>
                                <tr><td><code><?= $e($a['reference']) ?></code></td><td><?= $e($a['partner_name']) ?></td><td>#<?= (int)$a['candidate_id'] ?></td><td><?= $e($a['program'] ?: '—') ?></td><td><?= $pill($a['status']) ?></td><td><?= $e($when($a['created_at'])) ?></td><td><?= $e($when($a['status_at'])) ?></td></tr>
                            <?php endforeach; ?></tbody>
                        </table></div>
                    <?php endif; ?>
                </div>

            <?php elseif ($tab === 'results'): ?>
                <?php if ($canEdit): ?>
                    <form class="tp-card" method="POST" action="<?= url('/admin/elearning/training-results') ?>">
                        <h2>Add or correct a result by hand</h2>
                        <p class="tp-sub">For a partner that cannot use the API yet, or to fix a mistake. A new score for the same reference replaces the old one (kept in history) and adjusts the plan.</p>
                        <div class="tp-grid">
                            <div class="tp-field"><label for="tp-ref">Reference</label><input id="tp-ref" class="tp-input" name="reference" required maxlength="24" placeholder="ICT..."></div>
                            <div class="tp-field"><label for="tp-score">Score (0-100)</label><input id="tp-score" class="tp-input" name="score" required inputmode="decimal"></div>
                            <div class="tp-field"><label for="tp-course">Course</label><input id="tp-course" class="tp-input" name="course" maxlength="160"></div>
                            <div class="tp-field"><label for="tp-cert">Certificate number</label><input id="tp-cert" class="tp-input" name="certificate_id" maxlength="80"></div>
                        </div>
                        <div class="tp-field" style="margin-top: 12px;"><label for="tp-certurl">Certificate link</label><input id="tp-certurl" class="tp-input" name="certificate_url" maxlength="500" placeholder="https://"></div>
                        <div class="tp-row" style="justify-content: flex-end; margin-top: 12px;"><button type="submit" class="tp-btn">Save result</button></div>
                    </form>
                <?php endif; ?>
                <div class="tp-card">
                    <h2>Results</h2>
                    <?php if (!$results): ?><div class="tp-empty">No results yet.</div><?php else: ?>
                        <div class="tp-wrap no-scrollbar"><table class="tp-table">
                            <thead><tr><th>Reference</th><th>Partner</th><th>Candidate</th><th>Score</th><th>Plan unlocked</th><th>Status</th><th>Recorded</th><th></th></tr></thead>
                            <tbody><?php foreach ($results as $r): ?>
                                <tr x-data="{ confirm: false }">
                                    <td><code><?= $e($r['reference']) ?></code><small><?= $e($r['course'] ?: '') ?></small></td>
                                    <td><?= $e($r['partner_name']) ?></td>
                                    <td>#<?= (int)$r['candidate_id'] ?></td>
                                    <td><strong><?= rtrim(rtrim(number_format((float)$r['score'], 2), '0'), '.') ?>%</strong><small><?= $r['passed'] ? 'Passed' : 'Below pass mark' ?></small></td>
                                    <td><?= $r['plan'] ? $e(ucfirst($r['plan'])) . '<small>' . (int)$r['plan_days'] . ' days</small>' : '—' ?></td>
                                    <td><?= $pill($r['status']) ?><small><?= $e($r['source'] === 'manual' ? 'Added by ' . ($r['created_by'] ?: 'admin') : 'From partner API') ?></small></td>
                                    <td><?= $e($when($r['created_at'])) ?></td>
                                    <td style="text-align: right;">
                                        <?php if ($canEdit && $r['status'] === 'active'): ?>
                                            <button type="button" class="tp-btn tp-btn-ghost tp-btn-sm" x-show="!confirm" @click="confirm = true">Revoke</button>
                                            <form x-show="confirm" style="display: none; margin: 0;" method="POST" action="<?= url('/admin/elearning/training-results/' . (int)$r['id'] . '/revoke') ?>" class="tp-row">
                                                <button type="submit" class="tp-btn tp-btn-danger tp-btn-sm">Revoke result and plan</button>
                                                <button type="button" class="tp-btn tp-btn-ghost tp-btn-sm" @click="confirm = false">Cancel</button>
                                            </form>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?></tbody>
                        </table></div>
                    <?php endif; ?>
                </div>

            <?php else: ?>
                <div class="tp-card">
                    <h2>Score distribution</h2>
                    <p class="tp-sub">Active results in 10-point bands (dark bands are at or above the global pass range).</p>
                    <div class="tp-bars" aria-label="Score distribution">
                        <?php foreach ($distribution as $i => $n): ?>
                            <div class="tp-bar <?= $i >= 5 ? 'is-pass' : '' ?>" style="height: <?= max(3, (int)round($n / $maxBand * 100)) ?>%;"><span><?= (int)$n ?></span></div>
                        <?php endforeach; ?>
                    </div>
                    <div class="tp-bar-labels"><?php for ($i = 0; $i < 10; $i++): ?><span><?= $i * 10 ?>-<?= $i === 9 ? 100 : $i * 10 + 9 ?></span><?php endfor; ?></div>
                </div>
                <div class="tp-card">
                    <h2>Partner API log</h2>
                    <p class="tp-sub">Every call to the partner API, including refused ones. IPs are masked.</p>
                    <?php if (!$auditLog): ?><div class="tp-empty">No API calls yet.</div><?php else: ?>
                        <div class="tp-wrap no-scrollbar"><table class="tp-table">
                            <thead><tr><th>Time</th><th>Partner</th><th>Request</th><th>Result</th><th>Reference</th><th>Idempotency key</th><th>IP</th></tr></thead>
                            <tbody><?php foreach ($auditLog as $l): ?>
                                <tr>
                                    <td><?= $e($when($l['created_at'])) ?></td>
                                    <td><?= $e($l['partner_name'] ?: 'Unknown key') ?></td>
                                    <td><code><?= $e($l['method'] . ' ' . str_replace('/api/v1/partners', '', $l['path'])) ?></code></td>
                                    <td><span class="tp-pill" style="background: <?= (int)$l['status'] < 300 ? '#ECFDF5' : '#FEF2F2' ?>; color: <?= (int)$l['status'] < 300 ? '#047857' : '#B91C1C' ?>;"><?= (int)$l['status'] ?></span><small><?= $e($l['error_code'] ?: '') ?><?= $l['duration_ms'] !== null ? ' ' . (int)$l['duration_ms'] . ' ms' : '' ?></small></td>
                                    <td><?= $l['reference'] ? '<code>' . $e($l['reference']) . '</code>' : '—' ?></td>
                                    <td style="max-width: 160px; overflow-wrap: anywhere;"><?= $e($l['idempotency_key'] ?: '—') ?></td>
                                    <td><?= $e($l['ip_masked'] ?: '') ?></td>
                                </tr>
                            <?php endforeach; ?></tbody>
                        </table></div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </main>
    </div>
</div>
