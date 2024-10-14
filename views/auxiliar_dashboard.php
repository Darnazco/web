<?php
session_start();
require_once '../models/database.php';

if (!isset($_SESSION['rolUsuario']) || $_SESSION['rolUsuario'] !== 'auxiliar') {
    header("Location: ../views/login.php");
    exit();
}

// Conectar a la base de datos
$database = new Database();
$db = $database->getConnection();

// Inicializar las asignaciones
$asignaciones = [];

// Obtener asignaciones de la base de datos con nombres
$query = "
    SELECT 
        arc.idAsigRefCur,
        arc.fechaAsignacion,
        r.descripcionRefrigerio AS nombreRefrigerio,
        c.sedeCurso AS nombreCurso
    FROM 
        AsigRefrigerioCurso arc
    JOIN 
        Refrigerio r ON arc.idRefrigeriofk = r.idRefrigerio
    JOIN 
        Curso c ON arc.idCursofk = c.idCurso
";
$stmt = $db->prepare($query);
$stmt->execute();
$asignaciones = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Si se busca por fecha
$fechabusqueda = '';
if (isset($_POST['fechabusqueda'])) {
    $fechabusqueda = $_POST['fechabusqueda'];
    $query = "
        SELECT 
            arc.idAsigRefCur,
            arc.fechaAsignacion,
            r.descripcionRefrigerio AS nombreRefrigerio,
            c.sedeCurso AS nombreCurso
        FROM 
            AsigRefrigerioCurso arc
        JOIN 
            Refrigerio r ON arc.idRefrigeriofk = r.idRefrigerio
        JOIN 
            Curso c ON arc.idCursofk = c.idCurso
        WHERE 
            arc.fechaAsignacion = :fecha
    ";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':fecha', $fechabusqueda);
    $stmt->execute();
    $asignaciones = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asignación de Refrigerios</title>
    <link rel="stylesheet" href="../css/auxiliar_dashboard.css">
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        h1 {
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 8px;
            text-align: center;
        }
        .rojo {
            color: red;
        }
        .verde {
            color: green;
        }
        .btn {
            padding: 10px 15px;
            margin: 10px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <h1>Asignación de Refrigerios</h1>
    
    <form method="POST" action="">
        <input type="date" name="fechabusqueda" value="<?php echo $fechabusqueda; ?>" required>
        <button type="submit" class="btn">Buscar</button>
    </form>

    <table>
        <thead>
            <tr>
                <th>ID Asignación</th>
                <th>Fecha Asignación</th>
                <th>Refrigerio</th>
                <th>Curso</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($asignaciones as $asignacion): ?>
            <tr id="row-<?php echo $asignacion['idAsigRefCur']; ?>" class="rojo">
                <td><?php echo $asignacion['idAsigRefCur']; ?></td>
                <td><?php echo $asignacion['fechaAsignacion']; ?></td>
                <td><?php echo $asignacion['nombreRefrigerio']; ?></td>
                <td><?php echo $asignacion['nombreCurso']; ?></td>
                <td>
                    <button class="btn-asignar" data-id="<?php echo $asignacion['idAsigRefCur']; ?>">Asignar</button>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <button onclick="window.location.href='logout.php';" class="btn">Cerrar Sesión</button>

    <script>
        // Asignar evento a los botones
        document.querySelectorAll('.btn-asignar').forEach(button => {
            button.addEventListener('click', function() {
                const idAsignacion = this.getAttribute('data-id');
                const row = document.getElementById('row-' + idAsignacion);
                // Cambiar el color de la fila a verde
                row.classList.remove('rojo');
                row.classList.add('verde');
                
                // Mensaje de asignación correcta
                alert('Asignación correcta');

                // Aquí se puede agregar la lógica para guardar el estado en la base de datos
                fetch('actualizar_estado.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id: idAsignacion })
                });
            });
        });
    </script>
</body>
</html>
