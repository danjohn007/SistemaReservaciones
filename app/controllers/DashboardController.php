<?php
/**
 * Controlador de Dashboard
 * Enruta a los diferentes dashboards según el rol del usuario
 */

class DashboardController extends BaseController {
    
    public function index() {
        $this->requireAuth();
        
        $role = $_SESSION['user_role'];
        
        // Redirigir según el rol
        switch ($role) {
            case 'superadmin':
                $this->adminDashboard();
                break;
            case 'admin_sucursal':
                $this->branchAdminDashboard();
                break;
            case 'especialista':
                $this->especialistaDashboard();
                break;
            case 'recepcionista':
                $this->recepcionistaDashboard();
                break;
            case 'cliente':
            default:
                $this->clienteDashboard();
                break;
        }
    }
    
    /**
     * Dashboard de Superadministrador
     */
    private function adminDashboard() {
        $sucursalModel = new Sucursal();
        $usuarioModel = new Usuario();
        $reservacionModel = new Reservacion();
        
        // Obtener estadísticas generales
        $sucursales = $sucursalModel->getAll();
        $stats = $reservacionModel->getEstadisticas();
        
        $this->view('dashboard/admin', [
            'title' => 'Dashboard - Administrador',
            'sucursales' => $sucursales,
            'stats' => $stats
        ]);
    }
    
    /**
     * Dashboard de Administrador de Sucursal
     */
    private function branchAdminDashboard() {
        // Por ahora mostrar mensaje
        $this->view('dashboard/branch_admin', [
            'title' => 'Dashboard - Admin. Sucursal'
        ]);
    }
    
    /**
     * Dashboard de Especialista
     */
    private function especialistaDashboard() {
        $especialistaModel = new Especialista();
        $reservacionModel = new Reservacion();
        
        // Obtener datos del especialista
        $especialista = $especialistaModel->findByUserId($_SESSION['user_id']);
        
        if (!$especialista) {
            $_SESSION['error'] = 'No se encontró su perfil de especialista';
            $this->redirect('');
        }
        
        // Obtener citas del día
        $hoy = date('Y-m-d');
        $citasHoy = $reservacionModel->getByEspecialista(
            $especialista['id'], 
            null,
            $hoy . ' 00:00:00',
            $hoy . ' 23:59:59'
        );
        
        // Obtener próximas citas
        $proximasCitas = $reservacionModel->getByEspecialista(
            $especialista['id'],
            'confirmada',
            date('Y-m-d H:i:s'),
            date('Y-m-d H:i:s', strtotime('+7 days'))
        );
        
        $this->view('dashboard/especialista', [
            'title' => 'Dashboard - Especialista',
            'especialista' => $especialista,
            'citasHoy' => $citasHoy,
            'proximasCitas' => $proximasCitas
        ]);
    }
    
    /**
     * Dashboard de Recepcionista
     */
    private function recepcionistaDashboard() {
        $this->view('dashboard/recepcionista', [
            'title' => 'Dashboard - Recepcionista'
        ]);
    }
    
    /**
     * Dashboard de Cliente
     */
    private function clienteDashboard() {
        $reservacionModel = new Reservacion();
        
        // Obtener reservaciones del cliente
        $reservaciones = $reservacionModel->getByCliente($_SESSION['user_id']);
        
        // Separar por estado
        $proximasCitas = [];
        $historial = [];
        
        foreach ($reservaciones as $reservacion) {
            if (in_array($reservacion['estado'], ['pendiente', 'confirmada'])) {
                $proximasCitas[] = $reservacion;
            } else {
                $historial[] = $reservacion;
            }
        }
        
        $this->view('dashboard/cliente', [
            'title' => 'Mis Citas',
            'proximasCitas' => $proximasCitas,
            'historial' => array_slice($historial, 0, 10) // Últimas 10
        ]);
    }
}
