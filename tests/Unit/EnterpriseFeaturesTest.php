<?php
declare(strict_types=1);

namespace Tests\Unit;

use App\Controllers\EmployerController;
use App\Services\ExportService;
use ReflectionClass;
use ReflectionMethod;

class EnterpriseFeaturesTest
{
    public function testEmployerAnalyticsControllerMethodsExist(): void
    {
        $ref = new ReflectionClass(EmployerController::class);
        $methods = ['analytics', 'exportCandidates', 'exportInterviews', 'exportBilling'];

        foreach ($methods as $method) {
            if (!$ref->hasMethod($method)) {
                throw new \Exception("EmployerController is missing method: {$method}");
            }
        }
    }

    public function testAnalyticsUrlRoutes(): void
    {
        $routesFile = file_get_contents(dirname(__DIR__, 2) . '/backend/routes.php');
        $expectedRoutes = [
            '/employer/analytics',
            '/employer/analytics/export/candidates',
            '/employer/analytics/export/interviews',
            '/employer/analytics/export/billing'
        ];

        foreach ($expectedRoutes as $route) {
            if (strpos($routesFile, "'" . $route . "'") === false && strpos($routesFile, '"' . $route . '"') === false) {
                throw new \Exception("Route {$route} is not registered in routes.php");
            }
        }
    }

    public function testExportServiceMethods(): void
    {
        $ref = new ReflectionClass(ExportService::class);
        $expectedMethods = [
            'streamCsv',
            'exportCandidatesPipeline',
            'exportInterviews',
            'exportBilling'
        ];

        foreach ($expectedMethods as $m) {
            if (!$ref->hasMethod($m)) {
                throw new \Exception("ExportService is missing method: {$m}");
            }
            $rm = $ref->getMethod($m);
            if (!$rm->isStatic()) {
                throw new \Exception("ExportService method {$m} should be static");
            }
        }
    }

    public function testZeroColoredSideBordersInAnalyticsViews(): void
    {
        $viewPath = dirname(__DIR__, 2) . '/frontend/employer/analytics/index.php';
        if (!file_exists($viewPath)) {
            throw new \Exception("Analytics view not found at: {$viewPath}");
        }

        $content = file_get_contents($viewPath);
        $forbiddenPatterns = [
            '/border-left:\s*[2-9]px\s+solid/i',
            '/border-top:\s*[2-9]px\s+solid/i',
            '/border-left:\s*1px\s+solid\s+#[0-9a-f]{6}/i'
        ];

        foreach ($forbiddenPatterns as $pattern) {
            if (preg_match($pattern, $content, $matches)) {
                throw new \Exception("Forbidden colored accent border pattern detected in analytics view: {$matches[0]}");
            }
        }
    }

    public function testZeroNativeAlertsInAnalyticsViews(): void
    {
        $viewPath = dirname(__DIR__, 2) . '/frontend/employer/analytics/index.php';
        $content = file_get_contents($viewPath);

        $forbiddenFunctions = ['alert(', 'confirm(', 'prompt('];
        foreach ($forbiddenFunctions as $func) {
            if (strpos($content, $func) !== false) {
                throw new \Exception("Forbidden native browser function {$func} found in analytics view");
            }
        }
    }

    public function testSingleLineButtonIntegrityInAnalyticsViews(): void
    {
        $viewPath = dirname(__DIR__, 2) . '/frontend/employer/analytics/index.php';
        $content = file_get_contents($viewPath);

        if (strpos($content, 'white-space: nowrap !important;') === false) {
            throw new \Exception("Button integrity rule not enforced in analytics view (white-space: nowrap !important missing)");
        }
    }
}

