<?php

declare(strict_types=1);

/**
 * Layout: Authentication Base Layout Placeholder
 * @var string $content
 * @var string $title
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- CSRF token for every POST (App\Core\Security; ic-csrf.js attaches it to fetch, forms and beacons) -->
    <meta name="csrf-token" content="<?= htmlspecialchars(\App\Core\Security::csrfToken(), ENT_QUOTES) ?>">
    <script src="<?= asset('assets/js/ic-csrf.js') ?>?v=<?= @filemtime(dirname(__DIR__, 3) . '/public/assets/js/ic-csrf.js') ?: 1 ?>"></script>
    <!-- Cookie consent choices (docs/54): read by the banner, cookie page and visit tracking -->
    <script src="<?= asset('assets/js/ic-consent.js') ?>?v=<?= @filemtime(dirname(__DIR__, 3) . '/public/assets/js/ic-consent.js') ?: 1 ?>"></script>
<?php
    $authPath = '/' . trim((string)preg_replace('#^' . preg_quote(rtrim(dirname($_SERVER['SCRIPT_NAME'] ?? ''), '/\\'), '#') . '#', '', (string)parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH)), '/');
    if (!in_array($authPath, ['/login', '/register', '/register/student', '/register/employer'], true)) {
        seo()->setRobots('noindex, follow');
    }
    ?>
<?= seo_render_head(str_replace(' - InternConnect', '', $title ?? 'Sign in'), $metaDescription ?? 'Sign in or create a free InternConnect account to find internships, get matched by Jenny AI and get hired.') ?>
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?= asset('assets/images/logos/Logo_single_dark_dj6vhf.png') ?>">
    <!-- Tailwind CSS -->
    <link rel="stylesheet" href="<?= asset('assets/css/style.css') ?>">
    <?php if (isset($extraCss)): ?>
        <?php foreach ((array)$extraCss as $cssPath): ?>
            <link rel="stylesheet" href="<?= asset($cssPath) ?>">
        <?php endforeach; ?>
    <?php endif; ?>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.17.5/dist/cdn.min.js"></script>
    <script defer src="<?= asset('assets/js/ic-select.js') ?>?v=<?= @filemtime(dirname(__DIR__, 3) . '/public/assets/js/ic-select.js') ?: 1 ?>"></script>
</head>
<body class="font-sans antialiased text-gray-900">
    
    <!-- Global Page Loader -->
    <?php if (($showLoader ?? true) === true): ?>
        <?php include dirname(__DIR__) . '/components/loader.php'; ?>
    <?php endif; ?>
    
    <!-- Auth Main Container -->
    <main class="flex-1 flex w-full">
        <?= $content ?>
    </main>

    <!-- Animated Favicon -->
    <script src="<?= asset('assets/js/favicon-animator.js') ?>"></script>

</body>
</html>
