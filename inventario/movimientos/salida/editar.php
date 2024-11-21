<?php
include('../../../app/config.php');
include('../../../layout/sesion.php');
include('../../../layout/parte1.php');

// Validar el parámetro `id_detalle_tecnico_producto`
$id_detalle_tecnico_producto = isset($_GET['Id_det_tecnico_producto']) ? (int)$_GET['Id_det_tecnico_producto'] : 0;


// Consulta para obtener el detalle del tecnico-producto
$consulta_detalle_tecnico_producto = $pdo->prepare("
    SELECT 
        dtp.Id_det_tecnico_producto, 
        dtp.ID_tecnico, 
        dtp.ID_producto, 
        dtp.ID_usuario, 
        dtp.Fecha_retiro, 
        dtp.cantidad, 
        dtp.Observación, 
        dtp.Estado
    FROM 
        detalle_tecnico_producto dtp
    WHERE 
        dtp.Id_det_tecnico_producto = :Id_det_tecnico_producto
");
$consulta_detalle_tecnico_producto->execute([':Id_det_tecnico_producto' => $id_detalle_tecnico_producto]);
$fila = $consulta_detalle_tecnico_producto->fetch(PDO::FETCH_ASSOC);

if (!$fila) {
    echo json_encode(["success" => false, "error" => "Detalle no encontrado."]);
    exit;
}

$id_detalle = $fila['Id_det_tecnico_producto'];
$id_tecnico = $fila['ID_tecnico'];
$id_producto = $fila['ID_producto'];
$id_usuario = $fila['ID_usuario'];
$fecha_retiro = $fila['Fecha_retiro'];
$cantidad = $fila['cantidad'];
$observacion = $fila['Observación'];
$estado = $fila['Estado'];


// Consulta para obtener el nombre y DNI del tecnico
$consulta_tecnico = $pdo->prepare("SELECT p.Nombre, p.Dni FROM tecnico t INNER JOIN personal p ON t.id_personal = p.ID_personal WHERE t.ID_tecnico = :ID_tecnico");
$consulta_tecnico->execute([':ID_tecnico' => $id_tecnico]);
$fila_tecnico = $consulta_tecnico->fetch(PDO::FETCH_ASSOC);
$nombre_tecnico = $fila_tecnico['Nombre'];
$Dni_tecnico = $fila_tecnico['Dni'] ;// Mostrar el nombre y DNI del tecnico


// Consulta para obtener todos los nombres de personal que son tecnicos
$consulta_tecnico = $pdo->prepare("SELECT p.Nombre, p.Dni FROM tecnico t INNER JOIN personal p ON t.id_personal = p.ID_personal");
$consulta_tecnico->execute();
$tecnicos = $consulta_tecnico->fetchAll(PDO::FETCH_ASSOC);
$nombre_tecnicos = array_column($tecnicos, 'Nombre');
$Dni_tecnicos = array_column($tecnicos, 'Dni');


// Obtener el nombre del producto
$consulta_producto = $pdo->prepare("SELECT nombre FROM productos WHERE id_producto = :id_producto");
$consulta_producto->execute([':id_producto' => $id_producto]);
$nombre_producto = $consulta_producto->fetchColumn(); // Mostrar el nombre del producto



?>


<style>
    #editarFormDetalleTecnicoProducto {
        margin-top: 20px;
    }
    #editarFormDetalleTecnicoProducto .form-group {
        margin-bottom: 20px;
    }
    #editarFormDetalleTecnicoProducto .form-group label {
        font-weight: bold;
    }
    #editarFormDetalleTecnicoProducto .btn {
        margin-top: 20px;
    }
    #editarFormDetalleTecnicoProducto .alert {
        margin-top: 20px;
    }
</style>
<div class="content">
<div class="container-fluid">



