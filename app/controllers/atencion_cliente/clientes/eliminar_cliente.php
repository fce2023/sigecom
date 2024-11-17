<?php
include '../../../config.php'; // Incluye el archivo de conexión
include '../../../../layout/sesion.php'; // Incluye el archivo de conexión

header('Content-Type: application/json');

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);
        $id_cliente = htmlspecialchars($data['ID_cliente']); // Verifica que el ID sea válido

        if (empty($id_cliente)) {
            throw new Exception('El ID del cliente es obligatorio.');
        }

        $stmt = $pdo->prepare("DELETE FROM cliente WHERE ID_cliente = :id_cliente");
        $stmt->execute(['id_cliente' => $id_cliente]);

        echo json_encode(['success' => true, 'message' => 'Cliente eliminado correctamente.']);
    } else {
        throw new Exception('Método de solicitud no válido.');
    }
} catch (Exception $e) {
    error_log("Error en eliminar_cliente.php: " . $e->getMessage(), 3, __DIR__ . '/error_log.txt');
    echo json_encode(['success' => false, 'error' => 'Error interno del servidor. ' . $e->getMessage()]);
}

