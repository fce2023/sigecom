<?php

include ('../app/config.php');
include ('../layout/sesion.php');

include ('../layout/parte1.php');
?>

<div class="container-fluid">
    <div class="page-header">
        <h1 class="text-titles"><i class="zmdi zmdi-account zmdi-hc-fw"></i> Registrar <small>Usuario</small></h1>
    </div>
</div>

<!-- Panel nuevo usuario -->
<div class="container-fluid">
    <div class="panel panel-danger">
        <div class="panel-heading">
            <h3 class="panel-title"><i class="zmdi zmdi-plus"></i> &nbsp; NUEVO USUARIO</h3>
        </div>
        <div class="panel-body">
            <form action="<?php echo $URL; ?>/app/controllers/usuario/crear.php" method="post" style="background-color: #E0E0E0; padding: 15px; border-radius: 10px; box-shadow: 0px 0px 10px rgba(0,0,0,0.2);">
                <fieldset>
                    <legend style="color: #2196F3;"><i class="zmdi zmdi-account-box"></i> &nbsp; Información del Usuario</legend>
                    <div class="container-fluid">
                        <div class="row">

                        


                        <div class="form-group label-floating">
                            <div class="form-group label-floating">
                                <label class="control-label" style="color: #2196F3;">Personal</label>
                                <select class="form-control" name="id-personal">
                                    <option value="" disabled selected>Seleccione un personal</option>
                                    <?php
                                        try {


                                            // Consulta preparada para obtener datos del personal activo (Estado = 1)
                                            $consulta = $pdo->prepare("SELECT ID_personal, Nombre, Apellido, Dni FROM personal WHERE Estado = 1");
                                            $consulta->execute();
                                            
                                            // Obtener resultados
                                            $resultado = $consulta->fetchAll(PDO::FETCH_ASSOC);
                                            
                                            // Verificar y mostrar resultados
                                            if (!$resultado) {
                                                echo "<option>No se encontraron datos</option>";
                                            } else {
                                                foreach ($resultado as $fila) {
                                                    echo "<option value='" . $fila['ID_personal'] . "'>" . $fila['Nombre'] . " " . $fila['Apellido'] . " - " . $fila['Dni'] . "</option>";
                                                }
                                            }

                                        } catch (PDOException $e) {
                                            echo "<option>Error al conectar a la base de datos: " . htmlspecialchars($e->getMessage()) . "</option>";
                                        }
                                    ?>
                                </select>
                            </div>
                            
                            <p>Si no encuentra al personal, puede crearlo en <a href="../personal/crear.php">Personal</a>.</p>

                            <div class="col-xs-12">
                                <div class="form-group label-floating">
                                    <label class="control-label" style="color: #2196F3;">Nombre del Usuario *</label>
                                    <input class="form-control" type="text" name="nombre-usuario" required="" maxlength="50">
                                </div>
                            </div>
                            <div class="col-xs-12">
                                <div class="form-group label-floating">
                                    <label class="control-label" style="color: #2196F3;">Contraseña *</label>
                                    <input class="form-control" type="password" name="contraseña" required="" maxlength="100">
                                </div>
                            </div>

                            <div class="col-xs-12">
                                <div class="form-group label-floating">
                                    <label class="control-label" style="color: #2196F3;">Tipo de Usuario</label>
                                    <select class="form-control" name="id-tipousuario">
                                        <?php
                                            // Use the PDO connection defined in config.php
                                            $consulta = $pdo->prepare("SELECT ID_tipousuario, Nombre_tipousuario FROM tipo_usuario");
                                            $consulta->execute();
                                            $resultado = $consulta->fetchAll();
                                            foreach ($resultado as $fila) {
                                        ?>
                                            <option value="<?php echo $fila['ID_tipousuario']; ?>"><?php echo $fila['Nombre_tipousuario']; ?></option>
                                        <?php
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>


                            <div class="col-xs-12">
                                <div class="form-group label-floating">
                                    <label class="control-label" style="color: #2196F3;">Estado del Usuario</label>
                                    <select class="form-control" name="estado-usuario">
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

