<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Services\HelpService;
use App\Services\AiService;
use App\Services\CommunicationService;
use App\Services\UploadStorage;
use App\Services\VideoInterviewService;

/**
 * CandidateController
 *
 * Handles all candidate-facing actions including internship application
 * submission and draft persistence.
 *
 * Currently operates in mock mode (no database). Backend integration points
 * are clearly marked with TODO comments for future implementation.
 */
class CandidateController extends Controller
{
    /**
     * Handle POST submission of an internship application.
     *
     * Validates the request, processes the uploaded resume file (mock),
     * and renders a success confirmation view.
     *
     * @param array $params Route parameters, expects 'slug' key.
     */
    public function submitApplication(array $params = []): void
    {
        // --- Security: Only accept POST requests ---
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo 'Method Not Allowed';
            return;
        }

        $slug = $params['slug'] ?? 'software-engineer-intern';

        // --- Input Sanitization ---
        $phone          = trim(strip_tags($_POST['phone'] ?? ''));
        $jobTitle       = trim(strip_tags($_POST['job_title'] ?? ''));
        $company        = trim(strip_tags($_POST['experience_company'] ?? ''));
        $salary         = trim(strip_tags($_POST['salary_expectation'] ?? ''));
        $coverLetter    = trim(strip_tags($_POST['cover_letter'] ?? ''));
        $interestReason = trim(strip_tags($_POST['interest_reason'] ?? ''));
        $designProcess  = trim(strip_tags($_POST['design_process'] ?? ''));
        $learningGoals  = trim(strip_tags($_POST['learning_goals'] ?? ''));
        $figmaLink      = trim(strip_tags($_POST['figma_link'] ?? ''));
        $portfolioLink  = trim(strip_tags($_POST['portfolio_link'] ?? ''));

        // --- Validation ---
        $errors = [];

        if (empty($phone)) {
            $errors[] = 'Phone number is required.';
        }

        if (empty($jobTitle)) {
            $errors[] = 'Job title is required.';
        }

        if (empty($company)) {
            $errors[] = 'Company name is required.';
        }

        if (empty($salary)) {
            $errors[] = 'Salary expectation is required.';
        }

        if (mb_strlen($coverLetter) > 2000) {
            $errors[] = 'Cover letter must not exceed 2,000 characters.';
        }

        // Resume file validation
        $resumeUploaded = isset($_FILES['resume']) && $_FILES['resume']['error'] === UPLOAD_ERR_OK;

        if (!$resumeUploaded) {
            $errors[] = 'A resume file is required.';
        } else {
            $allowedExtensions = ['pdf', 'docx', 'rtf'];
            $originalName      = $_FILES['resume']['name'] ?? '';
            $ext               = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
            $fileSize          = $_FILES['resume']['size'] ?? 0;

            if (!in_array($ext, $allowedExtensions, true)) {
                $errors[] = 'Unsupported file type. Please upload a PDF, DOCX, or RTF file.';
            }

            if ($fileSize > 5 * 1024 * 1024) {
                $errors[] = 'Resume file must be under 5MB.';
            }
        }

        // Portfolio piece validation (optional)
        $portfolioUploaded = isset($_FILES['portfolio_piece']) && $_FILES['portfolio_piece']['error'] === UPLOAD_ERR_OK;
        if ($portfolioUploaded) {
            $allowedPortfolioExtensions = ['pdf', 'png', 'jpg', 'jpeg'];
            $portfolioOriginalName      = $_FILES['portfolio_piece']['name'] ?? '';
            $portfolioExt               = strtolower(pathinfo($portfolioOriginalName, PATHINFO_EXTENSION));
            $portfolioFileSize          = $_FILES['portfolio_piece']['size'] ?? 0;

            if (!in_array($portfolioExt, $allowedPortfolioExtensions, true)) {
                $errors[] = 'Unsupported portfolio file type. Please upload a PDF, PNG, or JPG file.';
            }

            if ($portfolioFileSize > 10 * 1024 * 1024) {
                $errors[] = 'Portfolio file must be under 10MB.';
            }
        }

        // --- If errors, redirect back (mock: render error view) ---
        if (!empty($errors)) {
            // Find correct internship details
            $internships = [
                'senior-product-design-intern' => [
                    'id'      => 'senior-product-design-intern',
                    'slug'    => 'senior-product-design-intern',
                    'title'   => 'Senior Product Design Intern',
                    'company' => 'WellSpring Care',
                ],
                'software-engineer-intern' => [
                    'id'      => 'software-engineer-intern',
                    'slug'    => 'software-engineer-intern',
                    'title'   => 'Software Engineering Internship',
                    'company' => 'Nexora',
                ],
            ];
            $internship = $internships[$slug] ?? $internships['software-engineer-intern'];

            $this->renderView('candidate/application', [
                'title'      => 'Apply to ' . $internship['company'] . ' — InternConnect',
                'internship' => $internship,
                'slug'       => $slug,
                'errors'     => $errors,
                'oldInput'   => [
                    'phone' => $phone,
                    'job_title' => $jobTitle,
                    'experience_company' => $company,
                    'salary_expectation' => $salary,
                    'cover_letter' => $coverLetter,
                    'interest_reason' => $interestReason,
                    'design_process' => $designProcess,
                    'learning_goals' => $learningGoals,
                    'portfolio_link' => $portfolioLink,
                    'figma_link' => $figmaLink,
                ]
            ]);
            return;
        }

        // --- Mock Processing (replace with real DB + file storage logic) ---
        // TODO: Move uploaded file to secure storage path (e.g. storage/resumes/)
        // TODO: Parse resume metadata via AI service (09-ai-system.md)
        // TODO: Insert application record into `applications` table (see sql/)
        // TODO: Send confirmation email to candidate (13-notification-system.md)

        $applicationData = [
            'slug'            => $slug,
            'phone'           => $phone,
            'cover_letter'    => $coverLetter,
            'resume_name'     => $resumeUploaded ? ($_FILES['resume']['name'] ?? '') : '',
            'interest_reason' => $interestReason,
            'design_process'  => $designProcess,
            'learning_goals'  => $learningGoals,
            'portfolio_name'  => $portfolioUploaded ? ($_FILES['portfolio_piece']['name'] ?? '') : '',
            'portfolio_link'  => $portfolioLink,
            'figma_link'      => $figmaLink,
            'submitted_at'    => date('Y-m-d H:i:s'),
            'status'          => 'submitted', // pending → submitted → reviewed → interviewing
        ];

        // Find correct internship details
        $internships = [
            'senior-product-design-intern' => [
                'id'      => 'senior-product-design-intern',
                'slug'    => 'senior-product-design-intern',
                'title'   => 'Senior Product Design Intern',
                'company' => 'WellSpring Care',
            ],
            'software-engineer-intern' => [
                'id'      => 'software-engineer-intern',
                'slug'    => 'software-engineer-intern',
                'title'   => 'Software Engineering Internship',
                'company' => 'Nexora',
            ],
        ];
        $internship = $internships[$slug] ?? $internships['software-engineer-intern'];

