<?php
include_once("config/config.php");
$conexion = dbConectar();

require(__DIR__ . '/../Cliente/lib/fpdf/fpdf.php');

class PDF extends FPDF {
    function Header() {
        $logoPath = __DIR__ . '/../Cliente/img/NOREVA.png';
        if (file_exists($logoPath)) {
            $this->Image($logoPath, 10, 8, 33);
        }
        $this->SetFont('Arial', 'B', 15);
        $this->Cell(110);
        $this->Cell(0, 10, 'Reporte Productos', 0, 1, 'C');
        $this->Ln(30);
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(20,10,'Codigo',1,0,'C');
        $this->Cell(40,10,'Nombre',1,0,'C');
        $this->Cell(80,10,'Descripcion',1,0,'C');
        $this->Cell(30,10,'Costo',1,0,'C');
        $this->Cell(40,10,'Imagen',1,0,'C');
        $this->Cell(50,10,'Categoria',1,0,'C');
        $this->Cell(20,10,'Estado',1,0,'C');
        $this->Ln(10);
    }
}

$pdf = new PDF('L');
$pdf->AddPage();
$pdf->SetFont('Arial', '', 10);

// CONSULTA CON JOIN PARA OBTENER NOMBRE DE CATEGORÍA
$consulta = "
    SELECT p.procod, p.pronom, p.prodes, p.procos, p.proimg, p.proest, c.catnom
    FROM producto p
    LEFT JOIN categoria c ON p.catcve = c.catcve
";

$resultado = $conexion->query($consulta);

if ($resultado && $resultado->num_rows > 0) {
    while ($row = $resultado->fetch_assoc()) {
        $pdf->Cell(20,10,utf8_decode($row['procod']),1,0,'C');
        $pdf->Cell(40,10,utf8_decode($row['pronom']),1,0,'C');
        $pdf->Cell(80,10,utf8_decode($row['prodes']),1,0,'C');
        $pdf->Cell(30,10,number_format($row['procos'], 2),1,0,'C');
        $pdf->Cell(40,10,utf8_decode($row['proimg']),1,0,'C');
        $pdf->Cell(50,10,utf8_decode($row['catnom']),1,0,'C');
        $estado = $row['proest'] == 1 ? 'Activo' : 'Inactivo';
        $pdf->Cell(20,10,utf8_decode($estado),1,0,'C');
        $pdf->Ln(10);
    }
}

$pdf->Output();
?>