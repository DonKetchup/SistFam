<?php
    define("RUTA","/sistfam/");

    function dbConectar()
    {
        static $conexion;

        if(!isset($connection)) 
        {
            $config = parse_ini_file('config.ini'); 
            $conexion = mysqli_connect($config['servidor'],$config['usuario'],$config['pass'],$config['bbdd']);
            $query="set CHARSET 'utf8'";
            $conexion->query($query);
        }
        if($conexion === false) 
        {
            // Manejo de error  para notificar al administrador, creamos un archivo log, mostramos un error en pantalla, etc.
            error_log("Error de conexión: " . mysqli_connect_error());
            echo "Error de conexión a la base de datos.";
        }
        return $conexion;

    }
    
    
?>