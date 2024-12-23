<?php

include '../../../config.php';


 if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_detalle_tecnico_producto'])) {
    // Recibir datos del formulario
    $id_detalle_tecnico_producto = $_POST['id_detalle_tecnico_producto'];
    $id_tecnico = $_POST['ID_tecnico'] ?? null;
    $id_producto = $_POST['ID_producto'] ?? null;
    $fecha_retiro = $_POST['Fecha_retiro'] ?? null;
    $cantidad = $_POST['cantidad'] ?? null;
    $observacion = $_POST['Observación'] ?? null;
    $estado = $_POST['Estado'] ?? null;

   
    // Validar que los campos obligatorios no estén vacíos
  

    try {
        // Preparar la consulta para actualizar
        $query = "UPDATE detalle_tecnico_producto 
                  SET ID_tecnico = :id_tecnico, 
                      ID_producto = :id_producto, 
                      Fecha_retiro = :fecha_retiro, 
                      cantidad = :cantidad, 
                      Observación = :observacion, 
                      Estado = CASE WHEN :estado = 1 THEN 1 ELSE 0 END 
                  WHERE Id_det_tecnico_producto = :id_detalle_tecnico_producto";

        $stmt = $pdo->prepare($query);
        // Asignar parámetros
        $stmt->bindParam(':id_detalle_tecnico_producto', $id_detalle_tecnico_producto, PDO::PARAM_INT);
        $stmt->bindParam(':id_tecnico', $id_tecnico, PDO::PARAM_INT);
        $stmt->bindParam(':id_producto', $id_producto, PDO::PARAM_INT);
        $stmt->bindParam(':fecha_retiro', $fecha_retiro);
        $stmt->bindParam(':cantidad', $cantidad, PDO::PARAM_INT);
        $stmt->bindParam(':observacion', $observacion, PDO::PARAM_STR);
        $stmt->bindParam(':estado', $estado, PDO::PARAM_STR);

        // Ejecutar la consulta
        $stmt->execute();

        // Verificar si la fila fue actualizada
        if ($stmt->rowCount() > 0) {
            echo json_encode(['success' => true, 'message' => 'Salida de producto actualizada correctamente.']);
        } else {
            echo json_encode(['success' => false, 'error' => 'No se realizaron cambios en los datos.']);
        }
    } catch (PDOException $e) {
        // Manejo de errores de base de datos
        echo json_encode(['success' => false, 'error' => 'Error en la base de datos: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'No se recibió el ID de la salida de producto o el método no es POST.']);
}

