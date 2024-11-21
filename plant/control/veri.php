<?php
date_default_timezone_set('America/Lima');

// Verificar si el archivo de conexión existe y es legible
// Verificar si hay conexión a internet
$url = 'https://www.google.com';
if (file_get_contents($url)) {
    


try {
    include 'conexion.php';
    // Definir la clave que usará el programa
    $clave_programa = "ABC123"; // Clave asignada a tu programa
    $clave_cifrado = hash('sha256', $clave_programa); // Derivar una clave segura

    // Consultar la base de datos para verificar la licencia
    $query = "SELECT * FROM licencias 
              WHERE clave = :clave 
              AND estado = 1 
              AND fecha_inicio <= NOW() 
              AND (fecha_fin >= NOW() OR fecha_fin IS NULL)";
    $stmt = $conexion->prepare($query);
    $stmt->bindParam(':clave', $clave_programa);
    $stmt->execute();
    $licencia = $stmt->fetch(PDO::FETCH_ASSOC);

    $ruta_archivo = "app/config.php";
    $ruta_iv = "app/config_iv.bin"; // Ruta para almacenar el IV
    $ruta_archivo_cons = "login/index.php";
    $ruta_iv_cons = "login/index_iv.bin"; // Ruta para almacenar el IV

    if ($licencia) {
        // Licencia válida

        // Verificar que el archivo y el IV existan y que no estén desencriptados
        if (file_exists($ruta_archivo) && file_exists($ruta_iv) && file_exists($ruta_archivo_cons) && file_exists($ruta_iv_cons)) {
            $contenido_archivo = file_get_contents($ruta_archivo);
            $iv = file_get_contents($ruta_iv);
            $contenido_archivo_cons = file_get_contents($ruta_archivo_cons);
            $iv_cons = file_get_contents($ruta_iv_cons);

            if ($iv === false || strlen($iv) !== openssl_cipher_iv_length('AES-256-CBC') ||
                $iv_cons === false || strlen($iv_cons) !== openssl_cipher_iv_length('AES-256-CBC')) {
                throw new Exception("El IV es inválido o no tiene la longitud correcta.");
            }

            // Verificar si ya está desencriptado
            if (strpos($contenido_archivo, '<?php') === false && strpos($contenido_archivo_cons, '<?php') === false) {
                // Desencriptar archivo config
                $contenido_desencriptado = openssl_decrypt($contenido_archivo, 'AES-256-CBC', $clave_cifrado, 0, $iv);
                if ($contenido_desencriptado === false) {
                    throw new Exception("Error al desencriptar el archivo.");
                }
                file_put_contents($ruta_archivo, $contenido_desencriptado);

                // Desencriptar archivo cons/index
                $contenido_desencriptado_cons = openssl_decrypt($contenido_archivo_cons, 'AES-256-CBC', $clave_cifrado, 0, $iv_cons);
                if ($contenido_desencriptado_cons === false) {
                    throw new Exception("Error al desencriptar el archivo.");
                }
                file_put_contents($ruta_archivo_cons, $contenido_desencriptado_cons);

                // Borrar los archivos config_iv.bin y cons/index_iv.bin
                unlink($ruta_iv);
                unlink($ruta_iv_cons);
            }
        }
    } else {
       
        // Encriptar los archivos antes de mostrar el mensaje
        if (!file_exists($ruta_iv) || !file_exists($ruta_iv_cons)) {
            // Encriptar archivo config
            $contenido_archivo = file_get_contents($ruta_archivo);
            if ($contenido_archivo === false) {
                throw new Exception("No se pudo leer el archivo config.php.");
            }
            // Generar un IV para el cifrado
            $iv_length = openssl_cipher_iv_length('AES-256-CBC');
            $iv = openssl_random_pseudo_bytes($iv_length);

            if ($iv === false || strlen($iv) !== $iv_length) {
                throw new Exception("Error al generar el IV.");
            }

            $contenido_encriptado = openssl_encrypt($contenido_archivo, 'AES-256-CBC', $clave_cifrado, 0, $iv);
            if ($contenido_encriptado === false) {
                throw new Exception("Error al encriptar el archivo.");
            }

            file_put_contents($ruta_archivo, $contenido_encriptado);
            file_put_contents($ruta_iv, $iv); // Guardar el IV para desencriptar en el futuro

            // Encriptar archivo cons/index
            $contenido_archivo_cons = file_get_contents($ruta_archivo_cons);
            if ($contenido_archivo_cons === false) {
                throw new Exception("No se pudo leer el archivo cons/index.php.");
            }
            // Generar un IV para el cifrado
            $iv_length = openssl_cipher_iv_length('AES-256-CBC');
            $iv_cons = openssl_random_pseudo_bytes($iv_length);

            if ($iv_cons === false || strlen($iv_cons) !== $iv_length) {
                throw new Exception("Error al generar el IV.");
            }

            $contenido_encriptado_cons = openssl_encrypt($contenido_archivo_cons, 'AES-256-CBC', $clave_cifrado, 0, $iv_cons);
            if ($contenido_encriptado_cons === false) {
                throw new Exception("Error al encriptar el archivo.");
            }

            file_put_contents($ruta_archivo_cons, $contenido_encriptado_cons);
            file_put_contents($ruta_iv_cons, $iv_cons); // Guardar el IV para desencriptar en el futuro
        }

        // Mostrar mensaje de error después de encriptar los archivos
            // Consultar la base de datos para obtener el enlace para redirigir
            $query = "SELECT enlace FROM licencias WHERE clave = :clave";
            $stmt = $conexion->prepare($query);
            $stmt->bindParam(':clave', $clave_programa);
            $stmt->execute();
            $enlace = $stmt->fetchColumn();

            // Crear un botón para redirigir
      echo '<style>
      .redirigir {
        text-align: center;
        margin-top: 2em;
      }
      .redirigir button {
        background-color: #4CAF50;
        color: white;
        padding: 14px 25px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 16px;
        margin: 4px 2px;
        cursor: pointer;
      }
    </style>
    <div class="redirigir">
      <h1>Su sistema esta desactivado entre en este enlace para mas informacion</h1>
      <form action="' . $enlace . '" method="get">
        <button type="submit">Ir al enlace</button>
      </form>
    </div>';
    

        // Detener la ejecución para evitar que se muestren otras páginas
        exit;
    }

    // Si la licencia es válida, el flujo continúa sin mostrar nada al navegador
    // No utilizar exit aquí, ya que queremos continuar la ejecución en los archivos que incluyan este script.

} catch (Exception $e) {
    // Manejo de errores: registrar el error en un archivo de log
    error_log("Error: " . $e->getMessage());
    // Opcional: Aquí puedes enviar un correo o guardar el error en la base de datos si lo necesitas
    // No se envía nada al navegador.
}


} 
    
    
?>

