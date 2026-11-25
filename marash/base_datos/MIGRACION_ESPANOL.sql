-- MARASH | MIGRACION AL ESPANOL | ejecutar UNA SOLA VEZ sobre base ya instalada
-- IMPORTANTE: exportar respaldo de marash antes de ejecutar.
-- Compatible con el proyecto PHP original mediante VISTAS de compatibilidad.
USE marash;

-- Verifica previamente que existen las 10 tablas originales y que no has ejecutado esta migracion.
-- La tabla branches solo debe contener el registro inicial 'Principal'.
DELIMITER $$
CREATE PROCEDURE verificar_marash_espanol()
BEGIN
 IF (SELECT COUNT(*) FROM information_schema.tables WHERE table_schema=DATABASE() AND table_name='usuarios' AND table_type='BASE TABLE') > 0 THEN
  SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT='MARASH ya fue migrado al espanol';
 END IF;
 IF (SELECT COUNT(*) FROM branches WHERE id<>1 OR name<>'Principal') > 0 THEN
  SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT='Sucursales con datos adicionales: revisa antes de migrar';
 END IF;
END$$
DELIMITER ;
CALL verificar_marash_espanol();
DROP PROCEDURE verificar_marash_espanol;

-- Renombrar tablas sin perder datos ni relaciones
RENAME TABLE `users` TO `usuarios`,
 `suppliers` TO `proveedores`,
 `clients` TO `clientes`,
 `products` TO `productos`,
 `sales` TO `ventas`,
 `sale_items` TO `detalles_ventas`,
 `stock_movements` TO `movimientos_inventario`,
 `online_orders` TO `pedidos`,
 `online_order_items` TO `detalles_pedidos`;

ALTER TABLE `usuarios` RENAME COLUMN `username` TO `nombre_usuario`;
ALTER TABLE `usuarios` RENAME COLUMN `email` TO `correo`;
ALTER TABLE `usuarios` RENAME COLUMN `full_name` TO `nombre_completo`;
ALTER TABLE `usuarios` RENAME COLUMN `password_hash` TO `clave_hash`;
ALTER TABLE `usuarios` RENAME COLUMN `role` TO `rol`;
ALTER TABLE `usuarios` RENAME COLUMN `active` TO `activo`;
ALTER TABLE `usuarios` RENAME COLUMN `created_at` TO `fecha_creacion`;

ALTER TABLE `proveedores` RENAME COLUMN `name` TO `nombre`;
ALTER TABLE `proveedores` RENAME COLUMN `phone` TO `telefono`;
ALTER TABLE `proveedores` RENAME COLUMN `email` TO `correo`;

ALTER TABLE `clientes` RENAME COLUMN `name` TO `nombre`;
ALTER TABLE `clientes` RENAME COLUMN `phone` TO `telefono`;
ALTER TABLE `clientes` RENAME COLUMN `email` TO `correo`;

ALTER TABLE `productos` RENAME COLUMN `name` TO `nombre`;
ALTER TABLE `productos` RENAME COLUMN `sku` TO `codigo`;
ALTER TABLE `productos` RENAME COLUMN `category` TO `categoria`;
ALTER TABLE `productos` RENAME COLUMN `price` TO `precio_venta`;
ALTER TABLE `productos` RENAME COLUMN `cost` TO `precio_compra`;
ALTER TABLE `productos` RENAME COLUMN `stock` TO `existencias`;
ALTER TABLE `productos` RENAME COLUMN `sizes` TO `tallas`;
ALTER TABLE `productos` RENAME COLUMN `colors` TO `colores`;
ALTER TABLE `productos` RENAME COLUMN `supplier` TO `proveedor`;
ALTER TABLE `productos` RENAME COLUMN `image_url` TO `imagen_url`;
ALTER TABLE `productos` RENAME COLUMN `created_at` TO `fecha_registro`;

ALTER TABLE `ventas` RENAME COLUMN `client_id` TO `id_cliente`;
ALTER TABLE `ventas` RENAME COLUMN `user_id` TO `id_usuario`;
ALTER TABLE `ventas` RENAME COLUMN `payment_method` TO `metodo_pago`;
ALTER TABLE `ventas` RENAME COLUMN `created_at` TO `fecha_venta`;

