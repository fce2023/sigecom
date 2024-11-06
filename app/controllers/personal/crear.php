<?php

include ('../../config.php');

$dni = $_POST['dni-reg'];
$nombre = $_POST['nombre-reg'];
$apellido = $_POST['apellido-reg'];
$celular = $_POST['celular-reg'];
$cargo = $_POST['cargo-reg'];
$direccion = $_POST['direccion-reg'];

try {
    $sentencia = $pdo->prepare("INSERT INTO personal
        (Dni, Nombre, Apellido, Celular, ID_cargo, Estado, Direccion) 
        VALUES (:dni, :nombre, :apellido, :celular, :cargo, 1, :direccion)");

    $sentencia->bindParam('dni', $dni);
    $sentencia->bindParam('nombre', $nombre);
    $sentencia->bindParam('apellido', $apellido);
    $sentencia->bindParam('celular', $celular);
    $sentencia->bindParam('cargo', $cargo);
    $sentencia->bindParam('direccion', $direccion);

    $sentencia->execute();

    session_start();
    $_SESSION['mensaje'] = "Se registró al personal de la manera correcta";
    header('Location: ' . $URL . '/personal/index.php');
} catch (PDOException $e) {
    // Handle error
    session_start();
    $_SESSION['mensaje'] = "Error al registrar al personal: " . $e->getMessage();
    header('Location: ' . $URL . '/personal/create.php');
}

