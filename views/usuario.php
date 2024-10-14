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
    $stmt = $conn->prepare("SELECT * FROM Usuario WHERE nombreUsuario LIKE ? OR apellidoUsuario LIKE ?");
    $stmt->execute(["%$searchTerm%", "%$searchTerm%"]);
} else {
    $stmt = $conn->prepare("SELECT * FROM Usuario");
    $stmt->execute();
}

$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuarios</title>
    <link rel="stylesheet" href="../css/usuario.css">
</head>
<body>
    <div class="container">


        <h1>Lista de Usuarios</h1>
        <a href="usuario_pdf.php">Generar PDF de Usuarios</a>

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
                    <th>Rol</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($usuarios) > 0): ?>
                    <?php foreach ($usuarios as $usuario): ?>
                        <tr>
                            <td><?= htmlspecialchars($usuario['idUsuario']) ?></td>
                            <td><?= htmlspecialchars($usuario['nombreUsuario']) ?></td>
                            <td><?= htmlspecialchars($usuario['apellidoUsuario']) ?></td>
                            <td><?= htmlspecialchars($usuario['correoUsuario']) ?></td>
                            <td><?= htmlspecialchars($usuario['telefonoUsuario']) ?></td>
                            <td><?= htmlspecialchars($usuario['direccionUsuario']) ?></td>
                            <td><?= htmlspecialchars($usuario['rolUsuario']) ?></td>
                            <td><?= $usuario['estadoUsuario'] ? 'Activo' : 'Inactivo' ?></td>
                            <td>
                            <a href="actualizar_usuario.php?idUsuario=<?= $usuario['idUsuario'] ?>">Actualizar</a>

                            <a href="eliminar_usuario.php?idUsuario=<?= $usuario['idUsuario'] ?>" onclick="return confirm('¿Estás seguro de que deseas eliminar este usuario?');">Eliminar</a>

                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="9">No hay usuarios registrados.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <a href="insertar_usuario.php" class="button">Insertar Usuario</a>
        <a href="coordinador_dashboard.php" class="button">Volver al Dashboard</a>
    </div>
</body>
</html>
