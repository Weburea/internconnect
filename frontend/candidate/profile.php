<?php
declare(strict_types=1);
/**
 * View: Candidate Profile Settings
 * Route: /profile
 * Layout: dashboard (custom sidebar layout — no global navbar/footer)
 * @var array  $candidate  Candidate profile data
 * @var string|null $success  Success message if set
 */

$name = $candidate['name'] ?? 'Sarah Peters';
$firstName = explode(' ', trim($name))[0];
$role = $candidate['role'] ?? 'Student Applicant';
$avatar = $candidate['avatar'] ?? null;
?>
<div class="db-shell" x-data="{ 
    deactivateModal: false, 
    confirmNameInput: '',
    candidateFullName: '<?= htmlspecialchars($candidate['name'] ?? 'Sarah Peters') ?>',
    portfolio: '<?= htmlspecialchars($candidate['portfolio'] ?? '') ?>',
    linkedin: '<?= htmlspecialchars($candidate['linkedin'] ?? '') ?>',
    github: '<?= htmlspecialchars($candidate['github'] ?? '') ?>',
    portfolioEdit: '<?= htmlspecialchars($candidate['portfolio'] ?? '') ?>',
    linkedinEdit: '<?= htmlspecialchars($candidate['linkedin'] ?? '') ?>',
    githubEdit: '<?= htmlspecialchars($candidate['github'] ?? '') ?>',
    editingLinks: false,
    successAlert: '<?= $success ? htmlspecialchars($success) : '' ?>',
    resumeFile: 'Sarah_Peters_CV_2024.pdf',
    resumeSize: '1.2 MB',
    resumeUploaded: 'Oct 12, 2023',
    dragging: false,
    showPreviewModal: false,
    uploadedFileName: 'Sarah_Peters_CV_2024.pdf',
    uploadedFileSize: '1.2 MB',
    uploadedFileUrl: '<?= asset('dummy.pdf') ?>',
    uploadedFileExt: 'pdf',
    init() {
        if (this.successAlert) {
            this.$nextTick(() => {
                this.$dispatch('show-toast', { text: this.successAlert, type: 'success' });
                this.successAlert = '';
            });
        }
    },
    saveQuickLinks() {
        this.portfolio = this.portfolioEdit;
        this.linkedin = this.linkedinEdit;
        this.github = this.githubEdit;
        this.editingLinks = false;
        this.$dispatch('show-toast', { text: 'Quick links updated successfully!', type: 'success' });
    },
    triggerFileInput() {
        this.$refs.fileInput.click();
    },
    handleFileDrop(e) {
        this.dragging = false;
        if (e.dataTransfer.files.length > 0) {
            const file = e.dataTransfer.files[0];
            this.uploadMockFile(file);
        }
    },
    handleFileSelect(e) {
        if (e.target.files.length > 0) {
            const file = e.target.files[0];
            this.uploadMockFile(file);
        }
    },
    uploadMockFile(file) {
        if (!file) return;
        if (file.size > 5 * 1024 * 1024) {
            this.$dispatch('show-toast', { text: 'The selected file exceeds the 5MB limit. Please upload a smaller file.', type: 'warning' });
            return;
        }
        const ext = file.name.split('.').pop().toLowerCase();
        if (!['pdf', 'docx', 'rtf'].includes(ext)) {
            this.$dispatch('show-toast', { text: 'Unsupported file type. Please upload a PDF, DOCX, or RTF document.', type: 'warning' });
            return;
        }
        if (this.uploadedFileUrl && this.uploadedFileUrl !== '<?= asset('dummy.pdf') ?>' && this.uploadedFileUrl.startsWith('blob:')) {
            URL.revokeObjectURL(this.uploadedFileUrl);
        }
        this.resumeFile = file.name;
        this.resumeSize = (file.size / (1024 * 1024)).toFixed(1) + ' MB';
        const options = { month: 'short', day: 'numeric', year: 'numeric' };
        this.resumeUploaded = new Date().toLocaleDateString('en-US', options);
        
        this.uploadedFileName = file.name;
        this.uploadedFileSize = this.resumeSize;
        this.uploadedFileExt = ext;
        this.uploadedFileUrl = URL.createObjectURL(file);
        
        this.$dispatch('show-toast', { text: 'Resume uploaded successfully!', type: 'success' });
    },
    deleteResume() {
        if (this.uploadedFileUrl && this.uploadedFileUrl !== '<?= asset('dummy.pdf') ?>' && this.uploadedFileUrl.startsWith('blob:')) {
            URL.revokeObjectURL(this.uploadedFileUrl);
        }
        this.resumeFile = '';
        this.resumeSize = '';
        this.resumeUploaded = '';
        this.uploadedFileName = '';
        this.uploadedFileSize = '';
        this.uploadedFileExt = '';
        this.uploadedFileUrl = '';
        this.$dispatch('show-toast', { text: 'Resume removed.', type: 'info' });
    }
}">

    <!-- ══ SIDEBAR ══ -->
    <?php 
    $activeNav = 'profile';
    include dirname(__DIR__) . '/shared/components/candidate-sidebar.php'; 
    ?>

    <!-- ══ MAIN ══ -->
    <div class="db-main">

        <!-- ══ TOP NAVBAR ══ -->
        <?php include dirname(__DIR__) . '/shared/components/candidate-topnav.php'; ?>

        <!-- Scrollable Content -->
        <div class="db-content" id="db-content">

            <!-- Header Title -->
            <div class="db-profile-settings-header">
                <span>Profile Settings</span>
                <h1>Create Your <span>Identity.</span></h1>
                <p>Refine your professional narrative. Your profile is the foundation of your career trajectory at
                    InternConnect.</p>
            </div>

            <!-- Settings Grid -->
            <div class="db-settings-grid">

                <!-- Left Column -->
                <div class="db-settings-left">
                    <form action="<?= url('/candidate/profile') ?>" method="POST" id="profile-form">

                        <!-- 1. Personal Info Card -->
                        <div class="db-settings-card">
                            <div class="db-settings-card-header">
                                <h3 class="db-settings-card-title">Personal Information</h3>
                                <button type="submit" class="db-btn-save" id="btn-save-personal">
                                    <svg style="width: 14px; height: 14px;" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                    </svg>
                                    <span>Save Changes</span>
                                </button>
                            </div>

                            <div class="db-form-row">
                                <div class="db-form-group">
                                    <label for="input-name" class="db-label">Full Name</label>
                                    <input type="text" name="name" id="input-name" class="db-input"
                                        value="<?= htmlspecialchars($candidate['name'] ?? 'Sarah Peters') ?>" required>
                                </div>
                                <div class="db-form-group">
                                    <label for="input-email" class="db-label">Email Address</label>
                                    <input type="email" name="email" id="input-email" class="db-input"
                                        value="<?= htmlspecialchars($candidate['email'] ?? 'sarah.peters@design-intern.com') ?>" required>
                                </div>
                            </div>

                            <div class="db-form-row">
                                <div class="db-form-group">
                                    <label for="input-role" class="db-label">Current Role</label>
                                    <input type="text" name="role" id="input-role" class="db-input"
                                        value="<?= htmlspecialchars($candidate['role'] ?? 'Student Applicant') ?>" required>
                                </div>
                                <div class="db-form-group">
                                    <label for="input-phone" class="db-label">Phone Number</label>
                                    <input type="text" name="phone" id="input-phone" class="db-input"
                                        value="<?= htmlspecialchars($candidate['phone'] ?? '+1 (555) 012-3456') ?>" required>
                                </div>
                            </div>

                            <div class="db-form-group db-form-group-full">
                                <label for="textarea-bio" class="db-label">Professional Bio</label>
                                <textarea name="bio" id="textarea-bio" class="db-input db-textarea"
                                    placeholder="Tell us about yourself..."
                                    required><?= htmlspecialchars($candidate['bio'] ?? 'Passionate designer focused on creating intuitive digital experiences. Currently exploring the intersection of accessibility and high-end visual design.') ?></textarea>
                            </div>
                        </div>

                        <!-- 2. Academic Information Card -->
                        <div class="db-settings-card">
                            <div class="db-settings-card-header">
                                <h3 class="db-settings-card-title">Academic Information</h3>
                                <button type="submit" class="db-btn-save" id="btn-save-academic">
                                    <svg style="width: 14px; height: 14px;" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                    </svg>
                                    <span>Save Changes</span>
                                </button>
                            </div>

                            <div class="db-form-group db-form-group-full" style="margin-bottom: 20px;">
                                <label for="input-university" class="db-label">University Name</label>
                                <input type="text" name="university" id="input-university" class="db-input"
                                    value="<?= htmlspecialchars($candidate['university'] ?? '') ?>"
                                    placeholder="e.g. Stanford University">
                            </div>

                            <div class="db-form-row">
                                <div class="db-form-group">
                                    <label for="select-grad-year" class="db-label">Expected Graduation Year/ Graduation Year</label>
                                    <select name="graduation_year" id="select-grad-year" class="db-input db-select">
                                        <option value="" <?= empty($candidate['graduation_year']) ? 'selected' : '' ?>>Select Year</option>
                                        <?php 
                                        $selectedYear = (string)($candidate['graduation_year'] ?? '');
                                        for ($yr = 2030; $yr >= 2018; $yr--): ?>
                                            <option value="<?= $yr ?>" <?= ($selectedYear === (string)$yr) ? 'selected' : '' ?>><?= $yr ?></option>
                                        <?php endfor; ?>
                                    </select>
                                </div>
                                <div class="db-form-group">
                                    <label for="select-degree-type" class="db-label">Degree Type</label>
                                    <select name="degree_type" id="select-degree-type" class="db-input db-select">
                                        <option value="" <?= empty($candidate['degree_type']) ? 'selected' : '' ?>>Select Degree</option>
                                        <?php 
                                        $degreeOptions = [
                                            "Bachelor's",
                                            "Master's",
                                            "Doctorate / Ph.D",
                                            "Associate Degree",
                                            "Diploma / Certificate",
                                            "High School / Secondary",
                                            "Bootcamp / Certificate",
                                        ];
                                        $selectedDegree = $candidate['degree_type'] ?? "";
                                        foreach ($degreeOptions as $dOption): ?>
                                            <option value="<?= htmlspecialchars($dOption) ?>" <?= ($selectedDegree === $dOption) ? 'selected' : '' ?>><?= htmlspecialchars($dOption) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="db-form-row">
                                <div class="db-form-group">
                                    <label for="input-field-of-study" class="db-label">Field of Study</label>
                                    <input type="text" name="field_of_study" id="input-field-of-study" class="db-input"
                                        value="<?= htmlspecialchars($candidate['field_of_study'] ?? '') ?>"
                                        placeholder="e.g. Cognitive Science">
                                </div>
                                <div class="db-form-group">
                                    <label for="input-gpa" class="db-label">GPA/ Grade (Optional)</label>
                                    <input type="text" name="gpa" id="input-gpa" class="db-input"
                                        value="<?= htmlspecialchars($candidate['gpa'] ?? '') ?>"
                                        placeholder="e.g. 3.8/4.0">
                                </div>
                            </div>
                        </div>

                        <!-- 3. Experience Assets Card (Renamed from Resume & Portfolio) -->
                        <div class="db-settings-card">
                            <div class="db-settings-card-header">
                                <h3 class="db-settings-card-title">Experience Assets</h3>
                                <button type="submit" class="db-btn-save" id="btn-save-experience">
                                    <svg style="width: 14px; height: 14px;" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                    </svg>
                                    <span>Save Changes</span>
                                </button>
                            </div>

                            <!-- Resume Upload Sub-block -->
                            <div style="margin-bottom: 24px;">
                                <label class="db-label" style="display: block; margin-bottom: 8px;">Resume / CV Document</label>
                                <!-- File input (hidden) -->
                                <input type="file" x-ref="fileInput" @change="handleFileSelect" style="display: none;"
                                    accept=".pdf,.docx,.rtf">

                                <!-- Drag-and-drop Zone -->
                                <div class="db-upload-zone" :class="{ 'dragging': dragging }"
                                    @dragover.prevent="dragging = true" @dragleave.prevent="dragging = false"
                                    @drop.prevent="handleFileDrop" @click="triggerFileInput">
                                    <div class="db-upload-icon-wrap">
                                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                        </svg>
                                    </div>
                                    <h5>Upload your latest resume</h5>
                                    <p>Drag and drop or <span
                                            style="color: #4F47E6; font-weight: 700; text-decoration: underline;">browse</span>
                                        your files</p>
                                    <span>PDF, DOCX, or RTF up to 5MB</span>
                                </div>

                                <!-- Uploaded Resume Pill -->
                                <template x-if="resumeFile">
                                    <div class="db-file-card">
                                        <div class="db-file-card-inner">
                                            <!-- Visual file icon -->
                                            <div class="db-file-visual-icon">
                                                <div class="db-file-visual-badge" x-text="resumeFile.split('.').pop()">
                                                </div>
                                                <div class="db-file-visual-lines">
                                                    <div class="db-file-visual-line"></div>
                                                    <div class="db-file-visual-line accent"></div>
                                                    <div class="db-file-visual-line"></div>
                                                </div>
                                            </div>
                                            <div class="db-file-card-details">
                                                <div class="db-file-card-name" x-text="resumeFile"></div>
                                                <div class="db-file-card-meta">
                                                    Uploaded <span x-text="resumeUploaded"></span>
                                                </div>
                                                <div class="db-file-card-status-badges">
                                                    <span class="db-file-card-badge success">Ready for review</span>
                                                    <span class="db-file-card-badge info">✓ Parsed by InternConnect AI</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="db-file-card-actions">
                                            <button type="button" @click="showPreviewModal = true"
                                                class="db-file-action-btn">
                                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0zm6 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                <span>View File</span>
                                            </button>
                                            <button type="button" class="db-file-action-btn danger" @click="deleteResume"
                                                aria-label="Delete resume">
                                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                                <span>Delete</span>
                                            </button>
                                        </div>
                                    </div>
                                </template>
                            </div>

                            <!-- Professional Links Sub-block (6 by 6 Two-Side Layout) -->
                            <div style="padding-top: 18px; border-top: 1px solid #F1F5F9;">
                                <div class="db-form-row">
                                    <!-- Portfolio Website -->
                                    <div class="db-form-group">
                                        <label for="input-portfolio" class="db-label">Portfolio URL</label>
                                        <div class="db-input-group-icon" style="position: relative; display: flex; align-items: center; width: 100%;">
                                            <span class="db-input-icon-prefix" style="position: absolute; left: 14px; display: inline-flex; align-items: center; justify-content: center; width: 16px; height: 16px; pointer-events: none; color: #64748B; z-index: 2;">
                                                <svg style="width: 16px; height: 16px; max-width: 16px; max-height: 16px; display: block;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                                                </svg>
                                            </span>
                                            <input type="text" name="portfolio" id="input-portfolio" class="db-input"
                                                style="padding-left: 40px;"
                                                x-model="portfolio" placeholder="e.g. sarahpeters.design">
                                        </div>
                                    </div>

                                    <!-- LinkedIn Profile -->
                                    <div class="db-form-group">
                                        <label for="input-linkedin" class="db-label">LinkedIn Username / URL</label>
                                        <div class="db-input-group-icon" style="position: relative; display: flex; align-items: center; width: 100%;">
                                            <span class="db-input-icon-prefix" style="position: absolute; left: 14px; display: inline-flex; align-items: center; justify-content: center; width: 16px; height: 16px; pointer-events: none; color: #64748B; z-index: 2;">
                                                <svg style="width: 16px; height: 16px; max-width: 16px; max-height: 16px; display: block;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                                                </svg>
                                            </span>
                                            <input type="text" name="linkedin" id="input-linkedin" class="db-input"
                                                style="padding-left: 40px;"
                                                x-model="linkedin" placeholder="e.g. in/sarah-peters">
                                        </div>
                                    </div>
                                </div>

                                <!-- GitHub Profile -->
                                <div class="db-form-group db-form-group-full">
                                    <label for="input-github" class="db-label">GitHub Username / URL</label>
                                    <div class="db-input-group-icon" style="position: relative; display: flex; align-items: center; width: 100%;">
                                        <span class="db-input-icon-prefix" style="position: absolute; left: 14px; display: inline-flex; align-items: center; justify-content: center; width: 16px; height: 16px; pointer-events: none; color: #64748B; z-index: 2;">
                                            <svg style="width: 16px; height: 16px; max-width: 16px; max-height: 16px; display: block;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                                            </svg>
                                        </span>
                                        <input type="text" name="github" id="input-github" class="db-input"
                                            style="padding-left: 40px;"
                                            x-model="github" placeholder="e.g. github.com/sarah-peters">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 4. Notification Preferences Card -->
                        <div class="db-settings-card">
                            <div class="db-settings-card-header">
                                <h3 class="db-settings-card-title">Notification Preferences</h3>
                                <button type="submit" class="db-btn-save" id="btn-save-notif">
                                    <svg style="width: 14px; height: 14px;" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                    </svg>
                                    <span>Save Changes</span>
                                </button>
                            </div>

                            <div class="db-notif-list">
                                <!-- Job Recs Toggle -->
                                <div class="db-notif-item">
                                    <div class="db-notif-info">
                                        <h5>Job Recommendations</h5>
                                        <p>Get notified when new jobs match your design profile.</p>
                                    </div>
                                    <label class="db-switch">
                                        <input type="checkbox" name="notif_recommendations" value="1"
                                            <?= $candidate['notif_recommendations'] ? 'checked' : '' ?>>
                                        <span class="db-switch-slider"></span>
                                    </label>
                                </div>

                                <!-- Application Status Toggle -->
                                <div class="db-notif-item">
                                    <div class="db-notif-info">
                                        <h5>Application Status</h5>
                                        <p>Real-time updates on your active job applications.</p>
                                    </div>
                                    <label class="db-switch">
                                        <input type="checkbox" name="notif_status" value="1"
                                            <?= $candidate['notif_status'] ? 'checked' : '' ?>>
                                        <span class="db-switch-slider"></span>
                                    </label>
                                </div>

                                <!-- Announcements Toggle -->
                                <div class="db-notif-item">
                                    <div class="db-notif-info">
                                        <h5>Platform Announcements</h5>
                                        <p>Occasional news about InternConnect features and events.</p>
                                    </div>
                                    <label class="db-switch">
                                        <input type="checkbox" name="notif_announcements" value="1"
                                            <?= $candidate['notif_announcements'] ? 'checked' : '' ?>>
                                        <span class="db-switch-slider"></span>
                                    </label>
                                </div>
                            </div>
                        </div>

                    </form>

                    <!-- 5. Account Management / Deactivate Card -->
                    <div class="db-danger-card">
                        <div class="db-danger-card-body">
                            <div class="db-danger-card-info">
                                <h4>Deactivate Account</h4>
                                <p>Permanently remove your candidate profile, resume data, and active internship applications.</p>
                            </div>
                            <button type="button" class="db-btn-danger-card" @click="confirmNameInput = ''; deactivateModal = true;">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                <span>Deactivate Account</span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Security & Authentication -->
                <div class="db-settings-right">

                    <!-- 1. Authentication & Connected Accounts Card -->
                    <div class="db-settings-card">
                        <div class="db-settings-card-header">
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <svg style="width: 18px; height: 18px; color: #4F47E6;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                                <h3 class="db-settings-card-title" style="margin: 0;">Authentication</h3>
                            </div>
                        </div>

                        <!-- Primary Login Email -->
                        <div class="db-auth-item">
                            <div class="db-auth-item-left">
                                <div class="db-auth-icon" style="color: #4F47E6;">
                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div class="db-auth-details">
                                    <span class="db-auth-label">Primary Login Email</span>
                                    <span class="db-auth-val" title="<?= htmlspecialchars($candidate['email'] ?? 'sarah.peters@design-intern.com') ?>">
                                        <?= htmlspecialchars($candidate['email'] ?? 'sarah.peters@design-intern.com') ?>
                                    </span>
                                </div>
                            </div>
                            <span class="db-badge-verified">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                </svg>
                                Verified
                            </span>
                        </div>

                        <!-- Connected Google Account -->
                        <div class="db-auth-item">
                            <div class="db-auth-item-left">
                                <div class="db-auth-icon">
                                    <svg viewBox="0 0 24 24" style="width: 18px; height: 18px;">
                                        <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                        <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                        <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
                                        <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
                                    </svg>
                                </div>
                                <div class="db-auth-details">
                                    <span class="db-auth-label">Google Authentication</span>
                                    <span class="db-auth-val" title="<?= htmlspecialchars($candidate['google_email'] ?? 'sarah.peters@gmail.com') ?>">
                                        <?= htmlspecialchars($candidate['google_email'] ?? 'sarah.peters@gmail.com') ?>
                                    </span>
                                </div>
                            </div>
                            <span class="db-badge-verified" style="background: #EEF2FF; color: #4F47E6;">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                </svg>
                                Connected
                            </span>
                        </div>

                        <p style="font-size: 11.5px; color: #64748B; margin: 12px 0 0; line-height: 1.45;">
                            Your Google account is linked to your primary profile. You can log in using either method without spawning duplicate accounts.
                        </p>
                    </div>

                    <!-- 2. Password & Security Card -->
                    <div class="db-settings-card">
                        <div class="db-settings-card-header">
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <svg style="width: 18px; height: 18px; color: #4F47E6;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                                </svg>
                                <h3 class="db-settings-card-title" style="margin: 0;">Password & Security</h3>
                            </div>
                        </div>

                        <form action="<?= url('/candidate/profile') ?>" method="POST" id="password-form">
                            <input type="hidden" name="action" value="update_password">

                            <!-- Current Password -->
                            <div class="db-form-group" style="margin-bottom: 14px;">
                                <label for="input-current-pass" class="db-label">Current Password</label>
                                <div class="db-input-password-wrap">
                                    <input :type="showCurrentPass ? 'text' : 'password'" name="current_password" id="input-current-pass" class="db-input" placeholder="Enter current password" required>
                                    <button type="button" class="db-password-toggle-btn" @click="showCurrentPass = !showCurrentPass" :aria-label="showCurrentPass ? 'Hide password' : 'Show password'">
                                        <svg x-show="!showCurrentPass" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zm6 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <svg x-show="showCurrentPass" style="display: none;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <!-- New Password -->
                            <div class="db-form-group" style="margin-bottom: 14px;">
                                <label for="input-new-pass" class="db-label">New Password</label>
                                <div class="db-input-password-wrap">
                                    <input :type="showNewPass ? 'text' : 'password'" name="new_password" id="input-new-pass" class="db-input" placeholder="Min. 8 characters" minlength="8" required>
                                    <button type="button" class="db-password-toggle-btn" @click="showNewPass = !showNewPass" :aria-label="showNewPass ? 'Hide password' : 'Show password'">
                                        <svg x-show="!showNewPass" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zm6 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <svg x-show="showNewPass" style="display: none;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <!-- Confirm New Password -->
                            <div class="db-form-group" style="margin-bottom: 18px;">
                                <label for="input-confirm-pass" class="db-label">Confirm New Password</label>
                                <div class="db-input-password-wrap">
                                    <input :type="showConfirmPass ? 'text' : 'password'" name="confirm_password" id="input-confirm-pass" class="db-input" placeholder="Repeat new password" minlength="8" required>
                                    <button type="button" class="db-password-toggle-btn" @click="showConfirmPass = !showConfirmPass" :aria-label="showConfirmPass ? 'Hide password' : 'Show password'">
                                        <svg x-show="!showConfirmPass" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zm6 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <svg x-show="showConfirmPass" style="display: none;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <button type="submit" class="db-btn-primary-form" id="btn-update-pass">
                                Update Password
                            </button>

                            <a href="<?= url('/forgot-password') ?>" style="display: block; text-align: center; font-size: 12.5px; font-weight: 700; color: #4F47E6; text-decoration: none; margin-top: 14px;" onmouseover="this.style.textDecoration='underline'" onmouseout="this.style.textDecoration='none'">
                                Forgot your password?
                            </a>
                        </form>
                    </div>

                    <!-- 3. Active Devices & Sessions Card -->
                    <div class="db-settings-card">
                        <div class="db-settings-card-header">
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <svg style="width: 18px; height: 18px; color: #4F47E6;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                <h3 class="db-settings-card-title" style="margin: 0;">Active Devices & Sessions</h3>
                            </div>
                        </div>

                        <div class="db-sessions-list">
                            <?php 
                            $sessions = $candidate['active_sessions'] ?? [];
                            foreach ($sessions as $session): ?>
                                <div class="db-session-item">
                                    <div class="db-session-icon">
                                        <?php if (($session['icon'] ?? '') === 'mobile'): ?>
                                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                            </svg>
                                        <?php elseif (($session['icon'] ?? '') === 'laptop'): ?>
                                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                            </svg>
                                        <?php elseif (($session['icon'] ?? '') === 'tablet'): ?>
                                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 18h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                            </svg>
                                        <?php else: ?>
                                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                            </svg>
                                        <?php endif; ?>
                                    </div>
                                    <div class="db-session-info">
                                        <div class="db-session-title">
                                            <span class="db-session-device-name"><?= htmlspecialchars($session['device']) ?></span>
                                            <?php if (!empty($session['current'])): ?>
                                                <span class="db-session-badge-current">This Device</span>
                                            <?php endif; ?>
                                        </div>
                                        <div class="db-session-meta">
                                            <?= htmlspecialchars($session['location'] . ' • IP: ' . $session['ip']) ?><br>
                                            <span style="color: <?= !empty($session['current']) ? '#16A34A' : '#94A3B8' ?>; font-weight: 600;">
                                                <?= htmlspecialchars($session['time']) ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <div style="margin-top: 18px; padding-top: 14px; border-top: 1px solid #F1F5F9;">
                            <button type="button" class="db-btn-primary-form" style="background: #F1F5F9; color: #475569; border: 1px solid #E2E8F0;"
                                @click="$dispatch('show-toast', { text: 'Successfully signed out of all other active sessions.', type: 'info' })"
                                onmouseover="this.style.background='#E2E8F0'" onmouseout="this.style.background='#F1F5F9'">
                                Sign Out All Other Devices
                            </button>
                        </div>
                    </div>

                </div>

            </div>

        </div><!-- /.db-content -->

        <!-- Dashboard Footer -->
        <?php include __DIR__ . '/../shared/components/db-footer.php'; ?>

    </div><!-- /.db-main -->

    <!-- Deactivate Account Confirmation Modal (Alpine) -->
    <div x-show="deactivateModal"
        style="display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.65); backdrop-filter: blur(8px); z-index: 2000; align-items: center; justify-content: center; padding: 16px;"
        class="flex" 
        @click.self="deactivateModal = false; confirmNameInput = '';"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0">
        
        <div style="background: #FFFFFF; max-width: 480px; width: 100%; border-radius: 20px; padding: 32px; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25); border: 1px solid #F1F5F9;"
            @click.stop
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95">
            
            <div style="width: 48px; height: 48px; border-radius: 12px; background: #FEE2E2; color: #DC2626; display: flex; align-items: center; justify-content: center; margin-bottom: 20px;">
                <svg style="width: 24px; height: 24px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            
            <h4 style="font-size: 19px; font-weight: 800; color: #0F172A; margin: 0 0 8px;">Deactivate Account</h4>
            <p style="font-size: 13.5px; color: #64748B; line-height: 1.5; margin: 0 0 20px;">
                Are you sure you want to deactivate your InternConnect account? This action is permanent and will withdraw all of your active applications.
            </p>
            
            <!-- Name Verification Input -->
            <div style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 12px; padding: 14px 16px; margin-bottom: 24px;">
                <label for="confirm-name-input" style="display: block; font-size: 12px; font-weight: 600; color: #475569; margin-bottom: 8px;">
                    To confirm, type your full name <strong style="color: #0F172A; font-weight: 800;" x-text="candidateFullName"></strong> below:
                </label>
                <input type="text" 
                    id="confirm-name-input"
                    x-model="confirmNameInput" 
                    class="db-input" 
                    placeholder="Type your full name to confirm"
                    style="background: #FFFFFF; font-size: 13.5px;"
                    autocomplete="off">
            </div>
            
            <div style="display: flex; gap: 12px; justify-content: flex-end; align-items: center;">
                <button type="button" class="db-btn-modal-cancel" @click="deactivateModal = false; confirmNameInput = '';">
                    Cancel
                </button>
                <button type="button" 
                    class="db-btn-danger-confirm"
                    :disabled="confirmNameInput.trim().toLowerCase() !== candidateFullName.trim().toLowerCase()"
                    @click="deactivateModal = false; confirmNameInput = ''; $dispatch('show-toast', { text: 'Account deactivated successfully.', type: 'danger' });">
                    Deactivate Account
                </button>
            </div>
        </div>
    </div>

    <!-- ════════════ Full Screen Preview Modal ════════════ -->
    <div x-show="showPreviewModal" class="fixed inset-0 z-[2005] flex items-center justify-center p-3 sm:p-6"
        style="display: none;" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95">

        <!-- Overlay -->
        <div class="fixed inset-0 bg-black/65 backdrop-blur-sm" @click="showPreviewModal = false"></div>

        <!-- Modal Card -->
        <div class="relative z-10 w-full max-w-3xl flex flex-col rounded-2xl bg-white shadow-2xl border border-gray-100 overflow-hidden"
            style="height: min(90vh, 860px);">

            <!-- Modal Header -->
            <div
                class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gray-50/50 flex-shrink-0">
                <div class="flex items-center gap-3">
                    <div class="h-10 w-10 bg-red-50 text-red-500 rounded-xl flex items-center justify-center font-bold text-sm border border-red-100"
                        x-text="uploadedFileExt.toUpperCase()">PDF</div>
                    <div>
                        <h3 class="text-base font-bold text-slate-800 leading-tight" x-text="uploadedFileName"
                            style="margin: 0;"></h3>
                        <p class="text-xs text-slate-500" x-text="uploadedFileSize + ' • View only'" style="margin: 0;">
                        </p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <!-- File type label -->
                    <span class="text-xs text-slate-400 font-medium hidden sm:inline"
                        x-text="uploadedFileExt === 'pdf' ? 'PDF • Live Preview' : (uploadedFileExt.toUpperCase() + ' • Document')"></span>
                    <button type="button" @click="showPreviewModal = false"
                        class="p-2 text-gray-400 hover:text-gray-700 hover:bg-gray-100 rounded-lg transition-colors"
                        style="background: none; border: none; cursor: pointer;">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"
                            style="width: 20px; height: 20px;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Modal Content -->
            <div class="flex-1 overflow-hidden bg-gray-50 relative" style="min-height: 0;">
                <!-- PDF: full iframe -->
                <div x-show="uploadedFileExt === 'pdf'" class="absolute inset-0">
                    <iframe
                        :src="uploadedFileExt === 'pdf' ? (uploadedFileUrl + '#toolbar=0&navpanes=0&scrollbar=0&view=Fit') : ''"
                        class="w-full h-full" style="border: none;" title="Full Resume Preview"></iframe>
                </div>

                <!-- DOCX / RTF: centered placeholder -->
                <div x-show="uploadedFileExt !== 'pdf'"
                    class="absolute inset-0 flex flex-col items-center justify-center gap-6 p-8">
                    <div class="flex items-center justify-center h-20 w-20 rounded-3xl bg-indigo-50 border border-indigo-100 shadow-sm"
                        style="width: 80px; height: 80px; border-radius: 20px;">
                        <svg class="h-10 w-10 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="1.5" style="width: 40px; height: 40px;">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                        </svg>
                    </div>
                    <div class="text-center max-w-sm" style="text-align: center;">
                        <p class="font-bold text-slate-800 text-lg mb-2" x-text="uploadedFileName"
                            style="font-size: 18px; margin-bottom: 8px;"></p>
                        <p class="text-sm text-slate-500 mb-1" x-text="uploadedFileSize + ' • Ready for review'"
                            style="font-size: 14px; margin-bottom: 4px;"></p>
                        <p class="text-sm text-slate-400 leading-relaxed"
                            style="font-size: 13px; color: #94A3B8; line-height: 1.5;">Browser-based preview is
                            available for PDF files only. Your Word / RTF document has been successfully loaded and will
                            be submitted with your application.</p>
                    </div>
                    <a :href="uploadedFileUrl" :download="uploadedFileName"
                        class="inline-flex items-center gap-2 text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 px-5 py-2.5 rounded-xl transition active:scale-95 shadow"
                        style="text-decoration: none; padding: 10px 20px; border-radius: 12px; font-size: 14px; color: #fff; background: #4F47E6;">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"
                            style="width: 16px; height: 16px;">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        Download & Open Locally
                    </a>
                </div>
            </div>

            <!-- Modal Footer -->
            <div
                class="px-5 sm:px-6 py-4 border-t border-gray-100 flex items-center justify-between bg-white flex-shrink-0">
                <span class="text-xs text-slate-400"
                    x-text="uploadedFileExt === 'pdf' ? 'Scroll to navigate pages' : 'Download the file to view it in full'"></span>
                <button type="button" @click="showPreviewModal = false"
                    class="text-white text-sm font-semibold py-2.5 px-5 rounded-xl transition active:scale-95 shadow-sm"
                    style="background: #4F47E6; border: none; color: #fff; padding: 10px 20px; border-radius: 12px; cursor: pointer;">
                    Close Preview
                </button>
            </div>
        </div>
    </div>

</div><!-- /.db-shell -->