<?php

include ('../app/config.php');
include ('../layout/sesion.php');
include ('../layout/parte1.php');
include ('../layout/tecnico.php');


$limit = 5;
$page = (isset($_GET['page'])) ? $_GET['page'] : 1;
$offset = ($page - 1) * $limit;

try {
    $query = "SELECT h.id, h.fecha, h.accion, h.detalle, u.Nombre_usuario as usuario, a.Codigo_Operacion as atencion_cliente, e.nombre as estado_atencion_cliente
              FROM historial_atencion_cliente h
              JOIN usuario u ON h.id_usuario = u.ID_usuario
              JOIN atencion_cliente a ON h.id_atencion_cliente = a.ID
              JOIN estado_atencion_cliente e ON h.id_estado_atencion_cliente = e.id";
    $stmt = $pdo->query($query);
    $total = $stmt->rowCount();
    $pages = ceil($total / $limit);

    $query .= " ORDER BY h.fecha DESC LIMIT :offset, :limit";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();

    echo "<h4>Paginación: Mostrando $limit registros de $total, de 5 en 5</h4>";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
<!-- Panel listado de productos -->
<div class="container-fluid">
    <div class="panel panel-success">
        <div class="panel-heading" style="background-color: #00b6ff;">
            <h3 class="panel-title">
                <i class="zmdi zmdi-format-list-bulleted"></i> &nbsp; HISTORIAL DE ATENCIÓN AL CLIENTE
            </h3>
        </div>
        <div class="panel-body">
            <div class="table-responsive">
                <table class="table table-hover text-center">
                    <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th class="text-center">FECHA</th>
                            <th class="text-center">USUARIO</th>
                            <th class="text-center">ATENCIÓN CLIENTE</th>
                            <th class="text-center">ESTADO</th>
                            <th class="text-center">ACCIÓN</th>
                            <th class="text-center">DETALLE</th>
                            <th class="text-center">ELIMINAR</th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $contador = 0;
                        if ($stmt) {
                            while ($fila = $stmt->fetch(PDO::FETCH_ASSOC)) : ?>
                                <tr>
                                    <td><?php echo ++$contador; ?></td>
                                    <td><?php echo htmlspecialchars($fila['fecha']); ?></td>
                                    <td><?php echo htmlspecialchars($fila['usuario']); ?></td>
                                    <td><?php echo htmlspecialchars($fila['atencion_cliente']); ?></td>
                                    <td><?php echo htmlspecialchars($fila['estado_atencion_cliente']); ?></td>
                                    <td><?php echo htmlspecialchars($fila['accion']); ?></td>
                                    <td><?php echo htmlspecialchars($fila['detalle']); ?></td>
                                    <td>
                                        <form action="delete_historial.php" method="post" onsubmit="return confirm('Are you sure you want to delete this item?');">
                                            <input type="hidden" name="id" value="<?php echo $fila['id']; ?>">
                                            <button type="submit" class="btn btn-danger btn-raised btn-xs">
                                                <i class="zmdi zmdi-delete"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endwhile;
                        } else { ?>
                            <tr>
                                <td colspan="7">No se encontraron datos</td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Paginación -->
<nav class="text-center">
    <ul class="pagination pagination-sm">
        <li class="<?php echo ($page <= 1) ? 'disabled' : ''; ?>"><a href="<?php echo $URL . '/atencion_cliente/historial.php?page=1'; ?>">«</a>
            <a href="<?php echo $page > 1 ? $URL . '/atencion_cliente/historial.php?page=' . ($page - 1) : 'javascript:void(0)'; ?>">«</a>
        </li>
        <?php
        $query = "SELECT COUNT(*) as total FROM historial_atencion_cliente";
        $stmt = $pdo->query($query);
        $total = $stmt ? $stmt->fetchColumn() : 0;
        $pages = ceil($total / $limit);
        for ($i = 1; $i <= $pages; $i++) {
            $active = ($i == $page) ? 'active' : '';
            echo '<li class="' . $active . '"><a href="' . $URL . '/atencion_cliente/historial.php?page=' . $i . '">' . $i . '</a></li>';
        }
        ?>
        <li class="<?php echo ($page >= $pages || $total <= ($page * $limit)) ? 'disabled' : ''; ?>">
            <a href="<?php echo $page < $pages && $total > ($page * $limit) ? $URL . '/atencion_cliente/historial.php?page=' . ($page + 1) : 'javascript:void(0)'; ?>">»</a>
        </li>
    </ul>
</nav>

