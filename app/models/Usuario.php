<?php
/**
 * Modelo de Usuario
 */

class Usuario {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    /**
     * Crear un nuevo usuario
     */
    public function create($data) {
        $sql = "INSERT INTO usuarios (nombre, apellido, email, telefono, password_hash, rol_id, email_verificado) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        
        $password_hash = password_hash($data['password'], PASSWORD_DEFAULT);
        
        $params = [
            $data['nombre'],
            $data['apellido'],
            $data['email'],
            $data['telefono'] ?? null,
            $password_hash,
            $data['rol_id'],
            isset($data['email_verificado']) ? (int)$data['email_verificado'] : 0
        ];
        
        $this->db->query($sql, $params);
        return $this->db->lastInsertId();
    }
    
    /**
     * Buscar usuario por email
     */
    public function findByEmail($email) {
        $sql = "SELECT u.*, r.nombre as rol_nombre 
                FROM usuarios u 
                INNER JOIN roles r ON u.rol_id = r.id 
                WHERE u.email = ? LIMIT 1";
        
        $stmt = $this->db->query($sql, [$email]);
        return $stmt->fetch();
    }
    
    /**
     * Buscar usuario por ID
     */
    public function findById($id) {
        $sql = "SELECT u.*, r.nombre as rol_nombre 
                FROM usuarios u 
                INNER JOIN roles r ON u.rol_id = r.id 
                WHERE u.id = ? LIMIT 1";
        
        $stmt = $this->db->query($sql, [$id]);
        return $stmt->fetch();
    }
    
    /**
     * Verificar credenciales
     */
    public function verifyCredentials($email, $password) {
        $user = $this->findByEmail($email);
        
        if (!$user) {
            return false;
        }
        
        if (!$user['activo']) {
            return false;
        }
        
        // Verificar si el usuario está bloqueado
        if ($user['bloqueado_hasta'] && strtotime($user['bloqueado_hasta']) > time()) {
            return false;
        }
        
        if (password_verify($password, $user['password_hash'])) {
            // Resetear intentos fallidos
            $this->resetFailedAttempts($user['id']);
            return $user;
        }
        
        // Incrementar intentos fallidos
        $this->incrementFailedAttempts($user['id']);
        return false;
    }
    
    /**
     * Incrementar intentos fallidos
     */
    private function incrementFailedAttempts($userId) {
        $sql = "UPDATE usuarios SET intentos_fallidos = intentos_fallidos + 1 WHERE id = ?";
        $this->db->query($sql, [$userId]);
        
        // Verificar si debe bloquearse
        $user = $this->findById($userId);
        if ($user['intentos_fallidos'] >= MAX_LOGIN_ATTEMPTS) {
            $this->blockUser($userId);
        }
    }
    
    /**
     * Resetear intentos fallidos
     */
    private function resetFailedAttempts($userId) {
        $sql = "UPDATE usuarios SET intentos_fallidos = 0, bloqueado_hasta = NULL WHERE id = ?";
        $this->db->query($sql, [$userId]);
    }
    
    /**
     * Bloquear usuario
     */
    private function blockUser($userId) {
        $bloqueado_hasta = date('Y-m-d H:i:s', time() + LOCKOUT_TIME);
        $sql = "UPDATE usuarios SET bloqueado_hasta = ? WHERE id = ?";
        $this->db->query($sql, [$bloqueado_hasta, $userId]);
    }
    
    /**
     * Actualizar último acceso
     */
    public function updateLastAccess($userId) {
        $sql = "UPDATE usuarios SET ultimo_acceso = NOW() WHERE id = ?";
        $this->db->query($sql, [$userId]);
    }
    
    /**
     * Actualizar usuario
     */
    public function update($id, $data) {
        $fields = [];
        $params = [];
        
        $allowedFields = ['nombre', 'apellido', 'email', 'telefono', 'activo'];
        
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
        $sql = "UPDATE usuarios SET " . implode(', ', $fields) . " WHERE id = ?";
        
        $this->db->query($sql, $params);
        return true;
    }
    
    /**
     * Cambiar contraseña
     */
    public function changePassword($userId, $newPassword) {
        $password_hash = password_hash($newPassword, PASSWORD_DEFAULT);
        $sql = "UPDATE usuarios SET password_hash = ? WHERE id = ?";
        $this->db->query($sql, [$password_hash, $userId]);
        return true;
    }
    
    /**
     * Listar usuarios por rol
     */
    public function listByRole($roleName, $limit = null, $offset = 0) {
        $sql = "SELECT u.*, r.nombre as rol_nombre 
                FROM usuarios u 
                INNER JOIN roles r ON u.rol_id = r.id 
                WHERE r.nombre = ? 
                ORDER BY u.created_at DESC";
        
        if ($limit) {
            $sql .= " LIMIT ? OFFSET ?";
            $stmt = $this->db->query($sql, [$roleName, $limit, $offset]);
        } else {
            $stmt = $this->db->query($sql, [$roleName]);
        }
        
        return $stmt->fetchAll();
    }
    
    /**
     * Verificar si existe email
     */
    public function emailExists($email, $excludeId = null) {
        $sql = "SELECT COUNT(*) as total FROM usuarios WHERE email = ?";
        $params = [$email];
        
        if ($excludeId) {
            $sql .= " AND id != ?";
            $params[] = $excludeId;
        }
        
        $stmt = $this->db->query($sql, $params);
        $result = $stmt->fetch();
        return $result['total'] > 0;
    }
}
