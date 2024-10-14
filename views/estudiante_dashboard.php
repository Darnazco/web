<?php
session_start();

// Verificar si el usuario ha iniciado sesión y es un estudiante
if (!isset($_SESSION['idUsuario']) || $_SESSION['rolUsuario'] != 'estudiante') {
    header("Location: ../views/login.php");
    exit();
}

require_once '../models/database.php';

// Conexión a la base de datos
$database = new Database();
$db = $database->getConnection();

// Obtener los cursos disponibles (solo aquellos con estado activo)
$query = "SELECT idCurso, sedeCurso FROM Curso WHERE estadoCurso = 0";
$stmt = $db->prepare($query);
$stmt->execute();
$cursos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Depuración: Verificar si se obtienen los cursos
if (empty($cursos)) {
    echo "<p>No hay cursos disponibles en este momento.</p>";
} else {
    echo "<p>Se encontraron " . count($cursos) . " cursos.</p>";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Estudiante</title>
    <link rel="stylesheet" href="../css/estudiante_dashboard.css">
</head>
<body>
    <h1>Bienvenido Estudiante</h1>
    <h2>Selecciona tu curso:</h2>

    <form action="asignacion_refrigerios_cursos.php" method="POST">
        <label for="curso">Curso (Sede):</label>
        <select name="idCurso" id="curso" required>
            <option value="" disabled selected>Selecciona un curso</option>
            <?php if (!empty($cursos)): ?>
                <?php foreach ($cursos as $curso): ?>
                    <option value="<?php echo $curso['idCurso']; ?>"><?php echo $curso['sedeCurso']; ?></option>
                <?php endforeach; ?>
            <?php else: ?>
                <option value="" disabled>No hay cursos disponibles</option>
            <?php endif; ?>
        </select>
        <br><br>
        <button type="submit">Ver Asignación de Refrigerios</button>             <a href="logout.php" class="button">Cerrar Sesión</a>
    </form>
</body>
</html>
