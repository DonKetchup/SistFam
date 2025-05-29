<?php
session_start();
include("config/config.php");

$conexion = dbConectar();

$correo = $_POST['correo'] ?? '';
$contra = $_POST['contra'] ?? '';

function mostrarSweetAlert($titulo, $mensaje, $icono, $redireccion) {
    // Escapar caracteres especiales para evitar problemas de JS/HTML
    $titulo = htmlspecialchars($titulo, ENT_QUOTES, 'UTF-8');
    $mensaje = htmlspecialchars($mensaje, ENT_QUOTES, 'UTF-8');
    $icono = htmlspecialchars($icono, ENT_QUOTES, 'UTF-8');
    $redireccion = htmlspecialchars($redireccion, ENT_QUOTES, 'UTF-8');
    echo "
    <!DOCTYPE html>
    <html lang='es'>
    <head>
        <meta charset='UTF-8'>
        <title>Mensaje</title>
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    </head>
    <body>
        <script>
            Swal.fire({
                title: '$titulo',
                text: '$mensaje',
                icon: '$icono',
                confirmButtonText: 'Aceptar'
            }).then(() => {
                window.location.href = '$redireccion';
            });
        </script>
    </body>
    </html>
    ";
}

if ($correo != '' && $contra != '') {
    $sql = "SELECT * FROM usuarios WHERE correo = ? AND pass = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("ss", $correo, $contra);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows === 1) {
        $usuario = $resultado->fetch_assoc();
        $_SESSION['usuario'] = $usuario['nombre'];
        $_SESSION['apa'] = $usuario['apaterno'];
        $_SESSION['ama'] = $usuario['amaterno'];
        $_SESSION['tipo'] = $usuario['idtipo'];

        $nombreCompleto = $usuario["nombre"] . ' ' . $usuario["apaterno"] . ' ' . $usuario["amaterno"];
        mostrarSweetAlert('Bienvenido', ' ' . $nombreCompleto, 'success', '../Cliente/principal.php');
        exit;
    } else {
        mostrarSweetAlert('Error', 'Correo o contraseña incorrectos', 'error', '../index.php');
        exit;
    }
} else {
    mostrarSweetAlert('Campos Vacíos', 'Por favor, complete todos los campos', 'warning', '../index.php');
    exit;
}
?>
