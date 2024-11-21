<?php

include '../../config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $id_tecnico = $data['ID_tecnico'] ?? null;

    if (empty($id_tecnico)) {
        echo json_encode(['success' => false, 'error' => 'El ID del técnico es obligatorio.']);
        exit();
    }

    try {
        $pdo->beginTransaction();

        $query = "DELETE FROM tecnico WHERE ID_tecnico = :ID_tecnico";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':ID_tecnico', $id_tecnico, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $pdo->commit();
            echo json_encode(['success' => true, 'message' => 'Técnico eliminado correctamente.']);
        } else {
            $pdo->rollBack();
            echo json_encode(['success' => false, 'error' => 'No se encontró el técnico.']);
        }
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), '23000') !== false) {
            echo json_encode(['success' => false, 'error' => 'Error al eliminar el técnico: El técnico tiene registros asociados en la tabla Detalle Cliente Técnico.']);
        } else {
            echo json_encode(['success' => false, 'error' => 'Error al eliminar el técnico: ' . $e->getMessage()]);
        }
        $pdo->rollBack();
    }
    exit();
}


