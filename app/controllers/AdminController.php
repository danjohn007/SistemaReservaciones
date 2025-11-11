<?php
/**
 * Controlador de Administración
 * Gestión de sucursales, servicios, especialistas y usuarios
 */

class AdminController extends BaseController {
    private $sucursalModel;
    private $servicioModel;
    private $especialistaModel;
    private $usuarioModel;
    
    public function __construct() {
        parent::__construct();
        $this->sucursalModel = new Sucursal();
        $this->servicioModel = new Servicio();
        $this->especialistaModel = new Especialista();
        $this->usuarioModel = new Usuario();
    }
    
    /**
     * Dashboard principal de administración
     */
    public function index() {
        $this->requireAuth();
        $this->requireRole(['superadmin', 'admin_sucursal']);
        
        $this->redirect('dashboard');
    }
    
    /**
     * Gestión de Sucursales
     */
    public function sucursales() {
        $this->requireAuth();
        $this->requireRole('superadmin');
        
        $sucursales = $this->sucursalModel->getAll(false);
        
        $this->view('admin/sucursales/index', [
            'title' => 'Gestión de Sucursales',
            'sucursales' => $sucursales,
            'csrf_token' => $this->generateCSRFToken()
        ]);
    }
    
    /**
     * Crear nueva sucursal
     */
    public function createSucursal() {
        $this->requireAuth();
        $this->requireRole('superadmin');
        
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $this->view('admin/sucursales/create', [
                'title' => 'Nueva Sucursal',
                'csrf_token' => $this->generateCSRFToken()
            ]);
            return;
        }
        
        // POST
        if (!$this->verifyCSRFToken($this->post('csrf_token'))) {
            $_SESSION['error'] = 'Token de seguridad inválido';
            $this->redirect('admin/sucursales');
        }
        
        $data = [
            'nombre' => $this->sanitize($this->post('nombre')),
            'direccion' => $this->sanitize($this->post('direccion')),
            'ciudad' => $this->sanitize($this->post('ciudad')),
            'estado' => $this->sanitize($this->post('estado')),
            'codigo_postal' => $this->sanitize($this->post('codigo_postal')),
            'telefono' => $this->sanitize($this->post('telefono')),
            'email' => $this->sanitize($this->post('email')),
            'hora_apertura' => $this->post('hora_apertura'),
            'hora_cierre' => $this->post('hora_cierre'),
            'activo' => $this->post('activo') ? 1 : 0
        ];
        
