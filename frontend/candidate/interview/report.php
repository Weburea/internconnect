<?php
declare(strict_types=1);
/**
 * View: Candidate interview report (history)
 * Route: /candidate/interview/{id}/report
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
    $activeNav = 'applications';
    include dirname(__DIR__, 2) . '/shared/components/candidate-sidebar.php';
    ?>
    <div class="db-main">
        <?php include dirname(__DIR__, 2) . '/shared/components/candidate-topnav.php'; ?>
        <div class="db-content" id="db-content">
            <?php include dirname(__DIR__, 2) . '/shared/components/interview-report.php'; ?>
        </div>
        <?php include dirname(__DIR__, 2) . '/shared/components/db-footer.php'; ?>
    </div>
</div>