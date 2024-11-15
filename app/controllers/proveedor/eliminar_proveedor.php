<?php
include '../../config.php'; // Incluye el archivo de conexión
include '../../../layout/sesion.php'; // Incluye el archivo de conexión

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_proveedor = $_POST['id_proveedor']; // Verifica que el ID sea válido
    if (!empty($id_proveedor)) {
        try {
            // Intenta eliminar el registro
            $stmt = $pdo->prepare("DELETE FROM proveedor WHERE ID_proveedor = ?");
            $stmt->execute([$id_proveedor]);

            // Redirige con mensaje de éxito si la eliminación es exitosa
            $mensaje = "Registro eliminado correctamente.";
            $tipo_mensaje = "success";
        } catch (PDOException $e) {
            // Verifica si el error es una violación de restricción de integridad
            if ($e->getCode() === '23000') {
                try {
                    // Si ocurre una violación de restricción, consulta el nombre de proveedor relacionado
                    $stmt = $pdo->prepare("SELECT proveedor.Nombre FROM proveedor WHERE proveedor.ID_proveedor = ?");
                    $stmt->execute([$id_proveedor]);
                    $proveedor = $stmt->fetch(PDO::FETCH_ASSOC);

                    if ($proveedor) {
                        // Redirige con un mensaje de error si el proveedor está relacionado con el registro
                        $mensaje = "No se puede eliminar el proveedor porque el proveedor '" . $proveedor['Nombre'] . "' está relacionado con este registro. Primero elimine el proveedor y luego el registro.";
                        $tipo_mensaje = "error";
                        
                    } else {
                        echo "No se encontró el proveedor relacionado.";
                    }
                } catch (PDOException $e) {
                    $mensaje = "Error al consultar el proveedor relacionado: " . $e->getMessage();
                    $tipo_mensaje = "error";
                }
            } else {
                // Manejo de otros posibles errores
                $mensaje = "Error inesperado: " . $e->getMessage();
                $tipo_mensaje = "error";
            }
        }
    } else {
        $mensaje = "ID de proveedor no válido.";
        $tipo_mensaje = "error";
    }
} else {
    $mensaje = "Acción no permitida.";
    $tipo_mensaje = "error";
}
?>
<!-- Agregar el script y CSS local para notificación -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notificación</title>
    <style>
        /* Estilos para las notificaciones */
        .notification {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            padding: 15px;
            background-color: #4CAF50;
            color: white;
            border-radius: 5px;
            font-size: 16px;
            z-index: 1000;
            opacity: 1;
            transition: opacity 0.5s ease;
        }

        .notification.error {
            background-color: #f44336;
        }

        .notification.success {
            background-color: #4CAF50;
        }

        .notification.hide {
            opacity: 0;
        }
    </style>
</head>
<body>
    <?php if (isset($mensaje)): ?>
        <div id="notification" class="notification <?= $tipo_mensaje ?>">
            <?= $mensaje ?>
        </div>
    <?php endif; ?>
    <script>
        // Script para ocultar la notificación después de unos segundos
        setTimeout(function() {
            var notification = document.getElementById('notification');
            if (notification && (notification.classList.contains('success') || notification.classList.contains('error'))) {
                notification.classList.add('hide');
                window.location.href = "<?= $URL ?>/proveedor";
            }
        }, 2000); // 5 segundos para que desaparezca
    </script>
</body>
</html>

