<?php
/**
 * Mock Database class for testing views without DB
 */
class Database {
    private static $instance = null;
    private $connection;
    
    private function __construct() {
        // Mock - do nothing
        $this->connection = null;
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function getConnection() {
        return $this->connection;
    }
    
    public function query($sql, $params = []) {
        // Mock - return empty result
        return new class {
            public function execute($params) {}
            public function fetch() { return null; }
            public function fetchAll() { return []; }
        };
    }
    
    public function lastInsertId() {
        return 1;
    }
    
    private function __clone() {}
    public function __wakeup() {
        throw new Exception("Cannot unserialize singleton");
    }
}
