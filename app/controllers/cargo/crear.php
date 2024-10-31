<?php

include ('../../config.php');

$nombre = $_POST['nombre-reg'];

try {
    $sentencia = $pdo->prepare("INSERT INTO cargo
        (Nom_cargo, Estado) 
        VALUES (:nombre, 1)");

    $sentencia->bindParam('nombre', $nombre);

    $sentencia->execute();

    session_start();
    $_SESSION['mensaje'] = "Se registró al cargo de la manera correcta";
    header('Location: ' . $URL . '/cargo/index.php');
} catch (PDOException $e) {
    // Handle error
    session_start();
    $_SESSION['mensaje'] = "Error al registrar al cargo: " . $e->getMessage();
    header('Location: ' . $URL . '/cargo/crear.php');
}
