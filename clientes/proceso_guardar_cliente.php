<?php
include '../app/config.php';


header('Content-Type: text/html; charset=utf-8');
/* header('Content-Type: application/json; charset=utf-8');
 */
try {
    // Verificar método HTTP
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Método de solicitud no válido. Solo se permite POST.');
    }

    $stmt_usuario = $pdo->prepare("SELECT ID_usuario FROM usuario WHERE ID_tipousuario = (SELECT ID_tipousuario FROM tipo_usuario WHERE Nombre_tipousuario = 'superadministrador' AND Estado = '1') LIMIT 1");
    $stmt_usuario->execute();
    $fila_usuario = $stmt_usuario->fetch(PDO::FETCH_ASSOC);
    $id_usuario = $fila_usuario['ID_usuario'] ?? null;
    // Inicializar variables

    if (!$id_usuario) {
    }
   
    //datos del cliente
    $dni = $_POST['dni'];
    $full_name = $_POST['full_name'];
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $address = $_POST['address'];
    $correo_electronico = $_POST['correo-electronico-reg'];
    $celular = $_POST['celular-reg'];

    //datos del servicio
    $id_servicio = $_POST['id_servicio'];
    $details = $_POST['details'];
    $codigo_operacion = $_POST['codigo-operacion-reg'];



    $stmt = $pdo->prepare("
        INSERT INTO cliente (Dni, Nombre, Apellido_paterno, Apellido_materno, Dirección, Celular, Correo_Electronico, Estado)
        VALUES (:dni, :nombre, :apellido_paterno, :apellido_materno, :direccion, :celular, :correo_electronico, :estado)
    ");
    $stmt->execute([
        ':dni' => $dni,
        ':nombre' => $name,
        ':apellido_paterno' => $surname,
        ':apellido_materno' => '', // Adjust as needed for Apellido_materno
        ':direccion' => $address,
        ':celular' => $celular,
        ':correo_electronico' => $correo_electronico,
        ':estado' => 1 // Assuming default state is '1' for active
    ]);

    $stmt = $pdo->prepare("
        SELECT ID_cliente
        FROM cliente
        ORDER BY ID_cliente DESC
        LIMIT 1
    ");
    $stmt->execute();
    $id_cliente = $stmt->fetchColumn();



    // Uso de transacciones para garantizar la integridad
    $pdo->beginTransaction();

    // Primera inserción: atencion_cliente
    $query1 = "INSERT INTO atencion_cliente (ID_usuario, id_cliente, ID_tipo_servicio, Codigo_Operacion, fecha_creacion, estado) VALUES (:id_usuario, :id_cliente, :id_servicio, :codigo_operacion, :fecha_creacion, :estado)";
    $stmt1 = $pdo->prepare($query1);
    $stmt1->bindValue(':id_usuario', $id_usuario, PDO::PARAM_INT);
    $stmt1->bindValue(':id_cliente', $id_cliente, PDO::PARAM_INT);
    $stmt1->bindValue(':id_servicio', $id_servicio, PDO::PARAM_INT);
    $stmt1->bindValue(':codigo_operacion', $codigo_operacion, PDO::PARAM_STR);
    $stmt1->bindValue(':fecha_creacion', date('Y-m-d H:i:s'), PDO::PARAM_STR);
    $stmt1->bindValue(':estado', 0, PDO::PARAM_INT);
    $stmt1->execute();
    // Obtener el ID de la atención creada
    $id_atencion_cliente = $pdo->lastInsertId();

   
    // Segunda inserción: historial_atencion_cliente
    $query2 = "INSERT INTO historial_atencion_cliente 
                (id_usuario, id_atencion_cliente, id_estado_atencion_cliente, fecha, accion, detalle) 
               VALUES 
                (:id_usuario, :id_atencion_cliente, :id_estado_atencion_cliente, :fecha, :accion, :detalle)";
    $stmt2 = $pdo->prepare($query2);
    $stmt2->bindValue(':id_usuario', $id_usuario, PDO::PARAM_INT);
    $stmt2->bindValue(':id_atencion_cliente', $id_atencion_cliente, PDO::PARAM_INT);
    $stmt2->bindValue(':id_estado_atencion_cliente', $estado ?? 1, PDO::PARAM_INT);
    $stmt2->bindValue(':fecha', $fecha_actual = date('Y-m-d H:i:s'), PDO::PARAM_STR);
    $stmt2->bindValue(':accion', $accion = 'Creación', PDO::PARAM_STR);
    $stmt2->bindValue(':detalle', $detalle = 'Creación de la atención al cliente', PDO::PARAM_STR);
    $stmt2->execute();

    // Confirmar transacción
    $pdo->commit();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultado de la Atención</title>
</head>
<body>
    <div class="container">
        <h1>Resultado de la Atención</h1>
        <p>
            <?php try { ?>
                Su pedido se ha guardado correctamente.
                <p>Volver a la Atención en 3 segundos...</p>
                <script>
                    setTimeout(function(){ window.location.href="index.php"; }, 3000);
                </script>
            <?php } catch (Exception $e) { ?>
                Error: <?php echo htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8'); ?>
            <?php } ?>
        </p>
    </div>
</body>
</html>

<?php
} catch (Exception $e) {
    $pdo->rollBack();
    echo "Error: " . $e->getMessage();
}
?>

