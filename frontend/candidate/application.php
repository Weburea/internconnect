<?php
$extraCss = ['assets/css/application.css'];
$oldInput = $oldInput ?? [];

$screeningQuestions = !empty($internship['screening_questions']) && is_array($internship['screening_questions'])
    ? $internship['screening_questions']
    : [];

$relevantExpFields = !empty($internship['relevant_experience']) && is_array($internship['relevant_experience'])
    ? $internship['relevant_experience']
    : [];
?>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('applicationForm', () => ({
        step: <?= !empty($errors) ? 3 : 1 ?>, 
        coverLetter: <?= json_encode($oldInput['cover_letter'] ?? '') ?>, 
        fileUploaded: false, 
        uploadedFileName: '', 
        uploadedFileSize: '', 
        uploadedFileUrl: '', 
        uploadedFileExt: '', 
        dragOver: false, 
        clDragOver: false,
        showPreviewModal: false, 
        phone: <?= json_encode($oldInput['phone'] ?? '+234 806 071 8932') ?>,
        parsedSkills: ['PHP', 'JavaScript', 'Tailwind CSS', 'SQL', 'Alpine.js'],
        jobTitle: <?= json_encode($oldInput['job_title'] ?? '') ?>,
        company: <?= json_encode($oldInput['experience_company'] ?? '') ?>,
        salary: <?= json_encode($oldInput['salary_expectation'] ?? '') ?>,
        coverLetterFile: false,
        coverLetterFileName: '',
        clMode: 'type',
        showUploadSwitchAlert: false,
        showTypeSwitchAlert: false,
        alertOpen: false,
        alertTitle: '',
        alertMessage: '',
        resumeError: '',
        coverLetterError: '',
        portfolioError: '',
        
        interestReason: <?= json_encode($oldInput['interest_reason'] ?? '') ?>,
        designProcess: <?= json_encode($oldInput['design_process'] ?? '') ?>,
        learningGoals: <?= json_encode($oldInput['learning_goals'] ?? '') ?>,
        
        portfolioUploaded: false,
        portfolioFileName: '',
        portfolioFileSize: '',
        portfolioFileUrl: '',
        portfolioFileExt: '',
        portfolioLink: <?= json_encode($oldInput['portfolio_link'] ?? '') ?>,
        figmaLink: <?= json_encode($oldInput['figma_link'] ?? '') ?>,
        portfolioDragOver: false,
        
        wordCount(text) {
            if (!text) return 0;
            return text.trim().split(/\s+/).filter(Boolean).length;
        },
        
        showAlert(title, message) {
            this.$dispatch('toast', { message: message, type: 'warning' });
        },
        
        handleFileChange(event) {
            const file = event.target.files[0];
            if (file) {
                this.processFile(file);
            }
        },
        handleFileDrop(event) {
            this.dragOver = false;
            const file = event.dataTransfer.files[0];
            if (file) {
                this.processFile(file);
                if (this.$refs.fileInput) {
                    this.$refs.fileInput.files = event.dataTransfer.files;
                }
            }
        },
        processFile(file) {
            if (file.size > 5 * 1024 * 1024) {
                this.resumeError = 'File size exceeds the 5MB limit.';
                this.showAlert('File Size Limit Exceeded', 'The selected file exceeds the 5MB limit. Please upload a smaller file.');
                return;
            }
            const ext = file.name.split('.').pop().toLowerCase();
            if (!['pdf', 'docx', 'rtf'].includes(ext)) {
                this.resumeError = 'Unsupported file type. Please upload a PDF, DOCX, or RTF.';
                this.showAlert('Invalid File Format', 'Unsupported file type. Please upload a PDF, DOCX, or RTF document.');
                return;
            }
            this.resumeError = '';
            // Revoke any previous object URL to avoid memory leaks
            if (this.uploadedFileUrl) {
                URL.revokeObjectURL(this.uploadedFileUrl);
            }
            this.uploadedFileName = file.name;
            this.uploadedFileSize = (file.size / (1024 * 1024)).toFixed(2) + ' MB';
            this.uploadedFileExt = ext;
            this.uploadedFileUrl = URL.createObjectURL(file);
            this.fileUploaded = true;
        },
        removeResume() {
            if (this.uploadedFileUrl) {
                URL.revokeObjectURL(this.uploadedFileUrl);
            }
            this.fileUploaded = false;
            this.uploadedFileName = '';
            this.uploadedFileSize = '';
            this.uploadedFileUrl = '';
            this.uploadedFileExt = '';
            this.resumeError = '';
            if (this.$refs.fileInput) {
                this.$refs.fileInput.value = '';
            }
        },
        handleCoverLetterFile(event) {
            const file = event.target.files[0];
            if (file) {
                this.processCoverLetterFile(file);
            }
        },
        handleCoverLetterDrop(event) {
            this.clDragOver = false;
            const file = event.dataTransfer.files[0];
            if (file) {
                this.processCoverLetterFile(file);
                if (this.$refs.clFileInput) {
                    this.$refs.clFileInput.files = event.dataTransfer.files;
                }
            }
        },
        processCoverLetterFile(file) {
            const ext = file.name.split('.').pop().toLowerCase();
            if (!['pdf','docx','rtf'].includes(ext)) {
                this.coverLetterError = 'Unsupported type. Please upload a PDF, DOCX, or RTF.';
                this.showAlert('Invalid File Format', 'Unsupported file type. Please upload a PDF, DOCX, or RTF document.');
                return;
            }
            if (file.size > 5 * 1024 * 1024) {
                this.coverLetterError = 'File size exceeds the 5MB limit.';
                this.showAlert('File Size Limit Exceeded', 'The selected file exceeds the 5MB limit. Please upload a smaller file.');
                return;
            }
            this.coverLetterError = '';
            this.coverLetterFile = true;
            this.coverLetterFileName = file.name;
        },
        removeCoverLetterFile() {
            this.coverLetterFile = false;
            this.coverLetterFileName = '';
            this.coverLetterError = '';
            if (this.$refs.clFileInput) this.$refs.clFileInput.value = '';
        },
        switchMode(mode) {
            if (mode === 'upload') {
                if (this.coverLetter && this.coverLetter.trim().length > 0) {
                    this.showUploadSwitchAlert = true;
                    this.showTypeSwitchAlert = false;
                } else {
                    this.clMode = 'upload';
                    this.showUploadSwitchAlert = false;
                    this.showTypeSwitchAlert = false;
                }
            } else if (mode === 'type') {
                if (this.coverLetterFile) {
                    this.showTypeSwitchAlert = true;
                    this.showUploadSwitchAlert = false;
                } else {
                    this.clMode = 'type';
                    this.showTypeSwitchAlert = false;
                    this.showUploadSwitchAlert = false;
                }
            }
        },
        confirmSwitchToUpload() {
            this.coverLetter = '';
            this.clMode = 'upload';
            this.showUploadSwitchAlert = false;
        },
        confirmSwitchToType() {
            this.removeCoverLetterFile();
            this.clMode = 'type';
            this.showTypeSwitchAlert = false;
        },
        isCoverLetterValid() {
            if (this.clMode === 'type') {
                return this.coverLetter.length >= 100 && this.coverLetter.length <= 2000;
            } else {
                return this.coverLetterFile === true;
            }
        },
        handlePortfolioFile(event) {
            const file = event.target.files[0];
            if (file) {
                this.processPortfolioFile(file);
            }
        },
        handlePortfolioDrop(event) {
            this.portfolioDragOver = false;
            const file = event.dataTransfer.files[0];
            if (file) {
                this.processPortfolioFile(file);
                if (this.$refs.portfolioFileInput) {
                    this.$refs.portfolioFileInput.files = event.dataTransfer.files;
                }
            }
        },
        processPortfolioFile(file) {
            if (file.size > 10 * 1024 * 1024) {
                this.portfolioError = 'File size exceeds the 10MB limit.';
                this.showAlert('File Size Limit Exceeded', 'The selected file exceeds the 10MB limit. Please upload a smaller file.');
                return;
            }
            const ext = file.name.split('.').pop().toLowerCase();
            if (!['pdf', 'png', 'jpg', 'jpeg'].includes(ext)) {
                this.portfolioError = 'Unsupported file type. Please upload a PDF, PNG, or JPG.';
                this.showAlert('Invalid File Format', 'Unsupported file type. Please upload a PDF, PNG, or JPG document.');
                return;
            }
            this.portfolioError = '';
            if (this.portfolioFileUrl) {
                URL.revokeObjectURL(this.portfolioFileUrl);
            }
            this.portfolioFileName = file.name;
            this.portfolioFileSize = (file.size / (1024 * 1024)).toFixed(2) + ' MB';
            this.portfolioFileExt = ext;
            this.portfolioFileUrl = URL.createObjectURL(file);
            this.portfolioUploaded = true;
        },
        removePortfolio() {
            if (this.portfolioFileUrl) {
                URL.revokeObjectURL(this.portfolioFileUrl);
            }
            this.portfolioUploaded = false;
            this.portfolioFileName = '';
            this.portfolioFileSize = '';
            this.portfolioFileUrl = '';
            this.portfolioFileExt = '';
            this.portfolioError = '';
            if (this.$refs.portfolioFileInput) {
                this.$refs.portfolioFileInput.value = '';
            }
        },
        init() {
            this.$watch('salary', (value) => {
                if (!value) return;
                let clean = value.replace(/[^\d]/g, '');
                if (clean) {
                    let formatted = Number(clean).toLocaleString('en-US');
                    if (value !== formatted) {
                        this.salary = formatted;
                    }
                } else {
                    this.salary = '';
                }
            });
        },
        canAccessStep(targetStep) {
            if (targetStep === 1) return true;
            
            const isStep1Valid = this.phone && this.phone.trim() !== '' &&
                                 this.jobTitle && this.jobTitle.trim() !== '' &&
                                 this.company && this.company.trim() !== '' &&
                                 this.salary && this.salary.trim() !== '';
                                 
            if (targetStep === 2) return isStep1Valid;
            
            const isStep2Valid = isStep1Valid && this.fileUploaded && this.isCoverLetterValid();
            if (targetStep === 3) return isStep2Valid;
            
            const isStep3Valid = isStep2Valid && 
                                 this.interestReason && this.interestReason.trim() !== '' &&
                                 this.designProcess && this.designProcess.trim() !== '' &&
                                 this.learningGoals && this.learningGoals.trim() !== '';
            if (targetStep === 4) return isStep3Valid;
            
            return false;
        },
        goToStep(targetStep) {
            if (this.canAccessStep(targetStep)) {
                this.step = targetStep;
            } else {
                this.showAlert('Step Incomplete', 'You need to complete the step-by-step process sequentially.');
            }
        }
    }));
});
</script>

