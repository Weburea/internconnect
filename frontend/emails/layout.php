<?php
/**
 * Branded e-mail layout (docs/54-email-newsletter-contact.md, "E-mail design").
 * One rounded card: banner (or logo row), body, then a navy footer with the brand band.
 * Table layout + inline styles only (Gmail, Outlook, Apple Mail). Images from Cloudinary as PNG/JPG.
 *
 * @var string      $content         Inner HTML from the view
 * @var string      $preheader       Inbox preview text
 * @var array|null  $hero            ['src' => url, 'alt' => text]; banners carry the logo themselves
 * @var string|null $badge           Small pill above the heading, e.g. "PASSWORD RESET"
 * @var string|null $footer          Footer band: circles (default), logo, waves, dots
 * @var string|null $unsubscribeUrl  Newsletter mail only
 */
use App\Core\Env;
use App\Services\MailService;

$e = static fn ($v): string => htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');
$home = MailService::link('/');
$font = "'Inter', 'Segoe UI', Arial, Helvetica, sans-serif";
$socials = array_filter([
    'LinkedIn'  => Env::get('SOCIAL_LINKEDIN', 'https://linkedin.com'),
    'X'         => Env::get('SOCIAL_X', 'https://x.com'),
    'Instagram' => Env::get('SOCIAL_INSTAGRAM', 'https://instagram.com'),
    'Facebook'  => Env::get('SOCIAL_FACEBOOK', 'https://facebook.com'),
]);
?><!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="x-apple-disable-message-reformatting">
<meta name="color-scheme" content="light only">
<title>InternConnect</title>
<style>
  @media only screen and (max-width: 620px) {
    .ic-wrap { width: 100% !important; }
    .ic-pad { padding-left: 24px !important; padding-right: 24px !important; }
    .ic-h1 { font-size: 25px !important; line-height: 32px !important; }
    .ic-code { font-size: 28px !important; letter-spacing: 8px !important; }
    .ic-col { display: block !important; width: 100% !important; }
  }
  @media (prefers-color-scheme: dark) { .ic-btn, .ic-btn a { color: #FFFFFF !important; } }
  [data-ogsc] .ic-btn a, [data-ogsb] .ic-btn a { color: #FFFFFF !important; }
</style>
</head>
<body style="margin: 0; padding: 0; background: #F1F0FA; -webkit-text-size-adjust: 100%;">
<div style="display: none; max-height: 0; overflow: hidden; mso-hide: all; font-size: 1px; line-height: 1px; color: #F1F0FA;"><?= $e($preheader) ?>&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;</div>
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background: #F1F0FA;">
  <tr>
    <td align="center" style="padding: 32px 12px;">
      <table role="presentation" class="ic-wrap" width="600" cellpadding="0" cellspacing="0" border="0" style="width: 600px; max-width: 600px; background: #FFFFFF; border: 1px solid #E4E2F5; border-radius: 22px; overflow: hidden;">
        <?php if (!empty($hero['src'])): ?>
        <tr>
          <td style="padding: 0; line-height: 0; font-size: 0;">
            <a href="<?= $e($home) ?>"><img src="<?= $e($hero['src']) ?>" width="600" alt="<?= $e($hero['alt'] ?? 'InternConnect') ?>" style="display: block; width: 100%; max-width: 600px; height: auto; border: 0;"></a>
          </td>
        </tr>
        <?php else: ?>
        <tr>
          <td class="ic-pad" style="padding: 28px 40px 0;">
            <a href="<?= $e($home) ?>" style="text-decoration: none;">
              <img src="<?= $e(MailService::image('logo', 64)) ?>" width="28" height="32" alt="" style="display: inline-block; vertical-align: middle; border: 0;">
              <span style="display: inline-block; vertical-align: middle; margin-left: 8px; font-family: <?= $font ?>; font-size: 19px; font-weight: 800; color: #000623; letter-spacing: -0.3px;">Intern<span style="color: #4F48E6;">Connect</span></span>
            </a>
          </td>
        </tr>
        <?php endif; ?>
        <tr>
          <td class="ic-pad" style="padding: 34px 40px 38px; font-family: <?= $font ?>; font-size: 15px; line-height: 25px; color: #475569;">
            <?php if (!empty($badge)): ?>
            <table role="presentation" cellpadding="0" cellspacing="0" border="0" style="margin: 0 0 18px;"><tr>
              <td style="background: #EEEBFF; border: 1px solid #D9D4FF; border-radius: 999px; padding: 6px 14px; font-family: <?= $font ?>; font-size: 11.5px; line-height: 14px; font-weight: 800; letter-spacing: 1.4px; color: #4F48E6; text-transform: uppercase;"><?= $e($badge) ?></td>
            </tr></table>
            <?php endif; ?>
            <?= $content ?>
          </td>
        </tr>
        <!-- Footer -->
        <tr>
          <td style="padding: 0; line-height: 0; font-size: 0; background: #000623;">
            <img src="<?= $e(MailService::image(in_array($footer ?? '', ['logo', 'waves', 'dots'], true) ? 'email_footer_' . $footer : 'email_footer', 1200)) ?>" width="600" alt="" style="display: block; width: 100%; max-width: 600px; height: auto; border: 0;">
          </td>
        </tr>
        <tr>
          <td class="ic-pad" align="center" style="background: #000623; padding: 22px 32px 30px; font-family: <?= $font ?>; font-size: 12.5px; line-height: 20px; color: #94A3B8; text-align: center;">
            <div style="margin-bottom: 14px;">
              <?php $i = 0; foreach ($socials as $label => $href): ?><?= $i++ ? '<span style="color: #4F48E6; padding: 0 8px;">&bull;</span>' : '' ?><a href="<?= $e($href) ?>" style="color: #C7C3FF; font-weight: 700; text-decoration: none;"><?= $e($label) ?></a><?php endforeach; ?>
            </div>
            <div style="margin-bottom: 14px;">
              <a href="<?= $e(MailService::link('/internships')) ?>" style="color: #C7C3FF; text-decoration: underline;">Find internships</a><span style="padding: 0 6px;">&bull;</span>
              <a href="<?= $e(MailService::link('/contact')) ?>" style="color: #C7C3FF; text-decoration: underline;">Support</a><span style="padding: 0 6px;">&bull;</span>
              <a href="<?= $e(MailService::link('/privacy')) ?>" style="color: #C7C3FF; text-decoration: underline;">Privacy policy</a>
            </div>
            <?php if (!empty($unsubscribeUrl)): ?>
            <div style="margin-bottom: 8px;">You are receiving this because you subscribed to InternConnect updates. <a href="<?= $e($unsubscribeUrl) ?>" style="color: #C7C3FF; text-decoration: underline;">Unsubscribe</a> at any time.</div>
            <?php else: ?>
            <div style="margin-bottom: 8px;">You are receiving this e-mail from InternConnect.</div>
            <?php endif; ?>
            <div style="color: #64748B;">InternConnect &bull; Find internships. Get trained. Get hired.</div>
          </td>
        </tr>
        <?php if (!empty($trackUrl)): ?>
        <tr><td style="line-height: 0; font-size: 0; background: #000623;"><img src="<?= $e($trackUrl) ?>" width="1" height="1" alt="" style="display: block; border: 0;"></td></tr>
        <?php endif; ?>
      </table>
    </td>
  </tr>
</table>
</body>
</html>
