<?php
session_start();

// Verificamos que el usuario ha iniciado sesión y es un estudiante
if (!isset($_SESSION['idUsuario']) || $_SESSION['rolUsuario'] != 'estudiante') {
    header("Location: ../views/login.php");
    exit();
}

require_once '../models/database.php';

// Verificar si se envió el id del curso seleccionado
if (isset($_POST['idCurso'])) {
    $idCurso = $_POST['idCurso'];

    // Conexión a la base de datos
    $database = new Database();
    $db = $database->getConnection();

    // Consultar las asignaciones de refrigerios para el curso seleccionado
    $query = "SELECT ar.fechaAsignacion, r.tipoRefrigerio, r.cantidadRefrigerio, r.descripcionRefrigerio
              FROM AsigRefrigerioCurso ar
              JOIN Refrigerio r ON ar.idRefrigeriofk = r.idRefrigerio
              WHERE ar.idCursofk = :idCurso";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':idCurso', $idCurso);
    $stmt->execute();
    $asignaciones = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    header("Location: estudiante_dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asignación de Refrigerios</title>
    <link rel="stylesheet" href="../css/asignación_refrigerios_cursos.css">
</head>
<body>
    <h1>Asignación de Refrigerios para el Curso</h1>

    <?php if (count($asignaciones) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Fecha de Asignación</th>
                    <th>Tipo de Refrigerio</th>
                    <th>Cantidad de Refrigerios</th>
                    <th>Descripción</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($asignaciones as $asignacion): ?>
                    <tr>
                        <td><?php echo $asignacion['fechaAsignacion']; ?></td>
                        <td><?php echo $asignacion['tipoRefrigerio']; ?></td>
                        <td><?php echo $asignacion['cantidadRefrigerio']; ?></td>
                        <td><?php echo $asignacion['descripcionRefrigerio']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No hay asignaciones de refrigerios para este curso.</p>
    <?php endif; ?>
    
    <br>
    <a href="estudiante_dashboard.php">Volver a seleccionar curso</a>
</body>
</html>
