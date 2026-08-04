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
}
