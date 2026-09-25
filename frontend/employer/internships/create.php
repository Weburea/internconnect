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
$draftWhatYouWillDo  = is_array($draft['what_you_will_do'] ?? null) 
    ? implode("\n", $draft['what_you_will_do']) 
    : "Collaborate with cross-functional teams to define, design, and new features.\nCreate wireframes, storyboards, user flows, and site maps to effectively communicate interaction and design ideas.\nConduct user research and evaluate user feedback to enhance the platform's UX.";
$draftWhatWeLookFor  = is_array($draft['what_we_look_for'] ?? null)
    ? implode("\n", $draft['what_we_look_for'])
    : "Demonstrated passion for user-centered digital design and interface prototyping.\nFamiliarity with modern design tools (Figma, Adobe XD) and component systems.\nClear communication and eagerness to learn from senior mentors in a fast-paced environment.";
$draftSkills         = $draft['skills'] ?? ['React', 'TypeScript', 'Figma'];
$draftEducation      = $draft['education_level'] ?? 'Undergraduate';
$draftExperience     = $draft['experience_level'] ?? 'No Experience';
$draftStartDate      = $draft['start_date'] ?? '2026-06-01';
$draftDuration       = $draft['duration'] ?? '3 months';
$draftOpenings       = $draft['openings'] ?? 10;
$draftStipend        = $draft['stipend_amount'] ?? '2000';
$draftIsUnpaid       = !empty($draft['is_unpaid']);
$draftPerks          = $draft['perks'] ?? ['Certificate', 'Letter of Recommendation', 'Flexible work hours'];
$draftDeadline       = $draft['deadline'] ?? '2026-05-15';
$draftNumInterns     = $draft['num_interns'] ?? 10;
$draftAllowRemote    = $draft['allow_remote'] ?? true;
$draftInterviewType  = $draft['interview_type'] ?? 'Video Interview (Google Meet)';
$draftQuestions      = $draft['screening_questions'] ?? [
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
            <div class="emp-post-container" 
                 x-data="postInternshipForm({
                     skills: <?= htmlspecialchars(json_encode($draftSkills)) ?>,
                     category: '<?= htmlspecialchars($draftCategory) ?>',
                     type: '<?= htmlspecialchars($draftType) ?>',
                     education: '<?= htmlspecialchars($draftEducation) ?>',
                     experience: '<?= htmlspecialchars($draftExperience) ?>',
                     duration: '<?= htmlspecialchars($draftDuration) ?>',
                     interviewType: '<?= htmlspecialchars($draftInterviewType) ?>',
                     isUnpaid: <?= $draftIsUnpaid ? 'true' : 'false' ?>,
                     allowRemote: <?= $draftAllowRemote ? 'true' : 'false' ?>,
                     questions: <?= htmlspecialchars(json_encode($draftQuestions)) ?>
                 })">

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
                    <input type="hidden" name="screening_questions" :value="JSON.stringify(questions)">
                    <input type="hidden" name="category" :value="category">
                    <input type="hidden" name="internship_type" :value="type">
                    <input type="hidden" name="education_level" :value="education">
                    <input type="hidden" name="experience_level" :value="experience">
                    <input type="hidden" name="duration" :value="duration">
                    <input type="hidden" name="interview_type" :value="interviewType">

                    <div class="emp-post-grid">
                        
                        <!-- ════════════════════════════════════════════════════
                             LEFT COLUMN (Core Opportunity Details)
                        ════════════════════════════════════════════════════ -->
                        <div class="emp-post-col-left">

                            <!-- ── Card 1: Basic Information ── -->
                            <div class="emp-form-card">
                                <div class="emp-card-header">
                                    <div class="emp-card-icon-badge">
                                        <!-- Document / Info Icon SVG -->
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
                                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;" class="emp-field-group">
                                    
                                    <!-- Category Custom Alpine Dropdown (Image 4 Match) -->
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

                            <!-- ── Card 2: Role Overview ── -->
                            <div class="emp-form-card">
                                <div class="emp-card-header">
                                    <div class="emp-card-icon-badge">
                                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h7" />
                                        </svg>
                                    </div>
                                    <h2 class="emp-card-title">Role Overview</h2>
                                </div>

                                <!-- About the Internship -->
                                <div class="emp-field-group">
                                    <label class="emp-field-label">About the Internship</label>
                                    <div class="emp-richtext-wrap">
                                        <div class="emp-richtext-toolbar">
                                            <button type="button" class="emp-toolbar-btn" title="Bold">B</button>
                                            <button type="button" class="emp-toolbar-btn" title="Italic"><em>I</em></button>
                                            <button type="button" class="emp-toolbar-btn" title="Bulleted List">
                                                <svg style="width: 14px; height: 14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                                                </svg>
                                            </button>
                                        </div>
                                        <textarea name="about" 
                                                  class="emp-richtext-textarea" 
                                                  placeholder="Describe responsibilities, expectations, and learning outcomes."><?= htmlspecialchars($draftAbout) ?></textarea>
                                    </div>
                                </div>

                                <!-- What You'll Do (Supports Clean Bullets -> `<li>` on review) -->
                                <div class="emp-field-group">
                                    <label class="emp-field-label">What You'll Do (One responsibility per line for bulleted display)</label>
                                    <div class="emp-richtext-wrap">
                                        <div class="emp-richtext-toolbar">
                                            <button type="button" class="emp-toolbar-btn" title="Bold">B</button>
                                            <button type="button" class="emp-toolbar-btn active" title="Bulleted List">
                                                <svg style="width: 14px; height: 14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                                                </svg>
                                            </button>
                                        </div>
                                        <textarea name="what_you_will_do" 
                                                  class="emp-richtext-textarea" 
                                                  placeholder="Describe what the applicant will do (one bullet per line)..."><?= htmlspecialchars($draftWhatYouWillDo) ?></textarea>
                                    </div>
                                </div>

                                <!-- What We're Looking For (Who can apply) -->
                                <div class="emp-field-group">
                                    <label class="emp-field-label">What We're Looking For / Who Can Apply (One item per line)</label>
                                    <div class="emp-richtext-wrap">
                                        <div class="emp-richtext-toolbar">
                                            <button type="button" class="emp-toolbar-btn" title="Bold">B</button>
                                            <button type="button" class="emp-toolbar-btn active" title="Bulleted List">
                                                <svg style="width: 14px; height: 14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                                                </svg>
                                            </button>
                                        </div>
                                        <textarea name="what_we_look_for" 
                                                  class="emp-richtext-textarea" 
                                                  placeholder="Describe the skills and qualifications required (one bullet per line)..."><?= htmlspecialchars($draftWhatWeLookFor) ?></textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- ── Card 3: Requirements ── -->
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
                                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;" class="emp-field-group">
                                    
                                    <!-- Education Level Alpine Dropdown (Image 4 Match) -->
                                    <div>
                                        <label class="emp-field-label">Education Level</label>
                                        <div class="db-dropdown-wrap" x-data="{ 
                                            open: false, 
                                            tempEdu: '<?= htmlspecialchars($draftEducation) ?>',
                                            options: ['Undergraduate', 'Graduate', 'Postgraduate']
                                        }" @click.outside="open = false">
                                            
                                            <button type="button" class="db-dropdown-btn" @click="open = !open">
                                                <span x-text="education">Undergraduate</span>
                                                <svg class="db-dropdown-chevron" :style="open ? 'transform: rotate(180deg)' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                                </svg>
                                            </button>

                                            <div class="db-dropdown-menu" x-show="open" x-transition style="display: none;">
                                                <div class="db-dropdown-list">
                                                    <template x-for="item in options" :key="item">
                                                        <label class="db-dropdown-item">
                                                            <span x-text="item"></span>
                                                            <input type="radio" name="edu_radio" :value="item" :checked="tempEdu === item" @change="tempEdu = item">
                                                        </label>
                                                    </template>
                                                </div>
                                                <button type="button" class="db-dropdown-apply-btn" @click="education = tempEdu; open = false">Apply</button>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Experience Level Dropdown -->
                                    <div>
                                        <label class="emp-field-label">Experience Level (Optional)</label>
                                        <div class="db-dropdown-wrap" x-data="{ 
                                            open: false, 
                                            tempExp: '<?= htmlspecialchars($draftExperience) ?>',
                                            options: ['No Experience', '1-2 Years', '2+ Years']
                                        }" @click.outside="open = false">
                                            
                                            <button type="button" class="db-dropdown-btn" @click="open = !open">
                                                <span x-text="experience">No Experience</span>
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

                            <!-- ── Card 4: Details & Duration ── -->
                            <div class="emp-form-card">
                                <div class="emp-card-header">
                                    <div class="emp-card-icon-badge">
                                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <h2 class="emp-card-title">Details & Duration</h2>
                                </div>

                                <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px;">
                                    
                                    <!-- Start Date -->
                                    <div>
                                        <label class="emp-field-label" for="field_start_date">Start Date</label>
                                        <input type="date" 
                                               id="field_start_date" 
                                               name="start_date" 
                                               class="emp-input-text" 
                                               value="<?= htmlspecialchars($draftStartDate) ?>">
                                    </div>

                                    <!-- Duration (Optional) Alpine Dropdown (Image 4 Match) -->
                                    <div>
                                        <label class="emp-field-label">Duration (Optional)</label>
                                        <div class="db-dropdown-wrap" x-data="{ 
                                            open: false, 
                                            tempDur: '<?= htmlspecialchars($draftDuration) ?>',
                                            options: ['1 week', '2 weeks', '1 month', '3 months', '6 months']
                                        }" @click.outside="open = false">
                                            
                                            <button type="button" class="db-dropdown-btn" @click="open = !open">
                                                <span x-text="duration">3 months</span>
                                                <svg class="db-dropdown-chevron" :style="open ? 'transform: rotate(180deg)' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                                </svg>
                                            </button>

                                            <div class="db-dropdown-menu" x-show="open" x-transition style="display: none;">
                                                <div class="db-dropdown-list">
                                                    <template x-for="item in options" :key="item">
                                                        <label class="db-dropdown-item">
                                                            <span x-text="item"></span>
                                                            <input type="radio" name="dur_radio" :value="item" :checked="tempDur === item" @change="tempDur = item">
                                                        </label>
                                                    </template>
                                                </div>
                                                <button type="button" class="db-dropdown-apply-btn" @click="duration = tempDur; open = false">Apply</button>
                                            </div>
                                        </div>
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

                            <!-- ── Card 5: Perks & Stipend ── -->
                            <div class="emp-form-card">
                                <div class="emp-card-header">
                                    <div class="emp-card-icon-badge">
                                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <h2 class="emp-card-title">Perks & Stipend</h2>
                                </div>

                                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">
                                    
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

                        </div>


                        <!-- ════════════════════════════════════════════════════
                             RIGHT COLUMN (AI Assistant, Settings, Questions)
                        ════════════════════════════════════════════════════ -->
                        <div class="emp-post-col-right">

                            <!-- ── Card 1: AI Internship Assistant ── -->
                            <div class="emp-ai-card">
                                <div class="emp-ai-header">
                                    <div class="emp-ai-badge-icon">
                                        <!-- Sparkles / AI Robot Icon SVG -->
                                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                        </svg>
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
                                        <!-- Video Camera SVG -->
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
                                        options: ['Video Interview (Google Meet)', 'Phone Screening', 'In-person Interview', 'Asynchronous Video']
                                    }" @click.outside="open = false">
                                        
                                        <button type="button" class="db-dropdown-btn" @click="open = !open">
                                            <span x-text="interviewType">Video Interview (Google Meet)</span>
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

                            <!-- ── Card 4: Screening Questions ── -->
                            <div class="emp-form-card">
                                <div class="emp-card-header">
                                    <div class="emp-card-icon-badge">
                                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <h2 class="emp-card-title">Screening Questions</h2>
                                </div>

                                <div class="emp-field-group">
                                    <template x-for="(q, idx) in questions" :key="q.id">
                                        <div class="emp-question-item">
                                            <div>
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

                                <button type="button" class="emp-add-question-btn" @click="addQuestion()">
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

            </div>
        </div>

    </div>

