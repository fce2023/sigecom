<?php

include('../app/config.php');
include('../layout/sesion.php');
include('../layout/parte1.php');
include('../layout/tecnico.php');

?>







<!-- Panel nuevo cliente -->
<div class="container-fluid">



    <div class="panel panel-primary">
        <H1>DESIGNAR T&Eacute;CNICO</H1>

        <div class="panel-body">

            <form id="nuevoDesignarTecnicoForm">
    <fieldset>
        <legend style="color: black;"><i class="zmdi zmdi-assignment-o"></i> &nbsp; Información del cliente</legend>
        <div class="container-fluid">
            <div class="row">
                <div class="col-xs-12 col-sm-6">
                    <div class="form-group label-floating">
                        <label class="control-label">Cliente *</label>
                        <input type="text" class="form-control" id="buscarCliente" onkeyup="buscarClientes(this.value)" placeholder="Buscar por nombre, apellido o dni">
                        <select class="form-control" name="id_atencion_cliente-reg" id="listaClientes" required onchange="llenarDatosCliente(this.value)">
                            <?php
                            $query = "SELECT ac.ID, c.Dni, c.Nombre, c.Apellido_paterno, c.Apellido_materno, ac.Codigo_Operacion, t.Nom_servicio 
                                      FROM cliente c
                                      LEFT JOIN atencion_cliente ac ON c.ID_cliente = ac.id_cliente
                                      LEFT JOIN tipo_servicio t ON ac.ID_tipo_servicio = t.ID_tipo_servicio
                                      WHERE ac.id_cliente IS NOT NULL AND c.Estado = 1";
                            $stmt = $pdo->query($query);
                            echo $stmt->rowCount() > 0 ? "<option value=''>Seleccione Cliente</option>" : "<option value='' disabled selected>No hay cliente registrado o están inactivos</option>";
                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                echo "<option value='" . $row['ID'] . "'>" . $row['Dni'] . " - " . $row['Nombre'] . " " . $row['Apellido_paterno'] . " " . $row['Apellido_materno'] . " - " . $row['Nom_servicio'] . "</option>";
                            }
                            ?>
                        </select>
                        <div id="clienteNoExiste" style="color: red; display: none;">Cliente aún no existe</div>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-6">
                    <div class="form-group label-floating">
                        <label class="control-label">Tipo Servicio *</label>
                        <select class="form-control" name="tipo_servicio-reg" id="tipo_servicio-reg" disabled>
                            <option value="">Seleccione un tipo de servicio</option>
                            <?php
                            $query = "SELECT ID_tipo_servicio, Nom_servicio FROM tipo_servicio WHERE Estado = 1";
                            $stmt = $pdo->query($query);
                            echo $stmt->rowCount() > 0 ? "" : "<option value='' disabled selected>No hay tipo de servicio registrado o están inactivos</option>";
                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                echo "<option value='" . $row['ID_tipo_servicio'] . "'>" . $row['Nom_servicio'] . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-6">
                    <div class="form-group label-floating">
                        <label class="control-label">C&oacute;digo de cliente</label>
                        <input type="text" class="form-control" name="codigo_cliente-reg" id="codigo_cliente-reg" title="Ingrese el c&oacute;digo de cliente (hasta 50 caracteres)." readonly>
                    </div>
                </div>

                <script>
                    function llenarDatosCliente(id_atencion_cliente) {
                        var xhr = new XMLHttpRequest();
                        xhr.onreadystatechange = function() {
                            if (xhr.readyState == 4 && xhr.status == 200) {
                                var response = JSON.parse(xhr.responseText);
                                if (response.success) {
                                    document.getElementById("codigo_cliente-reg").value = response.codigo_operacion;
                                    var tipo_servicio_select = document.getElementById("tipo_servicio-reg");
                                    for (var i = 0; i < tipo_servicio_select.options.length; i++) {
                                        if (tipo_servicio_select.options[i].value == response.id_tipo_servicio) {
                                            tipo_servicio_select.selectedIndex = i;
                                            break;
                                        }
                                    }
                                    document.getElementById("tipo_servicio-reg").disabled = false;
                                } else {
                                    document.getElementById("codigo_cliente-reg").value = '';
                                    document.getElementById("tipo_servicio-reg").value = '';
                                    document.getElementById("tipo_servicio-reg").disabled = true;
                                }
                            }
                        };
                        xhr.open("GET", "obtener_codigo_operacion.php?id_atencion_cliente=" + id_atencion_cliente, true);
                        xhr.send();
                    }

                    document.getElementById('listaClientes').addEventListener('change', function() {
                        if (this.value === '') {
                            document.getElementById("codigo_cliente-reg").value = '';
                            document.getElementById("codigo_cliente-reg").disabled = true;
                            document.getElementById("tipo_servicio-reg").value = '';
                            document.getElementById("tipo_servicio-reg").disabled = true;
                        } else {
                            llenarDatosCliente(this.value);
                            document.getElementById("codigo_cliente-reg").disabled = false;
                        }
                    });
                </script>

                <legend style="color: black;"><i class="zmdi zmdi-assignment-o"></i> &nbsp; Información del Tecnico</legend>
                <div class="col-xs-12 col-sm-6">
                    <div class="form-group label-floating">
                        <label class="control-label">Técnico *</label>
                        <input type="text" class="form-control" id="buscarTecnico" onkeyup="buscarTecnicos(this.value)" placeholder="Buscar por nombre, apellido o dni">
                        <select class="form-control" name="id_tecnico-reg" id="listaTecnicos" required onchange="llenarDatosTecnico(this.value)">
                            <?php
                            $query = "SELECT t.ID_tecnico, p.Dni, p.Nombre, p.Apellido_paterno, p.Apellido_materno 
                                      FROM tecnico t
                                      INNER JOIN personal p ON t.id_personal = p.ID_personal
                                      WHERE t.estado = 1";
                            $stmt = $pdo->query($query);
                            echo $stmt->rowCount() > 0 ? "<option value=''>Seleccione Técnico</option>" : "<option value='' disabled selected>No hay técnicos registrados o están inactivos</option>";
                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                echo "<option value='" . $row['ID_tecnico'] . "'>" . $row['Dni'] . " - " . $row['Nombre'] . " " . $row['Apellido_paterno'] . " " . $row['Apellido_materno'] . "</option>";
                            }
                            ?>
                        </select>
                        <div id="tecnicoNoExiste" style="color: red; display: none;">Técnico aún no existe</div>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-6">
                    <div class="form-group label-floating">
                        <label class="control-label">Código</label>
                        <input type="text" class="form-control" name="codigo-reg" id="codigo-reg" title="Ingrese el código (hasta 50 caracteres)." readonly>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-6">
                    <div class="form-group label-floating">
                        <label class="control-label">Observación</label>
                        <input type="text" class="form-control" name="observacion-reg" id="observacion-reg" title="Ingrese la observación (hasta 50 caracteres).">
                    </div>
                </div>

                <div class="col-xs-12 col-sm-6">
                    <div class="form-group label-floating">
                        <label class="control-label">Fecha de creaci&oacute;n *</label>
                        <input type="date" class="form-control" name="fecha_creacion-reg" required title="Ingrese una fecha de creaci&oacute;n válida." value="<?php echo date('Y-m-d'); ?>">
                    </div>
                </div>

                <div class="col-xs-12 col-sm-6">
                    <div class="form-group label-floating">
                        <label class="control-label">Estado </label>
                        <select class="form-control" name="estado-reg" id="estado-reg" readonly="readonly" style="pointer-events: none;">
                            <option value="1" selected>Activo</option>
                            <option value="0">Inactivo</option>
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
               

           


                




     




                // Función para obtener el código del técnico
                function obtenerCodigoTecnico(id_tecnico) {
                    console.log("Obteniendo código del técnico con id: " + id_tecnico);
                    return new Promise(function(resolve, reject) {
                        var xhr = new XMLHttpRequest();
                        xhr.onreadystatechange = function() {
                            if (xhr.readyState == 4 && xhr.status == 200) {
                                var response = JSON.parse(xhr.responseText);
                                if (response.success) {
                                    resolve(response.codigo);
                                } else {
                                    reject();
                                }
                            }
                        };
                        xhr.open("GET", "obtener_codigo_tecnico.php?id=" + id_tecnico, true);
                        xhr.send();
                    });
                }

                // Escucha el cambio en el select de técnico y actualiza los campos
                document.getElementById('listaTecnicos').addEventListener('change', function() {
                    if (this.value === '') {
                        document.getElementById("codigo-reg").value = '';
                    } else {
                        obtenerCodigoTecnico(this.value).then(function(codigo) {
                            document.getElementById("codigo-reg").value = codigo;
                        }).catch(function() {
                            document.getElementById("codigo-reg").value = '';
                        });
                    }
                });

                // Escucha el input del campo de texto "buscarTecnico" y busca en la lista de técnico
                document.getElementById('buscarTecnico').addEventListener('input', function() {
                    var nombre = this.value;
                    var filter = nombre.toUpperCase();
                    var ul = document.getElementById("listaTecnicos");
                    var li = ul.getElementsByTagName("option");
                    var tecnicoEncontrado = false;
                    for (var i = 0; i < li.length; i++) {
                        var a = li[i].textContent || li[i].innerText;
                        if (a.toUpperCase().indexOf(filter) > -1) {
                            li[i].style.display = "";
                            if (!tecnicoEncontrado) {
                                ul.value = li[i].value;
                                tecnicoEncontrado = true;
                                obtenerCodigoTecnico(li[i].value).then(function(codigo) {
                                    document.getElementById("codigo-reg").value = codigo;
                                }).catch(function() {
                                    document.getElementById("codigo-reg").value = '';
                                });
                            }
                        } else {
                            li[i].style.display = "none";
                        }
                    }
                    var mensaje = document.getElementById("tecnicoNoExiste");
                    if (tecnicoEncontrado) {
                        ul.style.display = '';
                        mensaje.style.display = 'none';
                    } else {
                        ul.style.display = 'none';
                        mensaje.style.display = '';
                        document.getElementById("codigo-reg").value = '';
                    }
                });



               


                document.getElementById('nuevoDesignarTecnicoForm').addEventListener('submit', function(event) {
                    event.preventDefault();
                    var xhr = new XMLHttpRequest();
                    xhr.open('POST', '../app/controllers/designar_tecnico/guardar.php', true);
                    xhr.onload = function() {
                        try {
                            if (xhr.status === 200) {
                                var data = JSON.parse(xhr.responseText);
                                if (data.success) {
                                    showModal('Tecnico designado correctamente.', 'success', true);
                                } else {
                                    showModal('Error: ' + (data.error || 'No se pudo designar al técnico.'), 'danger');
                                }
                            } else {
                                showModal('Error en la conexión al servidor. Código de estado: ' + xhr.status, 'danger');
                            }
                        } catch (error) {
                            showModal('Error al procesar la respuesta del servidor: ' + error, 'danger');
                        }
                    };

                    var formData = new FormData(this);
                    xhr.send(formData);
                });


                // Función para mostrar el modal y redirigir automáticamente a la lista
                function showModal(message) {
                    var modalContent = `
            <div class="modal" id="mensajeModal" tabindex="-1" role="dialog" aria-labelledby="mensajeModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="mensajeModalLabel">Mensaje</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            ${message}
                        </div>
                    </div>
                </div>
            </div>`;

                    document.body.insertAdjacentHTML('beforeend', modalContent);
                    $('#mensajeModal').modal({
                        backdrop: 'static',
                        keyboard: false,
                        show: true
                    });

                    setTimeout(function() {
                        $('#mensajeModal').modal('hide');
                        window.location.href = '<?php echo $URL; ?>/tecnico/lista_tecnicos_asignados.php';
                    }, 2000); // Adjust the delay as needed
                }
            </script>