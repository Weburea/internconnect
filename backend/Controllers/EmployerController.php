<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;

/**
 * EmployerController
 *
 * Handles the dashboard view and actions for employer portal.
 */
class EmployerController extends Controller
{
    /**
     * Renders the employer dashboard with detailed mock statistics,
     * review queue, recommended candidates, and activity.
     */
    public function dashboard(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Mock employer profile
        $employer = [
            'company_name' => $_SESSION['employer_company'] ?? 'Global Tech Solutions',
            'role'         => 'Manage Talent',
            'avatar'       => $_SESSION['employer_avatar'] ?? null,
        ];

        // Mock KPIs (Active Posts, Applications, Shortlisted, Interviews, Hired)
        $stats = [
            'active_posts' => 12,
            'applications' => '1,284',
            'shortlisted'  => 48,
            'interviews'   => 15,
            'hired'        => '08',
        ];

        // Mock Review Queue Applicants
        $reviewQueue = [
            [
                'name'        => 'Jordan Smith',
                'role'        => 'UI Designer Intern',
                'applied_date'=> 'Oct 12, 2026',
                'avatar'      => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=100&h=100&fit=crop&crop=faces',
            ],
            [
                'name'        => 'Elena Daniels',
                'role'        => 'Backend Developer',
                'applied_date'=> 'Oct 11, 2026',
                'avatar'      => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=100&h=100&fit=crop&crop=faces',
            ]
        ];

        // Mock AI Recommended Candidates
        $recommendations = [
            [
                'name'       => 'Topper Andrew',
                'role'       => 'CS Senior',
                'match'      => '98% MATCH',
                'tags'       => ['React', 'TypeScript', 'Node.js'],
                'bio'        => '"Passionate about building scalable web applications with a focus on accessibility..."',
                'avatar'     => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=100&h=100&fit=crop&crop=faces',
                'status'     => 'Available Now',
                'status_cls' => 'text-emerald-500 bg-emerald-50 border-emerald-100',
            ],
            [
                'name'       => 'Mark Jonny',
                'role'       => 'Product Design',
                'match'      => '94% MATCH',
                'tags'       => ['Figma', 'UI/UX', 'Prototyping'],
                'bio'        => '"Focused on human-centered design and creating intuitive user journeys for mobile..."',
                'avatar'     => 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=100&h=100&fit=crop&crop=faces',
                'status'     => 'Interviewed',
                'status_cls' => 'text-amber-600 bg-amber-50 border-amber-100',
            ]
        ];

        // Mock Upcoming Interviews
        $interviews = [
            [
                'candidate' => 'Elena Daniels',
                'role'      => 'Data Science - Final Round',
                'day'       => 'Today',
                'time'      => '2:00 PM',
                'live'      => true,
            ],
            [
                'candidate' => 'Kevin Smith',
                'role'      => 'UX Design - Screening',
                'day'       => 'Tomorrow',
                'time'      => '10:30 AM',
                'live'      => false,
            ]
        ];

        // Mock Recent Activity log
        $activity = [
            [
                'title' => 'New application from Sandra Alex',
                'desc'  => 'Applied for Senior UX Designer • 2m ago',
                'type'  => 'candidate',
            ],
            [
                'title' => 'Interview confirmed: Sarah Peters',
                'desc'  => 'Tomorrow at 10:00 AM • 45m ago',
                'type'  => 'interview',
            ],
            [
                'title' => 'Message from Marcus Jordan',
                'desc'  => '"Looking forward to the meeting!" • 2h ago',
                'type'  => 'message',
            ],
            [
                'title' => 'Position "React Lead" closed',
                'desc'  => 'Position filled successfully • 5h ago',
                'type'  => 'position',
            ]
        ];

        $this->renderView('employer/dashboard/index', [
            'title'           => 'Employer Dashboard — InternConnect',
            'employer'        => $employer,
            'stats'           => $stats,
            'reviewQueue'     => $reviewQueue,
            'recommendations' => $recommendations,
            'interviews'      => $interviews,
            'activity'        => $activity,
            'showLoader'      => false,
        ], 'dashboard');
    }

    /**
     * Renders the Company Profile view (White / Light theme layout)
     */
    public function companyProfile(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $departments = $_SESSION['employer_departments'] ?? ['Engineering', 'Product Design', 'Marketing', 'Data Science'];

        $employer = [
            'company_name'     => $_SESSION['employer_company'] ?? 'TechFlow Systems',
            'slogan'           => $_SESSION['employer_slogan'] ?? 'Building innovative digital experiences for the future',
            'cac_number'       => $_SESSION['employer_cac_number'] ?? 'RC-1849204',
            'verified'         => true,
            'industry'         => $_SESSION['employer_industry'] ?? 'Software Development',
            'location'         => $_SESSION['employer_location'] ?? 'Victoria Island, Lagos, Nigeria',
            'size'             => $_SESSION['employer_company_size'] ?? '201–500 employees',
            'website'          => $_SESSION['employer_website'] ?? 'https://techflow.io',
            'website_display'  => 'techflow.io',
            'email'            => $_SESSION['employer_email'] ?? 'careers@techflow.io',
            'phone'            => $_SESSION['employer_phone'] ?? '+234 801 234 5678',
            'logo'             => $_SESSION['employer_logo'] ?? 'https://res.cloudinary.com/weburea/image/upload/v1789424092/internConnect/images/hk_logo.jpg',
            'banner'           => $_SESSION['employer_banner'] ?? 'https://res.cloudinary.com/weburea/image/upload/v1789424121/internConnect/images/hk_banner.png',
            'profile_strength' => 78,
            'role'             => 'Employer Portal',
            'departments'      => $departments,
        ];

        $about = [
            'description' => $_SESSION['employer_description'] ?? 'TechFlow Systems is a leading provider of enterprise software solutions, specializing in cloud-native applications and AI-driven analytics. We empower businesses to streamline their operations and unlock new growth opportunities through cutting-edge technology. Our team is passionate about solving complex problems and delivering exceptional value to our clients worldwide.',
            'tags' => $departments,
        ];

        $culture = [
            'description' => $_SESSION['employer_culture_desc'] ?? 'We believe that a supportive and inclusive environment is key to driving innovation. Our core values guide everything we do, from product development to team collaboration.',
            'tags' => ['Inclusive Environment', 'Collaborative Teams', 'Continuous Learning'],
        ];

        $gallery = [
            'https://images.unsplash.com/photo-1497366216548-37526070297c?w=600&auto=format&fit=crop&q=80',
            'https://images.unsplash.com/photo-1522071820081-009f0129c71c?w=600&auto=format&fit=crop&q=80',
            'https://images.unsplash.com/photo-1531482615713-2afd69097998?w=600&auto=format&fit=crop&q=80',
            'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=600&auto=format&fit=crop&q=80',
        ];

        $reviews = [
            [
                'name'    => 'Alexander Wright',
                'role'    => 'Software Engineering Intern',
                'rating'  => 5,
                'date'    => 'August 2026',
                'comment' => 'Outstanding mentorship and real production impact from week one. The collaborative engineering culture helped me sharpen my full-stack skills tremendously.',
                'avatar'  => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=100&h=100&fit=crop&crop=faces',
            ],
            [
                'name'    => 'Chioma Adeyemi',
                'role'    => 'Product Design Intern',
                'rating'  => 5,
                'date'    => 'July 2026',
                'comment' => 'Working at TechFlow gave me exposure to complex enterprise design systems. The team actively listens to intern ideas and encourages creative problem-solving.',
                'avatar'  => 'https://images.unsplash.com/photo-1517841905240-472988babdf9?w=100&h=100&fit=crop&crop=faces',
            ],
            [
                'name'    => 'David Olatunji',
                'role'    => 'Backend Systems Intern',
                'rating'  => 5,
                'date'    => 'May 2026',
                'comment' => 'Great leadership, supportive peers, and high engineering standards. Highly recommended for students wanting hands-on cloud experience.',
                'avatar'  => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=100&h=100&fit=crop&crop=faces',
            ]
        ];

        $this->renderView('employer/company/index', [
            'title'     => 'TechFlow Systems — Company Profile | InternConnect',
            'employer'  => $employer,
            'about'     => $about,
            'culture'   => $culture,
            'gallery'   => $gallery,
            'reviews'   => $reviews,
        ], 'dashboard');
    }

