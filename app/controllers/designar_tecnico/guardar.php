<?php
require_once('../../config.php');
require_once('../../../layout/sesion.php');

try {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $id_cliente = $_POST['id_cliente-reg'] ?? null;
        $id_personal = $_POST['id_personal-reg'] ?? null;
        $id_tipo_servicio = $_POST['tipo_servicio-reg'] ?? null;
        $observacion = $_POST['observacion-reg'] ?? null;
        $fecha_creacion = $_POST['fecha_creacion-reg'] ?? null;
        $estado = (int) ($_POST['estado-reg'] ?? 0);
        $id_usuario = $id_usuario_sesion;

        $estado = 0;
        if (!$id_cliente || !$id_personal || !$id_tipo_servicio || !$fecha_creacion) {
            throw new Exception("Todos los campos requeridos no están presentes.");
        }

        $stmt = $pdo->prepare("SELECT id_tecnico FROM tecnico WHERE id_personal = :id_personal");
        $stmt->execute(['id_personal' => $id_personal]);
        $id_tecnico = $stmt->fetchColumn();

        if (!$id_tecnico) {
            throw new Exception("No se encontró el técnico con el ID personal proporcionado.");
        }

        $stmt = $pdo->prepare("SELECT ID FROM atencion_cliente WHERE id_cliente = :id_cliente");
        $stmt->execute(['id_cliente' => $id_cliente]);
        $id_atencion_cliente = $stmt->fetchColumn();

        if (!$id_atencion_cliente) {
            throw new Exception("No se encontró el ID de la atención al cliente con el ID_cliente proporcionado.");
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

