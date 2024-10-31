<?php

include ('../app/config.php');
include ('../layout/sesion.php');

include ('../layout/parte1.php');
?>

<div class="container-fluid">
    <div class="page-header">
        <h1 class="text-titles"><i class="zmdi zmdi-account zmdi-hc-fw"></i> Lista <small>Tipo de Usuario</small></h1>
    </div>
</div>

<!-- Panel nuevo tipo de usuario -->
<div class="container-fluid">
    <div class="panel panel-danger">
        <div class="panel-heading">
            <h3 class="panel-title"><i class="zmdi zmdi-plus"></i> &nbsp; NUEVO TIPO DE USUARIO</h3>
        </div>
        <div class="panel-body">
            <form action="<?php echo $URL; ?>/app/controllers/roles/crear.php" method="post" style="background-color: #E0E0E0; padding: 15px; border-radius: 10px; box-shadow: 0px 0px 10px rgba(0,0,0,0.2);">
                <fieldset>
                    <legend style="color: #2196F3;"><i class="zmdi zmdi-account-box"></i> &nbsp; Información del Tipo de Usuario</legend>
                    <div class="container-fluid">
                        <div class="row">

                            <div class="col-xs-12">
                                <div class="form-group label-floating">
                                    <label class="control-label" style="color: #2196F3;">Nombre del Tipo de Usuario *</label>
                                    <input pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,100}" class="form-control" type="text" name="nombre-tipo-usuario" required="" maxlength="100">
                                </div>
                            </div>
                            <div class="col-xs-12">
                                <div class="form-group label-floating">
                                    <label class="control-label" style="color: #2196F3;">Estado del Tipo de Usuario</label>
                                    <select class="form-control" name="estado-tipo-usuario">
                                        <option value="Activo">Activo</option>
                                        <option value="Inactivo">Inactivo</option>
                                    </select>
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

