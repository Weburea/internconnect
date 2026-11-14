<?php
declare(strict_types=1);
/**
 * Cookie consent banner (docs/54-email-newsletter-contact.md, "Cookies").
 * Shown until the visitor chooses. Choices are stored by public/assets/js/ic-consent.js in the
 * `ic_consent` cookie; the full controls live on /cookies. Essential cookies need no consent.
 */
?>
<div x-data="{ show: false, init() { this.show = !!window.icConsent && !window.icConsent.decided(); }, choose(all) { all ? window.icConsent.acceptAll() : window.icConsent.essentialOnly(); this.show = false; } }"
     x-show="show" x-cloak x-transition.opacity.duration.200ms
     class="ic-cookie-banner" role="dialog" aria-live="polite" aria-label="Cookie choices">
    <style>
        .ic-cookie-banner { position: fixed; left: 16px; bottom: 16px; z-index: 9990; width: min(420px, calc(100vw - 32px)); background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 16px; box-shadow: 0 20px 45px -18px rgba(0, 6, 35, .35); padding: 16px; box-sizing: border-box; font-family: inherit; }
        .ic-cookie-banner h2 { font-size: 14.5px; font-weight: 800; color: #000623; margin: 0 0 6px; }
        .ic-cookie-banner p { font-size: 13px; line-height: 1.55; color: #475569; margin: 0 0 12px; }
        .ic-cookie-banner a { color: #4F48E6; font-weight: 700; text-decoration: none; }
        .ic-cookie-actions { display: flex; gap: 8px; flex-wrap: wrap; }
        .ic-cookie-actions button { flex: 1 1 130px; height: 40px; border-radius: 11px; font-size: 13px; font-weight: 700; cursor: pointer; font-family: inherit; }
        .ic-cookie-accept { background: #4F48E6; color: #FFFFFF; border: 0; }
        .ic-cookie-accept:hover { background: #4338CA; }
        .ic-cookie-essential { background: #FFFFFF; color: #0F172A; border: 1px solid #CBD5E1; }
        .ic-cookie-essential:hover { background: #F8FAFC; }
        @media (max-width: 480px) { .ic-cookie-banner { left: 12px; bottom: 12px; width: calc(100vw - 24px); } }
    </style>
    <h2>Cookies on InternConnect</h2>
    <p>We use essential cookies to keep you signed in and safe. With your permission we also use a visitor number to count returning visitors. No advertising cookies. <a href="<?= url('/cookies#manage-preferences') ?>">Cookie settings</a></p>
    <div class="ic-cookie-actions">
        <button type="button" class="ic-cookie-essential" @click="choose(false)">Essential only</button>
        <button type="button" class="ic-cookie-accept" @click="choose(true)">Accept all</button>
    </div>
</div>
