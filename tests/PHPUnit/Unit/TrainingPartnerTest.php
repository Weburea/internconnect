<?php

declare(strict_types=1);

namespace Tests\PHPUnit\Unit;

use App\Api\ApiException;
use App\Api\ApiResponse;
use App\Api\ApiResponseSent;
use App\Controllers\Api\V1\PartnerApiController;
use App\Core\Database;
use App\Services\BillingService;
use App\Services\PartnerService;
use App\Services\PlanService;
use PHPUnit\Framework\TestCase;

/**
 * Training partners (docs/57, docs/58) — the brief's test checklist:
 * duplicate submissions, unknown student, invalid score, paid user grant, expiry,
 * plus signatures, stale timestamps, corrections and admin-editable pass marks/tiers.
 */
final class TrainingPartnerTest extends TestCase
{
    private array $partner;
    private array $keys;

    protected function setUp(): void
    {
        $_ENV['JWT_SECRET'] = str_repeat('cd', 32);
        ApiResponse::$testing = true;
        ApiResponse::$captured = null;
        PartnerApiController::$testBody = null;
        $_SERVER['REMOTE_ADDR'] = '10.1.0.' . random_int(1, 250);
        unset($_SERVER['HTTP_AUTHORIZATION']);
        $slug = 'test-' . bin2hex(random_bytes(4));
        $r = PartnerService::savePartner(['name' => 'Test Academy ' . $slug, 'slug' => $slug, 'application_url' => 'https://academy.test/apply', 'status' => 'active', 'pass_mark' => 50]);
        $this->assertTrue($r['ok'], $r['error'] ?? '');
        $this->keys = PartnerService::rotateKeys($r['id']);
        $this->partner = PartnerService::partner($r['id']);
    }

    protected function tearDown(): void
    {
        ApiResponse::$testing = false;
        PartnerApiController::$testBody = null;
        unset($_SERVER['HTTP_X_PARTNER_KEY'], $_SERVER['HTTP_X_SIGNATURE'], $_SERVER['HTTP_X_TIMESTAMP'], $_SERVER['HTTP_IDEMPOTENCY_KEY']);
    }

    private function candidate(): int
    {
        return 800000 + random_int(1, 99999);
    }

    /** Call the partner API in-process with a correctly signed request (or with $tamper applied). */
    private function api(string $method, array|string|null $body, array $params = [], ?string $idem = null, ?callable $tamper = null): array
    {
        $raw = is_array($body) ? json_encode($body) : (string)$body;
        $ts = (string)time();
        $_SERVER['HTTP_X_PARTNER_KEY'] = $this->keys['api_key'];
        $_SERVER['HTTP_X_TIMESTAMP'] = $ts;
        $_SERVER['HTTP_X_SIGNATURE'] = hash_hmac('sha256', $ts . '.' . $raw, $this->keys['signing_secret']);
        $_SERVER['HTTP_IDEMPOTENCY_KEY'] = $idem ?? ('idem-' . bin2hex(random_bytes(6)));
        if ($tamper) {
            $tamper();
        }
        PartnerApiController::$testBody = $raw;
        ApiResponse::$captured = null;
        try {
            try {
                $c = new PartnerApiController();
                match ($method) {
                    'results' => $c->results(),
                    'status'  => $c->applicationStatus(),
                    'student' => $c->student($params),
                    'students' => $c->students(),
                    'stats'   => $c->stats(),
                };
            } catch (ApiException $e) {
                ApiResponse::fromException($e);
            }
        } catch (ApiResponseSent $e) {
        }
        return [ApiResponse::$captured['status'], ApiResponse::$captured['body'], ApiResponse::$captured['headers'] ?? []];
    }

