<?php
declare(strict_types=1);
require_once __DIR__.'/../configuracion/conexion.php';
require_once __DIR__.'/../modelos/ModeloGestion.php';
require_once __DIR__.'/../modelos/ModeloTienda.php';
require_once __DIR__.'/../modelos/ModeloAutenticacion.php';
require_once __DIR__.'/../modelos/ModeloPanel.php';
require_once __DIR__.'/../modelos/ModeloVentas.php';
require_once __DIR__.'/../modelos/ModeloInventario.php';
require_once __DIR__.'/../modelos/ModeloReportes.php';
require_once __DIR__.'/../controladores/ControladorPaginas.php';
require_once __DIR__.'/../controladores/ControladorAutenticacion.php';
require_once __DIR__.'/../controladores/ControladorGestion.php';
require_once __DIR__.'/../controladores/ControladorTienda.php';
function redirectPage(string $page): never {header('Location: index.php?page='.rawurlencode($page));exit;}
function dispatchPost(): void
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        return;
    }

    $token = (string) ($_POST['csrf'] ?? '');
    if (!hash_equals($_SESSION['csrf'] ?? '', $token)) {
        throw new RuntimeException('Sesión caducada. Actualiza la página.');
    }

    $action = (string) ($_POST['action'] ?? '');

    if ($action === 'carrito') {
        actualizarCarrito();
        redirectPage('carrito');
    }

    if ($action === 'registro_cliente') {
        registrarDesdeFormulario();
        redirectPage('login');
    }

    if ($action === 'login') {
        authLogin();
        $destino = ($_SESSION['role'] ?? '') === 'cliente' ? 'tienda' : 'panel';
        if ($destino === 'tienda' && !empty($_SESSION['continuar_compra'])) {
            $destino = 'carrito';
            unset($_SESSION['continuar_compra']);
        }
        redirectPage($destino);
    }

    if ($action === 'crear_pedido') {
        if (empty($_SESSION['uid'])) {
            $_SESSION['continuar_compra'] = true;
            redirectPage('login');
        }
        requireRole(['cliente']);
        $pedidoId = crearPedidoOnline((int) $_SESSION['uid'], $_SESSION['carrito'] ?? []);
        $_SESSION['carrito'] = [];
        $_SESSION['flash'] = 'Pedido #' . $pedidoId . ' creado. Pago pendiente.';
        redirectPage('pedidos');
    }

    requireLogin();

    if ($action === 'create_seller') {
        createSellerFromForm();
        $_SESSION['flash'] = 'Vendedor creado correctamente.';
        redirectPage('usuarios');
    }

    requireRole(['admin', 'vendedor']);

    $pages = ['product' => 'productos', 'client' => 'clientes', 'supplier' => 'proveedores'];

    if ($action === 'save') {
        requireRole(['admin']);
        $type = (string) ($_POST['type'] ?? '');
        saveRecord($type);
        $page = $pages[$type] ?? 'panel';
    } elseif ($action === 'delete') {
        requireRole(['admin']);
        $type = (string) ($_POST['type'] ?? '');
        deleteRecord($type);
        $page = $pages[$type] ?? 'panel';
    } elseif ($action === 'stock') {
        requireRole(['admin']);
        adjustStock();
        $page = 'inventario';
    } elseif ($action === 'sale') {
        createSale();
        $page = 'ventas';
    } else {
        throw new RuntimeException('Acción desconocida.');
    }

    $_SESSION['flash'] = 'Cambios guardados correctamente.';
    redirectPage($page);
}
