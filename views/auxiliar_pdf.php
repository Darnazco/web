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
$pdf->Cell(0, 10, 'Listado de Auxiliares', 0, 1, 'C');

// Encabezados de la tabla
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(30, 10, 'ID', 1);
$pdf->Cell(40, 10, 'Nombre', 1);
$pdf->Cell(40, 10, 'Apellido', 1);
$pdf->Cell(60, 10, 'Correo', 1);
$pdf->Cell(30, 10, 'Estado', 1);
$pdf->Ln();

// Consulta para obtener todos los auxiliares
$stmt = $conn->prepare("SELECT idAuxiliar, nombreAuxiliar, apellidoAuxiliar, correoAuxiliar, estadoUsuario FROM auxiliar");
$stmt->execute();
$auxiliares = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Mostrar datos de los auxiliares
$pdf->SetFont('Arial', '', 12);
foreach ($auxiliares as $auxiliar) {
    $pdf->Cell(30, 10, $auxiliar['idAuxiliar'], 1);
    $pdf->Cell(40, 10, $auxiliar['nombreAuxiliar'], 1);
    $pdf->Cell(40, 10, $auxiliar['apellidoAuxiliar'], 1);
    $pdf->Cell(60, 10, $auxiliar['correoAuxiliar'], 1);
    $pdf->Cell(30, 10, $auxiliar['estadoUsuario'], 1);
    $pdf->Ln();
}

// Salida del PDF
$pdf->Output('D', 'auxiliares.pdf'); // Forzar la descarga del archivo PDF
?>
