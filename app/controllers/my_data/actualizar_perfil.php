<?php
session_start();
if (isset($_SESSION['sesion_usuario'])) {
    include ('../../config.php');

    $nombre_usuario_sesion = $_SESSION['sesion_usuario'];
    $sql = "SELECT u.ID_usuario, u.Nombre_usuario, u.ID_tipousuario, tu.Nombre_tipousuario as rol, u.id_personal
            FROM usuario as u 
            INNER JOIN tipo_usuario as tu ON u.ID_tipousuario = tu.ID_tipousuario 
            WHERE Nombre_usuario = :nombre_usuario_sesion";
    $query = $pdo->prepare($sql);
    $query->execute(['nombre_usuario_sesion' => $nombre_usuario_sesion]);
    $usuarios = $query->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($usuarios as $usuario) {
        $id_usuario_sesion = $usuario['ID_usuario'];
        $nombres_sesion = $usuario['Nombre_usuario'];
        $rol_sesion = $usuario['rol'];
        $id_personal_sesion = $usuario['id_personal'];  // Asegúrate de obtener el id_personal
    }

    if (isset($_POST['dni-up'], $_POST['nombre-up'], $_POST['apellido-up'], $_POST['telefono-up'], $_POST['cargo-up'], $_POST['direccion-up'])) {
        $dni = htmlspecialchars($_POST['dni-up']);
        $nombre = htmlspecialchars($_POST['nombre-up']);
        $apellido = htmlspecialchars($_POST['apellido-up']);
        $telefono = htmlspecialchars($_POST['telefono-up']);
        $cargo = htmlspecialchars($_POST['cargo-up']);
        $direccion = htmlspecialchars($_POST['direccion-up']);

        if (!empty($dni) && !empty($nombre) && !empty($apellido) && !empty($direccion)) {
            // Realiza la actualización en la base de datos
            $sql_update = "UPDATE personal 
                           SET Dni = :dni, Nombre = :nombre, Apellido = :apellido, Celular = :telefono, ID_cargo = :cargo, `Direccion` = :direccion
                           WHERE ID_personal = :id_personal";

            $query_update = $pdo->prepare($sql_update);
            $params = [
                'dni' => $dni,
                'nombre' => $nombre,
                'apellido' => $apellido,
                'telefono' => $telefono,
                'direccion' => $direccion,
                'cargo' => $cargo,
                'id_personal' => $id_personal_sesion // Asegúrate de que este valor sea el correcto
            ];

            try {
                $result = $query_update->execute($params);

                if ($result) {
                    echo '<div id="customConfirmModal" class="modal">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h2>Éxito</h2>
                                </div>
                                <div class="modal-body">
                                    <p>Los datos se han actualizado correctamente.</p>
                                </div>

                            </div>
                          </div>';
                    echo '<script>setTimeout(function() { window.location.href = "' . $URL . '/my_data"; }, 1000);</script>';
                } else {
                    echo '<div id="customConfirmModal" class="modal">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h2>Error</h2>
                                </div>
                                <div class="modal-body">
                                    <p>Hubo un problema al actualizar los datos. Error: ' . $query_update->errorInfo()[2] . '</p>
                                </div>
                                <div class="modal-footer">
                                    <button id="cancelDelete" onclick="closeModal()">Cerrar</button>
                                </div>
                            </div>
                          </div>';
                }
            } catch (PDOException $e) {
                echo '<div id="customConfirmModal" class="modal">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h2>Error</h2>
                            </div>
                            <div class="modal-body">
                                <p>Error de ejecución: ' . $e->getMessage() . '</p>
                            </div>
                            <div class="modal-footer">
                                <button id="cancelDelete" onclick="closeModal()">Cerrar</button>
                            </div>
                        </div>
                      </div>';
            }
        } else {
            echo '<div id="customConfirmModal" class="modal">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h2>Advertencia</h2>
                        </div>
                        <div class="modal-body">
                            <p>Por favor, complete todos los campos requeridos.</p>
                        </div>
                        <div class="modal-footer">
                            <button id="cancelDelete" onclick="closeModal()">Cerrar</button>
                        </div>
                    </div>
                  </div>';
        }
    }
} else {
    echo '<div id="customConfirmModal" class="modal">
            <div class="modal-content">
                <div class="modal-header">
                    <h2>Advertencia</h2>
                </div>
                <div class="modal-body">
                    <p>No existe sesión activa.</p>
                </div>
                <div class="modal-footer">
                    <button id="cancelDelete" onclick="closeModal()">Cerrar</button>
                </div>
            </div>
          </div>';
}
?>

<!-- Estilos CSS para la ventana modal -->
<style>
/* Estilo del fondo del modal */
.modal {
    display: block; /* Mostrar la ventana modal */
    position: fixed;
    z-index: 1; /* Asegúrate de que esté por encima del contenido */
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto; /* Hacer que la ventana modal sea desplazable si es necesario */
    background-color: rgba(0, 0, 0, 0.4); /* Fondo oscuro para la superposición */
    padding: 20px;
}

/* Estilo del contenido del modal */
#customConfirmModal .modal-content {
    background-color: white;
    margin: 0 auto;
    padding: 20px;
    border: 1px solid #888;
    width: 80%;
    max-width: 400px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    border-radius: 5px;
}

/* Estilo del título */
#customConfirmModal .modal-header h2 {
    margin: 0;
    font-size: 24px;
    color: #333;
    font-weight: bold;
}

/* Estilo de los botones */
#customConfirmModal .modal-footer button {
    padding: 10px 20px;
    font-size: 16px;
    margin: 5px;
    cursor: pointer;
    border: none;
    border-radius: 5px;
    background-color: #3498db;
    color: white;
}

#customConfirmModal .modal-footer button:hover {
    opacity: 0.8;
}

#customConfirmModal .modal-footer button:active {
    background-color: #2980b9;
}

#customConfirmModal .modal-footer #cancelDelete {
    background-color: #d33;
}

#customConfirmModal .modal-footer #cancelDelete:hover {
    background-color: #c0392b;
}

#customConfirmModal .modal-footer #cancelDelete:active {
    background-color: #e74c3c;
}
</style>

<!-- Script JavaScript para cerrar la ventana modal -->
<script>
function closeModal() {
    var modal = document.getElementById("customConfirmModal");
    modal.style.display = "none";
}
</script>

