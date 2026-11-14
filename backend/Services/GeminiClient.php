<?php

declare(strict_types=1);

namespace App\Services;

/**
 * Minimal Google Gemini client (generateContent, JSON output).
 *
 * Reads GEMINI_API_KEY, GEMINI_MODEL, GEMINI_FALLBACK_MODEL, AI_REQUEST_TIMEOUT_SECONDS,
 * AI_MAX_OUTPUT_TOKENS and AI_TEMPERATURE from .env. Tries the primary model, then the
 * fallback model, then gemini-flash-lite-latest, and returns null on any failure so callers
 * can use a rule-based answer. The key is sent in a header, never in a URL or to the browser.
 *
 * A model that is overloaded (503/429), retired (404) or times out is skipped for a few
 * minutes (storage/cache/gemini-models.json), so one slow model does not slow down every call.
 * Files (PDF, images) can be attached and are sent inline; Gemini reads them directly.
 */
class GeminiClient
{
    private const BASE = 'https://generativelanguage.googleapis.com/v1beta/models/';
    private const COOLDOWN_SECONDS = 300;      // 429: quota reached
    private const BUSY_SECONDS = 60;           // 503/500/504/timeout: usually clears within a minute
    private const RETIRED_SECONDS = 86400;     // 404: model not available for this key
    private const MAX_INLINE_BYTES = 15728640; // 15MB inline request limit (Gemini allows ~20MB)

    public static function isConfigured(): bool
    {
        return trim((string)($_ENV['GEMINI_API_KEY'] ?? '')) !== ''
            && strtolower(trim((string)($_ENV['AI_PROVIDER'] ?? 'gemini'), "\"' ")) === 'gemini';
    }

    /**
     * Ask Gemini for a JSON object. Returns the decoded array or null.
     *
     * @param array $files [['path' => string, 'mime' => 'application/pdf'|'image/png'|...]]
     * @param bool  $firstModelOnly Chat: try only the first available model (full timeout, one retry on overload)
     *                              and let the caller fall back, instead of waiting on every model in turn.
     */
    public static function json(string $prompt, int $timeoutSeconds = 0, array $files = [], int $maxTokens = 0, bool $firstModelOnly = false): ?array
    {
        if (!self::isConfigured()) {
            return null;
        }

        $parts = [];
        foreach ($files as $file) {
            $path = (string)($file['path'] ?? '');
            $mime = (string)($file['mime'] ?? '');
            if ($path === '' || $mime === '' || !is_file($path) || filesize($path) > self::MAX_INLINE_BYTES) {
                continue;
            }
            $parts[] = ['inline_data' => ['mime_type' => $mime, 'data' => base64_encode((string)file_get_contents($path))]];
        }
        $parts[] = ['text' => $prompt];

        $timeout = $timeoutSeconds > 0 ? $timeoutSeconds : max(5, min(30, (int)($_ENV['AI_REQUEST_TIMEOUT_SECONDS'] ?? 25)));
        $payload = [
            'contents'         => [['role' => 'user', 'parts' => $parts]],
            'generationConfig' => [
                'temperature'      => (float)($_ENV['AI_TEMPERATURE'] ?? 0.2),
                'maxOutputTokens'  => $maxTokens > 0 ? $maxTokens : (int)($_ENV['AI_MAX_OUTPUT_TOKENS'] ?? 2048),
                'responseMimeType' => 'application/json',
            ],
        ];

        $models = self::models();
        if ($firstModelOnly) {
            $models = array_slice($models, 0, 1);
        }
        foreach ($models as $index => $model) {
            // Earlier models get a shorter limit so one overloaded model cannot stall the page.
            $limit = $index < count($models) - 1 ? min($timeout, 12) : $timeout;
            [$raw, $status] = self::post($model, $payload, $limit);
            // The last model gets one quick retry on a temporary overload (503/429).
            if (in_array($status, [429, 503], true) && $index === count($models) - 1) {
                usleep(1200000);
                [$raw, $status] = self::post($model, $payload, $timeout);
            }

            if ($raw === false || $status !== 200) {
                error_log('Gemini ' . $model . ' failed with HTTP ' . $status);
                if ($status === 404) {
                    self::coolDown($model, self::RETIRED_SECONDS);
                } elseif ($status === 429) {
                    self::coolDown($model, self::COOLDOWN_SECONDS); // quota reached for this key/model
                } elseif ($raw === false || in_array($status, [0, 500, 503, 504], true)) {
                    self::coolDown($model, self::BUSY_SECONDS); // temporary overload or timeout
                }
                continue;
            }
            $body = json_decode((string)$raw, true) ?: [];
            $text = (string)($body['candidates'][0]['content']['parts'][0]['text'] ?? '');
            $text = trim(preg_replace('/^```(?:json)?|```$/m', '', $text) ?? $text);
            $decoded = json_decode($text, true);
            if (is_array($decoded)) {
                return $decoded;
            }
        }
        return null;
    }

    /** @return array{0: string|false, 1: int} raw body and HTTP status */
    private static function post(string $model, array $payload, int $timeout): array
    {
        $ch = curl_init(self::BASE . rawurlencode($model) . ':generateContent');
        curl_setopt_array($ch, [
            CURLOPT_POST           => true,
            CURLOPT_HTTPHEADER     => ['Content-Type: application/json', 'x-goog-api-key: ' . trim((string)$_ENV['GEMINI_API_KEY'], "\"' ")],
            CURLOPT_POSTFIELDS     => json_encode($payload),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CONNECTTIMEOUT => 8,
            CURLOPT_TIMEOUT        => $timeout,
        ]);
        $raw = curl_exec($ch);
        $status = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        return [$raw, $status];
    }

    /**
     * Models to try, in order, skipping any that recently failed. If every model is cooling
     * down, the lightweight model is still tried so AI never switches off completely.
     */
    public static function models(): array
    {
        $all = array_values(array_unique(array_filter([
            trim((string)($_ENV['GEMINI_MODEL'] ?? 'gemini-flash-latest'), "\"' "),
            trim((string)($_ENV['GEMINI_FALLBACK_MODEL'] ?? ''), "\"' "),
            'gemini-flash-lite-latest',
        ])));
        $state = self::state();
        $now = time();
        $ready = array_values(array_filter($all, static fn ($m) => (int)($state[$m] ?? 0) <= $now));
        return $ready ?: ['gemini-flash-lite-latest'];
    }

    private static function stateFile(): string
    {
        return dirname(__DIR__, 2) . '/storage/cache/gemini-models.json';
    }

    private static function state(): array
    {
        $file = self::stateFile();
        if (!is_file($file)) {
            return [];
        }
        $data = json_decode((string)@file_get_contents($file), true);
        return is_array($data) ? $data : [];
    }

    private static function coolDown(string $model, int $seconds): void
    {
        $file = self::stateFile();
        if (!is_dir(dirname($file))) {
            @mkdir(dirname($file), 0775, true);
        }
        $state = self::state();
        $state[$model] = time() + $seconds;
        @file_put_contents($file, json_encode($state), LOCK_EX);
    }
}