        // Render success confirmation
        $this->renderView('candidate/application-success', [
            'title'           => 'Application Submitted — InternConnect',
            'applicationData' => $applicationData,
            'internship'      => $internship,
        ]);
    }

    /**
     * Candidate Dashboard
     * Renders the main dashboard view with mock data.
     */
    public function dashboard(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $uri = strtok($_SERVER['REQUEST_URI'] ?? '', '?');
        if (str_ends_with($uri, '/dashboard') && !str_ends_with($uri, '/candidate/dashboard') && !str_ends_with($uri, '/employer/dashboard')) {
            header('Location: ' . url('/candidate/dashboard'), true, 302);
            exit;
        }

        $candidateIconSvg = '<svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zm-4 7a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>';

        $candidate = [
            'name'        => $_SESSION['profile_name'] ?? 'Sarah Peters',
            'role'        => $_SESSION['profile_role'] ?? 'Student Applicant',
            'avatar'      => $_SESSION['profile_avatar'] ?? asset('assets/images/profile.png'),
            'profile_pct' => 100,
            'is_pro'      => false,
            'checklist'   => [
                ['label' => 'Upload Resume',       'done' => true],
                ['label' => 'Technical Skills',    'done' => true],
                ['label' => 'Education Details',   'done' => true],
                ['label' => 'Experience History',  'done' => true],
            ],
        ];

        $stats = [
            [
                'label'      => 'Total Applications',
                'value'      => 24,
                'badge'      => '+2',
                'badge_type' => 'up',
                'desc'       => '',
                'color'      => 'blue',
                'icon'       => '<svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>',
            ],
            [
                'label'      => 'Accepted',
                'value'      => 8,
                'badge'      => '+12%',
                'badge_type' => 'up',
                'desc'       => 'View details and next steps for your offers.',
                'color'      => 'green',
                'icon'       => '<svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
            ],
            [
                'label'      => 'Rejected',
                'value'      => 4,
                'badge'      => 'Steady',
                'badge_type' => 'steady',
                'desc'       => 'Keep trying! New opportunities are added daily.',
                'color'      => 'red',
                'icon'       => '<svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
            ],
        ];

        $recommendations = [
            [
                'title'       => 'Software Engineer Intern',
                'category'    => 'ENGINEERING',
                'company'     => 'Nexura',
                'company_slug'=> 'nexura',
                'logo_bg'     => 'background: #000000; color: #FFFFFF;',
                'initial'     => 'N',
                'logo_url'    => null,
                'location'    => 'Lagos, NG',
                'commitment'  => 'Full-time',
                'arrangement' => 'REMOTE',
                'description' => 'Join our payments infrastructure team to build the foundation of global commerce. You\'ll work on high-scale systems.',
                'salary'      => '$5,000/mo',
                'slug'        => 'software-engineer-intern',
            ],
            [
                'title'       => 'Product Design Intern',
                'category'    => 'DESIGN',
                'company'     => 'Airbnb',
                'company_slug'=> 'airbnb',
                'logo_bg'     => 'background: #FFE4E6; color: #E11D48;',
                'initial'     => 'A',
                'logo_url'    => null,
                'location'    => 'Lagos, NG',
                'commitment'  => 'Full-time',
                'arrangement' => 'HYBRID',
                'description' => 'Craft beautiful, intuitive experiences for travelers and hosts. You\'ll participate in the full design lifecycle.',
                'salary'      => '$4,500/mo',
                'slug'        => 'product-design-intern',
            ],
            [
                'title'       => 'Senior Product Design Intern',
                'category'    => 'PRODUCT',
                'company'     => 'WellSpring Care',
                'company_slug'=> 'wellspring-care',
                'logo_bg'     => 'background: #D1FAE5; color: #059669;',
                'initial'     => 'W',
                'logo_url'    => null,
                'location'    => 'Lagos, NG',
                'commitment'  => 'Full-time',
                'arrangement' => 'HYBRID',
                'description' => 'Shape the future of audio by defining product requirements and working with cross-functional engineering teams.',
                'salary'      => '$4,800/mo',
                'slug'        => 'senior-product-design-intern',
            ],
            [
                'title'       => 'Growth Marketing Intern',
                'category'    => 'MARKETING',
                'company'     => 'BrightWave',
                'company_slug'=> 'brightwave',
                'logo_bg'     => 'background: #FEF3C7; color: #B45309;',
                'initial'     => 'B',
                'logo_url'    => null,
                'location'    => 'Lagos, NG',
                'commitment'  => 'Part-time',
                'arrangement' => 'ON SITE',
                'description' => 'Help us reach more users by running experiments, analyzing data, and crafting compelling narratives.',
                'salary'      => '$3,800/mo',
                'slug'        => 'growth-marketing-intern',
            ],
        ];

        $aiRecommendations = [
            [
                'title'       => 'UX/UI Designer',
                'company'     => 'Kiro Labs',
                'slug'        => 'kiro-labs',
                'location'    => 'Remote / Lagos',
                'logo'        => 'https://res.cloudinary.com/weburea/image/upload/v1790902254/kiro_uhmovc.svg',
                'logo_bg'     => '#000623',
                'description' => 'Design beautiful, intuitive user journeys and high-fidelity interactive prototypes for global web applications.',
                'type'        => 'Full-Time',
                'type_class'  => 'badge-fulltime',
                'category'    => 'Design',
                'experience'  => 'Internship Role',
                'stipend'     => '₦180,000/mo',
            ],
            [
                'title'       => 'Digital Marketing Specialist',
                'company'     => 'Zod Studio',
                'slug'        => 'zod-studio',
                'location'    => 'Hybrid / Abuja',
                'logo'        => 'https://res.cloudinary.com/weburea/image/upload/v1790902252/zod_fb2rqf.svg',
                'logo_bg'     => '#FFFFFF',
                'description' => 'Manage multi-channel campaigns, optimize SEO funnels, and scale organic social engagement across tech communities.',
                'type'        => 'Part-Time',
                'type_class'  => 'badge-parttime',
                'category'    => 'Marketing',
                'experience'  => '0 - 1 Year',
                'stipend'     => '₦130,000/mo',
            ],
            [
                'title'       => 'Backend Developer',
                'company'     => 'Firebase',
                'slug'        => 'firebase',
                'location'    => 'Remote / Nigeria',
                'logo'        => 'https://res.cloudinary.com/weburea/image/upload/v1790902244/firebase-icon_twxbsc.svg',
                'logo_bg'     => '#FFFFFF',
                'description' => 'Build resilient REST APIs, optimize high-throughput database queries, and streamline core infrastructure microservices.',
                'type'        => 'Remote',
                'type_class'  => 'badge-remote',
                'category'    => 'Engineering',
                'experience'  => '1 - 2 Years',
                'stipend'     => '₦220,000/mo',
            ],
        ];

        $applications = [
            ['company' => 'TechCorp Inc.',  'role' => 'Software Engineering Intern', 'date' => 'Oct 12, 2023', 'status' => 'Under Review',  'status_class' => 'review'],
            ['company' => 'DesignHub',      'role' => 'UX/UI Design Intern',          'date' => 'Oct 10, 2023', 'status' => 'Interviewing',  'status_class' => 'interviewing'],
            ['company' => 'FinanceFlow',    'role' => 'Finance Analyst Intern',       'date' => 'Oct 08, 2023', 'status' => 'Accepted',      'status_class' => 'accepted'],
            ['company' => 'Global Pulse Media', 'role' => 'Digital Marketing',    'date' => 'Oct 05, 2023', 'status' => 'Rejected',      'status_class' => 'rejected'],
            ['company' => 'Uber',           'role' => 'Frontend Intern',              'date' => 'Oct 01, 2023', 'status' => 'Under Review',  'status_class' => 'review'],
            ['company' => 'Figma',          'role' => 'Product Design Intern',        'date' => 'Sep 28, 2023', 'status' => 'Interviewing',  'status_class' => 'interviewing'],
        ];

        $alerts = [
            [
                'title' => 'Interview Scheduled',
                'desc'  => 'Slack • Frontend Intern Position',
                'time'  => 'Tomorrow, 10:00 AM',
                'color' => 'blue',
                'icon'  => '<svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>',
            ],
            [
                'title' => 'Application Received',
                'desc'  => 'Your application for Uber was received.',
                'time'  => '2 hours ago',
                'color' => 'green',
                'icon'  => '<svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
            ],
            [
                'title' => 'New Message',
                'desc'  => 'Recruiter from Figma sent you a message.',
                'time'  => '4 hours ago',
                'color' => 'purple',
                'icon'  => '<svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>',
            ],
        ];

        $this->renderView('candidate/dashboard/index', [
            'title'             => 'Dashboard — InternConnect',
            'candidate'         => $candidate,
            'stats'             => $stats,
            'recommendations'   => $recommendations,
            'aiRecommendations' => $aiRecommendations,
            'applications'      => $applications,
            'alerts'            => $alerts,
            'showLoader'        => false,
        ], 'dashboard');
    }

    /**
     * Candidate Saved Internships
     * Renders the saved internships view with mock data.
     */
    public function savedInternships(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $candidate = [
            'name'   => $_SESSION['profile_name'] ?? 'Sarah Peters',
            'role'   => $_SESSION['profile_role'] ?? 'Student Applicant',
            'avatar' => $_SESSION['profile_avatar'] ?? asset('assets/images/profile.png'),
        ];

        $savedInternships = [
            [
                'id'           => 1,
                'title'        => 'Product Design Intern',
                'company'      => 'Google',
                'company_slug' => 'google',
                'location'     => 'Mountain View, CA',
                'slug'         => 'product-design-intern-google',
                'category'     => 'Design',
                'work_mode'    => 'Remote',
                'term'         => 'Summer 2024',
                'stipend'      => '₦200,000/mo',
                'logo'         => 'https://res.cloudinary.com/weburea/image/upload/v1790902241/google-icon_nqold7.svg',
                'logo_bg'      => '#FFFFFF',
                'description'  => 'Design beautiful, intuitive user journeys and high-fidelity interactive prototypes for global web applications.',
            ],
            [
                'id'           => 2,
                'title'        => 'Data Science Fellow',
                'company'      => 'Spotify',
                'company_slug' => 'spotify',
                'location'     => 'New York, NY',
                'slug'         => 'data-science-fellow-spotify',
                'category'     => 'Data Science',
                'work_mode'    => 'Hybrid',
                'term'         => 'Fall 2024',
                'stipend'      => '₦180,000/mo',
                'logo'         => null,
                'logo_svg'     => '<svg viewBox="0 0 24 24" width="28" height="28"><path fill="#1ED760" d="M12 2C6.477 2 2 6.477 2 12s4.477 10 10 10 10-4.477 10-10S17.523 2 12 2zm4.586 14.424c-.18.295-.565.387-.86.207-2.377-1.454-5.37-1.783-8.895-1.02-.336.074-.668-.14-.744-.478-.075-.337.14-.668.478-.744 3.856-.88 7.15-.503 9.814 1.128.295.18.387.563.207.857zm1.225-2.72c-.226.367-.707.487-1.074.26-2.72-1.672-6.87-2.157-10.08-1.182-.413.125-.85-.107-.978-.52-.128-.414.108-.85.52-.977 3.663-1.11 8.234-.57 11.35 1.348.368.226.488.707.262 1.07zm.107-2.81c-3.262-1.937-8.646-2.12-11.76-1.17-.5.152-1.025-.133-1.177-.635-.152-.5.133-1.027.635-1.178 3.585-1.09 9.513-.883 13.264 1.344.45.267.6.846.333 1.296-.267.45-.847.6-1.297.333z"/></svg>',
                'logo_bg'      => '#191414',
                'description'  => 'Clean and model complex operational datasets, build automated dashboards, and assist with predictive candidate matching.',
            ],
            [
                'id'           => 3,
                'title'        => 'Marketing Strategy Intern',
                'company'      => 'Airbnb',
                'company_slug' => 'airbnb',
                'location'     => 'San Francisco, CA',
                'slug'         => 'marketing-strategy-intern-airbnb',
                'category'     => 'Marketing',
                'work_mode'    => 'Remote',
                'term'         => 'Summer 2024',
                'stipend'      => '₦150,000/mo',
                'logo'         => null,
                'logo_svg'     => '<svg viewBox="0 0 32 32" width="28" height="28"><path fill="#FF5A5F" d="M16 1c-2.008 0-3.916 1.183-4.834 3.003L1.524 22.955c-1.025 2.03-.541 4.544 1.157 6.06A7.478 7.478 0 0 0 7.625 31c2.723 0 5.253-1.492 6.643-3.926l1.732-3.033 1.733 3.033C19.122 29.508 21.652 31 24.375 31a7.478 7.478 0 0 0 4.944-1.985c1.698-1.516 2.182-4.03 1.157-6.06L20.834 4.003A5.498 5.498 0 0 0 16 1zm0 2.2c1.233 0 2.39.73 2.946 1.834l9.643 18.95c.579 1.144.3 2.502-.686 3.383A5.278 5.278 0 0 1 24.375 28.8c-1.928 0-3.722-1.057-4.706-2.778l-3.669-6.42a1.102 1.102 0 0 0-1.928 0l-3.67 6.42c-.983 1.72-2.777 2.778-4.705 2.778a5.278 5.278 0 0 1-3.527-1.433c-.987-.88-1.266-2.239-.687-3.383l9.643-18.95c.556-1.104 1.713-1.834 2.946-1.834zm0 6.6c-2.099 0-3.8 1.701-3.8 3.8s1.701 3.8 3.8 3.8 3.8-1.701 3.8-3.8-1.701-3.8-3.8-3.8zm0 2.2c.884 0 1.6.716 1.6 1.6 0 .884-.716 1.6-1.6 1.6a1.602 1.602 0 0 1-1.6-1.6c0-.884.716-1.6 1.6-1.6z"/></svg>',
                'logo_bg'      => '#FFFFFF',
                'description'  => 'Manage multi-channel campaigns, optimize SEO funnels, and scale organic social engagement across tech communities.',
            ],
            [
                'id'           => 4,
                'title'        => 'Software Engineering Intern',
                'company'      => 'Microsoft',
                'company_slug' => 'microsoft',
                'location'     => 'Redmond, WA',
                'slug'         => 'software-engineering-intern-microsoft',
                'category'     => 'Engineering',
                'work_mode'    => 'Hybrid',
                'term'         => 'Winter 2024',
                'stipend'      => '₦220,000/mo',
                'logo'         => null,
                'logo_svg'     => '<svg viewBox="0 0 23 23" width="28" height="28"><path fill="#F25022" d="M0 0h11v11H0z"/><path fill="#7FBA00" d="M12 0h11v11H12z"/><path fill="#00A4EF" d="M0 12h11v11H0z"/><path fill="#FFB900" d="M12 12h11v11H12z"/></svg>',
                'logo_bg'      => '#F8FAFC',
                'description'  => 'Build resilient cloud services, optimize high-throughput distributed database queries, and streamline developer infrastructure.',
            ],
            [
                'id'           => 5,
                'title'        => 'UX/UI Designer',
                'company'      => 'Kiro Labs',
                'company_slug' => 'kiro-labs',
                'location'     => 'Remote / Lagos',
                'slug'         => 'ux-ui-designer-kiro',
                'category'     => 'Design',
                'work_mode'    => 'Remote',
                'term'         => 'Full-Time Role',
                'stipend'      => '₦180,000/mo',
                'logo'         => 'https://res.cloudinary.com/weburea/image/upload/v1790902254/kiro_uhmovc.svg',
                'logo_bg'      => '#000623',
                'description'  => 'Design beautiful, intuitive user journeys and high-fidelity interactive prototypes for global web applications.',
            ],
            [
                'id'           => 6,
                'title'        => 'Backend Developer',
                'company'      => 'Firebase',
                'company_slug' => 'firebase',
                'location'     => 'Remote / Nigeria',
                'slug'         => 'backend-developer-firebase',
                'category'     => 'Engineering',
                'work_mode'    => 'Remote',
                'term'         => '1 - 2 Years',
                'stipend'      => '₦220,000/mo',
                'logo'         => 'https://res.cloudinary.com/weburea/image/upload/v1790902244/firebase-icon_twxbsc.svg',
                'logo_bg'      => '#FFFFFF',
                'description'  => 'Build resilient REST APIs, optimize high-throughput database queries, and streamline core infrastructure microservices.',
            ],
        ];

        $this->renderView('candidate/saved/index', [
            'title'            => 'Saved Internships — InternConnect',
            'candidate'        => $candidate,
            'savedInternships' => $savedInternships,
            'showLoader'       => false,
        ], 'dashboard');
    }

    /**
     * Candidate Profile Settings
     * Renders the profile settings view.
     */
    public function profileSettings(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $fullName = $_SESSION['profile_name'] ?? 'Sarah Peters';
        $parts = explode(' ', trim($fullName), 2);
        $firstName = $_SESSION['profile_first_name'] ?? ($parts[0] ?? 'Sarah');
        $lastName  = $_SESSION['profile_last_name'] ?? ($parts[1] ?? 'Peters');

        $candidate = [
            'name'                  => $fullName,
            'first_name'            => $firstName,
            'last_name'             => $lastName,
            'role'                  => $_SESSION['profile_role'] ?? 'Student Applicant',
            'avatar'                => $_SESSION['profile_avatar'] ?? asset('assets/images/profile.png'),
            'email'                 => $_SESSION['profile_email'] ?? 'sarah.peters@design-intern.com',
            'phone'                 => $_SESSION['profile_phone'] ?? '+1 (555) 012-3456',
            'bio'                   => $_SESSION['profile_bio'] ?? 'Passionate designer focused on creating intuitive digital experiences. Currently exploring the intersection of accessibility and high-end visual design.',
            // Interests & Preferences
            'interests'             => $_SESSION['profile_interests'] ?? ['UI/UX Design', 'Software Engineering', 'Product Management'],
            'work_arrangements'     => $_SESSION['profile_work_arrangements'] ?? ['Remote', 'Hybrid'],
            // Location Preferences (onboarding step 7)
            'location'              => $_SESSION['profile_location'] ?? 'Lagos, Nigeria',
            'open_to_relocating'    => $_SESSION['profile_open_to_relocating'] ?? 'Yes',
            'preferred_locations'   => $_SESSION['profile_preferred_locations'] ?? ['Lagos', 'Abuja'],
            'include_remote'        => $_SESSION['profile_include_remote'] ?? true,
            // Academic Info
            'university'            => $_SESSION['profile_university'] ?? '',
            'graduation_year'       => $_SESSION['profile_graduation_year'] ?? '',
            'degree_type'           => $_SESSION['profile_degree_type'] ?? '',
            'field_of_study'        => $_SESSION['profile_field_of_study'] ?? '',
            'gpa'                   => $_SESSION['profile_gpa'] ?? '',
            // Experience Assets
            'portfolio'             => $_SESSION['profile_portfolio'] ?? '',
            'linkedin'              => $_SESSION['profile_linkedin'] ?? '',
            'github'                => $_SESSION['profile_github'] ?? '',
            // Security & Authentication
            'google_email'          => $_SESSION['profile_google_email'] ?? 'sarah.peters@gmail.com',
            'google_linked'         => $_SESSION['profile_google_linked'] ?? true,
            'active_sessions'       => [
                [
                    'device'   => 'Windows PC',
                    'browser'  => 'Google Chrome 129.0',
                    'ip'       => '102.89.41.112',
                    'location' => 'Lagos, Nigeria',
                    'current'  => true,
                    'time'     => 'Active Now',
                    'icon'     => 'desktop',
                ],
                [
                    'device'   => 'iPhone 15 Pro',
                    'browser'  => 'Mobile Safari 17.4',
                    'ip'       => '102.89.41.115',
                    'location' => 'Lagos, Nigeria',
                    'current'  => false,
                    'time'     => 'Active 2 hours ago',
                    'icon'     => 'mobile',
                ],
                [
                    'device'   => 'MacBook Air M2',
                    'browser'  => 'Safari 17.2',
                    'ip'       => '86.12.98.44',
                    'location' => 'London, UK',
                    'current'  => false,
                    'time'     => 'Active Yesterday',
                    'icon'     => 'laptop',
                ],
                [
                    'device'   => 'iPad Pro 11"',
                    'browser'  => 'Chrome for iOS',
                    'ip'       => '102.89.41.112',
                    'location' => 'Lagos, Nigeria',
                    'current'  => false,
                    'time'     => 'Active 3 days ago',
                    'icon'     => 'tablet',
                ],
            ],
            'notif_recommendations' => $_SESSION['profile_notif_recommendations'] ?? true,
            'notif_status'          => $_SESSION['profile_notif_status'] ?? true,
            'notif_announcements'   => $_SESSION['profile_notif_announcements'] ?? false,
            // Resume stored by UploadStorage (null until uploaded during onboarding or here)
            'resume'                => UploadStorage::describe($_SESSION['profile_resume'] ?? null, url('/candidate/profile/resume')),
            // New email waiting for its 6-digit confirmation code
            'pending_email'         => $_SESSION['profile_pending_email']['email'] ?? null,
            'pending_email_dev_code'=> (($_ENV['APP_ENV'] ?? '') === 'local') ? ($_SESSION['profile_pending_email']['dev_code'] ?? null) : null,
        ];

        $this->renderView('candidate/profile', [

            'title'      => 'Curate Your Identity — InternConnect',
            'candidate'  => $candidate,
            'success'    => $_SESSION['success_msg'] ?? null,
            'error'      => $_SESSION['error_msg'] ?? null,
        ], 'dashboard');

        unset($_SESSION['success_msg'], $_SESSION['error_msg']);
    }

    /**
     * Update Candidate Profile Settings
     */
    public function updateProfileSettings(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? 'profile';

            if ($action === 'update_password') {
                $currentPass = trim($_POST['current_password'] ?? '');
                $newPass     = trim($_POST['new_password'] ?? '');
                $confirmPass = trim($_POST['confirm_password'] ?? '');

                if (empty($currentPass) || empty($newPass) || empty($confirmPass)) {
                    $_SESSION['error_msg'] = 'Please fill in all password fields.';
                } elseif (strlen($newPass) < 8) {
                    $_SESSION['error_msg'] = 'New password must be at least 8 characters long.';
                } elseif ($newPass !== $confirmPass) {
                    $_SESSION['error_msg'] = 'New password and confirmation do not match.';
                } elseif (!empty($_SESSION['profile_password_hash']) && !password_verify($currentPass, $_SESSION['profile_password_hash'])) {
                    $_SESSION['error_msg'] = 'Your current password is incorrect.';
                } elseif (!empty($_SESSION['profile_password_hash']) && password_verify($newPass, $_SESSION['profile_password_hash'])) {
                    $_SESSION['error_msg'] = 'Your new password must be different from the current one.';
                } else {
                    $_SESSION['profile_password_hash'] = password_hash($newPass, PASSWORD_DEFAULT);
                    $_SESSION['success_msg'] = 'Password updated successfully!';
                }
            } elseif ($action === 'verify_email') {
                $pending = $_SESSION['profile_pending_email'] ?? null;
                $code = preg_replace('/\D+/', '', (string)($_POST['email_code'] ?? ''));
                if (!$pending) {
                    $_SESSION['error_msg'] = 'There is no email change waiting for confirmation.';
                } elseif (time() > (int)$pending['expires']) {
                    unset($_SESSION['profile_pending_email']);
                    $_SESSION['error_msg'] = 'That code has expired. Please change your email again to get a new code.';
                } elseif (strlen($code) !== 6 || !hash_equals((string)$pending['code_hash'], hash('sha256', $code))) {
                    $_SESSION['error_msg'] = 'That code is not correct. Please check it and try again.';
                } else {
                    $_SESSION['profile_email'] = $pending['email'];
                    unset($_SESSION['profile_pending_email']);
                    $_SESSION['success_msg'] = 'Email address confirmed and updated.';
                }
            } elseif ($action === 'cancel_email') {
                unset($_SESSION['profile_pending_email']);
                $_SESSION['success_msg'] = 'Email change cancelled.';
            } else {
                $firstName = trim(strip_tags($_POST['first_name'] ?? ''));
                $lastName  = trim(strip_tags($_POST['last_name'] ?? ''));
                if ($firstName !== '' || $lastName !== '') {
                    $_SESSION['profile_first_name'] = $firstName;
                    $_SESSION['profile_last_name']  = $lastName;
                    $_SESSION['profile_name']       = trim($firstName . ' ' . $lastName);
                } elseif (isset($_POST['name'])) {
                    $_SESSION['profile_name'] = trim(strip_tags($_POST['name']));
                    $parts = explode(' ', $_SESSION['profile_name'], 2);
                    $_SESSION['profile_first_name'] = $parts[0] ?? '';
                    $_SESSION['profile_last_name']  = $parts[1] ?? '';
                }

                // Email changes are confirmed with a 6-digit code before they take effect
                $currentEmail = $_SESSION['profile_email'] ?? 'sarah.peters@design-intern.com';
                $postedEmail = strtolower(trim(strip_tags((string)($_POST['email'] ?? $currentEmail))));
                $emailNotice = '';
                if ($postedEmail !== '' && $postedEmail !== strtolower($currentEmail)) {
                    if (!filter_var($postedEmail, FILTER_VALIDATE_EMAIL)) {
                        $emailNotice = ' The new email address is not valid, so it was not changed.';
                    } else {
                        $code = (string)random_int(100000, 999999);
                        $_SESSION['profile_pending_email'] = [
                            'email'     => $postedEmail,
                            'code_hash' => hash('sha256', $code),
                            'expires'   => time() + 15 * 60,
                            'dev_code'  => $code,
                        ];
                        // TODO(backend): send $code to $postedEmail via the mailer instead of exposing it locally.
                        $emailNotice = ' Enter the 6-digit code sent to ' . $postedEmail . ' to confirm your new email.';
                    }
                }
                $_SESSION['profile_role']  = trim(strip_tags($_POST['role'] ?? 'Student Applicant'));
                $_SESSION['profile_phone'] = trim(strip_tags($_POST['phone'] ?? '+1 (555) 012-3456'));
                $_SESSION['profile_bio']   = trim(strip_tags($_POST['bio'] ?? ''));

                if (isset($_POST['avatar']) && !empty($_POST['avatar'])) {
                    $_SESSION['profile_avatar'] = trim(strip_tags($_POST['avatar']));
                } elseif (isset($_POST['remove_avatar']) && $_POST['remove_avatar'] === '1') {
                    $_SESSION['profile_avatar'] = null;
                }

                // Interests & Work Arrangements
                if (isset($_POST['interests'])) {
                    $rawInterests = $_POST['interests'];
                    if (is_string($rawInterests)) {
                        $decoded = json_decode($rawInterests, true);
                        $_SESSION['profile_interests'] = is_array($decoded) ? $decoded : [];
                    } elseif (is_array($rawInterests)) {
                        $_SESSION['profile_interests'] = $rawInterests;
                    }
                }

                if (isset($_POST['work_arrangements'])) {
                    $rawArrangements = $_POST['work_arrangements'];
                    if (is_string($rawArrangements)) {
                        $decoded = json_decode($rawArrangements, true);
                        $_SESSION['profile_work_arrangements'] = is_array($decoded) ? $decoded : [];
                    } elseif (is_array($rawArrangements)) {
                        $_SESSION['profile_work_arrangements'] = $rawArrangements;
                    }
                }

                // Location Preferences
                if (isset($_POST['location'])) {
                    $_SESSION['profile_location'] = trim(strip_tags($_POST['location']));
                }
                if (isset($_POST['open_to_relocating'])) {
                    $_SESSION['profile_open_to_relocating'] = $_POST['open_to_relocating'] === 'No' ? 'No' : 'Yes';
                }
                if (isset($_POST['preferred_locations'])) {
                    $decoded = json_decode((string)$_POST['preferred_locations'], true);
                    $cities = [];
                    foreach (is_array($decoded) ? $decoded : [] as $city) {
                        $city = trim(strip_tags((string)$city));
                        if ($city !== '' && mb_strlen($city) <= 80) {
                            $cities[] = $city;
                        }
                    }
                    $_SESSION['profile_preferred_locations'] = array_values(array_unique(array_slice($cities, 0, 20)));
                }
                if (isset($_POST['include_remote'])) {
                    $_SESSION['profile_include_remote'] = $_POST['include_remote'] === '1';
                }

                // Academic Info
                $_SESSION['profile_university']      = trim(strip_tags($_POST['university'] ?? ''));
                $_SESSION['profile_graduation_year'] = trim(strip_tags($_POST['graduation_year'] ?? ''));
                $_SESSION['profile_degree_type']     = trim(strip_tags($_POST['degree_type'] ?? ''));
                $_SESSION['profile_field_of_study']  = trim(strip_tags($_POST['field_of_study'] ?? ''));
                $_SESSION['profile_gpa']             = trim(strip_tags($_POST['gpa'] ?? ''));

                // Experience Assets
                $_SESSION['profile_portfolio'] = trim(strip_tags($_POST['portfolio'] ?? ''));
                $_SESSION['profile_linkedin']  = trim(strip_tags($_POST['linkedin'] ?? ''));
                $_SESSION['profile_github']    = trim(strip_tags($_POST['github'] ?? ''));

                $_SESSION['profile_notif_recommendations'] = isset($_POST['notif_recommendations']) ? (bool)$_POST['notif_recommendations'] : false;
                $_SESSION['profile_notif_status']          = isset($_POST['notif_status']) ? (bool)$_POST['notif_status'] : false;
                $_SESSION['profile_notif_announcements']   = isset($_POST['notif_announcements']) ? (bool)$_POST['notif_announcements'] : false;

                $_SESSION['success_msg'] = 'Profile settings saved successfully!' . $emailNotice;
            }
        }

        header('Location: ' . url('/candidate/profile'));
        exit;
    }

    /**
     * Upload / replace the candidate resume (AJAX, multipart field `resume`)
     */
    public function uploadResume(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        header('Content-Type: application/json');

        $result = UploadStorage::store($_FILES['resume'] ?? null, 'resume');
        if (!$result['ok']) {
            http_response_code(422);
            echo json_encode(['success' => false, 'error' => $result['error']]);
            exit;
        }

        UploadStorage::delete($_SESSION['profile_resume'] ?? null);
        $_SESSION['profile_resume'] = $result['meta'];

        echo json_encode([
            'success' => true,
            'resume'  => UploadStorage::describe($result['meta'], url('/candidate/profile/resume')),
        ]);
        exit;
    }

    /**
     * Stream the signed-in candidate's resume (private file, never a public URL)
     */
    public function streamResume(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        UploadStorage::stream($_SESSION['profile_resume'] ?? null, isset($_GET['download']));
    }

    /**
     * Remove the stored resume (AJAX)
     */
    public function deleteResume(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        UploadStorage::delete($_SESSION['profile_resume'] ?? null);
        unset($_SESSION['profile_resume']);

        header('Content-Type: application/json');
        echo json_encode(['success' => true]);
        exit;
    }
    /**
     * Candidate My Applications
     * Renders the application journey tracking view.
     */
    public function applications(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $candidate = [
            'name'   => $_SESSION['profile_name'] ?? 'Sarah Peters',
            'role'   => $_SESSION['profile_role'] ?? 'Student Applicant',
            'avatar' => $_SESSION['profile_avatar'] ?? asset('assets/images/profile.png'),
        ];

        $metrics = [
            [
                'title'    => 'Total Applications',
                'value'    => 24,
                'subtitle' => 'All time',
                'icon'     => 'document',
                'bg_color' => '#EEF2FF',
                'color'    => '#4F46E5',
            ],
            [
                'title'    => 'In Progress',
                'value'    => 5,
                'subtitle' => 'Active applications',
                'icon'     => 'clock',
                'bg_color' => '#E0F2FE',
                'color'    => '#0284C7',
            ],
            [
                'title'    => 'Interviews',
                'value'    => 3,
                'subtitle' => 'Upcoming interviews',
                'icon'     => 'calendar',
                'bg_color' => '#F3E8FF',
                'color'    => '#9333EA',
            ],
            [
                'title'    => 'Offers',
                'value'    => 1,
                'subtitle' => 'Offers received',
                'icon'     => 'briefcase',
                'bg_color' => '#DCFCE7',
                'color'    => '#16A34A',
            ],
            [
                'title'    => 'Rejected',
                'value'    => 15,
                'subtitle' => 'Not selected',
                'icon'     => 'x-circle',
                'bg_color' => '#FEE2E2',
                'color'    => '#EF4444',
            ],
        ];

        $tabs = [
            ['id' => 'all',       'label' => 'All',       'count' => 24],
            ['id' => 'applied',   'label' => 'Applied',   'count' => 8],
            ['id' => 'in_review', 'label' => 'In Review', 'count' => 5],
            ['id' => 'interview', 'label' => 'Interview', 'count' => 3],
            ['id' => 'offer',     'label' => 'Offer',     'count' => 1],
            ['id' => 'rejected',  'label' => 'Rejected',  'count' => 7],
        ];

        $urgentBanners = [
            'offer' => [
                'company'     => 'Stellar Systems',
                'title'       => 'Stellar Systems extended an offer!',
                'subtitle'    => 'Action required: Finalize your decision for the Data Analyst role.',
                'deadline'    => 'Respond by Oct 15',
                'action_text' => 'Review Offer',
                'action_url'  => url('/candidate/offer'),
            ],
            'interview' => [
                'company'     => 'Vanguard Design',
                'title'       => 'Upcoming Interview: Vanguard Design',
                'datetime'    => 'Oct 5, 2:00 PM',
                'action_text' => 'View Invite',
                'action_url'  => url('/candidate/interview'),
            ],
        ];

        $applications = [
            [
                'id'            => 1,
                'company'       => 'FinTech Global',
                'verified'      => true,
                'role'          => 'Backend Engineer Intern',
                'tags'          => ['Remote', 'Internship'],
                'status'        => 'Applied',
                'status_type'   => 'applied',
                'current_stage' => 'Application Submitted',
                'stage_type'    => 'applied',
                'next_step'     => 'Wait for feedback',
                'next_step_sub' => '',
                'next_type'     => 'blue',
                'date'          => 'Sep 28, 2024',
                'letter'        => 'F',
                'letter_color'  => '#2563EB',
                'letter_bg'     => '#DBEAFE',
                'listing_url'   => url('/internship/software-engineer-intern'),
            ],
            [
                'id'            => 2,
                'company'       => 'Vanguard Design',
                'verified'      => false,
                'role'          => 'UI/UX Design Intern',
                'tags'          => ['Paid', 'Hybrid'],
                'status'        => 'Interview',
                'status_type'   => 'interview',
                'current_stage' => 'Interview Stage',
                'stage_type'    => 'interview',
                'next_step'     => 'Prepare for interview',
                'next_step_sub' => 'Oct 5, 2024',
                'next_type'     => 'purple',
                'date'          => 'Sep 25, 2024',
                'letter'        => 'V',
                'letter_color'  => '#FFFFFF',
                'letter_bg'     => '#4F46E5',
                'listing_url'   => url('/candidate/interview'),
            ],
            [
                'id'            => 3,
                'company'       => 'Stellar Systems',
                'verified'      => true,
                'role'          => 'Data Analyst Intern',
                'tags'          => ['Paid', 'On-site'],
                'status'        => 'In Review',
                'status_type'   => 'in_review',
                'current_stage' => 'Offer Received',
                'stage_type'    => 'offer',
                'next_step'     => 'Review offer',
                'next_step_sub' => '',
                'next_type'     => 'green',
                'date'          => 'Sep 20, 2024',
                'letter'        => 'S',
                'letter_color'  => '#FFFFFF',
                'letter_bg'     => '#059669',
                'listing_url'   => url('/candidate/offer'),
            ],
            [
                'id'            => 4,
                'company'       => 'CloudScale AI',
                'verified'      => false,
                'role'          => 'Product Marketing Intern',
                'tags'          => ['Remote', 'Internship'],
                'status'        => 'Closed',
                'status_type'   => 'rejected',
                'current_stage' => 'Not Selected',
                'stage_type'    => 'rejected',
                'next_step'     => 'Application closed',
                'next_step_sub' => '',
                'next_type'     => 'gray',
                'date'          => 'Sep 15, 2024',
                'letter'        => 'C',
                'letter_color'  => '#64748B',
                'letter_bg'     => '#E2E8F0',
                'listing_url'   => url('/internship/senior-product-design-intern'),
            ],
            [
                'id'            => 5,
                'company'       => 'Nexus Media',
                'verified'      => true,
                'role'          => 'Content Strategy Intern',
                'tags'          => ['Paid', 'Hybrid'],
                'status'        => 'Review',
                'status_type'   => 'in_review',
                'current_stage' => 'Under Review',
                'stage_type'    => 'review',
                'next_step'     => 'Wait for feedback',
                'next_step_sub' => '',
                'next_type'     => 'amber',
                'date'          => 'Sep 12, 2024',
                'letter'        => 'N',
                'letter_color'  => '#D97706',
                'letter_bg'     => '#FEF3C7',
                'listing_url'   => url('/internship/senior-product-design-intern'),
            ],
            [
                'id'            => 6,
                'company'       => 'BioPath Labs',
                'verified'      => false,
                'role'          => 'Bioinformatics Intern',
                'tags'          => ['Paid', 'On-site'],
                'status'        => 'Applied',
                'status_type'   => 'applied',
                'current_stage' => 'Application Submitted',
                'stage_type'    => 'applied',
                'next_step'     => 'Wait for feedback',
                'next_step_sub' => '',
                'next_type'     => 'blue',
                'date'          => 'Sep 08, 2024',
                'letter'        => 'B',
                'letter_color'  => '#4F46E5',
                'letter_bg'     => '#EEF2FF',
                'listing_url'   => url('/internship/software-engineer-intern'),
            ],
            [
                'id'            => 7,
                'company'       => 'Google',
                'verified'      => true,
                'role'          => 'Product Design Intern',
                'tags'          => ['Remote', 'Paid'],
                'status'        => 'In Review',
                'status_type'   => 'in_review',
                'current_stage' => 'Portfolio Review',
                'stage_type'    => 'review',
                'next_step'     => 'Wait for recruiter',
                'next_step_sub' => '',
                'next_type'     => 'amber',
                'date'          => 'Sep 04, 2024',
                'letter'        => 'G',
                'letter_color'  => '#EA4335',
                'letter_bg'     => '#FEE2E2',
                'listing_url'   => url('/internship/senior-product-design-intern'),
            ],
            [
                'id'            => 8,
                'company'       => 'Microsoft',
                'verified'      => true,
                'role'          => 'Software Engineering Intern',
                'tags'          => ['On-site', 'Paid'],
                'status'        => 'Applied',
                'status_type'   => 'applied',
                'current_stage' => 'Application Submitted',
                'stage_type'    => 'applied',
                'next_step'     => 'Under initial review',
                'next_step_sub' => '',
                'next_type'     => 'blue',
                'date'          => 'Sep 01, 2024',
                'letter'        => 'M',
                'letter_color'  => '#00A4EF',
                'letter_bg'     => '#E0F2FE',
                'listing_url'   => url('/internship/software-engineer-intern'),
            ],
            [
                'id'            => 9,
                'company'       => 'Spotify',
                'verified'      => true,
                'role'          => 'Data Science Fellow',
                'tags'          => ['Hybrid', 'Paid'],
                'status'        => 'Interview',
                'status_type'   => 'interview',
                'current_stage' => 'Technical Screening',
                'stage_type'    => 'interview',
                'next_step'     => 'Live coding session',
                'next_step_sub' => 'Oct 12, 2024',
                'next_type'     => 'purple',
                'date'          => 'Aug 29, 2024',
                'letter'        => 'S',
                'letter_color'  => '#16A34A',
                'letter_bg'     => '#DCFCE7',
                'listing_url'   => url('/candidate/interview'),
            ],
            [
                'id'            => 10,
                'company'       => 'Airbnb',
                'verified'      => true,
                'role'          => 'Marketing Strategy Intern',
                'tags'          => ['Remote'],
                'status'        => 'Closed',
                'status_type'   => 'rejected',
                'current_stage' => 'Position Filled',
                'stage_type'    => 'rejected',
                'next_step'     => 'Application closed',
                'next_step_sub' => '',
                'next_type'     => 'gray',
                'date'          => 'Aug 24, 2024',
                'letter'        => 'A',
                'letter_color'  => '#FF5A5F',
                'letter_bg'     => '#FFE4E6',
                'listing_url'   => url('/internship/senior-product-design-intern'),
            ],
            [
                'id'            => 11,
                'company'       => 'Stripe',
                'verified'      => true,
                'role'          => 'Infrastructure Engineer Intern',
                'tags'          => ['Remote', 'Paid'],
                'status'        => 'Applied',
                'status_type'   => 'applied',
                'current_stage' => 'Application Submitted',
                'stage_type'    => 'applied',
                'next_step'     => 'Wait for feedback',
                'next_step_sub' => '',
                'next_type'     => 'blue',
                'date'          => 'Aug 18, 2024',
                'letter'        => 'S',
                'letter_color'  => '#6366F1',
                'letter_bg'     => '#EEF2FF',
                'listing_url'   => url('/internship/software-engineer-intern'),
            ],
            [
                'id'            => 12,
                'company'       => 'Figma',
                'verified'      => true,
                'role'          => 'Systems Design Intern',
                'tags'          => ['Hybrid', 'Paid'],
                'status'        => 'Applied',
                'status_type'   => 'applied',
                'current_stage' => 'Application Submitted',
                'stage_type'    => 'applied',
                'next_step'     => 'Wait for feedback',
                'next_step_sub' => '',
                'next_type'     => 'blue',
                'date'          => 'Aug 10, 2024',
                'letter'        => 'F',
                'letter_color'  => '#A855F7',
                'letter_bg'     => '#F3E8FF',
                'listing_url'   => url('/internship/senior-product-design-intern'),
            ],
        ];

        $this->renderView('candidate/applications/index', [
            'title'         => 'My Applications — InternConnect',
            'candidate'     => $candidate,
            'metrics'       => $metrics,
            'tabs'          => $tabs,
            'urgentBanners' => $urgentBanners,
            'applications'  => $applications,
            'showLoader'    => false,
        ], 'dashboard');
    }

    /**
     * Candidate View Interview Invite View
     * Route: /candidate/interview, /candidate/interview/invite, /candidate/interview/{slug}
     *
     * @param array $params Route parameters
     */
    public function viewInterviewInvite(array $params = []): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $candidate = [
            'name'   => $_SESSION['profile_name'] ?? 'Sarah Peters',
            'role'   => $_SESSION['profile_role'] ?? 'Student Applicant',
            'avatar' => $_SESSION['profile_avatar'] ?? asset('assets/images/profile.png'),
        ];

        $interview = [
            'company'           => 'Vanguard Design',
            'role'              => 'Product Design Intern',
            'type'              => 'Paid • Remote',
            'status'            => 'Confirmed',
            'letter'            => 'V',
            'letter_bg'         => '#4F46E5',
            'days_remaining'    => 3,
            'hours_remaining'   => 4,
            'minutes_remaining' => 15,
            'meet_link'         => 'https://meet.google.com/abc-defg-hij',
            'meet_code'         => 'meet.google.com/abc-defg-hij',
            'interviewer_name'  => 'Alex Chen',
            'interviewer_role'  => 'Senior Product Designer',
            'format_title'      => 'Portfolio Walkthrough (30m) + Q&A (15m)',
            'date'              => 'Oct 24, 2026',
            'time'              => '11:00 AM - 12:00 PM',
            'platform'          => 'Google Meet',
            'timezone'          => 'GMT+1 (West Africa Standard Time)',

            // What you submitted section (inspired by /apply/senior-product-design-intern)
            'submission'        => [
                'resume_file'      => 'Resume_Sarah_Peters_V2.pdf',
                'resume_size'      => '2.4 MB',
                'portfolio_file'   => 'Portfolio_Case_Study_FinTech.pdf',
                'portfolio_size'   => '8.1 MB',
                'figma_link'       => 'figma.com/@sarahp/vanguard-design-spec',
                'portfolio_url'    => 'sarahpeters.design/work/fintech-case-study',
                'cover_letter'     => "I'm particularly excited about Vanguard's focus on inclusive design systems. I've spent my last semester researching accessible color theory and designing component libraries with high-contrast usability for fintech workflows...",
                'interest_reason'  => 'Vanguard is known for setting the standard in user-centered design and systematic interface architecture.',
                'design_process'   => 'Double Diamond methodology: discover through user interviews, define problem statements, develop rapid wireframes and prototypes, deliver polished tokens.',
                'learning_goals'   => 'Mastering enterprise design tokens and conducting live unmoderated usability benchmarks.'
            ],

            'tips' => [
                [
                    'title' => 'Background Matters',
                    'desc'  => 'Vanguard values organization. Ensure your video background is tidy or use a professional blur.'
                ],
                [
                    'title' => 'Design Reasoning',
                    'desc'  => "Don't just show the result; Alex will ask about the 'Why' behind your layout decisions and user trade-offs."
                ],
                [
                    'title' => 'Prepare Questions',
                    'desc'  => 'Have 2-3 thoughtful questions about the team culture, design ops tooling, or mentorship opportunities.'
                ]
            ]
        ];

        // Merge active scheduled interview if set by employer
        if (!empty($_SESSION['active_upcoming_interview'])) {
            $sessInt = $_SESSION['active_upcoming_interview'];
            if (!empty($sessInt['company_name'])) {
                $interview['company'] = $sessInt['company_name'];
            }
            if (!empty($sessInt['candidate_role'])) {
                $interview['role'] = $sessInt['candidate_role'];
            }
            if (!empty($sessInt['status'])) {
                $interview['status'] = $sessInt['status'];
            }
            if (!empty($sessInt['interview_date'])) {
                $interview['date'] = $sessInt['interview_date'];
            }
            if (!empty($sessInt['interview_time'])) {
                $interview['time'] = $sessInt['interview_time'];
            }
            if (!empty($sessInt['meeting_link'])) {
                $interview['meet_link'] = $sessInt['meeting_link'];
                $interview['meet_code'] = str_replace(['https://', 'http://'], '', $sessInt['meeting_link']);
            }
        }

        $this->renderView('candidate/interview/invite', [
            'title'     => 'Upcoming Interview — Vanguard Design — InternConnect',
            'candidate' => $candidate,
            'interview' => $interview,
        ], 'dashboard');
    }

    /**
     * Candidate Interview Success Guide View
     * Route: /candidate/interview/guide, /interview/guide
     *
     * @param array $params Route parameters
     */
    public function interviewGuide(array $params = []): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $candidate = [
            'name'   => $_SESSION['profile_name'] ?? 'Sarah Peters',
            'role'   => $_SESSION['profile_role'] ?? 'Student Applicant',
            'avatar' => $_SESSION['profile_avatar'] ?? asset('assets/images/profile.png'),
        ];

        $this->renderView('candidate/interview/guide', [
            'title'     => 'Interview Success Playbook — InternConnect',
            'candidate' => $candidate,
        ], 'dashboard');
    }

    /**
     * In-Platform Video Interview Room (Powered by Daily.co)
     * Route: /candidate/interview/room/{id}
     */
    public function interviewRoom(array $params = []): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $interviewId = (int)($params['id'] ?? (isset($_GET['id']) ? (int)$_GET['id'] : 1));
        $room = VideoInterviewService::getRoomDetails($interviewId, 'candidate');

        $this->renderView('candidate/interview/room', [
            'title'     => 'In-Platform Video Interview — ' . $room['title'] . ' | InternConnect',
            'room'      => $room,
            'activeNav' => 'applications',
        ], 'dashboard');
    }

    /**
     * Candidate Review Offer View
     * Displays the official offer letter and job description matching design specs.
     *
     * @param array $params Route parameters
     */
    public function reviewOffer(array $params = []): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $candidate = [
            'name'   => $_SESSION['profile_name'] ?? 'Sarah Peters',
            'role'   => $_SESSION['profile_role'] ?? 'Student Applicant',
            'avatar' => $_SESSION['profile_avatar'] ?? asset('assets/images/profile.png'),
        ];

        $offer = [
            'id'             => 3,
            'company'        => 'FinTech Global',
            'verified'       => true,
            'role'           => 'Backend Engineer Intern',
            'tags'           => ['Remote', 'Internship'],
            'status'         => 'Applied',
            'status_type'    => 'applied',
            'current_stage'  => 'Application Submitted',
            'stage_type'     => 'applied',
            'applied_date'   => 'Sep 28, 2024',
            'letter'         => 'F',
            'letter_color'   => '#2563EB',
            'letter_bg'      => '#DBEAFE',

            // Offer Recipient & Terms
            'candidate_name'      => $candidate['name'],
            'commencement_date'   => 'February 1, 2027',
            'response_deadline'   => 'October 15, 2026',
            'signatory_name'      => 'Akinyele Okoro',
            'signatory_title'     => 'Talent Acquisition Lead, Stellar Systems',

            // Job Description details
            'job_title'      => 'Data Analyst Intern',
            'location'       => 'Remote',
            'type'           => 'Internship',
            'duration'       => '6 months',
            'responsibilities' => [
                'Clean and analyse large datasets to identify trends and insights',
                'Build and maintain dashboards and reports',
                'Work with cross-functional teams to support data-driven decisions',
                'Assist in data visualization using Python, SQL, Tableau and BigQuery',
            ],
            'skills'         => [
                'Python',
                'SQL',
                'Tableau',
                'Google BigQuery',
                'Data Analysis',
                'Problem Solving',
            ],
            'about_company'  => 'Stellar Systems is a technology company focused on building innovative data solutions for real-world problems. We value curiosity, collaboration and continuous learning.',
        ];

        $this->renderView('candidate/offers/review', [
            'title'     => 'Review Offer — Stellar Systems — InternConnect',
            'candidate' => $candidate,
            'offer'     => $offer,
        ], 'dashboard');
    }

    /**
     * Offer Accepted Celebration View
     * Route: /candidate/offer/accepted
     */
    public function offerAccepted(array $params = []): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $candidate = [
            'name'   => $_SESSION['profile_name'] ?? 'Sarah Peters',
            'role'   => $_SESSION['profile_role'] ?? 'Student Applicant',
            'avatar' => $_SESSION['profile_avatar'] ?? asset('assets/images/profile.png'),
        ];

        $offer = [
            'company'           => 'Stellar Systems',
            'role'              => 'Product Design Intern',
            'location'          => 'Lagos, NG',
            'type'              => 'Full-time',
            'start_date'        => 'Feb 01, 2027',
            'commencement_date' => 'February 1, 2027',
            'status'            => 'Placement Secured',
        ];

        $this->renderView('candidate/offers/accepted', [
            'title'     => 'Offer Accepted — Stellar Systems — InternConnect',
            'candidate' => $candidate,
            'offer'     => $offer,
        ], 'dashboard');
    }

    /**
     * Offer Declined Confirmation View
     * Route: /candidate/offer/declined
     */
    public function offerDeclined(array $params = []): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $candidate = [
            'name'   => $_SESSION['profile_name'] ?? 'Sarah Peters',
            'role'   => $_SESSION['profile_role'] ?? 'Student Applicant',
            'avatar' => $_SESSION['profile_avatar'] ?? asset('assets/images/profile.png'),
        ];

        $offer = [
            'company'    => 'Stellar Systems',
            'role'       => 'Product Design Intern',
            'location'   => 'Lagos, NG',
            'type'       => 'Full-time',
            'start_date' => 'Feb 01, 2027',
            'status'     => 'Offer Declined',
        ];

        $this->renderView('candidate/offers/declined', [
            'title'     => 'Offer Declined — Stellar Systems — InternConnect',
            'candidate' => $candidate,
            'offer'     => $offer,
        ], 'dashboard');
    }

    /**
     * Help Center Landing View
     * Route: /help, /candidate/help
     */
    public function help(array $params = []): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // If a search query parameter is present, redirect/handle as search
        $query = trim(strip_tags($_GET['q'] ?? ($_GET['search'] ?? '')));
        if ($query !== '') {
            $this->helpSearch(['query' => $query]);
            return;
        }

        $candidate = [
            'name'   => $_SESSION['profile_name'] ?? 'Sarah Peters',
            'role'   => $_SESSION['profile_role'] ?? 'Student Applicant',
            'avatar' => $_SESSION['profile_avatar'] ?? asset('assets/images/profile.png'),
        ];

        $categories = HelpService::getCategories();
        $popularFaqs = HelpService::getPopularFaqs();

        $this->renderView('candidate/help/index', [
            'title'       => 'Help Center — InternConnect',
            'activeNav'   => 'help',
            'candidate'   => $candidate,
            'categories'  => $categories,
            'popularFaqs' => $popularFaqs,
        ], 'dashboard');
    }

    /**
     * Help Center Search Results View
     * Route: /help/search
     */
    public function helpSearch(array $params = []): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $query = $params['query'] ?? trim(strip_tags($_GET['q'] ?? ($_GET['search'] ?? '')));

        $candidate = [
            'name'   => $_SESSION['profile_name'] ?? 'Sarah Peters',
            'role'   => $_SESSION['profile_role'] ?? 'Student Applicant',
            'avatar' => $_SESSION['profile_avatar'] ?? asset('assets/images/profile.png'),
        ];

        $searchData = HelpService::search($query);

        $this->renderView('candidate/help/search', [
            'title'       => 'Search Results for "' . htmlspecialchars($query) . '" — Help Center',
            'activeNav'   => 'help',
            'candidate'   => $candidate,
            'query'       => $searchData['query'],
            'count'       => $searchData['count'],
            'results'     => $searchData['results'],
        ], 'dashboard');
    }

    /**
     * Help Center Article Detail View
     * Route: /help/article/{slug}, /help/{category}/{slug}
     */
    public function helpArticle(array $params = []): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $slug = $params['slug'] ?? trim(strip_tags($_GET['slug'] ?? 'can-i-edit-an-application-after-submission'));
        $article = HelpService::getArticleBySlug($slug);

        if (!$article) {
            // Default fallback to first article if slug not found
            $all = HelpService::getAllArticles();
            $article = $all['can-i-edit-an-application-after-submission'] ?? reset($all);
        }

        $candidate = [
            'name'   => $_SESSION['profile_name'] ?? 'Sarah Peters',
            'role'   => $_SESSION['profile_role'] ?? 'Student Applicant',
            'avatar' => $_SESSION['profile_avatar'] ?? asset('assets/images/profile.png'),
        ];

        $this->renderView('candidate/help/article', [
            'title'     => ($article['title'] ?? 'Help Article') . ' — InternConnect',
            'activeNav' => 'help',
            'candidate' => $candidate,
            'article'   => $article,
        ], 'dashboard');
    }

    /**
     * Notifications Center View
     * Route: /notifications, /candidate/notifications
     */
    public function notifications(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $uri = strtok($_SERVER['REQUEST_URI'] ?? '', '?');
        if (str_ends_with($uri, '/notifications') && !str_ends_with($uri, '/candidate/notifications')) {
            header('Location: ' . url('/candidate/notifications'), true, 302);
            exit;
        }

        $candidate = [
            'name'   => $_SESSION['profile_name'] ?? 'Sarah Peters',
            'role'   => $_SESSION['profile_role'] ?? 'Student Applicant',
            'avatar' => $_SESSION['profile_avatar'] ?? asset('assets/images/profile.png'),
        ];

        $notifications = CommunicationService::presentNotifications(
            CommunicationService::getNotifications('candidate'),
            'candidate'
        );

        $this->renderView('candidate/notifications/index', [
            'title'          => 'Notifications Center — InternConnect',
            'activeNav'      => 'notifications',
            'candidate'      => $candidate,
            'notifications'  => $notifications,
            'notifCategories'=> CommunicationService::notificationCategories('candidate'),
        ], 'dashboard');
    }

    /**
     * AI Career Assistant Landing Hub
     * Route: /candidate/ai-assistant
     */
    public function aiAssistant(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (isset($_GET['plan'])) {
            $requestedPlan = strtolower(trim((string)$_GET['plan']));
            if (in_array($requestedPlan, ['free', 'pro', 'premium'], true)) {
                $_SESSION['candidate_plan'] = $requestedPlan;
            }
        }

        $candPlan = $_SESSION['candidate_plan'] ?? 'pro';

        // Free plan gate: redirect or prompt upgrade
        if ($candPlan === 'free') {
            header('Location: ' . url('/pricing?plan=pro&ref=ai-assistant'));
            exit;
        }

        $candidate = [
            'name'        => $_SESSION['profile_name'] ?? 'Sarah Peters',
            'first_name'  => 'Sarah',
            'role'        => $_SESSION['profile_role'] ?? 'Student Applicant',
            'avatar'      => $_SESSION['profile_avatar'] ?? asset('assets/images/profile.png'),
            'plan'        => $candPlan,
            'profile_pct' => 100,
        ];

        // AI Matched Internships (Screenshot 1)
        $aiMatchedInternships = [
            [
                'id'          => 1,
                'role'        => 'Backend Engineering Intern',
                'company'     => 'Paystack',
                'stipend'     => '₦200,000/month',
                'location'    => 'Lagos',
                'duration'    => '3 months',
                'match_pct'   => 96,
                'match_reason'=> 'Matches your API development skill',
                'logo_text'   => 'PW',
                'logo_bg'     => '#059669',
                'logo_color'  => '#FFFFFF',
                'slug'        => 'backend-developer-paystack',
            ],
            [
                'id'          => 2,
                'role'        => 'Data Analyst Intern',
                'company'     => 'Andela',
                'stipend'     => '₦150,000/month',
                'location'    => 'Remote',
                'duration'    => '3 months',
                'match_pct'   => 89,
                'match_reason'=> 'Matches your SQL & Python coursework',
                'logo_text'   => 'AN',
                'logo_bg'     => '#2563EB',
                'logo_color'  => '#FFFFFF',
                'slug'        => 'data-analyst-andela',
            ],
        ];

        // Premium Exclusive Features / High-Pay Remote Roles (Screenshot 4: 3-column opportunity cards)
        $premiumRoles = [
            [
                'title'       => 'UX/UI Designer',
                'company'     => 'Kiro Labs',
                'slug'        => 'kiro-labs',
                'location'    => 'Remote / Global',
                'logo'        => 'https://res.cloudinary.com/weburea/image/upload/v1790902254/kiro_uhmovc.svg',
                'logo_bg'     => '#000623',
                'description' => 'Design scalable design systems and high-fidelity interactive user journeys for global enterprise software.',
                'type'        => 'Full-Time',
                'type_class'  => 'badge-fulltime',
                'category'    => 'Design',
                'experience'  => 'Internship Role',
                'stipend'     => '₦350,000/mo ($2,400)',
                'is_premium'  => true,
            ],
            [
                'title'       => 'Digital Marketing Specialist',
                'company'     => 'Zod Studio',
                'slug'        => 'zod-studio',
                'location'    => 'Remote / Global',
                'logo'        => 'https://res.cloudinary.com/weburea/image/upload/v1790902252/zod_fb2rqf.svg',
                'logo_bg'     => '#FFFFFF',
                'description' => 'Scale global organic growth funnels, orchestrate marketing automation, and launch cross-border campaigns.',
                'type'        => 'Part-Time',
                'type_class'  => 'badge-parttime',
                'category'    => 'Marketing',
                'experience'  => '0 - 1 Year',
                'stipend'     => '₦300,000/mo ($2,000)',
                'is_premium'  => true,
            ],
            [
                'title'       => 'Backend Developer',
                'company'     => 'Firebase',
                'slug'        => 'firebase',
                'location'    => 'Remote / Global',
                'logo'        => 'https://res.cloudinary.com/weburea/image/upload/v1790902244/firebase-icon_twxbsc.svg',
                'logo_bg'     => '#FFFFFF',
                'description' => 'Architect high-throughput real-time APIs, distributed event streams, and global serverless functions.',
                'type'        => 'Remote',
                'type_class'  => 'badge-remote',
                'category'    => 'Engineering',
                'experience'  => '1 - 2 Years',
                'stipend'     => '₦480,000/mo ($3,200)',
                'is_premium'  => true,
            ],
        ];

        $this->renderView('candidate/ai-assistant/index', [
            'title'                => 'AI Career Assistant — InternConnect',
            'activeNav'            => 'ai-assistant',
            'candidate'            => $candidate,
            'candPlan'             => $candPlan,
            'aiMatchedInternships' => $aiMatchedInternships,
            'premiumRoles'         => $premiumRoles,
        ], 'dashboard');
    }

    /**
     * AI CV Analyze Setup View
     * Route: /candidate/ai-assistant/cv-analyze
     */
    public function aiCvAnalyze(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (isset($_GET['plan'])) {
            $requestedPlan = strtolower(trim((string)$_GET['plan']));
            if (in_array($requestedPlan, ['free', 'pro', 'premium'], true)) {
                $_SESSION['candidate_plan'] = $requestedPlan;
            }
        }

        $candPlan = $_SESSION['candidate_plan'] ?? 'pro';
        if ($candPlan === 'free') {
            header('Location: ' . url('/pricing?plan=pro&ref=ai-cv-analyze'));
            exit;
        }

        $candidate = [
            'name'       => $_SESSION['profile_name'] ?? 'Sarah Peters',
            'first_name' => 'Sarah',
            'role'       => $_SESSION['profile_role'] ?? 'Student Applicant',
            'avatar'     => $_SESSION['profile_avatar'] ?? asset('assets/images/profile.png'),
            'plan'       => $candPlan,
        ];

        $currentCv = [
            'name'        => 'Sarah_Peters_CV.pdf',
            'uploaded_at' => 'Aug 20, 2026',
            'size'        => '2.4 MB',
        ];

        $targetRoles = [
            'Product Designer',
            'UI/UX Designer',
            'Software Engineer',
            'Frontend Developer',
            'Backend Developer',
            'Data Analyst',
            'Product Manager',
        ];

        $suggestedPills = [
            'Product Design Intern',
            'UX/UI Design Intern',
            'Product Management Intern',
        ];

        $this->renderView('candidate/ai-assistant/cv-analyze', [
            'title'          => 'Analyze Your CV — AI Career Assistant — InternConnect',
            'activeNav'      => 'ai-assistant',
            'candidate'      => $candidate,
            'candPlan'       => $candPlan,
            'currentCv'      => $currentCv,
            'targetRoles'    => $targetRoles,
            'suggestedPills' => $suggestedPills,
        ], 'dashboard');
    }

    /**
     * Process CV Analysis Form
     * Route: POST /candidate/ai-assistant/cv-analyze
     */
    public function processAiCvAnalyze(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $targetRole = trim(strip_tags($_POST['target_role'] ?? 'Product Designer'));
        $opportunity = trim(strip_tags($_POST['opportunity'] ?? ''));

        $cvText = '';
        $fileName = 'Sarah_Peters_CV.pdf';

        // Check if new file uploaded
        if (!empty($_FILES['cv_file']['tmp_name']) && is_uploaded_file($_FILES['cv_file']['tmp_name'])) {
            $uploadedName = $_FILES['cv_file']['name'] ?? 'Uploaded_CV.pdf';
            $ext = (string)pathinfo($uploadedName, PATHINFO_EXTENSION);
            $cvText = AiService::extractCvText($_FILES['cv_file']['tmp_name'], $ext);
            $fileName = $uploadedName;
            $_SESSION['candidate_current_cv'] = [
                'name' => $fileName,
                'uploaded_at' => date('M j, Y'),
                'size' => round($_FILES['cv_file']['size'] / 1024) . ' KB',
            ];
        }

        $candidateProfile = [
            'user_id' => $_SESSION['candidate_id'] ?? 1,
            'name'    => $_SESSION['profile_name'] ?? 'Sarah Peters',
            'skills'  => $_SESSION['profile_skills'] ?? [],
        ];

        $analysis = AiService::analyzeCv($cvText, $targetRole, $opportunity, $candidateProfile);
        $analysis['file_name'] = $fileName;

        $_SESSION['ai_cv_analysis'] = $analysis;

        header('Location: ' . url('/candidate/ai-assistant/cv-results'));
        exit;
    }

    /**
     * AI CV Analysis Results View
     * Route: /candidate/ai-assistant/cv-results
     */
    public function aiCvResults(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (isset($_GET['plan'])) {
            $requestedPlan = strtolower(trim((string)$_GET['plan']));
            if (in_array($requestedPlan, ['free', 'pro', 'premium'], true)) {
                $_SESSION['candidate_plan'] = $requestedPlan;
            }
        }

        $candPlan = $_SESSION['candidate_plan'] ?? 'pro';
        if ($candPlan === 'free') {
            header('Location: ' . url('/pricing?plan=pro&ref=ai-cv-results'));
            exit;
        }

        $candidate = [
            'name'       => $_SESSION['profile_name'] ?? 'Sarah Peters',
            'first_name' => 'Sarah',
            'role'       => $_SESSION['profile_role'] ?? 'Student Applicant',
            'avatar'     => $_SESSION['profile_avatar'] ?? asset('assets/images/profile.png'),
            'plan'       => $candPlan,
        ];

        $analysis = $_SESSION['ai_cv_analysis'] ?? null;
        if ($analysis === null) {
            $candidateProfile = [
                'user_id' => $_SESSION['candidate_id'] ?? 1,
                'name'    => $candidate['name'],
                'skills'  => [],
            ];
            $analysis = AiService::analyzeCv('', 'Product Designer', 'Google, Product Design Intern', $candidateProfile);
            $analysis['file_name'] = 'Sarah_Peters_CV.pdf';
        }

        $this->renderView('candidate/ai-assistant/cv-results', [
            'title'     => 'AI CV Analysis Results — InternConnect',
            'activeNav' => 'ai-assistant',
            'candidate' => $candidate,
            'candPlan'  => $candPlan,
            'analysis'  => $analysis,
        ], 'dashboard');
    }

    /**
     * AI Matched Internships List View
     * Route: /candidate/ai-assistant/matched
     */
    public function aiMatchedInternships(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (isset($_GET['plan'])) {
            $requestedPlan = strtolower(trim((string)$_GET['plan']));
            if (in_array($requestedPlan, ['free', 'pro', 'premium'], true)) {
                $_SESSION['candidate_plan'] = $requestedPlan;
            }
        }

        $candPlan = $_SESSION['candidate_plan'] ?? 'pro';

        // Free plan gate: redirect to pricing
        if ($candPlan === 'free') {
            header('Location: ' . url('/pricing?plan=pro&ref=ai-matched'));
            exit;
        }

        $candidate = [
            'name'       => $_SESSION['user_name'] ?? 'Sarah Peters',
            'first_name' => explode(' ', $_SESSION['user_name'] ?? 'Sarah Peters')[0],
            'email'      => $_SESSION['user_email'] ?? 'sarah.peters@university.edu.ng',
            'role'       => 'Student Applicant',
            'university' => 'University of Lagos (UNILAG)',
            'course'     => 'Computer Science & Human-Computer Interaction',
            'year'       => 'Final Year (400L)',
            'plan'       => $candPlan,
        ];

        $matchedInternships = [
            [
                'id'            => 1,
                'role'          => 'Product Design Intern',
                'company'       => 'Flutterwave',
                'initials'      => 'FW',
                'logo_bg'       => '#4F46E5',
                'stipend'       => '₦150,000/month',
                'location'      => 'Lagos',
                'duration'      => '3 months',
                'match_score'   => 96,
                'match_tier'    => 'Excellent Match',
                'match_reason'  => 'Matches your product design experience and Figma skills.',
                'skills'        => ['Figma', 'UX Research', 'Prototyping', 'Product Design'],
                'potential_gap' => null,
                'slug'          => 'product-design-intern-flutterwave',
                'is_saved'      => false,
            ],
            [
                'id'            => 2,
                'role'          => 'UX Design Intern',
                'company'       => 'Paystack',
                'initials'      => 'PS',
                'logo_bg'       => '#0F172A',
                'stipend'       => '₦200,000/month',
                'location'      => 'Remote',
                'duration'      => '6 months',
                'match_score'   => 91,
                'match_tier'    => 'Strong Match',
                'match_reason'  => 'Strong alignment with your UX research background.',
                'skills'        => ['UX Research', 'Wireframing', 'Prototyping'],
                'potential_gap' => null,
                'slug'          => 'ux-design-intern-paystack',
                'is_saved'      => true,
            ],
            [
                'id'            => 3,
                'role'          => 'Junior Product Designer',
                'company'       => 'Kuda Bank',
                'initials'      => 'KB',
                'logo_bg'       => '#40196D',
                'stipend'       => '₦180,000/month',
                'location'      => 'Lagos',
                'duration'      => '3 months',
                'match_score'   => 88,
                'match_tier'    => 'Good Match',
                'match_reason'  => 'Strong alignment with visual design and user flow principles.',
                'skills'        => ['Design Systems', 'Figma', 'User Testing'],
                'potential_gap' => null,
                'slug'          => 'junior-product-designer-kuda',
                'is_saved'      => false,
            ],
            [
                'id'            => 4,
                'role'          => 'Design Systems Intern',
                'company'       => 'Moniepoint',
                'initials'      => 'MP',
                'logo_bg'       => '#003399',
                'stipend'       => '₦160,000/month',
                'location'      => 'Remote',
                'duration'      => '3 months',
                'match_score'   => 76,
                'match_tier'    => 'Good Match',
                'match_reason'  => 'Matches your core Figma skills and design documentation interest.',
                'skills'        => ['Figma Tokens', 'Component Libraries', 'Auto Layout'],
                'potential_gap' => [
                    'title' => 'Design systems tokens & GitHub workflow',
                    'desc'  => 'This requirement appears in the internship description but is not clearly represented in your profile.',
                ],
                'slug'          => 'design-systems-intern-moniepoint',
                'is_saved'      => false,
            ],
            [
                'id'            => 5,
                'role'          => 'Product Operations & Design Intern',
                'company'       => 'Piggyvest',
                'initials'      => 'PV',
                'logo_bg'       => '#0D60D8',
                'stipend'       => '₦140,000/month',
                'location'      => 'Lagos',
                'duration'      => '6 months',
                'match_score'   => 68,
                'match_tier'    => 'Fair Match',
                'match_reason'  => 'Your user journey mapping and problem-solving skills align well with team workflows.',
                'skills'        => ['Journey Mapping', 'Notion', 'Wireframing'],
                'potential_gap' => [
                    'title' => 'SQL & Product Analytics (Mixpanel)',
                    'desc'  => 'The hiring team prefers candidates with basic product analytics tracking familiarity.',
                ],
                'slug'          => 'product-ops-design-piggyvest',
                'is_saved'      => false,
            ],
            [
                'id'            => 6,
                'role'          => 'UI Engineer Intern',
                'company'       => 'Interswitch',
                'initials'      => 'IS',
                'logo_bg'       => '#E01A22',
                'stipend'       => '₦175,000/month',
                'location'      => 'Lagos',
                'duration'      => '6 months',
                'match_score'   => 62,
                'match_tier'    => 'Fair Match',
                'match_reason'  => 'Matches your UI design fundamentals and component architecture understanding.',
                'skills'        => ['HTML/CSS', 'Tailwind CSS', 'Figma to Code'],
                'potential_gap' => [
                    'title' => 'Frontend Framework Experience (React/Vue)',
                    'desc'  => 'Role requires basic implementation in React which is missing from your recent projects.',
                ],
                'slug'          => 'ui-engineer-intern-interswitch',
                'is_saved'      => false,
            ],
        ];

        $this->renderView('candidate/ai-assistant/matched', [
            'title'              => 'AI Matched Internships — InternConnect',
            'activeNav'          => 'ai-assistant',
            'candidate'          => $candidate,
            'candPlan'           => $candPlan,
            'matchedInternships' => $matchedInternships,
            'totalCount'         => 12,
        ], 'dashboard');
    }

    /**
     * AI Find Internships Filter View
     * Route: /candidate/ai-assistant/find-internships
     */
    public function aiFindInternships(): void
    {
        $this->aiMatchedInternships();
    }

    /**
     * AI Mock Interview Assistant View
     * Route: /candidate/ai-assistant/interview-prep
     */
    public function aiInterviewPrep(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (isset($_GET['plan'])) {
            $requestedPlan = strtolower(trim((string)$_GET['plan']));
            if (in_array($requestedPlan, ['free', 'pro', 'premium'], true)) {
                $_SESSION['candidate_plan'] = $requestedPlan;
            }
        }

        $candPlan = $_SESSION['candidate_plan'] ?? 'pro';
        if ($candPlan === 'free') {
            header('Location: ' . url('/pricing?plan=pro&ref=ai-mock-interview'));
            exit;
        }

        $candidate = [
            'name'       => $_SESSION['user_name'] ?? 'Sarah Peters',
            'first_name' => explode(' ', $_SESSION['user_name'] ?? 'Sarah Peters')[0],
            'email'      => $_SESSION['user_email'] ?? 'sarah.peters@university.edu.ng',
            'role'       => 'Student Applicant',
            'plan'       => $candPlan,
            'user_id'    => $_SESSION['user_id'] ?? 1,
        ];

        // Determine target role from GET or previous CV analysis
        $targetRole = trim((string)($_GET['role'] ?? ($_SESSION['ai_cv_analysis']['target_role'] ?? 'Product Designer')));
        $targetCompany = trim((string)($_GET['company'] ?? 'Paystack'));

        $rolesList = [
            'Product Designer',
            'Frontend Developer',
            'Backend Developer',
            'Data Analyst',
            'Product Manager',
        ];

        $companiesList = [
            'Paystack',
            'Flutterwave',
            'Kuda Bank',
            'Moniepoint',
            'Interswitch',
            'Piggyvest',
            'Tech Startup',
        ];

        $questions = AiService::getInterviewQuestions($targetRole);

        $this->renderView('candidate/ai-assistant/interview-prep', [
            'title'          => 'AI Mock Interview Assistant — InternConnect',
            'activeNav'      => 'ai-assistant',
            'candidate'      => $candidate,
            'candPlan'       => $candPlan,
            'targetRole'     => $targetRole,
            'targetCompany'  => $targetCompany,
            'rolesList'      => $rolesList,
            'companiesList'  => $companiesList,
            'questions'      => $questions,
        ], 'dashboard');
    }

    /**
     * Evaluate Candidate Mock Interview Answer via AJAX
     * Route: POST /candidate/ai-assistant/interview-prep/evaluate
     */
    public function evaluateMockAnswer(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        header('Content-Type: application/json; charset=UTF-8');

        $input = json_decode((string)file_get_contents('php://input'), true);
        if (!is_array($input)) {
            $input = $_POST;
        }

        $role     = trim((string)($input['role'] ?? 'Product Designer'));
        $question = trim((string)($input['question'] ?? ''));
        $answer   = trim((string)($input['answer'] ?? ''));

        if (empty($answer)) {
            echo json_encode([
                'success' => false,
                'error'   => 'Please provide your answer before requesting evaluation.',
            ]);
            exit;
        }

        $evaluation = AiService::evaluateInterviewAnswer($role, $question, $answer, [
            'user_id' => $_SESSION['user_id'] ?? 1,
        ]);

        echo json_encode([
            'success'    => true,
            'evaluation' => $evaluation,
        ]);
        exit;
    }

    /**
     * Candidate Direct 1-on-1 Messages
     * Route: /candidate/messages, /candidate/messages/{id}, /messages
     */
    public function messages(array $params = []): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $candidate = [
            'name'       => $_SESSION['user_name'] ?? 'Sarah Peters',
            'role'       => 'Student Applicant',
            'email'      => $_SESSION['user_email'] ?? 'sarah.peters@student.unilag.edu.ng',
            'avatar'     => $_SESSION['user_avatar'] ?? null,
            'is_pro'     => true,
            'plan'       => 'pro',
        ];

        $activeThreadId = isset($params['id']) ? (int)$params['id'] : 1;

        $conversations = [
            [
                'id'              => 1,
                'company_name'    => 'Nexura Technologies',
                'company_initials'=> 'NT',
                'recruiter_name'  => 'Tunde Bakare',
                'recruiter_role'  => 'Talent Acquisition Team',
                'role'            => 'UI/UX Design Intern',
                'recruiter_avatar'=> 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=100&h=100&fit=crop&crop=faces',
                'status'          => 'online',
                'last_active'     => 'Online now',
                'last_time'       => '10:42 AM',
                'last_message'    => 'Received and confirmed! I\'ve accepted the calendar invite for Thursday.',
                'unread'          => 0,
                'messages'        => [
                    [
                        'id'     => 101,
                        'sender' => 'candidate',
                        'text'   => 'Hello! Thank you for reviewing my design portfolio. I was very excited to see the UI/UX internship opening at Nexura Technologies.',
                        'time'   => 'Yesterday, 10:15 AM',
                        'status' => 'read',
                    ],
                    [
                        'id'     => 102,
                        'sender' => 'recruiter',
                        'text'   => 'Hi Sarah! We reviewed your Oakland banking app case study and were really impressed with the user flow and design tokens.',
                        'time'   => 'Yesterday, 10:22 AM',
                        'status' => 'read',
                    ],
                    [
                        'id'     => 103,
                        'sender' => 'candidate',
                        'text'   => 'Thank you so much! I really enjoyed working on the accessibility guidelines for that project. I\'m available anytime this week for the technical round.',
                        'time'   => 'Yesterday, 10:28 AM',
                        'status' => 'read',
                    ],
                    [
                        'id'     => 104,
                        'sender' => 'recruiter',
                        'text'   => 'Wonderful. We just sent an interview invitation for Thursday at 10:00 AM via Google Meet. Looking forward to speaking!',
                        'time'   => 'Yesterday, 10:35 AM',
                        'status' => 'read',
                    ],
                    [
                        'id'     => 105,
                        'sender' => 'candidate',
                        'text'   => 'Received and confirmed! I\'ve accepted the calendar invite for Thursday.',
                        'time'   => '10:42 AM',
                        'status' => 'read',
                    ],
                ],
            ],
            [
                'id'              => 2,
                'company_name'    => 'Paystack',
                'company_initials'=> 'PS',
                'recruiter_name'  => 'Chidinma Eze',
                'recruiter_role'  => 'People Operations Lead',
                'role'            => 'Frontend Engineering Intern',
                'recruiter_avatar'=> 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=100&h=100&fit=crop&crop=faces',
                'status'          => 'online',
                'last_active'     => 'Active 20m ago',
                'last_time'       => '11:15 AM',
                'last_message'    => 'Yes, you will receive the invitation link within the next 24 hours. Best of luck!',
                'unread'          => 1,
                'messages'        => [
                    [
                        'id'     => 201,
                        'sender' => 'recruiter',
                        'text'   => 'Hello Sarah, your application has been shortlisted for the next technical assessment stage.',
                        'time'   => 'Yesterday, 4:10 PM',
                        'status' => 'read',
                    ],
                    [
                        'id'     => 202,
                        'sender' => 'candidate',
                        'text'   => 'Thank you Chidinma! Should I expect the HackerRank assessment via email?',
                        'time'   => 'Yesterday, 4:45 PM',
                        'status' => 'read',
                    ],
                    [
                        'id'     => 203,
                        'sender' => 'recruiter',
                        'text'   => 'Yes, you will receive the invitation link within the next 24 hours. Best of luck!',
                        'time'   => '11:15 AM',
                        'status' => 'delivered',
                    ],
                ],
            ],
            [
                'id'              => 3,
                'company_name'    => 'Moniepoint',
                'company_initials'=> 'MP',
                'recruiter_name'  => 'Femi Adeyemi',
                'recruiter_role'  => 'Technical Talent Partner',
                'role'            => 'Software Engineer Intern',
                'recruiter_avatar'=> 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=100&h=100&fit=crop&crop=faces',
                'status'          => 'offline',
                'last_active'     => 'Active 2h ago',
                'last_time'       => 'Yesterday',
                'last_message'    => 'Thanks for turning it in ahead of the deadline! Our design panel is reviewing submissions today.',
                'unread'          => 0,
                'messages'        => [
                    [
                        'id'     => 301,
                        'sender' => 'candidate',
                        'text'   => 'Good afternoon Femi, I submitted my take-home project repository link yesterday evening.',
                        'time'   => 'Oct 23, 2:10 PM',
                        'status' => 'read',
                    ],
                    [
                        'id'     => 302,
                        'sender' => 'recruiter',
                        'text'   => 'Thanks for turning it in ahead of the deadline! Our design panel is reviewing submissions today.',
                        'time'   => 'Oct 23, 3:30 PM',
                        'status' => 'read',
                    ],
                ],
            ],
            [
                'id'              => 4,
                'company_name'    => 'Google',
                'company_initials'=> 'GO',
                'recruiter_name'  => 'David Chen',
                'recruiter_role'  => 'University Programs Recruiter',
                'role'            => 'Product Design Intern',
                'recruiter_avatar'=> 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=100&h=100&fit=crop&crop=faces',
                'status'          => 'offline',
                'last_active'     => 'Active 1d ago',
                'last_time'       => '2 days ago',
                'last_message'    => 'Hi Sarah, thank you for applying! We are currently reviewing 2026 intake applications.',
                'unread'          => 1,
                'messages'        => [
                    [
                        'id'     => 401,
                        'sender' => 'recruiter',
                        'text'   => 'Hi Sarah, thank you for applying! We are currently reviewing 2026 intake applications.',
                        'time'   => '2 days ago',
                        'status' => 'delivered',
                    ],
                ],
            ],
        ];

        foreach ($conversations as &$c) {
            $c['messages'] = CommunicationService::getThreadMessages($c['id'], $c['messages']);
            if (!empty($c['messages'])) {
                $lastMsg = end($c['messages']);
                $c['last_message'] = $lastMsg['text'] ?? $c['last_message'];
                $c['last_time'] = $lastMsg['time'] ?? $c['last_time'];
            }
        }
        unset($c);

        $this->renderView('candidate/messages/index', [
            'title'          => 'Messages & Inquiries — Candidate Portal — InternConnect',
            'activeNav'      => 'messages',
            'candidate'      => $candidate,
            'conversations'  => $conversations,
            'activeThreadId' => $activeThreadId,
        ], 'dashboard');
    }

    /**
     * Send a candidate direct message
     */
    public function sendMessage(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $input = json_decode(file_get_contents('php://input'), true) ?? $_POST;
        $threadId = (int)($input['thread_id'] ?? 1);
        $content = trim((string)($input['content'] ?? ($input['text'] ?? '')));
        $senderName = $_SESSION['profile_name'] ?? ($_SESSION['user_name'] ?? 'Sarah Peters');

        if ($content === '') {
            http_response_code(422);
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Message content cannot be empty']);
            exit;
        }

        $msg = CommunicationService::sendDirectMessage($threadId, 'candidate', $senderName, $content);

        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'message' => $msg]);
        exit;
    }

    /**
     * Mark a candidate notification as read
     */
    public function markNotificationRead(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $input = json_decode(file_get_contents('php://input'), true) ?? $_POST;
        $id = (int)($input['id'] ?? 0);

        CommunicationService::markNotificationRead($id, 'candidate');

        header('Content-Type: application/json');
        echo json_encode(['success' => true]);
        exit;
    }

    /**
     * Delete a single candidate notification (AJAX)
     */
    public function deleteNotification(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $input = json_decode(file_get_contents('php://input'), true) ?? $_POST;
        $id = (int)($input['id'] ?? 0);
        $ok = $id > 0 && CommunicationService::deleteNotification($id, 'candidate');

        header('Content-Type: application/json');
        echo json_encode(['success' => $ok]);
        exit;
    }

    /**
     * Mark all candidate notifications as read (AJAX)
     */    public function markAllNotificationsRead(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        CommunicationService::markAllNotificationsRead('candidate');

        header('Content-Type: application/json');
        echo json_encode(['success' => true]);
        exit;
    }

    /**
     * Candidate responds to interview invitation (Confirm or Reschedule)
     */
    public function respondInterview(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $input = json_decode(file_get_contents('php://input'), true) ?? $_POST;
        $interviewId = (int)($input['interview_id'] ?? 1);
        $status = (string)($input['status'] ?? 'Confirmed');
        $candidateName = $_SESSION['profile_name'] ?? 'Sarah Peters';

        $meta = [
            'candidate_name'  => $candidateName,
            'reason'          => $input['reason'] ?? '',
            'reschedule_date' => $input['reschedule_date'] ?? '',
            'reschedule_time' => $input['reschedule_time'] ?? '',
            'notes'           => $input['notes'] ?? '',
        ];

        $updated = CommunicationService::updateInterviewStatus($interviewId, $status, $meta);

        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'interview' => $updated]);
        exit;
    }

    /**
     * API to fetch latest thread messages for live synchronization
     */
    public function getThreadMessagesApi(array $params = []): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $threadId = (int)($params['id'] ?? 1);
        $messages = CommunicationService::getThreadMessages($threadId);

        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'messages' => $messages]);
        exit;
    }

    /**
     * View: AI Cover Letter Generator (Version 2.0 / Phase 5 AI Feature)
     * Route: /candidate/ai-assistant/cover-letter
     */
    public function aiCoverLetter(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $candidate = [
            'id'          => $_SESSION['user_id'] ?? 1,
            'name'        => $_SESSION['profile_name'] ?? 'Sarah Peters',
            'first_name'  => 'Sarah',
            'role'        => $_SESSION['profile_role'] ?? 'UI/UX Design Intern',
            'school'      => $_SESSION['candidate_school'] ?? 'University of Lagos (UNILAG)',
            'degree'      => 'B.Sc Systems Engineering',
            'avatar'      => $_SESSION['profile_avatar'] ?? asset('assets/images/profile.png'),
            'skills'      => ['Figma', 'UI Design', 'Wireframing', 'User Research', 'Prototyping'],
        ];

        // Sample Target Roles/Internships for quick selection
        $targetJobs = [
            [
                'id'       => 1,
                'company'  => 'Paystack',
                'role'     => 'UI/UX Design Intern',
                'location' => 'Lagos, Nigeria (Hybrid)',
                'industry' => 'Fintech / Payments',
                'logo_bg'  => '#059669',
                'initials' => 'PW'
            ],
            [
                'id'       => 2,
                'company'  => 'Flutterwave',
                'role'     => 'Product Design Intern',
                'location' => 'Remote, Africa',
                'industry' => 'Fintech / Global Infrastructure',
                'logo_bg'  => '#F59E0B',
                'initials' => 'FL'
            ],
            [
                'id'       => 3,
                'company'  => 'Nexura Technologies',
                'role'     => 'Frontend Developer Intern',
                'location' => 'Lagos, Nigeria',
                'industry' => 'Enterprise SaaS',
                'logo_bg'  => '#4F48E6',
                'initials' => 'NX'
            ],
            [
                'id'       => 4,
                'company'  => 'Interswitch Group',
                'role'     => 'Software Engineer Intern',
                'location' => 'Victoria Island, Lagos',
                'industry' => 'Digital Commerce',
                'logo_bg'  => '#0EA5E9',
                'initials' => 'IS'
            ]
        ];

        // Pre-generate default initial cover letter
        $defaultJob = $targetJobs[0];
        $initialLetter = AiService::generateCoverLetter($candidate, $defaultJob, 'professional');

        $this->renderView('candidate/ai-assistant/cover-letter', [
            'title'         => 'AI Cover Letter Generator — InternConnect',
            'activeNav'     => 'ai-assistant',
            'candidate'     => $candidate,
            'targetJobs'    => $targetJobs,
            'initialLetter' => $initialLetter,
        ], 'dashboard');
    }

    /**
     * API: Generate AI Cover Letter via AJAX
     * Route: /candidate/ai-assistant/cover-letter/generate
     */
    public function generateAiCoverLetter(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $input = json_decode(file_get_contents('php://input'), true) ?? $_POST;

        $candidate = [
            'id'     => $_SESSION['user_id'] ?? 1,
            'name'   => $_SESSION['profile_name'] ?? 'Sarah Peters',
            'school' => $_SESSION['candidate_school'] ?? 'University of Lagos (UNILAG)',
            'degree' => 'B.Sc Systems Engineering',
            'skills' => !empty($input['skills']) && is_array($input['skills']) 
                        ? $input['skills'] 
                        : ['Figma', 'UI Design', 'Wireframing', 'User Research', 'Prototyping'],
        ];

        $jobData = [
            'company'  => trim((string)($input['company'] ?? 'Paystack')),
            'role'     => trim((string)($input['role'] ?? 'UI/UX Design Intern')),
            'location' => trim((string)($input['location'] ?? 'Lagos, Nigeria (Hybrid)')),
        ];

        $tone = trim((string)($input['tone'] ?? 'professional'));

        $result = AiService::generateCoverLetter($candidate, $jobData, $tone);

        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'letter' => $result]);
        exit;
    }

    /**
     * View: AI Career Coach & Skill Roadmap (Version 2.0 / Phase 5 AI Feature)
     * Route: /candidate/ai-assistant/career-coach
     */
    public function aiCareerCoach(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $candidate = [
            'id'          => $_SESSION['user_id'] ?? 1,
            'name'        => $_SESSION['profile_name'] ?? 'Sarah Peters',
            'first_name'  => 'Sarah',
            'role'        => $_SESSION['profile_role'] ?? 'UI/UX Design Intern',
            'school'      => $_SESSION['candidate_school'] ?? 'University of Lagos (UNILAG)',
            'avatar'      => $_SESSION['profile_avatar'] ?? asset('assets/images/profile.png'),
            'skills'      => ['Figma', 'User Research', 'Wireframing', 'Prototyping', 'Design Systems'],
        ];

        $disciplines = [
            'Product Designer',
            'UI/UX Designer',
            'Software Engineer',
            'Frontend Developer',
            'Backend Developer',
            'Data Analyst',
            'Product Manager'
        ];

        $selectedRole = (string)($_GET['role'] ?? 'Product Designer');
        if (!in_array($selectedRole, $disciplines, true)) {
            $selectedRole = 'Product Designer';
        }

        $roadmap = AiService::generateCareerRoadmap($candidate, $selectedRole);

        $this->renderView('candidate/ai-assistant/career-coach', [
            'title'        => 'AI Career Coach & Skill Roadmap — InternConnect',
            'activeNav'    => 'ai-assistant',
            'candidate'    => $candidate,
            'disciplines'  => $disciplines,
            'selectedRole' => $selectedRole,
            'roadmap'      => $roadmap,
        ], 'dashboard');
    }

    /**
     * API: Generate AI Roadmap via AJAX
     * Route: /candidate/ai-assistant/career-coach/roadmap
     */
    public function generateAiRoadmap(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $input = json_decode(file_get_contents('php://input'), true) ?? $_POST;
        $targetRole = trim((string)($input['target_role'] ?? 'Product Designer'));

        $candidate = [
            'id'     => $_SESSION['user_id'] ?? 1,
            'name'   => $_SESSION['profile_name'] ?? 'Sarah Peters',
            'school' => $_SESSION['candidate_school'] ?? 'University of Lagos (UNILAG)',
            'skills' => ['Figma', 'User Research', 'Wireframing', 'Prototyping', 'Design Systems'],
        ];

        $result = AiService::generateCareerRoadmap($candidate, $targetRole);

        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'roadmap' => $result]);
        exit;
    }
}


