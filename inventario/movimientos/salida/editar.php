<?php
include('../../../app/config.php');
include('../../../layout/sesion.php');
include('../../../layout/parte1.php');

// Validar el parámetro `id_detalle_tecnico_producto`
$id_detalle_tecnico_producto = isset($_GET['Id_det_tecnico_producto']) ? (int)$_GET['Id_det_tecnico_producto'] : 0;

// Consulta para obtener el detalle del tecnico-producto
$consulta_detalle_tecnico_producto = $pdo->prepare("
    SELECT 
        dtp.ID_tecnico, 
        dtp.ID_producto, 
        dtp.Fecha_retiro, 
        dtp.cantidad, 
        dtp.Observación, 
        dtp.Estado, 
        t.nombre AS nombre_tecnico, 
        p.nombre AS nombre_producto
    FROM 
        detalle_tecnico_producto dtp
    LEFT JOIN 
        tecnico t ON dtp.ID_tecnico = t.ID_tecnico
    LEFT JOIN 
        productos p ON dtp.ID_producto = p.ID_producto
    WHERE 
        dtp.Id_det_tecnico_producto = :Id_det_tecnico_producto
");
$consulta_detalle_tecnico_producto->execute([':Id_det_tecnico_producto' => $id_detalle_tecnico_producto]);
$fila = $consulta_detalle_tecnico_producto->fetch(PDO::FETCH_ASSOC);

if (!$fila) {
    echo json_encode(["success" => false, "error" => "Detalle no encontrado."]);
    exit;
}
?>
<style>
    #editarFormDetalleTecnicoProducto {
        margin-top: 20px;
    }
    #editarFormDetalleTecnicoProducto .form-group {
        margin-bottom: 20px;
    }
    #editarFormDetalleTecnicoProducto .form-group label {
        font-weight: bold;
    }
    #editarFormDetalleTecnicoProducto .btn {
        margin-top: 20px;
    }
    #editarFormDetalleTecnicoProducto .alert {
        margin-top: 20px;
    }
</style>

<div class="container-fluid">
    <form id="editarFormDetalleTecnicoProducto" action="<?php echo $URL; ?>/app/controllers/inventario/salida/editar_salida.php" method="post">
        <input type="hidden" name="id_detalle_tecnico_producto" value="<?php echo $id_detalle_tecnico_producto; ?>">
        
        <div class="form-group">
            <label for="nombre-tecnico">Nombre del tecnico</label>
            <select class="form-control" id="nombre-tecnico" name="ID_tecnico">
                <?php
                $consulta_tecnico = $pdo->prepare("SELECT ID_tecnico, nombre FROM tecnico WHERE Estado = 1 ORDER BY nombre");
                $consulta_tecnico->execute();
                while ($fila_tecnico = $consulta_tecnico->fetch(PDO::FETCH_ASSOC)) {
                    echo "<option value='{$fila_tecnico['ID_tecnico']}'" . 
                         ($fila_tecnico['ID_tecnico'] == $fila['ID_tecnico'] ? ' selected' : '') . 
                         ">{$fila_tecnico['nombre']}</option>";
                }
                ?>
            </select>
        </div>

        <div class="form-group">
            <label for="nombre-producto">Nombre del producto</label>
            <select class="form-control" id="nombre-producto" name="ID_producto">
                <?php
                $consulta_producto = $pdo->prepare("SELECT ID_producto, nombre FROM productos WHERE estado = 1 ORDER BY nombre");
                $consulta_producto->execute();
                while ($fila_producto = $consulta_producto->fetch(PDO::FETCH_ASSOC)) {
                    echo "<option value='{$fila_producto['ID_producto']}'" . 
                         ($fila_producto['ID_producto'] == $fila['ID_producto'] ? ' selected' : '') . 
                         ">{$fila_producto['nombre']}</option>";
                }
                ?>
            </select>
        </div>

        <div class="form-group">
            <label for="fecha-retiro">Fecha de retiro</label>
            <input type="date" class="form-control" id="fecha-retiro" name="Fecha_retiro" 
                   value="<?php echo $fila['Fecha_retiro']; ?>" required>
        </div>

        <div class="form-group">
            <label for="cantidad">Cantidad</label>
            <input type="number" class="form-control" id="cantidad" name="cantidad" 
                   value="<?php echo $fila['cantidad']; ?>" required>
        </div>

        <div class="form-group">
            <label for="observacion">Observación</label>
            <textarea class="form-control" id="observacion" name="Observación" rows="3"><?php echo $fila['Observación']; ?></textarea>
        </div>

        <div class="form-group">
            <label for="estado">Estado</label>
            <select class="form-control" id="estado" name="Estado">
                <option value="Activo" <?php echo $fila['Estado'] === 'Activo' ? 'selected' : ''; ?>>Activo</option>
                <option value="Inactivo" <?php echo $fila['Estado'] === 'Inactivo' ? 'selected' : ''; ?>>Inactivo</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
        <a href="javascript:window.history.back();" class="btn btn-default">Cancelar</a>
    </form>
</div>

<script>
    var campos = {};
    document.getElementById('editarFormDetalleTecnicoProducto').addEventListener('input', function(event) {
        var campo = event.target;
        var name = campo.name;
        var value = campo.value;
        campos[name] = value;
    });

    document.getElementById('editarFormDetalleTecnicoProducto').addEventListener('submit', function(event) {
        event.preventDefault();
        var form = this;
        var formData = new FormData(form);

        for (var campo in campos) {
            formData.set(campo, campos[campo]);
        }

        var xhr = new XMLHttpRequest();
        xhr.open('POST', '../../../app/controllers/inventario/salida/editar_salida.php', true);
        xhr.onload = function() {
            try {
                if (xhr.status === 200) {
                    var data = JSON.parse(xhr.responseText);
                    if (data.success) {
                        showModal('Salida de producto guardada exitosamente.', 'success', true);
                    } else {
                        showModal('Error: ' + (data.error || 'No se pudo guardar la salida del producto.'), 'danger');
                    }
                } else {
                    showModal('Error en la conexión al servidor. Código de estado: ' + xhr.status, 'danger');
                }
            } catch (error) {
                showModal('Error al procesar la respuesta del servidor: ' + error, 'danger');
            }
        };

        xhr.onerror = function() {
            showModal('Error al enviar la solicitud. Verifique su conexión.', 'danger');
        };

        xhr.send(formData);
    });

    function showModal(message, type, showButtons = false) {
        var modalContent = `
            <div class="modal fade" id="mensajeModal" tabindex="-1" role="dialog" aria-labelledby="mensajeModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="mensajeModalLabel">${type === 'success' ? 'Éxito' : 'Error'}</h5>
                        </div>
                        <div class="modal-body">
                            ${message}
                        </div>
                        <div class="modal-footer">
                            ${showButtons ? `
                                <button id="nuevaSalidaProductoBtn" class="btn btn-primary btn-sm">Seguir editando</button>
                                <button id="listaSalidasBtn" class="btn btn-secondary btn-sm">Ir a la lista</button>
                            ` : ''}
                        </div>
                    </div>
                </div>
            </div>`;
        
        document.body.insertAdjacentHTML('beforeend', modalContent);
        $('#mensajeModal').modal('show');

        if (showButtons) {
            document.getElementById('nuevaSalidaProductoBtn').addEventListener('click', function() {
                document.getElementById('nuevaSalidaProductoForm').reset();
                $('#mensajeModal').modal('hide');
            });
            document.getElementById('listaSalidasBtn').addEventListener('click', function() {
                window.location.href = '<?php echo $URL; ?>inventario/movimientos/salida/index.php';
            });
        }
    }
</script>
