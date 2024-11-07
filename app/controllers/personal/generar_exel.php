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
$query = "SELECT * FROM personal";
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
    $sheet->setCellValue('C1', 'Apellido');
    $sheet->setCellValue('D1', 'Celular');
    $sheet->setCellValue('E1', 'Dirección');
    $sheet->setCellValue('F1', 'Cargo');
    $sheet->setCellValue('G1', 'Estado');

    // Itera sobre el array de empleados y agrega cada uno a la hoja
    $row = 2; // Comenzamos a escribir desde la segunda fila
    foreach ($personal as $empleado) {
        $sheet->setCellValue('A' . $row, isset($empleado['Dni']) ? $empleado['Dni'] : 'No disponible');
        $sheet->setCellValue('B' . $row, isset($empleado['Nombre']) ? $empleado['Nombre'] : 'No disponible');
        $sheet->setCellValue('C' . $row, isset($empleado['Apellido']) ? $empleado['Apellido'] : 'No disponible');
        $sheet->setCellValue('D' . $row, isset($empleado['Celular']) ? $empleado['Celular'] : 'No disponible');
        $sheet->setCellValue('E' . $row, isset($empleado['Direccion']) ? $empleado['Direccion'] : 'No disponible');

        // Obtener el nombre del cargo de la base de datos
        $query2 = "SELECT Nom_cargo FROM cargo WHERE ID_cargo = " . $empleado['ID_cargo'];
        $stmt2 = $pdo->prepare($query2);
        $stmt2->execute();
        $cargo = $stmt2->fetch(PDO::FETCH_ASSOC);
        $sheet->setCellValue('F' . $row, isset($cargo['Nom_cargo']) ? $cargo['Nom_cargo'] : 'No disponible');

        // Estado (Activo/Inactivo)
        $estado = isset($empleado['Estado']) && $empleado['Estado'] == 1 ? 'Activo' : 'Inactivo';
        $sheet->setCellValue('G' . $row, $estado);

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
