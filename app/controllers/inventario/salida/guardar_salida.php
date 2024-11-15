<?php
include '../../../config.php';

header('Content-Type: application/json');

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id_tecnico = $_POST['tecnico-reg'] ?? null;
        $id_producto = $_POST['producto-reg'] ?? null;
        $fecha_retiro = $_POST['fecha-retiro-reg'] ?? null;
        $cantidad = $_POST['cantidad-reg'] ?? null;
        $observacion = $_POST['observacion-reg'] ?? null;

        if (empty($id_tecnico) || empty($id_producto) || empty($fecha_retiro) || empty($cantidad)) {
            throw new Exception('El id del técnico, id del producto, fecha de retiro y cantidad son obligatorios.');
        }

        // Consultar cantidad de entrada
        $stmt = $pdo->prepare("SELECT SUM(cantidad) AS cantidad_entrada FROM detalle_producto_proveedor WHERE ID_producto = :id_producto");
        $stmt->bindParam(':id_producto', $id_producto, PDO::PARAM_INT);
        $stmt->execute();
        $fila = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($fila['cantidad_entrada'] < $cantidad) {
            throw new Exception('La cantidad de salida es mayor a la cantidad de entrada.');
        }

        $query = "INSERT INTO detalle_tecnico_producto (ID_tecnico, ID_producto, Fecha_retiro, cantidad, Observación, Estado) 
                  VALUES (:id_tecnico, :id_producto, :fecha_retiro, :cantidad, :observacion, 1)";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':id_tecnico', $id_tecnico, PDO::PARAM_INT);
        $stmt->bindParam(':id_producto', $id_producto, PDO::PARAM_INT);
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

