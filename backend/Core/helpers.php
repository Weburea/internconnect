<?php

declare(strict_types=1);

if (!function_exists('url')) {
    function url(string $path = ''): string
    {
        $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
        $basePath = str_replace(['/public/index.php', '/index.php'], '', $scriptName);

        return $basePath . '/' . ltrim($path, '/');
    }
}

if (!function_exists('absolute_url')) {
    function absolute_url(string $path = ''): string
    {
        $relativePath = url($path);

        if (str_starts_with($relativePath, 'http://') || str_starts_with($relativePath, 'https://')) {
            return $relativePath;
        }

        $scheme = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https')) ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';

        return $scheme . '://' . $host . '/' . ltrim($relativePath, '/');
    }
}

if (!function_exists('asset')) {
    function asset(string $path): string
    {
        $cloudinaryCloudName = $_ENV['CLOUDINARY_CLOUD_NAME'] ?? $_SERVER['CLOUDINARY_CLOUD_NAME'] ?? null;

        if ($cloudinaryCloudName) {
            $cleanPath = ltrim($path, '/');

            // Check if this is a local image asset under assets/images
            if (str_starts_with($cleanPath, 'assets/images/')) {
                $subPath = substr($cleanPath, strlen('assets/images/'));

                // Local test / dummy documents under dummb/ must never route to Cloudinary
                if (str_starts_with($subPath, 'dummb/')) {
                    return url($path);
                }

                // Specific logo asset mappings per brand guidelines
                if ($subPath === 'logos/Logo_dark_dx00tg.svg' || $subPath === 'logos/logo_color_name.svg') {
                    return "https://res.cloudinary.com/{$cloudinaryCloudName}/image/upload/v1785517289/Logo_dark_dx00tg.svg";
                }
                if ($subPath === 'logos/logo_black_b_z9uhv6.svg' || $subPath === 'logos/logo_black_name.svg') {
                    return "https://res.cloudinary.com/{$cloudinaryCloudName}/image/upload/v1785562552/logo_black_b_z9uhv6.svg";
                }
                if ($subPath === 'logos/Logo_single_dark_dj6vhf.png' || $subPath === 'logos/logo_color.svg') {
                    return "https://res.cloudinary.com/{$cloudinaryCloudName}/image/upload/v1785517512/Logo_single_dark_dj6vhf.png";
                }
                if ($subPath === 'logos/internconnect-icon.svg' || $subPath === 'internconnect-icon.svg') {
                    return "https://res.cloudinary.com/{$cloudinaryCloudName}/image/upload/v1790300840/internConnect/images/logo/internconnect-icon.svg";
                }

                // Specific homepage image asset mappings with versions
                if ($subPath === 'homepage_feature_showcase.png') {
                    return "https://res.cloudinary.com/{$cloudinaryCloudName}/image/upload/v1785645677/internConnect/images/homepage_feature_showcase.png";
                }
                if ($subPath === 'homepage_icon_star.png') {
                    return "https://res.cloudinary.com/{$cloudinaryCloudName}/image/upload/v1785645709/internConnect/images/homepage_icon_star.png";
                }
                if ($subPath === 'homepage_icon_margin_alt.png') {
                    return "https://res.cloudinary.com/{$cloudinaryCloudName}/image/upload/v1785645701/internConnect/images/homepage_icon_margin_alt.png";
                }
                if ($subPath === 'homepage_icon_margin.png') {
                    return "https://res.cloudinary.com/{$cloudinaryCloudName}/image/upload/v1785645693/internConnect/images/homepage_icon_margin.png";
                }
                // Homepage hero banner illustration
                if ($subPath === 'internconnect_banner_img.png' || $subPath === 'internconnect_banner_img_ik0cyy.png' || $subPath === 'internconnect_banner_qkshpt.png') {
                    return "https://res.cloudinary.com/{$cloudinaryCloudName}/image/upload/v1790719321/internconnect_banner_img_ik0cyy.png";
                }

                // Homepage hero illustration (Our Mission section)
                if ($subPath === 'homepage_hero_illustration_bnhu4c.png') {
                    return "https://res.cloudinary.com/{$cloudinaryCloudName}/image/upload/v1785564352/homepage_hero_illustration_bnhu4c.png";
                }

                // Visual Identity background illustration for recovery and auth pages
                if ($subPath === 'Visual_Identity_7_ptgohs.png') {
                    return "https://res.cloudinary.com/{$cloudinaryCloudName}/image/upload/v1785958183/Visual_Identity_7_ptgohs.png";
                }

                // Payment Status Screen Images
                if ($subPath === 'pricing_success_n0cbzk.png' || $subPath === 'payment_success.png') {
                    return "https://res.cloudinary.com/{$cloudinaryCloudName}/image/upload/v1788574692/pricing_success_n0cbzk.png";
                }
                if ($subPath === 'payment_declined_jyso8t.png' || $subPath === 'payment_declined.png') {
                    return "https://res.cloudinary.com/{$cloudinaryCloudName}/image/upload/v1788574693/payment_declined_jyso8t.png";
                }
                if ($subPath === 'payment_cancelled_dnvsmc.png' || $subPath === 'payment_cancelled.png') {
                    return "https://res.cloudinary.com/{$cloudinaryCloudName}/image/upload/v1788574692/payment_cancelled_dnvsmc.png";
                }
                if ($subPath === 'banner_my_internship.jpg' || $subPath === 'banner_my_internship.jpeg') {
                    return "https://res.cloudinary.com/{$cloudinaryCloudName}/image/upload/v1790308883/internConnect/images/banner_my_internship.jpg";
                }

                // Jenny AI Chatbot mascot poses and launcher badge
                if ($subPath === 'jenny_ai/jenny_icon.png' || $subPath === 'jenny_icon.png') {
                    return "https://res.cloudinary.com/{$cloudinaryCloudName}/image/upload/v1790304925/internConnect/images/jenny_ai/jenny_icon.png";
                }
                if ($subPath === 'jenny_ai/jenny_ai_hi.png' || $subPath === 'jenny_ai_hi.png') {
                    return "https://res.cloudinary.com/{$cloudinaryCloudName}/image/upload/v1790296466/internConnect/images/jenny_ai/jenny_ai_hi.png";
                }
                if ($subPath === 'jenny_ai/Thinking_jenny_ai.png' || $subPath === 'Thinking_jenny_ai.png') {
                    return "https://res.cloudinary.com/{$cloudinaryCloudName}/image/upload/v1790296471/internConnect/images/jenny_ai/Thinking_jenny_ai.png";
                }
                if ($subPath === 'jenny_ai/Pointing_jenny_ai.png' || $subPath === 'Pointing_jenny_ai.png') {
                    return "https://res.cloudinary.com/{$cloudinaryCloudName}/image/upload/v1790296510/internConnect/images/jenny_ai/Pointing_jenny_ai.png";
                }
                if ($subPath === 'jenny_ai/Full-body_standing_pose_jenny_ai.png' || $subPath === 'Full-body_standing_pose_jenny_ai.png') {
                    return "https://res.cloudinary.com/{$cloudinaryCloudName}/image/upload/v1790296516/internConnect/images/jenny_ai/Full-body_standing_pose_jenny_ai.png";
                }

                // Student portraits & avatars
                if ($subPath === 'learning_student_beanbag.png' || $subPath === 'company/learning_student_beanbag.png') {
                    return "https://res.cloudinary.com/{$cloudinaryCloudName}/image/upload/v1793377311/internConnect/images/company/learning_student_beanbag.png";
                }
                if ($subPath === 'ipad_e-learn-removebg-preview_zjxx87.png' || $subPath === 'company/ipad_e-learn-removebg-preview_zjxx87.png') {
                    return "https://res.cloudinary.com/weburea/image/upload/v1793383654/ipad_e-learn-removebg-preview_zjxx87.png";
                }
                if ($subPath === 'ipad_e-learn_aamnea.png' || $subPath === 'company/ipad_e-learn_aamnea.png') {
                    return "https://res.cloudinary.com/weburea/image/upload/v1793382018/ipad_e-learn_aamnea.png";
                }
                if ($subPath === 'welcome_smiling_woman.png') {
                    return "https://res.cloudinary.com/{$cloudinaryCloudName}/image/upload/v1785645753/internConnect/images/welcome_smiling_woman.png";
                }
                if ($subPath === 'profile.png' || $subPath === 'student_profile_avatar.png') {
                    return "https://res.cloudinary.com/{$cloudinaryCloudName}/image/upload/v1793376037/internConnect/images/student_profile_avatar.png";
                }

                // How-it-Works and Showcase Mockups (Company & Platform Suite)
                if ($subPath === 'company/holding_phone_jenny_ai.png' || $subPath === 'holding_phone_jenny_ai.png') {
                    return "https://res.cloudinary.com/{$cloudinaryCloudName}/image/upload/v1793110800/internConnect/images/company/holding_phone_jenny_ai.png";
                }
                if ($subPath === 'company/internconnect_website_browser_mockup.png' || $subPath === 'internconnect_website_browser_mockup.png') {
                    return "https://res.cloudinary.com/{$cloudinaryCloudName}/image/upload/v1793110768/internConnect/images/company/internconnect_website_browser_mockup.png";
                }
                if ($subPath === 'company/internconnect_multi_device_mockup.png' || $subPath === 'internconnect_multi_device_mockup.png') {
                    return "https://res.cloudinary.com/{$cloudinaryCloudName}/image/upload/v1793110727/internConnect/images/company/internconnect_multi_device_mockup.png";
                }
                if ($subPath === 'company/internconnect_ipad_on_chair.webp' || $subPath === 'internconnect_ipad_on_chair.webp') {
                    return "https://res.cloudinary.com/{$cloudinaryCloudName}/image/upload/v1793110830/internConnect/images/company/internconnect_ipad_on_chair.webp";
                }
                if ($subPath === 'company/internconnect_ipad_mockup.png' || $subPath === 'internconnect_ipad_mockup.png') {
                    return "https://res.cloudinary.com/{$cloudinaryCloudName}/image/upload/v1793110858/internConnect/images/company/internconnect_ipad_mockup.png";
                }
                if ($subPath === 'company/internconnect_laptop_dark_testimonials.webp' || $subPath === 'internconnect_laptop_dark_testimonials.webp') {
                    return "https://res.cloudinary.com/{$cloudinaryCloudName}/image/upload/v1793110888/internConnect/images/company/internconnect_laptop_dark_testimonials.webp";
                }
                if ($subPath === 'company/ipad_Scheduling_Interview_yfgy0h.png' || $subPath === 'ipad_Scheduling_Interview_yfgy0h.png') {
                    return "https://res.cloudinary.com/{$cloudinaryCloudName}/image/upload/v1793420191/ipad_Scheduling_Interview_yfgy0h.png";
                }
                if ($subPath === 'company/Corporate_Legitimacy_image_ninwep.png' || $subPath === 'Corporate_Legitimacy_image_ninwep.png') {
                    return "https://res.cloudinary.com/{$cloudinaryCloudName}/image/upload/v1793420623/Corporate_Legitimacy_image_ninwep.png";
                }
                if ($subPath === 'company/AI_Optimization_Screening_Rules_tn60ad.png' || $subPath === 'AI_Optimization_Screening_Rules_tn60ad.png') {
                    return "https://res.cloudinary.com/{$cloudinaryCloudName}/image/upload/v1793421304/AI_Optimization_Screening_Rules_tn60ad.png";
                }
                if ($subPath === 'company/Match_Score_brqqxk.png' || $subPath === 'Match_Score_brqqxk.png') {
                    return "https://res.cloudinary.com/{$cloudinaryCloudName}/image/upload/v1793421523/Match_Score_brqqxk.png";
                }
                if ($subPath === 'company/Schedule_Interviews_cllzqd.png' || $subPath === 'Schedule_Interviews_cllzqd.png') {
                    return "https://res.cloudinary.com/{$cloudinaryCloudName}/image/upload/v1793421867/Schedule_Interviews_cllzqd.png";
                }

                // If it starts with logos/, map to logo/ (singular)
                if (str_starts_with($subPath, 'logos/')) {
                    $cloudinaryPath = 'internConnect/images/logo/' . substr($subPath, strlen('logos/'));
                } else {
                    $cloudinaryPath = 'internConnect/images/' . $subPath;
                }

                return "https://res.cloudinary.com/{$cloudinaryCloudName}/image/upload/{$cloudinaryPath}";
            }
        }

        return url($path);
    }
}

