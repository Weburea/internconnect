<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Services\HelpService;

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
            'profile_pct' => 75,
            'checklist'   => [
                ['label' => 'Upload Resume',       'done' => true],
                ['label' => 'Technical Skills',    'done' => true],
                ['label' => 'Education Details',   'done' => false],
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
                'title'    => 'UI/UX Design Intern',
                'company'  => 'DesignStudio Pro',
                'location' => 'Lagos, NG',
                'tags'     => ['User Testing', 'Figma'],
                'salary'   => '$4,500/mo',
                'slug'     => 'uiux-design-intern-designstudio',
                'icon'     => '<svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>',
            ],
            [
                'title'    => 'Digital Marketing',
                'company'  => 'Global Pulse Media',
                'location' => 'Lagos, NG',
                'tags'     => ['Strategy', 'Analytics'],
                'salary'   => '$3,800/mo',
                'slug'     => 'digital-marketing-global-pulse',
                'icon'     => '<svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>',
            ],
            [
                'title'    => 'Backend Developer',
                'company'  => 'FinTech Systems',
                'location' => 'Lagos, NG',
                'tags'     => ['React', 'Node.js'],
                'salary'   => '$5,200/mo',
                'slug'     => 'backend-developer-fintech-systems',
                'icon'     => '<svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 3H5a2 2 0 00-2 2v4m6-6h10a2 2 0 012 2v4M9 3v18m0 0h10a2 2 0 002-2V9M9 21H5a2 2 0 01-2-2V9m0 0h18"/></svg>',
            ],
            [
                'title'    => 'Frontend Developer',
                'company'  => 'TechCorp Inc.',
                'location' => 'Lagos, NG',
                'tags'     => ['HTML/CSS', 'Vue.js'],
                'salary'   => '$4,800/mo',
                'slug'     => 'frontend-developer-techcorp',
                'icon'     => '<svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>',
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
            'title'           => 'Dashboard — InternConnect',
            'candidate'       => $candidate,
            'stats'           => $stats,
            'recommendations' => $recommendations,
            'applications'    => $applications,
            'alerts'          => $alerts,
            'showLoader'      => false,
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
                'title'      => 'Product Design Intern',
                'company'    => 'Google',
                'location'   => 'Mountain View, CA',
                'slug'       => 'senior-product-design-intern',
                'tags'       => ['Remote', 'Summer 2024', 'Paid'],
                'logo_class' => 'google',
                'logo_svg'   => '<svg viewBox="0 0 24 24" width="24" height="24"><path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/><path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/><path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/><path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/></svg>',
            ],
            [
                'title'      => 'Data Science Fellow',
                'company'    => 'Spotify',
                'location'   => 'New York, NY',
                'slug'       => 'software-engineer-intern',
                'tags'       => ['Hybrid', 'Fall 2024', 'Paid'],
                'logo_class' => 'spotify',
                'logo_svg'   => '<svg viewBox="0 0 24 24" width="24" height="24"><path fill="#1ED760" d="M12 2C6.477 2 2 6.477 2 12s4.477 10 10 10 10-4.477 10-10S17.523 2 12 2zm4.586 14.424c-.18.295-.565.387-.86.207-2.377-1.454-5.37-1.783-8.895-1.02-.336.074-.668-.14-.744-.478-.075-.337.14-.668.478-.744 3.856-.88 7.15-.503 9.814 1.128.295.18.387.563.207.857zm1.225-2.72c-.226.367-.707.487-1.074.26-2.72-1.672-6.87-2.157-10.08-1.182-.413.125-.85-.107-.978-.52-.128-.414.108-.85.52-.977 3.663-1.11 8.234-.57 11.35 1.348.368.226.488.707.262 1.07zm.107-2.81c-3.262-1.937-8.646-2.12-11.76-1.17-.5.152-1.025-.133-1.177-.635-.152-.5.133-1.027.635-1.178 3.585-1.09 9.513-.883 13.264 1.344.45.267.6.846.333 1.296-.267.45-.847.6-1.297.333z"/></svg>',
            ],
            [
                'title'      => 'Marketing Strategy Intern',
                'company'    => 'Airbnb',
                'location'   => 'San Francisco, CA',
                'slug'       => 'senior-product-design-intern',
                'tags'       => ['Remote', 'Summer 2024'],
                'logo_class' => 'airbnb',
                'logo_svg'   => '<svg viewBox="0 0 32 32" width="24" height="24"><path fill="#FF5A5F" d="M16 1c-2.008 0-3.916 1.183-4.834 3.003L1.524 22.955c-1.025 2.03-.541 4.544 1.157 6.06A7.478 7.478 0 0 0 7.625 31c2.723 0 5.253-1.492 6.643-3.926l1.732-3.033 1.733 3.033C19.122 29.508 21.652 31 24.375 31a7.478 7.478 0 0 0 4.944-1.985c1.698-1.516 2.182-4.03 1.157-6.06L20.834 4.003A5.498 5.498 0 0 0 16 1zm0 2.2c1.233 0 2.39.73 2.946 1.834l9.643 18.95c.579 1.144.3 2.502-.686 3.383A5.278 5.278 0 0 1 24.375 28.8c-1.928 0-3.722-1.057-4.706-2.778l-3.669-6.42a1.102 1.102 0 0 0-1.928 0l-3.67 6.42c-.983 1.72-2.777 2.778-4.705 2.778a5.278 5.278 0 0 1-3.527-1.433c-.987-.88-1.266-2.239-.687-3.383l9.643-18.95c.556-1.104 1.713-1.834 2.946-1.834zm0 6.6c-2.099 0-3.8 1.701-3.8 3.8s1.701 3.8 3.8 3.8 3.8-1.701 3.8-3.8-1.701-3.8-3.8-3.8zm0 2.2c.884 0 1.6.716 1.6 1.6 0 .884-.716 1.6-1.6 1.6a1.602 1.602 0 0 1-1.6-1.6c0-.884.716-1.6 1.6-1.6z"/></svg>',
            ],
            [
                'title'      => 'Software Engineering Intern',
                'company'    => 'Microsoft',
                'location'   => 'Redmond, WA',
                'slug'       => 'software-engineer-intern',
                'tags'       => ['Onsite', 'Winter 2024', 'Paid'],
                'logo_class' => 'microsoft',
                'logo_svg'   => '<svg viewBox="0 0 23 23" width="24" height="24"><path fill="#F25022" d="M0 0h11v11H0z"/><path fill="#7FBA00" d="M12 0h11v11H12z"/><path fill="#00A4EF" d="M0 12h11v11H0z"/><path fill="#FFB900" d="M12 12h11v11H12z"/></svg>',
            ]
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

        $candidate = [
            'name'                  => $_SESSION['profile_name'] ?? 'Sarah Peters',
            'role'                  => $_SESSION['profile_role'] ?? 'Student Applicant',
            'avatar'                => $_SESSION['profile_avatar'] ?? asset('assets/images/profile.png'),
            'email'                 => $_SESSION['profile_email'] ?? 'sarah.peters@design-intern.com',
            'phone'                 => $_SESSION['profile_phone'] ?? '+1 (555) 012-3456',
            'bio'                   => $_SESSION['profile_bio'] ?? 'Passionate designer focused on creating intuitive digital experiences. Currently exploring the intersection of accessibility and high-end visual design.',
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
                } else {
                    $_SESSION['success_msg'] = 'Password updated successfully!';
                }
            } else {
                $_SESSION['profile_name']  = trim(strip_tags($_POST['name'] ?? 'Sarah Peters'));
                $_SESSION['profile_email'] = trim(strip_tags($_POST['email'] ?? 'sarah.peters@design-intern.com'));
                $_SESSION['profile_role']  = trim(strip_tags($_POST['role'] ?? 'Student Applicant'));
                $_SESSION['profile_phone'] = trim(strip_tags($_POST['phone'] ?? '+1 (555) 012-3456'));
                $_SESSION['profile_bio']   = trim(strip_tags($_POST['bio'] ?? ''));

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

                $_SESSION['success_msg'] = 'Profile settings saved successfully!';
            }
        }

        header('Location: ' . url('/candidate/profile'));
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
}


