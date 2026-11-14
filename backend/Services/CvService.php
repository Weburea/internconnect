<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\Database;
use PDO;
use Throwable;

/**
 * CV reading and the candidate's parsed CV.
 *
 * parse(): Gemini reads the file itself (PDF inline, DOCX as extracted text) and returns
 * contact details, the latest job, work experience, education, skills and a short summary.
 * Without Gemini (or if it fails) a rule-based reader still finds email, phone, name and links.
 *
 * The parsed CV is kept per candidate in `candidate_cv_profiles` (created on first use, like the
 * interview tables) and mirrored in the session, so the application form, profile and the
 * employer's candidate page all read the same data.
 * Spec: docs/49-cv-autofill-and-applications.md
 */
class CvService
{
    private static bool $tablesChecked = false;

    /** Demo platform: the signed-in candidate is candidate #1 until accounts move to MySQL. */
    public static function currentCandidateId(): int
    {
        return (int)($_SESSION['candidate_id'] ?? 1);
    }

    private static function pdo(): PDO
    {
        return Database::getConnection();
    }

    public static function ensureTables(): void
    {
        if (self::$tablesChecked) {
            return;
        }
        try {
            $pdo = self::pdo();
            if ((string)$pdo->getAttribute(PDO::ATTR_DRIVER_NAME) === 'sqlite') {
                $pdo->exec('CREATE TABLE IF NOT EXISTS candidate_cv_profiles (
                    candidate_id INTEGER PRIMARY KEY, file_meta TEXT, parsed_json TEXT NOT NULL,
                    source TEXT NOT NULL DEFAULT \'rules\', updated_at TEXT NOT NULL)');
            } else {
                $pdo->exec('CREATE TABLE IF NOT EXISTS candidate_cv_profiles (
                    candidate_id BIGINT UNSIGNED PRIMARY KEY, file_meta JSON NULL, parsed_json JSON NOT NULL,
                    source VARCHAR(16) NOT NULL DEFAULT \'rules\', updated_at DATETIME NOT NULL
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');
            }
            self::$tablesChecked = true;
        } catch (Throwable $e) {
            error_log('CvService tables: ' . $e->getMessage());
        }
    }

    /**
     * Read a stored CV (UploadStorage meta) and return the structured result.
     *
     * @return array{name:string,email:string,phone:string,location:string,headline:string,job_title:string,company:string,
     *               summary:string,experience:array,education:array,skills:array,links:array,source:string}
     */
    public static function parse(?array $meta): array
    {
        $path = UploadStorage::path($meta);
        $empty = self::blank();
        if ($path === null) {
            return $empty;
        }
        $ext = strtolower((string)($meta['ext'] ?? pathinfo($path, PATHINFO_EXTENSION)));
        $text = AiService::extractCvText($path, $ext);

        $ai = null;
        if (GeminiClient::isConfigured()) {
            $prompt = "You read CVs for InternConnect, a Nigerian internship platform. Extract the candidate's details from the CV"
                . ($ext === 'pdf' ? ' attached as a PDF.' : " below.\n\nCV TEXT:\n" . mb_substr($text, 0, 12000))
                . "\n\nReturn JSON only, using empty strings or empty arrays when something is not in the CV (never invent details):\n"
                . '{"name":"","email":"","phone":"","location":"","headline":"short professional headline",'
                . '"job_title":"most recent job or internship title","company":"company for that job",'
                . '"summary":"2-3 sentence professional summary in the third person from the CV, using the name or they (never assume gender)",'
                . '"experience":[{"title":"","company":"","location":"","start":"e.g. Jan 2024","end":"e.g. Jun 2024 or Present","current":false,"highlights":["achievement"]}],'
                . '"education":[{"school":"","degree":"","field":"","start":"","end":""}],'
                . '"skills":["skill"],"links":{"linkedin":"","github":"","portfolio":""}}'
                . "\nList experience newest first, at most 8 roles and 4 highlights each.";
            $files = $ext === 'pdf' ? [['path' => $path, 'mime' => 'application/pdf']] : [];
            if ($files || trim($text) !== '') {
                $ai = GeminiClient::json($prompt, 25, $files, 4096);
            }
        }

        $result = $ai ? self::clean($ai) : self::rules($text);
        $result['source'] = $ai ? 'gemini' : 'rules';
        // Rules fill anything the AI left empty (for example a phone number in a footer).
        if ($ai) {
            foreach (self::rules($text) as $k => $v) {
                if (is_string($v) && $v !== '' && ($result[$k] ?? '') === '') {
                    $result[$k] = $v;
                }
            }
        }
        return $result;
    }

    /**
     * Read a CV the moment it is chosen (signup or application), before the form is submitted.
     * The parse is kept in the session under $sessionKey so the submit step can reuse it.
     *
     * @return array{ok: bool, error?: string, parsed?: array}
     */
    public static function parseUpload(?array $upload, string $sessionKey): array
    {
        $stored = UploadStorage::store($upload, 'resume');
        if (!$stored['ok']) {
            return ['ok' => false, 'error' => $stored['error']];
        }
        $parsed = self::parse($stored['meta']);
        UploadStorage::delete($_SESSION[$sessionKey]['meta'] ?? null);
        $_SESSION[$sessionKey] = ['file_name' => $stored['meta']['name'], 'meta' => $stored['meta'], 'parsed' => $parsed];
        return ['ok' => true, 'parsed' => $parsed];
    }

    /** The parse made by parseUpload() for this file name (temporary copy removed), or null. */
    public static function takeSessionParse(string $sessionKey, string $fileName): ?array
    {
        $entry = $_SESSION[$sessionKey] ?? null;
        unset($_SESSION[$sessionKey]);
        if (!$entry) {
            return null;
        }
        UploadStorage::delete($entry['meta'] ?? null);
        $clean = preg_replace('/[^\w\s.\-()]+/u', '', $fileName) ?: $fileName;
        return ($entry['file_name'] ?? '') === mb_substr($clean, 0, 120) ? ($entry['parsed'] ?? null) : null;
    }

    /** Fields returned to the browser after a live parse. */
    public static function fieldsForForm(array $parsed): array
    {
        return [
            'full_name' => $parsed['name'] ?? '',
            'email'     => $parsed['email'] ?? '',
            'phone'     => $parsed['phone'] ?? '',
            'job_title' => $parsed['job_title'] ?? '',
            'company'   => $parsed['company'] ?? '',
            'skills'    => array_slice($parsed['skills'] ?? [], 0, 8),
        ];
    }

    /** Parse and remember a candidate's CV (signup, profile upload or application). */
    public static function saveForCandidate(int $candidateId, ?array $meta, ?array $parsed = null): array
    {
        $parsed = $parsed ?? self::parse($meta);
        $_SESSION['profile_cv'] = $parsed;
        self::ensureTables();
        try {
            $pdo = self::pdo();
            $sql = (string)$pdo->getAttribute(PDO::ATTR_DRIVER_NAME) === 'mysql'
                ? 'INSERT INTO candidate_cv_profiles (candidate_id, file_meta, parsed_json, source, updated_at) VALUES (?, ?, ?, ?, ?)
                   ON DUPLICATE KEY UPDATE file_meta = VALUES(file_meta), parsed_json = VALUES(parsed_json), source = VALUES(source), updated_at = VALUES(updated_at)'
                : 'INSERT INTO candidate_cv_profiles (candidate_id, file_meta, parsed_json, source, updated_at) VALUES (?, ?, ?, ?, ?)
                   ON CONFLICT(candidate_id) DO UPDATE SET file_meta = excluded.file_meta, parsed_json = excluded.parsed_json, source = excluded.source, updated_at = excluded.updated_at';
            $pdo->prepare($sql)->execute([$candidateId, json_encode($meta), json_encode($parsed), $parsed['source'] ?? 'rules', date('Y-m-d H:i:s')]);
        } catch (Throwable $e) {
            error_log('CvService save: ' . $e->getMessage());
        }
        return $parsed;
    }

    /** @return array{parsed: array, file: ?array, updated_at: string}|null */
    public static function forCandidate(int $candidateId): ?array
    {
        self::ensureTables();
        try {
            $stmt = self::pdo()->prepare('SELECT file_meta, parsed_json, updated_at FROM candidate_cv_profiles WHERE candidate_id = ?');
            $stmt->execute([$candidateId]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Throwable $e) {
            return null;
        }
        if (!$row) {
            return null;
        }
        $parsed = json_decode((string)$row['parsed_json'], true);
        return [
            'parsed'     => is_array($parsed) ? $parsed + self::blank() : self::blank(),
            'file'       => json_decode((string)$row['file_meta'], true) ?: null,
            'updated_at' => (string)$row['updated_at'],
        ];
    }

    /** Fields used to prefill forms: account details first, then the parsed CV. */
    public static function prefill(): array
    {
        $cv = $_SESSION['profile_cv'] ?? (self::forCandidate(self::currentCandidateId())['parsed'] ?? []);
        $pick = static fn (string ...$values) => array_values(array_filter($values, static fn ($v) => trim((string)$v) !== ''))[0] ?? '';
        return [
            'full_name' => $pick((string)($_SESSION['profile_name'] ?? ''), (string)($cv['name'] ?? '')),
            'email'     => $pick((string)($_SESSION['profile_email'] ?? ''), (string)($cv['email'] ?? '')),
            'phone'     => $pick((string)($_SESSION['profile_phone'] ?? ''), (string)($cv['phone'] ?? '')),
            'job_title' => (string)($cv['job_title'] ?? ''),
            'company'   => (string)($cv['company'] ?? ''),
            'from_cv'   => !empty($cv) && (($cv['job_title'] ?? '') !== '' || ($cv['email'] ?? '') !== '' || ($cv['name'] ?? '') !== ''),
        ];
    }

    public static function blank(): array
    {
        return ['name' => '', 'email' => '', 'phone' => '', 'location' => '', 'headline' => '', 'job_title' => '', 'company' => '',
            'summary' => '', 'experience' => [], 'education' => [], 'skills' => [], 'links' => ['linkedin' => '', 'github' => '', 'portfolio' => ''], 'source' => 'none'];
    }

    /** Normalise and bound AI output so the UI never receives unexpected shapes. */
    private static function clean(array $ai): array
    {
        $str = static fn ($v, int $max = 160) => mb_substr(trim(strip_tags(is_scalar($v) ? (string)$v : '')), 0, $max);
        $out = self::blank();
        foreach (['name' => 80, 'email' => 120, 'phone' => 40, 'location' => 120, 'headline' => 160, 'job_title' => 120, 'company' => 120] as $k => $max) {
            $out[$k] = $str($ai[$k] ?? '', $max);
        }
        if ($out['email'] !== '' && !filter_var($out['email'], FILTER_VALIDATE_EMAIL)) {
            $out['email'] = '';
        }
        $out['summary'] = $str($ai['summary'] ?? '', 900);
        foreach (array_slice(is_array($ai['experience'] ?? null) ? $ai['experience'] : [], 0, 8) as $e) {
            if (!is_array($e) || ($str($e['title'] ?? '') === '' && $str($e['company'] ?? '') === '')) {
                continue;
            }
            $out['experience'][] = [
                'title'      => $str($e['title'] ?? ''),
                'company'    => $str($e['company'] ?? ''),
                'location'   => $str($e['location'] ?? ''),
                'start'      => $str($e['start'] ?? '', 30),
                'end'        => $str($e['end'] ?? '', 30),
                'current'    => !empty($e['current']),
                'highlights' => array_values(array_filter(array_map(static fn ($h) => $str($h, 300), array_slice(is_array($e['highlights'] ?? null) ? $e['highlights'] : [], 0, 4)))),
            ];
        }
        foreach (array_slice(is_array($ai['education'] ?? null) ? $ai['education'] : [], 0, 4) as $e) {
            if (is_array($e) && $str($e['school'] ?? '') !== '') {
                $out['education'][] = ['school' => $str($e['school']), 'degree' => $str($e['degree'] ?? ''), 'field' => $str($e['field'] ?? ''), 'start' => $str($e['start'] ?? '', 30), 'end' => $str($e['end'] ?? '', 30)];
            }
        }
        $out['skills'] = array_values(array_unique(array_filter(array_map(static fn ($s) => $str($s, 40), array_slice(is_array($ai['skills'] ?? null) ? $ai['skills'] : [], 0, 20)))));
        foreach (['linkedin', 'github', 'portfolio'] as $k) {
            $out['links'][$k] = $str($ai['links'][$k] ?? '', 200);
        }
        if ($out['job_title'] === '' && $out['experience']) {
            $out['job_title'] = $out['experience'][0]['title'];
            $out['company'] = $out['company'] ?: $out['experience'][0]['company'];
        }
        return $out;
    }

    /** Rule-based reader: contact details and links only (experience needs the AI). */
    public static function rules(string $text): array
    {
        $out = self::blank();
        unset($out['source']);
        $text = trim($text);
        if ($text === '') {
            return $out;
        }
        if (preg_match('/[A-Z0-9._%+\-]+@[A-Z0-9.\-]+\.[A-Z]{2,}/i', $text, $m)) {
            $out['email'] = $m[0];
        }
        // Phone: 10-15 digits (so date ranges such as "2019 - 2023" are not mistaken for one).
        if (preg_match_all('/\+?\d[\d\s\-()]{8,18}\d/', $text, $all)) {
            foreach ($all[0] as $candidate) {
                $digits = strlen(preg_replace('/\D/', '', $candidate) ?? '');
                if ($digits >= 10 && $digits <= 15) {
                    $out['phone'] = trim(preg_replace('/\s+/', ' ', $candidate) ?? $candidate);
                    break;
                }
            }
        }
        if (preg_match('/linkedin\.com\/in\/[\w\-]+/i', $text, $m)) {
            $out['links']['linkedin'] = $m[0];
        }
        if (preg_match('/github\.com\/[\w\-]+/i', $text, $m)) {
            $out['links']['github'] = $m[0];
        }
        // Name: the first two or three capitalised words at the start of the CV.
        $head = preg_replace('/^\s*(curriculum vitae|résumé|resume|cv)\b[\s:\-]*/iu', '', $text) ?? $text;
        if (preg_match('/^\s*([A-Z][a-zA-Z\'\-]+(?:\s+[A-Z][a-zA-Z\'\-]+){1,2})\b/', $head, $m)) {
            // Stop before a headline word ("Amaka Johnson Product Designer" -> "Amaka Johnson").
            $words = preg_split('/\s+/', $m[1]) ?: [];
            $titleWords = ['product', 'software', 'senior', 'junior', 'lead', 'frontend', 'backend', 'fullstack', 'data', 'graphic', 'ui', 'ux',
                'designer', 'developer', 'engineer', 'intern', 'student', 'analyst', 'manager', 'marketing', 'business', 'mobile', 'web'];
            while (count($words) > 2 && in_array(strtolower((string)end($words)), $titleWords, true)) {
                array_pop($words);
            }
            $out['name'] = implode(' ', $words);
        }
        return $out;
    }
}
