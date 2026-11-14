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
        countdown: 120, // 2 minutes (120 seconds)
        resend_cooldown: 120,
        countdown_timer_id: null,
        cooldown_timer_id: null,
        show_success_modal: false,
        show_failure_modal: false,
        countdown_progress: 100,
        success_redirect_timer: null,
        // Step 4 (Empty initial values with placeholders as requested)
        first_name: '',
        last_name: '',
        university: '',
        university_results: [],
        university_loading: false,
        show_university_dropdown: false,
        university_debounce_timer: null,
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
        // Jenny AI CV read: '' | reading | parsed | failed (docs/49-cv-autofill-and-applications.md)
        cv_state: '',
        cv_found: '',
        portfolio: '',
        linkedin: '',
        github: '',
        is_dragged: false,
        // Step 6: Career Interests Taxonomy with Relational Surfacing & Hidden Keywords
        search_query: '',
        interests: [],
        interest_keywords: [], // Hidden keywords vector synchronized for AI matchmaking & DB fulltext index
        work_arrangements: ['Remote', 'Hybrid'], // default checked

        /**
         * Comprehensive Career Interests Taxonomy
         * 
         * Architectural Logic:
         * 1. Each discipline defines:
         *    - primary (boolean): Whether it displays by default in the initial unselected view.
         *    - category (string): Parent industry grouping.
         *    - related (array): Specializations surfaced dynamically when this field is active.
         *    - keywords (array): Hidden searchable & matchmaking keywords stored in candidate profile vector.
         * 
         * 2. Relational Surfacing & Adaptive Filtering:
         *    - Default state (0 selected): All 15 foundational primary domains are displayed.
         *    - Active selection state: Unrelated disciplines (e.g., Graphic Design when Software Engineering is chosen)
         *      are automatically hidden, while related specializations (e.g. DevOps, Web Dev, Product Engineer,
         *      Civil Engineering, Mechanical Engineering, etc.) dynamically pop up!
         *    - Active tags ALWAYS remain visible and highlighted so the user can easily review and uncheck them.
         *    - Search queries match tag names, categories, related fields, AND hidden keywords!
         * 
         * 3. Database Synchronization:
         *    - Selected tags are saved in `interests`.
         *    - Extracted hidden keywords are collected into `interest_keywords` for database fulltext search / vector indexing.
         */
        interests_taxonomy: {
            'Software Engineering': {
                primary: true,
                category: 'Engineering',
                related: ['DevOps', 'Cloud Computing', 'Web Development', 'Mobile App Development', 'Backend Engineering', 'Frontend Engineering', 'Civil Engineering', 'Mechanical Engineering', 'Product Engineer', 'Cybersecurity'],
                keywords: ['coding', 'programming', 'software development', 'full stack', 'react', 'python', 'java', 'c++', 'algorithms', 'git', 'api', 'architecture', 'software engineer']
            },
            'UI/UX Design': {
                primary: true,
                category: 'Design',
                related: ['Product Design', 'UI Designer', 'UX Designer', 'UX Research', 'Design Systems', 'Interaction Design', 'Wireframing & Prototyping', 'Product Management', 'Graphic Design'],
                keywords: ['figma', 'user interface', 'user experience', 'prototyping', 'wireframing', 'design thinking', 'user research', 'usability', 'personas', 'visual design', 'ui/ux']
            },
            'Product Management': {
                primary: true,
                category: 'Product',
                related: ['Associate Product Manager', 'Product Operations', 'Technical Product Manager', 'Product Growth', 'UI/UX Design', 'Product Engineer', 'Agile & Scrum', 'Market Research'],
                keywords: ['roadmap', 'product strategy', 'user stories', 'feature prioritization', 'kpis', 'agile', 'scrum', 'mvp', 'stakeholder management', 'product manager']
            },
            'Data Science': {
                primary: true,
                category: 'Data & AI',
                related: ['AI/ML', 'Machine Learning Engineer', 'Data Analyst', 'Business Intelligence', 'Deep Learning', 'Data Engineering', 'Python / SQL'],
                keywords: ['python', 'sql', 'pandas', 'statistics', 'machine learning', 'data analysis', 'modeling', 'visualization', 'tableau', 'big data', 'data scientist']
            },
            'AI/ML': {
                primary: true,
                category: 'Data & AI',
                related: ['Data Science', 'Machine Learning Engineer', 'Deep Learning', 'Computer Vision', 'Natural Language Processing (NLP)', 'AI Research', 'Python / SQL'],
                keywords: ['artificial intelligence', 'machine learning', 'neural networks', 'deep learning', 'pytorch', 'tensorflow', 'nlp', 'llms', 'prompt engineering', 'ai', 'ml']
            },
            'Marketing': {
                primary: true,
                category: 'Marketing',
                related: ['Digital Marketing', 'Content Creation', 'SEO & SEM', 'Social Media Management', 'Growth Marketing', 'Brand Strategy', 'Public Relations'],
                keywords: ['copywriting', 'content strategy', 'google ads', 'social media', 'analytics', 'campaigns', 'growth hacking', 'email marketing', 'digital marketing']
            },
            'Sales': {
                primary: true,
                category: 'Business',
                related: ['Business Development', 'Account Management', 'Sales Operations', 'Customer Success', 'Market Expansion'],
                keywords: ['crm', 'lead generation', 'cold outreach', 'client relations', 'negotiation', 'pipeline management', 'b2b sales', 'business development']
            },
            'Finance': {
                primary: true,
                category: 'Finance',
                related: ['Financial Analysis', 'Corporate Finance', 'Accounting & Audit', 'Investment Banking', 'Fintech', 'Risk Management'],
                keywords: ['financial modeling', 'excel', 'valuation', 'bookkeeping', 'audit', 'taxation', 'budgeting', 'portfolio management', 'financial analyst']
            },
            'Operations': {
                primary: true,
                category: 'Operations',
                related: ['Business Operations', 'Supply Chain & Logistics', 'Project Management', 'Process Optimization', 'Operations Analyst'],
                keywords: ['workflow optimization', 'operations', 'logistics', 'supply chain', 'kpis', 'efficiency', 'inventory', 'vendor management', 'operations manager']
            },
            'Cybersecurity': {
                primary: true,
                category: 'Security',
                related: ['Information Security', 'Network Security', 'Ethical Hacking', 'SOC Analyst', 'Cloud Security', 'DevOps'],
                keywords: ['infosec', 'penetration testing', 'firewalls', 'encryption', 'vulnerability assessment', 'compliance', 'cyber defense', 'security engineer']
            },
            'Cloud Computing': {
                primary: true,
                category: 'Infrastructure',
                related: ['DevOps', 'Software Engineering', 'Site Reliability Engineering (SRE)', 'Kubernetes & Docker', 'Cloud Security'],
                keywords: ['aws', 'azure', 'gcp', 'cloud architecture', 'serverless', 'infrastructure as code', 'linux', 'cloud engineer']
            },
            'DevOps': {
                primary: true,
                category: 'Infrastructure',
                related: ['Cloud Computing', 'Software Engineering', 'Site Reliability Engineering (SRE)', 'Kubernetes & Docker', 'CI/CD Pipelines'],
                keywords: ['docker', 'kubernetes', 'ci/cd', 'jenkins', 'terraform', 'automation', 'monitoring', 'gitops', 'devops engineer']
            },
            'Graphic Design': {
                primary: true,
                category: 'Design',
                related: ['UI/UX Design', 'Visual Design', 'Brand Identity', 'Motion Graphics', 'Illustration', 'Content Creation'],
                keywords: ['photoshop', 'illustrator', 'branding', 'typography', 'creative suite', 'visual arts', 'motion design', 'graphic designer']
            },
            'Content Creation': {
                primary: true,
                category: 'Media',
                related: ['Marketing', 'Copywriting', 'Video Editing', 'Content Strategy', 'Social Media Management', 'Graphic Design'],
                keywords: ['storytelling', 'scriptwriting', 'premiere pro', 'content marketing', 'blogging', 'creative writing', 'editing', 'content creator']
            },
            'HR': {
                primary: true,
                category: 'People',
                related: ['Human Resources (HR)', 'People Operations', 'Talent Acquisition', 'Recruiting', 'Employee Engagement'],
                keywords: ['recruiting', 'onboarding', 'human resources', 'talent acquisition', 'people operations', 'interviews', 'hr tech', 'hr coordinator']
            },

            // Dynamically surfaced specializations
            'Web Development': {
                primary: false,
                category: 'Engineering',
                related: ['Software Engineering', 'Frontend Engineering', 'Backend Engineering', 'UI/UX Design', 'DevOps'],
                keywords: ['html', 'css', 'javascript', 'react', 'vue', 'node.js', 'web apps', 'frontend', 'web developer']
            },
            'Backend Engineering': {
                primary: false,
                category: 'Engineering',
                related: ['Software Engineering', 'Web Development', 'Cloud Computing', 'DevOps', 'Data Science'],
                keywords: ['apis', 'databases', 'sql', 'python', 'java', 'node', 'microservices', 'backend developer']
            },
            'Frontend Engineering': {
                primary: false,
                category: 'Engineering',
                related: ['Software Engineering', 'Web Development', 'UI/UX Design', 'Design Systems'],
                keywords: ['react', 'vue', 'angular', 'tailwind', 'javascript', 'typescript', 'css', 'frontend developer']
            },
            'Mobile App Development': {
                primary: false,
                category: 'Engineering',
                related: ['Software Engineering', 'Frontend Engineering', 'UI/UX Design'],
                keywords: ['ios', 'android', 'flutter', 'react native', 'swift', 'kotlin', 'mobile developer']
            },
            'Product Engineer': {
                primary: false,
                category: 'Engineering',
                related: ['Software Engineering', 'Product Management', 'UI/UX Design', 'Frontend Engineering'],
                keywords: ['product design', 'user centric', 'fullstack', 'fast prototyping', 'feature delivery', 'product engineer']
            },
            'Civil Engineering': {
                primary: false,
                category: 'Engineering',
                related: ['Software Engineering', 'Mechanical Engineering', 'Project Management'],
                keywords: ['civil', 'structures', 'autocad', 'infrastructure', 'construction', 'engineering', 'civil engineer']
            },
            'Mechanical Engineering': {
                primary: false,
                category: 'Engineering',
                related: ['Software Engineering', 'Civil Engineering', 'Product Design'],
                keywords: ['cad', 'solidworks', 'thermodynamics', 'manufacturing', 'robotics', 'hardware', 'mechanical engineer']
            },
            'UI Designer': {
                primary: false,
                category: 'Design',
                related: ['UI/UX Design', 'UX Designer', 'Design Systems', 'Graphic Design'],
                keywords: ['figma', 'visual design', 'typography', 'components', 'colors', 'ui design', 'ui designer']
            },
            'UX Designer': {
                primary: false,
                category: 'Design',
                related: ['UI/UX Design', 'UI Designer', 'UX Research', 'Wireframing & Prototyping'],
                keywords: ['user experience', 'wireframes', 'heuristics', 'flows', 'user empathy', 'ux designer']
            },
            'UX Research': {
                primary: false,
                category: 'Design',
                related: ['UI/UX Design', 'UX Designer', 'Product Management'],
                keywords: ['user interviews', 'usability testing', 'surveys', 'analytics', 'persona creation', 'ux researcher']
            },
            'Product Design': {
                primary: false,
                category: 'Design',
                related: ['UI/UX Design', 'Product Management', 'Design Systems'],
                keywords: ['end-to-end design', 'prototyping', 'figma', 'interaction design', 'product designer']
            },
            'Design Systems': {
                primary: false,
                category: 'Design',
                related: ['UI/UX Design', 'UI Designer', 'Frontend Engineering'],
                keywords: ['tokenization', 'component library', 'figma components', 'accessibility', 'design system']
            },
            'Associate Product Manager': {
                primary: false,
                category: 'Product',
                related: ['Product Management', 'Product Growth', 'UI/UX Design'],
                keywords: ['apm', 'junior pm', 'product backlog', 'user stories', 'feature specs']
            },
            'Technical Product Manager': {
                primary: false,
                category: 'Product',
                related: ['Product Management', 'Software Engineering', 'DevOps'],
                keywords: ['technical specs', 'api documentation', 'architecture', 'engineering alignment']
            },
            'Product Growth': {
                primary: false,
                category: 'Product',
                related: ['Product Management', 'Marketing', 'Data Science'],
                keywords: ['retention', 'funnel conversion', 'a/b testing', 'growth strategy']
            },
            'Data Analyst': {
                primary: false,
                category: 'Data & AI',
                related: ['Data Science', 'Business Intelligence', 'Python / SQL'],
                keywords: ['sql', 'excel', 'power bi', 'dashboards', 'reporting', 'metrics', 'data analyst']
            },
            'Machine Learning Engineer': {
                primary: false,
                category: 'Data & AI',
                related: ['AI/ML', 'Data Science', 'Software Engineering'],
                keywords: ['model training', 'mlops', 'scikit-learn', 'deep learning', 'ml engineer']
            },
            'Business Intelligence': {
                primary: false,
                category: 'Data & AI',
                related: ['Data Science', 'Data Analyst', 'Operations'],
                keywords: ['bi', 'tableau', 'power bi', 'executive reporting', 'etl pipelines']
            },
            'Digital Marketing': {
                primary: false,
                category: 'Marketing',
                related: ['Marketing', 'SEO & SEM', 'Social Media Management'],
                keywords: ['digital campaigns', 'meta ads', 'google search ads', 'performance marketing']
            },
            'SEO & SEM': {
                primary: false,
                category: 'Marketing',
                related: ['Marketing', 'Content Creation', 'Digital Marketing'],
                keywords: ['search engine optimization', 'keywords', 'backlinks', 'organic ranking']
            },
            'Social Media Management': {
                primary: false,
                category: 'Marketing',
                related: ['Marketing', 'Content Creation', 'Graphic Design'],
                keywords: ['instagram', 'tiktok', 'linkedin', 'community management', 'content calendar']
            },
            'Business Development': {
                primary: false,
                category: 'Business',
                related: ['Sales', 'Marketing', 'Operations'],
                keywords: ['partnership', 'lead generation', 'market research', 'outreach', 'biz dev']
            },
            'Account Management': {
                primary: false,
                category: 'Business',
                related: ['Sales', 'Customer Success'],
                keywords: ['client success', 'upselling', 'relationship management', 'customer satisfaction']
            },
            'Financial Analysis': {
                primary: false,
                category: 'Finance',
                related: ['Finance', 'Accounting & Audit'],
                keywords: ['variance analysis', 'forecasting', 'dcf', 'financial statements']
            },
            'Accounting & Audit': {
                primary: false,
                category: 'Finance',
                related: ['Finance', 'Corporate Finance'],
                keywords: ['gaap', 'ifrs', 'general ledger', 'reconciliation', 'auditing']
            },
            'People Operations': {
                primary: false,
                category: 'People',
                related: ['HR', 'Talent Acquisition', 'Operations'],
                keywords: ['hr operations', 'employee experience', 'workplace culture', 'benefits']
            },
            'Talent Acquisition': {
                primary: false,
                category: 'People',
                related: ['HR', 'People Operations'],
                keywords: ['headhunting', 'interviewing', 'job boards', 'recruiter', 'sourcing']
            }
        },
        // Step 6 & 7 UI Controls
        show_interest_dropdown: false,
        foundationalDisciplines: [
            'Software Engineering',
            'UI/UX Design',
            'Product Management',
            'Data Science',
            'AI/ML',
            'Marketing',
            'Sales',
            'Finance',
            'Cybersecurity',
            'Cloud Computing',
            'Graphic Design',
            'HR'
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
            this.resetModals();
            // Always clear stale localStorage drafts on fresh page load.
            // Draft restoration should only happen via server-side session,
            // not client-side localStorage which retains old test data.
            localStorage.removeItem('internconnect_onboarding_draft');

            this.syncInterestKeywords();

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
                interest_keywords: this.interest_keywords,
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

        goBackToStep2() {
            this.resetModals();
            this.clearTimers();
            this.step = 2;
            this.server_errors = [];
            this.saveDraftToLocal();
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

        searchUniversities() {
            clearTimeout(this.university_debounce_timer);
            const query = (this.university || '').trim();
            if (query.length < 2) {
                this.university_results = [];
                this.show_university_dropdown = false;
                this.university_loading = false;
                return;
            }

            this.university_loading = true;
            this.show_university_dropdown = true;

            this.university_debounce_timer = setTimeout(async () => {
                try {
                    const apiUrl = window.INTERN_UNIVERSITIES_API || '/api/universities';
                    const res = await fetch(`${apiUrl}?q=${encodeURIComponent(query)}`);
                    if (res.ok) {
                        const data = await res.json();
                        this.university_results = Array.isArray(data) ? data : (data.data || []);
                    } else {
                        this.university_results = [];
                    }
                } catch (e) {
                    this.university_results = [];
                } finally {
                    this.university_loading = false;
                }
            }, 100);
        },

        selectUniversity(name) {
            this.university = name;
            this.show_university_dropdown = false;
            this.university_results = [];
            this.university_loading = false;
        },

        closeUniversityDropdown() {
            setTimeout(() => {
                this.show_university_dropdown = false;
            }, 200);
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
            // Keep the File outside Alpine's reactive state; it is sent with the final step.
            window.__icOnboardingResume = file;
            this.resume_name = file.name;
            const sizeInMb = (file.size / (1024 * 1024)).toFixed(2);
            this.resume_size = sizeInMb + ' MB';
            this.fileUploaded = true;
            this.server_errors = [];
            this.readResume(file);
        },

        // Read the CV now so the badge reflects a real parse; the result is reused when signup completes.
        async readResume(file) {
            this.cv_state = 'reading';
            this.cv_found = '';
            try {
                const form = new FormData();
                form.append('resume', file);
                const res = await fetch(window.INTERN_CV_PARSE_URL || '/register/student/parse-resume', { method: 'POST', headers: { 'Accept': 'application/json' }, body: form });
                const json = await res.json().catch(() => ({}));
                if (window.__icOnboardingResume !== file) return; // a different file was chosen meanwhile
                if (!res.ok || !json.success) throw new Error(json.error || '');
                const f = json.fields || {};
                const found = [];
                if (f.full_name) found.push(f.full_name);
                if (f.job_title) found.push(f.job_title + (f.company ? ' at ' + f.company : ''));
                if (Array.isArray(f.skills) && f.skills.length) found.push(f.skills.length + ' skills');
                this.cv_found = found.join(' · ');
                this.cv_state = found.length || f.email || f.phone ? 'parsed' : 'failed';
            } catch (e) {
                if (window.__icOnboardingResume === file) this.cv_state = 'failed';
            }
        },

        removeSelectedFile() {
            this.resume_name = '';
            this.resume_size = '';
            this.fileUploaded = false;
            this.cv_state = '';
            this.cv_found = '';
            window.__icOnboardingResume = null;
            if (this.$refs.fileInput) {
                this.$refs.fileInput.value = '';
            }
        },

        // Search results for floating auto-suggest dropdown attached to the search bar
        searchResults() {
            const query = (this.search_query || '').trim().toLowerCase();
            if (!query) return [];

            const results = [];
            for (const [name, meta] of Object.entries(this.interests_taxonomy)) {
                const matchName = name.toLowerCase().includes(query);
                const matchCategory = (meta.category || '').toLowerCase().includes(query);
                const matchKeyword = Array.isArray(meta.keywords) && meta.keywords.some(k => k.toLowerCase().includes(query));

                if (matchName || matchCategory || matchKeyword) {
                    results.push(name);
                }
            }
            return results;
        },

        filteredInterests() {
            return this.foundationalDisciplines;
        },

        toggleInterest(item) {
            if (this.interests.includes(item)) {
                this.interests = this.interests.filter(x => x !== item);
            } else {
                this.interests.push(item);
            }
            this.syncInterestKeywords();
            this.saveDraftToLocal();
        },

        clearInterests() {
            this.interests = [];
            this.interest_keywords = [];
            this.search_query = '';
            this.saveDraftToLocal();
        },

        syncInterestKeywords() {
            const keywordSet = new Set();
            this.interests.forEach(name => {
                const meta = this.interests_taxonomy[name];
                if (meta && Array.isArray(meta.keywords)) {
                    meta.keywords.forEach(kw => keywordSet.add(kw.toLowerCase().trim()));
                }
            });
            this.interest_keywords = Array.from(keywordSet);
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

        // Save every onboarding answer to the candidate profile, then continue to the welcome page.
        async submitStep7() {
            if (this.submitting) return;
            this.submitting = true;
            this.server_errors = [];
            const fallback = (window.INTERN_WELCOME_URL || '/welcome') + '?name=' + encodeURIComponent(this.first_name + ' ' + this.last_name);
            const payload = {
                email: this.email,
                password: this.password,
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
                interests: this.interests,
                work_arrangements: this.work_arrangements,
                location: this.location,
                open_to_relocating: this.open_to_relocating,
                job_suggestions_locations: this.job_suggestions_locations,
                include_remote: this.include_remote
            };
            const form = new FormData();
            form.append('data', JSON.stringify(payload));
            if (this.fileUploaded && window.__icOnboardingResume) {
                form.append('resume', window.__icOnboardingResume);
            }
            try {
                const res = await fetch(window.INTERN_ONBOARD_COMPLETE_URL || '/register/student/complete', {
                    method: 'POST',
                    headers: { 'Accept': 'application/json' },
                    body: form
                });
                const json = await res.json().catch(() => ({}));
                if (!res.ok || !json.success) {
                    this.server_errors = [json.error || 'We could not save your profile. Please try again.'];
                    this.submitting = false;
                    return;
                }
                localStorage.removeItem('internconnect_onboarding_draft');
                window.__icOnboardingResume = null;
                window.location.href = json.redirect || fallback;
            } catch (e) {
                this.server_errors = ['Network error while saving your profile. Please try again.'];
                this.submitting = false;
            }
        }
    };
}
