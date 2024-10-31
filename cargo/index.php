<?php

include ('../app/config.php');
include ('../layout/sesion.php');
include ('../layout/parte1.php');
include ('../app/controllers/cargo/listado_de_cargo.php');


?>


<!DOCTYPE html>
<html lang="es">
<head>
	<title>Admin</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	
</head>
<body>
	

		<!-- Content page -->
		<div class="container-fluid">
			<div class="page-header">
			  <h1 class="text-titles"><i class="zmdi zmdi-account zmdi-hc-fw"></i> Lista <small>Cargo</small></h1>
			</div>
		</div>

		<div class="container-fluid">
			<ul class="breadcrumb breadcrumb-tabs">
			  	<li>
			  		                    <a href="crear.php" class="btn btn-info">
			  			<i class="zmdi zmdi-plus"></i> &nbsp; NUEVO CARGO
			  		</a>
			  	</li>

			</ul>
		</div>
		
		<!-- Panel listado de personal -->
		<div class="container-fluid">
			<div class="panel panel-success">
				<div class="panel-heading">
					<h3 class="panel-title"><i class="zmdi zmdi-format-list-bulleted"></i> &nbsp; LISTA DE CARGO</h3>
				</div>
				<div class="panel-body">
					<div class="table-responsive">
						<table class="table table-hover text-center">
							<thead>
								<tr>
									<th class="text-center">#</th>
									<th class="text-center">NOMBRE DEL CARGO</th>
									<th class="text-center">ESTADO</th>
									<th class="text-center">ELIMINAR</th>
								</tr>
							</thead>
							<tbody>
								<?php
									foreach ($cargos as $fila) {
								?>
								<tr>
									<td><?php echo $fila['ID_cargo']; ?></td>
									<td><?php echo $fila['Nom_cargo']; ?></td>
									<td><?php echo $fila['Estado'] == 1 ? 'Activo' : 'Inactivo'; ?></td>
							
									<td>
										<form>
											<button type="submit" class="btn btn-danger btn-raised btn-xs">
												<i class="zmdi zmdi-delete"></i>
											</button>
										</form>
									</td>
								</tr>
								<?php
									}
								?>
							</tbody>
						</table>
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
				</div>
			</div>
		</div>
		
		
	</section>


</body>
</html>
