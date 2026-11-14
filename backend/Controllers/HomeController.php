<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;

class HomeController extends Controller
{
    public function index(): void
    {
        $this->renderView('landing/index', [
            'title'           => 'InternConnect — AI Internship & Early-Career Platform',
            'metaDescription' => 'Find verified internships and graduate roles worldwide. Jenny AI matches your skills, polishes your CV and helps you prepare, while employers hire ready-to-work interns.',
        ]);
    }

    public function pricing(): void
    {
        $this->renderView('landing/pricing', [
            'title'           => 'Pricing Plans for Students & Employers | InternConnect',
            'metaDescription' => 'Simple Free, Pro and Premium plans for students and employers. Unlock more applications, Jenny AI help, premium listings and priority recruiter visibility.',
        ]);
    }

    public function payments(): void
    {
        $plan = $_GET['plan'] ?? 'pro';
        $this->renderView('landing/payment', [
            'title'           => 'Secure Paystack Checkout — InternConnect',
            'metaDescription' => 'Complete your subscription upgrade safely via Paystack. Instant activation for Pro and Premium memberships.',
            'plan'            => htmlspecialchars((string)$plan)
        ]);
    }

    public function elearning(): void
    {
        $this->renderView('landing/elearning', [
            'title'           => 'E-Learning & Career Academy — Courses & Roadmaps | InternConnect',
            'metaDescription' => 'Level up your technical and professional skills with industry-curated courses in UI/UX design, software engineering, and data analytics.',
            'programs'        => \App\Services\ElearningService::catalog(),
        ]);
    }

