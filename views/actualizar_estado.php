<?php
session_start();
require_once '../models/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verificar que el usuario esté autenticado y sea un auxiliar
    if (!isset($_SESSION['rolUsuario']) || $_SESSION['rolUsuario'] !== 'auxiliar') {
        http_response_code(403); // Prohibido
        echo json_encode(['message' => 'Acceso denegado']);
        exit();
    }

    // Conectar a la base de datos
    $database = new Database();
    $db = $database->getConnection();

    // Obtener el ID de la asignación desde el cuerpo de la solicitud
    $data = json_decode(file_get_contents("php://input"));

    if (isset($data->id)) {
        $idAsignacion = $data->id;

        // Actualizar el estado en la base de datos
        $query = "UPDATE AsigRefrigerioCurso SET estado = 1 WHERE idAsigRefCur = :id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':id', $idAsignacion);

        if ($stmt->execute()) {
            echo json_encode(['message' => 'Estado actualizado correctamente']);
        } else {
            http_response_code(500); // Error interno del servidor
            echo json_encode(['message' => 'Error al actualizar el estado']);
        }
    } else {
        http_response_code(400); // Solicitud incorrecta
        echo json_encode(['message' => 'ID de asignación no proporcionado']);
    }
} else {
    http_response_code(405); // Método no permitido
    echo json_encode(['message' => 'Método no permitido']);
}
