<?php
require '../../../vendor/autoload.php'; // Asegúrate de que PhpSpreadsheet esté cargado

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Incluye la configuración de la base de datos
include '../../config.php'; // Asegúrate de que la configuración de la base de datos esté correcta

// Verifica si la conexión a la base de datos se ha realizado correctamente
if (!isset($pdo) || !$pdo instanceof PDO) {
    die("No se pudo conectar a la base de datos.");
}

// Obtener todos los proveedores desde la base de datos
$query = "SELECT * FROM proveedor";
$stmt = $pdo->prepare($query);
$stmt->execute();
$proveedores = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Verifica si la variable $proveedores está definida y contiene datos
if (!empty($proveedores)) {
    // Crear un objeto Spreadsheet
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Títulos de las columnas
    $sheet->setCellValue('A1', 'Nombre');
    $sheet->setCellValue('B1', 'Dirección');
    $sheet->setCellValue('C1', 'Teléfono');
    $sheet->setCellValue('D1', 'Estado');

    // Itera sobre el array de proveedores y agrega cada uno a la hoja
    $row = 2; // Comenzamos a escribir desde la segunda fila
    foreach ($proveedores as $proveedor) {
        $sheet->setCellValue('A' . $row, isset($proveedor['Nombre']) ? $proveedor['Nombre'] : 'No disponible');
        $sheet->setCellValue('B' . $row, isset($proveedor['Dirección']) ? $proveedor['Dirección'] : 'No disponible');
        $sheet->setCellValue('C' . $row, isset($proveedor['Teléfono']) ? $proveedor['Teléfono'] : 'No disponible');

        // Estado (Activo/Inactivo)
        $estado = isset($proveedor['Estado']) && $proveedor['Estado'] == 1 ? 'Activo' : 'Inactivo';
        $sheet->setCellValue('D' . $row, $estado);

        $row++; // Incrementa la fila
    }

    // Establece un pie de página o una nota en la parte inferior
    $sheet->setCellValue('A' . $row, 'Fecha de la consulta: ' . date('d/m/Y H:i:s'));

    // Configura el escritor para guardar el archivo Excel
    $writer = new Xlsx($spreadsheet);

    // Envía el archivo Excel al navegador para que el usuario lo descargue
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="reporte_proveedores.xlsx"');
    header('Cache-Control: max-age=0');

    // Escribe el archivo Excel en la salida
    $writer->save('php://output');
} else {
    echo 'No se encontraron proveedores.';
}
?>

