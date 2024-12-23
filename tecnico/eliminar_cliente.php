<?php
include '../app/config.php'; // Incluye el archivo de conexión
include '../layout/sesion.php'; // Incluye el archivo de conexión

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $idDetalleClienteTecnico = (int) $_GET['id'];

    if (empty($idDetalleClienteTecnico)) {
        header('Location: lista_tecnicos_asignados.php');
        exit;
    }

    $stmt = $pdo->prepare("DELETE FROM detalle_cliente_tecnico WHERE Id_det_cliente_tecnico = :id_detalle_cliente_tecnico");
    $stmt->execute([':id_detalle_cliente_tecnico' => $idDetalleClienteTecnico]);

    header('Location: lista_tecnicos_asignados.php?mensaje=Cliente eliminado correctamente.&tipo_mensaje=success');
    exit;
} else {
    header('Location: lista_tecnicos_asignados.php');
    exit;
}

