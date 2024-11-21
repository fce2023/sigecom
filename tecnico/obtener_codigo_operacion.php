<?php
require_once('../app/config.php');

if (isset($_GET['id_cliente'])) {
    $id_cliente = $_GET['id_cliente'];
    $query = "SELECT t.ID_tipo_servicio, t.Nom_servicio, ac.Codigo_Operacion, ac.fecha_creacion FROM atencion_cliente ac
              INNER JOIN tipo_servicio t ON ac.ID_tipo_servicio = t.ID_tipo_servicio
              WHERE ac.id_cliente = :id_cliente ORDER BY ac.fecha_creacion DESC LIMIT 1";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['id_cliente' => $id_cliente]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    echo json_encode(['success' => true, 'id_tipo_servicio' => $row['ID_tipo_servicio'], 'codigo_operacion' => $row['Codigo_Operacion'], 'nom_servicio' => $row['Nom_servicio']]);
} else {
    echo json_encode(['success' => false, 'id_tipo_servicio' => '', 'codigo_operacion' => '', 'nom_servicio' => '']);
}


