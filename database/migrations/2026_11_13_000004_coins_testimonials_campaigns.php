<?php

declare(strict_types=1);

/**
 * InternConnect migration 2026_11_13_000004: jenny_wallets (Jenny Coins), testimonials (admin-managed
 * quotes and video stories) and the newsletter campaign type/audience/image columns plus the
 * subscriber audience column. Additive and safe to run again.
 */
return static function (): array {
    \App\Services\PlanService::ensureTables();
    \App\Services\NewsletterService::ensureTables();
    \App\Services\TestimonialService::ensureTables();
    return ['jenny_wallets', 'testimonials', 'newsletter_campaigns (type, audience, hero_url, body_image_url, extra)', 'newsletter_subscribers (audience)'];
};
