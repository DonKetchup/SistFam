<?php
include("../Servidor/config/config.php");
$conexion = dbConectar();

// Obtener categorías para el select
$categorias = [];
$sqlCat = "SELECT catcve, catnom FROM categoria";
$resCat = $conexion->query($sqlCat);
if ($resCat) {
    while ($fila = $resCat->fetch_assoc()) {
        $categorias[] = $fila;
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <title>Gestión de Productos</title>
    <link rel="stylesheet" href="css/estilo2.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
</head>

<body>
    <?php include("include/menu.php"); ?>
    <div class="main-content">
        <header>
            <h1>Sistema de Gestión de Productos</h1>
        </header>
        <div class="content">
            <!-- Formulario para registrar producto -->
            <form id="registroProductoForm" method="post" action="../Servidor/insertarProducto.php"
                enctype="multipart/form-data">
                <h2>Registro de Producto</h2>
                <input type="text" name="procod" placeholder="Código del producto" maxlength="20" required />
                <input type="text" name="pronom" placeholder="Nombre" maxlength="100" required />
                <input type="text" name="prodes" placeholder="Descripción" maxlength="250" />
                <input type="number" name="procos" step="0.01" placeholder="Costo" required />
                <input type="file" name="proimg" accept="image/*" />
                <select name="catcve" required>
                    <option value="">Selecciona categoría</option>
                    <?php foreach ($categorias as $cat): ?>
                    <option value="<?php echo $cat['catcve']; ?>"><?php echo htmlspecialchars($cat['catnom']); ?>
                    </option>
                    <?php endforeach; ?>
                </select>
                <select name="proest" required>
                    <option value="">Selecciona estado</option>
                    <option value="1">Activo</option>
                    <option value="0">Inactivo</option>
                </select>
                <button type="submit">Registrar Producto</button>
            </form>

            <!-- Tabla de productos -->
            <h2 class="mt-4">Productos Registrados</h2>
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle" style="color:black">
                    <thead class="table-dark">
                        <tr>
                            <th>Código</th>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Costo</th>
                            <th>Imagen</th>
                            <th>Categoría</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
          $sqlProductos = "SELECT p.procod, p.pronom, p.prodes, p.procos, p.proimg, c.catcve, p.proest 
                      FROM producto p
                      LEFT JOIN categoria c ON p.catcve = c.catcve";
          $resProd = $conexion->query($sqlProductos);
          if ($resProd && $resProd->num_rows > 0):
            while ($prod = $resProd->fetch_assoc()):
          ?>
                        <tr>
                            <td><?php echo htmlspecialchars($prod['procod']); ?></td>
                            <td><?php echo htmlspecialchars($prod['pronom']); ?></td>
                            <td><?php echo htmlspecialchars($prod['prodes']); ?></td>
                            <td><?php echo number_format($prod['procos'], 2); ?></td>
                            <td>
                                <?php if ($prod['proimg']): ?>
                                <img src="../img_productos/<?php echo htmlspecialchars($prod['proimg']); ?>"
                                    alt="Imagen" width="50" />
                                <?php else: ?>
                                Sin imagen
                                <?php endif; ?>
                            </td>
                            <td><?php echo htmlspecialchars($cat['catnom']); ?></td>
                            <td><?php echo $prod['proest'] == 1 ? 'Activo' : 'Inactivo'; ?></td>
                            <td>
                                <button class="btn btn-sm btn-warning" type="button" data-bs-toggle="modal"
                                    data-bs-target="#modalEditarProducto"
                                    onclick="cargarDatosEditarProducto('<?php echo addslashes($prod['procod']); ?>', '<?php echo addslashes($prod['pronom']); ?>', '<?php echo addslashes($prod['prodes']); ?>', '<?php echo $prod['procos']; ?>', '<?php echo addslashes($prod['proimg']); ?>', '<?php echo $prod['catcve']; ?>', '<?php echo $prod['proest']; ?>')">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                                <button class="btn btn-sm btn-danger"
                                    onclick="eliminarProducto('<?php echo $prod['procod']; ?>')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                        <?php endwhile; else: ?>
                        <tr>
                            <td colspan="8" class="text-center">No hay productos registrados.</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <?php include("include/pie.php"); ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {

        window.eliminarProducto = function(procod) {
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
                    fetch('../Servidor/eliminarProducto.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                procod: procod
                            })
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire('¡Eliminado!', 'El producto ha sido eliminado.',
                                        'success')
                                    .then(() => location.reload());
                            } else {
                                Swal.fire('Error', 'No se pudo eliminar el producto.', 'error');
                            }
                        });
                }
            });
        };

        window.cargarDatosEditarProducto = function(procod, pronom, prodes, procos, proimg, catcve, proest) {
            document.getElementById('edit-procod').value = procod;
            document.getElementById('edit-pronom').value = pronom;
            document.getElementById('edit-prodes').value = prodes;
            document.getElementById('edit-procos').value = procos;
            // Aquí puedes agregar selects para categoría y estado si deseas
        };

    });
    </script>

    <!-- Modal para Editar Producto -->
    <div class="modal fade" id="modalEditarProducto" tabindex="-1" aria-labelledby="modalEditarProductoLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <form id="formEditarProducto" class="modal-content" method="post"
                action="../Servidor/actualizarProducto.php" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEditarProductoLabel">Editar Producto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="edit-procod" name="procod" />
                    <div class="mb-3">
                        <label for="edit-pronom" class="form-label">Nombre</label>
                        <input type="text" id="edit-pronom" name="pronom" class="form-control" required />
                    </div>
                    <div class="mb-3">
                        <label for="edit-prodes" class="form-label">Descripción</label>
                        <input type="text" id="edit-prodes" name="prodes" class="form-control" />
                    </div>
                    <div class="mb-3">
                        <label for="edit-procos" class="form-label">Costo</label>
                        <input type="number" step="0.01" id="edit-procos" name="procos" class="form-control" required />
                    </div>
                    <!-- Aquí puedes agregar selects para categoría y estado si deseas -->
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