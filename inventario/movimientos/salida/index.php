
<?php

include ('../../../app/config.php');
include ('../../../layout/sesion.php');
include ('../../../layout/parte1.php');
?>


<!-- Panel nueva categoria -->
<div class="container-fluid">
    <?php include ('../layout/parte1.php');?>

    
    <div class="panel panel-info">
	<div><?php include ('lista.php'); ?></div>
        <div class="panel-heading">
            <h3 class="panel-title"><i class="zmdi zmdi-plus"></i> &nbsp; NUEVA SALIDA DE PRODUCTO</h3>
        </div>
        <div class="panel-body">
        <form id="nuevaSalidaProductoForm" autocomplete="off">
    <fieldset>
        <legend><i class="zmdi zmdi-assignment-o"></i> &nbsp; Información de la salida del producto</legend>
        <div class="container-fluid">
            <div class="row">
                <div class="col-xs-12">
                    <div class="form-group label-floating">
                        <label class="control-label">Técnico *</label>
                        <select class="form-control" name="tecnico-reg" required title="Seleccione un técnico.">
                            <option value="">Seleccione una opción</option>
                            <?php
                            $query = "SELECT t.ID_tecnico, p.nombre FROM tecnico t INNER JOIN personal p ON t.id_personal = p.ID_personal ORDER BY p.nombre";
                            $stmt = $pdo->query($query);
                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                echo "<option value='" . $row['ID_tecnico'] . "'>" . $row['nombre'] . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-6">
                    <div class="form-group label-floating">
                        <label class="control-label">Producto *</label>
                        <select class="form-control" name="producto-reg" required title="Seleccione un producto.">
                            <option value="">Seleccione una opción</option>
                            <?php
                            $query = "SELECT p.id_producto, p.nombre, tp.Nom_producto FROM productos p LEFT JOIN tipo_producto tp ON p.id_tipo_producto = tp.ID_tipo_producto ORDER BY p.nombre";
                            $stmt = $pdo->query($query);
                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                echo "<option value='" . $row['id_producto'] . "'>" . $row['nombre'] . " - " . $row['Nom_producto'] . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-6">
                    <div class="form-group label-floating">
                        <label class="control-label">Cantidad *</label>
                        <input pattern="[0-9]+" class="form-control" type="text" name="cantidad-reg" required title="Ingrese una cantidad válida (solo números).">
                    </div>
                </div>
                <div class="col-xs-12">
                    <div class="form-group label-floating">
                        <label class="control-label">Fecha de retiro *</label>
                        <input type="date" class="form-control" name="fecha-retiro-reg" required title="Ingrese una fecha de retiro válida." value="<?php echo date('Y-m-d');?>">
                    </div>
                </div>
                <div class="col-xs-12">
                    <div class="form-group label-floating">
                        <label class="control-label">Observación</label>
                        <textarea class="form-control" name="observacion-reg" rows="1" maxlength="250" title="Puede agregar una observación breve (máximo 250 caracteres)."></textarea>
                    </div>
                </div>
                <input type="hidden" name="id_usuario_sesion" value="<?php echo $id_usuario_sesion; ?>">
                
            </div>
        </div>
    </fieldset>
    <p class="text-center" style="margin-top: 20px;">
        <button type="button" id="guardarBtn" class="btn btn-info btn-raised btn-sm">
            <i class="zmdi zmdi-floppy"></i> Guardar
        </button>
    </p>
</form>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var form = document.getElementById('nuevaSalidaProductoForm');
        var guardarBtn = document.getElementById('guardarBtn');
        var camposModificados = false;

        // Detectar cambios en los campos del formulario
        Array.from(form.elements).forEach(function (campo) {
            campo.addEventListener('input', function () {
                camposModificados = true;
            });
        });

        // Manejar el clic del botón Guardar
        guardarBtn.addEventListener('click', function () {
            // Validar que los campos requeridos estén completos
            if (!form.checkValidity()) {
                showModal('Por favor, complete correctamente todos los campos requeridos.', 'danger');
                return;
            }

            // Verificar si se han realizado cambios en los campos
            if (!camposModificados) {
                showModal('No se han detectado cambios en los campos. No se guardó la salida de producto.', 'warning');
                return;
            }

            // Recorrer los campos y recopilar datos actualizados
            var formData = new FormData();
            Array.from(form.elements).forEach(function (campo) {
                if (campo.name) {
                    formData.append(campo.name, campo.value);
                }
            });

            // Enviar datos al servidor
            var xhr = new XMLHttpRequest();
            xhr.open('POST', '../../../app/controllers/inventario/salida/guardar_salida.php', true);
            xhr.onload = function () {
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

            xhr.onerror = function () {
                showModal('Error al enviar la solicitud. Verifique su conexión.', 'danger');
            };

            xhr.send(formData);
        });

        // Mostrar modal
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
                                    <button id="nuevaSalidaProductoBtn" class="btn btn-primary btn-sm">Agregar nueva salida de producto</button>
                                    <button id="listaSalidasBtn" class="btn btn-secondary btn-sm">Ir a la lista</button>
                                ` : ''}
                            </div>
                        </div>
                    </div>
                </div>`;

            document.body.insertAdjacentHTML('beforeend', modalContent);
            $('#mensajeModal').modal('show');

            if (showButtons) {
                document.getElementById('nuevaSalidaProductoBtn').addEventListener('click', function () {
                    form.reset();
                    camposModificados = false;
                    $('#mensajeModal').modal('hide');
                });
                document.getElementById('listaSalidasBtn').addEventListener('click', function () {
                    window.location.href = '<?php echo $URL; ?>inventario/movimientos/salida/index.php';
                });
            }
        }
    });
</script>

