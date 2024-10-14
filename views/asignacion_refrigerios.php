<?php
session_start();
if (!isset($_SESSION['rolUsuario']) || $_SESSION['rolUsuario'] !== 'coordinador') {
    header("Location: login.php");
    exit();
}

require_once '../models/database.php'; // Ajusta la ruta si es necesario

// Conexión a la base de datos
$db = new Database();
$conn = $db->getConnection();

// Manejo de la búsqueda
$searchTerm = '';
if (isset($_POST['search'])) {
    $searchTerm = $_POST['searchTerm'];
    $stmt = $conn->prepare("SELECT * FROM AsigRefrigerioCurso WHERE idRefrigeriofk LIKE ? OR idCursofk LIKE ?");
    $stmt->execute(["%$searchTerm%", "%$searchTerm%"]);
} else {
    $stmt = $conn->prepare("SELECT * FROM AsigRefrigerioCurso");
    $stmt->execute();
}

$asignaciones = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asignación de Refrigerios</title>
    <link rel="stylesheet" href="../css/asignacion_refrigerios.css">
</head>
<body>
    <div class="container">
        <h1>Lista de Asignaciones de Refrigerios</h1>
        <a href="asignacion_pdf.php">Generar PDF de Asignaciones</a>

        <form method="POST">
            <input type="text" name="searchTerm" placeholder="Buscar..." value="<?= htmlspecialchars($searchTerm) ?>">
            <button type="submit" name="search" class="button">Buscar</button>
        </form>
        <table>
            <thead>
                <tr>
                    <th>ID Asignación</th>
                    <th>Fecha de Asignación</th>
                    <th>ID Refrigerio</th>
                    <th>ID Curso</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($asignaciones) > 0): ?>
                    <?php foreach ($asignaciones as $asignacion): ?>
                        <tr>
                            <td><?= htmlspecialchars($asignacion['idAsigRefCur']) ?></td>
                            <td><?= htmlspecialchars($asignacion['fechaAsignacion']) ?></td>
                            <td><?= htmlspecialchars($asignacion['idRefrigeriofk']) ?></td>
                            <td><?= htmlspecialchars($asignacion['idCursofk']) ?></td>
                            <td>
                                <a href="actualizar_asignacion.php?idAsigRefCur=<?= $asignacion['idAsigRefCur'] ?>" class="button">Actualizar</a>
                                <a href="eliminar_asignacion.php?idAsigRefCur=<?= $asignacion['idAsigRefCur'] ?>" class="button">Eliminar</a>

                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5">No hay asignaciones registradas.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <a href="insertar_asignacion.php" class="button">Insertar Asignación</a>
        <a href="coordinador_dashboard.php" class="button">Volver al Dashboard</a>
    </div>
</body>
</html>
