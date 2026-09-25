<?php
declare(strict_types=1);
/**
 * View: Employer Profile & Organization Settings
 * Route: /employer/profile, /employer/settings
 * Layout: dashboard (custom employer sidebar layout)
 * 
 * @var array       $employer Employer and company profile data
 * @var string|null $success  Flash message if set
 */

$companyName = $employer['company_name'] ?? 'TechFlow Systems';
$compRole    = $employer['role'] ?? 'Employer Portal';
$logoUrl     = $employer['logo'] ?? 'https://res.cloudinary.com/weburea/image/upload/v1789424092/internConnect/images/hk_logo.jpg';
$bannerUrl   = $employer['banner'] ?? 'https://res.cloudinary.com/weburea/image/upload/v1789424121/internConnect/images/hk_banner.png';
?>
<style>
/* ── Guaranteed Single Arrow for All Custom Selects (Eliminates repeated background arrows) ── */
select.db-select, 
select.db-select:focus, 
select.db-select:active {
    appearance: none !important;
    -webkit-appearance: none !important;
    -moz-appearance: none !important;
    background-color: #F8FAFC !important;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%2364748B' stroke-width='2.2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='m6 9 6 6 6-6'/%3E%3C/svg%3E") !important;
    background-repeat: no-repeat !important;
    background-position: right 14px center !important;
    background-size: 16px 16px !important;
    padding-right: 42px !important;
}
select.db-select:focus {
    background-color: #FFFFFF !important;
    border-color: #4F47E6 !important;
    box-shadow: 0 0 0 3px rgba(79, 70, 230, 0.12) !important;
}

/* ── Interactive Department Tags (Multi-Select Pills) ── */
.emp-tags-wrapper {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}
.emp-tag-pill {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 7px 14px;
    border-radius: 99px;
    font-size: 12.5px;
    font-weight: 600;
    cursor: pointer;
    border: 1.5px solid #E2E8F0;
    background: #F8FAFC;
    color: #475569;
    transition: all 0.15s ease;
    white-space: nowrap;
    user-select: none;
}
.emp-tag-pill:hover {
    border-color: #CBD5E1;
    background: #F1F5F9;
    color: #0F172A;
}
.emp-tag-pill.is-active {
    background: #4F47E6 !important;
    color: #FFFFFF !important;
    border-color: #4F47E6 !important;
    box-shadow: 0 2px 6px rgba(79, 70, 230, 0.25);
}
.emp-tag-pill svg {
    width: 12px;
    height: 12px;
    flex-shrink: 0;
}

/* ── Custom Form Dropdown Component (Modern rounded replacement for native selects) ── */
.db-dropdown-wrap {
    position: relative;
    width: 100%;
}
.db-dropdown-btn {
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: space-between;
    background-color: #F8FAFC;
    border: 1.5px solid #E2E8F0;
    border-radius: 8px;
    padding: 12px 16px;
    font-size: 13.5px;
    color: #1E293B;
    cursor: pointer;
    text-align: left;
    transition: all 0.15s ease;
    outline: none;
    font-family: inherit;
    user-select: none;
}
.db-dropdown-btn:hover {
    border-color: #CBD5E1;
    background-color: #F1F5F9;
}
.db-dropdown-btn.is-open,
.db-dropdown-btn:focus {
    border-color: #4F47E6;
    background-color: #FFFFFF;
    box-shadow: 0 0 0 3px rgba(79, 70, 230, 0.12);
}
.db-dropdown-chevron {
    width: 15px;
    height: 15px;
    color: #64748B;
    flex-shrink: 0;
    transition: transform 0.2s ease, color 0.15s ease;
}
.db-dropdown-btn.is-open .db-dropdown-chevron {
    color: #4F47E6;
    transform: rotate(180deg);
}
.db-dropdown-menu {
    position: absolute;
    top: calc(100% + 6px);
    left: 0;
    right: 0;
    background: #FFFFFF;
    border: 1px solid #E2E8F0;
    border-radius: 12px;
    box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.08);
    z-index: 60;
    padding: 6px;
    max-height: 250px;
    overflow-y: auto;
}
.db-dropdown-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 9px 12px;
    font-size: 13px;
    font-weight: 500;
    color: #334155;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.12s ease;
    user-select: none;
}
.db-dropdown-item:hover {
    background-color: #F1F5F9;
    color: #0F172A;
}
.db-dropdown-item.is-selected {
    background-color: #EEF2FF;
    color: #4F47E6;
    font-weight: 600;
}
.db-dropdown-check {
    width: 14px;
    height: 14px;
    color: #4F47E6;
    flex-shrink: 0;
}
</style>

