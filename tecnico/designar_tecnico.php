
<?php

include ('../app/config.php');
include ('../layout/sesion.php');
include ('../layout/parte1.php');
include ('../layout/tecnico.php');

?>



<!-- Panel nuevo cliente -->
<div class="container-fluid">
    
    <div class="panel panel-primary">
    <H1>DESIGNAR T&Eacute;CNICO</H1>
        <div class="panel-body">
        <!-- <form id="nuevoTecnicoForm"> -->
            <form  method="post" action="../app/controllers/designar_tecnico/guardar.php">
                <fieldset>
                    <legend style="color: black;"><i class="zmdi zmdi-assignment-o"></i> &nbsp; Información del cliente</legend>
                    <div class="container-fluid">
                        <div class="row">

                            <!-- tambien para que con el id_cliente del cliente seleccionado
                              consultar a al tabla atencion_cliente y 
                              trer el Codigo_operacion y hubicarla en codigo-cliente-reg  -->

                            <div class="col-xs-12 col-sm-6">
                                <div class="form-group label-floating">
                                    <label class="control-label">Cliente *</label>
                                    <input type="text" class="form-control" id="buscarCliente" onkeyup="buscarClientes(this.value)" placeholder="Buscar por nombre, apellido o dni">
                                    <select class="form-control" name="id_cliente-reg" id="listaClientes" required onchange="llenarDatosCliente(this.value)">
                                        <?php
                                        $query = "SELECT c.ID_cliente, c.Dni, c.Nombre, c.Apellido_paterno, c.Apellido_materno, ac.Codigo_Operacion FROM cliente c
                                                  LEFT JOIN atencion_cliente ac ON c.ID_cliente = ac.id_cliente
                                                  WHERE ac.id_cliente IS NOT NULL AND c.Estado = 1";
                                        $stmt = $pdo->query($query);
                                        if ($stmt->rowCount() > 0) {
                                            echo "<option value=''>Seleccione Cliente</option>";
                                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                                echo "<option value='" . $row['ID_cliente'] . "'>" . $row['Dni'] . " - " . $row['Nombre'] . " " . $row['Apellido_paterno'] . " " . $row['Apellido_materno'] . "</option>";
                                            }
                                        } else {
                                            echo "<option value='' disabled selected>No hay cliente registrado o estan inactivos</option>";
                                        }
                                        ?>
                                    </select>
                                    <div id="clienteNoExiste" style="color: red; display: none;">Cliente aún no existe</div>
                                </div>
                            </div>


                            <div class="col-xs-12 col-sm-6">
                                <div class="form-group label-floating">
                                    <label class="control-label">Tipo Servicio *</label>
                                    <select class="form-control" name="tipo_servicio-reg" id="tipo_servicio-reg" readonly="readonly" style="pointer-events: none;">
                                        <option value="">Seleccione un tipo de servicio</option>
                                        <?php
                                        $query = "SELECT ID_tipo_servicio, Nom_servicio FROM tipo_servicio WHERE Estado = 1";
                                        $stmt = $pdo->query($query);
                                        if ($stmt->rowCount() > 0) {
                                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                                echo "<option value='" . $row['ID_tipo_servicio'] . "'>" . $row['Nom_servicio'] . "</option>";
                                            }
                                        } else {
                                            echo "<option value='' disabled selected>No hay tipo de servicio registrado o estan inactivos</option>";
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

                            <div class="col-xs-12 col-sm-6">
                            <legend style="color: black;"><i class="zmdi zmdi-assignment-o"></i> &nbsp; Información del Tecnico</legend>
                                <div class="form-group label-floating">
                                    <label class="control-label">Tecnico *</label>
                                    <input type="text" class="form-control" id="buscarPersonal" onkeyup="buscarPersonales(this.value)" placeholder="Buscar por nombre, apellido o dni">
                                    <select class="form-control" name="id_personal-reg" id="listaPersonales" required onchange="llenarDatosPersonal(this.value)">
                                        <?php
                                        $query = "SELECT p.ID_personal, p.Dni, p.Nombre, p.Apellido_paterno, p.Apellido_materno FROM personal p
                                                INNER JOIN tecnico t ON p.ID_personal = t.id_personal
                                                WHERE p.Estado = 1";
                                        $stmt = $pdo->query($query);
                                        if ($stmt->rowCount() > 0) {
                                            echo "<option value=''>Seleccione Tecnico</option>";
                                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                                echo "<option value='" . $row['ID_personal'] . "'>" . $row['Dni'] . " - " . $row['Nombre'] . " " . $row['Apellido_paterno'] . " " . $row['Apellido_materno'] . "</option>";
                                            }
                                        } else {
                                            echo "<option value='' disabled selected>No hay personal registrado o estan inactivos</option>";
                                        }
                                        ?>
                                    </select>
                                    <div id="personalNoExiste" style="color: red; display: none;">Personal aún no existe</div>
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
             <!--    <p class="text-center" style="margin-top: 20px;">
                    <button type="button" id="guardarBtn" class="btn btn-info btn-raised btn-sm">
                        <i class="zmdi zmdi-floppy"></i> Guardar
                    </button>
                </p>
 -->
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
    document.getElementById('guardarBtn').addEventListener('click', function() {
        var form = document.getElementById('nuevoTecnicoForm');
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
        xhr.open('POST', '../app/controllers/tecnico/guardar_tecnico.php', true);
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
                var form = document.getElementById('nuevoTecnicoForm');
                form.reset();
                $('#mensajeModal').modal('hide');
                location.reload();
            });
            document.getElementById('listaClientesBtn').addEventListener('click', function() {
                window.location.href = '<?php echo $URL; ?>atencion_cliente/tecnico/lista_tecnicos.php';
            });
        }
    }





