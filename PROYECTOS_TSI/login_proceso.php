<?php
session_start();
include 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Buscar el usuario en la tabla `usuario`
    $sql = "SELECT * FROM usuario WHERE Nombre_usuario = ? AND Estado = 'activo'";
    $stmt = $cn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // Validar el usuario y la contraseña
    if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();

    // Verificar la contraseña
    if ($password === $user['Contraseña']) { 
        $_SESSION['username'] = $username;
        header("Location: dashboard.php"); // Redirige al panel principal
        exit();
    } else {
        header("Location: login.php?error=Contraseña incorrecta&username=" . urlencode($username));
        exit();
    }
} else {
    header("Location: login.php?error=Usuario no existe&username=" . urlencode($username));
    exit();
}

    $stmt->close();
    $cn->close();
}
?>



