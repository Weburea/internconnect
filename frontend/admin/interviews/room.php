<?php
declare(strict_types=1);
/**
 * Admin: join an interview room as a host (any time, no join window).
 * Route: /admin/interviews/{id}/room
 * Video, lobby, controls and chat: frontend/shared/components/video-room.php
 * Drawer (session brief): frontend/admin/interviews/partials/room-drawer.php
 * Spec: docs/47-video-interviews.md
 *
 * @var array $room  Room details from VideoInterviewService::getRoomDetails()
 * @var array $staff
 */

$interviewId = (int)$room['id'];
$adminBase = '/admin/interviews/' . $interviewId;
$vrConfig = [
    'role'        => 'admin',
    'joinUrl'     => url($adminBase . '/room/join'),
    'leftUrl'     => url($adminBase . '/room/left'),
    'endUrl'      => url($adminBase . '/end'),
    'filesUrl'    => url($adminBase . '/room/files'),
    'chatUrl'     => url($adminBase . '/room/chat'),
    'eventUrl'    => url($adminBase . '/room/event'),
    'reportUrl'   => url($adminBase),
    'exitUrl'     => url('/admin/interviews'),
    'exitLabel'   => 'Back to interviews',
    'selfName'    => 'InternConnect Admin',
    'peerName'    => $room['candidate_name'],
    'subtitle'    => '<strong>' . htmlspecialchars($room['company_name']) . '</strong> &middot; ' . htmlspecialchars($room['candidate_name']) . ' &middot; ' . htmlspecialchars($room['time']),
    'lobbyKicker' => 'Admin · Interview #' . $interviewId,
    'lobbyTitle'  => 'Check your camera and microphone',
    'lobbyText'   => 'You join as an InternConnect host. You can open this room at any time, before the employer or candidate arrive, and end it for everyone.',
    'joinLabel'   => 'Join as admin',
    'drawerTitle' => 'Session',
    'tabs'        => [
        ['key' => 'session', 'label' => 'Session'],
    ],
    'defaultTab'  => 'session',
];
$vrDrawerView = __DIR__ . '/partials/room-drawer.php';
?>
<div class="admin-shell">
    <?php include dirname(__DIR__, 2) . '/shared/components/admin-sidebar.php'; ?>
    <div class="admin-main-wrap">
        <?php include dirname(__DIR__, 2) . '/shared/components/admin-topnav.php'; ?>
        <main class="admin-content-body">
            <?php include dirname(__DIR__, 2) . '/shared/components/video-room.php'; ?>
        </main>
    </div>
</div>
