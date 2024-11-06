<link rel="stylesheet" href="<?php echo $URL; ?>/css/editar_cargo.css">

<div class="custom-modal" id="editarModal<?php echo isset($id_cargo) ? $id_cargo : ''; ?>"
     <?php if (isset($fila)): ?>
         data-nombre-cargo="<?php echo htmlspecialchars($fila['Nom_cargo']); ?>" 
         data-estado="<?php echo htmlspecialchars($fila['Estado']); ?>"
     <?php endif; ?>
>
    <div class="custom-modal-content">
        <div class="custom-modal-header">
            <h5 class="custom-modal-title">Editar Cargo</h5>
            <span class="close" onclick="closeModal('editarModal<?php echo isset($id_cargo) ? $id_cargo : ''; ?>')">&times;</span>
        </div>
        <div class="custom-modal-body">
            <form id="editarForm<?php echo isset($id_cargo) ? $id_cargo : ''; ?>"
                  action="<?php echo $URL; ?>/app/controllers/cargo/editar.php" method="post">
                <input type="hidden" name="id_cargo" value="<?php echo isset($id_cargo) ? $id_cargo : ''; ?>">

                <div class="form-group">
                    <label for="nombre-cargo-<?php echo isset($id_cargo) ? $id_cargo : ''; ?>">Nombre del Cargo</label>
                    <input type="text" class="form-control" id="nombre-cargo-<?php echo isset($id_cargo) ? $id_cargo : ''; ?>" name="nombre-reg" required>
                </div>

                <div class="form-group">
                    <label for="estado-cargo-<?php echo isset($id_cargo) ? $id_cargo : ''; ?>">Estado del Cargo</label>
                    <select class="form-control" id="estado-cargo-<?php echo isset($id_cargo) ? $id_cargo : ''; ?>" name="estado-reg">
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
