<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Services\CommunicationService;
use App\Services\GeoService;
use App\Services\UploadStorage;
use App\Services\VideoInterviewService;
use Tests\TestCase;

/**
 * In-platform video interviews: setup, timing window, scorecards, files, chat,
 * reports, admin console data and view rules. Spec: docs/47-video-interviews.md
 */
class VideoInterviewTest extends TestCase
{
    /** Book a session through the same path the employer schedule page uses. */
    private function bookInterview(?int $startsAt = null, int $minutes = 30, ?array $setup = null): int
    {
        $startsAt = $startsAt ?? time() + 3600;
        $row = CommunicationService::scheduleInterview([
            'candidate_id'     => 1,
            'candidate_name'   => 'Test Candidate',
            'candidate_role'   => 'QA Intern',
            'company_name'     => 'Unit Test Co',
            'interviewer_name' => 'Unit Tester',
            'meeting_type'     => 'virtual',
            'date'             => date('m/d/Y', $startsAt),
            'time'             => date('h:i A', $startsAt),
            'duration'         => $minutes . ' mins',
        ]);
        $id = (int)$row['id'];
        VideoInterviewService::saveInterviewMeta($id, $startsAt, $minutes, $setup, 'employer');
        return $id;
    }

    /** Keep test sessions out of the employer/admin lists (non-destructive: just unmark them). */
    private function hide(int $id): void
    {
        \App\Core\Database::getConnection()->prepare('UPDATE scheduled_interviews SET created_by = NULL WHERE id = ?')->execute([$id]);
    }

    public function testSettingsDefaultsAndClamping(): void
    {
        $before = VideoInterviewService::settings();
        foreach (['video_enabled', 'allow_screenshare', 'allow_chat', 'allow_files', 'default_duration_min', 'grace_min', 'join_early_min'] as $key) {
            $this->assertArrayHasKey($key, $before, "Setting {$key} must exist");
        }

        $saved = VideoInterviewService::saveSettings([
            'video_enabled' => 1, 'allow_screenshare' => 1, 'allow_chat' => 1, 'allow_files' => 1,
            'default_duration_min' => 999, 'grace_min' => -4, 'join_early_min' => 500,
        ]);
        $this->assertEquals(180, $saved['default_duration_min'], 'Duration is capped at 180 minutes');
        $this->assertEquals(0, $saved['grace_min'], 'Wrap-up time cannot be negative');
        $this->assertEquals(60, $saved['join_early_min'], 'Early join is capped at 60 minutes');

        // Restore what was there before the test.
        VideoInterviewService::saveSettings($before);
        $this->assertEquals($before['default_duration_min'], VideoInterviewService::settings()['default_duration_min']);
    }

    public function testScheduleParsing(): void
    {
        $ts = VideoInterviewService::parseSchedule('2026-11-20', '14:30');
        $this->assertEquals('2026-11-20 14:30', date('Y-m-d H:i', (int)$ts));
        $ts = VideoInterviewService::parseSchedule('11/20/2026', '02:30 PM');
        $this->assertEquals('2026-11-20 14:30', date('Y-m-d H:i', (int)$ts));
        $this->assertTrue(VideoInterviewService::parseSchedule('Tomorrow', '10:00 AM') === null, 'Free text dates are rejected');
        $this->assertTrue(VideoInterviewService::parseSchedule('', '') === null, 'Empty dates are rejected');
    }

