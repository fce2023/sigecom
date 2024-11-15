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

// Obtener todos los empleados desde la base de datos
$query = "SELECT p.ID_personal, p.Dni, p.Nombre, p.Apellido_paterno, p.Apellido_materno, p.Celular, p.Direccion, c.Nom_cargo, p.Estado FROM personal p INNER JOIN cargo c ON p.ID_cargo = c.ID_cargo";
$stmt = $pdo->prepare($query);
$stmt->execute();
$personal = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Verifica si la variable $personal está definida y contiene datos
if (!empty($personal)) {
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
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 8px;
            text-align: left;
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
    <h3>Reporte de Personal</h3>';
    $html .= '<table>';
    $html .= '<thead>
                <tr>
                    <th>DNI</th>
                    <th>Nombre</th>
                    <th>Apellido Paterno</th>
                    <th>Apellido Materno</th>
                    <th>Celular</th>
                    <th>Dirección</th>
                    <th>Cargo</th>
                    <th>Estado</th>
                </tr>
              </thead>';
    $html .= '<tbody>';
    
    // Itera sobre el array de empleados y agrega cada uno a la tabla
    foreach ($personal as $empleado) {
        $html .= '<tr>';
        $html .= "<td>" . (isset($empleado['Dni']) ? $empleado['Dni'] : 'No disponible') . "</td>";
        $html .= "<td>" . (isset($empleado['Nombre']) ? $empleado['Nombre'] : 'No disponible') . "</td>";
        $html .= "<td>" . (isset($empleado['Apellido_paterno']) ? $empleado['Apellido_paterno'] : 'No disponible') . "</td>";
        $html .= "<td>" . (isset($empleado['Apellido_materno']) ? $empleado['Apellido_materno'] : 'No disponible') . "</td>";
        $html .= "<td>" . (isset($empleado['Celular']) ? $empleado['Celular'] : 'No disponible') . "</td>";
        $html .= "<td>" . (isset($empleado['Direccion']) ? $empleado['Direccion'] : 'No disponible') . "</td>";
        $html .= "<td>" . (isset($empleado['Nom_cargo']) ? $empleado['Nom_cargo'] : 'No disponible') . "</td>";
        $html .= "<td>" . (isset($empleado['Estado']) && $empleado['Estado'] == 1 ? 'Activo' : 'Inactivo') . "</td>";
        $html .= '</tr>';
    }

    $html .= '</tbody>';
    $html .= '</table>';
    $html .= '<p style="text-align:right">Fecha de la consulta: ' . date('d/m/Y H:i:s') . '</p>';

} else {
    $html = 'No se encontraron empleados.';
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
$dompdf->stream("ficha_personal.pdf", array("Attachment" => false));
?>

