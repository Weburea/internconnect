<?php
/**
 * Newsletter issue written by admin (Admin > Communications > Compose). docs/54 §2.
 * Layouts by $type: general, marketing (feature tiles + colour band), event (details box),
 * interns (internship cards + Jenny tip), employers (navy band, ticked benefits).
 * Body: plain text, blank line between paragraphs, "## " sub-heading, "- " bullets. Admin never writes HTML.
 *
 * @var string      $title
 * @var string      $body
 * @var string      $type
 * @var array       $extra     event_date, event_time, event_tz, event_place
 * @var array       $listings  interns layout: [title, company, meta, pay, url]
 * @var string|null $bodyImage
 * @var array|null  $cta
 * @var array|null  $jenny
 */
$e = static fn ($v): string => htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');
$type = $type ?? 'general';
$extra = $extra ?? [];
$listings = $listings ?? [];
$blocks = preg_split("/\R{2,}/", trim((string)$body)) ?: [];
$font = 'font-family: Arial, Helvetica, sans-serif;';
$button = static function (array $cta, string $bg = '#4F48E6', string $fg = '#FFFFFF') use ($e, $font): string {
    return '<table role="presentation" cellpadding="0" cellspacing="0" border="0" style="margin: 22px 0 4px;"><tr><td class="ic-btn" bgcolor="' . $bg . '" style="border-radius: 12px; background: ' . $bg . '; border: 1px solid ' . $bg . ';">'
        . '<a href="' . $e($cta['url']) . '" style="display: inline-block; padding: 14px 28px; ' . $font . ' font-size: 15px; font-weight: 700; color: ' . $fg . '; text-decoration: none; border-radius: 12px;">' . $e($cta['label'] ?: 'Open InternConnect') . '</a></td></tr></table>';
};
// Bullets: dots (general), tiles (marketing), ticks (employers).
$renderBlock = static function (array $lines) use ($e, $type): string {
    $isList = count(array_filter($lines, static fn ($l) => str_starts_with(ltrim($l), '- '))) === count($lines);
    if (!$isList) {
        return '<p style="margin: 0 0 14px;">' . nl2br($e(implode("\n", $lines))) . '</p>';
    }
    $items = array_map(static fn ($l) => substr(ltrim($l), 2), $lines);
    if ($type === 'marketing') {
        $html = '<table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="margin: 4px 0 14px;">';
        foreach (array_chunk($items, 2) as $pair) {
            $html .= '<tr>';
            foreach ($pair as $item) {
                $html .= '<td class="ic-col" width="50%" style="padding: 5px; vertical-align: top;"><div style="background: #F5F3FF; border-radius: 14px; padding: 14px 16px; font-size: 14px; line-height: 21px; color: #1E1B4B;"><div style="width: 26px; height: 4px; border-radius: 4px; background: #4F48E6; margin-bottom: 10px;"></div>' . $e($item) . '</div></td>';
            }
            if (count($pair) === 1) {
                $html .= '<td class="ic-col" width="50%" style="padding: 5px;"></td>';
            }
            $html .= '</tr>';
        }
        return $html . '</table>';
    }
    $html = '<table role="presentation" cellpadding="0" cellspacing="0" border="0" style="margin: 0 0 14px;">';
    foreach ($items as $item) {
        $mark = $type === 'employers'
            ? '<div style="width: 20px; height: 20px; border-radius: 10px; background: #000623; color: #FFFFFF; font-size: 12px; line-height: 20px; text-align: center; font-weight: 700;">&#10003;</div>'
            : '<div style="width: 7px; height: 7px; border-radius: 7px; background: #4F48E6; margin-top: 2px;"></div>';
        $html .= '<tr><td style="vertical-align: top; padding: ' . ($type === 'employers' ? '2px 10px 0 0' : '7px 10px 0 0') . ';">' . $mark . '</td><td style="padding: 2px 0 8px; font-size: 15px; line-height: 24px; color: #334155;">' . $e($item) . '</td></tr>';
    }
    return $html . '</table>';
};
?>
<?php if ($type === 'employers'): ?>
<div style="display: inline-block; margin: 0 0 14px; padding: 5px 12px; border-radius: 999px; background: #000623; color: #FFFFFF; <?= $font ?> font-size: 11px; font-weight: 700; letter-spacing: 1px;">FOR EMPLOYERS</div>
<?php elseif ($type === 'event'): ?>
<div style="display: inline-block; margin: 0 0 14px; padding: 5px 12px; border-radius: 999px; background: #EEF0FF; color: #4F48E6; <?= $font ?> font-size: 11px; font-weight: 700; letter-spacing: 1px;">EVENT INVITATION</div>
<?php elseif ($type === 'interns'): ?>
<div style="display: inline-block; margin: 0 0 14px; padding: 5px 12px; border-radius: 999px; background: #EEF0FF; color: #4F48E6; <?= $font ?> font-size: 11px; font-weight: 700; letter-spacing: 1px;">PICKED FOR YOU</div>
<?php endif; ?>
<h1 class="ic-h1" style="margin: 0 0 18px; <?= $font ?> font-size: <?= $type === 'marketing' ? '30px' : '27px' ?>; line-height: <?= $type === 'marketing' ? '38px' : '35px' ?>; font-weight: 800; color: #000623; letter-spacing: -0.4px;"><?= $e($title) ?></h1>

