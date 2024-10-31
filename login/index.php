<!DOCTYPE html>
<html lang="es">
<head>
	<title>LogIn</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<link rel="stylesheet" href="../plant/css/main.css">
</head>
<body>
	<div class="full-box login-container cover">
		<form action="../app/controllers/login/ingreso.php" method="post" autocomplete="off" class="logInForm">
			<p class="text-center text-muted"><i class="zmdi zmdi-account-circle zmdi-hc-5x"></i></p>
			<p class="text-center text-muted text-uppercase">Inicia sesión con tu cuenta</p>
			<div class="form-group label-floating">
			  <label class="control-label" for="UserName">Usuario</label>
			  <input class="form-control" id="UserName" type="text" name="usuario">
			  <p class="help-block">Escribe tú nombre de usuario</p>
			</div>
			<div class="form-group label-floating">
			  <label class="control-label" for="UserPass">Contraseña</label>
			  <input class="form-control" id="UserPass" type="password" name="clave">
			  <p class="help-block">Escribe tú contraseña</p>
			</div>
			<div class="form-group text-center">
				<input type="submit" value="Iniciar sesión" class="btn btn-info" style="color: #FFF;">
			</div>
		</form>
	</div>
	<!--====== Scripts -->
	<script src="<?php echo $URL; ?>plant/js/jquery-3.1.1.min.js"></script>
	<script src="./plant/js/bootstrap.min.js"></script>
	<script src="./plant/js/material.min.js"></script>
	<script src="./plant/js/ripples.min.js"></script>
	<script src="./plant/js/sweetalert2.min.js"></script>
	<script src="./plant/js/jquery.mCustomScrollbar.concat.min.js"></script>
	<script src="./plant/js/main.js"></script>
	<script>
		$.material.init();
	</script>
</body>
</html>