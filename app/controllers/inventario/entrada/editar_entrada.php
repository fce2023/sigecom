<?php

include '../../../config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_detalle_producto_proveedor'])) {
    // Recibir datos del formulario
    $id_detalle_producto_proveedor = $_POST['id_detalle_producto_proveedor'];
    $id_proveedor = $_POST['ID_proveedor'] ?? null;
    $id_producto = $_POST['ID_producto'] ?? null;
    $fecha_abastecimiento = $_POST['Fecha_abastecimiento'] ?? null;
    $cantidad = $_POST['cantidad'] ?? null;
    $observacion = $_POST['Observación'] ?? null;
    $estado = isset($_POST['Estado']) ? (int)$_POST['Estado'] : 0;
    // Validar que los campos obligatorios no estén vacíos
    if (!$id_proveedor || !$id_producto || !$fecha_abastecimiento || !$cantidad || !in_array($estado, [0, 1])) {
        echo json_encode(['success' => false, 'error' => 'Todos los campos obligatorios deben ser completados.']);
        exit;
    }
    try {
        // Preparar la consulta para actualizar
        $query = "UPDATE detalle_producto_proveedor 
                  SET ID_proveedor = :id_proveedor, 
                      ID_producto = :id_producto, 
                      Fecha_abastecimiento = :fecha_abastecimiento, 
                      cantidad = :cantidad, 
                      Observación = :observacion, 
                      Estado = :estado 
                  WHERE Id_det_producto_proveedor = :id_detalle_producto_proveedor";

        $stmt = $pdo->prepare($query);

        // Asignar parámetros
        $stmt->bindParam(':id_detalle_producto_proveedor', $id_detalle_producto_proveedor, PDO::PARAM_INT);
        $stmt->bindParam(':id_proveedor', $id_proveedor, PDO::PARAM_INT);
        $stmt->bindParam(':id_producto', $id_producto, PDO::PARAM_INT);
        $stmt->bindParam(':fecha_abastecimiento', $fecha_abastecimiento);
        $stmt->bindParam(':cantidad', $cantidad, PDO::PARAM_INT);
        $stmt->bindParam(':observacion', $observacion, PDO::PARAM_STR);
        $stmt->bindParam(':estado', $estado, PDO::PARAM_STR);

        // Ejecutar la consulta
        $stmt->execute();

        // Verificar si la fila fue actualizada
        if ($stmt->rowCount() > 0) {
            echo json_encode(['success' => true, 'message' => 'Entrada de producto actualizada correctamente.']);
        } else {
            echo json_encode(['success' => false, 'error' => 'No se realizaron cambios en los datos.']);
        }
    } catch (PDOException $e) {
        // Manejo de errores de base de datos
        echo json_encode(['success' => false, 'error' => 'Error en la base de datos: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'No se recibió el ID de la entrada de producto o el método no es POST.']);
}
