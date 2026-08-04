<?php

declare(strict_types=1);

/**
 * View: Internship Detail Page
 *
 * @var array  $internship  Mock data from InternshipController::show()
 * @var string $title
 *
 * IMPORTANT: Only use Tailwind classes that exist in the compiled
 * public/assets/css/style.css. Confirmed available grid spans:
 *   lg:col-span-3, lg:col-span-9, lg:grid-cols-12
 * Missing (do NOT use): lg:col-span-4, lg:col-span-8, top-24, gap-10
 */

$i = $internship;

/**
 * Return the inline SVG for a requirement card icon type.
 */
function reqIcon(string $type): string
{
    $icons = [
        'academic' => '<svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5zm0 7v-6m-4-1.268A10.97 10.97 0 0012 21a10.97 10.97 0 004-0.732V14m-8 0l4 2 4-2"/></svg>',
        'technical' => '<svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>',
        'collaboration' => '<svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>',
        'detail'    => '<svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zm6 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
    ];
    return $icons[$type] ?? $icons['detail'];
}

/**
 * Return the CSS classes for a tag badge based on its text.
 */
function tagBadgeClass(string $tag): string
{
    return match (strtoupper($tag)) {
        'INTERNSHIP' => 'bg-orange-50 text-orange-600 border border-orange-200',
        'FULL-TIME'  => 'bg-gray-100 text-gray-600 border border-gray-200',
        'PART-TIME'  => 'bg-gray-100 text-gray-600 border border-gray-200',
        'REMOTE'     => 'bg-gray-100 text-gray-600 border border-gray-200',
        'HYBRID'     => 'bg-primary-light text-primary border border-primary-light',
        'ON SITE'    => 'bg-gray-100 text-gray-600 border border-gray-200',
        default      => 'bg-gray-100 text-gray-600 border border-gray-200',
    };
}
?>

