<?php
include('../app/config.php');

// Recibir parámetros de la URL
$id_detalle_tecnico = $_GET['id_detalle_tecnico'] ?? 0;
$id_producto = $_GET['id_producto'] ?? 0;  // Asegúrate de pasar ambos parámetros correctamente desde la URL

// Verificar que el id_detalle_tecnico es válido
if ($id_detalle_tecnico && $id_producto) {
    // Preparar consulta de eliminación basada en id_detalle_tecnico y id_producto
    $consulta_eliminar = $pdo->prepare("DELETE FROM detalle_tecnico_producto 
                                       WHERE id_detall_tecnico_cliente = :id_detalle_tecnico 
                                       AND ID_producto = :id_producto");

    // Ejecutar la consulta con los parámetros recibidos
    $consulta_eliminar->execute([
        ':id_detalle_tecnico' => $id_detalle_tecnico,
        ':id_producto' => $id_producto
    ]);

    // Verificar si se eliminó algún registro
    if ($consulta_eliminar->rowCount() > 0) {
        // Redirigir si la eliminación fue exitosa
        header('Location: mas_detalles.php?id=' . $id_detalle_tecnico);
    } else {
        // Mostrar mensaje de error si no se pudo eliminar
        echo "Error al eliminar el material o no se encontró el registro.";
    }
} else {
    // Mostrar error si no se reciben los parámetros necesarios
    echo "Error al recibir los datos.";
}
?>
