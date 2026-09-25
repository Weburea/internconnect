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
            'skills'            => ['React', 'TypeScript', 'Figma'],
            'education_level'   => 'Undergraduate',
            'experience_level'  => 'No Experience',
            'start_date'        => '2026-06-01',
            'duration'          => '3 months',
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

            $whatYouWillDo = [];
            if (!empty($_POST['what_you_will_do'])) {
                $whatYouWillDo = array_filter(array_map('trim', explode("\n", $_POST['what_you_will_do'])));
            } else {
                $whatYouWillDo = [
                    'Collaborate with cross-functional teams to define, design, and new features.',
                    'Create wireframes, storyboards, user flows, and site maps to effectively communicate interaction and design ideas.',
                    'Conduct user research and evaluate user feedback to enhance the platform\'s UX.'
                ];
            }

            $whatWeLookFor = [];
            if (!empty($_POST['what_we_look_for'])) {
                $whatWeLookFor = array_filter(array_map('trim', explode("\n", $_POST['what_we_look_for'])));
            } else {
                $whatWeLookFor = [
                    'Demonstrated passion for user-centered digital design and interface prototyping.',
                    'Familiarity with modern design tools (Figma, Adobe XD) and component systems.',
                    'Clear communication and eagerness to learn from senior mentors in a fast-paced environment.'
                ];
            }

            $_SESSION['draft_internship'] = [
                'title'             => trim($_POST['title'] ?? 'Product Design Intern'),
                'category'          => trim($_POST['category'] ?? 'Technology'),
                'internship_type'   => trim($_POST['internship_type'] ?? 'Remote'),
                'location'          => trim($_POST['location'] ?? 'Remote'),
                'about'             => trim($_POST['about'] ?? ''),
                'what_you_will_do'  => $whatYouWillDo,
                'what_we_look_for'  => $whatWeLookFor,
                'skills'            => !empty($skills) ? $skills : ['Figma', 'Prototyping', 'User Research', 'UI Design'],
                'education_level'   => trim($_POST['education_level'] ?? 'Undergraduate'),
                'experience_level'  => trim($_POST['experience_level'] ?? 'No Experience'),
                'start_date'        => trim($_POST['start_date'] ?? '2026-06-01'),
                'duration'          => trim($_POST['duration'] ?? '3 months'),
                'openings'          => (int)($_POST['openings'] ?? 10),
                'stipend_amount'    => trim($_POST['stipend_amount'] ?? '2000'),
                'is_unpaid'         => isset($_POST['is_unpaid']),
                'perks'             => $perks,
                'deadline'          => trim($_POST['deadline'] ?? '2026-05-15'),
                'num_interns'       => (int)($_POST['num_interns'] ?? 10),
                'allow_remote'      => isset($_POST['allow_remote']),
                'interview_type'    => trim($_POST['interview_type'] ?? 'Video Interview (Google Meet)'),
                'screening_questions' => $screeningQuestions,
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
            'title'             => 'Product Design Intern',
            'category'          => 'Technology',
            'internship_type'   => 'Remote',
            'location'          => 'Remote',
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
            'skills'            => ['Figma', 'Prototyping', 'User Research', 'UI Design'],
            'education_level'   => 'Undergraduate',
            'experience_level'  => 'No Experience',
            'start_date'        => '2026-06-01',
            'duration'          => '3 months',
            'openings'          => 10,
            'stipend_amount'    => '3,000 - $4,500/mo',
            'is_unpaid'         => false,
            'deadline'          => 'May 15, 2026',
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
}

