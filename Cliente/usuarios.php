<?php
// Conexión a la base de datos
include("../Servidor/config/config.php");
$conexion = dbConectar();

// Consulta de tipos de usuario
$tipos = [];
$sql = "SELECT idtipo, tipo FROM tipo";
$result = $conexion->query($sql);
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $tipos[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Usuarios</title>
  <link rel="stylesheet" href="css/estilo2.css">
<!-- Bootstrap 5 CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>
  <!--incluimos el menu general -->
<?php include("include/menu.php");?>
  <div class="main-content">
    <header> 
      <h1>Sistema de Gestión de  SPA</h1>
    <div class="content">
       <!-- Formulario -->
    <form id="registroForm" method="post" action="../servidor/insertarUsu.php">
      <div style="text-align: center;">
        <h2>Registro de Usuario</h2>
      </div>
      <input type="text" id="nombre" name="nombre" placeholder="Nombre(s)" required>
      <input type="text" id="apa" name="apa" placeholder="Apellido Paterno" required>
      <input type="text" id="ama" name="ama" placeholder="Apellido Materno" required>
      <input type="email" id="correo" name="correo" placeholder="Correo electrónico" required>
      <input type="text" id="telefono" name="telefono" placeholder="Teléfono (10 dígitos)" pattern="\d{10}" required>
      <input type="password" id="pass" name="pass" placeholder="Contraseña (mín. 6 caracteres)" minlength="6" required>
      <select id="idtipo" name="idtipo" required>
        <option value="">Selecciona tipo de usuario</option>
        <?php foreach($tipos as $tipo): ?>
          <option value="<?php echo $tipo['idtipo']; ?>"><?php echo htmlspecialchars($tipo['tipo']); ?></option>
        <?php endforeach; ?>
      </select>
      <button type="submit">Registrar</button>
    </form>

    <!-- Tabla de usuarios -->
    <h2 class="text-center mt-4">Usuarios Registrados</h2>
    <div class="table-responsive">
      <table class="table table-striped table-hover align-middle" style="color:black">
        <thead class="table-dark">
          <tr>
            <th>Nombre</th>
            <th>Apellido Paterno</th>
            <th>Apellido Materno</th>
            <th>Correo</th>
            <th>Teléfono</th>
            <th>Tipo</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $sqlUsuarios = "SELECT u.idusuario, u.nombre, u.apaterno, u.amaterno, u.correo, u.telefono, t.tipo 
                          FROM usuarios u 
                          INNER JOIN tipo t ON u.idtipo = t.idtipo";
          $resUsuarios = $conexion->query($sqlUsuarios);
          if ($resUsuarios && $resUsuarios->num_rows > 0):
            while ($usu = $resUsuarios->fetch_assoc()):
          ?>
          <tr>
            <td><?php echo htmlspecialchars($usu['nombre']); ?></td>
            <td><?php echo htmlspecialchars($usu['apaterno']); ?></td>
            <td><?php echo htmlspecialchars($usu['amaterno']); ?></td>
            <td><?php echo htmlspecialchars($usu['correo']); ?></td>
            <td><?php echo htmlspecialchars($usu['telefono']); ?></td>
            <td><?php echo htmlspecialchars($usu['tipo']); ?></td>
            <td>
              <!-- Button trigger modal -->
<button 
  class="btn btn-sm btn-warning"
  type="button"
  data-bs-toggle="modal"
  data-bs-target="#modalEditar"
  onclick="cargarDatosEditar(
    <?php echo $usu['idusuario']; ?>,
    '<?php echo htmlspecialchars($usu['nombre'], ENT_QUOTES); ?>',
    '<?php echo htmlspecialchars($usu['apaterno'], ENT_QUOTES); ?>',
    '<?php echo htmlspecialchars($usu['amaterno'], ENT_QUOTES); ?>',
    '<?php echo htmlspecialchars($usu['correo'], ENT_QUOTES); ?>',
    '<?php echo htmlspecialchars($usu['telefono'], ENT_QUOTES); ?>'
  )"
