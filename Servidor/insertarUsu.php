<?php 
include_once 'config/config.php';

$conexion = dbConectar(); // <-- Agrega esta línea

$c1 = $_POST['nombre'];
$c2 = $_POST['apa'];
$c3 = $_POST['ama'];
$c4 = $_POST['correo'];
$c5 = $_POST['telefono'];
$c6 = $_POST['pass'];
$c7 = $_POST['idtipo']; // Nuevo campo para el tipo de usuario

// Sentencia para evitar inyección SQL
$sql = $conexion->prepare("INSERT INTO usuarios(nombre, apaterno, amaterno, correo, telefono, pass, idtipo) VALUES (?, ?, ?, ?, ?, ?, ?)");
$sql->bind_param("ssssssi", $c1, $c2, $c3, $c4, $c5, $c6, $c7);
$sql->execute();
$sql->close();

// Redireccionar a la página de inicio
header("Location:../Cliente/usuarios.php");

// Cerrar la conexión
$conexion->close();
?>