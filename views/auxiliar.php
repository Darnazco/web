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
    $stmt = $conn->prepare("SELECT * FROM Auxiliar WHERE nombreAuxiliar LIKE ? OR apellidoAuxiliar LIKE ?");
    $stmt->execute(["%$searchTerm%", "%$searchTerm%"]);
} else {
    $stmt = $conn->prepare("SELECT * FROM Auxiliar");
    $stmt->execute();
}

$auxiliares = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Auxiliares</title>
    <link rel="stylesheet" href="../css/auxiliar.css">
</head>
<body>
    <div class="container">
        <h1>Lista de Auxiliares</h1>
        <a href="auxiliar_pdf.php">Generar PDF de Auxiliares</a>

        <form method="POST">
            <input type="text" name="searchTerm" placeholder="Buscar..." value="<?= htmlspecialchars($searchTerm) ?>">
            <button type="submit" name="search" class="button">Buscar</button>
        </form>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Correo</th>
                    <th>Teléfono</th>
                    <th>Dirección</th>
                    <th>Curso</th>
                    <th>Jornada</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($auxiliares) > 0): ?>
                    <?php foreach ($auxiliares as $auxiliar): ?>
                        <tr>
                            <td><?= htmlspecialchars($auxiliar['idAuxiliar']) ?></td>
                            <td><?= htmlspecialchars($auxiliar['nombreAuxiliar']) ?></td>
                            <td><?= htmlspecialchars($auxiliar['apellidoAuxiliar']) ?></td>
                            <td><?= htmlspecialchars($auxiliar['correoAuxiliar']) ?></td>
                            <td><?= htmlspecialchars($auxiliar['telefonoAuxiliar']) ?></td>
                            <td><?= htmlspecialchars($auxiliar['direccionAuxiliar']) ?></td>
                            <td><?= htmlspecialchars($auxiliar['cursoAuxiliar']) ?></td>
                            <td><?= htmlspecialchars($auxiliar['jornadaAuxiliar']) ?></td>
                            <td><?= $auxiliar['estadoUsuario'] ? 'Activo' : 'Inactivo' ?></td>
                            <td>
                            <a href="actualizar_auxiliar.php?idAuxiliar=<?= $auxiliar['idAuxiliar'] ?>">Actualizar</a>

                            <a href="eliminar_auxiliar.php?idAuxiliar=<?= $auxiliar['idAuxiliar'] ?>" class="button">Eliminar</a>

                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="10">No hay auxiliares registrados.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <a href="insertar_auxiliar.php" class="button">Insertar Auxiliar</a>
        <a href="coordinador_dashboard.php" class="button">Volver al Dashboard</a>
    </div>
</body>
</html>
