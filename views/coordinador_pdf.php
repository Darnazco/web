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
$pdf->Cell(0, 10, 'Listado de Coordinadores', 0, 1, 'C');

// Encabezados de la tabla
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(30, 10, 'ID', 1);
$pdf->Cell(40, 10, 'Nombre', 1);
$pdf->Cell(40, 10, 'Apellido', 1);
$pdf->Cell(50, 10, 'Correo', 1);
$pdf->Cell(40, 10, 'Teléfono', 1);
$pdf->Cell(40, 10, 'Estado', 1);
$pdf->Ln();

// Consulta para obtener todos los coordinadores
$stmt = $conn->prepare("SELECT idCoordinador, nombreCoordinador, apellidoCoordinador, correoCoordinador, telefonoCoordinador, estadoUsuario FROM coordinador");
$stmt->execute();
$coordinadores = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Mostrar datos de los coordinadores
$pdf->SetFont('Arial', '', 12);
foreach ($coordinadores as $coordinador) {
    $pdf->Cell(30, 10, $coordinador['idCoordinador'], 1);
    $pdf->Cell(40, 10, $coordinador['nombreCoordinador'], 1);
    $pdf->Cell(40, 10, $coordinador['apellidoCoordinador'], 1);
    $pdf->Cell(50, 10, $coordinador['correoCoordinador'], 1);
    $pdf->Cell(40, 10, $coordinador['telefonoCoordinador'], 1);
    $pdf->Cell(40, 10, $coordinador['estadoUsuario'], 1);
    $pdf->Ln();
}

// Salida del PDF
$pdf->Output('D', 'coordinadores.pdf'); // Forzar la descarga del archivo PDF
?>
