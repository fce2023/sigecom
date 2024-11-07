<?php

include ('../../config.php');

try {
    // Se obtienen los datos del formulario
    $id_personal = $_POST['id-personal'];
    $nombre_usuario = $_POST['nombre-usuario'];
    $contrasena = $_POST['contraseña'];
    $id_tipousuario = $_POST['id-tipousuario'];
    $estado_usuario = ($_POST['estado-usuario'] === 'Activo') ? 1 : 0;

    // Verificar si el ID de personal existe en la tabla `personal`
    $consulta = $pdo->prepare("SELECT COUNT(*) FROM personal WHERE ID_personal = :id_personal");
    $consulta->execute([':id_personal' => $id_personal]);
    $personal_exists = $consulta->fetchColumn();

    if ($personal_exists) {
        // Verificar si el nombre de usuario ya existe
        $check_user = $pdo->prepare("SELECT COUNT(*) FROM usuario WHERE Nombre_usuario = :nombre_usuario");
        $check_user->execute([':nombre_usuario' => $nombre_usuario]);
        $user_exists = $check_user->fetchColumn();

        if ($user_exists) {
            // Si el nombre de usuario ya existe, redirigir con un mensaje de error
            header("Location: " . $URL . "usuario/crear.php?error=El nombre de usuario ya existe&nombre_usuario=" . urlencode($nombre_usuario));
            exit();
        } else {
            // Si el usuario no existe, insertar en la tabla `usuario`
            $sentencia = $pdo->prepare("INSERT INTO usuario 
                (id_personal, Nombre_usuario, Contraseña, ID_tipousuario, Estado) 
                VALUES (:id_personal, :nombre_usuario, :contrasena, :id_tipousuario, :estado_usuario)");

            $sentencia->execute([
                ':id_personal' => $id_personal,
                ':nombre_usuario' => $nombre_usuario,
                ':contrasena' => $contrasena,
                ':id_tipousuario' => $id_tipousuario,
                ':estado_usuario' => $estado_usuario
            ]);

            // Redirigir a la página de usuarios con mensaje de éxito
            header("Location: " . $URL . "/usuario/index.php?success=Usuario registrado correctamente");
            exit();
        }
    } else {
        // Si el personal no existe, redirigir con un mensaje de error
        header("Location: " . $URL . "/usuario/crear.php?error=Seleccione un Personal");
        exit();
    }
} catch (PDOException $e) {
    // Manejo de errores de conexión o consulta
    header("Location: " . $URL . "/usuario/crear.php?error=Error al registrar el usuario: " . urlencode($e->getMessage()));
    exit();
}
