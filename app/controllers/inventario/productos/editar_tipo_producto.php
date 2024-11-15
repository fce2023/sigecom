<?php

include '../../../config.php';

if (isset($_POST['id_tipo_producto'])) {
    $id_tipo_producto = $_POST['id_tipo_producto'];
    $nombre = $_POST['nombre-reg'] ?? '';
    $estado = $_POST['estado-reg'] ? 1 : 0;

    $query = "UPDATE tipo_producto SET Nom_producto = :nombre, Estado = :estado WHERE ID_tipo_producto = :id_tipo_producto";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':id_tipo_producto', $id_tipo_producto, PDO::PARAM_INT);
    $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
    $stmt->bindParam(':estado', $estado, PDO::PARAM_INT);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        echo json_encode(['success' => true, 'message' => 'Tipo de producto actualizado correctamente.']);
    } else {
        echo json_encode(['success' => false, 'error' => 'No se pudo actualizar el tipo de producto.']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'No se recibió el id del tipo de producto a editar.']);
}

