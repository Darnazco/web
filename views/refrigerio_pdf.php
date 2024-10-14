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
$pdf->Cell(0, 10, 'Listado de Refrigerios', 0, 1, 'C');

// Encabezados de la tabla
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(30, 10, 'ID', 1);
$pdf->Cell(40, 10, 'Fecha', 1);
$pdf->Cell(30, 10, 'Hora', 1);
$pdf->Cell(40, 10, 'Tipo', 1);
$pdf->Cell(30, 10, 'Cantidad', 1);
$pdf->Cell(60, 10, 'Descripción', 1);
$pdf->Cell(30, 10, 'Coordinador', 1);
$pdf->Cell(30, 10, 'Auxiliar', 1);
$pdf->Ln();

// Consulta para obtener todos los refrigerios
$stmt = $conn->prepare("SELECT 
                            r.idRefrigerio, 
                            r.fechaRefrigerio, 
                            r.horaRefrigerio, 
                            r.tipoRefrigerio, 
                            r.cantidadRefrigerio, 
                            r.descripcionRefrigerio, 
                            c.nombreCoordinador,
                            a.nombreAuxiliar
                        FROM refrigerio r
                        JOIN coordinador c ON r.idCoordinadorFK = c.idCoordinador
                        JOIN auxiliar a ON r.idAuxiliarFK = a.idAuxiliar");
$stmt->execute();
$refrigerios = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Mostrar datos de los refrigerios
$pdf->SetFont('Arial', '', 12);
foreach ($refrigerios as $refrigerio) {
    $pdf->Cell(30, 10, $refrigerio['idRefrigerio'], 1);
    $pdf->Cell(40, 10, $refrigerio['fechaRefrigerio'], 1);
    $pdf->Cell(30, 10, $refrigerio['horaRefrigerio'], 1);
    $pdf->Cell(40, 10, $refrigerio['tipoRefrigerio'], 1);
    $pdf->Cell(30, 10, $refrigerio['cantidadRefrigerio'], 1);
    $pdf->Cell(60, 10, $refrigerio['descripcionRefrigerio'], 1);
    $pdf->Cell(30, 10, $refrigerio['nombreCoordinador'], 1);
    $pdf->Cell(30, 10, $refrigerio['nombreAuxiliar'], 1);
    $pdf->Ln();
}

// Salida del PDF
$pdf->Output('D', 'refrigerios.pdf'); // Forzar la descarga del archivo PDF
?>
