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
            <h3 class="panel-title"><i class="zmdi zmdi-plus"></i> &nbsp; NUEVO TIPO DE PRODUCTO</h3>
        </div>
        <div class="panel-body">
        <form id="nuevoProductoForm">
    <fieldset>
        <legend><i class="zmdi zmdi-assignment-o"></i> &nbsp; Agregar tipo de producto</legend>
        <div class="container-fluid">
            <div class="row">
                <div class="col-xs-12">
                    <div class="form-group label-floating">
                        <label class="control-label">Nombre *</label>
                        <input 
                            pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,10}" 
                            class="form-control" 
                            type="text" 
                            name="nombre-reg" 
                            required 
                            maxlength="10"
                            title="Ingrese solo letras y espacios (hasta 10 caracteres)."
                        >
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
        var form = document.getElementById('nuevoProductoForm');

        if (!form.checkValidity()) {
            modalShow('Por favor, complete correctamente todos los campos requeridos.', 'danger', true);
            return;
        }

        var formData = new FormData(form);

        var xhr = new XMLHttpRequest();
        xhr.open('POST', '../../app/controllers/inventario/productos/guardar_tipo_producto.php', true);
        xhr.onload = function() {
            try {
                if (xhr.status === 200) {
                    var data = JSON.parse(xhr.responseText);
                    if (data.success) {
                        modalShow('Tipo de producto guardado exitosamente.', 'success', true);
                    } else {
                        modalShow('Error: ' + (data.error || 'No se pudo guardar el tipo de producto.'), 'danger', true);
                    }
                } else {
                    modalShow('Error en la conexión al servidor. Código de estado: ' + xhr.status, 'danger', true);
                }
            } catch (error) {
                modalShow('Error al procesar la respuesta del servidor: ' + error, 'danger', true);
            }
        };

        xhr.onerror = function() {
            modalShow('Error al enviar la solicitud. Verifique su conexión.', 'danger', true);
        };

        xhr.send(formData);
    });

    function modalShow(message, type, redirect = false) {
        var modalContent = `
            <div class="modal fade" id="mensajeModal" tabindex="-1" role="dialog" aria-labelledby="mensajeModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content" style="background-color: #2E865F; color: #FFFFFF;">
					
                        <div class="modal-body text-center">
                            ${message}
                        </div>
                    </div>
                </div>
            </div>`;
        
        document.body.insertAdjacentHTML('beforeend', modalContent);
        $('#mensajeModal').modal('show');

        setTimeout(function() {
            $('#mensajeModal').modal('hide');
            if (redirect) {
                window.location.href = '<?php echo $URL; ?>inventario/productos/tipo_producto.php';
            }
        }, 3000);
    }
</script>

<!-- Panel listado de productos -->
<div class="container-fluid">
       
		<div class="panel panel-success">
			<div class="panel-heading">
				<h3 class="panel-title"><i class="zmdi zmdi-format-list-bulleted"></i> &nbsp; LISTA DE TIPOS DE PRODUCTOS</h3>
			</div>
			<div class="panel-body">
				<div class="table-responsive">
					<table class="table table-hover text-center">
						<thead>
							<tr>
								<th class="text-center">#</th>
								<th class="text-center">NOMBRE</th>
								<th class="text-center">ESTADO</th>
								<th class="text-center">ACTUALIZAR</th>
								<th class="text-center">ELIMINAR</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$query = "SELECT ID_tipo_producto, Nom_producto, Estado FROM tipo_producto";
							$stmt = $pdo->query($query);
							while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
								<tr>
									<td><?php echo htmlspecialchars($row['ID_tipo_producto']); ?></td>
									<td><?php echo htmlspecialchars($row['Nom_producto']); ?></td>
									<td>
										<?php echo htmlspecialchars($row['Estado'] ? 'Activo' : 'Inactivo'); ?>
									</td>
									<td>
										<button type="button" class="btn btn-primary btn-raised btn-xs" onclick="window.location.href = 'editar_tipo_producto.php?id_tipo_producto=<?php echo $row['ID_tipo_producto']; ?>'">
											<i class="zmdi zmdi-edit"></i>
										</button>
									</td>
									<td>
										<button type="button" class="btn btn-danger btn-raised btn-xs" onclick="deleteTipoProductoUnique(<?php echo htmlspecialchars($row['ID_tipo_producto']); ?>);">
											<i class="zmdi zmdi-delete"></i>
										</button>
										<script>
                                            function deleteTipoProductoUnique(id_tipo_producto) {
                                                showConfirmModalUnique('¿Está seguro de que desea eliminar este tipo de producto?', function() {
                                                    var xhr = new XMLHttpRequest();
                                                    xhr.open('POST', '../../app/controllers/inventario/productos/eliminar_tipo_producto.php', true);
                                                    xhr.setRequestHeader('Content-Type', 'application/json');
                                                    xhr.onload = function() {
                                                        try {
                                                            if (xhr.status === 200) {
                                                                var data = JSON.parse(xhr.responseText);
                                                                if (data.success) {
                                                                    showModalUnique('<span style="color:green;">' + data.message + '</span>', 'success');
                                                                    setTimeout(function() {
                                                                        window.location.reload(true);
                                                                    }, 1000);
                                                                } else {
                                                                    showModalUnique('<span style="color:red;">' + (data.error || 'No se pudo eliminar el tipo de producto.') + '</span>', 'danger');
                                                                }
                                                            } else {
                                                                showModalUnique('<span style="color:red;">Error en la conexión al servidor. Código de estado: ' + xhr.status + '</span>', 'danger');
                                                            }
                                                        } catch (error) {
                                                            showModalUnique('<span style="color:red;">Error al procesar la respuesta del servidor: ' + error.message + '</span>', 'danger');
                                                        }
                                                    };

                                                    xhr.onerror = function() {
                                                        showModalUnique('<span style="color:red;">Error al enviar la solicitud. Verifique su conexión.</span>', 'danger');
                                                    };

                                                    xhr.send(JSON.stringify({ id_tipo_producto: id_tipo_producto }));
                                                });
                                            }

                                            function showConfirmModalUnique(message, onConfirm) {
                                                var modalContent = `
                                                    <div class="modal fade" id="confirmModalUnique" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabelUnique" aria-hidden="true">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="confirmModalLabelUnique">Confirmar Eliminación</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    ${message}
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                                                                    <button type="button" class="btn btn-primary" id="confirmBtnUnique">Sí</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                `;

                                                document.body.insertAdjacentHTML('beforeend', modalContent);
                                                $('#confirmModalUnique').modal('show');

                                                document.getElementById('confirmBtnUnique').addEventListener('click', function() {
                                                    onConfirm();
                                                    $('#confirmModalUnique').modal('hide');
                                                });
                                            }

                                            function showModalUnique(message, type) {
                                                var modalContent = `
                                                    <div class="modal fade" id="mensajeModalUnique" tabindex="-1" role="dialog" aria-labelledby="mensajeModalLabelUnique" aria-hidden="true">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="mensajeModalLabelUnique">${type === 'success' ? '<span style="color:green;">Éxito</span>' : '<span style="color:red;">Error</span>'}</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    ${message}
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                `;

                                                document.body.insertAdjacentHTML('beforeend', modalContent);
                                                $('#mensajeModalUnique').modal('show');
                                            }
                                        </script>
									</td>
								</tr>
							<?php endwhile; ?>
							
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>


