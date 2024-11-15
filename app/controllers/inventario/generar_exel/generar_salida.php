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
$query = "SELECT p.id_producto, p.nombre, p.descripcion, tp.Nom_producto AS tipo_producto, p.precio, p.fecha_registro, p.estado
FROM productos p
LEFT JOIN tipo_producto tp ON p.id_tipo_producto = tp.ID_tipo_producto";
$stmt = $pdo->prepare($query);
$stmt->execute();
$productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Verifica si la variable $productos está definida y contiene datos
if (!empty($productos)) {
    // Crear un objeto Spreadsheet
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Títulos de las columnas
    $sheet->setCellValue('A1', 'ID');
    $sheet->setCellValue('B1', 'Nombre');
    $sheet->setCellValue('C1', 'Descripción');
    $sheet->setCellValue('D1', 'Tipo de producto');
    $sheet->setCellValue('E1', 'Precio');
    $sheet->setCellValue('F1', 'Fecha de registro');
    $sheet->setCellValue('G1', 'Estado');

    // Itera sobre el array de productos y agrega cada uno a la hoja
    $row = 2; // Comenzamos a escribir desde la segunda fila
    foreach ($productos as $producto) {
        $sheet->setCellValue('A' . $row, $producto['id_producto']);
        $sheet->setCellValue('B' . $row, $producto['nombre']);
        $sheet->setCellValue('C' . $row, $producto['descripcion']);
        $sheet->setCellValue('D' . $row, $producto['tipo_producto']);
        $sheet->setCellValue('E' . $row, $producto['precio']);
        $sheet->setCellValue('F' . $row, $producto['fecha_registro']);

        // Estado (Activo/Inactivo)
        $estado = $producto['estado'] == 1 ? 'Activo' : 'Inactivo';
        $sheet->setCellValue('G' . $row, $estado);

        $row++; // Incrementa la fila
    }

    // Establece un pie de página o una nota en la parte inferior
    $sheet->setCellValue('A' . $row, 'Fecha de la consulta: ' . date('d/m/Y H:i:s'));

    // Configura el escritor para guardar el archivo Excel
    $writer = new Xlsx($spreadsheet);

    // Envía el archivo Excel al navegador para que el usuario lo descargue
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="reporte_productos.xlsx"');
    header('Cache-Control: max-age=0');

    // Escribe el archivo Excel en la salida
    $writer->save('php://output');
} else {
    echo 'No se encontraron productos.';
}
?>

