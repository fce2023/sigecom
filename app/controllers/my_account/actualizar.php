<?php
// Iniciar sesión
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['sesion_usuario'])) {
    die('Acción no permitida');
}

// Incluir archivo de conexión
require_once('../../config.php');

// Variables para los mensajes de notificación
$mensaje = '';

// Obtener los datos del formulario
$usuario = $_POST['usuario-up'];
$email = $_POST['email-up'];
$password = $_POST['password-up'];
$newPassword1 = $_POST['newPassword1-up'];
$newPassword2 = $_POST['newPassword2-up'];

// Verificar que los datos no estén vacíos
if (empty($usuario) || empty($email) || empty($password)) {
    $mensaje = 'Todos los campos son requeridos';
    $_SESSION['mensaje'] = $mensaje;
    header('Location: ' . $URL . '/my_account/index.php'); // Redirigir al formulario
    exit();
}

// Verificar que las contraseñas coincidan
if ($newPassword1 !== $newPassword2) {
    $mensaje = 'Las contraseñas no coinciden';
    $_SESSION['mensaje'] = $mensaje;
    header('Location: ' . $URL . '/my_account/index.php'); // Redirigir al formulario
    exit();
}

// Verificar la contraseña actual
$consulta = "SELECT * FROM usuario WHERE Nombre_usuario = :nombre_usuario";
$stmt = $pdo->prepare($consulta);
$stmt->bindParam(':nombre_usuario', $usuario, PDO::PARAM_STR);
$stmt->execute();
$usuario_data = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$usuario_data) {
    $mensaje = 'Usuario no encontrado';
    $_SESSION['mensaje'] = $mensaje;
    header('Location: ' . $URL . '/my_account/index.php'); // Redirigir al formulario
    exit();
}

if ($password != $usuario_data['password']) {
    $mensaje = 'Contraseña actual incorrecta';
    $_SESSION['mensaje'] = $mensaje;
    header('Location: ' . $URL . '/my_account/index.php'); // Redirigir al formulario
    exit();
}

// Si se proporciona una nueva contraseña, guardarla como texto plano
if (!empty($newPassword1)) {
    $newPassword = $newPassword1;
} else {
    $newPassword = $password; // Mantener la contraseña actual si no se proporciona una nueva
}

// Actualizar los datos en la base de datos
try {
    $updateQuery = "UPDATE usuario 
                    SET Nombre_usuario = :usuario, Correo = :email ";
    $updateQuery .= !empty($newPassword1) ? ", password = :password " : "";
    $updateQuery .= " WHERE Nombre_usuario = :usuario";  // Modifiqué el WHERE a Nombre_usuario para coincidir con la consulta anterior

    $stmt = $pdo->prepare($updateQuery);
    
    $stmt->bindParam(':usuario', $usuario);
    $stmt->bindParam(':email', $email);
    if (!empty($newPassword1)) {
        $stmt->bindParam(':password', $newPassword);
    }

    $stmt->execute();

    // Verificar si la actualización fue exitosa
    if ($stmt->rowCount() > 0) {
        // Mensaje de éxito
        $mensaje = 'Datos actualizados con éxito';
    } else {
        // Mensaje de no cambios
        $mensaje = 'No se realizaron cambios';
    }

    $_SESSION['mensaje'] = $mensaje;
    header("Location: " . $URL . "/my_account/index.php"); // Redirigir al formulario

} catch (PDOException $e) {
    // Mostrar el error si algo falla
    $mensaje = 'Error al actualizar datos: ' . $e->getMessage();
    $_SESSION['mensaje'] = $mensaje;
    header('Location: ' . $URL . '/my_account/index.php'); // Redirigir al formulario
}
?>
