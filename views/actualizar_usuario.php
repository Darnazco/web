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
    $idUsuario = $_POST['idUsuario'];
    $nombreUsuario = $_POST['nombreUsuario'];
    $apellidoUsuario = $_POST['apellidoUsuario'];
    $correoUsuario = $_POST['correoUsuario'];
    $telefonoUsuario = $_POST['telefonoUsuario'];
    $direccionUsuario = $_POST['direccionUsuario'];
    $rolUsuario = $_POST['rolUsuario'];
    $estadoUsuario = $_POST['estadoUsuario'];

    // Preparar la consulta de actualización
    $stmt = $conn->prepare("UPDATE usuario SET nombreUsuario = ?, apellidoUsuario = ?, correoUsuario = ?, telefonoUsuario = ?, direccionUsuario = ?, rolUsuario = ?, estadoUsuario = ? WHERE idUsuario = ?");
    $stmt->execute([$nombreUsuario, $apellidoUsuario, $correoUsuario, $telefonoUsuario, $direccionUsuario, $rolUsuario, $estadoUsuario, $idUsuario]);

    // Redirigir al dashboard después de actualizar
    header("Location: usuario.php");
    exit();
}

// Obtener el ID del usuario a actualizar
$idUsuario = $_GET['idUsuario'];

// Obtener datos del usuario
$stmt = $conn->prepare("SELECT * FROM usuario WHERE idUsuario = ?");
$stmt->execute([$idUsuario]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$usuario) {
    echo "Usuario no encontrado.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualizar Usuario</title>
    <link rel="stylesheet" href="../css/actualizar_usuario.css">
</head>
<body>
    <div class="container">
        <h1>Actualizar Usuario</h1>
        <form method="POST" action="">
            <input type="hidden" name="idUsuario" value="<?= $usuario['idUsuario'] ?>">

            <label for="nombreUsuario">Nombre:</label>
            <input type="text" name="nombreUsuario" value="<?= $usuario['nombreUsuario'] ?>" required>

            <label for="apellidoUsuario">Apellido:</label>
            <input type="text" name="apellidoUsuario" value="<?= $usuario['apellidoUsuario'] ?>" required>

            <label for="correoUsuario">Correo:</label>
            <input type="email" name="correoUsuario" value="<?= $usuario['correoUsuario'] ?>" required>

            <label for="telefonoUsuario">Teléfono:</label>
            <input type="text" name="telefonoUsuario" value="<?= $usuario['telefonoUsuario'] ?>" required>

            <label for="direccionUsuario">Dirección:</label>
            <input type="text" name="direccionUsuario" value="<?= $usuario['direccionUsuario'] ?>" required>

            <label for="rolUsuario">Rol:</label>
            <input type="text" name="rolUsuario" value="<?= $usuario['rolUsuario'] ?>" required>

            <label for="estadoUsuario">Estado:</label>
            <select name="estadoUsuario" required>
                <option value="activo" <?= $usuario['estadoUsuario'] === 'activo' ? 'selected' : '' ?>>Activo</option>
                <option value="inactivo" <?= $usuario['estadoUsuario'] === 'inactivo' ? 'selected' : '' ?>>Inactivo</option>
            </select>

            <button type="submit" class="button">Actualizar Usuario</button>
        </form>
        <a href="usuario.php" class="button">Volver a Usuarios</a>
    </div>
</body>
</html>
