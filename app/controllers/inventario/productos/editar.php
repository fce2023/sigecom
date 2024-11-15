<?php

include '../../../config.php';

if (isset($_POST['id_producto'])) {
    $id_producto = $_POST['id_producto'];
    $nombre = $_POST['nombre-reg'];
    $descripcion = $_POST['descripcion-reg'];
    $id_tipo_producto = $_POST['id_tipo_producto'];
    $precio = $_POST['precio-reg'];
    $estado = $_POST['estado-reg'] ? 1 : 0;

    $query = "UPDATE productos SET nombre = :nombre, descripcion = :descripcion, id_tipo_producto = :id_tipo_producto, precio = :precio, estado = :estado WHERE id_producto = :id_producto";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':id_producto', $id_producto, PDO::PARAM_INT);
    $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
    $stmt->bindParam(':descripcion', $descripcion, PDO::PARAM_STR);
    $stmt->bindParam(':id_tipo_producto', $id_tipo_producto, PDO::PARAM_INT);
    $stmt->bindParam(':precio', $precio, PDO::PARAM_STR);
    $stmt->bindParam(':estado', $estado, PDO::PARAM_INT);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        echo json_encode(['success' => true, 'message' => 'Producto actualizado correctamente.']);
    } else {
        echo json_encode(['success' => false, 'error' => 'No se pudo actualizar el producto.']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'No se recibió el id del producto a editar.']);
}

