<?php
session_start();
if (!isset($_SESSION['rolUsuario']) || $_SESSION['rolUsuario'] !== 'coordinador') {
    header("Location: login.php");
    exit();
}

require_once '../models/database.php'; // Asegúrate de que la ruta sea correcta

// Manejo de la inserción
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombreUsuario = $_POST['nombreUsuario'];
    $apellidoUsuario = $_POST['apellidoUsuario'];
    $correoUsuario = $_POST['correoUsuario'];
    $telefonoUsuario = $_POST['telefonoUsuario'];
    $direccionUsuario = $_POST['direccionUsuario'];
    $passwordUsuario = $_POST['passwordUsuario'];
    
    // Rol y estado por defecto
    $rolUsuario = 'estudiante';
    $estadoUsuario = 1; // Activo

    // Conexión a la base de datos
    $db = new Database();
    $conn = $db->getConnection();

    // Preparar la consulta de inserción
    $stmt = $conn->prepare("INSERT INTO Usuario (nombreUsuario, apellidoUsuario, correoUsuario, telefonoUsuario, direccionUsuario, passwordUsuario, rolUsuario, estadoUsuario) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$nombreUsuario, $apellidoUsuario, $correoUsuario, $telefonoUsuario, $direccionUsuario, $passwordUsuario, $rolUsuario, $estadoUsuario]);

    // Redirigir al dashboard después de insertar
    header("Location: usuario.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insertar Usuario</title>
    <link rel="stylesheet" href="../css/insertar_usuario.css">
</head>
<body>
    <div class="container">
        <h1>Insertar Nuevo Usuario</h1>
        <form method="POST" action="">
            <label for="nombreUsuario">Nombre:</label>
            <input type="text" name="nombreUsuario" required>
            
            <label for="apellidoUsuario">Apellido:</label>
            <input type="text" name="apellidoUsuario" required>
            
            <label for="correoUsuario">Correo:</label>
            <input type="email" name="correoUsuario" required>
            
            <label for="telefonoUsuario">Teléfono:</label>
            <input type="text" name="telefonoUsuario" required>
            
            <label for="direccionUsuario">Dirección:</label>
            <input type="text" name="direccionUsuario" required>
            
            <label for="passwordUsuario">Contraseña:</label>
            <input type="password" name="passwordUsuario" required>

            <button type="submit" class="button">Insertar Usuario</button>
        </form>
        <a href="usuario.php" class="button">Volver a Usuarios</a>
    </div>
</body>
</html>
