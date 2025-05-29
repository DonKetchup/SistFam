<?php
include_once("config/config.php");
$conexion = dbConectar()();
require('../Cliente/lib/fpdf/fpdf.php');
class PDF extends FPDF{
    function Header(){
        $this->Image('../Cliente/img',10,8,33);
        //tipo de letra
        $this->SetFont('Cascadia Code', 'B', 15);
        //Mover a la derecha
        $this->Cell(110);
        //Titulo
        $this->Cell(60,10,'REPORTE DE USUARIOS', 0,0,'C');
        //SALTO DE LINEA
        $this->Ln(30);
        //CAMBIAMOS EL TIPO DE LETRA
        $this->SetFont('Courier New', 'B', 11);
        //Agregamos los encabezados de la tabla
        $this->Cell(30,10,'Nombre',1,0,'C');
        $this->Cell(30,10,'Apellido Paterno',1,0,'C');
        $this->Cell(30,10,'Apellido Materno',1,0,'C');
        $this->Cell(30,10,'Correo',1,0,'C');
        $this->Cell(30,10,'Telefono',1,0,'C');
        //SALTO DE LINEA
        $this->Ln(10);
    }
}
//Consulta a nuestra bd
$consulta = 'select * from usuarios';
$resultado = $mysql->query($consulta);
$pdf = new PDF('L');
$pdf->AddPage();
$pdf->SetFont('Cascadia Code', 'B', 9);
while($row=$resultado->fetch_assoc()){
    $pdf->Cell(30,10,utf8_decode($row['nombre']),1,0,'C');
    $pdf->Cell(30,10,utf8_decode($row['apaterno']),1,0,'C');
    $pdf->Cell(30,10,utf8_decode($row['amaterno']),1,0,'C');
    $pdf->Cell(60,10,utf8_decode($row['correo']),1,0,'C');
    $pdf->Cell(20,10,utf8_decode($row['telefono']),1,0,'C');
    $pdf->Ln(10);
    //Salida ya para mostrar los datos
    $pdf->Output();
}