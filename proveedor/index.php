<?php

include ('../app/config.php');
include ('../layout/sesion.php');
include ('../layout/parte1.php');
include ('../app/controllers/proveedor/listado_de_proveedor.php');

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
			<h1 class="text-titles"><i class="zmdi zmdi-account zmdi-hc-fw"></i> Lista <small>PROVEEDORES</small></h1>
		</div>
	</div>

	<div class="container-fluid">
		<ul class="breadcrumb breadcrumb-tabs">
			<li>
				<a href="crear.php" class="btn btn-info">
					<i class="zmdi zmdi-plus"></i> &nbsp; NUEVO PROVEEDOR
				</a>
			</li>
		</ul>
	</div>

    <div class="container-fluid">
		<ul class="breadcrumb breadcrumb-tabs">
			<li>
				<a href="<?php echo $URL; ?>/app/controllers/proveedor/generar_pdf.php" target="_blank" class="btn btn-info">
					<i class="zmdi zmdi-file-pdf"></i> &nbsp; GENERAR PDF
				</a>
			</li>
			<li>
				<a href="<?php echo $URL; ?>/app/controllers/proveedor/generar_exel.php" target="_blank" class="btn btn-success">
					<i class="zmdi zmdi-file-excel"></i> &nbsp; GENERAR EXCEL
				</a>
			</li>
		</ul>
	</div>
	
	<!-- Panel listado de proveedores -->
	<div class="container-fluid">
		<div class="panel panel-success">
			<div class="panel-heading">
				<h3 class="panel-title"><i class="zmdi zmdi-format-list-bulleted"></i> &nbsp; LISTA DE PROVEEDORES</h3>
			</div>
			<form method="GET" action="" class="estado-filter-form" style="display: flex; align-items: center; justify-content: space-between;">
				<!-- 	<label for="filtroEstado" style="margin-right: 10px;">Mostrar:</label>
				<select id="filtroEstado" name="filtroEstado" class="form-control estado-filter-select" onchange="this.form.submit()" style="margin-right: 10px;">
					<option value="todos" <?php echo (isset($_GET['filtroEstado']) && $_GET['filtroEstado'] == 'todos') ? 'selected' : ''; ?>>Mostrar ambos</option>
					<option value="activos" <?php echo (isset($_GET['filtroEstado']) && $_GET['filtroEstado'] == 'activos') ? 'selected' : ''; ?>>Mostrar solo activos</option>
					<option value="inactivos" <?php echo (isset($_GET['filtroEstado']) && $_GET['filtroEstado'] == 'inactivos') ? 'selected' : ''; ?>>Mostrar solo inactivos</option>
				</select> -->
				
			</form>
<div style="width: 300px; background-color: #f5f5f5; border-radius: 5px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
    <h1>Buscar proveedor</h1>
<div style="background-color: #f5f5f5;">
        <span style="background-color: #f5f5f5; border-right: none; border-radius: 5px 0 0 5px;"><i class="zmdi zmdi-search" style="color: #888;"></i></span>
    </div>
    <input type="text" id="buscadorNombre" name="buscadorNombre" class="form-control" placeholder="Buscar proveedor" value="<?php echo isset($_GET['buscadorNombre']) ? htmlspecialchars($_GET['buscadorNombre']) : ''; ?>" aria-label="Buscar proveedor" aria-describedby="basic-addon1" autocomplete="off" onkeyup="buscarProveedor(this.value)" style="background-color: #f5f5f5; border-left: none; border-radius: 0 5px 5px 0;">
