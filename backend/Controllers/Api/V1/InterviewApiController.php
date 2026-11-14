<?php

declare(strict_types=1);

namespace App\Controllers\Api\V1;

use App\Api\ApiException;
use App\Api\ApiResponse;
use App\Services\VideoInterviewService;

/**
 * GET  /api/v1/interviews              sessions for the signed-in account (admin: all)
 * GET  /api/v1/interviews/{id}/status  schedule, join window, room state
 * POST /api/v1/interviews/{id}/token   Daily room URL + meeting token (join window enforced for candidates)
 * Ownership: candidates and employers only reach their own interviews (404 otherwise).
 */
final class InterviewApiController extends ApiController
{
    public function index(): void
    {
        $me = $this->requireRole('candidate', 'employer', 'admin');
        $filters = ['limit' => 200];
        if ($me['role'] === 'candidate') {
            $filters['candidate_id'] = (int)$me['id'];
        } elseif ($me['role'] === 'employer') {
            $filters['employer_id'] = (int)$me['id'];
        }
        $rows = array_map(static fn (array $s) => [
            'id'           => (int)$s['id'],
            'candidate'    => ['id' => (int)$s['candidate_id'], 'name' => $s['candidate_name'], 'role' => $s['candidate_role']],
            'company'      => $s['company_name'],
            'interviewer'  => $s['interviewer_name'],
            'status'       => $s['status'],
            'scheduled_at' => $s['scheduled_at'] ? date(DATE_ATOM, (int)$s['scheduled_at']) : null,
            'duration_min' => (int)$s['duration_min'],
            'room_status'  => $s['room_status'],
        ], VideoInterviewService::sessions($filters));
        [$page, $per] = $this->page();
        ApiResponse::paginated($rows, 'Interviews retrieved.', $page, $per);
    }

    public function status(array $params = []): void
    {
        [$me, $interview] = $this->owned((int)($params['id'] ?? 0));
        $room = VideoInterviewService::getRoomDetails((int)$interview['id'], $me['role'] === 'candidate' ? 'candidate' : 'employer');
        $timing = $room['timing'] ?? [];
        $settings = VideoInterviewService::settings();
        $opensAt = $me['role'] === 'candidate' && !empty($timing['opens_at']) ? (int)$timing['opens_at'] : null;
        ApiResponse::ok([
            'id'           => (int)$interview['id'],
            'status'       => $interview['status'],
            'scheduled_at' => $interview['scheduled_at'] ? date(DATE_ATOM, (int)$interview['scheduled_at']) : null,
            'duration_min' => (int)$interview['duration_min'],
            'grace_min'    => (int)$settings['grace_min'],
            'opens_at'     => $opensAt ? date(DATE_ATOM, $opensAt) : null,
            'can_join_now' => $interview['status'] !== 'Cancelled' && (!$opensAt || time() >= $opensAt) && ($room['room_status'] ?? 'open') !== 'ended',
            'room_status'  => $room['room_status'] ?? 'none',
            'ends_at'      => !empty($timing['ends_at']) ? date(DATE_ATOM, (int)$timing['ends_at']) : null,
        ], 'Interview status.');
    }

    public function token(array $params = []): void
    {
        [$me, $interview] = $this->owned((int)($params['id'] ?? 0));
        $this->throttle('interview-token', $me, 10);
        $name = $me['role'] === 'admin' ? 'InternConnect Admin (' . $me['name'] . ')'
            : ($me['role'] === 'employer' ? $interview['interviewer_name'] . ' (' . $interview['company_name'] . ')' : $me['name']);
        $info = VideoInterviewService::joinInfo((int)$interview['id'], $me['role'], $name);
        if (empty($info['ok'])) {
            $status = ['too_early' => 409, 'finished' => 409, 'cancelled' => 409, 'not_found' => 404, 'disabled' => 503, 'not_configured' => 503][$info['code'] ?? ''] ?? 502;
            throw new ApiException((string)($info['error'] ?? 'Could not open the room.'), $status, [], (string)($info['code'] ?? 'room_error'));
        }
        ApiResponse::ok([
            'room_url' => $info['url'],
            'token'    => $info['token'],
            'owner'    => (bool)$info['owner'],
            'ends_at'  => date(DATE_ATOM, (int)($info['endsAt'] / 1000)),
            'hard_end' => date(DATE_ATOM, (int)($info['hardEnd'] / 1000)),
        ], 'Room access granted.');
    }

    /** @return array{0: array, 1: array} account + interview, or 404 when it is not theirs. */
    private function owned(int $id): array
    {
        $me = $this->requireRole('candidate', 'employer', 'admin');
        $interview = VideoInterviewService::getInterview($id);
        $mine = $interview && (
            $me['role'] === 'admin'
            || ($me['role'] === 'candidate' && (int)$interview['candidate_id'] === (int)$me['id'])
            || ($me['role'] === 'employer' && (int)$interview['employer_id'] === (int)$me['id'])
        );
        if (!$mine) {
            throw new ApiException('Interview not found.', 404);
        }
        return [$me, $interview];
    }
}
