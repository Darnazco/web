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

// Verifica si se ha enviado un ID de asignación válido
if (isset($_GET['idAsigRefCur'])) {
    $idAsigRefCur = $_GET['idAsigRefCur'];

    try {
        // Preparar la consulta para eliminar
        $stmt = $conn->prepare("DELETE FROM asigrefrigeriocurso WHERE idAsigRefCur = ?");
        $stmt->execute([$idAsigRefCur]);

        // Verificar si se ha eliminado alguna fila
        if ($stmt->rowCount() > 0) {
            // Redirigir a la página de asignación de refrigerios después de eliminar
            header("Location: asignacion_refrigerios.php?mensaje=eliminado");
            exit();
        } else {
            // Si no se ha podido eliminar
            header("Location: asignacion_refrigerios.php?mensaje=no_se_puede_eliminar");
            exit();
        }
    } catch (PDOException $e) {
        // Si ocurre algún error, muestra el mensaje
        header("Location: asignacion_refrigerios.php?mensaje=no_se_puede_eliminar");
        exit();
    }
} else {
    // Si no se envió un ID válido, redirigir a la página principal de asignaciones
    header("Location: asignacion_refrigerios.php");
    exit();
}
?>
