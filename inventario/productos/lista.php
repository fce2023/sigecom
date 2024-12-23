
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
								<th class="text-center"># </th>
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
							$items_per_page = 5;
							$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
							$offset = ($page - 1) * $items_per_page;

							$total_items_query = $pdo->query("SELECT COUNT(*) FROM productos");
							$total_items = $total_items_query->fetchColumn();
							$total_pages = ceil($total_items / $items_per_page);

							echo "<h4>Paginación: Página $page de $total_pages. Mostrando $items_per_page de $total_items registros</h4>";

							$query = "SELECT p.id_producto, p.nombre, p.descripcion, tp.Nom_producto, p.precio, 
							          (SELECT COALESCE(SUM(dpp.Cantidad), 0) - COALESCE(SUM(dtp.Cantidad), 0) 
							           FROM detalle_producto_proveedor dpp 
							           LEFT JOIN detalle_tecnico_producto dtp ON dpp.ID_producto = dtp.ID_producto 
							           WHERE dpp.ID_producto = p.id_producto) AS stock, 
							          p.fecha_registro, p.estado 
							          FROM productos p 
							          JOIN tipo_producto tp ON p.id_tipo_producto = tp.ID_tipo_producto
							          LIMIT :offset, :items_per_page";
							$stmt = $pdo->prepare($query);
							$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
							$stmt->bindParam(':items_per_page', $items_per_page, PDO::PARAM_INT);
							$stmt->execute();

							while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
								<tr>
									<td><?= ($offset++) + 1; ?></td>
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
									</td>
								</tr>
							<?php endwhile; ?>
						</tbody>

						<tfoot>
							<tr>
								<td colspan="9">
									<nav aria-label="Page navigation">
										<ul class="pagination justify-content-center">
											<?php if ($page > 1): ?>
												<li class="page-item">
													<a class="page-link" href="?page=<?php echo $page - 1; ?>" aria-label="Previous">
														<span aria-hidden="true">&laquo;</span>
													</a>
												</li>
											<?php endif; ?>
											<?php for ($i = 1; $i <= $total_pages; $i++): ?>
												<li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
													<a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
												</li>
											<?php endfor; ?>
											<?php if ($page < $total_pages): ?>
												<li class="page-item">
													<a class="page-link" href="?page=<?php echo $page + 1; ?>" aria-label="Next">
														<span aria-hidden="true">&raquo;</span>
													</a>
												</li>
											<?php endif; ?>
										</ul>
									</nav>
								</td>
							</tr>
						</tfoot>
					</table>
				</div>
			</div>
		</div>
	</div>
    
