<?php

include ('../../config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dni = $_POST['dni-reg'];
    $nombres = $_POST['nombre-reg'];
    $apellido_paterno = $_POST['apellido-paterno-reg'];
    $apellido_materno = $_POST['apellido_materno-reg'];
    $celular = $_POST['celular-reg'];
    $direccion = $_POST['direccion-reg'];
    $id_cargo = $_POST['cargo-reg'];
    $estado = !empty($_POST['estado-reg']) ? ($_POST['estado-reg'] == "Activo" ? 1 : 0) : 1;

    try {
        $stmt = $pdo->prepare("SELECT COUNT(*) as cantidad FROM personal WHERE Dni = :dni");
        $stmt->execute(['dni' => $dni]);
        $row = $stmt->fetch();
        if ($row['cantidad'] > 0) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Ya existe un personal con ese DNI.']);
            exit;
        }

        $stmt = $pdo->prepare("INSERT INTO personal 
            (Dni, Nombre, Apellido_paterno, Apellido_materno, Celular, Direccion, ID_cargo, Estado) 
            VALUES 
            (:dni, :nombres, :apellido_paterno, :apellido_materno, :celular, :direccion, :id_cargo, :estado)");
        $stmt->execute([
            'dni' => $dni,
            'nombres' => $nombres,
            'apellido_paterno' => $apellido_paterno,
            'apellido_materno' => $apellido_materno,
            'celular' => $celular,
            'direccion' => $direccion,
            'id_cargo' => $id_cargo,
            'estado' => $estado
        ]);

        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'message' => 'Datos guardados correctamente.']);
        exit;
    } catch (PDOException $e) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Error al guardar el personal: ' . $e->getMessage()]);
    }
}
