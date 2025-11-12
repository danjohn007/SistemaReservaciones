-- =====================================================
-- SQL para agregar configuraciones al sistema ReserBot
-- Módulo de Configuraciones
-- =====================================================
-- 
-- NOTA: Ajuste el nombre de la base de datos según su configuración
-- Opción 1: USE i45com_reserbot;  (Producción)
-- Opción 2: USE reserbot_db;       (Desarrollo)
-- =====================================================

-- USE i45com_reserbot;

-- Eliminar configuraciones existentes que vamos a actualizar/reemplazar
DELETE FROM configuraciones WHERE clave IN (
    'sitio_nombre',
    'sitio_logo_url',
    'email_remitente',
    'email_remitente_nombre',
    'email_smtp_host',
    'email_smtp_port',
    'email_smtp_usuario',
    'email_smtp_password',
    'email_smtp_seguridad',
    'whatsapp_numero',
    'whatsapp_chatbot_activo',
    'telefono_principal',
    'telefono_secundario',
    'horario_atencion_inicio',
    'horario_atencion_fin',
    'horario_atencion_dias',
    'color_primario',
    'color_secundario',
    'color_acento',
    'color_texto',
    'color_fondo',
    'paypal_modo',
    'paypal_client_id',
    'paypal_secret',
    'paypal_email',
    'api_qr_proveedor',
    'api_qr_url',
    'api_qr_token',
    'api_qr_activo',
    'api_shelly_url',
    'api_shelly_token',
    'api_shelly_activo',
    'api_hikvision_url',
    'api_hikvision_usuario',
    'api_hikvision_password',
    'api_hikvision_activo',
    'sistema_zona_horaria',
    'sistema_idioma',
    'sistema_formato_fecha',
    'sistema_formato_hora',
    'sistema_moneda',
    'sistema_duracion_sesion',
    'sistema_mantenimiento',
    'sistema_registro_publico'
);

-- =====================================================
-- 1. NOMBRE DEL SITIO Y LOGOTIPO
-- =====================================================
INSERT INTO configuraciones (clave, valor, descripcion) VALUES
('sitio_nombre', 'ReserBot', 'Nombre del sitio web'),
('sitio_logo_url', '/public/images/logo.png', 'URL del logotipo del sitio');

-- =====================================================
-- 2. CONFIGURACIÓN DE CORREO ELECTRÓNICO
-- =====================================================
INSERT INTO configuraciones (clave, valor, descripcion) VALUES
('email_remitente', 'noreply@reserbot.com', 'Correo electrónico remitente del sistema'),
('email_remitente_nombre', 'ReserBot - Sistema de Reservaciones', 'Nombre del remitente de correos'),
('email_smtp_host', 'smtp.gmail.com', 'Servidor SMTP para envío de correos'),
('email_smtp_port', '587', 'Puerto del servidor SMTP'),
('email_smtp_usuario', '', 'Usuario para autenticación SMTP'),
('email_smtp_password', '', 'Contraseña para autenticación SMTP'),
('email_smtp_seguridad', 'tls', 'Tipo de seguridad SMTP (tls, ssl, none)');

-- =====================================================
-- 3. WHATSAPP CHATBOT
-- =====================================================
INSERT INTO configuraciones (clave, valor, descripcion) VALUES
('whatsapp_numero', '+52 442 123 4567', 'Número de WhatsApp del chatbot del sistema'),
('whatsapp_chatbot_activo', '1', 'Activar/desactivar chatbot de WhatsApp (1=activo, 0=inactivo)');

-- =====================================================
-- 4. TELÉFONOS DE CONTACTO Y HORARIOS DE ATENCIÓN
-- =====================================================
INSERT INTO configuraciones (clave, valor, descripcion) VALUES
('telefono_principal', '+52 442 123 4567', 'Teléfono principal de contacto'),
('telefono_secundario', '+52 442 765 4321', 'Teléfono secundario de contacto'),
('horario_atencion_inicio', '08:00', 'Hora de inicio de atención'),
('horario_atencion_fin', '20:00', 'Hora de fin de atención'),
('horario_atencion_dias', 'Lunes a Viernes', 'Días de atención al público');

