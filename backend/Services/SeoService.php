<?php

declare(strict_types=1);

namespace App\Services;

use Spatie\SchemaOrg\Schema;

/**
 * Class SeoService
 * 
 * Production-grade Systematic SEO and Metadata engine for InternConnect.
 * Generates semantic meta tags, OpenGraph, Twitter Cards, Nigerian Local SEO tags,
 * and Google-compliant Schema.org JSON-LD structured data.
 */
class SeoService
{
    private static ?self $instance = null;

    private string $siteName;
    private string $title;
    private string $description;
    private array $keywords = [];
    private string $canonicalUrl = '';
    private string $ogImage = '';
    private string $ogType = 'website';
    private string $robots = 'index, follow';
    private string $twitterHandle = '@InternConnectNG';
    private string $locale = 'en_NG';
    private string $geoRegion = 'NG-LA';
    private string $geoPlacename = 'Lagos, Nigeria';
    private string $geoPosition = '6.5244;3.3792';
    private string $icbm = '6.5244, 3.3792';
    private string $googleVerification = '';
    private string $bingVerification = '';

    private array $breadcrumbs = [];
    private ?array $jobPostingData = null;
    private array $faqItems = [];
    private array $customSchemas = [];

    public function __construct()
    {
        $this->siteName = $_ENV['SEO_SITE_NAME'] ?? 'InternConnect';
        $this->title = $_ENV['SEO_SITE_TITLE_DEFAULT'] ?? 'InternConnect — AI-Powered Internship Platform in Nigeria';
        $this->description = $_ENV['SEO_DESCRIPTION_DEFAULT'] ?? 'Connecting Nigerian students, interns, and fresh graduates with verified employers. AI matching, resume optimization, and instant interview scheduling.';
        
        $keywordsRaw = $_ENV['SEO_KEYWORDS_DEFAULT'] ?? 'internships in nigeria, student internships lagos, SIWES placement, NYSC jobs, tech internships nigeria';
        $this->keywords = array_filter(array_map('trim', explode(',', $keywordsRaw)));

        $this->ogImage = $_ENV['SEO_DEFAULT_OG_IMAGE'] ?? 'https://res.cloudinary.com/weburea/image/upload/v1785517512/Logo_single_dark_dj6vhf.png';
        $this->twitterHandle = $_ENV['SEO_TWITTER_HANDLE'] ?? '@InternConnectNG';
        $this->locale = $_ENV['SEO_LOCALE'] ?? 'en_NG';
        $this->geoRegion = $_ENV['SEO_GEO_REGION'] ?? 'NG-LA';
        $this->geoPlacename = $_ENV['SEO_GEO_PLACENAME'] ?? 'Lagos, Nigeria';
        $this->geoPosition = $_ENV['SEO_GEO_POSITION'] ?? '6.5244;3.3792';
        $this->icbm = $_ENV['SEO_ICBM'] ?? '6.5244, 3.3792';
        $this->robots = $_ENV['SEO_ROBOTS_DEFAULT'] ?? 'index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1';
        $this->googleVerification = $_ENV['SEO_GOOGLE_SITE_VERIFICATION'] ?? '';
        $this->bingVerification = $_ENV['SEO_BING_SITE_VERIFICATION'] ?? '';

        $this->resolveCanonicalUrl();
    }

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function resolveCanonicalUrl(): void
    {
        $uri = $_SERVER['REQUEST_URI'] ?? '/';
        $path = parse_url($uri, PHP_URL_PATH) ?? '/';

        $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
        $basePath = str_replace(['/public/index.php', '/index.php'], '', $scriptName);

        if (!empty($basePath) && str_starts_with($path, $basePath)) {
            $path = substr($path, strlen($basePath));
        }

        $envBase = rtrim($_ENV['SEO_CANONICAL_BASE_URL'] ?? '', '/');
        if (!empty($envBase) && !str_contains($envBase, 'localhost')) {
            $this->canonicalUrl = $envBase . '/' . ltrim($path, '/');
        } elseif (function_exists('absolute_url')) {
            $this->canonicalUrl = absolute_url($path ?: '/');
        } else {
            $scheme = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? 'https' : 'http';
            $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
            $this->canonicalUrl = $scheme . '://' . $host . '/' . ltrim($path, '/');
        }
    }

    public function setTitle(string $title, bool $appendSiteName = true): self
    {
        $cleanTitle = trim($title);
        if ($appendSiteName && !str_contains($cleanTitle, $this->siteName)) {
            $this->title = $cleanTitle . ' — ' . $this->siteName;
        } else {
            $this->title = $cleanTitle;
        }
        return $this;
    }

    public function setDescription(string $description): self
    {
        $this->description = trim($description);
        return $this;
    }

    public function setKeywords(array|string $keywords): self
    {
        if (is_string($keywords)) {
            $keywords = array_map('trim', explode(',', $keywords));
        }
        $this->keywords = array_filter($keywords);
        return $this;
    }