ALTER TABLE `detalles_ventas` RENAME COLUMN `sale_id` TO `id_venta`;
ALTER TABLE `detalles_ventas` RENAME COLUMN `product_id` TO `id_producto`;
ALTER TABLE `detalles_ventas` RENAME COLUMN `quantity` TO `cantidad`;
ALTER TABLE `detalles_ventas` RENAME COLUMN `unit_price` TO `precio_unitario`;
ALTER TABLE `detalles_ventas` RENAME COLUMN `unit_cost` TO `costo_unitario`;

ALTER TABLE `movimientos_inventario` RENAME COLUMN `product_id` TO `id_producto`;
ALTER TABLE `movimientos_inventario` RENAME COLUMN `user_id` TO `id_usuario`;
ALTER TABLE `movimientos_inventario` RENAME COLUMN `quantity` TO `cantidad`;
ALTER TABLE `movimientos_inventario` RENAME COLUMN `reason` TO `motivo`;
ALTER TABLE `movimientos_inventario` RENAME COLUMN `created_at` TO `fecha_movimiento`;

ALTER TABLE `pedidos` RENAME COLUMN `user_id` TO `id_usuario`;
ALTER TABLE `pedidos` RENAME COLUMN `created_at` TO `fecha_pedido`;

ALTER TABLE `detalles_pedidos` RENAME COLUMN `order_id` TO `id_pedido`;
ALTER TABLE `detalles_pedidos` RENAME COLUMN `product_id` TO `id_producto`;
ALTER TABLE `detalles_pedidos` RENAME COLUMN `quantity` TO `cantidad`;
ALTER TABLE `detalles_pedidos` RENAME COLUMN `unit_price` TO `precio_unitario`;

-- Sucursal unica: quitar tabla de ejemplo que ya no se utiliza.
DROP TABLE `branches`;

CREATE OR REPLACE VIEW `users` AS SELECT `id` AS `id`, `nombre_usuario` AS `username`, `correo` AS `email`, `nombre_completo` AS `full_name`, `clave_hash` AS `password_hash`, `rol` AS `role`, `activo` AS `active`, `fecha_creacion` AS `created_at` FROM `usuarios`;
CREATE OR REPLACE VIEW `suppliers` AS SELECT `id` AS `id`, `nombre` AS `name`, `telefono` AS `phone`, `correo` AS `email` FROM `proveedores`;
CREATE OR REPLACE VIEW `clients` AS SELECT `id` AS `id`, `nombre` AS `name`, `telefono` AS `phone`, `correo` AS `email` FROM `clientes`;
CREATE OR REPLACE VIEW `products` AS SELECT `id` AS `id`, `nombre` AS `name`, `codigo` AS `sku`, `categoria` AS `category`, `precio_venta` AS `price`, `precio_compra` AS `cost`, `existencias` AS `stock`, `tallas` AS `sizes`, `colores` AS `colors`, `proveedor` AS `supplier`, `imagen_url` AS `image_url`, `fecha_registro` AS `created_at` FROM `productos`;
CREATE OR REPLACE VIEW `sales` AS SELECT `id` AS `id`, `id_cliente` AS `client_id`, `id_usuario` AS `user_id`, `metodo_pago` AS `payment_method`, `total` AS `total`, `fecha_venta` AS `created_at` FROM `ventas`;
CREATE OR REPLACE VIEW `sale_items` AS SELECT `id` AS `id`, `id_venta` AS `sale_id`, `id_producto` AS `product_id`, `cantidad` AS `quantity`, `precio_unitario` AS `unit_price`, `costo_unitario` AS `unit_cost` FROM `detalles_ventas`;
CREATE OR REPLACE VIEW `stock_movements` AS SELECT `id` AS `id`, `id_producto` AS `product_id`, `id_usuario` AS `user_id`, `cantidad` AS `quantity`, `motivo` AS `reason`, `fecha_movimiento` AS `created_at` FROM `movimientos_inventario`;
CREATE OR REPLACE VIEW `online_orders` AS SELECT `id` AS `id`, `id_usuario` AS `user_id`, `total` AS `total`, `estado` AS `estado`, `fecha_pedido` AS `created_at` FROM `pedidos`;
CREATE OR REPLACE VIEW `online_order_items` AS SELECT `id` AS `id`, `id_pedido` AS `order_id`, `id_producto` AS `product_id`, `cantidad` AS `quantity`, `precio_unitario` AS `unit_price` FROM `detalles_pedidos`;

-- Las 9 tablas reales estan en espanol. Las 9 VISTAS en ingles permiten que el PHP original siga funcionando.
-- Para ver solo las tablas reales: SHOW FULL TABLES WHERE Table_type = 'BASE TABLE';