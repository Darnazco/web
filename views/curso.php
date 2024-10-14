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
    $stmt = $conn->prepare("SELECT * FROM Curso WHERE sedeCurso LIKE ? OR directorCurso LIKE ?");
    $stmt->execute(["%$searchTerm%", "%$searchTerm%"]);
} else {
    $stmt = $conn->prepare("SELECT * FROM Curso");
    $stmt->execute();
}

$cursos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cursos</title>
    <link rel="stylesheet" href="../css/curso.css">
</head>
<body>
    <div class="container">
        <h1>Lista de Cursos</h1>
        <a href="curso_pdf.php">Generar PDF de Cursos</a>

        <form method="POST">
            <input type="text" name="searchTerm" placeholder="Buscar..." value="<?= htmlspecialchars($searchTerm) ?>">
            <button type="submit" name="search" class="button">Buscar</button>
        </form>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Sede</th>
                    <th>Cantidad de Alumnos</th>
                    <th>Director</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($cursos) > 0): ?>
                    <?php foreach ($cursos as $curso): ?>
                        <tr>
                            <td><?= htmlspecialchars($curso['idCurso']) ?></td>
                            <td><?= htmlspecialchars($curso['sedeCurso']) ?></td>
                            <td><?= htmlspecialchars($curso['cantidadAlumnosCurso']) ?></td>
                            <td><?= htmlspecialchars($curso['directorCurso']) ?></td>
                            <td><?= $curso['estadoCurso'] ? 'Activo' : 'Inactivo' ?></td>
                            <td>
                            <a href="actualizar_curso.php?idCurso=<?= $curso['idCurso'] ?>">Actualizar</a>

                            <a href="eliminar_curso.php?idCurso=<?= $curso['idCurso'] ?>" onclick="return confirm('¿Estás seguro de que deseas eliminar este curso?');">Eliminar</a>

                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6">No hay cursos registrados.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <a href="insertar_curso.php" class="button">Insertar Curso</a>
        <a href="coordinador_dashboard.php" class="button">Volver al Dashboard</a>
    </div>
</body>
</html>
