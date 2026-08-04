<?php

declare(strict_types=1);

namespace App\Core;

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

    public function resolve(): void
    {
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);

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

        // Exact match
        if (isset($this->routes[$method][$path])) {
            $this->execute($this->routes[$method][$path]);
            return;
        }

        // Dynamic parameter matching
        foreach ($this->routes[$method] ?? [] as $routePath => $callback) {
            $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?P<$1>[a-zA-Z0-9_-]+)', $routePath);
            $pattern = '#^' . $pattern . '$#';

            if (preg_match($pattern, $path, $matches)) {
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                $this->execute($callback, $params);
                return;
            }
        }

        // Route not found
        http_response_code(404);
        echo "404 - Not Found";
    }

    private function execute(array $callback, array $params = []): void
    {
        [$controllerClass, $method] = $callback;

        if (class_exists($controllerClass)) {
            $controller = new $controllerClass();
            if (method_exists($controller, $method)) {
                call_user_func_array([$controller, $method], [$params]);
                return;
            }
        }

        http_response_code(500);
        echo "500 - Internal Server Error (Invalid controller action)";
    }
}
