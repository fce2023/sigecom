<?php
require '../../../../vendor/autoload.php'; // Asegúrate de que PhpSpreadsheet esté cargado

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Incluye la configuración de la base de datos
include '../../../config.php'; // Asegúrate de que la configuración de la base de datos esté correcta

// Verifica si la conexión a la base de datos se ha realizado correctamente
if (!isset($pdo) || !$pdo instanceof PDO) {
    die("No se pudo conectar a la base de datos.");
}

// Obtener todos los productos desde la base de datos
$query = "SELECT dpp.Id_det_producto_proveedor, p.Nombre AS producto, u.Nombre_usuario AS usuario, pr.Nombre AS proveedor, dpp.Fecha_abastecimiento, dpp.cantidad, dpp.Observación, dpp.Estado AS estado_entrada
FROM detalle_producto_proveedor dpp
LEFT JOIN usuario u ON dpp.ID_usuario = u.ID_usuario
LEFT JOIN productos p ON dpp.ID_producto = p.id_producto
LEFT JOIN proveedor pr ON dpp.ID_proveedor = pr.ID_proveedor";
$stmt = $pdo->prepare($query);
$stmt->execute();
$entradas = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Verifica si la variable $entradas está definida y contiene datos
if (!empty($entradas)) {
    // Crear un objeto Spreadsheet
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Títulos de las columnas
    $sheet->setCellValue('A1', 'ID');
    $sheet->setCellValue('B1', 'Producto');
    $sheet->setCellValue('C1', 'Usuario');
    $sheet->setCellValue('D1', 'Proveedor');
    $sheet->setCellValue('E1', 'Fecha de abastecimiento');
    $sheet->setCellValue('F1', 'Cantidad');
    $sheet->setCellValue('G1', 'Observación');
    $sheet->setCellValue('H1', 'Estado');

    // Itera sobre el array de productos y agrega cada uno a la hoja
    $row = 2; // Comenzamos a escribir desde la segunda fila
    foreach ($entradas as $entrada) {
        $sheet->setCellValue('A' . $row, $entrada['Id_det_producto_proveedor']);
        $sheet->setCellValue('B' . $row, $entrada['producto']);
        $sheet->setCellValue('C' . $row, $entrada['usuario']);
        $sheet->setCellValue('D' . $row, $entrada['proveedor']);
        $sheet->setCellValue('E' . $row, $entrada['Fecha_abastecimiento']);
        $sheet->setCellValue('F' . $row, $entrada['cantidad']);
        $sheet->setCellValue('G' . $row, $entrada['Observación']);

        // Estado (Activo/Inactivo)
        $estado = $entrada['estado_entrada'] == 1 ? 'Activo' : 'Inactivo';
        $sheet->setCellValue('H' . $row, $estado);

        $row++; // Incrementa la fila
    }

    // Establece un pie de página o una nota en la parte inferior
    $sheet->setCellValue('A' . $row, 'Fecha de la consulta: ' . date('d/m/Y H:i:s'));

    // Configura el escritor para guardar el archivo Excel
    $writer = new Xlsx($spreadsheet);

    // Envía el archivo Excel al navegador para que el usuario lo descargue
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="reporte_entrada.xlsx"');
    header('Cache-Control: max-age=0');

    // Escribe el archivo Excel en la salida
    $writer->save('php://output');
} else {
    echo 'No se encontraron entradas.';
}
?>

