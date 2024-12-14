<?php
// Conexión a la base de datos y consultas
include('../app/config.php');
include('../layout/sesion.php');
include('../layout/parte1.php');
include('../layout/tecnico.php');


$id_detalle_tecnico = (isset($_GET['id'])) ? $_GET['id'] : 0;

if ($id_detalle_tecnico == 0) {
    header('Location: lista_tecnicos.php');
    exit;
}
$consulta2 = $pdo->prepare("SELECT p.id_producto, p.nombre, dt.cantidad
FROM detalle_tecnico_producto dt
INNER JOIN productos p ON dt.ID_producto = p.id_producto
WHERE dt.id_detall_tecnico_cliente = :id_detalle_tecnico
ORDER BY p.nombre");
$consulta2->execute([':id_detalle_tecnico' => $id_detalle_tecnico]);
$detalleTecnicoProducto2 = $consulta2->fetchAll(PDO::FETCH_ASSOC);
// Paginación
$limit = 5;
$page = (isset($_GET['page'])) ? $_GET['page'] : 1;
$startIndex = ($page - 1) * $limit;
$total = count($detalleTecnicoProducto2);
$pages = ceil($total / $limit);

echo "<table class='table table-hover table-striped'>
            <thead>
                <tr>
                    <th>id_producto</th>
                    <th>nombre</th>
                    <th>cantidad</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>";
foreach (array_slice($detalleTecnicoProducto2, $startIndex, $limit) as $detalle2) {
    echo "<tr>
                <td>{$detalle2['id_producto']}</td>
                <td>{$detalle2['nombre']}</td>
                <td>{$detalle2['cantidad']}</td>
                <td>

                    <button class='btn btn-success btn-sm' onclick='editarMaterial({$detalle2['id_producto']})'>
                        <i class='zmdi zmdi-edit'></i>
                    </button>
                    <button class='btn btn-danger btn-sm' onclick='eliminarMaterial({$detalle2['id_producto']})'>
                        <i class='zmdi zmdi-delete'></i>
                    </button>
                </td>
            </tr>";
}
echo "</tbody>
        </table>";
// Paginación
echo "<nav class='text-center'>
        <ul class='pagination pagination-sm'>";
for ($i = 1; $i <= $pages; $i++) {
    $active = ($page == $i) ? 'active' : '';
    echo "<li class='$active'><a href='?id=$id_detalle_tecnico&page=$i'>$i</a></li>";
}
echo "</ul>
    </nav>";
?>


<!-- Botón para abrir la modal -->
<p><button type="button" class="btn btn-primary" onclick="abrirModal()">
        <i class="zmdi zmdi-plus"></i> Agregar más materiales
    </button></p>

<!-- Modal -->
<div class="modal" id="modalAgregarDetalle">
    <div class="modal-content">
        <div class="modal-header">
            <span class="close" onclick="cerrarModal()">&times;</span>
            <h5 class="modal-title">Agregar material</h5>
        </div>
        <form id="agregarMaterialForm" method="post">
            <div class="modal-body">
                <div class="mb-3">
                    <label for="id_producto" class="form-label">Seleccione el material</label>
                    <select class="form-select" id="id_producto" name="id_producto">
                        <option value="">Seleccione un material</option>
                        <?php
                        $consulta3 = $pdo->prepare("SELECT id_producto, nombre FROM productos ORDER BY nombre");
                        $consulta3->execute();
                        while ($fila3 = $consulta3->fetch(PDO::FETCH_ASSOC)) {
                            echo "<option value='{$fila3['id_producto']}'>{$fila3['nombre']}</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="cantidad" class="form-label">Cantidad</label>
                    <input type="number" class="form-control" id="cantidad" name="cantidad" required>
                </div>
                <div class="mb-3">
                    <label for="Observacion" class="form-label">Observación</label>
                    <textarea class="form-control" id="Observacion" name="Observacion"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="cerrarModal()">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="agregarMaterial()">Agregar</button>
            </div>
        </form>

   
    </div>
</div>



<style>
    /* Estilo de la modal */
    .modal {
        display: none;
        /* Ocultar por defecto */
        position: fixed;
        /* Se queda en su lugar */
        z-index: 1;
        /* Estar encima de otros elementos */
        padding-top: 100px;
        /* Ubicación de la caja */
        left: 0;
        top: 0;
        width: 100%;
        /* Ancho completo */
        height: 100%;
        /* Alto completo */
        overflow: auto;
        /* Habilitar desplazamiento si es necesario */
        background-color: rgba(0, 0, 0, 0.4);
        /* Fondo oscuro con opacidad */
    }

    /* Contenido de la modal */
    .modal-content {
        background-color: #fefefe;
        margin: auto;
        padding: 20px;
        border: 1px solid #888;
        width: 50%;
    }

    /* El botón de cerrar */
    .close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
    }

    .close:hover,
    .close:focus {
        color: #000;
        text-decoration: none;
        cursor: pointer;
    }
</style>


<script>
    // Obtener el elemento de la modal
    const modalElement = document.getElementById('modalAgregarDetalle');

    // Función para abrir la modal
    function abrirModal() {
        modalElement.style.display = 'block'; // Muestra la modal
    }

    // Función para cerrar la modal
    function cerrarModal() {
        modalElement.style.display = 'none'; // Oculta la modal
    }

    // Cerrar la modal si se hace clic fuera del contenido de la modal
    window.onclick = function(event) {
        if (event.target == modalElement) {
            cerrarModal();
        }
    }

    function agregarMaterial() {
        const formData = new FormData(document.getElementById('agregarMaterialForm'));
        fetch('pro_agregar_producto.php?id_detalle_tecnico=<?php echo $id_detalle_tecnico; ?>', {
                method: 'POST',
                body: formData,
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => console.error('Error:', error));
    }

    function editarMaterial(idProducto) {
    // Redirigir a la página de edición con el id del producto
    const url = `editar_material.php?id_producto=${idProducto}`;
    window.location.href = url;
}

function eliminarMaterial(idProducto) {
    // Confirmar la eliminación
    const confirmar = confirm("¿Estás seguro de que deseas eliminar este material?");
    if (confirmar) {
        // Redirigir a la página de eliminación con el id del producto
        const url = `eliminar_material.php?id_producto=${idProducto}`;
        window.location.href = url;
    }
}

    
</script>