if (!function_exists('render_pagination')) {
    /**
     * Render standard responsive pagination component.
     *
     * @param int    $currentPage     Current active page number (1-indexed).
     * @param int    $totalItems      Total items count.
     * @param int    $perPage         Number of items per page.
     * @param string $itemName        Plural item name (e.g. 'applications', 'internships').
     * @param string $baseUrl         Base URL for pagination links (optional).
     * @param array  $queryParams     Extra query params to preserve in page links.
     * @param array  $options         Extra configuration:
     *                                - 'alpine' (bool): Whether to bind to Alpine.js x-data pagination state.
     *                                - 'pageVar' (string): Alpine page variable name (default 'currentPage').
     *                                - 'totalVar' (string): Alpine total count expression.
     *                                - 'perPageVar' (string): Alpine per page count expression.
     *                                - 'class' (string): Extra CSS classes.
     * @return string Rendered HTML markup.
     */
    function render_pagination(
        int $currentPage = 1,
        int $totalItems = 12,
        int $perPage = 6,
        string $itemName = 'applications',
        string $baseUrl = '',
        array $queryParams = [],
        array $options = []
    ): string {
        $totalPages = max(1, (int) ceil($totalItems / max(1, $perPage)));
        $currentPage = max(1, min($currentPage, $totalPages));
        $isAlpine = !empty($options['alpine']);
        $pageVar = $options['pageVar'] ?? 'currentPage';
        $totalVar = $options['totalVar'] ?? null;
        $customClass = $options['class'] ?? '';
        
        $startItem = ($currentPage - 1) * $perPage + 1;
        $endItem = min($currentPage * $perPage, $totalItems);
        $showingCount = min($perPage, $totalItems);

        ob_start();
        ?>
        <div class="app-pagination-bar <?= htmlspecialchars($customClass) ?>" role="navigation" aria-label="Pagination">
            <!-- Left: Showing Info -->
            <div class="app-pagination-info">
                <?php if ($isAlpine && $totalVar): ?>
                    Showing <span x-text="Math.min(<?= $perPage ?>, <?= $totalVar ?>)"></span> of <span x-text="<?= $totalVar ?>"></span> <?= htmlspecialchars($itemName) ?>
                <?php else: ?>
                    Showing <?= $showingCount ?> of <?= $totalItems ?> <?= htmlspecialchars($itemName) ?>
                <?php endif; ?>
            </div>

            <!-- Right: Controls -->
            <div class="app-pagination-controls">
                <?php if ($isAlpine): ?>
                    <!-- Prev Button -->
                    <button type="button" 
                            class="app-pagination-btn app-pagination-btn-prev" 
                            :disabled="<?= $pageVar ?> <= 1" 
                            @click="if (<?= $pageVar ?> > 1) { <?= $pageVar ?>--; $dispatch('page-changed', <?= $pageVar ?>); }"
                            aria-label="Previous page">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </button>

                    <!-- Page Numbers -->
                    <template x-for="p in totalPages()" :key="p">
                        <button type="button" 
                                class="app-pagination-btn" 
                                :class="{ 'is-active': <?= $pageVar ?> === p }" 
                                @click="<?= $pageVar ?> = p; $dispatch('page-changed', p);"
                                x-text="p"
                                :aria-current="<?= $pageVar ?> === p ? 'page' : false">
                        </button>
                    </template>

                    <!-- Next Button -->
                    <button type="button" 
                            class="app-pagination-btn app-pagination-btn-next" 
                            :disabled="<?= $pageVar ?> >= totalPages()" 
                            @click="if (<?= $pageVar ?> < totalPages()) { <?= $pageVar ?>++; $dispatch('page-changed', <?= $pageVar ?>); }"
                            aria-label="Next page">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>
                <?php else: ?>
                    <!-- Server-Side Link Pagination -->
                    <?php
                    $buildUrl = function(int $p) use ($baseUrl, $queryParams): string {
                        $params = array_merge($queryParams, ['page' => $p]);
                        $query = http_build_query($params);
                        $base = !empty($baseUrl) ? url($baseUrl) : strtok($_SERVER['REQUEST_URI'] ?? '', '?');
                        return $base . ($query ? '?' . $query : '');
                    };
                    ?>
                    <!-- Prev -->
                    <?php if ($currentPage > 1): ?>
                        <a href="<?= htmlspecialchars($buildUrl($currentPage - 1)) ?>" class="app-pagination-btn app-pagination-btn-prev" aria-label="Previous page">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                            </svg>
                        </a>
                    <?php else: ?>
                        <button type="button" class="app-pagination-btn app-pagination-btn-prev is-disabled" disabled aria-label="Previous page">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                            </svg>
                        </button>
                    <?php endif; ?>

                    <!-- Numbered Pages -->
                    <?php for ($p = 1; $p <= $totalPages; $p++): ?>
                        <?php if ($p === $currentPage): ?>
                            <span class="app-pagination-btn is-active" aria-current="page"><?= $p ?></span>
                        <?php else: ?>
                            <a href="<?= htmlspecialchars($buildUrl($p)) ?>" class="app-pagination-btn"><?= $p ?></a>
                        <?php endif; ?>
                    <?php endfor; ?>

                    <!-- Next -->
                    <?php if ($currentPage < $totalPages): ?>
                        <a href="<?= htmlspecialchars($buildUrl($currentPage + 1)) ?>" class="app-pagination-btn app-pagination-btn-next" aria-label="Next page">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    <?php else: ?>
                        <button type="button" class="app-pagination-btn app-pagination-btn-next is-disabled" disabled aria-label="Next page">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                            </svg>
                        </button>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
        <?php
        return ob_get_clean() ?: '';
    }
}

if (!function_exists('seo')) {
    function seo(): \App\Services\SeoService
    {
        return \App\Services\SeoService::getInstance();
    }
}

if (!function_exists('seo_render_head')) {
    function seo_render_head(?string $fallbackTitle = null, ?string $fallbackDesc = null): string
    {
        $service = \App\Services\SeoService::getInstance();
        if ($fallbackTitle !== null) {
            $service->setTitle($fallbackTitle);
        }
        if ($fallbackDesc !== null) {
            $service->setDescription($fallbackDesc);
        }
        return $service->render();
    }
}


