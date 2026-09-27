<?php
declare(strict_types=1);
/**
 * View: Create New Opportunity (Post Internship)
 * Route: /employer/internships/create
 * Layout: dashboard (Employer Portal)
 * 
 * @var array  $employer Employer profile data
 * @var array  $draft    Draft internship data from session or defaults
 * @var string $activeNav Active navigation key ('post')
 */

$draftTitle          = $draft['title'] ?? 'Product Design Intern';
$draftCategory       = $draft['category'] ?? 'Technology';
$draftType           = $draft['internship_type'] ?? 'Remote';
$draftLocation       = $draft['location'] ?? 'Remote';
$draftAbout          = $draft['about'] ?? 'We are looking for a passionate Product Design Intern to join our remote team. You will work closely with our senior designers and product managers to create intuitive, beautiful, and functional interfaces for the InternConnect platform. This is an incredible opportunity to impact a product used by thousands of students and employers globally.';

// Key Responsibilities: start with 1 clean single-line item by default with helpful placeholder
$draftResponsibilitiesTitle = $draft['responsibilities_title'] ?? 'Key Responsibilities';
$draftResponsibilities      = !empty($draft['responsibilities']) && is_array($draft['responsibilities'])
    ? $draft['responsibilities']
    : [''];

// What We're Looking For: start with 1 clean single-line qualification card by default
$draftLookingForTitle       = $draft['looking_for_title'] ?? "What We're Looking For";
$draftLookingForCards       = !empty($draft['looking_for_cards']) && is_array($draft['looking_for_cards'])
    ? $draft['looking_for_cards']
    : [
        [
            'icon'  => 'target',
            'title' => '',
            'desc'  => ''
        ]
    ];

// Program Benefits: start with 1 clean single-line item by default
$draftBenefitsTitle         = $draft['benefits_title'] ?? 'Program Benefits';
$draftBenefits              = !empty($draft['benefits']) && is_array($draft['benefits'])
    ? $draft['benefits']
    : [''];

$draftSkills         = $draft['skills'] ?? ['Figma', 'UI/UX Design', 'Wireframing', 'Prototyping', 'Design Systems'];
$rawEdu              = $draft['education_level'] ?? 'Undergraduate';
$draftEducationLevels = is_array($rawEdu) ? $rawEdu : array_values(array_filter(array_map('trim', explode(',', (string)$rawEdu))));
if (empty($draftEducationLevels)) {
    $draftEducationLevels = ['Undergraduate'];
}
$draftEducation      = implode(', ', $draftEducationLevels);
$draftExperience     = $draft['experience_level'] ?? 'No Experience';
$draftStartDate      = $draft['start_date'] ?? '2026-06-01';
$draftDuration       = (!empty($draft['duration']) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $draft['duration'])) ? $draft['duration'] : '2026-08-31';
$draftOpenings       = $draft['openings'] ?? 10;
$draftStipend        = $draft['stipend_amount'] ?? '2000';
$draftIsUnpaid       = !empty($draft['is_unpaid']);
$draftPerks          = $draft['perks'] ?? ['Certificate', 'Letter of Recommendation', 'Flexible work hours'];
$draftDeadline       = $draft['deadline'] ?? '2026-05-15';
$draftNumInterns     = $draft['num_interns'] ?? 10;
$draftAllowRemote    = $draft['allow_remote'] ?? true;

$validInterviewTypes = [
    'Video Interview (Online Meeting Link)',
    'In-person Interview (Physical Interview)'
];
$draftInterviewType  = $draft['interview_type'] ?? 'Video Interview (Online Meeting Link)';
if (!in_array($draftInterviewType, $validInterviewTypes, true)) {
    $draftInterviewType = 'Video Interview (Online Meeting Link)';
}

// Relevant Experience: configures Step 1 candidate experience questions
$draftRelevantExperience = !empty($draft['relevant_experience']) && is_array($draft['relevant_experience'])
    ? $draft['relevant_experience']
    : [
        ['id' => 1, 'label' => '1. Job Title', 'type' => 'Text Input', 'required' => true],
        ['id' => 2, 'label' => '2. Company', 'type' => 'Text Input', 'required' => true],
        ['id' => 3, 'label' => '3. Salary Expectation ($/month)', 'type' => 'Currency / Number', 'required' => true],
    ];

