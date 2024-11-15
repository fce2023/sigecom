<?php
include_once '../../config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_proveedor = $_POST['id_proveedor'];
    $nombre_proveedor = $_POST['nombre-reg'];
    $direccion_proveedor = $_POST['direccion-reg'];
    $telefono_proveedor = $_POST['telefono-reg'];
    $estado_proveedor = $_POST['estado-reg'] ? 1 : 0;

    $query = "UPDATE proveedor SET Nombre = ?, Dirección = ?, Teléfono = ?, Estado = ? WHERE ID_proveedor = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$nombre_proveedor, $direccion_proveedor, $telefono_proveedor, $estado_proveedor, $id_proveedor]);

    if ($stmt) {
        header("Location: " . $URL . "/proveedor?success=datos actualizados");
    } else {
        header("Location: " . $URL . "/proveedor?error=Error al actualizar");
    }
}
?>

