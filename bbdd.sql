-- *** DB: proyectafp ***
DROP DATABASE IF EXISTS proyectafp;

CREATE DATABASE IF NOT EXISTS proyectafp CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE proyectafp;

-- select * from alumnos;
-- update users set rol_id=1 where email='jimenezelichesergio@gmail.com';

CREATE TABLE IF NOT EXISTS roles (
	id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(25) NOT NULL UNIQUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS users (
	id INT AUTO_INCREMENT PRIMARY KEY,
	email VARCHAR(50) NOT NULL UNIQUE,
	password VARCHAR(255) NOT NULL,
	rol_id INT NOT NULL,
	remember_token VARCHAR(255) NULL,
	activo BOOLEAN NOT NULL DEFAULT TRUE,
	created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	CONSTRAINT fk_user_rol FOREIGN KEY (rol_id) REFERENCES roles(id) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS familias (
	id INT AUTO_INCREMENT PRIMARY KEY,
	nombre VARCHAR(100) NOT NULL UNIQUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS ciclos (
	id INT AUTO_INCREMENT PRIMARY KEY,
	nombre VARCHAR(100) NOT NULL UNIQUE,
	nivel VARCHAR(50) NULL,
	familia_id INT NOT NULL,
	CONSTRAINT fk_ciclo_familia FOREIGN KEY (familia_id) REFERENCES familias(id) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS alumnos (
	id INT AUTO_INCREMENT PRIMARY KEY,
	nombre VARCHAR(25) NOT NULL,
	apellidos VARCHAR(50) NOT NULL,
	direccion VARCHAR(255) NOT NULL,
	telefono VARCHAR(25) NOT NULL,
	foto VARCHAR(255) NOT NULL,
	cv VARCHAR(255) NOT NULL,
    ciclo_id INT NOT NULL,
	user_id INT NOT NULL UNIQUE,
	CONSTRAINT fk_alumno_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_alumno_ciclo FOREIGN KEY (ciclo_id) REFERENCES ciclos(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS empresas (
	id INT AUTO_INCREMENT PRIMARY KEY,
	nombre VARCHAR(50) NOT NULL,
	telefono VARCHAR(25) NOT NULL,
	direccion VARCHAR(255) NOT NULL,
	logo VARCHAR(255) NOT NULL,
	user_id INT NOT NULL UNIQUE,
	CONSTRAINT fk_empresa_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS ofertas (
	id INT AUTO_INCREMENT PRIMARY KEY,
	empresa_id INT NOT NULL,
	titulo VARCHAR(255) NOT NULL,
	descripcion TEXT NOT NULL,
    ciclo_id INT NOT NULL,
	fecha_inicio DATE NOT NULL,
	fecha_fin DATE NULL,
    CONSTRAINT fk_oferta_ciclo FOREIGN KEY (ciclo_id) REFERENCES ciclos(id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT fk_oferta_empresa FOREIGN KEY (empresa_id) REFERENCES empresas(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS solicitudes (
	id INT AUTO_INCREMENT PRIMARY KEY,
	oferta_id INT NOT NULL,
	alumno_id INT NOT NULL,
	fecha_solicitud DATETIME DEFAULT CURRENT_TIMESTAMP,
	cv_visto BOOLEAN DEFAULT FALSE,
	CONSTRAINT fk_solicitud_oferta FOREIGN KEY (oferta_id) REFERENCES ofertas(id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT fk_solicitud_alumno FOREIGN KEY (alumno_id) REFERENCES alumnos(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- *** INSERTS INICIALES ***

-- 1. Roles y Users
INSERT INTO roles (id, nombre) VALUES
(1, 'Admin'),
(2, 'Empresa'),
(3, 'Alumno');

INSERT INTO users (email, password, rol_id, activo) VALUES
('admin@proyectafp.com', 'admin1234', 1, TRUE), -- Admin
('empresa1@example.com', '$2y$10$v0a0p.Q1Z7D4H5K6L7M8O9P.C5B3E2F1A0G9H8I7J6K5L4M3N2O1P0/s/Z/X/C/V/B/N/M/1234567890abcdef', 2, TRUE),
('empresa2@example.com', '$2y$10$v0a0p.Q1Z7D4H5K6L7M8O9P.C5B3E2F1A0G9H8I7J6K5L4M3N2O1P0/s/Z/X/C/V/B/N/M/1234567890abcdef', 2, TRUE),
('empresa3@example.com', '$2y$10$v0a0p.Q1Z7D4H5K6L7M8O9P.C5B3E2F1A0G9H8I7J6K5L4M3N2O1P0/s/Z/X/C/V/B/N/M/1234567890abcdef', 2, TRUE),
('empresa4@example.com', '$2y$10$v0a0p.Q1Z7D4H5K6L7M8O9P.C5B3E2F1A0G9H8I7J6K5L4M3N2O1P0/s/Z/X/C/V/B/N/M/1234567890abcdef', 2, TRUE),
('empresa5@example.com', '$2y$10$v0a0p.Q1Z7D4H5K6L7M8O9P.C5B3E2F1A0G9H8I7J6K5L4M3N2O1P0/s/Z/X/C/V/B/N/M/1234567890abcdef', 2, TRUE),
('alumno1@example.com', '$2y$10$v0a0p.Q1Z7D4H5K6L7M8O9P.C5B3E2F1A0G9H8I7J6K5L4M3N2O1P0/s/Z/X/C/V/B/N/M/1234567890abcdef', 3, TRUE),
('alumno2@example.com', '$2y$10$v0a0p.Q1Z7D4H5K6L7M8O9P.C5B3E2F1A0G9H8I7J6K5L4M3N2O1P0/s/Z/X/C/V/B/N/M/1234567890abcdef', 3, TRUE),
('alumno3@example.com', '$2y$10$v0a0p.Q1Z7D4H5K6L7M8O9P.C5B3E2F1A0G9H8I7J6K5L4M3N2O1P0/s/Z/X/C/V/B/N/M/1234567890abcdef', 3, TRUE),
('alumno4@example.com', '$2y$10$v0a0p.Q1Z7D4H5K6L7M8O9P.C5B3E2F1A0G9H8I7J6K5L4M3N2O1P0/s/Z/X/C/V/B/N/M/1234567890abcdef', 3, TRUE),
('alumno5@example.com', '$2y$10$v0a0p.Q1Z7D4H5K6L7M8O9P.C5B3E2F1A0G9H8I7J6K5L4M3N2O1P0/s/Z/X/C/V/B/N/M/1234567890abcdef', 3, TRUE),
('alumno6@example.com', '$2y$10$v0a0p.Q1Z7D4H5K6L7M8O9P.C5B3E2F1A0G9H8I7J6K5L4M3N2O1P0/s/Z/X/C/V/B/N/M/1234567890abcdef', 3, TRUE),
('alumno7@example.com', '$2y$10$v0a0p.Q1Z7D4H5K6L7M8O9P.C5B3E2F1A0G9H8I7J6K5L4M3N2O1P0/s/Z/X/C/V/B/N/M/1234567890abcdef', 3, TRUE),
('alumno8@example.com', '$2y$10$v0a0p.Q1Z7D4H5K6L7M8O9P.C5B3E2F1A0G9H8I7J6K5L4M3N2O1P0/s/Z/X/C/V/B/N/M/1234567890abcdef', 3, TRUE),
('alumno9@example.com', '$2y$10$v0a0p.Q1Z7D4H5K6L7M8O9P.C5B3E2F1A0G9H8I7J6K5L4M3N2O1P0/s/Z/X/C/V/B/N/M/1234567890abcdef', 3, TRUE),
('alumno10@example.com', '$2y$10$v0a0p.Q1Z7D4H5K6L7M8O9P.C5B3E2F1A0G9H8I7J6K5L4M3N2O1P0/s/Z/X/C/V/B/N/M/1234567890abcdef', 3, TRUE);

-- 2. Familias Profesionales
INSERT INTO familias (nombre) VALUES
('Actividades Físicas y Deportivas'),
('Administración y Gestión'),
('Informática y Comunicaciones'),
('Electricidad y Electrónica'),
('Instalación y Mantenimiento'),
('Fabricación Mecánica'),
('Energía y Agua');

-- 3. Ciclos (Adaptados a las familias y niveles de la imagen)
INSERT INTO ciclos (nombre, nivel, familia_id) VALUES
-- CCFF de Grado Básico
('Básico en Servicios Administrativos', 'Grado Básico', (SELECT id FROM familias WHERE nombre = 'Administración y Gestión')),
('Básico en Informática y Comunicaciones', 'Grado Básico', (SELECT id FROM familias WHERE nombre = 'Informática y Comunicaciones')),
('Básico en Programa Específico de Servicios Administrativos', 'Grado Básico', (SELECT id FROM familias WHERE nombre = 'Administración y Gestión')),
-- CCFF de Grado Medio
('Técnico en Gestión Administrativa', 'Grado Medio', (SELECT id FROM familias WHERE nombre = 'Administración y Gestión')),
('Técnico en Instalaciones Eléctricas y Automáticas', 'Grado Medio', (SELECT id FROM familias WHERE nombre = 'Electricidad y Electrónica')),
('Técnico en Instalaciones de Telecomunicaciones', 'Grado Medio', (SELECT id FROM familias WHERE nombre = 'Electricidad y Electrónica')),
('Técnico en Mantenimiento Electromecánico', 'Grado Medio', (SELECT id FROM familias WHERE nombre = 'Instalación y Mantenimiento')),
-- CCFF de Grado Superior
('Técnico Superior en Enseñanza y Animación Sociodeportiva', 'Grado Superior', (SELECT id FROM familias WHERE nombre = 'Actividades Físicas y Deportivas')),
('Técnico Superior en Acondicionamiento Físico', 'Grado Superior', (SELECT id FROM familias WHERE nombre = 'Actividades Físicas y Deportivas')),
('Técnico Superior en Administración y Finanzas', 'Grado Superior', (SELECT id FROM familias WHERE nombre = 'Administración y Gestión')),
('Técnico Superior en Administracion de Sistemas Informáticos en Red', 'Grado Superior', (SELECT id FROM familias WHERE nombre = 'Informática y Comunicaciones')),
('Técnico Superior en Desarrollo de Aplicaciones Web', 'Grado Superior', (SELECT id FROM familias WHERE nombre = 'Informática y Comunicaciones')),
('Técnico Superior en Desarrollo de Aplicaciones Multiplataforma', 'Grado Superior', (SELECT id FROM familias WHERE nombre = 'Informática y Comunicaciones')),
('Técnico Superior en Sistemas Electrotécnicos y Automatizados', 'Grado Superior', (SELECT id FROM familias WHERE nombre = 'Electricidad y Electrónica')),
('Técnico Superior en Sistemas de Telecomunicaciones e Informáticos', 'Grado Superior', (SELECT id FROM familias WHERE nombre = 'Electricidad y Electrónica')),
('Técnico Superior en Mecatrónica Industrial', 'Grado Superior', (SELECT id FROM familias WHERE nombre = 'Instalación y Mantenimiento')),
('Técnico Superior en Energías Renovables', 'Grado Superior', (SELECT id FROM familias WHERE nombre = 'Energía y Agua')),
-- Cursos de Especialización
('Especialización en Digitalización del Mantenimiento Industrial', 'Curso de Especialización', (SELECT id FROM familias WHERE nombre = 'Instalación y Mantenimiento')),
('Especialización en Ciberseguridad en Entornos de las Tecnologías de Operación', 'Curso de Especialización', (SELECT id FROM familias WHERE nombre = 'Electricidad y Electrónica')),
('Especialización en Inteligencia Artificial y Big Data', 'Curso de Especialización', (SELECT id FROM familias WHERE nombre = 'Informática y Comunicaciones'));

-- 4. Alumnos y Empresas 
INSERT INTO alumnos (nombre, apellidos, direccion, telefono, foto, cv, ciclo_id, user_id) VALUES
('Juan', 'Pérez García', 'Calle Falsa 123', '600111222', '/img/alumnos/1.jpg', '/resources/cvs/1.pdf', 1, (SELECT id FROM users WHERE email = 'alumno1@example.com')),
('María', 'López Fernández', 'Avenida Siempreviva 45', '600333444', '/img/alumnos/1.jpg', '/resources/cvs/1.pdf', 2, (SELECT id FROM users WHERE email = 'alumno2@example.com')),
('Carlos', 'Sánchez Ruiz', 'Plaza Mayor 7', '600555666', '/img/alumnos/1.jpg', '/resources/cvs/1.pdf', 3, (SELECT id FROM users WHERE email = 'alumno3@example.com')),
('Ana', 'Martín Gómez', 'Ronda del Sol 1', '600777888', '/img/alumnos/1.jpg', '/resources/cvs/1.pdf', 4, (SELECT id FROM users WHERE email = 'alumno4@example.com')),
('Pedro', 'Díaz Navarro', 'Pasaje de la Luna 3', '600999000', '/img/alumnos/1.jpg', '/resources/cvs/1.pdf', 5, (SELECT id FROM users WHERE email = 'alumno5@example.com')),
('Laura', 'Hernández Gil', 'Calle del Río 8', '611222333', '/img/alumnos/1.jpg', '/resources/cvs/1.pdf', 6, (SELECT id FROM users WHERE email = 'alumno6@example.com')),
('Sofía', 'Ramírez Vargas', 'Avenida Central 25', '622333444', '/img/alumnos/1.jpg', '/resources/cvs/1.pdf', 7, (SELECT id FROM users WHERE email = 'alumno7@example.com')),
('David', 'Jiménez Castro', 'Paseo de las Flores 10', '633444555', '/img/alumnos/1.jpg', '/resources/cvs/1.pdf', 8, (SELECT id FROM users WHERE email = 'alumno8@example.com')),
('Elena', 'Ruiz Moreno', 'Camino Real 15', '644555666', '/img/alumnos/1.jpg', '/resources/cvs/1.pdf', 9, (SELECT id FROM users WHERE email = 'alumno9@example.com')),
('Miguel', 'Santos Vidal', 'Calle Estrecha 5', '655666777', '/img/alumnos/1.jpg', '/resources/cvs/1.pdf', 10, (SELECT id FROM users WHERE email = 'alumno10@example.com'));

INSERT INTO empresas (nombre, telefono, direccion, logo, user_id) VALUES
('TecnoSoluciones S.L.', '911223344', 'Calle Innovación 10, Madrid', 'img/empresas/1.jpg', (SELECT id FROM users WHERE email = 'empresa1@example.com')),
('GlobalSoft IT', '933445566', 'Gran Vía 50, Barcelona', 'img/empresas/2.jpg', (SELECT id FROM users WHERE email = 'empresa2@example.com')),
('Consultoría Alfa', '955667788', 'Paseo de la Castellana 200, Madrid', 'img/empresas/3.jpg', (SELECT id FROM users WHERE email = 'empresa3@example.com')),
('Marketing Pro', '966778899', 'Calle del Mar 15, Valencia', 'img/empresas/4.jpg', (SELECT id FROM users WHERE email = 'empresa4@example.com')),
('Hostelería Deluxe', '922113344', 'Avenida de la Playa 7, Málaga', 'img/empresas/5.jpg', (SELECT id FROM users WHERE email = 'empresa5@example.com'));

-- 5. Ofertas
INSERT INTO ofertas (empresa_id, titulo, descripcion, ciclo_id, fecha_inicio, fecha_fin) VALUES
((SELECT id FROM empresas WHERE nombre = 'TecnoSoluciones S.L.'), 'Desarrollador Web Junior', 'Buscamos un desarrollador web junior con conocimientos en PHP y JavaScript.', (SELECT id FROM ciclos WHERE nombre = 'Técnico Superior en Desarrollo de Aplicaciones Web'), '2023-10-26', '2023-11-30'),
((SELECT id FROM empresas WHERE nombre = 'GlobalSoft IT'), 'Técnico de Sistemas Junior', 'Se requiere técnico para soporte y administración de redes.', (SELECT id FROM ciclos WHERE nombre = 'Técnico Superior en Desarrollo de Aplicaciones Multiplataforma'),'2023-10-20', '2023-11-15'),
((SELECT id FROM empresas WHERE nombre = 'Consultoría Alfa'), 'Administrativo Contable', 'Puesto de administrativo para gestión de facturas y contabilidad básica.', (SELECT id FROM ciclos WHERE nombre = 'Técnico Superior en Administracion de Sistemas Informáticos en Red'), '2023-10-25', '2023-12-05'),
((SELECT id FROM empresas WHERE nombre = 'Marketing Pro'), 'Asistente de Marketing Digital', 'Ayuda en la creación de campañas y gestión de redes sociales.', (SELECT id FROM ciclos WHERE nombre = 'Técnico en Gestión Administrativa'), '2023-10-28', '2023-11-20'),
((SELECT id FROM empresas WHERE nombre = 'Hostelería Deluxe'), 'Técnico de Mantenimiento Industrial', 'Puesto de técnico para mantenimiento de maquinaria y sistemas automatizados.', (SELECT id FROM ciclos WHERE nombre = 'Técnico en Mantenimiento Electromecánico'), '2023-11-01', NULL);

-- 6. Solicitudes
INSERT INTO solicitudes (oferta_id, alumno_id, fecha_solicitud, cv_visto) VALUES
((SELECT id FROM ofertas WHERE titulo = 'Desarrollador Web Junior'), (SELECT id FROM users WHERE email = 'alumno1@example.com'), '2023-10-27 10:00:00', FALSE),
((SELECT id FROM ofertas WHERE titulo = 'Técnico de Sistemas Junior'), (SELECT id FROM users WHERE email = 'alumno3@example.com'), '2023-10-21 11:30:00', FALSE),
((SELECT id FROM ofertas WHERE titulo = 'Administrativo Contable'), (SELECT id FROM users WHERE email = 'alumno4@example.com'), '2023-10-26 14:00:00', FALSE),
((SELECT id FROM ofertas WHERE titulo = 'Asistente de Marketing Digital'), (SELECT id FROM users WHERE email = 'alumno8@example.com'), '2023-10-29 09:00:00', FALSE),
((SELECT id FROM ofertas WHERE titulo = 'Técnico de Mantenimiento Industrial'), (SELECT id FROM users WHERE email = 'alumno10@example.com'), '2023-11-02 12:00:00', FALSE);