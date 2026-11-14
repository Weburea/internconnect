<?php

declare(strict_types=1);

/**
 * Layout: App Base Layout Placeholder
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
    <meta name="ic-visit-url" content="<?= url('/t/visit') ?>">
    <script defer src="<?= asset('assets/js/ic-visit.js') ?>?v=<?= @filemtime(dirname(__DIR__, 3) . '/public/assets/js/ic-visit.js') ?: 1 ?>"></script>
    <!-- Cookie consent choices (docs/54): read by the banner, cookie page and visit tracking -->
    <script src="<?= asset('assets/js/ic-consent.js') ?>?v=<?= @filemtime(dirname(__DIR__, 3) . '/public/assets/js/ic-consent.js') ?: 1 ?>"></script>
<?= seo_render_head($title ?? null, $metaDescription ?? null) ?>
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?= asset('assets/images/logos/Logo_single_dark_dj6vhf.png') ?>">
    <!-- Global CSS -->
    <style>[x-cloak] { display: none !important; }</style>
    <link rel="stylesheet" href="<?= asset('assets/css/style.css') ?>?v=<?= @filemtime(dirname(__DIR__, 3) . '/public/assets/css/style.css') ?: 1 ?>">
    <link rel="stylesheet" href="<?= asset('assets/css/pages.css') ?>?v=<?= @filemtime(dirname(__DIR__, 3) . '/public/assets/css/pages.css') ?: 1 ?>">
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
<?php // Scroll-reveal animations on public pages only (landing views, not checkout/payment results).
$revealAuto = isset($view) && str_starts_with((string)$view, 'landing/') && !str_starts_with((string)$view, 'landing/payment'); ?>
<body class="font-sans antialiased text-gray-900 bg-gray-50"<?= $revealAuto ? ' data-reveal="auto"' : '' ?>>
    
    <!-- Global Page Loader -->
    <?php if (($showLoader ?? true) === true): ?>
        <?php include dirname(__DIR__) . '/components/loader.php'; ?>
    <?php endif; ?>
    
    <!-- Navbar Component Placeholder -->
    <?php include dirname(__DIR__) . '/components/navbar.php'; ?>

    <!-- View Content Wrapper -->
    <main id="main-content">
        <?= $content ?>
    </main>

    <!-- Footer Component Placeholder -->
    <?php if (!isset($hideFooter) || !$hideFooter): ?>
        <?php include dirname(__DIR__) . '/components/footer.php'; ?>
    <?php endif; ?>

    <!-- Global Toast Component -->
    <?php include dirname(__DIR__) . '/components/toast.php'; ?>

    <!-- Global Jenny AI Chatbot Component -->
    <?php include dirname(__DIR__) . '/components/chatbot/jenny-ai.php'; ?>
    <?php include dirname(__DIR__) . '/components/cookie-banner.php'; ?>

    <?php if ($revealAuto): ?>
    <script src="<?= asset('assets/js/ic-reveal.js') ?>?v=<?= @filemtime(dirname(__DIR__, 3) . '/public/assets/js/ic-reveal.js') ?: 1 ?>"></script>
    <?php endif; ?>

    <!-- Animated Favicon -->
    <script src="<?= asset('assets/js/favicon-animator.js') ?>"></script>

</body>
</html>
