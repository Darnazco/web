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
    $idAsigRefCur = $_POST['idAsigRefCur'];
    $fechaAsignacion = $_POST['fechaAsignacion'];
    $idRefrigerioFk = $_POST['idRefrigerioFk'];
    $idCursoFk = $_POST['idCursoFk'];

    // Preparar la consulta de actualización
    $stmt = $conn->prepare("UPDATE asigrefrigeriocurso SET fechaAsignacion = ?, idRefrigerioFk = ?, idCursoFk = ? WHERE idAsigRefCur = ?");
    $stmt->execute([$fechaAsignacion, $idRefrigerioFk, $idCursoFk, $idAsigRefCur]);

    // Redirigir al dashboard después de actualizar
    header("Location: asignacion_refrigerios.php");
    exit();
}

// Obtener el ID de la asignación a actualizar
if (isset($_GET['idAsigRefCur'])) {
    $idAsigRefCur = $_GET['idAsigRefCur'];

    // Obtener datos de la asignación
    $stmt = $conn->prepare("SELECT * FROM asigrefrigeriocurso WHERE idAsigRefCur = ?");
    $stmt->execute([$idAsigRefCur]);
    $asignacion = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$asignacion) {
        echo "Asignación no encontrada.";
        exit();
    }

    // Obtener opciones para refrigerios y cursos
    $refrigerios = $conn->query("SELECT idRefrigerio, descripcionRefrigerio FROM refrigerio")->fetchAll(PDO::FETCH_ASSOC);
    $cursos = $conn->query("SELECT idCurso, sedeCurso FROM curso")->fetchAll(PDO::FETCH_ASSOC);
} else {
    echo "ID de asignación no especificado.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualizar Asignación de Refrigerios</title>
    <link rel="stylesheet" href="../css/actualizar_asignacion.css">
</head>
<body>
    <div class="container">
        <h1>Actualizar Asignación de Refrigerios</h1>
        <form method="POST" action="">
            <input type="hidden" name="idAsigRefCur" value="<?= $asignacion['idAsigRefCur'] ?>">

            <label for="fechaAsignacion">Fecha de Asignación:</label>
            <input type="date" name="fechaAsignacion" value="<?= $asignacion['fechaAsignacion'] ?>" required>

            <label for="idRefrigerioFk">Refrigerio:</label>
<select name="idRefrigerioFk" required>
    <?php foreach ($refrigerios as $refrigerio): ?>
        <option value="<?= $refrigerio['idRefrigerio'] ?>" <?= (isset($asignacion['idRefrigerioFk']) && $refrigerio['idRefrigerio'] == $asignacion['idRefrigerioFk']) ? 'selected' : '' ?>>
            <?= $refrigerio['descripcionRefrigerio'] ?>
        </option>
    <?php endforeach; ?>
</select>

<label for="idCursoFk">Curso:</label>
<select name="idCursoFk" required>
    <?php foreach ($cursos as $curso): ?>
        <option value="<?= $curso['idCurso'] ?>" <?= (isset($asignacion['idCursoFk']) && $curso['idCurso'] == $asignacion['idCursoFk']) ? 'selected' : '' ?>>
            <?= $curso['sedeCurso'] ?>
        </option>
    <?php endforeach; ?>
</select>






            
            <button type="submit" class="button">Actualizar Asignación</button>
        </form>
        <a href="asignacion_refrigerios.php" class="button">Volver a Asignaciones</a>
    </div>
</body>
</html>