    public function testDefaultTierRulesAndPassMark(): void
    {
        $p = $this->partner;
        $this->assertSame(['passed' => true, 'plan' => 'premium', 'days' => 180], array_intersect_key(PartnerService::evaluate($p, 92), ['passed' => 1, 'plan' => 1, 'days' => 1]));
        $this->assertSame('pro', PartnerService::evaluate($p, 70)['plan']);
        $this->assertSame(30, PartnerService::evaluate($p, 55)['days']);
        $this->assertFalse(PartnerService::evaluate($p, 49)['passed']);

        // Admin raises the pass mark to 70 and stops offering Pro: own rules for this partner only.
        PartnerService::savePartner(['name' => $p['name'], 'slug' => $p['slug'], 'application_url' => $p['application_url'], 'status' => 'active', 'pass_mark' => 70], (int)$p['id']);
        $this->assertTrue(PartnerService::saveRules((int)$p['id'], [
            ['min_score' => 70, 'max_score' => 89.99, 'plan' => 'none', 'duration_days' => 0, 'is_active' => 1],
            ['min_score' => 90, 'max_score' => 100, 'plan' => 'premium', 'duration_days' => 90, 'is_active' => 1],
        ])['ok']);
        $p = PartnerService::partner((int)$p['id']);
        $this->assertFalse(PartnerService::evaluate($p, 65)['passed'], 'Below the new pass mark.');
        $this->assertSame(['passed' => true, 'plan' => null], array_intersect_key(PartnerService::evaluate($p, 75), ['passed' => 1, 'plan' => 1]), 'Passed, but no plan in this band.');
        $this->assertSame(90, PartnerService::evaluate($p, 95)['days']);
        $this->assertFalse(PartnerService::saveRules((int)$p['id'], [['min_score' => 50, 'max_score' => 80, 'plan' => 'pro', 'duration_days' => 30, 'is_active' => 1], ['min_score' => 70, 'max_score' => 100, 'plan' => 'pro', 'duration_days' => 30, 'is_active' => 1]])['ok'], 'Overlapping ranges are refused.');
        // Global defaults untouched for every other partner.
        $this->assertSame(4, count(PartnerService::rulesFor(null)));
    }

    public function testFullFlowCreatedDuplicateCorrectionAndHistory(): void
    {
        $cand = $this->candidate();
        $app = PartnerService::startApplication((int)$this->partner['id'], $cand, 'Data Analytics');
        $this->assertMatchesRegularExpression('/^ICT[A-F0-9]{12}$/', $app['reference']);
        $this->assertStringContainsString('ic_ref=' . $app['reference'], $app['handoff_url']);
        $this->assertSame($app['reference'], PartnerService::startApplication((int)$this->partner['id'], $cand)['reference'], 'An open application is reused.');

        [$s, $b] = $this->api('student', '', ['reference' => $app['reference']]);
        $this->assertSame(200, $s);
        $this->assertSame('started', $b['data']['status']);

        [$s, $b] = $this->api('status', ['reference' => $app['reference'], 'status' => 'enrolled']);
        $this->assertSame(200, $s);

        $idem = 'result-' . bin2hex(random_bytes(4));
        $body = ['reference' => $app['reference'], 'score' => 88, 'course' => 'Data Analytics', 'certificate_id' => 'CERT-1', 'certificate_url' => 'https://academy.test/c/1'];
        [$s, $b] = $this->api('results', $body, [], $idem);
        $this->assertSame(201, $s, json_encode($b));
        $this->assertSame('premium', $b['data']['plan_unlocked']);
        $this->assertSame('premium', PlanService::effectivePlan('candidate', $cand));

        // Duplicate: same Idempotency-Key -> same answer, replay header, nothing new.
        [$s2, $b2, $h2] = $this->api('results', $body, [], $idem);
        $this->assertSame([201, $b['data']['id']], [$s2, $b2['data']['id']]);
        $this->assertSame('true', $h2['Idempotent-Replayed'] ?? null);
        // Same key with a different body is a conflict.
        [$s3] = $this->api('results', ['reference' => $app['reference'], 'score' => 40], [], $idem);
        $this->assertSame(409, $s3);
        // Same result with a new key: recognised as unchanged.
        [$s4, $b4] = $this->api('results', $body);
        $this->assertSame([200, 'unchanged'], [$s4, $b4['data']['outcome']]);

        // Correction: 60 -> Pro 30 days, the Premium grant is revoked, history kept.
        [$s5, $b5] = $this->api('results', ['reference' => $app['reference'], 'score' => 60]);
        $this->assertSame([200, 'corrected', 'pro'], [$s5, $b5['data']['outcome'], $b5['data']['plan_unlocked']]);
        $this->assertSame($b['data']['id'], $b5['data']['supersedes']);
        PlanService::flush();
        $this->assertSame('pro', PlanService::effectivePlan('candidate', $cand));
        $this->assertSame('superseded', PartnerService::result($b['data']['id'])['status']);
        $this->assertCount(1, PartnerService::certifications($cand));

        // Status updates after completion are refused.
        [$s6] = $this->api('status', ['reference' => $app['reference'], 'status' => 'in_progress']);
        $this->assertSame(409, $s6);
    }

