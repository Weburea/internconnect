<?php

declare(strict_types=1);

namespace App\Services;

/**
 * Minimal Google Gemini client (generateContent, JSON output).
 *
 * Reads GEMINI_API_KEY, GEMINI_MODEL, GEMINI_FALLBACK_MODEL, AI_REQUEST_TIMEOUT_SECONDS,
 * AI_MAX_OUTPUT_TOKENS and AI_TEMPERATURE from .env. Tries the primary model, then the
 * fallback model, and returns null on any failure so callers can use a rule-based answer.
 * The key is sent in a header, never in a URL or to the browser.
 */
class GeminiClient
{
    private const BASE = 'https://generativelanguage.googleapis.com/v1beta/models/';

    public static function isConfigured(): bool
    {
        return trim((string)($_ENV['GEMINI_API_KEY'] ?? '')) !== ''
            && strtolower(trim((string)($_ENV['AI_PROVIDER'] ?? 'gemini'), "\"' ")) === 'gemini';
    }

    /**
     * Ask Gemini for a JSON object. Returns the decoded array or null.
     */
    public static function json(string $prompt, int $timeoutSeconds = 0): ?array
    {
        if (!self::isConfigured()) {
            return null;
        }

        // Configured models first, then a lightweight model that stays available when the
        // main ones are overloaded or retired (a 404 fails fast; a hang is capped by the timeout).
        $models = array_values(array_unique(array_filter([
            trim((string)($_ENV['GEMINI_MODEL'] ?? 'gemini-flash-latest'), "\"' "),
            trim((string)($_ENV['GEMINI_FALLBACK_MODEL'] ?? ''), "\"' "),
            'gemini-flash-lite-latest',
        ])));
        $timeout = $timeoutSeconds > 0 ? $timeoutSeconds : max(5, min(30, (int)($_ENV['AI_REQUEST_TIMEOUT_SECONDS'] ?? 25)));

        $payload = [
            'contents'         => [['role' => 'user', 'parts' => [['text' => $prompt]]]],
            'generationConfig' => [
                'temperature'      => (float)($_ENV['AI_TEMPERATURE'] ?? 0.2),
                'maxOutputTokens'  => (int)($_ENV['AI_MAX_OUTPUT_TOKENS'] ?? 2048),
                'responseMimeType' => 'application/json',
            ],
        ];

        foreach ($models as $model) {
            $ch = curl_init(self::BASE . rawurlencode($model) . ':generateContent');
            curl_setopt_array($ch, [
                CURLOPT_POST           => true,
                CURLOPT_HTTPHEADER     => ['Content-Type: application/json', 'x-goog-api-key: ' . trim((string)$_ENV['GEMINI_API_KEY'], "\"' ")],
                CURLOPT_POSTFIELDS     => json_encode($payload),
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT        => $timeout,
            ]);
            $raw = curl_exec($ch);
            $status = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($raw === false || $status !== 200) {
                error_log('Gemini ' . $model . ' failed with HTTP ' . $status);
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
}
