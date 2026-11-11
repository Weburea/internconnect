<?php
declare(strict_types=1);

namespace Tests\Unit;

use Tests\TestCase;
use App\Controllers\CandidateController;
use App\Controllers\EmployerController;

class CommunicationFeaturesTest extends TestCase
{
    private CandidateController $candidateController;
    private EmployerController $employerController;

    public function __construct()
    {
        $this->candidateController = new CandidateController();
        $this->employerController = new EmployerController();
    }

    public function testCommunicationControllerMethodsExist(): void
    {
        // Candidate communication methods
        $this->assertTrue(method_exists($this->candidateController, 'messages'), 'CandidateController must have messages method');
        $this->assertTrue(method_exists($this->candidateController, 'notifications'), 'CandidateController must have notifications method');
        $this->assertTrue(method_exists($this->candidateController, 'viewInterviewInvite'), 'CandidateController must have viewInterviewInvite method');
        $this->assertTrue(method_exists($this->candidateController, 'interviewGuide'), 'CandidateController must have interviewGuide method');

        // Employer communication methods
        $this->assertTrue(method_exists($this->employerController, 'messages'), 'EmployerController must have messages method');
        $this->assertTrue(method_exists($this->employerController, 'notifications'), 'EmployerController must have notifications method');
        $this->assertTrue(method_exists($this->employerController, 'interviews'), 'EmployerController must have interviews method');
        $this->assertTrue(method_exists($this->employerController, 'scheduleInterview'), 'EmployerController must have scheduleInterview method');
    }

    public function testCommunicationUrlRoutes(): void
    {
        $this->assertStringContains('/candidate/messages', url('/candidate/messages'));
        $this->assertStringContains('/candidate/notifications', url('/candidate/notifications'));
        $this->assertStringContains('/candidate/interview', url('/candidate/interview'));
        $this->assertStringContains('/candidate/interview/invite', url('/candidate/interview/invite'));
        $this->assertStringContains('/candidate/interview/guide', url('/candidate/interview/guide'));

        $this->assertStringContains('/employer/messages', url('/employer/messages'));
        $this->assertStringContains('/employer/notifications', url('/employer/notifications'));
        $this->assertStringContains('/employer/interviews', url('/employer/interviews'));
        $this->assertStringContains('/employer/interviews/schedule', url('/employer/interviews/schedule'));
    }

    public function testZeroColoredSideBordersInCommunicationViews(): void
    {
        $commFiles = [
            dirname(__DIR__, 2) . '/frontend/candidate/messages/index.php',
            dirname(__DIR__, 2) . '/frontend/candidate/notifications/index.php',
            dirname(__DIR__, 2) . '/frontend/candidate/interview/invite.php',
            dirname(__DIR__, 2) . '/frontend/candidate/interview/guide.php',
            dirname(__DIR__, 2) . '/frontend/employer/messages/index.php',
            dirname(__DIR__, 2) . '/frontend/employer/notifications/index.php',
            dirname(__DIR__, 2) . '/frontend/employer/interviews/index.php',
            dirname(__DIR__, 2) . '/frontend/employer/interviews/schedule.php',
        ];

        foreach ($commFiles as $file) {
            $this->assertTrue(file_exists($file), "Communication view file must exist: {$file}");
            $content = (string)file_get_contents($file);

            $this->assertFalse(
                (bool)preg_match('/border-left:\s*[2-9]px\s+solid\s+(?!#[eE]2[eE]8[fF]0)/', $content),
                "No colored accent border-left strips permitted in {$file}"
            );
            $this->assertFalse(
                (bool)preg_match('/border-top:\s*[2-9]px\s+solid\s+#(?!E2E8F0|EDE9FE|F1F5F9)/i', $content),
                "No colored accent border-top strips permitted in {$file}"
            );
        }
    }

