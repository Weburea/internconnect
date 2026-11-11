<?php

declare(strict_types=1);

/**
 * View: About Us
 * Route: /about
 * Layout: app
 */
?>
<section class="ic-page-shell">

    <!-- Hero Header Banner -->
    <header class="ic-page-hero">
        <div class="ic-container">
            <span class="ic-badge">
                <svg style="width: 14px; height: 14px; flex-shrink: 0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
                About InternConnect
            </span>
            <h1 class="ic-page-hero-title">Connecting Africa's Next Generation to Global Opportunity</h1>
            <p class="ic-page-hero-subtitle">
                We empower university students, polytechnic graduates, and ambitious interns by pairing verified candidates with forward-thinking companies through AI assistance, transparent stipends, and editorial career resources.
            </p>
        </div>
    </header>

    <div class="ic-container">

        <!-- Top KPI Metrics Grid -->
        <div class="about-metrics-grid">
            <div class="about-metric-card">
                <div class="about-metric-number">15,000+</div>
                <div class="about-metric-label">Placements Completed</div>
                <div class="about-metric-subtext">Across tech, finance, design, &amp; engineering</div>
            </div>
            <div class="about-metric-card">
                <div class="about-metric-number">450+</div>
                <div class="about-metric-label">Verified Employers</div>
                <div class="about-metric-subtext">Strictly vetted with corporate CAC checks</div>
            </div>
            <div class="about-metric-card">
                <div class="about-metric-number">85+</div>
                <div class="about-metric-label">Partner Institutions</div>
                <div class="about-metric-subtext">Connecting universities across Nigeria</div>
            </div>
            <div class="about-metric-card">
                <div class="about-metric-number">94%</div>
                <div class="about-metric-label">Candidate Satisfaction</div>
                <div class="about-metric-subtext">Rated 4.9/5 by student applicants</div>
            </div>
        </div>

        <!-- Story & Mission Section -->
        <div class="about-story-grid">
            <div class="about-story-content">
                <span class="ic-badge ic-badge-neutral" style="margin-bottom: 8px;">Our Genesis</span>
                <h2>Solving the Broken Early-Career Hiring Experience</h2>
                <p>
                    For decades, finding an internship across African tertiary institutions has been marred by unverified job postings, fraudulent application fees, opaque hiring pipelines, and a disconnect between classroom theory and industry requirements.
                </p>
                <p>
                    InternConnect was built by early-career engineers, educators, and enterprise recruiters to solve this fundamental bottleneck. We created a singular, unified platform where verified organizations meet ambitious university talent—backed by AI resume optimization, automated screening, and direct calendar interview scheduling.
                </p>
                <div style="display: flex; gap: 12px; margin-top: 24px; flex-wrap: wrap;">
                    <a href="<?= url('/register/student') ?>" class="ic-btn ic-btn-primary">
                        <span>Find an Internship</span>
                        <svg style="width: 14px; height: 14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                    <a href="<?= url('/register/employer') ?>" class="ic-btn ic-btn-secondary">
                        <span>Post Internship Roles</span>
                    </a>
                </div>
            </div>

            <div style="background: #FFFFFF; border: 1px solid var(--ic-border); border-radius: var(--ic-radius-card); padding: 36px; box-shadow: var(--ic-shadow-md);">
                <div class="about-card-badge">
                    <svg style="width: 22px; height: 22px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
                <h3 style="font-size: 19px; font-weight: 800; color: #000623; margin: 0 0 10px;">Our Guiding Mission</h3>
                <p style="font-size: 14px; color: #475569; line-height: 1.65; margin: 0 0 20px;">
                    To accelerate youth employability across Africa by removing friction, preventing fraudulent recruiting, and equipping every student with AI-assisted career tools.
                </p>
                <div style="padding-top: 20px; border-top: 1px solid var(--ic-border-subtle); display: flex; flex-direction: column; gap: 12px;">
                    <div style="display: flex; align-items: center; gap: 10px; font-size: 13.5px; font-weight: 600; color: #0F172A;">
                        <svg style="width: 16px; height: 16px; color: #059669; flex-shrink: 0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span>100% Free For All Student Applicants</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 10px; font-size: 13.5px; font-weight: 600; color: #0F172A;">
                        <svg style="width: 16px; height: 16px; color: #059669; flex-shrink: 0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span>Zero Tolerance for Unverified or Scam Roles</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 10px; font-size: 13.5px; font-weight: 600; color: #0F172A;">
                        <svg style="width: 16px; height: 16px; color: #059669; flex-shrink: 0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span>Transparent Stipends &amp; Compensation</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- 4 Core Pillars -->
        <div style="text-align: center; margin-top: 32px;">
            <span class="ic-badge">Core Pillars</span>
            <h2 style="font-size: clamp(24px, 3vw, 32px); font-weight: 800; color: #000623; margin: 12px 0 8px;">What Sets InternConnect Apart</h2>
            <p style="font-size: 15px; color: #64748B; max-width: 600px; margin: 0 auto;">
                Every interaction on our platform is anchored by four architectural standards.
            </p>
        </div>

        <div class="about-pillars-grid">
            <div class="about-pillar-card">
                <div class="about-card-badge">
                    <svg style="width: 22px; height: 22px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                <h3 class="about-pillar-title">Verified Corporate Trust</h3>
                <p class="about-pillar-desc">
                    Every hiring company must provide Corporate Affairs Commission (CAC) verification and registered corporate email credentials before publishing opportunities. Students never encounter anonymous recruiters.
                </p>
            </div>

            <div class="about-pillar-card">
                <div class="about-card-badge">
                    <svg style="width: 22px; height: 22px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
                <h3 class="about-pillar-title">AI Precision Matchmaking</h3>
                <p class="about-pillar-desc">
                    Our relational taxonomy and Jenny AI algorithm analyze candidates' core disciplines, projects, and coursework to highlight genuine talent, cutting employer screening time from weeks to hours.
                </p>
            </div>

            <div class="about-pillar-card">
                <div class="about-card-badge">
                    <svg style="width: 22px; height: 22px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="about-pillar-title">Stipend Transparency</h3>
                <p class="about-pillar-desc">
                    We require full compensation disclosures. Employers specify exact monthly stipends in NGN or formally designate volunteer programs. We enforce zero hidden costs for candidates.
                </p>
            </div>

            <div class="about-pillar-card">
                <div class="about-card-badge">
                    <svg style="width: 22px; height: 22px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <h3 class="about-pillar-title">Direct Interview Scheduling</h3>
                <p class="about-pillar-desc">
                    Eliminate endless email tag. Employers schedule physical or virtual meetings directly into candidates' calendars, complete with preparation checklists and automatic reminder notifications.
                </p>
            </div>

            <div class="about-pillar-card">
                <div class="about-card-badge">
                    <svg style="width: 22px; height: 22px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
                <h3 class="about-pillar-title">Career Acceleration Modules</h3>
                <p class="about-pillar-desc">
                    From soft skills training to technical whiteboard prep, our integrated E-Learning hub and Jenny AI assistant coach candidates so they arrive on Day 1 prepared to make an impact.
                </p>
            </div>

            <div class="about-pillar-card">
                <div class="about-card-badge">
                    <svg style="width: 22px; height: 22px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/>
                    </svg>
                </div>
                <h3 class="about-pillar-title">Equal Opportunity Access</h3>
                <p class="about-pillar-desc">
                    Every student, regardless of university tier or family connections, has equal access to competitive roles at top-tier startups, multinational corporations, and civic institutions.
                </p>
            </div>
        </div>

        <!-- Call to Action Banner -->
        <div class="hiw-cta-banner">
            <h2 class="hiw-cta-title">Ready to Experience the Future of Internship Hiring?</h2>
            <p class="hiw-cta-desc">
                Join over 15,000 students and 450+ verified employers today. Experience streamlined matching and editorial-grade support.
            </p>
            <div style="display: flex; justify-content: center; gap: 14px; flex-wrap: wrap;">
                <a href="<?= url('/register/student') ?>" class="ic-btn ic-btn-primary" style="padding: 12px 28px; font-size: 15px;">
                    <span>Start as a Candidate</span>
                    <svg style="width: 15px; height: 15px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
                <a href="<?= url('/register/employer') ?>" class="ic-btn ic-btn-secondary" style="padding: 12px 28px; font-size: 15px;">
                    <span>Post Internship Roles</span>
                </a>
            </div>
        </div>

    </div>

</section>
