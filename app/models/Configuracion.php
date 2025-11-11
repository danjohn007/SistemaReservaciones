<?php
/**
 * Modelo de Configuración
 * Gestiona las configuraciones del sistema
 */

class Configuracion {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    /**
     * Obtener todas las configuraciones
     */
    public function getAll() {
        $sql = "SELECT * FROM configuraciones ORDER BY clave";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }
    
    /**
     * Obtener todas las configuraciones como array asociativo clave => valor
     */
    public function getAllAsArray() {
        $configs = $this->getAll();
        $result = [];
        foreach ($configs as $config) {
            $result[$config['clave']] = $config['valor'];
        }
        return $result;
    }
    
    /**
     * Obtener configuración por clave
     */
    public function getByClave($clave, $default = null) {
        $sql = "SELECT * FROM configuraciones WHERE clave = ? LIMIT 1";
        $stmt = $this->db->query($sql, [$clave]);
        $config = $stmt->fetch();
        
        if (!$config) {
            return $default;
        }
        
        return $config['valor'];
    }
    
    /**
     * Obtener múltiples configuraciones por prefijo
     */
    public function getByPrefix($prefix) {
        $sql = "SELECT * FROM configuraciones WHERE clave LIKE ? ORDER BY clave";
        $stmt = $this->db->query($sql, [$prefix . '%']);
        return $stmt->fetchAll();
    }
    
    /**
     * Establecer o actualizar una configuración
     */
    public function set($clave, $valor, $descripcion = null) {
        // Verificar si existe
        $exists = $this->getByClave($clave);
        
        if ($exists !== null) {
            // Actualizar
            $sql = "UPDATE configuraciones SET valor = ?, updated_at = CURRENT_TIMESTAMP";
            $params = [$valor];
            
            if ($descripcion !== null) {
                $sql .= ", descripcion = ?";
                $params[] = $descripcion;
            }
            
            $sql .= " WHERE clave = ?";
            $params[] = $clave;
            
            $this->db->query($sql, $params);
        } else {
            // Insertar
            $sql = "INSERT INTO configuraciones (clave, valor, descripcion) VALUES (?, ?, ?)";
            $this->db->query($sql, [$clave, $valor, $descripcion]);
        }
        
        return true;
    }
    
    /**
     * Actualizar múltiples configuraciones
     */
    public function setMultiple($configuraciones) {
        foreach ($configuraciones as $clave => $valor) {
            $this->set($clave, $valor);
        }
        return true;
    }
    
    /**
     * Eliminar una configuración
     */
    public function delete($clave) {
        $sql = "DELETE FROM configuraciones WHERE clave = ?";
        $this->db->query($sql, [$clave]);
        return true;
    }
    
    /**
     * Verificar si una configuración existe
     */
    public function exists($clave) {
        $sql = "SELECT COUNT(*) as total FROM configuraciones WHERE clave = ?";
        $stmt = $this->db->query($sql, [$clave]);
        $result = $stmt->fetch();
        return $result['total'] > 0;
    }
    
    /**
     * Obtener configuraciones agrupadas por categoría
     */
    public function getGrouped() {
        $allConfigs = $this->getAll();
        $grouped = [
            'general' => [],
            'email' => [],
            'whatsapp' => [],
            'contacto' => [],
            'colores' => [],
            'paypal' => [],
            'qr' => [],
            'shelly' => [],
            'hikvision' => [],
            'sistema' => [],
            'notificaciones' => []
        ];
        
        foreach ($allConfigs as $config) {
            $clave = $config['clave'];
            
            if (strpos($clave, 'sitio_') === 0) {
                $grouped['general'][] = $config;
            } elseif (strpos($clave, 'email_') === 0) {
                $grouped['email'][] = $config;
            } elseif (strpos($clave, 'whatsapp_') === 0) {
                $grouped['whatsapp'][] = $config;
            } elseif (strpos($clave, 'telefono_') === 0 || strpos($clave, 'horario_') === 0) {
                $grouped['contacto'][] = $config;
            } elseif (strpos($clave, 'color_') === 0) {
                $grouped['colores'][] = $config;
            } elseif (strpos($clave, 'paypal_') === 0) {
                $grouped['paypal'][] = $config;
            } elseif (strpos($clave, 'api_qr_') === 0) {
                $grouped['qr'][] = $config;
            } elseif (strpos($clave, 'api_shelly_') === 0) {
                $grouped['shelly'][] = $config;
            } elseif (strpos($clave, 'api_hikvision_') === 0) {
                $grouped['hikvision'][] = $config;
            } elseif (strpos($clave, 'sistema_') === 0) {
                $grouped['sistema'][] = $config;
            } elseif (strpos($clave, 'notificaciones_') === 0 || strpos($clave, 'tiempo_') === 0 || strpos($clave, 'permitir_') === 0) {
                $grouped['notificaciones'][] = $config;
            }
        }
        
        return $grouped;
    }
}