    public function testZeroNativeAlertsInCommunicationViews(): void
    {
        $commFiles = [
            dirname(__DIR__, 2) . '/frontend/candidate/messages/index.php',
            dirname(__DIR__, 2) . '/frontend/candidate/notifications/index.php',
            dirname(__DIR__, 2) . '/frontend/candidate/interview/invite.php',
            dirname(__DIR__, 2) . '/frontend/candidate/interview/guide.php',
            dirname(__DIR__, 2) . '/frontend/employer/messages/index.php',
            dirname(__DIR__, 2) . '/frontend/employer/notifications/index.php',
            dirname(__DIR__, 2) . '/frontend/employer/interviews/index.php',
            dirname(__DIR__, 2) . '/frontend/employer/interviews/schedule.php',
        ];

        foreach ($commFiles as $file) {
            $content = (string)file_get_contents($file);
            $this->assertFalse(str_contains($content, 'alert('), "Native alert() prohibited in {$file}");
            $this->assertFalse(str_contains($content, 'confirm('), "Native confirm() prohibited in {$file}");
            $this->assertFalse(str_contains($content, 'prompt('), "Native prompt() prohibited in {$file}");
        }
    }

    public function testSingleLineButtonIntegrityInCommunicationViews(): void
    {
        $schedFile = (string)file_get_contents(dirname(__DIR__, 2) . '/frontend/employer/interviews/schedule.php');
        $this->assertStringContains(
            'flex-shrink: 0;',
            $schedFile,
            'Buttons and icons in schedule.php must enforce flex-shrink: 0'
        );

        $inviteFile = (string)file_get_contents(dirname(__DIR__, 2) . '/frontend/candidate/interview/invite.php');
        $this->assertStringContains(
            'flex-shrink: 0;',
            $inviteFile,
            'Buttons and icons in invite.php must enforce flex-shrink: 0'
        );
    }

    public function testCommunicationNewActionMethodsExist(): void
    {
        $this->assertTrue(method_exists($this->candidateController, 'sendMessage'), 'CandidateController must have sendMessage method');
        $this->assertTrue(method_exists($this->candidateController, 'markNotificationRead'), 'CandidateController must have markNotificationRead method');
        $this->assertTrue(method_exists($this->candidateController, 'markAllNotificationsRead'), 'CandidateController must have markAllNotificationsRead method');
        $this->assertTrue(method_exists($this->candidateController, 'respondInterview'), 'CandidateController must have respondInterview method');

        $this->assertTrue(method_exists($this->employerController, 'sendMessage'), 'EmployerController must have sendMessage method');
        $this->assertTrue(method_exists($this->employerController, 'markNotificationRead'), 'EmployerController must have markNotificationRead method');
        $this->assertTrue(method_exists($this->employerController, 'markAllNotificationsRead'), 'EmployerController must have markAllNotificationsRead method');
        $this->assertTrue(method_exists($this->employerController, 'processScheduleInterview'), 'EmployerController must have processScheduleInterview method');
    }

    public function testDirectMessageDispatchAndRetrieval(): void
    {
        // 1. Candidate sends direct message to thread 1
        $msg = \App\Services\CommunicationService::sendDirectMessage(
            1,
            'candidate',
            'Sarah Peters',
            'Hello team, confirming I am ready for the technical session.'
        );

        $this->assertTrue(is_array($msg), 'sendDirectMessage must return an array');
        $this->assertEquals('candidate', $msg['sender']);
        $this->assertEquals('Sarah Peters', $msg['sender_name']);
        $this->assertStringContains('technical session', $msg['text']);

        // 2. Verify thread retrieval includes the new message
        $thread = \App\Services\CommunicationService::getThreadMessages(1);
        $this->assertTrue(count($thread) > 0, 'Thread messages must not be empty');

        $found = false;
        foreach ($thread as $m) {
            if (str_contains($m['text'] ?? '', 'technical session')) {
                $found = true;
                break;
            }
        }
        $this->assertTrue($found, 'Sent message must be found in thread messages');

        // 3. Verify notification automatically dispatched to employer
        $employerNotifs = \App\Services\CommunicationService::getNotifications('employer');
        $notifFound = false;
        foreach ($employerNotifs as $n) {
            if (str_contains($n['title'] ?? '', 'Sarah Peters') || str_contains($n['message'] ?? '', 'technical session')) {
                $notifFound = true;
                break;
            }
        }
        $this->assertTrue($notifFound, 'Employer must receive in-app notification when candidate sends message');
    }

