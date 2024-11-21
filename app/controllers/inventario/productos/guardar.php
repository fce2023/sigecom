<?php
include '../../../config.php';

header('Content-Type: application/json');

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nombre = trim($_POST['nombre-reg'] ?? '');
        $descripcion = trim($_POST['descripcion-reg'] ?? '');
        $id_tipo_producto = $_POST['tipo-reg'] ?? null;
        $precio = trim($_POST['precio-reg'] ?? '');

        if (empty($nombre) || empty($precio)) {
            throw new Exception('El nombre y el precio son obligatorios.');
        }

        $stmt = $pdo->prepare("SELECT COUNT(*) FROM productos WHERE nombre = :nombre");
        $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
        $stmt->execute();
        if ($stmt->fetchColumn() > 0) {
            throw new Exception('El producto ya existe.');
        }

        $query = "INSERT INTO productos (nombre, descripcion, id_tipo_producto, precio, fecha_registro, estado) 
                  VALUES (:nombre, :descripcion, :id_tipo_producto, :precio, CURRENT_TIMESTAMP, 1)";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
        $stmt->bindParam(':descripcion', $descripcion, PDO::PARAM_STR);
        $stmt->bindParam(':id_tipo_producto', $id_tipo_producto, PDO::PARAM_INT);
        $stmt->bindParam(':precio', $precio, PDO::PARAM_STR);
        $stmt->execute();

        echo json_encode(['success' => true, 'message' => 'Producto guardado correctamente.']);
    } else {
        throw new Exception('Método de solicitud no válido.');
    }
} catch (Exception $e) {
    error_log("Error en guardar.php: " . $e->getMessage(), 3, __DIR__ . '/error_log.txt');
    echo json_encode(['success' => false, 'error' => 'Error interno del servidor. ' . $e->getMessage()]);
}
