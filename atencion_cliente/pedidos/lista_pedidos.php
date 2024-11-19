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
        <div class="panel-heading">
            <h3 class="panel-title"><i class="zmdi zmdi-format-list-bulleted"></i> &nbsp; LISTA DE PEDIDOS</h3>
        </div>
        <div class="panel-body" style="background-color: #F5F5F5;">
            <div class="table-responsive" style="background-color: #f0f0f0;">
                <table class="table table-hover text-center">
                    <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th class="text-center">USUARIO</th>
                            <th class="text-center">TECNICO</th>
                            <th class="text-center">CLIENTE</th>
                            <th class="text-center">SERVICIO</th>
                            <th class="text-center">CODIGO_OPERACION</th>
                            <th class="text-center">FECHA_CREACION</th>
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
                            $query = "SELECT * FROM atencion_cliente ORDER BY ID DESC LIMIT :limit OFFSET :offset";
                            $stmt = $pdo->prepare($query);
                            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
                            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
                            $stmt->execute();
                            $contador = $offset;
                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                                <tr>
                                    <td><?php echo ++$contador; ?></td>
                                    <?php
                                    $stmt2 = $pdo->prepare("SELECT u.ID_usuario, u.id_personal, u.Nombre_usuario, p.Dni, p.Nombre, p.Apellido_paterno, p.Apellido_materno 
                                    FROM usuario u 
                                    INNER JOIN personal p ON u.id_personal = p.ID_personal 
                                    WHERE u.ID_usuario = :id");
                                    $stmt2->bindParam(':id', $row['ID_usuario'], PDO::PARAM_INT);
                                    $stmt2->execute();
                                    $user = $stmt2->fetch(PDO::FETCH_ASSOC);
                                    ?>
                                    <td><?php echo htmlspecialchars("{$user['Nombre_usuario']} - {$user['Dni']} {$user['Nombre']} {$user['Apellido_paterno']} {$user['Apellido_materno']}"); ?></td>
                                    <?php
                                    if (is_null($row['ID_detalle_cliente_tecnico'])) {
                                        echo '<td>No designado</td>';
                                    } else {
                                        $stmt3 = $pdo->prepare("SELECT t.id_personal FROM detalle_cliente_tecnico d INNER JOIN tecnico t ON d.ID_tecnico = t.ID_tecnico WHERE Id_det_cliente_tecnico = :id");
                                        $stmt3->bindParam(':id', $row['ID_detalle_cliente_tecnico'], PDO::PARAM_INT);
                                        $stmt3->execute();
                                        $detalle = $stmt3->fetch(PDO::FETCH_ASSOC);
                                        $stmt4 = $pdo->prepare("SELECT Dni, Nombre FROM personal WHERE ID_personal = :id");
                                        $stmt4->bindParam(':id', $detalle['id_personal'], PDO::PARAM_INT);
                                        $stmt4->execute();
                                        $tecnico = $stmt4->fetch(PDO::FETCH_ASSOC);
                                        echo '<td>' . htmlspecialchars("{$detalle['id_personal']} - {$tecnico['Dni']} {$tecnico['Nombre']}") . '</td>';
                                    }
                                    ?>
                                    <?php
                                    $stmt2 = $pdo->prepare("SELECT Dni, Nombre FROM cliente WHERE ID_cliente = :id");
                                    $stmt2->bindParam(':id', $row['id_cliente'], PDO::PARAM_INT);
                                    $stmt2->execute();
                                    $cliente = $stmt2->fetch(PDO::FETCH_ASSOC);
                                    ?>
                                    <td><?php echo htmlspecialchars("{$cliente['Dni']} {$cliente['Nombre']}"); ?></td>
                                    <?php
                                    $stmt5 = $pdo->prepare("SELECT Nom_servicio FROM tipo_servicio WHERE ID_tipo_servicio = :id");
                                    $stmt5->bindParam(':id', $row['ID_tipo_servicio'], PDO::PARAM_INT);
                                    $stmt5->execute();
                                    $servicio = $stmt5->fetch(PDO::FETCH_ASSOC);
                                    ?>
                                    <td><?php echo htmlspecialchars($servicio['Nom_servicio']); ?></td>
                                    <td><?php echo htmlspecialchars($row['Codigo_Operacion']); ?></td>
                                    <td><?php echo htmlspecialchars($row['fecha_creacion']); ?></td>
                                    <td><?php echo htmlspecialchars($row['estado'] == 1 ? 'Activo' : 'Inactivo'); ?></td>
                                    <td>
                                        <button type="button" class="btn btn-primary btn-raised btn-xs" onclick="window.location.href = 'editar_pedido.php?ID=<?php echo $row['ID']; ?>'">
                                            <i class="zmdi zmdi-edit"></i>
                                        </button>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-danger btn-raised btn-xs" onclick="deletePedidoUnique(<?php echo htmlspecialchars($row['ID']); ?>);">
                                            <i class="zmdi zmdi-delete"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    function deletePedidoUnique(ID) {
        showConfirmModalUnique('¿Está seguro de que desea eliminar este pedido?', function() {
            var xhr = new XMLHttpRequest();
            xhr.open('POST', '<?php echo $URL; ?>/app/controllers/atencion_cliente/pedidos/eliminar_pedido.php', true);
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
                            showModalUnique('<span style="color:red;">' + (data.error || 'No se pudo eliminar el pedido.') + '</span>', 'danger');
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
            xhr.send(JSON.stringify({ ID: ID }));
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
        $('#confirmModalUnique').on('hidden.bs.modal', function() {
            $(this).remove();
        });
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
        $('#mensajeModalUnique').on('hidden.bs.modal', function() {
            $(this).remove();
        });
    }
</script>

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

