
<?php

include ('../../app/config.php');
include ('../../layout/sesion.php');
include ('../../layout/parte1.php');
?>



	<!-- Panel listado de productos -->
	<div class="container-fluid">
        <?php include ('layout/parte1.php'); ?>
		<div class="panel panel-success">
			<div class="panel-heading">
				<h3 class="panel-title"><i class="zmdi zmdi-format-list-bulleted"></i> &nbsp; LISTA DE PRODUCTOS</h3>
			</div>
			<div class="panel-body">
			<div class="container-fluid">
		<ul class="breadcrumb breadcrumb-tabs">
			<li>
				<a href="<?php echo $URL; ?>/app/controllers/inventario/productos/generar_pdf.php" target="_blank" class="btn btn-info">
					<i class="zmdi zmdi-file-pdf"></i> &nbsp; GENERAR PDF
				</a>
			</li>
			<li>
				<a href="<?php echo $URL; ?>/app/controllers/inventario/productos/generar_exel.php" target="_blank" class="btn btn-success">
					<i class="zmdi zmdi-file-excel"></i> &nbsp; GENERAR EXCEL
				</a>
			</li>
		</ul>
	</div>
				<div class="table-responsive">
					<table class="table table-hover text-center">
						<thead>
							<tr>
								<th class="text-center">#</th>
								<th class="text-center">NOMBRE</th>
								<th class="text-center">DESCRIPCIÓN</th>
								<th class="text-center">TIPO DE PRODUCTO</th>
								<th class="text-center">PRECIO</th>
								<th class="text-center">STOCK</th>
								<th class="text-center">FECHA DE REGISTRO</th>
								<th class="text-center">ACTUALIZAR</th>
								<th class="text-center">ELIMINAR</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$query = "SELECT p.id_producto, p.nombre, p.descripcion, tp.Nom_producto, p.precio, 
							          (SELECT COALESCE(SUM(dpp.Cantidad), 0) - COALESCE(SUM(dtp.Cantidad), 0) 
							           FROM detalle_producto_proveedor dpp 
							           LEFT JOIN detalle_tecnico_producto dtp ON dpp.ID_producto = dtp.ID_producto 
							           WHERE dpp.ID_producto = p.id_producto) AS stock, 
							          p.fecha_registro, p.estado 
							          FROM productos p 
							          JOIN tipo_producto tp ON p.id_tipo_producto = tp.ID_tipo_producto";
							$stmt = $pdo->query($query);
							while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
								<tr>
									<td><?php echo htmlspecialchars($row['id_producto']); ?></td>
									<td><?php echo htmlspecialchars($row['nombre']); ?></td>
									<td><?php echo htmlspecialchars($row['descripcion']); ?></td>
									<td><?php echo htmlspecialchars($row['Nom_producto']); ?></td>
									<td><?php echo number_format($row['precio'], 2); ?></td>
									<td><?php echo htmlspecialchars($row['stock']); ?></td>
									<td><?php echo htmlspecialchars($row['fecha_registro']); ?></td>
									<td>
										<button type="button" class="btn btn-primary btn-raised btn-xs" onclick="window.location.href = 'editar.php?id_producto=<?php echo $row['id_producto']; ?>'">
											<i class="zmdi zmdi-edit"></i>
										</button>
									</td>
									<td>
										<button type="button" class="btn btn-danger btn-raised btn-xs" onclick="deleteProducto(<?php echo htmlspecialchars($row['id_producto']); ?>);">
											<i class="zmdi zmdi-delete"></i>
										</button>
										<script>
											function deleteProducto(id_producto) {
												showConfirmModal('¿Está seguro de que desea eliminar este producto?', function() {
													var xhr = new XMLHttpRequest();
													xhr.open('POST', '../../app/controllers/inventario/productos/eliminar.php', true);
													xhr.onload = function() {
														try {
															if (xhr.status === 200) {
																var data = JSON.parse(xhr.responseText);
																if (data.success) {
																	window.location.href = '<?php echo $URL; ?>inventario/productos/lista.php';
																} else {
																	showModal('Error: ' + (data.error || 'No se pudo eliminar el producto.'), 'danger');
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

													xhr.send(JSON.stringify({ id_producto: id_producto }));
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
																	<h5 class="modal-title" id="mensajeModalLabel">${type === 'success' ? 'Éxito' : 'Error'}</h5>
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
						<li class="active"><a href="javascript:void(0)">1</a></li>
						<li><a href="javascript:void(0)">2</a></li>
						<li><a href="javascript:void(0)">3</a></li>
						<li><a href="javascript:void(0)">4</a></li>
						<li><a href="javascript:void(0)">5</a></li>
						<li><a href="javascript:void(0)">»</a></li>
					</ul>
	</nav>
