<div class="container-fluid">
			<ul class="breadcrumb breadcrumb-tabs">
			  	
				
				<li>
				
				
				
				 
				  <li>
			  		<a href="<?php echo $URL;?>inventario/tecnico/salida/lista.php" class="btn btn-danger">
			  			<i class="zmdi zmdi-format-list-bulleted"></i> &nbsp; Salida
			  		</a>
			  	</li>
				

				  <li>
					<li class="dropdown">
						<a href="#" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							<i class="zmdi zmdi-file-pdf"></i> &nbsp; Reportes en pdf
						</a>
						<ul class="dropdown-menu">
							<li>
								<a href="<?php echo $URL;?>/app/controllers/inventario/generar_pdf/generar_entrada.php" class="dropdown-item">
									<i class="zmdi zmdi-file-pdf"></i> &nbsp; Entrada.PDF
								</a>
							</li>
							<li>
								<a href="<?php echo $URL;?>/app/controllers/inventario/generar_pdf/generar_salida.php" class="dropdown-item">
									<i class="zmdi zmdi-file-pdf"></i> &nbsp; Salida.pdf
								</a>
							</li>
							<li>
								<a href="<?php echo $URL;?>/app/controllers/inventario/generar_pdf/generar_movimientos.php" class="dropdown-item">
									<i class="zmdi zmdi-file-pdf"></i> &nbsp; movimientos.pdf
								</a>
							</li>
						</ul>
					</li>

					<li>
					<li class="dropdown">
						<a href="#" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							<i class="zmdi zmdi-file-excel"></i> &nbsp; Reportes en exel
						</a>
						<ul class="dropdown-menu">
							<li>
								<a href="<?php echo $URL;?>/app/controllers/inventario/generar_exel/generar_entrada.php" class="dropdown-item">
									<i class="zmdi zmdi-file-excel"></i> &nbsp; Entrada.Exel
								</a>
							</li>
							<li>
								<a href="<?php echo $URL;?>/app/controllers/inventario/generar_exel/generar_salida.php" class="dropdown-item">
									<i class="zmdi zmdi-file-excel"></i> &nbsp; Salida.Exel
								</a>
							</li>
							<li>
								<a href="<?php echo $URL;?>/app/controllers/inventario/generar_exel/generar_movimientos.php" class="dropdown-item">
									<i class="zmdi zmdi-file-excel"></i> &nbsp; movimientos.Exel
								</a>
							</li>
						</ul>
					</li>


				</li>
				<li>
				
			</ul>
