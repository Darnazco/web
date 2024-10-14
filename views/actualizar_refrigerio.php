<?php
// Incluir el archivo de conexión a la base de datos
require_once '../models/database.php';

// Crear una conexión a la base de datos
$db = new Database();
$connection = $db->getConnection();

// Verificar si el idRefrigerio está presente en la URL
if (isset($_GET['idRefrigerio'])) {
    $idRefrigerio = $_GET['idRefrigerio'];

    // Preparar y ejecutar la consulta para obtener el refrigerio
    $stmt = $connection->prepare("SELECT * FROM refrigerio WHERE idRefrigerio = :idRefrigerio");
    $stmt->bindParam(':idRefrigerio', $idRefrigerio);
    $stmt->execute();

    // Obtener el resultado
    $refrigerio = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verificar si se encontró el refrigerio
    if (!$refrigerio) {
        die("Refrigerio no encontrado.");
    }
} else {
    die("ID de refrigerio no especificado.");
}

// Procesar el formulario de actualización
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener los datos del formulario
    $fechaRefrigerio = $_POST['fechaRefrigerio'];
    $horaRefrigerio = $_POST['horaRefrigerio'];
    $tipoRefrigerio = $_POST['tipoRefrigerio'];
    $cantidadRefrigerio = $_POST['cantidadRefrigerio'];
    $descripcionRefrigerio = $_POST['descripcionRefrigerio'];
    $idCoordinadorFK = $_POST['idCoordinadorFK'];
    $idAuxiliarFK = $_POST['idAuxiliarFK'];

    // Validar que la cantidad no sea negativa
    if ($cantidadRefrigerio < 0) {
        die("La cantidad no puede ser negativa.");
    }

    // Actualizar el refrigerio en la base de datos
    $stmt = $connection->prepare("UPDATE refrigerio SET fechaRefrigerio = :fecha, horaRefrigerio = :hora, tipoRefrigerio = :tipo, cantidadRefrigerio = :cantidad, descripcionRefrigerio = :descripcion, idCoordinadorFK = :idCoordinador, idAuxiliarFK = :idAuxiliar WHERE idRefrigerio = :idRefrigerio");
    $stmt->bindParam(':fecha', $fechaRefrigerio);
    $stmt->bindParam(':hora', $horaRefrigerio);
    $stmt->bindParam(':tipo', $tipoRefrigerio);
    $stmt->bindParam(':cantidad', $cantidadRefrigerio);
    $stmt->bindParam(':descripcion', $descripcionRefrigerio);
    $stmt->bindParam(':idCoordinador', $idCoordinadorFK);
    $stmt->bindParam(':idAuxiliar', $idAuxiliarFK);
    $stmt->bindParam(':idRefrigerio', $idRefrigerio);

    if ($stmt->execute()) {
        // Redirigir a la página de coordinador después de actualizar
        header('Location: refrigerio.php?mensaje=Refrigerio actualizado exitosamente.');
        exit();
    } else {
        echo "Error al actualizar el refrigerio.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Actualizar Refrigerio</title>
    <link rel="stylesheet" href="../css/actualizar_refrigerio.css">
</head>
<body>
    <h1>Actualizar Refrigerio</h1>
    <form action="" method="post">
        <input type="hidden" name="idRefrigerio" value="<?= $refrigerio['idRefrigerio'] ?>">
        <label for="fechaRefrigerio">Fecha:</label>
        <input type="date" name="fechaRefrigerio" value="<?= $refrigerio['fechaRefrigerio'] ?>" required>
        <br>
        <label for="horaRefrigerio">Hora:</label>
        <input type="time" name="horaRefrigerio" value="<?= $refrigerio['horaRefrigerio'] ?>" required>
        <br>
        <label for="tipoRefrigerio">Tipo:</label>
        <input type="text" name="tipoRefrigerio" value="<?= $refrigerio['tipoRefrigerio'] ?>" required>
        <br>
        <label for="cantidadRefrigerio">Cantidad:</label>
        <input type="number" name="cantidadRefrigerio" value="<?= $refrigerio['cantidadRefrigerio'] ?>" min="0" required>
        <br>
        <label for="descripcionRefrigerio">Descripción:</label>
        <textarea name="descripcionRefrigerio" required><?= $refrigerio['descripcionRefrigerio'] ?></textarea>
        <br>
        
        <label for="idCoordinadorFK">ID Coordinador:</label>
        <select name="idCoordinadorFK" required>
            <!-- Aquí deberías incluir la lógica para mostrar los coordinadores disponibles -->
            <?php
            $coordinadoresStmt = $connection->prepare("SELECT idCoordinador, nombreCoordinador FROM coordinador");
            $coordinadoresStmt->execute();
            $coordinadores = $coordinadoresStmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($coordinadores as $coordinador) {
                echo "<option value=\"{$coordinador['idCoordinador']}\" " . ($coordinador['idCoordinador'] == $refrigerio['idCoordinadorFK'] ? 'selected' : '') . ">{$coordinador['nombreCoordinador']}</option>";
            }
            ?>
        </select>
        <br>

        <label for="idAuxiliarFK">ID Auxiliar:</label>
        <select name="idAuxiliarFK" required>
            <!-- Aquí deberías incluir la lógica para mostrar los auxiliares disponibles -->
            <?php
            $auxiliaresStmt = $connection->prepare("SELECT idAuxiliar, nombreAuxiliar FROM auxiliar");
            $auxiliaresStmt->execute();
            $auxiliares = $auxiliaresStmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($auxiliares as $auxiliar) {
                echo "<option value=\"{$auxiliar['idAuxiliar']}\" " . ($auxiliar['idAuxiliar'] == $refrigerio['idAuxiliarFK'] ? 'selected' : '') . ">{$auxiliar['nombreAuxiliar']}</option>";
            }
            ?>
        </select>
        <br>
        
        <button type="submit">Actualizar</button>
    </form>
    <br>
    <a href="coordinador_dashboard.php">Volver al Dashboard</a>
</body>
</html>
