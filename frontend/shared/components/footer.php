<?php

declare(strict_types=1);

/**
 * Component: Reusable Footer
 */
?>
<footer id="site-footer">
    <div class="footer-container">
        <div class="footer-grid">
            
            <!-- Brand Column -->
            <div class="footer-brand-col">
                <a href="<?= url('/') ?>" class="footer-brand-logo" aria-label="InternConnect">
                    <img src="<?= asset('assets/images/logos/Logo_dark_dx00tg.svg') ?>" alt="InternConnect" style="height: 28px; width: auto; max-height: 28px; display: block;">
                </a>
                <p class="footer-brand-text">
                    Empowering the next generation of global talent through editorial-grade career resources.
                </p>
            </div>
            
            <!-- Platform Column -->
            <div class="footer-col">
                <h3 class="footer-heading">Platform</h3>
                <ul class="footer-list">
                    <li><a href="<?= url('/elearning') ?>" class="footer-link">E-Learning</a></li>
                    <li><a href="<?= url('/internships') ?>" class="footer-link">Find Jobs</a></li>
                </ul>
            </div>
            
            <!-- Support Column -->
            <div class="footer-col">
                <h3 class="footer-heading">Support</h3>
                <ul class="footer-list">
                    <li><a href="<?= url('/help') ?>" class="footer-link">Help Center</a></li>
                    <li><a href="<?= url('/refund') ?>" class="footer-link">Refund Policy</a></li>
                    <li><a href="<?= url('/contact') ?>" class="footer-link">Contact Us</a></li>
                    <li><a href="<?= url('/cookies') ?>" class="footer-link">Cookie Policy</a></li>
                </ul>
            </div>
            
            <!-- Company Column -->
            <div class="footer-col">
                <h3 class="footer-heading">Company</h3>
                <ul class="footer-list">
                    <li><a href="<?= url('/about') ?>" class="footer-link">About Us</a></li>
                    <li><a href="<?= url('/testimonials') ?>" class="footer-link">Success Stories</a></li>
                    <li>
                        <a href="<?= url('/#meet-jenny-block') ?>" class="footer-link footer-link-jenny" @click.prevent="$dispatch('open-jenny')">
                            <span>Ask Jenny</span>
                            <span class="footer-jenny-badge">AI</span>
                        </a>
                    </li>
                </ul>
            </div>
            
            <!-- Follow Us Column -->
            <div class="footer-col footer-follow-col">
                <h3 class="footer-heading">Follow Us</h3>
                <div class="footer-social-boxes">
                    <!-- LinkedIn -->
                    <a href="https://linkedin.com" target="_blank" rel="noopener noreferrer" class="footer-social-box" aria-label="LinkedIn">
                        <svg class="footer-social-svg" fill="currentColor" viewBox="0 0 24 24">
                            <path fill-rule="evenodd" d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.779-1.75-1.75s.784-1.75 1.75-1.75 1.75.779 1.75 1.75-.784 1.75-1.75 1.75zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z" clip-rule="evenodd" />
                        </svg>
                    </a>
                    <!-- Facebook -->
                    <a href="https://facebook.com" target="_blank" rel="noopener noreferrer" class="footer-social-box" aria-label="Facebook">
                        <svg class="footer-social-svg" fill="currentColor" viewBox="0 0 24 24">
                            <path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd" />
                        </svg>
                    </a>
                    <!-- Instagram -->
                    <a href="https://instagram.com" target="_blank" rel="noopener noreferrer" class="footer-social-box" aria-label="Instagram">
                        <svg class="footer-social-svg" fill="currentColor" viewBox="0 0 24 24">
                            <path fill-rule="evenodd" d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z" clip-rule="evenodd" />
                        </svg>
                    </a>
                    <!-- X / Twitter -->
                    <a href="https://x.com" target="_blank" rel="noopener noreferrer" class="footer-social-box" aria-label="X (formerly Twitter)">
                        <svg class="footer-social-svg" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 24.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Bottom Bar -->
        <div class="footer-bottom-bar">
            <p class="footer-copyright">
                &copy; <script>document.write(new Date().getFullYear())</script> InternConnect. All rights reserved.
            </p>
            <p class="footer-legal-notice">
                Read our <a href="<?= url('/privacy') ?>" class="footer-legal-link">Privacy Policy</a> and <a href="<?= url('/terms') ?>" class="footer-legal-link">Terms &amp; Conditions</a>.
            </p>
        </div>
    </div>
</footer>

<style>
.footer-link-jenny {
    display: inline-flex;
    align-items: center;
    gap: 6px;
}
.footer-jenny-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 1px 6px;
    font-size: 10px;
    font-weight: 700;
    line-height: 1.2;
    border-radius: 9999px;
    background: #EEF2FF;
    color: #4F48E6;
    border: 1px solid #C7D2FE;
    letter-spacing: 0.04em;
    vertical-align: middle;
}
.footer-legal-notice {
    font-size: 13px;
    color: #64748B;
    margin: 0;
}
.footer-legal-link {
    color: #4F48E6;
    text-decoration: underline;
    text-underline-offset: 3px;
    font-weight: 500;
    transition: color 0.15s ease;
}
.footer-legal-link:hover {
    color: #3730A3;
}
@media (max-width: 640px) {
    .footer-bottom-bar {
        flex-direction: column;
        gap: 12px;
        align-items: flex-start;
    }
}
</style>
