

<!DOCTYPE html>
<html lang="es">
<head>
	<title>Inicio</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<link rel="stylesheet" href="<?php echo $URL; ?>/plant/css/main.css">
</head>
<body>
	<!-- SideBar -->
	<section class="full-box cover dashboard-sideBar">
		<div class="full-box dashboard-sideBar-bg btn-menu-dashboard"></div>
		<div class="full-box dashboard-sideBar-ct">
			<!--SideBar Title -->
			<div class="full-box text-uppercase text-center text-titles dashboard-sideBar-title">
				SIGECOM <i class="zmdi zmdi-close btn-menu-dashboard visible-xs"></i>
			</div>
			<!-- SideBar User info -->
			<div class="full-box dashboard-sideBar-UserInfo">
				<figure class="full-box">
					<img src="<?php echo $URL; ?>/plant/assets/avatars/AdminMaleAvatar.png" alt="UserIcon">
					<figcaption class="text-center text-titles"><?php echo $nombres_sesion; ?></figcaption>
				</figure>
				<ul class="full-box list-unstyled text-center">
					<li>
						<a href="<?php echo $URL; ?>/my_data" title="Mis datos">
							<i class="zmdi zmdi-account-circle"></i>
						</a>
					</li>
					<li>
						<a href="<?php echo $URL; ?>/my_account" title="Mi cuenta">
							<i class="zmdi zmdi-settings"></i>
						</a>
					</li>
					<li>
    <a href="#" title="Cerrar Sesión" onclick="confirmacionCerrarSesion(event)">
        <i class="zmdi zmdi-power"></i>
    </a>
</li>

<!-- Modal de confirmación de cierre de sesión -->
<div id="customConfirmModalLogout" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>¿Estás seguro que deseas cerrar sesión?</h2>
        </div>
        <div class="modal-footer">
            <button id="confirmDeleteLogout">Sí</button>
            <button id="cancelDeleteLogout">No</button>
        </div>
    </div>
</div>

<script>
function confirmacionCerrarSesion(e) {
    e.preventDefault(); // Prevenir el envío del formulario por defecto

    // Mostrar el modal de confirmación
    var modal = document.getElementById("customConfirmModalLogout");
    modal.style.display = "block";

    // Configurar el comportamiento del botón de confirmación
    document.getElementById("confirmDeleteLogout").onclick = function() {
        window.location.href = "<?php echo $URL; ?>/app/controllers/login/cerrar_sesion.php";
    };

    // Configurar el comportamiento del botón de cancelación
    document.getElementById("cancelDeleteLogout").onclick = function() {
        modal.style.display = "none"; // Cerrar el modal si el usuario cancela
    };

    // Cerrar el modal si el usuario hace clic fuera de él
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    };
}
</script>

<style>
/* Estilo para el modal de confirmación de cierre de sesión */
#customConfirmModalLogout {
    display: none;
    position: fixed;
    z-index: 1;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.7);
    overflow: auto;
    backdrop-filter: blur(5px); /* Añadir desenfoque para un efecto más elegante */
}

/* Estilo del contenido del modal */
#customConfirmModalLogout .modal-content {
    background-color: #fff;
    margin: 15% auto;
    padding: 30px;
    border-radius: 10px;
    width: 70%;
    max-width: 500px;
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
    color: #333;
    text-align: center;
}

/* Estilo del título */
#customConfirmModalLogout .modal-header h2 {
    margin: 0 0 20px;
    color: #444;
    font-size: 24px;
    font-weight: bold;
}

/* Estilo de los botones */
#customConfirmModalLogout .modal-footer button {
    padding: 12px 25px;
    font-size: 16px;
    margin: 10px;
    cursor: pointer;
    border: none;
    border-radius: 25px;
    color: #fff;
    transition: background-color 0.3s, transform 0.3s;
}

/* Estilo del botón de confirmación */
#customConfirmModalLogout .modal-footer #confirmDeleteLogout {
    background-color: #3085d6;
}

#customConfirmModalLogout .modal-footer #confirmDeleteLogout:hover {
    background-color: #267ac8;
    transform: scale(1.05);
}

/* Estilo del botón de cancelación */
#customConfirmModalLogout .modal-footer #cancelDeleteLogout {
    background-color: #d33;
}

#customConfirmModalLogout .modal-footer #cancelDeleteLogout:hover {
    background-color: #b22;
    transform: scale(1.05);
}