    public function testNotificationLifecycle(): void
    {
        // Create candidate notification
        $notif = \App\Services\CommunicationService::createNotification(
            'candidate',
            1,
            'Test Offer Extended',
            'You received an offer from Acme Corp.',
            'offer',
            '/candidate/offers'
        );

        $this->assertTrue(is_array($notif), 'createNotification must return array');
        $this->assertEquals('Test Offer Extended', $notif['title']);
        $this->assertEquals(0, $notif['is_read']);

        // Mark single notification read
        $readOk = \App\Services\CommunicationService::markNotificationRead((int)$notif['id'], 'candidate');
        $this->assertTrue($readOk, 'markNotificationRead must succeed');

        // Mark all read
        $markAllOk = \App\Services\CommunicationService::markAllNotificationsRead('candidate');
        $this->assertTrue($markAllOk, 'markAllNotificationsRead must succeed');

        $unreadCount = \App\Services\CommunicationService::getUnreadNotificationCount('candidate');
        $this->assertEquals(0, $unreadCount, 'Unread notification count must be 0 after marking all read');
    }

    public function testInterviewSchedulingAndCandidateNotification(): void
    {
        // 1. Employer schedules an interview
        $interviewData = [
            'candidate_id'     => 1,
            'employer_id'      => 1,
            'candidate_name'   => 'Sarah Peters',
            'candidate_role'   => 'Senior UI/UX Intern',
            'company_name'     => 'Nexura Tech Labs',
            'interviewer_name' => 'Tunde Bakare',
            'meeting_type'     => 'virtual',
            'meeting_link'     => 'https://meet.google.com/test-room',
            'date'             => '10/28/2026',
            'time'             => '03:00 PM',
            'duration'         => '45 mins',
            'notes'            => 'Prepare portfolio walkthrough',
        ];

        $interview = \App\Services\CommunicationService::scheduleInterview($interviewData);
        $this->assertTrue(is_array($interview), 'scheduleInterview must return array');
        $this->assertEquals('Scheduled', $interview['status']);
        $this->assertEquals('Nexura Tech Labs', $interview['company_name']);

        // 2. Verify candidate received notification
        $candNotifs = \App\Services\CommunicationService::getNotifications('candidate');
        $inviteNotifFound = false;
        foreach ($candNotifs as $n) {
            if (str_contains($n['title'] ?? '', 'Nexura Tech Labs') || str_contains($n['message'] ?? '', 'Nexura Tech Labs')) {
                $inviteNotifFound = true;
                break;
            }
        }
        $this->assertTrue($inviteNotifFound, 'Candidate must receive in-app notification when employer schedules interview');

        // 3. Candidate confirms interview
        $updated = \App\Services\CommunicationService::updateInterviewStatus(
            (int)$interview['id'],
            'Confirmed',
            ['candidate_name' => 'Sarah Peters']
        );
        $this->assertEquals('Confirmed', $updated['status']);

        // 4. Verify employer received confirmation notification
        $employerNotifs = \App\Services\CommunicationService::getNotifications('employer');
        $confFound = false;
        foreach ($employerNotifs as $n) {
            if (str_contains($n['title'] ?? '', 'Confirmed') || str_contains($n['message'] ?? '', 'Sarah Peters accepted')) {
                $confFound = true;
                break;
            }
        }
        $this->assertTrue($confFound, 'Employer must receive notification when candidate confirms interview');
    }