// Screening Questions: configures Step 3 candidate questions (Matches Screenshot 1)
$draftQuestions      = !empty($draft['screening_questions']) && is_array($draft['screening_questions'])
    ? $draft['screening_questions']
    : [
        ['id' => 1, 'text' => 'Why are you interested in this specific internship role?', 'type' => 'Short Answer'],
        ['id' => 2, 'text' => 'Share a link to your portfolio or GitHub profile.', 'type' => 'Link / URL'],
    ];
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
            <script>
            window.__postInternshipInitialData = <?= json_encode([
                'skills'                => $draftSkills,
                'category'              => $draftCategory,
                'type'                  => $draftType,
                'education'             => $draftEducation,
                'educationLevels'       => $draftEducationLevels,
                'experience'            => $draftExperience,
                'duration'              => $draftDuration,
                'interviewType'         => $draftInterviewType,
                'isUnpaid'              => $draftIsUnpaid,
                'allowRemote'           => $draftAllowRemote,
                'relevantExperience'    => $draftRelevantExperience,
                'questions'             => $draftQuestions,
                'responsibilitiesTitle' => $draftResponsibilitiesTitle,
                'responsibilities'      => $draftResponsibilities,
                'lookingForTitle'       => $draftLookingForTitle,
                'lookingForCards'       => $draftLookingForCards,
                'benefitsTitle'         => $draftBenefitsTitle,
                'benefits'              => $draftBenefits,
            ], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>;
            </script>
            <div class="emp-post-container" x-data="postInternshipForm()">

                <!-- Header Title & Subtitle -->
                <div class="emp-post-header">
                    <div>
                        <h1 class="emp-post-title">Create New Opportunity</h1>
                        <p class="emp-post-subtitle">Reach thousands of talented students by posting your internship requirements.</p>
                    </div>
                </div>

                <!-- Main Form (Submits to /employer/internships/create or review) -->
                <form id="postInternshipForm" action="<?= url('/employer/internships/create') ?>" method="POST">
                    <input type="hidden" name="action" id="formActionInput" value="preview">
                    <input type="hidden" name="skills" :value="JSON.stringify(skills)">
                    <input type="hidden" name="relevant_experience" :value="JSON.stringify(relevantExperience)">
                    <input type="hidden" name="screening_questions" :value="JSON.stringify(questions)">
                    <input type="hidden" name="category" :value="category">
                    <input type="hidden" name="internship_type" :value="type">
                    <input type="hidden" name="education_level" :value="education">
                    <input type="hidden" name="experience_level" :value="experience">
                    <input type="hidden" name="duration" :value="duration">
                    <input type="hidden" name="interview_type" :value="interviewType">
                    <input type="hidden" name="responsibilities_title" :value="responsibilitiesTitle">
                    <input type="hidden" name="responsibilities" :value="JSON.stringify(responsibilities)">
                    <input type="hidden" name="looking_for_title" :value="lookingForTitle">
                    <input type="hidden" name="looking_for_cards" :value="JSON.stringify(lookingForCards)">
                    <input type="hidden" name="benefits_title" :value="benefitsTitle">
                    <input type="hidden" name="benefits" :value="JSON.stringify(benefits)">

                    <div class="emp-post-grid">
                        
                        <!-- ════════════════════════════════════════════════════
                             LEFT COLUMN (Core Opportunity Details)
                             Order:
                             1. Basic Information
                             2. Requirements
                             3. Details & Duration
                             4. Perks & Stipend
                             5. Role Overview
                             6. Key Responsibilities
                             7. What We're Looking For
                             8. Program Benefits
                        ════════════════════════════════════════════════════ -->
                        <div class="emp-post-col-left">

                            <!-- ── 1. Basic Information ── -->
                            <div class="emp-form-card">
                                <div class="emp-card-header">
                                    <div class="emp-card-icon-badge">
                                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <h2 class="emp-card-title">Basic Information</h2>
                                </div>

                                <!-- Internship Title -->
                                <div class="emp-field-group">
                                    <label class="emp-field-label" for="field_title">Internship Title</label>
                                    <input type="text" 
                                           id="field_title" 
                                           name="title" 
                                           class="emp-input-text" 
                                           placeholder="e.g., UI/UX Design Intern" 
                                           value="<?= htmlspecialchars($draftTitle) ?>" 
                                           required>
                                </div>

                                <!-- Category & Internship Type (Row) -->
                                <div class="emp-form-grid-2col emp-field-group">
                                    
                                    <!-- Category Custom Alpine Dropdown -->
                                    <div>
                                        <label class="emp-field-label">Category</label>
                                        <div class="db-dropdown-wrap" x-data="{ 
                                            open: false, 
                                            search: '', 
                                            tempCategory: '<?= htmlspecialchars($draftCategory) ?>',
                                            options: ['Technology', 'Creative', 'Business', 'Finance', 'Engineering', 'Education', 'Media & Communication']
                                        }" @click.outside="open = false">
                                            
                                            <button type="button" class="db-dropdown-btn" @click="open = !open" :class="{ 'active': open }">
                                                <span x-text="category || 'Select Category'">Select Category</span>
                                                <svg class="db-dropdown-chevron" :style="open ? 'transform: rotate(180deg)' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                                </svg>
                                            </button>

                                            <!-- Dropdown Menu with Search, Checkboxes & Apply -->
                                            <div class="db-dropdown-menu" x-show="open" x-transition style="display: none;">
                                                <div class="db-dropdown-search-wrap">
                                                    <svg class="db-dropdown-search-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                                    </svg>
                                                    <input type="text" x-model="search" class="db-dropdown-search-input" placeholder="Search category...">
                                                </div>

                                                <div class="db-dropdown-list">
                                                    <template x-for="item in options.filter(o => o.toLowerCase().includes(search.toLowerCase()))" :key="item">
                                                        <label class="db-dropdown-item">
                                                            <span x-text="item"></span>
                                                            <input type="radio" name="cat_radio" :value="item" :checked="tempCategory === item" @change="tempCategory = item">
                                                        </label>
                                                    </template>
                                                </div>

                                                <button type="button" class="db-dropdown-apply-btn" @click="category = tempCategory; open = false">Apply</button>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Internship Type Segmented Control -->
                                    <div>
                                        <label class="emp-field-label">Internship Type</label>
                                        <div class="emp-segmented-control">
                                            <button type="button" 
                                                    class="emp-segment-btn" 
                                                    :class="{ 'active': type === 'Remote' }" 
                                                    @click="type = 'Remote'">Remote</button>
                                            <button type="button" 
                                                    class="emp-segment-btn" 
                                                    :class="{ 'active': type === 'Hybrid' }" 
                                                    @click="type = 'Hybrid'">Hybrid</button>
                                            <button type="button" 
                                                    class="emp-segment-btn" 
                                                    :class="{ 'active': type === 'On-site' }" 
                                                    @click="type = 'On-site'">On-site</button>
                                        </div>
                                    </div>

                                </div>

                                <!-- Location -->
                                <div class="emp-field-group">
                                    <label class="emp-field-label" for="field_location">Location</label>
                                    <div class="emp-input-with-icon">
                                        <span class="emp-input-icon-slot">
                                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                        </span>
                                        <input type="text" 
                                               id="field_location" 
                                               name="location" 
                                               class="emp-input-text" 
                                               placeholder="City or Remote" 
                                               value="<?= htmlspecialchars($draftLocation) ?>">
                                    </div>
                                </div>
                            </div>

                            <!-- ── 2. Requirements ── -->
                            <div class="emp-form-card">
                                <div class="emp-card-header">
                                    <div class="emp-card-icon-badge">
                                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                        </svg>
                                    </div>
                                    <h2 class="emp-card-title">Requirements</h2>
                                </div>

                                <!-- Required Skills Tags -->
                                <div class="emp-field-group">
                                    <label class="emp-field-label">Required Skills</label>
                                    <div class="emp-tag-wrap" x-data="{ newSkill: '', adding: false }">
                                        <template x-for="(skill, index) in skills" :key="index">
                                            <span class="emp-tag-pill">
                                                <span x-text="skill"></span>
                                                <button type="button" class="emp-tag-remove-btn" @click="removeSkill(index)">
                                                    <svg style="width: 12px; height: 12px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                </button>
                                            </span>
                                        </template>

                                        <!-- Add Skill trigger / input -->
                                        <div style="display: inline-flex; align-items: center;">
                                            <template x-if="!adding">
                                                <button type="button" class="emp-tag-add-btn" @click="adding = true; $nextTick(() => $refs.skillInput.focus())">+ Add Skill</button>
                                            </template>
                                            <template x-if="adding">
                                                <div style="display: inline-flex; align-items: center; gap: 4px; background: #fff; border: 1px solid #4F48E6; border-radius: 99px; padding: 2px 8px;">
                                                    <input type="text" 
                                                           x-ref="skillInput"
                                                           x-model="newSkill" 
                                                           @keydown.enter.prevent="if(newSkill.trim()){ addSkill(newSkill.trim()); newSkill = ''; adding = false; }"
                                                           @keydown.escape="adding = false"
                                                           placeholder="Skill name..."
                                                           style="border: none; outline: none; font-size: 12px; width: 90px; color: #0F172A;">
                                                    <button type="button" @click="if(newSkill.trim()){ addSkill(newSkill.trim()); newSkill = ''; } adding = false;" style="background: #4F48E6; color: #fff; border: none; border-radius: 50%; width: 18px; height: 18px; font-size: 10px; cursor: pointer; display: flex; align-items: center; justify-content: center;">
                                                        <svg style="width: 10px; height: 10px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                                        </svg>
                                                    </button>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </div>

                                <!-- Education Level & Experience Level (Row) -->
                                <div class="emp-form-grid-2col emp-field-group">
                                    
                                    <!-- Education Level Multi-Select Dropdown -->
                                    <div>
                                        <label class="emp-field-label">Education Level</label>
                                        <div class="db-dropdown-wrap" x-data="{ 
                                            open: false, 
                                            tempLevels: [...educationLevels],
                                            options: ['Undergraduate', 'Graduate', 'Masters', 'Diploma', 'Higher Degree', 'HND', 'OND', 'Bootcamp']
                                        }" @click.outside="open = false">
                                            
                                            <button type="button" class="db-dropdown-btn" @click="open = !open; if (open) tempLevels = [...educationLevels]">
                                                <span x-text="educationLevels.length === 0 ? 'Select Education Levels' : (educationLevels.length === 1 ? educationLevels[0] : educationLevels.length + ' Levels Selected')">Undergraduate</span>
                                                <svg class="db-dropdown-chevron" :style="open ? 'transform: rotate(180deg)' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                                </svg>
                                            </button>

                                            <div class="db-dropdown-menu" x-show="open" x-transition style="display: none;">
                                                <div class="db-dropdown-list">
                                                    <template x-for="item in options" :key="item">
                                                        <label class="db-dropdown-item" style="cursor: pointer;">
                                                            <span x-text="item"></span>
                                                            <input type="checkbox" 
                                                                   :value="item" 
                                                                   :checked="tempLevels.includes(item)" 
                                                                   @change="if ($event.target.checked) { if (!tempLevels.includes(item)) tempLevels.push(item); } else { tempLevels = tempLevels.filter(i => i !== item); }">
                                                        </label>
                                                    </template>
                                                </div>
                                                <button type="button" class="db-dropdown-apply-btn" @click="educationLevels = [...tempLevels]; education = educationLevels.join(', '); open = false">Apply</button>
                                            </div>
                                        </div>

                                        <!-- Selected Education Level Tag Pills -->
                                        <div class="emp-tag-wrap" style="margin-top: 8px; display: flex; flex-wrap: wrap; gap: 6px;" x-show="educationLevels.length > 0">
                                            <template x-for="(lvl, lIdx) in educationLevels" :key="lIdx">
                                                <div class="emp-tag-pill" style="border-radius: 99px; padding: 4px 10px; font-size: 11.5px;">
                                                    <span x-text="lvl"></span>
                                                    <button type="button" class="emp-tag-remove" @click="removeEducationLevel(lvl)" style="cursor: pointer; display: inline-flex; align-items: center; justify-content: center; margin-left: 4px;">
                                                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" style="width: 12px; height: 12px;">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                                        </svg>
                                                    </button>
                                                </div>
                                            </template>
                                        </div>
                                    </div>

                                    <!-- Experience Level Dropdown -->
                                    <div>
                                        <label class="emp-field-label">Experience Level (Optional)</label>
                                        <div class="db-dropdown-wrap" x-data="{ 
                                            open: false, 
                                            tempExp: '<?= htmlspecialchars($draftExperience) ?>',
                                            options: ['No Experience', '1-2 Years', '2-3 Years', '3-4 Years', '4-5 Years', '5-6 Years', '6-7 Years', '7-8 Years', '8-9 Years', '9-10 Years', '10+ Years']
                                        }" @click.outside="open = false">
                                            
                                            <button type="button" class="db-dropdown-btn" @click="open = !open">
                                                <span x-text="experience || 'No Experience'">No Experience</span>
                                                <svg class="db-dropdown-chevron" :style="open ? 'transform: rotate(180deg)' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                                </svg>
                                            </button>

                                            <div class="db-dropdown-menu" x-show="open" x-transition style="display: none;">
                                                <div class="db-dropdown-list">
                                                    <template x-for="item in options" :key="item">
                                                        <label class="db-dropdown-item">
                                                            <span x-text="item"></span>
                                                            <input type="radio" name="exp_radio" :value="item" :checked="tempExp === item" @change="tempExp = item">
                                                        </label>
                                                    </template>
                                                </div>
                                                <button type="button" class="db-dropdown-apply-btn" @click="experience = tempExp; open = false">Apply</button>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <!-- ── 3. Details & Duration ── -->
                            <div class="emp-form-card">
                                <div class="emp-card-header">
                                    <div class="emp-card-icon-badge">
                                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <h2 class="emp-card-title">Details & Duration</h2>
                                </div>

                                <div class="emp-form-grid-3col emp-field-group">
                                    
                                    <!-- Start Date -->
                                    <div>
                                        <label class="emp-field-label" for="field_start_date">Start Date</label>
                                        <input type="date" 
                                               id="field_start_date" 
                                               name="start_date" 
                                               class="emp-input-text" 
                                               value="<?= htmlspecialchars($draftStartDate) ?>">
                                    </div>

                                    <!-- Duration (Optional) Date Input -->
                                    <div>
                                        <label class="emp-field-label" for="field_duration">Duration (Optional)</label>
                                        <input type="date" 
                                               id="field_duration" 
                                               name="duration" 
                                               class="emp-input-text" 
                                               x-model="duration" 
                                               value="<?= htmlspecialchars($draftDuration) ?>">
                                    </div>

                                    <!-- Openings -->
                                    <div>
                                        <label class="emp-field-label" for="field_openings">Openings</label>
                                        <input type="number" 
                                               id="field_openings" 
                                               name="openings" 
                                               class="emp-input-text" 
                                               min="1" 
                                               value="<?= htmlspecialchars((string)$draftOpenings) ?>">
                                    </div>

                                </div>
                            </div>

                            <!-- ── 4. Perks & Stipend ── -->
                            <div class="emp-form-card">
                                <div class="emp-card-header">
                                    <div class="emp-card-icon-badge">
                                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <h2 class="emp-card-title">Perks & Stipend</h2>
                                </div>

                                <div class="emp-form-grid-2col">
                                    
                                    <!-- Stipend Amount -->
                                    <div>
                                        <label class="emp-field-label">Stipend Amount (Monthly)</label>
                                        <div style="display: flex; align-items: center; border: 1px solid #E2E8F0; border-radius: 10px; overflow: hidden; background: #fff;" class="emp-field-group">
                                            <span style="background: #F8FAFC; border-right: 1px solid #E2E8F0; padding: 11px 16px; font-weight: 700; color: #475569; font-size: 14px;">$</span>
                                            <input type="text" 
                                                   name="stipend_amount" 
                                                   class="emp-input-text" 
                                                   :disabled="isUnpaid" 
                                                   value="<?= htmlspecialchars($draftStipend) ?>" 
                                                   placeholder="2000" 
                                                   style="border: none; border-radius: 0;">
                                        </div>

                                        <label style="display: flex; align-items: center; gap: 8px; font-size: 13px; color: #475569; cursor: pointer; margin-top: 10px;">
                                            <input type="checkbox" 
                                                   name="is_unpaid" 
                                                   value="1" 
                                                   x-model="isUnpaid" 
                                                   style="accent-color: #4F48E6; width: 16px; height: 16px;">
                                            <span>Unpaid / Volunteering</span>
                                        </label>
                                    </div>

                                    <!-- Perks Checkboxes -->
                                    <div>
                                        <label class="emp-field-label">Perks</label>
                                        <div class="emp-perks-grid">
                                            <label class="emp-perk-card">
                                                <input type="checkbox" name="perks[]" value="Certificate" <?= in_array('Certificate', $draftPerks, true) ? 'checked' : '' ?>>
                                                <span>Certificate</span>
                                            </label>
                                            <label class="emp-perk-card">
                                                <input type="checkbox" name="perks[]" value="Letter of Recommendation" <?= in_array('Letter of Recommendation', $draftPerks, true) ? 'checked' : '' ?>>
                                                <span>Letter of Recommendation</span>
                                            </label>
                                            <label class="emp-perk-card">
                                                <input type="checkbox" name="perks[]" value="Flexible work hours" <?= in_array('Flexible work hours', $draftPerks, true) ? 'checked' : '' ?>>
                                                <span>Flexible work hours</span>
                                            </label>
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <!-- ── 5. Role Overview ── -->
                            <div class="emp-form-card">
                                <div class="emp-card-header">
                                    <div class="emp-card-icon-badge">
                                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h7" />
                                        </svg>
                                    </div>
                                    <h2 class="emp-card-title">Role Overview</h2>
                                </div>

                                <!-- Narrative Textarea with Bold, Italic & Working Paragraph WYSIWYG Toolbar -->
                                <div class="emp-field-group">
                                    <label class="emp-field-label">About the Internship</label>
                                    <div class="emp-richtext-wrap">
                                        <div class="emp-richtext-toolbar">
                                            <button type="button" class="emp-toolbar-btn" title="Bold (Ctrl+B)" @mousedown.prevent="formatDoc('bold')">
                                                <span style="font-weight: 800; font-size: 13px;">B</span>
                                            </button>
                                            <button type="button" class="emp-toolbar-btn" title="Italic (Ctrl+I)" @mousedown.prevent="formatDoc('italic')">
                                                <span style="font-style: italic; font-weight: 700; font-size: 13px; font-family: serif;">I</span>
                                            </button>
                                            <button type="button" class="emp-toolbar-btn" title="Format Paragraph" @mousedown.prevent="formatParagraph()">
                                                <svg style="width: 14px; height: 14px; flex-shrink: 0 !important;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h10" />
                                                </svg>
                                                <span style="font-size: 12px; font-weight: 700; margin-left: 2px;">¶</span>
                                            </button>
                                        </div>
                                        <!-- Visual Editable WYSIWYG Div (min-height: 220px) -->
                                        <div contenteditable="true" 
                                             class="emp-richtext-editor" 
                                             x-ref="editorArea"
                                             @input="syncEditor()"
                                             @blur="syncEditor()"
                                             placeholder="Describe the opportunity, company mission, team culture, and learning outcomes..."></div>
                                        <textarea name="about" x-ref="aboutHidden" style="display: none;"><?= htmlspecialchars($draftAbout) ?></textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- ── 6. Key Responsibilities (Single-Line Dynamic Builder) ── -->
                            <div class="emp-form-card">
                                <div class="emp-card-header" style="align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px;">
                                    <div style="display: flex; align-items: center; gap: 12px; flex: 1; min-width: 240px;">
                                        <div class="emp-card-icon-badge">
                                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                                            </svg>
                                        </div>
                                        <div class="emp-section-title-wrap">
                                            <div class="emp-title-edit-container">
                                                <input type="text" 
                                                       class="emp-section-title-input" 
                                                       x-model="responsibilitiesTitle" 
                                                       placeholder="Key Responsibilities">
                                                <span class="emp-title-edit-hint" title="Click to rename this section">
                                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                    </svg>
                                                    <span>Editable Title</span>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="emp-card-info-wrap" x-data="{ showInfo: false }" @mouseenter="showInfo = true" @mouseleave="showInfo = false">
                                        <button type="button" class="emp-card-info-btn">
                                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </button>
                                        <div class="emp-card-info-popover" x-show="showInfo" x-transition x-cloak style="display: none;">
                                            <div class="emp-card-info-popover-title">Key Responsibilities Guidance</div>
                                            <p class="emp-card-info-popover-text">List day-to-day duties, project ownership, deliverables, and core tasks the intern will handle during this role.</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="emp-field-group">
                                    <div class="emp-dyn-list">
                                        <template x-for="(item, idx) in responsibilities" :key="idx">
                                            <div class="emp-dyn-item">
                                                <div class="emp-dyn-icon">
                                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                </div>
                                                <input type="text" 
                                                       class="emp-dyn-input" 
                                                       x-model="responsibilities[idx]" 
                                                       placeholder="e.g. Collaborate with cross-functional teams to define, design, and ship new features">
                                                <button type="button" 
                                                        class="emp-dyn-delete-btn" 
                                                        @click="removeResponsibility(idx)" 
                                                        title="Remove responsibility">
                                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </template>
                                    </div>

                                    <button type="button" class="emp-dyn-add-btn" @click="addResponsibility()">
                                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                                        </svg>
                                        <span>Add Responsibility</span>
                                    </button>
                                </div>
                            </div>

                            <!-- ── 7. What We're Looking For (Single-Line Qualification Cards) ── -->
                            <div class="emp-form-card">
                                <div class="emp-card-header" style="align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px;">
                                    <div style="display: flex; align-items: center; gap: 12px; flex: 1; min-width: 240px;">
                                        <div class="emp-card-icon-badge">
                                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                                            </svg>
                                        </div>
                                        <div class="emp-section-title-wrap">
                                            <div class="emp-title-edit-container">
                                                <input type="text" 
                                                       class="emp-section-title-input" 
                                                       x-model="lookingForTitle" 
                                                       placeholder="What We're Looking For">
                                                <span class="emp-title-edit-hint" title="Click to rename this section">
                                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                    </svg>
                                                    <span>Editable Title</span>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="emp-card-info-wrap" x-data="{ showInfo: false }" @mouseenter="showInfo = true" @mouseleave="showInfo = false">
                                        <button type="button" class="emp-card-info-btn">
                                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </button>
                                        <div class="emp-card-info-popover" x-show="showInfo" x-transition x-cloak style="display: none;">
                                            <div class="emp-card-info-popover-title">Qualifications Guidance</div>
                                            <p class="emp-card-info-popover-text">Define required competencies, degree background, technical proficiencies, and teamwork mindset expected of applicants.</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="emp-field-group">
                                    <div class="emp-qual-list">
                                        <template x-for="(card, idx) in lookingForCards" :key="idx">
                                            <div class="emp-qual-row">
                                                <!-- Icon Picker Dropdown -->
                                                <div class="emp-qual-icon-picker" @click.outside="if (openIconPicker === idx) openIconPicker = null">
                                                    <button type="button" 
                                                            class="emp-qual-icon-btn" 
                                                            @click="openIconPicker = (openIconPicker === idx ? null : idx)">
                                                        <template x-if="card.icon === 'academic-cap' || card.icon === 'academic'">
                                                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5zm0 7v-6m-4-1.268A10.97 10.97 0 0012 21a10.97 10.97 0 004-0.732V14m-8 0l4 2 4-2"/></svg>
                                                        </template>
                                                        <template x-if="card.icon === 'code' || card.icon === 'technical'">
                                                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                                                        </template>
                                                        <template x-if="card.icon === 'users' || card.icon === 'collaboration'">
                                                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                                        </template>
                                                        <template x-if="card.icon === 'target' || card.icon === 'detail'">
                                                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="6"/><circle cx="12" cy="12" r="2"/></svg>
                                                        </template>
                                                        <template x-if="card.icon === 'sparkles'">
                                                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                                                        </template>
                                                        <template x-if="card.icon === 'light-bulb'">
                                                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
                                                        </template>
                                                        <template x-if="card.icon === 'briefcase'">
                                                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                                        </template>
                                                        <template x-if="card.icon === 'rocket'">
                                                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.59 14.37a6 6 0 01-5.84 7.38v-4.8m5.84-2.58a14.98 14.98 0 006.16-12.12A14.98 14.98 0 009.63 8.41m5.96 5.96a14.926 14.926 0 01-5.841 2.58m-.119-7.38a6 6 0 00-7.38 5.84h4.8m2.58-5.84a14.927 14.927 0 00-2.58 5.84m2.699-2.58a3 3 0 114.243-4.243 3 3 0 01-4.243 4.243z"/></svg>
                                                        </template>
                                                        <template x-if="card.icon === 'award'">
                                                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                                                        </template>
                                                        <template x-if="card.icon === 'chart'">
                                                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                                                        </template>
                                                        <template x-if="card.icon === 'laptop'">
                                                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                                        </template>
                                                        <template x-if="card.icon === 'shield'">
                                                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                                        </template>
                                                        <template x-if="card.icon === 'clock'">
                                                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                        </template>
                                                        <template x-if="card.icon === 'book'">
                                                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                                        </template>
                                                        <template x-if="card.icon === 'palette'">
                                                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/></svg>
                                                        </template>
                                                    </button>
                                                    <div class="emp-qual-icon-menu" x-show="openIconPicker === idx" x-cloak style="display: none;">
                                                        <button type="button" class="emp-qual-icon-opt" :class="{ 'active': card.icon === 'academic-cap' || card.icon === 'academic' }" @click="card.icon = 'academic-cap'; openIconPicker = null">
                                                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5zm0 7v-6m-4-1.268A10.97 10.97 0 0012 21a10.97 10.97 0 004-0.732V14m-8 0l4 2 4-2"/></svg>
                                                        </button>
                                                        <button type="button" class="emp-qual-icon-opt" :class="{ 'active': card.icon === 'code' || card.icon === 'technical' }" @click="card.icon = 'code'; openIconPicker = null">
                                                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                                                        </button>
                                                        <button type="button" class="emp-qual-icon-opt" :class="{ 'active': card.icon === 'users' || card.icon === 'collaboration' }" @click="card.icon = 'users'; openIconPicker = null">
                                                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                                        </button>
                                                        <button type="button" class="emp-qual-icon-opt" :class="{ 'active': card.icon === 'target' || card.icon === 'detail' }" @click="card.icon = 'target'; openIconPicker = null">
                                                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="6"/><circle cx="12" cy="12" r="2"/></svg>
                                                        </button>
                                                        <button type="button" class="emp-qual-icon-opt" :class="{ 'active': card.icon === 'sparkles' }" @click="card.icon = 'sparkles'; openIconPicker = null">
                                                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                                                        </button>
                                                        <button type="button" class="emp-qual-icon-opt" :class="{ 'active': card.icon === 'light-bulb' }" @click="card.icon = 'light-bulb'; openIconPicker = null">
                                                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
                                                        </button>
                                                        <button type="button" class="emp-qual-icon-opt" :class="{ 'active': card.icon === 'briefcase' }" @click="card.icon = 'briefcase'; openIconPicker = null">
                                                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                                        </button>
                                                        <button type="button" class="emp-qual-icon-opt" :class="{ 'active': card.icon === 'rocket' }" @click="card.icon = 'rocket'; openIconPicker = null">
                                                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.59 14.37a6 6 0 01-5.84 7.38v-4.8m5.84-2.58a14.98 14.98 0 006.16-12.12A14.98 14.98 0 009.63 8.41m5.96 5.96a14.926 14.926 0 01-5.841 2.58m-.119-7.38a6 6 0 00-7.38 5.84h4.8m2.58-5.84a14.927 14.927 0 00-2.58 5.84m2.699-2.58a3 3 0 114.243-4.243 3 3 0 01-4.243 4.243z"/></svg>
                                                        </button>
                                                        <button type="button" class="emp-qual-icon-opt" :class="{ 'active': card.icon === 'award' }" @click="card.icon = 'award'; openIconPicker = null">
                                                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                                                        </button>
                                                        <button type="button" class="emp-qual-icon-opt" :class="{ 'active': card.icon === 'chart' }" @click="card.icon = 'chart'; openIconPicker = null">
                                                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                                                        </button>
                                                        <button type="button" class="emp-qual-icon-opt" :class="{ 'active': card.icon === 'laptop' }" @click="card.icon = 'laptop'; openIconPicker = null">
                                                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                                        </button>
                                                        <button type="button" class="emp-qual-icon-opt" :class="{ 'active': card.icon === 'shield' }" @click="card.icon = 'shield'; openIconPicker = null">
                                                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                                        </button>
                                                        <button type="button" class="emp-qual-icon-opt" :class="{ 'active': card.icon === 'clock' }" @click="card.icon = 'clock'; openIconPicker = null">
                                                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                        </button>
                                                        <button type="button" class="emp-qual-icon-opt" :class="{ 'active': card.icon === 'book' }" @click="card.icon = 'book'; openIconPicker = null">
                                                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                                        </button>
                                                        <button type="button" class="emp-qual-icon-opt" :class="{ 'active': card.icon === 'palette' }" @click="card.icon = 'palette'; openIconPicker = null">
                                                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/></svg>
                                                        </button>
                                                    </div>
                                                </div>

                                                <!-- Title Input -->
                                                <input type="text" 
                                                       class="emp-qual-title-input" 
                                                       x-model="card.title" 
                                                       placeholder="Requirement title (e.g. Design / CS Background)"
                                                       maxlength="60">

                                                <!-- Description Input (Single line text input!) -->
                                                <input type="text" 
                                                       class="emp-qual-desc-input" 
                                                       x-model="card.desc" 
                                                       placeholder="Brief requirement description (e.g. Pursuing degree in Design or CS)..."
                                                       maxlength="120">

                                                <!-- Delete Button -->
                                                <button type="button" 
                                                        class="emp-dyn-delete-btn" 
                                                        @click="removeLookingCard(idx)" 
                                                        title="Remove card">
                                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </template>
                                    </div>

                                    <button type="button" class="emp-dyn-add-btn" @click="addLookingCard()">
                                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                                        </svg>
                                        <span>Add Qualification Card</span>
                                    </button>
                                </div>
                            </div>

                            <!-- ── 8. Program Benefits (Placed Under What We're Looking For) ── -->
                            <div class="emp-form-card">
                                <div class="emp-card-header" style="align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px;">
                                    <div style="display: flex; align-items: center; gap: 12px; flex: 1; min-width: 240px;">
                                        <div class="emp-card-icon-badge">
                                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7" />
                                            </svg>
                                        </div>
                                        <div class="emp-section-title-wrap">
                                            <div class="emp-title-edit-container">
                                                <input type="text" 
                                                       class="emp-section-title-input" 
                                                       x-model="benefitsTitle" 
                                                       placeholder="Program Benefits">
                                                <span class="emp-title-edit-hint" title="Click to rename this section">
                                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                    </svg>
                                                    <span>Editable Title</span>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="emp-card-info-wrap" x-data="{ showInfo: false }" @mouseenter="showInfo = true" @mouseleave="showInfo = false">
                                        <button type="button" class="emp-card-info-btn">
                                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </button>
                                        <div class="emp-card-info-popover" x-show="showInfo" x-transition x-cloak style="display: none;">
                                            <div class="emp-card-info-popover-title">Program Benefits Guidance</div>
                                            <p class="emp-card-info-popover-text">Detail stipends, learning budget, direct mentorship, flexible work hours, and potential return offer pathways.</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="emp-field-group">
                                    <div class="emp-dyn-list">
                                        <template x-for="(benefit, idx) in benefits" :key="idx">
                                            <div class="emp-dyn-item">
                                                <div class="emp-dyn-icon">
                                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                </div>
                                                <input type="text" 
                                                       class="emp-dyn-input" 
                                                       x-model="benefits[idx]" 
                                                       placeholder="e.g. Competitive Stipend, Return Offer Potential...">
                                                <button type="button" 
                                                        class="emp-dyn-delete-btn" 
                                                        @click="removeBenefit(idx)" 
                                                        title="Remove benefit">
                                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </template>
                                    </div>

                                    <button type="button" class="emp-dyn-add-btn" @click="addBenefit()">
                                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                                        </svg>
                                        <span>Add Benefit</span>
                                    </button>
                                </div>
                            </div>

                        </div>


                        <!-- ════════════════════════════════════════════════════
                             RIGHT COLUMN (AI Assistant, Settings, Experience, Questions)
                        ════════════════════════════════════════════════════ -->
                        <div class="emp-post-col-right">

                            <!-- ── Card 1: AI Internship Assistant ── -->
                            <div class="emp-ai-card">
                                <div class="emp-ai-header">
                                    <div class="emp-ai-avatar-wrap">
                                        <!-- Official Waving Jenny AI Chatbot Mascot Image -->
                                        <img src="<?= asset('assets/images/jenny_ai/jenny_ai_hi.png') ?>" alt="Jenny AI Assistant" class="emp-ai-mascot-img">
                                    </div>
                                    <div class="emp-ai-title-wrap">
                                        <h3 class="emp-ai-title">AI Internship Assistant</h3>
                                        <span class="emp-ai-tag">PREMIUM FEATURE</span>
                                    </div>
                                </div>

                                <p class="emp-ai-desc">Let our smart assistant help you craft the perfect internship posting in seconds.</p>

                                <div class="emp-ai-actions">
                                    <button type="button" class="emp-ai-action-btn" @click="suggestDescription()">
                                        <span>Generate description</span>
                                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                        </svg>
                                    </button>

                                    <button type="button" class="emp-ai-action-btn" @click="suggestSkills()">
                                        <span>Suggest required skills</span>
                                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                        </svg>
                                    </button>

                                    <button type="button" class="emp-ai-action-btn" @click="suggestRequirements()">
                                        <span>Suggest requirements</span>
                                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                        </svg>
                                    </button>
                                </div>

                                <!-- Social Proof Footer -->
                                <div class="emp-ai-social">
                                    <div class="emp-ai-avatars">
                                        <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=50&h=50&fit=crop&crop=faces" alt="Recruiter">
                                        <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=50&h=50&fit=crop&crop=faces" alt="Employer">
                                    </div>
                                    <span class="emp-ai-social-text">Used by 2.4k+ employers today</span>
                                </div>
                            </div>

                            <!-- ── Card 2: Application Settings ── -->
                            <div class="emp-form-card">
                                <div class="emp-card-header">
                                    <div class="emp-card-icon-badge">
                                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                        </svg>
                                    </div>
                                    <h2 class="emp-card-title">Application Settings</h2>
                                </div>

                                <!-- Application Deadline -->
                                <div class="emp-field-group">
                                    <label class="emp-field-label" for="field_deadline">Application Deadline</label>
                                    <input type="date" 
                                           id="field_deadline" 
                                           name="deadline" 
                                           class="emp-input-text" 
                                           value="<?= htmlspecialchars($draftDeadline) ?>">
                                </div>

                                <!-- Number of Interns Needed -->
                                <div class="emp-field-group">
                                    <label class="emp-field-label" for="field_num_interns">Number of Interns Needed</label>
                                    <input type="number" 
                                           id="field_num_interns" 
                                           name="num_interns" 
                                           class="emp-input-text" 
                                           min="1" 
                                           value="<?= htmlspecialchars((string)$draftNumInterns) ?>">
                                </div>

                                <!-- Allow Remote Applicants Toggle -->
                                <div class="emp-switch-wrap">
                                    <div class="emp-switch-info">
                                        <span class="emp-switch-title">Allow Remote Applicants</span>
                                        <span class="emp-switch-sub">Visible for worldwide talent</span>
                                    </div>
                                    <label class="emp-switch">
                                        <input type="checkbox" name="allow_remote" value="1" x-model="allowRemote">
                                        <span class="emp-slider"></span>
                                    </label>
                                </div>
                            </div>

                            <!-- ── Card 3: Interview Preferences ── -->
                            <div class="emp-form-card">
                                <div class="emp-card-header">
                                    <div class="emp-card-icon-badge">
                                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <h2 class="emp-card-title">Interview Preferences</h2>
                                </div>

                                <div class="emp-field-group">
                                    <label class="emp-field-label">Interview Type</label>
                                    <div class="db-dropdown-wrap" x-data="{ 
                                        open: false, 
                                        options: ['Video Interview (Online Meeting Link)', 'In-person Interview (Physical Interview)']
                                    }" @click.outside="open = false">
                                        
                                        <button type="button" class="db-dropdown-btn" @click="open = !open">
                                            <span x-text="interviewType">Video Interview (Online Meeting Link)</span>
                                            <svg class="db-dropdown-chevron" :style="open ? 'transform: rotate(180deg)' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                            </svg>
                                        </button>

                                        <div class="db-dropdown-menu" x-show="open" x-transition style="display: none;">
                                            <div class="db-dropdown-list">
                                                <template x-for="item in options" :key="item">
                                                    <div class="db-dropdown-item" @click="interviewType = item; open = false">
                                                        <span x-text="item"></span>
                                                        <svg x-show="interviewType === item" style="width: 14px; height: 14px; color: #4F48E6;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                                        </svg>
                                                    </div>
                                                </template>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Built-in Video Callout Banner -->
                                <div style="background: #F5F3FF; border: 1px solid #DDD6FE; border-radius: 10px; padding: 12px 14px; display: flex; align-items: flex-start; gap: 10px; margin-top: 14px;">
                                    <svg style="width: 16px; height: 16px; color: #7C3AED; flex-shrink: 0 !important; margin-top: 2px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                    </svg>
                                    <p style="font-size: 11.5px; color: #6D28D9; line-height: 1.5; margin: 0;">Our built-in video platform supports automatic transcription for better evaluations.</p>
                                </div>
                            </div>

                            <!-- ── Card: Screening Questions Process Notice (AI Blue Screen Style) ── -->
                            <div class="emp-optimization-card" style="margin-bottom: 20px; padding: 18px 20px;">
                                <div class="emp-card-header" style="margin-bottom: 0; align-items: flex-start;">
                                    <div class="emp-card-icon-badge" style="background: #DBEAFE; color: #1D4ED8;">
                                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="emp-card-title" style="margin: 0; font-size: 15px; color: #0F172A;">Screening Questions</h3>
                                        <p style="margin: 4px 0 0 0; font-size: 12.5px; color: #475569; line-height: 1.45;">
                                            These are questions that will be asked to the candidate during the onboarding application process.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- ── Card 4: Relevant Experience (Candidate Step 1 Fields) ── -->
                            <div class="emp-form-card">
                                <div class="emp-card-header" style="align-items: flex-start; justify-content: space-between;">
                                    <div style="display: flex; align-items: flex-start; gap: 12px;">
                                        <div class="emp-card-icon-badge">
                                            <!-- Briefcase / Experience SVG -->
                                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <h2 class="emp-card-title" style="margin: 0;">Relevant Experience</h2>
                                            <p style="font-size: 11.5px; color: #64748B; margin: 2px 0 0 0;">Configured fields shown in Candidate Step 1</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="emp-field-group">
                                    <template x-for="(exp, idx) in relevantExperience" :key="exp.id">
                                        <div class="emp-question-item">
                                            <div style="min-width: 0; flex: 1;">
                                                <div class="emp-question-text" x-text="exp.label"></div>
                                                <div class="emp-question-type" style="display: flex; align-items: center; gap: 6px; margin-top: 2px;">
                                                    <span x-text="exp.type"></span>
                                                    <span style="color: #94A3B8;">•</span>
                                                    <span style="color: #4F48E6; font-weight: 600;" x-text="exp.required ? 'Required' : 'Optional'"></span>
                                                </div>
                                            </div>
                                            <button type="button" class="emp-question-delete" @click="removeExperienceField(idx)" title="Remove field">
                                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </div>
                                    </template>
                                </div>

                                <button type="button" class="emp-add-question-btn" @click="openExperienceModal()">
                                    <svg style="width: 14px; height: 14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                                    </svg>
                                    <span>Add Experience Field</span>
                                </button>
                            </div>

                            <!-- ── Card 5: Screening Questions (Candidate Step 3 Questions) ── -->
                            <div class="emp-form-card">
                                <div class="emp-card-header" style="align-items: flex-start; justify-content: space-between;">
                                    <div style="display: flex; align-items: flex-start; gap: 12px;">
                                        <div class="emp-card-icon-badge">
                                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <h2 class="emp-card-title" style="margin: 0;">Screening Questions</h2>
                                            <p style="font-size: 11.5px; color: #64748B; margin: 2px 0 0 0;">Candidates will answer these in Step 3</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="emp-field-group">
                                    <template x-for="(q, idx) in questions" :key="q.id">
                                        <div class="emp-question-item">
                                            <div style="min-width: 0; flex: 1;">
                                                <div class="emp-question-text" x-text="q.text"></div>
                                                <div class="emp-question-type" x-text="q.type"></div>
                                            </div>
                                            <button type="button" class="emp-question-delete" @click="removeQuestion(idx)" title="Remove question">
                                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </div>
                                    </template>
                                </div>

                                <button type="button" class="emp-add-question-btn" @click="openQuestionModal()">
                                    <svg style="width: 14px; height: 14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                                    </svg>
                                    <span>Add Question</span>
                                </button>
                            </div>

                        </div>

                    </div>

                    <!-- ════════════════════════════════════════════════════
                         BOTTOM ACTION BAR
                    ════════════════════════════════════════════════════ -->
                    <div class="emp-post-actions-bar">
                        <a href="<?= url('/employer/dashboard') ?>" class="emp-btn-cancel">Cancel</a>

                        <div class="emp-post-actions-right">
                            <!-- Preview button redirects to review page -->
                            <button type="button" class="emp-btn-secondary" @click="submitAction('preview')">Preview</button>
                            
                            <!-- Save draft -->
                            <button type="button" class="emp-btn-draft" @click="submitAction('save_draft')">Save Draft</button>

                            <!-- Direct Publish -->
                            <button type="button" class="emp-btn-primary" @click="submitAction('publish')">Publish Internship</button>
                        </div>
                    </div>
                </form>

                <!-- ════════════════════════════════════════════════════
                     RELEVANT EXPERIENCE CUSTOM MODAL (NO NATIVE PROMPT)
                ════════════════════════════════════════════════════ -->
                <div class="db-offer-modal-backdrop" 
                     x-show="showExperienceModal" 
                     x-cloak 
                     style="display: none;" 
                     @keydown.escape.window="showExperienceModal = false">
                    <div class="db-offer-modal-dialog" @click.outside="showExperienceModal = false">
                        
                        <!-- Modal Header -->
                        <div style="display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 20px;">
                            <div style="display: flex; align-items: center; gap: 14px;">
                                <div class="db-offer-modal-icon-wrap message" style="margin-bottom: 0;">
                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="db-offer-modal-title" style="margin: 0; font-size: 19px;">Add Experience Field</h3>
                                    <p style="font-size: 13px; color: #64748B; margin: 3px 0 0 0;">Candidates will provide this in Step 1 of application.</p>
                                </div>
                            </div>
                            <button type="button" 
                                    @click="showExperienceModal = false" 
                                    style="background: none; border: none; color: #94A3B8; cursor: pointer; padding: 4px; border-radius: 6px; display: flex; align-items: center; justify-content: center; transition: color 0.15s ease;" 
                                    onmouseover="this.style.color='#0F172A'" 
                                    onmouseout="this.style.color='#94A3B8'">
                                <svg style="width: 20px; height: 20px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>

                        <!-- Field Label -->
                        <div style="margin-bottom: 16px;">
                            <label class="db-modal-field-label">Field Title / Label</label>
                            <input type="text" 
                                   class="emp-input-text" 
                                   x-model="newExpLabel" 
                                   placeholder="e.g., Previous Job Title or Years of Experience">
                        </div>

                        <!-- Field Type Custom Alpine Dropdown -->
                        <div style="margin-bottom: 16px;">
                            <label class="db-modal-field-label">Field Type</label>
                            <div class="db-dropdown-wrap" x-data="{ openExpTypeDropdown: false }" @click.outside="openExpTypeDropdown = false">
                                <button type="button" class="db-dropdown-btn" @click="openExpTypeDropdown = !openExpTypeDropdown" :class="{ 'active': openExpTypeDropdown }">
                                    <span x-text="newExpType">Text Input</span>
                                    <svg class="db-dropdown-chevron" :style="openExpTypeDropdown ? 'transform: rotate(180deg)' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                                <div class="db-dropdown-menu" x-show="openExpTypeDropdown" x-transition style="display: none;">
                                    <div class="db-dropdown-list">
                                        <template x-for="eType in ['Text Input', 'Currency / Number', 'Short Answer']" :key="eType">
                                            <label class="db-dropdown-item" @click="newExpType = eType; openExpTypeDropdown = false;">
                                                <span x-text="eType"></span>
                                                <input type="radio" name="modal_exptype" :value="eType" :checked="newExpType === eType">
                                            </label>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Required Toggle -->
                        <div style="margin-bottom: 24px;">
                            <label style="display: flex; align-items: center; gap: 8px; font-size: 13px; color: #475569; cursor: pointer;">
                                <input type="checkbox" x-model="newExpRequired" style="accent-color: #4F48E6; width: 16px; height: 16px;">
                                <span>Mark as mandatory / required</span>
                            </label>
                        </div>

                        <!-- Modal Actions -->
                        <div class="db-offer-modal-actions" style="margin-top: 20px;">
                            <button type="button" class="db-offer-modal-btn-cancel" @click="showExperienceModal = false">Cancel</button>
                            <button type="button" class="db-offer-modal-btn-primary" @click="submitExperienceModal()" style="background-color: #4F48E6;">
                                <span>Add Field</span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- ════════════════════════════════════════════════════
                     SCREENING QUESTION CUSTOM MODAL (NO NATIVE PROMPT)
                ════════════════════════════════════════════════════ -->
                <div class="db-offer-modal-backdrop" 
                     x-show="showQuestionModal" 
                     x-cloak 
                     style="display: none;" 
                     @keydown.escape.window="showQuestionModal = false">
                    <div class="db-offer-modal-dialog" @click.outside="showQuestionModal = false">
                        
                        <!-- Modal Header -->
                        <div style="display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 20px;">
                            <div style="display: flex; align-items: center; gap: 14px;">
                                <div class="db-offer-modal-icon-wrap message" style="margin-bottom: 0;">
                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="db-offer-modal-title" style="margin: 0; font-size: 19px;">Add Screening Question</h3>
                                    <p style="font-size: 13px; color: #64748B; margin: 3px 0 0 0;">Candidates will be required to answer this during application.</p>
                                </div>
                            </div>
                            <button type="button" 
                                    @click="showQuestionModal = false" 
                                    style="background: none; border: none; color: #94A3B8; cursor: pointer; padding: 4px; border-radius: 6px; display: flex; align-items: center; justify-content: center; transition: color 0.15s ease;" 
                                    onmouseover="this.style.color='#0F172A'" 
                                    onmouseout="this.style.color='#94A3B8'">
                                <svg style="width: 20px; height: 20px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>

                        <!-- Question Text with Real-Time Word Count (Max 15 words) -->
                        <div style="margin-bottom: 16px;">
                            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 6px;">
                                <label class="db-modal-field-label" style="margin-bottom: 0;">Question Text</label>
                                <span style="font-size: 11.5px; font-weight: 600;" 
                                      :style="wordCount(newQuestionText) > 15 ? 'color: #EF4444;' : 'color: #64748B;'"
                                      x-text="wordCount(newQuestionText) + ' / 15 words max'">
                                </span>
                            </div>
                            <textarea class="db-modal-textarea" 
                                      x-model="newQuestionText" 
                                      placeholder="e.g., Why are you interested in this specific internship role?" 
                                      style="min-height: 85px;"
                                      :style="wordCount(newQuestionText) > 15 ? 'border-color: #EF4444; background: #FEF2F2;' : ''"></textarea>
                            <div x-show="wordCount(newQuestionText) > 15" x-cloak style="color: #EF4444; font-size: 11.5px; margin-top: 4px; font-weight: 500;">
                                Question cannot exceed 15 words.
                            </div>
                        </div>

                        <!-- Response Type Custom Alpine Dropdown (Matches Screenshot 2) -->
                        <div style="margin-bottom: 24px;">
                            <label class="db-modal-field-label">Response Type</label>
                            <div class="db-dropdown-wrap" x-data="{ openTypeDropdown: false }" @click.outside="openTypeDropdown = false">
                                <button type="button" class="db-dropdown-btn" @click="openTypeDropdown = !openTypeDropdown" :class="{ 'active': openTypeDropdown }">
                                    <span x-text="newQuestionType">Short Answer</span>
                                    <svg class="db-dropdown-chevron" :style="openTypeDropdown ? 'transform: rotate(180deg)' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                                <div class="db-dropdown-menu" x-show="openTypeDropdown" x-transition style="display: none;">
                                    <div class="db-dropdown-list">
                                        <template x-for="qType in ['Short Answer', 'Long Answer', 'Link / URL', 'Yes / No', 'File Upload (Portfolio)']" :key="qType">
                                            <label class="db-dropdown-item" @click="newQuestionType = qType; openTypeDropdown = false;">
                                                <span x-text="qType"></span>
                                                <input type="radio" name="modal_qtype" :value="qType" :checked="newQuestionType === qType">
                                            </label>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Modal Actions -->
                        <div class="db-offer-modal-actions" style="margin-top: 20px;">
                            <button type="button" class="db-offer-modal-btn-cancel" @click="showQuestionModal = false">Cancel</button>
                            <button type="button" 
                                    class="db-offer-modal-btn-primary" 
                                    @click="submitQuestionModal()" 
                                    :disabled="wordCount(newQuestionText) > 15 || !newQuestionText.trim()"
                                    :style="(wordCount(newQuestionText) > 15 || !newQuestionText.trim()) ? 'opacity: 0.5; cursor: not-allowed; background-color: #94A3B8;' : 'background-color: #4F48E6;'">
                                <span>Add Question</span>
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>

