<!---interfaz  para la  autentificación de  usuarios--->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Interfaz de Autenticación</title>
   <link rel="stylesheet" href="Cliente/css/estiloindex.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  </head>
<body>
  <div class="auth-container">
    <h2>Iniciar Sesión</h2>
    <form method='POST' action="Servidor/iniciosesion.php">
      <input type="email" placeholder="Correo electrónico" name="correo"/>
      <input type="password" placeholder="Contraseña" name="contra" />
      <button type="submit">Iniciar Sesión</button>
      <a href="#">¿Olvidaste tu contraseña?</a>
    </form>
  </div>
</body>
</html>