<?php
session_start();
if (!isset($_SESSION['rolUsuario']) || $_SESSION['rolUsuario'] !== 'coordinador') {
    header("Location: login.php");
    exit();
}

// Incluir el archivo de conexión a la base de datos
require_once '../models/database.php'; // Asegúrate de que la ruta sea correcta

// Crear una instancia de la clase Database y obtener la conexión
$database = new Database();
$pdo = $database->getConnection();

// Inicializar variables
$coordinadores = [];

// Ejecutar el procedimiento almacenado
try {
    $sql = "CALL ObtenerCoordinadoresConRefrigerios()";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $coordinadores = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Procedimiento Almacenado</title>
    <link rel="stylesheet" href="../css/procedimiento_almacenado.css">
</head>
<body>
    <div class="container">
        <h1>Coordinadores con Refrigerios</h1>
        
        <?php if (count($coordinadores) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID Coordinador</th>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>Cantidad de Refrigerios</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($coordinadores as $coordinador): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($coordinador['idCoordinador']); ?></td>
                            <td><?php echo htmlspecialchars($coordinador['nombreCoordinador']); ?></td>
                            <td><?php echo htmlspecialchars($coordinador['apellidoCoordinador']); ?></td>
                            <td><?php echo htmlspecialchars($coordinador['cantidadRefrigerios']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No hay coordinadores con refrigerios registrados.</p>
        <?php endif; ?>

        <a href="coordinador_dashboard.php" class="button">Regresar</a>
    </div>
</body>
</html>
