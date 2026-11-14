<?php

declare(strict_types=1);

namespace App\Controllers\Api\V1;

use App\Api\ApiException;
use App\Api\ApiResponse;
use App\Api\ApiResponseSent;
use App\Services\PartnerService;
use App\Services\RateLimiter;

/**
 * Training partner API (Jigide and others). Developer guide: docs/58-partner-api.md
 *
 * Every request:  X-Partner-Key: icp_...      (API key from InternConnect admin)
 *                 X-Timestamp:   unix seconds  (within 5 minutes of our clock)
 *                 X-Signature:   hex(HMAC-SHA256(signing secret, timestamp + "." + raw body))
 * Every POST:     Idempotency-Key: unique per logical request (retries reuse it)
 *
 * POST /api/v1/partners/results               final score for a student reference
 * POST /api/v1/partners/applications/status   enrolled | in_progress | withdrawn
 * GET  /api/v1/partners/students/{reference}  check a reference before enrolling the student
 */
final class PartnerApiController extends ApiController
{
    private const RATE = 60;
    private float $t0;
    private ?array $partner = null;
    private ?string $idemKey = null;
    private ?string $reference = null;
    private string $raw = '';
    /** Tests only: the raw body to use instead of php://input. */
    public static ?string $testBody = null;

    /** Authenticate the partner, check the signature and the rate limit. */
    private function boot(bool $isPost): void
    {
        $this->t0 = microtime(true);
        $this->raw = self::$testBody ?? (string)file_get_contents('php://input');
        $key = trim((string)($_SERVER['HTTP_X_PARTNER_KEY'] ?? ''));
        if ($key === '') {
            throw new ApiException('Send your API key in the X-Partner-Key header.', 401, [], 'partner_key_missing');
        }
        $partner = PartnerService::partnerForKey($key);
        if (!$partner) {
            throw new ApiException('This API key is not valid. It may have been rotated.', 401, [], 'invalid_partner_key');
        }
        $this->partner = $partner;
        $hit = RateLimiter::hit('partner:' . $partner['id'], self::RATE, 60);
        if (!$hit['allowed']) {
            throw new ApiException('Rate limit reached: ' . self::RATE . ' requests per minute.', 429, [], 'too_many_requests', ['Retry-After' => (string)$hit['retry_after']]);
        }
        $ts = trim((string)($_SERVER['HTTP_X_TIMESTAMP'] ?? ''));
        if (!preg_match('/^\d{9,11}$/', $ts) || abs(time() - (int)$ts) > PartnerService::TIMESTAMP_SKEW) {
            throw new ApiException('X-Timestamp is missing or more than 5 minutes from our clock (UTC unix seconds).', 401, [], 'stale_timestamp');
        }
        if (!PartnerService::verifySignature($partner, $ts, $this->raw, (string)($_SERVER['HTTP_X_SIGNATURE'] ?? ''))) {
            throw new ApiException('X-Signature does not match. Sign timestamp + "." + the exact raw body with your signing secret (HMAC-SHA256, hex).', 401, [], 'invalid_signature');
        }
        if ($partner['status'] !== 'active') {
            throw new ApiException('Your partner account is paused. Contact InternConnect.', 403, [], 'partner_paused');
        }
        if ($isPost) {
            $idem = trim((string)($_SERVER['HTTP_IDEMPOTENCY_KEY'] ?? ''));
            if (!preg_match('/^[A-Za-z0-9_\-:.]{8,80}$/', $idem)) {
                throw new ApiException('Send a unique Idempotency-Key header (8-80 letters, digits, - _ : .) and reuse it when you retry.', 400, [], 'idempotency_key_required');
            }
            $this->idemKey = $idem;
            $seen = PartnerService::idempotent((int)$partner['id'], $idem, $this->raw);
            if ($seen !== null) {
                if (!empty($seen['conflict'])) {
                    throw new ApiException('This Idempotency-Key was already used with a different body.', 409, [], 'idempotency_conflict');
                }
                ApiResponse::send($seen['status'], $seen['body'], ['Idempotent-Replayed' => 'true']);
            }
        }
    }

