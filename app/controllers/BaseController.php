<?php
/**
 * Controlador Base
 * Funciones comunes para todos los controladores
 */

class BaseController {
    protected $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    /**
     * Cargar una vista
     */
    protected function view($view, $data = []) {
        extract($data);
        
        $viewFile = ROOT_PATH . '/app/views/' . $view . '.php';
        
        if (file_exists($viewFile)) {
            require_once $viewFile;
        } else {
            die("Vista no encontrada: $view");
        }
    }
    
    /**
     * Verificar si el usuario está autenticado
     */
    protected function isAuthenticated() {
        return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
    }
    
    /**
     * Verificar si el usuario tiene un rol específico
     */
    protected function hasRole($role) {
        if (!$this->isAuthenticated()) {
            return false;
        }
        
        if (is_array($role)) {
            return in_array($_SESSION['user_role'], $role);
        }
        
        return $_SESSION['user_role'] === $role;
    }
    
    /**
     * Requerir autenticación
     */
    protected function requireAuth() {
        if (!$this->isAuthenticated()) {
            $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
            $this->redirect('auth/login');
        }
    }
    
    /**
     * Requerir un rol específico
     */
    protected function requireRole($role) {
        $this->requireAuth();
        
        if (!$this->hasRole($role)) {
            $this->redirect('dashboard');
        }
    }
    
    /**
     * Redirigir a una URL
     */
    protected function redirect($path) {
        header('Location: ' . BASE_URL . '/' . $path);
        exit;
    }
    
    /**
     * Devolver JSON
     */
    protected function json($data, $status = 200) {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
    
    /**
     * Obtener datos POST
     */
    protected function post($key = null, $default = null) {
        if ($key === null) {
            return $_POST;
        }
        return isset($_POST[$key]) ? $_POST[$key] : $default;
    }
    
    /**
     * Obtener datos GET
     */
    protected function get($key = null, $default = null) {
        if ($key === null) {
            return $_GET;
        }
        return isset($_GET[$key]) ? $_GET[$key] : $default;
    }
    
    /**
     * Sanitizar entrada
     */
    protected function sanitize($data) {
        if (is_array($data)) {
            return array_map([$this, 'sanitize'], $data);
        }
        return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
    }
    
    /**
     * Validar email
     */
    protected function validateEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }
    
    /**
     * Validar teléfono (formato mexicano)
     */
    protected function validatePhone($phone) {
        $phone = preg_replace('/[^0-9]/', '', $phone);
        return strlen($phone) === 10;
    }
    
    /**
     * Generar token CSRF
     */
    protected function generateCSRFToken() {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
    
    /**
     * Verificar token CSRF
     */
    protected function verifyCSRFToken($token) {
        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    }
    
    /**
     * Registrar log de seguridad
     */
    protected function logSecurity($accion, $descripcion = '') {
        try {
            $usuario_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
            $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
            $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';
            
            $sql = "INSERT INTO logs_seguridad (usuario_id, accion, descripcion, ip_address, user_agent) 
                    VALUES (?, ?, ?, ?, ?)";
            $this->db->query($sql, [$usuario_id, $accion, $descripcion, $ip, $user_agent]);
        } catch (Exception $e) {
            error_log("Error al registrar log de seguridad: " . $e->getMessage());
        }
    }
}
