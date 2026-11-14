<?php
/**
 * General e-mail body: heading, paragraphs, optional details table, button and note.
 *
 * @var string      $title
 * @var string[]    $paragraphs  Plain text (escaped here)
 * @var array|null  $details     [label => value] shown as a summary box
 * @var array|null  $cta         ['label' => text, 'url' => link]
 * @var string|null $note        Small print under the button
 * @var string|null $signoff
 */
$e = static fn ($v): string => htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');
?>
<h1 class="ic-h1" style="margin: 0 0 16px; font-family: 'Inter', 'Segoe UI', Arial, Helvetica, sans-serif; font-size: 28px; line-height: 36px; font-weight: 800; color: #000623; letter-spacing: -0.4px;"><?= $e($title) ?></h1>
<?php foreach (($paragraphs ?? []) as $p): ?>
<p style="margin: 0 0 14px;"><?= nl2br($e($p)) ?></p>
<?php endforeach; ?>
<?php if (!empty($details)): ?>
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="margin: 8px 0 18px; background: #F8FAFC; border-radius: 12px;">
  <?php foreach ($details as $label => $value): ?>
  <tr>
    <td style="padding: 10px 16px; font-size: 13px; color: #64748B; width: 38%; vertical-align: top;"><?= $e($label) ?></td>
    <td style="padding: 10px 16px; font-size: 14px; color: #0F172A; font-weight: 600; vertical-align: top;"><?= nl2br($e($value)) ?></td>
  </tr>
  <?php endforeach; ?>
</table>
<?php endif; ?>
<?php if (!empty($cta['url'])): ?>
<table role="presentation" cellpadding="0" cellspacing="0" border="0" style="margin: 22px 0 8px;">
  <tr>
    <td style="border-radius: 12px; background: #4F48E6;">
      <a href="<?= $e($cta['url']) ?>" style="display: inline-block; padding: 14px 26px; font-family: Arial, Helvetica, sans-serif; font-size: 15px; font-weight: 700; color: #FFFFFF; text-decoration: none; border-radius: 12px;"><?= $e($cta['label']) ?></a>
    </td>
  </tr>
</table>
<?php endif; ?>
<?php if (!empty($note)): ?>
<p style="margin: 16px 0 0; font-size: 13px; line-height: 20px; color: #64748B;"><?= nl2br($e($note)) ?></p>
<?php endif; ?>
<p style="margin: 24px 0 0; font-size: 14px; color: #334155;"><?= $e($signoff ?? 'The InternConnect team') ?></p>
