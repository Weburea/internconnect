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

if (!function_exists('asset')) {
    function asset(string $path): string
    {
        $cloudinaryCloudName = $_ENV['CLOUDINARY_CLOUD_NAME'] ?? $_SERVER['CLOUDINARY_CLOUD_NAME'] ?? null;

        if ($cloudinaryCloudName) {
            $cleanPath = ltrim($path, '/');

            // Check if this is a local image asset under assets/images
            if (str_starts_with($cleanPath, 'assets/images/')) {
                $subPath = substr($cleanPath, strlen('assets/images/'));

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
                // Homepage hero illustration (Our Mission section)
                if ($subPath === 'homepage_hero_illustration_bnhu4c.png') {
                    return "https://res.cloudinary.com/{$cloudinaryCloudName}/image/upload/v1785564352/homepage_hero_illustration_bnhu4c.png";
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

