<?php

declare(strict_types=1);

if (function_exists('opcache_reset')) {
    @opcache_reset();
}

require_once dirname(__DIR__) . '/vendor/autoload.php';
require_once dirname(__DIR__) . '/backend/Core/helpers.php';

use App\Core\Router;

// Simple environment configuration loader
if (file_exists(dirname(__DIR__) . '/.env')) {
    $lines = file(dirname(__DIR__) . '/.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '' || str_starts_with($line, '#')) {
            continue;
        }
        
        $parts = explode('=', $line, 2);
        if (count($parts) === 2) {
            $key = trim($parts[0]);
            $val = trim($parts[1]);
            
            // Remove enclosing quotes if any
            if (str_starts_with($val, '"') && str_ends_with($val, '"')) {
                $val = substr($val, 1, -1);
            } elseif (str_starts_with($val, "'") && str_ends_with($val, "'")) {
                $val = substr($val, 1, -1);
            }
            
            $_ENV[$key] = $val;
            $_SERVER[$key] = $val;
        }
    }
}

// Instantiate router and register defined routes
$router = new Router();
require_once dirname(__DIR__) . '/backend/routes.php';

// Resolve incoming request
$router->resolve();
