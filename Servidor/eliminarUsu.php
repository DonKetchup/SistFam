<?php
include_once 'config/config.php';
$conexion = dbConectar();

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['id'])) {
    $id = intval($data['id']);
    $sql = $conexion->prepare("DELETE FROM usuarios WHERE idusuario=?");
    $sql->bind_param("i", $id);
    $success = $sql->execute();
    $sql->close();
    $conexion->close();
    echo json_encode(['success' => $success]);
} else {
    echo json_encode(['success' => false]);
}
?>