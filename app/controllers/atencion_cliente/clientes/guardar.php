<?php
include '../../../config.php';

header('Content-Type: application/json');

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $dni = trim($_POST['dni-reg'] ?? '');
        $nombre = trim($_POST['nombre-reg'] ?? '');
        $apellidoPaterno = trim($_POST['apellido-paterno-reg'] ?? '');
        $apellidoMaterno = trim($_POST['apellido-materno-reg'] ?? '');
        $direccion = trim($_POST['direccion-reg'] ?? '');
        $celular = trim($_POST['celular-reg'] ?? '');
        $correoElectronico = trim($_POST['correo-electronico-reg'] ?? '');
        $estado = trim($_POST['estado-reg'] ?? '');

        if (empty($dni) || empty($nombre) || empty($apellidoPaterno) || empty($apellidoMaterno) || empty($direccion) || empty($celular)) {
            throw new Exception('El DNI, nombre, apellido paterno, apellido materno, dirección y celular son obligatorios.');
        }

        $query = "INSERT INTO cliente (Dni, Nombre, Apellido_paterno, Apellido_materno, Dirección, Celular, Correo_Electronico, Estado) 
                  VALUES (:dni, :nombre, :apellidoPaterno, :apellidoMaterno, :direccion, :celular, :correoElectronico, :estado)";

        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':dni', $dni, PDO::PARAM_STR);
        $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
        $stmt->bindParam(':apellidoPaterno', $apellidoPaterno, PDO::PARAM_STR);
        $stmt->bindParam(':apellidoMaterno', $apellidoMaterno, PDO::PARAM_STR);
        $stmt->bindParam(':direccion', $direccion, PDO::PARAM_STR);
        $stmt->bindParam(':celular', $celular, PDO::PARAM_STR);
        $stmt->bindParam(':correoElectronico', $correoElectronico, PDO::PARAM_STR);
        $stmt->bindParam(':estado', $estado, PDO::PARAM_INT);
        $stmt->execute();

        echo json_encode(['success' => true, 'message' => 'Cliente guardado correctamente.']);
    } else {
        throw new Exception('Método de solicitud no válido.');
    }
} catch (Exception $e) {
    error_log("Error en guardar.php: " . $e->getMessage(), 3, __DIR__ . '/error_log.txt');
    echo json_encode(['success' => false, 'error' => 'Error interno del servidor. ' . $e->getMessage()]);
}


