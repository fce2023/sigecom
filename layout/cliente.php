
<div class="container-fluid">
	<ul class="breadcrumb breadcrumb-tabs">
		<li class="dropdown">
			<a href="#" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
				<i class="zmdi zmdi-account-add"></i> &nbsp; Clientes <span class="caret"></span>
			</a>
			<ul class="dropdown-menu">
				<li><a href="<?php echo $URL;?>atencion_cliente/clientes/crear_cliente.php">Agregar Cliente</a></li>
				<li><a href="<?php echo $URL;?>atencion_cliente/clientes/lista_clientes.php">Lista de clientes</a></li>
			</ul>
		</li>
		<li class="dropdown">
			<a href="#" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
				<i class="zmdi zmdi-plus-circle"></i> &nbsp; Pedidos <span class="caret"></span>
			</a>
			<ul class="dropdown-menu">
				<li><a href="<?php echo $URL;?>atencion_cliente/pedidos/crear_pedido.php">Agregar Pedido</a></li>
				<li><a href="<?php echo $URL;?>atencion_cliente/pedidos/lista_pedidos.php">Ver Pedidos</a></li>
			</ul>
		</li>
	</ul>
</div>

