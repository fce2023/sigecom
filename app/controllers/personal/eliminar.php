<?php
include '../../config.php'; // Incluye el archivo de conexión

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_personal = $_POST['id_personal']; // Verifica que el ID sea válido
    if (!empty($id_personal)) {
        try {
            // Intenta eliminar el registro
            $stmt = $pdo->prepare("DELETE FROM personal WHERE ID_personal = ?");
            $stmt->execute([$id_personal]);

            // Redirecciona con mensaje de éxito si la eliminación es exitosa
            header("Location: " . $URL . "/personal/index.php?mensaje=Registro eliminado correctamente");
            exit();
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') {
                // Si ocurre una violación de restricción de integridad, redirige con mensaje de error
                echo "<script>
                        alert('No se puede eliminar el personal porque tiene un usuario de inicio de sesión asignado. Primero elimine el usuario de inicio de sesión y luego el personal.');
                        setTimeout(function() {
                            window.location.href = '" . $URL . "/personal/index.php';
                        }, 10);
                      </script>";
                exit();
            } else {
                // Manejo de otros posibles errores
                echo "Error inesperado: " . $e->getMessage();
            }
        }
    } else {
        echo "ID de personal no válido.";
    }
} else {
    // En caso de que no sea un POST, redirige de nuevo al listado
    header("Location: " . $URL . "/personal/index.php?error=Acción no permitida.");
    exit();
}

