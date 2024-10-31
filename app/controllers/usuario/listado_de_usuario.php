<?php

$id_usuario = 1; // Replace with the desired usuario ID

$sql = "SELECT Nombre_usuario FROM usuario WHERE ID_usuario = :id_usuario";
$stmt = $pdo->prepare($sql);
$stmt->execute([':id_usuario' => $id_usuario]);
$resultado = $stmt->fetch(PDO::FETCH_ASSOC);

$usuario_nombre = $resultado['Nombre_usuario'] ?? 'Usuario no encontrado';



