<?php
include '../../config.php'; // Incluye el archivo de conexión

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_tipo_usuario = $_POST['id_tipousuario']; // Verifica que el ID sea válido
    if (!empty($id_tipo_usuario)) {
        try {
            // Intenta eliminar el registro
            $stmt = $pdo->prepare("DELETE FROM tipo_usuario WHERE ID_tipousuario = ?");
            $stmt->execute([$id_tipo_usuario]);

            // Redirecciona con mensaje de éxito si la eliminación es exitosa
            header("Location: " . $URL . "/roles/index.php?mensaje=Registro eliminado correctamente");
            exit();
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') {
                echo "<script>
                        alert('No se puede eliminar el tipo de usuario porque está asignado a un usuario. Primero reasigna o edita el usuario.');
                        setTimeout(function() {
                            window.location.href = '" . $URL . "/roles/index.php';
                        }, 50);
                      </script>
                      <style>
                          .alert {
                              background-color: #ffcccc !important;
                              color: #333 !important;
                              padding: 10px !important;
                              border: 1px solid #ffcccc !important;
                              margin-bottom: 10px !important;
                          }
                      </style>";

                exit();
            } else {
                // Manejo de otros posibles errores
                echo "Error inesperado: " . $e->getMessage();
            }
        }
    } else {
        echo "ID de tipo de usuario no válido.";
    }
} else {
    // En caso de que no sea un POST, redirige de nuevo al listado
    header("Location: " . $URL . "/roles/index.php?error=Acción no permitida.");
    exit();
}


