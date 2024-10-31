<?php

include('../../config.php');

// Se obtienen los datos del formulario
$nombre_usuario = $_POST['usuario'];
$contrasena = $_POST['clave'];

// Se verifica si el usuario existe en la base de datos
$sql = "SELECT * FROM usuario WHERE Nombre_usuario = :nombre_usuario AND Contraseña = :contrasena LIMIT 1";
$query = $pdo->prepare($sql);
$query->execute([
    ':nombre_usuario' => $nombre_usuario,
    ':contrasena' => $contrasena
]);

$usuario = $query->fetch(PDO::FETCH_ASSOC);

// Se verifica si los datos ingresados son correctos
if ($usuario) {
    // Si los datos son correctos, se inicia la sesión y se redirige al index
    session_start();
    $_SESSION['sesion_usuario'] = $usuario['Nombre_usuario'];
    header('Location: '.$URL.'/index.php');
    exit();
} else {
    // Si los datos son incorrectos, se muestra un mensaje de error y se redirige al login
    echo "<script>
    alert('Error: datos incorrectos');
    window.location.href='".$URL."/login';
    </script>";
    exit();
}
?>

