<?php
/**
 * ReserBot - Front Controller
 * Punto de entrada principal de la aplicación
 */

// Cargar configuración
require_once 'config/config.php';
require_once 'config/database.php';

// Autoloader simple para cargar clases
spl_autoload_register(function ($class) {
    $paths = [
        ROOT_PATH . '/app/controllers/',
        ROOT_PATH . '/app/models/',
    ];
    
    foreach ($paths as $path) {
        $file = $path . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

// Obtener la URL solicitada
$url = isset($_GET['url']) ? $_GET['url'] : '';
$url = rtrim($url, '/');
$url = filter_var($url, FILTER_SANITIZE_URL);
$url = explode('/', $url);

// Determinar controlador, método y parámetros
$controllerName = !empty($url[0]) ? ucfirst($url[0]) . 'Controller' : 'HomeController';
$method = isset($url[1]) && !empty($url[1]) ? $url[1] : 'index';
$params = array_slice($url, 2);

// Verificar si existe el archivo del controlador
$controllerFile = ROOT_PATH . '/app/controllers/' . $controllerName . '.php';

if (file_exists($controllerFile)) {
    require_once $controllerFile;
    
    if (class_exists($controllerName)) {
        $controller = new $controllerName();
        
        if (method_exists($controller, $method)) {
            call_user_func_array([$controller, $method], $params);
        } else {
            // Método no encontrado
            header("HTTP/1.0 404 Not Found");
            require_once ROOT_PATH . '/app/views/errors/404.php';
        }
    } else {
        // Clase no encontrada
        header("HTTP/1.0 404 Not Found");
        require_once ROOT_PATH . '/app/views/errors/404.php';
    }
} else {
    // Controlador no encontrado
    header("HTTP/1.0 404 Not Found");
    require_once ROOT_PATH . '/app/views/errors/404.php';
}
