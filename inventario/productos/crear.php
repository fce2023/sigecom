
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
					<h3 class="panel-title"><i class="zmdi zmdi-plus"></i> &nbsp; NUEVO PRODUCTO</h3>
				</div>
				<div class="panel-body">
					<form>
				    	<fieldset>
				    		<legend><i class="zmdi zmdi-assignment-o"></i> &nbsp; Información del producto</legend>
				    		<div class="container-fluid">
				    			<div class="row">
				    				<div class="col-xs-12">
								    	<div class="form-group label-floating">
										  	<label class="control-label">Nombre *</label>
										  	<input pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,100}" class="form-control" type="text" name="nombre-reg" required="" maxlength="100">
										</div>
				    				</div>
				    				<div class="col-xs-12">
								    	<div class="form-group label-floating">
										  	<label class="control-label">Descripción</label>
										  	<textarea class="form-control" name="descripcion-reg" rows="3"></textarea>
										</div>
				    				</div>
				    			</div>
				    			<div class="row">
				    				<div class="col-xs-12 col-sm-6">
								    	<div class="form-group label-floating">
										  	<label class="control-label">Precio *</label>
										  	<input pattern="[0-9.]{1,10}" class="form-control" type="text" name="precio-reg" required="" maxlength="10">
										</div>
				    				</div>
				    				<div class="col-xs-12 col-sm-6">
								    	<div class="form-group label-floating">
										  	<label class="control-label">Tipo de producto</label>
										  	<select class="form-control" name="tipo-reg">
										  		<option value="">Seleccione una opción</option>
										  		<?php
										  		$query = "SELECT ID_tipo_producto, Nom_producto FROM tipo_producto ORDER BY ID_tipo_producto";
										  		$stmt = $pdo->query($query);
										  		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
										  			echo "<option value='" . $row['ID_tipo_producto'] . "'>" . $row['Nom_producto'] . "</option>";
										  		}
										  		?>
										  	</select>
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

