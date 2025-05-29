<?php
include_once 'config/config.php';
$conexion = dbConectar();

$data = json_decode(file_get_contents('php://input'), true);

// Depuración: guarda los datos recibidos
file_put_contents('debug_actualizar.txt', print_r($data, true));

if ($data) {
    $id = intval($data['id']);
    $nombre = $data['nombre'];
    $apa = $data['apa'];
    $ama = $data['ama'];
    $correo = $data['correo'];
    $telefono = $data['telefono'];

    $sql = $conexion->prepare("UPDATE usuarios SET nombre=?, apaterno=?, amaterno=?, correo=?, telefono=? WHERE idusuario=?");
    $sql->bind_param("sssssi", $nombre, $apa, $ama, $correo, $telefono, $id);
    $success = $sql->execute();

    if ($sql->error) {
        echo json_encode(['success' => false, 'error' => $sql->error]);
    } else {
        echo json_encode(['success' => $success]);
    }

    $sql->close();
    $conexion->close();
} else {
    echo json_encode(['success' => false, 'error' => 'No data']);
}
?>