<div x-data="applicationForm" class="min-h-screen bg-[#F8FAFC] flex flex-col font-sans">
    
    <div class="flex-grow pt-16 pb-16">
        <div class="container mx-auto px-4 max-w-7xl">
            
            <div class="flex flex-col lg:flex-row gap-8 xl:gap-12 items-start">
                
                <!-- ════════════ Left Sidebar: Progress & Info ════════════ -->
                <div class="lg:w-1/3 w-full flex flex-col gap-4 lg:sticky lg:top-24">

                    <!-- ── Applying For Card (top of sidebar) ── -->
                    <div class="bg-white rounded-2xl border border-brandBorder shadow-sm overflow-hidden">
                        <div class="p-5">
                            <p class="text-[10px] font-bold uppercase tracking-widest text-primary mb-3">Application Process</p>
                            <h2 class="text-xl font-extrabold text-primary-text leading-tight mb-1">
                                Take the next step in your <span class="text-primary">career journey.</span>
                            </h2>
                            <p class="text-sm text-gray-500 leading-relaxed mt-3">
                                Apply to the <strong class="text-primary-text"><?= htmlspecialchars($internship['title'] ?? 'Internship') ?></strong> at
                                <strong class="text-primary-text"><?= htmlspecialchars($internship['company'] ?? 'Company') ?></strong>.
                                Your profile will be shared directly with the hiring committee.
                            </p>
                            <!-- Status row -->
                            <div class="flex items-center gap-2 mt-4 pt-4 border-t border-gray-100">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-500 animate-pulse inline-block"></span>
                                    Applications Open
                                </span>
                                <span class="text-[10px] text-gray-400">· Reviewed on a rolling basis</span>
                            </div>
                        </div>
                    </div>
                    <!-- ── Progress Card ── -->
                    <div class="bg-[#F4F7FB] rounded-2xl p-8 border border-gray-100 shadow-sm">
                        <p class="text-primary font-bold text-lg mb-1">Application Progress</p>
                        <p class="text-gray-500 text-sm mb-10" x-text="'Step ' + step + ' of 4'"></p>

                        <!-- Stepper Container -->
                        <div class="flex flex-col relative space-y-8 mb-12">
                            <!-- Step 1 -->
                            <div class="flex items-center gap-5 relative z-10 p-2 rounded-2xl transition-all duration-300 cursor-pointer" 
                                 @click="goToStep(1)"
                                 :class="{ 'bg-white shadow-sm border border-gray-100': step === 1, 'opacity-70 hover:opacity-100': step !== 1 }">
                                <div class="relative flex-shrink-0 w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm transition-all duration-300"
                                     :class="step > 1 ? 'river-flow-bg text-white shadow-md' : (step === 1 ? 'river-flow-bg text-white shadow-lg ring-4 ring-[#EEF2FF]' : 'bg-white border border-gray-200 text-gray-400')">
                                    <template x-if="step > 1">
                                        <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </template>
                                    <template x-if="step === 1">
                                        <span>1</span>
                                    </template>
                                    <!-- Segment connector to next step -->
                                    <div class="stepper-connector"
                                         :class="step > 1 ? 'river-flow-bg' : 'bg-gray-200'"></div>
                                </div>
                                <div>
                                    <p class="text-[10px] font-bold uppercase tracking-wider mb-0.5"
                                       :class="step === 1 ? 'text-primary' : 'text-gray-400'">Contact Details</p>
                                    <h3 class="font-bold text-sm transition-colors" :class="step === 1 ? 'text-primary-text' : 'text-gray-500'">Sarah Peters</h3>
                                </div>
                            </div>
                            
                            <!-- Step 2 -->
                            <div class="flex items-center gap-5 relative z-10 p-2 rounded-2xl transition-all duration-300 cursor-pointer" 
                                 @click="goToStep(2)"
                                 :class="{ 'bg-white shadow-sm border border-gray-100': step === 2, 'opacity-70 hover:opacity-100': step !== 2 }">
                                <div class="relative flex-shrink-0 w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm transition-all duration-300"
                                     :class="step > 2 ? 'river-flow-bg text-white shadow-md' : (step === 2 ? 'river-flow-bg text-white shadow-lg ring-4 ring-[#EEF2FF]' : 'bg-white border border-gray-200 text-gray-400')">
                                    <template x-if="step > 2">
                                        <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </template>
                                    <template x-if="step <= 2">
                                        <span>2</span>
                                    </template>
                                    <!-- Segment connector to next step -->
                                    <div class="stepper-connector"
                                         :class="step > 2 ? 'river-flow-bg' : 'bg-gray-200'"></div>
                                </div>
                                <div>
                                    <p class="text-[10px] font-bold uppercase tracking-wider mb-0.5"
                                       :class="step === 2 ? 'text-primary' : 'text-gray-400'">Resume / CV</p>
                                    <h3 class="font-bold text-sm transition-colors" :class="step === 2 ? 'text-primary-text' : 'text-gray-500'">
                                        <span x-show="fileUploaded" x-text="uploadedFileName.length > 15 ? uploadedFileName.slice(0, 15) + '...' : uploadedFileName"></span>
                                        <span x-show="!fileUploaded">Upload PDF/DOCX</span>
                                    </h3>
                                </div>
                            </div>

                            <!-- Step 3 -->
                            <div class="flex items-center gap-5 relative z-10 p-2 rounded-2xl transition-all duration-300 cursor-pointer" 
                                 @click="goToStep(3)"
                                 :class="{ 'bg-white shadow-sm border border-gray-100': step === 3, 'opacity-70 hover:opacity-100': step !== 3 }">
                                <div class="relative flex-shrink-0 w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm transition-all duration-300"
                                     :class="step > 3 ? 'river-flow-bg text-white shadow-md' : (step === 3 ? 'river-flow-bg text-white shadow-lg ring-4 ring-[#EEF2FF]' : 'bg-white border border-gray-200 text-gray-400')">
                                    <template x-if="step > 3">
                                        <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </template>
                                    <template x-if="step <= 3">
                                        <span>3</span>
                                    </template>
                                    <!-- Segment connector to next step -->
                                    <div class="stepper-connector"
                                         :class="step > 3 ? 'river-flow-bg' : 'bg-gray-200'"></div>
                                </div>
                                <div>
                                    <p class="text-[10px] font-bold uppercase tracking-wider mb-0.5"
                                       :class="step === 3 ? 'text-primary' : 'text-gray-400'">Questions</p>
                                    <h3 class="font-bold text-sm transition-colors" :class="step === 3 ? 'text-primary-text' : 'text-gray-500'">A few questions from <?= htmlspecialchars($internship['company'] ?? 'Nexora') ?></h3>
                                </div>
                            </div>

                            <!-- Step 4 -->
                            <div class="flex items-center gap-5 relative z-10 p-2 rounded-2xl transition-all duration-300 cursor-pointer" 
                                 @click="goToStep(4)"
                                 :class="{ 'bg-white shadow-sm border border-gray-100': step === 4, 'opacity-70 hover:opacity-100': step !== 4 }">
                                <div class="flex-shrink-0 w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm transition-all duration-300"
                                     :class="step === 4 ? 'river-flow-bg text-white shadow-lg ring-4 ring-[#EEF2FF]' : 'bg-white border border-gray-200 text-gray-400'">
                                    <span>4</span>
                                </div>
                                <div>
                                    <p class="text-[10px] font-bold uppercase tracking-wider mb-0.5"
                                       :class="step === 4 ? 'text-primary' : 'text-gray-400'">Preview</p>
                                    <h3 class="font-bold text-sm transition-colors" :class="step === 4 ? 'text-primary-text' : 'text-gray-500'">See what your application looks like</h3>
                                </div>
                            </div>
                        </div>

                        <!-- Pro Tip Card -->
                        <div class="bg-primary-light border border-primary/20 rounded-xl p-5 mb-8 shadow-sm">
                            <p class="text-sm text-primary-text leading-relaxed font-medium italic mb-4">
                                "InternConnect applicants are 3x more likely to receive an interview request thanks to our curated application flow."
                            </p>
                            <div class="flex items-center gap-2 text-primary font-bold text-xs uppercase tracking-wider">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                                </svg>
                                Pro Tip
                            </div>
                        </div>

                        <button type="button" class="w-full flex items-center justify-center space-x-2 border border-gray-300 text-gray-700 hover:bg-gray-50 text-sm font-semibold py-3 px-6 rounded-xl transition active:scale-95 shadow-sm">
                            <svg class="h-4 w-4 text-gray-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                            </svg>
                            <span>Save Progress</span>
                        </button>
                    </div>
                </div>


                <!-- ════════════ Right Content: Form Container ════════════ -->
                <div class="lg:w-2/3 w-full">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8 lg:p-12 relative overflow-hidden min-h-[550px]">
                        <form action="<?= url('/apply/' . htmlspecialchars($internship['id'] ?? $slug ?? 'software-engineer-intern')) ?>" method="POST" enctype="multipart/form-data" class="relative w-full">
                            <!-- CSRF Token (placeholder — hook into session token when auth is implemented) -->
                            <input type="hidden" name="_csrf_token" value="<?= htmlspecialchars(bin2hex(random_bytes(16))) ?>">
                            <input type="hidden" name="slug" value="<?= htmlspecialchars($internship['id'] ?? $slug ?? 'software-engineer-intern') ?>">
                            
                            <?php if (!empty($errors)): ?>
                                <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl flex items-start gap-3">
                                    <svg class="h-5 w-5 text-red-500 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                    </svg>
                                    <div>
                                        <h4 class="font-bold text-sm text-red-800 mb-1">Please correct the following errors:</h4>
                                        <ul class="list-disc pl-4 text-xs text-red-700 space-y-1">
                                            <?php foreach ($errors as $error): ?>
                                                <li><?= htmlspecialchars($error) ?></li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                </div>
                            <?php endif; ?>
                            
                            <!-- ================= STEP 1: CONTACT INFO ================= -->
                            <div x-show="step === 1" 
                                 x-transition:enter="transition ease-out duration-300 transform delay-100"
                                 x-transition:enter-start="opacity-0 translate-x-8"
                                 x-transition:enter-end="opacity-100 translate-x-0"
                                 x-transition:leave="transition ease-in duration-200 transform absolute top-0 left-0 right-0 w-full"
                                 x-transition:leave-start="opacity-100 translate-x-0"
                                 x-transition:leave-end="opacity-0 -translate-x-8"
                                 class="w-full pb-4">
                                
                                <h2 class="text-3xl font-bold text-primary-text mb-2">Personal Details</h2>
                                <p class="text-gray-500 mb-10 text-base">Please review your contact information below.</p>
                                
                                <div class="space-y-6">
                                    <div>
                                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Full Name</label>
                                        <input type="text" name="full_name" class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary focus:bg-white transition-colors font-medium text-primary-text" value="Sarah Peters" readonly>
                                    </div>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Email Address</label>
                                            <input type="email" name="email" class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary focus:bg-white transition-colors font-medium text-primary-text" value="sarahpeters@internconnect.com" readonly>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Phone Number</label>
                                            <input type="tel" name="phone" x-model="phone" class="w-full px-5 py-4 bg-white border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-colors font-medium text-primary-text">
                                        </div>
                                    </div>
                                </div>

                                <!-- Relevant Experience Section -->
                                <div class="mt-10 pt-8 border-t border-gray-100">
                                    <div class="mb-6">
                                        <p class="text-xs font-bold uppercase tracking-widest text-primary mb-1">Relevant Experience</p>
                                        <h3 class="text-base font-bold text-primary-text">Enter a job that shows relevant experience</h3>
                                        <p class="text-sm text-gray-500 mt-1">We share one job title with the employer to introduce you as a candidate.</p>
                                    </div>

                                    <div class="space-y-3">
                                        <!-- 1. Job Title -->
                                        <div>
                                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">
                                                1. Job Title <span class="text-red-500">*</span>
                                            </label>
                                            <input type="text" name="job_title" x-model="jobTitle"
                                                   placeholder="Frontend Developer Intern"
                                                   class="w-full px-5 py-4 bg-white border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-colors font-medium text-primary-text placeholder-gray-400">
                                        </div>

                                        <!-- 2. Company -->
                                        <div>
                                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">
                                                2. Company <span class="text-red-500">*</span>
                                            </label>
                                            <input type="text" name="experience_company" x-model="company"
                                                   placeholder="TechLabs Nigeria"
                                                   class="w-full px-5 py-4 bg-white border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-colors font-medium text-primary-text placeholder-gray-400">
                                        </div>
                                    </div>

                                    <!-- Employer Questions divider -->
                                    <div class="mt-8 mb-5 flex items-center gap-3">
                                        <span class="text-[10px] font-bold uppercase tracking-widest text-gray-400 whitespace-nowrap">Answer these questions from the employer</span>
                                        <div class="flex-grow h-px bg-gray-100"></div>
                                    </div>

                                    <!-- 3. Salary Expectation -->
                                    <div>
                                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">
                                            3. Salary Expectation ($/month) <span class="text-red-500">*</span>
                                        </label>
                                        <div class="flex items-center px-5 border border-gray-200 rounded-xl bg-white focus-within:ring-2 focus-within:ring-primary focus-within:border-transparent transition-all">
                                            <span class="text-gray-400 font-medium text-base select-none flex-shrink-0 pr-1">$</span>
                                            <input type="text" name="salary_expectation" x-model="salary"
                                                   inputmode="decimal"
                                                   placeholder="2,500"
                                                   class="flex-1 py-4 pr-5 bg-transparent focus:outline-none font-medium text-primary-text placeholder-gray-400">
                                        </div>
                                        <p class="text-xs text-gray-400 mt-2">This is shared privately with the employer to assess fit.</p>
                                    </div>
                                </div>

                                <div class="mt-12 flex justify-end">
                                    <button type="button" @click="goToStep(2)" class="bg-primary hover:bg-primary-hover text-white text-sm font-semibold py-3 px-6 rounded-xl transition active:scale-95 shadow-sm flex items-center gap-2">
                                        Next: Resume
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                                    </button>
                                </div>
                            </div>

                            <!-- ================= STEP 2: RESUME ================= -->
                            <div x-show="step === 2" style="display: none;"
                                 x-transition:enter="transition ease-out duration-300 transform delay-100"
                                 x-transition:enter-start="opacity-0 translate-x-8"
                                 x-transition:enter-end="opacity-100 translate-x-0"
                                 x-transition:leave="transition ease-in duration-200 transform absolute top-0 left-0 right-0 w-full"
                                 x-transition:leave-start="opacity-100 translate-x-0"
                                 x-transition:leave-end="opacity-0 -translate-x-8"
                                 class="w-full pb-4">
                                
                                <h2 class="text-3xl font-bold text-primary-text mb-2">Upload your Resume</h2>
                                <p class="text-gray-500 mb-10 text-base">We accept PDF, DOCX, or RTF files. Ensure your file size is under 5MB.</p>
                                
                                <!-- Hidden File Input -->
                                <input type="file" 
                                       name="resume"
                                       x-ref="fileInput" 
                                       @change="handleFileChange($event)" 
                                       class="hidden" 
                                       accept=".pdf,.docx,.rtf">

                                <!-- Drag & Drop Box -->
                                <div @click="$refs.fileInput.click()"
                                     @dragover.prevent="dragOver = true"
                                     @dragleave.prevent="dragOver = false"
                                     @drop.prevent="handleFileDrop($event)"
                                     class="border-2 border-dashed rounded-2xl p-8 lg:p-12 flex flex-col sm:flex-row items-center justify-center gap-6 text-center sm:text-left transition-all duration-300 cursor-pointer group mb-6"
                                     :class="dragOver ? 'border-primary bg-primary-light' : 'border-gray-300 bg-gray-50/50 hover:bg-gray-50 hover:border-primary/50'">
                                     
                                    <div class="h-16 w-16 bg-primary rounded-full flex items-center justify-center flex-shrink-0 shadow-lg group-hover:scale-105 transition-transform"
                                         :class="{ 'river-flow-bg': dragOver }">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-primary-text font-bold text-lg mb-1">Drag and drop your file here</p>
                                        <p class="text-sm text-gray-500">or click to browse from your computer</p>
                                    </div>
                                </div>

                                <!-- Inline Resume Error Message -->
                                <div x-show="resumeError" class="text-sm text-red-600 flex items-center gap-2 mb-6 bg-red-50 border border-red-200 rounded-xl p-3" style="display: none;">
                                    <svg class="h-5 w-5 text-red-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                    </svg>
                                    <span x-text="resumeError"></span>
                                </div>

                                <!-- Uploaded Files Box -->
                                <div x-show="fileUploaded" class="mb-6">
                                    <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">Uploaded File</p>
                                    <div class="border border-gray-200 rounded-xl p-4 sm:p-5 flex flex-col sm:flex-row gap-4 sm:gap-5 items-start sm:items-center justify-between bg-[#F8FAFC] shadow-sm w-full box-border overflow-hidden">
                                        <div class="flex items-center gap-3 sm:gap-4 min-w-0 flex-1 overflow-hidden">
                                            <!-- Visual file icon -->
                                            <div class="h-12 w-10 sm:h-14 sm:w-11 bg-white border border-gray-300 rounded-lg shadow-sm flex-shrink-0 relative overflow-hidden flex flex-col p-1 justify-between">
                                                <div class="absolute right-0 top-0 h-3.5 w-3.5 bg-red-500 text-white font-bold text-[6px] flex items-center justify-center rounded-bl uppercase" x-text="uploadedFileExt ? uploadedFileExt.toUpperCase() : 'PDF'">PDF</div>
                                                <div class="space-y-1 mt-2.5">
                                                    <div class="h-[1.5px] bg-gray-300 w-full rounded"></div>
                                                    <div class="h-[1.5px] bg-gray-300 w-5/6 rounded"></div>
                                                    <div class="h-[1.5px] bg-gray-300 w-4/5 rounded"></div>
                                                </div>
                                                <div class="h-[1.5px] bg-primary w-1/2 rounded"></div>
                                            </div>
                                            <div class="min-w-0 flex-1 overflow-hidden">
                                                <p class="text-sm sm:text-base font-bold text-primary-text mb-0.5 truncate" :title="uploadedFileName" x-text="uploadedFileName"></p>
                                                <p class="text-xs sm:text-sm text-gray-500" x-text="uploadedFileSize + ' • Uploaded just now'"></p>
                                                <!-- Status badges -->
                                                <div class="flex flex-wrap items-center gap-2 mt-1.5">
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200" style="white-space: nowrap !important; flex-shrink: 0 !important;">Ready for review</span>
                                                    <span class="inline-flex items-center gap-1 text-[10px] text-gray-400" style="white-space: nowrap !important; flex-shrink: 0 !important;">
                                                        <svg class="w-3 h-3 text-emerald-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                                        </svg>
                                                        Parsed by InternConnect AI
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Actions -->
                                        <div class="flex items-center gap-3 w-full sm:w-auto justify-end flex-shrink-0 mt-3 sm:mt-0 pt-3 sm:pt-0 border-t sm:border-t-0 border-gray-200">
                                            <button type="button" @click="showPreviewModal = true"
                                                    class="flex-1 sm:flex-initial border border-gray-300 bg-white text-gray-700 hover:bg-gray-50 text-xs font-semibold py-2.5 px-4 sm:px-4.5 rounded-lg transition active:scale-95 inline-flex items-center justify-center gap-2 shadow-sm"
                                                    style="white-space: nowrap !important; flex-shrink: 0 !important;">
                                                <svg class="h-4 w-4 flex-shrink-0 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zm6 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                <span>View File</span>
                                            </button>
                                            <button type="button" @click="removeResume()"
                                                    class="flex-1 sm:flex-initial border border-red-200 bg-white text-red-600 hover:bg-red-50 text-xs font-semibold py-2.5 px-4 sm:px-4.5 rounded-lg transition active:scale-95 inline-flex items-center justify-center gap-2 shadow-sm"
                                                    style="white-space: nowrap !important; flex-shrink: 0 !important;">
                                                <svg class="h-4 w-4 flex-shrink-0 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                                <span>Delete</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Live Preview Panel inside page -->
                                <div x-show="fileUploaded" class="mt-6 border border-gray-200 rounded-2xl bg-white p-4 sm:p-6 shadow-sm">
                                    <div class="flex items-center justify-between border-b border-gray-100 pb-3 mb-4">
                                        <div class="flex items-center gap-2">
                                            <div class="w-2.5 h-2.5 rounded-full bg-green-500 animate-pulse"></div>
                                            <h4 class="font-bold text-xs text-primary-text uppercase tracking-wider">Document Visualizer</h4>
                                        </div>
                                        <span class="text-[10px] text-gray-400 hidden sm:inline"
                                              x-text="uploadedFileExt === 'pdf' ? 'Live PDF Preview' : 'Document Preview'"></span>
                                    </div>

                                    <!-- PDF: single-scrollbar preview using pdf-clip trick -->
                                    <div x-show="uploadedFileExt === 'pdf'"
                                         class="pdf-clip rounded-xl shadow-inner"
                                         style="height: clamp(260px, 50vh, 520px);">
                                        <iframe
                                            :src="uploadedFileExt === 'pdf' ? (uploadedFileUrl + '#toolbar=0&navpanes=0&scrollbar=1') : ''"
                                            style="height: 100%; min-height: clamp(260px, 50vh, 520px);"
                                            title="Resume Preview"
                                        ></iframe>
                                    </div>

                                    <!-- DOCX / RTF: styled placeholder -->
                                    <div x-show="uploadedFileExt !== 'pdf'"
                                         class="rounded-xl border border-gray-100 bg-gray-50/60 shadow-inner flex flex-col items-center justify-center gap-4 px-4 py-8 sm:py-0"
                                         style="min-height: 220px;">
                                        <div class="flex items-center justify-center h-14 w-14 rounded-2xl bg-indigo-50 border border-indigo-100">
                                            <svg class="h-7 w-7 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                            </svg>
                                        </div>
                                        <div class="text-center">
                                            <p class="font-semibold text-primary-text text-sm mb-1 truncate max-w-[260px]" x-text="uploadedFileName"></p>
                                            <p class="text-xs text-gray-500 mb-2" x-text="uploadedFileSize + ' · Ready for review'"></p>
                                            <p class="text-[11px] text-gray-400 leading-relaxed">Live preview is only available for PDF files.<br>Your document is loaded and will be submitted.</p>
                                        </div>
                                        <a :href="uploadedFileUrl" :download="uploadedFileName"
                                           class="inline-flex items-center gap-1.5 text-xs font-semibold text-primary border border-indigo-200 bg-indigo-50 hover:bg-indigo-100 px-4 py-2 rounded-lg transition">
                                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                                            Download to verify
                                        </a>
                                    </div>
                                </div>


                                <!-- ================= COVER LETTER (Now in Step 2) ================= -->
                                <div class="mt-12 pt-8 border-t border-gray-100">
                                    <h2 class="text-3xl font-bold text-primary-text mb-2">Statement of Intent / Cover Letter</h2>
                                    <p class="text-gray-500 mb-8 text-base">Explain your motivation for applying and how your skills align with the internship requirements.</p>

                                    <!-- Toggle: Type vs Upload -->
                                    <div class="mb-8">
                                        <div class="flex sm:inline-flex bg-gray-100 rounded-xl p-1 gap-1 mb-8 w-full sm:w-auto">
                                            <button type="button" @click="switchMode('type')"
                                                    :class="clMode === 'type' ? 'bg-white shadow-sm text-primary font-bold' : 'text-gray-500 hover:text-gray-700'"
                                                    class="flex-1 sm:flex-none px-4 sm:px-5 py-2.5 rounded-lg text-sm font-semibold transition-all duration-200 flex items-center justify-center gap-2">
                                                <svg class="h-4 w-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                                <span style="white-space: nowrap;">Type Letter</span>
                                            </button>
                                            <button type="button" @click="switchMode('upload')"
                                                    :class="clMode === 'upload' ? 'bg-white shadow-sm text-primary font-bold' : 'text-gray-500 hover:text-gray-700'"
                                                    class="flex-1 sm:flex-none px-4 sm:px-5 py-2.5 rounded-lg text-sm font-semibold transition-all duration-200 flex items-center justify-center gap-2">
                                                <svg class="h-4 w-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                                <span style="white-space: nowrap;">Upload File</span>
                                            </button>
                                        </div>

                                        <!-- Alert Box for switching to Upload mode while text is typed -->
                                        <div x-show="showUploadSwitchAlert" x-transition class="bg-[#EEF2FF] border border-[#4F47E6]/25 rounded-2xl p-5 mb-6" style="display: none;">
                                            <div class="flex gap-4 items-start">
                                                <div class="h-10 w-10 bg-white border border-[#4F47E6]/20 rounded-xl flex items-center justify-center flex-shrink-0 shadow-sm">
                                                    <svg class="h-5 w-5 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                </div>
                                                <div class="flex-1">
                                                    <h4 class="text-sm font-bold text-primary mb-1">Clear your typed statement?</h4>
                                                    <p class="text-xs text-gray-600 leading-relaxed mb-4">You have already started typing a statement of intent. Switching to file upload requires clearing your current text so that you can upload a file.</p>
                                                    <div class="flex gap-2">
                                                        <button type="button" @click="confirmSwitchToUpload()" class="bg-primary hover:bg-primary-hover text-white text-xs font-bold px-4 py-2.5 rounded-xl shadow-sm transition active:scale-95" style="background-color: #4F47E6;">
                                                            Clear & Switch
                                                        </button>
                                                        <button type="button" @click="showUploadSwitchAlert = false" class="bg-white border border-gray-200 text-gray-600 hover:bg-gray-50 text-xs font-bold px-4 py-2.5 rounded-xl shadow-sm transition active:scale-95">
                                                            Cancel
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Alert Box for switching to Type mode while a file is uploaded -->
                                        <div x-show="showTypeSwitchAlert" x-transition class="bg-[#EEF2FF] border border-[#4F47E6]/25 rounded-2xl p-5 mb-6" style="display: none;">
                                            <div class="flex gap-4 items-start">
                                                <div class="h-10 w-10 bg-white border border-[#4F47E6]/20 rounded-xl flex items-center justify-center flex-shrink-0 shadow-sm">
                                                    <svg class="h-5 w-5 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                </div>
                                                <div class="flex-1">
                                                    <h4 class="text-sm font-bold text-primary mb-1">Remove uploaded file?</h4>
                                                    <p class="text-xs text-gray-600 leading-relaxed mb-4">You have already uploaded a cover letter file. To type a statement of intent instead, you must remove the uploaded file first.</p>
                                                    <div class="flex gap-2">
                                                        <button type="button" @click="confirmSwitchToType()" class="bg-primary hover:bg-primary-hover text-white text-xs font-bold px-4 py-2.5 rounded-xl shadow-sm transition active:scale-95" style="background-color: #4F47E6;">
                                                            Remove & Switch
                                                        </button>
                                                        <button type="button" @click="showTypeSwitchAlert = false" class="bg-white border border-gray-200 text-gray-600 hover:bg-gray-50 text-xs font-bold px-4 py-2.5 rounded-xl shadow-sm transition active:scale-95">
                                                            Cancel
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Type mode -->
                                        <div x-show="clMode === 'type'" x-transition style="margin-top: 24px;">
                                            <label class="block text-xs font-bold text-gray-900 uppercase tracking-wider mb-3">Statement of Intent</label>
                                            <textarea name="cover_letter" x-model="coverLetter" rows="12"
                                                      class="w-full px-6 py-5 bg-gray-50 border border-gray-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-primary focus:bg-white focus:border-transparent transition-colors resize-none text-gray-700 placeholder-gray-400 text-base leading-relaxed shadow-sm"
                                                      placeholder="Type or paste your cover letter here..."></textarea>
                                            <div x-show="coverLetter.length > 0 && coverLetter.length < 100" class="text-xs text-amber-600 font-semibold mt-2" style="display: none;">
                                                Your statement is too short. It must be at least 100 characters (currently <span x-text="coverLetter.length"></span>).
                                            </div>
                                            <div x-show="coverLetter.length > 2000" class="text-xs text-red-500 font-semibold mt-2" style="display: none;">
                                                Your statement exceeds the 2,000 character limit.
                                            </div>
                                            <div class="flex items-center justify-between mt-4 px-1">
                                                <span class="text-sm text-gray-500 font-medium">Professional tone recommended.</span>
                                                <span class="text-sm font-bold" :class="(coverLetter.length > 2000 || (coverLetter.length > 0 && coverLetter.length < 100)) ? 'text-red-500' : 'text-gray-900'" x-text="coverLetter.length + ' / 2,000 characters'"></span>
                                            </div>
                                        </div>

                                        <!-- Upload mode -->
                                        <div x-show="clMode === 'upload'" x-transition style="display:none; margin-top: 24px;">
                                            <input type="file" name="cover_letter_file" x-ref="clFileInput"
                                                   @change="handleCoverLetterFile($event)"
                                                   class="hidden" accept=".pdf,.docx,.rtf">

                                            <!-- Drop zone (hidden once file uploaded) -->
                                            <div x-show="!coverLetterFile"
                                                 @click="$refs.clFileInput.click()"
                                                 @dragover.prevent="clDragOver = true"
                                                 @dragleave.prevent="clDragOver = false"
                                                 @drop.prevent="handleCoverLetterDrop($event)"
                                                 :class="clDragOver ? 'border-primary bg-primary-light' : 'border-gray-300 bg-gray-50/50 hover:bg-gray-50 hover:border-primary/50'"
                                                 class="border-2 border-dashed rounded-2xl p-8 lg:p-12 flex flex-col sm:flex-row items-center justify-center gap-6 text-center sm:text-left transition-all duration-300 cursor-pointer group mb-6">
                                                <div class="h-16 w-16 bg-primary rounded-full flex items-center justify-center flex-shrink-0 shadow-lg group-hover:scale-105 transition-transform" :class="{ 'river-flow-bg': clDragOver }">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" /></svg>
                                                </div>
                                                <p class="font-bold text-primary-text mb-1">Upload your cover letter</p>
                                                <p class="text-sm text-gray-500">PDF, DOCX or RTF · Max 5MB</p>
                                            </div>

                                            <!-- Inline Cover Letter Error Message -->
                                            <div x-show="coverLetterError" class="text-sm text-red-600 flex items-center gap-2 mb-6 bg-red-50 border border-red-200 rounded-xl p-3" style="display: none;">
                                                <svg class="h-5 w-5 text-red-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                                </svg>
                                                <span x-text="coverLetterError"></span>
                                            </div>

                                            <!-- Uploaded file row -->
                                            <div x-show="coverLetterFile" class="border border-gray-200 rounded-xl p-4 flex items-center justify-between bg-[#F8FAFC] shadow-sm">
                                                <div class="flex items-center gap-3 min-w-0 flex-1">
                                                    <div class="h-10 w-8 bg-white border border-gray-300 rounded-lg flex-shrink-0 flex items-center justify-center">
                                                        <span class="text-[8px] font-bold text-red-500 uppercase">PDF</span>
                                                    </div>
                                                    <div class="min-w-0 flex-1">
                                                        <p class="text-sm font-bold text-primary-text break-all" x-text="coverLetterFileName"></p>
                                                        <span class="text-[10px] font-semibold text-emerald-600">✓ Ready to submit</span>
                                                    </div>
                                                </div>
                                                <button type="button" @click="removeCoverLetterFile()"
                                                        class="text-xs text-red-500 hover:text-red-700 font-semibold border border-red-100 hover:bg-red-50 px-3 py-1.5 rounded-lg transition">
                                                    Remove
                                                </button>
                                            </div>

                                            <p class="text-xs text-gray-400 mt-4">Upload your pre-written cover letter document. If you also typed a letter above, the uploaded file takes priority.</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-8 pt-8 border-t border-gray-200 flex flex-col sm:flex-row items-center gap-3">
                                    <!-- Next: Questions — top on mobile, right on desktop -->
                                    <button type="button" 
                                            @click="step = 3"
                                            :disabled="!fileUploaded || !isCoverLetterValid()"
                                            class="w-full sm:w-auto sm:ml-auto bg-primary hover:bg-primary-hover disabled:opacity-50 disabled:cursor-not-allowed text-white text-sm font-semibold py-3 px-6 rounded-xl transition active:scale-95 shadow-sm flex items-center justify-center gap-2 order-1 sm:order-3">
                                        Next: Questions
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                                    </button>

                                    <!-- Previous — bottom on mobile, left on desktop -->
                                    <button type="button" @click="step = 1" class="w-full sm:w-auto justify-center border border-gray-300 text-gray-700 hover:bg-gray-50 text-sm font-semibold py-3 px-6 rounded-xl transition active:scale-95 flex items-center gap-2 order-3 sm:order-1 sm:mr-auto shadow-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M7.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l2.293 2.293a1 1 0 010 1.414z" clip-rule="evenodd" /></svg>
                                        Previous
                                    </button>
                                </div>
                            </div>

                            <!-- ================= STEP 3: QUESTIONS ================= -->
                            <div x-show="step === 3" style="display: none;"
                                 x-transition:enter="transition ease-out duration-300 transform delay-100"
                                 x-transition:enter-start="opacity-0 translate-x-8"
                                 x-transition:enter-end="opacity-100 translate-x-0"
                                 x-transition:leave="transition ease-in duration-200 transform absolute top-0 left-0 right-0 w-full"
                                 x-transition:leave-start="opacity-100 translate-x-0"
                                 x-transition:leave-end="opacity-0 -translate-x-8"
                                 class="w-full pb-4">
                                
                                <h2 class="text-3xl font-extrabold text-primary-text mb-2">A few questions from <?= htmlspecialchars($internship['company'] ?? 'Nexora') ?></h2>
                                <p class="text-gray-500 mb-8 text-base">These help the hiring team learn more about your motivations, unique perspective, and philosophy.</p>

                                <div class="space-y-8">
                                    <!-- Question 1 -->
                                    <div>
                                        <label class="block text-sm font-bold text-gray-900 mb-3">Why are you interested in joining <?= htmlspecialchars($internship['company'] ?? 'Nexora') ?> specifically?</label>
                                        <textarea name="interest_reason" x-model="interestReason" rows="4"
                                                  class="w-full px-5 py-4 bg-white border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all resize-none text-gray-700 placeholder-gray-400 text-sm shadow-sm"
                                                  placeholder="Share your motivation and how your values align with <?= htmlspecialchars($internship['company'] ?? 'Nexora') ?>'s mission..."></textarea>
                                        <div class="flex justify-end mt-2">
                                            <span class="text-xs text-gray-400" x-text="wordCount(interestReason) + ' / 500 words'"></span>
                                        </div>
                                    </div>

                                    <!-- Question 2 -->
                                    <div>
                                        <label class="block text-sm font-bold text-gray-900 mb-3">Describe your proudest project or process.</label>
                                        <textarea name="design_process" x-model="designProcess" rows="4"
                                                  class="w-full px-5 py-4 bg-white border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all resize-none text-gray-700 placeholder-gray-400 text-sm shadow-sm"
                                                  placeholder="What problem did you solve? What was your specific impact?"></textarea>
                                        <div class="flex justify-end mt-2">
                                            <span class="text-xs text-gray-400" x-text="wordCount(designProcess) + ' / 500 words'"></span>
                                        </div>
                                    </div>

                                    <!-- Question 3 -->
                                    <div>
                                        <label class="block text-sm font-bold text-gray-900 mb-3">What are your primary learning goals for this internship?</label>
                                        <textarea name="learning_goals" x-model="learningGoals" rows="4"
                                                  class="w-full px-5 py-4 bg-white border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all resize-none text-gray-700 placeholder-gray-400 text-sm shadow-sm"
                                                  placeholder="What skills or knowledge do you hope to acquire?"></textarea>
                                        <div class="flex justify-end mt-2">
                                            <span class="text-xs text-gray-400" x-text="wordCount(learningGoals) + ' / 300 words'"></span>
                                        </div>
                                    </div>

                                    <!-- NYSC Credentials Upload -->
                                    <div class="pt-4 border-t border-gray-100">
                                        <label class="block text-sm font-bold text-gray-900 mb-3">Attach NYSC credentials (optional)</label>
                                        
                                        <input type="file" name="portfolio_piece" x-ref="portfolioFileInput"
                                               @change="handlePortfolioFile($event)"
                                               class="hidden" accept=".pdf,.png,.jpg,.jpeg">

                                        <!-- Drop zone -->
                                        <div x-show="!portfolioUploaded"
                                             @click="$refs.portfolioFileInput.click()"
                                             @dragover.prevent="portfolioDragOver = true"
                                             @dragleave.prevent="portfolioDragOver = false"
                                             @drop.prevent="handlePortfolioDrop($event)"
                                             :class="portfolioDragOver ? 'border-primary bg-primary-light' : 'border-gray-300 bg-gray-50/50 hover:bg-gray-50 hover:border-primary/50'"
                                             class="border-2 border-dashed rounded-2xl p-8 flex flex-col items-center justify-center gap-3 text-center transition-all duration-300 cursor-pointer group">
                                            <div class="h-12 w-12 bg-primary rounded-xl flex items-center justify-center shadow-md group-hover:scale-105 transition-transform" :class="{ 'river-flow-bg': portfolioDragOver }">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="font-bold text-sm text-primary-text mb-1">Click to upload or drag and drop</p>
                                                <p class="text-xs text-gray-500">PDF, PNG or JPG · Max 10MB</p>
                                            </div>
                                        </div>

                                        <!-- Inline Portfolio Error Message -->
                                        <div x-show="portfolioError" class="text-sm text-red-600 flex items-center gap-2 mt-4 bg-red-50 border border-red-200 rounded-xl p-3" style="display: none;">
                                            <svg class="h-5 w-5 text-red-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                            </svg>
                                            <span x-text="portfolioError"></span>
                                        </div>

                                        <!-- Uploaded Portfolio File Row -->
                                        <div x-show="portfolioUploaded" class="border border-gray-200 rounded-xl p-4 flex items-center justify-between bg-[#F8FAFC] shadow-sm mt-4">
                                            <div class="flex items-center gap-3 min-w-0 flex-1">
                                                <div class="h-10 w-8 bg-white border border-gray-300 rounded-lg flex-shrink-0 flex items-center justify-center">
                                                    <span class="text-[8px] font-bold text-red-500 uppercase" x-text="portfolioFileExt"></span>
                                                </div>
                                                <div class="min-w-0 flex-1">
                                                    <p class="text-sm font-bold text-primary-text break-all" x-text="portfolioFileName"></p>
                                                    <span class="inline-flex items-center gap-1 text-[10px] font-semibold text-emerald-600">
                                                        <svg class="w-3 h-3 text-emerald-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                                        </svg>
                                                        Credential Attached
                                                    </span>
                                                </div>
                                            </div>
                                            <button type="button" @click="removePortfolio()"
                                                    class="text-xs text-red-500 hover:text-red-700 font-semibold border border-red-100 hover:bg-red-50 px-3 py-1.5 rounded-lg transition">
                                                Remove
                                            </button>
                                        </div>

                                        <!-- Portfolio Link Input -->
                                        <div class="mt-6">
                                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Link for Portfolio</label>
                                            <input type="text" name="portfolio_link" x-model="portfolioLink" placeholder="https://myportfolio.com"
                                                   class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary focus:bg-white focus:border-transparent transition-colors text-gray-700 placeholder-gray-400 text-sm shadow-sm">
                                        </div>

                                        <!-- Figma Link Input -->
                                        <div class="mt-4">
                                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Figma Link</label>
                                            <input type="text" name="figma_link" x-model="figmaLink" placeholder="https://www.figma.com/file/..."
                                                   class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary focus:bg-white focus:border-transparent transition-colors text-gray-700 placeholder-gray-400 text-sm shadow-sm">
                                        </div>

                                        <?php 
                                        $customQuestions = array_filter($screeningQuestions ?? [], function($q) {
                                            $def = ['Why are you interested in joining', 'Describe your proudest project', 'What are your primary learning goals', 'Attach NYSC credentials', 'Link for Portfolio', 'Figma Link'];
                                            foreach ($def as $d) {
                                                if (stripos($q['question'] ?? '', $d) !== false) return false;
                                            }
                                            return true;
                                        });
                                        ?>
                                        <?php if (!empty($customQuestions)): ?>
                                            <div class="mt-8 pt-6 border-t border-gray-100 space-y-6">
                                                <h4 class="text-xs font-bold uppercase tracking-wider text-primary">Additional Questions from Employer</h4>
                                                <?php foreach ($customQuestions as $cqIdx => $cq): ?>
                                                    <div>
                                                        <label class="block text-sm font-bold text-gray-900 mb-2">
                                                            <?= htmlspecialchars($cq['question'] ?? '') ?>
                                                            <?php if (!empty($cq['required'])): ?><span class="text-red-500">*</span><?php endif; ?>
                                                        </label>
                                                        <?php if (($cq['type'] ?? '') === 'long_answer'): ?>
                                                            <textarea name="custom_q_<?= $cq['id'] ?? $cqIdx ?>" rows="3"
                                                                      placeholder="<?= htmlspecialchars($cq['placeholder'] ?? 'Your answer...') ?>"
                                                                      class="w-full px-5 py-3 bg-white border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent text-gray-700 placeholder-gray-400 text-sm shadow-sm resize-none"></textarea>
                                                        <?php elseif (($cq['type'] ?? '') === 'yes_no'): ?>
                                                            <div class="flex items-center gap-4">
                                                                <label class="inline-flex items-center gap-2 cursor-pointer text-sm font-semibold text-gray-700">
                                                                    <input type="radio" name="custom_q_<?= $cq['id'] ?? $cqIdx ?>" value="yes" class="accent-primary"> Yes
                                                                </label>
                                                                <label class="inline-flex items-center gap-2 cursor-pointer text-sm font-semibold text-gray-700">
                                                                    <input type="radio" name="custom_q_<?= $cq['id'] ?? $cqIdx ?>" value="no" class="accent-primary"> No
                                                                </label>
                                                            </div>
                                                        <?php elseif (($cq['type'] ?? '') === 'link'): ?>
                                                            <input type="url" name="custom_q_<?= $cq['id'] ?? $cqIdx ?>"
                                                                   placeholder="<?= htmlspecialchars($cq['placeholder'] ?? 'https://...') ?>"
                                                                   class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary focus:bg-white text-gray-700 placeholder-gray-400 text-sm shadow-sm">
                                                        <?php else: ?>
                                                            <input type="text" name="custom_q_<?= $cq['id'] ?? $cqIdx ?>"
                                                                   placeholder="<?= htmlspecialchars($cq['placeholder'] ?? 'Your answer...') ?>"
                                                                   class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary text-gray-700 placeholder-gray-400 text-sm shadow-sm">
                                                        <?php endif; ?>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <!-- Step 3 Buttons -->
                                <div class="mt-12 pt-8 border-t border-gray-100 flex flex-col sm:flex-row items-center justify-between gap-3">
                                    <button type="button" @click="step = 2" class="w-full sm:w-auto justify-center border border-gray-300 text-gray-700 hover:bg-gray-50 text-sm font-semibold py-3 px-6 rounded-xl transition active:scale-95 flex items-center gap-2 shadow-sm order-2 sm:order-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M7.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l2.293 2.293a1 1 0 010 1.414z" clip-rule="evenodd" /></svg>
                                        Previous
                                    </button>
                                    <button type="button" @click="goToStep(4)" class="w-full sm:w-auto justify-center bg-primary hover:bg-primary-hover text-white text-sm font-semibold py-3 px-6 rounded-xl transition active:scale-95 shadow-sm flex items-center gap-2 order-1 sm:order-2">
                                        Preview Application
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                                    </button>
                                </div>
                            </div>

                            <!-- ================= STEP 4: PREVIEW / REVIEW ================= -->
                            <div x-show="step === 4" style="display: none;"
                                 x-transition:enter="transition ease-out duration-300 transform delay-100"
                                 x-transition:enter-start="opacity-0 translate-x-8"
                                 x-transition:enter-end="opacity-100 translate-x-0"
                                 x-transition:leave="transition ease-in duration-200 transform absolute top-0 left-0 right-0 w-full"
                                 x-transition:leave-start="opacity-100 translate-x-0"
                                 x-transition:leave-end="opacity-0 -translate-x-8"
                                 class="w-full pb-4">
                                
                                <h2 class="text-3xl font-extrabold text-primary-text mb-2">Review your application</h2>
                                <p class="text-gray-500 mb-8 text-base">Everything looks good? Submit when you're ready.</p>

                                <div class="border border-gray-200 rounded-2xl bg-white shadow-sm flex flex-col divide-y divide-gray-100 overflow-hidden">
                                    <!-- Section 1: Your Details -->
                                    <div class="p-6 sm:p-8">
                                        <div class="flex items-center justify-between mb-6">
                                            <div class="flex items-center gap-2.5 text-primary">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                </svg>
                                                <h3 class="font-bold text-base text-primary-text">Your Details</h3>
                                            </div>
                                            <button type="button" @click="step = 1" class="text-xs font-semibold text-primary hover:underline flex items-center gap-1">
                                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                                                Edit
                                            </button>
                                        </div>

                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-y-4 gap-x-8">
                                            <div>
                                                <span class="block text-xs text-gray-400 font-bold uppercase tracking-wider mb-1">Full Name</span>
                                                <span class="text-sm font-semibold text-primary-text">Sarah Peters</span>
                                            </div>
                                            <div>
                                                <span class="block text-xs text-gray-400 font-bold uppercase tracking-wider mb-1">Phone Number</span>
                                                <span class="text-sm font-semibold text-primary-text" x-text="phone || 'Not provided'"></span>
                                            </div>
                                            <div>
                                                <span class="block text-xs text-gray-400 font-bold uppercase tracking-wider mb-1">Salary Expectation</span>
                                                <span class="text-sm font-semibold text-primary-text" x-text="salary ? ('$' + salary) : 'Not provided'"></span>
                                            </div>
                                            <div>
                                                <span class="block text-xs text-gray-400 font-bold uppercase tracking-wider mb-1">Application Source</span>
                                                <span class="text-sm font-semibold text-primary-text">InternConnect</span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Section 2: Your Answers -->
                                    <div class="p-6 sm:p-8">
                                        <div class="flex items-center justify-between mb-6">
                                            <div class="flex items-center gap-2.5 text-primary">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                <h3 class="font-bold text-base text-primary-text">Your Answers</h3>
                                            </div>
                                            <button type="button" @click="goToStep(3)" class="text-xs font-semibold text-primary hover:underline flex items-center gap-1">
                                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                                                Edit
                                            </button>
                                        </div>

                                        <div class="space-y-4">
                                            <!-- Q1 -->
                                            <div class="bg-[#F8FAFC] border border-gray-100 rounded-2xl p-5 shadow-sm">
                                                <span class="block text-xs font-bold text-primary uppercase tracking-wider mb-2">Why are you interested in joining <?= htmlspecialchars($internship['company'] ?? 'Nexora') ?> specifically?</span>
                                                <p class="text-sm text-gray-700 leading-relaxed font-medium" x-text="interestReason || 'No answer provided.'"></p>
                                            </div>
                                            <!-- Q2 -->
                                            <div class="bg-[#F8FAFC] border border-gray-100 rounded-2xl p-5 shadow-sm">
                                                <span class="block text-xs font-bold text-primary uppercase tracking-wider mb-2">Describe your proudest project or process.</span>
                                                <p class="text-sm text-gray-700 leading-relaxed font-medium" x-text="designProcess || 'No answer provided.'"></p>
                                            </div>
                                            <!-- Q3 -->
                                            <div class="bg-[#F8FAFC] border border-gray-100 rounded-2xl p-5 shadow-sm">
                                                <span class="block text-xs font-bold text-primary uppercase tracking-wider mb-2">What are your primary learning goals for this internship?</span>
                                                <p class="text-sm text-gray-700 leading-relaxed font-medium" x-text="learningGoals || 'No answer provided.'"></p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Section 3: Attachments -->
                                    <div class="p-6 sm:p-8">
                                        <div class="flex items-center justify-between mb-6">
                                            <div class="flex items-center gap-2.5 text-primary">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                                </svg>
                                                <h3 class="font-bold text-base text-primary-text">Attachments & Links</h3>
                                            </div>
                                            <button type="button" @click="goToStep(2)" class="text-xs font-semibold text-primary hover:underline flex items-center gap-1">
                                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                                                Edit
                                            </button>
                                        </div>

                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                            <!-- Resume -->
                                            <div x-show="fileUploaded" class="bg-[#F8FAFC] border border-gray-100 rounded-2xl p-4 flex items-center gap-4 shadow-sm hover:border-primary/20 transition-all duration-300">
                                                <div class="h-12 w-12 bg-red-50 border border-red-100 rounded-xl flex items-center justify-center flex-shrink-0 text-red-500 font-bold uppercase text-[10px] shadow-sm">
                                                    PDF
                                                </div>
                                                <div class="min-w-0 flex-1">
                                                    <p class="text-xs text-gray-400 font-bold uppercase tracking-wider mb-0.5">Resume / CV</p>
                                                    <p class="text-sm font-bold text-primary-text truncate" x-text="uploadedFileName"></p>
                                                </div>
                                                <div class="h-6 w-6 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center flex-shrink-0 shadow-sm">
                                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                </div>
                                            </div>

                                            <!-- NYSC Credentials -->
                                            <div x-show="portfolioUploaded" class="bg-[#F8FAFC] border border-gray-100 rounded-2xl p-4 flex items-center gap-4 shadow-sm hover:border-primary/20 transition-all duration-300">
                                                <div class="h-12 w-12 bg-indigo-50 border border-indigo-100 rounded-xl flex items-center justify-center flex-shrink-0 text-indigo-500 font-bold uppercase text-[10px] shadow-sm">
                                                    <span x-text="portfolioFileExt">Doc</span>
                                                </div>
                                                <div class="min-w-0 flex-1">
                                                    <p class="text-xs text-gray-400 font-bold uppercase tracking-wider mb-0.5">NYSC Credentials</p>
                                                    <p class="text-sm font-bold text-primary-text truncate" x-text="portfolioFileName"></p>
                                                </div>
                                                <div class="h-6 w-6 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center flex-shrink-0 shadow-sm">
                                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                </div>
                                            </div>

                                            <!-- Portfolio Link -->
                                            <div x-show="portfolioLink" class="bg-[#F8FAFC] border border-gray-100 rounded-2xl p-4 flex items-center gap-4 shadow-sm hover:border-primary/20 transition-all duration-300">
                                                <div class="h-12 w-12 bg-indigo-50 border border-indigo-100 rounded-xl flex items-center justify-center flex-shrink-0 text-indigo-600 shadow-sm">
                                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                                                    </svg>
                                                </div>
                                                <div class="min-w-0 flex-1">
                                                    <p class="text-xs text-gray-400 font-bold uppercase tracking-wider mb-0.5">Portfolio Link</p>
                                                    <p class="text-sm font-bold text-primary-text truncate" x-text="portfolioLink"></p>
                                                </div>
                                                <div class="h-6 w-6 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center flex-shrink-0 shadow-sm">
                                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                </div>
                                            </div>

                                            <!-- Figma Link -->
                                            <div x-show="figmaLink" class="bg-[#F8FAFC] border border-gray-100 rounded-2xl p-4 flex items-center gap-4 shadow-sm hover:border-primary/20 transition-all duration-300">
                                                <div class="h-12 w-12 bg-pink-50 border border-pink-100 rounded-xl flex items-center justify-center flex-shrink-0 text-pink-500 shadow-sm">
                                                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                        <path d="M8 2h8a6 6 0 0 1 6 6v8a6 6 0 0 1-6 6H8a6 6 0 0 1-6-6V8a6 6 0 0 1 6-6z" />
                                                        <path d="M12 18a3 3 0 1 0 0-6 3 3 0 0 0 0 6z" />
                                                    </svg>
                                                </div>
                                                <div class="min-w-0 flex-1">
                                                    <p class="text-xs text-gray-400 font-bold uppercase tracking-wider mb-0.5">Figma Link</p>
                                                    <p class="text-sm font-bold text-primary-text truncate" x-text="figmaLink"></p>
                                                </div>
                                                <div class="h-6 w-6 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center flex-shrink-0 shadow-sm">
                                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Review Alert Message -->
                                <div class="mt-6 bg-blue-50 border border-blue-100 rounded-2xl p-5 flex gap-4">
                                    <div class="flex-shrink-0 h-10 w-10 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <p class="text-sm text-blue-700 leading-relaxed font-medium">
                                        Your application will be reviewed by <?= htmlspecialchars($internship['company'] ?? 'Nexora') ?> recruiting team. If your profile matches our requirements, you will be contacted for an online screening within 14 business days.
                                    </p>
                                </div>

                                <!-- Step 4 Buttons -->
                                <div class="mt-8 pt-8 border-t border-gray-200 flex flex-col sm:flex-row items-center gap-3">
                                    <!-- Submit — top on mobile, right on desktop -->
                                    <button type="submit" 
                                            :disabled="!fileUploaded"
                                            class="w-full sm:w-auto sm:ml-auto bg-primary hover:bg-primary-hover disabled:opacity-50 disabled:cursor-not-allowed text-white text-sm font-semibold py-3 px-6 rounded-xl transition active:scale-95 shadow-sm flex items-center justify-center gap-2 order-1 sm:order-3">
                                        Submit Application
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                                        </svg>
                                    </button>

                                    <!-- Previous — bottom on mobile, left on desktop -->
                                    <button type="button" @click="step = 3" class="w-full sm:w-auto justify-center border border-gray-300 text-gray-700 hover:bg-gray-50 text-sm font-semibold py-3 px-6 rounded-xl transition active:scale-95 flex items-center gap-2 order-3 sm:order-1 sm:mr-auto shadow-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M7.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l2.293 2.293a1 1 0 010 1.414z" clip-rule="evenodd" /></svg>
                                        Previous
                                    </button>
                                </div>
                            </div>
                            
                        </form>
                    </div>

                    <!-- ════════════ Extra Info Cards (Dynamic per Step) ════════════ -->
                    
                    <!-- Show on Step 2 (Resume) -->
                    <div x-show="step === 2" 
                         x-transition:enter="transition ease-out duration-300 delay-300"
                         x-transition:enter-start="opacity-0 translate-y-4"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         style="display: none;" 
                         class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8">
                         
                        <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm flex gap-4 hover:shadow-md transition-shadow">
                            <div class="flex-shrink-0 h-10 w-10 bg-primary-light text-primary rounded-xl flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-sm text-primary-text mb-1">Secure & Confidential</h4>
                                <p class="text-xs text-gray-500 leading-relaxed">Your documents are encrypted and shared only with verified recruiters.</p>
                            </div>
                        </div>
                        
                        <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm flex gap-4 hover:shadow-md transition-shadow">
                            <div class="flex-shrink-0 h-10 w-10 bg-primary-light text-primary rounded-xl flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" /></svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-sm text-primary-text mb-1">AI Optimization</h4>
                                <p class="text-xs text-gray-500 leading-relaxed">Our system scans your resume to suggest relevant internships automatically.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Show on Step 3 (Cover Letter) -->
                    <!-- Extra Step 3 info cards removed -->

                </div>

            </div>
        </div>
    </div>

    <!-- ════════════ Full Screen Preview Modal ════════════ -->
    <div x-show="showPreviewModal" 
         class="fixed inset-0 z-[60] flex items-center justify-center p-3 sm:p-6"
         style="display: none;"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95">
         
        <!-- Overlay -->
        <div class="fixed inset-0 bg-black/65 backdrop-blur-sm" @click="showPreviewModal = false"></div>
        
        <!-- Modal Card -->
        <div class="relative z-10 w-full max-w-3xl flex flex-col rounded-2xl bg-white shadow-2xl border border-gray-100 overflow-hidden" style="height: min(90vh, 860px);">

                <!-- Modal Header -->
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gray-50/50 flex-shrink-0">
                    <div class="flex items-center gap-3">
                        <div class="h-10 w-10 bg-red-50 text-red-500 rounded-xl flex items-center justify-center font-bold text-sm border border-red-100">PDF</div>
                        <div>
                            <h3 class="text-base font-bold text-primary-text leading-tight" x-text="uploadedFileName"></h3>
                            <p class="text-xs text-gray-500" x-text="uploadedFileSize + ' • View only'"></p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <!-- File type label -->
                        <span class="text-xs text-gray-400 font-medium hidden sm:inline"
                              x-text="uploadedFileExt === 'pdf' ? 'PDF • Live Preview' : (uploadedFileExt.toUpperCase() + ' • Document')"></span>
                        <button type="button" @click="showPreviewModal = false" class="p-2 text-gray-400 hover:text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
                
                <!-- Modal Content — Actual uploaded document -->
                <div class="flex-1 overflow-hidden bg-gray-50 relative" style="min-height: 0;">

                    <!-- PDF: full iframe -->
                    <div x-show="uploadedFileExt === 'pdf'" class="absolute inset-0">
                        <iframe
                            :src="uploadedFileExt === 'pdf' ? (uploadedFileUrl + '#toolbar=0&navpanes=0&scrollbar=0&view=Fit') : ''"
                            class="w-full h-full"
                            style="border: none;"
                            title="Full Resume Preview"
                        ></iframe>
                    </div>

                    <!-- DOCX / RTF: centered placeholder -->
                    <div x-show="uploadedFileExt !== 'pdf'" class="absolute inset-0 flex flex-col items-center justify-center gap-6 p-8">
                        <div class="flex items-center justify-center h-20 w-20 rounded-3xl bg-indigo-50 border border-indigo-100 shadow-sm">
                            <svg class="h-10 w-10 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                            </svg>
                        </div>
                        <div class="text-center max-w-sm">
                            <p class="font-bold text-primary-text text-lg mb-2" x-text="uploadedFileName"></p>
                            <p class="text-sm text-gray-500 mb-1" x-text="uploadedFileSize + ' • Ready for review'"></p>
                            <p class="text-sm text-gray-400 leading-relaxed">Browser-based preview is available for PDF files only. Your Word / RTF document has been successfully loaded and will be submitted with your application.</p>
                        </div>
                        <a :href="uploadedFileUrl" :download="uploadedFileName"
                           class="inline-flex items-center gap-2 text-sm font-semibold text-white bg-primary hover:bg-primary-hover px-5 py-2.5 rounded-xl transition active:scale-95 shadow">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                            Download & Open Locally
                        </a>
                    </div><!-- end DOCX placeholder -->

                </div><!-- end modal content (flex-1) -->

                <!-- Modal Footer -->
                <div class="px-5 sm:px-6 py-4 border-t border-gray-100 flex items-center justify-between bg-white flex-shrink-0">
                    <span class="text-xs text-gray-400" x-text="uploadedFileExt === 'pdf' ? 'Scroll to navigate pages' : 'Download the file to view it in full'"></span>
                    <button type="button" @click="showPreviewModal = false" class="bg-primary hover:bg-primary-hover text-white text-sm font-semibold py-2.5 px-5 rounded-xl transition active:scale-95 shadow-sm">
                        Close Preview
                    </button>
                </div>
        </div><!-- end modal card -->
    </div><!-- end modal overlay wrapper -->

</div><!-- end x-data root -->
