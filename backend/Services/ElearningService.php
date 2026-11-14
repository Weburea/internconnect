<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\Database;
use PDO;
use Throwable;

/**
 * E-Learning partner directory (/elearning) and its admin management (/admin/elearning).
 *
 * Model: InternConnect sends students to partner platforms (ALX, Coursera, DataCamp ...) to upskill;
 * the partners send job-ready candidates back. Each partner has a logo, an about text, hub locations
 * and the services it offers; each program is a card on /elearning with its InternConnect perk.
 * Every "Visit Platform & Enroll" click goes through /elearning/go/{programId} and is recorded as a
 * referral so the admin can see which partners InternConnect sends students to.
 *
 * Tables (created on first use, seeded with the launch partners): elearning_partners,
 * elearning_programs, elearning_referrals. MySQL DDL: database/schema_mysql.sql section 14.
 * Spec: docs/50-elearning-partners.md
 */
class ElearningService
{
    private static bool $ready = false;

    public const CATEGORIES = [
        'tech'     => 'Software & Cloud',
        'design'   => 'Product & Design',
        'data'     => 'Data Science & AI',
        'business' => 'Business & Finance',
        'free'     => 'Free Certifications',
    ];

    private static function pdo(): PDO
    {
        return Database::getConnection();
    }

    private static function isMysql(PDO $pdo): bool
    {
        return (string)$pdo->getAttribute(PDO::ATTR_DRIVER_NAME) === 'mysql';
    }

