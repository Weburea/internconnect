<?php
declare(strict_types=1);

namespace App\Services;

use App\Core\Database;
use PDO;
use Throwable;

/**
 * InternConnect Enterprise Export & Reporting Service
 * Generates standards-compliant CSV and JSON data exports for enterprise hiring pipelines,
 * interview schedules, and financial ledgers.
 */
class ExportService
{
    /**
     * Stream CSV download directly to browser with Excel UTF-8 BOM
     */
    public static function streamCsv(string $filename, array $headers, array $rows): void
    {
        if (ob_get_level()) {
            ob_end_clean();
        }

        header('Content-Type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename="' . rawurlencode($filename) . '"');
        header('Pragma: no-cache');
        header('Expires: 0');

        $output = fopen('php://output', 'w');
        // UTF-8 BOM for Microsoft Excel compatibility
        fwrite($output, "\xEF\xBB\xBF");

        fputcsv($output, $headers);

        foreach ($rows as $row) {
            fputcsv($output, $row);
        }

        fclose($output);
        exit;
    }

    /**
     * Generate Candidate Pipeline Export
     */
    public static function exportCandidatesPipeline(array $candidates = []): void
    {
        $headers = [
            'Candidate ID',
            'Full Name',
            'Email Address',
            'Target Role',
            'University / Institution',
            'AI Match Score (%)',
            'Pipeline Stage',
            'Applied Date',
            'Status'
        ];

        $rows = [];
        foreach ($candidates as $c) {
            $rows[] = [
                $c['id'] ?? 'N/A',
                $c['name'] ?? 'Candidate',
                $c['email'] ?? strtolower(str_replace(' ', '.', $c['name'] ?? 'candidate')) . '@student.unilag.edu.ng',
                $c['role'] ?? 'Intern',
                $c['school'] ?? ($c['student_title'] ?? 'University of Lagos'),
                ($c['match_score'] ?? 90) . '%',
                $c['pipeline_stage'] ?? ($c['status'] ?? 'Under Review'),
                $c['applied_time'] ?? 'Recent',
                $c['status'] ?? 'Active',
            ];
        }

        self::streamCsv('internconnect_candidates_pipeline_' . date('Y-m-d') . '.csv', $headers, $rows);
    }

    /**
     * Generate Interview Schedule Export
     */
    public static function exportInterviews(array $interviews = []): void
    {
        $headers = [
            'Interview ID',
            'Candidate Name',
            'Role',
            'Company',
            'Interviewer',
            'Meeting Format',
            'Scheduled Date',
            'Scheduled Time',
            'Duration',
            'Meeting Link',
            'Status'
        ];

        $rows = [];
        foreach ($interviews as $i) {
            $rows[] = [
                $i['id'] ?? 'N/A',
                $i['candidate_name'] ?? 'Sarah Peters',
                $i['candidate_role'] ?? 'Intern',
                $i['company_name'] ?? 'Nexura Technologies',
                $i['interviewer_name'] ?? 'Tunde Bakare',
                $i['meeting_type'] ?? 'Virtual (InternConnect video room)',
                $i['interview_date'] ?? date('Y-m-d'),
                $i['interview_time'] ?? '10:00 AM',
                $i['duration'] ?? '45 mins',
                $i['meeting_link'] ?? url('/candidate/interview/room/1'),
                $i['status'] ?? 'Scheduled',
            ];
        }

        self::streamCsv('internconnect_interviews_schedule_' . date('Y-m-d') . '.csv', $headers, $rows);
    }

    /**
     * Generate Billing & Paystack Ledger Export
     */
    public static function exportBilling(array $transactions = []): void
    {
        self::exportBillingLedger($transactions);
    }

    /**
     * Generate Billing & Paystack Ledger Export
     */
    public static function exportBillingLedger(array $transactions = []): void
    {
        $headers = [
            'Transaction Ref',
            'Plan Tier',
            'Billing Cycle',
            'Amount (NGN)',
            'Payment Gateway',
            'Payment Status',
            'Date'
        ];

        $rows = [];
        foreach ($transactions as $t) {
            $rows[] = [
                $t['reference'] ?? 'TRX_' . mt_rand(100000, 999999),
                $t['plan'] ?? 'Pro Recruiter',
                $t['cycle'] ?? 'Monthly',
                '₦' . number_format((float)($t['amount'] ?? 15000), 2),
                $t['method'] ?? 'Paystack',
                $t['status'] ?? 'Successful',
                $t['date'] ?? date('Y-m-d H:i:s'),
            ];
        }

        self::streamCsv('internconnect_billing_ledger_' . date('Y-m-d') . '.csv', $headers, $rows);
    }
}
