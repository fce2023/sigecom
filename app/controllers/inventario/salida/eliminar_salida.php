<?php

include '../../../config.php';

header('Content-Type: application/json');

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);
        $id_det_tecnico_producto = $data['Id_det_tecnico_producto'];

        if (empty($id_det_tecnico_producto)) {
            throw new Exception('El ID del detalle de técnico producto es obligatorio.');
        }

        $stmt = $pdo->prepare("DELETE FROM detalle_tecnico_producto WHERE Id_det_tecnico_producto = ?");
        $stmt->execute([$id_det_tecnico_producto]);

        echo json_encode(['success' => true, 'message' => 'Salida eliminada correctamente.']);
    } else {
        throw new Exception('Método de solicitud no válido.');
    }
} catch (Exception $e) {
    error_log("Error en eliminar_salida.php: " . $e->getMessage(), 3, __DIR__ . '/error_log.txt');
    echo json_encode(['success' => false, 'error' => '' . $e->getMessage()]);
}