    public function testSetupSanitizing(): void
    {
        $setup = VideoInterviewService::sanitizeSetup([
            'criteria'        => ['Design thinking', '<b>Figma</b> skills', '', 'Design thinking'],
            'questions'       => [['question' => 'Tell us about a project', 'hint' => 'Impact'], ['question' => '   ']],
            'recommendations' => [['label' => 'Advance', 'tone' => 'good'], ['label' => 'Decline', 'tone' => 'evil']],
        ]);
        $this->assertEquals(['Design thinking', 'Figma skills', 'Design thinking'], array_column($setup['criteria'], 'label'), 'Tags are stripped and blanks dropped');
        $this->assertEquals(3, count(array_unique(array_column($setup['criteria'], 'key'))), 'Criterion keys stay unique');
        $this->assertEquals(1, count($setup['questions']), 'Blank questions are dropped');
        $this->assertEquals('neutral', $setup['recommendations'][1]['tone'], 'Unknown tones fall back to neutral');

        $defaults = VideoInterviewService::sanitizeSetup(null);
        $this->assertEquals(4, count($defaults['criteria']), 'Default criteria are used when none are given');
        $this->assertEquals(4, count($defaults['recommendations']), 'Default decisions are used when fewer than two are given');
    }

    public function testJoinWindowAndCallTiming(): void
    {
        $settings = ['grace_min' => 1, 'join_early_min' => 10] + VideoInterviewService::settings();
        $start = time() + 3600;
        $interview = ['scheduled_at' => $start, 'duration_min' => 30];

        $t = VideoInterviewService::timing($interview, null, $settings);
        $this->assertEquals($start - 600, $t['opens_at'], 'Room opens 10 minutes before the start');
        $this->assertTrue($t['started_at'] === null && $t['hard_end'] === null, 'No timer before anyone joins');

        $room = ['started_at' => date('Y-m-d H:i:s', $start)];
        $t = VideoInterviewService::timing($interview, $room, $settings);
        $this->assertEquals($start + 1800, $t['ends_at'], 'The call lasts the interview duration');
        $this->assertEquals($start + 1860, $t['hard_end'], 'Then one extra wrap-up minute');
    }

    public function testInterviewRecordUsesEmployerSetup(): void
    {
        $id = $this->bookInterview(time() + 7200, 30, [
            'criteria'        => ['Design thinking', 'Communication'],
            'questions'       => [['question' => 'Walk us through your portfolio.']],
            'recommendations' => [['label' => 'Advance', 'tone' => 'good'], ['label' => 'Hold'], ['label' => 'Decline', 'tone' => 'bad']],
        ]);
        $interview = VideoInterviewService::getInterview($id);
        $this->assertEquals(30, $interview['duration_min']);
        $this->assertEquals('employer', $interview['created_by']);
        $this->assertEquals(['Design thinking', 'Communication'], array_column($interview['setup']['criteria'], 'label'));
        $this->assertEquals(['advance', 'hold', 'decline'], array_column($interview['setup']['recommendations'], 'key'));

        $room = VideoInterviewService::getRoomDetails($id, 'employer');
        $this->assertEquals('Walk us through your portfolio.', $room['screening_questions'][0]['question'], 'The room shows the employer questions');
        $this->assertTrue(VideoInterviewService::getInterview(99999999) === null, 'Unknown interviews return null');
        $this->hide($id);
    }

    public function testScorecardOnlyKeepsKnownCriteriaAndDecisions(): void
    {
        $id = $this->bookInterview(null, 30, ['criteria' => ['Design thinking', 'Communication'], 'recommendations' => [['label' => 'Advance'], ['label' => 'Decline']]]);
        $saved = VideoInterviewService::saveScorecard($id, [
            'scores'         => ['design_thinking' => 9, 'communication' => 3, 'hacked' => 5],
            'recommendation' => 'advance',
            'notes'          => 'Strong process.',
            'asked'          => [1, '2', -4, 'x'],
        ]);
        $this->assertEquals(['design_thinking' => 5, 'communication' => 3], $saved['scores'], 'Scores are clamped and unknown criteria dropped');
        $this->assertEquals('advance', $saved['recommendation']);
        $this->assertEquals([1, 2], $saved['asked']);
        $this->assertEquals(4.0, VideoInterviewService::scoreAverage(VideoInterviewService::getScorecard($id)));

        $bad = VideoInterviewService::saveScorecard($id, ['recommendation' => 'strong_hire']);
        $this->assertTrue($bad['recommendation'] === null, 'Decisions outside the setup are rejected');
        $this->hide($id);
    }

