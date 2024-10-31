<?php
$sql = "SELECT ID_cargo, Nom_cargo, Estado FROM cargo";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$cargos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
