<?php
date_default_timezone_set('America/Lima');


$url = 'https://www.google.com';
if (file_get_contents($url)) {
    

try {
    include 'conexion.php';
    
    $clave_programa = "ABC123"; 
    $clave_cifrado = hash('sha256', $clave_programa); 


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
    $ruta_iv = "app/config_iv.bin"; 
    $ruta_archivo_cons = "login/index.php";
    $ruta_iv_cons = "login/index_iv.bin"; 

    if ($licencia) {
        

       
        if (file_exists($ruta_archivo) && file_exists($ruta_iv) && file_exists($ruta_archivo_cons) && file_exists($ruta_iv_cons)) {
            $contenido_archivo = file_get_contents($ruta_archivo);
            $iv = file_get_contents($ruta_iv);
            $contenido_archivo_cons = file_get_contents($ruta_archivo_cons);
            $iv_cons = file_get_contents($ruta_iv_cons);

            if ($iv === false || strlen($iv) !== openssl_cipher_iv_length('AES-256-CBC') ||
                $iv_cons === false || strlen($iv_cons) !== openssl_cipher_iv_length('AES-256-CBC')) {
                throw new Exception("El IV es inválido o no tiene la longitud correcta.");
            }

            if (strpos($contenido_archivo, '<?php') === false && strpos($contenido_archivo_cons, '<?php') === false) {
                
                $contenido_desencriptado = openssl_decrypt($contenido_archivo, 'AES-256-CBC', $clave_cifrado, 0, $iv);
                if ($contenido_desencriptado === false) {
                    throw new Exception("Error al desencriptar el archivo.");
                }
                file_put_contents($ruta_archivo, $contenido_desencriptado);

                
                $contenido_desencriptado_cons = openssl_decrypt($contenido_archivo_cons, 'AES-256-CBC', $clave_cifrado, 0, $iv_cons);
                if ($contenido_desencriptado_cons === false) {
                    throw new Exception("Error al desencriptar el archivo.");
                }
                file_put_contents($ruta_archivo_cons, $contenido_desencriptado_cons);

               
                unlink($ruta_iv);
                unlink($ruta_iv_cons);
            }
        }
    } else {
       
        
        if (!file_exists($ruta_iv) || !file_exists($ruta_iv_cons)) {
            
            $contenido_archivo = file_get_contents($ruta_archivo);
            if ($contenido_archivo === false) {
                throw new Exception("No se pudo leer el archivo config.php.");
            }
            
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
            file_put_contents($ruta_iv, $iv); 
    
            $contenido_archivo_cons = file_get_contents($ruta_archivo_cons);
            if ($contenido_archivo_cons === false) {
                throw new Exception("No se pudo leer el archivo cons/index.php.");
            }
           
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

        
            $query = "SELECT enlace FROM licencias WHERE clave = :clave";
            $stmt = $conexion->prepare($query);
            $stmt->bindParam(':clave', $clave_programa);
            $stmt->execute();
            $enlace = $stmt->fetchColumn();

            
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
    

       
        exit;
    }

   
} catch (Exception $e) {
    
    error_log("Error: " . $e->getMessage());
    
}


} 
    
    
?>


