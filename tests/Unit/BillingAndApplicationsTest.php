<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Services\ApplicationService;
use App\Services\BillingService;
use App\Services\CvService;
use App\Services\GeminiClient;
use App\Services\UploadStorage;
use App\Services\VideoInterviewService;
use Tests\TestCase;

/**
 * Billing (Paystack ledger + subscriptions), CV reading/autofill, application submissions,
 * the candidate-only join window and the view rules for the new pages.
 * Uses account/candidate ids >= 990000 so the demo accounts (#1) are never touched.
 * Gemini is not configured in tests, so every AI path runs its rule-based fallback.
 * Spec: docs/49-cv-autofill-and-applications.md, docs/08-payment-system.md
 */
class BillingAndApplicationsTest extends TestCase
{
    private function ref(string $tag): string
    {
        return 'IC_TEST_' . $tag . '_' . bin2hex(random_bytes(5));
    }

    public function testSuccessfulPaymentActivatesPlanAndIsRecordedOnce(): void
    {
        $acc = ['type' => 'employer', 'id' => 990000 + random_int(1, 9999)];
        $ref = $this->ref('OK');
        BillingService::recordPending($acc, $ref, 'pro', 'monthly', 249900, 'billing@test.dev');
        $this->assertEquals('pending', BillingService::transaction($ref)['status']);

        $paid = ['reference' => $ref, 'status' => 'success', 'amount' => 249900, 'paid_at' => date('c'), 'channel' => 'card',
            'authorization' => ['last4' => '4081', 'card_type' => 'visa']];
        $tx = BillingService::applyPaystackResult($paid);
        $this->assertEquals('success', $tx['status']);
        $this->assertEquals('4081', $tx['card_last4']);

        // Callback and webhook both arrive: the second one changes nothing.
        $sub = BillingService::subscription($acc['type'], $acc['id']);
        BillingService::applyPaystackResult($paid);
        $this->assertEquals($sub['renews_at'], BillingService::subscription($acc['type'], $acc['id'])['renews_at'], 'A duplicate confirmation must not extend the plan.');

        $ov = BillingService::overview($acc['type'], $acc['id']);
        $this->assertEquals('active', $ov['subscription']['status']);
        $this->assertEquals('pro', $ov['subscription']['plan']);
        $this->assertEquals(249900, $ov['paid_kobo']);
        $this->assertEquals(1, $ov['total']);
        $this->assertTrue(abs(strtotime($ov['subscription']['renews_at']) - strtotime('+1 month')) < 120, 'Monthly plan renews in one month.');
    }

    public function testAmountMismatchIsRejectedAndDoesNotActivate(): void
    {
        $acc = ['type' => 'candidate', 'id' => 990000 + random_int(1, 9999)];
        $ref = $this->ref('TAMPER');
        BillingService::recordPending($acc, $ref, 'premium', 'monthly', 499900, 'x@test.dev');
        $tx = BillingService::applyPaystackResult(['reference' => $ref, 'status' => 'success', 'amount' => 100]);
        $this->assertEquals('failed', $tx['status']);
        $this->assertTrue(BillingService::subscription($acc['type'], $acc['id']) === null, 'No plan for a payment of the wrong amount.');
    }

    public function testRenewingEarlyAddsTheNewPeriodAfterTheCurrentOne(): void
    {
        $acc = ['type' => 'candidate', 'id' => 990000 + random_int(1, 9999)];
        foreach (['A', 'B'] as $tag) {
            $ref = $this->ref('RENEW' . $tag);
            BillingService::recordPending($acc, $ref, 'pro', 'monthly', 249900, 'r@test.dev');
            BillingService::applyPaystackResult(['reference' => $ref, 'status' => 'success', 'amount' => 249900, 'paid_at' => date('c')]);
        }
        $renews = strtotime(BillingService::subscription($acc['type'], $acc['id'])['renews_at']);
        $this->assertTrue(abs($renews - strtotime('+2 months')) < 86400 * 2, 'Two monthly payments give two months.');
    }

