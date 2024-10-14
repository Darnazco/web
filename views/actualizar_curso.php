<?php
// Incluir el archivo de conexión a la base de datos
require_once '../models/database.php';

// Crear una conexión a la base de datos
$db = new Database();
$connection = $db->getConnection();

// Verificar si el idCurso está presente en la URL
if (isset($_GET['idCurso'])) {
    $idCurso = $_GET['idCurso'];

    // Preparar y ejecutar la consulta para obtener el curso
    $stmt = $connection->prepare("SELECT * FROM curso WHERE idCurso = :idCurso");
    $stmt->bindParam(':idCurso', $idCurso);
    $stmt->execute();

    // Obtener el resultado
    $curso = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verificar si se encontró el curso
    if (!$curso) {
        die("Curso no encontrado.");
    }
} else {
    die("ID de curso no especificado.");
}

// Procesar el formulario de actualización
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener los datos del formulario
    $sedeCurso = $_POST['sedeCurso'];
    $cantidadAlumnosCurso = $_POST['cantidadAlumnosCurso'];
    $directorCurso = $_POST['directorCurso'];

    // Actualizar el curso en la base de datos
    $stmt = $connection->prepare("UPDATE curso SET sedeCurso = :sede, cantidadAlumnosCurso = :cantidad, directorCurso = :director WHERE idCurso = :idCurso");
    $stmt->bindParam(':sede', $sedeCurso);
    $stmt->bindParam(':cantidad', $cantidadAlumnosCurso);
    $stmt->bindParam(':director', $directorCurso);
    $stmt->bindParam(':idCurso', $idCurso);

    if ($stmt->execute()) {
        // Redirigir a la página de coordinador después de actualizar
        header('Location: coordinador_dashboard.php?mensaje=Curso actualizado exitosamente.');
        exit();
    } else {
        echo "Error al actualizar el curso.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Actualizar Curso</title>
    <link rel="stylesheet" href="../css/actualizar_curso.css">
</head>
<body>
    <h1>Actualizar Curso</h1>
    <form action="" method="post">
        <input type="hidden" name="idCurso" value="<?= $curso['idCurso'] ?>">
        <label for="sedeCurso">Sede:</label>
        <input type="text" name="sedeCurso" value="<?= $curso['sedeCurso'] ?>" required>
        <br>
        <label for="cantidadAlumnosCurso">Cantidad de Alumnos:</label>
        <input type="number" name="cantidadAlumnosCurso" value="<?= $curso['cantidadAlumnosCurso'] ?>" min="0" required>
        <br>
        <label for="directorCurso">Director:</label>
        <input type="text" name="directorCurso" value="<?= $curso['directorCurso'] ?>" required>
        <br>
        <button type="submit">Actualizar</button>
    </form>
    <br>
    <a href="coordinador_dashboard.php">Volver al Dashboard</a>
</body>
</html>
