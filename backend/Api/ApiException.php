<?php

declare(strict_types=1);

namespace App\Api;

use RuntimeException;

/**
 * Thrown inside API controllers; rendered as the standard error envelope by the router.
 */
class ApiException extends RuntimeException
{
    /** @param array<string, string[]> $errors field => messages */
    public function __construct(
        string $message,
        public readonly int $status = 400,
        public readonly array $errors = [],
        public readonly string $errorCode = '',
        public readonly array $headers = [],
    ) {
        parent::__construct($message, $status);
    }
}
