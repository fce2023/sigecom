<?php

include ('../app/config.php');
include ('../layout/sesion.php');

include ('../layout/parte1.php');
?>


		<div class="container-fluid">
			<div class="page-header">
			  <h1 class="text-titles"><i class="zmdi zmdi-account zmdi-hc-fw"></i> Crear<small> Cargo</small></h1>
			</div>
			<!-- <p class="lead">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Esse voluptas reiciendis tempora voluptatum eius porro ipsa quae voluptates officiis sapiente sunt dolorem, velit quos a qui nobis sed, dignissimos possimus!</p> -->
		</div>


<!-- Panel nuevo personal -->
<div class="container-fluid">
    <div class="panel panel-danger">
        <div class="panel-heading">
            <h3 class="panel-title"><i class="zmdi zmdi-plus"></i> &nbsp; NUEVO CARGO</h3>
        </div>
        <div class="panel-body">
            <form action="<?php echo $URL; ?>/app/controllers/cargo/crear.php" method="post" style="background-color: #E0E0E0; padding: 15px; border-radius: 10px; box-shadow: 0px 0px 10px rgba(0,0,0,0.2);">
                <fieldset>
                    <legend style="color: #2196F3;"><i class="zmdi zmdi-account-box"></i> &nbsp; Información del cargo</legend>
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="form-group label-floating">
                                    <label class="control-label" style="color: #2196F3;">Nombre del cargo *</label>
                                    <input pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,50}" class="form-control" type="text" name="nombre-reg" required="" maxlength="50">
                                </div>
                            </div>
                        </div>
                    </div>
                </fieldset>
                <br>
				
                <p class="text-center" style="margin-top: 20px;">
                    <button type="submit" class="btn btn-primary btn-raised btn-sm"><i class="zmdi zmdi-floppy"></i> Guardar</button>
                </p>
            </form>
        </div>
    </div>
</div>

