<?php
declare(strict_types=1);
/**
 * View: Candidate Saved Internships
 * Route: /saved, /candidate/saved
 * Layout: dashboard (candidate sidebar + topnav)
 *
 * @var array  $candidate        Candidate profile data
 * @var array  $savedInternships Saved internships list
 */

$name      = $candidate['name'] ?? 'Sarah Peters';
$firstName = explode(' ', trim($name))[0];
$role      = $candidate['role'] ?? 'Student Applicant';
$avatar    = $candidate['avatar'] ?? null;
$savedCount = count($savedInternships);
?>

<div class="db-shell">

    <!-- Sidebar Overlay (mobile) -->
    <div class="db-sidebar-overlay" id="db-sidebar-overlay"></div>

    <?php 
    $activeNav = 'saved';
    include dirname(__DIR__, 2) . '/shared/components/candidate-sidebar.php'; 
    ?>

    <!-- ══ MAIN ══ -->
    <div class="db-main">

        <!-- ══ TOP NAVBAR ══ -->
        <?php include dirname(__DIR__, 2) . '/shared/components/candidate-topnav.php'; ?>

        <!-- Scrollable Content -->
        <div class="db-content" id="db-content">
            <style>
            /* ─── Saved Internships Design System (Homepage Featured Standard) ─── */
            .db-saved-header {
                display: flex;
                align-items: center;
                justify-content: space-between;
                margin-bottom: 24px;
                gap: 16px;
                flex-wrap: wrap;
            }
            .db-saved-header-left h1 {
                font-size: 28px;
                font-weight: 800;
                color: #000623;
                margin: 0 0 6px 0;
                letter-spacing: -0.02em;
            }
            .db-saved-header-left p {
                font-size: 14.5px;
                color: #64748B;
                margin: 0;
                line-height: 1.5;
            }
            .db-saved-badge {
                font-size: 11px;
                font-weight: 800;
                letter-spacing: 0.05em;
                padding: 6px 14px;
                background: #EEF2FF;
                color: #4F48E6;
                border-radius: 99px;
                white-space: nowrap !important;
                flex-shrink: 0 !important;
                border: 1px solid #C7D2FE;
            }

            /* ─── Opportunities Grid ─── */
            .db-saved-cards-grid {
                display: grid;
                grid-template-columns: minmax(0, 1fr) !important;
                gap: 22px;
                width: 100% !important;
                max-width: 100% !important;
                min-width: 0 !important;
                box-sizing: border-box !important;
                margin-bottom: 40px;
            }
            @media (min-width: 680px) {
                .db-saved-cards-grid {
                    grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
                }
            }
            @media (min-width: 1280px) {
                .db-saved-cards-grid {
                    grid-template-columns: repeat(3, minmax(0, 1fr)) !important;
                }
            }

            /* ─── Opportunity Card (Homepage Standard) ─── */
            .db-opp-card {
                background: #FFFFFF;
                border: 1px solid #E2E8F0;
                border-radius: 20px;
                padding: 24px;
                box-shadow: 0 4px 20px -2px rgba(0, 6, 35, 0.05), 0 2px 6px -1px rgba(0, 6, 35, 0.02);
                display: flex;
                flex-direction: column;
                justify-content: space-between;
                transition: transform 0.25s cubic-bezier(0.4, 0, 0.2, 1), box-shadow 0.25s ease, border-color 0.25s ease, opacity 0.2s ease;
                box-sizing: border-box;
                width: 100%;
                min-width: 0;
            }
            .db-opp-card:hover {
                transform: translateY(-3px);
                border-color: #CBD5E1;
                box-shadow: 0 16px 36px -4px rgba(0, 6, 35, 0.09), 0 4px 12px -2px rgba(0, 6, 35, 0.03);
            }
            .db-opp-card.is-unsaved {
                opacity: 0.55;
            }

            /* Card Top: Logo on Left + Action Icons on Right (Save & Share) */
            .db-opp-card-top {
                display: flex;
                align-items: flex-start;
                justify-content: space-between;
                gap: 12px;
                margin-bottom: 16px;
            }
            .db-opp-logo-box {
                width: 52px;
                height: 52px;
                border-radius: 14px;
                border: 1px solid #E2E8F0;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 9px;
                box-sizing: border-box;
                box-shadow: 0 2px 8px rgba(0, 6, 35, 0.04);
                flex-shrink: 0;
                transition: transform 0.2s ease;
            }
            .db-opp-card:hover .db-opp-logo-box {
                transform: scale(1.04);
            }
            .db-opp-logo-img {
                width: 32px;
                height: 32px;
                max-width: 100%;
                max-height: 100%;
                object-fit: contain;
                display: block;
            }

            /* Top Right Action Buttons (Share & Save) */
            .db-opp-actions-wrap {
                display: inline-flex;
                align-items: center;
                gap: 6px;
                flex-shrink: 0;
            }
            .db-opp-action-btn {
                width: 34px;
                height: 34px;
                border-radius: 10px;
                border: 1px solid #E2E8F0;
                background: #FFFFFF;
                color: #64748B;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                cursor: pointer;
                transition: all 0.15s ease;
                padding: 0;
            }
            .db-opp-action-btn:hover {
                background: #F8FAFC;
                border-color: #CBD5E1;
                color: #000623;
                transform: translateY(-1px);
            }
            .db-opp-action-btn.btn-saved {
                color: #4F48E6;
                background: #EEF2FF;
                border-color: #C7D2FE;
            }
            .db-opp-action-btn.btn-saved:hover {
                background: #E0E7FF;
                border-color: #A5B4FC;
            }
            .db-opp-action-btn.btn-unsaved {
                color: #94A3B8;
                background: #FFFFFF;
                border-color: #E2E8F0;
            }

            /* Card Role & Company */
            .db-opp-role-title {
                font-size: 18.5px;
                font-weight: 700;
                color: #000623;
                line-height: 1.3;
                margin: 0 0 4px 0;
                transition: color 0.15s ease;
                letter-spacing: -0.01em;
            }
            .db-opp-role-title a {
                color: inherit;
                text-decoration: none;
            }
            .db-opp-card:hover .db-opp-role-title {
                color: #4F48E6;
            }
            .db-opp-role-company {
                font-size: 13.5px;
                font-weight: 500;
                color: #64748B;
                margin: 0 0 14px 0;
                display: flex;
                align-items: center;
                gap: 6px;
                flex-wrap: wrap;
            }
            .db-opp-role-company a {
                color: inherit;
                text-decoration: none;
                font-weight: 600;
                transition: color 0.15s ease;
            }
            .db-opp-role-company a:hover {
                color: #4F48E6;
            }
            .db-opp-desc-text {
                font-size: 13.5px;
                line-height: 1.58;
                color: #475569;
                margin: 0 0 18px 0;
                font-weight: 400;
                display: -webkit-box;
                -webkit-line-clamp: 2;
                -webkit-box-orient: vertical;
                overflow: hidden;
            }

            /* ─── 3-Stat Row (Discipline | Work Mode & Term | Stipend) ─── */
            .db-opp-stats-row {
                display: flex;
                align-items: center;
                justify-content: space-between;
                background: #F8FAFC;
                border: 1px solid #F1F5F9;
                border-radius: 12px;
                padding: 8px 10px;
                margin-bottom: 20px;
                gap: 6px;
                box-sizing: border-box;
                min-width: 0;
                overflow: hidden;
            }
            .db-opp-stat-item {
                display: flex;
                align-items: center;
                gap: 4px;
                font-size: 11px;
                font-weight: 500;
                color: #475569;
                white-space: nowrap !important;
                min-width: 0;
                flex-shrink: 0;
            }
            .db-opp-stat-item.stat-stipend {
                font-weight: 700;
                color: #000623;
            }
            .db-opp-stat-icon {
                width: 13px;
                height: 13px;
                color: #4F48E6;
                flex-shrink: 0;
            }
            .db-opp-stat-divider {
                width: 1px;
                height: 14px;
                background: #E2E8F0;
                flex-shrink: 0;
            }

            /* ─── Full-Width Quick Apply Button ─── */
            .db-opp-card-btn {
                display: flex !important;
                align-items: center !important;
                justify-content: center !important;
                gap: 8px;
                width: 100% !important;
                height: 44px;
                background-color: #EEF2FF;
                border: 1px solid #C7D2FE;
                border-radius: 12px;
                color: #4338CA;
                font-size: 13.5px;
                font-weight: 700;
                text-decoration: none;
                transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
                box-sizing: border-box;
                white-space: nowrap !important;
                flex-shrink: 0 !important;
            }
            .db-opp-card-btn:hover {
                background-color: #4F48E6;
                border-color: #4F48E6;
                color: #FFFFFF;
                transform: translateY(-1px);
                box-shadow: 0 4px 14px rgba(79, 72, 230, 0.28);
            }
            .db-opp-btn-icon {
                width: 15px;
                height: 15px;
                transition: transform 0.2s ease;
                flex-shrink: 0;
            }
            .db-opp-card-btn:hover .db-opp-btn-icon {
                transform: translateX(3px);
            }

            /* ─── Bottom Section: Based on Saved Items ─── */
            .db-saved-bottom-grid {
                display: grid;
                grid-template-columns: minmax(0, 1.8fr) minmax(0, 1fr);
                gap: 20px;
                margin-top: 10px;
                box-sizing: border-box;
            }
            @media (max-width: 900px) {
                .db-saved-bottom-grid {
                    grid-template-columns: minmax(0, 1fr) !important;
                }
            }
            .db-opp-action-btn.is-active {
                color: #4F48E6;
                background: #EEF2FF;
                border-color: #C7D2FE;
            }

            /* Share Menu */
            .db-opp-card.is-share-open {
                position: relative;
                z-index: 30;
                transform: none;
            }
            .db-share {
                position: relative;
            }
            .db-share-menu {
                position: absolute;
                top: calc(100% + 8px);
                right: -44px;
                width: 288px;
                max-width: calc(100vw - 32px);
                background: #FFFFFF;
                border: 1px solid #E2E8F0;
                border-radius: 14px;
                box-shadow: 0 18px 40px -8px rgba(15, 23, 42, 0.18), 0 4px 10px rgba(15, 23, 42, 0.04);
                padding: 14px;
                z-index: 40;
                box-sizing: border-box;
            }
            .db-share-head {
                display: flex;
                flex-direction: column;
                gap: 2px;
                margin-bottom: 12px;
                min-width: 0;
            }
            .db-share-head strong {
                font-size: 13.5px;
                font-weight: 800;
                color: #000623;
            }
            .db-share-head span {
                font-size: 12px;
                color: #64748B;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }
            .db-share-grid {
                display: grid;
                grid-template-columns: repeat(3, minmax(0, 1fr));
                gap: 4px;
            }
            .db-share-app {
                display: flex;
                flex-direction: column;
                align-items: center;
                gap: 6px;
                padding: 10px 4px 8px;
                border-radius: 10px;
                text-decoration: none;
                font-size: 11.5px;
                font-weight: 600;
                color: #334155;
                transition: background 0.15s ease;
                white-space: nowrap;
            }
            .db-share-app:hover {
                background: #F8FAFC;
                color: #000623;
            }
            .db-share-icon {
                width: 38px;
                height: 38px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                transition: transform 0.15s ease;
            }
            .db-share-app:hover .db-share-icon {
                transform: translateY(-1px);
            }
            .db-share-icon svg {
                width: 18px;
                height: 18px;
            }
            .db-share-native {
                display: flex;
                align-items: center;
                gap: 8px;
                width: 100%;
                margin-top: 8px;
                padding: 9px 10px;
                border: 1px solid #E2E8F0;
                border-radius: 10px;
                background: #FFFFFF;
                font-size: 12.5px;
                font-weight: 600;
                color: #334155;
                cursor: pointer;
                text-align: left;
            }
            .db-share-native:hover {
                background: #F8FAFC;
            }
            .db-share-native svg {
                width: 16px;
                height: 16px;
                flex-shrink: 0;
                color: #64748B;
            }
            .db-share-copy {
                display: flex;
                align-items: center;
                gap: 8px;
                margin-top: 12px;
                padding: 5px 5px 5px 12px;
                border: 1px solid #E2E8F0;
                border-radius: 10px;
                background: #F8FAFC;
                transition: border-color 0.15s ease, background 0.15s ease;
            }
            .db-share-url {
                flex: 1;
                min-width: 0;
                font-size: 12px;
                color: #64748B;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }
            .db-share-copy button {
                display: inline-flex;
                align-items: center;
                gap: 5px;
                height: 32px;
                padding: 0 12px;
                border: 0;
                border-radius: 8px;
                background: #4F48E6;
                color: #FFFFFF;
                font-size: 12px;
                font-weight: 700;
                cursor: pointer;
                white-space: nowrap;
                flex-shrink: 0;
                transition: background 0.15s ease;
            }
            .db-share-copy button:hover {
                background: #4338CA;
            }
            .db-share-copy button svg {
                width: 14px;
                height: 14px;
            }
            .db-share-copy.is-copied {
                border-color: #A7F3D0;
                background: #ECFDF5;
            }
            .db-share-copy.is-copied button {
                background: #059669;
            }
            </style>

            <script>
            function savedShareMenu(cfg) {
                const fullUrl = new URL(cfg.path, window.location.origin).href;
                const text = cfg.title + ' at ' + cfg.company + ' on InternConnect';
                const enc = encodeURIComponent;
                return {
                    open: false,
                    copied: false,
                    copyTimer: null,
                    url: fullUrl,
                    displayUrl: fullUrl.replace(/^https?:\/\//, ''),
                    canNativeShare: typeof navigator !== 'undefined' && typeof navigator.share === 'function',
                    links: {
                        whatsapp: 'https://wa.me/?text=' + enc(text + ' ' + fullUrl),
                        linkedin: 'https://www.linkedin.com/sharing/share-offsite/?url=' + enc(fullUrl),
                        x: 'https://twitter.com/intent/tweet?text=' + enc(text) + '&url=' + enc(fullUrl),
                        facebook: 'https://www.facebook.com/sharer/sharer.php?u=' + enc(fullUrl),
                        telegram: 'https://t.me/share/url?url=' + enc(fullUrl) + '&text=' + enc(text),
                        email: 'mailto:?subject=' + enc(text) + '&body=' + enc('Thought you might like this internship:\n\n' + text + '\n' + fullUrl)
                    },

                    toggle() {
                        this.open ? this.close() : this.show();
                    },

                    show() {
                        this.open = true;
                        const card = this.$el.closest('.db-opp-card');
                        if (card) card.classList.add('is-share-open');
                    },

                    close() {
                        if (!this.open) return;
                        this.open = false;
                        const card = this.$el.closest('.db-opp-card');
                        if (card) card.classList.remove('is-share-open');
                    },

                    async nativeShare() {
                        try {
                            await navigator.share({ title: text, text: text, url: this.url });
                            this.close();
                        } catch (e) {}
                    },

                    async copyLink() {
                        let ok = false;
                        try {
                            if (navigator.clipboard && window.isSecureContext) {
                                await navigator.clipboard.writeText(this.url);
                                ok = true;
                            }
                        } catch (e) {}
                        if (!ok) {
                            const ta = document.createElement('textarea');
                            ta.value = this.url;
                            ta.setAttribute('readonly', '');
                            ta.style.position = 'fixed';
                            ta.style.opacity = '0';
                            document.body.appendChild(ta);
                            ta.select();
                            try { ok = document.execCommand('copy'); } catch (e) { ok = false; }
                            document.body.removeChild(ta);
                        }
                        if (!ok) {
                            this.$dispatch('show-toast', { text: 'Could not copy automatically. Please copy the link shown.', type: 'error' });
                            return;
                        }
                        this.copied = true;
                        this.$dispatch('show-toast', { text: 'Internship link copied to clipboard!', type: 'success' });
                        clearTimeout(this.copyTimer);
                        this.copyTimer = setTimeout(() => { this.copied = false; this.close(); }, 1600);
                    }
                };
            }
            </script>
            <!-- ── Header Section ── -->
            <div class="db-saved-header">
                <div class="db-saved-header-left">
                    <h1>Saved Internships</h1>
                    <p>A curated list of roles you're interested in. Review and apply whenever you're ready.</p>
                </div>
                <div class="db-saved-badge"><?= $savedCount ?> SAVED</div>
            </div>

            <!-- ── Saved Internships Grid (Homepage Featured Opportunities Standard) ── -->
            <div class="db-saved-cards-grid">
                <?php foreach ($savedInternships as $index => $internship): ?>
                <div class="db-opp-card" 
                     id="db-saved-card-<?= $index ?>" 
                     x-data="{ isSaved: true }">
                    <div>
                        <!-- Card Top: Company Logo on Left & Actions (Save + Share) on Right -->
                        <div class="db-opp-card-top">
                            <a href="<?= url('/company/' . ($internship['company_slug'] ?? 'google')) ?>" 
                               class="db-opp-logo-box" 
                               style="background-color: <?= htmlspecialchars($internship['logo_bg'] ?? '#FFFFFF') ?>; <?= ($internship['logo_bg'] ?? '') === '#000623' ? 'border-color: #1E293B;' : '' ?>" 
                               aria-label="View <?= htmlspecialchars($internship['company']) ?> Profile">
                                <?php if (!empty($internship['logo'])): ?>
                                    <img src="<?= htmlspecialchars($internship['logo']) ?>" alt="<?= htmlspecialchars($internship['company']) ?>" class="db-opp-logo-img" loading="lazy">
                                <?php elseif (!empty($internship['logo_svg'])): ?>
                                    <?= $internship['logo_svg'] ?>
                                <?php else: ?>
                                    <span style="font-size: 15px; font-weight: 800; color: #4F48E6;"><?= htmlspecialchars(substr($internship['company'], 0, 2)) ?></span>
                                <?php endif; ?>
                            </a>

                            <!-- Actions Row: Share + Saved Bookmark -->
                            <div class="db-opp-actions-wrap">
                                <!-- Share Menu -->
                                <div class="db-share" x-data="savedShareMenu(<?= htmlspecialchars(json_encode([
                                        'path'    => url('/internship/' . ($internship['slug'] ?? 'software-engineer-intern')),
                                        'title'   => $internship['title'],
                                        'company' => $internship['company'],
                                    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE), ENT_QUOTES, 'UTF-8') ?>)" @click.outside="close()" @keydown.escape.window="close()">
                                    <button type="button"
                                            class="db-opp-action-btn"
                                            :class="{ 'is-active': open }"
                                            aria-haspopup="menu"
                                            :aria-expanded="open"
                                            aria-label="Share <?= htmlspecialchars($internship['title']) ?>"
                                            @click="toggle()">
                                        <svg style="width: 15px; height: 15px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                                            <circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/>
                                            <path stroke-linecap="round" d="M8.59 13.51l6.83 3.98M15.41 6.51l-6.82 3.98"/>
                                        </svg>
                                    </button>

                                    <div class="db-share-menu" x-show="open" x-transition.opacity.duration.150ms role="menu" style="display: none;">
                                        <div class="db-share-head">
                                            <strong>Share this role</strong>
                                            <span><?= htmlspecialchars($internship['title']) ?> &middot; <?= htmlspecialchars($internship['company']) ?></span>
                                        </div>

                                        <div class="db-share-grid">
                                            <a class="db-share-app" role="menuitem" :href="links.whatsapp" target="_blank" rel="noopener" @click="close()">
                                                <span class="db-share-icon" style="background: #25D366;">
                                                    <svg viewBox="0 0 24 24" fill="#FFFFFF"><path d="M17.47 14.38c-.3-.15-1.75-.86-2.02-.96-.27-.1-.47-.15-.67.15-.2.3-.77.96-.94 1.16-.17.2-.35.22-.64.07-.3-.15-1.25-.46-2.38-1.47-.88-.78-1.48-1.75-1.65-2.05-.17-.3-.02-.46.13-.6.13-.13.3-.35.45-.52.15-.17.2-.3.3-.5.1-.2.05-.37-.03-.52-.07-.15-.67-1.61-.92-2.2-.24-.58-.49-.5-.67-.51h-.57c-.2 0-.52.07-.79.37-.27.3-1.04 1.02-1.04 2.48s1.07 2.88 1.21 3.08c.15.2 2.1 3.2 5.08 4.49.71.31 1.26.49 1.69.63.71.23 1.36.2 1.87.12.57-.09 1.75-.72 2-1.41.25-.69.25-1.29.17-1.41-.07-.12-.27-.2-.57-.35zM12.05 21.5h-.01a9.4 9.4 0 01-4.8-1.31l-.34-.2-3.57.94.95-3.48-.22-.36a9.43 9.43 0 01-1.45-5.03c0-5.2 4.24-9.44 9.45-9.44a9.38 9.38 0 016.68 2.77 9.38 9.38 0 012.76 6.68c0 5.2-4.24 9.44-9.45 9.44zm8.04-17.48A11.3 11.3 0 0012.05.7C5.78.7.68 5.8.68 12.07c0 2 .52 3.96 1.52 5.68L.58 23.7l6.08-1.6a11.33 11.33 0 005.43 1.38h.01c6.27 0 11.37-5.1 11.37-11.37 0-3.04-1.18-5.9-3.33-8.04z"/></svg>
                                                </span>
                                                <span>WhatsApp</span>
                                            </a>
                                            <a class="db-share-app" role="menuitem" :href="links.linkedin" target="_blank" rel="noopener" @click="close()">
                                                <span class="db-share-icon" style="background: #0A66C2;">
                                                    <svg viewBox="0 0 24 24" fill="#FFFFFF"><path d="M20.45 20.45h-3.56v-5.57c0-1.33-.02-3.04-1.85-3.04-1.85 0-2.14 1.45-2.14 2.94v5.67H9.35V9h3.41v1.56h.05c.48-.9 1.64-1.85 3.37-1.85 3.6 0 4.27 2.37 4.27 5.46v6.28zM5.34 7.43a2.06 2.06 0 110-4.13 2.06 2.06 0 010 4.13zM7.12 20.45H3.56V9h3.56v11.45zM22.22 0H1.77C.79 0 0 .77 0 1.73v20.54C0 23.23.79 24 1.77 24h20.45c.98 0 1.78-.77 1.78-1.73V1.73C24 .77 23.2 0 22.22 0z"/></svg>
                                                </span>
                                                <span>LinkedIn</span>
                                            </a>
                                            <a class="db-share-app" role="menuitem" :href="links.x" target="_blank" rel="noopener" @click="close()">
                                                <span class="db-share-icon" style="background: #0F1419;">
                                                    <svg viewBox="0 0 24 24" fill="#FFFFFF"><path d="M18.24 2.25h3.31l-7.23 8.26 8.5 11.24h-6.66l-5.21-6.82-5.97 6.82H1.67l7.73-8.84L1.25 2.25h6.83l4.71 6.23 5.45-6.23zm-1.16 17.52h1.83L7.08 4.13H5.12l11.96 15.64z"/></svg>
                                                </span>
                                                <span>X</span>
                                            </a>
                                            <a class="db-share-app" role="menuitem" :href="links.facebook" target="_blank" rel="noopener" @click="close()">
                                                <span class="db-share-icon" style="background: #1877F2;">
                                                    <svg viewBox="0 0 24 24" fill="#FFFFFF"><path d="M24 12.07C24 5.41 18.63 0 12 0S0 5.4 0 12.07C0 18.1 4.39 23.1 10.13 24v-8.44H7.08v-3.49h3.04V9.41c0-3.02 1.8-4.7 4.54-4.7 1.31 0 2.68.24 2.68.24v2.97h-1.5c-1.5 0-1.96.93-1.96 1.89v2.26h3.32l-.53 3.5h-2.8V24C19.62 23.1 24 18.1 24 12.07z"/></svg>
                                                </span>
                                                <span>Facebook</span>
                                            </a>
                                            <a class="db-share-app" role="menuitem" :href="links.telegram" target="_blank" rel="noopener" @click="close()">
                                                <span class="db-share-icon" style="background: #229ED9;">
                                                    <svg viewBox="0 0 24 24" fill="#FFFFFF"><path d="M9.78 15.27l-.4 5.6c.57 0 .82-.25 1.12-.54l2.68-2.56 5.56 4.07c1.02.57 1.74.27 2.02-.94l3.66-17.15c.33-1.51-.54-2.1-1.53-1.73L1.37 10.3c-1.47.57-1.45 1.39-.25 1.76l5.5 1.71L19.4 5.73c.6-.4 1.15-.18.7.22"/></svg>
                                                </span>
                                                <span>Telegram</span>
                                            </a>
                                            <a class="db-share-app" role="menuitem" :href="links.email" @click="close()">
                                                <span class="db-share-icon" style="background: #475569;">
                                                    <svg viewBox="0 0 24 24" fill="none" stroke="#FFFFFF" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                                </span>
                                                <span>Email</span>
                                            </a>
                                        </div>

                                        <button type="button" class="db-share-native" role="menuitem" x-show="canNativeShare" style="display: none;" @click="nativeShare()">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 12v7a2 2 0 002 2h12a2 2 0 002-2v-7M16 6l-4-4-4 4M12 2v13"/></svg>
                                            <span>Instagram &amp; more apps</span>
                                        </button>

                                        <div class="db-share-copy" :class="{ 'is-copied': copied }">
                                            <span class="db-share-url" x-text="displayUrl"></span>
                                            <button type="button" role="menuitem" @click="copyLink()" :aria-label="copied ? 'Link copied' : 'Copy link'">
                                                <svg x-show="!copied" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.83 10.17a4 4 0 00-5.66 0l-4 4a4 4 0 105.66 5.66l1.1-1.1m-.76-4.9a4 4 0 005.66 0l4-4a4 4 0 00-5.66-5.66l-1.1 1.1"/></svg>
                                                <svg x-show="copied" style="display: none;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                                <span x-text="copied ? 'Copied' : 'Copy link'">Copy link</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Bookmark Saved Button -->
                                <button type="button" 
                                        class="db-opp-action-btn" 
                                        :class="isSaved ? 'btn-saved' : 'btn-unsaved'" 
                                        aria-label="Toggle save <?= htmlspecialchars($internship['title']) ?>"
                                        @click="isSaved = !isSaved; $dispatch('show-toast', { text: isSaved ? 'Internship saved to your collection!' : 'Internship removed from saved list', type: isSaved ? 'success' : 'info' })">
                                    <svg style="width: 15px; height: 15px;" :fill="isSaved ? '#4F48E6' : 'none'" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Role Title & Subtitle -->
                        <h3 class="db-opp-role-title">
                            <a href="<?= url('/internship/' . ($internship['slug'] ?? 'senior-product-design-intern')) ?>">
                                <?= htmlspecialchars($internship['title']) ?>
                            </a>
                        </h3>
                        <p class="db-opp-role-company">
                            <a href="<?= url('/company/' . ($internship['company_slug'] ?? 'google')) ?>">
                                <?= htmlspecialchars($internship['company']) ?>
                            </a>
                            <span>•</span>
                            <span><?= htmlspecialchars($internship['location']) ?></span>
                        </p>

                        <!-- Description (2 Lines Clamped) -->
                        <p class="db-opp-desc-text">
                            <?= htmlspecialchars($internship['description'] ?? 'Join the team to design and ship customer-facing digital features with high agency and hands-on guidance.') ?>
                        </p>
                    </div>

                    <div>
                        <!-- Stat Row: Category | Work Mode & Term/Deadline | Stipend -->
                        <div class="db-opp-stats-row">
                            <!-- Category -->
                            <div class="db-opp-stat-item">
                                <svg class="db-opp-stat-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5a2 2 0 011.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V5a2 2 0 012-2z"/>
                                </svg>
                                <span><?= htmlspecialchars($internship['category'] ?? 'Design') ?></span>
                            </div>

                            <div class="db-opp-stat-divider"></div>

                            <!-- Work Mode & Term / Deadline -->
                            <div class="db-opp-stat-item">
                                <svg class="db-opp-stat-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                <span><?= htmlspecialchars(($internship['work_mode'] ?? 'Remote') . ' • ' . ($internship['term'] ?? 'Summer 2024')) ?></span>
                            </div>

                            <div class="db-opp-stat-divider"></div>

                            <!-- Stipend/Pay -->
                            <div class="db-opp-stat-item stat-stipend">
                                <svg class="db-opp-stat-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                                <span><?= htmlspecialchars($internship['stipend'] ?? '₦180,000/mo') ?></span>
                            </div>
                        </div>

                        <!-- Full-Width Quick Apply Button -->
                        <a href="<?= url('/internship/' . ($internship['slug'] ?? 'software-engineer-intern')) ?>" class="db-opp-card-btn">
                            <span>Quick Apply</span>
                            <svg class="db-opp-btn-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- ── Based on Saved Section ── -->
            <div class="db-section-header" style="margin-top: 16px;">
                <h3 class="db-section-title">Based on your saved items</h3>
            </div>
            
            <div class="db-saved-bottom-grid">
                <!-- Resume Optimization Card -->
                <div class="db-saved-banner-card">
                    <div class="db-saved-banner-card-top">
                        <p>Resume Optimization</p>
                        <h4>Tailor your profile for Tech Internships</h4>
                        <span>Our AI tool analyzes your saved roles and suggests keywords to help you stand out to recruiters.</span>
                    </div>
                    <a href="<?= url('/candidate/ai-assistant/cv-analyze') ?>" class="db-saved-banner-btn">Try AI Optimizer</a>
                </div>

                <!-- Get Alerts Card -->
                <div class="db-saved-alerts-card">
                    <div class="db-saved-alerts-icon">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                    </div>
                    <div class="db-saved-alerts-title">Get Alerts</div>
                    <div class="db-saved-alerts-desc">Never miss a deadline for these companies.</div>
                    <a class="db-saved-alerts-link" href="#" @click.prevent="$dispatch('show-toast', { text: 'Deadline alerts enabled for your saved roles!', type: 'success' })">Enable Deadlines</a>
                </div>
            </div>

        </div><!-- /.db-content -->

        <!-- Dashboard Footer -->
        <?php include __DIR__ . '/../../shared/components/db-footer.php'; ?>

    </div><!-- /.db-main -->
</div><!-- /.db-shell -->
