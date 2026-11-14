<?php
declare(strict_types=1);
/**
 * Admin > Testimonials (docs/61-testimonials.md)
 * Quotes (social-post cards) and video stories for the home page section and /testimonials.
 *
 * @var array      $rows     filtered list
 * @var array      $all
 * @var string     $filter   all | home | page | video | hidden
 * @var array|null $editing  [] = new, row = edit, null = closed
 * @var array|null $flash
 */
use App\Services\TestimonialService;

$e = static fn ($v): string => htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');
$counts = [
    'all'    => count($all),
    'home'   => count(array_filter($all, static fn ($t) => $t['show_home'] && $t['is_active'])),
    'page'   => count(array_filter($all, static fn ($t) => $t['show_page'] && $t['is_active'])),
    'video'  => count(array_filter($all, static fn ($t) => $t['kind'] === 'video')),
    'hidden' => count(array_filter($all, static fn ($t) => !$t['is_active'])),
];
$filters = ['all' => 'All', 'home' => 'On home page', 'page' => 'On testimonials page', 'video' => 'Videos', 'hidden' => 'Hidden'];
$f = $editing ?? [];
$isNew = $editing !== null && empty($f['id']);
$cfg = [
    'kind'      => (string)($f['kind'] ?? 'quote'),
    'name'      => (string)($f['name'] ?? ''),
    'handle'    => (string)($f['handle'] ?? ''),
    'role'      => (string)($f['role'] ?? ''),
    'quote'     => (string)($f['quote'] ?? ''),
    'platform'  => (string)($f['platform'] ?? 'linkedin'),
    'avatar'    => (string)($f['avatar_url'] ?? ''),
    'video'     => (string)($f['video_url'] ?? ''),
    'rating'    => (int)($f['rating'] ?? 5),
    'badges'    => array_map(static fn ($p) => TestimonialService::badge($p, 15), array_combine(array_keys(TestimonialService::PLATFORMS), array_keys(TestimonialService::PLATFORMS))),
    'labels'    => TestimonialService::PLATFORMS,
    'uploadUrl' => url('/admin/testimonials/upload'),
];
$toggle = static function (array $t, string $field, string $label) use ($e, $filter): string {
    $on = (int)$t[$field] === 1;
    return '<form method="POST" action="' . url('/admin/testimonials/' . (int)$t['id'] . '/toggle') . '" style="margin:0;">'
        . '<input type="hidden" name="field" value="' . $field . '"><input type="hidden" name="show" value="' . $e($filter) . '">'
        . '<button type="submit" class="tm-switch ' . ($on ? 'is-on' : '') . '" role="switch" aria-checked="' . ($on ? 'true' : 'false') . '" aria-label="' . $e($label) . '"><span></span></button></form>';
};
?>
<style>
    .tm-card { background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 16px; padding: 20px; margin-bottom: 20px; min-width: 0; }
    .tm-card h2 { font-size: 15px; font-weight: 700; color: #000623; margin: 0 0 4px; }
    .tm-sub { font-size: 12.5px; color: #64748B; margin: 0 0 14px; line-height: 1.5; }
    .tm-tabs { display: flex; gap: 4px; padding: 4px; background: #F1F5F9; border-radius: 12px; overflow-x: auto; margin-bottom: 18px; }
    .tm-tab { flex: 0 0 auto; display: inline-flex; align-items: center; gap: 7px; padding: 8px 14px; border-radius: 10px; font-size: 13px; font-weight: 600; color: #64748B; text-decoration: none; white-space: nowrap; }
    .tm-tab.is-on { background: #FFFFFF; color: #4F48E6; box-shadow: 0 1px 3px rgba(15, 23, 42, .1); }
    .tm-tab em { font-style: normal; font-size: 11px; font-weight: 800; background: #EEF0FF; color: #4F48E6; padding: 1px 7px; border-radius: 999px; }
    .tm-wrap { width: 100%; overflow-x: auto; }
    .tm-table { width: 100%; min-width: 860px; border-collapse: collapse; font-size: 13px; }
    .tm-table th { text-align: left; font-size: 11px; font-weight: 700; letter-spacing: .04em; text-transform: uppercase; color: #64748B; padding: 8px 10px; border-bottom: 1px solid #E2E8F0; white-space: nowrap; }
    .tm-table td { padding: 12px 10px; border-bottom: 1px solid #F1F5F9; vertical-align: middle; color: #0F172A; }
    .tm-person { display: flex; align-items: center; gap: 10px; min-width: 0; }
    .tm-avatar { width: 40px; height: 40px; border-radius: 50%; object-fit: cover; background: #EEF0FF; color: #4F48E6; font-weight: 800; display: flex; align-items: center; justify-content: center; flex-shrink: 0; overflow: hidden; }
    .tm-person small, .tm-table small { display: block; font-size: 11.5px; color: #94A3B8; }
    .tm-quote { max-width: 320px; color: #475569; font-size: 12.5px; line-height: 1.5; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
    .tm-plat { display: inline-flex; align-items: center; gap: 6px; padding: 3px 9px; border-radius: 999px; background: #F8FAFC; border: 1px solid #E2E8F0; font-size: 12px; font-weight: 600; white-space: nowrap; }
    .tm-kind { font-size: 11px; font-weight: 800; padding: 2px 8px; border-radius: 999px; background: #EEF0FF; color: #4F48E6; }
    .tm-kind.video { background: #000623; color: #FFFFFF; }
    .tm-switch { width: 38px; height: 22px; border-radius: 999px; border: 0; background: #CBD5E1; position: relative; cursor: pointer; padding: 0; transition: background .15s ease; }
    .tm-switch span { position: absolute; top: 3px; left: 3px; width: 16px; height: 16px; border-radius: 50%; background: #FFFFFF; transition: transform .15s ease; }
    .tm-switch.is-on { background: #4F48E6; }
    .tm-switch.is-on span { transform: translateX(16px); }
    .tm-btn { display: inline-flex; align-items: center; justify-content: center; gap: 6px; height: 38px; padding: 0 16px; border-radius: 10px; border: 0; background: #4F48E6; color: #FFFFFF; font-size: 13px; font-weight: 700; cursor: pointer; text-decoration: none; white-space: nowrap; font-family: inherit; }
    .tm-btn:hover { background: #4338CA; }
    .tm-btn-ghost { background: #FFFFFF; color: #0F172A; border: 1px solid #CBD5E1; }
    .tm-btn-ghost:hover { background: #F8FAFC; }
    .tm-btn-sm { height: 30px; padding: 0 11px; font-size: 12px; }
    .tm-btn-danger { background: #B91C1C; }
    .tm-input { width: 100%; height: 40px; border: 1px solid #CBD5E1; border-radius: 10px; padding: 0 12px; font-size: 13.5px; color: #0F172A; background: #FFFFFF; box-sizing: border-box; font-family: inherit; }
    textarea.tm-input { height: auto; min-height: 110px; padding: 10px 12px; resize: vertical; line-height: 1.55; }
    .tm-input:focus { outline: none; border-color: #4F48E6; box-shadow: 0 0 0 3px rgba(79, 72, 230, .15); }
    .tm-field { margin-bottom: 14px; min-width: 0; }
    .tm-field label { display: block; font-size: 12px; font-weight: 600; color: #334155; margin-bottom: 6px; }
    .tm-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 12px; }
    .tm-form { display: grid; grid-template-columns: minmax(0, 1.4fr) minmax(0, 1fr); gap: 20px; align-items: start; }
    .tm-seg { display: inline-flex; padding: 3px; background: #F1F5F9; border-radius: 10px; gap: 3px; margin-bottom: 14px; }
    .tm-seg button { border: 0; background: transparent; padding: 7px 16px; border-radius: 8px; font-size: 13px; font-weight: 700; color: #64748B; cursor: pointer; font-family: inherit; }
    .tm-seg button.is-on { background: #FFFFFF; color: #4F48E6; box-shadow: 0 1px 2px rgba(15, 23, 42, .1); }
    .tm-upload { display: inline-flex; align-items: center; gap: 6px; height: 36px; padding: 0 14px; border-radius: 10px; border: 1px dashed #A5B4FC; background: #F5F6FF; color: #4F48E6; font-size: 12.5px; font-weight: 700; cursor: pointer; white-space: nowrap; }
    .tm-field label.tm-upload { display: inline-flex; margin: 0; font-size: 12.5px; font-weight: 700; color: #4F48E6; }
    .tm-preview { border: 1px solid #E2E8F0; border-radius: 18px; padding: 18px; background: #FFFFFF; box-shadow: 0 10px 30px -22px rgba(0, 6, 35, .35); }
    .tm-preview-video { border-radius: 18px; overflow: hidden; background: #000623; aspect-ratio: 9 / 16; max-width: 240px; position: relative; }
    .tm-preview-video video { width: 100%; height: 100%; object-fit: cover; display: block; }
    .tm-flash { border-radius: 12px; padding: 12px 14px; font-size: 13px; font-weight: 600; margin-bottom: 16px; }
    .tm-flash.ok { background: #ECFDF5; color: #047857; }
    .tm-flash.err { background: #FEF2F2; color: #B91C1C; }
    @media (max-width: 1100px) { .tm-form { grid-template-columns: minmax(0, 1fr); } }
</style>

<div class="admin-shell">
    <?php include dirname(__DIR__, 2) . '/shared/components/admin-sidebar.php'; ?>
    <div class="admin-main-wrap">
        <?php include dirname(__DIR__, 2) . '/shared/components/admin-topnav.php'; ?>
        <main class="admin-content-body">
            <div class="admin-page-header mb-6" style="display: flex; justify-content: space-between; align-items: flex-start; gap: 12px; flex-wrap: wrap;">
                <div style="min-width: 0;">
                    <h1 class="admin-page-title">Testimonials</h1>
                    <p class="admin-page-subtitle">Quotes and video stories on the home page and the Success Stories page. Changes show on the site straight away.</p>
                </div>
                <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                    <a class="tm-btn tm-btn-ghost" href="<?= url('/testimonials') ?>" target="_blank" rel="noopener">View page</a>
                    <a class="tm-btn" href="<?= url('/admin/testimonials?edit=0#tm-form') ?>">Add testimonial</a>
                </div>
            </div>

            <?php if ($flash): ?><div class="tm-flash <?= $flash['ok'] ? 'ok' : 'err' ?>" role="status"><?= $e($flash['text']) ?></div><?php endif; ?>

            <?php if ($editing !== null): ?>
                <script type="application/json" id="tm-data"><?= json_encode($cfg, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?></script>
                <form id="tm-form" class="tm-card" method="POST" action="<?= url('/admin/testimonials' . ($isNew ? '' : '/' . (int)$f['id'])) ?>"
                      x-data="Object.assign(JSON.parse(document.getElementById('tm-data').textContent), { uploading: '', err: '',
                          async upload(ev, kind) {
                              const file = ev.target.files && ev.target.files[0]; ev.target.value = '';
                              if (!file) return;
                              this.uploading = kind; this.err = '';
                              const fd = new FormData(); fd.append('file', file); fd.append('kind', kind);
                              try {
                                  const r = await fetch(this.uploadUrl, { method: 'POST', headers: { 'Accept': 'application/json' }, body: fd });
                                  const d = await r.json();
                                  if (!d.success) throw new Error(d.error || 'Upload failed');
                                  if (kind === 'video') this.video = d.url; else this.avatar = d.url;
                              } catch (e) { this.err = e.message; }
                              this.uploading = '';
                          } })">
                    <div style="display: flex; justify-content: space-between; align-items: center; gap: 10px; flex-wrap: wrap; margin-bottom: 12px;">
                        <h2><?= $isNew ? 'Add a testimonial' : 'Edit ' . $e($f['name'] ?? '') ?></h2>
                        <a class="tm-btn tm-btn-ghost tm-btn-sm" href="<?= url('/admin/testimonials') ?>">Close</a>
                    </div>
                    <div class="tm-form">
                        <div style="min-width: 0;">
                            <input type="hidden" name="kind" :value="kind">
                            <div class="tm-seg" role="tablist" aria-label="Type">
                                <button type="button" :class="{ 'is-on': kind === 'quote' }" @click="kind = 'quote'">Quote</button>
                                <button type="button" :class="{ 'is-on': kind === 'video' }" @click="kind = 'video'">Video story</button>
                            </div>
                            <div class="tm-grid">
                                <div class="tm-field"><label for="tm-name">Name</label><input id="tm-name" class="tm-input" name="name" x-model="name" maxlength="120" required></div>
                                <div class="tm-field"><label for="tm-handle">Handle or school</label><input id="tm-handle" class="tm-input" name="handle" x-model="handle" maxlength="120" placeholder="@name or University"></div>
                            </div>
                            <div class="tm-grid">
                                <div class="tm-field"><label for="tm-role">Role and company</label><input id="tm-role" class="tm-input" name="role" x-model="role" maxlength="160" placeholder="Product Design Intern at Airbnb"></div>
                                <div class="tm-field"><label for="tm-aud">This person is a</label>
                                    <select data-ic-select id="tm-aud" name="audience" class="tm-input">
                                        <?php foreach (TestimonialService::AUDIENCES as $k => $label): ?><option value="<?= $k ?>" <?= ($f['audience'] ?? 'student') === $k ? 'selected' : '' ?>><?= $e($label) ?></option><?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="tm-grid">
                                <div class="tm-field"><label for="tm-plat">Posted on</label>
                                    <select data-ic-select id="tm-plat" name="platform" class="tm-input" x-model="platform">
                                        <?php foreach (TestimonialService::PLATFORMS as $k => $label): ?><option value="<?= $k ?>"><?= $e($label) ?></option><?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="tm-field"><label for="tm-src">Link to the original post (optional)</label><input id="tm-src" class="tm-input" name="source_url" maxlength="500" value="<?= $e($f['source_url'] ?? '') ?>" placeholder="https://www.linkedin.com/posts/..."></div>
                            </div>
                            <div class="tm-field" x-show="kind === 'quote'"><label for="tm-quote">What they said</label><textarea id="tm-quote" class="tm-input" name="quote" x-model="quote" maxlength="1200"></textarea></div>
                            <div class="tm-grid">
                                <div class="tm-field"><label for="tm-rating">Rating</label>
                                    <select data-ic-select id="tm-rating" name="rating" class="tm-input" x-model.number="rating">
                                        <option value="0">No rating</option><?php for ($i = 5; $i >= 1; $i--): ?><option value="<?= $i ?>"><?= $i ?> stars</option><?php endfor; ?>
                                    </select>
                                </div>
                                <div class="tm-field"><label for="tm-sort">Order (lower shows first)</label><input id="tm-sort" class="tm-input" name="sort" inputmode="numeric" value="<?= (int)($f['sort'] ?? (count($all) + 1) * 10) ?>"></div>
                            </div>
                            <div class="tm-field">
                                <label>Photo</label>
                                <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
                                    <template x-if="avatar"><img :src="avatar" alt="" width="48" height="48" style="width: 48px; height: 48px; border-radius: 50%; object-fit: cover;"></template>
                                    <label class="tm-upload"><input type="file" accept="image/png,image/jpeg,image/webp,image/gif" style="display: none;" @change="upload($event, 'image')"><span x-text="uploading === 'image' ? 'Uploading...' : 'Upload photo'"></span></label>
                                    <input class="tm-input" style="flex: 1 1 220px;" name="avatar_url" x-model="avatar" maxlength="500" placeholder="or paste an https:// image link" aria-label="Photo link">
                                </div>
                            </div>
                            <div class="tm-field" x-show="kind === 'video'">
                                <label>Video (MP4, MOV or WebM, up to 40 MB; shown muted)</label>
                                <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
                                    <label class="tm-upload"><input type="file" accept="video/mp4,video/quicktime,video/webm" style="display: none;" @change="upload($event, 'video')"><span x-text="uploading === 'video' ? 'Uploading video...' : 'Upload video'"></span></label>
                                    <input class="tm-input" style="flex: 1 1 220px;" name="video_url" x-model="video" maxlength="500" placeholder="or paste an https:// video link" aria-label="Video link">
                                </div>
                            </div>
                            <p x-show="err" x-text="err" style="color: #B91C1C; font-size: 12.5px; margin: 0 0 10px;" x-cloak></p>
                            <div style="display: flex; gap: 18px; flex-wrap: wrap; margin: 4px 0 16px; font-size: 13px; color: #334155;">
                                <label style="display: inline-flex; gap: 8px; align-items: center; cursor: pointer;"><input type="checkbox" name="show_home" value="1" <?= !empty($f['show_home']) || $isNew ? 'checked' : '' ?> style="width: 16px; height: 16px;"> Show on home page</label>
                                <label style="display: inline-flex; gap: 8px; align-items: center; cursor: pointer;"><input type="checkbox" name="show_page" value="1" <?= !empty($f['show_page']) || $isNew ? 'checked' : '' ?> style="width: 16px; height: 16px;"> Show on testimonials page</label>
                                <label style="display: inline-flex; gap: 8px; align-items: center; cursor: pointer;"><input type="checkbox" name="is_active" value="1" <?= !empty($f['is_active']) || $isNew ? 'checked' : '' ?> style="width: 16px; height: 16px;"> Visible</label>
                            </div>
                            <button type="submit" class="tm-btn"><?= $isNew ? 'Add testimonial' : 'Save changes' ?></button>
                        </div>
                        <div style="min-width: 0;">
                            <div style="font-size: 12px; font-weight: 800; letter-spacing: .05em; text-transform: uppercase; color: #94A3B8; margin-bottom: 10px;">Preview</div>
                            <div class="tm-preview" x-show="kind === 'quote'">
                                <div style="display: flex; align-items: center; justify-content: space-between; gap: 10px;">
                                    <div style="display: flex; align-items: center; gap: 10px; min-width: 0;">
                                        <template x-if="avatar"><img :src="avatar" alt="" class="tm-avatar" width="40" height="40"></template>
                                        <template x-if="!avatar"><span class="tm-avatar" x-text="(name || '?').charAt(0)"></span></template>
                                        <div style="min-width: 0;"><div style="font-weight: 800; color: #000623;" x-text="name || 'Name'"></div><div style="font-size: 12.5px; color: #64748B;" x-text="handle || role || '@handle'"></div></div>
                                    </div>
                                    <span class="tm-plat" x-html="badges[platform] + '<span>' + labels[platform] + '</span>'"></span>
                                </div>
                                <p style="font-size: 14px; line-height: 1.6; color: #334155; margin: 12px 0 0;" x-text="quote ? '“' + quote + '”' : 'What they said appears here.'"></p>
                                <div x-show="rating" style="color: #F59E0B; letter-spacing: 2px; margin-top: 10px;" x-text="'★'.repeat(rating || 0)"></div>
                            </div>
                            <div class="tm-preview-video" x-show="kind === 'video'">
                                <template x-if="video"><video :src="video" muted playsinline loop autoplay></video></template>
                                <div style="position: absolute; left: 0; right: 0; bottom: 0; padding: 12px; background: linear-gradient(to top, rgba(0,6,35,.85), transparent); color: #FFFFFF;"><strong style="display: block; font-size: 13px;" x-text="name || 'Name'"></strong><span style="font-size: 11.5px; color: #C7D2FE;" x-text="role"></span></div>
                            </div>
                        </div>
                    </div>
                </form>
            <?php endif; ?>

            <nav class="tm-tabs no-scrollbar" aria-label="Filter testimonials">
                <?php foreach ($filters as $k => $label): ?>
                    <a class="tm-tab <?= $filter === $k ? 'is-on' : '' ?>" href="<?= url('/admin/testimonials' . ($k === 'all' ? '' : '?show=' . $k)) ?>"><?= $e($label) ?><em><?= (int)$counts[$k] ?></em></a>
                <?php endforeach; ?>
            </nav>

            <div class="tm-card">
                <?php if (!$rows): ?>
                    <p class="tm-sub" style="text-align: center; padding: 24px 0; margin: 0;">Nothing here yet.</p>
                <?php else: ?>
                    <div class="tm-wrap no-scrollbar">
                        <table class="tm-table">
                            <thead><tr><th>Person</th><th>Type</th><th>Posted on</th><th>Testimonial</th><th>Home</th><th>Page</th><th>Visible</th><th>Order</th><th></th></tr></thead>
                            <tbody>
                            <?php foreach ($rows as $t): ?>
                                <tr x-data="{ sure: false }">
                                    <td><div class="tm-person"><?php if ($t['avatar_url']): ?><img class="tm-avatar" src="<?= $e($t['avatar_url']) ?>" alt="" width="40" height="40" loading="lazy"><?php else: ?><span class="tm-avatar"><?= $e(mb_substr((string)$t['name'], 0, 1)) ?></span><?php endif; ?><div style="min-width: 0;"><strong><?= $e($t['name']) ?></strong><small><?= $e($t['role'] ?: $t['handle']) ?></small></div></div></td>
                                    <td><span class="tm-kind <?= $t['kind'] === 'video' ? 'video' : '' ?>"><?= $t['kind'] === 'video' ? 'Video' : 'Quote' ?></span><small><?= $e(TestimonialService::AUDIENCES[$t['audience']] ?? '') ?></small></td>
                                    <td><span class="tm-plat"><?= TestimonialService::badge((string)$t['platform'], 14) ?><?= $e(TestimonialService::PLATFORMS[$t['platform']] ?? $t['platform']) ?></span></td>
                                    <td><div class="tm-quote"><?= $t['kind'] === 'video' ? '<a href="' . $e($t['video_url']) . '" target="_blank" rel="noopener" style="color:#4F48E6;font-weight:700;">Open video</a>' : $e($t['quote']) ?></div></td>
                                    <td><?= $toggle($t, 'show_home', 'Show on home page') ?></td>
                                    <td><?= $toggle($t, 'show_page', 'Show on testimonials page') ?></td>
                                    <td><?= $toggle($t, 'is_active', 'Visible') ?></td>
                                    <td><?= (int)$t['sort'] ?></td>
                                    <td style="text-align: right; white-space: nowrap;">
                                        <a class="tm-btn tm-btn-ghost tm-btn-sm" href="<?= url('/admin/testimonials?edit=' . (int)$t['id'] . '#tm-form') ?>" x-show="!sure">Edit</a>
                                        <button type="button" class="tm-btn tm-btn-ghost tm-btn-sm" x-show="!sure" @click="sure = true">Delete</button>
                                        <form method="POST" action="<?= url('/admin/testimonials/' . (int)$t['id'] . '/delete') ?>" x-show="sure" x-cloak style="display: inline-flex; gap: 6px; margin: 0;">
                                            <button type="submit" class="tm-btn tm-btn-danger tm-btn-sm">Delete</button>
                                            <button type="button" class="tm-btn tm-btn-ghost tm-btn-sm" @click="sure = false">Cancel</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </main>
    </div>
</div>