</style>


				</ul>
			</div>
			<!-- SideBar Menu -->
			<ul class="list-unstyled full-box dashboard-sideBar-Menu">
				<li>
					<a href="<?php echo $URL; ?>/home.php">
						<i class="zmdi zmdi-home zmdi-hc-fw"></i> Inicio
					</a>
				</li>

				
				<?php if ($rol_sesion === "superadministrador"): ?>
					
				<li>
					<a href="#!" class="btn-sideBar-SubMenu">
						<i class="zmdi zmdi-graduation-cap zmdi-hc-fw"></i> Administración de Personal <i class="zmdi zmdi-caret-down pull-right"></i>
					</a>
					<ul class="list-unstyled full-box">
						<li>
							<a href="<?php echo $URL; ?>/personal/index.php"><i class="zmdi zmdi-graduation-cap zmdi-hc-fw"></i> Listado de Personal</a>
						</li>

                        <li>
							<a href="<?php echo $URL; ?>/personal/crear.php"><i class="zmdi zmdi-plus zmdi-hc-fw"></i> Agregar Personal</a>
						</li>

					</ul>
				</li>

				

				<li>
					<a href="#!" class="btn-sideBar-SubMenu">
						<i class="zmdi zmdi-box zmdi-hc-fw"></i> Administración de Cargo <i class="zmdi zmdi-caret-down pull-right"></i>
					</a>
					<ul class="list-unstyled full-box">
						<li>
							<a href="<?php echo $URL; ?>/cargo/index.php"><i class="zmdi zmdi-box zmdi-hc-fw"></i> Listado de Cargo</a>
						</li>

                        <li>
							<a href="<?php echo $URL; ?>/cargo/crear.php"><i class="zmdi zmdi-plus zmdi-hc-fw"></i> Agregar Cargo</a>
						</li>



					</ul>
				</li>

				<li>
					<a href="#!" class="btn-sideBar-SubMenu">
						<i class="zmdi zmdi-accounts zmdi-hc-fw"></i> Administración de Usuario <i class="zmdi zmdi-caret-down pull-right"></i>
					</a>
					<ul class="list-unstyled full-box">
						<li>
							<a href="<?php echo $URL; ?>/usuario/index.php"><i class="zmdi zmdi-accounts zmdi-hc-fw"></i> Listado de Usuario</a>
						</li>

                        <li>
							<a href="<?php echo $URL; ?>/usuario/crear.php"><i class="zmdi zmdi-plus zmdi-hc-fw"></i> Agregar Usuario</a>
						</li>
					</ul>
				</li>



				<li>
					<a href="#!" class="btn-sideBar-SubMenu">
						<i class="zmdi zmdi-group zmdi-hc-fw"></i> Administración de Roles <i class="zmdi zmdi-caret-down pull-right"></i>
					</a>
					<ul class="list-unstyled full-box">
						<li>
							<a href="<?php echo $URL; ?>/roles"><i class="zmdi zmdi-group zmdi-hc-fw"></i> Lista de Roles</a>
						</li>
						<li>
							<a href="<?php echo $URL; ?>/roles/crear.php"><i class="zmdi zmdi-plus zmdi-hc-fw"></i> Agregar Rol</a>
						</li>
					</ul>
				</li>

				<?php endif; ?>

				<?php if ($rol_sesion !== "tecnico"): ?>
				<li>
					<a href="#!" class="btn-sideBar-SubMenu">
						<i class="zmdi zmdi-group zmdi-hc-fw"></i> Proveedores <i class="zmdi zmdi-caret-down pull-right"></i>
					</a>
					<ul class="list-unstyled full-box">
						<li>
							<a href="<?php echo $URL; ?>/proveedor/crear.php"><i class="zmdi zmdi-group zmdi-hc-fw"></i> Agregar proveedor</a>
						</li>
						<li>
							<a href="<?php echo $URL; ?>/proveedor/"><i class="zmdi zmdi-view-list zmdi-hc-fw"></i> Lista de proveedores</a>
						</li>
					</ul>
				</li>
