<?php
/**
 * Controlador de Reservaciones
 */

class ReservacionController extends BaseController {
    private $reservacionModel;
    private $sucursalModel;
    private $servicioModel;
    private $especialistaModel;
    
    public function __construct() {
        parent::__construct();
        $this->reservacionModel = new Reservacion();
        $this->sucursalModel = new Sucursal();
        $this->servicioModel = new Servicio();
        $this->especialistaModel = new Especialista();
    }
    
    /**
     * Iniciar proceso de reservación
     */
    public function create() {
        $this->requireAuth();
        $this->requireRole('cliente');
        
        // Paso 1: Seleccionar sucursal
        $sucursales = $this->sucursalModel->getAll();
        
        $this->view('reservations/create', [
            'title' => 'Nueva Reservación',
            'sucursales' => $sucursales,
            'csrf_token' => $this->generateCSRFToken()
        ]);
    }
    
    /**
     * Obtener servicios por sucursal (AJAX)
     */
    public function getServicios($sucursalId) {
        $this->requireAuth();
        
        $servicios = $this->servicioModel->getBySucursal($sucursalId);
        $this->json(['success' => true, 'servicios' => $servicios]);
    }
    
    /**
     * Obtener especialistas por servicio (AJAX)
     */
    public function getEspecialistas($servicioId) {
        $this->requireAuth();
        
        $sucursalId = $this->get('sucursal_id');
        $especialistas = $this->especialistaModel->findByServicio($servicioId, $sucursalId);
        
        $this->json(['success' => true, 'especialistas' => $especialistas]);
    }
    
    /**
     * Obtener slots disponibles (AJAX)
     */
    public function getSlots() {
        $this->requireAuth();
        
        $especialistaId = $this->get('especialista_id');
        $fecha = $this->get('fecha');
        $duracionMinutos = $this->get('duracion_minutos');
        
        if (!$especialistaId || !$fecha || !$duracionMinutos) {
            $this->json(['success' => false, 'message' => 'Parámetros inválidos'], 400);
        }
        
        $slots = $this->reservacionModel->getSlotsDisponibles($especialistaId, $fecha, $duracionMinutos);
        
        $this->json(['success' => true, 'slots' => $slots]);
    }
    
