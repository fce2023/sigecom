<?php
require '../../../vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

include '../../config.php';

if (!isset($pdo) || !$pdo instanceof PDO) {
    die("No se pudo conectar a la base de datos.");
}

$query = "SELECT * FROM proveedor";
$stmt = $pdo->prepare($query);
$stmt->execute();
$proveedores = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!empty($proveedores)) {
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
    <h3>Reporte de Proveedores</h3>';
    $html .= '<table>';
    $html .= '<thead>
                <tr>
                    <th>ID Proveedor</th>
                    <th>Nombre</th>
                    <th>Dirección</th>
                    <th>Teléfono</th>
                    <th>Estado</th>
                </tr>
              </thead>';
    $html .= '<tbody>';
    
    foreach ($proveedores as $proveedor) {
        $html .= '<tr>';
        $html .= "<td>" . (isset($proveedor['ID_proveedor']) ? $proveedor['ID_proveedor'] : 'No disponible') . "</td>";
        $html .= "<td>" . (isset($proveedor['Nombre']) ? $proveedor['Nombre'] : 'No disponible') . "</td>";
        $html .= "<td>" . (isset($proveedor['Dirección']) ? $proveedor['Dirección'] : 'No disponible') . "</td>";
        $html .= "<td>" . (isset($proveedor['Teléfono']) ? $proveedor['Teléfono'] : 'No disponible') . "</td>";
        $html .= "<td>" . (isset($proveedor['Estado']) && $proveedor['Estado'] == 1 ? 'Activo' : 'Inactivo') . "</td>";
        $html .= '</tr>';
    }

    $html .= '</tbody>';
    $html .= '</table>';
    $html .= '<p style="text-align:right">Fecha de la consulta: ' . date('d/m/Y H:i:s') . '</p>';

} else {
    $html = 'No se encontraron proveedores.';
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

$dompdf->stream("ficha_proveedor.pdf", array("Attachment" => false));
?>