<?php endif; ?>

				<?php if ($rol_sesion !== "tecnico"): ?>
				<li>
					<a href="#!" class="btn-sideBar-SubMenu">
						<i class="zmdi zmdi-comment-more zmdi-hc-fw"></i> Atencion al cliente<i class="zmdi zmdi-caret-down pull-right"></i>
					</a>
					<ul class="list-unstyled full-box">
						<li>
							<a href="<?php echo $URL; ?>/atencion_cliente/pedidos/crear_pedido.php"><i class="zmdi zmdi-comment-more zmdi-hc-fw"></i> Registrar pedido</a>
						</li>
						<li>
							<a href="<?php echo $URL; ?>/tecnico/designar_tecnico.php"><i class="zmdi zmdi-comment-alt-text zmdi-hc-fw"></i> Designar tecnico</a>
						</li>
						<li>
							<a href="<?php echo $URL; ?>/atencion_cliente/historial.php"><i class="zmdi zmdi-comment-alt-text zmdi-hc-fw"></i> Historial</a>
						</li>
					</ul>
				</li>

				<?php endif; ?>

				<?php if ($rol_sesion !== "tecnico"): ?>
				<li>
					<a href="#!" class="btn-sideBar-SubMenu">
						<i class="zmdi zmdi-case-download zmdi-hc-fw"></i> Inventario <i class="zmdi zmdi-caret-down pull-right"></i>
					</a>
					<ul class="list-unstyled full-box">
						<li>
							<a href="<?php echo $URL; ?>/inventario/productos/crear.php"><i class="zmdi zmdi-case-download zmdi-hc-fw"></i> Productos</a>
						</li>
						<li>
							<a href="<?php echo $URL; ?>inventario/movimientos/entrada/"><i class="zmdi zmdi-plus zmdi-hc-fw"></i> Movimientos</a>
						</li>
					</ul>
				</li>
				<?php endif; ?>

				<?php if ($rol_sesion == "tecnico"): ?>
				<li>
					<a href="#!" class="btn-sideBar-SubMenu">
						<i class="zmdi zmdi-case-download zmdi-hc-fw"></i> Movimientos <i class="zmdi zmdi-caret-down pull-right"></i>
					</a>
					<ul class="list-unstyled full-box">
						<li>
							<a href="<?php echo $URL; ?>/inventario/tecnico/salida/"><i class="zmdi zmdi-case-download zmdi-hc-fw"></i> Registrar movimiento</a>
						</li>
						<li>
							<a href="<?php echo $URL; ?>inventario/tecnico/salida/lista.php/"><i class="zmdi zmdi-plus zmdi-hc-fw"></i> Lista de movimientos</a>
						</li>
					</ul>
				</li>
				<?php endif; ?>

				<?php if ($rol_sesion === "tecnico"): ?>
				<li>
					<a href="#!" class="btn-sideBar-SubMenu">
						<i class="zmdi zmdi-globe zmdi-hc-fw"></i> Registro de instalaciones <i class="zmdi zmdi-caret-down pull-right"></i>
					</a>
					<ul class="list-unstyled full-box">
						
						<li>
							<a href="<?php echo $URL; ?>tecnico_rol/lista_tecnicos_asignados.php"><i class="zmdi zmdi-plus zmdi-hc-fw"></i> Lista de Instalaciones</a>
						</li>
					</ul>
				</li>
				<?php endif; ?>


			</ul>
		</div>
	</section>
	<?php if ($rol_sesion === "superadministrador"): ?>
<?php endif; ?>
	<!-- Content page-->
	<section class="full-box dashboard-contentPage">
		<!-- NavBar -->
		<nav class="full-box dashboard-Navbar">
			<ul class="full-box list-unstyled text-right">
				<li class="pull-left">
					<a href="#!" class="btn-menu-dashboard"><i class="zmdi zmdi-more-vert"></i></a>
				</li>
	<!-- 			<li>
					<a href="search.html" class="btn-search">
						<i class="zmdi zmdi-search"></i>
					</a>
				</li> -->
			</ul>
		</nav>
		
		
		

	<!--====== Scripts -->
	<script src="<?php echo $URL; ?>/plant/js/jquery-3.1.1.min.js"></script>
	<script src="<?php echo $URL; ?>/plant/js/sweetalert2.min.js"></script>
	<script src="<?php echo $URL; ?>/plant/js/bootstrap.min.js"></script>
	<script src="<?php echo $URL; ?>/plant/js/material.min.js"></script>
	<script src="<?php echo $URL; ?>/plant/js/ripples.min.js"></script>
	<script src="<?php echo $URL; ?>/plant/js/jquery.mCustomScrollbar.concat.min.js"></script>
	<script src="<?php echo $URL; ?>/plant/js/main.js"></script>
	<script>
		$.material.init();
	</script>
</body>
</html>