<?php
/**
 * Modelo de Sucursal
 */

class Sucursal {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    /**
     * Crear sucursal
     */
    public function create($data) {
        $sql = "INSERT INTO sucursales (nombre, direccion, ciudad, estado, codigo_postal, telefono, email, 
                hora_apertura, hora_cierre, zona_horaria, activo) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $params = [
            $data['nombre'],
            $data['direccion'],
            $data['ciudad'],
            $data['estado'],
            $data['codigo_postal'] ?? null,
            $data['telefono'] ?? null,
            $data['email'] ?? null,
            $data['hora_apertura'],
            $data['hora_cierre'],
            $data['zona_horaria'] ?? 'America/Mexico_City',
            $data['activo'] ?? true
        ];
        
        $this->db->query($sql, $params);
        return $this->db->lastInsertId();
    }
    
    /**
     * Obtener todas las sucursales activas
     */
    public function getAll($activeOnly = true) {
        $sql = "SELECT * FROM sucursales";
        if ($activeOnly) {
            $sql .= " WHERE activo = 1";
        }
        $sql .= " ORDER BY nombre";
        
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }
    
    /**
     * Obtener sucursal por ID
     */
    public function findById($id) {
        $sql = "SELECT * FROM sucursales WHERE id = ? LIMIT 1";
        $stmt = $this->db->query($sql, [$id]);
        return $stmt->fetch();
    }
    
    /**
     * Actualizar sucursal
     */
    public function update($id, $data) {
        $fields = [];
        $params = [];
        
        $allowedFields = ['nombre', 'direccion', 'ciudad', 'estado', 'codigo_postal', 
                          'telefono', 'email', 'hora_apertura', 'hora_cierre', 'zona_horaria', 'activo'];
        
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
        $sql = "UPDATE sucursales SET " . implode(', ', $fields) . " WHERE id = ?";
        
        $this->db->query($sql, $params);
        return true;
    }
    
    /**
     * Eliminar sucursal
     */
    public function delete($id) {
        $sql = "DELETE FROM sucursales WHERE id = ?";
        $this->db->query($sql, [$id]);
        return true;
    }
    
    /**
     * Obtener días no laborables de una sucursal
     */
    public function getDiasNoLaborables($sucursalId, $fechaInicio = null, $fechaFin = null) {
        $sql = "SELECT * FROM dias_no_laborables WHERE sucursal_id = ?";
        $params = [$sucursalId];
        
        if ($fechaInicio && $fechaFin) {
            $sql .= " AND fecha BETWEEN ? AND ?";
            $params[] = $fechaInicio;
            $params[] = $fechaFin;
        }
        
        $sql .= " ORDER BY fecha";
        
        $stmt = $this->db->query($sql, $params);
        return $stmt->fetchAll();
    }
    
    /**
     * Agregar día no laborable
     */
    public function addDiaNoLaborable($sucursalId, $fecha, $descripcion) {
        $sql = "INSERT INTO dias_no_laborables (sucursal_id, fecha, descripcion) VALUES (?, ?, ?)";
        $this->db->query($sql, [$sucursalId, $fecha, $descripcion]);
        return $this->db->lastInsertId();
    }
}
