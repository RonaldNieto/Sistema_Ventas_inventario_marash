<?php
declare(strict_types=1);

require_once __DIR__ . '/servidor/configuracion/inicio.php';

require_once __DIR__ . '/servidor/configuracion/instalacion.php';

try {
    prepararBaseDatos();
} catch (Throwable $e) {
    error_log($e->getMessage());
    http_response_code(503);
    exit('<h1>MARASH</h1><p>Comprueba que MySQL esté iniciado y que la conexión sea correcta. Si existe una instalación incompleta, revisa HeidiSQL antes de continuar.</p>');
}

$error = '';

try {
    dispatchPost();
} catch (Throwable $exception) {
    $error = $exception instanceof PDOException
        ? 'No fue posible completar la operación. Revisa los datos e inténtalo nuevamente.'
        : $exception->getMessage();
}

if (isset($_GET['logout'])) {
    $_SESSION = [];
    session_destroy();
    header('Location: index.php?page=tienda');
    exit;
}

if (($_GET['continuar'] ?? '') === 'compra') {
    $_SESSION['continuar_compra'] = true;
}

$role = (string) ($_SESSION['role'] ?? '');
$loggedIn = !empty($_SESSION['uid']);
$defaultPage = !$loggedIn || $role === 'cliente' ? 'tienda' : 'panel';
$page = (string) ($_GET['page'] ?? $defaultPage);

$pages = [
    'panel', 'productos', 'clientes', 'ventas', 'inventario',
    'reportes', 'proveedores', 'configuracion', 'usuarios',
    'tienda', 'carrito', 'pedidos', 'registro', 'login'
];

if (!in_array($page, $pages, true)) {
    $page = $defaultPage;
}

$publicPages = ['tienda', 'carrito', 'registro', 'login'];

if (!$loggedIn && !in_array($page, $publicPages, true)) {
    $page = 'login';
} elseif ($role === 'cliente' && !in_array($page, ['tienda', 'carrito', 'pedidos'], true)) {
    $page = 'tienda';
} elseif ($role === 'vendedor' && in_array($page, ['usuarios', 'reportes', 'configuracion', 'inventario'], true)) {
    $page = 'panel';
} elseif ($loggedIn && $role !== 'admin' && $page === 'usuarios') {
    $page = $defaultPage;
}

require __DIR__ . '/vistas/plantilla.php';
