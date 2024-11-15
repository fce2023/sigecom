<?php
require '../../../../vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

include '../../../config.php';

if (!isset($pdo) || !$pdo instanceof PDO) {
    die("No se pudo conectar a la base de datos.");
}

$query = "SELECT p.id_producto, p.nombre, p.descripcion, tp.Nom_producto AS tipo, p.precio, p.estado FROM productos p LEFT JOIN tipo_producto tp ON p.id_tipo_producto = tp.ID_tipo_producto";
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
    <h3>Reporte de Productos</h3>';
    $html .= '<table>';
    $html .= '<thead>
                <tr>
                    <th>ID Producto</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Tipo</th>
                    <th>Precio</th>
                    <th>Estado</th>
                </tr>
              </thead>';
    $html .= '<tbody>';
    
    foreach ($productos as $producto) {
        $html .= '<tr>';
        $html .= "<td>" . (isset($producto['id_producto']) ? $producto['id_producto'] : 'No disponible') . "</td>";
        $html .= "<td>" . (isset($producto['nombre']) ? $producto['nombre'] : 'No disponible') . "</td>";
        $html .= "<td>" . (isset($producto['descripcion']) ? $producto['descripcion'] : 'No disponible') . "</td>";
        $html .= "<td>" . (isset($producto['tipo']) ? $producto['tipo'] : 'No disponible') . "</td>";
        $html .= "<td>" . (isset($producto['precio']) ? $producto['precio'] : 'No disponible') . "</td>";
        $html .= "<td>" . (isset($producto['estado']) && $producto['estado'] == 1 ? 'Activo' : 'Inactivo') . "</td>";
        $html .= '</tr>';
    }

    $html .= '</tbody>';
    $html .= '</table>';
    $html .= '<p style="text-align:right">Fecha de la consulta: ' . date('d/m/Y H:i:s') . '</p>';

} else {
    $html = 'No se encontraron productos.';
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

$dompdf->stream("ficha_producto.pdf", array("Attachment" => false));
?>

