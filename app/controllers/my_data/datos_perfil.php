<?php

if (isset($_SESSION['sesion_usuario'])) {
    $nombre_usuario_sesion = $_SESSION['sesion_usuario'];

    // Consulta para obtener los datos del usuario y del personal asociado
    $sql = "
        SELECT u.ID_usuario, u.Nombre_usuario, u.ID_tipousuario, tu.Nombre_tipousuario as rol, 
               p.Dni, p.Nombre as Nombre_personal, p.Apellido, p.Celular, p.Direccion, p.Estado as Estado_personal, p.ID_personal,
               c.Nom_cargo
        FROM usuario as u
        INNER JOIN tipo_usuario as tu ON u.ID_tipousuario = tu.ID_tipousuario
        INNER JOIN personal as p ON u.id_personal = p.ID_personal
        INNER JOIN cargo as c ON p.ID_cargo = c.ID_cargo
        WHERE u.Nombre_usuario = :nombre_usuario_sesion";
    $query = $pdo->prepare($sql);
    $query->execute(['nombre_usuario_sesion' => $nombre_usuario_sesion]);
    $usuario = $query->fetch(PDO::FETCH_ASSOC);

    if (!$usuario) {
        echo "No se encontraron datos para el usuario.";
        exit;
    }
} else {
    header('Location: ' . $URL . '/login');
    exit;
}
?>