// Funcion para obtener codigo de operacion
function llenarDatosCliente(id_cliente) {
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            var response = JSON.parse(xhr.responseText);
            if (response.success) {
                var id_tipo_servicio = response.id_tipo_servicio;
                var nom_servicio = response.nom_servicio;
                document.getElementById("codigo_cliente-reg").value = response.codigo_operacion;
                document.getElementById("tipo_servicio-reg").value = id_tipo_servicio;
                document.getElementById("tipo_servicio-reg").setAttribute('data-id', id_tipo_servicio);
                
            } else {
                document.getElementById("codigo_cliente-reg").value = '';
                document.getElementById("tipo_servicio-reg").value = '';
                document.getElementById("tipo_servicio-reg").removeAttribute('data-id');
                
            }
        }
    };
    xhr.open("GET", "obtener_codigo_operacion.php?id_cliente=" + id_cliente, true);
    xhr.send();
}
// Escucha el cambio en el select de clientes y actualiza los campos
document.getElementById('listaClientes').addEventListener('change', function() {
    if (this.value === '') {
        document.getElementById("codigo_cliente-reg").value = '';
        document.getElementById("codigo_cliente-reg").disabled = true;
        document.getElementById("tipo_servicio-reg").value = '';
    } else {
        llenarDatosCliente(this.value);
        document.getElementById("codigo_cliente-reg").disabled = false;
    }
});


// Escucha el input del campo de texto "buscarCliente" y busca en la lista de clientes
document.getElementById('buscarCliente').addEventListener('input', function() {
    var nombre = this.value;
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
                llenarDatosCliente(li[i].value);
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
        var codigo_cliente = document.getElementById("codigo_cliente-reg");
        if (codigo_cliente.value === '') {
            codigo_cliente.value = '';
        }
    }
});








// Funcion para obtener codigo personal
function llenarDatosPersonal(id_personal) {
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            var response = JSON.parse(xhr.responseText);
            if (response.success) {
                var codigo = response.codigo;
                document.getElementById("codigo-reg").value = codigo;
            } else {
                if (document.getElementById("codigo-reg").value === '') {
                    document.getElementById("codigo-reg").value = '';
                }
            }
        }
    };
    xhr.open("GET", "obtener_codigo_tecnico.php?id_personal=" + id_personal, true);
    xhr.send();
}

// Escucha el cambio en el select de personales y actualiza los campos
document.getElementById('listaPersonales').addEventListener('change', function() {
    if (this.value === '') {
        document.getElementById("codigo-reg").value = '';
    }
    llenarDatosPersonal(this.value);
});


// Escucha el input del campo de texto "buscarPersonal" y busca en la lista de personales
document.getElementById('buscarPersonal').addEventListener('input', function() {
    var nombre = this.value;
    var filter = nombre.toUpperCase();
    var ul = document.getElementById("listaPersonales");
    var li = ul.getElementsByTagName("option");
    var personalEncontrado = false;
    for (var i = 0; i < li.length; i++) {
        var a = li[i].textContent || li[i].innerText;
        if (a.toUpperCase().indexOf(filter) > -1) {
            li[i].style.display = "";
            if (!personalEncontrado) {
                ul.value = li[i].value;
                personalEncontrado = true;
                llenarDatosPersonal(li[i].value);
            }
        } else {
            li[i].style.display = "none";
        }
    }
    var mensaje = document.getElementById("personalNoExiste");
    if (personalEncontrado) {
        ul.style.display = '';
        mensaje.style.display = 'none';
    } else {
        ul.style.display = 'none';
        mensaje.style.display = '';
        document.getElementById("codigo-reg").value = '';
    }
});

     // Escucha el input del campo de texto "buscarTipoServicio" y busca en la lista de tipo de servicios
     document.getElementById('buscarTipoServicio').addEventListener('input', function() {
        var valor = this.value;
        var filter = valor.toUpperCase();
        var select = document.getElementById("listaTipoServicios");
        var options = select.getElementsByTagName("option");
        var tipoServicioEncontrado = false;
        
        if (valor.trim() === '') {
            select.value = '';
            select.style.display = '';
            document.getElementById("tipoServicioNoExiste").style.display = 'none';
        } else {
            for (var i = 0; i < options.length; i++) {
                var a = options[i].textContent || options[i].innerText;
                if (a.toUpperCase().indexOf(filter) > -1) {
                    options[i].style.display = "";
                    if (!tipoServicioEncontrado) {
                        select.value = options[i].value;
                        tipoServicioEncontrado = true;
                        llenarDatosTipoServicio(options[i].value);
                    }
                } else {
                    options[i].style.display = "none";
                }
            }
            
            var mensaje = document.getElementById("tipoServicioNoExiste");
            if (tipoServicioEncontrado) {
                select.style.display = '';
                mensaje.style.display = 'none';
            } else {
                select.style.display = 'none';
                mensaje.style.display = '';
                
            }
        }
    });




</script>


