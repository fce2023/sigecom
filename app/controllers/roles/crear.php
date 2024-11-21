<?php

include ('../../config.php');

$nombre_tipo_usuario = $_POST['nombre-tipo-usuario'];
$estado_tipo_usuario = $_POST['estado-tipo-usuario'] === 'Activo' ? 1 : 0;

try {
    // Verificar si el nombre del tipo de usuario ya existe
    $check_tipo_usuario = $pdo->prepare("SELECT COUNT(*) FROM tipo_usuario WHERE Nombre_tipousuario = :nombre_tipo_usuario");
    $check_tipo_usuario->execute([':nombre_tipo_usuario' => $nombre_tipo_usuario]);
    $tipo_usuario_exists = $check_tipo_usuario->fetchColumn();

    if ($tipo_usuario_exists) {
        // Si el tipo de usuario ya existe, redirigir con un mensaje de error
        session_start();
        $_SESSION['mensaje'] = "El tipo de usuario ya existe, no se puede registrar";
        header('Location: ' . $URL . '/roles/crear.php');
        exit();
    }

    // Insertar nuevo tipo de usuario
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

