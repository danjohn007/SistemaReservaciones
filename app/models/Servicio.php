<?php
/**
 * Modelo de Servicio
 */

class Servicio {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    /**
     * Crear servicio
     */
    public function create($data) {
        $sql = "INSERT INTO servicios (categoria_id, sucursal_id, nombre, descripcion, duracion_minutos, precio, activo) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        
        $params = [
            $data['categoria_id'],
            $data['sucursal_id'],
            $data['nombre'],
            $data['descripcion'] ?? null,
            $data['duracion_minutos'],
            $data['precio'],
            $data['activo'] ?? true
        ];
        
        $this->db->query($sql, $params);
        return $this->db->lastInsertId();
    }
    
    /**
     * Obtener servicios por sucursal
     */
    public function getBySucursal($sucursalId, $activeOnly = true) {
        $sql = "SELECT s.*, c.nombre as categoria_nombre 
                FROM servicios s 
                INNER JOIN categorias_servicios c ON s.categoria_id = c.id 
                WHERE s.sucursal_id = ?";
        
        if ($activeOnly) {
            $sql .= " AND s.activo = 1 AND c.activo = 1";
        }
        
        $sql .= " ORDER BY c.nombre, s.nombre";
        
        $stmt = $this->db->query($sql, [$sucursalId]);
        return $stmt->fetchAll();
    }
    
    /**
     * Obtener servicio por ID
     */
    public function findById($id) {
        $sql = "SELECT s.*, c.nombre as categoria_nombre 
                FROM servicios s 
                INNER JOIN categorias_servicios c ON s.categoria_id = c.id 
                WHERE s.id = ? LIMIT 1";
        
        $stmt = $this->db->query($sql, [$id]);
        return $stmt->fetch();
    }
    
    /**
     * Obtener todas las categorías
     */
    public function getCategorias($activeOnly = true) {
        $sql = "SELECT * FROM categorias_servicios";
        if ($activeOnly) {
            $sql .= " WHERE activo = 1";
        }
        $sql .= " ORDER BY nombre";
        
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }
    
    /**
     * Actualizar servicio
     */
    public function update($id, $data) {
        $fields = [];
        $params = [];
        
        $allowedFields = ['categoria_id', 'nombre', 'descripcion', 'duracion_minutos', 'precio', 'activo'];
        
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
        $sql = "UPDATE servicios SET " . implode(', ', $fields) . " WHERE id = ?";
        
        $this->db->query($sql, $params);
        return true;
    }
}
