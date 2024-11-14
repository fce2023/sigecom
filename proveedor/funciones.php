<link rel="stylesheet" href="<?php echo $URL; ?>/css/editar_proveedores.css ?>">
<div class="custom-modal" id="editarModalProveedor<?php echo isset($id_proveedor) ? $id_proveedor : ''; ?>"
     <?php if (isset($fila)): ?>
         data-nombre-proveedor="<?php echo htmlspecialchars($fila['Nombre']); ?>"
         data-direccion-proveedor="<?php echo htmlspecialchars($fila['Dirección']); ?>"
         data-telefono-proveedor="<?php echo htmlspecialchars($fila['Teléfono']); ?>"
         data-estado-proveedor="<?php echo htmlspecialchars($fila['Estado'] ?? ''); ?>"
     <?php endif; ?>
>
    <div class="custom-modal-content">
        <div class="custom-modal-header">
            <h5 class="custom-modal-title">Editar Proveedor</h5>
            <span class="close" onclick="closeModal('editarModalProveedor<?php echo isset($id_proveedor) ? $id_proveedor : ''; ?>')">&times;</span>
        </div>
        <div class="custom-modal-body">
            <form id="editarFormProveedor<?php echo isset($id_proveedor) ? $id_proveedor : ''; ?>"
                  action="<?php echo $URL; ?>/app/controllers/proveedor/editar.php" method="post">
                <input type="hidden" name="id_proveedor" value="<?php echo isset($id_proveedor) ? $id_proveedor : ''; ?>">

                <div class="form-group">
                    <label for="nombre-proveedor-<?php echo isset($id_proveedor) ? $id_proveedor : ''; ?>">Nombre del proveedor</label>
                    <input type="text" class="form-control" id="nombre-proveedor-<?php echo isset($id_proveedor) ? $id_proveedor : ''; ?>" name="nombre-reg" value="<?php echo isset($fila['Nombre']) ? htmlspecialchars($fila['Nombre']) : ''; ?>" required>
                </div>

                <div class="form-group">
                    <label for="direccion-proveedor-<?php echo isset($id_proveedor) ? $id_proveedor : ''; ?>">Dirección del proveedor</label>
                    <input type="text" class="form-control" id="direccion-proveedor-<?php echo isset($id_proveedor) ? $id_proveedor : ''; ?>" name="direccion-reg" value="<?php echo isset($fila['Dirección']) ? htmlspecialchars($fila['Dirección']) : ''; ?>" required>
                </div>

                <div class="form-group">
                    <label for="telefono-proveedor-<?php echo isset($id_proveedor) ? $id_proveedor : ''; ?>">Teléfono del proveedor</label>
                    <input type="text" class="form-control" id="telefono-proveedor-<?php echo isset($id_proveedor) ? $id_proveedor : ''; ?>" name="telefono-reg" value="<?php echo isset($fila['Teléfono']) ? htmlspecialchars($fila['Teléfono']) : ''; ?>" required>
                </div>

                <div class="form-group">
                    <label for="estado-proveedor-<?php echo isset($id_proveedor) ? $id_proveedor : ''; ?>">Estado</label>
                    <select class="form-control" id="estado-proveedor-<?php echo isset($id_proveedor) ? $id_proveedor : ''; ?>" name="estado-reg">
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

