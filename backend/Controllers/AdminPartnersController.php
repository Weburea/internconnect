<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Services\ElearningService;
use App\Services\PartnerService;

/**
 * Admin > E-Learning > Training partners (docs/57-training-partners.md).
 * Partners, API keys, pass marks and tier rules, applications, results (manual add, revoke),
 * score distribution and the partner API log. Customer Care staff can look but not change.
 */
class AdminPartnersController extends AdminController
{
    private const TABS = ['partners', 'rules', 'applications', 'results', 'activity'];

    private function canEdit(): bool
    {
        return !in_array((string)($_SESSION['admin_role'] ?? 'Platform Admin'), ['Customer Care'], true);
    }

    private function guardEdit(string $tab): bool
    {
        if ($this->canEdit()) {
            return true;
        }
        $this->flash(false, 'Your role can view training partners but not change them.');
        $this->back($tab);
        return false;
    }

    private function flash(bool $ok, string $text): void
    {
        $_SESSION['admin_partner_flash'] = ['ok' => $ok, 'text' => $text];
    }

    private function back(string $tab, array $q = []): void
    {
        $this->redirect('/admin/elearning/partners?' . http_build_query(['tab' => $tab] + $q));
    }

    private function by(): string
    {
        return (string)($_SESSION['admin_email'] ?? 'admin');
    }

    public function index(): void
    {
        $this->requireAdminAuth();
        $tab = in_array($_GET['tab'] ?? '', self::TABS, true) ? $_GET['tab'] : 'partners';
        $flash = $_SESSION['admin_partner_flash'] ?? null;
        $newKeys = $_SESSION['admin_partner_keys'] ?? null;
        unset($_SESSION['admin_partner_flash'], $_SESSION['admin_partner_keys']);
        $partners = PartnerService::partners();
        $edit = isset($_GET['edit']) ? PartnerService::partner((int)$_GET['edit']) : null;
        $rulesFor = isset($_GET['partner']) && (int)$_GET['partner'] > 0 ? (int)$_GET['partner'] : null;
        $this->renderView('admin/elearning/partners', [
            'title'       => 'Training Partners — InternConnect Admin',
            'staff'       => $this->staffProfile(),
            'activeNav'   => 'elearning',
            'tab'         => $tab,
            'flash'       => $flash,
            'newKeys'     => $newKeys,
            'canEdit'     => $this->canEdit(),
            'partners'    => $partners,
            'edit'        => $edit,
            'directory'   => ElearningService::adminPartners(),
            'rulesFor'    => $rulesFor,
            'rules'       => PartnerService::rulesFor($rulesFor),
            'hasOwnRules' => $rulesFor ? (bool)array_filter(PartnerService::rulesFor($rulesFor), static fn ($r) => (int)$r['partner_id'] === $rulesFor) : false,
            'applications'=> $tab === 'applications' ? PartnerService::applications(100) : [],
            'results'     => $tab === 'results' ? PartnerService::results(100) : [],
            'distribution'=> PartnerService::distribution(),
            'auditLog'    => $tab === 'activity' ? PartnerService::auditLog(100) : [],
            'apiBase'     => absolute_url('/api/v1/partners'),
        ], 'admin');
    }

    public function savePartner(array $params = []): void
    {
        $this->requireAdminAuth();
        if (!$this->guardEdit('partners')) {
            return;
        }
        $id = isset($params['id']) ? (int)$params['id'] : null;
        $r = PartnerService::savePartner($_POST, $id);
        $this->flash($r['ok'], $r['ok'] ? 'Partner saved.' : $r['error']);
        $this->back('partners', $r['ok'] ? [] : ($id ? ['edit' => $id] : []));
    }

    public function rotateKeys(array $params = []): void
    {
        $this->requireAdminAuth();
        if (!$this->guardEdit('partners')) {
            return;
        }
        $id = (int)($params['id'] ?? 0);
        $p = PartnerService::partner($id);
        if (!$p) {
            $this->flash(false, 'Partner not found.');
            $this->back('partners');
            return;
        }
        try {
            // Shown once on the next page view, never stored in plain text.
            $_SESSION['admin_partner_keys'] = ['partner' => $p['name'], 'id' => $id] + PartnerService::rotateKeys($id);
            $this->flash(true, 'New API key and signing secret created. The previous key stopped working.');
        } catch (\RuntimeException $e) {
            $this->flash(false, $e->getMessage());
        }
        $this->back('partners');
    }

    public function saveRules(): void
    {
        $this->requireAdminAuth();
        $partnerId = (int)($_POST['partner_id'] ?? 0) ?: null;
        if (!$this->guardEdit('rules')) {
            return;
        }
        if (!empty($_POST['use_defaults']) && $partnerId) {
            // Switch this partner back to the global rules.
            $r = PartnerService::saveRules($partnerId, []);
        } else {
            $rows = [];
            foreach ((array)($_POST['rules'] ?? []) as $row) {
                $rows[] = (array)$row;
            }
            $r = PartnerService::saveRules($partnerId, $rows);
        }
        $this->flash($r['ok'], $r['ok'] ? 'Tier rules saved. New results use them straight away; existing results keep the plan they earned.' : $r['error']);
        $this->redirect('/admin/elearning/partners?' . http_build_query(['tab' => 'rules'] + ($partnerId ? ['partner' => $partnerId] : [])));
    }

    public function addResult(): void
    {
        $this->requireAdminAuth();
        if (!$this->guardEdit('results')) {
            return;
        }
        $app = PartnerService::application((string)($_POST['reference'] ?? ''));
        $partner = $app ? PartnerService::partner((int)$app['partner_id']) : null;
        if (!$app || !$partner) {
            $this->flash(false, 'No application has that reference.');
            $this->back('results');
            return;
        }
        try {
            $r = PartnerService::recordResult($partner, $app, (float)($_POST['score'] ?? -1), [
                'course' => $_POST['course'] ?? null, 'certificate_id' => $_POST['certificate_id'] ?? null, 'certificate_url' => $_POST['certificate_url'] ?? null,
            ], 'manual', $this->by());
            $plan = $r['result']['plan'] ? ucfirst($r['result']['plan']) . ' for ' . $r['result']['plan_days'] . ' days' : 'no plan';
            $this->flash(true, 'Result ' . $r['outcome'] . ' for ' . $app['reference'] . ': ' . $r['result']['score'] . '% (' . ($r['result']['passed'] ? 'passed, ' . $plan : 'below the pass mark') . ').');
        } catch (\InvalidArgumentException $e) {
            $this->flash(false, $e->getMessage());
        }
        $this->back('results');
    }

    public function revokeResult(array $params = []): void
    {
        $this->requireAdminAuth();
        if (!$this->guardEdit('results')) {
            return;
        }
        $ok = PartnerService::revokeResult((int)($params['id'] ?? 0), $this->by());
        $this->flash($ok, $ok ? 'Result revoked and its plan grant ended.' : 'That result is not active.');
        $this->back('results');
    }
}
