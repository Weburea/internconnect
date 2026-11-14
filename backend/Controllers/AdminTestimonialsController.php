<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Services\CloudinaryService;
use App\Services\TestimonialService;

/**
 * Admin > Testimonials (docs/61-testimonials.md): quotes and video stories shown on the home page
 * section and on /testimonials. Avatars and videos upload to Cloudinary (InternConnect/images/testimonials, InternConnect/videos/testimonials).
 */
class AdminTestimonialsController extends AdminController
{
    private function flash(bool $ok, string $text): void
    {
        $_SESSION['admin_tm_flash'] = ['ok' => $ok, 'text' => $text];
    }

    public function index(): void
    {
        $this->requireAdminAuth();
        $flash = $_SESSION['admin_tm_flash'] ?? null;
        $old = $_SESSION['admin_tm_old'] ?? null;
        unset($_SESSION['admin_tm_flash'], $_SESSION['admin_tm_old']);
        $filter = in_array($_GET['show'] ?? '', ['home', 'page', 'video', 'hidden'], true) ? $_GET['show'] : 'all';
        $all = TestimonialService::list();
        $rows = array_values(array_filter($all, static fn ($t) => match ($filter) {
            'home'   => $t['show_home'] && $t['is_active'],
            'page'   => $t['show_page'] && $t['is_active'],
            'video'  => $t['kind'] === 'video',
            'hidden' => !$t['is_active'],
            default  => true,
        }));
        $editing = null;
        if (isset($_GET['edit'])) {
            $editing = (int)$_GET['edit'] > 0 ? TestimonialService::find((int)$_GET['edit']) : [];
        }
        $this->renderView('admin/testimonials/index', [
            'title'     => 'Testimonials — InternConnect Admin',
            'staff'     => $this->staffProfile(),
            'activeNav' => 'testimonials',
            'flash'     => $flash,
            'rows'      => $rows,
            'all'       => $all,
            'filter'    => $filter,
            'editing'   => $old ?: $editing,
        ], 'admin');
    }

    public function save(array $params = []): void
    {
        $this->requireAdminAuth();
        $id = isset($params['id']) ? (int)$params['id'] : null;
        $r = TestimonialService::save($_POST, $id);
        if (!$r['ok']) {
            $_SESSION['admin_tm_old'] = $_POST + ['id' => $id];
            $this->flash(false, $r['error']);
            $this->redirect('/admin/testimonials?edit=' . (int)$id);
            return;
        }
        $this->flash(true, 'Testimonial saved. It appears on the site straight away.');
        $this->redirect('/admin/testimonials');
    }

    public function toggle(array $params = []): void
    {
        $this->requireAdminAuth();
        TestimonialService::toggle((int)($params['id'] ?? 0), (string)($_POST['field'] ?? ''));
        $this->redirect('/admin/testimonials' . (!empty($_POST['show']) ? '?show=' . urlencode((string)$_POST['show']) : ''));
    }

    public function delete(array $params = []): void
    {
        $this->requireAdminAuth();
        $ok = TestimonialService::delete((int)($params['id'] ?? 0));
        $this->flash($ok, $ok ? 'Testimonial deleted.' : 'Testimonial not found.');
        $this->redirect('/admin/testimonials');
    }

    /** POST /admin/testimonials/upload (kind=image|video): Cloudinary upload, JSON. */
    public function upload(): void
    {
        $this->requireAdminAuth();
        $kind = ($_POST['kind'] ?? 'image') === 'video' ? 'video' : 'image';
        $r = CloudinaryService::upload($_FILES['file'] ?? null, $kind === 'video' ? 'InternConnect/videos/testimonials' : 'InternConnect/images/testimonials', $kind);
        $this->jsonResponse($r['ok'] ? ['success' => true, 'url' => $r['url']] : ['success' => false, 'error' => $r['error']], $r['ok'] ? 200 : 422);
    }
}
