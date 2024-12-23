<?php

include ('../app/config.php');
include ('../layout/sesion.php');
include ('../layout/parte1.php');
include ('../app/controllers/personal/listado_de_personal.php');
@include '../plant/control/veri.php';

?>


<!DOCTYPE html>
<html lang="es">
<head>
	<title>Admin</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
</head>
<body>
	<!-- Content page -->
	<div class="container-fluid">
		<div class="page-header">
			<h1 class="text-titles"><i class="zmdi zmdi-accounts-list zmdi-hc-fw"></i> Lista <small>PERSONAL</P></small></h1>
		</div>
	</div>



    <div class="container-fluid">
		<ul class="breadcrumb breadcrumb-tabs">
			<li>
				<a href="crear.php" class="btn btn-info">
					<i class="zmdi zmdi-plus"></i> &nbsp; NUEVO PERSONAL
				</a>
			</li>
		</ul>
	</div>

	<div class="container-fluid">
		<ul class="breadcrumb breadcrumb-tabs">
			<li>
				<a href="<?php echo $URL; ?>/app/controllers/personal/generar_pdf.php" target="_blank" class="btn btn-info">
					<i class="zmdi zmdi-file-pdf"></i> &nbsp; GENERAR PDF
				</a>
			</li>
			<li>
				<a href="<?php echo $URL; ?>/app/controllers/personal/generar_exel.php" target="_blank" class="btn btn-success">
					<i class="zmdi zmdi-file-excel"></i> &nbsp; GENERAR EXCEL
				</a>
			</li>
		</ul>
	</div>

	<!-- Panel listado de personal -->
<div class="container-fluid" style="background-color: #E5E5E5;">
    <div class="panel panel-success">
        <div class="panel-heading">
            <h3 class="panel-title"><i class="zmdi zmdi-format-list-bulleted"></i> &nbsp; LISTA DE PERSONAL</h3>
        </div>
        <form method="GET" action="" class="estado-filter-form">
            <label for="filtroEstado">Mostrar:</label>
            <select id="filtroEstado" name="filtroEstado" class="form-control estado-filter-select" onchange="this.form.submit()">
                <option value="todos" <?php echo (isset($_GET['filtroEstado']) && $_GET['filtroEstado'] == 'todos') ? 'selected' : ''; ?>>Ambos</option>
                <option value="activos" <?php echo (isset($_GET['filtroEstado']) && $_GET['filtroEstado'] == 'activos') ? 'selected' : ''; ?>>Mostrar solo activos</option>
                <option value="inactivos" <?php echo (isset($_GET['filtroEstado']) && $_GET['filtroEstado'] == 'inactivos') ? 'selected' : ''; ?>>Mostrar solo inactivos</option>
            </select>
        </form>

        <?php
            $filtroEstado = isset($_GET['filtroEstado']) ? $_GET['filtroEstado'] : 'todos';

            // Filtra según el estado seleccionado
            $query = "SELECT * FROM personal";
            if ($filtroEstado == 'activos') {
                $query .= " WHERE Estado = 1";
            } elseif ($filtroEstado == 'inactivos') {
                $query .= " WHERE Estado = 0";
            }

            // Ejecuta la consulta
            $personal = $pdo->query($query);
        ?>

        <div class="panel-body" style="background-color: #F5F5F5;">
            <div style="overflow-x: scroll;">
                <?php
// Pagination logic
$items_per_page = 3;
$total_items = $personal->rowCount();
$total_pages = ceil($total_items / $items_per_page);
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$current_page = max(1, min($current_page, $total_pages)); // Ensure current page is within range
$offset = ($current_page - 1) * $items_per_page;

// Fetch paginated data
$query .= " LIMIT $offset, $items_per_page";
$paginated_personal = $pdo->query($query);
?>