    /**
     * "Visit Platform & Enroll": record the referral, then send the visitor to the partner.
     * Route: GET /elearning/go/{id}
     */
    public function elearningGo(array $params = []): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $candidateId = !empty($_SESSION['candidate_id']) ? (int)$_SESSION['candidate_id'] : (!empty($_SESSION['is_logged_in']) ? 1 : null);
        $target = \App\Services\ElearningService::referralTarget((int)($params['id'] ?? 0), $candidateId);
        header('Location: ' . ($target ?? url('/elearning')), true, 302);
        exit;
    }

    /** Route: GET /elearning/logo/{id} (partner logo uploaded by an admin) */
    public function elearningLogo(array $params = []): void
    {
        \App\Services\ElearningService::streamLogo((int)($params['id'] ?? 0));
    }

    public function howItWorks(): void
    {
        $this->renderView('landing/how-it-works', [
            'title'           => 'How InternConnect Works — For Students & Employers | InternConnect',
            'metaDescription' => 'See how students find verified internships and how employers discover, interview and hire early-career talent on InternConnect.',
        ]);
    }

    public function contact(): void
    {
        $this->renderView('landing/contact', [
            'title'           => 'Contact Us — Support & Enterprise Partnerships | InternConnect',
            'metaDescription' => 'Get in touch with the InternConnect team in Lagos, Nigeria for candidate support, university partnerships, and corporate hiring inquiries.',
        ]);
    }

    public function about(): void
    {
        $this->renderView('landing/about', [
            'title'           => 'About Us — Connecting Talent with Opportunity | InternConnect',
            'metaDescription' => 'Learn about InternConnect\'s mission to connect students and graduates everywhere with fast-growing tech, finance and creative companies.',
        ]);
    }

    public function testimonials(): void
    {
        $this->renderView('landing/testimonials', [
            'title'           => 'Success Stories & Alumni Reviews — InternConnect',
            'metaDescription' => 'Read real stories from students across UNILAG, Covenant, FUTA, and UI who secured top tech and finance internships through InternConnect.',
        ]);
    }

    public function refund(): void
    {
        $this->renderView('legal/refund', [
            'title'           => 'Refund & Cancellation Policy | InternConnect',
            'metaDescription' => 'Learn about our clear, student-friendly 7-day money-back guarantee and subscription cancellation policies.',
        ]);
    }

    public function cookies(): void
    {
        $this->renderView('legal/cookies', [
            'title'           => 'Cookie Policy & Consent Management | InternConnect',
            'metaDescription' => 'Understand how InternConnect uses first-party and essential cookies to provide seamless user sessions and security.',
        ]);
    }

    public function privacy(): void
    {
        $this->renderView('legal/privacy', [
            'title'           => 'Privacy Policy & NDPR Compliance | InternConnect',
            'metaDescription' => 'Our commitment to data privacy, student credential protection, and Nigeria Data Protection Regulation (NDPR) compliance.',
        ]);
    }

    public function terms(): void
    {
        $this->renderView('legal/terms', [
            'title'           => 'Terms of Service & Platform Agreement | InternConnect',
            'metaDescription' => 'User terms and conditions governing student applicants, universities, and corporate employers on the InternConnect network.',
        ]);
    }

    /**
     * Dynamic XML Sitemap for Search Engine Crawlers
     * Route: /sitemap.xml
     */
    public function sitemap(): void
    {
        header('Content-Type: application/xml; charset=UTF-8');

        $baseUrl = function_exists('absolute_url') ? rtrim(absolute_url('/'), '/') : 'https://internconnect.ng';

        $pages = [
            ['loc' => $baseUrl . '/',               'changefreq' => 'daily',   'priority' => '1.0'],
            ['loc' => $baseUrl . '/internships',     'changefreq' => 'hourly',  'priority' => '0.9'],
            ['loc' => $baseUrl . '/companies',       'changefreq' => 'weekly',  'priority' => '0.8'],
            ['loc' => $baseUrl . '/pricing',         'changefreq' => 'weekly',  'priority' => '0.8'],
            ['loc' => $baseUrl . '/how-it-works',    'changefreq' => 'monthly', 'priority' => '0.7'],
            ['loc' => $baseUrl . '/elearning',       'changefreq' => 'weekly',  'priority' => '0.7'],
            ['loc' => $baseUrl . '/about',           'changefreq' => 'monthly', 'priority' => '0.6'],
            ['loc' => $baseUrl . '/contact',         'changefreq' => 'monthly', 'priority' => '0.6'],
            ['loc' => $baseUrl . '/testimonials',   'changefreq' => 'monthly', 'priority' => '0.6'],
            ['loc' => $baseUrl . '/privacy',         'changefreq' => 'yearly',  'priority' => '0.3'],
            ['loc' => $baseUrl . '/terms',           'changefreq' => 'yearly',  'priority' => '0.3'],
            ['loc' => $baseUrl . '/cookies',         'changefreq' => 'yearly',  'priority' => '0.3'],
            ['loc' => $baseUrl . '/refund',          'changefreq' => 'yearly',  'priority' => '0.3'],
        ];

        echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
        echo "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";
        foreach ($pages as $p) {
            echo "  <url>\n";
            echo "    <loc>" . htmlspecialchars($p['loc'], ENT_XML1, 'UTF-8') . "</loc>\n";
            echo "    <lastmod>" . date('Y-m-d') . "</lastmod>\n";
            echo "    <changefreq>{$p['changefreq']}</changefreq>\n";
            echo "    <priority>{$p['priority']}</priority>\n";
            echo "  </url>\n";
        }
        echo "</urlset>\n";
        exit;
    }

    /**
     * Dynamic robots.txt
     * Route: /robots.txt
     */
    public function robots(): void
    {
        header('Content-Type: text/plain; charset=UTF-8');

        $baseUrl = function_exists('absolute_url') ? rtrim(absolute_url('/'), '/') : 'https://internconnect.ng';

        echo "User-agent: *\n";
        echo "Allow: /\n";
        echo "Disallow: /candidate/\n";
        echo "Disallow: /employer/\n";
        echo "Disallow: /backend/\n";
        echo "Disallow: /admin/\n";
        echo "\n";
        echo "Sitemap: {$baseUrl}/sitemap.xml\n";
        exit;
    }
}
