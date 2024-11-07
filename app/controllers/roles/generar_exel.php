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

// Obtener todos los tipos de usuario desde la base de datos
$query = "SELECT * FROM tipo_usuario";
$stmt = $pdo->prepare($query);
$stmt->execute();
$tiposUsuario = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Verifica si la variable $tiposUsuario está definida y contiene datos
if (!empty($tiposUsuario)) {
    // Crear un objeto Spreadsheet
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Títulos de las columnas
    $sheet->setCellValue('A1', 'ID Tipo Usuario');
    $sheet->setCellValue('B1', 'Nombre Tipo Usuario');
    $sheet->setCellValue('C1', 'Estado');

    // Itera sobre el array de tipos de usuario y agrega cada uno a la hoja
    $row = 2; // Comenzamos a escribir desde la segunda fila
    foreach ($tiposUsuario as $tipoUsuario) {
        $sheet->setCellValue('A' . $row, $tipoUsuario['ID_tipousuario'] ?? 'No disponible');
        $sheet->setCellValue('B' . $row, $tipoUsuario['Nombre_tipousuario'] ?? 'No disponible');
        $sheet->setCellValue('C' . $row, $tipoUsuario['Estado'] == 1 ? 'Activo' : 'Inactivo');
        $row++; // Incrementa la fila
    }

    // Establece un pie de página o una nota en la parte inferior
    $sheet->setCellValue('A' . $row, 'Fecha de la consulta: ' . date('d/m/Y H:i:s'));

    // Configura el escritor para guardar el archivo Excel
    $writer = new Xlsx($spreadsheet);

    // Envía el archivo Excel al navegador para que el usuario lo descargue
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="reporte_tipos_usuario.xlsx"');
    header('Cache-Control: max-age=0');

    // Escribe el archivo Excel en la salida
    $writer->save('php://output');
} else {
    echo 'No se encontraron tipos de usuario.';
}
?>

