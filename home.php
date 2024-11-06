<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <?php
    include "app/config.php";
    include "layout/sesion.php";
    include "layout/parte1.php";
    ?>
</head>
<body>
    <!-- Content page -->
		<div class="container-fluid">
			<div class="page-header">
			  <h1 class="text-titles">S <small>SH</small></h1>
			</div>
		</div>
		<div class="full-box text-center" style="padding: 30px 10px;">
			<article class="full-box tile">
				<div class="full-box tile-title text-center text-titles text-uppercase">
					Usuarios
				</div>
				<div class="full-box tile-icon text-center">
					<i class="zmdi zmdi-account"></i>
				</div>
				<?php
				$consulta = "SELECT COUNT(*) AS cantidad FROM usuario";
				$resultado = $pdo->query($consulta);
				$fila = $resultado->fetch(PDO::FETCH_ASSOC);
				?>
				<div class="full-box tile-number text-titles">
					<p class="full-box"><?php echo $fila['cantidad']; ?></p>
					<small>Register</small>
				</div>
			</article>
			<article class="full-box tile">
				<div class="full-box tile-title text-center text-titles text-uppercase">
					Clientes
				</div>
				<div class="full-box tile-icon text-center">
					<i class="zmdi zmdi-male-alt"></i>
				</div>
				<div class="full-box tile-number text-titles">
					<p class="full-box">10</p>
					<small>Register</small>
				</div>
			</article>
			<article class="full-box tile">
				<div class="full-box tile-title text-center text-titles text-uppercase">
					Proveedores
				</div>
				<div class="full-box tile-icon text-center">
					<i class="zmdi zmdi-face"></i>
				</div>
				<div class="full-box tile-number text-titles">
					<p class="full-box">70</p>
					<small>Register</small>
				</div>
			</article>
		</div>
</body>

<footer>
<?php
include "layout/parte2.php";
?>

</footer>
</html>


