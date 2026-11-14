<?php

declare(strict_types=1);

namespace App\Controllers\Api\V1;

use App\Api\ApiException;
use App\Api\ApiResponse;
use App\Services\ApplicationService;
use App\Services\BillingService;
use App\Services\CommunicationService;
use App\Services\CvService;
use App\Services\PaystackService;

/**
 * Signed-in account data.
 * Candidate: GET /api/v1/candidate/profile, /candidate/applications, /candidate/applications/{id}
 * Both:      GET /api/v1/billing, /api/v1/notifications
 * Employer:  GET /api/v1/employer/candidates/{id}/application, POST /api/v1/employer/candidates/{id}/stage
 */
final class AccountApiController extends ApiController
{
    public function candidateProfile(): void
    {
        $me = $this->requireRole('candidate');
        $cv = CvService::forCandidate((int)$me['id']);
        $parsed = $cv['parsed'] ?? CvService::blank();
        ApiResponse::ok([
            'id'         => (int)$me['id'],
            'name'       => $me['name'],
            'email'      => $me['email'],
            'headline'   => $parsed['headline'],
            'location'   => $parsed['location'],
            'summary'    => $parsed['summary'],
            'skills'     => $parsed['skills'],
            'experience' => $parsed['experience'],
            'education'  => $parsed['education'],
            'links'      => $parsed['links'],
            'cv'         => $cv && !empty($cv['file']) ? ['name' => $cv['file']['name'] ?? 'CV', 'updated_at' => $cv['updated_at'], 'parsed_by' => $parsed['source']] : null,
        ], 'Profile retrieved.');
    }

    public function candidateApplications(): void
    {
        $me = $this->requireRole('candidate');
        $rows = array_map([$this, 'presentApplication'], ApplicationService::listForCandidate((int)$me['id']));
        [$page, $per] = $this->page();
        ApiResponse::paginated($rows, 'Applications retrieved.', $page, $per);
    }

    public function candidateApplication(array $params = []): void
    {
        $me = $this->requireRole('candidate');
        $app = ApplicationService::find((int)($params['id'] ?? 0));
        if (!$app || (int)$app['candidate_id'] !== (int)$me['id']) {
            throw new ApiException('Application not found.', 404);
        }
        ApiResponse::ok($this->presentApplication($app, true), 'Application retrieved.');
    }

    public function billing(): void
    {
        $me = $this->requireRole('candidate', 'employer');
        $o = BillingService::overview($me['role'], (int)$me['id'], max(1, (int)($_GET['page'] ?? 1)), 20);
        $sub = $o['subscription'];
        ApiResponse::ok([
            'plan' => $sub ? [
                'key'        => $sub['plan'],
                'name'       => (PaystackService::getPlanDetails((string)$sub['plan'])['name'] ?? ucfirst((string)$sub['plan'])),
                'cycle'      => $sub['cycle'],
                'status'     => $sub['status'],
                'amount'     => ['kobo' => (int)$sub['amount_kobo'], 'label' => BillingService::naira((int)$sub['amount_kobo'])],
                'started_at' => $sub['started_at'],
                'renews_at'  => $sub['renews_at'],
            ] : ['key' => 'free', 'name' => 'Free plan', 'status' => 'free'],
            'total_paid' => ['kobo' => (int)$o['paid_kobo'], 'label' => BillingService::naira((int)$o['paid_kobo'])],
            'payments'   => array_map(static fn (array $t) => [
                'reference' => $t['reference'],
                'plan'      => $t['plan'],
                'cycle'     => $t['cycle'],
                'amount'    => ['kobo' => (int)$t['amount_kobo'], 'label' => BillingService::naira((int)$t['amount_kobo'])],
                'status'    => $t['status'],
                'method'    => $t['card_last4'] ? trim(($t['card_brand'] ?: 'Card') . ' ' . $t['card_last4']) : ($t['channel'] ?: null),
                'paid_at'   => $t['paid_at'],
                'created_at'=> $t['created_at'],
            ], $o['transactions']),
        ], 'Billing retrieved.', 200, ['current_page' => $o['page'], 'total_pages' => $o['pages'], 'total_records' => $o['total'], 'per_page' => 20]);
    }

    public function notifications(): void
    {
        $me = $this->requireRole('candidate', 'employer');
        $items = CommunicationService::presentNotifications(CommunicationService::getNotifications($me['role'], (int)$me['id']), $me['role']);
        $items = array_map(static fn (array $n) => array_intersect_key($n, array_flip(['id', 'title', 'body', 'category', 'is_read', 'time_label', 'timestamp', 'action_url'])), $items);
        if (isset($_GET['unread']) && $_GET['unread'] !== '0') {
            $items = array_values(array_filter($items, static fn ($n) => empty($n['is_read'])));
        }
        [$page, $per] = $this->page();
        ApiResponse::paginated($items, 'Notifications retrieved.', $page, $per);
    }

    public function employerCandidateApplication(array $params = []): void
    {
        $this->requireRole('employer', 'admin');
        $app = ApplicationService::latestForCandidate((int)($params['id'] ?? 0));
        if (!$app) {
            throw new ApiException('This candidate has no submitted application.', 404);
        }
        $cv = CvService::forCandidate((int)$app['candidate_id']);
        ApiResponse::ok($this->presentApplication($app, true) + [
            'candidate' => [
                'id'         => (int)$app['candidate_id'],
                'name'       => $app['full_name'],
                'email'      => $app['email'],
                'phone'      => $app['phone'],
                'experience' => $cv['parsed']['experience'] ?? [],
                'education'  => $cv['parsed']['education'] ?? [],
                'skills'     => $cv['parsed']['skills'] ?? [],
            ],
        ], 'Application retrieved.');
    }

    public function employerSetStage(array $params = []): void
    {
        $this->requireRole('employer', 'admin');
        $in = $this->validate(['stage' => 'required|string|in:' . implode(',', ApplicationService::STAGES)]);
        $app = ApplicationService::latestForCandidate((int)($params['id'] ?? 0));
        if (!$app) {
            throw new ApiException('This candidate has no submitted application.', 404);
        }
        ApplicationService::setStage((int)$app['id'], $in['stage']);
        ApiResponse::ok(['application_id' => (int)$app['id'], 'stage' => $in['stage']], 'Stage updated.');
    }

    private function presentApplication(array $a, bool $full = false): array
    {
        $out = [
            'id'           => (int)$a['id'],
            'internship'   => ['slug' => $a['internship_slug'], 'title' => $a['internship_title'], 'company' => $a['company_name']],
            'stage'        => ApplicationService::stageOf($a),
            'submitted_at' => $a['submitted_at'],
        ];
        if ($full) {
            $out += [
                'job_title'          => $a['job_title'],
                'job_company'        => $a['job_company'],
                'salary_expectation' => $a['salary_expectation'],
                'cover_letter'       => $a['cover_letter'],
                'has_cover_letter_file' => !empty($a['cover_letter_file']),
                'answers'            => $a['answers'],
                'links'              => $a['links'],
                'ai_summary'         => $a['ai_summary'],
                'resume'             => $a['resume_file'] ? ['name' => $a['resume_file']['name'], 'ext' => $a['resume_file']['ext']] : null,
            ];
        }
        return $out;
    }
}
