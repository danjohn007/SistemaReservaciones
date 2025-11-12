<?php
/**
 * Modelo de Configuración
 * Gestiona las configuraciones del sistema
 */

class Configuracion {
    private $db;
    private static $cache = [];
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    /**
     * Obtener una configuración por clave
     */
    public function get($clave, $default = null) {
        // Verificar si está en caché
        if (isset(self::$cache[$clave])) {
            return self::$cache[$clave];
        }
        
        $sql = "SELECT valor FROM configuraciones WHERE clave = ? LIMIT 1";
        $stmt = $this->db->query($sql, [$clave]);
        $result = $stmt->fetch();
        
        if ($result) {
            self::$cache[$clave] = $result['valor'];
            return $result['valor'];
        }
        
        return $default;
    }
    
    /**
     * Establecer o actualizar una configuración
     */
    public function set($clave, $valor, $descripcion = null) {
        // Verificar si existe
        $existing = $this->get($clave);
        
        if ($existing !== null) {
            // Actualizar
            $sql = "UPDATE configuraciones SET valor = ?, updated_at = NOW()";
            $params = [$valor];
            
            if ($descripcion !== null) {
                $sql .= ", descripcion = ?";
                $params[] = $descripcion;
            }
            
            $sql .= " WHERE clave = ?";
            $params[] = $clave;
            
            $this->db->query($sql, $params);
        } else {
            // Insertar nueva
            $sql = "INSERT INTO configuraciones (clave, valor, descripcion) VALUES (?, ?, ?)";
            $this->db->query($sql, [$clave, $valor, $descripcion]);
        }
        
        // Actualizar caché
        self::$cache[$clave] = $valor;
        
        return true;
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
     * Obtener configuraciones por prefijo de clave
     */
    public function getByPrefix($prefix) {
        $sql = "SELECT * FROM configuraciones WHERE clave LIKE ? ORDER BY clave";
        $stmt = $this->db->query($sql, [$prefix . '%']);
        $results = $stmt->fetchAll();
        
        $configs = [];
        foreach ($results as $config) {
            $configs[$config['clave']] = $config['valor'];
            self::$cache[$config['clave']] = $config['valor'];
        }
        
        return $configs;
    }
    
    /**
     * Actualizar múltiples configuraciones a la vez
     */
    public function setMultiple($configuraciones) {
        $updated = 0;
        
        foreach ($configuraciones as $clave => $valor) {
            if ($this->set($clave, $valor)) {
                $updated++;
            }
        }
        
        return $updated;
    }
    
    /**
     * Eliminar una configuración
     */
    public function delete($clave) {
        $sql = "DELETE FROM configuraciones WHERE clave = ?";
        $this->db->query($sql, [$clave]);
        
        // Limpiar caché
        unset(self::$cache[$clave]);
        
        return true;
    }
    
    /**
     * Limpiar caché de configuraciones
     */
    public static function clearCache() {
        self::$cache = [];
    }
    
    /**
     * Obtener configuraciones agrupadas por categoría
     */
    public function getAllGrouped() {
        $all = $this->getAll();
        $grouped = [
            'sitio' => [],
            'email' => [],
            'whatsapp' => [],
            'contacto' => [],
            'colores' => [],
            'paypal' => [],
            'api_qr' => [],
            'api_shelly' => [],
            'api_hikvision' => [],
            'sistema' => [],
            'otros' => []
        ];
        
        foreach ($all as $config) {
            $clave = $config['clave'];
            
            if (strpos($clave, 'sitio_') === 0) {
                $grouped['sitio'][$clave] = $config;
            } elseif (strpos($clave, 'email_') === 0) {
                $grouped['email'][$clave] = $config;
            } elseif (strpos($clave, 'whatsapp_') === 0) {
                $grouped['whatsapp'][$clave] = $config;
            } elseif (strpos($clave, 'telefono_') === 0 || strpos($clave, 'horario_') === 0) {
                $grouped['contacto'][$clave] = $config;
            } elseif (strpos($clave, 'color_') === 0) {
                $grouped['colores'][$clave] = $config;
            } elseif (strpos($clave, 'paypal_') === 0) {
                $grouped['paypal'][$clave] = $config;
            } elseif (strpos($clave, 'api_qr_') === 0) {
                $grouped['api_qr'][$clave] = $config;
            } elseif (strpos($clave, 'api_shelly_') === 0) {
                $grouped['api_shelly'][$clave] = $config;
            } elseif (strpos($clave, 'api_hikvision_') === 0) {
                $grouped['api_hikvision'][$clave] = $config;
            } elseif (strpos($clave, 'sistema_') === 0) {
                $grouped['sistema'][$clave] = $config;
            } else {
                $grouped['otros'][$clave] = $config;
            }
        }
        
        // Eliminar categorías vacías
        foreach ($grouped as $key => $value) {
            if (empty($value)) {
                unset($grouped[$key]);
            }
        }
        
        return $grouped;
    }
}
