<?php

declare(strict_types=1);

namespace Tests\PHPUnit\Feature;

use PHPUnit\Framework\TestCase;

/**
 * Base for HTTP feature tests against the running site (TEST_BASE_URL, default
 * http://localhost/InternConnect). Tests are skipped when the site does not answer.
 * A cookie jar keeps one browser-like session per test class.
 */
abstract class HttpTestCase extends TestCase
{
    protected static string $base = '';
    protected static string $jar = '';
    private static ?bool $up = null;

    public static function setUpBeforeClass(): void
    {
        static::$base = rtrim((string)(getenv('TEST_BASE_URL') ?: ($_ENV['TEST_BASE_URL'] ?? 'http://localhost/InternConnect')), '/');
        static::$jar = tempnam(sys_get_temp_dir(), 'icjar');
    }

    public static function tearDownAfterClass(): void
    {
        @unlink(static::$jar);
    }

    protected function setUp(): void
    {
        if (self::$up === null) {
            $ch = curl_init(static::$base . '/api/v1/health');
            curl_setopt_array($ch, [CURLOPT_RETURNTRANSFER => true, CURLOPT_TIMEOUT => 5, CURLOPT_CONNECTTIMEOUT => 3]);
            curl_exec($ch);
            self::$up = curl_getinfo($ch, CURLINFO_HTTP_CODE) === 200;
            curl_close($ch);
        }
        if (!self::$up) {
            $this->markTestSkipped('The site is not running at ' . static::$base . ' (start Apache or set TEST_BASE_URL).');
        }
    }

    /** @return array{status:int, headers:array<string,string>, body:string, json:?array} */
    protected function request(string $method, string $path, array|string|null $body = null, array $headers = [], bool $withCookies = true): array
    {
        $ch = curl_init(static::$base . $path);
        $responseHeaders = [];
        $opts = [
            CURLOPT_CUSTOMREQUEST  => $method,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => false,
            CURLOPT_TIMEOUT        => 60,
            CURLOPT_HTTPHEADER     => $headers,
            CURLOPT_HEADERFUNCTION => static function ($ch, $line) use (&$responseHeaders) {
                $parts = explode(':', $line, 2);
                if (count($parts) === 2) {
                    $responseHeaders[strtolower(trim($parts[0]))] = trim($parts[1]);
                }
                return strlen($line);
            },
        ];
        if ($withCookies) {
            $opts[CURLOPT_COOKIEJAR] = static::$jar;
            $opts[CURLOPT_COOKIEFILE] = static::$jar;
        }
        if ($body !== null) {
            $opts[CURLOPT_POSTFIELDS] = is_array($body) ? http_build_query($body) : $body;
        }
        curl_setopt_array($ch, $opts);
        $raw = (string)curl_exec($ch);
        $status = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        return ['status' => $status, 'headers' => $responseHeaders, 'body' => $raw, 'json' => json_decode($raw, true)];
    }

    /** CSRF token from a page's <meta name="csrf-token">. */
    protected function csrfFrom(string $path): string
    {
        $page = $this->request('GET', $path);
        preg_match('/<meta name="csrf-token" content="([a-f0-9]{64})"/', $page['body'], $m);
        return $m[1] ?? '';
    }

    /** API_DEMO_PASSWORD from the project .env ('' when not set). */
    protected static function demoPassword(): string
    {
        $env = dirname(__DIR__, 3) . '/.env';
        foreach (is_file($env) ? file($env, FILE_IGNORE_NEW_LINES) : [] as $line) {
            if (preg_match('/^API_DEMO_PASSWORD=["\']?([^"\']*)["\']?\s*$/', trim($line), $m)) {
                return $m[1];
            }
        }
        return '';
    }
}
