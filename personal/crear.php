
<?php

include ('../app/config.php');
include ('../layout/sesion.php');

include ('../layout/parte1.php');
?>


		<div class="container-fluid">
			<div class="page-header">
			  <h1 class="text-titles"><i class="zmdi zmdi-account zmdi-hc-fw"></i> Registrar <small>Personal</small></h1>
			</div>
			<!-- <p class="lead">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Esse voluptas reiciendis tempora voluptatum eius porro ipsa quae voluptates officiis sapiente sunt dolorem, velit quos a qui nobis sed, dignissimos possimus!</p> -->
		</div>


<!-- Panel nuevo personal -->
<div class="container-fluid">
    <div class="panel panel-danger">
        <div class="panel-heading">
            <h3 class="panel-title"><i class="zmdi zmdi-plus"></i> &nbsp; NUEVO PERSONAL</h3>
        </div>
        <div class="panel-body">
            <form action="<?php echo $URL; ?>/app/controllers/personal/crear.php" method="post" autocomplete="off" style="background-color: #E0E0E0; padding: 15px; border-radius: 10px; box-shadow: 0px 0px 10px rgba(0,0,0,0.2);">
                <fieldset>
                    <legend style="color: #2196F3;"><i class="zmdi zmdi-account-box"></i> &nbsp; Información personal</legend>
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="form-group label-floating">
                                    <label class="control-label" style="color: #2196F3;">DNI/CEDULA *</label>
                                    <input pattern="[0-9-]{1,10}" class="form-control" type="text" name="dni-reg" required="" maxlength="10" autocomplete="off" readonly onfocus="this.removeAttribute('readonly');">
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6">
                                <div class="form-group label-floating">
                                    <label class="control-label" style="color: #2196F3;">Nombres *</label>
                                    <input pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,100}" class="form-control" type="text" name="nombre-reg" required="" maxlength="100" autocomplete="off" readonly onfocus="this.removeAttribute('readonly');">
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6">
                                <div class="form-group label-floating">
                                    <label class="control-label" style="color: #2196F3;">Apellido Paterno *</label>
                                    <input class="form-control" type="text" name="apellido-paterno-reg" required="" autocomplete="off" readonly onfocus="this.removeAttribute('readonly');">
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6">
                                <div class="form-group label-floating">
                                    <label class="control-label" style="color: #2196F3;">Apellido Materno *</label>
                                    <input class="form-control" type="text" name="apellido_materno-reg" required="" autocomplete="off" readonly onfocus="this.removeAttribute('readonly');">
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6">
                                <div class="form-group label-floating">
                                    <label class="control-label" style="color: #2196F3;">Celular</label>
                                    <input pattern="[0-9+]{1,11}" class="form-control" type="text" name="celular-reg" maxlength="11" autocomplete="off" readonly onfocus="this.removeAttribute('readonly');">
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6">
                                <div class="form-group label-floating">
                                    <label class="control-label" style="color: #2196F3;">Dirección</label>
                                    <input type="text" class="form-control" name="direccion-reg" maxlength="250" autocomplete="off" readonly onfocus="this.removeAttribute('readonly');">
                                </div>
                            </div>
                            <div class="col-xs-12">
                                <div class="form-group label-floating">
                                    <label class="control-label" style="color: #2196F3;">Cargo</label>
                                    <?php
                                    try {
                                        
                                        $consulta = $pdo->query("SELECT * FROM cargo");
                                        $consulta->execute();
                                        $filas = $consulta->fetchAll();
                                    } catch (PDOException $e) {
                                        echo "Error al conectar a la base de datos";
                                    }
                                    ?>
                                    <select class="form-control" name="cargo-reg">
                                        <?php
                                        foreach ($filas as $fila) {
                                            echo '<option value="'.$fila['ID_cargo'].'">'.$fila['Nom_cargo'].'</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                    <p>Si no hay cargo en la lista, ve y <a href="<?php echo $URL; ?>/cargo/crear.php">agrega uno en la sección de Administrar Cargo</a></p>
                </fieldset>
                <br>
				
                <p class="text-center" style="margin-top: 20px;">
                    <button type="submit" class="btn btn-primary btn-raised btn-sm"><i class="zmdi zmdi-floppy"></i> Guardar</button>
                </p>
            </form>
        </div>
    </div>
</div>