        try {
            $id = $this->sucursalModel->create($data);
            $this->logSecurity('sucursal_creada', "Sucursal ID: $id");
            $_SESSION['success'] = 'Sucursal creada exitosamente';
            $this->redirect('admin/sucursales');
        } catch (Exception $e) {
            error_log("Error al crear sucursal: " . $e->getMessage());
            $_SESSION['error'] = 'Error al crear la sucursal';
            $this->redirect('admin/createSucursal');
        }
    }
    
    /**
     * Gestión de Servicios
     */
    public function servicios() {
        $this->requireAuth();
        $this->requireRole(['superadmin', 'admin_sucursal']);
        
        $sucursales = $this->sucursalModel->getAll();
        $categorias = $this->servicioModel->getCategorias();
        
        $sucursalId = $this->get('sucursal_id');
        $servicios = [];
        
        if ($sucursalId) {
            $servicios = $this->servicioModel->getBySucursal($sucursalId, false);
        }
        
        $this->view('admin/servicios/index', [
            'title' => 'Gestión de Servicios',
            'sucursales' => $sucursales,
            'categorias' => $categorias,
            'servicios' => $servicios,
            'selectedSucursal' => $sucursalId,
            'csrf_token' => $this->generateCSRFToken()
        ]);
    }
    
    /**
     * Crear nuevo servicio
     */
    public function createServicio() {
        $this->requireAuth();
        $this->requireRole(['superadmin', 'admin_sucursal']);
        
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $sucursales = $this->sucursalModel->getAll();
            $categorias = $this->servicioModel->getCategorias();
            
            $this->view('admin/servicios/create', [
                'title' => 'Nuevo Servicio',
                'sucursales' => $sucursales,
                'categorias' => $categorias,
                'csrf_token' => $this->generateCSRFToken()
            ]);
            return;
        }
        
        // POST
        if (!$this->verifyCSRFToken($this->post('csrf_token'))) {
            $_SESSION['error'] = 'Token de seguridad inválido';
            $this->redirect('admin/servicios');
        }
        
        $data = [
            'categoria_id' => $this->post('categoria_id'),
            'sucursal_id' => $this->post('sucursal_id'),
            'nombre' => $this->sanitize($this->post('nombre')),
            'descripcion' => $this->sanitize($this->post('descripcion')),
            'duracion_minutos' => $this->post('duracion_minutos'),
            'precio' => $this->post('precio'),
            'activo' => $this->post('activo') ? 1 : 0
        ];
        
        try {
            $id = $this->servicioModel->create($data);
            $this->logSecurity('servicio_creado', "Servicio ID: $id");
            $_SESSION['success'] = 'Servicio creado exitosamente';
            $this->redirect('admin/servicios?sucursal_id=' . $data['sucursal_id']);
        } catch (Exception $e) {
            error_log("Error al crear servicio: " . $e->getMessage());
            $_SESSION['error'] = 'Error al crear el servicio';
            $this->redirect('admin/createServicio');
        }
    }
    
    /**
     * Gestión de Especialistas
     */
    public function especialistas() {
        $this->requireAuth();
        $this->requireRole(['superadmin', 'admin_sucursal']);
        
        $sucursales = $this->sucursalModel->getAll();
        
        $sucursalId = $this->get('sucursal_id');
        $especialistas = [];
        
        if ($sucursalId) {
            $especialistas = $this->especialistaModel->getBySucursal($sucursalId, false);
        }
        
        $this->view('admin/especialistas/index', [
            'title' => 'Gestión de Especialistas',
            'sucursales' => $sucursales,
            'especialistas' => $especialistas,
            'selectedSucursal' => $sucursalId,
            'csrf_token' => $this->generateCSRFToken()
        ]);
    }
    
    /**
     * Ver horarios de un especialista
     */
    public function horarios($especialistaId) {
        $this->requireAuth();
        $this->requireRole(['superadmin', 'admin_sucursal', 'especialista']);
        
        $especialista = $this->especialistaModel->findById($especialistaId);
        
        if (!$especialista) {
            $_SESSION['error'] = 'Especialista no encontrado';
            $this->redirect('admin/especialistas');
        }
        
        // Verificar permisos si es especialista
        if ($_SESSION['user_role'] === 'especialista') {
            $myEspecialista = $this->especialistaModel->findByUserId($_SESSION['user_id']);
            if (!$myEspecialista || $myEspecialista['id'] != $especialistaId) {
                $_SESSION['error'] = 'No tiene permiso para ver estos horarios';
                $this->redirect('dashboard');
            }
        }
        
        $horarios = $this->especialistaModel->getHorarios($especialistaId);
        $servicios = $this->especialistaModel->getServicios($especialistaId);
        
        $this->view('admin/especialistas/horarios', [
            'title' => 'Horarios de Especialista',
            'especialista' => $especialista,
            'horarios' => $horarios,
            'servicios' => $servicios,
            'csrf_token' => $this->generateCSRFToken()
        ]);
    }
    
    /**
     * Agregar horario a especialista
     */
    public function addHorario($especialistaId) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('admin/horarios/' . $especialistaId);
        }
        
        $this->requireAuth();
        $this->requireRole(['superadmin', 'admin_sucursal', 'especialista']);
        
        if (!$this->verifyCSRFToken($this->post('csrf_token'))) {
            $_SESSION['error'] = 'Token de seguridad inválido';
            $this->redirect('admin/horarios/' . $especialistaId);
        }
        
        $diaSemana = $this->post('dia_semana');
        $horaInicio = $this->post('hora_inicio');
        $horaFin = $this->post('hora_fin');
        
        try {
            $this->especialistaModel->addHorario($especialistaId, $diaSemana, $horaInicio, $horaFin);
            $this->logSecurity('horario_agregado', "Especialista ID: $especialistaId");
            $_SESSION['success'] = 'Horario agregado exitosamente';
        } catch (Exception $e) {
            error_log("Error al agregar horario: " . $e->getMessage());
            $_SESSION['error'] = 'Error al agregar el horario';
        }
        
        $this->redirect('admin/horarios/' . $especialistaId);
    }
    
    /**
     * Eliminar horario
     */
    public function deleteHorario($horarioId) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('dashboard');
        }
        
        $this->requireAuth();
        $this->requireRole(['superadmin', 'admin_sucursal', 'especialista']);
        
        if (!$this->verifyCSRFToken($this->post('csrf_token'))) {
            $_SESSION['error'] = 'Token de seguridad inválido';
            $this->redirect('dashboard');
        }
        
        $especialistaId = $this->post('especialista_id');
        
        try {
            $this->especialistaModel->deleteHorario($horarioId);
            $this->logSecurity('horario_eliminado', "Horario ID: $horarioId");
            $_SESSION['success'] = 'Horario eliminado exitosamente';
        } catch (Exception $e) {
            error_log("Error al eliminar horario: " . $e->getMessage());
            $_SESSION['error'] = 'Error al eliminar el horario';
        }
        
        $this->redirect('admin/horarios/' . $especialistaId);
    }
    
    /**
     * Reportes y estadísticas
     */
    public function reportes() {
        $this->requireAuth();
        $this->requireRole(['superadmin', 'admin_sucursal']);
        
        $reservacionModel = new Reservacion();
        
        // Obtener parámetros de fecha
        $fechaInicio = $this->get('fecha_inicio') ?: date('Y-m-01'); // Primer día del mes
        $fechaFin = $this->get('fecha_fin') ?: date('Y-m-t'); // Último día del mes
        
        // Obtener estadísticas
        $stats = $reservacionModel->getEstadisticas(null, $fechaInicio . ' 00:00:00', $fechaFin . ' 23:59:59');
        
        $this->view('admin/reportes/index', [
            'title' => 'Reportes y Estadísticas',
            'stats' => $stats,
            'fechaInicio' => $fechaInicio,
            'fechaFin' => $fechaFin
        ]);
    }
}
