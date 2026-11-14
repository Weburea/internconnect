<?php

declare(strict_types=1);

namespace Tests\PHPUnit\Unit;

use App\Core\Database;
use App\Services\BillingService;
use App\Services\PlanService;
use PHPUnit\Framework\TestCase;

/** Plans: effective plan (paid + grants), stacking policy, quotas, admin edits (docs/53). */
final class PlanServiceTest extends TestCase
{
    private static int $next = 910000;

    private function account(): int
    {
        return ++self::$next + random_int(0, 50000) * 10;
    }

    private function pay(int $id, string $plan, string $renews): void
    {
        BillingService::ensureTables();
        $now = date('Y-m-d H:i:s');
        Database::getConnection()->prepare('INSERT INTO billing_subscriptions (account_type, account_id, plan, cycle, status, amount_kobo, started_at, renews_at, last_reference, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)')
            ->execute(['candidate', $id, $plan, 'monthly', 'active', 249900, $now, $renews, 'T-' . $id, $now]);
        PlanService::flush();
    }

    public function testNoUrlOrSessionCanChangeThePlan(): void
    {
        $id = $this->account();
        $_GET['plan'] = 'premium';
        $_SESSION['candidate_plan'] = 'premium';
        $this->assertSame('free', PlanService::effectivePlan('candidate', $id));
        unset($_GET['plan'], $_SESSION['candidate_plan']);
    }

    public function testGrantAndPaidPlanTakeTheHigher(): void
    {
        $id = $this->account();
        $this->pay($id, 'pro', date('Y-m-d H:i:s', strtotime('+10 days')));
        $this->assertSame('pro', PlanService::effectivePlan('candidate', $id));

        // Higher plan starts now, paid Pro keeps running underneath.
        $g = PlanService::grant('candidate', $id, 'premium', 30, 'training', 'R1', 'Jigide Academy');
        $this->assertLessThanOrEqual(time() + 2, strtotime($g['starts_at']));
        $this->assertSame('premium', PlanService::effectivePlan('candidate', $id));
        $this->assertSame('grant', PlanService::planSource('candidate', $id)['source']);

        // Same plan as the paid one stacks after it (never wastes paid days).
        $id2 = $this->account();
        $renews = strtotime('+10 days');
        $this->pay($id2, 'pro', date('Y-m-d H:i:s', $renews));
        $g2 = PlanService::grant('candidate', $id2, 'pro', 30, 'admin');
        $this->assertEqualsWithDelta($renews, strtotime($g2['starts_at']), 2);
        $this->assertSame('pro', PlanService::effectivePlan('candidate', $id2));

        // Lower plan than the paid Premium waits too: never a downgrade.
        $id3 = $this->account();
        $this->pay($id3, 'premium', date('Y-m-d H:i:s', strtotime('+5 days')));
        PlanService::grant('candidate', $id3, 'pro', 30, 'training');
        $this->assertSame('premium', PlanService::effectivePlan('candidate', $id3));
    }

    public function testRevokeAndExpiry(): void
    {
        $id = $this->account();
        $g = PlanService::grant('candidate', $id, 'pro', 7, 'admin');
        $this->assertSame('pro', PlanService::effectivePlan('candidate', $id));
        $this->assertTrue(PlanService::revokeGrant((int)$g['id']));
        $this->assertFalse(PlanService::revokeGrant((int)$g['id']), 'Already revoked.');
        $this->assertSame('free', PlanService::effectivePlan('candidate', $id));

        $g = PlanService::grant('candidate', $id, 'premium', 1, 'admin');
        Database::getConnection()->prepare('UPDATE plan_grants SET expires_at = ? WHERE id = ?')->execute([date('Y-m-d H:i:s', time() - 60), $g['id']]);
        PlanService::flush();
        $this->assertSame('free', PlanService::effectivePlan('candidate', $id));
        $this->assertGreaterThanOrEqual(1, PlanService::expireGrants());
    }

    public function testRevokedGrantCanBeReactivated(): void
    {
        $id = $this->account();
        $g = PlanService::grant('candidate', $id, 'pro', 7, 'admin');
        PlanService::revokeGrant((int)$g['id']);
        $this->assertSame('free', PlanService::effectivePlan('candidate', $id));
        $back = PlanService::reactivateGrant((int)$g['id']);
        $this->assertSame((int)$g['id'], (int)$back['id'], 'Time left: the same grant continues.');
        $this->assertSame('pro', PlanService::effectivePlan('candidate', $id));
        $this->assertNull(PlanService::reactivateGrant((int)$g['id']), 'Already active.');
    }

    public function testInvalidGrantsAreRefused(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        PlanService::grant('candidate', 1, 'gold', 30, 'admin');
    }

