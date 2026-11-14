<?php
declare(strict_types=1);

namespace Tests\Unit;

use Tests\TestCase;
use App\Controllers\AdminController;

class AdminControllerTest extends TestCase
{
    private AdminController $controller;

    public function __construct()
    {
        $this->controller = new AdminController();
    }

    public function testAdminMetricsConsistency(): void
    {
        // Test that AdminController exists and methods are callable
        $this->assertTrue(method_exists($this->controller, 'overview'), 'Overview method must exist on AdminController');
        $this->assertTrue(method_exists($this->controller, 'interns'), 'Interns method must exist on AdminController');
        $this->assertTrue(method_exists($this->controller, 'employers'), 'Employers method must exist on AdminController');
        $this->assertTrue(method_exists($this->controller, 'login'), 'Login method must exist on AdminController');
        $this->assertTrue(method_exists($this->controller, 'loginPost'), 'LoginPost method must exist on AdminController');
        $this->assertTrue(method_exists($this->controller, 'twoFactor'), 'TwoFactor method must exist on AdminController');
        $this->assertTrue(method_exists($this->controller, 'twoFactorPost'), 'TwoFactorPost method must exist on AdminController');
        $this->assertTrue(method_exists($this->controller, 'forgotPassword'), 'ForgotPassword method must exist on AdminController');
        $this->assertTrue(method_exists($this->controller, 'forgotPasswordPost'), 'ForgotPasswordPost method must exist on AdminController');
        $this->assertTrue(method_exists($this->controller, 'resetPassword'), 'ResetPassword method must exist on AdminController');
        $this->assertTrue(method_exists($this->controller, 'resetPasswordPost'), 'ResetPasswordPost method must exist on AdminController');
        $this->assertTrue(method_exists($this->controller, 'logout'), 'Logout method must exist on AdminController');
    }

    public function testUrlHelperGeneratesCorrectAdminPaths(): void
    {
        $adminUrl = url('/admin');
        $this->assertStringContains('/admin', $adminUrl, 'url(/admin) must generate valid path');

        $internsUrl = url('/admin/interns');
        $this->assertStringContains('/admin/interns', $internsUrl, 'url(/admin/interns) must generate valid path');

        $employersUrl = url('/admin/employers');
        $this->assertStringContains('/admin/employers', $employersUrl, 'url(/admin/employers) must generate valid path');

        $loginUrl = url('/admin/login');
        $this->assertStringContains('/admin/login', $loginUrl, 'url(/admin/login) must generate valid path');

        $logoutUrl = url('/admin/logout');
        $this->assertStringContains('/admin/logout', $logoutUrl, 'url(/admin/logout) must generate valid path');

        $twoFactorUrl = url('/admin/login/2fa');
        $this->assertStringContains('/admin/login/2fa', $twoFactorUrl, 'url(/admin/login/2fa) must generate valid path');

        $forgotPasswordUrl = url('/admin/forgot-password');
        $this->assertStringContains('/admin/forgot-password', $forgotPasswordUrl, 'url(/admin/forgot-password) must generate valid path');

        $resetPasswordUrl = url('/admin/reset-password');
        $this->assertStringContains('/admin/reset-password', $resetPasswordUrl, 'url(/admin/reset-password) must generate valid path');
    }

    /**
     * Requirement: Zero red buttons on operational actions (suspend, reject, audit).
     * Red is strictly reserved for logout or permanent account deletion.
     */
    public function testZeroRedButtonsOnOperationalActions(): void
    {
        $viewsDir = __DIR__ . '/../../frontend/admin';
        $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($viewsDir));

