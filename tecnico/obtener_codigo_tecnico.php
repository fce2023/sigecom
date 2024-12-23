<?php
require_once('../app/config.php');

if (isset($_GET['id'])) {
    $id_tecnico = $_GET['id'];
    $query = "SELECT codigo FROM tecnico WHERE id_tecnico = :id_tecnico";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['id_tecnico' => $id_tecnico]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    echo json_encode(['success' => true, 'codigo' => $row['codigo']]);
} else {
    echo json_encode(['success' => false, 'codigo' => '']);
}

