<?php

header('Content-Type: application/json'); // Encabezado para JSON
include '../app/config.php';
include '../layout/sesion.php';

try {
    // Verificar que todos los datos necesarios estén presentes
    if (!isset($_GET['id_detalle_tecnico'], $_POST['id_producto'], $_POST['cantidad'])) {
        throw new Exception('Faltan datos obligatorios.');
    }

    $id_detalle_tecnico = $_GET['id_detalle_tecnico'];
    $id_producto = $_POST['id_producto'];
    $cantidad = $_POST['cantidad'];
    $Observacion = $_POST['Observacion'] ?? ''; // Observación opcional

    // Obtener el ID del técnico
    $stmt = $pdo->prepare("SELECT ID_tecnico FROM detalle_cliente_tecnico WHERE Id_det_cliente_tecnico = :id_detalle_tecnico");
    $stmt->execute(['id_detalle_tecnico' => $id_detalle_tecnico]);
    $id_tecnico = $stmt->fetchColumn();

    if (!$id_tecnico) {
        throw new Exception('No se encontró el técnico para el detalle técnico especificado.');
    }

    // Insertar el nuevo registro en la base de datos
    $stmt = $pdo->prepare("INSERT INTO detalle_tecnico_producto
        (ID_tecnico, ID_producto, ID_usuario, Fecha_retiro, cantidad, Observación, Estado, id_detall_tecnico_cliente)
        VALUES
        (:id_tecnico, :id_producto, :id_usuario, :fecha_retiro, :cantidad, :Observacion, :estado, :id_detalle_tecnico)");

    $stmt->execute([
        'id_tecnico' => $id_tecnico,
        'id_producto' => $id_producto,
        'id_usuario' => $id_usuario_sesion, // Variable de sesión del usuario
        'fecha_retiro' => date('Y-m-d'),
        'cantidad' => $cantidad,
        'Observacion' => $Observacion,
        'estado' => 1,
        'id_detalle_tecnico' => $id_detalle_tecnico
    ]);

    // Respuesta en caso de éxito
    echo json_encode([
        'success' => true,
        'message' => 'Producto agregado con éxito.'
    ]);
} catch (PDOException $e) {
    // Respuesta en caso de error en la base de datos
    echo json_encode([
        'success' => false,
        'message' => 'Error en la base de datos: ' . $e->getMessage()
    ]);
} catch (Exception $e) {
    // Respuesta en caso de error general
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
