
<?php

include ('../../../app/config.php');
include ('../../../layout/sesion.php');
include ('../../../layout/parte1.php');
?>
		<!-- Panel nueva categoria -->
		<div class="container-fluid">
<?php include ('../layout/parte1.php');?>
			<div class="panel panel-info">
				<div class="panel-heading">
					<h3 class="panel-title"><i class="zmdi zmdi-plus"></i> &nbsp; ABASTECIMIENTO DE PRODUCTOS</h3>
				</div>
				<div class="panel-body">
                
					<form>
				    	<fieldset>
				    		<legend><i class="zmdi zmdi-assignment-o"></i> &nbsp; ENTRADA DE PRODUCTOS - Información del prodcuto y proveedor</legend>
				    		<div class="container-fluid">
				    			<div class="row">
				    				<div class="col-xs-12">
								    	<div class="form-group label-floating">
										  	<label class="control-label">Nombre del proveedor *</label>
										  	<select class="form-control" name="proveedor-reg" required="">
										  		<option value="">Seleccione una opción</option>
										  		<?php
										  		$query = "SELECT ID_proveedor, Nombre FROM proveedor ORDER BY ID_proveedor";
										  		$stmt = $pdo->query($query);
										  		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
										  			echo "<option value='" . $row['ID_proveedor'] . "'>" . $row['Nombre'] . "</option>";
										  		}
										  		?>
										  	</select>
										</div>
				    				</div>
				    				<div class="col-xs-12">
								    	<div class="form-group label-floating">
										  	<label class="control-label">Nombre del producto *</label>
										  	<select class="form-control" name="producto-reg" required="">
										  		<option value="">Seleccione una opción</option>
										  		<?php
										  		$query = "SELECT id_producto, nombre FROM productos ORDER BY id_producto";
										  		$stmt = $pdo->query($query);
										  		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
										  			echo "<option value='" . $row['id_producto'] . "'>" . $row['nombre'] . "</option>";
										  		}
										  		?>
										  	</select>
										</div>
				    				</div>
				    			</div>
                                <div class="col-xs-12 col-sm-6">
                                    <div class="form-group label-floating">
                                        <label class="control-label">Cantidad *</label>
                                        <input pattern="[0-9]+" class="form-control" type="number" name="cantidad-reg" required="" min="1">
                                    </div>
                                </div>
				    			<div class="row">
				    				<div class="col-xs-12 col-sm-6">
								    	<div class="form-group label-floating">
										  	<label class="control-label">Fecha de abastecimiento *</label>
										  	<input pattern="[0-9]{4}-[0-9]{2}-[0-9]{2}" class="form-control" type="text" name="fecha-reg" value="<?php echo date('Y-m-d'); ?>" required="" maxlength="10" readonly>
										</div>
				    				</div>
				    				<div class="col-xs-12 col-sm-6">
								    	<div class="form-group label-floating">
										  	<label class="control-label">Observación</label>
										  	<textarea class="form-control" name="observacion-reg" rows="3"><?php echo isset($observacion) ? $observacion : 'Ninguna observación'; ?></textarea>
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

