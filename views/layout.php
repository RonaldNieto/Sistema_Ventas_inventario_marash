<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MARASH | <?= e(ucfirst($page)) ?></title>
    <link rel="stylesheet" href="frontend/css/style.css">
</head>
<body>
<?php
$publicStore = in_array($page, ['tienda', 'carrito'], true);
$authPage = in_array($page, ['login', 'registro'], true);
?>
<?php if ($authPage): ?>
    <?php require __DIR__ . '/auth/' . $page . '.php'; ?>
<?php elseif ($publicStore): ?>
    <div class="store-shell">
        <header class="store-header">
            <a class="store-logo" href="?page=tienda">MARASH<span>✦</span></a>
            <nav class="store-nav" aria-label="Navegación de tienda">
                <a href="?page=tienda">Colección</a>
                <a href="?page=carrito">Mi carrito (<?= array_sum($_SESSION['carrito'] ?? []) ?>)</a>
                <?php if (empty($_SESSION['uid'])): ?>
                    <a href="?page=login">Ingresar</a>
                    <a class="store-signup" href="?page=registro">Crear cuenta</a>
                <?php elseif (($_SESSION['role'] ?? '') === 'cliente'): ?>
                    <a href="?page=pedidos">Mis pedidos</a>
                    <a href="?logout=1">Salir</a>
                <?php else: ?>
                    <a href="?page=dashboard">Panel administrativo</a>
                    <a href="?logout=1">Salir</a>
                <?php endif; ?>
            </nav>
        </header>
        <main class="store-content">
            <?php if ($error): ?>
                <div class="flash error"><?= e($error) ?></div>
            <?php endif; ?>
            <?php if (!empty($_SESSION['flash'])): ?>
                <div class="flash"><?= e($_SESSION['flash']) ?></div>
                <?php unset($_SESSION['flash']); ?>
            <?php endif; ?>
            <?php try { ?>
                <?php require __DIR__ . '/' . $page . '/index.php'; ?>
            <?php } catch (Throwable $exception) { ?>
                <?php error_log((string) $exception); ?>
                <div class="flash error">No se pudo cargar la tienda. Comprueba la conexión a MySQL.</div>
            <?php } ?>
        </main>
        <footer class="store-footer">MARASH · Moda para tu estilo · Tienda virtual</footer>
    </div>
<?php else: ?>
    <div class="app">
        <aside class="sidebar">
            <h2>Marash</h2>
            <nav>
                <?php
                $menus = ($_SESSION['role'] ?? '') === 'cliente'
                    ? ['tienda' => 'Tienda', 'carrito' => 'Carrito', 'pedidos' => 'Mis pedidos']
                    : (($_SESSION['role'] ?? '') === 'vendedor'
                        ? ['dashboard' => 'Inicio', 'ventas' => 'Ventas', 'productos' => 'Productos', 'clientes' => 'Clientes', 'proveedores' => 'Proveedores']
                        : ['dashboard' => 'Inicio', 'ventas' => 'Ventas', 'inventario' => 'Inventario', 'productos' => 'Productos', 'clientes' => 'Clientes', 'reportes' => 'Reportes', 'proveedores' => 'Proveedores', 'usuarios' => 'Usuarios', 'configuracion' => 'Configuración']);
                foreach ($menus as $key => $label):
                ?>
                    <a class="<?= $page === $key ? 'active' : '' ?>" href="?page=<?= e($key) ?>"><?= e($label) ?></a>
                <?php endforeach; ?>
                <a href="?page=tienda">Ver tienda virtual</a>
            </nav>
            <a class="logout" href="?logout=1">Cerrar sesión</a>
        </aside>
        <main class="main">
            <header class="topbar">
                <button id="menuBtn" class="icon-btn" type="button">☰</button>
                <div>
                    <strong>Bienvenido, <?= e($_SESSION['name'] ?? 'Usuario') ?></strong>
                    <small>Gestiona tu tienda de ropa · Marash</small>
                </div>
                <div class="top-right"><span class="avatar"><?= e(mb_strtoupper(mb_substr((string) ($_SESSION['name'] ?? 'M'), 0, 2))) ?></span></div>
            </header>
            <div class="content-wrap">
                <?php if ($error): ?><div class="flash error"><?= e($error) ?></div><?php endif; ?>
                <?php if (!empty($_SESSION['flash'])): ?>
                    <div class="flash"><?= e($_SESSION['flash']) ?></div>
                    <?php unset($_SESSION['flash']); ?>
                <?php endif; ?>
                <?php
                try {
                    extract(pageData($page), EXTR_SKIP);
                    require __DIR__ . '/' . $page . '/index.php';
                } catch (Throwable $exception) {
                    error_log((string) $exception);
                    echo '<div class="flash error">No se pudo cargar el módulo. Verifica la conexión a MariaDB.</div>';
                }
                ?>
            </div>
            <div class="footer-note">Marash · Sistema de gestión comercial · PHP + MariaDB</div>
        </main>
    </div>
<?php endif; ?>
<script src="frontend/js/app.js"></script>
</body>
</html>
