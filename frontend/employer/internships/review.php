<?php
declare(strict_types=1);
/**
 * View: Review & Publish Internship
 * Route: /employer/internships/review
 * Layout: dashboard (Employer Portal)
 * 
 * @var array  $employer Employer profile data
 * @var array  $draft    Draft internship data
 * @var string $activeNav Active navigation key ('post')
 */

$title            = $draft['title'] ?? 'Product Design Intern';
$companyName      = $employer['company_name'] ?? 'TechFlow System';
$location         = $draft['location'] ?? 'Remote';
$type             = $draft['internship_type'] ?? 'Remote';
$about            = $draft['about'] ?? 'We are looking for a passionate Product Design Intern to join our remote team. You will work closely with our senior designers and product managers to create intuitive, beautiful, and functional interfaces for the InternConnect platform. This is an incredible opportunity to impact a product used by thousands of students and employers globally.';

$responsibilitiesTitle = !empty($draft['responsibilities_title']) ? $draft['responsibilities_title'] : 'Key Responsibilities';
$responsibilities      = !empty($draft['responsibilities']) && is_array($draft['responsibilities'])
    ? $draft['responsibilities']
    : (!empty($draft['what_you_will_do']) && is_array($draft['what_you_will_do']) ? $draft['what_you_will_do'] : [
        'Collaborate with cross-functional teams to define, design, and ship new features.',
        'Create wireframes, storyboards, user flows, and site maps to effectively communicate interaction ideas.',
        'Conduct user research and evaluate candidate/student feedback to enhance the platform experience.',
        'Participate in design critiques and mentor peer contributors in a fast-paced environment.'
    ]);

$lookingForTitle       = !empty($draft['looking_for_title']) ? $draft['looking_for_title'] : "What We're Looking For";
$lookingForCards       = !empty($draft['looking_for_cards']) && is_array($draft['looking_for_cards'])
    ? $draft['looking_for_cards']
    : [
        [
            'icon'  => 'academic-cap',
            'title' => 'Design / CS Background',
            'desc'  => 'Pursuing or completed a degree in Design, Computer Science, Human-Computer Interaction, or related field.'
        ],
        [
            'icon'  => 'code',
            'title' => 'Prototyping Skills',
            'desc'  => 'Proficiency with modern design and prototyping tools such as Figma, FigJam, or Adobe XD.'
        ],
        [
            'icon'  => 'users',
            'title' => 'Team Player',
            'desc'  => 'Comfortable working in collaborative sprints and communicating blockers proactively.'
        ],
        [
            'icon'  => 'target',
            'title' => 'Problem Solver',
            'desc'  => 'Strong analytical mindset with a passion for crafting intuitive digital experiences.'
        ]
    ];

$benefitsTitle         = !empty($draft['benefits_title']) ? $draft['benefits_title'] : 'Program Benefits';
$benefits              = !empty($draft['benefits']) && is_array($draft['benefits'])
    ? $draft['benefits']
    : [
        'Competitive Stipend',
        'Remote-first Culture',
        'Learning & Development Budget',
        'Return Offer Potential'
    ];

$skills           = !empty($draft['skills']) && is_array($draft['skills'])
    ? $draft['skills']
    : ['Figma', 'Prototyping', 'User Research', 'UI Design'];

$stipendAmount    = !empty($draft['stipend_amount']) ? '$' . $draft['stipend_amount'] . '/mo' : '$3,000 - $4,500/mo';
if (!empty($draft['is_unpaid'])) {
    $stipendAmount = 'Unpaid';
}
$deadline         = !empty($draft['deadline']) ? date('M j, Y', strtotime($draft['deadline'])) : 'May 15, 2026';

