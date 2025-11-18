-- *** DB: proyectafp ***
DROP DATABASE IF EXISTS proyectafp;

CREATE DATABASE IF NOT EXISTS proyectafp CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

SET NAMES utf8mb4;
SET CHARACTER SET utf8mb4;
SET character_set_connection = utf8mb4;
SET character_set_server = utf8mb4;
SET character_set_database = utf8mb4;
SET collation_connection = utf8mb4_unicode_ci;
SET collation_server = utf8mb4_unicode_ci;

USE proyectafp;

-- select * from alumnos;
-- update users set rol_id=1 where email='jimenezelichesergio@gmail.com';
-- update ofertas set empresa_id= 6 where id=1;
-- update solicitudes set alumno_id=12 where id=1;
-- update ofertas set ciclo_id=8 where id=1;
-- select * from ofertas;

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
('admin@proyectafp.com', '$2y$10$2FH28cJ89YdKMQa9HUqqr..SjnLj8p0G4vjS/0EEkwtt0FA8.ejUu', 1, TRUE), -- Admin
('empresa1@example.com', '$2y$10$2FH28cJ89YdKMQa9HUqqr..SjnLj8p0G4vjS/0EEkwtt0FA8.ejUu', 2, TRUE),
('empresa2@example.com', '$2y$10$2FH28cJ89YdKMQa9HUqqr..SjnLj8p0G4vjS/0EEkwtt0FA8.ejUu', 2, TRUE),
('empresa3@example.com', '$2y$10$2FH28cJ89YdKMQa9HUqqr..SjnLj8p0G4vjS/0EEkwtt0FA8.ejUu', 2, TRUE),
('empresa4@example.com', '$2y$10$2FH28cJ89YdKMQa9HUqqr..SjnLj8p0G4vjS/0EEkwtt0FA8.ejUu', 2, TRUE),
('empresa5@example.com', '$2y$10$2FH28cJ89YdKMQa9HUqqr..SjnLj8p0G4vjS/0EEkwtt0FA8.ejUu', 2, TRUE),
('alumno1@example.com', '$2y$10$2FH28cJ89YdKMQa9HUqqr..SjnLj8p0G4vjS/0EEkwtt0FA8.ejUu', 3, TRUE),
('alumno2@example.com', '$2y$10$2FH28cJ89YdKMQa9HUqqr..SjnLj8p0G4vjS/0EEkwtt0FA8.ejUu', 3, TRUE),
('alumno3@example.com', '$2y$10$2FH28cJ89YdKMQa9HUqqr..SjnLj8p0G4vjS/0EEkwtt0FA8.ejUu', 3, TRUE),
('alumno4@example.com', '$2y$10$2FH28cJ89YdKMQa9HUqqr..SjnLj8p0G4vjS/0EEkwtt0FA8.ejUu', 3, TRUE),
('alumno5@example.com', '$2y$10$2FH28cJ89YdKMQa9HUqqr..SjnLj8p0G4vjS/0EEkwtt0FA8.ejUu', 3, TRUE),
('alumno6@example.com', '$2y$10$2FH28cJ89YdKMQa9HUqqr..SjnLj8p0G4vjS/0EEkwtt0FA8.ejUu', 3, TRUE),
('alumno7@example.com', '$2y$10$2FH28cJ89YdKMQa9HUqqr..SjnLj8p0G4vjS/0EEkwtt0FA8.ejUu', 3, TRUE),
('alumno8@example.com', '$2y$10$2FH28cJ89YdKMQa9HUqqr..SjnLj8p0G4vjS/0EEkwtt0FA8.ejUu', 3, TRUE),
('alumno9@example.com', '$2y$10$2FH28cJ89YdKMQa9HUqqr..SjnLj8p0G4vjS/0EEkwtt0FA8.ejUu', 3, TRUE),
('alumno10@example.com', '$2y$10$2FH28cJ89YdKMQa9HUqqr..SjnLj8p0G4vjS/0EEkwtt0FA8.ejUu', 3, TRUE);

