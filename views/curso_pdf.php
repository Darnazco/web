<?php
session_start();
if (!isset($_SESSION['rolUsuario'])) {
    header("Location: login.php");
    exit();
}

require_once '../models/database.php'; // Asegúrate de que la ruta sea correcta
require('../lib/fpdf.php'); // Asegúrate de que la ruta a FPDF sea correcta

// Conexión a la base de datos
$db = new Database();
$conn = $db->getConnection();

// Crear una nueva instancia de FPDF
$pdf = new FPDF();
$pdf->AddPage();

// Establecer título
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, 'Listado de Cursos', 0, 1, 'C');

// Encabezados de la tabla
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(30, 10, 'ID', 1);
$pdf->Cell(50, 10, 'Sede', 1);
$pdf->Cell(40, 10, 'Cantidad Alumnos', 1);
$pdf->Cell(50, 10, 'Director', 1);
$pdf->Cell(30, 10, 'Estado', 1);
$pdf->Ln();

// Consulta para obtener todos los cursos
$stmt = $conn->prepare("SELECT idCurso, sedeCurso, cantidadAlumnosCurso, directorCurso, estadoCurso FROM curso");
$stmt->execute();
$cursos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Mostrar datos de los cursos
$pdf->SetFont('Arial', '', 12);
foreach ($cursos as $curso) {
    $pdf->Cell(30, 10, $curso['idCurso'], 1);
    $pdf->Cell(50, 10, $curso['sedeCurso'], 1);
    $pdf->Cell(40, 10, $curso['cantidadAlumnosCurso'], 1);
    $pdf->Cell(50, 10, $curso['directorCurso'], 1);
    $pdf->Cell(30, 10, $curso['estadoCurso'], 1);
    $pdf->Ln();
}

// Salida del PDF
$pdf->Output('D', 'cursos.pdf'); // Forzar la descarga del archivo PDF
?>
