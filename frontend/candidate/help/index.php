<?php
declare(strict_types=1);
/**
 * View: Help Center Main Hub
 * Route: /help, /candidate/help
 * Layout: dashboard
 * 
 * @var array  $candidate   Candidate profile data
 * @var array  $categories  Help categories list
 * @var array  $popularFaqs Popular FAQs list
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
            /* ─── Help Center Specific Layout Styles ─── */
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

            /* Decorative Background Radial Curves */
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

            .help-hero-banner::after {
                content: '';
                position: absolute;
                bottom: -60%;
                left: 30%;
                width: 380px;
                height: 380px;
                background: radial-gradient(circle, rgba(255, 255, 255, 0.08) 0%, rgba(255, 255, 255, 0) 70%);
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

            .help-search-input::placeholder {
                color: #94A3B8;
            }

            .help-suggestions-row {
                display: flex;
                align-items: center;
                gap: 8px;
                margin-top: 14px;
                flex-wrap: wrap;
                position: relative;
                z-index: 2;
            }

            .help-suggestions-label {
                font-size: 13px;
                color: #E0E7FF;
                font-weight: 500;
            }

            .help-suggestion-chip {
                background: rgba(255, 255, 255, 0.16);
                border: 1px solid rgba(255, 255, 255, 0.25);
                color: #FFFFFF;
                padding: 4px 12px;
                border-radius: 999px;
                font-size: 12px;
                font-weight: 500;
                text-decoration: none;
                cursor: pointer;
                transition: all 0.15s ease;
                white-space: nowrap;
                flex-shrink: 0;
            }

            .help-suggestion-chip:hover {
                background: #FFFFFF;
                color: #4F46E5;
                border-color: #FFFFFF;
                transform: translateY(-1px);
            }

            /* Category Cards Grid */
            .help-categories-grid {
                display: grid;
                grid-template-columns: repeat(3, 1fr);
                gap: 20px;
                margin-bottom: 40px;
            }

            @media (max-width: 1024px) {
                .help-categories-grid {
                    grid-template-columns: repeat(2, 1fr);
                }
            }

            @media (max-width: 640px) {
                .help-categories-grid {
                    grid-template-columns: 1fr;
                }
            }

            .help-cat-card {
                background: #FFFFFF;
                border: 1px solid #E2E8F0;
                border-radius: 16px;
                padding: 24px;
                display: flex;
                flex-direction: column;
                justify-content: space-between;
                text-decoration: none;
                transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
                position: relative;
                min-height: 170px;
            }

            .help-cat-card:hover {
                border-color: #818CF8;
                transform: translateY(-2px);
                box-shadow: 0 10px 20px -5px rgba(79, 70, 229, 0.1);
            }

            .help-cat-top {
                display: flex;
                align-items: flex-start;
                justify-content: space-between;
                margin-bottom: 14px;
            }

            .help-cat-icon {
                width: 42px;
                height: 42px;
                border-radius: 12px;
                background: #EEF2FF;
                color: #4F46E5;
                display: flex;
                align-items: center;
                justify-content: center;
                flex-shrink: 0;
            }

            .help-cat-icon svg {
                width: 20px;
                height: 20px;
            }

            .help-cat-badge {
                font-size: 11px;
                font-weight: 700;
                padding: 2px 8px;
                border-radius: 999px;
                background: #FEF3C7;
                color: #D97706;
                white-space: nowrap;
                flex-shrink: 0;
                letter-spacing: 0.04em;
            }

            .help-cat-title {
                font-size: 16px;
                font-weight: 700;
                color: #0F172A;
                margin: 0 0 6px 0;
            }

            .help-cat-desc {
                font-size: 13.5px;
                color: #64748B;
                line-height: 1.5;
                margin: 0;
                flex: 1;
            }

            .help-cat-link {
                margin-top: 18px;
                font-size: 13px;
                font-weight: 600;
                color: #4F46E5;
                display: inline-flex;
                align-items: center;
                gap: 4px;
            }

            .help-cat-link.is-empty {
                color: #D97706;
            }

            /* Popular FAQs Section */
            .help-faq-section {
                margin-bottom: 48px;
            }

            .help-faq-heading {
                font-size: 20px;
                font-weight: 700;
                color: #0F172A;
                margin: 0 0 20px 0;
            }

            .help-faq-list {
                display: flex;
                flex-direction: column;
                gap: 12px;
            }

            .help-faq-card {
                background: #FFFFFF;
                border: 1px solid #E2E8F0;
                border-radius: 12px;
                overflow: hidden;
                transition: border-color 0.15s ease;
            }

            .help-faq-card.is-active {
                border-color: #CBD5E1;
            }

            .help-faq-btn {
                width: 100%;
                display: flex;
                align-items: center;
                justify-content: space-between;
                padding: 18px 20px;
                background: transparent;
                border: none;
                font-size: 15px;
                font-weight: 600;
                color: #0F172A;
                cursor: pointer;
                text-align: left;
                outline: none;
                gap: 12px;
            }

            .help-faq-chevron {
                width: 18px;
                height: 18px;
                color: #64748B;
                transition: transform 0.2s ease;
                flex-shrink: 0;
            }

            .help-faq-body {
                padding: 0 20px 20px 20px;
                font-size: 14px;
                line-height: 1.6;
                color: #475569;
            }

            .help-faq-article-link {
                display: inline-flex;
                align-items: center;
                gap: 4px;
                font-size: 13.5px;
                font-weight: 600;
                color: #4F46E5;
                text-decoration: none;
                margin-top: 10px;
                transition: color 0.15s ease;
            }

            .help-faq-article-link:hover {
                color: #4338CA;
                text-decoration: underline;
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

            <!-- ── Hero Purple Banner ── -->
            <section class="help-hero-banner" aria-labelledby="help-hero-title">
                <span class="help-pill-badge">HELP CENTER</span>
                <h1 id="help-hero-title" class="help-hero-title">How can we help you, <?= htmlspecialchars($firstName) ?>?</h1>

                <!-- Search Input Form -->
                <form action="<?= url('/help/search') ?>" method="GET" class="help-search-form">
                    <div class="help-search-input-wrap">
                        <svg class="help-search-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <input type="text" 
                               name="q" 
                               class="help-search-input" 
                               placeholder="Search for answers..." 
                               required 
                               autocomplete="off">
                    </div>
                </form>

                <!-- Popular Suggestions Row -->
                <div class="help-suggestions-row">
                    <span class="help-suggestions-label">Popular suggestions:</span>
                    <a href="<?= url('/help/article/how-does-quick-apply-work') ?>" class="help-suggestion-chip">Quick Apply</a>
                    <a href="<?= url('/help/search?q=resume') ?>" class="help-suggestion-chip">Resume building</a>
                    <a href="<?= url('/help/search?q=interview') ?>" class="help-suggestion-chip">Interview tips</a>
                </div>
            </section>

            <!-- ── Category Cards Grid (Image 3 & 4) ── -->
            <section class="help-categories-grid" aria-label="Help categories">
                <?php foreach ($categories as $cat): ?>
                <a href="<?= url('/help/search?q=' . urlencode($cat['title'])) ?>" class="help-cat-card">
                    <div>
                        <div class="help-cat-top">
                            <div class="help-cat-icon">
                                <?= $cat['icon_svg'] ?>
                            </div>
                            <?php if (!empty($cat['badge'])): ?>
                                <span class="help-cat-badge"><?= htmlspecialchars($cat['badge']) ?></span>
                            <?php endif; ?>
                        </div>
                        <h3 class="help-cat-title"><?= htmlspecialchars($cat['title']) ?></h3>
                        <p class="help-cat-desc"><?= htmlspecialchars($cat['description']) ?></p>
                    </div>

                    <div class="help-cat-link <?= $cat['slug'] === 'account-privacy' && !empty($cat['badge']) ? 'is-empty' : '' ?>">
                        <span><?= $cat['slug'] === 'account-privacy' && !empty($cat['badge']) ? 'No articles yet' : htmlspecialchars($cat['count_text']) ?></span>
                        <span aria-hidden="true">&rarr;</span>
                    </div>
                </a>
                <?php endforeach; ?>
            </section>

            <!-- ── Popular FAQs (Accordion) ── -->
            <section class="help-faq-section" aria-labelledby="popular-faqs-heading" x-data="{ activeFaq: 'how-does-quick-apply-work' }">
                <h2 id="popular-faqs-heading" class="help-faq-heading">Popular FAQs</h2>

                <div class="help-faq-list">
                    <?php foreach ($popularFaqs as $faq): ?>
                    <div class="help-faq-card" :class="{ 'is-active': activeFaq === '<?= $faq['slug'] ?>' }">
                        <button type="button" 
                                class="help-faq-btn" 
                                @click="activeFaq = (activeFaq === '<?= $faq['slug'] ?>' ? null : '<?= $faq['slug'] ?>')"
                                :aria-expanded="activeFaq === '<?= $faq['slug'] ?>'">
                            <span><?= htmlspecialchars($faq['question']) ?></span>
                            <svg class="help-faq-chevron" 
                                 :style="activeFaq === '<?= $faq['slug'] ?>' ? 'transform: rotate(180deg);' : ''"
                                 fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div x-show="activeFaq === '<?= $faq['slug'] ?>'" x-collapse>
                            <div class="help-faq-body">
                                <p style="margin: 0;"><?= htmlspecialchars($faq['answer']) ?></p>
                                <?php if (!empty($faq['article_url'])): ?>
                                    <div>
                                        <a href="<?= htmlspecialchars($faq['article_url']) ?>" class="help-faq-article-link">
                                            <span>Read full article</span>
                                            <span aria-hidden="true">&rarr;</span>
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </section>

            <!-- ── Footer ── -->
            <div class="help-footer-wrap">
                &copy; <?= date('Y') ?> InternConnect. All rights reserved.
            </div>

        </div> <!-- /db-content -->
    </div> <!-- /db-main -->

</div> <!-- /db-shell -->
