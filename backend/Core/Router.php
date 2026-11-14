<?php

declare(strict_types=1);

namespace App\Core;

use App\Api\ApiException;
use App\Api\ApiResponse;
use App\Api\ApiResponseSent;
use App\Core\Security;
use Throwable;

class Router
{
    private array $routes = [];

    public function get(string $path, array $callback): void
    {
        $this->routes['GET'][$path] = $callback;
    }

    public function post(string $path, array $callback): void
    {
        $this->routes['POST'][$path] = $callback;
    }

    /** All registered routes, ['GET' => [path => callback], 'POST' => [...]] (route audit, API docs tests). */
    public function getRegisteredRoutes(): array
    {
        return $this->routes;
    }

    /** Request path relative to the site base (also used by Security). */
    public static function currentPath(): string
    {
        $path = (string)parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);

        // Remove base path if run from subdirectory in dev environments
        $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
        $basePath = str_replace(['/public/index.php', '/index.php'], '', $scriptName);

        if ($basePath !== '' && str_starts_with($path, $basePath)) {
            $path = substr($path, strlen($basePath));
            if ($path === '') {
                $path = '/';
            }
        }

        // Support legacy URLs containing /public
        if (str_starts_with($path, '/public')) {
            $path = substr($path, 7);
            if ($path === '') {
                $path = '/';
            }
        }

        // Normalize path
        if ($path !== '/' && str_ends_with($path, '/')) {
            $path = rtrim($path, '/');
        }
        return $path;
    }

    public function resolve(): void
    {
        $method = strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
        $path = self::currentPath();
        $isApi = str_starts_with($path, '/api/');

        if ($isApi) {
            Security::apiCors($method);
        } else {
            // CSRF for every state-changing web request (docs/51-api-and-security.md).
            Security::verifyCsrf($method, $path);
        }

        $match = $this->match($method === 'HEAD' ? 'GET' : $method, $path);
        if ($match) {
            $this->execute($match[0], $match[1], $isApi);
            return;
        }

        if ($isApi) {
            $allowed = array_values(array_filter(['GET', 'POST'], fn ($m) => $this->match($m, $path) !== null));
            if ($allowed) {
                ApiResponse::error(405, 'Use ' . implode(' or ', $allowed) . ' for this endpoint.', 'method_not_allowed', [], ['Allow' => implode(', ', $allowed)]);
            }
            ApiResponse::error(404, 'This API endpoint does not exist. See the API reference at /admin/api-docs.', 'not_found');
        }

        // Route not found
        http_response_code(404);
        echo "404 - Not Found";
    }

    /** @return array{0: array, 1: array}|null callback + params */
    private function match(string $method, string $path): ?array
    {
        if (isset($this->routes[$method][$path])) {
            return [$this->routes[$method][$path], []];
        }

        // Dynamic parameter matching
        foreach ($this->routes[$method] ?? [] as $routePath => $callback) {
            $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?P<$1>[a-zA-Z0-9_-]+)', $routePath);
            $pattern = '#^' . $pattern . '$#';

            if (preg_match($pattern, $path, $matches)) {
                return [$callback, array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY)];
            }
        }
        return null;
    }

    private function execute(array $callback, array $params = [], bool $isApi = false): void
    {
        [$controllerClass, $method] = $callback;

        if (!$isApi) {
            if (class_exists($controllerClass)) {
                $controller = new $controllerClass();
                if (method_exists($controller, $method)) {
                    call_user_func_array([$controller, $method], [$params]);
                    return;
                }
            }
            http_response_code(500);
            echo "500 - Internal Server Error (Invalid controller action)";
            return;
        }

        // API: every outcome is a JSON envelope, never an HTML error page.
        try {
            if (!class_exists($controllerClass) || !method_exists($controllerClass, $method)) {
                throw new ApiException('Endpoint is not available.', 500, [], 'server_error');
            }
            $controller = new $controllerClass();
            call_user_func_array([$controller, $method], [$params]);
            ApiResponse::error(500, 'The endpoint returned no response.', 'server_error');
        } catch (ApiResponseSent $e) {
            throw $e; // tests capture responses
        } catch (ApiException $e) {
            ApiResponse::fromException($e);
        } catch (Throwable $e) {
            error_log('API ' . $controllerClass . '::' . $method . ' - ' . $e->getMessage() . ' @ ' . $e->getFile() . ':' . $e->getLine());
            ApiResponse::error(500, 'Something went wrong on our side. Please try again.', 'server_error');
        }
    }
}
