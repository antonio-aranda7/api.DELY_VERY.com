<?php
    require('fpdf/fpdf.php');
    require('controller2.php');

    class PDF extends FPDF
    {
        // Page header
        function Header()
        {
            // Logo
            // Arial bold 15
            $this->SetFont('Arial','B',15);
            // Move to the right
            $this->Cell(60);
            //Titulo
            $this->Cell(30,10,'reporte Generado','C');
            // Line break
            $this->Ln(30);
        }

        // Page footer
        function Footer()
        {
            // Position at 1.5 cm from bottom
            $this->SetY(-15);
            // Arial italic 8
            $this->SetFont('Arial','I',8);
            // Page number
            $this->Cell(0,10,'Page '.$this->PageNo().'',0,0,'C');
        }

        function headerTable(){
            $this->SetFont('Times','B',8);
            $this-> cell(30,10,'id_producto',1,0,'C');
            $this-> cell(30,10,'titulo',1,0,'C');
            $this-> cell(30,10,'imagen',1,0,'C');
            $this-> cell(30,10,'precio',1,0,'C');
            $this-> cell(30,10,'descripcion',1,0,'C');
            $this->Ln();
        }

        function viewTable()
        {
            $db=Db::conectar();
            $this->SetFont('Times','B',8);
            $select =$db->query('SELECT id, title, image, price, description FROM capas ORDER BY id');
            while($data =  $select ->fetch(PDO::FETCH_OBJ)){
                $this-> cell(30,10, $data -> id_producto,1,0,'L');
                $this-> cell(30,10, $data -> titulo,1,0,'L');
                $this-> cell(30,10, $data -> imagen,1,0,'L');
                $this-> cell(30,10, $data -> precio,1,0,'L');
                $this-> cell(30,10, $data -> descripcion,1,0,'L');
                $this->Ln();

            }
        }
    }

    $pdf = new PDF();
    $pdf->AddPage();
    
    $pdf->headerTable();
    $pdf->viewTable();
    ///$pdf->Cell(40,10,'hola este es un prueba!');
    $pdf->Output();
    
?>