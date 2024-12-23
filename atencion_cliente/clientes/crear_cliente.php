<?php

include ('../../app/config.php');
include ('../../layout/sesion.php');
include ('../../layout/parte1.php');


?>
<b></b>

<!-- Panel nuevo cliente -->
<div class="container-fluid">

<h1><i class="zmdi zmdi-male-alt zmdi-hc-fw"></i> Atención al Clientes</h1>
    <?php 
    
    

    include ('../../layout/cliente.php');

    
    
    ?>

  

    <div class="panel panel-success">
    <div class="panel-heading" style="background-color: #2ecc71; color: white;">
            <h3 class="panel-title"><i class="zmdi zmdi-plus"></i> &nbsp; NUEVO CLIENTE</h3>
        </div>
        <div class="panel-body">

<form id="nuevoClienteForm">
    <fieldset>
        <legend style="color: black;"><i class="zmdi zmdi-assignment-o"></i> &nbsp; Información del cliente</legend>
        <div class="container-fluid">
            <div class="row">
                <div class="col-xs-12 col-sm-6">
                    <div class="form-group label-floating">
                        <label class="control-label">DNI *</label>
                        <input 
                            pattern="\d{1,10}" 
                            class="form-control" 
                            type="text" 
                            name="dni-reg" 
                            required 
                            maxlength="10"
                            title="Ingrese un DNI válido (máximo 10 dígitos)."
                            autocomplete="off"
                        >
                    </div>
                </div>
                <div class="col-xs-12 col-sm-6">
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
                            autocomplete="off"
                        >
                    </div>
                </div>
                <div class="col-xs-12 col-sm-6">
                    <div class="form-group label-floating">
                        <label class="control-label">Apellido Paterno *</label>
                        <input 
                            pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,100}" 
                            class="form-control" 
                            type="text" 
                            name="apellido-paterno-reg" 
                            required 
                            maxlength="100"
                            title="Ingrese solo letras y espacios (hasta 100 caracteres)."
                            autocomplete="off"
                        >
                    </div>
                </div>
                <div class="col-xs-12 col-sm-6">
                    <div class="form-group label-floating">
                        <label class="control-label">Apellido Materno *</label>
                        <input 
                            pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,100}" 
                            class="form-control" 
                            type="text" 
                            name="apellido-materno-reg" 
                            required 
                            maxlength="100"
                            title="Ingrese solo letras y espacios (hasta 100 caracteres)."
                            autocomplete="off"
                        >
                    </div>
                </div>
                <div class="col-xs-12 col-sm-6">
                    <div class="form-group label-floating">
                        <label class="control-label">Dirección *</label>
                        <input 
                            class="form-control" 
                            type="text" 
                            name="direccion-reg" 
                            required 
                            maxlength="100"
                            title="Ingrese la dirección (hasta 100 caracteres)."
                            autocomplete="off"
                        >
                    </div>
                </div>
                <div class="col-xs-12 col-sm-6">
                    <div class="form-group label-floating">
                        <label class="control-label">Celular *</label>
                        <input 
                            pattern="\d{1,11}" 
                            class="form-control" 
                            type="text" 
                            name="celular-reg" 
                            required 
                            maxlength="11"
                            title="Ingrese un número de celular válido (hasta 11 dígitos)."
                            autocomplete="off"
                        >
                    </div>
                </div>
                <div class="col-xs-12 col-sm-6">
                    <div class="form-group label-floating">
                        <label class="control-label">Correo Electrónico</label>
                        <input 
                            class="form-control" 
                            type="email" 
                            name="correo-electronico-reg" 
                            maxlength="150"
                            title="Ingrese el correo electrónico (hasta 150 caracteres)."
                            autocomplete="off"
                        >
                    </div>
                </div>
                <div class="col-xs-12 col-sm-6">
                    <div class="form-group label-floating">
                        <label class="control-label">Estado</label>
                        <select class="form-control" name="estado-reg" title="Seleccione un estado.">
                            
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
    document.getElementById('guardarBtn').addEventListener('click', function(event) {
        event.preventDefault(); // Prevent default form submission

        var form = document.getElementById('nuevoClienteForm');
        var camposInvalidos = validarCampos(form); // Validar campos vacíos

        if (camposInvalidos.length > 0) {
            var mensaje = 'Por favor, complete los siguientes campos requeridos:<br>';
            mensaje += camposInvalidos.map(campo => `- ${campo}`).join('<br>');
            showModal(mensaje, 'danger');
            return;
        }

        var formData = new FormData(form);

        var xhr = new XMLHttpRequest();
        xhr.open('POST', '../../app/controllers/atencion_cliente/clientes/guardar.php', true);
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

    function validarCampos(form) {
        var camposInvalidos = [];
        Array.from(form.elements).forEach(function(campo) {
            if (campo.required && !campo.value.trim()) {
                camposInvalidos.push(campo.name || campo.placeholder || 'Campo sin nombre');
                campo.classList.add('is-invalid');
            } else {
                campo.classList.remove('is-invalid');
            }
        });
        return camposInvalidos;
    }

    function showModal(message, type, showButtons = false) {
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
                var form = document.getElementById('nuevoClienteForm');
                form.reset();
                $('#mensajeModal').modal('hide');
                location.reload();
            });
            document.getElementById('listaClientesBtn').addEventListener('click', function() {
                window.location.href = '<?php echo $URL; ?>atencion_cliente/clientes/lista_clientes.php';
            });
        }
    }
</script>

