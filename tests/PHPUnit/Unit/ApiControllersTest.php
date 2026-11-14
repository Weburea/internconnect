<?php

declare(strict_types=1);

namespace Tests\PHPUnit\Unit;

use App\Api\ApiAuth;
use App\Api\ApiException;
use App\Api\ApiResponse;
use App\Api\ApiResponseSent;
use App\Api\OpenApiSpec;
use App\Controllers\Api\V1\AccountApiController;
use App\Controllers\Api\V1\AuthApiController;
use App\Controllers\Api\V1\CatalogApiController;
use App\Core\Router;
use PHPUnit\Framework\TestCase;

/**
 * API controllers run in-process (ApiResponse::$testing captures the envelope instead of exiting),
 * plus the OpenAPI spec <-> routes contract.
 */
final class ApiControllersTest extends TestCase
{
    protected function setUp(): void
    {
        ApiResponse::$testing = true;
        ApiResponse::$captured = null;
        $_ENV['JWT_SECRET'] = str_repeat('ab', 32);
        $_ENV['API_DEMO_PASSWORD'] = 'Correct-Horse-9';
        $_GET = $_POST = [];
        unset($_SERVER['HTTP_AUTHORIZATION'], $_SERVER['CONTENT_TYPE']);
        $_SERVER['REMOTE_ADDR'] = '10.0.0.' . random_int(1, 250);
    }

    protected function tearDown(): void
    {
        ApiResponse::$testing = false;
    }

    /** Run an action; return [status, body]. ApiExceptions are rendered like the router does. */
    private function call(callable $action): array
    {
        try {
            try {
                $action();
            } catch (ApiException $e) {
                ApiResponse::fromException($e);
            }
        } catch (ApiResponseSent $e) {
        }
        $this->assertNotNull(ApiResponse::$captured, 'The action produced a response.');
        return [ApiResponse::$captured['status'], ApiResponse::$captured['body']];
    }

    public function testInternshipsEnvelopeFiltersAndPagination(): void
    {
        $_GET = ['per_page' => '2', 'page' => '1'];
        [$status, $body] = $this->call(fn () => (new CatalogApiController())->internships());
        $this->assertSame(200, $status);
        $this->assertTrue($body['success']);
        $this->assertCount(2, $body['data']);
        $this->assertSame(['current_page', 'per_page', 'total_records', 'total_pages'], array_keys($body['meta']));
        $this->assertSame(count(require dirname(__DIR__, 3) . '/backend/Data/internships.php'), $body['meta']['total_records']);

        $_GET = ['q' => 'python'];
        [, $body] = $this->call(fn () => (new CatalogApiController())->internships());
        foreach ($body['data'] as $item) {
            $this->assertStringContainsStringIgnoringCase('python', $item['title'] . ' ' . $item['description'] . ' ' . implode(' ', $item['tags']));
        }
    }

    public function testInternshipNotFound(): void
    {
        [$status, $body] = $this->call(fn () => (new CatalogApiController())->internship(['id' => 'does-not-exist']));
        $this->assertSame(404, $status);
        $this->assertFalse($body['success']);
        $this->assertSame('not_found', $body['error']);
    }

    public function testLoginValidationAndSuccess(): void
    {
        $_POST = ['email' => 'nope', 'password' => ''];
        [$status, $body] = $this->call(fn () => (new AuthApiController())->login());
        $this->assertSame(422, $status);
        $this->assertArrayHasKey('email', $body['errors']);
        $this->assertArrayHasKey('password', $body['errors']);

        $_POST = ['email' => 'candidate@internconnect.ng', 'password' => 'Correct-Horse-9'];
        [$status, $body] = $this->call(fn () => (new AuthApiController())->login());
        $this->assertSame(200, $status);
        $this->assertSame('candidate', $body['data']['user']['role']);
        $this->assertNotEmpty($body['data']['access_token']);
    }

    public function testRolesAreEnforced(): void
    {
        [$status] = $this->call(fn () => (new AccountApiController())->candidateProfile());
        $this->assertSame(401, $status, 'No token: 401.');

        $pair = ApiAuth::issue(ApiAuth::verifyCredentials('employer@internconnect.ng', 'Correct-Horse-9'));
        $_SERVER['HTTP_AUTHORIZATION'] = 'Bearer ' . $pair['access_token'];
        [$status, $body] = $this->call(fn () => (new AccountApiController())->candidateProfile());
        $this->assertSame(403, $status, 'Employer cannot read candidate endpoints.');
        $this->assertSame('forbidden', $body['error']);
    }

    public function testSpecDocumentsEveryApiRouteAndNothingElse(): void
    {
        $router = new Router();
        require dirname(__DIR__, 3) . '/backend/routes.php';
        $routes = [];
        foreach ($router->getRegisteredRoutes() as $method => $list) {
            foreach (array_keys($list) as $path) {
                if (str_starts_with($path, '/api/v1/')) {
                    $routes[] = strtolower($method) . ' ' . substr($path, 7);
                }
            }
        }
        $spec = OpenApiSpec::build('http://localhost/InternConnect');
        $documented = [];
        foreach ($spec['paths'] as $path => $ops) {
            foreach (array_keys($ops) as $method) {
                $documented[] = $method . ' ' . $path;
            }
        }
        sort($routes);
        sort($documented);
        $this->assertSame($routes, $documented, 'OpenAPI paths match backend/routes.php exactly.');
        $this->assertSame('3.0.3', $spec['openapi']);
    }

    public function testSpecReferencesResolve(): void
    {
        $spec = OpenApiSpec::build('http://localhost/InternConnect');
        $json = json_encode($spec);
        $this->assertNotFalse($json);
        preg_match_all('#"\$ref":"\#\\\\/components\\\\/(schemas|responses)\\\\/([A-Za-z0-9]+)"#', $json, $m, PREG_SET_ORDER);
        $this->assertNotEmpty($m);
        foreach ($m as [, $kind, $name]) {
            $this->assertArrayHasKey($name, $spec['components'][$kind], "#/components/{$kind}/{$name} exists");
        }
        foreach ($spec['paths'] as $path => $ops) {
            foreach ($ops as $method => $op) {
                $this->assertNotEmpty($op['summary'], "{$method} {$path} has a summary");
                $this->assertArrayHasKey('200', $op['responses'], "{$method} {$path} documents success");
                preg_match_all('/\{([a-z_]+)\}/', $path, $params);
                foreach ($params[1] as $p) {
                    $names = array_column($op['parameters'] ?? [], 'name');
                    $this->assertContains($p, $names, "{$method} {$path} documents path parameter {$p}");
                }
            }
        }
    }
}
