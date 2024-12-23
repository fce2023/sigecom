<?php
// conexion.php

$host = "159.112.141.127";  
$usuario = "sigecom_1";  
$contraseña = "4QOWMBYoc2k/-_Tf";  
$base_de_datos = "control";  

try {
 
    $conexion = new PDO("mysql:host=$host;dbname=$base_de_datos", $usuario, $contraseña);
  
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Error al conectar: " . $e->getMessage();
    exit;
}
?>

