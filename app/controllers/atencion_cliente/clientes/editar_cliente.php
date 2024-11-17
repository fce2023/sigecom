<?php
include '../../../config.php';
include '../../../../layout/sesion.php';

header('Content-Type: application/json');

$response = ['success' => false, 'message' => ''];

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Método de solicitud no válido. Solo se permite POST.');
    }

    $id_cliente = trim($_POST['id_cliente'] ?? '');
    $dni = trim($_POST['dni-reg'] ?? '');
    $nombre = trim($_POST['nombre-reg'] ?? '');
    $apellido_paterno = trim($_POST['apellido-paterno-reg'] ?? '');
    $apellido_materno = trim($_POST['apellido-materno-reg'] ?? '');
    $direccion = trim($_POST['direccion-reg'] ?? '');
    $celular = trim($_POST['celular-reg'] ?? '');
    $correo_electronico = trim($_POST['correo-electronico-reg'] ?? '');
    $estado = trim($_POST['estado-reg'] ?? '');

    $query = "UPDATE cliente SET Dni = :dni, Nombre = :nombre, Apellido_paterno = :apellido_paterno, Apellido_materno = :apellido_materno, Dirección = :direccion, Celular = :celular, Correo_Electronico = :correo_electronico, Estado = :estado WHERE ID_cliente = :id_cliente";

    $stmt = $pdo->prepare($query);
    $stmt->execute([
        ':id_cliente' => $id_cliente,
        ':dni' => $dni,
        ':nombre' => $nombre,
        ':apellido_paterno' => $apellido_paterno,
        ':apellido_materno' => $apellido_materno,
        ':direccion' => $direccion,
        ':celular' => $celular,
        ':correo_electronico' => $correo_electronico,
        ':estado' => $estado,
    ]);

    $response['success'] = true;
    $response['message'] = 'Los datos se han actualizado correctamente.';
} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    error_log("Error: " . $e->getMessage());
    $response['message'] = 'Error: ' . $e->getMessage();
}

echo json_encode($response);

ini_set('display_errors', 0);
ini_set('log_errors', 1);
error_reporting(E_ALL);

$pdo = null;
?>

