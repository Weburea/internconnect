<?php

declare(strict_types=1);

/**
 * Scheduled jobs (cPanel > Cron Jobs). Command line only; .htaccess never serves bin/.
 *
 *   Every 5 minutes:  php /home/USER/public_html/bin/cron.php mail
 *   Once a day:       php /home/USER/public_html/bin/cron.php daily
 *
 * mail   sends due e-mails from mail_queue in batches (newsletter campaigns, retries).
 * daily  closes expired plan grants (and training-partner grants), then sends mail too.
 * A lock file stops two runs overlapping. Output is one line per job, for the cron e-mail/log.
 */

if (PHP_SAPI !== 'cli') {
    http_response_code(404);
    exit;
}

$root = dirname(__DIR__);
require $root . '/vendor/autoload.php';
require_once $root . '/backend/Core/helpers.php';
\App\Core\Env::load($root);
chdir($root);
date_default_timezone_set(\App\Core\Env::get('APP_TIMEZONE', 'Africa/Lagos'));
// Links built outside a request use APP_URL (MailService::link); give helpers a sane host too.
$_SERVER['HTTP_HOST'] = parse_url(\App\Core\Env::get('APP_URL', 'http://localhost'), PHP_URL_HOST) ?: 'localhost';
$_SERVER['SCRIPT_NAME'] = rtrim((string)parse_url(\App\Core\Env::get('APP_URL', ''), PHP_URL_PATH), '/') . '/index.php';

$job = $argv[1] ?? 'mail';
if (!in_array($job, ['mail', 'daily'], true)) {
    fwrite(STDERR, "Usage: php bin/cron.php mail|daily\n");
    exit(2);
}

$lockDir = $root . '/storage/cache';
if (!is_dir($lockDir)) {
    @mkdir($lockDir, 0775, true);
}
$lock = fopen($lockDir . '/cron-' . $job . '.lock', 'c');
if (!$lock || !flock($lock, LOCK_EX | LOCK_NB)) {
    echo date('c') . " $job: previous run still busy, skipped\n";
    exit(0);
}

$log = static function (string $line): void {
    echo date('c') . ' ' . $line . "\n";
};

try {
    if ($job === 'daily') {
        $log('plan grants expired: ' . \App\Services\PlanService::expireGrants());
        $log('plan expiry reminders queued: ' . \App\Services\PlanService::sendExpiryReminders());
        $log('visit records older than 90 days removed: ' . \App\Services\VisitService::prune(90));
        if (class_exists(\App\Services\PartnerService::class)) {
            foreach (\App\Services\PartnerService::dailyMaintenance() as $k => $v) {
                $log('partners ' . $k . ': ' . $v);
            }
        }
    }
    $r = \App\Services\MailService::processQueue($job === 'daily' ? 100 : 40);
    $log(sprintf('mail: %d sent, %d failed, %d still queued%s', $r['sent'], $r['failed'], $r['left'], \App\Services\MailService::configured() ? '' : ' (MAIL_PASSWORD not set)'));
} catch (Throwable $e) {
    $log('ERROR ' . get_class($e) . ': ' . $e->getMessage());
    exit(1);
} finally {
    flock($lock, LOCK_UN);
    fclose($lock);
}
