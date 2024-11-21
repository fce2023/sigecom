<?php

include ('../../config.php');

$nombre = $_POST['nombre-reg'];

try {
    $sentencia = $pdo->prepare("SELECT Nom_cargo FROM cargo WHERE Nom_cargo = :nombre");
    $sentencia->bindParam('nombre', $nombre);
    $sentencia->execute();

    if ($sentencia->rowCount() > 0) {
        session_start();
        $_SESSION['mensaje'] = "El cargo ya existe, no se puede registrar";
        header('Location: ' . $URL . '/cargo/crear.php');
        exit;
    }

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

