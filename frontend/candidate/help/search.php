<?php
declare(strict_types=1);
/**
 * View: Help Center Search Results
 * Route: /help/search
 * Layout: dashboard
 * 
 * @var array  $candidate   Candidate profile data
 * @var string $query       Search keyword string
 * @var int    $count       Total match count
 * @var array  $results     List of matched results
 */

$name      = $candidate['name'] ?? ($_SESSION['profile_name'] ?? 'Sarah Peters');
$firstName = explode(' ', trim($name))[0];
$role      = $candidate['role'] ?? ($_SESSION['profile_role'] ?? 'Student Applicant');
$avatar    = $candidate['avatar'] ?? null;
?>

<div class="db-shell">

    <!-- Sidebar Overlay (mobile) -->
    <div class="db-sidebar-overlay" id="db-sidebar-overlay"></div>

    <!-- ══ SIDEBAR ══ -->
    <?php 
    $activeNav = 'help';
    include dirname(__DIR__, 2) . '/shared/components/candidate-sidebar.php'; 
    ?>

    <!-- ══ MAIN ══ -->
    <div class="db-main">

        <!-- ══ TOP NAVBAR ══ -->
        <?php include dirname(__DIR__, 2) . '/shared/components/candidate-topnav.php'; ?>

        <!-- Scrollable Content -->
        <div class="db-content" id="db-content">

            <style>
            /* ─── Breadcrumb Navigation ─── */
            .help-breadcrumb {
                display: flex;
                align-items: center;
                gap: 8px;
                font-size: 13px;
                color: #64748B;
                margin-bottom: 24px;
            }

            .help-breadcrumb a {
                color: #4F46E5;
                text-decoration: none;
                font-weight: 500;
            }

            .help-breadcrumb a:hover {
                text-decoration: underline;
            }

            .help-breadcrumb-sep {
                color: #CBD5E1;
            }

            .help-breadcrumb-current {
                color: #64748B;
                font-weight: 500;
            }

            /* ─── Hero Purple Banner ─── */
            .help-hero-banner {
                position: relative;
                background: linear-gradient(135deg, #4F46E5 0%, #6366F1 50%, #4338CA 100%);
                border-radius: 20px;
                padding: 36px 36px 32px 36px;
                color: #FFFFFF;
                overflow: hidden;
                box-shadow: 0 10px 25px -5px rgba(79, 70, 229, 0.25);
                margin-bottom: 32px;
            }

            .help-hero-banner::before {
                content: '';
                position: absolute;
                top: -50%;
                right: -10%;
                width: 460px;
                height: 460px;
                background: radial-gradient(circle, rgba(255, 255, 255, 0.12) 0%, rgba(255, 255, 255, 0) 70%);
                border-radius: 50%;
                pointer-events: none;
            }

            .help-pill-badge {
                display: inline-block;
                background: #FFFFFF;
                color: #4F46E5;
                font-size: 11px;
                font-weight: 800;
                letter-spacing: 0.08em;
                text-transform: uppercase;
                padding: 5px 14px;
                border-radius: 999px;
                box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
                margin-bottom: 12px;
                white-space: nowrap;
                flex-shrink: 0;
            }

            .help-hero-title {
                font-size: 24px;
                font-weight: 700;
                color: #FFFFFF;
                margin: 0 0 20px 0;
                letter-spacing: -0.01em;
            }

            .help-search-form {
                position: relative;
                max-width: 100%;
                z-index: 2;
            }

            .help-search-input-wrap {
                position: relative;
                display: flex;
                align-items: center;
            }

            .help-search-icon {
                position: absolute;
                left: 18px;
                width: 18px;
                height: 18px;
                color: #64748B;
                pointer-events: none;
            }

            .help-search-input {
                width: 100%;
                height: 52px;
                background: #FFFFFF;
                border: 2px solid transparent;
                border-radius: 12px;
                padding: 0 20px 0 50px;
                font-size: 15px;
                color: #0F172A;
                font-family: inherit;
                box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
                outline: none;
                transition: all 0.2s ease;
            }

            .help-search-input:focus {
                border-color: #818CF8;
                box-shadow: 0 12px 30px -5px rgba(0, 0, 0, 0.18);
            }

            /* ─── Search Results Heading ─── */
            .help-search-header {
                margin-bottom: 24px;
            }

            .help-search-title {
                font-size: 22px;
                font-weight: 800;
                color: #0F172A;
                margin: 0 0 6px 0;
            }

            .help-search-count {
                font-size: 14px;
                color: #64748B;
                margin: 0;
            }

            /* ─── Search Results List ─── */
            .help-results-list {
                display: flex;
                flex-direction: column;
                gap: 16px;
                margin-bottom: 40px;
            }

            .help-result-card {
                background: #FFFFFF;
                border: 1px solid #E2E8F0;
                border-radius: 14px;
                padding: 22px 24px;
                text-decoration: none;
                display: block;
                transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
            }

            .help-result-card:hover {
                border-color: #818CF8;
                transform: translateY(-1px);
                box-shadow: 0 8px 20px -4px rgba(79, 70, 229, 0.08);
            }

            .help-result-title {
                font-size: 17px;
                font-weight: 700;
                color: #0F172A;
                margin: 0 0 8px 0;
                line-height: 1.35;
            }

            .help-result-snippet {
                font-size: 14px;
                color: #475569;
                line-height: 1.55;
                margin: 0 0 14px 0;
            }

            .help-result-category {
                font-size: 13px;
                font-weight: 700;
                color: #4F46E5;
                display: inline-block;
            }

            /* ─── Empty State ─── */
            .help-empty-card {
                background: #FFFFFF;
                border: 1px dashed #CBD5E1;
                border-radius: 16px;
                padding: 40px 24px;
                text-align: center;
                margin-bottom: 40px;
            }

            .help-empty-icon {
                width: 48px;
                height: 48px;
                color: #94A3B8;
                margin: 0 auto 14px auto;
            }

            .help-empty-title {
                font-size: 18px;
                font-weight: 700;
                color: #1E293B;
                margin: 0 0 8px 0;
            }

            .help-empty-desc {
                font-size: 14px;
                color: #64748B;
                max-width: 460px;
                margin: 0 auto 20px auto;
                line-height: 1.5;
            }

            .help-back-btn {
                display: inline-flex;
                align-items: center;
                gap: 6px;
                padding: 10px 20px;
                background: #4F46E5;
                color: #FFFFFF;
                border-radius: 10px;
                font-size: 13.5px;
                font-weight: 600;
                text-decoration: none;
                transition: background 0.15s ease;
            }

            .help-back-btn:hover {
                background: #4338CA;
            }

            /* Footer */
            .help-footer-wrap {
                margin-top: 40px;
                padding-top: 20px;
                border-top: 1px solid #F1F5F9;
                font-size: 12.5px;
                color: #94A3B8;
            }
            </style>

            <!-- ── Breadcrumb Navigation (Image 2) ── -->
            <nav class="help-breadcrumb" aria-label="Breadcrumb">
                <a href="<?= url('/help') ?>">Help Center</a>
                <span class="help-breadcrumb-sep">/</span>
                <span class="help-breadcrumb-current">Search</span>
            </nav>

            <!-- ── Hero Banner with Active Search Input ── -->
            <section class="help-hero-banner" aria-label="Help Search">
                <span class="help-pill-badge">HELP CENTER</span>
                <div class="help-hero-title">How can we help you, <?= htmlspecialchars($firstName) ?>?</div>

                <form action="<?= url('/help/search') ?>" method="GET" class="help-search-form">
                    <div class="help-search-input-wrap">
                        <svg class="help-search-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <input type="text" 
                               name="q" 
                               class="help-search-input" 
                               placeholder="Search for answers..." 
                               value="<?= htmlspecialchars($query) ?>" 
                               required 
                               autocomplete="off">
                    </div>
                </form>
            </section>

            <!-- ── Search Results Header ── -->
            <div class="help-search-header">
                <h1 class="help-search-title">Search results for &ldquo;<?= htmlspecialchars($query) ?>&rdquo;</h1>
                <p class="help-search-count"><?= $count ?> result<?= $count === 1 ? '' : 's' ?> found</p>
            </div>

            <!-- ── Results List (Image 2) ── -->
            <?php if (!empty($results)): ?>
                <div class="help-results-list">
                    <?php foreach ($results as $res): ?>
                    <a href="<?= url('/help/article/' . $res['slug']) ?>" class="help-result-card">
                        <div class="help-result-title"><?= $res['highlighted_title'] ?></div>
                        <div class="help-result-snippet"><?= $res['highlighted_snippet'] ?></div>
                        <div class="help-result-category"><?= htmlspecialchars($res['category']) ?></div>
                    </a>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="help-empty-card">
                    <svg class="help-empty-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <h3 class="help-empty-title">No matching articles found</h3>
                    <p class="help-empty-desc">We couldn't find any help articles matching &ldquo;<?= htmlspecialchars($query) ?>&rdquo;. Try searching for general terms like &ldquo;Quick Apply&rdquo;, &ldquo;Interview&rdquo;, or browse our categories.</p>
                    <a href="<?= url('/help') ?>" class="help-back-btn">
                        <span>&larr;</span>
                        <span>Browse Help Categories</span>
                    </a>
                </div>
            <?php endif; ?>

            <!-- ── Footer ── -->
            <div class="help-footer-wrap">
                &copy; <?= date('Y') ?> InternConnect. All rights reserved.
            </div>

        </div> <!-- /db-content -->
    </div> <!-- /db-main -->

</div> <!-- /db-shell -->
