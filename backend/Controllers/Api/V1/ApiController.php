<?php

declare(strict_types=1);

namespace App\Controllers\Api\V1;

use App\Api\ApiAuth;
use App\Api\ApiException;
use App\Services\RateLimiter;

/**
 * Base for /api/v1 controllers: JSON input, Bearer authentication, role checks, validation,
 * per-client rate limiting. Errors are thrown as ApiException and rendered by the router.
 * Spec: docs/51-api-and-security.md
 */
abstract class ApiController
{
    /** Requests per minute per signed-in account (or per IP when anonymous). */
    protected const RATE_PER_MINUTE = 120;

    private ?array $jsonCache = null;

    public function __construct()
    {
        $who = ($a = ApiAuth::fromRequest()) ? $a['role'] . ':' . $a['id'] : 'ip:' . RateLimiter::ip();
        $r = RateLimiter::hit('api:' . $who, static::RATE_PER_MINUTE, 60);
        if (!$r['allowed']) {
            throw new ApiException('Too many requests. Try again in ' . $r['retry_after'] . ' seconds.', 429, [], 'too_many_requests', ['Retry-After' => (string)$r['retry_after']]);
        }
    }

    /** JSON body (application/json) or form fields. */
    protected function input(): array
    {
        if ($this->jsonCache !== null) {
            return $this->jsonCache;
        }
        $raw = (string)file_get_contents('php://input');
        $type = strtolower((string)($_SERVER['CONTENT_TYPE'] ?? ''));
        if (str_contains($type, 'application/json')) {
            $data = json_decode($raw, true);
            if ($raw !== '' && !is_array($data)) {
                throw new ApiException('The request body is not valid JSON.', 400, [], 'invalid_json');
            }
            return $this->jsonCache = is_array($data) ? $data : [];
        }
        return $this->jsonCache = $_POST;
    }

    /** Signed-in account or 401. */
    protected function user(): array
    {
        $account = ApiAuth::fromRequest();
        if (!$account) {
            throw new ApiException('Sign in to use this endpoint. Send "Authorization: Bearer <access_token>".', 401, [], 'unauthenticated', ['WWW-Authenticate' => 'Bearer']);
        }
        return $account;
    }

    /** Signed-in account with one of $roles or 403. */
    protected function requireRole(string ...$roles): array
    {
        $account = $this->user();
        if (!in_array($account['role'], $roles, true)) {
            throw new ApiException('Your account type cannot use this endpoint.', 403, [], 'forbidden');
        }
        return $account;
    }

    /**
     * Validate input: ['field' => 'required|string|max:120|email|in:a,b|int|min:1'].
     * Returns the cleaned values; throws 422 with per-field messages.
     */
    protected function validate(array $rules, ?array $data = null): array
    {
        $data = $data ?? $this->input();
        $errors = [];
        $out = [];
        foreach ($rules as $field => $spec) {
            $value = $data[$field] ?? null;
            $parts = explode('|', $spec);
            $required = in_array('required', $parts, true);
            if ($value === null || $value === '' || (is_array($value) && !$value)) {
                if ($required) {
                    $errors[$field][] = "The {$field} field is required.";
                }
                continue;
            }
            foreach ($parts as $rule) {
                [$name, $arg] = array_pad(explode(':', $rule, 2), 2, null);
                $label = str_replace('_', ' ', $field);
                switch ($name) {
                    case 'string':
                        if (!is_string($value)) { $errors[$field][] = "The {$label} must be text."; }
                        else { $value = trim($value); }
                        break;
                    case 'int':
                        if (filter_var($value, FILTER_VALIDATE_INT) === false) { $errors[$field][] = "The {$label} must be a whole number."; }
                        else { $value = (int)$value; }
                        break;
                    case 'email':
                        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) { $errors[$field][] = "The {$label} must be a valid e-mail address."; }
                        break;
                    case 'max':
                        if (is_string($value) && mb_strlen($value) > (int)$arg) { $errors[$field][] = "The {$label} may not be longer than {$arg} characters."; }
                        if (is_int($value) && $value > (int)$arg) { $errors[$field][] = "The {$label} may not be greater than {$arg}."; }
                        break;
                    case 'min':
                        if (is_string($value) && mb_strlen($value) < (int)$arg) { $errors[$field][] = "The {$label} must be at least {$arg} characters."; }
                        if (is_int($value) && $value < (int)$arg) { $errors[$field][] = "The {$label} must be at least {$arg}."; }
                        break;
                    case 'in':
                        if (!in_array((string)$value, explode(',', (string)$arg), true)) { $errors[$field][] = "The {$label} must be one of: " . str_replace(',', ', ', (string)$arg) . '.'; }
                        break;
                }
            }
            $out[$field] = $value;
        }
        if ($errors) {
            throw new ApiException('Some fields need attention.', 422, $errors, 'validation_failed');
        }
        return $out;
    }

    protected function page(): array
    {
        return [max(1, (int)($_GET['page'] ?? 1)), max(1, min(50, (int)($_GET['per_page'] ?? 10)))];
    }

    /** Extra per-account limit for expensive endpoints (AI, uploads). */
    protected function throttle(string $name, array $account, int $perMinute): void
    {
        $r = RateLimiter::hit('api:' . $name . ':' . $account['role'] . ':' . $account['id'], $perMinute, 60);
        if (!$r['allowed']) {
            throw new ApiException('This action is limited to ' . $perMinute . ' requests per minute. Try again in ' . $r['retry_after'] . ' seconds.', 429, [], 'too_many_requests', ['Retry-After' => (string)$r['retry_after']]);
        }
    }
}
