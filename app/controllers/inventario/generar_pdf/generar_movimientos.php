<?php
require '../../../../vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

include '../../../config.php';

if (!isset($pdo) || !$pdo instanceof PDO) {
    die("No se pudo conectar a la base de datos.");
}

$query = "SELECT dpp.Id_det_producto_proveedor, 'Entrada' AS tipo, p.Nombre AS producto, dpp.Fecha_abastecimiento, dpp.cantidad, dpp.Observación, dpp.Estado AS estado_entrada, u.Nombre_usuario AS usuario_registra_entrada
          FROM detalle_producto_proveedor dpp
          LEFT JOIN productos p ON dpp.ID_producto = p.id_producto
          LEFT JOIN usuario u ON dpp.ID_usuario = u.ID_usuario
          WHERE dpp.Estado = 1
          UNION
          SELECT dtp.Id_det_tecnico_producto, 'Salida' AS tipo, p.Nombre AS producto, dtp.Fecha_retiro, dtp.cantidad AS cantidad_salida, dtp.Observación AS observacion_salida, dtp.Estado AS estado_salida, u.Nombre_usuario AS usuario_registra_salida
          FROM detalle_tecnico_producto dtp
          LEFT JOIN productos p ON dtp.ID_producto = p.id_producto
          LEFT JOIN usuario u ON dtp.ID_usuario = u.ID_usuario
          WHERE dtp.Estado = 1";
$stmt = $pdo->prepare($query);
$stmt->execute();
$movimientos = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!empty($movimientos)) {
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
    <h3>Reporte de Movimientos</h3>';
    $html .= '<table>';
    $html .= '<thead>
                <tr>
                    <th>N°</th>
                    <th>TIPO</th>
                    <th>USUARIO QUE REGISTRA</th>
                    <th>NOMBRE PRODUCTO</th>
                    <th>FECHA</th>
                    <th>CANTIDAD</th>
                    <th>OBSERVACIÓN</th>
                    <th>ESTADO</th>
                </tr>
              </thead>';
    $html .= '<tbody>';
    
    foreach ($movimientos as $key => $movimiento) {
        $html .= '<tr>';
        $html .= "<td>" . ($key + 1) . "</td>";
        $html .= "<td>" . (isset($movimiento['tipo']) ? $movimiento['tipo'] : 'No disponible') . "</td>";
        $html .= "<td>" . (isset($movimiento['usuario_registra_entrada']) ? $movimiento['usuario_registra_entrada'] : (isset($movimiento['usuario_registra_salida']) ? $movimiento['usuario_registra_salida'] : 'No disponible')) . "</td>";
        $html .= "<td>" . (isset($movimiento['producto']) ? $movimiento['producto'] : 'No disponible') . "</td>";
        $html .= "<td>" . (isset($movimiento['Fecha_abastecimiento']) ? $movimiento['Fecha_abastecimiento'] : (isset($movimiento['Fecha_retiro']) ? $movimiento['Fecha_retiro'] : 'No disponible')) . "</td>";
        $html .= "<td>" . (isset($movimiento['cantidad']) ? $movimiento['cantidad'] : 'No disponible') . "</td>";
        $html .= "<td>" . (isset($movimiento['Observación']) ? $movimiento['Observación'] : 'No disponible') . "</td>";
        $html .= "<td>" . ((isset($movimiento['estado_entrada']) && $movimiento['estado_entrada'] == 1) || (isset($movimiento['estado_salida']) && $movimiento['estado_salida'] == 1) ? 'Activo' : 'Inactivo') . "</td>";
        $html .= '</tr>';
    }

    $html .= '</tbody>';
    $html .= '</table>';
    $html .= '<p style="text-align:right">Fecha de la consulta: ' . date('d/m/Y H:i:s') . '</p>';

} else {
    $html = 'No se encontraron movimientos.';
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

$dompdf->stream("ficha_movimiento.pdf", array("Attachment" => false));
?>


