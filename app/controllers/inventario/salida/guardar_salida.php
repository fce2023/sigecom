<?php
include '../../../config.php';

header('Content-Type: application/json; charset=UTF-8');

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id_tecnico = $_POST['tecnico-reg'] ?? null;
        $id_cliente = $_POST['id_cliente-reg'] ?? null;
        $id_producto = $_POST['producto-reg'] ?? null;
        $fecha_retiro = $_POST['fecha-retiro-reg'] ?? null;
        $cantidad = $_POST['cantidad-reg'] ?? null;
        $observacion = $_POST['observacion-reg'] ?? null;
        $id_usuario_sesion = $_POST['id_usuario_sesion'] ?? null;

        if (!$id_tecnico || !$id_cliente || !$id_producto || !$fecha_retiro || !$cantidad || !$id_usuario_sesion) {
            throw new Exception('Todos los campos son obligatorios.');
        }

        $stmt = $pdo->prepare("
            SELECT Id_det_cliente_tecnico 
            FROM detalle_cliente_tecnico 
            WHERE ID_cliente = :id_cliente
            AND Estado = 1
        ");
        $stmt->bindParam(':id_cliente', $id_cliente, PDO::PARAM_INT);
        $stmt->execute();
        $id_det_cliente_tecnico = $stmt->fetchColumn();

        if (!$id_det_cliente_tecnico) {
            throw new Exception('No se encontró un detalle cliente técnico activo para el ID de cliente proporcionado.');
        }

        $query = "INSERT INTO detalle_tecnico_producto (ID_tecnico, ID_producto, ID_usuario, Fecha_retiro, cantidad, Observación, Estado) 
                  VALUES (:id_tecnico, :id_producto, :id_usuario_sesion, :fecha_retiro, :cantidad, :observacion, 1)";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':id_tecnico', $id_tecnico, PDO::PARAM_INT);
        $stmt->bindParam(':id_producto', $id_producto, PDO::PARAM_INT);
        $stmt->bindParam(':id_usuario_sesion', $id_usuario_sesion, PDO::PARAM_INT);
        $stmt->bindParam(':fecha_retiro', $fecha_retiro, PDO::PARAM_STR);
        $stmt->bindParam(':cantidad', $cantidad, PDO::PARAM_INT);
        $stmt->bindParam(':observacion', $observacion, PDO::PARAM_STR);
        $stmt->execute();

        echo json_encode(['success' => true, 'message' => 'Salida guardada correctamente.']);
    } else {
        throw new Exception('Método de solicitud no válido.');
    }
} catch (Exception $e) {
    error_log("Error en guardar_salida.php: " . $e->getMessage(), 3, __DIR__ . '/error_log.txt');
    echo json_encode(['success' => false, 'error' => 'Error interno del servidor. ' . $e->getMessage()]);
}

