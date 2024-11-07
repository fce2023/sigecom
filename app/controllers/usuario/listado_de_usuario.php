<?php

$id_usuario = 1; // Replace with the desired usuario ID

$sql = "SELECT u.ID_usuario, u.id_personal, u.Nombre_usuario, u.Contraseña, u.ID_tipousuario, u.Estado, u.Correo
        FROM usuario u
        WHERE u.ID_usuario = :id_usuario";
$stmt = $pdo->prepare($sql);
$stmt->execute([':id_usuario' => $id_usuario]);
$resultado = $stmt->fetch(PDO::FETCH_ASSOC);

$usuario_id = $resultado['ID_usuario'] ?? 0;
$usuario_id_personal = $resultado['id_personal'] ?? 0;
$usuario_nombre = $resultado['Nombre_usuario'] ?? 'Usuario no encontrado';
$usuario_contraseña = $resultado['Contraseña'] ?? '';
$usuario_id_tipousuario = $resultado['ID_tipousuario'] ?? 0;
$usuario_estado = $resultado['Estado'] ?? '';
$usuario_correo = $resultado['Correo'] ?? '';

