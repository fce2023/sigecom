<link rel="stylesheet" href="<?php echo $URL; ?>/css/editar_personal.css" />
<div class="custom-modal" id="editarModalPersonal<?php echo isset($id_personal) ? $id_personal : ''; ?>"
     <?php if (isset($fila)): ?>
         data-dni-personal="<?php echo htmlspecialchars((string) $fila['Dni'], ENT_QUOTES); ?>"
         data-nombre-personal="<?php echo htmlspecialchars((string) $fila['Nombre'], ENT_QUOTES); ?>"
         data-apellido-paterno-personal="<?php echo htmlspecialchars((string) $fila['Apellido_paterno'], ENT_QUOTES); ?>"
         data-apellido-materno-personal="<?php echo htmlspecialchars((string) $fila['Apellido_materno'], ENT_QUOTES); ?>"
         data-celular-personal="<?php echo htmlspecialchars((string) $fila['Celular'], ENT_QUOTES); ?>"
         data-direccion-personal="<?php echo htmlspecialchars((string) $fila['Direccion'], ENT_QUOTES); ?>"
         data-id-cargo-personal="<?php echo htmlspecialchars((string) $fila['ID_cargo'], ENT_QUOTES); ?>"
         data-estado-personal="<?php echo htmlspecialchars((string) $fila['Estado'] == 'Activo' ? 1 : 0, ENT_QUOTES); ?>"
     <?php endif; ?>
>
    <div class="custom-modal-content">
        <div class="custom-modal-header">
            <h5 class="custom-modal-title">Edición Personal</h5>
            <span class="close" onclick="closeModal('editarModalPersonal<?php echo isset($id_personal) ? $id_personal : ''; ?>')">&times;</span>
        </div>
        <div class="custom-modal-body">
            <form id="editarFormPersonal<?php echo isset($id_personal) ? $id_personal : ''; ?>"
                  action="<?php echo $URL; ?>/app/controllers/personal/editar.php" method="post">
                <input type="hidden" name="id_personal" value="<?php echo isset($id_personal) ? $id_personal : ''; ?>">
                
                <div class="form-group">
                    <label for="dni-personal-<?php echo isset($id_personal) ? $id_personal : ''; ?>">DNI</label>
                    <input type="text" class="form-control" id="dni-personal-<?php echo isset($id_personal) ? $id_personal : ''; ?>" name="dni-reg" value="<?php echo isset($fila['Dni']) ? htmlspecialchars((string) $fila['Dni'], ENT_QUOTES) : ''; ?>" required>
                </div>

                <div class="form-group">
                    <label for="nombre-personal-<?php echo isset($id_personal) ? $id_personal : ''; ?>">Nombre Personal</label>
                    <input type="text" class="form-control" id="nombre-personal-<?php echo isset($id_personal) ? $id_personal : ''; ?>" name="nombre-reg" value="<?php echo isset($fila['Nombre']) ? htmlspecialchars((string) $fila['Nombre'], ENT_QUOTES) : ''; ?>" required>
                </div>

                <div class="form-group">
                    <label for="apellido-paterno-personal-<?php echo isset($id_personal) ? $id_personal : ''; ?>">Apellido Paterno</label>
                    <input type="text" class="form-control" id="apellido-paterno-personal-<?php echo isset($id_personal) ? $id_personal : ''; ?>" name="apellido-paterno-reg" value="<?php echo isset($fila['Apellido_paterno']) ? htmlspecialchars((string) $fila['Apellido_paterno'], ENT_QUOTES) : ''; ?>" required>
                </div>

                <div class="form-group">
                    <label for="apellido-materno-personal-<?php echo isset($id_personal) ? $id_personal : ''; ?>">Apellido Materno</label>
                    <input type="text" class="form-control" id="apellido-materno-personal-<?php echo isset($id_personal) ? $id_personal : ''; ?>" name="apellido-materno-reg" value="<?php echo isset($fila['Apellido_materno']) ? htmlspecialchars((string) $fila['Apellido_materno'], ENT_QUOTES) : ''; ?>" required>
                </div>

                <div class="form-group">
                    <label for="celular-personal-<?php echo isset($id_personal) ? $id_personal : ''; ?>">Celular</label>
                    <input type="text" class="form-control" id="celular-personal-<?php echo isset($id_personal) ? $id_personal : ''; ?>" name="celular-reg" value="<?php echo isset($fila['Celular']) ? htmlspecialchars((string) $fila['Celular'], ENT_QUOTES) : ''; ?>" required>
                </div>

                <div class="form-group">
                    <label for="direccion-personal-<?php echo isset($id_personal) ? $id_personal : ''; ?>">Direccion</label>
                    <input type="text" class="form-control" id="direccion-personal-<?php echo isset($id_personal) ? $id_personal : ''; ?>" name="direccion-reg" value="<?php echo isset($fila['Direccion']) ? htmlspecialchars((string) $fila['Direccion'], ENT_QUOTES) : ''; ?>" required>
                </div>

                <div class="form-group">
                    <label for="id-cargo-personal-<?php echo isset($id_personal) ? $id_personal : ''; ?>">Cargo</label>
                    <select class="form-control" id="id-cargo-personal-<?php echo isset($id_personal) ? $id_personal : ''; ?>" name="id-cargo-reg">
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

                <?php
                try {
                    $consulta_estado = $pdo->prepare("SELECT Estado FROM personal WHERE ID_personal = :id_personal");
                    $consulta_estado->execute(['id_personal' => $id_personal]);
                    $estado = $consulta_estado->fetchColumn();
                } catch (PDOException $e) {
                    echo "Error al conectar a la base de datos";
                }
                
                
              

                ?>
                <div class="form-group">
                    <label">Estado</label>
                    <select class="form-control" name="estado-reg">
                        <option value="<?php echo $estado; ?>" selected><?php echo $estado == 1 ? 'Activo' : 'Inactivo'; ?></option>
                        <option value="<?php echo $estado == 0 ? 1 : 0; ?>"><?php echo $estado == 0 ? 'Activo' : 'Inactivo'; ?></option>
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
            <p><?php echo isset($_GET['error']) ? htmlspecialchars((string) $_GET['error'], ENT_QUOTES) : ''; ?></p>
        </div>
        <div class="custom-modal-footer">
            <button class="btn btn-secondary" onclick="closeErrorModal('errorModal')">Cerrar</button>
        </div>
    </div>
</div>

<script>
function closeModal(modalId) {
    var modal = document.getElementById(modalId);
    modal.style.display = "none";
}

function closeErrorModal(modalId) {
    var modal = document.getElementById(modalId);
    modal.style.display = "none";
}
</script>

