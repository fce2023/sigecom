<?php

include_once '../../config.php';
include_once '../../../layout/sesion.php';



try {
    $id_personal = $_POST['id_personal-reg'] ?? null;
   
    $codigo = $_POST['codigo-reg'] ?? null;
   
    $fecha_creacion = $_POST['fecha_creacion-reg'] ?? null;
    
    
    $estado = (int) ($_POST['estado-reg'] ?? 0);

    $query = "INSERT INTO tecnico (id_personal, codigo, fecha_creacion, estado, ID_usuario) VALUES (:id_personal, :codigo, :fecha_creacion, :estado, :id_usuario)";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':id_personal', $id_personal);
    $stmt->bindParam(':codigo', $codigo);
    $stmt->bindParam(':fecha_creacion', $fecha_creacion);
    $stmt->bindParam(':estado', $estado);
    $stmt->bindParam(':id_usuario', $id_usuario_sesion);
    $stmt->execute();
    echo json_encode(['success' => true, 'message' => 'Tecnico registrado correctamente']);
    exit();
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => 'Error al registrar el técnico: ' . $e->getMessage()]);
    exit();
}

