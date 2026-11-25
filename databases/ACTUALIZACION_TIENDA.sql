-- MARASH: pedidos online. Ejecutar despues de marash.sql.
USE marash;
CREATE TABLE IF NOT EXISTS online_orders (
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 user_id INT UNSIGNED NOT NULL,
 total DECIMAL(14,2) NOT NULL,
 estado ENUM('pendiente_pago','pagado','cancelado') NOT NULL DEFAULT 'pendiente_pago',
 created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
 FOREIGN KEY (user_id) REFERENCES users(id)
) ENGINE=InnoDB;
CREATE TABLE IF NOT EXISTS online_order_items (
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 order_id INT UNSIGNED NOT NULL,
 product_id INT UNSIGNED NOT NULL,
 quantity INT UNSIGNED NOT NULL,
 unit_price DECIMAL(12,2) NOT NULL,
 FOREIGN KEY (order_id) REFERENCES online_orders(id),
 FOREIGN KEY (product_id) REFERENCES products(id)
) ENGINE=InnoDB;