    /**
     * Renders the Employer Profile & Settings page
     */
    public function profileSettings(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $success = $_SESSION['profile_success'] ?? null;
        unset($_SESSION['profile_success']);

        $employer = [
            'company_name'          => $_SESSION['employer_company'] ?? 'TechFlow Systems',
            'slogan'                => $_SESSION['employer_slogan'] ?? 'Building innovative digital experiences for the future',
            'cac_number'            => $_SESSION['employer_cac_number'] ?? 'RC-1849204',
            'business_doc_name'     => $_SESSION['employer_business_doc_name'] ?? '',
            'business_doc_size'     => $_SESSION['employer_business_doc_size'] ?? '',
            'business_doc_uploaded' => !empty($_SESSION['employer_business_doc_name']),
            'business_doc_url'      => $_SESSION['employer_business_doc_url'] ?? asset('dummy.pdf'),
            'business_doc_verified' => !empty($_SESSION['employer_business_doc_name']),
            'industry'              => $_SESSION['employer_industry'] ?? 'Software Development',
            'company_size'          => $_SESSION['employer_company_size'] ?? '201–500 employees',
            'website'               => $_SESSION['employer_website'] ?? 'https://techflow.io',
            'website_display'       => 'techflow.io',
            'email'                 => $_SESSION['employer_email'] ?? 'careers@techflow.io',
            'google_email'          => 'techflow.official@gmail.com',
            'phone'                 => $_SESSION['employer_phone'] ?? '+234 801 234 5678',
            'location'              => $_SESSION['employer_location'] ?? 'Victoria Island, Lagos, Nigeria',
            'logo'                  => $_SESSION['employer_logo'] ?? 'https://res.cloudinary.com/weburea/image/upload/v1789424092/internConnect/images/hk_logo.jpg',
            'banner'                => $_SESSION['employer_banner'] ?? 'https://res.cloudinary.com/weburea/image/upload/v1789424121/internConnect/images/hk_banner.png',
            'description'           => $_SESSION['employer_description'] ?? 'TechFlow Systems is a leading provider of enterprise software solutions, specializing in cloud-native applications and AI-driven analytics. We empower businesses to streamline their operations and unlock new growth opportunities through cutting-edge technology.',
            'culture_description'   => $_SESSION['employer_culture_desc'] ?? 'We believe that a supportive and inclusive environment is key to driving innovation. Our core values guide everything we do, from product development to team collaboration.',
            'departments'           => $_SESSION['employer_departments'] ?? ['Engineering', 'Product Design', 'Marketing', 'Data Science'],
            'expected_volume'       => $_SESSION['employer_expected_volume'] ?? '6 - 15 Interns',
            'internship_type'       => $_SESSION['employer_internship_type'] ?? 'Paid',
            'notif_applicants'      => $_SESSION['employer_notif_applicants'] ?? true,
            'notif_interviews'      => $_SESSION['employer_notif_interviews'] ?? true,
            'notif_announcements'   => $_SESSION['employer_notif_announcements'] ?? false,
            'active_sessions'       => [
                [
                    'device'   => 'Windows PC',
                    'location' => 'Lagos, Nigeria',
                    'ip'       => '102.89.23.14',
                    'time'     => 'Active Now',
                    'current'  => true,
                    'icon'     => 'desktop',
                ],
                [
                    'device'   => 'MacBook Pro',
                    'location' => 'Lagos, Nigeria',
                    'ip'       => '102.89.23.18',
                    'time'     => '2 hours ago',
                    'current'  => false,
                    'icon'     => 'laptop',
                ],
                [
                    'device'   => 'iPhone 15 Pro',
                    'location' => 'Abuja, Nigeria',
                    'ip'       => '197.210.45.92',
                    'time'     => 'Yesterday at 4:15 PM',
                    'current'  => false,
                    'icon'     => 'mobile',
                ],
            ],
            'role'                  => 'Employer Portal',
        ];

        $this->renderView('employer/profile/index', [
            'title'    => 'Employer Profile & Settings — InternConnect',
            'employer' => $employer,
            'success'  => $success,
        ], 'dashboard');
    }

