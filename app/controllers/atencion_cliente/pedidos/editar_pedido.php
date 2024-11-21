    <?php
include '../../../config.php';
include '../../../../layout/sesion.php';

$response = [
    'success' => false,
    'message' => ''
];

try {
    // para actualizar en la tabla atencion_cliente
    $id_pedido = $_POST['id_pedido-reg'] ?? 0;
    $id_tipo_servicio = trim($_POST['id_tipo_servicio-reg'] ?? '');
    $id_atencion_cliente = !empty($_POST['id_atencion_cliente-reg']) ? trim($_POST['id_atencion_cliente-reg']) : null;
    $codigo_operacion = trim($_POST['codigo-operacion-reg'] ?? '');
    $estado = trim($_POST['estado-reg'] ?? '');


    $id_tecnico = $id_atencion_cliente;




    $query = "UPDATE atencion_cliente SET ID_tipo_servicio = :id_tipo_servicio, Codigo_Operacion = :codigo_operacion, estado = :estado WHERE ID = :id";
    $stmt = $pdo->prepare($query);
    $stmt->execute([
        ':id_tipo_servicio' => $id_tipo_servicio,
        ':codigo_operacion' => $codigo_operacion,
        ':estado' => $estado,
        ':id' => $id_pedido
    ]);

    // Actualiza el ID_tecnico en la tabla detalle_cliente_tecnico con el
    // valor de $id_atencion_cliente para el ID_atencion_cliente especificado
    $query = "UPDATE detalle_cliente_tecnico SET ID_tecnico = :ID_atencion_cliente WHERE ID_atencion_cliente = :id_atencion_cliente";
    $stmt = $pdo->prepare($query);
    $stmt->execute([
        ':ID_atencion_cliente' => $id_atencion_cliente,
        ':id_atencion_cliente' => $id_pedido
    ]);
    // insertar datos en la tabla historial_atencion_cliente
    $id_sesion = $id_usuario_sesion;
    $id_estado_atencion_cliente = $_POST['id_estado_atencion_cliente'] ?? '';
    $fecha = $_POST['fecha-actualizacion'] ?? '';
    $accion = $_POST['accion'] ?? '';
    $detalle = $_POST['detalle'] ?? '';

    $query = "INSERT INTO historial_atencion_cliente (id_usuario, id_atencion_cliente, id_estado_atencion_cliente, fecha, accion, detalle) VALUES (:id_usuario, :id_atencion_cliente, :id_estado_atencion_cliente, :fecha, :accion, :detalle)";
    $stmt = $pdo->prepare($query);
    $stmt->execute([
        ':id_usuario' => $id_sesion,
        ':id_atencion_cliente' => $id_pedido,
        ':id_estado_atencion_cliente' => $id_estado_atencion_cliente,
        ':fecha' => $fecha,
        ':accion' => $accion,
        ':detalle' => $detalle
    ]);

    $response['success'] = true;
    $response['message'] = 'Actualización exitosa y se insertó en el historial correctamente.';
} catch (Exception $e) {
    $response['message'] = 'Error: ' . htmlspecialchars($e->getMessage());
}

header('Content-Type: application/json');
echo json_encode($response);

