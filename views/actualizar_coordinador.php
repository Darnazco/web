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

// Manejo de la actualización
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $idCoordinador = $_POST['idCoordinador'];
    $nombreCoordinador = $_POST['nombreCoordinador'];
    $apellidoCoordinador = $_POST['apellidoCoordinador'];
    $correoCoordinador = $_POST['correoCoordinador'];
    $telefonoCoordinador = $_POST['telefonoCoordinador'];
    $oficinaCoordinador = $_POST['oficinaCoordinador'];
    $estadoUsuario = $_POST['estadoUsuario'];

    // Preparar la consulta de actualización
    $stmt = $conn->prepare("UPDATE coordinador SET nombreCoordinador = ?, apellidoCoordinador = ?, correoCoordinador = ?, telefonoCoordinador = ?, oficinaCoordinador = ?, estadoUsuario = ? WHERE idCoordinador = ?");
    $stmt->execute([$nombreCoordinador, $apellidoCoordinador, $correoCoordinador, $telefonoCoordinador, $oficinaCoordinador, $estadoUsuario, $idCoordinador]);

    // Redirigir al dashboard después de actualizar
    header("Location: coordinador.php");
    exit();
}

// Obtener el ID del coordinador a actualizar
if (isset($_GET['idCoordinador'])) {
    $idCoordinador = $_GET['idCoordinador'];

    // Obtener datos del coordinador
    $stmt = $conn->prepare("SELECT * FROM coordinador WHERE idCoordinador = ?");
    $stmt->execute([$idCoordinador]);
    $coordinador = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$coordinador) {
        echo "Coordinador no encontrado.";
        exit();
    }
} else {
    echo "ID de coordinador no especificado.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualizar Coordinador</title>
    <link rel="stylesheet" href="../css/actualizar_coordinador.css">
</head>
<body>
    <div class="container">
        <h1>Actualizar Coordinador</h1>
        <form method="POST" action="">
            <input type="hidden" name="idCoordinador" value="<?= $coordinador['idCoordinador'] ?>">

            <label for="nombreCoordinador">Nombre:</label>
            <input type="text" name="nombreCoordinador" value="<?= $coordinador['nombreCoordinador'] ?>" required>

            <label for="apellidoCoordinador">Apellido:</label>
            <input type="text" name="apellidoCoordinador" value="<?= $coordinador['apellidoCoordinador'] ?>" required>

            <label for="correoCoordinador">Correo:</label>
            <input type="email" name="correoCoordinador" value="<?= $coordinador['correoCoordinador'] ?>" required>

            <label for="telefonoCoordinador">Teléfono:</label>
            <input type="text" name="telefonoCoordinador" value="<?= $coordinador['telefonoCoordinador'] ?>" required>

            <label for="oficinaCoordinador">Oficina:</label>
            <input type="text" name="oficinaCoordinador" value="<?= $coordinador['oficinaCoordinador'] ?>" required>

            <label for="estadoUsuario">Estado:</label>
            <select name="estadoUsuario" required>
                <option value="activo" <?= $coordinador['estadoUsuario'] === 'activo' ? 'selected' : '' ?>>Activo</option>
                <option value="inactivo" <?= $coordinador['estadoUsuario'] === 'inactivo' ? 'selected' : '' ?>>Inactivo</option>
            </select>

            <button type="submit" class="button">Actualizar Coordinador</button>
        </form>
        <a href="coordinador.php" class="button">Volver a Coordinadores</a>
    </div>
</body>
</html>
