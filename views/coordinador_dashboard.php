<?php
session_start();
if (!isset($_SESSION['rolUsuario']) || $_SESSION['rolUsuario'] !== 'coordinador') {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Coordinador</title>
    <link rel="stylesheet" href="../css/coordinador_dashboard.css">
</head>
<body>
    <div class="container">
        <h1>Bienvenido Coordinador/@</h1>
        <div class="button-container">
            <a href="asignacion_refrigerios.php" class="button">Asignación de Refrigerios</a>
            <a href="auxiliar.php" class="button">Auxiliar</a>
            <a href="coordinador.php" class="button">Coordinador</a>
            <a href="curso.php" class="button">Curso</a>
            <a href="refrigerio.php" class="button">Refrigerio</a>
            <a href="usuario.php" class="button">Usuario</a>
            <a href="procedimiento_almacenado.php" class="button">Procedimiento Almacenado</a>

            <a href="logout.php" class="button">Cerrar Sesión</a>
        </div>
    </div>
</body>
</html>
