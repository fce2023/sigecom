
<?php

include ('../app/config.php');
include ('../layout/sesion.php');
include ('../layout/parte1.php');
include ('../layout/tecnico.php');

try {
    $consulta_tecnicos = $pdo->prepare("SELECT * FROM detalle_cliente_tecnico");
    $consulta_tecnicos->execute();
    $tecnicos = $consulta_tecnicos->fetchAll(PDO::FETCH_ASSOC);

    if (empty($tecnicos)) {
        echo "No existen registros en la tabla de detalle_cliente_tecnico <br><br>";
    } else {
        foreach ($tecnicos as $tecnico) {
            $id_detalle_cliente_tecnico = $tecnico['Id_det_cliente_tecnico'];
            $id_cliente = $tecnico['ID_cliente'];
            $id_tecnico = $tecnico['ID_tecnico'];
            $id_tipo_servicio = $tecnico['ID_tipo_servicio'];
            $id_usuario = $tecnico['ID_usuario'];
            $fecha_atencion = $tecnico['Fecha_atencion'];
            $observacion = $tecnico['Observación'];
            $estado = $tecnico['Estado'];
            $id_detalle_cliente_producto = $tecnico['ID_detalle_cliente_producto'];

            echo "ID detalle cliente tecnico: $id_detalle_cliente_tecnico <br>";
            echo "ID cliente: $id_cliente <br>";
            echo "ID tecnico: $id_tecnico <br>";
            echo "ID tipo servicio: $id_tipo_servicio <br>";
            echo "ID usuario: $id_usuario <br>";
            echo "Fecha atencion: $fecha_atencion <br>";
            echo "Observación: $observacion <br>";
            echo "Estado: $estado <br>";
            echo "ID detalle cliente producto: $id_detalle_cliente_producto <br><br>";
        }
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}



