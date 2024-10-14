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
$pdf->Cell(0, 10, 'Listado de Asignaciones de Refrigerios', 0, 1, 'C');

// Encabezados de la tabla
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(30, 10, 'ID Asignacion', 1);
$pdf->Cell(30, 10, 'ID Refrigerio', 1);
$pdf->Cell(30, 10, 'ID Curso', 1);
$pdf->Cell(40, 10, 'Fecha Asignacion', 1);
$pdf->Ln();

// Consulta para obtener todas las asignaciones
$stmt = $conn->prepare("SELECT idAsigRefCur, idRefrigeriofk, idCursofk, fechaAsignacion FROM asigrefrigeriocurso");
$stmt->execute();
$asignaciones = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Mostrar datos de las asignaciones
$pdf->SetFont('Arial', '', 12);
foreach ($asignaciones as $asignacion) {
    $pdf->Cell(30, 10, $asignacion['idAsigRefCur'], 1);
    $pdf->Cell(30, 10, $asignacion['idRefrigeriofk'], 1);
    $pdf->Cell(30, 10, $asignacion['idCursofk'], 1);
    $pdf->Cell(40, 10, $asignacion['fechaAsignacion'], 1);
    $pdf->Ln();
}

// Salida del PDF
$pdf->Output('D', 'asignaciones.pdf'); // Forzar la descarga del archivo PDF
?>
