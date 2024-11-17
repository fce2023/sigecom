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

// Obtener todos los detalles de técnicos y productos junto con el nombre de usuario desde la base de datos
$query = "SELECT dtp.Id_det_tecnico_producto, CONCAT(p.Nombre, ' ', p.Apellido_paterno, ' ', p.Apellido_materno) AS tecnico, u.Nombre_usuario AS usuario_registro, prod.Nombre AS producto, dtp.Fecha_retiro, dtp.cantidad, dtp.Observación, dtp.Estado 
FROM detalle_tecnico_producto dtp 
LEFT JOIN tecnico t ON dtp.ID_tecnico = t.ID_tecnico 
LEFT JOIN personal p ON t.id_personal = p.ID_personal
LEFT JOIN productos prod ON dtp.ID_producto = prod.id_producto
LEFT JOIN usuario u ON dtp.ID_usuario = u.ID_usuario";
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
    $sheet->setCellValue('B1', 'Tecnico');
    $sheet->setCellValue('C1', 'Usuario que registró');
    $sheet->setCellValue('D1', 'Producto');
    $sheet->setCellValue('E1', 'Cantidad');
    $sheet->setCellValue('F1', 'Fecha de retiro');
    $sheet->setCellValue('G1', 'Observación');
    $sheet->setCellValue('H1', 'Estado');

    // Itera sobre el array de productos y agrega cada uno a la hoja
    $row = 2; // Comenzamos a escribir desde la segunda fila
    foreach ($productos as $producto) {
        $sheet->setCellValue('A' . $row, $producto['Id_det_tecnico_producto']);
        $sheet->setCellValue('B' . $row, $producto['tecnico']);
        $sheet->setCellValue('C' . $row, $producto['usuario_registro']);
        $sheet->setCellValue('D' . $row, $producto['producto']);
        $sheet->setCellValue('E' . $row, $producto['cantidad']);
        $sheet->setCellValue('F' . $row, $producto['Fecha_retiro']);
        $sheet->setCellValue('G' . $row, $producto['Observación']);

        // Estado (Activo/Inactivo)
        $estado = $producto['Estado'] == 1 ? 'Activo' : 'Inactivo';
        $sheet->setCellValue('H' . $row, $estado);

        $row++; // Incrementa la fila
    }

    // Establece un pie de página o una nota en la parte inferior
    $sheet->setCellValue('A' . $row, 'Fecha de la consulta: ' . date('d/m/Y H:i:s'));

    // Configura el escritor para guardar el archivo Excel
    $writer = new Xlsx($spreadsheet);

    // Envía el archivo Excel al navegador para que el usuario lo descargue
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="reporte_salida.xlsx"');
    header('Cache-Control: max-age=0');

    // Escribe el archivo Excel en la salida
    $writer->save('php://output');
} else {
    echo 'No se encontraron productos.';
}

