<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;

class HomeController extends Controller
{
    public function index(): void
    {
        $this->renderView('landing/index', [
            'title' => 'Home - InternConnect'
        ]);
    }

    public function pricing(): void
    {
        $this->renderView('landing/pricing', [
            'title' => 'Pricing - InternConnect'
        ]);
    }

    public function payments(): void
    {
        $plan = $_GET['plan'] ?? 'pro';
        $this->renderView('landing/payment', [
            'title' => 'Checkout - InternConnect',
            'plan' => htmlspecialchars((string)$plan)
        ]);
    }
}
