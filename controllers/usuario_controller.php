<?php
require_once '../models/database.php';
require_once '../models/usuario.php';



    

 

    class UsuarioController {
        private $db;
        private $usuario;
    
        public function __construct() {
            $this->db = new Database();
            $this->usuario = new Usuario($this->db->getConnection());
        }
    
        public function crearUsuario($nombre, $apellido, $correo, $telefono, $direccion, $password, $rol, $estado) {
            return $this->usuario->crear($nombre, $apellido, $correo, $telefono, $direccion, $password, $rol, $estado);
        }
    
    // Función para validar usuario
    public function validarUsuario($correo, $password) {
        $database = new Database();
        $db = $database->getConnection();
    
        $query = "SELECT idUsuario, rolUsuario FROM Usuario WHERE correoUsuario = :correo AND passwordUsuario = :password";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':correo', $correo);
        $stmt->bindParam(':password', $password);
        
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
            return $this->determinarRol($usuario['idUsuario'], $usuario['rolUsuario']);
        }
    
        return false; // Credenciales incorrectas
    }
    
    private function determinarRol($idUsuario, $rolUsuario) {
        $database = new Database();
        $db = $database->getConnection();
    
        // Verificar si es coordinador
        $queryCoordinador = "SELECT idCoordinador FROM Coordinador WHERE idUsuariofk = :idUsuario";
        $stmtCoordinador = $db->prepare($queryCoordinador);
        $stmtCoordinador->bindParam(':idUsuario', $idUsuario);
        $stmtCoordinador->execute();
        
        if ($stmtCoordinador->rowCount() > 0) {
            return ['idUsuario' => $idUsuario, 'rol' => 'coordinador'];
        }
    
        // Verificar si es auxiliar
        $queryAuxiliar = "SELECT idAuxiliar FROM Auxiliar WHERE idUsuariofk = :idUsuario";
        $stmtAuxiliar = $db->prepare($queryAuxiliar);
        $stmtAuxiliar->bindParam(':idUsuario', $idUsuario);
        $stmtAuxiliar->execute();
        
        if ($stmtAuxiliar->rowCount() > 0) {
            return ['idUsuario' => $idUsuario, 'rol' => 'auxiliar'];
        }
    
        // Si no es ni coordinador ni auxiliar, se devuelve el rol original
        return ['idUsuario' => $idUsuario, 'rol' => $rolUsuario];
    }

    // Método para obtener las asignaciones
    public function obtenerAsignaciones() {
        $database = new Database();
        $db = $database->getConnection();
        
        $sql = "SELECT a.*, r.descripcionRefrigerio, c.sedeCurso 
                FROM AsigRefrigerioCurso a
                JOIN Refrigerio r ON a.idRefrigeriofk = r.idRefrigerio
                JOIN Curso c ON a.idCursofk = c.idCurso";
        
        $stmt = $db->prepare($sql);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Método para obtener refrigerios
    public function obtenerRefrigerios() {
        $database = new Database();
        $db = $database->getConnection();
    
        $query = "SELECT idRefrigerio, descripcionRefrigerio FROM Refrigerio"; 
        $stmt = $db->prepare($query);
        $stmt->execute();
    
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function eliminarAsignacion($id) {
        $database = new Database();
        $db = $database->getConnection();

        $query = "DELETE FROM AsigRefrigerioCurso WHERE idAsigRefCur = :id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':id', $id);

        return $stmt->execute();
    }
    
    public function asignarRefrigerio($idAsignacion) {
        $db = new Database();
        $connection = $db->getConnection();
        
        $sql = "UPDATE AsigRefrigerioCurso SET estado = 1 WHERE idAsigRefCur = :idAsignacion";
        $stmt = $connection->prepare($sql);
        $stmt->bindParam(':idAsignacion', $idAsignacion);
        
        return $stmt->execute();
    }
}

// Manejo de las solicitudes
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    if (isset($_GET['action']) && $_GET['action'] === 'eliminar' && isset($_GET['id'])) {
        $controller = new UsuarioController();
        $id = $_GET['id'];
        $controller->eliminarAsignacion($id);
        exit(); // Salir después de procesar la solicitud
    }
}
?>
