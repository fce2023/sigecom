
<?php

include ('../../../app/config.php');
include ('../../../layout/sesion.php');
include ('../../../layout/parte1.php');
?>


<!-- Panel nueva categoria -->
<div class="container-fluid">
    <?php include ('../layout/parte1.php');?>
    <div><?php include ('lista.php'); ?></div>
    <div class="panel panel-info">
        <div class="panel-heading">
            <h3 class="panel-title"><i class="zmdi zmdi-plus"></i> &nbsp; NUEVA ENTRADA DE PRODUCTO</h3>
        </div>
        <div class="panel-body">
        <form id="nuevaEntradaProductoForm">
    <fieldset>
        <legend><i class="zmdi zmdi-assignment-o"></i> &nbsp; Información de la entrada del producto</legend>
        <div class="container-fluid">
            <div class="row">
                <div class="col-xs-12">
                    <div class="form-group label-floating">
                        <label class="control-label">Proveedor *</label>
                        <select class="form-control" name="proveedor-reg" required title="Seleccione un proveedor.">
                            <option value="">Seleccione una opción</option>
                            <?php
                            $query = "SELECT ID_proveedor, Nombre FROM proveedor ORDER BY Nombre";
                            $stmt = $pdo->query($query);
                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                echo "<option value='" . $row['ID_proveedor'] . "'>" . $row['Nombre'] . "</option>";
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
                        <label class="control-label">Fecha de abastecimiento *</label>
                        <input type="date" class="form-control" name="fecha-abastecimiento-reg" required title="Ingrese una fecha de abastecimiento válida." value="<?php echo date('Y-m-d');?>">
                    </div>
                </div>
                <div class="col-xs-12">
                    <div class="form-group label-floating">
                        <label class="control-label">Observación</label>
                        <textarea class="form-control" name="observacion-reg" rows="1" maxlength="250" title="Puede agregar una observación breve (máximo 250 caracteres)."></textarea>
                    </div>
                </div>
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
    document.getElementById('guardarBtn').addEventListener('click', function() {
        var form = document.getElementById('nuevaEntradaProductoForm');
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
        xhr.open('POST', '../../../app/controllers/inventario/entrada/guardar_entrada.php', true);
        xhr.onload = function() {
            try {
                if (xhr.status === 200) {
                    var data = JSON.parse(xhr.responseText);
                    if (data.success) {
                        showModal('Entrada de producto guardada exitosamente.', 'success', true);
                    } else {
                        showModal('Error: ' + (data.error || 'No se pudo guardar la entrada del producto.'), 'danger');
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
                                <button id="nuevaEntradaProductoBtn" class="btn btn-primary btn-sm">Agregar nueva entrada de producto</button>
                                <button id="listaEntradasBtn" class="btn btn-secondary btn-sm">Ir a la lista</button>
                            ` : ''}
                        </div>
                    </div>
                </div>
            </div>`;

        document.body.insertAdjacentHTML('beforeend', modalContent);
        $('#mensajeModal').modal('show');

        if (showButtons) {
            document.getElementById('nuevaEntradaProductoBtn').addEventListener('click', function() {
                document.getElementById('nuevaEntradaProductoForm').reset();
                $('#mensajeModal').modal('hide');
            });
            document.getElementById('listaEntradasBtn').addEventListener('click', function() {
                window.location.href = '<?php echo $URL; ?>inventario/movimientos/entrada/index.php';
            });
        }
    }
</script>
