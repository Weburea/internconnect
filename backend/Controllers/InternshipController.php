<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;

class InternshipController extends Controller
{
    public function index(): void
    {
        $search = trim($_GET['search'] ?? '');
        $location = trim($_GET['location'] ?? '');
        $category = trim($_GET['category'] ?? '');

        $this->renderView('landing/internships', [
            'title'           => 'Browse Internships — InternConnect',
            'initialSearch'   => $search,
            'initialLocation' => $location,
            'initialCategory' => $category,
        ]);
    }

    public function search(): void
    {
        $search = trim($_GET['search'] ?? '');
        $location = trim($_GET['location'] ?? '');
        $category = trim($_GET['category'] ?? '');

        $pageTitle = 'Search Internships — InternConnect';
        if (!empty($search) && !empty($location)) {
            $pageTitle = 'Results for "' . htmlspecialchars($search) . '" in ' . htmlspecialchars($location) . ' — InternConnect';
        } elseif (!empty($search)) {
            $pageTitle = 'Results for "' . htmlspecialchars($search) . '" — InternConnect';
        }

        $this->renderView('landing/search', [
            'title'           => $pageTitle,
            'initialSearch'   => $search,
            'initialLocation' => $location,
            'initialCategory' => $category,
        ]);
    }

    public function show(array $params = []): void
    {
        // Mock internship dataset — no database queries
        $internships = [
            'senior-product-design-intern' => [
                'id'          => 'senior-product-design-intern',
                'title'       => 'Senior Product Design Intern',
                'company'     => 'WellSpring Care',
                'company_url' => '#',
                'location'    => 'Lagos, NG',
                'work_mode'   => 'Hybrid',
                'category'    => 'Technology',
                'sub_category'=> 'Product Designer',
                'tags'        => ['REMOTE', 'FULL-TIME', 'INTERNSHIP'],
                'salary'      => '$4,500',
                'salary_label'=> 'Monthly Pay',
                'applicants'  => 124,
                'posted'      => '2 days ago',
                'level'       => 'Junior / Intern',
                'overview'    => [
                    "At WellSpring Care, we're building a people-first healthcare experience that prioritises accessibility, empathy, and innovation. As an intern, you'll play an active role in supporting our mission to deliver seamless care solutions through thoughtful design, efficient systems, and user-centred thinking.",
                    "You'll collaborate with a growing team to improve how patients connect with care services, contribute ideas that shape real features, and gain hands-on experience working on meaningful projects that impact everyday lives.",
                    "This role is perfect for someone eager to learn, contribute, and grow in a fast-paced, purpose-driven environment where your ideas are valued and your work makes a difference.",
                ],
                'responsibilities' => [
                    'Execute end-to-end design projects from initial wireframing to high-fidelity prototyping.',
                    'Conduct user research sessions and synthesise feedback into actionable design iterations.',
                    'Contribute to the evolution of our global design system, "Nova UI."',
                    'Present your work and rationale during weekly design critiques with the leadership team.',
                ],
                'requirements' => [
                    [
                        'icon'    => 'academic',
                        'title'   => 'Academic Foundation',
                        'desc'    => 'Currently enrolled in or a recent graduate of a Design, HCI, or related program.',
                    ],
                    [
                        'icon'    => 'technical',
                        'title'   => 'Technical Proficiency',
                        'desc'    => 'Expertise in Figma and a strong understanding of interactive prototyping tools.',
                    ],
                    [
                        'icon'    => 'collaboration',
                        'title'   => 'Collaboration',
                        'desc'    => 'A team player who values feedback and thrives in a fast-paced environment.',
                    ],
                    [
                        'icon'    => 'detail',
                        'title'   => 'Attention to Detail',
                        'desc'    => 'A keen eye for typography, layout, and pixel-perfect execution.',
                    ],
                ],
                'benefits' => [
                    'Competitive Stipend',
                    'High-end Equipment',
                    'Mentorship Programme',
                    'Flexible Working Hours',
                ],
                'application_window' => 'Applications close on October 15th, 2026. Interviews will be conducted on a rolling basis.',
            ],
            'software-engineer-intern' => [
                'id'          => 'software-engineer-intern',
                'title'       => 'Software Engineer Intern',
                'company'     => 'Nexura',
                'company_url' => '#',
                'location'    => 'Lagos, NG',
                'work_mode'   => 'Remote',
                'category'    => 'Technology',
                'sub_category'=> 'Software Engineering',
                'tags'        => ['REMOTE', 'FULL-TIME', 'INTERNSHIP'],
                'salary'      => '$3,000',
                'salary_label'=> 'Monthly Pay',
                'applicants'  => 89,
                'posted'      => '5 days ago',
                'level'       => 'Junior / Intern',
                'overview'    => [
                    "Join Nexura's payments infrastructure team to build the foundation of global commerce. You'll work on high-scale systems serving millions of transactions every day.",
                    "As a Software Engineer Intern, you'll contribute directly to our core platform, writing production-quality code and collaborating with senior engineers.",
                    "This is a unique opportunity to grow rapidly in a fast-moving fintech environment with real impact from day one.",
                ],
                'responsibilities' => [
                    'Design and implement features for our payments processing pipeline.',
                    'Write clean, tested, and well-documented code in Go and TypeScript.',
                    'Participate in code reviews and architectural discussions.',
                    'Monitor and improve system reliability and performance metrics.',
                ],
                'requirements' => [
                    [
                        'icon'    => 'academic',
                        'title'   => 'Computer Science Background',
                        'desc'    => 'Pursuing or completed a degree in Computer Science, Engineering, or a related field.',
                    ],
                    [
                        'icon'    => 'technical',
                        'title'   => 'Programming Skills',
                        'desc'    => 'Proficiency in at least one backend language (Go, Python, Java, or TypeScript).',
                    ],
                    [
                        'icon'    => 'collaboration',
                        'title'   => 'Team Player',
                        'desc'    => 'Comfortable working in agile teams and communicating blockers proactively.',
                    ],
                    [
                        'icon'    => 'detail',
                        'title'   => 'Problem Solver',
                        'desc'    => 'Strong analytical mindset with a passion for solving complex technical challenges.',
                    ],
                ],
                'benefits' => [
                    'Competitive Stipend',
                    'Remote-first Culture',
                    'Learning & Development Budget',
                    'Return Offer Potential',
                ],
                'application_window' => 'Applications close on November 1st, 2026. Interviews begin on a rolling basis.',
            ],
        ];

        $slug = $params['slug'] ?? 'senior-product-design-intern';
        $internship = $internships[$slug] ?? $internships['senior-product-design-intern'];

        $this->renderView('landing/internship-detail', [
            'title'      => $internship['title'] . ' at ' . $internship['company'] . ' — InternConnect',
            'internship' => $internship,
        ]);
    }