-- 2. Familias Profesionales
INSERT INTO familias (nombre) VALUES
('Actividades Físicas y Deportivas'),
('Administración y Gestión'),
('Informática y Comunicaciones'),
('Electricidad y Electrónica'),
('Instalación y Mantenimiento'),
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
('Juan', 'Pérez García', 'Calle Falsa 123', '667281918', '/img/alumnos/1.jpg', '/resources/cvs/1.pdf', 1, 7),
('María', 'López Fernández', 'Avenida Siempreviva 45', '616290154', '/img/alumnos/1.jpg', '/resources/cvs/1.pdf', 2, 8),
('Carlos', 'Sánchez Ruiz', 'Plaza Mayor 7', '616892615', '/img/alumnos/1.jpg', '/resources/cvs/1.pdf', 3, 9),
('Ana', 'Martín Gómez', 'Ronda del Sol 1', '673829134', '/img/alumnos/1.jpg', '/resources/cvs/1.pdf', 4, 10),
('Pedro', 'Díaz Navarro', 'Pasaje de la Luna 3', '664020193', '/img/alumnos/1.jpg', '/resources/cvs/1.pdf', 5, 11),
('Laura', 'Hernández Gil', 'Calle del Río 8', '612093091', '/img/alumnos/1.jpg', '/resources/cvs/1.pdf', 6, 12),
('Sofía', 'Ramírez Vargas', 'Avenida Central 25', '649012345', '/img/alumnos/1.jpg', '/resources/cvs/1.pdf', 7, 13),
('David', 'Jiménez Castro', 'Paseo de las Flores 10', '647380291', '/img/alumnos/1.jpg', '/resources/cvs/1.pdf', 8, 14),
('Elena', 'Ruiz Moreno', 'Camino Real 15', '647582918', '/img/alumnos/1.jpg', '/resources/cvs/1.pdf', 9, 15),
('Miguel', 'Santos Vidal', 'Calle Estrecha 5', '659102983', '/img/alumnos/1.jpg', '/resources/cvs/1.pdf', 10, 16);

INSERT INTO empresas (nombre, telefono, direccion, logo, user_id) VALUES
('NTT Data', '953761298', 'Calle Innovación 10', 'img/empresas/1.jpg', 2),
('Nter', '953239819', 'Gran Vía 50', 'img/empresas/2.jpg', 3),
('Asesoría Montijano', '953981234', 'Paseo de la Castellana 200', 'img/empresas/3.jpg', 4),
('Sumur Digital', '953982309', 'Calle del Mar 15', 'img/empresas/4.jpg', 5),
('Embarba', '953901243', 'Avenida de la Playa 7', 'img/empresas/5.jpg', 6);

-- 5. Ofertas
INSERT INTO ofertas (empresa_id, titulo, descripcion, ciclo_id, fecha_inicio, fecha_fin) VALUES
(1, 'Desarrollador Web Junior', 'Buscamos un desarrollador web junior con conocimientos en PHP y JavaScript.', (SELECT id FROM ciclos WHERE nombre = 'Técnico Superior en Desarrollo de Aplicaciones Web'), '2025-10-26', '2025-11-30'),
(2, 'Técnico de Sistemas Junior', 'Se requiere técnico para soporte y administración de redes.', (SELECT id FROM ciclos WHERE nombre = 'Técnico Superior en Desarrollo de Aplicaciones Multiplataforma'),'2025-10-20', '2025-11-15'),
(3, 'Administrativo Contable', 'Puesto de administrativo para gestión de facturas y contabilidad básica.', (SELECT id FROM ciclos WHERE nombre = 'Técnico Superior en Administracion de Sistemas Informáticos en Red'), '2025-10-25', '2025-12-05'),
(4, 'Asistente de Marketing Digital', 'Ayuda en la creación de campañas y gestión de redes sociales.', (SELECT id FROM ciclos WHERE nombre = 'Técnico en Gestión Administrativa'), '2025-10-28', '2025-11-20'),
(5, 'Técnico de Mantenimiento Industrial', 'Puesto de técnico para mantenimiento de maquinaria de ascensores.', (SELECT id FROM ciclos WHERE nombre = 'Técnico en Mantenimiento Electromecánico'), '2025-11-01', '2025-12-12');

-- 6. Solicitudes
INSERT INTO solicitudes (oferta_id, alumno_id, fecha_solicitud, cv_visto) VALUES
((SELECT id FROM ofertas WHERE titulo = 'Desarrollador Web Junior'), 1, '2025-10-27 10:00:00', FALSE),
((SELECT id FROM ofertas WHERE titulo = 'Técnico de Sistemas Junior'), 2, '2025-10-21 11:30:00', FALSE),
((SELECT id FROM ofertas WHERE titulo = 'Administrativo Contable'), 3, '2025-10-26 14:00:00', FALSE),
((SELECT id FROM ofertas WHERE titulo = 'Asistente de Marketing Digital'), 4, '2025-10-29 09:00:00', FALSE),
((SELECT id FROM ofertas WHERE titulo = 'Técnico de Mantenimiento Industrial'), 5, '2025-11-02 12:00:00', FALSE);