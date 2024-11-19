<?php
include('../../../app/config.php');
include('../../../layout/sesion.php');
include('../../../layout/parte1.php');

// Validar el parámetro `id_detalle_producto_proveedor`
$id_detalle_producto_proveedor = isset($_GET['Id_det_producto_proveedor']) ? (int)$_GET['Id_det_producto_proveedor'] : 0;

// Consulta para obtener el detalle del producto-proveedor
$consulta_detalle_producto_proveedor = $pdo->prepare("
    SELECT 
        dpp.ID_proveedor, 
        dpp.ID_producto, 
        dpp.Fecha_abastecimiento, 
        dpp.cantidad, 
        dpp.Observación, 
        dpp.Estado, 
        p.nombre AS nombre_producto, 
        tp.Nom_producto AS nombre_tipo_producto, 
        pr.Nombre AS nombre_proveedor
    FROM 
        detalle_producto_proveedor dpp
    LEFT JOIN 
        productos p ON dpp.ID_producto = p.ID_producto
    LEFT JOIN 
        tipo_producto tp ON p.id_tipo_producto = tp.ID_tipo_producto
    LEFT JOIN 
        proveedor pr ON dpp.ID_proveedor = pr.ID_proveedor
    WHERE 
        dpp.Id_det_producto_proveedor = :Id_det_producto_proveedor
");
$consulta_detalle_producto_proveedor->execute([':Id_det_producto_proveedor' => $id_detalle_producto_proveedor]);
$fila = $consulta_detalle_producto_proveedor->fetch(PDO::FETCH_ASSOC);

if (!$fila) {
    echo "<div class='alert alert-danger'>Detalle no encontrado.</div>";
    exit;
}
?>
<style>
    #editarFormDetalleProductoProveedor {
        margin-top: 20px;
    }
    #editarFormDetalleProductoProveedor .form-group {
        margin-bottom: 20px;
    }
    #editarFormDetalleProductoProveedor .form-group label {
        font-weight: bold;
    }
    #editarFormDetalleProductoProveedor .btn {
        margin-top: 20px;
    }
    #editarFormDetalleProductoProveedor .alert {
        margin-top: 20px;
    }
</style>

<div class="container-fluid">
<!-- <form id="" action="<?php echo $URL; ?>/app/controllers/inventario/entrada/editar_entrada.php" method="post"> -->
    <form id="editarFormDetalleProductoProveedor">
        <input type="hidden" name="id_detalle_producto_proveedor" value="<?php echo $id_detalle_producto_proveedor; ?>">
        
        <div class="form-group">
            <label for="nombre-producto">Nombre del producto</label>
            <select class="form-control" id="nombre-producto" name="ID_producto">
                <?php
                $consulta_producto = $pdo->prepare("SELECT ID_producto, nombre FROM productos WHERE estado = 1 ORDER BY nombre");
                $consulta_producto->execute();
                if ($consulta_producto->rowCount() > 0) {
                    while ($fila_producto = $consulta_producto->fetch(PDO::FETCH_ASSOC)) {
                        echo "<option value='{$fila_producto['ID_producto']}'" . 
                             ($fila_producto['ID_producto'] == $fila['ID_producto'] ? ' selected' : '') . 
                             ">{$fila_producto['nombre']}</option>";
                    }
                } else {
                    echo "<option>No hay ningun producto disponible o está en estado inactivo</option>";
                }
                ?>
            </select>
        </div>

        <div class="form-group">
            <label for="nombre-proveedor">Nombre del proveedor</label>
            <select class="form-control" id="nombre-proveedor" name="ID_proveedor">
                <?php
                $consulta_proveedor = $pdo->prepare("SELECT ID_proveedor, Nombre FROM proveedor WHERE Estado = 1 ORDER BY Nombre");
                $consulta_proveedor->execute();
                while ($fila_proveedor = $consulta_proveedor->fetch(PDO::FETCH_ASSOC)) {
                    echo "<option value='{$fila_proveedor['ID_proveedor']}'" . 
                         ($fila_proveedor['ID_proveedor'] == $fila['ID_proveedor'] ? ' selected' : '') . 
                         ">{$fila_proveedor['Nombre']}</option>";
                }
                ?>
            </select>
        </div>

        <div class="form-group">
            <label for="fecha-abastecimiento">Fecha de abastecimiento</label>
            <input type="date" class="form-control" id="fecha-abastecimiento" name="Fecha_abastecimiento" 
                   value="<?php echo $fila['Fecha_abastecimiento']; ?>" required>
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
                <option value="1" <?php echo $fila['Estado'] == 1 ? 'selected' : ''; ?>>Activo</option>
                <option value="0" <?php echo $fila['Estado'] == 0 ? 'selected' : ''; ?>>Inactivo</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
        <a href="javascript:window.history.back();" class="btn btn-default">Cancelar</a>
    </form>
</div>


<script>
    document.getElementById('editarFormDetalleProductoProveedor').addEventListener('submit', function(event) {
        event.preventDefault();
        var form = this;
        var camposInvalidos = validarCampos(form); // Validar campos vacíos

        // Si hay campos vacíos, mostrar un modal con los campos faltantes
        if (camposInvalidos.length > 0) {
            var mensaje = 'Por favor, complete los siguientes campos requeridos:<br>';
            mensaje += camposInvalidos.map(campo => `- ${campo}`).join('<br>');
            showModal(mensaje, 'danger');
            return;
        }

        var formData = new FormData(form);

        var xhr = new XMLHttpRequest();
        xhr.open('POST', '../../../app/controllers/inventario/entrada/editar_entrada.php', true);
        xhr.onload = function() {
            try {
                if (xhr.status === 200) {
                    var data = JSON.parse(xhr.responseText);
                    if (data.success) {
                        showModal('Detalle proveedor del producto guardado exitosamente.', 'success', true);
                    } else {
                        showModal('Error: ' + (data.error || 'No se pudo guardar el detalle proveedor del producto.'), 'danger');
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

    // Función para validar campos vacíos
    function validarCampos(form) {
        var camposInvalidos = [];
        Array.from(form.elements).forEach(function(campo) {
            if (campo.required && !campo.value.trim()) {
                camposInvalidos.push(campo.name || campo.placeholder || 'Campo sin nombre');
                campo.classList.add('is-invalid'); // Resaltar el campo vacío
            } else {
                campo.classList.remove('is-invalid'); // Remover el resaltado si el campo está completo
            }
        });
        return camposInvalidos;
    }

    // Función para mostrar el modal
    function showModal(message, type, showButtons = false) {
        // Eliminar cualquier modal existente
        var existingModal = document.getElementById('mensajeModal');
        if (existingModal) {
            existingModal.remove();
        }

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
                                <button id="seguirEditandoBtn" class="btn btn-primary btn-sm">Seguir editando</button>
                                <button id="listaSalidasBtn" class="btn btn-secondary btn-sm">Ir a la lista</button>
                            ` : ''}
                        </div>
                    </div>
                </div>
            </div>`;

        document.body.insertAdjacentHTML('beforeend', modalContent);
        $('#mensajeModal').modal('show');

        if (showButtons) {
            document.getElementById('seguirEditandoBtn').addEventListener('click', function() {
                location.reload();
            });
            document.getElementById('listaSalidasBtn').addEventListener('click', function() {
                window.location.href = '<?php echo $URL; ?>inventario/movimientos/entrada/index.php';
            });
        }
    }
</script>