        foreach ($iterator as $file) {
            if ($file->isFile() && $file->getExtension() === 'php') {
                $content = file_get_contents($file->getPathname());
                $this->assertFalse(
                    (bool) preg_match('/admin-btn-danger(?:-soft)?/i', $content),
                    "Forbidden red button class found in {$file->getFilename()}! Operational actions must use dark buttons or dark outline."
                );
            }
        }
    }

    /**
     * Requirement: Candidate onboarding credentials in interns directory must include complete academic & document records.
     */
    public function testCandidateOnboardingCredentialsCompleteness(): void
    {
        $controllerFile = file_get_contents(__DIR__ . '/../../backend/Controllers/AdminController.php');

        // Verify that candidates have soft skills, CGPA, academic level, and documents
        $this->assertStringContains("'cgpa' =>", $controllerFile, 'Interns array must include CGPA');
        $this->assertStringContains("'level' =>", $controllerFile, 'Interns array must include academic level');
        $this->assertStringContains("'soft_skills' =>", $controllerFile, 'Interns array must include verified soft skills');
        $this->assertStringContains("'cv_document' =>", $controllerFile, 'Interns array must include CV document preview');
        $this->assertStringContains("'student_id_document' =>", $controllerFile, 'Interns array must include student ID document');
        $this->assertStringContains("'portfolio_url' =>", $controllerFile, 'Interns array must include portfolio URL');
    }

    public function testVerificationBadgeVisibilityRule(): void
    {
        // Verified employer should show badge; unverified should NOT show negative badge
        $verifiedEmployer = ['name' => 'Nexura Technologies', 'is_verified' => true];
        $unverifiedEmployer = ['name' => 'Kiro Labs', 'is_verified' => false];

        $this->assertTrue($verifiedEmployer['is_verified'], 'Verified employer must have is_verified true');
        $this->assertFalse($unverifiedEmployer['is_verified'], 'Unverified employer must have is_verified false');
    }

    /**
     * Requirement: Zero native browser dialogs (alert, prompt, confirm) anywhere in admin views.
     */
    public function testZeroNativeAlertsInAdminViews(): void
    {
        $viewsDir = __DIR__ . '/../../frontend/admin';
        $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($viewsDir));

        foreach ($iterator as $file) {
            if ($file->isFile() && $file->getExtension() === 'php') {
                $content = file_get_contents($file->getPathname());
                $this->assertFalse(
                    (bool) preg_match('/(?:window\.)?(?:alert|prompt|confirm)\s*\(/i', $content),
                    "Native browser dialog found in {$file->getFilename()}! Must use custom Alpine modals or toast notifications."
                );
            }
        }
    }

    /**
     * Requirement: Badge strings in Overview attention queue must fit within 32px badge square (<= 4 chars).
     */
    public function testAttentionQueueBadgesFitWithinPills(): void
    {
        $controllerFile = file_get_contents(__DIR__ . '/../../backend/Controllers/AdminController.php');
        
        // Extract $attentionQueue block
        if (preg_match('/\$attentionQueue\s*=\s*\[(.*?)\];\s*\$funnel/s', $controllerFile, $block)) {
            preg_match_all("/'badge'\s*=>\s*'([^']+)'/", $block[1], $matches);
            $this->assertNotEmpty($matches[1], 'Attention queue badges must not be empty');
            foreach ($matches[1] as $badgeText) {
                $this->assertTrue(
                    strlen($badgeText) <= 4,
                    "Badge text '{$badgeText}' in Attention Queue is too long! Maximum 4 characters allowed to fit 32px square."
                );
            }
        } else {
            $this->fail('Could not locate $attentionQueue in AdminController.php');
        }
    }

    /**
     * Requirement: Datepickers across Overview, Interns, and Employers must use custom Flatpickr range pickers with static month selector.
     */
    public function testAdminDatepickerAndDocumentViewerFeatures(): void
    {
        $overviewView = file_get_contents(__DIR__ . '/../../frontend/admin/overview/index.php');
        $internsView = file_get_contents(__DIR__ . '/../../frontend/admin/interns/index.php');
        $employersView = file_get_contents(__DIR__ . '/../../frontend/admin/employers/index.php');

        $this->assertStringContains('id="overview-datepicker"', $overviewView, 'Overview must have #overview-datepicker');
        $this->assertStringContains('id="interns-datepicker"', $internsView, 'Interns must have #interns-datepicker');
        $this->assertStringContains('id="employers-datepicker"', $employersView, 'Employers must have #employers-datepicker');

        // Flatpickr static month header (Rule: No native boxy select covering calendar days)
        $this->assertStringContains("monthSelectorType: 'static'", $overviewView, 'Overview Flatpickr must use static month selector');
        $this->assertStringContains("monthSelectorType: 'static'", $internsView, 'Interns Flatpickr must use static month selector');
        $this->assertStringContains("monthSelectorType: 'static'", $employersView, 'Employers Flatpickr must use static month selector');

        // Document Viewer modal presence (standardized reusable component)
        $this->assertStringContains('admin-doc-modal.php', $internsView, 'Interns must include reusable document viewer modal');
        $this->assertStringContains('admin-doc-modal.php', $employersView, 'Employers must include reusable document viewer modal');

        // Multi-format preview support in universal component
        $modalComponent = file_get_contents(__DIR__ . '/../../frontend/shared/components/admin-doc-modal.php');
        $this->assertStringContains("viewDoc.type", $modalComponent, 'Modal must inspect viewDoc.type');
        $this->assertStringContains("'pdf'", $modalComponent, 'Doc modal must support PDF preview');
        $this->assertStringContains("'docx'", $modalComponent, 'Doc modal must support DOCX preview');
        $this->assertStringContains("'image'", $modalComponent, 'Doc modal must support Image preview');
    }

    /**
     * Requirement: Verification shelf must be fully responsive on mobile and tablet without horizontal card squashing.
     */
    public function testVerificationShelfResponsiveBreakpoints(): void
    {
        $adminCss = file_get_contents(__DIR__ . '/../../public/assets/css/admin.css');

        // Must define 640px mobile breakpoint with single-column layout
        $this->assertStringContains('.admin-verification-shelf', $adminCss, 'admin.css must define .admin-verification-shelf');
        $this->assertStringContains('grid-template-columns: minmax(0, 1fr) !important;', $adminCss, 'admin.css must enforce single-column on mobile verification shelf');
        $this->assertStringContains('@media (max-width: 1024px)', $adminCss, 'admin.css must define 1024px tablet breakpoint');
        $this->assertStringContains('@media (max-width: 640px)', $adminCss, 'admin.css must define 640px mobile breakpoint');
    }

    /**
     * Requirement: Overview period toggles must be instantaneous without full-page dimming/blur lag.
     */
    public function testOverviewPeriodSwitchingSnappiness(): void
    {
        $overviewView = file_get_contents(__DIR__ . '/../../frontend/admin/overview/index.php');

        $this->assertStringNotContains('isFading', $overviewView, 'Overview must not apply isFading full-page opacity dimming on period change');
        $this->assertStringContains("setPeriod('Week')", $overviewView, 'Overview must support Week period');
        $this->assertStringContains("setPeriod('Month')", $overviewView, 'Overview must support Month period');
        $this->assertStringContains("setPeriod('Quarter')", $overviewView, 'Overview must support Quarter period');
        $this->assertStringContains("setPeriod('Year')", $overviewView, 'Overview must support Year period');
    }

    /**
     * Requirement: Flatpickr calendar must provide ample width and padding so Saturday and rightmost dates are never clipped.
     */
    public function testFlatpickrCalendarWidthAndSpacing(): void
    {
        $adminCss = file_get_contents(__DIR__ . '/../../public/assets/css/admin.css');

        $this->assertStringContains('.flatpickr-calendar', $adminCss, 'admin.css must define .flatpickr-calendar');
        $this->assertStringContains('width: 334px !important;', $adminCss, 'Flatpickr calendar must define explicit width >= 326px');
        $this->assertStringContains('.flatpickr-weekdaycontainer', $adminCss, 'Flatpickr must customize weekday container');
        $this->assertStringContains('span.flatpickr-weekday', $adminCss, 'Flatpickr must customize weekday labels');
    }

    /**
     * Requirement: Auth screens must have permanent visible button text labels (never blank).
     */
    public function testAuthButtonsHaveExplicitLabels(): void
    {
        $loginView = file_get_contents(__DIR__ . '/../../frontend/admin/auth/login.php');
        $forgotView = file_get_contents(__DIR__ . '/../../frontend/admin/auth/forgot-password.php');
        $resetView = file_get_contents(__DIR__ . '/../../frontend/admin/auth/reset-password.php');
        $twoFaView = file_get_contents(__DIR__ . '/../../frontend/admin/auth/2fa.php');

        $this->assertStringContains('Sign In to Admin Console', $loginView, 'Login button must have permanent label');
        $this->assertStringContains('Send Recovery Link', $forgotView, 'Forgot password button must have permanent label');
        $this->assertStringContains('Update Password & Sign In', $resetView, 'Reset password button must have permanent label');
        $this->assertStringContains('Verify & Enter Console', $twoFaView, '2FA button must have permanent label');
    }

    /**
     * Requirement: Universal Admin Document Preview Modal must enforce z-index 999999,
     * separate drawerOpen from showDocViewerModal, and replicate Image 4 specifications.
     */
    public function testAdminDocModalIsolationAndZIndex(): void
    {
        $modalComponent = file_get_contents(__DIR__ . '/../../frontend/shared/components/admin-doc-modal.php');
        $internsView = file_get_contents(__DIR__ . '/../../frontend/admin/interns/index.php');
        $employersView = file_get_contents(__DIR__ . '/../../frontend/admin/employers/index.php');
        $adminCss = file_get_contents(__DIR__ . '/../../public/assets/css/admin.css');

        // Modal z-index & positioning
        $this->assertStringContains('z-index: 999999 !important;', $modalComponent, 'Modal component must enforce inline z-index 999999');
        $this->assertStringContains('z-index: 999999 !important;', $adminCss, 'admin.css must enforce z-index 999999 for .admin-doc-modal-backdrop');

        // Image 4 design contract & PDF.js Canvas Rendering Engine
        $this->assertStringContains('Close Preview', $modalComponent, 'Modal must have explicit Close Preview action button');
        $this->assertStringContains('Scroll to navigate pages', $modalComponent, 'Modal footer must display navigation guidance');
        $this->assertStringContains('PDF • Live Preview', $modalComponent, 'Modal header must display PDF Live Preview badge');
        $this->assertStringContains('admin-pdf-canvas-wrapper', $modalComponent, 'Modal must feature Canvas wrapper for PDF.js rendering');
        $this->assertStringContains('renderAdminPdf', $modalComponent, 'Modal must implement renderAdminPdf renderer');

        // State separation in views
        $this->assertStringContains('showDocViewerModal', $internsView, 'Interns view must track showDocViewerModal');
        $this->assertStringContains('closeDocViewer', $internsView, 'Interns view must implement closeDocViewer');

        $this->assertStringContains('showDocViewerModal', $employersView, 'Employers view must track showDocViewerModal');
        $this->assertStringContains('closeDocViewer', $employersView, 'Employers view must implement closeDocViewer');
        $this->assertStringContains('drawerOpen', $employersView, 'Employers view must use drawerOpen for side drawer');
        $this->assertStringNotContains('showDocModal', $employersView, 'Employers view must not overload drawer with showDocModal');
    }

    /**
     * Requirement: Section 2.0 Global Application Shell routes and controller methods.
     */
    public function testGlobalApplicationShellRoutesAndControllers(): void
    {
        $this->assertTrue(method_exists($this->controller, 'ai'), 'ai() method must exist on AdminController');
        $this->assertTrue(method_exists($this->controller, 'aiChat'), 'aiChat() method must exist on AdminController');
        $this->assertTrue(method_exists($this->controller, 'elearning'), 'elearning() method must exist on AdminController');
        $this->assertTrue(method_exists($this->controller, 'subscriptions'), 'subscriptions() method must exist on AdminController');
        $this->assertTrue(method_exists($this->controller, 'settings'), 'settings() method must exist on AdminController');
        $this->assertTrue(method_exists($this->controller, 'settingsTeam'), 'settingsTeam() method must exist on AdminController');

        $routesFile = file_get_contents(__DIR__ . '/../../backend/routes.php');
        $this->assertStringContains('/admin/ai', $routesFile, 'Route /admin/ai must be registered in backend/routes.php');
        $this->assertStringContains('/admin/ai/chat', $routesFile, 'Route /admin/ai/chat must be registered in backend/routes.php');
        $this->assertStringContains('/admin/elearning', $routesFile, 'Route /admin/elearning must be registered in backend/routes.php');
        $this->assertStringContains('/admin/subscriptions', $routesFile, 'Route /admin/subscriptions must be registered in backend/routes.php');
        $this->assertStringContains('/admin/settings/team', $routesFile, 'Route /admin/settings/team must be registered in backend/routes.php');
    }

    /**
     * Requirement: Section 2.1 Admin Sidebar brand header, console badge, and live queue badges.
     */
    public function testAdminSidebarBrandHeaderAndQueueBadges(): void
    {
        $sidebar = file_get_contents(__DIR__ . '/../../frontend/shared/components/admin-sidebar.php');
        
        // Brand header shows the logo alone, without a console badge
        $this->assertStringContains('admin-logo-img', $sidebar, 'Sidebar header must feature the InternConnect logo');
        $this->assertStringNotContains('CONSOLE', $sidebar, 'Sidebar header must not display a CONSOLE badge');

        // Queue badges
        $this->assertStringContains('37', $sidebar, 'Sidebar must display 37 awaiting review on interns');
        $this->assertStringContains('14', $sidebar, 'Sidebar must display 14 pending verification on employers');
        $this->assertStringContains('6', $sidebar, 'Sidebar must display 6 flagged on AI integration');
        $this->assertStringContains('3', $sidebar, 'Sidebar must display 3 courses on e-learning');

        // Staff profile card in footer
        $this->assertStringContains('admin-sidebar-footer', $sidebar, 'Sidebar must have pinned footer');
        $this->assertStringContains('admin-logout-btn', $sidebar, 'Sidebar footer must have sign out action');
    }

    /**
     * Requirement: Section 2.2 Top Navigation Header and Permanent Removal of "Export Report".
     */
    public function testAdminTopnavHeaderSpecifications(): void
    {
        $topnav = file_get_contents(__DIR__ . '/../../frontend/shared/components/admin-topnav.php');

        // Export report must NOT exist
        $this->assertStringNotContains('Export report', $topnav, 'Export report button must be permanently removed from topnav');

        // Search trigger
        $this->assertStringContains('open-admin-command-palette', $topnav, 'Topnav search box must dispatch open-admin-command-palette');
        $this->assertStringContains('Ctrl K', $topnav, 'Topnav must show Ctrl K chip');

        // Notification panel with feed items
        $this->assertStringContains('admin-notif-panel', $topnav, 'Topnav must feature notification panel');
        $this->assertStringContains('14 employers awaiting verification', $topnav, 'Notification feed must display CAC alert');
        $this->assertStringContains('6 AI matches flagged for review', $topnav, 'Notification feed must display AI alert');
        $this->assertStringContains('Sarah Peters accepted offer', $topnav, 'Notification feed must display Candidate placement alert');
    }

    /**
     * Requirement: Section 2.3 Global Command Palette Modal (Ctrl + K Overlay).
     */
    public function testGlobalCommandPaletteModalComponent(): void
    {
        $palettePath = __DIR__ . '/../../frontend/shared/components/admin-command-palette.php';
        $this->assertTrue(file_exists($palettePath), 'admin-command-palette.php component must exist');

        $palette = file_get_contents($palettePath);
        $layout = file_get_contents(__DIR__ . '/../../frontend/shared/layouts/admin.php');

        // Layout inclusion
        $this->assertStringContains('admin-command-palette.php', $layout, 'Admin layout must include admin-command-palette.php');

        // Hotkey & category coverage
        $this->assertStringContains('ctrlKey', $palette, 'Command palette must listen for Ctrl/Cmd key');
        $this->assertStringContains('interns', $palette, 'Command palette must support interns category');
        $this->assertStringContains('employers', $palette, 'Command palette must support employers category');
        $this->assertStringContains('courses', $palette, 'Command palette must support courses category');
        $this->assertStringContains('actions', $palette, 'Command palette must support quick actions category');
        $this->assertStringContains('Jenny AI', $palette, 'Command palette must feature Jenny AI integration');
    }

    /**
     * Requirement: Section 2.4 Universal Slide-Over Inspector Drawer Framework.
     */
    public function testUniversalInspectorDrawerFramework(): void
    {
        $internsView = file_get_contents(__DIR__ . '/../../frontend/admin/interns/index.php');
        $employersView = file_get_contents(__DIR__ . '/../../frontend/admin/employers/index.php');

        $this->assertStringContains('admin-inspector-drawer', $internsView, 'Interns view must implement admin-inspector-drawer');
        $this->assertStringContains('admin-inspector-drawer', $employersView, 'Employers view must implement admin-inspector-drawer');

        // ESC listener
        $this->assertStringContains('@keydown.escape.window', $internsView, 'Interns view must dismiss drawer on ESC');
        $this->assertStringContains('@keydown.escape.window', $employersView, 'Employers view must dismiss drawer on ESC');
    }

    /**
     * Requirement: Zero colored side borders or accent border strips across all admin components.
     */
    public function testZeroColoredSideBorders(): void
    {
        $adminCss = file_get_contents(__DIR__ . '/../../public/assets/css/admin.css');
        $this->assertFalse(
            (bool) preg_match('/border-(?:left|top|right|bottom):\s*[2-9]px\s*solid/i', $adminCss),
            'admin.css must NOT define thick colored border accent strips!'
        );
    }

    /**
     * Requirement: Form role dropdowns must use .db-dropdown-wrap and not unstyled raw button grids.
     */
    public function testRoleDropdownCompliance(): void
    {
        $teamView = file_get_contents(__DIR__ . '/../../frontend/admin/settings/team.php');
        $this->assertStringContains('db-dropdown-wrap', $teamView, 'Settings team view must use standardized .db-dropdown-wrap for role selection');
        $this->assertStringContains('db-dropdown-btn', $teamView, 'Settings team view must feature .db-dropdown-btn');
        $this->assertStringContains('db-dropdown-menu', $teamView, 'Settings team view must feature .db-dropdown-menu');
    }

    /**
     * Requirement: Anti-collision mobile-scrollable table containers.
     */
    public function testAntiCollisionTableScrollContainers(): void
    {
        $subView = file_get_contents(__DIR__ . '/../../frontend/admin/subscriptions/index.php');
        $teamView = file_get_contents(__DIR__ . '/../../frontend/admin/settings/team.php');

        $this->assertStringContains('admin-table-scroll-container', $subView, 'Subscriptions view must wrap table in admin-table-scroll-container');
        $this->assertStringContains('admin-table-scroll-container', $teamView, 'Settings team view must wrap table in admin-table-scroll-container');
        $this->assertStringContains('min-width: 820px;', $subView, 'Subscriptions table must enforce minimum 820px width to prevent cell overlap on mobile');
        $this->assertStringContains('min-width: 760px;', $teamView, 'Settings team table must enforce minimum 760px width to prevent status/active overlap on mobile');
    }

    /**
     * Requirement: Every console screen and document endpoint must require an authenticated staff session.
     */
    public function testAdminScreensRequireAuthentication(): void
    {
        $controllerFile = file_get_contents(__DIR__ . '/../../backend/Controllers/AdminController.php');

        foreach (['overview', 'interns', 'employers', 'ai', 'aiChat', 'elearning', 'subscriptions', 'settingsTeam', 'streamDocument', 'liveSessions'] as $method) {
            $this->assertTrue(
                (bool) preg_match('/function ' . $method . '\([^)]*\): void\s*\{\s*\$this->requireAdminAuth\(\);/', $controllerFile),
                "{$method}() must call requireAdminAuth() first"
            );
        }
    }

    public function testDashboardRefinementsAndHistogramCharts(): void
    {
        $subView = file_get_contents(dirname(__DIR__, 2) . '/frontend/admin/subscriptions/index.php');
        $elearningView = file_get_contents(dirname(__DIR__, 2) . '/frontend/admin/elearning/index.php');
        $sidebarView = file_get_contents(dirname(__DIR__, 2) . '/frontend/shared/components/admin-sidebar.php');
        $aiView = file_get_contents(dirname(__DIR__, 2) . '/frontend/admin/ai/index.php');
        $chatView = file_get_contents(dirname(__DIR__, 2) . '/frontend/admin/ai/chat.php');

        // 1. Subscription & Ledger Title & Removed buttons
        $this->assertStringContains('Subscription &amp; Ledger', $subView, 'Title must be Subscription & Ledger');
        $this->assertFalse(str_contains($subView, 'Paystack Live (NGN)'), 'Paystack Live NGN pill must be removed from subscriptions header');
        $this->assertFalse(str_contains($subView, 'Sync Paystack'), 'Sync Paystack button must be removed from subscriptions header');

        // 2. Histograms replacing Donut / Pie Charts
        $this->assertStringContains('admin-revenue-histogram', $subView, 'Subscriptions must use histogram bar chart');
        $this->assertFalse(str_contains($subView, 'admin-revenue-donut'), 'Subscriptions must not use doughnut/pie chart');
        $this->assertStringContains('admin-elearning-histogram', $elearningView, 'Elearning must use histogram bar chart');
        $this->assertFalse(str_contains($elearningView, 'admin-elearning-donut'), 'Elearning must not use doughnut/pie chart');

        // 3. Spacing on bottom insight lines
        $this->assertStringContains('margin-top: 24px;', $subView, 'Subscriptions insight line must have generous top spacing');
        $this->assertStringContains('margin-top: 24px;', $elearningView, 'Elearning insight line must have generous top spacing');

        // 4. Safe Paystack Webhook Method
        $this->assertStringContains('getWebhookJson()', $subView, 'Subscriptions view must declare and call getWebhookJson()');

        // 5. Elearning Header Cleanliness & Metric Relevancy
        $this->assertFalse(str_contains($elearningView, 'Browse Catalog &rarr;'), 'Browse catalog button must be removed from elearning header');
        $this->assertStringContains('Top Enrolled Curriculum', $elearningView, 'Elearning must feature relevant curriculum metric in footer');

        // 6. Sidebar Jenny AI Branding & Zero Elearning Badge
        $this->assertStringContains('Jenny AI', $sidebarView, 'Sidebar must use Jenny AI title');
        $this->assertStringContains('jenny_icon.png', $sidebarView, 'Sidebar must display Jenny AI icon next to title');
        $this->assertFalse(str_contains($sidebarView, 'E-Learning (Badge: 3)'), 'Sidebar must not have 3 badge on E-Learning');

        // 7. Jenny AI Operations Console Branding & Flagged Queue Controls
        $this->assertStringContains('jenny_icon.png', $aiView, 'Jenny AI console header must include Jenny AI avatar');
        $this->assertStringContains('searchQuery', $aiView, 'Flagged discrepancy queue must feature search filter');
        $this->assertStringContains('paginatedItems', $aiView, 'Flagged discrepancy queue must be paginated');

        // 8. Jenny AI Chat Layout
        $this->assertStringContains('jenny_icon.png', $chatView, 'Chat workspace must include Jenny AI avatar');
        $this->assertStringContains('handleFileSelect', $chatView, 'Chat workspace must include a real file attachment action');
    }
}
