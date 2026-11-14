<?php

declare(strict_types=1);

namespace App\Api;

/** Internal signal thrown by ApiResponse::send() only when ApiResponse::$testing is on. */
final class ApiResponseSent extends \RuntimeException
{
}
