<?php

    //require('fpdf/fpdf.php');
    //require('controller2.php');
    require('mysql_table\mysql_table.php');
    //require('db_abstract_model.php');

    class producto 
    {

        # Reportar un capa
        public function reporte() {
        $link = mysqli_connect('localhost','root','','delyvery');

            $pdf = new PDF();
            $pdf->AddPage("P");
            // First table: output all columns
            $pdf->Table($link,'SELECT id_producto, titulo, imagen, precio, descripcion FROM productos ORDER BY id_producto');
            $pdf->Output();
        }
        
        function __construct() {
            //$this->db_name = 'book_example';
        }

    }
    
    class PDF extends PDF_MySQL_Table
    {
        function Header()
        {
            // Title
            $this->SetFont('Arial','',18);
            $this->Cell(0,6,'Listado de Productos',0,1,'C');
            $this->Ln(10);
            // Ensure table header is printed
            parent::Header();
        }
    }
            
?>