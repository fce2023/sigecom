<?php

include ('../../config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $consulta = $pdo->prepare("INSERT INTO personal (Dni, Nombre, Apellido_paterno, Apellido_materno, Celular, Direccion, ID_cargo, Estado) VALUES (:dni, :nombres, :apellido_paterno, :apellido_materno, :celular, :direccion, :id_cargo, :estado)");
    $consulta->execute(array(
        ':dni' => $_POST['dni-reg'],
        ':nombres' => $_POST['nombre-reg'],
        ':apellido_paterno' => $_POST['apellido-paterno-reg'],
        ':apellido_materno' => $_POST['apellido_materno-reg'],
        ':celular' => $_POST['celular-reg'],
        ':direccion' => $_POST['direccion-reg'],
        ':id_cargo' => $_POST['cargo-reg'],
        ':estado' => !empty($_POST['estado-reg']) ? ($_POST['estado-reg'] == "Activo" ? 1 : 0) : 1
    ));

    if ($consulta) {
        $_SESSION['mensaje'] = "Se ha registrado correctamente el personal";
        header('Location: '.$URL.'/personal/');
        exit;
    } else {
        $_SESSION['errores'] = array("Error al registrar el personal");
        header('Location: ../crear.php');
        exit;
    }
} else {
    session_start();
    if (isset($_SESSION['mensaje'])) {
        echo "<p class='alert alert-success'>".$_SESSION['mensaje']."</p>";
        unset($_SESSION['mensaje']);
    }

    if (isset($_SESSION['errores'])) {
        echo "<p class='alert alert-danger'>";
        foreach ($_SESSION['errores'] as $error) {
            echo $error . "<br>";
        }
        echo "</p>";
        unset($_SESSION['errores']);
    }
}

