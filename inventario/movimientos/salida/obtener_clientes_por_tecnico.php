<?php

include('../../../app/config.php');

header('Content-Type: application/json');

try {
    $id_tecnico = (int) $_GET['id_tecnico'];

    $query = "SELECT DISTINCT c.ID_cliente, c.Dni, c.Nombre, c.Apellido_paterno, c.Apellido_materno, ts.ID_tipo_servicio, ts.Nom_servicio
              FROM cliente c
              INNER JOIN atencion_cliente ac ON c.ID_cliente = ac.id_cliente
              INNER JOIN detalle_cliente_tecnico dct ON ac.ID = dct.ID_atencion_cliente
              INNER JOIN tipo_servicio ts ON dct.ID_tipo_servicio = ts.ID_tipo_servicio
              WHERE dct.ID_tecnico = :id_tecnico
              AND dct.Estado = 1";
    $stmt = $pdo->prepare($query);
    $stmt->execute([':id_tecnico' => $id_tecnico]);

    $clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($clientes);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Error de base de datos: ' . $e->getMessage()]);
} catch (Exception $e) {
    echo json_encode(['error' => 'Error: ' . $e->getMessage()]);
}

