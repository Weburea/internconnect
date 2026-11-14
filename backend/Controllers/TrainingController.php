<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Services\PartnerService;

/**
 * POST /training/start/{id}: a candidate starts a certified course with a training partner.
 * Creates (or reuses) the application and sends the student to the partner with the signed
 * reference (ic_ref, ic_ts, ic_sig). docs/57-training-partners.md
 */
class TrainingController extends Controller
{
    public function start(array $params = []): void
    {
        if (($_SESSION['user_role'] ?? 'candidate') === 'employer') {
            $this->redirect('/elearning?training=candidates-only#certified-training');
            return;
        }
        // Candidate area uses the demo candidate until accounts move to MySQL (as CandidateController does).
        $candidateId = (int)($_SESSION['candidate_id'] ?? 1);
        try {
            $app = PartnerService::startApplication((int)($params['id'] ?? 0), $candidateId, mb_substr(trim(strip_tags((string)($_POST['program'] ?? ''))), 0, 160) ?: null);
        } catch (\InvalidArgumentException $e) {
            $this->redirect('/elearning?training=unavailable#certified-training');
            return;
        }
        header('Location: ' . $app['handoff_url'], true, 303);
        exit;
    }
}