    public function setCanonical(string $url): self
    {
        $this->canonicalUrl = $url;
        return $this;
    }

    public function setImage(string $imageUrl): self
    {
        $this->ogImage = $imageUrl;
        return $this;
    }

    public function setType(string $ogType): self
    {
        $this->ogType = $ogType;
        return $this;
    }

    public function setRobots(string $robots): self
    {
        $this->robots = $robots;
        return $this;
    }

    public function addBreadcrumb(string $name, string $url): self
    {
        $this->breadcrumbs[] = [
            'name' => $name,
            'url'  => $url,
        ];
        return $this;
    }

    public function setJobPosting(array $data): self
    {
        $this->jobPostingData = $data;
        $this->ogType = 'article';
        return $this;
    }

    public function setFaq(array $items): self
    {
        $this->faqItems = $items;
        return $this;
    }

    public function addSchema(mixed $schema): self
    {
        $this->customSchemas[] = $schema;
        return $this;
    }

    /**
     * Build all Schema.org structured data graph objects using spatie/schema-org
     */
    public function buildStructuredData(): array
    {
        $schemas = [];
        $siteUrl = function_exists('absolute_url') ? absolute_url('/') : 'https://internconnect.ng';

        // 1. WebSite Schema with SearchAction
        $searchTarget = (function_exists('absolute_url') ? absolute_url('/search') : $siteUrl . '/search') . '?q={search_term_string}';
        $websiteSchema = Schema::webSite()
            ->name($this->siteName)
            ->url($siteUrl)
            ->description($this->description)
            ->inLanguage('en-NG')
            ->potentialAction(
                Schema::searchAction()
                    ->target($searchTarget)
                    ->setProperty('query-input', 'required name=search_term_string')
            );
        $schemas[] = $websiteSchema;

        // 2. Organization Schema
        $orgSchema = Schema::organization()
            ->name($this->siteName)
            ->url($siteUrl)
            ->logo($this->ogImage)
            ->description('The leading AI-assisted internship and career acceleration network in Nigeria.')
            ->address(
                Schema::postalAddress()
                    ->addressLocality('Lagos')
                    ->addressRegion('Lagos State')
                    ->addressCountry('NG')
            )
            ->sameAs([
                'https://twitter.com/InternConnectNG',
                'https://linkedin.com/company/internconnect-ng',
                'https://instagram.com/internconnect_ng'
            ]);
        $schemas[] = $orgSchema;

        // 3. BreadcrumbList Schema (if configured)
        if (!empty($this->breadcrumbs)) {
            $itemListElements = [];
            $pos = 1;
            foreach ($this->breadcrumbs as $crumb) {
                $itemListElements[] = Schema::listItem()
                    ->position($pos++)
                    ->name($crumb['name'])
                    ->item($crumb['url']);
            }
            $breadcrumbSchema = Schema::breadcrumbList()
                ->itemListElement($itemListElements);
            $schemas[] = $breadcrumbSchema;
        }

        // 4. JobPosting Schema (for internship detail views)
        if (!empty($this->jobPostingData)) {
            $jp = $this->jobPostingData;
            $jobSchema = Schema::jobPosting()
                ->title($jp['title'] ?? 'Internship')
                ->description($jp['description'] ?? $this->description)
                ->datePosted($jp['date_posted'] ?? date('Y-m-d'))
                ->validThrough($jp['deadline'] ?? date('Y-m-d', strtotime('+60 days')))
                ->employmentType($jp['employment_type'] ?? 'INTERN')
                ->hiringOrganization(
                    Schema::organization()
                        ->name($jp['company_name'] ?? 'Verified Partner Company')
                        ->sameAs($jp['company_url'] ?? $siteUrl)
                        ->logo($jp['company_logo'] ?? $this->ogImage)
                )
                ->jobLocation(
                    Schema::place()
                        ->address(
                            Schema::postalAddress()
                                ->addressLocality($jp['location'] ?? 'Lagos')
                                ->addressRegion('Lagos')
                                ->addressCountry('NG')
                        )
                );

            if (!empty($jp['salary'])) {
                $jobSchema->baseSalary(
                    Schema::monetaryAmount()
                        ->currency('NGN')
                        ->value(Schema::quantitativeValue()->value((float)$jp['salary'])->unitText('MONTH'))
                );
            }

            $schemas[] = $jobSchema;
        }

        // 5. FAQPage Schema (if configured)
        if (!empty($this->faqItems)) {
            $questions = [];
            foreach ($this->faqItems as $q => $a) {
                $questions[] = Schema::question()
                    ->name($q)
                    ->acceptedAnswer(
                        Schema::answer()->text($a)
                    );
            }
            $faqSchema = Schema::fAQPage()->mainEntity($questions);
            $schemas[] = $faqSchema;
        }

        // Custom schemas
        foreach ($this->customSchemas as $custom) {
            $schemas[] = $custom;
        }

        return $schemas;
    }

