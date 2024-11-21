<?php

include('../../../app/config.php');

header('Content-Type: application/json');

try {
    $id_tecnico = (int) $_GET['id_tecnico'];

    $query = "SELECT c.ID_cliente, c.Dni, c.Nombre, c.Apellido_paterno, c.Apellido_materno, c.Dirección, c.Celular, c.Correo_Electronico 
              FROM cliente c 
              LEFT JOIN detalle_cliente_tecnico dct ON c.ID_cliente = dct.ID_cliente 
              WHERE dct.ID_tecnico = :id_tecnico 
              AND c.Estado = 1";

    $stmt = $pdo->prepare($query);
    $stmt->execute([':id_tecnico' => $id_tecnico]);

    $clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($clientes);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Error de base de datos: ' . $e->getMessage()]);
} catch (Exception $e) {
    echo json_encode(['error' => 'Error: ' . $e->getMessage()]);
}
