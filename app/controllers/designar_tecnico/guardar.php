<?php
require_once('../../config.php');
require_once('../../../layout/sesion.php');

try {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $id_atencion_cliente = $_POST['id_atencion_cliente-reg'] ?? null;
        $id_tecnico = $_POST['id_tecnico-reg'] ?? null;
        $id_tipo_servicio = $_POST['tipo_servicio-reg'] ?? null;
        $observacion = $_POST['observacion-reg'] ?? null;
        $fecha_creacion = $_POST['fecha_creacion-reg'] ?? null;
        $estado = (int) ($_POST['estado-reg'] ?? 0);
        $id_usuario = $id_usuario_sesion;

        if (!$id_atencion_cliente || !$id_tecnico || !$id_tipo_servicio || !$fecha_creacion) {
            throw new Exception("Todos los campos requeridos no están presentes.");
        }

        $stmt = $pdo->prepare("
            INSERT INTO detalle_cliente_tecnico 
            (ID_tecnico, ID_tipo_servicio, ID_usuario, Fecha_atencion, Observacion, Estado, ID_atencion_cliente) 
            VALUES 
            (:ID_tecnico, :ID_tipo_servicio, :ID_usuario, :Fecha_atencion, :Observacion, :Estado, :ID_atencion_cliente)
        ");
        $stmt->execute([
            'ID_tecnico' => $id_tecnico,
            'ID_tipo_servicio' => $id_tipo_servicio,
            'ID_usuario' => $id_usuario,
            'Fecha_atencion' => $fecha_creacion,
            'Observacion' => $observacion,
            'Estado' => $estado,
            'ID_atencion_cliente' => $id_atencion_cliente
        ]);

        echo json_encode(['success' => true, 'message' => 'Datos guardados correctamente.']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => 'Error de base de datos: ' . $e->getMessage()]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => 'Error: ' . $e->getMessage()]);
}

