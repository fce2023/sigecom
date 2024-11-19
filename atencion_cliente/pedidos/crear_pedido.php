<?php

include ('../../app/config.php');
include ('../../layout/sesion.php');
include ('../../layout/parte1.php');
?>
<!-- Panel nuevo cliente -->
<div class="container-fluid">
    <h1><i class="zmdi zmdi-male-alt zmdi-hc-fw"></i> Atención al Clientes</h1>
    <?php include ('../../layout/cliente.php'); ?>
    <div class="panel panel-primary">
    <div class="panel-heading">
            <h3 class="panel-title"><i class="zmdi zmdi-plus"></i> &nbsp; NUEVO PEDIDO</h3>
        </div>
        <div class="panel-body">
            <form id="nuevoPedidoForm">
                <fieldset>
                    <legend style="color: black;"><i class="zmdi zmdi-assignment-o"></i> &nbsp; Información del Pedido</legend>
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-xs-12 col-sm-6">
                                <div class="form-group label-floating">
                                    <label class="control-label">Cliente *</label>
                                    <input type="text" class="form-control" id="buscarCliente" onkeyup="buscarClientes(this.value)" placeholder="Buscar por nombre, apellido o dni">
                                    <select class="form-control" name="id_cliente-reg" id="listaClientes" required>
                                        <option value="">Seleccione cliente</option>
                                        <?php
                                        $query = "SELECT ID_cliente, Dni, Nombre, Apellido_paterno, Apellido_materno FROM cliente";
                                        $stmt = $pdo->query($query);
                                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                            echo "<option value='" . $row['ID_cliente'] . "'>" . $row['Dni'] . " - " . $row['Nombre'] . " " . $row['Apellido_paterno'] . " " . $row['Apellido_materno'] . "</option>";
                                        }
                                        ?>
                                    </select>
                                    <div id="clienteNoExiste" style="color: red; display: none;">Cliente aún no existe</div>
                                </div>
                            </div>

                            <script>
                            function buscarClientes(nombre) {
                                var filter = nombre.toUpperCase();
                                var ul = document.getElementById("listaClientes");
                                var li = ul.getElementsByTagName("option");
                                var clienteEncontrado = false;
                                for (var i = 0; i < li.length; i++) {
                                    var a = li[i].textContent || li[i].innerText;
                                    if (a.toUpperCase().indexOf(filter) > -1) {
                                        li[i].style.display = "";
                                        if (!clienteEncontrado) {
                                            ul.value = li[i].value;
                                            clienteEncontrado = true;
                                        }
                                    } else {
                                        li[i].style.display = "none";
                                    }
                                }
                                var mensaje = document.getElementById("clienteNoExiste");
                                if (clienteEncontrado) {
                                    ul.style.display = '';
                                    mensaje.style.display = 'none';
                                } else {
                                    ul.style.display = 'none';
                                    mensaje.style.display = '';
                                }
                            }
                            </script>

                            <div class="col-xs-12 col-sm-6">
                                <div class="form-group label-floating">
                                    <label class="control-label">ID del tipo de servicio *</label>
                                    <select class="form-control" name="ID_tipo_servicio-reg" required title="Seleccione un tipo de servicio.">
                                        <option value="">Seleccione una opción</option>
                                        <?php
                                        $query = "SELECT ID_tipo_servicio, Nom_servicio FROM tipo_servicio ORDER BY Nom_servicio";
                                        $stmt = $pdo->query($query);
                                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                            echo "<option value='" . $row['ID_tipo_servicio'] . "'>" . $row['Nom_servicio'] . "</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <div class="col-xs-12 col-sm-6">
                                <div class="form-group label-floating">
                                    <label class="control-label">C&oacute;digo de operaci&oacute;n *</label>
                                    <?php
                                    $prefijo = '41';
                                    $sufijo = str_pad(rand(0, 999), 3, '0', STR_PAD_LEFT);
                                    $codigo = $prefijo . $sufijo;
                                    ?>
                                    <input 
                                        class="form-control" 
                                        type="text" 
                                        name="Codigo_Operacion-reg" 
                                        value="<?php echo $codigo; ?>" 
                                        required 
                                        maxlength="50"
                                        title="Ingrese el c&oacute;digo de operaci&oacute;n (hasta 50 caracteres)."
                                        readonly
                                    >
                                </div>
                            </div>

                            <div class="col-xs-12 col-sm-6">
                                <div class="form-group label-floating">
                                    <label class="control-label">Fecha de creaci&oacute;n *</label>
                                    <input 
                                        type="date" 
                                        class="form-control" 
                                        name="fecha_creacion-reg" 
                                        required 
                                        title="Ingrese una fecha de creaci&oacute;n válida."
                                        value="<?php echo date('Y-m-d');?>"
                                    >
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6">
                                <div class="form-group label-floating">
                                    <label class="control-label">Estado *</label>
                                    <select class="form-control" name="estado-reg" required title="Seleccione un estado.">
                                        <option value="">Seleccione una opción</option>
                                        <?php
                                        $query = "SELECT id, nombre FROM estado_atencion_cliente";
                                        $stmt = $pdo->query($query);
                                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                            echo "<option value='" . $row['id'] . "'>" . $row['nombre'] . "</option>";
                                        }
                                        ?>
                                    </select>
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

            <style>
                .is-invalid {
                    border-color: #dc3545;
                    padding-right: calc(1.5em + 0.75rem);
                    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='none' stroke='#dc3545' viewBox='0 0 12 12'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='#dc3545' stroke='none'/%3e%3c/svg%3e");
                    background-repeat: no-repeat;
                    background-position: right calc(0.375em + 0.1875rem) center;
                    background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
                }
            </style>
<script>
    document.getElementById('guardarBtn').addEventListener('click', function() {
        var form = document.getElementById('nuevoPedidoForm');
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
        xhr.open('POST', '../../app/controllers/atencion_cliente/pedidos/guardar_pedido.php', true);
        xhr.onload = function() {
            try {
                if (xhr.status === 200) {
                    var data = JSON.parse(xhr.responseText);
                    if (data.success) {
                        showModal('Cliente guardado exitosamente.', 'success', true);
                    } else {
                        showModal('Error: ' + (data.error || 'No se pudo guardar el cliente.'), 'danger');
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
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            ${message}
                        </div>
                        <div class="modal-footer">
                            ${showButtons ? `
                                <button id="nuevoClienteBtn" class="btn btn-primary btn-sm">Agregar nuevo cliente</button>
                                <button id="listaClientesBtn" class="btn btn-secondary btn-sm">Ir a la lista</button>
                            ` : ''}
                        </div>
                    </div>
                </div>
            </div>`;

        document.body.insertAdjacentHTML('beforeend', modalContent);
        $('#mensajeModal').modal('show');

        if (showButtons) {
            document.getElementById('nuevoClienteBtn').addEventListener('click', function() {
                document.getElementById('nuevoPedidoForm').reset();
                $('#mensajeModal').modal('hide');
            });
            document.getElementById('listaClientesBtn').addEventListener('click', function() {
                window.location.href = '<?php echo $URL; ?>atencion_cliente/pedidos/lista_pedidos.php';
            });
        }
    }
</script>


