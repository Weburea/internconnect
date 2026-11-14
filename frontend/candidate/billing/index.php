<?php
declare(strict_types=1);
/**
 * View: Candidate Billing & Subscription
 * Route: /candidate/billing
 * Layout: dashboard
 * Body: frontend/shared/components/billing-center.php (shared by candidate and employer)
 *
 * @var array $billing BillingService::overview()
 * @var array $account ['type', 'id']
 */
?>
<div class="db-shell">

    <!-- Sidebar Overlay (mobile) -->
    <div class="db-sidebar-overlay" id="db-sidebar-overlay"></div>

    <!-- ══ SIDEBAR ══ -->
    <?php
    $activeNav = 'billing';
    include dirname(__DIR__, 2) . '/shared/components/candidate-sidebar.php';
    ?>

    <!-- ══ MAIN ══ -->
    <div class="db-main">

        <!-- ══ TOP NAVBAR ══ -->
        <?php include dirname(__DIR__, 2) . '/shared/components/candidate-topnav.php'; ?>

        <div class="db-content" id="db-content">
            <?php include dirname(__DIR__, 2) . '/shared/components/billing-center.php'; ?>
        </div><!-- /.db-content -->

        <!-- Dashboard Footer -->
        <?php include dirname(__DIR__, 2) . '/shared/components/db-footer.php'; ?>

    </div><!-- /.db-main -->
</div><!-- /.db-shell -->
