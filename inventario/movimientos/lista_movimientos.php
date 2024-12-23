
<?php

include ('../../app/config.php');
include ('../../layout/sesion.php');
include ('../../layout/parte1.php');
?>




	<!-- Panel listado de movimientos -->
	<div class="container-fluid">
        <?php include ('layout/parte1.php'); ?>

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
                            $query = "SELECT p.nombre, 
                                      COALESCE((SELECT SUM(dpp.cantidad) FROM detalle_producto_proveedor dpp WHERE dpp.ID_producto = p.id_producto), 0) + 
                                      COALESCE((SELECT SUM(dtp.cantidad) FROM detalle_tecnico_producto dtp WHERE dtp.ID_producto = p.id_producto AND dtp.tipo_movimiento = 'Entrada'), 0) - 
                                      COALESCE((SELECT SUM(dtp.cantidad) FROM detalle_tecnico_producto dtp WHERE dtp.ID_producto = p.id_producto AND dtp.tipo_movimiento = 'Salida'), 0) AS stock
                                      FROM productos p
                                      GROUP BY p.id_producto";
                            $stmt = $pdo->query($query);
                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                if ($row['stock'] <= 0) {
                                    echo "<tr>
                                        <td>" . htmlspecialchars($row['nombre']) . "</td>
                                        <td class='text-danger'><strong>STOCK AGOTADO</strong></td>
                                    </tr>";
                                } else {
                                    echo "<tr>
                                        <td>" . htmlspecialchars($row['nombre']) . "</td>
                                        <td>" . max(0, htmlspecialchars($row['stock'])) . "</td>
                                    </tr>";
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


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
								<th class="text-center">N°</th>
								<th class="text-center">TIPO</th>
								<th class="text-center">USUARIO QUE REALIZÓ MOVIMIENTO</th>
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
                            
                            // Pagination settings
                            $itemsPerPage = 5;
                            $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                            $offset = ($currentPage - 1) * $itemsPerPage;

                            // Remove pagination button logic
                           

                            $query = "SELECT dpp.Id_det_producto_proveedor AS id_detalle, 'Entrada' AS tipo, p.nombre AS nombre_producto, 
                                              dpp.Fecha_abastecimiento AS fecha, dpp.Cantidad, dpp.Observación, dpp.Estado, u.Nombre_usuario
                                       FROM detalle_producto_proveedor dpp
                                       JOIN productos p ON dpp.ID_producto = p.id_producto
                                       JOIN usuario u ON dpp.ID_usuario = u.ID_usuario
                                       UNION ALL
                                       SELECT dtp.Id_det_tecnico_producto AS id_detalle, 'Salida' AS tipo, p.nombre AS nombre_producto, 
                                              dtp.Fecha_retiro AS fecha, dtp.Cantidad, dtp.Observación, dtp.Estado, u.Nombre_usuario
                                       FROM detalle_tecnico_producto dtp
                                       JOIN productos p ON dtp.ID_producto = p.id_producto
                                       JOIN usuario u ON dtp.ID_usuario = u.ID_usuario";

                            if ($filtroTipo == 'Entrada') {
                                $query = "SELECT * FROM (" . $query . ") AS movimientos WHERE tipo = 'Entrada'";
                            } elseif ($filtroTipo == 'Salida') {
                                $query = "SELECT * FROM (" . $query . ") AS movimientos WHERE tipo = 'Salida'";
                            }

                            // Get the total number of records
                            $totalQuery = "SELECT COUNT(*) FROM (" . $query . ") AS total";
                            $totalStmt = $pdo->query($totalQuery);
                            $totalRecords = $totalStmt->fetchColumn();
                            $totalPages = ceil($totalRecords / $itemsPerPage);

                            // Add pagination to the query
                            $query .= " LIMIT $offset, $itemsPerPage";

                            $stmt = $pdo->query($query, PDO::FETCH_ASSOC);
                            $num = $offset + 1;
                            while ($row = $stmt->fetch()) {

                                $id_detalle = $row['id_detalle'];
                                $tipo = $row['tipo'];
                                $nombre_producto = $row['nombre_producto'];
                                $fecha = $row['fecha'];
                                $cantidad = $row['Cantidad'];
                                $observación = $row['Observación'];
                                $estado = $row['Estado'];
                                $nombre_usuario = $row['Nombre_usuario'];
                                ?>
                                <tr>
                                    <td><?php echo $num; ?></td>
                                    <td style='color: <?php echo ($row['tipo'] == 'Entrada') ? 'green' : 'red'; ?>;'><?php echo $row['tipo']; ?></td>
                                    <td><?php echo $row['Nombre_usuario']; ?></td>
                                    <td><?php echo $row['nombre_producto']; ?></td>
                                    <td><?php echo $row['fecha']; ?></td>
                                    <td><?php echo $row['Cantidad']; ?></td>
                                    <td><?php echo $row['Observación']; ?></td>
                                    <td><?php echo $row['Estado']; ?></td>
                        
                                </tr>

                                <?php
                                $num++;
                            }
                            ?>

                        </tbody>
                    </table>

                    <!-- Pagination controls -->
                    <div class="pagination">
                        <?php if ($currentPage > 1): ?>
                            <a href="?page=<?php echo $currentPage - 1; ?>&filtroTipo=<?php echo $filtroTipo; ?>" class="button">Anterior</a>
                        <?php endif; ?>
                        
                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <a href="?page=<?php echo $i; ?>&filtroTipo=<?php echo $filtroTipo; ?>" <?php if ($i == $currentPage) echo 'class="active"'; ?>><?php echo $i; ?></a>
                        <?php endfor; ?>
                        
                        <a href="?page=<?php echo min($currentPage + 1, $totalPages); ?>&filtroTipo=<?php echo $filtroTipo; ?>" class="button">Siguiente</a>
                    </div>



					







				</div>
			</div>
		</div>
	</div>
  
	 