    public function testQuotas(): void
    {
        $id = $this->account();
        // Free: AI documents not included at all.
        $r = PlanService::consume('candidate', $id, 'ai_documents');
        $this->assertFalse($r['ok']);
        $this->assertTrue($r['upgrade']);

        // Free: 5 applications a month, the sixth is refused and check() never counts.
        for ($i = 0; $i < 5; $i++) {
            $this->assertTrue(PlanService::check('candidate', $id, 'applications')['ok']);
            $this->assertTrue(PlanService::consume('candidate', $id, 'applications')['ok']);
        }
        $this->assertFalse(PlanService::consume('candidate', $id, 'applications')['ok']);
        $this->assertSame(5, PlanService::usage('candidate', $id, 'applications')['used']);

        // Premium: unlimited applications.
        PlanService::grant('candidate', $id, 'premium', 30, 'admin');
        $r = PlanService::consume('candidate', $id, 'applications');
        $this->assertTrue($r['ok']);
        $this->assertTrue($r['usage']['unlimited']);
        $this->assertNull($r['usage']['remaining']);
    }

    public function testAdminEditsApplyAndAreValidated(): void
    {
        $before = PlanService::value('applications', 'free');
        $settings = ['premium_stipend_min' => (string)PlanService::setting('premium_stipend_min'), 'premium_needs_flex' => (string)PlanService::setting('premium_needs_flex')];
        PlanService::saveMatrix(['applications' => ['free' => '7']], ['premium_stipend_min' => '150000', 'premium_needs_flex' => '1']);
        $this->assertSame(7, PlanService::limit('applications', 'free'));
        $this->assertTrue(PlanService::isPremiumListing(150000, 'Remote'));
        $this->assertFalse(PlanService::isPremiumListing(150000, 'On-site'));
        $this->assertFalse(PlanService::isPremiumListing(90000, 'Hybrid'));
        try {
            PlanService::saveMatrix(['applications' => ['free' => 'lots']], []);
            $this->fail('Non-numeric limits are refused.');
        } catch (\InvalidArgumentException) {
            $this->assertSame(7, PlanService::limit('applications', 'free'));
        }
        PlanService::saveMatrix(['applications' => ['free' => $before]], $settings);
    }

    public function testJennyCoinsSpendRunOutAndRefill(): void
    {
        $id = $this->account();
        $c = PlanService::coins('candidate', $id);
        $this->assertSame([5, 5, 24, null], [$c['allowance'], $c['balance'], $c['refill_hours'], $c['refills_at']], 'Free: 5 coins, 24h refill, clock not started.');
        PlanService::spendCoins('candidate', $id, 2);
        $c = PlanService::coins('candidate', $id);
        $this->assertSame(3, $c['balance']);
        $this->assertEqualsWithDelta(24 * 3600, $c['refills_in'], 5, 'The refill clock starts at the first coin.');
        PlanService::spendCoins('candidate', $id, 3);
        $no = PlanService::canSpendCoins('candidate', $id, 1);
        $this->assertFalse($no['ok']);
        $this->assertStringContainsString('refill in', $no['message']);
        // Time passes: the wallet is full again.
        Database::getConnection()->prepare('UPDATE jenny_wallets SET cycle_started_at = ? WHERE account_type = ? AND account_id = ?')
            ->execute([date('Y-m-d H:i:s', time() - 25 * 3600), 'candidate', $id]);
        $this->assertSame(5, PlanService::coins('candidate', $id)['balance']);

        // Pro and Premium: more coins, faster refills; admin can change both.
        PlanService::grant('candidate', $id, 'pro', 30, 'admin');
        $this->assertSame([20, 12], [PlanService::coins('candidate', $id)['allowance'], PlanService::coins('candidate', $id)['refill_hours']]);
        $id2 = $this->account();
        PlanService::grant('candidate', $id2, 'premium', 30, 'admin');
        $this->assertSame([60, 5], [PlanService::coins('candidate', $id2)['allowance'], PlanService::coins('candidate', $id2)['refill_hours']]);
        PlanService::saveMatrix(['jenny_coins' => ['premium' => '80'], 'jenny_refill_hours' => ['premium' => '4']], []);
        $this->assertSame([80, 4], [PlanService::coins('candidate', $id2)['allowance'], PlanService::coins('candidate', $id2)['refill_hours']]);
        try {
            PlanService::saveMatrix(['jenny_refill_hours' => ['free' => '0']], []);
            $this->fail('Refill hours below 1 are refused.');
        } catch (\InvalidArgumentException) {
            $this->assertSame(24, PlanService::limit('jenny_refill_hours', 'free'));
        }
        PlanService::saveMatrix(['jenny_coins' => ['premium' => '60'], 'jenny_refill_hours' => ['premium' => '5']], []);
    }
}
