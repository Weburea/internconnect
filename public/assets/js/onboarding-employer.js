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
        countdown: 300,
        resend_cooldown: 51,
        countdown_timer_id: null,
        cooldown_timer_id: null,
        show_success_modal: false,
        show_failure_modal: false,
        countdown_progress: 100,

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
        industry: 'Fintech',
        company_size: '11-50 employees',
        website: '',
        description: '',

        // Step 6 Hiring Focus
        search_query: '',
        departments: ['Engineering'],
        all_departments: [
            'Engineering', 'Product Design', 'Marketing', 'Data Science', 'Finance', 'Operations',
            'Sales', 'HR', 'Customer Success', 'Legal'
        ],
        expected_volume: '6 - 15 Interns',
        internship_type: 'Paid', // 'Paid' | 'Stipend' | 'Unpaid'
        show_dept_dropdown: false,

        // General state
        server_errors: [],
        submitting: false,

        init() {
            // Load partial draft from local state if exists
            const draft = localStorage.getItem('internconnect_employer_onboarding_draft');
            if (draft) {
                try {
                    const data = JSON.parse(draft);
                    const loadedStep = data.step || 2;
                    this.step = loadedStep < 2 ? 2 : loadedStep;
                    this.role = data.role || 'employer';
                    this.company_name = data.company_name || '';
                    this.email = data.email || '';
                    this.signup_method = data.signup_method || null;
                    this.cac_number = data.cac_number || '';
                    this.business_doc_name = data.business_doc_name || '';
                    this.business_doc_size = data.business_doc_size || '';
                    this.business_doc_uploaded = data.business_doc_uploaded || false;
                    this.logo_name = data.logo_name || '';
                    this.logo_size = data.logo_size || '';
                    this.logo_uploaded = data.logo_uploaded || false;
                    this.logo_url = data.logo_url || '';
                    this.industry = data.industry || 'Fintech';
                    this.company_size = data.company_size || '11-50 employees';
                    this.website = data.website || '';
                    this.description = data.description || '';
                    this.departments = data.departments || ['Engineering'];
                    this.expected_volume = data.expected_volume || '6 - 15 Interns';
                    this.internship_type = data.internship_type || 'Paid';
                } catch (e) {
                    console.error('Error loading employer onboarding draft', e);
                }
            }

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

        formatCooldown(secs) {
            return `0:${secs < 10 ? '0' : ''}${secs}`;
        },

        formatExpiry(secs) {
            const m = Math.floor(secs / 60);
            const s = secs % 60;
            return `${m}:${s < 10 ? '0' : ''}${s}`;
        },

        startTimers() {
            this.clearTimers();
            
            // Countdown timer (5 minutes)
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
        },

        resendCode() {
            this.resend_cooldown = 51;
            this.countdown = 300;
            this.startTimers();
            this.verification_input = '';
            this.$nextTick(() => {
                if (this.$refs.verificationInput) this.$refs.verificationInput.focus();
            });
        },

        continueWithGoogle() {
            // Mocks OAuth success or error
            // Simulates failure if company_name contains "fail" or randomly
            if (this.company_name.toLowerCase().includes('fail')) {
                this.oauth_error = 'Google Sign-up was canceled or failed. Please try again.';
                this.signup_method = null;
                return;
            }
            this.oauth_error = '';
            this.company_name = this.company_name || 'Flux Technologies';
            this.email = this.email || 'contact@fluxtech.io';
            this.signup_method = 'google';
            // Skips email verification modal step 3 and moves to step 4
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

        submitStep3() {
            this.show_success_modal = true;
            this.startSuccessModalRedirect();
        },

        submitStep4() {
            // Business Registration Document is mandatory for all organizations
            if (!this.business_doc_uploaded) {
                this.server_errors = ['Uploading a Business Registration Document is required to verify your organization.'];
                return;
            }
            // CAC Number is mandatory if they register with a personal email domain
            if (this.isPersonalEmail(this.email)) {
                if (!this.cac_number.trim()) {
                    this.server_errors = ['Because you registered with a personal email domain, providing a CAC Number is also required.'];
                    return;
                }
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

        submitStep7() {
            localStorage.removeItem('internconnect_employer_onboarding_draft');
            const welcomeUrl = window.INTERN_WELCOME_EMPLOYER_URL || '/welcome/employer';
            window.location.href = welcomeUrl + '?name=' + encodeURIComponent(this.company_name);
        },

        goBackToStep2() {
            // Reset signup method if we return to Step 2
            this.signup_method = null;
            this.oauth_error = '';
            this.step = 2;
            this.saveDraftToLocal();
        }
    };
}
