<?php
declare(strict_types=1);
/**
 * View: Candidate Dashboard
 * Route: /dashboard
 * Layout: dashboard (custom sidebar layout — no global navbar/footer)
 * @var array  $candidate  Candidate profile data
 * @var array  $stats      KPI statistics
 * @var array  $recommendations Recommended internships
 * @var array  $applications   Recent applications
 * @var array  $alerts         Recent alerts/notifications
 */

$name      = $candidate['name'] ?? 'Sarah Peters';
$firstName = explode(' ', trim($name))[0];
$role      = $candidate['role'] ?? 'Student Applicant';
$avatar    = $candidate['avatar'] ?? null;
?>
<div class="db-shell">

    <!-- Sidebar Overlay (mobile) -->
    <div class="db-sidebar-overlay" id="db-sidebar-overlay"></div>    <!-- ══ SIDEBAR ══ -->
    <?php 
    $activeNav = 'dashboard';
    include dirname(__DIR__, 2) . '/shared/components/candidate-sidebar.php'; 
    ?>

    <!-- ══ MAIN ══ -->
    <div class="db-main">

        <!-- ══ TOP NAVBAR ══ -->
        <?php include dirname(__DIR__, 2) . '/shared/components/candidate-topnav.php'; ?>

        <!-- Scrollable Content -->
        <div class="db-content" id="db-content">

            <!-- ── Welcome Banner ── -->
            <section class="db-banner" aria-labelledby="db-banner-heading">
                <h1 id="db-banner-heading">Welcome back, <?= htmlspecialchars($firstName) ?></h1>
                <p>Track your internship applications and discover new opportunities designed for your skill set.</p>
            </section>

            <!-- ── KPI Stats ── -->
            <div class="db-stats-row">
                <?php foreach ($stats as $stat): ?>
                <div class="db-stat-card">
                    <!-- Subtle background curve shape -->
                    <div style="position: absolute; top: -10px; right: -10px; width: 80px; height: 80px; background: #F8FAFC; border-radius: 50%; z-index: 1;"></div>
                    
                    <div class="db-stat-top">
                        <div class="db-stat-icon <?= $stat['color'] ?>">
                            <?= $stat['icon'] ?>
                        </div>
                        <span class="db-stat-badge <?= $stat['badge_type'] ?>"><?= $stat['badge'] ?></span>
                    </div>
                    <div class="db-stat-label"><?= $stat['label'] ?></div>
                    <div class="db-stat-val"><?= str_pad((string)$stat['value'], 2, '0', STR_PAD_LEFT) ?></div>
                    
                    <?php if ($stat['color'] === 'blue'): ?>
                        <div style="display: flex; height: 3px; background: #F1F5F9; border-radius: 4px; margin-bottom: 12px; width: 100%;">
                            <div style="width: 60%; background: #4F47E6; border-radius: 4px;"></div>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($stat['desc'])): ?>
                        <div class="db-stat-desc"><?= $stat['desc'] ?></div>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- ── Middle Row ── -->
            <div class="db-mid-row">
                <style>
                .db-chart-col {
                    position: relative;
                }
                .cand-chart-tooltip {
                    position: absolute;
                    top: -42px;
                    left: 50%;
                    transform: translateX(-50%) translateY(4px);
                    background: #0F172A;
                    color: #FFFFFF;
                    font-size: 11px;
                    font-weight: 700;
                    padding: 6px 12px;
                    border-radius: 8px;
                    white-space: nowrap;
                    opacity: 0;
                    visibility: hidden;
                    transition: all 0.2s ease;
                    pointer-events: none;
                    box-shadow: 0 10px 15px -3px rgba(0,0,0,0.3);
                    z-index: 30;
                }
                .cand-chart-tooltip::after {
                    content: '';
                    position: absolute;
                    bottom: -4px;
                    left: 50%;
                    transform: translateX(-50%);
                    border-width: 5px 5px 0 5px;
                    border-style: solid;
                    border-color: #0F172A transparent transparent transparent;
                }
                .db-chart-col:hover .cand-chart-tooltip {
                    opacity: 1;
                    visibility: visible;
                    transform: translateX(-50%) translateY(0);
                }
                </style>
                <!-- Application Performance Chart -->
                <div class="db-card" x-data="{ view: 'monthly', charts: {
                    weekly: [
                        { label: 'APPLIED', pct: 45, class: 'blue', tooltip: 'Applied: 12 Applications' },
                        { label: 'REVIEW', pct: 30, class: 'lblue', tooltip: 'In Review: 5 Applications' },
                        { label: 'INTERVIEW', pct: 15, class: 'blue', tooltip: 'Interviews: 2 Scheduled' },
                        { label: 'ACCEPT', pct: 10, class: 'green', tooltip: 'Accepted: 1 Offer' },
                        { label: 'REJECT', pct: 5, class: 'red', tooltip: 'Rejected: 1 Application' }
                    ],
                    monthly: [
                        { label: 'APPLIED', pct: 90, class: 'blue', tooltip: 'Applied: 34 Applications' },
                        { label: 'REVIEW', pct: 60, class: 'lblue', tooltip: 'In Review: 10 Applications' },
                        { label: 'INTERVIEW', pct: 40, class: 'blue', tooltip: 'Interviews: 4 Scheduled' },
                        { label: 'ACCEPT', pct: 25, class: 'green', tooltip: 'Accepted: 2 Offers' },
                        { label: 'REJECT', pct: 20, class: 'red', tooltip: 'Rejected: 3 Applications' }
                    ]
                }}">
                    <div class="db-card-header">
                        <h3 class="db-card-title">Application Performance</h3>
                        <div class="db-toggle-group">
                            <button class="db-toggle-btn" :class="{ 'active': view === 'weekly' }" @click="view = 'weekly'" type="button">Weekly</button>
                            <button class="db-toggle-btn" :class="{ 'active': view === 'monthly' }" @click="view = 'monthly'" type="button">Monthly</button>
                        </div>
                    </div>
                    <div class="db-chart-area" role="img" aria-label="Bar chart showing application stages">
                        <template x-for="bar in charts[view]" :key="bar.label">
                            <div class="db-chart-col">
                                <div class="cand-chart-tooltip" x-text="bar.tooltip"></div>
                                <div class="db-chart-bar-wrap">
                                    <div class="db-chart-bar" :class="bar.class" :style="'height: ' + bar.pct + '%'"></div>
                                </div>
                                <span class="db-chart-label" x-text="bar.label"></span>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Complete Profile -->
                <div class="db-card db-profile-card">
                    <div class="db-card-header">
                        <h3 class="db-card-title">Complete Profile</h3>
                    </div>
                    <div class="db-progress-bar-wrap">
                        <div class="db-progress-label">
                            <span>Profile Completion</span>
                            <span class="db-progress-pct"><?= $candidate['profile_pct'] ?? 75 ?>%</span>
                        </div>
                        <div class="db-progress-track">
                            <div class="db-progress-fill" style="width: <?= $candidate['profile_pct'] ?? 75 ?>%;"></div>
                        </div>
                    </div>
                    <div class="db-checklist">
                        <?php foreach (($candidate['checklist'] ?? []) as $item): ?>
                        <div class="db-check-item">
                            <div class="db-check-dot <?= $item['done'] ? 'done' : 'undone' ?>">
                                <?php if ($item['done']): ?>
                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                <?php else: ?>
                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/></svg>
                                <?php endif; ?>
                            </div>
                            <span><?= htmlspecialchars($item['label']) ?></span>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <a href="<?= url('/profile') ?>" class="db-update-btn" id="db-update-profile-btn">Update Profile</a>
                </div>
            </div>

            <!-- ── Recommended For You ── -->
            <section aria-labelledby="db-recs-heading">
                <div class="db-section-header">
                    <h3 class="db-section-title" id="db-recs-heading">Recommended for You</h3>
                    <a href="<?= url('/internships') ?>" class="db-view-all">View All Matches</a>
                </div>
                <div class="db-rec-grid">
                    <?php foreach ($recommendations as $rec): ?>
                    <div class="db-rec-card" id="db-rec-card-<?= htmlspecialchars($rec['slug']) ?>" x-data="{ saved: false }" style="background: #ffffff; border: 1px solid #E5E7EB; border-radius: 16px; padding: 22px; display: flex; flex-direction: column; justify-content: space-between; transition: all 0.2s ease;">
                        
                        <!-- Top Header Info -->
                        <div>
                            <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 12px; margin-bottom: 12px;">
                                <!-- Brand Logo / Category / Title -->
                                <div style="display: flex; align-items: center; gap: 12px; min-width: 0; flex: 1;">
                                    <?php if (!empty($rec['logo_url'])): ?>
                                        <img src="<?= htmlspecialchars($rec['logo_url']) ?>" alt="<?= htmlspecialchars($rec['company']) ?>" style="width: 40px; height: 40px; border-radius: 12px; object-fit: cover; flex-shrink: 0; box-shadow: 0 1px 3px rgba(0,0,0,0.06);">
                                    <?php else: ?>
                                        <div style="<?= $rec['logo_bg'] ?> width: 40px; height: 40px; min-width: 40px; min-height: 40px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 16px; flex-shrink: 0; box-shadow: 0 1px 3px rgba(0,0,0,0.06);">
                                            <?= htmlspecialchars($rec['initial'] ?? substr($rec['company'], 0, 1)) ?>
                                        </div>
                                    <?php endif; ?>
                                    <div style="min-width: 0; flex: 1;">
                                        <span style="display: block; font-size: 10px; font-weight: 700; color: #4F47E6; text-transform: uppercase; letter-spacing: 0.08em; line-height: 1.2; margin-bottom: 2px;">
                                            <?= htmlspecialchars($rec['category']) ?>
                                        </span>
                                        <h4 style="font-size: 14.5px; font-weight: 700; color: #111827; margin: 0; line-height: 1.25; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="<?= htmlspecialchars($rec['title']) ?>">
                                            <?= htmlspecialchars($rec['title']) ?>
                                        </h4>
                                    </div>
                                </div>

                                <!-- Bookmark Icon Button -->
                                <button type="button" @click="saved = !saved" style="background: none; border: none; padding: 4px; color: #9CA3AF; cursor: pointer; border-radius: 8px; flex-shrink: 0; transition: color 0.15s;" :style="saved ? 'color: #4F47E6;' : ''" aria-label="Save <?= htmlspecialchars($rec['title']) ?>" id="db-rec-bookmark-<?= htmlspecialchars($rec['slug']) ?>">
                                    <!-- Saved outline state -->
                                    <svg x-show="!saved" style="width: 20px; height: 20px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/>
                                    </svg>
                                    <!-- Saved filled state -->
                                    <svg x-show="saved" style="display: none; width: 20px; height: 20px; color: #4F47E6;" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/>
                                    </svg>
                                </button>
                            </div>

                            <!-- Company, Location & Commitment -->
                            <div style="display: flex; align-items: center; gap: 6px; font-size: 12px; color: #6B7280; margin-bottom: 12px; flex-wrap: wrap;">
                                <a href="<?= url('/company/' . ($rec['company_slug'] ?? '')) ?>" style="font-weight: 600; color: #374151; text-decoration: none; transition: color 0.12s;"><?= htmlspecialchars($rec['company']) ?></a>
                                <span style="color: #D1D5DB; font-size: 10px;">•</span>
                                <span><?= htmlspecialchars($rec['location']) ?></span>
                                <span style="color: #D1D5DB; font-size: 10px;">•</span>
                                <span style="font-style: italic; font-size: 11px;"><?= htmlspecialchars($rec['commitment']) ?></span>
                            </div>

                            <!-- Brief Description Excerpt -->
                            <p style="font-size: 12px; color: #4B5563; line-height: 1.55; margin: 0 0 16px 0; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                <?= htmlspecialchars($rec['description']) ?>
                            </p>
                        </div>

                        <!-- Footer: Work Arrangement Tag + Apply Button -->
                        <div style="display: flex; align-items: center; justify-content: space-between; border-top: 1px solid #F3F4F6; padding-top: 12px; margin-top: auto;">
                            <span style="font-size: 10px; font-weight: 700; padding: 4px 10px; border-radius: 8px; background: #F3F4F6; color: #374151; letter-spacing: 0.05em; text-transform: uppercase; white-space: nowrap; flex-shrink: 0;">
                                <?= htmlspecialchars($rec['arrangement']) ?>
                            </span>
                            <a href="<?= url('/internship/' . $rec['slug']) ?>" class="db-apply-btn" id="db-apply-btn-<?= htmlspecialchars($rec['slug']) ?>" style="background: #4F47E6; color: #ffffff; font-size: 12px; font-weight: 700; padding: 6px 14px; border-radius: 10px; text-decoration: none; display: inline-flex; align-items: center; justify-content: center; white-space: nowrap !important; flex-shrink: 0 !important; transition: all 0.15s ease;">
                                Apply
                            </a>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </section>

            <!-- ── Bottom Row ── -->
            <div class="db-bottom-row">
                <!-- Recent Applications -->
                <div class="db-card">
                    <div class="db-table-header">
                        <h3 class="db-card-title">Recent Applications</h3>
                        <div style="position: relative;" x-data="{ open: false }" @click.outside="open = false">
                            <button class="db-filter-btn" type="button" @click="open = !open">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                                Filter
                            </button>
                            <div x-show="open" style="display: none; position: absolute; top: calc(100% + 8px); right: 0; width: 180px; background: #fff; border: 1px solid #E8ECF2; border-radius: 10px; box-shadow: 0 8px 24px rgba(0,0,0,.08); z-index: 50; padding: 8px;">
                                <div style="padding: 6px 12px; font-size: 13px; cursor: pointer; border-radius: 6px;" onmouseover="this.style.background='#F1F5F9'" onmouseout="this.style.background='transparent'">All Statuses</div>
                                <div style="padding: 6px 12px; font-size: 13px; cursor: pointer; border-radius: 6px;" onmouseover="this.style.background='#F1F5F9'" onmouseout="this.style.background='transparent'">Interviewing</div>
                                <div style="padding: 6px 12px; font-size: 13px; cursor: pointer; border-radius: 6px;" onmouseover="this.style.background='#F1F5F9'" onmouseout="this.style.background='transparent'">Accepted</div>
                            </div>
                        </div>
                    </div>
                    <div class="db-table-responsive">
                        <table class="db-table" aria-label="Recent applications">
                            <thead>
                                <tr>
                                    <th>Company</th>
                                    <th>Role</th>
                                    <th class="col-date-applied">Date Applied</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($applications as $app): ?>
                                <tr>
                                    <td class="company-name"><?= htmlspecialchars($app['company']) ?></td>
                                    <td class="role-name"><?= htmlspecialchars($app['role']) ?></td>
                                    <td class="date-cell col-date-applied"><?= htmlspecialchars($app['date']) ?></td>
                                    <td><span class="db-status-pill <?= $app['status_class'] ?>"><?= htmlspecialchars($app['status']) ?></span></td>
                                    <td style="text-align: right;">
                                        <div style="position: relative; display: inline-block; text-align: left;" x-data="{ open: false }" @click.outside="open = false">
                                            <button class="db-action-btn" type="button" @click="open = !open">...</button>
                                            <div x-show="open" style="display: none; position: absolute; top: calc(100% - 5px); right: 0; width: 160px; background: #fff; border: 1px solid #E8ECF2; border-radius: 10px; box-shadow: 0 8px 24px rgba(0,0,0,.08); z-index: 50; padding: 8px;">
                                                <div style="padding: 6px 12px; font-size: 13px; color: #191C1D; cursor: pointer; border-radius: 6px; display: flex; align-items: center; gap: 8px;" onmouseover="this.style.background='#F1F5F9'" onmouseout="this.style.background='transparent'">
                                                    <svg style="width: 14px; height: 14px; color: #64748B;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                    </svg>
                                                    <span>View Details</span>
                                                </div>
                                                <div style="padding: 6px 12px; font-size: 13px; color: #DC2626; cursor: pointer; border-radius: 6px; display: flex; align-items: center; gap: 8px;" onmouseover="this.style.background='#FEE2E2'" onmouseout="this.style.background='transparent'">
                                                    <svg style="width: 14px; height: 14px; color: #DC2626;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                    <span>Withdraw</span>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="db-pagination">
                        <button class="db-page-btn active">1</button>
                        <button class="db-page-btn">2</button>
                        <button class="db-page-btn">3</button>
                        <span class="db-page-dots">...</span>
                        <button class="db-page-btn" style="padding: 0 12px;">Next</button>
                    </div>
                </div>

                <!-- Recent Alerts -->
                <div class="db-card">
                    <div class="db-alerts-header">
                        <h3 class="db-card-title">Recent Alerts</h3>
                        <span class="db-alert-count" aria-label="7 unread alerts">7</span>
                    </div>
                    <div class="db-alert-list" role="list">
                        <?php foreach ($alerts as $alert): ?>
                        <div class="db-alert-item" role="listitem">
                            <div class="db-alert-icon-wrap <?= $alert['color'] ?>">
                                <?= $alert['icon'] ?>
                            </div>
                            <div class="db-alert-content">
                                <div class="db-alert-title"><?= htmlspecialchars($alert['title']) ?></div>
                                <div class="db-alert-desc"><?= htmlspecialchars($alert['desc']) ?></div>
                                <div class="db-alert-time"><?= htmlspecialchars($alert['time']) ?></div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <a href="<?= url('/notifications') ?>" class="db-mark-read">Mark all as read</a>
                </div>
            </div>
        </div><!-- /.db-content -->

        <!-- Dashboard Footer -->
        <?php include __DIR__ . '/../../shared/components/db-footer.php'; ?>

        <!-- Mobile Sidebar Toggle Script -->
        <script>
        (function() {
            var hamburger = document.getElementById('db-hamburger');
            var sidebar   = document.querySelector('.db-sidebar');
            var overlay   = document.getElementById('db-sidebar-overlay');
            if (!hamburger || !sidebar || !overlay) return;
            function toggleSidebar() {
                sidebar.classList.toggle('is-open');
                overlay.classList.toggle('is-open');
            }
            hamburger.addEventListener('click', toggleSidebar);
            overlay.addEventListener('click', toggleSidebar);
        })();
        </script>
    </div><!-- /.db-main -->
</div><!-- /.db-shell -->
