<?php

error_reporting(-1);
ini_set('display_errors', '1');

include('../../config.php');
include('../../../layout/sesion.php');

$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$id_tecnico = $_POST['id_tecnico'] ?? null;
$id_personal = $_POST['id_personal'] ?? null;
$Dni = $_POST['Dni'] ?? '';
$Nombre = $_POST['Nombre'] ?? '';
$Apellido_paterno = $_POST['Apellido_paterno'] ?? '';
$Apellido_materno = $_POST['Apellido_materno'] ?? '';
$Celular = $_POST['Celular'] ?? '';
$Direccion = $_POST['Direccion'] ?? '';
$Estado = (int) $_POST['Estado'] === 1 ? 1 : 0;



if (empty($id_tecnico) || empty($id_personal)) {
    echo json_encode(['success' => false, 'error' => 'El ID del técnico y del personal es obligatorio.']);
    exit();
}

if (!is_numeric($id_tecnico) || !is_numeric($id_personal)) {
    echo json_encode(['success' => false, 'error' => 'Los IDs deben ser numéricos.']);

    exit();
}

try {
    $consulta = $pdo->prepare("UPDATE personal 
        SET Dni = :Dni, Nombre = :Nombre, Apellido_paterno = :Apellido_paterno, 
            Apellido_materno = :Apellido_materno, Celular = :Celular, Direccion = :Direccion 
        WHERE ID_personal = :id_personal");
    $consulta->execute([
        'id_personal' => $id_personal,
        'Dni' => $Dni,
        'Nombre' => $Nombre,
        'Apellido_paterno' => $Apellido_paterno,
        'Apellido_materno' => $Apellido_materno,
        'Celular' => $Celular,
        'Direccion' => $Direccion
    ]);

    $filas_afectadas_personal = $consulta->rowCount();

    $consulta_estado = $pdo->prepare("UPDATE tecnico 
        SET estado = CASE WHEN :estado = 1 THEN 1 ELSE 0 END WHERE ID_tecnico = :id_tecnico");
    $consulta_estado->execute([
        'id_tecnico' => $id_tecnico,
        'estado' => $Estado
    ]);

    $filas_afectadas_tecnico = $consulta_estado->rowCount();

    if ($filas_afectadas_personal > 0 || $filas_afectadas_tecnico > 0) {
        echo json_encode(['success' => true, 'message' => 'Técnico actualizado exitosamente.']);
    } else {
        echo json_encode(['success' => false, 'error' => 'No se realizaron cambios en \'personal\' ni en \'tecnico\'.']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => "Error: {$e->getMessage()}\n Archivo: {$e->getFile()}\n Línea: {$e->getLine()}"]);

}
