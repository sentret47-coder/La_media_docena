-- la_media_docena.sql - crea las tablas y datos de ejemplo
CREATE DATABASE IF NOT EXISTS la_media_docena CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE la_media_docena;

-- Tabla usuarios (para administración)
CREATE TABLE IF NOT EXISTS usuarios (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  nombre_completo VARCHAR(150),
  email VARCHAR(255),
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabla platillos
CREATE TABLE IF NOT EXISTS platillos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(150) NOT NULL,
  descripcion TEXT,
  precio DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  categoria VARCHAR(80),
  disponible TINYINT(1) DEFAULT 1,
  fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabla clientes
CREATE TABLE IF NOT EXISTS clientes (
  id_cliente INT AUTO_INCREMENT PRIMARY KEY,
  nombre_completo VARCHAR(150) NOT NULL,
  email VARCHAR(255),
  telefono VARCHAR(50),
  total_pedidos INT DEFAULT 0,
  ultima_visita DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabla pedidos
CREATE TABLE IF NOT EXISTS pedidos (
  id_pedido INT AUTO_INCREMENT PRIMARY KEY,
  cliente_id INT,
  cliente_nombre VARCHAR(150),
  subtotal DECIMAL(10,2) DEFAULT 0.00,
  costo_envio DECIMAL(10,2) DEFAULT 0.00,
  total DECIMAL(10,2) DEFAULT 0.00,
  estado VARCHAR(50) DEFAULT 'pendiente',
  fecha_pedido DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (cliente_id) REFERENCES clientes(id_cliente) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Datos de ejemplo para platillos
INSERT INTO platillos (nombre, descripcion, precio, categoria, disponible) VALUES
('Bagel Clásico', 'Bagel con queso crema y salmón', 85.00, 'especiales', 1),
('Media Docena Donuts', '6 mini donuts surtidos', 120.00, 'postres', 1),
('Hamburguesa Urban', 'Carne 200g, queso, tocino', 129.00, 'hamburguesas', 1),
('Tacos Al Pastor', '3 tacos con piña', 89.00, 'tacos', 1),
('Coca-Cola 500ml', 'Bebida', 25.00, 'bebidas', 1);

-- Datos de ejemplo para clientes
INSERT INTO clientes (nombre_completo, email, telefono, total_pedidos, ultima_visita) VALUES
('Juan Pérez', 'juan@ejemplo.com', '555-1234', 5, NOW() - INTERVAL 5 DAY),
('María García', 'maria@ejemplo.com', '555-5678', 3, NOW() - INTERVAL 2 DAY),
('Carlos López', 'carlos@ejemplo.com', '555-9999', 2, NOW() - INTERVAL 10 DAY);

-- Datos de ejemplo para pedidos
INSERT INTO pedidos (cliente_id, cliente_nombre, subtotal, costo_envio, total, estado, fecha_pedido) VALUES
(1, 'Juan Pérez', 200.00, 20.00, 220.00, 'completado', NOW() - INTERVAL 1 DAY),
(2, 'María García', 150.00, 15.00, 165.00, 'pendiente', NOW()),
(3, 'Carlos López', 89.00, 10.00, 99.00, 'completado', NOW() - INTERVAL 3 DAY);
