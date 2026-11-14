<?php
declare(strict_types=1);
/**
 * Admin > Communications (docs/54-email-newsletter-contact.md)
 * Tabs: Campaigns, Compose, Subscribers, Contact inbox, Mail log.
 *
 * @var array  $staff
 * @var string $activeNav
 * @var string $tab
 * @var array|null $flash
 * @var array  $mailStats   queued, sent, failed, opened, configured
 * @var array  $subStats    subscribed, pending, unsubscribed, new_7d
 * @var array  $inboxCounts new, open, replied, closed
 */
use App\Services\ContactService;
use App\Services\NewsletterService;

$e = static fn ($v): string => htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');
$when = static fn ($v): string => $v ? date('j M Y, g:i A', strtotime((string)$v)) : '—';
$tabs = [
    'campaigns'   => 'Campaigns',
    'compose'     => 'Compose',
    'subscribers' => 'Subscribers',
    'inbox'       => 'Contact inbox',
    'mail'        => 'Mail log',
];
$tabIcons = [
    'campaigns'   => 'M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z',
    'compose'     => 'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z',
    'subscribers' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z',
    'inbox'       => 'M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4',
    'mail'        => 'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z',
];
$tabCounts = ['subscribers' => $subStats['subscribed'] ?? 0, 'inbox' => $inboxCounts['new'] ?? 0, 'mail' => $mailStats['failed'] ?? 0];
$pill = static function (string $status): string {
    $map = [
        'sent' => ['#ECFDF5', '#047857'], 'subscribed' => ['#ECFDF5', '#047857'], 'replied' => ['#ECFDF5', '#047857'],
        'queued' => ['#EEF2FF', '#4F48E6'], 'sending' => ['#EEF2FF', '#4F48E6'], 'pending' => ['#FFFBEB', '#B45309'], 'new' => ['#EEF2FF', '#4F48E6'], 'open' => ['#FFFBEB', '#B45309'],
        'failed' => ['#FEF2F2', '#B91C1C'], 'draft' => ['#F1F5F9', '#475569'], 'unsubscribed' => ['#F1F5F9', '#64748B'], 'closed' => ['#F1F5F9', '#64748B'], 'cancelled' => ['#F1F5F9', '#64748B'],
    ];
    [$bg, $fg] = $map[$status] ?? ['#F1F5F9', '#475569'];
    return '<span class="cm-pill" style="background: ' . $bg . '; color: ' . $fg . ';">' . htmlspecialchars(ucfirst($status), ENT_QUOTES) . '</span>';
};
$pager = static function (int $total, int $page, array $query): string {
    $pages = (int)ceil($total / 20);
    if ($pages < 2) {
        return '';
    }
    $out = '<div class="cm-pager">';
    if ($page > 1) {
        $out .= '<a class="cm-btn cm-btn-ghost cm-btn-sm" href="' . url('/admin/communications?' . http_build_query($query + ['page' => $page - 1])) . '">Previous</a>';
    }
    $out .= '<span>Page ' . $page . ' of ' . $pages . '</span>';
    if ($page < $pages) {
        $out .= '<a class="cm-btn cm-btn-ghost cm-btn-sm" href="' . url('/admin/communications?' . http_build_query($query + ['page' => $page + 1])) . '">Next</a>';
    }
    return $out . '</div>';
};
$campaign = $campaign ?? null;
$draft = $draft ?? null;
$form = $draft ?: ($campaign ?: []);
$isDraft = !$campaign || $campaign['status'] === 'draft';
?>
<style>
    .cm-card { background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 16px; padding: 20px; margin-bottom: 20px; min-width: 0; }
    .cm-card h2 { font-size: 15px; font-weight: 700; color: #000623; margin: 0 0 4px; }
    .cm-sub { font-size: 12.5px; color: #64748B; margin: 0 0 16px; line-height: 1.5; }
    .cm-kpis { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 12px; margin-bottom: 20px; }
    .cm-kpi { background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 14px; padding: 14px 16px; min-width: 0; }
    .cm-kpi span { display: block; font-size: 11px; font-weight: 700; letter-spacing: .05em; text-transform: uppercase; color: #64748B; }
    .cm-kpi strong { display: block; font-size: 22px; font-weight: 800; color: #000623; margin-top: 4px; }
    .cm-kpi small { font-size: 11.5px; color: #94A3B8; }
    .cm-tabs { display: flex; gap: 4px; padding: 4px; background: #F1F5F9; border-radius: 12px; overflow-x: auto; margin-bottom: 20px; }
    .cm-tab { flex: 0 0 auto; display: inline-flex; align-items: center; gap: 8px; padding: 9px 15px; border-radius: 10px; font-size: 13.5px; font-weight: 600; color: #64748B; text-decoration: none; white-space: nowrap; transition: background .15s ease, color .15s ease; }
    .cm-tab:hover { color: #000623; background: rgba(255, 255, 255, .6); }
    .cm-tab svg { flex-shrink: 0; color: #94A3B8; }
    .cm-tab.is-active { background: #FFFFFF; color: #4F48E6; box-shadow: 0 1px 3px rgba(15, 23, 42, .1); }
    .cm-tab.is-active svg { color: #4F48E6; }
    .cm-tab em { font-style: normal; font-size: 11px; font-weight: 800; color: #4F48E6; background: #EEF0FF; padding: 1px 7px; border-radius: 999px; }
    .cm-tab em.is-alert { background: #4F48E6; color: #FFFFFF; }
    .cm-queue-empty { display: inline-flex; align-items: center; gap: 7px; height: 38px; padding: 0 14px; border-radius: 10px; background: #F1F5F9; color: #475569; font-size: 13px; font-weight: 600; white-space: nowrap; }
    .cm-queue-empty svg { color: #059669; }
    .cm-table-wrap { width: 100%; overflow-x: auto; }
    .cm-table { width: 100%; min-width: 680px; border-collapse: collapse; font-size: 13px; }
    .cm-table th { text-align: left; font-size: 11px; font-weight: 700; letter-spacing: .04em; text-transform: uppercase; color: #64748B; padding: 8px 10px; border-bottom: 1px solid #E2E8F0; white-space: nowrap; }
    .cm-table td { padding: 11px 10px; border-bottom: 1px solid #F1F5F9; vertical-align: top; color: #0F172A; }
    .cm-table td small { display: block; font-size: 11.5px; color: #94A3B8; margin-top: 2px; }
    .cm-pill { display: inline-flex; align-items: center; padding: 2px 9px; border-radius: 999px; font-size: 11px; font-weight: 700; white-space: nowrap; }
    .cm-input, .cm-textarea { width: 100%; border: 1px solid #CBD5E1; border-radius: 10px; padding: 0 12px; font-size: 13.5px; color: #0F172A; background: #FFFFFF; box-sizing: border-box; font-family: inherit; }
    .cm-input { height: 40px; }
    .cm-textarea { padding: 10px 12px; min-height: 120px; resize: vertical; line-height: 1.55; }
    .cm-input:focus, .cm-textarea:focus { outline: none; border-color: #4F48E6; box-shadow: 0 0 0 3px rgba(79, 72, 230, .15); }
    .cm-field { margin-bottom: 14px; min-width: 0; }
    .cm-field label { display: block; font-size: 12px; font-weight: 600; color: #334155; margin-bottom: 6px; }
    .cm-field .cm-hint { font-size: 11.5px; color: #94A3B8; margin-top: 4px; }
    .cm-grid2 { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 14px; }
    .cm-btn { display: inline-flex; align-items: center; justify-content: center; height: 38px; padding: 0 16px; border-radius: 10px; border: 0; background: #4F48E6; color: #FFFFFF; font-size: 13px; font-weight: 700; cursor: pointer; text-decoration: none; white-space: nowrap; font-family: inherit; }
    .cm-btn:hover { background: #4338CA; }
    .cm-btn-dark { background: #000623; }
    .cm-btn-dark:hover { background: #1E293B; }
    .cm-btn-ghost { background: #FFFFFF; color: #0F172A; border: 1px solid #CBD5E1; }
    .cm-btn-ghost:hover { background: #F8FAFC; }
    .cm-btn-sm { height: 30px; padding: 0 12px; font-size: 12px; }
    .cm-row { display: flex; gap: 8px; flex-wrap: wrap; align-items: center; }
    .cm-between { display: flex; gap: 12px; flex-wrap: wrap; align-items: flex-start; justify-content: space-between; }
    .cm-flash { border-radius: 12px; padding: 12px 14px; font-size: 13px; font-weight: 600; margin-bottom: 16px; }
    .cm-flash.ok { background: #ECFDF5; color: #047857; }
    .cm-flash.err { background: #FEF2F2; color: #B91C1C; }
    .cm-warn { background: #FFFBEB; color: #92400E; border-radius: 12px; padding: 12px 14px; font-size: 13px; margin-bottom: 16px; line-height: 1.5; }
    .cm-empty { font-size: 13px; color: #64748B; padding: 24px 0; text-align: center; }
    .cm-pager { display: flex; gap: 10px; align-items: center; justify-content: flex-end; margin-top: 14px; font-size: 12.5px; color: #64748B; }
    .cm-compose { display: grid; grid-template-columns: minmax(0, 1fr) minmax(0, 1fr); gap: 20px; align-items: start; }
    .cm-preview { width: 100%; height: 720px; border: 1px solid #E2E8F0; border-radius: 12px; background: #F1F3F9; }
    .cm-msg { white-space: pre-wrap; overflow-wrap: anywhere; font-size: 14px; line-height: 1.6; color: #1E293B; background: #F8FAFC; border-radius: 12px; padding: 14px; }
    .cm-inbox { display: grid; grid-template-columns: minmax(0, 1fr) minmax(0, 1.1fr); gap: 20px; align-items: start; }
    .cm-link-row { color: inherit; text-decoration: none; display: block; }
    .cm-link-row:hover strong { color: #4F48E6; }
    @media (max-width: 1100px) { .cm-compose, .cm-inbox { grid-template-columns: minmax(0, 1fr); } .cm-preview { height: 560px; } }
    @media (max-width: 760px) { .cm-kpis { grid-template-columns: repeat(2, minmax(0, 1fr)); } .cm-grid2 { grid-template-columns: minmax(0, 1fr); } .cm-card { padding: 16px; } }
</style>

<div class="admin-shell">
    <?php include dirname(__DIR__, 2) . '/shared/components/admin-sidebar.php'; ?>

    <div class="admin-main-wrap">
        <?php include dirname(__DIR__, 2) . '/shared/components/admin-topnav.php'; ?>

        <main class="admin-content-body">
            <div class="admin-page-header mb-6 cm-between">
                <div style="min-width: 0;">
                    <h1 class="admin-page-title">Communications</h1>
                    <p class="admin-page-subtitle">Newsletter campaigns, subscribers, contact messages and every e-mail the platform sends.</p>
                </div>
                <form method="POST" action="<?= url('/admin/communications/queue') ?>" style="margin: 0;">
                    <input type="hidden" name="tab" value="<?= $e($tab) ?>">
                    <?php if ((int)$mailStats['queued'] > 0): ?>
                        <button type="submit" class="cm-btn"><svg width="15" height="15" style="margin-right: 6px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>Send next batch (<?= (int)$mailStats['queued'] ?> waiting)</button>
                    <?php else: ?>
                        <span class="cm-queue-empty"><svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.4"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>Queue empty: everything is sent</span>
                    <?php endif; ?>
                </form>
            </div>

            <?php if (!$mailStats['configured']): ?>
                <div class="cm-warn"><strong>Mail is not connected yet.</strong> Add the mailbox password as <code>MAIL_PASSWORD</code> in <code>.env</code>. Until then e-mails wait in the queue and go out once it is set.</div>
            <?php endif; ?>
            <?php if ($flash): ?>
                <div class="cm-flash <?= $flash['ok'] ? 'ok' : 'err' ?>" role="status"><?= $e($flash['text']) ?></div>
            <?php endif; ?>

            <section class="cm-kpis" aria-label="Communication totals">
                <div class="cm-kpi"><span>Subscribers</span><strong><?= number_format($subStats['subscribed']) ?></strong><small>+<?= (int)$subStats['new_7d'] ?> this week, <?= (int)$subStats['pending'] ?> unconfirmed</small></div>
                <div class="cm-kpi"><span>E-mails sent</span><strong><?= number_format($mailStats['sent']) ?></strong><small><?= (int)$mailStats['queued'] ?> queued, <?= (int)$mailStats['failed'] ?> failed</small></div>
                <div class="cm-kpi"><span>Opened</span><strong><?= $mailStats['sent'] ? round($mailStats['opened'] / max(1, $mailStats['sent']) * 100) : 0 ?>%</strong><small><?= number_format($mailStats['opened']) ?> opens (images on)</small></div>
                <div class="cm-kpi"><span>Contact inbox</span><strong><?= (int)$inboxCounts['new'] ?></strong><small>new, <?= (int)$inboxCounts['open'] ?> open</small></div>
            </section>

            <nav class="cm-tabs no-scrollbar" aria-label="Communications sections">
                <?php foreach ($tabs as $key => $label): ?>
                    <a href="<?= url('/admin/communications?tab=' . $key) ?>" class="cm-tab <?= $tab === $key ? 'is-active' : '' ?>" <?= $tab === $key ? 'aria-current="page"' : '' ?>><svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="<?= $tabIcons[$key] ?>"/></svg><span><?= $e($label) ?></span><?php $cnt = $tabCounts[$key] ?? null; if ($cnt): ?><em class="<?= $key === 'inbox' ? 'is-alert' : '' ?>"><?= (int)$cnt ?></em><?php endif; ?></a>
                <?php endforeach; ?>
            </nav>

            <?php if ($tab === 'campaigns'): ?>
                <div class="cm-card">
                    <div class="cm-between" style="margin-bottom: 14px;">
                        <div><h2>Newsletter campaigns</h2><p class="cm-sub" style="margin: 0;">Sent only to confirmed subscribers. Each e-mail carries its own unsubscribe link.</p></div>
                        <a href="<?= url('/admin/communications?tab=compose') ?>" class="cm-btn">New campaign</a>
                    </div>
                    <?php if (empty($campaigns)): ?>
                        <div class="cm-empty">No campaigns yet. Write the first one in Compose.</div>
                    <?php else: ?>
                        <div class="cm-table-wrap no-scrollbar">
                            <table class="cm-table">
                                <thead><tr><th>Campaign</th><th>Status</th><th>Recipients</th><th>Delivered</th><th>Opened</th><th>Failed</th><th></th></tr></thead>
                                <tbody>
                                <?php foreach ($campaigns as $c): ?>
                                    <tr>
                                        <td><strong><?= $e($c['subject']) ?></strong><small><?= $e($when($c['queued_at'] ?: $c['created_at'])) ?> by <?= $e($c['created_by'] ?: 'admin') ?></small></td>
                                        <td><?= $pill($c['status']) ?></td>
                                        <td><?= (int)$c['recipients'] ?></td>
                                        <td><?= (int)$c['sent'] ?><?php if ($c['queued']): ?><small><?= (int)$c['queued'] ?> waiting</small><?php endif; ?></td>
                                        <td><?= (int)$c['opened'] ?><small><?= $c['sent'] ? round($c['opened'] / $c['sent'] * 100) . '%' : '' ?></small></td>
                                        <td><?= (int)$c['failed'] ?></td>
                                        <td style="text-align: right;"><a class="cm-btn cm-btn-ghost cm-btn-sm" href="<?= url('/admin/communications?tab=compose&id=' . (int)$c['id']) ?>"><?= $c['status'] === 'draft' ? 'Edit' : 'View' ?></a></td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>

            <?php elseif ($tab === 'compose'):
                $types = \App\Services\NewsletterService::TYPES;
                $extraSaved = $form['extra'] ?? null;
                $extraSaved = is_array($extraSaved) ? $extraSaved : (json_decode((string)$extraSaved, true) ?: []);
                $composerCfg = [
                    'type'      => (string)($form['type'] ?? 'general'),
                    'audience'  => (string)($form['audience'] ?? 'all'),
                    'subject'   => (string)($form['subject'] ?? ''),
                    'preheader' => (string)($form['preheader'] ?? ''),
                    'title'     => (string)($form['title'] ?? ''),
                    'hero'      => (string)($form['hero'] ?? ($campaign ? '' : 'email_updates')),
                    'footer'    => (string)($extraSaved['footer'] ?? $form['footer'] ?? 'circles'),
                    'button'    => (string)($extraSaved['button'] ?? $form['button'] ?? 'auto'),
                    'footers'   => $footerLibrary ?? [],
                    'buttons'   => array_map(static fn ($k, $b) => ['key' => $k, 'label' => $b[0], 'hex' => $b[1]], array_keys(\App\Services\NewsletterService::BUTTONS), \App\Services\NewsletterService::BUTTONS),
                    'heroUrl'   => (string)($form['hero_url'] ?? ''),
                    'bodyImage' => (string)($form['body_image_url'] ?? ''),
                    'body'      => (string)($form['body'] ?? ''),
                    'jennyTip'  => (string)($form['jenny_tip'] ?? ''),
                    'ctaLabel'  => (string)($form['cta_label'] ?? 'Find internships'),
                    'ctaUrl'    => (string)($form['cta_url'] ?? '/internships'),
                    'eventDate' => (string)($extraSaved['event_date'] ?? $form['event_date'] ?? ''),
                    'eventTime' => (string)($extraSaved['event_time'] ?? $form['event_time'] ?? ''),
                    'eventTz'   => (string)($extraSaved['event_tz'] ?? $form['event_tz'] ?? 'WAT'),
                    'eventPlace'=> (string)($extraSaved['event_place'] ?? $form['event_place'] ?? ''),
                    'listings'  => array_values((array)($extraSaved['listings'] ?? $form['listings'] ?? [])),
                    'defaults'  => array_map(static fn ($t) => $t[2], $types),
                    'library'   => $library ?? [],
                    'counts'    => $audienceCounts ?? [],
                    'locked'    => !$isDraft,
                    'urls'      => ['upload' => url('/admin/communications/images'), 'jenny' => url('/admin/communications/jenny-draft'), 'preview' => url('/admin/communications/preview-draft')],
                ];
            ?>
                <style>
                    .cx-types { display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 10px; margin-bottom: 18px; }
                    .cx-type { text-align: left; border: 1.5px solid #E2E8F0; background: #FFFFFF; border-radius: 14px; padding: 10px; cursor: pointer; font-family: inherit; transition: border-color .15s ease, box-shadow .15s ease; }
                    .cx-type:hover { border-color: #C7D2FE; }
                    .cx-type.is-on { border-color: #4F48E6; box-shadow: 0 0 0 3px rgba(79, 72, 230, .14); }
                    .cx-type svg { display: block; width: 100%; height: auto; border-radius: 8px; background: #F8FAFC; }
                    .cx-type strong { display: block; font-size: 13px; color: #000623; margin-top: 8px; }
                    .cx-type span { display: block; font-size: 11.5px; color: #64748B; margin-top: 2px; line-height: 1.4; }
                    .cx-jenny { border-radius: 16px; padding: 16px; background: linear-gradient(135deg, #F5F3FF, #EEF2FF); border: 1px solid #E0E7FF; margin-bottom: 18px; }
                    .cx-jenny-head { display: flex; align-items: center; gap: 10px; }
                    .cx-jenny-head img { width: 34px; height: 34px; border-radius: 50%; background: #FFFFFF; }
                    .cx-tips { margin: 10px 0 0; padding: 10px 12px 10px 28px; background: #FFFFFF; border-radius: 12px; font-size: 12.5px; color: #334155; line-height: 1.6; }
                    .cx-foot { display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 8px; }
                    .cx-foot button { border: 2px solid transparent; border-radius: 10px; padding: 0; overflow: hidden; cursor: pointer; background: #000623; text-align: left; font-family: inherit; }
                    .cx-foot button.is-on { border-color: #4F48E6; box-shadow: 0 0 0 3px rgba(79, 72, 230, .18); }
                    .cx-foot img { display: block; width: 100%; height: 34px; object-fit: cover; }
                    .cx-foot span { display: block; padding: 6px 8px; font-size: 11.5px; font-weight: 700; color: #E2E8F0; }
                    .cx-swatches { display: flex; flex-wrap: wrap; gap: 8px; }
                    .cx-swatch { display: inline-flex; align-items: center; gap: 8px; height: 36px; padding: 0 12px 0 6px; border: 1.5px solid #E2E8F0; border-radius: 999px; background: #FFFFFF; cursor: pointer; font-family: inherit; font-size: 12.5px; font-weight: 700; color: #0F172A; }
                    .cx-swatch.is-on { border-color: #4F48E6; box-shadow: 0 0 0 3px rgba(79, 72, 230, .14); }
                    .cx-swatch i { width: 24px; height: 24px; border-radius: 50%; display: block; flex-shrink: 0; }
                    .cx-lib { display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 8px; }
                    .cx-lib button { position: relative; border: 2px solid transparent; border-radius: 10px; padding: 0; overflow: hidden; cursor: pointer; background: #F1F5F9; aspect-ratio: 3 / 1; }
                    .cx-lib button img { width: 100%; height: 100%; object-fit: cover; display: block; }
                    .cx-lib button.is-on { border-color: #4F48E6; }
                    .cx-lib .cx-none { font-size: 11.5px; font-weight: 700; color: #64748B; font-family: inherit; }
                    .cx-imgbox { display: flex; align-items: center; gap: 12px; flex-wrap: wrap; margin-top: 10px; }
                    .cx-imgbox img { width: 140px; height: 80px; object-fit: cover; border-radius: 10px; border: 1px solid #E2E8F0; }
                    label.cx-upload, .cm-field label.cx-upload { margin: 0; display: inline-flex; justify-content: center; line-height: 1; white-space: nowrap; }
                    .cx-upload { display: inline-flex; align-items: center; gap: 6px; height: 36px; padding: 0 14px; border-radius: 10px; border: 1px dashed #A5B4FC; background: #F5F6FF; color: #4F48E6; font-size: 12.5px; font-weight: 700; cursor: pointer; }
                    .cx-section { font-size: 12px; font-weight: 800; letter-spacing: .05em; text-transform: uppercase; color: #94A3B8; margin: 20px 0 10px; }
                    .cx-picks { max-height: 220px; overflow-y: auto; border: 1px solid #E2E8F0; border-radius: 12px; }
                    .cx-picks label { display: flex; align-items: center; gap: 10px; padding: 9px 12px; border-bottom: 1px solid #F1F5F9; font-size: 13px; cursor: pointer; }
                    .cx-picks label:last-child { border-bottom: 0; }
                    .cx-picks small { color: #94A3B8; display: block; }
                    .cx-spin { width: 14px; height: 14px; border: 2px solid rgba(255,255,255,.45); border-top-color: #FFFFFF; border-radius: 50%; display: inline-block; animation: cxSpin .8s linear infinite; }
                    @keyframes cxSpin { to { transform: rotate(360deg); } }
                </style>
                <script type="application/json" id="cx-data"><?= json_encode($composerCfg, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?></script>
                <script>
                function campaignComposer() {
                    const cfg = JSON.parse(document.getElementById('cx-data').textContent);
                    return Object.assign({}, cfg, {
                        brief: '', tone: 'friendly', drafting: false, tips: [], jennyMsg: '', uploading: '', previewHtml: '', previewing: false,
                        init() { if (!this.locked) this.$nextTick(() => this.preview()); },
                        setType(t) {
                            if (this.locked) return;
                            this.type = t;
                            this.audience = this.defaults[t] || 'all';
                            if (t === 'employers' && (!this.ctaUrl || this.ctaUrl === '/internships')) { this.ctaUrl = '/employer/post-internship'; this.ctaLabel = 'Post an internship'; }
                            if (t === 'event' && this.ctaLabel === 'Find internships') { this.ctaLabel = 'Register now'; }
                            this.preview();
                        },
                        toast(text, type) { window.dispatchEvent(new CustomEvent('show-toast', { detail: { text: text, message: text, type: type || 'info' } })); if (typeof window.adminToast === 'function') window.adminToast(text, '', null, type || 'info'); },
                        async upload(ev, target) {
                            const f = ev.target.files && ev.target.files[0];
                            ev.target.value = '';
                            if (!f) return;
                            this.uploading = target;
                            const fd = new FormData();
                            fd.append('image', f);
                            try {
                                const r = await fetch(this.urls.upload, { method: 'POST', headers: { 'Accept': 'application/json' }, body: fd });
                                const d = await r.json();
                                if (!d.success) throw new Error(d.error || 'Upload failed');
                                if (target === 'hero') { this.heroUrl = d.url; this.hero = ''; } else { this.bodyImage = d.url; }
                                this.preview();
                            } catch (e) { this.toast(e.message || 'The image could not be uploaded.', 'error'); }
                            this.uploading = '';
                        },
                        async draftWithJenny() {
                            if (this.drafting) return;
                            this.drafting = true; this.jennyMsg = '';
                            try {
                                const r = await fetch(this.urls.jenny, { method: 'POST', headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' }, body: JSON.stringify({ type: this.type, audience: this.audience, brief: this.brief, tone: this.tone }) });
                                const d = await r.json();
                                if (!d.success) throw new Error(d.error || 'Jenny could not write this right now.');
                                const x = d.draft;
                                this.subject = x.subject; this.preheader = x.preheader; this.title = x.title; this.body = x.body;
                                if (x.cta_label) this.ctaLabel = x.cta_label;
                                if (x.cta_url) this.ctaUrl = x.cta_url;
                                this.jennyTip = x.jenny_tip || this.jennyTip;
                                if (x.listings && x.listings.length) this.listings = x.listings;
                                this.tips = d.tips || [];
                                this.jennyMsg = d.source === 'gemini' ? 'Draft ready. Read it, adjust anything, then save.' : 'Jenny is busy, so this is a starter draft to edit.';
                                this.$nextTick(() => this.preview());
                            } catch (e) { this.jennyMsg = e.message; }
                            this.drafting = false;
                        },
                        async preview() {
                            const form = this.$refs.form;
                            if (!form) return;
                            this.previewing = true;
                            try {
                                const r = await fetch(this.urls.preview, { method: 'POST', body: new FormData(form) });
                                this.previewHtml = (await r.text()).replace('</head>', '<style>html, body { overflow-x: hidden !important; } table { max-width: 100% !important; } img { max-width: 100% !important; height: auto !important; }</style></head>');
                            } catch (e) {}
                            this.previewing = false;
                        },
                        heroThumb() {
                            if (this.heroUrl) return this.heroUrl;
                            const l = this.library.find(x => x.key === this.hero);
                            return l ? l.thumb : '';
                        },
                        toggleListing(id) {
                            const i = this.listings.indexOf(id);
                            if (i >= 0) this.listings.splice(i, 1); else if (this.listings.length < 4) this.listings.push(id);
                        }
                    });
                }
                </script>
                <div class="cm-compose" x-data="campaignComposer()">
                    <form x-ref="form" method="POST" action="<?= url('/admin/communications/campaigns' . ($campaign ? '/' . (int)$campaign['id'] : '')) ?>" class="cm-card" @input.debounce.900ms="preview()">
                        <div class="cm-between" style="margin-bottom: 4px;">
                            <h2><?= $campaign ? ($isDraft ? 'Edit draft' : 'Campaign (sent)') : 'New campaign' ?></h2>
                        </div>
                        <p class="cm-sub">Pick a layout, let Jenny draft it if you like, then adjust. The InternConnect header, footer and unsubscribe link are added for you.</p>
                        <fieldset style="border: 0; padding: 0; margin: 0; min-width: 0;" <?= $isDraft ? '' : 'disabled' ?>>
                            <input type="hidden" name="type" :value="type">
                            <input type="hidden" name="hero" :value="hero">
                            <input type="hidden" name="hero_url" :value="heroUrl">
                            <input type="hidden" name="footer" :value="footer">
                            <input type="hidden" name="button" :value="button">
                            <input type="hidden" name="body_image_url" :value="bodyImage">

                            <div class="cx-section" style="margin-top: 4px;">1. Layout</div>
                            <div class="cx-types" role="radiogroup" aria-label="Campaign layout">
                                <?php
                                $wire = [
                                    'general'   => '<rect x="8" y="6" width="104" height="22" rx="4" fill="#C7D2FE"/><rect x="8" y="34" width="70" height="6" rx="3" fill="#000623"/><rect x="8" y="45" width="104" height="4" rx="2" fill="#CBD5E1"/><rect x="8" y="53" width="90" height="4" rx="2" fill="#CBD5E1"/><rect x="8" y="66" width="40" height="11" rx="5" fill="#4F48E6"/>',
                                    'marketing' => '<rect x="0" y="0" width="120" height="30" fill="#A5B4FC"/><rect x="8" y="35" width="80" height="7" rx="3" fill="#000623"/><rect x="8" y="47" width="50" height="14" rx="4" fill="#EDE9FE"/><rect x="62" y="47" width="50" height="14" rx="4" fill="#EDE9FE"/><rect x="8" y="66" width="104" height="14" rx="5" fill="#4F48E6"/><rect x="44" y="70" width="32" height="6" rx="3" fill="#FFFFFF"/>',
                                    'event'     => '<rect x="8" y="6" width="30" height="7" rx="3" fill="#E0E7FF"/><rect x="8" y="17" width="70" height="7" rx="3" fill="#000623"/><rect x="8" y="29" width="104" height="24" rx="6" fill="#000623"/><rect x="13" y="33" width="16" height="16" rx="3" fill="#FFFFFF"/><rect x="13" y="33" width="16" height="5" rx="2" fill="#4F48E6"/><rect x="35" y="36" width="50" height="4" rx="2" fill="#C7D2FE"/><rect x="35" y="43" width="34" height="4" rx="2" fill="#818CF8"/><rect x="8" y="58" width="104" height="4" rx="2" fill="#CBD5E1"/><rect x="8" y="68" width="40" height="10" rx="5" fill="#4F48E6"/>',
                                    'interns'   => '<rect x="8" y="6" width="30" height="7" rx="3" fill="#E0E7FF"/><rect x="8" y="17" width="66" height="7" rx="3" fill="#000623"/><rect x="8" y="29" width="104" height="13" rx="4" fill="#FFFFFF" stroke="#CBD5E1"/><rect x="8" y="45" width="104" height="13" rx="4" fill="#FFFFFF" stroke="#CBD5E1"/><rect x="12" y="33" width="44" height="4" rx="2" fill="#000623"/><rect x="12" y="49" width="38" height="4" rx="2" fill="#000623"/><rect x="8" y="62" width="104" height="16" rx="5" fill="#EEF0FF"/><circle cx="17" cy="70" r="5" fill="#818CF8"/>',
                                    'employers' => '<rect x="8" y="6" width="38" height="8" rx="4" fill="#000623"/><rect x="8" y="19" width="76" height="7" rx="3" fill="#000623"/><circle cx="12" cy="35" r="3.5" fill="#000623"/><rect x="19" y="33" width="70" height="4" rx="2" fill="#CBD5E1"/><circle cx="12" cy="45" r="3.5" fill="#000623"/><rect x="19" y="43" width="60" height="4" rx="2" fill="#CBD5E1"/><circle cx="12" cy="55" r="3.5" fill="#000623"/><rect x="19" y="53" width="66" height="4" rx="2" fill="#CBD5E1"/><rect x="8" y="65" width="48" height="12" rx="5" fill="#000623"/>',
                                ];
                                foreach ($types as $key => [$label, $desc]): ?>
                                    <button type="button" role="radio" class="cx-type" :class="{ 'is-on': type === '<?= $key ?>' }" :aria-checked="type === '<?= $key ?>'" @click="setType('<?= $key ?>')">
                                        <svg viewBox="0 0 120 84" width="120" height="84" aria-hidden="true"><?= $wire[$key] ?></svg>
                                        <strong><?= $e($label) ?></strong>
                                        <span><?= $e($desc) ?></span>
                                    </button>
                                <?php endforeach; ?>
                            </div>

                            <div class="cx-jenny" x-show="!locked">
                                <div class="cx-jenny-head">
                                    <img src="<?= asset('assets/images/jenny_ai/jenny_icon.png') ?>" alt="" width="34" height="34">
                                    <div style="min-width: 0;"><div style="font-size: 14px; font-weight: 800; color: #000623;">Write with Jenny</div><div style="font-size: 12px; color: #64748B;">Say what the campaign is about. Jenny drafts the subject, heading, message and button for the layout you picked.</div></div>
                                </div>
                                <textarea class="cm-textarea" x-model="brief" style="min-height: 70px; margin-top: 10px; background: #FFFFFF;" maxlength="1500" placeholder="e.g. Invite students to a free CV clinic on 28 November, online, with our recruiters" aria-label="What is the campaign about?"></textarea>
                                <div class="cm-row" style="margin-top: 8px;">
                                    <div style="flex: 0 0 170px;"><select data-ic-select class="cm-input" style="background: #FFFFFF;" x-model="tone" aria-label="Tone">
                                        <option value="friendly">Friendly</option>
                                        <option value="professional">Professional</option>
                                        <option value="exciting">Exciting</option>
                                        <option value="urgent">Urgent</option>
                                    </select></div>
                                    <button type="button" class="cm-btn" @click="draftWithJenny()" :disabled="drafting || brief.trim().length < 5" :style="(drafting || brief.trim().length < 5) ? 'opacity: .6;' : ''">
                                        <span class="cx-spin" x-show="drafting" x-cloak style="margin-right: 6px;"></span>
                                        <span x-text="drafting ? 'Jenny is writing...' : 'Draft with Jenny'"></span>
                                    </button>
                                </div>
                                <p x-show="jennyMsg" x-text="jennyMsg" style="font-size: 12.5px; color: #3730A3; margin: 8px 0 0;" x-cloak></p>
                                <ul class="cx-tips" x-show="tips.length" x-cloak>
                                    <template x-for="t in tips" :key="t"><li x-text="t"></li></template>
                                </ul>
                            </div>

                            <div class="cx-section">2. Who receives it</div>
                            <select data-ic-select class="cm-input" name="audience" x-model="audience" aria-label="Audience">
                                <?php foreach (\App\Services\NewsletterService::AUDIENCES as $k => $label): ?>
                                    <option value="<?= $k ?>"><?= $e($label) ?> (<?= (int)($audienceCounts[$k] ?? 0) ?> confirmed)</option>
                                <?php endforeach; ?>
                            </select>

                            <div class="cx-section">3. Content</div>
                            <div class="cm-field"><label for="cm-subject">Subject line</label><input id="cm-subject" class="cm-input" name="subject" x-model="subject" maxlength="200" required placeholder="5 new paid internships this week"></div>
                            <div class="cm-field"><label for="cm-pre">Preview text</label><input id="cm-pre" class="cm-input" name="preheader" x-model="preheader" maxlength="200" placeholder="Shown next to the subject in the inbox"></div>
                            <div class="cm-field"><label for="cm-title">Heading</label><input id="cm-title" class="cm-input" name="title" x-model="title" maxlength="200" required placeholder="This week on InternConnect"></div>

                            <div x-show="type === 'event'" x-cloak class="cm-grid2">
                                <div class="cm-field"><label for="cm-edate">Event date</label><input id="cm-edate" class="cm-input" type="date" name="event_date" x-model="eventDate"></div>
                                <div class="cm-field"><label for="cm-etime">Start time</label><input id="cm-etime" class="cm-input" type="time" name="event_time" x-model="eventTime"></div>
                                <div class="cm-field"><label for="cm-etz">Time zone</label><input id="cm-etz" class="cm-input" name="event_tz" x-model="eventTz" maxlength="20" placeholder="WAT, GMT, EST"></div>
                                <div class="cm-field"><label for="cm-eplace">Place or online link</label><input id="cm-eplace" class="cm-input" name="event_place" x-model="eventPlace" maxlength="160" placeholder="Online (link sent after registering)"></div>
                            </div>

                            <div class="cm-field">
                                <label>Header image</label>
                                <div class="cx-lib">
                                    <button type="button" :class="{ 'is-on': !hero && !heroUrl }" @click="hero = ''; heroUrl = ''; preview()" aria-label="No header image"><span class="cx-none">No image</span></button>
                                    <template x-for="img in library" :key="img.key">
                                        <button type="button" :class="{ 'is-on': hero === img.key && !heroUrl }" @click="hero = img.key; heroUrl = ''; preview()" :aria-label="img.label"><img :src="img.thumb" alt="" loading="lazy"></button>
                                    </template>
                                </div>
                                <div class="cx-imgbox">
                                    <img x-show="heroThumb()" :src="heroThumb()" alt="Selected header image" x-cloak>
                                    <label class="cx-upload">
                                        <input type="file" accept="image/png,image/jpeg,image/webp,image/gif" style="display: none;" @change="upload($event, 'hero')">
                                        <span x-text="uploading === 'hero' ? 'Uploading...' : 'Upload your own'"></span>
                                    </label>
                                    <span class="cm-hint">PNG, JPG, WebP or GIF up to 5 MB. Best at 1200 x 480.</span>
                                </div>
                            </div>

                            <div class="cm-field">
                                <label>Footer design</label>
                                <div class="cx-foot">
                                    <template x-for="f in footers" :key="f.key">
                                        <button type="button" :class="{ 'is-on': footer === f.key }" @click="footer = f.key; preview()" :aria-label="'Footer: ' + f.label" :aria-pressed="footer === f.key"><img :src="f.thumb" alt="" width="300" height="24" loading="lazy"><span x-text="f.label"></span></button>
                                    </template>
                                </div>
                            </div>

                            <div class="cm-field"><label for="cm-body">Message</label><textarea id="cm-body" class="cm-textarea" name="body" x-model="body" required style="min-height: 220px;" placeholder="Hi there,&#10;&#10;## New this week&#10;- Product Design Intern at Airbnb&#10;- Data Analyst Intern at Stellar Systems"></textarea><div class="cm-hint">Blank line between paragraphs. "## " starts a sub-heading, "- " a bullet (tiles in Marketing, ticks for Employers).</div></div>

                            <div class="cm-field">
                                <label>Image inside the message (optional, shown after the first paragraph)</label>
                                <div class="cx-imgbox" style="margin-top: 0;">
                                    <img x-show="bodyImage" :src="bodyImage" alt="Image inside the message" x-cloak>
                                    <label class="cx-upload">
                                        <input type="file" accept="image/png,image/jpeg,image/webp,image/gif" style="display: none;" @change="upload($event, 'body')">
                                        <span x-text="uploading === 'body' ? 'Uploading...' : (bodyImage ? 'Replace image' : 'Upload image')"></span>
                                    </label>
                                    <button type="button" class="cm-btn cm-btn-ghost cm-btn-sm" x-show="bodyImage" x-cloak @click="bodyImage = ''; preview()">Remove</button>
                                </div>
                            </div>

                            <div class="cm-field" x-show="type === 'interns'" x-cloak>
                                <label>Internships to feature (up to 4)</label>
                                <div class="cx-picks">
                                    <?php foreach (($listingOptions ?? []) as $l): ?>
                                        <label><input type="checkbox" name="listings[]" value="<?= $e($l['id']) ?>" :checked="listings.includes('<?= $e($l['id']) ?>')" @change="toggleListing('<?= $e($l['id']) ?>'); preview()" style="width: 16px; height: 16px;"><span><?= $e($l['title']) ?><small><?= $e($l['company'] . ' · ' . $l['location']) ?></small></span></label>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <div class="cm-field"><label for="cm-jenny">Tip from Jenny (optional)</label><textarea id="cm-jenny" class="cm-textarea" name="jenny_tip" x-model="jennyTip" maxlength="600" style="min-height: 70px;" placeholder="Tailor the first line of your cover letter to the company."></textarea></div>
                            <div class="cm-grid2">
                                <div class="cm-field"><label for="cm-cta">Button text</label><input id="cm-cta" class="cm-input" name="cta_label" x-model="ctaLabel" maxlength="60"></div>
                                <div class="cm-field"><label for="cm-url">Button link</label><input id="cm-url" class="cm-input" name="cta_url" x-model="ctaUrl" maxlength="500"><div class="cm-hint">A page such as /internships, or a full https:// link.</div></div>
                            </div>
                            <div class="cm-field">
                                <label>Button colour</label>
                                <div class="cx-swatches" role="radiogroup" aria-label="Button colour">
                                    <template x-for="b in buttons" :key="b.key">
                                        <button type="button" class="cx-swatch" :class="{ 'is-on': button === b.key }" role="radio" :aria-checked="button === b.key" @click="button = b.key; preview()">
                                            <i :style="b.hex ? 'background:' + b.hex : 'background: conic-gradient(#4F48E6 0 50%, #000623 0 100%)'"></i><span x-text="b.label"></span>
                                        </button>
                                    </template>
                                </div>
                                <div class="cm-hint">Button text stays white on every colour, so it reads well in light and dark mode. "Layout default" uses the colour that suits the layout.</div>
                            </div>
                        </fieldset>
                        <?php if ($isDraft): ?>
                            <div class="cm-row" style="justify-content: flex-end;"><button type="submit" class="cm-btn"><?= $campaign ? 'Save changes' : 'Save draft' ?></button></div>
                        <?php endif; ?>
                    </form>

                    <div style="min-width: 0;">
                        <div class="cm-card">
                            <div class="cm-between" style="margin-bottom: 12px;">
                                <div><h2>Live preview</h2><p class="cm-sub" style="margin: 0;">Updates as you type. This is what subscribers receive.</p></div>
                                <?= $campaign ? $pill($campaign['status']) : '<span class="cm-pill" style="background:#F1F5F9;color:#475569;">Not saved</span>' ?>
                            </div>
                            <iframe class="cm-preview" sandbox="" :srcdoc="previewHtml" title="E-mail preview"></iframe>
                        </div>
                        <?php if ($campaign && $isDraft): ?>
                            <form method="POST" action="<?= url('/admin/communications/campaigns/' . (int)$campaign['id'] . '/test') ?>" class="cm-card">
                                <h2>Send a test</h2>
                                <p class="cm-sub">Save first, then check how it looks in a real inbox.</p>
                                <div class="cm-row"><input class="cm-input" style="flex: 1 1 200px;" type="email" name="test_email" required value="<?= $e($staff['email'] ?? '') ?>" aria-label="Test e-mail address"><button type="submit" class="cm-btn cm-btn-ghost">Send test</button></div>
                            </form>
                            <form method="POST" action="<?= url('/admin/communications/campaigns/' . (int)$campaign['id'] . '/send') ?>" class="cm-card" x-data="{ sure: false }">
                                <h2>Send to subscribers</h2>
                                <p class="cm-sub">Goes to <strong><?= number_format((int)($audienceCounts[$campaign['audience'] ?? 'all'] ?? 0)) ?></strong> confirmed subscribers (<?= $e(\App\Services\NewsletterService::AUDIENCES[$campaign['audience'] ?? 'all'] ?? 'All subscribers') ?>), in batches. A campaign can only be sent once.</p>
                                <label class="cm-row" style="font-size: 13px; color: #334155; margin-bottom: 12px; cursor: pointer;"><input type="checkbox" x-model="sure" style="width: 16px; height: 16px;"> I checked the preview and the test e-mail.</label>
                                <button type="submit" class="cm-btn cm-btn-dark" :disabled="!sure" :style="sure ? '' : 'opacity: .5; cursor: not-allowed;'">Send campaign</button>
                            </form>
                        <?php elseif (!$campaign): ?>
                            <div class="cm-note-card cm-card"><h2>Next steps</h2><p class="cm-sub" style="margin: 0;">Save the draft to send yourself a test and then send it to subscribers.</p></div>
                        <?php endif; ?>
                    </div>
                </div>

            <?php elseif ($tab === 'subscribers'): ?>
                <style>
                    .sb-filters { display: flex; gap: 10px; flex-wrap: wrap; align-items: center; }
                    .sb-search { position: relative; flex: 1 1 240px; min-width: 0; }
                    .sb-search svg { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #94A3B8; pointer-events: none; }
                    .sb-search input { padding-left: 38px; }
                    .sb-stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(140px, 1fr)); gap: 10px; margin: 16px 0; }
                    .sb-stat { border: 1px solid #F1F5F9; background: #F8FAFC; border-radius: 12px; padding: 10px 12px; }
                    .sb-stat span { display: block; font-size: 11px; font-weight: 700; letter-spacing: .04em; text-transform: uppercase; color: #94A3B8; }
                    .sb-stat strong { display: block; font-size: 18px; font-weight: 800; color: #000623; margin-top: 2px; }
                    .sb-person { display: flex; align-items: center; gap: 10px; min-width: 0; }
                    .sb-avatar { width: 34px; height: 34px; border-radius: 50%; background: #EEF0FF; color: #4F48E6; font-size: 13px; font-weight: 800; display: flex; align-items: center; justify-content: center; flex-shrink: 0; text-transform: uppercase; }
                    .sb-email { font-weight: 700; color: #0F172A; overflow-wrap: anywhere; }
                    .sb-group { height: 32px; border: 1px solid #E2E8F0; border-radius: 9px; padding: 0 8px; font-size: 12.5px; background: #FFFFFF; font-family: inherit; color: #334155; }
                    .sb-empty { text-align: center; padding: 36px 12px; }
                    .sb-empty svg { color: #C7D2FE; }
                    .sb-empty h3 { font-size: 15px; font-weight: 800; color: #000623; margin: 10px 0 4px; }
                    .sb-empty p { font-size: 13px; color: #64748B; margin: 0; }
                </style>
                <div class="cm-card">
                    <div class="cm-between" style="margin-bottom: 4px;">
                        <div><h2>Subscribers</h2><p class="cm-sub" style="margin: 0;">People who joined the newsletter. Only confirmed subscribers receive campaigns; the group decides which campaigns reach them.</p></div>
                        <a href="<?= url('/admin/communications/subscribers/export') ?>" class="cm-btn cm-btn-ghost"><svg width="15" height="15" style="margin-right: 6px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>Export CSV</a>
                    </div>
                    <div class="sb-stats">
                        <div class="sb-stat"><span>Confirmed</span><strong><?= number_format($subStats['subscribed']) ?></strong></div>
                        <div class="sb-stat"><span>Waiting to confirm</span><strong><?= number_format($subStats['pending']) ?></strong></div>
                        <div class="sb-stat"><span>Unsubscribed</span><strong><?= number_format($subStats['unsubscribed']) ?></strong></div>
                        <div class="sb-stat"><span>New this week</span><strong><?= number_format($subStats['new_7d']) ?></strong></div>
                    </div>
                    <form method="GET" action="<?= url('/admin/communications') ?>" class="sb-filters">
                        <input type="hidden" name="tab" value="subscribers">
                        <div class="sb-search">
                            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            <input class="cm-input" name="q" value="<?= $e($q) ?>" placeholder="Search by e-mail or name" aria-label="Search subscribers">
                        </div>
                        <div style="flex: 0 0 210px; max-width: 100%;"><select data-ic-select name="status" class="cm-input" aria-label="Status" onchange="this.form.submit()">
                            <option value="" <?= $status === '' ? 'selected' : '' ?>>All statuses</option>
                            <option value="subscribed" <?= $status === 'subscribed' ? 'selected' : '' ?>>Confirmed</option>
                            <option value="pending" <?= $status === 'pending' ? 'selected' : '' ?>>Waiting to confirm</option>
                            <option value="unsubscribed" <?= $status === 'unsubscribed' ? 'selected' : '' ?>>Unsubscribed</option>
                        </select></div>
                        <button type="submit" class="cm-btn">Search</button>
                        <?php if ($q !== '' || $status !== ''): ?><a class="cm-btn cm-btn-ghost" href="<?= url('/admin/communications?tab=subscribers') ?>">Clear</a><?php endif; ?>
                    </form>
                    <?php if (!$subscribers['rows']): ?>
                        <div class="sb-empty">
                            <svg width="44" height="44" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <h3><?= ($q !== '' || $status !== '') ? 'No subscribers match' : 'No subscribers yet' ?></h3>
                            <p><?= ($q !== '' || $status !== '') ? 'Try another search or clear the filters.' : 'People join from the newsletter form on the home page and confirm by e-mail.' ?></p>
                        </div>
                    <?php else: ?>
                        <div class="cm-table-wrap no-scrollbar" style="margin-top: 14px;">
                            <table class="cm-table">
                                <thead><tr><th>Subscriber</th><th>Group</th><th>Status</th><th>Joined from</th><th>Joined</th><th>Confirmed</th><th></th></tr></thead>
                                <tbody>
                                <?php foreach ($subscribers['rows'] as $s): ?>
                                    <tr>
                                        <td><div class="sb-person"><span class="sb-avatar"><?= $e(mb_substr($s['name'] ?: $s['email'], 0, 1)) ?></span><div style="min-width: 0;"><div class="sb-email"><?= $e($s['email']) ?></div><?php if ($s['name']): ?><small><?= $e($s['name']) ?></small><?php endif; ?></div></div></td>
                                        <td>
                                            <form method="POST" action="<?= url('/admin/communications/subscribers/' . (int)$s['id'] . '/audience') ?>" style="margin: 0;">
                                                <select data-ic-select name="audience" class="sb-group" aria-label="Group for <?= $e($s['email']) ?>" onchange="this.form.submit()">
                                                    <option value="student" <?= ($s['audience'] ?? 'student') === 'student' ? 'selected' : '' ?>>Student or graduate</option>
                                                    <option value="employer" <?= ($s['audience'] ?? '') === 'employer' ? 'selected' : '' ?>>Employer</option>
                                                </select>
                                            </form>
                                        </td>
                                        <td><?= $pill($s['status'] === 'pending' ? 'pending' : $s['status']) ?></td>
                                        <td><?= $e(ucfirst(str_replace(['-', '_'], ' ', (string)$s['source']))) ?></td>
                                        <td><?= $e($when($s['created_at'])) ?></td>
                                        <td><?= $e($when($s['confirmed_at'])) ?></td>
                                        <td style="text-align: right;">
                                            <?php if ($s['status'] !== 'unsubscribed'): ?>
                                                <form method="POST" action="<?= url('/admin/communications/subscribers/' . (int)$s['id'] . '/unsubscribe') ?>" style="margin: 0;" x-data="{ sure: false }">
                                                    <button type="button" class="cm-btn cm-btn-ghost cm-btn-sm" x-show="!sure" @click="sure = true">Remove</button>
                                                    <button type="submit" class="cm-btn cm-btn-sm" x-show="sure" x-cloak style="background: #B91C1C;">Confirm remove</button>
                                                </form>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <?= $pager($subscribers['total'], $pageNo, ['tab' => 'subscribers', 'status' => $status, 'q' => $q]) ?>
                    <?php endif; ?>
                </div>

            <?php elseif ($tab === 'inbox'): ?>
                <div class="cm-inbox">
                    <div class="cm-card">
                        <div class="cm-between" style="margin-bottom: 12px;">
                            <h2>Messages</h2>
                            <form method="GET" action="<?= url('/admin/communications') ?>" class="cm-row">
                                <input type="hidden" name="tab" value="inbox">
                                <select data-ic-select name="status" class="cm-input" style="width: 150px;" aria-label="Status" onchange="this.form.submit()">
                                    <option value="" <?= $status === '' ? 'selected' : '' ?>>All</option>
                                    <?php foreach (ContactService::STATUSES as $k => $label): ?>
                                        <option value="<?= $e($k) ?>" <?= $status === $k ? 'selected' : '' ?>><?= $e($label) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </form>
                        </div>
                        <?php if (!$messages['rows']): ?>
                            <div class="cm-empty">No messages yet. Messages from the Contact page appear here.</div>
                        <?php else: ?>
                            <?php foreach ($messages['rows'] as $m): $active = $openMessage && (int)$openMessage['id'] === (int)$m['id']; ?>
                                <a class="cm-link-row" href="<?= url('/admin/communications?tab=inbox&id=' . (int)$m['id'] . ($status !== '' ? '&status=' . $e($status) : '')) ?>" style="padding: 12px; border-radius: 12px; margin-bottom: 6px; background: <?= $active ? '#EEF2FF' : '#FFFFFF' ?>; border: 1px solid <?= $active ? '#C7D2FE' : '#F1F5F9' ?>;">
                                    <div class="cm-between" style="gap: 8px;">
                                        <strong style="font-size: 13.5px; color: #0F172A; min-width: 0; overflow-wrap: anywhere;"><?= $e($m['name']) ?></strong>
                                        <?= $pill($m['status']) ?>
                                    </div>
                                    <div style="font-size: 12.5px; color: #334155; margin-top: 3px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;"><?= $e($m['subject'] ?: ucfirst($m['topic'])) ?></div>
                                    <div style="font-size: 11.5px; color: #94A3B8; margin-top: 2px;"><?= $e($m['reference']) ?> &middot; <?= $e($when($m['created_at'])) ?></div>
                                </a>
                            <?php endforeach; ?>
                            <?= $pager($messages['total'], $pageNo, ['tab' => 'inbox', 'status' => $status]) ?>
                        <?php endif; ?>
                    </div>

                    <div class="cm-card">
                        <?php if (!$openMessage): ?>
                            <div class="cm-empty">Select a message to read and reply.</div>
                        <?php else: $m = $openMessage; ?>
                            <div class="cm-between" style="margin-bottom: 10px;">
                                <div style="min-width: 0;">
                                    <h2 style="overflow-wrap: anywhere;"><?= $e($m['subject'] ?: ucfirst($m['topic'])) ?></h2>
                                    <p class="cm-sub" style="margin: 0; overflow-wrap: anywhere;"><?= $e($m['name']) ?> &lt;<?= $e($m['email']) ?>&gt; &middot; <?= $e(ContactService::ROLES[$m['role']] ?? $m['role']) ?> &middot; <?= $e($m['reference']) ?></p>
                                </div>
                                <form method="POST" action="<?= url('/admin/communications/inbox/' . (int)$m['id'] . '/status') ?>" class="cm-row" style="margin: 0;">
                                    <select data-ic-select name="status" class="cm-input" style="width: 130px;" aria-label="Change status" onchange="this.form.submit()">
                                        <?php foreach (ContactService::STATUSES as $k => $label): ?>
                                            <option value="<?= $e($k) ?>" <?= $m['status'] === $k ? 'selected' : '' ?>><?= $e($label) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </form>
                            </div>
                            <div style="font-size: 11.5px; color: #94A3B8; margin-bottom: 8px;">Received <?= $e($when($m['created_at'])) ?></div>
                            <div class="cm-msg"><?= $e($m['message']) ?></div>
                            <?php if ($m['reply']): ?>
                                <div style="font-size: 12px; font-weight: 700; color: #047857; margin: 16px 0 6px;">Replied by <?= $e($m['replied_by']) ?>, <?= $e($when($m['replied_at'])) ?></div>
                                <div class="cm-msg" style="background: #F0FDF4;"><?= $e($m['reply']) ?></div>
                            <?php endif; ?>
                            <form method="POST" action="<?= url('/admin/communications/inbox/' . (int)$m['id'] . '/reply') ?>" style="margin-top: 16px;">
                                <div class="cm-field"><label for="cm-reply"><?= $m['reply'] ? 'Send another reply' : 'Reply by e-mail' ?></label><textarea id="cm-reply" class="cm-textarea" name="reply" required placeholder="Write your reply. It is sent from the InternConnect mailbox with the reference number."></textarea></div>
                                <div class="cm-row" style="justify-content: flex-end;"><button type="submit" class="cm-btn">Send reply</button></div>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>

            <?php else: ?>
                <div class="cm-card">
                    <div class="cm-between" style="margin-bottom: 14px;">
                        <div><h2>Mail log</h2><p class="cm-sub" style="margin: 0;">The last 50 e-mails. Failed messages are retried automatically up to 5 times.</p></div>
                        <form method="POST" action="<?= url('/admin/communications/test-mail') ?>" class="cm-row" style="margin: 0;">
                            <input class="cm-input" style="width: 230px; max-width: 100%;" type="email" name="to" required value="<?= $e($staff['email'] ?? '') ?>" aria-label="Send a test e-mail to">
                            <button type="submit" class="cm-btn cm-btn-ghost">Send test e-mail</button>
                        </form>
                    </div>
                    <?php if (empty($mailLog)): ?>
                        <div class="cm-empty">No e-mails yet.</div>
                    <?php else: ?>
                        <div class="cm-table-wrap no-scrollbar">
                            <table class="cm-table">
                                <thead><tr><th>To</th><th>Subject</th><th>Type</th><th>Status</th><th>Sent</th><th>Opened</th></tr></thead>
                                <tbody>
                                <?php foreach ($mailLog as $l): ?>
                                    <tr>
                                        <td style="overflow-wrap: anywhere;"><?= $e($l['to_email']) ?></td>
                                        <td><?= $e($l['subject']) ?><?php if ($l['last_error'] && $l['status'] !== 'sent'): ?><small style="color: #B91C1C;"><?= $e($l['last_error']) ?></small><?php endif; ?></td>
                                        <td><?= $e(str_replace('_', ' ', $l['category'])) ?></td>
                                        <td><?= $pill($l['status']) ?><?php if ($l['attempts'] > 1): ?><small><?= (int)$l['attempts'] ?> attempts</small><?php endif; ?></td>
                                        <td><?= $e($when($l['sent_at'])) ?></td>
                                        <td><?= $e($when($l['opened_at'])) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </main>
    </div>
</div>
