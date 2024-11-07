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
$query = "SELECT * FROM usuario";
$stmt = $pdo->prepare($query);
$stmt->execute();
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Verifica si la variable $usuarios está definida y contiene datos
if (!empty($usuarios)) {
    // Crear un objeto Spreadsheet
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Títulos de las columnas
    $contador = 1;
    $sheet->setCellValue('A1', $contador);
    $sheet->setCellValue('B1', 'ID Personal');
    $sheet->setCellValue('C1', 'Nombre Usuario');
    $sheet->setCellValue('D1', 'Correo');
    $sheet->setCellValue('E1', 'Contraseña');
    $sheet->setCellValue('F1', 'ID Tipo Usuario');
    $sheet->setCellValue('G1', 'Estado');

    // Itera sobre el array de usuarios y agrega cada uno a la hoja
    $row = 2; // Comenzamos a escribir desde la segunda fila
    foreach ($usuarios as $usuario) {
        $sheet->setCellValue('A' . $row, $usuario['ID_usuario']);
        $query = "SELECT Nombre, Apellido FROM personal WHERE ID_personal = " . $usuario['id_personal'];
        $personalStmt = $pdo->query($query);
        $nombreCompleto = 'No disponible';
        if ($personalStmt) {
            $personalData = $personalStmt->fetch(PDO::FETCH_ASSOC);
            if ($personalData) {
                $nombreCompleto = $personalData['Nombre'] . ' ' . $personalData['Apellido'];
            }
        }
        $sheet->setCellValue('B' . $row, $nombreCompleto);
        $sheet->setCellValue('C' . $row, $usuario['Nombre_usuario']);
        $sheet->setCellValue('D' . $row, isset($usuario['Correo']) ? $usuario['Correo'] : 'No disponible');
        $sheet->setCellValue('E' . $row, $usuario['Contraseña']);
        $query2 = "SELECT Nombre_tipousuario FROM tipo_usuario WHERE ID_tipousuario = " . $usuario['ID_tipousuario'];
        $stmt2 = $pdo->prepare($query2);
        $stmt2->execute();
        $tipo_usuario = $stmt2->fetch(PDO::FETCH_ASSOC);
        $sheet->setCellValue('F' . $row, isset($tipo_usuario['Nombre_tipousuario']) ? $tipo_usuario['Nombre_tipousuario'] : 'No disponible');
        $estado = isset($usuario['Estado']) && $usuario['Estado'] == 1 ? 'Activo' : 'Inactivo';
        $sheet->setCellValue('G' . $row, $estado);
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

