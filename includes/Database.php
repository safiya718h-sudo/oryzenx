<?php
/**
 * Database Connection Class
 * PDO-based database wrapper with prepared statements
 */

class Database {
    private static $instance = null;
    private $pdo;
    private $host = DB_HOST;
    private $dbname = DB_NAME;
    private $user = DB_USER;
    private $pass = DB_PASS;

    private function __construct() {
        try {
            $this->pdo = new PDO(
                "mysql:host={$this->host};dbname={$this->dbname};charset=utf8mb4",
                $this->user,
                $this->pass,
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, PDO::ATTR_EMULATE_PREPARES => false]
            );
        } catch (PDOException $e) {
            die('Database Error: ' . $e->getMessage());
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getPDO() {
        return $this->pdo;
    }

    public function query($sql, $params = []) {
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            if (LOG_ERRORS) error_log($e->getMessage());
            return false;
        }
    }

    public function fetch($sql, $params = []) {
        $stmt = $this->query($sql, $params);
        return $stmt ? $stmt->fetch() : null;
    }

    public function fetchAll($sql, $params = []) {
        $stmt = $this->query($sql, $params);
        return $stmt ? $stmt->fetchAll() : [];
    }

    public function insert($table, $data) {
        $columns = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));
        $sql = "INSERT INTO $table ($columns) VALUES ($placeholders)";
        $stmt = $this->query($sql, array_values($data));
        return $stmt ? $this->pdo->lastInsertId() : false;
    }

    public function update($table, $data, $where) {
        $set = implode(', ', array_map(fn($k) => "$k = ?", array_keys($data)));
        $whereSql = implode(' AND ', array_map(fn($k) => "$k = ?", array_keys($where)));
        $sql = "UPDATE $table SET $set WHERE $whereSql";
        $params = array_merge(array_values($data), array_values($where));
        $stmt = $this->query($sql, $params);
        return $stmt ? $stmt->rowCount() : false;
    }

    public function delete($table, $where) {
        $whereSql = implode(' AND ', array_map(fn($k) => "$k = ?", array_keys($where)));
        $sql = "DELETE FROM $table WHERE $whereSql";
        $stmt = $this->query($sql, array_values($where));
        return $stmt ? $stmt->rowCount() : false;
    }

    public function count($table, $where = null) {
        $sql = "SELECT COUNT(*) as count FROM $table";
        if ($where) {
            $whereSql = implode(' AND ', array_map(fn($k) => "$k = ?", array_keys($where)));
            $sql .= " WHERE $whereSql";
            $result = $this->fetch($sql, array_values($where));
        } else {
            $result = $this->fetch($sql);
        }
        return $result ? $result['count'] : 0;
    }
}

$db = Database::getInstance();