<script>
function employerProfileSettings() {
    return {
        deactivateModal: false,
        confirmNameInput: '',
        companyFullName: <?= json_encode($companyName) ?>,
        successAlert: <?= json_encode($success ?? '') ?>,
        
        // Banner & Logo Upload States
        bannerUrl: <?= json_encode($bannerUrl) ?>,
        logoUrl: <?= json_encode($logoUrl) ?>,
        draggingBanner: false,
        draggingLogo: false,

        // Business CAC Document (empty by default on clean onboarding)
        cacFile: <?= json_encode($employer['business_doc_name'] ?? '') ?>,
        cacSize: <?= json_encode($employer['business_doc_size'] ?? '') ?>,
        cacUploaded: <?= json_encode(!empty($employer['business_doc_name']) ? 'Aug 14, 2026' : '') ?>,
        cacVerified: <?= json_encode(!empty($employer['business_doc_name'])) ?>,
        showPreviewModal: false,
        uploadedFileName: <?= json_encode($employer['business_doc_name'] ?? 'CAC_Incorporation_Certificate.pdf') ?>,
        uploadedFileSize: <?= json_encode($employer['business_doc_size'] ?? '1.8 MB') ?>,
        uploadedFileUrl: <?= json_encode(asset('dummy.pdf')) ?>,
        uploadedFileExt: 'pdf',
        draggingCac: false,

        // Password fields
        showCurrentPass: false,
        showNewPass: false,
        showConfirmPass: false,

        // Hiring focus departments
        selectedDepts: <?= json_encode($employer['departments'] ?? ['Engineering', 'Product Design', 'Marketing', 'Data Science']) ?>,
        allDepts: ['Engineering', 'Product Design', 'Marketing', 'Data Science', 'Finance', 'Operations', 'Sales', 'HR', 'Customer Success', 'Legal'],

        init() {
            if (this.successAlert) {
                this.$nextTick(() => {
                    this.$dispatch('show-toast', { text: this.successAlert, type: 'success' });
                    this.successAlert = '';
                });
            }
        },

        toggleDept(dept) {
            if (this.selectedDepts.includes(dept)) {
                this.selectedDepts = this.selectedDepts.filter(function(d) { return d !== dept; });
            } else {
                this.selectedDepts.push(dept);
            }
        },

        // Banner file handlers
        triggerBannerInput() {
            this.$refs.bannerInput.click();
        },
        handleBannerSelect(e) {
            if (e.target.files.length > 0) {
                this.uploadBannerFile(e.target.files[0]);
            }
        },
        uploadBannerFile(file) {
            if (!file) return;
            if (file.size > 8 * 1024 * 1024) {
                this.$dispatch('show-toast', { text: 'Cover banner exceeds 8MB limit.', type: 'warning' });
                return;
            }
            this.bannerUrl = URL.createObjectURL(file);
            this.$dispatch('show-toast', { text: 'Cover banner updated!', type: 'success' });
        },

        // Logo file handlers
        triggerLogoInput() {
            this.$refs.logoInput.click();
        },
        handleLogoSelect(e) {
            if (e.target.files.length > 0) {
                this.uploadLogoFile(e.target.files[0]);
            }
        },
        uploadLogoFile(file) {
            if (!file) return;
            if (file.size > 4 * 1024 * 1024) {
                this.$dispatch('show-toast', { text: 'Logo exceeds 4MB limit.', type: 'warning' });
                return;
            }
            this.logoUrl = URL.createObjectURL(file);
            this.$dispatch('show-toast', { text: 'Company logo updated!', type: 'success' });
        },

        // CAC Document handlers
        triggerCacInput() {
            this.$refs.cacInput.click();
        },
        handleCacDrop(e) {
            this.draggingCac = false;
            if (e.dataTransfer.files.length > 0) {
                this.uploadCacFile(e.dataTransfer.files[0]);
            }
        },
        handleCacSelect(e) {
            if (e.target.files.length > 0) {
                this.uploadCacFile(e.target.files[0]);
            }
        },
        uploadCacFile(file) {
            if (!file) return;
            if (file.size > 10 * 1024 * 1024) {
                this.$dispatch('show-toast', { text: 'Document exceeds 10MB limit.', type: 'warning' });
                return;
            }
            const ext = file.name.split('.').pop().toLowerCase();
            if (!['pdf', 'docx', 'jpg', 'jpeg', 'png'].includes(ext)) {
                this.$dispatch('show-toast', { text: 'Please upload a PDF, DOCX, or Image document.', type: 'warning' });
                return;
            }
            this.cacFile = file.name;
            this.cacSize = (file.size / (1024 * 1024)).toFixed(1) + ' MB';
            this.cacUploaded = new Date().toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
            this.uploadedFileName = file.name;
            this.uploadedFileSize = this.cacSize;
            this.uploadedFileExt = ext;
            this.uploadedFileUrl = URL.createObjectURL(file);
            this.cacVerified = true;
            this.$dispatch('show-toast', { text: 'Business registration document uploaded successfully!', type: 'success' });
        },
        deleteCacFile() {
            this.cacFile = '';
            this.cacSize = '';
            this.cacUploaded = '';
            this.uploadedFileName = '';
            this.uploadedFileSize = '';
            this.uploadedFileExt = '';
            this.cacVerified = false;
            this.$dispatch('show-toast', { text: 'Document removed.', type: 'info' });
        }
    };
}
</script>

