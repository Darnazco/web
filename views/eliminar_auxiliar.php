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

// Manejo de la eliminación
if (isset($_GET['idAuxiliar'])) {
    $idAuxiliar = $_GET['idAuxiliar'];

    // Preparar la consulta de eliminación
    $stmt = $conn->prepare("DELETE FROM auxiliar WHERE idAuxiliar = ?");
    $stmt->execute([$idAuxiliar]);

    // Comprobar si se ha eliminado
    if ($stmt->rowCount() > 0) {
        header("Location: auxiliar.php"); // Redirigir a la lista de auxiliares
        exit();
    } else {
        $mensaje = "No se puede eliminar esta casilla"; // Mensaje si no se elimina
    }
} else {
    $mensaje = "ID de auxiliar no especificado."; // Mensaje si no se pasa el ID
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliminar Auxiliar</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Eliminar Auxiliar</h1>
        <?php if (isset($mensaje)): ?>
            <p><?= $mensaje ?></p>
        <?php endif; ?>
        <a href="auxiliar.php" class="button">Volver a Auxiliares</a>
    </div>
</body>
</html>
