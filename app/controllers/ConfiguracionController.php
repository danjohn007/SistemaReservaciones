<?php
/**
 * Controlador de Configuración
 * Gestiona las configuraciones del sistema
 */

class ConfiguracionController extends BaseController {
    private $configuracionModel;
    
    public function __construct() {
        parent::__construct();
        $this->configuracionModel = new Configuracion();
    }
    
    /**
     * Página principal de configuraciones
     */
    public function index() {
        $this->requireAuth();
        $this->requireRole('superadmin');
        
        $configuraciones = $this->configuracionModel->getGrouped();
        
        $this->view('admin/configuraciones/index', [
            'title' => 'Configuraciones del Sistema',
            'configuraciones' => $configuraciones,
            'csrf_token' => $this->generateCSRFToken()
        ]);
    }
    
    /**
     * Guardar configuraciones
     */
    public function save() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('configuracion');
        }
        
        $this->requireAuth();
        $this->requireRole('superadmin');
        
        // Verificar CSRF
        if (!$this->verifyCSRFToken($this->post('csrf_token'))) {
            $_SESSION['error'] = 'Token de seguridad inválido';
            $this->redirect('configuracion');
        }
        
        try {
            $postData = $this->post();
            unset($postData['csrf_token']); // Remover el token
            
            // Guardar cada configuración
            foreach ($postData as $clave => $valor) {
                // Sanitizar el valor según el tipo
                if (is_array($valor)) {
                    $valor = implode(',', $valor);
                }
                
                $this->configuracionModel->set($clave, $valor);
            }
            
            $this->logSecurity('configuraciones_actualizadas', 'Configuraciones del sistema actualizadas');
            $_SESSION['success'] = 'Configuraciones guardadas exitosamente';
            
        } catch (Exception $e) {
            error_log("Error al guardar configuraciones: " . $e->getMessage());
            $_SESSION['error'] = 'Error al guardar las configuraciones';
        }
        
        $this->redirect('configuracion');
    }
    
    /**
     * Subir logo del sitio
     */
    public function uploadLogo() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false, 'message' => 'Método no permitido'], 405);
        }
        
        $this->requireAuth();
        $this->requireRole('superadmin');
        
        // Verificar CSRF
        if (!$this->verifyCSRFToken($this->post('csrf_token'))) {
            $this->json(['success' => false, 'message' => 'Token de seguridad inválido'], 403);
        }
        
        if (!isset($_FILES['logo']) || $_FILES['logo']['error'] !== UPLOAD_ERR_OK) {
            $this->json(['success' => false, 'message' => 'Error al subir el archivo'], 400);
        }
        
        $file = $_FILES['logo'];
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $maxSize = 2 * 1024 * 1024; // 2MB
        
        // Validar tipo
        if (!in_array($file['type'], $allowedTypes)) {
            $this->json(['success' => false, 'message' => 'Tipo de archivo no permitido. Solo se permiten imágenes.'], 400);
        }
        
        // Validar tamaño
        if ($file['size'] > $maxSize) {
            $this->json(['success' => false, 'message' => 'El archivo es demasiado grande. Máximo 2MB.'], 400);
        }
        
        // Crear directorio si no existe
        $uploadDir = ROOT_PATH . '/public/images/config/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        // Generar nombre único
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = 'logo_' . time() . '.' . $extension;
        $destination = $uploadDir . $filename;
        
        // Mover archivo
        if (move_uploaded_file($file['tmp_name'], $destination)) {
            $logoUrl = BASE_URL . '/public/images/config/' . $filename;
            
            // Guardar en configuración
            $this->configuracionModel->set('sitio_logo', $logoUrl);
            
            $this->logSecurity('logo_actualizado', 'Logo del sitio actualizado');
            $this->json(['success' => true, 'message' => 'Logo actualizado exitosamente', 'url' => $logoUrl]);
        } else {
            $this->json(['success' => false, 'message' => 'Error al guardar el archivo'], 500);
        }
    }
    
    /**
     * Restablecer configuraciones a valores por defecto
     */
    public function reset() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('configuracion');
        }
        
        $this->requireAuth();
        $this->requireRole('superadmin');
        
        // Verificar CSRF
        if (!$this->verifyCSRFToken($this->post('csrf_token'))) {
            $_SESSION['error'] = 'Token de seguridad inválido';
            $this->redirect('configuracion');
        }
        
        try {
            // Valores por defecto
            $defaults = [
                'sitio_nombre' => 'ReserBot',
                'color_primario' => '#2563eb',
                'color_secundario' => '#3b82f6',
                'color_acento' => '#60a5fa',
                'horario_atencion_inicio' => '08:00',
                'horario_atencion_fin' => '20:00',
                'horario_atencion_dias' => 'Lunes a Viernes',
                'sistema_duracion_sesion' => '3600',
                'sistema_permitir_registro' => '1',
                'sistema_verificar_email' => '0'
            ];
            
            $this->configuracionModel->setMultiple($defaults);
            
            $this->logSecurity('configuraciones_restablecidas', 'Configuraciones restablecidas a valores por defecto');
            $_SESSION['success'] = 'Configuraciones restablecidas exitosamente';
            
        } catch (Exception $e) {
            error_log("Error al restablecer configuraciones: " . $e->getMessage());
            $_SESSION['error'] = 'Error al restablecer las configuraciones';
        }
        
        $this->redirect('configuracion');
    }
}