    public function testChatEventsReportAndAudienceRules(): void
    {
        $id = $this->bookInterview();
        VideoInterviewService::addChat($id, 'candidate', 'Test Candidate', 'Hello there');
        VideoInterviewService::addChat($id, 'employer', 'Unit Tester', '   ');
        VideoInterviewService::logEvent($id, 'employer', 'joined');
        VideoInterviewService::logEvent($id, 'candidate', 'joined');
        VideoInterviewService::logEvent($id, 'employer', 'screen_share_started');
        VideoInterviewService::saveScorecard($id, ['scores' => ['technical' => 4], 'notes' => 'Private note']);

        $this->assertEquals(1, count(VideoInterviewService::chat($id)), 'Blank chat messages are ignored');

        $employer = VideoInterviewService::report($id, 'employer');
        $this->assertEquals(1, $employer['screen_shares']);
        $this->assertArrayHasKey('scorecard', $employer);
        $this->assertArrayHasKey('chat', $employer);

        $candidate = VideoInterviewService::report($id, 'candidate');
        $this->assertFalse(array_key_exists('scorecard', $candidate), 'Candidates never see the scorecard');
        $this->assertTrue($candidate['ai'] === null, 'Candidates never see the AI assessment');

        $admin = VideoInterviewService::report($id, 'admin');
        $this->assertFalse(array_key_exists('chat', $admin), 'Admins see counts, not chat content');
        $this->assertEquals(1, $admin['chat_count']);
        $this->hide($id);
    }

    public function testRuleBasedSummaryWhenAiIsUnavailable(): void
    {
        $id = $this->bookInterview(null, 30, ['criteria' => ['Design thinking', 'Communication']]);
        VideoInterviewService::saveScorecard($id, ['scores' => ['design_thinking' => 5, 'communication' => 4], 'recommendation' => 'hire', 'asked' => [1]]);
        $interview = VideoInterviewService::getInterview($id);
        $summary = VideoInterviewService::ruleSummary($interview, VideoInterviewService::getScorecard($id), ['employer' => 28, 'candidate' => 27], [$interview['setup']['questions'][0]], []);

        $this->assertStringContains('Test Candidate', $summary['overview']);
        $this->assertEquals(5, $summary['suggested_rating'], 'Average 4.5 rounds to 5');
        $this->assertEquals('strong_hire', $summary['suggested_recommendation'], 'High averages map to the strongest decision');
        $this->assertNotEmpty($summary['highlights']);
        $this->hide($id);
    }

    public function testAttendanceFromJoinAndLeaveEvents(): void
    {
        $events = [
            ['role' => 'employer', 'event' => 'joined', 'created_at' => '2026-11-11 10:00:00'],
            ['role' => 'candidate', 'event' => 'joined', 'created_at' => '2026-11-11 10:02:00'],
            ['role' => 'candidate', 'event' => 'left', 'created_at' => '2026-11-11 10:20:00'],
            ['role' => 'employer', 'event' => 'room_ended', 'created_at' => '2026-11-11 10:30:00'],
        ];
        $this->assertEquals(['employer' => 30, 'candidate' => 18], VideoInterviewService::attendance($events));
    }

    public function testCancellingMarksStatusAndClosesRoom(): void
    {
        $id = $this->bookInterview();
        $this->assertTrue(VideoInterviewService::setStatus($id, 'Ready', 'admin'));
        $this->assertEquals('Ready', VideoInterviewService::getInterview($id)['status']);
        $this->assertFalse(VideoInterviewService::setStatus($id, 'Hacked', 'admin'), 'Unknown statuses are rejected');
        $this->assertTrue(VideoInterviewService::setStatus($id, 'Cancelled', 'admin'));
        $this->assertEquals('Cancelled', VideoInterviewService::getInterview($id)['status']);
        $this->assertTrue(VideoInterviewService::endRoom(99999999, 'admin'), 'Ending a room that never opened is a no-op');
        $this->hide($id);
    }

