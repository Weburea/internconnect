<?php
declare(strict_types=1);
/**
 * View: Candidate Offer Accepted Success
 * Route: /candidate/offer/accepted
 * Layout: dashboard (Candidate Portal)
 * 
 * @var array $candidate Candidate profile information
 * @var array $offer     Accepted offer details
 */

$name      = $candidate['name'] ?? 'Sarah Peters';
$firstName = explode(' ', trim($name))[0];
$role      = $candidate['role'] ?? 'Student Applicant';
$avatar    = $candidate['avatar'] ?? null;
?>

<div class="db-shell" x-data="{
    copied: false,
    shareWin() {
        const shareText = 'I am thrilled to announce that I have accepted an internship offer at Stellar Systems through InternConnect! 🚀🎉';
        if (navigator.clipboard) {
            navigator.clipboard.writeText(shareText).then(() => {
                this.copied = true;
                if (typeof window.$dispatch === 'function') {
                    window.$dispatch('show-toast', { 
                        text: 'Celebration message copied to clipboard! Share it with your network.', 
                        type: 'success' 
                    });
                }
            });
        } else {
            if (typeof window.$dispatch === 'function') {
                window.$dispatch('show-toast', { 
                    text: 'Share your win: ' + shareText, 
                    type: 'info' 
                });
            }
        }
    }
}">

    <!-- ══ SIDEBAR ══ -->
    <?php 
    $activeNav = 'applications';
    include dirname(__DIR__, 2) . '/shared/components/candidate-sidebar.php'; 
    ?>

    <!-- ══ MAIN ══ -->
    <div class="db-main">

        <!-- ══ TOP NAVBAR ══ -->
        <?php include dirname(__DIR__, 2) . '/shared/components/candidate-topnav.php'; ?>

        <!-- ══ CONTENT ══ -->
        <div class="db-content">

            <div class="db-outcome-wrap">
                
                <!-- Success Checkmark Icon Badge -->
                <div class="db-outcome-icon-wrap accepted">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>

                <!-- Page Headline -->
                <h1 class="db-outcome-title">
                    You're going to <?= htmlspecialchars($offer['company'] ?? 'Stellar Systems') ?>, <span class="db-outcome-title-highlight"><?= htmlspecialchars($firstName) ?>.</span>
                </h1>
                
                <p class="db-outcome-subtitle">
                    Congratulations — your acceptance has been sent. Your internship begins <?= htmlspecialchars($offer['commencement_date'] ?? 'February 1, 2027') ?>. This is just the beginning.
                </p>

                <!-- Top 2-Column Summary Grid -->
                <div class="db-outcome-grid-top">
                    
                    <!-- Left Summary Card -->
                    <div class="db-outcome-summary-card">
                        <div>
                            <div class="db-outcome-company-title"><?= htmlspecialchars($offer['company'] ?? 'Stellar Systems') ?></div>
                            <div class="db-outcome-meta-row">
                                <span class="db-outcome-meta-item">
                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                    <span><?= htmlspecialchars($offer['role'] ?? 'Product Design Intern') ?></span>
                                </span>
                                
                                <span class="db-outcome-meta-item">
                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    <span><?= htmlspecialchars($offer['location'] ?? 'Lagos, NG') ?></span>
                                </span>

                                <span class="db-outcome-meta-item">
                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span><?= htmlspecialchars($offer['type'] ?? 'Full-time') ?></span>
                                </span>
                            </div>
                        </div>

                        <div class="db-outcome-summary-divider">
                            <div class="db-outcome-date-block">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <div>
                                    <div class="db-outcome-date-label">Start Date</div>
                                    <div class="db-outcome-date-val"><?= htmlspecialchars($offer['start_date'] ?? 'Feb 01, 2027') ?></div>
                                </div>
                            </div>

                            <span class="db-outcome-status-pill secured">
                                <svg style="width: 13px; height: 13px;" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span>Placement Secured</span>
                            </span>
                        </div>
                    </div>

                    <!-- Right "What's next?" Card -->
                    <div class="db-outcome-info-card">
                        <div class="db-outcome-info-icon-box">
                            <svg fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2L14.4 8.6L21 11L14.4 13.4L12 20L9.6 13.4L3 11L9.6 8.6L12 2Z"/>
                            </svg>
                        </div>
                        <div class="db-outcome-info-content">
                            <h3 class="db-outcome-info-title">What's next?</h3>
                            <p class="db-outcome-info-text">
                                Keep an eye on your email for next steps from <?= htmlspecialchars($offer['company'] ?? 'Stellar Systems') ?>. They'll be in touch soon with onboarding details and further instructions.
                            </p>
                        </div>
                    </div>

                </div>

                <!-- 3 Bottom Action Cards Grid -->
                <div class="db-outcome-grid-actions">
                    
                    <!-- Card 1: Update your profile -->
                    <a href="<?= url('/candidate/profile') ?>" class="db-outcome-action-card">
                        <div>
                            <div class="db-outcome-action-icon-box blue">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                            <h4 class="db-outcome-action-title">Update your profile</h4>
                            <p class="db-outcome-action-desc">Keep your profile up to date so you can make the best impression at your new workplace.</p>
                        </div>
                        <span class="db-outcome-action-arrow blue">→</span>
                    </a>

                    <!-- Card 2: Share your win -->
                    <div class="db-outcome-action-card" @click="shareWin()">
                        <div>
                            <div class="db-outcome-action-icon-box green">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/>
                                </svg>
                            </div>
                            <h4 class="db-outcome-action-title">Share your win</h4>
                            <p class="db-outcome-action-desc">Tell your network about your new journey at <?= htmlspecialchars($offer['company'] ?? 'Stellar Systems') ?>.</p>
                        </div>
                        <span class="db-outcome-action-arrow green">→</span>
                    </div>

                    <!-- Card 3: View Application -->
                    <a href="<?= url('/candidate/applications') ?>" class="db-outcome-action-card">
                        <div>
                            <div class="db-outcome-action-icon-box purple">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <h4 class="db-outcome-action-title">View Application</h4>
                            <p class="db-outcome-action-desc">Check your application details and stay updated.</p>
                        </div>
                        <span class="db-outcome-action-arrow purple">→</span>
                    </a>

                </div>

                <!-- Central Bottom CTA -->
                <a href="<?= url('/candidate/dashboard') ?>" class="db-outcome-btn-dashboard">
                    <span>Go to Dashboard</span>
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </a>

            </div><!-- /.db-outcome-wrap -->

        </div><!-- /.db-content -->

        <!-- Dashboard Footer -->
        <?php include __DIR__ . '/../../shared/components/db-footer.php'; ?>

        <!-- Mobile Sidebar Toggle Script -->
        <script>
        (function() {
            var hamburger = document.getElementById('db-hamburger');
            var sidebar   = document.querySelector('.db-sidebar');
            var overlay   = document.getElementById('db-sidebar-overlay');
            if (!hamburger || !sidebar || !overlay) return;
            function toggleSidebar() {
                sidebar.classList.toggle('is-open');
                overlay.classList.toggle('is-open');
            }
            hamburger.addEventListener('click', toggleSidebar);
            overlay.addEventListener('click', toggleSidebar);
        })();
        </script>

    </div><!-- /.db-main -->

</div>