    public function testWebhookForUnknownReferenceIsFiledFromMetadata(): void
    {
        $id = 990000 + random_int(1, 9999);
        $ref = $this->ref('HOOK');
        $tx = BillingService::applyPaystackResult(['reference' => $ref, 'status' => 'success', 'amount' => 249900,
            'metadata' => ['plan' => 'pro', 'cycle' => 'monthly', 'account_type' => 'employer', 'account_id' => $id], 'customer' => ['email' => 'h@test.dev']]);
        $this->assertEquals('success', $tx['status']);
        $this->assertEquals('employer', $tx['account_type']);
        $this->assertEquals('active', BillingService::subscription('employer', $id)['status']);
        $this->assertTrue(BillingService::applyPaystackResult(['reference' => $this->ref('BAD'), 'status' => 'success', 'amount' => 1, 'metadata' => ['plan' => 'gold']]) === null, 'Unknown plans are ignored.');
    }

    public function testBillingHelpers(): void
    {
        $this->assertEquals('₦2,499', BillingService::naira(249900));
        $this->assertEquals('₦2,499.50', BillingService::naira(249950));
        $this->assertEquals(['Paid', 'green'], BillingService::statusMeta('success'));
        $this->assertEquals('employer', BillingService::account('employer')['type']);
        $this->assertEquals('candidate', BillingService::account('anything-else')['type']);
    }

    public function testCvRulesFindContactDetailsWithoutMistakingDates(): void
    {
        $text = 'Curriculum Vitae Amaka Johnson Product Designer amaka.johnson@gmail.com +234 803 555 0142 '
            . 'Experience UX Design Intern, Flutterwave 2019 - 2023 linkedin.com/in/amakaj github.com/amaka';
        $r = CvService::rules($text);
        $this->assertEquals('Amaka Johnson', $r['name']);
        $this->assertEquals('amaka.johnson@gmail.com', $r['email']);
        $this->assertEquals('+234 803 555 0142', $r['phone']);
        $this->assertEquals('linkedin.com/in/amakaj', $r['links']['linkedin']);

        $onlyDates = CvService::rules('Jane Doe Education 2019 - 2023 Experience 2023 - 2024');
        $this->assertEquals('', $onlyDates['phone'], 'Date ranges are not phone numbers.');
    }

    public function testCvParseReadsAStoredFileAndFallsBackToRules(): void
    {
        $this->assertTrue(!GeminiClient::isConfigured(), 'Tests run without a Gemini key.');
        $file = bin2hex(random_bytes(16)) . '.pdf';
        $path = UploadStorage::directory('resume') . '/' . $file;
        // Uncompressed text stream: the rule reader can see it.
        file_put_contents($path, "%PDF-1.4\n1 0 obj << >> stream\n(Tunde Bakare) (tunde.b@test.dev) (+234 801 234 5678)\nendstream\n%%EOF");
        $meta = ['kind' => 'resume', 'file' => $file, 'name' => 'cv.pdf', 'size' => filesize($path), 'ext' => 'pdf'];
        $parsed = CvService::parse($meta);
        unlink($path);
        $this->assertEquals('rules', $parsed['source']);
        $this->assertEquals('tunde.b@test.dev', $parsed['email']);
        $this->assertEquals('none', CvService::parse(['kind' => 'resume', 'file' => '../../.env', 'ext' => 'pdf'])['source'], 'Tampered paths are refused.');

        $fields = CvService::fieldsForForm(['name' => 'A B', 'email' => 'a@b.co', 'phone' => '1', 'job_title' => 'Intern', 'company' => 'X', 'skills' => range(1, 12)]);
        $this->assertEquals(['full_name', 'email', 'phone', 'job_title', 'company', 'skills'], array_keys($fields));
        $this->assertEquals(8, count($fields['skills']));
    }

