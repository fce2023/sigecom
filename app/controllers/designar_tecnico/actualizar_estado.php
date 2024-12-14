<?php
include('../../config.php'); // Incluye la configuración de la base de datos

// Verificar si se recibió la solicitud POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los datos de la solicitud
    $id = isset($_POST['id']) ? $_POST['id'] : null;
    $estado = isset($_POST['estado']) ? $_POST['estado'] : null;

    
    // Validar que ambos parámetros estén presentes
    if ($id && $estado !== null) {
        try {
            // Preparar la consulta para actualizar el estado
            $query = "UPDATE detalle_cliente_tecnico SET Estado = :estado WHERE Id_det_cliente_tecnico = :id";
            $stmt = $pdo->prepare($query);

            // Vincular los parámetros
            $stmt->bindParam(':estado', $estado, PDO::PARAM_INT);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            // Ejecutar la consulta
            if ($stmt->execute()) {
                // Responder con éxito
                echo json_encode([
                    'success' => true,
                    'message' => 'Estado actualizado correctamente.'
                ]);
            } else {
                // Responder con error si la consulta no se ejecutó
                echo json_encode([
                    'success' => false,
                    'message' => 'Error al actualizar el estado.'
                ]);
            }
        } catch (PDOException $e) {
            // Manejo de excepciones
            echo json_encode([
                'success' => false,
                'message' => 'Error en la base de datos: ' . $e->getMessage()
            ]);
        }
    } else {
        // Si falta algún parámetro, responder con error
        echo json_encode([
            'success' => false,
            'message' => 'Faltan parámetros.'
        ]);
    }
} else {
    // Si no es una solicitud POST, responder con error
    echo json_encode([
        'success' => false,
        'message' => 'Solicitud inválida.'
    ]);
}
?>
