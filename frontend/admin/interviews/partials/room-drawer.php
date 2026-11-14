<?php
declare(strict_types=1);
/**
 * Admin interview room drawer: session brief.
 * Rendered inside frontend/shared/components/video-room.php (parent scope provides `drawerTab`).
 *
 * @var array $room Room details from VideoInterviewService::getRoomDetails()
 */
$adminRows = [
    'Candidate' => $room['candidate_name'] . (!empty($room['candidate_school']) ? ' · ' . $room['candidate_school'] : ''),
    'Role'      => $room['candidate_role'],
    'Employer'  => $room['company_name'] . ' · ' . $room['interviewer_name'],
    'When'      => $room['time'],
    'Length'    => (int)$room['duration_minutes'] . ' minutes',
];
?>
<style>
.ar-brief { border: 1px solid #E2E8F0; border-radius: 12px; overflow: hidden; }
.ar-brief div { display: grid; grid-template-columns: 84px minmax(0, 1fr); gap: 10px; padding: 10px 14px; font-size: 12.5px; }
.ar-brief div + div { border-top: 1px solid #F1F5F9; }
.ar-brief dt { font-weight: 700; color: #64748B; margin: 0; }
.ar-brief dd { margin: 0; color: #0F172A; font-weight: 600; overflow-wrap: anywhere; }
.ar-note { font-size: 12px; color: #64748B; line-height: 1.55; margin: 0; padding: 12px 14px; border-radius: 12px; background: #F8FAFC; }
.ar-links { display: flex; gap: 8px; flex-wrap: wrap; }
.ar-link { display: inline-flex; align-items: center; height: 34px; padding: 0 12px; border-radius: 9px; border: 1px solid #E2E8F0; font-size: 12.5px; font-weight: 700; color: #334155; text-decoration: none; }
.ar-link:hover { background: #F8FAFC; color: #0F172A; }
</style>

<div class="vr-panel" x-show="drawerTab === 'session'">
    <dl class="ar-brief" style="margin: 0;">
        <?php foreach ($adminRows as $label => $value): ?>
        <div><dt><?= htmlspecialchars($label) ?></dt><dd><?= htmlspecialchars((string)$value) ?></dd></div>
        <?php endforeach; ?>
    </dl>
    <p class="ar-note">Admins can open this room at any time. The candidate can only enter shortly before the start time, and the interview clock starts at the scheduled time, not when you join.</p>
    <div class="ar-links">
        <a class="ar-link" href="<?= url('/admin/interviews/' . (int)$room['id']) ?>">Session details</a>
        <a class="ar-link" href="<?= url('/admin/interviews') ?>">All interviews</a>
    </div>
</div>
