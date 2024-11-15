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

// Obtener todos los usuarios desde la base de datos
$query = "SELECT u.ID_usuario, u.Nombre_usuario, u.Correo, u.Contraseña, u.ID_tipousuario, u.id_personal, u.Estado, p.Dni, p.Nombre, p.Apellido_paterno, p.Apellido_materno, p.Celular, p.Direccion, c.Nom_cargo
          FROM usuario u
          INNER JOIN personal p ON u.id_personal = p.ID_personal
          INNER JOIN cargo c ON p.ID_cargo = c.ID_cargo";
$stmt = $pdo->prepare($query);
$stmt->execute();
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Verifica si la variable $usuarios está definida y contiene datos
if (!empty($usuarios)) {
    // Crear un objeto Spreadsheet
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Títulos de las columnas
    $sheet->setCellValue('A1', 'ID');
    $sheet->setCellValue('B1', 'DNI');
    $sheet->setCellValue('C1', 'Nombre Personal');
    $sheet->setCellValue('D1', 'Apellido Paterno');
    $sheet->setCellValue('E1', 'Apellido Materno');
    $sheet->setCellValue('F1', 'Celular');
    $sheet->setCellValue('G1', 'Dirección');
    $sheet->setCellValue('H1', 'Nombre Usuario');
    $sheet->setCellValue('I1', 'Correo');
    $sheet->setCellValue('J1', 'Contraseña');
    $sheet->setCellValue('K1', 'ID Tipo Usuario');
    $sheet->setCellValue('L1', 'Estado');

    // Itera sobre el array de usuarios y agrega cada uno a la hoja
    $row = 2; // Comenzamos a escribir desde la segunda fila
    foreach ($usuarios as $usuario) {
        $sheet->setCellValue('A' . $row, $usuario['ID_usuario']);
        $sheet->setCellValue('B' . $row, $usuario['Dni']);
        $sheet->setCellValue('C' . $row, $usuario['Nombre']);
        $sheet->setCellValue('D' . $row, $usuario['Apellido_paterno']);
        $sheet->setCellValue('E' . $row, $usuario['Apellido_materno']);
        $sheet->setCellValue('F' . $row, $usuario['Celular']);
        $sheet->setCellValue('G' . $row, $usuario['Direccion']);
        $sheet->setCellValue('H' . $row, $usuario['Nombre_usuario']);
        $sheet->setCellValue('I' . $row, $usuario['Correo']);
        $sheet->setCellValue('J' . $row, $usuario['Contraseña']);
        $sheet->setCellValue('K' . $row, $usuario['ID_tipousuario']);
        $estado = $usuario['Estado'] == 1 ? 'Activo' : 'Inactivo';
        $sheet->setCellValue('L' . $row, $estado);
        $row++; // Incrementa la fila
    }

    // Establece un pie de página o una nota en la parte inferior
    $sheet->setCellValue('A' . $row, 'Fecha de la consulta: ' . date('d/m/Y H:i:s'));

    // Configura el escritor para guardar el archivo Excel
    $writer = new Xlsx($spreadsheet);

    // Envía el archivo Excel al navegador para que el usuario lo descargue
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="reporte_usuarios.xlsx"');
    header('Cache-Control: max-age=0');

    // Escribe el archivo Excel en la salida
    $writer->save('php://output');
} else {
    echo 'No se encontraron usuarios.';
}
?>

