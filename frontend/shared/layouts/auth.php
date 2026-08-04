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
    <title><?= htmlspecialchars($title ?? 'Authenticate - InternConnect') ?></title>
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
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
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
