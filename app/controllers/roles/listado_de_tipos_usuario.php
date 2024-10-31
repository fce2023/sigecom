<?php

$sql = "SELECT ID_tipousuario, Nombre_tipousuario, Estado FROM tipo_usuario";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$tipos_usuario = $stmt->fetchAll(PDO::FETCH_ASSOC);



