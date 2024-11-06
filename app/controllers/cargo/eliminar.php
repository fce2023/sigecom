<?php
include '../../config.php'; // Incluye el archivo de conexión

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_cargo = $_POST['id_cargo']; // Verifica que el ID sea válido
    if (!empty($id_cargo)) {
        try {
            // Intenta eliminar el registro
            $stmt = $pdo->prepare("DELETE FROM cargo WHERE ID_cargo = ?");
            $stmt->execute([$id_cargo]);

            // Redirecciona con mensaje de éxito si la eliminación es exitosa
            header("Location: " . $URL . "/cargo/index.php?mensaje=Registro eliminado correctamente");
            exit();
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') {
                echo "<script>
                        alert('No se puede eliminar el cargo porque está asignado a personal. Primero reasigna o edita el cargo en personal.');
                        setTimeout(function() {
                            window.location.href = '" . $URL . "/cargo/index.php';
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
        echo "ID de cargo no válido.";
    }
} else {
    // En caso de que no sea un POST, redirige de nuevo al listado
    header("Location: " . $URL . "/cargo/index.php?error=Acción no permitida.");
    exit();
}
