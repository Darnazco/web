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
    $stmt = $conn->prepare("SELECT * FROM Refrigerio WHERE tipoRefrigerio LIKE ? OR descripcionRefrigerio LIKE ?");
    $stmt->execute(["%$searchTerm%", "%$searchTerm%"]);
} else {
    $stmt = $conn->prepare("SELECT * FROM Refrigerio");
    $stmt->execute();
}

$refrigerios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Refrigerios</title>
    <link rel="stylesheet" href="../css/refrigerio.css">
</head>
<body>
    <div class="container">
        <h1>Lista de Refrigerios</h1>
        <a href="refrigerio_pdf.php">Generar PDF de Refrigerios</a>

        <form method="POST">
            <input type="text" name="searchTerm" placeholder="Buscar..." value="<?= htmlspecialchars($searchTerm) ?>">
            <button type="submit" name="search" class="button">Buscar</button>
        </form>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Fecha</th>
                    <th>Hora</th>
                    <th>Tipo</th>
                    <th>Cantidad</th>
                    <th>Descripción</th>
                    <th>Estado</th>
                    <th>Coordinador</th>
                    <th>Auxiliar</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($refrigerios) > 0): ?>
                    <?php foreach ($refrigerios as $refrigerio): ?>
                        <tr>
                            <td><?= htmlspecialchars($refrigerio['idRefrigerio']) ?></td>
                            <td><?= htmlspecialchars($refrigerio['fechaRefrigerio']) ?></td>
                            <td><?= htmlspecialchars($refrigerio['horaRefrigerio']) ?></td>
                            <td><?= htmlspecialchars($refrigerio['tipoRefrigerio']) ?></td>
                            <td><?= htmlspecialchars($refrigerio['cantidadRefrigerio']) ?></td>
                            <td><?= htmlspecialchars($refrigerio['descripcionRefrigerio']) ?></td>
                            <td><?= $refrigerio['estadoRefrigerio'] ? 'Activo' : 'Inactivo' ?></td>
                            <td><?= htmlspecialchars($refrigerio['idCoordinadorFK']) ?></td>
                            <td><?= htmlspecialchars($refrigerio['idAuxiliarFK']) ?></td>
                            <td>
                            <a href="actualizar_refrigerio.php?idRefrigerio=<?= $refrigerio['idRefrigerio'] ?>">Actualizar</a>

                            <a href="eliminar_refrigerio.php?idRefrigerio=<?= $refrigerio['idRefrigerio'] ?>" onclick="return confirm('¿Estás seguro de que deseas eliminar este refrigerio?');">Eliminar</a>

                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="10">No hay refrigerios registrados.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <a href="insertar_refrigerio.php" class="button">Insertar Refrigerio</a>
        <a href="coordinador_dashboard.php" class="button">Volver al Dashboard</a>
    </div>
</body>
</html>