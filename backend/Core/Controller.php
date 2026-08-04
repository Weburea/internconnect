<?php

declare(strict_types=1);

namespace App\Core;

class Controller
{
    protected function renderView(string $view, array $data = [], string $layout = 'app'): void
    {
        // Extract variables to scope
        extract($data);

        // Capture view content
        $viewFile = dirname(__DIR__, 2) . '/frontend/' . $view . '.php';

        if (!file_exists($viewFile)) {
            http_response_code(500);
            echo "View not found: " . htmlspecialchars($view);
            return;
        }

        ob_start();
        include $viewFile;
        $content = ob_get_clean();

        // Render layout
        $layoutFile = dirname(__DIR__, 2) . '/frontend/shared/layouts/' . $layout . '.php';
        if (file_exists($layoutFile)) {
            include $layoutFile;
        } else {
            echo $content;
        }
    }

    protected function jsonResponse(array $data, int $statusCode = 200): void
    {
        header('Content-Type: application/json; charset=utf-8');
        http_response_code($statusCode);
        echo json_encode($data);
    }
}
