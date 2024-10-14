<?php
// Incluir el archivo de conexión a la base de datos
require_once '../models/database.php';

// Crear una conexión a la base de datos
$db = new Database();
$connection = $db->getConnection();

// Verificar si el idAuxiliar está presente en la URL
if (isset($_GET['idAuxiliar'])) {
    $idAuxiliar = $_GET['idAuxiliar'];

    // Preparar y ejecutar la consulta para obtener el auxiliar
    $stmt = $connection->prepare("SELECT * FROM auxiliar WHERE idAuxiliar = :idAuxiliar");
    $stmt->bindParam(':idAuxiliar', $idAuxiliar);
    $stmt->execute();

    // Obtener el resultado
    $auxiliar = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verificar si se encontró el auxiliar
    if (!$auxiliar) {
        die("Auxiliar no encontrado.");
    }
} else {
    die("ID de auxiliar no especificado.");
}

// Procesar el formulario de actualización
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener los datos del formulario
    $nombreAuxiliar = $_POST['nombreAuxiliar'];
    $apellidoAuxiliar = $_POST['apellidoAuxiliar'];
    $direccionAuxiliar = $_POST['direccionAuxiliar'];
    $telefonoAuxiliar = $_POST['telefonoAuxiliar'];
    $correoAuxiliar = $_POST['correoAuxiliar'];

    // Actualizar el auxiliar en la base de datos
    $stmt = $connection->prepare("UPDATE auxiliar SET nombreAuxiliar = :nombre, apellidoAuxiliar = :apellido, direccionAuxiliar = :direccion, telefonoAuxiliar = :telefono, correoAuxiliar = :correo WHERE idAuxiliar = :idAuxiliar");
    $stmt->bindParam(':nombre', $nombreAuxiliar);
    $stmt->bindParam(':apellido', $apellidoAuxiliar);
    $stmt->bindParam(':direccion', $direccionAuxiliar);
    $stmt->bindParam(':telefono', $telefonoAuxiliar);
    $stmt->bindParam(':correo', $correoAuxiliar);
    $stmt->bindParam(':idAuxiliar', $idAuxiliar);

    if ($stmt->execute()) {
        // Redirigir a la página de coordinador después de actualizar
        header('Location: auxiliar.php?mensaje=Auxiliar actualizado exitosamente.');
        exit();
    } else {
        echo "Error al actualizar el auxiliar.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Actualizar Auxiliar</title>
    <link rel="stylesheet" href="../css/actualizar_usuario.css">
</head>
<body>
    <h1>Actualizar Auxiliar</h1>
    <form action="" method="post">
        <input type="hidden" name="idAuxiliar" value="<?= $auxiliar['idAuxiliar'] ?>">
        <label for="nombreAuxiliar">Nombre:</label>
        <input type="text" name="nombreAuxiliar" value="<?= $auxiliar['nombreAuxiliar'] ?>" required>
        <br>
        <label for="apellidoAuxiliar">Apellido:</label>
        <input type="text" name="apellidoAuxiliar" value="<?= $auxiliar['apellidoAuxiliar'] ?>" required>
        <br>
        <label for="direccionAuxiliar">Dirección:</label>
        <input type="text" name="direccionAuxiliar" value="<?= $auxiliar['direccionAuxiliar'] ?>" required>
        <br>
        <label for="telefonoAuxiliar">Teléfono:</label>
        <input type="text" name="telefonoAuxiliar" value="<?= $auxiliar['telefonoAuxiliar'] ?>" required>
        <br>
        <label for="correoAuxiliar">Correo:</label>
        <input type="email" name="correoAuxiliar" value="<?= $auxiliar['correoAuxiliar'] ?>" required>
        <br>
        <button type="submit">Actualizar</button>
    </form>
    <br>
    <a href="coordinador_dashboard.php">Volver al Dashboard</a>
</body>
</html>
