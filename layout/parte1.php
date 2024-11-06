
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
						<a href="#!" title="Cerrar Sesión" class="btn-exit-system">
							<i class="zmdi zmdi-power"></i>
						</a>
					</li>
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

			</ul>
		</div>
	</section>

<?php endif; ?>
	<!-- Content page-->
	<section class="full-box dashboard-contentPage">
		<!-- NavBar -->
		<nav class="full-box dashboard-Navbar">
			<ul class="full-box list-unstyled text-right">
				<li class="pull-left">
					<a href="#!" class="btn-menu-dashboard"><i class="zmdi zmdi-more-vert"></i></a>
				</li>
				<li>
					<a href="search.html" class="btn-search">
						<i class="zmdi zmdi-search"></i>
					</a>
				</li>
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