<div class="login-layout">
    <section class="login-brand">
        <div>
            <span class="login-chip">MARASH FASHION</span>
            <h1>Crea tu cuenta</h1>
            <p>Descubre nuestra colección de ropa.</p>
        </div>
    </section>
    <section class="login-area">
        <form class="login-card" method="post">
            <h2>Registro de clientes</h2>
            <?php if ($error): ?><div class="flash error"><?= e($error) ?></div><?php endif; ?>
            <?php csrf(); ?>
            <input type="hidden" name="action" value="registro_cliente">
            <label>Nombre completo<input name="nombre" required maxlength="150"></label>
            <label>Correo electrónico<input type="email" name="correo" required maxlength="160"></label>
            <label>Contraseña<input type="password" name="clave" required minlength="8"></label>
            <button class="primary wide" type="submit">Crear cuenta</button>
            <p><a href="?page=tienda">← Volver a la tienda</a></p>
            <p>¿Ya tienes cuenta? <a href="?page=login">Inicia sesión</a></p>
        </form>
    </section>
</div>
