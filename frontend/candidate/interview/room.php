<?php
declare(strict_types=1);
/**
 * View: Candidate In-Platform Video Interview Room
 * Route: /candidate/interview/room/{id}
 * Layout: dashboard
 *
 * Video, lobby, controls and chat: frontend/shared/components/video-room.php
 * Drawer (agenda, Jenny AI tips): frontend/candidate/interview/partials/room-drawer.php
 * Spec: docs/47-video-interviews.md
 *
 * @var array $room Room details from VideoInterviewService::getRoomDetails()
 */

$interviewId = (int)$room['id'];
$selfName = $_SESSION['profile_name'] ?? $room['candidate_name'];
$vrConfig = [
    'role'        => 'candidate',
    'joinUrl'     => url('/candidate/interview/room/' . $interviewId . '/join'),
    'leftUrl'     => url('/candidate/interview/room/' . $interviewId . '/left'),
    'filesUrl'    => url('/candidate/interview/room/' . $interviewId . '/files'),
    'chatUrl'     => url('/candidate/interview/room/' . $interviewId . '/chat'),
    'eventUrl'    => url('/candidate/interview/room/' . $interviewId . '/event'),
    'reportUrl'   => url('/candidate/interview/' . $interviewId . '/report'),
    'exitUrl'     => url('/candidate/interview/invite'),
    'exitLabel'   => 'Back to invitation',
    'selfName'    => $selfName,
    'peerName'    => $room['interviewer_name'],
    'subtitle'    => '<strong>' . htmlspecialchars($room['company_name']) . '</strong> &middot; with ' . htmlspecialchars($room['interviewer_name']) . ' &middot; ' . htmlspecialchars($room['time']),
    'lobbyKicker' => $room['company_name'] . ' interview',
    'lobbyTitle'  => 'Get ready to join',
    'lobbyText'   => 'Check that you look and sound right. ' . $room['interviewer_name'] . ' will let you know when the interview starts. Your video stays inside InternConnect.',
    'joinLabel'   => 'Join interview',
    'drawerTitle' => 'Interview guide',
    'tabs'        => [
        ['key' => 'agenda', 'label' => 'Agenda'],
        ['key' => 'tips',   'label' => 'Jenny AI tips'],
    ],
    'defaultTab'  => 'agenda',
];
$vrDrawerView = __DIR__ . '/partials/room-drawer.php';
?>
<div class="db-shell">

    <!-- Sidebar Overlay (mobile) -->
    <div class="db-sidebar-overlay" id="db-sidebar-overlay"></div>

    <!-- ══ SIDEBAR ══ -->
    <?php
    $activeNav = 'applications';
    include dirname(__DIR__, 2) . '/shared/components/candidate-sidebar.php';
    ?>

    <!-- ══ MAIN ══ -->
    <div class="db-main">

        <!-- ══ TOP NAVBAR ══ -->
        <?php include dirname(__DIR__, 2) . '/shared/components/candidate-topnav.php'; ?>

        <div class="db-content" id="db-content" style="padding-bottom: 24px;">
            <?php include dirname(__DIR__, 2) . '/shared/components/video-room.php'; ?>
        </div>

        <!-- Dashboard Footer -->
        <?php include dirname(__DIR__, 2) . '/shared/components/db-footer.php'; ?>

    </div>
</div>