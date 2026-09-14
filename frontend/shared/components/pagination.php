<?php
declare(strict_types=1);
/**
 * Shared Pagination Component
 * 
 * Supports both server-side link-based routing and interactive Alpine.js client-side lists.
 * Matches visual specs: "Showing X of Y [items]" with responsive prev/next chevrons and numbered buttons.
 * 
 * @var int         $currentPage    Current active page (default: 1)
 * @var int         $totalItems     Total items count (default: 12)
 * @var int         $perPage        Items per page (default: 6)
 * @var string      $itemName       Plural label (default: 'applications')
 * @var string      $baseUrl        Link base url (default: '')
 * @var array       $queryParams    Query parameters to preserve (default: [])
 * @var array       $options        Extra configuration (default: [])
 */

$currentPage = $currentPage ?? 1;
$totalItems  = $totalItems ?? 12;
$perPage     = $perPage ?? 6;
$itemName    = $itemName ?? 'applications';
$baseUrl     = $baseUrl ?? '';
$queryParams = $queryParams ?? [];
$options     = $options ?? [];

echo render_pagination($currentPage, $totalItems, $perPage, $itemName, $baseUrl, $queryParams, $options);
