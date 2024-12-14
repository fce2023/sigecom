<?php

include ('../app/config.php');

$id = $_POST['id'];

$query = "DELETE FROM historial_atencion_cliente WHERE id = :id";
$stmt = $pdo->prepare($query);
$stmt->execute(['id' => $id]);

header('Location: historial.php');
