<?php
require '../../../../vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

include '../../../config.php';

if (!isset($pdo) || !$pdo instanceof PDO) {
    die("No se pudo conectar a la base de datos.");
}

$query = "SELECT dtp.Id_det_tecnico_producto, CONCAT(p.Nombre, ' ', p.Apellido_paterno, ' ', p.Apellido_materno) AS tecnico, u.Nombre_usuario AS usuario, prod.Nombre AS producto, dtp.Fecha_retiro, dtp.cantidad, dtp.Observación, dtp.Estado 
          FROM detalle_tecnico_producto dtp 
          LEFT JOIN tecnico t ON dtp.ID_tecnico = t.ID_tecnico 
          LEFT JOIN personal p ON t.id_personal = p.ID_personal
          LEFT JOIN productos prod ON dtp.ID_producto = prod.id_producto
          LEFT JOIN usuario u ON dtp.ID_usuario = u.ID_usuario";
$stmt = $pdo->prepare($query);
$stmt->execute();
$productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
if (!empty($productos)) {
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
    <h3>Reporte de Salidas</h3>';
    $html .= '<table>';
    $html .= '<thead>
                <tr>
                    <th>ID Salida</th>
                    <th>Tecnico</th>
                    <th>Usuario que registró</th>
                    <th>Producto</th>
                    <th>Fecha de Retiro</th>
                    <th>Cantidad</th>
                    <th>Observación</th>
                    <th>Estado</th>
                </tr>
              </thead>';
    $html .= '<tbody>';
    
    foreach ($productos as $key => $producto) {
        $html .= '<tr>';
        $html .= "<td>" . ($key + 1) . "</td>";
        $html .= "<td>" . (isset($producto['tecnico']) ? $producto['tecnico'] : 'No disponible') . "</td>";
        $html .= "<td>" . (isset($producto['usuario']) ? $producto['usuario'] : 'No disponible') . "</td>";
        $html .= "<td>" . (isset($producto['producto']) ? $producto['producto'] : 'No disponible') . "</td>";
        $html .= "<td>" . (isset($producto['Fecha_retiro']) ? $producto['Fecha_retiro'] : 'No disponible') . "</td>";
        $html .= "<td>" . (isset($producto['cantidad']) ? $producto['cantidad'] : 'No disponible') . "</td>";
        $html .= "<td>" . (isset($producto['Observación']) ? $producto['Observación'] : 'No disponible') . "</td>";
        $html .= "<td>" . (isset($producto['Estado']) && $producto['Estado'] == 1 ? 'Activo' : 'Inactivo') . "</td>";
        $html .= '</tr>';
    }

    $html .= '</tbody>';
    $html .= '</table>';
    $html .= '<p style="text-align:right">Fecha de la consulta: ' . date('d/m/Y H:i:s') . '</p>';

} else {
    $html = 'No se encontraron salidas.';
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

$dompdf->stream("ficha_salida.pdf", array("Attachment" => false));
?>

