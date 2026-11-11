<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;

class HomeController extends Controller
{
    public function index(): void
    {
        $this->renderView('landing/index', [
            'title'           => 'InternConnect — AI-Powered Internship & Early-Career Platform in Nigeria',
            'metaDescription' => 'Discover verified student internships, SIWES placements, and graduate trainee roles across Nigeria. Connect with top employers through AI skill matching and direct scheduling.',
        ]);
    }

    public function pricing(): void
    {
        $this->renderView('landing/pricing', [
            'title'           => 'Subscription & Pricing Plans — For Students & Employers | InternConnect',
            'metaDescription' => 'Transparent pricing plans for Nigerian students and employers. Unlock AI Resume Optimization, priority recruiter visibility, and verified applicant pipelines.',
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
        ]);
    }

    public function howItWorks(): void
    {
        $this->renderView('landing/how-it-works', [
            'title'           => 'How InternConnect Works — For Students & Employers | InternConnect',
            'metaDescription' => 'Explore how students find verified internships and how Nigerian employers discover, interview, and hire early-career talent seamlessly.',
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
            'title'           => 'About Us — Bridging Nigerian Talent with Opportunity | InternConnect',
            'metaDescription' => 'Learn about InternConnect\'s mission to connect university students and graduates with Nigeria\'s fastest growing tech, finance, and creative companies.',
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
