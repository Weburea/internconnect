<?php

declare(strict_types=1);

namespace App\Api;

/**
 * Standard /api/v1 JSON envelope (docs/45 section 2.1, docs/51).
 *
 * Success: {"success": true,  "code": 200, "message": "...", "data": ..., "meta": {...}}
 * Error:   {"success": false, "code": 422, "message": "...", "error": "validation_failed", "errors": {"field": ["..."]}}
 */
final class ApiResponse
{
    /** Set by tests to capture output instead of exiting. */
    public static bool $testing = false;
    public static ?array $captured = null;

    public static function send(int $status, array $body, array $headers = []): void
    {
        if (self::$testing) {
            self::$captured = ['status' => $status, 'body' => $body, 'headers' => $headers];
            throw new ApiResponseSent();
        }
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        header('Cache-Control: no-store');
        header('X-Content-Type-Options: nosniff');
        foreach ($headers as $name => $value) {
            header($name . ': ' . $value);
        }
        echo json_encode($body, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        exit;
    }

    public static function ok(mixed $data = null, string $message = 'OK', int $status = 200, ?array $meta = null, array $headers = []): void
    {
        $body = ['success' => true, 'code' => $status, 'message' => $message, 'data' => $data];
        if ($meta !== null) {
            $body['meta'] = $meta;
        }
        self::send($status, $body, $headers);
    }

    /** Paginate a list: ?page=1&per_page=10 (max 50). */
    public static function paginated(array $items, string $message, int $page, int $perPage): void
    {
        $perPage = max(1, min(50, $perPage));
        $total = count($items);
        $pages = max(1, (int)ceil($total / $perPage));
        $page = max(1, min($pages, $page));
        self::ok(array_values(array_slice($items, ($page - 1) * $perPage, $perPage)), $message, 200, [
            'current_page'  => $page,
            'per_page'      => $perPage,
            'total_records' => $total,
            'total_pages'   => $pages,
        ]);
    }

    public static function error(int $status, string $message, string $errorCode = '', array $errors = [], array $headers = []): void
    {
        $body = ['success' => false, 'code' => $status, 'message' => $message, 'error' => $errorCode !== '' ? $errorCode : self::defaultCode($status)];
        if ($errors) {
            $body['errors'] = $errors;
        }
        self::send($status, $body, $headers);
    }

    public static function fromException(ApiException $e): void
    {
        self::error($e->status, $e->getMessage(), $e->errorCode, $e->errors, $e->headers);
    }

    private static function defaultCode(int $status): string
    {
        return [
            400 => 'bad_request', 401 => 'unauthenticated', 403 => 'forbidden', 404 => 'not_found',
            405 => 'method_not_allowed', 409 => 'conflict', 413 => 'payload_too_large', 415 => 'unsupported_media_type',
            422 => 'validation_failed', 429 => 'too_many_requests', 500 => 'server_error', 501 => 'not_implemented', 503 => 'service_unavailable',
        ][$status] ?? 'error';
    }
}

