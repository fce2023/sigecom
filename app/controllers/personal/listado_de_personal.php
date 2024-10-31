<?php

$id_cargo = 1; // Replace with the desired cargo ID

$sql = "SELECT Nom_cargo FROM cargo WHERE ID_cargo = :id_cargo";
$stmt = $pdo->prepare($sql);
$stmt->execute([':id_cargo' => $id_cargo]);
$resultado = $stmt->fetch(PDO::FETCH_ASSOC);

$cargo_nombre = $resultado['Nom_cargo'] ?? 'Cargo no encontrado';


