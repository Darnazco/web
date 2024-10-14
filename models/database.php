<?php
class Database {
    private $host = 'localhost';
    private $db_name = 'proyecto_brillit1';
    private $username = 'root'; // Cambia si es necesario
    private $password = ''; // Cambia si es necesario
    public $conn;

    public function getConnection() {
        $this->conn = null;

        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }

        return $this->conn;
    }
}
?>
