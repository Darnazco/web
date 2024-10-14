<?php
session_start();
if (!isset($_SESSION['rolUsuario']) || $_SESSION['rolUsuario'] !== 'coordinador') {
    header("Location: login.php");
    exit();
}

require_once '../models/database.php'; // Asegúrate de que la ruta sea correcta

// Conexión a la base de datos
$db = new Database();
$conn = $db->getConnection();

// Verificar si se ha enviado el ID del coordinador a eliminar
if (isset($_GET['idCoordinador'])) {
    $idCoordinador = $_GET['idCoordinador'];

    // Preparar la consulta para eliminar
    $stmt = $conn->prepare("DELETE FROM coordinador WHERE idCoordinador = ?");
    
    // Intentar ejecutar la consulta de eliminación
    try {
        $stmt->execute([$idCoordinador]);

        // Comprobar si se eliminó alguna fila
        if ($stmt->rowCount() > 0) {
            header("Location: coordinador.php"); // Redirigir a la página de coordinadores
            exit();
        } else {
            echo "No se puede eliminar esta casilla.";
        }
    } catch (PDOException $e) {
        echo "No se puede eliminar esta casilla. ";
    }
} else {
    echo "ID de coordinador no especificado.";
}
?>
