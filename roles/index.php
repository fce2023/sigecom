<?php

include ('../app/config.php');
include ('../layout/sesion.php');
include ('../layout/parte1.php');
include ('../app/controllers/roles/listado_de_tipos_usuario.php');

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
			<h1 class="text-titles"><i class="zmdi zmdi-account zmdi-hc-fw"></i> Lista <small>TIPOS DE USUARIO</P></small></h1>
		</div>
	</div>

	<div class="container-fluid">
		<ul class="breadcrumb breadcrumb-tabs">
			<li>
				<a href="crear.php" class="btn btn-info">
					<i class="zmdi zmdi-plus"></i> &nbsp; NUEVO TIPO DE USUARIO
				</a>
			</li>
		</ul>
	</div>
	
	<!-- Panel listado de tipos de usuario -->
<div class="container-fluid">
    <div class="panel panel-success">
        <div class="panel-heading">
            <h3 class="panel-title"><i class="zmdi zmdi-format-list-bulleted"></i> &nbsp; LISTA DE TIPOS DE USUARIO</h3>
        </div>
        <form method="GET" action="" class="estado-filter-form">
            <label for="filtroEstado">Mostrar:</label>
            <select id="filtroEstado" name="filtroEstado" class="form-control estado-filter-select" onchange="this.form.submit()">
                <option value="todos" <?php echo (isset($_GET['filtroEstado']) && $_GET['filtroEstado'] == 'todos') ? 'selected' : ''; ?>>Mostrar ambos</option>
                <option value="activos" <?php echo (isset($_GET['filtroEstado']) && $_GET['filtroEstado'] == 'activos') ? 'selected' : ''; ?>>Mostrar solo activos</option>
                <option value="inactivos" <?php echo (isset($_GET['filtroEstado']) && $_GET['filtroEstado'] == 'inactivos') ? 'selected' : ''; ?>>Mostrar solo inactivos</option>
            </select>
        </form>

        <?php
            $filtroEstado = isset($_GET['filtroEstado']) ? $_GET['filtroEstado'] : 'todos';

            // Filtra según el estado seleccionado
            $query = "SELECT tipo_usuario.ID_tipousuario, tipo_usuario.Nombre_tipousuario, tipo_usuario.Estado FROM tipo_usuario";
            if ($filtroEstado == 'activos') {
                $query .= " WHERE tipo_usuario.Estado = '1'";
            } elseif ($filtroEstado == 'inactivos') {
                $query .= " WHERE tipo_usuario.Estado = '0'";
            }

            // Ejecuta la consulta
            $tipos_usuario = $pdo->query($query);
        ?>

        <div class="panel-body">
            <div class="table-responsive">
                
            <table class="table table-hover text-center">
                <thead>
                    <tr>
                        <th class="text-center">#</th>
                        <th class="text-center">NOMBRE TIPO DE USUARIO</th>
                        <th class="text-center">ESTADO</th>
                        <th class="text-center">EDITAR</th>
                        <th class="text-center">ELIMINAR</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($tipos_usuario as $fila) {
                    ?>
                    <tr>
                        <td><?php echo $contador += 1; ?></td>
                        <td><?php echo $fila['Nombre_tipousuario']; ?></td>
                        <td style="color: <?php echo $fila['Estado'] == '1' ? 'green' : 'red'; ?>"><?php echo $fila['Estado'] == '1' ? 'Activo' : 'Inactivo'; ?></td>
                        <td>
                            <button type="button" class="btn btn-primary btn-raised btn-xs" onclick="openModalTipoUsuario('<?php echo $fila['ID_tipousuario']; ?>')">
                                <i class="zmdi zmdi-edit"></i>
                            </button>
                            <?php 
                            $id_tipousuario = $fila['ID_tipousuario']; 
                            include 'funciones.php';
                            ?>
                        </td>



                        <td>
						<form action="<?php echo $URL; ?>/app/controllers/roles/eliminar.php" method="post" onsubmit="return confirmacionEliminar(event, this);">
									<input type="hidden" name="id_tipousuario" value="<?php echo $fila['ID_tipousuario']; ?>">
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
			<script src="<?php echo $URL; ?>/js/funciones_tipo_usuario.js"></script>
            </div>
            <nav class="text-center">
                <ul class="pagination pagination-sm">
                    <li class="disabled"><a href="javascript:void(0)">«</a></li>
                    <li class="active"><a href="javascript:void(0)">1</a></li>
                    <li><a href="javascript:void(0)">2</a></li>
                    <li><a href="javascript:void(0)">3</a></li>
                    <li><a href="javascript:void(0)">4</a></li>
                    <li><a href="javascript:void(0)">5</a></li>
                    <li><a href="javascript:void(0)">»</a></li>
                </ul>
            </nav>
        </div>
    </div>
</div>



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