    public function testStudentListAndStatsOnlyShowThisPartner(): void
    {
        $passed = PartnerService::startApplication((int)$this->partner['id'], $this->candidate(), 'Data Analytics');
        $failed = PartnerService::startApplication((int)$this->partner['id'], $this->candidate(), 'Data Analytics');
        PartnerService::startApplication((int)$this->partner['id'], $this->candidate());
        PartnerService::recordResult($this->partner, $passed, 90);
        PartnerService::recordResult($this->partner, $failed, 30);
        $other = PartnerService::savePartner(['name' => 'Other ' . bin2hex(random_bytes(3)), 'application_url' => 'https://other.test', 'status' => 'active']);
        PartnerService::startApplication((int)$other['id'], $this->candidate());

        $_GET = [];
        [$s, $b] = $this->api('students', '');
        $this->assertSame(200, $s);
        $this->assertSame(3, $b['meta']['total_records'], 'Only this partner\'s students.');
        $this->assertArrayNotHasKey('email', $b['data'][0], 'No personal data.');
        $_GET = ['passed' => 'true'];
        [, $b] = $this->api('students', '');
        $this->assertSame([$passed['reference']], array_column($b['data'], 'reference'));
        $this->assertSame(90.0, (float)$b['data'][0]['score']);
        $_GET = ['from' => 'nope'];
        [$s] = $this->api('students', '');
        $this->assertSame(422, $s);

        $_GET = [];
        [$s, $b] = $this->api('stats', '');
        $this->assertSame(200, $s);
        $d = $b['data'];
        $this->assertSame([3, 2, 1, 1, 50.0, 60.0], [$d['students'], $d['results'], $d['passed'], $d['failed'], (float)$d['pass_rate'], (float)$d['average_score']]);
    }

    public function testUnknownStudentInvalidScoreAndValidation(): void
    {
        [$s, $b] = $this->api('results', ['reference' => 'ICT000000000000', 'score' => 80]);
        $this->assertSame([404, 'unknown_reference'], [$s, $b['error']]);

        $app = PartnerService::startApplication((int)$this->partner['id'], $this->candidate());
        foreach ([101, -1, 'abc', null] as $bad) {
            [$s, $b] = $this->api('results', ['reference' => $app['reference'], 'score' => $bad]);
            $this->assertSame(422, $s, 'score ' . json_encode($bad));
            $this->assertArrayHasKey('score', $b['errors']);
        }
        [$s, $b] = $this->api('results', ['reference' => $app['reference'], 'score' => 70, 'certificate_url' => 'http://insecure']);
        $this->assertSame(422, $s);
        [$s] = $this->api('results', '{not json');
        $this->assertSame(400, $s);
        [$s, $b] = $this->api('status', ['reference' => $app['reference'], 'status' => 'completed']);
        $this->assertSame(422, $s, 'Final results go to /results, not /status.');

        // Another partner cannot see this reference.
        $other = PartnerService::savePartner(['name' => 'Other ' . bin2hex(random_bytes(3)), 'application_url' => 'https://other.test', 'status' => 'active']);
        $this->keys = PartnerService::rotateKeys($other['id']);
        [$s] = $this->api('student', '', ['reference' => $app['reference']]);
        $this->assertSame(404, $s);
    }