    public function testSavedCvDrivesPrefillAndEmployerSummary(): void
    {
        $cid = 990000 + random_int(1, 9999);
        $cv = CvService::blank();
        $cv = array_merge($cv, ['name' => 'Kemi Ade', 'email' => 'kemi@test.dev', 'phone' => '+234 809 000 1111', 'job_title' => 'Design Intern', 'company' => 'Paystack',
            'summary' => 'Kemi Ade is a product designer.', 'skills' => ['Figma', 'Research'], 'source' => 'rules',
            'experience' => [['title' => 'Design Intern', 'company' => 'Paystack', 'location' => 'Lagos', 'start' => 'Jan 2025', 'end' => 'Jun 2025', 'current' => false, 'highlights' => ['Built checkout']]]]);
        CvService::saveForCandidate($cid, ['kind' => 'resume', 'file' => str_repeat('a', 32) . '.pdf', 'name' => 'kemi.pdf', 'ext' => 'pdf'], $cv);

        $_SESSION['candidate_id'] = $cid;
        unset($_SESSION['profile_cv']);
        $pre = CvService::prefill();
        $this->assertEquals('kemi@test.dev', $pre['email']);
        $this->assertEquals('Design Intern', $pre['job_title']);
        $this->assertEquals('Paystack', $pre['company']);
        $this->assertTrue($pre['from_cv']);
        $_SESSION['profile_name'] = 'Kemi Adeyemi';
        $this->assertEquals('Kemi Adeyemi', CvService::prefill()['full_name'], 'The account name wins over the CV name.');

        $id = ApplicationService::submit($cid, [
            'slug' => 'software-engineer-intern', 'internship_title' => 'Design Intern', 'company_name' => 'Nexora',
            'full_name' => 'Kemi Adeyemi', 'email' => 'kemi@test.dev', 'phone' => '+234 809 000 1111',
            'job_title' => 'Design Intern', 'job_company' => 'Paystack', 'salary_expectation' => '300',
            'cover_letter' => 'Hello', 'answers' => [['question' => 'Why?', 'answer' => 'Because.']], 'links' => ['portfolio' => 'kemi.design'],
        ]);
        $this->assertTrue($id > 0, 'Submission saved.');
        $app = ApplicationService::latestForCandidate($cid);
        $this->assertEquals($id, (int)$app['id']);
        $this->assertEquals('rules', $app['ai_source']);
        $this->assertTrue(str_contains($app['ai_summary'], 'Kemi Ade is a product designer.'), 'Fallback summary uses the CV summary.');
        $this->assertEquals('Because.', $app['answers'][0]['answer']);
        $this->assertEquals('kemi.design', $app['links']['portfolio']);
    }

    public function testOnlyCandidatesWaitForTheJoinWindow(): void
    {
        $settings = VideoInterviewService::settings();
        $this->assertTrue($settings['join_early_min'] >= 0 && $settings['join_early_min'] <= 60);
        $src = (string)file_get_contents(dirname(__DIR__, 2) . '/backend/Services/VideoInterviewService.php');
        $this->assertTrue(str_contains($src, "\$role === 'candidate' && \$timing['opens_at'] && \$now < \$timing['opens_at']"), 'The too-early check applies to candidates only.');
        $this->assertTrue(str_contains($src, "'join_early_min'       => 5,"), 'Default candidate window is 5 minutes.');
        $room = (string)file_get_contents(dirname(__DIR__, 2) . '/frontend/shared/components/video-room.php');
        $this->assertTrue(str_contains($room, "\$vrConfig['role'] === 'candidate' && !empty(\$room['timing']['opens_at'])"), 'Only the candidate lobby shows the countdown.');
    }

