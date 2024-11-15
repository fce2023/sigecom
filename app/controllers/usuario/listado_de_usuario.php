<?php

$id_usuario = 1; // Replace with the desired usuario ID

$sql = "SELECT u.ID_usuario, p.Dni, p.Nombre, p.Apellido_paterno, p.Apellido_materno, p.Celular, p.Direccion, p.ID_cargo, p.Estado
        FROM usuario u
        INNER JOIN personal p ON u.id_personal = p.ID_personal
        WHERE u.ID_usuario = :id_usuario";
$stmt = $pdo->prepare($sql);
$stmt->execute([':id_usuario' => $id_usuario]);
$resultado = $stmt->fetch(PDO::FETCH_ASSOC);

$usuario_id = $resultado['ID_usuario'] ?? 0;
$usuario_dni = $resultado['Dni'] ?? '';
$usuario_nombre = $resultado['Nombre'] ?? '';
$usuario_apellido_paterno = $resultado['Apellido_paterno'] ?? '';
$usuario_apellido_materno = $resultado['Apellido_materno'] ?? '';
$usuario_nombre_completo = (isset($resultado['Nombre']) && isset($resultado['Apellido_paterno']) && isset($resultado['Apellido_materno'])) ? $resultado['Nombre'] . ' ' . $resultado['Apellido_paterno'] . ' ' . $resultado['Apellido_materno'] : 'Usuario no encontrado';
$usuario_celular = $resultado['Celular'] ?? '';
$usuario_direccion = $resultado['Direccion'] ?? '';
$usuario_id_cargo = $resultado['ID_cargo'] ?? 0;
$usuario_estado = $resultado['Estado'] ?? '';


