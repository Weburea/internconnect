<?php

declare(strict_types=1);

namespace Tests\PHPUnit\Unit;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * Runs every test of the original suites (tests/Unit/*Test.php, Tests\TestCase) inside PHPUnit,
 * one PHPUnit test per method, so `composer test` reports all of them with names.
 * The same files still run with `php tests/run_tests.php`.
 */
final class LegacySuiteTest extends TestCase
{
    /** @return iterable<string, array{class-string, string}> */
    public static function legacyTests(): iterable
    {
        require_once dirname(__DIR__, 2) . '/TestCase.php';
        foreach (glob(dirname(__DIR__, 2) . '/Unit/*Test.php') ?: [] as $file) {
            require_once $file;
            $class = 'Tests\\Unit\\' . basename($file, '.php');
            if (!class_exists($class)) {
                continue;
            }
            foreach (get_class_methods($class) as $method) {
                if (str_starts_with($method, 'test')) {
                    yield basename($file, '.php') . '::' . $method => [$class, $method];
                }
            }
        }
    }

    #[DataProvider('legacyTests')]
    public function testLegacy(string $class, string $method): void
    {
        $_SESSION = [];
        (new $class())->$method();
        // Legacy tests throw on failure; reaching here means every assertion held.
        $this->addToAssertionCount(1);
    }
}
