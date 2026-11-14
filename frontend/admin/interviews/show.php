<?php
declare(strict_types=1);
/**
 * Admin: single interview session (brief, timeline with locations, scores, AI summary, files)
 * Route: /admin/interviews/{id}
 * Body: frontend/shared/components/interview-report.php (audience = admin: no chat content, no private notes)
 *
 * @var array      $staff
 * @var array|null $report
 * @var string     $fileBase
 * @var string     $aiUrl
 */
$audience = 'admin';
$backUrl = url('/admin/interviews');
$roomUrl = url('/admin/interviews/' . (int)($report['interview']['id'] ?? 0) . '/room');
?>
<div class="admin-shell">
    <?php include dirname(__DIR__, 2) . '/shared/components/admin-sidebar.php'; ?>
    <div class="admin-main-wrap">
        <?php include dirname(__DIR__, 2) . '/shared/components/admin-topnav.php'; ?>
        <main class="admin-content-body">
            <?php include dirname(__DIR__, 2) . '/shared/components/interview-report.php'; ?>
        </main>
    </div>
</div>