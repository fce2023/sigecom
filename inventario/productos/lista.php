
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
							while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
								echo "<tr>
									<td>" . $row['id_producto'] . "</td>
									<td>" . $row['nombre'] . "</td>
									<td>" . $row['descripcion'] . "</td>
									<td>" . $row['Nom_producto'] . "</td>
									<td>" . number_format($row['precio'], 2) . "</td>
									<td>" . $row['stock'] . "</td>
									<td>" . $row['fecha_registro'] . "</td>
									<td>
										<a href='editar.php?id=" . $row['id_producto'] . "' class='btn btn-success btn-raised btn-xs'>
											<i class='zmdi zmdi-refresh'></i>
										</a>
									</td>
									<td>
										<form method='post' action='eliminar.php'>
											<input type='hidden' name='id_producto' value='" . $row['id_producto'] . "'>
											<button type='submit' class='btn btn-danger btn-raised btn-xs'>
												<i class='zmdi zmdi-delete'></i>
											</button>
										</form>
									</td>
								</tr>";
							}
							?>
							
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
