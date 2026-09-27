<?php
declare(strict_types=1);
/**
 * View: Candidate Offer Declined Confirmation
 * Route: /candidate/offer/declined
 * Layout: dashboard (Candidate Portal)
 * 
 * @var array $candidate Candidate profile information
 * @var array $offer     Declined offer details
 */

$name      = $candidate['name'] ?? 'Sarah Peters';
$firstName = explode(' ', trim($name))[0];
$role      = $candidate['role'] ?? 'Student Applicant';
$avatar    = $candidate['avatar'] ?? null;
?>

<div class="db-shell">

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
                
                <!-- Declined Red Cross Icon Badge -->
                <div class="db-outcome-icon-wrap declined">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </div>

                <!-- Page Headline -->
                <h1 class="db-outcome-title">Offer Declined</h1>
                
                <p class="db-outcome-subtitle">
                    You have decided not to accept the job offer for this position. We understand, and wish you the best in your job search.
                </p>

                <!-- Top 2-Column Summary Grid -->
                <div class="db-outcome-grid-top">
                    
                    <!-- Left Summary Card -->
                    <div class="db-outcome-summary-card">
                        <div>
                            <div class="db-outcome-company-header-with-icon">
                                <div class="db-outcome-company-icon-box">
                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <div class="db-outcome-company-title" style="margin-bottom: 0;"><?= htmlspecialchars($offer['company'] ?? 'Stellar Systems') ?></div>
                            </div>

                            <div class="db-outcome-meta-row" style="margin-top: 10px;">
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

                            <span class="db-outcome-status-pill declined">
                                <span>Offer Declined</span>
                            </span>
                        </div>
                    </div>

                    <!-- Right "What happened?" Card -->
                    <div class="db-outcome-info-card">
                        <div class="db-outcome-info-icon-box">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="db-outcome-info-content">
                            <h3 class="db-outcome-info-title">What happened?</h3>
                            <p class="db-outcome-info-text">
                                The job offer has been declined. Reasons can include accepting another position, the offer not matching expectations, or personal circumstances.
                            </p>
                        </div>
                    </div>

                </div>

                <!-- Section Header -->
                <div class="db-outcome-section-title">Here's what you can do</div>

                <!-- 3 Bottom Action Cards Grid -->
                <div class="db-outcome-grid-actions">
                    
                    <!-- Card 1: Continue Job Search -->
                    <a href="<?= url('/internships') ?>" class="db-outcome-action-card">
                        <div>
                            <div class="db-outcome-action-icon-box blue">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                            <h4 class="db-outcome-action-title">Continue Job Search</h4>
                            <p class="db-outcome-action-desc">Explore more job opportunities that match your skills and career goals.</p>
                        </div>
                        <span class="db-outcome-action-arrow blue">→</span>
                    </a>

                    <!-- Card 2: View Other Applications -->
                    <a href="<?= url('/candidate/applications') ?>" class="db-outcome-action-card">
                        <div>
                            <div class="db-outcome-action-icon-box purple">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                </svg>
                            </div>
                            <h4 class="db-outcome-action-title">View Other Applications</h4>
                            <p class="db-outcome-action-desc">Check the status of your other job applications and opportunities.</p>
                        </div>
                        <span class="db-outcome-action-arrow blue">→</span>
                    </a>

                    <!-- Card 3: Update Job Preferences -->
                    <a href="<?= url('/candidate/profile') ?>" class="db-outcome-action-card">
                        <div>
                            <div class="db-outcome-action-icon-box blue">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            <h4 class="db-outcome-action-title">Update Job Preferences</h4>
                            <p class="db-outcome-action-desc">Update your preferences to receive opportunities that better match your interests.</p>
                        </div>
                        <span class="db-outcome-action-arrow blue">→</span>
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



    </div><!-- /.db-main -->

</div>
