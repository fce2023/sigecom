    <?php
    include '../../config.php';
    include '../../../layout/sesion.php';

    $response = [
        'success' => false,
        'message' => ''
    ];

    try {
        // para actualizar en la tabla atencion_cliente
        $id_pedido = $_POST['id_pedido-reg'] ?? 0;
        "id_pedido: $id_pedido <br>";
        $id_tipo_servicio = trim($_POST['id_tipo_servicio-reg'] ?? '');
        "id_tipo_servicio: $id_tipo_servicio <br>";

        // esta variable puede recibir dos valores:
        // 1. 'id_atencion_cliente-reg' que es el ID de la atencion cliente que se esta editando
        // 2. 'id_tecnico-reg' que es el ID del tecnico que se esta asignando a la atencion cliente
        // dependiendo del caso, se toma el valor de una u otra variable
        $id_atencion_cliente = (filter_var($_POST['id_atencion_cliente-reg'] ?? '', FILTER_VALIDATE_INT)) ? trim($_POST['id_atencion_cliente-reg']) : (isset($_POST['id_tecnico-reg']) ? trim($_POST['id_tecnico-reg']) : null);
        "id_atencion_cliente: $id_atencion_cliente <br>";
        $codigo_operacion = trim($_POST['codigo-operacion-reg'] ?? '');
        "codigo_operacion: $codigo_operacion <br>";
        $estado = trim($_POST['estado-reg'] ?? '');
        "estado: $estado <br>";
        $id_tecnico = $id_atencion_cliente ?? $_POST['id_tecnico-reg'] ?? null;
        "id_tecnico: $id_tecnico <br>";


        $query = "UPDATE atencion_cliente SET ID_tipo_servicio = :id_tipo_servicio, Codigo_Operacion = :codigo_operacion, estado = :estado WHERE ID = :id";
        $stmt = $pdo->prepare($query);
        $stmt->execute([
            ':id_tipo_servicio' => $id_tipo_servicio,
            ':codigo_operacion' => $codigo_operacion,
            ':estado' => $estado,
            ':id' => $id_pedido
        ]);



        // Actualiza el ID_tecnico y el ID_tipo_servicio en la tabla detalle_cliente_tecnico
        // con el valor de $id_atencion_cliente y $id_tipo_servicio respectivamente
        // para el ID_atencion_cliente especificado
        if ($id_tecnico) {
            $query = "UPDATE detalle_cliente_tecnico SET ID_tecnico = :ID_tecnico, ID_tipo_servicio = :id_tipo_servicio, Estado = :estado WHERE ID_atencion_cliente = :id_atencion_cliente";
            $stmt = $pdo->prepare($query);
            $stmt->execute([
                ':ID_tecnico' => $id_tecnico,
                ':id_tipo_servicio' => $id_tipo_servicio,
                ':estado' => $estado,
                ':id_atencion_cliente' => $id_pedido
            ]);
        }
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
