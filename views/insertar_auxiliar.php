<?php
session_start();
if (!isset($_SESSION['rolUsuario']) || $_SESSION['rolUsuario'] !== 'coordinador') {
    header("Location: login.php");
    exit();
}

require_once '../models/database.php'; // Asegúrate de que la ruta sea correcta

// Manejo de la inserción
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombreAuxiliar = $_POST['nombreAuxiliar'];
    $apellidoAuxiliar = $_POST['apellidoAuxiliar'];
    $correoAuxiliar = $_POST['correoAuxiliar'];
    $telefonoAuxiliar = $_POST['telefonoAuxiliar'];
    $direccionAuxiliar = $_POST['direccionAuxiliar'];
    $cursoAuxiliar = $_POST['cursoAuxiliar'];
    $jornadaAuxiliar = $_POST['jornadaAuxiliar'];
    $idUsuariofk = $_POST['idUsuariofk'];

    // Conexión a la base de datos
    $db = new Database();
    $conn = $db->getConnection();

    // Preparar la consulta de inserción
    $stmt = $conn->prepare("INSERT INTO Auxiliar (nombreAuxiliar, apellidoAuxiliar, correoAuxiliar, telefonoAuxiliar, direccionAuxiliar, cursoAuxiliar, jornadaAuxiliar, idUsuariofk) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$nombreAuxiliar, $apellidoAuxiliar, $correoAuxiliar, $telefonoAuxiliar, $direccionAuxiliar, $cursoAuxiliar, $jornadaAuxiliar, $idUsuariofk]);

    // Redirigir al dashboard después de insertar
    header("Location: auxiliar.php");
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
    <title>Insertar Auxiliar</title>
    <link rel="stylesheet" href="../css/insertar_auxiliar.css">
</head>
<body>
    <div class="container">
        <h1>Insertar Nuevo Auxiliar</h1>
        <form method="POST" action="">
            <label for="nombreAuxiliar">Nombre:</label>
            <input type="text" name="nombreAuxiliar" required>
            
            <label for="apellidoAuxiliar">Apellido:</label>
            <input type="text" name="apellidoAuxiliar" required>
            
            <label for="correoAuxiliar">Correo:</label>
            <input type="email" name="correoAuxiliar" required>
            
            <label for="telefonoAuxiliar">Teléfono:</label>
            <input type="text" name="telefonoAuxiliar" required>
            
            <label for="direccionAuxiliar">Dirección:</label>
            <input type="text" name="direccionAuxiliar" required>
            
            <label for="cursoAuxiliar">Curso:</label>
            <input type="text" name="cursoAuxiliar" required>
            
            <label for="jornadaAuxiliar">Jornada:</label>
            <input type="text" name="jornadaAuxiliar" required>
            
            <label for="idUsuariofk">ID Usuario:</label>
            <select name="idUsuariofk" required>
                <option value="">Selecciona un Usuario</option>
                <?php foreach ($usuarios as $usuario): ?>
                    <option value="<?= $usuario['idUsuario'] ?>">
                        <?= $usuario['nombreUsuario'] . ' ' . $usuario['apellidoUsuario'] ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <button type="submit" class="button">Insertar Auxiliar</button>
        </form>
        <a href="auxiliar.php" class="button">Volver a Auxiliares</a>
    </div>
</body>
</html>
