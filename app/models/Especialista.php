<?php
/**
 * Modelo de Especialista
 */

class Especialista {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    /**
     * Crear especialista
     */
    public function create($data) {
        $sql = "INSERT INTO especialistas (usuario_id, sucursal_id, profesion, descripcion, experiencia_anos, activo) 
                VALUES (?, ?, ?, ?, ?, ?)";
        
        $params = [
            $data['usuario_id'],
            $data['sucursal_id'],
            $data['profesion'] ?? null,
            $data['descripcion'] ?? null,
            $data['experiencia_anos'] ?? 0,
            $data['activo'] ?? true
        ];
        
        $this->db->query($sql, $params);
        return $this->db->lastInsertId();
    }
    
    /**
     * Obtener especialistas por sucursal
     */
    public function getBySucursal($sucursalId, $activeOnly = true) {
        $sql = "SELECT e.*, u.nombre, u.apellido, u.email, u.telefono 
                FROM especialistas e 
                INNER JOIN usuarios u ON e.usuario_id = u.id 
                WHERE e.sucursal_id = ?";
        
        if ($activeOnly) {
            $sql .= " AND e.activo = 1 AND u.activo = 1";
        }
        
        $sql .= " ORDER BY u.nombre, u.apellido";
        
        $stmt = $this->db->query($sql, [$sucursalId]);
        return $stmt->fetchAll();
    }
    
    /**
     * Obtener especialista por ID
     */
    public function findById($id) {
        $sql = "SELECT e.*, u.nombre, u.apellido, u.email, u.telefono, s.nombre as sucursal_nombre 
                FROM especialistas e 
                INNER JOIN usuarios u ON e.usuario_id = u.id 
                INNER JOIN sucursales s ON e.sucursal_id = s.id 
                WHERE e.id = ? LIMIT 1";
        
        $stmt = $this->db->query($sql, [$id]);
        return $stmt->fetch();
    }
    
    /**
     * Obtener especialista por usuario ID
     */
    public function findByUserId($userId) {
        $sql = "SELECT e.*, s.nombre as sucursal_nombre 
                FROM especialistas e 
                INNER JOIN sucursales s ON e.sucursal_id = s.id 
                WHERE e.usuario_id = ? LIMIT 1";
        
        $stmt = $this->db->query($sql, [$userId]);
        return $stmt->fetch();
    }
    
    /**
     * Obtener servicios de un especialista
     */
    public function getServicios($especialistaId) {
        $sql = "SELECT s.*, es.id as especialista_servicio_id 
                FROM servicios s 
                INNER JOIN especialista_servicios es ON s.id = es.servicio_id 
                WHERE es.especialista_id = ? AND s.activo = 1 
                ORDER BY s.nombre";
        
        $stmt = $this->db->query($sql, [$especialistaId]);
        return $stmt->fetchAll();
    }
    
    /**
     * Asignar servicio a especialista
     */
    public function assignServicio($especialistaId, $servicioId) {
        $sql = "INSERT IGNORE INTO especialista_servicios (especialista_id, servicio_id) VALUES (?, ?)";
        $this->db->query($sql, [$especialistaId, $servicioId]);
        return true;
    }
    
    /**
     * Remover servicio de especialista
     */
    public function removeServicio($especialistaId, $servicioId) {
        $sql = "DELETE FROM especialista_servicios WHERE especialista_id = ? AND servicio_id = ?";
        $this->db->query($sql, [$especialistaId, $servicioId]);
        return true;
    }
    
    /**
     * Obtener horarios de un especialista
     */
    public function getHorarios($especialistaId) {
        $sql = "SELECT * FROM horarios_especialistas WHERE especialista_id = ? AND activo = 1 ORDER BY 
                FIELD(dia_semana, 'lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado', 'domingo'), 
                hora_inicio";
        
        $stmt = $this->db->query($sql, [$especialistaId]);
        return $stmt->fetchAll();
    }
    
    /**
     * Agregar horario
     */
    public function addHorario($especialistaId, $diaSemana, $horaInicio, $horaFin) {
        $sql = "INSERT INTO horarios_especialistas (especialista_id, dia_semana, hora_inicio, hora_fin) 
                VALUES (?, ?, ?, ?)";
        $this->db->query($sql, [$especialistaId, $diaSemana, $horaInicio, $horaFin]);
        return $this->db->lastInsertId();
    }
    
    /**
     * Eliminar horario
     */
    public function deleteHorario($horarioId) {
        $sql = "DELETE FROM horarios_especialistas WHERE id = ?";
        $this->db->query($sql, [$horarioId]);
        return true;
    }
    
    /**
     * Obtener bloqueos de horarios
     */
    public function getBloqueos($especialistaId, $fechaInicio = null, $fechaFin = null) {
        $sql = "SELECT * FROM bloqueos_horarios WHERE especialista_id = ?";
        $params = [$especialistaId];
        
        if ($fechaInicio && $fechaFin) {
            $sql .= " AND ((fecha_inicio BETWEEN ? AND ?) OR (fecha_fin BETWEEN ? AND ?) 
                      OR (fecha_inicio <= ? AND fecha_fin >= ?))";
            $params[] = $fechaInicio;
            $params[] = $fechaFin;
            $params[] = $fechaInicio;
            $params[] = $fechaFin;
            $params[] = $fechaInicio;
            $params[] = $fechaFin;
        }
        
        $sql .= " ORDER BY fecha_inicio";
        
        $stmt = $this->db->query($sql, $params);
        return $stmt->fetchAll();
    }
    
    /**
     * Agregar bloqueo de horario
     */
    public function addBloqueo($especialistaId, $fechaInicio, $fechaFin, $motivo) {
        $sql = "INSERT INTO bloqueos_horarios (especialista_id, fecha_inicio, fecha_fin, motivo) 
                VALUES (?, ?, ?, ?)";
        $this->db->query($sql, [$especialistaId, $fechaInicio, $fechaFin, $motivo]);
        return $this->db->lastInsertId();
    }
    
    /**
     * Actualizar calificación promedio
     */
    public function updateCalificacion($especialistaId) {
        $sql = "UPDATE especialistas e 
                SET e.calificacion_promedio = (
                    SELECT AVG(c.calificacion) 
                    FROM calificaciones c 
                    WHERE c.especialista_id = e.id
                ),
                e.total_calificaciones = (
                    SELECT COUNT(*) 
                    FROM calificaciones c 
                    WHERE c.especialista_id = e.id
                )
                WHERE e.id = ?";
        
        $this->db->query($sql, [$especialistaId]);
        return true;
    }
    
    /**
     * Buscar especialistas disponibles por servicio
     */
    public function findByServicio($servicioId, $sucursalId = null) {
        $sql = "SELECT DISTINCT e.*, u.nombre, u.apellido, u.email 
                FROM especialistas e 
                INNER JOIN usuarios u ON e.usuario_id = u.id 
                INNER JOIN especialista_servicios es ON e.id = es.especialista_id 
                WHERE es.servicio_id = ? AND e.activo = 1 AND u.activo = 1";
        
        $params = [$servicioId];
        
        if ($sucursalId) {
            $sql .= " AND e.sucursal_id = ?";
            $params[] = $sucursalId;
        }
        
        $sql .= " ORDER BY e.calificacion_promedio DESC, u.nombre";
        
        $stmt = $this->db->query($sql, $params);
        return $stmt->fetchAll();
    }
}