    /**
     * Handles updating Employer Profile Settings
     */
    public function updateProfileSettings(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $action = $_POST['action'] ?? 'update_profile';

        if ($action === 'update_password') {
            $_SESSION['profile_success'] = 'Password updated successfully!';
        } else {
            if (!empty($_POST['company_name'])) {
                $_SESSION['employer_company'] = trim($_POST['company_name']);
            }
            if (!empty($_POST['slogan'])) {
                $_SESSION['employer_slogan'] = trim($_POST['slogan']);
            }
            if (!empty($_POST['cac_number'])) {
                $_SESSION['employer_cac_number'] = trim($_POST['cac_number']);
            }
            if (!empty($_POST['industry'])) {
                $_SESSION['employer_industry'] = trim($_POST['industry']);
            }
            if (!empty($_POST['company_size'])) {
                $_SESSION['employer_company_size'] = trim($_POST['company_size']);
            }
            if (!empty($_POST['description'])) {
                $_SESSION['employer_description'] = trim($_POST['description']);
            }
            if (!empty($_POST['culture_description'])) {
                $_SESSION['employer_culture_desc'] = trim($_POST['culture_description']);
            }
            if (!empty($_POST['email'])) {
                $_SESSION['employer_email'] = trim($_POST['email']);
            }
            if (!empty($_POST['phone'])) {
                $_SESSION['employer_phone'] = trim($_POST['phone']);
            }
            if (!empty($_POST['website'])) {
                $_SESSION['employer_website'] = trim($_POST['website']);
            }
            if (!empty($_POST['location'])) {
                $_SESSION['employer_location'] = trim($_POST['location']);
            }
            if (isset($_POST['departments'])) {
                $depts = json_decode($_POST['departments'], true);
                if (is_array($depts)) {
                    $_SESSION['employer_departments'] = $depts;
                }
            }
            if (!empty($_POST['expected_volume'])) {
                $_SESSION['employer_expected_volume'] = trim($_POST['expected_volume']);
            }
            if (!empty($_POST['internship_type'])) {
                $_SESSION['employer_internship_type'] = trim($_POST['internship_type']);
            }
            $_SESSION['profile_success'] = 'Employer profile settings updated successfully!';
        }

        $this->redirect('/employer/profile');
    }

    /**
     * Renders the Create New Opportunity form.
     */
    public function createInternship(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $employer = [
            'company_name' => $_SESSION['employer_company'] ?? 'TechFlow System',
            'role'         => 'Manage Talent',
            'avatar'       => $_SESSION['employer_avatar'] ?? null,
        ];

        $draft = $_SESSION['draft_internship'] ?? [
            'title'             => 'Product Design Intern',
            'category'          => 'Technology',
            'internship_type'   => 'Remote',
            'location'          => 'City or Remote',
            'about'             => 'We are looking for a passionate Product Design Intern to join our remote team. You will work closely with our senior designers and product managers to create intuitive, beautiful, and functional interfaces for the InternConnect platform. This is an incredible opportunity to impact a product used by thousands of students and employers globally.',
            'what_you_will_do'  => [
                'Collaborate with cross-functional teams to define, design, and new features.',
                'Create wireframes, storyboards, user flows, and site maps to effectively communicate interaction and design ideas.',
                'Conduct user research and evaluate user feedback to enhance the platform\'s UX.'
            ],
            'what_we_look_for'  => [
                'Demonstrated passion for user-centered digital design and interface prototyping.',
                'Familiarity with modern design tools (Figma, Adobe XD) and component systems.',
                'Clear communication and eagerness to learn from senior mentors in a fast-paced environment.'
            ],
            'responsibilities_title' => 'Key Responsibilities',
            'responsibilities' => [
                'Collaborate with cross-functional teams to define, design, and ship new features.',
                'Create wireframes, storyboards, user flows, and site maps to effectively communicate interaction ideas.',
                'Conduct user research and evaluate candidate/student feedback to enhance the platform experience.',
                'Participate in design critiques and mentor peer contributors in a fast-paced environment.'
            ],
            'looking_for_title' => "What We're Looking For",
            'looking_for_cards' => [
                [
                    'icon' => 'academic-cap',
                    'title' => 'Design / CS Background',
                    'desc' => 'Pursuing or completed a degree in Design, Computer Science, Human-Computer Interaction, or related field.'
                ],
                [
                    'icon' => 'code',
                    'title' => 'Prototyping Skills',
                    'desc' => 'Proficiency with modern design and prototyping tools such as Figma, FigJam, or Adobe XD.'
                ],
                [
                    'icon' => 'users',
                    'title' => 'Team Player',
                    'desc' => 'Comfortable working in collaborative sprints and communicating blockers proactively.'
                ],
                [
                    'icon' => 'target',
                    'title' => 'Problem Solver',
                    'desc' => 'Strong analytical mindset with a passion for crafting intuitive digital experiences.'
                ],
            ],
            'benefits_title' => 'Program Benefits',
            'benefits' => [
                'Competitive Stipend',
                'Remote-first Culture',
                'Learning & Development Budget',
                'Return Offer Potential'
            ],
            'skills'            => ['Figma', 'UI/UX Design', 'Wireframing', 'Prototyping', 'Design Systems'],
            'education_level'   => 'Undergraduate',
            'experience_level'  => 'No Experience',
            'start_date'        => '2026-06-01',
            'duration'          => '2026-08-31',
            'openings'          => 10,
            'stipend_amount'    => '2000',
            'is_unpaid'         => false,
            'perks'             => ['Certificate', 'Letter of Recommendation', 'Flexible work hours'],
            'deadline'          => '2026-05-15',
            'num_interns'       => 10,
            'allow_remote'      => true,
            'interview_type'    => 'Video Interview (Google Meet)',
            'screening_questions' => [
                [
                    'id'    => 1,
                    'text'  => 'Why are you interested in this specific internship role?',
                    'type'  => 'Short Answer'
                ],
                [
                    'id'    => 2,
                    'text'  => 'Share a link to your portfolio or GitHub profile.',
                    'type'  => 'Link / URL'
                ]
            ]
        ];

        $this->renderView('employer/internships/create', [
            'title'     => 'Create New Opportunity — InternConnect',
            'employer'  => $employer,
            'draft'     => $draft,
            'activeNav' => 'post',
        ], 'dashboard');
    }

