<?php
include_once("config/config.php"); // Incluir la configuración para la conexión a la base de datos
$mysqli = dbConectar(); // Conectar a la base de datos

// Incluir la librería FPDF para la generación de PDFs
require(__DIR__ . '/../Cliente/lib/fpdf/fpdf.php');

class PDF extends FPDF {
    // Método header() que se llama al principio de cada página del PDF
    function Header() {
        // Añadir una imagen al encabezado - skip if image doesn't exist
        $logoPath = __DIR__ . '/../Cliente/img/logo.png';
        if (file_exists($logoPath)) {
            $this->Image($logoPath, 10, 8, 33);
        }
        $this->SetFont('Arial', 'B', 15); // Establecer fuente para el encabezado
        $this->Cell(110);
        $this->Cell(0, 10, 'Reporte Usuarios', 0, 1, 'C'); // Escribir un texto centrado
        $this->Ln(30);//Salto de linea
        $this->SetFont('Arial', 'B', 12);//cambiamos el tipo de letra
        $this->Cell(30,10,'Nombre',1,0,'C');
        $this->Cell(40,10,'Paterno',1,0,'C');
        $this->Cell(50,10,'Materno',1,0,'C');
        $this->Cell(60,10,'Correo',1,0,'C');
        $this->Cell(70,10,'Telefono',1,0,'C');
        $this->Ln(10);//Salto de linea
    }
    
    // Puedes agregar otros métodos aquí como Footer() o cualquier otro personalización para el PDF
}
//consulta a l
// a base de datos
$consulta='select * from usuarios';
$resultado=$mysqli->query($consulta);

// Crear un objeto PDF
$pdf = new PDF('L');
$pdf->AddPage(); // Añadir una página
$pdf->SetFont('Arial', 'B', 10);//cambiamos el tipo de letra
while ($row=$resultado->fetch_assoc()){
    $pdf->Cell(30,10,utf8_decode($row['nombre']),1,0,'C');
    $pdf->Cell(40,10,utf8_decode($row['apaterno']),1,0,'C');
    $pdf->Cell(50,10,utf8_decode($row['amaterno']),1,0,'C');
    $pdf->Cell(60,10,utf8_decode($row['correo']),1,0,'C');
    $pdf->Cell(70,10,utf8_decode($row['telefono']),1,0,'C');
    $pdf->Ln(10);//Salto de linea
}
$pdf->Output(); // Mostrar el PDF generado
?>