<?php
declare(strict_types=1);
/**
 * View: Employer In-Platform Video Interview Room
 * Route: /employer/interviews/room/{id}
 * Layout: dashboard
 *
 * Video, lobby, controls and chat: frontend/shared/components/video-room.php
 * Drawer (scorecard, questions, profile): frontend/employer/interviews/partials/room-drawer.php
 * Spec: docs/47-video-interviews.md
 *
 * @var array $room Room details from VideoInterviewService::getRoomDetails()
 */

$interviewId = (int)$room['id'];
$vrConfig = [
    'role'        => 'employer',
    'joinUrl'     => url('/employer/interviews/room/' . $interviewId . '/join'),
    'leftUrl'     => url('/employer/interviews/room/' . $interviewId . '/left'),
    'endUrl'      => url('/employer/interviews/room/' . $interviewId . '/end'),
    'filesUrl'    => url('/employer/interviews/room/' . $interviewId . '/files'),
    'chatUrl'     => url('/employer/interviews/room/' . $interviewId . '/chat'),
    'eventUrl'    => url('/employer/interviews/room/' . $interviewId . '/event'),
    'reportUrl'   => url('/employer/interviews/' . $interviewId . '/report'),
    'exitUrl'     => url('/employer/interviews?tab=upcoming'),
    'exitLabel'   => 'Back to interviews',
    'selfName'    => $room['interviewer_name'],
    'peerName'    => $room['candidate_name'],
    'subtitle'    => '<strong>' . htmlspecialchars($room['candidate_name']) . '</strong> &middot; ' . htmlspecialchars($room['candidate_school']) . ' &middot; ' . htmlspecialchars($room['time']),
    'lobbyKicker' => 'Interview with ' . $room['candidate_name'],
    'lobbyTitle'  => 'Check your camera and microphone',
    'lobbyText'   => 'You join as the host. ' . $room['candidate_name'] . ' will see you as soon as you enter, and you can end the interview for everyone when you finish.',
    'joinLabel'   => 'Start interview',
    'drawerTitle' => 'Evaluation',
    'tabs'        => [
        ['key' => 'scorecard', 'label' => 'Scorecard'],
        ['key' => 'questions', 'label' => 'Questions'],
        ['key' => 'profile',   'label' => 'Profile'],
    ],
    'defaultTab'  => 'scorecard',
];
$vrDrawerView = __DIR__ . '/partials/room-drawer.php';
?>
<div class="db-shell">

    <!-- Sidebar Overlay (mobile) -->
    <div class="db-sidebar-overlay" id="db-sidebar-overlay"></div>

    <!-- ══ SIDEBAR ══ -->
    <?php
    $activeNav = 'interviews';
    include dirname(__DIR__, 2) . '/shared/components/employer-sidebar.php';
    ?>

    <!-- ══ MAIN ══ -->
    <div class="db-main">

        <!-- ══ TOP NAVBAR ══ -->
        <?php include dirname(__DIR__, 2) . '/shared/components/employer-topnav.php'; ?>

        <div class="db-content" id="db-content" style="padding-bottom: 24px;">
            <?php include dirname(__DIR__, 2) . '/shared/components/video-room.php'; ?>
        </div>

        <!-- Dashboard Footer -->
        <?php include dirname(__DIR__, 2) . '/shared/components/db-footer.php'; ?>

    </div>
</div>