<div class="bg-white min-h-screen">

    <!-- ─── BREADCRUMB ────────────────────────────────────────────────────── -->
    <div class="bg-gray-50 border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3">
            <nav aria-label="Breadcrumb">
                <ol class="flex items-center space-x-2 text-sm text-gray-500">
                    <li>
                        <a href="<?= url('/internships') ?>" class="hover:text-primary transition">Internships</a>
                    </li>
                    <li aria-hidden="true">
                        <svg class="h-4 w-4 text-gray-300 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                    </li>
                    <li>
                        <span class="text-gray-500"><?= htmlspecialchars($i['category']) ?></span>
                    </li>
                    <li aria-hidden="true">
                        <svg class="h-4 w-4 text-gray-300 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                    </li>
                    <li>
                        <span class="font-medium text-gray-800"><?= htmlspecialchars($i['sub_category']) ?></span>
                    </li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- ─── MAIN LAYOUT ──────────────────────────────────────────────────── -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <!--
            LAYOUT: 12-col grid.
            lg:col-span-9  → main content  (confirmed in compiled CSS)
            lg:col-span-3  → sidebar       (confirmed in compiled CSS)
        -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

            <!-- ═══════════════ LEFT: MAIN CONTENT ═══════════════ -->
            <main class="lg:col-span-9 space-y-8">

                <!-- ── Header ─────────────────────────────────── -->
                <header>
                    <!-- Job title -->
                    <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 tracking-tight leading-tight mb-4">
                        <?= htmlspecialchars($i['title']) ?>
                    </h1>

                    <!-- Company row + tags -->
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">

                        <!-- Left: company logo, name, location -->
                        <div class="flex items-center space-x-3">
                            <div class="h-12 w-12 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center font-bold text-xl flex-shrink-0">
                                <?= htmlspecialchars(substr($i['company'], 0, 1)) ?>
                            </div>
                            <div class="flex flex-wrap items-center gap-x-2 text-base">
                                <a href="<?= htmlspecialchars($i['company_url']) ?>" class="text-primary font-bold hover:underline whitespace-nowrap">
                                    <?= htmlspecialchars($i['company']) ?>
                                </a>
                                <span class="text-gray-300 hidden sm:inline">&bull;</span>
                                <span class="text-primary font-bold whitespace-nowrap"><?= htmlspecialchars($i['location']) ?></span>
                            </div>
                        </div>

                        <!-- Right: tag pills (REMOTE, FULL-TIME, INTERNSHIP) -->
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="text-sm font-medium text-gray-500 mr-1">Work Type:</span>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-[11px] sm:text-xs font-semibold tracking-wide bg-gray-100 text-gray-600 border border-gray-200">Remote</span>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-[11px] sm:text-xs font-semibold tracking-wide bg-orange-50 text-orange-600 border border-orange-200">Internship</span>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-[11px] sm:text-xs font-semibold tracking-wide bg-primary-light text-primary border border-primary-light">Full-time</span>
                        </div>

                    </div>

                </header>

                <!-- ── Role Overview ──────────────────────────── -->
                <section>
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Role Overview</h2>
                    <div class="space-y-4">
                        <?php foreach ($i['overview'] as $paragraph): ?>
                            <p class="text-sm text-gray-600 leading-relaxed">
                                <?= htmlspecialchars($paragraph) ?>
                            </p>
                        <?php endforeach; ?>
                    </div>
                </section>

                <!-- ── Key Responsibilities ───────────────────── -->
                <section>
                    <div class="bg-gray-50 border border-gray-100 rounded-2xl p-6">
                        <h2 class="text-lg font-bold text-gray-900 mb-4">Key Responsibilities</h2>
                        <ul class="space-y-3">
                            <?php foreach ($i['responsibilities'] as $item): ?>
                                <li class="flex items-start space-x-3">
                                    <span class="flex-shrink-0 mt-0.5 text-primary">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    </span>
                                    <span class="text-sm text-gray-700 leading-relaxed"><?= htmlspecialchars($item) ?></span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </section>

                <!-- ── What We're Looking For ─────────────────── -->
                <section>
                    <h2 class="text-xl font-bold text-gray-900 mb-6">What We're Looking For</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <?php foreach ($i['requirements'] as $req): ?>
                            <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm hover:shadow-md transition-shadow">
                                <div class="text-primary mb-3">
                                    <?= reqIcon($req['icon']) ?>
                                </div>
                                <h3 class="font-semibold text-gray-900 text-sm mb-1"><?= htmlspecialchars($req['title']) ?></h3>
                                <p class="text-xs text-gray-500 leading-relaxed"><?= htmlspecialchars($req['desc']) ?></p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </section>

                <!-- ── Program Benefits + Application Window ──── -->
                <section>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        <!-- Benefits -->
                        <div>
                            <h2 class="text-lg font-bold text-gray-900 mb-4">Program Benefits</h2>
                            <ul class="space-y-3">
                                <?php foreach ($i['benefits'] as $benefit): ?>
                                    <li class="flex items-center space-x-3">
                                        <span class="flex-shrink-0 h-8 w-8 rounded-lg bg-primary-light flex items-center justify-center">
                                            <svg class="h-4 w-4 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        </span>
                                        <span class="text-sm text-gray-700 font-medium"><?= htmlspecialchars($benefit) ?></span>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>

                        <!-- Application Window -->
                        <div>
                            <h2 class="text-lg font-bold text-gray-900 mb-4">Application Window</h2>
                            <p class="text-sm text-gray-600 leading-relaxed">
                                <?= htmlspecialchars($i['application_window']) ?>
                            </p>
                        </div>

                    </div>
                </section>

            </main>
            <!-- /main content -->

            <!-- ═══════════════ RIGHT: SIDEBAR ═══════════════════ -->
            <aside class="lg:col-span-3">
                <!--
                    Sticky container.
                    'top-24' is NOT in compiled CSS — use inline style instead.
                -->
                <div class="sticky space-y-4" style="top: 5.5rem;">

                    <!-- Salary + CTA card -->
                    <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">

                        <!-- Salary: single amount -->
                        <div class="mb-6">
                            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">
                                Monthly Compensation
                            </p>
                            <p class="text-3xl font-bold text-gray-900">
                                $3,000
                            </p>
                        </div>

                        <!-- Apply Now button -->
                        <a href="<?= url('/apply/' . $i['id']) ?>"
                           id="apply-now-btn"
                           class="w-full flex items-center justify-center bg-primary hover:bg-primary-hover text-white text-sm font-semibold py-3 px-6 rounded-xl transition shadow-sm mb-3 active:scale-95">
                            Apply Now
                        </a>

                        <!-- Save for Later button -->
                        <button id="save-later-btn"
                                class="w-full flex items-center justify-center space-x-2 border border-gray-300 text-gray-700 hover:bg-gray-50 text-sm font-semibold py-3 px-6 rounded-xl transition active:scale-95">
                            <svg class="h-4 w-4 text-gray-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/></svg>
                            <span>Save for Later</span>
                        </button>
                    </div>

                    <!-- Quick Stats card -->
                    <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">
                        <h3 class="text-sm font-bold text-gray-900 mb-4">Quick Stats</h3>
                        <dl class="space-y-3">
                            <div class="flex items-center justify-between">
                                <dt class="text-sm text-gray-500">Applicants</dt>
                                <dd class="text-sm font-semibold text-gray-900"><?= (int) $i['applicants'] ?></dd>
                            </div>
                            <div class="flex items-center justify-between">
                                <dt class="text-sm text-gray-500">Posted</dt>
                                <dd class="text-sm font-semibold text-gray-900"><?= htmlspecialchars($i['posted']) ?></dd>
                            </div>
                            <div class="flex items-center justify-between">
                                <dt class="text-sm text-gray-500">Level</dt>
                                <dd class="text-sm font-semibold text-gray-900"><?= htmlspecialchars($i['level']) ?></dd>
                            </div>
                        </dl>
                    </div>

                    <!-- Share Role card -->
                    <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm flex items-center justify-between">
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider" style="margin-bottom:0;">Share Role</p>
                        <div class="flex items-center space-x-3" x-data="{ copied: false, tip: '' }">

                            <!-- Copy link -->
                            <button id="share-copy-btn"
                                    @mouseenter="tip = 'copy'"
                                    @mouseleave="tip = ''; copied = false"
                                    @click="navigator.clipboard.writeText(window.location.href); copied = true"
                                    style="position:relative;"
                                    class="h-9 w-9 flex items-center justify-center rounded-xl border border-gray-200 hover:bg-gray-50 text-gray-500 hover:text-primary transition">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                                <span x-show="tip === 'copy'"
                                      x-transition
                                      style="display:none; position:absolute; top:100%; left:50%; transform:translateX(-50%); margin-top:8px; white-space:nowrap; background:#fff; border:1px solid #e5e7eb; color:#374151; font-size:10px; font-weight:700; padding:4px 12px; border-radius:9999px; box-shadow:0 2px 8px rgba(0,0,0,.08); z-index:50;"
                                      x-text="copied ? 'Copied!' : 'Copy link'"></span>
                            </button>

                            <!-- Email -->
                            <a href="mailto:?subject=<?= rawurlencode($i['title'] . ' at ' . $i['company']) ?>&amp;body=<?= rawurlencode('Check out this internship: ') ?>"
                               @mouseenter="tip = 'email'"
                               @mouseleave="tip = ''"
                               style="position:relative;"
                               class="h-9 w-9 flex items-center justify-center rounded-xl border border-gray-200 hover:bg-gray-50 text-gray-500 hover:text-primary transition">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                <span x-show="tip === 'email'"
                                      x-transition
                                      style="display:none; position:absolute; top:100%; left:50%; transform:translateX(-50%); margin-top:8px; white-space:nowrap; background:#fff; border:1px solid #e5e7eb; color:#374151; font-size:10px; font-weight:700; padding:4px 12px; border-radius:9999px; box-shadow:0 2px 8px rgba(0,0,0,.08); z-index:50;">Email</span>
                            </a>

                            <!-- Native share -->
                            <button id="share-native-btn"
                                    @mouseenter="tip = 'share'"
                                    @mouseleave="tip = ''"
                                    @click="if(navigator.share){navigator.share({title:document.title,url:window.location.href})}"
                                    style="position:relative;"
                                    class="h-9 w-9 flex items-center justify-center rounded-xl border border-gray-200 hover:bg-gray-50 text-gray-500 hover:text-primary transition">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/></svg>
                                <span x-show="tip === 'share'"
                                      x-transition
                                      style="display:none; position:absolute; top:100%; left:50%; transform:translateX(-50%); margin-top:8px; white-space:nowrap; background:#fff; border:1px solid #e5e7eb; color:#374151; font-size:10px; font-weight:700; padding:4px 12px; border-radius:9999px; box-shadow:0 2px 8px rgba(0,0,0,.08); z-index:50;">Share</span>
                            </button>

                        </div>
                    </div>

                </div><!-- /sticky wrapper -->
            </aside>
            <!-- /sidebar -->

        </div><!-- /grid -->
    </div><!-- /max-w-7xl -->

</div><!-- /bg-white -->
