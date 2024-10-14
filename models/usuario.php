<?php
class Usuario {
    private $conn;
    private $table_name = "Usuario";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function crear($nombre, $apellido, $correo, $telefono, $direccion, $password, $rol, $estado) {
        $query = "INSERT INTO " . $this->table_name . " (nombreUsuario, apellidoUsuario, correoUsuario, telefonoUsuario, direccionUsuario, passwordUsuario, rolUsuario, estadoUsuario) 
                  VALUES (:nombre, :apellido, :correo, :telefono, :direccion, :password, :rol, :estado)";

        $stmt = $this->conn->prepare($query);

        // Bind
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':apellido', $apellido);
        $stmt->bindParam(':correo', $correo);
        $stmt->bindParam(':telefono', $telefono);
        $stmt->bindParam(':direccion', $direccion);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':rol', $rol);
        $stmt->bindParam(':estado', $estado);

        return $stmt->execute();
    }
}
?>