    /**
     * Stores or previews the internship opportunity draft.
     */
    public function storeInternship(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? 'preview';

            $skills = [];
            if (!empty($_POST['skills'])) {
                $decoded = json_decode($_POST['skills'], true);
                $skills = is_array($decoded) ? $decoded : explode(',', $_POST['skills']);
            }

            $perks = [];
            if (!empty($_POST['perks'])) {
                $perks = is_array($_POST['perks']) ? $_POST['perks'] : json_decode($_POST['perks'], true) ?? [];
            }

            $screeningQuestions = [];
            if (!empty($_POST['screening_questions'])) {
                $decoded = json_decode($_POST['screening_questions'], true);
                if (is_array($decoded)) {
                    $screeningQuestions = $decoded;
                }
            }

            $responsibilities = [];
            if (!empty($_POST['responsibilities'])) {
                $decoded = json_decode($_POST['responsibilities'], true);
                if (is_array($decoded)) {
                    $responsibilities = array_values(array_filter(array_map('trim', $decoded)));
                }
            }
            if (empty($responsibilities)) {
                $responsibilities = [
                    'Collaborate with cross-functional teams to define, design, and ship new features.',
                    'Create wireframes, storyboards, user flows, and site maps to effectively communicate interaction ideas.',
                    'Conduct user research and evaluate candidate/student feedback to enhance the platform experience.',
                    'Participate in design critiques and mentor peer contributors in a fast-paced environment.'
                ];
            }

            $lookingForCards = [];
            if (!empty($_POST['looking_for_cards'])) {
                $decoded = json_decode($_POST['looking_for_cards'], true);
                if (is_array($decoded)) {
                    $lookingForCards = $decoded;
                }
            }
            if (empty($lookingForCards)) {
                $lookingForCards = [
                    [
                        'icon' => 'academic-cap',
                        'title' => 'Design / CS Background',
                        'desc' => 'Pursuing or completed a degree in Design, Computer Science, Human-Computer Interaction, or related field.'
                    ],
                    [
                        'icon' => 'code',
                        'title' => 'Prototyping Skills',
                        'desc' => 'Proficiency with modern design and prototyping tools such as Figma, FigJam, or Adobe XD.'
                    ],
                    [
                        'icon' => 'users',
                        'title' => 'Team Player',
                        'desc' => 'Comfortable working in collaborative sprints and communicating blockers proactively.'
                    ],
                    [
                        'icon' => 'target',
                        'title' => 'Problem Solver',
                        'desc' => 'Strong analytical mindset with a passion for crafting intuitive digital experiences.'
                    ],
                ];
            }

            $benefits = [];
            if (!empty($_POST['benefits'])) {
                $decoded = json_decode($_POST['benefits'], true);
                if (is_array($decoded)) {
                    $benefits = array_values(array_filter(array_map('trim', $decoded)));
                }
            }
            if (empty($benefits)) {
                $benefits = [
                    'Competitive Stipend',
                    'Remote-first Culture',
                    'Learning & Development Budget',
                    'Return Offer Potential'
                ];
            }

            $_SESSION['draft_internship'] = [
                'title'                 => trim($_POST['title'] ?? 'Product Design Intern'),
                'category'              => trim($_POST['category'] ?? 'Technology'),
                'internship_type'       => trim($_POST['internship_type'] ?? 'Remote'),
                'location'              => trim($_POST['location'] ?? 'Remote'),
                'about'                 => trim($_POST['about'] ?? ''),
                'responsibilities_title'=> trim($_POST['responsibilities_title'] ?? 'Key Responsibilities'),
                'responsibilities'      => $responsibilities,
                'what_you_will_do'      => $responsibilities, // Backward compatibility
                'looking_for_title'     => trim($_POST['looking_for_title'] ?? "What We're Looking For"),
                'looking_for_cards'     => $lookingForCards,
                'what_we_look_for'      => array_column($lookingForCards, 'desc'), // Backward compatibility
                'benefits_title'        => trim($_POST['benefits_title'] ?? 'Program Benefits'),
                'benefits'              => $benefits,
                'skills'                => !empty($skills) ? $skills : ['Figma', 'Prototyping', 'User Research', 'UI Design'],
                'education_level'       => trim($_POST['education_level'] ?? 'Undergraduate'),
                'experience_level'      => trim($_POST['experience_level'] ?? 'No Experience'),
                'start_date'            => trim($_POST['start_date'] ?? '2026-06-01'),
                'duration'              => trim($_POST['duration'] ?? '2026-08-31'),
                'openings'              => (int)($_POST['openings'] ?? 10),
                'stipend_amount'        => trim($_POST['stipend_amount'] ?? '2000'),
                'is_unpaid'             => isset($_POST['is_unpaid']),
                'perks'                 => $perks,
                'deadline'              => trim($_POST['deadline'] ?? '2026-05-15'),
                'num_interns'           => (int)($_POST['num_interns'] ?? 10),
                'allow_remote'          => isset($_POST['allow_remote']),
                'interview_type'        => trim($_POST['interview_type'] ?? 'Video Interview (Google Meet)'),
                'screening_questions'   => $screeningQuestions,
            ];

            if ($action === 'publish') {
                $this->redirect('/employer/internships/success');
                return;
            }

            if ($action === 'save_draft') {
                $_SESSION['toast_message'] = 'Internship draft saved successfully.';
                $this->redirect('/employer/internships/create');
                return;
            }

            // Default to review page
            $this->redirect('/employer/internships/review');
            return;
        }