-- =====================================================
-- 5. ESTILOS Y COLORES DEL SISTEMA
-- =====================================================
INSERT INTO configuraciones (clave, valor, descripcion) VALUES
('color_primario', '#2563eb', 'Color primario del sistema (hex)'),
('color_secundario', '#1e40af', 'Color secundario del sistema (hex)'),
('color_acento', '#3b82f6', 'Color de acento del sistema (hex)'),
('color_texto', '#1f2937', 'Color de texto principal (hex)'),
('color_fondo', '#f9fafb', 'Color de fondo del sistema (hex)');

-- =====================================================
-- 6. CONFIGURACIÓN DE PAYPAL
-- =====================================================
INSERT INTO configuraciones (clave, valor, descripcion) VALUES
('paypal_modo', 'sandbox', 'Modo de PayPal (sandbox o live)'),
('paypal_client_id', '', 'Client ID de PayPal'),
('paypal_secret', '', 'Secret de PayPal'),
('paypal_email', '', 'Email de la cuenta principal de PayPal');

-- =====================================================
-- 7. API PARA CREAR QRS MASIVOS
-- =====================================================
INSERT INTO configuraciones (clave, valor, descripcion) VALUES
('api_qr_proveedor', 'qrcode-monkey', 'Proveedor de API para generación de QR'),
('api_qr_url', 'https://api.qrcode-monkey.com/qr/custom', 'URL del endpoint de la API de QR'),
('api_qr_token', '', 'Token de autenticación para API de QR'),
('api_qr_activo', '0', 'Activar/desactivar API de QR (1=activo, 0=inactivo)');

-- =====================================================
-- 8. API PARA DISPOSITIVOS SHELLY RELAY
-- =====================================================
INSERT INTO configuraciones (clave, valor, descripcion) VALUES
('api_shelly_url', 'http://192.168.1.100', 'URL base de los dispositivos Shelly Relay'),
('api_shelly_token', '', 'Token de autenticación para dispositivos Shelly'),
('api_shelly_activo', '0', 'Activar/desactivar integración Shelly (1=activo, 0=inactivo)');

-- =====================================================
-- 9. API PARA DISPOSITIVOS HIKVISION
-- =====================================================
INSERT INTO configuraciones (clave, valor, descripcion) VALUES
('api_hikvision_url', 'http://192.168.1.64', 'URL base de los dispositivos HikVision'),
('api_hikvision_usuario', 'admin', 'Usuario para autenticación HikVision'),
('api_hikvision_password', '', 'Contraseña para autenticación HikVision'),
('api_hikvision_activo', '0', 'Activar/desactivar integración HikVision (1=activo, 0=inactivo)');

-- =====================================================
-- 10. CONFIGURACIONES GLOBALES RECOMENDADAS
-- =====================================================
INSERT INTO configuraciones (clave, valor, descripcion) VALUES
('sistema_zona_horaria', 'America/Mexico_City', 'Zona horaria del sistema'),
('sistema_idioma', 'es', 'Idioma predeterminado del sistema'),
('sistema_formato_fecha', 'd/m/Y', 'Formato de fecha del sistema'),
('sistema_formato_hora', 'H:i', 'Formato de hora del sistema (24h)'),
('sistema_moneda', 'MXN', 'Moneda predeterminada del sistema'),
('sistema_duracion_sesion', '3600', 'Duración de sesión en segundos (1 hora)'),
('sistema_mantenimiento', '0', 'Modo mantenimiento (1=activo, 0=inactivo)'),
('sistema_registro_publico', '1', 'Permitir registro público de usuarios (1=sí, 0=no)');

-- =====================================================
-- VERIFICAR CONFIGURACIONES INSERTADAS
-- =====================================================
SELECT 'Configuraciones insertadas correctamente' as mensaje, COUNT(*) as total FROM configuraciones;
