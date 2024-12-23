<style>
    .table-header {
        background-color: #333;
        color: #fff;
        padding: 2px;
        margin-bottom: 4px;
        border-radius: 10px 10px 0 0;
    }

    .table-responsive {
        width: 90%;
        overflow-x: auto;
    }

    table {
        table-layout: auto;
    }
    /* Estilos generales del modal */
    .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 1000;
        justify-content: center;
        align-items: center;
    }

    .modal-content {
        background-color: #fff;
        padding: 20px;
        border-radius: 5px;
        width: 400px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid #ccc;
        padding-bottom: 10px;
    }

    .modal-header h5 {
        margin: 0;
        font-size: 1.2em;
    }

    .modal-body {
        margin: 20px 0;
        font-size: 1em;
    }

    .modal-footer {
        display: flex;
        justify-content: space-between;
    }

    .btn {
        padding: 8px 16px;
        font-size: 1em;
        cursor: pointer;
        border-radius: 4px;
        border: none;
    }

    .cancel {
        background-color: #e57373; /* New color */
        color: white;
    }

    .accept {
        background-color: #81c784; /* New color */
        color: white;
    }

    .reject {
        background-color: #ffb74d; /* New color */
        color: white;
    }

    .close {
        background: none;
        border: none;
        font-size: 1.5em;
        cursor: pointer;
    }

    .close:hover {
        color: red;
    }
</style>

<?php
// Conexión a la base de datos y consultas
include('../app/config.php');
include('../layout/sesion.php');
include('../layout/parte1.php');
include('../layout/tecnico.php');

$id_detalle_cliente_tecnico = 'Id_det_cliente_tecnico';
$id_tecnico = 'ID_tecnico';
$id_tipo_servicio = 'ID_tipo_servicio';
$id_usuario = 'ID_usuario';
$id_atencion_cliente = 'ID_atencion_cliente';
$fecha_atencion = 'Fecha_atencion';
$observacion = 'Observacion';
$estado = 'Estado';

try {
    $query = "SELECT dct.$id_tecnico, dct.$id_atencion_cliente, ac.id_cliente, c.Dni, c.Nombre, c.Apellido_paterno, c.Apellido_materno, dct.$id_tipo_servicio, ts.Nom_servicio AS tipo_servicio_nombre, p.Nombre AS tecnico_nombre, p.Apellido_paterno AS tecnico_apellido_paterno, p.Apellido_materno AS tecnico_apellido_materno, ts.Estado AS tipo_servicio_estado, dct.$fecha_atencion, dct.$observacion, dct.$estado, dct.$id_detalle_cliente_tecnico
              FROM detalle_cliente_tecnico dct
              INNER JOIN atencion_cliente ac ON dct.$id_atencion_cliente = ac.ID
              INNER JOIN cliente c ON ac.id_cliente = c.ID_cliente
              INNER JOIN tecnico t ON dct.$id_tecnico = t.ID_tecnico
              INNER JOIN personal p ON t.id_personal = p.ID_personal
              INNER JOIN tipo_servicio ts ON dct.$id_tipo_servicio = ts.ID_tipo_servicio
              ORDER BY dct.$id_tecnico, dct.$id_atencion_cliente";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $tecnicos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "<div class='alert alert-danger'>Error: " . $e->getMessage() . "</div>";
    exit;
}

if (empty($tecnicos)) {
    echo "<div class='alert alert-warning'>No se encontraron técnicos o clientes asignados.</div>";
    exit;
}

$currentTecnico = null;
$contador = 0;

$tecnicosHtml = "<table class='table table-hover table-striped rounded'>";
$tecnicosHtml .= "<thead>
        <tr>
            <th>#</th>
            <th>Técnico</th>
            <th>Fecha de Atención</th>
            <th>Tipo de Servicio</th>
            <th>Estado</th>
            <th>Observación</th>
            <th>Cliente</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>";

// Obtener parámetros de paginación
$totalTecnicos = count($tecnicos);
$itemsPerPage = 5;
$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$startIndex = ($currentPage - 1) * $itemsPerPage;
$totalPages = ceil($totalTecnicos / $itemsPerPage);
$tecnicosPagina = array_slice($tecnicos, $startIndex, $itemsPerPage);

