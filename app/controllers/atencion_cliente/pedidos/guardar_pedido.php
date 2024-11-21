<?php
include '../../../config.php';
include '../../../../layout/sesion.php';

header('Content-Type: application/json; charset=utf-8');

try {
    // Verificar método HTTP
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Método de solicitud no válido. Solo se permite POST.');
    }

    // Inicializar variables
    $id_usuario = isset($id_usuario_sesion) ? $id_usuario_sesion : null;
   
    $id_cliente = trim($_POST['id_cliente-reg'] ?? '');
    $id_tipo_servicio = trim($_POST['ID_tipo_servicio-reg'] ?? '');
    $codigo_operacion = trim($_POST['Codigo_Operacion-reg'] ?? '');
    $fecha_creacion = trim($_POST['fecha_creacion-reg'] ?? '');
    $estado = trim($_POST['estado-reg'] ?? '');

    // Validar datos obligatorios
    if (empty($id_usuario)) {
        throw new Exception("La sesión del usuario no está activa o no se ha definido el ID del usuario.");
    }
    if (empty($id_cliente) || empty($id_tipo_servicio) || empty($codigo_operacion) || empty($fecha_creacion) || empty($estado)) {
        throw new Exception("Todos los campos son obligatorios.");
    }

    // Validaciones específicas
    if (!filter_var($id_cliente, FILTER_VALIDATE_INT)) {
        throw new Exception("El ID del cliente no es válido.");
    }
    if (!filter_var($id_tipo_servicio, FILTER_VALIDATE_INT)) {
        throw new Exception("El tipo de servicio no es válido.");
    }
    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $fecha_creacion)) {
        throw new Exception("La fecha de creación no tiene un formato válido (AAAA-MM-DD).");
    }

    // Uso de transacciones para garantizar la integridad
    $pdo->beginTransaction();

    // Primera inserción: atencion_cliente
    $query1 = "INSERT INTO atencion_cliente 
                (ID_usuario, id_cliente, ID_tipo_servicio, Codigo_Operacion, fecha_creacion, estado) 
               VALUES 
                (:id_usuario, :id_cliente, :id_tipo_servicio, :codigo_operacion, :fecha_creacion, :estado)";
    $stmt1 = $pdo->prepare($query1);
    $stmt1->bindValue(':id_usuario', $id_usuario, PDO::PARAM_INT);
    $stmt1->bindValue(':id_cliente', $id_cliente, PDO::PARAM_INT);
    $stmt1->bindValue(':id_tipo_servicio', $id_tipo_servicio, PDO::PARAM_INT);
    $stmt1->bindValue(':codigo_operacion', $codigo_operacion, PDO::PARAM_STR);
    $stmt1->bindValue(':fecha_creacion', $fecha_creacion, PDO::PARAM_STR);
    $stmt1->bindValue(':estado', $estado, PDO::PARAM_INT);
    $stmt1->execute();

    // Obtener el ID de la atención creada
    $id_atencion_cliente = $pdo->lastInsertId();

    // Segunda inserción: historial_atencion_cliente
    $query2 = "INSERT INTO historial_atencion_cliente 
                (id_usuario, id_atencion_cliente, id_estado_atencion_cliente, fecha, accion, detalle) 
               VALUES 
                (:id_usuario, :id_atencion_cliente, :id_estado_atencion_cliente, :fecha, :accion, :detalle)";
    $stmt2 = $pdo->prepare($query2);
    $stmt2->bindValue(':id_usuario', $id_usuario, PDO::PARAM_INT);
    $stmt2->bindValue(':id_atencion_cliente', $id_atencion_cliente, PDO::PARAM_INT);
    $stmt2->bindValue(':id_estado_atencion_cliente', $estado, PDO::PARAM_INT);
    $stmt2->bindValue(':fecha', $fecha_actual = date('Y-m-d H:i:s'), PDO::PARAM_STR);
    $stmt2->bindValue(':accion', $accion = 'Creación', PDO::PARAM_STR);
    $stmt2->bindValue(':detalle', $detalle = 'Creación de la atención al cliente', PDO::PARAM_STR);
    $stmt2->execute();

    // Confirmar transacción
    $pdo->commit();

    echo json_encode(['success' => true, 'message' => 'Atención al cliente guardada correctamente.']);
} catch (Exception $e) {
    // Si ocurre un error, revierte la transacción
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    // Limpiar búfer de salida antes de enviar JSON
    while (ob_get_level()) {
        ob_end_clean();
    }

    // Mostrar error
    error_log("Error: " . $e->getMessage());
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

// Configuración de errores para depuración (desactívala en producción)
ini_set('display_errors', 0);
ini_set('log_errors', 1);
error_reporting(E_ALL);

