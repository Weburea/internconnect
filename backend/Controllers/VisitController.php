<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Services\RateLimiter;
use App\Services\VisitService;

/**
 * POST /t/visit  page view / heartbeat beacon from public/assets/js/ic-visit.js (CSRF via ?_csrf=)
 * GET  /geo/me   approximate city of this visitor for "Near me" (never stored client-side)
 * docs/56-location-and-visits.md
 */
class VisitController extends Controller
{
    public function beacon(): void
    {
        $hit = RateLimiter::hit('visit:' . session_id(), 120, 3600);
        if ($hit['allowed']) {
            $raw = json_decode((string)file_get_contents('php://input'), true);
            $in = is_array($raw) ? $raw : $_POST;
            VisitService::track((string)($in['page'] ?? '/'), !empty($in['heartbeat']));
        }
        http_response_code(204);
    }

    public function me(): void
    {
        $here = VisitService::here();
        header('Cache-Control: no-store');
        $this->jsonResponse($here ? ['success' => true, 'city' => $here['city'], 'country' => $here['country']] : ['success' => false, 'error' => 'We could not work out your location.'], $here ? 200 : 404);
    }
}
