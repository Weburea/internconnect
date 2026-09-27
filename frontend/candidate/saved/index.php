<?php
declare(strict_types=1);
/**
 * View: Candidate Saved Internships
 * Route: /saved
 * Layout: dashboard (custom sidebar layout — no global navbar/footer)
 * @var array  $candidate        Candidate profile data
 * @var array  $savedInternships Saved internships list
 */

$name      = $candidate['name'] ?? 'Sarah Peters';
$firstName = explode(' ', trim($name))[0];
$role      = $candidate['role'] ?? 'Student Applicant';
$avatar    = $candidate['avatar'] ?? null;
?>
<div class="db-shell">

    <!-- Sidebar Overlay (mobile) -->
    <div class="db-sidebar-overlay" id="db-sidebar-overlay"></div>

    <?php 
    $activeNav = 'saved';
    include dirname(__DIR__, 2) . '/shared/components/candidate-sidebar.php'; 
    ?>

    <!-- ══ MAIN ══ -->
    <div class="db-main">

        <!-- ══ TOP NAVBAR ══ -->
        <?php include dirname(__DIR__, 2) . '/shared/components/candidate-topnav.php'; ?>

        <!-- Scrollable Content -->
        <div class="db-content" id="db-content">

            <!-- ── Header Section ── -->
            <div class="db-saved-header">
                <div class="db-saved-header-left">
                    <h1>Saved Internships</h1>
                    <p>A curated list of roles you're interested in. Review and apply whenever you're ready.</p>
                </div>
                <div class="db-saved-badge">6 SAVED</div>
            </div>

            <!-- ── Saved Internships Grid ── -->
            <div class="db-saved-grid">
                <?php foreach ($savedInternships as $index => $internship): ?>
                <div class="db-saved-card" id="db-saved-card-<?= $index ?>">
                    <div class="db-saved-card-header">
                        <div class="db-saved-card-logo <?= htmlspecialchars($internship['logo_class']) ?>">
                            <?= $internship['logo_svg'] ?>
                        </div>
                        <button class="db-saved-card-bookmark" aria-label="Unsave <?= htmlspecialchars($internship['title']) ?>">
                            <!-- Bookmark filled state -->
                            <svg fill="currentColor" viewBox="0 0 24 24"><path d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/></svg>
                        </button>
                    </div>
                    
                    <div class="db-saved-card-title"><?= htmlspecialchars($internship['title']) ?></div>
                    <div class="db-saved-card-company"><?= htmlspecialchars($internship['company']) ?> • <?= htmlspecialchars($internship['location']) ?></div>
                    
                    <div class="db-saved-card-tags">
                        <?php foreach ($internship['tags'] as $tag): ?>
                            <span class="db-rec-tag"><?= htmlspecialchars($tag) ?></span>
                        <?php endforeach; ?>
                    </div>
                    
                    <div class="db-saved-card-footer">
                        <a href="<?= url('/internship/' . ($internship['slug'] ?? 'software-engineer-intern')) ?>" class="db-saved-quick-apply-btn" style="text-decoration: none; display: inline-flex; align-items: center; justify-content: center;">Quick Apply</a>
                        <button class="db-saved-share-btn" aria-label="Share role">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.684 10.742l4.622-2.312m0 7.14l-4.622-2.312M19 12a3 3 0 11-6 0 3 3 0 016 0zm-6-7a3 3 0 11-6 0 3 3 0 016 0zm0 14a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </button>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- ── Based on Saved Section ── -->
            <div class="db-section-header" style="margin-top: 16px;">
                <h3 class="db-section-title">Based on your saved items</h3>
            </div>
            
            <div class="db-saved-bottom-grid">
                <!-- Resume Optimization Card -->
                <div class="db-saved-banner-card">
                    <div class="db-saved-banner-card-top">
                        <p>Resume Optimization</p>
                        <h4>Tailor your profile for Tech Internships</h4>
                        <span>Our AI tool analyzes your saved roles and suggests keywords to help you stand out to recruiters.</span>
                    </div>
                    <a href="#" class="db-saved-banner-btn">Try AI Optimizer</a>
                </div>

                <!-- Get Alerts Card -->
                <div class="db-saved-alerts-card">
                    <div class="db-saved-alerts-icon">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                    </div>
                    <div class="db-saved-alerts-title">Get Alerts</div>
                    <div class="db-saved-alerts-desc">Never miss a deadline for these companies.</div>
                    <a class="db-saved-alerts-link">Enable Deadlines</a>
                </div>
            </div>

        </div><!-- /.db-content -->

        <!-- Dashboard Footer -->
        <?php include __DIR__ . '/../../shared/components/db-footer.php'; ?>


    </div><!-- /.db-main -->
</div><!-- /.db-shell -->
