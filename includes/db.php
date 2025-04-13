<?php
require_once 'config.php';

class Database {
    private static $instance = null;
    private $connection;
    
    public function __construct() {
        $this->connect();
    }
    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }
    private function connect() {
        $this->connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

        if ($this->connection->connect_error) {
            die("Ошибка подключения: " . $this->connection->connect_error);
        }

        $this->connection->set_charset("utf8");
    }

    public function getConnection() {
        return $this->connection;
    }

    public function query($sql) {
        return $this->connection->query($sql);
    }

    public function escape($value) {
        return $this->connection->real_escape_string($value);
    }

    public function getLastId() {
        return $this->connection->insert_id;
    }
}

$db = new Database();
?>