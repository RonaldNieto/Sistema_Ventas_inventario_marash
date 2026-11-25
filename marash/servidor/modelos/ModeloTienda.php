<?php
declare(strict_types=1);

function tiendaProductos(): array
{
    return db()->query('SELECT id, name, sku, category, price, stock, sizes, colors, image_url FROM products WHERE stock > 0 ORDER BY id DESC')->fetchAll();
}

function tiendaProducto(int $id): ?array
{
    $sql = 'SELECT id, name, price, stock FROM products WHERE id = ?';
    $stmt = db()->prepare($sql);
    $stmt->execute([$id]);
    return $stmt->fetch() ?: null;
}

function registrarCliente(string $nombre, string $correo, string $clave): void
{
    $pdo = db();
    $pdo->beginTransaction();

    try {
        $stmt = $pdo->prepare('INSERT INTO users (username, email, full_name, password_hash, role, active) VALUES (?, ?, ?, ?, ?, 1)');
        $stmt->execute([$correo, $correo, $nombre, password_hash($clave, PASSWORD_DEFAULT), 'cliente']);

        $stmt = $pdo->prepare('SELECT id FROM clients WHERE email = ? LIMIT 1');
        $stmt->execute([$correo]);
        if (!$stmt->fetchColumn()) {
            $stmt = $pdo->prepare('INSERT INTO clients (name, email, phone) VALUES (?, ?, ?)');
            $stmt->execute([$nombre, $correo, '']);
        }

        $pdo->commit();
    } catch (Throwable $error) {
        $pdo->rollBack();
        throw $error;
    }
}

function pedidosDelCliente(int $usuarioId): array
{
    $stmt = db()->prepare('SELECT id, total, estado, created_at FROM online_orders WHERE user_id = ? ORDER BY id DESC');
    $stmt->execute([$usuarioId]);
    return $stmt->fetchAll();
}

function crearPedidoOnline(int $usuarioId, array $carrito): int
{
    if (!$carrito) {
        throw new RuntimeException('Tu carrito está vacío.');
    }

    $pdo = db();
    $pdo->beginTransaction();

    try {
        $total = 0;
        $detalles = [];

        foreach ($carrito as $id => $cantidad) {
            $stmt = $pdo->prepare('SELECT id, name, price, stock FROM products WHERE id = ? FOR UPDATE');
            $stmt->execute([(int) $id]);
            $producto = $stmt->fetch();

            if (!$producto || $cantidad < 1 || $cantidad > (int) $producto['stock']) {
                throw new RuntimeException('Una prenda ya no tiene existencias suficientes.');
            }

            $precio = (float) $producto['price'];
            $total += $precio * $cantidad;
            $detalles[] = [$producto['id'], $cantidad, $precio];
        }

        $stmt = $pdo->prepare('INSERT INTO online_orders (user_id, total, estado) VALUES (?, ?, ?)');
        $stmt->execute([$usuarioId, $total, 'pendiente_pago']);
        $pedidoId = (int) $pdo->lastInsertId();

        $stmt = $pdo->prepare('INSERT INTO online_order_items (order_id, product_id, quantity, unit_price) VALUES (?, ?, ?, ?)');
        foreach ($detalles as [$productoId, $cantidad, $precio]) {
            $stmt->execute([$pedidoId, $productoId, $cantidad, $precio]);
        }

        $pdo->commit();
        return $pedidoId;
    } catch (Throwable $error) {
        $pdo->rollBack();
        throw $error;
    }
}
