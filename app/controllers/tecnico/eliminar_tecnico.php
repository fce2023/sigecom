<?php

include '../../config.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $id_tecnico = $data['Id_tecnico'] ?? null;

    if (empty($id_tecnico)) {
        echo json_encode(['success' => false, 'error' => 'El ID del técnico es obligatorio.']);
        exit();
    }

    // Continue with processing the ID_tecnico
    // For example, delete the tecnico from the database
    $query = "DELETE FROM tecnico WHERE ID_tecnico = :id_tecnico";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':id_tecnico', $id_tecnico, PDO::PARAM_INT);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        echo json_encode(['success' => true, 'message' => 'Técnico eliminado correctamente.']);
    } else {
        echo json_encode(['success' => false, 'error' => 'No se encontró el técnico.']);
    }
    exit();
}