    public static function ensureTables(): void
    {
        if (self::$ready) {
            return;
        }
        try {
            $pdo = self::pdo();
            if (self::isMysql($pdo)) {
                $pdo->exec('CREATE TABLE IF NOT EXISTS elearning_partners (
                    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY, slug VARCHAR(80) NOT NULL UNIQUE, name VARCHAR(120) NOT NULL,
                    tier VARCHAR(60) NOT NULL, website VARCHAR(255) NOT NULL, logo VARCHAR(255) NULL, brand_color CHAR(7) NOT NULL DEFAULT \'#4F48E6\',
                    about TEXT NOT NULL, hubs VARCHAR(255) NOT NULL, services_json JSON NOT NULL,
                    is_active TINYINT(1) NOT NULL DEFAULT 1, sort_order INT NOT NULL DEFAULT 0, updated_at DATETIME NOT NULL
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');
                $pdo->exec('CREATE TABLE IF NOT EXISTS elearning_programs (
                    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY, partner_id BIGINT UNSIGNED NOT NULL, title VARCHAR(200) NOT NULL,
                    category VARCHAR(20) NOT NULL, duration VARCHAR(80) NOT NULL, delivery VARCHAR(120) NOT NULL, level VARCHAR(60) NOT NULL,
                    description TEXT NOT NULL, highlights_json JSON NOT NULL, outcomes VARCHAR(255) NOT NULL,
                    perk VARCHAR(160) NOT NULL, perk_code VARCHAR(40) NOT NULL, external_url VARCHAR(255) NOT NULL,
                    is_active TINYINT(1) NOT NULL DEFAULT 1, sort_order INT NOT NULL DEFAULT 0, updated_at DATETIME NOT NULL,
                    INDEX idx_elp_partner (partner_id)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');
                $pdo->exec('CREATE TABLE IF NOT EXISTS elearning_referrals (
                    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY, program_id BIGINT UNSIGNED NOT NULL, partner_id BIGINT UNSIGNED NOT NULL,
                    candidate_id BIGINT UNSIGNED NULL, created_at DATETIME NOT NULL, INDEX idx_elr_partner (partner_id, created_at)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');
            } else {
                $pdo->exec('CREATE TABLE IF NOT EXISTS elearning_partners (
                    id INTEGER PRIMARY KEY AUTOINCREMENT, slug TEXT NOT NULL UNIQUE, name TEXT NOT NULL, tier TEXT NOT NULL,
                    website TEXT NOT NULL, logo TEXT, brand_color TEXT NOT NULL DEFAULT \'#4F48E6\', about TEXT NOT NULL, hubs TEXT NOT NULL,
                    services_json TEXT NOT NULL, is_active INTEGER NOT NULL DEFAULT 1, sort_order INTEGER NOT NULL DEFAULT 0, updated_at TEXT NOT NULL)');
                $pdo->exec('CREATE TABLE IF NOT EXISTS elearning_programs (
                    id INTEGER PRIMARY KEY AUTOINCREMENT, partner_id INTEGER NOT NULL, title TEXT NOT NULL, category TEXT NOT NULL,
                    duration TEXT NOT NULL, delivery TEXT NOT NULL, level TEXT NOT NULL, description TEXT NOT NULL, highlights_json TEXT NOT NULL,
                    outcomes TEXT NOT NULL, perk TEXT NOT NULL, perk_code TEXT NOT NULL, external_url TEXT NOT NULL,
                    is_active INTEGER NOT NULL DEFAULT 1, sort_order INTEGER NOT NULL DEFAULT 0, updated_at TEXT NOT NULL)');
                $pdo->exec('CREATE TABLE IF NOT EXISTS elearning_referrals (
                    id INTEGER PRIMARY KEY AUTOINCREMENT, program_id INTEGER NOT NULL, partner_id INTEGER NOT NULL,
                    candidate_id INTEGER, created_at TEXT NOT NULL)');
                $pdo->exec('CREATE INDEX IF NOT EXISTS idx_elr_partner ON elearning_referrals (partner_id, created_at)');
            }
            self::$ready = true;
            if ((int)$pdo->query('SELECT COUNT(*) FROM elearning_partners')->fetchColumn() === 0) {
                self::seed($pdo);
            }
        } catch (Throwable $e) {
            error_log('ElearningService tables: ' . $e->getMessage());
        }
    }

    /** Launch partners and programs (previously hard-coded on /elearning). */
    private static function seed(PDO $pdo): void
    {
        $now = date('Y-m-d H:i:s');
        $partners = [
            ['alx-africa', 'ALX Africa', 'Accredited Partner', 'https://www.alxafrica.com', 'assets/partners/alx-africa.png', '#0B0B2C',
                'ALX Africa is a leading tech training initiative transforming young African leaders through rigorous, market-aligned technical training programs.',
                'Lagos, Nairobi, Kigali, Accra, Johannesburg & Remote',
                [['Software Engineering', 'Full-stack and back-end engineering programme with peer learning.'], ['Data Analytics', 'Spreadsheets, SQL and dashboards for business decisions.'], ['Data Science', 'Python, statistics and machine-learning foundations.'], ['Cloud Computing (AWS)', 'AWS cloud practitioner and solutions foundations.'], ['AI Career Essentials', 'Use AI tools productively at work.'], ['Professional Foundations', 'Communication, teamwork and career readiness.'], ['ALX Hubs', 'Free co-working and community hubs in major African cities.']]],
            ['coursera', 'Coursera', 'Global Partner', 'https://www.coursera.org', 'assets/partners/coursera.png', '#0056D2',
                'Coursera partners with more than 300 leading universities and companies to bring flexible, affordable, job-relevant online learning.',
                'Global & Fully Remote',
                [['Professional Certificates', 'Job-ready certificates from Google, IBM, Meta and more.'], ['Specializations', 'Multi-course tracks on a single skill.'], ['Guided Projects', 'Short, hands-on projects in a browser workspace.'], ['Online Degrees', 'Bachelor and master degrees from partner universities.'], ['Financial Aid', 'Need-based aid and free audit on most courses.'], ['Coursera Plus', 'One subscription for 7,000+ courses.']]],
            ['datacamp', 'DataCamp', 'Data & AI Partner', 'https://www.datacamp.com', 'assets/partners/datacamp.png', '#03EF62',
                'DataCamp helps individuals and businesses acquire the in-demand data and AI skills needed to thrive in the modern economy.',
                'Global & Fully Remote',
                [['Career Tracks', 'Step-by-step paths to Data Analyst, Data Scientist and Data Engineer.'], ['Skill Tracks', 'Focused tracks on SQL, Python, R, Power BI and more.'], ['Certifications', 'Industry-recognised Data Analyst and Data Scientist certifications.'], ['Projects', 'Real datasets to build a portfolio.'], ['DataLab', 'AI-assisted notebook to analyse data in the browser.'], ['Live Trainings', 'Instructor-led code-alongs.']]],
            ['freecodecamp', 'freeCodeCamp', 'Free Certifications', 'https://www.freecodecamp.org', 'assets/partners/freecodecamp.png', '#0A0A23',
                'freeCodeCamp is a donor-supported non-profit community that has helped more than 40,000 graduates land jobs at tech companies.',
                'Global & Fully Remote',
                [['Responsive Web Design', 'HTML and CSS certification with projects.'], ['JavaScript Algorithms & Data Structures', 'Core JavaScript certification.'], ['Front End Libraries', 'React, Redux, Bootstrap and jQuery projects.'], ['Back End & APIs', 'Node.js, Express and MongoDB.'], ['Scientific Computing with Python', 'Python certification with 5 projects.'], ['Community Forum', 'Help from a global developer community.']]],
            ['google-career-certificates', 'Google Career Certificates', 'Global Tech Partner', 'https://grow.google/certificates', 'assets/partners/google-career-certificates.png', '#1A73E8',
                'Google Career Certificates provide pathways to jobs in high-growth fields, without requiring a 4-year degree.',
                'Global & Fully Remote',
                [['IT Support', 'Troubleshooting, networking and system administration.'], ['Cybersecurity', 'Security operations, Linux, SQL and Python.'], ['Data Analytics', 'Spreadsheets, SQL, Tableau and R.'], ['UX Design', 'Research, wireframes and Figma prototypes.'], ['Project Management', 'Agile and traditional project management.'], ['Digital Marketing & E-commerce', 'SEO, social media and online stores.'], ['Employer Consortium', 'Connect with employers that hire certificate graduates.']]],
            ['cfi', 'CFI Institute', 'Accredited Partner', 'https://corporatefinanceinstitute.com', 'assets/partners/cfi.png', '#0A1C3D',
                'Corporate Finance Institute (CFI) is a leading provider of online financial analyst certification programs and corporate training.',
                'Global & Fully Remote',
                [['FMVA Certification', 'Financial Modeling & Valuation Analyst.'], ['CBCA Certification', 'Commercial Banking & Credit Analyst.'], ['BIDA Certification', 'Business Intelligence & Data Analyst.'], ['CMSA Certification', 'Capital Markets & Securities Analyst.'], ['Excel Courses', 'Shortcuts, formulas and financial models.'], ['Templates Library', 'Ready-to-use financial models and templates.']]],
        ];
        $ins = $pdo->prepare('INSERT INTO elearning_partners (slug, name, tier, website, logo, brand_color, about, hubs, services_json, is_active, sort_order, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 1, ?, ?)');
        $ids = [];
        foreach ($partners as $i => $p) {
            $services = array_map(static fn ($s) => ['name' => $s[0], 'desc' => $s[1]], $p[8]);
            $ins->execute([$p[0], $p[1], $p[2], $p[3], $p[4], $p[5], $p[6], $p[7], json_encode($services), $i, $now]);
            $ids[$p[0]] = (int)$pdo->lastInsertId();
        }
        $programs = [
            ['alx-africa', 'ALX Full-Stack Software Engineering & Cloud', 'tech', '9 Months · Intensive Cohort', 'Hybrid Hubs & 100% Online', 'Beginner to Advanced',
                'World-class software engineering program covering low-level programming, data structures, full-stack web applications, and DevOps workflows.',
                ['C, Python, and modern JavaScript foundations', 'System design and database architectures', 'Peer-to-peer code reviews & agile team projects', 'Alumni network of 50,000+ African technologists'],
                'Full-Stack Developer, Backend Software Engineer, Cloud Practitioner', 'Direct Fast-Track to Employer Tech Shortlists', 'IC-ALX-FAST', 'https://www.alxafrica.com'],
            ['coursera', 'Google UX Design Professional Certificate', 'design', '3 - 6 Months · Self-Paced', '100% Online & Self-Paced', 'Beginner',
                'Master the complete UX design process, conduct usability studies, build wireframes and high-fidelity prototypes in Figma, and build 3 portfolio case studies.',
                ['User research, personas, and journey maps', 'Figma design system components and auto-layout', 'Cross-platform mobile and web responsiveness', 'Official Google Professional Certificate badge'],
                'Entry-level UX Designer, UI Designer, Product Design Intern', 'Free Financial Aid & 100% Audit Available', 'COURSERA-AID', 'https://www.coursera.org/professional-certificates/google-ux-design'],
            ['datacamp', 'Associate Data Analyst with Python & SQL', 'data', '2 - 4 Months · Interactive Labs', 'Hands-on Browser Labs & Online', 'Intermediate',
                'Interactive coding environment to query relational databases with SQL, clean data using pandas, and build data visualization stories for business teams.',
                ['PostgreSQL querying, joins, and aggregations', 'Exploratory data analysis with Python & pandas', 'Statistical hypothesis testing and dashboard reporting', 'Industry-recognized DataCamp certification'],
                'Junior Data Analyst, BI Intern, SQL Reporting Specialist', 'Exclusive: 20% Off Premium for InternConnect Students', 'ICDATA20', 'https://www.datacamp.com'],
            ['freecodecamp', 'Scientific Computing with Python & Algorithms', 'free', '300 Hours · Self-Paced', '100% Free & Open-Source', 'All Levels',
                'Acclaimed non-profit curriculum covering Python algorithms, data structures, and numerical computing through 5 verified portfolio projects.',
                ['Algorithm time and space complexity (Big O)', 'Object-oriented programming patterns in Python', 'Hands-on computational problem solving projects', 'Free verifiable certificate'],
                'Python Developer Intern, Algorithm Engineer, Software Apprentice', '100% Free Credential Recognized by Verified Recruiters', 'FREE-ALWAYS', 'https://www.freecodecamp.org/learn/scientific-computing-with-python/'],
            ['google-career-certificates', 'Google IT Support & Cybersecurity Specialization', 'tech', '3 - 6 Months · Self-Paced', '100% Online & Self-Paced', 'Beginner',
                'Hands-on practical training covering hardware diagnostics, networking protocols, Linux terminal administration, and enterprise cyber defense.',
                ['TCP/IP networking and DNS server configuration', 'Linux and Windows command line troubleshooting', 'System security encryption and incident response', 'Access to the Google employer consortium'],
                'IT Support Specialist, Systems Administrator, Cyber Defense Analyst', 'Fast-Tracks Applications for Corporate IT Roles', 'GOOGLE-CERT', 'https://grow.google/certificates'],
            ['cfi', 'Financial Modeling & Valuation Analyst (FMVA)', 'business', '4 - 6 Months · Self-Paced', '100% Online & Interactive Models', 'Intermediate to Advanced',
                'The global standard certification for corporate finance, teaching 3-statement financial modeling, DCF valuations, and Excel shortcuts for investment analysts.',
                ['Dynamic 3-statement forecasting in Excel', 'Discounted cash flow (DCF) models & sensitivity analysis', 'M&A and corporate pitch deck structuring', 'Global FMVA certification'],
                'Investment Banking Intern, Financial Analyst, Private Equity Trainee', 'Unlocks Verified Financial Analyst Badge on InternConnect', 'CFI-FMVA-IC', 'https://corporatefinanceinstitute.com'],
        ];
        $insP = $pdo->prepare('INSERT INTO elearning_programs (partner_id, title, category, duration, delivery, level, description, highlights_json, outcomes, perk, perk_code, external_url, is_active, sort_order, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1, ?, ?)');
        foreach ($programs as $i => $p) {
            $insP->execute([$ids[$p[0]], $p[1], $p[2], $p[3], $p[4], $p[5], $p[6], json_encode($p[7]), $p[8], $p[9], $p[10], $p[11], $i, $now]);
        }
    }

    public static function logoUrl(?string $logo, int $partnerId): string
    {
        $logo = (string)$logo;
        if ($logo === '') {
            return '';
        }
        // Bundled logos are public assets; admin uploads are streamed by partner id.
        return str_starts_with($logo, 'assets/') ? asset($logo) : url('/elearning/logo/' . $partnerId) . '?v=' . substr(md5($logo), 0, 8);
    }

    private static function decodePartner(array $r): array
    {
        $r['id'] = (int)$r['id'];
        $r['is_active'] = (bool)$r['is_active'];
        $r['services'] = json_decode((string)$r['services_json'], true) ?: [];
        $r['logo_url'] = self::logoUrl($r['logo'] ?? '', (int)$r['id']);
        $words = preg_split('/\s+/', trim((string)$r['name'])) ?: ['IC'];
        $r['initials'] = strtoupper(mb_substr($words[0], 0, 1) . (isset($words[1]) ? mb_substr($words[1], 0, 1) : mb_substr($words[0], 1, 1)));
        unset($r['services_json']);
        return $r;
    }

    /** @return array<int, array> partners keyed by id */
    public static function partners(bool $activeOnly = true): array
    {
        self::ensureTables();
        try {
            $rows = self::pdo()->query('SELECT * FROM elearning_partners' . ($activeOnly ? ' WHERE is_active = 1' : '') . ' ORDER BY sort_order, id')->fetchAll(PDO::FETCH_ASSOC);
        } catch (Throwable $e) {
            return [];
        }
        $out = [];
        foreach ($rows as $r) {
            $p = self::decodePartner($r);
            $out[$p['id']] = $p;
        }
        return $out;
    }

    /** Program cards for /elearning with their partner attached (active partners and programs only). */
    public static function catalog(): array
    {
        $partners = self::partners(true);
        try {
            $rows = self::pdo()->query('SELECT * FROM elearning_programs WHERE is_active = 1 ORDER BY sort_order, id')->fetchAll(PDO::FETCH_ASSOC);
        } catch (Throwable $e) {
            return [];
        }
        $out = [];
        foreach ($rows as $r) {
            $partner = $partners[(int)$r['partner_id']] ?? null;
            if (!$partner) {
                continue;
            }
            $out[] = [
                'id'             => (int)$r['id'],
                'platform'       => $partner['name'],
                'tier'           => $partner['tier'],
                'logo'           => $partner['logo_url'],
                'initials'       => $partner['initials'],
                'brand_color'    => $partner['brand_color'],
                'website'        => $partner['website'],
                'about_platform' => $partner['about'],
                'cities'         => $partner['hubs'],
                'services'       => $partner['services'],
                'title'          => $r['title'],
                'category'       => $r['category'],
                'category_label' => self::CATEGORIES[$r['category']] ?? ucfirst((string)$r['category']),
                'duration'       => $r['duration'],
                'delivery'       => $r['delivery'],
                'level'          => $r['level'],
                'desc'           => $r['description'],
                'highlights'     => json_decode((string)$r['highlights_json'], true) ?: [],
                'outcomes'       => $r['outcomes'],
                'perk'           => $r['perk'],
                'perk_code'      => $r['perk_code'],
                'go_url'         => url('/elearning/go/' . (int)$r['id']),
            ];
        }
        return $out;
    }

    /** Record a referral click and return the partner URL to send the visitor to (null = unknown program). */
    public static function referralTarget(int $programId, ?int $candidateId): ?string
    {
        self::ensureTables();
        try {
            $stmt = self::pdo()->prepare('SELECT p.id, p.partner_id, p.external_url FROM elearning_programs p JOIN elearning_partners pa ON pa.id = p.partner_id WHERE p.id = ? AND p.is_active = 1 AND pa.is_active = 1');
            $stmt->execute([$programId]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$row || !preg_match('#^https://#i', (string)$row['external_url'])) {
                return null;
            }
            self::pdo()->prepare('INSERT INTO elearning_referrals (program_id, partner_id, candidate_id, created_at) VALUES (?, ?, ?, ?)')
                ->execute([(int)$row['id'], (int)$row['partner_id'], $candidateId, date('Y-m-d H:i:s')]);
            return (string)$row['external_url'];
        } catch (Throwable $e) {
            return null;
        }
    }

    /** Admin view: every partner with program count and referrals (all time and last 30 days). */
    public static function adminPartners(): array
    {
        $partners = self::partners(false);
        try {
            $programs = self::pdo()->query('SELECT partner_id, COUNT(*) c FROM elearning_programs GROUP BY partner_id')->fetchAll(PDO::FETCH_KEY_PAIR);
            $refs = self::pdo()->query('SELECT partner_id, COUNT(*) c FROM elearning_referrals GROUP BY partner_id')->fetchAll(PDO::FETCH_KEY_PAIR);
            $stmt = self::pdo()->prepare('SELECT partner_id, COUNT(*) c FROM elearning_referrals WHERE created_at >= ? GROUP BY partner_id');
            $stmt->execute([date('Y-m-d H:i:s', strtotime('-30 days'))]);
            $recent = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
        } catch (Throwable $e) {
            $programs = $refs = $recent = [];
        }
        foreach ($partners as $id => $p) {
            $partners[$id]['programs'] = (int)($programs[$id] ?? 0);
            $partners[$id]['referrals'] = (int)($refs[$id] ?? 0);
            $partners[$id]['referrals_30d'] = (int)($recent[$id] ?? 0);
        }
        return array_values($partners);
    }

    /** Admin edit: details, services, visibility and (optionally) a new uploaded logo. */
    public static function updatePartner(int $id, array $input, ?array $logoUpload = null): array
    {
        self::ensureTables();
        $clean = static fn ($v, int $max) => mb_substr(trim(strip_tags((string)$v)), 0, $max);
        $name = $clean($input['name'] ?? '', 120);
        $website = $clean($input['website'] ?? '', 255);
        if ($name === '' || !preg_match('#^https://[^\s]+$#i', $website)) {
            return ['ok' => false, 'error' => 'A name and an https:// website are required.'];
        }
        $services = [];
        foreach ((array)($input['services'] ?? []) as $s) {
            $sn = $clean(is_array($s) ? ($s['name'] ?? '') : $s, 80);
            if ($sn !== '') {
                $services[] = ['name' => $sn, 'desc' => $clean(is_array($s) ? ($s['desc'] ?? '') : '', 200)];
            }
        }
        $color = preg_match('/^#[0-9a-f]{6}$/i', (string)($input['brand_color'] ?? '')) ? (string)$input['brand_color'] : '#4F48E6';
        $logoSql = '';
        $args = [$name, $clean($input['tier'] ?? 'Partner', 60), $website, $color, $clean($input['about'] ?? '', 1200), $clean($input['hubs'] ?? '', 255),
            json_encode(array_slice($services, 0, 12)), !empty($input['is_active']) ? 1 : 0, date('Y-m-d H:i:s')];
        if ($logoUpload && ($logoUpload['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE) {
            $stored = UploadStorage::store($logoUpload, 'logo');
            if (!$stored['ok']) {
                return ['ok' => false, 'error' => $stored['error']];
            }
            $logoSql = ', logo = ?';
            $args[] = $stored['meta']['file'];
        }
        $args[] = $id;
        try {
            self::pdo()->prepare('UPDATE elearning_partners SET name = ?, tier = ?, website = ?, brand_color = ?, about = ?, hubs = ?, services_json = ?, is_active = ?, updated_at = ?' . $logoSql . ' WHERE id = ?')->execute($args);
        } catch (Throwable $e) {
            return ['ok' => false, 'error' => 'The partner could not be saved.'];
        }
        $partner = self::partners(false)[$id] ?? null;
        return $partner ? ['ok' => true, 'partner' => $partner] : ['ok' => false, 'error' => 'Partner not found.'];
    }

    /** Stream an admin-uploaded partner logo (stored as UploadStorage kind "logo"). */
    public static function streamLogo(int $partnerId): void
    {
        $file = (string)((self::partners(false)[$partnerId]['logo'] ?? ''));
        UploadStorage::stream(str_starts_with($file, 'assets/') ? null : ['kind' => 'logo', 'file' => $file, 'name' => $file, 'ext' => strtolower(pathinfo($file, PATHINFO_EXTENSION))]);
    }
}
