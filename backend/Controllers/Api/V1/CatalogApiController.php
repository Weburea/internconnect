<?php

declare(strict_types=1);

namespace App\Controllers\Api\V1;

use App\Api\ApiException;
use App\Api\ApiResponse;
use App\Api\ApiAuth;
use App\Services\ElearningService;
use App\Services\PlanService;

/**
 * Public catalog (no sign-in needed):
 * GET /api/v1/health
 * GET /api/v1/internships?q=&category=&location=&remote=1&page=&per_page=
 * GET /api/v1/internships/{id}
 * GET /api/v1/elearning/programs?category=
 */
final class CatalogApiController extends ApiController
{
    public function health(): void
    {
        ApiResponse::ok(['status' => 'ok', 'version' => 'v1', 'time' => date(DATE_ATOM)], 'InternConnect API is running.');
    }

    /**
     * Shared with /internships (Find Jobs): backend/Data/internships.php.
     * Premium listings show their pay only to viewers whose plan includes them (PlanService).
     */
    public static function internshipData(string $viewerPlan = 'free'): array
    {
        return array_map(static fn (array $i) => [
            'id'          => (string)$i['id'],
            'title'       => (string)$i['title'],
            'company'     => (string)$i['company'],
            'company_slug'=> (string)($i['companySlug'] ?? ''),
            'category'    => (string)$i['category'],
            'location'    => (string)$i['location'],
            'country'     => (string)($i['country'] ?? ''),
            'commitment'  => (string)$i['commitment'],
            'work_mode'   => (string)$i['tag'],
            'posted'      => (string)$i['posted'],
            'stipend'     => $i['locked'] ? null : ['amount' => (int)$i['stipendAmount'], 'currency' => 'NGN', 'label' => (string)$i['stipendText']],
            'premium'     => $i['premium'],
            'locked'      => $i['locked'],
            'tags'        => array_values((array)$i['tags']),
            'description' => (string)$i['description'],
            'url'         => absolute_url('/internship/' . $i['id']),
        ], PlanService::annotateListings(require dirname(__DIR__, 3) . '/Data/internships.php', $viewerPlan));
    }

    /** Plan of the caller when a valid Bearer token is sent (public endpoints stay public). */
    private function viewerPlan(): string
    {
        $account = ApiAuth::fromRequest();
        return $account && $account['role'] === 'candidate' ? PlanService::effectivePlan('candidate', (int)$account['id']) : 'free';
    }

    public function internships(): void
    {
        $items = self::internshipData($this->viewerPlan());
        $q = strtolower(trim((string)($_GET['q'] ?? '')));
        $category = strtolower(trim((string)($_GET['category'] ?? '')));
        $location = strtolower(trim((string)($_GET['location'] ?? '')));
        $remote = isset($_GET['remote']) && $_GET['remote'] !== '0';
        $items = array_filter($items, static function (array $i) use ($q, $category, $location, $remote) {
            if ($q !== '' && !str_contains(strtolower($i['title'] . ' ' . $i['company'] . ' ' . $i['description'] . ' ' . implode(' ', $i['tags'])), $q)) {
                return false;
            }
            if ($category !== '' && strtolower($i['category']) !== $category && !in_array($category, array_map('strtolower', $i['tags']), true)) {
                return false;
            }
            if ($location !== '' && !str_contains(strtolower($i['location'] . ' ' . $i['country']), $location)) {
                return false;
            }
            return !$remote || strtoupper($i['work_mode']) === 'REMOTE';
        });
        [$page, $per] = $this->page();
        ApiResponse::paginated(array_values($items), 'Internships retrieved.', $page, $per);
    }

    public function internship(array $params = []): void
    {
        $id = (string)($params['id'] ?? '');
        foreach (self::internshipData($this->viewerPlan()) as $i) {
            if ($i['id'] === $id) {
                ApiResponse::ok($i, 'Internship retrieved.');
            }
        }
        throw new ApiException('Internship not found.', 404);
    }

    public function elearningPrograms(): void
    {
        $category = (string)($_GET['category'] ?? '');
        $programs = array_values(array_filter(ElearningService::catalog(), static fn ($p) => $category === '' || $p['category'] === $category));
        $programs = array_map(static function (array $p): array {
            $p['enroll_url'] = absolute_url('/elearning/go/' . $p['id']);
            // Absolute logo URL for mobile clients (scheme + host + site path).
            $origin = substr(absolute_url('/'), 0, -strlen(url('/')));
            if (str_starts_with((string)$p['logo'], '/')) {
                $p['logo'] = $origin . $p['logo'];
            }
            unset($p['go_url']);
            return $p;
        }, $programs);
        [$page, $per] = $this->page();
        ApiResponse::paginated($programs, 'E-learning programs retrieved.', $page, $per);
    }
}
