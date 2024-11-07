<?php
require '../../../vendor/autoload.php'; // Asegúrate de que Dompdf esté cargado

use Dompdf\Dompdf;
use Dompdf\Options;

// Incluye la configuración de la base de datos
include '../../config.php'; // Asegúrate de que la configuración de la base de datos esté correcta

// Verifica si la conexión a la base de datos se ha realizado correctamente
if (!isset($pdo) || !$pdo instanceof PDO) {
    die("No se pudo conectar a la base de datos.");
}

// Obtener todos los cargos desde la base de datos
$query = "SELECT * FROM cargo";
$stmt = $pdo->prepare($query);
$stmt->execute();
$cargos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Verifica si la variable $cargos está definida y contiene datos
if (!empty($cargos)) {
    // Inicia el contenido HTML para el PDF con una tabla y estilos CSS
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
    <h3>Reporte de Cargos</h3>';
    $html .= '<table>';
    $html .= '<thead>
                <tr>
                    <th>ID Cargo</th>
                    <th>Nombre Cargo</th>
                    <th>Estado</th>
                </tr>
              </thead>';
    $html .= '<tbody>';
    
    // Itera sobre el array de cargos y agrega cada uno a la tabla
    foreach ($cargos as $cargo) {
        $html .= '<tr>';
        $contador = isset($contador) ? $contador + 1 : 1;
        $html .= "<td>" . $contador . "</td>";
        $html .= "<td>" . (isset($cargo['Nom_cargo']) ? $cargo['Nom_cargo'] : 'No disponible') . "</td>";
        $html .= "<td>" . (isset($cargo['Estado']) && $cargo['Estado'] == 1 ? 'Activo' : 'Inactivo') . "</td>";
        $html .= '</tr>';
    }

    $html .= '</tbody>';
    $html .= '</table>';
    $html .= '<p style="text-align:right">Fecha de la consulta: ' . date('d/m/Y H:i:s') . '</p>';

} else {
    $html = 'No se encontraron cargos.';
}

$html .= '<hr>';
$html .= '<footer class="footer full-box text-center" style="position: fixed; bottom: 0; width: 100%;">';
$html .= '<p class="text-footer">SIGECOM &copy; Grupo 2: 2024 Julio. B, Betty .S, Daniel. T @Todos los Derechos Reservados </p>';
$html .= '</footer>';
// Configuración de Dompdf
$dompdf = new Dompdf();
$options = $dompdf->getOptions();
$options->set('isHtml5ParserEnabled', true);
$options->set('isPhpEnabled', true);
$dompdf->setOptions($options);
$dompdf->loadHtml($html);

// Establece el tamaño del papel (A4, retrato)
$dompdf->setPaper('A4', 'portrait');

// Renderiza el PDF (convierte el HTML en un archivo PDF)
$dompdf->render();

// Muestra el PDF en el navegador
$dompdf->stream("ficha_cargo.pdf", array("Attachment" => false));
?>