>
  <i class="bi bi-pencil-square"></i>
</button>
<button class="btn btn-sm btn-danger" onclick="eliminarUsuario(<?php echo $usu['idusuario']; ?>)">
  <i class="bi bi-trash"></i>
</button>
            </td>
          </tr>
          <?php endwhile; else: ?>
          <tr>
            <td colspan="7" class="text-center">No hay usuarios registrados.</td>
          </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

    </div>
    <?php include("include/pie.php");?>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
document.addEventListener('DOMContentLoaded', function() {

  function eliminarUsuario(id) {
    Swal.fire({
      title: '¿Estás seguro?',
      text: "¡No podrás revertir esto!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#3085d6',
      confirmButtonText: 'Sí, eliminar',
      cancelButtonText: 'Cancelar'
    }).then((result) => {
      if (result.isConfirmed) {
        fetch('../Servidor/eliminarUsu.php', {
          method: 'POST',
          headers: {'Content-Type': 'application/json'},
          body: JSON.stringify({id: id})
        })
        .then(response => response.json())
        .then(data => {
          if(data.success){
            Swal.fire('¡Eliminado!', 'El usuario ha sido eliminado.', 'success')
              .then(() => location.reload());
          } else {
            Swal.fire('Error', 'No se pudo eliminar el usuario.', 'error');
          }
        });
      }
    });
  }

  window.eliminarUsuario = eliminarUsuario;

  window.cargarDatosEditar = function(id, nombre, apa, ama, correo, telefono) {
    document.getElementById('edit-idusuario').value = id;
    document.getElementById('edit-nombre').value = nombre;
    document.getElementById('edit-apa').value = apa;
    document.getElementById('edit-ama').value = ama;
    document.getElementById('edit-correo').value = correo;
    document.getElementById('edit-telefono').value = telefono;
  }

  document.getElementById('formEditarUsuario').onsubmit = function(e) {
    e.preventDefault();

    const datos = {
      id: document.getElementById('edit-idusuario').value,
      nombre: document.getElementById('edit-nombre').value,
      apa: document.getElementById('edit-apa').value,
      ama: document.getElementById('edit-ama').value,
      correo: document.getElementById('edit-correo').value,
      telefono: document.getElementById('edit-telefono').value
    };
    fetch('../Servidor/actualizarUsu.php', {
      method: 'POST',
      headers: {'Content-Type': 'application/json'},
      body: JSON.stringify(datos)
    })
    .then(response => response.json())
    .then(data => {
      if(data.success) {
        Swal.fire('¡Actualizado!', 'El usuario ha sido actualizado.', 'success')
          .then(() => {
            location.reload();
          });
      } else {
        Swal.fire('Error', 'No se pudo actualizar el usuario.', 'error');
      }
    });
  }
});
  </script>

<!-- Modal para Editar Usuario -->
<div class="modal fade" id="modalEditar" tabindex="-1" aria-labelledby="modalEditarLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form id="formEditarUsuario" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalEditarLabel">Editar Usuario</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="edit-idusuario" name="idusuario">
        <div class="mb-3">
          <label for="edit-nombre" class="form-label">Nombre</label>
          <input type="text" class="form-control" id="edit-nombre" name="nombre" required>
        </div>
        <div class="mb-3">
          <label for="edit-apa" class="form-label">Apellido Paterno</label>
          <input type="text" class="form-control" id="edit-apa" name="apa" required>
        </div>
        <div class="mb-3">
          <label for="edit-ama" class="form-label">Apellido Materno</label>
          <input type="text" class="form-control" id="edit-ama" name="ama" required>
        </div>
        <div class="mb-3">
          <label for="edit-correo" class="form-label">Correo</label>
          <input type="email" class="form-control" id="edit-correo" name="correo" required>
        </div>
        <div class="mb-3">
          <label for="edit-telefono" class="form-label">Teléfono</label>
          <input type="text" class="form-control" id="edit-telefono" name="telefono" required>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
      </div>
    </form>
  </div>
</div>


</body>
</html>