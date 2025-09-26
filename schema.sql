-- Esquema de base de datos para el Sistema de Tickets
-- Crea primero una base de datos (ej: tickets_db) y luego ejecuta esto dentro de ella.

CREATE TABLE IF NOT EXISTS tickets (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  mes VARCHAR(20) DEFAULT NULL,
  fecha DATE NOT NULL,
  area VARCHAR(100) NOT NULL,
  centro_costo VARCHAR(100) DEFAULT NULL,
  quien_solicita VARCHAR(120) NOT NULL,
  tema VARCHAR(180) NOT NULL,
  solicitud TEXT NOT NULL,
  solucion TEXT NULL,
  metodo VARCHAR(100) NULL,
  fecha_rta DATE NULL,
  estado VARCHAR(20) NOT NULL DEFAULT 'Pendiente',
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY idx_estado (estado),
  KEY idx_fecha (fecha)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
