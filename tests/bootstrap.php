<?php
declare(strict_types=1);

/**
 * Test bootstrap (PHPUnit `vendor/bin/phpunit` and the legacy runner `tests/run_tests.php`).
 *
 * - Autoloading (Composer: App\ => backend/, Tests\ => tests/), helpers, a fake HTTP context.
 * - Database isolation: every run works on a fresh copy of database/database.sqlite in
 *   storage/testing/, so tests never write into the development database.
 * - No .env is loaded: Gemini, Daily and Paystack keys are absent, so every external call
 *   takes its offline fallback and runs are deterministic.
 * Spec: docs/45-testing-api-architecture-and-audit-plan.md section 1
 */

$root = dirname(__DIR__);

if (file_exists($root . '/vendor/autoload.php')) {
    require_once $root . '/vendor/autoload.php';
}

require_once $root . '/backend/Core/helpers.php';

// Set environment for testing
$_ENV['APP_ENV'] = 'testing';
$_SERVER['HTTP_HOST'] = 'localhost';
$_SERVER['SERVER_PORT'] = '80';
$_SERVER['REQUEST_SCHEME'] = 'http';
$_SERVER['SCRIPT_NAME'] = '/InternConnect/index.php';

// Isolated database copy (relative to the project root, as Database::getConnection() expects).
if (!defined('IC_TEST_DB_READY')) {
    define('IC_TEST_DB_READY', true);
    $testDir = $root . '/storage/testing';
    if (!is_dir($testDir)) {
        @mkdir($testDir, 0775, true);
    }
    $testDb = $testDir . '/test-' . getmypid() . '.sqlite';
    $source = $root . '/database/database.sqlite';
    if (is_file($source)) {
        copy($source, $testDb);
    } else {
        touch($testDb);
    }
    $_ENV['DB_CONNECTION'] = 'sqlite';
    $_ENV['DB_DATABASE'] = 'storage/testing/' . basename($testDb);
    register_shutdown_function(static function () use ($testDb) {
        // Close the shared PDO handle before deleting the file (Windows keeps it locked otherwise).
        try {
            $ref = new ReflectionProperty(\App\Core\Database::class, 'instance');
            $ref->setAccessible(true);
            $ref->setValue(null, null);
        } catch (Throwable $e) {
        }
        gc_collect_cycles();
        @unlink($testDb);
    });
}
