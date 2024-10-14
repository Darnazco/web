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
$pdf->Cell(0, 10, 'Listado de Usuarios', 0, 1, 'C');

// Encabezados de la tabla
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(30, 10, 'ID', 1);
$pdf->Cell(40, 10, 'Nombre', 1);
$pdf->Cell(40, 10, 'Apellido', 1);
$pdf->Cell(60, 10, 'Correo', 1);
$pdf->Cell(30, 10, 'Estado', 1);
$pdf->Ln();

// Consulta para obtener todos los usuarios
$stmt = $conn->prepare("SELECT idUsuario, nombreUsuario, apellidoUsuario, correoUsuario, estadoUsuario FROM usuario");
$stmt->execute();
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Mostrar datos de los usuarios
$pdf->SetFont('Arial', '', 12);
foreach ($usuarios as $usuario) {
    $pdf->Cell(30, 10, $usuario['idUsuario'], 1);
    $pdf->Cell(40, 10, $usuario['nombreUsuario'], 1);
    $pdf->Cell(40, 10, $usuario['apellidoUsuario'], 1);
    $pdf->Cell(60, 10, $usuario['correoUsuario'], 1);
    $pdf->Cell(30, 10, $usuario['estadoUsuario'], 1);
    $pdf->Ln();
}

// Salida del PDF
$pdf->Output('D', 'usuarios.pdf'); // Forzar la descarga del archivo PDF
?>
