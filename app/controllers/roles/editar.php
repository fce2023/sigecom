<?php
include_once '../../config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_tipousuario = $_POST['id_tipousuario'];
    $nombre = $_POST['nombre-reg'];
    $estado = $_POST['estado-reg'];

    $query = "UPDATE tipo_usuario SET Nombre_tipousuario = ?, Estado = ? WHERE ID_tipousuario = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$nombre, $estado, $id_tipousuario]);

    if ($stmt) {
        header("Location: " . $URL . "/roles?success=true");
    } else {
        header("Location: " . $URL . "/roles?error=Error al actualizar");
    }
}
?>

