<!-- Panel listado de productos -->
<div class="container-fluid">
       
		<div class="panel panel-success">
			<div class="panel-heading" style="background-color: #d9534f; color: #fff;">
				<h3 class="panel-title"><i class="zmdi zmdi-format-list-bulleted"></i> &nbsp; LISTA DE SALIDAS</h3>
			</div>
			<div class="panel-body">
				<div class="table-responsive">
					<table class="table table-hover text-center">
						<thead>
							<tr>
								<th class="text-center">#</th>
								<th class="text-center">NOMBRE TECNICO</th>
								<th class="text-center">USUARIO QUE REGISTRÓ</th>
								<th class="text-center">NOMBRE PRODUCTO</th>
								<th class="text-center">FECHA RETIRO</th>
								<th class="text-center">CANTIDAD</th>
								<th class="text-center">OBSERVACIÓN</th>
								<th class="text-center">ESTADO</th>
								<th class="text-center">ACTUALIZAR</th>
								<th class="text-center">ELIMINAR</th>
							</tr>
						</thead>
						<tbody>
							<?php
								// Obtener el número de registros por página
								$por_pagina = 5;

								// Obtener el número de página actual
								$pagina_actual = (isset($_GET['pagina'])) ? $_GET['pagina'] : 1;

								// Calcular el límite inferior y superior
								$limite_inferior = ($pagina_actual - 1) * $por_pagina;
								$limite_superior = $pagina_actual * $por_pagina;

								// Consultar el número total de registros
								$stmt = $pdo->prepare("SELECT COUNT(*) AS total_registros FROM detalle_tecnico_producto");
								$stmt->execute();
								$total_registros = $stmt->fetch(PDO::FETCH_ASSOC)['total_registros'];

								// Calcular el número total de páginas
								$total_paginas = ceil($total_registros / $por_pagina);

								

								// Consultar los registros para la página actual
								$query = "
								SELECT 
									dtp.Id_det_tecnico_producto, 
									CONCAT(p.Nombre, ' ', p.Apellido_paterno, ' ', p.Apellido_materno) AS tecnico, 
									dp.Nombre AS Nom_producto, 
									dtp.Fecha_retiro, 
									dtp.cantidad, 
									dtp.Observación, 
									dtp.Estado, 
									u.Nombre_usuario
								FROM 
									detalle_tecnico_producto dtp
								INNER JOIN tecnico t ON dtp.ID_tecnico = t.ID_tecnico
								INNER JOIN personal p ON t.id_personal = p.ID_personal
								INNER JOIN productos dp ON dtp.ID_producto = dp.id_producto
								INNER JOIN usuario u ON dtp.ID_usuario = u.ID_usuario
								ORDER BY 
									dtp.Id_det_tecnico_producto DESC
								LIMIT $limite_inferior, $por_pagina
								";

								$stmt = $pdo->prepare($query);
								$stmt->execute();

								$contador = 0;
								while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
								<tr>
									<td><?php echo htmlspecialchars(++$contador); ?></td>
									<td><?php echo htmlspecialchars($row['tecnico']); ?></td>
									<td><?php echo htmlspecialchars($row['Nombre_usuario']); ?></td>
									<td><?php echo htmlspecialchars($row['Nom_producto']); ?></td>
									<td><?php echo htmlspecialchars($row['Fecha_retiro']); ?></td>
									<td><?php echo htmlspecialchars($row['cantidad']); ?></td>
									<td><?php echo htmlspecialchars($row['Observación']); ?></td>
									<td>
										<?php echo htmlspecialchars($row['Estado'] ? 'Activo' : 'Inactivo'); ?>
									</td>
								
									<td>
										<button type="button" class="btn btn-primary btn-raised btn-xs" onclick="window.location.href = 'editar.php?Id_det_tecnico_producto=<?php echo $row['Id_det_tecnico_producto']; ?>'">
											<i class="zmdi zmdi-edit"></i>
										</button>
									</td>
									<td>
										<button type="button" class="btn btn-danger btn-raised btn-xs" onclick="deleteSalidaUnique(<?php echo htmlspecialchars($row['Id_det_tecnico_producto']); ?>);">
											<i class="zmdi zmdi-delete"></i>
										</button>
										<script>
											function deleteSalidaUnique(Id_det_tecnico_producto) {
												showConfirmModalUnique('¿Está seguro de que desea eliminar esta salida?', function() {
													var xhr = new XMLHttpRequest();
													xhr.open('POST', '<?php echo $URL; ?>/app/controllers/inventario/salida/eliminar_salida.php', true);
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
																	showModalUnique('<span style="color:red;">' + (data.error || 'No se pudo eliminar la salida.') + '</span>', 'danger');
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

													xhr.send(JSON.stringify({ Id_det_tecnico_producto: Id_det_tecnico_producto }));
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
				<!-- Paginación -->
				<div class="row">
							<div class="col-xs-12">
								<ul class="pagination pagination-sm">
									<li class="page-item <?php echo ($pagina_actual == 1) ? 'disabled' : ''; ?>">
										<a class="page-link" href="<?php echo ($pagina_actual > 1) ? '?pagina=' . ($pagina_actual - 1) : '#'; ?>" aria-label="Previous">
											<span aria-hidden="true">&laquo;</span>
											<span class="sr-only">Anterior</span>
										</a>
									</li>

									<?php for ($i = 1; $i <= $total_paginas; $i++): ?>
										<li class="page-item <?php echo ($i == $pagina_actual) ? 'active' : ''; ?>">
											<a class="page-link" href="<?php echo '?pagina=' . $i; ?>"><?php echo $i; ?></a>
										</li>
									<?php endfor; ?>

									<li class="page-item <?php echo ($pagina_actual == $total_paginas) ? 'disabled' : ''; ?>">
										<a class="page-link" href="<?php echo ($pagina_actual < $total_paginas) ? '?pagina=' . ($pagina_actual + 1) : '#'; ?>" aria-label="Next">
											<span aria-hidden="true">&raquo;</span>
											<span class="sr-only">Siguiente</span>
										</a>
									</li>
								</ul>
							</div>
						</div>
			</div>
		</div>
	</div>
