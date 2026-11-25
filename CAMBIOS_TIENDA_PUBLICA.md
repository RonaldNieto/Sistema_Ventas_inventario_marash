# MARASH — Tienda pública y carrito de invitados

## Qué cambió

- Al entrar a `http://localhost/marash/` sin sesión, se muestra la tienda.
- Los visitantes pueden ver productos y agregar o quitar prendas del carrito sin registrarse.
- Al finalizar la compra se pide iniciar sesión o crear una cuenta.
- El carrito se conserva en la sesión PHP al pasar por el registro e inicio de sesión.
- Los administradores y vendedores conservan su panel y permisos.
- No se requiere ejecutar ningún SQL nuevo para este cambio.

## Qué NO hace todavía

- No recibe pagos reales: la pasarela de pagos se integrará después.
- Los pedidos quedan pendientes de pago y no descuentan inventario.
- No incluye aún variantes de talla/color por unidad ni dirección de envío.

## Instalación

1. Reemplaza la carpeta del proyecto por la nueva versión.
2. Conserva tu `backend/config/local.php` actual (no se incluye en el ZIP por seguridad).
3. No ejecutes de nuevo `marash.sql` ni los scripts de actualización que ya ejecutaste.
4. Inicia Apache y entra a `http://localhost/marash/`.
5. Prueba como invitado: catálogo → carrito → iniciar sesión/registro → confirmar pedido.

## Revisión del profesor

Se mantienen las carpetas `frontend`, `backend`, `views` y `database` y el código nuevo está formateado con saltos de línea. Esta versión requiere pruebas funcionales en el XAMPP local.
