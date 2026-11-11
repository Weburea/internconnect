function employerOnboardingWizard() {
    return {
        step: 2,
        role: 'employer',
        
        // Step 2 Create Account
        company_name: '',
        email: '',
        password: '',
        confirm_password: '',
        terms: false,
        password_visible: false,
        confirm_password_visible: false,
        signup_method: null, // 'form' / 'google' / null
        oauth_error: '', // Inline error for OAuth failure/cancel

        // Step 3 Email Verification
        verification_input: '',
        email_sender: 'internconnect@gmail.com',
        countdown: 120, // 2 minutes (120 seconds)
        resend_cooldown: 120,
        countdown_timer_id: null,
        cooldown_timer_id: null,
        show_success_modal: false,
        show_failure_modal: false,
        countdown_progress: 100,
        success_redirect_timer: null,

        // Step 4 Organization Verification
        cac_number: '',
        business_doc_name: '',
        business_doc_size: '',
        business_doc_uploaded: false,
        is_dragged_business_doc: false,

        // Step 5 Company Details
        logo_name: '',
        logo_size: '',
        logo_uploaded: false,
        logo_url: '',
        is_dragged_logo: false,
        industry: '',
        company_size: '',
        company_size_options: [
            '1-10 employees',
            '11-50 employees',
            '51-200 employees',
            '201–500 employees',
            '500+ employees'
        ],
        website: '',
        description: '',

        // Step 6 Hiring Focus
        search_query: '',
        departments: [],
        all_departments: [
            'Engineering', 'Product Design', 'Marketing', 'Data Science', 'Finance & Accounting', 'Operations',
            'Sales & Business Dev', 'Human Resources', 'Customer Success', 'Legal & Compliance',
            'Cybersecurity', 'AI & Machine Learning', 'Content & Copywriting', 'Quality Assurance',
            'Project Management', 'Public Relations', 'Supply Chain & Logistics', 'IT & Systems Admin'
        ],
        expected_volume: '',
        expected_volume_options: [
            '1 - 5 Interns',
            '6 - 15 Interns',
            '16 - 30 Interns',
            '30+ Interns'
        ],
        internship_type: 'Paid', // 'Paid' | 'Stipend' | 'Unpaid'
        show_dept_dropdown: false,

        // General state
        server_errors: [],
        submitting: false,

        init() {
            this.resetModals();
            // Always clear stale localStorage drafts on fresh page load.
            // Draft restoration should only happen via server-side session,
            // not client-side localStorage which retains old test data.
            localStorage.removeItem('internconnect_employer_onboarding_draft');

            if (this.step === 3) {
                this.startTimers();
            }
        },

        saveDraftToLocal() {
            const data = {
                step: this.step,
                role: this.role,
                company_name: this.company_name,
                email: this.email,
                signup_method: this.signup_method,
                cac_number: this.cac_number,
                business_doc_name: this.business_doc_name,
                business_doc_size: this.business_doc_size,
                business_doc_uploaded: this.business_doc_uploaded,
                logo_name: this.logo_name,
                logo_size: this.logo_size,
                logo_uploaded: this.logo_uploaded,
                logo_url: this.logo_url,
                industry: this.industry,
                company_size: this.company_size,
                website: this.website,
                description: this.description,
                departments: this.departments,
                expected_volume: this.expected_volume,
                internship_type: this.internship_type
            };
            localStorage.setItem('internconnect_employer_onboarding_draft', JSON.stringify(data));
        },

        isPersonalEmail(email) {
            if (!email) return false;
            const personalDomains = ['gmail.com', 'outlook.com', 'yahoo.com', 'hotmail.com', 'aol.com', 'icloud.com', 'mail.com', 'zoho.com', 'protonmail.com'];
            const domain = email.split('@').pop().toLowerCase().trim();
            return personalDomains.includes(domain);
        },

        getPasswordStrength(pass) {
            if (!pass) return { score: 0, label: '', color: 'bg-gray-200' };
            let score = 0;
            if (pass.length >= 8) score++;
            if (/[a-z]/.test(pass) && /[A-Z]/.test(pass)) score++;
            if (/\d/.test(pass)) score++;
            if (/[!@#$%^&*(),.?":{}|<>]/.test(pass)) score++;

            if (score <= 1) return { score: 1, label: 'Weak password', color: 'bg-[#EF4444]' };
            if (score <= 3) return { score: 2, label: 'Medium password', color: 'bg-[#F59E0B]' };
            return { score: 4, label: 'Strong password', color: 'bg-[#22C55E]' };
        },

        getPasswordStrengthText(pass, defaultText) {
            if (!pass) return defaultText;
            const strength = this.getPasswordStrength(pass);
            return strength.label;
        },

        handleVerificationInput() {
            this.verification_input = this.verification_input.replace(/[^0-9]/g, '').substring(0, 6);
        },

        handlePaste(e) {
            e.preventDefault();
            const clipboardData = e.clipboardData || window.clipboardData;
            const pastedText = clipboardData ? clipboardData.getData('text') : '';
            const cleanDigits = pastedText.replace(/\D/g, '').substring(0, 6);
            if (cleanDigits.length > 0) {
                this.verification_input = cleanDigits;
            }
        },

        formatCooldown(secs) {
            const m = Math.floor(secs / 60);
            const s = secs % 60;
            return `${m}:${s < 10 ? '0' : ''}${s}`;
        },

        formatExpiry(secs) {
            const m = Math.floor(secs / 60);
            const s = secs % 60;
            return `${m}:${s < 10 ? '0' : ''}${s}`;
        },

        startTimers() {
            this.clearTimers();
            
            // Countdown timer (2 minutes)
            this.countdown_timer_id = setInterval(() => {
                if (this.countdown > 0) {
                    this.countdown--;
                } else {
                    this.clearTimers();
                }
            }, 1000);

            // Cooldown resend timer
            this.cooldown_timer_id = setInterval(() => {
                if (this.resend_cooldown > 0) {
                    this.resend_cooldown--;
                } else {
                    clearInterval(this.cooldown_timer_id);
                    this.cooldown_timer_id = null;
                }
            }, 1000);
        },

        clearTimers() {
            if (this.countdown_timer_id) {
                clearInterval(this.countdown_timer_id);
                this.countdown_timer_id = null;
            }
            if (this.cooldown_timer_id) {
                clearInterval(this.cooldown_timer_id);
                this.cooldown_timer_id = null;
            }
            if (this.success_redirect_timer) {
                clearInterval(this.success_redirect_timer);
                this.success_redirect_timer = null;
            }
        },

        resetModals() {
            this.show_success_modal = false;
            this.show_failure_modal = false;
            if (this.success_redirect_timer) {
                clearInterval(this.success_redirect_timer);
                this.success_redirect_timer = null;
            }
        },

        resendCode() {
            this.resend_cooldown = 120;
            this.countdown = 120;
            this.startTimers();
            this.verification_input = '';
            this.$nextTick(() => {
                if (this.$refs.verificationInput) this.$refs.verificationInput.focus();
            });
        },

        continueWithGoogle() {
            // Mocks OAuth success or error
            if (this.company_name && this.company_name.toLowerCase().includes('fail')) {
                this.oauth_error = 'Google Sign-up was canceled or failed. Please try again.';
                this.signup_method = null;
                return;
            }
            this.oauth_error = '';
            this.signup_method = 'google';
            // Skips email verification modal step 3 and moves to step 4 with clean user input state
            this.step = 4;
            this.saveDraftToLocal();
        },

        mockOauthFailure() {
            // Helper to simulate OAuth cancellation/failure from UI
            this.oauth_error = 'Google authentication canceled by user.';
            this.signup_method = null;
        },

        handleBusinessDocDrop(e) {
            this.is_dragged_business_doc = false;
            if (e.dataTransfer.files && e.dataTransfer.files[0]) {
                this.uploadBusinessDoc(e.dataTransfer.files[0]);
            }
        },

        handleBusinessDocSelect(e) {
            if (e.target.files && e.target.files[0]) {
                this.uploadBusinessDoc(e.target.files[0]);
            }
        },

        uploadBusinessDoc(file) {
            const allowed = ['pdf', 'jpg', 'jpeg', 'png'];
            const ext = file.name.split('.').pop().toLowerCase();
            if (!allowed.includes(ext)) {
                this.server_errors = ['Unsupported file type. Please upload a PDF, JPG, or PNG.'];
                return;
            }
            if (file.size > 10 * 1024 * 1024) {
                this.server_errors = ['Document must be under 10MB.'];
                return;
            }
            // Keep the File outside Alpine's reactive state; it is sent with the final step.
            window.__icEmployerBusinessDoc = file;
            this.business_doc_name = file.name;
            const sizeInMb = (file.size / (1024 * 1024)).toFixed(2);
            this.business_doc_size = sizeInMb + ' MB';
            this.business_doc_uploaded = true;
            this.server_errors = [];
        },

        removeBusinessDoc() {
            this.business_doc_name = '';
            this.business_doc_size = '';
            this.business_doc_uploaded = false;
            window.__icEmployerBusinessDoc = null;
            if (this.$refs.businessDocInput) {
                this.$refs.businessDocInput.value = '';
            }
        },

        handleLogoDrop(e) {
            this.is_dragged_logo = false;
            if (e.dataTransfer.files && e.dataTransfer.files[0]) {
                this.uploadLogo(e.dataTransfer.files[0]);
            }
        },

        handleLogoSelect(e) {
            if (e.target.files && e.target.files[0]) {
                this.uploadLogo(e.target.files[0]);
            }
        },

        uploadLogo(file) {
            const allowed = ['jpg', 'jpeg', 'png'];
            const ext = file.name.split('.').pop().toLowerCase();
            if (!allowed.includes(ext)) {
                this.server_errors = ['Unsupported file type. Please upload a JPG or PNG.'];
                return;
            }
            if (file.size > 5 * 1024 * 1024) {
                this.server_errors = ['Logo image must be under 5MB.'];
                return;
            }
            window.__icEmployerLogo = file;
            this.logo_name = file.name;
            const sizeInMb = (file.size / (1024 * 1024)).toFixed(2);
            this.logo_size = sizeInMb + ' MB';
            this.logo_uploaded = true;
            this.logo_url = URL.createObjectURL(file);
            this.server_errors = [];
        },

        removeLogo() {
            this.logo_name = '';
            this.logo_size = '';
            this.logo_uploaded = false;
            this.logo_url = '';
            window.__icEmployerLogo = null;
            if (this.$refs.logoInput) {
                this.$refs.logoInput.value = '';
            }
        },

        filteredDepartments() {
            if (!this.search_query) return this.all_departments;
            return this.all_departments.filter(item => 
                item.toLowerCase().includes(this.search_query.toLowerCase())
            );
        },

        toggleDepartment(item) {
            if (this.departments.includes(item)) {
                this.departments = this.departments.filter(x => x !== item);
            } else {
                this.departments.push(item);
            }
        },

        clearAllDepartments() {
            this.departments = [];
        },

        toggleInternshipType(type) {
            this.internship_type = (this.internship_type === type ? '' : type);
            this.saveDraftToLocal();
        },

        getCompanyInitials() {
            const name = (this.company_name || '').trim();
            if (!name) return 'FT';
            const words = name.split(/\s+/).filter(Boolean);
            if (words.length >= 2) {
                return (words[0][0] + words[1][0]).toUpperCase();
            }
            return name.substring(0, 2).toUpperCase() || 'FT';
        },

        getWordCount(text) {
            if (!text) return 0;
            return text.trim().split(/\s+/).filter(Boolean).length;
        },

        startSuccessModalRedirect() {
            this.countdown_progress = 100;
            const duration = 5000; // 5s
            const interval = 10;
            let elapsed = 0;
            const timer = setInterval(() => {
                elapsed += interval;
                this.countdown_progress = 100 - (elapsed / duration) * 100;
                if (elapsed >= duration) {
                    clearInterval(timer);
                    this.show_success_modal = false;
                    this.step = 4;
                    this.clearTimers();
                    this.saveDraftToLocal();
                }
            }, interval);
        },

        submitStep2() {
            this.signup_method = 'form';
            this.step = 3;
            this.verification_input = '';
            this.saveDraftToLocal();
            this.$nextTick(() => {
                if (this.$refs.verificationInput) this.$refs.verificationInput.focus();
            });
        },

        startSuccessModalRedirect() {
            if (this.success_redirect_timer) {
                clearInterval(this.success_redirect_timer);
                this.success_redirect_timer = null;
            }
            this.countdown_progress = 100;
            const duration = 5000; // 5s
            const interval = 25;
            let elapsed = 0;
            this.success_redirect_timer = setInterval(() => {
                elapsed += interval;
                this.countdown_progress = Math.max(0, 100 - (elapsed / duration) * 100);
                if (elapsed >= duration) {
                    if (this.success_redirect_timer) {
                        clearInterval(this.success_redirect_timer);
                        this.success_redirect_timer = null;
                    }
                    this.show_success_modal = false;
                    this.step = 4;
                    this.clearTimers();
                    this.saveDraftToLocal();
                }
            }, interval);
        },

        submitStep3() {
            this.show_success_modal = true;
            this.startSuccessModalRedirect();
        },

        submitStep4() {
            // Registration Number is required for all organizations
            if (!this.cac_number.trim()) {
                this.server_errors = ['Registration Number is required to verify your organization.'];
                return;
            }
            // Business Registration Document is mandatory for all organizations
            if (!this.business_doc_uploaded) {
                this.server_errors = ['Uploading a Business Registration Document is required to verify your organization.'];
                return;
            }
            this.server_errors = [];
            this.step = 5;
            this.saveDraftToLocal();
        },

        submitStep5() {
            this.step = 6;
            this.saveDraftToLocal();
        },

        submitStep6() {
            this.step = 7;
            this.saveDraftToLocal();
        },

        // Save the organization profile (and uploaded files), then continue to the welcome page.
        async submitStep7() {
            if (this.submitting) return;
            this.submitting = true;
            this.server_errors = [];
            const welcomeUrl = window.INTERN_WELCOME_EMPLOYER_URL || '/welcome/employer';
            const payload = {
                company_name: this.company_name,
                email: this.email,
                password: this.password,
                cac_number: this.cac_number,
                industry: this.industry,
                company_size: this.company_size,
                website: this.website,
                description: this.description,
                departments: this.departments,
                expected_volume: this.expected_volume,
                internship_type: this.internship_type
            };
            const form = new FormData();
            form.append('data', JSON.stringify(payload));
            if (this.logo_uploaded && window.__icEmployerLogo) form.append('logo', window.__icEmployerLogo);
            if (this.business_doc_uploaded && window.__icEmployerBusinessDoc) form.append('business_doc', window.__icEmployerBusinessDoc);
            try {
                const res = await fetch(window.INTERN_EMPLOYER_COMPLETE_URL || '/register/employer/complete', {
                    method: 'POST',
                    headers: { 'Accept': 'application/json' },
                    body: form
                });
                const json = await res.json().catch(() => ({}));
                if (!res.ok || !json.success) {
                    this.server_errors = [json.error || 'We could not save your organization profile. Please try again.'];
                    this.submitting = false;
                    return;
                }
                localStorage.removeItem('internconnect_employer_onboarding_draft');
                window.__icEmployerLogo = null;
                window.__icEmployerBusinessDoc = null;
                window.location.href = json.redirect || (welcomeUrl + '?name=' + encodeURIComponent(this.company_name));
            } catch (e) {
                this.server_errors = ['Network error while saving your organization profile. Please try again.'];
                this.submitting = false;
            }
        },

        goBackToStep2() {
            this.resetModals();
            this.clearTimers();
            this.signup_method = null;
            this.oauth_error = '';
            this.step = 2;
            this.saveDraftToLocal();
        }
    };
}
