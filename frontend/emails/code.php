<?php
/**
 * One-time code e-mail (password reset, e-mail verification).
 *
 * @var string      $title
 * @var string      $greeting    e.g. "Hi Sarah,"
 * @var string      $intro       Plain text before the code
 * @var string      $code        6 digits
 * @var string      $codeLabel   e.g. "Your reset code"
 * @var int         $minutes     Validity
 * @var string|null $note        Small print (ignore-if-not-you)
 */
$e = static fn ($v): string => htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');
$font = "'Inter', 'Segoe UI', Arial, Helvetica, sans-serif";
?>
<h1 class="ic-h1" style="margin: 0 0 14px; font-family: <?= $font ?>; font-size: 28px; line-height: 36px; font-weight: 800; color: #000623; letter-spacing: -0.5px;"><?= $e($title) ?></h1>
<p style="margin: 0 0 22px;"><strong style="color: #000623;"><?= $e($greeting) ?></strong> <?= $e($intro) ?> The code expires in <strong style="color: #4F48E6;"><?= (int)$minutes ?> minutes</strong>.</p>
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="margin: 0 0 22px; background: #F8F8FD; border: 1px solid #E4E2F5; border-radius: 14px;">
  <tr>
    <td align="center" style="padding: 22px 16px 24px; font-family: <?= $font ?>;">
      <div style="font-size: 12px; line-height: 16px; font-weight: 800; letter-spacing: 1.6px; color: #4F48E6; text-transform: uppercase; margin-bottom: 12px;"><?= $e($codeLabel) ?></div>
      <div class="ic-code" style="font-family: 'SFMono-Regular', Menlo, Consolas, 'Courier New', monospace; font-size: 34px; line-height: 40px; font-weight: 700; letter-spacing: 12px; color: #000623;"><?= $e(implode(' ', str_split($code))) ?></div>
    </td>
  </tr>
</table>
<?php if (!empty($note)): ?>
<p style="margin: 0; font-size: 13.5px; line-height: 21px; color: #64748B;"><?= $e($note) ?></p>
<?php endif; ?>
