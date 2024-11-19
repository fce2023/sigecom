
<?php

include('../app/config.php');
include('../layout/sesion.php');
include('../layout/parte1.php');
include('../layout/tecnico.php');

if (isset($_GET['ID_tecnico'])) {
    $id_tecnico = $_GET['ID_tecnico'];
    $consulta = $pdo->prepare("SELECT t.id_personal, p.Dni, p.Nombre, p.Apellido_paterno, p.Apellido_materno, p.Celular, p.Direccion
                             FROM tecnico t
                             INNER JOIN personal p ON t.id_personal = p.ID_personal
                             WHERE t.ID_tecnico = :id_tecnico");
    $consulta->execute([':id_tecnico' => $id_tecnico]);
    $fila = $consulta->fetch(PDO::FETCH_ASSOC);
    if (!$fila) {
        header('Location: ../index.php');
        exit;
    }

    $consulta2 = $pdo->prepare("SELECT t.Codigo, t.estado, t.Fecha_creacion
                             FROM tecnico t
                             WHERE t.ID_tecnico = :id_tecnico");
    $consulta2->execute([':id_tecnico' => $id_tecnico]);
    $fila2 = $consulta2->fetch(PDO::FETCH_ASSOC);
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

<h1>Editar tecnico con el codigo <?php echo $fila2['Codigo']; ?> </h1>
<form id="editarFormTecnico" action="<?php echo $URL; ?>/app/controllers/tecnico/editar_tecnico.php" method="post">
    <!-- <form id="editarFormTecnico"> -->
        <input type="hidden" name="id_tecnico" value="<?php echo $id_tecnico; ?>">
        
        <input type="hidden" id="id_personal" name="id_personal" value="<?php echo $fila['id_personal']; ?>">

        <div class="form-group">
            <label for="Dni">DNI</label>
            <input type="text" class="form-control" id="Dni" name="Dni" value="<?php echo $fila['Dni']; ?>" required>
        </div>

        <div class="form-group">
            <label for="Nombre">Nombre</label>
            <input type="text" class="form-control" id="Nombre" name="Nombre" value="<?php echo $fila['Nombre']; ?>" required>
        </div>

        <div class="form-group">
            <label for="Apellido_paterno">Apellido paterno</label>
            <input type="text" class="form-control" id="Apellido_paterno" name="Apellido_paterno" value="<?php echo $fila['Apellido_paterno']; ?>" required>
        </div>

        <div class="form-group">
            <label for="Apellido_materno">Apellido materno</label>
            <input type="text" class="form-control" id="Apellido_materno" name="Apellido_materno" value="<?php echo $fila['Apellido_materno']; ?>" required>
        </div>

        <div class="form-group">
            <label for="Celular">Celular</label>
            <input type="text" class="form-control" id="Celular" name="Celular" value="<?php echo $fila['Celular']; ?>" required>
        </div>

        <div class="form-group">
            <label for="Direccion">Dirección</label>
            <input type="text" class="form-control" id="Direccion" name="Direccion" value="<?php echo $fila['Direccion']; ?>" required>
        </div>

        <div class="form-group">
            <label for="estado">Estado</label>
            <select class="form-control" id="estado" name="Estado">
                <option value="1" <?php echo (isset($fila2['estado']) && $fila2['estado'] == 1) ? 'selected' : ''; ?>>Activo</option>
                <option value="0" <?php echo (isset($fila2['estado']) && $fila2['estado'] == 0) ? 'selected' : ''; ?>>Inactivo</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
        <a href="javascript:window.history.back();" class="btn btn-default">Cancelar</a>
    </form>
</div>


<script>
    document.getElementById('editarFormTecnico').addEventListener('submit', function(event) {
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
        xhr.open('POST', '../app/controllers/tecnico/editar_tecnico.php', true);
        xhr.onload = function() {
            try {
                if (xhr.status === 200) {
                    var data = JSON.parse(xhr.responseText);
                    if (data.success) {
                        showModal('Técnico actualizado exitosamente.', 'success', true);
                    } else {
                        showModal('Error: ' + (data.error || 'No se pudo guardar los cambios del técnico.'), 'danger');
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
                                <button id="listaTecnicosBtn" class="btn btn-secondary btn-sm">Ir a la lista</button>
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
            document.getElementById('listaTecnicosBtn').addEventListener('click', function() {
                window.location.href = '<?php echo $URL; ?>tecnico/lista_tecnicos.php';
            });
        }
    }
</script>


