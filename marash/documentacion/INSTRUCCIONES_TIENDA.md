# MARASH — Avance de tienda virtual

## Implementado

- Registro de clientes con correo único y contraseña protegida.
- Inicio de sesión de clientes y acceso a catálogo separado del panel administrativo.
- Catálogo de prendas, carrito y creación de pedidos pendientes de pago.
- Consulta de pedidos por el cliente que los creó.
- Se conservan las ventas e inventario del sistema existente.
- Archivos PHP con saltos de línea y funciones pequeñas para explicar en clase.

## Pendiente: cobro real

**Esta versión NO cobra dinero real.** El pedido queda `pendiente_pago`.
Para aceptar tarjetas, PSE, Nequi u otros medios se debe elegir una pasarela
(Mercado Pago, Wompi, PayU, etc.), obtener credenciales y conectar el pago
seguro con validación de confirmación en el servidor.

No se debe descontar inventario ni marcar `pagado` solo porque el cliente
regresó a la página; se requiere confirmación verificable de la pasarela.

## Instalación

1. Respaldar carpeta del proyecto y exportar base de datos.
2. Conservar `servidor/configuracion/local.php` original; nunca publicarlo.
3. Copiar esta versión a `C:\xampp\htdocs\marash`.
4. Si aún no se aplicó, ejecutar primero `base_datos/ACTUALIZACION_ROLES.sql`.
5. Ejecutar `base_datos/ACTUALIZACION_TIENDA.sql` en HeidiSQL.
6. Abrir `http://localhost/marash/index.php?page=registro`.
7. Crear un cliente, iniciar sesión, agregar prendas y crear un pedido.
8. Revisar en HeidiSQL las tablas `online_orders` y `online_order_items`.

No volver a importar `base_datos/marash.sql` sobre la base con datos.
