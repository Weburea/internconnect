<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\Database;
use PDO;

/**
 * Testimonials for the home page section and /testimonials, managed in Admin > Testimonials.
 * docs/61-testimonials.md
 *
 * kind: quote (social post style card) | video (story clip)
 * platform: linkedin, x, instagram, facebook, tiktok, youtube, google, website
 * show_home / show_page decide where it appears; sort orders it; is_active hides it everywhere.
 */
final class TestimonialService
{
    public const PLATFORMS = ['linkedin' => 'LinkedIn', 'x' => 'X (Twitter)', 'instagram' => 'Instagram', 'facebook' => 'Facebook', 'tiktok' => 'TikTok', 'youtube' => 'YouTube', 'google' => 'Google review', 'website' => 'InternConnect'];
    public const AUDIENCES = ['student' => 'Student or graduate', 'employer' => 'Employer'];
    private static bool $ready = false;

    private static function pdo(): PDO
    {
        return Database::getConnection();
    }

    public static function ensureTables(): void
    {
        if (self::$ready) {
            return;
        }
        $pdo = self::pdo();
        if ((string)$pdo->getAttribute(PDO::ATTR_DRIVER_NAME) === 'mysql') {
            $pdo->exec('CREATE TABLE IF NOT EXISTS testimonials (
                id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY, kind VARCHAR(10) NOT NULL DEFAULT \'quote\', name VARCHAR(120) NOT NULL,
                handle VARCHAR(120) NULL, role VARCHAR(160) NULL, quote TEXT NULL, platform VARCHAR(20) NOT NULL DEFAULT \'linkedin\',
                source_url VARCHAR(500) NULL, avatar_url VARCHAR(500) NULL, video_url VARCHAR(500) NULL, rating TINYINT UNSIGNED NULL,
                audience VARCHAR(16) NOT NULL DEFAULT \'student\', show_home TINYINT(1) NOT NULL DEFAULT 1, show_page TINYINT(1) NOT NULL DEFAULT 1,
                sort INT NOT NULL DEFAULT 0, is_active TINYINT(1) NOT NULL DEFAULT 1, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL,
                INDEX idx_testimonials_live (is_active, kind, sort)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');
        } else {
            $pdo->exec('CREATE TABLE IF NOT EXISTS testimonials (id INTEGER PRIMARY KEY AUTOINCREMENT, kind TEXT NOT NULL DEFAULT \'quote\', name TEXT NOT NULL,
                handle TEXT, role TEXT, quote TEXT, platform TEXT NOT NULL DEFAULT \'linkedin\', source_url TEXT, avatar_url TEXT, video_url TEXT, rating INTEGER,
                audience TEXT NOT NULL DEFAULT \'student\', show_home INTEGER NOT NULL DEFAULT 1, show_page INTEGER NOT NULL DEFAULT 1, sort INTEGER NOT NULL DEFAULT 0,
                is_active INTEGER NOT NULL DEFAULT 1, created_at TEXT NOT NULL, updated_at TEXT NOT NULL)');
        }
        self::$ready = true;
        if ((int)$pdo->query('SELECT COUNT(*) FROM testimonials')->fetchColumn() === 0) {
            self::seed($pdo);
        }
    }

    /** The testimonials that were hard-coded on the home page, plus a few more for /testimonials. */
    private static function seed(PDO $pdo): void
    {
        $u = static fn ($id) => 'https://images.unsplash.com/' . $id . '?w=150&auto=format&fit=crop&q=80';
        $quotes = [
            ['Daniella O.', '@Flux.tech', 'Hiring Manager, Flux.tech', 'linkedin', $u('photo-1573496359142-b8d87734a5a2'), "The quality of candidates on InternConnect is impressive. We're able to connect with motivated students who have the right skills, attitude, and willingness to learn", 'employer', 1],
            ['David M.', '@Google.com', 'Recruiter', 'instagram', $u('photo-1507003211169-0a1dd7228f2d'), 'Finding the right intern can take a lot of time, but InternConnect has made the process much more efficient for us. The candidates are ambitious, prepared, and eager to gain real-world experience.', 'employer', 1],
            ['Anita K.', '@AnitaK_design', 'Product Design Intern', 'linkedin', $u('photo-1524504388940-b1c1722653e1'), 'I really liked how simple the application process was. I could explore different opportunities, understand what employers were looking for, and apply without endless paperwork.', 'student', 1],
            ['Sarah A.', '@Prescot LTD', 'Talent Partner, Prescot Ltd', 'x', $u('photo-1534528741775-53994a69daeb'), 'The platform makes it easier to find students who are genuinely interested in growing with our team.', 'employer', 1],
            ['Michael T.', '@MichaelT_dev', 'Software Engineering Intern', 'x', $u('photo-1539571696357-5a69c17a67c6'), 'InternConnect made my internship search so much easier. I found opportunities that actually matched my skills and career goals, and I could track every application without feeling overwhelmed.', 'student', 1],
            ['Grace E.', '@GraceE_tech', 'Data Analyst Intern', 'facebook', $u('photo-1500648767791-00dcc994a43e'), 'Before InternConnect, I spent so much time jumping between different job boards. Now I can discover internships, apply quickly, and keep track of my progress in one place.', 'student', 1],
            ['Lucas R.', '@lucas.codes', 'Frontend Intern, remote', 'tiktok', $u('photo-1506794778202-cad84cf45f1d'), 'Jenny rewrote my cover letter in a minute and I got my first interview the same week. The mock interview practice made me far less nervous.', 'student', 0],
            ['Priya S.', '@priya.builds', 'Marketing Intern', 'google', $u('photo-1494790108377-be9c29b29330'), 'Clean, fast and honest. Every listing showed the pay and work mode up front, which saved me hours.', 'student', 0],
            ['Emeka N.', '@emeka_hr', 'Head of People', 'linkedin', $u('photo-1531427186611-ecfd6d936c79'), 'The AI summaries of each application let our small team review forty candidates in an afternoon. We hired two interns in our first month.', 'employer', 0],
            ['Adaeze Okafor', 'University of Lagos', 'Software Engineering Intern at Paystack', 'website', null, "I used to apply to dozens of roles with zero replies. InternConnect matched my GitHub projects with Paystack's requirements, and Jenny helped me practise API design questions. I signed my offer in less than 3 weeks.", 'student', 0],
            ['Tobi Adebayo', 'Covenant University', 'Product Design Intern at Flutterwave', 'website', null, "The stipend transparency is a game changer. I knew the exact pay upfront and didn't have to deal with endless unverified recruitment e-mails. The interview guide gave me the confidence to pitch my design system.", 'student', 0],
            ['Emeka Nwosu', 'University of Nigeria, Nsukka', 'Financial Analyst Intern at CardinalStone', 'website', null, 'Breaking into investment banking felt impossible. InternConnect verified my transcripts and scheduled my video interview straight into my calendar. The whole experience was seamless.', 'student', 0],
            ['Zainab Bello', 'Ahmadu Bello University', 'Frontend Developer Intern at Moniepoint', 'website', null, 'The screening questions let me show my React projects instead of just sending a generic PDF. The recruiter reached out within 48 hours for our technical discussion.', 'student', 0],
            ['Kelechi Eze', 'Obafemi Awolowo University', 'Associate Product Intern at Kuda', 'website', null, 'Jenny showed me how to turn my hackathon experience into measurable results. The platform makes student talent feel respected and valued.', 'student', 0],
            ['Blessing Danjuma', 'Federal University of Technology, Akure', 'Data Science Intern at Interswitch', 'website', null, 'Interview scheduling saved so much back-and-forth. I accepted my offer online and started onboarding within days.', 'student', 0],
        ];
        $now = date('Y-m-d H:i:s');
        $ins = $pdo->prepare('INSERT INTO testimonials (kind, name, handle, role, quote, platform, avatar_url, video_url, audience, rating, show_home, show_page, sort, is_active, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1, ?, 1, ?, ?)');
        foreach ($quotes as $i => [$name, $handle, $role, $platform, $avatar, $quote, $aud, $home]) {
            $ins->execute(['quote', $name, $handle, $role, $quote, $platform, $avatar, null, $aud, 5, $home, ($i + 1) * 10, $now, $now]);
        }
        $videos = [
            ['Anastasia K.', 'UI/UX Design Intern', 'https://res.cloudinary.com/weburea/video/upload/w_400,q_auto:eco,ac_none/v1790815553/test_4_l0uhcx.mp4', 'https://res.cloudinary.com/weburea/image/upload/v1790816969/test_4_adtliv.png', 'student'],
            ['Chioma B.', 'Talent Lead at TechStack', 'https://res.cloudinary.com/weburea/video/upload/w_400,q_auto:eco,ac_none/v1790815546/test_2_uypp2k.mp4', 'https://res.cloudinary.com/weburea/image/upload/v1790816968/test_2_hi8rmz.png', 'employer'],
            ['Kendra L.', 'Head of Talent at Apex Energy', 'https://res.cloudinary.com/weburea/video/upload/w_400,q_auto:eco,ac_none/v1790819428/test_6_erfvcc.mp4', 'https://res.cloudinary.com/weburea/image/upload/v1790819376/test_6_iwxj2z.png', 'employer'],
            ['Sipho D.', 'Software Engineering Intern', 'https://res.cloudinary.com/weburea/video/upload/w_400,q_auto:eco,ac_none/v1790815544/test_3_radsqu.mp4', 'https://res.cloudinary.com/weburea/image/upload/v1790816968/test_3_kakx5q.png', 'student'],
        ];
        foreach ($videos as $i => [$name, $role, $video, $avatar, $aud]) {
            $ins->execute(['video', $name, null, $role, null, 'instagram', $avatar, $video, $aud, null, 1, ($i + 1) * 10, $now, $now]);
        }
    }

    /** Live testimonials. $where: 'home' | 'page' | null (all, admin). */
    public static function list(?string $where = null, ?string $kind = null): array
    {
        self::ensureTables();
        $sql = 'SELECT * FROM testimonials WHERE 1 = 1';
        $args = [];
        if ($where !== null) {
            $sql .= ' AND is_active = 1 AND ' . ($where === 'home' ? 'show_home = 1' : 'show_page = 1');
        }
        if ($kind !== null) {
            $sql .= ' AND kind = ?';
            $args[] = $kind;
        }
        $stmt = self::pdo()->prepare($sql . ' ORDER BY sort, id');
        $stmt->execute($args);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function find(int $id): ?array
    {
        self::ensureTables();
        $stmt = self::pdo()->prepare('SELECT * FROM testimonials WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    /** Validate and save. Returns ['ok', 'id'|'error']. */
    public static function save(array $in, ?int $id = null): array
    {
        self::ensureTables();
        $clean = static fn ($v, int $max) => mb_substr(trim(strip_tags((string)$v)), 0, $max);
        $https = static fn ($v) => preg_match('#^https://[^\s"<>]+$#i', trim((string)$v)) ? trim((string)$v) : '';
        $kind = ($in['kind'] ?? 'quote') === 'video' ? 'video' : 'quote';
        $data = [
            'kind'       => $kind,
            'name'       => $clean($in['name'] ?? '', 120),
            'handle'     => $clean($in['handle'] ?? '', 120) ?: null,
            'role'       => $clean($in['role'] ?? '', 160) ?: null,
            'quote'      => mb_substr(trim(strip_tags((string)($in['quote'] ?? ''))), 0, 1200) ?: null,
            'platform'   => array_key_exists((string)($in['platform'] ?? ''), self::PLATFORMS) ? (string)$in['platform'] : 'website',
            'source_url' => $https($in['source_url'] ?? '') ?: null,
            'avatar_url' => $https($in['avatar_url'] ?? '') ?: null,
            'video_url'  => $https($in['video_url'] ?? '') ?: null,
            'rating'     => ($r = (int)($in['rating'] ?? 0)) >= 1 && $r <= 5 ? $r : null,
            'audience'   => array_key_exists((string)($in['audience'] ?? ''), self::AUDIENCES) ? (string)$in['audience'] : 'student',
            'show_home'  => !empty($in['show_home']) ? 1 : 0,
            'show_page'  => !empty($in['show_page']) ? 1 : 0,
            'sort'       => max(0, min(100000, (int)($in['sort'] ?? 0))),
            'is_active'  => !empty($in['is_active']) ? 1 : 0,
        ];
        if ($data['name'] === '') {
            return ['ok' => false, 'error' => 'Add the person\'s name.'];
        }
        if ($kind === 'quote' && mb_strlen((string)$data['quote']) < 10) {
            return ['ok' => false, 'error' => 'Add the testimonial text (at least 10 characters).'];
        }
        if ($kind === 'video' && !$data['video_url']) {
            return ['ok' => false, 'error' => 'Upload the video or paste its https:// link.'];
        }
        foreach (['source_url', 'avatar_url', 'video_url'] as $f) {
            if (trim((string)($in[$f] ?? '')) !== '' && !$data[$f]) {
                return ['ok' => false, 'error' => 'Links must start with https://'];
            }
        }
        $now = date('Y-m-d H:i:s');
        $pdo = self::pdo();
        if ($id) {
            $sets = implode(', ', array_map(static fn ($k) => $k . ' = ?', array_keys($data)));
            $pdo->prepare('UPDATE testimonials SET ' . $sets . ', updated_at = ? WHERE id = ?')->execute([...array_values($data), $now, $id]);
            return ['ok' => true, 'id' => $id];
        }
        $cols = implode(', ', array_keys($data));
        $marks = implode(', ', array_fill(0, count($data), '?'));
        $pdo->prepare('INSERT INTO testimonials (' . $cols . ', created_at, updated_at) VALUES (' . $marks . ', ?, ?)')->execute([...array_values($data), $now, $now]);
        return ['ok' => true, 'id' => (int)$pdo->lastInsertId()];
    }

    /** Quick toggles from the list (home / page / active). */
    public static function toggle(int $id, string $field): bool
    {
        if (!in_array($field, ['show_home', 'show_page', 'is_active'], true)) {
            return false;
        }
        self::ensureTables();
        $stmt = self::pdo()->prepare('UPDATE testimonials SET ' . $field . ' = 1 - ' . $field . ', updated_at = ? WHERE id = ?');
        $stmt->execute([date('Y-m-d H:i:s'), $id]);
        return $stmt->rowCount() > 0;
    }

    public static function delete(int $id): bool
    {
        self::ensureTables();
        $stmt = self::pdo()->prepare('DELETE FROM testimonials WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->rowCount() > 0;
    }

    /** SVG paths for the platform badges (shared by the home section, the page and admin). */
    public static function badge(string $platform, int $size = 16): string
    {
        $p = [
            'linkedin'  => '<path fill="currentColor" d="M19 3a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h14m-.5 15.5v-5.3a3.26 3.26 0 0 0-3.26-3.26c-.85 0-1.84.52-2.28 1.3v-1.11h-2.79v8.37h2.79v-4.93c0-.77.62-1.4 1.39-1.4a1.4 1.4 0 0 1 1.4 1.4v4.93h2.75M6.88 8.56a1.68 1.68 0 0 0 1.68-1.68c0-.93-.75-1.69-1.68-1.69a1.69 1.69 0 0 0-1.69 1.69c0 .93.76 1.68 1.69 1.68m1.39 9.94v-8.37H5.5v8.37h2.77z"/>',
            'x'         => '<path fill="currentColor" d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>',
            'instagram' => '<rect width="20" height="20" x="2" y="2" rx="5" ry="5" fill="none" stroke="currentColor" stroke-width="2"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z" fill="none" stroke="currentColor" stroke-width="2"/><line x1="17.5" x2="17.51" y1="6.5" y2="6.5" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/>',
            'facebook'  => '<path fill="currentColor" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z"/>',
            'tiktok'    => '<path fill="currentColor" d="M16.6 5.82a4.27 4.27 0 0 1-1.04-2.82h-3.1v12.4a2.6 2.6 0 1 1-2.6-2.6c.27 0 .53.04.78.12V9.75a5.73 5.73 0 1 0 4.95 5.67V9.18a7.3 7.3 0 0 0 4.27 1.37V7.47a4.27 4.27 0 0 1-3.26-1.65z"/>',
            'youtube'   => '<path fill="currentColor" d="M23 7.2a3 3 0 0 0-2.1-2.1C19 4.6 12 4.6 12 4.6s-7 0-8.9.5A3 3 0 0 0 1 7.2 31 31 0 0 0 .5 12a31 31 0 0 0 .5 4.8 3 3 0 0 0 2.1 2.1c1.9.5 8.9.5 8.9.5s7 0 8.9-.5a3 3 0 0 0 2.1-2.1 31 31 0 0 0 .5-4.8 31 31 0 0 0-.5-4.8zM9.75 15.02V8.98L15.5 12z"/>',
            'google'    => '<path fill="currentColor" d="M21.35 11.1H12v2.98h5.35c-.23 1.4-1.66 4.1-5.35 4.1-3.22 0-5.85-2.67-5.85-5.95S8.78 6.28 12 6.28c1.83 0 3.06.78 3.76 1.45l2.57-2.47C16.7 3.75 14.56 2.8 12 2.8 6.92 2.8 2.8 6.92 2.8 12s4.12 9.2 9.2 9.2c5.31 0 8.83-3.73 8.83-8.99 0-.6-.07-1.06-.15-1.51z"/>',
            'website'   => '<path fill="currentColor" d="M12 2a10 10 0 1 0 0 20 10 10 0 0 0 0-20zm6.9 6h-2.95a15.7 15.7 0 0 0-1.38-3.56A8.03 8.03 0 0 1 18.9 8zM12 4.04A14 14 0 0 1 13.9 8h-3.8A14 14 0 0 1 12 4.04zM4.26 14a8.2 8.2 0 0 1 0-4h3.38a16.5 16.5 0 0 0 0 4zm.84 2h2.95a15.7 15.7 0 0 0 1.38 3.56A8.03 8.03 0 0 1 5.1 16zM8.05 8H5.1a8.03 8.03 0 0 1 4.33-3.56A15.7 15.7 0 0 0 8.05 8zM12 19.96A14 14 0 0 1 10.1 16h3.8A14 14 0 0 1 12 19.96zM14.34 14H9.66a14.7 14.7 0 0 1 0-4h4.68a14.7 14.7 0 0 1 0 4zm.24 5.56A15.7 15.7 0 0 0 15.95 16h2.95a8.03 8.03 0 0 1-4.32 3.56zM16.36 14a16.5 16.5 0 0 0 0-4h3.38a8.2 8.2 0 0 1 0 4z"/>',
        ][$platform] ?? '';
        return '<svg width="' . $size . '" height="' . $size . '" viewBox="0 0 24 24" aria-hidden="true">' . $p . '</svg>';
    }
}