<!-- form id="editar" action="<?php echo $URL; ?>/app/controllers/inventario/salida/editar_salida.php" method="post"> -->
    <form id="editarFormDetalleTecnicoProducto">    
        <input type="hidden" name="id_detalle_tecnico_producto" value="<?php echo $id_detalle_tecnico_producto; ?>">
        <div class="form-group">
            <label for="nombre-tecnico">Nombre del tecnico</label>
            <select class="form-control" id="nombre-tecnico" name="ID_tecnico">
                <option value="<?php echo $id_tecnico; ?>"><?php echo $nombre_tecnico . " - " . $Dni_tecnico; ?></option>
                <?php
                $consulta_tecnico = $pdo->prepare("SELECT t.ID_tecnico, p.Nombre, p.Dni FROM tecnico t INNER JOIN personal p ON t.id_personal = p.ID_personal WHERE t.ID_tecnico != :ID_tecnico");
                $consulta_tecnico->execute([':ID_tecnico' => $id_tecnico]);
                $tecnicos = $consulta_tecnico->fetchAll(PDO::FETCH_ASSOC);
                foreach ($tecnicos as $tecnico) {
                    echo "<option value='" . $tecnico['ID_tecnico'] . "'>" . $tecnico['Nombre'] . " - " . $tecnico['Dni'] . "</option>";
                }
                ?>
            </select>

            <?php echo "este es el id del tecnico: ".$id_tecnico;?>
        </div>
        <div class="form-group">
            <label for="nombre-producto">Nombre del producto</label>
            <select class="form-control" id="nombre-producto" name="ID_producto">
                <?php
                $consulta_producto = $pdo->prepare("SELECT ID_producto, nombre FROM productos WHERE estado = 1 ORDER BY nombre");
                $consulta_producto->execute();
                $productos = $consulta_producto->fetchAll(PDO::FETCH_ASSOC);
                if (empty($productos)) {
                    echo "<option value=''>Productos no disponibles</option>";
                } else {
                    foreach ($productos as $fila_producto) {
                        echo "<option value='{$fila_producto['ID_producto']}'" . 
                             ($fila_producto['ID_producto'] == $id_producto ? ' selected' : '') . 
                             ">{$fila_producto['nombre']}</option>";
                    }
                }
                ?>
            </select>
        </div>

        <div class="form-group">
            <label for="fecha-retiro">Fecha de retiro</label>
            <input type="date" class="form-control" id="fecha-retiro" name="Fecha_retiro" 
                   value="<?php echo $fecha_retiro; ?>" required>
        </div>

        <div class="form-group">
            <label for="cantidad">Cantidad</label>
            <input type="number" class="form-control" id="cantidad" name="cantidad" 
                   value="<?php echo $cantidad; ?>" required>
        </div>

        <div class="form-group">
            <label for="observacion">Observación</label>
            <textarea class="form-control" id="observacion" name="Observación" rows="3"><?php echo $observacion; ?></textarea>
        </div>

        <div class="form-group">
            <label for="estado">Estado</label>
            <select class="form-control" id="estado" name="Estado">
                <option value="1" <?php echo $estado == '1' ? 'selected' : ''; ?>>Activado</option>
                <option value="0" <?php echo $estado == '0' ? 'selected' : ''; ?>>Inactivado</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
        <a href="javascript:window.history.back();" class="btn btn-default">Cancelar</a>
    </form>
</div>
</div>

<script>
    document.getElementById('editarFormDetalleTecnicoProducto').addEventListener('submit', function(event) {
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
        xhr.open('POST', '../../../app/controllers/inventario/salida/editar_salida.php', true);
        xhr.onload = function() {
            try {
                if (xhr.status === 200) {
                    var data = JSON.parse(xhr.responseText);
                    if (data.success) {
                        showModal('Detalle técnico del producto guardado exitosamente.', 'success', true);
                    } else {
                        showModal('Error: ' + (data.error || 'No se pudo guardar el detalle técnico del producto.'), 'danger');
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
                location.reload();
            });
            document.getElementById('listaSalidasBtn').addEventListener('click', function() {
                window.location.href = '<?php echo $URL; ?>inventario/movimientos/salida/index.php';
            });
        }
    }
</script>

