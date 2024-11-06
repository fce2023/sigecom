<?php
include_once '../../config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_cargo = $_POST['id_cargo'];
    $nombre_cargo = $_POST['nombre-reg'];
    $estado_cargo = $_POST['estado-reg'];

    $query = "UPDATE cargo SET Nom_cargo = ?, Estado = ? WHERE ID_cargo = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$nombre_cargo, $estado_cargo, $id_cargo]);

    if ($stmt) {
        header("Location: " . $URL . "/cargo?success=true");
    } else {
        header("Location: " . $URL . "/cargo?error=Error al actualizar");
    }
}
?>

