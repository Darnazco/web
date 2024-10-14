<?php
session_start();
if (!isset($_SESSION['rolUsuario']) || $_SESSION['rolUsuario'] !== 'coordinador') {
    header("Location: login.php");
    exit();
}

require_once '../models/database.php'; // Asegúrate de que la ruta sea correcta

// Manejo de la inserción
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $idRefrigerioFK = $_POST['idRefrigerioFK'];
    $idCursoFK = $_POST['idCursoFK'];
    $fechaAsignacion = $_POST['fechaAsignacion']; // Captura la fecha
    $cantidadEntregada = $_POST['cantidadEntregada']; // Captura la cantidad de refrigerios

    // Conexión a la base de datos
    $db = new Database();
    $conn = $db->getConnection();

    // Verificar la cantidad disponible del refrigerio
    $stmtVerificar = $conn->prepare("SELECT cantidadRefrigerio FROM Refrigerio WHERE idRefrigerio = ?");
    $stmtVerificar->execute([$idRefrigerioFK]);
    $refrigerio = $stmtVerificar->fetch(PDO::FETCH_ASSOC);

    if ($refrigerio && $refrigerio['cantidadRefrigerio'] >= $cantidadEntregada) {
        // Insertar la asignación de refrigerio
        $stmt = $conn->prepare("INSERT INTO AsigRefrigerioCurso (idRefrigerioFK, idCursoFK, fechaAsignacion) VALUES (?, ?, ?)");
        $stmt->execute([$idRefrigerioFK, $idCursoFK, $fechaAsignacion]);

        // Disminuir la cantidad en la tabla Refrigerio
        $stmtUpdate = $conn->prepare("UPDATE Refrigerio SET cantidadRefrigerio = cantidadRefrigerio - ? WHERE idRefrigerio = ?");
        $stmtUpdate->execute([$cantidadEntregada, $idRefrigerioFK]);

        // Redirigir al dashboard después de insertar
        header("Location: asignacion_refrigerios.php");
        exit();
    } else {
        $error = "No hay suficiente cantidad de este refrigerio disponible.";
    }
}

// Obtener los refrigerios disponibles
$db = new Database();
$conn = $db->getConnection();
$stmtRefrigerios = $conn->query("SELECT idRefrigerio, descripcionRefrigerio FROM Refrigerio WHERE cantidadRefrigerio > 0");
$refrigerios = $stmtRefrigerios->fetchAll(PDO::FETCH_ASSOC);

// Obtener los cursos disponibles
$stmtCursos = $conn->query("SELECT idCurso, sedeCurso FROM Curso");
$cursos = $stmtCursos->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insertar Asignación de Refrigerio</title>
    <link rel="stylesheet" href="../css/insertar_asignacion.css">
</head>
<body>
    <div class="container">
        <h1>Insertar Asignación de Refrigerio</h1>
        <?php if (isset($error)): ?>
            <p class="error"><?= $error ?></p>
        <?php endif; ?>
        <form method="POST" action="">
            <label for="idRefrigerioFK">ID Refrigerio:</label>
            <select name="idRefrigerioFK" required>
                <option value="">Selecciona un Refrigerio</option>
                <?php foreach ($refrigerios as $refrigerio): ?>
                    <option value="<?= $refrigerio['idRefrigerio'] ?>">
                        <?= $refrigerio['descripcionRefrigerio'] ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="idCursoFK">ID Curso:</label>
            <select name="idCursoFK" required>
                <option value="">Selecciona un Curso</option>
                <?php foreach ($cursos as $curso): ?>
                    <option value="<?= $curso['idCurso'] ?>">
                        <?= $curso['sedeCurso'] ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="cantidadEntregada">Cantidad de Refrigerios a Entregar:</label>
            <input type="number" name="cantidadEntregada" min="1" required>

            <label for="fechaAsignacion">Fecha de Asignación:</label>
            <input type="date" name="fechaAsignacion" required>

            <button type="submit" class="button">Insertar Asignación</button>
        </form>
        <a href="asignacion_refrigerios.php" class="button">Volver a Asignaciones</a>
    </div>
</body>
</html>
