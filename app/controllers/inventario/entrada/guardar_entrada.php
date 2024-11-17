<?php
include '../../../config.php';



header('Content-Type: application/json');

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id_proveedor = $_POST['proveedor-reg'] ?? null;
        $id_producto = $_POST['producto-reg'] ?? null;
        $fecha_abastecimiento = $_POST['fecha-abastecimiento-reg'] ?? null;
        $cantidad = $_POST['cantidad-reg'] ?? null;
        $observacion = $_POST['observacion-reg'] ?? null;
        $id_usuario_sesion = $_POST['id_usuario_sesion'] ?? null;

        if (empty($id_proveedor) || empty($id_producto) || empty($fecha_abastecimiento) || empty($cantidad)) {
            throw new Exception('El id del proveedor, id del producto, fecha de abastecimiento y cantidad son obligatorios.');
        }

        $query = "INSERT INTO detalle_producto_proveedor (ID_proveedor, ID_producto, ID_usuario, Fecha_abastecimiento, cantidad, Observación, Estado) 
                  VALUES (:id_proveedor, :id_producto, :id_usuario, :fecha_abastecimiento, :cantidad, :observacion, 'Activo')";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':id_proveedor', $id_proveedor, PDO::PARAM_INT);
        $stmt->bindParam(':id_producto', $id_producto, PDO::PARAM_INT);
        $stmt->bindParam(':id_usuario', $id_usuario_sesion, PDO::PARAM_INT);
        $stmt->bindParam(':fecha_abastecimiento', $fecha_abastecimiento, PDO::PARAM_STR);
        $stmt->bindParam(':cantidad', $cantidad, PDO::PARAM_INT);
        $stmt->bindParam(':observacion', $observacion, PDO::PARAM_STR);
        $stmt->execute();
        echo json_encode(['success' => true, 'message' => 'Entrada guardada correctamente.']);
    } else {
        throw new Exception('Método de solicitud no válido.');
    }
} catch (Exception $e) {
    
    error_log("Error en guardar.php: " . $e->getMessage(), 3, __DIR__ . '/error_log.txt');
    echo json_encode(['success' => false, 'error' => 'Error interno del servidor. ' . $e->getMessage()]);
}



