<?php
/**
 * Router script for PHP built-in web server
 * This handles URL rewriting when using php -S
 */

// Si es un archivo estático, dejarlo pasar
if (php_sapi_name() === 'cli-server') {
    $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    
    // Servir archivos estáticos directamente
    if (preg_match('/\.(?:png|jpg|jpeg|gif|css|js|ico|svg|woff|woff2|ttf|eot)$/', $path)) {
        return false; // Servir el archivo estático
    }
    
    // Si es index.php o test_*.php, dejarlo pasar
    if (preg_match('/\.(php)$/', $path)) {
        return false;
    }
    
    // Para todas las demás rutas, pasar a index.php con parámetro url
    $url = ltrim($path, '/');
    $_GET['url'] = $url;
    require_once __DIR__ . '/index.php';
    return true;
}

// Si no es el servidor built-in, requerir index.php normalmente
require_once __DIR__ . '/index.php';