<table class="table table-striped table-bordered text-center">
    <thead style="background-color: #2196F3; color: white;">
        <tr>
            <th class="text-center">#</th>
            <th class="text-center">DNI</th>
            <th class="text-center">NOMBRE</th>
            <th class="text-center">APELLIDO PATERNO</th>
            <th class="text-center">APELLIDO MATERNO</th>
            <th class="text-center">CELULAR</th>
            <th class="text-center">DIRECCION</th>
            <th class="text-center">CARGO</th>
            <th class="text-center">ESTADO</th>
            <th class="text-center">EDITAR</th>
            <th class="text-center">ELIMINAR</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $contador = $offset + 1;
        foreach ($paginated_personal as $fila) {
        ?>
        <tr>
            <td><?php echo $contador++; ?></td>
            <td><?php echo $fila['Dni']; ?></td>
            <td><?php echo $fila['Nombre']; ?></td>
            <td><?php echo $fila['Apellido_paterno']; ?></td>
            <td><?php echo $fila['Apellido_materno']; ?></td>
            <td><?php echo $fila['Celular']; ?></td>
            <td><?php echo $fila['Direccion']; ?></td>
            <td>
                <?php
                $query2 = "SELECT Nom_cargo FROM cargo WHERE ID_cargo = " . $fila['ID_cargo'];
                $cargo = $pdo->query($query2);
                foreach ($cargo as $fila2) {
                    echo $fila2['Nom_cargo'];
                }
                ?>
            </td>
            <td style="color: <?php echo $fila['Estado'] == 1 ? 'green' : 'red'; ?>"><?php echo $fila['Estado'] == 1 ? 'Activo' : 'Inactivo'; ?></td>
            <td>
                <button type="button" class="btn btn-primary btn-raised btn-xs" onclick="openModal('editarModalPersonal<?php echo $fila['ID_personal']; ?>')">
                    <i class="zmdi zmdi-edit"></i>
                </button>
                <?php 
                $id_personal = $fila['ID_personal'];
                include 'funciones.php';
                ?>
                <script src="<?php echo $URL; ?>/js/funciones_personal.js"></script>
            </td>
            <td>
                <form action="<?php echo $URL; ?>/app/controllers/personal/eliminar.php" method="post" onsubmit="return confirmacionEliminar(event, this);">
                    <input type="hidden" name="id_personal" value="<?php echo $fila['ID_personal']; ?>">
                    <button type="submit" class="btn btn-danger btn-raised btn-xs">
                        <i class="zmdi zmdi-delete"></i>
                    </button>
                </form>
            </td>
        </tr>
        <?php
        }
        ?>
    </tbody>
</table>

<!-- Pagination controls -->
<nav>
    <ul class="pagination">
        <li class="page-item <?php echo ($current_page <= 1) ? 'disabled' : ''; ?>">
            <a class="page-link" href="?page=<?php echo $current_page - 1; ?>" aria-label="Anterior">
                Anterior
            </a>
        </li>

        <?php for ($page = 1; $page <= $total_pages; $page++): ?>
            <li class="page-item <?php echo ($page == $current_page) ? 'active' : ''; ?>">
                <a class="page-link" href="?page=<?php echo $page; ?>"><?php echo $page; ?></a>
            </li>
        <?php endfor; ?>

        <li class="page-item <?php echo ($current_page >= $total_pages) ? 'disabled' : ''; ?>">
            <a class="page-link" href="?page=<?php echo $current_page + 1; ?>" aria-label="Siguiente">
                Siguiente
            </a>
        </li>
    </ul>
</nav>

            </div>
           
        </div>
    </div>
</div>
</section>
</body>
</html>

<script>
function confirmacionEliminar(e, form) {
    e.preventDefault(); // Prevenir el envío del formulario por defecto

    // Mostrar el modal de confirmación
    var modal = document.getElementById("customConfirmModal");
    modal.style.display = "block";

    // Configurar el comportamiento del botón de confirmación
    document.getElementById("confirmDelete").onclick = function() {
        form.submit(); // Enviar el formulario si el usuario confirma
    };

    // Configurar el comportamiento del botón de cancelación
    document.getElementById("cancelDelete").onclick = function() {
        modal.style.display = "none"; // Cerrar el modal si el usuario cancela
    };

    // Cerrar el modal si el usuario hace clic fuera de él
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    };
}

</script>

<style>
/* Estilos para el modal personalizado */
#customConfirmModal {
    display: none; /* Ocultar el modal por defecto */
    position: fixed;
    z-index: 1;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto; /* Hacer que el contenido sea desplazable si es necesario */
    background-color: rgba(0, 0, 0, 0.4); /* Fondo oscuro */
}

/* Estilo del contenido del modal */
#customConfirmModal .modal-content {
    background-color: white;
    margin: 15% auto;
    padding: 20px;
    border: 1px solid #888;
    width: 80%;
    max-width: 400px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

/* Estilo del título */
#customConfirmModal .modal-header h2 {
    margin: 0;
}

/* Estilo de los botones */
#customConfirmModal .modal-footer button {
    padding: 10px 20px;
    font-size: 16px;
    margin: 5px;
    cursor: pointer;
    border: none;
    border-radius: 5px;
}

#customConfirmModal .modal-footer #confirmDelete {
    background-color: #3085d6;
    color: white;
}

#customConfirmModal .modal-footer #cancelDelete {
    background-color: #d33;
    color: white;
}
</style>

<!-- Modal de confirmación personalizado -->
<div id="customConfirmModal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>¿Estás seguro?</h2>
        </div>
        <div class="modal-body">
            <p>No podrás revertir esto.</p>
        </div>
        <div class="modal-footer">
            <button type="button" id="confirmDelete">Sí, eliminarlo</button>
            <button type="button" id="cancelDelete">Cancelar</button>
        </div>
    </div>
</div>
