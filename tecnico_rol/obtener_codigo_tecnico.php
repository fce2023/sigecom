<?php
require_once('../app/config.php');

if (isset($_GET['id_personal'])) {
    $id_personal = $_GET['id_personal'];
    $query = "SELECT codigo FROM tecnico WHERE id_personal = :id_personal";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['id_personal' => $id_personal]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    echo json_encode(['success' => true, 'codigo' => $row['codigo']]);
} else {
    echo json_encode(['success' => false, 'codigo' => '']);
}

