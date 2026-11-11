<?php

declare(strict_types=1);

/**
 * View: Success Stories & Testimonials
 * Route: /testimonials, /success-stories
 * Layout: app
 */
?>
<section class="ic-page-shell" x-data="{
    activeFilter: 'all',
    testimonials: [
        {
            id: 1,
            name: 'Adaeze Okafor',
            role: 'Software Engineering Intern',
            company: 'Paystack',
            school: 'University of Lagos (UNILAG)',
            category: 'tech',
            year: 'Summer 2026',
            quote: 'I used to apply to dozens of roles on LinkedIn with zero replies. InternConnect matched my GitHub repos directly with Paystacks requirements, and Jenny AI helped me practice API design questions. I signed my offer in less than 3 weeks!'
        },
        {
            id: 2,
            name: 'Tobi Adebayo',
            role: 'Product Design Intern',
            company: 'Flutterwave',
            school: 'Covenant University',
            category: 'design',
            year: 'Spring 2026',
            quote: 'The stipend transparency is a game changer. I knew the exact compensation upfront and didn\'t have to deal with endless unverified recruitment emails. The interview guide gave me the confidence to pitch my Figma design system.'
        },
        {
            id: 3,
            name: 'Emeka Nwosu',
            role: 'Financial Analyst Intern',
            company: 'CardinalStone Partners',
            school: 'University of Nigeria, Nsukka (UNN)',
            category: 'finance',
            year: 'Fall 2026',
            quote: 'Coming from UNN, breaking into Lagos investment banking felt impossible. InternConnect verified my academic transcripts and scheduled my video interview directly into my calendar. The experience was seamless and editorial-grade.'
        },
        {
            id: 4,
            name: 'Zainab Bello',
            role: 'Frontend Developer Intern',
            company: 'Moniepoint',
            school: 'Ahmadu Bello University (ABU)',
            category: 'tech',
            year: 'Summer 2026',
            quote: 'The screening questions in the application wizard let me showcase my React projects rather than just submitting a generic PDF. The recruiter reached out in 48 hours for our technical discussion.'
        },
        {
            id: 5,
            name: 'Kelechi Eze',
            role: 'Associate Product Intern',
            company: 'Kuda Bank',
            school: 'Obafemi Awolowo University (OAU)',
            category: 'design',
            year: 'Spring 2026',
            quote: 'Jenny AI guided me on how to frame my university hackathon experience into measurable business outcomes. The platform makes student talent feel respected and valued.'
        },
        {
            id: 6,
            name: 'Blessing Danjuma',
            role: 'Data Science Intern',
            company: 'Interswitch Group',
            school: 'Federal University of Technology, Akure (FUTA)',
            category: 'tech',
            year: 'Summer 2026',
            quote: 'The calendar interview scheduling saved so much back-and-forth email tag. I accepted my offer digitally and started my onboarding within days. InternConnect is essential for every Nigerian student.'
        }
    ],
    filteredStories() {
        if (this.activeFilter === 'all') return this.testimonials;
        return this.testimonials.filter(t => t.category === this.activeFilter);
    }
}">

    <!-- Hero Header Banner -->
    <header class="ic-page-hero">
        <div class="ic-container">
            <span class="ic-badge">
                <svg style="width: 14px; height: 14px; flex-shrink: 0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                </svg>
                Proven Placements
            </span>
            <h1 class="ic-page-hero-title">Real Stories. Verified Roles. Breakthrough Careers.</h1>
            <p class="ic-page-hero-subtitle">
                Hear directly from university students and verified hiring managers who transformed their early-career journeys through InternConnect.
            </p>
        </div>
    </header>

    <div class="ic-container">

        <!-- Stat KPI Bar -->
        <div class="testim-kpi-bar">
            <div class="testim-kpi-item">
                <div class="testim-kpi-val">98%</div>
                <div class="testim-kpi-lbl">Offer Acceptance Rate</div>
            </div>
            <div class="testim-kpi-item">
                <div class="testim-kpi-val">2.5 Weeks</div>
                <div class="testim-kpi-lbl">Average Placement Time</div>
            </div>
            <div class="testim-kpi-item">
                <div class="testim-kpi-val">4.9 / 5.0</div>
                <div class="testim-kpi-lbl">Rating Across 3,200+ Reviews</div>
            </div>
        </div>

        <!-- Filter Bar -->
        <div class="elearn-filter-bar">
            <button type="button" class="elearn-filter-pill" :class="{ 'is-active': activeFilter === 'all' }" @click="activeFilter = 'all'">All Stories</button>
            <button type="button" class="elearn-filter-pill" :class="{ 'is-active': activeFilter === 'tech' }" @click="activeFilter = 'tech'">Engineering &amp; Code</button>
            <button type="button" class="elearn-filter-pill" :class="{ 'is-active': activeFilter === 'design' }" @click="activeFilter = 'design'">Design &amp; Product</button>
            <button type="button" class="elearn-filter-pill" :class="{ 'is-active': activeFilter === 'finance' }" @click="activeFilter = 'finance'">Finance &amp; Banking</button>
        </div>

        <!-- Testimonials Cards Grid -->
        <div class="testim-grid">
            <template x-for="item in filteredStories()" :key="item.id">
                <div class="testim-card">
                    <div>
                        <!-- Verified Header Strip -->
                        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px;">
                            <span class="ic-badge ic-badge-success" style="font-size: 11px;">
                                <svg style="width: 12px; height: 12px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                </svg>
                                Verified Placement
                            </span>
                            <span style="font-size: 11.5px; font-weight: 600; color: #94A3B8;" x-text="item.year"></span>
                        </div>

                        <!-- Quote -->
                        <p class="testim-card-quote" x-text="'“' + item.quote + '”'"></p>
                    </div>

                    <!-- Author Details -->
                    <div class="testim-author-row">
                        <div class="testim-avatar" style="display: flex; align-items: center; justify-content: center; font-weight: 700; color: #4F48E6; font-size: 15px;" x-text="item.name.charAt(0)"></div>
                        <div style="min-width: 0; flex: 1;">
                            <div class="testim-author-name" x-text="item.name"></div>
                            <div class="testim-author-meta">
                                <span style="font-weight: 600; color: #000623;" x-text="item.role"></span> at <span style="color: #4F48E6; font-weight: 600;" x-text="item.company"></span>
                            </div>
                            <div style="font-size: 11.5px; color: #64748B; margin-top: 2px;" x-text="item.school"></div>
                        </div>
                    </div>
                </div>
            </template>
        </div>

        <!-- Employer Partner Endorsement Banner -->
        <div style="background: #FFFFFF; border: 1px solid var(--ic-border); border-radius: 20px; padding: 40px; margin-bottom: 64px; box-shadow: var(--ic-shadow-md);">
            <div style="text-align: center; max-width: 680px; margin: 0 auto 32px;">
                <span class="ic-badge">Employer Feedback</span>
                <h3 style="font-size: 22px; font-weight: 800; color: #000623; margin: 10px 0 6px;">Trusted by High-Growth Companies</h3>
                <p style="font-size: 14px; color: #64748B; margin: 0;">What senior hiring directors say about InternConnect candidate quality.</p>
            </div>

            <div style="display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 24px;">
                <div style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 14px; padding: 24px;">
                    <p style="font-size: 14px; color: #334155; line-height: 1.6; margin: 0 0 16px;">
                        "InternConnect cut our summer intake screening time by 70%. The structured screening questions and AI match scores ensured we only interviewed candidates with verified prerequisite skills."
                    </p>
                    <div style="font-size: 13.5px; font-weight: 700; color: #000623;">Oluwaseun Bakare</div>
                    <div style="font-size: 12px; color: #64748B;">Head of People Operations &bull; Nexura Technologies</div>
                </div>

                <div style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 14px; padding: 24px;">
                    <p style="font-size: 14px; color: #334155; line-height: 1.6; margin: 0 0 16px;">
                        "The candidate profiles are editorial quality. Having university, GPA, and portfolio links verified in one unified dashboard made extending official digital offer letters completely seamless."
                    </p>
                    <div style="font-size: 13.5px; font-weight: 700; color: #000623;">Chidinma Obi</div>
                    <div style="font-size: 12px; color: #64748B;">Talent Acquisition Lead &bull; Paystack Inc.</div>
                </div>
            </div>
        </div>

        <!-- Call to Action Banner -->
        <div class="hiw-cta-banner">
            <h2 class="hiw-cta-title">Your Success Story Begins Here</h2>
            <p class="hiw-cta-desc">
                Join thousands of students and 450+ verified employers today. Experience editorial-grade matching with verified stipend transparency.
            </p>
            <div style="display: flex; justify-content: center; gap: 14px; flex-wrap: wrap;">
                <a href="<?= url('/register/student') ?>" class="ic-btn ic-btn-primary" style="padding: 12px 28px; font-size: 15px;">
                    <span>Find Your Internship</span>
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
