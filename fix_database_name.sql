-- =====================================================
-- Script para verificar y ajustar el nombre de la base de datos
-- ReserBot Sistema de Reservaciones
-- =====================================================

-- Este script ayuda a resolver la discrepancia entre los nombres de base de datos
-- en config.php (i45com_reserbot) y database.sql (reserbot_db)

-- PASO 1: Verificar qué bases de datos existen
SELECT 
    SCHEMA_NAME as 'Base de Datos',
    DEFAULT_CHARACTER_SET_NAME as 'Charset',
    DEFAULT_COLLATION_NAME as 'Collation'
FROM information_schema.SCHEMATA 
WHERE SCHEMA_NAME IN ('reserbot_db', 'i45com_reserbot')
ORDER BY SCHEMA_NAME;

-- PASO 2: Si existe reserbot_db pero no i45com_reserbot, renombrar
-- NOTA: Esto solo funcionará en MySQL 5.1.23+ o MariaDB 10.5+
-- Para versiones anteriores, use mysqldump y restore con el nuevo nombre

-- Descomentar la siguiente línea si necesita renombrar:
-- RENAME DATABASE reserbot_db TO i45com_reserbot;

-- ALTERNATIVA: Crear la base de datos con el nombre correcto si no existe
CREATE DATABASE IF NOT EXISTS i45com_reserbot CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- PASO 3: Verificar que la base de datos i45com_reserbot tiene todas las tablas necesarias
USE i45com_reserbot;

-- Mostrar todas las tablas
SHOW TABLES;

-- Contar las tablas (debería haber al menos 15 tablas)
SELECT COUNT(*) as 'Total de Tablas' 
FROM information_schema.TABLES 
WHERE TABLE_SCHEMA = 'i45com_reserbot';

-- Verificar que existe la tabla de configuraciones
SELECT 
    TABLE_NAME as 'Tabla',
    TABLE_ROWS as 'Filas',
    CREATE_TIME as 'Fecha Creación'
FROM information_schema.TABLES 
WHERE TABLE_SCHEMA = 'i45com_reserbot' 
AND TABLE_NAME = 'configuraciones';

-- Si la tabla de configuraciones no existe o está vacía, ejecute database_configuraciones.sql

-- =====================================================
-- DIAGNÓSTICO DE PROBLEMAS DE AUTENTICACIÓN
-- =====================================================

-- Verificar usuarios administradores
SELECT 
    id,
    nombre,
    apellido,
    email,
    rol_id,
    activo,
    email_verificado,
    bloqueado_hasta,
    ultimo_acceso
FROM usuarios 
WHERE rol_id IN (1, 2)  -- superadmin y admin_sucursal
ORDER BY rol_id, id;

-- Verificar roles
SELECT * FROM roles ORDER BY id;

-- Verificar sesiones activas (si hay una tabla de sesiones)
-- SELECT * FROM sessions WHERE expires > NOW() LIMIT 10;

-- Verificar logs de seguridad recientes
SELECT 
    ls.id,
    ls.accion,
    ls.descripcion,
    u.nombre,
    u.email,
    ls.ip_address,
    ls.created_at
FROM logs_seguridad ls
LEFT JOIN usuarios u ON ls.usuario_id = u.id
ORDER BY ls.created_at DESC
LIMIT 20;

-- =====================================================
-- RESULTADOS ESPERADOS
-- =====================================================
-- 1. Una base de datos llamada 'i45com_reserbot' debe existir
-- 2. Debe tener al menos 15 tablas
-- 3. La tabla 'configuraciones' debe existir
-- 4. Debe haber al menos 1 usuario con rol_id = 1 (superadmin)
-- 5. Los usuarios deben estar activos (activo = 1)
