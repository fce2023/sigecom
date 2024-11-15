<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cargando...</title>
    <style>
        /* Estilo básico para la pantalla de carga */
        body {
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #333;
            font-family: Arial, sans-serif;
        }

        .loader {
            display: inline-block;
            width: 64px;
            height: 64px;
            border: 8px solid #fff;
            border-top: 8px solid #3498db;
            border-radius: 50%;
            animation: spin 2s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .text {
            font-size: 24px;
            color: #fff;
            margin-top: 20px;
        }
    </style>
</head>
<body>

    <div class="loader"></div>
    <div class="text">Cargando login...</div>

    <script>
        // Redirigir a la página de login después de 3 segundos
        setTimeout(function() {
            window.location.href = "login"; // Asegúrate de que esta ruta sea la correcta
        }, 2000);
    </script>

</body>
</html>

