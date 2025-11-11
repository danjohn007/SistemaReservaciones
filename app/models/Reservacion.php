<?php
/**
 * Modelo de Reservación
 */

class Reservacion {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    /**
     * Crear reservación
     */
    public function create($data) {
        $sql = "INSERT INTO reservaciones (cliente_id, especialista_id, servicio_id, sucursal_id, 
                fecha_hora, duracion_minutos, estado, notas, precio) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $params = [
            $data['cliente_id'],
            $data['especialista_id'],
            $data['servicio_id'],
            $data['sucursal_id'],
            $data['fecha_hora'],
            $data['duracion_minutos'],
            $data['estado'] ?? 'pendiente',
            $data['notas'] ?? null,
            $data['precio']
        ];
        
        $this->db->query($sql, $params);
        return $this->db->lastInsertId();
    }
    
    /**
     * Verificar disponibilidad de especialista
     */
    public function checkDisponibilidad($especialistaId, $fechaHora, $duracionMinutos, $excludeReservacionId = null) {
        $fechaFin = date('Y-m-d H:i:s', strtotime($fechaHora) + ($duracionMinutos * 60));
        
        $sql = "SELECT COUNT(*) as total FROM reservaciones 
                WHERE especialista_id = ? 
                AND estado NOT IN ('cancelada', 'no_asistio')
                AND (
                    (fecha_hora <= ? AND DATE_ADD(fecha_hora, INTERVAL duracion_minutos MINUTE) > ?) OR
                    (fecha_hora < ? AND DATE_ADD(fecha_hora, INTERVAL duracion_minutos MINUTE) >= ?) OR
                    (fecha_hora >= ? AND fecha_hora < ?)
                )";
        
        $params = [$especialistaId, $fechaHora, $fechaHora, $fechaFin, $fechaFin, $fechaHora, $fechaFin];
        
        if ($excludeReservacionId) {
            $sql .= " AND id != ?";
            $params[] = $excludeReservacionId;
        }
        
        $stmt = $this->db->query($sql, $params);
        $result = $stmt->fetch();
        
        return $result['total'] == 0;
    }
    
    /**
     * Obtener reservaciones por cliente
     */
    public function getByCliente($clienteId, $estado = null) {
        $sql = "SELECT r.*, 
                u.nombre as especialista_nombre, u.apellido as especialista_apellido,
                s.nombre as servicio_nombre, s.duracion_minutos as servicio_duracion,
                suc.nombre as sucursal_nombre, suc.direccion as sucursal_direccion,
                e.profesion
                FROM reservaciones r
                INNER JOIN especialistas e ON r.especialista_id = e.id
                INNER JOIN usuarios u ON e.usuario_id = u.id
                INNER JOIN servicios s ON r.servicio_id = s.id
                INNER JOIN sucursales suc ON r.sucursal_id = suc.id
                WHERE r.cliente_id = ?";
        
        $params = [$clienteId];
        
        if ($estado) {
            $sql .= " AND r.estado = ?";
            $params[] = $estado;
        }
        
        $sql .= " ORDER BY r.fecha_hora DESC";
        
        $stmt = $this->db->query($sql, $params);
        return $stmt->fetchAll();
    }
    
    /**
     * Obtener reservaciones por especialista
     */
    public function getByEspecialista($especialistaId, $estado = null, $fechaInicio = null, $fechaFin = null) {
        $sql = "SELECT r.*, 
                c.nombre as cliente_nombre, c.apellido as cliente_apellido, c.telefono as cliente_telefono,
                s.nombre as servicio_nombre, s.duracion_minutos as servicio_duracion,
                suc.nombre as sucursal_nombre
                FROM reservaciones r
                INNER JOIN usuarios c ON r.cliente_id = c.id
                INNER JOIN servicios s ON r.servicio_id = s.id
                INNER JOIN sucursales suc ON r.sucursal_id = suc.id
                WHERE r.especialista_id = ?";
        
        $params = [$especialistaId];
        
        if ($estado) {
            $sql .= " AND r.estado = ?";
            $params[] = $estado;
        }
        
        if ($fechaInicio && $fechaFin) {
            $sql .= " AND r.fecha_hora BETWEEN ? AND ?";
            $params[] = $fechaInicio;
            $params[] = $fechaFin;
        }
        
        $sql .= " ORDER BY r.fecha_hora ASC";
        
        $stmt = $this->db->query($sql, $params);
        return $stmt->fetchAll();
    }
    
    /**
     * Obtener reservaciones por sucursal
     */
    public function getBySucursal($sucursalId, $estado = null, $fechaInicio = null, $fechaFin = null) {
        $sql = "SELECT r.*, 
                c.nombre as cliente_nombre, c.apellido as cliente_apellido,
                u.nombre as especialista_nombre, u.apellido as especialista_apellido,
                s.nombre as servicio_nombre
                FROM reservaciones r
                INNER JOIN usuarios c ON r.cliente_id = c.id
                INNER JOIN especialistas e ON r.especialista_id = e.id
                INNER JOIN usuarios u ON e.usuario_id = u.id
                INNER JOIN servicios s ON r.servicio_id = s.id
                WHERE r.sucursal_id = ?";
        
        $params = [$sucursalId];
        
        if ($estado) {
            $sql .= " AND r.estado = ?";
            $params[] = $estado;
        }
        
        if ($fechaInicio && $fechaFin) {
            $sql .= " AND r.fecha_hora BETWEEN ? AND ?";
            $params[] = $fechaInicio;
            $params[] = $fechaFin;
        }
        
        $sql .= " ORDER BY r.fecha_hora ASC";
        
        $stmt = $this->db->query($sql, $params);
        return $stmt->fetchAll();
    }
    
    /**
     * Obtener reservación por ID
     */
    public function findById($id) {
        $sql = "SELECT r.*, 
                c.nombre as cliente_nombre, c.apellido as cliente_apellido, c.email as cliente_email, c.telefono as cliente_telefono,
                u.nombre as especialista_nombre, u.apellido as especialista_apellido,
                e.profesion,
                s.nombre as servicio_nombre, s.descripcion as servicio_descripcion,
                suc.nombre as sucursal_nombre, suc.direccion as sucursal_direccion, suc.telefono as sucursal_telefono
                FROM reservaciones r
                INNER JOIN usuarios c ON r.cliente_id = c.id
                INNER JOIN especialistas e ON r.especialista_id = e.id
                INNER JOIN usuarios u ON e.usuario_id = u.id
                INNER JOIN servicios s ON r.servicio_id = s.id
                INNER JOIN sucursales suc ON r.sucursal_id = suc.id
                WHERE r.id = ? LIMIT 1";
        
        $stmt = $this->db->query($sql, [$id]);
        return $stmt->fetch();
    }
    
    /**
     * Actualizar estado de reservación
     */
    public function updateEstado($id, $estado, $confirmedBy = null) {
        $sql = "UPDATE reservaciones SET estado = ?, confirmada_por = ? WHERE id = ?";
        $this->db->query($sql, [$estado, $confirmedBy, $id]);
        return true;
    }
    
    /**
     * Actualizar reservación
     */
    public function update($id, $data) {
        $fields = [];
        $params = [];
        
        $allowedFields = ['fecha_hora', 'duracion_minutos', 'estado', 'notas', 'precio'];
        
        foreach ($allowedFields as $field) {
            if (isset($data[$field])) {
                $fields[] = "$field = ?";
                $params[] = $data[$field];
            }
        }
        
        if (empty($fields)) {
            return false;
        }
        
        $params[] = $id;
        $sql = "UPDATE reservaciones SET " . implode(', ', $fields) . " WHERE id = ?";
        
        $this->db->query($sql, $params);
        return true;
    }
    
    /**
     * Cancelar reservación
     */
    public function cancelar($id, $userId) {
        return $this->updateEstado($id, 'cancelada', $userId);
    }
    
    /**
     * Obtener slots disponibles para una fecha
     */
    public function getSlotsDisponibles($especialistaId, $fecha, $duracionMinutos) {
        // Obtener horarios del especialista para ese día
        $diaSemana = $this->getDiaSemana($fecha);
        
        $sql = "SELECT hora_inicio, hora_fin FROM horarios_especialistas 
                WHERE especialista_id = ? AND dia_semana = ? AND activo = 1";
        
        $stmt = $this->db->query($sql, [$especialistaId, $diaSemana]);
        $horarios = $stmt->fetchAll();
        
        if (empty($horarios)) {
            return [];
        }
        
        // Obtener reservaciones existentes
        $fechaInicio = $fecha . ' 00:00:00';
        $fechaFin = $fecha . ' 23:59:59';
        
        $sql = "SELECT fecha_hora, duracion_minutos FROM reservaciones 
                WHERE especialista_id = ? AND fecha_hora BETWEEN ? AND ? 
                AND estado NOT IN ('cancelada', 'no_asistio')
                ORDER BY fecha_hora";
        
        $stmt = $this->db->query($sql, [$especialistaId, $fechaInicio, $fechaFin]);
        $reservaciones = $stmt->fetchAll();
        
        // Generar slots disponibles
        $slots = [];
        
        foreach ($horarios as $horario) {
            $inicio = strtotime($fecha . ' ' . $horario['hora_inicio']);
            $fin = strtotime($fecha . ' ' . $horario['hora_fin']);
            
            $current = $inicio;
            while ($current + ($duracionMinutos * 60) <= $fin) {
                $slotInicio = date('Y-m-d H:i:s', $current);
                $slotFin = date('Y-m-d H:i:s', $current + ($duracionMinutos * 60));
                
                // Verificar si el slot está disponible
                $disponible = true;
                foreach ($reservaciones as $reservacion) {
                    $resInicio = strtotime($reservacion['fecha_hora']);
                    $resFin = $resInicio + ($reservacion['duracion_minutos'] * 60);
                    
                    if (($current >= $resInicio && $current < $resFin) || 
                        ($current + ($duracionMinutos * 60) > $resInicio && $current < $resInicio)) {
                        $disponible = false;
                        break;
                    }
                }
                
                if ($disponible && $current >= time()) {
                    $slots[] = [
                        'inicio' => $slotInicio,
                        'fin' => $slotFin,
                        'hora' => date('H:i', $current)
                    ];
                }
                
                $current += ($duracionMinutos * 60);
            }
        }
        
        return $slots;
    }
    
    /**
     * Obtener día de la semana en español
     */
    private function getDiaSemana($fecha) {
        $dias = ['domingo', 'lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado'];
        return $dias[date('w', strtotime($fecha))];
    }
    
    /**
     * Estadísticas de reservaciones
     */
    public function getEstadisticas($sucursalId = null, $fechaInicio = null, $fechaFin = null) {
        $where = [];
        $params = [];
        
        if ($sucursalId) {
            $where[] = "r.sucursal_id = ?";
            $params[] = $sucursalId;
        }
        
        if ($fechaInicio && $fechaFin) {
            $where[] = "r.fecha_hora BETWEEN ? AND ?";
            $params[] = $fechaInicio;
            $params[] = $fechaFin;
        }
        
        $whereClause = !empty($where) ? "WHERE " . implode(" AND ", $where) : "";
        
        $sql = "SELECT 
                COUNT(*) as total,
                SUM(CASE WHEN estado = 'pendiente' THEN 1 ELSE 0 END) as pendientes,
                SUM(CASE WHEN estado = 'confirmada' THEN 1 ELSE 0 END) as confirmadas,
                SUM(CASE WHEN estado = 'completada' THEN 1 ELSE 0 END) as completadas,
                SUM(CASE WHEN estado = 'cancelada' THEN 1 ELSE 0 END) as canceladas,
                SUM(CASE WHEN estado = 'no_asistio' THEN 1 ELSE 0 END) as no_asistio,
                SUM(CASE WHEN estado = 'completada' THEN precio ELSE 0 END) as ingresos
                FROM reservaciones r
                $whereClause";
        
        $stmt = $this->db->query($sql, $params);
        return $stmt->fetch();
    }
}
