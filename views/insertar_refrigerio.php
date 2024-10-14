<?php
session_start();
if (!isset($_SESSION['rolUsuario']) || $_SESSION['rolUsuario'] !== 'coordinador') {
    header("Location: login.php");
    exit();
}

require_once '../models/database.php'; // Asegúrate de que la ruta sea correcta

// Manejo de la inserción
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fechaRefrigerio = $_POST['fechaRefrigerio'];
    $horaRefrigerio = $_POST['horaRefrigerio'];
    $tipoRefrigerio = $_POST['tipoRefrigerio'];
    $cantidadRefrigerio = $_POST['cantidadRefrigerio'];
    $descripcionRefrigerio = $_POST['descripcionRefrigerio'];
    $idCoordinadorFK = $_POST['idCoordinadorFK'];
    $idAuxiliarFK = $_POST['idAuxiliarFK'];
    $estadoRefrigerio = $_POST['estadoRefrigerio'];

    // Validar que la cantidad no sea negativa
    if ($cantidadRefrigerio < 0) {
        $error = "La cantidad no puede ser negativa.";
    } else {
        // Conexión a la base de datos
        $db = new Database();
        $conn = $db->getConnection();

        // Preparar la consulta de inserción
        $stmt = $conn->prepare("INSERT INTO Refrigerio (fechaRefrigerio, horaRefrigerio, tipoRefrigerio, cantidadRefrigerio, descripcionRefrigerio, idCoordinadorFK, idAuxiliarFK, estadoRefrigerio) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$fechaRefrigerio, $horaRefrigerio, $tipoRefrigerio, $cantidadRefrigerio, $descripcionRefrigerio, $idCoordinadorFK, $idAuxiliarFK, $estadoRefrigerio]);

        // Redirigir al dashboard después de insertar
        header("Location: refrigerio.php");
        exit();
    }
}

// Obtener los coordinadores y auxiliares disponibles
$db = new Database();
$conn = $db->getConnection();
$stmtCoordinadores = $conn->query("SELECT idCoordinador, nombreCoordinador, apellidoCoordinador FROM Coordinador");
$coordinadores = $stmtCoordinadores->fetchAll(PDO::FETCH_ASSOC);

$stmtAuxiliares = $conn->query("SELECT idAuxiliar, nombreAuxiliar, apellidoAuxiliar FROM Auxiliar");
$auxiliares = $stmtAuxiliares->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insertar Refrigerio</title>
    <link rel="stylesheet" href="../css/insertar_refrigerio.css">
</head>
<body>
    <div class="container">
        <h1>Insertar Nuevo Refrigerio</h1>
        <?php if (isset($error)): ?>
            <p class="error"><?= $error ?></p>
        <?php endif; ?>
        <form method="POST" action="">
            <label for="fechaRefrigerio">Fecha:</label>
            <input type="date" name="fechaRefrigerio" required>
            
            <label for="horaRefrigerio">Hora:</label>
            <input type="time" name="horaRefrigerio" required>
            
            <label for="tipoRefrigerio">Tipo:</label>
            <input type="text" name="tipoRefrigerio" required>
            
            <label for="cantidadRefrigerio">Cantidad:</label>
            <input type="number" name="cantidadRefrigerio" required min="0">

            <label for="descripcionRefrigerio">Descripción:</label>
            <textarea name="descripcionRefrigerio" required></textarea>

            <label for="idCoordinadorFK">ID Coordinador:</label>
            <select name="idCoordinadorFK" required>
                <option value="">Selecciona un Coordinador</option>
                <?php foreach ($coordinadores as $coordinador): ?>
                    <option value="<?= $coordinador['idCoordinador'] ?>">
                        <?= $coordinador['nombreCoordinador'] . ' ' . $coordinador['apellidoCoordinador'] ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="idAuxiliarFK">ID Auxiliar:</label>
            <select name="idAuxiliarFK" required>
                <option value="">Selecciona un Auxiliar</option>
                <?php foreach ($auxiliares as $auxiliar): ?>
                    <option value="<?= $auxiliar['idAuxiliar'] ?>">
                        <?= $auxiliar['nombreAuxiliar'] . ' ' . $auxiliar['apellidoAuxiliar'] ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="estadoRefrigerio">Estado:</label>
            <select name="estadoRefrigerio" required>
                <option value="activo">Activo</option>
                <option value="inactivo">Inactivo</option>
            </select>

            <button type="submit" class="button">Insertar Refrigerio</button>
        </form>
        <a href="refrigerio.php" class="button">Volver a Refrigerios</a>
    </div>
</body>
</html>
