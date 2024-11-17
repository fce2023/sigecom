<?php

include ('../../app/config.php');
include ('../../layout/sesion.php');
include ('../../layout/parte1.php');



if (isset($_GET['ID'])) {
    $id_pedido = $_GET['ID'];
}

$consulta_pedido = $pdo->prepare("SELECT ac.ID, ac.ID_usuario, ac.ID_detalle_cliente_tecnico, ac.id_cliente, ac.ID_tipo_servicio, ac.Codigo_Operacion, ac.fecha_creacion, ac.estado FROM atencion_cliente ac WHERE ac.ID = :id_pedido");
$consulta_pedido->execute([':id_pedido' => $id_pedido]);
$fila_pedido = $consulta_pedido->fetch(PDO::FETCH_ASSOC);

if (!$fila_pedido) {
    header('Location: ' . $URL . '/atencion_cliente/pedidos/lista_pedidos.php');
    exit;
}

$id = $fila_pedido['ID'];
$id_usuario = $fila_pedido['ID_usuario'];
$id_cliente = $fila_pedido['id_cliente'];
$id_tipo_servicio = $fila_pedido['ID_tipo_servicio'];
$id_detalle_cliente_tecnico = $fila_pedido['ID_detalle_cliente_tecnico'];

$Codigo_Operacion = $fila_pedido['Codigo_Operacion'];
$fecha_creacion = $fila_pedido['fecha_creacion'];
$estado = $fila_pedido['estado'];

?>

<div class="container-fluid">
<?php include ('../../layout/cliente.php');?>
<style>
    #mensajeModal .modal-header {
        padding: 0;
        border: 0;
        background-color: green;
        color: white;
    }
    #mensajeModal .modal-header .close {
        display: none;
    }
    #mensajeModal .modal-body {
        padding: 20px;
        background-color: green;
        color: white;
    }
    #mensajeModal .modal-footer {
        padding: 0;
        border: 0;
        background-color: green;
        color: white;
    }
</style>

<div id="mensajeModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
            </div>
        </div>
    </div>
</div>

