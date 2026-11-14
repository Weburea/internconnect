<?php

declare(strict_types=1);

namespace App\Core;

/**
 * Loads .env into $_ENV/$_SERVER for scripts that do not go through public/index.php (bin/cron.php).
 * Same rules as the front controller: KEY=value, # comments, optional surrounding quotes.
 */
final class Env
{
    public static function load(string $root): void
    {
        $path = is_file($root . '/.env') ? $root . '/.env' : null;
        if (!$path) {
            return;
        }
        foreach (file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
            $line = trim($line);
            if ($line === '' || str_starts_with($line, '#') || !str_contains($line, '=')) {
                continue;
            }
            [$key, $val] = array_map('trim', explode('=', $line, 2));
            if (strlen($val) >= 2 && ($val[0] === '"' || $val[0] === "'") && str_ends_with($val, $val[0])) {
                $val = substr($val, 1, -1);
            }
            $_ENV[$key] = $val;
            $_SERVER[$key] = $val;
        }
    }

    public static function get(string $key, string $default = ''): string
    {
        $v = $_ENV[$key] ?? $_SERVER[$key] ?? getenv($key);
        return ($v === false || $v === null || $v === '') ? $default : (string)$v;
    }
}
