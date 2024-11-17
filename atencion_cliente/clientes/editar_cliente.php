<?php

include ('../../app/config.php');
include ('../../layout/sesion.php');
include ('../../layout/parte1.php');

$id_cliente = isset($_GET['ID_cliente']) ? $_GET['ID_cliente'] : '';

$consulta_cliente = $pdo->prepare("SELECT Dni, Nombre, Apellido_paterno, Apellido_materno, Dirección, Celular, Correo_Electronico, Estado 
                                   FROM cliente 
                                   WHERE ID_cliente = :id_cliente");
$consulta_cliente->execute([':id_cliente' => $id_cliente]);
$fila = $consulta_cliente->fetch(PDO::FETCH_ASSOC);

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

<form id="editarFormCliente<?php echo $id_cliente; ?>">
    <input type="hidden" name="id_cliente" value="<?php echo $id_cliente; ?>">
    <div class="form-group">
        <label for="dni-cliente-<?php echo $id_cliente; ?>">DNI</label>
        <input type="text" class="form-control" id="dni-cliente-<?php echo $id_cliente; ?>" name="dni-reg" value="<?php echo htmlspecialchars($fila['Dni']); ?>" required>
    </div>

    <div class="form-group">
        <label for="nombre-cliente-<?php echo $id_cliente; ?>">Nombre</label>
        <input type="text" class="form-control" id="nombre-cliente-<?php echo $id_cliente; ?>" name="nombre-reg" value="<?php echo htmlspecialchars($fila['Nombre']); ?>" required>
    </div>

    <div class="form-group">
        <label for="apellido-paterno-cliente-<?php echo $id_cliente; ?>">Apellido Paterno</label>
        <input type="text" class="form-control" id="apellido-paterno-cliente-<?php echo $id_cliente; ?>" name="apellido-paterno-reg" value="<?php echo htmlspecialchars($fila['Apellido_paterno']); ?>" required>
    </div>

    <div class="form-group">
        <label for="apellido-materno-cliente-<?php echo $id_cliente; ?>">Apellido Materno</label>
        <input type="text" class="form-control" id="apellido-materno-cliente-<?php echo $id_cliente; ?>" name="apellido-materno-reg" value="<?php echo htmlspecialchars($fila['Apellido_materno']); ?>" required>
    </div>

    <div class="form-group">
        <label for="direccion-cliente-<?php echo $id_cliente; ?>">Dirección</label>
        <input type="text" class="form-control" id="direccion-cliente-<?php echo $id_cliente; ?>" name="direccion-reg" value="<?php echo htmlspecialchars($fila['Dirección']); ?>" required>
    </div>

    <div class="form-group">
        <label for="celular-cliente-<?php echo $id_cliente; ?>">Celular</label>
        <input type="text" class="form-control" id="celular-cliente-<?php echo $id_cliente; ?>" name="celular-reg" value="<?php echo htmlspecialchars($fila['Celular']); ?>" required>
    </div>

    <div class="form-group">
        <label for="correo-electronico-cliente-<?php echo $id_cliente; ?>">Correo Electronico</label>
        <input type="email" class="form-control" id="correo-electronico-cliente-<?php echo $id_cliente; ?>" name="correo-electronico-reg" value="<?php echo htmlspecialchars($fila['Correo_Electronico']); ?>" required>
    </div>

    <div class="form-group">
        <label for="estado-cliente-<?php echo $id_cliente; ?>">Estado</label>
        <select class="form-control" id="estado-cliente-<?php echo $id_cliente; ?>" name="estado-reg">
            <option value="1" <?php echo $fila['Estado'] == 1 ? 'selected' : ''; ?>>Activo</option>
            <option value="0" <?php echo $fila['Estado'] == 0 ? 'selected' : ''; ?>>Inactivo</option>
        </select>
    </div>

    <button type="submit" class="btn btn-success">Guardar Cambios</button>
    <a href="listado_clientes.php" class="btn btn-default">Cancelar</a>
</form>

<script>
    $(document).ready(function() {
        $('#editarFormCliente<?php echo $id_cliente; ?>').submit(function(event) {
            event.preventDefault();
            var formData = $(this).serializeArray();
            $.ajax({
                type: 'POST',
                url: '<?php echo $URL; ?>/app/controllers/atencion_cliente/clientes/editar_cliente.php',
                data: formData,
                dataType: 'json',
                success: function(response) {
                    $('#mensajeModal .modal-body').html(response.message);
                    $('#mensajeModal').modal('show');
                    setTimeout(function() {
                        $('#mensajeModal').modal('hide');
                        window.location.href = '<?php echo $URL; ?>/atencion_cliente/clientes/lista_clientes.php';
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