    /**
     * Render complete HTML head tags (Meta + OpenGraph + Twitter + Geo + JSON-LD)
     */
    public function render(): string
    {
        $eTitle       = htmlspecialchars($this->title, ENT_QUOTES, 'UTF-8');
        $eDescription = htmlspecialchars($this->description, ENT_QUOTES, 'UTF-8');
        $eKeywords    = htmlspecialchars(implode(', ', $this->keywords), ENT_QUOTES, 'UTF-8');
        $eCanonical   = htmlspecialchars($this->canonicalUrl, ENT_QUOTES, 'UTF-8');
        $eOgImage     = htmlspecialchars($this->ogImage, ENT_QUOTES, 'UTF-8');
        $eSiteName    = htmlspecialchars($this->siteName, ENT_QUOTES, 'UTF-8');
        $eRobots      = htmlspecialchars($this->robots, ENT_QUOTES, 'UTF-8');
        $eLocale      = htmlspecialchars($this->locale, ENT_QUOTES, 'UTF-8');
        $eTwitter     = htmlspecialchars($this->twitterHandle, ENT_QUOTES, 'UTF-8');

        $html = [];
        $html[] = "    <title>{$eTitle}</title>";
        $html[] = "    <meta name=\"description\" content=\"{$eDescription}\">";
        if (!empty($eKeywords)) {
            $html[] = "    <meta name=\"keywords\" content=\"{$eKeywords}\">";
        }
        $html[] = "    <meta name=\"robots\" content=\"{$eRobots}\">";
        $html[] = "    <link rel=\"canonical\" href=\"{$eCanonical}\">";

        // OpenGraph
        $html[] = "    <!-- OpenGraph / Facebook -->";
        $html[] = "    <meta property=\"og:type\" content=\"{$this->ogType}\">";
        $html[] = "    <meta property=\"og:site_name\" content=\"{$eSiteName}\">";
        $html[] = "    <meta property=\"og:title\" content=\"{$eTitle}\">";
        $html[] = "    <meta property=\"og:description\" content=\"{$eDescription}\">";
        $html[] = "    <meta property=\"og:url\" content=\"{$eCanonical}\">";
        $html[] = "    <meta property=\"og:image\" content=\"{$eOgImage}\">";
        $html[] = "    <meta property=\"og:locale\" content=\"{$eLocale}\">";

        // Twitter Card
        $html[] = "    <!-- Twitter Cards -->";
        $html[] = "    <meta name=\"twitter:card\" content=\"summary_large_image\">";
        $html[] = "    <meta name=\"twitter:site\" content=\"{$eTwitter}\">";
        $html[] = "    <meta name=\"twitter:creator\" content=\"{$eTwitter}\">";
        $html[] = "    <meta name=\"twitter:title\" content=\"{$eTitle}\">";
        $html[] = "    <meta name=\"twitter:description\" content=\"{$eDescription}\">";
        $html[] = "    <meta name=\"twitter:image\" content=\"{$eOgImage}\">";

        // Localized Nigerian SEO Meta
        $html[] = "    <!-- Geographic & Regional Targeting (Nigeria) -->";
        $html[] = "    <meta name=\"geo.region\" content=\"" . htmlspecialchars($this->geoRegion, ENT_QUOTES, 'UTF-8') . "\">";
        $html[] = "    <meta name=\"geo.placename\" content=\"" . htmlspecialchars($this->geoPlacename, ENT_QUOTES, 'UTF-8') . "\">";
        $html[] = "    <meta name=\"geo.position\" content=\"" . htmlspecialchars($this->geoPosition, ENT_QUOTES, 'UTF-8') . "\">";
        $html[] = "    <meta name=\"ICBM\" content=\"" . htmlspecialchars($this->icbm, ENT_QUOTES, 'UTF-8') . "\">";

        // Search Engine Site Verification
        if (!empty($this->googleVerification)) {
            $html[] = "    <meta name=\"google-site-verification\" content=\"" . htmlspecialchars($this->googleVerification, ENT_QUOTES, 'UTF-8') . "\">";
        }
        if (!empty($this->bingVerification)) {
            $html[] = "    <meta name=\"msvalidate.01\" content=\"" . htmlspecialchars($this->bingVerification, ENT_QUOTES, 'UTF-8') . "\">";
        }

        // Schema.org JSON-LD
        $schemas = $this->buildStructuredData();
        if (!empty($schemas)) {
            $html[] = "    <!-- Schema.org JSON-LD Structured Data for Google -->";
            foreach ($schemas as $schema) {
                if (method_exists($schema, 'toScript')) {
                    $html[] = "    " . $schema->toScript();
                } elseif (method_exists($schema, 'toArray')) {
                    $json = json_encode($schema->toArray(), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
                    $html[] = "    <script type=\"application/ld+json\">\n{$json}\n    </script>";
                }
            }
        }

        return implode("\n", $html);
    }
}
