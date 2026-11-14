<?php
declare(strict_types=1);
/**
 * View: Success Stories & Testimonials
 * Route: /testimonials, /success-stories
 * Layout: app
 * Content: Admin > Testimonials (App\Services\TestimonialService, docs/61-testimonials.md).
 * Floating wall: vertical marquee columns like the home section (pause on hover, static with reduced motion).
 */
use App\Services\TestimonialService;

$tmAll = TestimonialService::list('page');
$tmQuotes = array_values(array_filter($tmAll, static fn ($t) => $t['kind'] === 'quote'));
$tmVideos = array_values(array_filter($tmAll, static fn ($t) => $t['kind'] === 'video' && $t['video_url']));
$tmPlatforms = array_values(array_unique(array_column($tmQuotes, 'platform')));
$tmRated = array_filter(array_column($tmQuotes, 'rating'));
$tmAvg = $tmRated ? round(array_sum($tmRated) / count($tmRated), 1) : 5.0;
$tmData = array_map(static fn ($t) => [
    'id' => (int)$t['id'], 'name' => $t['name'], 'handle' => $t['handle'] ?: '', 'role' => $t['role'] ?: '', 'quote' => trim((string)$t['quote'], "\" "),
    'platform' => $t['platform'], 'platformLabel' => TestimonialService::PLATFORMS[$t['platform']] ?? 'InternConnect', 'badge' => TestimonialService::badge((string)$t['platform'], $t['platform'] === 'x' ? 14 : 16),
    'source' => $t['source_url'] ?: '', 'avatar' => $t['avatar_url'] ?: '', 'rating' => (int)($t['rating'] ?? 0), 'audience' => $t['audience'],
], $tmQuotes);
$e = static fn ($v): string => htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');
?>
<style>
    .ts-hero { text-align: center; padding: 56px 16px 24px; }
    .ts-eyebrow { display: inline-flex; align-items: center; gap: 8px; padding: 6px 14px; border-radius: 999px; background: #EEF0FF; color: #4F48E6; font-size: 12.5px; font-weight: 700; }
    .ts-eyebrow i { width: 7px; height: 7px; border-radius: 50%; background: #4F48E6; animation: tsPulse 1.8s ease-in-out infinite; }
    .ts-title { font-size: clamp(30px, 5vw, 50px); font-weight: 800; letter-spacing: -0.03em; color: #000623; margin: 16px auto 12px; max-width: 760px; line-height: 1.1; }
    .ts-title span { color: #4F48E6; }
    .ts-sub { font-size: 16px; line-height: 1.6; color: #64748B; max-width: 620px; margin: 0 auto; }
    .ts-kpis { display: flex; justify-content: center; gap: 12px; flex-wrap: wrap; margin: 26px auto 0; }
    .ts-kpi { background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 16px; padding: 12px 20px; min-width: 140px; }
    .ts-kpi strong { display: block; font-size: 22px; font-weight: 800; color: #000623; }
    .ts-kpi span { font-size: 12.5px; color: #64748B; }
    .ts-filters { display: flex; justify-content: center; gap: 8px; flex-wrap: wrap; margin: 30px auto 26px; padding: 0 16px; }
    .ts-chip { display: inline-flex; align-items: center; gap: 7px; border: 1px solid #E2E8F0; background: #FFFFFF; color: #334155; font-size: 13px; font-weight: 600; padding: 8px 14px; border-radius: 999px; cursor: pointer; font-family: inherit; transition: all .15s ease; }
    .ts-chip svg { width: 14px; height: 14px; }
    .ts-chip:hover { border-color: #C7D2FE; color: #4F48E6; }
    .ts-chip.is-on { background: #4F48E6; border-color: #4F48E6; color: #FFFFFF; }
    .ts-wall { position: relative; display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 18px; height: 760px; overflow: hidden; max-width: 1180px; margin: 0 auto; padding: 0 16px;
        -webkit-mask-image: linear-gradient(to bottom, transparent 0, #000 70px, #000 calc(100% - 70px), transparent 100%); mask-image: linear-gradient(to bottom, transparent 0, #000 70px, #000 calc(100% - 70px), transparent 100%); }
    .ts-col { min-width: 0; overflow: hidden; }
    .ts-track { display: flex; flex-direction: column; gap: 18px; animation: tsUp var(--ts-speed, 38s) linear infinite; }
    .ts-col.is-down .ts-track { animation-name: tsDown; }
    .ts-wall:hover .ts-track { animation-play-state: paused; }
    @keyframes tsUp { from { transform: translateY(0); } to { transform: translateY(calc(-50% - 9px)); } }
    @keyframes tsDown { from { transform: translateY(calc(-50% - 9px)); } to { transform: translateY(0); } }
    @keyframes tsPulse { 0%, 100% { opacity: 1; } 50% { opacity: .35; } }
    .ts-card { background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 20px; padding: 20px; box-shadow: 0 10px 30px -22px rgba(0, 6, 35, .35); transition: transform .2s ease, box-shadow .2s ease; }
    .ts-card:hover { transform: translateY(-3px); box-shadow: 0 18px 40px -22px rgba(79, 72, 230, .45); }
    .ts-card-top { display: flex; align-items: center; justify-content: space-between; gap: 10px; }
    .ts-who { display: flex; align-items: center; gap: 10px; min-width: 0; }
    .ts-avatar { width: 42px; height: 42px; border-radius: 50%; object-fit: cover; flex-shrink: 0; background: #EEF0FF; color: #4F48E6; font-weight: 800; display: flex; align-items: center; justify-content: center; }
    .ts-name { font-size: 14.5px; font-weight: 800; color: #000623; }
    .ts-handle { font-size: 12.5px; color: #64748B; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .ts-badge { width: 34px; height: 34px; border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .ts-badge.linkedin { background: #F0F7FF; color: #0A66C2; } .ts-badge.x { background: #F8FAFC; color: #0F172A; } .ts-badge.instagram { background: #FFF1F5; color: #E1306C; }
    .ts-badge.facebook { background: #EFF6FF; color: #1877F2; } .ts-badge.tiktok { background: #F8FAFC; color: #000000; } .ts-badge.youtube { background: #FEF2F2; color: #FF0000; }
    .ts-badge.google { background: #F0F7FF; color: #4285F4; } .ts-badge.website { background: #EEF0FF; color: #4F48E6; }
    .ts-quote { font-size: 14.5px; line-height: 1.65; color: #334155; margin: 14px 0 0; }
    .ts-foot { display: flex; align-items: center; justify-content: space-between; gap: 8px; margin-top: 14px; padding-top: 12px; border-top: 1px solid #F1F5F9; font-size: 12.5px; color: #64748B; }
    .ts-stars { color: #F59E0B; letter-spacing: 2px; font-size: 13px; }
    .ts-foot a { color: #4F48E6; font-weight: 700; text-decoration: none; }
    .ts-role { font-size: 12.5px; font-weight: 600; color: #4F48E6; }
    .ts-videos { max-width: 1180px; margin: 64px auto 0; padding: 0 16px; }
    .ts-videos h2, .ts-cta h2 { font-size: clamp(24px, 3.4vw, 34px); font-weight: 800; color: #000623; letter-spacing: -0.02em; margin: 0 0 6px; text-align: center; }
    .ts-videos p.ts-lead { text-align: center; color: #64748B; margin: 0 0 24px; }
    .ts-vgrid { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 16px; }
    .ts-vcard { position: relative; border-radius: 22px; overflow: hidden; aspect-ratio: 9 / 16; background: #000623; cursor: pointer; box-shadow: 0 18px 40px -24px rgba(0, 6, 35, .6); animation: tsFloat 6s ease-in-out infinite; }
    .ts-vcard:nth-child(2n) { animation-delay: -3s; }
    @keyframes tsFloat { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-10px); } }
    .ts-vcard video { width: 100%; height: 100%; object-fit: cover; display: block; }
    .ts-vover { position: absolute; inset: auto 0 0 0; padding: 16px; background: linear-gradient(to top, rgba(0, 6, 35, .85), transparent); color: #FFFFFF; display: flex; align-items: center; gap: 10px; }
    .ts-vover img { width: 34px; height: 34px; border-radius: 50%; object-fit: cover; border: 2px solid #FFFFFF; }
    .ts-vover strong { display: block; font-size: 14px; }
    .ts-vover span { font-size: 12px; color: #C7D2FE; }
    .ts-play { position: absolute; top: 14px; right: 14px; width: 36px; height: 36px; border-radius: 50%; background: rgba(255, 255, 255, .92); color: #4F48E6; display: flex; align-items: center; justify-content: center; }
    .ts-cta { max-width: 980px; margin: 72px auto 64px; padding: 0 16px; }
    .ts-cta-box { border-radius: 26px; padding: 36px 24px; text-align: center; background: linear-gradient(135deg, #4F48E6, #000623); color: #FFFFFF; }
    .ts-cta-box h2 { color: #FFFFFF; }
    .ts-cta-box p { color: #C7D2FE; margin: 0 auto 20px; max-width: 520px; line-height: 1.6; }
    .ts-cta-row { display: flex; gap: 10px; justify-content: center; flex-wrap: wrap; }
    .ts-btn { display: inline-flex; align-items: center; height: 46px; padding: 0 22px; border-radius: 14px; font-weight: 700; font-size: 14.5px; text-decoration: none; }
    .ts-btn.light { background: #FFFFFF; color: #4F48E6; }
    .ts-btn.ghost { border: 1px solid rgba(255, 255, 255, .4); color: #FFFFFF; }
    .ts-empty { text-align: center; color: #64748B; padding: 40px 0; }
    @media (max-width: 1024px) { .ts-wall { grid-template-columns: repeat(2, minmax(0, 1fr)); } .ts-col:nth-child(3) { display: none; } }
    @media (max-width: 640px) { .ts-wall { grid-template-columns: minmax(0, 1fr); height: 620px; } .ts-col:nth-child(2) { display: none; } .ts-vgrid { grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 10px; } }
    @media (prefers-reduced-motion: reduce) {
        .ts-track, .ts-vcard, .ts-eyebrow i { animation: none !important; }
        .ts-wall { height: auto; -webkit-mask-image: none; mask-image: none; }
        .ts-track .ts-copy { display: none; }
    }
</style>

<section class="ic-page-shell" data-no-reveal x-data="{
    filter: 'all',
    items: <?= $e(json_encode($tmData, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)) ?>,
    get shown() {
        const f = this.filter;
        if (f === 'all') return this.items;
        if (f === 'student' || f === 'employer') return this.items.filter(t => t.audience === f);
        return this.items.filter(t => t.platform === f);
    },
    // Short columns are repeated so the floating loop never shows gaps.
    column(n) {
        const base = this.shown.length < 3 ? this.shown : this.shown.filter((_, i) => i % 3 === n);
        if (!base.length) return [];
        let out = base.slice();
        while (out.length < 4) out = out.concat(base);
        return out.map((t, i) => Object.assign({}, t, { _k: t.id + '-' + i }));
    },
    play(ev) { const v = ev.currentTarget.querySelector('video'); if (!v) return; if (v.paused) { v.play().catch(() => {}); } else { v.pause(); } }
}">
    <header class="ts-hero">
        <span class="ts-eyebrow"><i></i>Success stories</span>
        <h1 class="ts-title">Real people. <span>Real internships.</span></h1>
        <p class="ts-sub">Students, graduates and employers around the world share how InternConnect helped them find, prepare for and hire for internships.</p>
        <div class="ts-kpis">
            <div class="ts-kpi"><strong><?= count($tmAll) ?></strong><span>stories shared</span></div>
            <div class="ts-kpi"><strong><?= number_format($tmAvg, 1) ?> / 5</strong><span>average rating</span></div>
            <div class="ts-kpi"><strong><?= count($tmPlatforms) ?></strong><span>places people talk about us</span></div>
        </div>
    </header>

    <div class="ts-filters no-scrollbar" role="tablist" aria-label="Filter stories">
        <button type="button" class="ts-chip" :class="{ 'is-on': filter === 'all' }" @click="filter = 'all'" role="tab" :aria-selected="filter === 'all'">All stories</button>
        <button type="button" class="ts-chip" :class="{ 'is-on': filter === 'student' }" @click="filter = 'student'" role="tab" :aria-selected="filter === 'student'">Students</button>
        <button type="button" class="ts-chip" :class="{ 'is-on': filter === 'employer' }" @click="filter = 'employer'" role="tab" :aria-selected="filter === 'employer'">Employers</button>
        <?php foreach ($tmPlatforms as $p): ?>
            <button type="button" class="ts-chip" :class="{ 'is-on': filter === '<?= $e($p) ?>' }" @click="filter = '<?= $e($p) ?>'" role="tab" :aria-selected="filter === '<?= $e($p) ?>'"><?= TestimonialService::badge($p, 14) ?><?= $e(TestimonialService::PLATFORMS[$p] ?? $p) ?></button>
        <?php endforeach; ?>
    </div>

    <!-- Floating wall: three columns moving up and down, duplicated for a seamless loop -->
    <div class="ts-wall" x-show="shown.length">
        <template x-for="n in [0, 1, 2]" :key="n">
            <div class="ts-col" :class="{ 'is-down': n === 1 }" :style="'--ts-speed:' + (34 + n * 8) + 's'">
                <div class="ts-track">
                    <template x-for="copy in [false, true]" :key="String(copy)">
                        <div style="display: contents;" :class="{ 'ts-copy': copy }">
                            <template x-for="t in column(n)" :key="t._k + '-' + copy">
                                <article class="ts-card" :aria-hidden="copy ? 'true' : null" :class="{ 'ts-copy': copy }">
                                    <div class="ts-card-top">
                                        <div class="ts-who">
                                            <template x-if="t.avatar"><img class="ts-avatar" :src="t.avatar" :alt="copy ? '' : t.name" loading="lazy" width="42" height="42"></template>
                                            <template x-if="!t.avatar"><span class="ts-avatar" x-text="t.name.charAt(0)"></span></template>
                                            <div style="min-width: 0;"><div class="ts-name" x-text="t.name"></div><div class="ts-handle" x-text="t.handle || t.role"></div></div>
                                        </div>
                                        <span class="ts-badge" :class="t.platform" x-html="t.badge" :aria-label="t.platformLabel"></span>
                                    </div>
                                    <p class="ts-quote" x-text="'“' + t.quote + '”'"></p>
                                    <div class="ts-foot">
                                        <span class="ts-role" x-text="t.handle ? t.role : ''"></span>
                                        <span x-show="t.rating" class="ts-stars" x-text="'★'.repeat(t.rating)" :aria-label="t.rating + ' out of 5'"></span>
                                        <a x-show="t.source" :href="t.source" target="_blank" rel="noopener nofollow" x-text="'View on ' + t.platformLabel"></a>
                                    </div>
                                </article>
                            </template>
                        </div>
                    </template>
                </div>
            </div>
        </template>
    </div>
    <p class="ts-empty" x-show="!shown.length" x-cloak>No stories here yet.</p>

    <?php if ($tmVideos): ?>
    <section class="ts-videos" aria-label="Video stories">
        <h2>Hear it from them</h2>
        <p class="ts-lead">Short video stories. Tap a story to play it.</p>
        <div class="ts-vgrid">
            <?php foreach ($tmVideos as $v): ?>
                <div class="ts-vcard" @click="play($event)" role="button" tabindex="0" @keydown.enter="play($event)" aria-label="Play the story from <?= $e($v['name']) ?>">
                    <video src="<?= $e($v['video_url']) ?>#t=0.5" muted playsinline loop preload="metadata" disablePictureInPicture controlsList="nodownload noremoteplayback"></video>
                    <span class="ts-play" aria-hidden="true"><svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M8 5v14l11-7z"/></svg></span>
                    <div class="ts-vover">
                        <?php if ($v['avatar_url']): ?><img src="<?= $e($v['avatar_url']) ?>" alt="" width="34" height="34"><?php endif; ?>
                        <div style="min-width: 0;"><strong><?= $e($v['name']) ?></strong><span><?= $e($v['role'] ?? '') ?></span></div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endif; ?>

    <section class="ts-cta">
        <div class="ts-cta-box">
            <h2>Your story could be next</h2>
            <p>Find an internship that fits you, or tell us how InternConnect helped you. We would love to feature it here.</p>
            <div class="ts-cta-row">
                <a class="ts-btn light" href="<?= url('/internships') ?>">Find internships</a>
                <a class="ts-btn ghost" href="<?= url('/contact') ?>">Share your story</a>
            </div>
        </div>
    </section>
</section>
