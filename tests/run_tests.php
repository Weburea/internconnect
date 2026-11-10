<?php
declare(strict_types=1);

/**
 * InternConnect Test Runner
 * Executes unit and feature test suites with clean TAP output.
 */

require_once __DIR__ . '/bootstrap.php';
require_once __DIR__ . '/TestCase.php';
require_once __DIR__ . '/Unit/AdminControllerTest.php';
require_once __DIR__ . '/Unit/AiFeaturesTest.php';
require_once __DIR__ . '/Unit/CommunicationFeaturesTest.php';
require_once __DIR__ . '/Unit/EnterpriseFeaturesTest.php';

$testClasses = [
    \Tests\Unit\AdminControllerTest::class,
    \Tests\Unit\AiFeaturesTest::class,
    \Tests\Unit\CommunicationFeaturesTest::class,
    \Tests\Unit\EnterpriseFeaturesTest::class,
];

echo "========================================================\n";
echo " InternConnect Test Suite Execution\n";
echo " PHP Version: " . PHP_VERSION . "\n";
echo " Environment: " . ($_ENV['APP_ENV'] ?? 'local') . "\n";
echo "========================================================\n\n";

$passed = 0;
$failed = 0;

foreach ($testClasses as $class) {
    $instance = new $class();
    $methods = get_class_methods($instance);
    
    foreach ($methods as $method) {
        if (str_starts_with($method, 'test')) {
            try {
                $instance->$method();
                echo "[PASS] {$class}::{$method}\n";
                $passed++;
            } catch (\Throwable $e) {
                echo "[FAIL] {$class}::{$method}\n";
                echo "       Error: " . $e->getMessage() . "\n";
                echo "       File:  " . $e->getFile() . ":" . $e->getLine() . "\n";
                $failed++;
            }
        }
    }
}

echo "\n--------------------------------------------------------\n";
echo "Results: {$passed} passed, {$failed} failed (" . ($passed + $failed) . " total)\n";
echo "Status: " . ($failed === 0 ? "ALL TESTS PASSED [OK]\n" : "FAILURES DETECTED\n");
echo "========================================================\n";

exit($failed === 0 ? 0 : 1);

