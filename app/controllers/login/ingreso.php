<?php

include('../../config.php');

// Se obtienen los datos del formulario
$nombre_usuario = $_POST['username'];
$password = $_POST['password'];

// Se verifica si el usuario existe en la base de datos
$sql = "SELECT * FROM usuario WHERE Nombre_usuario = :nombre_usuario LIMIT 1";
$stmt = $pdo->prepare($sql);
$stmt->execute([
    ':nombre_usuario' => $nombre_usuario
]);

// Se verifica si el usuario existe
if ($stmt->rowCount() === 1) {
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verificar la contraseña
    if ($password === $user['password']) {
        // Si los datos son correctos, se inicia la sesión y se redirige al index
        session_start();
        $_SESSION['sesion_usuario'] = $user['Nombre_usuario'];
        header('Location: '.$URL.'/home.php');
        exit();
    } else {
        // Si la contraseña es incorrecta, se muestra un mensaje de error y se redirige al login
        header("Location: " . $URL . "/login/index.php?error=Contraseña incorrecta&username=" . urlencode($nombre_usuario));
        exit();
    }
} else {
    // Si el usuario no existe, se muestra un mensaje de error y se redirige al login
    header("Location: " . $URL . "login/index.php?error=Usuario no existe&username=" . urlencode($nombre_usuario));
    exit();
}

$stmt->close();
$pdo->close();

