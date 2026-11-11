<?php
declare(strict_types=1);

/**
 * PHPUnit & Test Suite Bootstrap
 * Initializes autoloading, mock environment, and helpers.
 */

if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
    require_once __DIR__ . '/../vendor/autoload.php';
}

require_once __DIR__ . '/../backend/Core/helpers.php';

// Set environment for testing
$_ENV['APP_ENV'] = 'testing';
$_SERVER['HTTP_HOST'] = 'localhost';
$_SERVER['SERVER_PORT'] = '80';
$_SERVER['REQUEST_SCHEME'] = 'http';
$_SERVER['SCRIPT_NAME'] = '/InternConnect/index.php';

