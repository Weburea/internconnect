<?php

declare(strict_types=1);

/**
 * InternConnect migration 2026_11_12_000002: tables of the services built in the frontend phase.
 * Each service owns its MySQL DDL (CREATE TABLE IF NOT EXISTS, additive columns only) and seeds
 * its defaults (plan limits, tier rules, e-learning partners) without overwriting admin edits.
 * Run by bin/migrate.php. Safe to run again.
 */
return static function (): array {
    $steps = [
        'rate_limits'                         => static fn () => (new ReflectionMethod(\App\Services\RateLimiter::class, 'ensureTable'))->invoke(null),
        'api tokens'                          => static fn () => (new ReflectionMethod(\App\Api\ApiAuth::class, 'ensureTables'))->invoke(null),
        'billing'                             => static fn () => \App\Services\BillingService::ensureTables(),
        'applications'                        => static fn () => \App\Services\ApplicationService::ensureTables(),
        'cv profiles'                         => static fn () => \App\Services\CvService::ensureTables(),
        'e-learning directory'                => static fn () => \App\Services\ElearningService::ensureTables(),
        'plans, grants, usage'                => static fn () => \App\Services\PlanService::ensureTables(),
        'mail queue'                          => static fn () => \App\Services\MailService::ensureTables(),
        'newsletter'                          => static fn () => \App\Services\NewsletterService::ensureTables(),
        'contact messages'                    => static fn () => \App\Services\ContactService::ensureTables(),
        'jenny conversations'                 => static fn () => \App\Services\JennyService::ensureTables(),
        'visits'                              => static fn () => \App\Services\VisitService::ensureTables(),
        'training partners'                   => static fn () => \App\Services\PartnerService::ensureTables(),
    ];
    $done = [];
    foreach ($steps as $name => $step) {
        $step();
        $done[] = $name;
    }
    return $done;
};
