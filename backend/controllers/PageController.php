<?php
declare(strict_types=1);
function pageData(string $page): array {
    return match ($page) {
        'dashboard' => dashboardData(),
        'ventas' => salesPageData(),
        'inventario' => inventoryPageData(),
        'reportes' => reportsPageData($_GET),
        default => [],
    };
}

function catalogPageData(string $type): array {
    $tables=['product'=>'products','client'=>'clients','supplier'=>'suppliers'];
    if (!isset($tables[$type])) throw new InvalidArgumentException('Tipo inválido');
    $table=$tables[$type];
    return ['rows'=>allRows($table),'edit'=>isset($_GET['edit'])?oneRow($table,(int)$_GET['edit']):null];
}
