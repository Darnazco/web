<?php
session_start();
if (!isset($_SESSION['rolUsuario']) || $_SESSION['rolUsuario'] !== 'coordinador') {
    header("Location: login.php");
    exit();
}

require_once '../models/database.php'; // Asegúrate de que la ruta sea correcta

// Manejo de la inserción
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $sedeCurso = $_POST['sedeCurso'];
    $cantidadAlumnosCurso = $_POST['cantidadAlumnosCurso'];
    $directorCurso = $_POST['directorCurso'];
    $idRefrigerioFK = $_POST['idRefrigerioFK'];

    // Validar que la cantidad de alumnos no sea negativa
    if ($cantidadAlumnosCurso < 0) {
        $error = "La cantidad de alumnos no puede ser negativa.";
    } else {
        // Conexión a la base de datos
        $db = new Database();
        $conn = $db->getConnection();

        // Preparar la consulta de inserción
        $stmt = $conn->prepare("INSERT INTO Curso (sedeCurso, cantidadAlumnosCurso, directorCurso, idRefrigerioFK) VALUES (?, ?, ?, ?)");
        $stmt->execute([$sedeCurso, $cantidadAlumnosCurso, $directorCurso, $idRefrigerioFK]);

        // Redirigir al dashboard después de insertar
        header("Location: curso.php");
        exit();
    }
}

// Obtener los refrigerios disponibles
$db = new Database();
$conn = $db->getConnection();
$stmtRefrigerios = $conn->query("SELECT idRefrigerio, descripcionRefrigerio FROM Refrigerio");
$refrigerios = $stmtRefrigerios->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insertar Curso</title>
    <link rel="stylesheet" href="../css/insertar_curso.css">
</head>
<body>
    <div class="container">
        <h1>Insertar Nuevo Curso</h1>
        <?php if (isset($error)): ?>
            <p class="error"><?= $error ?></p>
        <?php endif; ?>
        <form method="POST" action="">
            <label for="sedeCurso">Sede:</label>
            <input type="text" name="sedeCurso" required>
            
            <label for="cantidadAlumnosCurso">Cantidad de Alumnos:</label>
            <input type="number" name="cantidadAlumnosCurso" required min="0">

            <label for="directorCurso">Director:</label>
            <input type="text" name="directorCurso" required>

            <label for="idRefrigerioFK">ID Refrigerio:</label>
            <select name="idRefrigerioFK" required>
                <option value="">Selecciona un Refrigerio</option>
                <?php foreach ($refrigerios as $refrigerio): ?>
                    <option value="<?= $refrigerio['idRefrigerio'] ?>">
                        <?= $refrigerio['descripcionRefrigerio'] ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <button type="submit" class="button">Insertar Curso</button>
        </form>
        <a href="curso.php" class="button">Volver a Cursos</a>
    </div>
</body>
</html>
