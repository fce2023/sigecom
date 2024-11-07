<?php
include '../../config.php'; // Incluye el archivo de conexión
include '../../../layout/sesion.php'; // Incluye el archivo de conexión
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_usuario = $_POST['id_usuario']; // Obtiene el ID de usuario a eliminar
    $nombre_usuario_sesion = $_SESSION['sesion_usuario']; // Nombre de usuario en sesión

    if (!empty($id_usuario) && isset($pdo)) {
        try {
            // Consulta para obtener el nombre de usuario del ID a eliminar
            $stmt = $pdo->prepare("SELECT Nombre_usuario FROM usuario WHERE ID_usuario = ?");
            $stmt->execute([$id_usuario]);
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($usuario) {
                // Verifica si el nombre de usuario en sesión coincide con el de la base de datos
                if ($usuario['Nombre_usuario'] === $nombre_usuario_sesion) {
                    $mensaje = "Tienes la sesión iniciada con este usuario, inicia con otro para eliminar.";
                    $tipo_mensaje = "error";

                    // Redirigir a la página de inicio después de mostrar la notificación
                    echo "<script>
                            setTimeout(function(){
                                window.location.href = '" . $URL . "/usuario/index.php?error=Acción no permitida.';
                            }, 3000);
                        </script>";
                } else {
                    // Prepara la consulta para eliminar el registro
                    $stmt = $pdo->prepare("DELETE FROM usuario WHERE ID_usuario = ?");
                    $stmt->execute([$id_usuario]);

                    // Mensaje de éxito
                    $mensaje = "El usuario fue eliminado exitosamente.";
                    $tipo_mensaje = "success";
                }
            } else {
                $mensaje = "Usuario no encontrado.";
                $tipo_mensaje = "error";
            }
        } catch (PDOException $e) {
            $mensaje = "Error al eliminar el usuario: " . $e->getMessage();
            $tipo_mensaje = "error";
        }
    } else {
        $mensaje = "ID de usuario no válido o error de conexión.";
        $tipo_mensaje = "error";
    }
} else {
    header("Location: " . $URL . "/usuario/index.php?error=Acción no permitida.");
    exit();
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
                window.location.href = "<?= $URL ?>/usuario";
            }
        }, 2000); // 5 segundos para que desaparezca
    </script>
</body>
</html>
