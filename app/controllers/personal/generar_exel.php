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

// Obtener todos los empleados desde la base de datos
$query = "SELECT p.ID_personal, p.Dni, p.Nombre, p.Apellido_paterno, p.Apellido_materno, p.Celular, p.Direccion, c.Nom_cargo, p.Estado FROM personal p INNER JOIN cargo c ON p.ID_cargo = c.ID_cargo";
$stmt = $pdo->prepare($query);
$stmt->execute();
$personal = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Verifica si la variable $personal está definida y contiene datos
if (!empty($personal)) {
    // Crear un objeto Spreadsheet
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Títulos de las columnas
    $sheet->setCellValue('A1', 'DNI');
    $sheet->setCellValue('B1', 'Nombre');
    $sheet->setCellValue('C1', 'Apellido paterno');
    $sheet->setCellValue('D1', 'Apellido materno');
    $sheet->setCellValue('E1', 'Celular');
    $sheet->setCellValue('F1', 'Dirección');
    $sheet->setCellValue('G1', 'Cargo');
    $sheet->setCellValue('H1', 'Estado');

    // Itera sobre el array de empleados y agrega cada uno a la hoja
    $row = 2; // Comenzamos a escribir desde la segunda fila
    foreach ($personal as $empleado) {
        $sheet->setCellValue('A' . $row, $empleado['Dni']);
        $sheet->setCellValue('B' . $row, $empleado['Nombre']);
        $sheet->setCellValue('C' . $row, $empleado['Apellido_paterno']);
        $sheet->setCellValue('D' . $row, $empleado['Apellido_materno']);
        $sheet->setCellValue('E' . $row, $empleado['Celular']);
        $sheet->setCellValue('F' . $row, $empleado['Direccion']);
        $sheet->setCellValue('G' . $row, $empleado['Nom_cargo']);
        $sheet->setCellValue('H' . $row, $empleado['Estado'] == 1 ? 'Activo' : 'Inactivo');

        $row++; // Incrementa la fila
    }

    // Establece un pie de página o una nota en la parte inferior
    $sheet->setCellValue('A' . $row, 'Fecha de la consulta: ' . date('d/m/Y H:i:s'));

    // Configura el escritor para guardar el archivo Excel
    $writer = new Xlsx($spreadsheet);

    // Envía el archivo Excel al navegador para que el usuario lo descargue
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="reporte_personal.xlsx"');
    header('Cache-Control: max-age=0');

    // Escribe el archivo Excel en la salida
    $writer->save('php://output');
} else {
    echo 'No se encontraron empleados.';
}
?>

