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

$category         = $draft['category'] ?? 'Technology';
$openings         = $draft['openings'] ?? 10;
$rawEdu           = $draft['education_level'] ?? 'Undergraduate';
$educationLevelsList = is_array($rawEdu) ? $rawEdu : array_values(array_filter(array_map('trim', explode(',', (string)$rawEdu))));
if (empty($educationLevelsList)) {
    $educationLevelsList = ['Undergraduate'];
}

$skills           = !empty($draft['skills']) && is_array($draft['skills'])
    ? $draft['skills']
    : ['Figma', 'Prototyping', 'User Research', 'UI Design'];

$isUnpaid         = !empty($draft['is_unpaid']) || ($draft['stipend_type'] ?? '') === 'unpaid' || ($draft['stipend_amount'] ?? '') === 'Unpaid';
$stipendAmount    = !empty($draft['stipend_amount']) ? '$' . $draft['stipend_amount'] . '/mo' : '$3,000 - $4,500/mo';
$stipendDisplay   = $isUnpaid ? 'No Fixed Fee (Unpaid)' : ('Paid (' . htmlspecialchars($stipendAmount) . ')');
$deadline         = !empty($draft['deadline']) ? date('M j, Y', strtotime($draft['deadline'])) : 'May 15, 2026';

$relevantExperience = !empty($draft['relevant_experience']) && is_array($draft['relevant_experience'])
    ? $draft['relevant_experience']
    : [
        ['id' => 1, 'title' => 'Job Title', 'type' => 'short_answer', 'placeholder' => 'Frontend Developer Intern', 'required' => true],
        ['id' => 2, 'title' => 'Company', 'type' => 'short_answer', 'placeholder' => 'TechLabs Nigeria', 'required' => true],
        ['id' => 3, 'title' => 'Salary Expectation ($/month)', 'type' => 'short_answer', 'placeholder' => '2,500', 'required' => true]
    ];

