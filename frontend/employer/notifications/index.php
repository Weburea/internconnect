<?php
declare(strict_types=1);
/**
 * View: Employer Notifications Center
 * Directory: frontend/employer/notifications/index.php
 * Route: /employer/notifications
 * Layout: dashboard
 *
 * The inbox UI lives in frontend/shared/components/notification-center.php
 * (shared with the candidate portal). Spec: docs/13-notification-system.md
 *
 * @var array  $employer         Employer info
 * @var array  $notifications    Items from CommunicationService::presentNotifications()
 * @var array  $notifCategories  From CommunicationService::notificationCategories('employer')
 */

$notifConfig = [
    'subtitle'    => 'New applications, interview confirmations, candidate messages and billing updates.',
    'markReadUrl' => url('/employer/notifications/mark-read'),
    'markAllUrl'  => url('/employer/notifications/mark-all-read'),
    'deleteUrl'   => url('/employer/notifications/delete'),
    'settingsUrl' => url('/employer/profile') . '#notification-preferences',
    'perPage'     => 15,
];
?>
<div class="db-shell">

    <!-- Mobile Drawer Overlay -->
    <div class="db-sidebar-overlay" id="db-sidebar-overlay"></div>

    <!-- ══ SIDEBAR ══ -->
    <?php
    $activeNav = 'notifications';
    include dirname(__DIR__, 2) . '/shared/components/employer-sidebar.php';
    ?>

    <!-- ══ MAIN AREA ══ -->
    <div class="db-main">

        <!-- ══ TOP NAVBAR ══ -->
        <?php include dirname(__DIR__, 2) . '/shared/components/employer-topnav.php'; ?>

        <div class="db-content" id="db-content">
            <?php include dirname(__DIR__, 2) . '/shared/components/notification-center.php'; ?>
        </div>

        <!-- Footer -->
        <?php include dirname(__DIR__, 2) . '/shared/components/db-footer.php'; ?>

    </div>
</div>