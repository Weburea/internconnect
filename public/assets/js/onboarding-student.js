function studentOnboardingWizard() {
    return {
        step: 2,
        // Step 1
        role: 'student',
        // Step 2 (Empty initial values with placeholders as requested)
        email: '',
        password: '',
        confirm_password: '',
        terms: false,
        password_visible: false,
        confirm_password_visible: false,
        // Step 3
        verification_input: '',
        email_sender: 'internconnect@gmail.com',
        countdown: 300, // 5 minutes (300 seconds)
        resend_cooldown: 51,
        countdown_timer_id: null,
        cooldown_timer_id: null,
        show_success_modal: false,
        show_failure_modal: false,
        countdown_progress: 100,
        // Step 4 (Empty initial values with placeholders as requested)
        first_name: '',
        last_name: '',
        university: '',
        degree_level: '',
        graduation_year: '',
        field_of_study: '',
        gpa: '',
        degree_options: [
            "Bachelor's",
            "Master's",
            "Doctorate / Ph.D",
            "Associate Degree",
            "Diploma / Certificate",
            "High School / Secondary",
            "Bootcamp / Certificate"
        ],
        graduation_year_options: [
            '2035', '2034', '2033', '2032', '2031', '2030', '2029', '2028', '2027', '2026',
            '2025', '2024', '2023', '2022', '2021', '2020', '2019', '2018', '2017', '2016', '2015'
        ],
        // Step 5
        resume_name: '',
        resume_size: '',
        fileUploaded: false,
        portfolio: '',
        linkedin: '',
        github: '',
        is_dragged: false,
        // Step 6
        search_query: '',
        interests: [],
        work_arrangements: ['Remote', 'Hybrid'], // default checked
        all_interests: [
            'UI/UX Design', 'Software Engineering', 'Product Management', 'Data Science', 'AI/ML',
            'Marketing', 'Sales', 'Finance', 'Operations', 'Cybersecurity', 'Cloud Computing',
            'DevOps', 'Graphic Design', 'Content Creation', 'HR'
        ],
        // Step 7
        location: '',
        open_to_relocating: 'Yes',
        job_suggestions_locations: [],
        new_suggestion_location: '',
        include_remote: true,
        // General state
        server_errors: [],
        submitting: false,

        init() {
            // Always clear stale localStorage drafts on fresh page load.
            // Draft restoration should only happen via server-side session,
            // not client-side localStorage which retains old test data.
            localStorage.removeItem('internconnect_onboarding_draft');

            // Start verification timers if initialized on step 3
            if (this.step === 3) {
                this.startTimers();
            }
        },

        saveDraftToLocal() {
            const data = {
                step: this.step,
                role: this.role,
                email: this.email,
                first_name: this.first_name,
                last_name: this.last_name,
                university: this.university,
                degree_level: this.degree_level,
                graduation_year: this.graduation_year,
                field_of_study: this.field_of_study,
                gpa: this.gpa,
                portfolio: this.portfolio,
                linkedin: this.linkedin,
                github: this.github,
                resume_name: this.resume_name,
                resume_size: this.resume_size,
                fileUploaded: this.fileUploaded,
                interests: this.interests,
                work_arrangements: this.work_arrangements,
                location: this.location,
                open_to_relocating: this.open_to_relocating,
                job_suggestions_locations: this.job_suggestions_locations,
                include_remote: this.include_remote
            };
            localStorage.setItem('internconnect_onboarding_draft', JSON.stringify(data));
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
            // Keep numeric only and max 6 digits
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
            // Google OAuth login sets email as verified and advances to Step 4 without prefilled dummy values
            this.signup_method = 'google';
            this.step = 4;
            this.saveDraftToLocal();
        },

        handleFileDrop(e) {
            this.is_dragged = false;
            if (e.dataTransfer.files && e.dataTransfer.files[0]) {
                const file = e.dataTransfer.files[0];
                this.uploadResumeFile(file);
            }
        },

        handleFileSelect(e) {
            if (e.target.files && e.target.files[0]) {
                this.uploadResumeFile(e.target.files[0]);
            }
        },

        uploadResumeFile(file) {
            const allowed = ['pdf', 'docx'];
            const ext = file.name.split('.').pop().toLowerCase();
            if (!allowed.includes(ext)) {
                this.server_errors = ['Unsupported file type. Please upload a PDF or DOCX file.'];
                return;
            }
            if (file.size > 10 * 1024 * 1024) {
                this.server_errors = ['Resume file must be under 10MB.'];
                return;
            }
            this.resume_name = file.name;
            const sizeInMb = (file.size / (1024 * 1024)).toFixed(2);
            this.resume_size = sizeInMb + ' MB';
            this.fileUploaded = true;
            this.server_errors = [];
        },

        removeSelectedFile() {
            this.resume_name = '';
            this.resume_size = '';
            this.fileUploaded = false;
            if (this.$refs.fileInput) {
                this.$refs.fileInput.value = '';
            }
        },

        filteredInterests() {
            if (!this.search_query) return this.all_interests;
            return this.all_interests.filter(item => 
                item.toLowerCase().includes(this.search_query.toLowerCase())
            );
        },

        toggleInterest(item) {
            if (this.interests.includes(item)) {
                this.interests = this.interests.filter(x => x !== item);
            } else {
                this.interests.push(item);
            }
        },

        addSuggestionLocation() {
            const val = this.new_suggestion_location.trim();
            if (val && !this.job_suggestions_locations.includes(val)) {
                this.job_suggestions_locations.push(val);
            }
            this.new_suggestion_location = '';
        },

        removeSuggestionLocation(city) {
            this.job_suggestions_locations = this.job_suggestions_locations.filter(x => x !== city);
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

        // Client-side transitions to bypass network/AJAX errors during UI design testing
        submitStep1() {
            this.step = 2;
            this.saveDraftToLocal();
        },

        submitStep2() {
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
            this.step = 5;
            this.saveDraftToLocal();
        },

        submitStep5() {
            this.step = 6;
            this.saveDraftToLocal();
        },

        skipStep5() {
            this.step = 6;
            this.saveDraftToLocal();
        },

        submitStep6() {
            this.step = 7;
            this.saveDraftToLocal();
        },

        submitStep7() {
            localStorage.removeItem('internconnect_onboarding_draft');
            window.location.href = (window.INTERN_WELCOME_URL || '/welcome') + '?name=' + encodeURIComponent(this.first_name + ' ' + this.last_name);
        }
    };
}
