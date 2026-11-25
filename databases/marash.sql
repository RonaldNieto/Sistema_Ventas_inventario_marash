-- MARASH: instalacion inicial, MySQL 8.0.44
-- Ejecutar primero en una base nueva. No elimina datos existentes.
CREATE DATABASE IF NOT EXISTS marash CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE marash;

CREATE TABLE IF NOT EXISTS users (
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 username VARCHAR(80) NOT NULL UNIQUE,
 email VARCHAR(160) NOT NULL UNIQUE,
 full_name VARCHAR(150) NOT NULL,
 password_hash VARCHAR(255) NOT NULL,
 role ENUM('admin','vendedor','cliente') NOT NULL DEFAULT 'cliente',
 active TINYINT(1) NOT NULL DEFAULT 1,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;
CREATE TABLE IF NOT EXISTS branches (
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 name VARCHAR(120) NOT NULL UNIQUE
) ENGINE=InnoDB;
CREATE TABLE IF NOT EXISTS suppliers (
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 name VARCHAR(160) NOT NULL,
 phone VARCHAR(40) NOT NULL DEFAULT '',
 email VARCHAR(160) NOT NULL DEFAULT ''
) ENGINE=InnoDB;
CREATE TABLE IF NOT EXISTS clients (
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 name VARCHAR(160) NOT NULL,
 phone VARCHAR(40) NOT NULL DEFAULT '',
 email VARCHAR(160) NOT NULL DEFAULT ''
) ENGINE=InnoDB;
CREATE TABLE IF NOT EXISTS products (
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 name VARCHAR(180) NOT NULL,
 sku VARCHAR(80) NOT NULL UNIQUE,
 category VARCHAR(100) NOT NULL,
 price DECIMAL(12,2) NOT NULL DEFAULT 0,
 cost DECIMAL(12,2) NOT NULL DEFAULT 0,
 stock INT UNSIGNED NOT NULL DEFAULT 0,
 sizes VARCHAR(200) NOT NULL DEFAULT '',
 colors VARCHAR(200) NOT NULL DEFAULT '',
 supplier VARCHAR(160) NOT NULL DEFAULT '',
 image_url VARCHAR(500) NOT NULL DEFAULT '',
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;
CREATE TABLE IF NOT EXISTS sales (
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 client_id INT UNSIGNED NULL,
 user_id INT UNSIGNED NOT NULL,
 payment_method ENUM('Efectivo','Tarjeta','Transferencia') NOT NULL DEFAULT 'Efectivo',
 total DECIMAL(14,2) NOT NULL,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 FOREIGN KEY (client_id) REFERENCES clients(id) ON DELETE SET NULL,
 FOREIGN KEY (user_id) REFERENCES users(id)
) ENGINE=InnoDB;
CREATE TABLE IF NOT EXISTS sale_items (
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 sale_id INT UNSIGNED NOT NULL,
 product_id INT UNSIGNED NOT NULL,
 quantity INT UNSIGNED NOT NULL,
 unit_price DECIMAL(12,2) NOT NULL,
 unit_cost DECIMAL(12,2) NOT NULL,
 FOREIGN KEY (sale_id) REFERENCES sales(id) ON DELETE CASCADE,
 FOREIGN KEY (product_id) REFERENCES products(id)
) ENGINE=InnoDB;
CREATE TABLE IF NOT EXISTS stock_movements (
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 product_id INT UNSIGNED NOT NULL,
 user_id INT UNSIGNED NOT NULL,
 quantity INT NOT NULL,
 reason VARCHAR(200) NOT NULL,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 FOREIGN KEY (product_id) REFERENCES products(id),
 FOREIGN KEY (user_id) REFERENCES users(id)
) ENGINE=InnoDB;

INSERT IGNORE INTO branches (id,name) VALUES (1,'Principal');
INSERT IGNORE INTO suppliers (id,name,phone,email) VALUES
 (1,'Moda Textil','3001112233','ventas@modatextil.co'),
 (2,'Denim SAS','3002223344','info@denim.co');
INSERT IGNORE INTO clients (id,name,phone,email) VALUES
 (1,'María López','3001234567','maria@ejemplo.com'),
 (2,'José Ramírez','3005551122','jose@ejemplo.com');
INSERT IGNORE INTO products (id,name,sku,category,price,cost,stock,sizes,colors,supplier,image_url) VALUES
 (1,'Camiseta Básica','TRX-1045','Camisetas',45000,23000,32,'S,M,L,XL','Gris,Negro,Azul','Moda Textil','https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=300&q=80'),
 (2,'Jean Slim Fit','TRX-1044','Pantalones',85000,45000,18,'28,30,32,34','Azul,Negro','Denim SAS','https://images.unsplash.com/photo-1542272604-787c3835535d?w=300&q=80'),
 (3,'Chaqueta Denim','TRX-1043','Chaquetas',120000,70000,8,'S,M,L','Azul','Denim SAS','https://images.unsplash.com/photo-1544923246-77307dd654cb?w=300&q=80'),
 (4,'Hoodie Clásico','TRX-1042','Sudaderas',95000,50000,12,'S,M,L,XL','Beige,Negro','Moda Textil','https://images.unsplash.com/photo-1556821840-3a63f95609a7?w=300&q=80'),
 (5,'Chaqueta Bomber','TRX-1041','Chaquetas',150000,85000,6,'M,L,XL','Negro','Moda Textil','https://images.unsplash.com/photo-1591047139829-d91aecb6caea?w=300&q=80');
