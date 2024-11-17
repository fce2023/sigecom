<?php
require '../../../../vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

include '../../../config.php';

if (!isset($pdo) || !$pdo instanceof PDO) {
    die("No se pudo conectar a la base de datos.");
}

$query = "SELECT dpp.Id_det_producto_proveedor, p.Nombre AS producto, pr.Nombre AS proveedor, u.Nombre_usuario AS usuario, dpp.Fecha_abastecimiento, dpp.cantidad, dpp.Observación, dpp.Estado 
          FROM detalle_producto_proveedor dpp
          LEFT JOIN productos p ON dpp.ID_producto = p.id_producto
          LEFT JOIN proveedor pr ON dpp.ID_proveedor = pr.ID_proveedor
          INNER JOIN usuario u ON dpp.ID_usuario = u.ID_usuario";
$stmt = $pdo->prepare($query);
$stmt->execute();
$entradas = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!empty($entradas)) {
    $html = '
    <style>
        body {
            font-family: "Open Sans", sans-serif;
            margin: 20px;
        }
        h3 {
            text-align: center;
            font-size: 1.5em;
            font-weight: bold;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            table-layout: fixed;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 8px;
            text-align: left;
            word-break: break-word;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        tr:hover {
            background-color: #ddd;
        }
    </style>
    <h3>Reporte de Entradas</h3>';
    $html .= '<table>';
    $html .= '<thead>
                <tr>
                    <th>ID Detalle</th>
                    <th>Producto</th>
                    <th>Usuario que registró</th>
                    <th>Proveedor</th>
                    <th>Fecha Abastecimiento</th>
                    <th>Cantidad</th>
                    <th>Observación</th>
                    <th>Estado</th>
                </tr>
              </thead>';
    $html .= '<tbody>';
    
    foreach ($entradas as $entrada) {
        $html .= '<tr>';
        $html .= "<td>" . $entrada['Id_det_producto_proveedor'] . "</td>";
        $html .= "<td>" . $entrada['producto'] . "</td>";
        $html .= "<td>" . $entrada['usuario'] . "</td>";
        $html .= "<td>" . $entrada['proveedor'] . "</td>";
        $html .= "<td>" . $entrada['Fecha_abastecimiento'] . "</td>";
        $html .= "<td>" . $entrada['cantidad'] . "</td>";
        $html .= "<td>" . $entrada['Observación'] . "</td>";
        $html .= "<td>" . ($entrada['Estado'] == 1 ? 'Activo' : 'Inactivo') . "</td>";
        $html .= '</tr>';
    }
    $html .= '</tbody>';
    $html .= '</table>';
    $html .= '<p style="text-align:right">Fecha de la consulta: ' . date('d/m/Y H:i:s') . '</p>';

} else {
    $html = 'No se encontraron entradas.';
}

$html .= '<hr>';
$html .= '<footer class="footer full-box text-center" style="position: fixed; bottom: 0; width: 100%;">';
$html .= '<p class="text-footer">SIGECOM &copy; Grupo 2: 2024 Julio. B, Betty .S, Daniel. T @Todos los Derechos Reservados </p>';
$html .= '</footer>';

$dompdf = new Dompdf();
$options = $dompdf->getOptions();
$options->set('isHtml5ParserEnabled', true);
$options->set('isPhpEnabled', true);
$dompdf->setOptions($options);
$dompdf->loadHtml($html);

$dompdf->setPaper('A4', 'portrait');

$dompdf->render();

$dompdf->stream("ficha_entrada.pdf", array("Attachment" => false));
?>

