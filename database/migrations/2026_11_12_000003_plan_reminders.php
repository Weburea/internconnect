<?php

declare(strict_types=1);

/**
 * InternConnect migration 2026_11_12_000003: plan_reminders (one expiry e-mail per paid period),
 * now created with the other plan tables. Additive and safe to run again.
 */
return static function (): array {
    \App\Services\PlanService::ensureTables();
    return ['plan_reminders'];
};