    public function apply(array $params = []): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Mock internship dataset for application view
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

        $slug = $params['slug'] ?? 'software-engineer-intern';
        $internship = $internships[$slug] ?? $internships['software-engineer-intern'];

        $defaultRelevantExperience = [
            ['id' => 1, 'title' => 'Job Title', 'type' => 'short_answer', 'placeholder' => 'Frontend Developer Intern', 'required' => true],
            ['id' => 2, 'title' => 'Company', 'type' => 'short_answer', 'placeholder' => 'TechLabs Nigeria', 'required' => true],
            ['id' => 3, 'title' => 'Salary Expectation ($/month)', 'type' => 'short_answer', 'placeholder' => '2,500', 'required' => true]
        ];

        $defaultScreeningQuestions = [
            [
                'id' => 1,
                'question' => 'Why are you interested in joining ' . ($internship['company'] ?? 'Nexora') . ' specifically?',
                'type' => 'long_answer',
                'type_label' => 'Long Answer',
                'placeholder' => "Share your motivation and how your values align with " . ($internship['company'] ?? 'Nexora') . "'s mission...",
                'max_words' => 500,
                'required' => true
            ],
            [
                'id' => 2,
                'question' => 'Describe your proudest project or process.',
                'type' => 'long_answer',
                'type_label' => 'Long Answer',
                'placeholder' => 'What problem did you solve? What was your specific impact?',
                'max_words' => 500,
                'required' => true
            ],
            [
                'id' => 3,
                'question' => 'What are your primary learning goals for this internship?',
                'type' => 'long_answer',
                'type_label' => 'Long Answer',
                'placeholder' => 'What skills or knowledge do you hope to acquire?',
                'max_words' => 300,
                'required' => true
            ],
            [
                'id' => 4,
                'question' => 'Attach NYSC credentials (optional)',
                'type' => 'file_upload',
                'type_label' => 'File Upload (Portfolio / Document)',
                'file_hint' => 'PDF, PNG or JPG · Max 10MB',
                'required' => false
            ],
            [
                'id' => 5,
                'question' => 'Link for Portfolio',
                'type' => 'link',
                'type_label' => 'Link / URL',
                'placeholder' => 'https://myportfolio.com',
                'required' => false
            ],
            [
                'id' => 6,
                'question' => 'Figma Link',
                'type' => 'link',
                'type_label' => 'Link / URL',
                'placeholder' => 'https://www.figma.com/file/...',
                'required' => false
            ]
        ];

        if (!empty($_SESSION['draft_internship']['relevant_experience'])) {
            $internship['relevant_experience'] = $_SESSION['draft_internship']['relevant_experience'];
        } else {
            $internship['relevant_experience'] = $defaultRelevantExperience;
        }

        if (!empty($_SESSION['draft_internship']['screening_questions'])) {
            $internship['screening_questions'] = $_SESSION['draft_internship']['screening_questions'];
        } else {
            $internship['screening_questions'] = $defaultScreeningQuestions;
        }

        $this->renderView('candidate/application', [
            'title'      => 'Apply to ' . $internship['company'] . ' — InternConnect',
            'internship' => $internship,
            'slug'       => $slug,
        ]);
    }
}

