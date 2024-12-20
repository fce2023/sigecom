<?php
require_once '../app/config.php';
require_once '../layout/sesion.php';
require_once 'consulta_detalles_tecnico_cliente.php';
require_once('../vendor/autoload.php');


use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Suponemos que las variables $clientes, $nombredeltecnico, y $detalleClienteTecnico ya están definidas en tu código

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Títulos y cabecera del reporte
$sheet->setCellValue('A1', 'Reporte de Atenciones por Técnico ' . $nombredeltecnico);
$sheet->setCellValue('A2', 'Fecha de emisión: ' . date('d/m/Y H:i'));
$sheet->mergeCells('A2:E2');

// Cabecera de la tabla
$sheet->setCellValue('A4', '#');
$sheet->setCellValue('B4', 'Cliente');
$sheet->setCellValue('C4', 'Servicio');
$sheet->setCellValue('D4', 'Fecha');
$sheet->setCellValue('E4', 'Estado');

// Contador para las filas de datos
$contador = 5;

// Recorre los datos de los clientes y genera las filas del reporte
foreach ($clientes as $index => $cliente) {
    // Número de la fila
    $sheet->setCellValue('A' . $contador, $contador - 4);
    
    // Información del cliente
    $sheet->setCellValue('B' . $contador, $cliente['Nombre'] . ' ' . $cliente['Dni']);
    
    // Nombre del servicio (suponiendo que todos los servicios son iguales o definidos previamente)
    $sheet->setCellValue('C' . $contador, $nombreServicio ?? 'Servicio no disponible');
    
    // Fecha de la atención
    $sheet->setCellValue('D' . $contador, date('d/m/Y H:i', strtotime($detalleClienteTecnico[$index]['Fecha_atencion'] ?? '')));
    
    // Estado de la atención
    $estado = $detalleClienteTecnico[$index]['Estado'] == 0 ? 'Emitido' : ($detalleClienteTecnico[$index]['Estado'] == 1 ? 'Aceptado' : 'Rechazado');
    $sheet->setCellValue('E' . $contador, $estado);

    $contador++; // Incrementa el contador para la siguiente fila
}
ob_end_clean();

// Configuración para la descarga del archivo Excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="reporte_atenciones_tecnico_' . $nombredeltecnico . '.xlsx"');
header('Cache-Control: max-age=0');

// Escribir el archivo Excel directamente al navegador
$writer = new Xlsx($spreadsheet);
$writer->save('php://output'); // Esto enviará el archivo directamente al navegador

?>