    private function body(): array
    {
        $data = json_decode($this->raw, true);
        if (!is_array($data)) {
            throw new ApiException('The request body must be a JSON object.', 400, [], 'invalid_json');
        }
        return $data;
    }

    private function reply(int $status, string $message, array $data): void
    {
        $body = ['success' => true, 'code' => $status, 'message' => $message, 'data' => $data];
        if ($this->idemKey && $this->partner) {
            PartnerService::remember((int)$this->partner['id'], $this->idemKey, $this->raw, $status, $body);
        }
        ApiResponse::send($status, $body);
    }

    /**
     * Run an endpoint and write exactly one audit row. ApiResponse::send() ends the request with exit
     * (finally blocks do not run then), so the success path is logged by a shutdown hook.
     */
    private function audited(string $path, bool $isPost, callable $fn): void
    {
        $this->t0 = microtime(true);
        $written = false;
        $write = function (int $status, ?string $error) use (&$written, $path, $isPost): void {
            if ($written) {
                return;
            }
            $written = true;
            PartnerService::audit($this->partner ? (int)$this->partner['id'] : null, $isPost ? 'POST' : 'GET', $path, $status, $error, $this->idemKey, $this->reference, (int)round((microtime(true) - $this->t0) * 1000));
        };
        if (!ApiResponse::$testing) {
            register_shutdown_function(static function () use ($write): void {
                $write((int)(http_response_code() ?: 200), null);
            });
        }
        try {
            $this->boot($isPost);
            $fn();
        } catch (ApiResponseSent $e) {
            $write((int)(ApiResponse::$captured['status'] ?? 200), null);
            throw $e;
        } catch (ApiException $e) {
            $write($e->status, $e->errorCode ?: null);
            throw $e;
        } catch (\InvalidArgumentException $e) {
            $write(422, 'validation_failed');
            throw new ApiException($e->getMessage(), 422, [], 'validation_failed');
        }
    }

    private function application(string $reference): array
    {
        $this->reference = strtoupper(trim($reference));
        $app = PartnerService::application($this->reference);
        // Unknown and other partners' references look the same: nothing to learn by guessing.
        if (!$app || (int)$app['partner_id'] !== (int)$this->partner['id']) {
            throw new ApiException('No student with this reference was sent to you by InternConnect.', 404, ['reference' => ['Unknown reference.']], 'unknown_reference');
        }
        return $app;
    }

    public function results(): void
    {
        $this->audited('/api/v1/partners/results', true, function (): void {
            $in = $this->body();
            $errors = [];
            $ref = (string)($in['reference'] ?? '');
            if (!preg_match('/^ICT[A-F0-9]{12}$/i', $ref)) {
                $errors['reference'][] = 'reference is the ic_ref value we sent you (ICT + 12 characters).';
            }
            $score = $in['score'] ?? null;
            if (!is_int($score) && !is_float($score) && !(is_string($score) && is_numeric($score))) {
                $errors['score'][] = 'score must be a number from 0 to 100.';
            } elseif ((float)$score < 0 || (float)$score > 100) {
                $errors['score'][] = 'score must be between 0 and 100.';
            }
            if (isset($in['certificate_url']) && $in['certificate_url'] !== '' && !preg_match('#^https://#i', (string)$in['certificate_url'])) {
                $errors['certificate_url'][] = 'certificate_url must be an https:// link.';
            }
            if (isset($in['completed_at']) && $in['completed_at'] !== '' && strtotime((string)$in['completed_at']) === false) {
                $errors['completed_at'][] = 'completed_at must be an ISO 8601 date-time.';
            }
            if ($errors) {
                throw new ApiException('The result has errors.', 422, $errors, 'validation_failed');
            }
            $app = $this->application($ref);
            $r = PartnerService::recordResult($this->partner, $app, (float)$score, [
                'course'          => $in['course'] ?? null,
                'certificate_id'  => $in['certificate_id'] ?? null,
                'certificate_url' => $in['certificate_url'] ?? null,
                'completed_at'    => $in['completed_at'] ?? null,
            ], 'api', 'partner:' . $this->partner['slug']);
            $messages = ['created' => 'Result recorded.', 'corrected' => 'Result corrected. The previous score is kept in the history.', 'unchanged' => 'This result was already recorded.'];
            $this->reply($r['outcome'] === 'created' ? 201 : 200, $messages[$r['outcome']], PartnerService::publicResult($r['result']) + ['outcome' => $r['outcome']]);
        });
    }