foreach ($tecnicosPagina as $tecnico) {
    // Extraer el nombre completo del técnico actual
    $nombreTecnicoActual = "{$tecnico['tecnico_nombre']} {$tecnico['tecnico_apellido_paterno']} {$tecnico['tecnico_apellido_materno']}";

    // Si el técnico cambia, agregar un encabezado de sección
    if ($currentTecnico !== $nombreTecnicoActual) {
        $currentTecnico = $nombreTecnicoActual;
        // Fila para los botones de generar reporte solo una vez por técnico
        $tecnicosHtml .= "<tr class='table-info'>
            <td colspan='8'><strong>Técnico: " . htmlspecialchars($currentTecnico) . "</strong></td>
        </tr>";
        $tecnicosHtml .= "<tr>
            <td colspan='8'>
                <a href='generar_reporte_pdf.php?id_tecnico=" . urlencode($tecnico[$id_tecnico]) . "' class='btn btn-secondary btn-sm me-2'>
                    <i class='fas fa-file-pdf'></i> Generar PDF
                </a>
                <a href='generar_reporte_excel.php?id_tecnico=" . urlencode($tecnico[$id_tecnico]) . "' class='btn btn-success btn-sm'>
                    <i class='fas fa-file-excel'></i> Generar Excel
                </a>
            </td>
        </tr>";
    }
    $idDetalleClienteTecnico = isset($tecnico[$id_detalle_cliente_tecnico]) ? $tecnico[$id_detalle_cliente_tecnico] : null;
    $tecnicosHtml .= "<tr id='fila_$idDetalleClienteTecnico'>
            <td>" . htmlspecialchars($contador + 1) . "</td>
            <td>" . htmlspecialchars($currentTecnico) . "</td>
            <td>" . htmlspecialchars($tecnico[$fecha_atencion]) . "</td>
            <td>" . htmlspecialchars($tecnico['tipo_servicio_nombre']) . "</td>
            <td>";

    switch ($tecnico[$estado]) {
        case 0: // Emitido
            $tecnicosHtml .= "<button class='btn btn-warning btn-sm' style='color: orange;' onclick='openModal($idDetalleClienteTecnico)'>Emitido</button>";
            break;
        case 1: // Aceptado
            $tecnicosHtml .= "<button class='btn btn-success btn-sm' style='color: green;' onclick='openModal($idDetalleClienteTecnico)'>Aceptado</button>";
            break;
        case 2: // Rechazado
            $tecnicosHtml .= "<button class='btn btn-danger btn-sm' style='color: red;' onclick='openModal($idDetalleClienteTecnico)'>Rechazado</button>";
            break;
        case 3: // Completado
            $tecnicosHtml .= "<button class='btn btn-primary btn-sm' style='color: blue;' onclick='openModal($idDetalleClienteTecnico)'>Completado</button>";
            break;
        default: // Desconocido
            $tecnicosHtml .= "<button class='btn btn-secondary btn-sm' style='color: black;' onclick='openModal($idDetalleClienteTecnico)'>Desconocido</button>";
            break;
    }
   $tecnicosHtml .= "</td>
            <td>" . htmlspecialchars($tecnico[$observacion]) . "</td>
            <td>" . htmlspecialchars("{$tecnico['Nombre']} {$tecnico['Apellido_paterno']} {$tecnico['Apellido_materno']}") . "</td>
            <td style='display: flex; gap: 10px;'>
                <button type='button' class='btn btn-primary btn-sm' onclick=\"window.location.href='editar_tecnico_designado.php?id=$idDetalleClienteTecnico'\">Editar</button>
                <button type='button' class='btn btn-info btn-sm' onclick=\"window.location.href='mas_detalles.php?id=$idDetalleClienteTecnico'\">Materiales</button>
                <button type='button' class='btn btn-danger btn-sm' onclick=\"window.location.href='eliminar_cliente.php?id=$idDetalleClienteTecnico'\">Eliminar</button>
            </td>
        </tr>";
    $contador++;
}

$tecnicosHtml .= "</tbody></table>";

