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
    portfolio: '<?= htmlspecialchars($candidate['portfolio'] ?? 'sarahpeters.design') ?>',
    linkedin: '<?= htmlspecialchars($candidate['linkedin'] ?? 'in/sarah-peters') ?>',
    portfolioEdit: '<?= htmlspecialchars($candidate['portfolio'] ?? 'sarahpeters.design') ?>',
    linkedinEdit: '<?= htmlspecialchars($candidate['linkedin'] ?? 'in/sarah-peters') ?>',
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

    <!-- Sidebar Overlay (mobile) -->
    <div class="db-sidebar-overlay" id="db-sidebar-overlay"></div>

    <!-- ══ SIDEBAR ══ -->
    <aside class="db-sidebar" role="complementary" aria-label="Dashboard sidebar">
        <!-- Logo -->
        <div class="db-sidebar-logo">
            <img src="<?= asset('assets/images/logos/Logo_dark_dx00tg.svg') ?>" alt="InternConnect">
        </div>

        <!-- Profile Block -->
        <div class="db-profile">
            <div class="db-avatar-wrap" x-data="{ open: false }">
                <?php if ($avatar): ?>
                    <img class="db-avatar" src="<?= htmlspecialchars($avatar) ?>" alt="<?= htmlspecialchars($name) ?>">
                <?php else: ?>
                    <div class="db-avatar-initials" aria-hidden="true">
                        <?= strtoupper(substr($firstName, 0, 1)) . strtoupper(substr(explode(' ', $name)[1] ?? '', 0, 1)) ?>
                    </div>
                <?php endif; ?>
                <div class="db-avatar-edit" role="button" aria-label="Edit profile photo" @click="open = !open">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                    </svg>
                </div>
                <!-- Upload Dropdown -->
                <div x-show="open" @click.outside="open = false"
                    style="display: none; position: absolute; top: calc(100% + 5px); left: 50%; transform: translateX(-50%); width: max-content; min-width: 160px; white-space: nowrap; z-index: 100;"
                    class="db-dropdown">
                    <div style="font-size: 13px; color: #191C1D; padding: 8px 16px; cursor: pointer; border-bottom: 1px solid #F0F2F7;"
                        onmouseover="this.style.background='#F1F5F9'" onmouseout="this.style.background='transparent'">
                        Upload Image</div>
                    <div style="font-size: 13px; color: #DC2626; padding: 8px 16px; cursor: pointer;"
                        onmouseover="this.style.background='#FEE2E2'" onmouseout="this.style.background='transparent'">
                        Remove Image</div>
                </div>
            </div>
            <div class="db-profile-name"><?= htmlspecialchars($name) ?></div>
            <div class="db-profile-role"><?= htmlspecialchars($role) ?></div>
        </div>

        <!-- Nav -->
        <nav class="db-nav" aria-label="Sidebar navigation">
            <a href="<?= url('/dashboard') ?>" class="db-nav-link">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                </svg>
                <span>Dashboard</span>
            </a>
            <a href="<?= url('/saved') ?>" class="db-nav-link">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                </svg>
                <span>Saved Internships</span>
            </a>
            <a href="<?= url('/applications') ?>" class="db-nav-link">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                <span>My Applications</span>
            </a>
            <a href="<?= url('/internships') ?>" class="db-nav-link">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <span>Browse Opportunities</span>
            </a>
            <a href="<?= url('/help') ?>" class="db-nav-link">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192L5.636 18.364M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                <span>Help Center</span>
            </a>
            <a href="<?= url('/profile') ?>" class="db-nav-link is-active" aria-current="page">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <span>Profile Settings</span>
            </a>
        </nav>

        <!-- Promo -->
        <div class="db-promo">
            <h4>Find More Opportunities</h4>
            <p>Upgrade to unlock exclusive listings, smarter insights, and increase your chances of getting hired.</p>
            <a href="<?= url('/subscribe') ?>" class="db-promo-btn">Subscribe Now</a>
        </div>
    </aside>

    <!-- ══ MAIN ══ -->
    <div class="db-main">

        <!-- Top Navbar -->
        <header class="db-topnav" role="banner">
            <div class="db-topnav-inner">
                <!-- Mobile Hamburger -->
                <button class="db-hamburger" id="db-hamburger" type="button" aria-label="Open menu">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
                <div class="db-search">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input id="db-search-input" type="search" placeholder="Search internships, companies..."
                        aria-label="Search">
                </div>
                <div class="db-topnav-spacer"></div>
                <!-- Notification -->
                <div class="db-topnav-icon" role="button" aria-label="Notifications" id="db-topnav-notif" tabindex="0"
                    x-data="{ open: false }" @click.outside="open = false" @click="open = !open">
                    <div class="db-notif-dot" aria-hidden="true"></div>
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>

                    <div x-show="open" style="display: none; width: 320px;" class="db-dropdown" @click.stop>
                        <div class="db-dropdown-title"
                            style="margin-bottom: 12px; display: flex; justify-content: space-between; align-items: center;">
                            <span>Notifications</span>
                            <span
                                style="background: #FEE2E2; color: #EF4444; font-size: 10px; padding: 2px 6px; border-radius: 99px; font-weight: 700;">7
                                New</span>
                        </div>
                        <div
                            style="display: flex; flex-direction: column; gap: 8px; max-height: 240px; overflow-y: auto;">
                            <div style="display: flex; gap: 10px; padding: 8px; border-radius: 8px; cursor: pointer; transition: background .12s;"
                                onmouseover="this.style.background='#F8FAFC'"
                                onmouseout="this.style.background='transparent'">
                                <div
                                    style="width: 28px; height: 28px; border-radius: 50%; background: #EFF6FF; display: flex; align-items: center; justify-content: center; color: #3B82F6; flex-shrink: 0;">
                                    <svg style="width: 14px; height: 14px;" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div>
                                    <div style="font-size: 12px; font-weight: 600; color: #191C1D;">Slack Interview
                                        Scheduled</div>
                                    <div style="font-size: 11px; color: #64748B;">Tomorrow at 10:00 AM</div>
                                </div>
                            </div>
                            <div style="display: flex; gap: 10px; padding: 8px; border-radius: 8px; cursor: pointer; transition: background .12s;"
                                onmouseover="this.style.background='#F8FAFC'"
                                onmouseout="this.style.background='transparent'">
                                <div
                                    style="width: 28px; height: 28px; border-radius: 50%; background: #DCFCE7; display: flex; align-items: center; justify-content: center; color: #16A34A; flex-shrink: 0;">
                                    <svg style="width: 14px; height: 14px;" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <div style="font-size: 12px; font-weight: 600; color: #191C1D;">Uber Application
                                        Received</div>
                                    <div style="font-size: 11px; color: #64748B;">2 hours ago</div>
                                </div>
                            </div>
                            <div style="display: flex; gap: 10px; padding: 8px; border-radius: 8px; cursor: pointer; transition: background .12s;"
                                onmouseover="this.style.background='#F5F3FF'"
                                onmouseout="this.style.background='transparent'">
                                <div
                                    style="width: 28px; height: 28px; border-radius: 50%; background: #F5F3FF; display: flex; align-items: center; justify-content: center; color: #7C3AED; flex-shrink: 0;">
                                    <svg style="width: 14px; height: 14px;" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                                    </svg>
                                </div>
                                <div>
                                    <div style="font-size: 12px; font-weight: 600; color: #191C1D;">New Message from
                                        Figma</div>
                                    <div style="font-size: 11px; color: #64748B;">4 hours ago</div>
                                </div>
                            </div>
                        </div>
                        <a href="<?= url('/notifications') ?>"
                            style="display: block; text-align: center; font-size: 12px; color: #4F47E6; font-weight: 600; margin-top: 12px; text-decoration: none;"
                            onmouseover="this.style.textDecoration='underline'"
                            onmouseout="this.style.textDecoration='none'">View All Notifications</a>
                    </div>
                </div>
                <!-- Settings -->
                <div class="db-topnav-icon" role="button" aria-label="Settings" id="db-topnav-settings" tabindex="0"
                    x-data="{ open: false }" @click.outside="open = false" @click="open = !open">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>

                    <div x-show="open" style="display: none; width: 200px;" class="db-dropdown" @click.stop>
                        <div class="db-dropdown-title">Account Settings</div>
                        <div style="display: flex; flex-direction: column; gap: 4px;">
                            <div style="display: flex; align-items: center; gap: 8px; padding: 8px 10px; border-radius: 6px; cursor: pointer; font-size: 13px; color: #475569;"
                                onmouseover="this.style.background='#F1F5F9'; this.style.color='#1E293B'"
                                onmouseout="this.style.background='transparent'; this.style.color='#475569'">
                                <svg style="width: 14px; height: 14px; color: #64748B;" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zm-4 7a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                <span>Profile Details</span>
                            </div>
                            <div style="display: flex; align-items: center; gap: 8px; padding: 8px 10px; border-radius: 6px; cursor: pointer; font-size: 13px; color: #475569;"
                                onmouseover="this.style.background='#F1F5F9'; this.style.color='#1E293B'"
                                onmouseout="this.style.background='transparent'; this.style.color='#475569'">
                                <svg style="width: 14px; height: 14px; color: #64748B;" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                                <span>Security & Privacy</span>
                            </div>
                            <div style="display: flex; align-items: center; gap: 8px; padding: 8px 10px; border-radius: 6px; cursor: pointer; font-size: 13px; color: #475569;"
                                onmouseover="this.style.background='#F1F5F9'; this.style.color='#1E293B'"
                                onmouseout="this.style.background='transparent'; this.style.color='#475569'">
                                <svg style="width: 14px; height: 14px; color: #64748B;" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                </svg>
                                <span>Notification Settings</span>
                            </div>
                            <div style="display: flex; align-items: center; gap: 8px; padding: 8px 10px; border-radius: 6px; cursor: pointer; font-size: 13px; color: #475569;"
                                onmouseover="this.style.background='#F1F5F9'; this.style.color='#1E293B'"
                                onmouseout="this.style.background='transparent'; this.style.color='#475569'">
                                <svg style="width: 14px; height: 14px; color: #64748B;" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192L5.636 18.364M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                                <span>Help & Support</span>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Sign Out -->
                <a href="<?= url('/logout') ?>" class="db-signout" id="db-signout-btn" title="Sign Out">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    <span class="db-signout-text">Sign Out</span>
                </a>
            </div>
        </header>

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
                    <form action="<?= url('/profile') ?>" method="POST">

                        <!-- Personal Info Card -->
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
                                        value="<?= htmlspecialchars($candidate['name']) ?>" required>
                                </div>
                                <div class="db-form-group">
                                    <label for="input-email" class="db-label">Email Address</label>
                                    <input type="email" name="email" id="input-email" class="db-input"
                                        value="<?= htmlspecialchars($candidate['email']) ?>" required>
                                </div>
                            </div>

                            <div class="db-form-row">
                                <div class="db-form-group">
                                    <label for="input-role" class="db-label">Current Role</label>
                                    <input type="text" name="role" id="input-role" class="db-input"
                                        value="<?= htmlspecialchars($candidate['role']) ?>" required>
                                </div>
                                <div class="db-form-group">
                                    <label for="input-phone" class="db-label">Phone Number</label>
                                    <input type="text" name="phone" id="input-phone" class="db-input"
                                        value="<?= htmlspecialchars($candidate['phone']) ?>" required>
                                </div>
                            </div>

                            <div class="db-form-group db-form-group-full">
                                <label for="textarea-bio" class="db-label">Professional Bio</label>
                                <textarea name="bio" id="textarea-bio" class="db-input db-textarea"
                                    placeholder="Tell us about yourself..."
                                    required><?= htmlspecialchars($candidate['bio']) ?></textarea>
                            </div>
                        </div>

                        <!-- Resume & Portfolio Card -->
                        <div class="db-settings-card">
                            <div class="db-settings-card-header">
                                <h3 class="db-settings-card-title">Resume & Portfolio</h3>
                            </div>

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
                                                <span class="db-file-card-badge info">✓ Parsed by InternConnect
                                                    AI</span>
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

                        <!-- Notification Preferences Card -->
                        <div class="db-settings-card">
                            <div class="db-settings-card-header">
                                <h3 class="db-settings-card-title">Notification Preferences</h3>
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

                        <!-- Hidden Fields to save current link states -->
                        <input type="hidden" name="portfolio" :value="portfolio">
                        <input type="hidden" name="linkedin" :value="linkedin">

                    </form>

                    <!-- Deactivate Link Action -->
                    <div class="db-deactivate-container">
                        <button type="button" class="db-btn-deactivate" @click="deactivateModal = true">
                            <svg style="width: 14px; height: 14px;" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M13 7a4 4 0 11-8 0 4 4 0 018 0zM9 14a6 6 0 00-6 6v1h12v-1a6 6 0 00-6-6zM21 12h-6" />
                            </svg>
                            <span>Deactivate Account</span>
                        </button>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="db-settings-right">

                    <!-- Quick Links Card -->
                    <div class="db-settings-card">
                        <div class="db-settings-card-header">
                            <h3 class="db-settings-card-title">Quick Links</h3>
                        </div>

                        <!-- View Links Mode -->
                        <div x-show="!editingLinks">
                            <div class="db-links-list">
                                <!-- Portfolio link -->
                                <div class="db-link-item">
                                    <div class="db-link-icon">
                                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                                        </svg>
                                    </div>
                                    <div class="db-link-details">
                                        <span class="db-link-label">Portfolio</span>
                                        <span class="db-link-url" x-text="portfolio"></span>
                                    </div>
                                </div>

                                <!-- LinkedIn link -->
                                <div class="db-link-item">
                                    <div class="db-link-icon">
                                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                                        </svg>
                                    </div>
                                    <div class="db-link-details">
                                        <span class="db-link-label">LinkedIn</span>
                                        <span class="db-link-url" x-text="linkedin"></span>
                                    </div>
                                </div>
                            </div>

                            <button type="button" class="db-btn-primary-form" @click="editingLinks = true"
                                id="btn-edit-links">
                                Update Links
                            </button>
                        </div>

                        <!-- Edit Links Mode -->
                        <div x-show="editingLinks" style="display: none;">
                            <div style="display: flex; flex-direction: column; gap: 16px; margin-bottom: 20px;">
                                <div class="db-form-group">
                                    <label class="db-label">Portfolio URL</label>
                                    <input type="text" x-model="portfolioEdit" class="db-input">
                                </div>
                                <div class="db-form-group">
                                    <label class="db-label">LinkedIn username/path</label>
                                    <input type="text" x-model="linkedinEdit" class="db-input">
                                </div>
                            </div>
                            <div style="display: flex; gap: 10px;">
                                <button type="button" class="db-btn-primary-form" style="flex: 1;"
                                    @click="saveQuickLinks" id="btn-save-links">Save</button>
                                <button type="button" class="db-btn-primary-form"
                                    style="flex: 1; background: #E2E8F0; color: #475569;"
                                    @click="editingLinks = false">Cancel</button>
                            </div>
                        </div>
                    </div>

                </div>

            </div>

        </div><!-- /.db-content -->

        <!-- Global Footer -->
        <div class="db-footer-wrap" style="width: 100%; background: #fff; border-top: 1px solid #E8ECF2;">
            <?php include __DIR__ . '/../shared/components/footer.php'; ?>
        </div>

        <!-- Mobile Sidebar Toggle Script -->
        <script>
            (function () {
                var hamburger = document.getElementById('db-hamburger');
                var sidebar = document.querySelector('.db-sidebar');
                var overlay = document.getElementById('db-sidebar-overlay');
                if (!hamburger || !sidebar || !overlay) return;
                function toggleSidebar() {
                    sidebar.classList.toggle('is-open');
                    overlay.classList.toggle('is-open');
                }
                hamburger.addEventListener('click', toggleSidebar);
                overlay.addEventListener('click', toggleSidebar);
            })();
        </script>
    </div><!-- /.db-main -->

    <!-- Deactivate Account Confirmation Modal (Alpine) -->
    <div x-show="deactivateModal"
        style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 2000; align-items: center; justify-content: center;"
        class="flex" @click.self="deactivateModal = false">
        <div style="background: #fff; max-width: 480px; width: 100%; border-radius: 16px; padding: 32px; box-shadow: 0 20px 50px rgba(0,0,0,0.15);"
            @click.stop>
            <div
                style="width: 48px; height: 48px; border-radius: 50%; background: #FEE2E2; color: #EF4444; display: flex; align-items: center; justify-content: center; margin-bottom: 20px;">
                <svg style="width: 24px; height: 24px;" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                    stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <h4 style="font-size: 18px; font-weight: 800; color: #1E293B; margin-bottom: 8px;">Deactivate Account</h4>
            <p style="font-size: 13.5px; color: #64748B; line-height: 1.5; margin-bottom: 24px;">Are you sure you want
                to deactivate your InternConnect account? This action is permanent and will withdraw all of your active
                applications.</p>
            <div style="display: flex; gap: 12px; justify-content: flex-end;">
                <button type="button"
                    style="background: #EF4444; color: #fff; font-size: 13px; font-weight: 700; padding: 10px 20px; border-radius: 8px; border: none; cursor: pointer;"
                    @click="deactivateModal = false; $dispatch('show-toast', { text: 'Account deactivated (mock action)', type: 'danger' });">Deactivate</button>
                <button type="button"
                    style="background: #F1F5F9; color: #475569; font-size: 13px; font-weight: 700; padding: 10px 20px; border-radius: 8px; border: none; cursor: pointer;"
                    @click="deactivateModal = false">Cancel</button>
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