<?php

include ('../../app/config.php');
include ('../../layout/sesion.php');
include ('../../layout/parte1.php');
?>

<?php
$id_producto = isset($_GET['id_producto']) ? $_GET['id_producto'] : '';

$consulta_producto = $pdo->prepare("SELECT nombre, descripcion, id_tipo_producto, precio, estado FROM productos WHERE id_producto = :id_producto");
$consulta_producto->execute([':id_producto' => $id_producto]);
$fila = $consulta_producto->fetch(PDO::FETCH_ASSOC);
?>
<div class="container-fluid">
<?php include ('layout/parte1.php');?>
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
<script>
    $(document).ready(function () {
        $('#editarFormProducto<?php echo $id_producto; ?>').submit(function (e) {
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
                        window.location.href = '<?php echo $URL; ?>/inventario/productos/lista.php';
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

<form id="editarFormProducto<?php echo $id_producto; ?>" action="<?php echo $URL; ?>/app/controllers/inventario/productos/editar.php" method="post">
    <input type="hidden" name="id_producto" value="<?php echo $id_producto; ?>">
    <div class="form-group">
        <label for="nombre-producto-<?php echo $id_producto; ?>">Nombre del producto</label>
        <input type="text" class="form-control" id="nombre-producto-<?php echo $id_producto; ?>" name="nombre-reg" value="<?php echo htmlspecialchars($fila['nombre']); ?>" required>
    </div>

    <div class="form-group">
        <label for="descripcion-producto-<?php echo $id_producto; ?>">Descripci n del producto</label>
        <textarea class="form-control" id="descripcion-producto-<?php echo $id_producto; ?>" name="descripcion-reg" rows="3"><?php echo htmlspecialchars($fila['descripcion']); ?></textarea>
    </div>

    <div class="form-group">
        <label for="tipo-producto-<?php echo $id_producto; ?>">Tipo de producto</label>
        <select class="form-control" id="tipo-producto-<?php echo $id_producto; ?>" name="id_tipo_producto">
            <?php
            $consulta_tipo_producto = $pdo->prepare("SELECT ID_tipo_producto, Nom_producto FROM tipo_producto WHERE Estado = 1 ORDER BY Nom_producto");
            $consulta_tipo_producto->execute();
            while ($fila_tipo_producto = $consulta_tipo_producto->fetch(PDO::FETCH_ASSOC)) {
                ?>
                <option value="<?php echo $fila_tipo_producto['ID_tipo_producto']; ?>" <?php echo $fila_tipo_producto['ID_tipo_producto'] == $fila['id_tipo_producto'] ? 'selected' : ''; ?>>
                    <?php echo $fila_tipo_producto['Nom_producto']; ?>
                </option>
                <?php
            }
            ?>
        </select>
    </div>

    <div class="form-group">
        <label for="precio-producto-<?php echo $id_producto; ?>">Precio del producto</label>
        <input type="number" class="form-control" id="precio-producto-<?php echo $id_producto; ?>" name="precio-reg" value="<?php echo htmlspecialchars($fila['precio']); ?>" required>
    </div>

    <div class="form-group">
        <label for="estado-producto-<?php echo $id_producto; ?>">Estado</label>
        <select class="form-control" id="estado-producto-<?php echo $id_producto; ?>" name="estado-reg">
            <option value="1" <?php echo $fila['estado'] == 1 ? 'selected' : ''; ?>>Activo</option>
            <option value="0" <?php echo $fila['estado'] == 0 ? 'selected' : ''; ?>>Inactivo</option>
        </select>
    </div>

    <button type="submit" class="btn btn-success">Guardar Cambios</button>
    <a href="javascript:window.history.back();" class="btn btn-default">Cancelar</a>
</form>

</div>