    public function applicationStatus(): void
    {
        $this->audited('/api/v1/partners/applications/status', true, function (): void {
            $in = $this->body();
            $status = (string)($in['status'] ?? '');
            if (!in_array($status, PartnerService::REPORTABLE_STATUSES, true)) {
                throw new ApiException('status must be one of: ' . implode(', ', PartnerService::REPORTABLE_STATUSES) . '. Send final scores to /partners/results.', 422, ['status' => ['Invalid status.']], 'validation_failed');
            }
            $app = $this->application((string)($in['reference'] ?? ''));
            if (in_array($app['status'], ['completed', 'failed', 'expired'], true)) {
                throw new ApiException('This application is already ' . $app['status'] . '.', 409, [], 'application_closed');
            }
            $app = PartnerService::setApplicationStatus($app, $status);
            $this->reply(200, 'Status updated.', ['reference' => $app['reference'], 'status' => $app['status'], 'updated_at' => date(DATE_ATOM, strtotime((string)$app['updated_at']))]);
        });
    }

    /** Query filters shared by the list and the stats. */
    private function filters(): array
    {
        $f = [];
        foreach (['from', 'to'] as $k) {
            $v = (string)($_GET[$k] ?? '');
            if ($v !== '' && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $v)) {
                throw new ApiException('Use YYYY-MM-DD for ' . $k . '.', 422, [$k => 'Use YYYY-MM-DD.'], 'validation_error');
            }
            $f[$k] = $v !== '' ? $v : null;
        }
        $status = (string)($_GET['status'] ?? '');
        if ($status !== '' && !in_array($status, PartnerService::APP_STATUSES, true)) {
            throw new ApiException('Unknown status.', 422, ['status' => 'One of: ' . implode(', ', PartnerService::APP_STATUSES)], 'validation_error');
        }
        $f['status'] = $status !== '' ? $status : null;
        $passed = strtolower((string)($_GET['passed'] ?? ''));
        $f['passed'] = in_array($passed, ['true', '1'], true) ? true : (in_array($passed, ['false', '0'], true) ? false : null);
        return $f;
    }

    /** GET /api/v1/partners/students: my students with their latest score (paginated). */
    public function students(): void
    {
        $this->audited('/api/v1/partners/students', false, function (): void {
            $list = PartnerService::partnerStudents((int)$this->partner['id'], $this->filters());
            ApiResponse::paginated($list, count($list) . ' student(s).', (int)($_GET['page'] ?? 1), (int)($_GET['per_page'] ?? 20));
        });
    }

    /** GET /api/v1/partners/stats: totals, pass rate and average score for my students. */
    public function stats(): void
    {
        $this->audited('/api/v1/partners/stats', false, function (): void {
            ApiResponse::ok(PartnerService::partnerStats((int)$this->partner['id'], $this->filters()), 'Statistics for your students.');
        });
    }

    public function student(array $params = []): void
    {
        $this->audited('/api/v1/partners/students/{reference}', false, function () use ($params): void {
            $app = $this->application((string)($params['reference'] ?? ''));
            ApiResponse::ok([
                'reference'  => $app['reference'],
                'status'     => $app['status'],
                'program'    => $app['program'],
                'started_at' => date(DATE_ATOM, strtotime((string)$app['created_at'])),
            ], 'Reference is valid.');
        });
    }
}
