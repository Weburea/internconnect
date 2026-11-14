<?php

declare(strict_types=1);

/**
 * View: newsletter confirm / unsubscribe result (docs/54-email-newsletter-contact.md)
 * Routes: /newsletter/confirm/{token}, /newsletter/unsubscribe/{token}
 * Layout: app
 *
 * @var string      $state  confirmed | confirm-unsubscribe | unsubscribed | invalid
 * @var string|null $email
 * @var string|null $token
 */
$e = static fn ($v): string => htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');
$copy = [
    'confirmed'           => ['You are subscribed', 'Thanks for confirming. Look out for new internships, partner training programmes and career tips in your inbox.'],
    'confirm-unsubscribe' => ['Unsubscribe from the newsletter?', 'You will stop receiving the InternConnect newsletter. Account e-mails, such as application updates, are not affected.'],
    'unsubscribed'        => ['You have been unsubscribed', 'You will not receive the newsletter any more. You can subscribe again at any time from the bottom of our home page.'],
    'invalid'             => ['This link is no longer valid', 'The link may have expired or already been used. Subscribe again from the bottom of our home page, or contact us if you need help.'],
][$state] ?? ['Newsletter', ''];
$good = in_array($state, ['confirmed', 'unsubscribed'], true);
?>
<section class="ic-page-shell" style="min-height: 62vh; display: flex; align-items: center; justify-content: center; padding: 48px 16px;">
    <div style="width: 100%; max-width: 480px; background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 20px; padding: 34px 28px; text-align: center; box-shadow: 0 10px 30px -12px rgba(0, 6, 35, 0.12); box-sizing: border-box;">
        <div style="width: 56px; height: 56px; border-radius: 50%; margin: 0 auto 18px; display: flex; align-items: center; justify-content: center; background: <?= $good ? '#ECFDF5' : '#EEF2FF' ?>; color: <?= $good ? '#059669' : '#4F48E6' ?>;">
            <?php if ($good): ?>
                <svg style="width: 28px; height: 28px;" width="28" height="28" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
            <?php else: ?>
                <svg style="width: 26px; height: 26px;" width="26" height="26" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            <?php endif; ?>
        </div>
        <h1 style="font-size: 22px; font-weight: 800; color: #000623; margin: 0 0 10px; letter-spacing: -0.02em;"><?= $e($copy[0]) ?></h1>
        <?php if ($email): ?>
            <p style="font-size: 14px; font-weight: 600; color: #4F48E6; margin: 0 0 8px; overflow-wrap: anywhere;"><?= $e($email) ?></p>
        <?php endif; ?>
        <p style="font-size: 14.5px; line-height: 1.6; color: #64748B; margin: 0 0 24px;"><?= $e($copy[1]) ?></p>

        <?php if ($state === 'confirm-unsubscribe' && $token): ?>
            <form method="POST" action="<?= url('/newsletter/unsubscribe/' . $e($token)) ?>" style="display: flex; gap: 10px; justify-content: center; flex-wrap: wrap;">
                <button type="submit" class="ic-btn ic-btn-primary" style="padding: 12px 22px;">Yes, unsubscribe</button>
                <a href="<?= url('/') ?>" class="ic-btn ic-btn-secondary" style="padding: 12px 22px;">Keep me subscribed</a>
            </form>
        <?php else: ?>
            <div style="display: flex; gap: 10px; justify-content: center; flex-wrap: wrap;">
                <a href="<?= url('/internships') ?>" class="ic-btn ic-btn-primary" style="padding: 12px 22px;">Find internships</a>
                <a href="<?= url($state === 'invalid' ? '/contact' : '/') ?>" class="ic-btn ic-btn-secondary" style="padding: 12px 22px;"><?= $state === 'invalid' ? 'Contact us' : 'Back to home' ?></a>
            </div>
        <?php endif; ?>
    </div>
</section>
