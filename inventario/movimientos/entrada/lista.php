

<!-- Panel listado de productos -->
<div class="container-fluid">
       
		<div class="panel panel-success">
			<div class="panel-heading">
				<h3 class="panel-title"><i class="zmdi zmdi-format-list-bulleted"></i> &nbsp; LISTA DE ENTRADAS</h3>
			</div>
			<div class="panel-body">
				<div class="table-responsive">
					<table class="table table-hover text-center">
						<thead>
							<tr>
								<th class="text-center">#</th>
								<th class="text-center">NOMBRE PROVEEDOR</th>
								<th class="text-center">USUARIO QUE REGISTRÓ</th>
								<th class="text-center">NOMBRE PRODUCTO</th>
								<th class="text-center">FECHA ABASTECIMIENTO</th>
								<th class="text-center">CANTIDAD</th>
								<th class="text-center">OBSERVACIÓN</th>
								<th class="text-center">ESTADO</th>
								<th class="text-center">ACTUALIZAR</th>
								<th class="text-center">ELIMINAR</th>
							</tr>
						</thead>
						<tbody>
							<?php
								$limit = 5;
								$page = (isset($_GET['page'])) ? $_GET['page'] : 1;
								$offset = ($page - 1) * $limit;
								$query = "SELECT dpp.Id_det_producto_proveedor, p.Nombre, dp.nombre as Nom_producto, dpp.Fecha_abastecimiento, dpp.cantidad, dpp.Observación, dpp.Estado, u.Nombre_usuario
								FROM detalle_producto_proveedor dpp
								INNER JOIN proveedor p ON dpp.ID_proveedor = p.ID_proveedor
								INNER JOIN productos dp ON dpp.ID_producto = dp.id_producto
								INNER JOIN usuario u ON dpp.ID_usuario = u.ID_usuario
								ORDER BY dpp.Id_det_producto_proveedor DESC
								LIMIT $limit OFFSET $offset";
								$stmt = $pdo->query($query);
								while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
									<tr>
										<td><?php echo htmlspecialchars($row['Id_det_producto_proveedor']); ?></td>
										<td><?php echo htmlspecialchars($row['Nombre']); ?></td>
										<td><?php echo htmlspecialchars($row['Nombre_usuario']); ?></td>
										<td><?php echo htmlspecialchars($row['Nom_producto']); ?></td>
										<td><?php echo htmlspecialchars($row['Fecha_abastecimiento']); ?></td>
										<td><?php echo htmlspecialchars($row['cantidad']); ?></td>
										<td><?php echo htmlspecialchars($row['Observación']); ?></td>
										<td>
											<?php echo htmlspecialchars($row['Estado'] ? 'Activo' : 'Inactivo'); ?>
										</td>
										<td>
											<button type="button" class="btn btn-primary btn-raised btn-xs" onclick="window.location.href = 'editar.php?Id_det_producto_proveedor=<?php echo $row['Id_det_producto_proveedor']; ?>'">
												<i class="zmdi zmdi-edit"></i>
											</button>
										</td>
										<td>
											<button type="button" class="btn btn-danger btn-raised btn-xs" onclick="deleteEntradaUnique(<?php echo htmlspecialchars($row['Id_det_producto_proveedor']); ?>);">
												<i class="zmdi zmdi-delete"></i>
											</button>
											<script>
												function deleteEntradaUnique(Id_det_producto_proveedor) {
													showConfirmModalUnique('¿Está seguro de que desea eliminar esta entrada?', function() {
														var xhr = new XMLHttpRequest();
														xhr.open('POST', '<?php echo $URL; ?>/app/controllers/inventario/entrada/eliminar_entrada.php', true);
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
																		showModalUnique('<span style="color:red;">' + (data.error || 'No se pudo eliminar la entrada.') + '</span>', 'danger');
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

														xhr.send(JSON.stringify({ Id_det_producto_proveedor: Id_det_producto_proveedor }));
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

