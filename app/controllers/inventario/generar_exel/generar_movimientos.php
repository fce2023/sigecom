<?php
require '../../../../vendor/autoload.php'; // Asegúrate de que PhpSpreadsheet esté cargado

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

include '../../../config.php'; // Asegúrate de que la configuración de la base de datos esté correcta

if (!isset($pdo) || !$pdo instanceof PDO) {
    die("No se pudo conectar a la base de datos.");
}

$query = "SELECT dpp.Id_det_producto_proveedor, 'Entrada' AS tipo, pr.Nombre AS proveedor, p.Nombre AS producto, dpp.Fecha_abastecimiento, dpp.cantidad, dpp.Observación, dpp.Estado AS estado_entrada
          FROM detalle_producto_proveedor dpp
          LEFT JOIN proveedor pr ON dpp.ID_proveedor = pr.ID_proveedor
          LEFT JOIN productos p ON dpp.ID_producto = p.id_producto
          WHERE dpp.Estado = 1
          UNION
          SELECT dtp.Id_det_tecnico_producto, 'Salida' AS tipo, t.Nombre AS tecnico, p.Nombre AS producto, dtp.Fecha_retiro, dtp.cantidad AS cantidad_salida, dtp.Observación AS observacion_salida, dtp.Estado AS estado_salida
          FROM detalle_tecnico_producto dtp
          LEFT JOIN tecnico t ON dtp.ID_tecnico = t.ID_tecnico
          LEFT JOIN productos p ON dtp.ID_producto = p.id_producto
          WHERE dtp.Estado = 1";
$stmt = $pdo->prepare($query);
$stmt->execute();
$movimientos = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!empty($movimientos)) {
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    $sheet->setCellValue('A1', 'N°');
    $sheet->setCellValue('B1', 'TIPO');
    $sheet->setCellValue('C1', 'NOMBRE PRODUCTO');
    $sheet->setCellValue('D1', 'NOMBRE PROVEEDOR');
    $sheet->setCellValue('E1', 'FECHA');
    $sheet->setCellValue('F1', 'CANTIDAD');
    $sheet->setCellValue('G1', 'OBSERVACIÓN');
    $sheet->setCellValue('H1', 'ESTADO');

    $row = 2;
    foreach ($movimientos as $key => $movimiento) {
        $sheet->setCellValue('A' . $row, $key + 1);
        $sheet->setCellValue('B' . $row, isset($movimiento['tipo']) ? $movimiento['tipo'] : 'No disponible');
        $sheet->setCellValue('C' . $row, isset($movimiento['producto']) ? $movimiento['producto'] : 'No disponible');
        $sheet->setCellValue('D' . $row, isset($movimiento['proveedor']) ? $movimiento['proveedor'] : 'No disponible');
        $sheet->setCellValue('E' . $row, isset($movimiento['Fecha_abastecimiento']) ? $movimiento['Fecha_abastecimiento'] : 'No disponible');
        $sheet->setCellValue('F' . $row, isset($movimiento['cantidad']) ? $movimiento['cantidad'] : 'No disponible');
        $sheet->setCellValue('G' . $row, isset($movimiento['Observación']) ? $movimiento['Observación'] : 'No disponible');
        $sheet->setCellValue('H' . $row, isset($movimiento['estado_entrada']) && $movimiento['estado_entrada'] == 1 ? 'Activo' : 'Inactivo');
        $row++;
    }

    $sheet->setCellValue('A' . $row, 'Fecha de la consulta: ' . date('d/m/Y H:i:s'));

    $writer = new Xlsx($spreadsheet);
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="reporte_movimientos.xlsx"');
    header('Cache-Control: max-age=0');
    $writer->save('php://output');
} else {
    echo 'No se encontraron movimientos.';
}
?>