<div class="db-shell" x-data="employerProfileSettings()">

    <!-- ══ SIDEBAR ══ -->
    <?php 
    $activeNav = 'profile';
    include dirname(__DIR__, 2) . '/shared/components/employer-sidebar.php'; 
    ?>

    <!-- ══ MAIN ══ -->
    <div class="db-main">

        <!-- ══ TOP NAVBAR ══ -->
        <?php include dirname(__DIR__, 2) . '/shared/components/employer-topnav.php'; ?>

        <!-- Scrollable Content -->
        <div class="db-content" id="db-content">

            <!-- Header Title -->
            <div class="db-profile-settings-header">
                <span>Employer Settings</span>
                <h1>Organization & <span>Brand Identity.</span></h1>
                <p>Manage your organization details, business verification, hiring parameters, and brand assets across InternConnect.</p>
            </div>

            <!-- Settings Grid -->
            <div class="db-settings-grid">

                <!-- Left Column (Settings Forms) -->
                <div class="db-settings-left">
                    <form action="<?= url('/employer/profile') ?>" method="POST" id="employer-profile-form">
                        <input type="hidden" name="action" value="update_profile">

                        <!-- 1. Brand Assets: Cover Banner & Logo -->
                        <div class="db-settings-card">
                            <div class="db-settings-card-header">
                                <div style="display: flex; align-items: center; gap: 8px;">
                                    <svg style="width: 18px; height: 18px; color: #4F47E6;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <h3 class="db-settings-card-title" style="margin: 0;">Brand Visual Assets</h3>
                                </div>
                                <button type="submit" class="db-btn-save">
                                    <svg style="width: 14px; height: 14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                    </svg>
                                    <span>Save Changes</span>
                                </button>
                            </div>

                            <!-- Hidden file inputs -->
                            <input type="file" x-ref="bannerInput" @change="handleBannerSelect" style="display: none;" accept="image/png,image/jpeg,image/webp">
                            <input type="file" x-ref="logoInput" @change="handleLogoSelect" style="display: none;" accept="image/png,image/jpeg,image/svg+xml,image/webp">

                            <!-- Interactive Banner & Logo Box -->
                            <div style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 16px; padding: 16px; margin-bottom: 20px;">
                                <label class="db-label" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                                    <span>Company Cover Banner</span>
                                    <span style="font-size: 11px; color: #64748B; font-weight: 500;">Recommended: 1200 × 320 px (Max 8MB)</span>
                                </label>
                                
                                <div style="position: relative; border-radius: 12px; overflow: hidden; height: 160px; background: #0F172A; border: 1px solid #CBD5E1; cursor: pointer;"
                                     @click="triggerBannerInput">
                                    <img :src="bannerUrl" alt="Cover Banner Preview" style="width: 100%; height: 100%; object-fit: cover; object-position: center; display: block;">
                                    <div style="position: absolute; inset: 0; background: linear-gradient(180deg, rgba(15, 23, 42, 0.15) 0%, rgba(15, 23, 42, 0.65) 100%); display: flex; align-items: center; justify-content: center; gap: 8px; opacity: 0; transition: opacity 0.2s ease;"
                                         onmouseover="this.style.opacity='1'" onmouseout="this.style.opacity='0'">
                                        <div style="background: #FFFFFF; color: #0F172A; padding: 8px 16px; border-radius: 10px; font-size: 12.5px; font-weight: 700; display: flex; align-items: center; gap: 6px; box-shadow: 0 4px 12px rgba(0,0,0,0.15);">
                                            <svg style="width: 16px; height: 16px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                            <span>Upload New Banner</span>
                                        </div>
                                    </div>
                                    <div style="position: absolute; bottom: 10px; right: 10px; background: rgba(15, 23, 42, 0.75); backdrop-filter: blur(4px); color: #FFFFFF; padding: 4px 10px; border-radius: 6px; font-size: 11px; font-weight: 600; display: flex; align-items: center; gap: 4px;">
                                        <svg style="width: 12px; height: 12px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                        </svg>
                                        <span>Click to Change</span>
                                    </div>
                                </div>

                                <!-- Company Logo Row -->
                                <div style="display: flex; align-items: center; gap: 16px; margin-top: 18px;">
                                    <div style="position: relative; width: 72px; height: 72px; border-radius: 50%; padding: 3px; background: #FFFFFF; border: 2px solid #E2E8F0; box-shadow: 0 4px 10px rgba(0,0,0,0.06); flex-shrink: 0; cursor: pointer;"
                                         @click="triggerLogoInput">
                                        <img :src="logoUrl" alt="Company Logo" style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover; background: #0F172A;">
                                        <div style="position: absolute; bottom: -2px; right: -2px; width: 24px; height: 24px; background: #4F47E6; color: #FFFFFF; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 2px solid #FFFFFF; box-shadow: 0 2px 4px rgba(0,0,0,0.15);">
                                            <svg style="width: 12px; height: 12px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                            </svg>
                                        </div>
                                    </div>
                                    <div style="flex: 1;">
                                        <div style="font-size: 13.5px; font-weight: 700; color: #0F172A; margin-bottom: 2px;">Company Logo / Icon</div>
                                        <div style="font-size: 12px; color: #64748B; margin-bottom: 8px;">Square aspect ratio (min 400 × 400 px, PNG, JPG, or SVG).</div>
                                        <div style="display: flex; gap: 8px;">
                                            <button type="button" class="db-btn-secondary" style="padding: 5px 12px; font-size: 12px; border-radius: 8px;" @click="triggerLogoInput">
                                                Upload New Logo
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 2. Organization Verification (CAC Registration & Legal Document) -->
                        <div class="db-settings-card">
                            <div class="db-settings-card-header">
                                <div style="display: flex; align-items: center; gap: 8px;">
                                    <svg style="width: 18px; height: 18px; color: #4F47E6;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                    </svg>
                                    <h3 class="db-settings-card-title" style="margin: 0;">Business Verification & CAC</h3>
                                </div>
                                <span class="db-badge-verified" style="background: #DCFCE7; color: #166534;">
                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                    </svg>
                                    Verified Status
                                </span>
                            </div>

                            <p style="font-size: 13px; color: #64748B; margin: 0 0 16px; line-height: 1.5;">
                                Your official registration details verify your organization to prospective interns, granting your internship postings verified badges and higher visibility.
                            </p>

                            <!-- CAC Number Input -->
                            <div class="db-form-row">
                                <div class="db-form-group">
                                    <label for="input-cac" class="db-label">CAC Registration / RC Number</label>
                                    <div class="db-input-group-icon" style="position: relative; display: flex; align-items: center; width: 100%;">
                                        <span class="db-input-icon-prefix" style="position: absolute; left: 14px; color: #64748B; pointer-events: none;">
                                            <svg style="width: 16px; height: 16px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14" />
                                            </svg>
                                        </span>
                                        <input type="text" name="cac_number" id="input-cac" class="db-input" style="padding-left: 40px;"
                                               value="<?= htmlspecialchars($employer['cac_number'] ?? 'RC-1849204') ?>"
                                               placeholder="e.g. RC-1849204">
                                    </div>
                                </div>
                                <div class="db-form-group">
                                    <label for="input-founded" class="db-label">Registration / Founded Year</label>
                                    <input type="text" name="founded_year" id="input-founded" class="db-input"
                                           value="2019" placeholder="e.g. 2019">
                                </div>
                            </div>

                            <!-- CAC Certificate Document Upload Area -->
                            <div style="margin-top: 10px;">
                                <label class="db-label" style="display: block; margin-bottom: 8px;">Business Registration Certificate (CAC)</label>
                                
                                <input type="file" x-ref="cacInput" @change="handleCacSelect" style="display: none;" accept=".pdf,.docx,.jpg,.jpeg,.png">

                                <!-- Drag-and-drop Zone if no file -->
                                <div x-show="!cacFile" class="db-upload-zone" :class="{ 'dragging': draggingCac }"
                                     @dragover.prevent="draggingCac = true" @dragleave.prevent="draggingCac = false"
                                     @drop.prevent="handleCacDrop" @click="triggerCacInput">
                                    <div class="db-upload-icon-wrap">
                                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                        </svg>
                                    </div>
                                    <h5>Upload Certificate of Incorporation</h5>
                                    <p>Drag and drop or <span style="color: #4F47E6; font-weight: 700; text-decoration: underline;">browse</span> your legal document</p>
                                    <span>PDF, DOCX, or High-Res Image up to 10MB</span>
                                </div>

                                <!-- Uploaded Document Card -->
                                <div x-show="cacFile" class="db-file-card" style="margin-top: 4px;">
                                    <div class="db-file-card-inner">
                                        <div class="db-file-visual-icon">
                                            <div class="db-file-visual-badge" x-text="cacFile.split('.').pop().toUpperCase()">PDF</div>
                                            <div class="db-file-visual-lines">
                                                <div class="db-file-visual-line"></div>
                                                <div class="db-file-visual-line accent"></div>
                                                <div class="db-file-visual-line"></div>
                                            </div>
                                        </div>
                                        <div class="db-file-card-details">
                                            <div class="db-file-card-name" x-text="cacFile"></div>
                                            <div class="db-file-card-meta">
                                                <span x-text="cacSize"></span> • Uploaded <span x-text="cacUploaded"></span>
                                            </div>
                                            <div class="db-file-card-status-badges">
                                                <span class="db-file-card-badge success">Verified Document</span>
                                                <span class="db-file-card-badge info">✓ CAC RC Record Matched</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="db-file-card-actions">
                                        <button type="button" @click="showPreviewModal = true" class="db-file-action-btn">
                                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zm6 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <span>View Doc</span>
                                        </button>
                                        <button type="button" @click="triggerCacInput" class="db-file-action-btn" title="Replace document">
                                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                            </svg>
                                            <span>Replace</span>
                                        </button>
                                        <button type="button" class="db-file-action-btn danger" @click="deleteCacFile" aria-label="Delete document">
                                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                            <span>Delete</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 3. Company Information Card -->
                        <div class="db-settings-card">
                            <div class="db-settings-card-header">
                                <h3 class="db-settings-card-title">Company Profile Details</h3>
                                <button type="submit" class="db-btn-save">
                                    <svg style="width: 14px; height: 14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                    </svg>
                                    <span>Save Changes</span>
                                </button>
                            </div>

                            <div class="db-form-row">
                                <div class="db-form-group">
                                    <label for="input-company-name" class="db-label">Company Name</label>
                                    <input type="text" name="company_name" id="input-company-name" class="db-input"
                                           value="<?= htmlspecialchars($employer['company_name'] ?? 'TechFlow Systems') ?>" required>
                                </div>
                                <div class="db-form-group">
                                    <label for="input-tagline" class="db-label">Company Slogan / Tagline</label>
                                    <input type="text" name="slogan" id="input-tagline" class="db-input"
                                           value="<?= htmlspecialchars($employer['slogan'] ?? 'Building innovative digital experiences for the future') ?>">
                                </div>
                            </div>

                            <div class="db-form-row">
                                <div class="db-form-group">
                                    <label class="db-label">Primary Industry</label>
                                    <?php 
                                    $industries = [
                                        'Software Development', 'Fintech', 'Artificial Intelligence', 
                                        'E-Commerce & Retail', 'Healthcare & Biotech', 'Digital Media & Design',
                                        'Banking & Financial Services', 'Telecommunications', 'Education Technology'
                                    ];
                                    $currInd = $employer['industry'] ?? 'Software Development';
                                    ?>
                                    <div class="db-dropdown-wrap" x-data="{ open: false, selected: '<?= htmlspecialchars($currInd) ?>', label: '<?= htmlspecialchars($currInd) ?>' }" @click.outside="open = false">
                                        <button type="button" class="db-dropdown-btn" :class="{ 'is-open': open }" @click="open = !open">
                                            <span x-text="label" :style="!selected ? 'color: #94A3B8;' : 'color: #1E293B; font-weight: 500;'">Select Industry</span>
                                            <svg class="db-dropdown-chevron" :class="{ 'rotate-180': open }" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="m6 9 6 6 6-6"/></svg>
                                        </button>
                                        <div x-show="open" x-transition class="db-dropdown-menu" style="display: none;">
                                            <?php foreach ($industries as $ind): ?>
                                                <div class="db-dropdown-item" :class="{ 'is-selected': selected === '<?= htmlspecialchars($ind, ENT_QUOTES) ?>' }" @click="selected = '<?= htmlspecialchars($ind, ENT_QUOTES) ?>'; label = '<?= htmlspecialchars($ind, ENT_QUOTES) ?>'; open = false;">
                                                    <span><?= htmlspecialchars($ind) ?></span>
                                                    <svg x-show="selected === '<?= htmlspecialchars($ind, ENT_QUOTES) ?>'" class="db-dropdown-check" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                        <input type="hidden" name="industry" :value="selected">
                                    </div>
                                </div>
                                <div class="db-form-group">
                                    <label class="db-label">Company Size</label>
                                    <?php 
                                    $sizes = ['1-10 employees', '11-50 employees', '51-200 employees', '201–500 employees', '500+ employees'];
                                    $currSize = $employer['company_size'] ?? '201–500 employees';
                                    ?>
                                    <div class="db-dropdown-wrap" x-data="{ open: false, selected: '<?= htmlspecialchars($currSize) ?>', label: '<?= htmlspecialchars($currSize) ?>' }" @click.outside="open = false">
                                        <button type="button" class="db-dropdown-btn" :class="{ 'is-open': open }" @click="open = !open">
                                            <span x-text="label" :style="!selected ? 'color: #94A3B8;' : 'color: #1E293B; font-weight: 500;'">Select Size</span>
                                            <svg class="db-dropdown-chevron" :class="{ 'rotate-180': open }" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="m6 9 6 6 6-6"/></svg>
                                        </button>
                                        <div x-show="open" x-transition class="db-dropdown-menu" style="display: none;">
                                            <?php foreach ($sizes as $sOpt): ?>
                                                <div class="db-dropdown-item" :class="{ 'is-selected': selected === '<?= htmlspecialchars($sOpt, ENT_QUOTES) ?>' }" @click="selected = '<?= htmlspecialchars($sOpt, ENT_QUOTES) ?>'; label = '<?= htmlspecialchars($sOpt, ENT_QUOTES) ?>'; open = false;">
                                                    <span><?= htmlspecialchars($sOpt) ?></span>
                                                    <svg x-show="selected === '<?= htmlspecialchars($sOpt, ENT_QUOTES) ?>'" class="db-dropdown-check" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                        <input type="hidden" name="company_size" :value="selected">
                                    </div>
                                </div>
                            </div>

                            <!-- Description / About -->
                            <div class="db-form-group db-form-group-full" style="margin-top: 6px;">
                                <label for="textarea-desc" class="db-label">About Company / Overview</label>
                                <textarea name="description" id="textarea-desc" class="db-input db-textarea" rows="4" required><?= htmlspecialchars($employer['description'] ?? '') ?></textarea>
                            </div>

                            <!-- Culture & Values -->
                            <div class="db-form-group db-form-group-full" style="margin-top: 14px;">
                                <label for="textarea-culture" class="db-label">Culture & Work Values</label>
                                <textarea name="culture_description" id="textarea-culture" class="db-input db-textarea" rows="3"><?= htmlspecialchars($employer['culture_description'] ?? '') ?></textarea>
                            </div>
                        </div>

                        <!-- 4. Hiring Focus & Internship Program -->
                        <div class="db-settings-card">
                            <div class="db-settings-card-header">
                                <div style="display: flex; align-items: center; gap: 8px;">
                                    <svg style="width: 18px; height: 18px; color: #4F47E6;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                    <h3 class="db-settings-card-title" style="margin: 0;">Internship Program & Focus</h3>
                                </div>
                                <button type="submit" class="db-btn-save">
                                    <svg style="width: 14px; height: 14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                    </svg>
                                    <span>Save Changes</span>
                                </button>
                            </div>

                            <!-- Target Focus Departments / Tags -->
                            <div style="margin-bottom: 20px;">
                                <label class="db-label" style="display: block; margin-bottom: 8px;">Target Internship Departments</label>
                                <div class="emp-tags-wrapper">
                                    <?php 
                                    $allDeptsList = ['Engineering', 'Product Design', 'Marketing', 'Data Science', 'Finance', 'Operations', 'Sales', 'HR', 'Customer Success', 'Legal'];
                                    foreach ($allDeptsList as $dept): ?>
                                        <button type="button" 
                                                class="emp-tag-pill"
                                                :class="{ 'is-active': selectedDepts.includes('<?= $dept ?>') }"
                                                @click="toggleDept('<?= $dept ?>')">
                                            <span><?= $dept ?></span>
                                            <svg x-show="selectedDepts.includes('<?= $dept ?>')" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                            </svg>
                                        </button>
                                    <?php endforeach; ?>
                                </div>
                                <input type="hidden" name="departments" :value="JSON.stringify(selectedDepts)">
                            </div>

                            <div class="db-form-row">
                                <div class="db-form-group">
                                    <label class="db-label">Expected Intern Intake Volume</label>
                                    <?php 
                                    $volumes = ['1 - 5 Interns', '6 - 15 Interns', '16 - 30 Interns', '30+ Interns'];
                                    $currVol = $employer['expected_volume'] ?? '6 - 15 Interns';
                                    ?>
                                    <div class="db-dropdown-wrap" x-data="{ open: false, selected: '<?= htmlspecialchars($currVol) ?>', label: '<?= htmlspecialchars($currVol) ?>' }" @click.outside="open = false">
                                        <button type="button" class="db-dropdown-btn" :class="{ 'is-open': open }" @click="open = !open">
                                            <span x-text="label" :style="!selected ? 'color: #94A3B8;' : 'color: #1E293B; font-weight: 500;'">Select Volume</span>
                                            <svg class="db-dropdown-chevron" :class="{ 'rotate-180': open }" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="m6 9 6 6 6-6"/></svg>
                                        </button>
                                        <div x-show="open" x-transition class="db-dropdown-menu" style="display: none;">
                                            <?php foreach ($volumes as $v): ?>
                                                <div class="db-dropdown-item" :class="{ 'is-selected': selected === '<?= htmlspecialchars($v, ENT_QUOTES) ?>' }" @click="selected = '<?= htmlspecialchars($v, ENT_QUOTES) ?>'; label = '<?= htmlspecialchars($v, ENT_QUOTES) ?>'; open = false;">
                                                    <span><?= htmlspecialchars($v) ?></span>
                                                    <svg x-show="selected === '<?= htmlspecialchars($v, ENT_QUOTES) ?>'" class="db-dropdown-check" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                        <input type="hidden" name="expected_volume" :value="selected">
                                    </div>
                                </div>
                                <div class="db-form-group">
                                    <label class="db-label">Program Compensation Type</label>
                                    <?php 
                                    $types = ['Paid', 'Stipend', 'Academic Credit'];
                                    $currType = $employer['internship_type'] ?? 'Paid';
                                    ?>
                                    <div class="db-dropdown-wrap" x-data="{ open: false, selected: '<?= htmlspecialchars($currType) ?>', label: '<?= htmlspecialchars($currType) ?>' }" @click.outside="open = false">
                                        <button type="button" class="db-dropdown-btn" :class="{ 'is-open': open }" @click="open = !open">
                                            <span x-text="label" :style="!selected ? 'color: #94A3B8;' : 'color: #1E293B; font-weight: 500;'">Select Compensation</span>
                                            <svg class="db-dropdown-chevron" :class="{ 'rotate-180': open }" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="m6 9 6 6 6-6"/></svg>
                                        </button>
                                        <div x-show="open" x-transition class="db-dropdown-menu" style="display: none;">
                                            <?php foreach ($types as $t): ?>
                                                <div class="db-dropdown-item" :class="{ 'is-selected': selected === '<?= htmlspecialchars($t, ENT_QUOTES) ?>' }" @click="selected = '<?= htmlspecialchars($t, ENT_QUOTES) ?>'; label = '<?= htmlspecialchars($t, ENT_QUOTES) ?>'; open = false;">
                                                    <span><?= htmlspecialchars($t) ?></span>
                                                    <svg x-show="selected === '<?= htmlspecialchars($t, ENT_QUOTES) ?>'" class="db-dropdown-check" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                        <input type="hidden" name="internship_type" :value="selected">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 5. Contact & Social Links Card -->
                        <div class="db-settings-card">
                            <div class="db-settings-card-header">
                                <h3 class="db-settings-card-title">Contact & Presence Links</h3>
                                <button type="submit" class="db-btn-save">
                                    <svg style="width: 14px; height: 14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                    </svg>
                                    <span>Save Changes</span>
                                </button>
                            </div>

                            <div class="db-form-row">
                                <div class="db-form-group">
                                    <label for="input-email" class="db-label">Careers Email</label>
                                    <input type="email" name="email" id="input-email" class="db-input"
                                           value="<?= htmlspecialchars($employer['email'] ?? 'careers@techflow.io') ?>" required>
                                </div>
                                <div class="db-form-group">
                                    <label for="input-phone" class="db-label">Office Phone Number</label>
                                    <input type="text" name="phone" id="input-phone" class="db-input"
                                           value="<?= htmlspecialchars($employer['phone'] ?? '+234 801 234 5678') ?>" required>
                                </div>
                            </div>

                            <div class="db-form-row">
                                <div class="db-form-group">
                                    <label for="input-website" class="db-label">Company Website URL</label>
                                    <input type="url" name="website" id="input-website" class="db-input"
                                           value="<?= htmlspecialchars($employer['website'] ?? 'https://techflow.io') ?>">
                                </div>
                                <div class="db-form-group">
                                    <label for="input-location" class="db-label">Headquarters / Office Location</label>
                                    <input type="text" name="location" id="input-location" class="db-input"
                                           value="<?= htmlspecialchars($employer['location'] ?? 'Victoria Island, Lagos, Nigeria') ?>">
                                </div>
                            </div>

                            <!-- Social Profiles (6 by 6 Two-Side Layout) -->
                            <div style="padding-top: 18px; margin-top: 6px; border-top: 1px solid #F1F5F9;">
                                <div class="db-form-row">
                                    <div class="db-form-group">
                                        <label for="input-linkedin" class="db-label">LinkedIn Page URL</label>
                                        <div class="db-input-group-icon" style="position: relative; display: flex; align-items: center; width: 100%;">
                                            <span class="db-input-icon-prefix" style="position: absolute; left: 14px; color: #64748B; pointer-events: none;">
                                                <svg style="width: 16px; height: 16px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                                                </svg>
                                            </span>
                                            <input type="text" name="linkedin" id="input-linkedin" class="db-input" style="padding-left: 40px;"
                                                   value="linkedin.com/company/techflow" placeholder="e.g. linkedin.com/company/techflow">
                                        </div>
                                    </div>
                                    <div class="db-form-group">
                                        <label for="input-twitter" class="db-label">X / Twitter Handle</label>
                                        <div class="db-input-group-icon" style="position: relative; display: flex; align-items: center; width: 100%;">
                                            <span class="db-input-icon-prefix" style="position: absolute; left: 14px; color: #64748B; pointer-events: none;">
                                                <svg style="width: 16px; height: 16px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14" />
                                                </svg>
                                            </span>
                                            <input type="text" name="twitter" id="input-twitter" class="db-input" style="padding-left: 40px;"
                                                   value="@TechFlowHQ" placeholder="e.g. @TechFlowHQ">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 6. Notification Preferences -->
                        <div class="db-settings-card">
                            <div class="db-settings-card-header">
                                <h3 class="db-settings-card-title">Notification Preferences</h3>
                                <button type="submit" class="db-btn-save">
                                    <svg style="width: 14px; height: 14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                    </svg>
                                    <span>Save Changes</span>
                                </button>
                            </div>

                            <div class="db-notif-list">
                                <div class="db-notif-item">
                                    <div class="db-notif-info">
                                        <h5>New Candidate Applications</h5>
                                        <p>Instant alerts when qualified candidates submit applications to your active internships.</p>
                                    </div>
                                    <label class="db-switch">
                                        <input type="checkbox" name="notif_applicants" value="1" <?= ($employer['notif_applicants'] ?? true) ? 'checked' : '' ?>>
                                        <span class="db-switch-slider"></span>
                                    </label>
                                </div>

                                <div class="db-notif-item">
                                    <div class="db-notif-info">
                                        <h5>Interview Schedule & RSVPs</h5>
                                        <p>Notifications for interview acceptances, reschedule requests, and meeting reminders.</p>
                                    </div>
                                    <label class="db-switch">
                                        <input type="checkbox" name="notif_interviews" value="1" <?= ($employer['notif_interviews'] ?? true) ? 'checked' : '' ?>>
                                        <span class="db-switch-slider"></span>
                                    </label>
                                </div>

                                <div class="db-notif-item">
                                    <div class="db-notif-info">
                                        <h5>Platform Announcements & Tips</h5>
                                        <p>Periodic product releases, talent market reports, and internship best practices.</p>
                                    </div>
                                    <label class="db-switch">
                                        <input type="checkbox" name="notif_announcements" value="1" <?= ($employer['notif_announcements'] ?? false) ? 'checked' : '' ?>>
                                        <span class="db-switch-slider"></span>
                                    </label>
                                </div>
                            </div>
                        </div>

                    </form>

                    <!-- 7. Account Management / Deactivate Card -->
                    <div class="db-danger-card">
                        <div class="db-danger-card-body">
                            <div class="db-danger-card-info">
                                <h4>Deactivate Organization Account</h4>
                                <p>Permanently remove company profile, all active internship posts, and archived applicant records.</p>
                            </div>
                            <button type="button" class="db-btn-danger-card" @click="confirmNameInput = ''; deactivateModal = true;">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                <span>Deactivate Account</span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Right Column (Security, Authentication, Sessions) -->
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
                                    <span class="db-auth-val" title="<?= htmlspecialchars($employer['email'] ?? 'careers@techflow.io') ?>">
                                        <?= htmlspecialchars($employer['email'] ?? 'careers@techflow.io') ?>
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
                                    <span class="db-auth-label">Google Workspace OAuth</span>
                                    <span class="db-auth-val" title="<?= htmlspecialchars($employer['google_email'] ?? 'techflow.official@gmail.com') ?>">
                                        <?= htmlspecialchars($employer['google_email'] ?? 'techflow.official@gmail.com') ?>
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
                            Corporate Google authentication is linked to your portal profile. You can log in securely via single sign-on without duplicating employer profiles.
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

                        <form action="<?= url('/employer/profile') ?>" method="POST" id="employer-password-form">
                            <input type="hidden" name="action" value="update_password">

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

                            <button type="submit" class="db-btn-primary-form">
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
                            $sessions = $employer['active_sessions'] ?? [];
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
        <?php include dirname(__DIR__, 2) . '/shared/components/db-footer.php'; ?>

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
            
            <h4 style="font-size: 19px; font-weight: 800; color: #0F172A; margin: 0 0 8px;">Deactivate Organization</h4>
            <p style="font-size: 13.5px; color: #64748B; line-height: 1.5; margin: 0 0 20px;">
                Are you sure you want to deactivate your organization account? This action is permanent and will withdraw all active internships and applicant records.
            </p>
            
            <div style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 12px; padding: 14px 16px; margin-bottom: 24px;">
                <label for="confirm-company-name-input" style="display: block; font-size: 12px; font-weight: 600; color: #475569; margin-bottom: 8px;">
                    To confirm, type your company name <strong style="color: #0F172A; font-weight: 800;" x-text="companyFullName"></strong> below:
                </label>
                <input type="text" 
                    id="confirm-company-name-input"
                    x-model="confirmNameInput" 
                    class="db-input" 
                    placeholder="Type company name to confirm"
                    style="background: #FFFFFF; font-size: 13.5px;"
                    autocomplete="off">
            </div>
            
            <div style="display: flex; gap: 12px; justify-content: flex-end; align-items: center;">
                <button type="button" class="db-btn-modal-cancel" @click="deactivateModal = false; confirmNameInput = '';">
                    Cancel
                </button>
                <button type="button" 
                    class="db-btn-danger-confirm"
                    :disabled="confirmNameInput.trim().toLowerCase() !== companyFullName.trim().toLowerCase()"
                    @click="deactivateModal = false; confirmNameInput = ''; $dispatch('show-toast', { text: 'Organization deactivated successfully.', type: 'danger' });">
                    Deactivate Account
                </button>
            </div>
        </div>
    </div>

    <!-- ════════════ Full Screen Document Preview Modal ════════════ -->
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
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gray-50/50 flex-shrink-0">
                <div class="flex items-center gap-3">
                    <div class="h-10 w-10 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center font-bold text-sm border border-indigo-100"
                        x-text="uploadedFileExt.toUpperCase()">PDF</div>
                    <div>
                        <h3 class="text-base font-bold text-slate-800 leading-tight" x-text="uploadedFileName" style="margin: 0;"></h3>
                        <p class="text-xs text-slate-500" x-text="uploadedFileSize + ' • CAC Document'" style="margin: 0;"></p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <span class="text-xs text-slate-400 font-medium hidden sm:inline"
                        x-text="uploadedFileExt === 'pdf' ? 'PDF • Live Document Preview' : (uploadedFileExt.toUpperCase() + ' • Document')"></span>
                    <button type="button" @click="showPreviewModal = false"
                        class="p-2 text-gray-400 hover:text-gray-700 hover:bg-gray-100 rounded-lg transition-colors"
                        style="background: none; border: none; cursor: pointer;">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width: 20px; height: 20px;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Modal Content -->
            <div class="flex-1 overflow-hidden bg-gray-50 relative" style="min-height: 0;">
                <div x-show="uploadedFileExt === 'pdf'" class="absolute inset-0">
                    <iframe :src="uploadedFileExt === 'pdf' ? (uploadedFileUrl + '#toolbar=0&navpanes=0&scrollbar=0&view=Fit') : ''"
                        class="w-full h-full" style="border: none;" title="CAC Document Preview"></iframe>
                </div>

                <div x-show="uploadedFileExt !== 'pdf'" class="absolute inset-0 flex flex-col items-center justify-center gap-6 p-8">
                    <div class="flex items-center justify-center h-20 w-20 rounded-3xl bg-indigo-50 border border-indigo-100 shadow-sm"
                        style="width: 80px; height: 80px; border-radius: 20px;">
                        <svg class="h-10 w-10 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" style="width: 40px; height: 40px;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                        </svg>
                    </div>
                    <div class="text-center max-w-sm" style="text-align: center;">
                        <p class="font-bold text-slate-800 text-lg mb-2" x-text="uploadedFileName" style="font-size: 18px; margin-bottom: 8px;"></p>
                        <p class="text-sm text-slate-500 mb-1" x-text="uploadedFileSize + ' • Verified Document'" style="font-size: 14px; margin-bottom: 4px;"></p>
                        <p class="text-sm text-slate-400 leading-relaxed" style="font-size: 13px; color: #94A3B8; line-height: 1.5;">
                            Document preview is available in full resolution.
                        </p>
                    </div>
                    <a :href="uploadedFileUrl" :download="uploadedFileName"
                        class="inline-flex items-center gap-2 text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 px-5 py-2.5 rounded-xl transition active:scale-95 shadow"
                        style="text-decoration: none; padding: 10px 20px; border-radius: 12px; font-size: 14px; color: #fff; background: #4F47E6;">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width: 16px; height: 16px;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        Download Document
                    </a>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="px-5 sm:px-6 py-4 border-t border-gray-100 flex items-center justify-between bg-white flex-shrink-0">
                <span class="text-xs text-slate-400">CAC Incorporation Certificate</span>
                <button type="button" @click="showPreviewModal = false"
                    class="text-white text-sm font-semibold py-2.5 px-5 rounded-xl transition active:scale-95 shadow-sm"
                    style="background: #4F47E6; border: none; color: #fff; padding: 10px 20px; border-radius: 12px; cursor: pointer;">
                    Close Preview
                </button>
            </div>
        </div>
    </div>

</div><!-- /.db-shell -->
