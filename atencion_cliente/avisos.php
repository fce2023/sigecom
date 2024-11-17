<?php

if (isset($_POST['mensaje'])) {
    $mensaje = $_POST['mensaje'];
    $response = array('message' => $mensaje);
    echo json_encode($response);
    exit;
}
