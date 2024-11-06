<link rel="stylesheet" href="<?php echo $URL; ?>/css/editar_usuarios.css">

<div class="custom-modal" id="editarModalUsuario<?php echo isset($id_usuario) ? $id_usuario : ''; ?>"
     <?php if (isset($fila)): ?>
         data-nombre-usuario="<?php echo htmlspecialchars($fila['Nombre_usuario']); ?>"
         data-id_tipousuario="<?php echo htmlspecialchars($fila['ID_tipousuario']); ?>"
         data-estado-usuario="<?php echo htmlspecialchars($fila['Estado'] ?? ''); ?>"
     <?php endif; ?>
>
    <div class="custom-modal-content">
        <div class="custom-modal-header">
            <h5 class="custom-modal-title">Editar Usuario</h5>
            <span class="close" onclick="closeModal('editarModalUsuario<?php echo isset($id_usuario) ? $id_usuario : ''; ?>')">&times;</span>
        </div>
        <div class="custom-modal-body">
            <form id="editarFormUsuario<?php echo isset($id_usuario) ? $id_usuario : ''; ?>"
                  action="<?php echo $URL; ?>/app/controllers/usuario/editar.php" method="post">
                <input type="hidden" name="id_usuario" value="<?php echo isset($id_usuario) ? $id_usuario : ''; ?>">

                <div class="form-group">
                    <label for="nombre-usuario-<?php echo isset($id_usuario) ? $id_usuario : ''; ?>">Nombre de usuario</label>
                    <input type="text" class="form-control" id="nombre-usuario-<?php echo isset($id_usuario) ? $id_usuario : ''; ?>" name="nombre-reg" value="<?php echo isset($fila['Nombre_usuario']) ? htmlspecialchars($fila['Nombre_usuario']) : ''; ?>" required>
                </div>

                <div class="form-group">
                    <label for="id_tipousuario-<?php echo isset($id_usuario) ? $id_usuario : ''; ?>">Tipo de usuario</label>
                    <select class="form-control" id="id_tipousuario-<?php echo isset($id_usuario) ? $id_usuario : ''; ?>" name="id_tipousuario-reg">
                        <?php
                        $query = "SELECT ID_tipousuario, Nombre_tipousuario FROM tipo_usuario WHERE Estado = '1'";
                        $tipos_usuarios = $pdo->query($query);
                        foreach ($tipos_usuarios as $tipo_usuario) {
                            $selected = (isset($fila['ID_tipousuario']) && $fila['ID_tipousuario'] == $tipo_usuario['ID_tipousuario']) ? 'selected' : '';
                            echo "<option value=\"{$tipo_usuario['ID_tipousuario']}\" $selected>{$tipo_usuario['Nombre_tipousuario']}</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="estado-usuario-<?php echo isset($id_usuario) ? $id_usuario : ''; ?>">Estado</label>
                    <select class="form-control" id="estado-usuario-<?php echo isset($id_usuario) ? $id_usuario : ''; ?>" name="estado-reg">
                        <option value="1" <?php echo (isset($fila['Estado']) && $fila['Estado'] == 1) ? 'selected' : ''; ?>>Activo</option>
                        <option value="0" <?php echo (isset($fila['Estado']) && $fila['Estado'] == 0) ? 'selected' : ''; ?>>Inactivo</option>
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
