<?php
include '../../../config.php';

header('Content-Type: application/json');

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id_tecnico = $_POST['tecnico-reg'] ?? null;
        $id_cliente = $_POST['id_cliente-reg'] ?? null;
        $id_producto = $_POST['producto-reg'] ?? null;
        $fecha_retiro = $_POST['fecha-retiro-reg'] ?? null;
        $cantidad = $_POST['cantidad-reg'] ?? null;
        $observacion = $_POST['observacion-reg'] ?? null;
        $id_usuario_sesion = $_POST['id_usuario_sesion'] ?? null;
        $tipo = "salida" ?? null;
        // Consulta para calcular el stock
        $stmt = $pdo->prepare("SELECT p.id_producto, p.nombre, tp.Nom_producto,
                    COALESCE((SELECT SUM(dpp.cantidad) FROM detalle_producto_proveedor dpp WHERE dpp.ID_producto = p.id_producto), 0) +
                    COALESCE((SELECT SUM(dtp.cantidad) FROM detalle_tecnico_producto dtp WHERE dtp.ID_producto = p.id_producto AND dtp.tipo_movimiento = 'Entrada'), 0) -
                    COALESCE((SELECT SUM(dtp.cantidad) FROM detalle_tecnico_producto dtp WHERE dtp.ID_producto = p.id_producto AND dtp.tipo_movimiento = 'Salida'), 0) AS stock
                    FROM productos p
                    LEFT JOIN tipo_producto tp ON p.id_tipo_producto = tp.ID_tipo_producto
                    WHERE p.id_producto = :id_producto
                    LIMIT 1");
        $stmt->bindParam(':id_producto', $id_producto, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $stock = (int) $row['stock'];

        if ($stock < $cantidad) {
            echo json_encode(['error' => 'faltastock', 'message' => "No hay suficiente stock del producto seleccionado. Stock actual: $stock"]);
            exit;
        }

        $stmt = $pdo->prepare("
            SELECT d.Id_det_cliente_tecnico
            FROM detalle_cliente_tecnico d
            INNER JOIN atencion_cliente a ON d.ID_atencion_cliente = a.ID
            WHERE a.id_cliente = :id_cliente
            AND d.Estado = 1
        ");
        $stmt->bindParam(':id_cliente', $id_cliente, PDO::PARAM_INT);
        $stmt->execute();
        $id_detalle_cliente_tecnico = $stmt->fetchColumn();

        if (!$id_tecnico || !$id_cliente || !$id_producto || !$fecha_retiro || !$cantidad || !$id_usuario_sesion) {
            throw new Exception('Los campos técnico, cliente, producto, fecha de retiro, cantidad y usuario son obligatorios.');
        }

        if (!$id_detalle_cliente_tecnico) {
            throw new Exception('No se encontró un detalle cliente técnico activo para el ID de cliente proporcionado.');
        }

        $stmt = $pdo->prepare("
            INSERT INTO detalle_tecnico_producto (ID_tecnico, ID_producto, ID_usuario, Fecha_retiro, cantidad, Observación, Estado, Id_detall_tecnico_cliente, tipo_movimiento) 
            VALUES (:id_tecnico, :id_producto, :id_usuario_sesion, :fecha_retiro, :cantidad, :observacion, 1, :id_detalle_cliente_tecnico, :tipo)
        ");
        $stmt->bindParam(':id_tecnico', $id_tecnico, PDO::PARAM_INT);
        $stmt->bindParam(':id_producto', $id_producto, PDO::PARAM_INT);
        $stmt->bindParam(':id_usuario_sesion', $id_usuario_sesion, PDO::PARAM_INT);
        $stmt->bindParam(':fecha_retiro', $fecha_retiro, PDO::PARAM_STR);
        $stmt->bindParam(':cantidad', $cantidad, PDO::PARAM_INT);
        $stmt->bindParam(':observacion', $observacion, PDO::PARAM_STR);
        $stmt->bindParam(':id_detalle_cliente_tecnico', $id_detalle_cliente_tecnico, PDO::PARAM_INT);
        $stmt->bindParam(':tipo', $tipo, PDO::PARAM_STR);
        $stmt->execute();

        echo json_encode(['success' => true, 'message' => 'Salida guardada correctamente.']);
    } else {
        throw new Exception('Método de solicitud no válido.');
    }
} catch (Exception $e) {
    error_log("Error en guardar_salida.php: " . $e->getMessage(), 3, __DIR__ . '/error_log.txt');
    echo json_encode(['error' => $e->getMessage()]);
}