    public function testAuthenticationSignatureTimestampAndPause(): void
    {
        $app = PartnerService::startApplication((int)$this->partner['id'], $this->candidate());
        $body = ['reference' => $app['reference'], 'score' => 75];
        [$s, $b] = $this->api('results', $body, [], null, fn () => $_SERVER['HTTP_X_PARTNER_KEY'] = 'icp_deadbeef_' . str_repeat('0', 40));
        $this->assertSame([401, 'invalid_partner_key'], [$s, $b['error']]);
        [$s, $b] = $this->api('results', $body, [], null, fn () => $_SERVER['HTTP_X_SIGNATURE'] = str_repeat('a', 64));
        $this->assertSame([401, 'invalid_signature'], [$s, $b['error']]);
        [$s, $b] = $this->api('results', $body, [], null, fn () => $_SERVER['HTTP_X_TIMESTAMP'] = (string)(time() - 3600));
        $this->assertSame([401, 'stale_timestamp'], [$s, $b['error']]);
        [$s, $b] = $this->api('results', $body, [], null, fn () => $_SERVER['HTTP_IDEMPOTENCY_KEY'] = '');
        $this->assertSame([400, 'idempotency_key_required'], [$s, $b['error']]);

        // Rotating keys stops the old key at once.
        $old = $this->keys;
        PartnerService::rotateKeys((int)$this->partner['id']);
        [$s] = $this->api('results', $body);
        $this->assertSame(401, $s);
        $this->keys = PartnerService::rotateKeys((int)$this->partner['id']);
        $this->assertNotSame($old['api_key'], $this->keys['api_key']);

        // Paused partner: authenticated but refused.
        $p = $this->partner;
        PartnerService::savePartner(['name' => $p['name'], 'slug' => $p['slug'], 'application_url' => $p['application_url'], 'status' => 'paused'], (int)$p['id']);
        [$s, $b] = $this->api('results', $body);
        $this->assertSame([403, 'partner_paused'], [$s, $b['error']]);

        // Every call was audited.
        $rows = array_filter(PartnerService::auditLog(50), fn ($r) => (int)$r['partner_id'] === (int)$p['id']);
        $this->assertGreaterThanOrEqual(3, count($rows));
    }

    public function testPaidUserIsNeverDowngradedAndGrantsExpire(): void
    {
        $cand = $this->candidate();
        BillingService::ensureTables();
        $renews = strtotime('+20 days');
        Database::getConnection()->prepare('INSERT INTO billing_subscriptions (account_type, account_id, plan, cycle, status, amount_kobo, started_at, renews_at, last_reference, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)')
            ->execute(['candidate', $cand, 'premium', 'monthly', 'active', 499900, date('Y-m-d H:i:s'), date('Y-m-d H:i:s', $renews), 'PAID-' . $cand, date('Y-m-d H:i:s')]);
        PlanService::flush();
        $app = PartnerService::startApplication((int)$this->partner['id'], $cand);
        $r = PartnerService::recordResult($this->partner, $app, 70)['result']; // Pro 180 days
        $grant = array_values(array_filter(PlanService::grants('candidate', $cand), fn ($g) => (int)$g['id'] === (int)$r['grant_id']))[0];
        $this->assertSame('premium', PlanService::effectivePlan('candidate', $cand), 'Paid Premium stays.');
        $this->assertGreaterThanOrEqual($renews - 2, strtotime($grant['starts_at']), 'The free Pro starts when the paid Premium ends.');

        // Expiry: grant ended -> cron closes it; stale applications expire.
        Database::getConnection()->prepare('UPDATE plan_grants SET starts_at = ?, expires_at = ? WHERE id = ?')->execute([date('Y-m-d H:i:s', time() - 7200), date('Y-m-d H:i:s', time() - 60), $grant['id']]);
        $this->assertGreaterThanOrEqual(1, PlanService::expireGrants());
        $old = PartnerService::startApplication((int)$this->partner['id'], $this->candidate());
        Database::getConnection()->prepare('UPDATE partner_applications SET created_at = ? WHERE id = ?')->execute([date('Y-m-d H:i:s', strtotime('-400 days')), $old['id']]);
        PartnerService::dailyMaintenance();
        $this->assertSame('expired', PartnerService::application($old['reference'])['status']);
    }

    public function testRevokeResultRemovesPlanAndBadge(): void
    {
        $cand = $this->candidate();
        $app = PartnerService::startApplication((int)$this->partner['id'], $cand);
        $r = PartnerService::recordResult($this->partner, $app, 90, [], 'manual', 'admin@test')['result'];
        $this->assertSame('premium', PlanService::effectivePlan('candidate', $cand));
        $this->assertTrue(PartnerService::revokeResult((int)$r['id'], 'admin@test'));
        PlanService::flush();
        $this->assertSame('free', PlanService::effectivePlan('candidate', $cand));
        $this->assertSame([], PartnerService::certifications($cand));
    }
}
