<?php
declare(strict_types=1);

function registrarDesdeFormulario(): void
{
    $nombre = trim((string) ($_POST['nombre'] ?? ''));
    $correo = mb_strtolower(trim((string) ($_POST['correo'] ?? '')));
    $clave = (string) ($_POST['clave'] ?? '');

    if ($nombre === '' || !filter_var($correo, FILTER_VALIDATE_EMAIL) || strlen($clave) < 8) {
        throw new RuntimeException('Escribe tu nombre, correo válido y una contraseña de mínimo 8 caracteres.');
    }

    registrarCliente($nombre, $correo, $clave);
    $_SESSION['flash'] = 'Cuenta creada. Ya puedes iniciar sesión.';
}

function actualizarCarrito(): void
{
    $productoId = filter_var($_POST['producto_id'] ?? null, FILTER_VALIDATE_INT);
    $cantidad = filter_var($_POST['cantidad'] ?? null, FILTER_VALIDATE_INT);

    if (!$productoId || $cantidad === false || $cantidad < 0 || $cantidad > 100) {
        throw new RuntimeException('Cantidad inválida.');
    }

    $producto = tiendaProducto($productoId);
    if (!$producto || ($cantidad > 0 && $cantidad > (int) $producto['stock'])) {
        throw new RuntimeException('Producto no disponible en esa cantidad.');
    }

    if ($cantidad === 0) {
        unset($_SESSION['carrito'][$productoId]);
    } else {
        $actual = (int) ($_SESSION['carrito'][$productoId] ?? 0);
        $nuevaCantidad = $actual + $cantidad;

        if ($nuevaCantidad > (int) $producto['stock']) {
            throw new RuntimeException('No hay suficientes unidades disponibles.');
        }

        $_SESSION['carrito'][$productoId] = $nuevaCantidad;
    }
}

function detalleCarrito(): array
{
    $lineas = [];
    $total = 0;

    foreach ($_SESSION['carrito'] ?? [] as $id => $cantidad) {
        $producto = tiendaProducto((int) $id);
        if (!$producto) {
            continue;
        }

        $subtotal = (float) $producto['price'] * (int) $cantidad;
        $total += $subtotal;
        $lineas[] = ['producto' => $producto, 'cantidad' => (int) $cantidad, 'subtotal' => $subtotal];
    }

    return ['lineas' => $lineas, 'total' => $total];
}
