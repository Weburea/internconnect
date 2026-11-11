<?php
/**
 * InternConnect Admin Mission Control Layout
 * Clean, high-density, professional administrative interface.
 * 
 * @var string $title
 * @var string $content
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Admin Mission Control — InternConnect') ?></title>
    <meta name="robots" content="noindex, nofollow">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?= asset('assets/images/logos/Logo_single_dark_dj6vhf.png') ?>">
    
    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Pre-compiled Tailwind Utilities -->
    <link rel="stylesheet" href="<?= asset('assets/css/style.css') ?>?v=<?= filemtime(dirname(__DIR__, 3) . '/public/assets/css/style.css') ?>">
    <link rel="stylesheet" href="<?= asset('assets/css/admin-utilities.css') ?>?v=<?= filemtime(dirname(__DIR__, 3) . '/public/assets/css/admin-utilities.css') ?>">

    <!-- Flatpickr Range Calendar -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    
    <!-- Alpine.js & Chart.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- PDF.js Engine (Cross-Browser Local Canvas Rendering) -->
    <script src="<?= asset('assets/js/pdf.min.js') ?>"></script>
    <script>
        if (typeof pdfjsLib !== 'undefined') {
            pdfjsLib.GlobalWorkerOptions.workerSrc = '<?= asset('assets/js/pdf.worker.min.js') ?>';
        }
    </script>
    
    <!-- Admin Portal Stylesheet -->
    <link rel="stylesheet" href="<?= asset('assets/css/admin.css') ?>?v=<?= filemtime(dirname(__DIR__, 3) . '/public/assets/css/admin.css') ?>">
</head>
<body class="admin-body">
    <?= $content ?>

    <!-- Global Admin Command Palette Modal (Ctrl + K Overlay) -->
    <?php include dirname(__DIR__) . '/components/admin-command-palette.php'; ?>

    <!-- Global Admin Toast Component (Reference 1 Bottom Pill) -->
    <?php include dirname(__DIR__) . '/components/admin-toast.php'; ?>
</body>
</html>

