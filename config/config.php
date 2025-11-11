<?php
/**
 * ReserBot - Sistema de Reservaciones
 * Archivo de configuración principal
 */

// Configuración automática de URL base
function getBaseUrl() {
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
    $host = $_SERVER['HTTP_HOST'];
    $script = $_SERVER['SCRIPT_NAME'];
    $path = str_replace(basename($script), '', $script);
    return $protocol . "://" . $host . $path;
}

define('BASE_URL', rtrim(getBaseUrl(), '/'));
define('ROOT_PATH', dirname(__DIR__));

// Configuración de la base de datos
define('DB_HOST', 'localhost');
define('DB_NAME', 'i45com_reserbot');
define('DB_USER', 'i45com_reserbot');
define('DB_PASS', 'Danjohn007!');
define('DB_CHARSET', 'utf8mb4');

// Configuración de la aplicación
define('APP_NAME', 'ReserBot');
define('APP_VERSION', '1.0.0');
define('APP_ENV', 'development'); // development, production

// Configuración de sesiones
define('SESSION_LIFETIME', 3600); // 1 hora
define('SESSION_NAME', 'reserbot_session');

// Configuración de seguridad
define('PASSWORD_MIN_LENGTH', 8);
define('MAX_LOGIN_ATTEMPTS', 5);
define('LOCKOUT_TIME', 900); // 15 minutos

// Zona horaria por defecto
date_default_timezone_set('America/Mexico_City');

// Configuración de errores según el entorno
if (APP_ENV === 'development') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
    ini_set('log_errors', 1);
    ini_set('error_log', ROOT_PATH . '/logs/php_errors.log');
}

// Iniciar sesión
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.cookie_httponly', 1);
    ini_set('session.use_only_cookies', 1);
    session_name(SESSION_NAME);
    session_start();
}

/**
 * Helper function to get configuration values from database
 * @param string $clave Configuration key
 * @param mixed $default Default value if not found
 * @return mixed Configuration value
 */
function getConfig($clave, $default = null) {
    static $configCache = null;
    
    // Load configurations on first call
    if ($configCache === null) {
        try {
            require_once __DIR__ . '/database.php';
            require_once ROOT_PATH . '/app/models/Configuracion.php';
            
            $configModel = new Configuracion();
            $configCache = $configModel->getAllAsArray();
        } catch (Exception $e) {
            error_log("Error loading configurations: " . $e->getMessage());
            $configCache = [];
        }
    }
    
    return isset($configCache[$clave]) ? $configCache[$clave] : $default;
}
