<?php
/**
 * Created by PhpStorm.
 * User: HILARIWEB
 * Date: 18/1/2023
 * Time: 15:02
 */

session_start();
if (isset($_SESSION['sesion_usuario'])) {
    // echo "si existe sesion de " . $_SESSION['sesion_usuario'];
    $nombre_usuario_sesion = $_SESSION['sesion_usuario'];
    $sql = "SELECT u.ID_usuario, u.Nombre_usuario, u.ID_tipousuario, tu.Nombre_tipousuario as rol 
            FROM usuario as u 
            INNER JOIN tipo_usuario as tu ON u.ID_tipousuario = tu.ID_tipousuario 
            WHERE Nombre_usuario = :nombre_usuario_sesion";
    $query = $pdo->prepare($sql);
    $query->execute(['nombre_usuario_sesion' => $nombre_usuario_sesion]);
    $usuarios = $query->fetchAll(PDO::FETCH_ASSOC);
    foreach ($usuarios as $usuario) {
        $id_usuario_sesion = $usuario['ID_usuario'];
        $nombres_sesion = $usuario['Nombre_usuario'];
        $rol_sesion = $usuario['rol'];
    }
} else {
    echo "no existe sesion";
    header('Location: ' . $URL . '/login');
}


