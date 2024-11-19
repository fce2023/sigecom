<?php

include ('../app/config.php');
include ('../layout/sesion.php');
include ('../layout/parte1.php');
include ('../layout/tecnico.php');


$limit = 5;
$page = (isset($_GET['page'])) ? $_GET['page'] : 1;
$offset = ($page - 1) * $limit;

try {

    $query = "SELECT t.ID_tecnico, p.Dni, p.Nombre, p.Apellido_paterno, p.Apellido_materno, p.Celular, p.Direccion, t.estado
              FROM tecnico t
              INNER JOIN personal p ON t.id_personal = p.ID_personal";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $tecnicos = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}


?>
<!-- Panel listado de productos -->
<div class="container-fluid">
       
		<div class="panel panel-success">
			<div class="panel-heading" style="background-color: #00b6ff;">
				<h3 class="panel-title"><i class="zmdi zmdi-format-list-bulleted"></i> &nbsp; LISTA DE TECNICOS</h3>
			</div>
			<div class="panel-body">
				<div class="table-responsive">
					<table class="table table-hover text-center">
						<thead>
							<tr>
								<th class="text-center">#</th>
								<th class="text-center">DNI</th>
								<th class="text-center">NOMBRE</th>
								<th class="text-center">APELLIDO PATERNO</th>
								<th class="text-center">APELLIDO MATERNO</th>
								<th class="text-center">CELULAR</th>
								<th class="text-center">DIRECCIÓN</th>
								<th class="text-center">ESTADO</th>
								<th class="text-center">ACTUALIZAR</th>
								<th class="text-center">ELIMINAR</th>
							</tr>
						</thead>
						<tbody>
							<?php
								$contador = 0;
								foreach ($tecnicos as $tecnico): ?>
									<tr>
										<td><?php echo ++$contador; ?></td>
										<td><?php echo htmlspecialchars($tecnico['Dni']); ?></td>
										<td><?php echo htmlspecialchars($tecnico['Nombre']); ?></td>
										<td><?php echo htmlspecialchars($tecnico['Apellido_paterno']); ?></td>
										<td><?php echo htmlspecialchars($tecnico['Apellido_materno']); ?></td>
										<td><?php echo htmlspecialchars($tecnico['Celular']); ?></td>
										<td><?php echo htmlspecialchars($tecnico['Direccion']); ?></td>

										<td><?php echo htmlspecialchars($tecnico['estado'] == 1 ? 'Activo' : 'Inactivo'); ?></td>
										<td>
											<button type="button" class="btn btn-primary btn-raised btn-xs" onclick="window.location.href = 'editar_tecnico.php?ID_tecnico=<?php echo $tecnico['ID_tecnico']; ?>'">
												<i class="zmdi zmdi-edit"></i>
											</button>
										</td>
										<td>
											<button type="button" class="btn btn-danger btn-raised btn-xs" onclick="deleteTecnicoUnique(<?php echo htmlspecialchars($tecnico['ID_tecnico']); ?>);">
												<i class="zmdi zmdi-delete"></i>
											</button>
										</td>
									</tr>
								<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>



    <nav class="text-center">
					<ul class="pagination pagination-sm">
						<li class="disabled"><a href="javascript:void(0)">«</a></li>
						<?php
							$query = "SELECT COUNT(*) as total FROM detalle_producto_proveedor";
							$stmt = $pdo->query($query);
							$total = $stmt->fetchColumn();
							$pages = ceil($total / $limit);
							for ($i = 1; $i <= $pages; $i++) {
								$active = ($i == $page) ? 'active' : '';
								                                echo '<li class="' . $active . '"><a href="' . $URL . '/inventario/movimientos/entrada/index.php?page=' . $i . '">' . $i . '</a></li>';
							}
						?>
						<li><a href="javascript:void(0)">»</a></li>
					</ul>
	</nav>


<script>


function deleteTecnico(Id_tecnico) {
	showConfirmModal('¿Está seguro de que desea eliminar a este técnico?', function() {
		var xhr = new XMLHttpRequest();
		xhr.open('POST', '<?php echo $URL; ?>/app/controllers/tecnico/eliminar_tecnico.php', true);
		xhr.setRequestHeader('Content-Type', 'application/json');
		xhr.onload = function() {
			try {
				if (xhr.status === 200) {
					var data = JSON.parse(xhr.responseText);
					if (data.success) {
						showModal('<span style="color:green;">' + data.message + '</span>', 'success');
						setTimeout(function() {
							window.location.reload(true);
						}, 1000);
					} else {
						showModal('<span style="color:red;">' + (data.error || 'No se pudo eliminar al técnico.') + '</span>', 'danger');
					}
				} else {
					showModal('<span style="color:red;">Error en la conexión al servidor. Código de estado: ' + xhr.status + '</span>', 'danger');
				}
			} catch (error) {
				showModal('<span style="color:red;">Error al procesar la respuesta del servidor: ' + error.message + '</span>', 'danger');
			}
		};
		xhr.onerror = function() {
			showModal('<span style="color:red;">Error al enviar la solicitud. Verifique su conexión.</span>', 'danger');
		};
		xhr.send(JSON.stringify({ Id_tecnico: Id_tecnico }));
	});
}
function showConfirmModal(message, onConfirm) {
	var modalContent = `
		<div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="confirmModalLabel">Confirmar Eliminación</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						${message}
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
						<button type="button" class="btn btn-primary" id="confirmBtn">Sí</button>
					</div>
				</div>
			</div>
		</div>
	`;
	document.body.insertAdjacentHTML('beforeend', modalContent);
	$('#confirmModal').modal('show');
	document.getElementById('confirmBtn').addEventListener('click', function() {
		onConfirm();
		$('#confirmModal').modal('hide');
	});
}
function showModal(message, type) {
	var modalContent = `
		<div class="modal fade" id="mensajeModal" tabindex="-1" role="dialog" aria-labelledby="mensajeModalLabel" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="mensajeModalLabel">${type === 'success' ? '<span style="color:green;">Éxito</span>' : '<span style="color:red;">Error</span>'}</h5>
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
	$('#mensajeModal').modal('show');
}

