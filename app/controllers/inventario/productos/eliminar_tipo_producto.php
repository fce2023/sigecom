<?php

include '../../../config.php';

header('Content-Type: application/json');

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);
        $id_tipo_producto = $data['id_tipo_producto'];

        if (empty($id_tipo_producto)) {
            throw new Exception('El ID del tipo de producto es obligatorio.');
        }

        $stmt = $pdo->prepare("SELECT COUNT(*) AS cantidad FROM productos WHERE id_tipo_producto = ?");
        $stmt->execute([$id_tipo_producto]);
        $fila = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($fila['cantidad'] > 0) {
            throw new Exception('No se puede eliminar el tipo de producto seleccionado, ya que tiene relación con productos. Modifique los productos primero.');
        }

        $stmt = $pdo->prepare("DELETE FROM tipo_producto WHERE ID_tipo_producto = ?");
        $stmt->execute([$id_tipo_producto]);

        echo json_encode(['success' => true, 'message' => 'Tipo de producto eliminado correctamente.']);
    } else {
        throw new Exception('Método de solicitud no válido.');
    }
} catch (Exception $e) {
    error_log("Error en eliminar_tipo_producto.php: " . $e->getMessage(), 3, __DIR__ . '/error_log.txt');
    echo json_encode(['success' => false, 'error' => '' . $e->getMessage()]);
}

