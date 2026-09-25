<?php
declare(strict_types=1);
/**
 * View: Help Center Article Detail
 * Route: /help/article/{slug}
 * Layout: dashboard
 * 
 * @var array  $candidate  Candidate profile data
 * @var array  $article    Article metadata and body content
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
                flex-wrap: wrap;
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

            /* ─── Article Header ─── */
            .help-article-header {
                margin-bottom: 28px;
            }

            .help-article-title {
                font-size: 32px;
                font-weight: 800;
                color: #0F172A;
                line-height: 1.25;
                letter-spacing: -0.02em;
                margin: 0 0 16px 0;
            }

            @media (max-width: 640px) {
                .help-article-title {
                    font-size: 26px;
                }
            }

            .help-article-meta {
                display: flex;
                align-items: center;
                gap: 20px;
                font-size: 13.5px;
                color: #475569;
                font-weight: 500;
            }

            .help-meta-item {
                display: flex;
                align-items: center;
                gap: 6px;
            }

            .help-meta-icon {
                width: 16px;
                height: 16px;
                color: #64748B;
                flex-shrink: 0;
            }

            /* ─── Article Body ─── */
            .help-article-body {
                font-size: 15px;
                line-height: 1.7;
                color: #334155;
                max-width: 860px;
                margin-bottom: 40px;
            }

            .help-article-body p {
                margin: 0 0 20px 0;
            }

            .help-article-body h3 {
                font-size: 18px;
                font-weight: 700;
                color: #0F172A;
                margin: 28px 0 12px 0;
            }

            .help-article-body ul {
                margin: 0 0 24px 0;
                padding-left: 20px;
                list-style-type: disc;
            }

            .help-article-body li {
                margin-bottom: 8px;
            }

            /* ─── Related Articles ─── */
            .help-related-section {
                margin-top: 40px;
                margin-bottom: 32px;
                max-width: 860px;
            }

            .help-related-heading {
                font-size: 18px;
                font-weight: 700;
                color: #0F172A;
                margin: 0 0 16px 0;
            }

            .help-related-grid {
                display: grid;
                grid-template-columns: repeat(2, 1fr);
                gap: 16px;
            }

            @media (max-width: 640px) {
                .help-related-grid {
                    grid-template-columns: 1fr;
                }
            }

            .help-related-card {
                background: #FFFFFF;
                border: 1px solid #E2E8F0;
                border-radius: 14px;
                padding: 18px 20px;
                text-decoration: none;
                transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
                display: flex;
                flex-direction: column;
                justify-content: center;
            }

            .help-related-card:hover {
                border-color: #818CF8;
                transform: translateY(-2px);
                box-shadow: 0 6px 16px -2px rgba(79, 70, 229, 0.08);
            }

            .help-related-title {
                font-size: 14.5px;
                font-weight: 700;
                color: #0F172A;
                margin: 0 0 4px 0;
            }

            .help-related-cat {
                font-size: 12.5px;
                color: #64748B;
                margin: 0;
            }

            /* ─── Was This Article Helpful Widget ─── */
            .help-feedback-card {
                background: #FFFFFF;
                border: 1px solid #E2E8F0;
                border-radius: 14px;
                padding: 24px;
                text-align: center;
                max-width: 860px;
                margin-bottom: 24px;
            }

            .help-feedback-prompt {
                font-size: 14.5px;
                font-weight: 700;
                color: #0F172A;
                margin: 0 0 14px 0;
            }

            .help-feedback-buttons {
                display: inline-flex;
                align-items: center;
                gap: 10px;
            }

            .help-feedback-btn {
                display: inline-flex;
                align-items: center;
                gap: 6px;
                padding: 7px 20px;
                border-radius: 8px;
                border: 1px solid #CBD5E1;
                background: #FFFFFF;
                color: #334155;
                font-size: 13.5px;
                font-weight: 600;
                cursor: pointer;
                transition: all 0.15s ease;
                outline: none;
            }

            .help-feedback-btn:hover {
                background: #F8FAFC;
                border-color: #94A3B8;
            }

            .help-feedback-btn.is-active-yes {
                background: #ECFDF5;
                border-color: #10B981;
                color: #047857;
            }

            .help-feedback-btn.is-active-no {
                background: #FEF2F2;
                border-color: #EF4444;
                color: #B91C1C;
            }

            .help-feedback-thankyou {
                font-size: 14px;
                font-weight: 600;
                color: #047857;
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 6px;
            }

            /* ─── Still Need Help Banner ─── */
            .help-support-banner {
                background: #F5F7FF;
                border: 1px solid #E0E7FF;
                border-radius: 16px;
                padding: 24px 28px;
                display: flex;
                align-items: center;
                justify-content: space-between;
                max-width: 860px;
                gap: 20px;
                margin-bottom: 40px;
            }

            @media (max-width: 640px) {
                .help-support-banner {
                    flex-direction: column;
                    align-items: flex-start;
                }
            }

            .help-support-title {
                font-size: 16px;
                font-weight: 700;
                color: #1E1B4B;
                margin: 0 0 4px 0;
            }

            .help-support-desc {
                font-size: 13.5px;
                color: #475569;
                margin: 0;
                line-height: 1.5;
            }

            .help-support-btn {
                background: #4F46E5;
                color: #FFFFFF;
                border: none;
                border-radius: 10px;
                padding: 11px 24px;
                font-size: 13.5px;
                font-weight: 600;
                cursor: pointer;
                transition: background 0.15s ease, transform 0.15s ease;
                white-space: nowrap;
                flex-shrink: 0;
                box-shadow: 0 4px 12px rgba(79, 70, 229, 0.25);
                outline: none;
            }

            .help-support-btn:hover {
                background: #4338CA;
                transform: translateY(-1px);
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

            <!-- ── Breadcrumb Navigation (Image 1) ── -->
            <nav class="help-breadcrumb" aria-label="Breadcrumb">
                <a href="<?= url('/help') ?>">Help Center</a>
                <span class="help-breadcrumb-sep">/</span>
                <a href="<?= url('/help/search?q=' . urlencode($article['category'])) ?>"><?= htmlspecialchars($article['category']) ?></a>
                <span class="help-breadcrumb-sep">/</span>
                <span class="help-breadcrumb-current"><?= htmlspecialchars($article['title']) ?></span>
            </nav>

            <!-- ── Article Header ── -->
            <header class="help-article-header">
                <h1 class="help-article-title"><?= htmlspecialchars($article['title']) ?></h1>
                
                <div class="help-article-meta">
                    <div class="help-meta-item">
                        <svg class="help-meta-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <span><?= htmlspecialchars($article['category']) ?></span>
                    </div>
                    <div class="help-meta-item">
                        <svg class="help-meta-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span><?= htmlspecialchars($article['read_time'] ?? '2 min read') ?></span>
                    </div>
                </div>
            </header>

            <!-- ── Article Body Content (Image 1) ── -->
            <article class="help-article-body">
                <?php foreach (($article['paragraphs'] ?? []) as $para): ?>
                    <p><?= htmlspecialchars($para) ?></p>
                <?php endforeach; ?>

                <?php foreach (($article['sections'] ?? []) as $sec): ?>
                    <?php if (!empty($sec['heading'])): ?>
                        <h3><?= htmlspecialchars($sec['heading']) ?></h3>
                    <?php endif; ?>

                    <?php if (!empty($sec['content'])): ?>
                        <p><?= htmlspecialchars($sec['content']) ?></p>
                    <?php endif; ?>

                    <?php if (!empty($sec['bullets'])): ?>
                        <ul>
                            <?php foreach ($sec['bullets'] as $bullet): ?>
                                <li><?= htmlspecialchars($bullet) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                <?php endforeach; ?>
            </article>

            <!-- ── Related Articles (Image 1) ── -->
            <?php if (!empty($article['related_articles'])): ?>
            <section class="help-related-section" aria-labelledby="related-articles-heading">
                <h2 id="related-articles-heading" class="help-related-heading">Related articles</h2>
                <div class="help-related-grid">
                    <?php foreach ($article['related_articles'] as $rel): ?>
                    <a href="<?= url('/help/article/' . $rel['slug']) ?>" class="help-related-card">
                        <div class="help-related-title"><?= htmlspecialchars($rel['title']) ?></div>
                        <div class="help-related-cat"><?= htmlspecialchars($rel['category']) ?></div>
                    </a>
                    <?php endforeach; ?>
                </div>
            </section>
            <?php endif; ?>

            <!-- ── Was This Article Helpful Widget (Image 1) ── -->
            <section class="help-feedback-card" x-data="{ feedback: null }">
                <div x-show="feedback === null">
                    <p class="help-feedback-prompt">Was this article helpful?</p>
                    <div class="help-feedback-buttons">
                        <button type="button" 
                                @click="feedback = 'yes'" 
                                class="help-feedback-btn">
                            <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span>Yes</span>
                        </button>
                        <button type="button" 
                                @click="feedback = 'no'" 
                                class="help-feedback-btn">
                            <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            <span>No</span>
                        </button>
                    </div>
                </div>

                <div x-show="feedback !== null" x-cloak class="help-feedback-thankyou">
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="#047857" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span>Thank you for your feedback!</span>
                </div>
            </section>

            <!-- ── Still Need Help Banner (Image 1) ── -->
            <section class="help-support-banner">
                <div>
                    <h3 class="help-support-title">Still need help?</h3>
                    <p class="help-support-desc">Our support team is here for anything this article didn't cover.</p>
                </div>
                <button type="button" 
                        @click="$dispatch('open-support-chat')" 
                        class="help-support-btn">
                    Contact Support
                </button>
            </section>

            <!-- ── Footer ── -->
            <div class="help-footer-wrap">
                &copy; <?= date('Y') ?> InternConnect. All rights reserved.
            </div>

        </div> <!-- /db-content -->
    </div> <!-- /db-main -->

</div> <!-- /db-shell -->
