<?php
include_once 'config/config.php';

$conexion = dbConectar();

$procod = $_POST['procod'];        // varchar(20)
$pronom = $_POST['pronom'];        // varchar(100)
$prodes = $_POST['prodes'];        // varchar(250)
$procos = $_POST['procos'];        // double(8,2)
$catcve = $_POST['catcve'];        // int
$proest = $_POST['proest'];        // int (0 o 1)

// No subes imágenes, se guarda NULL
$proimg = null;

$sql = $conexion->prepare("INSERT INTO producto(procod, pronom, prodes, procos, proimg, catcve, proest) VALUES (?, ?, ?, ?, ?, ?, ?)");
$sql->bind_param("sssdisi", $procod, $pronom, $prodes, $procos, $proimg, $catcve, $proest);

if($sql->execute()){
    $sql->close();
    $conexion->close();
    header("Location: ../Cliente/productos.php");
    exit();
} else {
    $error = $conexion->error;
    $sql->close();
    $conexion->close();
    die("Error al insertar el producto: " . $error);
}
?>