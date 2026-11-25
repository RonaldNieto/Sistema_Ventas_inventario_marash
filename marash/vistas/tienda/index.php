<?php
$productos = tiendaProductos();
?>
<section class="store-hero">
    <div>
        <span class="store-eyebrow">MARASH · NUEVA COLECCIÓN</span>
        <h1>Tu estilo empieza aquí.</h1>
        <p>Descubre tus prendas favoritas. Explora y llena tu carrito sin registrarte.</p>
        <a class="store-hero-button" href="#catalogo">Explorar colección</a>
    </div>
</section>

<section id="catalogo" class="store-catalog">
    <div class="store-catalog-heading">
        <div>
            <span class="store-eyebrow">PARA TI</span>
            <h2>Nuestras prendas</h2>
        </div>
        <a class="btn-link" href="?page=carrito">Ver carrito (<?= array_sum($_SESSION['carrito'] ?? []) ?>)</a>
    </div>
    <div class="marash-products">
        <?php foreach ($productos as $producto): ?>
            <article class="panel marash-product">
                <?php if (!empty($producto['image_url'])): ?>
                    <img src="<?= e($producto['image_url']) ?>" alt="<?= e($producto['name']) ?>" loading="lazy">
                <?php else: ?>
                    <div class="store-image-placeholder">MARASH ✦</div>
                <?php endif; ?>
                <h3><?= e($producto['name']) ?></h3>
                <p><?= e($producto['category']) ?> · Tallas: <?= e($producto['sizes']) ?></p>
                <strong><?= money($producto['price']) ?></strong>
                <p>Disponibles: <?= (int) $producto['stock'] ?></p>
                <form method="post">
                    <?php csrf(); ?>
                    <input type="hidden" name="action" value="carrito">
                    <input type="hidden" name="producto_id" value="<?= (int) $producto['id'] ?>">
                    <label>Cantidad
                        <input type="number" name="cantidad" min="1" max="<?= (int) $producto['stock'] ?>" value="1" required>
                    </label>
                    <button class="primary" type="submit">Agregar al carrito</button>
                </form>
            </article>
        <?php endforeach; ?>
    </div>
    <?php if (!$productos): ?>
        <div class="panel">No hay prendas disponibles por ahora.</div>
    <?php endif; ?>
</section>