    /**
     * Guardar reservación
     */
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('reservacion/create');
        }
        
        $this->requireAuth();
        $this->requireRole('cliente');
        
        // Verificar CSRF
        if (!$this->verifyCSRFToken($this->post('csrf_token'))) {
            $_SESSION['error'] = 'Token de seguridad inválido';
            $this->redirect('reservacion/create');
        }
        
        $sucursalId = $this->post('sucursal_id');
        $servicioId = $this->post('servicio_id');
        $especialistaId = $this->post('especialista_id');
        $fechaHora = $this->post('fecha_hora');
        $notas = $this->sanitize($this->post('notas'));
        
        // Validar campos
        if (!$sucursalId || !$servicioId || !$especialistaId || !$fechaHora) {
            $_SESSION['error'] = 'Por favor complete todos los campos requeridos';
            $this->redirect('reservacion/create');
        }
        
        // Obtener información del servicio
        $servicio = $this->servicioModel->findById($servicioId);
        if (!$servicio) {
            $_SESSION['error'] = 'Servicio no encontrado';
            $this->redirect('reservacion/create');
        }
        
        // Verificar disponibilidad
        if (!$this->reservacionModel->checkDisponibilidad($especialistaId, $fechaHora, $servicio['duracion_minutos'])) {
            $_SESSION['error'] = 'El horario seleccionado ya no está disponible';
            $this->redirect('reservacion/create');
        }
        
        try {
            // Crear reservación
            $reservacionId = $this->reservacionModel->create([
                'cliente_id' => $_SESSION['user_id'],
                'especialista_id' => $especialistaId,
                'servicio_id' => $servicioId,
                'sucursal_id' => $sucursalId,
                'fecha_hora' => $fechaHora,
                'duracion_minutos' => $servicio['duracion_minutos'],
                'precio' => $servicio['precio'],
                'notas' => $notas,
                'estado' => 'pendiente'
            ]);
            
            $this->logSecurity('reservacion_creada', "Reservación ID: $reservacionId");
            
            $_SESSION['success'] = 'Reservación creada exitosamente';
            $this->redirect('reservacion/view/' . $reservacionId);
            
        } catch (Exception $e) {
            error_log("Error al crear reservación: " . $e->getMessage());
            $_SESSION['error'] = 'Error al crear la reservación. Intente nuevamente';
            $this->redirect('reservacion/create');
        }
    }
    
    /**
     * Ver detalles de una reservación
     */
    public function view($id) {
        $this->requireAuth();
        
        $reservacion = $this->reservacionModel->findById($id);
        
        if (!$reservacion) {
            $_SESSION['error'] = 'Reservación no encontrada';
            $this->redirect('dashboard');
        }
        
        // Verificar permisos
        $role = $_SESSION['user_role'];
        $userId = $_SESSION['user_id'];
        
        $hasAccess = false;
        
        if ($role === 'superadmin' || $role === 'admin_sucursal' || $role === 'recepcionista') {
            $hasAccess = true;
        } elseif ($role === 'cliente' && $reservacion['cliente_id'] == $userId) {
            $hasAccess = true;
        } elseif ($role === 'especialista') {
            $especialista = $this->especialistaModel->findByUserId($userId);
            if ($especialista && $reservacion['especialista_id'] == $especialista['id']) {
                $hasAccess = true;
            }
        }
        
        if (!$hasAccess) {
            $_SESSION['error'] = 'No tiene permiso para ver esta reservación';
            $this->redirect('dashboard');
        }
        
        $this->view('reservations/view', [
            'title' => 'Detalles de Reservación',
            'reservacion' => $reservacion,
            'csrf_token' => $this->generateCSRFToken()
        ]);
    }
    
    /**
     * Cancelar reservación
     */
    public function cancel($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('dashboard');
        }
        
        $this->requireAuth();
        
        // Verificar CSRF
        if (!$this->verifyCSRFToken($this->post('csrf_token'))) {
            $_SESSION['error'] = 'Token de seguridad inválido';
            $this->redirect('reservacion/view/' . $id);
        }
        
        $reservacion = $this->reservacionModel->findById($id);
        
        if (!$reservacion) {
            $_SESSION['error'] = 'Reservación no encontrada';
            $this->redirect('dashboard');
        }
        
        // Verificar permisos
        $role = $_SESSION['user_role'];
        $userId = $_SESSION['user_id'];
        
        if ($role === 'cliente' && $reservacion['cliente_id'] != $userId) {
            $_SESSION['error'] = 'No tiene permiso para cancelar esta reservación';
            $this->redirect('dashboard');
        }
        
        // Verificar si se puede cancelar
        if (!in_array($reservacion['estado'], ['pendiente', 'confirmada'])) {
            $_SESSION['error'] = 'No se puede cancelar una reservación en estado: ' . $reservacion['estado'];
            $this->redirect('reservacion/view/' . $id);
        }
        
        // Cancelar
        try {
            $this->reservacionModel->cancelar($id, $userId);
            $this->logSecurity('reservacion_cancelada', "Reservación ID: $id");
            
            $_SESSION['success'] = 'Reservación cancelada exitosamente';
            $this->redirect('dashboard');
            
        } catch (Exception $e) {
            error_log("Error al cancelar reservación: " . $e->getMessage());
            $_SESSION['error'] = 'Error al cancelar la reservación';
            $this->redirect('reservacion/view/' . $id);
        }
    }
    
    /**
     * Confirmar reservación (solo especialista/admin)
     */
    public function confirm($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('dashboard');
        }
        
        $this->requireAuth();
        $this->requireRole(['especialista', 'admin_sucursal', 'superadmin', 'recepcionista']);
        
        // Verificar CSRF
        if (!$this->verifyCSRFToken($this->post('csrf_token'))) {
            $_SESSION['error'] = 'Token de seguridad inválido';
            $this->redirect('reservacion/view/' . $id);
        }
        
        try {
            $this->reservacionModel->updateEstado($id, 'confirmada', $_SESSION['user_id']);
            $this->logSecurity('reservacion_confirmada', "Reservación ID: $id");
            
            $_SESSION['success'] = 'Reservación confirmada exitosamente';
            $this->redirect('reservacion/view/' . $id);
            
        } catch (Exception $e) {
            error_log("Error al confirmar reservación: " . $e->getMessage());
            $_SESSION['error'] = 'Error al confirmar la reservación';
            $this->redirect('reservacion/view/' . $id);
        }
    }
    
    /**
     * Completar reservación (solo especialista)
     */
    public function complete($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('dashboard');
        }
        
        $this->requireAuth();
        $this->requireRole(['especialista', 'admin_sucursal', 'superadmin']);
        
        // Verificar CSRF
        if (!$this->verifyCSRFToken($this->post('csrf_token'))) {
            $_SESSION['error'] = 'Token de seguridad inválido';
            $this->redirect('reservacion/view/' . $id);
        }
        
        try {
            $this->reservacionModel->updateEstado($id, 'completada', $_SESSION['user_id']);
            $this->logSecurity('reservacion_completada', "Reservación ID: $id");
            
            $_SESSION['success'] = 'Reservación marcada como completada';
            $this->redirect('reservacion/view/' . $id);
            
        } catch (Exception $e) {
            error_log("Error al completar reservación: " . $e->getMessage());
            $_SESSION['error'] = 'Error al completar la reservación';
            $this->redirect('reservacion/view/' . $id);
        }
    }
}
