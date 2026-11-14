<?php

declare(strict_types=1);

namespace App\Controllers\Api\V1;

use App\Api\ApiException;
use App\Api\ApiResponse;
use App\Services\AiService;
use App\Services\CvService;
use App\Services\PlanService;
use App\Services\UploadStorage;

/**
 * Jenny AI (Gemini with rule-based fallback). Each response says which engine answered ("source").
 * POST /api/v1/ai/cv/parse                  multipart "resume" (PDF/DOCX) -> parsed CV, saved to the profile
 * POST /api/v1/ai/cover-letter              {company, role, tone?, description?}
 * POST /api/v1/ai/interview-prep/questions  {role}
 * Limits: 10 AI requests per minute per account.
 */
final class AiApiController extends ApiController
{
    public function parseCv(): void
    {
        $me = $this->requireRole('candidate');
        $this->throttle('ai', $me, 10);
        if (empty($_FILES['resume']) || ($_FILES['resume']['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
            throw new ApiException('Attach the CV as multipart field "resume" (PDF or DOCX).', 422, ['resume' => ['The resume file is required.']]);
        }
        $stored = UploadStorage::store($_FILES['resume'], 'resume');
        if (!$stored['ok']) {
            throw new ApiException((string)$stored['error'], 422, ['resume' => [(string)$stored['error']]]);
        }
        $parsed = CvService::saveForCandidate((int)$me['id'], $stored['meta']);
        ApiResponse::ok($parsed, 'CV read.', 200, ['source' => $parsed['source']]);
    }

    public function coverLetter(): void
    {
        $me = $this->requireRole('candidate');
        $this->throttle('ai', $me, 10);
        $in = $this->validate([
            'company'     => 'required|string|max:160',
            'role'        => 'required|string|max:160',
            'tone'        => 'string|in:professional,confident,technical,enthusiastic',
            'description' => 'string|max:2000',
        ]);
        $quota = PlanService::check('candidate', (int)$me['id'], 'ai_documents');
        if (!$quota['ok']) {
            throw new ApiException($quota['message'], $quota['usage']['limit'] === 0 ? 403 : 429, [], 'plan_limit');
        }
        $cv = CvService::forCandidate((int)$me['id'])['parsed'] ?? [];
        PlanService::consume('candidate', (int)$me['id'], 'ai_documents');
        $letter = AiService::generateCoverLetter(
            ['user_id' => (int)$me['id'], 'name' => $me['name'], 'skills' => $cv['skills'] ?? []],
            ['company' => $in['company'], 'role' => $in['role'], 'description' => $in['description'] ?? '', 'max_chars' => 2000],
            $in['tone'] ?? 'professional'
        );
        ApiResponse::ok([
            'letter'         => $letter['full_letter'],
            'paragraphs'     => $letter['paragraphs'],
            'tone'           => $letter['tone'],
            'matched_skills' => $letter['matched_skills'],
            'word_count'     => $letter['word_count'],
        ], 'Cover letter written.', 200, ['source' => $letter['source'] ?? 'rules']);
    }

    public function interviewQuestions(): void
    {
        $me = $this->requireRole('candidate', 'employer');
        $this->throttle('ai', $me, 10);
        $in = $this->validate(['role' => 'required|string|max:120']);
        ApiResponse::ok(AiService::getInterviewQuestions($in['role']), 'Interview practice questions.');
    }
}
