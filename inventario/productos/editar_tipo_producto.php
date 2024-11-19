<?php

$id_tipo_producto = isset($_GET['id_tipo_producto']) ? $_GET['id_tipo_producto'] : 0;

include ('../../app/config.php');
include ('../../layout/sesion.php');
include ('../../layout/parte1.php');
?>
<div class="container-fluid">
<?php include ('layout/parte1.php');?>
<style>
    body {
        display: flex;
        align-items: center;
        justify-content: center;
    }
    #mensajeModal {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }
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
<script>
    $(document).ready(function () {
        $('#editarFormTipoProducto<?php echo $id_tipo_producto; ?>').submit(function (e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: $(this).attr('action'),
                data: formData,
                dataType: 'json',
                beforeSend: function () {
                    $('#mensajeModal').modal('show');
                    $('#mensajeModal .modal-body').html('Procesando...');
                },
                success: function (data) {
                    $('#mensajeModal .modal-body').html(data.message);
                    setTimeout(function () {
                        $('#mensajeModal').modal('hide');
                        window.location.href = '<?php echo $URL; ?>/inventario/productos/tipo_producto.php';
                    }, 2000);
                },
                error: function (xhr, status, error) {
                    $('#mensajeModal .modal-body').html('Error interno del servidor. ' + error);
                    setTimeout(function () {
                        $('#mensajeModal').modal('hide');
                    }, 3000);
                }
            });
        });
    });
</script>
<div id="mensajeModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
            </div>
        </div>
    </div>
</div>

<?php
$consulta_tipo_producto = $pdo->prepare("SELECT Nom_producto, Estado FROM tipo_producto WHERE ID_tipo_producto = :id_tipo_producto");
$consulta_tipo_producto->execute([':id_tipo_producto' => $id_tipo_producto]);
$fila = $consulta_tipo_producto->fetch(PDO::FETCH_ASSOC);
?>
<form id="editarFormTipoProducto<?php echo $id_tipo_producto; ?>" action="<?php echo $URL; ?>/app/controllers/inventario/productos/editar_tipo_producto.php" method="post">
    <input type="hidden" name="id_tipo_producto" value="<?php echo $id_tipo_producto; ?>">
    <div class="form-group">
        <label for="nombre-tipo-<?php echo $id_tipo_producto; ?>">Nombre del tipo de producto</label>
        <input type="text" class="form-control" id="nombre-tipo-<?php echo $id_tipo_producto; ?>" name="nombre-reg" value="<?php echo htmlspecialchars($fila['Nom_producto']); ?>" required>
    </div>

    <div class="form-group">
        <label for="estado-tipo-<?php echo $id_tipo_producto; ?>">Estado</label>
        <select class="form-control" id="estado-tipo-<?php echo $id_tipo_producto; ?>" name="estado-reg">
            <option value="1" <?php echo $fila['Estado'] == 1 ? 'selected' : ''; ?>>Activo</option>
            <option value="0" <?php echo $fila['Estado'] == 0 ? 'selected' : ''; ?>>Inactivo</option>
        </select>
    </div>

    <button type="submit" class="btn btn-success">Guardar Cambios</button>
    <a href="javascript:window.history.back();" class="btn btn-default">Cancelar</a>
</form>

</div>