    public function testNewPagesFollowTheUiRules(): void
    {
        $root = dirname(__DIR__, 2);
        $files = [
            '/frontend/shared/components/billing-center.php', '/frontend/shared/billing/receipt.php',
            '/frontend/admin/interviews/room.php', '/frontend/admin/interviews/partials/room-drawer.php',
            '/frontend/employer/candidates/show.php', '/frontend/candidate/application.php',
        ];
        foreach ($files as $f) {
            // iframe titles are accessible names (not hover tooltips), so iframes are excluded.
            $html = (string)preg_replace('/<\?=.*?\?>/s', '', (string)file_get_contents($root . $f));
            $html = (string)preg_replace('/<iframe\b[^>]*>/is', '', $html);
            $this->assertTrue(!preg_match('/\stitle="/', $html), "No native title tooltips in {$f}");
            $this->assertTrue(!preg_match('/\b(alert|confirm|prompt)\s*\(/i', $html), "No native dialogs in {$f}");
            $this->assertTrue(!preg_match('/border-left:\s*[2-9]px\s+solid/i', $html), "No coloured side strips in {$f}");
        }
        $sidebar = (string)file_get_contents($root . '/frontend/shared/components/candidate-sidebar.php');
        $this->assertTrue(str_contains($sidebar, "url('/candidate/billing')"), 'Candidate billing link opens the billing page, not pricing.');
        $this->assertTrue(str_contains((string)file_get_contents($root . '/frontend/shared/components/employer-sidebar.php'), "url('/employer/billing')"));
        $routes = (string)file_get_contents($root . '/backend/routes.php');
        foreach (['/candidate/billing', '/employer/billing', '/admin/interviews/{id}/room', '/apply/{slug}/parse-resume', '/register/student/parse-resume', '/employer/candidates/{id}/files/{kind}'] as $r) {
            $this->assertTrue(str_contains($routes, "'" . $r . "'"), "Route {$r} is registered");
        }
    }

    public function testElearningCatalogHasPartnersLogosAndServices(): void
    {
        $catalog = \App\Services\ElearningService::catalog();
        $this->assertTrue(count($catalog) >= 1, 'Seeded programs are listed.');
        $alx = array_values(array_filter($catalog, static fn ($p) => $p['platform'] === 'ALX Africa'))[0] ?? null;
        $this->assertTrue($alx !== null, 'ALX Africa program present.');
        $this->assertTrue(str_contains($alx['logo'], 'assets/partners/alx-africa.png'), 'Bundled logo used.');
        $this->assertTrue(count($alx['services']) >= 5, 'Partner services listed.');
        $this->assertTrue(str_contains($alx['go_url'], '/elearning/go/'), 'Enroll goes through the referral link.');
        foreach ($catalog as $p) {
            if (str_contains($p['logo'], 'assets/partners/')) {
                $file = dirname(__DIR__, 2) . '/public/assets/partners/' . basename((string)parse_url($p['logo'], PHP_URL_PATH));
                $this->assertTrue(is_file($file), 'Logo file exists for ' . $p['platform']);
            }
        }
        $this->assertTrue(\App\Services\ElearningService::referralTarget(99999999, null) === null, 'Unknown programs never redirect.');
        $bad = \App\Services\ElearningService::updatePartner(1, ['name' => 'X', 'website' => 'http://insecure.example']);
        $this->assertTrue($bad['ok'] === false, 'Partner websites must be https.');
    }

    public function testRevealOnlyOnPublicPagesAndDropdownEverywhere(): void
    {
        $root = dirname(__DIR__, 2);
        $layout = (string)file_get_contents($root . '/frontend/shared/layouts/app.php');
        $this->assertTrue(str_contains($layout, "str_starts_with((string)\$view, 'landing/')") && str_contains($layout, "'landing/payment'"), 'Reveal is limited to landing views, not payment results.');
        $js = (string)file_get_contents($root . '/public/assets/js/ic-reveal.js');
        $this->assertTrue(str_contains($js, 'prefers-reduced-motion'), 'Reduced motion respected.');
        $this->assertTrue(!str_contains($js, 'transform:none'), 'Reveal never overrides element transforms.');
        foreach (['admin', 'app', 'auth', 'dashboard'] as $l) {
            $this->assertTrue(str_contains((string)file_get_contents($root . "/frontend/shared/layouts/{$l}.php"), 'ic-select.js'), "Custom dropdown loaded in {$l} layout");
        }
        foreach (glob($root . '/frontend/{*,*/*,*/*/*,*/*/*/*}.php', GLOB_BRACE) as $file) {
            if (preg_match_all('/<select\b[^>]*>/i', (string)file_get_contents($file), $m)) {
                foreach ($m[0] as $tag) {
                    $this->assertTrue(str_contains($tag, 'data-ic-select'), 'Every select uses the custom dropdown: ' . basename($file));
                }
            }
        }
    }
}
