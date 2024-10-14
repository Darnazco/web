<?php
session_start();
if (!isset($_SESSION['rolUsuario']) || $_SESSION['rolUsuario'] !== 'coordinador') {
    header("Location: login.php");
    exit();
}

require_once '../models/database.php'; // Asegúrate de que la ruta sea correcta

// Manejo de la inserción
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombreCoordinador = $_POST['nombreCoordinador'];
    $apellidoCoordinador = $_POST['apellidoCoordinador'];
    $correoCoordinador = $_POST['correoCoordinador'];
    $telefonoCoordinador = $_POST['telefonoCoordinador'];
    $oficinaCoordinador = $_POST['oficinaCoordinador'];
    $idUsuariofk = $_POST['idUsuariofk'];

    // Conexión a la base de datos
    $db = new Database();
    $conn = $db->getConnection();

    // Preparar la consulta de inserción
    $stmt = $conn->prepare("INSERT INTO Coordinador (nombreCoordinador, apellidoCoordinador, correoCoordinador, telefonoCoordinador, oficinaCoordinador, idUsuariofk) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$nombreCoordinador, $apellidoCoordinador, $correoCoordinador, $telefonoCoordinador, $oficinaCoordinador, $idUsuariofk]);

    // Redirigir al dashboard después de insertar
    header("Location: coordinador.php");
    exit();
}

// Obtener los usuarios disponibles
$db = new Database();
$conn = $db->getConnection();
$stmt = $conn->query("SELECT idUsuario, nombreUsuario, apellidoUsuario FROM Usuario WHERE estadoUsuario = 1");
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insertar Coordinador</title>
    <link rel="stylesheet" href="../css/insertar_coordinador.css">
</head>
<body>
    <div class="container">
        <h1>Insertar Nuevo Coordinador</h1>
        <form method="POST" action="">
            <label for="nombreCoordinador">Nombre:</label>
            <input type="text" name="nombreCoordinador" required>
            
            <label for="apellidoCoordinador">Apellido:</label>
            <input type="text" name="apellidoCoordinador" required>
            
            <label for="correoCoordinador">Correo:</label>
            <input type="email" name="correoCoordinador" required>
            
            <label for="telefonoCoordinador">Teléfono:</label>
            <input type="text" name="telefonoCoordinador" required>
            
            <label for="oficinaCoordinador">Oficina:</label>
            <input type="text" name="oficinaCoordinador" required>
            
            <label for="idUsuariofk">ID Usuario:</label>
            <select name="idUsuariofk" required>
                <option value="">Selecciona un Usuario</option>
                <?php foreach ($usuarios as $usuario): ?>
                    <option value="<?= $usuario['idUsuario'] ?>">
                        <?= $usuario['nombreUsuario'] . ' ' . $usuario['apellidoUsuario'] ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <button type="submit" class="button">Insertar Coordinador</button>
        </form>
        <a href="coordinador.php" class="button">Volver a Coordinadores</a>
    </div>
</body>
</html>
