<?php 
$cn = mysqli_connect("localhost", "root", "", "sigecom"); // Asegúrate de que los parámetros son correctos

// Verificar si hay errores de conexión
if (!$cn) {
    die("Error al conectar a la base de datos: " . mysqli_connect_error()); // Cambié para usar mysqli_connect_error()
}

// Configurar el conjunto de caracteres
mysqli_set_charset($cn, "utf8");

