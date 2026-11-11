<?php
declare(strict_types=1);

namespace Tests;

/**
 * Base TestCase for InternConnect Tests
 */
class TestCase
{
    protected function setUp(): void
    {
        // Reset session state
        $_SESSION = [];
    }

    protected function tearDown(): void
    {
        $_SESSION = [];
    }

    protected function assert(bool $condition, string $message = 'Assertion failed'): void
    {
        if (!$condition) {
            throw new \Exception($message);
        }
    }

    protected function assertEquals($expected, $actual, string $message = ''): void
    {
        if ($expected !== $actual) {
            $msg = $message ?: "Expected " . var_export($expected, true) . ", got " . var_export($actual, true);
            throw new \Exception($msg);
        }
    }

    protected function assertTrue($actual, string $message = ''): void
    {
        if ($actual !== true) {
            throw new \Exception($message ?: "Expected true, got " . var_export($actual, true));
        }
    }

    protected function assertFalse($actual, string $message = ''): void
    {
        if ($actual !== false) {
            throw new \Exception($message ?: "Expected false, got " . var_export($actual, true));
        }
    }

    protected function assertStringContains(string $needle, string $haystack, string $message = ''): void
    {
        if (!str_contains($haystack, $needle)) {
            throw new \Exception($message ?: "Failed asserting that '$haystack' contains '$needle'.");
        }
    }

    protected function assertStringNotContains(string $needle, string $haystack, string $message = ''): void
    {
        if (str_contains($haystack, $needle)) {
            throw new \Exception($message ?: "Failed asserting that '$haystack' does not contain '$needle'.");
        }
    }

    protected function assertNotEmpty($actual, string $message = ''): void
    {
        if (empty($actual)) {
            throw new \Exception($message ?: "Expected non-empty value, got empty.");
        }
    }

    protected function assertIsArray($actual, string $message = ''): void
    {
        if (!is_array($actual)) {
            throw new \Exception($message ?: "Expected array, got " . gettype($actual));
        }
    }

    protected function assertArrayHasKey(string|int $key, array $array, string $message = ''): void
    {
        if (!array_key_exists($key, $array)) {
            throw new \Exception($message ?: "Expected array to have key '{$key}'");
        }
    }
}

