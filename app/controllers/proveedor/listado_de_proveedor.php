<?php

$id_proveedor = 1; // Replace with the desired proveedor ID

$sql = "SELECT p.ID_proveedor, p.Nombre, p.Dirección, p.Teléfono, p.Estado
        FROM proveedor p
        WHERE p.ID_proveedor = :id_proveedor";
$stmt = $pdo->prepare($sql);
$stmt->execute([':id_proveedor' => $id_proveedor]);
$resultado = $stmt->fetch(PDO::FETCH_ASSOC);

$proveedor_id = $resultado['ID_proveedor'] ?? 0;
$proveedor_nombre = $resultado['Nombre'] ?? 'Proveedor no encontrado';
$proveedor_dirección = $resultado['Dirección'] ?? '';
$proveedor_teléfono = $resultado['Teléfono'] ?? '';
$proveedor_estado = $resultado['Estado'] ?? '';