    public function testEventBadgesUseKnownTones(): void
    {
        foreach (['joined', 'left', 'screen_share_started', 'file_shared', 'scheduled', 'room_ended', 'status_cancelled', 'anything_new'] as $event) {
            [$label, $tone] = VideoInterviewService::eventMeta($event);
            $this->assertNotEmpty($label);
            $this->assertTrue(in_array($tone, ['green', 'slate', 'indigo', 'sky', 'violet', 'amber', 'red'], true), "Tone for {$event} must be a palette tone");
        }
    }

    public function testGeoHelpersMaskAndDescribe(): void
    {
        $this->assertEquals('102.89.41.xxx', GeoService::maskIp('102.89.41.112'));
        $this->assertEquals('unknown', GeoService::maskIp('not-an-ip'));
        $this->assertTrue(GeoService::isPrivate('127.0.0.1'));
        $this->assertFalse(GeoService::isPrivate('102.89.41.112'));
        $this->assertEquals('Chrome on Windows', GeoService::deviceLabel('Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0 Safari/537.36'));
        $this->assertEquals('Safari on iPhone', GeoService::deviceLabel('Mozilla/5.0 (iPhone; CPU iPhone OS 17_4 like Mac OS X) AppleWebKit/605.1.15 Version/17.4 Mobile/15E148 Safari/604.1'));
    }

    public function testInterviewFileRulesAreConfigured(): void
    {
        $result = UploadStorage::store(null, 'interview_file');
        $this->assertFalse($result['ok'], 'A missing upload is rejected');
        $this->assertStringContains('choose a file', $result['error']);
        $this->assertTrue(UploadStorage::path(['kind' => 'interview_file', 'file' => '../../.env']) === null, 'Path traversal names are refused');
        $this->assertTrue(UploadStorage::path(['kind' => 'nope', 'file' => str_repeat('a', 32) . '.pdf']) === null, 'Unknown kinds are refused');
    }

    public function testInterviewViewsFollowUiRules(): void
    {
        $root = dirname(__DIR__, 2);
        $views = [
            '/frontend/shared/components/video-room.php',
            '/frontend/shared/components/interview-report.php',
            '/frontend/employer/interviews/partials/room-drawer.php',
            '/frontend/employer/interviews/index.php',
            '/frontend/employer/interviews/schedule.php',
            '/frontend/candidate/interview/invite.php',
            '/frontend/admin/interviews/index.php',
        ];
        foreach ($views as $view) {
            $content = (string)file_get_contents($root . $view);
            $this->assertFalse((bool)preg_match('/\b(alert|confirm|prompt)\(/', $content), "No native dialogs in {$view}");
            $this->assertFalse((bool)preg_match('/\stitle="/', $content), "No native title tooltips in {$view}");
            $this->assertFalse(str_contains($content, 'meet.google.com'), "No Google Meet links in {$view}");
            $this->assertFalse((bool)preg_match('/border-left:\s*[2-9]px\s+solid/i', $content), "No coloured side strips in {$view}");
        }
    }

    public function testInterviewRoutesAreRegistered(): void
    {
        $routes = (string)file_get_contents(dirname(__DIR__, 2) . '/backend/routes.php');
        foreach (["'/employer/interviews/{id}/report'", "'/candidate/interview/{id}/report'", "'/admin/interviews/{id}'", "'/admin/api/interviews/live-map'", "'/admin/interviews/schedule'", "/{id}/files/{fileId}'", "/{id}/scorecard'"] as $needle) {
            $this->assertStringContains($needle, $routes, "Route {$needle} must be registered");
        }
    }
}
