<?php
include_once '../../config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_personal = $_POST['id_personal'];
    $dni = $_POST['dni-reg'];
    $nombre = $_POST['nombre-reg'];
    $apellido_paterno = $_POST['apellido-paterno-reg'];
    $apellido_materno = $_POST['apellido-materno-reg'];
    $celular = $_POST['celular-reg'];
    $direccion = $_POST['direccion-reg'];
    $id_cargo = $_POST['id-cargo-reg'];
    $estado = $_POST['estado-reg'];

    $query = "UPDATE personal SET Dni = ?, Nombre = ?, Apellido_paterno = ?, Apellido_materno = ?, Celular = ?, Direccion = ?, ID_cargo = ?, Estado = ? WHERE ID_personal = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$dni, $nombre, $apellido_paterno, $apellido_materno, $celular, $direccion, $id_cargo, $estado, $id_personal]);

    if ($stmt) {
        header("Location: " . $URL . "/personal?success=true");
    } else {
        header("Location: " . $URL . "/personal?error=Error al actualizar");
    }
}

