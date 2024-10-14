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

// Verificar si se ha enviado el ID del usuario a eliminar
if (isset($_GET['idUsuario'])) {
    $idUsuario = $_GET['idUsuario'];

    // Preparar la consulta para eliminar
    $stmt = $conn->prepare("DELETE FROM usuario WHERE idUsuario = ?");

    // Intentar ejecutar la consulta de eliminación
    try {
        $stmt->execute([$idUsuario]);

        // Comprobar si se eliminó alguna fila
        if ($stmt->rowCount() > 0) {
            header("Location: usuario.php"); // Redirigir a la página de usuarios
            exit();
        } else {
            // Mostrar mensaje sin errores
            echo "<script>alert('No se puede eliminar esta casilla.'); window.location.href='usuario.php';</script>";
        }
    } catch (PDOException $e) {
        // Mostrar mensaje sin errores
        echo "<script>alert('No se puede eliminar esta casilla.'); window.location.href='usuario.php';</script>";
    }
} else {
    // Mostrar mensaje sin errores si no se especifica el ID
    echo "<script>alert('ID de usuario no especificado.'); window.location.href='usuario.php';</script>";
}
?>
