
<link href="css/styles.css" rel="stylesheet">

<style>
    .table-header {
        background-color: #333;
        color: #fff;
        padding: 10px;
        margin-bottom: 10px;
        border-radius: 10px 10px 0 0;
    }

    .table-responsive {
        max-width: 700px;
    }
</style>

<?php
include('../app/config.php');
include('../layout/sesion.php');
include('../layout/parte1.php');
include('../layout/tecnico.php');

$id_detalle_cliente_tecnico = 'Id_det_cliente_tecnico';
$id_tecnico = 'ID_tecnico';
$id_tipo_servicio = 'ID_tipo_servicio';
$id_usuario = 'ID_usuario';
$id_atencion_cliente = 'ID_atencion_cliente';

try {
    $query = "SELECT dct.$id_tecnico, dct.$id_atencion_cliente, ac.id_cliente, c.Dni, c.Nombre, c.Apellido_paterno, c.Apellido_materno, dct.$id_tipo_servicio, ts.Nom_servicio AS tipo_servicio_nombre, p.Nombre AS tecnico_nombre, p.Apellido_paterno AS tecnico_apellido_paterno, p.Apellido_materno AS tecnico_apellido_materno
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
$tecnicosHtml = '';
foreach ($tecnicos as $tecnico) {
    if ($tecnico[$id_tecnico] !== $currentTecnico) {
        if ($currentTecnico !== null) {
            $tecnicosHtml .= "</tbody>";
            $tecnicosHtml .= "</table>";
        }
        $currentTecnico = $tecnico[$id_tecnico];
        $contador = 0;
        $tecnicosHtml .= "<div class='table-header'>Técnico: {$tecnico['tecnico_nombre']} {$tecnico['tecnico_apellido_paterno']} {$tecnico['tecnico_apellido_materno']}</div>";
        $tecnicosHtml .= "<table class='table table-hover table-striped rounded'>";
        $tecnicosHtml .= "<thead>
                <tr>
                    <th>#</th>
                    <th>DNI</th>
                    <th>Nombre</th>
                    <th>Apellidos</th>
                    <th>Tipo de Servicio</th>
                </tr>
              </thead>
              <tbody>";
    }
    
    $contador++;
    $tecnicosHtml .= "<tr>
            <td>{$contador}</td>
            <td>{$tecnico['Dni']}</td>
            <td>{$tecnico['Nombre']}</td>
            <td>{$tecnico['Apellido_paterno']} {$tecnico['Apellido_materno']}</td>
            <td>{$tecnico['tipo_servicio_nombre']}</td>
          </tr>";
}
$tecnicosHtml .= "</tbody>";
$tecnicosHtml .= "</table>";
?>

<div class="container mt-5">
    <div class="table-responsive">
        <?php echo $tecnicosHtml; ?>
    </div>
</div>
<style>
/* styles.css */
.table thead th {
    background-color: #333;
    color: white;
    text-align: center;
}

.table tbody tr:hover {
    background-color: #f1f1f1;
}

.table tbody td {
    text-align: center;
}

.table th, .table td {
    padding: 15px;
}

.table {
    width: 100%;
    margin: 0 auto;
}

.alert {
    margin-top: 20px;
}

.table-header {
    font-size: 18px;
    font-weight: bold;
    padding: 10px;
    text-align: center;
    background-color: #444;
    color: white;
    border-radius: 5px;
    margin-bottom: 10px;
}

