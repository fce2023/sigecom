<link rel="stylesheet" href="<?php echo $URL; ?>/css/editar_personal.css">

<div class="custom-modal" id="editarModalPersonal<?php echo isset($id_personal) ? $id_personal : ''; ?>"
     <?php if (isset($fila)): ?>
         data-nombre-personal="<?php echo htmlspecialchars($fila['Nombre']); ?>"
         data-apellido-personal="<?php echo htmlspecialchars($fila['Apellido']); ?>"
         data-celular-personal="<?php echo htmlspecialchars($fila['Celular']); ?>"
         data-dni-personal="<?php echo htmlspecialchars($fila['Dni']); ?>"
         data-estado-personal="<?php echo htmlspecialchars($fila['Estado']); ?>"
         data-cargo-personal="<?php echo htmlspecialchars($fila['ID_cargo']); ?>"
     <?php endif; ?>
>
    <div class="custom-modal-content">
        <div class="custom-modal-header">
            <h5 class="custom-modal-title">Editar Personal</h5>
            <span class="close" onclick="closeModal('editarModalPersonal<?php echo isset($id_personal) ? $id_personal : ''; ?>')">&times;</span>
        </div>
        <div class="custom-modal-body">
            <form id="editarFormPersonal<?php echo isset($id_personal) ? $id_personal : ''; ?>"
                  action="<?php echo $URL; ?>/app/controllers/personal/editar.php" method="post">
                <input type="hidden" name="id_personal" value="<?php echo isset($id_personal) ? $id_personal : ''; ?>">

                <div class="form-group">
                    <label for="nombre-personal-<?php echo isset($id_personal) ? $id_personal : ''; ?>">Nombre</label>
                    <input type="text" class="form-control" id="nombre-personal-<?php echo isset($id_personal) ? $id_personal : ''; ?>" name="nombre-reg" value="<?php echo isset($fila['Nombre']) ? htmlspecialchars($fila['Nombre']) : ''; ?>" required>
                </div>

                <div class="form-group">
                    <label for="apellido-personal-<?php echo isset($id_personal) ? $id_personal : ''; ?>">Apellido</label>
                    <input type="text" class="form-control" id="apellido-personal-<?php echo isset($id_personal) ? $id_personal : ''; ?>" name="apellido-reg" value="<?php echo isset($fila['Apellido']) ? htmlspecialchars($fila['Apellido']) : ''; ?>" required>
                </div>

                <div class="form-group">
                    <label for="celular-personal-<?php echo isset($id_personal) ? $id_personal : ''; ?>">Celular</label>
                    <input type="text" class="form-control" id="celular-personal-<?php echo isset($id_personal) ? $id_personal : ''; ?>" name="celular-reg" value="<?php echo isset($fila['Celular']) ? htmlspecialchars($fila['Celular']) : ''; ?>" required>
                </div>

                <div class="form-group">
                    <label for="dni-personal-<?php echo isset($id_personal) ? $id_personal : ''; ?>">DNI</label>
                    <input type="text" class="form-control" id="dni-personal-<?php echo isset($id_personal) ? $id_personal : ''; ?>" name="dni-reg" value="<?php echo isset($fila['Dni']) ? htmlspecialchars($fila['Dni']) : ''; ?>" required>
                </div>

                <div class="form-group">
                    <label for="estado-personal-<?php echo isset($id_personal) ? $id_personal : ''; ?>">Estado</label>
                    <select class="form-control" id="estado-personal-<?php echo isset($id_personal) ? $id_personal : ''; ?>" name="estado-reg">
                        <option value="1" <?php echo (isset($fila['Estado']) && $fila['Estado'] == 1) ? 'selected' : ''; ?>>Activo</option>
                        <option value="0" <?php echo (isset($fila['Estado']) && $fila['Estado'] == 0) ? 'selected' : ''; ?>>Inactivo</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="cargo-personal-<?php echo isset($id_personal) ? $id_personal : ''; ?>">Cargo</label>
                    <select class="form-control" id="cargo-personal-<?php echo isset($id_personal) ? $id_personal : ''; ?>" name="cargo-reg">
                        <?php
                        try {
                            $consulta = $pdo->query("SELECT * FROM cargo");
                            $consulta->execute();
                            $filas = $consulta->fetchAll();
                        } catch (PDOException $e) {
                            echo "Error al conectar a la base de datos";
                        }
                        ?>
                        <?php
                        foreach ($filas as $fila2) {
                            echo '<option value="'.$fila2['ID_cargo'].'" '.(isset($fila['ID_cargo']) && $fila['ID_cargo'] == $fila2['ID_cargo'] ? 'selected' : '').'>'.$fila2['Nom_cargo'].'</option>';
                        }
                        ?>
                    </select>
                </div>

                <button type="submit" class="btn btn-success">Guardar Cambios</button>
            </form>
        </div>
    </div>
</div>

<div id="errorModal" class="custom-modal">
    <div class="custom-modal-content">
        <div class="custom-modal-header">
            <h5 class="custom-modal-title">Error al Eliminar</h5>
            <span class="close" onclick="closeErrorModal('errorModal')">&times;</span>
        </div>
        <div class="custom-modal-body">
            <p><?php echo isset($_GET['error']) ? htmlspecialchars($_GET['error']) : ''; ?></p>
        </div>
        <div class="custom-modal-footer">
            <button class="btn btn-secondary" onclick="closeErrorModal('errorModal')">Cerrar</button>
        </div>
    </div>
</div>

