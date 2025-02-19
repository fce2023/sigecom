<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/login.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>Iniciar Sesión</title>
</head>
<body>
    <div class="login-wrapper">
        <div class="logo-container">
            <img src="../img/señales_logo.png" alt="Logo de la Empresa" class="logo">
        </div>
        <div class="login-container">
            <h2>Iniciar Sesión</h2>
            
            <!-- Mostrar el mensaje de error si existe -->
            <?php if (isset($_GET['error'])): ?>
                <p style="color: red;"><?php echo htmlspecialchars($_GET['error']); ?></p>
            <?php endif; ?>
            
            <form action="../app/controllers/login/ingreso.php" method="POST" autocomplete="off">
                <!-- Mantener el nombre de usuario si existe en la URL -->
                <input type="text" name="username" placeholder="Usuario" 
                       value="<?php echo isset($_GET['username']) ? htmlspecialchars($_GET['username']) : ''; ?>" required autocomplete="off">
                
                <div class="password-container">
                    <input type="password" name="password" id="password" placeholder="password" required autocomplete="off">
                    <span id="togglePassword" onclick="togglePasswordVisibility()" autocomplete="off">
                        <i class="fas fa-eye-slash"></i> <!-- Ícono de ojo para mostrar/ocultar contraseña -->
                    </span>
                </div>
                <button type="submit" autocomplete="off">Iniciar sesión</button>
                <p class="forgot-password"><a href="#" autocomplete="off">¿Olvidaste tu contraseña?</a></p>
            </form>
        </div>
    </div>

    <script>
        function togglePasswordVisibility() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('togglePassword').querySelector('i');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye'); // Cambiar a ícono de "ojo tachado"
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash'); // Cambiar a ícono de "ojo"
            }
        }
    </script>
</body>
</html>

