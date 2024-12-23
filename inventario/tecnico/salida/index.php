
<?php

include ('../../../app/config.php');
include ('../../../layout/sesion.php');
include ('../../../layout/parte1.php');
?>

<!-- Panel nueva categoria -->
<div class="container-fluid">
    <?php include ('../layout/parte1.php');?>
    <div class="panel panel-info">
	
        <div class="panel-heading">
            <h3 class="panel-title"><i class="zmdi zmdi-plus"></i> &nbsp; NUEVA SALIDA DE PRODUCTO</h3>
        </div>
        <div class="panel-body">

        <form id = "nuevaSalidaProductoForm">
    <fieldset>
        <legend><i class="zmdi zmdi-assignment-o"></i> &nbsp; Información de la salida del producto</legend>
        <div class="container-fluid">
                <div class="row">
                        <div class="col-xs-12">
                            <div class="form-group label-floating">
                                <label class="control-label">Técnico *</label>
                                <select class="form-control" name="tecnico-reg" id="listaTecnicos" required title="Seleccione un técnico." onchange="filtrarClientesPorTecnico(this.value)">
                                    <option value="">Seleccione tu nombre para que aparesca tus clientes asignados</option>
                                    <?php
                                    $query = "SELECT t.ID_tecnico, p.nombre FROM tecnico t INNER JOIN personal p ON t.id_personal = p.ID_personal WHERE t.ID_tecnico = $id_tecnico_sesion ORDER BY p.nombre";
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
                                <label class="control-label">Cliente *</label>
                                <input type="text" class="form-control" id="buscarCliente" onkeyup="buscarClientes(this.value)" placeholder="Buscar por nombre, apellido o dni">
                                <select class="form-control" name="id_cliente-reg" id="listaClientes" required>
                                    <option value="" disabled selected>Clientes relacionados al técnico</option>
                                </select>
                                <div id="clienteNoExiste" style="color: red; display: none;">Cliente aún no existe</div>
                            </div>
                        </div>
                    </div>
                </div>
            </fieldset>


        <script>
        function filtrarClientesPorTecnico(idTecnico) {
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4) {
                    if (xhr.status == 200) {
                        var respuesta = xhr.responseText;
                        try {
                            var clientes = JSON.parse(respuesta);
                            var listaClientes = document.getElementById('listaClientes');
                            listaClientes.innerHTML = "<option value=''>Seleccione Cliente</option>";
                            if (clientes.error) {
                                alert(clientes.error);
                            } else if (clientes.length > 0) {
                                clientes.forEach(function(cliente) {
                                    var option = document.createElement('option');
                                    option.value = cliente.ID_cliente;
                                    option.text = cliente.Dni + " - " + cliente.Nombre + " " + cliente.Apellido_paterno + " " + cliente.Apellido_materno + " - " + cliente.Nom_servicio;
                                    option.text = cliente.Dni + " - " + cliente.Nombre + " " + cliente.Apellido_paterno + " " + cliente.Apellido_materno;
                                    listaClientes.add(option);
                                });
                            } else {
                                var option = document.createElement('option');
                                option.value = '';
                                option.text = 'No hay cliente relacionado';
                                option.disabled = true;
                                listaClientes.add(option);
                            }
                        } catch (e) {
                            alert("Error al recibir respuesta del servidor: " + e.message);
                        }
                    } else {
                        alert("Error al conectar con el servidor: " + xhr.statusText);
                    }
                }
            };
            xhr.open("GET", "obtener_clientes_por_tecnico.php?id_tecnico=" + idTecnico, true);
            xhr.send();
        }

        </script>
                <div class="col-xs-12 col-sm-6">
                <div class="form-group label-floating">
                        <label class="control-label">Producto *</label>
                        <select class="form-control" name="producto-reg" required title="Seleccione un producto.">
            <option value="">Seleccione una opción</option>
            <?php
            // Consulta para calcular el stock
            $query = "SELECT p.id_producto, p.nombre, tp.Nom_producto, 
                      COALESCE((SELECT SUM(dpp.cantidad) FROM detalle_producto_proveedor dpp WHERE dpp.ID_producto = p.id_producto), 0) + 
                      COALESCE((SELECT SUM(dtp.cantidad) FROM detalle_tecnico_producto dtp WHERE dtp.ID_producto = p.id_producto AND dtp.tipo_movimiento = 'Entrada'), 0) - 
                      COALESCE((SELECT SUM(dtp.cantidad) FROM detalle_tecnico_producto dtp WHERE dtp.ID_producto = p.id_producto AND dtp.tipo_movimiento = 'Salida'), 0) AS stock
                      FROM productos p
                      LEFT JOIN tipo_producto tp ON p.id_tipo_producto = tp.ID_tipo_producto
                      GROUP BY p.id_producto
                      ORDER BY p.nombre";

            $stmt = $pdo->query($query);
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $stock = (int) $row['stock'];
                $disabled = $stock <= 0 ? 'disabled' : '';
                $class = $stock <= 0 ? 'style="color: red;"' : '';
                echo "<option value='" . $row['id_producto'] . "' $disabled $class>" . htmlspecialchars($row['nombre']) . " - " . htmlspecialchars($row['Nom_producto']) . ($stock <= 0 ? " (Sin stock)" : " (Stock: $stock)") . "</option>";
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
        <button type="submit" id="guardarBtn" class="btn btn-info btn-raised btn-sm">
            <i class="zmdi zmdi-floppy"></i> Guardar
        </button>
    </p>
</form>

<script>
    document.getElementById('nuevaSalidaProductoForm').addEventListener('submit', function(event) {
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
        xhr.open('POST', '../../../app/controllers/inventario/salida/guardar_salida_tecnico.php', true);
        xhr.onload = function() {
            try {
                var data = JSON.parse(xhr.responseText);
                if (data.success) {
                    showModal(data.message, 'success', true);
                } else {
                    showModal(data.message || 'No se pudo guardar el detalle técnico del producto.', 'danger');
                }
            } catch (error) {
                showModal('Error al procesar la respuesta del servidor: ' + (error.message.indexOf('Unexpected token') > -1 ? 'La respuesta del servidor no es un JSON válido.' : error.message), 'danger');
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
                                <button id="seguirEditandoBtn" class="btn btn-primary btn-sm">Agregar otra salida</button>
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
                document.getElementById('nuevaSalidaProductoForm').reset();
                $('#mensajeModal').modal('hide');
            });
            document.getElementById('listaSalidasBtn').addEventListener('click', function() {
                window.location.href = '<?php echo $URL; ?>inventario/tecnico/salida/lista.php';
            });
        }
    }
</script>