        $this->redirect('/employer/internships/create');
    }

    /**
     * Renders the Review & Publish page.
     */
    public function reviewInternship(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $employer = [
            'company_name' => $_SESSION['employer_company'] ?? 'TechFlow System',
            'role'         => 'Manage Talent',
            'avatar'       => $_SESSION['employer_avatar'] ?? null,
        ];

        $draft = $_SESSION['draft_internship'] ?? [
            'title'                 => 'Product Design Intern',
            'category'              => 'Technology',
            'internship_type'       => 'Remote',
            'location'              => 'Remote',
            'about'                 => 'We are looking for a passionate Product Design Intern to join our remote team. You will work closely with our senior designers and product managers to create intuitive, beautiful, and functional interfaces for the InternConnect platform. This is an incredible opportunity to impact a product used by thousands of students and employers globally.',
            'responsibilities_title'=> 'Key Responsibilities',
            'responsibilities'      => [
                'Collaborate with cross-functional teams to define, design, and ship new features.',
                'Create wireframes, storyboards, user flows, and site maps to effectively communicate interaction ideas.',
                'Conduct user research and evaluate candidate/student feedback to enhance the platform experience.',
                'Participate in design critiques and mentor peer contributors in a fast-paced environment.'
            ],
            'looking_for_title'     => "What We're Looking For",
            'looking_for_cards'     => [
                [
                    'icon' => 'academic-cap',
                    'title' => 'Design / CS Background',
                    'desc' => 'Pursuing or completed a degree in Design, Computer Science, Human-Computer Interaction, or related field.'
                ],
                [
                    'icon' => 'code',
                    'title' => 'Prototyping Skills',
                    'desc' => 'Proficiency with modern design and prototyping tools such as Figma, FigJam, or Adobe XD.'
                ],
                [
                    'icon' => 'users',
                    'title' => 'Team Player',
                    'desc' => 'Comfortable working in collaborative sprints and communicating blockers proactively.'
                ],
                [
                    'icon' => 'target',
                    'title' => 'Problem Solver',
                    'desc' => 'Strong analytical mindset with a passion for crafting intuitive digital experiences.'
                ],
            ],
            'benefits_title'        => 'Program Benefits',
            'benefits'              => [
                'Competitive Stipend',
                'Remote-first Culture',
                'Learning & Development Budget',
                'Return Offer Potential'
            ],
            'skills'                => ['Figma', 'Prototyping', 'User Research', 'UI Design'],
            'education_level'       => 'Undergraduate',
            'experience_level'      => 'No Experience',
            'start_date'            => '2026-06-01',
            'duration'              => '3 months',
            'openings'              => 10,
            'stipend_amount'        => '2,000',
            'is_unpaid'             => false,
            'deadline'              => '2026-05-15',
        ];

        $this->renderView('employer/internships/review', [
            'title'     => 'Review & Publish Internship — InternConnect',
            'employer'  => $employer,
            'draft'     => $draft,
            'activeNav' => 'post',
        ], 'dashboard');
    }

    /**
     * Publishes the internship and redirects to success celebration.
     */
    public function publishInternship(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION['published_internship'] = $_SESSION['draft_internship'] ?? [];
        $this->redirect('/employer/internships/success');
    }

    /**
     * Renders the "Your role is live!" celebration screen.
     */
    public function internshipSuccess(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $employer = [
            'company_name' => $_SESSION['employer_company'] ?? 'TechFlow System',
            'role'         => 'Manage Talent',
            'avatar'       => $_SESSION['employer_avatar'] ?? null,
        ];

        $internship = $_SESSION['published_internship'] ?? ($_SESSION['draft_internship'] ?? [
            'title' => 'Product Design Intern'
        ]);

        $this->renderView('employer/internships/success', [
            'title'      => 'Your Role Is Live! — InternConnect',
            'employer'   => $employer,
            'internship' => $internship,
            'activeNav'  => 'post',
        ], 'dashboard');
    }

    /**
     * Renders the "My Internships" management page (Employer Portal).
     */
    public function myInternships(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $employer = [
            'company_name' => $_SESSION['employer_company'] ?? 'TechFlow System',
            'role'         => 'Manage Talent',
            'avatar'       => $_SESSION['employer_avatar'] ?? null,
        ];

        // Sample / real internships data matching Image 5 specs
        $internships = [
            [
                'id'            => 1,
                'title'         => 'Senior Software Engineer Intern',
                'status'        => 'ACTIVE',
                'department'    => 'Engineering',
                'location_type' => 'Hybrid',
                'posted_at'     => 'Posted Apr 12, 2026',
                'is_draft'      => false,
                'stats'         => [
                    'applicants' => 142,
                    'shortlist'  => 24,
                    'interviews' => 8,
                    'hired'      => 2,
                ],
            ],
            [
                'id'            => 2,
                'title'         => 'Product Designer (UX/UI) Intern',
                'status'        => 'ACTIVE',
                'department'    => 'Design',
                'location_type' => 'Remote',
                'posted_at'     => 'Posted Apr 08, 2026',
                'is_draft'      => false,
                'stats'         => [
                    'applicants' => 86,
                    'shortlist'  => 12,
                    'interviews' => 4,
                    'hired'      => 0,
                ],
            ],
            [
                'id'            => 3,
                'title'         => 'Product Marketing Intern',
                'status'        => 'CLOSED',
                'department'    => 'Marketing',
                'location_type' => 'On-site',
                'posted_at'     => 'Closed Mar 30, 2026',
                'is_draft'      => false,
                'stats'         => [
                    'applicants' => 215,
                    'shortlist'  => 45,
                    'interviews' => 12,
                    'hired'      => 3,
                ],
            ],
            [
                'id'            => 4,
                'title'         => 'Content Strategist Intern (Draft)',
                'status'        => 'DRAFT',
                'department'    => 'Marketing',
                'location_type' => 'Remote',
                'posted_at'     => 'Last saved 2 hours ago',
                'is_draft'      => true,
                'stats'         => null,
            ],
        ];

        $currentFilter = strtolower($_GET['filter'] ?? 'all');
        $validFilters  = ['all', 'active', 'closed', 'draft'];
        if (!in_array($currentFilter, $validFilters, true)) {
            $currentFilter = 'all';
        }

        $this->renderView('employer/internships/index', [
            'title'         => 'My Internships — InternConnect',
            'employer'      => $employer,
            'internships'   => $internships,
            'currentFilter' => $currentFilter,
            'activeNav'     => 'internships',
        ], 'dashboard');
    }

    /**
     * Renders the Applications / Candidates list view
     * Route: /employer/candidates or /employer/applications
     */
    public function candidates(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $employer = [
            'company_name' => $_SESSION['employer_company'] ?? 'Global Tech Solutions',
            'role'         => 'Manage Talent',
            'avatar'       => $_SESSION['employer_avatar'] ?? null,
        ];

        $candidates = $this->getMockCandidates();

        $this->renderView('employer/candidates/index', [
            'title'      => 'Applications — InternConnect',
            'employer'   => $employer,
            'candidates' => $candidates,
            'activeNav'  => 'candidates',
        ], 'dashboard');
    }

    /**
     * Renders the Candidate Profile / Application Review view
     * Route: /employer/candidates/{id}
     */
    public function candidateProfile(array $params = []): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $id = (int)($params['id'] ?? ($_GET['id'] ?? 1));
        $all = $this->getMockCandidates();
        $candidate = null;

        foreach ($all as $c) {
            if ($c['id'] === $id) {
                $candidate = $c;
                break;
            }
        }

        if (!$candidate) {
            $candidate = $all[0];
        }

        $employer = [
            'company_name' => $_SESSION['employer_company'] ?? 'Global Tech Solutions',
            'role'         => 'Manage Talent',
            'avatar'       => $_SESSION['employer_avatar'] ?? null,
        ];

        $this->renderView('employer/candidates/show', [
            'title'      => $candidate['name'] . ' — Application Review | InternConnect',
            'employer'   => $employer,
            'candidate'  => $candidate,
            'activeNav'  => 'candidates',
        ], 'dashboard');
    }

    /**
     * Renders the Interview Scheduler view
     * Route: /employer/interviews/schedule/{id} or /employer/interviews/schedule
     */
    public function scheduleInterview(array $params = []): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $id = (int)($params['id'] ?? ($_GET['id'] ?? 1));
        $all = $this->getMockCandidates();
        $candidate = null;

        foreach ($all as $c) {
            if ($c['id'] === $id) {
                $candidate = $c;
                break;
            }
        }

        if (!$candidate) {
            $candidate = $all[0];
        }

        $employer = [
            'company_name' => $_SESSION['employer_company'] ?? 'Global Tech Solutions',
            'role'         => 'Manage Talent',
            'avatar'       => $_SESSION['employer_avatar'] ?? null,
        ];

        $this->renderView('employer/interviews/schedule', [
            'title'      => 'Schedule Interview — ' . $candidate['name'] . ' | InternConnect',
            'employer'   => $employer,
            'candidate'  => $candidate,
            'activeNav'  => 'interviews',
        ], 'dashboard');
    }

    /**
     * Convenience route for Interviews listing / scheduler
     * Route: /employer/interviews
     */
    public function interviews(array $params = []): void
    {
        $this->scheduleInterview($params);
    }

    /**
     * Mock Candidates dataset matching Applications and Candidate Profile screenshots
     */
    private function getMockCandidates(): array
    {
        return [
            [
                'id'              => 1,
                'name'            => 'Sarah Peters',
                'avatar'          => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=160&h=160&fit=crop&crop=faces',
                'initials'        => 'SP',
                'role'            => 'UX Design Intern',
                'applied_time'    => 'Applied 2d ago',
                'match_score'     => 92,
                'match_label'     => 'Strong UI skills...',
                'status'          => 'Applied',
                'status_type'     => 'applied',
                'student_title'   => 'UI/UX Design Student • Class of 2026 • Lagos, NG',
                'availability'    => 'Immediate Start',
                'relocation'      => 'Open to Relocate',
                'pipeline_stage'  => 'Under Review',
                'summary'         => 'Passionate designer focused on creating intuitive digital experiences. Currently exploring the intersection of accessibility and high-end visual design. Strong foundation in design systems and user-centric research methodologies.',
                'questions'       => [
                    [
                        'q' => 'Why are you interested in Flux Technologies?',
                        'a' => "Flux's commitment to accessible enterprise software aligns perfectly with my thesis project. I've been following your design team's blog on systemic UX for two years."
                    ],
                    [
                        'q' => 'Describe your proudest design project.',
                        'a' => 'Created a community banking app for unbanked youth in Oakland. It focused on ultra-clear visual hierarchy and reduced cognitive load, which increased savings rates by 12% in user testing.'
                    ],
                    [
                        'q' => 'What are your primary learning goals?',
                        'a' => 'I want to master design-to-code handoff workflows and learn how to leverage AI/ML in predicting user navigation friction points.'
                    ]
                ],
                'experience'      => [
                    [
                        'title'   => 'UI Design Intern',
                        'period'  => 'JUN 2026 - AUG 2026',
                        'company' => 'TechFlow Solutions • Remote',
                        'desc'    => 'Led the redesign of the client dashboard, resulting in a 15% increase in user engagement. Collaborated with engineers to implement a new component library.'
                    ],
                    [
                        'title'   => 'Freelance UX Researcher',
                        'period'  => 'JAN 2026 - MAY 2026',
                        'company' => 'Independent • Hybrid',
                        'desc'    => 'Conducted user interviews and usability testing for local startups. Delivered actionable insights through journey maps and wireframes.'
                    ]
                ],
                'documents'       => [
                    'resume_name'   => 'Sarah_Peters_Resume',
                    'portfolio_url' => 'https://sarahpeters.design',
                    'linkedin_url'  => 'https://linkedin.com/in/sarahpeters'
                ],
                'contact'         => [
                    'email' => 'sarahp@university.edu',
                    'phone' => '+1 (415) 555-0198'
                ],
                'education'       => [
                    'degree'     => 'BFA in Interaction Design',
                    'school'     => 'California College of the Arts',
                    'grade'      => 'FIRST CLASS',
                    'gpa'        => 'GPA 3.8/4.0',
                    'graduation' => 'Expected Graduation: May 2027'
                ],
                'skills'          => ['UI/UX', 'Figma', 'Adobe XD', 'AI/ML', 'Marketing', 'React.js', 'Tailwind CSS']
            ],
            [
                'id'              => 2,
                'name'            => 'Michael Chang',
                'avatar'          => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=160&h=160&fit=crop&crop=faces',
                'initials'        => 'MC',
                'role'            => 'UI Design Intern',
                'applied_time'    => 'Applied 5d ago',
                'match_score'     => 88,
                'match_label'     => 'Excellent Match...',
                'status'          => 'Shortlisted',
                'status_type'     => 'shortlisted',
                'student_title'   => 'Visual Design Specialist • Class of 2026 • Lagos, NG',
                'availability'    => 'Immediate Start',
                'relocation'      => 'Open to Relocate',
                'pipeline_stage'  => 'Shortlisted',
                'summary'         => 'Experienced UI/UX designer with a deep passion for minimalist design systems and accessible user interfaces.',
                'questions'       => [
                    ['q' => 'Why are you interested in Flux Technologies?', 'a' => 'Passionate about crafting design systems at scale.'],
                    ['q' => 'Describe your proudest design project.', 'a' => 'Developed an end-to-end design token pipeline for a fintech startup.'],
                    ['q' => 'What are your primary learning goals?', 'a' => 'Enhancing user motion design and micro-interactions.']
                ],
                'experience'      => [
                    [
                        'title'   => 'Visual Designer Intern',
                        'period'  => 'MAY 2026 - AUG 2026',
                        'company' => 'Apex Studios • Remote',
                        'desc'    => 'Created 40+ responsive web components and built cross-browser guidelines.'
                    ]
                ],
                'documents'       => [
                    'resume_name'   => 'Michael_Chang_Resume',
                    'portfolio_url' => 'https://michaelchang.design',
                    'linkedin_url'  => 'https://linkedin.com/in/michaelchang'
                ],
                'contact'         => [
                    'email' => 'mchang@university.edu',
                    'phone' => '+1 (415) 555-0210'
                ],
                'education'       => [
                    'degree'     => 'B.Sc. in Human-Computer Interaction',
                    'school'     => 'University of Lagos',
                    'grade'      => 'FIRST CLASS',
                    'gpa'        => 'GPA 3.9/4.0',
                    'graduation' => 'Expected Graduation: Dec 2026'
                ],
                'skills'          => ['UI Design', 'Figma', 'Prototyping', 'Design Systems', 'CSS']
            ],
            [
                'id'              => 3,
                'name'            => 'Elena Daniels',
                'avatar'          => 'https://images.unsplash.com/photo-1517841905240-472988babdf9?w=160&h=160&fit=crop&crop=faces',
                'initials'        => 'ED',
                'role'            => 'Marketing Intern',
                'applied_time'    => 'Applied 2d ago',
                'match_score'     => 40,
                'match_label'     => 'Poor Fit...',
                'status'          => 'Rejected',
                'status_type'     => 'rejected',
                'student_title'   => 'Marketing & Communications • Class of 2027 • Abuja, NG',
                'availability'    => 'Flexible',
                'relocation'      => 'Not Available',
                'pipeline_stage'  => 'Under Review',
                'summary'         => 'Content marketer and social strategist interested in brand voice and organic social media growth.',
                'questions'       => [
                    ['q' => 'Why are you interested in Flux Technologies?', 'a' => 'Looking to grow within tech marketing.'],
                    ['q' => 'Describe your proudest design project.', 'a' => 'Led social campaigns for university campus club.'],
                    ['q' => 'What are your primary learning goals?', 'a' => 'Data-driven marketing and SEO.']
                ],
                'experience'      => [],
                'documents'       => [
                    'resume_name'   => 'Elena_Daniels_Resume',
                    'portfolio_url' => '#',
                    'linkedin_url'  => '#'
                ],
                'contact'         => [
                    'email' => 'edaniels@university.edu',
                    'phone' => '+234 802 345 6789'
                ],
                'education'       => [
                    'degree'     => 'B.A. in Mass Communication',
                    'school'     => 'University of Abuja',
                    'grade'      => 'SECOND CLASS UPPER',
                    'gpa'        => 'GPA 3.4/4.0',
                    'graduation' => 'Expected Graduation: June 2027'
                ],
                'skills'          => ['Marketing', 'Social Media', 'Copywriting', 'Canva']
            ],
            [
                'id'              => 4,
                'name'            => 'Isabella Dan',
                'avatar'          => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=160&h=160&fit=crop&crop=faces',
                'initials'        => 'ID',
                'role'            => 'Software Engineering Intern',
                'applied_time'    => 'Applied 1week ago',
                'match_score'     => 88,
                'match_label'     => 'Excellent Match...',
                'status'          => 'Shortlisted',
                'status_type'     => 'shortlisted',
                'student_title'   => 'Computer Science Senior • Class of 2026 • Port Harcourt, NG',
                'availability'    => 'Immediate Start',
                'relocation'      => 'Open to Relocate',
                'pipeline_stage'  => 'Shortlisted',
                'summary'         => 'Full-stack developer passionate about high-throughput APIs, clean architecture, and modern JavaScript frameworks.',
                'questions'       => [
                    ['q' => 'Why are you interested in Flux Technologies?', 'a' => 'Great engineering challenges and cloud infrastructure.'],
                    ['q' => 'Describe your proudest design project.', 'a' => 'Built an open-source real-time markdown editor with WebSockets.'],
                    ['q' => 'What are your primary learning goals?', 'a' => 'Distributed systems and cloud deployment.']
                ],
                'experience'      => [
                    [
                        'title'   => 'Frontend Engineer Intern',
                        'period'  => 'JAN 2026 - MAY 2026',
                        'company' => 'CloudScale • Remote',
                        'desc'    => 'Engineered real-time dashboard analytics using React and TypeScript.'
                    ]
                ],
                'documents'       => [
                    'resume_name'   => 'Isabella_Dan_Resume',
                    'portfolio_url' => 'https://isabelladan.dev',
                    'linkedin_url'  => 'https://linkedin.com/in/isabelladan'
                ],
                'contact'         => [
                    'email' => 'isabella.dan@techuni.edu',
                    'phone' => '+234 812 345 6789'
                ],
                'education'       => [
                    'degree'     => 'B.Sc. in Computer Science',
                    'school'     => 'University of Port Harcourt',
                    'grade'      => 'FIRST CLASS',
                    'gpa'        => 'GPA 3.85/4.0',
                    'graduation' => 'Expected Graduation: July 2026'
                ],
                'skills'          => ['React.js', 'Node.js', 'TypeScript', 'PostgreSQL', 'Tailwind CSS', 'Docker']
            ],
            [
                'id'              => 5,
                'name'            => 'Peter John',
                'avatar'          => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=160&h=160&fit=crop&crop=faces',
                'initials'        => 'PJ',
                'role'            => 'Social Media Manager Intern',
                'applied_time'    => 'Applied 1d ago',
                'match_score'     => 30,
                'match_label'     => 'Poor Fit...',
                'status'          => 'Rejected',
                'status_type'     => 'rejected',
                'student_title'   => 'Media Studies • Class of 2027 • Lagos, NG',
                'availability'    => 'Immediate Start',
                'relocation'      => 'Not Available',
                'pipeline_stage'  => 'Under Review',
                'summary'         => 'Enthusiastic creator focused on short-form video production and social media audience engagement.',
                'questions'       => [
                    ['q' => 'Why are you interested in Flux Technologies?', 'a' => 'Looking to learn social media strategy.'],
                    ['q' => 'Describe your proudest design project.', 'a' => 'Managed TikTok account reaching 10k followers.'],
                    ['q' => 'What are your primary learning goals?', 'a' => 'Content strategy and paid acquisition.']
                ],
                'experience'      => [],
                'documents'       => [
                    'resume_name'   => 'Peter_John_Resume',
                    'portfolio_url' => '#',
                    'linkedin_url'  => '#'
                ],
                'contact'         => [
                    'email' => 'peter.j@college.edu',
                    'phone' => '+234 701 234 5678'
                ],
                'education'       => [
                    'degree'     => 'Diploma in Creative Arts',
                    'school'     => 'Yaba College of Technology',
                    'grade'      => 'UPPER CREDIT',
                    'gpa'        => 'GPA 3.2/4.0',
                    'graduation' => 'Expected Graduation: Aug 2027'
                ],
                'skills'          => ['Social Media', 'Video Editing', 'Content Creation']
            ],
            [
                'id'              => 6,
                'name'            => 'Topper Andrew',
                'avatar'          => 'https://images.unsplash.com/photo-1522075469751-3a6694fb2f61?w=160&h=160&fit=crop&crop=faces',
                'initials'        => 'TA',
                'role'            => 'Software Engineering Intern',
                'applied_time'    => 'Applied 5d ago',
                'match_score'     => 88,
                'match_label'     => 'Excellent Match...',
                'status'          => 'Shortlisted',
                'status_type'     => 'shortlisted',
                'student_title'   => 'Software Engineering • Class of 2026 • Ibadan, NG',
                'availability'    => 'Immediate Start',
                'relocation'      => 'Open to Relocate',
                'pipeline_stage'  => 'Shortlisted',
                'summary'         => 'Backend-focused software engineering student with extensive hands-on experience building REST APIs with Go and PHP.',
                'questions'       => [
                    ['q' => 'Why are you interested in Flux Technologies?', 'a' => 'Impressed by the architecture and scalable services.'],
                    ['q' => 'Describe your proudest design project.', 'a' => 'Architected a payment webhook processor handling 500 req/sec.'],
                    ['q' => 'What are your primary learning goals?', 'a' => 'Cloud-native services and microservice orchestration.']
                ],
                'experience'      => [
                    [
                        'title'   => 'Backend Developer Intern',
                        'period'  => 'MAR 2026 - JUN 2026',
                        'company' => 'CodeBridge NG • Remote',
                        'desc'    => 'Designed relational database schemas and automated CI/CD pipeline tests.'
                    ]
                ],
                'documents'       => [
                    'resume_name'   => 'Topper_Andrew_Resume',
                    'portfolio_url' => 'https://topperandrew.dev',
                    'linkedin_url'  => 'https://linkedin.com/in/topperandrew'
                ],
                'contact'         => [
                    'email' => 'topper.a@uni-ibadan.edu',
                    'phone' => '+234 813 456 7890'
                ],
                'education'       => [
                    'degree'     => 'B.Sc. in Computer Engineering',
                    'school'     => 'University of Ibadan',
                    'grade'      => 'FIRST CLASS',
                    'gpa'        => 'GPA 3.9/4.0',
                    'graduation' => 'Expected Graduation: Nov 2026'
                ],
                'skills'          => ['Go', 'PHP', 'MySQL', 'REST APIs', 'Git', 'Linux']
            ],
            [
                'id'              => 7,
                'name'            => 'Sarah Peters',
                'avatar'          => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=160&h=160&fit=crop&crop=faces',
                'initials'        => 'SP',
                'role'            => 'UX Design Intern',
                'applied_time'    => 'Applied 2d ago',
                'match_score'     => 95,
                'match_label'     => 'Perfect match...',
                'status'          => 'Interview Scheduled',
                'status_type'     => 'scheduled',
                'student_title'   => 'UI/UX Design Student • Class of 2026 • Lagos, NG',
                'availability'    => 'Immediate Start',
                'relocation'      => 'Open to Relocate',
                'pipeline_stage'  => 'Interviewing',
                'summary'         => 'Passionate designer focused on creating intuitive digital experiences. Currently exploring the intersection of accessibility and high-end visual design.',
                'questions'       => [
                    ['q' => 'Why are you interested in Flux Technologies?', 'a' => "Flux's commitment to accessible enterprise software aligns perfectly with my thesis project."],
                    ['q' => 'Describe your proudest design project.', 'a' => 'Created a community banking app for unbanked youth in Oakland.'],
                    ['q' => 'What are your primary learning goals?', 'a' => 'I want to master design-to-code handoff workflows and learn how to leverage AI/ML.']
                ],
                'experience'      => [
                    [
                        'title'   => 'UI Design Intern',
                        'period'  => 'JUN 2026 - AUG 2026',
                        'company' => 'TechFlow Solutions • Remote',
                        'desc'    => 'Led the redesign of the client dashboard, resulting in a 15% increase in user engagement.'
                    ]
                ],
                'documents'       => [
                    'resume_name'   => 'Sarah_Peters_Resume',
                    'portfolio_url' => 'https://sarahpeters.design',
                    'linkedin_url'  => 'https://linkedin.com/in/sarahpeters'
                ],
                'contact'         => [
                    'email' => 'sarahp@university.edu',
                    'phone' => '+1 (415) 555-0198'
                ],
                'education'       => [
                    'degree'     => 'BFA in Interaction Design',
                    'school'     => 'California College of the Arts',
                    'grade'      => 'FIRST CLASS',
                    'gpa'        => 'GPA 3.8/4.0',
                    'graduation' => 'Expected Graduation: May 2027'
                ],
                'skills'          => ['UI/UX', 'Figma', 'Adobe XD', 'AI/ML', 'Marketing', 'React.js', 'Tailwind CSS']
            ]
        ];
    }
}

