CREATE DATABASE `Banco4`;
USE Banco4;

-- Tabla Clientes
CREATE TABLE `Clientes` (
  `cliente_id` int PRIMARY KEY AUTO_INCREMENT,
  `nombre` varchar(50),
  `apellido` varchar(50),
  `telefono` varchar(20),
  `correo_electronico` varchar(100) UNIQUE,
  `fecha_registro` date,
  `estado` enum('activo','inactivo')
);

-- Tabla Cuentas
CREATE TABLE `Cuentas` (
  `cuenta_id` int PRIMARY KEY AUTO_INCREMENT,
  `cliente_id` int,
  `tipo_cuenta` enum('Ahorro', 'Corriente') NOT NULL,
  `saldo_actual` decimal(15,2),
  `fecha_apertura` date,
  `estado` enum('activo','inactivo'),
  FOREIGN KEY (`cliente_id`) REFERENCES `Clientes`(`cliente_id`)
);

-- Tabla Movimientos
CREATE TABLE `Movimientos` (
  `movimiento_id` int PRIMARY KEY AUTO_INCREMENT,
  `cuenta_id` int,
  `fecha_movimiento` date,
  `tipo_movimiento` enum('depósito', 'retiro', 'transferencia'),
  `monto` decimal(15,2),
  `descripcion` text,
  FOREIGN KEY (`cuenta_id`) REFERENCES `Cuentas`(`cuenta_id`)
);

-- Tabla Tarjetas
CREATE TABLE `Tarjetas` (
  `tarjeta_id` int PRIMARY KEY AUTO_INCREMENT,
  `cliente_id` int,
  `numero_enmascarado` varchar(20),
  `tipo_tarjeta` enum('débito', 'crédito'),
  `estado` enum('activo', 'inactivo'),
  `fecha_emision` date,
  `fecha_bloqueo` date,
  `fecha_vencimiento` date,
  FOREIGN KEY (`cliente_id`) REFERENCES `Clientes`(`cliente_id`)
);

-- Tabla Llamadas
CREATE TABLE `Llamadas` (
  `llamada_id` int PRIMARY KEY AUTO_INCREMENT,
  `cliente_id` int,
  `telefono_llamante` varchar(20),
  `fecha_hora_inicio` datetime,
  `fecha_hora_fin` datetime,
  `duracion` int,
  `resultado` varchar(50),
  FOREIGN KEY (`cliente_id`) REFERENCES `Clientes`(`cliente_id`)
);

-- Tabla Interacciones IVR
CREATE TABLE `Interacciones_IVR` (
  `interaccion_id` int PRIMARY KEY AUTO_INCREMENT,
  `llamada_id` int,
  `opcion_elegida` enum('Consulta de saldo', 'Bloqueo de tarjeta', 'Movimientos recientes', 'Hablar con un agente'),
  `tiempo_respuesta` int,  -- tiempo en segundos
  `fecha_hora` datetime,
  `descripcion` text,
  FOREIGN KEY (`llamada_id`) REFERENCES `Llamadas`(`llamada_id`)
);

-- Tabla Redirecciones
CREATE TABLE `Redirecciones` (
  `redireccion_id` int PRIMARY KEY AUTO_INCREMENT,
  `llamada_id` int,
  `motivo` varchar(100),
  `destino` varchar(100),  -- Ejemplo: 'Atención al cliente', 'Departamento de fraudes'
  `fecha_hora` datetime,
  FOREIGN KEY (`llamada_id`) REFERENCES `Llamadas`(`llamada_id`)
);

-- Insertar datos en la tabla Clientes
INSERT INTO `Clientes` (`nombre`, `apellido`, `telefono`, `correo_electronico`, `fecha_registro`, `estado`)
VALUES 
('Juan', 'Pérez', '123456789', 'juan.perez@ejemplo.com', '2025-01-01', 'activo'),
('Ana', 'Gómez', '987654321', 'ana.gomez@ejemplo.com', '2024-11-15', 'activo'),
('Carlos', 'López', '555123456', 'carlos.lopez@ejemplo.com', '2023-03-25', 'inactivo');

-- Insertar datos en la tabla Cuentas
INSERT INTO `Cuentas` (`cliente_id`, `tipo_cuenta`, `saldo_actual`, `fecha_apertura`, `estado`)
VALUES 
(1, 'Ahorro', 15000.75, '2025-01-01', 'activo'),
(2, 'Corriente', 5200.50, '2024-11-15', 'activo'),
(3, 'Ahorro', 1000.00, '2023-03-25', 'inactivo');

-- Insertar datos en la tabla Movimientos
INSERT INTO `Movimientos` (`cuenta_id`, `fecha_movimiento`, `tipo_movimiento`, `monto`, `descripcion`)
VALUES 
(1, '2025-01-10', 'depósito', 5000.00, 'Depósito de sueldo'),
(2, '2024-12-01', 'retiro', 1000.00, 'Pago de tarjeta de crédito'),
(1, '2025-02-01', 'transferencia', 2000.00, 'Transferencia a cuenta externa');

-- Insertar datos en la tabla Tarjetas
INSERT INTO `Tarjetas` (`cliente_id`, `numero_enmascarado`, `tipo_tarjeta`, `estado`, `fecha_emision`, `fecha_bloqueo`, `fecha_vencimiento`)
VALUES 
(1, '1234 5678 9012 3456', 'débito', 'activo', '2025-01-01', NULL, '2028-01-01'),
(2, '9876 5432 1098 7654', 'crédito', 'activo', '2024-11-20', NULL, '2027-11-20'),
(3, '5551 2345 6789 0123', 'débito', 'inactivo', '2023-03-30', '2024-01-01', '2026-03-30');

-- Insertar datos en la tabla Llamadas
INSERT INTO `Llamadas` (`cliente_id`, `telefono_llamante`, `fecha_hora_inicio`, `fecha_hora_fin`, `duracion`, `resultado`)
VALUES 
(1, '123456789', '2025-02-10 10:30:00', '2025-02-10 10:35:00', 300, 'Consulta de saldo'),
(2, '987654321', '2024-12-05 14:00:00', '2024-12-05 14:05:00', 300, 'Bloqueo de tarjeta'),
(1, '123456789', '2025-02-15 16:20:00', '2025-02-15 16:22:00', 120, 'Consulta de movimientos');

-- Insertar datos en la tabla Interacciones_IVR
INSERT INTO `Interacciones_IVR` (`llamada_id`, `opcion_elegida`, `tiempo_respuesta`, `fecha_hora`, `descripcion`)
VALUES 
(1, 'Consulta de saldo', 15, '2025-02-10 10:30:00', 'Consulta del saldo disponible en la cuenta de ahorro'),
(2, 'Bloqueo de tarjeta', 10, '2024-12-05 14:00:00', 'Bloqueo de tarjeta de crédito debido a robo'),
(3, 'Movimientos recientes', 12, '2025-02-15 16:20:00', 'Consulta de los últimos movimientos de la cuenta corriente');

-- Insertar datos en la tabla Redirecciones
INSERT INTO `Redirecciones` (`llamada_id`, `motivo`, `destino`, `fecha_hora`)
VALUES 
(1, 'Consulta de saldo', 'Atención al cliente', '2025-02-10 10:32:00'),
(2, 'Bloqueo de tarjeta', 'Departamento de fraudes', '2024-12-05 14:02:00'),
(3, 'Movimientos recientes', 'Atención al cliente', '2025-02-15 16:22:00');
