<?php
/**
 * Dashboard Layout
 * A clean layout without the global navbar and footer.
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
    <title><?= htmlspecialchars($title ?? 'Dashboard — InternConnect') ?></title>
    <meta name="description" content="Candidate dashboard interface for tracking internship applications, recommendations, and settings.">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?= asset('assets/images/logos/Logo_single_dark_dj6vhf.png') ?>">
    
    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS (required for global components like footer) -->
    <link rel="stylesheet" href="<?= asset('assets/css/style.css') ?>">
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>
    
    <!-- Dashboard Custom Styles -->
    <link rel="stylesheet" href="<?= asset('assets/css/dashboard.css') ?>">
    
    <!-- Flatpickr Date & Time Picker -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
</head>
<body>
    <?= $content ?>
    
    <!-- Global Toast Notifications -->
    <?php include dirname(__DIR__) . '/components/toast.php'; ?>
    
    <!-- Animated Favicon -->
    <script src="<?= asset('assets/js/favicon-animator.js') ?>"></script>
</body>
</html>
