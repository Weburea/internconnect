<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Services\UploadStorage;
use App\Services\VideoInterviewService;

/**
 * In-call actions and interview history for employers and candidates.
 * The role comes from the URL prefix (/employer/... or /candidate/...), never from the request body.
 * Spec: docs/47-video-interviews.md
 */
class InterviewController extends Controller
{
    private function boot(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    private function role(): string
    {
        $path = (string)parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH);
        return str_contains($path, '/employer/') ? 'employer' : 'candidate';
    }

    private function input(): array
    {
        $json = json_decode((string)file_get_contents('php://input'), true);
        return is_array($json) ? $json : $_POST;
    }

    private function json(array $data, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json');
        header('Cache-Control: no-store');
        echo json_encode($data);
        exit;
    }

    private function selfName(array $interview, string $role): string
    {
        return $role === 'employer'
            ? $interview['interviewer_name'] . ' (' . $interview['company_name'] . ')'
            : ($_SESSION['profile_name'] ?? $interview['candidate_name']);
    }

    private function fileUrl(string $role, int $interviewId, int $fileId): string
    {
        return url(($role === 'employer' ? '/employer/interviews/room/' : '/candidate/interview/room/') . $interviewId . '/files/' . $fileId);
    }

    /** POST .../room/{id}/event  {event: screen_share_started | screen_share_stopped} */
    public function roomEvent(array $params = []): void
    {
        $this->boot();
        $event = (string)($this->input()['event'] ?? '');
        if (!in_array($event, ['screen_share_started', 'screen_share_stopped', 'time_warning'], true)) {
            $this->json(['success' => false], 422);
        }
        VideoInterviewService::logEvent((int)($params['id'] ?? 0), $this->role(), $event);
        $this->json(['success' => true]);
    }

    /** POST .../room/{id}/files  (multipart field "file") */
    public function uploadFile(array $params = []): void
    {
        $this->boot();
        $id = (int)($params['id'] ?? 0);
        $role = $this->role();
        $interview = VideoInterviewService::getInterview($id);
        if (!$interview) {
            $this->json(['success' => false, 'error' => 'We could not find this interview.'], 404);
        }
        $result = VideoInterviewService::addFile($id, $role, $this->selfName($interview, $role), $_FILES['file'] ?? null);
        if (!$result['ok']) {
            $this->json(['success' => false, 'error' => $result['error']], 422);
        }
        $file = $result['file'] + ['url' => $this->fileUrl($role, $id, $result['file']['id'])];
        VideoInterviewService::addChat($id, $role, $this->selfName($interview, $role), 'Shared a file: ' . $file['name'], (int)$file['id']);
        $this->json(['success' => true, 'file' => $file]);
    }

    /** GET .../room/{id}/files/{fileId} */
    public function downloadFile(array $params = []): void
    {
        $this->boot();
        $meta = VideoInterviewService::fileMeta((int)($params['id'] ?? 0), (int)($params['fileId'] ?? 0));
        UploadStorage::stream($meta, isset($_GET['download']));
    }

    /** POST .../room/{id}/chat  {body} */
    public function chatMessage(array $params = []): void
    {
        $this->boot();
        $id = (int)($params['id'] ?? 0);
        $interview = VideoInterviewService::getInterview($id);
        $body = trim((string)($this->input()['body'] ?? ''));
        if (!$interview || $body === '') {
            $this->json(['success' => false], 422);
        }
        $role = $this->role();
        VideoInterviewService::addChat($id, $role, $this->selfName($interview, $role), $body);
        $this->json(['success' => true]);
    }

    /** POST /employer/interviews/room/{id}/scorecard */
    public function saveScorecard(array $params = []): void
    {
        $this->boot();
        $saved = VideoInterviewService::saveScorecard((int)($params['id'] ?? 0), $this->input());
        $this->json(['success' => true, 'scorecard' => $saved]);
    }

    /** POST /employer/interviews/room/{id}/suggest  {asked: [ids], notes} */
    public function suggestQuestions(array $params = []): void
    {
        $this->boot();
        $in = $this->input();
        $asked = array_map('intval', (array)($in['asked'] ?? []));
        $result = VideoInterviewService::suggestQuestions((int)($params['id'] ?? 0), $asked, (string)($in['notes'] ?? ''));
        $this->json(['success' => true] + $result);
    }

    /** POST /employer/interviews/{id}/report/ai */
    public function generateSummary(array $params = []): void
    {
        $this->boot();
        $summary = VideoInterviewService::generateSummary((int)($params['id'] ?? 0));
        $summary ? $this->json(['success' => true, 'summary' => $summary]) : $this->json(['success' => false], 404);
    }

    /** GET /employer/interviews/{id}/report and /candidate/interview/{id}/report */
    public function report(array $params = []): void
    {
        $this->boot();
        $role = $this->role();
        $id = (int)($params['id'] ?? 0);
        $report = VideoInterviewService::report($id, $role);
        if (!$report) {
            http_response_code(404);
        }

        $view = [
            'title'     => 'Interview report | InternConnect',
            'report'    => $report,
            'audience'  => $role,
            'activeNav' => $role === 'employer' ? 'interviews' : 'applications',
            'fileBase'  => url(($role === 'employer' ? '/employer/interviews/room/' : '/candidate/interview/room/') . $id . '/files/'),
            'aiUrl'     => $role === 'employer' ? url('/employer/interviews/' . $id . '/report/ai') : '',
            'backUrl'   => $role === 'employer' ? url('/employer/interviews?tab=history') : url('/candidate/interview/invite'),
            'roomUrl'   => url(($role === 'employer' ? '/employer/interviews/room/' : '/candidate/interview/room/') . $id),
        ];
        $this->renderView($role === 'employer' ? 'employer/interviews/report' : 'candidate/interview/report', $view, 'dashboard');
    }
}
