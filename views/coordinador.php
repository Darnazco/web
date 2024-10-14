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
    $stmt = $conn->prepare("SELECT * FROM Coordinador WHERE nombreCoordinador LIKE ? OR apellidoCoordinador LIKE ?");
    $stmt->execute(["%$searchTerm%", "%$searchTerm%"]);
} else {
    $stmt = $conn->prepare("SELECT * FROM Coordinador");
    $stmt->execute();
}

$coordinadores = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coordinadores</title>
    <link rel="stylesheet" href="../css/coordinador.css">
</head>
<body>
    <div class="container">
        <h1>Lista de Coordinadores</h1>
        <a href="coordinador_pdf.php">Generar PDF de Coordinadores</a>

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
                    <th>Oficina</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($coordinadores) > 0): ?>
                    <?php foreach ($coordinadores as $coordinador): ?>
                        <tr>
                            <td><?= htmlspecialchars($coordinador['idCoordinador']) ?></td>
                            <td><?= htmlspecialchars($coordinador['nombreCoordinador']) ?></td>
                            <td><?= htmlspecialchars($coordinador['apellidoCoordinador']) ?></td>
                            <td><?= htmlspecialchars($coordinador['correoCoordinador']) ?></td>
                            <td><?= htmlspecialchars($coordinador['telefonoCoordinador']) ?></td>
                            <td><?= htmlspecialchars($coordinador['oficinaCoordinador']) ?></td>
                            <td><?= $coordinador['estadoUsuario'] ? 'Activo' : 'Inactivo' ?></td>
                            <td>
                            <a href="actualizar_coordinador.php?idCoordinador=<?= $coordinador['idCoordinador'] ?>">Actualizar</a>

                            <a href="eliminar_coordinador.php?idCoordinador=<?= $coordinador['idCoordinador'] ?>" onclick="return confirm('¿Estás seguro de que deseas eliminar este coordinador?');">Eliminar</a>

                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8">No hay coordinadores registrados.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <a href="insertar_coordinador.php" class="button">Insertar Coordinador</a>
        <a href="coordinador_dashboard.php" class="button">Volver al Dashboard</a>
    </div>
</body>
</html>
