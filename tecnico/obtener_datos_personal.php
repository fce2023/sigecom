<?php

include('../app/config.php');

header('Content-Type: application/json');

try {
    $query = "SELECT ID_personal, Dni, Nombre, Apellido_paterno, Apellido_materno, Celular, Direccion, ID_cargo, Estado FROM personal";
    $stmt = $pdo->query($query);
    $personalData = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'status' => 'success',
        'data' => $personalData
    ]);
} catch (PDOException $e) {
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}

?>
