<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Reportes</title>
    <link rel="stylesheet" href="css/estilo2.css">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <!-- Incluimos el menú general -->
    <?php include("include/menu.php"); ?>

    <div class="main-content">
        <header>
            <h1 class="text-center mt-3">Sistema de Gestión de SPA</h1>
        </header>

        <div class="container mt-4">
            <h2 class="text-center mb-4">Reportes</h2>
            <div class="row justify-content-center">
                <!-- Reporte PDF -->
                <div class="col-md-6 text-center mb-3">
                    <h3>Reporte 1 (PDF)</h3>
                    <a href="../Servidor/reportepdfUsu.php">
                        <img src="img/pdf_Icon.png" height="200px" width="200px" alt="PDF">
                    </a>
                </div>
                <!-- Reporte Excel -->
                <div class="col-md-6 text-center mb-3">
                    <h3>Reporte 2 (Excel)</h3>
                    <a href="../Servidor/reporteexcelUsu.php">
                        <img src="img/Excel_Icon.png" height="200px" width="200px" alt="Excel">
                    </a>
                </div>
            </div>
        </div>

        <?php include("include/pie.php"); ?>
    </div>


</body>

</html>