</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('postInternshipForm', () => {
        const initialData = window.__postInternshipInitialData || {};
        return {
            skills: Array.isArray(initialData.skills) ? initialData.skills : ['Figma', 'UI/UX Design', 'Wireframing', 'Prototyping', 'Design Systems'],
            category: initialData.category || 'Technology',
            type: initialData.type || 'Remote',
            educationLevels: Array.isArray(initialData.educationLevels) 
                ? initialData.educationLevels 
                : (initialData.education ? initialData.education.split(',').map(s => s.trim()).filter(Boolean) : ['Undergraduate']),
            education: initialData.education || 'Undergraduate',
            experience: initialData.experience || 'No Experience',
            duration: initialData.duration || '2026-08-31',
            interviewType: initialData.interviewType || 'Video Interview (Online Meeting Link)',
            isUnpaid: Boolean(initialData.isUnpaid),
            allowRemote: initialData.allowRemote !== undefined ? Boolean(initialData.allowRemote) : true,
            
            // Relevant Experience fields (Step 1 Candidate)
            relevantExperience: Array.isArray(initialData.relevantExperience) ? initialData.relevantExperience : [
                { id: 1, label: '1. Job Title', type: 'Text Input', required: true },
                { id: 2, label: '2. Company', type: 'Text Input', required: true },
                { id: 3, label: '3. Salary Expectation ($/month)', type: 'Currency / Number', required: true }
            ],
            
            // Screening Questions (Step 3 Candidate)
            questions: Array.isArray(initialData.questions) ? initialData.questions : [],
            responsibilitiesTitle: initialData.responsibilitiesTitle || 'Key Responsibilities',
            responsibilities: Array.isArray(initialData.responsibilities) && initialData.responsibilities.length > 0 ? initialData.responsibilities : [''],
            lookingForTitle: initialData.lookingForTitle || "What We're Looking For",
            lookingForCards: Array.isArray(initialData.lookingForCards) && initialData.lookingForCards.length > 0 ? initialData.lookingForCards : [{ icon: 'target', title: '', desc: '' }],
            benefitsTitle: initialData.benefitsTitle || 'Program Benefits',
            benefits: Array.isArray(initialData.benefits) && initialData.benefits.length > 0 ? initialData.benefits : [''],

            // Screening Question Modal state
            showQuestionModal: false,
            newQuestionText: '',
            newQuestionType: 'Short Answer',

            // Relevant Experience Modal state
            showExperienceModal: false,
            newExpLabel: '',
            newExpType: 'Text Input',
            newExpRequired: true,

            openIconPicker: null,

            init() {
                this.$nextTick(() => {
                    if (this.$refs.editorArea) {
                        const raw = this.$refs.aboutHidden ? this.$refs.aboutHidden.value : '';
                        if (raw) {
                            if (raw.includes('<p>') || raw.includes('<b>') || raw.includes('<i>') || raw.includes('<br>')) {
                                this.$refs.editorArea.innerHTML = raw;
                            } else {
                                const paragraphs = raw.split(/\n\s*\n/).map(p => `<p>${p.trim()}</p>`).join('');
                                this.$refs.editorArea.innerHTML = paragraphs || `<p>${raw}</p>`;
                            }
                        }
                    }
                });
            },

            formatDoc(cmd, val = null) {
                if (this.$refs.editorArea) {
                    this.$refs.editorArea.focus();
                }
                document.execCommand(cmd, false, val);
                this.syncEditor();
            },

            formatParagraph() {
                if (this.$refs.editorArea) {
                    this.$refs.editorArea.focus();
                }
                const sel = window.getSelection();
                let applied = false;

                try {
                    applied = document.execCommand('formatBlock', false, '<p>');
                } catch (e) {}

                if (!applied) {
                    try {
                        applied = document.execCommand('formatBlock', false, 'p');
                    } catch (e) {}
                }

                if (!applied && sel && sel.rangeCount > 0) {
                    const range = sel.getRangeAt(0);
                    if (!range.collapsed) {
                        const content = range.extractContents();
                        const p = document.createElement('p');
                        p.appendChild(content);
                        range.insertNode(p);
                        sel.removeAllRanges();
                        const newRange = document.createRange();
                        newRange.selectNodeContents(p);
                        sel.addRange(newRange);
                    } else {
                        const p = document.createElement('p');
                        p.innerHTML = '<br>';
                        range.insertNode(p);
                        range.selectNodeContents(p);
                        range.collapse(true);
                        sel.removeAllRanges();
                        sel.addRange(range);
                    }
                }

                this.syncEditor();
                if (this.$refs.editorArea) {
                    this.$refs.editorArea.focus();
                }
                this.$dispatch('show-toast', { text: 'Paragraph formatting applied.', type: 'info' });
            },

            syncEditor() {
                if (this.$refs.editorArea && this.$refs.aboutHidden) {
                    this.$refs.aboutHidden.value = this.$refs.editorArea.innerHTML.trim();
                }
            },

            addSkill(name) {
                if (name && !this.skills.includes(name)) {
                    this.skills.push(name);
                }
            },

            removeSkill(index) {
                this.skills.splice(index, 1);
            },

            removeEducationLevel(lvl) {
                this.educationLevels = this.educationLevels.filter(item => item !== lvl);
                this.education = this.educationLevels.join(', ');
            },

            addResponsibility() {
                this.responsibilities.push('');
            },

            removeResponsibility(index) {
                if (this.responsibilities.length > 1) {
                    this.responsibilities.splice(index, 1);
                } else {
                    this.responsibilities[0] = '';
                }
            },

            addLookingCard() {
                this.lookingForCards.push({
                    icon: 'target',
                    title: '',
                    desc: ''
                });
            },

            removeLookingCard(index) {
                if (this.lookingForCards.length > 1) {
                    this.lookingForCards.splice(index, 1);
                } else {
                    this.lookingForCards[0] = { icon: 'target', title: '', desc: '' };
                }
            },

            addBenefit() {
                this.benefits.push('');
            },

            removeBenefit(index) {
                if (this.benefits.length > 1) {
                    this.benefits.splice(index, 1);
                } else {
                    this.benefits[0] = '';
                }
            },

            // Relevant Experience Modal Methods
            openExperienceModal() {
                this.newExpLabel = '';
                this.newExpType = 'Text Input';
                this.newExpRequired = true;
                this.showExperienceModal = true;
            },

            submitExperienceModal() {
                if (!this.newExpLabel.trim()) {
                    this.$dispatch('show-toast', { text: 'Please enter field title.', type: 'error' });
                    return;
                }
                this.relevantExperience.push({
                    id: Date.now(),
                    label: this.newExpLabel.trim(),
                    type: this.newExpType,
                    required: this.newExpRequired
                });
                this.showExperienceModal = false;
                this.$dispatch('show-toast', { text: 'Experience field added successfully.', type: 'success' });
            },

            removeExperienceField(index) {
                this.relevantExperience.splice(index, 1);
                this.$dispatch('show-toast', { text: 'Field removed.', type: 'info' });
            },

            // Screening Question Modal Methods
            wordCount(text) {
                if (!text) return 0;
                const matches = text.trim().match(/\S+/g);
                return matches ? matches.length : 0;
            },

            openQuestionModal() {
                this.newQuestionText = '';
                this.newQuestionType = 'Short Answer';
                this.showQuestionModal = true;
            },

            submitQuestionModal() {
                const trimmed = this.newQuestionText.trim();
                if (!trimmed) {
                    this.$dispatch('show-toast', { text: 'Please enter question text.', type: 'error' });
                    return;
                }
                if (this.wordCount(trimmed) > 15) {
                    this.$dispatch('show-toast', { text: 'Question text cannot exceed 15 words.', type: 'error' });
                    return;
                }
                this.questions.push({
                    id: Date.now(),
                    text: trimmed,
                    type: this.newQuestionType
                });
                this.showQuestionModal = false;
                this.$dispatch('show-toast', { text: 'Screening question added successfully.', type: 'success' });
            },

            removeQuestion(index) {
                this.questions.splice(index, 1);
                this.$dispatch('show-toast', { text: 'Question removed.', type: 'info' });
            },

            suggestDescription() {
                const sampleText = '<p>We are seeking an ambitious <b>Product Design Intern</b> to join our fast-growing engineering team.</p><p>In this role, you will collaborate on production applications, design clean interfaces, and receive direct 1-on-1 mentorship to accelerate your industry skills.</p>';
                if (this.$refs.editorArea) {
                    this.$refs.editorArea.innerHTML = sampleText;
                }
                if (this.$refs.aboutHidden) {
                    this.$refs.aboutHidden.value = sampleText;
                }
                this.$dispatch('show-toast', { text: 'AI suggestion applied to Role Overview!', type: 'success' });
            },

            suggestSkills() {
                const suggestions = ['UI/UX', 'Wireframing', 'User Research', 'Design Systems'];
                suggestions.forEach(s => this.addSkill(s));
                this.$dispatch('show-toast', { text: 'AI added recommended skills: ' + suggestions.join(', '), type: 'success' });
            },

            suggestRequirements() {
                this.lookingForCards = [
                    {
                        icon: 'academic-cap',
                        title: 'Design or Technical Degree',
                        desc: 'Pursuing or holding a degree in Design, Computer Science, Human-Computer Interaction, or equivalent.'
                    },
                    {
                        icon: 'code',
                        title: 'Figma & Prototyping',
                        desc: 'Demonstrated experience with modern prototyping tools, wireframing, and interactive components.'
                    },
                    {
                        icon: 'users',
                        title: 'Collaboration & Agility',
                        desc: 'Eagerness to participate in sprints, team standups, and communicate design decisions clearly.'
                    },
                    {
                        icon: 'target',
                        title: 'Attention to Detail',
                        desc: 'Strong passion for pixel precision, user-centric thinking, and elegant simplicity.'
                    }
                ];
                this.$dispatch('show-toast', { text: 'AI generated qualification requirements!', type: 'success' });
            },

            submitAction(action) {
                this.syncEditor();
                const form = document.getElementById('postInternshipForm');
                const actionInput = document.getElementById('formActionInput');
                if (form && actionInput) {
                    actionInput.value = action;
                    form.submit();
                }
            }
        };
    });
});
</script>