<div class="row">
    <?php
    $consulta_cliente = $pdo->prepare("SELECT Dni, Nombre, Apellido_paterno, Apellido_materno
                                       FROM cliente
                                       WHERE ID_cliente = :id_cliente");
    $consulta_cliente->execute([':id_cliente' => $id_cliente]);
    $fila_cliente = $consulta_cliente->fetch(PDO::FETCH_ASSOC);
    ?>
    <h1>Editar Pedido de <?php echo $fila_cliente['Nombre'] . ' ' . $fila_cliente['Apellido_paterno'] . ' ' . $fila_cliente['Apellido_materno']; ?></h1>

    <?php
    
    $consulta_usuario_ultima_actualizacion = $pdo->prepare("SELECT p.Nombre, p.Dni, u.Nombre_usuario
                                                            FROM historial_atencion_cliente h
                                                            INNER JOIN usuario u ON h.id_usuario = u.ID_usuario
                                                            INNER JOIN personal p ON u.id_personal = p.ID_personal
                                                            WHERE h.id_atencion_cliente = :id_pedido
                                                            ORDER BY h.fecha DESC
                                                            LIMIT 1");
    $consulta_usuario_ultima_actualizacion->execute([':id_pedido' => $id]);
    $fila_usuario_ultima_actualizacion = $consulta_usuario_ultima_actualizacion->fetch(PDO::FETCH_ASSOC);
    $usuario_ultima_actualizacion = isset($fila_usuario_ultima_actualizacion['Nombre']) && isset($fila_usuario_ultima_actualizacion['Dni']) && isset($fila_usuario_ultima_actualizacion['Nombre_usuario']) 
        ? $fila_usuario_ultima_actualizacion['Nombre'] . ' ' . $fila_usuario_ultima_actualizacion['Dni'] . ' (' . $fila_usuario_ultima_actualizacion['Nombre_usuario'] . ')' 
        : 'El usuario ya no trabaja con nosotros. Consultar el historial.';
    
    
    
    ?>


    <h2 style="color: #007bff">Usuario que realizó la ultima actualizacion: <span style="font-weight: bold; color: #dc3545;"><?php echo $usuario_ultima_actualizacion?></span></h2>
</div>
<!-- <form id="Pedido<?php echo $id; ?>" action="<?php echo $URL; ?>/app/controllers/atencion_cliente/pedidos/editar_pedido.php" method="post"> -->
<form id="editarFormPedido<?php echo $id; ?>">
<div class="form-group">
    <input type="hidden" name="id_pedido-reg" value="<?php echo $id = $fila_pedido['ID']; ?>">
    
        <label for="tipo-servicio-<?php echo $id; ?>">Tipo Servicio</label>
        <select class="form-control" id="tipo-servicio-<?php echo $id; ?>" name="id_tipo_servicio-reg">
            <?php
            $consulta_tipo_servicio = $pdo->prepare("SELECT ID_tipo_servicio, Nom_servicio FROM tipo_servicio ORDER BY Nom_servicio");
            $consulta_tipo_servicio->execute();
            while ($fila_tipo_servicio = $consulta_tipo_servicio->fetch(PDO::FETCH_ASSOC)) {
                ?>
                <option value="<?php echo $fila_tipo_servicio['ID_tipo_servicio']; ?>" <?php echo $id_tipo_servicio == $fila_tipo_servicio['ID_tipo_servicio'] ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($fila_tipo_servicio['Nom_servicio']); ?>
                </option>
                <?php
            }
            ?>
        </select>
</div>



<div class="form-group">
        <label for="detalle-cliente-tecnico-<?php echo $id; ?>">Tecnico encargado</label>
        <select class="form-control" id="detalle-cliente-tecnico-<?php echo $id; ?>" name="id_detalle_cliente_tecnico-reg">
            <?php
            $consulta_detalle_cliente_tecnico = $pdo->prepare("SELECT d.Id_det_cliente_tecnico, t.ID_tecnico, p.Nombre, p.Dni FROM detalle_cliente_tecnico d INNER JOIN tecnico t ON d.ID_tecnico = t.ID_tecnico INNER JOIN personal p ON t.id_personal = p.ID_personal ORDER BY d.Id_det_cliente_tecnico");
            $consulta_detalle_cliente_tecnico->execute();
            while ($fila_detalle_cliente_tecnico = $consulta_detalle_cliente_tecnico->fetch(PDO::FETCH_ASSOC)) {
                ?>
                <option value="<?php echo $fila_detalle_cliente_tecnico['Id_det_cliente_tecnico']; ?>" <?php echo $id_detalle_cliente_tecnico == $fila_detalle_cliente_tecnico['Id_det_cliente_tecnico'] ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars("{$fila_detalle_cliente_tecnico['Nombre']} {$fila_detalle_cliente_tecnico['Dni']}"); ?>
                </option>
                <?php
            }
            ?>
        </select>
</div>

    
    <div class="form-group">
        <label for="codigo-operacion-<?php echo $id; ?>">Código Operación</label>
        <input type="text" class="form-control" id="codigo-operacion-<?php echo $id; ?>" name="codigo-operacion-reg" value="<?php echo htmlspecialchars($Codigo_Operacion); ?>" readonly onfocus="mostrarAviso('No se puede modificar el código de la operación');">
    </div>

    <script>
        function mostrarAviso(mensaje) {
            var datos = { "mensaje": mensaje };
            $.post('<?php echo $URL; ?>atencion_cliente/avisos.php', datos, function(response) {
                $('#mensajeModal .modal-body').html(response.message);
                $('#mensajeModal').modal('show');
                setTimeout(function() {
                    $('#mensajeModal').modal('hide');
                }, 1500);
            }, 'json');
        }
    </script>
    <div class="form-group">
        <label for="fecha-creacion-<?php echo $id; ?>">Fecha Creación</label>
        <input type="text" class="form-control" id="fecha-creacion-<?php echo $id; ?>" name="fecha-creacion-reg" value="<?php echo date('Y-m-d', strtotime($fecha_creacion)); ?>" readonly onfocus="mostrarAviso('No se puede modificar la fecha de creación');">
    </div>

    <?php
    $consulta_fecha_ultima_actualizacion = $pdo->prepare("SELECT MAX(fecha) as fecha_ultima_actualizacion FROM historial_atencion_cliente WHERE id_atencion_cliente = :id_atencion_cliente");
    $consulta_fecha_ultima_actualizacion->execute([':id_atencion_cliente' => $id]);
    $fila_fecha_ultima_actualizacion = $consulta_fecha_ultima_actualizacion->fetch(PDO::FETCH_ASSOC);
    ?>
    <div class="form-group">
        <label for="fecha-ultima-actualizacion-<?php echo $id; ?>">Fecha Última Actualización</label>
        <input type="text" class="form-control" id="fecha-ultima-actualizacion-<?php echo $id; ?>" name="fecha-ultima-actualizacion-reg" value="<?php echo isset($fila_fecha_ultima_actualizacion['fecha_ultima_actualizacion']) ? date('Y-m-d', strtotime($fila_fecha_ultima_actualizacion['fecha_ultima_actualizacion'])) : date('Y-m-d', strtotime($fecha_creacion)); ?>" readonly onfocus="mostrarAviso('No se puede modificar la fecha de última actualización');">
    </div>


  <div class="form-group">
        <label for="estado-pedido-<?php echo $id; ?>">Estado de pedido</label>
        <select class="form-control" id="estado-pedido-<?php echo $id; ?>" name="estado-reg">
            <option value="1" <?php echo $estado == 1 ? 'selected' : ''; ?>>Activo</option>
            <option value="0" <?php echo $estado == 0 ? 'selected' : ''; ?>>Inactivo</option>
        </select>
    </div>





    <fieldset>
    <legend>Historial de Atenciones del Cliente</legend>

        
        <p>Usuario: <?php echo $nombres_sesion; ?></p>


        <div class="form-group">
            <label for="id_estado_atencion_cliente-historial-<?php echo $id; ?>">Estado de Proceso</label>
            <select class="form-control" id="id_estado_atencion_cliente-historial-<?php echo $id; ?>" name="id_estado_atencion_cliente" required>
                <?php
                $query = "SELECT id, nombre FROM estado_atencion_cliente";
                $stmt = $pdo->query($query);
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<option value='{$row['id']}'>{$row['nombre']}</option>";
                }
                ?>
            </select>
        </div>
        <div class="form-group">
            <label for="fecha-historial-<?php echo $id; ?>">Fecha</label>
            <input type="datetime-local" class="form-control" id="fecha-historial-<?php echo $id; ?>" name="fecha-actualizacion" value="<?php echo date('Y-m-d\TH:i'); ?>" required>
        </div>
        <div class="form-group">
            <label for="accion-historial-<?php echo $id; ?>">Accion *</label>
            <input type="text" class="form-control" id="accion-historial-<?php echo $id; ?>" name="accion" required>
        </div>
        <div class="form-group">
            <label for="detalle-historial-<?php echo $id; ?>">Detalle</label>
            <textarea class="form-control" id="detalle-historial-<?php echo $id; ?>" name="detalle" required></textarea>
        </div>










    <button type="submit" class="btn btn-success">Guardar Cambios</button>
    <a href="lista_pedidos.php" class="btn btn-default">Cancelar</a>
</form>

<script>
    $(document).ready(function() {
        $('#editarFormPedido<?php echo $id; ?>').submit(function(event) {
            event.preventDefault();
            var formData = $(this).serializeArray();
            $.ajax({
                type: 'POST',
                url: '<?php echo $URL; ?>/app/controllers/atencion_cliente/pedidos/editar_pedido.php',
                data: formData,
                dataType: 'json',
                success: function(response) {
                    $('#mensajeModal .modal-body').html(response.message);
                    $('#mensajeModal').modal('show');
                    setTimeout(function() {
                        $('#mensajeModal').modal('hide');
                        window.location.href = '<?php echo $URL; ?>/atencion_cliente/pedidos/lista_pedidos.php';
                    }, 1500);
                },
                error: function(xhr, status, error) {
                    $('#mensajeModal .modal-body').html('Error interno del servidor. ' + error);
                    $('#mensajeModal').modal('show');
                    setTimeout(function() {
                        $('#mensajeModal').modal('hide');
                    }, 3000);
                }
            });
        });
    });
</script>

</div>






    <script>
    $(document).ready(function() {
        $('#guardarHistorialPedido<?php echo $id; ?>').submit(function(event) {
            event.preventDefault();
            var formData = $(this).serialize();
            $.post('<?php echo $URL; ?>/app/controllers/atencion_cliente/guardar_historial_pedido.php', formData, function(response) {
                $('#mensajeModal .modal-body').html(response.message);
                $('#mensajeModal').modal('show');
                setTimeout(function() {
                    $('#mensajeModal').modal('hide');
                    window.location.href = '<?php echo $URL; ?>/atencion_cliente/lista_pedidos.php';
                }, 1500);
            }, 'json');
        });
    });
    </script>


<footer>

<footer style="background-color: #3E3E3E; color: white; padding: 10px; text-align: center;">

<?php
include ('../../layout/parte2.php');
?>
</div>



</footer>
