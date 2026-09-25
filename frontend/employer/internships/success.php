<?php
declare(strict_types=1);
/**
 * View: Your Role Is Live! (Celebration Screen)
 * Route: /employer/internships/success
 * Layout: dashboard (Employer Portal)
 * 
 * @var array  $employer   Employer profile data
 * @var array  $internship Published internship data
 * @var string $activeNav  Active navigation key ('post')
 */

$title = $internship['title'] ?? 'Product Design Intern';
?>

<link rel="stylesheet" href="<?= asset('assets/css/employer-post-internship.css') ?>">

<div class="db-shell">

    <!-- ══ EMPLOYER SIDEBAR ══ -->
    <?php 
    $activeNav = 'post';
    include dirname(__DIR__, 2) . '/shared/components/employer-sidebar.php'; 
    ?>

    <!-- ══ MAIN CONTENT WRAPPER ══ -->
    <div class="db-main">

        <!-- ══ TOP NAVBAR ══ -->
        <?php include dirname(__DIR__, 2) . '/shared/components/employer-topnav.php'; ?>

        <!-- ══ PAGE CONTENT ══ -->
        <div class="db-content">
            <div class="emp-success-shell">

                <div class="emp-success-card">
                    
                    <!-- Left Photo Card with Smiling Woman with Laptop (Matches Image 3) -->
                    <div class="emp-success-photo-wrap">
                        <img src="<?= asset('assets/images/welcome_smiling_woman.png') ?>" 
                             alt="Smiling woman with laptop" 
                             class="emp-success-photo-img">
                        
                        <div class="emp-success-photo-overlay">
                            <h3 class="emp-success-photo-text">The next chapter of your career starts here.</h3>
                            <div class="emp-success-photo-bar"></div>
                        </div>
                    </div>

                    <!-- Right Content Area -->
                    <div class="emp-success-content">
                        
                        <!-- Success Checkmark Badge -->
                        <div class="emp-success-icon-badge">
                            <div class="emp-success-icon-inner">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                        </div>

                        <!-- Headline -->
                        <h1 class="emp-success-h1">Your role is live!</h1>

                        <!-- Lead Description -->
                        <p class="emp-success-lead">
                            Your internship listing for <strong><?= htmlspecialchars($title) ?></strong> is now visible to 12,000+ students on InternConnect. Qualified applications will start appearing in your Hiring Pipeline shortly.
                        </p>

                        <!-- Action Buttons -->
                        <div class="emp-success-cta-row">
                            <a href="<?= url('/employer/dashboard') ?>" class="emp-btn-primary">
                                <span>View Candidates</span>
                                <svg style="width: 14px; height: 14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                            </a>

                            <a href="<?= url('/employer/internships/create') ?>" class="emp-btn-secondary" style="background: #EFF6FF; border-color: #BFDBFE; color: #4F48E6;">
                                <span>Post another role</span>
                                <svg style="width: 14px; height: 14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                            </a>
                        </div>

                        <!-- Manage Link -->
                        <div class="emp-success-manage-link">
                            Want to edit this listing? <a href="<?= url('/employer/dashboard') ?>">You can manage it from your My Internships</a>
                        </div>

                    </div>

                </div>

            </div>

            <!-- Footer copyright matching Image 3 -->
            <div style="text-align: left; padding: 24px 32px; font-size: 12px; color: #94A3B8; border-top: 1px solid #F1F5F9; margin-top: 40px;">
                &copy; <?= date('Y') ?> InternConnect. All rights reserved.
            </div>
        </div>

    </div>

</div>
