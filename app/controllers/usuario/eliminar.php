<?php
include '../../config.php'; // Incluye el archivo de conexión

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_usuario = $_POST['id_usuario']; // Verifica que el ID sea válido
    if (!empty($id_usuario)) {
        try {
            // Intenta eliminar el registro
            $stmt = $pdo->prepare("DELETE FROM usuario WHERE ID_usuario = ?");
            $stmt->execute([$id_usuario]);

            // Redirecciona con mensaje de éxito si la eliminación es exitosa
            header("Location: " . $URL . "/usuario/index.php?mensaje=Registro eliminado correctamente");
            exit();
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') {
                // Si ocurre una violación de restricción de integridad, redirige con mensaje de error
                echo "<script>
                        alert('No se puede eliminar el usuario porque está asignado a un recurso. Primero reasigna o edita el recurso.');
                        setTimeout(function() {
                            window.location.href = '" . $URL . "/usuario/index.php';
                        }, 10);
                      </script>";
                exit();
            } else {
                // Manejo de otros posibles errores
                echo "Error inesperado: " . $e->getMessage();
            }
        }
    } else {
        echo "ID de usuario no válido.";
    }
} else {
    // En caso de que no sea un POST, redirige de nuevo al listado
    header("Location: " . $URL . "/usuario/index.php?error=Acción no permitida.");
    exit();
}

