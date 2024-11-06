<link rel="stylesheet" href="<?php echo $URL; ?>/css/editar_tipos_usuario.css">

<div class="custom-modal" id="modal-tipo-usuario-<?php echo isset($id_tipousuario) ? $id_tipousuario : ''; ?>"
     data-nombre="<?php echo isset($fila['Nombre_tipousuario']) ? htmlspecialchars($fila['Nombre_tipousuario']) : ''; ?>"
     data-estado="<?php echo isset($fila['Estado']) ? htmlspecialchars($fila['Estado']) : ''; ?>">
    <div class="custom-modal-content">
        <div class="custom-modal-header">
            <h5 class="custom-modal-title">Editar Tipo de Usuario</h5>
            <span class="close" onclick="closeModal('modal-tipo-usuario-editar-<?php echo isset($id_tipousuario) ? $id_tipousuario : ''; ?>')">&times;</span>
        </div>
        <div class="custom-modal-body">
            <form id="editarFormTipoUsuario<?php echo isset($id_tipousuario) ? $id_tipousuario : ''; ?>"
                  action="<?php echo $URL; ?>/app/controllers/roles/editar.php" method="post">
                <input type="hidden" name="id_tipousuario" value="<?php echo isset($id_tipousuario) ? $id_tipousuario : ''; ?>">
                <div class="form-group">
                    <label for="nombre_tipousuario">Nombre del tipo de usuario</label>
                    <input type="text" class="form-control" id="nombre_tipousuario" name="nombre-reg" required>
                </div>

                <div class="form-group">
                    <label for="estado_tipousuario">Estado</label>
                    <select class="form-control" id="estado_tipousuario" name="estado-reg">
                        <option value="1">Activo</option>
                        <option value="0">Inactivo</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-success">Guardar Cambios</button>
            </form>
        </div>
    </div>
</div>

<?php if (isset($_GET['error'])): ?>
<div id="errorModal" class="custom-modal">
    <div class="custom-modal-content">
        <div class="custom-modal-header">
            <h5 class="custom-modal-title">Error al Eliminar</h5>
            <span class="close" onclick="closeErrorModal('errorModal')">&times;</span>
        </div>
        <div class="custom-modal-body">
            <p><?php echo htmlspecialchars($_GET['error']); ?></p>
        </div>
        <div class="custom-modal-footer">
            <button class="btn btn-secondary" onclick="closeErrorModal('errorModal')">Cerrar</button>
        </div>
    </div>
</div>
<?php endif; ?>

