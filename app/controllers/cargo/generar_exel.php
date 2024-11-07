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

// Obtener todos los cargos desde la base de datos
$query = "SELECT * FROM cargo";
$stmt = $pdo->prepare($query);
$stmt->execute();
$cargos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Verifica si la variable $cargos está definida y contiene datos
if (!empty($cargos)) {
    // Crear un objeto Spreadsheet
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Títulos de las columnas
    $sheet->setCellValue('A1', 'ID Cargo');
    $sheet->setCellValue('B1', 'Nombre Cargo');
    $sheet->setCellValue('C1', 'Estado');

    // Itera sobre el array de cargos y agrega cada uno a la hoja
    $row = 2; // Comenzamos a escribir desde la segunda fila
    foreach ($cargos as $cargo) {
        $sheet->setCellValue('A' . $row, $cargo['ID_cargo'] ?? 'No disponible');
        $sheet->setCellValue('B' . $row, $cargo['Nom_cargo'] ?? 'No disponible');
        $sheet->setCellValue('C' . $row, $cargo['Estado'] == 1 ? 'Activo' : 'Inactivo');
        $row++; // Incrementa la fila
    }

    // Establece un pie de página o una nota en la parte inferior
    $sheet->setCellValue('A' . $row, 'Fecha de la consulta: ' . date('d/m/Y H:i:s'));

    // Configura el escritor para guardar el archivo Excel
    $writer = new Xlsx($spreadsheet);

    // Envía el archivo Excel al navegador para que el usuario lo descargue
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="reporte_cargos.xlsx"');
    header('Cache-Control: max-age=0');

    // Escribe el archivo Excel en la salida
    $writer->save('php://output');
} else {
    echo 'No se encontraron cargos.';
}
?>

