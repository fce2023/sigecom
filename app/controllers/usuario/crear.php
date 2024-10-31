<?php

include ('../../config.php');

$id_personal = $_POST['id-personal'];
$nombre_usuario = $_POST['nombre-usuario'];
$contrasena = $_POST['contraseña'];
$id_tipousuario = $_POST['id-tipousuario'];
$estado_usuario = ($_POST['estado-usuario'] === 'Activo') ? 1 : 0;

try {
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $consulta = $pdo->prepare("SELECT COUNT(*) FROM personal WHERE ID_personal = :id_personal");
    $consulta->execute([':id_personal' => $id_personal]);
    $personal_exists = $consulta->fetchColumn();

    if ($personal_exists) {
        $sentencia = $pdo->prepare("INSERT INTO usuario 
            (ID_usuario, Nombre_usuario, Contraseña, ID_tipousuario, Estado) 
            VALUES (:id_usuario, :nombre_usuario, :contrasena, :id_tipousuario, :estado_usuario)");

        $id_usuario = $id_personal;

        $sentencia->bindParam(':id_usuario', $id_usuario);
        $sentencia->bindParam(':nombre_usuario', $nombre_usuario);
        $sentencia->bindParam(':contrasena', $contrasena);
        $sentencia->bindParam(':id_tipousuario', $id_tipousuario);
        $sentencia->bindParam(':estado_usuario', $estado_usuario);

        $sentencia->execute();
        header("Location: " . $URL . "/usuario/index.php");
        exit();
    } else {
        echo "El personal seleccionado no existe.";
    }
} catch (PDOException $e) {
    echo "Error al registrar al usuario: " . $e->getMessage() . ".";
}

