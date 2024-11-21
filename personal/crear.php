<?php

include ('../app/config.php');
include ('../layout/sesion.php');

include ('../layout/parte1.php');
?>

<div class="container-fluid">
    <div class="page-header">
        <h1 class="text-titles"><i class="zmdi zmdi-account zmdi-hc-fw"></i> Registrar <small>Personal</small></h1>
    </div>
</div>

<!-- Panel nuevo personal -->
<div class="container-fluid">
    <div class="panel panel-danger">
        <div class="panel-heading">
            <h3 class="panel-title"><i class="zmdi zmdi-plus"></i> &nbsp; NUEVO PERSONAL</h3>
        </div>
        <div class="panel-body">
            <form id="nuevoPersonal">
                <fieldset>
                    <legend style="color: #2196F3;"><i class="zmdi zmdi-account-box"></i> &nbsp; Información personal</legend>
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="form-group label-floating">
                                    <label class="control-label" style="color: #2196F3;">DNI/CEDULA *</label>
                                    <input pattern="[0-9-]{1,10}" class="form-control" type="text" name="dni-reg" required="" maxlength="10" autocomplete="off" readonly onfocus="this.removeAttribute('readonly');">
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6">
                                <div class="form-group label-floating">
                                    <label class="control-label" style="color: #2196F3;">Nombres *</label>
                                    <input pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,100}" class="form-control" type="text" name="nombre-reg" required="" maxlength="100" autocomplete="off" readonly onfocus="this.removeAttribute('readonly');">
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6">
                                <div class="form-group label-floating">
                                    <label class="control-label" style="color: #2196F3;">Apellido Paterno *</label>
                                    <input class="form-control" type="text" name="apellido-paterno-reg" required="" autocomplete="off" readonly onfocus="this.removeAttribute('readonly');">
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6">
                                <div class="form-group label-floating">
                                    <label class="control-label" style="color: #2196F3;">Apellido Materno *</label>
                                    <input class="form-control" type="text" name="apellido_materno-reg" required="" autocomplete="off" readonly onfocus="this.removeAttribute('readonly');">
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6">
                                <div class="form-group label-floating">
                                    <label class="control-label" style="color: #2196F3;">Celular</label>
                                    <input pattern="[0-9+]{1,11}" class="form-control" type="text" name="celular-reg" maxlength="11" autocomplete="off" readonly onfocus="this.removeAttribute('readonly');">
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6">
                                <div class="form-group label-floating">
                                    <label class="control-label" style="color: #2196F3;">Dirección</label>
                                    <input type="text" class="form-control" name="direccion-reg" maxlength="250" autocomplete="off" readonly onfocus="this.removeAttribute('readonly');">
                                </div>
                            </div>
                            <div class="col-xs-12">
                                <div class="form-group label-floating">
                                    <label class="control-label" style="color: #2196F3;">Cargo</label>
                                    <?php
                                    try {
                                        $consulta = $pdo->query("SELECT * FROM cargo");
                                        $consulta->execute();
                                        $filas = $consulta->fetchAll();
                                    } catch (PDOException $e) {
                                        echo "Error al conectar a la base de datos";
                                    }
                                    ?>
                                    <select class="form-control" name="cargo-reg">
                                        <?php
                                        foreach ($filas as $fila) {
                                            echo '<option value="'.$fila['ID_cargo'].'">'.$fila['Nom_cargo'].'</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <p>Si no hay cargo en la lista, ve y <a href="<?php echo $URL; ?>/cargo/crear.php">agrega uno en la sección de Administrar Cargo</a></p>
                </fieldset>
                <br>

                <p class="text-center" style="margin-top: 20px;">
                    <button type="submit" id="guardarBtn" class="btn btn-primary btn-raised btn-sm"><i class="zmdi zmdi-floppy"></i> Guardar</button>
                </p>
            </form>
        </div>
    </div>
</div>

<style>
    p.alert {
        position: fixed;
        top: 60px;
        left: 50%;
        transform: translateX(-50%);
        z-index: 1000;
    }
</style>

<script>
    document.getElementById('nuevoPersonal').addEventListener('submit', function(event) {
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
        xhr.open('POST', '../app/controllers/personal/crear.php', true);
        xhr.onload = function() {
            try {
                if (xhr.status === 200) {
                    var data = JSON.parse(xhr.responseText);
                    if (data.success) {
                        showModal(data.message, 'success', true); // Show server message
                    } else {
                        showModal('Error: ' + (data.message || 'No se pudo guardar el personal.'), 'danger');
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
                                <button id="seguirEditandoBtn" class="btn btn-primary btn-sm">Agregar otro personal</button>
                                <button id="listaPersonalesBtn" class="btn btn-secondary btn-sm">Ir a la lista</button>
                            ` : ''}
                        </div>
                    </div>
                </div>
            </div>`;

        document.body.insertAdjacentHTML('beforeend', modalContent);
        $('#mensajeModal').modal('show');

        if (showButtons) {
            document.getElementById('seguirEditandoBtn').addEventListener('click', function() {
                document.getElementById('nuevoPersonal').reset();
                $('#mensajeModal').modal('hide');
            });
            document.getElementById('listaPersonalesBtn').addEventListener('click', function() {
                window.location.href = '<?php echo $URL; ?>/personal/index.php';
            });
        }
    }
</script>

