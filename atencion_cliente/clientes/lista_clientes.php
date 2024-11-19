<?php

include ('../../app/config.php');
include ('../../layout/sesion.php');
include ('../../layout/parte1.php');
?>


<!-- Panel listado de clientes -->
<div class="container-fluid">
<h1><i class="zmdi zmdi-male-alt zmdi-hc-fw"></i> Atención al Clientes</h1>
<?php


include ('../../layout/cliente.php');
?>

    <div class="panel panel-primary">
        <div class="panel-heading" style="background-color: #2ecc71; color: white;">
            <h3 class="panel-title"><i class="zmdi zmdi-format-list-bulleted"></i> &nbsp; LISTA DE CLIENTES</h3>
        </div>
        <div class="panel-body" style="background-color: #F5F5F5;">
            <div class="table-responsive" style="background-color: #f0f0f0;">
                <table class="table table-hover text-center">
                    <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th class="text-center">DNI</th>
                            <th class="text-center">NOMBRE</th>
                            <th class="text-center">APELLIDO</th>
                            <th class="text-center">DIRECCIÓN</th>
                            <th class="text-center">CELULAR</th>
                            <th class="text-center">CORREO ELECTRÓNICO</th>
                            <th class="text-center">ESTADO</th>
                            <th class="text-center">ACTUALIZAR</th>
                            <th class="text-center">ELIMINAR</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $limit = 3;
                            $page = (isset($_GET['page'])) ? $_GET['page'] : 1;
                            $offset = ($page - 1) * $limit;
                            $query = "SELECT * FROM cliente ORDER BY ID_cliente DESC LIMIT :limit OFFSET :offset";
                            $stmt = $pdo->prepare($query);
                            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
                            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
                            $stmt->execute();
                            $contador = $offset; // Start the counter from the offset
                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                                <tr>
                                    <td><?php echo ++$contador; ?></td>
                                    <td><?php echo htmlspecialchars($row['Dni']); ?></td>
                                    <td><?php echo htmlspecialchars($row['Nombre']); ?></td>
                                    <td><?php echo htmlspecialchars($row['Apellido_paterno'] . ' ' . $row['Apellido_materno']); ?></td>
                                    <td><?php echo htmlspecialchars($row['Dirección']); ?></td>
                                    <td><?php echo htmlspecialchars($row['Celular']); ?></td>
                                    <td><?php echo htmlspecialchars($row['Correo_Electronico']); ?></td>
                                    <td><?php echo htmlspecialchars($row['Estado'] == 1 ? 'Activo' : 'Inactivo'); ?></td>
                                    <td>
                                        <button type="button" class="btn btn-primary btn-raised btn-xs" onclick="window.location.href = 'editar_cliente.php?ID_cliente=<?php echo $row['ID_cliente']; ?>'">
                                            <i class="zmdi zmdi-edit"></i>
                                        </button>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-danger btn-raised btn-xs" onclick="deleteClienteUnique(<?php echo htmlspecialchars($row['ID_cliente']); ?>);">
                                            <i class="zmdi zmdi-delete"></i>
                                        </button>
                                        <script>
                                            function deleteClienteUnique(ID_cliente) {
                                                showConfirmModalUnique('¿Está seguro de que desea eliminar este cliente?', function() {
                                                    var xhr = new XMLHttpRequest();
                                                    xhr.open('POST', '<?php echo $URL; ?>/app/controllers/atencion_cliente/clientes/eliminar_cliente.php', true);
                                                    xhr.setRequestHeader('Content-Type', 'application/json');
                                                    xhr.onload = function() {
                                                        try {
                                                            if (xhr.status === 200) {
                                                                var data = JSON.parse(xhr.responseText);
                                                                if (data.success) {
                                                                    showModalUnique('<span style="color:green;">' + data.message + '</span>', 'success');
                                                                    setTimeout(function() {
                                                                        window.location.reload(true);
                                                                    }, 1000);
                                                                } else {
                                                                    showModalUnique('<span style="color:red;">' + (data.error || 'No se pudo eliminar el cliente.') + '</span>', 'danger');
                                                                }
                                                            } else {
                                                                showModalUnique('<span style="color:red;">Error en la conexión al servidor. Código de estado: ' + xhr.status + '</span>', 'danger');
                                                            }
                                                        } catch (error) {
                                                            showModalUnique('<span style="color:red;">Error al procesar la respuesta del servidor: ' + error.message + '</span>', 'danger');
                                                        }
                                                    };
                                                    xhr.onerror = function() {
                                                        showModalUnique('<span style="color:red;">Error al enviar la solicitud. Verifique su conexión.</span>', 'danger');
                                                    };
                                                    xhr.send(JSON.stringify({ ID_cliente: ID_cliente }));
                                                });
                                            }
                                            function showConfirmModalUnique(message, onConfirm) {
                                                var modalContent = `
                                                    <div class="modal fade" id="confirmModalUnique" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabelUnique" aria-hidden="true">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="confirmModalLabelUnique">Confirmar Eliminación</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    ${message}
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                                                                    <button type="button" class="btn btn-primary" id="confirmBtnUnique">Sí</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                `;
                                                document.body.insertAdjacentHTML('beforeend', modalContent);
                                                $('#confirmModalUnique').modal('show');
                                                document.getElementById('confirmBtnUnique').addEventListener('click', function() {
                                                    onConfirm();
                                                    $('#confirmModalUnique').modal('hide');
                                                });
                                            }
                                            function showModalUnique(message, type) {
                                                var modalContent = `
                                                    <div class="modal fade" id="mensajeModalUnique" tabindex="-1" role="dialog" aria-labelledby="mensajeModalLabelUnique" aria-hidden="true">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="mensajeModalLabelUnique">${type === 'success' ? '<span style="color:green;">Éxito</span>' : '<span style="color:red;">Error</span>'}</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    ${message}
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                `;
                                                document.body.insertAdjacentHTML('beforeend', modalContent);
                                                $('#mensajeModalUnique').modal('show');
                                            }
                                        </script>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<nav class="text-center">
    <ul class="pagination pagination-sm">
        <li class="<?php echo ($page <= 1) ? 'disabled' : ''; ?>">
            <a href="?page=<?php echo ($page > 1) ? $page - 1 : 1; ?>">«</a>
        </li>
        <?php
            $query = "SELECT COUNT(*) as total FROM cliente";
            $stmt = $pdo->query($query);
            $total = $stmt->fetchColumn();
            $pages = ceil($total / $limit);
            for ($i = 1; $i <= $pages; $i++) {
                $active = ($page == $i) ? 'active' : '';
                echo '<li class="' . $active . '"><a href="?page=' . $i . '">' . $i . '</a></li>';
            }
        ?>
        <li class="<?php echo ($page >= $pages) ? 'disabled' : ''; ?>">
            <a href="?page=<?php echo ($page < $pages) ? $page + 1 : $pages; ?>">»</a>
        </li>
    </ul>
</nav>

