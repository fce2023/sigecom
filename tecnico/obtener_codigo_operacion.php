<?php
include ('../app/config.php');

if (isset($_GET['id_atencion_cliente'])) {
    $id_atencion_cliente = $_GET['id_atencion_cliente'];
    
    // Query para obtener los datos necesarios del cliente
    $query = "SELECT ac.ID_tipo_servicio, t.Nom_servicio, ac.Codigo_Operacion
              FROM atencion_cliente ac
              LEFT JOIN tipo_servicio t ON ac.ID_tipo_servicio = t.ID_tipo_servicio
              WHERE ac.ID = :id_atencion_cliente";
    
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':id_atencion_cliente', $id_atencion_cliente);
    $stmt->execute();
    
    $response = [];
    
    if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $response['success'] = true;
        $response['id_tipo_servicio'] = $row['ID_tipo_servicio'];
        $response['nom_servicio'] = $row['Nom_servicio'];
        $response['codigo_operacion'] = $row['Codigo_Operacion'];
    } else {
        $response['success'] = false;
    }
    
    echo json_encode($response);
}
?>

