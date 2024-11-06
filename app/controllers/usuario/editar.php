<?php
include_once '../../config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_usuario = $_POST['id_usuario'];
    $nombre_usuario = $_POST['nombre-reg'];
    $id_tipousuario = $_POST['id_tipousuario-reg'];
    $estado_usuario = $_POST['estado-reg'];

    $query = "UPDATE usuario SET Nombre_usuario = ?, ID_tipousuario = ?, Estado = ? WHERE ID_usuario = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$nombre_usuario, $id_tipousuario, $estado_usuario, $id_usuario]);

    if ($stmt) {
        header("Location: " . $URL . "/usuario?success=true");
    } else {
        header("Location: " . $URL . "/usuario?error=Error al actualizar");
    }
}
?>

