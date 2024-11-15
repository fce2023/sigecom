<?php

include ('../../config.php');

try {
    // Se obtienen los datos del formulario
    $nombre = $_POST['nombre-proveedor'];
    $direccion = $_POST['direccion-proveedor'];
    $telefono = $_POST['telefono-proveedor'];
    $estado = ($_POST['estado-proveedor'] === '1') ? 1 : 0;

    // Verificar si el nombre del proveedor existe en la tabla `proveedor`
    $consulta = $pdo->prepare("SELECT COUNT(*) FROM proveedor WHERE Nombre = :nombre");
    $consulta->execute([':nombre' => $nombre]);
    $proveedor_exists = $consulta->fetchColumn();

    if (!$proveedor_exists) {
        // Si el proveedor no existe, insertar en la tabla `proveedor`
        $sentencia = $pdo->prepare("INSERT INTO proveedor 
            (Nombre, Dirección, Teléfono, Estado) 
            VALUES (:nombre, :direccion, :telefono, :estado)");

        $sentencia->execute([
            ':nombre' => $nombre,
            ':direccion' => $direccion,
            ':telefono' => $telefono,
            ':estado' => $estado
        ]);

        // Redirigir a la página de proveedores con mensaje de éxito
        header("Location: " . $URL . "/proveedor/index.php?success=Proveedor registrado correctamente");
        exit();
    } else {
        // Si el proveedor ya existe, redirigir con un mensaje de error
        header("Location: " . $URL . "/proveedor/crear.php?error=El proveedor ya existe&nombre=" . urlencode($nombre));
        exit();
    }
} catch (PDOException $e) {
    // Manejo de errores de conexión o consulta
    header("Location: " . $URL . "/proveedor/crear.php?error=Error al registrar el proveedor: " . urlencode($e->getMessage()));
    exit();
}

