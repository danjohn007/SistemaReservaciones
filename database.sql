-- ReserBot Database Schema
-- Sistema de Reservaciones y Citas Profesionales
-- Datos de ejemplo del estado de Querétaro

-- Crear base de datos
CREATE DATABASE IF NOT EXISTS reserbot_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE reserbot_db;

-- Tabla de roles
CREATE TABLE roles (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(50) NOT NULL UNIQUE,
    descripcion VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabla de usuarios
CREATE TABLE usuarios (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL,
    apellido VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    telefono VARCHAR(20),
    password_hash VARCHAR(255) NOT NULL,
    rol_id INT NOT NULL,
    email_verificado BOOLEAN DEFAULT FALSE,
    activo BOOLEAN DEFAULT TRUE,
    ultimo_acceso DATETIME,
    intentos_fallidos INT DEFAULT 0,
    bloqueado_hasta DATETIME,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (rol_id) REFERENCES roles(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabla de sucursales
CREATE TABLE sucursales (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(150) NOT NULL,
    direccion VARCHAR(255) NOT NULL,
    ciudad VARCHAR(100) NOT NULL,
    estado VARCHAR(100) NOT NULL,
    codigo_postal VARCHAR(10),
    telefono VARCHAR(20),
    email VARCHAR(150),
    hora_apertura TIME,
    hora_cierre TIME,
    zona_horaria VARCHAR(50) DEFAULT 'America/Mexico_City',
    activo BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabla de días no laborables
CREATE TABLE dias_no_laborables (
    id INT PRIMARY KEY AUTO_INCREMENT,
    sucursal_id INT NOT NULL,
    fecha DATE NOT NULL,
    descripcion VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (sucursal_id) REFERENCES sucursales(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabla de categorías de servicios
CREATE TABLE categorias_servicios (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    activo BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabla de servicios
CREATE TABLE servicios (
    id INT PRIMARY KEY AUTO_INCREMENT,
    categoria_id INT NOT NULL,
    sucursal_id INT NOT NULL,
    nombre VARCHAR(150) NOT NULL,
    descripcion TEXT,
    duracion_minutos INT NOT NULL,
    precio DECIMAL(10,2) NOT NULL,
    activo BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (categoria_id) REFERENCES categorias_servicios(id),
    FOREIGN KEY (sucursal_id) REFERENCES sucursales(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabla de especialistas
CREATE TABLE especialistas (
    id INT PRIMARY KEY AUTO_INCREMENT,
    usuario_id INT NOT NULL,
    sucursal_id INT NOT NULL,
    profesion VARCHAR(150),
    descripcion TEXT,
    experiencia_anos INT,
    calificacion_promedio DECIMAL(3,2) DEFAULT 0.00,
    total_calificaciones INT DEFAULT 0,
    activo BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
    FOREIGN KEY (sucursal_id) REFERENCES sucursales(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabla de servicios ofrecidos por especialistas
CREATE TABLE especialista_servicios (
    id INT PRIMARY KEY AUTO_INCREMENT,
    especialista_id INT NOT NULL,
    servicio_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (especialista_id) REFERENCES especialistas(id) ON DELETE CASCADE,
    FOREIGN KEY (servicio_id) REFERENCES servicios(id) ON DELETE CASCADE,
    UNIQUE KEY (especialista_id, servicio_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabla de horarios de especialistas
CREATE TABLE horarios_especialistas (
    id INT PRIMARY KEY AUTO_INCREMENT,
    especialista_id INT NOT NULL,
    dia_semana ENUM('lunes','martes','miercoles','jueves','viernes','sabado','domingo') NOT NULL,
    hora_inicio TIME NOT NULL,
    hora_fin TIME NOT NULL,
    activo BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (especialista_id) REFERENCES especialistas(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabla de bloqueos de horarios (vacaciones, días libres)
CREATE TABLE bloqueos_horarios (
    id INT PRIMARY KEY AUTO_INCREMENT,
    especialista_id INT NOT NULL,
    fecha_inicio DATETIME NOT NULL,
    fecha_fin DATETIME NOT NULL,
    motivo VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (especialista_id) REFERENCES especialistas(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabla de reservaciones
CREATE TABLE reservaciones (
    id INT PRIMARY KEY AUTO_INCREMENT,
    cliente_id INT NOT NULL,
    especialista_id INT NOT NULL,
    servicio_id INT NOT NULL,
    sucursal_id INT NOT NULL,
    fecha_hora DATETIME NOT NULL,
    duracion_minutos INT NOT NULL,
    estado ENUM('pendiente','confirmada','en_proceso','completada','cancelada','no_asistio') DEFAULT 'pendiente',
    confirmada_por INT,
    notas TEXT,
    precio DECIMAL(10,2),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (cliente_id) REFERENCES usuarios(id),
    FOREIGN KEY (especialista_id) REFERENCES especialistas(id),
    FOREIGN KEY (servicio_id) REFERENCES servicios(id),
    FOREIGN KEY (sucursal_id) REFERENCES sucursales(id),
    FOREIGN KEY (confirmada_por) REFERENCES usuarios(id),
    INDEX idx_fecha_hora (fecha_hora),
    INDEX idx_especialista_fecha (especialista_id, fecha_hora),
    INDEX idx_cliente (cliente_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabla de calificaciones
CREATE TABLE calificaciones (
    id INT PRIMARY KEY AUTO_INCREMENT,
    reservacion_id INT NOT NULL,
    cliente_id INT NOT NULL,
    especialista_id INT NOT NULL,
    calificacion INT NOT NULL CHECK (calificacion BETWEEN 1 AND 5),
    comentario TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (reservacion_id) REFERENCES reservaciones(id),
    FOREIGN KEY (cliente_id) REFERENCES usuarios(id),
    FOREIGN KEY (especialista_id) REFERENCES especialistas(id),
    UNIQUE KEY (reservacion_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabla de notificaciones
CREATE TABLE notificaciones (
    id INT PRIMARY KEY AUTO_INCREMENT,
    usuario_id INT NOT NULL,
    tipo ENUM('email','sms','whatsapp','sistema') NOT NULL,
    asunto VARCHAR(255),
    mensaje TEXT NOT NULL,
    enviado BOOLEAN DEFAULT FALSE,
    fecha_envio DATETIME,
    error TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabla de logs de seguridad
CREATE TABLE logs_seguridad (
    id INT PRIMARY KEY AUTO_INCREMENT,
    usuario_id INT,
    accion VARCHAR(100) NOT NULL,
    descripcion TEXT,
    ip_address VARCHAR(45),
    user_agent VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabla de configuraciones
CREATE TABLE configuraciones (
    id INT PRIMARY KEY AUTO_INCREMENT,
    clave VARCHAR(100) NOT NULL UNIQUE,
    valor TEXT,
    descripcion VARCHAR(255),
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ===================================
-- DATOS DE EJEMPLO - ESTADO DE QUERÉTARO
-- ===================================

-- Insertar roles
INSERT INTO roles (nombre, descripcion) VALUES
('superadmin', 'Administrador General del Sistema'),
('admin_sucursal', 'Administrador de Sucursal'),
('especialista', 'Profesional/Especialista'),
('cliente', 'Cliente/Usuario Final'),
('recepcionista', 'Recepcionista');

-- Insertar usuarios (password: ReserBot2024)
INSERT INTO usuarios (nombre, apellido, email, telefono, password_hash, rol_id, email_verificado, activo) VALUES
('Carlos', 'Rodríguez', 'admin@reserbot.com', '4421234567', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1, TRUE, TRUE),
('María', 'González', 'admin.centro@reserbot.com', '4421234568', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 2, TRUE, TRUE),
('José', 'Martínez', 'admin.juriquilla@reserbot.com', '4421234569', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 2, TRUE, TRUE),
('Dra. Ana', 'López', 'ana.lopez@reserbot.com', '4421234570', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 3, TRUE, TRUE),
('Dr. Roberto', 'Hernández', 'roberto.hernandez@reserbot.com', '4421234571', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 3, TRUE, TRUE),
('Lic. Patricia', 'Ramírez', 'patricia.ramirez@reserbot.com', '4421234572', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 3, TRUE, TRUE),
('Mtro. Fernando', 'Silva', 'fernando.silva@reserbot.com', '4421234573', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 3, TRUE, TRUE),
('Juan', 'Pérez', 'juan.perez@email.com', '4429876543', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 4, TRUE, TRUE),
('Laura', 'Sánchez', 'laura.sanchez@email.com', '4429876544', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 4, TRUE, TRUE),
('Sofía', 'Torres', 'sofia.torres@reserbot.com', '4421234574', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 5, TRUE, TRUE);

-- Insertar sucursales en Querétaro
INSERT INTO sucursales (nombre, direccion, ciudad, estado, codigo_postal, telefono, email, hora_apertura, hora_cierre) VALUES
('ReserBot Centro Histórico', 'Av. 5 de Mayo 45, Centro', 'Santiago de Querétaro', 'Querétaro', '76000', '4422001100', 'centro@reserbot.com', '08:00:00', '20:00:00'),
('ReserBot Juriquilla', 'Blvd. Juriquilla 3000, Juriquilla', 'Santiago de Querétaro', 'Querétaro', '76230', '4422001101', 'juriquilla@reserbot.com', '09:00:00', '21:00:00'),
('ReserBot Corregidora', 'Av. Tecnológico 150, El Pueblito', 'Corregidora', 'Querétaro', '76900', '4422001102', 'corregidora@reserbot.com', '08:00:00', '19:00:00');

-- Insertar días no laborables (feriados en Querétaro)
INSERT INTO dias_no_laborables (sucursal_id, fecha, descripcion) VALUES
(1, '2024-01-01', 'Año Nuevo'),
(1, '2024-02-05', 'Día de la Constitución'),
(1, '2024-03-18', 'Natalicio de Benito Juárez'),
(1, '2024-05-01', 'Día del Trabajo'),
(1, '2024-09-16', 'Día de la Independencia'),
(1, '2024-11-20', 'Día de la Revolución'),
(1, '2024-12-25', 'Navidad'),
(2, '2024-01-01', 'Año Nuevo'),
(2, '2024-12-25', 'Navidad'),
(3, '2024-01-01', 'Año Nuevo'),
(3, '2024-12-25', 'Navidad');

-- Insertar categorías de servicios
INSERT INTO categorias_servicios (nombre, descripcion) VALUES
('Medicina General', 'Consultas médicas generales y diagnósticos'),
('Odontología', 'Servicios dentales y de salud bucal'),
('Asesoría Legal', 'Consultas y servicios legales'),
('Asesoría Financiera', 'Planeación financiera y contable'),
('Barbería y Estética', 'Servicios de corte de cabello y estética'),
('Terapia Psicológica', 'Consultas psicológicas y terapias');

-- Insertar servicios
INSERT INTO servicios (categoria_id, sucursal_id, nombre, descripcion, duracion_minutos, precio) VALUES
-- Medicina General (Sucursal 1)
(1, 1, 'Consulta General', 'Consulta médica general', 30, 350.00),
(1, 1, 'Chequeo Preventivo', 'Chequeo médico completo', 60, 650.00),
-- Odontología (Sucursal 1)
(2, 1, 'Limpieza Dental', 'Limpieza dental profesional', 45, 450.00),
(2, 1, 'Consulta Odontológica', 'Revisión y diagnóstico dental', 30, 300.00),
-- Asesoría Legal (Sucursal 2)
(3, 2, 'Consulta Legal', 'Asesoría legal general', 60, 800.00),
(3, 2, 'Elaboración de Contratos', 'Redacción y revisión de contratos', 90, 1500.00),
-- Asesoría Financiera (Sucursal 2)
(4, 2, 'Consulta Financiera', 'Planeación financiera personal', 60, 700.00),
(4, 2, 'Declaración de Impuestos', 'Asesoría fiscal y declaraciones', 90, 1200.00),
-- Barbería (Sucursal 3)
(5, 3, 'Corte de Cabello', 'Corte de cabello para caballero', 30, 120.00),
(5, 3, 'Corte y Barba', 'Corte de cabello y arreglo de barba', 45, 180.00);

-- Insertar especialistas
INSERT INTO especialistas (usuario_id, sucursal_id, profesion, descripcion, experiencia_anos, calificacion_promedio, total_calificaciones) VALUES
(4, 1, 'Médico General', 'Médico especialista con amplia experiencia en medicina general y preventiva', 8, 4.8, 245),
(5, 1, 'Odontólogo', 'Especialista en odontología general y estética dental', 5, 4.9, 189),
(6, 2, 'Abogado Civilista', 'Abogada especializada en derecho civil y mercantil', 12, 4.7, 156),
(7, 2, 'Contador Público', 'Contador con experiencia en asesoría fiscal y financiera', 10, 4.6, 132);

-- Asignar servicios a especialistas
INSERT INTO especialista_servicios (especialista_id, servicio_id) VALUES
(1, 1), (1, 2),  -- Dra. Ana - Medicina General
(2, 3), (2, 4),  -- Dr. Roberto - Odontología
(3, 5), (3, 6),  -- Lic. Patricia - Legal
(4, 7), (4, 8);  -- Mtro. Fernando - Financiero

-- Insertar horarios de especialistas
-- Dra. Ana López (Lunes a Viernes)
INSERT INTO horarios_especialistas (especialista_id, dia_semana, hora_inicio, hora_fin) VALUES
(1, 'lunes', '09:00:00', '14:00:00'),
(1, 'lunes', '16:00:00', '19:00:00'),
(1, 'martes', '09:00:00', '14:00:00'),
(1, 'miercoles', '09:00:00', '14:00:00'),
(1, 'miercoles', '16:00:00', '19:00:00'),
(1, 'jueves', '09:00:00', '14:00:00'),
(1, 'viernes', '09:00:00', '13:00:00');

-- Dr. Roberto Hernández (Martes a Sábado)
INSERT INTO horarios_especialistas (especialista_id, dia_semana, hora_inicio, hora_fin) VALUES
(2, 'martes', '10:00:00', '14:00:00'),
(2, 'martes', '16:00:00', '20:00:00'),
(2, 'miercoles', '10:00:00', '14:00:00'),
(2, 'jueves', '10:00:00', '14:00:00'),
(2, 'jueves', '16:00:00', '20:00:00'),
(2, 'viernes', '10:00:00', '14:00:00'),
(2, 'sabado', '09:00:00', '14:00:00');

-- Lic. Patricia Ramírez (Lunes a Viernes)
INSERT INTO horarios_especialistas (especialista_id, dia_semana, hora_inicio, hora_fin) VALUES
(3, 'lunes', '10:00:00', '15:00:00'),
(3, 'lunes', '17:00:00', '20:00:00'),
(3, 'martes', '10:00:00', '15:00:00'),
(3, 'miercoles', '10:00:00', '15:00:00'),
(3, 'jueves', '10:00:00', '15:00:00'),
(3, 'jueves', '17:00:00', '20:00:00'),
(3, 'viernes', '10:00:00', '14:00:00');

-- Mtro. Fernando Silva (Lunes a Sábado)
INSERT INTO horarios_especialistas (especialista_id, dia_semana, hora_inicio, hora_fin) VALUES
(4, 'lunes', '09:00:00', '13:00:00'),
(4, 'martes', '09:00:00', '13:00:00'),
(4, 'martes', '15:00:00', '19:00:00'),
(4, 'miercoles', '09:00:00', '13:00:00'),
(4, 'jueves', '09:00:00', '13:00:00'),
(4, 'viernes', '09:00:00', '13:00:00'),
(4, 'viernes', '15:00:00', '19:00:00'),
(4, 'sabado', '09:00:00', '13:00:00');

-- Insertar algunas reservaciones de ejemplo
INSERT INTO reservaciones (cliente_id, especialista_id, servicio_id, sucursal_id, fecha_hora, duracion_minutos, estado, precio) VALUES
(8, 1, 1, 1, '2024-01-15 10:00:00', 30, 'completada', 350.00),
(9, 2, 3, 1, '2024-01-16 11:00:00', 45, 'completada', 450.00),
(8, 3, 5, 2, '2024-01-18 15:00:00', 60, 'confirmada', 800.00),
(9, 4, 7, 2, '2024-01-20 10:00:00', 60, 'pendiente', 700.00);

-- Insertar calificaciones de ejemplo
INSERT INTO calificaciones (reservacion_id, cliente_id, especialista_id, calificacion, comentario) VALUES
(1, 8, 1, 5, 'Excelente atención, muy profesional'),
(2, 9, 2, 5, 'Muy buena limpieza dental, recomendado');

-- Insertar configuraciones del sistema
INSERT INTO configuraciones (clave, valor, descripcion) VALUES
('notificaciones_email', '1', 'Activar notificaciones por email'),
('notificaciones_sms', '0', 'Activar notificaciones por SMS'),
('tiempo_anticipacion_reserva', '60', 'Minutos mínimos de anticipación para reservar'),
('tiempo_recordatorio', '1440', 'Minutos antes de la cita para enviar recordatorio (24 horas)'),
('permitir_cancelacion', '1', 'Permitir que clientes cancelen citas'),
('tiempo_limite_cancelacion', '120', 'Minutos antes de la cita para poder cancelar (2 horas)');

-- Crear índices adicionales para optimización
CREATE INDEX idx_usuarios_email ON usuarios(email);
CREATE INDEX idx_usuarios_rol ON usuarios(rol_id);
CREATE INDEX idx_sucursales_activo ON sucursales(activo);
CREATE INDEX idx_servicios_categoria ON servicios(categoria_id);
CREATE INDEX idx_servicios_sucursal ON servicios(sucursal_id);
CREATE INDEX idx_especialistas_sucursal ON especialistas(sucursal_id);
CREATE INDEX idx_reservaciones_estado ON reservaciones(estado);
CREATE INDEX idx_notificaciones_enviado ON notificaciones(enviado);
