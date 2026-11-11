<?php
declare(strict_types=1);
/**
 * Candidate interview room drawer: Agenda and Jenny AI tips.
 * Rendered inside frontend/shared/components/video-room.php (parent scope provides `drawerTab`).
 *
 * @var array $room Room details from VideoInterviewService::getRoomDetails()
 */
?>
<style>
.cr-host { border: 1px solid #E2E8F0; border-radius: 12px; padding: 14px; background: #F8FAFC; }
.cr-host span { display: block; font-size: 11px; font-weight: 700; letter-spacing: .06em; text-transform: uppercase; color: #64748B; margin-bottom: 4px; }
.cr-host strong { display: block; font-size: 14px; font-weight: 800; color: #000623; }
.cr-host em { display: block; font-style: normal; font-size: 12.5px; color: #4F48E6; font-weight: 600; margin-top: 2px; }
.cr-label { font-size: 12.5px; font-weight: 800; color: #0F172A; }
.cr-agenda { list-style: none; margin: 0; padding: 0; display: flex; flex-direction: column; gap: 10px; }
.cr-agenda li { display: flex; gap: 10px; align-items: flex-start; font-size: 13px; color: #334155; line-height: 1.45; }
.cr-agenda b { flex-shrink: 0; min-width: 54px; text-align: center; padding: 3px 6px; border-radius: 6px; background: #EEF2FF; color: #4F48E6; font-size: 11px; font-weight: 800; white-space: nowrap; }
.cr-tip { display: flex; gap: 12px; padding: 12px; border: 1px solid #E2E8F0; border-radius: 12px; }
.cr-tip-icon { width: 32px; height: 32px; border-radius: 9px; background: #EEF2FF; color: #4F48E6; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.cr-tip-icon svg { width: 15px; height: 15px; }
.cr-tip h4 { font-size: 13px; font-weight: 800; color: #000623; margin: 0 0 3px; }
.cr-tip p { font-size: 12px; color: #64748B; margin: 0; line-height: 1.5; }
</style>

<div class="vr-panel" x-show="drawerTab === 'agenda'">
    <div class="cr-host">
        <span>Host organization</span>
        <strong><?= htmlspecialchars($room['company_name']) ?></strong>
        <em><?= htmlspecialchars($room['candidate_role']) ?> &middot; with <?= htmlspecialchars($room['interviewer_name']) ?></em>
    </div>
    <span class="cr-label">Session plan (<?= (int)$room['duration_minutes'] ?> minutes)</span>
    <ol class="cr-agenda">
        <?php foreach ($room['agenda'] as $item): ?>
        <li><b><?= htmlspecialchars($item['range']) ?></b><span><?= htmlspecialchars($item['text']) ?></span></li>
        <?php endforeach; ?>
    </ol>
</div>

<div class="vr-panel" x-show="drawerTab === 'tips'">
    <span class="cr-label">Jenny AI tips for this interview</span>
    <?php foreach ($room['jenny_ai_tips'] as $tip): ?>
    <div class="cr-tip">
        <span class="cr-tip-icon"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg></span>
        <div>
            <h4><?= htmlspecialchars($tip['title']) ?></h4>
            <p><?= htmlspecialchars($tip['desc']) ?></p>
        </div>
    </div>
    <?php endforeach; ?>
</div>