</div>
<?php
    $filtroEstado = isset($_GET['filtroEstado']) ? $_GET['filtroEstado'] : 'todos';
    $buscadorNombre = isset($_GET['buscadorNombre']) ? $_GET['buscadorNombre'] : '';

    $query = "SELECT * FROM proveedor WHERE Nombre LIKE :buscadorNombre";
    if ($filtroEstado == 'activos') {
        $query .= " AND Estado = 1";
    } elseif ($filtroEstado == 'inactivos') {
        $query .= " AND Estado = 0";
    }

    $stmt = $pdo->prepare($query);
    $stmt->execute(['buscadorNombre' => "%$buscadorNombre%"]);
    $proveedores = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<div class="panel-body">
    <div class="table-responsive">
        <table class="table table-hover text-center" id="tablaProveedores">
            <thead>
                <tr>
                    <th class="text-center">#</th>
                  
                    <th class="text-center">Nombre</th>
                    <th class="text-center">Dirección</th>
                    <th class="text-center">Teléfono</th>
                    <th class="text-center">Estado</th>
                    <th class="text-center">EDITAR</th>
                    <th class="text-center">ELIMINAR</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($proveedores as $fila) {
                ?>
                <tr>
                    <td><?php echo isset($contador) ? ++$contador : ($contador = 1); ?></td>
                    
                    <td><?php echo $fila['Nombre']; ?></td>
                    <td><?php echo $fila['Dirección']; ?></td>
                    <td><?php echo $fila['Teléfono']; ?></td>
                    <td style="color: <?php echo $fila['Estado'] == 1 ? 'green' : 'red'; ?>"><?php echo $fila['Estado'] == 1 ? 'Activo' : 'Inactivo'; ?></td>
                    <td>
                        <button type="button" class="btn btn-primary btn-raised btn-xs" onclick="openModal('editarModalProveedor<?php echo $fila['ID_proveedor']; ?>')">
                            <i class="zmdi zmdi-edit"></i>
                        </button>
                        <?php 
                        $id_proveedor = $fila['ID_proveedor']; 
                        include 'funciones.php';
                        ?>
                    </td>
                    <td>
                        <form action="<?php echo $URL; ?>/app/controllers/proveedor/eliminar_proveedor.php" method="post" onsubmit="return confirmacionEliminar(event, this);">
                            <input type="hidden" name="id_proveedor" value="<?php echo $fila['ID_proveedor']; ?>">
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
        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger">
                <?php echo htmlspecialchars($_GET['error']); ?>
            </div>
        <?php endif; ?>
        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success">
                <?php echo htmlspecialchars($_GET['success']); ?>
            </div>
        <?php endif; ?>
        <script src="<?php echo $URL; ?>/js/funciones_proveedor.js"></script>
    </div>
    <nav class="text-center">
        <ul class="pagination pagination-sm">
            <li><a href="javascript:void(0)">«</a></li>
            <li><a href="javascript:void(0)">1</a></li>
            <li><a href="javascript:void(0)">2</a></li>
            <li><a href="javascript:void(0)">3</a></li>
            <li><a href="javascript:void(0)">4</a></li>
            <li><a href="javascript:void(0)">5</a></li>
            <li><a href="javascript:void(0)">»</a></li>
        </ul>
    </nav>
</div>

<script>
function confirmacionEliminar(e, form) {
    e.preventDefault();
    var modal = document.getElementById("customConfirmModal");
    modal.style.display = "block";
    document.getElementById("confirmDelete").onclick = function() {
        form.submit();
    };
    document.getElementById("cancelDelete").onclick = function() {
        modal.style.display = "none";
    };
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    };
}

function buscarProveedor(nombre) {
    var filter = nombre.toUpperCase();
    var table = document.getElementById("tablaProveedores");
    var tr = table.getElementsByTagName("tr");
    for (var i = 1; i < tr.length; i++) {
        var td = tr[i].getElementsByTagName("td")[2];
        if (td) {
            var txtValue = td.textContent || td.innerText;
            tr[i].style.display = txtValue.toUpperCase().indexOf(filter) > -1 ? "" : "none";
        }
    }
}
</script>

<style>
#customConfirmModal {
    display: none;
    position: fixed;
    z-index: 1;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0, 0, 0, 0.4);
}
#customConfirmModal .modal-content {
    background-color: white;
    margin: 15% auto;
    padding: 20px;
    border: 1px solid #888;
    width: 80%;
    max-width: 400px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}
#customConfirmModal .modal-header h2 {
    margin: 0;
}
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

