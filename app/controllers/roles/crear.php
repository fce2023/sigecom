<?php

include ('../../config.php');

$nombre_tipo_usuario = $_POST['nombre-tipo-usuario'];
$estado_tipo_usuario = $_POST['estado-tipo-usuario'] === 'Activo' ? 1 : 0;

try {
    $sentencia = $pdo->prepare("INSERT INTO tipo_usuario
        (Nombre_tipousuario, Estado) 
        VALUES (:nombre_tipo_usuario, :estado_tipo_usuario)");

    $sentencia->bindParam('nombre_tipo_usuario', $nombre_tipo_usuario);
    $sentencia->bindParam('estado_tipo_usuario', $estado_tipo_usuario);

    $sentencia->execute();

    session_start();
    $_SESSION['mensaje'] = "Se registró el tipo de usuario de la manera correcta";
    header('Location: ' . $URL . '/roles/index.php');
} catch (PDOException $e) {
    session_start();
    $_SESSION['mensaje'] = "Error al registrar el tipo de usuario: " . $e->getMessage();
    header('Location: ' . $URL . '/roles/crear.php');
}


