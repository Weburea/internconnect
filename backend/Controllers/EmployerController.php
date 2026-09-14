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

        $employer = [
            'company_name'     => 'TechFlow Systems',
            'slogan'           => 'Building innovative digital experiences for the future',
            'verified'         => true,
            'industry'         => 'Software Development',
            'location'         => 'Lagos, Nigeria',
            'size'             => '201–500 employees',
            'website'          => 'https://techflow.io',
            'website_display'  => 'techflow.io',
            'email'            => 'careers@techflow.io',
            'phone'            => '+234 801 234 5678',
            'logo'             => 'https://res.cloudinary.com/weburea/image/upload/v1789424092/internConnect/images/hk_logo.jpg',
            'banner'           => 'https://res.cloudinary.com/weburea/image/upload/v1789424121/internConnect/images/hk_banner.png',
            'profile_strength' => 78,
            'role'             => 'Employer Portal',
        ];

        $about = [
            'description' => 'TechFlow Systems is a leading provider of enterprise software solutions, specializing in cloud-native applications and AI-driven analytics. We empower businesses to streamline their operations and unlock new growth opportunities through cutting-edge technology. Our team is passionate about solving complex problems and delivering exceptional value to our clients worldwide.',
            'tags' => ['Innovation', 'Collaboration', 'Growth'],
        ];

        $culture = [
            'description' => 'We believe that a supportive and inclusive environment is key to driving innovation. Our core values guide everything we do, from product development to team collaboration.',
            'tags' => ['Innovation', 'Collaboration', 'Growth'],
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
}
