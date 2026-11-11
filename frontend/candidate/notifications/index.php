<?php
declare(strict_types=1);
/**
 * View: Candidate Notifications Center
 * Directory: frontend/candidate/notifications/index.php
 * Route: /candidate/notifications, /notifications
 * Layout: dashboard
 *
 * The inbox UI lives in frontend/shared/components/notification-center.php
 * (shared with the employer portal). Spec: docs/13-notification-system.md
 *
 * @var array  $candidate        Candidate profile info
 * @var array  $notifications    Items from CommunicationService::presentNotifications()
 * @var array  $notifCategories  From CommunicationService::notificationCategories('candidate')
 */

$notifConfig = [
    'subtitle'    => 'Application updates, interview schedules, offers and messages in one place.',
    'markReadUrl' => url('/candidate/notifications/mark-read'),
    'markAllUrl'  => url('/candidate/notifications/mark-all-read'),
    'deleteUrl'   => url('/candidate/notifications/delete'),
    'settingsUrl' => url('/candidate/profile') . '#notification-preferences',
    'perPage'     => 15,
];
?>
<div class="db-shell">

    <!-- Sidebar Overlay (mobile) -->
    <div class="db-sidebar-overlay" id="db-sidebar-overlay"></div>

    <!-- ══ SIDEBAR ══ -->
    <?php
    $activeNav = 'notifications';
    include dirname(__DIR__, 2) . '/shared/components/candidate-sidebar.php';
    ?>

    <!-- ══ MAIN ══ -->
    <div class="db-main">

        <!-- ══ TOP NAVBAR ══ -->
        <?php include dirname(__DIR__, 2) . '/shared/components/candidate-topnav.php'; ?>

        <div class="db-content" id="db-content">
            <?php include dirname(__DIR__, 2) . '/shared/components/notification-center.php'; ?>
        </div><!-- /.db-content -->

        <!-- Dashboard Footer -->
        <?php include dirname(__DIR__, 2) . '/shared/components/db-footer.php'; ?>

    </div><!-- /.db-main -->
</div><!-- /.db-shell -->