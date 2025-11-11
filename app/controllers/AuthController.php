<?php
/**
 * Controlador de Autenticación
 */

class AuthController extends BaseController {
    private $usuarioModel;
    
    public function __construct() {
        parent::__construct();
        $this->usuarioModel = new Usuario();
    }
    
    /**
     * Página de login
     */
    public function login() {
        if ($this->isAuthenticated()) {
            $this->redirect('dashboard');
        }
        
        $this->view('auth/login', [
            'csrf_token' => $this->generateCSRFToken()
        ]);
    }
    
    /**
     * Procesar login
     */
    public function doLogin() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('auth/login');
        }
        
        // Verificar CSRF
        if (!$this->verifyCSRFToken($this->post('csrf_token'))) {
            $_SESSION['error'] = 'Token de seguridad inválido';
            $this->redirect('auth/login');
        }
        
        $email = $this->sanitize($this->post('email'));
        $password = $this->post('password');
        
        // Validar campos
        if (empty($email) || empty($password)) {
            $_SESSION['error'] = 'Por favor complete todos los campos';
            $this->redirect('auth/login');
        }
        
        // Verificar credenciales
        $user = $this->usuarioModel->verifyCredentials($email, $password);
        
        if (!$user) {
            $_SESSION['error'] = 'Credenciales incorrectas o cuenta bloqueada';
            $this->logSecurity('login_fallido', "Email: $email");
            $this->redirect('auth/login');
        }
        
        // Iniciar sesión
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['nombre'] . ' ' . $user['apellido'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_role'] = $user['rol_nombre'];
        $_SESSION['user_role_id'] = $user['rol_id'];
        
        // Actualizar último acceso
        $this->usuarioModel->updateLastAccess($user['id']);
        
        // Log de seguridad
        $this->logSecurity('login_exitoso', "Usuario: {$user['nombre']} {$user['apellido']}");
        
        // Redirigir
        $redirect = isset($_SESSION['redirect_after_login']) ? $_SESSION['redirect_after_login'] : 'dashboard';
        unset($_SESSION['redirect_after_login']);
        
        $this->redirect($redirect);
    }
    
    /**
     * Página de registro
     */
    public function register() {
        if ($this->isAuthenticated()) {
            $this->redirect('dashboard');
        }
        
        $this->view('auth/register', [
            'csrf_token' => $this->generateCSRFToken()
        ]);
    }
    
    /**
     * Procesar registro
     */
    public function doRegister() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('auth/register');
        }
        
        // Verificar CSRF
        if (!$this->verifyCSRFToken($this->post('csrf_token'))) {
            $_SESSION['error'] = 'Token de seguridad inválido';
            $this->redirect('auth/register');
        }
        
        $nombre = $this->sanitize($this->post('nombre'));
        $apellido = $this->sanitize($this->post('apellido'));
        $email = $this->sanitize($this->post('email'));
        $telefono = $this->sanitize($this->post('telefono'));
        $password = $this->post('password');
        $confirm_password = $this->post('confirm_password');
        
        // Validar campos
        $errors = [];
        
        if (empty($nombre) || empty($apellido)) {
            $errors[] = 'El nombre y apellido son obligatorios';
        }
        
        if (empty($email) || !$this->validateEmail($email)) {
            $errors[] = 'Email inválido';
        }
        
        if (!empty($telefono) && !$this->validatePhone($telefono)) {
            $errors[] = 'Teléfono inválido (debe ser 10 dígitos)';
        }
        
        if (empty($password) || strlen($password) < PASSWORD_MIN_LENGTH) {
            $errors[] = 'La contraseña debe tener al menos ' . PASSWORD_MIN_LENGTH . ' caracteres';
        }
        
        if ($password !== $confirm_password) {
            $errors[] = 'Las contraseñas no coinciden';
        }
        
        // Verificar si el email ya existe
        if ($this->usuarioModel->emailExists($email)) {
            $errors[] = 'El email ya está registrado';
        }
        
        if (!empty($errors)) {
            $_SESSION['error'] = implode('<br>', $errors);
            $_SESSION['old_data'] = $_POST;
            $this->redirect('auth/register');
        }
        
        // Crear usuario (rol cliente por defecto = 4)
        try {
            $userId = $this->usuarioModel->create([
                'nombre' => $nombre,
                'apellido' => $apellido,
                'email' => $email,
                'telefono' => $telefono,
                'password' => $password,
                'rol_id' => 4, // Cliente
                'email_verificado' => false
            ]);
            
            $this->logSecurity('registro_exitoso', "Usuario: $nombre $apellido, Email: $email");
            
            $_SESSION['success'] = 'Registro exitoso. Por favor inicie sesión';
            $this->redirect('auth/login');
            
        } catch (Exception $e) {
            error_log("Error en registro: " . $e->getMessage());
            $_SESSION['error'] = 'Error al crear la cuenta. Intente nuevamente';
            $_SESSION['old_data'] = $_POST;
            $this->redirect('auth/register');
        }
    }
    
    /**
     * Cerrar sesión
     */
    public function logout() {
        $this->logSecurity('logout', "Usuario: " . ($_SESSION['user_name'] ?? 'Desconocido'));
        
        session_unset();
        session_destroy();
        
        $this->redirect('');
    }
}
