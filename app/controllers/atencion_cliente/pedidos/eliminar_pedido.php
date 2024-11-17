<?php

include '../../../config.php'; // Incluye el archivo de conexion
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $id_pedido = htmlspecialchars($data['ID']); // Securely handle the ID

    if (empty($id_pedido)) {
        echo json_encode(['success' => false, 'error' => 'El ID del pedido es obligatorio.']);
        exit;
    }

    try {
        $stmt = $pdo->prepare("DELETE FROM atencion_cliente WHERE ID = :id_pedido");
        $stmt->execute(['id_pedido' => $id_pedido]);

        echo json_encode(['success' => true, 'message' => 'Pedido eliminado correctamente.']);
    } catch (PDOException $e) {
        if ($e->getCode() === '23000') {
            echo json_encode(['success' => false, 'error' => 'No se puede eliminar este pedido porque tiene historial de atencion asociado. 
            <br> Por proteccion al cliente no se puede eliminar el historial
            <br> Editar o poner en modo inactivo']);
        } else {
            error_log("Error en eliminar_pedido.php: " . $e->getMessage(), 3, __DIR__ . '/error_log.txt');
            echo json_encode(['success' => false, 'error' => 'Error interno del servidor. ' . $e->getMessage()]);
        }
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Método de solicitud no válido.']);
}

