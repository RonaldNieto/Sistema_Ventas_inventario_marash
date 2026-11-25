-- Instalación nueva: ejecutar solo en una base sin tablas anteriores.
CREATE DATABASE IF NOT EXISTS marash CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE marash;

CREATE TABLE IF NOT EXISTS usuarios (id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,nombre_usuario VARCHAR(80) NOT NULL UNIQUE,correo VARCHAR(160) NOT NULL UNIQUE,nombre_completo VARCHAR(150) NOT NULL,clave_hash VARCHAR(255) NOT NULL,rol ENUM('admin','vendedor','cliente') NOT NULL DEFAULT 'cliente',activo TINYINT(1) NOT NULL DEFAULT 1,fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS proveedores (id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,nombre VARCHAR(160) NOT NULL,telefono VARCHAR(40) NOT NULL DEFAULT '',correo VARCHAR(160) NOT NULL DEFAULT '') ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS clientes (id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,nombre VARCHAR(160) NOT NULL,telefono VARCHAR(40) NOT NULL DEFAULT '',correo VARCHAR(160) NOT NULL DEFAULT '') ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS productos (id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,nombre VARCHAR(180) NOT NULL,codigo VARCHAR(80) NOT NULL UNIQUE,categoria VARCHAR(100) NOT NULL,precio_venta DECIMAL(12,2) NOT NULL DEFAULT 0,precio_compra DECIMAL(12,2) NOT NULL DEFAULT 0,existencias INT UNSIGNED NOT NULL DEFAULT 0,tallas VARCHAR(200) NOT NULL DEFAULT '',colores VARCHAR(200) NOT NULL DEFAULT '',proveedor VARCHAR(160) NOT NULL DEFAULT '',imagen_url VARCHAR(500) NOT NULL DEFAULT '',fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS ventas (id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,id_cliente INT UNSIGNED NULL,id_usuario INT UNSIGNED NOT NULL,metodo_pago ENUM('Efectivo','Tarjeta','Transferencia') NOT NULL DEFAULT 'Efectivo',total DECIMAL(14,2) NOT NULL,fecha_venta TIMESTAMP DEFAULT CURRENT_TIMESTAMP,FOREIGN KEY (id_cliente) REFERENCES clientes(id) ON DELETE SET NULL,FOREIGN KEY (id_usuario) REFERENCES usuarios(id)) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS detalles_ventas (id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,id_venta INT UNSIGNED NOT NULL,id_producto INT UNSIGNED NOT NULL,cantidad INT UNSIGNED NOT NULL,precio_unitario DECIMAL(12,2) NOT NULL,costo_unitario DECIMAL(12,2) NOT NULL,FOREIGN KEY (id_venta) REFERENCES ventas(id) ON DELETE CASCADE,FOREIGN KEY (id_producto) REFERENCES productos(id)) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS movimientos_inventario (id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,id_producto INT UNSIGNED NOT NULL,id_usuario INT UNSIGNED NOT NULL,cantidad INT NOT NULL,motivo VARCHAR(200) NOT NULL,fecha_movimiento TIMESTAMP DEFAULT CURRENT_TIMESTAMP,FOREIGN KEY (id_producto) REFERENCES productos(id),FOREIGN KEY (id_usuario) REFERENCES usuarios(id)) ENGINE=InnoDB;

INSERT IGNORE INTO proveedores(id,nombre,telefono,correo) VALUES (1,'Moda Textil','3001112233','ventas@modatextil.co'),(2,'Denim SAS','3002223344','info@denim.co');

INSERT IGNORE INTO clientes(id,nombre,telefono,correo) VALUES (1,'María López','3001234567','maria@ejemplo.com'),(2,'José Ramírez','3005551122','jose@ejemplo.com');

INSERT IGNORE INTO productos(id,nombre,codigo,categoria,precio_venta,precio_compra,existencias,tallas,colores,proveedor,imagen_url) VALUES
(1,'Camiseta Básica','TRX-1045','Camisetas',45000,23000,32,'S,M,L,XL','Gris,Negro,Azul','Moda Textil','https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=300&q=80'),
(2,'Jean Slim Fit','TRX-1044','Pantalones',85000,45000,18,'28,30,32,34','Azul,Negro','Denim SAS','https://images.unsplash.com/photo-1542272604-787c3835535d?w=300&q=80'),
(3,'Chaqueta Denim','TRX-1043','Chaquetas',120000,70000,8,'S,M,L','Azul','Denim SAS','https://images.unsplash.com/photo-1544923246-77307dd654cb?w=300&q=80'),
(4,'Hoodie Clásico','TRX-1042','Sudaderas',95000,50000,12,'S,M,L,XL','Beige,Negro','Moda Textil','https://images.unsplash.com/photo-1556821840-3a63f95609a7?w=300&q=80'),
(5,'Chaqueta Bomber','TRX-1041','Chaquetas',150000,85000,6,'M,L,XL','Negro','Moda Textil','https://images.unsplash.com/photo-1591047139829-d91aecb6caea?w=300&q=80');

USE marash;

CREATE TABLE IF NOT EXISTS pedidos (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT UNSIGNED NOT NULL,
    total DECIMAL(14,2) NOT NULL,
    estado ENUM('pendiente_pago','pagado','cancelado') NOT NULL DEFAULT 'pendiente_pago',
    fecha_pedido TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS detalles_pedidos (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    id_pedido INT UNSIGNED NOT NULL,
    id_producto INT UNSIGNED NOT NULL,
    cantidad INT UNSIGNED NOT NULL,
    precio_unitario DECIMAL(12,2) NOT NULL,
    FOREIGN KEY (id_pedido) REFERENCES pedidos(id),
    FOREIGN KEY (id_producto) REFERENCES productos(id)
) ENGINE=InnoDB;

CREATE OR REPLACE VIEW `users` AS SELECT `id` AS `id`, `nombre_usuario` AS `username`, `correo` AS `email`, `nombre_completo` AS `full_name`, `clave_hash` AS `password_hash`, `rol` AS `role`, `activo` AS `active`, `fecha_creacion` AS `created_at` FROM `usuarios`;

CREATE OR REPLACE VIEW `suppliers` AS SELECT `id` AS `id`, `nombre` AS `name`, `telefono` AS `phone`, `correo` AS `email` FROM `proveedores`;

CREATE OR REPLACE VIEW `clients` AS SELECT `id` AS `id`, `nombre` AS `name`, `telefono` AS `phone`, `correo` AS `email` FROM `clientes`;

CREATE OR REPLACE VIEW `products` AS SELECT `id` AS `id`, `nombre` AS `name`, `codigo` AS `sku`, `categoria` AS `category`, `precio_venta` AS `price`, `precio_compra` AS `cost`, `existencias` AS `stock`, `tallas` AS `sizes`, `colores` AS `colors`, `proveedor` AS `supplier`, `imagen_url` AS `image_url`, `fecha_registro` AS `created_at` FROM `productos`;

CREATE OR REPLACE VIEW `sales` AS SELECT `id` AS `id`, `id_cliente` AS `client_id`, `id_usuario` AS `user_id`, `metodo_pago` AS `payment_method`, `total` AS `total`, `fecha_venta` AS `created_at` FROM `ventas`;

CREATE OR REPLACE VIEW `sale_items` AS SELECT `id` AS `id`, `id_venta` AS `sale_id`, `id_producto` AS `product_id`, `cantidad` AS `quantity`, `precio_unitario` AS `unit_price`, `costo_unitario` AS `unit_cost` FROM `detalles_ventas`;

CREATE OR REPLACE VIEW `stock_movements` AS SELECT `id` AS `id`, `id_producto` AS `product_id`, `id_usuario` AS `user_id`, `cantidad` AS `quantity`, `motivo` AS `reason`, `fecha_movimiento` AS `created_at` FROM `movimientos_inventario`;

CREATE OR REPLACE VIEW `online_orders` AS SELECT `id` AS `id`, `id_usuario` AS `user_id`, `total` AS `total`, `estado` AS `estado`, `fecha_pedido` AS `created_at` FROM `pedidos`;

CREATE OR REPLACE VIEW `online_order_items` AS SELECT `id` AS `id`, `id_pedido` AS `order_id`, `id_producto` AS `product_id`, `cantidad` AS `quantity`, `precio_unitario` AS `unit_price` FROM `detalles_pedidos`;

-- Cuentas de demostración local
INSERT IGNORE INTO usuarios (nombre_usuario,correo,nombre_completo,clave_hash,rol,activo) VALUES ('administrador','admin@marash.local','Administrador MARASH','$2y$12$N2L1PjMdM1gPoWEKleogpeCEKmFLY4PjlNkcEUrFWgRBJtEjZ.5FG','admin',1);
INSERT IGNORE INTO usuarios (nombre_usuario,correo,nombre_completo,clave_hash,rol,activo) VALUES ('vendedor','vendedor@marash.local','Vendedor MARASH','$2y$12$mQOCLT0/L3/gUK990KRouOrHKzfbglyYDuH/lCba9pC.lepGTnwT6','vendedor',1);
INSERT IGNORE INTO usuarios (nombre_usuario,correo,nombre_completo,clave_hash,rol,activo) VALUES ('cliente','cliente@marash.local','Cliente MARASH','$2y$12$9YqmYa9jQhGyl3Iq.YXXceMWUpIL.Do6xry6C3Ce1.qeGs6hYC61e','cliente',1);