<?php if ($type === 'event' && !empty($extra['event_date'])):
    $when = strtotime($extra['event_date'] . ' ' . ($extra['event_time'] ?: '00:00')); ?>
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="margin: 0 0 20px; background: #000623; border-radius: 16px;">
  <tr>
    <td class="ic-col" width="96" style="padding: 18px 0 18px 18px; vertical-align: middle;">
      <div style="width: 72px; border-radius: 12px; background: #FFFFFF; text-align: center; overflow: hidden;">
        <div style="background: #4F48E6; color: #FFFFFF; <?= $font ?> font-size: 11px; font-weight: 700; letter-spacing: 1px; padding: 4px 0;"><?= $e(strtoupper(date('M', $when))) ?></div>
        <div style="<?= $font ?> font-size: 30px; font-weight: 800; color: #000623; line-height: 40px;"><?= $e(date('j', $when)) ?></div>
      </div>
    </td>
    <td class="ic-col" style="padding: 18px; vertical-align: middle; <?= $font ?> color: #FFFFFF;">
      <div style="font-size: 16px; font-weight: 700;"><?= $e(date('l, j F Y', $when)) ?></div>
      <?php if (!empty($extra['event_time'])): ?><div style="font-size: 14px; color: #C7D2FE; margin-top: 4px;"><?= $e(date('g:i A', $when) . ' ' . ($extra['event_tz'] ?? '')) ?></div><?php endif; ?>
      <?php if (!empty($extra['event_place'])): ?><div style="font-size: 14px; color: #C7D2FE; margin-top: 4px;"><?= $e($extra['event_place']) ?></div><?php endif; ?>
    </td>
  </tr>
</table>
<?php endif; ?>

<?php foreach ($blocks as $i => $block):
    $lines = preg_split('/\R/', trim($block)) ?: [];
    if (str_starts_with($lines[0] ?? '', '## ')): ?>
<h2 style="margin: 22px 0 10px; <?= $font ?> font-size: 18px; line-height: 25px; font-weight: 800; color: #000623;"><?= $e(substr(array_shift($lines), 3)) ?></h2>
    <?php endif;
    if ($lines) {
        echo $renderBlock($lines);
    }
    if ($i === 0 && !empty($bodyImage)): ?>
<img src="<?= $e($bodyImage) ?>" width="520" alt="" style="display: block; width: 100%; max-width: 520px; height: auto; border: 0; border-radius: 14px; margin: 6px 0 18px;">
    <?php endif;
endforeach; ?>

<?php if ($type === 'interns' && $listings): ?>
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="margin: 6px 0 10px;">
  <?php foreach ($listings as $l): ?>
  <tr><td style="padding: 0 0 10px;">
    <a href="<?= $e($l['url']) ?>" style="display: block; text-decoration: none; border: 1px solid #E2E8F0; border-radius: 14px; padding: 14px 16px;">
      <div style="<?= $font ?> font-size: 15px; font-weight: 700; color: #000623;"><?= $e($l['title']) ?></div>
      <div style="<?= $font ?> font-size: 13px; color: #64748B; margin-top: 3px;"><?= $e($l['company'] . ' · ' . $l['meta']) ?></div>
      <div style="<?= $font ?> font-size: 13px; font-weight: 700; color: #4F48E6; margin-top: 6px;"><?= $e($l['pay']) ?> &rarr;</div>
    </a>
  </td></tr>
  <?php endforeach; ?>
</table>
<?php endif; ?>

<?php if (!empty($jenny['text'])): ?>
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="margin: 22px 0 6px; background: #EEF0FF; border-radius: 14px;">
  <tr>
    <td class="ic-col" width="96" style="padding: 16px 0 16px 16px; vertical-align: middle;">
      <img src="<?= $e(\App\Services\MailService::image('jenny_hi', 160)) ?>" width="80" height="80" alt="Jenny, the InternConnect AI assistant" style="display: block; border: 0; border-radius: 40px;">
    </td>
    <td class="ic-col" style="padding: 16px 18px; vertical-align: middle; font-size: 14px; line-height: 22px; color: #1E1B4B;">
      <div style="font-weight: 800; color: #4F48E6; margin-bottom: 4px;">Tip from Jenny</div>
      <?= nl2br($e($jenny['text'])) ?>
    </td>
  </tr>
</table>
<?php endif; ?>

<?php if (!empty($cta['url'])): ?>
  <?php if ($type === 'marketing'): ?>
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" bgcolor="<?= $e($buttonColor ?: '#4F48E6') ?>" style="margin: 22px 0 4px; background: <?= $e($buttonColor ?: '#4F48E6') ?>; border-radius: 16px;">
  <tr><td style="padding: 22px; text-align: center;">
    <div style="<?= $font ?> font-size: 17px; font-weight: 700; color: #FFFFFF; margin-bottom: 14px;">Ready when you are.</div>
    <a href="<?= $e($cta['url']) ?>" style="display: inline-block; padding: 14px 30px; background: #FFFFFF; color: <?= $e($buttonColor ?: '#4F48E6') ?>; <?= $font ?> font-size: 15px; font-weight: 800; text-decoration: none; border-radius: 12px;"><?= $e($cta['label'] ?: 'Open InternConnect') ?></a>
  </td></tr>
</table>
  <?php elseif ($type === 'employers'): ?>
<?= $button($cta, ($buttonColor ?? '') ?: '#000623') ?>
  <?php else: ?>
<?= $button($cta, ($buttonColor ?? '') ?: '#4F48E6') ?>
  <?php endif; ?>
<?php endif; ?>