if (!function_exists('renderQualIcon')) {
    function renderQualIcon(string $type): string
    {
        $icons = [
            'academic'      => '<svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5zm0 7v-6m-4-1.268A10.97 10.97 0 0012 21a10.97 10.97 0 004-0.732V14m-8 0l4 2 4-2"/></svg>',
            'academic-cap'  => '<svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5zm0 7v-6m-4-1.268A10.97 10.97 0 0012 21a10.97 10.97 0 004-0.732V14m-8 0l4 2 4-2"/></svg>',
            'technical'     => '<svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>',
            'code'          => '<svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>',
            'collaboration' => '<svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>',
            'users'         => '<svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>',
            'target'        => '<svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="6"/><circle cx="12" cy="12" r="2"/></svg>',
            'detail'        => '<svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="6"/><circle cx="12" cy="12" r="2"/></svg>',
            'sparkles'      => '<svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>',
            'light-bulb'    => '<svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>'
        ];
        return $icons[$type] ?? $icons['target'];
    }
}
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
            <div class="emp-post-container" x-data="{ visibility: true, autoClose: false, score: 85, suggestionsApplied: false }">

                <!-- Header Title & Action Buttons -->
                <div class="emp-post-header">
                    <div>
                        <h1 class="emp-post-title">Review & Publish Internship</h1>
                        <p class="emp-post-subtitle">Make sure everything looks perfect before going live.</p>
                    </div>

                    <div class="emp-post-header-actions">
                        <a href="<?= url('/employer/internships/create') ?>" class="emp-btn-secondary">Save as Draft</a>
                        <form action="<?= url('/employer/internships/publish') ?>" method="POST" style="margin: 0;">
                            <button type="submit" class="emp-btn-primary">Publish Internship</button>
                        </form>
                    </div>
                </div>

                <!-- 2-Column Grid (Left: Preview Card, Right: Optimization & Settings) -->
                <div class="emp-post-grid">

                    <!-- ════════════════════════════════════════════════════
                         LEFT COLUMN (Candidate-Facing Preview Card)
                    ════════════════════════════════════════════════════ -->
                    <div class="emp-post-col-left">

                        <div class="emp-review-card">

                            <!-- Role Title & Company Header -->
                            <div class="emp-review-role-header">
                                <div class="emp-review-logo-box">
                                    <!-- Geometric Layered Diamond / Tech Icon SVG -->
                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                    </svg>
                                </div>
                                <div style="flex: 1;">
                                    <h2 class="emp-review-role-title"><?= htmlspecialchars($title) ?></h2>
                                    <div class="emp-review-meta">
                                        <span><?= htmlspecialchars($companyName) ?></span>
                                        <span>•</span>
                                        <span style="display: inline-flex; align-items: center; gap: 4px;">
                                            <svg style="width: 13px; height: 13px; color: #64748B;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                            <?= htmlspecialchars($location) ?>
                                        </span>
                                    </div>
                                    <div class="emp-review-meta-pills">
                                        <span class="emp-meta-pill"><?= htmlspecialchars($type) ?></span>
                                        <span class="emp-meta-pill">Paid (<?= htmlspecialchars($stipendAmount) ?>)</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Role Overview Paragraph -->
                            <div>
                                <h3 class="emp-review-section-title">Role Overview</h3>
                                <p class="emp-review-paragraph">
                                    <?= nl2br(htmlspecialchars($about)) ?>
                                </p>
                            </div>

                            <!-- Key Responsibilities (Matching Detail Page Container) -->
                            <div class="emp-review-container-box">
                                <h3 class="emp-review-section-title" style="margin-bottom: 14px;"><?= htmlspecialchars($responsibilitiesTitle) ?></h3>
                                <ul class="emp-review-list">
                                    <?php foreach ($responsibilities as $item): ?>
                                        <?php if (trim($item) !== ''): ?>
                                            <li>
                                                <span class="emp-review-bullet-icon">
                                                    <!-- Checkmark SVG Icon -->
                                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                </span>
                                                <span><?= htmlspecialchars(ltrim(trim($item), '-*• ')) ?></span>
                                            </li>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </ul>
                            </div>

                            <!-- What We're Looking For (2x2 Grid Matching Detail Page) -->
                            <div style="margin-top: 24px;">
                                <h3 class="emp-review-section-title" style="margin-bottom: 14px;"><?= htmlspecialchars($lookingForTitle) ?></h3>
                                <div class="emp-review-qual-grid">
                                    <?php foreach ($lookingForCards as $card): ?>
                                        <div class="emp-review-qual-card">
                                            <div class="emp-review-qual-icon">
                                                <?= renderQualIcon($card['icon'] ?? 'target') ?>
                                            </div>
                                            <h4 class="emp-review-qual-title"><?= htmlspecialchars($card['title'] ?? '') ?></h4>
                                            <p class="emp-review-qual-desc"><?= htmlspecialchars($card['desc'] ?? '') ?></p>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <!-- Required Skills Row -->
                            <div style="margin-top: 22px;">
                                <h4 style="font-size: 12px; font-weight: 700; color: #64748B; margin: 0 0 8px 0; text-transform: uppercase; letter-spacing: 0.05em;">Required Skills</h4>
                                <div style="display: flex; flex-wrap: wrap; gap: 8px;">
                                    <?php foreach ($skills as $skill): ?>
                                        <span style="background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 8px; padding: 6px 14px; font-size: 12.5px; font-weight: 600; color: #1E293B; box-shadow: 0 1px 2px rgba(0,0,0,0.03); white-space: nowrap !important; flex-shrink: 0 !important;">
                                            <?= htmlspecialchars($skill) ?>
                                        </span>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <!-- Program Benefits + Application Window (2-col grid) -->
                            <div style="margin-top: 26px; display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px;">
                                <div>
                                    <h3 class="emp-review-section-title" style="margin-bottom: 12px;"><?= htmlspecialchars($benefitsTitle) ?></h3>
                                    <ul style="display: flex; flex-direction: column; gap: 10px; margin: 0; padding: 0; list-style: none;">
                                        <?php foreach ($benefits as $benefit): ?>
                                            <?php if (trim($benefit) !== ''): ?>
                                                <li class="emp-review-benefit-item">
                                                    <span class="emp-review-benefit-icon">
                                                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                    </span>
                                                    <span style="font-size: 13.5px; font-weight: 600; color: #1E293B;"><?= htmlspecialchars($benefit) ?></span>
                                                </li>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>

                                <div>
                                    <h3 class="emp-review-section-title" style="margin-bottom: 12px;">Application Window</h3>
                                    <p style="font-size: 13px; color: #475569; line-height: 1.6; margin: 0;">
                                        Applications are accepted until <strong><?= htmlspecialchars($deadline) ?></strong>. Early submissions receive priority review.
                                    </p>
                                </div>
                            </div>

                            <!-- Smart Tip Callout Banner -->
                            <div class="emp-tip-callout">
                                <svg class="emp-tip-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <p class="emp-tip-text">
                                    <strong>Smart Tip:</strong> Posts with explicit salary ranges get 40% more applications. You've included one!
                                </p>
                            </div>

                        </div>

                    </div>


                    <!-- ════════════════════════════════════════════════════
                         RIGHT COLUMN (Optimization, Performance & Settings)
                    ════════════════════════════════════════════════════ -->
                    <div class="emp-post-col-right">

                        <!-- ── Card 1: AI Optimization ── -->
                        <div class="emp-optimization-card">
                            <div class="emp-ai-header">
                                <div class="emp-ai-avatar-wrap">
                                    <!-- Official Waving Jenny AI Chatbot Mascot Image -->
                                    <img src="<?= asset('assets/images/jenny_ai/jenny_ai_hi.png') ?>" alt="Jenny AI Assistant" class="emp-ai-mascot-img">
                                </div>
                                <h3 class="emp-ai-title">AI Optimization</h3>
                            </div>

                            <div class="emp-score-row">
                                <span class="emp-score-label">Listing Score</span>
                                <span class="emp-score-val" x-text="score + '%'">85%</span>
                            </div>

                            <div class="emp-progress-track">
                                <div class="emp-progress-bar" :style="'width: ' + score + '%;'"></div>
                            </div>

                            <!-- Suggestions List -->
                            <div class="emp-suggestion-item" x-show="!suggestionsApplied">
                                Add a specific starting date to increase clarity.
                            </div>
                            <div class="emp-suggestion-item" x-show="!suggestionsApplied">
                                Mention mentorship opportunities in benefits.
                            </div>
                            <div class="emp-suggestion-item" x-show="suggestionsApplied" style="background: #F0FDF4; border-color: #BBF7D0; color: #166534;">
                                Great job! All optimization suggestions have been applied.
                            </div>

                            <button type="button" 
                                    class="emp-btn-primary" 
                                    style="width: 100%; justify-content: center; margin-top: 14px;"
                                    @click="score = 98; suggestionsApplied = true;">
                                <span x-text="suggestionsApplied ? 'Suggestions Applied' : 'Apply Suggestions'">Apply Suggestions</span>
                            </button>
                        </div>

                        <!-- ── Card 2: Expected Performance ── -->
                        <div class="emp-form-card">
                            <h3 class="emp-card-title" style="margin-bottom: 16px;">Expected Performance</h3>

                            <div class="emp-perf-grid">
                                <!-- Applicants Tile -->
                                <div class="emp-perf-tile">
                                    <div class="emp-perf-num">120+</div>
                                    <div class="emp-perf-sub">Expected Applicants</div>
                                </div>

                                <!-- Visibility Tile -->
                                <div class="emp-perf-tile">
                                    <div class="emp-perf-num">
                                        <svg style="width: 20px; height: 20px; color: #4F48E6;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        <span>High</span>
                                    </div>
                                    <div class="emp-perf-sub">Visibility</div>
                                </div>
                            </div>
                        </div>

                        <!-- ── Card 3: Target Audience Match ── -->
                        <div class="emp-form-card">
                            <h3 class="emp-card-title" style="margin-bottom: 16px;">Target Audience Match</h3>

                            <div style="margin-bottom: 14px;">
                                <span style="font-size: 11.5px; font-weight: 600; color: #64748B; text-transform: uppercase; letter-spacing: 0.05em;">Best Suited For</span>
                                <div style="font-size: 13.5px; font-weight: 700; color: #0F172A; margin-top: 4px;">
                                    UI/UX Students, Junior Product Designers
                                </div>
                            </div>

                            <div>
                                <span style="font-size: 11.5px; font-weight: 600; color: #64748B; text-transform: uppercase; letter-spacing: 0.05em;">Top Matching Skills Detected</span>
                                <div style="display: flex; flex-wrap: wrap; gap: 8px; margin-top: 8px;">
                                    <span style="background: #EEF2FF; border-radius: 6px; padding: 4px 10px; font-size: 12px; font-weight: 600; color: #4F48E6; white-space: nowrap !important; flex-shrink: 0 !important;">Figma</span>
                                    <span style="background: #EEF2FF; border-radius: 6px; padding: 4px 10px; font-size: 12px; font-weight: 600; color: #4F48E6; white-space: nowrap !important; flex-shrink: 0 !important;">Research</span>
                                    <span style="background: #EEF2FF; border-radius: 6px; padding: 4px 10px; font-size: 12px; font-weight: 600; color: #4F48E6; white-space: nowrap !important; flex-shrink: 0 !important;">Prototyping</span>
                                </div>
                            </div>
                        </div>

                        <!-- ── Card 4: Publish Settings ── -->
                        <div class="emp-form-card">
                            <h3 class="emp-card-title" style="margin-bottom: 16px;">Publish Settings</h3>

                            <!-- Visibility -->
                            <div class="emp-switch-wrap" style="padding-top: 0;">
                                <div class="emp-switch-info">
                                    <span class="emp-switch-title">Visibility</span>
                                    <span class="emp-switch-sub">Public to all students</span>
                                </div>
                                <label class="emp-switch">
                                    <input type="checkbox" x-model="visibility">
                                    <span class="emp-slider"></span>
                                </label>
                            </div>

                            <!-- Application Deadline -->
                            <div style="display: flex; align-items: center; justify-content: space-between; padding: 12px 0; border-top: 1px solid #F1F5F9; border-bottom: 1px solid #F1F5F9;">
                                <div>
                                    <span style="display: block; font-size: 13px; font-weight: 600; color: #0F172A;">Application Deadline</span>
                                    <span style="font-size: 12.5px; color: #64748B; margin-top: 2px;"><?= htmlspecialchars($deadline) ?></span>
                                </div>
                                <a href="<?= url('/employer/internships/create') ?>" style="font-size: 12.5px; font-weight: 700; color: #4F48E6; text-decoration: none;">Edit</a>
                            </div>

                            <!-- Auto-close listing -->
                            <div style="display: flex; align-items: center; justify-content: space-between; padding-top: 12px;">
                                <div>
                                    <span style="display: block; font-size: 13px; font-weight: 600; color: #0F172A;">Auto-close listing</span>
                                    <span style="font-size: 12px; color: #64748B; margin-top: 2px;">After 100 applicants</span>
                                </div>
                                <input type="checkbox" x-model="autoClose" style="accent-color: #4F48E6; width: 16px; height: 16px;">
                            </div>
                        </div>

                    </div>

                </div>

                <!-- ════════════════════════════════════════════════════
                     BOTTOM ACTION BAR
                ════════════════════════════════════════════════════ -->
                <div class="emp-post-actions-bar">
                    <a href="<?= url('/employer/internships/create') ?>" class="emp-btn-cancel">Cancel</a>

                    <div class="emp-post-actions-right">
                        <a href="<?= url('/employer/internships/create') ?>" class="emp-btn-draft">Save Draft</a>
                        <form action="<?= url('/employer/internships/publish') ?>" method="POST" style="margin: 0; width: 100%;">
                            <button type="submit" class="emp-btn-primary" style="width: 100%;">Publish Internship</button>
                        </form>
                    </div>
                </div>

            </div>
        </div>

    </div>

</div>
