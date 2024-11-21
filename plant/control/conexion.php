<?php
// conexion.php

$host = "159.112.141.127";  // Cambia a tu host de base de datos si es necesario
$usuario = "sigecom_1";  // Tu usuario de la base de datos
$contraseña = "4QOWMBYoc2k/-_Tf";  // Tu contraseña de la base de datos
$base_de_datos = "control";  // Nombre de la base de datos

try {
    // Crear conexión
    $conexion = new PDO("mysql:host=$host;dbname=$base_de_datos", $usuario, $contraseña);
    // Establecer el modo de error de PDO
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Error al conectar: " . $e->getMessage();
    exit;
}
?>

