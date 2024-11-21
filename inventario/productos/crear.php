<?php

include ('../../app/config.php');
include ('../../layout/sesion.php');
include ('../../layout/parte1.php');
?>

<!-- Panel nueva categoria -->
<div class="container-fluid">
    <?php include ('layout/parte1.php');?>
    <div class="panel panel-info">
        <div class="panel-heading">
            <h3 class="panel-title"><i class="zmdi zmdi-plus"></i> &nbsp; NUEVO PRODUCTO</h3>
        </div>
        <div class="panel-body">
        <form id="nuevoProductoForm">
    <fieldset>
        <legend><i class="zmdi zmdi-assignment-o"></i> &nbsp; Información del producto</legend>
        <div class="container-fluid">
            <div class="row">
                <div class="col-xs-12">
                    <div class="form-group label-floating">
                        <label class="control-label">Nombre *</label>
                        <input 
                            pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,100}" 
                            class="form-control" 
                            type="text" 
                            name="nombre-reg" 
                            required 
                            maxlength="100"
                            title="Ingrese solo letras y espacios (hasta 100 caracteres)."
                        >
                    </div>
                </div>
                <div class="col-xs-12">
                    <div class="form-group label-floating">
                        <label class="control-label">Descripción</label>
                        <textarea 
                            class="form-control" 
                            name="descripcion-reg" 
                            rows="3"
                            maxlength="255"
                            title="Puede agregar una descripción breve (máximo 255 caracteres)."
                        ></textarea>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-sm-6">
                    <div class="form-group label-floating">
                        <label class="control-label">Precio *</label>
                        <input 
                            pattern="^\d+(\.\d{1,2})?$" 
                            class="form-control" 
                            type="text" 
                            name="precio-reg" 
                            required 
                            maxlength="10"
                            title="Ingrese un precio válido (máximo 2 decimales)."
                        >
                    </div>
                </div>
                <div class="col-xs-12 col-sm-6">
                    <div class="form-group label-floating">
                        <label class="control-label">Tipo de producto</label>
                        <select 
                            class="form-control" 
                            name="tipo-reg" 
                            required
                            title="Seleccione un tipo de producto."
                        >
                            <option value="">Seleccione una opción</option>
                            <?php
                            $query = "SELECT ID_tipo_producto, Nom_producto FROM tipo_producto ORDER BY ID_tipo_producto";
                            $stmt = $pdo->query($query);
                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                echo "<option value='" . $row['ID_tipo_producto'] . "'>" . $row['Nom_producto'] . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </fieldset>
    <p class="text-center" style="margin-top: 20px;">
        <button type="submit" id="guardarBtn" class="btn btn-info btn-raised btn-sm">
            <i class="zmdi zmdi-floppy"></i> Guardar
        </button>
    </p>
</form>

<script>
    document.getElementById('nuevoProductoForm').addEventListener('submit', function(event) {
        event.preventDefault();
        var form = this;
        var camposInvalidos = validarCampos(form);

        if (camposInvalidos.length > 0) {
            var mensaje = 'Por favor, complete los siguientes campos requeridos:<br>';
            mensaje += camposInvalidos.map(campo => `- ${campo}`).join('<br>');
            showModal(mensaje, 'danger');
            return;
        }

        var formData = new FormData(form);

        var xhr = new XMLHttpRequest();
        xhr.open('POST', '../../app/controllers/inventario/productos/guardar.php', true);
        xhr.onload = function() {
            try {
                if (xhr.status === 200) {
                    var data = JSON.parse(xhr.responseText);
                    if (data.success) {
                        showModal(data.message, 'success', true); // Show server message
                    } else {
                        showModal('Error: ' + (data.message || 'No se pudo guardar el producto.'), 'danger');
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
                                <button id="seguirEditandoBtn" class="btn btn-primary btn-sm">Agregar otro producto</button>
                                <button id="listaProductosBtn" class="btn btn-secondary btn-sm">Ir a la lista</button>
                            ` : ''}
                        </div>
                    </div>
                </div>
            </div>`;

        document.body.insertAdjacentHTML('beforeend', modalContent);
        $('#mensajeModal').modal('show');

        if (showButtons) {
            document.getElementById('seguirEditandoBtn').addEventListener('click', function() {
                document.getElementById('nuevoProductoForm').reset();
                $('#mensajeModal').modal('hide');
            });
            document.getElementById('listaProductosBtn').addEventListener('click', function() {
                window.location.href = '<?php echo $URL; ?>/inventario/productos/lista.php';
            });
        }
    }
</script>