$screeningQuestions = !empty($draft['screening_questions']) && is_array($draft['screening_questions'])
    ? $draft['screening_questions']
    : [
        ['id' => 1, 'question' => 'Why are you interested in joining this specific internship role?', 'type' => 'short_answer', 'type_label' => 'Short Answer', 'required' => true],
        ['id' => 2, 'question' => 'Share a link to your portfolio or GitHub profile.', 'type' => 'link', 'type_label' => 'Link / URL', 'required' => false]
    ];

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
            'light-bulb'    => '<svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>',
            'briefcase'     => '<svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>',
            'rocket'        => '<svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.59 14.37a6 6 0 01-5.84 7.38v-4.8m5.84-2.58a14.98 14.98 0 006.16-12.12A14.98 14.98 0 009.63 8.41m5.96 5.96a14.926 14.926 0 01-5.841 2.58m-.119-7.38a6 6 0 00-7.38 5.84h4.8m2.58-5.84a14.927 14.927 0 00-2.58 5.84m2.699-2.58a3 3 0 114.243-4.243 3 3 0 01-4.243 4.243z"/></svg>',
            'award'         => '<svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>',
            'chart'         => '<svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>',
            'laptop'        => '<svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>',
            'shield'        => '<svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>',
            'clock'         => '<svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
            'book'          => '<svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>',
            'palette'       => '<svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/></svg>'
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
                                <div style="flex: 1; min-width: 0;">
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
                                        <span class="emp-meta-pill"><?= htmlspecialchars($category) ?></span>
                                        <span class="emp-meta-pill"><?= htmlspecialchars($type) ?></span>
                                        <span class="emp-meta-pill"><?= htmlspecialchars((string)$openings) ?> <?= (int)$openings === 1 ? 'Opening' : 'Openings' ?></span>
                                        <span class="emp-meta-pill"><?= $stipendDisplay ?></span>
                                    </div>
                                </div>
                            </div>

                            <!-- Role Overview Paragraph -->
                            <div>
                                <h3 class="emp-review-section-title">Role Overview</h3>
                                <div class="emp-review-paragraph">
                                    <?php 
                                    $safeAbout = strip_tags($about, '<p><b><strong><i><em><br>');
                                    echo !empty($safeAbout) ? (strpos($safeAbout, '<p>') !== false ? $safeAbout : nl2br($safeAbout)) : 'No overview provided.';
                                    ?>
                                </div>
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

                            <!-- What We're Looking For (Single-line cards) -->
                            <div style="margin-top: 24px;">
                                <h3 class="emp-review-section-title" style="margin-bottom: 14px;"><?= htmlspecialchars($lookingForTitle) ?></h3>
                                <div class="emp-review-qual-grid">
                                    <?php foreach ($lookingForCards as $card): ?>
                                        <div class="emp-review-qual-card">
                                            <div class="emp-review-qual-icon">
                                                <?= renderQualIcon($card['icon'] ?? 'target') ?>
                                            </div>
                                            <div class="emp-review-qual-info">
                                                <h4 class="emp-review-qual-title"><?= htmlspecialchars($card['title'] ?? '') ?></h4>
                                                <?php if (!empty($card['desc'])): ?>
                                                    <p class="emp-review-qual-desc"><?= htmlspecialchars($card['desc']) ?></p>
                                                <?php endif; ?>
                                            </div>
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

                            <!-- Target Education Level -->
                            <div style="margin-top: 18px;">
                                <h4 style="font-size: 12px; font-weight: 700; color: #64748B; margin: 0 0 8px 0; text-transform: uppercase; letter-spacing: 0.05em;">Target Education Level</h4>
                                <div style="display: flex; flex-wrap: wrap; gap: 8px;">
                                    <?php foreach ($educationLevelsList as $eduItem): ?>
                                        <span style="background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 99px; padding: 5px 14px; font-size: 12px; font-weight: 600; color: #334155; box-shadow: 0 1px 2px rgba(0,0,0,0.03); white-space: nowrap !important; flex-shrink: 0 !important;">
                                            <?= htmlspecialchars($eduItem) ?>
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

                            <!-- Relevant Experience Fields Preview -->
                            <?php if (!empty($relevantExperience)): ?>
                                <div class="emp-review-experience-box">
                                    <div class="emp-review-box-header">
                                        <div class="emp-review-box-title-group">
                                            <span class="emp-review-box-icon">
                                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                                </svg>
                                            </span>
                                            <h4 class="emp-review-box-title">Candidate Experience Required (Step 1)</h4>
                                        </div>
                                        <span class="emp-review-box-badge">
                                            <?= count($relevantExperience) ?> fields
                                        </span>
                                    </div>
                                    <div style="display: flex; flex-direction: column; gap: 8px;">
                                        <?php foreach ($relevantExperience as $idx => $field): ?>
                                            <?php 
                                                $rawTitle = $field['title'] ?? $field['label'] ?? ('Field ' . ($idx + 1));
                                                $cleanTitle = preg_replace('/^\d+\.\s*/', '', (string)$rawTitle);
                                                $fieldBadge = $field['type'] ?? $field['placeholder'] ?? 'Text Input';
                                            ?>
                                            <div class="emp-review-item-row">
                                                <div class="emp-review-item-left">
                                                    <span style="font-size: 11.5px; font-weight: 700; color: #4F48E6; flex-shrink: 0;"><?= ($idx + 1) ?>.</span>
                                                    <span class="emp-review-item-title"><?= htmlspecialchars($cleanTitle) ?></span>
                                                    <?php if (!empty($field['required'])): ?>
                                                        <span style="color: #EF4444; font-size: 12px; font-weight: 700; flex-shrink: 0;" aria-label="Required">*</span>
                                                    <?php endif; ?>
                                                </div>
                                                <span class="emp-review-item-pill">
                                                    <?= htmlspecialchars($fieldBadge) ?>
                                                </span>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <!-- Screening Questions Preview -->
                            <?php if (!empty($screeningQuestions)): ?>
                                <div class="emp-review-question-box">
                                    <div class="emp-review-box-header">
                                        <div class="emp-review-box-title-group">
                                            <span class="emp-review-box-icon">
                                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                            </span>
                                            <h4 class="emp-review-box-title">Screening Questions (Step 3)</h4>
                                        </div>
                                        <span class="emp-review-box-badge">
                                            <?= count($screeningQuestions) ?> questions
                                        </span>
                                    </div>
                                    <div style="display: flex; flex-direction: column; gap: 8px;">
                                        <?php foreach ($screeningQuestions as $qIdx => $sq): ?>
                                            <?php 
                                                $questionText = $sq['question'] ?? $sq['text'] ?? ('Question ' . ($qIdx + 1));
                                                $questionType = $sq['type_label'] ?? $sq['type'] ?? 'Short Answer';
                                            ?>
                                            <div class="emp-review-item-row">
                                                <div class="emp-review-item-left">
                                                    <span style="font-size: 11.5px; font-weight: 700; color: #4F48E6; flex-shrink: 0; margin-top: 1px;">Q<?= ($qIdx + 1) ?>.</span>
                                                    <span class="emp-review-item-title" aria-label="<?= htmlspecialchars($questionText) ?>">
                                                        <?= htmlspecialchars($questionText) ?>
                                                    </span>
                                                </div>
                                                <span class="emp-review-item-badge">
                                                    <?= htmlspecialchars($questionType) ?>
                                                </span>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endif; ?>

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
                        <div class="emp-optimization-card" style="margin-bottom: 20px;">
                            <h3 class="emp-card-title" style="margin-bottom: 16px;">Expected Performance</h3>

                            <div class="emp-perf-grid">
                                <!-- Applicants Tile -->
                                <div class="emp-perf-tile" style="background: #FFFFFF; border-color: #DBEAFE;">
                                    <div class="emp-perf-num">120+</div>
                                    <div class="emp-perf-sub">Expected Applicants</div>
                                </div>

                                <!-- Visibility Tile -->
                                <div class="emp-perf-tile" style="background: #FFFFFF; border-color: #DBEAFE;">
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
                        <div class="emp-optimization-card" style="margin-bottom: 20px;">
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
                                    <span style="background: #FFFFFF; border: 1px solid #DBEAFE; border-radius: 6px; padding: 4px 10px; font-size: 12px; font-weight: 600; color: #4F48E6; white-space: nowrap !important; flex-shrink: 0 !important;">Figma</span>
                                    <span style="background: #FFFFFF; border: 1px solid #DBEAFE; border-radius: 6px; padding: 4px 10px; font-size: 12px; font-weight: 600; color: #4F48E6; white-space: nowrap !important; flex-shrink: 0 !important;">Research</span>
                                    <span style="background: #FFFFFF; border: 1px solid #DBEAFE; border-radius: 6px; padding: 4px 10px; font-size: 12px; font-weight: 600; color: #4F48E6; white-space: nowrap !important; flex-shrink: 0 !important;">Prototyping</span>
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
