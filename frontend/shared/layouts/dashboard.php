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
    <!-- CSRF token for every POST (App\Core\Security; ic-csrf.js attaches it to fetch, forms and beacons) -->
    <meta name="csrf-token" content="<?= htmlspecialchars(\App\Core\Security::csrfToken(), ENT_QUOTES) ?>">
    <script src="<?= asset('assets/js/ic-csrf.js') ?>?v=<?= @filemtime(dirname(__DIR__, 3) . '/public/assets/js/ic-csrf.js') ?: 1 ?>"></script>
    <meta name="ic-visit-url" content="<?= url('/t/visit') ?>">
    <script defer src="<?= asset('assets/js/ic-visit.js') ?>?v=<?= @filemtime(dirname(__DIR__, 3) . '/public/assets/js/ic-visit.js') ?: 1 ?>"></script>
    <!-- Cookie consent choices (docs/54): read by the banner, cookie page and visit tracking -->
    <script src="<?= asset('assets/js/ic-consent.js') ?>?v=<?= @filemtime(dirname(__DIR__, 3) . '/public/assets/js/ic-consent.js') ?: 1 ?>"></script>
    <?php
    // The help centre is public and should be found in search; every other dashboard page is private.
    $dashPath = '/' . trim((string)preg_replace('#^' . preg_quote(rtrim(dirname($_SERVER['SCRIPT_NAME'] ?? ''), '/\\'), '#') . '#', '', (string)parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH)), '/');
    if (str_starts_with($dashPath, '/help')): ?>
<?= seo_render_head(str_replace(' — InternConnect', '', $title ?? 'Help Center'), $metaDescription ?? 'Answers about finding internships, applications, interviews, Jenny AI, plans and payments on InternConnect.') ?>
    <?php else: ?>
    <title><?= htmlspecialchars($title ?? 'Dashboard — InternConnect') ?></title>
    <meta name="description" content="Your InternConnect workspace for applications, interviews, messages and settings.">
    <meta name="robots" content="noindex, nofollow">
    <?php endif; ?>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?= asset('assets/images/logos/Logo_single_dark_dj6vhf.png') ?>">
    
    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS (required for global components like footer) -->
    <link rel="stylesheet" href="<?= asset('assets/css/style.css') ?>?v=<?= filemtime(dirname(__DIR__, 3) . '/public/assets/css/style.css') ?>">
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>
    <script defer src="<?= asset('assets/js/ic-select.js') ?>?v=<?= @filemtime(dirname(__DIR__, 3) . '/public/assets/js/ic-select.js') ?: 1 ?>"></script>
    
    <!-- Dashboard Custom Styles -->
    <link rel="stylesheet" href="<?= asset('assets/css/dashboard.css') ?>?v=<?= filemtime(dirname(__DIR__, 3) . '/public/assets/css/dashboard.css') ?>">
    
    <!-- Flatpickr Date & Time Picker -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
</head>
<body>
    <?= $content ?>
    
    <!-- Global Toast Notifications -->
    <?php include dirname(__DIR__) . '/components/toast.php'; ?>
    
    <!-- Global Jenny AI Chatbot Component -->
    <?php include dirname(__DIR__) . '/components/chatbot/jenny-ai.php'; ?>
    <?php include dirname(__DIR__) . '/components/cookie-banner.php'; ?>
    
    <!-- Animated Favicon -->
    <script src="<?= asset('assets/js/favicon-animator.js') ?>"></script>
</body>
</html>
