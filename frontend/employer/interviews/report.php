<?php
declare(strict_types=1);
/**
 * View: Employer interview report (history)
 * Route: /employer/interviews/{id}/report
 * Body: frontend/shared/components/interview-report.php
 *
 * @var array|null $report
 * @var string     $audience
 * @var string     $fileBase
 * @var string     $aiUrl
 * @var string     $backUrl
 * @var string     $roomUrl
 */
?>
<div class="db-shell">
    <div class="db-sidebar-overlay" id="db-sidebar-overlay"></div>
    <?php
    $activeNav = 'interviews';
    include dirname(__DIR__, 2) . '/shared/components/employer-sidebar.php';
    ?>
    <div class="db-main">
        <?php include dirname(__DIR__, 2) . '/shared/components/employer-topnav.php'; ?>
        <div class="db-content" id="db-content">
            <?php include dirname(__DIR__, 2) . '/shared/components/interview-report.php'; ?>
        </div>
        <?php include dirname(__DIR__, 2) . '/shared/components/db-footer.php'; ?>
    </div>
</div>