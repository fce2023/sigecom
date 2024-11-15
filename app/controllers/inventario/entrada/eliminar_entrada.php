<?php

include '../../../config.php';

header('Content-Type: application/json');

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);
        $id_det_producto_proveedor = $data['Id_det_producto_proveedor'];

        if (empty($id_det_producto_proveedor)) {
            throw new Exception('El ID del detalle de producto proveedor es obligatorio.');
        }

        $stmt = $pdo->prepare("DELETE FROM detalle_producto_proveedor WHERE Id_det_producto_proveedor = ?");
        $stmt->execute([$id_det_producto_proveedor]);

        echo json_encode(['success' => true, 'message' => 'Entrada eliminada correctamente.']);
    } else {
        throw new Exception('Método de solicitud no válido.');
    }
} catch (Exception $e) {
    error_log("Error en eliminar_entrada.php: " . $e->getMessage(), 3, __DIR__ . '/error_log.txt');
    echo json_encode(['success' => false, 'error' => '' . $e->getMessage()]);
}