    public function testVideoInterviewControllerMethodsAndRoutes(): void
    {
        $this->assertTrue(method_exists($this->employerController, 'interviewRoom'), 'EmployerController must have interviewRoom method');
        $this->assertTrue(method_exists($this->employerController, 'saveInterviewScore'), 'EmployerController must have saveInterviewScore method');
        $this->assertTrue(method_exists($this->candidateController, 'interviewRoom'), 'CandidateController must have interviewRoom method');

        $this->assertStringContains('/employer/interviews/room', url('/employer/interviews/room'));
        $this->assertStringContains('/candidate/interview/room', url('/candidate/interview/room'));
        $this->assertStringContains('/api/interviews/room/score', url('/api/interviews/room/score'));
    }

    public function testVideoInterviewServiceOperations(): void
    {
        // 1. Get room details for employer
        $employerRoom = \App\Services\VideoInterviewService::getRoomDetails(1, 'employer');
        $this->assertIsArray($employerRoom, 'getRoomDetails must return an array');
        $this->assertEquals('Nexura Technologies', $employerRoom['company_name']);
        $this->assertEquals('Sarah Peters', $employerRoom['candidate_name']);
        $this->assertStringContains('https://internconnect.daily.co/', $employerRoom['daily_room_url']);
        $this->assertArrayHasKey('rubric', $employerRoom);
        $this->assertArrayHasKey('portfolio_links', $employerRoom);
        $this->assertArrayHasKey('screening_questions', $employerRoom);

        // 2. Get room details for candidate
        $candidateRoom = \App\Services\VideoInterviewService::getRoomDetails(1, 'candidate');
        $this->assertIsArray($candidateRoom, 'getRoomDetails for candidate must return an array');
        $this->assertArrayHasKey('jenny_ai_tips', $candidateRoom);

        // 3. Save evaluation score
        $evalSaved = \App\Services\VideoInterviewService::saveEvaluation(1, [
            'technical_score'        => 5,
            'problem_solving_score'   => 5,
            'communication_score'    => 5,
            'culture_score'          => 4,
            'overall_recommendation' => 'strong_hire',
            'notes'                  => 'Demonstrated exceptional design thinking and clear user empathy.'
        ]);
        $this->assertIsArray($evalSaved, 'saveEvaluation must return array');

        // 4. Retrieve saved evaluation
        $eval = \App\Services\VideoInterviewService::getEvaluation(1);
        $this->assertIsArray($eval, 'getEvaluation must return an array');
        $this->assertEquals('strong_hire', $eval['overall_recommendation']);
        $this->assertEquals(5, $eval['technical_score']);
        $this->assertStringContains('exceptional design thinking', $eval['notes']);
    }

    public function testVideoInterviewViewsZeroColoredBordersAndZeroNativeAlerts(): void
    {
        $videoViews = [
            dirname(__DIR__, 2) . '/frontend/employer/interviews/room.php',
            dirname(__DIR__, 2) . '/frontend/candidate/interview/room.php',
        ];

        foreach ($videoViews as $file) {
            $this->assertTrue(file_exists($file), "Video room view file must exist: {$file}");
            $content = (string)file_get_contents($file);

            // Zero native alert/confirm/prompt
            $this->assertFalse(str_contains($content, 'alert('), "Native alert() prohibited in {$file}");
            $this->assertFalse(str_contains($content, 'confirm('), "Native confirm() prohibited in {$file}");
            $this->assertFalse(str_contains($content, 'prompt('), "Native prompt() prohibited in {$file}");

            // Zero colored accent border-left strips
            $this->assertFalse(
                (bool)preg_match('/border-left:\s*[2-9]px\s+solid\s+(?!#[eE]2[eE]8[fF]0)/', $content),
                "No colored accent border-left strips permitted in {$file}"
            );
            $this->assertFalse(
                (bool)preg_match('/border-top:\s*[2-9]px\s+solid\s+#(?!E2E8F0|EDE9FE|F1F5F9)/i', $content),
                "No colored accent border-top strips permitted in {$file}"
            );
        }
    }
}

