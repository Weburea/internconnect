<?php
declare(strict_types=1);
/**
 * Component: Minimal Dashboard Footer
 */
?>
<footer class="bg-white border-t border-gray-100 py-6 px-8 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs font-medium text-gray-500 w-full">
    <div>
        InternConnect &copy; <?= date('Y') ?>. All rights reserved.
    </div>
    <div class="flex items-center gap-6">
        <a href="<?= url('/privacy') ?>" class="hover:text-gray-900 transition-colors">Privacy Policy</a>
        <a href="<?= url('/terms') ?>" class="hover:text-gray-900 transition-colors">Terms of Service</a>
        <a href="<?= url('/support') ?>" class="hover:text-gray-900 transition-colors">Support</a>
    </div>
</footer>
