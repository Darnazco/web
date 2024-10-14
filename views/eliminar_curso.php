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

// Verificar si se ha enviado el ID del curso a eliminar
if (isset($_GET['idCurso'])) {
    $idCurso = $_GET['idCurso'];

    // Preparar la consulta para eliminar
    $stmt = $conn->prepare("DELETE FROM curso WHERE idCurso = ?");

    // Intentar ejecutar la consulta de eliminación
    try {
        $stmt->execute([$idCurso]);

        // Comprobar si se eliminó alguna fila
        if ($stmt->rowCount() > 0) {
            header("Location: curso.php"); // Redirigir a la página de cursos
            exit();
        } else {
            echo "No se puede eliminar esta casilla.";
        }
    } catch (Exception $e) {
        // No mostramos errores, solo el mensaje
        echo "No se puede eliminar esta casilla.";
    }
} else {
    echo "ID de curso no especificado.";
}
?>
