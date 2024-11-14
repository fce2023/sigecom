
<?php

include ('../../app/config.php');
include ('../../layout/sesion.php');
include ('../../layout/parte1.php');
?>


	<!-- Panel listado de movimientos -->
	<div class="container-fluid">
        <?php include ('layout/parte1.php'); ?>
		<div class="panel panel-primary">
        <form method="GET" action="" class="estado-filter-form">
            <label for="filtroTipo">Mostrar:</label>
            <select id="filtroTipo" name="filtroTipo" class="form-control estado-filter-select" onchange="this.form.submit()">
                <option value="todos" <?php echo (isset($_GET['filtroTipo']) && $_GET['filtroTipo'] == 'todos') ? 'selected' : ''; ?>>Mostrar todos</option>
                <option value="Entrada" <?php echo (isset($_GET['filtroTipo']) && $_GET['filtroTipo'] == 'Entrada') ? 'selected' : ''; ?>>Mostrar solo Entrada</option>
                <option value="Salida" <?php echo (isset($_GET['filtroTipo']) && $_GET['filtroTipo'] == 'Salida') ? 'selected' : ''; ?>>Mostrar solo Salida</option>
            </select>
        </form>
			<div class="panel-heading">
				<h3 class="panel-title"><i class="zmdi zmdi-format-list-bulleted"></i> &nbsp; LISTA DE MOVIMIENTOS</h3>
			</div>
			<div class="panel-body">
				<div class="table-responsive">
					<table class="table table-hover text-center">
						<thead>
							<tr>
								<th class="text-center">#</th>
								<th class="text-center">ID DETALLE</th>
								<th class="text-center">TIPO</th>
								<th class="text-center">NOMBRE PRODUCTO</th>
								<th class="text-center">FECHA</th>
								<th class="text-center">CANTIDAD</th>
								<th class="text-center">OBSERVACIÓN</th>
								<th class="text-center">ESTADO</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$filtroTipo = isset($_GET['filtroTipo']) ? $_GET['filtroTipo'] : 'todos';

							$query = "SELECT dpp.Id_det_producto_proveedor AS id_detalle, 'Entrada' AS tipo, p.nombre AS nombre_producto, 
							                  dpp.Fecha_abastecimiento AS fecha, dpp.Cantidad, dpp.Observación, dpp.Estado
							           FROM detalle_producto_proveedor dpp
							           JOIN productos p ON dpp.ID_producto = p.id_producto 
                                       UNION ALL
							           SELECT dtp.Id_det_tecnico_producto AS id_detalle, 'Salida' AS tipo, p.nombre AS nombre_producto, 
							                  dtp.Fecha_retiro AS fecha, dtp.Cantidad, dtp.Observación, dtp.Estado
							           FROM detalle_tecnico_producto dtp
							           JOIN productos p ON dtp.ID_producto = p.id_producto";

							if ($filtroTipo == 'Entrada') {
							    $query = "SELECT * FROM (" . $query . ") AS movimientos WHERE tipo = 'Entrada'";
							} elseif ($filtroTipo == 'Salida') {
							    $query = "SELECT * FROM (" . $query . ") AS movimientos WHERE tipo = 'Salida'";
							}

							$stmt = $pdo->query($query, PDO::FETCH_ASSOC);
							$num = 1;
							while ($row = $stmt->fetch()) {
								echo "<tr>
									<td>" . $num . "</td>
									<td>" . $row['id_detalle'] . "</td>
									<td style='color: " . (($row['tipo'] == 'Entrada') ? 'green' : 'red') . ";'>" . $row['tipo'] . "</td>
									<td>" . $row['nombre_producto'] . "</td>
									<td>" . $row['fecha'] . "</td>
									<td>" . $row['Cantidad'] . "</td>
									<td>" . $row['Observación'] . "</td>
									<td>" . $row['Estado'] . "</td>
								</tr>";
								$num++;
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

	<!-- Panel stock de productos -->
	<div class="container-fluid">
		<div class="panel panel-info">
			<div class="panel-heading">
				<h3 class="panel-title"><i class="zmdi zmdi-chart"></i> &nbsp; STOCK DE PRODUCTOS</h3>
			</div>
			<div class="panel-body">
				<div class="table-responsive">
					<table class="table table-hover text-center">
						<thead>
							<tr>
								<th class="text-center">NOMBRE PRODUCTO</th>
								<th class="text-center">STOCK</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$query = "SELECT p.nombre, SUM(dpp.Cantidad) - SUM(dtp.Cantidad) AS stock
							          FROM productos p
							          LEFT JOIN detalle_producto_proveedor dpp ON p.id_producto = dpp.ID_producto
							          LEFT JOIN detalle_tecnico_producto dtp ON p.id_producto = dtp.ID_producto
							          GROUP BY p.id_producto";
							$stmt = $pdo->query($query);
							while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
								echo "<tr>
									<td>" . $row['nombre'] . "</td>
									<td>" . $row['stock'] . "</td>
								</tr>";
							}
							?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
