
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
					<form action="../../app/controllers/inventario/productos/guardar_tipo_producto.php" method="post">
				    	<fieldset>
				    		<legend><i class="zmdi zmdi-assignment-o"></i> &nbsp; Información del tipo de producto</legend>
				    		<div class="container-fluid">
				    			<div class="row">
				    				<div class="col-xs-12">
								    	<div class="form-group label-floating">
										  	<label class="control-label">Nombre *</label>
										  	<input pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,10}" class="form-control" type="text" name="nombre-reg" required="" maxlength="10">
										</div>
				    				</div>
				    				<div class="col-xs-12">
								    	<div class="form-group label-floating">
										  	<label class="control-label">Estado</label>
										  	<select class="form-control" name="estado-reg">
										  		<option value="">Seleccione una opción</option>
										  		<option value="Activo">Activo</option>
										  		<option value="Inactivo">Inactivo</option>
										  	</select>
										</div>
				    				</div>
				    			</div>
				    		</div>
				    	</fieldset>
					    <p class="text-center" style="margin-top: 20px;">
					    	<button type="submit" class="btn btn-info btn-raised btn-sm"><i class="zmdi zmdi-floppy"></i> Guardar</button>
					    </p>
				    </form>
				</div>
			</div>
		</div>

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
								<th class="text-center">NOMBRE TIPO DE PRODUCTO</th>
								<th class="text-center">ESTADO</th>
								<th class="text-center">ACTUALIZAR</th>
								<th class="text-center">ELIMINAR</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$query = "SELECT ID_tipo_producto, Nom_producto, Estado FROM tipo_producto";
							$stmt = $pdo->query($query);
							$count = 1;
							while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
								echo "<tr>
									<td>" . $count . "</td>
									<td>" . $row['Nom_producto'] . "</td>";
								if ($row['Estado'] == 1) {
									echo "<td>Activo</td>";
								} else {
									echo "<td>Inactivo</td>";
								}
								echo "<td>
										<a href='editar_tipo_producto.php?id=" . $row['ID_tipo_producto'] . "' class='btn btn-success btn-raised btn-xs'>
											<i class='zmdi zmdi-refresh'></i>
										</a>
									</td>
									<td>
										<form method='post' action='eliminar_tipo_producto.php'>
											<input type='hidden' name='ID_tipo_producto' value='" . $row['ID_tipo_producto'] . "'>
											<button type='submit' class='btn btn-danger btn-raised btn-xs'>
												<i class='zmdi zmdi-delete'></i>
											</button>
										</form>
									</td>
								</tr>";
								$count++;
							}
							?>
							
						</tbody>
					</table>
				</div>
			</div>
		</div>
