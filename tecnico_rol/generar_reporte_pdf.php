<?php
require_once '../app/config.php';
require_once '../layout/sesion.php';
require_once 'consulta_detalles_tecnico_cliente.php';
require_once('../vendor/autoload.php');
use Dompdf\Dompdf;
$dompdf = new Dompdf();
$dompdf->setPaper('A4', 'portrait');

$html = "";
$html .= "<style>
    table {
        border-collapse: collapse;
        border: 1px solid #ddd;
        width: 100%;
    }
    th, td {
        text-align: left;
        padding: 8px;
    }
    th {
        background-color: #4CAF50;
        color: white;
    }
    tr:nth-child(even) {
        background-color: #f2f2f2;
    }
</style>";
$html .= "<h2 style='text-align: center;'>Reporte de Atenciones por T&eacute;cnico $nombredeltecnico</h2>";
$html .= "<br><table class='table table-bordered'><thead><tr><th>#</th><th>Cliente</th><th>Servicio</th><th>Fecha</th><th>Estado</th></tr></thead><tbody>";
$contador = 0;
foreach ($clientes as $index => $cliente) {
    $contador++;
    $fechaHora = date('d/m/Y H:i', strtotime($detalleClienteTecnico[$index]['Fecha_atencion'] ?? ''));
    $estado = $detalleClienteTecnico[$index]['Estado'] == 0 ? 'Emitido' : ($detalleClienteTecnico[$index]['Estado'] == 1 ? 'Aceptado' : 'Rechazado');
    $nombreCliente = htmlspecialchars($cliente['Nombre'] ?? '');
    $dniCliente = htmlspecialchars($cliente['Dni'] ?? '');
    $html .= "<tr><td>" . $contador . "</td><td>$nombreCliente $dniCliente</td><td>" . htmlspecialchars($nombreServicio) . "</td><td>" . $fechaHora . "</td><td>" . htmlspecialchars($estado) . "</td></tr>";
}
$html .= "</tbody></table><br>";
$html .= "<h4>Reporte echo por " . htmlspecialchars($nombre_usuario_sesion ?? '') . " el " . date('d/m/Y H:i') . "</h4>";
$dompdf->loadHtml($html);
$dompdf->render();
$dompdf->stream("reporte_atenciones_tecnico_$nombredeltecnico.pdf", array("Attachment" => 0));