// Generar enlaces de paginación
$tecnicosHtml .= "<nav><ul class='pagination'>";
if ($currentPage > 1) {
    $prevPage = $currentPage - 1;
    $tecnicosHtml .= "<li class='page-item'><a class='page-link' href='?page=$prevPage'>Anterior</a></li>";
}
for ($i = 1; $i <= $totalPages; $i++) {
    $activeClass = ($i === $currentPage) ? 'active' : '';
    $tecnicosHtml .= "<li class='page-item $activeClass'><a class='page-link' href='?page=$i'>$i</a></li>";
}
if ($currentPage < $totalPages) {
    $nextPage = $currentPage + 1;
    $tecnicosHtml .= "<li class='page-item'><a class='page-link' href='?page=$nextPage'>Siguiente</a></li>";
}

$tecnicosHtml .= "</ul></nav>";


?>

<div class="container mt-5">
    <div class="table-responsive">
        <?php echo $tecnicosHtml; ?>
    </div>
</div>


<!-- Modal para cambiar el estado -->
<?php foreach ($tecnicos as $tecnico) { ?>
    <div class="modal" id="modal_<?php echo $tecnico[$id_detalle_cliente_tecnico]; ?>">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Aceptar/Rechazar Atención</h5>
                <button type="button" class="close" onclick="closeModal(<?php echo $tecnico[$id_detalle_cliente_tecnico]; ?>)">
                    &times;
                </button>
            </div>
            <div class="modal-body">
                <p>¿Está seguro de aceptar o rechazar la atención?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary cancel" onclick="closeModal(<?php echo $tecnico[$id_detalle_cliente_tecnico]; ?>)">Cancelar</button>
                <button type="button" class="btn btn-success accept" onclick="cambiar_estado(<?php echo $tecnico[$id_detalle_cliente_tecnico]; ?>, 1)">Aceptar</button>
                <button type="button" class="btn btn-danger reject" onclick="cambiar_estado(<?php echo $tecnico[$id_detalle_cliente_tecnico]; ?>, 2)">Rechazar</button>
                <button type="button" class="btn btn-primary reject" style="color: #007bff;" onclick="cambiar_estado(<?php echo $tecnico[$id_detalle_cliente_tecnico]; ?>, 3)">Completado</button>
            </div>
        </div>
    </div>
<?php } ?>





<!-- Script JS para cambiar el estado -->
<script>
    function openModal(id) {
        document.getElementById('modal_' + id).style.display = 'flex';
    }

    function closeModal(id) {
        document.getElementById('modal_' + id).style.display = 'none';
    }

    function cambiar_estado(id, estado) {
    console.log("Cambiando estado para id: " + id + " a estado: " + estado);

    // Crear el objeto FormData
    var formData = new FormData();
    formData.append('id', id);
    formData.append('estado', estado);

    // Enviar la solicitud al servidor usando fetch
    fetch('<?php echo $URL; ?>/app/controllers/designar_tecnico/actualizar_estado.php', {
        method: 'POST',
        body: formData
    })
    .then(function(response) {
        return response.json(); // Parsear la respuesta JSON
    })
    .then(function(data) {
        if (data.success) {
            alert(data.message); // Mostrar mensaje de éxito
            actualizarUI(id, estado); // Actualizar la interfaz
        } else {
            alert(data.message); // Mostrar mensaje de error
        }
    })
    .catch(function(err) {
        console.log('Error:', err); // Manejo de errores
    });
}


    function actualizarUI(id, estado) {
        let mensaje;
        switch (estado) {
            case 1:
                mensaje = 'Atención aceptada';
                break;
            case 2:
                mensaje = 'Atención rechazada';
                break;
            case 3:
                mensaje = 'Atención completada';
                break;
            default:
                mensaje = 'Estado desconocido';
        }
        alert(mensaje);
        closeModal(id);
        const estadoTexto = estado === 1 ? 'Aceptado' : (estado === 2 ? 'Rechazado' : 'Completado');
        document.getElementById('fila_' + id).querySelectorAll('td')[4].textContent = estadoTexto;
    }

    function rechazar(id) {
        if (confirm("¿Está seguro de rechazar la atención?")) {
            cambiar_estado(id, 2);
        } else {
            closeModal(id);
        }
    }
</script>