</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('postInternshipForm', (initialData) => ({
        skills: initialData.skills || ['React', 'TypeScript', 'Figma'],
        category: initialData.category || 'Technology',
        type: initialData.type || 'Remote',
        education: initialData.education || 'Undergraduate',
        experience: initialData.experience || 'No Experience',
        duration: initialData.duration || '3 months',
        interviewType: initialData.interviewType || 'Video Interview (Google Meet)',
        isUnpaid: initialData.isUnpaid || false,
        allowRemote: initialData.allowRemote !== undefined ? initialData.allowRemote : true,
        questions: initialData.questions || [],

        addSkill(name) {
            if (name && !this.skills.includes(name)) {
                this.skills.push(name);
            }
        },

        removeSkill(index) {
            this.skills.splice(index, 1);
        },

        addQuestion() {
            const text = prompt('Enter the screening question text:');
            if (text && text.trim()) {
                this.questions.push({
                    id: Date.now(),
                    text: text.trim(),
                    type: 'Short Answer'
                });
            }
        },

        removeQuestion(index) {
            this.questions.splice(index, 1);
        },

        suggestDescription() {
            const aboutEl = document.querySelector('textarea[name="about"]');
            if (aboutEl) {
                aboutEl.value = 'TechFlow System is seeking a passionate intern to join our fast-paced product engineering team. You will work on real-world web applications, collaborate with cross-functional designers and developers, and receive dedicated 1-on-1 mentorship to fast-track your professional growth.';
                alert('AI suggestion applied to "About the Internship"!');
            }
        },

        suggestSkills() {
            const suggestions = ['UI/UX', 'Wireframing', 'User Research', 'Design Systems'];
            suggestions.forEach(s => this.addSkill(s));
            alert('AI added recommended skills: ' + suggestions.join(', '));
        },

        suggestRequirements() {
            const reqEl = document.querySelector('textarea[name="what_we_look_for"]');
            if (reqEl) {
                reqEl.value = "Strong visual design fundamentals and attention to detail.\nProficiency with modern prototyping tools (Figma, FigJam).\nCuriosity to solve user experience challenges and eagerness to learn.";
                alert('AI requirements applied to "What We\'re Looking For"!');
            }
        },

        submitAction(action) {
            const form = document.getElementById('postInternshipForm');
            const actionInput = document.getElementById('formActionInput');
            if (form && actionInput) {
                actionInput.value = action;
                form.submit();
            }
        }
    }));
});
</script>
