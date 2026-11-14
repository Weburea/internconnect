<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\Database;
use PDO;
use Throwable;

/**
 * Submitted internship applications (frontend phase store).
 *
 * Each submission keeps the contact details, the shared job, salary expectation, the cover letter
 * (typed text and/or file), employer question answers, links, the private resume file, and a short
 * AI profile summary written for the employer. Employers read it on /employer/candidates/{id}.
 *
 * Table `application_submissions` is created on first use (SQLite locally, MySQL in production),
 * the same way as the interview tables. The MySQL phase will merge it into `applications`.
 * Spec: docs/49-cv-autofill-and-applications.md
 */
class ApplicationService
{
    private static bool $tablesChecked = false;

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
                $pdo->exec('CREATE TABLE IF NOT EXISTS application_submissions (
                    id INTEGER PRIMARY KEY AUTOINCREMENT, candidate_id INTEGER NOT NULL,
                    internship_slug TEXT NOT NULL, internship_title TEXT NOT NULL, company_name TEXT NOT NULL,
                    full_name TEXT NOT NULL, email TEXT NOT NULL, phone TEXT NOT NULL,
                    job_title TEXT NOT NULL, job_company TEXT NOT NULL, salary_expectation TEXT,
                    cover_letter TEXT, cover_letter_file TEXT, answers_json TEXT, links_json TEXT,
                    resume_file TEXT, portfolio_file TEXT, ai_summary TEXT, ai_source TEXT,
                    status TEXT NOT NULL DEFAULT \'Submitted\', submitted_at TEXT NOT NULL)');
                $pdo->exec('CREATE INDEX IF NOT EXISTS idx_app_sub_candidate ON application_submissions (candidate_id, submitted_at)');
            } else {
                $pdo->exec('CREATE TABLE IF NOT EXISTS application_submissions (
                    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY, candidate_id BIGINT UNSIGNED NOT NULL,
                    internship_slug VARCHAR(160) NOT NULL, internship_title VARCHAR(200) NOT NULL, company_name VARCHAR(200) NOT NULL,
                    full_name VARCHAR(120) NOT NULL, email VARCHAR(190) NOT NULL, phone VARCHAR(40) NOT NULL,
                    job_title VARCHAR(160) NOT NULL, job_company VARCHAR(160) NOT NULL, salary_expectation VARCHAR(40) NULL,
                    cover_letter TEXT NULL, cover_letter_file JSON NULL, answers_json JSON NULL, links_json JSON NULL,
                    resume_file JSON NULL, portfolio_file JSON NULL, ai_summary TEXT NULL, ai_source VARCHAR(16) NULL,
                    status VARCHAR(32) NOT NULL DEFAULT \'Submitted\', submitted_at DATETIME NOT NULL,
                    INDEX idx_app_sub_candidate (candidate_id, submitted_at)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');
            }
            self::$tablesChecked = true;
        } catch (Throwable $e) {
            error_log('ApplicationService tables: ' . $e->getMessage());
        }
    }

    /** Save a submission and return its id (0 on failure). */
    public static function submit(int $candidateId, array $data): int
    {
        self::ensureTables();
        $cv = CvService::forCandidate($candidateId)['parsed'] ?? CvService::blank();
        [$summary, $source] = self::profileSummary($data, $cv);
        try {
            $stmt = self::pdo()->prepare('INSERT INTO application_submissions
                (candidate_id, internship_slug, internship_title, company_name, full_name, email, phone, job_title, job_company,
                 salary_expectation, cover_letter, cover_letter_file, answers_json, links_json, resume_file, portfolio_file,
                 ai_summary, ai_source, status, submitted_at)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
            $stmt->execute([
                $candidateId, $data['slug'], $data['internship_title'], $data['company_name'],
                $data['full_name'], $data['email'], $data['phone'], $data['job_title'], $data['job_company'],
                $data['salary_expectation'] ?? '', $data['cover_letter'] ?? '',
                isset($data['cover_letter_file']) ? json_encode($data['cover_letter_file']) : null,
                json_encode($data['answers'] ?? []), json_encode($data['links'] ?? []),
                isset($data['resume_file']) ? json_encode($data['resume_file']) : null,
                isset($data['portfolio_file']) ? json_encode($data['portfolio_file']) : null,
                $summary, $source, 'Submitted', date('Y-m-d H:i:s'),
            ]);
            return (int)self::pdo()->lastInsertId();
        } catch (Throwable $e) {
            error_log('ApplicationService submit: ' . $e->getMessage());
            return 0;
        }
    }

    /** Latest submission for a candidate (decoded), or null. */
    public static function latestForCandidate(int $candidateId): ?array
    {
        self::ensureTables();
        try {
            $stmt = self::pdo()->prepare('SELECT * FROM application_submissions WHERE candidate_id = ? ORDER BY id DESC LIMIT 1');
            $stmt->execute([$candidateId]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Throwable $e) {
            return null;
        }
        return $row ? self::decode($row) : null;
    }

    /** Employer pipeline stages (stored in `status`). "Submitted" reads as Under Review. */
    public const STAGES = ['Under Review', 'Shortlisted', 'Interviewing', 'Offer Extended', 'Rejected'];

    public static function stageOf(?array $app): string
    {
        $status = (string)($app['status'] ?? '');
        return in_array($status, self::STAGES, true) ? $status : 'Under Review';
    }

    /** Move an application to a pipeline stage; returns false for unknown ids or stages. */
    public static function setStage(int $id, string $stage): bool
    {
        if (!in_array($stage, self::STAGES, true)) {
            return false;
        }
        self::ensureTables();
        try {
            $stmt = self::pdo()->prepare('UPDATE application_submissions SET status = ? WHERE id = ?');
            $stmt->execute([$stage, $id]);
            return $stmt->rowCount() > 0;
        } catch (Throwable $e) {
            return false;
        }
    }

    /** All submissions for a candidate, newest first (decoded). */
    public static function listForCandidate(int $candidateId, int $limit = 100): array
    {
        self::ensureTables();
        try {
            $stmt = self::pdo()->prepare('SELECT * FROM application_submissions WHERE candidate_id = ? ORDER BY id DESC LIMIT ' . max(1, min(500, $limit)));
            $stmt->execute([$candidateId]);
            return array_map([self::class, 'decode'], $stmt->fetchAll(PDO::FETCH_ASSOC));
        } catch (Throwable $e) {
            return [];
        }
    }

    public static function find(int $id): ?array
    {
        self::ensureTables();
        try {
            $stmt = self::pdo()->prepare('SELECT * FROM application_submissions WHERE id = ?');
            $stmt->execute([$id]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Throwable $e) {
            return null;
        }
        return $row ? self::decode($row) : null;
    }

    private static function decode(array $row): array
    {
        foreach (['cover_letter_file', 'answers_json', 'links_json', 'resume_file', 'portfolio_file'] as $k) {
            $row[$k] = $row[$k] !== null ? (json_decode((string)$row[$k], true) ?: null) : null;
        }
        $row['answers'] = $row['answers_json'] ?? [];
        $row['links'] = $row['links_json'] ?? [];
        unset($row['answers_json'], $row['links_json']);
        return $row;
    }

    /**
     * A short third-person summary for the employer: Gemini when available, otherwise built
     * from the CV summary, the shared job and the answers. Returns [text, source].
     */
    public static function profileSummary(array $data, array $cv): array
    {
        if (GeminiClient::isConfigured()) {
            $facts = [
                'applying_for' => $data['internship_title'] . ' at ' . $data['company_name'],
                'name'         => $data['full_name'],
                'latest_job'   => trim($data['job_title'] . ' at ' . $data['job_company']),
                'cv_summary'   => $cv['summary'] ?? '',
                'experience'   => array_map(static fn ($e) => trim(($e['title'] ?? '') . ' at ' . ($e['company'] ?? '') . ' (' . ($e['start'] ?? '') . ' - ' . ($e['end'] ?? '') . ')'), array_slice($cv['experience'] ?? [], 0, 5)),
                'education'    => array_map(static fn ($e) => trim(($e['degree'] ?? '') . ' ' . ($e['field'] ?? '') . ', ' . ($e['school'] ?? '')), array_slice($cv['education'] ?? [], 0, 2)),
                'skills'       => array_slice($cv['skills'] ?? [], 0, 12),
                'answers'      => array_map(static fn ($a) => mb_substr((string)($a['answer'] ?? ''), 0, 600), $data['answers'] ?? []),
                'cover_letter' => mb_substr((string)($data['cover_letter'] ?? ''), 0, 1500),
            ];
            $ai = GeminiClient::json(
                "Write a professional profile summary of this internship applicant for the hiring team. 3-4 sentences, third person, factual. "
                . "Refer to the applicant by name or as \"they\"; never assume gender. "
                . "Base it only on the facts below (no invented details, no scores). Mention relevant experience, strengths and motivation.\n"
                . 'FACTS: ' . json_encode($facts, JSON_UNESCAPED_UNICODE) . "\nReturn JSON: {\"summary\":\"...\"}",
                15
            );
            $text = trim(strip_tags((string)($ai['summary'] ?? '')));
            if ($text !== '') {
                return [mb_substr($text, 0, 1200), 'gemini'];
            }
        }

        $parts = [];
        if (!empty($cv['summary'])) {
            $parts[] = $cv['summary'];
        } else {
            $parts[] = $data['full_name'] . ' is applying for the ' . $data['internship_title'] . ' role at ' . $data['company_name'] . '.';
        }
        if ($data['job_title'] !== '') {
            $parts[] = 'Most recent experience: ' . $data['job_title'] . ($data['job_company'] !== '' ? ' at ' . $data['job_company'] : '') . '.';
        }
        if (!empty($cv['skills'])) {
            $parts[] = 'Key skills: ' . implode(', ', array_slice($cv['skills'], 0, 6)) . '.';
        }
        return [implode(' ', $parts), 'rules'];
    }
}
