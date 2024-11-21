<?php
date_default_timezone_set('America/Lima');

// Incluir archivo de conexión
include '../../app/config.php';

// Consultar a la tabla clientes y obtener la cantidad de datos
$query = "SELECT COUNT(*) FROM cliente";
$stmt = $pdo->prepare($query);
$stmt->execute();
$cantidad_clientes = $stmt->fetchColumn();

// Consultar a la tabla atencion_cliente y obtener la cantidad de datos
$query = "SELECT COUNT(*) FROM atencion_cliente";
$stmt = $pdo->prepare($query);
$stmt->execute();


$cantidad_atencion_cliente = $stmt->fetchColumn();

$cantidad_clientes;

$cantidad_atencion_cliente;

include "conexion.php";

try {
    $query = "SELECT fecha FROM cantidad_usuarios WHERE sistema = 'sigecom' ORDER BY fecha DESC LIMIT 1";
    $stmt = $conexion->prepare($query);
    $stmt->execute();
    $last_date = $stmt->fetchColumn();

    if ($last_date && strtotime($last_date) >= strtotime('-1 month')) {
        $query = "UPDATE cantidad_usuarios SET cantidad_clientes = ?, cantidad_movimientos = ?, fecha = NOW() WHERE sistema = 'sigecom' AND fecha = ?";
        $stmt = $conexion->prepare($query);
        $stmt->execute([$cantidad_clientes, $cantidad_atencion_cliente, $last_date]);
    } else {
        $query = "INSERT INTO cantidad_usuarios (sistema, cantidad_clientes, cantidad_movimientos, fecha) VALUES ('sigecom', ?, ?, NOW())";
        $stmt = $conexion->prepare($query);
        $stmt->execute([$cantidad_clientes, $cantidad_atencion_cliente]);
    }
} catch (PDOException $e) {
    // Log the error to a file
    error_log("Error while inserting or updating in the table cantidad_usuarios: " . $e->getMessage());
}
?>




