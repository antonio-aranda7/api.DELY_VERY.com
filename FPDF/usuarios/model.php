<?php
    # Importar modelo de abstracción de base de datos
    //require_once('../core/db_abstract_model.php');
    //require_once('D:\XAMPP\htdocs\MVC\mysql_table\mysql_table.php');
    require_once('..\core\mysql_table.php');

    class Usuario {

        # Reportar un usuario
        public function reporte() {

            //Aqui es para que agarre el FPDF
            $link = mysqli_connect('localhost','root','','dbmvc');

            $pdf = new PDF();
            $pdf->AddPage("P");
            // First table: output all columns
            $pdf->Table($link,'SELECT nombre, apellido, email FROM usuarios ORDER BY email');
            $pdf->Output();
        }

        # Método constructor
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
            $this->Cell(0,6,'Listado de Usuarios',0,1,'C');
            $this->Ln(10);
            // Ensure table header is printed
            parent::Header();
        }
    }
        
?>