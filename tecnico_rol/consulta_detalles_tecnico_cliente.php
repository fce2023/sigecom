<?php

//datos de detalle tecnico
$idTecnico = isset($_GET['id_tecnico']) ? $_GET['id_tecnico'] : null;
$consulta = $pdo->prepare("SELECT dct.ID_tecnico, dct.ID_tipo_servicio, dct.ID_usuario, dct.Fecha_atencion, dct.Observacion, dct.Estado, dct.ID_atencion_cliente
    FROM detalle_cliente_tecnico dct
    INNER JOIN tecnico t ON dct.ID_tecnico = t.ID_tecnico
    WHERE dct.ID_tecnico = :id_tecnico");
$consulta->bindParam(':id_tecnico', $idTecnico, PDO::PARAM_INT);
$consulta->execute();
$detalleClienteTecnico = $consulta->fetchAll(PDO::FETCH_ASSOC);
$fechaHora = $detalleClienteTecnico[0]['Fecha_atencion'];
//datos para el servicio
$idServicio = $detalleClienteTecnico[0]['ID_tipo_servicio'];
$consulta2 = $pdo->prepare("SELECT Nom_servicio FROM tipo_servicio WHERE ID_tipo_servicio = :id_tipo_servicio");
$consulta2->bindParam(':id_tipo_servicio', $idServicio, PDO::PARAM_INT);
$consulta2->execute();
$tipoServicio = $consulta2->fetch(PDO::FETCH_ASSOC);
$nombreServicio = $tipoServicio['Nom_servicio'];
$idsAtencionCliente = array_column($detalleClienteTecnico, 'ID_atencion_cliente');
$consulta3 = $pdo->prepare("SELECT ID_usuario, id_cliente, ID_tipo_servicio, Codigo_Operacion, fecha_creacion, estado
    FROM atencion_cliente
    WHERE ID IN (" . implode(',', array_fill(0, count($idsAtencionCliente), '?')) . ")");
$consulta3->execute($idsAtencionCliente);
$atencionCliente = $consulta3->fetchAll(PDO::FETCH_ASSOC);
$clientes = [];
foreach ($atencionCliente as $atencion) {
    $id_cliente = $atencion['id_cliente'];
    $consulta4 = $pdo->prepare("SELECT Dni, Nombre FROM cliente WHERE ID_cliente = :id_cliente");
    $consulta4->bindParam(':id_cliente', $id_cliente, PDO::PARAM_INT);
    $consulta4->execute();
    $cliente = $consulta4->fetch(PDO::FETCH_ASSOC);
    $clientes[] = $cliente;
}
//datos de tecnico
$consulta2 = $pdo->prepare("SELECT t.ID_tecnico, t.id_personal, dct.ID_tipo_servicio, dct.ID_usuario, dct.Fecha_atencion, dct.Observacion, dct.Estado, dct.ID_atencion_cliente
    FROM detalle_cliente_tecnico dct
    INNER JOIN tecnico t ON dct.ID_tecnico = t.ID_tecnico
    WHERE dct.ID_tecnico = :id_tecnico");
$consulta2->bindParam(':id_tecnico', $idTecnico, PDO::PARAM_INT);
$consulta2->execute();

$detalleClienteTecnico = $consulta2->fetchAll(PDO::FETCH_ASSOC);

$idPersonal = 0;
foreach ($detalleClienteTecnico as $detalle) {
    $idPersonal = $detalle['id_personal'];
    break;
}
$consulta3 = $pdo->prepare("SELECT Nombre, Apellido_paterno, Apellido_materno FROM personal WHERE ID_personal = :id_personal");
$consulta3->bindParam(':id_personal', $idPersonal, PDO::PARAM_INT);
$consulta3->execute();
$personal = $consulta3->fetch(PDO::FETCH_ASSOC);

$personal['Nombre'] . ' ' . $personal['Apellido_paterno'] . ' ' . $personal['Apellido_materno'];

$nombredeltecnico = $personal['Nombre'] . ' ' . $personal['Apellido_paterno'] . ' ' . $personal['Apellido_materno'];

?>