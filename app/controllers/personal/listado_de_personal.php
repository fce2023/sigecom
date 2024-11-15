<?php

$sql = "SELECT p.ID_personal, p.Dni, p.Nombre, p.Apellido_paterno, p.Apellido_materno, p.Celular, p.Direccion, c.ID_cargo, c.Nom_cargo, p.Estado
        FROM personal p
        INNER JOIN cargo c ON p.ID_cargo = c.ID_cargo
        WHERE p.Estado = 'Activo'
        ORDER BY p.Nombre";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$personal = $stmt->fetchAll(PDO::FETCH_ASSOC);


