<?php
$carrito = detalleCarrito();
$esCliente = ($_SESSION['role'] ?? '') === 'cliente';
?>
<div class="store-catalog-heading">
    <div>
        <span class="store-eyebrow">TU SELECCIÓN</span>
        <h1>Mi carrito</h1>
        <p>Revisa tus prendas antes de continuar.</p>
    </div>
</div>
<div class="panel store-cart-panel">
    <?php foreach ($carrito['lineas'] as $linea): ?>
        <div class="marash-cart-line">
            <div>
                <strong><?= e($linea['producto']['name']) ?></strong>
                <p><?= (int) $linea['cantidad'] ?> × <?= money($linea['producto']['price']) ?></p>
            </div>
            <strong><?= money($linea['subtotal']) ?></strong>
            <form method="post">
                <?php csrf(); ?>
                <input type="hidden" name="action" value="carrito">
                <input type="hidden" name="producto_id" value="<?= (int) $linea['producto']['id'] ?>">
                <input type="hidden" name="cantidad" value="0">
                <button class="secondary" type="submit">Quitar</button>
            </form>
        </div>
    <?php endforeach; ?>
    <?php if (!$carrito['lineas']): ?>
        <p>Tu carrito está vacío. ¡Descubre nuestra colección!</p>
    <?php endif; ?>
    <h2>Total: <?= money($carrito['total']) ?></h2>
    <?php if ($carrito['lineas']): ?>
        <?php if (!$esCliente && empty($_SESSION['uid'])): ?>
            <div class="store-checkout-message">
                <strong>¡Ya casi tienes tu pedido!</strong>
                <p>Para finalizar la compra, inicia sesión o crea una cuenta. Tu carrito se conservará.</p>
                <a class="store-hero-button" href="?page=login&amp;continuar=compra">Iniciar sesión</a>
                <a class="btn-link" href="?page=registro&amp;continuar=compra">Crear cuenta</a>
            </div>
        <?php elseif (!$esCliente): ?>
            <p class="notice">Para comprar, utiliza una cuenta de cliente. Puedes salir del panel y registrarte como cliente con otro correo.</p>
        <?php else: ?>
            <form method="post">
                <?php csrf(); ?>
                <input type="hidden" name="action" value="crear_pedido">
                <button class="primary" type="submit">Confirmar pedido</button>
            </form>
            <p class="notice">El pedido quedará pendiente de pago. Todavía no hay cobros reales ni descuentos de inventario por pedidos pendientes.</p>
        <?php endif; ?>
    <?php endif; ?>
    <a class="btn-link" href="?page=tienda">Seguir comprando</a>
</div>
