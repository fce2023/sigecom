<?php
include_once '../../config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_personal = $_POST['id_personal'];
    $nombre = $_POST['nombre-reg'];
    $apellido = $_POST['apellido-reg'];
    $celular = $_POST['celular-reg'];
    $dni = $_POST['dni-reg'];
    $estado = $_POST['estado-reg'];
    $cargo = $_POST['cargo-reg'];

    $query = "UPDATE personal SET Nombre = ?, Apellido = ?, Celular = ?, Dni = ?, Estado = ?, ID_cargo = ? WHERE ID_personal = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$nombre, $apellido, $celular, $dni, $estado, $cargo, $id_personal]);

    if ($stmt) {
        header("Location: " . $URL . "/personal?success=true");
    } else {
        header("Location:. $URL ./personal?error=Error al actualizar");
    }
}
?>
