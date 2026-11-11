<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Services\CommunicationService;
use App\Services\UploadStorage;
use App\Services\ExportService;
use App\Services\VideoInterviewService;

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
                'id'          => 1,
                'name'        => 'Sarah Peters',
                'role'        => 'UI Designer Intern',
                'applied_date'=> 'Oct 12, 2026',
                'avatar'      => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=100&h=100&fit=crop&crop=faces',
            ],
            [
                'id'          => 2,
                'name'        => 'David Adeleke',
                'role'        => 'Backend Developer Intern',
                'applied_date'=> 'Oct 11, 2026',
                'avatar'      => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=100&h=100&fit=crop&crop=faces',
            ]
        ];

        // Mock AI Recommended Candidates
        $recommendations = [
            [
                'id'         => 3,
                'name'       => 'Amara Okafor',
                'role'       => 'Associate Product Manager',
                'match'      => '98% MATCH',
                'tags'       => ['Product Strategy', 'Figma', 'User Research'],
                'bio'        => '"Passionate about building scalable digital products with a focus on usability..."',
                'avatar'     => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=100&h=100&fit=crop&crop=faces',
                'status'     => 'Available Now',
                'status_cls' => 'text-emerald-500 bg-emerald-50 border-emerald-100',
            ],
            [
                'id'         => 4,
                'name'       => 'Tunde Bakare',
                'role'       => 'Data Analyst Intern',
                'match'      => '94% MATCH',
                'tags'       => ['SQL', 'Python', 'PowerBI'],
                'bio'        => '"Focused on business intelligence, ETL pipelines, and data storytelling..."',
                'avatar'     => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=100&h=100&fit=crop&crop=faces',
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
     * Renders the Public Company Profile (Candidate & Visitor Facing)
     * Route: /company, /company/{slug}, /companies, /companies/{slug}
     * Layout: app (Public Navbar, Jenny AI Chatbot, Public Footer)
     */
    public function publicCompanyProfile(array $params = []): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $rawSlug = strtolower(trim($params['slug'] ?? 'techflow'));
        if ($rawSlug === '' || $rawSlug === 'company' || $rawSlug === 'companies') {
            $rawSlug = 'techflow';
        }

        // Canonical alias mapping
        $slugAliases = [
            'techflow-system'  => 'techflow',
            'techflow-systems' => 'techflow',
            'techflow'         => 'techflow',
            'nexura'           => 'nexura',
            'airbnb'           => 'airbnb',
            'wellspring'       => 'wellspring-care',
            'wellspring-care'  => 'wellspring-care',
            'brightwave'       => 'brightwave',
            'kiro'             => 'kiro-labs',
            'kiro-labs'        => 'kiro-labs',
            'zod'              => 'zod-studio',
            'zod-studio'       => 'zod-studio',
            'firebase'         => 'firebase',
            'google'           => 'google',
            'panda-css'        => 'panda-css',
            'pandacss'         => 'panda-css',
            'firecrawl'        => 'firecrawl',
        ];

        $slug = $slugAliases[$rawSlug] ?? $rawSlug;

        // Comprehensive Mock Catalog of Companies
        $catalog = [
            'techflow' => [
                'name'        => $_SESSION['employer_company'] ?? 'TechFlow Systems',
                'slug'        => 'techflow',
                'slogan'      => $_SESSION['employer_slogan'] ?? 'Building innovative digital experiences for the future',
                'industry'    => $_SESSION['employer_industry'] ?? 'Software Development',
                'location'    => $_SESSION['employer_location'] ?? 'Victoria Island, Lagos, Nigeria',
                'size'        => $_SESSION['employer_company_size'] ?? '201–500 employees',
                'website'     => $_SESSION['employer_website'] ?? 'https://techflow.io',
                'web_display' => 'techflow.io',
                'email'       => $_SESSION['employer_email'] ?? 'careers@techflow.io',
                'phone'       => $_SESSION['employer_phone'] ?? '+234 801 234 5678',
                'logo'        => $_SESSION['employer_logo'] ?? 'https://res.cloudinary.com/weburea/image/upload/v1789424092/internConnect/images/hk_logo.jpg',
                'banner'      => $_SESSION['employer_banner'] ?? 'https://res.cloudinary.com/weburea/image/upload/v1789424121/internConnect/images/hk_banner.png',
                'verified'    => true,
                'rating'      => '4.9',
                'reviews_cnt' => 24,
                'description' => $_SESSION['employer_description'] ?? 'TechFlow Systems is a leading enterprise software provider specializing in cloud-native platforms and AI-driven workflow analytics. We empower fast-moving global organizations to build, scale, and automate high-impact software experiences with industry-leading reliability.',
                'departments' => $_SESSION['employer_departments'] ?? ['Engineering', 'Product Design', 'Marketing', 'Data Science'],
                'culture'     => [
                    'description' => $_SESSION['employer_culture_desc'] ?? 'We believe that inclusive collaboration, psychological safety, and bold experimentation drive sustainable innovation. Interns at TechFlow are paired with senior staff engineers from day one and contribute directly to live production services.',
                    'values'      => ['Inclusive Mentorship', 'Production Ownership', 'Continuous Learning', 'High Standards'],
                ],
                'socials'     => [
                    'website'   => $_SESSION['employer_website'] ?? 'https://techflow.io',
                    'linkedin'  => 'https://linkedin.com/company/techflow',
                    'twitter'   => 'https://x.com/TechFlowHQ',
                    'instagram' => 'https://instagram.com/techflowhq',
                    'dribbble'  => 'https://dribbble.com/techflow',
                ],
                'privacy'     => [
                    'show_email'    => $_SESSION['employer_privacy_show_email'] ?? true,
                    'show_phone'    => $_SESSION['employer_privacy_show_phone'] ?? false,
                    'show_socials'  => $_SESSION['employer_privacy_show_socials'] ?? true,
                    'show_size'     => $_SESSION['employer_privacy_show_size'] ?? true,
                    'show_gallery'  => $_SESSION['employer_privacy_show_gallery'] ?? true,
                    'show_location' => $_SESSION['employer_privacy_show_location'] ?? true,
                ],
                'gallery'     => $_SESSION['employer_gallery'] ?? [
                    'https://images.unsplash.com/photo-1497366216548-37526070297c?w=800&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1522071820081-009f0129c71c?w=800&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1531482615713-2afd69097998?w=800&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=800&auto=format&fit=crop&q=80',
                ],
                'openings'    => [
                    [
                        'id'         => 'senior-software-engineer-intern',
                        'title'      => 'Senior Software Engineer Intern',
                        'category'   => 'ENGINEERING',
                        'work_mode'  => 'Hybrid',
                        'location'   => 'Victoria Island, Lagos',
                        'stipend'    => '$5,000/mo',
                        'type'       => 'Full-Time',
                        'applicants' => 142,
                        'desc'       => 'Build distributed backend microservices, design scalable APIs, and collaborate closely with cloud infrastructure engineers.',
                    ],
                    [
                        'id'         => 'product-designer-ux-ui-intern',
                        'title'      => 'Product Designer (UX/UI) Intern',
                        'category'   => 'DESIGN',
                        'work_mode'  => 'Remote',
                        'location'   => 'Lagos, NG or Remote',
                        'stipend'    => '$4,500/mo',
                        'type'       => 'Full-Time',
                        'applicants' => 86,
                        'desc'       => 'Conduct user research, design wireframes, craft multi-platform Figma design systems, and prototype next-gen web journeys.',
                    ],
                ],
                'reviews'     => [
                    [
                        'name'    => 'Alexander Wright',
                        'role'    => 'Software Engineering Intern',
                        'date'    => 'August 2026',
                        'rating'  => 5,
                        'avatar'  => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=100&h=100&fit=crop&crop=faces',
                        'comment' => 'Outstanding mentorship and real production impact from week one. The collaborative engineering culture helped me sharpen my full-stack skills tremendously.',
                    ],
                    [
                        'name'    => 'Chioma Adeyemi',
                        'role'    => 'Product Design Intern',
                        'date'    => 'July 2026',
                        'rating'  => 5,
                        'avatar'  => 'https://images.unsplash.com/photo-1517841905240-472988babdf9?w=100&h=100&fit=crop&crop=faces',
                        'comment' => 'Working at TechFlow gave me exposure to complex enterprise design systems. The team actively listens to intern ideas and encourages creative problem-solving.',
                    ],
                    [
                        'name'    => 'David Olatunji',
                        'role'    => 'Backend Systems Intern',
                        'date'    => 'May 2026',
                        'rating'  => 5,
                        'avatar'  => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=100&h=100&fit=crop&crop=faces',
                        'comment' => 'Great leadership, supportive peers, and high engineering standards. Highly recommended for students wanting hands-on cloud experience.',
                    ],
                ]
            ],

            'nexura' => [
                'name'        => 'Nexura',
                'slug'        => 'nexura',
                'slogan'      => 'Next-generation payment infrastructure for African and global commerce',
                'industry'    => 'Fintech & Payments',
                'location'    => 'Lagos, Nigeria',
                'size'        => '51–200 employees',
                'website'     => 'https://nexura.finance',
                'web_display' => 'nexura.finance',
                'email'       => 'careers@nexura.finance',
                'phone'       => '+234 802 999 1122',
                'logo'        => null,
                'logo_bg'     => '#000000',
                'logo_color'  => '#FFFFFF',
                'initial'     => 'N',
                'banner'      => 'https://images.unsplash.com/photo-1559526324-4b87b5e36e44?w=1200&auto=format&fit=crop&q=80',
                'verified'    => true,
                'rating'      => '4.9',
                'reviews_cnt' => 19,
                'description' => 'Nexura builds real-time financial rail orchestration, instant bank settlements, and multi-currency payout infrastructure. We connect African businesses with international markets through clean developer APIs and resilient low-latency systems.',
                'departments' => ['Core Banking', 'Payments Engineering', 'Risk & Compliance', 'Developer Relations'],
                'culture'     => [
                    'description' => 'At Nexura, high velocity meets bulletproof financial integrity. We obsess over milliseconds of latency and write comprehensive test suites while maintaining an open, helpful environment for early career engineers.',
                    'values'      => ['Financial Rigor', 'Developer-First Mindset', 'Radical Candor', 'Bias for Execution'],
                ],
                'socials'     => [
                    'website'   => 'https://nexura.finance',
                    'linkedin'  => 'https://linkedin.com/company/nexura-finance',
                    'twitter'   => 'https://x.com/NexuraHQ',
                    'instagram' => 'https://instagram.com/nexurahq',
                ],
                'privacy'     => [
                    'show_email'    => true,
                    'show_phone'    => false,
                    'show_socials'  => true,
                    'show_size'     => true,
                    'show_gallery'  => true,
                    'show_location' => true,
                ],
                'gallery'     => [
                    'https://images.unsplash.com/photo-1497215728101-856f4ea42174?w=800&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1531482615713-2afd69097998?w=800&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1522071820081-009f0129c71c?w=800&auto=format&fit=crop&q=80',
                ],
                'openings'    => [
                    [
                        'id'         => 'software-engineer-intern',
                        'title'      => 'Software Engineer Intern',
                        'category'   => 'ENGINEERING',
                        'work_mode'  => 'Remote',
                        'location'   => 'Lagos, NG',
                        'stipend'    => '$5,000/mo',
                        'type'       => 'Full-Time',
                        'applicants' => 128,
                        'desc'       => 'Join our payments infrastructure team to build the foundation of global commerce. You\'ll work on high-scale distributed systems.',
                    ],
                ],
                'reviews'     => [
                    [
                        'name'    => 'Emmanuel Okoro',
                        'role'    => 'Fintech Engineering Intern',
                        'date'    => 'September 2026',
                        'rating'  => 5,
                        'avatar'  => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=100&h=100&fit=crop&crop=faces',
                        'comment' => 'Incredible hands-on experience with transaction concurrency and idempotency. The team treats interns as full teammates.',
                    ],
                ]
            ],

            'airbnb' => [
                'name'        => 'Airbnb',
                'slug'        => 'airbnb',
                'slogan'      => 'Belong anywhere — creating world-class travel, design, and hosting experiences',
                'industry'    => 'Consumer Tech & Travel',
                'location'    => 'Lagos, NG',
                'size'        => '5,000+ employees',
                'website'     => 'https://airbnb.com',
                'web_display' => 'airbnb.com',
                'email'       => 'university-recruiting@airbnb.com',
                'phone'       => '+1 415 555 0199',
                'logo'        => null,
                'logo_bg'     => '#FFE4E6',
                'logo_color'  => '#E11D48',
                'initial'     => 'A',
                'banner'      => 'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?w=1200&auto=format&fit=crop&q=80',
                'verified'    => true,
                'rating'      => '5.0',
                'reviews_cnt' => 48,
                'description' => 'Airbnb is a global travel community that offers end-to-end trips, including where you stay, what you do, and the people you meet. Our design and engineering teams craft iconic interfaces trusted by hundreds of millions worldwide.',
                'departments' => ['Product Design', 'Experience Research', 'Design Systems', 'Host Experience'],
                'culture'     => [
                    'description' => 'Design thinking is at the heart of everything we build. We champion craftsmanship, deep empathy for travelers and hosts, and thoughtful aesthetic precision.',
                    'values'      => ['Champion the Mission', 'Be a Host', 'Embrace the Adventure', 'Be a "Cereal" Entrepreneur'],
                ],
                'socials'     => [
                    'website'   => 'https://airbnb.com',
                    'linkedin'  => 'https://linkedin.com/company/airbnb',
                    'twitter'   => 'https://x.com/Airbnb',
                    'instagram' => 'https://instagram.com/airbnb',
                ],
                'privacy'     => [
                    'show_email'    => true,
                    'show_phone'    => false,
                    'show_socials'  => true,
                    'show_size'     => true,
                    'show_gallery'  => true,
                    'show_location' => true,
                ],
                'gallery'     => [
                    'https://images.unsplash.com/photo-1524758631624-e2822e304c36?w=800&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1497366811353-6870744d04b2?w=800&auto=format&fit=crop&q=80',
                ],
                'openings'    => [
                    [
                        'id'         => 'product-design-intern',
                        'title'      => 'Product Design Intern',
                        'category'   => 'DESIGN',
                        'work_mode'  => 'Hybrid',
                        'location'   => 'Lagos, NG',
                        'stipend'    => '$4,500/mo',
                        'type'       => 'Full-Time',
                        'applicants' => 176,
                        'desc'       => 'Craft beautiful, intuitive experiences for travelers and hosts. You\'ll participate in the full design lifecycle alongside world-class designers.',
                    ],
                ],
                'reviews'     => [
                    [
                        'name'    => 'Sarah Jenkins',
                        'role'    => 'Product Design Intern',
                        'date'    => 'August 2026',
                        'rating'  => 5,
                        'avatar'  => 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=100&h=100&fit=crop&crop=faces',
                        'comment' => 'Working at Airbnb was a masterclass in UI polish and storytelling. The mentorship and feedback sessions were second to none.',
                    ],
                ]
            ],

            'wellspring-care' => [
                'name'        => 'WellSpring Care',
                'slug'        => 'wellspring-care',
                'slogan'      => 'Human-centered healthcare solutions and intelligent digital wellness',
                'industry'    => 'Healthcare & HealthTech',
                'location'    => 'Lagos, NG',
                'size'        => '51–200 employees',
                'website'     => 'https://wellspringcare.health',
                'web_display' => 'wellspringcare.health',
                'email'       => 'careers@wellspringcare.health',
                'phone'       => '+234 803 111 2233',
                'logo'        => null,
                'logo_bg'     => '#D1FAE5',
                'logo_color'  => '#059669',
                'initial'     => 'W',
                'banner'      => 'https://images.unsplash.com/photo-1576091160399-112ba8d25d1d?w=1200&auto=format&fit=crop&q=80',
                'verified'    => true,
                'rating'      => '4.8',
                'reviews_cnt' => 15,
                'description' => 'WellSpring Care develops digital triage systems, tele-consultation interfaces, and patient monitoring software. We prioritize accessibility and human empathy across all digital patient touchpoints.',
                'departments' => ['Clinical UX', 'Product Design', 'Healthcare Engineering', 'Patient Success'],
                'culture'     => [
                    'description' => 'Our mission is to save lives and make medical expertise universally accessible. We work closely with clinical doctors and human factors researchers.',
                    'values'      => ['Patient Empathy', 'Clinical Precision', 'Accessibility by Default', 'Integrity'],
                ],
                'socials'     => [
                    'website'   => 'https://wellspringcare.health',
                    'linkedin'  => 'https://linkedin.com/company/wellspring-care',
                    'twitter'   => 'https://x.com/WellSpringCare',
                ],
                'privacy'     => [
                    'show_email'    => true,
                    'show_phone'    => false,
                    'show_socials'  => true,
                    'show_size'     => true,
                    'show_gallery'  => true,
                    'show_location' => true,
                ],
                'gallery'     => [
                    'https://images.unsplash.com/photo-1519494026892-80bbd2d6fd0d?w=800&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1538108149393-fbbd81895907?w=800&auto=format&fit=crop&q=80',
                ],
                'openings'    => [
                    [
                        'id'         => 'senior-product-design-intern',
                        'title'      => 'Senior Product Design Intern',
                        'category'   => 'PRODUCT',
                        'work_mode'  => 'Hybrid',
                        'location'   => 'Lagos, NG',
                        'stipend'    => '$4,800/mo',
                        'type'       => 'Full-Time',
                        'applicants' => 114,
                        'desc'       => 'Shape the future of patient healthcare experiences by defining product requirements and working with clinical cross-functional teams.',
                    ],
                ],
                'reviews'     => [
                    [
                        'name'    => 'Toluwani Balogun',
                        'role'    => 'Product Design Intern',
                        'date'    => 'July 2026',
                        'rating'  => 5,
                        'avatar'  => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=100&h=100&fit=crop&crop=faces',
                        'comment' => 'Designing software that real patients and doctors use every single morning was the most rewarding experience of my academic career.',
                    ],
                ]
            ],

            'brightwave' => [
                'name'        => 'BrightWave',
                'slug'        => 'brightwave',
                'slogan'      => 'Accelerating modern brand growth through data-driven acquisition and creative testing',
                'industry'    => 'Digital Marketing & Media',
                'location'    => 'Lagos, NG',
                'size'        => '11–50 employees',
                'website'     => 'https://brightwave.agency',
                'web_display' => 'brightwave.agency',
                'email'       => 'hello@brightwave.agency',
                'phone'       => '+234 804 777 8899',
                'logo'        => null,
                'logo_bg'     => '#FEF3C7',
                'logo_color'  => '#B45309',
                'initial'     => 'B',
                'banner'      => 'https://images.unsplash.com/photo-1557804506-669a67965ba0?w=1200&auto=format&fit=crop&q=80',
                'verified'    => true,
                'rating'      => '4.7',
                'reviews_cnt' => 12,
                'description' => 'BrightWave is a performance marketing collective helping African and European tech companies scale customer acquisition through viral creative funnels, paid performance marketing, and conversion rate optimization.',
                'departments' => ['Growth Marketing', 'Creative Strategy', 'Paid Media', 'Analytics'],
                'culture'     => [
                    'description' => 'We test hypotheses fast, celebrate data insights over guesswork, and encourage everyone to bring bold creative campaigns to the table.',
                    'values'      => ['Hypothesis-Driven', 'Creative Velocity', 'Data Transparency', 'Customer Focus'],
                ],
                'socials'     => [
                    'website'   => 'https://brightwave.agency',
                    'linkedin'  => 'https://linkedin.com/company/brightwave-agency',
                    'twitter'   => 'https://x.com/BrightWaveHQ',
                ],
                'privacy'     => [
                    'show_email'    => true,
                    'show_phone'    => false,
                    'show_socials'  => true,
                    'show_size'     => true,
                    'show_gallery'  => true,
                    'show_location' => true,
                ],
                'gallery'     => [
                    'https://images.unsplash.com/photo-1522071820081-009f0129c71c?w=800&auto=format&fit=crop&q=80',
                ],
                'openings'    => [
                    [
                        'id'         => 'growth-marketing-intern',
                        'title'      => 'Growth Marketing Intern',
                        'category'   => 'MARKETING',
                        'work_mode'  => 'On-site',
                        'location'   => 'Lagos, NG',
                        'stipend'    => '$3,800/mo',
                        'type'       => 'Part-Time',
                        'applicants' => 64,
                        'desc'       => 'Help us reach more users by running experiments, analyzing multi-channel campaign data, and crafting compelling growth narratives.',
                    ],
                ],
                'reviews'     => [
                    [
                        'name'    => 'Kelechi Nwosu',
                        'role'    => 'Growth Marketing Intern',
                        'date'    => 'June 2026',
                        'rating'  => 5,
                        'avatar'  => 'https://images.unsplash.com/photo-1492562080023-ab3db95bfbce?w=100&h=100&fit=crop&crop=faces',
                        'comment' => 'Managed five-figure ad spend budgets with direct supervision from the managing partner. Unbeatable real-world marketing crash course.',
                    ],
                ]
            ],

            'kiro-labs' => [
                'name'        => 'Kiro Labs',
                'slug'        => 'kiro-labs',
                'slogan'      => 'Pioneering interactive design, motion interfaces, and modern web software',
                'industry'    => 'Design & Digital Media',
                'location'    => 'Remote / Lagos',
                'size'        => '11–50 employees',
                'website'     => 'https://kirolabs.design',
                'web_display' => 'kirolabs.design',
                'email'       => 'hello@kirolabs.design',
                'phone'       => '+234 805 222 3344',
                'logo'        => 'https://res.cloudinary.com/weburea/image/upload/v1790902254/kiro_uhmovc.svg',
                'logo_bg'     => '#000623',
                'banner'      => 'https://images.unsplash.com/photo-1542744094-3a31f272c490?w=1200&auto=format&fit=crop&q=80',
                'verified'    => true,
                'rating'      => '4.9',
                'reviews_cnt' => 14,
                'description' => 'Kiro Labs is a digital product design studio partnering with global founders to build award-winning mobile applications, interactive websites, and comprehensive brand design systems.',
                'departments' => ['UX/UI Design', 'Design Systems', 'Creative Frontend', 'Motion & 3D'],
                'culture'     => [
                    'description' => 'We care deeply about typography, micro-interactions, and high-fidelity prototypes that delight end users.',
                    'values'      => ['Obsessive Craft', 'Micro-Interactions', 'Open Collaboration', 'Rapid Prototyping'],
                ],
                'socials'     => [
                    'website'   => 'https://kirolabs.design',
                    'linkedin'  => 'https://linkedin.com/company/kiro-labs',
                    'twitter'   => 'https://x.com/KiroLabs',
                ],
                'privacy'     => [
                    'show_email'    => true,
                    'show_phone'    => false,
                    'show_socials'  => true,
                    'show_size'     => true,
                    'show_gallery'  => true,
                    'show_location' => true,
                ],
                'gallery'     => [
                    'https://images.unsplash.com/photo-1497366216548-37526070297c?w=800&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1531482615713-2afd69097998?w=800&auto=format&fit=crop&q=80',
                ],
                'openings'    => [
                    [
                        'id'         => 'ux-ui-designer-intern',
                        'title'      => 'UX/UI Designer',
                        'category'   => 'Design',
                        'work_mode'  => 'Remote',
                        'location'   => 'Remote / Lagos',
                        'stipend'    => '₦180,000/mo',
                        'type'       => 'Full-Time',
                        'applicants' => 92,
                        'desc'       => 'Design beautiful, intuitive user journeys and high-fidelity interactive prototypes for global web applications.',
                    ],
                ],
                'reviews'     => [
                    [
                        'name'    => 'Folake Daniels',
                        'role'    => 'Design Systems Intern',
                        'date'    => 'July 2026',
                        'rating'  => 5,
                        'avatar'  => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=100&h=100&fit=crop&crop=faces',
                        'comment' => 'Kiro Labs helped me transform from making static wireframes to building production-ready responsive component systems.',
                    ],
                ]
            ],

            'zod-studio' => [
                'name'        => 'Zod Studio',
                'slug'        => 'zod-studio',
                'slogan'      => 'Multi-channel marketing campaigns, SEO growth engines, and organic engagement',
                'industry'    => 'Marketing & Digital Strategy',
                'location'    => 'Hybrid / Abuja',
                'size'        => '11–50 employees',
                'website'     => 'https://zodstudio.io',
                'web_display' => 'zodstudio.io',
                'email'       => 'info@zodstudio.io',
                'phone'       => '+234 806 333 4455',
                'logo'        => 'https://res.cloudinary.com/weburea/image/upload/v1790902252/zod_fb2rqf.svg',
                'logo_bg'     => '#FFFFFF',
                'banner'      => 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=1200&auto=format&fit=crop&q=80',
                'verified'    => true,
                'rating'      => '4.8',
                'reviews_cnt' => 11,
                'description' => 'Zod Studio drives sustainable organic traffic, performance SEO, and engaging multi-channel narratives for modern consumer technology startups.',
                'departments' => ['Growth Marketing', 'Technical SEO', 'Content Operations', 'Social Strategy'],
                'culture'     => [
                    'description' => 'A supportive, collaborative studio atmosphere where creativity meets analytical curiosity.',
                    'values'      => ['Creative Spark', 'Organic Authenticity', 'Analytical Rigor', 'Team Spirit'],
                ],
                'socials'     => [
                    'website'   => 'https://zodstudio.io',
                    'linkedin'  => 'https://linkedin.com/company/zod-studio',
                    'twitter'   => 'https://x.com/ZodStudio',
                ],
                'privacy'     => [
                    'show_email'    => true,
                    'show_phone'    => false,
                    'show_socials'  => true,
                    'show_size'     => true,
                    'show_gallery'  => true,
                    'show_location' => true,
                ],
                'gallery'     => [
                    'https://images.unsplash.com/photo-1522071820081-009f0129c71c?w=800&auto=format&fit=crop&q=80',
                ],
                'openings'    => [
                    [
                        'id'         => 'digital-marketing-specialist',
                        'title'      => 'Digital Marketing Specialist',
                        'category'   => 'Marketing',
                        'work_mode'  => 'Hybrid',
                        'location'   => 'Hybrid / Abuja',
                        'stipend'    => '₦130,000/mo',
                        'type'       => 'Part-Time',
                        'applicants' => 54,
                        'desc'       => 'Manage multi-channel campaigns, optimize SEO funnels, and scale organic social engagement across tech communities.',
                    ],
                ],
                'reviews'     => [
                    [
                        'name'    => 'Amina Bello',
                        'role'    => 'SEO Strategy Intern',
                        'date'    => 'May 2026',
                        'rating'  => 5,
                        'avatar'  => 'https://images.unsplash.com/photo-1517841905240-472988babdf9?w=100&h=100&fit=crop&crop=faces',
                        'comment' => 'Fantastic team culture in Abuja. I learned how to analyze search intent and rank software landing pages on page one of Google.',
                    ],
                ]
            ],
        ];

        // Graceful fallback for dynamic / other slugs
        if (!isset($catalog[$slug])) {
            $formattedName = ucwords(str_replace(['-', '_'], ' ', $slug));
            $catalog[$slug] = [
                'name'        => $formattedName,
                'slug'        => $slug,
                'slogan'      => 'Innovating at the cutting edge of modern technology',
                'industry'    => 'Technology',
                'location'    => 'Lagos, Nigeria',
                'size'        => '51–200 employees',
                'website'     => 'https://' . $slug . '.com',
                'web_display' => $slug . '.com',
                'email'       => 'careers@' . $slug . '.com',
                'phone'       => '+234 800 000 0000',
                'logo'        => null,
                'logo_bg'     => '#4F47E6',
                'logo_color'  => '#FFFFFF',
                'initial'     => strtoupper(substr($formattedName, 0, 1)),
                'banner'      => 'https://images.unsplash.com/photo-1497366216548-37526070297c?w=1200&auto=format&fit=crop&q=80',
                'verified'    => true,
                'rating'      => '4.8',
                'reviews_cnt' => 10,
                'description' => $formattedName . ' is an innovative enterprise creating impactful products and empowering talented interns to launch long-term tech careers.',
                'departments' => ['Engineering', 'Design', 'Operations'],
                'culture'     => [
                    'description' => 'We foster an inclusive culture that values curious minds, proactive collaboration, and mutual growth.',
                    'values'      => ['Innovation', 'Collaboration', 'Integrity'],
                ],
                'socials'     => [
                    'website' => 'https://' . $slug . '.com',
                ],
                'privacy'     => [
                    'show_email'    => true,
                    'show_phone'    => false,
                    'show_socials'  => true,
                    'show_size'     => true,
                    'show_gallery'  => true,
                    'show_location' => true,
                ],
                'gallery'     => [
                    'https://images.unsplash.com/photo-1522071820081-009f0129c71c?w=800&auto=format&fit=crop&q=80',
                ],
                'openings'    => [],
                'reviews'     => [],
            ];
        }

        $company = $catalog[$slug];

        // Similar companies to explore in right column
        $similarCompanies = [];
        foreach ($catalog as $catSlug => $catComp) {
            if ($catSlug !== $slug && count($similarCompanies) < 4) {
                $similarCompanies[] = [
                    'name'         => $catComp['name'],
                    'slug'         => $catSlug,
                    'industry'     => $catComp['industry'],
                    'logo'         => $catComp['logo'] ?? null,
                    'logo_bg'      => $catComp['logo_bg'] ?? '#4F47E6',
                    'logo_color'   => $catComp['logo_color'] ?? '#FFFFFF',
                    'initial'      => $catComp['initial'] ?? substr($catComp['name'], 0, 1),
                    'openings_cnt' => count($catComp['openings'] ?? []),
                ];
            }
        }

        $this->renderView('landing/company', [
            'title'            => $company['name'] . ' — Verified Company Profile | InternConnect',
            'company'          => $company,
            'similarCompanies' => $similarCompanies,
        ], 'app');
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
        $error = $_SESSION['profile_error'] ?? null;
        unset($_SESSION['profile_success'], $_SESSION['profile_error']);

        $employer = [
            'company_name'          => $_SESSION['employer_company'] ?? 'TechFlow Systems',
            'slogan'                => $_SESSION['employer_slogan'] ?? 'Building innovative digital experiences for the future',
            'cac_number'            => $_SESSION['employer_cac_number'] ?? 'RC-1849204',
            // Business registration document stored privately by UploadStorage
            'business_doc'          => UploadStorage::describe($_SESSION['employer_business_doc'] ?? null, url('/employer/profile/business-doc')),
            'business_doc_name'     => $_SESSION['employer_business_doc']['name'] ?? '',
            'founded_year'          => $_SESSION['employer_founded_year'] ?? '2018',
            'linkedin'              => $_SESSION['employer_linkedin'] ?? 'linkedin.com/company/techflow',
            'twitter'               => $_SESSION['employer_twitter'] ?? '@techflow',
            'pending_email'         => $_SESSION['employer_pending_email']['email'] ?? null,
            'pending_email_dev_code'=> (($_ENV['APP_ENV'] ?? '') === 'local') ? ($_SESSION['employer_pending_email']['dev_code'] ?? null) : null,
            'industry'              => $_SESSION['employer_industry'] ?? 'Software Development',
            'company_size'          => $_SESSION['employer_company_size'] ?? '201–500 employees',
            'website'               => $_SESSION['employer_website'] ?? 'https://techflow.io',
            'website_display'       => 'techflow.io',
            'email'                 => $_SESSION['employer_email'] ?? 'careers@techflow.io',
            'google_email'          => 'techflow.official@gmail.com',
            'phone'                 => $_SESSION['employer_phone'] ?? '+234 801 234 5678',
            'location'              => $_SESSION['employer_location'] ?? 'Victoria Island, Lagos, Nigeria',
            'logo'                  => UploadStorage::path($_SESSION['employer_logo_file'] ?? null) !== null
                ? UploadStorage::describe($_SESSION['employer_logo_file'], url('/employer/profile/logo'))['url']
                : ($_SESSION['employer_logo'] ?? 'https://res.cloudinary.com/weburea/image/upload/v1789424092/internConnect/images/hk_logo.jpg'),
            'banner'                => $_SESSION['employer_banner'] ?? 'https://res.cloudinary.com/weburea/image/upload/v1789424121/internConnect/images/hk_banner.png',
            'description'           => $_SESSION['employer_description'] ?? 'TechFlow Systems is a leading provider of enterprise software solutions, specializing in cloud-native applications and AI-driven analytics. We empower businesses to streamline their operations and unlock new growth opportunities through cutting-edge technology.',
            'culture_description'   => $_SESSION['employer_culture_desc'] ?? 'We believe that a supportive and inclusive environment is key to driving innovation. Our core values guide everything we do, from product development to team collaboration.',
            'departments'           => $_SESSION['employer_departments'] ?? ['Engineering', 'Product Design', 'Marketing', 'Data Science'],
            'expected_volume'       => $_SESSION['employer_expected_volume'] ?? '6 - 15 Interns',
            'internship_type'       => $_SESSION['employer_internship_type'] ?? 'Paid',
            'notif_applicants'      => $_SESSION['employer_notif_applicants'] ?? true,
            'notif_interviews'      => $_SESSION['employer_notif_interviews'] ?? true,
            'notif_announcements'   => $_SESSION['employer_notif_announcements'] ?? false,
            // Public Profile Visibility / Privacy toggles
            'privacy_show_email'    => $_SESSION['employer_privacy_show_email'] ?? true,
            'privacy_show_phone'    => $_SESSION['employer_privacy_show_phone'] ?? false,
            'privacy_show_socials'  => $_SESSION['employer_privacy_show_socials'] ?? true,
            'privacy_show_size'     => $_SESSION['employer_privacy_show_size'] ?? true,
            'privacy_show_gallery'  => $_SESSION['employer_privacy_show_gallery'] ?? true,
            'privacy_show_location' => $_SESSION['employer_privacy_show_location'] ?? true,
            'gallery'               => $_SESSION['employer_gallery'] ?? [
                'https://images.unsplash.com/photo-1497366216548-37526070297c?w=600&auto=format&fit=crop&q=80',
                'https://images.unsplash.com/photo-1522071820081-009f0129c71c?w=600&auto=format&fit=crop&q=80',
                'https://images.unsplash.com/photo-1531482615713-2afd69097998?w=600&auto=format&fit=crop&q=80',
                'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=600&auto=format&fit=crop&q=80',
            ],
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
            'error'    => $error,
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
            $currentPass = (string)($_POST['current_password'] ?? '');
            $newPass     = (string)($_POST['new_password'] ?? '');
            $confirmPass = (string)($_POST['confirm_password'] ?? '');
            $hash        = $_SESSION['employer_password_hash'] ?? '';
            if ($currentPass === '' || $newPass === '' || $confirmPass === '') {
                $_SESSION['profile_error'] = 'Please fill in all password fields.';
            } elseif (strlen($newPass) < 8) {
                $_SESSION['profile_error'] = 'New password must be at least 8 characters long.';
            } elseif ($newPass !== $confirmPass) {
                $_SESSION['profile_error'] = 'New password and confirmation do not match.';
            } elseif ($hash !== '' && !password_verify($currentPass, $hash)) {
                $_SESSION['profile_error'] = 'Your current password is incorrect.';
            } else {
                $_SESSION['employer_password_hash'] = password_hash($newPass, PASSWORD_DEFAULT);
                $_SESSION['profile_success'] = 'Password updated successfully!';
            }
        } elseif ($action === 'verify_email') {
            $pending = $_SESSION['employer_pending_email'] ?? null;
            $code = preg_replace('/\D+/', '', (string)($_POST['email_code'] ?? ''));
            if (!$pending) {
                $_SESSION['profile_error'] = 'There is no email change waiting for confirmation.';
            } elseif (time() > (int)$pending['expires']) {
                unset($_SESSION['employer_pending_email']);
                $_SESSION['profile_error'] = 'That code has expired. Please change your email again to get a new code.';
            } elseif (strlen($code) !== 6 || !hash_equals((string)$pending['code_hash'], hash('sha256', $code))) {
                $_SESSION['profile_error'] = 'That code is not correct. Please check it and try again.';
            } else {
                $_SESSION['employer_email'] = $pending['email'];
                unset($_SESSION['employer_pending_email']);
                $_SESSION['profile_success'] = 'Email address confirmed and updated.';
            }
        } elseif ($action === 'cancel_email') {
            unset($_SESSION['employer_pending_email']);
            $_SESSION['profile_success'] = 'Email change cancelled.';
        } elseif ($action === 'update_privacy') {
            $_SESSION['employer_privacy_show_email']    = isset($_POST['privacy_show_email']);
            $_SESSION['employer_privacy_show_phone']    = isset($_POST['privacy_show_phone']);
            $_SESSION['employer_privacy_show_socials']  = isset($_POST['privacy_show_socials']);
            $_SESSION['employer_privacy_show_size']     = isset($_POST['privacy_show_size']);
            $_SESSION['employer_privacy_show_gallery']  = isset($_POST['privacy_show_gallery']);
            $_SESSION['employer_privacy_show_location'] = isset($_POST['privacy_show_location']);
            $_SESSION['profile_success'] = 'Public profile privacy preferences saved successfully!';
        } elseif ($action === 'add_gallery_photo') {
            $newPhoto = trim($_POST['photo_url'] ?? '');
            if (!empty($newPhoto)) {
                if (!isset($_SESSION['employer_gallery']) || !is_array($_SESSION['employer_gallery'])) {
                    $_SESSION['employer_gallery'] = [
                        'https://images.unsplash.com/photo-1497366216548-37526070297c?w=600&auto=format&fit=crop&q=80',
                        'https://images.unsplash.com/photo-1522071820081-009f0129c71c?w=600&auto=format&fit=crop&q=80',
                        'https://images.unsplash.com/photo-1531482615713-2afd69097998?w=600&auto=format&fit=crop&q=80',
                        'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=600&auto=format&fit=crop&q=80',
                    ];
                }
                $_SESSION['employer_gallery'][] = $newPhoto;
                $_SESSION['profile_success'] = 'New workspace photo added to gallery!';
            }
        } elseif ($action === 'delete_gallery_photo') {
            $index = (int)($_POST['photo_index'] ?? -1);
            if (isset($_SESSION['employer_gallery'][$index])) {
                array_splice($_SESSION['employer_gallery'], $index, 1);
                $_SESSION['profile_success'] = 'Workspace photo removed from gallery.';
            }
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
            // Email changes are confirmed with a 6-digit code before they take effect
            $emailNotice = '';
            $currentEmail = $_SESSION['employer_email'] ?? 'careers@techflow.io';
            $postedEmail = strtolower(trim(strip_tags((string)($_POST['email'] ?? ''))));
            if ($postedEmail !== '' && $postedEmail !== strtolower($currentEmail)) {
                if (!filter_var($postedEmail, FILTER_VALIDATE_EMAIL)) {
                    $emailNotice = ' The new email address is not valid, so it was not changed.';
                } else {
                    $code = (string)random_int(100000, 999999);
                    $_SESSION['employer_pending_email'] = [
                        'email'     => $postedEmail,
                        'code_hash' => hash('sha256', $code),
                        'expires'   => time() + 15 * 60,
                        'dev_code'  => $code,
                    ];
                    // TODO(backend): send $code to $postedEmail via the mailer instead of exposing it locally.
                    $emailNotice = ' Enter the 6-digit code sent to ' . $postedEmail . ' to confirm your new email.';
                }
            }
            foreach (['founded_year' => 4, 'linkedin' => 200, 'twitter' => 60] as $key => $max) {
                if (isset($_POST[$key])) {
                    $_SESSION['employer_' . $key] = mb_substr(trim(strip_tags((string)$_POST[$key])), 0, $max);
                }
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
            $_SESSION['profile_success'] = 'Employer profile settings updated successfully!' . $emailNotice;
        }

        $this->redirect('/employer/profile');
    }

    /**
     * Upload / replace the company logo (AJAX, multipart field `logo`)
     */
    public function uploadLogo(): void
    {
        $this->storeEmployerUpload('logo', 'employer_logo_file', '/employer/profile/logo');
    }

    /**
     * Upload / replace the business registration document (AJAX, multipart field `business_doc`)
     */
    public function uploadBusinessDoc(): void
    {
        $this->storeEmployerUpload('business_doc', 'employer_business_doc', '/employer/profile/business-doc');
    }

    public function streamLogo(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        UploadStorage::stream($_SESSION['employer_logo_file'] ?? null);
    }

    public function streamBusinessDoc(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        UploadStorage::stream($_SESSION['employer_business_doc'] ?? null, isset($_GET['download']));
    }

    public function deleteBusinessDoc(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        UploadStorage::delete($_SESSION['employer_business_doc'] ?? null);
        unset($_SESSION['employer_business_doc']);
        header('Content-Type: application/json');
        echo json_encode(['success' => true]);
        exit;
    }

    private function storeEmployerUpload(string $kind, string $sessionKey, string $viewPath): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        header('Content-Type: application/json');

        $result = UploadStorage::store($_FILES[$kind] ?? null, $kind);
        if (!$result['ok']) {
            http_response_code(422);
            echo json_encode(['success' => false, 'error' => $result['error']]);
            exit;
        }

        UploadStorage::delete($_SESSION[$sessionKey] ?? null);
        $_SESSION[$sessionKey] = $result['meta'];

        echo json_encode(['success' => true, 'file' => UploadStorage::describe($result['meta'], url($viewPath))]);
        exit;
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
            'title'             => '',
            'category'          => 'Technology',
            'internship_type'   => 'Remote',
            'location'          => '',
            'about'             => '',
            'what_you_will_do'  => [
                'Collaborate with cross-functional teams to define, design, and ship new features.',
                'Create wireframes, storyboards, user flows, and site maps to effectively communicate interaction and design ideas.',
                'Conduct user research and evaluate user feedback to enhance the platform\'s UX.'
            ],
            'what_we_look_for'  => [
                'Demonstrated passion for user-centered digital design and interface prototyping.',
                'Familiarity with modern design tools (Figma, Adobe XD) and component systems.',
                'Clear communication and eagerness to learn from senior mentors in a fast-paced environment.'
            ],
            'responsibilities_title' => 'Key Responsibilities',
            'responsibilities' => [''],
            'looking_for_title' => "What We're Looking For",
            'looking_for_cards' => [
                [
                    'icon'  => 'target',
                    'title' => '',
                    'desc'  => ''
                ]
            ],
            'benefits_title' => 'Program Benefits',
            'benefits' => [''],
            'skills'            => [],
            'education_level'   => 'Undergraduate',
            'experience_level'  => 'No Experience',
            'start_date'        => '2026-06-01',
            'duration'          => '2026-08-31',
            'openings'          => 1,
            'stipend_amount'    => '2000',
            'is_unpaid'         => false,
            'perks'             => ['Certificate', 'Letter of Recommendation', 'Flexible work hours'],
            'deadline'          => '2026-05-15',
            'num_interns'       => 1,
            'allow_remote'      => true,
            'interview_type'    => 'Video Interview (Online Meeting Link)',
            'screening_questions' => []
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
            if (empty($screeningQuestions)) {
                $screeningQuestions = [];
            } else {
                foreach ($screeningQuestions as &$sq) {
                    $txt = $sq['question'] ?? $sq['text'] ?? '';
                    $sq['question'] = $txt;
                    $sq['text'] = $txt;
                    $typ = $sq['type_label'] ?? $sq['type'] ?? 'Short Answer';
                    $sq['type'] = $typ;
                    $sq['type_label'] = $typ;
                }
                unset($sq);
            }

            $relevantExperience = [];
            if (!empty($_POST['relevant_experience'])) {
                $decoded = json_decode($_POST['relevant_experience'], true);
                if (is_array($decoded)) {
                    $relevantExperience = $decoded;
                }
            }
            if (empty($relevantExperience)) {
                $relevantExperience = [
                    ['id' => 1, 'label' => '1. Job Title', 'title' => 'Job Title', 'type' => 'Text Input', 'placeholder' => 'Text Input', 'required' => true],
                    ['id' => 2, 'label' => '2. Company', 'title' => 'Company', 'type' => 'Text Input', 'placeholder' => 'Text Input', 'required' => true],
                    ['id' => 3, 'label' => '3. Salary Expectation ($/month)', 'title' => 'Salary Expectation ($/month)', 'type' => 'Currency / Number', 'placeholder' => 'Currency / Number', 'required' => true],
                ];
            } else {
                foreach ($relevantExperience as &$item) {
                    if (empty($item['title']) && !empty($item['label'])) {
                        $item['title'] = preg_replace('/^\d+\.\s*/', '', (string)$item['label']);
                    }
                    if (empty($item['placeholder'])) {
                        $item['placeholder'] = $item['type'] ?? 'Input';
                    }
                }
                unset($item);
            }

            $responsibilities = [];
            if (isset($_POST['responsibilities'])) {
                $decoded = json_decode($_POST['responsibilities'], true);
                if (is_array($decoded)) {
                    $responsibilities = $decoded;
                }
            }
            if (empty($responsibilities)) {
                $responsibilities = [''];
            }

            $lookingForCards = [];
            if (isset($_POST['looking_for_cards'])) {
                $decoded = json_decode($_POST['looking_for_cards'], true);
                if (is_array($decoded)) {
                    $lookingForCards = $decoded;
                }
            }
            if (empty($lookingForCards)) {
                $lookingForCards = [
                    [
                        'icon'  => 'target',
                        'title' => '',
                        'desc'  => ''
                    ]
                ];
            }

            $benefits = [];
            if (isset($_POST['benefits'])) {
                $decoded = json_decode($_POST['benefits'], true);
                if (is_array($decoded)) {
                    $benefits = $decoded;
                }
            }
            if (empty($benefits)) {
                $benefits = [''];
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
                'relevant_experience'   => $relevantExperience,
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
            'relevant_experience'   => [
                ['id' => 1, 'title' => 'Job Title', 'type' => 'short_answer', 'placeholder' => 'Frontend Developer Intern', 'required' => true],
                ['id' => 2, 'title' => 'Company', 'type' => 'short_answer', 'placeholder' => 'TechLabs Nigeria', 'required' => true],
                ['id' => 3, 'title' => 'Salary Expectation ($/month)', 'type' => 'short_answer', 'placeholder' => '2,500', 'required' => true]
            ],
            'screening_questions'   => [
                ['id' => 1, 'question' => 'Why are you interested in joining this specific internship role?', 'type' => 'short_answer', 'placeholder' => 'Short Answer', 'required' => true],
                ['id' => 2, 'question' => 'Share a link to your portfolio or GitHub profile.', 'type' => 'link', 'placeholder' => 'https://...', 'required' => false]
            ],
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
     * Renders the Interviews Overview Dashboard view
     * Route: /employer/interviews
     */
    public function interviews(array $params = []): void
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

        $stats = [
            'scheduled' => 12,
            'completed' => 8,
            'upcoming'  => 4,
            'cancelled' => 4,
        ];

        $this->renderView('employer/interviews/index', [
            'title'       => 'Interviews — Employer Portal | InternConnect',
            'employer'    => $employer,
            'candidates'  => $candidates,
            'stats'       => $stats,
            'activeNav'   => 'interviews',
        ], 'dashboard');
    }

    /**
     * In-Platform Video Interview Room (Powered by Daily.co)
     * Route: /employer/interviews/room/{id}
     */
    public function interviewRoom(array $params = []): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $interviewId = (int)($params['id'] ?? (isset($_GET['id']) ? (int)$_GET['id'] : 1));
        $room = VideoInterviewService::getRoomDetails($interviewId, 'employer');

        $this->renderView('employer/interviews/room', [
            'title'     => 'In-Platform Video Interview — ' . $room['title'] . ' | InternConnect',
            'room'      => $room,
            'activeNav' => 'interviews',
        ], 'dashboard');
    }

    /**
     * Save Interview Evaluation Scorecard API
     * Route: POST /api/interviews/room/score
     */
    public function saveInterviewScore(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $input = json_decode(file_get_contents('php://input'), true) ?? $_POST;
        $interviewId = (int)($input['interview_id'] ?? 1);
        $saved = VideoInterviewService::saveEvaluation($interviewId, $input);

        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'rubric' => $saved]);
        exit;
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
                'match_score'     => 95,
                'match_label'     => 'Top 95% Match • High UI/UX Alignment',
                'status'          => 'Applied',
                'status_type'     => 'applied',
                'student_title'   => 'UI/UX Design Student • Class of 2026 • Lagos, NG',
                'availability'    => 'Immediate Start',
                'relocation'      => 'Open to Relocate',
                'pipeline_stage'  => 'Under Review',
                'summary'         => 'Passionate designer focused on creating intuitive digital experiences. Currently exploring the intersection of accessibility and high-end visual design. Strong foundation in design systems and user-centric research methodologies.',
                'cover_letter'    => "Dear Hiring Team at Flux Technologies,\n\nI am writing to express my enthusiastic interest in the UI Design Intern role. Having researched Flux's design principles and followed your engineering blog on systemic enterprise UX, I deeply admire your commitment to accessible software. My academic projects and previous internship experience have given me hands-on practice building design tokens, Figma auto-layouts, and user-tested prototypes. I would love the opportunity to contribute to your team while learning from your experienced mentors.",
                'relevant_experience_answers' => [
                    [
                        'field' => 'Target Job Title',
                        'value' => 'UI/UX Design Intern',
                        'type'  => 'Text Input'
                    ],
                    [
                        'field' => 'Previous Company / Organization',
                        'value' => 'TechFlow Solutions (Design Intern)',
                        'type'  => 'Text Input'
                    ],
                    [
                        'field' => 'Salary / Stipend Expectation ($/month)',
                        'value' => '$2,000 / month',
                        'type'  => 'Currency / Number'
                    ],
                    [
                        'field' => 'Prior Industry Experience',
                        'value' => '1 Previous Internship • 2 Years Academic Studio Projects',
                        'type'  => 'Text Input'
                    ]
                ],
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
                    'resume_name'       => 'Sarah_Peters_Resume.pdf',
                    'cover_letter_name' => 'Sarah_Peters_Cover_Letter.pdf',
                    'portfolio_url'     => 'https://sarahpeters.design',
                    'linkedin_url'      => 'https://linkedin.com/in/sarahpeters',
                    'github_url'        => 'https://github.com/sarahpeters-design'
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
                'name'            => 'Elena Daniels',
                'avatar'          => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=160&h=160&fit=crop&crop=faces',
                'initials'        => 'ED',
                'role'            => 'Data Science Intern',
                'applied_time'    => 'Applied 3d ago',
                'match_score'     => 88,
                'match_label'     => 'Top 88% Match • High Data Analytics Alignment',
                'status'          => 'Shortlisted',
                'status_type'     => 'shortlisted',
                'student_title'   => 'Data Science Specialist • Class of 2026 • Lagos, NG',
                'availability'    => 'Immediate Start',
                'relocation'      => 'Open to Relocate',
                'pipeline_stage'  => 'Shortlisted',
                'summary'         => 'Analytical and curious data science student skilled in Python, SQL, and predictive machine learning models.',
                'questions'       => [
                    ['q' => 'Why are you interested in our organization?', 'a' => 'Passionate about applying machine learning to real-world business challenges.'],
                    ['q' => 'Describe your proudest technical project.', 'a' => 'Built a churn prediction model with 91% accuracy for an e-commerce platform.'],
                    ['q' => 'What are your primary learning goals?', 'a' => 'Mastering production MLOps and large-scale data pipelines.']
                ],
                'experience'      => [
                    [
                        'title'   => 'Junior Data Analyst Intern',
                        'period'  => 'MAY 2026 - AUG 2026',
                        'company' => 'DataFlow Corp • Hybrid',
                        'desc'    => 'Created automated KPI dashboards in Tableau and optimized SQL queries.'
                    ]
                ],
                'documents'       => [
                    'resume_name'   => 'Elena_Daniels_Resume.pdf',
                    'portfolio_url' => 'https://github.com/edaniels-ds',
                    'linkedin_url'  => 'https://linkedin.com/in/elenadaniels'
                ],
                'contact'         => [
                    'email' => 'e.daniels@intconnect.com',
                    'phone' => '+234 802 345 6789'
                ],
                'education'       => [
                    'degree'     => 'B.Sc. in Computer Science & Statistics',
                    'school'     => 'University of Lagos',
                    'grade'      => 'FIRST CLASS',
                    'gpa'        => 'GPA 3.85/4.0',
                    'graduation' => 'Expected Graduation: Dec 2026'
                ],
                'skills'          => ['Python', 'SQL', 'Pandas', 'Scikit-Learn', 'Tableau', 'Machine Learning']
            ],
            [
                'id'              => 3,
                'name'            => 'Topper Andrew',
                'avatar'          => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=160&h=160&fit=crop&crop=faces',
                'initials'        => 'TA',
                'role'            => 'Data Science Intern',
                'applied_time'    => 'Applied 4d ago',
                'match_score'     => 85,
                'match_label'     => 'Top 85% Match • Strong Quantitative Skills',
                'status'          => 'Shortlisted',
                'status_type'     => 'shortlisted',
                'student_title'   => 'Applied Mathematics & Data Science • Class of 2026 • Lagos, NG',
                'availability'    => 'Immediate Start',
                'relocation'      => 'Open to Relocate',
                'pipeline_stage'  => 'Shortlisted',
                'summary'         => 'Driven researcher with strong statistical modeling background, experienced in neural networks and time-series forecasting.',
                'questions'       => [
                    ['q' => 'Why are you interested in our organization?', 'a' => 'Looking to contribute quantitative models in a fast-paced environment.'],
                    ['q' => 'Describe your proudest technical project.', 'a' => 'Engineered a forecasting algorithm for urban traffic flow.'],
                    ['q' => 'What are your primary learning goals?', 'a' => 'Deep learning architectures and scalable distributed computing.']
                ],
                'experience'      => [
                    [
                        'title'   => 'Research Assistant',
                        'period'  => 'JAN 2026 - AUG 2026',
                        'company' => 'AI Lab • Lagos',
                        'desc'    => 'Analyzed large spatial-temporal datasets and published findings.'
                    ]
                ],
                'documents'       => [
                    'resume_name'   => 'Topper_Andrew_Resume.pdf',
                    'portfolio_url' => 'https://github.com/topper-andrew',
                    'linkedin_url'  => 'https://linkedin.com/in/topperandrew'
                ],
                'contact'         => [
                    'email' => 'topper@intconnect.com',
                    'phone' => '+234 803 123 4567'
                ],
                'education'       => [
                    'degree'     => 'B.Sc. in Applied Mathematics',
                    'school'     => 'University of Ibadan',
                    'grade'      => 'FIRST CLASS',
                    'gpa'        => 'GPA 3.9/4.0',
                    'graduation' => 'Expected Graduation: June 2027'
                ],
                'skills'          => ['Python', 'R', 'TensorFlow', 'SQL', 'Data Analytics', 'Statistics']
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

    /**
     * Renders the employer 1-on-1 direct messaging workspace.
     */
    public function messages(array $params = []): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $activeThreadId = isset($params['id']) ? (int)$params['id'] : (isset($_GET['candidate']) ? (int)$_GET['candidate'] : (isset($_GET['id']) ? (int)$_GET['id'] : 1));

        $employer = [
            'company_name' => $_SESSION['employer_company'] ?? 'Nexura Technologies',
            'role'         => 'Talent Acquisition Team',
            'avatar'       => $_SESSION['employer_avatar'] ?? null,
        ];

        $conversations = [
            [
                'id'               => 1,
                'candidate_id'     => 1,
                'candidate_name'   => 'Sarah Peters',
                'candidate_avatar' => asset('assets/images/profile.png'),
                'role'             => 'UI/UX Design Intern',
                'school'           => 'University of Lagos (UNILAG)',
                'status'           => 'online',
                'last_active'      => 'Online now',
                'unread'           => 1,
                'last_message'     => "Received and confirmed! I've accepted the calendar invite for Thursday.",
                'last_time'        => '10:42 AM',
                'messages'         => [
                    [
                        'id'       => 1,
                        'sender'   => 'candidate',
                        'text'     => "Hello! Thank you for reviewing my design portfolio. I was very excited to see the UI/UX internship opening at Nexura Technologies.",
                        'time'     => '10:15 AM',
                        'status'   => 'read'
                    ],
                    [
                        'id'       => 2,
                        'sender'   => 'employer',
                        'text'     => "Hi Sarah! We reviewed your Oakland banking app case study and were really impressed with the user flow and design tokens.",
                        'time'     => '10:22 AM',
                        'status'   => 'read'
                    ],
                    [
                        'id'       => 3,
                        'sender'   => 'candidate',
                        'text'     => "Thank you so much! I really enjoyed working on the accessibility guidelines for that project. I'm available anytime this week for the technical round.",
                        'time'     => '10:28 AM',
                        'status'   => 'read'
                    ],
                    [
                        'id'       => 4,
                        'sender'   => 'employer',
                        'text'     => "Wonderful. We just sent an interview invitation for Thursday at 10:00 AM via Google Meet. Looking forward to speaking!",
                        'time'     => '10:35 AM',
                        'status'   => 'read'
                    ],
                    [
                        'id'       => 5,
                        'sender'   => 'candidate',
                        'text'     => "Received and confirmed! I've accepted the calendar invite for Thursday.",
                        'time'     => '10:42 AM',
                        'status'   => 'delivered'
                    ]
                ]
            ],
            [
                'id'               => 2,
                'candidate_id'     => 2,
                'candidate_name'   => 'David Adeleke',
                'candidate_avatar' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=100&h=100&fit=crop&crop=faces',
                'role'             => 'Software Engineer Intern (Backend)',
                'school'           => 'Covenant University',
                'status'           => 'offline',
                'last_active'      => 'Active 2h ago',
                'unread'           => 0,
                'last_message'     => 'Thanks David, reviewing this with our lead engineer now.',
                'last_time'        => 'Yesterday',
                'messages'         => [
                    [
                        'id'       => 1,
                        'sender'   => 'employer',
                        'text'     => "Hi David, could you share the GitHub repository link for your distributed cache project?",
                        'time'     => 'Yesterday, 2:15 PM',
                        'status'   => 'read'
                    ],
                    [
                        'id'       => 2,
                        'sender'   => 'candidate',
                        'text'     => "Hello! Yes, absolutely. Here is the link: github.com/davidadeleke/redis-cache-go. It includes benchmark tests and Docker compose setups.",
                        'time'     => 'Yesterday, 2:30 PM',
                        'status'   => 'read'
                    ],
                    [
                        'id'       => 3,
                        'sender'   => 'employer',
                        'text'     => "Thanks David, reviewing this with our lead engineer now.",
                        'time'     => 'Yesterday, 3:00 PM',
                        'status'   => 'read'
                    ]
                ]
            ],
            [
                'id'               => 3,
                'candidate_id'     => 3,
                'candidate_name'   => 'Amara Okafor',
                'candidate_avatar' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=100&h=100&fit=crop&crop=faces',
                'role'             => 'Associate Product Manager Intern',
                'school'           => 'University of Ibadan',
                'status'           => 'online',
                'last_active'      => 'Online now',
                'unread'           => 2,
                'last_message'     => 'Does Nexura support hybrid work arrangements for students traveling from Ibadan to Lagos twice a month?',
                'last_time'        => '1h ago',
                'messages'         => [
                    [
                        'id'       => 1,
                        'sender'   => 'candidate',
                        'text'     => "Good afternoon! I had a quick question regarding the 6-month SIWES duration for the PM internship.",
                        'time'     => 'Yesterday, 4:20 PM',
                        'status'   => 'read'
                    ],
                    [
                        'id'       => 2,
                        'sender'   => 'candidate',
                        'text'     => "Does Nexura support hybrid work arrangements for students traveling from Ibadan to Lagos twice a month?",
                        'time'     => '1h ago',
                        'status'   => 'delivered'
                    ]
                ]
            ],
            [
                'id'               => 4,
                'candidate_id'     => 4,
                'candidate_name'   => 'Tunde Bakare',
                'candidate_avatar' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=100&h=100&fit=crop&crop=faces',
                'role'             => 'Data Analyst Intern',
                'school'           => 'Federal University of Technology, Akure',
                'status'           => 'offline',
                'last_active'      => 'Active 1d ago',
                'unread'           => 0,
                'last_message'     => 'Thank you! I am ready whenever your team is free.',
                'last_time'        => '2 days ago',
                'messages'         => [
                    [
                        'id'       => 1,
                        'sender'   => 'employer',
                        'text'     => "Hi Tunde, we loved your SQL analytics case study. We'd like to invite you for an introductory call next week.",
                        'time'     => '2 days ago',
                        'status'   => 'read'
                    ],
                    [
                        'id'       => 2,
                        'sender'   => 'candidate',
                        'text'     => "Thank you! I am ready whenever your team is free.",
                        'time'     => '2 days ago',
                        'status'   => 'read'
                    ]
                ]
            ]
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

        $this->renderView('employer/messages/index', [
            'title'          => 'Messages — Employer Portal — InternConnect',
            'activeNav'      => 'messages',
            'employer'       => $employer,
            'conversations'  => $conversations,
            'activeThreadId' => $activeThreadId,
        ], 'dashboard');
    }

    /**
     * Renders the Employer AI Assistant Hub powered by Jenny AI.
     * Free for employers to post roles, match candidates, and auto-schedule interviews.
     */
    public function aiAssistant(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $employer = [
            'company_name' => $_SESSION['employer_company'] ?? 'Global Tech Solutions',
            'role'         => 'Hiring Lead',
            'first_name'   => $_SESSION['employer_first_name'] ?? 'Tunde',
            'avatar'       => $_SESSION['employer_avatar'] ?? null,
        ];

        // AI Match Candidates for employer roles
        $aiMatchedCandidates = [
            [
                'id'          => 1,
                'name'        => 'Sarah Peters',
                'role'        => 'UI/UX Design Intern',
                'school'      => 'University of Lagos',
                'match_score' => 96,
                'match_reason'=> 'Figma design system & user research match your opening',
                'status'      => 'Ready for Interview',
                'avatar'      => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=100&h=100&fit=crop&crop=faces',
            ],
            [
                'id'          => 2,
                'name'        => 'Chukwuemeka Okonkwo',
                'role'        => 'Full-Stack Developer Intern',
                'school'      => 'Covenant University',
                'match_score' => 93,
                'match_reason'=> 'React.js, PHP and REST API portfolio matches technical criteria',
                'status'      => 'Shortlisted',
                'avatar'      => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=100&h=100&fit=crop&crop=faces',
            ],
            [
                'id'          => 3,
                'name'        => 'Amina Bello',
                'role'        => 'Data Analyst Intern',
                'school'      => 'Ahmadu Bello University',
                'match_score' => 90,
                'match_reason'=> 'SQL coursework & Python analytics align with department needs',
                'status'      => 'Application Reviewed',
                'avatar'      => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=100&h=100&fit=crop&crop=faces',
            ]
        ];

        // Active Roles AI is assisting with
        $activeRoles = [
            [
                'title'       => 'Product Design Intern',
                'applicants'  => 42,
                'high_matches'=> 8,
                'status'      => 'Active & Optimized',
            ],
            [
                'title'       => 'Junior Backend Engineer',
                'applicants'  => 29,
                'high_matches'=> 6,
                'status'      => 'Active & Optimized',
            ]
        ];

        $this->renderView('employer/ai-assistant/index', [
            'title'               => 'Jenny AI Assistant — Employer Portal — InternConnect',
            'activeNav'           => 'ai-assistant',
            'employer'            => $employer,
            'aiMatchedCandidates' => $aiMatchedCandidates,
            'activeRoles'         => $activeRoles,
        ], 'dashboard');
    }

    /**
     * View: Employer Notifications Center
     * Route: /employer/notifications
     */
    public function notifications(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $employer = [
            'company_name' => $_SESSION['employer_company'] ?? 'Nexura Technologies',
            'role'         => 'Talent Acquisition Team',
            'first_name'   => $_SESSION['employer_first_name'] ?? 'Tunde',
            'avatar'       => $_SESSION['employer_avatar'] ?? null,
        ];

        $notifications = CommunicationService::presentNotifications(
            CommunicationService::getNotifications('employer'),
            'employer'
        );

        $this->renderView('employer/notifications/index', [
            'title'          => 'Notifications Center — Employer Portal — InternConnect',
            'activeNav'      => 'notifications',
            'employer'       => $employer,
            'notifications'  => $notifications,
            'notifCategories'=> CommunicationService::notificationCategories('employer'),
        ], 'dashboard');
    }

    /**
     * Send an employer direct message
     */
    public function sendMessage(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $input = json_decode(file_get_contents('php://input'), true) ?? $_POST;
        $threadId = (int)($input['thread_id'] ?? 1);
        $content = trim((string)($input['content'] ?? ($input['text'] ?? '')));
        $senderName = $_SESSION['employer_company'] ?? 'Nexura Technologies';

        if ($content === '') {
            http_response_code(422);
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Message content cannot be empty']);
            exit;
        }

        $msg = CommunicationService::sendDirectMessage($threadId, 'employer', $senderName, $content);

        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'message' => $msg]);
        exit;
    }

    /**
     * Mark an employer notification as read
     */
    public function markNotificationRead(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $input = json_decode(file_get_contents('php://input'), true) ?? $_POST;
        $id = (int)($input['id'] ?? 0);

        CommunicationService::markNotificationRead($id, 'employer');

        header('Content-Type: application/json');
        echo json_encode(['success' => true]);
        exit;
    }

    /**
     * Delete a single employer notification (AJAX)
     */
    public function deleteNotification(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $input = json_decode(file_get_contents('php://input'), true) ?? $_POST;
        $id = (int)($input['id'] ?? 0);
        $ok = $id > 0 && CommunicationService::deleteNotification($id, 'employer');

        header('Content-Type: application/json');
        echo json_encode(['success' => $ok]);
        exit;
    }
    /**
     * Mark all employer notifications as read
     */
    public function markAllNotificationsRead(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        CommunicationService::markAllNotificationsRead('employer');

        header('Content-Type: application/json');
        echo json_encode(['success' => true]);
        exit;
    }

    /**
     * Process employer interview scheduling and trigger candidate notification
     */
    public function processScheduleInterview(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $input = json_decode(file_get_contents('php://input'), true) ?? $_POST;
        $payload = [
            'candidate_id'     => (int)($input['candidate_id'] ?? 1),
            'employer_id'      => 1,
            'candidate_name'   => $input['candidate_name'] ?? 'Sarah Peters',
            'candidate_role'   => $input['candidate_role'] ?? 'UI/UX Design Intern',
            'company_name'     => $_SESSION['employer_company'] ?? 'Nexura Technologies',
            'interviewer_name' => $_SESSION['employer_first_name'] ?? 'Tunde Bakare',
            'meeting_type'     => $input['meeting_type'] ?? 'virtual',
            'meeting_link'     => $input['meeting_link'] ?? 'https://meet.google.com/nxt-uxin-2026',
            'date'             => $input['date'] ?? date('m/d/Y', strtotime('+3 days')),
            'time'             => $input['time'] ?? '10:00 AM',
            'duration'         => $input['duration'] ?? '45 mins',
            'notes'            => $input['notes'] ?? '',
        ];

        $interview = CommunicationService::scheduleInterview($payload);

        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'interview' => $interview]);
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
     * View: Employer Hiring Analytics & Enterprise Reports (Phase 5)
     * Route: /employer/analytics
     */
    public function analytics(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $employer = [
            'company_name' => $_SESSION['employer_company'] ?? 'Nexura Technologies',
            'role'         => 'Talent Acquisition Team',
            'avatar'       => $_SESSION['employer_avatar'] ?? null,
        ];

        $kpis = [
            'total_views' => [
                'val'    => '1,840',
                'change' => '+24.5%',
                'trend'  => 'up',
                'sub'    => 'Across 4 active postings'
            ],
            'applications_received' => [
                'val'    => '142',
                'change' => '+18.2%',
                'trend'  => 'up',
                'sub'    => '7.7% view-to-apply rate'
            ],
            'interviews_scheduled' => [
                'val'    => '26',
                'change' => '+34.2%',
                'trend'  => 'up',
                'sub'    => '18.3% interview conversion'
            ],
            'avg_time_to_hire' => [
                'val'    => '11.4 Days',
                'change' => '-3.2 Days',
                'trend'  => 'down',
                'sub'    => 'Faster than 82% of tech firms'
            ],
            'offers_accepted' => [
                'val'    => '12',
                'change' => '88.5%',
                'trend'  => 'up',
                'sub'    => 'High-yield closing rate'
            ]
        ];

        $funnel = [
            ['stage' => 'Job Views',               'count' => 1840, 'percentage' => 100,  'dropoff' => '0%'],
            ['stage' => 'Applications Started',   'count' => 520,  'percentage' => 28.3, 'dropoff' => '-71.7%'],
            ['stage' => 'Applications Submitted', 'count' => 142,  'percentage' => 7.7,  'dropoff' => '-72.7%'],
            ['stage' => 'Candidates Shortlisted', 'count' => 48,   'percentage' => 2.6,  'dropoff' => '-66.2%'],
            ['stage' => 'Interviews Conducted',   'count' => 26,   'percentage' => 1.4,  'dropoff' => '-45.8%'],
            ['stage' => 'Offers Extended & Hired','count' => 12,   'percentage' => 0.65, 'dropoff' => '-53.8%'],
        ];

        $departments = [
            [
                'name'         => 'UI/UX Design',
                'openings'     => 1,
                'applicants'   => 48,
                'interviews'   => 8,
                'avg_days'     => '9.5 Days',
                'top_source'   => 'UNILAG (62%)',
                'status'       => 'Active Interviewing'
            ],
            [
                'name'         => 'Backend Engineering',
                'openings'     => 2,
                'applicants'   => 42,
                'interviews'   => 10,
                'avg_days'     => '12.1 Days',
                'top_source'   => 'Covenant Univ (45%)',
                'status'       => 'Technical Challenge'
            ],
            [
                'name'         => 'Frontend Development',
                'openings'     => 1,
                'applicants'   => 32,
                'interviews'   => 5,
                'avg_days'     => '11.0 Days',
                'top_source'   => 'FUTA (38%)',
                'status'       => 'Screening'
            ],
            [
                'name'         => 'Data & Product Analytics',
                'openings'     => 1,
                'applicants'   => 20,
                'interviews'   => 3,
                'avg_days'     => '14.2 Days',
                'top_source'   => 'OAU (40%)',
                'status'       => 'Shortlisted'
            ]
        ];

        $sources = [
            ['name' => 'University of Lagos (UNILAG)', 'percentage' => 38, 'color' => '#4F48E6'],
            ['name' => 'Covenant University',          'percentage' => 26, 'color' => '#06B6D4'],
            ['name' => 'Federal Univ of Tech Akure',   'percentage' => 18, 'color' => '#10B981'],
            ['name' => 'Obafemi Awolowo Univ (OAU)',   'percentage' => 12, 'color' => '#F59E0B'],
            ['name' => 'Other Tertiary Institutions',  'percentage' => 6,  'color' => '#94A3B8'],
        ];

        $this->renderView('employer/analytics/index', [
            'title'        => 'Hiring Analytics & Funnel Reports — Employer Portal — InternConnect',
            'activeNav'    => 'analytics',
            'employer'     => $employer,
            'kpis'         => $kpis,
            'funnel'       => $funnel,
            'departments'  => $departments,
            'sources'      => $sources,
        ], 'dashboard');
    }

    /**
     * Download Candidate Pipeline CSV Export
     */
    public function exportCandidates(): void
    {
        $candidates = $this->getMockCandidates();
        ExportService::exportCandidatesPipeline($candidates);
    }

    /**
     * Download Interviews Schedule CSV Export
     */
    public function exportInterviews(): void
    {
        $interviews = [
            [
                'id'               => 1,
                'candidate_name'   => 'Sarah Peters',
                'candidate_role'   => 'UI/UX Design Intern',
                'company_name'     => 'Nexura Technologies',
                'interviewer_name' => 'Tunde Bakare',
                'meeting_type'     => 'Virtual (Google Meet)',
                'interview_date'   => '2026-10-26',
                'interview_time'   => '10:00 AM',
                'duration'         => '45 mins',
                'meeting_link'     => 'https://meet.google.com/nxt-uxin-2026',
                'status'           => 'Confirmed',
            ],
            [
                'id'               => 2,
                'candidate_name'   => 'David Adeleke',
                'candidate_role'   => 'Software Engineer Intern (Backend)',
                'company_name'     => 'Nexura Technologies',
                'interviewer_name' => 'Tunde Bakare',
                'meeting_type'     => 'Virtual (Google Meet)',
                'interview_date'   => '2026-10-27',
                'interview_time'   => '02:00 PM',
                'duration'         => '60 mins',
                'meeting_link'     => 'https://meet.google.com/nxt-be-2026',
                'status'           => 'Scheduled',
            ]
        ];
        ExportService::exportInterviews($interviews);
    }

    /**
     * Download Billing & Paystack Ledger CSV Export
     */
    public function exportBilling(): void
    {
        $transactions = [
            [
                'reference' => 'TRX_NX_99421',
                'plan'      => 'Pro Recruiter Monthly',
                'cycle'     => 'Monthly',
                'amount'    => 24900,
                'method'    => 'Paystack',
                'status'    => 'Successful',
                'date'      => date('Y-m-d H:i:s', strtotime('-15 days')),
            ],
            [
                'reference' => 'TRX_NX_88301',
                'plan'      => 'Pro Recruiter Monthly',
                'cycle'     => 'Monthly',
                'amount'    => 24900,
                'method'    => 'Paystack',
                'status'    => 'Successful',
                'date'      => date('Y-m-d H:i:s', strtotime('-45 days')),
            ]
        ];
        ExportService::exportBillingLedger($transactions);
    }
}

