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

// Verificar si se ha enviado el ID del refrigerio a eliminar
if (isset($_GET['idRefrigerio'])) {
    $idRefrigerio = $_GET['idRefrigerio'];

    // Preparar la consulta para eliminar
    $stmt = $conn->prepare("DELETE FROM refrigerio WHERE idRefrigerio = ?");
    
    // Intentar ejecutar la consulta de eliminación
    try {
        $stmt->execute([$idRefrigerio]);

        // Comprobar si se eliminó alguna fila
        if ($stmt->rowCount() > 0) {
            header("Location: refrigerio.php"); // Redirigir a la página de refrigerios
            exit();
        } else {
            echo "No se puede eliminar esta casilla.";
        }
    } catch (PDOException $e) {
        // Solo muestra el mensaje sin especificar el error
        echo "No se puede eliminar esta casilla.";
    }
} else {
    echo "ID de refrigerio no especificado.";
}
?>
